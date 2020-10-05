<?php
$titlePage = 'Page catégories';
$subTitlePage = 'Les catégories du blog ;)';
$picturePage = 'img/home-bg.jpg';
$view ='categories';

/** On charge le modèle Category */
require('lib/models/category.php');

/** PREPARATION DES CATEGORIES
 * On sélectionne toutes les catégories
 */
$categories = getCategories($dbh);

/**  On va créer un tableau des catégorie hiérarchisée pour afficher des ul>li hiérarchiques (arbre des catégories parent/enfants)
 * Utilisation d'une fonction récursive (pour l'exemple algorithmique !).
 */
$orderedCategories = orderCategoriesLevel($categories);
