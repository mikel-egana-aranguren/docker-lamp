document.addEventListener('DOMContentLoaded', function () {
const form = document.getElementById("register_form");
const nameInput = document.getElementById("name");
const surnamesInput = document.getElementById("surnames");
const dniInput = document.getElementById("dni");
const emailInput = document.getElementById("email");
const tlfnInput = document.getElementById("tlfn");

form.addEventListener("submit", function(event) {
    const name = nameInput.value.trim();
    const surnames = surnamesInput.value.trim();
    const dni = dniInput.value.trim();
    const email = emailInput.value.trim();
    const tlfn = tlfnInput.value.trim();

    const nameRegex = /^[A-Za-z]+$/;
    const surnamesRegex = /^[A-Za-zÀ-ÿ ]+$/;
    const dniRegex = /^[0-9]{8}[-\s]?[A-Z]$/i;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const tlfnRegex = /^[679]\d{8}$/;

    if (!nameRegex.test(name)) {
        alert("Por favor, introduce un nombre válido.");
        event.preventDefault();
    } else if (!surnamesRegex.test(surnames)) {
        alert("Por favor, introduce apellidos válidos.");
        event.preventDefault();
    } else if (!dniRegex.test(dni)) {
    	alert("Por favor, introduce un DNI válido.");
        event.preventDefault();
    } else if (!emailRegex.test(email)) {
    	alert("Por favor, introduce un correo válido (usuario@servidor.extension).");
        event.preventDefault();
    } else if (!tlfnRegex.test(tlfn)) {
    	alert("Por favor, introduce un teléfono válido (Ejemplo ; 600123456)");
        event.preventDefault();
    }
});
});
