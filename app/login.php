<?php

require_once('config.php');


if (isset($_SESSION['user_id'])) {
    header('Location: modify_user');
    exit;
}

include('header.php'); 


if (isset($_GET['registro']) && $_GET['registro'] == 'exito') {
    echo '<div class="exito">Erregistroa ondo burutu da! Mesedez, hasi saioa.</div>';
}


if (isset($_GET['error']) && $_GET['error'] == '1') {
    echo '<div class="error">Errorea: Emaila edo pasahitza ez dira zuzenak.</div>';
}
?>

<h2>Saioa Hasi</h2>
<p>Sartu zure emaila eta pasahitza.</p>

<form action="procesar_login.php" method="POST" id="login_form" onsubmit="return validateLoginForm()">
    
    <label for="email">Emaila:</label>
    <input type="email" id="email" name="email" required>
    <small id="email_error" class="js-error"></small>
    
    <label for="pass">Pasahitza:</label>
    <input type="password" id="pass" name="pasahitza" required>
    <small id="pass_error" class="js-error"></small>
    <br>

    <button type="submit" id="login_submit">Sartu</button>
</form>
<?php
include('footer.php');
?>