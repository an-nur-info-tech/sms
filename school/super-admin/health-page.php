<?php
include('includes/header.php');
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-primary">Health Registration Page</h1>
          </div>
          <!-- Student Content Row -->
          <form method="POST" action="health-page">
            <div class="form-row form-inline">
              <div class="col-md-8">
                <?php
                  $db = new Database();
                  $db->query("SELECT * FROM students_tbl;");
                  $data = $db->resultset();
                ?>
                <input class="form-control"  name="admNo" list="datalistioptions" placeholder="Admission Number" auto_complete="on" required >
				        <datalist id ="datalistioptions">
                    <?php 
                    if(!$db->isConnected()){
                        $warningMsg = die("No connection");
                    }
                    else{
                        if($db->rowCount() > 0){
                            foreach($data as $dat){
                    ?>
                    <option value="<?php echo $dat->admNo; ?>"> <?php echo $dat->admNo; ?> </option>                
                    <?php
                            }
                        }else{
                          ?>
                          <option value=""> No record found</option>                
                        <?php
                        }
                        $db->Disconect();
                    }
                    ?>
                </datalist> &nbsp;&nbsp;
                <button name="view_btn" class="btn btn-outline-primary"> View </button><br><br>
              </div>
            </div>
          </form>

          <form method="post" action="health-page">
          <?php
            if(isset($_POST['view_btn'])){
            $admNo = $_POST['admNo'];
            $db = new Database();
            $db->query("SELECT * FROM students_tbl WHERE admNo =:admNo;");
            $db->bind(':admNo', $admNo);
            $data = $db->resultset();

          ?>             
            <table class="table table-bordered table-hover" >
                <thead>
                    <th class="table-primary">Passsport</th>
                    <th class="table-primary">Admission No.</th>
                    <th class="table-primary">Full name</th>
                    <th class="table-primary">Class</th>
                    <th class="table-primary">Gender</th>
                    <th class="table-primary">D.O.B</th>
                    <th class="table-primary">Religion</th>                    
                    <th class="table-primary">Action</th>
                </thead> 
                <tbody>
                <?php
                  if(!$db->isConnected()){
                    $warningMsg = die("Error in connection");
                  }
                  else{
                    if($db->rowCount() > 0 ){
                      foreach($data as $dat){
                ?>
                  <tr>
                    <td> <img src="<?php echo $dat->passport; ?>" height="50" width="50"> </td>
                    <td> <?php echo $dat->admNo;?> </td>
                    <td> <?php echo $dat->sname." ".$dat->lname." ".$dat->oname; ?> </td>
                    <td> <?php echo $dat->class_name; ?> </td>
                    <td> <?php echo $dat->gender; ?> </td>
                    <td> <?php echo $dat->dob; ?> </td>
                    <td> <?php echo $dat->religion; ?> </td>
                    <td><button class="btn btn-outline-primary btn-sm"><i class="fas fa-fw fa-edit"></i> </button></td>
                  </tr>
                <?php         
                      }
                    }else{
                      ?>
                      <td> No record found for this class</td>
                    <?php
                    }

                  } 
                  $db->Disconect();
                }
                ?>
                </tbody>
            </table>
            
           <div class="col-md-8">
           <center>
              <?php
              if(isset($warningMsg)){
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
        </div>
        <!-- /.container-fluid --> 



	  
<?php
include('includes/footer.php');
include('includes/script.php');
?>