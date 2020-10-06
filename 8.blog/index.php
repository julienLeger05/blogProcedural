<?php
require('config/config.php');
require('lib/bdd.lib.php');
require('lib/app.lib.php');




try {
    $dbh = connexion();
    $sth = $dbh->prepare('SELECT * FROM articles inner join author on art_aut_id1=aut_id ');
    $sth->execute();
    $donnees = $sth->fetchAll();
    var_dump($donnees);
    require('admin/tpl/acceuilArticle.phtml');
} catch (PDOException $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
