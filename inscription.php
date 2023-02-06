<?php 
    include "inc/config.php";
    include $pathInclude."inc/model.php";
    $usersModel = new ModelUsers();

    // redirection path if connected
    if(isConnected()) {
        header("location: ".$pathLien.isConnected()."/index.php");
        exit();
    }

    // tableaux d'erreurs
    $errors = [
        "loginError" => "",
        "passwordError" => "",
        "identifiantError" => "",
        "champVide" => "",
        "confirmPassError" => "",
        "loginExist" => "",
        "siteError" => "",
    ];

    if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['login']) && isset($_POST['password']) && isset($_POST['cPassword'])) {
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $login = $_POST["login"];
        $password = $_POST["password"];
        $cPassword = $_POST["cPassword"]; //confirm password
        if(!empty($nom) && !empty($login) && !empty($password) && !empty($password)) {
            // ajouter des vérifications pour nom
            // validation password : il faut qu'il contient
            $uppercase = preg_match('@[A-Z]@', $password); // lettre en majuscule
            $lowercase  = preg_match("@[a-z]@", $password); // lettre en minuscule
            $number = preg_match("@[0-9]@", $password); // un numéro
            $specialChars = preg_match("@[^\w]@", $password); // caractère spéciaux 
            $passLength = strlen($password) >= 8; // 8 caractère au min
            if($uppercase && $lowercase && $number && $specialChars && $passLength) {
                if($password === $cPassword) {
                    // check if user exist
                    if(!$usersModel->isExist($login)) {
                        // si tout est bon mais il y'a des problème coté serveur, database ...
                        if(!$usersModel->setUser($nom, $prenom, $login, $password)) {
                            $_SESSION["user_inscrit"] = $login;
                            header("location: ". $pathLien."connexion.php");
                            exit();
                        } else {
                            $errors["siteError"] = "Error base de donnes !";
                        }
                    } else {
                        $errors["loginExist"] = "Désoli! ce login : ".htmlspecialchars($login)." est déja utiliser !";
                    }
                } else {
                    $errors['confirmPassError'] = "merci de confirmer que le confirm mote de passe ressembler au mote de pass";
                }
            } else {
                $errors["passwordError"] = "Confirmer que le mote de passe contient :<br> au moins 1 caractère en majuscule, en minuscule, un muméro, caractère spéciaux, 8 caractère au min !";
            }
        } else {
            $errors['champVide'] = "un ou plusieurs champs de formulaire sont vide !";
        }
    }

    // On ferme la connexion
    $usersModel->closeConnection();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <?php include $pathInclude."inc/head.php" ?>
    <body class="flex-r register-body">
        <div class="return-acceuil">
            <a href="<?php echo $pathLien?>index.php">Acceuil</a>
        </div>
        <section class="register flex-r">
            <div class="container-register flex-c">
                <img src="<?php echo $pathLien?>assets/img/bg-register.jpg" alt="bg-register">
                <h3>Create Your Account</h3>
                <form method="post" class="register-form">
                    <!-- si l'login est déja utiliser -->
                    <?php echo "<p class='error-msg'>".$errors['loginExist']."</p>";?>
                    <div class="form-group">
                        <label for="nom">NOM</label>
                        <input type="text" id="nom" name="nom" value="<?php echo isset($_POST['nom']) ? $_POST['nom'] : ""?>" placeholder="Wahil Ch" pattern="[A-Za-z]{3,25}" title="a-z-A-Z (3-25 characters)" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">PRENOM</label>
                        <input type="text" id="prenom" name="prenom" value="<?php echo isset($_POST['prenom']) ? $_POST['prenom'] : ""?>" placeholder="Wahil Ch" pattern="[A-Za-z]{3,25}" title="a-z-A-Z (3-25 characters)" required>
                    </div>
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="login" id="login" name="login" placeholder="login ex:" value="<?php echo isset($_POST['login']) ? $_POST['login'] : ""?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">PASSWORD</label>
                        <input type="password" id="password" name="password" placeholder="Password" minlength="8" required>
                    </div>
                    <div class="form-group">
                        <label for="cPassword">CONFIRM PASSWORD</label>
                        <input type="password" id="cPassword" name="cPassword" placeholder="Confirm Password" minlength="8" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" id="submit" value="Sign Up">
                    </div>
                    <?php echo "<p class='error-msg'>".$errors['loginError']."</p>";?>
                    <?php echo "<p class='error-msg'>".$errors['identifiantError']."</p>";?>
                    <?php echo "<p class='error-msg'>".$errors['champVide']."</p>";?>
                    <?php echo "<p class='error-msg'>".$errors['passwordError']."</p>";?>
                    <?php echo "<p class='error-msg'>".$errors['confirmPassError']."</p>";?>
                    <?php echo "<p class='error-msg'>".$errors['siteError']."</p>";?>
                </form>
                <p>I'm already a member! <a href="<?php echo $pathLien?>connexion.php">Sign In</a></p>
            </div>
        </section>
    </body>
</html>