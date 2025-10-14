<?php
echo '
<div class="container">
  <div class="content">
    <h1>INICIAR SESIÓN</h1>
    <div class="rellenar">
    <form id="login_form" action="login.php" method="post" class="labels">
      <label for="email">Correo</label>
      <input type="text" id="email" name="email" required>

      <label for="passwd">Contraseña</label>
      <input type="password" id="passwd" name="passwd" required>
    </div>
      <button type="submit" id="login_submit">Confirmar</button>
    </form>
  </div>
</div>

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
const form = document.getElementById("login_form");
const emailInput = document.getElementById("email");

form.addEventListener("submit", function(event) {
    const email = emailInput.value.trim();

    const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;

    if (!emailRegex.test(email)) {
        alert("Por favor, introduce un correo válido (usuario@servidor.extension).");
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

// datos introducidos
  $user = $_POST['email'];       
  $passwd = $_POST['passwd'];
?>

