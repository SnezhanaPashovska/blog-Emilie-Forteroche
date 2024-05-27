<?php

class AdminMonitoringManager {

// Connexion à la base de données avec PDO
  private $db;

  public function __construct() {
      $this->db = DBManager::getInstance()->getPDO();
  }

  public function fetchArticles() {
    
    // Requête pour obtenir le nombre de vues pour chaque article
    $statementViews = $this->db->prepare("SELECT article_id, COUNT(*) AS view_count FROM view GROUP BY article_id");
    $statementViews->execute();
    $viewCounts = $statementViews->fetchAll(PDO::FETCH_ASSOC);

    // Requête pour obtenir le nombre de commentaires pour chaque article
    $statementComments = $this->db->prepare("SELECT a.id, COUNT(c.id) AS comment_count
    FROM article AS a
    LEFT JOIN comment AS c ON a.id = c.id_article
    GROUP BY a.id"
    );
    $statementComments->execute();
    $commentCounts = $statementComments->fetchAll(PDO::FETCH_ASSOC);

    // Requête pour obtenir les données de base des articles
    $statementArticles = $this->db->prepare("SELECT id, title, date_creation FROM article");
    $statementArticles->execute();
    $articles = $statementArticles->fetchAll(PDO::FETCH_ASSOC);

    // Traitement des données pour obtenir les détails supplémentaires des articles
    $articlesData = [];
    foreach ($articles as $article) {
        $articleId = $article['id'];
        $viewCount = 0; // Par défaut 0 si aucune vue n'est trouvée pour un article
        foreach ($viewCounts as $view) {
            if ($view['article_id'] == $articleId) {
                $viewCount = $view['view_count'];
                break;
            }
        }
        $commentCount = 0; // Par défaut 0 si aucun commentaire n'est trouvé pour un article
        foreach ($commentCounts as $comment) {
            if ($comment['id'] == $articleId) {
                $commentCount = $comment['comment_count'];
                break;
            }
        }
        // Ajout des détails supplémentaires de l'article dans le tableau
        $articlesData[] = [
            'id' => $articleId,
            'title' => $article['title'],
            'view_count' => $viewCount,
            'comment_count' => $commentCount,
            'date_creation' => $article['date_creation']
        ];
    }
    
    // Retourne les données des articles avec des détails supplémentaires
    return $articlesData;
  }
}