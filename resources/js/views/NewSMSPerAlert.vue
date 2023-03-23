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


    const subs = window.subs;
    const plan = window.subs_plan;
    const cw_theme = window.cw_theme;

    import { ref } from 'vue';
    
    let limitError = ref(false);
    let createAlertButton = ref(true);
    let ajaxLoader = ref(false);

    let feedback = ref('');
    let form = ref({
        'phone': window.dest_phone_nr,
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
        // router.push({path: '/sms-per'});
    }


    function selectedCoinChange(selected) {
        // console.log("NewEmailAlert:", selected);
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

        var url = '/api/alert_sms_per';

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
    <widget style="text-align:center;" :title="'New SMS Alert'" :secondary="'Percentage'">
        <span v-if="store.userLoggedIn">
            <div v-if="plan == 'standard'" style="padding-left:20px;padding-right:20px;padding-top:40px;padding-bottom:10px;" class="tg-alerts-msg-metaverse">
                SMS alerts are not available for the Standard plan. Please use email and Telegram alert delivery channels.
            </div>
            <div v-else-if="!store.alertCreated">
                <div style="height:10px;" class="spacer-new-alert"></div>

                <span v-if="subs == 0">
                    <div style="margin-top:5px;margin-bottom:20px;">
                        <router-link to="/subscription" class="link-subscription">Subscribe</router-link><br>
                        <div class="subscribe-note">
                            Subscribe to enable SMS alerts
                        </div>
                    </div>
                </span>

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

                    <!-- @TODO: Test -->
                    <input name="price_set_btc" id="price_set_btc_sms_per" type="hidden" v-model="form.price_set_btc">
                    <input name="price_set_usd" id="price_set_usd_sms_per" type="hidden" v-model="form.price_set_usd">
                    <input name="price_set_eth" id="price_set_eth_sms_per" type="hidden" v-model="form.price_set_eth">
                    
                    <div class="block-alert">
                        <div class="text-label">Your phone number:<br><div style="font-size:10px;margin-bottom:2px;">It should start with the plus sign. <a href="https://support.twilio.com/hc/en-us/articles/223183008-Formatting-International-Phone-Numbers" class="label-more-info" target="blank">More info</a></div></div>
                        <input maxlength="99" class="input-general" id="phone_sms_per" name="phone" type="text" placeholder="e.g. +14155552671" required="" v-model="form.phone">
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
                    <div id="reserved_message_per">
                        <span v-if="(form.plus_percent && !form.minus_percent) || (!form.plus_percent && form.minus_percent)">1 SMS alert</span>
                        <span v-else-if="form.plus_percent && form.minus_percent">2 SMS alerts</span>
                    </div>
                    
                    <div style="height:5px;"></div>
                    <span v-if="subs">
                        
                        <!-- @TODO PR2: (maybe) Finish this, since now 'Limit error' response doesn't exist -->
                        <div v-if="feedback == 'Limit error'">
                            You reached the soft limit of 1000 active alerts.
                            <br><br>
                            To continue, please delete some alerts first, or contact us in order to delete all alerts at once.
                            <div style="height:10px;"></div>
                        </div>
                        <div v-else-if="feedback" id="feedback_acc" class="feedback" v-html="feedback"></div>

                        <div style="height:15px;"></div>

                        <div v-if="createAlertButton" class="block-submit">
                            <input  type="submit" class="button-main" value="Create alert">
                        </div>
                        <div v-else class="block-submit" style="margin-bottom:15px;">
                            <LoadingSpinner :theme='cw_theme' />
                        </div>

                    </span>
                    <span v-else>
                        <div class="block-submit" style="margin-top:-3px;margin-bottom:20px;">
                            <input type="submit" class="button-main button-disabled" value="Create alert" disabled/>
                            <div style="margin-top:13px;">
                                <router-link to="/subscription" class="link-subscription"><b>Subscribe</b></router-link>
                            </div>
                        </div>
                    </span>
                </form>
                <div style="height:5px;"></div>
            </div>
            <div v-if="store.alertCreated" style="padding-left:20px;padding-right:20px;">
                <alert-created :form="form" :type="'sms_per'" @reset="resetForm" />
            </div>
        </span>
        <span v-else>
            <div style="margin-top:50px;">
                For SMS crypto alerts, please create an account
            </div>
            <br>
            <router-link to="/register">
                <input type="submit" class="hashLink button-acc" value="Sign up">
            </router-link>
            <div style="height:39px;"></div>
            Already have an account?
            <br><br>
            <router-link to="/login" style="margin-bottom:10px;">
                <input type="submit" class="hashLink button-acc" value="Log in">
            </router-link>
            <br>
            <br>
            <router-link to="/forgot-password" class="blacklink hashLink">
                Password recovery
            </router-link>
            <div style="height:15px;"></div>
        </span>
    </widget>
</template>