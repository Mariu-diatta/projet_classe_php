<?php
require_once('../include/init.php');

if(!internauteConnecteAdmin()){
    header('location:' . URL . 'connexion.php');
    exit();
}

if(isset($_GET['page']) && !empty($_GET['page'])){
    $pageCourante = (int) strip_tags($_GET['page']);
}else{
    $pageCourante = 1;
}

$queryDetails = $pdo->query("SELECT COUNT(id_details_commande) AS nombreDetails FROM details_commande ");
$resultatDetails = $queryDetails->fetch(PDO::FETCH_ASSOC);
$nombreDetails = (int) $resultatDetails['nombreDetails'];

$parPage = 10;
$nombrePages = ceil($nombreDetails / $parPage);
$premierDetail = ($pageCourante - 1) * $parPage;

require_once('includeAdmin/header.php');
?>

<h1 class="text-center my-5"><div class="badge badge-warning text-wrap p-3">Gestion détail des commandes</div></h1>

<?php $queryDetails = $pdo->query("SELECT id_details_commande FROM details_commande") ?>

<h2 class="py-5">Nombre de détails de commandes en base de données: <?= $queryDetails->rowCount() ?></h2>

<nav>
  <ul class="pagination justify-content-end">
    <li class="page-item <?= ($pageCourante == 1) ? "disabled" : "" ?>">
        <a class="page-link bg-dark text-warning" href="?page=<?= $pageCourante - 1 ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
        </a>
    </li>
    <?php for($page = 1; $page <= $nombrePages; $page++): ?>
        <li class="page-item ">
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
<?php $afficheDetails = $pdo->query("SELECT id_details_commande, id_commande, dc.id_produit, categorie, titre, quantite, prix_unite FROM details_commande AS dc INNER JOIN produit AS p WHERE dc.id_produit = p.id_produit ORDER BY id_details_commande DESC LIMIT $parPage OFFSET $premierDetail") ?>
    <thead>
        <tr>
            <?php for($i = 0; $i < $afficheDetails->columnCount(); $i++){
                $colonne = $afficheDetails->getColumnMeta($i); ?>
                <th><?= $colonne['name'] ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php while($detail = $afficheDetails->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <?php foreach($detail as $key => $value): ?>
                <?php if($key == "prix_unite"): ?>
                    <td><?= $value ?> €</td>
                <?php else: ?>
                    <td><?= $value ?></td>
                <?php endif; ?>
            <?php endforeach; ?>
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
        <li class="page-item ">
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

<!-- modal infos -->
<?php if(!isset($_GET['page'])): ?>
<div class="modal fade" id="myModalDetailsCommand" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-warning" id="exampleModalLabel">Gestion des détails des commandes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Visualisez ici votre base de données des détails de commande</p>
        <p>Aucune action n'est possible, ses données étant reliées a d'autres, cela entrainerait des dysfonctionnements</p>
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