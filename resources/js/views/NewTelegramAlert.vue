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

    // Needed for email alerts only
    // if (switchLocation != 'email') {
    //     jQuery("#emailSwitch").prop('checked', true);
    //     switchLocation = "email";
    // }

    const userRole = window.userRole;

    import { ref } from 'vue';
    
    let limitError = ref(false);
    let createAlertButton = ref(true);
    let ajaxLoader = ref(false);

    const cw_theme = window.cw_theme;

    let tg_user = ref(window.tg_user);
    let username = ref('');
    let validateFeedback = ref(false);
    
    let feedback = ref('');
    let form = ref({
        'email': username.value,
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
        // router.push({path: '/email'});
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
        
        var url = '/api/alert_tg_cur';
        createAlertButton.value = false;

        axios({
            method: 'post',
            url: url,
            data: form.value
        })
        .then((response) => {
            // console.log(response);
            if (response.data == 'Limit error') {
                limitError.value = true;
                feedback.value = 'Limit error';
                createAlertButton.value = true;
                ajaxLoader.value = false;
            }
            else if (response.data == 'Limit error: IP') {
                limitError.value = true;
                feedback.value = 'Limit error: IP';
                createAlertButton.value = true;
                ajaxLoader.value = false;
            }
            else {
                store.alertCreated = true;
            }
        })
        .catch((error) => {
            console.log(error);
        });

        return false;
    }

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
                }
            })
            .catch((error) => {
                console.log(error);
            });
        }
    }
</script>

<template>
    <widget style="text-align:center;" :title="'New Telegram Alert'">
        <span v-if="store.userLoggedIn">
            <div v-if="tg_user == ''" style="padding-left:20px;padding-right:20px;">
                <div style="height:25px;"></div>
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
                <div style="height:5px;"></div>
            </div>
            <div v-else-if="!store.alertCreated">
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

                    <div class="block-alert">
                        <div class="text-label">Telegram username:</div>
                        <div class="input-tg-user">
                            <span class="noselect">@<span>{{ tg_user }}</span></span>
                            <div style="position:absolute;right:6px;top:4px;font-size:13px;cursor:pointer;" @click="telegramDisconnect()" title="Disconnect" class="tg-disconnect">✕</div>
                        </div>
                    </div>
                        <div class="block-alert">
                        <div class="text-label">Alert when price is above:</div>
                        <div class="grid-new-alert-conditions">
                        <div>
                        <input id="above_acc" name="above" maxlength="32" type="text" step="any" autocomplete="off" class="input-general-fluid" v-model="form.abovePrice">
                        </div>
                        <div></div>
                        <div>
                        <select name="above_currency" id="above_currency_acc" class="select-css-currency" v-model="form.aboveCur">
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
                        <input id="below_acc" name="below" maxlength="32" type="text" step="any" autocomplete="off" class="input-general-fluid" v-model="form.belowPrice">
                        </div>
                        <div></div>
                        <div>
                        <select name="below_currency" id="below_currency_acc" class="select-css-currency" v-model="form.belowCur">
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


                    <!-- @TEST: Test all cases -->
                    <div v-if="feedback == 'Limit error' && store.userLoggedIn" class="feedback">
                        <div style="height:5px;"></div>
                        You reached the limit of 5 alerts.
                        <br><br>
                        To continue, delete some alerts first or <router-link to="/subscription" class="blacklink link-subscription"><b>Upgrade to Premium</b></router-link>
                        <div style="height:10px;"></div>
                    </div>
                    <div v-else id="feedback_acc" class="feedback" v-html="feedback"></div>

                    <!-- <div v-if="feedback == 'Limit error'">
                        You reached the soft limit of 1000 active alerts.
                        <br><br>
                        To continue, please delete some alerts first, or contact us in order to delete all alerts at once.
                        <div style="height:10px;"></div>
                    </div>
                    <div v-else id="feedback_acc" class="feedback" v-html="feedback"></div> -->


                    <div style="height:15px;"></div>

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
            </div>
            <div v-if="store.alertCreated" style="padding-left:20px;padding-right:20px;">
                <alert-created :form="form" :type="'tg'" @reset="resetForm" />
            </div>
        </span>
        <span v-else>
            <div style="margin-top:50px;padding-left:20px;padding-right:20px;">
                To receive Telegram crypto alerts, please create an account
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

<style>
    .tg-disconnect:hover, .tg-disconnect:active {
        font-weight:bold;
    }

    .input-tg-user {
        max-width:240px;height:28px;border:1px solid #888;margin: 0 auto;padding-top:4px;font-size:14px;position:relative;
    }
</style>