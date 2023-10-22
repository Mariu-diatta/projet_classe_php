<?php

/** // pour les commentaires concernant la procédure de connexion, lire ceux dans connexion.php**/
if(isset($_POST['connexion'])){
  $verifUser = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
  $verifUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
  $verifUser->execute();

  if($verifUser->rowCount() == 1){
    $user = $verifUser->fetch(PDO::FETCH_ASSOC);
    if(password_verify($_POST['mdp'], $user['mdp'])){
      foreach($user as $key => $value){
        if($key != 'mdp'){
          $_SESSION['membre'][$key] = $value;

          if(internauteConnecteAdmin()){
            header('location:admin/index.php?action=validate');
          }else{
            // pas de redirection vers panier.php, car panier.php envoit vers connexion.php, et non vers cette modale. Donc une seule redirection possible si on n'est pas admin, c'est sur l'index
            header('location:?action=validate_index');
          }
        }
      }
    }else{
      $erreur_index .= '<div class="alert alert-danger" role="alert">Erreur mot de passe inconnu !</div>';
    }
  }else{
    $erreur_index .= '<div class="alert alert-danger" role="alert">Erreur pseudo inconnu !</div>';
  }
}

// message de félicitations
if(isset($_GET['action']) && $_GET['action'] == "validate_index"){
  $validate_index .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    Félicitations <strong>' . $_SESSION['membre']['pseudo'] .'</strong>, vous etes connecté(e) 😉 !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
}

// rerquete pour afficher les onglets dans la barre de navigation
$afficheMenuPublics = $pdo->query("SELECT DISTINCT public FROM produit ORDER BY public ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- favicon -->
    <link rel="icon" type="image/png" href="logo.png" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

         <!-- links pour les icon bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

  <!-- si $pageTitle existe, j'affiche son contenu, sinon, par défaut, j'affiche La Boutique -->
    <title><?= (isset($pageTitle) ? $pageTitle : "La Boutique" ) ?></title>
</head>
<body>

<header>

<!-- ------------------- -->

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?= URL ?>"><img src="img/boutique_logo.webp"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item mt-2">
        <!-- redirection vers l'accueil si je clique sur le titre La Boutique -->
        <a class="nav-link" href="<?= URL ?>">La Boutique</a>
      </li>
      <!-- -------la boucle qui va générer les onglets du menu, avec un if pour rediriger en cas d'injection url d'une valeur erronnée ---- -->
      <?php while($menuPublic = $afficheMenuPublics->fetch(PDO::FETCH_ASSOC)): ?>
      <li class="nav-item">
        <a class="nav-link"
        <?php if(isset($_GET['public']) && $_GET['public'] != 'enfant' && $_GET['public'] != 'femme' && $_GET['public'] != 'homme' && $_GET['public'] != 'mixte' ){ ?>
        href="<?= header('location:'. URL); exit(); ?>"
        <?php }else{ ?>
        href="<?= URL ?>?public=<?= $menuPublic['public'] ?>"
        <?php } ?>
        ><button type="button" class="btn btn-outline-success <?= (isset($_GET['public']) && $_GET['public'] == $menuPublic['public']) ? "active" : "" ?>" ><?= ucfirst($menuPublic['public']) ?></button></a>
        <!-- ci dessus, si la valeur récupérée dans avec $menuPublic['public] est similaire a celle de $_GET['public'] alors la classe active ( css de bootstrap) dans le button fontionnera  -->
      </li>
      <?php endwhile; ?>
      <!-- ---------- -->
    </ul>
    <ul class="navbar-nav ml-auto">
      <!-- -------------------------- -->
    <?php if(internauteConnecte()): ?>
      <!-- les onglets visibles dans le cas ou le user est connecté
    Je récupère aussi son pseudo via la session pour personnaliser son menu -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-success">Espace <?= $_SESSION['membre']['pseudo'] ?><strong></strong></button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="profil.php">Profil <?= $_SESSION['membre']['pseudo'] ?></a>
          <a class="dropdown-item" href="panier.php">Panier <?= $_SESSION['membre']['pseudo'] ?></a>
          <a class="dropdown-item" href="connexion.php?action=deconnexion">Déconnexion</a>
        </div>
      </li>
    <?php else: ?>
      <!-- d'autres onglets visibles si le user n'est pas connecté
    il n'aura pas d'onglet pour sa page profil, mais la possibilité de s'inscrire, se connecter -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle mr-5" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-success">Espace Membre</button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="inscription.php">
            <button class="btn btn-outline-success" >Inscription</button>
          </a>
          <a class="dropdown-item">
            <button class="btn btn-outline-success" data-toggle="modal" data-target="#connexionModal">
             Connexion
            </button></a>
          <a class="dropdown-item" href="panier.php"><button class="btn btn-outline-success px-4">Panier</button></a>
        </div>
      </li>
    <?php endif; ?>
     <!-- onglet admin visible par seulement le user connecté qui sera en même temps admin du site -->
    <?php if(internauteConnecteAdmin()): ?>
      <li class="nav-item mr-5">
          <a class="nav-link" href="admin/index.php"><button type="button" class="btn btn-outline-success">Admin</button></a>
      </li>
    <?php endif; ?>
      <!-- ------------------------------------ -->
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>

</header>

<div >
  <!-- Modal -->
  <div class="modal fade" id="connexionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="exampleModalLabel"><img src="img/boutique_logo.webp"> La Boutique</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body text-center">
          <form name="connexion" method="POST" action="" >
              <div class="row justify-content-around">
                <div class="col-md-6 ">
                <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
                <input class="form-control btn btn-outline-success" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo">
                </div>
              </div>

              <div class="row justify-content-around">
                <div class="col-md-6">
                <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
                <input class="form-control btn btn-outline-success" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe">
                </div>
              </div>
              
              <div class="row justify-content-center">
                <button type="submit" name="connexion" class="btn btn-lg btn-outline-success mt-3">Connexion</button>
              </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>
  <!-- ------------- -->

<!-- ne s'affiche que sur l'index, pour ne pas avoir de doubles erreurs ou validations sur les pages inscription et connexion -->
<?= $erreur_index ?>
<?= $validate_index ?>

<!--h1 class="text-center mt-5"><div class="badge badge-dark text-wrap p-3">La Boutique</div></h1-->
<!--h2 class="text-center pb-5">Notre Catalogue. Nos Produits !</h2--> 