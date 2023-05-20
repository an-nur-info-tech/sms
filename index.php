<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start(); //start session if session not start
}
require_once('code.php');
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

  <link rel="icon" href="../img/success.png" type="image/png" />

  <!-- Custom fonts for this template-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />


  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.css" integrity="sha512-gOfBez3ehpchNxj4TfBZfX1MDLKLRif67tFJNLQSpF13lXM1t9ffMNCbZfZNBfcN2/SaWvOf+7CvIHtQ0Nci2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

  <!-- START -->

  <!-- Custom fonts for this template-->
  <link href="school/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="school/assets/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="school/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- END -->

  <!-- Sweet alert scripts -->
  <script src="school/assets/sweetalert2/sweetalert.all.min.js"></script>


</head>

<body class="bg-gradient-primary">

  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-12">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body src="successschool/construct2.jpg" -->
            <div class="row">
              <div class="col-lg-6 col-md-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Login Page</h1>
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
                  <form class="user" method="POST" action="index">
                    <div class="form-group">
                      <input type="text" onkeypress="checkInput()"  id="user_name" name="user_name" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter User name/Email Address...">
                    </div>
                    <div class="form-group">
                      <input type="password" id="pwd" name="pwd" onkeypress="checkInput()"  class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>
                    <button name="login-btn" id="login_btn" disabled type="submit" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.php"> Forgot Password? </a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="#">Create an Account!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <script>
    const checkInput = () => {
      let user_name = document.querySelector("#user_name").value;
      let pwd = document.querySelector("#pwd").value;
      let login_btn = document.querySelector("#login_btn");

      if ((user_name.length > 4) && (pwd.length > 4))login_btn.removeAttribute("disabled");
    }
  </script>
</body>
</html>