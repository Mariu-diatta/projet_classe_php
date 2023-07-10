<?php
    require_once('include/init.php');
    require_once('include/fonctions.php');
    require_once('include/header.php');
?>

<h2 class="text-center py-5"><div class="badge badge-dark text-wrap p-3">Connexion</div></h2>

<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format adresse !</div>'; -->

<!-- $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    <strong>Félicitations !</strong> Votre inscription est réussie 😉, vous pouvez vous connecter !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>'; -->

<form class="my-5" method="POST" action="#">

    <div class="col-md-4 offset-md-4 my-4">

    <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
    <input class="form-control btn btn-outline-success mb-4" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo">

    <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
    <input class="form-control btn btn-outline-success mb-4" type="password" name="mbp" id="mbp" placeholder="Votre mot de passe">

    <button type="submit" class="btn btn-lg btn-outline-success offset-md-4 my-2">Connexion</button>

    </div>
   
</form>



<?php

if(isset($_GET['action']) && $_GET['action'] == "deconnexion"){
    unset($_SESSION['membre']);
    // session_destroy ne va pas supprimer le fichier crée dans temp(XAMP) lors de la connexion. Il va par contre le vider de ses informations (de 1ko a 0ko en taille)
    // session_destroy();
    // je ne fais plus de session_destroy car lorsque je me deconnecte, cela detruit la session panier aussi. Désormais je veux quand même conserver tout ce qu'il y a dans le panier, d'une session a l'autre
    header('location: connexion.php');
    exit();
}

if(internauteConnecte()){
    // j'interdis l'acces a cette page à un user qui est déjà connecté.Je le renvoi vers sa page profil
    header('location: profil.php');
    exit();
}

// le title de ma page, inséré dans le header
$pageTitle = "Connexion";

// message de félicitations pour le user qui aura réussi son inscription. Il arrive sur connexion.php avec ce message en plus
if(isset($_GET['action']) && $_GET['action'] == "validate"){
    $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    <strong>Félicitations !</strong> Votre inscription est réussie 😉, vous pouvez vous connecter !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
}

if($_POST && !internauteConnecte()){
    // procédure d'authentification commence par vérifier si le pseudo existe en BDD
    $verifUser = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verifUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $verifUser->execute();
    echo "je lance la connexion";
    // si j'en trouve un similaire en bdd
    if($verifUser->rowCount() == 1){
        // fetch pour récupérer ses données en bdd, dont le mdp qui va m'interesser tout de suite, puis d'autres infos, un peu plus bas pour ouvrir la session
        $user = $verifUser->fetch(PDO::FETCH_ASSOC);
        echo "Il y a un seul membre";
        // debug($user);
        if(password_verify($_POST['mbp'], $user['mbp'])){
        // password_verify prend deux arguments. Le mdp du formulaire et le compare au mdp stocké en bdd
        echo "je valide le pseudo";
        // $_SESSION['membre']['id_membre'] = $membre['id_membre'];
        // $_SESSION['membre']['pseudo'] = $membre['pseudo'];
        // $_SESSION['membre']['nom'] = $membre['nom'];
        // $_SESSION['membre']['prenom'] = $membre['prenom'];
        // $_SESSION['membre']['email'] = $membre['email'];
        // $_SESSION['membre']['civilite'] = $membre['civilite'];
        // $_SESSION['membre']['ville'] = $membre['ville'];
        // $_SESSION['membre']['code_postal'] = $membre['code_postal'];
        // $_SESSION['membre']['adresse'] = $membre['adresse'];
        // $_SESSION['membre']['statut'] = $membre['statut'];

        // la foreach ci dessous remplace tout code qui précède
            foreach($user as $key => $value){
                if($key != 'mbp'){
                    // si le user réussit a se connecter, on lui ouvre une session, et on collecte toutes ses données enregistrées en bdd (sauf le mdp). Ci dessus la syntaxe (commentée), champs par champs,syntaxe longue, détaillée, au lieu de la boucle foreach
                    $_SESSION['membre'][$key] = $value;
                    if(internauteConnecteAdmin()){
                    // trois rediractions possibles. si on est admin, si on se connecte en venant du panier, ou en temps que user lambda
                        // redirection vers le back office si c'est l'admin
                        header('location:admin/index.php?action=validate');
                    }elseif(isset($_GET['action']) && $_GET['action'] == "acheter"){
                        // redirection vers le panier si le user en vient après sa connection
                        header('location:panier.php');
                    }else{
                        // redirection vers le profil si c'est un user lambda
                        $pudateStatut =  $pdo->prepare("UPDATE produit set statut=1 WHERE pseudo = :pseudo");
                        $pudateStatut->bindValue(':statut', 1, PDO::PARAM_STR);
                        $pudateStatut->execute();
                        echo "je suis connecté";
                        header('location:profil.php?action=validate');
                    }
                }
            }
        }else{
            // si le mot de passe ne correspond pas
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur mot de passe inconnu !</div>';
        }
    }else{
        // si le pseudo n'existe pas en bdd
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur pseudo inconnu !</div>';
    }
}   
?>

<?php require_once('include/footer.php');