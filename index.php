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

  <!-- Custom fonts for this template-->
  <link href="school/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="school/assets/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="school/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Sweet alert scripts -->
  <script src="school/assets/sweetalert2/sweetalert.all.min.js"></script>
</head>

<body class="bg-gradient-primary">

  <div class="container">
    <div class="row mt-3 text-center text-white">
      <div class="col-md-12">
        <h1>School Management System</h1>
        <p class="text-gray-300">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rem et, at, explicabo eaque laudantium adipisci facere ullam officia quis sapiente Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rem et, at, explicabo eaque laudantium adipisci facere ullam officia quis sapiente illo aliquam? Nam quos recusandae, laborum a ullam et culpa reiciendis voluptas harum nemo beatae quia facilis nostrum, libero eligendi.</p>
      </div>
    </div>
    <div class="row justify-content-center mt-5">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <div class="card o-hidden border-0 shadow-lg">
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
          <div class="card-header p-2 text-center">
            <h1 class="text-gray-900">Login Page</h1>
          </div>
          <form class="user" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="card-body">
              <p>Please input your email and password for login access</p>
              <div class="form-group">
                <input type="text" onkeypress="checkInput()" id="user_name" name="user_name" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Input email address">
              </div>
              <div class="form-group">
                <input type="password" id="pwd" name="pwd" onkeypress="checkInput()" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
              </div>
              <button name="login-btn" id="login_btn" onclick="add_spinner()" disabled type="submit" class="btn btn-primary btn-user btn-block spinner_btn">
                Login
              </button>
            </div>
            <div class="card-footer text-center mt-3">
              <a class="small" type="submit" href="forgot-password"> Forgot Password? </a>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>

  <script>
    const checkInput = () => {
      let user_name = document.querySelector("#user_name").value;
      let pwd = document.querySelector("#pwd").value;
      let login_btn = document.querySelector("#login_btn");

      if ((user_name.length > 4) && (pwd.length > 4)) login_btn.removeAttribute("disabled");
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