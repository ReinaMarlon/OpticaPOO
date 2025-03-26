<?php
session_start();
if (!isset($_SESSION['UserID']) || empty($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

require_once '../src/models/Rol.php';
require_once '../src/config/conn.php';

$userRolID = $_SESSION['UserRolID'];
$username = $_SESSION['Username'];

$conn = Conn::getInstance($_SESSION['Username'], $_SESSION['uPass']);
$dbConn = $conn->getConnect();

$rol = Rol::getRolById($dbConn, $userRolID);
$rolName = $rol ? $rol->getNombreRol() : "Usuario";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lafam Optica - Dashboard</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <div class="dashboard-container">
        <header>
            <h1>Bienvenido, <?php echo htmlspecialchars($username); ?></h1>
            <p>Rol: <?php echo htmlspecialchars($rolName); ?></p>
            <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
        </header>
        
        <main>
            <?php
            switch ($userRolID) {
                case 1:
                    include_once '../src/views/admin_dashboard.php';
                    break;
                case 2:
                    include_once '../src/views/optometrist_dashboard.php';
                    break;
                case 3:
                    include_once '../src/views/seller_dashboard.php';
                    break;
                case 4:
                    include_once '../src/views/cashier_dashboard.php';
                    break;
                default:
                    include_once '../src/views/default_dashboard.php';
                    break;
            }
            ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/dashboard.js"></script>
</body>

</html>