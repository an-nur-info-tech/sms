<?php 
include_once '../../database/Database.php';

if (isset($_POST['view_staff_id']))
{
    $db = new Database();
    $staff_id = $_POST['view_staff_id'];

    $db->query("SELECT * FROM staff_tbl WHERE staff_id = :staff_id;");
    $db->bind(':staff_id', $staff_id);
    if ($db->execute()){
        if ($db->rowCount() > 0){
            $row = $db->single();
            echo json_encode($row);
        }else{
            return "No record found";
        }
    }else{
        die($db->getError());
    }
    $db->Disconect();
}