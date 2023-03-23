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
        'abovePrice': '',
        'aboveCur': 'USD',
        'belowPrice': '',
        'belowCur': 'USD',
    });


    function resetForm() {
        form.value.abovePrice = '';
        form.value.belowPrice = '';
        form.value.aboveCur = 'USD';
        form.value.belowCur = 'USD';
        
        store.alertCreated = false;
        createAlertButton.value = true;
        ajaxLoader.value = false;
        // router.push({path: '/sms'});
    }


    function selectedCoinChange(selected) {
        // console.log("NewEmailAlert:", selected);
        for(var i=0; i < cw_cmc.length; i++) {
            var coin = cw_cmc[i];
            if (coin['id'] == selected) {
                form.value.coinId = selected;
                form.value.coinSymbol = coin['symbol'];
                form.value.coinName = coin['name'];
                break;
            }
        }
    }


    function validateForm(e) {
        e.preventDefault()
        feedback.value = ''

        // clear feedback
        limitError.value = false;

        if (!form.value.belowPrice && !form.value.abovePrice) {
            feedback.value = 'Please enter at least 1 price value.';
            return false;
        }
        
        if (form.value.abovePrice.length > 0 && isNaN(form.value.abovePrice)) {
            feedback.value = 'Price field should be a numeric value.';
            return false;
        }

        if (form.value.belowPrice.length > 0 && isNaN(form.value.belowPrice)) {
            feedback.value = 'Price field should be a numeric value.';
            return false;
        }

        var url = '/api/alert_sms_cur';

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
    <widget style="text-align:center;" :title="'New SMS Alert'">
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

                    <div class="block-alert">
                        <div class="text-label">Your phone number:<br><div style="font-size:10px;margin-bottom:2px;">In the international format - <a href="https://coinwink.com/blog/how-to-start-receiving-sms-crypto-alerts" class="label-more-info" target="blank">More info</a></div></div>
                        <input maxlength="99" class="input-general" id="phone" name="phone" type="text" placeholder="e.g. +14155552671" required="" v-model="form.phone">
                    </div>
                    <div class="block-alert">
                    <div class="text-label">Alert when price is above:</div>
                    <div class="grid-new-alert-conditions">
                    <div>
                    <input class="input-general-fluid sms_input" id="above_sms" name="above_sms" maxlength="32" type="text" step="any" autocomplete="off" v-model="form.abovePrice">
                    </div>
                    <div></div>
                    <div>
                    <select name="above_currency_sms" id="above_currency_sms" class="select-css-currency" v-model="form.aboveCur">
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
                    <input class="input-general-fluid sms_input" id="below_sms" name="below_sms" maxlength="32" type="text" step="any" autocomplete="off" v-model="form.belowPrice">
                    </div>
                    <div></div>
                    <div>
                    <select name="below_currency_sms" id="below_currency_sms" class="select-css-currency" v-model="form.belowCur">
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
                    
                    <div id="reserved_message_per">
                        <span v-if="(form.abovePrice && !form.belowPrice) || (!form.abovePrice && form.belowPrice)">1 SMS alert</span>
                        <span v-else-if="form.abovePrice && form.belowPrice">2 SMS alerts</span>
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
                        <div v-else id="feedback_acc" class="feedback" v-html="feedback"></div>

                        <div style="height:12px;"></div>

                        <div v-if="createAlertButton" class="block-submit">
                            <input  type="submit" class="button-main" value="Create alert">
                        </div>
                        <div v-else class="block-submit" style="margin-bottom:15px;">
                            <LoadingSpinner :theme='cw_theme' />
                        </div>

                    </span>
                    <span v-else>
                        <div class="block-submit" style="margin-top:7px;margin-bottom:20px;">
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
                <alert-created :form="form" :type="'sms'" @reset="resetForm" />
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
            <br>
            <br>
            <br>
        </span>
    </widget>
</template>