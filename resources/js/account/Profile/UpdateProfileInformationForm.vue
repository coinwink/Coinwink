<template>
    <jet-form-section @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <jet-input-error :message="errors.name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                <jet-input-error :message="errors.email" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        props: ['user'],

        data() {
            return {
                form: {
                    name: null,
                    email: null,
                    recentlySuccessful: null,
                    processing: null,
                },
                errors: {
                    name: '',
                    email: '',
                },
            }
        },

        mounted() {
            this.form.name = this.user.name;
            this.form.email = this.user.email;
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
            resetErrors() {
                this.errors.name = '';
                this.errors.email = '';
            },
            updateProfileInformation() {
                this.formProcessing();
                // axios({
                //     method: 'put',
                //     url: '/user/profile-information',
                //     data: this.form
                // })
                
                jQuery.ajax({
                    type: 'PUT',
                    url: '/user/profile-information',
                    data: this.form
                })
				.then((response) => {
                    // console.log(response);
					if (response.statusText == 'OK' || response.statusText == '') {
                        this.resetErrors();
						this.formRecentlySuccessful();
                        this.formProcessing();
					}
				})
				.catch((error) => {
                    this.errors = error.response.data.errors;
                    this.formProcessing();
				});
            },

        },
    }
</script>
