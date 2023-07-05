<?php
require_once('../include/init.php');

if(!internauteConnecteAdmin()){
    header('location:' . URL . 'connexion.php');
}

// pagination
if(isset($_GET['page']) && !empty($_GET['page'])){
    $pageCourante = (int) strip_tags($_GET['page']);
}else{
    $pageCourante = 1;
}

$queryProduits= $pdo->query("SELECT COUNT(id_produit) AS nombreProduits FROM produit ");
$resultatProduits = $queryProduits->fetch();
$nombreProduits = (int) $resultatProduits['nombreProduits'];
// je pourrais récupérer le nombre de produits avec un row count plus simple et rapide, mais la syntaxe du dessus améliore les performances pour l'affichage

$parPage = 10;
$nombrePages = ceil($nombreProduits / $parPage);
$premierProduit = ($pageCourante - 1) * $parPage;
// pagination

if(isset($_GET['action'])){
    if($_POST){
        if(!isset($_POST['reference']) || !preg_match('#^[a-zA-Z0-9]{4,10}$#', $_POST['reference'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format référence !</div>';
        }
        if(!isset($_POST['categorie']) || iconv_strlen($_POST['categorie']) < 2 || iconv_strlen($_POST['categorie']) > 20){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format categorie !</div>';
        }
        if(!isset($_POST['titre']) || iconv_strlen($_POST['titre']) < 2 || iconv_strlen($_POST['titre']) > 20){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format titre !</div>';
        }
        if(!isset($_POST['description']) || iconv_strlen($_POST['description']) < 2 || iconv_strlen($_POST['description']) > 20){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description !</div>';
        }
        if(!isset($_POST['couleur']) || $_POST['couleur'] != "bleu" && $_POST['couleur'] != "rouge" && $_POST['couleur'] != "vert" && $_POST['couleur'] != "jaune" && $_POST['couleur'] != "blanc" && $_POST['couleur'] != "noir" && $_POST['couleur'] != "marron"){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format couleur !</div>';
        }
        if(!isset($_POST['taille']) || $_POST['taille'] != "small" && $_POST['taille'] != "medium" && $_POST['taille'] != "large" && $_POST['taille'] != "xlarge"){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format couleur !</div>';
        }
        if(!isset($_POST['public']) || $_POST['public'] != "enfant" && $_POST['public'] != "femme" && $_POST['public'] != "homme" && $_POST['public'] != "mixte"){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format couleur !</div>';
        }
        if(!isset($_POST['prix']) || !preg_match('#^[0-9]{1,10}$#', $_POST['prix'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prix !</div>';
        }
        if(!isset($_POST['stock']) || !preg_match('#^[0-9]{1,10}$#', $_POST['stock'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format stock !</div>';
        }

        // tout ce qui va concerner l'ajout ou l'update de la photo a partir du formulaire
        $photo_bdd = "";
        if($_GET['action'] == "update"){
            // dans le cas de l'update, je fais référence au nouveau name
            $photo_bdd = $_POST['photoActuelle'];
        }

        if(!empty($_FILES['photo']['name'])){
            // debug($_FILES);
            $photo_nom = $_POST['reference'] . "_" . $_FILES['photo']['name'];
            $photo_bdd = URL_UPLOAD . "$photo_nom";
            $photo_dossier = /*RACINE_SITE .*/ "img/$photo_nom";
            copy($_FILES['photo']['tmp_name'], $photo_dossier);
            // echo $photo_bdd . "<br>";
            // echo $photo_dossier . "<br>";
        }
        // photo

        if(empty($erreur)){
            if($_GET['action'] == "update"){
                $modifierProduit = $pdo->prepare("UPDATE produit SET id_produit = :id_produit, reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, public = :public, photo = :photo, prix = :prix, stock = :stock WHERE id_produit = :id_produit ");
                $modifierProduit->bindValue(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
                $modifierProduit->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
                $modifierProduit->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
                $modifierProduit->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $modifierProduit->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
                $modifierProduit->bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
                $modifierProduit->bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
                $modifierProduit->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
                $modifierProduit->bindValue(':photo', $photo_bdd, PDO::PARAM_STR);
                $modifierProduit->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
                $modifierProduit->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);
                $modifierProduit->execute();

                // query et fetch pour faire référence au produit modifié
                $queryProduits = $pdo->query("SELECT reference, categorie FROM produit WHERE id_produit = '$_GET[id_produit]' ");
                $produit = $queryProduits->fetch(PDO::FETCH_ASSOC);
                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                                <strong>Félicitations !</strong> Modification du produit <strong>'. $produit['reference'] . ' ' . $produit['categorie'] . '</strong> réussie !
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
            }else{
                $ajouterProduit = $pdo->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock) ");
                $ajouterProduit->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
                $ajouterProduit->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
                $ajouterProduit->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $ajouterProduit->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
                $ajouterProduit->bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
                $ajouterProduit->bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
                $ajouterProduit->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
                $ajouterProduit->bindValue(':photo', $photo_bdd, PDO::PARAM_STR);
                $ajouterProduit->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
                $ajouterProduit->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);
                $ajouterProduit->execute();

                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                                <strong>Félicitations !</strong> Ajout du produit réussie !
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
            }
        }
    }

    if($_GET['action'] == "update"){
        $queryProduits = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]' ");
        $produitActuel = $queryProduits->fetch(PDO::FETCH_ASSOC);
    }

    $id_produit = (isset($produitActuel['id_produit'])) ? $produitActuel['id_produit'] : "";
    $reference = (isset($produitActuel['reference'])) ? $produitActuel['reference'] : "";
    $categorie = (isset($produitActuel['categorie'])) ? $produitActuel['categorie'] : "";
    $titre = (isset($produitActuel['titre'])) ? $produitActuel['titre'] : "";
    $description = (isset($produitActuel['description'])) ? $produitActuel['description'] : "";
    $couleur = (isset($produitActuel['couleur'])) ? $produitActuel['couleur'] : "";
    $taille = (isset($produitActuel['taille'])) ? $produitActuel['taille'] : "";
    $public = (isset($produitActuel['public'])) ? $produitActuel['public'] : "";
    $photo = (isset($produitActuel['photo'])) ? $produitActuel['photo'] : "";
    $prix = (isset($produitActuel['prix'])) ? $produitActuel['prix'] : "";
    $stock = (isset($produitActuel['stock'])) ? $produitActuel['stock'] : "";

    if($_GET['action'] == "delete"){
        $pdo->query("DELETE FROM produit WHERE id_produit = '$_GET[id_produit]' ");
    }
}

require_once('includeAdmin/header.php');
?>

<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mot de passe !</div>'; -->

<!--  -->

<h1 class="text-center my-5"><div class="badge badge-warning text-wrap p-3">Gestion des produits</div></h1>

<div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
    <p>Gérez ici votre base de données des produits</p>
    <p>Vous pouvez modifier leurs données, ajouter ou supprimer un produit</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php if(isset($_GET['action'])): ?>
<h2 class="pt-5">Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?> des produits</h2>


<form id="monForm" class="my-5" method="POST" action=""  enctype="multipart/form-data">

<!-- toujours le input type id_produit pour pouvoir récupérer cette valeur dont on aura besoin, mais en hidden, pour ne pas la modifier. Dès que je fais référence à l'id auto-incrémenté d'une table, je dois l'appeler dans un input de type hidden -->
<input type="hidden" name="id_produit" value="<?= $id_produit ?>">

<div class="row mt-5">
    <div class="col-md-4">
    <label class="form-label" for="reference"><div class="badge badge-dark text-wrap">Référence</div></label>
    <input class="form-control" type="text" name="reference" id="reference" value="<?= $reference ?>" placeholder="Référence">
    </div>

    <div class="col-md-4">
    <label class="form-label" for="categorie"><div class="badge badge-dark text-wrap">Catégorie</div></label>
    <input class="form-control" type="text" name="categorie" id="categorie" value="<?= $categorie ?>" placeholder="Catégorie">
    </div>

    <div class="col-md-4">
    <label class="form-label" for="titre"><div class="badge badge-dark text-wrap">Titre</div></label>
    <input class="form-control" type="text" name="titre" id="titre" value="<?= $titre ?>" placeholder="Titre">
    </div>
</div>

<div class="row justify-content-around mt-5">
    <div class="col-md-6">
    <label class="form-label" for="description"><div class="badge badge-dark text-wrap">Description</div></label>
    <textarea class="form-control" name="description" id="description" placeholder="Description" rows="5"><?= $description ?></textarea>
    </div>
</div>

<div class="row mt-5">

    <div class="col-md-4 mt-3">
        <label class="badge badge-dark text-wrap" for="couleur">Couleur</label>
            <select class="form-control" name="couleur" id="couleur">
                <option value="">Choisissez</option>
                <option class="bg-primary text-light" value="bleu" <?= ($couleur == "bleu") ? "selected" : "" ?>>Bleu</option>
                <option class="bg-danger text-light" value="rouge" <?= ($couleur == "rouge") ? "selected" : "" ?>>Rouge</option>
                <option class="bg-success text-light" value="vert" <?= ($couleur == "vert") ? "selected" : "" ?>>Vert</option>
                <option class="bg-warning text-light" value="jaune" <?= ($couleur == "jaune") ? "selected" : "" ?>>Jaune</option>
                <option class="bg-light text-dark" value="blanc" <?= ($couleur == "blanc") ? "selected" : "" ?>>Blanc</option>
                <option class="bg-dark text-light" value="noir" <?= ($couleur == "noir") ? "selected" : "" ?>>Noir</option>
                <option class="text-light" style="background:brown;" value="marron" <?= ($couleur == "marron") ? "selected" : "" ?>>Marron</option>
            </select>
    </div>

    <div class="col-md-4">
        <p><div class="badge badge-dark text-wrap">Taille</div></p>

        <input type="radio" name="taille" id="taille1" value="small" <?= ($taille == "small") ? "checked" : "" ?>>
        <label class="mx-1" for="taille1">Small</label>

        <input type="radio" name="taille" id="taille2" value="medium" <?= ($taille == "medium") ? "checked" : "" ?>>
        <label class="mx-1" for="public2">Medium</label>

        <input type="radio" name="taille" id="taille3" value="large" <?= ($taille == "large") ? "checked" : "" ?>> 
        <label class="mx-1" for="taille3">Large</label>

        <input type="radio" name="taille" id="taille4" value="xlarge" <?= ($taille == "xlarge") ? "checked" : "" ?>> 
        <label class="mx-1" for="taille4">XLarge</label>
    </div>

    <div class="col-md-4">
        <p><div class="badge badge-dark text-wrap">Public</div></p>

        <input type="radio" name="public" id="public1" value="enfant" <?= ($public == "enfant") ? "checked" : "" ?>>
        <label class="mx-1" for="public1">Enfant</label>

        <input type="radio" name="public" id="public2" value="femme" <?= ($public == "femme") ? "checked" : "" ?>>
        <label class="mx-1" for="public2">Femme</label>

        <input type="radio" name="public" id="public3" value="homme" <?= ($public == "homme") ? "checked" : "" ?>>
        <label class="mx-1" for="public3">Homme</label>

        <input type="radio" name="public" id="public4" value="mixte" <?= ($public == "mixte") ? "checked" : "" ?>> 
        <label class="mx-1" for="public4">Mixte</label>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4">
    <label class="form-label" for="photo"><div class="badge badge-dark text-wrap">Photo</div></label>
    <input class="form-control" type="file" name="photo" id="photo" placeholder="Photo">
    </div>
    <!-- ----cas particulier pour l'update de la photo ou d'un upload----- -->
    <?php if(!empty($photo)): ?>
        <div class="mt-4">
            <p>Vous pouvez changer d'image
                <img src="<?= URL . 'img/' . $photo ?>" width="50">
            </p>
        </div>
    <?php endif ?>
    <input type="hidden" name="photoActuelle" value="<?= $photo ?>">
    <!-- -------------------- -->
    <div class="col-md-4">
    <label class="form-label" for="prix"><div class="badge badge-dark text-wrap">Prix</div></label>
    <input class="form-control" type="text" name="prix" id="prix" value="<?= $prix ?>" placeholder="Prix">
    </div>

    <div class="col-md-4">
    <label class="form-label" for="stock"><div class="badge badge-dark text-wrap">Stock</div></label>
    <input class="form-control" type="text" name="stock" id="stock" value="<?= $stock ?>" placeholder="Stock">
    </div>
</div>

<div class="col-md-1 mt-5">
<button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
</div>

</form>
<?php endif; ?>

<?php $queryProduits = $pdo->query("SELECT id_produit FROM produit") ?>
<h2 class="py-5">Nombre de produits en base de données: <?= $queryProduits->rowCount() ?></h2>

<div class="row justify-content-center py-5">
    <a href="?action=add">
        <button type="button" class="btn btn-sm btn-outline-dark btn-warning text-dark">
            <i class="bi bi-plus-circle-fill text-dark"></i> Ajouter un produit
        </button>
    </a>
</div>


<nav aria-label="">
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
<?php $afficheProduits = $pdo->query("SELECT * FROM produit ORDER BY prix ASC LIMIT $parPage OFFSET $premierProduit") ?>
    <thead>
        <tr>
            <?php for($i = 0; $i < $afficheProduits->columnCount(); $i++){
                $colonne = $afficheProduits->getColumnMeta($i) ?>
                <th><?= $colonne['name'] ?></th>
            <?php } ?>
                <th colspan="2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($produit = $afficheProduits->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <?php foreach($produit as $key => $value): ?>
                <?php if($key == "photo"): ?>
                    <td><img class="img-fluid" src="<?= URL . "img/" . $value ?>" width="50"></td>
                <?php elseif($key == "prix"): ?>
                    <td><?= $value ?> €</td>
                <?php else: ?>
                    <td><?= $value ?></td>
                <?php endif; ?>
            <?php endforeach; ?>
            <td><a href='?action=update&id_produit=<?= $produit['id_produit'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
            <td><a data-href="?action=delete&id_produit=<?= $produit['id_produit'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<nav aria-label="">
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

<!-- ici pas de modale info car j'ai gardé le blockquote info...pour donner deux solutions possibles -->

<?php require_once('includeAdmin/footer.php');