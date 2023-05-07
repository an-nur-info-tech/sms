<?php
/* if(session_start() == PHP_SESSION_NONE){
  session_start();
} */
//include('includes/security.php');
//include('../database/Database.php');
include('includes/header.php');


/* include('includes/security.php');
include('../database/Database.php');
include('includes/header.php');
include('includes/sidebar.php');
include('includes/navbar.php'); */

?>
<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <div class="d-sm-flex  align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0 font-weight-bold text-primary">Super admin Dashboard [<?php echo $_SESSION['name']; ?>]</h1>
  </div><br>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary text-uppercase">Students data</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th class="table-primary">#</th>
              <th class="table-primary">Passport</th>
              <th class="table-primary">Admission No.</th>
              <th class="table-primary">Names</th>
              <th class="table-primary">Class</th>
              <th class="table-primary">Gender</th>
              <th class="table-primary">D.O.B</th>
              <th class="table-primary">Religion</th>
            </tr>
          </thead>
          <tbody>
            <!-- Fetching Data from the Staff  table -->
            <?php
            $db = new Database();
            //Checking database connection  
            if (!$db->isConnected()) {
              echo $db->getError() . PHP_EOL;
            } else {

              $db->query("SELECT * FROM students_tbl;");
              $record = $db->resultset();
              //var_dump($record->admNo);
              $count = 1;
              if ($db->rowCount() < 0) {
            ?>
                <tr>
                  <td class="fw-bold text-center"> No record in the database </td>
                </tr>
                <?php

              } else {
                foreach ($record as $value) { //Iterate through the object
                  //var_dump($value->admNo);
                ?>
                  <tr>
                    <td><?php echo $count; //$value->student_id; 
                        ?></td>
                    <td><img src="<?php if ($value->passport == null) {
                                    echo "../uploads/student_image.jpg";
                                  } else {
                                    echo $value->passport;
                                  }   ?>" class="rounded-circle" height="50" width="50"></td>
                    <td><?php echo $value->admNo; ?></td>
                    <td><?php echo $value->sname . " " . $value->lname . " " . $value->oname; ?></td>
                    <td><?php echo $value->class_name; ?></td>
                    <td><?php echo $value->gender; ?></td>
                    <td><?php echo $value->dob; ?></td>
                    <td><?php echo $value->religion; ?></td>
                  </tr>
            <?php
                  $count++;
                }
              }
            }
            $db->Disconect();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <br><br><br>
  <br>
</div>
<!-- /.container-fluid -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>