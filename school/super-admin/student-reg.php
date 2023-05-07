<?php
include('includes/header.php');

if (isset($_POST['submit_btn'])) {
  $db = new Database();
  //Checking database connection  
  if (!$db->isConnected()) {
    echo $db->getError() . PHP_EOL;
  }
  $error = false;
  $section  = $_POST['section'];

  $data = array(
    'student_sname' => trim(strtoupper($_POST['student_sname'])),
    'student_lname' => trim(strtoupper($_POST['student_lname'])),
    'student_oname' => trim(strtoupper($_POST['student_oname'])),
    'class_name' => trim(strtoupper($_POST['class_name'])),
    'dob' => $_POST['dob'],
    'religion' => trim(strtoupper($_POST['religion'])),
    'gender' => trim(strtoupper($_POST['gender'])),
    'nationality' => trim(strtoupper($_POST['nationality'])),
    'student_state' => trim(strtoupper($_POST['student_state'])),
    'lga' => trim(strtoupper($_POST['lga']))
  );

  // For image upload
  $fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);

  if ($fileToUpload) { //Select Image
    //specifying the directory where the file is going to be placed.
    $target_dir = "../uploads/students/";
    //specifying path of the file to be uploaded
    $target_file = $target_dir . basename($fileToUpload);
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
    if (!$error) { //If error free
      if ($section == "NS/") { //Nursery school
        $section_nur = $section . date('y') . "/";
        $db->query("SELECT student_id FROM students_tbl  ORDER BY student_id DESC LIMIT 1;"); //Get the last record for creation of custom adm number
        $result = $db->resultset();
        if ($db->rowCount() > 0) { //Creating admission number from last record if available
          foreach ($result as $record) {
            $lastID = $record->student_id;
            $getNumber = str_replace($section_nur, "", $lastID);
            $id_increase = $getNumber + 1;
            $get_string = str_pad($id_increase, 4, 0, STR_PAD_LEFT);
            $id = $section_nur . $get_string;
            $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);

            $db->query(
              "INSERT INTO 
              students_tbl(admNo, pwd, sname, lname, oname, class_name, passport, dob, religion, gender, nationality, 
              student_state,lga) 
              VALUES(:id, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, :target_file, 
              :dob, :religion, :gender, :nationality, :student_state, :lga);"
            );
            $db->bind(':id', $id);
            $db->bind(':hash_pwd', $hash_pwd);
            $db->bind(':student_sname', $data['student_sname']);
            $db->bind(':student_lname', $data['student_lname']);
            $db->bind(':student_oname', $data['student_oname']);
            $db->bind(':class_name', $data['class_name']);
            $db->bind(':target_file', $target_file);
            $db->bind(':dob', $data['dob']);
            $db->bind(':religion', $data['religion']);
            $db->bind(':gender', $data['gender']);
            $db->bind(':nationality', $data['nationality']);
            $db->bind(':student_state', $data['student_state']);
            $db->bind(':lga', $data['lga']);

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) && $db->execute()) {
              $successMsg = "Record added successfully";
            } else {
              $warningMsg = "Error! record could not be uploaded";
            }
          }
        } else { //Initial admission number if no record in the database
          $section_nur = $section . date('y') . "/0001";
          $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);

          $db->query(
            "INSERT INTO 
            students_tbl(admNo, pwd, sname, lname, oname, class_name, passport, dob, religion, gender, nationality, 
            student_state,lga) 
            VALUES(:section_nur, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, :target_file, 
            :dob, :religion, :gender, :nationality, :student_state, :lga);"
          );
          $db->bind(':section_nur', $section_nur);
          $db->bind(':hash_pwd', $hash_pwd);
          $db->bind(':student_sname', $data['student_sname']);
          $db->bind(':student_lname', $data['student_lname']);
          $db->bind(':student_oname', $data['student_oname']);
          $db->bind(':class_name', $data['class_name']);
          $db->bind(':target_file', $target_file);
          $db->bind(':dob', $data['dob']);
          $db->bind(':religion', $data['religion']);
          $db->bind(':gender', $data['gender']);
          $db->bind(':nationality', $data['nationality']);
          $db->bind(':student_state', $data['student_state']);
          $db->bind(':lga', $data['lga']);

          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) && $db->execute()) {
            $successMsg = "Record added successfully";
          } else {
            $warningMsg = "Error! record could not be uploaded";
          }
        }
      } elseif ($section == "PS/") { //Primary school
        $section_nur = $section . date('y') . "/";
        $db->query("SELECT student_id FROM students_tbl  ORDER BY student_id DESC LIMIT 1;"); //Get the last record for creation of custom adm number
        $result = $db->resultset();
        if ($db->rowCount() > 0) { //Creating admission number from last record if available
          foreach ($result as $record) {
            $lastID = $record->student_id;
            $getNumber = str_replace($section_nur, "", $lastID);
            $id_increase = $getNumber + 1;
            $get_string = str_pad($id_increase, 4, 0, STR_PAD_LEFT);
            $id = $section_nur . $get_string;
            $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);

            $db->query(
              "INSERT INTO 
              students_tbl(admNo, pwd, sname, lname, oname, class_name, passport, dob, religion, gender, nationality, 
              student_state,lga) 
              VALUES(:id, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, :target_file, 
              :dob, :religion, :gender, :nationality, :student_state, :lga);"
            );
            $db->bind(':id', $id);
            $db->bind(':hash_pwd', $hash_pwd);
            $db->bind(':student_sname', $data['student_sname']);
            $db->bind(':student_lname', $data['student_lname']);
            $db->bind(':student_oname', $data['student_oname']);
            $db->bind(':class_name', $data['class_name']);
            $db->bind(':target_file', $target_file);
            $db->bind(':dob', $data['dob']);
            $db->bind(':religion', $data['religion']);
            $db->bind(':gender', $data['gender']);
            $db->bind(':nationality', $data['nationality']);
            $db->bind(':student_state', $data['student_state']);
            $db->bind(':lga', $data['lga']);

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) && $db->execute()) {
              $successMsg = "Record added successfully";
            } else {
              $warningMsg = "Error! record could not be uploaded";
            }
          }
        } else { //Initial admission number if no record in the database
          $section_nur = $section . date('y') . "/0001";
          $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);

          $db->query(
            "INSERT INTO 
            students_tbl(admNo, pwd, sname, lname, oname, class_name, passport, dob, religion, gender, nationality, 
            student_state,lga) 
            VALUES(:section_nur, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, :target_file, 
            :dob, :religion, :gender, :nationality, :student_state, :lga);"
          );
          $db->bind(':section_nur', $section_nur);
          $db->bind(':hash_pwd', $hash_pwd);
          $db->bind(':student_sname', $data['student_sname']);
          $db->bind(':student_lname', $data['student_lname']);
          $db->bind(':student_oname', $data['student_oname']);
          $db->bind(':class_name', $data['class_name']);
          $db->bind(':target_file', $target_file);
          $db->bind(':dob', $data['dob']);
          $db->bind(':religion', $data['religion']);
          $db->bind(':gender', $data['gender']);
          $db->bind(':nationality', $data['nationality']);
          $db->bind(':student_state', $data['student_state']);
          $db->bind(':lga', $data['lga']);

          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) && $db->execute()) {
            $successMsg = "Record added successfully";
          } else {
            $warningMsg = "Error! record could not be uploaded";
          }
        }
      } elseif ($section == "SS/") { //Secondary School
        $section_nur = $section . date('y') . "/";
        $db->query("SELECT student_id FROM students_tbl  ORDER BY student_id DESC LIMIT 1;"); //Get the last record for creation of custom adm number
        $result = $db->resultset();
        if ($db->rowCount() > 0) { //Creating admission number from last record if available
          foreach ($result as $record) {
            $lastID = $record->student_id;
            $getNumber = str_replace($section_nur, "", $lastID);
            $id_increase = $getNumber + 1;
            $get_string = str_pad($id_increase, 4, 0, STR_PAD_LEFT);
            $id = $section_nur . $get_string;
            $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);

            $db->query(
              "INSERT INTO 
              students_tbl(admNo, pwd, sname, lname, oname, class_name, passport, dob, religion, gender, nationality, 
              student_state,lga) 
              VALUES(:id, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, :target_file, 
              :dob, :religion, :gender, :nationality, :student_state, :lga);"
            );
            $db->bind(':id', $id);
            $db->bind(':hash_pwd', $hash_pwd);
            $db->bind(':student_sname', $data['student_sname']);
            $db->bind(':student_lname', $data['student_lname']);
            $db->bind(':student_oname', $data['student_oname']);
            $db->bind(':class_name', $data['class_name']);
            $db->bind(':target_file', $target_file);
            $db->bind(':dob', $data['dob']);
            $db->bind(':religion', $data['religion']);
            $db->bind(':gender', $data['gender']);
            $db->bind(':nationality', $data['nationality']);
            $db->bind(':student_state', $data['student_state']);
            $db->bind(':lga', $data['lga']);

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) && $db->execute()) {
              $successMsg = "Record added successfully";
            } else {
              $warningMsg = "Error! record could not be uploaded";
            }
          }
        } else { //Initial admission number if no record in the database
          $section_nur = $section . date('y') . "/0001";
          $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);

          $db->query(
            "INSERT INTO 
            students_tbl(admNo, pwd, sname, lname, oname, class_name, passport, dob, religion, gender, nationality, 
            student_state,lga) 
            VALUES(:section_nur, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, :target_file, 
            :dob, :religion, :gender, :nationality, :student_state, :lga);"
          );
          $db->bind(':section_nur', $section_nur);
          $db->bind(':hash_pwd', $hash_pwd);
          $db->bind(':student_sname', $data['student_sname']);
          $db->bind(':student_lname', $data['student_lname']);
          $db->bind(':student_oname', $data['student_oname']);
          $db->bind(':class_name', $data['class_name']);
          $db->bind(':target_file', $target_file);
          $db->bind(':dob', $data['dob']);
          $db->bind(':religion', $data['religion']);
          $db->bind(':gender', $data['gender']);
          $db->bind(':nationality', $data['nationality']);
          $db->bind(':student_state', $data['student_state']);
          $db->bind(':lga', $data['lga']);

          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) && $db->execute()) {
            $successMsg = "Record added successfully";
          } else {
            $warningMsg = "Error! record could not be uploaded";
          }
        }
      }
    }
  } else { //Uploading without image file
    if (!$error) { //If error free
      if ($section == "NS/") { //Nursery school
        $section_nur = $section . date('y') . "/";
        $db->query("SELECT student_id FROM students_tbl  ORDER BY student_id DESC LIMIT 1;"); //Get the last record for creation of custom adm number
        $result = $db->resultset();
        if ($db->rowCount() > 0) { //Creating admission number from last record if available
          foreach ($result as $record) {
            $lastID = $record->student_id;
            $getNumber = str_replace($section_nur, "", $lastID);
            $id_increase = $getNumber + 1;
            $get_string = str_pad($id_increase, 4, 0, STR_PAD_LEFT);
            $id = $section_nur . $get_string;
            $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);

            $db->query(
              "INSERT INTO 
              students_tbl(admNo, pwd, sname, lname, oname, class_name, dob, religion, gender, nationality, 
              student_state,lga) 
              VALUES(:id, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, :target_file, 
              :dob, :religion, :gender, :nationality, :student_state, :lga);"
            );
            $db->bind(':id', $id);
            $db->bind(':hash_pwd', $hash_pwd);
            $db->bind(':student_sname', $data['student_sname']);
            $db->bind(':student_lname', $data['student_lname']);
            $db->bind(':student_oname', $data['student_oname']);
            $db->bind(':class_name', $data['class_name']);
            $db->bind(':dob', $data['dob']);
            $db->bind(':religion', $data['religion']);
            $db->bind(':gender', $data['gender']);
            $db->bind(':nationality', $data['nationality']);
            $db->bind(':student_state', $data['student_state']);
            $db->bind(':lga', $data['lga']);
            if ($db->execute()) {
              $successMsg = "Record added successfully";
            } else {
              //unlink($target_file);TODO
              $warningMsg = "Submittion failed!";
            }
          }
        } else { //Initial admission number if no record in the database
          $section_nur = $section . date('y') . "/0001";
          $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);
          $db->query(
            "INSERT INTO 
            students_tbl(admNo, pwd, sname, lname, oname, class_name, dob, religion, gender, nationality, 
            student_state,lga) 
            VALUES(:section_nur, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, 
            :dob, :religion, :gender, :nationality, :student_state, :lga);"
          );
          $db->bind(':section_nur', $section_nur);
          $db->bind(':hash_pwd', $hash_pwd);
          $db->bind(':student_sname', $data['student_sname']);
          $db->bind(':student_lname', $data['student_lname']);
          $db->bind(':student_oname', $data['student_oname']);
          $db->bind(':class_name', $data['class_name']);
          $db->bind(':dob', $data['dob']);
          $db->bind(':religion', $data['religion']);
          $db->bind(':gender', $data['gender']);
          $db->bind(':nationality', $data['nationality']);
          $db->bind(':student_state', $data['student_state']);
          $db->bind(':lga', $data['lga']);

          if ($db->execute()) {
            $successMsg = "Record added successfully";
          } else {
            $warningMsg = "Submittion failed!";
          }
        }
      } elseif ($section == "PS/") { //Primary school
        $section_nur = $section . date('y') . "/";
        $db->query("SELECT student_id FROM students_tbl  ORDER BY student_id DESC LIMIT 1;"); //Get the last record for creation of custom adm number
        $result = $db->resultset();
        if ($db->rowCount() > 0) { //Creating admission number from last record if available
          foreach ($result as $record) {
            $lastID = $record->student_id;
            $getNumber = str_replace($section_nur, "", $lastID);
            $id_increase = $getNumber + 1;
            $get_string = str_pad($id_increase, 4, 0, STR_PAD_LEFT);
            $id = $section_nur . $get_string;
            $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);
            $db->query(
              "INSERT INTO 
              students_tbl(admNo, pwd, sname, lname, oname, class_name, dob, religion, gender, nationality, 
              student_state,lga) 
              VALUES(:id, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, 
              :dob, :religion, :gender, :nationality, :student_state, :lga);"
            );
            $db->bind(':id', $id);
            $db->bind(':hash_pwd', $hash_pwd);
            $db->bind(':student_sname', $data['student_sname']);
            $db->bind(':student_lname', $data['student_lname']);
            $db->bind(':student_oname', $data['student_oname']);
            $db->bind(':class_name', $data['class_name']);
            $db->bind(':dob', $data['dob']);
            $db->bind(':religion', $data['religion']);
            $db->bind(':gender', $data['gender']);
            $db->bind(':nationality', $data['nationality']);
            $db->bind(':student_state', $data['student_state']);
            $db->bind(':lga', $data['lga']);

            if ($db->execute()) {
              $successMsg = "Record added successfully";
            } else {
              //unlink($target_file);TODO
              $warningMsg = "Submittion failed!";
            }
          }
        } else { //Initial admission number if no record in the database
          $section_nur = $section . date('y') . "/0001";
          $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);
          $db->query(
            "INSERT INTO 
            students_tbl(admNo, pwd, sname, lname, oname, class_name, dob, religion, gender, nationality, 
            student_state,lga) 
            VALUES(:section_nur, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, 
            :dob, :religion, :gender, :nationality, :student_state, :lga);"
          );
          $db->bind(':section_nur', $section_nur);
          $db->bind(':hash_pwd', $hash_pwd);
          $db->bind(':student_sname', $data['student_sname']);
          $db->bind(':student_lname', $data['student_lname']);
          $db->bind(':student_oname', $data['student_oname']);
          $db->bind(':class_name', $data['class_name']);
          $db->bind(':target_file', $target_file);
          $db->bind(':dob', $data['dob']);
          $db->bind(':religion', $data['religion']);
          $db->bind(':gender', $data['gender']);
          $db->bind(':nationality', $data['nationality']);
          $db->bind(':student_state', $data['student_state']);
          $db->bind(':lga', $data['lga']);
          if ($db->execute()) {
            $successMsg = "Record added successfully";
          } else {
            $warningMsg = "Submittion failed!";
          }
        }
      } elseif ($section == "SS/") { //Secondary School
        $section_nur = $section . date('y') . "/";
        $db->query("SELECT student_id FROM students_tbl  ORDER BY student_id DESC LIMIT 1;"); //Get the last record for creation of custom adm number
        $result = $db->resultset();
        if ($db->rowCount() > 0) { //Creating admission number from last record if available
          foreach ($result as $record) {
            $lastID = $record->student_id;
            $getNumber = str_replace($section_nur, "", $lastID);
            $id_increase = $getNumber + 1;
            $get_string = str_pad($id_increase, 4, 0, STR_PAD_LEFT);
            $id = $section_nur . $get_string;
            $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);
            $db->query(
              "INSERT INTO 
              students_tbl(admNo, pwd, sname, lname, oname, class_name, dob, religion, gender, nationality, 
              student_state,lga) 
              VALUES(:id, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, 
              :dob, :religion, :gender, :nationality, :student_state, :lga);"
            );
            $db->bind(':id', $id);
            $db->bind(':hash_pwd', $hash_pwd);
            $db->bind(':student_sname', $data['student_sname']);
            $db->bind(':student_lname', $data['student_lname']);
            $db->bind(':student_oname', $data['student_oname']);
            $db->bind(':class_name', $data['class_name']);
            $db->bind(':dob', $data['dob']);
            $db->bind(':religion', $data['religion']);
            $db->bind(':gender', $data['gender']);
            $db->bind(':nationality', $data['nationality']);
            $db->bind(':student_state', $data['student_state']);
            $db->bind(':lga', $data['lga']);

            if ($db->execute()) {
              $successMsg = "Record added successfully";
            } else {
              //unlink($target_file);TODO
              $warningMsg = "Submittion failed!";
            }
          }
        } else { //Initial admission number if no record in the database
          $section_nur = $section . date('y') . "/0001";
          $hash_pwd = password_hash('123654', PASSWORD_BCRYPT);
          $db->query(
            "INSERT INTO 
            students_tbl(admNo, pwd, sname, lname, oname, class_name, dob, religion, gender, nationality, 
            student_state,lga) 
            VALUES(:section_nur, :hash_pwd, :student_sname, :student_lname, :student_oname, :class_name, 
            :dob, :religion, :gender, :nationality, :student_state, :lga);"
          );
          $db->bind(':section_nur', $section_nur);
          $db->bind(':hash_pwd', $hash_pwd);
          $db->bind(':student_sname', $data['student_sname']);
          $db->bind(':student_lname', $data['student_lname']);
          $db->bind(':student_oname', $data['student_oname']);
          $db->bind(':class_name', $data['class_name']);
          $db->bind(':dob', $data['dob']);
          $db->bind(':religion', $data['religion']);
          $db->bind(':gender', $data['gender']);
          $db->bind(':nationality', $data['nationality']);
          $db->bind(':student_state', $data['student_state']);
          $db->bind(':lga', $data['lga']);

          if ($db->execute()) {
            $successMsg = "Record added successfully";
          } else {
            $warningMsg = "Submittion failed!";
          }
        }
      }
    }
  }
  $db->Disconect();
}


?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <div class="align-items-center justify-content-center ">
    <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Student Registration Page </h3>
    <p> Fields with asterisk (*) are to be filled </p>
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
  <form method="post" action="student-reg" enctype="multipart/form-data">
    <div class="form-row">
      <div class="col-md-4"> </div>
      <div class="form-group col-md-4">
        <center>
          <img id="image" class="form-control-img img-thumbnail" width="100px" height="100px" />
          <p class="text-danger" style="font-size: 13px;"> Image size should be in the range of 15KB to 100KB</p>
          <input type="file" name="fileToUpload" onchange="loadFile(event)" />
        </center>
      </div>
      <div class="col-md-4"> </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-4">
        <label for="section">* Select section: </label>
        <select name="section" id="section" class="form-control">
          <option value=""> Select section... </option>
          <option value="NS/"> Nursery </option>
          <option value="PS/"> Primary </option>
          <option value="SS/"> Secondary </option>
        </select>
      </div>
    </div>
    <!-- First Row -->
    <div class="form-row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="class_name" class="control-label">* Class </label>
          <!-- Fetching data from class table -->
          <?php
          $db = new Database();
          $db->query("SELECT * FROM class_tbl;");
          $data = $db->resultset();
          ?>
          <select name="class_name" id="class_name" class="form-control">
            <option value=""> Select class...</option>
            <?php
            if ($db->rowCount() > 0) {
              foreach ($data as $record) {
            ?>
                <option value="<?php echo $record->class_name; ?>"> <?php echo $record->class_name; ?> </option>
              <?php
              }
            } else {
              $db->Disconect();
              ?>
              <option value=""> No record</option>
            <?php
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label" for="student_sname">* Surname: </label>
          <input type="text" class="form-control" id="student_sname" placeholder="Surname" max="5" name="student_sname" auto-compplete="off">
        </div>
      </div>
      <div class="col-md-4 ">
        <div class="form-group">
          <label class="control-label" for="student_lname">* Last name: </label>
          <input type="text" class="form-control" placeholder="Lastname" name="student_lname" auto-compplete="off" auto-compplete="off">
        </div>
      </div>
    </div>
    <!-- Second Row -->
    <div class="form-row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label" for="student_oname"> Other name: </label>
          <input type="text" class="form-control" placeholder="Othername" name="student_oname" auto-compplete="off">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="dob">* D.O.B</label>
          <input type="date" name="dob" class="form-control">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="religion">* Religion</label>
          <select name="religion" class="form-control">
            <option value=""> Select religion </option>
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
            <option value=""> Select gender...</option>
            <option value="Male"> Male </option>
            <option value="Female"> Female </option>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="nationality"> * Nationality </label>
          <input class="form-control" name="nationality" list="datalistioptions1" placeholder="Type in country" auto_complete="off">
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
          <input class="form-control" name="student_state" list="datalistioptions" placeholder="Type in state" auto_complete="off">
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
          <input name="lga" class="form-control" placeholder="Enter LGA" auto-compplete="off">
        </div>
      </div>
    </div>
    <!-- Fourth Row -->
    <div class="form-row">
      <div class="col-md-12 text-center">
        <div class="fom-group">
          <button type="submit" class="btn btn-primary" name="submit_btn"> Submit </button>
        </div>
      </div>
    </div>
  </form>
</div>

<?php
include('includes/footer.php');
include('includes/script.php');
?>