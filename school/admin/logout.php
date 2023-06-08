<?php 
if(isset($_POST['logout_btn']))
{
    session_start();
    
    unset($_SESSION['valid']);
    unset($_SESSION['user-email']); 
    unset($_SESSION['user-type']); 
    unset($_SESSION['login-time']);
    unset($_SESSION['name']); 
    unset($_SESSION['staff_id']);
    session_destroy();
    header('location: ../../index');
	
}
