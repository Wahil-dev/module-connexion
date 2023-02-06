<?php 
    require_once("config.php");
?>

<header class="header-panel flex-r">
    <h1 class="title"><?php echo isset($_SESSION["login"]) ? $_SESSION["login"]->login : 'Acceuil ' ?>Page</h1>
    <nav class="nav-panel">
        <ul name="menuPanel" id="menuPanel">
            <li class="menu-box">
                <span id="openMenu"><i class="fa-solid fa-user"></i><?php echo isset($_SESSION["login"]) ? $_SESSION["login"]->login : 'menuBar ' ?></span>
                <ul id="menu" class="ul-in-ul">
                    <?php if(isConnected()) :?>
                        <?php if(isConnected()=="admin") :?>
                            <li><a href="<?php echo $pathLien?>admin/index.php">Acceuil</a></li>
                            <li><a href="<?php echo $pathLien?>admin/afficher_users.php">afficher_users</a></li>
                            <li><a href="<?php echo $pathLien?>inc/profile.php">Profile</a></li>
                            <li><a href="?logout">déconnexion</a></li>
                        <?php else :?>
                            <li><a href="<?php echo $pathLien?>users/index.php">Acceuil</a></li>
                            <li><a href="<?php echo $pathLien?>inc/profile.php">Profile</a></li>
                            <li><a href="?logout">déconnexion</a></li>
                        <?php endif ?>
                    <?php else :?>
                        <li><a href="<?php echo $pathLien?>index.php">Acceuil</a></li>
                        <li><a href="<?php echo $pathLien?>connexion.php">Connexion</a></li>
                        <li><a href="<?php echo $pathLien?>inscription.php">Inscription</a></li>
                    <?php endif ?>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<script src="<?php echo $pathLien?>assets/js/script.js"></script>