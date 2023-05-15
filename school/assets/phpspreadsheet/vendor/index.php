<?php 
session_start();

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_POST['xport_btn']))
{
    $fileName = $_FILES['import_file']['name'];
    $fileExist = pathinfo($fileName, PATHINFO_EXTENSION);

    $allow_ext = ['xls', 'csv','xlsx'];

    if(in_array($fileExist, $allow_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = "0";
        foreach($data as $row)
        {
            if($count > 0)
            {
                $admNo = $row[0];
                $sname = $row[1];
                $lname = $row[2];
                $oname = $row[3];
                $class_name = $row[4];
                $dob = $row[5];
                $religion = $row[6];
                $gender = $row[7];
				echo $admNo." ".$sname." ".$lname." ".$oname." ".$class_name." ".$dob." ".$religion." ".$gender."<br />";
                //$con = mysqli_connect("localhost", "root", "", "success_schools_sokoto");
                //$con =  mysqli_connect("sql213.unaux.com", "unaux_29465961", "horney6060@1991", "unaux_29465961_success_schools_sokoto");
				//$student_query = mysqli_query($con, "INSERT INTO students_tbl(admNo, sname, lname, oname, class_name, dob, religion, gender) VALUES('$admNo', '$sname', '$lname', '$oname', '$class_name', '$dob', '$religion', '$gender')");
                //$msg = true;
            }
            else
            {
                $count = "1";
            }            
        }
        if(isset($msg))
        {
            $_SESSION['message'] = "Successfully";
        }
        else
        {
            $_SESSION['message'] = die("Error");
        }
    }
    else
    {
        $_SESSION['message'] = "File not supported!";
    }
    //$spreadsheet = new Spreadsheet();
    //$sheet = $spreadsheet->getActiveSheet();
    //$sheet->setCellValue('A1', 'Hello World !');
    
    //$writer = new Xlsx($spreadsheet);
    //$writer->save('hello world.xlsx');
    
}
if(isset($_POST['result_btn']))
{
    $fileName = $_FILES['result_import_file']['name'];
    $fileExist = pathinfo($fileName, PATHINFO_EXTENSION);

    $allow_ext = ['xls', 'csv','xlsx'];

    if(in_array($fileExist, $allow_ext))
    {
        $inputFileNamePath = $_FILES['result_import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = "0";
        foreach($data as $row)
        {
            if($count > 0)
            {
                $class_id = $row[0];
                $session_name = $row[1];
                $term_name = $row[2];
                $subject_id = $row[3];
                $admNo = $row[4];
                $ca = $row[5];
                $exam = $row[6];
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

                $con =  mysqli_connect("sql213.unaux.com", "unaux_29465961", "horney6060@1991", "unaux_29465961_success_schools_sokoto");
				//$con = mysqli_connect("localhost", "root", "", "success_schools_sokoto");
                $result_query = mysqli_query($con, "INSERT INTO result_tbl(class_id, session_name, term_name, subject_id, admNo, ca, exam, total, grade, remark) VALUES('$class_id', '$session_name', '$term_name', '$subject_id', '$admNo', '$ca', '$exam', '$total', '$grade', '$remark')");
                $msg = true;
            }
            else
            {
                $count = "1";
            }            
        }
        if(isset($msg))
        {
            $_SESSION['message'] = "Successfully";
        }
        else
        {
            $_SESSION['message'] = die("Error ". mysqli_error($con));
        }
    }
    else
    {
        $_SESSION['message'] = "File not supported!";
    }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Exporting Excel file to MySql Database </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
    <h1 class="control-label">Exporting Excel file to MySql Database </h1>
        <div class="row">
            <div class="col-md-12">
                 
                    <?php 
                        if(isset($_SESSION['message']))
                        {
                    ?>
                            <h1 class="text-primary"> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?></h1>
                    <?php
                        }
                    ?>
            </div>
            <div class="col-md-9">
                <form method="POST" action="index.php" enctype="multipart/form-data">
                    Students data:<input type="file" class="form-control" name="import_file">
                    <button class="btn btn-primary mt-3" type="submit" name="xport_btn">Export</button>
                </form>
            </div>
            <hr />
            <div class="col-md-9">
                <form method="POST" action="index.php" enctype="multipart/form-data">
                    Result upload:<input type="file" class="form-control" name="result_import_file">
                    <button class="btn btn-primary mt-3" type="submit" name="result_btn">Export</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 