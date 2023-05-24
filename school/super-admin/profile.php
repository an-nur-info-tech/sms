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
                    <span onclick="alert('TODO')" ><i class="fas fa-camera fa-lg"></i></span>
                    <p class="mt-3" ><?php echo "$row->fname $row->sname $row->oname"; ?>&nbsp; <i class="fas fa-edit fa-sm" onclick="alert('TODO')"></i></p>
                </div>
                <!-- <div class="col-md-4"></div> -->
            </div>
            <div class="row">
                <table class="table table-bordered table-hover mt-5">
                    <tr>
                        <td><span class=""> Staff ID:  </span>  <?php echo "$row->staff_id"; ?></td>
                        <td>User Type:  <?php echo "$row->user_type"; ?></td>
                        <td>Role: <?php echo "$row->user_role"; ?></td>
                    </tr>
                    <tr>
                        <td>Email:  <?php echo "$row->email"; ?></td>
                        <td>Gender: <?php echo "$row->gender"; ?></td>
                        <td>Dob: <?php echo "$row->dob"; ?></td>
                    </tr>
                    <tr>
                        <td>Religion:  <?php echo "$row->religion"; ?></td>
                        <td>State: <?php echo "$row->staff_state"; ?></td>
                        <td>lga: <?php echo "$row->lga"; ?></td>
                    </tr>
                    <tr>
                        <td>Qualification:  <?php echo "$row->qualification"; ?></td>
                        <td>Level of Qualification: <?php echo "$row->qualification_level"; ?></td>
                        <td>Employment Date: <?php echo "$row->year_joined"; ?></td>
                    </tr>
                    <tr>
                        <td>Address:  <?php echo "$row->home_address"; ?></td>
                        <td>Mobile no.: <?php echo "$row->gsm1"; ?></td>
                        <td>Phone no.: <?php echo "$row->gsm2"; ?></td>
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