

//
// ACC: Ajax to manage alerts
//

function ajaxSubmit_acc() {
    alerts = {};
    alerts_string = "";
  
      jQuery.ajax({
      type:"GET",
      url: '/api/manage_alerts_acc',
      success: function(data) {
  
        if (data == 'zero_alerts') {
          jQuery("#manage_alerts_acc_loader").hide();
            jQuery("#manage_alerts_acc_feedback").html('<div style="height:20px;"></div><br><span style="font-size:12.5px;">You have no alerts to manage.<br><br>Create some alerts first!</span><div style="height:20px;"></div><div onclick="logsShow()" style="position:relative;" class="logs logs-hover" id="logs-show-hide"><div id="logs-close-btn" onclick="logsHide()" style="position:absolute;left:3px;top:3px;height:10px;width:10px;padding:5px;cursor:pointer;" title="Close"><svg width="9" height="9"><use xlink:href="#svg-alert-delete"></use></svg></div><span  class="logs-title">Logs</span><div class="logs-content" id="logs-content"></div></div><div style="height:5px;"></div>');
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
              for(var i = 0; i < cw_cmc.length; i++) {
                  var coin = cw_cmc[i];
                  if (coin['name'] == key) {
                    var coin_id = coin['id'];
                    alerts_meta[coin_id] = key;
                    return;
                  }
                  else {
                    if (i == (cw_cmc.length - 1)) {
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
            var rank = cw_cmc.findIndex(function(x) { return x.id == coin_id });
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
              for(var i = 0; i < cw_cmc.length; i++) {
                  var coin = cw_cmc[i];
                  if (coin['name'] == coin_name) {
                    coin_slug = coin['slug']; // current coin in loop slug
                  }
              }
            }
            getCoinSlug(coin_name);
  
  
            // Need to define, or shows an error
            sms_alerts = 'sms_alerts';
            sms_alerts_per = 'sms_alerts_per';
            email_alerts = 'email_alerts';
            email_alerts_per = 'email_alerts_per';
            tg_alerts = 'tg_alerts';
            tg_alerts_per = 'tg_alerts_per';
  
            // console.log(alerts);
  
            // Build alerts view in the particular order
            jQuery.each(alerts[coin_name], function(id, alert) {
              if (alert.alert_type == 'sms_alerts') {
                buildAlerts(alert);
              }
            });
            jQuery.each(alerts[coin_name], function(id, alert) {
              if (alert.alert_type == 'sms_alerts_per') {
                buildAlerts(alert);
              }
            });
            jQuery.each(alerts[coin_name], function(id, alert) {
              if (alert.alert_type == 'email_alerts') {
                buildAlerts(alert);
              }
            });
            jQuery.each(alerts[coin_name], function(id, alert) {
              if (alert.alert_type == 'email_alerts_per') {
                buildAlerts(alert);
              }
            });
            jQuery.each(alerts[coin_name], function(id, alert) {
              if (alert.alert_type == 'tg_alerts') {
                buildAlerts(alert);
              }
            });
            jQuery.each(alerts[coin_name], function(id, alert) {
              if (alert.alert_type == 'tg_alerts_per') {
                buildAlerts(alert);
              }
            });
  
  
            // Alerts array builder
            function buildAlerts(alert) {
  
              if (alert.alert_type == 'sms_alerts' || alert.alert_type == 'sms_alerts_per') {
                ma_alert_destination = alert.phone;
              }
              else if (alert.alert_type == 'tg_alerts' || alert.alert_type == 'tg_alerts_per') {
                ma_alert_destination = '@'+alert.tg_user;
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

              var alertIcon = '';
              if (alert.alert_type == 'sms_alerts' || alert.alert_type == 'sms_alerts_per') {
                alertIcon='sms';
              }
              else if (alert.alert_type == 'tg_alerts' || alert.alert_type == 'tg_alerts_per') {
                alertIcon='telegram';
              }
              else if (alert.alert_type == 'email_alerts' || alert.alert_type == 'email_alerts_per') {
                alertIcon='email';
              }
              
              // <div style='position:absolute;width:13px;height:15px;left:6px;top:5px;'><img src='/img/icon-"+alertIcon+".svg' title="+alertIcon.toUpperCase()+"></div>

              var iconSvg = ''
              if (alertIcon == 'telegram') {
                iconSvg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.7 129.6" style="enable-background:new 0 0 149.7 129.6;" xml:space="preserve"><path class="alert-icon-svg" d="M10.9,49.5c44.8-15,89.5-30,134.3-45c-8.8,37.3-17.5,74.5-26.3,111.8c-1,4.2-1.5,7.2-3.1,8.2c-2.9,2-7.1-1.2-8.9-2.9c-14.4-12.4-28.8-24.8-43.2-37.3c15.7-16.3,31.4-32.5,47.1-48.8C88.3,48,65.7,60.4,43.1,72.8C32,67.9,20.8,62.9,9.6,58C2.6,54.6,2.6,51.9,10.9,49.5L10.9,49.5z"/><path class="alert-icon-svg" d="M43.1,72.8l8.8,46.9l11.7-35.4 M52.1,121.1l28.1-22.5"/></svg>';
              }
              else if (alertIcon == 'email') {
                iconSvg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.6 107.2" style="enable-background:new 0 0 149.6 107.2;" xml:space="preserve"><path class="alert-icon-svg" d="M10.8,4.5h128c3.5,0,6.3,2.9,6.3,6.3V92c0,5.9-4.8,10.7-10.7,10.7H15.2c-5.9,0-10.7-4.8-10.7-10.7V10.8C4.5,7.4,7.4,4.5,10.8,4.5L10.8,4.5z"/><path class="alert-icon-svg" d="M141,8.4l-59,59c-3.9,3.9-10.4,3.9-14.3,0l-59-59"/></svg>';
              }
              else if (alertIcon == 'sms') {
                iconSvg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 151 137.6" style="enable-background:new 0 0 151 137.6;" xml:space="preserve"><path class="alert-icon-svg" d="M30.2,99.8v33.3l23.5-23.5c6.9,1.7,14.2,2.6,21.8,2.6c39.2,0,71-24.1,71-53.9s-31.8-53.9-71-53.9c-39.2,0-71,24.1-71,53.9C4.5,75,14.5,89.9,30.2,99.8L30.2,99.8z"/><path class="alert-icon-svg" d="M46.7,46h57.5 M46.7,71.7h57.5"/></svg>';
              }

              alerts_string += "<div class='alert-box' id='alert-div-"+alert.ID+"'><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;'>"+iconSvg+"</div><a target='_blank' class='portfoliocoin' href='https://coinmarketcap.com/currencies/"+coin_slug+"/'><img width='18' height='18' class='noselect' src='/img/coins/32x32/"
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
                
              alerts_string += "</div><div title='Delete alert' class='alert-delete noselect' id="+alert.ID+" onclick='deleteAnyAlert("+alert.ID+", "+alert_type+")'>✕</div></div></div>";   
  
            }
  
          });
          // console.log(alerts_meta);
          alerts_string += "<div id='manage-alerts-note'><div style='font-size:11px;padding:5px 0 10px 0;line-height: 190%;' class='noselect'>Alerts are sorted by coin market cap<br>Tip: Click an alert to re-enable it</div></div>";
  
          alerts_string += '<div onclick="logsShow()" style="position:relative;" class="logs logs-hover" id="logs-show-hide"><div id="logs-close-btn" title="Close" onclick="logsHide()" class="alert-delete noselect" style="left:3px;top:3px;">✕</div><div  class="logs-title" style="margin-bottom:8px;">Logs</div><div class="logs-content" id="logs-content"></div></div><div style="height:10px;"></div>';
          
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
  
    if (cw_theme == 'metaverse') {
      var divHeight = jQuery(divIdString).height() + 30;
    }
    else {
      var divHeight = jQuery(divIdString).height() + 32;
    }
    jQuery(divIdString).css('height', divHeight+'px');
    jQuery(divIdString).css('padding-top', (divHeight / 2) - 5 + 'px');
    jQuery(divIdString).html('<span style="user-select: none;-webkit-user-select: none;-ms-user-select: none;">Deleted!</span>');

    setTimeout(() => {
      jQuery(divIdString).remove();
    }, 300);
  
    // jQuery(divIdString).remove();

    if (type == "email_alerts") {
      var delete_alert = 'action=delete_alert_acc_email&alert_id='+id;
    }
    else if (type == "email_alerts_per") {
      var delete_alert = 'action=delete_alert_percent_acc&alert_id='+id;
    }
    else if (type == "sms_alerts") {
      var delete_alert = 'action=delete_alert_acc_sms&alert_id='+id;
    }
    else if (type == "sms_alerts_per") {
      var delete_alert = 'action=delete_alert_acc_sms_per&alert_id='+id;
    }
    else if (type == "tg_alerts") {
      var delete_alert = 'action=delete_alert_acc_tg&alert_id='+id;
    }
    else if (type == "tg_alerts_per") {
      var delete_alert = 'action=delete_alert_acc_tg_per&alert_id='+id;
    }
    
    // Execute
    jQuery.ajax({
      type:"POST",
      url: '/api/delete_alert',
      data: delete_alert
    });
  
    if (jQuery('#manage_alerts_acc_feedback').html().length < 800) {
      jQuery("#manage-alerts-note").html('<div style="height:40px;"></div><span style="font-size:12.5px;">You have no alerts to manage.<br><br>Create some alerts first!</span><div style="height:20px;"></div>');
      return
    }
  
  }
  
  
  // Re-enable alert
  function alertReenable (id, type, microType) {
  
    var alert_reenable = 'alert_id='+id+'&type='+type+'&microType='+microType;
    
    // Execute
    jQuery.ajax({
      type:"POST",
      url: '/api/alert_reenable',
      data: alert_reenable
    });
    
  }
  
  
  function alertPerPriceRefresh(coinId, alertId, cur, type, delivType) {
    // console.log(coinId, alertId, microType, cur);
  
    var priceNew = null;
  
    for(var i = 0; i < cw_cmc.length; i++) {
          var coin = cw_cmc[i];
  
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
  
    var alert_per_price_refresh = 'alert_id='+alertId+'&cur='+cur+'&price_new='+priceNew+'&delivery_type='+delivType;
  
    // console.log(alert_per_price_refresh);
    
    // Execute
    jQuery.ajax({
      type: "POST",
      url: '/api/alert_per_price_refresh',
      data: alert_per_price_refresh,
      success: function(data) {
        // console.log(data);        
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
  
    jQuery('#logs-content').html('<div style="height:15px;"></div><div class="appify-spinner-div"></div><div style="height:16px;"></div>');
  
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
      type:"GET",
      url: '/api/get_logs',
      success: function(data) {
        var dataArray = JSON.parse(data);
        var finalHtml = '';
  
        var logsEmail = dataArray[0];
        var logsSms = dataArray[1];
        var logsTelegram = dataArray[2];
        var logsPortfolio = dataArray[3];
  
        // @wp_todo: check/show only when logs available
        
        if (logsSms.length > 0) {
          var logsSmsHtml = '<br><b>SMS alerts</b><div style="height:20px;"></div>';
  
          for (var log in logsSms) {
            // var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsSms[log].timestamp).toLocaleString("en-US", options)+'</span>';
  
            if (logsSms[log].alert_ID.length > 0) {
              var clientTimestamp = '<a href="/alert/'+logsSms[log].alert_ID+'" target="_blank" class="blacklink">'+logsSms[log].name+' ('+logsSms[log].symbol+') alert</a><br><span style="letter-spacing:1px;">'+timeConverter(logsSms[log].time)+'</span>';
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
              var clientTimestamp = '<a href="/alert/'+logsEmail[log].alert_ID+'" target="_blank" class="blacklink">'+logsEmail[log].name+' ('+logsEmail[log].symbol+') alert</a><br><span style="letter-spacing:1px;">'+timeConverter(logsEmail[log].time)+'</span>';
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
  
        if (logsTelegram.length > 0) {
          var logsTelegramHtml = '<br><b>Telegram alerts</b><div style="height:20px;"></div>';
  
          for (var log in logsTelegram) {
            if (logsTelegram[log].alert_ID.length > 0) {
              var clientTimestamp = '<a href="/alert/'+logsTelegram[log].alert_ID+'" target="_blank" class="blacklink">'+logsTelegram[log].name+' ('+logsTelegram[log].symbol+') alert</a><br><span style="letter-spacing:1px;">'+timeConverter(logsTelegram[log].time)+'</span>';
            }
            else {
              var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsTelegram[log].timestamp).toLocaleString("en-US", options)+'</span>';
            }
  
            logsTelegramHtml += clientTimestamp + '<br>' + logsTelegram[log].destination;
  
            if (logsTelegram[log].status == 'error') {
              logsTelegramHtml += '<br><b style="color:red;">Undelivered alert</b><br>Unknown error.<br>';
            }
  
            logsTelegramHtml += '<div style="height:20px;"></div>';
          }
  
          logsTelegramHtml += "<br>";
  
          finalHtml += logsTelegramHtml;
        }

        if (logsPortfolio.length > 0) {
          var logsPortfolioHtml = '<br><b>Portfolio alerts</b><br><br>';
  
          for (var log in logsPortfolio) {
            // var clientTimestamp = '<span style="letter-spacing:1px;">'+new Date(logsPortfolio[log].timestamp).toLocaleString("en-US", options)+'</span>';
  
            if (logsPortfolio[log].alert_ID.length > 0) {
              var clientTimestamp = '<a href="/alert/'+logsPortfolio[log].alert_ID+'" target="_blank" class="blacklink">'+logsPortfolio[log].name+' ('+logsPortfolio[log].symbol+') alert</a><br><span style="letter-spacing:1px;">'+timeConverter(logsPortfolio[log].time)+'</span>';
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
          jQuery('#logs-content').html('<div style="padding:15px;padding-bottom:18px;"><span style="font-size:12.5px;">No logs yet. First receive some alerts, then check your logs.</span><br></div>');
        }
  
        logsOpen = true;
        
      }
    });
  
  }