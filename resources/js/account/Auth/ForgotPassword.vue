<template>
    <widget style="text-align:center;" :title="'Password Recovery'">

        <div style="padding-left:20px;padding-right:20px;padding-top:25px;">
            <div>
                Forgot your password?
                <br><br>
                Let us know your email address and we will send you a password reset link that will allow you to set a new password.
            </div>

            <div style="height:10px;"></div>

            <div v-if="status" class="auth-status">
                {{ status }}
            </div>
            
            <div v-if="errors" class="auth-errors">
                <validation-errors :errors="errors"  />
            </div>

            <div style="height:20px;"></div>

            <form @submit.prevent="submit">
                <div>
                    <div class="auth-label">Email</div>
                    <input id="email" type="email" class="auth-input" v-model="form.email" required autofocus />
                </div>

                <button :class="{ 'auth-button-disabled': form.processing }" :disabled="form.processing" class="auth-button" >
                    Submit
                </button>
            </form>

            <div style="height:10px;"></div>
        </div>
    </widget>
</template>

<script>
    import Widget from '../../widget/Widget.vue';
    import ValidationErrors from '../ValidationErrors'


    export default {
        components: {
            Widget,
            ValidationErrors
        },

        data() {
            return {
                form: {
                    email: '',
                    processing: null,
                },
                status: null,
                errors: null,
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

                // jQuery.ajax({
                //     type: 'POST',
                //     url: '/forgot-password',
                //     data: this.form
                // })
                
                axios({
                    method: 'post',
                    url: '/forgot-password',
                    data: this.form
                })
				.then((response) => {
                    // console.log(response)
                    // this.status = "Email sent, please check your mailbox."
                    this.status = response.data.message;
                    this.formProcessing();
				})
				.catch((error) => {
                    // console.log(error.response)
                    this.errors = error.response.data.errors;
                    this.formProcessing();
				});
            }
        }
        
    }
</script>
