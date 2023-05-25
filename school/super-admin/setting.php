<?php 
include('includes/header.php');

if (isset($_POST['passwordBtn']))
{
    $db = new Database();

    $current_password = $_POST['current_password'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $staff_id = $_POST['staff_id'];
    $hashed_P = password_hash($password, PASSWORD_BCRYPT);

    // Check if current password match in database
    $db->query("SELECT pwd FROM staff_tbl WHERE staff_id = :staff_id;");
    $db->bind(':staff_id', $staff_id);
    if ($db->execute())
    {
        $result = $db->single();
        if (password_verify($current_password, $result->pwd))
        {
            // Check if New password match confirm password
            if ($password = $current_password)
            {
                $db->query("UPDATE staff_tbl SET pwd = :hashed_P WHERE staff_id = :staff_id;");
                $db->bind(':hashed_P', $hashed_P);
                $db->bind(':staff_id', $staff_id);
                if ($db->execute())
                {
                    if ($db->rowCount() > 0)
                    {
                        $_SESSION['errorMsg'] = true;
                        $_SESSION['errorTitle'] = "Success";
                        $_SESSION['sessionMsg'] = "Password changed";
                        $_SESSION['sessionIcon'] = "success";
                        $_SESSION['location'] = "setting";
                    }
                    else 
                    {
                        $_SESSION['errorMsg'] = true;
                        $_SESSION['errorTitle'] = "Error";
                        $_SESSION['sessionMsg'] = "Change failed";
                        $_SESSION['sessionIcon'] = "error";
                        $_SESSION['location'] = "setting";
                    }
                }
                else 
                {
                    die($db->getError());
                }
            }
            else 
            {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Error";
                $_SESSION['sessionMsg'] = "New password does not matched confirm password";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "setting";
            }
        }
        else 
        {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Password does not matched";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "setting";
        }
    }
}
$db->Disconect();
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="card mt-5">
        <div class="card-header">
            <h1>Profile Settings</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p>
                        Enable 2FA &nbsp; &nbsp;<input type="checkbox" disabled value="1">
                    </p>
                    <p>
                        Account active &nbsp; &nbsp;<input type="checkbox" value="1" disabled checked>
                    </p>
                    <p>
                        <a href="#" data-toggle="modal" data-target="#change_password" >Change password </a>
                    </p>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
            </div>
        </div>        
    </div>

  <!-- Change password Modal-->
  <div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Change password</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="setting">
          <div class="modal-body">
            <p>Password strength should be at least 8 character length including Uppercase letter, Numeric, and Special characters. e.g Abc@123 </p>
            <div class="mt-3">
                <input type="password" name="current_password" autocomplete="off" placeholder="Current password?"  class="form-control" required>
            </div>
            <div class="mt-3">
                <input type="password" name="password" id="password" onkeypress="check_Password_stregth()" autocomplete="off" placeholder="New password?"  class="form-control" required>
            </div>
            <div class="mt-3">
                <input type="password" name="confirm_password" id="confirm_password" onkeypress="check_Password_stregth()" autocomplete="off" placeholder="Confirm password?"  class="form-control" required>
            </div>
            <input type="hidden" name="staff_id" value="<?php echo $_SESSION['staff_id']; ?>" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="passwordBtn" id="passwordBtn" disabled class="btn btn-sm btn-primary"> Change </button>
          </div>
        </form>
      </div>
    </div>
  </div>  
  
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
</div>  

<?php 
include('includes/footer.php');
include('includes/script.php');
?>