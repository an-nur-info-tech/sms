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

    const getById = ele => {
      return document.getElementById(ele);
    }
    const getBySelectAll = ele => {
      return document.querySelectorAll(ele);
    }
    
  const add = () => {
    let ca = getById("ca").value;
    let exam = getById("exam").value;
    
    if((ca > 40) || (ca < 0))
    {
      return getById("ca").value = "";  
    }
    if((exam > 60) || (exam < 0))
    {
      return getById("exam").value = "";
    }

    let total_value = parseInt(ca) + parseInt(exam); //Adding C.A with Exam as total
    //let average = total_value/100;
    // var av_reduce = average.toFixed(2);

    if(total_value <= 39 )
    {
      getById("grade").value = "F9";
      getById("remark").value = "Fail";
    }
    if(total_value >= 40 || total_value >= 44)
    {
      getById("grade").value = "E8";
      getById("remark").value = "Pass";
    }
    if(total_value >= 45 || total_value >= 49 )
    {
      getById("grade").value = "D7";
      getById("remark").value = "Pass";
    }
    if(total_value >= 50 || total_value >= 59)
    {
      getById("grade").value = "C6";
      getById("remark").value = "Credit";
    }
    if(total_value >= 60 || total_value >= 64 )
    {
      getById("grade").value = "C5";
      getById("remark").value = "Credit";
    }
    if(total_value >= 65 || total_value >= 69 )
    {
      getById("grade").value = "C4";
      getById("remark").value = "Credit";
    }
    if(total_value >= 70 || total_value >= 74 )
    {
      getById("grade").value = "B3";
      getById("remark").value = "Good";
    }
    if(total_value >= 75 || total_value >= 79 )
    {
      getById("grade").value = "B2";
      getById("remark").value = "Good";
    }
    if(total_value >= 80 || total_value >= 100 )
    {
      getById("grade").value = "A1";
      getById("remark").value = "Excellent";
    }
        
    if(!isNaN(total_value))
    {
      getById("total").value = total_value;
      // getById("average").value = av_reduce;         
    }
  }
  const adds = () => {
    // let ca = getById("ca").value;
    // let exam = getById("exam").value;
    let ca = getBySelectAll(".ca").value;
    let exam = getBySelectAll(".exam").value;
    
    for(let i = 0; i < ca.length; i++)
    {
      if((ca[i] > 40) || (ca[i] < 0))
      {
        return ca[i].value = "";  
      }
      if((exam[i] > 60) || (exam[i] < 0))
      {
        return exam[i].value = "";
      }
    }

    let total_value = parseInt(ca) + parseInt(exam); //Adding C.A with Exam as total
    //let average = total_value/100;
    // var av_reduce = average.toFixed(2);

    if(total_value <= 39 )
    {
      getById("grade").value = "F9";
      getById("remark").value = "Fail";
    }
    if(total_value >= 40 || total_value >= 44)
    {
      getById("grade").value = "E8";
      getById("remark").value = "Pass";
    }
    if(total_value >= 45 || total_value >= 49 )
    {
      getById("grade").value = "D7";
      getById("remark").value = "Pass";
    }
    if(total_value >= 50 || total_value >= 59)
    {
      getById("grade").value = "C6";
      getById("remark").value = "Credit";
    }
    if(total_value >= 60 || total_value >= 64 )
    {
      getById("grade").value = "C5";
      getById("remark").value = "Credit";
    }
    if(total_value >= 65 || total_value >= 69 )
    {
      getById("grade").value = "C4";
      getById("remark").value = "Credit";
    }
    if(total_value >= 70 || total_value >= 74 )
    {
      getById("grade").value = "B3";
      getById("remark").value = "Good";
    }
    if(total_value >= 75 || total_value >= 79 )
    {
      getById("grade").value = "B2";
      getById("remark").value = "Good";
    }
    if(total_value >= 80 || total_value >= 100 )
    {
      getById("grade").value = "A1";
      getById("remark").value = "Excellent";
    }
        
    if(!isNaN(total_value))
    {
      getById("total").value = total_value;
      // getById("average").value = av_reduce;         
    }
  }

  
  const checkAllSelect = () => {
    let check_All = document.getElementById("check_All");
    let checkB = document.querySelectorAll("#checkB");
    
    //var ele=document.getElementsByName('chk');  
    if(check_All.checked = true)
    {
      for(let i=0; i<checkB.length; i++){  
        // if(checkB[i].type=='checkbox')  
        // checkB[i].checked=true;
        checkB[i].checked = true;
        checkB[i].setAttribute('required', '');  
      }
    }else if(check_All.checked = false)
    {
      for(let i=0; i<checkB.length; i++){  
        // if(checkB[i].type=='checkbox')  
        // checkB[i].checked=true;
        checkB[i].checked = false;
        checkB[i].removeAttribute('required');  
      }
    }
    

    /* for(let i=0; i<checkB.length; i++){  
        // if(checkB[i].type=='checkbox')  
        // checkB[i].checked=true;
        checkB[i].checked = true;  
      } */
    /* if(checkbox.checked){
      checkB.innerText = "Y";
      // for(let i=0; i<checkB.length; i++){  
      //   // if(checkB[i].type=='checkbox')  
      //   checkB[i].checked=true;  
      // }
      // for (let i=0; i < checkB.length; i++)
      // {
      //   checkB[i].checked;
      // }
      //checkB.checked;
    }else {
      console.log("Unchecked")
    } */
    // console.log("Checked");

    // check_all.addEventListener.o
  }
  //check_all();
</script>
</body>

</html>


