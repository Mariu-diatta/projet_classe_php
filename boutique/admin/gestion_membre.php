<?php
require_once('../include/init.php');

if(!internauteConnecteAdmin()){
    header('location:' . URL . 'connexion.php');
    exit();
}

// pagination
// pagination d'un tableau https://nouvelle-techno.fr/actualites/mettre-en-place-une-pagination-en-php
// je vérifie que l'url reçoit bien un $_GET 'page'
if(isset($_GET['page']) && !empty($_GET['page'])){
    // je m'assure que c'est bien un entier que je reçoit dans le "page"
    $pageCourante = (int) strip_tags($_GET['page']);
}else{
    // par défaut, ma page sera la première
    $pageCourante = 1;
}
// je détermine le nombre de membres, en usant d'un alias (nombreMembres)
$queryUser = $pdo->query("SELECT COUNT(id_membre) AS nombreUsers FROM membre");
// je récupère le nb de membres
$resultatUsers = $queryUser->fetch();
// je force la conversion en nombre entier.
$nombreUsers = (int) $resultatUsers['nombreUsers'];

// je determine le nb de membres par page
$parPage = 10;
// je determine le nmbre de pages dont je vais avoir besoin
// j'utilise ceil() pour arrondir automatiquement à l'entier supérieur si le résultat de la division n'est pas un nombre entier
$nombrePages = ceil($nombreUsers / $parPage);
// je determine quel sera le membre pour chaque début de page
// exemple, pour la page 1, je vais avoir $premierUser = (1 - 1) x 10 ce qui me donnera en page 1, le membre 0
// pour la page 2 (2 - 1) x 10 = le membre 10 etc....
$premierUser = ($pageCourante - 1) * $parPage;
// pagination

// je ne débute la validation du formulaire seulement si une action a été demandée: action d'ajout, modif ou suppression
if(isset($_GET['action'])){
    if($_POST){
        if(!isset($_POST['pseudo']) || !preg_match('#^[a-zA-Z0-9-_.]{3,20}$#', $_POST['pseudo'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pseudo !</div>';
        }
        // je ne procède a ces vérifications que dans le cas de l'ajout et non pas d'une modification (vérifier que le pseudo n'existe pas, hashage du mdp)
        if($_GET['action'] == 'add'){
            $verifPseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
            $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $verifPseudo->execute();
        
            if($verifPseudo->rowCount() == 1){
                $erreur .= '<div class="alert alert-danger" role="alert">Erreur, ce pseudo est déjà pris !</div>';
            }
            
            if(!isset($_POST['mdp']) || iconv_strlen($_POST['mdp']) < 3 || iconv_strlen($_POST['mdp']) > 20){
                $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mot de passe !</div>';
            }
        
            $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

        }
        // fin de la vérif particuliere a l'ajout et retour aux vérifications communes
        
        if(!isset($_POST['nom']) || iconv_strlen($_POST['nom']) < 1 || iconv_strlen($_POST['nom']) > 20){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format nom !</div>';
        }
        if(!isset($_POST['prenom']) || iconv_strlen($_POST['prenom']) < 1 || iconv_strlen($_POST['prenom']) > 20){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prenom !</div>';
        }
        if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format email !</div>';
        }
        if(!isset($_POST['civilite']) || $_POST['civilite'] != "femme" && $_POST['civilite'] != "homme" ){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format civilité !</div>';
        }
        if(!isset($_POST['ville']) || iconv_strlen($_POST['ville']) < 3 || iconv_strlen($_POST['ville']) > 20){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format ville !</div>';
        }
        if(!isset($_POST['code_postal']) || !preg_match('#^[0-9]{5}$#', $_POST['code_postal'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format code postal !</div>';
        }
        if(!isset($_POST['adresse']) || iconv_strlen($_POST['adresse']) < 5 || iconv_strlen($_POST['adresse']) > 20){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format adresse !</div>';
        }

        if(empty($erreur)){
            // si pas d'erreur pour commencer la validation du formulaire, deux cas de figure. En premier la modif, puis le cas de l'ajout.
            if($_GET['action'] == 'update'){
                $modifierUser = $pdo->prepare("UPDATE membre SET id_membre = :id_membre, pseudo = :pseudo, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, ville = :ville, code_postal = :code_postal, adresse = :adresse WHERE id_membre = :id_membre ") ;
                $modifierUser->bindValue(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
                $modifierUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                $modifierUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                $modifierUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                $modifierUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $modifierUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
                $modifierUser->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                $modifierUser->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
                $modifierUser->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                $modifierUser->execute();

                $queryUsers = $pdo->query("SELECT pseudo FROM membre WHERE id_membre = '$_GET[id_membre]' ");
                $user = $queryUsers->fetch(PDO::FETCH_ASSOC);
                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                                <strong>Félicitations !</strong> Modification du user <strong>'. $user['pseudo'] .'</strong> réussie !
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
            }else{
                $ajouterUser = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse) ");
                $ajouterUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                $ajouterUser->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
                $ajouterUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                $ajouterUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                $ajouterUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $ajouterUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
                $ajouterUser->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                $ajouterUser->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
                $ajouterUser->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                $ajouterUser->execute();

                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                                <strong>Félicitations !</strong> Ajout du user réussie !
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
            }
        }
    }
    // tout ce qui va servir pour que le formulaire reprenne les données pré-existantes en bdd pour l'update. Utile et necessaire pour l'admin. Je crée une variable a laquelle j'affecte la valeur correspondante en bdd. Puis je l'injecte dans le formulaire si elle existe
    if($_GET['action'] == 'update'){
        $queryUsers = $pdo->query("SELECT * FROM membre WHERE id_membre = '$_GET[id_membre]' ");
        $userActuel = $queryUsers->fetch(PDO::FETCH_ASSOC);
    }

    $id_membre = (isset($userActuel['id_membre'])) ? $userActuel['id_membre'] : "";
    $pseudo = (isset($userActuel['pseudo'])) ? $userActuel['pseudo'] : "";
    $nom = (isset($userActuel['nom'])) ? $userActuel['nom'] : "";
    $prenom = (isset($userActuel['prenom'])) ? $userActuel['prenom'] : "";
    $email = (isset($userActuel['email'])) ? $userActuel['email'] : "";
    $civilite = (isset($userActuel['civilite'])) ? $userActuel['civilite'] : "";
    $ville = (isset($userActuel['ville'])) ? $userActuel['ville'] : "";
    $code_postal = (isset($userActuel['code_postal'])) ? $userActuel['code_postal'] : "";
    $adresse = (isset($userActuel['adresse'])) ? $userActuel['adresse'] : "";
    // fin de la création des variables a réinjecter pour l'update

    // pour le delete si on clique sur l'icone poubelle dans le tableau
    if($_GET['action'] == 'delete'){
        $pdo->query("DELETE FROM membre WHERE id_membre = '$_GET[id_membre]' ");
    }
}
// fin de la validation des actions

require_once('includeAdmin/header.php');
?>

<h1 class="text-center my-5"><div class="badge badge-warning text-wrap p-3">Gestion des utilisateurs</div></h1>

<!-- <div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
    <p>Gérez ici votre base de données des utilisateurs</p>
    <p>Vous pouvez modifier leurs données, ajouter ou supprimer un utilisateur</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div> -->

<?= $erreur ?>
<?= $content ?>

<!-- le formulaire et son h2 n'apparaissent que si on en a fait la demande a partir du tableau des données. Sinon, c'est le tableau uniquement qui s'affiche, plus pratique pour l'admin -->
<?php if(isset($_GET['action'])): ?>
<h2 class="my-5">Formulaire <?= ($_GET['action'] == "add") ? "d'ajout" : "de modification" ?> des utilisateurs</h2>

<form class="my-5" method="POST" action="">

    <input type="hidden" name="id_membre" value="<?= $id_membre ?>">

    <div class="row">
        <div class="col-md-4 mt-5">
        <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
        <input class="form-control" type="text" name="pseudo" id="pseudo"  value="<?= $pseudo ?>" placeholder="Pseudo">
        </div>

        <!-- la création du mdp n'apparait que si c'est dans le cas d'un ajout d'un user. Pas pour modifier les données du user. Seul lui peut le faire en réinitialisant son mdp -->
        <?php if($_GET['action'] == "add"): ?>
        <div class="col-md-4 mt-5">
        <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
        <input class="form-control" type="password" name="mdp" id="mdp" placeholder="Mot de passe">
        </div>
        <?php endif; ?>
        <!-- fin mdp -->
        
        <div class="col-md-4 mt-5">
        <label class="form-label" for="email"><div class="badge badge-dark text-wrap">Email</div></label>
        <input class="form-control" type="email" name="email" id="email"  value="<?= $email ?>" placeholder="Email">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mt-5">
        <label class="form-label" for="nom"><div class="badge badge-dark text-wrap">Nom</div></label>
        <input class="form-control" type="text" name="nom" id="nom"  value="<?= $nom ?>" placeholder="Nom">
        </div>

        <div class="col-md-4 mt-5">
        <label class="form-label" for="prenom"><div class="badge badge-dark text-wrap">Prénom</div></label>
        <input class="form-control" type="text" name="prenom" id="prenom" value="<?= $prenom ?>" placeholder="Prénom">
        </div>

        <div class="col-md-4 mt-4">
            <p><div class="badge badge-dark text-wrap">Civilité</div></p>

            <input type="radio" name="civilite" id="civilite1" value="femme" <?= ($civilite == "femme") ? "checked" : "" ?>>
            <label class="mx-2" for="civilite1">Femme</label>

            <input type="radio" name="civilite" id="civilite2" value="homme" <?= ($civilite == "homme") ? "checked" : "" ?>>
            <label class="mx-2" for="civilite2">Homme</label>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mt-5">
            <label class="form-label" for="ville"><div class="badge badge-dark text-wrap">Ville</div></label>
            <input class="form-control" type="text" name="ville" id="ville" value="<?= $ville ?>" placeholder="Ville">
        </div>

        <div class="col-md-4 mt-5">
            <label class="form-label" for="code_postal"><div class="badge badge-dark text-wrap">Code Postal</div></label>
            <input class="form-control" type="text" name="code_postal" id="code_postal" value="<?= $code_postal ?>" placeholder="Code postal">
        </div>

        <div class="col-md-4 mt-5">
            <label class="form-label" for="adresse"><div class="badge badge-dark text-wrap">Adresse</div></label>
            <input class="form-control" type="text" name="adresse" id="adresse" value="<?= $adresse ?>" placeholder="Adresse">
        </div>
    </div>

    <div class="col-md-1 mt-5">
    <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
    </div>

</form>
<?php endif; ?>

<?php $queryUsers = $pdo->query("SELECT id_membre FROM membre") ?>
<h2 class="py-5">Nombre d'utilisateurs en base de données: <?= $queryUsers->rowCount() ?></h2>

<div class="row justify-content-center py-5">
    <a href='?action=add'>
        <button type="button" class="btn btn-sm btn-outline-dark btn-warning text-dark">
        <i class="bi bi-plus-circle-fill text-dark"></i> Ajouter un utilisateur
        </button>
    </a>
</div>

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
<?php $afficheUsers = $pdo->query("SELECT * FROM membre ORDER BY pseudo ASC LIMIT $parPage OFFSET $premierUser") ?>
    <thead>
        <tr>
            <?php for($i = 0; $i < $afficheUsers->columnCount(); $i++){
                $colonne = $afficheUsers->getColumnMeta($i);
                if($colonne['name'] != 'mdp'){ ?>
                <th><?= $colonne['name'] ?></th>
            <?php }
            } ?>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($user = $afficheUsers->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <?php foreach($user as $key => $value){
                if($key != 'mdp'){ ?>
                <td><?= $value ?></td>
                <?php }
            } ?>
                <td><a href='?action=update&id_membre=<?= $user['id_membre'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
                <td><a data-href="?action=delete&id_membre=<?= $user['id_membre'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
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
                Supprimer Utilisateur
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer cet utilisateur de votre base de données ?
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
<!-- avec un if pour qu'elle n'apparaisse qu'en tout début, et pas a chaque changement de page -->
<?php if(!isset($_GET['action']) && !isset($_GET['page'])): ?>
<div class="modal fade" id="myModalUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-warning" id="exampleModalLabel">Gestion des utilisateurs</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Gérez ici votre base de données des utilisateurs</p>
        <p>Vous pouvez modifier leurs données, ajouter ou supprimer un utilisateur</p>
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