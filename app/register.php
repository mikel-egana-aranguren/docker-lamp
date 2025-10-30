<?php

require_once('config.php');


include('header.php');


$errores = $_SESSION['errores'] ?? [];
$old_data = $_SESSION['old_data'] ?? [];


unset($_SESSION['errores']);
unset($_SESSION['old_data']);


function old($key) {
    global $old_data;
    return htmlspecialchars($old_data[$key] ?? '');
}
?>

<h2>Erregistro Orria</h2>
<p>Mesedez, bete eremuak. Adibideek derrigorrezko formatua erakusten dute.</p>

<?php

if (isset($errores['general'])):
?>
    <div class="error"><?php echo $errores['general']; ?></div>
<?php endif; ?>


<form action="procesar_register.php" method="POST" id="register_form" onsubmit="return validateRegisterForm()">
    
    <label for="nombre">Izen Abizenak:</label>
    <input type="text" id="nombre" name="izen_abizenak" value="<?php echo old('izen_abizenak'); ?>" required> 
    <span>(Adib. Jon Smith - Testua soilik)</span>
    <small id="nombre_error" class="js-error"></small>
    <?php if (isset($errores['nombre'])): ?><small class="error"><?php echo $errores['nombre']; ?></small><?php endif; ?>
    
    <label for="nan">NAN:</label>
    <input type="text" id="nan" name="nan" value="<?php echo old('nan'); ?>" required> 
    <span>(Adib. 11111111-Z - Formatua derrigorrezkoa)</span>
    <small id="nan_error" class="js-error"></small>
    <?php if (isset($errores['nan'])): ?><small class="error"><?php echo $errores['nan']; ?></small><?php endif; ?>
    
    <label for="tel">Telefonoa:</label>
    <input type="text" id="tel" name="telefonoa" value="<?php echo old('telefonoa'); ?>" required> 
    <span>(Adib. 600123456 - 9 zenbaki zehatz)</span>
    <small id="tel_error" class="js-error"></small>
    <?php if (isset($errores['telefono'])): ?><small class="error"><?php echo $errores['telefono']; ?></small><?php endif; ?>

    <label for="fecha">Jaiotze data:</label>
    <input type="text" id="fecha" name="jaiotze_data" value="<?php echo old('jaiotze_data'); ?>" required> 
    <span>(Adib. 2001-08-26 - uuuu-hh-ee formatua)</span>
    <small id="fecha_error" class="js-error"></small>
    <?php if (isset($errores['fecha'])): ?><small class="error"><?php echo $errores['fecha']; ?></small><?php endif; ?>
    
    <label for="email">Emaila:</label>
    <input type="email" id="email" name="email" value="<?php echo old('email'); ?>" required> 
    <span>(Adib. korreoa@adibidea.com)</span>
    <small id="email_error" class="js-error"></small>
    <?php if (isset($errores['email'])): ?><small class="error"><?php echo $errores['email']; ?></small><?php endif; ?>
    
    <label for="pass">Pasahitza:</label>
    <input type="password" id="pass" name="pasahitza" required>
    <span>(Gutxienez 6 karaktere)</span>
    <small id="pass_error" class="js-error"></small>
    <?php if (isset($errores['pass'])): ?><small class="error"><?php echo $errores['pass']; ?></small><?php endif; ?>
    <br>

    <button type="submit" id="register_submit">Erregistratu</button>
</form>
<?php

include('footer.php');
?>