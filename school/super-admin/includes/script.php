        </div>
        <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Click "Logout" below if you are ready to leave otherwise cancel.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <form method="POST" action="logout">
          <button class="btn btn-primary" name="logout_btn" type="submit">logout</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- **********************************START -->

<!-- Bootstrap core JavaScript-->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/demo/datatables-demo.js"></script>

<!-- Core plugin JavaScript-->
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../assets/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- END ############################################# -->

  <!-- Custom script -->
  <script type="text/javascript">
  var loadFile = function(event)
  {
    var image = document.getElementById('image');
    image.src = URL.createObjectURL(event.target.files[0]);
    image.onload = function(){
      URL.revokeObjectURL(image.src)
    }
  };

    /* Swal.fire({
      title: "success",
      text: "Good",
      icon: "success",
      showConfirmButton: true,
      confirmButtonText: "ok"
    }); */
</script>
</body>

</html>


