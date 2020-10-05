<?php
/** MODELE DES ARTICLES */

class Article
{
    const TABLE = DB_PREFIXE . 'article';

    private $dbh;

    /** Le constructeur de l'objet Article */
    public function __construct($connexion)
    {
        $this->dbh = $connexion;
    }

    public function getById($id)
    {
        $sth = $this->dbh->prepare('SELECT b_article.*,use_firstname,use_lastname,cat_title
                    FROM ' . self::TABLE . '
                    INNER JOIN b_user ON (use_id = art_author)
                    INNER JOIN b_categorie ON (cat_id = art_categorie)
                    WHERE art_id = :idArticle AND art_valide = 1 AND art_date_published <= NOW()
                    ORDER BY art_date_published DESC');
        $sth->bindValue('idArticle', $id);
        $sth->execute();
        $article = $sth->fetch();

        return $article;
    }

    public function getCount()
    {
        $sth = $this->dbh->prepare('SELECT COUNT(art_id) as nb
                        FROM ' . self::TABLE . '
                        WHERE art_valide = 1 AND art_date_published <= NOW()');
        $sth->execute();
        $nbArticles = $sth->fetch();
        return $nbArticles;
    }

    public function getWithLimit($start, $nbArticles = MAX_ARTICLES_BY_PAGE)
    {
        $sth = $this->dbh->prepare('SELECT b_article.*,use_firstname,use_lastname,cat_title
                        FROM ' . self::TABLE . '
                        INNER JOIN b_user ON (use_id = art_author)
                        INNER JOIN b_categorie ON (cat_id = art_categorie)
                        WHERE art_valide = 1 AND art_date_published <= NOW()
                        ORDER BY art_date_published DESC
                        LIMIT :start,:end');
        $sth->bindValue('start', $start, PDO::PARAM_INT);
        $sth->bindValue('end', $nbArticles, PDO::PARAM_INT);
        $sth->execute();
        $articles = $sth->fetchAll();

        return $articles;
    }

    public function getByCategoryId($id)
    {
        $sth = $this->dbh->prepare('SELECT *,use_firstname,use_lastname,cat_title
                         FROM ' . self::TABLE . '
                        INNER JOIN b_user ON (use_id = art_author)
                        INNER JOIN b_categorie ON (cat_id = art_categorie)
                        WHERE art_categorie = :idCategory AND art_valide = 1 AND art_date_published <= NOW()
                        ORDER BY art_date_published DESC');
        $sth->bindValue('idCategory', $id);
        $sth->execute();
        $articles = $sth->fetchAll();
        return $articles;
    }

    public function getByUserId($id)
    {
        $sth = $this->dbh->prepare('SELECT b_article.*,use_firstname,use_lastname,cat_title
                     FROM ' . self::TABLE . '
                    INNER JOIN b_user ON (use_id = art_author)
                    INNER JOIN b_categorie ON (cat_id = art_categorie)
                    WHERE art_author = :idAuthor AND art_valide = 1 AND art_date_published <= NOW()
                    ORDER BY art_date_published DESC');
        $sth->bindValue('idAuthor', $id);
        $sth->execute();
        $articles = $sth->fetchAll();

        return $articles;
    }
}