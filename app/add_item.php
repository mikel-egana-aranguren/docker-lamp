<?php
echo '
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Añadir Item</title>

<style>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
  }

  .container {
    display: grid;
    place-items: center;
    height: 100vh;
    box-sizing: border-box;
  }

  .content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 50px;
    font-family: Arial, sans-serif;
  }

  h1 {
    font-size: 48px;
    margin: 0;
  }

  .labels {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
  }

  label {
    font-weight: bold;
    font-size: 22px;
  }

  input {
    padding: 10px 20px;
    font-size: 18px;
    border-radius: 20px;
    border: 2px solid #000;
    outline: none;
  }

  button {
    display: inline-block;
    font-weight: bold;
    border-radius: 100px;
    text-decoration: none;
    border: 4px solid #000;
    font-size: 22px;
    box-shadow: 0 10px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    background-color: #000;
    color: #fff;
    padding: 20px 50px;
  }

  button:hover {
    background-color: #232323;
    color: #fff;
    border-color: #232323;
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0,0,0,0.2);
  }
</style>

<script>

  <div class="container">
    <div class="content">
      <h1>Añadir Item</h1>

      <form id="item_add_form" action="add_item.php" method="post" class="labels">
        <label for="item_name">Nombre del item</label>
        <input type="text" id="item_name" name="item_name" required>

        <label for="item_desc">Descripción</label>
        <input type="text" id="item_desc" name="item_desc" required>

        <label for="item_price">Precio (€)</label>
        <input type="number" id="item_price" name="item_price" min="0" step="0.01" required>

        <button type="submit" id="item_add_submit">Añadir</button>
      </form>
    </div>
  </div>

<script>

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
</script>
';
// phpinfo();

$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";
?>

