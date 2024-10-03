// Validación del formulario de registro
document.getElementById('register_form').onsubmit = function() {
    const dni = document.getElementById('dni').value;
    const telefono = document.getElementById('telefono').value;

    // Validar DNI
    if (!/^\d{8}-[A-Z]$/.test(dni)) {
        alert('DNI debe tener el formato 12345678-Z');
        return false;
    }

    // Validar Teléfono
    if (!/^\d{9}$/.test(telefono)) {
        alert('El teléfono debe tener 9 dígitos');
        return false;
    }

    // Validaciones adicionales como email, fecha, etc. se pueden agregar aquí.
    
    return true; // Si todas las validaciones pasan, el formulario se enviará.
}
