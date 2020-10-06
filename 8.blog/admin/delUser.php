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

        $view = 'delAuthor';
    }
    if (array_key_exists('aut_valid', $_POST)) {

        $req4 = $dbh->prepare('SELECT aut_picture,aut_id 
                                FROM author
                                WHERE aut_id=:aut_id');
        $req4->bindValue('aut_id', $_POST['aut_valid']);
        $req4->execute();
        $article = $req4->fetch();

        /** On supprime la photo ! */
        $pathPicture =  '../uploads/author/' . $article['aut_picture'];
        var_dump($article);
        var_dump(file_exists($pathPicture));
        if (file_exists($pathPicture)) {
            unlink($pathPicture);
        }



        $req2 = $dbh->prepare('DELETE FROM articles_has_categories
            where articles_art_id=:aut_id
            ');
        $req2->bindValue('aut_id', $_POST['aut_valid']);
        $req2->execute();

        $req3 = $dbh->prepare(' DELETE  FROM commentaires
        WHERE com_art_id=:art_aut_id1
         ');
        $req3->bindValue('aut_id', $_POST['aut_valid']);
        $req3->execute();

        $req = $dbh->prepare(' DELETE  FROM articles
        WHERE art_aut_id1=:aut_id
         ');
        $req->bindValue('aut_id', $_POST['aut_valid']);
        $req->execute();;
        //  header('Location: index.php');


        $req = $dbh->prepare(' DELETE  FROM author
        WHERE aut_id=:aut_id
         ');
        $req->bindValue('aut_id', $_POST['aut_valid']);
        $req->execute();;
        //  header('Location: index.php');
    }
} catch (Exception $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
require('../layout/layout.phtml');
