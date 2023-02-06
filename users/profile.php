<?php 
    require_once("../inc/config.php");
    include $pathInclude."inc/model.php";
    $usersModel = new ModelUsers();

    // redirection path if connected
    if(isConnected()) {
        if(isConnected() != "users") {
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

    // si l'utilisateur taper sur le button submit
    if(isset($_POST["nName"], $_POST["nPrenom"], $_POST["nLogin"], $_POST["nPassword"])) {
        $nouveauNom = $_POST["nName"];
        $nouveauPrenom = $_POST["nPrenom"];
        $nouveauLogin = $_POST["nLogin"];
        $nouveauPassword = $_POST["nPassword"];
        // executer la modification
        $usersModel->updateProfile($nouveauNom, $nouveauPrenom, $nouveauLogin, $nouveauPassword);
        $_SESSION["login"] = $usersModel->getUserDataById($_SESSION["login"]->id);

    }
?>
<!DOCTYPE html>
<html lang="fr-FR">
    <?php include $pathInclude."inc/head.php" ?>
    <body id="body" class="profile-body">
        <main class="content">
            <?php include $pathInclude."inc/header.php" ?>
            <section class="presentation">
                <div class="container flex-c">
                    <h2>Présentation</h2>
                    <article class="content flex-r">
                        <div class="img-box">
                            <img src="<?php echo $pathLien?>assets/img/image.jpg" alt="image">
                        </div>
                        <div class="info-box flex-c">
                            <h3><?php echo htmlspecialchars($_SESSION['login']->login) ?> welcome to your profile</h3>
                            <p>login : <?php echo $_SESSION['login']->login ?></p>
                            <p>Nom : <?php echo $_SESSION['login']->nom ?></p>
                            <p>Prenom : <?php echo $_SESSION['login']->prenom ?></p>
                        </div>
                    </article>
                </div>
            </section>
            <section class="edit-profile">
                <div class="container">
                    <h2>Update Profile</h2>
                    <form action="" method="post" class='edit-form flex-r'>

                        <div class='box-edit-form'>
                            <label for="nName">Nouveau Nom :</label>
                            <input type="text" name="nName" id="nName" class='inp-edit-form'>
                        </div>

                        <div class='box-edit-form'>
                            <label for="nPrenom">Nouveau Prenom :</label>
                            <input type="text" name="nPrenom" id="nPrenom" class='inp-edit-form'>
                        </div>
                        
                        <div class='box-edit-form'>
                            <label for="nLogin">Nouveau login :</label>
                            <input type="login" name="nLogin" id="nLogin" class='inp-edit-form'>
                        </div>


                        <div class='box-edit-form'>
                            <label for="nPassword">Nouveau Password :</label>
                            <input type="password" name="nPassword" id="nPassword" class='inp-edit-form' >
                        </div>

                        <div class='box-btn'>
                            <input type="submit" value='update' class='btn-panel'>
                        </div>
                    </form>
                </div>
            </section>
        </main>
        <?php
            // on ferme la connexion
            $usersModel->closeConnection(); 
            include  $pathInclude."inc/footer.php"; 
        ?>
        <script src="<?php echo $pathLien?>assets/js/script.js"></script>
    </body>
</html>