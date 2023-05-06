<?php
include('includes/header.php');

if(isset($_POST['session_btn']))
{
    $session_name = trim(mysqli_real_escape_string($con, $_POST['session_name']));
    //Checking if User record exist
    $session_check = mysqli_query($con, "SELECT * FROM session_tbl WHERE session_name='$session_name'");
    $session_num = mysqli_num_rows($session_check);
    if($session_num > 0 )
    {
        die($warningMsg="This session has been added already ".mysqli_error($con));
    }
    else
    {
        $sql = mysqli_query($con, "INSERT INTO session_tbl(session_name) VALUES('$session_name')");

        if(!$sql)
        {
          die($warningMsg="Error occured ".mysqli_error($con));
        }
        else
        {
            $successMsg = "Session Uploaded";
        }
    }
}

if(isset($_POST['term_btn']))
{
    $error = false;
    $session_id = mysqli_real_escape_string($con, $_POST['session_id']);
    $term_name = trim(strtoupper(mysqli_real_escape_string($con, $_POST['term_name'])));

    if(empty($session_id))
    {
        $error = true;
        $warningMsg = "Session field is required";
    }
    if(empty($term_name))
    {
        $error = true;
        $warningMsg = "Term field is required";
    }
    //Checking if Session and Term exists
    $term_q = mysqli_query($con, "SELECT * FROM term_tbl WHERE session_id='$session_id' AND term_name='$term_name'");
    if(mysqli_num_rows($term_q) > 0)
    {
        $error = true;
        $warningMsg = "Term has been added to this session";
    }

    if(!$error)
    {
        $sql = mysqli_query($con, "INSERT INTO term_tbl(session_id, term_name) VALUES('$session_id', '$term_name')");
        
        if(!$sql)
        {
            $warningMsg = die($warningMsg="Error occured ".mysqli_error($con));
        }
        else
        {
            $successMsg = "Submition Successfully";
        }
    }
}

?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div  class="align-items-center justify-content-center ">
              <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Session Registration</h3>
              <p>Please enter the session and click submit</p>
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
        <div  class="align-items-center justify-content-center ">
            <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Term Registration</h3>
            <p>Please select session and term and to add term for each session </p>
        </div>
            <!-- Session/Term Content Row -->
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-row form-inline">
                    <div class="form-group col-md-12">
                        <label class="control-label" for="session_name"> Session Year: </label> &nbsp;
                        <?php $sql = mysqli_query($con, "SELECT * FROM session_tbl"); ?>
                        <select name="session_id" class="form-control" required>
                            <option value="" > Select session...</option>
                            <?php
                                if(!$sql){
                                    $warningMsg =die(mysqli_error($con));
                                }
                                else{
                                    if(mysqli_num_rows($sql) > 0){
                                        while($row = mysqli_fetch_assoc($sql)){
                            ?>
                            <option value="<?php echo $row['session_id']; ?>" > <?php echo $row['session_name']; ?> </option>
                            <?php
                                            
                                        }
                                    }else{
                                        echo "Table record empty";
                                    }
                                }
                            ?>
                        </select>
                        &nbsp;&nbsp;
                        <label class="control-label" for="term_name"> Term: </label> &nbsp;
                        <select class="form-control" name="term_name" required>
                            <option value=""> Select Term... </option>
                            <option value="First Term" > First Term </option>
                            <option value="Second Term" > Second Term </option>
                            <option value="Third Term" > Third Term </option>
                        </select>
                        &nbsp;&nbsp;
                        <button name="term_btn" class="btn btn-primary"> Submit </button>                        
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
            <?php 
                $sql = mysqli_query($con, "SELECT * FROM term_tbl");
            ?>
            <!-- Class Table-->
            <table class="table table-bordered table-responsive">
                <thead>
                    <th class="table-primary"> ID </th>
                    <th class="table-primary"> Sessions </th>
                    <th class="table-primary" > Terms </th>
                </thead>
                <?php
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
                      <td> <?php echo $row['term_id'];  ?>  </td>
                      <td> 
                        <?php 
                            $session_id = $row['session_id'];
                            $session_q = mysqli_query($con, "SELECT * FROM session_tbl WHERE session_id='$session_id'");
                            $session_num = mysqli_num_rows($session_q);
                            $session_fetch = mysqli_fetch_assoc($session_q);
                            echo $session_fetch['session_name'];
                        ?> 
                        </td>
                      <td> <?php echo $row['term_name']; ?> </td>
                    </tbody>
                
                <?php
                            }
                        }
                        else
                        {
                            echo "Session/Term Table is empty";
                        }
                    }
                ?>
            </table>
        </div><!-- End of Main Content -->
          
<?php
include('includes/footer.php');
include('includes/script.php');
?>

