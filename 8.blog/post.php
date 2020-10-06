<?php

require('config/config.php');
require('lib/bdd.lib.php');


$errors = [];




try {
    if (array_key_exists('id', $_GET) || (array_key_exists('com_art_id', $_POST))) {
        /* 1 : connexion au SGBDR */
        $dbh = connexion();
        /** PREMIERE REQUÊTE */
        /* 2 : Préparer notre requête !*/
        $sth = $dbh->prepare('SELECT a.*, aut.aut_name,aut.aut_bio,aut.aut_picture
                            FROM articles a
                           INNER JOIN author aut ON (a.art_aut_id1 =aut.aut_id)
                          WHERE a.art_id=:articleid');

        if (isset($_GET['id'])) {
            $sth->bindValue('articleid', $_GET['id']);
        }
        if (isset($_POST['com_art_id'])) {
            $sth->bindValue('articleid', $_POST['com_art_id']);
        }

        $sth->execute();

        /* 5 : récupération du jeu d'enregistrement*/
        $donnees = $sth->fetch();






        var_dump($donnees);
        if (array_key_exists('com_description', $_POST)) {
            $email = $_POST['com_email'];
            if (!isset($_POST['com_pseudo']))
                $errors[] = 'selectionner un pseudo ';
            if (trim($_POST['com_email']) == '')
                $errors[] = 'selectionner un titre';
            if (trim($_POST['com_description']) == '')
                $errors[] = 'selectionner un contenu';
            if (trim($email) == '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $errors[] =  'Erreur email';
            }
            if (count($errors) == 0) {
                $sth2  = $dbh->prepare('INSERT INTO commentaires ( com_art_id,com_pseudo,com_email,com_description,com_time) 
        VALUES ( :com_art_id,:com_pseudo, :com_email,:com_description,:com_time);
           ');
                $sth2->bindValue('com_art_id', $_POST['com_art_id']);
                $sth2->bindValue('com_pseudo', $_POST['com_pseudo']);
                $sth2->bindValue('com_email', $_POST['com_email']);
                $sth2->bindValue('com_description', $_POST['com_description']);
                $sth2->bindValue('com_time', $_POST['com_time']);
                $sth2->execute();
            }
        }
    }
    $sth3 = $dbh->prepare('SELECT * FROM commentaires');
    $sth3->execute();

    $donnees2 = $sth3->fetchAll();
    require('admin/tpl/post.phtml');
} catch (PDOException $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
