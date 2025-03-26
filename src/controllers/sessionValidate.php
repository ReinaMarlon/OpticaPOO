<?php
session_start();
include_once '../models/Auth.php';
include_once '../models/User.php';
include_once '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $userpassword = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    if (empty($username) || empty($userpassword)) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
        exit;
    }

    $auth = new Auth($username, $userpassword);

    $conn = new Conn($auth->getUsername(), $auth->getPassword());
    $validate = $conn->getAuthUserData();
    if($conn){
        $user = new User($validate['idUsers'], $validate['nombreUsuario'], $validate['idRol']);
        echo json_encode(["success" => true, "redirect" => "dashboard.php"]);
        $_SESSION['UserID'] = $user->getUserID();
        $_SESSION['Username'] = $user->getUsername();
        $_SESSION['UserRolID'] = $user->getRolID();
    }

    
}
