<?php
  echo '
<div class="container">
  <div class="content">
    <h1>PODCAST</h1>
    <div class="buttons">
      <a href="login.php" class="btn-login">Iniciar Sesión</a>
      <a href="register.php" class="btn-register">Registrarse</a>
      <a href="modify_user.php" class="btn-modify_user">Modificar Usuario</a>
    </div>
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
    gap: 60px;
  }

  h1, .content {
    font-family: Arial, sans-serif;
    font-size: 48px;
    margin: 0;
  }

  .buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 40px;
  }

  .btn-login,
  .btn-register 
  .btn-modify_user{
    display: inline-block;
    font-weight: bold;
    border-radius: 100px;
    text-decoration: none;
    border: 4px solid #000;
    font-size: 22px;
    box-shadow: 0 10px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
  }

  .btn-login {
    background-color: #000;
    color: #fff;
    padding: 40px 140px;
  }

  .btn-register {
    background-color: #fff;
    color: #000;
    padding: 40px 150px;
  }
  
  .btn-modify_user {
      background-color: #000;
      color: #fff;
      padding: 40px 140px;
  }

  .btn-login:hover,
  .btn-register:hover 
  .btn-modify_user:hover {
    background-color: #232323;
    color: #fff;
    border-color: #232323;
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0,0,0,0.2);
  }

</style>
';
?>
