/*   CHANGE CLASS SUBJECT INSTRUCTOR MODAL */
$(".change_instructor").click(function() {
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
      success: function(response) {
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