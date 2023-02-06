<?php
    session_start();
    class Connection {
        public $severname;
        public $user;
        public $pass;
        public $dbname;
        public $db;
        //public $tbname;
        public function __construct(/*$tbname*/) {
            $this->severname = "localhost";
            $this->user = "root";
            $this->pass = "";
            $this->dbname = "moduleconnexion";
            //$this->tbname = $tbname;
            try {
                $this->db = new PDO("mysql:host=".$this->severname."; dbname=".$this->dbname."; charset=utf8",$this->user, $this->pass);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->db;
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }

        public function closeConnection() {
            // On ferme la connexion
            $this->db = null;
            return $this->db;
        }

        public function replaceQuote($text) {
            $singleQuote = str_replace("'", "\'", $text);
            $result = str_replace('"', '\"', $singleQuote);
            return $result;
        }

        // return la valeur par défaut si la valeur est vide (update profile)
        public function isEmpty($val, $valParDefaut) {
            if(!isset($val) || empty($val)) {
                return $valParDefaut;
            }
            return $val;
        }

        public function deleteById($id, $tbname) {
            $request = $this->db->prepare('DELETE FROM '.$tbname.' WHERE id=:id');
            $request->bindValue("id", $id);
            $request->execute();
            return $request->fetch(PDO::FETCH_ASSOC);
        }

        // utiliser cette methode s tu veux entrer le nom de table (data base) sur toutes les object créer (class connexion, child class)
        // public function getTableName() {
        //     return $this->tbname;
        // }

        public function checkIfExistById($id, $tbname) {
            $request = $this->db->prepare("SELECT * FROM ". $tbname ." WHERE id=:id");
            $request->bindValue("id", $id);
            $request->execute();
            return $request->fetchObject();
        }
    }

    class ModelUsers extends Connection {
        public $tbname = "utilisateurs";

        public function userConnected($login, $password) {
            $request = $this->db->prepare("SELECT * FROM ". $this->getTableName() ." WHERE password=:password && login=:login");
            $request->bindValue("login", $login);
            $request->bindValue("password", $password);
            $request->execute();
            return $request->fetchObject(); // return un object s'il exist ou rien s'il n'est pas inscrit déja
        }   

        public function isExist($login) {
            $sth = $this->db->prepare("SELECT * FROM ". $this->getTableName() ." WHERE login = :login");
            $sth->bindValue("login", $login);
            $sth->execute();
            return !empty($sth->fetch());
        }

        // ------------ Getters ------------
        function getUsers() {
            $sth = $this->db->prepare('SELECT * FROM '. $this->getTableName());
            $sth->execute();
            $categories = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $categories;
        }

        function getUserDataById($userId) {
            $request = $this->db->prepare("SELECT * FROM ". $this->getTableName() ." WHERE id = $userId");
            $request->execute();
            return $request->fetchObject();
        }

        public function getTableName() {
            return $this->tbname;
        }


        // ------------- Setters ------------- -
        function setUser($nom, $prenom, $login, $password,) {
            $request = $this->db->prepare("INSERT INTO ". $this->getTableName() ."(login, nom, prenom, password) VALUES(?, ?, ?, ?)");
            $request->bindParam(2, $nom);
            $request->bindParam(3, $prenom);
            $request->bindParam(1, $login);
            $request->bindParam(4, $password);
            $request->execute();
            return $request->fetch(PDO::FETCH_ASSOC);
        }
        // pour modifier le profile
        public function updateProfile($nouveauNom, $nouveauPrenom, $nouveaulogin, $nouveauPassword, $nouveauMetier, $nouveauPresentation) {
            $request = $this->db->prepare("UPDATE ". $this->getTableName() ." SET nom = :nouveauNom, prenom = :nouveauPrenom, login = :nouveaulogin, password = :nouveauPassword, job = :nouveauMetier, presentation = :nouveauPresentation WHERE id= :idUserCurrent");
            $request->bindValue("nouveauNom", $this->isEmpty($nouveauNom, $_SESSION['login']->nom));
            $request->bindValue("nouveauPrenom", $this->isEmpty($nouveauPrenom, $_SESSION['login']->prenom));
            $request->bindValue("nouveaulogin", $this->isEmpty($nouveaulogin, $_SESSION['login']->login));
            $request->bindValue("nouveauPassword", $this->isEmpty($nouveauPassword, $_SESSION['login']->password));
            $request->bindValue("nouveauMetier", $this->isEmpty($nouveauMetier, $_SESSION['login']->job));
            $request->bindValue("nouveauPresentation", $this->isEmpty($nouveauPresentation, $_SESSION['login']->presentation));    

            $request->bindValue("idUserCurrent", $_SESSION['login']->id);
            $request->execute();
            return $request->fetch();
        }
    }
