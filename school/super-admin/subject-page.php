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

  <?php
  $db->query("SELECT * FROM subject_tbl;");
  $data = $db->resultset();
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
                        <form action="" method="post">
                          <button class="btn btn-sm btn-outline-primary " alt="edit" name="edit_btn"> <i class="fas fa-fw fa-edit"></i> Edit</button>
                        </form> &nbsp;
                        <form action="" method="post">
                          <button class="btn btn-sm btn-outline-danger" name="delete_btn"> <i class="fas fa-fw fa-trash-alt"></i> Delete</button>
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
      </div>
    </div>
  </div>
</div><!-- End of Main Content -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>