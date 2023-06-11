<?php
include('includes/header.php');

if (isset($_POST['update_btn'])) {
    $db = new Database();

    $session_id =  $_POST['session_id'];
    $term_id = $_POST['term_id'];
    $admNo = $_POST['admNo'];
    $ca = $_POST['ca'];
    $exam = $_POST['exam'];
    $total = $_POST['total'];
    $subject_id = $_POST['subject_id'];
    $grade = $_POST['grade'];
    $remark = $_POST['remark'];

    $db->query(
        "UPDATE result_tbl 
        SET subject_id = :subject_id, ca = :ca, exam = :exam, total = :total, grade = :grade, remark = :remark 
        WHERE admNo = :admNo 
        AND session_id = :session_id 
        AND term_id = :term_id;"
    );
    $db->bind(':subject_id', $subject_id);
    $db->bind(':ca', $ca);
    $db->bind(':exam', $exam);
    $db->bind(':total', $total);
    $db->bind(':grade', $grade);
    $db->bind(':remark', $remark);
    $db->bind(':admNo', $admNo);
    $db->bind(':session_id', $session_id);
    $db->bind(':term_id', $term_id);

    if ($db->execute()) {
        if ($db->rowCount() > 0) {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Success";
            $_SESSION['sessionMsg'] = "Result updated successfully";
            $_SESSION['sessionIcon'] = "success";
            $_SESSION['location'] = "result-update";
        } else {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Result update failed";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "result-update";
        }
    } else {
        die($db->getError());
    }
    $db->Disconect();
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h2 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Result Update Page </h2>
        <h6>Type in the student registration number to update his/her class to update result </h6>
    </div><br>

    <!-- GETTING SINGLE STUDENT TO INPUT RESULT -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" name="admNo" class="form-control" list="admNo" placeholder="Adm Number" autocomplete="off" required>
                    <datalist id="admNo">
                        <option value=""> </option>
                        <!-- Fetching data from class table -->
                        <?php
                        $db = new Database();

                        $db->query("SELECT * FROM students_tbl;");
                        if (!$db->execute()) {
                            die($db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
                                $data = $db->resultset();
                                foreach ($data as $row) {
                        ?>
                                    <option value="<?php echo $row->admNo; ?>"> <?php echo $row->admNo; ?> </option>
                                <?php
                                }
                            } else {
                                ?>
                                <option value="">No record found</option>

                        <?php
                            }
                        }
                        ?>
                    </datalist>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select name="subject_id" class="form-control" required>
                        <option value=""> Select subject...</option>
                        <!-- Fetching data from Session table -->
                        <?php
                        $db->query("SELECT * FROM subject_tbl;");
                        if (!$db->execute()) {
                            die($db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
                                $data = $db->resultset();
                                foreach ($data as $row) {
                        ?>
                                    <option value="<?php echo $row->subject_id; ?>"> <?php echo $row->subject_name; ?> </option>
                                <?php
                                }
                            } else {
                                ?>
                                <option value=""> No record </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select name="session_id" class="form-control" required>
                        <option value=""> Select Session...</option>
                        <!-- Fetching data from Session table -->
                        <?php
                        $db->query("SELECT * FROM session_tbl ORDER BY session_id DESC LIMIT 1;");
                        $data = $db->resultset();
                        if (!$db->isConnected()) {
                            die($db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
                                foreach ($data as $row) {
                        ?>
                                    <option value="<?php echo $row->session_id; ?>"> <?php echo $row->session_name; ?> </option>
                                <?php
                                }
                            } else {
                                ?>
                                <option value=""> No record </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select name="term_id" class="form-control" required>
                        <option value=""> Select term...</option>
                        <!-- Fetching data from Session table -->
                        <?php
                        $db->query("SELECT * FROM term_tbl;");
                        $data = $db->resultset();
                        if (!$db->isConnected()) {
                            die($db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
                                foreach ($data as $row) {
                        ?>
                                    <option value="<?php echo $row->term_id; ?>"> <?php echo $row->term_name; ?> </option>
                                <?php
                                }
                            } else {
                                ?>
                                <option value=""> No record </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button name="priview_btn" type="submit" class="btn btn-outline-primary"> Preview </button>
                </div>
            </div>
        </div>
    </form>

    <?php
    if (isset($_POST['priview_btn'])) {
        $admNo = trim($_POST['admNo']);
        $subject_id = $_POST['subject_id'];
        $term_id = $_POST['term_id'];
        $session_id = $_POST['session_id'];

        $db->query("SELECT * FROM result_tbl AS rs 
                    JOIN students_tbl ON students_tbl.admNo = rs.admNo 
                    JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id
                    WHERE rs.admNo = :admNo 
                    AND rs.subject_id = :subject_id 
                    AND rs.session_id = :session_id 
                    AND rs.term_id = :term_id;");
        $db->bind(':admNo', $admNo);
        $db->bind(':subject_id', $subject_id);
        $db->bind(':session_id', $session_id);
        $db->bind(':term_id', $term_id);
        if (!$db->execute()) {
            die($db->getError());
        } else {
            if ($db->rowCount() > 0) {
                $result = $db->single();            ?>
                <!-- ADDING RESULT ON A SINGLE STUDENT -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <th class="table-primary"> Name/Admission No. </th>
                            <th class="table-primary"> Subjects </th>
                            <th class="table-primary"> C.A </th>
                            <th class="table-primary">EXAM </th>
                            <th class="table-primary">Actions </th>
                        </thead>
                        <tbody>
                            <td>
                                <?php echo $result->sname . " " . $result->lname . " " . $result->oname . " [" . $result->admNo . "]"; ?>
                            </td>
                            <td> <input type="text" name="subject" value="<?php echo $result->subject_name; ?>" class="form-control" readonly></td>
                            <td> <input type="number" name="ca" id="ca" value="<?php echo $result->ca; ?>" onkeypress="add()" onkeyup="add()" class="form-control" required></td>
                            <td> <input type="number" name="exam" id="exam" value="<?php echo $result->exam; ?>" onkeypress="add()" onkeyup="add()" class="form-control" required> </td>
                            <input type="hidden" name="total" id="total" placeholder=" Total" class="form-control">
                            <input type="hidden" name="admNo" value="<?php echo $result->admNo; ?>" class="form-control">
                            <input type="hidden" name="session_id" value="<?php echo $session_id; ?>" class="form-control">
                            <input type="hidden" name="term_id" value="<?php echo $term_id; ?>" class="form-control">
                            <input type="hidden" name="subject_id" value="<?php echo $result->subject_id; ?>" class="form-control">
                            <input type="hidden" name="grade" id="grade" placeholder=" grade" class="form-control">
                            <input type="hidden" name="remark" id="remark" placeholder="Remark" class="form-control">
                            <td> <button class="btn btn-sm btn-outline-primary spinner_btn" type="submit" id="submit_btn" name="update_btn"> Submit </button> </td>

                        <?php

                    } else {
                        ?>
                            <tr>
                                <td colspan="5" class="text-center"> <?php echo "There is no record found"; ?></td>
                            </tr>
                <?php
                    }
                }
            }
                ?>
                        </tbody>
                    </table>
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
</div><!-- End of Main Content -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>