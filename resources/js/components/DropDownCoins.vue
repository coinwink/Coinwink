

<template>
  <div v-if="converter">
    <select class="selectconverter" :id="id" style="width:100%;height:30px;"></select>
  </div>
  <div v-else style="position:relative;">
    <select class="selectcoin" :id="id"></select>

    <div class="main-item" v-if="loading" style="margin-top:1px;">
      <img v-if="cw_theme == 'metaverse'" src="/img/loader-metaverse.gif" style="width:100%;">
      <img v-else-if="cw_theme == 'matrix'" src="/img/loader-matrix.gif" style="width:100%;">
      <img v-else src="/img/loader-classic.gif" style="width:100%;">
    </div>
  </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router'
import { store } from '../store.js';

const route=useRoute();
const router=useRouter();

const props = defineProps({
  id: String,
  converter: Boolean
})

const emit = defineEmits(['selected'])

function init() {
  loading.value = false;
  
  window.initializedDropDownCoins = true;
  
  // Default is the top1 coin, preselected only on individual coin page
  if (route.name == 'IndividualCoin') {
    var urlSymbolUp = route.params.slug.toUpperCase();
    for(var i=0; i < cw_cmc.length; i++) {
      var coin = cw_cmc[i];
      if (coin['symbol'] == urlSymbolUp) {
        initialValue = coin['id'];
        break;
      }
    }
  }
  else {
    if (typeof(initialValue) == 'undefined') {
      initialValue = "1";
    }
  }


  if (route.name == 'Portfolio' || route.name == 'Watchlist') {
    // Initialize Select2
    jQuery(document).ready(function() {
        var  initialValue = "1";
        var myOptions = {
            ajax: {},
            jsonData: cw_cmc,
            jsonMap: {id: "id", text: "text"},
            initialValue: initialValue,
            pageSize: 50,
            dataAdapter: jsonAdapter
      };
      jQuery(".selectcoin").select2(myOptions);
    });
  }
  // Currency converter
  else if (!props.converter) {

    // Initialize Select2
    jQuery(document).ready(function() {
        if (typeof(initialValue) == 'undefined') {
          initialValue = "1";
        }
        var myOptions = {
            ajax: {},
            jsonData: cw_cmc,
            jsonMap: {id: "id", text: "text"},
            initialValue: initialValue,
            pageSize: 50,
            dataAdapter: jsonAdapter
      };
      jQuery(".selectcoin").select2(myOptions);

      // Initial emit
      emit('selected', initialValue)
      

      // emit selected value after change 
      jQuery(".selectcoin").on("select2:select", function (e) { 
        var select_val = $(e.currentTarget).val();
        initialValue = select_val;
        
        emit('selected', select_val)
        if (route.name == 'IndividualCoin') { 
          var select_symbol = '';

          for(var i=0; i < cw_cmc.length; i++) {
            var coin = cw_cmc[i];
            if (coin['id'] == select_val) {
              select_symbol = coin['symbol'];
              break;
            }
          }
          
          router.push({path:'/'+select_symbol});
        }
      });
    });

  }

  // https://github.com/select2/select2/issues/5993
  jQuery(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
  });

  // Keep focus on select2 for keyword navigation after the selection is picked
  jQuery(document).on('select2:close', (el) => {
    jQuery(el.target).focus();
  });
}

import { watch, ref } from 'vue';

let loading = ref(true);
const cw_theme = window.cw_theme;

if (cw_cmc) {
  init();
}
else {
  watch(store, (selection, prevSelection) => { 
    init();
  })
}

</script>


<style scoped>

/* here should be default styles, custom select2 styling is in main css */

.grid-select2 {
  display: grid;
  grid-template-rows: 31px 3px 15px;
}

/* Select 2 */
.selectcoin {
  width: 240px;
  height: 30px;
  background-color: white;
}


.selectcoin::-ms-expand  {
  display: none;
}

.selectcoin {
  -moz-appearance: none;
  -webkit-appearance: none;
  appearance: none;
}

.select2-container {
  text-align: left;
  box-sizing: border-box;
  display: inline-block;
  margin: 0;
  margin-bottom: 10px;
  position: relative;
  vertical-align: middle;
}

.select2-container .select2-selection--single {
  box-sizing: border-box;
  cursor: default;
  display: block;
  height: 30px;
  /* user-select: none;
  -webkit-user-select: none; */
  outline: none!important;
}

.select2-container .select2-selection--single:focus, .select2-container .select2-selection--single:hover {
  border: 1px solid #2e83ae!important;
}

.select2-search__field:focus, .select2-search__field:hover {
  border: 1px solid #2e83ae!important;
}

.select2-search__field {
  /* user-select: none;
  -webkit-user-select: none; */
  outline: none!important;
} 

.select2-container .select2-selection--single .select2-selection__rendered {
  display: block;
  padding-left: 6px;
  padding-right: 20px;
  font-size: 13px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.select2-container .select2-selection--single .select2-selection__clear {
  position: relative;
}

.select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
  padding-right: 8px;
  padding-left: 20px;
}

.select2-container .select2-selection--multiple {
  box-sizing: border-box;
  cursor: default;
  display: block;
  min-height: 32px;
  user-select: none;
  -webkit-user-select: none;
}

.select2-container .select2-selection--multiple .select2-selection__rendered {
  display: inline-block;
  overflow: hidden;
  padding-left: 8px;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.select2-container .select2-search--inline {
  float: left;
}

.select2-container .select2-search--inline .select2-search__field {
  box-sizing: border-box;
  border: none;
  font-size: 100%;
  margin-top: 5px;
  padding: 0;
}

.select2-container .select2-search--inline .select2-search__field::-webkit-search-cancel-button {
  -webkit-appearance: none;
}


.select2-dropdown {
  background-color: white;
  border: 1px solid #888;
  border-radius: 2px;
  box-sizing: border-box;
  display: block;
  position: absolute;
  left: -100000px;
  width: 100%;
  z-index: 1051;
}

.select2-results {
  display: block;
}

.select2-results__options {
  list-style: none;
  margin: 0;
  padding: 0;
}

.select2-results__option {
  padding: 6px;
  user-select: none;
  -webkit-user-select: none;
}

.select2-results__option[aria-selected] {
  cursor: default;
}

.select2-container--open .select2-dropdown {
  left: 0;
}

.select2-container--open .select2-dropdown--above {
  border-bottom: none;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

.select2-container--open .select2-dropdown--below {
  border-top: none;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.select2-search--dropdown {
  display: block;
  padding: 4px;
}

.select2-search--dropdown .select2-search__field {
  padding: 4px;
  width: 100%;
  box-sizing: border-box;
}

.select2-search--dropdown .select2-search__field::-webkit-search-cancel-button {
  -webkit-appearance: none;
}

.select2-search--dropdown.select2-search--hide {
  display: none;
}

.select2-close-mask {
  border: 0;
  margin: 0;
  padding: 0;
  display: block;
  position: fixed;
  left: 0;
  top: 0;
  min-height: 100%;
  min-width: 100%;
  height: auto;
  width: auto;
  opacity: 0;
  z-index: 99;
  background-color: #fff;
  filter: alpha(opacity=0);
}

.select2-hidden-accessible {
  border: 0 !important;
  clip: rect(0 0 0 0) !important;
  height: 1px !important;
  margin: -1px !important;
  overflow: hidden !important;
  padding: 0 !important;
  position: absolute !important;
  width: 1px !important;
}

.select2-container--default .select2-selection--single {
  background-color: #fff;
  border: 1px solid #888;
  border-radius: 2px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  background-color: transparent;
  color: #444;
  line-height: 30px;
}

.select2-container--default .select2-selection--single .select2-selection__clear {
  cursor: default;
  float: right;
  font-weight: bold;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
  color: #999;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 26px;
  position: absolute;
  top: 2px;
  right: 1px;
  width: 20px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
  border-color: #888 transparent transparent transparent;
  border-style: solid;
  border-width: 5px 4px 0 4px;
  height: 0;
  left: 50%;
  margin-left: -4px;
  margin-top: -2px;
  position: absolute;
  top: 50%;
  width: 0;
}

.select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__clear {
  float: left;
}

.select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {
  left: 1px;
  right: auto;
}

.select2-container--default.select2-container--disabled .select2-selection--single {
  background-color: #eee;
  cursor: default;
}

.select2-container--default.select2-container--disabled .select2-selection--single .select2-selection__clear {
  display: none;
}

.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
  border-color: transparent transparent #888 transparent;
  border-width: 0 4px 5px 4px;
}

.select2-container--default .select2-selection--multiple {
  background-color: white;
  border: 1px solid #888;
  border-radius: 2px;
  cursor: default;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
  box-sizing: border-box;
  list-style: none;
  margin: 0;
  padding: 0 5px;
  width: 100%;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
  list-style: none;
}

.select2-container--default .select2-selection--multiple .select2-selection__placeholder {
  color: #999;
  margin-top: 5px;
  float: left;
}

.select2-container--default .select2-selection--multiple .select2-selection__clear {
  cursor: default;
  float: right;
  font-weight: bold;
  margin-top: 5px;
  margin-right: 10px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #888;
  border-radius: 2px;
  cursor: default;
  float: left;
  margin-right: 5px;
  margin-top: 5px;
  padding: 0 5px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
  color: #999;
  cursor: default;
  display: inline-block;
  font-weight: bold;
  margin-right: 2px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
  color: #333;
}

.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice,
.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__placeholder,
.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-search--inline {
  float: right;
}

.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice {
  margin-left: 5px;
  margin-right: auto;
}

.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove {
  margin-left: 2px;
  margin-right: auto;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
  border: solid black 1px;
  outline: 0;
}

.select2-container--default.select2-container--disabled .select2-selection--multiple {
  background-color: #eee;
  cursor: default;
}

.select2-container--default.select2-container--disabled .select2-selection__choice__remove {
  display: none;
}

.select2-container--default.select2-container--open.select2-container--above .select2-selection--single,
.select2-container--default.select2-container--open.select2-container--above .select2-selection--multiple {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.select2-container--default.select2-container--open.select2-container--below .select2-selection--single,
.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
  border: 1px solid #aaa;
}

.select2-container--default .select2-search--inline .select2-search__field {
  background: transparent;
  border: none;
  outline: 0;
  box-shadow: none;
  -webkit-appearance: textfield;
}

.select2-container--default .select2-results>.select2-results__options {
  max-height: 200px;
  overflow-y: auto;
}

.select2-container--default .select2-results__option[role=group] {
  padding: 0;
}

.select2-container--default .select2-results__option[aria-disabled=true] {
  color: #999;
}

.select2-container--default .select2-results__option[aria-selected=true] {
  background-color: #ddd;
}

.select2-container--default .select2-results__option .select2-results__option {
  padding-left: 1em;
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__group {
  padding-left: 0;
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option {
  margin-left: -1em;
  padding-left: 2em;
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
  margin-left: -2em;
  padding-left: 3em;
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
  margin-left: -3em;
  padding-left: 4em;
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
  margin-left: -4em;
  padding-left: 5em;
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
  margin-left: -5em;
  padding-left: 6em;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
  background-color: #5897fb;
  color: white;
}

.select2-container--default .select2-results__group {
  cursor: default;
  display: block;
  padding: 6px;
}

.select2-container--classic .select2-selection--single {
  background-color: #f7f7f7;
  border: 1px solid #888;
  border-radius: 2px;
  outline: 0;
  background-image: -webkit-linear-gradient(top, white 50%, #eeeeee 100%);
  background-image: -o-linear-gradient(top, white 50%, #eeeeee 100%);
  background-image: linear-gradient(to bottom, white 50%, #eeeeee 100%);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFFFF', endColorstr='#FFEEEEEE', GradientType=0);
}

.select2-container--classic .select2-selection--single:focus {
  border: 1px solid #5897fb;
}

.select2-container--classic .select2-selection--single .select2-selection__rendered {
  color: #444;
  line-height: 30px;
}

.select2-container--classic .select2-selection--single .select2-selection__clear {
  cursor: default;
  float: right;
  font-weight: bold;
  margin-right: 10px;
}

.select2-container--classic .select2-selection--single .select2-selection__placeholder {
  color: #999;
}

.select2-container--classic .select2-selection--single .select2-selection__arrow {
  background-color: #ddd;
  border: none;
  border-left: 1px solid #888;
  border-top-right-radius: 2px;
  border-bottom-right-radius: 2px;
  height: 26px;
  position: absolute;
  top: 1px;
  right: 1px;
  width: 20px;
  background-image: -webkit-linear-gradient(top, #eeeeee 50%, #cccccc 100%);
  background-image: -o-linear-gradient(top, #eeeeee 50%, #cccccc 100%);
  background-image: linear-gradient(to bottom, #eeeeee 50%, #cccccc 100%);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFEEEEEE', endColorstr='#FFCCCCCC', GradientType=0);
}

.select2-container--classic .select2-selection--single .select2-selection__arrow b {
  border-color: #888 transparent transparent transparent;
  border-style: solid;
  border-width: 5px 4px 0 4px;
  height: 0;
  left: 50%;
  margin-left: -4px;
  margin-top: -2px;
  position: absolute;
  top: 50%;
  width: 0;
}

.select2-container--classic[dir="rtl"] .select2-selection--single .select2-selection__clear {
  float: left;
}

.select2-container--classic[dir="rtl"] .select2-selection--single .select2-selection__arrow {
  border: none;
  border-right: 1px solid #888;
  border-radius: 0;
  border-top-left-radius: 2px;
  border-bottom-left-radius: 2px;
  left: 1px;
  right: auto;
}

.select2-container--classic.select2-container--open .select2-selection--single {
  border: 1px solid #5897fb;
}

.select2-container--classic.select2-container--open .select2-selection--single .select2-selection__arrow {
  background: transparent;
  border: none;
}

.select2-container--classic.select2-container--open .select2-selection--single .select2-selection__arrow b {
  border-color: transparent transparent #888 transparent;
  border-width: 0 4px 5px 4px;
}

.select2-container--classic.select2-container--open.select2-container--above .select2-selection--single {
  border-top: none;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
  background-image: -webkit-linear-gradient(top, white 0%, #eeeeee 50%);
  background-image: -o-linear-gradient(top, white 0%, #eeeeee 50%);
  background-image: linear-gradient(to bottom, white 0%, #eeeeee 50%);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFFFF', endColorstr='#FFEEEEEE', GradientType=0);
}

.select2-container--classic.select2-container--open.select2-container--below .select2-selection--single {
  border-bottom: none;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  background-image: -webkit-linear-gradient(top, #eeeeee 50%, white 100%);
  background-image: -o-linear-gradient(top, #eeeeee 50%, white 100%);
  background-image: linear-gradient(to bottom, #eeeeee 50%, white 100%);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFEEEEEE', endColorstr='#FFFFFFFF', GradientType=0);
}

.select2-container--classic .select2-selection--multiple {
  background-color: white;
  border: 1px solid #888;
  border-radius: 2px;
  cursor: default;
  outline: 0;
}

.select2-container--classic .select2-selection--multiple:focus {
  border: 1px solid #5897fb;
}

.select2-container--classic .select2-selection--multiple .select2-selection__rendered {
  list-style: none;
  margin: 0;
  padding: 0 5px;
}

.select2-container--classic .select2-selection--multiple .select2-selection__clear {
  display: none;
}

.select2-container--classic .select2-selection--multiple .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #888;
  border-radius: 2px;
  cursor: default;
  float: left;
  margin-right: 5px;
  margin-top: 5px;
  padding: 0 5px;
}

.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove {
  color: #888;
  cursor: default;
  display: inline-block;
  font-weight: bold;
  margin-right: 2px;
}

.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove:hover {
  color: #555;
}

.select2-container--classic[dir="rtl"] .select2-selection--multiple .select2-selection__choice {
  float: right;
}

.select2-container--classic[dir="rtl"] .select2-selection--multiple .select2-selection__choice {
  margin-left: 5px;
  margin-right: auto;
}

.select2-container--classic[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove {
  margin-left: 2px;
  margin-right: auto;
}

.select2-container--classic.select2-container--open .select2-selection--multiple {
  border: 1px solid #5897fb;
}

.select2-container--classic.select2-container--open.select2-container--above .select2-selection--multiple {
  border-top: none;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.select2-container--classic.select2-container--open.select2-container--below .select2-selection--multiple {
  border-bottom: none;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

.select2-container--classic .select2-search--dropdown .select2-search__field {
  border: 1px solid #aaa;
  outline: 0;
}

.select2-container--classic .select2-search--inline .select2-search__field {
  outline: 0;
  box-shadow: none;
}

.select2-container--classic .select2-dropdown {
  background-color: white;
  border: 1px solid transparent;
}

.select2-container--classic .select2-dropdown--above {
  border-bottom: none;
}

.select2-container--classic .select2-dropdown--below {
  border-top: none;
}

.select2-container--classic .select2-results>.select2-results__options {
  max-height: 200px;
  overflow-y: auto;
}

.select2-container--classic .select2-results__option[role=group] {
  padding: 0;
}

.select2-container--classic .select2-results__option[aria-disabled=true] {
  color: grey;
}

.select2-container--classic .select2-results__option--highlighted[aria-selected] {
  background-color: #3875d7;
  color: white;
}

.select2-container--classic .select2-results__group {
  cursor: default;
  display: block;
  padding: 6px;
}

.select2-container--classic.select2-container--open .select2-dropdown {
  border-color: #5897fb;
}



/* CUSTOM DROP-DOWN SELECT  */
/* arrow color: C7C7C7 */

/* https://github.com/filamentgroup/select-css */
/* class applies to select element itself, not a wrapper element */
.select-css {
	display: block;
	font-size: 12px;
	color: rgb(199, 199, 199)!important;
	line-height: 1.3;
  width: 56px;
  height: 22px;
	margin: 0;
	border: 1px solid #aaa;
	box-shadow: 0 1px 0 1px rgba(0,0,0,.04);
  border-radius: 2px;
  padding-left:4px;
	-moz-appearance: none;
	-webkit-appearance: none;
	appearance: none;
	background-color: #333;
	background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23C7C7C7%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'), linear-gradient(to bottom, #3b3b3b 0%,#717171 100%);
	background-repeat: no-repeat, repeat;
	/* arrow icon position (1em from the right, 50% vertical) , then gradient position*/
	background-position: right 5px top 50%, 0 0;
	/* icon size, then gradient */
  background-size: .65em auto, 100%;

  position:absolute;
  top:15px;
  /* right:7px; */
  right:10px;
  font-family: 'Montserrat', sans-serif;
}

/* Hide arrow icon in IE browsers */
.select-css::-ms-expand {
	display: none;
}

.select-css:focus {
	border-color: #aaa;
	/* It'd be nice to use -webkit-focus-ring-color here but it doesn't work on box-shadow */
	/* box-shadow: 0 0 1px 3px rgba(59, 153, 252, .7);
	box-shadow: 0 0 0 3px -moz-mac-focusring; */
	color: #222; 
	outline: none;
}

/* Set options to normal weight */
.select-css option {
	font-weight:normal;
}

/* Disabled styles */
.select-css:disabled, .select-css[aria-disabled=true] {
	color: graytext;
	background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22graytext%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'), linear-gradient(to bottom, #3b3b3b 0%,#717171 100%);
}


/*  */





</style>