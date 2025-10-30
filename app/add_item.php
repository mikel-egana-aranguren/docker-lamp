<?php

require_once('config.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}


include('header.php');


$errores = $_SESSION['errores_item'] ?? [];
$old_data = $_SESSION['old_data_item'] ?? [];
unset($_SESSION['errores_item']);
unset($_SESSION['old_data_item']);

function old_item($key) {
    global $old_data;
    return htmlspecialchars($old_data[$key] ?? '');
}
?>

<h2>Elementu Berria Gehitu</h2>
<p>Bete 5 eremuak elementu berri bat sortzeko (adibidez, liburu bat).</p>

<?php if (isset($errores['general'])): ?>
    <div class="error"><?php echo $errores['general']; ?></div>
<?php endif; ?>

<form action="procesar_add_item.php" method="POST" id="item_add_form" onsubmit="return validateItemForm()" enctype="multipart/form-data">
    
    <label for="izena">Izenburua (Izena):</label>
    <input type="text" id="izena" name="izena" value="<?php echo old_item('izena'); ?>" required> 
    <span>(Adib. "El Señor de los Anillos")</span>
    <small id="izena_error" class="js-error"></small>
    <?php if (isset($errores['izena'])): ?><small class="error"><?php echo $errores['izena']; ?></small><?php endif; ?>

    <label for="deskribapena">Deskribapena:</label>
    <input type="text" id="deskribapena" name="deskribapena" value="<?php echo old_item('deskribapena'); ?>" required> 
    <span>(Adib. "Fantasiazko liburua...")</span>
    <small id="deskribapena_error" class="js-error"></small>
    
    <label for="prezioa">Prezioa:</label>
    <input type="text" id="prezioa" name="prezioa" value="<?php echo old_item('prezioa'); ?>" required> 
    <span>(Adib. "25.50" - Zenbakia soilik, koma '.' batekin)</span>
    <small id="prezioa_error" class="js-error"></small>
    <?php if (isset($errores['prezioa'])): ?><small class="error"><?php echo $errores['prezioa']; ?></small><?php endif; ?>

    <label for="stocka">Stocka:</label>
    <input type="text" id="stocka" name="stocka" value="<?php echo old_item('stocka'); ?>" required> 
    <span>(Adib. "10" - Zenbaki osoa soilik)</span>
    <small id="stocka_error" class="js-error"></small>
    <?php if (isset($errores['stocka'])): ?><small class="error"><?php echo $errores['stocka']; ?></small><?php endif; ?>

    <label for="kategoria">Kategoria:</label>
    <input type="text" id="kategoria" name="kategoria" value="<?php echo old_item('kategoria'); ?>"> 
    <span>(Adib. "Fantasia", "Zientzia Fikzioa")</span>
    <small id="kategoria_error" class="js-error"></small>

    <label for="portada">Portadako Argazkia:</label>
    <input type="file" id="portada" name="portada_fitxategia" accept="image/jpeg, image/png"> 
    <span>(JPG edo PNG soilik)</span>
    <small id="portada_error" class="js-error"></small>
    <?php if (isset($errores['portada'])): ?><small class="error"><?php echo $errores['portada']; ?></small><?php endif; ?>
    
    <br>
    <button type="submit" id="item_add_submit">Gehitu Elementua</button>
</form>



<?php
// 4. Incluir el footer
include('footer.php');
?>