function validarRegistro() {
  const dni = document.querySelector("[name='dni']").value.trim();
  const telefono = document.querySelector("[name='telefono']").value.trim();
  const dniRegex = /^\d{8}-[A-Z]$/;
  const telRegex = /^\d{9}$/;

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

  return true;
}

