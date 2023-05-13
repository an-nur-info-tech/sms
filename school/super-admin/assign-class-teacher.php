<?php
include('includes/header.php');

if (isset($_POST['assigning_btn'])) {
  $db = new Database();
  $class_id = $_POST['class_id'];
  $teacher_id = $_POST['teacher_id'];

  $db->query("SELECT * FROM class_tbl WHERE instructor_id =:teacher_id;");
  $db->bind(':teacher_id', $teacher_id);
  if($db->execute())
  {
    if ($db->rowCount() > 0) {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Ooops...";
      $_SESSION['sessionMsg'] = "Class has a teacher!";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "class-page";
    } 
    else 
    {
      $db->query("UPDATE class_tbl SET instructor_id = :teacher_id WHERE class_id = :class_id;");
      $db->bind(':teacher_id', $teacher_id);
      $db->bind(':class_id', $class_id);
      if(!$db->execute()) {
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
        $_SESSION['sessionMsg'] = "Teacher assigned!";
        $_SESSION['sessionIcon'] = "success";
        $_SESSION['location'] = "class-page";
      }
    }
  }
  else
  {
    die($db->getError());
    exit();
  }
  $db->Disconect();
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <div class="align-items-center justify-content-center ">
    <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Class Teacher Assigning Page</h3>
    <p>Please select a Teacher from the select option to assign a class teacher</p>
  </div><br>

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

  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="col-md-9 form-inline">
      <input type="hidden" name="class_id" value="<?php echo $_POST['class_id']; ?>">
      <label for="teacher_id">Select Teacher: </label> &nbsp;&nbsp;
      <select name="teacher_id" class="form-control" required>
        <option value=""> Select Teacher...</option>
        <?php
        $db = new Database();
        $db->query("SELECT * FROM staff_tbl;");
        if (!$db->execute()) {
          die("Error " .$db->getError());
        } else {
          if ($db->rowCount() > 0) {
            $data = $db->resultset();
            foreach ($data as $row) {
        ?>
              <option value="<?php echo $row->staff_id; ?>"> <?php echo $row->fname . " " . $row->sname . " " . $row->oname; ?> </option>
            <?php
            }
          } else {
            ?>
            <option value=""> Teacher Table is empty </option>
        <?php
          }
        }
        ?>
      </select> &nbsp;&nbsp;
      <button name="assigning_btn" class="btn btn-primary"> Assign </button>
    </div><br><br><br><br>
    <a href="class-page" class="btn btn-outline-danger"> Back </a>
  </form>
</div><!-- End of Main Content -->


<?php
include('includes/footer.php');
include('includes/script.php');
?>