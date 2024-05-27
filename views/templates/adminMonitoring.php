<?php

// Créer une instance de AdminMonitoringControlle
$controller = new AdminMonitoringController();

// Initialiser les valeurs par défaut pour sortCriteria et order
$sortCriteria = $_GET['sort'] ?? 'date_creation'; // Critère de tri par défaut
$order = $_GET['order'] ?? 'asc'; // Ordre de tri par défaut

// Filtrer et trier les articles en fonction du critère de tri
$articleManager = new ArticleManager();
$sortedArticles = $articleManager->filterTable($sortCriteria);

// Fetch the articles data after sorting
//$articles = $controller->fetchArticles();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1fff66d8ed.js" crossorigin="anonymous"></script>
  <title>Articles avec détails</title>
</head>
<body>
  <h1>Liste de articles avec détails</h1>
  <table>
  <thead>
    <tr>
        <th><a href="<?= SortUrl::generateSortUrl('title', ($sortCriteria === 'title') ? ($order === 'asc' ? 'desc' : 'asc') : 'asc'); ?>">Titre <?= ($sortCriteria === 'title') ? ($order === 'asc' ? '<i class="fas fa-arrow-down-wide-short"></i>' : '<i class="fas fa-arrow-up-wide-short"></i>') : '<i class="fas fa-arrow-down-wide-short"></i>'; ?></a></th>
        <th><a href="<?= SortUrl::generateSortUrl('view_count', ($sortCriteria === 'view_count') ? ($order === 'asc' ? 'desc' : 'asc') : 'asc'); ?>">Nombre de vues <?= ($sortCriteria === 'view_count') ? ($order === 'asc' ? '<i class="fas fa-arrow-down-wide-short"></i>' : '<i class="fas fa-arrow-up-wide-short"></i>') : '<i class="fas fa-arrow-down-wide-short"></i>'; ?></a></th>
        <th><a href="<?= SortUrl::generateSortUrl('comment_count', ($sortCriteria === 'comment_count') ? ($order === 'asc' ? 'desc' : 'asc') : 'asc'); ?>">Nombre de commentaires <?= ($sortCriteria === 'comment_count') ? ($order === 'asc' ? '<i class="fas fa-arrow-down-wide-short"></i>' : '<i class="fas fa-arrow-up-wide-short"></i>') : '<i class="fas fa-arrow-down-wide-short"></i>'; ?></a></th>
        <th><a href="<?= SortUrl::generateSortUrl('date_creation', ($sortCriteria === 'date_creation') ? ($order === 'asc' ? 'desc' : 'asc') : 'asc'); ?>">Date de publication <?= ($sortCriteria === 'date_creation') ? ($order === 'asc' ? '<i class="fas fa-arrow-down-wide-short"></i>' : '<i class="fas fa-arrow-up-wide-short"></i>') : '<i class="fas fa-arrow-down-wide-short"></i>'; ?></a></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($sortedArticles as $article): ?>
      <tr>
        <td><?= $article['title']; ?></td>
        <td><?= $article['view_count']; ?></td>
        <td><?= $article['comment_count']; ?></td>
        <td><?= date('d/m/Y', strtotime($article['date_creation'])); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</body>
</html>