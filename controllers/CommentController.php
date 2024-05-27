<?php

class CommentController 
{
    /**
     * Ajoute un commentaire.
     * @return void
     */
    public function addComment() : void
    {
        // Récupération des données du formulaire.
        $pseudo = Utils::request("pseudo");
        $content = Utils::request("content");
        $idArticle = Utils::request("idArticle");

        // On vérifie que les données sont valides.
        if (empty($pseudo) || empty($content) || empty($idArticle)) {
            throw new Exception("Tous les champs sont obligatoires. 3");
        }

        // On vérifie que l'article existe.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($idArticle);
        if (!$article) {
            throw new Exception("L'article demandé n'existe pas.");
        }

        // On crée l'objet Comment.
        $comment = new Comment([
            'pseudo' => $pseudo,
            'content' => $content,
            'idArticle' => $idArticle
        ]);

        // On ajoute le commentaire.
        $commentManager = new CommentManager();
        $result = $commentManager->addComment($comment);

        // On vérifie que l'ajout a bien fonctionné.
        if (!$result) {
            throw new Exception("Une erreur est survenue lors de l'ajout du commentaire.");
        }

        // On redirige vers la page de l'article.
        Utils::redirect("showArticle", ['id' => $idArticle]);
    }


    public function deleteComment() : void 
    {
        // Récupération de l'ID du commentaire à supprimer depuis la requête GET.
        $commentId = Utils::request("id");
        // On vérifie si l'ID du commentaire a bien été récupéré (c'est-à-dire qu'il n'est pas null)
        if ($commentId !== null) {
        // Instanciation du CommentManager
            $commentManager = new CommentManager();
        // Récupération du commentaire
            $comment = $commentManager->getCommentById($commentId);
        // On vérifie si le commentaire existe
            if ($comment !== null) {
        // On appelle la méthode deleteComment de CommentManager pour supprimer le commentaire.
                $result = $commentManager->deleteComment($comment);
                if ($result) {
                    Utils::redirect("showArticle", ["id" => $comment->getIdArticle()]);
                } else {
                    throw new Exception("Le commentaire demandé n'existe pas.");
                }
            } else {
                throw new Exception("L'identifiant du commentaire est invalide.");
            }
        }
    }

    
}