<?php

include('includes/header.php');

if (isset($_POST['update_btn'])) {
  $db1 = new Database();
  $data = array(
    'admNo' => $_POST['admNo'],
    'student_sname' => trim(strtoupper($_POST['student_sname'])),
    'student_lname' => trim(strtoupper($_POST['student_lname'])),
    'student_oname' => trim(strtoupper($_POST['student_oname'])),
    'class_name' => trim(strtoupper($_POST['class_id'])),
    'dob' => $_POST['dob'],
    'religion' => trim(strtoupper($_POST['religion'])),
    'gender' => trim(strtoupper($_POST['gender'])),
    'nationality' => trim(strtoupper($_POST['nationality'])),
    'student_state' => trim(strtoupper($_POST['student_state'])),
    'lga' => trim(strtoupper($_POST['lga'])),
    'oldImage' => $_POST['oldImage']
  );

  $fileToUpload = $_FILES["fileToUpload"]["name"];
  //specifying the directory where the file is going to be placed.
  $target_dir = "../uploads/students/";
  //specifying path of the file to be uploaded
  $target_file = $target_dir . basename($fileToUpload);
  //Getting the file extension
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  //check if file type is an image
  $imgType = ["jpg", "gif", "jpeg", "png"];

  if ($fileToUpload) { //Update with Image
    //Checking for image size
    if (!in_array($imageFileType, $imgType)) {
      $error = true;
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Error";
      $_SESSION['sessionMsg'] = "The file is not an image type";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "student-view-page";
    } else if ($_FILES['fileToUpload']['size'] > 102405 or $_FILES['fileToUpload']['size'] < 10240) {
      $error = true;
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Error";
      $_SESSION['sessionMsg'] = "Image size within 15KB to 100KB";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "student-view-page";
    } else {
      // **********************************************
      list($width, $height) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      //var_dump($_FILES["profilePicture"]["type"]);
      $newWidth = 500;
      $newHeight = 500;
      // $directory = "views/img/users/" . $_POST["name"];

      if ($_FILES['fileToUpload']['type'] == "image/jpeg") {
        // $ra = mt_rand(100, 999);
        // $root = "views/img/users/" . $_POST["name"] . "/" . $ra . ".jpeg";

        $source = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
        $destination = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagejpeg($destination, $target_file);
      }
      if ($_FILES['fileToUpload']['type'] == "image/png") {
          // $ra = mt_rand(100, 999);
          // $root = "views/img/users/" . $_POST["name"] . "/" . $ra . ".png";

          $source = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
          $destination = imagecreatetruecolor($newWidth, $newHeight);
          imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
          imagejpeg($destination, $target_file);
      }
      if ($_FILES['fileToUpload']['type'] == "image/jpg") {
          // $ra = mt_rand(100, 999);
          // $root = "views/img/users/" . $_POST["name"] . "/" . $ra . ".png";

          $source = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
          $destination = imagecreatetruecolor($newWidth, $newHeight);
          imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
          imagejpeg($destination, $target_file);
      }
      // **********************************************
      if (!empty($data['oldImage'])) {
        //Removing the old image if exist
        $db1->query("UPDATE students_tbl SET id = :class_id, 
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

        $db1->bind(':class_id', $data['class_name']);
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

        if (unlink($data['oldImage']) && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) && $db1->execute()) {
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Success";
          $_SESSION['sessionMsg'] = "Updated successfully";
          $_SESSION['sessionIcon'] = "success";
          $_SESSION['location'] = "student-view-page";
        } else {
          $db1->Disconect();
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Error";
          $_SESSION['sessionMsg'] = "Update fail";
          $_SESSION['sessionIcon'] = "error";
          $_SESSION['location'] = "student-view-page";
        }
      } else {
        $db1->query("UPDATE students_tbl SET class_id = :class_id, 
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

        $db1->bind(':class_id', $data['class_name']);
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

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) && $db1->execute()) {
          $db->Disconect();
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Success";
          $_SESSION['sessionMsg'] = "Updated successfully";
          $_SESSION['sessionIcon'] = "success";
          $_SESSION['location'] = "student-view-page";
        } else {
          $db->Disconect();
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Error";
          $_SESSION['sessionMsg'] = "Update fail";
          $_SESSION['sessionIcon'] = "error";
          $_SESSION['location'] = "student-view-page";
        }
      }
    }
    $db1->Disconect();
  } else { //Update without image file
    $db1->query("UPDATE students_tbl SET class_id = :class_id, 
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

    $db1->bind(':class_id', $data['class_name']);
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
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Error";
      $_SESSION['sessionMsg'] = "Update failed!";
      $_SESSION['sessionIcon'] = "error";
      $_SESSION['location'] = "student-view-page";
      die($db->getError());
    } else {
      $_SESSION['errorMsg'] = true;
      $_SESSION['errorTitle'] = "Success";
      $_SESSION['sessionMsg'] = "Update successfully";
      $_SESSION['sessionIcon'] = "success";
      $_SESSION['location'] = "student-view-page";
    }
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
  <!-- Student Content Row -->
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
    <div class="form-row">
      <?php
      if (isset($_POST['editBtn'])) {
        $db = new Database();
        $admNo = $_POST['admNo'];
        $db->query("SELECT * FROM students_tbl AS st JOIN class_tbl ON class_tbl.class_id = st.class_id WHERE admNo = :admNo;");
        $db->bind('admNo', $admNo);

        if ($db->execute()) {
          if ($db->rowCount() > 0) {
            $results = $db->resultset();
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
          <select name="class_id" id="class_name" class="form-control">
            <option value="<?php echo $result->class_id; ?>"> <?php echo $result->class_name; ?></option>
            <!-- Fetching data from class table -->
            <?php
              $db2 = new Database();
              $db2->query("SELECT * FROM class_tbl;");
              if ($db2->execute()) {
                if ($db2->rowCount() > 0) {
                  $datas = $db2->resultset();
                  foreach ($datas as $data) {
            ?>
                  <option value="<?php echo $data->class_id; ?>"> <?php echo $data->class_name; ?> </option>
                <?php
                  }
                  $db2->Disconect();
                } else {
                ?>
                <option value=""> No data </option>
            <?php
                }
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