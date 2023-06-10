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
      success: function(response) {
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
