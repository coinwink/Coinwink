<script setup>
    import { store } from '../store';

    const props = defineProps({
      form: Object,
      type: String,
    })

    const emit = defineEmits(['reset'])

    function resetForm() {
        // Initial emit
        emit('reset', true)
    }

    let tg_user = window.tg_user;
</script>

<template>
    <div style="height:35px;"></div>
    <b><span id="created-alert-type">{{form.coinName}} ({{form.coinSymbol}}) alert created!</span></b>
    <div style="height:25px;"></div>
    You will be alerted when:
    <div style="height:15px;"></div>

    <span v-if="type == 'sms' || type == 'email' || type == 'tg'">
        <div class="rounded-border" v-if="form.abovePrice"  id="created-alert-first" style="line-height:140%;">{{form.coinName}} ({{form.coinSymbol}})<br>price is above {{ form.abovePrice }} {{form.aboveCur}}</div>
        <div class="rounded-border" v-if="form.belowPrice" id="created-alert-second" style="line-height:140%;">{{form.coinName}} ({{form.coinSymbol}})<br>price is below {{form.belowPrice}} {{form.belowCur}}</div>
    </span>
    <span v-else>
        <div class="rounded-border" v-if="form.plus_percent"  id="created-alert-first" style="line-height:140%;">
            {{ form.coinName }} ({{ form.coinSymbol }})
            <br>
            <span v-if="form.plus_change == 'from_now'">
                price increases by {{form.plus_percent}}%
                <br>
                compared to {{form.plus_compared}}
            </span>
            <span v-else-if="form.plus_change == '1h'">
                price increases by {{form.plus_percent}}%
                <br>
                in 1 h period
            </span>
            <span v-else-if="form.plus_change == '24h'">
                price increases by {{form.plus_percent}}%
                <br>
                in 24 h period
            </span>
        </div>
        <div class="rounded-border" v-if="form.minus_percent" id="created-alert-second" style="line-height:140%;">
            {{ form.coinName }} ({{ form.coinSymbol }})
            <br>
            <span v-if="form.minus_change == 'from_now'">
                price decreases by {{form.minus_percent}}%
                <br>
                compared to {{form.minus_compared}}
            </span>
            <span v-else-if="form.minus_change == '1h'">
                price decreases by {{form.minus_percent}}%
                <br>
                in 1 h period
            </span>
            <span v-else-if="form.minus_change == '24h'">
                price decreases by {{form.minus_percent}}%
                <br>
                in 24 h period
            </span>
        </div>
    </span>
    
    <span id="created-sing-or-plur">Alerts</span> will be delivered to: 
    <div style="height:2px;"></div>
    <span v-if="type == 'sms' || type == 'sms_per'" id="created-delivered-to" style="font-weight:bold;">{{form.phone}}</span>
    <span v-else-if="type == 'tg' || type == 'tg_per'" id="created-delivered-to" style="font-weight:bold;">@{{tg_user}}</span>
    <span v-else id="created-delivered-to" style="font-weight:bold;">{{form.email}}</span>

    <div style="height:33px;"></div>

    <div v-if="!store.userLoggedIn" id="created-sign-up">
        <router-link to="/register" class="blacklink"><b>Sign up</b></router-link> for a free Coinwink account
        <div style="height:25px;"></div>
    </div>

    <span v-if="store.userLoggedIn">
        <div id="created-manage-alerts-link">
            <router-link to="/manage-alerts" class="blacklink link-manage-alerts" style="font-size:13px;">Manage alerts</router-link>
        </div>
        <div style="height:25px;"></div>
    </span>

    <span @click="resetForm()" class="blacklink link-email new-crypto-alert-link" style="font-size:13px;">New alert</span>

    <div style="height:15px;"></div>
</template>