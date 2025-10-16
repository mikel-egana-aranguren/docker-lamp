// Validación en cliente para /modify_user (requisito de la guía)
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("user_modify_form");
  if (!form) return;

  const msg = document.createElement("p");
  msg.style.color = "red";
  form.appendChild(msg);

  function error(t) { msg.textContent = t; }
  function emailOk(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
  function letraDNI(num8) {
    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    const n = parseInt(num8, 10);
    if (Number.isNaN(n)) return "";
    return letras[n % 23];
  }

  form.addEventListener("submit", (e) => {
    msg.textContent = "";

    const nombre   = form.nombre.value.trim();
    const apellido = form.apellido.value.trim();
    const numDni   = form.numDni.value.trim();
    const letra    = form.letraDni.value.trim().toUpperCase();
    const tlfn     = form.tlfn.value.trim();
    const fecha    = form.fNacimiento.value;
    const email    = form.email.value.trim();

    if (nombre.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(nombre)) {
      e.preventDefault(); return error("Nombre inválido.");
    }
    if (apellido.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(apellido)) {
      e.preventDefault(); return error("Apellido inválido.");
    }
    if (!/^\d{8}$/.test(numDni)) {
      e.preventDefault(); return error("El DNI debe tener 8 dígitos.");
    }
    if (!/^[A-Z]$/.test(letra) || letra !== letraDNI(numDni)) {
      e.preventDefault(); return error("La letra del DNI no coincide.");
    }
    if (!/^\d{9}$/.test(tlfn)) {
      e.preventDefault(); return error("El teléfono debe tener 9 dígitos.");
    }
    if (!fecha) {
      e.preventDefault(); return error("Selecciona una fecha de nacimiento.");
    }
    if (!emailOk(email)) {
      e.preventDefault(); return error("Email no válido.");
    }
  });
});
