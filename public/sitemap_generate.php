<?php

    require_once 'coinwink_auth_sql.php';

    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        <url>
            <loc>https://coinwink.com</loc>
            <priority>1</priority>
        </url>
        <url>
            <loc>https://coinwink.com/portfolio</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/watchlist</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/manage-alerts</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/email</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/email-per</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/sms</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/sms-per</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/telegram</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/telegram-per</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/about</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/pricing</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/terms</loc>
            <priority>0.9</priority>
        </url>
        <url>
            <loc>https://coinwink.com/privacy</loc>
            <priority>0.9</priority>
        </url>
        <url>
            <loc>https://coinwink.com/contacts</loc>
            <priority>0.99</priority>
        </url>
        <url>
            <loc>https://coinwink.com/account</loc>
            <priority>0.99</priority>
        </url>';

    // $pages = array (
    //     0 => 'about',
    //     1 => 'pricing',
    //     2 => 'terms',
    //     3 => 'privacy',
    //     4 => 'press',
    //     5 => 'contacts',
    //     6 => 'subscription',
    //     7 => 'manage-alerts',
    //     8 => 'portfolio',
    //     9 => 'email',
    //     10 => 'email-per',
    //     11 => 'sms',
    //     12 => 'sms-per',
    //     13 => 'account',
    //     14 => 'changepass',
    //     15 => 'home',
    //     16 => 'watchlist',
    // );

    // foreach ($pages as $page) {
    //     $sitemap .= '
    //     <url>
    //         <loc>https://coinwink.com/'.$page.'/</loc>
    //         <priority>0.99</priority>
    //     </url>';
    // }


    // Get coin price data from the database
    $resultdb2 = DB::query( "SELECT json FROM cw_data_cmc" );
    $newarrayjson = $resultdb2[0]['json'];
    // $newarrayunserialized = unserialize($newarrayjson);
    $newarrayunserialized = json_decode($newarrayjson, TRUE);


    $symbols = [];
    $i = 0;
    foreach ($newarrayunserialized as $jsoncoin) {
        // echo('https://coinwink.com/'.$jsoncoin['symbol']);
        if (in_array($jsoncoin['symbol'], $symbols)) {
            // echo($jsoncoin['symbol'].' ');
            continue;
        }
        $symbols[] = $jsoncoin['symbol'];
        $priority = 0.85;
        if ($i < 1000) {
            $priority = 0.9;
        }
        if ($i < 100) {
            $priority = 0.95;
        }
        if ($i < 10) {
            $priority = 0.99;
        }
        $sitemap .= '
        <url>
            <loc>https://coinwink.com/'.strtolower($jsoncoin['symbol']).'</loc>
            <priority>'.$priority.'</priority>
        </url>';
        $i++;
    }

    // echo($sitemap);

    $end = '
</urlset>';

    $sitemap .= $end;

    $file = fopen("sitemap.xml", "w");
    fwrite($file, $sitemap);

?>