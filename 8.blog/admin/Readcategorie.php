<?php
session_start();
require('../config/config.php');
require('../lib/bdd.lib.php');
require('../lib/app.lib.php');
userIsConnected();

$view = 'Readcategorie';

try {
    $dbh = connexion();
    $sth = $dbh->prepare('SELECT * FROM categories');
    $sth->execute();
    $donnees = $sth->fetchAll();
    var_dump($donnees);
    require('../layout/layout.phtml');
} catch (PDOException $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
