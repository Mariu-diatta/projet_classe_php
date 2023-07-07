<?php
// connexion vers la bdd
$pdo = new PDO('mysql:host=localhost;dbname=boutique', "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// initialisation de session_start
session_start();

// ce chemin ne sera bon que si le dossier est placé directement dans htdocs et pas dans un sous-dossier
define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] . '/boutique/');
// la constante RACINE_SITE ne me servira qu'une fois. Dans le fichier gestion_produit.php pour uploader les images dans le dossier img. J'aurais pu donc la coder dans ce même fichier. C'est surtout la constante URL que j'appelerais souvent, dans mes <a href> comme pour mes <img>

// ce chemin ne sera bon que si le dossier est placé directement dans htdocs et pas dans un sous-dossier
define('URL', "http://localhost/boutique/");
// Ci dessous, une constante pour l'url qui va servir pour l'upload de fichiers en bdd. Elle doit etre vide. Si je lui donne un chemin comme ci dessus, alors je retrouverais ce path en bdd, additionné au nom du fichier. Et cela posera de gros soucis lorsque le site sera hebergé, car le serveur ne reconnaitra pas localhost
// define('URL_UPLOAD', '');

// initialisation de diverses variables
$erreur = "";
$erreur_index = "";
$validate = "";
$validate_index = "";
$content = "";

// boucle foreach pour protéger avec htmlspecialchars et trim tous les transit de données via formulaires
foreach($_POST as $key => $value){
    $_POST[$key] = htmlspecialchars(trim($value));
}

// boucle foreach pour protéger avec htmlspecialchars et trim tous les transit de données via l'url
foreach($_GET as $key => $value){
    $_GET[$key] = htmlspecialchars(trim($value));
}

// je récupère ici tout ce que contient fonction.php
require_once('fonctions.php');