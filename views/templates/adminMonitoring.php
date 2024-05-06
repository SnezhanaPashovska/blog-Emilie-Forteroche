<?php

// Check if user is logged in as admin, redirect if not
/*    if(!isset($_SESSION['loggedin'])) {
        header('Location : index.php');
        exit;
    }
    if($_SESSION['role'] !== 'admin') {
        header('Location : index.php');
        exit;
    } */

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Articles avec détails</title>
</head>
<body>
  <h1>Liste de articles avec détails</h1>
  <table>
    <thead>
      <tr>
        <th><a href="?sort=title">Titre</a></th>
        <th><a href="?sort=view_count">Nombre de vues</a></th>
        <th><a href="?sort=comment_count">Nombre de commentaires</a></th>
        <th><a href="?sort=date_creation">Date de publication</a></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($articles as $article): ?>
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