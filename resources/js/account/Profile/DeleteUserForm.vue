<template>
    <div style="height:25px;"></div>
    Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
    <br><br>
    Please enter your password to confirm that you would like to delete your account.

    <div style="height:10px;"></div>

    <input type="password" ref="password" v-model="form.password" @keyup.enter="deleteUser" class="auth-input" style="width:240px;height:28px;" id="password-input" />

    <div v-if="feedback" style="margin-top:5px;margin-bottom:15px;color:red;font-weight:bold;" v-html="feedback"></div>
    
    <button @click="deleteUser()" :class="{ 'opacity-25': processing }" :disabled="processing" style="width:137px;height:27px;" class="button-account">
        Delete Account
    </button>

    <div style="height:12px;"></div>
</template>

<script>

    export default {
        components: {
            
        },

        data() {
            return {
                confirmingUserDeletion: false,

                form: {
                    password: '',
                },
                feedback: '',
                processing: null,
            }
        },

        mounted() {
            
        },

        methods: {
            formProcessing() {
                if (this.processing) setTimeout(() => { this.processing = false; }, 100)
                else this.processing = true;
            },
            confirmUserDel() {
                this.confirmingUserDeletion = true;

                setTimeout(() => this.$refs.password.focus(), 250)
            },

            deleteUser() {
                this.formProcessing();
                this.feedback = '';
                if (this.form.password == '') {
                    this.processing = false;
                    this.feedback = "Please enter your password";
                    document.getElementById('password-input').focus()
                    return;
                }
                if (!confirm("Are you sure you want to permanently delete your account?")) {
                    this.processing = false;
                    return;
                }

                // jQuery.ajax({
                //     type: 'post',
                //     url: '/user/delete',
                //     data: this.form
                // })

                axios({
                    method: 'post',
                    url: '/user/delete',
                    data: this.form
                })          
				.then((response) => {
                    // console.log(response)
                    if(response.data == 'success') {
                        window.location = '/';
                    }
                    else if (response.data == 'Wrong password') {
                        this.formProcessing();
                        this.feedback = 'Wrong password!';
                    }
				})
				.catch((error) => {
                    this.formProcessing();
					console.log(error);
                    if (error.response.statusText == 'Conflict' || error.response.statusText == '') {
                        // jQuery.ajax({
                        //     type: 'POST',
                        //     url: '/logout',
                        // })
                        
						axios.post('/logout')
                        .then(response => {
                            window.location = '/';
                        })
					}
                    else {
                        this.errors = error.response.data.errors;
                        this.form.password = '';
                        this.$refs.password.focus();
                    }
				});
            },

            closeModal() {
                this.confirmingUserDeletion = false

                this.form.password = '';
            },
        },
    }
</script>