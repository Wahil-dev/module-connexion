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
            $this->dbname = "wahil";
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
        public $tbname = "users";

        public function userConnected($email, $password) {
            $request = $this->db->prepare("SELECT * FROM ". $this->getTableName() ." WHERE password=:password &&email=:email");
            $request->bindValue("email", $email);
            $request->bindValue("password", $password);
            $request->execute();
            return $request->fetchObject(); // return un object s'il exist ou rien s'il n'est pas inscrit déja
        }   

        public function isExist($email) {
            $sth = $this->db->prepare("SELECT * FROM ". $this->getTableName() ." WHERE email = :email");
            $sth->bindValue("email", $email);
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
        function setUser($username, $email, $password, $role = "user") {
            $request = $this->db->prepare("INSERT INTO ". $this->getTableName() ."(username, email, password, role) VALUES(?, ?, ?, ?)");
            $request->bindParam(1, $username);
            $request->bindParam(2, $email);
            $request->bindParam(3, $password);
            $request->bindParam(4, $role);
            $request->execute();
            return $request->fetch(PDO::FETCH_ASSOC);
        }
        // pour modifier le profile
        public function updateProfile($nouveauNom, $nouveauEmail, $nouveauPassword, $nouveauMetier, $nouveauPresentation) {
            $request = $this->db->prepare("UPDATE ". $this->getTableName() ." SET username = :nouveauNom, email = :nouveauEmail, password = :nouveauPassword, job = :nouveauMetier, presentation = :nouveauPresentation WHERE id= :idUserCurrent");
            $request->bindValue("nouveauNom", $this->isEmpty($nouveauNom, $_SESSION['login']->username));
            $request->bindValue("nouveauEmail", $this->isEmpty($nouveauEmail, $_SESSION['login']->email));
            $request->bindValue("nouveauPassword", $this->isEmpty($nouveauPassword, $_SESSION['login']->password));
            $request->bindValue("nouveauMetier", $this->isEmpty($nouveauMetier, $_SESSION['login']->job));
            $request->bindValue("nouveauPresentation", $this->isEmpty($nouveauPresentation, $_SESSION['login']->presentation));    

            $request->bindValue("idUserCurrent", $_SESSION['login']->id);
            $request->execute();
            return $request->fetch();
        }
    }
