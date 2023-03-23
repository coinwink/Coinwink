<script setup>
import { ref } from 'vue';

var emailProvider = user_email.substring(
    user_email.indexOf("@") + 1, 
    user_email.lastIndexOf(".")
);
emailProvider = emailProvider.toLowerCase();
// console.log(emailProvider)
let emailProviderName = ref(null);
let emailSpamNotice = ref(false);

switch (emailProvider) {
	case 'yahoo':
		emailSpamNotice.value = true;
        emailProviderName.value = "Yahoo";
		break;
	case 'outlook':
		emailSpamNotice.value = true;
        emailProviderName.value = "Outlook";
		break;
	case 'msn':
		emailSpamNotice.value = true;
        emailProviderName.value = "MSN";
		break;
	case 'live':
		emailSpamNotice.value = true;
        emailProviderName.value = "Live";
		break;
	case 'aol':
		emailSpamNotice.value = true;
        emailProviderName.value = "AOL";
		break;
	case 'hotmail':
		emailSpamNotice.value = true;
        emailProviderName.value = "Hotmail";
		break;
	case 'att':
		emailSpamNotice.value = true;
        emailProviderName.value = "AT&T";
		break;
	case 'ymail':
		emailSpamNotice.value = true;
        emailProviderName.value = "Ymail";
		break;
	case 'icloud':
		emailSpamNotice.value = true;
        emailProviderName.value = "iCloud";
		break;
}
</script>

<template>
    <widget style="text-align:center;" :title="'Email Verification'">
        <div style="padding-left:25px;padding-right:25px;padding-top:25px;">
            <div v-if="userVerified">
                <div style="height:10px;"></div>
                <b style="color:green;">Your email is verified!</b>
                <div style="height:10px;"></div>
            </div>
            <div v-else>
                <div>
                    Thanks for signing up!
                    <br><br>
                    Please verify your email address by clicking on the link we emailed to you. If you didn't receive the email, we will gladly send you another.
                </div>

                <div v-if="verificationLinkSent" style="font-weight:bold;color:#00a600;">
                    A new verification link has been sent to the email address you provided during registration.
                </div>

                <div v-if="emailSpamNotice">
                    <div style="height:25px;"></div>
                    <div id="notice" style="width:280px;margin:0 auto;">
                        <b style="font-size:14px;">Attention!</b>
                        <div style="height:8px;"></div>
                        <span style="color:#ff0000;">For the {{emailProviderName}} email, check your Spam folder and mark the message as Not Spam.</span>
                        <div style="height:5px;"></div>
                    </div>
                </div>

                <div style="height:10px;"></div>

                <div v-if="status" class="auth-status">
                    {{ status }}
                </div>
                
                <div v-if="errors" class="auth-errors">
                    Too many requests!
                </div>

                <div style="height:10px;"></div>

                <form @submit.prevent="submit">
                    <button :class="{ 'auth-button-disabled': form.processing }" :disabled="form.processing" class="auth-button">
                        Resend Email
                    </button>
                </form>
                
                <div style="height:15px;"></div>
                <span @click="logout()" class="auth-link blacklink">Log out</span>
                <div style="height:5px;"></div>
            </div>

        </div>
    </widget>
</template>

<script>
    import Widget from '../../widget/Widget.vue';


    export default {
        components: {
            Widget
        },

        data() {
            return {
                form: {
                    processing: null,
                },
                status: null,
                errors: null,
                userVerified: window.userVerified,
            }
        },

        methods: {  
            formProcessing() {
                if (this.form.processing) setTimeout(() => { this.form.processing = false; }, 100)
                else this.form.processing = true;
            },
            submit() {
                this.formProcessing();
                this.status = null;
                this.errors = null;

                axios({
                    method: 'POST',
                    url: '/email/verification-notification',
                    data: this.form
                })
                .then((response) => {
                    // console.log(response);
                    this.status = "Email sent!"
                    this.formProcessing();
				})
				.catch((error) => {
                    console.log(error);
                    this.errors = true;
                    this.formProcessing();
				});
            },
            logout() {
                // jQuery.ajax({
                //     type: 'POST',
                //     url: '/logout'
                // })

                axios.post('/logout')
                .then(response => {
                    window.location = '/';
                })
            },
        },

        computed: {
            verificationLinkSent() {
                return this.status === 'verification-link-sent';
            }
        }
    }
</script>
