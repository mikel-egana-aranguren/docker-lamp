document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("user_modify_form");
  const nombreInput = document.getElementById("nombre");
  const apellidosInput = document.getElementById("apellidos");
  const dniInput = document.getElementById("dni");
  const correoInput = document.getElementById("correo");
  const telefonoInput = document.getElementById("telefono");
  const fechaInput = document.getElementById("fecha_nacimiento");
  const passwdInput = document.getElementById("contrasena");
  const passwdRepeatInput = document.getElementById("contrasena_repeat");

  // Crear un contenedor para mostrar errores
  const msgBox = document.createElement("div");
  msgBox.style.marginTop = "15px";
  msgBox.style.fontWeight = "bold";
  form.parentElement.insertBefore(msgBox, form);

  const showMessage = (text, color = "red") => {
    msgBox.textContent = text;
    msgBox.style.color = color;
  };

  const clearMessage = () => {
    msgBox.textContent = "";
  };

  // Limpiar mensaje al escribir
  form.querySelectorAll("input").forEach(input => {
    input.addEventListener("input", clearMessage);
  });

  // Función auxiliar para validar fecha real
  const esFechaValida = (fecha) => {
    const partes = fecha.split("-");
    if (partes.length !== 3) return false;
    const [año, mes, dia] = partes.map(Number);
    const date = new Date(año, mes - 1, dia);
    return (
      date.getFullYear() === año &&
      date.getMonth() === mes - 1 &&
      date.getDate() === dia
    );
  };

  form.addEventListener("submit", function (event) {
    const nombre = nombreInput.value.trim();
    const apellidos = apellidosInput.value.trim();
    const dni = dniInput.value.trim().toUpperCase();
    const correo = correoInput.value.trim();
    const telefono = telefonoInput.value.trim();
    const fecha = fechaInput.value.trim();
    const passwd = passwdInput.value.trim();
    const passwdRepeat = passwdRepeatInput.value.trim();

    const nombreRegex = /^[A-Za-zÀ-ÿ ]+$/;
    const apellidosRegex = /^[A-Za-zÀ-ÿ ]+$/;
    const dniRegex = /^(\d{8})([A-Za-z])$/;
    const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const telefonoRegex = /^[0-9]{9}$/;
    const fechaRegex = /^(19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/;

    if (!nombreRegex.test(nombre)) {
      showMessage("El nombre no es válido.");
      event.preventDefault();
      return;
    }

    if (!apellidosRegex.test(apellidos)) {
      showMessage("Los apellidos no son válidos.");
      event.preventDefault();
      return;
    }

    const dniMatch = dni.match(dniRegex);
    if (!dniMatch) {
      showMessage("El DNI debe tener 8 números y 1 letra (sin guion).");
      event.preventDefault();
      return;
    }

    // Validar letra del DNI
    const dniNumbers = parseInt(dniMatch[1], 10);
    const dniLetter = dniMatch[2].toUpperCase();
    const letters = "TRWAGMYFPDXBNJZSQVHLCKE";
    const correctLetter = letters[dniNumbers % 23];
    if (dniLetter !== correctLetter) {
      showMessage("DNI inválido");
      event.preventDefault();
      return;
    }

    if (!correoRegex.test(correo)) {
      showMessage("El correo electrónico no es válido.");
      event.preventDefault();
      return;
    }

    if (!telefonoRegex.test(telefono)) {
      showMessage("El teléfono debe tener 9 dígitos.");
      event.preventDefault();
      return;
    }

    if (!fechaRegex.test(fecha) || !esFechaValida(fecha)) {
      showMessage("La fecha de nacimiento no es válida.");
      event.preventDefault();
      return;
    }

    if (passwd !== "" && passwd !== passwdRepeat) {
      showMessage("Las contraseñas no coinciden.");
      event.preventDefault();
      return;
    }

    // Si todo está bien, mensaje temporal en verde antes del envío
    showMessage("Validando datos...", "green");
  });
});

