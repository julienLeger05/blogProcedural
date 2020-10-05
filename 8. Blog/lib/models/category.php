<?php

/** MODELE DES CATEGORIES */

//const TABLE = DB_PREFIXE.'categorie';

function getCategoryById($dbh, $id)
{

    $sth = $dbh->prepare('SELECT cat_id, cat_title
                         FROM ' . DB_PREFIXE . 'categorie 
                        WHERE cat_id = :idCategory');
    $sth->bindValue('idCategory', $id);
    $sth->execute();
    $category = $sth->fetch();

    return $category;
}



function getCategories($dbh)
{
    $sth = $dbh->prepare('SELECT c1.*, c2.cat_title as parent, COUNT(a.art_id) as articles, art_valide, art_date_published  
                    FROM '. DB_PREFIXE . 'categorie c1 
                    LEFT JOIN ' . DB_PREFIXE . 'categorie c2 ON c1.cat_parent=c2.cat_id 
                    LEFT JOIN ' . DB_PREFIXE . 'article a ON c1.cat_id = a.art_categorie 
                    GROUP BY c1.cat_id,c2.cat_id 
                    ORDER BY c1.cat_title, c1.cat_parent');
    /* tri sur le fait qu'un article soit en ligne à valider
  WHERE art_valide = 1 AND art_date_published <= NOW()
  HAVING articles = 0 OR (art_valide = 1 AND art_date_published <= NOW())
*/
    $sth->execute();
    $categories = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $categories;
}

