<?php

require_once('config.php');
require_once('funciones.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}


if (!isset($_GET['item']) || !ctype_digit($_GET['item'])) {
    header('Location: items'); exit;
}
$item_id = $_GET['item'];


$sql_select = "SELECT izena, deskribapena, prezioa, stocka, kategoria, erabiltzaile_id, portada_fitxategia FROM elementuak WHERE id = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $item_id);
$stmt_select->execute();
$resultado = $stmt_select->get_result();

if ($resultado->num_rows !== 1) {
    header('Location: items'); exit;
}
$item = $resultado->fetch_assoc();
$stmt_select->close();

// --- JABETZA EGIAZTATZEA ---
if ($item['erabiltzaile_id'] != $_SESSION['user_id']) {
    include('header.php');
    echo "<div class='error'>Ez duzu baimenik elementu hau aldatzeko.</div>";
    echo "<a href='items'>← Zerrendara Itzuli</a>";
    include('footer.php');
    $conn->close(); 
    exit; 
}


include('header.php');


$errores = $_SESSION['errores_item_mod'] ?? [];
$old_data = $_SESSION['old_data_item_mod'] ?? [];
unset($_SESSION['errores_item_mod']);
unset($_SESSION['old_data_item_mod']);

function old_item_mod($key) {
    global $old_data, $item;
    return htmlspecialchars($old_data[$key] ?? $item[$key] ?? '');
}
?>

<h2>Elementua Aldatu</h2>
<p>Aldatu nahi dituzun eremuak.</p>

<?php if (isset($errores['general'])): ?>
    <div class="error"><?php echo $errores['general']; ?></div>
<?php endif; ?>

<form action="procesar_modify_item.php" method="POST" id="item_modify_form" onsubmit="return validateItemForm()" enctype="multipart/form-data">
    
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
    
    <label for="izena">Izenburua (Izena):</label>
    <input type="text" id="izena" name="izena" value="<?php echo old_item_mod('izena'); ?>" required> 
    <span>(Adib. "El Señor de los Anillos")</span>
    <small id="izena_error" class="js-error"></small>
    <?php if (isset($errores['izena'])): ?><small class="error"><?php echo $errores['izena']; ?></small><?php endif; ?>

    <label for="deskribapena">Deskribapena:</label>
    <input type="text" id="deskribapena" name="deskribapena" value="<?php echo old_item_mod('deskribapena'); ?>" required> 
    <span>(Adib. "Fantasiazko liburua...")</span>
    <small id="deskribapena_error" class="js-error"></small>
    
    <label for="prezioa">Prezioa:</label>
    <input type="text" id="prezioa" name="prezioa" value="<?php echo old_item_mod('prezioa'); ?>" required> 
    <span>(Adib. "25.50" - Zenbakia soilik, koma '.' batekin)</span>
    <small id="prezioa_error" class="js-error"></small>
    <?php if (isset($errores['prezioa'])): ?><small class="error"><?php echo $errores['prezioa']; ?></small><?php endif; ?>

    <label for="stocka">Stocka:</label>
    <input type="text" id="stocka" name="stocka" value="<?php echo old_item_mod('stocka'); ?>" required> 
    <span>(Adib. "10" - Zenbaki osoa soilik)</span>
    <small id="stocka_error" class="js-error"></small>
    <?php if (isset($errores['stocka'])): ?><small class="error"><?php echo $errores['stocka']; ?></small><?php endif; ?>

    <label for="kategoria">Kategoria:</label>
    <input type="text" id="kategoria" name="kategoria" value="<?php echo old_item_mod('kategoria'); ?>"> 
    <span>(Adib. "Fantasia", "Zientzia Fikzioa")</span>
    <small id="kategoria_error" class="js-error"></small>

    <label for="portada">Portadako Argazkia:</label>
    <?php if (!empty($item['portada_fitxategia'])): ?>
        <div style="margin-bottom: 10px;">
            <strong>Oraingo portada:</strong><br>
            <img src="uploads/portadas/<?php echo htmlspecialchars($item['portada_fitxategia']); ?>" alt="Oraingo portada" style="max-width: 100px; max-height: 150px; margin-top: 5px;">
            <br>
            <input type="checkbox" name="ezabatu_portada" id="ezabatu_portada"> <label for="ezabatu_portada" style="display: inline; font-weight: normal;">Ezabatu oraingo portada</label>
        </div>
    <?php endif; ?>
    <input type="file" id="portada" name="portada_fitxategia_berria" accept="image/jpeg, image/png"> 
    <span>(Utzi hutsik oraingoa mantentzeko. JPG edo PNG soilik)</span>
    <small id="portada_error" class="js-error"></small>
    <?php if (isset($errores['portada'])): ?><small class="error"><?php echo $errores['portada']; ?></small><?php endif; ?>
    
    <br>
    <button type="submit" id="item_modify_submit">Aldaketak Gorde</button>
</form>
<?php

include('footer.php');
?>