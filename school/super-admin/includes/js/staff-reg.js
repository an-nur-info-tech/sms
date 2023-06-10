const enable_staff_btn = (args) => {
    // let  lga = getBySelector("#lga");
    // let gsm1 = getBySelector("#gsm1");
    if (args.length > 2) {
        $(".spinner_btn").removeAttr('disabled');
    } else {
        getBySelector(".spinner_btn").setAttribute('disabled', '');
    }
}

/* RESEND ACTIVATION LINK */
$(".email_link").click(function () {
    let currentEmail = $(this).attr("email_link");
    let staff_id_lnk = $(this).attr("staff_lnk");

    $("#link2change").val(currentEmail);
    $("#email_link_txt").val(currentEmail);
    $("#staff_id_lnk").val(staff_id_lnk);

});
/* XX RESEND ACTIVATION LINK XX */

/* Send user mail */
$(".send_user_mail").click(function () {
    let user_mail = $(this).attr("user_mail");

    $("#user_mail").val(user_mail);
    $("#mail_To").val(user_mail);
});
  /* XX Send user mail XX */

  /* Staff Edit */
$(".edit_staff_mail").click(function () {
    let edit_staff_name = $(this).attr("edit_staff_name");
    let edit_staff_mail = $(this).attr("edit_staff_mail");
    let edit_staff_section = $(this).attr("edit_staff_section");
    let edit_staff_role = $(this).attr("edit_staff_role");
    let edit_staff_id = $(this).attr("edit_staff_id");

    $("#staff_name").val(edit_staff_name);
    $("#staff_email").val(edit_staff_mail);
    $("#staff_section").val(edit_staff_section);
    $("#staff_role").val(edit_staff_role);
    $("#edit_staff_id").val(edit_staff_id);
});
  /* XX Staff Edit XX */

  /* Staff View */
$(".view_staff_id").click(function () {
    let view_staff_id = $(this).attr("view_staff_id");
    
    let datas = new FormData();
    datas.append("view_staff_id", view_staff_id);

    $.ajax({
        url: "ajax/staff-reg.ajax.php",
        method: "post",
        data: datas,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(res) {
        let names = res["fname"] +" " + res["sname"] + " " + res["oname"];
        let contacts = res["gsm1"] + ", " + res["gsm2"];

        $("#staff_view_name").val(names);
        $("#staff_view_state").val(res["staff_state"]);
        $("#staff_view_lga").val(res["lga"]);
        $("#staff_view_gender").val(res["gender"]);
        $("#staff_view_dob").val(res["dob"]);
        $("#staff_view_gsm").val(contacts);
        $("#staff_view_registerred").val(res["date_reg"]);
        $("#staff_view_appointment_date").val(res["year_joined"]);

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});
  /* XX Staff View XX */

/* Staff Delete */
$(".delete_staff_id").click(function () {
    let staff_id = $(this).attr("delete_staff_id");

    $("#staff_ID").val(staff_id);
});
  /* XX Staff Edit XX */