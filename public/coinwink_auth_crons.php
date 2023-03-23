<?php

$cron_alerts_email_cur = true;
$cron_alerts_email_per = true;
$cron_alerts_sms_cur = true;
$cron_alerts_sms_per = true;
$cron_alerts_portfolio = true;
$cron_data_cmc = true;
$cron_data_cmc_top200 = true;
$cron_data_cmc_top2000 = true;


$killAll = false;

if ($killAll) {
    $cron_alerts_email_cur = false;
    $cron_alerts_email_per = false;
    $cron_alerts_sms_cur = false;
    $cron_alerts_sms_per = false;
    $cron_alerts_portfolio = false;
    $cron_data_cmc = false;
    $cron_data_cmc_top200 = false;
    $cron_data_cmc_top2000 = false;
}