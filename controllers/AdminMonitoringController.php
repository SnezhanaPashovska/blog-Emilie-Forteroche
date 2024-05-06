<?php

require_once (__DIR__ . '/../models/User.php');


class AdminMonitoringController {

  //Méthode pour afficher les articles avec des details supplementaires
  public function showAdminMonitoring() : void 
  {

        // Instantiate the AdminController
    $adminController = new AdminController();

    // Call the monitoringPage() method
    $adminController->monitoringPage();

    //Fetch data
    $articles = $this->fetchArticles();

    //render the view
    $view = new View("Articles avec détails");
    $view->render("adminMonitoring", ['articles' => $articles]);
  }

  private function fetchArticles() {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Nombre de vues
    $statementViews = $pdo->prepare("SELECT article_id, COUNT(*) AS view_count FROM view GROUP BY article_id");
    $statementViews->execute();
    $viewCounts = $statementViews->fetchAll(PDO::FETCH_ASSOC);

    // Nombre de commentaires
    $statementComments = $pdo->prepare("SELECT a.id, COUNT(c.id) AS comment_count
    FROM article AS a
    LEFT JOIN comment AS c ON a.id = c.id_article
    GROUP BY a.id"
    );
    $statementComments->execute();
    $commentCounts = $statementComments->fetchAll(PDO::FETCH_ASSOC);

    // Articles
    $statementArticles = $pdo->prepare("SELECT id, title, date_creation FROM article");
    $statementArticles->execute();
    $articles = $statementArticles->fetchAll(PDO::FETCH_ASSOC);

    // Article data avec nombre de vues et nombre de commentaires
    $articlesData = [];
    foreach ($articles as $article) {
        $articleId = $article['id'];
        $viewCount = 0; // Default to 0 if no views found for an article
        foreach ($viewCounts as $view) {
            if ($view['article_id'] == $articleId) {
                $viewCount = $view['view_count'];
                break;
            }
        }
        $commentCount = 0; // Default to 0 if no comments found for an article
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