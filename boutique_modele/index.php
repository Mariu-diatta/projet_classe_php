<?php
require_once('include/init.php');

// je récupère toutes les requetes et autres traitements php codés dans affichage.php
require_once('include/affichage.php');
require_once('include/header.php');
?>

</div>
    <div class="container-fluid">
    
        <div class="row my-5">

            <div class="col-md-2">

            <div class="list-group text-center">
                <!-- boucle while qui va récupérer toutes les catégories en bdd pour les afficher dans cette side-bar -->
                <!-- si la catégorie est égale à la catégorie qui transite dans l'URL, l'onglet sera actif -->
                    <?php while($menuCategories = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)): ?>
                    <a class="btn btn-outline-success my-2 <?= (isset($_GET['categorie']) && $_GET['categorie'] == $menuCategories['categorie']) ? "active" : "" ?>" href="<?= URL ?>?categorie=<?= $menuCategories['categorie'] ?>"><?= $menuCategories['categorie'] ?></a>
                    <?php endwhile; ?>
                </div>
            
            </div>

           <!-- --------------------------- -->
           <?php if(isset($_GET['categorie'])): ?>
            <!-- affichage différent selon que l'on clique sur un onglet catégorie
            l'image d'accueil sera plus petite
            un titre reprenant la catégorie s'affichera
            plus une selection d'articles -->
            <div class="col-md-8">
            
                <div class="text-center my-5">
                    <img class='img-fluid' src="img/la_boutique_bis.webp" alt="Bandeau de La Boutique" loading="lazy">
                </div>

                <div class="row justify-content-around">
                    <h2 class="py-5"><div class="badge badge-dark text-wrap">Nos <?= $titreCategorie['categorie'] ?></div></h2>
                </div>

                <div class="row justify-content-around text-center">

                        
                    <?php while($produit = $afficheProduits->fetch(PDO::FETCH_ASSOC)): ?>
                        <!-- boucle while avec fetch pour récupérer les produits selectionnés dans $afficheProduits, scriptée dans affichage.php -->
                        <div class="card mx-3 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
                            <!-- récupération du chemin de l'image -->
                            <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>"><img src="<?= URL . 'img/' . $produit['photo'] ?>" class="card-img-top" alt="..."></a>
                            <div class="card-body">
                                <!-- du titre du produit -->
                                <h3 class="card-title"><?= $produit['titre'] ?></h3>
                                <!-- son prix -->
                                <h3 class="card-title"><div class="badge badge-dark text-wrap"><?= $produit['prix'] ?> €</div></h3>
                                <!-- sa description -->
                                <p class="card-text"><?= $produit['description'] ?></p>
                                <!-- transit dans href de l'id_produit pour récupérer les infos dans une car particulière codée dans le fiche_produit.php -->
                                <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>" class="btn btn-outline-success"><i class='bi bi-search'></i> Voir Produit</a>
                            </div>
                        </div>
                     <?php endwhile; ?>
                </div>

                <nav aria-label="">
                    <!-- le traitement pour la pagination a été codé dans affichage.php, s'y référer pour comprendre la suite -->
                    <ul class="pagination justify-content-end">
                        <!-- si je suis sur la page 1, cet onglet (vers la page - 1) sera desactivé -->
                        <li class="mx-1 page-item <?= ($pageCourante == 1) ? "disabled" : "" ?>">
                            <!-- pour aller sur la page - 1 (exemple, je suis sur la page 3, pour aller vers la page 2) -->
                            <!-- dans les 3 <a href> je dois faire référence à la catégorie, en plus de la page, sinon cela ne fonctionnera pas -->
                            <a class="page-link text-success" href="?page=<?= $pageCourante - 1 ?>&categorie=<?= $titreCategorie['categorie'] ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <!-- boucle for() qui va me générer autant de pages qu'il existe d'articles concernés par la catégorie (trois articles affichés par page) -->
                        <?php for($page = 1; $page <= $nombrePages; $page++): ?>
                            <li class="mx-1 page-item ">
                                <!-- si la page courante est égale à $page, l'onglet sera actif -->
                                <a class="btn btn-outline-success <?= ($pageCourante == $page) ? "active" : "" ?>" href="?page=<?= $page ?>&categorie=<?= $titreCategorie['categorie'] ?>"><?= $page ?></a>
                            </li>
                        <?php endfor; ?>
                        <!-- si je suis sur la dernière page, cet onglet (pour aller vers la page + 1) sera desactivé -->
                        <li class="mx-1 page-item <?= ($pageCourante == $nombrePages) ? "disabled" : "" ?>">
                            <a class="page-link text-success" href="?page=<?= $pageCourante + 1 ?>&categorie=<?= $titreCategorie['categorie'] ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
               
            </div>

            <!-- ou que l'on clique sur un onglet public -->
            <?php elseif(isset($_GET['public'])): ?>
            <div class="col-md-8">
            
                <div class="text-center my-5">
                    <img class='img-fluid' src="img/la_boutique_bis.webp" alt="Bandeau de La Boutique" loading="lazy">
                </div>

                <div class="row justify-content-around">
                    
                    <h2 class="py-5"><div class="badge badge-dark text-wrap"><?= ($titrePublic == 'mixte') ? "Nos modèles Mixtes" : "Nos modèles pour " . ucfirst($titrePublic['public']) . "s" ?> <!-- ternaire selon modèles mixtes ou modèles pour enfants, femmes, hommes
                Avec première lettre en majuscules grace a ucfirst()--> </div></h2>
                </div>

                <div class="row justify-content-around text-center">

                        
                    <?php while($produit = $afficheProduits->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="card mx-3 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
                            <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>"><img src="<?= URL . 'img/' . $produit['photo'] ?>" class="card-img-top" alt="..."></a>
                            <div class="card-body">
                                <h3 class="card-title"><?= $produit['titre'] ?></h3>
                                <h3 class="card-title"><div class="badge badge-dark text-wrap"><?= $produit['prix'] ?> €</div></h3>
                                <p class="card-text"><?= $produit['description'] ?></p>
                                <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>" class="btn btn-outline-success"><i class='bi bi-search'></i> Voir Produit</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <nav aria-label="">
                <!-- dans les 3 <a href> je dois faire référence au public, en plus de la page, sinon cela ne fonctionnera pas -->
                    <ul class="pagination justify-content-end">
                        <li class="mx-1 page-item <?= ($pageCourante == 1) ? "disabled" : "" ?>">
                            <a class="page-link text-success" href="?page=<?= $pageCourante - 1 ?>&public=<?= $titrePublic['public'] ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <?php for($page = 1; $page <= $nombrePages; $page++): ?>
                            <li class="mx-1 page-item ">
                                <a class="btn btn-outline-success <?= ($pageCourante == $page) ? "active" : "" ?>" href="?page=<?= $page ?>&public=<?= $titrePublic['public'] ?>"><?= $page ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="mx-1 page-item <?= ($pageCourante == $nombrePages) ? "disabled" : "" ?>">
                            <a class="page-link text-success" href="?page=<?= $pageCourante + 1 ?>&public=<?= $titrePublic['public'] ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            
            </div>

            <!-- ou que l'on a cliqué sur rien, on arrive pour la première fois sur l'accueil
            image plus grande
            pas de produit affiché -->
            <?php else: ?>
            <div class="col-md-8">

                <div class="row justify-content-around py-5">
                    <img class='img-fluid' src="img/la_boutique.webp" alt="Bandeau de La Boutique" loading="lazy">    
                </div>

            </div>
            <?php endif; ?>

        </div>

    </div>
<div class="container">

<?php require_once('include/footer.php');