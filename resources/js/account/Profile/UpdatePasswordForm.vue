<template>
    <div style="height:25px;"></div>

    <form @submit="updatePassword">

        <div class="auth-label">Current Password</div>
        <input id="current_password" type="password" v-model="form.current_password" ref="current_password" required style="width:240px;height:28px;" />

        <div style="height:10px;"></div>

        <div class="auth-label">New Password</div>
        <input id="password" type="password" v-model="form.password" ref="password" required style="width:240px;height:28px;" />

        <div style="height:10px;"></div>

        <div class="auth-label">Confirm Password</div>
        <input id="password_confirmation" type="password" v-model="form.password_confirmation" required style="width:240px;height:28px;"/>

        <div style="height:10px;"></div>

        <div v-if="status" class="auth-status">
            {{ status }}
        </div>
        
        <div v-if="errors" class="auth-errors">
            <validation-errors :errors="errors"  />
        </div>

        <div style="height:10px;"></div>

        <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" style="width:137px;height:27px;" class="button-account">
            Save
        </button>
    </form>
    
    <div style="height:10px;"></div>
</template>

<script>
    import ValidationErrors from '../ValidationErrors';

    export default {
        components: {
            ValidationErrors
        },

        data() {
            return {
                form: {
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                    recentlySuccessful: null,
                    processing: null,
                },
                errors: false,
                status: false,
            }
        },

        methods: {
            formProcessing() {
                if (this.form.processing) setTimeout(() => { this.form.processing = false; }, 100)
                else this.form.processing = true;
            },
            formRecentlySuccessful() {
                this.form.recentlySuccessful = true;
                setTimeout(() => { this.form.recentlySuccessful = false; }, 3000);
            },
            resetInputs() {
                this.form.current_password = '';
                this.form.password = '';
                this.form.password_confirmation = '';
                document.getElementById("current_password").focus();
            },
            errorsReset() {
                this.errors.current_password = '';
                this.errors.password = '';
                this.errors.password_confirmation = '';
            },
            updatePassword(e) {
                e.preventDefault();
                this.errors = false;
                this.status = '';
                this.formProcessing();

                axios({
                    method: 'put',
                    url: '/user/password',
                    data: this.form
                })
				.then((response) => {
                    this.formProcessing();
                    console.log(response);
                    this.status = "Password updated!";
                    this.resetInputs();
				})
				.catch((error) => {
                    // console.log(error);
                    this.formProcessing();
                    this.errors = error.response.data.errors;
				});

                // var http = new XMLHttpRequest();
                // var token = document.querySelector('meta[name="csrf-token"]').content;
                // var url = '/user/password';
                // var params = JSON.stringify(this.form);
                // http.open('put', url, true);

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
                
            },
        },
    }
</script>
