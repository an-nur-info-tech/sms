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
  const getById = ele => {
    return document.getElementById(ele);
  }
  const getBySelectAll = ele => {
    return document.querySelectorAll(ele);
  }

  var loadFile = function(event)
  {
    var image = document.getElementById('image');
    image.src = URL.createObjectURL(event.target.files[0]);
    image.onload = function(){
      URL.revokeObjectURL(image.src)
    }
  };

  const select_Section = () => {
    let select_section = getById("select_section").value;
    // console.log(select_section.value);
    let datas = new FormData();
    datas.append("select_section", select_section);
    
    $.ajax({
      url: "ajax/editClass.ajax.php",
      method: "POST",
      data: datas,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "html",
      success: function(res){
        console.log("Response", res);
        $("#class_name").html(res);
        // let result = JSON.parse(response);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(XMLHttpRequest);
      console.log(textStatus);
      console.log(errorThrown);
      }
    });
    
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
    let check_All = getById("check_All");
    let checkB = getBySelectAll("#checkB");
    let submitBtn = document.querySelector("#submitBtn");

    if(check_All.checked == true)
    {
      for(let i=0; i<checkB.length; i++){  
        checkB[i].checked = true;
        checkB[i].setAttribute('required', '');  
      }
      submitBtn.removeAttribute('disabled');      
    }

    if(check_All.checked == false)
    {
      for(let i=0; i<checkB.length; i++){  
        checkB[i].checked = false;
        checkB[i].removeAttribute('required');
      }
      submitBtn.setAttribute('disabled', '');
    }
  }
  const checkSingleSelect = () => {
    let checkB = getBySelectAll("#checkB");
    let submitBtn = document.querySelector("#submitBtn");
    let caCheck = getBySelectAll("caCheck");
    let examCheck = getBySelectAll("examCheck")

    for(let j = 0; j < checkB.length; j++)
    {
      if(checkB[j].checked == true)
      {
        checkB[j].setAttribute('required', '');
      }
      if(checkB[j].checked == false)
      {
        checkB[j].removeAttribute('required');
      }
    }
  }
  //check_all();

/*  ASSIGN CLASS TEACHER MODAL TODO*/
$(".assign_btn").click(function(e) {
  e.preventDefault();
  let assignClassID = $(this).attr("assignClassID");
  // console.log(assignClassID);
  $("#assignClassID").val(assignClassID);
  let datas = new FormData();
  datas.append("assignClassID", assignClassID);

  $.ajax({
    url: "ajax/editClass.ajax.php",
    method: "POST",
    data: datas,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "html",
    success: function(response){
      // console.log("Response", response[0]["staff_id"]);
      // let result = JSON.parse(response);
      // $("#teacher_id").val(response["staff_id"]);
      // for(let i = 0; i < response.length; i++)
      // {
      //   let staff_id = response[i]["staff_id"];
      //   let name = response[i]["fname"] +" "+ response[i]["sname"] + " "+ response[i]["oname"];
      //   // $("#teacher_id").append("<option>"+`${response[i]['staff_id']}`+"</option>");
      //   $("#teacher_id").append('<option value = "'+ `${staff_id}` +'">'+ `${name}` + '</option>');
        $("#teacher_id").html(response);
      //   // console.log(response[i]);
      // }
      // $("#assignClassID").val(response["staff_id"]);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
     console.log(XMLHttpRequest);
     console.log(textStatus);
     console.log(errorThrown);
  }
  })
});
/*-------x---- ASSIGN CLASS TEACHER MODAL -------x----*/

/*   CLASS EDIT MODAL */
$(".editClass").click(function() {
  let editClassID = $(this).attr("editClassID");
  // console.log(editClassID);
  let datas = new FormData();
  datas.append("editClassID", editClassID);

  
  $.ajax({
    url: "ajax/editClass.ajax.php",
    method: "POST",
    data: datas,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function(response){
      //console.log("Response", response.class_name);
      // let result = JSON.parse(response);
      $("#editClassName").val(response["class_name"]);
      $("#editClassID").val(response["class_id"]);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
     console.log(XMLHttpRequest);
     console.log(textStatus);
     console.log(errorThrown);
  }
  })
});
/*-------x---- CLASS EDIT MODAL -------x----*/

/*   REMOVE CLASS TEACHER MODAL */
$(".rmAssign_btn").click(function() {
  let rmAssignClassID = $(this).attr("rmAssignClassID");
  // console.log(rmAssignClassID);
  $("#rmAssignClassID").val(rmAssignClassID);
});
/*-------x---- REMOVE CLASS TEACHER MODAL -------x----*/

/*   CLASS DELETE MODAL */
$(".deleteClassID").click(function() {
  let deleteClassID = $(this).attr("deleteClassID");
  //console.log(deleteClassID);
  $("#deleteClassID").val(deleteClassID);
});
/*-------x---- CLASS DELETE MODAL -------x----*/

  
</script>
</body>

</html>


