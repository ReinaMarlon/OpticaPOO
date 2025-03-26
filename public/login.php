<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <div class="body">
        <div class="login-container">
            <?php
            // $password = '1234';
            // $hashedPassword = hash('sha256', $password);
            // echo $hashedPassword;
            ?>
            <h2>Iniciar Sesión</h2>
            <form id="loginForm">
                <input type="text" id="username" placeholder="Usuario" required>
                <input type="password" id="password" placeholder="Contraseña" required>
                <button type="submit">Ingresar</button>
                <p id="error-msg" class="error"></p>
            </form>
        </div>
    </div>
    <script src="assets/js/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>