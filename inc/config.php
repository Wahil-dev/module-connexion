<?php
    $pathLien = "/laplateforme/module-connexion/"; // pour les images
    $pathInclude =  dirname(__DIR__).'/'; // pour les include et requere blog

    function isConnected() {
        if(isset($_SESSION["login"])) {
            if($_SESSION["login"]->login == "admin") {
                return "admin";
            } else {
                return 'users';
            }
        } else {
            return false;
        }
    }
?>