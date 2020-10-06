<?php
session_start();
require('../config/config.php');
require('../lib/bdd.lib.php');
require('../lib/app.lib.php');
userIsConnected();
$errors = [];


try {

    $dbh = connexion();

    var_dump($_FILES);
    var_dump($_POST);
    var_dump($_GET);

    if (array_key_exists('id', $_GET)) {

        $view = 'delArticle';
    }
    if (array_key_exists('art_valid', $_POST)) {

        $req4 = $dbh->prepare('SELECT art_picture,art_id 
                                FROM articles
                                WHERE art_id=:art_id');
        $req4->bindValue('art_id', $_POST['art_valid']);
        $req4->execute();
        $article = $req4->fetch();

        /** On supprime la photo ! */
        $pathPicture =  '../uploads/articles/' . $article['art_picture'];
        var_dump($article);
        var_dump(file_exists($pathPicture));
        if (file_exists($pathPicture)) {
            unlink($pathPicture);
        }


        $req2 = $dbh->prepare('DELETE FROM articles_has_categories
            where articles_art_id=:art_id
            ');
        $req2->bindValue('art_id', $_POST['art_valid']);
        $req2->execute();

        $req3 = $dbh->prepare(' DELETE  FROM commentaires
        WHERE com_art_id=:art_id
         ');
        $req3->bindValue('art_id', $_POST['art_valid']);
        $req3->execute();

        $req = $dbh->prepare(' DELETE  FROM articles
        WHERE art_id=:art_id
         ');
        $req->bindValue('art_id', $_POST['art_valid']);
        $req->execute();;
        //  header('Location: index.php');
    }
} catch (Exception $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
require('../layout/layout.phtml');
