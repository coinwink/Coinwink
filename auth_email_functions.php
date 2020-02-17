<?php


// Send mail through SMTP
function wpse8170_phpmailer_init( PHPMailer $phpmailer ) {
    $phpmailer->Host = '';
    $phpmailer->Port = 465; // could be different
    $phpmailer->Username = ''; // if required
    $phpmailer->Password = ''; // if required
    $phpmailer->SMTPAuth = true; // if required
    $phpmailer->SMTPSecure = 'ssl'; // enable if required, 'tls' is another possible value
    $phpmailer->IsSMTP();
}
add_action( 'phpmailer_init', 'wpse8170_phpmailer_init' );


// Automatic wp email config
function website_email() {
    $sender_email= '';
    return $sender_email;
}
function website_name(){
    $site_name = '';
    return $site_name;
}
add_filter('wp_mail_from','website_email');
add_filter('wp_mail_from_name','website_name');


?>