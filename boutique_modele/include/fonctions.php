<?php

function debug($var, $mode = 1){
    // je crée cette fonction qui sera désormais reprise partout sur le site pour ne plus avoir a ecrire en longueur le <pre></pre> etc....je n'ai plus qu'a appeler ma fonction
    $trace = debug_backtrace();
    // fonction prédéfinie qui permet de tracer le nom du fichier, la ligne (entre autres) où l'on a demandé le debug
    $trace = array_shift($trace);
    // fonction prédéfinie qui permet de retirer une dimension a un tableau multi-dimentionnel. Concrètement, je n'aurais plus qu'a  crocheter (ci dessous) sur file ou line et non plus $trace[0]['file]

    echo "Debug demandé sur le fichier <strong>" . $trace['file'] . "</strong>, en ligne <strong>" . $trace['line'] . "</strong>";

    if($mode == 1){
        echo "<pre>"; print_r($var); echo "</pre>";
    }else{
        echo "<pre>"; var_dump($var); echo "</pre>";
    }
}

// fonction qui vérifie si l'internaute est connecté
function internauteConnecte(){
    if(!isset($_SESSION['membre'])){
        // si la session membre n'existe pas, elle retourne faux
        return FALSE;
    }else{
        //  dans le cas contraire (elle existe) elle retourne vrai
        return TRUE;
    }
}

// fonction qu vérifie si l'internaute connecté est admin ou non
function internauteConnecteAdmin(){
    if(internauteConnecte() && $_SESSION['membre']['statut'] == 1){
        // verifie que si l'internaute est connecté (reprend la fonction précédente) et que en même temps son statut == 1
        return TRUE;
        // si les deux conditions sont vérifiées, elle retourne vrai
    }else{
        // sinon, elle retourne faux
        return FALSE;
    }
}

// si le panier n'existe pas encore
function creerPanier(){
    // if pour ne pas ecraser un panier precedent déjà existant.
    if(!isset($_SESSION['panier'])){
        // si la session panier n'existe pas, on declare différents tableaux
        // le premier concerne la session panier
        $_SESSION['panier'] = array();
        // les autres concernent les différentes valeurs que je veux contenir dans mon tableau
        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['categorie'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['photo'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}

// si le panier existe et qu'on ajoute des produits
function ajouterAuPanier($id_produit, $categorie, $titre, $photo, $quantite, $prix){
    // j'inclus creerPanier() qui va permettre d'ajouter des arrays pour chaque nouveau produit dans un panier déjà existant
    creerPanier();
    $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);
    // array_serach va me permettre de vérifier si l'id_produit existe déjà dans mon array.
    // le but est de vérifier qu'un même produit existe déjà dans mon panier, pour ne pas créer une ligne supplémentaire a mon array, mais d'additionner la nouvelle quantité désirée à l'ancienne.

    if($positionProduit !== FALSE){
        // je suis obligé d'utiliser la triple différence (comme === est une triple égalité) car l'indice 0 (pour le produit 1), va etre considéré comme FALSE aussi en cas de différence simple (!=)
        // si je veux tout de même repérer le produit qui a l'indice 0, alors je dois le repérer avec !==
        $_SESSION['panier']['quantite'][$positionProduit] += $quantite;
        // ainsi, si le produit que je veux ajouter existe déjà, alors je ne modifie que la quantité. Sinon, dans le cas du else, j'ajoute ce nouveau produit au panier existant avec toutes ses nouvelles données.
    }else{
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['categorie'][] = $categorie;
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['photo'][] = $photo;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;
    }
}

// si un article n'est plus a vendre (rupture de stock) entre le moment ou il a été selectionné et le moment ou le user va payer
function retirerDuPanier($id_produit_a_retirer){
    // comme pour creationPanier() je dois vérifier le position de l'article dans mon panier avec array_search, et une fois trouvé, je fais en sorte de vider toutes ses données du panier
    $positionProduit = array_search($id_produit_a_retirer, $_SESSION['panier']['id_produit']);

    if($positionProduit !== FALSE){
        // le troisième argument (mode = 1) de array_splice, permet de supprimer l'info, mais aussi de faire "glisser" le produit suivant dans l'indice du tableau laissé vacant
        array_splice($_SESSION['panier']['id_produit'], $positionProduit,1);
        array_splice($_SESSION['panier']['categorie'], $positionProduit,1);
        array_splice($_SESSION['panier']['titre'], $positionProduit,1);
        array_splice($_SESSION['panier']['photo'], $positionProduit,1);
        array_splice($_SESSION['panier']['quantite'], $positionProduit,1);
        array_splice($_SESSION['panier']['prix'], $positionProduit,1);
    }
}

function montantTotal(){
    $total = 0;
    for($i = 0; $i < COUNT($_SESSION['panier']['id_produit']); $i++){
        // += ici (comme en haut pour += quantite) permet d'additioner au fur et a mesure les différents totaux pour chaque produit, au lieu qu' un total pour un produit vienne remplacer le total du produit précédent.
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
    }
    return round($total,2);
    // je permets au cas ou un max de deux chiffres après la virgule
}