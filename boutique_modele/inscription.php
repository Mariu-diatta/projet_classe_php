
<h2 class="text-center py-5"><div class="badge badge-dark text-wrap p-3">Inscription</div></h2>



<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pseudo !</div>'; -->

<form class="my-5" method="POST" action="">

    <div class="row">
        <div class="col-md-4 mt-5">
        <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
        <input class="form-control btn btn-outline-success" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" max-length="20" pattern="[a-zA-Z0-9-_.]{3,20}" title="caractères acceptés: majuscules et minuscules, chiffres, signes tels que: - _ . , entre trois et vingt caractères." required>
        </div>

        <div class="col-md-4 mt-5">
        <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
        <input class="form-control btn btn-outline-success" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe" required>
        </div>
        
        <div class="col-md-4 mt-5">
        <label class="form-label" for="email"><div class="badge badge-dark text-wrap">Email</div></label>
        <input class="form-control btn btn-outline-success" type="email" name="email" id="email" placeholder="Votre email" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mt-5">
        <label class="form-label" for="nom"><div class="badge badge-dark text-wrap">Nom</div></label>
        <input class="form-control btn btn-outline-success" type="text" name="nom" id="nom" placeholder="Votre nom">
        </div>

        <div class="col-md-4 mt-5">
        <label class="form-label" for="prenom"><div class="badge badge-dark text-wrap">Prénom</div></label>
        <input class="form-control btn btn-outline-success" type="text" name="prenom" id="prenom" placeholder="Votre prénom">
        </div>

        <div class="col-md-4 mt-5 pt-2">
        <p><div class="badge badge-dark text-wrap">Civilité</div></p> 
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="civilite" id="civilite1" value="femme">
                <label class="form-check-label mx-2" for="civilite1">Femme</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="civilite" id="civilite2" value="homme" checked>
                <label class="form-check-label mx-2" for="civilite2">Homme</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mt-5">
            <label class="form-label" for="ville"><div class="badge badge-dark text-wrap">Ville</div></label>
            <input class="form-control btn btn-outline-success" type="text" name="ville" id="ville" placeholder="Votre ville">
        </div>

        <div class="col-md-4 mt-5">
            <label class="form-label" for="code_postal"><div class="badge badge-dark text-wrap">Code Postal</div></label>
            <input class="form-control btn btn-outline-success" type="text" name="code_postal" id="code_postal" placeholder="Votre code postal">
        </div>

        <div class="col-md-4 mt-5">
            <label class="form-label" for="adresse"><div class="badge badge-dark text-wrap">Adresse</div></label>
            <input class="form-control btn btn-outline-success" type="text" name="adresse" id="adresse" placeholder="Votre adresse">
        </div>
    </div>

    <div class="col-md-1 mt-5">
    <button type="submit" class="btn btn-lg btn-outline-success">Valider</button>
    </div>
    
</form>
