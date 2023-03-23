<template>
    <widget style="text-align:center;" :title="'Register'">
        <div class="widget-padding-25" >
            <!-- <jet-validation-errors :errors="errors" v-if="errors" class="mb-4" /> -->

            <form @submit.prevent="submit">
                <div class="auth-label">Email</div>
                <input id="email" type="email" class="auth-input" v-model="form.email" required />

                <div class="auth-label">Password</div>
                <input id="password" type="password" class="auth-input" v-model="form.password" required autocomplete="new-password" />

                <div class="auth-label">Confirm Password</div>
                <input id="password_confirmation" type="password" class="auth-input" v-model="form.password_confirmation" required autocomplete="new-password" />

                <div style="height:5px;"></div>

                <validation-errors :errors="errors" v-if="errors" />
                <div style="height:20px;" v-if="errors"></div>
                
                <button class="auth-button" :class="{ 'auth-button-disabled': form.processing }" :disabled="form.processing">
                    Register
                </button>
            </form>

            <div style="height:29px;"></div>
            
            <router-link to="/login" class="blacklink">Log in</router-link>

            <div style="height:5px;"></div>
        </div>
    </widget>
</template>

<script>
    import Widget from '../../widget/Widget.vue';
    import ValidationErrors from '../ValidationErrors';


    export default {
        components: {
            Widget,
            ValidationErrors
        },

        data() {
            return {
                form: {
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
                    processing: null,
                },
                errors: null,
            }
        },

        computed: {
            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },
        },

        methods: {            
            formProcessing() {
                if (this.form.processing) setTimeout(() => { this.form.processing = false; }, 100)
                else this.form.processing = true;
            },
            submit() {
                this.errors = null;
                this.formProcessing();
                
                // jQuery.ajax({
                //     type: 'POST',
                //     url: '/register',
                //     data: this.form
                // })
                
                axios({
                    method: 'post',
                    url: '/register',
                    data: this.form
                })
				.then((response) => {
                    console.log(response)
                    // this.formProcessing();
                    window.location = '/email/verify';
					// if (response.statusText == 'Created' || response.statusText == 'EmailVerify' || response.statusText == '') {
					// 	window.location = '/email/verify';
					// }
				})
				.catch((error) => {
                    this.formProcessing();
                    this.errors = error.response.data.errors;
				});
            }
        }
    }
</script>
