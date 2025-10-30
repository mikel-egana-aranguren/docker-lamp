<?php
?>
<!DOCTYPE html>
<html lang="eu"> 
<head>
    <meta charset="UTF-R">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nire Web Sistema</title> 
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f9f9f9; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav { background: #333; padding: 10px; display: flex; justify-content: space-between; align-items: center; }
        nav .links-izquierda a { color: white; padding: 10px 15px; text-decoration: none; font-weight: bold; }
        nav .links-derecha a { color: white; padding: 10px 15px; text-decoration: none; font-weight: bold; }
        nav a:hover { background: #555; }
        form label { display: block; margin-top: 15px; font-weight: bold; }
        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 95%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;
        }
        form span { font-size: 0.9em; color: #777; }
        form button { background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px; }
        form button:hover { background: #0056b3; }
        .error { color: red; font-weight: bold; border: 1px solid red; padding: 10px; margin-bottom: 15px; border-radius: 4px; background: #ffebeb; }
        .exito { color: green; font-weight: bold; border: 1px solid green; padding: 10px; margin-bottom: 15px; border-radius: 4px; background: #e6ffed; }
        .js-error { 
            color: red; 
            font-weight: bold; 
            font-size: 0.9em; 
            display: block; 
            margin-top: 5px; 
        }
    </style>
    <script src="main.js" defer></script>
</head>
<body>

<nav>
    <div class="links-izquierda">
        <a href="./">Hasiera</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="modify_user">Nire Profila</a>
            <a href="items">Elementuak</a> 
        <?php endif; ?>
    </div>
    <div class="links-derecha">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout">Saioa Itxi</a>
        <?php else: ?>
            <a href="register">Erregistratu</a>
            <a href="login">Saioa Hasi</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">