<?php
include('includes/header.php');

if (isset($_POST['assigning_btn'])) {
  $db = new Database();
  $class_id = $_POST['class_id'];
  $teacher_class = $_POST['teacher_class'];

  $db->query("SELECT * FROM class_tbl WHERE instructor_id =:teacher_class;");
  $db->bind(':teacher_class', $teacher_class);
  $teacher_nums = $db->single();  //mysqli_num_rows($check_teacher);
  if ($db->rowCount() > 0) {
    $warningMsg = "Teacher assigned to a class already";
  } else {
    $db->query("UPDATE class_tbl SET instructor_id = :teacher_class WHERE class_id = :class_id;");
    $db->bind(':teacher_class', $teacher_class);
    $db->bind(':class_id', $class_id);
    if (!$db->execute()) {
      die("Error " . $db->getError());
    } else {
      $successMsg = "Teacher Assigned successfully";
    }
  }
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <div class="align-items-center justify-content-center ">
    <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Class Teacher Assigning Page</h3>
    <p>Please select a Teacher from the select option to assign a class teacher</p>
  </div><br>

  <center>
    <?php
    if (isset($successMsg)) {
    ?>
      <div class="alert alert-success">
        <span class="glyphicon glyphicon-saved"></span>
        <?php echo $successMsg; ?>
      </div>
    <?php
    } elseif (isset($warningMsg)) {
    ?>
      <div class="alert alert-warning">
        <span class="glyphicon glyphicon-ban-circle"></span>
        <?php echo $warningMsg; ?>
      </div>
    <?php
    }
    ?>
  </center>

  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="col-md-9 form-inline">
      <input type="hidden" name="class_id" value="<?php $class_id = $_POST['class_id'];
                                                  echo  $class_id; ?>">
      <!-- Fetching data from staff table -->
      <?php
      $db = new Database();
      $db->query("SELECT * FROM staff_tbl;");
      $data = $db->resultset();
      ?>
      <label for="teacher_class">Select Teacher: </label> &nbsp;&nbsp;
      <select name="teacher_class" class="form-control" required>
        <option value=""> Select Teacher...</option>
        <?php
        if (!$db->isConnected()) {
          die("Error " . $db->getError());
        } else {
          if ($db->rowCount() > 0) {
            //$class_id = $row->class_id;
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
    <a href="class-page.php" class="btn btn-outline-danger"> Back </a>
  </form>
</div><!-- End of Main Content -->


<?php
include('includes/footer.php');
include('includes/script.php');
?>