<?php 
include_once '../../database/Database.php';

if (isset($_POST['subject_id']))
{
    $db = new Database();

    $subject_id = $_POST['subject_id'];

    $db->query("SELECT * FROM subject_tbl WHERE subject_id = :subject_id;");
    $db->bind(':subject_id', $subject_id);
    if ($db->execute()){
        if ($db->rowCount() > 0){
            $response = $db->single();
            
            echo json_encode($response);
        }
        else 
        {
            echo "No record";
        }
    }else{
        die($db->getError());
    }
    $db->Disconect();
}

