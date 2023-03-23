<script setup>
    import { ref } from 'vue';

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
    <div style="padding-left:20px;padding-right:20px;">
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
</template>