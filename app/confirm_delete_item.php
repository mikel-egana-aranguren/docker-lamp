<?php

require_once('config.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}


if (!isset($_GET['item']) || !ctype_digit($_GET['item'])) {
    header('Location: items');
    exit;
}
$item_id = $_GET['item'];


$sql_select = "SELECT izena FROM elementuak WHERE id = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $item_id);
$stmt_select->execute();
$resultado = $stmt_select->get_result();

if ($resultado->num_rows !== 1) {
    header('Location: items');
    exit;
}
$item = $resultado->fetch_assoc();
$stmt_select->close();
$conn->close();


include('header.php');
?>

<h2>Elementua Ezabatu: Konfirmazioa</h2>
<p>Ziur zaude <strong><?php echo htmlspecialchars($item['izena']); ?></strong> elementua ezabatu nahi duzula?</p>
<p style="color: red;">Ekintza hau ezin da desegin.</p>

<form action="procesar_delete_item.php" method="POST" style="display: inline;">
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
    
    <button type="submit" id="item_delete_submit" style="background-color: red; color: white;">Bai, Ezabatu</button>
</form>

<a href="items" style="margin-left: 10px;">Ez, Utzi</a>

<?php

include('footer.php');
?>