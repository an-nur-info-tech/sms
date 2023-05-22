<?php
include('includes/header.php');
/* if (isset($_POST['deleteBtn'])) {
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
} */
?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary">Student view Page</h1>
  </div>
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
  <!-- Student Content Row -->
  <form method="POST" action="student-view-page">
    <div class="form-row ">
      <div class="col-md-4">
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
      <div class="col-md-4">
        <div class="form-group">
          <button name="view_btn" class="btn btn-outline-primary"> View </button>
        </div>
      </div>
      <div class="col-md-4">
      </div>
    </div>
  </form>

  <form action="export" method="post" target="_blank">
    <div class="form-row">
      <div class="col-md-4">
        <div class="form-group">
          <select name="class_id" class="form-control" required>
            <option value=""> Select class...</option>
            <option value="all"> All record</option>
            <?php
            $db = new Database();
            $db->query("SELECT * FROM class_tbl;");
            $data = $db->resultset();
            if ($db->rowCount() > 0) {
              foreach ($data as $record) {
            ?>
                <option value="<?php echo $record->class_id; ?>"> <?php echo $record->class_name; ?> </option>
              <?php
              }
            } else {
              $db->Disconect();
              ?>
              <option value=""> No record </option>
            <?php
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <select name="f_format" class="form-control" required>
            <option value="">Format...</option>
            <option value="xlsx">xlsx</option>
            <option value="xls">xls</option>
            <option value="csv">csv</option>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <button name="export_btn" class="btn btn-outline-primary"> Export </button>
        </div>
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
              $class_id = trim($_POST['select_class']);
              $db->query(
                "SELECT * FROM students_tbl AS st
                JOIN class_tbl ON class_tbl.class_id = st.class_id WHERE st.class_id = :class_id;");
              $db->bind(':class_id', $class_id);
              
              if($db->execute())
              {
                if ($db->rowCount() > 0) {
                  $data = $db->resultset();
                  $count = 1;
                  foreach ($data as $record) {
              ?>
                    <tr>
                      <td> <?php echo $count; ?></td>
                      <td> <img src="<?php if ($record->passport == null || empty($record->passport)) {
                                        echo '../uploads/student_image.jpg';
                                      } else {
                                        echo $record->passport;
                                      } ?>" class="rounded-circle" height="50" width="50"> </td>
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
                  ?>
                  <tr>
                    <td colspan="8" class="text-center fw-bold">No Record found</td>
                  </tr>
              <?php
                  $db->Disconect();
                }
              }else{
                die($db->getError());
              }
            }else{
              ?>
              <tr>
                <td colspan="9" class="text-center"> Select a class and click on View to view its record</td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

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