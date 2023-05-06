<?php
include('includes/header.php');

if(isset($_POST['submit_btn']))
{
    $error = false;

    $session_name = mysqli_real_escape_string($con, $_POST['session_name']);
    $term_name = mysqli_real_escape_string($con, $_POST['term_name']);

    $admNo = mysqli_real_escape_string($con, $_POST['admNo']);
    $attendance = mysqli_real_escape_string($con, $_POST['attendance']);
    $honesty = mysqli_real_escape_string($con, $_POST['honesty']);
    $neatness = mysqli_real_escape_string($con, $_POST['neatness']);
    $punctuality = mysqli_real_escape_string($con, $_POST['punctuality']);
    $relationship = mysqli_real_escape_string($con, $_POST['relationship']);
    $creativity = mysqli_real_escape_string($con, $_POST['creativity']);
    $dexterity = mysqli_real_escape_string($con, $_POST['dexterity']);
    $fluency = mysqli_real_escape_string($con, $_POST['fluency']);
    $handwriting = mysqli_real_escape_string($con, $_POST['handwriting']);
    $laboratory = mysqli_real_escape_string($con, $_POST['laboratory']);
    $teacher_comment = mysqli_real_escape_string($con, $_POST['teacher_comment']);

    if(empty($admNo))
    {
        $error = true;
        $warningMsg = "Registration number is required";
    }
    if(empty($session_name))
    {
        $error= true;
        $warningMsg = "Session is required";
    }
    if(empty($term_name))
    {
        $error= true;
        $warningMsg = "Term is required";
    }
    if(empty($attendance))
    {
        $error= true;
        $warningMsg = "Attendance in class required";
    }
    if(empty($honesty))
    {
        $error= true;
        $warningMsg = "Honesty is required";
    }
    if(empty($neatness))
    {
        $error= true;
        $warningMsg = "Neatness is required";
    } 
    if(empty($punctuality))
    {
        $error= true;
        $warningMsg = "Punctuality is required";
    }
    if(empty($relationship))
    {
        $error= true;
        $warningMsg = "Relationship with other is required";
    }
    if(empty($creativity))
    {
        $error= true;
        $warningMsg = "Creativity is required";
    }
    if(empty($dexterity))
    {
        $error= true;
        $warningMsg = "Dexterity is required";
    }
    if(empty($fluency))
    {
        $error= true;
        $warningMsg = "Fluency is required";
    }
    if(empty($handwriting))
    {
        $error= true;
        $warningMsg = "Handwriting is required";
    }
    if(empty($laboratory))
    {
        $error= true;
        $warningMsg = "Laboratory is required";
    }
    if(empty($teacher_comment))
    {
        $error= true;
        $warningMsg = "Comment is required";
    }
    //CHECKING IF COMMENT HAVE BEEN ENTER ALREADY
    $com_check = mysqli_query($con, "SELECT * FROM comments_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name'");
    if(mysqli_num_rows($com_check) > 0 )
    {
        $error = true;
        $warningMsg = "Comment already added for this student";
    }
    if(!$error)
    {
        $query_run = mysqli_query($con, "INSERT INTO comments_tbl(admNo, session_name, term_name, attendance, honesty, neatness, punctuality, relationship, creativity, dexterity, fluency, handwriting, laboratory, teacher_comment) 
        VALUES('$admNo', '$session_name', '$term_name', '$attendance', '$honesty', '$neatness', '$punctuality', '$relationship', '$creativity', '$dexterity', '$fluency', '$handwriting', '$laboratory', '$teacher_comment')");
        if(!$query_run)
        {
            $warningMsg = "Submittion failed";
        }
        else
        {
            $successMsg = "Submitted";
        }
    }
     
    
    

}

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

           <!-- Page Heading -->
           <div  class="align-items-center justify-content-center ">
            <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px;                           margin-bottom: 10px;"> Class Teacher Comment Page </h3>
            <p>Please type student registration number, select session, term and click view to enter comment </p>
          </div><br>

          <!-- Fetching record from the class subject table with staff id -->
          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" list="admNo" name="admNo" class="form-control" autocomplete="off" placeholder="Admission No..." required >
                        <datalist id="admNo">
                            <?php 
                                $studentlist = mysqli_query($con, "SELECT * FROM students_tbl");
                                $student_num = mysqli_num_rows($studentlist);
                                if($student_num > 0)
                                {
                                    while($student_fetched = mysqli_fetch_assoc($studentlist))
                                    {
                            ?>
                            <option value="<?php echo $student_fetched['admNo']; ?>"> <?php echo $student_fetched['admNo']; ?></option>
                            <?php
                                    }
                                }
                            ?>
                        </datalist>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="session_name" class="form-control" required>
                            <option value=""> Select session...</option>
                            <?php 
                                $session_query = mysqli_query($con, "SELECT * FROM session_tbl");
                                $session_num = mysqli_num_rows($session_query);
                                if($student_num > 0)
                                {
                                    while($session_fetched = mysqli_fetch_assoc($session_query))
                                    {
                                    
                            ?>
                            <option value="<?php echo $session_fetched['session_name']; ?>"> <?php echo $session_fetched['session_name']; ?></option>
                    
                            <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="term_name" class="form-control" required>
                            <option value=""> Select term...</option>
                            <?php 
                                $term_query = mysqli_query($con, "SELECT * FROM term_tbl");
                                $term_num = mysqli_num_rows($term_query);
                                if($term_num > 0)
                                {
                                    while($term_fetched = mysqli_fetch_assoc($term_query))
                                    {
                                    
                            ?>
                            <option value="<?php echo $term_fetched['term_name']; ?>"> <?php echo $term_fetched['term_name']; ?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button name="view_btn" type="submit" class="btn btn-outline-primary"> View </button>
                    </div>
                </div>
            </div>
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
                  if(isset($_POST['view_btn']))
                  {
                    $error = false;
                    $admNo = mysqli_real_escape_string($con, $_POST['admNo']);
                    $session_name = mysqli_real_escape_string($con, $_POST['session_name']);
                    $term_name = mysqli_real_escape_string($con, $_POST['term_name']);  
                    
                    if(empty($admNo))
                    {
                        $error = true;
                        echo "Please select student reg. no.";
                    }
                    if(!$error)
                    {
                ?> 
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <table class="table table-bordered table-responsive" >
                    <tbody>
                        <input type="hidden" name="admNo" value="<?php echo $admNo; ?>"> <input type="hidden" name="session_name" value="<?php echo $session_name; ?>"> <input type="hidden" name="term_name" value="<?php echo $term_name; ?>"> 
                        <tr align="center">
                            <td colspan="6">
                                <p> <b>KEY RATING: <br > A -> [Excellent] B -> [Very Good] C -> [Satisfactory] D -> [Poor] E -> [Very Poor] </b></p>
                            </td>
                        </tr>
                        <tr align="center">
                            <th colspan="6"> 
                            <?php 
                                $get = mysqli_query($con, "SELECT * FROM students_tbl WHERE admNo='$admNo'");
                                if(mysqli_num_rows($get) > 0)
                                {
                                    while($get_fetch = mysqli_fetch_assoc($get))
                                    {
                                        ?><img src="<?php echo $get_fetch['passport']; ?>" height="70" width="70" /><?php 
                                
                            ?> 
                            </th>
                        </tr>
                        <tr>
                            <th colspan="6"> 
                                <?php echo $get_fetch['sname']." ".$get_fetch['lname']." ".$get_fetch['oname'];    
                                    
                                    }
                                } ?> 
                            </th>
                        </tr>
                        <tr class="table-primary">
                            <th colspan="6" >CHARACTER DEVELOPMENT</th>
                        </tr>
                        <tr>
                            <td> 
                                <select class="form-control" name="attendance" required>
                                    <option value=""> Attentiveness...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                            <td> 
                                <select class="form-control" name="honesty" required>
                                    <option value=""> Honesty...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                            <td> 
                                <select class="form-control" name="neatness" required>
                                    <option value=""> Neatness...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                            <td> 
                                <select class="form-control" name="punctuality" required>
                                    <option value=""> Punctuality...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                            <td> 
                                <select class="form-control" name="relationship" required>
                                    <option value=""> Relationship with others...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                        </tr>
                        <tr  class="table-primary">
                            <th colspan="6">PRACTICAL SKILLS</th>
                        </tr>
                        <tr>
                            <td> 
                                <select class="form-control" name="creativity" required>
                                    <option value=""> Creativity...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                            <td> 
                                <select class="form-control" name="dexterity" required>
                                    <option value=""> Dexterity...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                            <td> 
                                <select class="form-control" name="fluency" required>
                                    <option value=""> Fluency...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                            <td> 
                                <select class="form-control" name="handwriting" required>
                                    <option value=""> Handwriting...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                            <td> 
                                <select class="form-control" name="laboratory" required>
                                    <option value=""> Laboratory work...</option>
                                    <option value="A"> A </option>
                                    <option value="B"> B </option>
                                    <option value="C"> C </option>
                                    <option value="D"> D </option>
                                    <option value="E"> E </option>
                                </select> 
                            </td>
                        </tr>
                        <tr class="table-primary">
                            <th colspan="6">COMMENTS  [50 Word Characters]</th>
                        </tr>
                        <tr>
                            <td colspan="6" > <textarea placeholder="Comments" maxlength="50" rows="2" name="teacher_comment" class="form-control"> </textarea></td>
                        </tr>
                        <tr>
                            <td colspan="6" align="center" > <button name="submit_btn" class="btn btn-outline-primary"> Submit </button> </td>
                        </tr>
                    </tbody> 
                    <?php
                    }
                }
                    ?>
                </table>
            </form>
        </div>
        <!-- /.container-fluid -->
 



	  
<?php
include('includes/footer.php');
include('includes/script.php');
?>

