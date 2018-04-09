<?php

// Send mail through SMTP

add_action( 'phpmailer_init', 'wpse8170_phpmailer_init' );
function wpse8170_phpmailer_init( PHPMailer $phpmailer ) {
    $phpmailer->Host = 'MAILSERVER';
    $phpmailer->Port = 465; // could be different
    $phpmailer->Username = 'EMAIL'; // if required
    $phpmailer->Password = 'PASS'; // if required
    $phpmailer->SMTPAuth = true; // if required
    $phpmailer->SMTPSecure = 'ssl'; // enable if required, 'tls' is another possible value

    $phpmailer->IsSMTP();

}


// Automatic wp email config

function website_email() {
    $sender_email= 'EMAIL';
    return $sender_email;
}
function website_name(){
    $site_name = 'NAME';
    return $site_name;
}
add_filter('wp_mail_from','website_email');
add_filter('wp_mail_from_name','website_name');

?>