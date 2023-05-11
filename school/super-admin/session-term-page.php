<?php
include('includes/header.php');
$db = new Database();

if (isset($_POST['session_btn'])) {
    $session_name = $_POST['session_name'];
    //Checking if User record exist
    $db->query("SELECT * FROM session_tbl WHERE session_name = :session_name;");
    $db->bind(':session_name', $session_name);
    if($db->execute())
    {
        if ($db->rowCount() > 0) 
        {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Ooops...";
            $_SESSION['sessionMsg'] = "Record exist!";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "session-term-page";
        } 
        else 
        {
            $db->query("INSERT INTO session_tbl(session_name) VALUES(:session_name);");
            $db->bind(':session_name', $session_name);
            if (!$db->execute()) {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Error";
                $_SESSION['sessionMsg'] = "Error occured!";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "session-term-page";
                die($db->getError());
            } else {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Success";
                $_SESSION['sessionMsg'] = "Record added!";
                $_SESSION['sessionIcon'] = "success";
                $_SESSION['location'] = "session-term-page";
            }
        }
    }
    else
    {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Error occured!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "session-term-page";
        die($db->getError());
    }
}

if (isset($_POST['year_btn'])) {
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];

    //Checking if Session and Term exists by id
    $db->query("SELECT * FROM tbl_year_session WHERE session_id = :session_id AND term_id = :term_id;");
    $db->bind(':session_id', $session_id);
    $db->bind(':term_id', $term_id);
    if ($db->execute()) {
        if ($db->rowCount() > 0) {
            $_SESSION['errorMsg'] = true;
            $_SESSION['errorTitle'] = "Ooops...";
            $_SESSION['sessionMsg'] = "Record exist!";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "session-term-page";
        } else {
            //Adding record if not exist
            $db->query("INSERT INTO tbl_year_session(session_id, term_id) VALUES(:session_id, :term_id);");
            $db->bind(':session_id', $session_id);
            $db->bind(':term_id', $term_id);
            if (!$db->execute()) {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Error";
                $_SESSION['sessionMsg'] = "Error occured!";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "session-term-page";
                die($db->getError());
            } else {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Success";
                $_SESSION['sessionMsg'] = "Record added!";
                $_SESSION['sessionIcon'] = "success";
                $_SESSION['location'] = "session-term-page";
            }
        }
    } else {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Error occured!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "session-term-page";
        //die($db->getError());
    }
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> 
            Year session
        </h3>
        <p>Please add the year session (e.g 2020/2024) and click submit</p>
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
        </div><hr>
    </form>
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> 
            Year term Registration
        </h3>
        <p>Please select the year session and term for the current year and click submit</p>
    </div>
    <!-- Session/Term Content Row -->
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-row form-inline">
            <div class="form-group col-md-12">
                <label class="control-label" for="session_name"> Session Year: </label> &nbsp;
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
                &nbsp;&nbsp;
                <label class="control-label" for="term_id"> Term: </label> &nbsp;
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
                &nbsp;&nbsp;
                <button name="year_btn" class="btn btn-primary"> Submit </button>
            </div>
        </div><hr>
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
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary text-uppercase">session/term data</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">    
      <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <th class="table-primary"> # </th>
                <th class="table-primary"> Sessions </th>
                <th class="table-primary"> Terms </th>
            </thead>
            <?php
            $db->query(
                "SELECT * FROM tbl_year_session AS y_s
                JOIN session_tbl ON session_tbl.session_id = y_s.session_id
                JOIN term_tbl ON term_tbl.term_id = y_s.term_id
                ORDER BY y_s.id DESC;"
            );
            if (!$db->execute()) 
            {
                $_SESSION['errorMsg'] = true;
                $_SESSION['errorTitle'] = "Error";
                $_SESSION['sessionMsg'] = "Error occured!";
                $_SESSION['sessionIcon'] = "error";
                $_SESSION['location'] = "session-term-page";
                die($db->getError());
            } else {
                $nums_result = $db->rowCount();
                $result = $db->resultset();
                
                if ($nums_result > 0) {
                    $count =  1;
                    foreach ($result as $row) {
            ?>
                        <tbody>
                            <tr>
                                <td> <?php echo $count;  ?> </td>
                                <td> <?php echo $row->session_name; ?> </td>
                                <td> <?php echo $row->term_name; ?> </td>
                            </tr>
                        </tbody>
            <?php
                        $count++;
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="3" class="fw-bold text-center">Empty record returned</td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
      </div>
    </div>
  </div>
</div><!-- End of Main Content -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>