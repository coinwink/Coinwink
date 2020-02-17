<?php /* Template Name: Coinwink - Home */ get_header(); ?>

<?php $ajax_nonce = wp_create_nonce( "my-special-string" ); ?>

<?php
	// Get coin price data from the database
	$result = $wpdb->get_results( "SELECT json FROM cw_data_cmc" , ARRAY_A);
	$CMCdata = array_values(unserialize($result[0]['json']));
?>

<?php
	if ( is_user_logged_in() ) {
        $user_ID = get_current_user_id();
        
		$result = $wpdb->get_results( "SELECT unique_id, legac, subs, sms FROM cw_settings WHERE user_ID = '".$user_ID."'", ARRAY_A);
		$unique_id = $result[0]["unique_id"];
		$legac = $result[0]["legac"];
		$subs = $result[0]["subs"];
        $sms = $result[0]["sms"];
        
		$result = $wpdb->get_results( "SELECT date_end, status, date_renewed, months FROM cw_subs WHERE user_ID = '".$user_ID."'", ARRAY_A);
		$status = $result[0]["status"];
		$months = $result[0]["months"];
		$date_renewed = $result[0]["date_renewed"];
		$date_end = $result[0]["date_end"];
		$date_end = new DateTime($date_end);
		$date_end = $date_end->format('Y-m-d');
	}
?>

<script>
    var unique_id = "<?php echo($unique_id); ?>"
</script>

<div style="position:relative;max-width:800px;margin:0 auto;" class="outer-buttons">

    <!-- LEFT SIDE -->
    <div style="float:left;width:24px;">
        <div class="fixed">

            <div class="switch-cur-per" style="left:11.5px;">
                <input type="radio" class="switch-2-input" name="switch-cur-per" id="curSwitch" checked="checked">
                <label for="curSwitch" id="switch-label-cur" style="margin-left:-1px;" class="switch-2-label switch-label-cur">$</label>
                <input type="radio" class="switch-2-input" name="switch-cur-per" id="perSwitch">
                <label style="margin-left:-3px;margin-top:7.5px;" for="perSwitch" class="switch-2-label switch-label-per">%</label>
                <svg id="switch-img-full" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33.58 88.49">
                    <defs>
                        <style>
                            .cls-1{fill:none;stroke:#c1c1c1;stroke-miterlimit:2;stroke-width:1.3px}
                            .cls-2{fill:none;stroke:#c1c1c1;stroke-miterlimit:2;stroke-width:1.2px}
                        </style>
                    </defs>
                    <path class="cls-1" id="switch-cur-init" d="M16.79,6.83h0A12,12,0,0,1,28.72,18.76V29.1A12,12,0,0,1,16.79,41h0A12,12,0,0,1,4.86,29.1V18.76A12,12,0,0,1,16.79,6.83Z" transform="translate(0 -1)"></path>
                    
                    <path class="cls-1" id="switch-cur" d="M16.79,6.83h0A12,12,0,0,1,28.72,18.76V29.1A12,12,0,0,1,16.79,41h0A12,12,0,0,1,4.86,29.1V18.76A12,12,0,0,1,16.79,6.83Z" ></path>
                    <path class="cls-1" id="switch-per" d="M16.79,6.83h0A12,12,0,0,1,28.72,18.76V29.1A12,12,0,0,1,16.79,41h0A12,12,0,0,1,4.86,29.1V18.76A12,12,0,0,1,16.79,6.83Z" ></path>
                    
                    <path class="cls-2" xmlns="http://www.w3.org/2000/svg" d="M16.79.7h0A16.14,16.14,0,0,1,32.88,16.79V71.7A16.14,16.14,0,0,1,16.79,87.79h0A16.14,16.14,0,0,1,.7,71.7V16.79A16.14,16.14,0,0,1,16.79.7Z" >
                    </path>
                </svg>
            </div>
            
            <div style="position:absolute;top:128.5px;width:23px;left:16.5px;">
                <a data-navigo href="manage-alerts" onclick="reloadManageAlerts()" class="alertslink">
                    <svg id="icon-alerts" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 541.67 602">
                        <title>Manage alerts</title>
                        <?php echo file_get_contents(get_stylesheet_directory_uri() . "/img/icon-alerts.svg"); ?>
                    </svg>
                </a>
            </div>
            
        </div>
	</div>


    <!-- RIGHT SIDE -->

    <div style="float:right;width:6px;">
        <div class="fixed ">
            <?php if ( is_user_logged_in() ) {  ?>

            <div style="position:absolute;top:21px;width:25px;right:11px;">
                <a href="<?php echo site_url(); ?>/account/">
                    <svg id="icon-account" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 552.52 552.52">
                        <title>Account</title>
                        <?php echo file_get_contents(get_stylesheet_directory_uri() . "/img/icon-account.svg"); ?>
                    </svg>
                </a>
            </div>

            <?php } else { ?>

            <div style="position:absolute;top:20px;width:23px;right:11px;">
                <a href="<?php echo site_url(); ?>/account/">
                    <svg id="icon-login" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 513.13 603.26">
                        <title>Sign up</title>
                        <?php echo file_get_contents(get_stylesheet_directory_uri() . "/img/icon-login.svg"); ?>
                    </svg>
                </a>
            </div>

            <?php } ?>

            <div style="position:absolute;top:80px;width:23px;right:12px;" class="icon-portfolio">
                <!-- <a href="#portfolio" class="hashlink portfoliolink"> -->
                <a data-navigo href="portfolio" onclick="reloadPortfolio()" href="https://coinwink.com/portfolio/" class="portfoliolink" style="cursor:pointer;">
                    <svg id="icon-portfolio" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.84 444.68">
                        <title>Portfolio</title>
                        <?php echo file_get_contents(get_stylesheet_directory_uri() . "/img/icon-portfolio.svg"); ?>
                    </svg>
                </a>
            </div>

            <div style="position:absolute;top:130px;width:27px;right:10px;" class="icon-portfolio">
                <!-- <a href="#portfolio" class="hashlink portfoliolink"> -->
                <a data-navigo href="watchlist" onclick="reloadWatchlist()" href="https://coinwink.com/watchlist/" class="portfoliolink" style="cursor:pointer;">
                    <svg id="icon-portfolio" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.84 444.68">
                        <title>Watchlist</title>
                        <?php echo file_get_contents(get_stylesheet_directory_uri() . "/img/icon-watchlist.svg"); ?>
                    </svg>
                </a>
            </div>
        </div>
    
    </div>


	
</div>


<div style="text-align: center;">
    <div style="height:27px;"></div>
    <div id="logo" style="width:44px;-webkit-filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));">
        <a href="<?php echo site_url(); ?>">
            <img src="https://coinwink.com/img/coinwink-crypto-alerts-logo.png" width="44" alt="Coinwink Crypto Alerts">
        </a>
    </div>
	<div id="txtlogo"><a href="<?php echo site_url(); ?>">Coinwink</a></div>
</div>
 
<div class="switch-email-sms">
	<input type="radio" class="switch-input" name="switch-email-sms" id="emailSwitch" checked="checked">
	<label for="emailSwitch" class="switch-label switch-label-email">Email</label>
	<input type="radio" class="switch-input" name="switch-email-sms" id="smsSwitch">
	<label for="smsSwitch" class="switch-label switch-label-sms">SMS</label>
	<span class="switch-selection"></span>
</div>

<div class="containerloader"></div>



<!-- WATCHLIST -->
<div id="watchlist" class="current-view">
      
    <!-- LOGGED IN WATCHLIST -->
    <?php if ( is_user_logged_in() ) { ?>

        <div id="watchlist-alerts" class="container" style="margin-bottom:22px;">

            <header style="margin-bottom:0px;display:block!important;">
                <h2 class="text-header" style="color:white;text-transform: none;">WATCHLIST</h2>
            </header>

            <div id="watchlist_container">

                <div id="watchlist_empty" class="content" style="margin-top:30px;display:none;">
                    <b>Watchlist Quickstart</b>
                    <br><br>
                    Select your first coin and add it with the PLUS button.
                    <br><br>
                    To remove a coin, select it in the list and click the MINUS button.
                    <br><br>
                    Tip: For faster navigation, use keyboard shortcuts (Enter, Tab, Shift+Tab).
                    <div style="height:10px;"></div>
                    __________________________________________
                    <div style="height:5px;"></div>
                </div>

                <div id="ajax_loader_watchlist" style="margin-top:30px;margin-bottom:10px;">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif" alt="Loading spinner">
                    <div style="height:10px;"></div>
                    __________________________________________
                </div>
            
                <div id="watchlist_content" style="display:none;">
                    <!-- Inject Watchlist -->
                </div>

                <div id="watchlist-message" style="clear:both;padding-top:20px;padding-bottom:15px;line-height:160%;">
                    You have reached your watchlist limit.
                    <br>
                    To enable unlimited features,<br><a href="subscription" data-navigo class="blacklink"><b>Upgrade to Premium</b></a>
                </div>

                <div style="height:5px;"></div>

                <div class="text-label">Add or remove coin:</div>
                <select class="selectcoin" id="watchlist_dropdown"></select>
                <div class="portfolio-buttons">
                    <button id="watchlist_add_coin" class="plus-minus" style="border: 1px solid #858585;float:left;">
                        <svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Add</title><polygon points="0.11 0.11 849.41 0.11 849.41 849.41 0.11 849.41 0.11 0.11 0.11 0.11" style="fill:none;stroke:#e6e6e6;stroke-miterlimit:2.613126039505005;stroke-width:0.2160000056028366px"/><polygon points="392.51 155.51 457.01 155.51 457.01 392.51 694.01 392.51 694.01 457.01 457.01 457.01 457.01 694.01 392.51 694.01 392.51 457.01 155.51 457.01 155.51 392.51 392.51 392.51 392.51 155.51 392.51 155.51" style="fill:#666;fill-rule:evenodd"/></svg>
                    </button>
                    <button id="watchlist_remove_coin" class="plus-minus" style="border: 1px solid #858585;float:right;padding-left:1px;">
                        <svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Remove</title><polygon points="0.11 0.11 849.41 0.11 849.41 849.41 0.11 849.41 0.11 0.11 0.11 0.11" style="fill:none;stroke:#e6e6e6;stroke-miterlimit:2.613126039505005;stroke-width:0.2160000056028366px"/><polygon points="155.51 463.46 155.51 386.06 694.01 386.06 694.01 463.46 155.51 463.46 155.51 463.46" style="fill:#666;fill-rule:evenodd"/></svg>
                    </button>
                </div>

                <div style="height:40px;"></div>

            </div>

        </div>

        <div style="height:10px;"></div>

    </div>
            
    <!-- LOGGED OUT WATCHLIST -->            
    <?php } else { ?>

        <div class="container">
            
            <header style="margin-bottom:0px;display:block!important;">
                <h2 class="text-header" style="color:white;text-transform: none;">WATCHLIST</h2>
            </header>

            <div style="margin-top:45px;padding-left:20px;padding-right:20px;">
                
                <h1>Cryptocurrency Watchlist</h1>

                <div style="height:5px;"></div>

                Keep an eye on your favorite crypto coins and tokens
                <div style="height:12px;"></div>
                Bitcoin, Ethereum, XRP and other 2500+ crypto coins and tokens
                <div style="height:12px;"></div>
                Based on CoinMarketCap
                <div style="height:12px;"></div>
                Convert between BTC, ETH, USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD
                <div style="height:12px;"></div>
                Keep individual notes for every coin in your watchlist

                <div style="height:32px;"></div>

                <!-- <a href="https://coinwink.com/brand/files/screenshots/05-watchlist.png?v=010" class="blacklink" target="_blank">Example</a> -->
                <span onclick="exampleWatchlist()" class="blacklink">Example</span>
                
                <div style="padding:45px 10px 10px 10px;">
                Manage your crypto watchlist with a free Coinwink account
                </div>

                <a href="<?php echo site_url(); ?>/account/">
                    <input type="submit" class="hashLink button-acc" value="Sign up">
                </a>

                <div style="padding:40px 10px 10px 10px;">
                    Already have an account?
                </div>

                <a style="margin-bottom:10px;" href="<?php echo site_url(); ?>/account/#login">
                    <input type="submit" class="hashLink button-acc" value="Log in">
                </a>
                
                <div style="height:10px;"></div>

                <a href="<?php echo site_url(); ?>/account/#forgotpass" class="blacklink hashLink">Password recovery</a>

                <div style="height:40px;"></div>


            </div>
            
        </div>

    <?php } ?>

</div>


<!-- PORTFOLIO -->
<div id="portfolio" class="current-view">

    <div class="container">

        <header style="margin-bottom:0px;">
        <h2 class="text-header" style="color:white;">PORTFOLIO</h2>
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
                    __________________________________________
                    <div style="height:5px;"></div>
                </div>

                <div id="ajax_loader_portfolio" style="margin-top:30px;margin-bottom:10px;">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif" alt="Loading spinner">
                    <div style="height:10px;"></div>
                    __________________________________________
                </div>
                
                <div id="portfolio_content" style="display:none;">
                    <!-- Inject Portfolio -->
                </div>

                <div id="portfolio-message" style="clear:both;padding-top:20px;padding-bottom:15px;line-height:160%;">
                    You have reached your portfolio limit.
                    <br>
                    To enable unlimited features,<br><a href="subscription" data-navigo class="blacklink"><b>Upgrade to Premium</b></a>
                </div>

                <div style="height:5px;"></div>

                <div class="text-label">Add or remove coin:</div>
                <select class="selectcoin" id="portfolio_dropdown"></select>
                <div class="portfolio-buttons">
                    <button id="portfolio_add_coin" class="plus-minus" style="border: 1px solid #858585;float:left;">
                        <svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Add</title><polygon points="0.11 0.11 849.41 0.11 849.41 849.41 0.11 849.41 0.11 0.11 0.11 0.11" style="fill:none;stroke:#e6e6e6;stroke-miterlimit:2.613126039505005;stroke-width:0.2160000056028366px"/><polygon points="392.51 155.51 457.01 155.51 457.01 392.51 694.01 392.51 694.01 457.01 457.01 457.01 457.01 694.01 392.51 694.01 392.51 457.01 155.51 457.01 155.51 392.51 392.51 392.51 392.51 155.51 392.51 155.51" style="fill:#666;fill-rule:evenodd"/></svg>
                    </button>
                    <button id="portfolio_remove_coin" class="plus-minus" style="border: 1px solid #858585;float:right;padding-left:1px;">
                        <svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Remove</title><polygon points="0.11 0.11 849.41 0.11 849.41 849.41 0.11 849.41 0.11 0.11 0.11 0.11" style="fill:none;stroke:#e6e6e6;stroke-miterlimit:2.613126039505005;stroke-width:0.2160000056028366px"/><polygon points="155.51 463.46 155.51 386.06 694.01 386.06 694.01 463.46 155.51 463.46 155.51 463.46" style="fill:#666;fill-rule:evenodd"/></svg>
                    </button>
                </div>
                
                <div style="height:35px;"></div>

            </div>

        <?php 
        // if($user_ID == 2 || $user_ID == 19985 || $user_ID == 21056 ) { 
        ?>

        </div>

        <div id="portfolio-alerts" class="container container-2">

            <header style="margin-bottom:0px;display:block!important;">
                <h2 class="text-header" style="color:white;text-transform: none;">Portfolio Alerts</h2>
                <div style="position:absolute;top:20px;right:20px;width:20px;cursor:pointer;" id="portfolio-alerts-hide" title="Collapse">
                    <svg data-name="Layer 1" viewBox="0 0 23 13">
                        <path class="svg-show-hide" d="M22 12H1v-1L11 1h1l11 10-1 1z" fill="#4b4b4d" stroke="#bdbfc1" stroke-miterlimit="3" fill-rule="evenodd"/>
                    </svg>
                </div>
                <div style="position:absolute;top:20px;right:20px;width:20px;cursor:pointer;display:none;" id="portfolio-alerts-show" title="Expand">
                    <svg data-name="Layer 1" viewBox="0 0 23 13">
                        <path class="svg-show-hide"  d="M1 1h22L12 12h-1L1 1V0z" fill="#4b4b4d" stroke="#bdbfc1" stroke-miterlimit="3" fill-rule="evenodd"/>
                    </svg>
                </div>
            </header>

            <div class="portfolio-alerts-content" id="portfolio-alerts-content" style="font-size:13px;line-height:150%;">
                <div style="height:30px;"></div>

                <form id="portfolio-alerts-form">
                    Alert me by:
                    <br>

                    <?php if ( (is_user_logged_in()) && ($subs == 0)) { ?>

                    <select id="portfolio-alert-type" name="alert_type" style="height:25px;width:210px;margin-top:3px;margin-bottom:8px;">
                        <option value="email">E-mail</option>
                        <option value="sms" disabled>SMS</option>
                    </select>

                    <?php } else { ?>

                    <select id="portfolio-alert-type" name="alert_type" style="height:25px;width:210px;margin-top:3px;margin-bottom:8px;">
                        <option value="email">E-mail</option>
                        <option value="sms">SMS</option>
                    </select>

                    <?php } ?>

                    <br>
                    <input type="text" id="portfolio-alert-destination" name="destination" placeholder="E-mail address" style="height:25px;width:210px;">
                    <br>
                    <br>
                    <div style="line-height:250%;">
                    
                        <div style="margin-left:-2px;">
                            When any coin in my portfolio:
                            <div style="height:10px;"></div>
                            
                            <div style="margin-left:30px;">
    
                                <div class="ma-label">
                                    <div class="checkbox-div" title="Enable/disable alert">
                                        <label>
                                            <input id="portfolio-alert-1-checkbox" type="checkbox" name="portfolio-alert-1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    Is up by
                                </div>
                                <div class="ma-input">
                                    <input name="portfolio-alert-1-value" id="portfolio-alert-1-value" min="10" max="1000" value="10" style="width:42px;height:21px;padding-left:2px;font-size:13px;"> <b>%</b> in 1h.
                                </div>

                                <div style="clear:both;height:0px;"></div>

                                <div class="ma-label">
                                    <div class="checkbox-div" title="Enable/disable alert">
                                        <label>
                                            <input id="portfolio-alert-2-checkbox" type="checkbox" name="portfolio-alert-2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    Is down by
                                </div>
                                <div class="ma-input">
                                    <input name="portfolio-alert-2-value" id="portfolio-alert-2-value" min="10" max="1000" value="10" style="width:42px;height:21px;padding-left:2px;font-size:13px;"> <b>%</b> in 1h.
                                </div>

                                <div style="clear:both;height:15px;"></div>


                                <div class="ma-label-2" style="margin-left:-4px;">
                                    <div class="checkbox-div" title="Enable/disable alert">
                                        <label>
                                            <input id="portfolio-alert-3-checkbox" type="checkbox" name="portfolio-alert-3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    Is up by
                                </div>
                                <div class="ma-input-2">
                                    <input name="portfolio-alert-3-value" id="portfolio-alert-3-value" min="10" max="1000" value="10" style="width:42px;height:21x;padding-left:2px;font-size:13px;"> <b>%</b> in 24h.
                                </div>

                                <br>

                                <div class="ma-label-2" style="width:77px;margin-left:-4px;">
                                    <div class="checkbox-div" title="Enable/disable alert">
                                        <label>
                                            <input id="portfolio-alert-4-checkbox" type="checkbox" name="portfolio-alert-4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    Is down by
                                </div>
                                <div class="ma-input-2">
                                    <input name="portfolio-alert-4-value" id="portfolio-alert-4-value" min="10" max="1000" value="10" style="width:42px;height:21px;padding-left:2px;font-size:13px;"> <b>%</b> in 24h.
                                </div>


                                <br>
                            
                            </div>

                        </div>

                    </div>

                </form>

                <div class="portfolio-alerts-about" id="portfolio-alerts-about-show-hide">
                    <span class="portfolio-alerts-about-title">About</span>

                    <div class="portfolio-alerts-about-content">
                        Portfolio alerts are multiple-coin alerts. You will receive an alert when any coin in your portfolio will increase/decrease in 1h/24h by a specified percentage.
                        <br><br>
                        Example: Add a few coins to your portfolio. Activate the 1h 10% increase alert. Now each time any coin from your portfolio increases by 10% (or more) in 1 hour period, you will receive an alert.
                        <br><br>
                        Portfolio alerts are continuous. It means that if the alert was sent, it will be sent again the next time the conditions are met. There is no need to manually re-activate it.
                        <br><br>
                        For each individual portfolio coin, the same type of alert is sent once in 24h. For example, if you received an alert that Bitcoin increased more than 10% in 1h, all the following Bitcoin portfolio alerts for the 1h increase will be ignored for the next 24h.
                        <br><br>
                        It means that during a 24 hour period, you can receive a maximum of 4 alerts for a single portfolio coin. For example, Bitcoin increased and decreased in 1h and in 24h by 10%.
                        <br><br>
                        While basic alerts are sent every 1-2 minutes, portfolio alerts are sent every 5 minutes.
                    </div>
                </div>

            </div>
            
            <div id="portfolio-user-feedback" style="margin-top:25px;margin-bottom:5px;">Loading...</div>
        
            <?php } else { ?>

            <!-- Logged out PORTFOLIO -->

            <div style="margin-top:40px;padding-left:20px;padding-right:20px;">

                <h1>Cryptocurrency<br>Portfolio Tracker</h1>

                <div style="height:5px;"></div>

                Bitcoin, Ethereum, XRP, Litecoin and other 2500+ crypto coins and tokens
                <div style="height:12px;"></div>
                The data is based on the industry standard: CoinMarketCap
                <div style="height:12px;"></div>
                Every coin on Coinwink has a direct link to its CoinMarketCap page where charts, social news and other relevant info can be found
                <div style="height:12px;"></div>
                Convert between BTC, ETH, USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD
                <div style="height:12px;"></div>
                Make notes, calculate return on investment (ROI)
                <div style="height:12px;"></div>
                Multi-coin crypto price alerts for your portfolio coins and tokens

                <div style="height:32px;"></div>

                <!-- <a href="https://coinwink.com/brand/files/screenshots/04-portfolio.png?v=005" class="blacklink" target="_blank">Example</a> -->
                <span onclick="examplePortfolio()" class="blacklink">Example</span>
                
                <div style="padding:45px 10px 10px 10px;">
                Manage your cryptocurrency portfolio with a free Coinwink account
                </div>

                <a href="<?php echo site_url(); ?>/account/">
                    <input type="submit" class="hashLink button-acc" value="Sign up">
                </a>

                <div style="padding:40px 10px 10px 10px;">
                    Already have an account?
                </div>

                <a style="margin-bottom:10px;" href="<?php echo site_url(); ?>/account/#login">
                    <input type="submit" class="hashLink button-acc" value="Log in">
                </a>
                
                <div style="height:10px;"></div>

                <a href="<?php echo site_url(); ?>/account/#forgotpass" class="blacklink hashLink">Password recovery</a>

                <div style="height:40px;"></div>

            </div>

            <?php } ?>

        </div>
    
    </div>

</div>
    

<!-- Form: EMAIL_CUR -->
<div class="current-view" id="email">

    <div class="container">

        <header>
        <h2 class="text-header" style="color:white;">New email alert</h2>
        </header>

        <?php if ( is_user_logged_in() ) { ?>

            <!-- LOGGED IN EMAIL_CUR -->

            <form method="post" id="form_new_alert_acc" style="margin-bottom:7px;">

            <div class="text-label">Coin to watch:</div>
            <select class="selectcoin" name="id" id="id_acc"></select>

            <div style="font-size:10px;margin-top:-6px;margin-bottom:15px;height:12px;" id="pricediv_acc"><div style="height:5px;"></div>Loading...</div>		  
            <input name="coin" id="coin_acc" type="hidden" value="Bitcoin" />
            <input name="symbol" id="symbol_acc" type="hidden" value="BTC" />

            <div class="text-label" style="margin-top:20px;">Alert me by email:</div>
            <input maxlength="99" autocapitalize="off" class="input-general" id="email_acc" name="email" type="text" required />

            <div class="text-label">Alert when price is above:</div>
            <input value="" class="input-above-below" id="above_acc" name="above" maxlength="32" type="text" step="any" autocomplete="off">
            <select name="above_currency" id="above_currency_acc" class="select-currency">
            <option value="BTC">BTC</option>
            <option value="USD">USD</option>
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

            <div class="text-label">And/or when price is below:</div>
            <input value="" class="input-above-below"  id="below_acc" name="below" maxlength="32" type="text" step="any" autocomplete="off">
            <select name="below_currency" id="below_currency_acc" class="select-currency">
            <option value="BTC">BTC</option>
            <option value="USD">USD</option>
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

            <div id="feedback_acc" style="color:red;margin-top:12px;" class="feedback content"></div>

            <div id="limit-error" style="padding-top:10px;">You have reached the limit of 5 alerts.<div style="height:15px;"></div>To continue, delete some alerts first or <a href="subscription" data-navigo style="color:black!important;font-weight:bold;">upgrade to Premium</a></div>
            
            <div style="height:3px;">&nbsp;</div>

            <input name="action" type="hidden" value="create_alert_acc" />
            <input name="unique_id" type="hidden" value="<?php echo($unique_id); ?>">
            <input type="submit" id="create_alert_button_acc" class="submit action-button" value="Create alert" />
            <div id="ajax_loader_acc" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif" alt="Loading spinner"></div>

            </form>

        <?php }	else { ?>

            <!-- LOGGED OUT EMAIL_CUR -->

            <form method="post" id="form_new_alert">

            <div class="text-label">Coin to watch:</div>
            <select class="selectcoin" name="id" id="id" ></select>

            <div style="font-size:10px;margin-top:-6px;margin-bottom:15px;height:12px;" id="pricediv"><div style="height:5px;"></div>Loading...</div>		  
            <input name="coin" id="coin" type="hidden" value="Bitcoin" />
            <input name="symbol" id="symbol" type="hidden" value="BTC" />

            <div class="text-label" style="margin-top:20px;">Alert me by email:</div>
            <input value="" maxlength="99" autocapitalize="off" class="input-general" id="email_out" name="email" type="text" required>

            <div class="text-label">Alert when price is above:</div>
            <input value="" class="input-above-below" id="above" name="above" maxlength="32" type="text" step="any" autocomplete="off">
            <select name="above_currency" id="above_currency" class="select-currency">
            <option value="BTC">BTC</option>
            <option value="USD">USD</option>
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

            <div class="text-label">And/or when price is below:</div>
            <input value="" class="input-above-below" id="below"  name="below" maxlength="32" type="text" step="any" autocomplete="off">
            <select name="below_currency" id="below_currency" class="select-currency">
            <option value="BTC">BTC</option>
            <option value="USD">USD</option>
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

            <div style="height:6px;"></div>

            <div class="content" style="width:260px;"><div id="feedback" style="color:red;" class="feedback"></div></div>

            <div id="limit-error" style="line-height:140%;">You have reached the limit of 5 alerts.<br><br><a href="/account" style="color:red!important;"><b>Sign up</b></a> for a free Coinwink account<br>to increase the limits and manage alerts.</div>

            <input name="action" type="hidden" value="create_alert" />
            <input type="submit" id="create_alert_button" class="submit action-button" value="Create alert" />
            <div id="ajax_loader" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif" alt="Loading spinner"><div style="height:6px;"></div></div>
            
            <div style="height:3px;"></div>

            </form>

        <?php } ?>

    </div>

</div>



<!-- Form: EMAIL_PER -->
<div class="current-view" id="email-per">

    <div class="container">

        <header style="height:63px;">
        <h2 class="text-header" style="color:white;">New email alert</h2>
        <div style="margin-top:-18px;font-size:11px;">Percentage</div>
        </header>

        <?php if ( is_user_logged_in() ) { ?>

            <!-- LOGGED IN EMAIL_PER -->

            <form method="post" id="form_new_alert_percent_acc">

            <div class="text-label">Coin to watch:</div>
            <select class="selectcoin" name="id" id="id_percent_acc" ></select>

            <div style="font-size:10px;margin-top:-6px;margin-bottom:15px;height:12px;" id="pricediv_percent_acc"><div style="height:5px;"></div>Loading...</div>		  
            <input name="coin" id="coin_percent_acc" type="hidden" value="Bitcoin" />
            <input name="symbol" id="symbol_percent_acc" type="hidden" value="BTC" />
            <input name="price_set_btc" id="price_set_btc_acc" type="hidden" value="" />
            <input name="price_set_usd" id="price_set_usd_acc" type="hidden" value="" />
            <input name="price_set_eth" id="price_set_eth_acc" type="hidden" value="" />	

            <div class="text-label" style="margin-top:22px;">Alert me by email:</div>
            <input value="" maxlength="99" autocapitalize="off" class="input-general" id="email_percent_acc" name="email_percent" type="text" required>

            <div class="text-label" style="margin-top:16px;">Alert when price increases by:</div>
            <input value="" class="input-per" id="plus_percent_acc" name="plus_percent" maxlength="32" type="text" step="any" autocomplete="off">&nbsp;<span class="per">%</span>&nbsp;&nbsp;
            <select name="plus_change" id="plus_change_acc" class="input-per-change">
                <option value="from_now">from now</option>
                <option value="1h">in 1h. period</option>
                <option value="24h">in 24h. period</option>
            </select>
            <div id="div_plus_compared_acc" style="margin-left:63px;margin-top:-3px;margin-bottom:5px;">
            Compared to:&nbsp;
            <select name="plus_compared" id="plus_compared_acc" class="select-currency" style="height:24px;margin-top:3px;">
                <option value="USD">USD</option>
                <option value="BTC">BTC</option>
                <option value="ETH">ETH</option>
            </select>
            </div>
            <div id="plus_usd_acc" style="display:none;margin-left:107px;margin-top:-1px;margin-bottom:13px!important;">
            Compared to: USD
            </div>

            <div class="text-label">And/or when price decreases by:</div>
            <input value="" class="input-per" id="minus_percent_acc" name="minus_percent" maxlength="32" type="text" step="any" autocomplete="off">&nbsp;<span class="per">%</span>&nbsp;&nbsp;
            <select name="minus_change" id="minus_change_acc" class="input-per-change">
                <option value="from_now">from now</option>
                <option value="1h">in 1h. period</option>
                <option value="24h">in 24h. period</option>
            </select>
            <div id="div_minus_compared_acc" style="margin-left:63px;margin-top:-3px;margin-bottom:10px;">
            Compared to:&nbsp;
            <select name="minus_compared" id="minus_compared_acc" class="select-currency" style="height:24px;margin-top:3px;">
                <option value="USD">USD</option>
                <option value="BTC">BTC</option>
                <option value="ETH">ETH</option>
            </select>
            </div>
            <div id="minus_usd_acc" style="display:none;margin-left:107px;margin-top:-1px;margin-bottom:13px!important;">
            Compared to: USD
            </div>

            <div id="feedback_percent_acc" style="color:red;" class="feedback content"></div>

            <div id="limit-error-per" style="display:none;padding-top:10px;padding-left:15px;padding-right:15px;padding-bottom:10px;">You have reached the limit of 5 alerts.<br><br>To continue, delete some alerts first or <a href="subscription" data-navigo style="color:black!important;font-weight:bold;">upgrade to Premium</a></div>

            <div style="height:3px;"></div>

            <input name="action" type="hidden" value="create_alert_percent_acc" />
            <input name="unique_id" type="hidden" value="<?php echo($unique_id); ?>">
            <input type="submit" id="create_alert_button_percent_acc" class="submit action-button" value="Create alert" />

            <div id="ajax_loader_percent_acc" style="display:none;">
                <div style="margin-top:-7px;"></div>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif" alt="Loading spinner"></div>
                <div style="height:1px;"></div>
            </div>
                   
            <div style="height:8px;"></div>

            </form>

        <?php } else { ?>

            <!-- LOGGED OUT Form: EMAIL_PER -->

            <form method="post" id="form_new_alert_percent">

            <div class="text-label">Coin to watch:</div>
            <select class="selectcoin" name="id" id="id_percent" ></select>

            <div style="font-size:10px;margin-top:-6px;margin-bottom:15px;height:12px;" id="pricediv_percent"><div style="height:5px;"></div>Loading...</div>		  
            <input name="coin" id="coin_percent" type="hidden" value="Bitcoin" />
            <input name="symbol" id="symbol_percent" type="hidden" value="BTC" />
            <input name="price_set_btc" id="price_set_btc" type="hidden" value="" />
            <input name="price_set_usd" id="price_set_usd" type="hidden" value="" />
            <input name="price_set_eth" id="price_set_eth" type="hidden" value="" />	
            
            <div class="text-label" style="margin-top:22px;">Alert me by email:</div>
            <input value="" maxlength="99" autocapitalize="off" class="input-general" id="email_percent" name="email_percent" type="text" required>

            <div class="text-label" style="margin-top:16px;">Alert when price increases by:</div>
            <input value="" class="input-per" id="plus_percent" name="plus_percent" maxlength="32" type="text" step="any" autocomplete="off">&nbsp;<span class="per">%</span>&nbsp;&nbsp;
            <select name="plus_change" id="plus_change" class="input-per-change">
                <option value="from_now">from now</option>
                <option value="1h">in 1h. period</option>
                <option value="24h">in 24h. period</option>
            </select>
            <div id="div_plus_compared" style="margin-left:63px;margin-top:-3px;margin-bottom:5px;">
                Compared to:&nbsp;
                <select name="plus_compared" id="plus_compared" class="select-currency" style="height:24px;margin-top:3px;">
                    <option value="USD">USD</option>
                    <option value="BTC">BTC</option>
                    <option value="ETH">ETH</option>
                </select>
            </div>
            <div id="plus_usd" style="display:none;margin-left:107px;margin-top:-1px;margin-bottom:13px!important;">
            Compared to: USD
            </div>

            <div class="text-label">And/or when price decreases by:</div>
            <input value="" class="input-per" id="minus_percent"  name="minus_percent" maxlength="32" type="text" step="any" autocomplete="off">&nbsp;<span class="per">%</span>&nbsp;&nbsp;
            <select name="minus_change" id="minus_change" class="input-per-change">
                <option value="from_now">from now</option>
                <option value="1h">in 1h. period</option>
                <option value="24h">in 24h. period</option>
            </select>
            <div id="div_minus_compared" style="margin-left:63px;margin-top:-3px;margin-bottom:10px;">
            Compared to:&nbsp;
            <select name="minus_compared" id="minus_compared" class="select-currency" style="height:24px;margin-top:3px;">
                <option value="USD">USD</option>
                <option value="BTC">BTC</option>
                <option value="ETH">ETH</option>
            </select>
            </div>
            <div id="minus_usd" style="display:none;margin-left:107px;margin-top:-1px;margin-bottom:13px!important;">
            Compared to: USD
            </div>

            <div id="feedback_percent" style="color:red;" class="feedback content"></div>

            <div id="limit-error-per-sans-acc" style="line-height:140%;">You have reached the limit of 5 alerts.<br><br><a href="/account" style="color:red!important;"><b>Sign up</b></a> for a free Coinwink account<br>to increase the limits and manage alerts.</div>

            <input name="action" type="hidden" value="create_alert_percent" />
            <input type="submit" id="create_alert_button_percent" class="submit action-button" value="Create alert" />
            <div id="ajax_loader_percent" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif" alt="Loading spinner"></div>
            
            <div style="height:5px;"></div>

            </form>

        <?php } ?>

    </div>
    
</div>




<!-- SMS forms - CURRENCY -->

<div class="current-view" id="sms">

    <div class="container">

        <?php if ( is_user_logged_in() ) { 	?>

            <header>
            <h2 class="text-header" style="color:white;">New SMS alert</h2>
            </header>
            
            <?php if ($subs == 0) { ?>
                <div style="margin-top:-15px;margin-bottom:25px;">
                    <a href="subscription" data-navigo class="blacklink hashLink">Subscribe</a><br>
                    <div style="color:red;">
                        Subscribe to enable SMS alerts
                    </div>
                </div>
            <?php } ?>

            <form method="post" style="margin-top:15px;" id="form_new_alert_sms">

                <div class="text-label">Coin to watch:</div>
                <select class="selectcoin" name="id" id="id_sms" ></select>

                <div style="font-size:10px;margin-top:-6px;margin-bottom:14px;height:12px;" id="pricediv_sms"><div style="height:5px;"></div>Loading...</div>		  
                <input name="coin" id="coin_sms" type="hidden" value="Bitcoin" />
                <input name="symbol" id="symbol_sms" type="hidden" value="BTC" />

                <div class="text-label" style="margin-top:20px;">Your phone number:<br><div style="font-size:10px;margin-bottom:2px;">It should start with the plus sign. <a href="https://support.twilio.com/hc/en-us/articles/223183008-Formatting-International-Phone-Numbers" class="blacklink" target="blank">More info</a></div></div>
                <input maxlength="99" class="input-general" id="phone" name="phone" type="text" placeholder="e.g. +14155552671" required>

                <div class="text-label">Alert when price is above:</div>
                <input value="" class="input-above-below sms_input" id="above_sms" name="above_sms" maxlength="32" type="text" step="any" autocomplete="off">
                <select name="above_currency_sms" id="above_currency_sms" class="select-currency">
                <option value="BTC">BTC</option>
                <option value="USD">USD</option>
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

                <div class="text-label">And/or when price is below:</div>
                <input value="" class="input-above-below sms_input" id="below_sms"  name="below_sms" maxlength="32" type="text" step="any" autocomplete="off">
                <select name="below_currency_sms" id="below_currency_sms" class="select-currency">
                <option value="BTC">BTC</option>
                <option value="USD">USD</option>
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

                <div style="height:10px;"></div>
                <div id="reserved_message"></div>

                <div id="feedback_sms" class="feedback content" style="color:red;padding-top:4px;padding-bottom:4px;"></div>

                <input name="action" type="hidden" value="create_alert_sms" />
                <?php if (($subs == 0)) { ?>
                <input type="submit" class="button-action-disabled" value="Create alert" disabled/>
                <div style="margin-top:-5px;"><a href="subscription" data-navigo class="blacklink" style="color:red;"><b>Subscribe</b></a></div>
                <?php }	else { ?>
                <input type="submit" id="create_alert_button_sms" class="submit action-button" style="margin-top:0px;margin-bottom:10px;" value="Create alert" />
                <div id="ajax_loader_sms" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif" alt="Loading spinner"></div>
                <?php } ?>

            </form>

        <?php } else { ?>

            <header>
            <h2 class="text-header" style="color:white;">New SMS alert</h2>
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


<!-- SMS forms - PERCENTAGE -->

<div class="current-view" id="sms-per">

    <div class="container">

        <?php if ( is_user_logged_in() ) { 	?>
            
            <header style="height: 63px;">
            <h2 class="text-header" style="color:white;">New SMS alert</h2>
            <div style="margin-top:-18px;font-size:11px;">Percentage</div>
            </header>

            <?php if ($subs == 0) { ?>
                <div style="margin-top:-15px;margin-bottom:25px;">
                    <a href="subscription" data-navigo  class="blacklink hashLink">Subscribe</a><br>
                    <div style="color:red;">
                        Subscribe to enable SMS alerts
                    </div>
                </div>
            <?php } ?>

            <form method="post" id="form_new_alert_sms_per">

            <div class="text-label">Coin to watch:</div>
            <select class="selectcoin" name="id" id="id_sms_per" ></select>

            <div style="font-size:10px;margin-top:-6px;margin-bottom:15px;height:12px;" id="pricediv_sms_per"><div style="height:5px;"></div>Loading...</div>		  
            <input name="coin" id="coin_sms_per" type="hidden" value="Bitcoin" />
            <input name="symbol" id="symbol_sms_per" type="hidden" value="BTC" />
            <input name="price_set_btc" id="price_set_btc_sms_per" type="hidden" value="" />
            <input name="price_set_usd" id="price_set_usd_sms_per" type="hidden" value="" />
            <input name="price_set_eth" id="price_set_eth_sms_per" type="hidden" value="" />

            <div class="text-label" style="margin-top:20px;">Your phone number:<br><div style="font-size:10px;margin-bottom:2px;">It should start with the plus sign. <a href="https://support.twilio.com/hc/en-us/articles/223183008-Formatting-International-Phone-Numbers" class="blacklink" target="blank">More info</a></div></div>
            <input maxlength="99" class="input-general" id="phone_sms_per" name="phone" type="text" placeholder="e.g. +14155552671" required>

            <div class="text-label" style="margin-top:16px;">Alert when price increases by:</div>
            <input value="" class="input-per sms_per_input" id="plus_sms_per" name="plus_percent" maxlength="32" type="text" step="any" autocomplete="off">&nbsp;<span class="per">%</span>&nbsp;&nbsp;
            <select name="plus_change" id="plus_change_sms_per" class="input-per-change">
                <option value="from_now">from now</option>
                <option value="1h">in 1h. period</option>
                <option value="24h">in 24h. period</option>
            </select>
            <div id="div_plus_compared_sms_per" style="margin-left:63px;margin-top:-3px;margin-bottom:5px;">
            Compared to:&nbsp;
            <select name="plus_compared" id="plus_compared_sms_per" class="select-currency" style="height:24px;margin-top:3px;">
                <option value="USD">USD</option>
                <option value="BTC">BTC</option>
                <option value="ETH">ETH</option>
            </select>
            </div>
            <div id="plus_usd_sms_per" style="display:none;margin-left:107px;margin-top:-1px;margin-bottom:13px!important;">
            Compared to: USD
            </div>

            <div class="text-label">And/or when price decreases by:</div>
            <input value="" class="input-per sms_per_input" id="minus_sms_per"  name="minus_percent" maxlength="32" type="text" step="any" autocomplete="off">&nbsp;<span class="per">%</span>&nbsp;&nbsp;
            <select name="minus_change" id="minus_change_sms_per" class="input-per-change">
                <option value="from_now">from now</option>
                <option value="1h">in 1h. period</option>
                <option value="24h">in 24h. period</option>
            </select>
            <div id="div_minus_compared_sms_per" style="margin-left:63px;margin-top:-3px;margin-bottom:10px;">
            Compared to:&nbsp;
            <select name="minus_compared" id="minus_compared_sms_per" class="select-currency" style="height:24px;margin-top:3px;">
                <option value="USD">USD</option>
                <option value="BTC">BTC</option>
                <option value="ETH">ETH</option>
            </select>
            </div>
            <div id="minus_usd_sms_per" style="display:none;margin-left:107px;margin-top:-1px;margin-bottom:13px!important;">
            Compared to: USD
            </div>

            <div id="reserved_message_per"></div>

            <div style="height:5px;">&nbsp;</div>

            <div id="feedback_sms_per" style="color:red;padding-bottom:5px;" class="feedback content"></div>

            <input name="action" type="hidden" value="create_alert_sms_per" />
            <?php if (($subs == 0)) { ?>
                <input type="submit"  class="button-action-disabled" value="Create alert" disabled/>
                <div style="margin-top:-5px;"><a href="subscription" data-navigo class="blacklink hashLink" style="color:red;"><b>Subscribe</b></a></div>
            <?php }	else { ?>
            <input type="submit" id="create_alert_button_sms_per" class="submit action-button" style="margin-top:5px;" value="Create alert" />
            <div id="ajax_loader_sms_per" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif" alt="Loading spinner"></div>
            <?php } ?>
            
            <div style="height:5px;"></div>

            </form>
            

        <?php } else { ?>

            <header style="height: 63px;">
                <h2 class="text-header" style="color:white;">New SMS alert</h2>
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



<!-- Subscription -->
<div class="current-view" id="subscription">

    <?php if ( is_user_logged_in() ) { ?>
	<div class="container">

		<header>
            <h2 class="text-header" style="color:white;">Subscription</h2>
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

                <label>
                    <div class="checkbox-div" title="Enable/disable alert" style="padding-left:18px;">
                        <label>
                            <input id="agree" type="checkbox">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    I accept the <a href="https://coinwink.com/terms" target="_blank" class="blacklink">Terms and Conditions</a>
                </label>
        
                <div style="height:10px;"></div>

                <button id="cc-pay-button" onclick="stripeCheckout()" class="submit action-button" style="height:30px;font-size:14px;width:120px;padding-top:5px;padding-bottom:5px;">Pay $12.00</button>

                <div style="height: 31px;"></div>

                <div style="clear:both;text-align:center;font-size:12px;">
                    Subscription price: <b>12 USD per month</b>
                    <br>
                    <div style="height:5px;"></div>
                    Cancel at any time in your account settings
                    <div style="height: 24px;"></div>
                </div>
                
                Payment with a bank card
                <div style="height:5px;"></div>
                Powered by <a href="https://stripe.com" target="_blank" class="blacklink">Stripe</a>

                <div style="height:5px;"></div>

			<?php } ?>
			
			<?php if ($subs == 1 && $status == 'active') { ?>
				Unlimited alerts<br>
                Unlimited coins in portfolio<br>
                Unlimited coins in watchlist<br>
				SMS left this month: <b><?php echo $sms; ?></b>
				<br>
				<br>
				<br>
				<a href="<?php echo site_url(); ?>/account/" class="blacklink">Account</a><div style="height:10px;"></div>
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




<!-- Success messages -->

<div class="container current-view" id="created_alert" style="display:none;">
	<div class="text-label block-alert-created-msg">
		Your email alert has been created
		<br>
		<br>
		<a href="https://coinwink.com/account/" class="blacklink"><b>Sign up</b></a> for a free Coinwink account
		<div style="height:25px;"></div>
		<span id="newalertemail" class="blacklink">New crypto alert</span>
	</div>
</div>

<div class="container current-view" id="created_alert_acc_sms" style="display:none;">
	<div class="text-label block-alert-created-msg">Your SMS alert has been created
	<br><br>
	<a href="sms" data-navigo id="newalertaccsms" class="blacklink">New crypto alert</a>
	</div>
</div>

<div class="container current-view" id="created_alert_acc_email" style="display:none;">
	<div class="text-label block-alert-created-msg">Your email alert has been created
	<br><br>
	<span id="newalertaccemail" class="blacklink underline">New crypto alert</span>
	</div>
</div>

<div class="container current-view" id="created_alert_percent" style="display:none;">
    <div class="content block-alert-created-msg">
        <br>
        Your email percentage alert has been created
        <br>
        <br>
        <a href="https://coinwink.com/account/" class="blacklink"><b>Sign up</b></a> for a free Coinwink account
        <div style="height:25px;"></div>
        <a href="email-per" data-navigo id="newalertemailpercent" class="blacklink">New crypto alert</a></div>
    </div>
</div>

<div class="container current-view" id="created_alert_percent_acc" style="display:none;">
	<div class="content block-alert-created-msg"><div class="text-label">Your email percentage alert has been created</div>
	<br>
	<a href="email-per" data-navigo id="newalertemailpercentacc" class="blacklink">New crypto alert</a></div>
</div>

<div class="container current-view" id="created_alert_sms_per" style="display:none;">
	<div class="content block-alert-created-msg"><div class="text-label">Your sms percentage alert has been created</div>
	<br>
	<a href="sms-per" data-navigo id="newalertsmsper" class="blacklink">New crypto alert</a></div>
</div>


<!-- VIEW: MANAGE ALERTS -->
<div id="manage-alerts" class="current-view">

    <div class="container">

        <header>
            <h2 class="text-header" style="color:white;">Manage alerts</h2>
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
                <a href="<?php echo site_url(); ?>/account/">
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

                <div id="manage_alerts_acc_loader" style="margin-top:50px;margin-bottom:20px;">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif" alt="Loading spinner">
                </div>

                <div style="margin-top:9px;" id="manage_alerts_acc_feedback"></div>

            </div>

        <?php } ?>

    </div>

</div>


<!-- PAGE: About -->
<div class="current-view" id="about">

    <div class="container">

        <header>
        <h2 class="text-header" style="color:white;">About</h2>
        </header>

        <div style="padding:20px;padding-bottom:10px;text-align:left;overflow:visible;line-height:140%;">
    
            <h3 style="margin-top:-5px;">About Coinwink</h3>

            <p>
                Coinwink is a cryptocurrency price alerts, portfolio and watchlist app for Bitcoin, Ethereum, XRP, and other 2500+ crypto coins and tokens.
            </p>

            <p>
                Coinwink monitors crypto prices 24/7 and alerts you by e-mail or SMS when your defined conditions are met.
            </p>

            <p>
                Additional tools, such as Portfolio and Watchlist allows you to track your crypto holdings and favorites from different blockchains in one single place. These tools help you to be aware of the market situation with the minimum amount of time invested.
            </p>

            <p>
                Charts, social activity, links, news and other additional information about any coin or a token are always just one click away, provided by CoinMarketCap.
            </p>

            <p>
                Coinwink's mission is to help you to acquire good cryptocurrency management and trading habits, that provide a high level of personal freedom and positively impact your investing outcomes.
            </p>
            
            <h3>History</h3>

            <p>
                Coinwink was founded in 2016. It was stress-tested, optimized and hardened during the 2017's bull-run.
            </p>

            <p>
                Since the founding, Coinwink is in a long-term professional partnership with CoinMarketCap, which is the main data provider for the cryptocurrency industry.
            </p>

            <p>
                Coinwink has thousands of active users and starting from 2016, around half of a million delivered alerts.
            </p>

            <p>
                Coinwink is fast. Depending on the location, the app <a href="https://twitter.com/Coinwink/status/962879291741519872" target="_blank" class="blacklink">loads</a> in less than 1 second. A new visitor can create an alert in seconds, even without the account. Alerts are activated every 1-2 minutes.
            </p>

            <h3>Features</h3>

            <p>
                Coinwink is a <a href="https://twitter.com/Coinwink/status/1164883857419788289" target="_blank" class="blacklink">cross-platform</a> web app and works on any desktop or mobile device!
            </p>

            <p>
                Coinwink is a <a href="https://coinwink.com/privacy" target="_blank" class="blacklink">privacy-focused</a> service. The data of the user is never shared with third parties. Our users do not receive spam.
            </p>

            <p>
                Coinwink is <a href="https://github.com/giekaton/coinwink" target="_blank" class="blacklink">open-source</a>.
            </p>

            <p>
                Below, the main three Coinwink features are explained in detail.
            </p>

            <h1>Crypto Alerts</h1>

            <p>Coinwink started as a crypto alerting service, and this is what it does best.</p>

            <p>When you create a Coinwink crypto alert, you can then safely forget charts because Coinwink is now watching the price for you.</p>

            <p>The two delivery methods - SMS and email - is the universal, timeless and customizable combination that works for all possible scenarios.</p>

            <p>Coinwink is not relying on mobile notifications since they are inherently annoying, easy to miss and often decrease productivity.</p>

            <p>Coinwink crypto alerts are reliable, precise and fast.</p>

            <h1>Crypto Portfolio</h1>

            <p>Coinwink Portfolio allows you to keep track of your crypto holdings in a single structured dashboard.</p>

            <p>You can convert between different currencies, calculate return on investment (ROI), make notes for individual coins, and get new insights into the overall situation of the portfolio.</p>

            <p>Additionally, the portfolio has a multiple-coin alerts feature, introduced in 2019.</p>

            <p>You need to set multiple-coin crypto alerts only once. After that, there is no need to keep checking the portfolio until any coin in the portfolio passes the threshold, and the alert is received.</p>

            <h1>Crypto Watchlist</h1>

            <p>
                Coinwink Watchlist is a newly released feature. Its UI is consistent with the portfolio, which makes both tools easy to use.
            </p>
            
            <p>
                Apart from navigational similarities, the watchlist serves a completely different function than the portfolio.
            </p>
            
            <p>
                Watchlist is for the opportunity hunt. Also, it is a way to get out of the “filter bubble”.
            </p>

            <h3>Tips & Tricks</h3>

            <p>
                You can place the Coinwink app icon on your mobile device home screen. To do this, open coinwink.com in your mobile Chrome browser, click settings and then Add to Home Screen. You 
                will then be able to use Coinwink similarly as a mobile app. See a how-to <a href="https://twitter.com/Coinwink/status/1164883857419788289" class="blacklink" target="_blank">video</a>.
            </p>

            <p>
                Use (and bookmark) a custom url address to pre-select your coin, e.g. <a href="https://coinwink.com/eth" class="blacklink" target="_blank">https://coinwink.com/eth</a> (put a coin symbol after the slash).
            </p>

            <p>When creating an alert, click the USD value to switch between currencies.</p>

            <p>On the alerts management page, click an alert to enable/disable it.</p>

            <p>Track your activated alerts with Logs in your Manage Alerts window.</p>

            <p>For faster navigation in portfolio and watchlist, use keyboard shortcuts (Enter, Tab, Shift+Tab).</p>

            <p>Use the portfolio alerts feature to track multiple coins at once.</p>

            <p>
                Click on any coin logo, anywhere inside of the Coinwink app, to open that particular coin's CoinMarketCap page, where you can find additional data: charts, social news, stats, etc.
            </p>
            
            <h3>Philosophy</h3>

            <p>Coinwink is designed around the philosophy described in the book Trading in the Zone, written by Mark Douglas.</p>

            <p>It states that trading is successful when it's done from the carefree, winning mindset, which is based not on extensive analysis, but on cultivated intuition.</p>

            <p>After all, it only takes one wink.</p>

        </div>

    </div>

</div>


<div class="current-view" id="pricing">

    <div class="container">

        <header>
        <h2 class="text-header" style="color:white;">Pricing</h2>
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
                <br><br>
            <?php } ?>
            
            <?php if ( (is_user_logged_in()) && ($subs == 0)) { ?>
                <a href="subscription" data-navigo class="blacklink">Upgrade</a><br><br>
            <?php } else if ( (is_user_logged_in()) && ($subs == 1)) { ?>
                <a href="<?php echo site_url(); ?>/account/" class="blacklink">Account</a><br>
            <?php } else if ( !is_user_logged_in() ) { ?>
                <a href="<?php echo site_url(); ?>/account/#signup" class="blacklink">Create account</a>
                <br>
            <?php } ?>
            
            <div style="height:3px;"></div>

        </div>

    </div>

</div>


<div class="current-view" id="terms">

    <div class="container">

        <header>
        <h2 class="text-header" style="color:white;">Terms & Conditions</h2>
        </header>

        <div style="padding-left:20px; padding-right:20px;text-align:left;overflow:visible;">

            <h3>Terms & Conditions</h3>

            <p>Last updated: 19, Jan 2019</p>

            <h4>1. Terms</h4>
            
            <p>
            These Terms of Use constitute a legally binding agreement made between you, whether personally or on behalf of an entity (“you”) and Coinwink (“we,” “us” or “our”), 
            concerning your access to and use of the https://coinwink.com web app as well as any other media form, media channel, mobile website or mobile application related, 
            linked, or otherwise connected thereto (collectively, the “Site”). You agree that by accessing the Site, you have read, understood, and agreed to be bound by all of 
            these Terms of Use. IF YOU DO NOT AGREE WITH ALL OF THESE TERMS OF USE, THEN YOU ARE EXPRESSLY PROHIBITED FROM USING THE SITE AND YOU MUST DISCONTINUE USE IMMEDIATELY.
            </p>

            <p>
            Supplemental terms and conditions or documents that may be posted on the Site from time to time are hereby expressly incorporated herein by reference. We reserve 
            the right, in our sole discretion, to make changes or modifications to these Terms of Use at any time and for any reason. We will alert you about any changes by 
            updating the “Last updated” date of these Terms of Use, and you waive any right to receive specific notice of each such change. It is your responsibility to 
            periodically review these Terms of Use to stay informed of updates. You will be subject to, and will be deemed to have been made aware of and to have accepted, 
            the changes in any revised Terms of Use by your continued use of the Site after the date such revised Terms of Use are posted.
            </p>
            <p>
            The information provided on the Site is not intended for distribution to or use by any person or entity in any jurisdiction or country where such distribution or 
            use would be contrary to law or regulation or which would subject us to any registration requirement within such jurisdiction or country. Accordingly, those persons 
            who choose to access the Site from other locations do so on their own initiative and are solely responsible for compliance with local laws, if and to the extent 
            local laws are applicable.
            </p>

            <h4>2. Use License</h4>
            
            <p>
                Unless otherwise indicated, the Site is our proprietary property and all source code, databases, functionality, software, website designs, audio, video, text, 
                photographs, and graphics on the Site (collectively, the “Content”) and the trademarks, service marks, and logos contained therein (the “Marks”) are owned or 
                controlled by us or licensed to us, and are protected by copyright and trademark laws and various other intellectual property rights and unfair competition 
                laws of the United States, foreign jurisdictions, and international conventions. The Content and the Marks are provided on the Site “AS IS” for your information 
                and personal use only. Except as expressly provided in these Terms of Use, no part of the Site and no Content or Marks may be copied, reproduced, aggregated, republished, 
                uploaded, posted, publicly displayed, encoded, translated, transmitted, distributed, sold, licensed, or otherwise exploited for any commercial purpose whatsoever, 
                without our express prior written permission.
            </p>

            <p>
                Provided that you are eligible to use the Site, you are granted a limited license to access and use the Site and to download or print a copy of any portion of 
                the Content to which you have properly gained access solely for your personal, non-commercial use. We reserve all rights not expressly granted to you in and to the 
                Site, the Content and the Marks.
            </p>

            <p>You must be at least 18 years old to use Coinwink services.</p>

            <h4>3. Privacy</h4>

            <p>Please review our Privacy Policy, which also governs your visit to our website, to understand our practices.</p>

            <h4>4. Limitations</h4>

            <p>
                In no event shall Coinwink or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or 
                due to business interruption) arising out of the use or inability to use the materials on Coinwink's website, even if Coinwink or a 
                Coinwink's authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not 
                allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.
            </p>

            <h4>5. Subscription</h4>

            <p>Your monthly subscription will continue on a month by month basis unless you cancel your subscription.</p>
            
            <p>
                We can cancel or suspend your subscription at any time if, in our sole discretion, you have committed a material or persistent breach of these Terms 
                (such as a failure to pay fees when due) or any other terms applying to your use of the Coinwink.
            </p>

            <p>
                You are entitled to cancel your subscription with Coinwink at any time. You can cancel your subscription by going to your account settings and clicking 
                "Cancel Subscription". If you cancel your subscription before the end of your current paid up month, your cancellation will take effect at the end of that 
                billing cycle. Unless required by law, we will not provide refunds in connection with the Coinwink subscription.
            </p>

            <h4>6. Refunds</h4>

            <p>
                Given the nature of the digital services provided by Coinwink and considering the several costs involved in creating and maintaining the service, 
                we do not offer refunds.
            </p>

            <p>
                In case our main service - creating and sending cryptocurrency alerts - was not fulfilled and it was a direct cause of Coinwink's application malfunctioning, 
                we guarantee that all steps will be taken to ensure that the service will be fulfilled as soon as possible without any additional costs involved for our users.
            </p>
            
            <p>
                For SMS messages broadcasting, Coinwink uses Twilio SMS messaging API. Twilio provides a worldwide coverage for SMS messages delivery. Neverthless, some countries 
                (e.g. India) have some specific restrictions for inboud SMS messages. If you haven't received your SMS message, please contact us and we will resolve the matter 
                as soon as possible.
            </p>

            <h4>7. Accuracy of materials</h4>

            <p>
                The materials appearing on Coinwink's website could include technical, typographical, or photographic errors. Coinwink does not warrant 
                that any of the materials on its website are accurate, complete or current. Coinwink may make changes to the materials contained on its website 
                at any time without notice. However Coinwink does not make any commitment to update the materials.
            </p>

            <p>
                For cryptocurrency price information, Coinwink uses Coinmarketcap.com professional API where the price of a particular coin is determined averagely, based on 
                different exchanges where that coin is traded. Coinwink should not be held responsible for the possible malfunction of the Coinmarketcap.com API.
            </p>

            <h4>8. Links</h4>

            <p>
                Coinwink has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion 
                of any link does not imply endorsement by Coinwink of the site. Use of any such linked website is at the user's own risk.
            </p>

            <h4>9. Modifications</h4>

            <p>
                Coinwink may revise these terms of service for its website at any time without notice. By using this website you are agreeing to be bound by 
                the then current version of these terms of service.
            </p>

            <h4>10. Governing Law</h4>

            <p>
                These terms and conditions are governed by and construed in accordance with the laws of Lithuania and you irrevocably submit to the exclusive jurisdiction 
                of the courts in that State or location.
            </p>

        </div>

    </div>

</div>


<div class="current-view" id="privacy">

    <div class="container">

        <header>
        <h2 class="text-header" style="color:white;">Privacy Policy</h2>
        </header>
        
        <div style="padding-left:20px; padding-right:20px;text-align:left;overflow:visible;">
            
            <h3>Privacy Policy</h3>

            <p>Last updated: 17, Aug 2019</p>

            <p>Coinwink operates https://coinwink.com, which is a digital service, accessible as a web-app (the "App").</p>

            <p>This page informs you of our policies regarding the collection, use and disclosure of Personal Information we receive from users of the App.</p>

            <p>Coinwink is a privacy-focused service. The Personal Information of the user is never shared with third parties. If the user deletes the account, all Personal Information related to the user is also deleted.</p>

            <p>If at some point Coinwink, as a business, is sold, the transfer of the users' Personal Information to the new business owner will be done under similar "privacy-focused" conditions.</p>

            <p>
                We use your Personal Information only for providing and improving the App. By using the App, you agree to the collection and use of information in accordance 
                with this policy.
            </p>

            <h4>Information Collection and Use</h4>

            <p>For all visitors of the App we collect standard Cookies and Log Data, as described in further sections.</p>

            <p>
                When creating e-mail alerts without the account, we collect the e-mail address to which we send the alert. If the user deletes the alert, the e-mail
                associated with that alert is also deleted from our database.
            </p>

            <p>
                When using the App with the account, we collect email address to authenticate the user. To send e-mail and SMS alerts we also collect e-mail addresses 
                and phone numbers. This data is automatically deleted from our database when user deletes alerts associated with it.
            </p>

            <p>
                Data submitted by the user for the Portfolio is saved in Coinwink's database. The portfolio data is never shared with third-parties. The data is deleted automatically
                from the database, as soon as the user deletes it from the Portfolio.
            </p>

            <p>
                For the App to be more user-friendly, we also collect the last e-mail address and phone number used with the account, and later pre-fill this information 
                in the New Alert creation form.
            </p>

            <p>When the user deletes the account, all the data associated with the user is automatically deleted from our database.</p>

            <p>
                For payment processing, we use Stripe. When processing your payment, we never see your personal or payment details,
                as they are only collected by the Stripe service. For more details, please refer to the 
                <a href="https://stripe.com/privacy" class="blacklink" target="_blank">Stripe privacy policy</a>.
            </p>

            <h4>Log Data</h4>

            <p>Like many App developers, we collect information that your browser sends whenever you visit our App ("Log Data").</p>

            <p>
                This Log Data may include information such as your computer's Internet Protocol ("IP") address, browser type, browser version, the pages of our App that you visit, the time and date of your visit, the time spent on those pages and other statistics.
            </p>

            <p>
                In addition, we may use third party services such as Google Analytics that collect, monitor and analyze this in order to provide user behavior insights and statistics 
                for our App.
            </p>

            <p>Starting from 2019, we record logs of all delivered e-mail, SMS and portfolio alerts. Every user can view his or her alerts history under "Manage Alerts" section. If the user deletes the account, logs are also deleted.</p>

            <h4>Cookies</h4>

            <p>
                Cookies are files with small amount of data, which may include an anonymous unique identifier. Cookies are sent to your browser from a web-app and stored on your 
                computer's hard drive.
            </p>
            
            <p>Like many web-apps, we use "cookies" to collect information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.</p>

            <h4>Security</h4>

            <p>
                The security of your Personal Information is important to us, but remember that no method of transmission over the Internet, or method of electronic storage, 
                is 100% secure. While we strive to use commercially acceptable means to protect your Personal Information, we cannot guarantee its absolute security.
            </p>

            <h4>Changes to This Privacy Policy</h4>

            <p>
                This Privacy Policy is effective as of 17, Aug 2019 and will remain in effect except with respect to any changes in its provisions in the future, which will 
                be in effect immediately after being posted on this page.
            </p>
            
            <p>
                We reserve the right to update or change our Privacy Policy at any time and you should check this Privacy Policy periodically. Your continued use of the Service 
                after we post any modifications to the Privacy Policy on this page will constitute your acknowledgment of the modifications and your consent to abide and be bound 
                by the modified Privacy Policy.
            </p>
            
        </div>

    </div>

</div>


<div class="current-view" id="press">

    <div class="container">

        <header>
        <h2 class="text-header" style="color:white;">PRESS KIT</h2>
        </header>
        
        <div style="padding-left:20px; padding-right:20px;text-align:left;overflow:visible;">

            <h3 style="padding-top:10px;">Spread the message</h3>

            <p>We invite you to create new content about Coinwink.</p>
            
            <p>For original and valuable content, we offer a free upgrade to Premium. For more info, contact us.</>

            <p>
            <a class="blacklink" href="&#x6d;&#x61;&#105;l&#x74;&#x6f;&#58;&#105;n&#x66;&#x6f;&#64;&#99;o&#x69;&#x6e;&#119;in&#x6b;&#x2e;&#99;o&#x6d;">&#x69;&#x6e;&#x66;&#x6f;&#x40;&#x63;&#x6f;&#x69;&#x6e;&#x77;&#x69;&#x6e;&#x6b;&#x2e;&#x63;&#x6f;&#x6d;</a></p>

            <h3 style="padding-top:10px;">Logo and screenshots</h3>

            <p>
                <a href="https://coinwink.com/brand/" target="_blank" class="blacklink">https://coinwink.com/brand/</a>
            </p>

            <h3 style="padding-top:30px;">Why Coinwink</h3>

            <div style="padding-left:15px;">
                <ul>
                    <li>Email and SMS crypto alerts with a global reach (Twilio API)</li>
                    <li style="margin-top:8px;">Based on the industry-standard: CoinMarketCap</li>
                    <li style="margin-top:8px;">Provides crypto market overview with minimum time invested</li>
                    <li style="margin-top:8px;">Saves time by automating mechanical tasks</li>
                    <li style="margin-top:8px;">Privacy-focused and open-source</li>
                    <li style="margin-top:8px;">Simple, user-friendly, fast and reliable (since 2016)</li>
                    <li style="margin-top:8px;">Crypto portfolio in multiple currencies, with ROI calc., notes, and multi-coin alerts</li>
                    <li style="margin-top:8px;">Cryptocurrency watchlist</li>
                    <li style="margin-top:8px;">Supported fiat currencies: USD, EUR, GBP, AUD, CAD, MXN, BRL, SGD, JPY</li>
                    <li style="margin-top:8px;">A cross-platform web app that works on any device and requires no install</li>
                    <li style="margin-top:8px;">With the mission to save the user's time, improve the quality of life and crypto trading outcomes</li>
                </ul>
            </div>

            <div style="height:35px;"></div>
        
        </div>

    </div>

</div>


<div class="current-view" id="contacts">

    <div class="container">

        <header>
        <h2 class="text-header" style="color:white;">Contacts</h2>
        </header>

        <div style="padding-left:20px; padding-right:20px;text-align:left;overflow:visible;">
            
            <h3>Contact us by e-mail</h3>

            <p><a class="blacklink" href="&#x6d;&#x61;&#105;l&#x74;&#x6f;&#58;&#105;n&#x66;&#x6f;&#64;&#99;o&#x69;&#x6e;&#119;in&#x6b;&#x2e;&#99;o&#x6d;">&#x69;&#x6e;&#x66;&#x6f;&#x40;&#x63;&#x6f;&#x69;&#x6e;&#x77;&#x69;&#x6e;&#x6b;&#x2e;&#x63;&#x6f;&#x6d;</a></p>

            <div style="height:10px;"></div>

            <p>Before reporting a missing alert, please double check your alert. Usually, there is a mistake in the alert (e.g. too many zeros).</p>
            
            <p>Also, please note that Coinwink uses CoinMarketCap as the source of truth for the cryptocurrency prices. The price on CoinMarketCap can be slightly different than a price on an individual exchange. When using Coinwink, always refer to the data on <a href="https://coinmarketcap.com" target="_blank" class="blacklink">CoinMarketCap</a>.</p>
            
            <div style="height:10px;"></div>

            <p>We listen to your requests for new features, ideas, and suggestions on how Coinwink can serve you better. Do not hesitate to send us your feedback!</p>

            <div style="height:10px;"></div>

            <h3 style="margin-bottom:25px;">Follow and fork Coinwink</h3>

            <div style="display:inline-block;width:26px;margin-right:10px;">
                <a href="https://twitter.com/coinwink" title="Twitter" target="_blank">
                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 124.11 124.11"><title>Twitter</title><path d="M199.05,335a62.05,62.05,0,1,0,62.05,62.05A62.05,62.05,0,0,0,199.05,335h0Zm46.81,39.49a35.82,35.82,0,0,1-10.3,2.82,18,18,0,0,0,7.88-9.92,35.93,35.93,0,0,1-11.39,4.35,17.94,17.94,0,0,0-30.55,16.35,50.9,50.9,0,0,1-37-18.74,18,18,0,0,0,5.55,23.94,17.87,17.87,0,0,1-8.12-2.24c0,0.07,0,.15,0,0.22a18,18,0,0,0,14.38,17.58,17.95,17.95,0,0,1-8.09.31A18,18,0,0,0,185,421.62,36.16,36.16,0,0,1,158.48,429,50.74,50.74,0,0,0,186,437.1c33,0,51-27.32,51-51q0-1.17-.05-2.32a36.45,36.45,0,0,0,8.94-9.28h0Z" transform="translate(-137 -335)" style="fill:#848688;fill-rule:evenodd"/></svg>
                </a>
            </div>

            <div style="display:inline-block;width:26px;">
                <a href="https://github.com/giekaton/coinwink" title="GitHub" target="_blank">
                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 124.11 124.11"><title>GitHub</title><path d="M411.45,335a62.06,62.06,0,0,0-19.61,120.94c3.1,0.57,4.24-1.35,4.24-3,0-1.47-.05-5.38-0.08-10.55-17.26,3.75-20.9-8.32-20.9-8.32-2.82-7.17-6.89-9.08-6.89-9.08-5.63-3.85.43-3.77,0.43-3.77,6.23,0.44,9.51,6.39,9.51,6.39,5.53,9.48,14.52,6.74,18.06,5.16,0.56-4,2.17-6.74,3.94-8.3-13.78-1.57-28.27-6.89-28.27-30.67a24,24,0,0,1,6.39-16.65c-0.64-1.57-2.77-7.88.61-16.42,0,0,5.21-1.67,17.06,6.36a58.79,58.79,0,0,1,31.07,0c11.85-8,17-6.36,17-6.36,3.39,8.54,1.26,14.85.62,16.42A23.94,23.94,0,0,1,451,393.81c0,23.84-14.51,29.08-28.33,30.62,2.22,1.91,4.21,5.7,4.21,11.49,0,8.29-.08,15-0.08,17,0,1.66,1.12,3.59,4.27,3A62.06,62.06,0,0,0,411.45,335h0Z" transform="translate(-349.4 -335)" style="fill:#848688;fill-rule:evenodd"/></svg>
                </a>
            </div>

            <div style="height:30px;"></div>

        </div>
    
    </div>

</div>



<!-- 
<div style="margin-top:5px;margin-bottom:13px;text-align:center;">
	<a target="_blank" href="https://twitter.com/Coinwink/status/1025407436981657600" style="color:#ff6464!important;;tex-decoration:underline;">
		Info: A bug on CMC has triggered several alerts...
	</a>
</div>
-->


<?php echo do_shortcode('[footer_shortcode]'); ?>


<!-- Credit card SVG icons -->
<svg style="display: none">
  <defs>
  
	<symbol id="portfolio-profitloss" viewBox="0 0 134.69 109.17">
        <title>Profit/Loss</title>
        <path style="fill:#8A8A8A" d="M165.63,400.74h26a6.62,6.62,0,0,1,4.69,1.94v0a6.6,6.6,0,0,1,1.94,4.67v37.17a6.64,6.64,0,0,1-6.63,6.63h-26a6.6,6.6,0,0,1-4.67-1.94h0a6.61,6.61,0,0,1-1.95-4.69V407.37a6.6,6.6,0,0,1,1.95-4.67l0,0a6.6,6.6,0,0,1,4.67-1.94h0Zm95.4-41.4h26a6.61,6.61,0,0,1,4.69,1.94v0a6.6,6.6,0,0,1,1.94,4.67v78.57a6.64,6.64,0,0,1-6.63,6.63H261a6.6,6.6,0,0,1-4.67-1.94h0a6.61,6.61,0,0,1-1.94-4.69V366a6.6,6.6,0,0,1,1.94-4.67l0,0a6.6,6.6,0,0,1,4.67-1.94h0Zm24.66,8H262.4v75.83h23.29V367.34h0ZM213.33,342h26a6.61,6.61,0,0,1,4.69,1.94v0a6.6,6.6,0,0,1,1.94,4.67v95.91a6.64,6.64,0,0,1-6.63,6.63h-26a6.6,6.6,0,0,1-4.67-1.94h0a6.61,6.61,0,0,1-1.94-4.69V348.63a6.6,6.6,0,0,1,1.94-4.67l0,0a6.6,6.6,0,0,1,4.67-1.94h0ZM238,350H214.7v93.17H238V350h0Zm-47.7,58.74H167v34.43h23.29V408.74h0Z" 
        transform="translate(-159 -342)"/>
    </symbol>

	<symbol id="portfolio-note" viewBox="0 0 134.69 109.17">
        <title>Note</title>
        <path style="fill:#8A8A8A" d="M364.79,421.29a4,4,0,1,1,0-8H402a4,4,0,0,1,0,8H364.79ZM352.71,342h89.92a12.53,12.53,0,0,1,12.51,12.51v84.16a12.53,12.53,0,0,1-12.51,12.51H352.71a12.53,12.53,0,0,1-12.51-12.51V354.51A12.53,12.53,0,0,1,352.71,342h0Zm89.92,8H352.71a4.53,4.53,0,0,0-4.51,4.51v84.16a4.53,4.53,0,0,0,4.51,4.51h89.92a4.53,4.53,0,0,0,4.51-4.51V354.51a4.53,4.53,0,0,0-4.51-4.51h0Zm-77.83,29.89a4,4,0,1,1,0-8h65.75a4,4,0,1,1,0,8H364.79Zm0,20.7a4,4,0,1,1,0-8h65.75a4,4,0,1,1,0,8H364.79Z" 
        transform="translate(-340.2 -342)"/>
    </symbol>

    <symbol id="svg-alert-delete" viewBox="0 0 12.45 12.45">
        <title>Delete</title>
        <path d="M299.6,390.6l11.25,11.25m0-11.25L299.6,401.85" transform="translate(-299 -390)" 
        style="fill:none;stroke:#888888;stroke-linecap:round;stroke-linejoin:round;stroke-width:1px"/>
    </symbol>

  </defs>
</svg>


<!-- JS/CSS -->

<script>
	var jqueryarray = <?php echo json_encode($CMCdata); ?>;
	var ajax_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	var security_url = "&security=<?php echo $ajax_nonce; ?>";
</script>


<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/select2.css?v=302" rel="stylesheet" />

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-3.3.1.min.js"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/select2.min.js?v=400"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/select2_optimization.js?v=400"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/navigo.min.js?v=400"></script>


<script>var subs = "<?php echo($subs); ?>";</script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink_portfolio.js?v=401"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink_watchlist.js?v=404"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink.js?v=409"></script>


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

<script>
    function exampleAlerts() {
        jQuery('body').prepend('<div class="popup" onclick="jQuery(\'.popup\').remove()" style="z-index: 99999;cursor: pointer;"><div style="z-index: 99999;display:grid;background-color: rgba(0,0,0,.8);overflow-x: hidden;-webkit-box-sizing: content-box;box-sizing: content-box;-webkit-box-align: center;-ms-flex-align: center;align-items: center;height:100%;width:100%;position:fixed;"><img src="https://coinwink.com/brand/files/screenshots/02-email-crypto-alerts.png?v=001" style="max-width: 90%;max-height: 85%;display: block;border:4px solid white;position: absolute;left: 0;top: 0;bottom: 0;right: 0;margin: auto;position: fixed;z-index:99999;min-width:100px;min-height:100px;background-color:#4f585b;background-image:url(https://coinwink.com/wp-content/themes/coinwink-theme/img/ajax_loader_dark.gif);background-repeat:no-repeat;background-position:center;"></div></div>');
    }

    function examplePortfolio() {
        jQuery('body').prepend('<div class="popup" onclick="jQuery(\'.popup\').remove()" style="z-index: 99999;cursor: pointer;"><div style="z-index: 99999;display:grid;background-color: rgba(0,0,0,.8);overflow-x: hidden;-webkit-box-sizing: content-box;box-sizing: content-box;-webkit-box-align: center;-ms-flex-align: center;align-items: center;height:100%;width:100%;position:fixed;"><img src="https://coinwink.com/brand/files/screenshots/04-portfolio.png" style="max-width: 90%;max-height: 85%;display: block;border:4px solid white;position: absolute;left: 0;top: 0;bottom: 0;right: 0;margin: auto;position: fixed;z-index:99999;min-width:100px;min-height:100px;background-color:#4f585b;background-image:url(https://coinwink.com/wp-content/themes/coinwink-theme/img/ajax_loader_dark.gif);background-repeat:no-repeat;background-position:center;"></div></div>');
    }

    function exampleWatchlist() {
        jQuery('body').prepend('<div class="popup" onclick="jQuery(\'.popup\').remove()" style="z-index: 99999;cursor: pointer;"><div style="z-index: 99999;display:grid;background-color: rgba(0,0,0,.8);overflow-x: hidden;-webkit-box-sizing: content-box;box-sizing: content-box;-webkit-box-align: center;-ms-flex-align: center;align-items: center;height:100%;width:100%;position:fixed;"><img src="https://coinwink.com/brand/files/screenshots/05-watchlist.png?v=001" style="max-width: 90%;max-height: 85%;display: block;border:4px solid white;position: absolute;left: 0;top: 0;bottom: 0;right: 0;margin: auto;position: fixed;z-index:99999;min-width:100px;min-height:100px;background-color:#4f585b;background-image:url(https://coinwink.com/wp-content/themes/coinwink-theme/img/ajax_loader_dark.gif);background-repeat:no-repeat;background-position:center;"></div></div>');
    }
</script>


</body>
</html>