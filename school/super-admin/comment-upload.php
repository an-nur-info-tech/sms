<?php
include('includes/header.php');

require '../assets/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['comment_btn'])) {
    $db = new Database();

    $fileName = $_FILES['comment_import_file']['name'];
    $fileExist = pathinfo($fileName, PATHINFO_EXTENSION);

    $allow_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($fileExist, $allow_ext)) {
        $error = false;
        
        $inputFileNamePath = $_FILES['comment_import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach ($data as $row) {
            if ($count > 0) {
                $session_id = trim($row[0]);
                $term_id = trim($row[1]);
                $admNo = trim($row[2]);
                // $names = trim(strtoupper($row[3]));
                $attendance =trim(strtoupper($row[4]));
                $honesty =trim(strtoupper($row[5]));
                $neatness =trim(strtoupper($row[6]));
                $punctuality =trim(strtoupper($row[7]));
                $tolerance =trim(strtoupper($row[8]));
                $creativity =trim(strtoupper($row[9]));
                $dexterity =trim(strtoupper($row[10]));
                $fluency =trim(strtoupper($row[11]));
                $handwriting =trim(strtoupper($row[12]));
                $obedience =trim(strtoupper($row[13]));
                $comments =trim($row[14]);
                
                if((strlen($comments) > 50) || (empty($comments)))
                {
                    $error = true;
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "Comments should <= 50 chars and not empty";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "comment-upload";
                }
                if(
                    (strlen($attendance) < 1 || strlen($attendance) > 1 )
                    || (strlen($honesty) < 1 || strlen($honesty) > 1 )
                    || (strlen($neatness) < 1 || strlen($neatness) > 1 )
                    || (strlen($punctuality) < 1 || strlen($punctuality) > 1 )
                    || (strlen($tolerance) < 1 || strlen($tolerance) > 1 )
                    || (strlen($creativity) < 1 || strlen($creativity) > 1 )
                    || (strlen($dexterity) < 1 || strlen($dexterity) > 1 )
                    || (strlen($fluency) < 1 || strlen($fluency) > 1 )
                    || (strlen($handwriting) < 1 || strlen($handwriting) > 1 )
                    || (strlen($obedience) < 1 || strlen($obedience) > 1 )
                    
                )
                {
                    $error = true;
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "The key chars should be = 1 char ";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "comment-upload";
                }
                // Check if value not temper
                if (
                    empty($term_id) || empty($session_id) || empty($admNo) 
                    || !is_numeric($term_id) || !is_numeric($session_id) 
                    )
                {
                    $error = true;
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "Value's tempered";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "comment-upload";
                }
                
                if(!$error)
                {
                    // Check if comment exits
                    $db->query("SELECT * FROM comments_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                    $db->bind(':admNo', $admNo);
                    $db->bind(':session_id', $session_id);
                    $db->bind(':term_id', $term_id);
                    
                    if($db->execute())
                    {
                        if($db->rowCount() > 0)
                        {
                            $_SESSION['errorMsg'] = true;
                            $_SESSION['errorTitle'] = "Ooops...";
                            $_SESSION['sessionMsg'] = "Comments exist";
                            $_SESSION['sessionIcon'] = "error";
                            $_SESSION['location'] = "comment-upload";
                        }
                        else 
                        {
                            $db->query(
                                "INSERT INTO 
                                comments_tbl(session_id, term_id, admNo, attendance, honesty, neatness, punctuality, tolerance, creativity, dexterity, fluency, handwriting, obedience, teacher_comment) 
                                VALUES(:session_id, :term_id, :admNo, :attendance, :honesty, :neatness, :punctuality, :tolerance, :creativity, :dexterity, :fluency, :handwriting, :obedience, :comments);
                            ");
                            $db->bind(':session_id', $session_id);
                            $db->bind(':term_id', $term_id);
                            $db->bind(':admNo', $admNo);
                            $db->bind(':attendance', $attendance);
                            $db->bind(':honesty', $honesty);
                            $db->bind(':neatness', $neatness);
                            $db->bind(':punctuality', $punctuality);
                            $db->bind(':tolerance', $tolerance);
                            $db->bind(':creativity', $creativity);
                            $db->bind(':dexterity', $dexterity);
                            $db->bind(':fluency', $fluency);
                            $db->bind(':handwriting', $handwriting);
                            $db->bind(':obedience', $obedience);
                            $db->bind(':comments', $comments);
                            
                            if (!$db->execute()) {
                                $_SESSION['errorMsg'] = true;
                                $_SESSION['errorTitle'] = "Error";
                                $_SESSION['sessionMsg'] = "Error occured!";
                                $_SESSION['sessionIcon'] = "error";
                                $_SESSION['location'] = "comment-upload";
                                die($db->getError());
                            } else {
                                $_SESSION['errorMsg'] = true;
                                $_SESSION['errorTitle'] = "Success";
                                $_SESSION['sessionMsg'] = "Comments uploaded";
                                $_SESSION['sessionIcon'] = "success";
                                $_SESSION['location'] = "comment-upload";
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
        $_SESSION['location'] = "comment-upload";
    }

    //return $con = null;
}
$db->Disconect();


?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 16px; margin-bottom: 5px;"> Spreadsheet File Upload page </h3>
        <p class="text-danger">Please upload only spreadsheet file </p>
    </div>

    <form method="POST" action="comment-upload" enctype="multipart/form-data">
        <div class="card mt-5">
            <div class="card-header">
                Class Teacher Comments Template Upload
            </div>
            <div class="card-body">
            <p><span class="text-danger"> Please note that comment should not be greater than 50 characters.</span></p>
                <div class="form-row form-inline">
                    <div class="col-md-8">
                        <div class="form-group">
                            Comment Upload: &nbsp;<input type="file" class="form-control" name="comment_import_file" required>
                            &nbsp;&nbsp;<button class="btn btn-primary spinner_btn" onclick="add_spinner()" type="submit" name="comment_btn">Upload</button>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div> 
    </form>

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