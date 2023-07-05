<?php
require_once('include/init.php');

if(!internauteConnecte()){
    // si la session membre n'existe pas, on interdit l'acces au profil (qui necessite l'ouverture d'une session, sinon erreur php) et on le redirige vers la page de connexion (ou autre, mais souvent connexion)
    header('location:' . URL . 'connexion.php');
    // si on n'est pas connecté, on arrete le script avec le exit. Fonctionne sans, mais par sécurité, on stoppe le script pour celui qui n'a pas a se trouver ici
    exit();
}

if(isset($_GET['action']) && $_GET['action'] == "validate"){
    $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    Félicitations <strong>' . $_SESSION['membre']['pseudo'] .'</strong>, vous etes connecté(e) 😉 !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
}

// title qui récupère le pseudo via la session
$pageTitle = "Profil de " . $_SESSION['membre']['pseudo'];

require_once('include/header.php');
?>

<!-- titre accueil en profil, personnalisé car il récupère le pseudo de l'utilisateur connecté via la session (ouverte dans connexion.php). si le user est aussi admin, il aura droit à un message personnalisé encore plus-->
<h2 class="text-center my-5"><div class="badge badge-dark text-wrap p-3">Bonjour <?= (internauteConnecteAdmin()) ? $_SESSION['membre']['pseudo'] . ", vous etes admin du site" : $_SESSION['membre']['pseudo'] ?></div></h2>

<?= $validate ?>

<div class="row justify-content-around py-5">
    <div class="col-md-2">
        <ul class="list-group">
            <!-- je récupère différentes infos stockées dans la session pour afficher son profil -->
            <li class="btn btn-outline-success text-dark my-3 shadow bg-white rounded"><?= $_SESSION['membre']['prenom'] ?></li>
            <li class="btn btn-outline-success text-dark my-3 shadow bg-white rounded"><?= $_SESSION['membre']['nom'] ?></li>
            <li class="btn btn-outline-success text-dark my-3 shadow bg-white rounded"><?= $_SESSION['membre']['email'] ?></li>
            <li class="btn btn-outline-success text-dark my-3 shadow bg-white rounded"><?= $_SESSION['membre']['adresse'] ?></li>
        </ul>
    </div>
</div>

<?php require_once('include/footer.php');