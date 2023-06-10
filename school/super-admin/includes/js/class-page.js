
/*  ASSIGN CLASS TEACHER MODAL TODO*/
$(".assign_btn").click(function (e) {
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
        success: function (response) {
            $("#teacher_id").html(response);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
        }
    })
});
/*-------x---- ASSIGN CLASS TEACHER MODAL -------x----*/

/*   CLASS EDIT MODAL */
$(".editClass").click(function () {
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
        success: function (response) {
            //console.log("Response", response.class_name);
            // let result = JSON.parse(response);
            $("#editClassName").val(response["class_name"]);
            $("#editClassID").val(response["class_id"]);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
        }
    })
});
/*-------x---- CLASS EDIT MODAL -------x----*/

/*   REMOVE CLASS TEACHER MODAL */
$(".rmAssign_btn").click(function () {
    let rmAssignClassID = $(this).attr("rmAssignClassID");
    // console.log(rmAssignClassID);
    $("#rmAssignClassID").val(rmAssignClassID);
});
/*-------x---- REMOVE CLASS TEACHER MODAL -------x----*/

/*   CLASS DELETE MODAL */
$(".deleteClassID").click(function () {
    let deleteClassID = $(this).attr("deleteClassID");
    //console.log(deleteClassID);
    $("#deleteClassID").val(deleteClassID);
});
          /*-------x---- CLASS DELETE MODAL -------x----*/
