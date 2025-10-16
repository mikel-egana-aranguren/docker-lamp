document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("user_modify_form");
  const nombreInput = document.getElementById("nombre");
  const apellidosInput = document.getElementById("apellidos");
  const dniInput = document.getElementById("dni");
  const correoInput = document.getElementById("correo");
  const telefonoInput = document.getElementById("telefono");
  const fechaInput = document.getElementById("fecha_nacimiento");
  const passwdInput = document.getElementById("contrasena");
  const passwdRepeatInput = document.getElementById("contrasena_repeat");

  form.addEventListener("submit", function (event) {
    const nombre = nombreInput.value.trim();
    const apellidos = apellidosInput.value.trim();
    const dni = dniInput.value.trim();
    const correo = correoInput.value.trim();
    const telefono = telefonoInput.value.trim();
    const fecha = fechaInput.value.trim();
    const passwd = passwdInput.value.trim();
    const passwdRepeat = passwdRepeatInput.value.trim();

    const nombreRegex = /^[A-Za-zÀ-ÿ ]+$/;
    const apellidosRegex = /^[A-Za-zÀ-ÿ ]+$/;
    const dniRegex = /^[0-9]{8}[A-Za-z]$/;
    const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const telefonoRegex = /^[0-9]{9}$/;
    const fechaRegex = /^\d{4}-\d{2}-\d{2}$/; // formato YYYY-MM-DD

    if (!nombreRegex.test(nombre)) {
      alert("El nombre no es válido.");
      event.preventDefault();
      return;
    }

    if (!apellidosRegex.test(apellidos)) {
      alert("Los apellidos no son válidos.");
      event.preventDefault();
      return;
    }

    if (!dniRegex.test(dni)) {
      alert("El DNI debe tener 8 números y 1 letra (sin guion).");
      event.preventDefault();
      return;
    }

    if (!correoRegex.test(correo)) {
      alert("El correo electrónico no es válido.");
      event.preventDefault();
      return;
    }

    if (!telefonoRegex.test(telefono)) {
      alert("El teléfono debe tener 9 dígitos.");
      event.preventDefault();
      return;
    }

    if (!fechaRegex.test(fecha)) {
      alert("La fecha de nacimiento no es válida.");
      event.preventDefault();
      return;
    }

    if (passwd !== "" && passwd !== passwdRepeat) {
      alert("Las contraseñas no coinciden.");
      event.preventDefault();
      return;
    }

    // Si pasa todas las validaciones, el formulario se envía normalmente
  });
});

