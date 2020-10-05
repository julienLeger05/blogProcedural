<?php


/** Les variables qui servent au layout et à la vue ! */
$titlePage = 'gAmE & cOdE';
$subTitlePage = 'du JeuX eT dU cOdE';
$picturePage = 'img/home-bg.jpg';
$view ='home';

$startArticle = 0;


/** On charge le modèle Article */
require('lib/models/Article.php');

$articleModel = new Article($dbh);
/** GESTION DE LA PAGINATION */
// On compte combien on a d'articles dans la base pour la pagination
$nbArticles = $articleModel->getCount();

// On détermine le nombre max que peut prendre la valeur start dans l'url
$tmpMaxNumberStart = intval($nbArticles['nb'] / MAX_ARTICLES_BY_PAGE) * MAX_ARTICLES_BY_PAGE;

// On défini la nouvelle valeur de start si le paramètre dans l'url est existe et est cohérent (> 0 et <$tmpMaxNumberStart)
if (array_key_exists('start', $_GET) && intval($_GET['start']) > 0 && intval($_GET['start']) <= $tmpMaxNumberStart)
    $startArticle = intval($_GET['start'], 10);


/** PREPARATION DES ARTICLES
 * On sélectionne tous les articles avec les données auteur et catégories
 * On sélectionne les articles en fonction de MAX_ARTICLES_BY_PAGE et $startArticle
 */
$articles = $articleModel->getWithLimit($startArticle);

// On modifie les données du jeu d'enregistrement pour le résumé de l'article et la date
foreach($articles as $index=>$article)
{
    $articles[$index]['art_content'] = mb_strimwidth(str_replace(['&eacute;','&egrave;', '&rsquo;'],['é','è',"'"],strip_tags($article['art_content'])), 0, RESUME_LENGTH, '...');
    $articles[$index]['art_date_published'] = (new DateTime($article['art_date_published']))->format('d/m/Y');
}
