<script setup>    
    import { store } from '../store';

    import { useRouter, useRoute } from 'vue-router';

    const router = useRouter();
    const route = useRoute();

    import Widget from '../widget/Widget.vue';
    import DropDownCoins from '../components/DropDownCoins.vue';
    import DropDownCoinPrice from '../components/DropDownCoinPrice.vue';
    import LoadingSpinner from '../components/LoadingSpinner.vue';

    import { ref } from 'vue';
    
    let limitError = ref(false);
    let limitErrorIp = ref(false);
    let alertCreated = ref(false);
    let createAlertButton = ref(true);
    let ajaxLoader = ref(false);

    const cw_theme = window.cw_theme;

    let feedback = ref('');
    let form = ref({
        'email': window.dest_email,
        'coinId': '', // 1 
        'coinName': '',
        'coinSymbol': route.params.slug.toUpperCase(),
        'abovePrice': '',
        'aboveCur': 'USD',
        'belowPrice': '',
        'belowCur': 'USD',
    });

    function resetForm() {
        form.value.abovePrice = '';
        form.value.belowPrice = '';
        form.value.belowCur = 'USD';
        form.value.aboveCur = 'USD';
        alertCreated.value = false;
        createAlertButton.value = true;
        ajaxLoader.value = false;
        router.push({path: route.params.slug});
    }

    function selectedCoinChange(selected) {
        // console.log("NewEmailAlert:", selected);
        for(var i=0; i < cw_cmc.length; i++) {
            var coin = cw_cmc[i];
            if (coin['id'] == selected) {
                form.value.coinId = selected+'';
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
        limitErrorIp.value = false;

        var emailFilter = /^([a-zA-Z+0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;

        if (!emailFilter.test(form.value.email)) {
            feedback.value = 'Please enter a valid e-mail address.';
            return false;
        }
        
        if (form.value.belowPrice + form.value.abovePrice < 1) {
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

        // var form_new_alert_acc = jQuery(this).serialize();
        // var formData = jQuery(this).serializeArray();

        var url = '/api/alert_email_cur';
        if (store.userLoggedIn) {
            url = '/api/alert_email_cur_acc';
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
                    // feedback.value = "You have reached the limit of 10 alerts. To continue, delete some alerts first or <router-link to='subscription' style='color:red!important;'>subscribe</a> to a Premium plan.";

                    createAlertButton.value = true;
                    ajaxLoader.value = false;
                }
                else if (data == 'Limit error: IP') {
                    limitErrorIp.value = true;
                    createAlertButton.value = true;
                    ajaxLoader.value = false;
                }
                else {
                    alertCreated.value = true;
                }
            
            }
        }); 
        return false;
    }

</script>

<template>
    <widget style="text-align:center;" :title="'New '+form.coinSymbol+' Alert'">
        <span v-if="!alertCreated">
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
                <div class="text-label">Alert me by email:</div>
                <input maxlength="99" class="input-general" autocapitalize="off" id="email_acc" name="email" type="text" v-model="form.email" required="">
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
                <div id="feedback_acc" class="feedback">{{ feedback }}</div>

                <div v-if="limitError" id="limit-error"  style="padding-left:20px;padding-right:20px;">
                    <div style="height:5px;"></div>
                    You reached the limit of 5 alerts.
                    <br><br>
                    <router-link to="/register" style="color:red!important;"><b>Sign up</b></router-link> for a free Coinwink account<br>to manage your alerts.
                    <div style="height:10px;"></div>
                </div>

                <div v-if="limitErrorIp" id="limit-error" style="padding-left:20px;padding-right:20px;">
                    <div style="height:5px;"></div>
                    You are doing that too much.
                    <br><br>
                    <router-link to="/register" style="color:red!important;"><b>Sign up</b></router-link> for a free Coinwink account.
                    <div style="height:10px;"></div>
                </div>

                <div style="height:15px;"></div>
                <input name="action" type="hidden" value="create_alert_acc">
                <input name="unique_id" type="hidden" value="5f843d4f9fcea">

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
        <span v-else>
            <div style="height:35px;"></div>
            <b><span id="created-alert-type">{{form.coinName}} ({{form.coinSymbol}}) alert created!</span></b>
            <div style="height:25px;"></div>
            You will be alerted when:
            <div style="height:15px;"></div>

            <div class="rounded-border" v-if="form.abovePrice"  id="created-alert-first" style="line-height:140%;">{{form.coinName}} ({{form.coinSymbol}})<br>price is above {{ form.abovePrice }} {{form.aboveCur}}</div>
            
            <div class="rounded-border" v-if="form.belowPrice" id="created-alert-second" style="line-height:140%;">{{form.coinName}} ({{form.coinSymbol}})<br>price is below {{form.belowPrice}} {{form.belowCur}}</div>
            
            <span id="created-sing-or-plur">Alerts</span> will be delivered to: 
            <div style="height:2px;"></div>
            <span id="created-delivered-to" style="font-weight:bold;">{{form.email}}</span>

            <div style="height:33px;"></div>

            <div v-if="!store.userLoggedIn" id="created-sign-up" style="padding-left: 20px;padding-right: 20px;">
                <router-link to="/register" class="blacklink"><b>Sign up</b></router-link> for a free Coinwink account
                <div style="height:25px;"></div>
            </div>

            <div id="created-manage-alerts-link">
                <router-link to="/manage-alerts" class="blacklink link-manage-alerts">Manage alerts</router-link>
            </div>

            <div style="height:25px;"></div>

            <span @click="resetForm()" class="blacklink link-email new-crypto-alert-link">New alert</span>

            <div style="height:15px;"></div>
        </span>
    </widget>
</template>
