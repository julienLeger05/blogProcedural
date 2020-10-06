<?php
session_start();
require('../config/config.php');
require('../lib/bdd.lib.php');
require('../lib/app.lib.php');
userIsConnected();

$view = 'ReadUser';

try {
    $dbh = connexion();
    $sth = $dbh->prepare('SELECT * FROM author');
    $sth->execute();
    $donnees = $sth->fetchAll();
    require('../layout/layout.phtml');
} catch (PDOException $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
