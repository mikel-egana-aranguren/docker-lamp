<?php
require_once('config.php');
require_once('funciones.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

$user_id = $_SESSION['user_id'];

// --- DATUAK LORTZEA ---
// 1. Erabiltzailearen datuak lortu
$sql_user = "SELECT nombre, nan, telefono, fecha_nacimiento, email FROM usuarios WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$usuario = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

// 2. Erabiltzailearen liburu pertsonalak lortu
$sql_pertsonalak = "SELECT e.id, e.izena 
                    FROM elementuak e
                    JOIN bazkide_liburuak bl ON e.id = bl.liburu_id
                    WHERE bl.bazkide_id = ?";
$stmt_pertsonalak = $conn->prepare($sql_pertsonalak);
$stmt_pertsonalak->bind_param("i", $user_id); 
$stmt_pertsonalak->execute();
$result_pertsonalak = $stmt_pertsonalak->get_result();
// Ez dugu $stmt_pertsonalak->close() deitzen, emaitza geroago erabiliko dugulako begiztan

// --- SAIOKO ALDAGAIEN KUDEAKETA ---
$errores = $_SESSION['errores_perfil'] ?? [];
$old_data = $_SESSION['old_data_perfil'] ?? [];
unset($_SESSION['errores_perfil']);
unset($_SESSION['old_data_perfil']);

function old_perfil($key) {
    global $old_data, $usuario;
    return htmlspecialchars($old_data[$key] ?? $usuario[$key] ?? '');
}

// --- HEADER-A SARTU ---
include('header.php');
?>

<h2>Ongi etorri zure profilera, <?php echo htmlspecialchars($usuario['nombre']); ?>!</h2>
<p>Hemen zure datuak aldatu ahal izango dituzu. Pasahitza bereiz aldatzen da (aukerakoa).</p>

<?php if (isset($_GET['exito'])): ?>
    <div class="exito">Datuak ondo eguneratu dira!</div>
<?php endif; ?>

<?php if (isset($errores['general'])): ?>
    <div class="error"><?php echo $errores['general']; ?></div>
<?php endif; ?>

<form action="procesar_modify_user.php" method="POST" id="user_modify_form" onsubmit="return validateModifyForm()">
    
    <label for="nombre">Izen Abizenak:</label>
    <input type="text" id="nombre" name="izen_abizenak" value="<?php echo old_perfil('nombre'); ?>" required> 
    <span>(Adib. Jon Smith - Testua soilik)</span>
    <small id="nombre_error" class="js-error"></small>
    <?php if (isset($errores['nombre'])): ?><small class="error"><?php echo $errores['nombre']; ?></small><?php endif; ?>
    
    <label for="nan">NAN:</label>
    <input type="text" id="nan" name="nan" value="<?php echo old_perfil('nan'); ?>" required> 
    <span>(Adib. 11111111-Z - Formatua derrigorrezkoa)</span>
    <small id="nan_error" class="js-error"></small>
    <?php if (isset($errores['nan'])): ?><small class="error"><?php echo $errores['nan']; ?></small><?php endif; ?>
    
    <label for="tel">Telefonoa:</label>
    <input type="text" id="tel" name="telefonoa" value="<?php echo old_perfil('telefono'); ?>" required> 
    <span>(Adib. 600123456 - 9 zenbaki zehatz)</span>
    <small id="tel_error" class="js-error"></small>
    <?php if (isset($errores['telefono'])): ?><small class="error"><?php echo $errores['telefono']; ?></small><?php endif; ?>

    <label for="fecha">Jaiotze data:</label>
    <input type="text" id="fecha" name="jaiotze_data" value="<?php echo old_perfil('fecha_nacimiento'); ?>" required> 
    <span>(Adib. 2001-08-26 - uuuu-hh-ee formatua)</span>
    <small id="fecha_error" class="js-error"></small>
    <?php if (isset($errores['fecha'])): ?><small class="error"><?php echo $errores['fecha']; ?></small><?php endif; ?>
    
    <label for="email">Emaila:</label>
    <input type="email" id="email" name="email" value="<?php echo old_perfil('email'); ?>" required> 
    <span>(Adib. korreoa@adibidea.com)</span>
    <small id="email_error" class="js-error"></small>
    <?php if (isset($errores['email'])): ?><small class="error"><?php echo $errores['email']; ?></small><?php endif; ?>
    
    <hr>
    <p>Pasahitza aldatzeko, bete hurrengo eremuak. Hutsik uzten badituzu, zure pasahitza ez da aldatuko.</p>

    <label for="pass_new">Pasahitz Berria:</label>
    <input type="password" id="pass_new" name="pasahitza_nueva">
    <span>(Gutxienez 6 karaktere)</span>
    <small id="pass_error" class="js-error"></small>
    <?php if (isset($errores['pass'])): ?><small class="error"><?php echo $errores['pass']; ?></small><?php endif; ?>

    <label for="pass_rep">Errepikatu Pasahitz Berria:</label>
    <input type="password" id="pass_rep" name="pasahitza_repetir">
    <small id="pass_rep_error" class="js-error"></small>
    <?php if (isset($errores['pass_rep'])): ?><small class="error"><?php echo $errores['pass_rep']; ?></small><?php endif; ?>
    <br>

    <button type="submit" id="user_modify_submit">Aldaketak Gorde</button>
</form>

<hr style="margin-top: 30px;">
<h2>Nire Liburu Pertsonalak</h2>

<ul style="list-style-type: none; padding: 0;">
<?php
if ($result_pertsonalak->num_rows > 0):
    while($liburu_pertsonala = $result_pertsonalak->fetch_assoc()):
?>
        <li style='border-bottom: 1px solid #eee; padding: 8px 0; display: flex; justify-content: space-between; align-items: center;'>
            <a href="show_item?item=<?php echo $liburu_pertsonala['id']; ?>">
                <?php echo htmlspecialchars($liburu_pertsonala['izena']); ?>
            </a>
            <a href="procesar_kendu_pertsonala.php?item=<?php echo $liburu_pertsonala['id']; ?>" 
               onclick="return confirm('Ziur zaude liburu hau zure zerrendatik kendu nahi duzula?');"
               style="color: red; font-size: 0.9em;">Kendu</a>
        </li>
<?php
    endwhile;
else:
    echo "<p>Oraindik ez duzu libururik gehitu zure zerrenda pertsonalera.</p>";
endif;
$stmt_pertsonalak->close();
$conn->close(); // Konexioa hemen ixten dugu, amaieran
?>
</ul>

<?php
include('footer.php');
?>