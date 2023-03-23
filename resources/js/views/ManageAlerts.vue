<script setup>
    import { store } from '../store.js';

    import DropDownCoins from '../components/DropDownCoins.vue';
    import LoadingSpinner from '../components/LoadingSpinner.vue';

    import Widget from '../widget/Widget.vue';

    import { onMounted, onBeforeUnmount, watch } from 'vue';

    const cw_theme = window.cw_theme;

    onBeforeUnmount(() => {
        jQuery("#manage_alerts_acc_feedback").html('');
    })

    if (store.userLoggedIn) {
        if (cw_cmc) {
            ajaxSubmit_acc();
        }
        else {
            watch(store, (selection, prevSelection) => { 
                ajaxSubmit_acc();
            })
        }
    }

</script>

<template>
    <widget style="text-align:center;" :title="'Manage Alerts'">
        <span v-if="store.userLoggedIn">
            <div class="content">
                <div style="height:5px"></div>

                <form method="post" id="manage_alerts_acc_form" action="">
                    <!-- <input type="hidden" name="unique_id" value="55555555555555555555555555555"> -->
                    <input type="hidden" name="action" value="manage_alerts_acc">
                </form>

                <div id="manage_alerts_acc_loader">
                    <div v-if="cw_theme != 'metaverse'" style="height:15px;"></div>
                    <LoadingSpinner :theme='cw_theme' />
                    <div v-if="cw_theme == 'metaverse'" style="height:10px;"></div>
                </div>

                <div id="manage_alerts_acc_feedback"></div>

                <div style="height:5px"></div>
            </div>
        </span>
        <span v-else>
            <div class="content">
                <div style="height:60px;"></div>

                Log in to manage your crypto alerts
                <br>
                <div style="height:8px;"></div>
                <router-link to="/login">
                    <input type="submit" class="button-acc hashLink" value="Log in">
                </router-link>

                <div style="height:10px;"></div>

                <router-link to="/forgot-password" class="blacklink hashLink">Password recovery</router-link>
                        
                <div style="height:55px;"></div>

                Create a free Coinwink account
                <br>
                <div style="height:8px;"></div>
                <router-link to="/register">
                    <input type="submit" class="hashLink button-acc" value="Sign up">
                </router-link>

                <div style="height:13px;"></div>
                    
            </div>
        </span>
    </widget>
</template>

<style>
</style>