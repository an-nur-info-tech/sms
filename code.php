<?php
if(session_status() == PHP_SESSION_NONE)
{
	session_start(); //start session if session not start
}

require_once('school/database/Database.php');


if(isset($_POST['login-btn']))
{
    $_SESSION['errorMsg'] = false;

    $data = array(
      'email' => trim($_POST['user_name']),
      'password' => $_POST['pwd']  
    );

  if(empty($data['email']) && empty($data['password']))
  {
    $_SESSION['errorMsg'] = true;
    //header('Location: index');
    $_SESSION['errorTitle'] = "Bad Request";
    $_SESSION['sessionMsg'] = "Input your email address and password";
    $_SESSION['sessionIcon'] = "warning";
    $_SESSION['location'] = "index";
  }elseif(empty($data['email']))
  {
    $_SESSION['errorMsg'] = true;
    //header('Location: index');
    $_SESSION['errorTitle'] = "Bad request";
    $_SESSION['sessionMsg'] = "Input your email address";
    $_SESSION['sessionIcon'] = "warning";
    $_SESSION['location'] = "index";
  }elseif(empty($data['password']))
  {
    $_SESSION['errorMsg'] = true;
    //header('Location: index');
    $_SESSION['errorTitle'] = "Bad request";
    $_SESSION['sessionMsg'] = "Input your password";
    $_SESSION['sessionIcon'] = "warning";
    $_SESSION['location'] = "index";
  }
  else{//Creating Database object
    $db = new Database(); 
    //Checking database connection  
    if(!$db->isConnected()){
      echo $db->getError().PHP_EOL;
    }
    else{//Fetching users table.
      $db->query("SELECT * FROM staff_tbl WHERE email=:email;");
      $db->bind(':email', $data['email']);
      $record = $db->single();

      if($record){
        if($record->user_type === "Super Admin"){ // Redirect to Super Admin page
          //Verifying password
          if(password_verify($data['password'], $record->pwd)){
            //check if user accout has been restricted or not
            if($record->act_status === 0){
              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Restriction";
              $_SESSION['sessionMsg'] = "Acount has been deactivated";
              $_SESSION['sessionIcon'] = "error";
              $_SESSION['location'] = "index";
            }
            else{//To end session after logout
              $_SESSION['valid'] = true;
              $_SESSION['user-email'] = $record->email;
              $_SESSION['user-type'] = "super-admin";
              $_SESSION['login-time'] = time(); //for auto logout
              $_SESSION['name'] = $record->fname. " ".$record->sname;
              $_SESSION['staff_id'] = $record->staff_id;
              $db->Disconect();

              //$db->getLogger($record->email, "Access Granted"); TODO
              $_SESSION['errorMsg'] = true;
              $_SESSION['errorTitle'] = "Great!";
              $_SESSION['sessionMsg'] = "Access Granted";
              $_SESSION['sessionIcon'] = "success";
              $_SESSION['location'] = "school/super-admin/dashboard";
            }
          }
          else{
            //$db->getLogger($record->email, "Invalid password");
            $_SESSION['errorMsg'] = true;
            //header('Location: index');
            $_SESSION['errorTitle'] = "Bad request";
            $_SESSION['sessionMsg'] = "Invalid password";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "index";
          }
        }
        elseif($record->user_type === "Admin"){ //Redirect Admin page
          //Verifying password
          if(password_verify($data['password'], $record->pwd)){
            //check if user accout has been restricted or not
            if($record->act_status === 0){
              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Restriction";
              $_SESSION['sessionMsg'] = "Acount has been deactivated";
              $_SESSION['sessionIcon'] = "error";
              $_SESSION['location'] = "index";
            }
            else{
              $_SESSION['valid'] = true;
              $_SESSION['user-email'] = $record->email;
              $_SESSION['user-type'] = "admin";
              $_SESSION['login-time'] = time(); //for auto logout
              $_SESSION['name'] = $record->fname. " ".$record->sname;
              $_SESSION['staff_id'] = $record->staff_id;
              $db->Disconect();

              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Great!";
              $_SESSION['sessionMsg'] = "Access Granted";
              $_SESSION['sessionIcon'] = "success";
              $_SESSION['location'] = "school/admin/dashboard";
            }
          }
          else{
            $_SESSION['errorMsg'] = true;
            //header('Location: index');
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Invalid password";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "index";
          }
        }
        elseif($record->user_type === "Secondary"){ //Redirect Secondary page
          //Verifying password
          if(password_verify($data['password'], $record->pwd)){
            //check if user accout has been restricted or not
            if($record->act_status === 0){
              $_SESSION['errorMsg'] = true;
             //header('Location: index');
              $_SESSION['errorTitle'] = "Restriction";
              $_SESSION['sessionMsg'] = "Acount has been deactivated";
              $_SESSION['sessionIcon'] = "error";
              $_SESSION['location'] = "index";
            }
            else{
              $_SESSION['valid'] = true;
              $_SESSION['user-email'] = $record->email;
              $_SESSION['user-type'] = "secondary";
              $_SESSION['login-time'] = time(); //for auto logout
              $_SESSION['name'] = $record->fname. " ".$record->sname;
              $_SESSION['staff_id'] = $record->staff_id;
              $db->Disconect();

              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Great!";
              $_SESSION['sessionMsg'] = "Access Granted";
              $_SESSION['sessionIcon'] = "success";
              $_SESSION['location'] = "school/secondary/dashboard";
            }
          }
          else{
            $_SESSION['errorMsg'] = true;
            //header('Location: index');
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Invalid password";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "index";
          }
        }
        elseif($record->user_type === "Primary"){ //Redirect Primary page
          //Verifying password
          if(password_verify($data['password'], $record->pwd)){
            //check if user accout has been restricted or not
            if($record->act_status === 0){
              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Restriction";
              $_SESSION['sessionMsg'] = "Acount has been deactivated";
              $_SESSION['sessionIcon'] = "error";
              $_SESSION['location'] = "index";
            }
            else{
              $_SESSION['valid'] = true;
              $_SESSION['user-email'] = $record->email;
              $_SESSION['user-type'] = "primary";
              $_SESSION['login-time'] = time(); //for auto logout
              $_SESSION['name'] = $record->fname. " ".$record->sname;
              $_SESSION['staff_id'] = $record->staff_id;
              $db->Disconect();

              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Great!";
              $_SESSION['sessionMsg'] = "Access Granted";
              $_SESSION['sessionIcon'] = "success";
              $_SESSION['location'] = "school/primary/dashboard";
            }
          }
          else{
            $_SESSION['errorMsg'] = true;
            //header('Location: index');
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Invalid password";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "index";
          }
        }
        elseif($record->user_type === "Nursery"){ //Redirect Nursery page
          //Verifying password
          if(password_verify($data['password'], $record->pwd)){
            //check if user accout has been restricted or not
            if($record->act_status === 0){
              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Restriction";
              $_SESSION['sessionMsg'] = "Acount has been deactivated";
              $_SESSION['sessionIcon'] = "error";
              $_SESSION['location'] = "index";
            }
            else{
              $_SESSION['valid'] = true;
              $_SESSION['user-email'] = $record->email;
              $_SESSION['user-type'] = "nursery";
              $_SESSION['login-time'] = time(); //for auto logout
              $_SESSION['name'] = $record->fname. " ".$record->sname;
              $_SESSION['staff_id'] = $record->staff_id;
              $db->Disconect();

              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Great!";
              $_SESSION['sessionMsg'] = "Access Granted";
              $_SESSION['sessionIcon'] = "success";
              $_SESSION['location'] = "school/nursery/dashboard";
            }
          }
          else{
            $_SESSION['errorMsg'] = true;
            //header('Location: index');
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Invalid password";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "index";
          }
        }        
      }
      else{ //Check students table
        //Fetching students record.
        $db->query("SELECT * FROM students_tbl WHERE admNo=:admNo;");
        $db->bind(':admNo', $data['email']);
        $rec = $db->single();

        if($rec){ //Redirect to Students Page
          
          //Verifying password
          if(password_verify($data['password'], $rec->pwd)){
            //check if user account has been restricted or not
            if($rec->act_status === 0){
              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Restriction";
              $_SESSION['sessionMsg'] = "Acount has been deactivated";
              $_SESSION['sessionIcon'] = "error";
              $_SESSION['location'] = "index";
            }
            else{
              $_SESSION['valid'] = true;
              $_SESSION['user-type'] = "student";
              $_SESSION['login-time'] = time(); //for auto logout
              $_SESSION['name'] = $record->lname. " ".$record->sname;
              $_SESSION['adminNo'] = $record->admNo;
              $db->Disconect();

              //$db->getLogger($_POST['email'], "Access granted");
              $_SESSION['errorMsg'] = true;
              //header('Location: index');
              $_SESSION['errorTitle'] = "Great!";
              $_SESSION['sessionMsg'] = "Access Granted";
              $_SESSION['sessionIcon'] = "success";
              $_SESSION['location'] = "school/student/dashboard";
            }
          }
          else{//TODO
            //$db->getLogger($_POST['email'], "Invalid password");
            $_SESSION['errorMsg'] = true;
            //header('Location: index');
            $_SESSION['errorTitle'] = "Error";
            $_SESSION['sessionMsg'] = "Invalid password";
            $_SESSION['sessionIcon'] = "error";
            $_SESSION['location'] = "index";
          }
        }
        else{//TODO
          //$db->getLogger($_POST['email'], "Account not exist");
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Bad request";
          $_SESSION['sessionMsg'] = "Account not exist";
          $_SESSION['sessionIcon'] = "error";
          $_SESSION['location'] = "index";
        }      
      }
    }
  }
}