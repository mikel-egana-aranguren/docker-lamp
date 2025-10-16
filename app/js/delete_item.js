document.addEventListener('DOMContentLoaded', function () {
function confirmDelete(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este ítem?")) {
    $sql = "DELETE FROM item WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Ítem eliminado correctamente.');
              </script>";
    } else {
        echo "Error al eliminar el ítem: " . $conn->error;
    }
  }
}
});

