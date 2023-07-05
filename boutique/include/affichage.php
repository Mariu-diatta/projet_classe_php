<?php
// affichage des catégories dans la navigation latérale
$afficheMenuCategories = $pdo->query("SELECT DISTINCT categorie FROM produit ORDER BY categorie ASC");
// fin de navigation laterale catégories

// tout l'affichage par categorie
if(isset($_GET['categorie'])){
    // pagination pour les categories
    // pagination d'un tableau https://nouvelle-techno.fr/actualites/mettre-en-place-une-pagination-en-php
    // je vérifie que l'url reçoit bien un $_GET 'page'
    if(isset($_GET['page']) && !empty($_GET['page'])){
        // je m'assure que c'est bien un entier que je reçoit dans le "page"
        $pageCourante = (int) strip_tags($_GET['page']);
    // le else est prévu pour le cas ou GET['page'] est vide
    }else{
        //et dans ce cas, par défaut, ma page sera la première
        $pageCourante = 1;
    }

    // ce query devra faire référence à la catégorie du produit, sinon la pagination ne fonctionnera que pour la première page (limité aux trois premiers produits...ensuite page blanche, mais je ne pourrais pas récupérer les produits suivants de la même catégorie)
    // je cible le COUNT() avec l'index id_produit pour optimiser la requete, son affichage et donc l'XP. Equivalent a un rowCount, mais autre méthode
    // je détermine le nombre de produits, en usant d'un alias (nombreProduits)
    $queryProduits = $pdo->query("SELECT COUNT(id_produit) AS nombreProduits FROM produit WHERE categorie = '$_GET[categorie]'  ");
    // je récupère le nb de produits
    $resultatProduits = $queryProduits->fetch();
    // je force la conversion en nombre entier.
    $nombreProduits = (int) $resultatProduits['nombreProduits'];

    // je determine le nb de produits par page
    $parPage = 3;
    // je determine le nmbre de pages dont je vais avoir besoin
    // j'utilise ceil() pour arrondir automatiquement à l'entier supérieur si le résultat de la division n'est pas un nombre entier
    $nombrePages = ceil($nombreProduits / $parPage);
    // je determine quel sera le produit pour chaque début de page
    // exemple, pour la page 1, je vais avoir pageCourante = (1 x 10) - 10 ce qui me donnera en page 1, le membre 0
    // pour la page 2 (2 x 10) - 10 = le membre 10 etc....
    // $premierProduit = ($pageCourante * $parPage) - $parPage;
    // sinon, autre calcul, plus simple
    $premierProduit = ($pageCourante - 1) * $parPage;
    // fin pagination pour les categories

    // affichage de tous les produits concernés par une categorie
    $afficheProduits = $pdo->query("SELECT * FROM produit WHERE categorie = '$_GET[categorie]' ORDER BY prix ASC LIMIT $parPage OFFSET $premierProduit ");
    // fin affichage des produits par categorie

    // affichage de la categorie dans le <h2>
    $afficheTitreCategorie = $pdo->query("SELECT categorie FROM produit WHERE categorie = '$_GET[categorie]' ");
    $titreCategorie = $afficheTitreCategorie->fetch(PDO::FETCH_ASSOC);
    // fin du h2 categorie

    // pour les onglets categories
    $pageTitle = "Nos modèles de " . $_GET['categorie'];
    // ci dessus, ecriture très rapide au lieu de celle qui suit
    // --------------------------------------------------------------------------
    // if($_GET['categorie'] == "Jupes"){
    //     $pageTitle = "Nos modèles de Jupes";
    // }
    // elseif($_GET['categorie'] == "Manteaux"){
    //     $pageTitle = "Nos modèles de Manteaux";
    // }
    // elseif($_GET['categorie'] == "Pantalons"){
    //     $pageTitle = "Nos modèles de Pantalons";
    // }
    // elseif($_GET['categorie'] == "Pulls"){
    //     $pageTitle = "Nos modèles de Pulls";
    // }
    // elseif($_GET['categorie'] == "Robes"){
    //     $pageTitle = "Nos modèles de Robes";
    // }
    // elseif($_GET['categorie'] == "Sous-Vetements"){
    //     $pageTitle = "Nos modèles de Sous-Vetements";
    // }
    // else{
    //     $pageTitle = "Nos modèles de Vestes";
    // }
    // -----------------------------------------------------------------------
    // fin onglets categories
}
// fin affichage par categorie

// -----------------------------------------------------------------------------------

// tout l'affichage par public
if(isset($_GET['public'])){
    // pagination produits par public
    if(isset($_GET['page']) && !empty($_GET['page'])){
        $pageCourante = (int) strip_tags($_GET['page']);
    }else{
        $pageCourante = 1;
    }

    $queryProduits = $pdo->query("SELECT COUNT(id_produit) AS nombreProduits FROM produit WHERE public = '$_GET[public]' ");
    $resultatProduits = $queryProduits->fetch();
    $nombreProduits = (int) $resultatProduits['nombreProduits'];

    $parPage = 3;
    $nombrePages = ceil($nombreProduits / $parPage);
    $premierProduit = ($pageCourante - 1) * $parPage;
    // fin pagination produits par public

    // affichage des produits par public
    $afficheProduits = $pdo->query("SELECT * FROM produit WHERE public = '$_GET[public]' ORDER BY prix ASC LIMIT $parPage OFFSET $premierProduit ");
    // fin affichage des produits par public

    // affichage du public dans le <h2>
    $afficheTitrePublic = $pdo->query("SELECT public FROM produit WHERE public = '$_GET[public]' ");
    $titrePublic = $afficheTitrePublic->fetch(PDO::FETCH_ASSOC);
    // fin du </h2> pour le public

    // pour les onglets publics
    if($_GET['public'] == "mixte"){
        $pageTitle = "Nos modèles Mixtes";
    }else{
        $pageTitle = "Nos modèles pour " . ucfirst($_GET['public']) . "s";
    }
    // fin onglets publics
}
// fin affichage par public

// ---------------------------------------------------------------------------------------
// Tout ce qui concerne la fiche produit

// affichage d'un produit
if(isset($_GET['id_produit'])){
    $detailProduit = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]' ");
    // ci dessous si le résultat de la requete n'aboutit pas, exemple injection dans l'url d'une valeur inexistante pour l'id produit, je redirige automatiquement
    if($detailProduit->rowCount() <= 0){
        header('location:' . URL);
        exit();
    }
    $detail = $detailProduit->fetch(PDO::FETCH_ASSOC);
}
// fin affichage d'un seul produit


//  fin fiche produit