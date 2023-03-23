<?php 

// Connect to Mysql
include_once "coinwink_auth_sql.php";

// @TODO PR2: Truncate by date
$sql = "TRUNCATE TABLE cw_rate_limiter_alerts";

$truncatetable = $conn->query($sql);

if($truncatetable !== FALSE)
{
   echo("All rows have been deleted.");
}
else
{
   echo("No rows have been deleted.");
}

?>
