<?php 
    /** 
     * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun. 
     * Et un formulaire pour ajouter un article. 
     */
?>

<h2>Edition des articles</h2>

<div class="adminArticle">
    <?php 
     $count = 0; // Initialise un compteur pour suivre les lignes paires et impaires
    foreach ($articles as $article) { // Boucle à travers chaque article dans la liste d'articles
        $count++; // Incrémente le compteur à chaque itération
        ?>
        <div class="articleLine <?= $count % 2 === 0 ? 'even' : 'odd' ?>"> <!-- Classe CSS pour les lignes paires et impaires -->
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="content"><?= $article->getContent(200) ?></div>
            <div><a class="submit" href="index.php?action=showUpdateArticleForm&id=<?= $article->getId() ?>">Modifier</a></div>
            <div><a class="submit" href="index.php?action=deleteArticle&id=<?= $article->getId() ?>" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?") ?> >Supprimer</a></div>
        </div>
    <?php } ?>
</div>

<a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>