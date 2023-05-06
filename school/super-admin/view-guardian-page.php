<?php
include('includes/header.php');

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div  class="align-items-center justify-content-center ">
            <h3 class="alert-primary" style="font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif; border-radius: 5px; padding: 2px; margin-bottom: 10px;"> Student Guardian View  Page</h3>
          </div><br>

          <!-- Student Content Row -->
          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-row form-inline">
              <div class="col-md-12">
                <?php
                  $db = new Database();
                  $db->query("SELECT * FROM guardian_tbl;");
                  $data = $db->resultset();
                ?>
                <input class="form-control"  name="admNo" list="datalistioptions" placeholder="Admission Number" auto_complete="off" required >
				        <datalist id ="datalistioptions" auto_complete="off">
                    <?php 
                    if(!$db->isConnected()){
                        $warningMsg = die("No connection");
                    }
                    else{
                        if($db->rowCount() > 0){
                            foreach($data as $dat){
                    ?>
                    <option value="<?php echo $dat->student_admNo; ?>"> <?php echo $dat->student_admNo; ?> </option>                
                    <?php
                            }
                        }else{
                          ?>
                          <option value=""> No record found </option>                
                          <?php
                        }
                      $db->Disconect();
                    }
                    ?>
                </datalist> &nbsp;&nbsp;
                <button name="view_btn" class="btn btn-outline-primary"> View </button><br><br>
              </div>
            </div>
          
            <?php
                  if(isset($_POST['view_btn'])){
                    $admNo = $_POST['admNo'];
                    $db = new Database();
                    $db->query("SELECT * FROM guardian_tbl WHERE student_admNo =:admNo;");
                    $db->bind(':admNo', $admNo);
                    $data = $db->resultset();  
                ?>             
            <table class="table table-bordered table-hover">
            <?php
                if(!$db->isConnected()){
                $warningMsg = die("No connection");
                }
                else{
                if($db->rowCount() > 0 ){
                    foreach( $data as $row){
            ?>
                <thead>
                    <tr>
                        <th class="table-primary">Admission No :-></th>
                        <td> <?php echo $row->student_admNo; ?> </td>
                    </tr>
                    <tr>
                        <th class="table-primary">Guardian/Parent name :-></th>
                        <td> <?php echo $row->guardian_name; ?> </td>
                    </tr>
                    <tr>
                        <th class="table-primary">Email address :-></th>
                        <td> <?php echo $row->email_address; ?> </td>
                    </tr>
                    <tr>
                        <th class="table-primary">Office address :-></th>
                        <td> <?php echo $row->office_address; ?> </td>
                    </tr>
                    <tr>
                        <th class="table-primary">Occupation :-></th>
                        <td> <?php echo $row->occupation; ?> </td>
                    </tr>
                    <tr>
                        <th class="table-primary">Phone Contact 1 :-></th>
                        <td> <?php echo $row->guardian_gsm1; ?> </td>
                    </tr>
                    <tr>
                        <th class="table-primary">Phone Contact 2 :-></th>
                        <td> <?php echo $row->guardian_gsm2; ?> </td>
                    </tr>
                    <tr>
                        <th class="table-primary">Mother contact 1 :-></th>
                        <td> <?php echo $row->mother_gsm1; ?> </td>
                    </tr>
                    <tr>
                        <th class="table-primary">Mother contact 2 :-></th>
                        <td> <?php echo $row->mother_gsm2; ?> </td>
                    </tr>
                </thead> 
            <?php         
                    }
                }else{
                  ?>
                  <tr class="text-center">
                      <th>No record found for this student</th>
                  </tr>

                <?php 
                }
              } 
              $db->Disconect();
            }
            ?>
               
            </table>
           <div class="col-md-12">
           <center>
                <?php
                if(isset($successMsg)){
                ?>
                  <div class="alert alert-success">
                    <span class="glyphicon glyphicon-saved"></span>
                    <?php echo $successMsg; ?> 
                  </div>
                <?php
                }elseif(isset($warningMsg)){
                ?>	
                  <div class="alert alert-warning">
                    <span class="glyphicon glyphicon-ban-circle"></span>
                    <?php echo $warningMsg; ?> 
                  </div>
                <?php
                }
                ?>
            </center>
           </div>
          </form>
          

        </div>
        <!-- /.container-fluid -->

 



	  
<?php
include('includes/footer.php');
include('includes/script.php');
?>

