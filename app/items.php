<?php

require_once('config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}


include('header.php');
?>

<h2>Elementuen Zerrenda</h2>
<p>Hemen dauden elementuak kudeatu ditzakezu.</p>

<a href="add_item" style="background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;">
    + Gehitu Elementu Berria
</a>
<br><br>
<hr>

<ul style="list-style-type: none; padding: 0;">

    <?php
    
    $sql = "SELECT id, izena, kategoria, erabiltzaile_id, portada_fitxategia FROM elementuak ORDER BY izena";
    $resultado = $conn->query($sql);
    
    $logged_user_id = $_SESSION['user_id']; 
    
    if ($resultado->num_rows > 0):
        while($item = $resultado->fetch_assoc()):
    ?>
            <li style='border-bottom: 1px solid #eee; padding: 10px 0; display: flex; justify-content: space-between; align-items: flex-start;'> 
            
            <div style="display: flex; align-items: center;"> 
                <?php if (!empty($item['portada_fitxategia'])): ?>
                    <img src="uploads/portadas/<?php echo htmlspecialchars($item['portada_fitxategia']); ?>" 
                         alt="<?php echo htmlspecialchars($item['izena']); ?> portada" 
                         style="width: 50px; height: auto; margin-right: 15px; border: 1px solid #ccc;">
                <?php else: ?>
                    <div style="width: 50px; height: 75px; margin-right: 15px; border: 1px solid #ccc; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 0.8em; color: #aaa;">Irudirik ez</div>
                <?php endif; ?>

                <div> 
                    <strong><a href="show_item?item=<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['izena']); ?></a></strong><br>
                    <small style='color: #555;'>Kategoria: <?php echo htmlspecialchars($item['kategoria']); ?></small>
                </div>
            </div>
            
            <div style="white-space: nowrap;"> 
                <a href='procesar_gehitu_pertsonala.php?item=<?php echo $item['id']; ?>' 
                    style='color: green; text-decoration: none; margin-right: 10px;'>+ Nirea</a>
                
                <?php if ($item['erabiltzaile_id'] == $logged_user_id): ?>
                    <a href='modify_item?item=<?php echo $item['id']; ?>' style='text-decoration: none; margin-right: 10px;'>Aldatu</a>
                    <a href='confirm_delete_item?item=<?php echo $item['id']; ?>' style='color: red; text-decoration: none;'>Ezabatu</a>
                <?php endif; ?>
            </div>
            
            </li>
    <?php
        endwhile;
    else:
    ?>
        <p>Oraindik ez dago elementurik sisteman.</p>
    <?php
    endif;
    
    $conn->close();
    ?>
    
</ul>

<?php

include('footer.php');
?>