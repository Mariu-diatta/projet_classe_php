</div>
    </div>
    <!-- /#page-content-wrapper -->

</div>
  <!-- /#wrapper -->

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>


<!-- Menu Toggle Script -->
<script>
  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
</script>

<script>
  $('.mydatatable').DataTable();
</script>

<!-- script pour popup de confirmation, à la place du on-click -->
<script>
    $(document).ready(function() {
    $('a[data-confirm]').click(function(ev) {
        var href = $(this).attr('href');

        if (!$('#dataConfirmModal').length) {
            $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Please Confirm</h3></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button><a class="btn btn-primary" id="dataConfirmOK">OK</a></div></div>');
        } 
        $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
        $('#dataConfirmOK').attr('href', href);
        $('#dataConfirmModal').modal({show:true});
        return false;
    });
});

$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

/*POST

// Bind click to OK button within popup
$('#confirm-delete').on('click', '.btn-ok', function(e) {

  var $modalDiv = $(e.delegateTarget);
  var id = $(this).data('recordId');

  $modalDiv.addClass('loading');
  $.post('/api/record/' + id).then(function() {
     $modalDiv.modal('hide').removeClass('loading');
  });
});

// Bind to modal opening to set necessary data properties to be used to make request
$('#confirm-delete').on('show.bs.modal', function(e) {
  var data = $(e.relatedTarget).data();
  $('.title', this).text(data.recordTitle);
  $('.btn-ok', this).data('recordId', data.recordId);
});

//OLD



*/

</script>

<!-- script modal infos commandes -->
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModalCommand').modal('show');
    });
</script>
<!-- modal -->

<!-- script modal infos détails commandes -->
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModalDetailsCommand').modal('show');
    });
</script>
<!-- modal -->

<!-- script modal infos users -->
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModalUsers').modal('show');
    });
</script>
<!-- modal -->

</body>

</html>
