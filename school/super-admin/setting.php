<?php 
include('includes/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="card mt-5">
        <div class="card-header">
            <h1>Profile Settings</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p>
                        Enable 2FA &nbsp; &nbsp;<input type="checkbox" disabled value="1">
                    </p>
                    <p>
                        Account active &nbsp; &nbsp;<input type="checkbox" value="1" disabled checked>
                    </p>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
            </div>
        </div>        
    </div>

</div>  

<?php 
include('includes/footer.php');
include('includes/script.php');
?>