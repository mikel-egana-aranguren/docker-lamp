document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById("login_form");
    const emailInput = document.getElementById("email");

    form.addEventListener("submit", function(event) {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            alert("Por favor, introduce un correo válido (usuario@servidor.extension).");
            event.preventDefault(); // detiene envío si correo inválido
        }
        // Las demás validaciones se hacen en PHP
    });
});
