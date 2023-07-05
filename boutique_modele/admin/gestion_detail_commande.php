<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format id membre !</div>'; -->

<!-- $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Insertion du produit réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>'; -->

<h1 class="text-center my-5"><div class="badge badge-warning text-wrap p-3">Gestion détail des commandes</div></h1>

<!-- <div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
    <p>Visualisez ici votre base de données des détails de commande</p>
    <p>Aucune action n'est possible, ses données étant reliées a d'autres, cela entrainerait des dysfonctionnements</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div> -->

<!-- <h2 class="my-2">Formulaire  des commandes</h2>

<form method="POST"action="">

    <div class="col-md-3 mt-5">
    <label class="form-label" for="id_commande"><div class="badge badge-dark text-wrap">Id Commande</div></label>
    <input type="text" class="form-control" name="id_commande" id="id_commande" placeholder="Id de la commande">
    </div>

    <div class="col-md-3 mt-5">
    <label class="form-label" for="id_produit"><div class="badge badge-dark text-wrap">Id Produit</div></label>
    <input type="text" class="form-control" name="id_produit" id="id_produit" placeholder="Id du produit">
    </div>

    <div class="col-md-3 mt-5">
    <label class="form-label" for="quantite"><div class="badge badge-dark text-wrap">Quantité</div></label>
    <input type="text" class="form-control" name="quantite" id="quantite" placeholder="Quantité">
    </div>

    <div class="col-md-3 mt-5">
    <label class="form-label" for="prix"><div class="badge badge-dark text-wrap">Prix</div></label>
    <input type="text" class="form-control" name="prix" id="prix" placeholder="Prix">
    </div>

    <div class="col-md-1 mt-5">
    <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
    </div>

</form> -->

<h2 class="py-5">Nombre de ... en base de données: </h2>

<div class="row justify-content-center py-5">
    <a href="">
        <button type="button" class="btn btn-sm btn-outline-dark btn-warning text-dark">
            <i class="bi bi-plus-circle-fill text-dark"></i> Ajouter une commande
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

<!-- <td><a href=''><i class="bi bi-pen-fill text-warning"></i></a></td>-->
<!-- <td><a data-href="" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td> -->

<!-- modal suppression codepen https://codepen.io/lowpez/pen/rvXbJq -->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
</div>

<!-- modal -->

<!-- modal infos -->
<div class="modal fade" id="myModalDetailsCommand" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-warning" id="exampleModalLabel">Gestion des détails des commandes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Visualisez ici votre base de données des détails de commande</p>
        <p>Aucune action n'est possible, ses données étant reliées a d'autres, cela entrainerait des dysfonctionnements</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-warning text-dark" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- modal -->