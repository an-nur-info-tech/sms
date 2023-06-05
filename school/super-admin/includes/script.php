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
  const getBySelector = ele => {
    return document.querySelector(ele);
  }
  const getBySelectAll = ele => {
    return document.querySelectorAll(ele);
  }

  const add_spinner = () => {
    let spinner_btn = getBySelector(".spinner_btn");
    let span = document.createElement("span");
    
    span.classList.add("spinner-border");
    span.classList.add("spinner-border-sm");
    span.setAttribute('role', 'status');
    span.setAttribute('aria-hidden', 'true');
    spinner_btn.innerHTML = " ";
    spinner_btn.appendChild(span);
    // spinner_btn.setAttribute('disabled', '');
  }

  var loadFile = function(event)
  {
    var image = document.getElementById('image');
    image.src = URL.createObjectURL(event.target.files[0]);
    image.onload = function(){
      URL.revokeObjectURL(image.src)
    }
  };

  // Select section on student reg page
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
        // console.log("Response", res);
        $("#class_id").html(res);
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
    let ca = getBySelectAll("#caCheck").value;
    let exam = getBySelectAll("#examCheck").value;
    let checkB = getBySelectAll("#checkB");

    
    // for(let i = 0; i < ca.length; i++)
    // {
      // if((ca > 40) || (ca < 0))
      // {
      //   return ca.value = "";  
      // }
      // if((exam > 60) || (exam < 0))
      // {
      //   return exam.value = "";
      // }
    // }

    //let total_value = parseInt(ca) + parseInt(exam); //Adding C.A with Exam as total
    //let average = total_value/100;
    // var av_reduce = average.toFixed(2);

    /* if(total_value <= 39 )
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
    } */
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
    // let submitBtn = document.querySelector("#submitBtn");
    // let caCheck = getBySelectAll("#caCheck").value;
    // let examCheck = getBySelectAll("#examCheck").value;
    /* checkB.forEach( data =>
    {
      
      if (data.checked === true)
      {
        data.value = 1;
      } 
      if (data.checked === false)
      {
        data.value = "";
      }

    }); */
    /* checkB.forEach(function(){
      console.log(this.length);
    }); */
    /* for (let i = 0; i < checkB.length; i++)
    {
      if (checkB[i].checked == true)
      {
        console.log(checkB[i].value);
        console.log(caCheck);
        console.log(examCheck);
      }
      if (checkB[i].checked == false)
      {
        console.log(checkB[i].value);
      }
    } */

    /* for(let j = 0; j < checkB.length; j++)
    {
      if(checkB[j].checked == true)
      {
        checkB[j].setAttribute('required', '');
      }
      if(checkB[j].checked == false)
      {
        checkB[j].removeAttribute('required');
      }
    } */
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
      $("#teacher_id").html(response);
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

/*   CHECK SUBJECT ON ADD RESULT PAGE and EXCEL UPLOAD PAGE*/
const checkSubject = () => {
  let class_id = $("#class_id").val(); // Get class id
  // console.log(class_id)
  let datas = new FormData();
  datas.append("class_id", class_id);

  $.ajax({
    url: "ajax/editClass.ajax.php",
    method: "POST",
    data: datas,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "html",
    success: function(response){
      //console.log("Response", response.class_name);
      // let result = JSON.parse(response);
      $("#subject_id").html(response);
      // $("#editClassID").val(response["class_id"]);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
     console.log(XMLHttpRequest);
     console.log(textStatus);
     console.log(errorThrown);
  }
  });
}
/*-------x---- CLASS DELETE MODAL -------x----*/

/*   CHECK PASSWORD STRENGTH ON PROFILE SETTINGS*/
const check_Password_stregth = () => {
  let password = getById("password").value;
  let confirm_password = getById("confirm_password").value;
  let passwordBtn = getById("passwordBtn");

  if ((password.length > 8) && (confirm_password.length > 8) && (password.match(/[a-zA-Z][0-9]/g))) // TODO RegEx
  {
    passwordBtn.removeAttribute('disabled');
  }
  else 
  {
    passwordBtn.setAttribute('disabled', '');
  }
  
}
/*-------x---- CHECK PASSWORD STRENGTH ON PROFILE SETTINGS -------x----*/

/*   CHANGE CLASS SUBJECT INSTRUCTOR MODAL */
$(".change_instructor").click(function(){
  let subject_id = $(this).attr("subject_id");
  let class_id = $(this).attr("class_id");
  // console.log(subject_id);
  let dat = new FormData();
  dat.append("subject_id", subject_id);

  $.ajax({
    url: "ajax/change-teacher.ajax.php",
    method: "POST",
    data: dat,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "html",
    success: function(response){
      $("#staff_id").html(response);
      $("#class_id").val(class_id);
      $("#subject_id").val(subject_id);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
     console.log(XMLHttpRequest);
     console.log(textStatus);
     console.log(errorThrown);
  }
  })
});
/*-------x---- CHANGE CLASS SUBJECT INSTRUCTOR MODA -------x----*/

/*   CLASS SUBJECT DELETE MODAL */
$(".deleteClassSubjectID").click(function() {
  let deleteSubjectID = $(this).attr("deleteSubjectID");
  let class_id = $(this).attr("class_id");
  //console.log(deleteSubjectID);
  $("#deleteSubjectID").val(deleteSubjectID);
  $("#class_ID").val(class_id);
});
/*-------x---- CLASS SUBJECT DELETE MODAL -------x----*/

/*   DELETE SUBJECT MODAL */
$(".subjectDelBtn").click(function() {
  let subject_id = $(this).attr("subjectDelID");
  $("#deleteSubjectID").val(subject_id);
});
/*-------x---- DELETE SUBJECT MODALL -------x----*/


/*   SUBJECT EDIT MODAL */
$(".subjectEditBtn").click(function() {
  let subject_id = $(this).attr("subjectEditID");
  // console.log(editClassID);
  let datas = new FormData();
  datas.append("subject_id", subject_id);

  
  $.ajax({
    url: "ajax/subject.ajax.php",
    method: "POST",
    data: datas,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function(response){
      $("#editSubjectName").val(response["subject_name"]);
      $("#editSubjectID").val(response["subject_id"]);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
     console.log(XMLHttpRequest);
     console.log(textStatus);
     console.log(errorThrown);
  }
  })
});
/*-------x---- SUBJECT EDIT MODAL -------x----*/

const enable_staff_btn = (args) => {
  if (args.length > 2)
  {
    $(".spinner_btn").removeAttr('disabled');
  }else{
    document.querySelector(".spinner_btn").setAttribute('disabled', '');
  }
}

/* RESEND ACTIVATION LINK */
$(".email_link").click(function(){
  let currentEmail = $(this).attr("email_link");
  let staff_id_lnk = $(this).attr("staff_lnk");

  $("#link2change").val(currentEmail);
  $("#email_link_txt").val(currentEmail);
  $("#staff_id_lnk").val(staff_id_lnk);

});
/* XX RESEND ACTIVATION LINK XX */
</script>
</body>

</html>


