document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("item_modify_form");
  const priceInput = document.querySelector('input[name="precio"]');

  if (!form || !priceInput) {
    console.warn("⚠️ No se encontró el formulario o el campo de precio");
    return;
  }

  form.addEventListener("submit", function(event) {
      const priceStr = priceInput.value.trim();
      const priceRegex = /^\d+$/; // solo dígitos, sin puntos ni comas

      if (!priceRegex.test(priceStr)) {
          alert("El formato del precio no es correcto (solo números sin puntos ni comas).");
          event.preventDefault();
      } else if (parseInt(priceStr, 10) <= 0) {
          alert("Por favor, introduce un precio mayor que 0");
          event.preventDefault();
      }
  });
});

