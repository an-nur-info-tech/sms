<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('./school/database/Database.php');

if (isset($_POST['submit_btn'])) {
    $db = new Database();

    $staff_id = $_POST['staff_id'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $staff_state = $_POST['staff_state'];
    $lga = trim($_POST['lga']);
    $qualification = $_POST['qualification'];
    $class_of_qual = $_POST['class_of_qual'];
    $date_joined = $_POST['date_joined'];
    $home_address = trim($_POST['home_address']);
    $gsm1 = $_POST['gsm1'];
    $gsm2 = $_POST['gsm2'];
    $pwd = $_POST['pwd'];
    $c_pwd = $_POST['c_pwd'];
    $religion = $_POST['religion'];
    $date_reg = date('d-M, Y');
    $hash_pwd = password_hash($pwd, PASSWORD_BCRYPT);

    // For image upload
    $fileToUpload = $_FILES["fileToUpload"]["name"];

    $act_verify = 1;

    //fetching user record to check for verification
    $db->query("SELECT * FROM staff_tbl WHERE staff_id = :staff_id;");
    $db->bind(':staff_id', $staff_id);
    $db->execute();

    if ($db->rowCount() > 0) {
        $row = $db->single();
        //Checking if user account has been verified or not
        if ($row->act_verify == 0) {
            //Validating the forms
            if (empty($fileToUpload)) {
                $error = true;
                $warningMsg = "Upload image";
            }
            if ($pwd !== $c_pwd) {
                $error = true;
                $warningMsg = "Password does not matched";
            }
            //specifying the directory where the file is going to be placed.
            $target_dir = "uploads/";
            $img_update = "../uploads/staff/";
            //specifying path of the file to be uploaded
            $target_file = $target_dir . basename($fileToUpload);
            //Getting the file extension
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // check if file exists
            if (file_exists($target_file)) {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Success";
                $_SESSION['sessionMsg'] = "Record added with email sent!";
                $_SESSION['sessionIcon'] = "success";
                $_SESSION['location'] = "staff-reg-page";

                $error = true;
                $warningMsg = "Picture exist";
            }
            // validate image size. Size is calculated in Bytes
            if ($_FILES['fileToUpload']['size'] > 102405 || $_FILES['fileToUpload']['size'] < 1024) {
                $error = true;
                $warningMsg = "Image size should be in the range of 15KB to 100KB";
            }
            //check if file type is an image
            if ($imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != "gif") {
                $error = true;
                $warningMsg = "The file is not an image type";
            }

            if (!$error) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $update_query = mysqli_query($con, "UPDATE  staff_tbl SET passport='$target_file', lname='$fname', sname='$lname', oname='$oname', dob='$dob',
              gender='$gender', staff_state='$staff_state', lga='$lga', qualification='$qualification', qualification_level='$class_of_qual', 
              year_joined='$date_joined', home_address='$home_address', gsm1='$gsm1', gsm2='$gsm2', pwd='$hash_pwd', act_verify='$act_verify', religion='$religion', date_reg ='$date_reg' WHERE staff_id='$staff_id'");
                    if (!$update_query) {
                        $warningMsg = die("Form not submitted " . mysqli_error($con));
                    } else {
                        $successMsg = "Form Submitted click <a href='login.php'>here</a> to login";
                    }
                }
            }
        } elseif ($row->act_verify == 1) {
            $error = true;
            $warningMsg = "This account has been verified please click <a href='login.php'>here</a> to login";
        } else {
            $error = true;
            $warningMsg = "This account is not available contact the admin";
        }
    } else {
        $db->Disconect();
        exit();
    }

    $db->Disconect();
}

if (isset($_GET['staff_id'])) {
    $db = new Database();
    $id = $_GET['staff_id'];
    $db->query("SELECT * FROM staff_tbl WHERE staff_id = :id;");
    $db->bind(':id', $id);
    $db->execute();
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
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- HEADER CONTENT -->
            <div class="row text-center">
                <div class="col-md-12">
                    <img src="./school/uploads/img/logoPdf.png" class="img img-thumbnail mt-3" height="100" width="100">
                    <h1 class="text-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif;  ">SUCCESS SCHOOLS SOKOTO</h1>
                    <p class="text-primary" style="font-weight: bold; ">Always on Top, Winning the Gold</p>
                </div>
            </div>
            <!--X- HEADER CONTENT -X-->
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
            <div class="card mt-5">
                <div class="card-header text-center">
                    <h1 class="text-primary"> Staff Registration Page </h1>
                </div>
                <div class="card-body">
                    <!-- Staff Content Row -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">

                        <!-- First row -->
                        <div class="form-row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <center>
                                    <img id="image" class="form-control-img" width="100px" height="100px" />
                                    <p class="text-danger" style="font-size: 13px;"> Image size should be in the range of 1KB to 100KB</p>
                                    <input type="file" name="fileToUpload" onchange="loadFile(event)" />
                                </center>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <input type="hidden" value="<?php echo $id; ?>" name="staff_id" class="form-control">

                        <?php
                        if (!$db->execute()) {
                            die($db->getError());
                        } else {
                            $row = $db->single();
                        ?>
                            <div class="form-row">
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <label class="control-label" for="fname">* First name: </label>
                                        <input type="text" class="form-control" value="<?php echo $row->fname ?>" name="fname" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="lname">* Surname: </label>
                                        <input type="text" class="form-control" value="<?php echo $row->sname ?>" name="lname" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="oname"> Other name: </label>
                                        <input type="text" class="form-control" value="<?php echo $row->oname ?>" name="oname" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Third row -->
                            <div class="form-row">
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <label class="control-label" for="dob">* DOB: </label>
                                        <input type="date" class="form-control" name="dob" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="gender">* Gender: </label>
                                        <select name="gender" class="form-control" required>
                                            <option value=""> Select gender...</option>
                                            <option value="Male"> Male </option>
                                            <option value="Female"> Female </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                            <!-- Fourth row -->
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="staff_state">* State: </label>
                                        <select name="staff_state" class="form-control" required>
                                            <option value=""> Select state...</option>
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
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <label class="control-label" for="lga">* LGA: </label>
                                        <input type="text" class="form-control" name="lga" placeholder="Local government" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="qualification"> * Qualification: </label>
                                        <select name="qualification" class="form-control" required>
                                            <option value=""> Select qualification...</option>
                                            <option value="NCE">Nigeria Certifate Education [NCE] </option>
                                            <option value="ND"> Natioal Diploma [ND]</option>
                                            <option value="HND"> Higher National Diploma [HND] </option>
                                            <option value="BSc"> Bachelor of Science [BSc] </option>
                                            <option value="PGD"> Post Graduate [PGD] </option>
                                            <option value="MSc"> Master of Science [MSc] </option>
                                            <option value="PHD"> PHD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Fifth row -->
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="class_of_qual">* Class of qualification: </label>
                                        <select name="class_of_qual" class="form-control" required>
                                            <option value=""> Class of qualification</option>
                                            <option value="First class(Distinction)">First class(Distinction) </option>
                                            <option value="Second class(Upper)"> Second class(Upper) </option>
                                            <option value="Second class(lower)"> Second class(Lower) </option>
                                            <option value="Third class(Pass) "> Third class(Pass) </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <label class="control-label" for="date_joined">* Date joined: </label>
                                        <input type="date" class="form-control" name="date_joined" placeholder="Date joined" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="home_address"> Home address: </label>
                                        <input type="text" class="form-control" name="home_address" required>
                                    </div>
                                </div>
                            </div>
                            <!-- Sixth row -->
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="gsm1">* Phone no: </label>
                                        <input type="number" name="gsm1" class="form-control" placeholder="Phone number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <label class="control-label" for="gsm2"> Additional phone no: </label>
                                        <input type="number" class="form-control" name="gsm2" placeholder="Phone number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="religion"> * Religion: </label>
                                        <select name="religion" class="form-control" required>
                                            <option value=""> Select religion...</option>
                                            <option value="Islam"> Islam </option>
                                            <option value="Christian"> Christian </option>
                                            <option value="Taditional religion"> Taditional religion </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Divider between Password -->
                            <hr class="sidebar-divider  d-md-block">
                            <!-- Seventh row -->
                            <div class="form-row">
                                <div class="col-md-12">
                                    <p>Please use a strong password, a combination of apha-numeric is highly recommended</p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-2"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="pwd"> * Password: </label>
                                        <input type="password" class="form-control" placeholder="Password" name="pwd" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="c_pwd">* Confirm Password: </label>
                                        <input type="password" name="c_pwd" class="form-control" placeholder="Confirm password" required>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>

                            </div>
                            <!-- Eight Row -->
                            <div class="form-row">
                                <div class="col-md-12 text-center mt-5">
                                    <button class="btn btn-primary" name="submit_btn"> Submit </button>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p>Copy right &copy; 2023 Powered by <a href="https://www.an-nur-info-tech.com">an-nur-info-tech.com</a></p>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php
                        }
                        $db->Disconect();
                    } else {
                        header('Location: index');
                    }
?>