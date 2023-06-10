<?php
include('includes/header.php');
include('includes/send-mail.php');

if (isset($_POST['submit_btn'])) {
  $error = false;
  $title = $_POST['title'];
  $email = trim($_POST['email']);
  $user_type = $_POST['user_type'];
  $user_role = $_POST['user_role'];
  $fname = $title . " " . trim($_POST['fname']);
  $sname = trim($_POST['sname']);
  $oname = trim($_POST['oname']);
  $dob = $_POST['dob'];
  $gender = $_POST['gender'];
  $staff_state = trim($_POST['staff_state']);
  $lga = trim($_POST['lga']);
  $gsm1 = $_POST['gsm1'];
  $gsm2 = $_POST['gsm2'];
  $appointment_date = $_POST['appointment_date'];
  $addr = trim($_POST['addr']);

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
    } else {
      if ($db->rowCount() > 0) {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Ooops...";
        $_SESSION['sessionMsg'] = "Email exist!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "staff-reg-page";
      } else {
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
            $staff_id = $staff . $get_string;

            $db->query(
              "INSERT INTO staff_tbl(staff_id, user_type, user_role, email, pwd, fname, sname, oname, dob, gender, staff_state, lga, year_joined, home_address, gsm1, gsm2)
              VALUES(:staff_id, :user_type, :user_role, :email, :hashed_P, :fname, :sname, :oname, :dob, :gender, :staff_state, :lga, :appointment_date, :home_address, :gsm1, :gsm2);"
            );
            $db->bind(':staff_id', $staff_id);
            $db->bind(':user_type', $user_type);
            $db->bind(':user_role', $user_role);
            $db->bind(':email', $email);
            $db->bind(':hashed_P', $hashed_P);
            $db->bind(':fname', $fname);
            $db->bind(':sname', $sname);
            $db->bind(':oname', $oname);
            $db->bind(':dob', $dob);
            $db->bind(':gender', $gender);
            $db->bind(':staff_state', $staff_state);
            $db->bind(':lga', $lga);
            $db->bind(':appointment_date', $appointment_date);
            $db->bind(':home_address', $addr);
            $db->bind(':gsm1', $gsm1);
            $db->bind(':gsm2', $gsm2);

            if ($db->execute()) {
              if (($db->rowCount() > 0)) {
                send_mail($staff_id, $email);
              } else {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Error";
                $_SESSION['sessionMsg'] = "Error occured!";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "staff-reg-page";
              }
            } else {
              die($db->getError());
            }
          }
        } else {
          //Enter record if the table is empty 
          $staff_id = "stf/" . date('y') . "/0001";
          $db->query(
            "INSERT INTO staff_tbl(staff_id, user_type, user_role, email, pwd, fname, sname, oname, dob, gender, staff_state, lga, year_joined, home_address, gsm1, gsm2)
            VALUES(:staff_id, :user_type, :user_role, :email, :hashed_P, :fname, :sname, :oname, :dob, :gender, :staff_state, :lga, :appointment_date, :home_address, :gsm1, :gsm2);"
          );
          $db->bind(':staff_id', $staff_id);
          $db->bind(':user_type', $user_type);
          $db->bind(':user_role', $user_role);
          $db->bind(':email', $email);
          $db->bind(':hashed_P', $hashed_P);
          $db->bind(':fname', $fname);
          $db->bind(':sname', $sname);
          $db->bind(':oname', $oname);
          $db->bind(':dob', $dob);
          $db->bind(':gender', $gender);
          $db->bind(':staff_state', $staff_state);
          $db->bind(':lga', $lga);
          $db->bind(':appointment_date', $appointment_date);
          $db->bind(':home_address', $addr);
          $db->bind(':gsm1', $gsm1);
          $db->bind(':gsm2', $gsm2);
          if ($db->execute()) {
            if (($db->rowCount() > 0)) {
              send_mail($staff_id, $email);
            } else {
              $_SESSION['errorMsg'] = true;
              $_SESSION['errorTitle'] = "Error";
              $_SESSION['sessionMsg'] = "Error occured!";
              $_SESSION['sessionIcon'] = "error";
              $_SESSION['location'] = "staff-reg-page";
            }
          } else {
            die($db->getError());
          }
        }
      }
    }
  }
  $db->Disconect();
}

// Resend activation link
if (isset($_POST['activation_link_btn'])) {
  $db = new Database();

  $email_link_txt = trim($_POST['email_link_txt']);
  $email2change = $_POST['link2change'];
  $staff_id = $_POST['staff_id_lnk'];

  // Check if email equal to email to change
  if ($email_link_txt == $email2change) {
    send_mail($staff_id, $email_link_txt);
  } else {
    // Check if email exit
    $db->query("SELECT * FROM staff_tbl WHERE email= :email_link_txt;");
    $db->bind(':email_link_txt', $email_link_txt);
    if ($db->execute()) {
      if ($db->rowCount() > 0) {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Ooops...";
        $_SESSION['sessionMsg'] = "Email exist in database";
        $_SESSION['sessionIcon'] = "warning";
        $_SESSION['location'] = "staff-reg-page";
      } else {
        // Update email and send a mail
        $db->query("UPDATE staff_tbl SET email = :email_link_txt WHERE email= :email2change;");
        $db->bind(':email_link_txt', $email_link_txt);
        $db->bind(':email2change', $email2change);
        if ($db->execute()) {
          if ($db->rowCount() > 0) {
            send_mail($staff_id, $email);
          } else {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Error occured!";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "staff-reg-page";
          }
        } else {
          die($db->getError());
        }
      }
    } else {
      die($db->getError());
    }
  }
  $db->Disconect();
}
// Disable User 
if (isset($_POST['act_enabled'])) {
  $db = new Database();

  $current_user = $_SESSION['staff_id'];
  $staff_id = $_POST['staff_id'];

  // Check if user is not disabling itself
  if ($current_user == $staff_id) {
    echo
    "<script>
      alert('You can not disable yourself');
    </script>";
  } else {
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
      $_SESSION['sessionMsg'] = "Account disabled!";
      $_SESSION['sessionIcon'] = "success";
      $_SESSION['location'] = "staff-reg-page";
    }
  }
  $db->Disconect();
}
// Enabling User
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
    $_SESSION['sessionMsg'] = "Account enabled!";
    $_SESSION['sessionIcon'] = "success";
    $_SESSION['location'] = "staff-reg-page";
  }
  $db->Disconect();
}

// Send mail
if (isset($_POST['send_user_mail_btn'])) {
  if (isset($_POST['cc'])) {
    $sender = $_SESSION['user-email'];
    $sender_name = $_SESSION['name'];
    $mail_subject = $_POST['mail_subject'];
    $recipient = trim($_POST['user_mail']);
    $message = $_POST['message'];
    $mail_cc = trim($_POST['cc']);
  } else {
    $sender = $_SESSION['user-email'];
    $sender_name = $_SESSION['name'];
    $mail_subject = $_POST['mail_subject'];
    $recipient = trim($_POST['user_mail']);
    $message = $_POST['message'];
    $mail_cc = null;
  }

  send_user_mail($sender, $sender_name, $recipient, $mail_subject, $mail_cc, $message);
}

//  Staff update
if (isset($_POST['update_staff_btn'])) {
  $db = new Database();

  $current_user = $_SESSION['staff_id'];
  $staff_id = $_POST['edit_staff_id'];
  $staff_email = $_POST['staff_email'];
  $staff_section = $_POST['staff_section'];
  $staff_role = $_POST['staff_role'];

  if (($current_user == $staff_id) && ($staff_section !== "Super Admin")) {
    echo "<script>alert('You can not be removed from Super Admin');</script>";
  } else {
    $db->query("UPDATE staff_tbl SET user_type = :staff_section, user_role = :staff_role WHERE email = :email;");
    $db->bind(':staff_section', $staff_section);
    $db->bind(':staff_role', $staff_role);
    $db->bind(':email', $staff_email);
    if ($db->execute()) {
      if ($db->rowCount() > 0) {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Success";
        $_SESSION['sessionMsg'] = "User updated";
        $_SESSION['sessionIcon'] = "success";
        $_SESSION['location'] = "staff-reg-page";
      } else {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Error occured";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "staff-reg-page";
      }
    } else {
      die($db->getError());
    }
  }
  $db->Disconect();
}

// Staff delete
if (isset($_POST['deleteStaffBtn'])) {
  $current_user = $_SESSION['staff_id'];
  $userID = $_POST['userID'];
  $staff_ID = $_POST['staff_ID'];
  $lookup = $_SESSION['staff_id']." deLEtED";

  if ($userID == $lookup) //Check if user input is equal to the lookup
  {

    if ($current_user == $staff_ID){
      echo "<script>alert('You can not delete yourself');</script>";
    }else{
      
      $db = new Database();
      $db->query("UPDATE staff_tbl SET deleted = 1 WHERE staff_id = :staff_ID;");
      $db->bind(':staff_ID', $staff_ID);
      if (!$db->execute()) {
        die($db->getError());
      } else {
        if ($db->rowCount() > 0)
        {
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Success";
          $_SESSION['sessionMsg'] = "Record deleted";
          $_SESSION['sessionIcon'] = "success";
          $_SESSION['location'] = "staff-reg-page";
        }
        else 
        {
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Error";
          $_SESSION['sessionMsg'] = "Something went wrong";
          $_SESSION['sessionIcon'] = "error";
          $_SESSION['location'] = "staff-reg-page";
        }
      }
      $db->Disconect();
    }
  }
  else
  {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Ooops..";
    $_SESSION['sessionMsg'] = "Input does not match, case are sensitive";
    $_SESSION['sessionIcon'] = "warning";
    $_SESSION['location'] = "staff-reg-page";
  }
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
        <form method="post" action="staff-reg-page">
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
                    <option value="Super Admin"> Super Admin </option>
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
                  <input type="text" name="sname" class="form-control" placeholder="* Surname" required>
                </div>
                <div class="form-group">
                  <input type="text" name="oname" class="form-control" placeholder="Other name">
                </div>
                <div class="form-group">
                  <label for="dob">* DOB:</label>
                  <input type="date" name="dob" class="form-control" required>
                </div>
                <div class="form-group">
                  <label for="gender">* Gender:</label>
                  <select class="form-control" name="gender" required>
                    <option value=""> Select gender...</option>
                    <option value="Male"> Male</option>
                    <option value="Female"> Female</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="state"> * State: </label>
                  <input class="form-control" name="staff_state" list="datalistioption2" placeholder="User state" auto_complete="off" required>
                  <datalist id="datalistioption2">
                    <option value=""> </option>
                    <option value="Abia"> Abia </option>
                    <option value="Adamawa"> Adamawa </option>
                    <option value="Akwa Ibom"> Akwa Ibom </option>
                    <option value="Anambra"> Anambra </option>
                    <option value="Bauchi"> Bauchi </option>
                    <option value="Bayelsa"> Bayelsa </option>
                    <option value="Benue"> Benue </option>
                    <option value="Borno"> Borno </option>
                    <option value="Cross River"> Cross River </option>
                    <option value="Delta"> Delta </option>
                    <option value="Ebonyi"> Ebonyi </option>
                    <option value="Edo"> Edo </option>
                    <option value="Ekiti"> Ekiti</option>
                    <option value="Enugu"> Enugu </option>
                    <option value="Gombe"> Gombe </option>
                    <option value="Imo"> Imo </option>
                    <option value="Jigawa"> Jigawa </option>
                    <option value="Kaduna"> Kaduna </option>
                    <option value="Kano"> Kano </option>
                    <option value="Katsina"> Katsina </option>
                    <option value="Kebbi"> Kebbi </option>
                    <option value="Kogi"> Kogi </option>
                    <option value="Kwara"> Kwara </option>
                    <option value="Lagos"> Lagos </option>
                    <option value="Nasarawa"> Nasarawa </option>
                    <option value="Niger"> Niger </option>
                    <option value="Ogun"> Ogun </option>
                    <option value="Ondo"> Ondo </option>
                    <option value="Osun"> Osun </option>
                    <option value="Oyo"> Oyo </option>
                    <option value="Plateau"> Plateau </option>
                    <option value="Rivers"> Rivers </option>
                    <option value="Sokoto"> Sokoto </option>
                    <option value="Taraba"> Taraba </option>
                    <option value="Yobe"> Yobe </option>
                    <option value="Zamfara"> Zamfara </option>
                    <option value="FCT"> FCT </option>
                  </datalist>
                </div>
                <div class="form-group">
                  <label for="LGA">* LGA:</label>
                  <input type="text" name="lga" id="lga" placeholder="Local government area" autocomplete="off" class="form-control">
                </div>
                <div class="form-group">
                  <label for="gsm">* Mobile no.:</label>
                  <input type="number" name="gsm1" id="gsm1" placeholder="Mobile number" class="form-control">
                </div>
                <div class="form-group">
                  <label for="gsm"> Alternate mobile no.:</label>
                  <input type="number" name="gsm2" placeholder="Mobile number" class="form-control">
                </div>
                <div class="form-group">
                  <label for="appointment_date"> * Appointment date:</label>
                  <input type="date" name="appointment_date" placeholder="Appointment date" class="form-control" required>
                </div>
                <div class="form-group">
                  <label for="address">* Addres:</label>
                  <textarea name="addr" onkeypress="enable_staff_btn(this.value)" placeholder="User address information" class="form-control"></textarea>
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
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <!-- Button trigger modal -->
        <button class="btn btn-outline-primary my-3" data-toggle="modal" data-target="#staffReg">
          Add Staff
        </button>
      </div>
      <div class="form-group">
        <small class="float-right text-danger">Note: If user does not receive activation link, click on the <strong>Not verified</strong></small>
      </div>
    </div>

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
                <th class="table-primary">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Fetching Data from the Staff  table -->
              <?php
              $db = new Database();
              $db->query("SELECT * FROM staff_tbl WHERE deleted != 1 ORDER BY id DESC;");
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
                            echo "<small class='text-danger email_link' staff_lnk='$row->staff_id' email_link= '$row->email' title='Click to resend activation link' data-toggle='modal' data-target='#activation_link_modal'>Not verified</small>";
                          } else {
                            echo "<small class='text-primary'>Verified</small>";
                          } ?></td>
                      <td><img src="
                      <?php
                      if (empty($row->passport) || $row->passport == null) {
                        echo "../uploads/default.png";
                      } else {
                        echo $row->passport;
                      }
                      ?>" alt="staff image" height="50" width="50"></td>
                      <td><?php echo $row->staff_id; ?></td>
                      <td><?php echo $row->fname . " " . $row->sname . " " . $row->oname; ?></td>
                      <td>
                        <?php echo $row->email; ?>
                        <i title="Click to send mail" user_mail="<?php echo $row->email; ?>" class="btn fa fa-envelope send_user_mail" data-toggle="modal" data-target="#sendMail"></i>
                      </td>
                      <td><?php echo $row->user_type; ?></td>
                      <td><?php echo $row->user_role; ?></td>
                      <td>
                        <!-- Users enable and disable-->
                        <form method="post" action="staff-reg-page">
                          <input type="hidden" name="staff_id" value="<?php echo $row->staff_id; ?>">
                          <?php
                          if ($row->act_status == 0) {
                          ?>
                            <input type="hidden" name="staff_id" value="<?php echo $row->staff_id; ?>">
                            <button type="submit" title="Click to enable account" name="act_disabled" onclick="add_spinner()" class="btn btn-sm btn-danger spinner_btn"> <?php echo "Disabled"; ?> </button>
                          <?php
                          }
                          if ($row->act_status == 1) {
                          ?>
                            <input type="hidden" name="staff_id" value="<?php echo $row->staff_id; ?>">
                            <button type="submit" title="Click to disable account" name="act_enabled" onclick="add_spinner()" class="btn btn-sm btn-primary spinner_btn"> <?php echo "Enabled"; ?> </button>
                          <?php
                          }
                          ?>
                          </button>
                        </form>
                      </td>
                      <td>
                        <div class="form-group form-inline">
                          <div class="m-1">
                            <button title="Click to view" 
                            view_staff_id="<?php echo $row->staff_id; ?>" 
                            type="button" 
                            data-toggle="modal" 
                            data-target="#viewModal" 
                            class="btn btn-sm btn-outline-secondary view_staff_id"><i class="fa fa-eye"></i></button>
                          </div>
                          <div class="m-1">
                            <button title="Click to edit" 
                            edit_staff_name="<?php echo "$row->fname $row->sname $row->oname"; ?>" 
                            edit_staff_mail="<?php echo $row->email; ?>" 
                            edit_staff_id="<?php echo $row->staff_id; ?>" 
                            edit_staff_section="<?php echo $row->user_type; ?>" 
                            edit_staff_role="<?php echo $row->user_role; ?>" 
                            type="button" 
                            data-toggle="modal" 
                            data-target="#editModal" 
                            class="btn btn-sm btn-outline-primary edit_staff_mail"><i class="fas fa-fw fa-edit"></i></button>
                          </div>
                          <div class="m-1">
                            <button title="Click to delete" 
                            delete_staff_id="<?php echo $row->staff_id; ?>" 
                            type="button" 
                            data-toggle="modal" 
                            data-target="#deleteModal" 
                            class="btn btn-sm btn-outline-danger delete_staff_id"><i class="fa fa-trash"></i></button>
                          </div>
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
  </div>

  <!-- Activation_link_modal -->
  <div class="modal fade" id="activation_link_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Account activation link page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="staff-reg-page">
          <div class="modal-body">
            <p>Please check the mail address and make sure is accurate then click on <strong>Resend activation link</strong></p>
            <div class="form-group">
              <label>Email:</label>
              <input type="email" id="email_link_txt" name="email_link_txt" class="form-control" required>
              <input type="hidden" id="link2change" name="link2change" class="form-control" required>
              <input type="hidden" id="staff_id_lnk" name="staff_id_lnk" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="activation_link_btn" class="btn btn-sm btn-primary spinner_btn" onclick="add_spinner()"> Resend activation link </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Send user mail modal -->
  <div class="modal fade" id="sendMail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Compose mail page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="staff-reg-page">
          <div class="modal-body">
            <div class="form-group">
              <label>Mail To:</label>
              <input type="text" readonly id="mail_To" class="form-control">
            </div>
            <div class="form-group">
              <label>Mail subject:</label>
              <input type="text" name="mail_subject" class="form-control" placeholder="Subject" required>
            </div>
            <div class="form-group">
              <label>CC:</label>
              <input type="text" name="cc" class="form-control" placeholder="CC">
              <input type="hidden" name="user_mail" id="user_mail" class="form-control" placeholder="CC" required>
            </div>
            <div class="form-group">
              <label>Message:</label>
              <textarea type="text" name="message" class="form-control" placeholder="Message" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="send_user_mail_btn" class="btn btn-sm btn-primary"> Send </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Staff Edit Page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="staff-reg-page">
          <div class="modal-body">
            <div class="form-group">
              <label>Name:</label>
              <input type="text" id="staff_name" readonly class="form-control">
            </div>
            <div class="form-group">
              <label>Email:</label>
              <input type="text" name="staff_email" id="staff_email" readonly class="form-control">
              <input type="hidden" name="edit_staff_id" id="edit_staff_id" readonly class="form-control">
            </div>
            <div class="form-group">
              <label>User section:</label>
              <select name="staff_section" id="staff_section" class="form-control" required>
                <option value="Super Admin"> Super Admin </option>
                <option value="Admin"> Admin </option>
                <option value="Secondary"> Secondary </option>
                <option value="Primary"> Primary </option>
                <option value="Nursery"> Nursery </option>
              </select>
            </div>
            <div class="form-group">
              <label>Staff Role:</label>
              <select name="staff_role" id="staff_role" class="form-control" required>
                <option value="Instructor"> Instructor </option>
                <option value="Non-Instructor"> Non-Instructor </option>
                <option value="Principal"> Principal </option>
                <option value="Vice Principal"> Vice Principal </option>
                <option value="Exam Officer"> Exam Officer </option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="update_staff_btn" class="btn btn-sm btn-primary"> Update </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Staff View Page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Name:</label>
            <input type="text" id="staff_view_name" readonly class="form-control">
          </div>
          <div class="form-group">
            <label>State:</label>
            <input type="text" id="staff_view_state" readonly class="form-control">
          </div>
          <div class="form-group">
            <label>LGA:</label>
            <input type="text" id="staff_view_lga" readonly class="form-control">
          </div>
          <div class="form-group">
            <label>Gender:</label>
            <input type="text" id="staff_view_gender" readonly class="form-control">
          </div>
          <div class="form-group">
            <label>DOB:</label>
            <input type="text" id="staff_view_dob" readonly class="form-control">
          </div>
          <div class="form-group">
            <label>Contact no.:</label>
            <input type="text" id="staff_view_gsm" readonly class="form-control">
          </div>
          <div class="form-group">
            <label>Date/Time registerred:</label>
            <input type="text" id="staff_view_registerred" readonly class="form-control">
          </div>
          <div class="form-group">
            <label>Appointment date:</label>
            <input type="text" id="staff_view_appointment_date" readonly class="form-control">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Class Modal-->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Staff Delete Page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="staff-reg-page">
          <div class="modal-body">
            <p>Are you sure to delete this Staff? if yes, type in your staff ID followed by deLEtED e.g stf/21/0001 deLEtED</p>
            <input type="text" name="userID" autocomplete="off" placeholder="stf/21/0001 deLEtED"  class="form-control" required>
            <input type="hidden" id="staff_ID" name="staff_ID" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="deleteStaffBtn" class="btn btn-sm btn-primary"> Yes </button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->





<?php
include('includes/footer.php');
include('includes/script.php');
?>