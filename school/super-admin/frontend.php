<?php
include('includes/header.php');

if (isset($_POST['update_btn']))
{
    $db = new Database();

    $school_name = trim(strtoupper($_POST['school_name']));
    $school_sections = trim($_POST['school_sections']);
    $school_addr = trim($_POST['school_addr']);
    $school_contact1 = trim($_POST['school_contact1']);
    $school_contact2 = trim($_POST['school_contact2']);
    $email = trim($_POST['email']);
    $project_name = trim(strtoupper($_POST['project_name']));
    $project_note = trim($_POST['project_note']);
    $footer = trim($_POST['footer']);
    $fileToRemove = trim($_POST['fileToRemove']);
    
    // For image upload
    $fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
    //specifying the directory where the file is going to be placed.
    $target_dir = "../uploads/img/";
    //specifying path of the file to be uploaded
    $target_file = $target_dir.basename($fileToUpload);
    //Getting the file extension
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //check if file type is an image
    $imgType = ["jpg", "gif", "jpeg", "png"];

    if ($fileToUpload) 
    { //Select Image
        if(!in_array($imageFileType, $imgType)) {
            $error = true;
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Warning";
            $_SESSION['sessionMsg'] = "File not supported";
            $_SESSION['sessionIcon'] = "warning";
            $_SESSION['location'] = "frontend";
        }
        else 
        {
            //Checking for image size
            if($_FILES['fileToUpload']['size'] > 102405 || $_FILES['fileToUpload']['size'] < 1024) {
                $error = true;
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Warning";
                $_SESSION['sessionMsg'] = "Image size within 15KB to 100KB";
                $_SESSION['sessionIcon'] = "warning";
                $_SESSION['location'] = "frontend";
            }        
            // check if file exists
            else if(file_exists($target_file)) {
                $error = true;
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Warning";
                $_SESSION['sessionMsg'] = "Picture exist!";
                $_SESSION['sessionIcon'] = "warning";
                $_SESSION['location'] = "frontend";
            }
            else{
                // Check if file to change is empty or not
                if(empty($fileToRemove) || ($fileToRemove == null) || !file_exists($fileToRemove)){ 
                    $db->query("UPDATE frontend_tbl
                        SET
                            img_logo = :img_logo, 
                            school_name = :school_name,
                            school_sections = :school_sections,
                            school_addr = :school_addr,
                            school_contact1 = :school_contact1,
                            school_contact2 = :school_contact2,
                            email = :email,
                            project_name = :project_name,
                            project_note = :project_note,
                            footer = :footer;"
                    );
                    $db->bind(':img_logo', $target_file);
                    $db->bind(':school_name', $school_name);
                    $db->bind(':school_sections', $school_sections);
                    $db->bind(':school_addr', $school_addr);
                    $db->bind(':school_contact1', $school_contact1);
                    $db->bind(':school_contact2', $school_contact2);
                    $db->bind(':email', $email);
                    $db->bind(':project_name', $project_name);
                    $db->bind(':project_note', $project_note);
                    $db->bind(':footer', $footer);
                    if ($db->execute()){
                        if (($db->rowCount() > 0) && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
                            $error = true;
                            $_SESSION['errorMsg'] = true;
                            $_SESSION['errorTitle'] = "Success";
                            $_SESSION['sessionMsg'] = "Updated successully";
                            $_SESSION['sessionIcon'] = "success";
                            $_SESSION['location'] = "frontend";
                        }else{
                            $error = true;
                            $_SESSION['errorMsg'] = true;
                            $_SESSION['errorTitle'] = "Error";
                            $_SESSION['sessionMsg'] = "Update failed";
                            $_SESSION['sessionIcon'] = "error";
                            $_SESSION['location'] = "frontend";
                        }
                    }else{
                        die($db->getError());
                    }
                }else{
                    $db->query("UPDATE frontend_tbl
                        SET
                            img_logo = :img_logo, 
                            school_name = :school_name,
                            school_sections = :school_sections,
                            school_addr = :school_addr,
                            school_contact1 = :school_contact1,
                            school_contact2 = :school_contact2,
                            email = :email,
                            project_name = :project_name,
                            project_note = :project_note,
                            footer = :footer;"
                    );
                    $db->bind(':img_logo', $target_file);
                    $db->bind(':school_name', $school_name);
                    $db->bind(':school_sections', $school_sections);
                    $db->bind(':school_addr', $school_addr);
                    $db->bind(':school_contact1', $school_contact1);
                    $db->bind(':school_contact2', $school_contact2);
                    $db->bind(':email', $email);
                    $db->bind(':project_name', $project_name);
                    $db->bind(':project_note', $project_note);
                    $db->bind(':footer', $footer);
                    if (($db->execute()) && (unlink($fileToRemove))){
                        if (($db->rowCount() > 0) && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
                            $error = true;
                            $_SESSION['errorMsg'] = true;
                            $_SESSION['errorTitle'] = "Success";
                            $_SESSION['sessionMsg'] = "Updated successully";
                            $_SESSION['sessionIcon'] = "success";
                            $_SESSION['location'] = "frontend";
                        }else{
                            $error = true;
                            $_SESSION['errorMsg'] = true;
                            $_SESSION['errorTitle'] = "Error";
                            $_SESSION['sessionMsg'] = "Update failed";
                            $_SESSION['sessionIcon'] = "error";
                            $_SESSION['location'] = "frontend";
                        }
                    }else{
                        die($db->getError());
                    }
                }
                
            }
        }
    }
    else
    {
        $db->query("UPDATE frontend_tbl
            SET
                school_name = :school_name,
                school_sections = :school_sections,
                school_addr = :school_addr,
                school_contact1 = :school_contact1,
                school_contact2 = :school_contact2,
                email = :email,
                project_name = :project_name,
                project_note = :project_note,
                footer = :footer;"
        );
        $db->bind(':school_name', $school_name);
        $db->bind(':school_sections', $school_sections);
        $db->bind(':school_addr', $school_addr);
        $db->bind(':school_contact1', $school_contact1);
        $db->bind(':school_contact2', $school_contact2);
        $db->bind(':email', $email);
        $db->bind(':project_name', $project_name);
        $db->bind(':project_note', $project_note);
        $db->bind(':footer', $footer);
        if ($db->execute()){
            if ($db->rowCount() > 0){
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Success";
                $_SESSION['sessionMsg'] = "Updated successully";
                $_SESSION['sessionIcon'] = "success";
                $_SESSION['location'] = "frontend";
            }else{
                $error = true;
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Error";
                $_SESSION['sessionMsg'] = "Update failed";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "frontend";
            }
        }else{
            die($db->getError());
        }
    }
    $db->Disconect();
}
if (isset($_POST['submit_btn']))
{
    $db = new Database();

    $school_name = trim(strtoupper($_POST['school_name']));
    $school_sections = trim($_POST['school_sections']);
    $school_addr = trim($_POST['school_addr']);
    $school_contact1 = trim($_POST['school_contact1']);
    $school_contact2 = trim($_POST['school_contact2']);
    $email = trim($_POST['email']);
    $project_name = trim(strtoupper($_POST['project_name']));
    $project_note = trim($_POST['project_note']);
    $footer = trim($_POST['footer']);
    
    //Validating Image files
    if (isset($_FILES["fileToUpload"]["tmp_name"])) {

        //check if file type is an image
        $imgType = ["jpg", "gif", "jpeg", "png"];

        list($width, $height) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        //var_dump($_FILES["fileToUpload"]["type"]);
        $newWidth = 500;
        $newHeight = 500;
        $directory = "../uploads/img/";
        $fileToUpload = "";

        if ($_FILES['fileToUpload']['type'] == "image/jpeg") {
            $ra = mt_rand(100, 999);
            $fileToUpload = $directory.$ra.".jpeg";

            $source = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
            $destination = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagejpeg($destination, $fileToUpload);
        }

        if ($_FILES['fileToUpload']['type'] == "image/png") {
            $ra = mt_rand(100, 999);
            $fileToUpload = $directory.$ra.".png";

            $source = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
            $destination = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagepng($destination, $fileToUpload);
        }
        
        if ($_FILES['fileToUpload']['type'] == "image/gif") {
            $ra = mt_rand(100, 999);
            $fileToUpload = $directory.$ra.".gif";

            $source = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
            $destination = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagegif($destination, $fileToUpload);
        }
        
        //Getting the file extension
        $imageFileType = strtolower(pathinfo($fileToUpload, PATHINFO_EXTENSION));

        if(!in_array($imageFileType, $imgType)){
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Warning";
            $_SESSION['sessionMsg'] = "File not support";
            $_SESSION['sessionIcon'] = "warning";
            $_SESSION['location'] = "frontend";
        }       
        else{

            // var_dump($fileToUpload);
            $db->query("INSERT INTO frontend_tbl
            (
                img_logo,
                school_name,
                school_sections,
                school_addr,
                school_contact1,
                school_contact2,
                email,
                project_name,
                project_note,
                footer
            ) 
            VALUES
            (
                :img_logo,
                :school_name,
                :school_sections,
                :school_addr,
                :school_contact1,
                :school_contact2,
                :email,
                :project_name,
                :project_note,
                :footer
            );");
            $db->bind(':img_logo', $fileToUpload);
            $db->bind(':school_name', $school_name);
            $db->bind(':school_sections', $school_sections);
            $db->bind(':school_addr', $school_addr);
            $db->bind(':school_contact1', $school_contact1);
            $db->bind(':school_contact2', $school_contact2);
            $db->bind(':email', $email);
            $db->bind(':project_name', $project_name);
            $db->bind(':project_note', $project_note);
            $db->bind(':footer', $footer);
            if ($db->execute()){
                if (($db->rowCount() > 0) && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $fileToUpload)){
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Success";
                    $_SESSION['sessionMsg'] = "Record added successufully";
                    $_SESSION['sessionIcon'] = "success";
                    $_SESSION['location'] = "frontend";
                }else{
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "Upload failed!";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "frontend";
                }
            }else{
                die($db->getError());
            }
        }
        
    }else 
    {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Warning";
        $_SESSION['sessionMsg'] = "Please upload logo image!";
        $_SESSION['sessionIcon'] = "warning";
        $_SESSION['location'] = "frontend";
    }

    $db->Disconect();
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex  mb-3">
        <h1 class="mb-0 font-weight-bold text-primary">Settings For The Frontend</h1>
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
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <?php
                $db = new Database();
                $db->query("SELECT * FROM frontend_tbl");
                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->single();
                ?>
                        <div class="form-group text-center">
                            <img id="image" src="<?php echo $row->img_logo; ?>" class="form-control-img img-thumbnail" width="100px" height="100px" />
                            <p class="text-danger" style="font-size: 13px;"> Logo image type should be png and size should be in the range of 1KB to 100KB</p>
                            <input type="file" name="fileToUpload" onchange="loadFile(event)" />
                            <input type="hidden" name="fileToRemove" value="<?php echo $row->img_logo; ?>" />
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="school_name">School name:</label>
                            <input type="text" name="school_name" class="form-control" value="<?php echo $row->school_name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="school_sections">School sections:</label>
                            <input type="text" name="school_sections" class="form-control" value="<?php echo $row->school_sections; ?>">
                        </div>
                        <div class="form-group">
                            <label for="school_addr">School address:</label>
                            <input type="text" name="school_addr" class="form-control" value="<?php echo $row->school_addr; ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="school_contact1" placeholder="Contact" class="form-control" value="<?php echo $row->school_contact1; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="school_contact2" placeholder="Contact" class="form-control" value="<?php echo $row->school_contact2; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $row->email; ?>" placeholder="Email">
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="project_name">Project name/title:</label>
                            <input type="text" name="project_name" class="form-control" value="<?php echo $row->project_name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="project_note">Project comments/notes:</label>
                            <textarea type="text" name="project_note" class="form-control"> <?php echo $row->project_note; ?> </textarea>
                        </div>
                        <div class="form-group">
                            <label for="footer">Footer note:</label>
                            <input type="text" name="footer" class="form-control" value="<?php echo $row->footer; ?>">
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" name="update_btn" class="btn btn-primary spinner_btn" onclick="add_spinner()">Update</button>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="form-group text-center">
                            <img id="image" class="form-control-img img-thumbnail" width="100px" height="100px" />
                            <p class="text-danger" style="font-size: 13px;"> Logo image type should be png and size should be in the range of 1KB to 100KB</p>
                            <input type="file" name="fileToUpload" onchange="loadFile(event)" required/>
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="school_name">School name:</label>
                            <input type="text" name="school_name" placeholder="School name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="school_sections">School sections:</label>
                            <input type="text" name="school_sections" placeholder="School sections separated with comma" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="school_addr">School address:</label>
                            <input type="text" name="school_addr" placeholder="School address" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="school_contact1" placeholder="Contact 1" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="school_contact2" placeholder="Contact 2" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="School email">
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="project_name">Project name/title:</label>
                            <input type="text" name="project_name" placeholder="Project name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="project_note">Project comments/notes:</label>
                            <textarea type="text" name="project_note" placeholder="Project information" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="footer">Footer note:</label>
                            <input type="text" name="footer" placeholder="Footer text" class="form-control" required>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" name="submit_btn" class="btn btn-primary spinner_btn" onclick="add_spinner()">Submit</button>
                        </div>
                <?php
                    }
                } else {
                    die($db->getError());
                }
                $db->Disconect();
                ?>
            </form>
        </div>
        <div class="col-md-2"></div>
    </div>

</div>
<!-- /.container-fluid -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>