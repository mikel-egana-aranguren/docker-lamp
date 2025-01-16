
    let logoutTimer = null;

    
    function startLogoutTimer() {
        
        if (logoutTimer) {
            clearTimeout(logoutTimer);
        }

        
        logoutTimer = setTimeout(function() {
            window.location.href = 'logout.php'; 
        }, 60000);  // 60 segundo hau aldatu dezakezu zure nahiaren arabera
    }

    function resetLogoutTimer() {
        startLogoutTimer();  
    }

    
    document.addEventListener('mousemove', resetLogoutTimer);
    document.addEventListener('keydown', resetLogoutTimer);
    document.addEventListener('click', resetLogoutTimer);

    
    startLogoutTimer();