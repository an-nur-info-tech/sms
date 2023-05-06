<?php
session_start();

include('includes/header.php');

require 'Exceltodatabase/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_POST['xport_btn']))
{
    $db = new Database();

    $fileName = $_FILES['import_file']['name'];
    $fileExist = pathinfo($fileName, PATHINFO_EXTENSION);

    $allow_ext = ['xls', 'csv','xlsx'];

    if(in_array($fileExist, $allow_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach($data as $row)
        {
            if($count > 0)
            {
                $admNo = trim(strtoupper($row[0]));
                $sname = trim(strtoupper($row[1]));
                $lname = trim(strtoupper($row[2]));
                $oname = trim(strtoupper($row[3]));
                $class_name = trim(strtoupper($row[4]));
                $dob = trim(strtoupper($row[5]));
                $religion = trim(strtoupper($row[6]));
                $gender = trim(strtoupper($row[7]));

				$db->query("INSERT INTO 
                        students_tbl(admNo, sname, lname, oname, class_name, dob, religion, gender) 
                        VALUES(:admNo, :sname, :lname, :oname, :class_name, :dob, :religion, :gender);
                    ");
                $db->bind(':admNo', $admNo);
                $db->bind(':sname', $sname);
                $db->bind(':lname', $lname);
                $db->bind(':oname', $oname);
                $db->bind(':class_name', $class_name);
                $db->bind(':dob', $dob);
                $db->bind(':religion', $religion);
                $db->bind(':gender', $gender);
                if(!$db->execute())
				{
					$_SESSION['message'] = $db->getError();
				}
				else
				{
                    echo '<script>
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                showConfirmButton: true,
                                confirmButtonText: "close",
                                closeOnConfirm: false
                            }).then((result) => {
                                if(result.value){
                                    window.location = "excel-upload";
                                }
                            })
                        </script>'; 					
                    //$_SESSION['message'] = "Successfully";
				}
            }
            else
            {
                $count = 1;
            }            
        }
    }
    else
    {
        echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "File not supported",
                    showConfirmButton: true,
                    confirmButtonText: "close",
                    closeOnConfirm: false
                }).then((result) => {
                    if(result.value){
                        window.location = "excel-upload";
                    }
                })
            </script>'; 
        //$_SESSION['message'] = "File not supported!";
    }
    //$spreadsheet = new Spreadsheet();
    //$sheet = $spreadsheet->getActiveSheet();
    //$sheet->setCellValue('A1', 'Hello World !');
    
    //$writer = new Xlsx($spreadsheet);
    //$writer->save('hello world.xlsx');
    //return $con = null;
    $db->Disconect();
}

if(isset($_POST['result_btn']))
{
    $db = new Database();
    $fileName = $_FILES['result_import_file']['name'];
    $fileExist = pathinfo($fileName, PATHINFO_EXTENSION);

    $allow_ext = ['xls', 'csv','xlsx'];

    if(in_array($fileExist, $allow_ext))
    {
        $inputFileNamePath = $_FILES['result_import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach($data as $row)
        {
            if($count > 0)
            {
                $class_id = trim($row[0]);
                $session_name = trim($row[1]);
                $term_name = trim($row[2]);
                $subject_id = trim($row[3]);
                $admNo = trim($row[4]);
                $ca = trim($row[5]);
                $exam = trim($row[6]);
                $total = $ca + $exam;
                //Getting Grade and Remark
                if($total <= 39 )
                {
                    $grade = "F9";
                    $remark = "Fail";
                }
                if($total >= 40 || $total >= 44)
                {
                    $grade = "E8";
                    $remark = "Pass";
                }
                if($total >= 45 || $total >= 49 )
                {
                    $grade = "D7";
                    $remark = "Pass";
                }
                if($total >= 50 || $total >= 59)
                {
                    $grade = "C6";
                    $remark = "Credit";
                }
                if($total >= 60 || $total >= 64 )
                {
                    $grade = "C5";
                    $remark = "Credit";
                }
                if($total >= 65 || $total >= 69 )
                {
                    $grade = "C4";
                    $remark = "Credit";
                }
                if($total >= 70 || $total >= 74 )
                {
                    $grade = "B3";
                    $remark = "Good";
                }
                if($total >= 75 || $total >= 79 )
                {
                    $grade = "B2";
                    $remark = "Good";
                }
                if($total >= 80 || $total >= 100 )
                {
                    $grade = "A1";
                    $remark = "Excellent";
                }

                $db->query("INSERT INTO 
                        result_tbl(class_id, session_name, term_name, subject_id, admNo, ca, exam, total, grade, remark) 
                        VALUES(:class_id, :session_name, :term_name, :subject_id, :admNo, :ca, :exam, :total, :grade, :remark);
                    ");
                $db->bind('class_id', $class_id);
                $db->bind('session_name', $session_name);
                $db->bind('term_name', $term_name);
                $db->bind('subject_id', $subject_id);
                $db->bind('admNo', $admNo);
                $db->bind('ca', $ca);
                $db->bind('exam', $exam);
                $db->bind('total', $total);
                $db->bind('grade', $grade);
                $db->bind('remark', $remark);

                if($db->execute()){
                    $msg = true;
                    return $msg;
                }else{
                    return false;
                }
                
            }
            else
            {
                $count = 1;
            }            
        }
        if(isset($msg))
        {
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        showConfirmButton: true,
                        confirmButtonText: "close",
                        closeOnConfirm: false
                    }).then((result) => {
                        if(result.value){
                            window.location = "excel-upload";
                        }
                    })
                </script>';             
            //$_SESSION['message'] = "Successfully";
        }
        else
        {
            echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "Error",
                        showConfirmButton: true,
                        confirmButtonText: "close",
                        closeOnConfirm: false
                    }).then((result) => {
                        if(result.value){
                            window.location = "excel-upload";
                        }
                    })
                </script>';             
            //$_SESSION['message'] = die("Error ". mysqli_error($con));
        }
    }
    else
    {
            echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "File format error",
                        showConfirmButton: true,
                        confirmButtonText: "close",
                        closeOnConfirm: false
                    }).then((result) => {
                        if(result.value){
                            window.location = "excel-upload";
                        }
                    })
                </script>';             
        
        //$_SESSION['message'] = "File not supported!";
    }

    //return $con = null;
    $db->Disconect();
}


?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

           <!-- Page Heading -->
           <div  class="align-items-center justify-content-center ">
            <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px;                           margin-bottom: 10px;"> Uploading Excel file </h3>
            <p class="text-danger">Please upload only Excel file format </p>
          </div><br>
                
                <div class="row">
                    <div class="col-md-12  col-sm-12">
                        
                            <?php 
                                if(isset($_SESSION['message']))
                                {
                            ?>
                                    <h1 class="alert alert-success"> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?></h1>
                            <?php
                                }
                            ?>
                    </div>
                </div>
            <form method="POST" action="excel-upload.php" enctype="multipart/form-data">
                <div class="form-row form-inline">
                    <div class="col-md-12 col-sm-4 p-3">
                        Students data: <input type="file" class="form-control" name="import_file" required>
                        <button class="btn btn-primary" type="submit" name="xport_btn">Upload</button>
                    </div>
                </div>
                <hr>
            </form>

            <form method="POST" action="excel-upload.php" enctype="multipart/form-data">
                <div class="form-row form-inline mt-3">
                    <div class="col-md-12 col-sm-4 p-3">
                        Result upload: <input type="file" class="form-control" name="result_import_file" required>
                        <button class="btn btn-primary" type="submit" name="result_btn">Upload</button>
                    </div>
                </div>
            </form>

        </div>
<?php
include('includes/footer.php');
include('includes/script.php');
?>
