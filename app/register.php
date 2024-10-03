<form id="register_form" action="procesar_registro.php" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>
    
    <label for="apellidos">Apellidos:</label>
    <input type="text" id="apellidos" name="apellidos" required>
    
    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" required pattern="\d{8}-[A-Z]" title="Formato correcto: 12345678-Z">

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" required pattern="\d{9}" title="Debe tener 9 dígitos">

    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="nombre_usuario">Nombre de Usuario:</label>
    <input type="text" id="nombre_usuario" name="nombre_usuario" required>

    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="contrasena" required>

    <input id="register_submit" type="submit" value="Registrar">
</form>

<!-- Incluyendo archivo JS para validaciones de registro -->
<script src="js/registro.js"></script>
