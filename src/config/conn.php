<?php
include_once '../models/User.php';

class Conn
{

    private $user;
    private $host;
    private $bd;
    private $password;
    private $pdo;

    public function __construct($username, $userpassword)
    {
        $host = 'localhost';
        $bd = 'poo_conn';
        $this->user = $username;
        $this->host = $host;
        $this->bd = $bd;
        $this->password = $userpassword;
        $this->connect();
        
    }

    public function connect()
    {
        try {
            // Conexión correcta a MySQL con PDO
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->bd}", $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Conexión exitosa";
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    //Obtener la conección, es util para utilizarla en otras clases.
    public function getConnect()
    {
        return $this->pdo;
    }

    public function getAuthUserData()
    {
        $sql = "SELECT idUsers, nombreUsuario, nombreContrasena, idRol FROM tbl_users WHERE nombreUsuario = :nombreUsuario";
        $stmt = $this->getConnect()->prepare($sql);
        $stmt->bindParam(':nombreUsuario', $this->user, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }
}
