<?php 
require_once('../assets/PHPMailer/src/PHPMailer.php');
require_once('../assets/PHPMailer/src/Exception.php');
require_once('../assets/PHPMailer/src/SMTP.php');
require_once('../assets/PHPMailer/src/POP3.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_mail($staff_id, $email, $fname, $sname, $oname)
{
  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);

  //Server settings
  // $mail->SMTPDebug = 2;                      //Enable verbose debug output
  $mail->isSMTP();                                            //Send using SMTP
  $mail->Host = '';                     //Set the SMTP server to send through
  $mail->SMTPAuth = true;                                   //Enable SMTP authentication

  $mail->Username = '';                     //SMTP username 
  $mail->Password = '';                               //SMTP password 
  $mail->SMTPSecure = '';            //Enable implicit TLS encryption
  $mail->Port = '';                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  //Recipients
  $mail->setFrom('', '');
  $mail->addAddress($email);     //Add a recipient

  //Content
  $mail->isHTML(true);
  $mail->Subject = 'Registration form (Success Schools Sokoto)';
  $mail_template =
    "
    <h1> Form Registration </h1>
    <h3> Please click the below link to complete your registration form</h3>
    <p>Please do not reply this mail</p>
    Click <a href='https://test.an-nur-info-tech.com/registration-form?staff_id=$staff_id+sname=$sname+fname=$fname+oname=$oname'>Here</a>
    <p style='color: red;'>Please note that this mail was sent to you based on request for registration in SUCCESS SCHOOLS SOKOTO 
    if in one way or other you received mail from this email kindly contact the 
    Admin(IBRAHIM BELLO).<br> From the ICT Department Success Schools Sokoto. <br> Thanks for your co-operations</p> 
  ";

  $mail->Body = $mail_template;

  if ($mail->Send()) {
    return true;
  } else {
    return false;
  }
}