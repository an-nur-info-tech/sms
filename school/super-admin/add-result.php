<?php
include('includes/header.php');
$db = new Database();

if (isset($_POST['submit_btn'])) {

    $admNo = $_POST['admNo'];
    $class_id = $_POST['class_id'];
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];
    $subject_id = $_POST['subject_id'];
    $ca = $_POST['ca'];
    $exam = $_POST['exam'];
    $total = $_POST['total'];
    $grade = $_POST['grade'];
    $remark = $_POST['remark'];
    /* 
    if ($ca > 40) {
        $error = true;
        $warningMsg = "CA can not be > 40";
    }
    if ($exam > 60) {
        $error = true;
        $warningMsg = "Exam can not be > 60";
    }
    if ($total > 100) {
        $error = true;
        $warningMsg = "Total can not be > 100";
    }
    if (empty($total)) {
        $error = true;
        $warningMsg = "total is required";
    }
    if (empty($ca)) {
        $error = true;
        $warningMsg = "ca is required";
    }
    if (empty($exam)) {
        $error = true;
        $warningMsg = "Exam is required";
    }
    if (empty($grade)) {
        $error = true;
        $warningMsg = "Grade is required";
    }
    if (empty($remark)) {
        $error = true;
        $warningMsg = "Remark is required";
    }
    if (empty($admNo)) {
        $error = true;
        $warningMsg = "Registration number is required";
    }
    if (empty($class_id)) {
        $error = true;
        $warningMsg = "class id is required";
    }
    if (empty($session_id)) {
        $error = true;
        $warningMsg = "Session name is required";
    }
    if (empty($term_id)) {
        $error = true;
        $warningMsg = "Term name is required";
    }
    if (empty($subject_id)) {
        $error = true;
        $warningMsg = "Subject ID is required";
    }
     */
    echo "Subjid [$subject_id] class id [$class_id] session id [$session_id] and term_id [$term_id]";

    //Checking if result uploaded already or not
    $db->query(
        "SELECT * FROM result_tbl 
        WHERE admNo = :admNo
        AND subject_id = :subject_id 
        AND session_id = :session_id 
        AND term_id = :term_id;
        AND class_id = :class_id
        "
    );
    $db->bind(':admNo', $admNo);
    $db->bind(':subject_id', $subject_id);
    $db->bind(':session_id', $session_id);
    $db->bind(':term_id', $term_id);
    $db->bind(':class_id', $class_id);

    if (!$db->execute()) {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Error occured!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "add-result";
        die($db->getError());
    } else {
        if ($db->rowCount() > 0) {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Ooops...";
            $_SESSION['sessionMsg'] = "Result exist...";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "add-result";
            exit();
        } else {
            $db->query(
                "INSERT INTO 
                result_tbl(class_id, session_id, term_id, subject_id, admNo, ca, exam, total, grade, remark) 
                VALUES(:class_id, :session_id, :term_id, :subject_id, :admNo, :ca, :exam, :total, :grade, :remark);
            "
            );

            $db->bind(':class_id', $class_id);
            $db->bind(':session_id', $session_id);
            $db->bind(':term_id', $term_id);
            $db->bind(':subject_id', $subject_id);
            $db->bind(':admNo', $admNo);
            $db->bind(':ca', $ca);
            $db->bind(':exam', $exam);
            $db->bind(':total', $total);
            $db->bind(':grade', $grade);
            $db->bind(':remark', $remark);

            if (!$db->execute()) {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Error";
                $_SESSION['sessionMsg'] = "Error occured!";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "add-result";
                die($db->getError());
            } else {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Success";
                $_SESSION['sessionMsg'] = "Result uploaded";
                $_SESSION['sessionIcon'] = "success";
                $_SESSION['location'] = "add-result";
            }
        }
    }
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h2 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Result uploading for Session </h2>
        <h6>Type in the student registration number and select he/her class to upload result </h6>
        <p class="text-danger"> Please review before submitting you can only upload once </p>
    </div><br>
    <!-- GETTING SINGLE STUDENT TO INPUT RESULT -->
    <form method="POST" action="add-result">
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" name="admNo" class="form-control" list="admNo" placeholder="Adm Number" autocomplete="off" />
                    <datalist id="admNo">
                        <option value=""> </option>
                        <!-- Fetching data from class table -->
                        <?php
                        $db->query("SELECT * FROM students_tbl WHERE admNo LIKE '%JSS%' OR admNo LIKE '%SS%';");
                        $data = $db->resultset();
                        if (!$db->isConnected()) {
                            die("Error " . $db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
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
                    <select name="session_id" class="form-control" required>
                        <option value=""> Select Session...</option>
                        <!-- Fetching data from Session table -->
                        <?php
                        $db->query("SELECT * FROM session_tbl ORDER BY session_id DESC LIMIT 1;");
                        $data = $db->resultset();
                        if (!$db->isConnected()) {
                            die("Error " . $db->getError());
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
            <div class="col-md-3">
                <div class="form-group">
                    <select name="term_id" class="form-control" required>
                        <option value=""> Select term...</option>
                        <!-- Fetching data from Session table -->
                        <?php
                        $db->query("SELECT * FROM term_tbl;");
                        $data = $db->resultset();
                        if (!$db->isConnected()) {
                            die("Error " . $db->getError());
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
            <div class="col-md-3">
                <div class="form-group">
                    <button name="single_pre_btn" type="submit" class="btn btn-outline-primary"> Preview </button>
                </div>
            </div>
        </div>
    </form>

    <!-- ADDING RESULT ON A SINGLE STUDENT -->
    <form method="POST" action="add-result">
        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead>
                <th class="table-primary"> Name/Admission No. </th>
                <th class="table-primary"> Class </th>
                <th class="table-primary"> Subjects </th>
                <th class="table-primary"> C.A </th>
                <th class="table-primary">EXAM </th>
                <th class="table-primary">Actions </th>
            </thead>
            <?php
            if (isset($_POST['single_pre_btn'])) {
                $admNo = $_POST['admNo'];
                $term_id = $_POST['term_id'];
                $session_id = $_POST['session_id'];

                $db->query("SELECT * FROM students_tbl AS cl WHERE cl.admNo = :admNo;");
                $db->bind(':admNo', $admNo);
                if (!$db->execute()) {
                    die($db->getError());
                } else {
                    $nums_result = $db->rowCount();

                    if ($nums_result > 0) {
                        $result = $db->single();
                        $class_name = $result->class_name;
            ?>
                        <tbody>
                            <td>
                                <?php echo $result->sname . " " . $result->lname . " " . $result->oname . " [" . $result->admNo . "]"; ?>
                            </td>
                            <td> <?php echo $class_name; ?> </td>
                            <td>
                                <select name="subject_id" class="form-control" required>
                                    <option value=""> Select subject...</option>
                                    <!-- Fetching data from class subject table -->
                                    <?php
                                    $db->query("SELECT class_id FROM class_tbl WHERE class_name = :class_name;");
                                    $db->bind(':class_name', $class_name);
                                    $db->execute();
                                    $result = $db->single();
                                    $class_id = $result->class_id;
                                    if (!empty($class_id) || $class_id != null) {
                                        // GETTING RECORD FROM THE CLASS SUBJECT TABLE
                                        $db->query(
                                            "SELECT * FROM class_subject_tbl AS cs
                                        JOIN class_tbl ON class_tbl.class_id = cs.class_id
                                        JOIN subject_tbl ON subject_tbl.subject_id = cs.subject_id
                                        WHERE cs.class_id = :class_id;
                                        "
                                        );
                                        $db->bind(':class_id', $class_id);
                                        if (!$db->execute()) {
                                            die("Error " . $db->getError());
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
                                                <option value=""> No record found </option>
                                        <?php
                                            }
                                        }
                                    } else {
                                        ?>
                                        <option value=""> No subject assigned </option>
                                    <?php
                                    }

                                    ?>
                                </select>
                                <input name="admNo" type="hidden" value="<?php echo $result->admNo; ?>">
                                <input name="class_id" type="hidden" value="<?php echo $class_id; ?>">
                                <input name="session_id" type="hidden" value="<?php echo $session_id; ?>">
                                <input name="term_id" type="hidden" value="<?php echo $term_id; ?>">
                            </td>
                            <td> <input type="number" name="ca" id="ca" onkeypress="add()" onkeyup="add()" class="form-control"></td>
                            <td> <input type="number" name="exam" id="exam" onkeypress="add()" onkeyup="add()" class="form-control"> </td>
                            <input type="hidden" name="total" id="total" placeholder=" Total" class="form-control">
                            <!-- <input type="hidden" name="average" id="average" placeholder=" Average" class="form-control"> -->
                            <input type="hidden" name="grade" id="grade" placeholder=" grade" class="form-control">
                            <input type="hidden" name="remark" id="remark" placeholder="Remark" class="form-control">
                            <td> <button class="btn btn-sm btn-outline-primary" type="submit" id="submit_btn" name="submit_btn"> Submit </button> </td>

                        <?php

                    } else {
                        ?>
                            <tr>
                                <td colspan="6" class="text-center"> <?php echo "There is no such student [ $admNo ]"; ?></td>
                            </tr>
                <?php
                    }
                }
            }
                ?>
                        </tbody>
        </table>
    </form>
    <hr />

    <!-- GETTING ALL CLASS TO INPUT RESULT -->
    <form method="POST" action="add-result">
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group">
                    <select name="class_id" class="form-control" required>
                        <option value=""> Select class...</option>
                        <!-- Fetching data from class table -->
                        <?php
                        $db->query("SELECT * FROM class_tbl;");
                        if (!$db->execute()) {
                            die("Error " . $db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
                                $data = $db->resultset();
                                foreach ($data as $row) {
                        ?>
                                    <option value="<?php echo $row->class_id; ?>"> <?php echo $row->class_name; ?> </option>
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
            <div class="col-md-3">
                <div class="form-group">
                    <select name="subject_id" class="form-control" required>
                        <option value=""> Select subject...</option>
                        <!-- Fetching data from class table -->
                        <?php
                        $db->query("SELECT * FROM subject_tbl;");
                        if (!$db->execute()) {
                            die("Error " . $db->getError());
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
            <div class="col-md-3">
                <div class="form-group">
                    <select name="session_id" class="form-control" required>
                        <option value=""> Select Session...</option>
                        <!-- Fetching data from Session table -->
                        <?php
                        $db->query("SELECT * FROM session_tbl ORDER BY session_id DESC LIMIT 1;");
                        if (!$db->execute()) {
                            die("Error " . $db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
                                $data = $db->resultset();
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
                        <option value=""> Select Term...</option>
                        <!-- Fetching data from Term table -->
                        <?php
                        $db->query("SELECT * FROM term_tbl;");
                        if (!$db->execute()) {
                            die("Error " . $db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
                                $data = $db->resultset();
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
            <div class="col-md-1">
                <div class="form-group">
                    <button name="preview_btn" onclick="get_result()" type="submit" class="btn btn-outline-primary"> Preview </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Previewing data according to classess Onclick -->
    <form method="POST" action="add-result">
        <?php
        if (isset($_POST['preview_btn'])) {
            $class_id = $_POST['class_id'];
            $subject_id = $_POST['subject_id'];
            $session_id = $_POST['session_id'];
            $term_id = $_POST['term_id'];

        ?>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-2">
                    <h6 class="m-0 font-weight-bold text-primary text-uppercase">
                        <?php
                        $db->query("SELECT subject_name FROM subject_tbl WHERE subject_id = :subject_id;");
                        $db->bind(':subject_id', $subject_id);
                        $db->execute();
                        $res = $db->single();
                        echo $res->subject_name;
                        ?>
                        <!-- class subject -->
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-resonsive table-hover" width="100%" cellspacing="0">
                            <thead>
                                <th class="table-primary">#</th>
                                <th class="table-primary"> <input type="checkbox" onchange="checkData()" id="checkbox"> </th>
                                <th class="table-primary">Class</th>
                                <th class="table-primary">Admission no.</th>
                                <th class="table-primary">Names</th>
                                <th class="table-primary">C.A</th>
                                <th class="table-primary">Exam</th>
                            </thead>
                            <tbody>
                                <?php
                                /* if (isset($_POST['preview_btn'])) {
                                    $class_id = $_POST['class_id'];
                                    $subject_id = $_POST['subject_id'];
                                    $session_id = $_POST['session_id'];
                                    $term_id = $_POST['term_id']; */
                                $db->query("SELECT * FROM class_subject_tbl WHERE subject_id = :subject_id;");
                                $db->bind(':subject_id', $subject_id);
                                if ($db->execute()) {
                                    if ($db->rowCount() > 0) {
                                        $result = $db->single();
                                        $class_id = $result->class_id;
                                        //echo $result->staff_id." ".$result->subject_id." ".$result->class_id;
                                        $db->query("SELECT class_name FROM class_tbl WHERE class_id = :class_id;");
                                        $db->bind(':class_id', $class_id);
                                        if ($db->execute()) {
                                            $row = $db->single();
                                            $class_name = $row->class_name;
                                            //echo $class_name;
                                            $db->query("SELECT admNo, sname, lname, oname FROM students_tbl WHERE class_name = :class_name;");
                                            $db->bind(':class_name', $class_name);
                                            if ($db->execute()) {
                                                if ($db->rowCount() > 0) {
                                                    $count = 1;
                                                    $result = $db->resultset();
                                                    foreach ($result as $row) {
                                                        $admNo = $row->admNo;
                                ?>
                                                        <tr>
                                                            <td><?php echo $count; ?></td>
                                                            <td><input type="checkbox" onchange="checkData()" class="checkB" id="checkB"></td>
                                                            <td><?php echo $class_name; ?></td>
                                                            <td><?php echo $admNo; ?></td>
                                                            <td><?php echo $row->sname . " " . $row->lname . " " . $row->oname; ?></td>
                                                            <td> <input type="number" name="ca" id="ca" onkeypress="adds()" onkeyup="add()" class="form-control ca"></td>
                                                            <td> <input type="number" name="exam" id="exam" onkeypress="adds()" onkeyup="add()" class="form-control exam"> </td>
                                                            <input type="hidden" name="total" id="total" placeholder=" Total" class="form-control">
                                                            <input type="hidden" name="grade" id="grade" placeholder=" grade" class="form-control">
                                                            <input type="hidden" name="remark" id="remark" placeholder="Remark" class="form-control">
                                                        </tr>
                                                    <?php
                                                        $count++;
                                                        //echo "$admNo class-> $class_id Term-> $term_id Session-> $session_id Subject-> $subject_id".PHP_EOL;                           
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center">
                                                            <button class="btn m-2 btn-outline-primary btn-sm " name="submit_btn">Submit </button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                } else {
                                                ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No student found</td>
                                                    </tr>
                                        <?php
                                                }
                                            } else {
                                                die($db->getError());
                                                exit();
                                            }
                                        } else {
                                            die($db->getError());
                                            exit();
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No subject related to this class</td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    die($db->getError());
                                    exit();
                                }
                            //}
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
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