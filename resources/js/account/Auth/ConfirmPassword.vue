<template>
    <widget style="text-align:center;" :title="'Confirm your Password'">
        <!-- <template #logo>
            <jet-authentication-card-logo />
        </template> -->

        <div class="mb-4 text-sm text-gray-600">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        <!-- <jet-validation-errors :errors="errors" class="mb-4" /> -->

        <form @submit.prevent="submit">
            <div>
                Password
                <input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Confirm
                </button>
            </div>
        </form>
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
                form: this.$inertia.form({
                    password: '',
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.confirm'), {
                    onFinish: () => this.form.reset(),
                })
            }
        }
    }
</script>
