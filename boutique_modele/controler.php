<?php
require_once('include/init.php');
// si' linternaute est connecté, je ne lui donne pas accés a cette page. Je le redirige vers son profil
if(internauteConnecte()){
    header('location:profil.php');
}

if($_POST){
    // debug($_POST);
    // debug($pdo);
    // début de vérification des champs
    if(!isset($_POST['pseudo']) || !preg_match('#^[a-zA-Z0-9-_.]{3,20}$#', $_POST['pseudo'])){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pseudo !</div>';
    }
    if(!isset($_POST['mdp']) || iconv_strlen($_POST['mdp']) < 3 || iconv_strlen($_POST['mdp']) > 20){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mot de passe !</div>';
    }
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

    // je dois vérifier que ce pseudo n'est pas déjà reservé par un autre user (et donc déjà stocké en bdd)
    $verifPseudo = $pdo->prepare("SELECT pseudo FROM membre WHERE pseudo = :pseudo");
    $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $verifPseudo->execute();

    // si je le rencontre un fois en bdd, je génère un message d'erreur
    if($verifPseudo->rowCount() == 1){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur, ce pseudo est déjà pris !</div>';
    }

    // je hashe le mot de passe reçu dans le form avec de l'envoyer en bdd
    $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    // si aucun message d'erreur n'a été généré
    if(empty($erreur)){
        // requete préparée d'insertion en bdd avec les bindValue qui suivent et execution de la requete
        $inscrireUser = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse) ");
        $inscrireUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $inscrireUser->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
        $inscrireUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $inscrireUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $inscrireUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $inscrireUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
        $inscrireUser->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $inscrireUser->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
        $inscrireUser->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        $inscrireUser->execute();
        // à la fin de la procédure, je le redirige automatiquement vers la page connexion.php, avec une action dans le get qui génèrera un message
        header('location: connexion.php?action=validate');
    }

}

?>