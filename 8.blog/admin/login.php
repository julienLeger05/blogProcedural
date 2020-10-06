<?php

session_start();

require('../config/config.php');
require('../lib/bdd.lib.php');

$errors = [];
$password = '';

try {

    if (array_key_exists('aut_email', $_POST)) {

        $dbh = connexion();
        $req = $dbh->prepare('SELECT * FROM author WHERE aut_email = :aut_email ');
        $req->bindValue('aut_email', $_POST['aut_email']);

        $req->execute();
        $donnees = $req->fetch();

        if ($donnees != false) {

            if (password_verify($_POST['aut_password'], $donnees['aut_password']) == true) {

                $_SESSION['connected'] = true;
                $_SESSION['user'] = $donnees;

                header('Location:index.php');
                exit();
            } else
                $errors[] = 'Erreur';
        } else {
            echo ('access denied');
            $errors[] =  'Erreur';
        }
    }
} catch (Exception $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
require('tpl/login.phtml');
