<?php
// Conexión a la base de datos
require 'conexion.php'; // Asegúrate de que 'conexion.php' está bien configurado

// Consulta para obtener los usuarios
$query = mysqli_query($conn, "SELECT * FROM usuarios") or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Sistema de Usuarios</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Vinculando el CSS -->
</head>
<body>
    <header>
        <h1>Sistema de Gestión de Usuarios</h1>
        <nav>
            <ul>
                <li><a href="register.php">Registrar Usuario</a></li>
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li><a href="items.php">Ver Ítems</a></li>
                <li><a href="add_item.php">Añadir Ítem</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Lista de Usuarios</h2>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Recorre los resultados de la consulta y genera filas en la tabla
                while ($row = mysqli_fetch_array($query)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['apellidos']}</td>
                            <td>{$row['email']}</td>
                            <td>
                                <a href='show_user.php?user={$row['id']}'>Ver</a> |
                                <a href='modify_user.php?user={$row['id']}'>Modificar</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <footer>
        <p>© 2024 Sistema de Gestión de Usuarios</p>
    </footer>
</body>
</html>
