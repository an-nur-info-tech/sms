<?php
include('includes/header.php');
$db = new Database();

/* if (isset($_POST['comment_btn'])) {
    $admNo = $_POST['student_id'];
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];
    $principal_comment = $_POST['principal_comment'];

    

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
} */
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Principal Comment Page </h3>
        <p>Please input the student application number, select session, term, and click view to add comment </p>
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
        <table class="table table-bordered table-hover">
            <tbody>
                <tr class="table-primary">
                    <th> # </th>
                    <th> Subjects </th>
                    <th> CA </th>
                    <th> Exams </th>
                    <th> Total </th>
                    <th> Grades </th>
                    <th> Remarks </th>
                </tr>
                <?php
                if (isset($_POST['view_btn'])) {
                    $student_id = $_POST['student_id'];
                    $session_id = $_POST['session_id'];
                    $term_id = $_POST['term_id'];

                    $db->query(
                        "SELECT * FROM result_tbl AS rst
                        JOIN subject_tbl ON subject_tbl.subject_id = rst.subject_id
                        WHERE rst.admNo = :student_id AND rst.session_id = :session_id AND rst.term_id = :term_id"
                        );
                    $db->bind(':student_id', $student_id);
                    $db->bind(':session_id', $session_id);
                    $db->bind(':term_id', $term_id);

                    if ($db->execute()) {
                        if ($db->rowCount() > 0) {
                            $count = 1;
                            $result = $db->resultset();
                            foreach ($result as $q_fetched) {
                ?>
                               <!--  <tr>
                                    <td colspan="7" class="text-center"><?php //echo $q_fetched->sname.' '.$q_fetched->lname.' '.$q_fetched->oname.' ( '.$q_fetched->admNo. ') from '.$q_fetched->class_name;  ?> </td>
                                </tr> -->
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $q_fetched->subject_name; ?></td>
                                    <td><?php echo $q_fetched->ca; ?></td>
                                    <td><?php echo $q_fetched->exam; ?></td>
                                    <td><?php echo $q_fetched->total; ?></td>
                                    <td><?php echo $q_fetched->grade; ?></td>
                                    <td><?php echo $q_fetched->remark; ?></td>
                                </tr>
                            <?php
                            $count++;
                            }
                            ?>
                            <tr>
                                <td colspan="7">Comment: (50 chars maximum)
                                    <textarea name="principal_comment" class="form-control" maxlength="50" required> </textarea>
                                    <input type="hidden" class="form-control" name="student_id" value="<?php echo $student_id; ?>">
                                    <input type="hidden" class="form-control" name="session_id" value="<?php echo $session_id; ?>">
                                    <input type="hidden" class="form-control" name="term_id" value="<?php echo $term_id; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-center"><button class="btn btn-outline-primary" type="submit" disabled name="comment_btn"> Submit </button></td>
                            </tr>
                <?php
                        } else 
                        {
                            ?>
                            <tr>
                                <td colspan="6" class="text-center"> Result have not been uploaded for this student [ <?php echo $student_id; ?> ]</td>
                            </tr>
                            <?php
                        }
                    } else {
                        die($db->getError());
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