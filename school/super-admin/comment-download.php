<?php
include('includes/header.php');

require '../assets/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 16px; margin-bottom: 10px;"> Spreadsheet File Download Page </h3>
    </div>

    <form action="export" method="post" target="_blank">
        <div class="card mt-5">
            <div class="card-header">
                Class Teacher Comments Template Download
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h5> KEY RATING:</h5>
                    <h6>A -> [Excellent], B -> [Very Good], C -> [Satisfactory], D -> [Poor], E -> [Very Poor]</h6>
                </div>
                <p>Please select class, session, and term to download comment template on your local machine. <span class="text-danger"> Please note that comment should not greater than 50 characters.</span></p>
                <div class="form-row mt-5">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="class_id" id="class_id" class="form-control" required>
                                <option value=""> Select class...</option>
                                <?php
                                $db = new Database();
                                $db->query("SELECT * FROM class_tbl;");
                                if($db->execute())
                                {
                                    if ($db->rowCount() > 0) {
                                        $data = $db->resultset();
                                        foreach ($data as $record) {
                                    ?>
                                        <option value="<?php echo $record->class_id; ?>"> <?php echo $record->class_name; ?> </option>
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
                                <option value=""> Select session...</option>
                                <?php
                                $db->query("SELECT * FROM session_tbl;");
                                if (!$db->execute()) {
                                    die($db->getError());
                                } else {
                                    if ($db->rowCount() > 0) {
                                        $result = $db->resultset();
                                        foreach ($result as $row) {
                                ?>
                                            <option value="<?php echo $row->session_id; ?>"> <?php echo $row->session_name; ?> </option>
                                <?php

                                        }
                                    } else {
                                        ?>
                                        <option> No record found </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" name="term_id" required>
                                <option value=""> Select term...</option>
                                <?php
                                $db->query("SELECT * FROM term_tbl;");
                                if (!$db->execute()) {
                                    die($db->getError());
                                } else {
                                    if ($db->rowCount() > 0) {
                                        $result = $db->resultset();
                                        foreach ($result as $row) {
                                ?>
                                            <option value="<?php echo $row->term_id; ?>"> <?php echo $row->term_name; ?> </option>
                                <?php

                                        }
                                    } else {
                                        ?>
                                        <option> No record found </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" name="commentImportBtn" > Download </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>  
</div>
<?php
include('includes/footer.php');
include('includes/script.php');
?>