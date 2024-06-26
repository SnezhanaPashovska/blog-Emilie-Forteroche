<?php 

class ArticleController 
{
    /**
     * Affiche la page d'accueil.
     * @return void
     */
    public function showHome() : void
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        $view = new View("Accueil");
        $view->render("home", ['articles' => $articles]);
    }

    /**
     * Affiche le détail d'un article.
     * @return void
     */
    public function showArticle() : void
    {
        // Récupération de l'id de l'article demandé.
        $id = Utils::request("id", -1);

        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);
        
        if (!$article) {
            throw new Exception("L'article demandé n'existe pas.");
        }

        $articleManager->recordView($id);

        //Pagination
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPDO();
        $commentCounter = new CommentCounter($pdo);
        $totalComments = $commentCounter->getTotalCommentsArticleId($id);

        // Numéro de la page et du nombre de commentaires par page
        $page = Utils::request("page", 1);
        $perPage = 3;

        $commentManager = new CommentManager();
        $comments = $commentManager->getAllCommentsByArticleId($id, $page, $perPage);

        // Calcul du nombre total de pages
        $totalPages = ceil($totalComments / $perPage);

        $view = new View($article->getTitle());
        $view->render("detailArticle", ['article' => $article, 'comments' => $comments, 'totalPages' => $totalPages]);
    }

    /**
     * Affiche le formulaire d'ajout d'un article.
     * @return void
     */
    public function addArticle() : void
    {
        $view = new View("Ajouter un article");
        $view->render("addArticle");
    }

    /**
     * Affiche la page "à propos".
     * @return void
     */
    public function showApropos() {
        $view = new View("A propos");
        $view->render("apropos");
    }

    
}