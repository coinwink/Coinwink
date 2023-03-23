<template>
    <widget style="text-align:center;" :title="'Password Reset'">

        <div class="widget-padding-25">

            <form @submit.prevent="submit">
                <div class="auth-label">Email</div>
                <input id="email" type="email" class="auth-input" v-model="form.email" required  />

                <div class="auth-label">Password</div>
                <input id="password" type="password" class="auth-input" v-model="form.password" required autocomplete="new-password" autofocus />

                <div class="auth-label">Confirm Password</div>
                <input id="password_confirmation" type="password" class="auth-input" v-model="form.password_confirmation" required autocomplete="new-password" />

                <div style="height:10px;"></div>

                <!-- <div v-if="status" style="font-weight:bold;color:#00a600;">
                    {{ status }}
                    <div style="height:10px;"></div>
                </div> -->
                
                <div v-if="errors">
                    <validation-errors :errors="errors"  />
                    <div style="height:20px;"></div>
                </div>

                <button :class="{ 'auth-button-disabled': form.processing }" :disabled="form.processing" class="auth-button">
                    Reset Password
                </button>

                <div style="height:10px;"></div>
            </form>
            
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
                    token: this.$route.params.token,
                    email: this.$route.query.email,
                    password: '',
                    password_confirmation: '',
                    processing: null,
                },
                errors: null,
                // status: null,
            }
        },

        methods: {
            resetInputs() {
                this.form.password = '';
                this.form.password_confirmation = '';
                document.getElementById("password").focus();
            },
            formProcessing() {
                if (this.form.processing) setTimeout(() => { this.form.processing = false; }, 100)
                else this.form.processing = true;
            },
            submit() {
                this.formProcessing();
                // this.errors = null;
                // this.status = null;
                
                // jQuery.ajax({
                //     type: 'POST',
                //     url: '/reset-password',
                //     data: this.form
                // })

                axios({
                    method: 'post',
                    url: '/reset-password',
                    data: this.form
                })
				.then((response) => {
                    console.log(response);
                    // console.log("ZZZ")
					if (response.statusText == 'OK' || response.statusText == '') {
                        // this.status = "Redirecting..."
                        window.location = '/login';
                        // this.formProcessing();
					}
				})
				.catch((error) => {
					// console.log(error.response);
                    this.errors = error.response.data.errors;
                    this.resetInputs();
                    this.formProcessing();
				});


                
                // var http = new XMLHttpRequest();
                // var token = document.querySelector('meta[name="csrf-token"]').content;
                // var url = '/reset-password';
                // var params = JSON.stringify(this.form);
                // http.open('post', url, true);

                // //Send the proper header information along with the request
                // http.setRequestHeader('Content-type', 'application/json');
                // http.setRequestHeader('Accept', 'application/json, text/plain, */*');
                // http.setRequestHeader('X-CSRF-TOKEN', token);

                // http.onreadystatechange = function() {//Call a function when the state changes.
                //     if(http.readyState == 4 && http.status == 200) {
                //         console.log(http.responseText);
                //     }
                // }
                // http.send(params);
            }
        }
    }
</script>
