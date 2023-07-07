CREATE DATABASE IF NOT EXISTS  boutique ;
use boutique;

create  table IF NOT EXISTS salle(
    id_salle int(3),
    titre varchar(200),
    descriptionn TEXT,
    photo varchar(200),
    pays  varchar(20),
    ville varchar(20),
    adresse varchar(50),
    cp int(5),
    capacite int(3),
    categorie ENUM('réunion', 'bureau', 'formation'),
    primary key  (id_salle)
)ENGINE=INNODB;

create table IF NOT EXISTS  produit(
    id_produit int(3),
    id_salle int(3),
    date_arrivee datetime,
    date_depart datetime,
    public TEXT,
    prix int(3),
    etat ENUM('libre', 'reservation'),
    categorie ENUM('réunion', 'bureau', 'formation'),
    primary key (id_produit),
    foreign key (id_salle) references salle(id_salle)
)ENGINE=INNODB;

create table IF NOT EXISTS membre(
    id_membre int(3),
    pseudo varchar(20),
    mbp varchar(60),
    nom varchar(20),
    prenom varchar(20),
    email varchar(50),
    civilite TEXT,
    ville TEXT,
    code_postal TEXT,
    adresse TEXT,
    date_enregistrement datetime,
    primary key  (id_membre)
)ENGINE=INNODB;

create table IF NOT EXISTS commande(
    id_commande int(3),
    id_membre int(3),
    id_produit int(3),
    date_enregistrement datetime,
    primary key (id_commande),
    foreign key (id_membre) references membre(id_membre),
    foreign key (id_produit) references produit(id_produit)
)ENGINE=INNODB;

create table IF NOT EXISTS avis(
    id_avis int(3),
    id_membre int(3),
    id_salle int(3),
    id_commentaire TEXT,
    note int(2),
    date_enregistrement datetime,
    primary key (id_avis)
)


