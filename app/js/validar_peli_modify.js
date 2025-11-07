// Validación en cliente para

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("modify_item_form");
  if (!form) return;

  const msg = document.createElement("p");
  const fecha = new Date();
  const currentYear = fecha.getFullYear;
  msg.style.color = "red";
  form.appendChild(msg);

  function error(t) { msg.textContent = t; }

  form.addEventListener("submit", (e) => {
    msg.textContent = "";

    const titulo   = form.titulo.value.trim();
    const anio = form.anio.value.trim();
    const director   = form.director.value.trim();
    const genero    = form.genero.value.trim().toUpperCase();
    const duracion     = form.duracion.value.trim();

    if (titulo.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(titulo)) {
      e.preventDefault(); return error("El título debe tener al menos 2 caracteres.");
    }
    if (anio == "" || (!/^\d{4}$/.test(anio) || parseInt(anio) < 1900 || parseInt(anio) > currentYear)) {
      e.preventDefault(); return error("El año debe ser entre 1900 y el año actual.");
    }
    if (director.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(director)) {
      e.preventDefault(); return error("El director debe tener al menos 2 caracteres.");
    }
    if (genero.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(genero)) {
      e.preventDefault(); return error("El género debe tener al menos 2 caracteres.");
    }
    if (anio == "" || !/^\d+$/.test(duracion) || parseInt(duracion) < 30 || parseInt(duracion) >= 52000) {
      e.preventDefault(); return error("La duración debe ser un número entero positivo asequible. El número mínimo es 30.");
    }

  });
});