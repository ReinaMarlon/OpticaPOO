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

    $conn = Conn::getInstance($auth->getUsername(), $auth->getPassword());
    
    if (!$conn->getConnect()) {
        echo json_encode(["success" => false, "message" => "Error de conexión: " . $conn->getError()]);
        exit;
    }
    
    $validate = $conn->getAuthUserData();
    
    if($validate) {
        $user = new User($validate['idUsers'], $validate['nombreUsuario'], $validate['idRol']);
        $_SESSION['UserID'] = $user->getUserID();
        $_SESSION['Username'] = $user->getUsername();
        $_SESSION['uPass'] = $auth->getPassword();
        $_SESSION['UserRolID'] = $user->getRolID();
        echo json_encode(["success" => true, "redirect" => "dashboard.php"]);
    } else {
        echo json_encode(["success" => false, "message" => "Usuario o contraseña incorrectos"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método de solicitud no válido"]);
}
