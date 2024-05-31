<?php

class AdminMonitoringManager {

  private $db;

  public function __construct() {
      $this->db = DBManager::getInstance()->getPDO();
  }

  public function fetchArticles() {
    
    // Nombre de vues
    $statementViews = $this->db->prepare("SELECT article_id, COUNT(*) AS view_count FROM view GROUP BY article_id");
    $statementViews->execute();
    $viewCounts = $statementViews->fetchAll(PDO::FETCH_ASSOC);

    //Nombre de commentaires
    $statementComments = $this->db->prepare("SELECT a.id, COUNT(c.id) AS comment_count
    FROM article AS a
    LEFT JOIN comment AS c ON a.id = c.id_article
    GROUP BY a.id"
    );
    $statementComments->execute();
    $commentCounts = $statementComments->fetchAll(PDO::FETCH_ASSOC);

    // Les données de base des articles
    $statementArticles = $this->db->prepare("SELECT id, title, date_creation FROM article");
    $statementArticles->execute();
    $articles = $statementArticles->fetchAll(PDO::FETCH_ASSOC);

    // Détails supplémentaires des articles
    $articlesData = [];
    foreach ($articles as $article) {
        $articleId = $article['id'];
        $viewCount = 0; 
        foreach ($viewCounts as $view) {
            if ($view['article_id'] == $articleId) {
                $viewCount = $view['view_count'];
                break;
            }
        }
        $commentCount = 0;
        foreach ($commentCounts as $comment) {
            if ($comment['id'] == $articleId) {
                $commentCount = $comment['comment_count'];
                break;
            }
        }
        $articlesData[] = [
            'id' => $articleId,
            'title' => $article['title'],
            'view_count' => $viewCount,
            'comment_count' => $commentCount,
            'date_creation' => $article['date_creation']
        ];
    }
    return $articlesData;
  }
}