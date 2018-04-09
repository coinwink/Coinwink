<?php 

header("HTTP/1.1 200 OK");

// Create a replica of homepage and serve it instead of 404 to catch coin symbols in url
get_template_part('template-home'); 

?>