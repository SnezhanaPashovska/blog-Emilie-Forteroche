<?php

class AdminMonitoringController {

  //Méthode pour afficher les articles avec des details supplementaires
  public function showAdminMonitoring() : void 
  {

   // Instanciation de la classe AdminController pour accéder à la méthode monitoringPage()
   $adminController = new AdminController();

   // Appel de la méthode monitoringPage() pour vérifier les autorisations de l'utilisateur
   $adminController->monitoringPage();

   // Define sort criteria and order
   $sortCriteria = $_GET['sort'] ?? 'date_creation'; // Default sort criteria
   $order = $_GET['order'] ?? 'asc'; // Default order

   // Récupération des données des articles avec des détails supplémentaires
   $articleManager = new ArticleManager();
   $articles = $articleManager->filterTable($sortCriteria);

   // Rendu de la vue pour afficher les articles avec des détails
   $view = new View("Articles avec détails");
   $view->render("adminMonitoring", ['articles' => $articles]);
  }
  
}