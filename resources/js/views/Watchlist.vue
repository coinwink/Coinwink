<script setup>
    import { store } from '../store.js';

    import LoadingSpinner from '../components/LoadingSpinner.vue';
    import DropDownCoins from '../components/DropDownCoins.vue';
    import Widget from '../widget/Widget.vue';

    const cw_theme = window.cw_theme;
    

    function openWatchlist() {
        jQuery("#watchlist_content").hide();
        jQuery(".ajax_loader_watchlist").show();

        jQuery.ajax({
            type: "GET",
            url: '/api/watchlist',
            success: function (data) {
                var watchlist = data;

                if (watchlist) {
                    watchlist = watchlist.replace(/\\/g, "");
                    watchlist = JSON.parse(watchlist);
                    window.watchlist = watchlist;
                    load_watchlist(watchlist);
                    jQuery(".ajax_loader_watchlist").hide();
                }
                else {
                    var watchlist = [];
                    window.watchlist = watchlist;

                    jQuery("#watchlist_empty").show();
                    jQuery("#watchlist_content").hide();
                    jQuery(".ajax_loader_watchlist").hide();
                }
            }
        });
    }

    import { watch, onBeforeUnmount } from 'vue';

    if (store.userLoggedIn) {
        if (cw_cmc) {
            openWatchlist();
        }
        else {
            watch(store, (selection, prevSelection) => { 
                openWatchlist();
            })
        }
    }

    onBeforeUnmount(() => {
        jQuery("#watchlist_empty").hide();
        jQuery("#watchlist_content").html('');
    })
</script>

<template>
    <widget style="text-align:center;" :title="'Watchlist'">

        <div id="portfolio">

            <div class="container">

                <span v-if="store.userLoggedIn">

                    <!-- Logged in WATCHLIST -->

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

                        <LoadingSpinner :theme='cw_theme' />

                        <div class="ajax_loader_portfolio ajax_loader_watchlist">
                            <div style="height:10px;"></div>
                            <div class="pw-line"></div>
                        </div>
                    
                        <div id="watchlist_content" style="display:none;">
                            <!-- Inject Watchlist -->
                        </div>

                        <div id="watchlist-message" style="clear:both;padding-top:30px;padding-bottom:0px;line-height:160%;">
                            You have reached your watchlist limit.
                            <br>
                            To enable unlimited features,<br><router-link to="/subscription" class="blacklink link-subscription"><b>Upgrade to Premium</b></router-link>
                        </div>

                        <div class="text-label" style="margin-top:30px;">Add or remove coin:</div>

                        <DropDownCoins :id="'watchlist_dropdown'" style="height:41px"/>

                        <div class="portfolio-buttons" >
                            <button id="watchlist_add_coin" onclick="watchlistAddCoin()" class="plus-minus" style="float:left;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Add</title><polygon points="392.51 155.51 457.01 155.51 457.01 392.51 694.01 392.51 694.01 457.01 457.01 457.01 457.01 694.01 392.51 694.01 392.51 457.01 155.51 457.01 155.51 392.51 392.51 392.51 392.51 155.51 392.51 155.51" class="plus-minus-svg" /></svg>
                            </button>
                            <button id="watchlist_remove_coin" onclick="watchlistRemoveCoin()"  class="plus-minus" style="float:right;padding-left:1px;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Remove</title><polygon points="155.51 463.46 155.51 386.06 694.01 386.06 694.01 463.46 155.51 463.46 155.51 463.46" class="plus-minus-svg" /></svg>
                            </button>
                        </div>

                        <div style="height:32px;"></div>
                        
                        <div id="watchlist-feedback" style="position:absolute;width:100%;text-align:center;padding-top:8px;">
                        </div>

                        <div style="height:3px;"></div>

                    </div>

                    
                </span>
                <span v-else>

                    <!-- Logged out WATCHLIST -->                            

                    <div style="margin-top:45px;padding-left:20px;padding-right:20px;">
                        
                        <h2>Cryptocurrency Watchlist</h2>

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
                        <div style="height:24px;"></div>
                        <a href="https://coinwink.com/blog/coinwink-user-testimonials" target="_blank" class="blacklink">User testimonials</a>
                        <div style="height:10px;"></div>
                        
                        <div style="padding:45px 10px 10px 10px;">
                        Manage your crypto watchlist with a free Coinwink account.
                        </div>

                        <router-link to="/register">
                            <input type="submit" class="hashLink button-acc" value="Sign up">
                        </router-link>

                        <div style="padding:40px 10px 10px 10px;">
                            Already have an account?
                        </div>

                        <router-link style="margin-bottom:10px;" to="/login">
                            <input type="submit" class="hashLink button-acc" value="Log in">
                        </router-link>
                        
                        <div style="height:15px;"></div>

                        <router-link to="/forgot-password" class="blacklink hashLink">Password recovery</router-link>

                        <div style="height:35px;"></div>

                    </div>

                </span>

            </div>
        
        </div>
    </widget>
</template>