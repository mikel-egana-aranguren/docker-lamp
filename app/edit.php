<?php
include 'databaseConnect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $titulu = $_GET['titulu'];
    $egilea = $_GET['egilea'];
    $prezioa = $_POST['prezioa'];
    $mota = $_POST['mota'];
    $urtea = $_POST['urtea'];
    
    $query = "SELECT * FROM bideojokoa WHERE titulu = '$titulu' AND egilea = '$egilea'";
    $Egiaztatu = mysqli_query($conn, $query);

    if (mysqli_num_rows($Egiaztatu) == 1) {
        $stmt = $conn->prepare("UPDATE bideojokoa
                SET titulu='$titulu', egilea='$egilea', prezioa='$prezioa', mota='$mota', urtea='$urtea'
                WHERE titulu='$titulu' AND egilea='$egilea'");
        if ($stmt == false) {
            echo "Errorea datu basearekin: " . $conn->error;
        }
        $stmt->bind_param("ssdss", $titulu, $egilea, $prezioa, $mota, $urtea);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Errorea datuak gordetzean";
        }  
        $stmt->close();
    } else {
        echo "Errorea: " . mysqli_error($conn);
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bideojokoa Aldatu</title>
    <link rel="stylesheet" href="main_style.css">
    <script src="main_script.js"></script>
</head>
<body>
    <h1>Bideojokoa Aldatu</h1>
    <form action="" method="post">
        <label for="titulu">Titulua:</label>
        <input type="text" id="titulu" name="titulu" required><br><br>
        <label for="egilea">Egilea:</label>
        <input type="text" id="egilea" name="egilea" required><br><br>
        <label for="prezioa">Prezioa:</label>
        <input type="number" id="prezioa" name="prezioa" required><br><br>
        <label for="mota">Mota:</label>
        <input type="text" id="mota" name="mota" required><br><br>
        <label for="urtea">Urtea:</label>
        <input type="number" id="urtea" name="urtea" required><br><br>
        <button type="submit">Aldatu</button>
    </form>

    <button class="atzera-botoia" onclick="window.location.href='index.php'">Atzera</button>
</body>
</html>
