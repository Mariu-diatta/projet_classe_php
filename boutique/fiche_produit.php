<?php
require_once('include/init.php');

// je récupère différentes requetes et autres traitements du fichier affichage.php
require_once('include/affichage.php');
// pour le title je récupère ma catégorie en bdd (en lui enlevant son pluriel avec substr()) ainsi que sa valeur dans $detail['titre']
$pageTitle = "Fiche du produit " . substr($detail['categorie'],0,-1) . " " . $detail['titre'];
require_once('include/header.php');
?>

</div>

<div class="container-fluid">
    <div class="row">
        <!-- debut de la colonne qui va afficher les categories -->
        <div class="col-md-2">

            <div class="list-group text-center">
                <!-- je généère toutes les catégories existantes en bdd sous forme d'onglets avec la while -->
                <?php while( $menuCategories = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)): ?>
                <a class="btn btn-outline-success my-2" href="<?= URL ?>?categorie=<?= $menuCategories['categorie'] ?>"><?= $menuCategories['categorie'] ?></a>
                <?php endwhile; ?>
            </div>

        </div>
        <!-- fin de la colonne catégories -->
        <div class="col-md-8">

        <!-- pareil que pour le title, je retire le pluriel de mes categories -->
            <h2 class='text-center my-5'>
                <div class="badge badge-dark text-wrap p-3">Fiche du produit <?= substr($detail['categorie'],0,-1) . " " . $detail['titre'] ?></div>
            </h2>

            <div class="row justify-content-around text-center py-5">
                <div class="card shadow p-3 mb-5 bg-white rounded" style="width: 22rem;">
                <!-- je récupère le chemin de la photo pour l'afficher -->
                    <img src="<?= URL . "img/" . $detail['photo'] ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <!-- recupère le rpix -->
                        <h3 class="card-title"><div class="badge badge-dark text-wrap"><?= $detail['prix'] ?> €</div></h3>
                        <!-- récupère la description -->
                        <p class="card-text"><?= $detail['description'] ?></p>
                        <!-- J'affiche un formulaire pour commander la quantité, si il me reste du stock en bdd -->
                        <?php if($detail['stock'] > 0): ?>
                        <!-- formulaire qui envoi les données vers la page panier.php -->
                        <form method="POST" action="panier.php">
                            <!-- input pour l'id_produit, en hidden, j'en aurai besoin pour le panier -->
                            <input type="hidden" name="id_produit" value="<?= $detail['id_produit'] ?>">
                            <label for="">J'en achète</label>
                            <select class="form-control col-md-5 mx-auto" name="quantite" id="quantite">
                                <!-- boucle for pour générer la quantité en stock, mais que je limite à 5. Je ne suis pas grossiste. Mon client ne peut en commander que 5 max -->
                                <?php for($quantite = 1; $quantite <= min($detail['stock'],5); $quantite++): ?>
                                <option class="bg-dark text-light" value="<?= $quantite ?>"><?= $quantite ?></option>
                                <?php endfor; ?>
                                <!-- ----------- -->
                            </select>
                            <!-- bouton pour envoyer le produit et sa quantité vers le panier -->
                            <button type="submit" class="btn btn-outline-success my-2" name="ajout_panier" value="ajout_panier"><i class="bi bi-plus-circle"></i> Panier <i class="bi bi-cart3"></i></button>
                        </form>
                        <?php else: ?>
                        <!-- si je n'ai plus de stock en bdd, le formulaire ne s'affiche pas, mais ce message à sa place -->
                            <p class="card-text"><div class="badge badge-danger text-wrap p-3">Produit en rupture de stock</div></p>
                        <?php endif; ?>
                        <!-- ------------ -->
                        
                        <!-- lien de redirection vers l'accueil, affichant les modèles de la même catégorie -->
                        <p><a href="<?= URL ?>?categorie=<?= $detail['categorie'] ?>">Voir tous les modèles</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">

<?php require_once('include/footer.php');