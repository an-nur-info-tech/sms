<?php
include('includes/header.php');
//include('includes/fpdf8/fpdf.php');
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h2 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Result View/Download (Secondary Section)</h2>
        <h6 class="text-danger">Select Class and click view to view all the students result by class or type in student admission number for second options</h6>
    </div><br>


    <!-- ***********************************Secondary Results Rows**************** -->
    <?php $db = new Database(); ?>
    <!-- Class Subject Content Row -->
    <form method="POST" action="fpdf" target="_blank">
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group">
                    <select name="class_id" class="form-control" required>
                        <option value=""> Select class...</option>
                        <!-- Fetching data from class table -->
                        <?php
                        $db->query("SELECT * FROM class_tbl WHERE class_name LIKE '%JSS%' OR class_name LIKE '%SS%';");
                        if (!$db->execute()) {
                            die("Error ".$db->getError());
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
                    <select name="session_id" class="form-control" required>
                        <option value=""> Select Session...</option>
                        <!-- Fetching data from Session table -->
                        <?php
                        $db->query("SELECT * FROM session_tbl;");
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
            <div class="col-md-3">
                <div class="form-group">
                    <select name="term_id" class="form-control" required>
                        <option value=""> Select Term...</option>
                        <!-- Fetching data from Term table -->
                        <?php
                        $db->query("SELECT * FROM term_tbl;");
                        if (!$db->execute()) {
                            die("Error ".$db->getError());
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
            <div class="col-md-3">
                <div class="form-group">
                    <button name="view_class_btn" type="submit" class="btn btn-outline-primary"> View </button>
                    <button name="download_class_btn" type="submit" class="btn btn-primary"> Download </button>
                </div>
            </div>
        </div>
    </form>

    <form method="POST" action="singleFpdf" target="_blank">
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
                        $db->query("SELECT * FROM session_tbl;");
                        if (!$db->execute()) {
                            die("Error ".$db->getError());
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
            <div class="col-md-3">
                <div class="form-group">
                    <select name="term_id" class="form-control" required>
                        <option value=""> Select term...</option>
                        <!-- Fetching data from Session table -->
                        <?php
                        $db->query("SELECT * FROM term_tbl;");
                        if (!$db->execute()) {
                            die("Error ".$db->getError());
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
            <div class="col-md-3">
                <div class="form-group">
                    <button name="single_view_btn" type="submit" class="btn btn-outline-primary"> View </button>
                    <button name="download_view_btn" type="submit" class="btn btn-primary"> Download </button>
                </div>
            </div>
        </div>
    </form>

    <!-- *********************************** END Secondary Results Rows**************** -->


    <!-- ***********************************Primary Results Rows**************** -->
    <h2 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Primary Section</h2>
    <!-- Class Subject Content Row -->
    <form method="POST" action="fpdf" target="_blank">
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group">
                    <select name="class_id" class="form-control" required>
                        <option value=""> Select class...</option>
                        <!-- Fetching data from class table -->
                        <?php
                        $db->query("SELECT * FROM class_tbl WHERE class_name LIKE '%JSS%' OR class_name LIKE '%SS%';");
                        $data = $db->resultset();
                        if (!$db->isConnected()) {
                            die("Error " . $db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
                                foreach ($data as $row) {
                        ?>
                                    <option value="<?php echo $row->class_id; ?>"> <?php echo $row->class_name; ?> </option>
                                <?php
                                }
                            } else {
                                ?>
                                <option value=""> No record found </option>
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
                        $db->query("SELECT * FROM session_tbl;");
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
                    <button name="primary_view_class_btn" type="submit" class="btn btn-outline-primary" disabled> View </button>
                    <button name="primary_download_class_btn" type="submit" class="btn btn-primary" disabled> Download </button>
                </div>
            </div>
        </div>
    </form>

    <form method="POST" action="singleFpdf" target="_blank">
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" name="admNo" class="form-control" list="admNo" placeholder="Adm Number" autocomplete="off" />
                    <datalist id="admNo">
                        <option value=""> </option>
                        <!-- Fetching data from class table -->
                        <?php
                        $db->query("SELECT * FROM students_tbl WHERE admNo LIKE '%JSS%' OR admNo LIKE '%SS%'");
                        $data = $db->resultset();
                        if (!$db->isConnected()) {
                            die("Error " . $db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
                                foreach ($data as $row) {
                        ?>
                                    <option value="<?php echo $row->admNo; ?>"> <?php echo $rowadmNo; ?> </option>
                                <?php
                                }
                            } else {
                                ?>
                                <option value=""> No record </option>
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
                        <option value=""> Select session...</option>
                        <!-- Fetching data from class table -->
                        <?php
                        $db->query("SELECT * FROM session_tbl;");
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
                                <option value=""> No record found </option>
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
                        <!-- Fetching data from class table -->
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
                                <option value=""> No record found </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button name="primary_view_class_btn" type="submit" class="btn btn-outline-primary" disabled> View </button>
                    <button name="primary_download_class_btn" type="submit" class="btn btn-primary" disabled> Download </button>
                </div>
            </div>
        </div>
    </form>
    <!-- ***********************************END Primary Results Rows**************** -->

    <!-- ***********************************Nursery Results Rows**************** -->
    <h2 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Nursery Section</h2>
    <!-- Class Subject Content Row -->
    <form method="POST" action="fpdf" target="_blank">
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group">
                    <select name="class_id" class="form-control" required>
                        <option value=""> Select class...</option>
                        <!-- Fetching data from class table -->
                        <?php
                        $db = new Database();
                        $db->query("SELECT * FROM class_tbl WHERE class_name LIKE '%JSS%' OR class_name LIKE '%SS%';");
                        $data = $db->resultset();
                        if (!$db->isConnected()) {
                            die("Error " . $db->getError());
                        } else {
                            if ($db->rowCount() > 0) {
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
                    <select name="session_id" class="form-control" required>
                        <option value=""> Select Session...</option>
                        <!-- Fetching data from Session table -->
                        <?php
                        $db->query("SELECT * FROM session_tbl;");
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
                        <option value=""> Select Term...</option>
                        <!-- Fetching data from Term table -->
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
                    <button name="nursery_view_class_btn" type="submit" class="btn btn-outline-primary" disabled> View </button>
                    <button name="nursery_download_class_btn" type="submit" class="btn btn-primary" disabled> Download </button>
                </div>
            </div>
        </div>
    </form>

    <form method="POST" action="singleFpdf" target="_blank">
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
                        $db->query("SELECT * FROM session_tbl;");
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
                        <option value=""> Select Term...</option>
                        <!-- Fetching data from Term table -->
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
                    <button name="nursery_view_class_btn" type="submit" class="btn btn-outline-primary" disabled> View </button>
                    <button name="nursery_download_class_btn" type="submit" class="btn btn-primary" disabled> Download </button>
                </div>
            </div>
        </div>
    </form>
    <!-- ***********************************END Nursery Results Rows**************** -->

    <?php $db->Disconect(); ?>

</div><!-- End of Main Content -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>