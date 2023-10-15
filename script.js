// Validación del formulario de registro
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let dni = document.getElementById('dni').value;

    if (!validateDNI(dni)) {
        alert('DNI inválido');
        e.preventDefault();
    }
});

function validateDNI(dni) {
    const letters = "TRWAGMYFPDXBNJZSQVHLCKE";
    const dniPattern = /^\d{8}[A-Za-z]$/; // Ahora sin el guion

    if (dniPattern.test(dni)) {
        let number = parseInt(dni.substr(0, 8), 10); // Convertir la cadena a un número
        let letter = dni.charAt(8).toUpperCase();
        if (letter === letters.charAt(number % 23)) {
            return true;
        }
    }
    return false;
}

// Modal de Edición de Asignaturas
let editButtons = document.querySelectorAll('.edit-asignatura');
let modal = document.getElementById('editModal');
let closeBtn = document.querySelector('.close-modal');

editButtons.forEach(function(button) {
    button.addEventListener('click', function(e) {
        let asignaturaId = e.target.getAttribute('data-id');
        // Aquí puedes agregar el código para cargar los datos actuales de la asignatura en el modal
        document.getElementById('editAsignaturaId').value = asignaturaId;
        modal.style.display = 'block';
    });
});

closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
});

// Cierra el modal al hacer clic fuera de él
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

