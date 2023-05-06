<?php
include('includes/header.php');

if(isset($_POST['class_btn']))
{
    $class = strtoupper(mysqli_real_escape_string($con, $_POST['class_name']));
    $sql = mysqli_query($con, "INSERT INTO class_tbl(class_name) VALUES('$class')");

    if(!$sql)
    {
      die($warningMsg ="Error occured ".mysqli_error($con));
    }
    else
    {
      $successMsg = "Submition Successfully";
    }
}
if(isset($_POST['update_btn']))
{
  $class_id = $_POST['class_id'];
  $class_name = strtoupper(mysqli_real_escape_string($con, $_POST['class_name']));

  $query_run = mysqli_query($con, "UPDATE class_tbl SET class_name='$class_name' WHERE class_id = '$class_id'");
  if(!$query_run)
  {
    $warningMsg = "Update failed ".mysqli_error($con);
  }
  else
  {
    $successMsg = "Record Updated";
  }
}

if(isset($_POST['delete_btn']))
{
  $class_id = $_POST['class_id'];

  $query_run = mysqli_query($con, "DELETE FROM class_tbl WHERE class_id = '$class_id'");
  if(!$query_run)
  {
    $warningMsg = "Deletion failed ".mysqli_error($con);
  }
  else
  {
    $successMsg = "Record Deleted";
  }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div  class="align-items-center justify-content-center ">
    <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px"> Class Registration Page</h3>
  </div><br>

    <!-- CLASS Content Row -->
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-row form-inline">
            <div class="form-group col-md-12">
                <label class="control-label" for="class_name"> Class name: </label> &nbsp;
                <input type="text" name="class_name" class="form-control" placeholder="Enter Class name" autocomplete="off" required>
                &nbsp;&nbsp;
                <button name="class_btn" class="btn btn-primary"> Submit </button>                        
            </div>
        </div><br><br>
    </form>
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
        
    <?php 
      $db = new Database();
    ?>

   <!-- Class Table-->
   <table class="table table-bordered">
        <thead class="table-primary">
            <th> <h5>#</h5> </th>
            <th> <h5>Names</h5>  </th>
            <th> <h5>Class Teacher's </h5> </th>
            <th> <h5>Actions</h5></th>
        </thead>
          <tbody>
        <?php
          
            $db->query("SELECT * FROM class_tbl;");
            $data = $db->resultset();
            if(!$db->isConnected())
            {
                die("Error ".$db->getError());
            }
            else
            {
              if($db->rowCount() > 0){
                $count = 1;
                foreach($data as $row)
                {
            ?>                    
            <tr>
              <td> <?php echo $count;  ?>  </td>
              <td> <?php echo $row->class_name; ?> </td>
              <td>

              <!-- Converting staff id to its name-->
                <?php 
                    if($row->instructor_id == null){echo "Class teacher not assign";}
                    else
                    { 
                      $staff_id = $row->instructor_id;
                      $db->query("SELECT * FROM staff_tbl WHERE staff_id = :staff_id;");
                      $db->bind(':staff_id', $staff_id);
                      $dats = $db->resultset();
                      foreach($dats as $dat){
                        echo $dat->fname." ".$dat->sname." ".$dat->oname;
                      }
                    }
                    $count++;
                ?> 
              </td>
              <td>
                <form method="POST" action="assign-class-teacher.php">
                  <input type="hidden" name="class_id" value="<?php echo $row->class_id;  ?>">
                  <button class="btn btn-outline-primary btn-sm" title="Click to assign teacher" name="assign_btn"> Assign </button>
                  
                  <!--Triger Button to edit  -->
                  <button name="edit_btn" title="Edit record" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target=""><i class="fas fa-fw fa-edit"></i> </button>
                  
                  <!--Triger Button to Delete  -->
                  <button name="delete_btn" title="Delete record" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target=""><i class="fas fa-fw fa-trash"></i> </button>
                </form>
              </td>                      
            </tr>
            <?php
                }
              }else{
            ?>
                  <tr>
                    <td>No record found</td>
                  </tr>  
              <?php
            }
          }
            
        ?>
          </tbody>
    </table>


  </div>
<!-- /.container-fluid -->
     
<?php
include('includes/footer.php');
include('includes/script.php');
?>

