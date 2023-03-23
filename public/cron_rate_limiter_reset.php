<?php 

// Connect to Mysql
include_once "coinwink_auth_sql.php";

$sql = "TRUNCATE TABLE cw_rate_limiter";

$truncatetable = $conn->query($sql);

if($truncatetable !== FALSE)
{
   echo("cw_rate_limiter: All rows have been deleted.");
}
else
{
   echo("cw_rate_limiter: No rows have been deleted.");
}


$sql = "TRUNCATE TABLE cw_rate_limiter_alerts";

$truncatetable = $conn->query($sql);

if($truncatetable !== FALSE)
{
   echo("cw_rate_limiter_alerts: All rows have been deleted.");
}
else
{
   echo("cw_rate_limiter_alerts: No rows have been deleted.");
}
