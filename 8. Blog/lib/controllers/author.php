<?php
$titlePage = 'Page auteur';
$subTitlePage = 'Les article de : ';
$picturePage = 'img/home-bg.jpg';
$view ='author';

/** On charge le modèle User */
require('lib/models/user.php');
/** On charge le modèle Article */
require('lib/models/article.php');

if (!array_key_exists('id', $_GET))
    throw new DomainException('Accès à la page non autorisé !');


$user = getUserById($dbh, $_GET['id']);

if($user == false)
    throw new DomainException('Cet auteur n\'existe pas !');

$articles = getArticlesByUserId($dbh, $_GET['id']);

$subTitlePage.= $articles[0]['use_firstname'].' '. $articles[0]['use_lastname'];

foreach ($articles as $index=>$article) {
    $articles[$index]['art_content'] = mb_strimwidth(str_replace(['&eacute;','&egrave;', '&rsquo;'], ['é','è',"'"], strip_tags($article['art_content'])), 0, RESUME_LENGTH, '...');
    $articles[$index]['art_date_published'] = (new DateTime($article['art_date_published']))->format('d/m/Y');
}  

