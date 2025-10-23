function validarRegistro() {
  const dni = document.querySelector("[name='dni']").value.trim();
  const telefono = document.querySelector("[name='telefono']").value.trim();
    const nombre = document.querySelector("[name='nombre']").value.trim();
    const apellidos = document.querySelector("[name='apellidos']").value.trim();
    const usuario = document.querySelector("[name='usuario']").value.trim();
    const password = document.querySelector("[name='password']").value;
    const email = document.querySelector("[name='email']").value.trim();
  const dniRegex = /^\d{8}-[A-Z]$/;
  const telRegex = /^\d{9}$/;
    const nombreRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ '´`.-]{2,100}$/; // letters, spaces and common name chars
    const usuarioRegex = /^[A-Za-z0-9_\-]{3,50}$/;
    const passwordMinLen = 6;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!dniRegex.test(dni)) {
    alert("Formato DNI incorrecto (11111111-Z)");
    return false;
  }

  const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
  const numero = parseInt(dni.substring(0,8));
  const letra = dni.charAt(9);
  if (letras[numero % 23] !== letra) {
    alert("La letra del DNI no corresponde");
    return false;
  }

  if (!telRegex.test(telefono)) {
    alert("Teléfono debe tener 9 dígitos");
    return false;
  }

    if (!nombreRegex.test(nombre)) {
      alert("Nombre no válido. Solo letras y espacios, 2-100 caracteres.");
      return false;
    }

    if (!nombreRegex.test(apellidos)) {
      alert("Apellidos no válidos. Solo letras y espacios, 2-100 caracteres.");
      return false;
    }

    if (!usuarioRegex.test(usuario)) {
      alert("Nombre de usuario no válido. Solo letras, números, _ o - y 3-50 caracteres.");
      return false;
    }

    if (password.length < passwordMinLen) {
      alert("La contraseña debe tener al menos " + passwordMinLen + " caracteres.");
      return false;
    }

    if (!emailRegex.test(email)) {
      alert("Email no válido. Usa el formato ejemplo@servidor.ext");
      return false;
    }

  return true;
}

// make function global so inline scripts can call it
window.validarRegistro = validarRegistro;

