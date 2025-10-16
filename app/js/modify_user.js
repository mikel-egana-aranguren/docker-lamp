document.addEventListener('DOMContentLoaded', function () {
const form = document.getElementById("user_modify_form");
const nameInput = document.getElementById("name");
const apelsInput = document.getElementById("Apels");
const dniInput = document.getElementById("dni");
const emailInput = document.getElementById("email");
const tlfInput = document.getElementById("tlf");
const fechaNctoInput = document.getElementById("fechaNcto");
const passInput = document.getElementById("passwd");
const passRepeatInput = document.getElementById("passwd_repeat");

function letraDNI(num) {
  const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
  return letras[num % 23];
}

form.addEventListener("submit", function(event) {
  const name = nameInput.value.trim();
  const apels = apelsInput.value.trim();
  const dni = dniInput.value.trim();
  const email = emailInput.value.trim();
  const tlf = tlfInput.value.trim();
  const fechaNcto = fechaNctoInput.value.trim();
  const p1 = passInput.value;
  const p2 = passRepeatInput.value;

  const nameRegex = /^[A-Za-zÀ-ÿ]+$/;
  const apelsRegex = /^[A-Za-zÀ-ÿ ]+$/;
  const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
  const tlfRegex = /^\\d{9}$/;
  const fechaRegex = /^\\d{4}-\\d{2}-\\d{2}$/;
  const dniRegex = /^(\\d{8})-([A-Za-z])$/;

  if (!nameRegex.test(name)) {
    alert("Por favor, introduce un nombre válido."); event.preventDefault(); return;
  }
  if (!apelsRegex.test(apels)) {
    alert("Por favor, introduce apellidos válidos."); event.preventDefault(); return;
  }
  if (!dniRegex.test(dni)) {
    alert("Por favor, introduce un DNI válido, debe ser 12345678-Z."); event.preventDefault(); return;
  } else {
    const m = dni.match(dniRegex);
    const numero = parseInt(m[1], 10);
    const letra = m[2].toUpperCase();
    if (letraDNI(numero) !== letra) {
      alert("La letra del DNI no corresponde al número.");
      event.preventDefault(); return;
    }
  }
    if (!emailRegex.test(email)) {
        alert("Por favor, introduce un correo válido (usuario@servidor.extension).");
        event.preventDefault();
    }
  if (!tlfRegex.test(tlf)) {
    alert("Por favor, introduce un teléfono válido, debe tener 9 dígitos."); event.preventDefault(); return;
  }
  if (!fechaRegex.test(fechaNcto)) {
    alert("Por favor, introduce una fecha válida, debe ser aaaa-mm-dd."); event.preventDefault(); return;
  }
  if (p1 !== "" || p2 !== "") {
    if (p1 !== p2) { alert("Las contraseñas no coinciden."); event.preventDefault(); return; }
    if (p1.length < 4) { alert("Contraseña demasiado corta (mín. 4)."); event.preventDefault(); return; }
  }
});
});
