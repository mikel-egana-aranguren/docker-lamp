// Validación en cliente para /modify_user (requisito de la guía)
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("user_modify_form");
  let numbien = false;
  let letrabien = false;
  if (!form) return;

  const msg = document.createElement("p");
  msg.style.color = "red";
  form.appendChild(msg);

  function error(t) { msg.textContent = t; }
  function emailOk(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

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
      e.preventDefault(); return error("El nombre debe tener al menos 2 caracteres.");
    }
    if (apellido.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(apellido)) {
      e.preventDefault(); return error("El apellido debe tener al menos 2 caracteres.");
    }

    if (!/^\d{8}$/.test(numDni)) {
      e.preventDefault(); return error("El DNI debe tener 8 dígitos.");
    }
    else{numbien=true;}

    if (!/^[A-Z]$/.test(letra) ) {
      e.preventDefault(); return error("El caracter debe ser una letra.");
    }
    else{letrabien=true;}

    if (!/^\d{9}$/.test(tlfn)) {
      e.preventDefault(); return error("El teléfono debe tener 9 dígitos.");
    }
    if (!fecha || fecha.getFullYear() < 1915 || fecha.getFullYear() > new Date().getFullYear()) {
      e.preventDefault(); return error("Introduzca una fecha entre 1915 y el año actual.");
    }
    if (!emailOk(email)) {
      e.preventDefault(); return error("Email no válido.");
    }

    //Comprobar que la letra del DNI corresponde al numero
    if(letrabien && numbien)
    {
      const numero = parseInt(numDni, 10);
      resto = numero % 23;
      dniletras = ['T','R','W','A','G','M','Y','F','P','D','X','B','N','J','Z','S','Q','V','H','L','C','K','E'];
      if(letra != dniletras[resto])
      {
        e.preventDefault(); return error("La letra del DNI debe ser una letra correspondiente al numero");
      }

    }

  });
});
