<?php 
include('includes/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="card mt-5">
        <div class="card-header">
            <h1>Profile Dashboard</h1>
        </div>
        <?php
                $db = new Database();
                $staff_id = $_SESSION['staff_id'];

                $db->query("SELECT * FROM staff_tbl WHERE staff_id = :staff_id;");
                $db->bind(':staff_id', $staff_id);
                if ($db->execute())
                {
                    if ($db->rowCount() > 0)
                    {
                        $row = $db->single();
            ?>
        <div class="card-body">
            <div class="row text-center ">
                <!-- <div class="col-md-4"></div> -->
                <div class="col-md-12">
                    <img src="<?php if(($row->passport == null) || (empty($row->passport))){echo "../uploads/default.png"; }else{ echo $row->passport;} ?>" class="rounded-circle">
                    <span><i class="fas fa-camera fa-lg"></i></span>
                    <p class="mt-3" ><?php echo "$row->fname $row->sname $row->oname"; ?>&nbsp; <i class="fas fa-edit fa-sm" onclick="alert('Hello')"></i></p>
                </div>
                <!-- <div class="col-md-4"></div> -->
            </div>
            <div class="row">
                <table class="table table-bordered mt-5">
                    <tr>
                        <td><span class=""> STAFF ID: </span>  <?php echo "$row->staff_id"; ?></td>
                        <td>USER TYPE: <?php echo "$row->user_type"; ?></td>
                        <td>ROLE: <?php echo "$row->user_role"; ?></td>
                    </tr>
                    <tr>
                        <td>EMAIL:  <?php echo "$row->email"; ?></td>
                        <td>GENDER: <?php echo "$row->gender"; ?></td>
                        <td>DOB: <?php echo "$row->dob"; ?></td>
                    </tr>
                    <tr>
                        <td>RELIGION:  <?php echo "$row->religion"; ?></td>
                        <td>STATE: <?php echo "$row->staff_state"; ?></td>
                        <td>LGA: <?php echo "$row->lga"; ?></td>
                    </tr>
                    <tr>
                        <td>QUALIFICATION:  <?php echo "$row->qualification"; ?></td>
                        <td>LEVEL OF QUALIFICATION: <?php echo "$row->qualification_level"; ?></td>
                        <td>EMPLOYMENT DATE: <?php echo "$row->year_joined"; ?></td>
                    </tr>
                    <tr>
                        <td>ADDRESS:  <?php echo "$row->home_address"; ?></td>
                        <td>MOBILE NO.: <?php echo "$row->gsm1"; ?></td>
                        <td>MOBILE NO.: <?php echo "$row->gsm2"; ?></td>
                    </tr>
                </table>
            </div>
        </div>
            <?php
                    }
                }
                $db->Disconect();
            ?>
        
    </div>

</div>  

<?php 
include('includes/footer.php');
include('includes/script.php');
?>