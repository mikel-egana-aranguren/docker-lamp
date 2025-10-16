<form id="item_delete_form" action="procesar_delete_item.php" method="POST" onsubmit="return confirmarEliminacion()">
    <input type="hidden" name="item_id" value="<?= $_GET['item'] ?>">
    <input id="item_delete_submit" type="submit" value="Eliminar ítem">
</form>

<script src="eliminar.js"></script>
