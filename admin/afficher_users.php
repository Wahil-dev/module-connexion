<?php 
    include "../inc/config.php";
    include $pathInclude."inc/model.php";
    $usersModel = new ModelUsers();
    $users = $usersModel->getUsers();
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

    // delete user
    if(isset($_GET["delete"])) {
        $userId = $_GET["delete"];
        $usersModel->deleteById($userId, $usersModel->getTableName());
        header("location:".$_SERVER['PHP_SELF']);
        exit();
    }

    // vérifier si vous avez bien créez un nouvelle utlisateur
    if(isset($_SESSION["user_inscrit"])) :?>
        <script>
            alert("<?php echo $_SESSION['login']->username?> vous aves bien créez l'utilisateur <?php echo $_SESSION['user_inscrit']?>")
        </script>
        <?php unset($_SESSION["user_inscrit"])?>
    <?php endif ?>
<?php ?>

<!DOCTYPE html>
<html lang="fr-FR">
<?php require_once($pathInclude."inc/head.php")?>
<body>
    <div class="content">
        <?php require_once($pathInclude."inc/header.php");?>
        <main>
            <section class="box-content">
                <h2 class="title-page">manage users</h2> 
                <?php if(!empty($users)) :?>
                    <table class='user-table'>
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>login</th>
                                <th>nom</th>
                                <th>prenom</th>
                                <th>password</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($i=0; isset($users[$i]); $i++) :?>
                                <tr>
                                    <td><?= $users[$i]['id'] ?></td>
                                    <td><?= htmlspecialchars($users[$i]['login']) ?></td>
                                    <td><?= htmlspecialchars($users[$i]['nom']) ?></td>
                                    <td><?= htmlspecialchars($users[$i]['prenom']) ?></td>
                                    <td><?= htmlspecialchars($users[$i]['password']) ?></td>
                                    <td class="action-content">
                                    <ul class="flex-r">
                                        <li><a href="" class="first-action">edit</a></li>
                                        <li><a href="?delete=<?php echo $users[$i]['id']?>" class="second-action">delete</a></li>
                                    </ul>
                                </td>
                                </tr>
                            <?php endfor ?>
                        </tbody>
                    </table>
                <?php else :?>
                    <p>Aucune utilisateur existe!</p>
                <?php endif ?>
            </section>
        </main>
    </div>
    <?php
        // On ferme la connexion
        $usersModel->closeConnection();
    ?>
</body>
</html>