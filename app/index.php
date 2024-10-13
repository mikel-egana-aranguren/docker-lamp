<?php
    include 'databaseConnect.php';
    $konexioa = konektatuDatuBasera();
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bideojokoen Zerrenda</title>
    <link rel="stylesheet" href="main_style.css">
</head>
<body>
    <h1>Bideojokoen Zerrenda</h1>
    <div class="bideojoko-zerrenda">
       <?php
        $sql = "SELECT * FROM bideojokoak";
        $result = $konexioa->query($sql);

        while($row = $result->fetch_assoc()) {
            echo "<div class='bideojoko'>";
            echo "<h2 class='bideojoko-titulua'> ". $row["titulua"] . "</h2>";
            echo "</div>";
            echo '<table style="display:none; " >';
            echo "<tr>";
            echo "<th></th>";
            echo "<th></th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Titulua</td>";
            echo "<td>" . ($row["titulua"]) . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Prezioa</td>";
            echo "<td>" . ($row["prezioa"]) . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Mota</td>";
            echo "<td>" . ($row["mota"]) . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Urtea</td>";
            echo "<td>" . ($row["urtea"]) . "</td>";
            echo "</tr>";
            echo "</table>";
        }
        $konexioa->close();
       ?>
    </div>

    <button class="aldatu-botoia" onclick="window.location.href='modify_user.php'" style="position: absolute; top: 10px; right: 10px;">Aldatu/Hasi Saioa</button>
    <button class="gehitu-botoia" onclick="erakutsiFormularioaGehitu()" style="position: absolute; top: 50px; right: 10px;">Bideojokoa Gehitu</button>

    <div id="modal-gehitu" class="modal">
        <div class="modal-edukia">
            <span class="itxi" onclick="itxiFormularioaGehitu()">&times;</span>
            <form action="main_functions.php" method="post"></form>
                <h3>Bideojoko Berria Gehitu</h3>
                    <label for="gehituTitulua">Izenburua:</label>
                    <input type="text" id="gehituTitulua" required><br>

                    <label for="gehituPrezioa">Prezioa:</label>
                    <input type="text" id="gehituPrezioa" required><br>

                    <label for="gehituMota">Mota:</label>
                    <input type="text" id="gehituMota" required><br>

                    <label for="gehituDeskribapena">Deskribapena:</label>
                    <textarea id="gehituDeskribapena" required></textarea><br>

                    <label for="gehituArgitaratzeData">Argitaratze Urtea:</label>
                    <input type="text" id="gehituArgitaratzeData" required><br>

                <button type="submit" class="gorde-botoia" onclick="return balioztatuFormularioa()">Gehitu</button>
            </form>
        </div>
    </div>   
</body>
</html>
