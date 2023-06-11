<?php
include('includes/header.php');

if (isset($_POST['submit_btn_single'])) {
    $db = new Database();

    $admNo = trim($_POST['admNo']);
    $class_id = $_POST['class_id'];
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];
    $subject_id = $_POST['subject_id'];
    $ca = trim($_POST['ca']);
    $exam = trim($_POST['exam']);
    $total = trim($_POST['total']);
    $grade = trim($_POST['grade']);
    $remark = trim($_POST['remark']);

    //Checking if result uploaded already or not
    $db->query(
        "SELECT * FROM result_tbl 
        WHERE admNo = :admNo
        AND subject_id = :subject_id 
        AND session_id = :session_id 
        AND term_id = :term_id;
        AND class_id = :class_id;
    "
    );
    $db->bind(':admNo', $admNo);
    $db->bind(':subject_id', $subject_id);
    $db->bind(':session_id', $session_id);
    $db->bind(':term_id', $term_id);
    $db->bind(':class_id', $class_id);

    if (!$db->execute()) {
        die($db->getError());
    } else {
        if ($db->rowCount() > 0) {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Ooops...";
            $_SESSION['sessionMsg'] = "Result exist...";
            $_SESSION['sessionIcon'] = "warning";
            $_SESSION['location'] = "add-result";
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
                die($db->getError());
            } else {
                if ($db->rowCount() > 0) {
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Success";
                    $_SESSION['sessionMsg'] = "Result uploaded";
                    $_SESSION['sessionIcon'] = "success";
                    $_SESSION['location'] = "add-result";
                } else {
                    $_SESSION['errorMsg'] = true;
                    $_SESSION['errorTitle'] = "Error";
                    $_SESSION['sessionMsg'] = "Result upload failed";
                    $_SESSION['sessionIcon'] = "error";
                    $_SESSION['location'] = "add-result";
                }
            }
        }
    }

    $db->Disconect();
}

if (isset($_POST['submit_btn'])) {
    if (isset($_POST['checkB']) && isset($_POST['ca']) && isset($_POST['exam'])) // Checks if checkbox is checked
    {
        $db = new Database();

        $checkB = $_POST['checkB']; // Arrays of checkbox

        $ca = $_POST['ca']; // Arrays of CA
        $exam = $_POST['exam']; // Arrays of Exams
        $class_id = $_POST['class_id'];
        $session_id = $_POST['session_id'];
        $term_id = $_POST['term_id'];
        $subject_id = $_POST['subject_id'];

        /* $count = 0;  testing
        foreach ($_POST['checkB'] as $row)
        {
            echo "$checkB[$count] $ca[$count] $exam[$count] ";
            $count++;
        } */
        foreach ($checkB as $key => $value) {
            $admNo = $value;
            // echo "$admNo $ca[$key] $exam[$key] $subject_id $term_id $class_id $session_id".PHP_EOL;
            if (($ca[$key] < 0) || ($ca[$key] > 40)) {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Ooops...";
                $_SESSION['sessionMsg'] = "CA should <= 40";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "add-result";
            } else if (($exam[$key] < 0) || ($exam[$key] > 60)) {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Ooops...";
                $_SESSION['sessionMsg'] = "Exam should <= 60";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "add-result";
            } else {
                // echo "$admNo $exam[$key]  $ca[$key]";
                $total = (int)$exam[$key] + (int)$ca[$key]; //Adding C.A with Exam as total
                //let average = total/100;
                // var av_reduce = average.toFixed(2);

                if ($total <= 39) {
                    $grade = "F9";
                    $remark = "Fail";
                }
                if (($total >= 40) || ($total >= 44)) {
                    $grade = "E8";
                    $remark = "Pass";
                }
                if (($total >= 45) || ($total >= 49)) {
                    $grade = "D7";
                    $remark = "Pass";
                }
                if (($total >= 50) || ($total >= 59)) {
                    $grade = "C6";
                    $remark = "Credit";
                }
                if (($total >= 60) || ($total >= 64)) {
                    $grade = "C5";
                    $remark = "Credit";
                }
                if (($total >= 65) || ($total >= 69)) {
                    $grade = "C4";
                    $remark = "Credit";
                }
                if (($total >= 70) || ($total >= 74)) {
                    $grade = "B3";
                    $remark = "Good";
                }
                if (($total >= 75) || ($total >= 79)) {
                    $grade = "B2";
                    $remark = "Good";
                }
                if (($total >= 80) || ($total >= 100)) {
                    $grade = "A1";
                    $remark = "Excellent";
                }
                //Checking if result uploaded already or not
                $db->query(
                    "SELECT * FROM result_tbl 
                    WHERE admNo = :admNo
                    AND subject_id = :subject_id 
                    AND session_id = :session_id 
                    AND term_id = :term_id;
                    AND class_id = :class_id;
                    "
                );
                $db->bind(':admNo', $admNo);
                $db->bind(':subject_id', $subject_id);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->bind(':class_id', $class_id);

                if (!$db->execute()) {
                    die($db->getError());
                } else {
                    if ($db->rowCount() > 0) {
                        $_SESSION['errorMsg'] = true;
                        $_SESSION['errorTitle'] = "Ooops...";
                        $_SESSION['sessionMsg'] = "Result exist...";
                        $_SESSION['sessionIcon'] = "warning";
                        $_SESSION['location'] = "add-result";
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
                        $db->bind(':ca', $ca[$key]);
                        $db->bind(':exam', $exam[$key]);
                        $db->bind(':total', $total);
                        $db->bind(':grade', $grade);
                        $db->bind(':remark', $remark);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $_SESSION['errorMsg'] = true;
                            $_SESSION['errorTitle'] = "Success";
                            $_SESSION['sessionMsg'] = "Result uploaded";
                            $_SESSION['sessionIcon'] = "success";
                            $_SESSION['location'] = "add-result";
                        } else {
                            $_SESSION['errorMsg'] = true;
                            $_SESSION['errorTitle'] = "Error";
                            $_SESSION['sessionMsg'] = "Error occured!";
                            $_SESSION['sessionIcon'] = "error";
                            $_SESSION['location'] = "add-result";
                        }
                    }
                }
            }
        }
        $db->Disconect();
    } else {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Warning";
        $_SESSION['sessionMsg'] = "Select student to upload its result!";
        $_SESSION['sessionIcon'] = "warning";
        $_SESSION['location'] = "add-result";
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h2 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Result Upload Page </h2>
        <h6>Type in the student registration number and select his/her class to upload result </h6>
        <p class="text-danger"> Please review before submitting you can only upload once </p>
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
                            die("Error " . $db->getError());
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
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                $admNo = trim($_POST['admNo']);
                $term_id = $_POST['term_id'];
                $session_id = $_POST['session_id'];

                $db->query("SELECT * FROM students_tbl AS cl JOIN class_tbl ON class_tbl.class_id = cl.class_id WHERE cl.admNo = :admNo;");
                $db->bind(':admNo', $admNo);
                if (!$db->execute()) {
                    die($db->getError());
                } else {
                    $nums_result = $db->rowCount();

                    if ($nums_result > 0) {
                        $result = $db->single();
                        $class_name = $result->class_name;
                        $class_id = $result->class_id;
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
                                            <option value=""> No subject found </option>
                                    <?php
                                        }
                                    }

                                    ?>
                                </select>
                                <input name="admNo" type="hidden" value="<?php echo $admNo; ?>">
                                <input name="class_id" type="hidden" value="<?php echo $class_id; ?>">
                                <input name="session_id" type="hidden" value="<?php echo $session_id; ?>">
                                <input name="term_id" type="hidden" value="<?php echo $term_id; ?>">
                            </td>
                            <td> <input type="number" name="ca" id="ca" onkeypress="add()" onkeyup="add()" class="form-control" required></td>
                            <td> <input type="number" name="exam" id="exam" onkeypress="add()" onkeyup="add()" class="form-control" required> </td>
                            <input type="hidden" name="total" id="total" placeholder=" Total" class="form-control">
                            <!-- <input type="hidden" name="average" id="average" placeholder=" Average" class="form-control"> -->
                            <input type="hidden" name="grade" id="grade" placeholder=" grade" class="form-control">
                            <input type="hidden" name="remark" id="remark" placeholder="Remark" class="form-control">
                            <td> <button class="btn btn-sm btn-outline-primary spinner_btn" type="submit" id="submit_btn" name="submit_btn_single"> Submit </button> </td>

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
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="row form-row">
            <div class="col-md-3">
                <div class="form-group">
                    <select name="class_id" id="class_id" onchange="checkSubject()" class="form-control" required>
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
                    <select name="subject_id" id="subject_id" class="form-control" required>
                        <option value=""> Select subject...</option>
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
                        $db->Disconect();
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <button name="preview_btn" type="submit" class="btn btn-outline-primary"> Preview </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Previewing data according to classess Onclick -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <?php
        if (isset($_POST['preview_btn'])) {
            $db = new Database();

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
                                <th class="table-primary"> <input type="checkbox" id="check_All" onchange="checkAllSelect()"> </th>
                                <th class="table-primary">Class</th>
                                <th class="table-primary">Admission no.</th>
                                <th class="table-primary">Names</th>
                                <th class="table-primary">C.A</th>
                                <th class="table-primary">Exam</th>
                            </thead>
                            <tbody>
                                <?php

                                $db->query("SELECT * FROM students_tbl AS st JOIN class_tbl ON class_tbl.class_id = st.class_id WHERE st.class_id = :class_id;");
                                $db->bind(':class_id', $class_id);
                                if ($db->execute()) {
                                    if ($db->rowCount() > 0) {
                                        $count = 1;
                                        $result = $db->resultset();
                                        foreach ($result as $row) {
                                            $admNo = $row->admNo;
                                ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><input type="checkbox" value="<?php echo $admNo; ?>" id="checkB" name="checkB[]" onchange="checkSingleSelect()"></td>
                                                <td><?php echo $row->class_name; ?></td>
                                                <td><?php echo $admNo; ?></td>
                                                <td><?php echo $row->sname . " " . $row->lname . " " . $row->oname; ?></td>
                                                <td> <input type="number" id="caCheck" onkeypress="adds()" name="ca[]" id="ca" class="form-control ca"></td>
                                                <td><input type="number" id="examCheck" onkeypress="adds()" name="exam[]" id="exam" class="form-control exam"> </td>
                                                <input type="hidden" name="total[]" id="total" placeholder=" Total" class="form-control">
                                                <input type="hidden" name="grade[]" id="grade" placeholder=" grade" class="form-control">
                                                <input type="hidden" name="remark[]" id="remark" placeholder="Remark" class="form-control">

                                                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>" class="form-control">
                                                <!-- <input type="hidden" name="admNo[]" value="<?php //echo $admNo; 
                                                                                                ?>" class="form-control"> -->
                                                <input type="hidden" name="session_id" value="<?php echo $session_id; ?>" class="form-control">
                                                <input type="hidden" name="term_id" value="<?php echo $term_id; ?>" class="form-control">
                                                <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>" class="form-control">


                                            </tr>
                                        <?php
                                            $count++;
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <button class="btn m-2 btn-outline-primary btn-sm spinner_btn" disabled onclick="add_spinner()" name="submit_btn" id="submitBtn">Submit </button>
                                            </td>
                                        </tr>
                                    <?php
                                    } else {
                                    ?>
                                        <tr>
                                            <td colspan="4" class="text-center">Students not found</td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    die($db->getError());
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
            $db->Disconect();
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