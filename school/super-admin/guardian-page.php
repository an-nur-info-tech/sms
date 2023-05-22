<?php
include('includes/header.php');


if (isset($_POST['submit_btn'])) {
  $data = array(
    'student_admNo' => $_POST['admNo'],
    'guardian_name' => $_POST['guardian_name'],
    'guardian_gsm1' => $_POST['guardian_gsm1'],
    'guardian_gsm2' => $_POST['guardian_gsm2'],
    'occupation' => $_POST['occupation'],
    'office_address' => $_POST['office_address'],
    'home_address' => $_POST['home_address'],
    'mother_gsm1' => $_POST['mother_gsm1'],
    'mother_gsm2' => $_POST['mother_gsm2'],
    'email_address' => $_POST['email_address']
  );

  $db = new Database();
  $db->query(
    "INSERT INTO 
    guardian_tbl(student_admNo, guardian_name, guardian_gsm1, guardian_gsm2, occupation, office_address, home_address, mother_gsm1, mother_gsm2, email_address)
    VALUES(:student_admNo, :guardian_name, :guardian_gsm1, :guardian_gsm2, :occupation, :office_address, :home_address, :mother_gsm1, :mother_gsm2, :email_address);
   "
  );
  $db->bind('student_admNo', $data['student_admNo']);
  $db->bind('guardian_name', $data['guardian_name']);
  $db->bind('guardian_gsm1', $data['guardian_gsm1']);
  $db->bind('guardian_gsm2', $data['guardian_gsm2']);
  $db->bind('occupation', $data['occupation']);
  $db->bind('office_address', $data['office_address']);
  $db->bind('home_address', $data['home_address']);
  $db->bind('mother_gsm1', $data['mother_gsm1']);
  $db->bind('mother_gsm2', $data['mother_gsm2']);
  $db->bind('email_address', $data['email_address']);

  if (!$db->execute()) {
    $warningMsg = "Submittion failed!";
  } else {
    $successMsg = "Record submitted!";
  }

  $db->Disconect();
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="align-items-center justify-content-center ">
    <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Guardians Registration Page</h3>
    <p>Enter the student Admission number to add his/her Guardian details</p>
  </div><br>

  <!-- Student Content Row -->
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-row form-inline">
      <div class="col-md-12">
        <?php
        $db = new Database();
        $db->query("SELECT * FROM students_tbl;");
        $data = $db->resultset();
        ?>
        <input class="form-control" name="admNo" list="datalistioptions" placeholder="Admission Number" auto_complete="on" required>
        <datalist id="datalistioptions">
          <?php
          if (!$db->isConnected()) {
            $warningMsg = die("No connection");
          } else {
            if ($db->rowCount() > 0) {
              foreach ($data as $dat) {
          ?>
                <option value="<?php echo $dat->admNo; ?>"> <?php echo $dat->admNo; ?></option>
              <?php
              }
            } else {
              ?>
              <option value=""> No record </option>
          <?php
            }
            $db->Disconect();
          }
          ?>
        </datalist> &nbsp;&nbsp;
        <button name="view_btn" class="btn btn-outline-primary"> View </button><br><br>
      </div>
    </div>
  </form>
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <?php
    if (isset($_POST['view_btn'])) {
      $admNo = $_POST['admNo'];

      $db = new Database();
      $db->query("SELECT * FROM students_tbl AS st JOIN class_tbl ON class_tbl.class_id = st.class_id WHERE admNo =:admNo;");
      $db->bind(':admNo', $admNo);
      $data = $db->resultset();
    ?>
      <table class="table table-bordered table-hover">
        <?php
        if (!$db->isConnected()) {
          $warningMsg = die("No connection");
        } else {
          if ($db->rowCount() > 0) {
            foreach ($data as $dat) {
        ?>
              <thead>
                <tr>
                  <td colspan="3" align="center"> <img src="<?php echo $dat->passport; ?>" height="100" width="100"> </td>
                </tr>
                <tr>
                  <th>Student Name -></th>
                  <td> <?php echo $dat->sname . " " . $dat->lname . " " . $dat->oname . " [ " . $dat->admNo . " ]"; ?></td>
                </tr>
                <tr>
                  <th>Class name -></th>
                  <td> <?php echo $dat->class_name; ?> <input type="hidden" name="admNo" value="<?php echo $dat->admNo; ?>" class="form-control"> </td>
                </tr>
                <tr>
                  <td> <input type="text" name="guardian_name" class="form-control" placeholder="Guardian/Parent name" required> </td>
                  <td> <input type="number" name="guardian_gsm1" class="form-control" placeholder="Guardian GSM 1" required> </td>
                  <td> <input type="number" name="guardian_gsm2" class="form-control" placeholder="Guardian GSM 2"> </td>
                </tr>
                <tr>
                  <td> <input type="text" name="occupation" class="form-control" placeholder="Occupation" required> </td>
                  <td><input type="text" name="office_address" class="form-control" placeholder="Office Address" required></td>
                  <td><input type="text" name="home_address" class="form-control" placeholder="Home address" required></td>
                </tr>
                <tr>
                  <td> <input type="number" name="mother_gsm1" class="form-control" placeholder="Mother  GSM 1" required> </td>
                  <td> <input type="number" name="mother_gsm2" class="form-control" placeholder="Mother  GSM 2"> </td>
                  <td> <input type="email" name="email_address" class="form-control" placeholder="Email" required> </td>
                </tr>
                <tr align="center">
                  <td colspan="3"><button name="submit_btn" class="btn btn-outline-primary"> Submit </button> </td>
                </tr>

              </thead>
            <?php
            }
          } else {
            ?>
            <tr align="center">
              <td colspan="3">No record found for this student </td>
            </tr>
      <?php
          }
        }
        $db->Disconect();
      }
      ?>

      </table>
      <div class="col-md-12">
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
      </div>
  </form>


</div>
<!-- /.container-fluid -->
<?php
include('includes/footer.php');
include('includes/script.php');
?>