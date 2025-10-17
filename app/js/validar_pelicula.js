document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector('.add-peli-dialog form');
  const boton = null;
  if (!form) return;

  const msg = document.createElement("p");
  msg.style.color = "red";
  form.appendChild(msg);

  function error(t) { msg.textContent = t; }

  form.addEventListener("submit", (e) => {
    msg.textContent = "";

    const titulo   = form.titulo.value.trim();
    const anio     = form.anio.value.trim();
    const director  = form.director.value.trim();
    const genero   = form.genero.value.trim();
    const duracion = form.duracion.value.trim();

    if (titulo.length < 2) {
      e.preventDefault(); return error("El título debe tener al menos 2 caracteres.");
    }
    if (!/^\d{4}$/.test(anio) || anio < 1900 || anio > new Date().getFullYear()) {
      e.preventDefault(); return error("El año debe ser un número de 4 dígitos válido.");
    }
    if (director.length < 2) {
      e.preventDefault(); return error("El director debe tener al menos 2 caracteres.");
    }
    if (genero.length < 2) {
      e.preventDefault(); return error("El género debe tener al menos 2 caracteres.");
    }
    if (!/^\d+$/.test(duracion) || duracion <= 30 || duracion >= 52000) {
      e.preventDefault(); return error("La duración debe ser un número positivo asequible.");
    }
  });
});