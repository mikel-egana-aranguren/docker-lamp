document.addEventListener('DOMContentLoaded', function() {
    const userButton = document.querySelector('.user-button');
    const dropdown = document.querySelector('.user-dropdown-content');

    if (userButton) {
        userButton.addEventListener('click', function(e) {
            e.stopPropagation(); // Evitar que se cierre al hacer click en el botón
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Cerrar el menú si se hace click fuera
        window.addEventListener('click', function() {
            dropdown.style.display = 'none';
        });
    }
    
    function confirmDelete(id) {
      if (confirm("¿Estás seguro de que deseas eliminar este ítem?")) {
        window.location.href = "delete_item.php?id=" + id;
      }
    }
});


