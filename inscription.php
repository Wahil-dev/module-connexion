<?php 
    include "inc/config.php";
    include $pathInclude."inc/model.php";
    $usersModel = new ModelUsers();

    if(isset($_SESSION["login"])) {
        if($_SESSION["login"]->role == "user") {
            header("location: ".$pathLien."users/index.php");
            exit();
        } else {
            header("location: ".$pathLien."admin/index.php");
            exit();
        }
    }

    // tableaux d'erreurs
    $errors = [
        "emailError" => "",
        "passwordError" => "",
        "identifiantError" => "",
        "champVide" => "",
        "confirmPassError" => "",
        "emailExist" => "",
        "siteError" => "",
    ];

    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['cPassword'])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $cPassword = $_POST["cPassword"]; //confirm password
        if(!empty($username) && !empty($email) && !empty($password) && !empty($password)) {
            // ajouter des vérifications pour username
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // validation password : il faut qu'il contient
                $uppercase = preg_match('@[A-Z]@', $password); // lettre en majuscule
                $lowercase  = preg_match("@[a-z]@", $password); // lettre en minuscule
                $number = preg_match("@[0-9]@", $password); // un numéro
                $specialChars = preg_match("@[^\w]@", $password); // caractère spéciaux 
                $passLength = strlen($password) >= 8; // 8 caractère au min
                if($uppercase && $lowercase && $number && $specialChars && $passLength) {
                    if($password === $cPassword) {
                        // check if user exist
                        if(!$usersModel->isExist($email)) {
                            // si tout est bon mais il y'a des problème coté serveur, database ...
                            if(!$usersModel->setUser($username, $email, $password)) {
                                $_SESSION["user_inscrit"] = $username;
                                header("location: login.php");
                                exit();
                            } else {
                                $errors["siteError"] = "Error base de donnes !";
                            }
                        } else {
                            $errors["emailExist"] = "Désoli! ce email : ".htmlspecialchars($email)." est déja utiliser !";
                        }
                    } else {
                        $errors['confirmPassError'] = "merci de confirmer que le confirm mote de passe ressembler au mote de pass";
                    }
                } else {
                    $errors["passwordError"] = "Confirmer que le mote de passe contient :<br> au moins 1 caractère en majuscule, en minuscule, un muméro, caractère spéciaux, 8 caractère au min !";
                }
            } else {
                $errors['emailError'] = "Entrer une email valide !";
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
                    <!-- si l'email est déja utiliser -->
                    <?php echo "<p class='error-msg'>".$errors['emailExist']."</p>";?>
                    <div class="form-group">
                        <label for="username">FULL NAME</label>
                        <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ""?>" placeholder="Wahil Ch" pattern="[A-Za-z]{3,25}" title="a-z-A-Z (3-25 characters)" required>
                    </div>
                    <div class="form-group">
                        <label for="Email">EMAIL ADDRESS</label>
                        <input type="email" id="email" name="email" placeholder="wahilchettouf@gmail.com" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ""?>" required>
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
                    <?php echo "<p class='error-msg'>".$errors['emailError']."</p>";?>
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