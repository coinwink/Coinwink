<script setup>
    import Widget from '../widget/Widget.vue';
    import WidgetInner from '../widget/WidgetInner.vue';

    import UpdatePasswordForm from './Profile/UpdatePasswordForm.vue';
    import DeleteUserForm from './Profile/DeleteUserForm.vue';
    import Feedback from './Feedback.vue';

	import Register from './Auth/Register.vue';

    import { store } from '../store';
    import { ref, onMounted } from 'vue';

    let subs = ref(window.subs);
    let plan = ref(window.subs_plan);
    let status = ref(window.status);
    let sms = ref(window.sms);
    let months = ref(window.months);
    let date_renewed = ref(window.date_renewed);
	const id_user = ref(window.id_user);
	const cw_theme = ref(window.cw_theme);
	const date_end = window.date_end;
	let showDeleteAccount = ref(false);
	let showChangePassword = ref(false);
	let showExtraCredits = ref(false);
	let showCancelSubs = ref(false);
	let showFeedback = ref(false);

	// TESTING
	// status = 'cancelled';
	// status = 'suspended';
	// status = 'active';
	// subs = 0;
	// months = 5;

    function logOut() {
		jQuery.ajax({
			type: 'POST',
			url: '/logout',
		})
		.then(response => {
            window.location = '/';
        })
    }

	onMounted(() => {
		if (userLoggedIn) {
			// THEME
			if (cw_theme.value == 'classic') {
				document.getElementById("themeClassic").checked = true;
			}
			else if (cw_theme.value == 'matrix') {
				document.getElementById("themeMatrix").checked = true;
			}
			else if (cw_theme.value == 'metaverse') {
				document.getElementById("themeMetaverse").checked = true;
			}

			if (cw_theme.value == 'matrix') {
				if (t_s != '0') {
					document.getElementById("static-check").checked = true;
				}
			}
		}
	});
</script>

<template>
	<Register v-if="!store.userLoggedIn" />
    <widget :title="'Account'" style="text-align:center;" v-else>
        <div style="padding-left:25px;padding-right:25px;line-height:160%;">
            <div style="height:30px;" class="spacer-account"></div>

                <widget-inner :title="'Subscription'">
                    <span style="font-size:14px;">
						Your current plan: 
						<b v-if="subs == 0 && status != 'suspended'">Free</b>
						<b v-else-if="subs == 1 && status == 'active' && plan == 'premium'">Premium</b>
						<b v-else-if="subs == 1 && status == 'active' && plan == 'standard'">Standard</b>
						<b v-else-if="subs == 1 && status == 'cancelled'">Cancelled</b>
						<b v-else-if="subs == 0 && status == 'suspended'">Suspended</b>
					</span>
                    
					<br>
                    <br> 
					
					<span v-if="subs == 0 && status != 'suspended'">
						5 active Email alerts<br>
						5 active Telegram alerts<br>
						<!-- 5 active Portfolio alerts<br> -->
						5 coins in Portfolio<br>
						5 coins in Watchlist
						<br><br>
						<router-link to="/subscription" class="blacklink blacklink-acc">Upgrade</router-link>
					</span>

					<span v-else-if="subs == 0 && status == 'suspended'">
						Your paid plan is temporarily suspended because our payments provider Stripe was unable to charge your credit card and extend the subscription.
						<br><br>
						To update your billing information, please visit our <a href="https://billing.stripe.com/p/login/28o7vH6lIdG5dIQ6oo" target="_blank" class="blacklink blacklink-acc">customer portal</a>.
						<br><br>
						Stripe will try to charge your credit card again during the next few days. If the payment is received, the paid plan features will be re-enabled automatically.
						<br><br>
						Create a new subscription:
						<div style="height:5px;"></div>
						<span class="new-subs-link"><span class="blacklink blacklink-acc" onclick="stripeCheckoutNew('premium')">Premium</span> | <span class="blacklink blacklink-acc" onclick="stripeCheckoutNew('standard')">Standard</span></span>
						<span id="new-subs-wait" style="display:none;">Please wait...</span>
						<div style="height:5px;"></div>
					</span>

					<span v-if="subs == 1 && status == 'active' && plan == 'premium'">
						Unlimited Email alerts<br>
						Unlimited Telegram alerts<br>
						<!-- Unlimited Portfolio alerts<br> -->
						Unlimited coins in Portfolio<br>
                        Unlimited coins in Watchlist<br>
						SMS left this month: {{sms}}
						<br>
						<br>
						<span style="cursor:pointer;text-decoration:underline;" class="blacklink blacklink-acc" id="extracredits" @click="showExtraCredits = !showExtraCredits">Get more SMS</span>

						<div id="extracreditsdiv" v-if="showExtraCredits">
							<div style="height:20px;"></div>
							You can buy packs of additional SMS credits for an ongoing month.
							<br><br>
							<div id="credits-buy-button" style="height:24px;">
								<input type="submit" onclick="stripeBuy100Credits()" id="buy_100_credits_button" style="width: 147px;height: 27px;" value="Buy 100 credits" />
							</div>
						</div>
						
						<br>
						<br>
						
						<span v-if="subs == 1 && status == 'active' && months > 1">
							Subscription renewed on: <br>{{date_renewed}}
							<br>
						</span>

						<span style="cursor:pointer;text-decoration:underline;" class="blacklink blacklink-acc" id="cancelsubs" @click="showCancelSubs = !showCancelSubs">Cancel subscription</span>
						
						<br>

						<div id="cancelsubsdiv" v-if="showCancelSubs">
							<div style="height:20px;"></div>
							If you cancel your subscription, you will be able to receive SMS alerts and use other Premium features until the end of the ongoing month.
							<br><br>
							<input type="submit" id="cancel_subs_button" onclick="stripeCancelSubs()" style="width: 147px;height: 27px;" value="Cancel subscription" />
							<div style="height:10px;"></div>
						</div>

						<br>

						Support:<br>
						&#x73;&#x75;&#x70;&#x70;&#x6f;&#x72;&#x74;&#x40;&#99;&#111;&#105;&#110;&#119;&#105;&#110;k&#46;co&#x6d;
					</span>

					<span v-if="subs == 1 && status == 'active' && plan == 'standard'">
						Unlimited Email alerts<br>
						Unlimited Telegram alerts<br>
						<!-- Unlimited Portfolio alerts<br> -->
						Unlimited coins in Portfolio<br>
                        Unlimited coins in Watchlist
						<br>
						<br>
						
						<span v-if="subs == 1 && status == 'active' && months > 1">
							Subscription renewed on: <br>{{date_renewed}}
							<br>
							<br>
						</span>
						
						<span style="cursor:pointer;text-decoration:underline;" class="blacklink blacklink-acc" id="cancelsubs" @click="showCancelSubs = !showCancelSubs">Cancel subscription</span>
						
						<br>

						<div id="cancelsubsdiv" v-if="showCancelSubs">
							<div style="height:20px;"></div>
							If you cancel your subscription, you will have access to all of the features included in the Standard plan until the end of the ongoing month.
							<br><br>
							<input type="submit" id="cancel_subs_button" onclick="stripeCancelSubs()" style="width: 147px;height: 27px;" value="Cancel subscription" />
							<div style="height:10px;"></div>
						</div>

						<br>

						Support:<br>
						&#x73;&#x75;&#x70;&#x70;&#x6f;&#x72;&#x74;&#x40;&#99;&#111;&#105;&#110;&#119;&#105;&#110;k&#46;co&#x6d;
					</span>

					<span v-if="subs == 1 && status == 'cancelled'">
						<div style="margin-bottom:5px;">
							Your subscription was cancelled.
							<br><br>
							You can continue using the <span style="text-transform: capitalize;">{{plan}}</span> plan features until {{ date_end }}. After that, your account will automatically switch to the Free plan.
							<br><br>
							Create a new subscription:
							<div style="height:5px;"></div>
							<span class="blacklink blacklink-acc new-subs-link" onclick="stripeCheckoutNew('premium')">Premium</span><div style="height:5px;"></div><span class="blacklink blacklink-acc new-subs-link" onclick="stripeCheckoutNew('standard')">Standard</span>
							<span id="new-subs-wait" style="display:none;">Please wait...</span>
						</div>
					</span>
					
                </widget-inner>
                
                <widget-inner :title="'Delete Account'">
					Permanently delete your account.
					<br><br>
                    <span  class="blacklink blacklink-acc" @click="showDeleteAccount = !showDeleteAccount">Delete Account</span>

					<DeleteUserForm v-if="showDeleteAccount" />
                </widget-inner>

                <widget-inner :title="'Update Password'">
					Ensure your account is using a long, random password to stay secure.
					<br><br>
                    <span  class="blacklink blacklink-acc" @click="showChangePassword = !showChangePassword">Change Password</span>

					<UpdatePasswordForm v-if="showChangePassword" />
                </widget-inner>

                <!-- <widget-inner :title="'Tips & Tricks'">
                    <span id="tt-container">
                        You can drag & drop coins in your watchlist to change their order.
                    </span>
                    <br>
                    <br>
                    <u onclick="nexttt()" style="cursor:pointer;">Next</u>
                </widget-inner> -->
                
                <widget-inner :title="'Themes'">
                    <div style="width:109px;margin:0 auto;text-align:left;letter-spacing:1px;padding-left:10px;">
						
                        <div class="appify-radio">
                            <input onclick="themeClassic()" id="themeClassic" type="radio" class="appify-radio-input themeClassic" value="classic"/>
                            <label for="themeClassic" class="themeClassic rad">
                                <div class="appify-radio-box">  
                                    <svg><use xlink:href="#radiomark" /></svg>
                                </div>
                                <span class="appify-radio-label noselect">Classic</span>
                            </label>
                        </div>

                        <div style="clear:both;height:8px;"></div>

                        <div class="appify-radio">
                            <input onclick="themeMetaverse()" id="themeMetaverse" type="radio" class="appify-radio-input themeMetaverse" value="metaverse"/>
                            <label for="themeMetaverse" class="themeMetaverse">
                                <div class="appify-radio-box">  
                                    <svg><use xlink:href="#radiomark" /></svg>
                                </div>
                                <span class="appify-radio-label noselect">Metaverse</span>
                            </label>
                        </div>

                        <div style="clear:both;height:8px;"></div>

                        <div class="appify-radio">
                            <input onclick="themeMatrix()" id="themeMatrix" type="radio" class="appify-radio-input themeMatrix" value="matrix"/>
                            <label for="themeMatrix" class="themeMatrix">
                                <div class="appify-radio-box">  
                                    <svg><use xlink:href="#radiomark" /></svg>
                                </div>
                                <span class="appify-radio-label noselect">Matrix</span>
                            </label>
                        </div>

                        <div style="clear:both;height:5px;"></div>

                    </div>

                    <span v-if="cw_theme == 'matrix'">

                        <div style="height:10px;"></div>

                        <div style="clear:both;width:220px;border:1px solid #272727;margin:0 auto;padding-top:12px;margin-bottom:5px;padding-bottom:12px;border-radius:3px;line-height:160%;background:black;" class="noselect">
                            
                            <div style="display:grid;grid-template-columns:0.66fr 1fr;">
                                <div style="display:grid;grid-template-columns:57px 25px;">
                                    <div style="text-align:right;padding-right:5px;">
                                        Static:
                                    </div>
                                    <div>
                                        <div class="appify-checkbox" style="width:231px;margin:0 auto;">
                                            <input id="static-check" name="portfolio-alert-3" type="checkbox" class="appify-input-checkbox" onclick="themeStatic()" />
                                            <label for="static-check">
                                                <div class="checkbox-box">  
                                                    <svg><use xlink:href="#checkmark" /></svg>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div style="display:grid;grid-template-columns:63px 50px;">
                                    <div style="text-align:right;padding-right:4px;">
                                        Intensity:
                                    </div>
                                    <div style="display:grid;grid-template-columns:23px 8px 23px;">
                                        <div>
                                            <button onclick="lessTransp()" class="plus-minus" style="width:21px;height:21px;margin-bottom:0px!important;margin-top:-2px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Remove</title><polygon points="155.51 463.46 155.51 386.06 694.01 386.06 694.01 463.46 155.51 463.46 155.51 463.46" class="plus-minus-svg" /></svg>
                                            </button>
                                        </div>
                                        <div></div>
                                        <div>
                                            <button onclick="moreTransp()" class="plus-minus" style="width:21px;height:21px;margin-bottom:0px!important;margin-top:-2px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Add</title><polygon points="392.51 155.51 457.01 155.51 457.01 392.51 694.01 392.51 694.01 457.01 457.01 457.01 457.01 694.01 392.51 694.01 392.51 457.01 155.51 457.01 155.51 392.51 392.51 392.51 392.51 155.51 392.51 155.51" class="plus-minus-svg" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </span>
                </widget-inner>

                <widget-inner :title="'Feedback'">
					Let us know your feedback by submitting a quick message.
					<br><br>
					<span class="blacklink blacklink-acc" @click="showFeedback = true">Send Feedback</span>
				</widget-inner>
				
				<feedback :show-feedback="showFeedback" @show-feedback="showFeedback = false" />

				<div style="height:10px;"></div>
                Logged in as:
                <br>
                {{ store.userEmail }}
                <br><br>
                <span @click="logOut"  class="blacklink blacklink-acc">Log Out</span>
				<div style="height:5px;"></div>

        </div>
    </widget>

    
    <div class="transition-matrix">
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>

        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>

        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>
        <div class="black-stripe"></div>

        <div class="t-centered" id="t-centered"></div>
		<div class="theme-transition-over bg-metaverse"></div>
    </div>

</template>

<style scoped>
    .link-default {
        color:white;
        cursor: pointer;
    }

    .link-default:hover {
        text-decoration: underline;
    }
    

	/* SCOPED: Account only */

	.np-modal-close {
		position: absolute;
		cursor: pointer;
		font-size: 22px;
		font-weight: bold;
		color: #777;    
		right: 10px;
		top: 9px;
		width: 30px;
		height: 30px;
	}

	.np-modal-close:hover {
		color: #999;
	}

	#np-modal-close:focus div {
		color: #999;
	}

	.np-modal-window {
		height: 100%;
		width: 100%;
		position: fixed;
		z-index: 999;
		left: 0;
		top: 0;
		background-color: rgb(0,0,0);
		background-color: rgba(0,0,0, 0.95);
		overflow-x: hidden;
		box-sizing: content-box;
		align-items: center;
	}

	.cw-feedback {
		color: white;
		font-size:14px;
		font-weight: normal;
		background-color: black;
		border: 1px solid #333;
		border-radius: 5px;
		height: auto;
		padding: 40px 15px 45px 15px;
		width: 560px;
		max-width: 95%;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -51%);
		text-align: center;
		box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.5);
	}

	.cw-feedback-input {
		margin-top: 10px;
		margin-bottom: 15px;
		/* background-color: #2b3845; */
		background-color: #151a1f;
		color: white;
		border: 1px solid #666;
		padding: 10px;
		font-size: 14px;
		font-family: inherit;
		max-width:400px;
		width:95%;
		height:240px;
		resize: none;
	}

	.cw-feedback-input:invalid {
		box-shadow: none;
	}

	.cw-feedback-input::-webkit-scrollbar {
		width: 10px;
	}

	.cw-feedback-input::-webkit-scrollbar-thumb {
		background-color: #555;
	}

	.cw-feedback-input::-webkit-scrollbar-thumb:hover {
		background-color: #555;
	}

	.cw-feedback-input:hover, .cw-feedback-input:focus {
		border: 1px solid #888!important;
		outline: 0;
	}

	.btn-feedback-submit {
		width:130px;
		height: 40px;
		/* padding-bottom:6px; */
		border:1px solid #666;
		cursor:pointer;
		margin:0 auto;
		font-size:15px;
		color:#999;
		border-radius: 5px;
		background-color: black;
		font-family: 'Montserrat', sans-serif;
		font-weight: bold;
	}

	.btn-feedback-submit:hover, .btn-feedback-submit:active {
		border:1px solid #999;
		color: #aeaeae;
		outline: none;
	}

	.btn-feedback-submit:focus {
		outline: none;
		border:1px solid #999;
		color: #999;
	}

	#feedback-error {
		display: none;
	}

	#feedback-success {
		display: none;
	}


	/* Themes Transition */

	.transition-matrix {
		position: fixed;
		height: 100%;
		width: 100%;
		display: grid;
		grid-template-columns: repeat(30, 1fr);
		top: 0;
		left: 0;
		z-index: -999;
	}

	.black-stripe {
		height: 0%;
		background: black;
		transition: height 0.5s;
	}

	.full-height {
		height: 100%;
	}

	.t-centered {
		width: 100%;
		margin: 0 auto;
		text-align: center;
		position: fixed;
		top: 50%;
		left: 50%;
		-ms-transform: translateX(-50%) translateY(-50%);
		-webkit-transform: translate(-50%,-50%);
		transform: translate(-50%,-50%);
		/* color: #00ff3c; */
		color: #23ff57;
		font-size: 20px;
		/* font-weight: bold; */
		letter-spacing:2px;
	}

	.theme-transition-over {
		width: 100%;
		height: 100%;
		position: absolute;
		display: none;
	}

	.bg-metaverse {
		background-image:url('/img/metaverse-04.svg');
		background-repeat: no-repeat;
		background-position: center calc(50vh); 
		background-size: 4000px;
		background-attachment: fixed;
	}
</style>