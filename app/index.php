<?php

require_once('config.php');


include('header.php');
?>

<h1>Ongi Etorri Nire Web Sistemara!</h1>

<?php if (isset($_SESSION['user_id'])): ?>
    
    <p>Kaixo berriro, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?>.</p>
    <p>Zure <a href="modify_user">profila</a> ikus dezakezu zure datuak aldatzeko.</p>

<?php else: ?>

    <p>Mesedez, <a href="register">erregistratu</a> edo <a href="login">hasi saioa</a> jarraitzeko.</p>

<?php endif; ?>


<?php

include('footer.php');
?>