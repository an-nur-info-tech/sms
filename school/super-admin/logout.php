<?php 
if(isset($_POST['logout_btn']))
{
    session_start();
    unset($_SESSION['user_id']);
    unset($_SESSION['fname']);
    unset($_SESSION['sname']);
    session_destroy();
    header('location: ../../index');
	
}

?>