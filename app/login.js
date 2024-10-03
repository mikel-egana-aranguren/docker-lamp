// Validar que los campos no estén vacíos
document.getElementById('login_form').onsubmit = function() {
    const nombreUsuario = document.getElementById('nombre_usuario').value;
    const contrasena = document.getElementById('contrasena').value;

    if (nombreUsuario.trim() === "" || contrasena.trim() === "") {
        alert('Por favor, completa ambos campos.');
        return false;
    }

    return true;
}
