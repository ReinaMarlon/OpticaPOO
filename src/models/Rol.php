<?php

class Rol
{
    private $idRol;
    private $nombreRol;

    public function __construct($idRol = null, $nombreRol = null, $descripcion = null, $permisos = [])
    {
        $this->idRol = $idRol;
        $this->nombreRol = $nombreRol;
    }

    // Getters
    public function getIdRol()
    {
        return $this->idRol;
    }

    public function getNombreRol()
    {
        return $this->nombreRol;
    }
    // Setters
    public function setIdRol($idRol)
    {
        $this->idRol = $idRol;
    }

    public function setNombreRol($nombreRol)
    {
        $this->nombreRol = $nombreRol;
    }
    public static function getRolById($conn, $idRol)
    {
        try {
            $sql = "SELECT * FROM tbl_roles WHERE idRoles = :idRoles";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idRoles', $idRol, PDO::PARAM_INT);
            $stmt->execute();
            
            $rolData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($rolData) {
                return new Rol(
                    $rolData['idRoles'],
                    $rolData['nombreRol']
                );
            }
            
            return null;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getAllRoles($conn)
    {
        try {
            $sql = "SELECT * FROM tbl_roles";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            
            $roles = [];
            while ($rolData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                $roles[] = new Rol(
                    $rolData['idRoles'],
                    $rolData['nombreRol']
                );
            }
            
            return $roles;
        } catch (PDOException $e) {
            // Handle error
            return [];
        }
    }
}