<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>La Boutique Admin</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

  <!-- links pour les icon bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

  <!-- {# links pour databaseTables #} -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>

</head>

<body>

  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-dark border-right" id="sidebar-wrapper">
      <div class="sidebar-heading text-warning">La Boutique Admin </div>
      <div class="list-group list-group-flush">
        <a href="<?= URL ?>admin/gestion_membre.php" class="list-group-item list-group-item-action bg-dark text-light py-5"><button type="button" class="btn btn-outline-warning text-light">&nbspGestion &nbspdes&nbsp membres&nbsp</button></a>
        <a href="<?= URL ?>admin/gestion_produit.php" class="list-group-item list-group-item-action bg-dark text-light py-5"><button type="button" class="btn btn-outline-warning text-light">&nbspGestion &nbsp&nbspdes&nbsp&nbsp produits&nbsp</button></a>
        <a href="<?= URL ?>admin/gestion_commande.php" class="list-group-item list-group-item-action bg-dark text-light py-5"><button type="button" class="btn btn-outline-warning text-light">Gestion des commandes</button></a>
        <a href="<?= URL ?>admin/gestion_detail_commande.php" class="list-group-item list-group-item-action bg-dark text-light py-4"><button type="button" class="btn btn-outline-warning text-light">&nbspDétail des commandes&nbsp</button></a>
        <a href="<?= URL ?>index.php" class="list-group-item list-group-item-action bg-dark text-light py-5"><button type="button" class="btn btn-outline-warning text-light">Retour Accueil Boutique</button></a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
  <button class="btn btn-lg btn-outline-warning" id="menu-toggle"><i class="bi bi-caret-left-square-fill"></i> Menu <i class="bi bi-caret-right-square-fill"></i></button>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
      <li class="nav-item">
        <a class="nav-link" href="<?= URL ?>index.php"><button type="button" class="btn btn-outline-warning text-light">Home Boutique</button></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= URL ?>admin/index.php"><button type="button" class="btn btn-outline-warning text-light">Home Admin</button></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-warning text-light">Menu Admin</button>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_membre.php">Gestion des membres</a>
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_produit.php">Gestion des produits</a>
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_commande.php">Gestion des commandes</a>
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_detail_commande.php">Détail des commandes</a>
        </div>
      </li>
    </ul>
  </div>
</nav>

<div class="container mb-5">