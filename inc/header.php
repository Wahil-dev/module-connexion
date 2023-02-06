<?php 
    require_once("config.php");
?>

<header class="header-panel flex-r">
    <h1 class="title"><?php echo isset($_SESSION["login"]) ? $_SESSION["login"]->username : 'Acceuil ' ?>Page</h1>
    <nav class="nav-panel">
        <ul name="menuPanel" id="menuPanel">
            <li class="menu-box">
                <span id="openMenu"><i class="fa-solid fa-user"></i>wahil chettouf</span>
                <ul id="menu" class="ul-in-ul">
                    <?php if(isConnected()) :?>
                        
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