<?php
include('includes/header.php');

if (isset($_POST['comment_btn'])) {
    $error = false;

    $admNo = mysqli_real_escape_string($con, $_POST['student_id']);
    $session_name = mysqli_real_escape_string($con, $_POST['session_name']);
    $term_name = mysqli_real_escape_string($con, $_POST['term_name']);
    $principal_comment = mysqli_real_escape_string($con, $_POST['principal_comment']);

    if ($admNo == "") {
        $error = true;
        $warningMsg = "Student Reg. No. require";
    }
    if (empty($session_name)) {
        $error = true;
        $warningMsg = "Session is require";
    }
    if (empty($term_name)) {
        $error = true;
        $warningMsg = "Term is require";
    }
    if ($principal_comment == "") {
        $error = true;
        $warningMsg = "Comment is require";
    }

    //check if class teacher has enter student comment
    $com_check = mysqli_query($con, "SELECT * FROM comments_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name'");
    if (mysqli_num_rows($com_check) > 0) {
        $sql_query = mysqli_query($con, "UPDATE comments_tbl SET principal_comment='$principal_comment' WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name'");
        if (!$sql_query) {
            $warningMsg = "Query failed " . mysqli_error($con);
        } else {
            $successMsg = "Comment successful";
        }
    } else {
        $error = true;
        $warningMsg = "Class teacher have to enter comment first";
    }
}
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Principal Comment Page </h3>
        <p>Please select class and click view to add comment </p>
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
                        $studentlist = mysqli_query($con, "SELECT * FROM students_tbl WHERE admNo LIKE '%ss%'");
                        $student_num = mysqli_num_rows($studentlist);
                        if ($student_num > 0) {
                            while ($student_fetched = mysqli_fetch_assoc($studentlist)) {
                        ?>
                                <option value="<?php echo $student_fetched['admNo']; ?>"> <?php echo $student_fetched['admNo']; ?></option>
                        <?php
                            }
                        }
                        ?>
                    </datalist>&nbsp;
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control" name="session_name" required>
                        <option value=""> Select session...</option>
                        <?php
                        $session_query = mysqli_query($con, "SELECT * FROM session_tbl");
                        $session_num = mysqli_num_rows($session_query);
                        if ($session_num > 0) {
                            while ($session_fetched = mysqli_fetch_assoc($session_query)) {
                        ?>
                                <option value="<?php echo $session_fetched['session_name']; ?>"> <?php echo $session_fetched['session_name']; ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select> &nbsp;
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control" name="term_name" required>
                        <option value=""> Select term...</option>
                        <?php
                        $term_query = mysqli_query($con, "SELECT * FROM term_tbl");
                        $term_num = mysqli_num_rows($term_query);
                        if ($term_num > 0) {
                            while ($term_fetched = mysqli_fetch_assoc($term_query)) {
                        ?>
                                <option value="<?php echo $term_fetched['term_name']; ?>"> <?php echo $term_fetched['term_name']; ?></option>
                        <?php
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
    <center>
        <?php
        if (isset($successMsg)) {
        ?>
            <div class="alert alert-success">
                <span class="glyphicon glyphicon-saved"></span>
                <?php echo $successMsg; ?>
            </div>
        <?php
        } elseif (isset($warningMsg)) {
        ?>
            <div class="alert alert-warning">
                <span class="glyphicon glyphicon-ban-circle"></span>
                <?php echo $warningMsg; ?>
            </div>
        <?php
        }
        ?>
    </center>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table class="table table-bordered table-responsive">
            <tbody>
                <tr class="table-primary">
                    <th> Subjects </th>
                    <th> CA </th>
                    <th> Exams </th>
                    <th> Total </th>
                    <th> Grades </th>
                    <th> Remarks </th>
                </tr>
                <?php
                if (isset($_POST['view_btn'])) {
                    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
                    $session_name = mysqli_real_escape_string($con, $_POST['session_name']);
                    $term_name = mysqli_real_escape_string($con, $_POST['term_name']);
                    $query_result = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$student_id' AND session_name='$session_name' AND term_name='$term_name'");

                    if ($query_result) {
                        if (mysqli_num_rows($query_result) > 0) {
                            while ($q_fetched = mysqli_fetch_assoc($query_result)) {
                ?>
                                <tr>
                                    <td>
                                        <?php
                                        $subject_id = $q_fetched['subject_id'];
                                        $subject_query = mysqli_query($con, "SELECT * FROM subject_tbl WHERE subject_id='$subject_id'");
                                        while ($rowss = mysqli_fetch_assoc($subject_query)) {
                                            echo $rowss['subject_name'];
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $q_fetched['ca']; ?></td>
                                    <td><?php echo $q_fetched['exam']; ?></td>
                                    <td><?php echo $q_fetched['total']; ?></td>
                                    <td><?php echo $q_fetched['grade']; ?></td>
                                    <td><?php echo $q_fetched['remark']; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td colspan="6">Comment:
                                    <textarea name="principal_comment" class="form-control" maxlength="50" required> </textarea>
                                    <input type="hidden" class="form-control" name="student_id" value="<?php echo $student_id; ?>">
                                    <input type="hidden" class="form-control" name="session_name" value="<?php echo $session_name; ?>">
                                    <input type="hidden" class="form-control" name="term_name" value="<?php echo $term_name; ?>">
                                </td>
                            </tr>
                            <tr align="center">
                                <td colspan="6"><button class="btn btn-outline-primary" type="submit" name="comment_btn"> Send </button></td>
                            </tr>
                <?php
                        } else {
                            echo "Result have not been uploaded for this student";
                        }
                    } else {
                        echo mysqli_error($con);
                    }
                }
                ?>

            </tbody>
        </table>
    </form>
</div>
<!-- /.container-fluid -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>