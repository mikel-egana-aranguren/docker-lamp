<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Registro</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro de usuario</h2>
        <div id="messages"></div>
        <form id="register_form" method="POST" action="register_action.php">
            <input name="nombre" placeholder="Nombre" required>
            <input name="apellidos" placeholder="Apellidos" required>
            <input name="dni" placeholder="11111111-Z" required>
            <input name="telefono" placeholder="Teléfono (9 dígitos)" required>
            <input type="date" name="fecha" required>
            <input type="email" name="email" placeholder="Email" required>
            <input name="usuario" placeholder="Nombre de usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button id="register_submit" type="submit">Registrar</button>
        </form>
        <div class="links"><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></div>
    </div>

    <script src="js/validation.js"></script>
    <script>
        // Handle navigation links
        document.querySelectorAll('.links a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                window.location.href = href.replace('.php', '');
            });
        });

        // Attach submit handler to use client-side validation and AJAX posting
const regForm = document.getElementById('register_form');
        const regMessages = document.getElementById('register_messages');
        regForm.addEventListener('submit', async function(e){
            e.preventDefault();
            regMessages.innerHTML = '';
            if (!validarRegistro()) return;
            const data = new FormData(regForm);
            // Usar URL absoluta para evitar problemas si la página está en /register (sin .php)
            const actionUrl = new URL(regForm.action, window.location.href).href;
            const resp = await fetch(actionUrl, { method: 'POST', body: data });
            const json = await resp.json();
            if (json.status === 'ok') {
                messages.innerHTML = '<div class="alert success">' + json.message + '</div>';
                if (json.redirect) {
                    // Eliminar la extensión .php de la redirección
                    window.location.href = json.redirect.replace('.php', '');
                } else {
                    form.reset();
                }
            } else {
                messages.innerHTML = '<div class="alert">' + json.errors.join('<br>') + '</div>';
            }
        });
    </script>
</body>
</html>
