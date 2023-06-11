// Add CA and EXAM to get Total, Grade and Remarks on single student
const add = () => {
    let ca = getById("ca").value;
    let exam = getById("exam").value;

    if ((ca > 40) || (ca < 0)) {
        return getById("ca").value = "";
    }
    if ((exam > 60) || (exam < 0)) {
        return getById("exam").value = "";
    }

    let total_value = parseInt(ca) + parseInt(exam); //Adding C.A with Exam as total
    //let average = total_value/100;
    // var av_reduce = average.toFixed(2);

    if (total_value <= 39) {
        getById("grade").value = "F9";
        getById("remark").value = "Fail";
    }
    if (total_value >= 40 || total_value >= 44) {
        getById("grade").value = "E8";
        getById("remark").value = "Pass";
    }
    if (total_value >= 45 || total_value >= 49) {
        getById("grade").value = "D7";
        getById("remark").value = "Pass";
    }
    if (total_value >= 50 || total_value >= 59) {
        getById("grade").value = "C6";
        getById("remark").value = "Credit";
    }
    if (total_value >= 60 || total_value >= 64) {
        getById("grade").value = "C5";
        getById("remark").value = "Credit";
    }
    if (total_value >= 65 || total_value >= 69) {
        getById("grade").value = "C4";
        getById("remark").value = "Credit";
    }
    if (total_value >= 70 || total_value >= 74) {
        getById("grade").value = "B3";
        getById("remark").value = "Good";
    }
    if (total_value >= 75 || total_value >= 79) {
        getById("grade").value = "B2";
        getById("remark").value = "Good";
    }
    if (total_value >= 80 || total_value >= 100) {
        getById("grade").value = "A1";
        getById("remark").value = "Excellent";
    }

    if (!isNaN(total_value)) {
        getById("total").value = total_value;
        // getById("average").value = av_reduce;         
    }
}
// Add CA and EXAM to get Total, Grade and Remarks in Bulks(All student)
const adds = () => {
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

// Check if checkbox is checked to enable/disable the CA and EXAM input box in Bulks uploads
const checkAllSelect = () => {
    let check_All = getById("check_All");
    let checkB = getBySelectAll("#checkB");
    let submitBtn = document.querySelector("#submitBtn");

    if (check_All.checked == true) {
        for (let i = 0; i < checkB.length; i++) {
            checkB[i].checked = true;
            checkB[i].setAttribute('required', '');
        }
        submitBtn.removeAttribute('disabled');
    }

    if (check_All.checked == false) {
        for (let i = 0; i < checkB.length; i++) {
            checkB[i].checked = false;
            checkB[i].removeAttribute('required');
        }
        submitBtn.setAttribute('disabled', '');
    }
}

// Check if checkbox is checked to enable/disable the CA and EXAM input box in single upload TODO
const checkSingleSelect = () => {
    let checkB = getBySelectAll("#checkB");
    let submitBtn = getBySelector("#submitBtn");
    let caCheck = getBySelectAll("#caCheck");
    let examCheck = getBySelectAll("#examCheck");
    
    for (let i = 0; i < checkB.length; i++)
    {
      if (checkB[i].checked == true)
      {
        checkB[i].setAttribute('required', '')
        caCheck[i].setAttribute('required', '');
        examCheck[i].setAttribute('required', '');
        submitBtn.removeAttribute('disabled');
      }
      else if (checkB[i].checked == false)
      {
        checkB[i].removeAttribute('required');
        caCheck[i].removeAttribute('required');
        examCheck[i].removeAttribute('required');
        submitBtn.setAttribute('disabled', '');
      }
    }
}

/*   CHECK SUBJECT ON ADD RESULT PAGE*/
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
        success: function (response) {
            //console.log("Response", response.class_name);
            // let result = JSON.parse(response);
            $("#subject_id").html(response);
            // $("#editClassID").val(response["class_id"]);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}
  /*-------x---- CHECK SUBJECT ON ADD RESULT PAGE -------x----*/