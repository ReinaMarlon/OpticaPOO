<?php

class Auth
{
    private $username;
    private $password;

    // Constructor con parámetros opcionales
    public function __construct($username = "", $password = "")
    {
        $this->username = $username;
        $this->password = $password;
    }

    // Getter para username
    public function getUsername()
    {
        return $this->username;
    }

    // Setter para username
    public function setUsername($username)
    {
        $this->username = $username;
    }

    // Getter para password
    public function getPassword()
    {
        return $this->password;
    }

    // Setter para password
    public function setPassword($password)
    {
        // Se recomienda hashear la contraseña antes de guardarla
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

}
?>