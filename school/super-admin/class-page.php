<?php
include('includes/header.php');

$db = new Database();
  

if (isset($_POST['class_btn'])) {
  $class = strtoupper($_POST['class_name']);
  $db->query("INSERT INTO class_tbl(class_name) VALUES(:class);");
  $db->bind(':class', $class);

  if (!$db->execute()) {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Error";
    $_SESSION['sessionMsg'] = "Error occured!";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "class-page";
    die($db->getError());
  } 
  else 
  {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Success";
    $_SESSION['sessionMsg'] = "Record added!";
    $_SESSION['sessionIcon'] = "success";
    $_SESSION['location'] = "class-page";
  }
}
/* if (isset($_POST['update_btn'])) {
  $class_id = $_POST['class_id'];
  $class_name = strtoupper(mysqli_real_escape_string($con, $_POST['class_name']));

  $query_run = mysqli_query($con, "UPDATE class_tbl SET class_name='$class_name' WHERE class_id = '$class_id'");
  if (!$query_run) {
    $warningMsg = "Update failed " . mysqli_error($con);
  } else {
    $successMsg = "Record Updated";
  }
} */

/* if (isset($_POST['delete_btn'])) {
  $class_id = $_POST['class_id'];

  $query_run = mysqli_query($con, "DELETE FROM class_tbl WHERE class_id = '$class_id'");
  if (!$query_run) {
    $warningMsg = "Deletion failed " . mysqli_error($con);
  } else {
    $successMsg = "Record Deleted";
  }
} */
?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="align-items-center justify-content-center ">
    <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px"> Class Registration Page</h3>
  </div><br>

  <!-- CLASS Content Row -->
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-row form-inline">
      <div class="form-group col-md-12">
        <label class="control-label" for="class_name"> Class name: </label> &nbsp;
        <input type="text" name="class_name" class="form-control" placeholder="Enter Class name" autocomplete="off" required>
        &nbsp;&nbsp;
        <button name="class_btn" class="btn btn-primary"> Submit </button>
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
      <h6 class="m-0 font-weight-bold text-primary text-uppercase">Class data</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <!-- Class Table-->
        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="table-primary">
            <th>
              <h5>#</h5>
            </th>
            <th>
              <h5>Names</h5>
            </th>
            <th>
              <h5>Class Teacher's </h5>
            </th>
            <th class="text-center">
              <h5>Actions</h5>
            </th>
          </thead>
          <tbody>
            <?php

            $db->query("SELECT * FROM class_tbl;");
            $data = $db->resultset();
            if (!$db->isConnected()) {
              die("Error " . $db->getError());
            } else {
              if ($db->rowCount() > 0) {
                $count = 1;
                foreach ($data as $row) {
            ?>
                  <tr>
                    <td> <?php echo $count;  ?> </td>
                    <td> <?php echo $row->class_name; ?> </td>
                    <td>

                      <!-- Converting staff id to its name-->
                      <?php
                      if ($row->instructor_id == null) {
                        echo "Class teacher not assign";
                      } else {
                        $staff_id = $row->instructor_id;
                        $db->query("SELECT * FROM staff_tbl WHERE staff_id = :staff_id;");
                        $db->bind(':staff_id', $staff_id);
                        $dats = $db->resultset();
                        foreach ($dats as $dat) {
                          echo $dat->fname . " " . $dat->sname . " " . $dat->oname;
                        }
                      }
                      $count++;
                      ?>
                    </td>
                    <td>
                      <div class="form-inline">
                        <form method="POST" action="assign-class-teacher">
                          <input type="hidden" name="class_id" value="<?php echo $row->class_id;  ?>">
                          <button class="btn btn-outline-primary btn-sm" title="Click to assign teacher" name="assign_btn"> Assign </button>
                        </form> &nbsp;
                        <form method="POST" action="">
                          <input type="hidden" name="class_id" value="<?php echo $row->class_id;  ?>">
                          <!--Triger Button to edit  -->
                          <button name="edit_btn" title="Edit record" class="btn btn-outline-primary btn-sm"><i class="fas fa-fw fa-edit"></i> Edit</button>
                        </form>&nbsp;
                        <form method="POST" action="">
                          <input type="hidden" name="class_id" value="<?php echo $row->class_id;  ?>">
                          <!--Triger Button to Delete  -->
                          <button name="delete_btn" title="Delete record" class="btn btn-outline-danger btn-sm"><i class="fas fa-fw fa-trash"></i> Delete </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php
                }
              } else {
                ?>
                <tr>
                  <td>No record found</td>
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



</div>
<!-- /.container-fluid -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>