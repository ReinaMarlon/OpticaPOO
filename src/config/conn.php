<?php
include_once __DIR__ . '/../models/User.php';

class Conn
{
    private $user;
    private $host;
    private $bd;
    private $password;
    private $pdo;
    private $error;
    private static $instance = null;

    public function __construct($username, $userpassword)
    {
        $host = 'localhost';
        $bd = 'optica_poo';
        $this->user = $username;
        $this->host = $host;
        $this->bd = $bd;
        $this->password = $userpassword;
        $this->connect();
    }
    
    public static function getInstance($username, $userpassword)
    {
        if (self::$instance === null) {
            self::$instance = new self($username, $userpassword);
        }
        return self::$instance;
    }

    public function connect()
    {
        try {
            // Conexión correcta a MySQL con PDO
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->bd}", $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->pdo = null;
            return false;
        }
    }

    //Obtener la conección, es util para utilizarla en otras clases.
    public function getConnect()
    {
        return $this->pdo;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getAuthUserData()
    {
        if (!$this->pdo) {
            return ['success' => false, 'message' => $this->error];
        }
        
        try {
            $sql = "SELECT idUsers, nombreUsuario, nombreContrasena, idRol FROM tbl_users WHERE nombreUsuario = :nombreUsuario";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombreUsuario', $this->user, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result;
            } else {
                return ['success' => false, 'message' => 'Usuario no encontrado'];
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return ['success' => false, 'message' => $this->error];
        }
    }
}
