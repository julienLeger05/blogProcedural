<?php
session_start();
require('../config/config.php');
require('../lib/bdd.lib.php');
require('../lib/app.lib.php');
userIsConnected();

$view = 'readArticle';

try {
    $dbh = connexion();
    $sth = $dbh->prepare('SELECT * FROM articles a
                        INNER JOIN author a3 on a3.aut_id=a.art_aut_id1');

    //[ 0 => ['id'=>10,'title'=>'titre'], 1=>['id'=>11,'title'=>'Titre art 11']]
    //[ 0 => ['id'=>10,'title'=>'titre','categories'=>[0=>['idCat'=>1,'title'=>'Cat1'],1=>]], 1=>['id'=>11,'title'=>'Titre art 11']]

    $sth->execute();
    $donnees = $sth->fetchAll();
    foreach ($donnees as $index => $donnee) {
        $sth2 = $dbh->prepare('SELECT cat_title FROM articles_has_categories
                            INNER JOIN categories   ON cat_id=categories_cat_id WHERE articles_art_id=:articles_art_id
                            
                            
                            ');
        $sth2->bindValue('articles_art_id', $donnee['art_id']);
        $sth2->execute();
        $donnees[$index]['categories'] = $sth2->fetchAll();
    }
    var_dump($donnees);
    require('../layout/layout.phtml');
} catch (PDOException $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
