//
// jQuery dynamic link handler
//
function initialLoad() {
	var hash = window.location.hash;
	if (hash == "#sms" || hash == "#settings") {
		jQuery(".switch-input").prop('checked', true);
	}
	if (hash) {
		jQuery(".container").attr('style','display: none');
		jQuery(hash).attr('style','display: table');
		jQuery("html,body").animate({ scrollTop: 0 }, 100);
	}
	else {
		jQuery("#email").attr('style','display: table');
	}
}
window.onload = initialLoad();


jQuery(".switch-input").click( function(){
	if( jQuery(this).is(':checked') ) {
		jQuery("#percentbutton").attr("class", "circlegrey");
		jQuery("#currencybutton").attr("class", "circle");
	}
 });

window.onload = function() {
	var hash = window.location.hash;
	if(hash == "#manage_alerts_acc") {
		jQuery('form#manage_alerts_acc_form').submit();
	}
}


jQuery('#manage_alerts_acc_link').click( function() {
	jQuery('form#manage_alerts_acc_form').submit();
});


window.onhashchange = function() {
	var hash = window.location.hash;
	if (hash) {
		jQuery(".container").attr('style','display: none');
		jQuery(hash).attr('style','display: table');
		jQuery("html,body").animate({ scrollTop: 0 }, 100);
	}
}


jQuery(".hashlink").click(function() {
	initialLoad();
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
	jQuery(".feedback").empty();	
});


jQuery("#newalertemail").click(function() {
	initialLoad();
	jQuery("#above").val('');
	jQuery("#below").val('');
	jQuery("#email").val('');
	jQuery("cptch_input_16").val('');
	jQuery("#ajax_loader").attr('style','display: none');
	jQuery("#create_alert_button").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
});


jQuery("#newalertaccsms").click(function() {
	initialLoad();
	jQuery("#above_sms").val('');
	jQuery("#below_sms").val('');
	jQuery("#phone").val(user_phone_nr);	
	jQuery("#email_sms").val('');
	jQuery("#ajax_loader_sms").attr('style','display: none');
	jQuery("#create_alert_button_sms").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
});


jQuery("#newalertaccemail").click(function() {
	jQuery("#above_acc").val('');
	jQuery("#below_acc").val('');
	jQuery("#email_acc").val(user_email);
	jQuery("#ajax_loader_acc").attr('style','display: none');
	jQuery("#create_alert_button_acc").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
});


jQuery("#newalertemailpercent").click(function() {
	jQuery("#plus_percent").val('');
	jQuery("#minus_percent").val('');
	jQuery("#email_percent").val('');
	jQuery("cptch_input_16").val('');
	jQuery("#ajax_loader_percent").attr('style','display: none');
	jQuery("#create_alert_button_percent").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
});


jQuery("#newalertemailpercentbutton").click(function() {
	jQuery("#plus_percent").val('');
	jQuery("#minus_percent").val('');
	jQuery("#email_percent").val('');
	jQuery("cptch_input_16").val('');
	jQuery("#ajax_loader_percent").attr('style','display: none');
	jQuery("#create_alert_button_percent").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
	jQuery("#percentbutton").attr("class", "circle");
	jQuery("#currencybutton").attr("class", "circlegrey");
	jQuery(".switch-input").attr('checked', false);
	jQuery("#switch-label-email").attr('style','color: rgba(0, 0, 0, 0.65); text-shadow: 0 1px rgba(255, 255, 255, 0.25); font-weight: bold;');
});


jQuery("#newalertemailbutton").click(function() {
	initialLoad();
	jQuery("#above").val('');
	jQuery("#below").val('');
	jQuery("#email").val('');
	jQuery("cptch_input_16").val('');
	jQuery("#ajax_loader").attr('style','display: none');
	jQuery("#create_alert_button").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
	jQuery("#percentbutton").attr("class", "circlegrey");
	jQuery("#currencybutton").attr("class", "circle");
});


jQuery("#newalertemailpercentacc").click(function() {
	jQuery("#plus_percent_acc").val('');
	jQuery("#minus_percent_acc").val('');
	jQuery("#email_percent_acc").val(user_email);
	jQuery("#ajax_loader_percent_acc").attr('style','display: none');
	jQuery("#create_alert_button_percent_acc").attr('style','display: inline');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);
});


jQuery("#smsSwitch").click(function() {
	var hash = "#sms";
	window.location.hash = hash;
	jQuery(".container").attr('style','display: none');
	jQuery(hash).attr('style','display: table');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);

	jQuery("#above_sms").val('');
	jQuery("#below_sms").val('');
	jQuery("#phone").val(user_phone_nr);	
	jQuery("#email_sms").val('');
	jQuery("#ajax_loader_sms").attr('style','display: none');
	jQuery("#create_alert_button_sms").attr('style','display: inline');
});


jQuery("#emailSwitch").click(function() {
	var hash = "#email";
	window.location.hash = hash;
	jQuery(".container").attr('style','display: none');
	jQuery(hash).attr('style','display: table');
	jQuery("html,body").animate({ scrollTop: 0 }, 100);

	jQuery("#above_acc").val('');
	jQuery("#below_acc").val('');
	jQuery("#email_acc").val(user_email);
	jQuery("#ajax_loader_acc").attr('style','display: none');
	jQuery("#create_alert_button_acc").attr('style','display: inline');

	jQuery("#above").val('');
	jQuery("#below").val('');
	jQuery("#email_out").val('');
	jQuery("#ajax_loader").attr('style','display: none');
	jQuery("#create_alert_button").attr('style','display: inline');
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
	if (data.substring(0, 4) == "<br>") {
		jQuery("#feedback4").html(data);
		jQuery("#container3").attr('style','display: none');
		jQuery("#container4").attr('style','display: table; background: white;');
		jQuery("#manage_alerts_loader").attr('style','display: none');
	}
	else {	
		jQuery("#feedback3").html(data.substring(0, data.length - 1));
	}
	}
	}); 
	return false;
}



//
// Ajax to manage alerts - get data - ACC
//
jQuery('#manage_alerts_acc_form').submit(ajaxSubmit_acc);

function ajaxSubmit_acc(){
	var manage_alerts = jQuery(this).serialize();
	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: manage_alerts+security_url,
	success:function(data){
	if (data.substring(0, 4) == "<br>") {
		jQuery("#manage_alerts_acc_feedback").html(data);
		jQuery("#manage1").attr('style','display: none');
		jQuery("#manage_alerts_acc_loader").attr('style','display: none');
		jQuery("#manage2").attr('style','display: table; background: white;');
	}
	else {	
		jQuery("#manage_alerts_acc_feedback").html(data.substring(0, data.length - 1));
	}
	}
	}); 
	return false;
}



//
// Ajax to delete alert
//
jQuery('#feedback4').on('click', '#form_delete_alert', function (){
	jQuery(this).parent().parent().remove(); 
	var delete_alert = jQuery(this).serialize();
	
	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: delete_alert+security_url
	}); 

	return false;
});



//
// Ajax to delete alert percent
//
jQuery('#feedback4').on('click', '#form_delete_alert_percent', function (){
	jQuery(this).parent().parent().remove(); 
	var delete_alert_percent = jQuery(this).serialize();
	
	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: delete_alert_percent+security_url
	}); 

	return false;
});



//
// Ajax to delete alert percent acc
//
jQuery('#manage_alerts_acc_feedback').on('click', '#form_delete_alert_percent_acc', function (){
	jQuery(this).parent().parent().remove(); 
	var delete_alert_percent_acc = jQuery(this).serialize();
	
	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: delete_alert_percent_acc+security_url
	}); 

	return false;
});



//
// Ajax to delete alert sms acc
//
jQuery('#manage_alerts_acc_feedback').on('click', '#delete_alert_acc_sms_form', function (){
	jQuery(this).parent().parent().remove(); 
	var delete_alert = jQuery(this).serialize();
	
	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: delete_alert+security_url
	}); 
	
	return false;
});



//
// Ajax to delete alert email acc
//
jQuery('#manage_alerts_acc_feedback').on('click', '#delete_alert_acc_email_form', function (){
	jQuery(this).parent().parent().remove(); 
	var delete_alert = jQuery(this).serialize();
	
	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: delete_alert+security_url
	}); 
	
	return false;
});



//
// Ajax to create new email PERCENT alert WITHOUT ACC
//
jQuery('#form_new_alert_percent').submit(validate_percent);

function validate_percent() {
	var email = document.getElementById('email_percent').value;
	var emailFilter = /^([a-zA-Z+0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;

	if (!emailFilter.test(email)) {
		jQuery("#feedback_percent").html('Please enter a valid e-mail address.');
		return false;
	}

	var above = jQuery("#plus_percent").val().length;
	var below = jQuery("#minus_percent").val().length;

	if (below + above < 1) {
		jQuery("#feedback_percent").html('Please enter at least 1 price value.');
		return false;
	}

	var form_new_alert_percent = jQuery(this).serialize();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert_percent+security_url,
	beforeSend: function(){
		jQuery("#create_alert_button_percent").attr('style','display: none');
		jQuery("#ajax_loader_percent").attr('style','display: inline').attr('style','margin-top: 33px');
	},
	success:function(data){

		jQuery(".container").attr('style','display: none');
		jQuery("#created_alert_percent").attr('style','display: table;border-radius:3px;padding-top:25px');
	
	}
	}); 
	return false;
}



//
// Ajax to create new email PERCENT alert WITH ACC
//
jQuery('#form_new_alert_percent_acc').submit(validate_percent_acc);

function validate_percent_acc() {
	var email = document.getElementById('email_percent_acc').value;
	var emailFilter = /^([a-zA-Z+0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;

	if (!emailFilter.test(email)) {
		jQuery("#feedback_percent_acc").html('Please enter a valid e-mail address.');
		return false;
	}

	var above = jQuery("#plus_percent_acc").val().length;
	var below = jQuery("#minus_percent_acc").val().length;

	if (below + above < 1) {
		jQuery("#feedback_percent_acc").html('Please enter at least 1 price value.');
		return false;
	}

	var form_new_alert_percent_acc = jQuery(this).serialize();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert_percent_acc+security_url,
	beforeSend: function(){
		jQuery("#create_alert_button_percent_acc").attr('style','display: none');
		jQuery("#ajax_loader_percent_acc").attr('style','display: inline').attr('style','margin-top: 33px');
	},
	success:function(data){

		jQuery(".container").attr('style','display: none');
		jQuery("#created_alert_percent_acc").attr('style','display: table;border-radius:3px;padding-top:25px');
	
	}
	}); 
	return false;
}



//
// Ajax to create new alert
//
jQuery('#form_new_alert').submit(validate);

function validate() {
	var email_out = document.getElementById('email_out').value;
	var emailFilter = /^([a-zA-Z+0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	console.log(email_out);
	if (!emailFilter.test(email_out)) {
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

	if (data == 'Please enter a valid CAPTCHA value.0')	{
		jQuery("#feedback").html(data.substring(0, data.length - 1));
		jQuery("#create_alert_button").attr('style','display: inline');
		jQuery("#ajax_loader").attr('style','display: none');
	}
	else if (data == 'Limit error') {
		jQuery("#feedback").html("You have reached the limit of 10 alerts. To continue, delete some alerts first or create a <a href='/account/?#signup' style='color:red!important;'>free account</a> for unlimited alerts.");
		jQuery("#create_alert_button").attr('style','display: inline');
		jQuery("#ajax_loader").attr('style','display: none');
	}
	else {
		jQuery("#email").attr('style','display: none');
		jQuery("#created_alert").attr('style','display: table;border-radius:3px;padding-top:25px');
	}
	}
	}); 
	return false;
}



//
// Ajax to create new email alert with ACC
//
jQuery('#form_new_alert_acc').submit(validate_acc);

function validate_acc() {
	var email = document.getElementById('email_acc').value;
	var emailFilter = /^([a-zA-Z+0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;

	if (!emailFilter.test(email)) {
		jQuery("#feedback_acc").html('Please enter a valid e-mail address.');
		return false;
	}

	var above = jQuery("#above_acc").val().length;
	var below = jQuery("#below_acc").val().length;

	if (below + above < 1) {
		jQuery("#feedback_acc").html('Please enter at least 1 price value.');
		return false;
	}

	var form_new_alert_acc = jQuery(this).serialize();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert_acc+security_url,
	beforeSend: function(){
		jQuery("#create_alert_button_acc").attr('style','display: none');
		jQuery("#ajax_loader_acc").attr('style','display: inline').attr('style','margin-top: 33px');
	},
	success:function(data){

		jQuery(".container").attr('style','display: none');
		jQuery("#created_alert_acc_email").attr('style','display: table;border-radius:3px;padding-top:25px');
	
	}
	}); 
	return false;
}



//
// Ajax to create new alert SMS
//
jQuery('#form_new_alert_sms').submit(validate_sms);

function validate_sms() {
	
	var above = jQuery("#above_sms").val().length;
	var below = jQuery("#below_sms").val().length;

	if (below + above < 1) {
		jQuery("#feedback_sms").html('Please enter at least 1 price value.');
		return false;
	}

	var form_new_alert_sms = jQuery(this).serialize();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert_sms+security_url,
	beforeSend: function(){
		jQuery("#create_alert_button_sms").attr('style','display: none');
		jQuery("#ajax_loader_sms").attr('style','display: inline').attr('style','margin-top: 33px');
	},
	success:function(data){

	if (data != 'Please enter a valid CAPTCHA value.0') {
		jQuery(".container").attr('style','display: none');
		jQuery("#created_alert_acc_sms").attr('style','display: table;border-radius:3px;padding-top:25px');
		jQuery("#ajax_loader_sms").attr('style','display: none');
		jQuery("#create_alert_button_sms").attr('style','display: inline');
	}
	else
	{
		jQuery("#feedback_sms").html(data.substring(0, data.length - 1));
		jQuery("#create_alert_button").attr('style','display: inline');
		jQuery("#ajax_loader").attr('style','display: none');
	}
	}
	}); 
	return false;
}



//
// Ajax to delete acc
//
jQuery('#delete_my_acc_button').click(function() {
	if (confirm("Are you sure?") == true) {
		
		jQuery.ajax({
			type: "POST",
			url: ajax_url,
			data: 'action=delete_my_acc'+security_url,
			success: function(response){
				window.location.href = "https://coinwink.com";
			}
		});

	}
});



//
// Show current price and add price to percent input
//
function showprice() {
	for(var i = 0; i < jqueryarray.length; i++) {
		var coin = jqueryarray[i];
		if (coin['id'] == jQuery("#id").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			jQuery("#pricediv").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['id'] +"'><img style='vertical-align:middle;' width='12' src='/coins/16x16/"+
			coin['id']+
			".png'></a><span style='position:relative;top:2px;'> = " + coin['price_btc'] + " BTC | " + eth + " ETH | " + coin['price_usd'] + " USD</span>");
		
			jQuery("#coin").val(coin['name']);
			jQuery("#symbol").val(coin['symbol']);
		}
		if (coin['id'] == jQuery("#id_sms").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			jQuery("#pricediv_sms").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['id'] +"'><img style='vertical-align:middle;' width='12' src='/coins/16x16/"+
			coin['id']+
			".png'></a><span style='position:relative;top:2px;'> = " + coin['price_btc'] + " BTC | " + eth + " ETH | " + coin['price_usd'] + " USD</span>");
				
			jQuery("#coin_sms").val(coin['name']);
			jQuery("#symbol_sms").val(coin['symbol']);
		}
		if (coin['id'] == jQuery("#id_acc").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			jQuery("#pricediv_acc").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['id'] +"'><img style='vertical-align:middle;' width='12' src='/coins/16x16/"+
			coin['id']+
			".png'></a><span style='position:relative;top:2px;'> = " + coin['price_btc'] + " BTC | " + eth + " ETH | " + coin['price_usd'] + " USD</span>");
				
			jQuery("#coin_acc").val(coin['name']);
			jQuery("#symbol_acc").val(coin['symbol']);
		}
		if (coin['id'] == jQuery("#id_percent").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			jQuery("#pricediv_percent").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['id'] +"'><img style='vertical-align:middle;' width='12' src='/coins/16x16/"+
			coin['id']+
			".png'></a><span style='position:relative;top:2px;'> = " + coin['price_btc'] + " BTC | " + eth + " ETH | " + coin['price_usd'] + " USD</span>");
			jQuery("#price_set_btc").val(coin['price_btc']);
			jQuery("#price_set_usd").val(coin['price_usd']);
			jQuery("#price_set_eth").val(coin['price_eth']);

			jQuery("#coin_percent").val(coin['name']);
			jQuery("#symbol_percent").val(coin['symbol']);
		}
		if (coin['id'] == jQuery("#id_percent_acc").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			jQuery("#pricediv_percent_acc").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['id'] +"'><img style='vertical-align:middle;' width='12' src='/coins/16x16/"+
			coin['id']+
			".png'></a><span style='position:relative;top:2px;'> = " + coin['price_btc'] + " BTC | " + eth + " ETH | " + coin['price_usd'] + " USD</span>");
			jQuery("#price_set_btc_acc").val(coin['price_btc']);
			jQuery("#price_set_usd_acc").val(coin['price_usd']);
			jQuery("#price_set_eth_acc").val(coin['price_eth']);

			jQuery("#coin_percent_acc").val(coin['name']);
			jQuery("#symbol_percent_acc").val(coin['symbol']);
		}
	}
}

jQuery(document).ready(function () {
	showprice();
});

jQuery("#id").change(function () {
	showprice();
});

jQuery("#id_sms").change(function () {
	showprice();
});

jQuery("#id_acc").change(function () {
	showprice();
});

jQuery("#id_percent").change(function () {
	showprice();
});

jQuery("#id_percent_acc").change(function () {
	showprice();
});



//
// Change currency
//
function toggleBTC() {
if (jQuery("#id").val() == "bitcoin") {
	jQuery("#above_currency").val("USD");
	jQuery("#below_currency").val("USD");
}
else {
	jQuery("#above_currency").val("BTC");
	jQuery("#below_currency").val("BTC");
}
if (jQuery("#id_sms").val() == "bitcoin") {
	jQuery("#above_currency_sms").val("USD");
	jQuery("#below_currency_sms").val("USD");
}
else {
	jQuery("#above_currency_sms").val("BTC");
	jQuery("#below_currency_sms").val("BTC");
}
if (jQuery("#id_acc").val() == "bitcoin") {
	jQuery("#above_currency_acc").val("USD");
	jQuery("#below_currency_acc").val("USD");
}
else {
	jQuery("#above_currency_acc").val("BTC");
	jQuery("#below_currency_acc").val("BTC");
}
if (jQuery("#id_percent").val() == "bitcoin") {
	jQuery("#plus_compared").val("USD");
	jQuery("#minus_compared").val("USD");
}
else {
	jQuery("#plus_compared").val("BTC");
	jQuery("#minus_compared").val("BTC");
}
if (jQuery("#id_percent_acc").val() == "bitcoin") {
	jQuery("#plus_compared_acc").val("USD");
	jQuery("#minus_compared_acc").val("USD");
}
else {
	jQuery("#plus_compared_acc").val("BTC");
	jQuery("#minus_compared_acc").val("BTC");
}
}


jQuery(document).ready(function () {
	toggleBTC();
});

jQuery("#id").change(function () {
	toggleBTC();
});

jQuery("#id_sms").change(function () {
	toggleBTC();
});

jQuery("#id_acc").change(function () {
	toggleBTC();
});

jQuery("#id_percent").change(function () {
	toggleBTC();
});

jQuery("#id_percent_acc").change(function () {
	toggleBTC();
});



//
// Coin symbol to pass for the backend
//
jQuery('#id').change(function(){
	myStr = jQuery("#id option:selected").text();
	var coin = myStr.substring(0, myStr.indexOf(' ('));
	jQuery("#coin").val(coin);
	var symbol = myStr.substring(myStr.indexOf('(') + 1, myStr.indexOf(')'),);
	jQuery("#symbol").val(symbol);
});

jQuery('#id_sms').change(function(){
	myStr = jQuery("#id_sms option:selected").text();
	var coin = myStr.substring(0, myStr.indexOf(' ('));
	jQuery("#coin_sms").val(coin);
	var symbol = myStr.substring(myStr.indexOf('(') + 1, myStr.indexOf(')'),);
	jQuery("#symbol_sms").val(symbol);
});

jQuery('#id_acc').change(function(){
	myStr = jQuery("#id_acc option:selected").text();
	var coin = myStr.substring(0, myStr.indexOf(' ('));
	jQuery("#coin_acc").val(coin);
	var symbol = myStr.substring(myStr.indexOf('(') + 1, myStr.indexOf(')'),);
	jQuery("#symbol_acc").val(symbol);
});

jQuery('#id_percent').change(function(){
	myStr = jQuery("#id_percent option:selected").text();
	var coin = myStr.substring(0, myStr.indexOf(' ('));
	jQuery("#coin_percent").val(coin);
	var symbol = myStr.substring(myStr.indexOf('(') + 1, myStr.indexOf(')'),);
	jQuery("#symbol_percent").val(symbol);
});

jQuery('#id_percent_acc').change(function(){
	myStr = jQuery("#id_percent_acc option:selected").text();
	var coin = myStr.substring(0, myStr.indexOf(' ('));
	jQuery("#coin_percent_acc").val(coin);
	var symbol = myStr.substring(myStr.indexOf('(') + 1, myStr.indexOf(')'),);
	jQuery("#symbol_percent_acc").val(symbol);
});

jQuery('#plus_change').change(function(){
	plusChange = jQuery("#plus_change option:selected").text();
	if ((plusChange == "in 1h. period") || (plusChange == "in 24h. period")) {
		jQuery("#div_plus_compared").hide();
		jQuery("#plus_usd").show();
		jQuery("#plus_compared").val("USD");
	}
	if (plusChange == "from now") {
		jQuery("#div_plus_compared").show();
		jQuery("#plus_usd").hide();
		jQuery("#plus_compared").val("BTC");
	}
});

jQuery('#minus_change').change(function(){
	minusChange = jQuery("#minus_change option:selected").text();
	if ((minusChange == "in 1h. period") || (minusChange == "in 24h. period")) {
		jQuery("#div_minus_compared").hide();
		jQuery("#minus_usd").show();
		jQuery("#minus_compared").val("USD");
	}
	if (minusChange == "from now") {
		jQuery("#div_minus_compared").show();
		jQuery("#minus_usd").hide();
		jQuery("#minus_compared").val("BTC");
	}
});

jQuery('#plus_change_acc').change(function(){
	plusChange = jQuery("#plus_change_acc option:selected").text();
	if ((plusChange == "in 1h. period") || (plusChange == "in 24h. period")) {
		jQuery("#div_plus_compared_acc").hide();
		jQuery("#plus_usd_acc").show();
		jQuery("#plus_compared_acc").val("USD");
	}
	if (plusChange == "from now") {
		jQuery("#div_plus_compared_acc").show();
		jQuery("#plus_usd_acc").hide();
		jQuery("#plus_compared_acc").val("BTC");
	}
});

jQuery('#minus_change_acc').change(function(){
	minusChange = jQuery("#minus_change_acc option:selected").text();
	if ((minusChange == "in 1h. period") || (minusChange == "in 24h. period")) {
		jQuery("#div_minus_compared_acc").hide();
		jQuery("#minus_usd_acc").show();
		jQuery("#minus_compared_acc").val("USD");
	}
	if (minusChange == "from now") {
		jQuery("#div_minus_compared_acc").show();
		jQuery("#minus_usd_acc").hide();
		jQuery("#minus_compared_acc").val("BTC");
	}
});



// Show reserved message
setInterval(function() {
	var val1 = jQuery('#above_sms').val();
	var val2 = jQuery('#below_sms').val();

	if ((val1 && !val2) || (val2 && !val1)) {
		jQuery('#reserved_message').html("1 SMS alert");
	}
	else if (val1 && val2) {
		jQuery('#reserved_message').html("2 SMS alerts");
	}
	else if (!val1 && !val2) {
		jQuery('#reserved_message').html("");
	}
}, 100);