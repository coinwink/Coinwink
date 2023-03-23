<script setup>    

    import { store } from '../store';
    import { watch } from 'vue';

    import { useRouter } from 'vue-router';
    const router = useRouter();

    import Widget from '../widget/Widget.vue';
    import DropDownCoins from '../components/DropDownCoins.vue';
    import DropDownCoinPrice from '../components/DropDownCoinPrice.vue';
    import LoadingSpinner from '../components/LoadingSpinner.vue';
    
    import AlertCreated from './AlertCreated.vue';

    watch(store, (to, from) => {
        if (!to.alertCreated) {
            resetForm();
        }
    })

    const userRole = window.userRole;

    const subs = window.subs;
    const cw_theme = window.cw_theme;

    import { ref } from 'vue';
    
    let limitError = ref(false);
    let createAlertButton = ref(true);
    let ajaxLoader = ref(false);

    let feedback = ref('');
    let form = ref({
        'email': window.dest_email,
        'coinId': initialValue,
        'coinName': '',
        'coinSymbol': '',
        // 'abovePrice': '',
        // 'aboveCur': 'USD',
        // 'belowPrice': '',
        // 'belowCur': 'USD',
        'price_set_btc': '',
        'price_set_usd': '',
        'price_set_eth': '',
        'plus_percent': '',
        'plus_change': 'from_now',
        'plus_compared': 'USD',
        'minus_percent': '',
        'minus_change': 'from_now',
        'minus_compared': 'USD'
    });


    function resetForm() {
        // form.value.coinId = '1';
        // form.value.coinName = 'Bitcoin';
        // form.value.coinSymbol = 'BTC';

        // form.value.price_set_btc = '1';
        // form.value.price_set_usd = cw_cmc[0]['price_usd'];
        // form.value.price_set_eth = cw_cmc[0]['price_eth'];

        form.value.plus_percent = '';
        form.value.plus_change = 'from_now';
        form.value.plus_compared = 'USD';
        form.value.minus_percent = '';
        form.value.minus_change = 'from_now';
        form.value.minus_compared = 'USD';



        store.alertCreated = false;
        createAlertButton.value = true;
        ajaxLoader.value = false;
        // router.push({path: '/email-per'});
    }


    function selectedCoinChange(selected) {
        // console.log("NewEmailPerAlert:", selected);
        for(var i=0; i < cw_cmc.length; i++) {
            var coin = cw_cmc[i];
            if (coin['id'] == selected) {
                form.value.coinId = selected;
                form.value.coinSymbol = coin['symbol'];
                form.value.coinName = coin['name'];
                form.value.price_set_usd = coin['price_usd'];
                form.value.price_set_btc = coin['price_btc'];
                form.value.price_set_eth = coin['price_eth'];
                break;
            }
        }
    }


    function validateForm(e) {
        // form.value.price_set_usd = cw_cmc[0]['price_usd'];
        // form.value.price_set_eth = cw_cmc[0]['price_eth'];

        e.preventDefault()
        feedback.value = ''

        // clear feedback
        limitError.value = false;

        const validateEmail = (email) => {
            return email.match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };

        if (!validateEmail(form.value.email)) {
            feedback.value = 'Please enter a valid e-mail address.';
            return false;
        }

        if (!form.value.plus_percent && !form.value.minus_percent) {
            feedback.value = 'Please enter at least 1 price value.';
            return false;
        }
        
        if (form.value.plus_percent.length > 0 && isNaN(form.value.plus_percent)) {
            feedback.value = 'Price field should be a numeric value.';
            return false;
        }

        if (form.value.minus_percent.length > 0 && isNaN(form.value.minus_percent)) {
            feedback.value = 'Price field should be a numeric value.';
            return false;
        }

        var url = '/api/alert_email_per';
        if (store.userLoggedIn) {
            url = '/api/alert_email_per_acc';
        }

        jQuery.ajax({
            type:"POST",
            url: url,
            data: form.value,
            beforeSend: function(){
                createAlertButton.value = false;
                ajaxLoader.value = true;
            },
            success:function(data){
                if (data == 'Limit error') {
                    limitError.value = true;
                    feedback.value = 'Limit error';
                    createAlertButton.value = true;
                    ajaxLoader.value = false;
                }
                else if (data == 'Limit error: IP') {
                    limitError.value = true;
                    feedback.value = 'Limit error: IP';
                    createAlertButton.value = true;
                    ajaxLoader.value = false;
                }
                else {
                    // alertCreated.value = true;
                    store.alertCreated = true;
                }
            
            }
        }); 
        return false;
    }
</script>

<template>
    <widget style="text-align:center;" :title="'New Email Alert'" :secondary="'Percentage'">
        <span v-if="!store.alertCreated">    
            <div style="height:10px;" class="spacer-new-alert"></div>
            <form @submit="validateForm">
                <div class="text-label">Coin to watch:</div>
                <div class="grid-select2">
                    <div>
                        <DropDownCoins @selected="selectedCoinChange" />
                    </div>
                    <div style="height:2px;"></div>
                    <DropDownCoinPrice :selected="form.coinId" />
                </div>

                <input name="coin" type="hidden" v-model="form.coinName">
                <input name="symbol" type="hidden" v-model="form.coinSymbol">

                <input name="price_set_btc" id="price_set_btc_sms_per" type="hidden" v-model="form.price_set_btc">
                <input name="price_set_usd" id="price_set_usd_sms_per" type="hidden" v-model="form.price_set_usd">
                <input name="price_set_eth" id="price_set_eth_sms_per" type="hidden" v-model="form.price_set_eth">
                
				<div class="block-alert">
                    <div class="text-label">Alert me by email:</div>
                    <input maxlength="99" class="input-general" autocapitalize="off" id="email_acc" name="email" type="text" v-model="form.email" required="">
                </div>

                <div class="block-alert">
                <div class="text-label">Alert when price increases by:</div>
                <div class="grid-new-alert-per">
                <div>
                <input class="input-per sms_per_input" v-model="form.plus_percent" id="plus_sms_per" name="plus_percent" maxlength="32" type="text" step="any" autocomplete="off">
                </div>
                <div class="alert-create-per-label">
                %
                </div>
                <div>
                <select name="plus_change" v-model="form.plus_change" id="plus_change_sms_per" class="select-css-currency">
                    <option value="from_now">from now</option>
                    <option value="1h">in 1h. period</option>
                    <option value="24h">in 24h. period</option>
                </select>
                </div>
                </div>
                <div style="clear:both;height:5px;"></div>
                <div v-if="form.plus_change == 'from_now'" id="div_plus_compared_sms_per" class="grid-new-alert-per-compared">
                    <div style="text-align:right;padding-top:4px;padding-right:2px;">
                        Compared to:&nbsp;
                    </div>
                    <div>
                        <select name="plus_compared" v-model="form.plus_compared" id="plus_compared_sms_per" class="select-css-currency" style="height:24px;">
                            <option value="USD">USD</option>
                            <option value="BTC">BTC</option>
                            <option value="ETH">ETH</option>
                        </select>
                    </div>
                </div>
                <div v-else id="plus_usd_sms_per" class="compared-to-usd">
                    Compared to: <span class="span-usd">USD</span>
                </div>
                </div>
                <div class="block-alert">
                <div class="text-label">And/or when price decreases by:</div>
                <div class="grid-new-alert-per">
                    <div>
                        <input class="input-per sms_per_input" id="minus_sms_per" name="minus_percent" maxlength="32" type="text" step="any" autocomplete="off" v-model="form.minus_percent">
                    </div>
                    <div class="alert-create-per-label">
                    %
                </div>
                <div>
                <select name="minus_change" id="minus_change_sms_per" class="select-css-currency" v-model="form.minus_change">
                    <option value="from_now">from now</option>
                    <option value="1h">in 1h. period</option>
                    <option value="24h">in 24h. period</option>
                </select>
                </div>
                </div>
                <div style="clear:both;height:5px;"></div>
                <div v-if="form.minus_change == 'from_now'" id="div_minus_compared_sms_per" class="grid-new-alert-per-compared">
                    <div style="text-align:right;padding-top:4px;padding-right:2px;">
                        Compared to:&nbsp;
                    </div>
                    <div>
                        <select name="minus_compared" id="minus_compared_sms_per" class="select-css-currency" style="height:24px;" v-model="form.minus_compared">
                            <option value="USD">USD</option>
                            <option value="BTC">BTC</option>
                            <option value="ETH">ETH</option>
                        </select>
                    </div>
                </div>
                <div v-else id="minus_usd_sms_per" class="compared-to-usd">
                    Compared to: <span class="span-usd">USD</span>
                </div>
            </div>

            <div style="height:15px;"></div>

                    <div v-if="feedback == 'Limit error' && store.userLoggedIn"  class="feedback">
                        <span v-if="userRole == 'special'">
                            You reached the limit of 10 alerts.
                        </span>
                        <span v-else>
                            You reached the limit of 5 alerts.
                        </span>

                        <div style="height:15px;"></div>

                        To continue, delete some alerts first or <router-link to="/subscription" class="blacklink link-subscription"><b>Upgrade to Premium</b></router-link>
                        <div style="height:7px;"></div>
                    </div>
                    <div v-else-if="feedback == 'Limit error'">
                        You reached the limit of 5 alerts.
                        <br><br>
                        <router-link to="/register" style="color:red!important;"><b>Sign up</b></router-link> for a free Coinwink account<br>to manage your alerts.
                        <div style="height:10px;"></div>
                    </div>
                    <div v-else-if="feedback == 'Limit error: IP'">
                        You are doing that too much.
                        <br><br>
                        <router-link to="/register" style="color:red!important;"><b>Sign up</b></router-link> for a free Coinwink account.
                        <div style="height:10px;"></div>
                    </div>
                    <div v-else id="feedback_acc" class="feedback" v-html="feedback"></div>

                    <div style="height:15px;"></div>
                    <input name="action" type="hidden" value="create_alert_acc">
                    <input name="unique_id" type="hidden" value="5f843d4f9fcea">
<!--                     
                    <div class="block-submit">
                    <input v-if="createAlertButton" type="submit" id="create_alert_button_acc" class="button-main" value="Create alert">
                    <div v-else>
                        <div class="appify-spinner-div" v-if="cw_theme == 'matrix'"></div>
                        <div v-else class="animated-gif-base64-spinner-loader ajax_loader_portfolio">
                            <div style="height:32px;background-repeat: no-repeat;background-position: center;background-image: url('data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==');"></div>
                        </div>
                    </div>
                     -->

                    <div v-if="createAlertButton" class="block-submit">
                        <input  type="submit" class="button-main" value="Create alert">
                    </div>
                    <div v-else class="block-submit" style="margin-bottom:15px;">
                        <LoadingSpinner :theme='cw_theme' />
                    </div>
            </form>
            <div v-if="!store.userLoggedIn" style="height:40px;">
                <div style="height:32px;"></div>
                To manage your crypto alerts, <router-link to="/login" class="blacklink">log in</router-link> or <router-link to="/register" class="blacklink">register</router-link>
                <div style="height:18px;"></div>
            </div>
            <div style="height:5px;"></div>
        </span>
        <div v-if="store.alertCreated" style="padding-left:20px;padding-right:20px;">
            <alert-created :form="form" :type="'email-per'" @reset="resetForm" />
        </div>
    </widget>
</template>