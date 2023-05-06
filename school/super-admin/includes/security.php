<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

/* if($_SESSION['user-type'] !== "super-admin" && $_SESSION['valid'] !== true){
    header("Location: ../".$_SESSION['user-type']. "/dashboard");  
} */
/* if(!$_SESSION['user_id'])
{
    header('location: http://localhost/sms1/index');
} */

//Auto logout after user idleness
$idle_time = 60;  //Time of idle

//Return user back to login page if access not granted 
/* if(!$_SESSION['valid'] && !$_SESSION['staff_id']){
    header('Location: ../../index');
    //Alert
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Access denied!";
    $_SESSION['sessionMsg'] = "Please login to have access";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "index";
}elseif(time() - $_SESSION['login-time'] > $idle_time ){
    $_SESSION['valid'] = false;
    unset($_SESSION['user-email']);
    unset($_SESSION['user-type']);
    unset($_SESSION['login-time']);
    unset($_SESSION['staff_id']);
    unset($_SESSION['name']);
    header('Location: ../../index');

    //Alert
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Session expired!";
    $_SESSION['sessionMsg'] = "Your session has expired, please login back";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "index";
}else{
    $_SESSION['login-time'] = time();   
} */



?>