<?php 
include_once '../../database/Database.php';

if (isset($_POST['subject_id']))
{
    $db = new Database();
    // $subject_id = $_POST['subject_id'];

    $db->query("SELECT * FROM staff_tbl WHERE deleted != 1;");
    if ($db->execute()){
        if ($db->rowCount() > 0){
            $response = $db->resultset();
            echo '<option value = ""> Select instructor... </option>';
            foreach($response as $row)
            {
                echo "<option value = '$row->staff_id'> $row->fname $row->sname $row->oname </option>";
            }
            // echo json_encode($response);
        }
        else 
        {
            echo '<option value = ""> No record found </option>';
        }
    }else{
        die($db->getError());
    }
    $db->Disconect();
}