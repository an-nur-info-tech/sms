<?php
include('includes/header.php');
include('includes/send-mail.php');

if (isset($_POST['submit_btn'])) {
  $error = false;
  $title = $_POST['title'];
  $email = trim($_POST['email']);
  $user_type = $_POST['user_type'];
  $user_role = $_POST['user_role'];
  $fname = $title." ".trim($_POST['fname']);
  $sname = trim($_POST['sname']);
  $oname = trim($_POST['oname']);

  $hashed_P = password_hash('123654', PASSWORD_BCRYPT);

  //if error free
  if (!$error) {
    //Check if email exist
    $db = new Database();
    $db->query("SELECT email FROM staff_tbl WHERE email =:email LIMIT 1;");
    $db->bind(':email', $email);
    //$db->single();
    if (!$db->execute()) {
      die("No conncection " . $db->getError());
    }
    else 
    {
      if ($db->rowCount() > 0) {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Ooops...";
        $_SESSION['sessionMsg'] = "Email exist!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "staff-reg-page";
      } 
      else {
        //Enter data new data if no record before
        $staff = "stf/" . date('y') . "/";
        $db->query("SELECT id FROM staff_tbl ORDER BY id DESC LIMIT 1;");
        $data = $db->resultset();
        if ($db->rowCount() > 0) {
          foreach ($data as $row) {
            $get_staff = $row->id;
            $getNumber = str_replace($staff, "", $get_staff);
            $id_increase = $getNumber + 1;
            $get_string = str_pad($id_increase, 4, 0, STR_PAD_LEFT);
            $staff_id = $staff.$get_string;
  
            $db->query(
              "INSERT INTO staff_tbl(staff_id, user_type, user_role, email, pwd, fname, sname, oname)
              VALUES(:staff_id, :user_type, :user_role, :email, :hashed_P, :fname, :sname, :oname);"
            );
            $db->bind(':staff_id', $staff_id);
            $db->bind(':user_type', $user_type);
            $db->bind(':user_role', $user_role);
            $db->bind(':email', $email);
            $db->bind(':hashed_P', $hashed_P);
            $db->bind(':fname', $fname);
            $db->bind(':sname', $sname);
            $db->bind(':oname', $oname);
            $db->execute();
            if (($db->rowCount() > 0)) {
              send_mail($staff_id, $email, $fname, $sname, $oname);              
            } else {
              $_SESSION['errorMsg'] = true;
              $_SESSION['errorTitle'] = "Error";
              $_SESSION['sessionMsg'] = "Error occured!";
              $_SESSION['sessionIcon'] = "error";
              $_SESSION['location'] = "staff-reg-page";
            }
          }
        } else {
          //Enter record if the table is empty 
          $staff_id = "stf/" . date('y') . "/0001";
          $db->query(
            "INSERT INTO staff_tbl(staff_id, user_type, user_role, email, pwd, fname, sname, oname)
            VALUES(:staff_id, :user_type, :user_role, :email, :hashed_P, :fname, :sname, :oname);
            "
          );
          $db->bind(':staff_id', $staff_id);
          $db->bind(':user_type', $user_type);
          $db->bind(':user_role', $user_role);
          $db->bind(':email', $email);
          $db->bind(':hashed_P', $hashed_P);
          $db->bind(':fname', $fname);
          $db->bind(':sname', $sname);
          $db->bind(':oname', $oname);
          $db->execute();
          if (($db->rowCount() > 0) && send_mail($staff_id, $email, $fname, $sname, $oname)) {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Success";
            $_SESSION['sessionMsg'] = "Record added with email sent!";
            $_SESSION['sessionIcon'] = "success";
            $_SESSION['location'] = "staff-reg-page";
            
          } else {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Error occured!";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "staff-reg-page";
          }
        }
      }
    }
  }
  $db->Disconect();
}
//Disabling and Enabling users
if (isset($_POST['act_enabled'])) {
  $staff_id = $_POST['staff_id'];
  $db = new Database();
  $db->query("UPDATE staff_tbl SET act_status = 0 WHERE staff_id =:staff_id;");
  $db->bind(':staff_id', $staff_id);
  if (!$db->execute()) {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Error";
    $_SESSION['sessionMsg'] = "Error occured!";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "staff-reg-page";
    die($db->getError());
  } else {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Success";
    $_SESSION['sessionMsg'] = "Account deactivated!";
    $_SESSION['sessionIcon'] = "success";
    $_SESSION['location'] = "staff-reg-page";
  }
  $db->Disconect();
}
if (isset($_POST['act_disabled'])) {
  $staff_id = $_POST['staff_id'];
  $db = new Database();
  $db->query("UPDATE staff_tbl SET act_status = 1 WHERE staff_id =:staff_id;");
  $db->bind(':staff_id', $staff_id);
  if (!$db->execute()) {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Error";
    $_SESSION['sessionMsg'] = "Error occured!";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "staff-reg-page";
    die($db->getError());
  } else {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Success";
    $_SESSION['sessionMsg'] = "Account activated!";
    $_SESSION['sessionIcon'] = "success";
    $_SESSION['location'] = "staff-reg-page";
  }
  $db->Disconect();
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="align-items-center justify-content-center ">
    <h2 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Staff registration Page</h2>
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
  <!-- Register Modal-->
  <div class="modal fade" id="staffReg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Staff registration page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="POST" action="staff-reg-page">
          <div class="modal-body">
            <div class="form-row">
              <div class="col-md-9">
                <div class="form-group">
                  <label>Email:</label>
                  <input type="email" name="email" class="form-control" placeholder="Staff email" required>
                </div>
                <div class="form-group">
                  <label> User Section:</label>
                  <select name="user_type" class="form-control" required>
                    <option value="">User section...</option>
                    <option value="Admin"> Admin </option>
                    <option value="Secondary"> Secondary </option>
                    <option value="Primary"> Primary </option>
                    <option value="Nursery"> Nursery </option>
                  </select>
                </div>
                <div class="form-group">
                  <label> User Role:</label>
                  <select name="user_role" class="form-control" required>
                    <option value="">User Role...</option>
                    <option value="Instructor"> Instructor </option>
                    <option value="Non-Instructor"> Non-Instructor </option>
                    <option value="Principal"> Principal </option>
                    <option value="Vice Principal"> Vice Principal </option>
                    <option value="Exam Officer"> Exam Officer </option>
                  </select>
                </div>
                <div class="form-group">
                  <label> Title:</label>&nbsp;
                  <select name="title" class="form-control" required>
                    <option value="">Select title...</option>
                    <option value="Mr."> Mr. </option>
                    <option value="Mrs."> Mrs. </option>
                    <option value="Miss"> Miss. </option>
                  </select>
                </div>
                <div class="form-group">
                  <input type="text" name="fname" class="form-control mb-3" placeholder="* First name" required>
                </div>
                <div class="form-group">
                  <input type="text" name="sname" onkeypress="enable_staff_btn(this.value)" class="form-control" placeholder="* Surname" required>
                </div>
                <div class="form-group">
                  <input type="text" name="oname" class="form-control" placeholder="Other name">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="submit_btn" disabled class="btn btn-sm btn-primary spinner_btn" onclick="add_spinner()"> Save </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Button trigger modal -->
  <button class="btn btn-outline-primary" data-toggle="modal" data-target="#staffReg">
    Add Staff
  </button><br /><br />

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Staff record</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th class="table-primary">#</th>
              <th class="table-primary">Verification</th>
              <th class="table-primary">Passport</th>
              <th class="table-primary">Staff ID</th>
              <th class="table-primary">Names</th>
              <th class="table-primary">Email</th>
              <th class="table-primary">Section</th>
              <th class="table-primary">Role</th>
              <th class="table-primary">Status</th>
              <th class="table-primary">Gender</th>
              <th class="table-primary">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Fetching Data from the Staff  table -->
            <?php
            $db = new Database();
            $db->query("SELECT * FROM staff_tbl ORDER BY id DESC;");
            $data = $db->resultset();

            if (!$db->isConnected()) {
              die("Error " . $db->getError());
            } else {
              if ($db->rowCount() > 0) {
                $count = 1;
                foreach ($data as $row) {
            ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php if ($row->act_verify == 0) {
                          echo "Not verify";
                        } else {
                          echo "Verified";
                        } ?></td>
                    <td><img src="..\<?php echo $row->passport; ?>" alt="staff image" height="50" width="50"></td>
                    <td><?php echo $row->staff_id; ?></td>
                    <td><?php echo $row->fname . " " . $row->sname . " " . $row->oname; ?></td>
                    <td><?php echo $row->email; ?>
                      <form method="post" action=""><!-- TODO SEND MAIL BUTTON -->
                        <i title="Click to send reg form to user" class="btn btn-outline-primary fa fa-envelope"></i>
                      </form>
                    </td>
                    <td><?php echo $row->user_type; ?></td>
                    <td><?php echo $row->user_role; ?></td>
                    <td>
                      <!-- Users enable and disable-->
                      <form method="POST" action="staff-reg-page">
                        <input type="hidden" name="staff_id" value="<?php echo $row->staff_id; ?>">
                        <?php
                        if ($row->act_status == 0) {
                        ?>
                          <input type="hidden" name="staff_id" value="<?php echo $row->staff_id; ?>">
                          <button type="submit" title="This account has been disabled" name="act_disabled" class="btn btn-sm btn-danger"> <?php echo "Disabled"; ?> </button>
                        <?php
                        }
                        if ($row->act_status == 1) {
                        ?>
                          <input type="hidden" name="staff_id" value="<?php echo $row->staff_id; ?>">
                          <button type="submit" title="This account is enabled" name="act_enabled" class="btn btn-sm btn-primary"> <?php echo "Enabled"; ?> </button>
                        <?php
                        }
                        ?>
                        </button>
                      </form>
                    </td>
                    <td><?php echo $row->gender; ?></td>
                    <td>
                      <form method="" action="">
                        <input type="hidden" value="<?php echo $row->staff_id; ?>">
                        <button title="Click to view" type="submit" class="btn btn-sm btn-outline-default"><i class="fa fa-eye"></i></button>
                        <button title="Click to edit" type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-fw fa-edit"></i></button>
                        <button title="Click to delete" type="submit" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                <?php
                  $count++;
                }
              } else {
                ?>
                <tr>
                  <td>
                    No Record in staff table
                  </td>
                </tr>
            <?php
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
