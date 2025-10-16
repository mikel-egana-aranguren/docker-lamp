document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("item_add_form");
  const nameInput = document.getElementById("item_name");
  const priceInput = document.getElementById("item_price");

  form.addEventListener("submit", function(event) {
      const name = nameInput.value.trim();
      const price = parseFloat(priceInput.value);

      if (name === "" || isNaN(price) || price <= 0) {
          alert("Por favor, introduce un nombre válido y un precio mayor que 0.");
          event.preventDefault();
      }
  });
});

