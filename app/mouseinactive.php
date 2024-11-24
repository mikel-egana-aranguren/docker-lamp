let logoutTimer = null;
let inactivityCountdown = null;

document.addEventListener('mousemove', function() {
    // Cancelar cualquier temporizador activo
    if (logoutTimer) {
        clearTimeout(logoutTimer);
    }
    if (inactivityCountdown) {
        clearInterval(inactivityCountdown);
    }

    // Establecer un nuevo temporizador para detectar inactividad
    logoutTimer = setTimeout(function() {
        let countdown = 60;  // 1 minuto en segundos

        // Iniciar la cuenta regresiva para cierre de sesión
        inactivityCountdown = setInterval(function() {
            countdown--;

            if (countdown <= 0) {
                clearInterval(inactivityCountdown);
                window.location.href = 'logout.php';  // Redirigir a logout
            }
        }, 1000); // Contar cada segundo
    }, 100);  // Esperar 100ms de inactividad antes de iniciar la cuenta regresiva
});
