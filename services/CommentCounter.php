<?php

class CommentCounter {
  private $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getTotalCommentsArticleId(int $idArticle) : int 
  {
    $sql = "SELECT COUNT(*) AS total FROM comment WHERE id_article = :idArticle";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':idArticle', $idArticle, PDO::PARAM_INT);
    $stmt->execute();
    $totalComments = $stmt->fetchColumn();
    return $totalComments;
  }
}