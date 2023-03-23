<script setup>
import { store } from '../store.js';

import DropDownCoins from '../components/DropDownCoins.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';

import CryptoConverter from '../widgets/CryptoConverter.vue';
import CryptoPortfolioAlerts from '../widgets/CryptoPortfolioAlerts.vue';

import Widget from '../widget/Widget.vue';


function openPortfolio() {
    jQuery("#portfolio_content").hide();
    jQuery(".ajax_loader_portfolio").show();

    jQuery.ajax({
        type:"GET",
        url: '/api/portfolio',
        success:function(data){
            var portfolio = data;
            
            if (portfolio) {
                portfolio = portfolio.replace(/\\/g, "");
                portfolio = JSON.parse( portfolio );
                window.portfolio = portfolio;
                load_portfolio(portfolio);
                jQuery(".ajax_loader_portfolio").hide();
            }
            else {
                var portfolio = [];
                window.portfolio = portfolio;
                
                jQuery("#portfolio_empty").show();
                jQuery("#portfolio_content").hide();
                jQuery(".ajax_loader_portfolio").hide();
            }
        }
    });
}


import { watch, onBeforeUnmount } from 'vue';

const cw_theme = window.cw_theme;

if (store.userLoggedIn) {
    if (cw_cmc) {
        openPortfolio();
    }
    else {
        watch(store, (selection, prevSelection) => { 
            openPortfolio();
        })
    }
}

onBeforeUnmount(() => {
    jQuery("#portfolio_content").html('');
    jQuery("#portfolio_empty").hide();
})

// if (store.userLoggedIn) {
//     openPortfolio();
// }


// Keep focus on select2
// jQuery('select').on('select2:close',
// 	function () {
// 		jQuery(this).focus();
// 	}
// );

</script>

<template>
    <widget style="text-align:center;" :title="'Portfolio'">
        <div id="portfolio">

            <div class="container">

                <span v-if="store.userLoggedIn">

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

                        <LoadingSpinner :theme='cw_theme' />

                        <div class="ajax_loader_portfolio">
                            <div style="height:10px;"></div>
                            <div class="pw-line"></div>
                        </div>
                        
                        <div id="portfolio_content" >
                            <!-- Inject Portfolio -->
                        </div>

                        <div id="portfolio-message" style="clear:both;padding-top:20px;padding-bottom:5px;line-height:160%;">
                            You have reached your portfolio limit.
                            <br>
                            To enable unlimited features,<br><router-link to="/subscription" class="blacklink link-subscription"><b>Upgrade to Premium</b></router-link>
                        </div>

                        <div class="text-label" style="margin-top:30px;">Add or remove coin:</div>
                        <!-- <select class="selectcoin" id="portfolio_dropdown"></select> -->
                        <DropDownCoins :id="'portfolio_dropdown'" style="height:41px"/>
                        <div class="portfolio-buttons" style="height:26px">
                            <button id="portfolio_add_coin" onclick="cwAddCoin()" class="plus-minus" style="float:left;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Add</title><polygon points="392.51 155.51 457.01 155.51 457.01 392.51 694.01 392.51 694.01 457.01 457.01 457.01 457.01 694.01 392.51 694.01 392.51 457.01 155.51 457.01 155.51 392.51 392.51 392.51 392.51 155.51 392.51 155.51" class="plus-minus-svg" /></svg>
                            </button>
                            <button id="portfolio_remove_coin" onclick="cwRemoveCoin()" class="plus-minus" style="float:right;padding-left:1px;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Remove</title><polygon points="155.51 463.46 155.51 386.06 694.01 386.06 694.01 463.46 155.51 463.46 155.51 463.46" class="plus-minus-svg" /></svg>
                            </button>
                        </div>
                        
                        <div style="height:12px;"></div>
                        
                        <div id="portfolio-feedback" style="position:absolute;width:100%;text-align:center;"></div>

                    </div>
                    
                </span>
                
                <span v-else>

                    <!-- Logged out PORTFOLIO -->

                    <div style="margin-top:45px;padding-left:20px;padding-right:20px;">

                        <h2>Crypto Portfolio Tracker</h2>

                        <div style="height:5px;"></div>

                        Bitcoin, Ethereum, XRP, Litecoin and other 3500+ crypto coins and tokens.
                        <div style="height:12px;"></div>
                        The data is based on CoinMarketCap, which is the crypto industry standard.
                        <div style="height:12px;"></div>
                        Every coin on Coinwink has a direct link to its CoinMarketCap page where charts, social news and other relevant info can be found.
                        <div style="height:12px;"></div>
                        Convert between BTC, ETH, USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD.
                        <div style="height:12px;"></div>
                        Make notes, calculate return on investment (ROI).
                        <div style="height:12px;"></div>
                        Multi-coin crypto price alerts for your portfolio coins and tokens.
                        <div style="height:24px;"></div>
                        <a href="https://coinwink.com/blog/coinwink-user-testimonials" target="_blank" class="blacklink">User testimonials</a>

                        <div style="height:10px;"></div>
                        
                        <div style="padding:45px 10px 10px 10px;">
                        Manage your cryptocurrency portfolio with a free Coinwink account.
                        </div>

                        <router-link to="/account">
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

    <span v-if="store.userLoggedIn">
        <div style="height:25px;"></div>

        <CryptoConverter />
        
        <div style="height:25px;"></div>

        <crypto-portfolio-alerts />
    </span>
</template>