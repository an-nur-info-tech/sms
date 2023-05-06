<?php
include('includes/header.php');

if(isset($_POST['submit_btn']))
{
  $error = false;
  $class_id = mysqli_real_escape_string($con, $_POST['class_id']);
  $subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);
  $instructor_id = mysqli_real_escape_string($con, $_POST['instructor_id']);
   
  if(empty($class_id))
  {
    $error = true;
    $warningMsg = "Select Class";
  }
  if(empty($subject_id))
  {
    $error = true;
    $warningMsg = "Select Subject";
  }
  if(empty($instructor_id))
  {
    $error = true;
    $warningMsg = "Select Instuctor";
  }
  //Checking if subject exist
  $check_subject = mysqli_query($con, "SELECT class_id, subject_id FROM class_subject_tbl WHERE subject_id = '$subject_id' AND class_id = '$class_id' LIMIT 1");
  $chek_num = mysqli_num_rows($check_subject);
  if($chek_num > 0)
  {
    $error = true;
    $warningMsg = "Subject added already";
  }
  if(!$error)
  {
    $sql = mysqli_query($con, "INSERT INTO class_subject_tbl(class_id, staff_id, subject_id) VALUES('$class_id', '$instructor_id', '$subject_id')");
    if(!$sql)
    {
      $warningMsg = die("Error occured ".mysqli_error($con));
    }
    else
    {
      $successMsg = "Subject Registered";
    } 
  }  
}

?>

    
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div  class="align-items-center justify-content-center ">
    <h2 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Class Subject Registration/View Page</h2>
    <h6 class="text-danger">(*) All fields are required</h6>
  </div><br>
  <center>
    <?php
    if(isset($successMsg)){
    ?>	
      <label class="text-success" ><i class="fas fa-fw fa-check-square"></i> <?php echo $successMsg; ?></label>
    <?php
    }
    elseif(isset($warningMsg)){
    ?>	
      <label class="text-danger" ><i class="fas fa-fw fa-user-times"></i><?php echo $warningMsg; ?></label>
    <?php
    }
    ?>
</center>

    <!-- Class Subject Content Row -->
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-row ">
          <div class="col-md-3">
            <div class="form-group">
            <!-- Fetching data from class/Subject/Staff table -->
            <?php
            $sql1 = mysqli_query($con, "SELECT * FROM class_tbl");
            ?>
              <select name="class_id" class="form-control" required>
              <option value="">Select Class...</option>
            <?php
              if(!$sql1)
              {
                $_SESSION['warningMsg'] = die(mysqli_error($con));
              }
              else
              {
                if(mysqli_num_rows($sql1) > 0 )
                {
                  while($row1 = mysqli_fetch_array($sql1))
                  {
              ?>
                <option value="<?php echo $row1['class_id']; ?>"> <?php echo $row1['class_name']; ?></option>
              <?php     
                  }  
                }
                else{
                  $_SESSION['warningMsg'] = "Class Record Empty";
                }
              }
            ?>
            </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <!-- Fetching data from subject table -->
              <?php
                $sql2 = mysqli_query($con, "SELECT * FROM subject_tbl");
              ?>
              <select name="subject_id" class="form-control" required>
                <option value=""> Subject...</option>
                <?php
                if(!$sql2)
                {
                  $warningMsg = die(mysqli_error($con));
                }
                else
                {
                  if(mysqli_num_rows($sql2) > 0 )
                  {
                    while($row2 = mysqli_fetch_array($sql2))
                    {
                ?>
                <option value="<?php echo $row2['subject_id']; ?>"> <?php echo $row2['subject_name']; ?></option>
                <?php     
                    }  
                  }
                  else{
                    $warningMsg = "Empty record";
                  }
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <!-- Fetching data from staff table -->
            <?php
              $sql_staff = mysqli_query($con, "SELECT * FROM staff_tbl");
            ?>
              <select name="instructor_id" class="form-control" required>
                <option value=""> Instructor...</option>
            <?php
            if(!$sql_staff)
            {
              echo die(mysqli_error($con));
            }
            else
            {
              if(mysqli_num_rows($sql_staff) > 0 )
              {
                while($row3 = mysqli_fetch_array($sql_staff))
                {
            ?>   
                <option value="<?php echo $row3['staff_id']; ?>"> <?php echo $row3['fname']." ". $row3['sname']." ". $row3['oname']; ?></option>
            <?php     
                }  
              }
              else{
                $warningMsg = "Empty record";
              }
            }
            ?>  
              </select>              
              </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <button name="submit_btn" class="btn btn-primary"> Submit </button>
            </div>
          </div>
        </div>
        <br />
  </form>
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-row">
      <div class="col-md-12">
        <div class="form-group" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px; background-color: grey; color: white;">
          <label > Select a class from the select option below to view its subjects</label>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
            <!-- Fetching data from class/Subject/Staff table -->
            <?php
            $sql1 = mysqli_query($con, "SELECT * FROM class_tbl");
            ?>
              <select name="class_select" class="form-control" required>
              <option value=""> Select class...</option>
            <?php
              if(!$sql1)
              {
                $warningMsg = die(mysqli_error($con));
              }
              else
              {
                if(mysqli_num_rows($sql1) > 0 )
                {
                  while($row1 = mysqli_fetch_array($sql1))
                  {
              ?>
                <option value="<?php echo $row1['class_id']; ?>"> <?php echo $row1['class_name']; ?></option>
              <?php     
                  }  
                }
                else{
                  $warningMsg = "Empty record";
                }
              }
            ?>
          </select>        
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <button name="view_btn" class="btn btn-outline-primary"> View </button>
        </div>
      </div>
    </div>
  </form>
 <?php 
    if(isset($_POST['view_btn']))
    {
      $class_select = mysqli_real_escape_string($con, $_POST['class_select']);
      $sql_run = mysqli_query($con, "SELECT * FROM class_subject_tbl WHERE class_id = '$class_select'");
    
 ?>
    <!-- Class Table-->
    <table class="table table-bordered table-responsive">
        <thead>
            <th class="table-primary"> class Name </th>
            <th class="table-primary"> Subjects </th>
            <th class="table-primary"> Instructor </th>
            <th class="table-primary"> Actions </th>
        </thead>
        <?php
            if(!$sql_run)
            {
                die(mysqli_error($con));
            }
            else
            {
                $nums_result = mysqli_num_rows($sql_run);

                if($nums_result > 0)
                {
                    while($row = mysqli_fetch_assoc($sql_run))
                    {
                      $class_id = $row['class_id'];
                      $staff_id = $row['staff_id'];
                      $subject_id = $row['subject_id'];
          ?>
          <tbody>
            <!-- Fetch data from class table-->
            <td> 
              <?php
                $sq1 = mysqli_query($con, "SELECT class_name FROM class_tbl WHERE class_id = '$class_id'");
                while($row = mysqli_fetch_array($sq1))
                echo $row['class_name'];
          ?> 
            </td>
            <!-- End of Fetching data from class table-->
            <td> 
            <!-- Fetch data from Subject table-->
          <?php 
                $sq2 = mysqli_query($con, "SELECT subject_name FROM subject_tbl WHERE subject_id = '$subject_id'");
                while($row = mysqli_fetch_array($sq2))
                echo $row['subject_name'];
          ?> 
            </td>
            <!-- End of Fetching data from Subject table-->
            <!-- Fetch data from Staff table-->
            <td> 
          <?php 
                $sq3 = mysqli_query($con, "SELECT fname, sname, oname FROM staff_tbl WHERE staff_id = '$staff_id'");
                while($row = mysqli_fetch_array($sq3))
                echo $row['fname']." ".$row['sname']." ".$row['oname'];
          ?> 
            </td>
            <!-- End of Fetching data from class table-->
            <td><button class="btn btn-sm btn-primary " alt="edit" name="edit_btn"> <i class="fas fa-fw fa-edit"></i> </button></td>
          </tbody>
          <?php
            }
              }
                else
                {
                    echo "Subject Table is empty";
                }
            }
          }
        ?>
    </table>
  
  </form>
</div><!-- End of Main Content -->
          
<?php
include('includes/footer.php');
include('includes/script.php');
?>