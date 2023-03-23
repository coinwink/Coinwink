<?php

    require_once 'coinwink_auth_sql.php';
    $timestamp = date("Y-m-d H:i:s", strtotime('-1 year'));


    $result = DB::queryFirstField("select count(*) FROM cw_logs_alerts_portfolio");
    echo('cw_logs_alerts_portfolio before:' . $result . "<br>");
    DB::query( "DELETE FROM `cw_logs_alerts_portfolio` WHERE `timestamp` < '".$timestamp."'" );
    $result = DB::queryFirstField("select count(*) FROM cw_logs_alerts_portfolio");
    echo('cw_logs_alerts_portfolio after:' . $result . "<br><br>");


    $result = DB::queryFirstField("select count(*) FROM cw_logs_alerts_email");
    echo('cw_logs_alerts_email before:' . $result . "<br>");
    DB::query( "DELETE FROM `cw_logs_alerts_email` WHERE `timestamp` < '".$timestamp."'" );
    $result = DB::queryFirstField("select count(*) FROM cw_logs_alerts_email");
    echo('cw_logs_alerts_email after:' . $result . "<br><br>");


    $result = DB::queryFirstField("select count(*) FROM cw_logs_alerts_sms");
    echo('cw_logs_alerts_sms before:' . $result . "<br>");
    DB::query( "DELETE FROM `cw_logs_alerts_sms` WHERE `timestamp` < '".$timestamp."'" );
    $result = DB::queryFirstField("select count(*) FROM cw_logs_alerts_sms");
    echo('cw_logs_alerts_sms after:' . $result . "<br><br>");


    $result = DB::queryFirstField("select count(*) FROM cw_logs_alerts_tg");
    echo('cw_logs_alerts_tg before:' . $result . "<br>");
    DB::query( "DELETE FROM `cw_logs_alerts_tg` WHERE `timestamp` < '".$timestamp."'" );
    $result = DB::queryFirstField("select count(*) FROM cw_logs_alerts_tg");
    echo('cw_logs_alerts_tg after:' . $result . "<br><br>");
