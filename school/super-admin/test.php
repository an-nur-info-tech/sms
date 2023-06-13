<?php
include('includes/header.php');



if (isset($_POST['test_btn'])) {
    // $checkB = $_POST['checkB']; // Arrays of checkbox
    $ca = $_POST['ca']; // Arrays of CA
    // $exam = $_POST['exam']; // Arrays of Exams
    foreach($ca as $key => $value){
        if (!preg_match('/^[a-eA-E]/', $ca[$key])){
            echo "Error";
        }else{
            echo "Success";
        }
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
    

    <!-- Previewing data according to classess Onclick -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <?php
        $count = 4; 
        $data = 
        '
        <div class="row my-3">
            <div class="col-md-3">
                <input type="checkbox" name="checkB[]">
            </div>
            <div class="col-md-3">
                <input type="text"   name="ca[]" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="text" name="exam[]" class="form-control">
            </div>
        </div>
        ';
        for ($i=0; $i < $count; $i++) { 
            echo $data;
        }
        // echo $data;
    ?>
          <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <button type="submit" class ="btn btn-primary" name="test_btn">Test</button>  
            </div>
            <div class="col-md-4"></div>
          </div>

            
                                           
    </form>
</div><!-- End of Main Content -->

<?php
include('includes/footer.php');
include('includes/script.php');
?>