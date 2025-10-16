/** Lo que se valida aquí son cosas rollo formato y tal, no que ya exista en la BD (eso se hace en register.php)
*/
document.addEventListener("DOMContentLoaded", () => { //a esto no se le llama, está escuchando durante todo el tiempo 
  // Seleccionamos el formulario y el contenedor de errores
  const formR = document.forms["register_form"]; //para registro
  //const formL = document.forms["login_form"]; //para login

  const mensajeError = document.createElement("p");
  mensajeError.style.color = "red";

  formR.appendChild(mensajeError);
  //formL.appendChild(mensajeError.cloneNode(true));
  formR.addEventListener("submit", (e) => { 
    e.preventDefault(); // Detener el envío hasta validar

    // Obtener los valores del formulario
    const nombre = formR.nombre.value.trim();
    const apellido = formR.apellido.value.trim();
    const numDni = formR.numeroDni.value.trim();
    const letraDni = formR.letraDni.value.trim().toUpperCase();
    const tlfn = formR.tlfn.value.trim();
    const fNacimiento = formR.fNacimiento.value;
    const email = formR.email.value.trim();
    const usuario = formR.usuario.value.trim();
    const contrasena = formR.contrasena.value;

    //validar:
    if (nombre.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ]+$/.test(nombre))
      return mostrarError("El nombre debe tener al menos 2 letras y solo contener letras.");

    if (apellido.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ]+$/.test(apellido))
      return mostrarError("El apellido debe tener al menos 2 letras y solo contener letras.");

    if (!/^\d{8}$/.test(numDni))
      return mostrarError("El número de DNI debe tener 8 dígitos.");

    if (!/^[A-Z]$/.test(letraDni))
      return mostrarError("La letra del DNI debe ser una sola letra mayúscula.");

    if (!/^\d{9}$/.test(tlfn))
      return mostrarError("El teléfono debe tener 9 dígitos.");

    if (!validarFechaNacimiento(fNacimiento))
      return mostrarError("Debes tener al menos 18 años.");

    if (!validarEmail(email))
      return mostrarError("El correo electrónico no es válido.");

    if (usuario.length < 3)
      return mostrarError("El usuario debe tener al menos 3 caracteres.");

    if (!validarContrasena(contrasena))
      return mostrarError("La contraseña debe tener al menos 6 caracteres y una mayúscula.");

    // Si todo es correcto, limpiar errores y enviar
    mensajeError.textContent = "";
    formR.submit();
  });
  function mostrarError(msg) {
    mensajeError.textContent = msg;
  }

  function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }

  function validarContrasena(pass) {
    return pass.length >= 6 && /[A-Z]/.test(pass);
  }

 function validarFechaNacimiento(fechaStr) {
    return !!fechaStr; // devuelve true si hay algo, false si está vacío. Bien, va a estar bn porque se despliega un calendario para seleccionar fecha
}
});