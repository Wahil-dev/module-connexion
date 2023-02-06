<?php 
    session_start();
    require_once("inc/config.php");
    // redirection path if connected
    if(isConnected()) {
        header("location: ".$pathLien.isConnected()."/index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fr-FR">
    <?php require_once($pathInclude."inc/head.php");?>
<body>
    <div class="content">
        <?php require_once($pathInclude."inc/header.php");?>
        <h1>Acceuil visiteurs</h1>
    </div>
    <?php
        include $pathInclude."inc/footer.php";
    ?>
</body>
</html>