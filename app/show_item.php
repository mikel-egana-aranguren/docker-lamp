<?php

require_once('config.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}


if (!isset($_GET['item']) || !ctype_digit($_GET['item'])) {
    header('Location: items'); exit;
}
$item_id = $_GET['item'];


$sql = "SELECT izena, deskribapena, prezioa, stocka, kategoria, erabiltzaile_id, portada_fitxategia FROM elementuak WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    header('Location: items'); exit;
}
$item = $resultado->fetch_assoc();
$stmt->close();
$conn->close();


include('header.php');
?>

<h2>Elementuaren Xehetasunak</h2>
<p>Hemen hautatutako elementuaren informazio guztia ikus dezakezu.</p>

<div style="margin-top: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 5px; background: #f9f9f9; display: flex; align-items: flex-start;">

    <div style="margin-right: 20px; flex-shrink: 0;"> <?php if (!empty($item['portada_fitxategia'])): ?>
            <img src="uploads/portadas/<?php echo htmlspecialchars($item['portada_fitxategia']); ?>" 
                 alt="<?php echo htmlspecialchars($item['izena']); ?> portada" 
                 style="max-width: 150px; height: auto; border: 1px solid #ccc;">
        <?php else: ?>
             <div style="width: 150px; height: 225px; border: 1px solid #ccc; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 0.9em; color: #aaa;">Irudirik ez</div>
        <?php endif; ?>
    </div>

    <div>
        <h3><?php echo htmlspecialchars($item['izena']); ?></h3>
        <p><strong>Deskribapena:</strong> <?php echo nl2br(htmlspecialchars($item['deskribapena'])); // nl2br lerro-jauziak errespetatzeko ?></p>
        <p><strong>Prezioa:</strong> <?php echo htmlspecialchars(number_format($item['prezioa'], 2, ',', '.')); // Formatua emateko ?> €</p>
        <p><strong>Stocka:</strong> <?php echo htmlspecialchars($item['stocka']); ?> unitate</p>
        <p><strong>Kategoria:</strong> <?php echo htmlspecialchars($item['kategoria']); ?></p>

        <?php if ($item['erabiltzaile_id'] == $_SESSION['user_id']): ?>
            <p style="margin-top: 20px;">
                <a href="modify_item?item=<?php echo $item_id; ?>">Aldatu</a> | 
                <a href="confirm_delete_item?item=<?php echo $item_id; ?>" style="color: red;">Ezabatu</a>
            </p>
        <?php endif; ?>
    </div>

</div>

<br>
<a href="items">← Zerrendara Itzuli</a>


<?php

include('footer.php');
?>