<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('./school/database/Database.php');
if (isset($_POST['cancel_pwd_reset_btn'])) {
    $db = new Database();

    $staff_id = $_POST['staff_id'];

    if (empty($staff_id)) {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Empt stfID";
        $_SESSION['sessionIcon'] = "warning";
        $_SESSION['location'] = "index";
    } else {
        //Check if the account has already been deactivated or else deactivate
        $db->query("SELECT act_status, change_pwd FROM staff_tbl WHERE staff_id = :staff_id;");
        $db->bind(':staff_id', $staff_id);
        $db->execute();
        if ($db->rowCount() > 0) {
            $result = $db->single();
            if (($result->act_status == 0) && ($result->change_pwd == 0) || ($result->act_status == 1) && ($result->change_pwd == 0)) {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Oops...!";
                $_SESSION['sessionMsg'] = "Account secured already";
                $_SESSION['sessionIcon'] = "warning";
                $_SESSION['location'] = "index";
            } else {
                // Disable user account and change change_pwd back to 0
                $db->query("UPDATE staff_tbl SET act_status = 0, change_pwd = 0 WHERE staff_id = :staff_id");
                $db->bind(':staff_id', $staff_id);
                $db->execute();
                if ($db->rowCount() > 0) {
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Awesome!";
                    $_SESSION['sessionMsg'] = "Please contact the admin to restore your account";
                    $_SESSION['sessionIcon'] = "success";
                    $_SESSION['location'] = "index";
                } else {
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "Error in protecting your account, try again or contact the admin";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "cancel-password-reset";
                }
            }
        }
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

        <?php
        $db = new Database();
        $db->query("SELECT * FROM frontend_tbl");
        if ($db->execute()) {
            if ($db->rowCount() > 0) {
                $row = $db->single();
                $title = $row->project_name;
                $logo_img = $row->img_logo;
                $project_note = $row->project_note;
        ?>
                <title><?php echo $title; ?></title>
                <link rel="icon" href="./school/super-admin/<?php echo $logo_img; ?>" type="image/png" />

            <?php
            } else {
            ?>
                <title>School Mangements System</title>
                <!-- <link rel="icon" href="./school/uploads/img/success.png" type="image/png" /> --> -->

        <?php
            }
        } else {
            die($db->getError());
        }
        $db->Disconect();
        ?>
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

    <body class="bg-gradient-primary">
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
                    <h1 class="text-primary"> Account Security Page </h1>
                </div>
                <div class="card-body">
                    <!-- Staff Content Row -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="staff_id" value="<?php echo $_GET['id']; ?>" required>
                                </div>
                                <div class="form-group text-center">
                                    <p>If you are sure that you did not initiate <em>Password reset </em> please click the <strong>Secure my account</strong> button to protect your account</p>
                                    <button type="submit" class="btn btn-primary spinner_btn" onclick="add_spinner()" name="cancel_pwd_reset_btn"> Secure my account </button>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
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
?>