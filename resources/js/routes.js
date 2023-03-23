import NewEmailAlert from './views/NewEmailAlert';
import NewEmailPerAlert from './views/NewEmailPerAlert';
import NewSMSAlert from './views/NewSMSAlert';
import NewSMSPerAlert from './views/NewSMSPerAlert';
import NewTelegramAlert from './views/NewTelegramAlert';
import NewTelegramPerAlert from './views/NewTelegramPerAlert';
import ManageAlerts from './views/ManageAlerts.vue';
import Subscription from './views/Subscription.vue';

import IndividualCoin from './views/IndividualCoin.vue';

import Portfolio from './views/Portfolio.vue';
import Watchlist from './views/Watchlist.vue';

import Account from './account/Account.vue';
import AccountLogin from './account/Auth/Login.vue';
import AccountRegister from './account/Auth/Register.vue';
import ForgotPassword from './account/Auth/ForgotPassword.vue';
import ResetPassword from './account/Auth/ResetPassword.vue';
import VerifyEmail from './account/Auth/VerifyEmail.vue';


import About from './pages/About.vue';
import Press from './pages/Press.vue';
import Contacts from './pages/Contacts.vue';
import Pricing from './pages/Pricing.vue';


export default {
    routes: [

        // Views
        {
            path: '/',
            component: NewEmailAlert,
            name: 'EmailHome',
            meta: {
                title: 'Coinwink - Crypto Alerts for Bitcoin, Ethereum, and More'
            },
        },
        {
            path: '/email',
            component: NewEmailAlert,
            name: 'Email',
            meta: {
                title: 'Coinwink - Email Crypto Price Alert'
            },
        },
        {
            path: '/email-per',
            component: NewEmailPerAlert,
            name: 'EmailPer',
            meta: {
                title: 'Coinwink - Email Crypto Percentage Alert'
            },
        },
        {
            path: '/telegram',
            component: NewTelegramAlert,
            name: 'Telegram',
            meta: {
                title: 'Coinwink - Telegram Crypto Alerts'
            },
        },
        {
            path: '/telegram-per',
            component: NewTelegramPerAlert,
            name: 'TelegramPer',
            meta: {
                title: 'Coinwink - Telegram Crypto Percentage Alerts'
            },
        },
        {
            path: '/sms',
            component: NewSMSAlert,
            name: 'SMS',
            meta: {
                title: 'Coinwink - SMS Crypto Price Alert'
            },
        },
        {
            path: '/sms-per',
            component: NewSMSPerAlert,
            name: 'SMSPer',
            meta: {
                title: 'Coinwink - SMS Crypto Percentage Alert'
            },
        },
        {
            path: '/portfolio',
            component: Portfolio,
            name: 'Portfolio',
            meta: {
                title: 'Coinwink - Crypto Portfolio Tracker App'
            },
        },
        {
            path: '/watchlist',
            component: Watchlist,
            name: 'Watchlist',
            meta: {
                title: 'Coinwink - Crypto Watchlist App for 3500+ Cryptocurrencies'
            },
        },
        {
            path: '/manage-alerts',
            component: ManageAlerts,
            name: 'ManageAlerts',
            meta: {
                title: 'Coinwink - Manage Alerts'
            },
        },
        {
            path: '/subscription',
            component: Subscription,
            name: 'Subscription',
            meta: {
                title: 'Coinwink - Subscribe to Premium Plan'
            },
        },

        // Account
        {
            path: '/account',
            component: Account,
            name: 'Account',
            meta: {
                title: 'Coinwink - My Account'
            },
        },
        {
            path: '/login',
            component: AccountLogin,
            name: 'Login',
            meta: {
                title: 'Coinwink - Login'
            },
        },
        {
            path: '/register',
            component: AccountRegister,
            name: 'Register',
            meta: {
                title: 'Coinwink - Sign Up for a Free Account'
            },
        },
        {
            path: '/forgot-password',
            component: ForgotPassword,
            name: 'ForgotPassword',
            meta: {
                title: 'Coinwink - Password Recovery'
            },
        },
        {
            path: '/password/reset/:token',
            component: ResetPassword,
            name: 'ResetPassword',
            meta: {
                title: 'Coinwink - Create a New Password'
            },
        },
        {
            path: '/email/verify',
            component: VerifyEmail,
            name: 'EmailVerify',
            meta: {
                title: 'Coinwink - Verify Your Email Address'
            },
        },

        // Pages
        {
            path: '/about',
            component: About,
            name: 'About',
            meta: {
                title: 'Coinwink - About'
            },
        },
        {
            path: '/press',
            component: Press,
            name: 'Press',
            meta: {
                title: 'Coinwink - Press Kit'
            },
        },
        {
            path: '/contacts',
            component: Contacts,
            name: 'Contacts',
            meta: {
                title: 'Coinwink - Contacts'
            },
        },
        {
            path: '/pricing',
            component: Pricing,
            name: 'Pricing',
            meta: {
                title: 'Coinwink - Pricing'
            },
        },

        {
            path: '/:slug',
            component: IndividualCoin,
            name: 'IndividualCoin',
        },
    ],
};