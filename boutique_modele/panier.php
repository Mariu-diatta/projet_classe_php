
<!-- $content .= '<div class="alert alert-danger" role="alert">La quantité du produit <strong>'  '</strong> a été diminuée. Vérifiez votre nouveau panier !</div>'; -->

<!-- $content .= '<div class="alert alert-danger" role="alert">Le produit <strong>'  '</strong> a été retiré de votre panier car il est désormais en rupture de stock. Navré !</div>'; -->

<h2 class='text-center my-5'>
    <!-- -----Selon que la session membre existe ou pas, pour le pseudo----- -->
    <div class="badge badge-dark text-wrap p-3">Panier</div>
    <!-- ------ -->

</h2>

<!--Section: Block Content-->
<section>

  <!--Grid row-->
  <div class="row mt-5">
  <!-- -----if panier existe ----- -->
    <!--Grid column-->
    <div class="col-lg-8">

      <!-- Card -->
      <div class="mb-3 shadow p-3 mb-5 bg-white rounded">
        <div class="pt-4 wish-list">

          <h5 class="mb-4"><div class="badge badge-dark text-wrap p-3">Détail de votre Panier</div></h5>
          <!-- début de boucle -->
          
          <div class="row mb-4">
            <div class="col-md-5 col-lg-3 col-xl-3">
              <div class="view zoom overlay z-depth-1 rounded mb-3 mb-md-0">
                <img class="img-fluid w-100"
                  src="" >
              </div>
            </div>
            <div class="col-md-7 col-lg-9 col-xl-9">
              <div>
                <div class="d-flex justify-content-between">
                  <div>
                    <h5></h5>
                    <p class="mb-3 text-muted text-uppercase small"></p>
                    <p class="mb-2 text-muted text-uppercase small">Quantité commandée: </p>
                    <form method="POST" action="">
                      <input type="hidden" name="id_produit" value="">
                      <button type="submit" class="btn btn-sm btn-outline-success" name="retirer"><i class="bi bi-dash"></i></button>
                        <input class="btn btn-sm bg-success text-light col-md-2" min="1" name="quantite" value="1" type="text">
                        <button type="submit" class="btn btn-sm btn-outline-success" name="ajouter"><i class="bi bi-plus"></i></button>
                    </form>
                    <p class="mt-3 text-muted text-uppercase small">Prix unitaire:  €</p>
                  </div>
                  <div>
                    <p><a  data-href="" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 2rem;"></i></a> Retirer article</p>
                  </div>
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
                </div>
                <div class="d-flex justify-content-end">
                <p class="mb-0">Montant pour cet article:  €</p class="mb-0">
                </div>
              </div>
            </div>
          </div>
          <hr class="mb-4">
          
          <!-- fin de boucle -->
          

        </div>
      </div>
      <!-- Card -->

    </div>
    <!--Grid column-->

    <!--Grid column-->
    <div class="col-lg-4">

      <!-- Card -->
      <div class="mb-3 shadow p-3 mb-5 bg-white rounded">
        <div class="pt-4">

        <h5 class="mb-5 text-right"><div class="badge badge-dark text-wrap p-3"> article(s) dans votre Panier</div></h5>

          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-end border-0">
              <p class='badge badge-success text-wrap p-3'>
              Montant total du panier: <strong><u> €</u></strong>
              </p>
            </li>
            <li class="list-group-item d-flex justify-content-end border-0">
              <p class="ml-auto">Je veux encore <a href="">acheter</a></p>
            </li>
            <li class="list-group-item d-flex justify-content-end border-0">
              <!-- -------- -->
              <p class="ml-auto">Vous devez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php?action=acheter">connecter</a> pour procéder au paiement</p>
              <!-- --------- -->
              <form method="POST" action="" class="ml-auto">
                  <button type='submit' class='btn btn-success' name='payer'>Valider Panier</button>
              </form>
              <!-- --------- -->
            </li>
          </ul>
        </div>
      </div>
      <!-- Card -->

    </div>
    <!--Grid column-->
  <!-- ------------ -->
    <div class="col-lg-4 offset-md-4">

      <!-- Card -->
      <div class="mb-3 shadow p-3 mb-5 bg-white rounded">
        <div class="pt-4">

        <h5 class="mb-5 text-center"><div class="badge badge-dark text-wrap p-3">Votre panier est actuellement vide.</div></h5>

          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex border-0">
              <p>Je veux <a href="">acheter</a></p>
            </li>
            <li class="list-group-item d-flex justify-content-end border-0">
              <!-- -------- -->
              <p class="ml-auto">Vous devez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php?action=acheter">connecter</a> pour procéder au paiement</p>
              <!-- --------- -->
            </li>
          </ul>
        </div>
      </div>
      <!-- Card -->

    </div>
  <!-- --------- -->
  </div>
  <!-- Grid row -->

</section>
<!--Section: Block Content-->

<!-- fin de panier -->
