

<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mot de passe !</div>'; -->

<!-- $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Insertion du produit réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>'; -->

<h1 class="text-center my-5"><div class="badge badge-warning text-wrap p-3">Gestion des produits</div></h1>

<div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
    <p>Gérez ici votre base de données des produits</p>
    <p>Vous pouvez modifier leurs données, ajouter ou supprimer un produit</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<h2 class="pt-5">Formulaire  des produits</h2>


<form id="monForm" class="my-5" method="POST" action=""  enctype="multipart/form-data">



<div class="row mt-5">
    <div class="col-md-4">
    <label class="form-label" for="reference"><div class="badge badge-dark text-wrap">Référence</div></label>
    <input class="form-control" type="text" name="reference" id="reference"  placeholder="Référence">
    </div>

    <div class="col-md-4">
    <label class="form-label" for="categorie"><div class="badge badge-dark text-wrap">Catégorie</div></label>
    <input class="form-control" type="text" name="categorie" id="categorie"  placeholder="Catégorie">
    </div>

    <div class="col-md-4">
    <label class="form-label" for="titre"><div class="badge badge-dark text-wrap">Titre</div></label>
    <input class="form-control" type="text" name="titre" id="titre"  placeholder="Titre">
    </div>
</div>

<div class="row justify-content-around mt-5">
    <div class="col-md-6">
    <label class="form-label" for="description"><div class="badge badge-dark text-wrap">Description</div></label>
    <textarea class="form-control" name="description" id="description" placeholder="Description" rows="5"></textarea>
    </div>
</div>

<div class="row mt-5">

    <div class="col-md-4 mt-3">
        <label class="badge badge-dark text-wrap" for="couleur">Couleur</label>
            <select class="form-control" name="couleur" id="couleur">
                <option value="">Choisissez</option>
                <option class="bg-primary text-light" value="bleu" >Bleu</option>
                <option class="bg-danger text-light" value="rouge" >Rouge</option>
                <option class="bg-success text-light" value="vert" >Vert</option>
                <option class="bg-warning text-light" value="jaune" >Jaune</option>
                <option class="bg-light text-dark" value="blanc" >Blanc</option>
                <option class="bg-dark text-light" value="noir" >Noir</option>
                <option class="text-light" style="background:brown;" value="marron" >Marron</option>
            </select>
    </div>

    <div class="col-md-4">
        <p><div class="badge badge-dark text-wrap">Taille</div></p>

        <input type="radio" name="taille" id="taille1" value="small" >
        <label class="mx-1" for="taille1">Small</label>

        <input type="radio" name="taille" id="taille2" value="medium" >
        <label class="mx-1" for="public2">Medium</label>

        <input type="radio" name="taille" id="taille3" value="large" > 
        <label class="mx-1" for="taille3">Large</label>

        <input type="radio" name="taille" id="taille4" value="xlarge" > 
        <label class="mx-1" for="taille4">XLarge</label>
    </div>

    <div class="col-md-4">
        <p><div class="badge badge-dark text-wrap">Public</div></p>

        <input type="radio" name="public" id="public1" value="enfant" >
        <label class="mx-1" for="public1">Enfant</label>

        <input type="radio" name="public" id="public2" value="femme" >
        <label class="mx-1" for="public2">Femme</label>

        <input type="radio" name="public" id="public3" value="homme" >
        <label class="mx-1" for="public3">Homme</label>

        <input type="radio" name="public" id="public4" value="mixte" > 
        <label class="mx-1" for="public4">Mixte</label>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4">
    <label class="form-label" for="photo"><div class="badge badge-dark text-wrap">Photo</div></label>
    <input class="form-control" type="file" name="photo" id="photo" placeholder="Photo">
    </div>
    <!-- ----------------- -->
        <div class="mt-4">
            <p>Vous pouvez changer d'image
                <img src="" width="50px">
            </p>
        </div>
    <!-- -------------------- -->
    <div class="col-md-4">
    <label class="form-label" for="prix"><div class="badge badge-dark text-wrap">Prix</div></label>
    <input class="form-control" type="text" name="prix" id="prix"  placeholder="Prix">
    </div>

    <div class="col-md-4">
    <label class="form-label" for="stock"><div class="badge badge-dark text-wrap">Stock</div></label>
    <input class="form-control" type="text" name="stock" id="stock"  placeholder="Stock">
    </div>
</div>

<div class="col-md-1 mt-5">
<button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
</div>

</form>

<h2 class="py-5">Nombre de ... en base de données: </h2>

<div class="row justify-content-center py-5">
    <a href=''>
        <button type="button" class="btn btn-sm btn-outline-dark shadow rounded">
        <i class="bi bi-plus-circle-fill"></i> Ajouter un produit
        </button>
    </a>
</div>

<table class="table table-dark text-center">
    <thead>
        <tr>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>

<nav>
  <ul class="pagination justify-content-end">
    <li class="page-item ">
        <a class="page-link text-dark" href="" aria-label="Previous">
            <span aria-hidden="true">précédente</span>
            <span class="sr-only">Previous</span>
        </a>
    </li>
    
        <li class="mx-1 page-item">
            <a class="btn btn-outline-dark " href=""></a>
        </li>
    
    <li class="page-item ">
        <a class="page-link text-dark" href="" aria-label="Next">
            <span aria-hidden="true">suivante</span>
            <span class="sr-only">Next</span>
        </a>
    </li>
  </ul>
</nav>

<!-- <img class="img-fluid" src="" width="50"> -->

<!-- <td><a href=''><i class="bi bi-pen-fill text-warning"></i></a></td>-->
<!-- <td><a data-href="" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td> -->

<!-- modal suppression codepen https://codepen.io/lowpez/pen/rvXbJq -->

<!-- <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer article
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer cet article de votre panier ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div> -->

<!-- modal -->

