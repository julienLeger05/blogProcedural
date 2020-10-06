<?php
session_start();
require('../config/config.php');
require('../lib/bdd.lib.php');
require('../lib/app.lib.php');
userIsConnected();
$errors = [];
$view = 'addArticle';
$title = '';
$time = '';
$contenu = '';
$image = '';
$pictureName = '';

try {

    $dbh = connexion();
    $sta = $dbh->prepare('SELECT* FROM categories ');
    $sta->execute();
    $categories = $sta->fetchAll();
    var_dump($_FILES);
    var_dump($_POST);

    if (array_key_exists('art_title', $_POST)) {

        if (array_key_exists('art_picture', $_FILES)) {


            if ($_FILES['art_picture']['error'] == 0) {

                $info =    new SplFileInfo($_FILES['art_picture']['name']);
                if (($info->getExtension()) == 'jpg' || 'png' || 'gif')
                    $pictureName = uniqid() . basename($_FILES['art_picture']['name']);

                move_uploaded_file($_FILES['art_picture']['tmp_name'], '../uploads/articles/' . $pictureName);
            }
        }

        $title = $_POST['art_title'];
        $contenu = $_POST['art_content'];
        $time = $_POST['art_published_date'];
        $valid = isset($_POST['art_valid']);
        $sth = $dbh->prepare('SELECT art_title FROM articles WHERE art_title = :art_title');
        $sth->execute(array(':art_title' => $title));
        $donnees = $sth->fetch();
        if ($donnees != false) // Si une valeur est retournée c'est qu'un membre possède déjà le pseudo.
            $errors[] = 'titre d article deja existant';

        if (!isset($_POST['categories']))
            $errors[] = 'selectionner une categorie ';
        if (trim($title) == '')
            $errors[] = 'selectionner un titre';
        if (trim($time) == '')
            $errors[] = 'selectionner une date';
        if (trim($contenu) == '')
            $errors[] = 'ecriver un contenu';
        if (count($errors) == 0) {

            $req = $dbh->prepare('INSERT INTO articles (art_title,art_content,art_picture,art_created_date,art_published_date,art_valid,art_aut_id1)
        VALUES (:art_title,:art_content,:art_picture,:art_created_date,:art_published_date,:art_valid,:art_aut_id1)
         ');

            $req->bindValue('art_title', $_POST['art_title']);
            $req->bindValue('art_content', $_POST['art_content']);
            $req->bindValue('art_picture', $pictureName);
            $req->bindValue('art_created_date', $_POST['art_created_date']);
            $req->bindValue('art_published_date', $_POST['art_published_date']);
            $req->bindValue('art_valid', isset($_POST['art_valid']), PDO::PARAM_BOOL);
            $req->bindValue('art_aut_id1', $_POST['art_aut_id1']);
            $req->execute();
            $idart = $dbh->lastInsertId();
            foreach ($_POST['categories'] as $_POST['categorie']) {
                $req2 = $dbh->prepare('INSERT INTO articles_has_categories (articles_art_id,categories_cat_id)
          VALUES (:articles_art_id,:categories_cat_id)');
                $req2->bindValue('articles_art_id', $idart);
                $req2->bindValue('categories_cat_id', $_POST['categorie']);
                $req2->execute();
                header('Location: readArticle.php');
            }
        }
    }
} catch (Exception $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
require('../layout/layout.phtml');
