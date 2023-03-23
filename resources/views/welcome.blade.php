<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ $meta['title'] }}</title>
        <meta name="description" content="{{ $meta['description'] }}"/>

        <meta property="og:title" content="{{ $meta['soc_title'] }}">
        <meta property="og:description" content="{{ $meta['soc_description'] }}">
        <meta property="og:site_name" content="Coinwink">
        <meta property="og:image" content="{{env('APP_URL')}}/img/{{ $meta['image'] }}">
        <meta property="og:image:width" content="{{ $meta['image_w'] }}" />
        <meta property="og:image:height" content="{{ $meta['image_h'] }}" />
        <meta property="og:url" content="{{Request::url()}}">

        <meta name="twitter:title" content="{{ $meta['soc_title'] }}">
        <meta name="twitter:description" content="{{ $meta['soc_description'] }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@coinwink">
        <meta name="twitter:creator" content="@coinwink">
        <meta name="twitter:image" content="{{env('APP_URL')}}/img/{{ $meta['image'] }}">
        <meta name="twitter:image:alt" content="{{ $meta['soc_title'] }}">
        <meta name="twitter:domain" content="{{env('APP_URL')}}">
        
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{env('APP_URL')}}/img/favicon/apple-touch-icon.png?v=2bBgz68qL">
        <link rel="icon" type="image/png" sizes="32x32" href="{{env('APP_URL')}}/img/favicon/favicon-32x32.png?v=2bBgz68qL">
        <link rel="icon" type="image/png" sizes="16x16" href="{{env('APP_URL')}}/img/favicon/favicon-16x16.png?v=2bBgz68qL">
        <link rel="shortcut icon" href="{{env('APP_URL')}}/img/favicon/favicon.ico?v=2bBgz68qL">
        <meta name="apple-mobile-web-app-title" content="Coinwink">
        <meta name="application-name" content="Coinwink">

        <?php
            //
            // THEME - PART 1/3
            //
            $theme = '';
            if (isset($settings)) {
                if ($settings->theme == 'matrix') {
                    $theme = 'matrix';
                    ?>  
                        <!-- MATRIX WEBAPP THEME -->
                        <link rel="manifest" href="{{env('APP_URL')}}/img/favicon/site.webmanifest-matrix?v=2bBgz68qL">
                        <meta name="msapplication-TileColor" content="#000">
                        <meta name="theme-color" content="#000">
                        <link rel="mask-icon" href="{{env('APP_URL')}}/img/favicon/safari-pinned-tab.svg?v=2bBgz68qL" color="#000">
                        <meta name="msapplication-config" content="{{env('APP_URL')}}/img/favicon/browserconfig-matrix.xml?v=2bBgz68qL">
                    <?php
                }
                else if ($settings->theme == 'metaverse') {
                    $theme = 'metaverse';
                    ?>  
                        <!-- METAVERSE WEBAPP THEME -->
                        <link rel="manifest" href="{{env('APP_URL')}}/img/favicon/site.webmanifest-metaverse?v=2bBgz68qL">
                        <meta name="msapplication-TileColor" content="#060512">
                        <meta name="theme-color" content="#060512">
                        <link rel="mask-icon" href="{{env('APP_URL')}}/img/favicon/safari-pinned-tab.svg?v=2bBgz68qL" color="#060512">
                        <meta name="msapplication-config" content="{{env('APP_URL')}}/img/favicon/browserconfig-metaverse.xml?v=2bBgz68qL">
                    <?php
                }
                else {
                    $theme = 'classic';
                }
            }
            else {
                $theme = 'classic';
            }
            
            if ($theme == 'classic') { ?>
                <!-- CLASSIC WEBAPP THEME -->
                <link rel="manifest" href="{{env('APP_URL')}}/img/favicon/site.webmanifest?v=2bBgz68q">
                <meta name="msapplication-TileColor" content="#4f585b">
                <meta name="theme-color" content="#4f585b">
                <link rel="mask-icon" href="{{env('APP_URL')}}/img/favicon/safari-pinned-tab.svg?v=2bBgz68qL" color="#4f585b">
                <meta name="msapplication-config" content="{{env('APP_URL')}}/img/favicon/browserconfig.xml?v=2bBgz68qL">
        <?php } ?>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{asset(mix('css/app.css'), true)}}">

        <!-- Data from the backend -->
        <?php if (isset($cmc)) { ?>
            <script>
                var cw_cmc = <?php echo($cmc) ?>;
            </script>
        <?php } else { ?>
            <script>
                var cw_cmc = null;
            </script>
        <?php } ?>
        <script>
            var updateStoreCMC = null;

            if (!cw_cmc) {
                var http = new XMLHttpRequest();
                http.responseType = 'json';
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var url = '/api/app_data';
                http.open('get', url, true);

                http.onreadystatechange = function() {
                    if(http.readyState == 4 && http.status == 200) {
                        cw_cmc = http.response.cmc;
                        console.log("App data loaded!")

                        // if updateStoreCMC is declared in Vue, then update, else try again in 0.1 sec
                        if (updateStoreCMC) {
                            updateStoreCMC();
                        }
                        else {
                            updateRetry();
                        }
                        function updateRetry() {
                            setTimeout(() => {
                                if (updateStoreCMC) {
                                    updateStoreCMC();
                                }
                                else {
                                    console.log("Trying again...")
                                    updateRetry();
                                }
                            }, 100);
                        }
                    }
                }
                http.send();
            }
        </script>

        <script>
            var rates = {}
            rates['eur'] = "<?php echo($rates[0]->EUR); ?>";
            rates['gbp'] = "<?php echo($rates[0]->GBP); ?>";
            rates['cad'] = "<?php echo($rates[0]->CAD); ?>";
            rates['aud'] = "<?php echo($rates[0]->AUD); ?>";
            rates['brl'] = "<?php echo($rates[0]->BRL); ?>";
            rates['mxn'] = "<?php echo($rates[0]->MXN); ?>";
            rates['jpy'] = "<?php echo($rates[0]->JPY); ?>";
            rates['sgd'] = "<?php echo($rates[0]->SGD); ?>";

            var userLoggedIn = "<?php echo(Auth::check()); ?>";
            var userVerified = null;

            var subs = 0;
            var coinwinkEnv = 'live';
            var cw_theme = '';

            var switchLocation = 'email';
            var cw_tab = null;

            var initialValue = '1';
        </script>

        <?php 
            if (Route::currentRouteName() == 'individual_coin') {
                $theme = 'metaverse';
            }
            if (Auth::check()) {
                $user = Auth::user();
                $id_user = $user->id;

                // User role
                $user_role = 'free';
                if ($id_user == '24301' || $id_user == '19762' || $id_user == '7929') {
                    $user_role = 'special';
                }

                $t_s = $settings->t_s;
                $t_i = $settings->t_i;
                $cur_p = $settings->cur_p;
                if ($cur_p == "") { $cur_p = "USD"; }
                $cur_w = $settings->cur_w;
                if ($cur_w == "") { $cur_w = "USD"; }
                $conf_w = $settings->conf_w;
                if ($conf_w == "") { $conf_w = "price"; }
                $cur_main = $settings->cur_main;
                if ($cur_main == "") { $cur_main = "USD"; }
                $conv_exp = $settings->conv_exp;
                
                $unique_id = $settings->unique_id;
                $legac = $settings->legac;
                $settings_subs = $settings->subs;
                $sms = $settings->sms;

                $user_email = auth()->user()->email;

                $dest_email = $settings->email;
                $dest_phone_nr = $settings->phone_nr;
                $tg_user = $settings->tg_user;

                // Last opened tab
                $cw_tab = $settings->cw_tab;

                // Allow 10 coins in watchlist for promoted users
                $limit_early = false;
                if ($id_user == 24301 || $id_user == 19762 || $id_user == 7929) {
                    $limit_early = true;
                }
                
                if (sizeof($subs) > 0) {
                    // var_dump($subs);
                    // exit;
                    $status = $subs[0]->status;
                    $subs_plan = $subs[0]->plan;
                    $months = $subs[0]->months;
                    $date_renewed = $subs[0]->date_renewed;
                    $date_end = $subs[0]->date_end;
                    $date_end = new DateTime($date_end);
                    $date_end = $date_end->format('Y-m-d');
                }
            }
        ?>

        <?php if (Auth::check()) { ?>
            <script>
                userVerified = "<?php echo($user->hasVerifiedEmail()); ?>";
                userRole = "<?php echo($user_role); ?>";

                var cur_p = "<?php echo($cur_p); ?>";
                var cur_pLower = cur_p.toLowerCase();
                var cur_w = "<?php echo($cur_w); ?>";
                var conf_w = "<?php echo($conf_w); ?>";
                var cur_main = "<?php echo($cur_main); ?>";
                var conv_exp = "<?php echo($conv_exp); ?>";

                var user_email = "<?php echo($user_email); ?>";
                var id_user = "<?php echo($id_user); ?>";

                var dest_email = "<?php echo($dest_email); ?>";
                var dest_phone_nr = "<?php echo($dest_phone_nr); ?>";
                var tg_user = "<?php echo($tg_user); ?>";

                // Last opened tab
                var cw_tab = "<?php echo($cw_tab); ?>";
                if (cw_tab != '') {
                    switchLocation = cw_tab;
                }
                // switchLocation = 'email';
                // console.log(switchLocation)

                var months = null;

                var limitEarly = "<?php echo($limit_early); ?>";

                var t_s = "<?php echo($t_s); ?>";
                var t_i = "<?php echo($t_i); ?>";
                var cw_theme = '<?php echo($theme) ?>';
                if (cw_theme == '') {
                    cw_theme = 'classic';
                }
            </script>
        <?php } else { ?>
            <script>
                var cw_theme = '<?php echo($theme) ?>';
                switchLocation = 'email';
            </script>
        <?php } ?>

        <?php if (isset($status)) { ?>
            <script>
                var status = "<?php echo($status); ?>";
                var subs = "<?php echo($settings_subs); ?>";
                var subs_plan = "<?php echo($subs_plan); ?>";
                var months = "<?php echo($months); ?>";
                var date_renewed = "<?php echo($date_renewed); ?>";
                var date_end = "<?php echo($date_end); ?>";
                var sms = "<?php echo($sms); ?>";
            </script>
        <?php } ?>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-57930548-9"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-57930548-9');
        </script>

    </head>
    <body>
        
        <!-- **************** -->
        <!-- THEME - PART 2/3 -->
        <!-- **************** -->

        <!-- LOGOS FOR DIFFERENT THEMES -->
        <svg style="display: none;">
            <defs>
                <?php if ($theme == 'matrix') { ?>
                    <!-- Matrix -->
                    <symbol id="coinwink-logo" viewBox="0 0 61.36 61.36">
                        <title>Coinwink Matrix Logo</title><path d="M30.68,0A30.68,30.68,0,1,1,0,30.68,30.69,30.69,0,0,1,30.68,0Z" class="logo-matrix-base" /><path d="M30.68,6.41a24.29,24.29,0,1,1-17.16,7.11A24.27,24.27,0,0,1,30.68,6.41ZM52.76,30.68A22.08,22.08,0,1,0,30.68,52.76,22.09,22.09,0,0,0,52.76,30.68Z"/><path d="M41.05,36.22a10.37,10.37,0,0,1-20.74,0H23a7.73,7.73,0,1,0,15.46,0Z"/><path d="M28.2,23c-.49,2.16-2.85,4.38-4.51,5.41a14.61,14.61,0,0,1-13.83.86C7.62,28-2.49,19.26,3.69,17.61c4.72-1.27,13.78-.41,18.6.69l1,.25a9.28,9.28,0,0,1,4.25,2.06h6.36a9.27,9.27,0,0,1,4.26-2.06l1-.25c4.83-1.11,13.88-2,18.6-.69,6.18,1.65-3.93,10.4-6.17,11.67a14.61,14.61,0,0,1-13.83-.86c-1.63-1-3.93-3.17-4.49-5.3a5,5,0,0,0-2.58-.73h0A5,5,0,0,0,28.2,23Z" style="fill-rule:evenodd"/>
                    </symbol>
                <?php } else if ($theme == 'metaverse') { ?>
                    <!-- Matrix -->
                    <symbol id="coinwink-logo" viewBox="0 0 862.96 862.95" width="100%" height="100%" fill-rule="evenodd" class="logo-metaverse">
                        <title>Coinwink Metaverse Logo</title><g id="Layer_x0020_1"><metadata id="CorelCorpID_0Corel-Layer"></metadata><path fill="none" stroke="#5BD9D9" stroke-width="27.78" stroke-miterlimit="10" d="M431.48 13.89c230.62,0 417.59,186.96 417.59,417.59 0,230.62 -186.96,417.58 -417.59,417.58 -230.63,0 -417.59,-186.96 -417.59,-417.58 0,-230.63 186.96,-417.59 417.59,-417.59z"></path><path fill="#5BD9D9" fill-rule="nonzero" d="M574.52 507.93c0,39.5 -16.02,75.26 -41.9,101.15 -25.88,25.88 -61.65,41.89 -101.14,41.89 -39.5,0 -75.26,-16.01 -101.15,-41.89 -25.89,-25.89 -41.9,-61.65 -41.9,-101.15l36.37 0c0,29.45 11.94,56.13 31.24,75.43 19.3,19.3 45.97,31.24 75.43,31.24 29.46,0 56.13,-11.94 75.43,-31.24 19.3,-19.3 31.24,-45.98 31.24,-75.43l36.37 0zm-18.19 -174.2c19.66,0 35.6,15.94 35.6,35.61 0,19.67 -15.94,35.6 -35.6,35.6 -19.67,0 -35.6,-15.94 -35.6,-35.6 0,-19.67 15.94,-35.61 35.6,-35.61zm-318.72 51.24l57.2 -48.09 11.79 -9.91 11.8 9.91 57.2 48.09 -23.59 28.04 -45.41 -38.17 -45.41 38.17 -23.59 -28.04z"></path><path fill="#5BD9D9" fill-rule="nonzero" d="M431.48 96.53c184.99,0 334.95,149.96 334.95,334.95 0,184.99 -149.96,334.95 -334.95,334.95 -92.49,0 -176.23,-37.49 -236.85,-98.1 -60.61,-60.61 -98.1,-144.35 -98.1,-236.85 0,-92.49 37.49,-176.23 98.1,-236.85 60.62,-60.61 144.36,-98.1 236.85,-98.1zm304.65 334.95c0,-168.25 -136.4,-304.65 -304.65,-304.65 -84.13,0 -160.3,34.1 -215.42,89.23 -55.13,55.13 -89.23,131.3 -89.23,215.42 0,84.13 34.1,160.3 89.23,215.42 55.13,55.13 131.3,89.23 215.42,89.23 168.25,0 304.65,-136.4 304.65,-304.65z"></path></g>
                    </symbol>
                <?php } else { ?>
                    <!-- Classic -->
                    <symbol id="coinwink-logo" viewBox="0 0 61.36 61.36">
                        <title>Coinwink Logo</title><path d="M30.68,0A30.68,30.68,0,1,1,0,30.68,30.68,30.68,0,0,1,30.68,0Z" style="fill:#fdff7f;fill-rule:evenodd"/><path d="M30.68,6.56a24.14,24.14,0,1,1-17,7.07,24.12,24.12,0,0,1,17-7.07Zm9,17.08a2.57,2.57,0,1,1-2.56,2.57,2.56,2.56,0,0,1,2.56-2.57Zm-23,3.69,4.12-3.46.85-.71.85.71,4.12,3.46-1.7,2L21.69,26.6l-3.27,2.75-1.7-2ZM41,36.19a10.3,10.3,0,0,1-20.6,0H23a7.68,7.68,0,0,0,15.36,0Zm11.64-5.51A21.93,21.93,0,1,0,30.68,52.62,21.94,21.94,0,0,0,52.62,30.68Z" style="fill:#1f1f1f"/>
                    </symbol>
                <?php } ?>
            </defs>
        </svg>

        <!-- SVG icons -->
        <svg style="display: none">
            <defs>
                <symbol id="svg-alert-delete" viewBox="0 0 12.45 12.45">
                    <path d="M299.6,390.6l11.25,11.25m0-11.25L299.6,401.85" transform="translate(-299 -390)" 
                    style="fill:none;stroke:#888888;stroke-linecap:round;stroke-linejoin:round;stroke-width:1px"/>
                </symbol>

                <symbol id="checkmark" viewBox="0 0 512 444.03">
                    <polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"></polygon> 
                </symbol>

                <symbol id="radiomark" viewBox="0 0 200 200">
                    <circle cx="100" cy="100" r="100" />
                </symbol>
            </defs>
        </svg>
        
        <!-- MATRIX THEME OVERLAY AND SCRIPTS -->
        <?php if ($theme == 'matrix') { ?>
            <canvas id="canv" style="position:fixed;z-index:-2;top:0;left:0;"></canvas>
            <div class="overlay" id="matrix-overlay" style="z-index:-1;"></div>
        <?php } ?>

        <div>
            <div id="app"></div>
        </div>
        
        <!-- Scripts -->
        <script src="{{asset(mix('js/app.js'), true)}}" defer></script>
    </body>


    <?php 
        $cssVersion = '002';
        $assetVersion = '033'; 
    ?>

    <?php
        //
        // THEME - PART 3/3
        //
        if (isset($theme)) {
            if ($theme == 'matrix') { ?>  
                <!-- CUSTOM THEME CSS -->
                <link href="/lib/css/style-matrix.css?v=<?php echo $cssVersion; ?>" rel="stylesheet" />
            <?php }
            else if ($theme == 'metaverse') { ?>
                <!-- CUSTOM THEME CSS -->
                <link href="/lib/css/style-metaverse.css?v=<?php echo $cssVersion; ?>" rel="stylesheet" />
            <?php }
        }
        else {
            $theme = 'classic';
        }
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>  
        jQuery.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    </script>
    
    <script src="/lib/js/select2.min.js?v=002"></script>
    <script src="/lib/js/select2_optimization.js?v=<?php echo $assetVersion; ?>"></script>

    <script src="/lib/js/coinwink_utils.js?v=<?php echo $assetVersion; ?>"></script>

    <script src="/lib/js/coinwink_portfolio.js?v=<?php echo $assetVersion; ?>"></script>
    <!-- <script src="/lib/js/DragDropTouch.min.js"></script> -->
    <script src="/lib/js/html5sortable.min.js"></script>
    <script src="/lib/js/coinwink_watchlist.js?v=<?php echo $assetVersion; ?>"></script>

    <script src="/lib/js/coinwink_manage_alerts.js?v=<?php echo $assetVersion; ?>"></script>
    <script src="/lib/js/coinwink_account.js?v=<?php echo $assetVersion; ?>"></script>

    <!-- CSS -->
    <link href="/lib/css/style-select2.css" rel="stylesheet" />


    <!-- Stripe Payments -->
    <?php if (Auth::check()) { 	
        $stripe_pub_key = env('STRIPE_PUB_KEY');
        ?>
        <script>
            var stripePubKey = '<?php echo($stripe_pub_key); ?>';
        </script>
        <script src="https://js.stripe.com/v3/"></script>
        <script src="/lib/js/coinwink_stripe.js?v=<?php echo $assetVersion; ?>"></script>
    <?php } ?>
    
</html>
