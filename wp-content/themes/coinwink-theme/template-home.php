<?php /* Template Name: Coinwink - Home */ get_header(); ?>

<?php $ajax_nonce = wp_create_nonce( "my-special-string" ); ?>


<?php 
    // // PERFORMANCE TEST

    // // Check execution time - Start time
    // $time_start = microtime(true);

    // // Check processing time - START
    // $rustart = getrusage();
?>


<?php
    $url = parse_url($_SERVER['REQUEST_URI']);
    $url_slug = $url['path'];
    $slashes = substr_count($url_slug, '/');
    $url_prefix = '';
    if ($slashes == 3) {
        $url_prefix = '..';
    }
    if ($slashes == 4) {
        $url_prefix = '../..';
    }
?>


<?php

    // Get coin price data from functions.php
    $cmc_data_from_backend = apply_filters( 'cmc_data_backend', '' );
    $CMCdata = array_values($cmc_data_from_backend);
    
    // Get coin price data from the database
    // $result = $wpdb->get_results( "SELECT json FROM cw_data_cmc" , ARRAY_A);
    // $CMCdata = array_values(unserialize($result[0]['json']));

    $result = $wpdb->get_results( "SELECT * FROM cw_data_cur_rates" , ARRAY_A);
?>


<script>
    var rates = {}

    rates['eur'] = "<?php echo($result[0]['EUR']); ?>";
    rates['gbp'] = "<?php echo($result[0]['GBP']); ?>";
    rates['cad'] = "<?php echo($result[0]['CAD']); ?>";
    rates['aud'] = "<?php echo($result[0]['AUD']); ?>";
    rates['brl'] = "<?php echo($result[0]['BRL']); ?>";
    rates['mxn'] = "<?php echo($result[0]['MXN']); ?>";
    rates['jpy'] = "<?php echo($result[0]['JPY']); ?>";
    rates['sgd'] = "<?php echo($result[0]['SGD']); ?>";
</script>

<?php
	if ( is_user_logged_in() ) {
        $user_ID = get_current_user_id();
        
        $result = $wpdb->get_results( "SELECT unique_id, legac, subs, sms, theme FROM cw_settings WHERE user_ID = '".$user_ID."'", ARRAY_A);
        $unique_id = $result[0]["unique_id"];
		$legac = $result[0]["legac"];
		$subs = $result[0]["subs"];
        $sms = $result[0]["sms"];
        $theme = $result[0]["theme"];
        
		$result = $wpdb->get_results( "SELECT date_end, status, date_renewed, months FROM cw_subs WHERE user_ID = '".$user_ID."'", ARRAY_A);
		$status = $result[0]["status"];
		$months = $result[0]["months"];
		$date_renewed = $result[0]["date_renewed"];
		$date_end = $result[0]["date_end"];
		$date_end = new DateTime($date_end);
		$date_end = $date_end->format('Y-m-d');
	}
?>

<?php
    $limit_early = false;
	if ( is_user_logged_in() ) {
        if ($user_ID == 24301 || $user_ID == 19762 || $user_ID == 7929) {
            $limit_early = true;
        }
	}
?>


<script>
    var unique_id = "<?php echo($unique_id); ?>";
    var limitEarly = "<?php echo($limit_early); ?>";
</script>


<!-- MAIN APP CONTAINER -->
<div style="max-width: 800px;margin: 0 auto;">

    <div> <!-- HEADER -->
        
        <!-- Header - LEFT SIDE -->
        <div style="float:left; width: 56px;">
            <div class="fixed grid-header-left">

                <div class="switch-cur-per" style="margin:0 auto;">
                    <input type="radio" class="switch-2-input" name="switch-cur-per" id="curSwitch" checked="checked">
                    <label for="curSwitch" id="switch-label-cur" style="margin-left:-1px;padding-bottom: 27px;" class="switch-2-label switch-label-cur noselect" title="Currency">$</label>

                    <input type="radio" class="switch-2-input" name="switch-cur-per" id="perSwitch">
                    <label style="margin-left:-3px;padding-bottom: 28px;" for="perSwitch" class="switch-2-label switch-label-per noselect" title="Percentage">%</label>
                    
                    <svg id="switch-img-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33.58 88.49">
                        <path class="switch-cur-per" id="switch-cur-init" d="M16.79,6.83h0A12,12,0,0,1,28.72,18.76V29.1A12,12,0,0,1,16.79,41h0A12,12,0,0,1,4.86,29.1V18.76A12,12,0,0,1,16.79,6.83Z" transform="translate(0 -1)"></path>
                        <path class="switch-cur-per" id="switch-cur" d="M16.79,6.83h0A12,12,0,0,1,28.72,18.76V29.1A12,12,0,0,1,16.79,41h0A12,12,0,0,1,4.86,29.1V18.76A12,12,0,0,1,16.79,6.83Z" ></path>
                        <path class="switch-cur-per" id="switch-per" d="M16.79,6.83h0A12,12,0,0,1,28.72,18.76V29.1A12,12,0,0,1,16.79,41h0A12,12,0,0,1,4.86,29.1V18.76A12,12,0,0,1,16.79,6.83Z" ></path>
                        <path class="switch-cur-per-2" xmlns="http://www.w3.org/2000/svg" d="M16.79.7h0A16.14,16.14,0,0,1,32.88,16.79V71.7A16.14,16.14,0,0,1,16.79,87.79h0A16.14,16.14,0,0,1,.7,71.7V16.79A16.14,16.14,0,0,1,16.79.7Z" >
                        </path>
                    </svg>
                </div>
                
                <div style="margin:0 auto;width:33px;height:30px;">
                    <a href="<?php echo($url_prefix); ?>/manage-alerts" class="link-manage-alerts">
                        <div title="Manage alerts" style="padding:4px;margin:0 auto;" class="icon-portfolio-outer">
                            <svg id="icon-alerts" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 541.67 602">
                                <path class="icon-hovered" d="M305.42,559.06A76.51,76.51,0,0,0,229,635.47v16.18l-3.07,1.29A210.06,210.06,0,0,0,97.76,846.73V966.17l-0.8,2.7-59.82,93a16.05,16.05,0,0,0,13.51,24.75h162l1,3.77a94.12,94.12,0,0,0,91,70.63h1.4a94.12,94.12,0,0,0,91-70.63l1-3.77H560.18a16,16,0,0,0,13.51-24.74l-59.82-93.06-0.8-2.7V846.73A210.06,210.06,0,0,0,384.9,652.95l-3.07-1.29V635.47a76.51,76.51,0,0,0-76.42-76.41h0Zm-44.3,76.6v-0.18a44.31,44.31,0,0,1,88.61,0v6.21l-5.92-1.12q-9.49-1.8-19.1-2.71-9.34-.88-19.29-0.89t-19.3.89q-9.6.9-19.09,2.71l-5.92,1.12v-6h0Zm-7.66,451H365.32l-3.45,7.17A62.24,62.24,0,0,1,339,1119.46a61.49,61.49,0,0,1-32.86,9.49h-1.4a61.48,61.48,0,0,1-32.86-9.49A62.25,62.25,0,0,1,249,1093.83l-3.45-7.17h7.94ZM85,1046.86L127.32,981a15.88,15.88,0,0,0,2.55-8.69h0V846.73h0a178.16,178.16,0,0,1,51.45-125.52,173.76,173.76,0,0,1,248.18,0A178.16,178.16,0,0,1,481,846.73V972.35A15.89,15.89,0,0,0,483.5,981l42.31,65.82,4.95,7.7H80.07l4.95-7.7h0Z" transform="translate(-34.58 -559.06)"/>
                            </svg>
                        </div>
                    </a>
                </div>
                
            </div>
        </div>

        <!-- Header - RIGHT SIDE -->
        <div style="float:right;width: 56px;">
            <div class="fixed grid-header-right">
                <?php if ( is_user_logged_in() ) {  ?>

                <div style="margin:0 auto;width:31px;">
                    <a href="<?php echo site_url(); ?>/account" style="cursor:pointer;">
                        <div title="Account" style="padding:3px;margin:0 auto;" class="icon-portfolio-outer">
                            <svg id="icon-account" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 552.52 552.52">
                                <path class="icon-hovered" d="M553.24,338.26l-38.78-6.58a219.29,219.29,0,0,0-15.58-37.62l22.85-32a34,34,0,0,0-3.69-44l-34.4-34.39a33.89,33.89,0,0,0-24.13-10A33.51,33.51,0,0,0,439.78,180l-32.09,22.85a216.61,216.61,0,0,0-39-16l-6.46-38.32A34.1,34.1,0,0,0,328.52,120H279.93a34.1,34.1,0,0,0-33.71,28.51l-6.69,39.24A213.34,213.34,0,0,0,202,203.57l-31.74-22.86a34.1,34.1,0,0,0-44,3.7L91.79,218.8a34.16,34.16,0,0,0-3.69,44l23.08,32.43A213.4,213.4,0,0,0,95.83,333l-38.32,6.46A34.1,34.1,0,0,0,29,373.12v48.59a34.1,34.1,0,0,0,28.51,33.7l39.24,6.7a213.75,213.75,0,0,0,15.81,37.51L89.83,531.24a34,34,0,0,0,3.69,44l34.4,34.39a33.94,33.94,0,0,0,43.86,3.7l32.43-23.08a218.35,218.35,0,0,0,36.47,15L247.14,644a34.1,34.1,0,0,0,33.7,28.51h48.71A34.09,34.09,0,0,0,363.26,644l6.58-38.78a218.66,218.66,0,0,0,37.63-15.58l32,22.85a34.08,34.08,0,0,0,44-3.69l34.4-34.4a34.17,34.17,0,0,0,3.69-44l-22.85-32.09a216.78,216.78,0,0,0,15.58-37.62L553,454.26a34.09,34.09,0,0,0,28.51-33.7V372a33.68,33.68,0,0,0-28.28-33.7h0Zm-2.65,82.29a3,3,0,0,1-2.54,3l-48.48,8.08a15.49,15.49,0,0,0-12.47,11.43A184.9,184.9,0,0,1,467,491.42a15.61,15.61,0,0,0,.69,17l28.51,40.17a3.16,3.16,0,0,1-.34,3.93l-34.4,34.4a3,3,0,0,1-2.2.92,2.86,2.86,0,0,1-1.73-.57l-40-28.51a15.61,15.61,0,0,0-17-.69,184.87,184.87,0,0,1-48.36,20.08,15.32,15.32,0,0,0-11.43,12.47L332.55,639a3,3,0,0,1-3,2.54H281a3,3,0,0,1-3-2.54l-8.08-48.48a15.5,15.5,0,0,0-11.43-12.47,192,192,0,0,1-47.32-19.39,16,16,0,0,0-7.84-2.07,15.21,15.21,0,0,0-9,2.89l-40.4,28.74a3.39,3.39,0,0,1-1.73.58,3.1,3.1,0,0,1-2.19-.92l-34.4-34.4a3.12,3.12,0,0,1-.34-3.92l28.39-39.82a15.81,15.81,0,0,0,.69-17.08A182.82,182.82,0,0,1,124,444.45,15.81,15.81,0,0,0,111.52,433L62.7,424.71a3,3,0,0,1-2.53-3V373.12a3,3,0,0,1,2.53-3L110.83,362a15.62,15.62,0,0,0,12.58-11.54A184.41,184.41,0,0,1,143.15,302a15.41,15.41,0,0,0-.81-16.85l-28.74-40.4a3.14,3.14,0,0,1,.34-3.92l34.4-34.4a2.92,2.92,0,0,1,2.19-.92,2.86,2.86,0,0,1,1.74.58l39.82,28.39a15.8,15.8,0,0,0,17.08.69,183.08,183.08,0,0,1,48.25-20.31,15.81,15.81,0,0,0,11.42-12.47l8.31-48.82a3,3,0,0,1,3-2.54h48.6a3,3,0,0,1,3,2.54l8.08,48.13a15.6,15.6,0,0,0,11.54,12.58,187.41,187.41,0,0,1,49.52,20.32,15.6,15.6,0,0,0,17-.69l39.82-28.62a3.41,3.41,0,0,1,1.73-.58,3.1,3.1,0,0,1,2.19.92L496,240a3.14,3.14,0,0,1,.35,3.93l-28.51,40a15.6,15.6,0,0,0-.69,17,184.82,184.82,0,0,1,20.09,48.36,15.31,15.31,0,0,0,12.46,11.42L548.16,369a3,3,0,0,1,2.54,3v48.59h-0.12Z" transform="translate(-29 -120)"/><path class="icon-hovered" d="M305.32,277A119.23,119.23,0,1,0,424.55,396.2,119.31,119.31,0,0,0,305.32,277h0Zm0,207.29a88.07,88.07,0,1,1,88.06-88.06,88.12,88.12,0,0,1-88.06,88.06h0Z" transform="translate(-29 -120)"/>
                            </svg>
                        </div>
                    </a>
                </div>

                <?php } else { ?>

                <div style="margin:0 auto;width:30px;">
                    <a href="<?php echo site_url(); ?>/account" style="cursor:pointer;">
                        <div title="Sign up" style="padding:3px;margin:0 auto;" class="icon-portfolio-outer">
                            <svg id="icon-login" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 513.14 565.96">
                                <path class="icon-hovered" d="M254.39,304.93h4c36.6-.58,66.21-12.65,88.07-35.74,48.09-50.87,40.1-138.07,39.22-146.36-3.12-62.48-34.6-92.36-60.58-106.3C305.73,6.1,283.1.47,257.89,0h-2.13c-13.87,0-41.1,2.11-67.21,16.06s-58.22,43.83-61.34,106.77c-.87,8.32-8.87,95.52,39.23,146.36,21.72,23.12,51.35,35.15,87.95,35.74ZM160.57,125.74c0-.35.13-.71.13-.94,4.12-84,67.71-93.06,94.94-93.06h1.52c33.73.7,91.07,13.59,94.94,93.06a2,2,0,0,0,.13.94c.12.81,8.87,80.51-30.86,122.48-15.74,16.64-36.73,24.84-64.33,25.08h-1.28c-27.48-.24-48.6-8.45-64.21-25.08-39.6-41.72-31.1-121.78-31-122.48h0Z"/><path class="icon-hovered" d="M513.11,449.56v-.35c0-.94-.13-1.88-.13-2.93-.74-23.21-2.37-77.46-56.59-94.81-.37-.12-.87-.24-1.25-.36C398.8,337.63,352,307.16,351.46,306.81A17.56,17.56,0,0,0,328,310.65v0a15.19,15.19,0,0,0,4.09,22h0c2.13,1.4,51.85,33.87,114.06,48.87,29.1,9.73,32.35,38.91,33.23,65.68a20.53,20.53,0,0,0,.13,2.93c.12,10.54-.63,26.84-2.62,36.21-20.24,10.78-99.57,48.06-220.25,48.06-120.17,0-200-37.39-220.36-48.17-2-9.38-2.87-25.67-2.62-36.23,0-.93.12-1.87.12-2.92.88-26.72,4.12-55.91,33.23-65.68,62.2-15,111.92-47.57,114-48.87a15.19,15.19,0,0,0,4.12-22l0,0a17.57,17.57,0,0,0-23.47-3.87h0c-.5.35-47.1,30.82-103.69,44.31-.5.11-.87.23-1.25.35C2.54,368.81.92,423.07.16,446.17A23.85,23.85,0,0,1,0,449.1v.35c-.13,6.1-.25,37.39,6.37,53.1a15.21,15.21,0,0,0,6.5,7.38c3.74,2.35,93.57,56,243.85,56s240.11-53.8,243.85-56a16,16,0,0,0,6.5-7.38c6.25-15.6,6.12-46.91,6-53h0Z"/>
                            </svg>
                        </div>
                    </a>
                </div>

                <?php } ?>

                <div style="margin:0 auto;width:30.5px;">
                    <a href="<?php echo($url_prefix); ?>/portfolio" class="link-portfolio" style="cursor:pointer;">
                        <div title="Portfolio" style="padding:3px;margin:0 auto;padding-top:5px;" class="icon-portfolio-outer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.84 444.68">
                                <path class="icon-hovered" d="M532.25,1903.35a8.52,8.52,0,0,0,2.5-6V1747.45a102.51,102.51,0,0,1-31,22.9,101.06,101.06,0,0,1-43.8,9.94H363.12v30.62a13.54,13.54,0,0,1-13.54,13.54H261.26a13.54,13.54,0,0,1-13.54-13.54v-30.62H150.86a101.06,101.06,0,0,1-43.8-9.94,102.51,102.51,0,0,1-31-22.9v149.86a8.55,8.55,0,0,0,8.54,8.54h441.6a8.52,8.52,0,0,0,6-2.51h0ZM336,1736.13H274.8v61.24H336v-61.24h0Zm-257.46-130a8.51,8.51,0,0,0-2.5,6v66.24a74.87,74.87,0,0,0,74.78,74.78h96.86v-30.62a13.54,13.54,0,0,1,13.54-13.54h88.32a13.54,13.54,0,0,1,13.54,13.54v30.62H460a74.87,74.87,0,0,0,74.78-74.78v-66.24a8.55,8.55,0,0,0-8.54-8.54H84.62a8.52,8.52,0,0,0-6,2.51h0Zm132.48-88.32a8.52,8.52,0,0,0-2.5,6v52.7H402.28v-52.7a8.55,8.55,0,0,0-8.54-8.54H217.1a8.52,8.52,0,0,0-6,2.5h0Zm218.3,58.74h96.86a35.66,35.66,0,0,1,35.62,35.62v285.12a35.66,35.66,0,0,1-35.62,35.62H84.62A35.66,35.66,0,0,1,49,1897.31V1612.19a35.66,35.66,0,0,1,35.62-35.62h96.86v-52.7a35.66,35.66,0,0,1,35.62-35.62H393.74a35.66,35.66,0,0,1,35.62,35.62v52.7h0Z" transform="translate(-49 -1488.25)"/>
                            </svg>
                        </div>
                    </a>
                </div>

                <div style="margin:0 auto;width:35px;">
                    <a href="<?php echo($url_prefix); ?>/watchlist" class="link-watchlist" style="cursor:pointer;">
                        <div title="Watchlist" style="padding:3px;padding-top:5px;margin:0 auto;" class="icon-portfolio-outer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 585.46 391.69">
                                <path class="icon-hovered" d="M515.16,108.8c40.43,39.24,65.28,74.79,67.55,78.1a15.29,15.29,0,0,1,2,4.25,15.91,15.91,0,0,1,0,9.4,15.36,15.36,0,0,1-2,4.24c-1.71,2.49-16.28,23.34-40.69,50.27a589.46,589.46,0,0,1-46.77,46.22c-28.68,25.24-63.26,50.21-101.91,67.5-31.25,14-65.12,22.91-100.62,22.91a233.59,233.59,0,0,1-72.91-12.14c-26.44-8.68-51.33-21.4-74.15-36.07-28.77-18.49-54.23-40.06-75.4-60.6C29.87,243.69,5,208.12,2.73,204.81a15.46,15.46,0,0,1-2-4.24v0a16.39,16.39,0,0,1,0-9.41h0a15.48,15.48,0,0,1,2-4.23L5,183.59l.07,0a587.54,587.54,0,0,1,65.26-74.85c21.17-20.54,46.63-42.11,75.37-60.59,22.82-14.67,47.72-27.39,74.15-36.06a225,225,0,0,1,145.83,0c26.44,8.67,51.33,21.4,74.15,36.07,28.75,18.48,54.2,40,75.37,60.59ZM252.78,99.21A104.53,104.53,0,0,1,366.64,269.76a105,105,0,0,1-34,22.72,104.45,104.45,0,0,1-79.89,0,105,105,0,0,1-56.69-56.69,104.45,104.45,0,0,1,0-79.89,105,105,0,0,1,56.69-56.69ZM262.92,268a77.9,77.9,0,0,0,85-17h0a78.08,78.08,0,0,0-25.35-127.26,77.9,77.9,0,0,0-84.95,17l0,0a77.86,77.86,0,0,0,0,110.29h0a78.12,78.12,0,0,0,25.35,17ZM229.5,350.9a196.53,196.53,0,0,0,126.58,0c23.12-7.51,45.16-18.58,65.62-31.48,24.65-15.55,47-33.8,66.18-51.7a591.91,591.91,0,0,0,62.37-68.26l2.8-3.61-2.8-3.59A592.82,592.82,0,0,0,487.78,124h0c-19.18-17.88-41.55-36.13-66.23-51.7-20.45-12.91-42.48-24-65.6-31.49a196.38,196.38,0,0,0-126.59,0c-23.12,7.51-45.16,18.57-65.62,31.48-24.65,15.54-47,33.8-66.18,51.7A591.91,591.91,0,0,0,35.2,192.23l-2.8,3.6,2.8,3.6a594,594,0,0,0,62.46,68.28c19.18,17.88,41.55,36.13,66.23,51.7,20.45,12.9,42.49,24,65.61,31.49Z" style="fill-rule:evenodd"/>
                            </svg>
                        </div>
                    </a>
                </div>

            </div>
        </div>

        <!-- Header - Logo -->
        <div style="text-align: center;">
            <div style="height:27px;"></div>
            <div id="logo" style="width:44px;-webkit-filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));">
                <a href="<?php echo site_url(); ?>">
                    <?php if ($theme == '' || $theme == 'classic') { ?>
                    <img src="https://coinwink.com/img/coinwink-crypto-alerts-logo.png?v=002" width="44" alt="Coinwink Crypto Alerts">
                    <?php } else if ($theme == 'matrix') { ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.36 61.36"><title>Coinwink Matrix Logo</title><path d="M30.68,0A30.68,30.68,0,1,1,0,30.68,30.69,30.69,0,0,1,30.68,0Z" class="logo-matrix-base" /><path d="M30.68,6.41a24.29,24.29,0,1,1-17.16,7.11A24.27,24.27,0,0,1,30.68,6.41ZM52.76,30.68A22.08,22.08,0,1,0,30.68,52.76,22.09,22.09,0,0,0,52.76,30.68Z"/><path d="M41.05,36.22a10.37,10.37,0,0,1-20.74,0H23a7.73,7.73,0,1,0,15.46,0Z"/><path d="M28.2,23c-.49,2.16-2.85,4.38-4.51,5.41a14.61,14.61,0,0,1-13.83.86C7.62,28-2.49,19.26,3.69,17.61c4.72-1.27,13.78-.41,18.6.69l1,.25a9.28,9.28,0,0,1,4.25,2.06h6.36a9.27,9.27,0,0,1,4.26-2.06l1-.25c4.83-1.11,13.88-2,18.6-.69,6.18,1.65-3.93,10.4-6.17,11.67a14.61,14.61,0,0,1-13.83-.86c-1.63-1-3.93-3.17-4.49-5.3a5,5,0,0,0-2.58-.73h0A5,5,0,0,0,28.2,23Z" style="fill-rule:evenodd"/></svg>
                    <?php } ?>
                </a>
            </div>
            <div id="txtlogo"><a href="<?php echo site_url(); ?>">Coinwink</a></div>
        </div>

        <!-- Header - Email/SMS switch -->
        <div class="switch-email-sms">
            <input type="radio" class="switch-input" name="switch-email-sms" id="emailSwitch" checked="checked">
            <label for="emailSwitch" class="switch-label switch-label-email noselect" title="Email alerts">Email</label>
            <input type="radio" class="switch-input" name="switch-email-sms" id="smsSwitch">
            <label for="smsSwitch" class="switch-label switch-label-sms noselect" title="SMS alerts">SMS</label>
            <span class="switch-selection"></span>
        </div>

    </div> <!-- End of HEADER -->



<main>

    <!-- Spinner -->
    <div class="containerloader"></div>


    <!-- ----------- -->
    <!-- ALERT FORMS -->
    <!-- ----------- -->

    <!-- FORM: EMAIL CUR -->
    <div class="current-view" id="email">

        <div class="container">

            <header>
            <h2 class="text-header">NEW EMAIL ALERT</h2>
            </header>

            <?php if ( is_user_logged_in() ) { ?>

                <!-- LOGGED IN: EMAIL_CUR -->

                <form method="post" id="form_new_alert_acc">

                    <div class="text-label">Coin to watch:</div>
                    <div class="grid-select2">
                        <div>
                            <select class="selectcoin" name="id" id="id_acc"></select>
                        </div>
                        <div></div>
                        <div style="font-size:10px;" id="pricediv_acc">
                            Loading...
                        </div>
                    </div>
                        
                    <input name="coin" id="coin_acc" type="hidden" value="Bitcoin" />
                    <input name="symbol" id="symbol_acc" type="hidden" value="BTC" />


                    <div class="block-alert">
                        <div class="text-label">Alert me by email:</div>
                        <input maxlength="99" class="input-general" autocapitalize="off" id="email_acc" name="email" type="text" required />
                    </div>


                    <div class="block-alert">
                        <div class="text-label">Alert when price is above:</div>
                        <div class="grid-new-alert-conditions">
                            <div>
                                <input value="" id="above_acc" name="above" maxlength="32" type="text" step="any" autocomplete="off" class="input-general-fluid">
                            </div>
                            <div></div>
                            <div>
                                <select name="above_currency" id="above_currency_acc" class="select-css-currency">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="AUD">AUD</option>
                                    <option value="CAD">CAD</option>
                                    <option value="BRL">BRL</option>
                                    <option value="MXN">MXN</option>
                                    <option value="JPY">JPY</option>
                                    <option value="SGD">SGD</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="block-alert">
                        <div class="text-label">And/or when price is below:</div>
                        <div class="grid-new-alert-conditions">
                            <div>
                                <input value="" id="below_acc" name="below" maxlength="32" type="text" step="any" autocomplete="off" class="input-general-fluid">
                            </div>

                            <div></div>

                            <div>
                                <select name="below_currency" id="below_currency_acc" class="select-css-currency">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="AUD">AUD</option>
                                    <option value="CAD">CAD</option>
                                    <option value="BRL">BRL</option>
                                    <option value="MXN">MXN</option>
                                    <option value="JPY">JPY</option>
                                    <option value="SGD">SGD</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="height:15px;"></div>

                    <div id="feedback_acc" class="feedback"></div>

                    <div id="limit-error" class="feedback-limit-error">
                        You have reached the limit of 5 alerts.
                        <div style="height:15px;"></div>
                        To continue, delete some alerts first or <a href="<?php echo site_url(); ?>/subscription" class="blacklink link-subscription"><b>Upgrade to Premium</b></a>
                        <div style="height:7px;"></div>
                    </div>
                    
                    <div style="height:15px;"></div>

                    <input name="action" type="hidden" value="create_alert_acc" />
                    <input name="unique_id" type="hidden" value="<?php echo($unique_id); ?>">

                    <div class="block-submit">
                        <input type="submit" id="create_alert_button_acc" class="button-main" value="Create alert" />

                        <div id="ajax_loader_acc" class="create-alert-spinner">
                            <div class="appify-spinner-div"></div>
                        </div>
                    </div>

                </form>

            <?php }	else { ?>

                <!-- LOGGED OUT: EMAIL_CUR -->

                <form method="post" id="form_new_alert">


                    <div class="text-label">Coin to watch:</div>
                    <div class="grid-select2">
                        <div>
                            <select class="selectcoin" name="id" id="id"></select>
                        </div>
                        <div></div>
                        <div style="font-size:10px;" id="pricediv">
                            Loading...
                        </div>
                    </div>


                    <input name="coin" id="coin" type="hidden" value="Bitcoin" />
                    <input name="symbol" id="symbol" type="hidden" value="BTC" />

                    <div class="block-alert">
                        <div class="text-label">Alert me by email:</div>
                        <input value="" maxlength="99" class="input-general" autocapitalize="off" id="email_out" name="email" type="text" required />
                    </div>

                    <div class="block-alert">
                        <div class="text-label">Alert when price is above:</div>
                        <div class="grid-new-alert-conditions">
                            <div>
                                <input value="" id="above" name="above" maxlength="32" type="text" step="any" autocomplete="off" class="input-general-fluid">
                            </div>
                            <div></div>
                            <div>
                                <select name="above_currency" id="above_currency" class="select-css-currency">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="AUD">AUD</option>
                                    <option value="CAD">CAD</option>
                                    <option value="BRL">BRL</option>
                                    <option value="MXN">MXN</option>
                                    <option value="JPY">JPY</option>
                                    <option value="SGD">SGD</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="block-alert">
                        <div class="text-label">And/or when price is below:</div>
                        <div class="grid-new-alert-conditions">
                            <div>
                                <input value="" id="below" name="below" maxlength="32" type="text" step="any" autocomplete="off" class="input-general-fluid">
                            </div>

                            <div></div>

                            <div>
                                <select name="below_currency" id="below_currency" class="select-css-currency">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="AUD">AUD</option>
                                    <option value="CAD">CAD</option>
                                    <option value="BRL">BRL</option>
                                    <option value="MXN">MXN</option>
                                    <option value="JPY">JPY</option>
                                    <option value="SGD">SGD</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        
                        
                    </div>

                    <div style="height:15px;"></div>

                    <div id="feedback" class="feedback"></div>

                    <div id="limit-error" class="feedback-limit-error" style="line-height:140%;">
                        You have reached the limit of 5 alerts.
                        <br><br>
                        <a href="<?php echo site_url(); ?>/account" style="color:red!important;"><b>Sign up</b></a> for a free Coinwink account<br>to increase the limits and manage alerts.
                    </div>
                    
                    <div style="height:15px;"></div>

                    <input name="action" type="hidden" value="create_alert" />

                    <div class="block-submit">
                        <input type="submit" id="create_alert_button" class="button-main" value="Create alert" />

                        <div id="ajax_loader" class="create-alert-spinner">
                            <div class="appify-spinner-div"></div>
                        </div>
                    </div>

                </form>

            <?php } ?>

        </div>

    </div>


    <!-- FORM: EMAIL PER -->
    <div class="current-view" id="email-per">

        <div class="container">

            <header style="height:63px;">
            <h2 class="text-header">NEW EMAIL ALERT</h2>
            <div style="margin-top:-18px;font-size:11px;">Percentage</div>
            </header>

            <?php if ( is_user_logged_in() ) { ?>

                <!-- LOGGED IN: EMAIL_PER -->

                <form method="post" id="form_new_alert_percent_acc">

                    <div class="text-label">Coin to watch:</div>
                    <div class="grid-select2">
                        <div>
                            <select class="selectcoin" name="id" id="id_percent_acc"></select>
                        </div>
                        <div></div>
                        <div style="font-size:10px;" id="pricediv_percent_acc">
                            Loading...
                        </div>
                    </div>
		  
                    <input name="coin" id="coin_percent_acc" type="hidden" value="Bitcoin" />
                    <input name="symbol" id="symbol_percent_acc" type="hidden" value="BTC" />
                    <input name="price_set_btc" id="price_set_btc_acc" type="hidden" value="" />
                    <input name="price_set_usd" id="price_set_usd_acc" type="hidden" value="" />
                    <input name="price_set_eth" id="price_set_eth_acc" type="hidden" value="" />	

                    <div class="block-alert">
                        <div class="text-label">Alert me by email:</div>
                        <input maxlength="99" class="input-general" autocapitalize="off" id="email_percent_acc" name="email_percent" type="text" required />
                    </div>


                    <div class="block-alert">
                        <div class="text-label">Alert when price increases by:</div>
                        <div class="grid-new-alert-per">
                            <div>
                                <input value="" class="input-per" id="plus_percent_acc" name="plus_percent" maxlength="32" type="text" step="any" autocomplete="off">
                            </div>

                            <div class="alert-create-per-label">
                                %
                            </div>

                            <div>
                                <select name="plus_change" id="plus_change_acc" class="select-css-currency">
                                    <option value="from_now">from now</option>
                                    <option value="1h">in 1h. period</option>
                                    <option value="24h">in 24h. period</option>
                                </select>
                            </div>
                        </div>

                        <div style="clear:both;height:5px;"></div>

                        <div id="div_plus_compared_acc" class="grid-new-alert-per-compared">
                            <div style="text-align:right;padding-top:4px;padding-right:2px;">
                                Compared to:&nbsp;
                            </div>
                            <div>
                                <select name="plus_compared" id="plus_compared_acc" class="select-css-currency" style="height:24px;">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                </select>
                            </div>
                        </div>

                        <div id="plus_usd_acc" class="compared-to-usd" >
                            Compared to: <span class="span-usd">USD</span>
                        </div>
                        
                    </div>

                    <div class="block-alert">
                        <div class="text-label">And/or when price decreases by:</div>

                        <div class="grid-new-alert-per">
                            <div>
                                <input value="" class="input-per" id="minus_percent_acc" name="minus_percent" maxlength="32" type="text" step="any" autocomplete="off">
                            </div>

                            <div class="alert-create-per-label">
                                %
                            </div>

                            <div>
                                <select name="minus_change" id="minus_change_acc" class="select-css-currency">
                                    <option value="from_now">from now</option>
                                    <option value="1h">in 1h. period</option>
                                    <option value="24h">in 24h. period</option>
                                </select>
                            </div>
                        </div>

                        <div style="clear:both;height:5px;"></div>

                        <div id="div_minus_compared_acc" class="grid-new-alert-per-compared">
                            <div style="text-align:right;padding-top:4px;padding-right:2px;">
                                Compared to:&nbsp;
                            </div>
                            <div>
                                <select name="minus_compared" id="minus_compared_acc" class="select-css-currency" style="height:24px;">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                </select>
                            </div>
                        </div>

                        <div id="minus_usd_acc" class="compared-to-usd" >
                            Compared to: <span class="span-usd">USD</span>
                        </div>

                    </div>
                    
                    <div style="height:15px;"></div>

                    <div id="feedback_percent_acc" class="feedback"></div>

                    <div id="limit-error-per" class="feedback-limit-error">
                        You have reached the limit of 5 alerts.
                        <br><br>
                        To continue, delete some alerts first or <a href="<?php echo site_url(); ?>/subscription" class="blacklink link-subscription"><b>Upgrade to Premium</b></a>
                        <div style="height:7px;"></div>
                    </div>
                    
                    <div style="height:15px;"></div>

                    <input name="action" type="hidden" value="create_alert_percent_acc" />
                    <input name="unique_id" type="hidden" value="<?php echo($unique_id); ?>">

                    <div class="block-submit">
                        <input type="submit" id="create_alert_button_percent_acc" class="button-main" value="Create alert" />

                        <div id="ajax_loader_percent_acc" class="create-alert-spinner">
                            <div class="appify-spinner-div"></div>
                        </div>
                    </div>
                        
                </form>

            <?php } else { ?>

                <!-- LOGGED OUT: EMAIL_PER -->

                <form method="post" id="form_new_alert_percent">

                    <div class="text-label">Coin to watch:</div>
                    <div class="grid-select2">
                        <div>
                            <select class="selectcoin" name="id" id="id_percent"></select>
                        </div>
                        <div></div>
                        <div style="font-size:10px;" id="pricediv_percent">
                            Loading...
                        </div>
                    </div>

                    <input name="coin" id="coin_percent" type="hidden" value="Bitcoin" />
                    <input name="symbol" id="symbol_percent" type="hidden" value="BTC" />
                    <input name="price_set_btc" id="price_set_btc" type="hidden" value="" />
                    <input name="price_set_usd" id="price_set_usd" type="hidden" value="" />
                    <input name="price_set_eth" id="price_set_eth" type="hidden" value="" />	
                    
                    <div class="block-alert">
                        <div class="text-label">Alert me by email:</div>
                        <input maxlength="99" class="input-general" autocapitalize="off" id="email_percent" name="email_percent" type="text" required />
                    </div>

                    <div class="block-alert">
                        <div class="text-label">Alert when price increases by:</div>
                        <div class="grid-new-alert-per">
                            <div>
                                <input value="" class="input-per" id="plus_percent" name="plus_percent" maxlength="32" type="text" step="any" autocomplete="off">
                            </div>

                            <div class="alert-create-per-label">
                                %
                            </div>

                            <div>
                                <select name="plus_change" id="plus_change" class="select-css-currency">
                                    <option value="from_now">from now</option>
                                    <option value="1h">in 1h. period</option>
                                    <option value="24h">in 24h. period</option>
                                </select>
                            </div>
                        </div>

                        <div style="clear:both;height:5px;"></div>

                        <div id="div_plus_compared" class="grid-new-alert-per-compared">
                            <div style="text-align:right;padding-top:4px;padding-right:2px;">
                                Compared to:&nbsp;
                            </div>
                            <div>
                                <select name="plus_compared" id="plus_compared" class="select-css-currency" style="height:24px;">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                </select>
                            </div>
                        </div>

                        <div id="plus_usd" class="compared-to-usd" >
                            Compared to: <span class="span-usd">USD</span>
                        </div>
                        
                    </div>

                    <div class="block-alert">
                        <div class="text-label">And/or when price decreases by:</div>

                        <div class="grid-new-alert-per">
                            <div>
                                <input value="" class="input-per" id="minus_percent" name="minus_percent" maxlength="32" type="text" step="any" autocomplete="off">
                            </div>

                            <div class="alert-create-per-label">
                                %
                            </div>

                            <div>
                                <select name="minus_change" id="minus_change" class="select-css-currency">
                                    <option value="from_now">from now</option>
                                    <option value="1h">in 1h. period</option>
                                    <option value="24h">in 24h. period</option>
                                </select>
                            </div>
                        </div>

                        <div style="clear:both;height:5px;"></div>

                        <div id="div_minus_compared" class="grid-new-alert-per-compared">
                            <div style="text-align:right;padding-top:4px;padding-right:2px;">
                                Compared to:&nbsp;
                            </div>
                            <div>
                                <select name="minus_compared" id="minus_compared" class="select-css-currency" style="height:24px;">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                </select>
                            </div>
                        </div>

                        <div id="minus_usd" class="compared-to-usd" >
                            Compared to: <span class="span-usd">USD</span>
                        </div>

                    </div>

                    <div>
                        
                        <div style="margin:0 auto;display:table;margin-bottom:-15px;"><?php echo apply_filters( 'cptch_display', '', 'Coinwink' ); ?></div>
                    </div>

                    <div style="height:10px;"></div>

                    <div id="feedback_percent" class="feedback"></div>

                    <div id="limit-error-per-sans-acc" class="feedback-limit-error" style="line-height:140%;">
                        You have reached the limit of 5 alerts.<br><br><a href="<?php echo site_url(); ?>/account" style="color:red!important;"><b>Sign up</b></a> for a free Coinwink account<br>to increase the limits and manage alerts.
                    </div>

                    <div style="height:15px;"></div>

                    <input name="action" type="hidden" value="create_alert_percent" />

                    <div class="block-submit">
                        <input type="submit" id="create_alert_button_percent" class="button-main" value="Create alert" />
                        
                        <div id="ajax_loader_percent" class="create-alert-spinner">
                            <div class="appify-spinner-div"></div>
                        </div>
                    </div>

                </form>

            <?php } ?>

        </div>
        
    </div>


    <!-- FORM: SMS CUR -->

    <div class="current-view" id="sms">

        <div class="container">

            <header>
                <h2 class="text-header">NEW SMS ALERT</h2>
            </header>

            <?php if ( is_user_logged_in() ) { 	?>
                
                <?php if ($subs == 0) { ?>
                    <div style="margin-top:-15px;margin-bottom:20px;">
                        <a href="<?php echo site_url(); ?>/subscription" class="link-subscription">Subscribe</a><br>
                        <div class="subscribe-note">
                            Subscribe to enable SMS alerts
                        </div>
                    </div>
                <?php } ?>

                <form method="post" id="form_new_alert_sms">
                    
                    <div class="text-label">Coin to watch:</div>
                    <div class="grid-select2">
                        <div>
                            <select class="selectcoin" name="id" id="id_sms"></select>
                        </div>
                        <div></div>
                        <div style="font-size:10px;" id="pricediv_sms">
                            Loading...
                        </div>
                    </div>

                    <input name="coin" id="coin_sms" type="hidden" value="Bitcoin" />
                    <input name="symbol" id="symbol_sms" type="hidden" value="BTC" />
                    
                    <div class="block-alert">
                        <div class="text-label">Your phone number:<br><div style="font-size:10px;margin-bottom:2px;">It should start with the plus sign. <a href="https://support.twilio.com/hc/en-us/articles/223183008-Formatting-International-Phone-Numbers" class="label-more-info" target="blank">More info</a></div></div>
                        <input maxlength="99" class="input-general" id="phone" name="phone" type="text" placeholder="e.g. +14155552671" required>
                    </div>
                    
                    <div class="block-alert">
                        <div class="text-label">Alert when price is above:</div>
                        <div class="grid-new-alert-conditions">
                            <div>
                                <input value="" class="input-general-fluid sms_input" id="above_sms" name="above_sms" maxlength="32" type="text" step="any" autocomplete="off">
                            </div>
                            <div></div>
                            <div>
                                <select name="above_currency_sms" id="above_currency_sms" class="select-css-currency">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="AUD">AUD</option>
                                    <option value="CAD">CAD</option>
                                    <option value="BRL">BRL</option>
                                    <option value="MXN">MXN</option>
                                    <option value="JPY">JPY</option>
                                    <option value="SGD">SGD</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="block-alert">
                        <div class="text-label">And/or when price is below:</div>
                        <div class="grid-new-alert-conditions">
                            <div>
                                <input value="" class="input-general-fluid sms_input" id="below_sms"  name="below_sms" maxlength="32" type="text" step="any" autocomplete="off">
                            </div>

                            <div></div>

                            <div>            
                                <select name="below_currency_sms" id="below_currency_sms" class="select-css-currency">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="AUD">AUD</option>
                                    <option value="CAD">CAD</option>
                                    <option value="BRL">BRL</option>
                                    <option value="MXN">MXN</option>
                                    <option value="JPY">JPY</option>
                                    <option value="SGD">SGD</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="height:15px;"></div>

                    <div id="reserved_message"></div>

                    <div id="feedback_sms" class="feedback content"></div>

                    <div style="height:15px;"></div>

                    <input name="action" type="hidden" value="create_alert_sms" />


                    <?php if (($subs == 0)) { ?>
                        <div class="block-submit" style="margin-top:-3px;margin-bottom:20px;">
                            <input type="submit" class="button-main button-disabled" value="Create alert" disabled/>
                            <div style="margin-top:13px;">
                                <a href="<?php echo site_url(); ?>/subscription" class="link-subscription"><b>Subscribe</b></a>
                            </div>
                        </div>
                    <?php }	else { ?>
                        <div class="block-submit">
                            <input type="submit" id="create_alert_button_sms" class="button-main" value="Create alert" />
                            
                            <div id="ajax_loader_sms" class="create-alert-spinner">
                                <div class="appify-spinner-div"></div>
                            </div>
                        </div>
                    <?php } ?>

                </form>

            <?php } else { ?>

                <div style="margin-top:50px;">
                    For SMS alerts, please create an account
                </div>
                <br>
                <form action="<?php echo site_url(); ?>/account/#signup">
                    <input type="submit" class="hashLink button-acc" value="Sign up">
                </form>
                <br>
                <br>
                <br>
                Already have an account?
                <br><br>
                <form style="margin-bottom:10px;" action="<?php echo site_url(); ?>/account/#login">
                    <input type="submit" class="hashLink button-acc" value="Log in">
                </form>
                <a href="<?php echo site_url(); ?>/account/#forgotpass" class="blacklink hashLink">
                    Password recovery
                </a>
                <br>
                <br>
                <br>
            
            <?php } ?>

        </div>
        
    </div>


    <!-- FORM: SMS PER -->

    <div class="current-view" id="sms-per">

        <div class="container">

            <?php if ( is_user_logged_in() ) { 	?>
                
                <header style="height: 63px;">
                    <h2 class="text-header">NEW SMS ALERT</h2>
                    <div style="margin-top:-18px;font-size:11px;">Percentage</div>
                </header>

                <?php if ($subs == 0) { ?>
                    <div style="margin-top:-15px;margin-bottom:20px;">
                        <a href="<?php echo site_url(); ?>/subscription" class="link-subscription">Subscribe</a><br>
                        <div class="subscribe-note">
                            Subscribe to enable SMS alerts
                        </div>
                    </div>
                <?php } ?>

                <form method="post" id="form_new_alert_sms_per">

                    <div class="text-label">Coin to watch:</div>
                    <div class="grid-select2">
                        <div>
                            <select class="selectcoin" name="id" id="id_sms_per"></select>
                        </div>
                        <div></div>
                        <div style="font-size:10px;" id="pricediv_sms_per">
                            Loading...
                        </div>
                    </div>
		  
                    <input name="coin" id="coin_sms_per" type="hidden" value="Bitcoin" />
                    <input name="symbol" id="symbol_sms_per" type="hidden" value="BTC" />
                    <input name="price_set_btc" id="price_set_btc_sms_per" type="hidden" value="" />
                    <input name="price_set_usd" id="price_set_usd_sms_per" type="hidden" value="" />
                    <input name="price_set_eth" id="price_set_eth_sms_per" type="hidden" value="" />

                    <div class="block-alert">
                        <div class="text-label">Your phone number:<br><div style="font-size:10px;margin-bottom:2px;">It should start with the plus sign. <a href="https://support.twilio.com/hc/en-us/articles/223183008-Formatting-International-Phone-Numbers" class="label-more-info" target="blank">More info</a></div></div>
                        <input maxlength="99" class="input-general" id="phone_sms_per" name="phone" type="text" placeholder="e.g. +14155552671" required>
                    </div>

                    <div class="block-alert">
                        <div class="text-label">Alert when price increases by:</div>
                        <div class="grid-new-alert-per">
                            <div>
                                <input value="" class="input-per sms_per_input" id="plus_sms_per" name="plus_percent" maxlength="32" type="text" step="any" autocomplete="off">
                            </div>

                            <div class="alert-create-per-label">
                                %
                            </div>

                            <div>
                                <select name="plus_change" id="plus_change_sms_per" class="select-css-currency">
                                    <option value="from_now">from now</option>
                                    <option value="1h">in 1h. period</option>
                                    <option value="24h">in 24h. period</option>
                                </select>
                            </div>
                        </div>

                        <div style="clear:both;height:5px;"></div>

                        <div id="div_plus_compared_sms_per" class="grid-new-alert-per-compared">
                            <div style="text-align:right;padding-top:4px;padding-right:2px;">
                                Compared to:&nbsp;
                            </div>
                            <div>
                                <select name="plus_compared" id="plus_compared_sms_per" class="select-css-currency" style="height:24px;">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                </select>
                            </div>
                        </div>

                        <div id="plus_usd_sms_per" class="compared-to-usd" >
                            Compared to: <span class="span-usd">USD</span>
                        </div>

                    </div>

                    <div class="block-alert">

                        <div class="text-label">And/or when price decreases by:</div>

                        <div class="grid-new-alert-per">
                            <div>
                                <input value="" class="input-per sms_per_input" id="minus_sms_per"  name="minus_percent" maxlength="32" type="text" step="any" autocomplete="off">
                            </div>

                            <div class="alert-create-per-label">
                                %
                            </div>
                        
                            <div>
                                <select name="minus_change" id="minus_change_sms_per" class="select-css-currency">
                                    <option value="from_now">from now</option>
                                    <option value="1h">in 1h. period</option>
                                    <option value="24h">in 24h. period</option>
                                </select>
                            </div>
                        </div>

                        <div style="clear:both;height:5px;"></div>

                        <div id="div_minus_compared_sms_per" class="grid-new-alert-per-compared">
                            <div style="text-align:right;padding-top:4px;padding-right:2px;">
                                Compared to:&nbsp;
                            </div>
                            <div>
                                <select name="minus_compared" id="minus_compared_sms_per" class="select-css-currency" style="height:24px;">
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                    <option value="ETH">ETH</option>
                                </select>
                            </div>
                        </div>
                        
                        <div id="minus_usd_sms_per" class="compared-to-usd" >
                            Compared to: <span class="span-usd">USD</span>
                        </div>
                    </div>

                    <div style="height:14px;"></div>

                    <div id="reserved_message_per"></div>

                    <div style="height:2px;"></div>

                    <div id="feedback_sms_per" class="feedback"></div>

                    <div style="height:14px;"></div>

                    <input name="action" type="hidden" value="create_alert_sms_per" />

                    <?php if (($subs == 0)) { ?>
                        <div class="block-submit" style="margin-top:-7px;margin-bottom:19px;">
                            <input type="submit"  class="button-main button-disabled" value="Create alert" disabled/>
                            <div style="margin-top:10px;">
                                <a href="<?php echo site_url(); ?>/subscription" class="link-subscription"><b>Subscribe</b></a>
                            </div>
                        </div>
                    <?php }	else { ?>
                        <div class="block-submit">
                            <input type="submit" id="create_alert_button_sms_per" class="button-main" value="Create alert" />
                        
                            <div id="ajax_loader_sms_per"  class="create-alert-spinner">
                                <div class="appify-spinner-div"></div>
                            </div>
                        </div>
                    <?php } ?>

                </form>
                
            <?php } else { ?>

                <header style="height: 63px;">
                    <h2 class="text-header">NEW SMS ALERT</h2>
                    <div style="margin-top:-18px;font-size:11px;">Percentage</div>
                </header>

                <div style="margin-top:50px;">
                    For SMS alerts, please create an account
                </div>
                <br>
                <form action="<?php echo site_url(); ?>/account/#signup">
                    <input type="submit" class="hashLink button-acc" value="Sign up">
                </form>
                <br>
                <br>
                <br>
                Already have an account?
                <br><br>
                <form style="margin-bottom:10px;" action="<?php echo site_url(); ?>/account/#login">
                    <input type="submit" class="hashLink button-acc" value="Log in">
                </form>
                <a href="<?php echo site_url(); ?>/account/#forgotpass" class="blacklink hashLink">
                    Password recovery
                </a>
                <br>
                <br>
                <br>

            <?php } ?>
        
        </div>

    </div>


    <!-- TEMPLATE: Success messages -->

    <div class="container current-view" id="created-container" style="display:none;border-radius:3px;">
        <header id="created-header" style="margin-bottom:10px;">
            <h2 class="text-header" id="created-header-title">New Bitcoin Alert</h2>
            <div style="margin-top:-18px;font-size:11px;display:none;" id="created-header-per">Percentage</div>
        </header>
        
        <div class="content">
            <div style="height:20px;"></div>
            <b><span id="created-alert-type">Your Bitcoin (BTC) alert has been created</span></b>
            <div style="height:25px;"></div>
            You will be alerted when:
            <div style="height:15px;"></div>

            <div class="rounded-border" id="created-alert-first" style="display:none;line-height:140%;">Bitcoin (BTC)<br>price is above X USD</div>
            
            <div class="rounded-border" id="created-alert-second" style="display:none;line-height:140%;">Bitcoin (BTC)<br>price is below X USD</div>
            
            <span id="created-sing-or-plur">Alerts</span> will be delivered to: 
            <div style="height:2px;"></div>
            <span id="created-delivered-to" style="font-weight:bold;"></span>

            <div style="height:33px;"></div>

            <div id="created-sign-up" style="display:none;">
                <a href="https://coinwink.com/account" class="blacklink"><b>Sign up</b></a> for a free Coinwink account to manage your crypto alerts.
                <div style="height:20px;"></div>
            </div>

            <!-- <div id="created-sign-up" style="display:none;">
                <a href="https://coinwink.com/account/" class="blacklink">
                    <span style="font-weight:bold;font-size:14px;">Sign up</span>
                </a>
                <br>
                for a free Coinwink account<br>to manage your crypto alerts.
                <div style="height:20px;"></div>
            </div> -->

            <div style="display:none;" id="created-manage-alerts-link">
                <a href="<?php echo site_url(); ?>/manage-alerts" class="blacklink link-manage-alerts">Manage alerts</a>
            </div>

            <div style="height:18px;"></div>

            <a style="display:none;" href="<?php echo site_url(); ?>/email" class="blacklink link-email new-crypto-alert-link">New crypto alert</a>

            <a style="display:none;" href="<?php echo site_url(); ?>/email-per" class="blacklink link-email-per new-crypto-alert-link">New crypto alert</a>

            <a style="display:none;" href="<?php echo site_url(); ?>/sms" class="blacklink link-sms new-crypto-alert-link">New crypto alert</a>

            <a style="display:none;" href="<?php echo site_url(); ?>/sms-per"class="blacklink link-sms-per new-crypto-alert-link">New crypto alert</a>

            <div style="height:2px;"></div>

        </div>
    </div>


    <!-- SCREEN: Manage alerts -->

    <div id="manage-alerts" class="current-view">

        <div class="container">

            <header>
                <h2 class="text-header">MANAGE ALERTS</h2>
            </header>

            <!-- LOGED OUT -->
            <?php if ( !is_user_logged_in() ) { ?>

                <div class="content" style="padding-top:20px;">

                    Log in to manage your crypto alerts
                    <br>
                    <div style="height:8px;"></div>
                    <a href="<?php echo site_url(); ?>/account/#login">
                        <input type="submit" class="button-acc hashLink" value="Log in">
                    </a>

                    <div style="height:10px;"></div>

                    <a href="<?php echo site_url(); ?>/account/#forgotpass" class="blacklink hashLink">Password recovery</a>
                            
                    <div style="height:50px;"></div>

                    Create a free Coinwink account
                    <br>
                    <div style="height:8px;"></div>
                    <a href="<?php echo site_url(); ?>/account">
                        <input type="submit" class="hashLink button-acc" value="Sign up">
                    </a>

                    <div style="height:20px;"></div>
                        
                </div>

            <!-- LOGGED IN -->
            <?php } if ( is_user_logged_in() ) { ?>

                <div class="content">

                    <form method="post" id="manage_alerts_acc_form" action="">
                        <input type="hidden" name="unique_id" value="<?php echo($unique_id); ?>">
                        <input type="hidden" name="action" value="manage_alerts_acc">
                    </form>

                    <div id="manage_alerts_acc_loader">
                        <div class="appify-spinner-div"></div>
                    </div>

                    <div style="margin-top:9px;" id="manage_alerts_acc_feedback"></div>

                </div>

            <?php } ?>

        </div>

    </div>


    <!-- SCREEN: Pricing -->

    <div class="current-view" id="pricing">

        <div class="container">

            <header>
            <h2 class="text-header">PRICING</h2>
            </header>

            <div class="content">

                <h4>Coinwink Plans</h4>

                <div style="height:5px;"></div>

                <div class="rounded-border">
                    <b>Free</b><br>
                    Without the account<br><br>
                    <div style="padding-left:40px;margin-bottom:16px;">
                        <div style="float:left;width:12px;">
                            <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                            </svg>
                        </div>
                        <div style="float:left;margin-left:7px;">
                            5 active Email alerts
                        </div>
                    </div>    
                </div>

                <div class="rounded-border">
                    <b>Always Free</b><br>
                    With the account<br><br>
                    <div style="padding-left:40px;margin-bottom:16px;">
                        <div style="float:left;width:12px;">
                            <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                            </svg>
                        </div>
                        <div style="float:left;margin-left:7px;">
                            5 active Email alerts
                        </div>
                        <div style="clear:both;height:3px;"></div>
                        <div style="float:left;width:12px;">
                            <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                            </svg>
                        </div>
                        <div style="float:left;margin-left:7px;">
                            5 coins in Portfolio
                        </div>
                        <div style="clear:both;height:3px;"></div>
                        <div style="float:left;width:12px;">
                            <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                            </svg>
                        </div>
                        <div style="float:left;margin-left:7px;">
                            5 coins in Watchlist
                        </div>
                    </div>
                </div>

                <div class="rounded-border">
                    <b>Premium</b><br>
                    US$12.00/month<br><br>
                    <div style="padding-left:29px;margin-bottom:18px;">
                        <div style="float:left;width:12px;">
                            <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                            </svg>
                        </div>
                        <div style="float:left;margin-left:7px;">
                            Unlimited Email alerts
                        </div>
                        <div style="clear:both;height:3px;"></div>
                        <div style="float:left;width:12px;">
                            <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                            </svg>
                        </div>
                        <div style="float:left;margin-left:7px;">
                            Unlimited Portfolio
                        </div>
                        <div style="clear:both;height:3px;"></div>
                        <div style="float:left;width:12px;">
                            <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                            </svg>
                        </div>
                        <div style="float:left;margin-left:7px;">
                            Unlimited Watchlist
                        </div>
                        <div style="clear:both;height:3px;"></div>
                        <div style="float:left;width:12px;">
                            <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                            </svg>
                        </div>
                        <div style="float:left;margin-left:7px;">
                            100 SMS alerts per month
                        </div>
                    </div>
                </div>
                
                <div style="height:5px;"></div>

                <?php if ( is_user_logged_in() ) { ?>
                    <span style="font-size:14px;">Your current plan: 
                    <?php if ($subs == 0) { ?><b>Free</b>
                    <?php } else if ($subs == 1 && $status == 'active') { ?><b>Premium</b>
                    <?php } else if ($subs == 1 && $status == 'cancelled') { ?><b>Cancelled</b>
                    <?php } else if ($subs == 1 && $status == 'suspended') { ?><b>Suspended</b>
                    <?php } ?>
                    </span>
                    <div style="height:15px;"></div>
                <?php } ?>
                
                <?php if ( (is_user_logged_in()) && ($subs == 0)) { ?>
                    <a href="<?php echo site_url(); ?>/subscription" class="blacklink link-subscription">Upgrade</a><br>
                <?php } else if ( (is_user_logged_in()) && ($subs == 1)) { ?>
                    <a href="<?php echo site_url(); ?>/account" class="blacklink">Account</a><br>
                <?php } else if ( !is_user_logged_in() ) { ?>
                    <a href="<?php echo site_url(); ?>/account/#signup" class="blacklink">Create account</a>
                    <br>
                <?php } ?>
                
                <div style="height:3px;"></div>

            </div>

        </div>

    </div>

    
    <!-- -------------------- -->
    <!-- SCREEN: Subscription -->
    <!-- -------------------- -->
    <div class="current-view" id="subscription">

        <?php if ( is_user_logged_in() ) { ?>
        <div class="container">

            <header>
                <h2 class="text-header">SUBSCRIPTION</h2>
            </header>

            <div class="content" style="width:280px;">
                
                <?php if (isset($_GET['declined']) && ($subs == 0)) { ?>
                    <div class="content" style="margin-top:5px;margin-bottom:5px;color:red;">
                        Your payment was declined. You can try again. Please double-check the data you have entered, or use another card.
                        <br><br><br>
                    </div>
                <?php } ?>

                <span style="font-size:14px;">Your current plan: 
                    <?php if ($subs == 0 && $status != 'suspended' ) { ?><b>Free</b>
                    <?php } else if ($subs == 1 && $status == 'active') { ?><b>Premium</b>
                    <?php } else if ($subs == 1 && $status == 'cancelled') { ?><b>Cancelled</b>
                    <?php } else if ($subs == 0 && $status == 'suspended') { ?><b>Suspended</b>
                    <?php } ?>
                </span>
                <br><br><br>

                <?php if ($subs == 0 && $status != 'suspended') { ?>
                    <div style="margin:0 auto;width: 260px;">
                        <!-- Upgrade to <b>Premium</b> -->

                        <div class="rounded-border">
                            Upgrade to <b>Premium</b>
                            <br><br>
                            <div style="padding-left:30px;margin-bottom:18px;">
                                <div style="float:left;width:12px;">
                                    <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                                    </svg>
                                </div>
                                <div style="float:left;margin-left:7px;">
                                    Unlimited Email alerts
                                </div>
                                <div style="clear:both;height:3px;"></div>
                                <div style="float:left;width:12px;">
                                    <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                                    </svg>
                                </div>
                                <div style="float:left;margin-left:7px;">
                                    Unlimited Watchlist
                                </div>
                                <div style="clear:both;height:3px;"></div>
                                <div style="float:left;width:12px;">
                                    <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                                    </svg>
                                </div>
                                <div style="float:left;margin-left:7px;">
                                    Unlimited Portfolio
                                </div>
                                <div style="clear:both;height:3px;"></div>
                                <div style="float:left;width:12px;">
                                    <svg viewBox="0 0 512 444.03"><polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03" style="fill:#777777"/>
                                    </svg>
                                </div>
                                <div style="float:left;margin-left:7px;">
                                    100 SMS alerts per month
                                </div>
                            </div>
                        </div>

                    </div>

                    <div style="height:5px;"></div>

                    <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                        <input id="agree" type="checkbox" class="appify-input-checkbox"/>
                        <label for="agree">
                        <div class="checkbox-box" style="margin-top:-1px;">  
                            <svg><use xlink:href="#checkmark" /></svg>
                        </div> 
                        I accept the <a href="https://coinwink.com/terms" target="_blank" class="blacklink blacklink-acc">Terms and Conditions</a>  
                        </label>
                    </div>
            
                    <div style="height:25px;"></div>

                    <button id="cc-pay-button" onclick="stripeCheckout()" class="button-checkout">Pay $12.00</button>

                    <div style="height: 35px;"></div>

                    <div style="clear:both;text-align:center;font-size:12px;">
                        Subscription price: <b>12 USD per month</b>
                        <br>
                        <div style="height:5px;"></div>
                        Cancel at any time in your account settings
                        <div style="height: 24px;"></div>
                    </div>
                    
                    Payment with a bank card
                    <div style="height:5px;"></div>
                    Powered by <a href="https://stripe.com" target="_blank" class="blacklink blacklink-acc">Stripe</a>

                    <!-- <div style="height:5px;"></div> -->

                <?php } ?>
                
                <?php if ($subs == 1 && $status == 'active') { ?>
                    Unlimited alerts<br>
                    Unlimited coins in portfolio<br>
                    Unlimited coins in watchlist<br>
                    SMS left this month: <b><?php echo $sms; ?></b>
                    <br>
                    <br>
                    <br>
                    <a href="<?php echo site_url(); ?>/account" class="blacklink">Account</a>
                <?php } ?>

                <?php if ($subs == 1 && $status == 'cancelled') { ?>
                    <div class="content">
                        Your subscription was cancelled.
                        <br><br>
                        You can continue using Premium features until <?php echo $date_end; ?>. 
                        After that, it will automatically switch to a free plan.
                    </div>
                <?php } ?>

                <?php if ($subs == 0 && $status == 'suspended') { ?>
                    <div class="content">
                        We were not able to proceed your credit card for recurring monthly payment.
                        <br><br>
                        Your Premium plan features are currently disabled.
                        <br><br>We will try to proceed your 
                        credit card during the period of the next few days. If the payment is received, the 
                        Premium features will be re-enabled automatically.
                    </div>
                <?php } ?>

                <?php 
                    echo do_shortcode( '[promo_shortcode]' ); 
                ?>

            </div>
        </div>
        <?php } ?>

    </div>


    <!-- --------- -->
    <!-- WATCHLIST -->
    <!-- --------- -->

    <div id="watchlist" class="current-view">
        
        <!-- LOGGED IN WATCHLIST -->
        <?php if ( is_user_logged_in() ) { ?>

            <div class="container" style="margin-bottom:22px;">

                <header style="margin-bottom:0px;display:block!important;">
                    <h2 class="text-header">WATCHLIST</h2>
                </header>

                <div id="watchlist_container">

                    <div id="watchlist_empty" class="content" style="margin-top:30px;display:none;">
                        <b>Watchlist Quickstart</b>
                        <br><br>
                        Select your first coin and add it with the PLUS button.
                        <br><br>
                        To remove a coin, select it in the list and click the MINUS button.
                        <br><br>
                        For faster navigation, use keyboard shortcuts (Enter, Tab, Shift+Tab).
                        <br><br>
                        Drag and drop coins to re-order them.
                        <br><br>
                        Click the price column name to switch to volume and market cap views, formatted in millions.
                        <div style="height:10px;"></div>
                        <div class="pw-line"></div>
                        <div style="height:5px;"></div>
                    </div>

                    <?php if ($theme == '' || $theme == 'classic') { ?>
                        <div class="animated-gif-base64-spinner-loader" id="ajax_loader_watchlist" style="margin-top:30px;margin-bottom:10px;">
                            <div style="height:32px;background-repeat: no-repeat;background-position: center;background-image: url('data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==');"></div>
                            <div style="height:10px;"></div>
                            <div class="pw-line"></div>
                        </div>
                    <?php } else if ($theme == 'matrix') { ?>
                        <div id="ajax_loader_watchlist" style="margin-top:30px;margin-bottom:10px;">
                            <div class="appify-spinner-div"></div>
                            <div style="height:10px;"></div>
                            <div class="pw-line"></div>
                        </div>
                    <?php } ?>
                
                    <div id="watchlist_content" style="display:none;">
                        <!-- Inject Watchlist -->
                    </div>

                    <div id="watchlist-message" style="clear:both;padding-top:30px;padding-bottom:0px;line-height:160%;">
                        You have reached your watchlist limit.
                        <br>
                        To enable unlimited features,<br><a href="<?php echo site_url(); ?>/subscription" class="blacklink link-subscription"><b>Upgrade to Premium</b></a>
                    </div>

                    <div class="text-label" style="margin-top:30px;">Add or remove coin:</div>
                    <select class="selectcoin" id="watchlist_dropdown"></select>
                    <div class="portfolio-buttons">
                        <button id="watchlist_add_coin" class="plus-minus" style="float:left;">
                            <svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Add</title><polygon points="392.51 155.51 457.01 155.51 457.01 392.51 694.01 392.51 694.01 457.01 457.01 457.01 457.01 694.01 392.51 694.01 392.51 457.01 155.51 457.01 155.51 392.51 392.51 392.51 392.51 155.51 392.51 155.51" class="plus-minus-svg" /></svg>
                        </button>
                        <button id="watchlist_remove_coin" class="plus-minus" style="float:right;padding-left:1px;">
                            <svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Remove</title><polygon points="155.51 463.46 155.51 386.06 694.01 386.06 694.01 463.46 155.51 463.46 155.51 463.46" class="plus-minus-svg" /></svg>
                        </button>
                    </div>

                    <div style="height:30px;"></div>

                </div>

            </div>

            <div style="height:10px;"></div>

        </div>
                
        <!-- LOGGED OUT WATCHLIST -->            
        <?php } else { ?>

            <div class="container">
                
                <header style="margin-bottom:0px;display:block!important;">
                    <h2 class="text-header">WATCHLIST</h2>
                </header>

                <div style="margin-top:45px;padding-left:20px;padding-right:20px;">
                    
                    <h1>Cryptocurrency Watchlist</h1>

                    <div style="height:5px;"></div>

                    Track your favorite crypto coins and tokens.
                    <div style="height:12px;"></div>
                    Bitcoin, Ethereum, XRP and other 3500+ cryptocurrencies.
                    <div style="height:12px;"></div>
                    Based on CoinMarketCap.
                    <div style="height:12px;"></div>
                    Convert between BTC, ETH, USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD.
                    <div style="height:12px;"></div>
                    Switch between price, volume and market cap views.
                    <div style="height:12px;"></div>
                    Keep individual notes for every coin in your watchlist.
                    <div style="height:10px;"></div>
                    
                    <div style="padding:45px 10px 10px 10px;">
                    Manage your crypto watchlist with a free Coinwink account.
                    </div>

                    <a href="<?php echo site_url(); ?>/account">
                        <input type="submit" class="hashLink button-acc" value="Sign up">
                    </a>

                    <div style="padding:40px 10px 10px 10px;">
                        Already have an account?
                    </div>

                    <a style="margin-bottom:10px;" href="<?php echo site_url(); ?>/account/#login">
                        <input type="submit" class="hashLink button-acc" value="Log in">
                    </a>
                    
                    <div style="height:15px;"></div>

                    <a href="<?php echo site_url(); ?>/account/#forgotpass" class="blacklink hashLink">Password recovery</a>

                    <div style="height:35px;"></div>

                </div>
                
            </div>

        <?php } ?>

    </div>


    <!-- --------- -->
    <!-- PORTFOLIO -->
    <!-- --------- -->

    <div id="portfolio" class="current-view">

        <div class="container">

            <header style="margin-bottom:0px;">
            <h2 class="text-header">PORTFOLIO</h2>
            </header>

            <?php if ( is_user_logged_in() ) { ?>

                <!-- Logged in PORTFOLIO -->

                <div id="portfolio_container">

                    <div id="portfolio_empty" class="content" style="margin-top:30px;display:none;">
                        <b>Portfolio Quickstart</b>
                        <br><br>
                        Select your first coin and add it with the PLUS button.
                        <br><br>
                        To remove a coin, select it in the list and click the MINUS button.
                        <br><br>
                        Tip: For faster navigation, use keyboard shortcuts (Enter, Tab, Shift+Tab).
                        <div style="height:10px;"></div>
                        <div class="pw-line"></div>
                        <div style="height:5px;"></div>
                    </div>

                    <?php if ($theme == '' || $theme == 'classic') { ?>
                        <div class="animated-gif-base64-spinner-loader" id="ajax_loader_portfolio" style="margin-top:30px;margin-bottom:10px;">
                            <div style="height:32px;background-repeat: no-repeat;background-position: center;background-image: url('data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==');"></div>
                            <div style="height:10px;"></div>
                            <div class="pw-line"></div>
                        </div>
                    <?php } else if ($theme == 'matrix') { ?>
                        <div id="ajax_loader_portfolio" style="margin-top:30px;margin-bottom:10px;">
                            <div class="appify-spinner-div"></div>
                            <div style="height:10px;"></div>
                            <div class="pw-line"></div>
                        </div>
                    <?php } ?>
                    
                    <div id="portfolio_content" style="display:none;">
                        <!-- Inject Portfolio -->
                    </div>

                    <div id="portfolio-message" style="clear:both;padding-top:20px;padding-bottom:5px;line-height:160%;">
                        You have reached your portfolio limit.
                        <br>
                        To enable unlimited features,<br><a href="<?php echo site_url(); ?>/subscription" class="blacklink link-subscription"><b>Upgrade to Premium</b></a>
                    </div>

                    <div class="text-label" style="margin-top:30px;">Add or remove coin:</div>
                    <select class="selectcoin" id="portfolio_dropdown"></select>
                    <div class="portfolio-buttons">
                        <button id="portfolio_add_coin" class="plus-minus" style="float:left;">
                            <svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Add</title><polygon points="392.51 155.51 457.01 155.51 457.01 392.51 694.01 392.51 694.01 457.01 457.01 457.01 457.01 694.01 392.51 694.01 392.51 457.01 155.51 457.01 155.51 392.51 392.51 392.51 392.51 155.51 392.51 155.51" class="plus-minus-svg" /></svg>
                        </button>
                        <button id="portfolio_remove_coin" class="plus-minus" style="float:right;padding-left:1px;">
                            <svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Remove</title><polygon points="155.51 463.46 155.51 386.06 694.01 386.06 694.01 463.46 155.51 463.46 155.51 463.46" class="plus-minus-svg" /></svg>
                        </button>
                    </div>
                    
                    <div style="height:30px;"></div>

                </div>

            </div>

            <div id="portfolio-alerts" class="container container-2">

                <header style="margin-bottom:0px;display:block!important;">
                    <h2 class="text-header">Portfolio Alerts</h2>
                    <div class="expand-collapse" style="top:19px;display:none;" id="portfolio-alerts-hide" title="Collapse">
                        <svg data-name="Layer 1" viewBox="0 0 23 13">
                            <path class="svg-show-hide" d="M22 12H1v-1L11 1h1l11 10-1 1z" stroke="#bdbfc1" stroke-miterlimit="3" fill-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="expand-collapse" id="portfolio-alerts-show" title="Expand">
                        <svg data-name="Layer 1" viewBox="0 0 23 13">
                            <path class="svg-show-hide"  d="M1 1h22L12 12h-1L1 1V0z" stroke="#bdbfc1" stroke-miterlimit="3" fill-rule="evenodd"/>
                        </svg>
                    </div>
                </header>

                <div class="portfolio-alerts-content" id="portfolio-alerts-content" style="font-size:13px;line-height:150%;">
                    <div style="height:30px;"></div>

                    <form id="portfolio-alerts-form">
                        Alert me by:
                        <br>

                        <?php if ( (is_user_logged_in()) && ($subs == 0)) { ?>

                        <select id="portfolio-alert-type" name="alert_type" class="select-css-currency" style="height:28px;width:220px;margin-top:3px;margin-bottom:8px;">
                            <option value="email">E-mail</option>
                            <option value="sms" disabled>SMS</option>
                        </select>

                        <?php } else { ?>

                        <select id="portfolio-alert-type" name="alert_type" class="select-css-currency" style="width:220px;margin-top:3px;margin-bottom:8px;height:28px;">
                            <option value="email">E-mail</option>
                            <option value="sms">SMS</option>
                        </select>

                        <?php } ?>

                        <br>
                        <input type="text" id="portfolio-alert-destination" name="destination" placeholder="E-mail address" style="width:220px;">
                        
                        <div style="height:30px;"></div>

                        <div>

                            <div style="margin-left:-2px;" class="portfolio-alerts-conditions">
                                When any coin in my portfolio:
                                <div style="height:15px;"></div>
                                
                                <div style="padding-left:1px;">

                                    <div class="ma-label">
                                        <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                                            <input id="portfolio-alert-1-checkbox" name="portfolio-alert-1" type="checkbox" class="appify-input-checkbox" />
                                            <label for="portfolio-alert-1-checkbox">
                                                <div class="checkbox-box">  
                                                    <svg><use xlink:href="#checkmark" /></svg>
                                                </div> 
                                                Is up by
                                            </label>
                                        </div>
                                    </div>
                                    <div class="ma-input">
                                        <input name="portfolio-alert-1-value" id="portfolio-alert-1-value" min="5" max="1000" value="10" type="number">&nbsp;&nbsp;<b>%</b> in 1h.
                                    </div>

                                    <div style="height:12px;"></div>

                                    <div class="ma-label">
                                        <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                                            <input id="portfolio-alert-2-checkbox" name="portfolio-alert-2" type="checkbox" class="appify-input-checkbox" />
                                            <label for="portfolio-alert-2-checkbox">
                                                <div class="checkbox-box">  
                                                    <svg><use xlink:href="#checkmark" /></svg>
                                                </div> 
                                                Is down by
                                            </label>
                                        </div>
                                    </div>
                                    <div class="ma-input">
                                        <input name="portfolio-alert-2-value" id="portfolio-alert-2-value" min="5" max="1000" value="10" type="number">&nbsp;&nbsp;<b>%</b> in 1h.
                                    </div>

                                    <div style="clear:both;height:20px;"></div>


                                    <div class="ma-label-2" style="margin-left:-4px;">
                                        <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                                            <input id="portfolio-alert-3-checkbox" name="portfolio-alert-3" type="checkbox" class="appify-input-checkbox" />
                                            <label for="portfolio-alert-3-checkbox">
                                                <div class="checkbox-box">  
                                                    <svg><use xlink:href="#checkmark" /></svg>
                                                </div> 
                                                Is up by
                                            </label>
                                        </div>
                                    </div>
                                    <div class="ma-input-2">
                                        <input name="portfolio-alert-3-value" id="portfolio-alert-3-value" min="5" max="1000" value="10" type="number">&nbsp;&nbsp;<b>%</b> in 24h.
                                    </div>

                                    <div style="height:12px;"></div>

                                    <div class="ma-label-2" style="margin-left:-4px;">
                                        <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                                            <input id="portfolio-alert-4-checkbox" name="portfolio-alert-4" type="checkbox" class="appify-input-checkbox" />
                                            <label for="portfolio-alert-4-checkbox">
                                                <div class="checkbox-box">  
                                                    <svg><use xlink:href="#checkmark" /></svg>
                                                </div> 
                                                Is down by
                                            </label>
                                        </div>
                                    </div>
                                    <div class="ma-input-2">
                                        <input name="portfolio-alert-4-value" id="portfolio-alert-4-value" min="5" max="1000" value="10" type="number">&nbsp;&nbsp;<b>%</b> in 24h.
                                    </div>

                                    <div style="height:10px;"></div>
                                
                                </div>

                            </div>

                        </div>

                    </form>

                    <div class="portfolio-alerts-about" id="portfolio-alerts-about-show-hide">
                        <span class="portfolio-alerts-about-title">About</span>

                        <div class="portfolio-alerts-about-content">
                            Portfolio alerts are multiple-coin alerts. You will receive an alert when any coin in your portfolio will increase/decrease in 1h/24h by a specified percentage.
                            <br><br>
                            The percentage range is between 5% and 1000%.
                            <br><br>
                            Example: Add a few coins to your portfolio. Activate the 1h 10% increase alert. Now each time any coin from your portfolio increases by 10% (or more) in 1 hour period, you will receive an alert.
                            <br><br>
                            Portfolio alerts are continuous. It means that if the alert was sent, it will be sent again the next time the conditions are met. There is no need to manually re-activate it.
                            <br><br>
                            For each individual portfolio coin, the same type of alert is sent once in 24h. For example, if you received an alert that Bitcoin increased more than 10% in 1h, all the following Bitcoin portfolio alerts for the 1h increase will be ignored for the next 24h.
                            <br><br>
                            It means that during a 24 hour period, you can receive a maximum of 4 alerts for a single portfolio coin. For example, Bitcoin increased and decreased in 1h and in 24h by 10%.
                            <br><br>
                            Portfolio alerts are sent every 5 minutes.
                        </div>
                    </div>

                </div>
                
                <div id="portfolio-user-feedback" style="margin-top:30px;">Loading...</div>
            
                <?php } else { ?>

                <!-- Logged out PORTFOLIO -->

                <div style="margin-top:45px;padding-left:20px;padding-right:20px;">

                    <h1>Crypto Portfolio Tracker</h1>

                    <div style="height:5px;"></div>

                    Bitcoin, Ethereum, XRP, Litecoin and other 3500+ crypto coins and tokens.
                    <div style="height:12px;"></div>
                    The data is based on CoinMarketCap, which is the industry standard.
                    <div style="height:12px;"></div>
                    Every coin on Coinwink has a direct link to its CoinMarketCap page where charts, social news and other relevant info can be found.
                    <div style="height:12px;"></div>
                    Convert between BTC, ETH, USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD.
                    <div style="height:12px;"></div>
                    Make notes, calculate return on investment (ROI).
                    <div style="height:12px;"></div>
                    Multi-coin crypto price alerts for your portfolio coins and tokens.

                    <div style="height:10px;"></div>
                    
                    <div style="padding:45px 10px 10px 10px;">
                    Manage your cryptocurrency portfolio with a free Coinwink account.
                    </div>

                    <a href="<?php echo site_url(); ?>/account">
                        <input type="submit" class="hashLink button-acc" value="Sign up">
                    </a>

                    <div style="padding:40px 10px 10px 10px;">
                        Already have an account?
                    </div>

                    <a style="margin-bottom:10px;" href="<?php echo site_url(); ?>/account/#login">
                        <input type="submit" class="hashLink button-acc" value="Log in">
                    </a>
                    
                    <div style="height:15px;"></div>
                    
                    <a href="<?php echo site_url(); ?>/account/#forgotpass" class="blacklink hashLink">Password recovery</a>

                    <div style="height:35px;"></div>

                </div>

                <?php } ?>

            </div>
        
        </div>

    </div>

    

    <!-- STATIC CONTENT PAGES -->
    <?php include('content-pages.php') ?>

</main>


<footer>

    <!-- <div style="height:5px;"></div>
    <div style="text-align:center;background-color:#232323;margin:0 auto;padding:20px 0px 20px 0px">
    
        <a target="_blank" href="https://twitter.com/Coinwink/status/1025407436981657600" style="color:red!important;;tex-decoration:underline;">
        03 05 2020 - Status update
        </a>

        <span style="color:red!important;">
            Temporary e-mail disruption. SMS alerts work well.
            <div style="height:5px;"></div>
            2020-05-30 13:00 UTC
        </a>
        
    </div>
    <div style="height:15px;"></div> -->


    <!-- <div style="height:5px;"></div>
    <div style="color:white;text-align:center;">Just received a strange BTC alert? See <a href="https://twitter.com/Coinwink/status/1174162988791713793" target="_blank" style="color:white;font-weight:bold;">here</a> why.</div>
    <div style="height:15px;"></div> -->

    <?php echo do_shortcode('[footer_shortcode]'); ?>

</footer>


<!-- End of the MAIN APP CONTAINER -->
</div>



<!-- JS/CSS -->

<script>
	var jqueryarray = <?php echo json_encode($CMCdata); ?>;
	var ajax_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	var security_url = "&security=<?php echo $ajax_nonce; ?>";
</script>



<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-3.3.1.min.js"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/select2.min.js?v=400"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/select2_optimization.js?v=404"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/navigo.min.js?v=400"></script>


<script>var subs = "<?php echo($subs); ?>";</script>


<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink_utils.js?v=600"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink_portfolio.js?v=601"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/html5sortable.min.js?v=001"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink_watchlist.js?v=600"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink.js?v=601"></script>


<!-- Stripe Payment -->
<?php if ( is_user_logged_in() ) { 	?>
    <script src="https://js.stripe.com/v3/"></script>
    
<?php } ?>



<?php if ( is_user_logged_in() ) { 	?>
	<script>
		var user_email = "<?php echo(esc_html($wpdb->get_var( "SELECT email FROM cw_settings WHERE user_ID = $user_ID" ))); ?>";
		jQuery("#email_acc").val(user_email);
		jQuery("#email_percent_acc").val(user_email);
		var user_phone_nr = "<?php echo(esc_html($wpdb->get_var( "SELECT phone_nr FROM cw_settings WHERE user_ID = $user_ID" ))); ?>";
		jQuery("#phone").val(user_phone_nr);
		jQuery("#phone_sms_per").val(user_phone_nr);
	</script>
<?php } ?>



<?php
    // // PERFORMANCE TEST STATS
    // // Check memory used in megabytes
    // function convert($size)
    // {
    //     $unit=array('b','kb','mb','gb','tb','pb');
    //     return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    // }
    // echo ("\r\nMemory used: " . convert(memory_get_usage(true)));

    // // End time
    // $time_end = microtime(true);

    // // Total time
    // $execution_time = ($time_end - $time_start);
    // echo ("\r\nExecution time: " . $execution_time . " sec");

    // // Check processing time - END
    // function rutime($ru, $rus, $index) {
    //     return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
    //     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
    // }
    // $ru = getrusage();
    // echo "\r\n\nThis process used " . rutime($ru, $rustart, "utime") .
    //     " ms for its computations\r\n";
    // echo "It spent " . rutime($ru, $rustart, "stime") .
    //     " ms in system calls\r\n";
?>


</body>
</html>