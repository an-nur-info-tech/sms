<?php
include('includes/header.php');

$db = new Database();
if (isset($_POST['submit_btn'])) {
  $class_id = $_POST['class_id'];
  $subject_id = $_POST['subject_id'];
  $instructor_id = $_POST['instructor_id'];

  //Checking if subject exist
  $db->query("SELECT * FROM class_subject_tbl WHERE subject_id = :subject_id AND class_id = :class_id;");
  $db->bind(':subject_id', $subject_id);
  $db->bind(':class_id', $class_id);

  if ($db->execute()) {
    if ($db->rowCount() > 0) {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Error";
      $_SESSION['sessionMsg'] = "Subject added already";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "class-subject-page";
    } else {
      $db->query("INSERT INTO class_subject_tbl(class_id, staff_id, subject_id) VALUES(:class_id, :instructor_id, :subject_id);");
      $db->bind(':class_id', $class_id);
      $db->bind(':instructor_id', $instructor_id);
      $db->bind(':subject_id', $subject_id);
      if (!$db->execute()) {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Error occured!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "class-subject-page";
        die($db->getError());
      } else {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Success";
        $_SESSION['sessionMsg'] = "Record added!";
        $_SESSION['sessionIcon'] = "success";
        $_SESSION['location'] = "class-subject-page";
      }
    }
  } else {
    die($db->getError());
  }
}

if (isset($_POST['changeTeacherBtn']))
{
  $staff_id = $_POST['staff_id'];
  $class_id = $_POST['class_id'];
  $subject_id = $_POST['subject_id'];

  $db->query("UPDATE class_subject_tbl SET staff_id = :staff_id WHERE subject_id = :subject_id AND class_id = :class_id;");
  $db->bind(':staff_id', $staff_id);
  $db->bind(':subject_id', $subject_id);
  $db->bind(':class_id', $class_id);

  if ($db->execute())
  {
    if ($db->rowCount() > 0)
    {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Success";
      $_SESSION['sessionMsg'] = "Instructor changed!";
      $_SESSION['sessionIcon'] = "success";
      $_SESSION['location'] = "class-subject-page";
    }
    else 
    {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Error";
      $_SESSION['sessionMsg'] = "Error occured!";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "class-subject-page";
    }
  }
  else 
  {
    die($db->getError());
  }
}

if (isset($_POST['deleteClassSubjectBtn'])) {
  $userID = $_POST['userID'];
  $deleteSubjectID = $_POST['deleteSubjectID'];
  $class_id = $_POST['class_id'];

  $lookup = $_SESSION['staff_id']." deLEtED";
  $search = $userID;

  if ($search == $lookup) //Check if user input is equal to the lookup
  {
    $db->query("DELETE FROM class_subject_tbl WHERE subject_id = :deleteSubjectID AND class_id = :class_id;");
    $db->bind(':deleteSubjectID', $deleteSubjectID);
    $db->bind(':class_id', $class_id);
    if (!$db->execute()) {
        die($db->getError());
    } 
    else 
    {
      if ($db->rowCount() > 0)
      {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Success";
        $_SESSION['sessionMsg'] = "Subject removed";
        $_SESSION['sessionIcon'] = "success";
        $_SESSION['location'] = "class-subject-page";
      }
      else 
      {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Something went wrong";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "class-subject-page";
      }
    }
  }
  else
  {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Ooops..";
    $_SESSION['sessionMsg'] = "Input does not match, case are sensitive";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "class-subject-page";
  }
}
?>


<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="align-items-center justify-content-center ">
    <h2 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Class Subject Registration/View Page</h2>
    <h6 class="text-danger">(*) All fields are required</h6>
  </div><br>

  <!-- Class Subject Content Row -->
  <form method="POST" action="class-subject-page">
    <div class="form-row ">
      <div class="col-md-3">
        <div class="form-group">
          <select name="class_id" class="form-control" required>
            <option value="">Select Class...</option>
            <!-- Fetching data from class/Subject/Staff table -->
            <?php
            $db->query("SELECT * FROM class_tbl");
            if (!$db->execute()) {
              die($db->getError());
            } else {
              if ($db->rowCount() > 0) {
                $result = $db->resultset();
                foreach ($result as $row1) {
            ?>
                  <option value="<?php echo $row1->class_id; ?>"> <?php echo $row1->class_name; ?></option>
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
      <div class="col-md-4">
        <div class="form-group">
          <select name="instructor_id" class="form-control" required>
            <option value=""> Instructor...</option>
            <!-- Fetching data from staff table -->
            <?php
            $db->query("SELECT * FROM staff_tbl");
            if (!$db->execute()) {
              die($db->getError());
            } else {
              if ($db->rowCount() > 0) {
                $result = $db->resultset();
                foreach ($result as $row3) {
            ?>
                  <option value="<?php echo $row3->staff_id; ?>"> <?php echo $row3->fname . " " . $row3->sname . " " . $row3->oname; ?></option>
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
          <button name="submit_btn" class="btn btn-primary"> Submit </button>
        </div>
      </div>
    </div>
    <br />
  </form>
  <form method="POST" action="class-subject-page">
    <div class="form-row">
      <div class="col-md-12">
        <div class="form-group" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px; background-color: grey; color: white;">
          <label> Select a class from the select option below to view its subjects</label>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <select name="class_select" class="form-control" required>
            <option value=""> Select class...</option>
            <!-- Fetching data from class/Subject/Staff table -->
            <?php
            $db->query("SELECT * FROM class_tbl");
            if (!$db->execute()) {
              $warningMsg = die(mysqli_error($con));
            } else {
              if ($db->rowCount() > 0) {
                $result = $db->resultset();
                foreach ($result as $row1) {
            ?>
                  <option value="<?php echo $row1->class_id; ?>"> <?php echo $row1->class_name; ?></option>
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
      <div class="col-md-4">
        <div class="form-group">
          <button name="view_btn" class="btn btn-outline-primary"> View </button>
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

  <?php
  if (isset($_POST['view_btn'])) {
    $class_select = $_POST['class_select'];
  ?>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary text-uppercase">Class subjects data</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <th class="table-primary"> # </th>
              <th class="table-primary"> Class name </th>
              <th class="table-primary"> Subjects </th>
              <th class="table-primary"> Instructor </th>
              <th class="table-primary"> Actions </th>
            </thead>
            <tbody>
            <?php
            
            $db->query(
              "SELECT * FROM class_subject_tbl AS cst
              JOIN class_tbl ON class_tbl.class_id = cst.class_id
              JOIN staff_tbl ON staff_tbl.staff_id = cst.staff_id
              JOIN subject_tbl ON subject_tbl.subject_id = cst.subject_id
              WHERE cst.class_id = :class_select ORDER BY cst_id DESC;"
            );
            $db->bind(':class_select', $class_select);

            if (!$db->execute()) {
              die("Error" . $db->getError());
            } else {
              $nums_result = $db->rowCount();
              if ($nums_result > 0) {
                $result = $db->resultset();
                $count = 1;
                foreach ($result as $row) {
            ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row->class_name; ?></td>
                    <td><?php echo $row->subject_name; ?></td>
                    <td><?php echo "$row->fname $row->sname $row->oname &nbsp; &nbsp;<i title='Change instructor' subject_id = '$row->subject_id' class_id = '$row->class_id'  data-toggle='modal' data-target='#change_instructor' class='fas fa-fw fa-edit text-primary change_instructor'></i>"; ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger deleteClassSubjectID" class_id="<?php echo $row->class_id; ?>" deleteSubjectID = "<?php echo $row->subject_id; ?>" data-toggle="modal" data-target="#deleteClassSubject" title="Remove class subject"> <i class="fas fa-fw fa-trash fa-sm"></i> </button>
                     </td>
                  </tr>
                  <?php
                  $count++;
                }
              } else {
                  ?>
                  <tr>
                    <td colspan="5" class="fw-bold text-center"> No subject found for the class </td>
                  </tr>
              <?php
              }
            }
          }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>


  <!-- Change Instructor Modal-->
  <div class="modal fade" id="change_instructor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Change Instructor</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="class-subject-page">
          <div class="modal-body">
            <p>Select an instrutor and click on Change to effect to process </p>
            <div class="mt-3">
                <select name="staff_id" id="staff_id" class="form-control" required>
                  <option value=""> Select instructor...</option>
                </select>
                <input type="hidden" name="class_id" id="class_id" autocomplete="off" class="form-control">
                <input type="hidden" name="subject_id" id="subject_id" autocomplete="off" class="form-control">
            </div>          
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="changeTeacherBtn" class="btn btn-sm btn-primary"> Change </button>
          </div>
        </form>
      </div>
    </div>
  </div>  

  <!-- Delete Class subject Modal-->
  <div class="modal fade" id="deleteClassSubject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Class Subject Page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="class-subject-page">
          <div class="modal-body">
            <p>Are you sure to remove the class subject? if yes, type in your staff ID followed by deLEtED e.g stf/21/0001 deLEtED</p>
            <input type="text" name="userID" autocomplete="off" placeholder="stf/21/0001 deLEtED"  class="form-control" required>
            <input type="hidden" id="deleteSubjectID" name="deleteSubjectID" class="form-control">
            <input type="hidden" id="class_ID" name="class_id" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="deleteClassSubjectBtn" class="btn btn-sm btn-primary"> Yes </button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div><!-- End of Main Content -->

<?php
$db->Disconect();
include('includes/footer.php');
include('includes/script.php');
?>