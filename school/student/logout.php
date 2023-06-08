<?php 
if(isset($_POST['logout_btn']))
{
    session_start();

    unset($_SESSION['valid']);
    unset($_SESSION['user-type']);
    unset($_SESSION['login-time']); //for auto logout
    unset($_SESSION['name']) ;
    unset($_SESSION['adminNo']);
    session_destroy();
    header('location: ../../index');
	
}
