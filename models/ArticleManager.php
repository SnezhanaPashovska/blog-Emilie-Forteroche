<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager 
{
    /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles() : array
    {
        $sql = "SELECT * FROM article";
        $result = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            error_log("Fetched article data: " . print_r($article, true));
            $articles[] = new Article($article);
        }
        return $articles;
    }
    
    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id) : ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article) : void 
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article) : void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article) : void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'id' => $article->getId(),
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id) : void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

    // Compter les vues
    public function recordView($articleId) : void {
        try {
          $dbManager = DBManager::getInstance();
          $pdo = $dbManager->getPDO();
    
            // Insert a new record into the view table
            $statement = $pdo->prepare("INSERT INTO view (article_id, view_count) VALUES (:article_id, NOW())");
            $statement->bindParam(':article_id', $articleId, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error recording view: " . $e->getMessage();
        }
    }

    //trier le tableau

    public function filterTable($sortCriteria)
{
    // Define the SQL query based on the sorting criteria
    switch ($sortCriteria) {
        case 'title': 
            $orderBy = 'title';
            break;
        case 'view_count': 
            $orderBy = 'view_count';
            break;
        case 'comment_count': 
            $orderBy = 'comment_count';
            break;
        case 'date_creation': 
            $orderBy = 'date_creation';
            break;
        default: 
            $orderBy = 'date_creation';
            break;
    }

    // Default order is ascending
    $order = 'asc';

    // Toggle order if the same criteria is used
    if (isset($_GET['sort']) && $_GET['sort'] === $sortCriteria) {
        $order = ($_GET['order'] === 'asc') ? 'desc' : 'asc';
    }

    // Construire la requête SQL avec les critères de tri choisis et l'ordre
    $sql = "SELECT a.id, a.title, a.date_creation, 
                   SUM(IFNULL(v.view_count, 0)) AS view_count, -- Total des vues pour chaque article
                   SUM(IFNULL(c.comment_count, 0)) AS comment_count -- Total des commentaires pour chaque article
            FROM article AS a
            LEFT JOIN (         -- Jointure externe gauche pour récupérer les vues associées à chaque article
                SELECT article_id, COUNT(*) AS view_count
                FROM view
                GROUP BY article_id
            ) AS v ON a.id = v.article_id
            LEFT JOIN (         -- Jointure externe gauche pour récupérer les commentaires associés à chaque article
                SELECT id_article, COUNT(*) AS comment_count
                FROM comment
                GROUP BY id_article     
            ) AS c ON a.id = c.id_article
            GROUP BY a.id, a.title, a.date_creation -- Regrouper les résultats par article pour éviter les doublons
            ORDER BY $orderBy $order"; //Trier les articles selon les critères spécifiés (colonne et ordre)

    // Get PDO instance from DBManager
    $pdo = $this->db->getPDO();
     // Préparer la requête SQL
     $stmt = $pdo->prepare($sql);
     // Exécuter la requête
     $stmt->execute();

    // Récupérer les articles triés à partir de la requête exécutée
    $sortedArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Retourner les articles triés
    return $sortedArticles;
}

}