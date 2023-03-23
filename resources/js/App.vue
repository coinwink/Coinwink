<script setup>
import { ref, onMounted } from 'vue';
import { store } from './store.js';

import AppHeader from './layout/Header.vue';
import AppFooter from './layout/Footer.vue';


// let state = ref(store);

// function updateAppData() {
//   jQuery.ajax({
//     type: 'GET',
//     url: '/api/app_data',
//   })
//   .then(result => { 
//       window.cw_cmc = result['cmc'];
//       console.log(result['cmc']);
//   })
// }

onMounted(() => {
  store.userLoggedIn = window.userLoggedIn;
  // store.cmc = window.cmc;
  store.userEmail = window.user_email;

  // store.dest_email = window.dest_email;
  // store.dest_phone_nr = window.dest_phone_nr;
  // updateAppData();
  // setInterval(function() {
  //     updateAppData();
  // }, 60 * 1000);
});

function updateStoreCMC() {
  store.cmc = window.cw_cmc;
}

window.updateStoreCMC = updateStoreCMC;
</script>

<template>
  <div>
    <div>
      <!-- <span @click="updateAppData" class="link-default">Update</span> -->

      <AppHeader />

      <router-view />

      <AppFooter />

      <div class="section-bottom"></div>
    </div>
  </div>
</template>
