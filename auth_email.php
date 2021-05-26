<?php

// PHPMailer configuration
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// // For WP versions up to 4.6
// require 'lib/PHPMailer/src/Exception.php';
// require 'lib/PHPMailer/src/PHPMailer.php';
// require 'lib/PHPMailer/src/SMTP.php';

// For newer WP versions
require 'wp-includes/PHPMailer/Exception.php';
require 'wp-includes/PHPMailer/PHPMailer.php';
require 'wp-includes/PHPMailer/SMTP.php';


$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Set the hostname of the mail server
$mail->Host = '';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 465;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
// Enable encryption
$mail->SMTPSecure = "ssl";
//Username to use for SMTP authentication
$mail->Username = '';
//Password to use for SMTP authentication
$mail->Password = '';
// From
$mail->setFrom('', '');

// Admin address to catch errors
$adminaddress = "";


?>