<?php


// SMTP server config
add_action( 'phpmailer_init', 'set_phpmailer_details' );
function set_phpmailer_details( $phpmailer ) {
    $phpmailer->isSMTP();     
    $phpmailer->Host = 'YOUR_SMTP HOST';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 'SMTP_PORT'; // 25 or 465
    $phpmailer->Username = 'SMTP_USERNAME';
    $phpmailer->Password = 'SMTP_PASSWORD';
    $phpmailer->SMTPSecure = 'ssl'; // ssl or tls
}


// WP email config
function website_email() {
    $sender_email= 'YOUR_EMAIL_ADDRESS';
    return $sender_email;
}
function website_name(){
    $site_name = 'YOUR_WEBSITE_NAME';
    return $site_name;
}
add_filter('wp_mail_from','website_email');
add_filter('wp_mail_from_name','website_name');


?>