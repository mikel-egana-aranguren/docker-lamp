
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar sesión</h2>
        <div id="login_messages"></div>
        <form id="login_form" method="POST" action="login_action.php">
            <input name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button id="login_submit" type="submit">Entrar</button>
        </form>
        <div class="links">
            <a href="register.php">¿No tienes cuenta? Regístrate aquí</a>
        </div>
    </div>

    <script>
        // Handle navigation links
        document.querySelectorAll('.links a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                window.location.href = href.replace('.php', '');
            });
        });
        
        const loginForm = document.getElementById('login_form');
        const loginMessages = document.getElementById('login_messages');
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            loginMessages.innerHTML = '';
            const data = new FormData(loginForm);
            try {
                    // Usar URL absoluta para evitar problemas si la página está en /login (sin .php)
                    const actionUrl = new URL(loginForm.action, window.location.href).href;
                    const resp = await fetch(actionUrl, {
                        method: 'POST',
                        body: data
                    });
                const json = await resp.json();
                if (json.status === 'ok') {
                    // Eliminar la extensión .php de la redirección
                    window.location.href = json.redirect.replace('.php', '');
                } else {
                    loginMessages.innerHTML = '<div class="alert">' + json.message + '</div>';
                }
            } catch (err) {
                loginMessages.innerHTML = '<div class="alert">Error de conexión</div>';
            }
        });
    </script>
</body>
</html>
