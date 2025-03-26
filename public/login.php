<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <?php
            // echo $hashedPassword = password_hash('alejandro123', PASSWORD_DEFAULT);

        ?>
        <h2>Iniciar Sesión</h2>
        <form id="loginForm">
            <input type="text" id="username" placeholder="Usuario" required>
            <input type="password" id="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
            <p id="error-msg" class="error"></p>
        </form>
    </div>
    <script src="assets/js/login.js"></script>
</body>
</html>
