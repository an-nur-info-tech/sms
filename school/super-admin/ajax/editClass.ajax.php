<?php 
include_once '../../database/Database.php';
$db = new Database();

if(isset($_POST["assignClassID"])){    
    $db->query("SELECT * FROM staff_tbl;");
    if ($db->execute()){
        if ($db->rowCount() > 0){
            $response = $db->resultset();
            echo json_encode($response);
        }
        else 
        {
            echo json_encode("No record found");
        }
    }else{
        echo json_encode("Error");
    }
}
if(isset($_POST["editClassID"])){
    $editClassID = $_POST['editClassID'];
    
    $db->query("SELECT * FROM class_tbl WHERE class_id = :editClassID;");
    $db->bind(':editClassID', $editClassID);
    if ($db->execute()){
        if ($db->rowCount() > 0){
            $response = $db->single();
            echo json_encode($response);
        }
        else 
        {
            echo json_encode("No record found");
        }
    }else{
        echo json_encode("Error");
    }
}
$db->Disconect();