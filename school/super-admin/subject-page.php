<?php
include('includes/header.php');
$db = new Database();

if (isset($_POST['subject_btn'])) {
  $subject_name = strtoupper($_POST['subject_name']);
  $db->query("INSERT INTO subject_tbl(subject_name) VALUES(:subject_name);");
  $db->bind(':subject_name', $subject_name);
  if(!$db->execute()) {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Error";
    $_SESSION['sessionMsg'] = "Error occured!";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "subject-page";
    die($db->getError());
  } 
  else 
  {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Success";
    $_SESSION['sessionMsg'] = "Record added!";
    $_SESSION['sessionIcon'] = "success";
    $_SESSION['location'] = "subject-page";
  }
}


if (isset($_POST['updateSubjectBtn'])) {
  $editSubjectName = strtoupper(trim($_POST['editSubjectName']));

  // Check if class name does not exist
  $db->query("SELECT * FROM subject_tbl WHERE subject_name = :editSubjectName;");
  $db->bind(':editSubjectName', $editSubjectName);
  if (!$db->execute()) {
    die($db->getError());
  } else {
    if ($db->rowCount() > 0) {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Ooops...";
      $_SESSION['sessionMsg'] = "The subject name exist";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "subject-page";
    } else {
      
      $editSubjectID = $_POST['editSubjectID'];
      //Updating record
      $db->query("UPDATE subject_tbl SET subject_name = :editSubjectName WHERE subject_id = :editSubjectID;");
      $db->bind(':editSubjectID', $editSubjectID);
      $db->bind(':editSubjectName', $editSubjectName);
      if (!$db->execute()) {
        die($db->getError());
      } else {
        if ($db->rowCount()  > 0)
        {
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Success";
          $_SESSION['sessionMsg'] = "Record updated!";
          $_SESSION['sessionIcon'] = "success";
          $_SESSION['location'] = "subject-page";
        }
        else 
        {
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Error";
          $_SESSION['sessionMsg'] = "Something went wrong";
          $_SESSION['sessionIcon'] = "error";
          $_SESSION['location'] = "subject-page";
        }
      }
    }
  }
}

if (isset($_POST['deleteSubjectBtn'])) {
  $userID = $_POST['userID'];
  $deleteSubjectID = $_POST['deleteSubjectID'];

  $lookup = $_SESSION['staff_id']." deLEtED";
  $search = $userID;

  if ($search == $lookup) //Check if user input is equal to the lookup
  {
    $db->query("DELETE FROM subject_tbl WHERE subject_id = :deleteSubjectID;");
    $db->bind(':deleteSubjectID', $deleteSubjectID);
    if (!$db->execute()) {
      die($db->getError());
    } else {
      if ($db->rowCount() > 0)
      {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Success";
        $_SESSION['sessionMsg'] = "Record deleted";
        $_SESSION['sessionIcon'] = "success";
        $_SESSION['location'] = "subject-page";
      }
      else 
      {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Something went wrong";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "subject-page";
      }
    }
  }
  else
  {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Ooops..";
    $_SESSION['sessionMsg'] = "Input does not match, case are sensitive";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "subject-page";
  }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary">Subject Registration Page</h1>
  </div>

  <!-- Subject Content Row -->
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-row form-inline">
      <div class="form-group col-md-12">
        <label class="control-label" for="class_name"> Subject: </label> &nbsp;
        <input type="text" name="subject_name" class="form-control" placeholder="Type in subject" autocomplete="off" required>&nbsp;
        &nbsp;
        <button name="subject_btn" class="btn btn-primary"> Submit </button>
      </div>
    </div><br><br>
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
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary text-uppercase">Subjects data</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <!-- Class Table-->
        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <th class="table-primary"> # </th>
            <th class="table-primary"> Subjects </th>
            <th class="table-primary"> Actions </th>
          </thead>
          <tbody>
            <?php
            
            $db->query("SELECT * FROM subject_tbl ORDER BY subject_id DESC;");
            $data = $db->resultset();
            if (!$db->isConnected()) {
              die("Error " . $db->getError());
            } else {
              if ($db->rowCount() > 0) {
                $count = 1;
                foreach($data as $row) {
                  //$row->subject_id = $numbering ++;
            ?>
                  <tr>
                    <td> <?php echo $count; ?> </td>
                    <td> <?php echo $row->subject_name; ?> </td>
                    <td>
                      <div class="form-inline">
                        <div class="m-2">
                            <button class="btn btn-sm btn-outline-primary subjectEditBtn" subjectEditID = "<?php echo $row->subject_id; ?>" data-toggle="modal" data-target="#subjectEditBtn" name="subjectEditBtn"> <i class="fas fa-fw fa-edit"></i> Edit</button>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-danger subjectDelBtn" subjectDelID = "<?php echo $row->subject_id; ?>" data-toggle="modal" data-target="#subjectDelBtn" name="subjectDelBtn"> <i class="fas fa-fw fa-trash-alt"></i> Delete</button>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php
                  $count++;
                }
              } else {
                ?>
                <tr>
                  <td>
                    Subject Table is empty(Input subject and click Submit)
                  </td>
                </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Edit Subject Modal-->
  <div class="modal fade" id="subjectEditBtn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Subject Page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="subject-page">
          <div class="modal-body">
            <div class="col-md-12">
              <div class="form-row form-inline">
                <div class="form-group">
                  <label>Subject:</label> &nbsp;
                  <input type="text" id="editSubjectName" name="editSubjectName" value="" class="form-control" required>
                  <input type="hidden" id="editSubjectID" name="editSubjectID" value="" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="updateSubjectBtn" class="btn btn-sm btn-primary"> Update </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Subject Modal-->
  <div class="modal fade" id="subjectDelBtn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Subject Page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="subject-page">
          <div class="modal-body">
            <p>Are you sure to delete this class? if yes, type in your staff ID followed by deLEtED e.g stf/21/0001 deLEtED</p>
            <input type="text" name="userID" autocomplete="off" placeholder="stf/21/0001 deLEtED"  class="form-control" required>
            <input type="hidden" id="deleteSubjectID" name="deleteSubjectID" value="" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="deleteSubjectBtn" class="btn btn-sm btn-primary"> Yes </button>
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