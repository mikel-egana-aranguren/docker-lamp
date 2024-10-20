<?php
    include 'databaseConnect.php';

?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bideojokoen Zerrenda</title>
    <link rel="stylesheet" href="main_style.css">
    <script src="main_script.js"></script>
</head>
<body>
    <h1>Bideojokoen Zerrenda</h1>
    <div class="bideojoko-lista">
       <div class="bideojoku-taula">>    
         <?php
             $sql = "SHOW TABLES";
            $sql = "SELECT * FROM bideojokoa";
        
            $result = $conn->query($sql);

            if (!$result) {
                echo "Errorea datu basearekin: " . $conn->error;
            } else if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {

                    $game_id = $row["titulu"] . '-' . $row["egilea"]; // Crear un id único combinando título y autor
                    echo "<div class='bideojoko' onclick='toggleDetalles(\"$game_id\")'>";
                    echo "<h2 class='bideojoko-titulua'>" . $row["titulu"] . "</h2>";
                    echo "<h2 class='bideojoko-titulua'>" . $row["egilea"] . "</h2>";
                    echo "</div>";
                    echo "<table id='detalles-$game_id' style='display:none;'>";
                    echo "<tr><th>Atributua</th><th>Balioa</th></tr>";
                    echo "<tr><td>Titulua</td><td>" . $row["titulu"] . "</td></tr>";
                    echo "<tr><td>Egilea</td><td>" . $row["egilea"] . "</td></tr>";
                    echo "<tr><td>Prezioa</td><td>" . $row["prezioa"] . "</td></tr>";
                    echo "<tr><td>Mota</td><td>" . $row["mota"] . "</td></tr>";
                    echo "<tr><td><button type=\"button\" id=\"modal-editatu\">Editatu</button></td></tr>";
                    echo "<tr><td><button onclick='bideojokoaEzabatu(\"" . $row["titulu"] . "\",\"" . $row["egilea"] . "\")'>Ezabatu</button></td></tr>";
                    echo "</table>";


                /* Generar un id único para cada videojuego
                $game_id = $row["id"];
                echo "<div class='bideojoko' onclick='toggleDetalles($game_id)'>";
                // Mostrar datos sin aplicar htmlspecialchars()
                echo "<h2 class='bideojoko-titulua'>". $row["titulua"] . "</h2>";
                echo "</div>";
                echo "<table id='detalles-$game_id' style='display:none;'>";
                echo "<tr><th>Atributua</th><th>Balioa</th></tr>";
                echo "<tr><td>Titulua</td><td>" . $row["titulua"] . "</td></tr>";
                echo "<tr><td>Prezioa</td><td>" . $row["prezioa"] . "</td></tr>";
                echo "<tr><td>Mota</td><td>" . $row["mota"] . "</td></tr>";
                echo "<tr><td>Urtea</td><td>" . $row["urtea"] . "</td></tr>";
                echo "</table>";*/
                }
            } else {
            echo "<p>Bideojokorik ez dago.</p>";
            }

            $conn->close();
            ?>
       </div>
    </div>

    <!-- Botoiak -->
    <button class="aldatu-botoia" onclick="window.location.href='modify_user.php'" style="position: absolute; top: 10px; right: 10px;">Aldatu/Hasi Saioa</button>
    <button class="gehitu-botoia" onclick="erakutsiFormularioaGehitu()" style="position: absolute; top: 50px; right: 10px;">Bideojokoa Gehitu</button>
    <button class="registro-botoia" onclick="window.location.href='register.php'" style="position: absolute; top: 90px; right: 10px;">Registro</button>
    
 
    <div id="modal-gehitu" class="modal" style="display:none;">
        <div class="modal-edukia">
            <span class="itxi" onclick="itxiFormularioaGehitu()">&times;</span>
            <form action="main_functions.php" method="post">
                <h3>Bideojoko Berria Gehitu</h3>
                <label for="gehituTitulua">Izenburua:</label>
                <input type="text" id="gehituTitulua" name="titulua" required><br>

                <label for="gehituEgilea">Egilea:</label>
                <input type="text" id="gehituEgilea" name="egilea" required><br>

                <label for="gehituPrezioa">Prezioa:</label>
                <input type="text" id="gehituPrezioa" name="prezioa" required><br>

                <label for="gehituMota">Mota:</label>
                <input type="text" id="gehituMota" name="mota" required><br>

                <label for="gehituArgitaratzeData">Argitaratze Urtea:</label>
                <input type="text" id="gehituArgitaratzeData" name="argitaratze_urtea" required><br>

                <input type="hidden" name="akzioa" value="gehitu">
                <button type="submit" onclick= "return balioztatuFormularioa()">Gehitu</button>
            </form>
        </div>
    </div>

    <div id="modal-editatu" class="modal" style="display:none;">
        <div class="modal-edukia">
            <span class="itxi" onclick="itxiFormularioaEditatu()">&times;</span>
            <form action="main_functions.php" method="post">
                <h3>Bideojoko Berria Gehitu</h3>
                <label for="aldatuTitulua">Izenburua:</label>
                <input type="text" id="aldatuTitulua" name="aldatuTitulua" required><br>

                <label for="aldatuEgilea">Egilea:</label>
                <input type="text" id="aldatuEgilea" name="aldatuEgilea" required><br>

                <label for="aldatuPrezioa">Prezioa:</label>
                <input type="text" id="aldatuPrezioa" name="aldatuPrezioa" required><br>

                <label for="aldatuMota">Mota:</label>
                <input type="text" id="aldatuMota" name="aldatuMota" required><br>

                <label for="aldatuArgitaratzeData">Argitaratze Urtea:</label>
                <input type="text" id="aldatuArgitaratzeData" name="aldatuUrtea" required><br>

                <input type="hidden" name="akzioa" value="aldatu">
                <button type="submit" onclick= "return balioztatuFormularioa()">Aldatu</button>
            </form>
        </div>
</body>
</html>


