<script setup>
import { onMounted, ref, watch } from 'vue';
import { store } from '../store.js';

import DropDownCoins from '../components/DropDownCoins.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';

let conv_exp = ref(window.conv_exp);
let isRounded = ref(false);
let loaded = ref(false);

const cw_theme = window.cw_theme;

function expandCollapse() {
    if (conv_exp.value == '1') {
        conv_exp.value = '0';
        window.conv_exp = '0';
        isRounded.value = true;
    }
    else {
        conv_exp.value = '1';
        window.conv_exp = '1';
        isRounded.value = false;
    }

    jQuery.ajax({
        type:"POST",
        url: '/api/cryptocurrency_converter_expanded',
        data: 'expanded='+conv_exp.value
    });
}


function init() {
    loaded.value = true;
    if (store.userLoggedIn) {
        jQuery('.converter-arrow').show();
        if (conv_exp.value == '1') {
            isRounded.value = false;
            jQuery("#converter-header").removeClass('conv-header-closed');
            jQuery('#converter-content').show();
            
            jQuery('#converter-hide').show();
            jQuery('#converter-show').hide();

            jQuery('#ajax_loader_converter').hide();
        }
        else {
            isRounded.value = true;
            jQuery("#converter-header").addClass('conv-header-closed');
        }
    }
    else {
        jQuery("#converter-header").removeClass('conv-header-closed');
        jQuery('#converter-content').show();
        jQuery('#ajax_loader_converter').hide();
    }

    currencyConverterPrep()
}

if (cw_cmc) {
    // @todo PR2: do without timeout
    setTimeout(() => {
        init();
    }, 100);
}
else {
    watch(store, (current, prev) => {
        init();
    })
}

function currencyConverterPrep() {
    var curListArray = Object.keys(rates).map((key) => [String(key), rates[key]]);
    var curListArrayNew = [];

    curListArrayNew[0] = [];
    curListArrayNew[0]['id'] = 'usd';
    curListArrayNew[0]['text'] = 'USD';

    for(var i=0; i < curListArray.length; i++) {
        var coin = curListArray[i];
        var i2 = i + 1;
        curListArrayNew[i2] = [];
        curListArrayNew[i2]['id'] = coin[0];
        curListArrayNew[i2]['text'] = coin[0].toUpperCase();
    }

    // console.log(curListArrayNew)

    var cw_cmcConverter = curListArrayNew.concat(cw_cmc);
    // console.log(cw_cmcConverter)

    // Activate Select2 top dropdown
    var myOptions = {
        ajax: {},
        jsonData: cw_cmcConverter,
        jsonMap: {id: "id", text: "text"},
        initialValue: 1,
        pageSize: 50,
        dataAdapter: jsonAdapter
    };
    jQuery("#conv-select-1").select2(myOptions);
    
    // Activate Select2 bottom dropdown
    var initialValue = "usd";
    myOptions = {
        ajax: {},
        jsonData: cw_cmcConverter,
        jsonMap: {id: "id", text: "text"},
        initialValue: initialValue,
        pageSize: 50,
        dataAdapter: jsonAdapter
    };
    jQuery("#conv-select-2").select2(myOptions);

    // Initial values
    jQuery('#conv-input-1').val(1);

    jQuery('#conv-input-1').change(function() {
        currencyConverter('top');
    })

    jQuery('#conv-input-2').change(function() {
        currencyConverter('bottom');
    })

    jQuery('#conv-select-1').change(function() {
        currencyConverter('top');
    })

    jQuery('#conv-select-2').change(function() {
        currencyConverter('top');
    })

    jQuery(document).ready(function() {
        currencyConverter('top');
    });
}

function currencyConverter(type) {
    if (type == 'top') {
        var convertFromCoinId = jQuery('#conv-select-1').val();
        var convertToCoinId = jQuery('#conv-select-2').val();
        
        var convertFromCoinAmount = jQuery('#conv-input-1').val();

        // Ignore commas
        convertFromCoinAmount = convertFromCoinAmount.replace(/,/g, '');
        jQuery('#conv-input-1').val(convertFromCoinAmount);
    }
    else if (type == 'bottom') {
        var convertFromCoinId = jQuery('#conv-select-2').val();
        var convertToCoinId = jQuery('#conv-select-1').val();
        
        var convertFromCoinAmount = jQuery('#conv-input-2').val();
        
        // Ignore commas
        convertFromCoinAmount = convertFromCoinAmount.replace(/,/g, '');
        jQuery('#conv-input-2').val(convertFromCoinAmount);
    }

    if (convertFromCoinAmount == '') {
        jQuery('#conv-input-2').val('');
        jQuery('#conv-input-1').val('');
        return;
    }



    if (isNaN(convertFromCoinAmount)) {
        alert('Input field should be a numeric value.');
        return;
    }

    var convertFromCoinPrice = null;
    var convertToCoinPrice = null;

    for(var i=0; i < cw_cmc.length; i++) {
		var coin = cw_cmc[i];
        if (convertFromCoinId == 'usd') {
            convertFromCoinPrice = 1;
            break;
        }
        else if (isNaN(convertFromCoinId)) {
            convertFromCoinPrice = rates[convertFromCoinId];
            break;
        }
		else if (coin['id'] == convertFromCoinId) {
			convertFromCoinPrice = coin['price_usd'];
			break;
		}
	}

    for(var i=0; i < cw_cmc.length; i++) {
		var coin = cw_cmc[i];
        if (convertToCoinId == 'usd') {
            convertToCoinPrice = 1;
            break;
        }
        else if (isNaN(convertToCoinId)) {
            convertToCoinPrice = rates[convertToCoinId];
            break;
        }
		else if (coin['id'] == convertToCoinId) {
			convertToCoinPrice = coin['price_usd'];
			break;
		}
	}

    // if currency-currency conversion
    if (isNaN(convertToCoinId) && isNaN(convertFromCoinId)) {
        // console.log(convertFromCoinPrice, convertToCoinPrice);

        // get usd values
        var fromUsdRate = 1 / convertFromCoinPrice;
        var toUsdRate = 1 / convertToCoinPrice;
        
        // console.log("from", fromUsdRate, "to", toUsdRate);

        var amountUsd = convertFromCoinAmount * fromUsdRate;
        var result = parseFloat(amountUsd / toUsdRate);

        if (result >= 1) {
            result = result.toFixed(2);
        }
    }
    else {
        // if currency-cryptocurrency
        if (isNaN(convertFromCoinId)) {
            // console.log("THIS")
            // get usd values
            var fromUsdRate = 1 / convertFromCoinPrice;
            var toUsdRate = convertToCoinPrice;
            
            // console.log("from", fromUsdRate, "to", toUsdRate);

            var valueUsd = convertFromCoinAmount * fromUsdRate;
            // console.log(valueUsd)
            // console.log(convertToCoinPrice, valueUsd)
            // console.log(parseFloat(convertToCoinPrice / valueUsd))
            // console.log((valueUsd / toUsdRate) + '')
            var result = parseFloat(valueUsd / toUsdRate);
            if (result >= 1) {
                result = result.toFixed(2);
            }
        }
        // if vice-versa
        else if (isNaN(convertToCoinId)) {
            // console.log("THIS2");
            // get usd values
            var fromUsdRate = 1 / convertFromCoinPrice;
            var toUsdRate = 1 / convertToCoinPrice;
            
            // console.log("from", fromUsdRate, "to", toUsdRate);

            var valueUsd = fromUsdRate / convertFromCoinAmount;
            // console.log(valueUsd)
            var result = parseFloat(convertToCoinPrice / valueUsd);
            if (result >= 1) {
                result = result.toFixed(2);
            }
        }
        // if cryptocurrency conversion
        else {
            var result = parseFloat(convertFromCoinAmount * convertFromCoinPrice / convertToCoinPrice);
        }
    }
    if (typeof(result) == 'number') {
        if (result == 0) {
            result = "0.00";
        }
        else if (result > 10000000000) {
            result = result.toFixed(0);
        }
        else if (result > 1000000000) {
            result = result.toFixed(2);
        }
        else if (result > 100000000) {
            result = result.toFixed(3);
        }
        else if (result > 10000000) {
            result = result.toFixed(4);
        }
        else {
            if (result < 0.000000001) {
                result = (result).toFixed(12);
            }
            else if (result < 0.0000001) {
                result = (result).toFixed(11);
            }
            else if (result < 0.0000001) {
                result = (result).toFixed(10);
            }
            else if (result < 0.000001) {
                result = (result).toFixed(9);
            }
            else if (result < 0.00001) {
                result = (result).toFixed(8);
            }
            else if (result < 0.0001) {
                result = (result).toFixed(7);
            }
            else {
                result = (result).toFixed(6);
            }
        }
    }
    
    jQuery('#ajax_loader_converter').hide();
    // jQuery('#converter-content').show();

    if (type == 'top') {
        jQuery('#conv-input-2').val(result);
    }
    else {
        jQuery('#conv-input-1').val(result);
    }
}
</script>

<template>
    <div class="widget-container" style="text-align:center;min-height:0px;" :class="{ contentrounded : isRounded }">
        <header :class="{ headerrounded : isRounded }">
            <h1 @click="expandCollapse()" style="cursor:pointer;">Cryptocurrency Converter</h1>
        </header>
        <div class="converter-arrow">
            <div v-show="conv_exp != '0'" class="expand-collapse" style="top:19px;" id="converter-hide" title="Collapse" @click="expandCollapse()">
                <svg data-name="Layer 1" viewBox="0 0 23 13">
                    <path class="svg-show-hide" d="M22 12H1v-1L11 1h1l11 10-1 1z" stroke="#bdbfc1" stroke-miterlimit="3" fill-rule="evenodd"/>
                </svg>
            </div>
            <div v-show="conv_exp != '1'" class="expand-collapse" id="converter-show" title="Expand" @click="expandCollapse()">
                <svg data-name="Layer 1" viewBox="0 0 23 13">
                    <path class="svg-show-hide"  d="M1 1h22L12 12h-1L1 1V0z" stroke="#bdbfc1" stroke-miterlimit="3" fill-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <div v-show="conv_exp != '0'" id="converter-content" style="font-size:13px;line-height:150%;">

            <div class="spacer-converter" style="height:30px;"></div>

            <div v-show="loaded">
                <div style="display:grid;width:285px;margin:0 auto;grid-template-columns: 110px 10px 165px;">
                    <div><input type="text" id="conv-input-1" style="padding-left: 5px; width: 100%; height: 30px;"></div>
                    <div></div>
                    <div>
                        <DropDownCoins :id="'conv-select-1'" :converter="true" style="width:100%;" />
                    </div>
                </div>

                <div style="height:10px;"></div>

                <div style="display:grid;width:285px;margin:0 auto;grid-template-columns: 110px 10px 165px;">
                    <div><input type="text" id="conv-input-2" style="padding-left: 5px; width: 100%; height: 30px;"></div>
                    <div></div>
                    <div>
                        <DropDownCoins :id="'conv-select-2'" :converter="true" style="width:100%;" />
                    </div>
                </div>
            </div>

            <div v-show="!loaded">
                <LoadingSpinner :theme='cw_theme' />
            </div>

            <div style="height:25px;"></div>
        </div>
    </div>

</template>

<style scoped>
    .conv-header-closed {
        border-radius: 3px;
    }

    .headerrounded {
        border-bottom-left-radius: 3px!important;
        border-bottom-right-radius: 3px!important;
    }

    .contentrounded {
        border-radius: 10px;
    }
</style>