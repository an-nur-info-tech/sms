/* EDIT YEAR SESSION DATE */
$(".date_edit").click(function() {
    let date_edit = $(this).attr("date_edit");
    let g_begin_date = $(this).attr("g_begin_date");
    let g_end_date = $(this).attr("g_end_date");
    let g_next_date = $(this).attr("g_next_date");

    $("#date_edit_id").val(date_edit);
    // console.log(g_end_date);
    $("#begin_date").val(g_begin_date);
    $("#end_date").val(g_end_date);
    $("#next_term_date").val(g_next_date);
  });
  /* XX EDIT YEAR SESSION DATE */