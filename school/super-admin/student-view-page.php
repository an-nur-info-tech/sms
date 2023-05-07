<?php
include('includes/header.php');

if (isset($_POST['update_btn'])) {
  $error = false;

  $admNo = mysqli_real_escape_string($con, $_POST['admNo']);
  $sname = mysqli_real_escape_string($con, $_POST['sname']);
  $lname = mysqli_real_escape_string($con, $_POST['lname']);
  $oname = mysqli_real_escape_string($con, $_POST['oname']);
  $select_class = mysqli_real_escape_string($con, $_POST['select_class']);

  $fileToUpload = $_FILES["fileToUpload"]["name"];

  //specifying the directory where the file is going to be placed.
  $target_dir = "uploads/";
  //specifying path of the file to be uploaded
  $target_file = $target_dir . basename($fileToUpload);
  //Getting the file extension
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // check if file exists
  if (file_exists($target_file)) {
    $error = true;
    $warningMsg = "Picture exist";
  }
  // validate image size. Size is calculated in Bytes
  if ($_FILES['fileToUpload']['size'] > 102405 or $_FILES['fileToUpload']['size'] < 10240) {
    $error = true;
    $warningMsg = "Image size should be in the range of 15KB to 100KB";
  }
  //check if file type is an image
  if ($imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != "gif") {
    $error = true;
    $warningMsg = "The file is not an image type";
  }

  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $query_run = mysqli_query($con, "UPDATE students_tbl SET passport='$target_file', sname='$sname', lname='$lname', oname='$oname', class_name='$select_class' WHERE admNo = '$admNo'");
    if (!$query_run) {
      $warningMsg = "Update failed";
      //echo '<script>alert(Update failed)</script>';
    } else {
      $successMsg = "Record updated";
      //echo '<script>alert("Record updated")</script>';
    }
  }
}

if (isset($_POST['deleteBtn'])) {
  $admNo = $_POST['admNo'];
  $db = new Database();
  $db->query("SELECT passport FROM students_tbl WHERE admNo = :admNo;");
  $db->bind(':admNo', $admNo);
  $img = $db->single();
  if ($img) {
    $db->query("DELETE FROM students_tbl WHERE admNo = :admNo;");
    $db->bind(':admNo', $admNo);
    if ($db->execute() && unlink($img->passport)) {
      echo "<script>alert('Record deleted');</script>";
    } else {
      echo "<script>alert('Record not deleted!');</script>";
    }
  } else {
    $db->query("DELETE FROM students_tbl WHERE admNo = :admNo;");
    $db->bind(':admNo', $admNo);
    if ($db->execute()) {
      echo "<script>alert('Record deleted');</script>";
    } else {
      echo "<script>alert('Record not deleted!');</script>";
    }
  }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary">Student view Page</h1>
  </div>
  <center>
    <?php
    if (isset($successMsg)) {
    ?>
      <label class="text-success"><i class="fas fa-fw fa-envelope"></i> <?php echo $successMsg; ?></label>
    <?php
    } elseif (isset($warningMsg)) {
    ?>
      <label class="text-danger"><i class="fas fa-fw fa-user-times"></i><?php echo $warningMsg; ?></label>
    <?php
    }
    ?>
  </center>
  <!-- Student Content Row -->
  <form method="POST" action="student-view-page">
    <div class="row ">
      <div class="form-group">
        <div class="col-md-12 col-sm-12 form-inline ">
          <?php
          $db = new Database();
          $db->query("SELECT * FROM class_tbl;");
          $data = $db->resultset();
          ?>
          <select name="select_class" class="form-control" required>
            <option value=""> Select class...</option>
            <?php
            if ($db->rowCount() > 0) {
              foreach ($data as $record) {
            ?>
                <option value="<?php echo $record->class_name; ?>"> <?php echo $record->class_name; ?> </option>
              <?php
              }
            } else {
              $db->Disconect();
              ?>
              <option value=""> No record </option>
            <?php
            }
            ?>
          </select> &nbsp;&nbsp;
          <button name="view_btn" class="btn btn-outline-primary"> View </button>
        </div> <br><br><br>
      </div>
    </div>
  </form>
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-2">
      <h6 class="m-0 font-weight-bold text-primary text-uppercase">Students data</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-resonsive table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <th class="table-primary">#</th>
            <th class="table-primary">Passsport</th>
            <th class="table-primary">Admission No.</th>
            <th class="table-primary">Full name</th>
            <th class="table-primary">Class</th>
            <th class="table-primary">Gender</th>
            <th class="table-primary">D.O.B</th>
            <th class="table-primary">Religion</th>
            <th class="table-primary">Action</th>
          </thead>
          <tbody>
            <?php
            if (isset($_POST['view_btn'])) {
              $select_class = trim($_POST['select_class']);
              $db->query("SELECT * FROM students_tbl WHERE class_name =:class_name;");
              $db->bind(':class_name', $select_class);
              $data = $db->resultset();

              if ($db->rowCount() > 0) {
                $count = 1;
                foreach ($data as $record) {
            ?>
                  <tr>
                    <td> <?php echo $count; ?></td>
                    <td> <img src="<?php if ($record->passport == null) {
                                      echo '../uploads/student_image.jpg';
                                    } else {
                                      echo $record->passport;
                                    } ?>" class="rounded-circle img-fluid" height="50" width="50"> </td>
                    <td> <?php echo $record->admNo; ?> </td>
                    <td> <?php echo $record->sname . " " . $record->lname . " " . $record->oname; ?> </td>
                    <td> <?php echo $record->class_name; ?> </td>
                    <td> <?php echo $record->gender; ?> </td>
                    <td> <?php echo $record->dob; ?> </td>
                    <td> <?php echo $record->religion; ?> </td>
                    <td>
                      <div class="form-inline">
                        <!--Triger Button to edit students -->
                        <form method="POST" action="student-edit-page">
                          <input type="hidden" name="admNo" value="<?php echo $record->admNo; ?>">
                          <button title="Edit record" class="btn m-2 btn-outline-primary btn-sm " name="editBtn"><i class="fas fa-fw fa-edit"></i> </button>
                        </form>
                        <!--Triger Button to delete students data-toggle="modal" data-target="#deleteModal" -->
                        <form method="POST" action="student-view-page">
                          <button title="Delete record" class="btn btn-outline-danger btn-sm " name="deleteBtn"><i class="fa fa-trash" aria-hidden="true"></i> </button>
                          <input type="hidden" name="admNo" value="<?php echo $record->admNo; ?>">
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php
                  $count++;
                }
              } else {
                $db->Disconect();
                ?>
                <tr>
                  <td colspan="8" class="text-center fw-bold">No Record found</td>
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

  <!-- TODO comfirm delete Modal-->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Are you sure to delete?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Click "Delete" <?php echo $_POST['admNo']; ?> to confirm deletion</div>
        <div class="modal-footer">
          <button class="btn btn-default" type="button" data-dismiss="modal">Cancel</button>
          <form method="POST" action="">
            <button class="btn btn-danger" name="delete_btn" type="submit">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div><br /><br /><br />
<!-- /.container-fluid -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>