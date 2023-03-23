<script setup>
  import {ref, watch} from 'vue';
  import { store } from '../store.js';

  let priceBtc = ref(0);
  let priceEth = ref(0);
  let priceFiat = ref(0);
  let fiatCur = ref('USD');
  // let coinSymbol = ref('BTC');
  let coinSlug = ref('bitcoin');
  let coinName = ref('Bitcoin');
  let loading = ref(true);

  const props = defineProps({
    selected: String,
  })

  watch(props, (selection, prevSelection) => { 
    showPrice();
  })


  // Define vars
  var curList = {};
  var curCurrent = 'USD'; if (userLoggedIn) curCurrent = cur_main;


  // Change currency
  function changeCur() {
    var curListTemp = { USD: '', EUR: '', GBP: '', AUD: '', CAD: '', BRL: '', MXN: '', JPY: '', SGD: '' };

    if (curCurrent == 'SGD') {
      curCurrent = 'USD';
    }
    else {
      var keys = Object.keys(curListTemp);
      var nextIndex = keys.indexOf(curCurrent) +1;
      var nextItem = keys[nextIndex];
      curCurrent = nextItem;
    }

    if (userLoggedIn) {
      jQuery.ajax({
        type: 'POST',
        url: '/api/config_cur_main',
        data: 'cur_main=' + curCurrent
      })
    }

    showPrice();
  }


  // Show current price and add price to percent input
  function parsePrice(price) {
    // console.log(price);
    if (price < 0.0001) { 
      price = parseFloat(price).toFixed(7); 
    }
    else if (price < 0.001) { 
      price = parseFloat(price).toFixed(6); 
    }
    else if (price < 0.01) { 
      price = parseFloat(price).toFixed(5); 
    }
    else if (price < 0.1) { 
      price = parseFloat(price).toFixed(4); 
    }
    else if (price < 0.9) { 
      price = parseFloat(price).toFixed(3); 
    }
    else { 
      price = parseFloat(price).toFixed(2); 
    }
    return price;
  }


  function showPrice() {
    // console.log("1")
    loading.value = false;
    
    for(var i = 0; i < cw_cmc.length; i++) {
      var coin = cw_cmc[i];

      var eth = coin['price_eth'];
      if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
      if (!(eth > 0.00000001)) {
        eth = parseFloat(coin['price_eth']).toFixed(9);
        if (!(eth > 0.000000001)) {
          eth = parseFloat(coin['price_eth']).toFixed(10);
        }
      }
      var btc = coin['price_btc'];
      if (btc % 1 != 0) { btc = parseFloat(btc).toFixed(8); }
      if (!(btc > 0.00000001)) {
        btc = parseFloat(coin['price_btc']).toFixed(9);
        if (!(btc > 0.000000001)) {
          btc = parseFloat(coin['price_btc']).toFixed(10);
        }
      }
      var usd = coin['price_usd'];

      var eur = coin['price_usd'] * rates['eur'];
      var gbp = coin['price_usd'] * rates['gbp'];
      var aud = coin['price_usd'] * rates['aud'];
      var cad = coin['price_usd'] * rates['cad'];
      
      var brl = coin['price_usd'] * rates['brl'];
      var mxn = coin['price_usd'] * rates['mxn'];
      var jpy = coin['price_usd'] * rates['jpy'];
      var sgd = coin['price_usd'] * rates['sgd'];

      if (coin['id'] == props.selected) {
        var curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };

        var price = curList[curCurrent];
        price = parsePrice(price);

        coinSlug.value = coin['slug'];
        coinName.value = coin['name'];
        priceBtc.value = btc;
        priceEth.value = eth;
        priceFiat.value = price;
        fiatCur.value = curCurrent;
      }
    }
  }


  if (cw_cmc) {
    showPrice();
    watch(
      props, (selection, prevSelection) => { 
        showPrice();
      }
    )
  }
  else {
    watch(
      store, (selection, prevSelection) => { 
        // console.log(selection, prevSelection)
        showPrice();
      },
      props, (selection, prevSelection) => { 
        showPrice();
      }
    )
  }
</script>

<template>
    <div class="dropdown-price-div" id="pricediv_sms">
      <span v-if="!loading">
        <a target="_blank" class="portfoliocoin" :href="'https://coinmarketcap.com/currencies/'+coinSlug+'/'">
          <img style="vertical-align:middle;" width="14" :src="'/img/coins/32x32/'+selected+'.png'">
        </a>
        <span style="position:relative;top:1.5px;"> = {{ priceBtc }} BTC | {{ priceEth }} ETH | <span @click="changeCur()" id="cur-span-sms" class="cur-span noselect">{{ priceFiat }} {{ fiatCur }}</span></span>
      </span>
      <span style="position:relative;top:1.5px;" v-else>Loading...</span>
    </div>
</template>