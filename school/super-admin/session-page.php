<?php
include('includes/header.php');


if(isset($_POST['session_btn']))
{
    $session_name = trim(mysqli_real_escape_string($con, $_POST['session_name']));
    $sql = mysqli_query($con, "INSERT INTO session_tbl(session_name) VALUES('$session_name')");

    if(!$sql)
    {
      $warningMsg = die("Error occured ".mysqli_error($con));
    }
    else
    {
        $successMsg = "Submition Successfully";
    }
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary">Session Registration Page</h1>
  </div>

    <!-- Session Content Row -->
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-row form-inline">
            <div class="form-group col-md-12">
                <label class="control-label" for="session_name"> Session Year: </label> &nbsp;
                <input type="text" name="session_name" class="form-control" placeholder="Enter session year" autocomplete="off" required>
                &nbsp;&nbsp;
                <button name="session_btn" class="btn btn-primary"> Submit </button>                        
            </div>
        </div><br><br>
    </form>
    <center>
        <?php
        if(isset($successMsg)){
        ?>
          <div class="alert alert-success">
            <span class="glyphicon glyphicon-saved"></span>
            <?php echo $successMsg; ?> 
          </div>
        <?php
        }elseif(isset($warningMsg)){
        ?>	
          <div class="alert alert-warning">
            <span class="glyphicon glyphicon-ban-circle"></span>
            <?php echo $warningMsg; ?> 
          </div>
        <?php
        }
        ?>
    </center>
    
    <!-- Class Table-->
    <table class="table table-bordered table-responsive">
        <thead>
            <th class="table-primary"> S/N </th>
            <th class="table-primary"> Sessions </th>
            <th class="table-primary" > Edit </th>
        </thead>
        <?php
          $sql = mysqli_query($con, "SELECT * FROM session_tbl");
            if(!$sql)
            {
                $warningMsg = die(mysqli_error($con));
            }
            else
            {
                $nums_result = mysqli_num_rows($sql);

                if($nums_result > 0)
                {
                  while($row = mysqli_fetch_assoc($sql))
                  {
        ?>
        
          <tbody>
              <td> <?php echo $row['session_id'];  ?>  </td>
              <td> <?php echo $row['session_name']; ?> </td>
              <td>
                <form method="POST" action="">
                <input type="hidden" name="session_id" value="<?php echo $row['session_id'];  ?>">
                <button class="btn btn-outline-primary" name="edit_btn"> Edit </button>
                </form>
              </td>
          </tbody>
        
        <?php
                    }
                }
                else
                {
                    echo "Session Table is empty";
                }
            }
        ?>
    </table>
</div><!-- End of Main Content -->
          
<?php
include('includes/footer.php');
include('includes/script.php');
?>


