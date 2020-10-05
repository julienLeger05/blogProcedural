<?php

/** MODELE DES USERS */

//const TABLE = DB_PREFIXE . 'user';

function getUserById($dbh, $id)
{
    $sth = $dbh->prepare('SELECT use_id
                    FROM '.DB_PREFIXE . 'user
                    WHERE use_id = :idAuthor');
    $sth->bindValue('idAuthor', $id);
    $sth->execute();
    $user = $sth->fetch();

    return $user;
}
