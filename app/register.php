<?php
echo '
<div class="container">
  <div class="content">
    <h1>REGISTRARSE</h1>
    <div class="rellenar">
    <form id="register_form" action="register.php" method="post" class="labels">
      <label for="name">Nombre *</label>
      <input type="text" id="name" name="name" required>

      <label for="surnames">Apellidos *</label>
      <input type="text" id="surnames" name="surnames" required>
      
      <label for="dni">DNI *</label>
      <input type="text" id="dni" name="dni" required>
      
      <label for="email">Correo *</label>
      <input type="text" id="email" name="email" required>
      
      <label for="tlfn">Teléfono *</label>
      <input type="text" id="tlfn" name="tlfn" required>
      
      <label for="fNcto">Fecha de Nacimiento *</label>
      <input type="date" id="fNcto" name="fNcto" required>
      
      <label for="passwd">Contraseña *</label>
      <input type="password" id="passwd" name="passwd" required>
      
      <label for="passwd_repeat">Repetir Contraseña *</label>
      <input type="password" id="passwd_repeat" name="passwd_repeat" required>
    </div>
      <button type="submit" id="register_submit">Confirmar</button>
    </form>
  </div>
</div>

<style>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
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
    gap: 30px;
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
    gap: 15px;
  }

  label {
    font-weight: bold;
    font-size: 20px;
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
const form = document.getElementById("register_form");
const nameInput = document.getElementById("name");
const surnamesInput = document.getElementById("surnames");

form.addEventListener("submit", function(event) {
    const name = nameInput.value.trim();
    const surnames = surnamesInput.value.trim();

    const nameRegex = /^[A-Za-z]+$/;
    const surnamesRegex = /^[A-Za-zÀ-ÿ ]+$/;

    if (!nameRegex.test(name)) {
        alert("Por favor, introduce un nombre válido.");
        event.preventDefault();
    } else if (!surnamesRegex.test(surnames)) {
        alert("Por favor, introduce apellidos válidos.");
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

