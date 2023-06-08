<?php
include('includes/header.php');

if (isset($_POST['session_btn'])) {
    $db = new Database();
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
    $db->Disconect();
}

if (isset($_POST['year_btn'])) {
    $db = new Database();

    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];
    $begin_date = $_POST['begin_date'];
    $end_date = $_POST['end_date'];
    $next_term_date = $_POST['next_term_date'];

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
            $db->query(
                "INSERT INTO tbl_year_session(session_id, term_id, begin_date, end_date, next_term_begin) 
                VALUES(:session_id, :term_id, :begin_date, :end_date, :next_term_date);"
            );
            $db->bind(':session_id', $session_id);
            $db->bind(':term_id', $term_id);
            $db->bind(':begin_date', $begin_date);
            $db->bind(':end_date', $end_date);
            $db->bind(':next_term_date', $next_term_date);

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
    $db->Disconect();
}

if (isset($_POST['updateTermBtn']))
{
    $db = new Database();

    $begin_date = $_POST['begin_date'];
    $end_date = $_POST['end_date'];
    $next_term_date = $_POST['next_term_date'];
    $date_edit_id = $_POST['date_edit_id'];

    $db->query("UPDATE tbl_year_session SET begin_date = :begin_date, end_date = :end_date, next_term_begin = :next_term_begin WHERE id = :id;");
    $db->bind(':begin_date', $begin_date);
    $db->bind(':end_date', $end_date);
    $db->bind(':next_term_begin', $next_term_date);
    $db->bind(':id', $date_edit_id);
    $db->execute();
    if ($db->rowCount() > 0)
    {
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Success";
        $_SESSION['sessionMsg'] = "Updated successfully!";
        $_SESSION['sessionIcon'] = "success";
        $_SESSION['location'] = "session-term-page";
    }else{
        $_SESSION['errorMsg'] = true;
        $_SESSION['errorTitle'] = "Error";
        $_SESSION['sessionMsg'] = "Error occured!";
        $_SESSION['sessionIcon'] = "error";
        $_SESSION['location'] = "session-term-page";
    }
    $db->Disconect();
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> 
            Year session
        </h3>
        <p>Please add the session year (e.g 2020/2024) and click submit</p>
    </div>

    <!-- Session Content Row -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <input type="text" name="session_name" class="form-control" placeholder="Enter session year" autocomplete="off" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <button name="session_btn" class="btn btn-primary"> Submit </button>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
    <hr />
    </form>
    <div class="align-items-center justify-content-center ">
        <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> 
            Year term Registration
        </h3>
        <p>Please select the session year, term, begin date, end date, and next term resumption date for the current year and click submit</p>
    </div>
    <!-- Session/Term Content Row -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <select name="session_id" class="form-control" required>
                        <option value=""> Select session...</option>
                        <?php
                        $db = new Database();
                        
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
            <div class="col-md-2">
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
                        $db->Disconect();
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <input type="date" name="begin_date" class="form-control" title="Start date" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <input type="date" name="end_date" class="form-control" title="Ends date" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <input type="date" name="next_term_date" class="form-control" title="Next term resumption" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button name="year_btn" class="btn btn-primary"> Submit </button>
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
                <th class="table-primary"> Term Begin </th>
                <th class="table-primary"> Term Ends </th>
                <th class="table-primary"> Next Term Start Date </th>
                <th class="table-primary"> Actions </th>
            </thead>
            <?php
            $db = new Database();
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
                                <td> <?php echo $row->begin_date;  ?> </td>
                                <td> <?php echo $row->end_date; ?> </td>
                                <td> <?php echo $row->next_term_begin; ?> </td>
                                <td> 
                                    <span class="btn date_edit" 
                                    date_edit = "<?php echo $row->id; ?>"
                                    g_begin_date = "<?php echo $row->begin_date; ?>"
                                    g_end_date = "<?php echo $row->end_date; ?>"
                                    g_next_date = "<?php echo $row->next_term_begin; ?>"
                                    data-toggle = "modal" data-target="#edit_modal">
                                        <i class="fas fa-edit"></i>
                                    </span> 
                                </td>
                            </tr>
                        </tbody>
            <?php
                        $count++;
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" class="fw-bold text-center">Empty record returned</td>
                    </tr>
                    <?php
                }
            }
            $db->Disconect();
            ?>
        </table>
      </div>
    </div>
  </div>

  <!-- Edit Subject Modal-->
  <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Page</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <div class="modal-body">
                <div class="form-group">
                  <label>Begins date:</label>
                  <input type="date" id="begin_date" name="begin_date" value="" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Ends date:</label>
                  <input type="date" id="end_date" name="end_date" value="" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Next term resumption date:</label>
                  <input type="date" id="next_term_date" name="next_term_date" value="" class="form-control" required>
                </div>
                <div class="form-group">
                  <input type="hidden" id="date_edit_id" name="date_edit_id" value="" class="form-control" required>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="updateTermBtn" class="btn btn-sm btn-primary"> Update </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- End of Main Content -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>