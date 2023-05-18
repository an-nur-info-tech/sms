<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

//Auto logout on idle
$idle_time = 1000;  //Time of idle

// Checks for authorization
if(!$_SESSION['staff_id'] && !$_SESSION['valid'])
{
    header('location: ../../index');
}
elseif(time() - $_SESSION['login-time'] > $idle_time ){ //Auto logout on idle
    header("Location: ../../index");
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Ooops...";
    $_SESSION['sessionMsg'] = "Your session has expired, please login back";
    $_SESSION['sessionIcon'] = "error";

    $_SESSION['valid'] = false;
    unset($_SESSION['user-email']);
    unset($_SESSION['user-type']);
    unset($_SESSION['login-time']);
    unset($_SESSION['staff_id']);
    unset($_SESSION['name']);

    $_SESSION['location'] = "index";
}
else
{
    $_SESSION['login-time'] = time(); 
    
    if($_SESSION['user-type'] !== "super-admin"){
        header("Location: ../".$_SESSION['user-type']. "/dashboard");  
    }
}
?>