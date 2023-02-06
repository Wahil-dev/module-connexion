<?php 
    session_start();
    require_once("../inc/config.php");
    // redirection path if connected
    if(isConnected()) {
        if(isConnected() != "admin") {
            header("location: ".$pathLien.isConnected()."/index.php");
            exit();
        }
    } else {
        header("location: ".$pathLien."index.php");
        exit();
    }

    // pour la déconnexion
    if(isset($_GET["logout"])) {
        require_once($pathInclude."inc/logout.php");
    }
?>
<!DOCTYPE html>
<html lang="fr-FR">
    <?php require_once($pathInclude."inc/head.php");?>
<body>
    <div class="content">
        <?php require_once($pathInclude."inc/header.php");?>
        <main class="main">
            <h1>Acceuil visiteurs</h1>
        </main>
    </div>
    <?php
        include $pathInclude."inc/footer.php";
    ?>
</body>
</html>