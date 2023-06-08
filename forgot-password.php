<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('./school/database/Database.php');
include('./school/assets/PHPMailer/src/PHPMailer.php');
include('./school/assets/PHPMailer/src/Exception.php');
include('./school/assets/PHPMailer/src/SMTP.php');
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['forgot_btn'])) {
    $db = new Database();

    $email = $_POST['email'];

    //fetching user record to check for verification
    $db->query("SELECT * FROM staff_tbl WHERE email = :email LIMIT 1;");
    $db->bind(':email', $email);
    if ($db->execute()) {
        $result = $db->single();
        if ($db->rowCount() > 0) {
            $email = $result->email;
            $id = $result->staff_id;

            // Update the change_pwd from the database
            $db->query("UPDATE staff_tbl SET change_pwd = 1 WHERE staff_id = :id"); // Request for change of password
            $db->bind(':id', $id);
            if ($db->execute()) {
                if ($db->rowCount() > 0) {
                    //Create an instance; passing `true` enables exceptions
                    $mail = new PHPMailer(true);

                    try {
                        //Server settings
                        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = '';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = '';                     //SMTP username
                        $mail->Password   = '';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  //           //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('', '');
                        $mail->addAddress($email);     //Add a recipient
                        // $mail->addAddress('ellen@example.com');               //Name is optional
                        // $mail->addReplyTo('info@example.com', 'Information');
                        // $mail->addCC('cc@example.com');
                        // $mail->addBCC('bcc@example.com');

                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Password reset request';
                        $mail_template =
                            "
                            <h1> Password Reset </h1>
                            <h4> You requested for password reset if yes Please click 
                                <a href='https://test.an-nur-info-tech.com/password-reset?id=$id'>
                                Here
                                </a>
                            to complete the reset of the password
                            </h4>
                            <p>
                                If you did not request please click 
                                <a href='https://test.an-nur-info-tech.com/cancel-password-reset?id=$id'>
                                Here
                                </a>
                            </p> 
                        ";
                        $mail->Body    = $mail_template;
                        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                        $mail->send();
                        // echo 'Message has been sent';
                        $_SESSION['errorMsg'] = true;
                        $_SESSION['errorTitle'] = "Success";
                        $_SESSION['sessionMsg'] = "Check your email for password reset link";
                        $_SESSION['sessionIcon'] = "success";
                        $_SESSION['location'] = "index";
                    } catch (Exception $e) {
                        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        $_SESSION['errorMsg'] = true;
                        $_SESSION['errorTitle'] = "Error";
                        $_SESSION['sessionMsg'] = "Mailer Error: {$mail->ErrorInfo}";
                        $_SESSION['sessionIcon'] = "error";
                        $_SESSION['location'] = "forgot-password";
                    }
                } else {
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "Error (pwd_udt)";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "forgot-password";
                }
            } else {
                die($db->getError());
            }
        } else {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Oops...";
            $_SESSION['sessionMsg'] = "No email found!";
            $_SESSION['sessionIcon'] = "warning";
            $_SESSION['location'] = "forgot-password";
        }
    } else {
        die($db->getError());
    }
    $db->Disconect();
}
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
                <h1 class="text-primary"> Forgotten Password Page </h1>
            </div>
            <div class="card-body">
                <!-- Staff Content Row -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="email"> Email: </label>
                                <input type="email" onkeyup="check_input(this.value)" class="form-control" name="email" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary spinner_btn" disabled onclick="add_spinner()" name="forgot_btn"> Submit </button>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center mt-3">
                <a class="small" type="submit" href="index"> Back </a>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        const check_input = (email) => {
            if (email.length > 5) {
                document.querySelector(".spinner_btn").removeAttribute("disabled");
            }
        }


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