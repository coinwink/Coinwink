function openWatchlist() {

  jQuery("#watchlist_content").hide();
  jQuery("#ajax_loader_watchlist").show();

  jQuery.ajax({
      type:"POST",
      url: ajax_url,
      data: 'action=get_watchlist'+security_url,
      success:function(data){
      var watchlist = data;
      
      if (watchlist) {
          watchlist = watchlist.replace(/\\/g, "");
          watchlist = JSON.parse( watchlist );
          window.watchlist = watchlist;
          load_watchlist(watchlist);
      }
      else {
          var watchlist = [];
          window.watchlist = watchlist;
          
          jQuery("#watchlist_empty").show();
          jQuery("#watchlist_content").hide();
          jQuery("#ajax_loader_watchlist").hide();
      }
      }
  });
  
}


// Keep focus on select2
jQuery('select').on(
  'select2:close',
  function () {
    jQuery(this).focus();
  }
);


// Add coin
jQuery("#watchlist_add_coin").click(function() {
  watchlistAddCoin();
});


function watchlistAddCoin() {
  var coin_id = jQuery("#watchlist_dropdown").val();

  for (var i in jqueryarray) {
      if (jqueryarray.hasOwnProperty(i)) {
          if (jqueryarray[i]['id'] == coin_id) {
              var website_slug = jqueryarray[i]['slug'];
              break;
          }
      }
  }

  if (watchlist.length > 0) {
      if (subs == 1 || watchlist.length < 5) {
          var id = watchlist.length + 1;
          var found = watchlist.some(function (i) {
              return i.coin_id === coin_id;
          });
          if (!found) { watchlist.push({"coin_id" : coin_id, "note": "", "slug" : website_slug}); }    
      }
      else {
          jQuery('#watchlist-message').show();
      }
  }
  else {
      if (subs == 1 || watchlist.length < 5) {
          watchlist.push({"coin_id" : coin_id, "note": "", "slug" : website_slug});
      }
      else {
          jQuery('#watchlist-message').show();
      }
  }

  watchlist_save_2();
  load_watchlist(watchlist);
}


// Remove coin
jQuery("#watchlist_remove_coin").click(function() {
  watchlistRemoveCoin();
});


// REMOVE COIN

function watchlistRemoveCoin() {
  var coin_id = jQuery("#watchlist_dropdown").val();

  for (var i = 0; i < watchlist.length; i++) {
          if (watchlist[i]["coin_id"] === coin_id) {
              watchlist.splice(i,1);
              break;
          }
  }

  if (watchlist.length < 1){
      watchlist = [];

      var data="&data="+JSON.stringify(watchlist);

      jQuery.ajax({
          type:"POST",
          url: ajax_url,
          data: 'action=update_watchlist'+data+security_url
      });
  };

  watchlist_save_2();
  load_watchlist(watchlist);	
}



// LOAD Watchlist

function load_watchlist(json) {
    if (watchlist.length < 1) {
        jQuery("#watchlist_empty").show();
        jQuery("#watchlist_content").hide();
        jQuery("#ajax_loader_watchlist").hide();
    }
    else {
        jQuery("#watchlist_empty").hide();
        jQuery("#watchlist_content").empty();
        jQuery("#watchlist_content").show();
        jQuery("#ajax_loader_watchlist").hide();

        function build_table(json) {

            var cols = Object.keys(json[0]);
            var headerRow = '';
            var bodyRows = '';

            headerRow += '<select onchange="selectcurrencyw(1)" id="selectcurrency1w" class="select-css"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="AUD">AUD</option><option value="CAD">CAD</option><option value="BRL">BRL</option><option value="MXN">MXN</option><option value="JPY">JPY</option><option value="SGD">SGD</option></select>'

            headerRow += '<div class="watchlist-col-labels"><div>Coin</div><div>Notes</div><div>1H</div><div>24H</div><div>Price</div></div>';

            var watchlist2 = [];

            json.map(function(coin, index) {
                // console.log(coin);

                var coin_id = coin["coin_id"];
                var note = coin["note"].replace(/4dW4t/g, "&#13;&#10;");
                var slug = coin["slug"];

                watchlist2.push({"coin_id" : coin_id, "note": note, "slug" : slug});

            });

            watchlist2.map(function(row) {
                bodyRows += '<tr>';
                
                function getCoinName(coin_id) {
                for(var i = 0; i < jqueryarray.length; i++) {
                    var coin = jqueryarray[i];
                    if (coin['id'] == coin_id) {
                        var coin_name = coin['symbol'];
                        return coin_name;
                    }
                }
                }

                function getCoinPrice(coin_id) {
                  for(var i = 0; i < jqueryarray.length; i++) {
                    var coin = jqueryarray[i];
                    if (coin['id'] == coin_id) {
                      var coin_price = coin['price_usd'];

                      if (coin_price < 1 && coin_price > 0.1) {
                        coin_price = parseFloat(coin_price).toFixed(2);
                      }
                      if (coin_price < 0.1 && coin_price > 0.01) {
                        coin_price = parseFloat(coin_price).toFixed(3);
                      } 
                      else if (coin_price < 0.01 && coin_price > 0.001) {
                        coin_price = parseFloat(coin_price).toFixed(4);
                      }
                      else if (coin_price < 0.001 && coin_price > 0.0001) {
                        coin_price = parseFloat(coin_price).toFixed(5);
                      }
                      else if (coin_price < 0.0001 && coin_price > 0.00001) {
                        coin_price = parseFloat(coin_price).toFixed(6);
                      }
                      else if (coin_price < 0.00001) {
                        coin_price = parseFloat(coin_price).toFixed(7);
                      }
                      else { 
                        coin_price = parseFloat(coin_price).toFixed(2); 
                      }
                      return coin_price;
                    }
                  }
                }
                
                function getCoin24h(coin_id) {
                    for(var i = 0; i < jqueryarray.length; i++) {
                        var coin = jqueryarray[i];
                        if (coin['id'] == coin_id) {
                            var coin_24h = coin['per_24h'].toFixed(2) + '%';
                            if (coin_24h.startsWith('-')) {
                                return ('<span style="color:#d14836;">' + coin_24h + '</span>');
                            }
                            else if (coin_24h == "0.00%") {
                                return "0.00%";
                            }
                            else if (coin_24h == "null%") {
                                return "---";
                            }
                            else {
                                return ('<span style="color:#093;">' + coin_24h + '</span>');
                            }
                        }
                    }
                }

                function getCoin1h(coin_id) {
                    for(var i = 0; i < jqueryarray.length; i++) {
                        var coin = jqueryarray[i];
                        if (coin['id'] == coin_id) {
                            var coin_1h = coin['per_1h'].toFixed(2) + '%';
                            if (coin_1h.startsWith('-')) {
                                return ('<span style="color:#d14836;">' + coin_1h + '</span>');
                            }
                            else if (coin_1h == "0.00%") {
                                return "0.00%";
                            }
                            else if (coin_1h == "null%") {
                                return "---";
                            }
                            else {
                                return ('<span style="color:#093;">' + coin_1h + '</span>');
                            }
                        }
                    }
                }

                bodyRows += '<div class="watchlist-grid"><div width="60" class="watchlistcoinid" id="'+
                row["coin_id"]+
                '"><a target="_blank" style="padding-top: 0px;" class="watchlistcoin" href="https://coinmarketcap.com/currencies/' +
                row["slug"] +
                '"><div style="position:absolute;left:8px;margin-top:-2px;"><img src="'+homePath+'/img/coins/32x32/'+
                row["coin_id"]+
                '.png" width="18"></div><div style="padding-left:30px;width:40px;text-align:left;">'+
                getCoinName(row["coin_id"]) +
                '</div></a></div>';

                bodyRows += '<div title="Notes" style="margin-left:2px;margin-top:-1px;cursor:pointer;" onclick="display_note_w(&#x27;'+row["coin_id"]+'&#x27;)" style="cursor:pointer;"><svg width="20" height="16"><use xlink:href="#portfolio-note"></use></svg></div>';
                            
                bodyRows += '<div class="text-bold" width="68" id="'+row["coin_id"]+'-1h">' + getCoin24h(row["coin_id"]) + '</div>';

                bodyRows += '<div class="text-bold" width="55" id="'+row["coin_id"]+'-24h">' + getCoin1h(row["coin_id"]) + '</div>';

                bodyRows += '<div width="90" class="text-bold" height="35" id="'+row["coin_id"]+'-price">'+
                formatCurrencyDecimalMax(getCoinPrice(row["coin_id"]), selectedCurrency) + 
                '</div></div>';

                // Note
                bodyRows += '<div class="note-tr '+row["coin_id"]+'-note-tr-w" style="border-bottom: 1px dashed grey;">'+
                '</div><div class="note-tr '+row["coin_id"]+'-note-tr-w note-tr">'+
                '<div class="note-div"><textarea id="'+row["coin_id"]+'-note-w" maxlength="800" placeholder="Place for your '+getCoinName(row["coin_id"])+' notes" class="note-textarea blursave_2" rows="4">'+row["note"]+'</textarea>'+
                '</div></div><div style="border-bottom:1px solid #918f7b"></div>';
                
            });

            return headerRow + bodyRows + '<div style="clear:both;height:20px;"></div>';
            
        }
        jQuery('#watchlist_content').html(build_table(watchlist));


        jQuery('.entersave').keydown(function(event){ 
            var keyCode = (event.keyCode ? event.keyCode : event.which);   
            if (keyCode == 13) {
                // jQuery('#watchlist_save').trigger('click');
                watchlist_save();
            }
        });

        jQuery('.blursave_2').focusout(function(){ 
          watchlist_save();
        });

        jQuery('.entersave_2').keydown(function(event){ 
            var keyCode = (event.keyCode ? event.keyCode : event.which);   
            if (keyCode == 13) {
                // jQuery('#watchlist_save').trigger('click');
                watchlist_save_2();
            }
        });

        jQuery('.entersave_2').focusout(function(){ 
            watchlist_save_2();
        });

        jQuery('.blursave').focusout(function(){ 
            watchlist_save_2();
        });

    }
}


function watchlist_save(){

    watchlist.forEach(function(coin) {
        coin.note = jQuery("#"+coin.coin_id + "-note-w").val().replace(/\n/g, "4dW4t");
    });

    //
    // Ajax to update db
    //
    var data="&data="+JSON.stringify(watchlist);

    jQuery.ajax({
        type:"POST",
        url: ajax_url,
        data: 'action=update_watchlist'+data+security_url
    }); 

    window.watchlist = watchlist;

    load_watchlist();
}

function watchlist_save_2(first){

    if (first) {
      watchlist.forEach(function(coin) {
        coin.note = jQuery("#"+coin.coin_id + "-note-w").val().replace(/\n/g, "4dW4t");
      });
    }

    //
    // Ajax to update db
    //
    var data="&data="+JSON.stringify(watchlist);

    jQuery.ajax({
        type:"POST",
        url: ajax_url,
        data: 'action=update_watchlist'+data+security_url
    }); 

    window.watchlist = watchlist;

}


function display_note_w(id) {
    if(jQuery("."+id+"-note-tr-w").is(":hidden")) {
        jQuery("."+id+"-note-tr-w").show();
    }
    else {
        jQuery("."+id+"-note-tr-w").hide();

    }
}


function selectcurrencyw(id) {
    if(id == 1) {
        newSelectedCurrency = jQuery("#selectcurrency1w option:selected").val();
        jQuery("#selectcurrency2w").val(newSelectedCurrency);
    }
    else if (id == 2) {
        newSelectedCurrency = jQuery("#selectcurrency2w option:selected").val();
        jQuery("#selectcurrency1w").val(newSelectedCurrency);
    }

    selectedCurrency = jQuery("#selectcurrency"+id+"w option:selected").val();
    selectedCurrencyLower = selectedCurrency.toLowerCase();

    if (selectedCurrency == "percent") {
        function change_to_percent() {
            var total_value = parseFloat((jQuery("#totalValue").html()).replace(/[^\d.-]/g, ''));
            // console.log(total_value);

            watchlist.forEach(function(coin) {
                // calculate value
                var coin_value = parseFloat((jQuery("#" + coin.coin_id + "-value").text()).replace(/,/g, ''));
                // console.log(coin_value);

                function calculatePercent() {
                  var coin_percent = ((coin_value / total_value) * 100).toFixed(2) + '%';
                  return coin_percent;
                }

                var coin_percent = calculatePercent();
                jQuery("#" + coin.coin_id + "-value").html(coin_percent);

            });
        }
        change_to_percent();	
        jQuery("#totalValue").text("100");
    }
    else {
        function price() {
            watchlist.forEach(function(coin) {

                for(var i = 0; i < jqueryarray.length; i++) {
                    var arraycoin = jqueryarray[i];
                    if (arraycoin['id'] == coin.coin_id) {
                      
                        // get price and value
                        coin_price = arraycoin['price_'+selectedCurrencyLower]
                        coin_value = coin_price * coin.amount;


                        // show ETH and BTC price in their own cur as 1.00
                        if (coin.slug == "ethereum" && selectedCurrency == "ETH" || coin.slug == "bitcoin" && selectedCurrency == "BTC") {
                          jQuery("#" + coin.coin_id + "-price").html(Number(coin_price).toFixed(2));

                          if (selectedCurrency == "ETH") {
                            jQuery("#" + coin.coin_id + "-value").html(Number(coin_value).toFixed(5));
                          }
                          else if (selectedCurrency == "BTC") {
                            jQuery("#" + coin.coin_id + "-value").html(Number(coin_value).toFixed(6));
                          }

                          return;
                        }

                        if (selectedCurrency != "BTC" && selectedCurrency != "ETH") {

                          // coin_price = parseFloat(coin_price).toFixed(2);

                          if (coin_price < 1 && coin_price > 0.1) {
                            coin_price = parseFloat(coin_price).toFixed(2);
                          }
                          if (coin_price < 0.1 && coin_price > 0.01) {
                            coin_price = parseFloat(coin_price).toFixed(3);
                          } 
                          else if (coin_price < 0.01 && coin_price > 0.001) {
                            coin_price = parseFloat(coin_price).toFixed(4);
                          }
                          else if (coin_price < 0.001 && coin_price > 0.0001) {
                            coin_price = parseFloat(coin_price).toFixed(5);
                          }
                          else if (coin_price < 0.0001 && coin_price > 0.00001) {
                            coin_price = parseFloat(coin_price).toFixed(6);
                          }
                          else if (coin_price < 0.00001) {
                            coin_price = parseFloat(coin_price).toFixed(7);
                          }
                          else { 
                            coin_price = parseFloat(coin_price).toFixed(2); 
                          }

                          jQuery("#" + coin.coin_id + "-price").html(coin_price);

                        }
                        else if (selectedCurrency == "BTC") {
                          jQuery("#" + coin.coin_id + "-price").html(coin_price.toFixed(8));
                        }
                        else if (selectedCurrency == "ETH") {
                          jQuery("#" + coin.coin_id + "-price").html(coin_price.toFixed(7));
                        }


                        if (selectedCurrency == "ETH") {
                          jQuery("#" + coin.coin_id + "-value").html(Number(coin_value).toFixed(5));
                        }
                        else if (selectedCurrency == "BTC") {
                          jQuery("#" + coin.coin_id + "-value").html(Number(coin_value).toFixed(6));
                        }
                        else {
                          jQuery("#" + coin.coin_id + "-value").html(formatCurrencyDecimal(coin_value, selectedCurrency));
                        }

                    }
                }

            });
        }
        price();

        var totalValue = 0;				
        jQuery( ".coinvalue" ).each(function() {
            number = parseFloat(jQuery(this).text().replace(/,/g, ''));
            totalValue += number;
        });
        if (selectedCurrency == "USD" || selectedCurrency == "EUR"  || selectedCurrency == "GBP" || selectedCurrency == "AUD" || selectedCurrency == "CAD" || selectedCurrency == "BRL" || selectedCurrency == "MXN" || selectedCurrency == "SGD" || selectedCurrency == "JPY") {
            // totalValue = Number(totalValue).toFixed(2);

            totalValue = formatCurrencyDecimalMax(totalValue, selectedCurrency);

        }

        
        else {
            totalValue = Number(totalValue).toFixed(6);
        }
        jQuery("#totalValue").html(totalValue);
    }

}

selectedCurrency = 'USD';

function formatCurrencyDecimalMax(totalValue, selectedCurrency) {
  if (selectedCurrency == 'BTC') {
    return Number(totalValue).toFixed(8);
  }
  else if (selectedCurrency == 'ETH') {
    return Number(totalValue).toFixed(7);
  }

  var formatter = new Intl.NumberFormat('en-US', {
    style: 'decimal',
    currency: selectedCurrency,
    minimumFractionDigits: 2,
    maximumFractionDigits: 6,

    // minimumSignificantDigits: 3,
    // maximumSignificantDigits: 6
  })
  return formatter.format(Number(totalValue));
}

function formatCurrencyDecimal(totalValue, selectedCurrency) {
  var formatter = new Intl.NumberFormat('en-US', {
    style: 'decimal',
    currency: selectedCurrency,
    minimumFractionDigits: 1,
    maximumFractionDigits: 2
  })
  return formatter.format(Number(totalValue));
}
