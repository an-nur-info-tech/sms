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
      success: function(res) {
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