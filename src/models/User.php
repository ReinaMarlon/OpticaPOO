<?php
class User
{
    private $username;
    private $userID;
    private $rolID;

    // Constructor con parámetros opcionales
    public function __construct( $userID = 0, $username = "", $rolID = 0)
    {
        $this->username = $username;
        $this->userID = $userID;
        $this->rolID = $rolID;
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

    // Getter para userID
    public function getUserID()
    {
        return $this->userID;
    }

    // Setter para userID
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    // Getter para rolID
    public function getRolID()
    {
        return $this->rolID;
    }

    // Setter para rolID
    public function setRolID($rolID)
    {
        $this->rolID = $rolID;
    }
}
?>
