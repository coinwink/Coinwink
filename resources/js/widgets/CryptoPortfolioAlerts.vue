<script setup>
import Widget from '../widget/Widget.vue';
import {store} from '../store.js';
import {ref, watch} from 'vue';

let showAbout = ref(false);
let expanded = ref(0);
let feedback = ref('Loading...');
const subs = window.subs;
const plan = window.subs_plan;

let form = ref({
    'change_1h_plus': '10',
    'change_1h_minus': '10',
    'change_24h_plus': '10',
    'change_24h_minus': '10',
    'on_1h_plus': false,
    'on_1h_minus': false,
    'on_24h_plus': false,
    'on_24h_minus': false,
    'type': 'email',
    'destination': ''
})

let tg_user = ref(window.tg_user);
let username = ref('');
let validateFeedback = ref(false);

function validateUsername() {
    validateFeedback.value = false;
    if (!username.value) {
        validateFeedback.value = 'empty';
        return;
    }
    axios({
        method: 'post',
        url: '/api/telegram_validate_username',
        data: {username: username.value}
    })
    .then((response) => {
        // console.log(response);
        if (response.data == 'success') {
            if(username.value.charAt(0) == '@') username.value = username.value.slice(1);
            tg_user.value = username.value;
            window.tg_user = username.value;
            form.value.destination = username.value;
            feedback.value = '0 active portfolio alerts';
        }
        else {
            validateFeedback.value = 'error';
        }
    })
    .catch((error) => {
        console.log(error);
    });
}

function telegramDisconnect() {
    if (confirm('Are you sure you want to disconnect your Telegram account from the Coinwink app?')) {
        axios({
            method: 'post',
            url: '/api/telegram_disconnect',
            data: {username: username.value}
        })
        .then((response) => {
            // console.log(response);
            if (response.data == 'success') {
                tg_user.value = '';
                window.tg_user = '';
                username.value = '';
                form.value.destination = '';
                portfolioAlertsReset();
            }
        })
        .catch((error) => {
            console.log(error);
        });
    }
}

// Execute
jQuery.ajax({
    type:"GET",
    url: '/api/portfolio_alerts',
    success:function(data){
        // var response = JSON.parse(data);
        var response = data;

        if (!response) {
            feedback.value = "0 active portfolio alerts";
            return;
        }

        if (response.expanded == 1) {
            expanded.value = 1;
        }
        else {
            expanded.value = 0;
        }

        var alertsCount = 0;

        if (response.on_1h_plus == "on") { 
            alertsCount++; 
            form.value.on_1h_plus = true 
            form.value.change_1h_plus = response.change_1h_plus;
        }
        if (response.on_1h_minus == "on") { 
            alertsCount++; 
            form.value.on_1h_minus = true 
            form.value.change_1h_minus = response.change_1h_minus;
        }
        if (response.on_24h_plus == "on") { 
            alertsCount++; 
            form.value.on_24h_plus = true 
            form.value.change_24h_plus = response.change_24h_plus;
        }
        if (response.on_24h_minus == "on") { 
            alertsCount++; 
            form.value.on_24h_minus = true 
            form.value.change_24h_minus = response.change_24h_minus;
        }

        if (typeof(response.type) != 'undefined') {
            form.value.type = response.type;
        }
        
        if (typeof(response.destination) != 'undefined') {
            form.value.destination = response.destination;
        }

        // console.log(response.plus_1h, response.plus_24h, response.minus_1h, response.minus_24h);

        // console.log(alertsCount);
        var alertsLabel = "";
        if (alertsCount == 1) { alertsLabel = "alert" } else { alertsLabel = "alerts" }
        feedback.value = alertsCount + " active portfolio "+alertsLabel; 

    }
});

function expandCollapse() {
    if (expanded.value == 1) {
        expanded.value = 0;
    }
    else {
        expanded.value = 1;
    }

    jQuery.ajax({
        type:"POST",
        url: '/api/portfolio_alerts_expanded',
        data: 'expanded='+expanded.value
    });
}


// 190522

// jQuery('#portfolio-alert-type').change(function( event ){
//   var alertType = jQuery("#portfolio-alert-type").val();
// 	if (alertType == "email") {
//     jQuery("#portfolio-alert-destination").val('');;
//     jQuery("#portfolio-alert-destination").attr("placeholder", "E-mail address");
//   }
//   else if (alertType == "sms") {
//     jQuery("#portfolio-alert-destination").val('');
//     jQuery("#portfolio-alert-destination").attr("placeholder", "Phone number");
//   }

//   // jQuery("#portfolio-user-feedback").html("All portfolio alerts were reset");

//   // portfolioAlertsSubmit();
//   portfolioAlertsReset();
// });


jQuery("#portfolio-alerts-about-show-hide").click(function() {
  jQuery("#portfolio-alerts-about-show-hide").toggleClass("portfolio-alerts-about-expanded");
  jQuery(".portfolio-alerts-about-title").toggleClass("portfolio-alerts-about-title-bold");
//   jQuery(".portfolio-alerts-about-content").toggle();
});

jQuery("#logs-show-hide").click(function() {
  jQuery("#logs-show-hide").toggleClass("logs-expanded");
  jQuery(".logs-title").toggleClass("portfolio-alerts-about-title-bold");
  jQuery(".logs-content").toggle();
});

function portfolioAlertsReset() {
    if (feedback.value != "<span style='padding-top:10px;padding-bottom:10px;color:red;'>Missing e-mail address</span>" && feedback.value != "<span style='padding-top:10px;padding-bottom:10px;color:red;'>Missing phone number</span>") {
        // Backend update
        jQuery.ajax({
            type:"POST",
            url: '/api/portfolio_alerts_clear',
            success:function(data){
                console.log(data);
            }
        });
    }

    form.value.change_1h_plus = '10';
    form.value.change_1h_minus = '10';
    form.value.change_24h_plus = '10';
    form.value.change_24h_minus = '10';
    form.value.on_1h_plus = false;
    form.value.on_1h_minus = false;
    form.value.on_24h_plus = false;
    form.value.on_24h_minus = false;
    if (tg_user.value != '' && form.value.type == 'telegram') {
        form.value.destination = tg_user.value;
    }
    else {
        form.value.destination = '';
    }

    feedback.value = 'Alerts were reset';
}


function portfolioAlertsSubmit () {
    var alertType = form.value.type;
    var alertDestination = form.value.destination;



  if (alertDestination == "") {
    if (alertType == "email") { 
      portfolioAlertsReset();
      feedback.value = "<span style='padding-top:10px;padding-bottom:10px;color:red;'>Missing e-mail address</span>";
      return;
    }
    if (alertType == "telegram") { 
      portfolioAlertsReset();
      feedback.value = "<span style='padding-top:10px;padding-bottom:10px;color:red;'>Missing Telegram username</span>";
      return;
    }
    else if (alertType == "sms") { 
      portfolioAlertsReset();
      feedback.value = "<span style='padding-top:10px;padding-bottom:10px;color:red;'>Missing phone number</span>";
      return;
    }
  }
  else {

    if (alertType == "email") {
        const validateEmail = (email) => {
            return email.match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };

        if (!validateEmail(alertDestination)) {
            feedback.value = "<span style='padding-top:10px;padding-bottom:10px;color:red;'>Please enter a valid e-mail address</span>";
            return false;
        }
    }
    
    var portfolioAlert1 = form.value.change_1h_plus;
    var portfolioAlert2 = form.value.change_1h_minus;
    var portfolioAlert3 = form.value.change_24h_plus;
    var portfolioAlert4 = form.value.change_24h_minus;

    // console.log(portfolioAlert1, portfolioAlert2, portfolioAlert3, portfolioAlert4);

    if (isNaN(portfolioAlert1) || isNaN(portfolioAlert2) || isNaN(portfolioAlert3) || isNaN(portfolioAlert4)) {
        feedback.value = "<span style='padding-top:10px;padding-bottom:10px;color:red;'>Entered value must be a number</span>";
        return;
    }

    if (portfolioAlert1 < 5 || portfolioAlert1 > 1000) {
        if (portfolioAlert1 < 5) { 
        portfolioAlert1 = 5;
        form.value.change_1h_plus = "5";
        }
        else { 
        portfolioAlert1 = 1000;
        form.value.change_1h_plus = "1000";
        }
    }

    if (portfolioAlert2 < 5 || portfolioAlert2 > 1000) {
        if (portfolioAlert2 < 5) { 
        portfolioAlert2 = 5;
        form.value.change_1h_minus = "5";
        }
        else { 
        portfolioAlert2 = 1000;
        form.value.change_1h_minus = "1000";
        }
    }
    
    if (portfolioAlert3 < 5 || portfolioAlert3 > 1000) {
        if (portfolioAlert3 < 5) { 
        portfolioAlert3 = 5;
        form.value.change_24h_plus = "5";
        }
        else { 
        portfolioAlert3 = 1000;
        form.value.change_24h_plus = "1000";
        }
    }
    
    if (portfolioAlert4 < 5 || portfolioAlert4 > 1000) {
        if (portfolioAlert4 < 5) { 
        portfolioAlert4 = 5;
        form.value.change_24h_minus = "5";
        }
        else { 
        portfolioAlert4 = 1000;
        form.value.change_24h_minus = "1000";
        }
    }

    var alertsLabel = "";
    var activeAlerts = form.value.on_1h_plus + form.value.on_1h_minus + form.value.on_24h_plus + form.value.on_24h_minus;

    if (activeAlerts == 1) { 
        alertsLabel = "alert" 
    } 
    else { 
        alertsLabel = "alerts"
    }
  
    feedback.value = activeAlerts+" active portfolio "+alertsLabel;

    var data = 'action=portfolio_alerts_create&' + jQuery('#portfolio-alerts-form').serialize();

    // Execute
    jQuery.ajax({
        type:"POST",
        url: '/api/portfolio_alerts_create',
        data: form.value,
        success:function(data){
            console.log(data);
        }
    });

  }

}

</script>

<template>
    <div class="widget-container" style="text-align:center;min-height:40px;">
        <header>
            <h1 @click="expandCollapse()" style="cursor:pointer;">Portfolio Alerts</h1>
        </header>
        <div class="converter-arrow">
            <div v-show="expanded != 0" class="expand-collapse" style="top:19px;" id="converter-hide" title="Collapse" @click="expandCollapse()">
                <svg data-name="Layer 1" viewBox="0 0 23 13">
                    <path class="svg-show-hide" d="M22 12H1v-1L11 1h1l11 10-1 1z" stroke="#bdbfc1" stroke-miterlimit="3" fill-rule="evenodd"/>
                </svg>
            </div>
            <div v-show="expanded != 1" class="expand-collapse" id="converter-show" title="Expand" @click="expandCollapse()">
                <svg data-name="Layer 1" viewBox="0 0 23 13">
                    <path class="svg-show-hide"  d="M1 1h22L12 12h-1L1 1V0z" stroke="#bdbfc1" stroke-miterlimit="3" fill-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <div v-if="expanded" class="portfolio-alerts-content" id="portfolio-alerts-content" style="font-size:13px;line-height:150%;">
            <div style="height:30px;"></div>

            <form id="portfolio-alerts-form" v-on:change="portfolioAlertsSubmit">
                Alert me by:
                <br>

                <!-- if logged in and not Premium -->
                <span v-if="(store.userLoggedIn && !subs) || (store.userLoggedIn && plan == 'standard')">
                    <select id="portfolio-alert-type" name="alert_type" class="select-css-currency" style="height:28px;width:220px;margin-top:3px;margin-bottom:8px;" v-model="form.type" @change="portfolioAlertsReset">
                        <option value="email">E-mail</option>
                        <option value="telegram">Telegram</option>
                        <option value="sms" disabled>SMS</option>
                    </select>
                </span>

                <!-- if have subs -->
                <span v-else>
                    <select id="portfolio-alert-type" name="alert_type" class="select-css-currency" style="width:220px;margin-top:3px;margin-bottom:8px;height:28px;" v-model="form.type" @change="portfolioAlertsReset">
                        <option value="email">E-mail</option>
                        <option value="telegram">Telegram</option>
                        <option value="sms">SMS</option>
                    </select>
                </span>

                <br>
                <input type="text" id="portfolio-alert-destination" name="destination" v-if="form.type == 'email'" placeholder="E-mail address" style="width:220px;" v-model="form.destination">

                <div v-if="form.type == 'telegram' && tg_user == ''"  style="padding-left:20px;padding-right:20px;">
                    <div style="height:15px;"></div>
                    To receive Telegram crypto alerts, first open the Telegram app and send any message to the <b>@CoinwinkBot</b> Telegram account.
                    <br><br>
                    Then enter your Telegram @username below, and click Connect. After the connection between your Coinwink account and the Telegram bot is established, you will be able to create and receive Telegram crypto alerts.
                    <div style="height:20px;"></div>
                    <form @submit.prevent="validateUsername()">
                        <input type="text" placeholder="Username" v-model="username" class="input-general" style="height:28px;width:220px;">
                        <div style="height:10px;"></div>
                        <input type="submit"  class="button-main" value="Connect">
                    </form>
                    <div v-if="validateFeedback == 'error'">
                        <div style="height:20px;"></div>
                        Error: Username does not exist
                        <br><br>
                        You can set up your username in the Telegram app settings. If you change your username, send a new message to @CoinwinkBot, and then connect with the new username.
                    </div>
                    <div v-if="validateFeedback == 'empty'">
                        <div style="height:20px;"></div>
                        Enter your Telegram username.
                        <br><br>
                        You can set up your username in the Telegram app settings. If you change your username, send a new message to @CoinwinkBot, and then connect with the new username.
                    </div>
                </div>

                <div v-if="form.type == 'telegram' && tg_user != ''">
                    <div class="block-alert">
                        <div class="input-tg-user" style="width:220px;position: relative;text-align: left;padding-left: 4px;">
                            <span class="noselect">@<span>{{ tg_user }}</span></span>
                            <div style="position:absolute;right:6px;top:4px;font-size:13px;cursor:pointer;" @click="telegramDisconnect()" title="Disconnect" class="tg-disconnect">✕</div>
                        </div>
                    </div>
                </div>
                
                <!-- <input type="text" id="portfolio-alert-destination" name="destination" v-else-if="form.type == 'telegram'" placeholder="@Username" style="width:220px;" v-model="form.destination"> -->

                <input type="text" id="portfolio-alert-destination" name="destination" v-if="form.type == 'sms'" placeholder="Phone number" style="width:220px;" v-model="form.destination">
                
                <div style="height:30px;"></div>

                <div>

                    <div style="margin-left:-2px;" class="portfolio-alerts-conditions">
                        When any coin in my portfolio:
                        <div style="height:15px;"></div>
                        
                        <div style="padding-left:2px;">

                            <div class="ma-label">
                                <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                                    <input id="portfolio-alert-1-checkbox" name="portfolio-alert-1" type="checkbox" class="appify-input-checkbox" v-model="form.on_1h_plus" />
                                    <label for="portfolio-alert-1-checkbox">
                                        <div class="checkbox-box">  
                                            <svg><use xlink:href="#checkmark" /></svg>
                                        </div> 
                                        Is up by
                                    </label>
                                </div>
                            </div>
                            <div class="ma-input">
                                <input name="portfolio-alert-1-value" id="portfolio-alert-1-value" min="5" max="1000" type="number" v-model="form.change_1h_plus">&nbsp;&nbsp;<b>%</b> in 1h.
                            </div>

                            <div style="height:12px;"></div>

                            <div class="ma-label">
                                <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                                    <input id="portfolio-alert-2-checkbox" name="portfolio-alert-2" type="checkbox" class="appify-input-checkbox" v-model="form.on_1h_minus" />
                                    <label for="portfolio-alert-2-checkbox">
                                        <div class="checkbox-box">  
                                            <svg><use xlink:href="#checkmark" /></svg>
                                        </div> 
                                        Is down by
                                    </label>
                                </div>
                            </div>
                            <div class="ma-input">
                                <input name="portfolio-alert-2-value" id="portfolio-alert-2-value" min="5" max="1000" type="number" v-model="form.change_1h_minus">&nbsp;&nbsp;<b>%</b> in 1h.
                            </div>

                            <div style="clear:both;height:20px;"></div>


                            <div class="ma-label-2" style="margin-left:-4px;">
                                <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                                    <input id="portfolio-alert-3-checkbox" name="portfolio-alert-3" type="checkbox" class="appify-input-checkbox" v-model="form.on_24h_plus"/>
                                    <label for="portfolio-alert-3-checkbox">
                                        <div class="checkbox-box">  
                                            <svg><use xlink:href="#checkmark" /></svg>
                                        </div> 
                                        Is up by
                                    </label>
                                </div>
                            </div>
                            <div class="ma-input-2">
                                <input name="portfolio-alert-3-value" id="portfolio-alert-3-value" min="5" max="1000" v-model="form.change_24h_plus" type="number">&nbsp;&nbsp;<b>%</b> in 24h.
                            </div>

                            <div style="height:12px;"></div>

                            <div class="ma-label-2" style="margin-left:-4px;">
                                <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                                    <input id="portfolio-alert-4-checkbox" name="portfolio-alert-4" type="checkbox" class="appify-input-checkbox" v-model="form.on_24h_minus"/>
                                    <label for="portfolio-alert-4-checkbox">
                                        <div class="checkbox-box">  
                                            <svg><use xlink:href="#checkmark" /></svg>
                                        </div> 
                                        Is down by
                                    </label>
                                </div>
                            </div>
                            <div class="ma-input-2">
                                <input name="portfolio-alert-4-value" id="portfolio-alert-4-value" min="5" max="1000" v-model="form.change_24h_minus" type="number">&nbsp;&nbsp;<b>%</b> in 24h.
                            </div>

                            <div style="height:10px;"></div>
                        
                        </div>

                    </div>

                </div>

            </form>

            <div class="portfolio-alerts-about" :class="{'portfolio-alerts-about-expanded': showAbout}" id="portfolio-alerts-about-show-hide" @click="showAbout = !showAbout">
                <span class="portfolio-alerts-about-title" :class="{'portfolio-alerts-about-title-bold': showAbout}">About</span>

                <div class="portfolio-alerts-about-content" v-if="showAbout" style="color:black;">
                    Portfolio alerts are multiple-coin alerts. You will receive an alert when any coin in your portfolio will increase/decrease in 1h/24h by specified percentage.
                    <br><br>
                    The percentage range is between 5% and 1000%.
                    <br><br>
                    Example: Add a few coins to your portfolio. Activate the 1h 10% increase alert. Now each time any coin from your portfolio increases by 10% (or more) in 1 hour period, you will receive an alert.
                    <br><br>
                    Portfolio alerts are continuous. It means that if the alert was sent, it will be sent again the next time the conditions are met. There is no need to manually re-activate it.
                    <br><br>
                    For each individual portfolio coin, the same type of alert is sent once in 24h. For example, if you received an alert that Bitcoin increased by more than 10% in 1h, all the following Bitcoin portfolio alerts for the 1h increase will be ignored for the next 24h. It means that during a 24 hour period, you can receive a maximum of 4 alerts for a single portfolio coin. For example, Bitcoin increased and decreased in 1h and in 24h by 10%.
                    <br><br>
                    Portfolio alerts are sent every 2 minutes.
                    <div style="height:10px;"></div>
                </div>
            </div>

        </div>

        <div id="portfolio-user-feedback" style="margin-top:30px;" v-html="feedback"></div>
        
        <div style="height:30px;"></div>
    </div>
</template>