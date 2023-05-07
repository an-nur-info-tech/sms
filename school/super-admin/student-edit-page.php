<?php

include('includes/header.php');

if (isset($_POST['update_btn'])) {
  $error = false;

  $data = array(
    'admNo' => $_POST['admNo'],
    'student_sname' => trim(strtoupper($_POST['student_sname'])),
    'student_lname' => trim(strtoupper($_POST['student_lname'])),
    'student_oname' => trim(strtoupper($_POST['student_oname'])),
    'class_name' => $_POST['class_name'],
    'dob' => $_POST['dob'],
    'religion' => $_POST['religion'],
    'gender' => $_POST['gender'],
    'nationality' => $_POST['nationality'],
    'student_state' => $_POST['student_state'],
    'lga' => $_POST['lga'],
    'oldImage' => $_POST['oldImage'],
    'fileToUpload' => strtolower($_FILES["fileToUpload"]["name"])
  );

  /* if(empty($student_sname)){
        $error = true;
        $warningMsg = "Surname is required";
    }
    if(empty($student_lname)){
        $error = true;
        $warningMsg = "Last name is required";
    }
    if(empty($class_name)){
        $error = true;
        $warningMsg = "Class name is required";
    }
    if(empty($dob)){
        $error = true;
        $warningMsg = "D.O.B is required";
    }
    if(empty($religion)){
        $error = true;
        $warningMsg = "Religion is required";
    }
    if(empty($nationality)){
        $error = true;
        $warningMsg = "Nationality is required";
    }
    if(empty($student_state)){
        $error = true;
        $warningMsg = "State is required";
    }
    if(empty($lga)){
        $error = true;
        $warningMsg = "LGA is required";
    } */
  if (!$error) {
    //Checking if user changing previous image
    /* if($_FILES["fileToUpload"]["tmp_name"]){
        //Getting image file type
        $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        //check if file type is an image
        if($imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "jpg" || $imageFileType == "gif" )
        {
          //If there is new image added
          if(!empty($_FILES["fileToUpload"]["tmp_name"])){
            unlink($_POST["currentPhoto"]);//Removing the old image
          }

          list($width, $height) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
          //var_dump($_FILES["fileToUpload"]["type"]);
          
          $newWidth = 500;
          $newHeight = 500;
          $directory = "img/";

          if($_FILES['fileToUpload']['type'] == "image/jpeg"){
            $ra = mt_rand(100, 999);
            $root = "img/".$ra.".jpeg";

            $source = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
            $destination = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($destination, $source, 0,0,0,0, $newWidth, $newHeight, $width, $height);
            imagejpeg($destination, $root);
          }
          
          if($_FILES['fileToUpload']['type'] == "image/png"){
            $ra = mt_rand(100, 999);
            $root = "img/".$ra.".png";

            $source = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
            $destination = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($destination, $source, 0,0,0,0, $newWidth, $newHeight, $width, $height);
            imagepng($destination, $root);
          }

          if($_FILES['fileToUpload']['type'] == "image/gif"){
            $ra = mt_rand(100, 999);
            $root = "img/".$ra.".gif";

            $source = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
            $destination = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($destination, $source, 0,0,0,0, $newWidth, $newHeight, $width, $height);
            imagegif($destination, $root);
          }

          $query_run = mysqli_query($con, "UPDATE students_tbl SET class_name='$class_name', passport= '$root', sname='$student_sname', lname='$student_lname', oname='$student_oname', dob='$dob', religion='$religion', gender='$gender', nationality='$nationality', student_state='$student_state', lga='$lga' WHERE admNo ='$admNo'");
          if(!$query_run){
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Update failed",
                    showConfirmButton: true,
                    confirmButtonText: "close",
                    closeOnConfirm: false
                }).then((result) => {
                    if(result.value){
                        window.location = "student-view-page";
                    }
                })
            </script>'; 
            //$warningMsg = "Update Failed ".mysqli_error($con);
          }else{
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Update successful",
                        showConfirmButton: true,
                        confirmButtonText: "close",
                        closeOnConfirm: false
                        }).then((result) => {
                        if(result.value){
                            window.location = "student-view-page";
                        }
                      });
                </script>';
          }
        }else{
          echo '<script>
                  Swal.fire({
                      icon: "warning",
                      title: "image type only",
                      showConfirmButton: true,
                      confirmButtonText: "close",
                      closeOnConfirm: false
                      }).then((result) => {
                      if(result.value){
                          window.location = "student-view-page";
                      }
                    });
                </script>';            
          //$warningMsg = "The file is not an image type";
        }
      } */

    if ($data['fileToUpload']) { //Update with Image
      //specifying the directory where the file is going to be placed.
      $target_dir = "../uploads/students/";
      //specifying path of the file to be uploaded
      $target_file = $target_dir . basename($data['fileToUpload']);
      //Getting the file extension
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      //check if file type is an image
      $imgType = ["jpg", "gif", "jpeg", "png"];
      if (!in_array($imageFileType, $imgType)) {
        $error = true;
        $warningMsg = "The file is not an image type";
      }

      // check if file exists
      if (file_exists($target_file)) {
        $error = true;
        $warningMsg = "Picture exist";
      }

      //Checking for image size
      if ($_FILES['fileToUpload']['size'] > 102405 or $_FILES['fileToUpload']['size'] < 10240) {
        $error = true;
        $warningMsg = "Image size should be in the range of 15KB to 100KB";
      }
      if (!empty($_FILES["fileToUpload"]["tmp_name"])) {
        unlink($data["oldImage"]); //Removing the old image
      }

      $db1 = new Database();
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $db1->query("UPDATE students_tbl SET class_name=:class_name, 
          passport = :target_file,
          sname=:student_sname, 
          lname=:student_lname, 
          oname=:student_oname, 
          dob=:dob, 
          religion=:religion, 
          gender=:gender, 
          nationality=:nationality, 
          student_state=:student_state, 
          lga=:lga 
          WHERE admNo =:admNo;");
        $db1->bind(':class_name', $data['class_name']);
        $db1->bind(':target_file', $target_file);
        $db1->bind(':student_sname', $data['student_sname']);
        $db1->bind(':student_lname', $data['student_lname']);
        $db1->bind(':student_oname', $data['student_oname']);
        $db1->bind(':dob', $data['dob']);
        $db1->bind(':religion', $data['religion']);
        $db1->bind(':gender', $data['gender']);
        $db1->bind(':nationality', $data['nationality']);
        $db1->bind(':student_state', $data['student_state']);
        $db1->bind(':lga', $data['lga']);
        $db1->bind(':admNo', $data['admNo']);

        if (!$db1->execute()) {
          echo '<script>
                      Swal.fire({
                          icon: "warning",
                          title: "Update failed",
                          showConfirmButton: true,
                          confirmButtonText: "close",
                          closeOnConfirm: false
                          }).then((result) => {
                          if(result.value){
                              window.location = "student-view-page";
                          }
                        });
                  </script>';
        } else {
          echo '<script>
                      Swal.fire({
                          icon: "success",
                          title: "Update successful",
                          showConfirmButton: true,
                          confirmButtonText: "close",
                          closeOnConfirm: false
                          }).then((result) => {
                          if(result.value){
                              window.location = "student-view-page";
                          }
                        });
                  </script>';
        }
      }
      $db->Disconect();
    } else { //Update without image file
      $db1 = new Database();
      $db1->query("UPDATE students_tbl SET class_name=:class_name, 
        sname=:student_sname, 
        lname=:student_lname, 
        oname=:student_oname, 
        dob=:dob, 
        religion=:religion, 
        gender=:gender, 
        nationality=:nationality, 
        student_state=:student_state, 
        lga=:lga 
        WHERE admNo =:admNo;");
      $db1->bind(':class_name', $data['class_name']);
      $db1->bind(':student_sname', $data['student_sname']);
      $db1->bind(':student_lname', $data['student_lname']);
      $db1->bind(':student_oname', $data['student_oname']);
      $db1->bind(':dob', $data['dob']);
      $db1->bind(':religion', $data['religion']);
      $db1->bind(':gender', $data['gender']);
      $db1->bind(':nationality', $data['nationality']);
      $db1->bind(':student_state', $data['student_state']);
      $db1->bind(':lga', $data['lga']);
      $db1->bind(':admNo', $data['admNo']);

      if (!$db1->execute()) {
        echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "Update failed",
                        showConfirmButton: true,
                        confirmButtonText: "close",
                        closeOnConfirm: false
                        }).then((result) => {
                        if(result.value){
                            window.location = "student-view-page";
                        }
                      });
                </script>';
      } else {
        echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Update successful",
                        showConfirmButton: true,
                        confirmButtonText: "close",
                        closeOnConfirm: false
                        }).then((result) => {
                        if(result.value){
                            window.location = "student-view-page";
                        }
                      });
                </script>';
      }
    }
    $db->Disconect();
  }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <div class="align-items-center justify-content-center ">
    <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Student Profile Edit Page </h3>
    <p>The students can be registered with or without an image. <br /> Fields with asterisk (*) are to be filled </p>
  </div><br>
  <center>
    <?php
    if (isset($successMsg)) {
    ?>
      <label class="text-success"><i class="fas fa-fw fa-check-square"></i> <?php echo $successMsg; ?></label>
    <?php
    } elseif (isset($warningMsg)) {
    ?>
      <label class="text-danger"><i class="fas fa-fw fa-user-times"></i><?php echo $warningMsg; ?></label>
    <?php
    }
    ?>
  </center>
  <!-- Student Content Row -->
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
    <div class="form-row">
      <?php
      if (isset($_POST['editBtn'])) {
        $db = new Database();
        $admNo = $_POST['admNo'];
        $db->query("SELECT * FROM students_tbl WHERE admNo=:admNo;");
        $db->bind('admNo', $admNo);
        $results = $db->resultset();
        if ($db->rowCount() > 0) {
          foreach ($results as $result) {
      ?>
            <div class="col-md-4"> </div>
            <div class="form-group col-md-4">
              <center>
                <img id="image" src="<?php echo $result->passport; ?>" class="form-control-img" width="100px" height="100px" />
                <p class="text-danger" style="font-size: 13px;"> Image size should be in the range of 15KB to 100KB</p>
                <input type="file" name="fileToUpload" onchange="loadFile(event)" />
                <input type="hidden" value="<?php echo $result->passport; ?>" name="oldImage">
              </center>
            </div>
            <div class="col-md-4"> </div>
    </div>
    <!-- First Row -->
    <div class="form-row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="class_name" class="control-label">* Class </label>
          <select name="class_name" id="class_name" class="form-control">
            <option value="<?php echo $result->class_name; ?>"> <?php echo $result->class_name; ?></option>
            <!-- Fetching data from class table -->
            <?php
            $db2 = new Database();
            $db2->query("SELECT * FROM class_tbl;");
            $datas = $db2->resultset();
            if ($datas > 0) {
              foreach ($datas as $data) {
            ?>
                <option value="<?php echo $data->class_name; ?>"> <?php echo $data->class_name; ?> </option>
              <?php
              }
              $db2->Disconect();
            } else {
              ?>
              <option value=""> No data </option>
            <?php
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label" for="student_sname">* Surname: </label>
          <input type="hidden" class="form-control" value="<?php echo $admNo; ?>" name="admNo">
          <input type="text" class="form-control" id="student_sname" value="<?php echo $result->sname; ?>" name="student_sname">
        </div>
      </div>
      <div class="col-md-4 ">
        <div class="form-group">
          <label class="control-label" for="student_lname">* Last name: </label>
          <input type="text" class="form-control" value="<?php echo $result->lname; ?>" name="student_lname" auto-compplete="off">
        </div>
      </div>
    </div>
    <!-- Second Row -->
    <div class="form-row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label" for="student_oname"> Other name: </label>
          <input type="text" class="form-control" value="<?php echo $result->oname; ?>" name="student_oname">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="dob">* D.O.B</label>
          <input type="date" name="dob" value="<?php echo $result->dob; ?>" class="form-control">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="religion">* Religion</label>
          <select name="religion" class="form-control">
            <option value="<?php echo $result->religion; ?>"> <?php echo $result->religion; ?> </option>
            <option value="Islam"> Islam </option>
            <option value="Christian"> Christian </option>
            <option value="Judaism"> Judaism </option>
            <option value="Buddhism"> Buddhism </option>
          </select>
        </div>
      </div>
    </div>
    <!-- Third Row -->
    <div class="form-row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="gender">* Gender </label>
          <select name="gender" class="form-control">
            <option value="<?php echo $result->gender; ?>"> <?php echo $result->gender; ?></option>
            <option value="Male"> Male </option>
            <option value="Female"> Female </option>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="nationality"> * Nationality </label>
          <input class="form-control" name="nationality" list="datalistioptions1" value="<?php echo $result->nationality; ?>">
          <datalist id="datalistioptions1" auto-compplete="off">
            <option value=""> </option>
            <option value="Afghanistan"> Afghanistan </option>
            <option value="Albania"> Albania </option>
            <option value="Algeria"> Algeria </option>
            <option value="Andorra"> Andorra </option>
            <option value="Angola"> Angola </option>
            <option value="Antigua and Barbuda"> Antigua and Barbuda </option>
            <option value="Argentina"> Argentina </option>
            <option value="Australia"> Australia </option>
            <option value="Austria"> Austria </option>
            <option value="Azerbaijan"> Azerbaijan </option>
            <option value="Nigeria"> Nigeria </option>
            <option value="Indian"> Indian </option>
          </datalist>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="student_state" class="form-label"> * State: </label>
          <input class="form-control" name="student_state" list="datalistioptions" value="<?php echo $result->student_state; ?>" auto_complete="off">
          <datalist id="datalistioptions" auto-compplete="off">
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
      </div>
    </div>
    <!-- Forth Row -->
    <div class="form-row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="lga">* L.G.A </label>
          <input name="lga" class="form-control" value="<?php echo $result->lga; ?>">
        </div>
      </div>
    </div>
    <!-- Fourth Row -->
    <div class="form-row">
      <div class="col-md-12" align="center">
        <div class="fom-group">
          <a href="student-view-page" class="btn btn-outline-danger"> Back </a>
          <button class="btn btn-primary" name="update_btn"> Update </button>
        </div>
      </div>
    </div>
  </form>
<?php
          }
        }
        $db->Disconect();
      }
?>
</div>
<!-- /.container-fluid -->


<?php
include('includes/footer.php');
include('includes/script.php');
?>