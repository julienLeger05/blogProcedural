<?php
session_start();
require('../config/config.php');
require('../lib/bdd.lib.php');
require('../lib/app.lib.php');
userIsConnected();
$errors = [];
$view = 'addCat';
$title = '';
$desc = '';

$pictureName = '';
try {

    if (array_key_exists('cat_title', $_POST)) {
        $title = $_POST['cat_title'];
        $desc = $_POST['cat_description'];


        var_dump($_FILES);
        if (array_key_exists('cat_picture', $_FILES)) {


            if ($_FILES['cat_picture']['error'] == 0) {

                $info =    new SplFileInfo($_FILES['cat_picture']['name']);
                if (($info->getExtension()) == 'jpg' || 'png' || 'gif')
                    $pictureName = uniqid() . basename($_FILES['cat_picture']['name']);

                move_uploaded_file($_FILES['cat_picture']['tmp_name'], '../uploads/cat/' . $pictureName);
            }
        }
        $dbh = connexion();
        $sth = $dbh->prepare('SELECT cat_title FROM categories WHERE cat_title = :cat_title');
        $sth->execute(array(':cat_title' => $title));
        $donnees = $sth->fetch();
        if ($donnees != false) // Si une valeur est retournée c'est qu'un membre possède déjà le pseudo.
            $errors[] = 'categorie deja existante';

        if (count($errors) == 0) {
            $req = $dbh->prepare('INSERT INTO categories (cat_title,cat_description,cat_picture)
        VALUES (:cat_title,:cat_description,:cat_picture)
         ');

            $req->bindValue('cat_title', $_POST['cat_title']);
            $req->bindValue('cat_description', $_POST['cat_description']);
            $req->bindValue('cat_picture', $pictureName);
            $req->execute();
        }
    }
} catch (Exception $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
require('../layout/layout.phtml');
