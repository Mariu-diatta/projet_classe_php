<?php
require_once('include/init.php');

// pour ajouter un produit au panier; transition entre fiche_produit et panier
if(array_key_exists('ajout_panier', $_POST)){
    $detailPanier = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]' ");
    $detail = $detailPanier->fetch(PDO::FETCH_ASSOC);
    // debug($detail);
    // pour alimenter la fonction ajouterAuPanier(), tout va provenir de ma table panier, hormis la quantité, qui arrive du formulaire dans fiche_produit
    ajouterAuPanier($detail['id_produit'], $detail['categorie'], $detail['titre'], $detail['photo'], $_POST['quantite'], $detail['prix']);
}

// --------------------------------------------
// tableau pour afficher les produits dans le panier, codé en php
// debug($_SESSION['panier']);

// $content .= "<table class='table my-5'>";
//     $content .= "<thead>";
//         $content .= "<tr>";
//             $content .= "<td colspan=3> Panier </td>";
//         $content .= "</tr>";
//         $content .= "<tr>";
//             $content .= "<th> Id Produit </th>";
//             $content .= "<th> Catégorie </th>";
//             $content .= "<th> Titre </th>";
//             $content .= "<th> Photo </th>";
//             $content .= "<th> Quantité </th>";
//             $content .= "<th> Prix (unité) </th>";
//         $content .= "</tr>";
//     $content .= "</thead>";
    
//     $content .= "<tbody>";
//     if(empty($_SESSION['panier']['id_produit'])){
//         $content .= "<tr>";
//             $content .= "<td class='badge badge-primary text-wrap p-3 mt-2'>Votre panier est vide</td>";
//         $content .= "</tr>";
//     }else{
//         for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++){
//         $content .= "<tr>";
//             $content .= "<td>" . $_SESSION['panier']['id_produit'][$i] ."</td>";
//             $content .= "<td>" . $_SESSION['panier']['categorie'][$i] ."</td>";
//             $content .= "<td>" . $_SESSION['panier']['titre'][$i] ."</td>";
//             $content .= "<td><img src=\"img/" . $_SESSION['panier']['photo'][$i] ."\"width='50px'></td>";
//             $content .= "<td>" . $_SESSION['panier']['quantite'][$i] ."</td>";
//             $content .= "<td>" . $_SESSION['panier']['prix'][$i] ." €</td>";
//         $content .= "</tr>";
//         }
//         $content .= "<tr>";
//             $content .= "<td class='badge badge-primary text-wrap p-3 mt-5' colspan=3>Montant total du panier : <strong><u>" . montantTotal() . " €</td>";
//         $content .= "</u></strong></tr>";
//     }
    
//     if(internauteConnecte()){
//         $content .= "<form method='POST' action=''>";
//         $content .= "<tr><td><button type='submit' class='btn btn-success my-2' name='payer'>Valider Panier</button></td></tr>";
//         $content .= "</form>";
//     }
//     else{
//         $content .= "<tr><td>Pour procéder au paiement vous devez vous <a href='incription.php'> inscrire </a> ou vous <a href='connexion.php'> connecter </a> !</td></tr>";
//     }
//     $content .= "</tbody>";

    
    
// $content .= "</table>";
// ------------------------------

// validation panier (verification stock, creation de la commande, creation du détail de la commande, soustraction de la quantité vendue du stock en bdd, suppression du panier)
if(array_key_exists('payer', $_POST)){
  // si le user clique sur payer, je procede a une vérification sur chaque produit dans son panier
  for($i = 0; $i < COUNT($_SESSION['panier']['id_produit']); $i++){
    // cette vérification est importante car entre le moment ou le user constitue son panier et le moment ou il procede au paiement, le stock peut avoir diminué. Il faut donc vérifier que sa quantité commandée est supérieur au stock.
    $queryProduits = $pdo->query("SELECT * FROM produit WHERE id_produit = ". $_SESSION['panier']['id_produit'][$i] ." ");
    $produit = $queryProduits->fetch(PDO::FETCH_ASSOC);
    if($produit['stock'] < $_SESSION['panier']['quantite'][$i]){
      // désormais, deux cas de figure. Encore du stock a proposer, ou alors le stock est a zéro
      if($produit['stock'] > 0){
        // je remplace la quantité dans son panier par mon stock
        $_SESSION['panier']['quantite'][$i] = $produit['stock'];
        // et j'en avertis l'acheteur
        $content .= '<div class="alert alert-danger" role="alert">La quantité du produit <strong>' .substr($produit['categorie'],0,-1). " " .$produit['titre']. '</strong> a été diminuée. Vérifiez votre nouveau panier !</div>';
      }else{
        // dans le cas ou mon stock est à zero
        // j'en avertis d'abord l'acheteur, AVANT de supprimer le produit du stock. Dans cet ordre. Car sinon j'aurais une erreur PHP, le produit n'existant désormais plus
        $content .= '<div class="alert alert-danger" role="alert">Le produit <strong>' .substr($produit['categorie'],0,-1). " " .$produit['titre']. '</strong> a été retiré de votre panier car il est désormais en rupture de stock. Navré !</div>';
        // puis donc suppression du produit grace à la bonne fonction utilisateur, avec l'id du produit en argument
        retirerDuPanier($_SESSION['panier']['id_produit'][$i]);
        // $i-- est très important ici pour compenser l'effet de "glisse " dans la fonction retirerDuPanier.
        // il faut décrémenter pour récupérer l'id_produit qui a glissé a la place du supprimé. Ou alors il faut faire une boucle qui parcourt le tableau de la fin vers le début.
        $i--;
      }
      // si donc mon stock était inférieur à la quantité commandée, je suis entré dans le circuit de l'erreur
      $erreur = TRUE;
    }
  }
  // si je ne suis pas entré dans le circuit de l'erreur, ou alors sorti (en corrigeant la quantité ou supprimant le produit), je crée une nouvelle commande en bdd
  if($erreur == FALSE){
    $ajouterCommande = $pdo->prepare("INSERT INTO commande (id_membre, montant, date_enregistrement) VALUES ( :id_membre, :montant, NOW() ) ");
    // pour l'id_membre, je récupère celui de la session membre
    $ajouterCommande->bindValue(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_INT);
    // pour le total de la commande, je le récupère grace a la fonction utilisateur montantTotal()
    $ajouterCommande->bindValue(':montant', montantTotal(), PDO::PARAM_INT);
    $ajouterCommande->execute();

    // je dois maintenant créer le détail de la commande
    // je commence par récupérer l'id de la commande qui vient d'etre créee en bdd. Et ce grace à la fonction prédéfinie/methode pdo, lastInsertId()
    $id_commande = $pdo->lastInsertId();

    // et comme je dois le faire pour chaque produit dans le panier, je boucle autour
    for($i = 0; $i < COUNT($_SESSION['panier']['id_produit']); $i++){
      // j'insere en bdd un detail_commande pour chaque produit, son prix, sa quantité. En récupérant les valeurs de la session avec query, je dois concaténer (pas avec le prepare du dessus pour la'jout de la commande)
      $ajouterDetailsCommande = $pdo->query("INSERT INTO details_commande (id_commande, id_produit, quantite, prix_unite) VALUES (".$id_commande.", ".$_SESSION['panier']['id_produit'][$i].", ".$_SESSION['panier']['quantite'][$i].", ".$_SESSION['panier']['prix'][$i].") ");

      // il me reste à soustraire la quantite vendue pour chaque produit, de son stock
      // A nouveau de la concaténation pour ce query update
      $modifierStock = $pdo->query("UPDATE produit SET stock = stock - ".$_SESSION['panier']['quantite'][$i]." WHERE id_produit = ".$_SESSION['panier']['id_produit'][$i]."  ");
    }
    // une fois la transaction validée, je vide le panier
    unset($_SESSION['panier']);
  }
}
// validation panier

// modifier quantite d'un produit (max 5 et min 1)
// si un formulaire nommé ajouter existe
if(isset($_POST['ajouter'])){
  // je boucle autour de tous les produits existant dans le panier
  for($i = 0; $i < COUNT($_SESSION['panier']['id_produit']); $i++){
    // pour le produit dans mon panier dont l'id correspond avec celui reçu dans mon formulaire
    if($_SESSION['panier']['id_produit'][$i] == $_POST['id_produit']){
      // Jusqu'a 5 articles max, j'ajoute, au dessus de 5 procédure impossible
      if($_SESSION['panier']['quantite'][$i] < 5){
        $_SESSION['panier']['quantite'][$i] += 1;
      }
    }
  }
}
// idem que au-dessus, sauf qu'a la quantite déjà existante du produit, je retire 1 (mais impossible d'avoir 0 article ou un nombre négatif)
if(isset($_POST['retirer'])){
  for($i = 0; $i < COUNT($_SESSION['panier']['id_produit']); $i++){
    if($_SESSION['panier']['id_produit'][$i] == $_POST['id_produit']){
      if($_SESSION['panier']['quantite'][$i] > 1){
        $_SESSION['panier']['quantite'][$i] -= 1;
      }
    }
  }
}
// modification quantité d'un produit

// retirer un produit du panier
// si je reçois une action égale à delete
if(array_key_exists('action', $_GET) && $_GET['action'] == 'delete'){
  // je fais intervenir la fonction utilisateur, avec pour parametre l'id_produit reçu en GET par la même occasion
  // je faiS référence au $_GET['id_produit'] même si je fais référence dans le <a href> de l'icone poubelle à $_SESSION['panier']['id_produit'][$i]. Ce dernier me permet sa réupération, mais une fois affiché dans l'url du navigateur, $_GET['id_produit'] devient suffisant, sans avoir a faire référence a [$i].
  retirerDuPanier($_GET['id_produit']);
}
// retirer un produit du panier

// si j'ai retiré tous les articles de mon panier, je décide, par choix esthétique d'affichage, de supprimer le panier aussi.// J'aurais pu garder une session avec un panier vide, mais c'est un choix.
if(array_key_exists('panier', $_SESSION)){
  if(COUNT($_SESSION['panier']['id_produit']) == 0){
    unset($_SESSION['panier']);
  }
}
// panier supprimé

// title de cette page selon que le user est connecté ou non
$pageTitle = (isset($_SESSION['membre'])) ? "Panier de " . $_SESSION['membre']['pseudo'] : "Votre panier";
require_once('include/header.php');
?>

<h2 class='text-center my-5'>
    <!-- -----Ternaire selon que la session membre existe ou pas, pour le pseudo----- -->
    <div class="badge badge-dark text-wrap p-3"><?= (isset($_SESSION['membre'])) ? "Panier de " . $_SESSION['membre']['pseudo'] : "Votre panier" ?></div>
    <!-- ------ -->

</h2>

<?= $content ?>

<!--Section: Block Content-->
<section>

  <!--Grid row-->
  <div class="row mt-5">
  <!-- -----s'il ya déjà un produit dans le panier (si la session existe) ----- -->
  <?php if(isset($_SESSION['panier'])): ?>
    <!--Grid column-->
    <div class="col-lg-8">

      <!-- Card -->
      <div class="mb-3 shadow p-3 mb-5 bg-white rounded">
        <div class="pt-4 wish-list">

          <h5 class="mb-4"><div class="badge badge-dark text-wrap p-3">Détail de votre Panier</div></h5>
          <!-- début de boucle for() qui va générer autant de cards qu'il existe de produits dans le panier -->
          <?php for($i = 0; $i < COUNT($_SESSION['panier']['id_produit']); $i++): ?>
          <div class="row mb-4">
            <div class="col-md-5 col-lg-3 col-xl-3">
              <div class="view zoom overlay z-depth-1 rounded mb-3 mb-md-0">
                <!-- récup de l'image -->
                <img class="img-fluid w-100"
                  src="<?= URL . "img/" . $_SESSION['panier']['photo'][$i] ?>" >
              </div>
            </div>
            <div class="col-md-7 col-lg-9 col-xl-9">
              <div>
                <div class="d-flex justify-content-between">
                  <div>
                    <!-- recup de la catégorie en supprimant le pluriel -->
                    <h5><?= substr($_SESSION['panier']['categorie'][$i],0,-1) ?></h5>
                    <!-- recup du titre -->
                    <p class="mb-3 text-muted text-uppercase small"><?= $_SESSION['panier']['titre'][$i] ?></p>
                    <!-- recupe de la quantite -->
                    <p class="mb-2 text-muted text-uppercase small">Quantité commandée: <?= $_SESSION['panier']['quantite'][$i] ?></p>
                    <form method="POST" action="">
                      <!-- form pour retirer ou ajouter une unité à la quantité déjà existante -->
                      <input type="hidden" name="id_produit" value="<?= $_SESSION['panier']['id_produit'][$i] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-success <?= ($_SESSION['panier']['quantite'][$i] == 1) ? "disabled" : "" ?>" name="retirer"><i class="bi bi-dash"></i></button>
                        <input class="btn btn-sm bg-success text-light col-md-2" min="1" name="quantite" value="1" type="text">
                        <button type="submit" class="btn btn-sm btn-outline-success <?= ($_SESSION['panier']['quantite'][$i] == 5) ? "disabled" : "" ?>" name="ajouter"><i class="bi bi-plus"></i></button>
                    </form>
                    <!-- recup du prix -->
                    <p class="mt-3 text-muted text-uppercase small">Prix unitaire:  <?= $_SESSION['panier']['prix'][$i] ?>€</p>
                  </div>
                  <div>
                    <!-- icone pour retirer un produit du panier, avec modale (en dessous) pour confirmer cette action -->
                    <p><a  data-href="?action=delete&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 2rem;"></i></a> Retirer article</p>
                  </div>
                  <!-- modal suppression codepen https://codepen.io/lowpez/pen/rvXbJq -->

                  <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  Supprimer article
                              </div>
                              <div class="modal-body">
                                  Etes-vous sur de vouloir retirer cet article de votre panier ?
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                                  <a class="btn btn-danger btn-ok">Supprimer</a>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- fin de modal -->

                </div>
                <div class="d-flex justify-content-end">
                  <!-- montant pour chaque article (calcul avec le prix multiplié à la quantité) -->
                <p class="mb-0">Montant pour cet article: <?= $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i] ?> €</p class="mb-0">
                </div>
              </div>
            </div>
          </div>
          <hr class="mb-4">
          <?php endfor; ?>
          <!-- fin de boucle -->
          

        </div>
      </div>
      <!-- Card -->

    </div>
    <!--Grid column-->

    <!--Grid column-->
    <div class="col-lg-4">

      <!-- Card de droite -->
      <div class="mb-3 shadow p-3 mb-5 bg-white rounded">
        <div class="pt-4">

        <!-- affiche le nb de produits (!= quantité) dans le panier -->
        <h5 class="mb-5 text-right"><div class="badge badge-dark text-wrap p-3"><?= COUNT($_SESSION['panier']['id_produit']) ?> article(s) dans votre Panier</div></h5>

          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-end border-0">
              <!-- montant total du panier grace à la focntion codée dans fonction.php -->
              <p class='badge badge-success text-wrap p-3'>
              Montant total du panier: <strong><u><?= montantTotal() ?> €</u></strong>
              </p>
            </li>
            <li class="list-group-item d-flex justify-content-end border-0">
              <!-- lien pour retourner vers l'accueil, si le user veut encore acheter -->
              <p class="ml-auto">Je veux encore <a href="<?= URL ?>">acheter</a></p>
            </li>
            <li class="list-group-item d-flex justify-content-end border-0">
              <!-- si le user n'est pas encore connecté. lien vers connexion.php avec en plus une action dans le href pour etre redirigé vers le panier, une fois connecté -->
              <?php if(!isset($_SESSION['membre'])): ?>
              <p class="ml-auto">Vous devez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php?action=acheter">connecter</a> pour procéder au paiement</p>
              <!-- si le user est déjà connecté -->
              <?php else: ?>
              <form method="POST" action="" class="ml-auto">
                  <button type='submit' class='btn btn-success' name='payer'>Valider Panier</button>
              </form>
              <?php endif; ?>
              <!-- --------- -->
            </li>
          </ul>
        </div>
      </div>
      <!-- Card -->

    </div>
    <!--Grid column-->
  <?php else: ?>
  <!-- affichage si le panier est vide -->
    <div class="col-lg-4 offset-md-4">

      <!-- Card -->
      <div class="mb-3 shadow p-3 mb-5 bg-white rounded">
        <div class="pt-4">

        <h5 class="mb-5 text-center"><div class="badge badge-dark text-wrap p-3">Votre panier est actuellement vide.</div></h5>

          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex border-0">
              <p>Je veux <a href="<?= URL ?>">acheter</a></p>
            </li>
            <li class="list-group-item d-flex justify-content-end border-0">
              <!-- -------- -->
              <?php if(!isset($_SESSION['membre'])): ?>
              <p class="ml-auto">Vous devez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php?action=acheter">connecter</a> pour procéder au paiement</p>
              <?php endif; ?>
              <!-- --------- -->
            </li>
          </ul>
        </div>
      </div>
      <!-- Card -->

    </div>
    <?php endif; ?>
  <!-- --------- -->
  </div>
  <!-- Grid row -->

</section>
<!--Section: Block Content-->

<!-- fin de panier -->

<?php require_once('include/footer.php');