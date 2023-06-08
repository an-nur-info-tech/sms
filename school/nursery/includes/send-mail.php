<?php
require_once('../assets/PHPMailer/src/PHPMailer.php');
require_once('../assets/PHPMailer/src/Exception.php');
require_once('../assets/PHPMailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_mail($staff_id, $email)
{
  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = '';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    
    $mail->Username   = '';                     //SMTP username
    $mail->Password   = '';                                //SMTP password 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    //Recipients
    $mail->setFrom('', 'SMS');
    $mail->addAddress($email);     //Add a recipient

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'SMS Account activation';
    $mail_template =
      "
      <h1> School Management System Registration confirmation </h1>
      <h3> Please click the below link to activate your account</h3>
      <p>Please do not reply to this mail address</p>
      Click <a href='https://test.an-nur-info-tech.com/activation-form?staff_id=$staff_id'>Here</a> 
    ";

    $mail->Body = $mail_template;

    $mail->Send();
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Great";
    $_SESSION['sessionMsg'] = "Success";
    $_SESSION['sessionIcon'] = "success";
    $_SESSION['location'] = "staff-reg-page";
    // return true;
  } catch (Exception $e) {
    $_SESSION['errorMsg'] = true;
    $_SESSION['errorTitle'] = "Error";
    $_SESSION['sessionMsg'] = "Mailer Error: {$mail->ErrorInfo}";
    $_SESSION['sessionIcon'] = "error";
    $_SESSION['location'] = "staff-reg-page";
    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}
