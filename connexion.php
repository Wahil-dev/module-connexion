<?php 
    include "inc/config.php";
    include $pathInclude."inc/model.php";
    $usersModel = new ModelUsers();
    $errors = [
        "loginError" => "",
        "passwordError" => "",
        "identifiantError" => "",
        "champVide" => "",
    ];

    // redirection path if connected
    if(isConnected()) {
        header("location: ".$pathLien.isConnected()."/index.php");
        exit();
    }


    if(isset($_SESSION["login"])) {
        if($_SESSION["login"]->role == "user") {
            header("location: ".$pathLien."users/index.php");
            exit();
        } else {
            header("location: ".$pathLien."admin/index.php");
            exit();
        }
    }

    if(isset($_POST["login"]) && isset($_POST["password"])) {
        $login = $_POST["login"];
        $password = $_POST["password"];
        if(!empty($login) && !empty($password)) {
            // validation login
        
            // validation password : il faut qu'il contient
            $uppercase = preg_match('@[A-Z]@', $password); // lettre en majuscule
            $lowercase  = preg_match("@[a-z]@", $password); // lettre en minuscule
            $number = preg_match("@[0-9]@", $password); // un numéro
            $specialChars = preg_match("@[^\w]@", $password); // caractère spéciaux 
            $passLength = strlen($password) >= 8 && strlen($password) < 255; // 8 caractère au min 255 au max
            if($uppercase && $lowercase && $number && $specialChars && $passLength) {
                $userObject = $usersModel->userConnected($login, $password);
                if($userObject) {
                    $_SESSION["login"] = $userObject;
                    header("refresh:0");
                    exit();
                } else {
                    $errors['identifiantError'] = "votre identifiant n'est pas valide !";
                } 
            } else {
                $errors["passwordError"] = "Confirmer que le mote de passe contient :<br> au moins 1 caractère en majuscule, en minuscule, un muméro, caractère spéciaux, 8 caractère au min, 255 ou max !";
            }
        } else {
            $errors['champVide'] = "un ou plusieurs champs de formulaire sont vide !";
        }  
    }
    // on ferme la connexion
    $usersModel->closeConnection();

    if (isset($_SESSION["user_inscrit"])) {
        $user_inscrit = $_SESSION["user_inscrit"];
        echo "<script>
                alert('$user_inscrit vous ètez bien Inscrit !');
            </script>";
        unset($_SESSION['user_inscrit']);
    };
?>

<!DOCTYPE html>
<html lang="fr-FR">
<?php include $pathInclude."inc/head.php" ?>
    <body class="flex-r login-body" style="position: relative">
        <div class="return-acceuil">
            <a href="<?php $pathLien?>index.php">Acceuil</a>
        </div>
        <section class="login flex-r">
            <div class="container-login flex-c">
                <h1>Sign In With</h1>
                <ul class="row-box flex-r">
                    <li><a class="flex-r" href="#"><img src="<?php echo $pathLien?>assets/img/facebook.png">Facebook
                    </a></li>
                    <li><a class="flex-r" href="#"><img src="<?php echo $pathLien?>assets/img/icon-google.png">Google</a></li>
                </ul>

                <!-- ajouter les attributes (aria-describedby et ...) pour aides les gens -->
                <form action="" method="post" class="row-box flex-c">
                    <div class="inp-box">
                        <label for="login">login</label>
                        <input class="inp" type="login" name="login" id="login" value="<?php echo isset($_POST['login']) ? $_POST['login'] : ""?>"maxlength="255" required>
                    </div>
                    <div class="inp-box">
                        <label for="password">Password</label><!-- <a href="#">Forgot?</a> -->
                        <input class="inp" type="password" name="password" id="password" minlength="8" maxlength="255" required>
                    </div>
                    <div class="inp-box">
                        <input class="inp" type="submit" value="Sign In" name="submit" id="submit">
                    </div>
                    <?php echo "<p class='error-msg'>".$errors['loginError']."</p>";?>
                    <?php echo "<p class='error-msg'>".$errors['identifiantError']."</p>";?>
                    <?php echo "<p class='error-msg'>".$errors['champVide']."</p>";?>
                    <?php echo "<p class='error-msg'>".$errors['passwordError']."</p>";?>
                </form>
                <p>Not a member? <a href="<?php echo $pathLien?>inscription.php">Sign up now</a></p>
            </div>
        </section>
    </body>
</html>