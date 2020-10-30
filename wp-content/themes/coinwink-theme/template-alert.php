<?php /* Template Name: Coinwink - Alert */ get_header(); ?>

<?php 
    $alert_ID = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); 
    $alert = $wpdb->get_results( "SELECT coin_ID, name, symbol, timestamp, time, content FROM cw_logs_alerts_sms WHERE alert_ID = '".$alert_ID."'", ARRAY_A);

    if (sizeof($alert) == 0) {
        $alert = $wpdb->get_results( "SELECT coin_ID, name, symbol, timestamp, time, content FROM cw_logs_alerts_email WHERE alert_ID = '".$alert_ID."'", ARRAY_A);
        
        if (sizeof($alert) == 0) {
            $alert = $wpdb->get_results( "SELECT coin_ID, name, symbol, timestamp, time, content FROM cw_logs_alerts_portfolio WHERE alert_ID = '".$alert_ID."'", ARRAY_A);

            if (sizeof($alert) == 0) {
                $alert_404 = true;
            }
        }
    }
?>

<div style="position:relative;max-width:800px;margin:0 auto;" class="outer-buttons">

    <div style="position:absolute;top:18px;right:15px;width:33px;">
		<a href="<?php echo site_url(); ?>" style="cursor:pointer;">
			<div title="Home" style="padding:3px;margin:0 auto;" class="icon-portfolio-outer">
				<svg id="icon-home" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 588.83 524.89">
					<?php echo file_get_contents(get_stylesheet_directory_uri() . "/img/icon-home.svg?v=001"); ?>
				</svg>
			</div>
		</a>
    </div>
    
</div>

<?php
    if ( is_user_logged_in() ) {
        $user_ID = get_current_user_id();
		$result = $wpdb->get_results( "SELECT theme FROM cw_settings WHERE user_ID = '".$user_ID."'", ARRAY_A);
        $theme = $result[0]["theme"];
    }
?>

<!-- Header - Logo -->
<div style="text-align: center;">
	<div style="height:27px;"></div>
	<div id="logo" style="width:44px;-webkit-filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));">
		<a href="<?php echo site_url(); ?>">
			<?php if ($theme == '' || $theme == 'classic') { ?>
			<img src="https://coinwink.com/img/coinwink-crypto-alerts-logo.png" width="44" alt="Coinwink Crypto Alerts">
			<?php } else if ($theme == 'matrix') { ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.36 61.36"><title>Coinwink Matrix Logo</title><path d="M30.68,0A30.68,30.68,0,1,1,0,30.68,30.69,30.69,0,0,1,30.68,0Z" class="logo-matrix-base" /><path d="M30.68,6.41a24.29,24.29,0,1,1-17.16,7.11A24.27,24.27,0,0,1,30.68,6.41ZM52.76,30.68A22.08,22.08,0,1,0,30.68,52.76,22.09,22.09,0,0,0,52.76,30.68Z"/><path d="M41.05,36.22a10.37,10.37,0,0,1-20.74,0H23a7.73,7.73,0,1,0,15.46,0Z"/><path d="M28.2,23c-.49,2.16-2.85,4.38-4.51,5.41a14.61,14.61,0,0,1-13.83.86C7.62,28-2.49,19.26,3.69,17.61c4.72-1.27,13.78-.41,18.6.69l1,.25a9.28,9.28,0,0,1,4.25,2.06h6.36a9.27,9.27,0,0,1,4.26-2.06l1-.25c4.83-1.11,13.88-2,18.6-.69,6.18,1.65-3.93,10.4-6.17,11.67a14.61,14.61,0,0,1-13.83-.86c-1.63-1-3.93-3.17-4.49-5.3a5,5,0,0,0-2.58-.73h0A5,5,0,0,0,28.2,23Z" style="fill-rule:evenodd"/></svg>
			<?php } ?>
		</a>
	</div>
	<div id="txtlogo"><a href="<?php echo site_url(); ?>">Coinwink</a></div>
</div>


<div class="container" id="account">

    <?php 
        if ($alert_404) {
            ?>

                <header>
                    <h2 class="text-header" style="color:white;">Error</h2>
                </header>

                <div class="content">

                    <div style="font-size:18px;line-height:140%;">
                    
                        <div style="height:21px;"></div>

                        This alert does not exist
                        
                        <div style="height:21px;"></div>

                    </div>

                </div>

            <?php
        }
        else {
    ?>

        <header>
        <h2 class="text-header" style="color:white;"><?php echo $alert[0]['name'] ?> (<?php echo $alert[0]['symbol'] ?>) Alert</h2>
        </header>

        <div class="content">

            <div style="font-size:18px;line-height:140%;">
            
                <div style="height:21px;"></div>

                <?php 
                
                    // var_dump($alert);
                
                    // Get coin price data from functions.php
                    $cmc_data_from_backend = apply_filters( 'cmc_data_backend', '' );
                    $CMCdata = array_values($cmc_data_from_backend);

                    $i = 0;
                    foreach ($CMCdata as $coin) {
                        if ($coin['id'] == $alert[0]['coin_ID']) {
                            $slug = $coin['slug'];
                            break;
                        }
                    }
                ?>
                

                <a href="https://coinmarketcap.com/currencies/<?php echo $slug; ?>/" target="_blank">
                    <img src="<?php echo site_url(); ?>/img/coins/128x128/<?php echo $alert[0]['coin_ID'] ?>.png" width="40">
                </a>

                <div style="height:20px;"></div>

                <span style="font-weight:bold;" id="alert-time">
                </span>

                <div style="height:20px;"></div>

                <?php echo $alert[0]['content']; ?>

                <div style="height:20px;"></div>
                
                <!-- <div style="height:45px;"></div>

                <a href="https://coinwink.com" class="blacklink" style="font-size:15px;color:#444!important;">New alert</a> -->

                <script>
                    var UNIX_timestamp = <?php echo $alert[0]['time']; ?>;
                    function timeConverter(UNIX_timestamp){
                    var a = new Date(UNIX_timestamp * 1000);
                    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    var year = a.getFullYear();
                    var month = months[a.getMonth()];
                    var date = a.getDate();
                    var hour = a.getHours();
                    hour += '';
                    if (hour.length == 1) { hour = '0'+hour }
                    var min = a.getMinutes();
                    min += '';
                    if (min.length == 1) { min = '0'+min }
                    var sec = a.getSeconds();
                    sec += '';
                    if (sec.length == 1) { sec = '0'+sec }
                    var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
                    return time;
                    }
                    document.getElementById('alert-time').innerHTML = timeConverter(UNIX_timestamp);
                </script>

                <div style="height:7px;"></div>

            </div>

        </div>
    
    <?php } ?>

</div>


<div style="height:10px;"></div>

<?php echo do_shortcode('[footer_shortcode]'); ?>


</body>
</html>