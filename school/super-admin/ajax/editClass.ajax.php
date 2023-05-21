<?php 
include_once '../../database/Database.php';
$db = new Database();

if(isset($_POST["select_section"])){    
    $sec = $_POST["select_section"]; //Section value

    //Display class according to their section
    if ($sec == "NS/")
    {
        // $db->query("SELECT * FROM class_tbl WHERE class_name LIKE '%JSS%' OR class_name LIKE '%SS%';");
        $db->query("SELECT * FROM class_tbl WHERE class_name LIKE '%NURSERY%';");
        if($db->execute())
        {
            if ($db->rowCount() > 0) {
                echo "<option value = ''> Select class... </option>";
                $data = $db->resultset();
                foreach ($data as $record) {
                    $class_name = $record->class_name;
                    echo "<option value = '$class_name'> $class_name </option>";
                }
            } else {
                echo "<option value = ''> No class found </option>";
            }
        }
    }
    else if ($sec == "PS/")
    {
        $db->query("SELECT * FROM class_tbl WHERE class_name LIKE '%PRIMARY%';");
        if($db->execute())
        {
            if ($db->rowCount() > 0) {
                echo "<option value = ''> Select class... </option>";
                $data = $db->resultset();
                foreach ($data as $record) {
                    $class_name = $record->class_name;
                    echo "<option value = '$class_name'> $class_name </option>";
                }
            } else {
                echo "<option value = ''> No class found </option>";
            }
        }
    }
    else if ($sec == "SS/")
    {
        $db->query("SELECT * FROM class_tbl WHERE class_name LIKE '%JSS%' OR class_name LIKE '%SS%';");
        if($db->execute())
        {
            if ($db->rowCount() > 0) {
                echo "<option value = ''> Select class... </option>";
                $data = $db->resultset();
                foreach ($data as $record) {
                    $class_name = $record->class_name;
                    echo "<option value = '$class_name'> $class_name </option>";
                }
            } else {
                echo "<option value = ''> No class found </option>";
            }
        }
    }
    else
    {
        echo "<option value = ''> Select class... </option>";
    }      
}

if(isset($_POST["assignClassID"])){    
    $db->query("SELECT * FROM staff_tbl;");
    if ($db->execute()){
        if ($db->rowCount() > 0){
            $response = $db->resultset();
            echo '<option value = ""> Select name... </option>';
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