<?php
include('includes/header.php');

$db = new Database();


if (isset($_POST['class_btn'])) {
  $class_name = strtoupper($_POST['class_name']);
  //Check if class exist
  $db->query("SELECT * FROM class_tbl WHERE class_name = :class_name;");
  $db->bind(':class_name', $class_name);
  if ($db->execute()) {
    if ($db->rowCount() > 0) {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Ooops...";
      $_SESSION['sessionMsg'] = "Class existed!";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "class-page";
    } else {
      $db->query("INSERT INTO class_tbl(class_name) VALUES(:class_name);");
      $db->bind(':class_name', $class_name);

      if (!$db->execute()) {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Error occured!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "class-page";
        die($db->getError());
      } else {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Success";
        $_SESSION['sessionMsg'] = "Record added!";
        $_SESSION['sessionIcon'] = "success";
        $_SESSION['location'] = "class-page";
      }
    }
  } else {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Error";
    $_SESSION['sessionMsg'] = "Error occured!";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "class-page";
    die($db->getError());
  }
}
//TODO
if (isset($_POST['assignClassBtn'])) {
  
  $class_id = $_POST['assignClassID'];
  $teacher_id = $_POST['teacher_id'];

  $db->query("SELECT * FROM class_tbl WHERE instructor_id =:teacher_id;");
  $db->bind(':teacher_id', $teacher_id);
  if($db->execute())
  {
    if ($db->rowCount() > 0) {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Ooops...";
      $_SESSION['sessionMsg'] = "Teacher has been assigned to another class!";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "class-page";
    } 
    else 
    {
      $db->query("UPDATE class_tbl SET instructor_id = :teacher_id WHERE class_id = :class_id;");
      $db->bind(':teacher_id', $teacher_id);
      $db->bind(':class_id', $class_id);
      if(!$db->execute()) {
        die($db->getError());
      } 
      else 
      {
        if ($db->rowCount() > 0)
        {
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Success";
          $_SESSION['sessionMsg'] = "Teacher assigned!";
          $_SESSION['sessionIcon'] = "success";
          $_SESSION['location'] = "class-page";
        }
        else 
        {
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Error";
          $_SESSION['sessionMsg'] = "Error occured!";
          $_SESSION['sessionIcon'] = "error";
          $_SESSION['location'] = "class-page";
        }
      }
    }
  }
  else
  {
    die($db->getError());
  }
}


if (isset($_POST['rmAssignClassBtn'])) {
  $classID = $_POST['rmAssignClassID'];
  
  $db->query("UPDATE class_tbl SET instructor_id = null WHERE class_id = :classID;");
  $db->bind(':classID', $classID);
  if (!$db->execute()) {
    die($db->getError());
  } else {
    if ($db->rowCount() > 0)
    {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Success";
      $_SESSION['sessionMsg'] = "Teacher removed";
      $_SESSION['sessionIcon'] = "success";
      $_SESSION['location'] = "class-page";
    }
    else 
    {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Error";
      $_SESSION['sessionMsg'] = "Something went wrong";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "class-page";
    }
  }
}

if (isset($_POST['updateClassBtn'])) {

  $editClassID = $_POST['editClassID'];
  $editClassName = strtoupper(trim($_POST['editClassName']));

  // Check if class name does not exist
  $db->query("SELECT * FROM class_tbl WHERE class_name = :editClassName;");
  $db->bind(':editClassName', $editClassName);
  if (!$db->execute()) {
    die($db->getError());
  } else {
    if ($db->rowCount() > 0) {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Ooops...";
      $_SESSION['sessionMsg'] = "The class name exist";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "class-page";
    } else {
      //Updating record
      $db->query("UPDATE class_tbl SET class_name = :editClassName WHERE class_id = :editClassID;");
      $db->bind(':editClassID', $editClassID);
      $db->bind(':editClassName', $editClassName);
      if (!$db->execute()) {
        die($db->getError());
      } else {
        if ($db->rowCount()  > 0)
        {
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Success";
          $_SESSION['sessionMsg'] = "Record updated!";
          $_SESSION['sessionIcon'] = "success";
          $_SESSION['location'] = "class-page";
        }
        else 
        {
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Error";
          $_SESSION['sessionMsg'] = "Something went wrong";
          $_SESSION['sessionIcon'] = "error";
          $_SESSION['location'] = "class-page";
        }
      }
    }
  }
}

if (isset($_POST['deleteClassBtn'])) {
  $userID = $_POST['userID'];
  $deleteClassID = $_POST['deleteClassID'];

  $lookup = $_SESSION['staff_id']." deLEtED";
  $search = $userID;

  if ($search == $lookup) //Check if user input is equal to the lookup
  {
    $db->query("DELETE FROM class_tbl WHERE class_id = :deleteClassID;");
    $db->bind(':deleteClassID', $deleteClassID);
    if (!$db->execute()) {
      die($db->getError());
    } else {
      if ($db->rowCount() > 0)
      {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Success";
        $_SESSION['sessionMsg'] = "Record deleted";
        $_SESSION['sessionIcon'] = "success";
        $_SESSION['location'] = "class-page";
      }
      else 
      {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Something went wrong";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "class-page";
      }
    }
  }
  else
  {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Ooops..";
    $_SESSION['sessionMsg'] = "Input does not match, case are sensitive";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "class-page";
  }
}
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
        <button name="class_btn" onclick="add_spinner()" class="btn btn-primary spinner_btn"> Submit </button>
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

            $db->query("SELECT * FROM class_tbl ORDER BY class_id DESC;");
            if (!$db->execute()) {
              die("Error " . $db->getError());
            } else {
              if ($db->rowCount() > 0) {
                $data = $db->resultset();
                $count = 1;
                foreach ($data as $row) {
            ?>
                  <tr>
                    <td> <?php echo $count;  ?> </td>
                    <td> <?php echo $row->class_name; ?> </td>
                    <td>
                      <!-- Converting staff id to its name-->
                      <?php
                      if (($row->instructor_id == null) || ($row->instructor_id == "")) {
                        echo "Class teacher not assign, click on Assign button to assign.";
                      } else {
                        $staff_id = $row->instructor_id;
                        $db->query("SELECT * FROM staff_tbl WHERE staff_id = :staff_id;");
                        $db->bind(':staff_id', $staff_id);
                        $db->execute();
                        $dats = $db->single();
                        echo "$dats->fname $dats->sname $dats->oname &nbsp;&nbsp;&nbsp;<span class='text-danger rmAssign_btn' rmAssignClassID='$row->class_id' data-toggle='modal' data-target='#rmAssignClass'  title='Click to remove teacher' name='assign_btn'><i class='fas fa-fw fa-trash fa-sm'></i> </span>";
                      }
                      ?>
                    </td>
                    <td>
                      <div class="form-row">
                        <!-- <form method="POST" action="assign-class-teacher"> -->
                          <!-- <input type="hidden" name="class_id" value="<?php //echo $row->class_id; ?>"> -->
                        <!--Triger Button to Assign class teacher TODO  -->
                        <button class="btn btn-outline-primary btn-sm assign_btn" assignClassID="<?php echo $row->class_id; ?>" data-toggle="modal" data-target="#assignClass"  title="Click to assign teacher" name="assign_btn"> Assign </button>
                        <!-- </form> &nbsp; -->
                        <!--Triger Button to edit  -->
                        &nbsp;<button name="edit_btn" title="Edit class" editClassID="<?php echo $row->class_id; ?>" data-toggle="modal" data-target="#editClass" class="btn btn-outline-primary btn-sm editClass"><i class="fas fa-fw fa-edit"></i> </button>
                        &nbsp;
                        <!--Triger Button to Delete  -->
                        <button name="delete_btn" title="Delete class" deleteClassID="<?php echo $row->class_id; ?>" data-toggle="modal" data-target="#deleteClass" class="btn btn-outline-danger btn-sm deleteClassID"><i class="fas fa-fw fa-trash"></i> </button>
                      </div>
                    </td>
                  </tr>
                <?php
                  $count++;
                }
              } else {
                ?>
                <tr>
                  <td colspan="4" class="text-center">No record found (Input class name and click Submit to register class)</td>
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

  <!-- Assign Class teacher Modal-->
  <div class="modal fade" id="assignClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Assign class teacher</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="class-page">
          <div class="modal-body">
            <p>Please select a teacher from the dropdown select to assign to a class </p>
            <select name="teacher_id" id="teacher_id" class="form-control" required>
              <!-- <option value=""> Select Teacher...</option> -->
            </select>
            <input type="hidden" id="assignClassID" name="assignClassID" value="" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="assignClassBtn" class="btn btn-sm btn-primary"> Assign </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Class Modal-->
  <div class="modal fade" id="editClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit class page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="class-page">
          <div class="modal-body">
            <div class="col-md-12">
              <div class="form-row form-inline">
                <div class="form-group">
                  <label>Class name:</label> &nbsp;
                  <input type="text" id="editClassName" name="editClassName" value="" class="form-control" required>
                  <input type="hidden" id="editClassID" name="editClassID" value="" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="updateClassBtn" class="btn btn-sm btn-primary"> Update </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Class Modal-->
  <div class="modal fade" id="deleteClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete class page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="class-page">
          <div class="modal-body">
            <p>Are you sure to delete this class? if yes, type in your staff ID followed by deLEtED e.g stf/21/0001 deLEtED</p>
            <input type="text" name="userID" autocomplete="off" placeholder="stf/21/0001 deLEtED"  class="form-control" required>
            <input type="hidden" id="deleteClassID" name="deleteClassID" value="" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="deleteClassBtn" class="btn btn-sm btn-primary"> Yes </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Remove Assign Class teacher Modal-->
  <div class="modal fade" id="rmAssignClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Remove Class Teacher Page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="class-page">
          <div class="modal-body">
            <p>Are you sure you want to remove the class teacher?</p>
            <input type="hidden" id="rmAssignClassID" name="rmAssignClassID" value="" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="rmAssignClassBtn" class="btn btn-sm btn-primary"> Yes </button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->
<?php
$db->Disconect();
include('includes/footer.php');
include('includes/script.php');
?>