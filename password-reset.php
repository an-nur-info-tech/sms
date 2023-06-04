<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('./school/database/Database.php');
    

if (isset($_POST['submit_btn'])) {
    $db = new Database();

    $staff_id = $_POST['staff_id'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $c_pwd = $_POST['c_pwd'];
    $hashed_P = password_hash($pwd, PASSWORD_BCRYPT);

    if ($pwd == $c_pwd)
    {
        // Check if change_pwd == 1 (for request password)
        $db->query("SELECT * FROM staff_tbl WHERE staff_id = :staff_id LIMIT 1;");
        $db->bind(':staff_id', $staff_id);
        $db->execute();    
        if ($db->rowCount() > 0)
        {
            $result = $db->single()->change_pwd;   
            
            if ($result == 1){ // Change password

                $db->query("UPDATE staff_tbl SET pwd = :hashed_P, change_pwd = 2 WHERE staff_id = :staff_id;");
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
                        $_SESSION['location'] = "index";
                    }
                    else 
                    {
                        $_SESSION['errorMsg'] = true;
                        $_SESSION['errorTitle'] = "Error";
                        $_SESSION['sessionMsg'] = "Change failed";
                        $_SESSION['sessionIcon'] = "error";
                        $_SESSION['location'] = "password-reset";
                    }
                }
                else 
                {
                    die($db->getError());
                }
            }else if ($result == 2) // Password changed already (link expired)
            {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Oops...";
                $_SESSION['sessionMsg'] = "Link has expired, please login again";
                $_SESSION['sessionIcon'] = "warning";
                $_SESSION['location'] = "index";
            }else{
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Error";
                $_SESSION['sessionMsg'] = "Invalid request";
                $_SESSION['sessionIcon'] = "warning";
                $_SESSION['location'] = "index";
            }
            
        }else{
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Request not found!";
            $_SESSION['sessionIcon'] = "warning";
            $_SESSION['location'] = "index";
        }
    }
    else
    {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Ooops...";
        $_SESSION['sessionMsg'] = "Password does not match confirm password!";
        $_SESSION['sessionIcon'] = "warning";
        $_SESSION['location'] = "password-reset"; 
    }
    $db->Disconect();
}

if (isset($_GET['id'])) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>School Management System</title>

        <link rel="icon" href="./school/uploads/img/success.png" type="image/png" />

        <!-- Custom fonts for this template-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />


        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.css" integrity="sha512-gOfBez3ehpchNxj4TfBZfX1MDLKLRif67tFJNLQSpF13lXM1t9ffMNCbZfZNBfcN2/SaWvOf+7CvIHtQ0Nci2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

        <!-- Custom fonts for this template-->
        <link href="school/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="school/assets/css/sb-admin-2.min.css" rel="stylesheet">

        <!-- Custom styles for this page -->
        <link href="school/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

        <!-- Sweet alert scripts -->
        <script src="school/assets/sweetalert2/sweetalert.all.min.js"></script>
        <script type="text/javascript">
            var loadFile = function(event) {
                var image = document.getElementById('image');
                image.src = URL.createObjectURL(event.target.files[0]);
                image.onload = function() {
                    URL.revokeObjectURL(image.src)
                }
            };
        </script>
    </head>

    <body>
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
        <!-- Begin Page Content -->
        <div class="container">
            <!-- Alerts messages -->
            <div class="card m-5">
                <div class="card-header text-center">
                    <h1 class="text-primary"> Change Password Page </h1>
                </div>
                <div class="card-body">
                    <!-- Staff Content Row -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <p>Please use a strong password at least 8 by lengths with alphanumeric to be able to apply changes</p>
                                <div class="form-group">
                                    <label class="control-label" for="pwd"> Password: </label>
                                    <input type="password" onkeyup="check_password_strength()" id="pwd" class="form-control" name="pwd" required>
                                    <!-- <input type="text"  class="form-control" value="<?php //echo $_GET['email']; ?>" name="email" required> -->
                                    <input type="hidden"  class="form-control" value="<?php echo $_GET['id'] ?>" name="staff_id" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="c_pwd"> Confirm Password: </label>
                                    <input type="password" onkeyup="check_password_strength()" id="c_pwd" class="form-control" name="c_pwd" required>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary spinner_btn" disabled onclick="add_spinner()" name="submit_btn"> Submit </button>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </form>
                </div>
                <!-- <div class="card-footer text-center mt-3">
              <a class="small" type="submit" href="index"> Back </a>
            </div> -->
            </div>
        </div>
        <script type="text/javascript">
            /*   CHECK PASSWORD STRENGTH ON PROFILE SETTINGS*/
            const check_password_strength = () => {
                let pwd = document.querySelector("#pwd").value;
                let c_pwd = document.querySelector("#c_pwd").value;

                if ((pwd.length >= 8) && (c_pwd.length >= 8) && (pwd.match(/[a-zA-Z][0-9]/g))) // TODO RegEx
                {
                    document.querySelector(".spinner_btn").removeAttribute("disabled");
                } else {
                    document.querySelector(".spinner_btn").setAttribute("disabled", "");
                }
            }
            /*-------x---- CHECK PASSWORD STRENGTH ON PROFILE SETTINGS -------x----*/

            const add_spinner = () => {
                let spinner_btn = document.querySelector(".spinner_btn");
                let span = document.createElement("span");

                span.classList.add("spinner-border");
                span.classList.add("spinner-border-sm");
                span.setAttribute('role', 'status');
                span.setAttribute('aria-hidden', 'true');
                spinner_btn.innerHTML = " ";
                spinner_btn.appendChild(span);
                // spinner_btn.setAttribute('disabled', '');
            }
        </script>
    </body>

    </html>



<?php
} else {
    header('Location: index');
}
