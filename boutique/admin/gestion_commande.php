<?php
require_once('../include/init.php');

// redirection si le user n'est pas admin
if(!internauteConnecteAdmin()){
    header('location:' . URL . 'connexion.php');
    exit();
}

// pagination (commentaires dans affichage.php)
if(isset($_GET['page']) && !empty($_GET['page'])){
    $pageCourante = (int) strip_tags($_GET['page']);
}else{
    $pageCourante = 1;
}

$queryCommandes = $pdo->query("SELECT COUNT(id_commande) AS nombreCommandes FROM commande ");
$resultatCommandes = $queryCommandes->fetch(PDO::FETCH_ASSOC);
$nombreCommandes = (int) $resultatCommandes['nombreCommandes'];

$parPage = 10;
$nombrePages = ceil($nombreCommandes / $parPage);
$premiereCommande = ($pageCourante - 1) * $parPage;
// pagination

// traitement php qui vérifie si j'ai reçu une action dans l'URL
if(isset($_GET['action'])){
    // si j'ai reçu des données dans le formulaire
    // ce formulaire sera surtout utilisé pour modifier l'état de la commande (passer de en cours à livré etc...). Sinon, peu de raisons de passer une commande ou modifier autre chose que l'état de la commande
    if($_POST){
        // vérification des champs
        if(!isset($_POST['montant']) || !preg_match('#^[0-9]{1,10}$#', $_POST['montant'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format montant !</div>';
        }
        if(!isset($_POST['etat']) || $_POST['etat'] != "en cours" && $_POST['etat'] != "envoyé" && $_POST['etat'] != "livré"){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format état livraison !</div>';
        }
        if(empty($erreur)){
            // si aucun input n'a declenché d'erreur, requete préparée de modification 
            $modifierCommande = $pdo->prepare("UPDATE commande SET id_commande = :id_commande, montant = :montant, etat = :etat WHERE id_commande = :id_commande");
            // les bindValue
            $modifierCommande->bindValue(':id_commande', $_POST['id_commande'], PDO::PARAM_INT);
            $modifierCommande->bindValue(':montant', $_POST['montant'], PDO::PARAM_INT);
            $modifierCommande->bindValue(':etat', $_POST['etat'], PDO::PARAM_STR);
            // son execution
            $modifierCommande->execute();

            // message de confirmation
            $queryCommandes = $pdo->query("SELECT id_commande FROM commande WHERE id_commande = '$_GET[id_commande]' ");
            $commande = $queryCommandes->fetch(PDO::FETCH_ASSOC);
            $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                            <strong>Félicitations !</strong> Modification de la commande <strong>' . $commande['id_commande'] . '</strong> réussie !
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        }
    }

    // si c'est une action update, je récupère dans mes champs les valeurs en BDD (plus facile pour faire la modif)
    if($_GET['action'] == "update"){
        // select puis fetch
        $queryCommandes = $pdo->query("SELECT id_commande, montant, etat FROM commande WHERE id_commande = '$_GET[id_commande]' ");
        $commandeActuelle = $queryCommandes->fetch(PDO::FETCH_ASSOC);
    }

    // si je récupère une valeur en bdd, je l'affecte à ma valeur, sinon, elle reste vide
    $id_commande = (isset($commandeActuelle['id_commande'])) ? $commandeActuelle['id_commande'] : "" ;
    $montant = (isset($commandeActuelle['montant'])) ? $commandeActuelle['montant'] : "" ;
    $etat = (isset($commandeActuelle['etat'])) ? $commandeActuelle['etat'] : "" ;

    // si action == supprimer
    if($_GET['action'] == "delete"){
        // requete de suppression de la commande
        $pdo->query("DELETE FROM commande WHERE id_commande = '$_GET[id_commande]' ");
    }
}

require_once('includeAdmin/header.php');
?>

<h1 class="text-center my-5"><div class="badge badge-warning text-wrap p-3">Gestion des commandes</div></h1>

<!-- j'affiche l'accueil de cette page avec une modale. Ci dessous, commenté, le même affichage mais dans un blockquote -->

<!-- <div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
    <p>Gérez ici votre base de données des commandes</p>
    <p>Vous ne pourrez modifier que son montant (pour une réduction, par exemple) ou son état (selon son avancement)</p>
    <p>Vous ne pourrez ajouter une commande. Par contre la suppression sera possible, supprimant par la même occasion les détails de cette commande</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div> -->

<?php if(isset($_GET['action']) && $_GET['action'] == "update"): ?>
<h2 class="my-2">Formulaire de modification des commandes</h2>

<form method="POST"action="">

    <input type="hidden" name="id_commande" value="<?= $id_commande ?>">

    <!-- <div class="col-md-3 mt-5">
    <label class="form-label" for="id_membre"><div class="badge badge-dark text-wrap">Id Membre</div></label>
    <input type="text" class="form-control" name="id_membre" id="id_membre"  placeholder="Id du membre">
    </div> -->

    <div class="col-md-3 mt-5">
    <label class="form-label" for="montant"><div class="badge badge-dark text-wrap">Montant</div></label>
    <input type="text" class="form-control" name="montant" id="montant" value="<?= $montant ?>" placeholder="Montant">
    </div>

    <div class="col-md-4 mt-5">
        <p><div class="badge badge-dark text-wrap">Etat de la livraison</div></p>

        <input type="radio" name="etat" id="etat1" value="en cours" <?= ($etat == "en cours") ? "checked" : "" ?>>
        <label class="mx-2" for="etat1"><div class="badge badge-danger text-wrap">En cours</div></label>

        <input type="radio" name="etat" id="etat2" value="envoyé" <?= ($etat == "envoyé") ? "checked" : "" ?>>
        <label class="mx-2" for="etat2"><div class="badge badge-warning text-wrap">Envoyé</div></label>

        <input type="radio" name="etat" id="etat3" value="livré" <?= ($etat == "livré") ? "checked" : "" ?>> 
        <label class="mx-2" for="etat3"><div class="badge badge-success text-wrap">Livré</div></label>
    </div>

    <div class="col-md-1 mt-5">
    <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
    </div>

</form>
<?php endif; ?>

<?php $queryCommandes = $pdo->query("SELECT id_commande FROM commande") ?>
<h2 class="py-5">Nombre de commandes en base de données: <?= $queryCommandes->rowCount() ?></h2>

<!-- <div class="row justify-content-center py-5">
    <a href="?action=add">
        <button type="button" class="btn btn-sm btn-outline-dark btn-warning text-dark">
            <i class="bi bi-plus-circle-fill text-dark"></i> Ajouter une commande
        </button>
    </a>
</div> -->

<nav>
  <ul class="pagination justify-content-end">
    <li class="page-item <?= ($pageCourante == 1) ? "disabled" : "" ?>">
        <a class="page-link bg-dark text-warning" href="?page=<?= $pageCourante - 1 ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
        </a>
    </li>
    <?php for($page = 1; $page <= $nombrePages; $page++): ?>
        <li class="page-item <?= ($pageCourante == $page) ? "active" : "" ?>">
            <a class="page-link bg-dark text-warning" href="?page=<?= $page ?>"><?= $page ?></a>
        </li>
    <?php endfor; ?>
    <li class="page-item <?= ($pageCourante == $nombrePages) ? "disabled" : "" ?>">
        <a class="page-link bg-dark text-warning" href="?page=<?= $pageCourante + 1 ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
        </a>
    </li>
  </ul>
</nav>

<table class="table table-dark text-center">
<?php $afficheCommandes = $pdo->query("SELECT id_commande, id_membre, montant, DATE_FORMAT(date_enregistrement, '%d/%m/%Y à %Hh %imn %ss') , etat FROM commande ORDER BY id_commande DESC LIMIT $parPage OFFSET $premiereCommande ") ?>
    <thead>
        <tr>
            <?php for($i = 0; $i < $afficheCommandes->columnCount(); $i++){
                $colonne = $afficheCommandes->getColumnMeta($i); ?>
                <?php if($colonne['name'] == "DATE_FORMAT(date_enregistrement, '%d/%m/%Y à %Hh %imn %ss')"): ?>
                <th>Date/heure enregistrement</th>
                <?php else: ?>
                <th><?= $colonne['name'] ?></th>
                <?php endif; ?>
            <?php } ?>
                <th colspan="2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($commande = $afficheCommandes->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <?php foreach($commande as $key => $value): ?>
                <?php if($key == 'montant'): ?>
                <td><?= $value ?> €</td>
                <?php elseif($value == 'en cours'): ?>
                <td class="text-danger"><?= $value ?></td>
                <?php elseif($value == 'envoyé'): ?>
                <td class="text-warning"><?= $value ?></td>
                <?php elseif($value == 'livré'): ?>
                <td class="text-success"><?= $value ?></td>
                <?php else: ?>
                <td><?= $value ?></td>
                <?php endif; ?>
            <?php endforeach; ?>
                <td><a href='?action=update&id_commande=<?= $commande['id_commande'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
                <td><a data-href="?action=delete&id_commande=<?= $commande['id_commande'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<nav>
  <ul class="pagination justify-content-end">
    <li class="page-item <?= ($pageCourante == 1) ? "disabled" : "" ?>">
        <a class="page-link bg-dark text-warning" href="?page=<?= $pageCourante - 1 ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
        </a>
    </li>
    <?php for($page = 1; $page <= $nombrePages; $page++): ?>
        <li class="page-item <?= ($pageCourante == $page) ? "active" : "" ?>">
            <a class="page-link bg-dark text-warning" href="?page=<?= $page ?>"><?= $page ?></a>
        </li>
    <?php endfor; ?>
    <li class="page-item <?= ($pageCourante == $nombrePages) ? "disabled" : "" ?>">
        <a class="page-link bg-dark text-warning" href="?page=<?= $pageCourante + 1 ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
        </a>
    </li>
  </ul>
</nav>

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
<!-- modal -->

<!-- modal infos -->
<?php if(!isset($_GET['action'])): ?>
<div class="modal fade" id="myModalCommand" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-warning" id="exampleModalLabel">Gestion des commandes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Vous ne pourrez modifier que son montant (pour une réduction, par exemple) ou son état (selon son avancement)</p>
        <p>Vous ne pourrez ajouter une commande. Par contre la suppression sera possible, supprimant par la même occasion les détails de cette commande</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-warning text-dark" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<!-- modal -->

<?php require_once('includeAdmin/footer.php');