//
// Changing views with jQuery
//
jQuery('#link_manage_alerts').click(function(){
	jQuery("#container1").attr('style','display: none');
	jQuery("#container2").attr('style','display: none');
	jQuery("#container3").attr('style','display: table');
	jQuery("#container5").attr('style','display: none');
	jQuery("#link_manage_alerts").attr('style','display: none');
	jQuery("#link_new_alert").attr('style','display: inline');
	jQuery("#manage_alerts")[0].reset();
	jQuery("#feedback3").empty();
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
	return false;
});

jQuery('#link_new_alert').click(function(){
	jQuery("#container1").attr('style','display: table');
	jQuery("#container2").attr('style','display: none');
	jQuery("#container3").attr('style','display: none');
	jQuery("#container4").attr('style','display: none');
	jQuery("#container5").attr('style','display: none');	
	jQuery("#link_manage_alerts").attr('style','display: inline');
	jQuery("#link_new_alert").attr('style','display: none');
	jQuery("#above").val('');
	jQuery("#below").val('');
	jQuery("#email").val('');
	jQuery("cptch_input_16").val('');
	jQuery("#feedback").empty();
	jQuery("#ajax_loader").attr('style','display: none');
	jQuery("#create_alert_button").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
	return false;
});

jQuery('#link_create_alert').click(function(){
	jQuery("#container1").attr('style','display: table');
	jQuery("#container2").attr('style','display: none');
	jQuery("#container3").attr('style','display: none');
	jQuery("#container5").attr('style','display: none');
	jQuery("#link_manage_alerts").attr('style','display: inline');
	jQuery("#link_new_alert").attr('style','display: none');
	jQuery("#above").val('');
	jQuery("#below").val('');
	jQuery("#email").val('');
	jQuery("cptch_input_16").val('');
	jQuery("#feedback").empty();
	jQuery("#ajax_loader").attr('style','display: none');
	jQuery("#create_alert_button").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
	return false;
});

jQuery('#link_create_alert2').click(function(){
	jQuery("#container1").attr('style','display: table');
	jQuery("#container2").attr('style','display: none');
	jQuery("#container3").attr('style','display: none');
	jQuery("#container5").attr('style','display: none');
	jQuery("#link_manage_alerts").attr('style','display: inline');
	jQuery("#link_new_alert").attr('style','display: none');
	jQuery("#above").val('');
	jQuery("#below").val('');
	jQuery("#email").val('');
	jQuery("cptch_input_16").val('');
	jQuery("#feedback").empty();
	jQuery("#ajax_loader").attr('style','display: none');
	jQuery("#create_alert_button").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
	return false;
});

jQuery('#link_tips_tricks').click(function(){
	jQuery("#container1").attr('style','display: none');
	jQuery("#container2").attr('style','display: none');
	jQuery("#container3").attr('style','display: none');
	jQuery("#container4").attr('style','display: none');
	jQuery("#container5").attr('style','display: table');
	jQuery("#link_new_alert").attr('style','display: none');
	jQuery("#link_manage_alerts").attr('style','display: inline');
	jQuery("#ajax_loader").attr('style','display: none');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
	return false;
});


//
// Ajax to delete alert
//
jQuery('#feedback4').on('click', '#form_delete_alert', function ajaxSubmit2(){
	jQuery(this).parent().remove(); 
	var delete_alert = jQuery(this).serialize();
	
	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: delete_alert+security_url
	}); 
	
	jQuery(this).remove(); 
	return false;
});


//
// Ajax to manage alerts - get data
//
jQuery('#manage_alerts').submit(ajaxSubmit);

function ajaxSubmit(){
	var manage_alerts = jQuery(this).serialize();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: manage_alerts+security_url,
	success:function(data){
	if (data.substring(0, 5) == "<div>") {
		jQuery("#feedback4").html(data);
		jQuery("#container3").attr('style','display: none');
		jQuery("#container4").attr('style','display: table; background: white;');
	}
	else {	
		jQuery("#feedback3").html(data.substring(0, data.length - 1));
	}
	}
	}); 
	return false;
}


//
// Ajax to create new alert
//
jQuery('#form_new_alert').submit(validate);

function validate() {
	var email = document.getElementById('email').value;
	var emailFilter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;

	if (!emailFilter.test(email)) {
		jQuery("#feedback").html('Please enter a valid e-mail address.');
		return false;
	}

	var above = jQuery("#above").val().length;
	var below = jQuery("#below").val().length;

	if (below + above < 1) {
		jQuery("#feedback").html('Please enter at least 1 price value.');
		return false;
	}

	var form_new_alert = jQuery(this).serialize();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert+security_url,
	beforeSend: function(){
		jQuery("#create_alert_button").attr('style','display: none');
		jQuery("#ajax_loader").attr('style','display: inline').attr('style','margin-top: 33px');
	},
	success:function(data){

	if (data != 'Please enter a valid CAPTCHA value.0') {
		jQuery("#container1").attr('style','display: none');
		jQuery("#container2").attr('style','display: table;border-radius:3px;padding-top:25px');
	}
	else
	{
		jQuery("#feedback").html(data.substring(0, data.length - 1));
		jQuery("#create_alert_button").attr('style','display: inline');
		jQuery("#ajax_loader").attr('style','display: none');
	}
	}
	}); 
	return false;
}


//
// Show current price
//
function showprice() {
	for(var i = 0; i < jqueryarray.length; i++) {
		var coin = jqueryarray[i];
		if (coin['symbol'] == jQuery("#symbol").val()) {
			jQuery("#pricediv").html("Price now: " + coin['price_btc'] + " BTC | " + coin['price_usd'] + " USD");
		}
	}
}

jQuery(document).ready(function () {
	showprice();
});

jQuery("#symbol").change(function () {
	showprice();
});



//
// Change currency
//
function toggleBTC() {
if (jQuery("#symbol").val() == "BTC") {
	jQuery("#above_currency").val("USD");
	jQuery("#below_currency").val("USD");
}
else {
	jQuery("#above_currency").val("BTC");
	jQuery("#below_currency").val("BTC");
}
}

jQuery(document).ready(function () {
	toggleBTC();
});

jQuery("#symbol").change(function () {
	toggleBTC();
});



//
// Coin symbol to pass for the backend
//
/* jQuery('#symbol').change(function(){
var regExp = /\(([^)]+)\)/;
var matches = regExp.exec(jQuery("#symbol option:selected").text());		
jQuery("#symbol").val(matches[1]);
}); */
jQuery('#symbol').change(function(){
	myStr = jQuery("#symbol option:selected").text();
	var coin = myStr.substring(0, myStr.indexOf(' ('))
	// var coin = jQuery("#symbol").val();
	jQuery("#coin").val(coin);
});



//
// Get coin symbol from url
//
/* var urlSymbol = window.location.pathname.replace(/^\/([^\/]*).*$/, '$1');
console.log(urlSymbol); */
var link = document.location.href.split('/');
// console.log(link[4]);
var urlSymbol = link[3].replace(/[^a-z0-9]/gi,'');
var urlSymbolUp = urlSymbol.toUpperCase();
if (urlSymbol) {
	jQuery("#symbol").val(urlSymbolUp).trigger("change");
}