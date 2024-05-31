<?php

class AdminMonitoringController {

  public function showAdminMonitoring() : void 
  {

   $adminController = new AdminController();

   $adminController->monitoringPage();

   $sortCriteria = $_GET['sort'] ?? 'date_creation'; 
   $order = $_GET['order'] ?? 'asc'; 

   $articleManager = new ArticleManager();
   $articles = $articleManager->filterTable($sortCriteria);

   $view = new View("Articles avec détails");
   $view->render("adminMonitoring", ['articles' => $articles]);
  }
}