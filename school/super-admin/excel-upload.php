<?php
include('includes/header.php');

require '../assets/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['xport_btn'])) {
    $db = new Database();

    $fileName = $_FILES['import_file']['name'];
    $fileExist = pathinfo($fileName, PATHINFO_EXTENSION);

    $allow_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($fileExist, $allow_ext)) {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach ($data as $row) {
            if ($count > 0) {
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

                if (!$db->execute()) {
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "Error occured!";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "excel-upload";
                    die($db->getError());
                } else {
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Success";
                    $_SESSION['sessionMsg'] = "Result uploaded";
                    $_SESSION['sessionIcon'] = "success";
                    $_SESSION['location'] = "excel-upload";
                    //$_SESSION['message'] = "Successfully";
                }
            } 
            else {
                $count = 1;
            }
        }
    } 
    else {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "File not supported!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "excel-upload";
    }
    //$spreadsheet = new Spreadsheet();
    //$sheet = $spreadsheet->getActiveSheet();
    //$sheet->setCellValue('A1', 'Hello World !');

    //$writer = new Xlsx($spreadsheet);
    //$writer->save('hello world.xlsx');
    //return $con = null;
    $db->Disconect();
}

if (isset($_POST['result_btn'])) {
    $db = new Database();

    $fileName = $_FILES['result_import_file']['name'];
    $fileExist = pathinfo($fileName, PATHINFO_EXTENSION);

    $allow_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($fileExist, $allow_ext)) {
        $error = false;
        
        $inputFileNamePath = $_FILES['result_import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach ($data as $row) {
            if ($count > 0) {
                $class_id = trim($row[0]);
                $session_id = trim($row[1]);
                $term_id = trim($row[2]);
                $subject_id = trim($row[3]);
                $admNo = trim(strtoupper($row[4]));
                $ca = trim($row[5]);
                $exam = trim($row[6]);
                if(($ca < 0) || ($ca > 40))
                {
                    $error = true;
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "C.A should <= 40";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "excel-upload";
                }
                if(($exam < 0) || ($exam > 60))
                {
                    $error = true;
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "Exam should <= 60";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "excel-upload";
                }
                if(!$error)
                {
                    $total = $ca + $exam;
                    //Getting Grade and Remark
                    if ($total <= 39) {
                        $grade = "F9";
                        $remark = "Fail";
                    }
                    if ($total >= 40 || $total >= 44) {
                        $grade = "E8";
                        $remark = "Pass";
                    }
                    if ($total >= 45 || $total >= 49) {
                        $grade = "D7";
                        $remark = "Pass";
                    }
                    if ($total >= 50 || $total >= 59) {
                        $grade = "C6";
                        $remark = "Credit";
                    }
                    if ($total >= 60 || $total >= 64) {
                        $grade = "C5";
                        $remark = "Credit";
                    }
                    if ($total >= 65 || $total >= 69) {
                        $grade = "C4";
                        $remark = "Credit";
                    }
                    if ($total >= 70 || $total >= 74) {
                        $grade = "B3";
                        $remark = "Good";
                    }
                    if ($total >= 75 || $total >= 79) {
                        $grade = "B2";
                        $remark = "Good";
                    }
                    if ($total >= 80 || $total >= 100) {
                        $grade = "A1";
                        $remark = "Excellent";
                    }

                    $db->query("SELECT * FROM result_tbl WHERE class_id = :class_id AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id AND admNo = :admNo;");
                    $db->bind(':class_id', $class_id);
                    $db->bind(':session_id', $session_id);
                    $db->bind(':term_id', $term_id);
                    $db->bind(':subject_id', $subject_id);
                    $db->bind(':admNo', $admNo);
                    if($db->execute())
                    {
                        if($db->rowCount() > 0)
                        {
                            $_SESSION['errorMsg'] = true;
                            $_SESSION['errorTitle'] = "Ooops...";
                            $_SESSION['sessionMsg'] = "Result exist";
                            $_SESSION['sessionIcon'] = "error";
                            $_SESSION['location'] = "excel-upload";
                        }
                        else 
                        {
                            $db->query("INSERT INTO 
                            result_tbl(class_id, session_id, term_id, subject_id, admNo, ca, exam, total, grade, remark) 
                            VALUES(:class_id, :session_id, :term_id, :subject_id, :admNo, :ca, :exam, :total, :grade, :remark);
                                ");
                            $db->bind('class_id', $class_id);
                            $db->bind('session_id', $session_id);
                            $db->bind('term_id', $term_id);
                            $db->bind('subject_id', $subject_id);
                            $db->bind('admNo', $admNo);
                            $db->bind('ca', $ca);
                            $db->bind('exam', $exam);
                            $db->bind('total', $total);
                            $db->bind('grade', $grade);
                            $db->bind('remark', $remark);

                            if (!$db->execute()) {
                                $_SESSION['errorMsg'] = true;
                                $_SESSION['errorTitle'] = "Error";
                                $_SESSION['sessionMsg'] = "Error occured!";
                                $_SESSION['sessionIcon'] = "error";
                                $_SESSION['location'] = "excel-upload";
                                die($db->getError());
                            } else {
                                $_SESSION['errorMsg'] = true;
                                $_SESSION['errorTitle'] = "Success";
                                $_SESSION['sessionMsg'] = "Result uploaded";
                                $_SESSION['sessionIcon'] = "success";
                                $_SESSION['location'] = "excel-upload";
                            }
                        }
                    }
                }
            } 
            else {
                $count = 1;
            }
        }
    } 
    else {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "File not supported!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "excel-upload";
    }

    //return $con = null;
    $db->Disconect();
}


?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px;                           margin-bottom: 10px;"> Uploading Excel file </h3>
        <p class="text-danger">Please upload only Excel file format </p>
    </div><br>

    <form action="export" method="post" target="_blank">
        <div class="card">
            <div class="card-header">
                Subjects Template Download
            </div>
            <div class="card-body">
                <p>Please select necessary field to download subject required to your local machine. <span class="text-danger"> Once downloaded do not tempered with the value except the CA and Exam.</span></p>
                <div class="form-row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="select_class" class="form-control" required>
                                <option value=""> Select class...</option>
                                <?php
                                $db = new Database();
                                $db->query("SELECT * FROM class_tbl;");
                                if($db->execute())
                                {
                                    if ($db->rowCount() > 0) {
                                        $data = $db->resultset();
                                        foreach ($data as $record) {
                                    ?>
                                        <option value="<?php echo $record->class_id; ?>"> <?php echo $record->class_name; ?> </option>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value=""> No record </option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="subject_id" class="form-control" required>
                                <option value=""> Subject...</option>
                                <!-- Fetching data from subject table -->
                                <?php
                                    $db->query("SELECT * FROM subject_tbl");
                                    if (!$db->execute()) {
                                    die($db->getError());
                                    } else {
                                    if ($db->rowCount() > 0) {
                                        $result = $db->resultset();
                                        foreach ($result as $row2) {
                                    ?>
                                        <option value="<?php echo $row2->subject_id; ?>"> <?php echo $row2->subject_name; ?></option>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value=""> No record found</option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select name="session_id" class="form-control" required>
                                <option value=""> Select session...</option>
                                <?php
                                $db->query("SELECT * FROM session_tbl;");
                                if (!$db->execute()) {
                                    die($db->getError());
                                } else {
                                    if ($db->rowCount() > 0) {
                                        $result = $db->resultset();
                                        foreach ($result as $row) {
                                ?>
                                            <option value="<?php echo $row->session_id; ?>"> <?php echo $row->session_name; ?> </option>
                                <?php

                                        }
                                    } else {
                                        ?>
                                        <option> No record found </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" name="term_id" required>
                                <option value=""> Select term...</option>
                                <?php
                                $db->query("SELECT * FROM term_tbl;");
                                if (!$db->execute()) {
                                    die($db->getError());
                                } else {
                                    if ($db->rowCount() > 0) {
                                        $result = $db->resultset();
                                        foreach ($result as $row) {
                                ?>
                                            <option value="<?php echo $row->term_id; ?>"> <?php echo $row->term_name; ?> </option>
                                <?php

                                        }
                                    } else {
                                        ?>
                                        <option> No record found </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" name="subjectImportBtn" > Download </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form method="POST" action="excel-upload" enctype="multipart/form-data">
        <div class="card mt-3">
            <div class="card-header">
                Subjects Template Upload
            </div>
            <div class="card-body">
                <p>Please select necessary field to download subject required to your local machine</p>
                <div class="form-row form-inline">
                    <div class="col-md-8">
                        <div class="form-group">
                            Result upload: &nbsp;<input type="file" class="form-control" name="result_import_file" required>
                            &nbsp;&nbsp;<button class="btn btn-primary" disabled type="submit" name="result_btn">Upload</button>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div> 
    </form>

    <!-- UPLOADING STUDENT RECORD -->
    <!-- <form method="POST" action="excel-upload" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">
                Students data
            </div>
            <div class="card-body">
                <div class="form-row form-inline">
                    <div class="col-md-8">
                        <div class="form-group">
                            Student data: &nbsp; <input type="file" class="form-control" name="import_file" required>
                            &nbsp;&nbsp; <button class="btn btn-primary" disabled type="submit" name="xport_btn">Upload</button>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
    </form> -->

    <!-- Alerts messages -->
    <?php
    if (isset($_SESSION['errorMsg'])) {
        echo '<script>
              Swal.fire({
                title: "' . $_SESSION['errorTitle'] . '",
                text: "' . $_SESSION['sessionMsg'] . '",
                icon: "' . $_SESSION['sessionIcon'] . '",
                showConfirmButton: true,
                confirmButtonText: "ok"
              }).then((result) => {
                  if(result.value){
                      window.location = "' . $_SESSION['location'] . '";
                  }
              })
          </script>';
        unset($_SESSION['errorTitle']);
        unset($_SESSION['errorMsg']);
        unset($_SESSION['sessionMsg']);
        unset($_SESSION['location']);
        unset($_SESSION['sessionIcon']);
    }
    ?>    
</div>
<?php
include('includes/footer.php');
include('includes/script.php');
?>