CREATE DATABASE IF NOT EXISTS  boutique ;
use boutique;

create  table IF NOT EXISTS salle(
    id_salle int(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titre varchar(200),
    descriptionn TEXT,
    photo varchar(200),
    pays  varchar(20),
    ville varchar(20),
    adresse varchar(50),
    cp int(5),
    capacite int(3),
    categorie ENUM('réunion', 'bureau', 'formation')
)ENGINE=INNODB;

create table IF NOT EXISTS  produit(
    id_produit int(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_salle int(3),
    date_arrivee DATE DEFAULT (CURRENT_DATE),
    date_depart datetime,
    public TEXT,
    produit_description TEXT,
    prix int(3),
    taille int(3),
    couleur varchar(),
    content BLOB not null,
    etat ENUM('libre', 'reservation'),
    categorie ENUM('réunion', 'bureau', 'formation'),
    foreign key (id_salle) references salle(id_salle)
)ENGINE=INNODB;

create table IF NOT EXISTS membre(
    id_membre int(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pseudo varchar(20),
    mbp varchar(60),
    nom varchar(20),
    prenom varchar(20),
    email varchar(50),
    civilite TEXT,
    ville TEXT,
    code_postal TEXT,
    adresse TEXT,
    statut int(1) DEFAULT 0,
    date_enregistrement DATE DEFAULT (CURRENT_DATE)
)ENGINE=INNODB;

create table IF NOT EXISTS commande(
    id_commande int(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_membre int(3),
    id_produit int(3),
    date_enregistrement datetime,
    foreign key (id_membre) references membre(id_membre),
    foreign key (id_produit) references produit(id_produit)
)ENGINE=INNODB;

create table IF NOT EXISTS avis(
    id_avis int(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_membre int(3),
    id_salle int(3),
    id_commentaire TEXT,
    note int(2),
    date_enregistrement datetime 
)


