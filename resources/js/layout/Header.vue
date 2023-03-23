
<script setup>
import { useRouter, useRoute } from 'vue-router';
import { store } from '../store.js';
import { ref } from 'vue';
import { assertBindExpression } from '@babel/types';

let switchReady = ref(false);
let labelReady = ref(false);

let tabSMS = ref(false);
let tabEmail = ref(false);
let tabTelegram = ref(false);

if (switchLocation == 'sms' || switchLocation == 'sms-per') {
  tabSMS.value = true;
}
else if (switchLocation == 'telegram' || switchLocation == 'telegram-per') {
  tabTelegram.value = true;
}
else if (switchLocation == 'email' || switchLocation == 'email-per') {
  tabEmail.value = true;
}

const router = useRouter(); 
let route = useRoute();

const cw_theme = window.cw_theme;

// SWITCHES CONTROL //

// @TODO PR2: Do without timeout
setTimeout(() => {
  // console.log(route.name);
  // console.log(switchLocation)
  // console.log("Delayed for 1 second.");
  // console.log(route.path);

  if (route.name == 'EmailHome') {
    if (switchLocation == 'sms') {
      jQuery("#smsSwitch").prop('checked', true);
      switchLocation = "sms";
      doNavigation(switchLocation);
    }
    else if (switchLocation == 'sms-per') {
      jQuery("#smsSwitch").prop('checked', true);
      switchLocation = "sms-per";
      jQuery("#perSwitch").prop('checked', true);
      jQuery("#switch-cur-init").hide();
      jQuery("#switch-per").show();
      doNavigation(switchLocation);
    }
    else if (switchLocation == 'email-per') {
      switchLocation = "email-per";
      jQuery("#perSwitch").prop('checked', true);
      jQuery("#switch-cur-init").hide();
      jQuery("#switch-per").show();
      doNavigation(switchLocation);
    }  
    else if (switchLocation == 'telegram') {
      jQuery("#tgSwitch").prop('checked', true);
      switchLocation = "telegram";
      doNavigation(switchLocation);
    }
    else if (switchLocation == 'telegram-per') {
      jQuery("#tgSwitch").prop('checked', true);
      switchLocation = "telegram-per";
      jQuery("#perSwitch").prop('checked', true);
      jQuery("#switch-cur-init").hide();
      jQuery("#switch-per").show();
      doNavigation(switchLocation);
    }
  }
  else if (route.name == 'SMS') {
    tabEmail.value = false; tabSMS.value = true; tabTelegram.value = false;

    jQuery("#smsSwitch").prop('checked', true);
    switchLocation = "sms";
  }
  else if (route.name == 'SMSPer') {
    tabEmail.value = false; tabSMS.value = true; tabTelegram.value = false;

    jQuery("#smsSwitch").prop('checked', true);
    switchLocation = "sms-per";
    jQuery("#perSwitch").prop('checked', true);
    jQuery("#switch-cur-init").hide();
    jQuery("#switch-per").show();
  }
  else if (route.name == 'EmailPer') {
    tabEmail.value = true; tabSMS.value = false; tabTelegram.value = false;

    switchLocation = "email-per";
    jQuery("#perSwitch").prop('checked', true);
    jQuery("#switch-cur-init").hide();
    jQuery("#switch-per").show();
  }  
  else if (route.name == 'Telegram') {
    tabEmail.value = false; tabSMS.value = false; tabTelegram.value = true;

    jQuery("#tgSwitch").prop('checked', true);
    switchLocation = "telegram";
  }
  else if (route.name == 'TelegramPer') {
    tabEmail.value = false; tabSMS.value = false; tabTelegram.value = true;
    
    jQuery("#tgSwitch").prop('checked', true);
    switchLocation = "telegram-per";
    jQuery("#perSwitch").prop('checked', true);
    jQuery("#switch-cur-init").hide();
    jQuery("#switch-per").show();
  }
  else {
    // switchLocation = 'email';
    if (switchLocation == 'sms') {
      jQuery("#smsSwitch").prop('checked', true);
    }
    else if (switchLocation == 'sms-per') {
      jQuery("#smsSwitch").prop('checked', true);
      jQuery("#perSwitch").prop('checked', true);
      jQuery("#switch-cur-init").hide();
      jQuery("#switch-per").show();
    }
    else if (switchLocation == 'email-per') {
      jQuery("#perSwitch").prop('checked', true);
      jQuery("#switch-cur-init").hide();
      jQuery("#switch-per").show();
    }  
    else if (switchLocation == 'telegram') {
      jQuery("#tgSwitch").prop('checked', true);
    }
    else if (switchLocation == 'telegram-per') {
      jQuery("#tgSwitch").prop('checked', true);
      jQuery("#perSwitch").prop('checked', true);
      jQuery("#switch-cur-init").hide();
      jQuery("#switch-per").show();
    }
    else {
      switchLocation = 'email';
    }
  }
  // jQuery('.switch-selection').css('display', 'block');
  switchReady.value = true;
  labelReady.value = true;
}, 50)


function doNavigation(switchLocation) {
  router.push({ path: '/'+switchLocation })

  if (switchLocation == 'email' || switchLocation == 'email-per') {
    tabEmail.value = true; tabSMS.value = false; tabTelegram.value = false;
  }
  else if (switchLocation == 'sms' || switchLocation == 'sms-per') {
    tabEmail.value = false; tabSMS.value = true; tabTelegram.value = false;
  }
  else if (switchLocation == 'telegram' || switchLocation == 'telegram-per') {
    tabEmail.value = false; tabSMS.value = false; tabTelegram.value = true;
  }

  if (userLoggedIn && userVerified) {
    saveTab(switchLocation);
  }
}

// Save last opened tab in the backend
function saveTab(switchLocation) {
  axios({
      method: 'post',
      url: '/api/cw_tab',
      data: {cw_tab: switchLocation}
  })
}


function smsSwitch() {
  store.alertCreated = false;
	switch (switchLocation) {
		case "email":
			switchLocation = "sms";
			break;
		case "email-per":
			switchLocation = "sms-per";
			break;
		case "sms":
			switchLocation = "sms";
			break;
		case "sms-per":
			switchLocation = "sms-per";
			break;
		case "telegram":
			switchLocation = "sms";
			break;
		case "telegram-per":
			switchLocation = "sms-per";
			break;
  }
  doNavigation(switchLocation);
}

function tgSwitch() {
  store.alertCreated = false;
	switch (switchLocation) {
		case "email":
			switchLocation = "telegram";
			break;
		case "email-per":
			switchLocation = "telegram-per";
			break;
		case "sms":
			switchLocation = "telegram";
			break;
		case "sms-per":
			switchLocation = "telegram-per";
			break;
		case "telegram":
			switchLocation = "telegram";
			break;
		case "telegram-per":
			switchLocation = "telegram-per";
			break;
  }
  doNavigation(switchLocation);
}


function emailSwitch() {
  store.alertCreated = false;
	switch (switchLocation) {
		case "email":
			switchLocation = "email";
			break;
		case "email-per":
			switchLocation = "email-per";
			break;
		case "sms":
			switchLocation = "email";
			break;
		case "sms-per":
			switchLocation = "email-per";
			break;
		case "telegram":
			switchLocation = "email";
			break;
		case "telegram-per":
			switchLocation = "email-per";
			break;
  }
  doNavigation(switchLocation);
}


function curSwitch() {
  store.alertCreated = false;
	switch (switchLocation) {
		case "email":
			switchLocation = "email";
			break;
		case "email-per":
			switchLocation = "email";
			jQuery("#switch-cur").show();
			jQuery("#switch-per").hide();
			break;
		case "sms":
			switchLocation = "sms";
			break;
		case "sms-per":
			switchLocation = "sms";
			jQuery("#switch-cur").show();
			jQuery("#switch-per").hide();
			break;
		case "telegram":
			switchLocation = "telegram";
			break;
		case "telegram-per":
			switchLocation = "telegram";
			jQuery("#switch-cur").show();
			jQuery("#switch-per").hide();
			break;
  }
  doNavigation(switchLocation);
}


function perSwitch() {
  store.alertCreated = false;
	switch (switchLocation) {
		case "email":
			switchLocation = "email-per";
			jQuery("#switch-cur-init").hide();
			jQuery("#switch-cur").hide();
			jQuery("#switch-per").show();
			break;
		case "email-per":
			switchLocation = "email-per";
			break;
		case "sms":
			switchLocation = "sms-per";
			jQuery("#switch-cur-init").hide();
			jQuery("#switch-cur").hide();
			jQuery("#switch-per").show();
			break;
		case "sms-per":
			switchLocation = "sms-per";
			break;
		case "telegram":
			switchLocation = "telegram-per";
			jQuery("#switch-cur-init").hide();
			jQuery("#switch-cur").hide();
			jQuery("#switch-per").show();
			break;
		case "telegram-per":
			switchLocation = "telegram-per";
			break;
  }
  doNavigation(switchLocation);
}

</script>

<template>

<!-- MAIN APP CONTAINER -->
<div style="max-width: 800px;margin: 0 auto;">

    <div> <!-- HEADER -->
        
        <!-- Header - LEFT SIDE -->
        <div style="float:left; width: 56px;">
            <div class="fixed grid-header-left">

                <div class="switch-cur-per" style="margin:0 auto;">
                    <input type="radio" class="switch-2-input" name="switch-cur-per" id="curSwitch" checked="checked" @click="curSwitch()">
                    <label for="curSwitch" id="switch-label-cur" style="margin-left:-1px;padding-bottom: 27px;" class="switch-2-label switch-label-cur noselect" title="Currency">$</label>

                    <input type="radio" class="switch-2-input" name="switch-cur-per" id="perSwitch" @click="perSwitch()">
                    <label style="margin-left:-3px;padding-bottom: 28px;" for="perSwitch" class="switch-2-label switch-label-per noselect" title="Percentage">%</label>
                    
                    <svg id="switch-img-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33.58 88.49">
                        <path class="switch-cur-per-1" id="switch-cur-init" d="M16.79,6.83h0A12,12,0,0,1,28.72,18.76V29.1A12,12,0,0,1,16.79,41h0A12,12,0,0,1,4.86,29.1V18.76A12,12,0,0,1,16.79,6.83Z" transform="translate(0 -1)"></path>
                        <path class="switch-cur-per-1" id="switch-cur" d="M16.79,6.83h0A12,12,0,0,1,28.72,18.76V29.1A12,12,0,0,1,16.79,41h0A12,12,0,0,1,4.86,29.1V18.76A12,12,0,0,1,16.79,6.83Z" ></path>
                        <path class="switch-cur-per-1" id="switch-per" d="M16.79,6.83h0A12,12,0,0,1,28.72,18.76V29.1A12,12,0,0,1,16.79,41h0A12,12,0,0,1,4.86,29.1V18.76A12,12,0,0,1,16.79,6.83Z" ></path>
                        <path class="switch-cur-per-2" xmlns="http://www.w3.org/2000/svg" d="M16.79.7h0A16.14,16.14,0,0,1,32.88,16.79V71.7A16.14,16.14,0,0,1,16.79,87.79h0A16.14,16.14,0,0,1,.7,71.7V16.79A16.14,16.14,0,0,1,16.79.7Z" >
                        </path>
                    </svg>
                </div>
                
                <div style="margin:0 auto;width:33px;height:30px;">
                    <router-link to="/manage-alerts" class="link-manage-alerts">
                        <div title="Manage alerts" style="padding:4px;margin:0 auto;" class="icon-portfolio-outer">
                            <svg id="icon-alerts" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 541.67 602">
                                <path class="icon-hovered" d="M305.42,559.06A76.51,76.51,0,0,0,229,635.47v16.18l-3.07,1.29A210.06,210.06,0,0,0,97.76,846.73V966.17l-0.8,2.7-59.82,93a16.05,16.05,0,0,0,13.51,24.75h162l1,3.77a94.12,94.12,0,0,0,91,70.63h1.4a94.12,94.12,0,0,0,91-70.63l1-3.77H560.18a16,16,0,0,0,13.51-24.74l-59.82-93.06-0.8-2.7V846.73A210.06,210.06,0,0,0,384.9,652.95l-3.07-1.29V635.47a76.51,76.51,0,0,0-76.42-76.41h0Zm-44.3,76.6v-0.18a44.31,44.31,0,0,1,88.61,0v6.21l-5.92-1.12q-9.49-1.8-19.1-2.71-9.34-.88-19.29-0.89t-19.3.89q-9.6.9-19.09,2.71l-5.92,1.12v-6h0Zm-7.66,451H365.32l-3.45,7.17A62.24,62.24,0,0,1,339,1119.46a61.49,61.49,0,0,1-32.86,9.49h-1.4a61.48,61.48,0,0,1-32.86-9.49A62.25,62.25,0,0,1,249,1093.83l-3.45-7.17h7.94ZM85,1046.86L127.32,981a15.88,15.88,0,0,0,2.55-8.69h0V846.73h0a178.16,178.16,0,0,1,51.45-125.52,173.76,173.76,0,0,1,248.18,0A178.16,178.16,0,0,1,481,846.73V972.35A15.89,15.89,0,0,0,483.5,981l42.31,65.82,4.95,7.7H80.07l4.95-7.7h0Z" transform="translate(-34.58 -559.06)"/>
                            </svg>
                        </div>
                    </router-link>
                </div>
                
            </div>
        </div>

        <!-- Header - RIGHT SIDE -->
        <div style="float:right;width: 56px;">
            <div class="fixed grid-header-right">

                <div v-if="store.userLoggedIn" style="margin:0 auto;width:31px;">
                    <router-link to="/account" style="cursor:pointer;">
                        <div title="Account" style="padding:3px;margin:0 auto;" class="icon-portfolio-outer">
                            <svg id="icon-account" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 552.52 552.52">
                                <path class="icon-hovered" d="M553.24,338.26l-38.78-6.58a219.29,219.29,0,0,0-15.58-37.62l22.85-32a34,34,0,0,0-3.69-44l-34.4-34.39a33.89,33.89,0,0,0-24.13-10A33.51,33.51,0,0,0,439.78,180l-32.09,22.85a216.61,216.61,0,0,0-39-16l-6.46-38.32A34.1,34.1,0,0,0,328.52,120H279.93a34.1,34.1,0,0,0-33.71,28.51l-6.69,39.24A213.34,213.34,0,0,0,202,203.57l-31.74-22.86a34.1,34.1,0,0,0-44,3.7L91.79,218.8a34.16,34.16,0,0,0-3.69,44l23.08,32.43A213.4,213.4,0,0,0,95.83,333l-38.32,6.46A34.1,34.1,0,0,0,29,373.12v48.59a34.1,34.1,0,0,0,28.51,33.7l39.24,6.7a213.75,213.75,0,0,0,15.81,37.51L89.83,531.24a34,34,0,0,0,3.69,44l34.4,34.39a33.94,33.94,0,0,0,43.86,3.7l32.43-23.08a218.35,218.35,0,0,0,36.47,15L247.14,644a34.1,34.1,0,0,0,33.7,28.51h48.71A34.09,34.09,0,0,0,363.26,644l6.58-38.78a218.66,218.66,0,0,0,37.63-15.58l32,22.85a34.08,34.08,0,0,0,44-3.69l34.4-34.4a34.17,34.17,0,0,0,3.69-44l-22.85-32.09a216.78,216.78,0,0,0,15.58-37.62L553,454.26a34.09,34.09,0,0,0,28.51-33.7V372a33.68,33.68,0,0,0-28.28-33.7h0Zm-2.65,82.29a3,3,0,0,1-2.54,3l-48.48,8.08a15.49,15.49,0,0,0-12.47,11.43A184.9,184.9,0,0,1,467,491.42a15.61,15.61,0,0,0,.69,17l28.51,40.17a3.16,3.16,0,0,1-.34,3.93l-34.4,34.4a3,3,0,0,1-2.2.92,2.86,2.86,0,0,1-1.73-.57l-40-28.51a15.61,15.61,0,0,0-17-.69,184.87,184.87,0,0,1-48.36,20.08,15.32,15.32,0,0,0-11.43,12.47L332.55,639a3,3,0,0,1-3,2.54H281a3,3,0,0,1-3-2.54l-8.08-48.48a15.5,15.5,0,0,0-11.43-12.47,192,192,0,0,1-47.32-19.39,16,16,0,0,0-7.84-2.07,15.21,15.21,0,0,0-9,2.89l-40.4,28.74a3.39,3.39,0,0,1-1.73.58,3.1,3.1,0,0,1-2.19-.92l-34.4-34.4a3.12,3.12,0,0,1-.34-3.92l28.39-39.82a15.81,15.81,0,0,0,.69-17.08A182.82,182.82,0,0,1,124,444.45,15.81,15.81,0,0,0,111.52,433L62.7,424.71a3,3,0,0,1-2.53-3V373.12a3,3,0,0,1,2.53-3L110.83,362a15.62,15.62,0,0,0,12.58-11.54A184.41,184.41,0,0,1,143.15,302a15.41,15.41,0,0,0-.81-16.85l-28.74-40.4a3.14,3.14,0,0,1,.34-3.92l34.4-34.4a2.92,2.92,0,0,1,2.19-.92,2.86,2.86,0,0,1,1.74.58l39.82,28.39a15.8,15.8,0,0,0,17.08.69,183.08,183.08,0,0,1,48.25-20.31,15.81,15.81,0,0,0,11.42-12.47l8.31-48.82a3,3,0,0,1,3-2.54h48.6a3,3,0,0,1,3,2.54l8.08,48.13a15.6,15.6,0,0,0,11.54,12.58,187.41,187.41,0,0,1,49.52,20.32,15.6,15.6,0,0,0,17-.69l39.82-28.62a3.41,3.41,0,0,1,1.73-.58,3.1,3.1,0,0,1,2.19.92L496,240a3.14,3.14,0,0,1,.35,3.93l-28.51,40a15.6,15.6,0,0,0-.69,17,184.82,184.82,0,0,1,20.09,48.36,15.31,15.31,0,0,0,12.46,11.42L548.16,369a3,3,0,0,1,2.54,3v48.59h-0.12Z" transform="translate(-29 -120)"/><path class="icon-hovered" d="M305.32,277A119.23,119.23,0,1,0,424.55,396.2,119.31,119.31,0,0,0,305.32,277h0Zm0,207.29a88.07,88.07,0,1,1,88.06-88.06,88.12,88.12,0,0,1-88.06,88.06h0Z" transform="translate(-29 -120)"/>
                            </svg>
                        </div>
                    </router-link>
                </div>

                <div v-else style="margin:0 auto;width:30px;">
                    <router-link to="/account" style="cursor:pointer;">
                        <div title="Sign up" style="padding:3px;margin:0 auto;" class="icon-portfolio-outer">
                            <svg id="icon-login" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 513.14 565.96">
                                <path class="icon-hovered" d="M254.39,304.93h4c36.6-.58,66.21-12.65,88.07-35.74,48.09-50.87,40.1-138.07,39.22-146.36-3.12-62.48-34.6-92.36-60.58-106.3C305.73,6.1,283.1.47,257.89,0h-2.13c-13.87,0-41.1,2.11-67.21,16.06s-58.22,43.83-61.34,106.77c-.87,8.32-8.87,95.52,39.23,146.36,21.72,23.12,51.35,35.15,87.95,35.74ZM160.57,125.74c0-.35.13-.71.13-.94,4.12-84,67.71-93.06,94.94-93.06h1.52c33.73.7,91.07,13.59,94.94,93.06a2,2,0,0,0,.13.94c.12.81,8.87,80.51-30.86,122.48-15.74,16.64-36.73,24.84-64.33,25.08h-1.28c-27.48-.24-48.6-8.45-64.21-25.08-39.6-41.72-31.1-121.78-31-122.48h0Z"/><path class="icon-hovered" d="M513.11,449.56v-.35c0-.94-.13-1.88-.13-2.93-.74-23.21-2.37-77.46-56.59-94.81-.37-.12-.87-.24-1.25-.36C398.8,337.63,352,307.16,351.46,306.81A17.56,17.56,0,0,0,328,310.65v0a15.19,15.19,0,0,0,4.09,22h0c2.13,1.4,51.85,33.87,114.06,48.87,29.1,9.73,32.35,38.91,33.23,65.68a20.53,20.53,0,0,0,.13,2.93c.12,10.54-.63,26.84-2.62,36.21-20.24,10.78-99.57,48.06-220.25,48.06-120.17,0-200-37.39-220.36-48.17-2-9.38-2.87-25.67-2.62-36.23,0-.93.12-1.87.12-2.92.88-26.72,4.12-55.91,33.23-65.68,62.2-15,111.92-47.57,114-48.87a15.19,15.19,0,0,0,4.12-22l0,0a17.57,17.57,0,0,0-23.47-3.87h0c-.5.35-47.1,30.82-103.69,44.31-.5.11-.87.23-1.25.35C2.54,368.81.92,423.07.16,446.17A23.85,23.85,0,0,1,0,449.1v.35c-.13,6.1-.25,37.39,6.37,53.1a15.21,15.21,0,0,0,6.5,7.38c3.74,2.35,93.57,56,243.85,56s240.11-53.8,243.85-56a16,16,0,0,0,6.5-7.38c6.25-15.6,6.12-46.91,6-53h0Z"/>
                            </svg>
                        </div>
                    </router-link>
                </div>

                <div style="margin:0 auto;width:30.5px;">
                    <router-link to="/portfolio" class="link-portfolio" style="cursor:pointer;">
                        <div title="Portfolio" style="padding:3px;margin:0 auto;padding-top:5px;" class="icon-portfolio-outer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.84 444.68">
                                <path class="icon-hovered" d="M532.25,1903.35a8.52,8.52,0,0,0,2.5-6V1747.45a102.51,102.51,0,0,1-31,22.9,101.06,101.06,0,0,1-43.8,9.94H363.12v30.62a13.54,13.54,0,0,1-13.54,13.54H261.26a13.54,13.54,0,0,1-13.54-13.54v-30.62H150.86a101.06,101.06,0,0,1-43.8-9.94,102.51,102.51,0,0,1-31-22.9v149.86a8.55,8.55,0,0,0,8.54,8.54h441.6a8.52,8.52,0,0,0,6-2.51h0ZM336,1736.13H274.8v61.24H336v-61.24h0Zm-257.46-130a8.51,8.51,0,0,0-2.5,6v66.24a74.87,74.87,0,0,0,74.78,74.78h96.86v-30.62a13.54,13.54,0,0,1,13.54-13.54h88.32a13.54,13.54,0,0,1,13.54,13.54v30.62H460a74.87,74.87,0,0,0,74.78-74.78v-66.24a8.55,8.55,0,0,0-8.54-8.54H84.62a8.52,8.52,0,0,0-6,2.51h0Zm132.48-88.32a8.52,8.52,0,0,0-2.5,6v52.7H402.28v-52.7a8.55,8.55,0,0,0-8.54-8.54H217.1a8.52,8.52,0,0,0-6,2.5h0Zm218.3,58.74h96.86a35.66,35.66,0,0,1,35.62,35.62v285.12a35.66,35.66,0,0,1-35.62,35.62H84.62A35.66,35.66,0,0,1,49,1897.31V1612.19a35.66,35.66,0,0,1,35.62-35.62h96.86v-52.7a35.66,35.66,0,0,1,35.62-35.62H393.74a35.66,35.66,0,0,1,35.62,35.62v52.7h0Z" transform="translate(-49 -1488.25)"/>
                            </svg>
                        </div>
                    </router-link>
                </div>

                <div style="margin:0 auto;width:35px;">
                    <router-link to="/watchlist" class="link-watchlist" style="cursor:pointer;">
                        <div title="Watchlist" style="padding:3px;padding-top:5px;margin:0 auto;" class="icon-portfolio-outer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 585.46 391.69">
                                <path class="icon-hovered" d="M515.16,108.8c40.43,39.24,65.28,74.79,67.55,78.1a15.29,15.29,0,0,1,2,4.25,15.91,15.91,0,0,1,0,9.4,15.36,15.36,0,0,1-2,4.24c-1.71,2.49-16.28,23.34-40.69,50.27a589.46,589.46,0,0,1-46.77,46.22c-28.68,25.24-63.26,50.21-101.91,67.5-31.25,14-65.12,22.91-100.62,22.91a233.59,233.59,0,0,1-72.91-12.14c-26.44-8.68-51.33-21.4-74.15-36.07-28.77-18.49-54.23-40.06-75.4-60.6C29.87,243.69,5,208.12,2.73,204.81a15.46,15.46,0,0,1-2-4.24v0a16.39,16.39,0,0,1,0-9.41h0a15.48,15.48,0,0,1,2-4.23L5,183.59l.07,0a587.54,587.54,0,0,1,65.26-74.85c21.17-20.54,46.63-42.11,75.37-60.59,22.82-14.67,47.72-27.39,74.15-36.06a225,225,0,0,1,145.83,0c26.44,8.67,51.33,21.4,74.15,36.07,28.75,18.48,54.2,40,75.37,60.59ZM252.78,99.21A104.53,104.53,0,0,1,366.64,269.76a105,105,0,0,1-34,22.72,104.45,104.45,0,0,1-79.89,0,105,105,0,0,1-56.69-56.69,104.45,104.45,0,0,1,0-79.89,105,105,0,0,1,56.69-56.69ZM262.92,268a77.9,77.9,0,0,0,85-17h0a78.08,78.08,0,0,0-25.35-127.26,77.9,77.9,0,0,0-84.95,17l0,0a77.86,77.86,0,0,0,0,110.29h0a78.12,78.12,0,0,0,25.35,17ZM229.5,350.9a196.53,196.53,0,0,0,126.58,0c23.12-7.51,45.16-18.58,65.62-31.48,24.65-15.55,47-33.8,66.18-51.7a591.91,591.91,0,0,0,62.37-68.26l2.8-3.61-2.8-3.59A592.82,592.82,0,0,0,487.78,124h0c-19.18-17.88-41.55-36.13-66.23-51.7-20.45-12.91-42.48-24-65.6-31.49a196.38,196.38,0,0,0-126.59,0c-23.12,7.51-45.16,18.57-65.62,31.48-24.65,15.54-47,33.8-66.18,51.7A591.91,591.91,0,0,0,35.2,192.23l-2.8,3.6,2.8,3.6a594,594,0,0,0,62.46,68.28c19.18,17.88,41.55,36.13,66.23,51.7,20.45,12.9,42.49,24,65.61,31.49Z" style="fill-rule:evenodd"/>
                            </svg>
                        </div>
                    </router-link>
                </div>

            </div>
        </div>

        <!-- Header - Logo -->
        <div style="text-align: center;">
            <div style="height:27px;"></div>
            <div id="logo" style="width:44px;-webkit-filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));">
                <a href="/">
                    <svg v-if="cw_theme == 'metaverse'" class="logo-metaverse" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.36 61.36" alt="Coinwink Crypto Alerts" width="44" height="44"><use xlink:href='#coinwink-logo' /></svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.36 61.36" alt="Coinwink Crypto Alerts" width="44" height="44"><use xlink:href='#coinwink-logo' /></svg>
                </a>
            </div>
            <div id="txtlogo"><a href="/">Coinwink</a></div>
        </div>

        <!-- Header - Email/Telegram/SMS switch -->
        <div class="switch-email-sms">

          <span v-if="cw_theme == 'matrix'">
            <input type="radio" class="switch-input" name="switch-email-sms" id="emailSwitch" checked @click="emailSwitch()">
            <label for="emailSwitch" v-show="labelReady" :class="{'label-selected' : tabEmail }" class="switch-label switch-label-email noselect" title="Email alerts">Email</label>

            <input type="radio" class="switch-input" name="switch-email-sms" id="tgSwitch" @click="tgSwitch()">
            <label for="tgSwitch" v-show="labelReady" :class="{'label-selected' : tabTelegram }" class="switch-label switch-label-tg noselect" title="Telegram alerts">Telegram</label>

            <input type="radio" class="switch-input" name="switch-email-sms" id="smsSwitch" @click="smsSwitch()">
            <label for="smsSwitch" v-show="labelReady" :class="{'label-selected' : tabSMS }" class="switch-label switch-label-sms noselect" title="SMS alerts">SMS</label>
          </span>
          <span v-else>
            <input type="radio" class="switch-input" name="switch-email-sms" id="emailSwitch" checked @click="emailSwitch()">
            <label for="emailSwitch" :class="{'label-selected' : tabEmail }" class="switch-label switch-label-email noselect" title="Email alerts">Email</label>

            <input type="radio" class="switch-input" name="switch-email-sms" id="tgSwitch" @click="tgSwitch()">
            <label for="tgSwitch" :class="{'label-selected' : tabTelegram }" class="switch-label switch-label-tg noselect" title="Telegram alerts">Telegram</label>

            <input type="radio" class="switch-input" name="switch-email-sms" id="smsSwitch" @click="smsSwitch()">
            <label for="smsSwitch" :class="{'label-selected' : tabSMS }" class="switch-label switch-label-sms noselect" title="SMS alerts">SMS</label>
          </span>

          <span class="switch-selection" :class="{'switch-selection-tg' : tabTelegram, 'switch-selection-sms' : tabSMS, 'switch-selection-email': tabEmail}"></span>
        </div>

    </div> <!-- End of HEADER -->
</div>

</template>

<style>

/* SWITCH: $ <-> % */

.switch-cur-per {
  position: relative;
  height: 72px;
  width: 33px;
}

#switch-img-full {
  position: absolute;
  top: 0;
  left: 0;
}

.switch-cur-per-1 {
  fill:none;
  stroke:#c1c1c1;
  stroke-miterlimit:2;
  stroke-width:1.3px;
}

.switch-cur-per-2 {
  fill:none;
  stroke:#c1c1c1;
  stroke-miterlimit:2;
  stroke-width:1.2px;
}

.switch-2-label {
  position: relative;
  z-index: 2;
  width: 35px;
  height: 35px;
  padding-top: 15px;
  display: block;
  font-size: 13px;
  color: rgb(175, 175, 175);
  text-align: center;
  cursor: pointer;
}

.switch-label-per {
  padding-left: 4px;
}

.switch-2-input {
  display: none;
}

/* .switch-2-selection {
  display: block;
  position: absolute;
  z-index: 1;
} */

#switch-cur {
  display: none;
  transform: translate(0px, 42px);
  animation: switch-anim-up 0s forwards;
}

@keyframes switch-anim-up {
  100% {
    transform: translate(0px, -1px);
  }
}

#switch-per {
  display: none;
  transform: translate(0px, -1px);
  animation: switch-anim-down 0s forwards;
}

@keyframes switch-anim-down {
  100% {
    transform: translate(0px, 42px);
  }
}

.switch-2-input:checked + .switch-2-label {
  font-weight: bold;
  color: rgb(194, 194, 194)!important;
  z-index: 10;
}


/* SWITCH: Email <-> Telegram <-> SMS */

.switch-email-sms {
  position: relative;
  margin: 20px auto;
  margin-bottom: 26px;
  height: 26px;
  width: 190px;
  background: rgba(0, 0, 0, 0.25);
  border-radius: 3px;
  -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
}

@media screen and (max-width: 600px) {
  .switch-email-sms {
    margin-bottom: 30px;
  }
}

.switch-label {
  position: relative;
  z-index: 2;
  float: left;
  width: 59px;
  line-height: 26px;
  font-size: 11px;
  color: rgba(255, 255, 255, 0.35);
  text-align: center;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.45);
  cursor: pointer;
}

.switch-label-email {
  padding-left: 2px;
}

.switch-input {
  display: none;
}

.switch-selection {
  display: block;
  position: absolute;
  z-index: 1;
  top: 2px;
  left: 2px;
  width: 58px;
  height: 22px;
  border-radius: 3px;
  background: #c4bb61;
  background-image: -webkit-linear-gradient(top, #e0dd94, #c4bb61);
  background-image: -moz-linear-gradient(top, #e0dd94, #c4bb61);
  background-image: -o-linear-gradient(top, #e0dd94, #c4bb61);
  background-image: linear-gradient(to bottom, #e0dd94, #c4bb61);
  -webkit-box-shadow: inset 0 1px rgba(255, 255, 255, 0.5), 0 0 2px rgba(0, 0, 0, 0.2);
  box-shadow: inset 0 1px rgba(255, 255, 255, 0.5), 0 0 2px rgba(0, 0, 0, 0.2);
  -webkit-transition: 0.2s ease-out;
  -moz-transition: 0.2s ease-out;
  -o-transition: 0.2s ease-out;
  transition: 0.2s ease-out;
}

/* .switch-input:checked + .switch-label {
  font-weight: bold;
  color: rgba(0, 0, 0, 0.65);
  text-shadow: 0 1px rgba(255, 255, 255, 0.25);
} */

.label-selected { 
  font-weight: bold;
  color: rgba(0, 0, 0, 0.65);
  text-shadow: 0 1px rgba(255, 255, 255, 0.25);
}

/* .switch-input:checked + .switch-label-sms ~ .switch-selection {
  left: 130px;
}

.switch-input:checked + .switch-label-tg ~ .switch-selection {
  left: 61px;
  width: 69px;
} */

.switch-selection-email {
  left: 2px!important;
  width: 58px!important;
}

.switch-selection-tg {
  left: 61px!important;
  width: 69px!important;
}

.switch-selection-sms {
  left: 130px!important;
  width: 58px!important;
}

.switch-label-tg {
  width: 70px;
  padding-left:3px;
}

.switch-label-sms {
  width: 60px;
}
</style>