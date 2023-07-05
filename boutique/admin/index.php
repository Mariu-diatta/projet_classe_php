<?php
require_once('../init.php');

// si le user n'est pas admin, je le redirige vers la page de connexion
if(!internauteConnecteAdmin()){
    header('location:' . URL . 'connexion.php');
    exit();
}

// message de bienvenue pour l'admin
if(isset($_GET['action']) && $_GET['action'] == "validate"){
    $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    Félicitations <strong>' . $_SESSION['membre']['pseudo'] .'</strong>, vous etes connecté(e) 😉 !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
}

require_once('includeAdmin/header.php');
?>

        <h1 class="my-5 text-center"><div class="badge badge-warning text-wrap p-3">Accueil Admin</div></h1>
        <!-- j'affihe le message de bienvenue -->
        <?= $validate ?>
        <!-- je récupère le pseudo avec la session -->
        <h2 class="text-center my-5"><div class="badge badge-dark text-wrap p-3">Bonjour  <?= $_SESSION['membre']['pseudo'] ?></div></h2>
        
        <div class="row justify-content-around my-5">
                <img class='img-fluid' src="<?= URL ?>img/back_office.webp" alt="Image du Back Office" loading="lazy">  
        </div>

<?php require_once('includeAdmin/footer.php');