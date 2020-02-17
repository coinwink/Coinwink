// default location
var switchLocation = "email";

// navigation
var router = new Navigo(homePath);

function reloadPortfolio() {
  jQuery("#portfolio_empty").hide();
  jQuery("#portfolio-message").hide();
  if (router.lastRouteResolved().url == "/portfolio" || router.lastRouteResolved().url == "/portfolio/") {
    selectedCurrency = 'USD';
    jQuery(".containerloader").show();

    document.title = "Crypto Portfolio - Track & Manage Your Cryptocurrency Assets";
    jQuery(".current-view").hide();
    jQuery('#portfolio').show();
    jQuery('#portfolio-alerts-content').hide();
    jQuery(".feedback").empty();
    toTop();  
    openPortfolio();
    openPortfolioAlerts();

    jQuery(".containerloader").hide();
  }
}

function reloadWatchlist() {
  jQuery("#watchlist_empty").hide();
  jQuery("#watchlist-message").hide();
  if (router.lastRouteResolved().url == "/watchlist" || router.lastRouteResolved().url == "/watchlist/") {
    selectedCurrency = 'USD';
    jQuery(".containerloader").show();

    document.title = "Crypto Watchlist - Keep an Eye on Promising Cryptocurrencies";
    jQuery(".current-view").hide();
    jQuery('#watchlist').show();
    jQuery('#watchlist-alerts-content').hide();
    jQuery(".feedback").empty();
    toTop();
    console.log('open watchlist')
    openWatchlist();
    // openPortfolioAlerts();

    jQuery(".containerloader").hide();
  }
}

function reloadManageAlerts() {
  if (router.lastRouteResolved().url == "/manage-alerts" || router.lastRouteResolved().url == "/manage-alerts/") {
    jQuery(".containerloader").show();

    document.title = "Coinwink - Manage Alerts";
    jQuery(".current-view").hide();
    jQuery("#manage-alerts").show();
    toTop();
    jQuery("#manage_alerts_acc_loader").show();
    jQuery("#manage_alerts_acc_feedback").html("");
    ajaxSubmit_acc();

    jQuery(".containerloader").hide();
  }
}


router
  .on(function () {
    jQuery(".containerloader").show();

    switchLocation = "email";
    jQuery("#email").show();

    jQuery(".containerloader").hide();
  })
  .resolve();


router
  .on({

    '/watchlist': function () {
      selectedCurrency = 'USD';
      jQuery(".containerloader").show();

      document.title = "Crypto Watchlist - Keep an Eye on Promising Cryptocurrencies";
      jQuery(".current-view").hide();
      jQuery('#watchlist').show();
      // jQuery('#portfolio-alerts-content').hide();
      jQuery(".feedback").empty();
      toTop();  
      openWatchlist();
      // openPortfolioAlerts();

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },
    '/portfolio': function () {
      selectedCurrency = 'USD';
      jQuery(".containerloader").show();

      document.title = "Crypto Portfolio - Track & Manage Your Cryptocurrency Assets";
      jQuery(".current-view").hide();
      jQuery('#portfolio').show();
      jQuery('#portfolio-alerts-content').hide();
      jQuery(".feedback").empty();
      toTop();  
      openPortfolio();
      openPortfolioAlerts();

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/manage-alerts': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Manage Alerts";
      jQuery(".current-view").hide();
      jQuery("#manage-alerts").show();
      toTop();
      jQuery("#manage_alerts_acc_loader").show();
      jQuery("#manage_alerts_acc_feedback").html("");
      ajaxSubmit_acc();

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/email': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Email Crypto Price Alerts";
      clear_email();
      jQuery(".current-view").hide();
      jQuery("#email").show();

      switchLocation = "email";

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/email-per': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Email Crypto Percentage Alerts";
      clear_email_per();
      jQuery(".current-view").hide();
      jQuery("#email-per").show();

      jQuery("#smsSwitch").prop('checked', false);
      jQuery("#perSwitch").prop('checked', true);
      switchLocation = "email-per";

      jQuery("#switch-cur-init").hide();
      jQuery("#switch-per").show();

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/sms': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - SMS Crypto Price Alerts";
      clear_sms();
      jQuery(".current-view").hide();
      jQuery("#sms").show();

      jQuery("#smsSwitch").prop('checked', true);
      switchLocation = "sms";

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/sms-per': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - SMS Crypto Percentage Alerts";
      clear_sms_per();
      jQuery(".current-view").hide();
      jQuery("#sms-per").show();

      jQuery("#smsSwitch").prop('checked', true);
      jQuery("#perSwitch").prop('checked', true);
      switchLocation = "sms-per";

      jQuery("#switch-cur-init").hide();
      jQuery("#switch-per").show();

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/subscription': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Subscribe to Premium Plan";
      jQuery(".current-view").hide();
      jQuery("#subscription").show();
      toTop();

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/about': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - About";
      jQuery(".current-view").hide();
      jQuery("#about").show();
      toTop();

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/pricing': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Pricing";
      jQuery(".current-view").hide();

      jQuery(".containerloader").hide();

      jQuery("#pricing").show();
      toTop();

      jQuery('.coin_page').remove();
    },

    '/terms': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Terms and Conditions";
      jQuery(".current-view").hide();

      jQuery(".containerloader").hide();

      jQuery("#terms").show();
      toTop();

      jQuery('.coin_page').remove();
    },

    '/privacy': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Privacy Policy";
      jQuery(".current-view").hide();

      jQuery(".containerloader").hide();

      jQuery("#privacy").show();
      toTop();

      jQuery('.coin_page').remove();
    },

    '/press': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Press";
      jQuery(".current-view").hide();

      jQuery(".containerloader").hide();

      jQuery("#press").show();
      toTop();

      jQuery('.coin_page').remove();
    },

    '/contacts': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Contacts";
      jQuery(".current-view").hide();

      jQuery(".containerloader").hide();

      jQuery("#contacts").show();
      toTop();

      jQuery('.coin_page').remove();
    },

    '*': function () {
      jQuery(".containerloader").show();
      
      console.log('custom coin page');

      clear_email();
      jQuery(".current-view").hide();
      jQuery(".containerloader").hide();

      jQuery("#email").show();

      switchLocation = "email";
    },
  })
  .resolve();


function toTop() {
  jQuery("html,body").animate({ scrollTop: 0 }, 100);
}


// CLEAR FORMS
function clear_email() {
  jQuery("#limit-error").hide();
	jQuery("#above").val('');
	jQuery("#below").val('');
	jQuery("cptch_input_16").val('');
	jQuery("#ajax_loader").hide();
	jQuery("#create_alert_button").show();
	jQuery("#above_acc").val('');
	jQuery("#below_acc").val('');
	jQuery(".feedback").empty();
	jQuery("#ajax_loader_acc").hide();
	jQuery("#create_alert_button_acc").show();
	if (typeof user_email !== 'undefined') {
		jQuery("#email_acc").val(user_email);
	}
	toTop();
}

function clear_email_per() {
  jQuery("#limit-error-per").hide();
	jQuery("#plus_percent").val('');
	jQuery("#minus_percent").val('');
	jQuery("cptch_input_16").val('');
	jQuery("#ajax_loader_percent").hide();
	jQuery("#create_alert_button_percent").show();
	jQuery("#plus_percent_acc").val('');
	jQuery("#minus_percent_acc").val('');
	jQuery(".feedback").empty();
	jQuery("#ajax_loader_percent_acc").hide();
	jQuery("#create_alert_button_percent_acc").show();
	if (typeof user_email !== 'undefined') {
		jQuery("#email_percent_acc").val(user_email);
	}
	toTop();
}

function clear_sms() {
	jQuery("#above_sms").val('');
	jQuery("#below_sms").val('');
	jQuery("#email_sms").val('');
	jQuery(".feedback").empty();
	jQuery("#ajax_loader_sms").hide();
	jQuery("#create_alert_button_sms").show();
	if (typeof user_phone_nr !== 'undefined') {
		jQuery("#phone").val(user_phone_nr);
	}
	toTop();
}

function clear_sms_per() {
	jQuery("#plus_sms_per").val('');
	jQuery("#minus_sms_per").val('');
	jQuery(".feedback").empty();
	jQuery("#ajax_loader_sms_per").hide();
	jQuery("#create_alert_button_sms_per").show();
	if (typeof user_phone_nr !== 'undefined') {
		jQuery("#phone_per").val(user_phone_nr);
	}
	toTop();
}


// SWITCHES CONTROL //

function doNavigation(switchLocation) {
  jQuery('.current-view').hide();
  jQuery('#'+switchLocation).show();
  router.navigate(switchLocation);
  
}

jQuery("#smsSwitch").click(function() {
	switch (switchLocation) {
		case "email":
			switchLocation = "sms";
			clear_sms();
			break;
		case "email-per":
			switchLocation = "sms-per";
			clear_sms_per();
			break;
		case "sms":
			switchLocation = "sms";
			clear_sms();
			break;
		case "sms-per":
			switchLocation = "sms-per";
			clear_sms_per();
			break;
  }
  doNavigation(switchLocation);
});


jQuery("#emailSwitch").click(function() {
	switch (switchLocation) {
		case "email":
			switchLocation = "email";
			clear_email();
			break;
		case "email-per":
			switchLocation = "email-per";
			clear_email_per();
			break;
		case "sms":
			switchLocation = "email";
			clear_email();
			break;
		case "sms-per":
			switchLocation = "email-per";
			clear_email_per();
			break;
  }
  doNavigation(switchLocation);
});


jQuery("#curSwitch").click(function() {
	switch (switchLocation) {
		case "email":
			switchLocation = "email";
			clear_email();
			break;
		case "email-per":
			switchLocation = "email";
			jQuery("#switch-cur").show();
			jQuery("#switch-per").hide();
			clear_email();
			break;
		case "sms":
			switchLocation = "sms";
			clear_sms();
			break;
		case "sms-per":
			switchLocation = "sms";
			jQuery("#switch-cur").show();
			jQuery("#switch-per").hide();
			clear_sms();
			break;
  }
  doNavigation(switchLocation);
});


jQuery("#perSwitch").click(function() {
	switch (switchLocation) {
		case "email":
			switchLocation = "email-per";
			jQuery("#switch-cur-init").hide();
			jQuery("#switch-cur").hide();
			jQuery("#switch-per").show();
			clear_email_per();
			break;
		case "email-per":
			switchLocation = "email-per";
			clear_email_per();
			break;
		case "sms":
			switchLocation = "sms-per";
			jQuery("#switch-cur-init").hide();
			jQuery("#switch-cur").hide();
			jQuery("#switch-per").show();
			clear_sms_per();
			break;
		case "sms-per":
			switchLocation = "sms-per";
			clear_sms_per();
			break;
  }
  doNavigation(switchLocation);
});


// "NEW ALERT" text links
jQuery("#newalertemail").click(function() {
  clear_email();
  jQuery(".current-view").hide();
  jQuery("#email").show();
});

jQuery("#newalertaccemail").click(function() {
  clear_email();
  jQuery(".current-view").hide();
  jQuery("#email").show();
});

jQuery("#newalertemailpercent").click(function() {
  clear_email_per();
  jQuery(".current-view").hide();
  jQuery("#email-per").show();
});

jQuery("#newalertemailpercentacc").click(function() {
  clear_email_per();
  jQuery(".current-view").hide();
  jQuery("#email-per").show();
});

jQuery("#newalertaccsms").click(function() {
  clear_sms();
  jQuery(".current-view").hide();
  jQuery("#sms").show();
});

jQuery("#newalertsmsper").click(function() {
  clear_sms_per();
  jQuery(".current-view").hide();
  jQuery("#sms-per").show();
});


//
//
//
// END OF NAVIGATION!!!
//
//
//


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
		jQuery("#container3").hide();
		jQuery("#container4").attr('style','display: table; background: white;');
		jQuery("#manage_alerts_loader").hide();
	}
	else {	
		jQuery("#feedback3").html(data.substring(0, data.length - 1));
	}
	}
	}); 
	return false;
}



//
// ACC: Ajax to manage alerts
//

function ajaxSubmit_acc() {
  var manage_alerts = "unique_id="+unique_id+"&action=manage_alerts_acc";
  alerts = {};
  alerts_string = "";

	jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: manage_alerts+security_url,
    success: function(data) {
      if (data == 'zero_alerts'){
        jQuery("#manage_alerts_acc_loader").hide();
      	jQuery("#manage_alerts_acc_feedback").html('<div style="height:10px;"></div>You have no alerts to manage<div style="height:20px;"></div><div onclick="logsShow()" class="logs" id="logs-show-hide"><span  class="logs-title">Logs</span><div class="logs-content" id="logs-content"></div></div><div style="height:10px;"></div>');
        
        
        // You have no alerts to manage<div style='border-bottom: 1px solid #a5a5a5;margin-top:35px;margin-bottom:10px;'></div><div class='logs-content portfolio-alerts-about' id='logs-content' style='padding-top:10px;padding-bottom:5px;font-size:11px;line-height:150%;'><span onclick='logsShow()' class='blacklink'>Logs</span></div>");
        return;
      }
      alertsDb = JSON.parse(data);
      jQuery.each(alertsDb, function(key, value) {
        if (alertsDb[key].length != 0) {
          var alert_type = key; // e.g. alerts_email
          jQuery.each(alertsDb[alert_type], function(key, value) {
            if (!alerts[value.coin]) { alerts[value.coin] = {} }
            value.alert_type = alert_type;
            alerts[value.coin][value.ID] = value;
          });
        }
        alertsdb = "";
      });

      if (alerts) {
        alerts_meta = {};
        jQuery.each(alerts, function(key, objOfObj) {
          // console.log(key, objOfObj);

          function getCoinId(key) {
            for(var i = 0; i < jqueryarray.length; i++) {
                var coin = jqueryarray[i];
                if (coin['name'] == key) {
                  var coin_id = coin['id'];
                  alerts_meta[coin_id] = key;
                  return;
                }
            }
          }
          getCoinId(key);

        });

        alerts_rank = [];


        jQuery.each(alerts_meta, function (coin_id, coin_name) {
              
          rank = jqueryarray.findIndex(function(x) { x.id == coin_id });

          alerts_rank.push({ 'rank': rank, 'name': coin_name, 'id': coin_id });
          
        });

        alerts_rank.sort(function(a, b){
          return a.rank-b.rank
        })
        
        jQuery.each(alerts_rank, function(rank, coin) {
          id = coin.id;
          coin_name = coin.name;

          function getCoinSlug(coin_name) {
            for(var i = 0; i < jqueryarray.length; i++) {
                var coin = jqueryarray[i];
                if (coin['name'] == coin_name) {
                  coin_slug = coin['slug']; // current coin in loop slug
                }
            }
          }
          getCoinSlug(coin_name);


          // Need to define, or shows an error
          sms_alerts = 'sms_alerts';
          email_alerts = 'email_alerts';
          sms_alerts_per = 'sms_alerts_per';
          email_alerts_per = 'email_alerts_per';


          // Build alerts view in the particular order
          jQuery.each(alerts[coin_name], function(id, alert) {
            if (alert.alert_type == 'sms_alerts') {
              buildAlerts(alert);
            }
          });
          jQuery.each(alerts[coin_name], function(id, alert) {
            if (alert.alert_type == 'email_alerts') {
              buildAlerts(alert);
            }
          });
          jQuery.each(alerts[coin_name], function(id, alert) {
            if (alert.alert_type == 'sms_alerts_per') {
              buildAlerts(alert);
            }
          });
          jQuery.each(alerts[coin_name], function(id, alert) {
            if (alert.alert_type == 'email_alerts_per') {
              buildAlerts(alert);
            }
          });


          // Alerts array builder
          function buildAlerts(alert) {

            if (alert.alert_type == 'sms_alerts' || alert.alert_type == 'sms_alerts_per') {
              ma_alert_destination = alert.phone;
            }
            else {
              ma_alert_destination = alert.email;
            }

            alert_type = alert.alert_type;
            
            jQuery.each(alerts_meta, function (id, name) {
              if (name == coin_name) {
                // console.log(id);
                coin_id = id;
              }
            });

            alerts_string += "<div class='alert-box' id='alert-div-"+alert.ID+"'><a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/"+coin_slug+"'><img width='18' src='"+homePath+"/img/coins/32x32/"
            +coin_id+".png'></a><br>"+coin_name+" ("+alert.symbol+")"
            +"<br>"+ma_alert_destination+"<br><div style='margin-top:8px;line-height:18px;'>";
            
            if (typeof(alert.above) != 'undefined') {
              // currency alerts
              if (alert.above.length > 0) {
                if (alert.above_sent == 1) {
                  alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"above_sent\")' class='line-through line-through-link'>Above: <b>"+alert.above+"</b> "+alert.above_currency+"</span><br>";
                }
                else {
                  alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"above_sent\")' class='line-through-link'>Above: <b>"+alert.above+"</b> "+alert.above_currency+"</span><br>";
                }
              }
            }
            else {
              // percentage alerts

              if (alert.plus_percent && alert.plus_sent && alert.plus_change == 'from_now') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through line-through-link'><b>+"+alert.plus_percent+"%</b> compared to "+alert.plus_compared+"</span>";
              }
              else if (alert.plus_percent && !alert.plus_sent && alert.plus_change == 'from_now') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through-link'><b>+"+alert.plus_percent +"%</b> compared to "+ alert.plus_compared+"</span>";
              }
              if (alert.plus_percent && alert.plus_sent && alert.plus_change == '1h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through line-through-link'><b>+"+alert.plus_percent+"%</b> in 1h. period</span>";
              }
              else if (alert.plus_percent && !alert.plus_sent && alert.plus_change == '1h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through-link'><b>+"+alert.plus_percent +"%</b> in 1h. period</span>";
              }
              if (alert.plus_percent && alert.plus_sent && alert.plus_change == '24h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through line-through-link'><b>+"+alert.plus_percent+"%</b> in 24h. period</span>";
              }
              else if (alert.plus_percent && !alert.plus_sent && alert.plus_change == '24h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through-link'><b>+"+alert.plus_percent+"%</b> in 24h. period</span>";
              }
              if (alert.plus_percent && alert.minus_percent) {
                alerts_string += "<br>";
              }
            }

            if (typeof(alert.below) != 'undefined') {
              // currency alerts
              if (alert.below.length > 0) {
                if (alert.below_sent == '') {
                  alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"below_sent\")' class='line-through-link'>Below: <b>"+alert.below+"</b> "+alert.below_currency+"</span><br>";
                }
                else {
                  alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"below_sent\")' class='line-through line-through-link'>Below: <b>"+alert.below+"</b> "+alert.below_currency+"</span><br>";
                }
              }
            }
            else {
              // percentage alerts

              if (alert.minus_percent && alert.minus_sent && alert.minus_change == 'from_now') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='line-through line-through-link'><b>-"+alert.minus_percent+"%</b> compared to "+alert.minus_compared+"</span>";
              }
              else if (alert.minus_percent && !alert.minus_sent && alert.minus_change == 'from_now') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='line-through-link'><b>-"+alert.minus_percent+"%</b> compared to "+alert.minus_compared+"</span>";
              }
              if (alert.minus_percent && alert.minus_sent && alert.minus_change == '1h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='line-through line-through-link'><b>-"+alert.minus_percent+"%</b> in 1h. period</span>";
              }
              else if (alert.minus_percent && !alert.minus_sent && alert.minus_change == '1h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='line-through-link'><b>-"+alert.minus_percent+"%</b> in 1h. period</span>";
              }
              if (alert.minus_percent && alert.minus_sent && alert.minus_change == '24h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='line-through line-through-link'><b>-"+alert.minus_percent+"%</b> in 24h. period</span>";
              }
              else if (alert.minus_percent && !alert.minus_sent && alert.minus_change == '24h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='line-through-link'><b>-"+alert.minus_percent+"%</b> in 24h. period</span>";
              }
            }
              
            alerts_string += "</div><div title='Delete alert' class='alert-delete' id="+alert.ID+" onclick='deleteAnyAlert("+alert.ID+", "+alert_type+")'><svg width='9' height='9'><use xlink:href='#svg-alert-delete'></use></svg></div></div></div>";   

          }

        });
        // console.log(alerts_meta);
        alerts_string += "<div style='font-size:11px;padding:5px 0 10px 0;'>Tip: Click an alert to re-enable it</div>";

        alerts_string += '<div onclick="logsShow()" class="logs" id="logs-show-hide"><span  class="logs-title">Logs</span><div class="logs-content" id="logs-content"></div></div><div style="height:10px;"></div>';
        
        // <div style='border-bottom: 1px solid #a5a5a5;margin-top:25px;margin-bottom:15px;'></div><div class='logs-content' id='logs-content' style='padding-top:10px;padding-bottom:5px;font-size:11px;line-height:150%;'><span onclick='logsShow()' class='blacklink'>Logs</span></div>";

        jQuery("#manage_alerts_acc_loader").hide();
        jQuery("#manage_alerts_acc_feedback").html(alerts_string);
        

        jQuery(".line-through-link").click(function(event) {
          event.stopPropagation();
          event.stopImmediatePropagation();
          jQuery(this).toggleClass( "line-through" );
        });
      }
    }
  });

  // console.log(alerts);
	return false;
}



function deleteAnyAlert (id, type) {

  var divIdString = '#alert-div-'+id;

  jQuery(divIdString).remove();

  if (type == "email_alerts") {
    var delete_alert = 'action=delete_alert_acc_email&alert_id='+id+'&security='+security_url;
  }
  else if (type == "sms_alerts") {
    var delete_alert = 'action=delete_alert_acc_sms&alert_id='+id+'&security='+security_url;
  }
  else if (type == "email_alerts_per") {
    var delete_alert = 'action=delete_alert_percent_acc&alert_id='+id+'&security='+security_url;
  }
  else if (type == "sms_alerts_per") {
    var delete_alert = 'action=delete_alert_acc_sms_per&alert_id='+id+'&security='+security_url;
  }
  
  // Execute
  jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: delete_alert+security_url
  });

  if (jQuery('#manage_alerts_acc_feedback').html().length == 0) {
    jQuery("#manage_alerts_acc_loader").hide();
    jQuery("#manage_alerts_acc_feedback").html('<div style="height:22px;"></div>You have no alerts to manage<br><div style="height:22px;"></div>');
    return
  }

}


// Re-enable alert
function alertReenable (id, type, microType) {

  var alert_reenable = 'action=alert_reenable&alert_id='+id+'&type='+type+'&microType='+microType;
  
  // Execute
  jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: alert_reenable+security_url
  });
  
}



//
// LOGS
//


// for date
var options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };

// load logs
function logsShow() {

  jQuery("#logs-show-hide").toggleClass("logs-expanded");
  jQuery(".logs-title").toggleClass("portfolio-alerts-about-title-bold");
  jQuery(".logs-content").toggle();

  jQuery('#logs-content').html('Loading...');

  var data = 'action=get_logs';

  // Execute
  jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: data+security_url,
    success: function(data) {
      var dataArray = JSON.parse(data);
      var finalHtml = '';

      var logsEmail = dataArray[0].reverse();
      var logsSms = dataArray[1].reverse();
      var logsPortfolio = dataArray[2].reverse();

      // @todo: check/show only when logs available
      
      if (logsSms.length > 0) {
        var logsSmsHtml = '<br><b>SMS alerts</b><br><br>';

        for (var log in logsSms) {
          var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsSms[log].timestamp + ' UTC+3').toLocaleString("en-US", options)+'</span>';

          logsSmsHtml += clientTimestamp + '<br>' + logsSms[log].destination;

          if (logsSms[log].status == 'error' || logsSms[log].status == 'failed') {
            logsSmsHtml += '<br><b style="color:red;">Undelivered alert</b><br>Possible reasons:<br>- Wrong phone number or its <a href="https://support.twilio.com/hc/en-us/articles/223183008-Formatting-International-Phone-Numbers" target="_blank" class="blacklink">format</a><br>- Cell phone out of range<br>- Expired Coinwink subscription<br>- No SMS credits available<br>';
          }

          logsSmsHtml += '<br><br>';
        }

        logsSmsHtml += "<br>";
        
        finalHtml += logsSmsHtml;
      }

      if (logsEmail.length > 0) {
        var logsEmailHtml = '<br><b>Email alerts</b><br><br>';

        for (var log in logsEmail) {
          var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsEmail[log].timestamp + ' UTC+3').toLocaleString("en-US", options)+'</span>';

          logsEmailHtml += clientTimestamp + '<br>' + logsEmail[log].destination;

          if (logsEmail[log].status == 'error') {
            if (logsEmail[log].error == 'SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting') {
              logsEmailHtml += '<br><b style="color:red;">Undelivered alert</b><br>Automatically blocked. <a href="https://twitter.com/Coinwink/status/1174162988791713793" target="_blank" class="blacklink">Details</a><br>';
            }
            else {
              logsEmailHtml += '<br><b style="color:red;">Undelivered alert</b><br>Double-check your e-mail address.<br>';
            }
          }

          logsEmailHtml += '<br><br>';
        }

        logsEmailHtml += "<br>";

        finalHtml += logsEmailHtml;
      }

      if (logsPortfolio.length > 0) {
        var logsPortfolioHtml = '<br><b>Portfolio alerts</b><br><br>';

        for (var log in logsPortfolio) {
          var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsPortfolio[log].timestamp + ' UTC+3').toLocaleString("en-US", options)+'</span>';

          logsPortfolioHtml += clientTimestamp + '<br>' + logsPortfolio[log].destination + '<br><br>';
        }
        
        logsPortfolioHtml += "<br>";

        finalHtml += logsPortfolioHtml;
      }

      if (finalHtml.length > 0) {
        jQuery("#logs-content").html('<div style="margin-bottom:0px;">'+finalHtml+'</div>');
      }
      else {
        jQuery('#logs-content').html('<div style="padding:15px;padding-bottom:18px;"><span style="font-size:12px;">No logs yet</span><br></div>');
      }
      
    }
  });

}

//
// Ajax - New EMAIL alert
//
jQuery('#form_new_alert').submit(validate);

function validate() {
	var email_out = document.getElementById('email_out').value;
	var emailFilter = /^([a-zA-Z+0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	// console.log(email_out);
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

  if (jQuery("#above").val().length > 0 && isNaN(jQuery("#above").val())) {
    jQuery("#feedback").html('Price field should be a numeric value.');
		return false;
  }

  if (jQuery("#below").val().length > 0 && isNaN(jQuery("#below").val())) {
    jQuery("#feedback").html('Price field should be a numeric value.');
		return false;
  }

	var form_new_alert = jQuery(this).serialize();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert+security_url,
	beforeSend: function(){
    jQuery("#create_alert_button").hide();
    jQuery("#feedback").hide();
		jQuery("#ajax_loader").show().attr('style','margin-top: 33px');
	},
	success:function(data){

	if (data == 'Please enter a valid CAPTCHA value.0')	{
    jQuery("#feedback").show();
		jQuery("#feedback").html(data.substring(0, data.length - 1));
		jQuery("#create_alert_button").show();
		jQuery("#ajax_loader").hide();
	}
	else if (data == 'Limit error') {
		// jQuery("#feedback").html("You have reached the limit of 5 alerts.<br><a href='/account/' style='color:red!important;'><b>Create</b></a> a free account to increase the limit.");
    jQuery("#limit-error").show();
    jQuery("#create_alert_button").show();
		jQuery("#ajax_loader").hide();
	}
	else {
		jQuery("#email").hide();
		jQuery("#created_alert").attr('style','display: table;border-radius:3px;padding-top:25px');
	}
	}
	}); 
	return false;
}


//
// Ajax - New EMAIL alert with ACC
//
jQuery('#form_new_alert_acc').submit(validate_acc);

function validate_acc() {
  // clear feedback
  jQuery(".feedback").empty();
  jQuery("#limit-error").hide();

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
  
  if (jQuery("#above_acc").val().length > 0 && isNaN(jQuery("#above_acc").val())) {
    jQuery("#feedback_acc").html('Price field should be a numeric value.');
		return false;
  }

  if (jQuery("#below_acc").val().length > 0 && isNaN(jQuery("#below_acc").val())) {
    jQuery("#feedback_acc").html('Price field should be a numeric value.');
		return false;
  }

	// Update email for front-end input field
	user_email = email;

	var form_new_alert_acc = jQuery(this).serialize();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert_acc+security_url,
	beforeSend: function(){
		jQuery("#create_alert_button_acc").hide();
		jQuery("#ajax_loader_acc").show().attr('style','margin-top: 26px');
	},
	success:function(data){

		if (data == 'Limit error') {
			// jQuery("#feedback_acc").html("You have reached the limit of 10 alerts. To continue, delete some alerts first or <a href='subscription' data-navigo style='color:red!important;'>subscribe</a> to a Premium plan.");
      jQuery("#limit-error").show();
      jQuery("#create_alert_button_acc").show();
			jQuery("#ajax_loader_acc").hide();
		}
		else {
		jQuery(".current-view").hide();
		jQuery("#created_alert_acc_email").attr('style','display: table;border-radius:3px;padding-top:25px');
		}
	
	}
	}); 
	return false;
}


//
// Ajax - new EMAIL PERCENTAGE alert
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

  if (jQuery("#plus_percent").val().length > 0 && isNaN(jQuery("#plus_percent").val())) {
    jQuery("#feedback_percent").html('Price field should be a numeric value.');
		return false;
  }

  if (jQuery("#minus_percent").val().length > 0 && isNaN(jQuery("#minus_percent").val())) {
    jQuery("#feedback_percent").html('Price field should be a numeric value.');
		return false;
  }

	var form_new_alert_percent = jQuery(this).serialize();

	jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: form_new_alert_percent+security_url,
    beforeSend: function(){
      jQuery("#create_alert_button_percent").hide();
      jQuery("#feedback_percent").hide();
      jQuery("#ajax_loader_percent").show().attr('style','margin-top: 33px');
    },
    success:function(data){
  
      if (data == 'Please enter a valid CAPTCHA value.0')	{
        jQuery("#feedback_percent").show();
        jQuery("#feedback_percent").html(data.substring(0, data.length - 1));
        jQuery("#create_alert_button_percent").show();
        jQuery("#ajax_loader_percent").hide();
      }
      else if (data == 'Limit error') {
        // jQuery("#feedback_percent").html("You have reached the limit of 5 alerts. To continue, delete some alerts first or create a <a href='/account/?#signup' style='color:red!important;'>free account</a> to increase the limit.");
        jQuery("#limit-error-per-sans-acc").show();
        jQuery("#create_alert_button_percent").show();
        jQuery("#ajax_loader_percent").hide();
      }
      else {
        jQuery(".current-view").hide();
        jQuery("#created_alert_percent").attr('style','display: table;border-radius:3px;padding-top:25px');
      }

	}
	}); 
	return false;
}


//
// Ajax - New EMAIL PERCENTAGE alert with ACC
//
jQuery('#form_new_alert_percent_acc').submit(validate_percent_acc);

function validate_percent_acc() {

  // clear errors
  jQuery("#feedback_percent_acc").empty();
  jQuery("#limit-error-per").hide();

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

  if (jQuery("#plus_percent_acc").val().length > 0 && isNaN(jQuery("#plus_percent_acc").val())) {
    jQuery("#feedback_percent_acc").html('Price field should be a numeric value.');
		return false;
  }

  if (jQuery("#plus_percent_acc").val().length > 0 && isNaN(jQuery("#plus_percent_acc").val())) {
    jQuery("#feedback_percent_acc").html('Price field should be a numeric value.');
		return false;
  }

	// Update email for front-end input field
	user_email = email;

	var form_new_alert_percent_acc = jQuery(this).serialize();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert_percent_acc+security_url,
	beforeSend: function(){
		jQuery("#create_alert_button_percent_acc").hide();
		jQuery("#ajax_loader_percent_acc").show().attr('style','margin-top: 33px');
	},
	success:function(data){

		if (data == 'Limit error') {
      jQuery("#limit-error-per").show();
			// jQuery("#feedback_percent_acc").html("You have reached the limit of 10 alerts. To continue, delete some alerts first or <a href='subscription' data-navigo class='blacklink' style='color:red!important;'>subscribe</a> to a Premium plan.");
			jQuery("#create_alert_button_percent_acc").show();
			jQuery("#ajax_loader_percent_acc").hide();
		}
		else {
			jQuery(".current-view").hide();
			jQuery("#created_alert_percent_acc").attr('style','display: table;border-radius:3px;padding-top:25px');
		}

	}
	}); 
	return false;
}


//
// Ajax - New SMS alert
//
jQuery('#form_new_alert_sms').submit(validate_sms);

function validate_sms() {
	
	var above = jQuery("#above_sms").val().length;
	var below = jQuery("#below_sms").val().length;

	if (below + above < 1) {
		jQuery("#feedback_sms").html('Please enter at least 1 price value.');
		return false;
	}

  if (jQuery("#above_sms").val().length > 0 && isNaN(jQuery("#above_sms").val())) {
    jQuery("#feedback_sms").html('Price field should be a numeric value.');
		return false;
  }

  if (jQuery("#below_sms").val().length > 0 && isNaN(jQuery("#below_sms").val())) {
    jQuery("#feedback_sms").html('Price field should be a numeric value.');
		return false;
  }

	// Update phone nr for front-end input field
	var phone = document.getElementById('phone').value;
	user_phone_nr = phone;

	var form_new_alert_sms = jQuery(this).serialize();

	jQuery.ajax({
		type:"POST",
		url: ajax_url,
		data: form_new_alert_sms+security_url,
		beforeSend: function(){
			jQuery("#create_alert_button_sms").hide();
			jQuery("#ajax_loader_sms").show().attr('style','margin-top: 33px');
		},
		success:function(data){

			if (data != 'Limit error') {
				jQuery(".current-view").hide();
				jQuery("#created_alert_acc_sms").attr('style','display: table;border-radius:3px;padding-top:25px');
				jQuery("#ajax_loader_sms").hide();
				jQuery("#create_alert_button_sms").show();
			}
			else if (data == 'Limit error') {
        jQuery("#limit-error").show();
				// jQuery("#feedback_sms").html("You have reached the limit of 10 alerts. To continue, delete some alerts first or <a href='subscription' data-navigo class='blacklink' style='color:red!important;'>subscribe</a> to a Premium plan.");
				jQuery("#ajax_loader_sms").hide();
				jQuery("#create_alert_button_sms").show();
			}
			else
			{
				jQuery("#feedback_sms").html(data.substring(0, data.length - 1));
				jQuery("#create_alert_button").show();
				jQuery("#ajax_loader").hide();
			}
		
		}
	}); 
	return false;
}


//
// Ajax - New SMS PERCENTAGE alert
//
jQuery('#form_new_alert_sms_per').submit(validate_sms_per);

function validate_sms_per() {
	var above = jQuery("#plus_sms_per").val().length;
	var below = jQuery("#minus_sms_per").val().length;

	if (below + above < 1) {
		jQuery("#feedback_sms_per").html('Please enter at least 1 price value.');
		return false;
	}

  if (jQuery("#plus_sms_per").val().length > 0 && isNaN(jQuery("#plus_sms_per").val())) {
    jQuery("#feedback_sms_per").html('Price field should be a numeric value.');
		return false;
  }

  if (jQuery("#minus_sms_per").val().length > 0 && isNaN(jQuery("#minus_sms_per").val())) {
    jQuery("#feedback_sms_per").html('Price field should be a numeric value.');
		return false;
  }

	// Update phone nr for front-end input field
	var phone = document.getElementById('phone_sms_per').value;
	user_phone_nr = phone;

	var form_new_alert_sms_per = jQuery(this).serialize();

	jQuery.ajax({
		type:"POST",
		url: ajax_url,
		data: form_new_alert_sms_per+security_url,
		beforeSend: function(){
			jQuery("#create_alert_button_sms_per").hide();
			jQuery("#ajax_loader_sms_per").show().attr('style','margin-top: 33px');
		},
		success:function(data){
			if (data == 'Limit error') {
        jQuery("#limit-error").show();
				// jQuery("#feedback_sms_per").html("You have reached the limit of 10 alerts. To continue, delete some alerts first or <a href='subscription' data-navigo class='blacklink' style='color:red!important;'>subscribe</a> to a Premium plan.");
				jQuery("#ajax_loader_sms_per").hide();
				jQuery("#create_alert_button_sms_per").show();
			}
			else {
				jQuery(".current-view").hide();
				jQuery("#created_alert_sms_per").attr('style','display: table;border-radius:3px;padding-top:25px');
			}
		
		}
	}); 
	return false;
}


// Show SMS count for new SMS currency alert
jQuery(".sms_input").focus( function() {
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
});


// Show SMS count for new SMS percentage alert
jQuery(".sms_per_input").focus( function() {
	setInterval(function() {
		var val1 = jQuery('#plus_sms_per').val();
		var val2 = jQuery('#minus_sms_per').val();

		if ((val1 && !val2) || (val2 && !val1)) {
			jQuery('#reserved_message_per').html("1 SMS alert");
		}
		else if (val1 && val2) {
			jQuery('#reserved_message_per').html("2 SMS alerts");
		}
		else if (!val1 && !val2) {
			jQuery('#reserved_message_per').html("");
		}
	}, 100);
});

//
// Ajax to cancel subscription
//
jQuery('#cancel_subs_button').click(function() {
	if (confirm("Are you sure?") == true) {
		
		jQuery.ajax({
			type: "POST",
			url: ajax_url,
			data: 'action=cancel_subscription'+security_url,
			success: function(response){
				location.reload();
			}
		});

	}
});


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


// function for the below function
function changeCur(self) {
  var keys = Object.keys(curList);
  var nextIndex = keys.indexOf(curCurrent) +1;
  var nextItem = keys[nextIndex];
  curCurrent = nextItem;
  var el = (document.getElementById(self.id));
  if (typeof(curCurrent) != 'undefined') {
    var price = curList[curCurrent];
    if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

    el.innerHTML = price + empty + curCurrent;
    // el.innerHTML = formatCurrencyHome(price, curCurrent) + ' ' + curCurrent;
  }
  else {
    changeCur(self);
  }
}


//
// Show current price and add price to percent input
//

function showprice() {
	for(var i = 0; i < jqueryarray.length; i++) {
		var coin = jqueryarray[i];

		if (coin['id'] == jQuery("#id").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			var btc = coin['price_btc'];
      if (btc % 1 != 0) { btc = parseFloat(btc).toFixed(8); }
      var usd = coin['price_usd'];


      // new code
      var eur = coin['price_eur'];
      var gbp = coin['price_gbp'];
      var aud = coin['price_aud'];
      var cad = coin['price_cad'];
      
      var rbl = coin['price_rbl'];
      var mxn = coin['price_mxn'];
      var jpy = coin['price_jpy'];
      var sgd = coin['price_sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, RBL: rbl, MXN: mxn, JPY: jpy, SGD: sgd };
      curCurrent = 'USD';

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);


      jQuery("#pricediv").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"'><img style='vertical-align:middle;' width='12' src='"+homePath+"/img/coins/16x16/"+
      coin['id']+
      ".png'></a><span style='position:relative;top:1.5px;'> = " + btc + " BTC | " + eth + " ETH | <span onclick='changeCur(this)' id='cur-span' class='cur-span noselect'>"+ price + " " + curCurrent + "</span>");
      // end of new code


			jQuery("#coin").val(coin['name']);
			jQuery("#symbol").val(coin['symbol']);
		}

		if (coin['id'] == jQuery("#id_sms").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			var btc = coin['price_btc'];
			if (btc % 1 != 0) { btc = parseFloat(btc).toFixed(8); }
      var usd = coin['price_usd'];
      
      // new code
      var eur = coin['price_eur'];
      var gbp = coin['price_gbp'];
      var aud = coin['price_aud'];
      var cad = coin['price_cad'];
    
      var brl = coin['price_brl'];
      var mxn = coin['price_mxn'];
      var jpy = coin['price_jpy'];
      var sgd = coin['price_sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      curCurrent = 'USD';

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }
 
      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);


      jQuery("#pricediv_sms").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"'><img style='vertical-align:middle;' width='12' src='"+homePath+"/img/coins/16x16/"+
      coin['id']+
      ".png'></a><span style='position:relative;top:1.5px;'> = " + btc + " BTC | " + eth + " ETH | <span onclick='changeCur(this)' id='cur-span-sms' class='cur-span noselect'>"+ price + " " + curCurrent + "</span>");
      // end of new code
				
			jQuery("#coin_sms").val(coin['name']);
			jQuery("#symbol_sms").val(coin['symbol']);
		}

		if (coin['id'] == jQuery("#id_acc").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(6); }
			var btc = coin['price_btc'];
      if (btc % 1 != 0) { btc = parseFloat(btc).toFixed(8); }
      var usd = coin['price_usd'];

      // new code
      var eur = coin['price_eur'];
      var gbp = coin['price_gbp'];
      var aud = coin['price_aud'];
      var cad = coin['price_cad'];

      var brl = coin['price_brl'];
      var mxn = coin['price_mxn'];
      var jpy = coin['price_jpy'];
      var sgd = coin['price_sgd'];

      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      curCurrent = 'USD';

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);

      jQuery("#pricediv_acc").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"'><img style='vertical-align:middle;' width='12' src='"+homePath+"/img/coins/16x16/"+
      coin['id']+
      ".png'></a><span style='position:relative;top:1.5px;'> = " + btc + " BTC | " + eth + " ETH | <span onclick='changeCur(this)' id='cur-span-acc' class='cur-span noselect'>"+ price + " " + curCurrent + "</span>");
      // end of new code

			jQuery("#coin_acc").val(coin['name']);
			jQuery("#symbol_acc").val(coin['symbol']);
		}

		if (coin['id'] == jQuery("#id_percent").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			var btc = coin['price_btc'];
			if (btc % 1 != 0) { btc = parseFloat(btc).toFixed(8); }
      var usd = coin['price_usd'];


      // new code
      var eur = coin['price_eur'];
      var gbp = coin['price_gbp'];
      var aud = coin['price_aud'];
      var cad = coin['price_cad'];
      
      var brl = coin['price_brl'];
      var mxn = coin['price_mxn'];
      var jpy = coin['price_jpy'];
      var sgd = coin['price_sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      curCurrent = 'USD';

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);


      jQuery("#pricediv_percent").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"'><img style='vertical-align:middle;' width='12' src='"+homePath+"/img/coins/16x16/"+
      coin['id']+
      ".png'></a><span style='position:relative;top:1.5px;'> = " + btc + " BTC | " + eth + " ETH | <span onclick='changeCur(this)' id='cur-span-per' class='cur-span noselect'>"+ price + " " + curCurrent + "</span>");
      // end of new code

      

			jQuery("#price_set_btc").val(coin['price_btc']);
			jQuery("#price_set_usd").val(coin['price_usd']);
			jQuery("#price_set_eth").val(coin['price_eth']);

			jQuery("#coin_percent").val(coin['name']);
			jQuery("#symbol_percent").val(coin['symbol']);
		}

		if (coin['id'] == jQuery("#id_percent_acc").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			var btc = coin['price_btc'];
			if (btc % 1 != 0) { btc = parseFloat(btc).toFixed(8); }
			var usd = coin['price_usd'];
      
      // new code
      var eur = coin['price_eur'];
      var gbp = coin['price_gbp'];
      var aud = coin['price_aud'];
      var cad = coin['price_cad'];
      
      var brl = coin['price_brl'];
      var mxn = coin['price_mxn'];
      var jpy = coin['price_jpy'];
      var sgd = coin['price_sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      curCurrent = 'USD';

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);


      jQuery("#pricediv_percent_acc").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"'><img style='vertical-align:middle;' width='12' src='"+homePath+"/img/coins/16x16/"+
      coin['id']+
      ".png'></a><span style='position:relative;top:1.5px;'> = " + btc + " BTC | " + eth + " ETH | <span onclick='changeCur(this)' id='cur-span-per-acc' class='cur-span noselect'>"+ price + " " + curCurrent + "</span>");
      // end of new code
      
      jQuery("#price_set_btc_acc").val(coin['price_btc']);
			jQuery("#price_set_usd_acc").val(coin['price_usd']);
			jQuery("#price_set_eth_acc").val(coin['price_eth']);

			jQuery("#coin_percent_acc").val(coin['name']);
			jQuery("#symbol_percent_acc").val(coin['symbol']);
		}

		if (coin['id'] == jQuery("#id_sms_per").val()) {
			var eth = coin['price_eth'];
			if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
			var btc = coin['price_btc'];
			if (btc % 1 != 0) { btc = parseFloat(btc).toFixed(8); }
      var usd = coin['price_usd'];
      

      // new code
      var eur = coin['price_eur'];
      var gbp = coin['price_gbp'];
      var aud = coin['price_aud'];
      var cad = coin['price_cad'];
      
      var brl = coin['price_brl'];
      var mxn = coin['price_mxn'];
      var jpy = coin['price_jpy'];
      var sgd = coin['price_sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      curCurrent = 'USD';

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);

      jQuery("#pricediv_sms_per").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"'><img style='vertical-align:middle;' width='12' src='"+homePath+"/img/coins/16x16/"+
      coin['id']+
      ".png'></a><span style='position:relative;top:1.5px;'> = " + btc + " BTC | " + eth + " ETH | <span onclick='changeCur(this)' id='cur-span-sms-per' class='cur-span noselect'>"+ price + " " + curCurrent + "</span>");
      // end of new code
      
      
      jQuery("#price_set_btc_sms_per").val(coin['price_btc']);
			jQuery("#price_set_usd_sms_per").val(coin['price_usd']);
			jQuery("#price_set_eth_sms_per").val(coin['price_eth']);

			jQuery("#coin_sms_per").val(coin['name']);
			jQuery("#symbol_sms_per").val(coin['symbol']);
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

jQuery("#id_sms_per").change(function () {
	showprice();
});


//
// Change currency
//
function toggleBTC() {
	if (jQuery("#id").val() == "1") {
		jQuery("#above_currency").val("USD");
		jQuery("#below_currency").val("USD");
	}

	if (jQuery("#id_sms").val() == "1") {
		jQuery("#above_currency_sms").val("USD");
		jQuery("#below_currency_sms").val("USD");
	}

	if (jQuery("#id_acc").val() == "1") {
		jQuery("#above_currency_acc").val("USD");
		jQuery("#below_currency_acc").val("USD");
  }
}

jQuery(document).ready(function () {
	toggleBTC();
});

jQuery("#id_percent").change(function () {
	toggleBTC();
});

jQuery("#id_percent_acc").change(function () {
	toggleBTC();
});

jQuery("#id_sms_per").change(function () {
	toggleBTC();
});


//
// Coin symbol to pass for the backend
//

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
		jQuery("#plus_compared").val("USD");
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
		jQuery("#minus_compared").val("USD");
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
		jQuery("#plus_compared_acc").val("USD");
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
		jQuery("#minus_compared_acc").val("USD");
	}
});

jQuery('#plus_change_sms_per').change(function(){
	plusChange = jQuery("#plus_change_sms_per option:selected").text();
	if ((plusChange == "in 1h. period") || (plusChange == "in 24h. period")) {
		jQuery("#div_plus_compared_sms_per").hide();
		jQuery("#plus_usd_sms_per").show();
		jQuery("#plus_compared_sms_per").val("USD");
	}
	if (plusChange == "from now") {
		jQuery("#div_plus_compared_sms_per").show();
		jQuery("#plus_usd_sms_per").hide();
		jQuery("#plus_compared_sms_per").val("USD");
	}
});

jQuery('#minus_change_sms_per').change(function(){
	minusChange = jQuery("#minus_change_sms_per option:selected").text();
	if ((minusChange == "in 1h. period") || (minusChange == "in 24h. period")) {
		jQuery("#div_minus_compared_sms_per").hide();
		jQuery("#minus_usd_sms_per").show();
		jQuery("#minus_compared_sms_per").val("USD");
	}
	if (minusChange == "from now") {
		jQuery("#div_minus_compared_sms_per").show();
		jQuery("#minus_usd_sms_per").hide();
		jQuery("#minus_compared_sms_per").val("USD");
	}
});



// 190522

jQuery('#portfolio-alert-type').change(function( event ){
  var alertType = jQuery("#portfolio-alert-type").val();
	if (alertType == "email") {
    jQuery("#portfolio-alert-destination").val('');;
    jQuery("#portfolio-alert-destination").attr("placeholder", "E-mail address");
  }
  else if (alertType == "sms") {
    jQuery("#portfolio-alert-destination").val('');
    jQuery("#portfolio-alert-destination").attr("placeholder", "Phone number");
  }

  // jQuery("#portfolio-user-feedback").html("All portfolio alerts were reset");

  // portfolioAlertsSubmit();
  portfolioAlertsReset();
});


jQuery("#portfolio-alerts-about-show-hide").click(function() {
  jQuery("#portfolio-alerts-about-show-hide").toggleClass("portfolio-alerts-about-expanded");
  jQuery(".portfolio-alerts-about-title").toggleClass("portfolio-alerts-about-title-bold");
  jQuery(".portfolio-alerts-about-content").toggle();
});

jQuery("#logs-show-hide").click(function() {
  jQuery("#logs-show-hide").toggleClass("logs-expanded");
  jQuery(".logs-title").toggleClass("portfolio-alerts-about-title-bold");
  jQuery(".logs-content").toggle();
});

function portfolioAlertsReset() {
  jQuery('#portfolio-alert-1-checkbox').prop('checked', false);
  jQuery('#portfolio-alert-2-checkbox').prop('checked', false);
  jQuery('#portfolio-alert-3-checkbox').prop('checked', false);
  jQuery('#portfolio-alert-4-checkbox').prop('checked', false);
  jQuery('#portfolio-alert-1-value').val(10);
  jQuery('#portfolio-alert-2-value').val(10);
  jQuery('#portfolio-alert-3-value').val(10);
  jQuery('#portfolio-alert-4-value').val(10);

  jQuery("#portfolio-user-feedback").html('Alerts were reset');

  var data = 'action=portfolio_alerts_clear';

  // Execute
  jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: data+security_url,
    success:function(data){
      console.log(data);
    }
  });
}


function portfolioAlertsSubmit () {
  var alertType = jQuery("#portfolio-alert-type").val();
  var alertDestination = jQuery("#portfolio-alert-destination").val();

  if (alertDestination == ""){
    if (alertType == "email") { 
      portfolioAlertsReset();
      jQuery("#portfolio-user-feedback").html("<span style='padding-top:10px;padding-bottom:10px;color:red;'>Missing e-mail address</span>");
      return;
    }
    else if (alertType == "sms") { 
      portfolioAlertsReset();
      jQuery("#portfolio-user-feedback").html("<span style='padding-top:10px;padding-bottom:10px;color:red;'>Missing phone number</span>");
      return;
    }
  }

  
  var portfolioAlert1 = jQuery('#portfolio-alert-1-value').val();
  var portfolioAlert2 = jQuery('#portfolio-alert-2-value').val();
  var portfolioAlert3 = jQuery('#portfolio-alert-3-value').val();
  var portfolioAlert4 = jQuery('#portfolio-alert-4-value').val();

  // console.log(portfolioAlert1, portfolioAlert2, portfolioAlert3, portfolioAlert4);

  if (isNaN(portfolioAlert1) || isNaN(portfolioAlert2) || isNaN(portfolioAlert3) || isNaN(portfolioAlert4)) {
    jQuery("#portfolio-user-feedback").html("<span style='padding-top:10px;padding-bottom:10px;color:red;'>Entered value must be a number</span>");
    return;
  }

  if (portfolioAlert1 < 10 || portfolioAlert1 > 1000) {
    jQuery("#portfolio-user-feedback").html("<span style='padding-top:10px;padding-bottom:10px;color:red;'>Value range between 10% and 1000%</span>");
    jQuery('#portfolio-alert-1-value').val("10");
    return;
  }

  if (portfolioAlert2 < 10 || portfolioAlert2 > 1000) {
    jQuery("#portfolio-user-feedback").html("<span style='padding-top:10px;padding-bottom:10px;color:red;'>Value range between 10% and 1000%</span>");
    jQuery('#portfolio-alert-2-value').val("10");
    return;
  }
  
  if (portfolioAlert3 < 10 || portfolioAlert3 > 1000) {
    jQuery("#portfolio-user-feedback").html("<span style='padding-top:10px;padding-bottom:10px;color:red;'>Value range between 10% and 1000%</span>");
    jQuery('#portfolio-alert-3-value').val("10");
    return;
  }
  
  if (portfolioAlert4 < 10 || portfolioAlert4 > 1000) {
    jQuery("#portfolio-user-feedback").html("<span style='padding-top:10px;padding-bottom:10px;color:red;'>Value range between 10% and 1000%</span>");
    jQuery('#portfolio-alert-4-value').val("10");
    return;
  }
  
  var alertsLabel = "";

  if (jQuery('#portfolio-alerts-form').serializeArray().length - 6 == 1) { 
    alertsLabel = "alert" 
  } 
  else { 
    alertsLabel = "alerts"
  }
  
  jQuery("#portfolio-user-feedback").html(jQuery('#portfolio-alerts-form').serializeArray().length - 6 + " active portfolio "+alertsLabel);

  var data = 'action=portfolio_alerts_create&' + jQuery('#portfolio-alerts-form').serialize();

  // Execute
  jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: data+security_url,
    success:function(data){
      console.log(data);
    }
  });
}


jQuery('#portfolio-alerts-form').change(function() {
  portfolioAlertsSubmit();
});


jQuery('#portfolio-alerts-hide').click(function() {
    jQuery('#portfolio-alerts-content').hide();
    jQuery('#portfolio-alerts-hide').hide();
    jQuery('#portfolio-alerts-show').show();
    
    var data = 'action=portfolio_alerts_expanded&expanded=0';
    
    // Execute
    jQuery.ajax({
      type:"POST",
      url: ajax_url,
      data: data+security_url
    });
});


jQuery('#portfolio-alerts-show').click(function() {
  jQuery('#portfolio-alerts-content').show();
  jQuery('#portfolio-alerts-hide').show();
  jQuery('#portfolio-alerts-show').hide();

  var data = 'action=portfolio_alerts_expanded&expanded=1';
  
  // Execute
  jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: data+security_url
  });
});



function openPortfolioAlerts() {
  
  // console.log('Loading...');

  var data = 'action=portfolio_get_alerts';
  
  // Execute
  jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: data+security_url,
    success:function(data){

      var response = JSON.parse(data);

      if (!response) {
        jQuery('#portfolio-alerts-content').show();
        jQuery('#portfolio-alerts-hide').show();
        jQuery('#portfolio-alerts-show').hide();
        jQuery(".container-visible").show();
        jQuery("#portfolio-user-feedback").html("0 active portfolio alerts");
        jQuery('#portfolio-alerts-content').hide();
        jQuery('#portfolio-alerts-hide').hide();
        jQuery('#portfolio-alerts-show').show();
        return;
      }

      if (response.expanded == 1) {
        jQuery('#portfolio-alerts-content').show();
        jQuery('#portfolio-alerts-hide').show();
        jQuery('#portfolio-alerts-show').hide();
      }
      else {
        jQuery('#portfolio-alerts-content').hide();
        jQuery('#portfolio-alerts-hide').hide();
        jQuery('#portfolio-alerts-show').show();
      }

      var alertsCount = 0;

      if (response.on_1h_plus == "on") { alertsCount++; jQuery("#portfolio-alert-1-checkbox").prop( "checked", true ); jQuery("#portfolio-alert-1-value").val(response.change_1h_plus) }
      if (response.on_1h_minus == "on") { alertsCount++; jQuery("#portfolio-alert-2-checkbox").prop( "checked", true ); jQuery("#portfolio-alert-2-value").val(response.change_1h_minus) }
      if (response.on_24h_plus == "on") { alertsCount++; jQuery("#portfolio-alert-3-checkbox").prop( "checked", true ); jQuery("#portfolio-alert-3-value").val(response.change_24h_plus) }
      if (response.on_24h_minus == "on") { alertsCount++; jQuery("#portfolio-alert-4-checkbox").prop( "checked", true ); jQuery("#portfolio-alert-4-value").val(response.change_24h_minus) }

      if (typeof(response.type) != 'undefined') {
        jQuery('#portfolio-alert-type').val(response.type);
      }
      
      if (typeof(response.destination) != 'undefined') {
        jQuery('#portfolio-alert-destination').val(response.destination);
      }


      // console.log(response.plus_1h, response.plus_24h, response.minus_1h, response.minus_24h);

      // console.log(alertsCount);
      var alertsLabel = "";
      if (alertsCount == 1) { alertsLabel = "alert" } else { alertsLabel = "alerts"}
      jQuery("#portfolio-user-feedback").html(alertsCount + " active portfolio "+alertsLabel);
      
    }
  });
}


// Activate Promotional Premium
function promoActivate() {

  var data = 'action=promo_activate';
  
  // Execute
  jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: data+security_url,
    success: function(data) {
      if (data == 'success') {
        location.replace("https://coinwink.com/account");
      }
    }
  });
}


// Stripe checkout

function stripeCheckout() {
  alert("Not available in this version.")
}