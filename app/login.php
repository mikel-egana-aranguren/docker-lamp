<form id="login_form" action="procesar_login.php" method="POST">
    <label for="nombre_usuario">Nombre de Usuario:</label>
    <input type="text" id="nombre_usuario" name="nombre_usuario" required>

    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="contrasena" required>

    <input id="login_submit" type="submit" value="Iniciar Sesión">
</form>

<!-- Incluyendo archivo JS para validaciones de login -->
<script src="js/login.js"></script>
