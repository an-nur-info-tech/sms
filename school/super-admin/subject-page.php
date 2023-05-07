<?php
include('includes/header.php');
$db = new Database();
if (isset($_POST['subject_btn'])) {
  $subject_name = strtoupper(mysqli_real_escape_string($con, $_POST['subject_name']));
  $sql = mysqli_query($con, "INSERT INTO subject_tbl(subject_name) VALUES('$subject_name')");

  if (!$sql) {
    die($warningMsg = "Error occured " . mysqli_error($con));
  } else {
    $successMsg = "Submition Successfully";
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
  <?php
  $db->query("SELECT * FROM subject_tbl;");
  $data = $db->resultset();
  ?>
  <!-- Class Table-->
  <table class="table table-bordered ">
    <thead>
      <th class="table-primary"> S/N </th>
      <th class="table-primary"> Subjects </th>
      <th class="table-primary"> Actions </th>
    </thead>
    <tbody>
      <?php
      if (!$db->isConnected()) {
        die("Error " . $db->getError());
      } else {
        if ($db->rowCount() > 0) {
          $count = 1;
          foreach ($data as $row) {
            //$row->subject_id = $numbering ++;
      ?>
            <tr>
              <td> <?php echo $count; ?> </td>
              <td> <?php echo $row->subject_name; ?> </td>
              <td>
                <button class="btn btn-sm btn-primary " alt="edit" name="edit_btn"> <i class="fas fa-fw fa-edit"></i> </button>
                <button class="btn btn-sm btn-danger" name="delete_btn"> <i class="fas fa-fw fa-trash-alt"></i> </button>
              </td>
            </tr>
          <?php
            $count++;
          }
        } else {
          ?>
          <tr>
            <td>
              Subject Table is empty
            </td>
          </tr>
      <?php
        }
      }
      ?>
    </tbody>
  </table>
</div><!-- End of Main Content -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>