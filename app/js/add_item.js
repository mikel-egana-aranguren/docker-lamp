document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("item_add_form");
  const nameInput = document.getElementById("item_name");
  const priceInput = document.getElementById("precio");
  const yearInput = document.getElementById("item_year");
  const caballosInput = document.getElementById("item_caballos");

  form.addEventListener("submit", function(event) {
      const name = nameInput.value.trim();
      const caballosStr = caballosInput.value.trim();
      const priceStr = priceInput.value.trim();
      const yearStr = yearInput ? yearInput.value.trim() : "";
      const priceRegex = /^\d+$/;
      const yearRegex = /^\d{4}$/;

      // --- Validación del nombre ---
      if (name === "") {
          alert("Por favor, introduce un nombre válido.");
          event.preventDefault();
          return;
      }

      // Dividimos el nombre en palabras ignorando espacios extra
      const words = name.split(/\s+/).filter(word => word.length > 0);
      if (words.length < 2) {
          alert("El nombre debe contener al menos dos palabras: marca y modelo.");
          event.preventDefault();
          return;
      }
      
      // --- Validación de los caballos ---
      const caballos = parseInt(caballosStr, 10);
      if (caballos < 1 || caballos > 2001) {
          alert("Los caballos deben estar entre 1 y 2000.");
          event.preventDefault();
          return;
      }
      
      // --- Validación del precio ---
      if (!priceRegex.test(priceStr)) {
          alert("El formato del precio no es correcto (solo números sin puntos ni comas).");
          event.preventDefault();
          return;
      }
      
      if (priceStr.length > 12) {
          alert("El precio no puede tener más de 12 cifras.");
          event.preventDefault();
          return;
      }

      if (priceStr.length > 12) {
          alert("El precio no puede tener más de 12 cifras.");
          event.preventDefault();
          return;
      }

      const price = parseInt(priceStr, 10);
      if (price <= 0) {
          alert("Por favor, introduce un precio mayor que 0.");
          event.preventDefault();
          return;
      }

      // --- Validación del año ---
      if (yearStr !== "") {
          if (!yearRegex.test(yearStr)) {
              alert("Por favor, introduce un año válido de 4 cifras.");
              event.preventDefault();
              return;
          }

          const year = parseInt(yearStr, 10);
          if (year < 1886 || year > 9999) {
              alert("El año debe estar entre 1886 y 9999.");
              event.preventDefault();
              return;
          }
      }
  });
});

