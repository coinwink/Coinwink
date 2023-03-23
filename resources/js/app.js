import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';

const router = createRouter({
    history: createWebHistory(),
    routes: routes.routes,
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 }
    },
});


router.beforeEach(async(to, from) => {
    // Redirect unverified users to email verification view
    if (userVerified === '' && to.name != 'EmailVerify') {
        return '/email/verify';
    }
    // Redirect loggedIn users from guest auth to account view
    else if (userVerified != null) {
        if (to.name == 'Register' || to.name == 'ForgotPassword' || to.name == 'Login') {
            return '/account';
        }
    }

    // SEO title tags
    if (to.name != 'IndividualCoin') {
        document.title = to.meta.title;
    }
    
    // if (to.name == 'IndividualCoin') {
    //     var coin_name = '';
    //     for(var i = 0; i < cw_cmc.length; i++) {
    //         var coin = cw_cmc[i];
    //         if (to.params.slug.toUpperCase() == coin['symbol']) {
    //           coin_name = coin['name']; // current coin in loop slug
    //         }
    //     }
    //     document.title = coin_name +" ("+ to.params.slug.toUpperCase() +") Price Alerts, Watchlist and Portfolio App";
    // }
    // else {
    //     document.title = to.meta.title;
    // }
});


window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { createApp } from 'vue'
import App from './App.vue'

const app = createApp(App).use(router)
app.mount('#app')