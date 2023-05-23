<?php
include('includes/header.php');
$db = new Database();

if (isset($_POST['submit_btn'])) {
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];
    $admNo = $_POST['admNo'];
    
    $attendance = $_POST['attendance'];
    $honesty = $_POST['honesty'];
    $neatness = $_POST['neatness'];
    $punctuality = $_POST['punctuality'];
    $tolerance = $_POST['tolerance'];
    $creativity = $_POST['creativity'];
    $dexterity = $_POST['dexterity'];
    $fluency = $_POST['fluency'];
    $handwriting = $_POST['handwriting'];
    $obedience = $_POST['obedience'];
    $teacher_comment = $_POST['teacher_comment'];

    //CHECKING IF COMMENT HAVE BEEN ENTER ALREADY
    $db->query("SELECT * FROM comments_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
    $db->bind(':admNo', $admNo);
    $db->bind(':session_id', $session_id);
    $db->bind(':term_id', $term_id);

    if($db->execute())
    {
        if ($db->rowCount() > 0) {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Ooops...";
            $_SESSION['sessionMsg'] = "Student has comments!";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "class-teacher-comment-page";
        }
        else 
        {
            $db->query(
                "INSERT INTO 
                comments_tbl(session_id, term_id, admNo, attendance, honesty, neatness, punctuality, tolerance, creativity, dexterity, fluency, handwriting, obedience, teacher_comment) 
                VALUES(:session_id, :term_id, :admNo, :attendance, :honesty, :neatness, :punctuality, :tolerance, :creativity, :dexterity, :fluency, :handwriting, :obedience, :teacher_comment);
                ");

            $db->bind(':session_id', $session_id);
            $db->bind(':term_id', $term_id);
            $db->bind(':admNo', $admNo);
            $db->bind(':attendance', $attendance);
            $db->bind(':honesty', $honesty);
            $db->bind(':neatness', $neatness);
            $db->bind(':punctuality', $punctuality);
            $db->bind(':tolerance', $tolerance);
            $db->bind(':creativity', $creativity);
            $db->bind(':dexterity', $dexterity);
            $db->bind(':fluency', $fluency);
            $db->bind(':handwriting', $handwriting);
            $db->bind(':obedience', $obedience);
            $db->bind(':teacher_comment', $teacher_comment);
            
            if(!$db->execute()) {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Error";
                $_SESSION['sessionMsg'] = "Error occured!";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "class-teacher-comment-page";
                die($db->getError());
              } 
              else 
              {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Success";
                $_SESSION['sessionMsg'] = "Comment successfully!";
                $_SESSION['sessionIcon'] = "success";
                $_SESSION['location'] = "class-teacher-comment-page";
              }
        }
    }    
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px;                           margin-bottom: 10px;"> Class Teacher Comment Page </h3>
        <p>Please type student registration number, select session, term and click view to enter comment </p>
    </div><br>

    <!-- Fetching record from the class subject table with staff id -->
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" name="student_id" list="studentData" class="form-control" required autocomplete="off">
                    <datalist id="studentData">
                        <option value=""> Select Class...</option>
                        <?php
                        $db->query("SELECT * FROM students_tbl;");
                        if ($db->execute()) {
                            $student_num = $db->rowCount();
                            if ($student_num > 0) {
                                $result = $db->resultset();
                                foreach ($result as $student_fetched) {
                        ?>
                                    <option value="<?php echo $student_fetched->admNo; ?>"> <?php echo $student_fetched->admNo; ?></option>
                        <?php
                                }
                            }
                        }
                        ?>
                    </datalist>&nbsp;
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control" name="session_id" required>
                        <option value=""> Select session...</option>
                        <?php
                        $db->query("SELECT * FROM session_tbl;");
                        if ($db->execute()) {
                            $session_num = $db->rowCount();
                            if ($session_num > 0) {
                                $result = $db->resultset();
                                foreach ($result as $session_fetched) {
                        ?>
                                    <option value="<?php echo $session_fetched->session_id; ?>"> <?php echo $session_fetched->session_name; ?></option>
                        <?php
                                }
                            }
                        }
                        ?>
                    </select> &nbsp;
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control" name="term_id" required>
                        <option value=""> Select term...</option>
                        <?php
                        $db->query("SELECT * FROM term_tbl;");
                        if ($db->execute()) {
                            $term_num = $db->rowCount();
                            if ($term_num > 0) {
                                $result = $db->resultset();
                                foreach ($result as $term_fetched) {
                        ?>
                                    <option value="<?php echo $term_fetched->term_id; ?>"> <?php echo $term_fetched->term_name; ?></option>
                        <?php
                                }
                            }
                        }
                        ?>
                    </select> &nbsp;
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button name="view_btn" type="submit" class="btn btn-outline-primary"> View </button><br /><br /><br />
                </div>
            </div>
        </div>
    </form>
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
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr align="center">
                        <td colspan="6">
                            <p> <b>KEY RATING: <br> A -> [Excellent], B -> [Very Good], C -> [Satisfactory], D -> [Poor], E -> [Very Poor] </b></p>
                        </td>
                    </tr>
                    <tr align="center">
                        <th colspan="6">
                        <?php
                        if (isset($_POST['view_btn'])) {

                            $admNo = $_POST['student_id'];
                            $session_id = $_POST['session_id'];
                            $term_id = $_POST['term_id'];

                            $db->query("SELECT * FROM students_tbl AS st JOIN class_tbl ON class_tbl.class_id = st.class_id WHERE admNo = :admNo;");
                            $db->bind(':admNo', $admNo);
                            if($db->execute())
                            {
                                if ($db->rowCount() > 0) {
                                    $result = $db->single();
                                    if($result->passport == null)
                                    {
                                    ?>
                                        <img src="../uploads/student_image.jpg" height="70" width="70" />
                                    <?php
                                    }
                                    else 
                                    {             
                                    ?>
                                        <img src="<?php echo $result->passport; ?>" height="70" width="70" />
                                    <?php   
                                    }                                                                                                         
                        ?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6">
                            <?php echo $result->sname." ".$result->lname." ".$result->oname." [ ".$result->admNo." ] from ".$result->class_name;
                                        //}
                                    }
                                    else 
                                    {
                                        ?>
                                        <tr>
                                            <td> No record found </td>
                                        </tr>
                                        <?php
                                    }
                            }
                         
                            ?>
                        </th>
                    </tr>
                    <tr class="table-primary">
                        <th colspan="6">CHARACTER DEVELOPMENT</th>
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
                            <select class="form-control" name="tolerance" required>
                                <option value=""> Tolerance </option>
                                <option value="A"> A </option>
                                <option value="B"> B </option>
                                <option value="C"> C </option>
                                <option value="D"> D </option>
                                <option value="E"> E </option>
                            </select>
                        </td>
                    </tr>
                    <tr class="table-primary">
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
                            <select class="form-control" name="obedience" required>
                                <option value=""> Obedience...</option>
                                <option value="A"> A </option>
                                <option value="B"> B </option>
                                <option value="C"> C </option>
                                <option value="D"> D </option>
                                <option value="E"> E </option>
                            </select>
                        </td>
                    </tr>
                    <tr class="table-primary">
                        <th colspan="6">COMMENTS [50 Word Characters]</th>
                    </tr>
                    <tr>
                        <td colspan="6"> <textarea placeholder="Comments" maxlength="50" rows="2" name="teacher_comment" class="form-control"> </textarea></td>
                    </tr>
                    <tr>
                        <input type="hidden" name="admNo" value="<?php echo $admNo; ?>"> <input type="hidden" name="session_id" value="<?php echo $session_id; ?>"> 
                        <input type="hidden" name="term_id" value="<?php echo $term_id; ?>">
                        <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                        <td colspan="6" class="text-center"> <button name="submit_btn" class="btn btn-outline-primary"> Submit </button> </td>
                    </tr>
                </tbody>
        <?php
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