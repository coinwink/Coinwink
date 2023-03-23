<script setup>
    import Widget from '../widget/Widget.vue';

    import { ref } from 'vue';

    let subs = ref(window.subs);
    let plan = ref(window.subs_plan);
    let status = ref(window.status);
    let sms = ref(window.sms);

    // plan.value = 'premium'

    let selectedPremium = ref(true);

    var declined = false;
    const date_end = window.date_end;
    const userLoggedIn = window.userLoggedIn;

    function stripePreCheckout() {
        if (selectedPremium.value) {
            stripeCheckout()
        }
        else {
            stripeCheckoutStandard();
        }
    }
</script>

<template>
    <widget :title="'Subscription'" style="text-align:center;">

        <div class="widget-padding-25" v-if="!userLoggedIn">
            To manage your Coinwink subscription, please <router-link to="/login" class="blacklink">log in</router-link> to your account.
        </div>

        <span v-else>
            <div style="height:30px;"></div>

            <span style="font-size:14px;">Your current plan: <b v-if="subs == 0 && status != 'suspended'">Free</b><b v-else-if="subs == 1 && status == 'active' && plan == 'premium'">Premium</b><b v-else-if="subs == 1 && status == 'active' && plan == 'standard'">Standard</b><b v-else-if="subs == 1 && status == 'cancelled'">Cancelled</b><b v-else-if="subs == 0 && status == 'suspended'">Suspended</b></span>
            <br>
            <br>
            <br>

            <span v-if="subs == 0 && status != 'suspended'" >
                <div style="margin:0 auto;width: 260px;">
                    <!-- Upgrade to <b>Premium</b> -->

                    <div class="rounded-border cursor-pointer pricing-plan"  @click="selectedPremium = true" :class="{ selectedsubs : selectedPremium }">
                        <b>Premium</b><br><br>
                        <div style="padding-left: 32px; margin-bottom: 18px">
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited Email alerts
                        </div>
                        <div style="clear: both; height: 3px"></div>
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited Telegram alerts
                        </div>
                        <!-- <div style="clear: both; height: 3px"></div>
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited Portfolio alerts
                        </div> -->
                        <div style="clear: both; height: 3px"></div>
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited coins in Portfolio
                        </div>
                        <div style="clear: both; height: 3px"></div>
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited coins in Watchlist
                        </div>
                        <div style="clear: both; height: 3px"></div>
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            100 SMS alerts per month
                        </div>
                        </div>
                        <br />
                        <b>$12&nbsp;/&nbsp;month</b>
                    </div>

                    <div class="rounded-border cursor-pointer pricing-plan" @click="selectedPremium = false" :class="{ selectedsubs : !selectedPremium }">
                        <b>Standard</b><br><br>
                        <div style="padding-left: 32px; margin-bottom: 18px">
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited Email alerts
                        </div>
                        <div style="clear: both; height: 3px"></div>
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited Telegram alerts
                        </div>
                        <!-- <div style="clear: both; height: 3px"></div>
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited Portfolio alerts
                        </div> -->
                        <div style="clear: both; height: 3px"></div>
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited coins in Portfolio
                        </div>
                        <div style="clear: both; height: 3px"></div>
                        <div style="float: left; width: 12px">
                            <svg viewBox="0 0 512 444.03">
                            <polygon
                                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                                style="fill: #777777"
                            ></polygon>
                            </svg>
                        </div>
                        <div style="float: left; margin-left: 7px">
                            Unlimited coins in Watchlist
                        </div>
                        </div>
                        <br />
                        <b>$8&nbsp;/&nbsp;month</b>
                    </div>

                </div>

                <div style="height:10px;"></div>

                <span style="font-size:15px;">
                    <span v-if="selectedPremium">Upgrade to <b>Premium</b></span>
                    <span v-else>Upgrade to <b>Standard</b></span>
                </span>

                <div style="height:15px;"></div>

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

                <button v-if="selectedPremium" id="cc-pay-button" @click="stripePreCheckout()" class="button-checkout" style="width:180px;">Pay $12.00</button>
                <button v-else id="cc-pay-button" @click="stripePreCheckout()" class="button-checkout" style="width:180px;">Pay $8.00</button>

                <div style="height: 35px;"></div>

                <div style="clear:both;text-align:center;font-size:12px;">
                    Subscription price: <b><span v-if="selectedPremium">12</span><span v-else>8</span> USD per month</b>
                    <br>
                    <div style="height:5px;"></div>
                    Cancel at any time in your account settings
                    <div style="height: 24px;"></div>
                </div>
                
                Payment with a bank card
                <div style="height:5px;"></div>
                Powered by <a href="https://stripe.com" target="_blank" class="blacklink blacklink-acc">Stripe</a>

                <div style="height:12px;"></div>

            </span>
            
            <span v-if="subs == 1 && status == 'active' && plan == 'premium'" style="line-height:140%;">
                Unlimited Email alerts<br>
                Unlimited Telegram alerts<br>
                <!-- Unlimited Portfolio alerts<br> -->
                Unlimited coins in Portfolio<br>
                Unlimited coins in Watchlist<br>
                SMS left this month: <b>{{sms}}</b>
                <div style="height:25px;"></div>
                <router-link to="/account" class="blacklink">Account</router-link>
            </span>

            <span v-if="subs == 1 && status == 'active' && plan == 'standard'" style="line-height:140%;">
                Unlimited Email alerts<br>
                Unlimited Telegram alerts<br>
                <!-- Unlimited Portfolio alerts<br> -->
                Unlimited coins in Portfolio<br>
                Unlimited coins in Watchlist<br>
                <div style="height:25px;"></div>
                <router-link to="/account" class="blacklink">Account</router-link>
            </span>

            <span v-if="subs == 1 && status == 'cancelled'">
                <div class="content">
                    Your subscription was cancelled.
                    <br><br>
                    You can continue using the <span style="text-transform: capitalize;">{{plan}}</span> plan features until {{ date_end }}. 
                    After that, your account will automatically switch to the Free plan.
                    <div style="height:20px;"></div>
                    Create a new subscription:
                    <div style="height:5px;"></div>
                    <span class="new-subs-link"><span class="blacklink blacklink-acc" onclick="stripeCheckoutNew('premium')">Premium</span> | <span class="blacklink blacklink-acc" onclick="stripeCheckoutNew('standard')">Standard</span></span>
                    <span id="new-subs-wait" style="display:none;">Please wait...</span>
                    <div style="height:5px;"></div>
                </div>
            </span>

            <span v-if="subs == 0 && status == 'suspended'">
                <div class="content">
                    We were not able to proceed your credit card for a recurring monthly payment.
                    <br><br>
                    Your paid plan features are currently disabled.
                    <br>
                    <br>
                    We will try to proceed your credit card during the period of the next few days. If the payment is received, the paid plan features will be re-enabled automatically.
                    <br>
                    <br>
                    If you wish to update payment information, please visit your account.
                    <br>
                    <br>
                    <router-link to="/account" class="blacklink">Account</router-link>
                </div>
            </span>
    
        </span>
    </widget>

</template>

<style scoped>
    .selectedsubs {
        border: 4px solid #00abc4;
        background: aliceblue;
        padding: 12px 7px 12px 7px!important;
    }
</style>