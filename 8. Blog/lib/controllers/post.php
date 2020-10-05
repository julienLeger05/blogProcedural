<?php
/** Les variables qui servent au layout et à la vue ! */
$titlePage = '';
$subTitlePage = '';
$picturePage = '';
$metaPage = true;
$view ='post';


$nameComment = '';
$emailComment = '';
$contentComment = '';

/** On charge le modèle Article */
require('lib/models/article.php');

/** Si on a pas d'id fourni on lance une exception DomainException 
 * Du coup la suite du code dans le try ne sera pas executé !
*/
if(!array_key_exists('id',$_GET))
    throw new DomainException('Accès à la page non autorisé !');
    
/** On récupère l'article dans la base */
$article = getArticleById($dbh, $_GET['id']);


/** Si l'article n'est pas trouvé on lance une exception DomainException
 * Le reste du code dans le try ne sera pas executé !
 */
if ($article === false)
    throw new DomainException('L\'article demandé n\'existe pas !');

// On modifie les données du jeu d'enregistrement pour le résumé de l'article et la date
$article['art_date_published'] = (new DateTime($article['art_date_published']))->format('d/m/Y');

// On modifie les variables de templates en fonction de l'article
$titlePage = $article['art_title'];
$picturePage = UPLOADS_URL . 'articles/' . $article['art_picture'];
$metaPage = 'Posté par <a href="author.php?id='.$article['art_author'].'">'.$article['use_firstname'].' '.$article['use_lastname'].'</a>
                le '.$article['art_date_published'].'- Dans <a href="category.php?id='.$article['art_categorie'].'">'.$article['cat_title'].'</a>';



