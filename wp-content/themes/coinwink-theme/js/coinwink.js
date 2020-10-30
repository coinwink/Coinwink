// default location
var switchLocation = "email";

// navigation
var router = new Navigo(homePath);

jQuery('.link-portfolio').click(function(e) {
  e.preventDefault();
  router._lastRouteResolved = null;
  router.navigate('/portfolio');
})

jQuery('.link-watchlist').click(function(e) {
  e.preventDefault();
  router._lastRouteResolved = null;
  router.navigate('/watchlist');
})

jQuery('.link-manage-alerts').click(function(e) {
  e.preventDefault();
  router._lastRouteResolved = null;
  router.navigate('/manage-alerts');
})

jQuery('.link-about').click(function(e) {
  e.preventDefault();
  router.navigate('/about');
})

jQuery('.link-pricing').click(function(e) {
  e.preventDefault();
  router.navigate('/pricing');
})
jQuery('.link-terms').click(function(e) {
  e.preventDefault();
  router.navigate('/terms');
})
jQuery('.link-privacy').click(function(e) {
  e.preventDefault();
  router.navigate('/privacy');
})
jQuery('.link-press').click(function(e) {
  e.preventDefault();
  router.navigate('/press');
})
jQuery('.link-contacts').click(function(e) {
  e.preventDefault();
  router.navigate('/contacts');
})
jQuery('.link-subscription').click(function(e) {
  e.preventDefault();
  router.navigate('/subscription');
})


jQuery('.link-email').click(function(e) {
  e.preventDefault();
  router._lastRouteResolved = null;
  jQuery(".new-crypto-alert-link").hide();
  router.navigate('/email');
  // clear_email();
  // jQuery(".current-view").hide();
  // jQuery("#email").show();
})
jQuery('.link-email-per').click(function(e) {
  e.preventDefault();
  router._lastRouteResolved = null;
  jQuery(".new-crypto-alert-link").hide();
  router.navigate('/email-per');
  // clear_email_per();
  // jQuery(".current-view").hide();
  // jQuery("#email").show();
})
jQuery('.link-sms').click(function(e) {
  e.preventDefault();
  router._lastRouteResolved = null;
  jQuery(".new-crypto-alert-link").hide();
  router.navigate('/sms');
  // clear_sms();
  // jQuery(".current-view").hide();
  // jQuery("#email").show();
})
jQuery('.link-sms-per').click(function(e) {
  e.preventDefault();
  router._lastRouteResolved = null;
  jQuery(".new-crypto-alert-link").hide();
  router.navigate('/sms-per');
  // clear_sms_per();
  // jQuery(".current-view").hide();
  // jQuery("#email").show();
})


router
  .on({

    '/watchlist': function () {
      // selectedCurrency = 'USD';
      watchlist_type = 'price';

      jQuery(".containerloader").show();

      document.title = "Coinwink - Crypto Watchlist App for 2500+ Cryptocurrencies";
      jQuery(".current-view").hide();
      jQuery('#watchlist').show();
      // jQuery('#portfolio-alerts-content').hide();
      jQuery(".feedback").empty();
      toTop();  
      if (isLoggedIn) {
        openWatchlist();
      }
      // openPortfolioAlerts();

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },
    '/portfolio': function () {
      // selectedCurrency = 'USD';
      jQuery(".containerloader").show();

      document.title = "Coinwink - Crypto Portfolio Tracker and Manager App";
      jQuery(".current-view").hide();
      jQuery('#portfolio').show();
      jQuery('#portfolio-alerts-content').hide();
      jQuery(".feedback").empty();
      toTop(); 
      if (isLoggedIn) {
        openPortfolio();
        openPortfolioAlerts();
      } 

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },
    '/manage-alerts': function () {
      logsOpen = false;
      jQuery(".containerloader").show();

      document.title = "Coinwink - Manage Alerts";
      jQuery(".current-view").hide();
      jQuery("#manage-alerts").show();
      toTop();
      jQuery("#manage_alerts_acc_loader").show();
      jQuery("#manage_alerts_acc_feedback").html("");
      if (isLoggedIn) {
        ajaxSubmit_acc();
      }

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/email': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Email Price Alert for Bitcoin, Free Crypto Alerts";
      clear_email();
      jQuery(".current-view").hide();
      jQuery("#email").show();

      switchLocation = "email";

      jQuery(".containerloader").hide();

      jQuery('.coin_page').remove();
    },

    '/email-per': function () {
      jQuery(".containerloader").show();

      document.title = "Coinwink - Email Percentage Alert for Bitcoin, Free Cryptocurrency Alerts";
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

      document.title = "Coinwink - SMS Price Alerts for Bitcoin, Ethereum, Cryptocurrency";
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

      document.title = "Coinwink - SMS Percentage Alerts for Bitcoin, 2500 Crypto Coins and Tokens";
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

      document.title = "Coinwink - Press Kit";
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
      
      // console.log('custom coin page');

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

      if (data == 'zero_alerts') {
        jQuery("#manage_alerts_acc_loader").hide();
      	jQuery("#manage_alerts_acc_feedback").html('<div style="height:10px;"></div><br><span style="font-size:12.5px;">You have no alerts to manage.<br><br>Create some alerts first!</span><div style="height:20px;"></div><div onclick="logsShow()" style="position:relative;" class="logs logs-hover" id="logs-show-hide"><div id="logs-close-btn" onclick="logsHide()" style="position:absolute;left:3px;top:3px;height:10px;width:10px;padding:5px;cursor:pointer;" title="Close"><svg width="9" height="9"><use xlink:href="#svg-alert-delete"></use></svg></div><span  class="logs-title">Logs</span><div class="logs-content" id="logs-content"></div></div><div style="height:10px;"></div>');
        return;
      }

      alertsDb = JSON.parse(data);
      // console.log(alertsDb);

      var i = 0;
      jQuery.each(alertsDb, function(key, value) {
        if (alertsDb[key].length != 0) {
          var alert_type = key; // e.g. alerts_email
          jQuery.each(alertsDb[alert_type], function(key, value) {
            // console.log(value.coin)
            if (!alerts[value.coin]) { alerts[value.coin] = {} }
            value.alert_type = alert_type;
            alerts[value.coin][i] = value;
            i++;
          });
        }
      });

      // console.log(alerts);

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
                else {
                  if (i == (jqueryarray.length - 1)) {
                    // console.log('found');
                    // console.log(key, objOfObj[0]['alert_type'], objOfObj[0]['ID']);
                    // console.log(objOfObj[3]);

                    for (var key in objOfObj) {
                      if (!objOfObj.hasOwnProperty(key)) continue;
                  
                      var obj = objOfObj[key];
                      for (var prop in obj) {
                          if (!obj.hasOwnProperty(prop)) continue;

                          // console.log(obj['ID'], obj['alert_type']);

                          // auto delete alert which is phantom because either coin's slug changed or the coin was removed from cmc
                          deleteAnyAlert(obj['ID'], obj['alert_type']);
                      }
                    }


                  }
                }
            }
          }
          getCoinId(key);

        });

        alerts_rank = [];


        jQuery.each(alerts_meta, function (coin_id, coin_name) {
          // console.log(coin_id, coin_name)
          // var coin_id = parseInt(coin_id);
          var rank = jqueryarray.findIndex(function(x) { return x.id == coin_id });
          alerts_rank.push({ 'rank': rank, 'name': coin_name, 'id': coin_id });
        });

        // console.log(alerts_rank)

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

          // console.log(alerts);

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
            // console.log(ma_alert_destination,alert_type)
            jQuery.each(alerts_meta, function (id, name) {
              if (name == coin_name) {
                // console.log(id);
                coin_id = id;
              }
            });

            alerts_string += "<div class='alert-box' id='alert-div-"+alert.ID+"'><a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/"+coin_slug+"/'><img width='18' height='18' class='noselect' src='"+homePath+"img/coins/32x32/"
            +coin_id+".png'></a><br>"+coin_name+" ("+alert.symbol+")"
            +"<br>"+ma_alert_destination+"<br><div style='margin-top:8px;line-height:18px;'>";
            
            if (typeof(alert.above) != 'undefined') {
              // currency alerts
              if (alert.above.length > 0) {
                if (alert.above_sent == 1) {
                  alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"above_sent\")' class='line-through cursor-pointer'>Above: <b>"+alert.above+"</b> "+alert.above_currency+"</span><br>";
                }
                else {
                  alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"above_sent\")' class='cursor-pointer'>Above: <b>"+alert.above+"</b> "+alert.above_currency+"</span><br>";
                }
              }
            }
            else {
              // percentage alerts


              // get previously set "from" price
              var from_price = null;
              if (alert.plus_compared == 'USD') {
                from_price = (Number(alert.price_set_usd)).toFixed(2);
              }
              else if (alert.plus_compared == 'BTC') {
                // console.log(alert.price_set_btc);
                from_price = (Number(alert.price_set_btc)).toFixed(8);
              }
              else if (alert.plus_compared == 'ETH') {
                from_price = (Number(alert.price_set_eth)).toFixed(8);
              }


              if (alert.plus_percent && alert.plus_sent && alert.plus_change == 'from_now') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through cursor-pointer'><b>+"+alert.plus_percent+"%</b></span> from <span title='Update price' onclick='alertPerPriceRefresh("+coin_id+", "+alert.ID+", \""+alert.plus_compared+"\", \"plus\", \""+alert.alert_type+"\")' class='cur-span "+alert.ID+"-spin-"+alert.plus_compared+"' id='"+alert.ID+"-spin-plus'>"+ from_price + "</span> " + alert.plus_compared;
              }
              else if (alert.plus_percent && !alert.plus_sent && alert.plus_change == 'from_now') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='cursor-pointer'><b>+"+alert.plus_percent +"%</b></span> from <span title='Update price' class='cur-span "+alert.ID+"-spin-"+alert.plus_compared+"' onclick='alertPerPriceRefresh("+coin_id+", "+alert.ID+", \""+alert.plus_compared+"\", \"plus\", \""+alert.alert_type+"\")' id='"+alert.ID+"-spin-plus'>"+ from_price + "</span> " + alert.plus_compared;
              }

              // if (alert.plus_percent && alert.plus_sent && alert.plus_change == 'from_now') {
              //   alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through cursor-pointer'><b>+"+alert.plus_percent+"%</b> compared to "+alert.plus_compared+"</span>";
              // }
              // else if (alert.plus_percent && !alert.plus_sent && alert.plus_change == 'from_now') {
              //   alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='cursor-pointer'><b>+"+alert.plus_percent +"%</b> compared to "+ alert.plus_compared+"</span>";
              // }




              if (alert.plus_percent && alert.plus_sent && alert.plus_change == '1h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through cursor-pointer'><b>+"+alert.plus_percent+"%</b> in 1h. period</span>";
              }
              else if (alert.plus_percent && !alert.plus_sent && alert.plus_change == '1h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='cursor-pointer'><b>+"+alert.plus_percent +"%</b> in 1h. period</span>";
              }
              if (alert.plus_percent && alert.plus_sent && alert.plus_change == '24h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='line-through cursor-pointer'><b>+"+alert.plus_percent+"%</b> in 24h. period</span>";
              }
              else if (alert.plus_percent && !alert.plus_sent && alert.plus_change == '24h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"plus_sent\")' class='cursor-pointer'><b>+"+alert.plus_percent+"%</b> in 24h. period</span>";
              }
              if (alert.plus_percent && alert.minus_percent) {
                alerts_string += "<br>";
              }
            }

            if (typeof(alert.below) != 'undefined') {
              // currency alerts
              if (alert.below.length > 0) {
                if (alert.below_sent == '') {
                  alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"below_sent\")' class='cursor-pointer'>Below: <b>"+alert.below+"</b> "+alert.below_currency+"</span><br>";
                }
                else {
                  alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"below_sent\")' class='line-through cursor-pointer'>Below: <b>"+alert.below+"</b> "+alert.below_currency+"</span><br>";
                }
              }
            }
            else {
              // percentage alerts

              // get previously set "from" price
              var from_price = null;
              if (alert.minus_compared == 'USD') {
                from_price = (Number(alert.price_set_usd)).toFixed(2);
              }
              else if (alert.minus_compared == 'BTC') {
                // console.log(alert.price_set_btc);
                from_price = (Number(alert.price_set_btc)).toFixed(8);
              }
              else if (alert.minus_compared == 'ETH') {
                from_price = (Number(alert.price_set_eth)).toFixed(8);
              }

              if (alert.minus_percent && alert.minus_sent && alert.minus_change == 'from_now') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='line-through cursor-pointer'><b>-"+alert.minus_percent+"%</b></span> from <span title='Update price' onclick='alertPerPriceRefresh("+coin_id+", "+alert.ID+", \""+alert.minus_compared+"\", \"minus\", \""+alert.alert_type+"\")' class='cur-span noselect "+alert.ID+"-spin-"+alert.minus_compared+"' id='"+alert.ID+"-spin-minus'>"+ from_price + "</span> " + alert.minus_compared;
              }
              else if (alert.minus_percent && !alert.minus_sent && alert.minus_change == 'from_now') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='cursor-pointer'><b>-"+alert.minus_percent+"%</b></span> from <span class='cur-span noselect "+alert.ID+"-spin-"+alert.minus_compared+"' title='Update price' onclick='alertPerPriceRefresh("+coin_id+", "+alert.ID+", \""+alert.minus_compared+"\", \"minus\", \""+alert.alert_type+"\")' id='"+alert.ID+"-spin-minus'>"+ from_price + "</span> " + alert.minus_compared;
              }




              if (alert.minus_percent && alert.minus_sent && alert.minus_change == '1h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='line-through cursor-pointer'><b>-"+alert.minus_percent+"%</b> in 1h. period</span>";
              }
              else if (alert.minus_percent && !alert.minus_sent && alert.minus_change == '1h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='cursor-pointer'><b>-"+alert.minus_percent+"%</b> in 1h. period</span>";
              }
              if (alert.minus_percent && alert.minus_sent && alert.minus_change == '24h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='line-through cursor-pointer'><b>-"+alert.minus_percent+"%</b> in 24h. period</span>";
              }
              else if (alert.minus_percent && !alert.minus_sent && alert.minus_change == '24h') {
                alerts_string += "<span onclick='alertReenable("+alert.ID+", "+alert_type+", \"minus_sent\")' class='cursor-pointer'><b>-"+alert.minus_percent+"%</b> in 24h. period</span>";
              }
            }
              
            alerts_string += "</div><div title='Delete alert' class='alert-delete' id="+alert.ID+" onclick='deleteAnyAlert("+alert.ID+", "+alert_type+")'><svg width='9' height='9'><use xlink:href='#svg-alert-delete'></use></svg></div></div></div>";   

          }

        });
        // console.log(alerts_meta);
        alerts_string += "<div style='font-size:11px;padding:5px 0 10px 0;line-height: 190%;' class='noselect'>Alerts are sorted by coin market cap<br>Tip: Click an alert to re-enable it</div>";

        alerts_string += '<div onclick="logsShow()" style="position:relative;" class="logs logs-hover" id="logs-show-hide"><div id="logs-close-btn" title="Close" onclick="logsHide()" style="position:absolute;left:3px;top:3px;height:10px;width:10px;padding:5px;cursor:pointer;"><svg width="9" height="9"><use xlink:href="#svg-alert-delete"></use></svg></div><div  class="logs-title" style="margin-bottom:8px;">Logs</div><div class="logs-content" id="logs-content"></div></div><div style="height:10px;"></div>';
        
        // <div style='border-bottom: 1px solid #a5a5a5;margin-top:25px;margin-bottom:15px;'></div><div class='logs-content' id='logs-content' style='padding-top:10px;padding-bottom:5px;font-size:11px;line-height:150%;'><span onclick='logsShow()' class='blacklink'>Logs</span></div>";

        jQuery("#manage_alerts_acc_loader").hide();
        jQuery("#manage_alerts_acc_feedback").html(alerts_string);
        

        jQuery(".cursor-pointer").click(function(event) {
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


function alertPerPriceRefresh(coinId, alertId, cur, type, delivType) {
  // console.log(coinId, alertId, microType, cur);

  var priceNew = null;

  for(var i = 0; i < jqueryarray.length; i++) {
		var coin = jqueryarray[i];

		if (coin['id'] == coinId) {
      // var eth = coin['price_eth'];
      // console.log(coin.price_btc, coin.price_eth, coin.price_usd);
      if (cur == 'USD') {
        priceNew = coin.price_usd;
      }
      else if (cur == 'BTC') {
        priceNew = coin.price_btc;
      }
      else if (cur == 'ETH') {
        priceNew = coin.price_eth;
      }
      break;
    }
  }

  // console.log(alertId, cur, priceNew, type);
  
  var el = getById(alertId+'-spin-'+type);
  // console.log(el);
  el.innerHTML = '';

  el.classList.add("alert-per-price");

  var alert_per_price_refresh = 'action=alert_per_price_refresh&alert_id='+alertId+'&cur='+cur+'&price_new='+priceNew+'&delivery_type='+delivType;

  // console.log(alert_per_price_refresh);
  
  // Execute
  jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: alert_per_price_refresh+security_url,
    success: function(data) {
      if (data == 'success') {
        el.classList.remove("alert-per-price");
        if (cur == 'USD') {
          jQuery('.'+alertId+'-spin-'+cur).html(Number(priceNew).toFixed(2));
          // el.innerHTML = Number(priceNew).toFixed(2);
        }
        else {
          jQuery('.'+alertId+'-spin-'+cur).html(Number(priceNew).toFixed(8));
          // el.innerHTML = Number(priceNew).toFixed(8);
        }
      }
    }
  });
}


//
// LOGS
//


// for date
var options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };
var logsOpen = false;


function logsHide() {
  jQuery("#logs-show-hide").removeClass("logs-expanded");
  jQuery(".logs-title").removeClass("portfolio-alerts-about-title-bold");
  jQuery(".logs-content").hide();
  jQuery("#logs-close-btn").hide();
  jQuery("#logs-show-hide").addClass("logs-hover");
  
  setTimeout(function() {
    logsOpen = false;
  }, 100);
}

// load logs
function logsShow() {

  if (logsOpen) { return };

  jQuery("#logs-close-btn").show();
  jQuery("#logs-show-hide").addClass("logs-expanded");
  jQuery("#logs-show-hide").removeClass("logs-hover");
  jQuery(".logs-title").addClass("portfolio-alerts-about-title-bold");
  jQuery(".logs-content").show();

  // jQuery("#logs-show-hide").toggleClass("logs-expanded");
  // jQuery(".logs-title").toggleClass("portfolio-alerts-about-title-bold");
  // jQuery(".logs-content").toggle();

  jQuery('#logs-content').html('Loading...');

  var data = 'action=get_logs';

  function timeConverter(UNIX_timestamp){
    var a = new Date(UNIX_timestamp * 1000);
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    hour += '';
    if (hour.length == 1) { hour = '0'+hour }
    var min = a.getMinutes();
    min += '';
    if (min.length == 1) { min = '0'+min }
    var sec = a.getSeconds();
    sec += '';
    if (sec.length == 1) { sec = '0'+sec }
    var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
    return time;
  }

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
        var logsSmsHtml = '<br><b>SMS alerts</b><div style="height:20px;"></div>';

        for (var log in logsSms) {
          // var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsSms[log].timestamp).toLocaleString("en-US", options)+'</span>';

          if (logsSms[log].alert_ID.length > 0) {
            var clientTimestamp = '<a href="'+homePath+'alert/'+logsSms[log].alert_ID+'" target="_blank" class="blacklink">'+logsSms[log].name+' ('+logsSms[log].symbol+') alert</a><br><span style="letter-spacing:1px;">'+timeConverter(logsSms[log].time)+'</span>';
          }
          else {
            var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsSms[log].timestamp).toLocaleString("en-US", options)+'</span>';
          }

          logsSmsHtml += clientTimestamp + '<br>' + logsSms[log].destination;

          if (logsSms[log].status == 'error' || logsSms[log].status == 'failed') {
            logsSmsHtml += '<br><b style="color:red;">Undelivered alert</b><br>Possible reasons:<br>- Wrong phone number <a href="https://support.twilio.com/hc/en-us/articles/223183008-Formatting-International-Phone-Numbers" target="_blank" class="blacklink">format</a><br>- Wrong phone number<br>- Cell phone out of range<br>- Expired Coinwink subscription<br>- No SMS credits available<br>';
          }

          logsSmsHtml += '<div style="height:20px;"></div>';
        }

        logsSmsHtml += "<br>";
        
        finalHtml += logsSmsHtml;
      }

      if (logsEmail.length > 0) {
        var logsEmailHtml = '<br><b>Email alerts</b><div style="height:20px;"></div>';

        for (var log in logsEmail) {
          if (logsEmail[log].alert_ID.length > 0) {
            var clientTimestamp = '<a href="'+homePath+'alert/'+logsEmail[log].alert_ID+'" target="_blank" class="blacklink">'+logsEmail[log].name+' ('+logsEmail[log].symbol+') alert</a><br><span style="letter-spacing:1px;">'+timeConverter(logsEmail[log].time)+'</span>';
          }
          else {
            var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsEmail[log].timestamp).toLocaleString("en-US", options)+'</span>';
          }

          logsEmailHtml += clientTimestamp + '<br>' + logsEmail[log].destination;

          if (logsEmail[log].status == 'error') {
            if (logsEmail[log].error == 'SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting') {
              if (parseInt(logsEmail[log].time) < 1590836464) {
                logsEmailHtml += '<br><b style="color:red;">Undelivered alert</b><br>Automatically blocked. <a href="https://twitter.com/Coinwink/status/1174162988791713793" target="_blank" class="blacklink">Details</a><br>';
              }
              else {
                logsEmailHtml += '<br><b style="color:red;">Undelivered alert</b><br>Temporary email disruption.<br>';
              }
            }
            else {
              logsEmailHtml += '<br><b style="color:red;">Undelivered alert</b><br>Double-check your e-mail address.<br>';
            }
          }

          logsEmailHtml += '<div style="height:20px;"></div>';
        }

        logsEmailHtml += "<br>";

        finalHtml += logsEmailHtml;
      }

      if (logsPortfolio.length > 0) {
        var logsPortfolioHtml = '<br><b>Portfolio alerts</b><br><br>';

        for (var log in logsPortfolio) {
          // var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsPortfolio[log].timestamp).toLocaleString("en-US", options)+'</span>';

          if (logsPortfolio[log].alert_ID.length > 0) {
            var clientTimestamp = '<a href="'+homePath+'alert/'+logsPortfolio[log].alert_ID+'" target="_blank" class="blacklink">'+logsPortfolio[log].name+' ('+logsPortfolio[log].symbol+') alert</a><br><span style="letter-spacing:1px;">'+timeConverter(logsPortfolio[log].time)+'</span>';
          }
          else {
            var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsPortfolio[log].timestamp).toLocaleString("en-US", options)+'</span>';
          }

          logsPortfolioHtml += clientTimestamp + '<br>' + logsPortfolio[log].destination + '<div style="height:20px;"></div>';
        }
        
        // logsPortfolioHtml += "<br>";

        finalHtml += logsPortfolioHtml;
      }

      if (finalHtml.length > 0) {
        jQuery("#logs-content").html('<div style="margin-bottom:0px;">'+finalHtml+'</div>');
      }
      else {
        jQuery('#logs-content').html('<div style="padding:15px;padding-bottom:18px;"><span style="font-size:12.5px;">No logs yet.</span><br></div>');
      }

      logsOpen = true;
      
    }
  });

}


//
//
//

//
// CREATE ALERTS
//

//
//
//

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
  var formData = jQuery(this).serializeArray();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert+security_url,
	beforeSend: function(){
    jQuery("#create_alert_button").hide();
    jQuery("#feedback").hide();
		jQuery("#ajax_loader").show();
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
      showCreated('email', formData);
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
  var formData = jQuery(this).serializeArray();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert_acc+security_url,
	beforeSend: function(){
		jQuery("#create_alert_button_acc").hide();
		jQuery("#ajax_loader_acc").show();
	},
	success:function(data){

		if (data == 'Limit error') {
			// jQuery("#feedback_acc").html("You have reached the limit of 10 alerts. To continue, delete some alerts first or <a href='subscription' data-navigo style='color:red!important;'>subscribe</a> to a Premium plan.");
      jQuery("#limit-error").show();
      jQuery("#create_alert_button_acc").show();
			jQuery("#ajax_loader_acc").hide();
		}
		else {
      showCreated('emailAcc', formData);
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
  var formData = jQuery(this).serializeArray();

	jQuery.ajax({
    type:"POST",
    url: ajax_url,
    data: form_new_alert_percent+security_url,
    beforeSend: function(){
      jQuery("#create_alert_button_percent").hide();
      jQuery("#feedback_percent").hide();
      jQuery("#ajax_loader_percent").show();
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
				showCreated('emailPer', formData);
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
  var formData = jQuery(this).serializeArray();

	jQuery.ajax({
	type:"POST",
	url: ajax_url,
	data: form_new_alert_percent_acc+security_url,
	beforeSend: function(){
		jQuery("#create_alert_button_percent_acc").hide();
		jQuery("#ajax_loader_percent_acc").show();
	},
	success:function(data){

		if (data == 'Limit error') {
      jQuery("#limit-error-per").show();
			jQuery("#create_alert_button_percent_acc").show();
			jQuery("#ajax_loader_percent_acc").hide();
		}
		else {
      showCreated('emailPerAcc', formData);
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
  var formData = jQuery(this).serializeArray();

	jQuery.ajax({
		type:"POST",
		url: ajax_url,
		data: form_new_alert_sms+security_url,
		beforeSend: function(){
			jQuery("#create_alert_button_sms").hide();
			jQuery("#ajax_loader_sms").show();
		},
		success:function(data){

      if (data == 'Subs error') {
				jQuery("#feedback_sms").html("Subscription not found.<br>To create SMS alerts, please <a href='subscription' data-navigo class='blacklink' style='color:red!important;'>subscribe</a> to the Premium plan.");
				jQuery("#ajax_loader_sms").hide();
				jQuery("#create_alert_button_sms").show();
			}
			else {
				showCreated('sms', formData);
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
  var formData = jQuery(this).serializeArray();

	jQuery.ajax({
		type:"POST",
		url: ajax_url,
		data: form_new_alert_sms_per+security_url,
		beforeSend: function(){
			jQuery("#create_alert_button_sms_per").hide();
			jQuery("#ajax_loader_sms_per").show();
		},
		success:function(data){
			if (data == 'Subs error') {
				jQuery("#feedback_sms_per").html("Subscription not found.<br>To create SMS alerts, please <a href='subscription' data-navigo class='blacklink' style='color:red!important;'>subscribe</a> to the Premium plan.");
				jQuery("#ajax_loader_sms_per").hide();
				jQuery("#create_alert_button_sms_per").show();
			}
			else {
				showCreated('smsPer', formData);
			}
		
		}
	}); 
	return false;
}



// After alert created - User feedback
function showCreated(type, formData) {
  // console.log(formData);
  jQuery(".new-crypto-alert-link").hide();

  // ALL TYPES
  var coinName = formData[1].value;
  var coinSymbol = formData[2].value;

  jQuery('.current-view').hide();
  jQuery('#created-container').show();

  var deliveredTo = '';
  var double = false;

  jQuery('#created-alert-first').hide();
  jQuery('#created-alert-second').hide();

  jQuery('.new-alert-link').hide();

  jQuery('#created-header-per').hide();
  jQuery('#created-header').css('height', '50px');

  // INDIVIDUAL TYPES
  if (type == 'email') {
    deliveredTo = formData[3].value;

    if (formData[4].value.length != 0 && formData[6].value.length != 0) {
      double = true;
    }
  }
  else if (type == 'emailPer') {
    deliveredTo = formData[6].value;

    if (formData[7].value.length != 0 && formData[10].value.length != 0) {
      double = true;
    }
  }
  else if (type == 'emailAcc') {
    deliveredTo = formData[3].value;

    if (formData[4].value.length != 0 && formData[6].value.length != 0) {
      double = true;
    }
  }
  else if (type == 'emailPerAcc') {
    deliveredTo = formData[6].value;

    if (formData[7].value.length != 0 && formData[10].value.length != 0) {
      double = true;
    }
  }
  else if (type == 'sms') {
    deliveredTo = formData[3].value;

    if (formData[4].value.length != 0 && formData[6].value.length != 0) {
      double = true;
    }
  }
  else if (type == 'smsPer') {
    deliveredTo = formData[6].value;

    if (formData[7].value.length != 0 && formData[10].value.length != 0) {
      double = true;
    }
  }

  jQuery('#created-delivered-to').html(deliveredTo);

  if (!double) {
    jQuery('#created-sing-or-plur').html('Alert');
    jQuery('#created-alert-type').html('Alert created');
  }
  else {
    jQuery('#created-sing-or-plur').html('Alerts');
    jQuery('#created-alert-type').html('Alerts created');
  }

  // Check if user is logged in
  var acc = true;
  if (type == 'email' || type == 'emailPer') {
    acc = false;
  }
  if (!acc) {
    jQuery('#created-sign-up').show();
  }
  else {
    jQuery('#created-manage-alerts-link').show();
  }


  // CUR
  if (type == 'email' || type == 'emailAcc' || type == 'sms') {

    var deliveryType = 'email';

    if (type == 'sms') {
      deliveryType = 'SMS'
    }

    jQuery('#created-header-title').html('New '+deliveryType+' alert');

    var createdStringFirst = '';
    if (formData[4].value != '') {
      createdStringFirst = coinName + ' ('+ coinSymbol +')<br>price is above ' + formData[4].value+ ' ' + formData[5].value;
  
      jQuery('#created-alert-first').show();
      jQuery('#created-alert-first').html(createdStringFirst);
    }

    var createdStringSecond = '';
    if (formData[6].value != '') {
      createdStringSecond = coinName + ' ('+ coinSymbol +')<br>price is below ' + formData[6].value + ' ' + formData[7].value;
      jQuery('#created-alert-second').show();
      jQuery('#created-alert-second').html(createdStringSecond);  
    }
    
    if (type == 'email') {
      jQuery('.link-email').show();
    }
    else if (type == 'emailAcc') {
      jQuery('.link-email').show();
    }
    else if (type == 'sms') {
      jQuery('.link-sms').show();
    }

  }


  // PER
  if (type == 'emailPer' || type == 'emailPerAcc' || type == 'smsPer') {

    jQuery('#created-header-per').show();
    jQuery('#created-header').css('height', '63px');

    var deliveryType = 'email';

    if (type == 'smsPer') {
      deliveryType = 'SMS'
    }

    jQuery('#created-header-title').html('New '+deliveryType+' alert');

    var createdStringFirst = '';
    if (formData[7].value != '') {
      if (formData[8].value == 'from_now') {
        createdStringFirst = coinName + ' ('+ coinSymbol +')<br>price increases by '+formData[7].value+'%<br>compared to '+formData[9].value;
      }
      else if (formData[8].value == '1h') {
        createdStringFirst = coinName + ' ('+ coinSymbol +')<br>price increases by '+formData[7].value+'%<br>in 1 h period';
      }
      else if (formData[8].value == '24h') {
        createdStringFirst = coinName + ' ('+ coinSymbol +')<br>price increases by '+formData[7].value+'%<br>in 24 h period';
      }
      jQuery('#created-alert-first').show();
      jQuery('#created-alert-first').html(createdStringFirst);
    }

    var createdStringSecond = '';
    if (formData[10].value != '') {
      if (formData[11].value == 'from_now') {
        createdStringSecond = coinName + ' ('+ coinSymbol +')<br> price decreases by '+formData[10].value+'%<br>compared to '+formData[12].value;
      }
      else if (formData[11].value == '1h') {
        createdStringSecond = coinName + ' ('+ coinSymbol +')<br> price decreases by '+formData[10].value+'%<br>in 1 h period';
      }
      else if (formData[11].value == '24h') {
        createdStringSecond = coinName + ' ('+ coinSymbol +')<br> price decreases by '+formData[10].value+'%<br>in 24 h period';
      }
      jQuery('#created-alert-second').show();
      jQuery('#created-alert-second').html(createdStringSecond);  
    }

    if (type == 'emailPer') {
      jQuery('.link-email-per').show();
    }
    else if (type == 'emailPerAcc') {
      jQuery('.link-email-per').show();
    }
    else if (type == 'smsPer') {
      jQuery('.link-sms-per').show();
    }

  }

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
  var el = getById(self.id);

  if (typeof(curCurrent) != 'undefined') {
    if (isLoggedIn) {
      jQuery.ajax({
        type: 'POST',
        url: ajax_url,
        data: 'action=config_cur_main&cur_main=' + curCurrent + security_url
      })
    }

    var price = curList[curCurrent];
    // console.log(curList, price);
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
      var eur = coin['price_usd'] * rates['eur'];
      var gbp = coin['price_usd'] * rates['gbp'];
      var aud = coin['price_usd'] * rates['aud'];
      var cad = coin['price_usd'] * rates['cad'];
      
      var brl = coin['price_usd'] * rates['brl'];
      var mxn = coin['price_usd'] * rates['mxn'];
      var jpy = coin['price_usd'] * rates['jpy'];
      var sgd = coin['price_usd'] * rates['sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };

      if (!isLoggedIn) {
        curCurrent = 'USD';
      }
      else {
        curCurrent = cur_main;
      }

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);


      jQuery("#pricediv").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"/'><img style='vertical-align:middle;' width='12' src='"+homePath+"img/coins/16x16/"+
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
      var eur = coin['price_usd'] * rates['eur'];
      var gbp = coin['price_usd'] * rates['gbp'];
      var aud = coin['price_usd'] * rates['aud'];
      var cad = coin['price_usd'] * rates['cad'];
      
      var brl = coin['price_usd'] * rates['brl'];
      var mxn = coin['price_usd'] * rates['mxn'];
      var jpy = coin['price_usd'] * rates['jpy'];
      var sgd = coin['price_usd'] * rates['sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      if (!isLoggedIn) {
        curCurrent = 'USD';
      }
      else {
        curCurrent = cur_main;
      }

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }
 
      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);


      jQuery("#pricediv_sms").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"/'><img style='vertical-align:middle;' width='12' src='"+homePath+"img/coins/16x16/"+
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
      var eur = coin['price_usd'] * rates['eur'];
      var gbp = coin['price_usd'] * rates['gbp'];
      var aud = coin['price_usd'] * rates['aud'];
      var cad = coin['price_usd'] * rates['cad'];
      
      var brl = coin['price_usd'] * rates['brl'];
      var mxn = coin['price_usd'] * rates['mxn'];
      var jpy = coin['price_usd'] * rates['jpy'];
      var sgd = coin['price_usd'] * rates['sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      if (!isLoggedIn) {
        curCurrent = 'USD';
      }
      else {
        curCurrent = cur_main;
      }

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);

      jQuery("#pricediv_acc").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"/'><img style='vertical-align:middle;' width='12' src='"+homePath+"img/coins/16x16/"+
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
      var eur = coin['price_usd'] * rates['eur'];
      var gbp = coin['price_usd'] * rates['gbp'];
      var aud = coin['price_usd'] * rates['aud'];
      var cad = coin['price_usd'] * rates['cad'];
      
      var brl = coin['price_usd'] * rates['brl'];
      var mxn = coin['price_usd'] * rates['mxn'];
      var jpy = coin['price_usd'] * rates['jpy'];
      var sgd = coin['price_usd'] * rates['sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      if (!isLoggedIn) {
        curCurrent = 'USD';
      }
      else {
        curCurrent = cur_main;
      }

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);


      jQuery("#pricediv_percent").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"/'><img style='vertical-align:middle;' width='12' src='"+homePath+"img/coins/16x16/"+
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
      var eur = coin['price_usd'] * rates['eur'];
      var gbp = coin['price_usd'] * rates['gbp'];
      var aud = coin['price_usd'] * rates['aud'];
      var cad = coin['price_usd'] * rates['cad'];
      
      var brl = coin['price_usd'] * rates['brl'];
      var mxn = coin['price_usd'] * rates['mxn'];
      var jpy = coin['price_usd'] * rates['jpy'];
      var sgd = coin['price_usd'] * rates['sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      if (!isLoggedIn) {
        curCurrent = 'USD';
      }
      else {
        curCurrent = cur_main;
      }

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);


      jQuery("#pricediv_percent_acc").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"/'><img style='vertical-align:middle;' width='12' src='"+homePath+"img/coins/16x16/"+
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
      var eur = coin['price_usd'] * rates['eur'];
      var gbp = coin['price_usd'] * rates['gbp'];
      var aud = coin['price_usd'] * rates['aud'];
      var cad = coin['price_usd'] * rates['cad'];
      
      var brl = coin['price_usd'] * rates['brl'];
      var mxn = coin['price_usd'] * rates['mxn'];
      var jpy = coin['price_usd'] * rates['jpy'];
      var sgd = coin['price_usd'] * rates['sgd'];


      curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };
      if (!isLoggedIn) {
        curCurrent = 'USD';
      }
      else {
        curCurrent = cur_main;
      }

      var price = curList[curCurrent];
      if (price < 0.1) { price = parseFloat(price).toFixed(4); } else { price = parseFloat(price).toFixed(2); }

      // var price = formatCurrencyHome(curList[curCurrent], curCurrent);

      jQuery("#pricediv_sms_per").html("<a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/" + coin['slug'] +"/'><img style='vertical-align:middle;' width='12' src='"+homePath+"img/coins/16x16/"+
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
// Coin symbol to pass to the backend
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

  if (portfolioAlert1 < 5 || portfolioAlert1 > 1000) {
    if (portfolioAlert1 < 5) { 
      portfolioAlert1 = 5;
      jQuery('#portfolio-alert-1-value').val("5");
    }
    else { 
      portfolioAlert1 = 1000;
      jQuery('#portfolio-alert-1-value').val("1000");
    }
  }

  if (portfolioAlert2 < 5 || portfolioAlert2 > 1000) {
    if (portfolioAlert2 < 5) { 
      portfolioAlert2 = 5;
      jQuery('#portfolio-alert-2-value').val("5");
    }
    else { 
      portfolioAlert2 = 1000;
      jQuery('#portfolio-alert-2-value').val("1000");
    }
  }
  
  if (portfolioAlert3 < 5 || portfolioAlert3 > 1000) {
    if (portfolioAlert3 < 5) { 
      portfolioAlert3 = 5;
      jQuery('#portfolio-alert-3-value').val("5");
    }
    else { 
      portfolioAlert3 = 1000;
      jQuery('#portfolio-alert-3-value').val("1000");
    }
  }
  
  if (portfolioAlert4 < 5 || portfolioAlert4 > 1000) {
    if (portfolioAlert4 < 5) { 
      portfolioAlert4 = 5;
      jQuery('#portfolio-alert-4-value').val("5");
    }
    else { 
      portfolioAlert4 = 1000;
      jQuery('#portfolio-alert-4-value').val("1000");
    }
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


// Lightbox (kind of)
function exampleAlerts() {
  jQuery('body').prepend('<div class="popup" onclick="jQuery(\'.popup\').remove()" style="z-index: 99999;cursor: pointer;"><div style="z-index: 99999;display:grid;background-color: rgba(0,0,0,.8);overflow-x: hidden;-webkit-box-sizing: content-box;box-sizing: content-box;-webkit-box-align: center;-ms-flex-align: center;align-items: center;height:100%;width:100%;position:fixed;"><img src="https://coinwink.com/brand/files/screenshots/02-email-crypto-alerts.png?v=001" style="max-width: 90%;max-height: 85%;display: block;border:4px solid white;position: absolute;left: 0;top: 0;bottom: 0;right: 0;margin: auto;position: fixed;z-index:99999;min-width:100px;min-height:100px;background-color:#4f585b;background-image:url(https://coinwink.com/wp-content/themes/coinwink-theme/img/ajax_loader_dark.gif);background-repeat:no-repeat;background-position:center;"></div></div>');
}

function examplePortfolio() {
  jQuery('body').prepend('<div class="popup" onclick="jQuery(\'.popup\').remove()" style="z-index: 99999;cursor: pointer;"><div style="z-index: 99999;display:grid;background-color: rgba(0,0,0,.8);overflow-x: hidden;-webkit-box-sizing: content-box;box-sizing: content-box;-webkit-box-align: center;-ms-flex-align: center;align-items: center;height:100%;width:100%;position:fixed;"><img src="https://coinwink.com/brand/files/screenshots/04-portfolio.png" style="max-width: 90%;max-height: 85%;display: block;border:4px solid white;position: absolute;left: 0;top: 0;bottom: 0;right: 0;margin: auto;position: fixed;z-index:99999;min-width:100px;min-height:100px;background-color:#4f585b;background-image:url(https://coinwink.com/wp-content/themes/coinwink-theme/img/ajax_loader_dark.gif);background-repeat:no-repeat;background-position:center;"></div></div>');
}

function exampleWatchlist() {
  jQuery('body').prepend('<div class="popup" onclick="jQuery(\'.popup\').remove()" style="z-index: 99999;cursor: pointer;"><div style="z-index: 99999;display:grid;background-color: rgba(0,0,0,.8);overflow-x: hidden;-webkit-box-sizing: content-box;box-sizing: content-box;-webkit-box-align: center;-ms-flex-align: center;align-items: center;height:100%;width:100%;position:fixed;"><img src="https://coinwink.com/brand/files/screenshots/05-watchlist.png?v=001" style="max-width: 90%;max-height: 85%;display: block;border:4px solid white;position: absolute;left: 0;top: 0;bottom: 0;right: 0;margin: auto;position: fixed;z-index:99999;min-width:100px;min-height:100px;background-color:#4f585b;background-image:url(https://coinwink.com/wp-content/themes/coinwink-theme/img/ajax_loader_dark.gif);background-repeat:no-repeat;background-position:center;"></div></div>');
}