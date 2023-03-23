

function watchlistTypeChange() {
  if (conf_w == 'price') {
    conf_w = 'vol'
    jQuery('#watchlist-span').html('Volume')
  }
  else if (conf_w == 'vol') {
    conf_w = 'cap'
    jQuery('#watchlist-span').html('Market cap')
  }
  else if (conf_w == 'cap') {
    conf_w = 'price'
    jQuery('#watchlist-span').html('Price')
  }
  selectcurrencyw();

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
      var bodyRows = '<div class="html5-sortable">';

      headerRow += '<select onchange="selectcurrencyw(1)" id="selectcurrency1w" class="select-css" style="right:17px;"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="AUD">AUD</option><option value="CAD">CAD</option><option value="BRL">BRL</option><option value="MXN">MXN</option><option value="JPY">JPY</option><option value="SGD">SGD</option></select>'

      var wLabel = null;
      if (conf_w == 'price') {
        wLabel = 'Price';
      }
      else if (conf_w == 'vol') {
        wLabel = 'Volume';
      }
      else if (conf_w == 'cap') {
        wLabel = 'Market Cap';
      }

      headerRow += '<div class="watchlist-col-labels"><div>Coin</div><div>Notes</div><div>24H</div><div>7d</div><div onclick="watchlistTypeChange()" id="watchlist-type-click"><span id="watchlist-span" class="span-click noselect">'+wLabel+'</span></div></div>';

      var watchlistNew = [];

      json.map(function (coin, index) {
        // console.log(coin);

        var coin_id = coin["coin_id"];
        var note = coin["note"].replace(/4dW4t/g, "&#13;&#10;");
        var slug = coin["slug"];

        watchlistNew.push({ "coin_id": coin_id, "note": note, "slug": slug });

      });

      function getCoinName(coin_id) {
        for (var i = 0; i < cw_cmc.length; i++) {
          var coin = cw_cmc[i];
          if (coin['id'] == coin_id) {
            var coin_name = coin['symbol'];
            return coin_name;
          }
        }
      }

      function getCoinPrice(coin_id) {
        for (var i = 0; i < cw_cmc.length; i++) {
          var coin = cw_cmc[i];
          if (coin['id'] == coin_id) {
            var coin_price = coin['price_usd'];

            return coin_price;
          }
        }
      }

      function getCoin24h(coin_id) {
        for (var i = 0; i < cw_cmc.length; i++) {
          var coin = cw_cmc[i];
          if (coin['id'] == coin_id) {
            var coin_24h = coin['per_24h'].toFixed(2) + '%';
            if (coin_24h.startsWith('-')) {
              return ('<span class="pw-minus">' + coin_24h + '</span>');
            }
            else if (coin_24h == "0.00%") {
              return "0.00%";
            }
            else if (coin_24h == "null%") {
              return "---";
            }
            else {
              return ('<span class="pw-plus">' + coin_24h + '</span>');
            }
          }
        }
      }

      function getCoin7d(coin_id) {
        for (var i = 0; i < cw_cmc.length; i++) {
          var coin = cw_cmc[i];
          if (coin['id'] == coin_id) {
            var coin_7d = coin['per_7d'].toFixed(2) + '%';
            if (coin_7d.startsWith('-')) {
              return ('<span class="pw-minus">' + coin_7d + '</span>');
            }
            else if (coin_7d == "0.00%") {
              return "0.00%";
            }
            else if (coin_7d == "null%") {
              return "---";
            }
            else {
              return ('<span class="pw-plus">' + coin_7d + '</span>');
            }
          }
        }
      }

      function getCoin1h(coin_id) {
        for (var i = 0; i < cw_cmc.length; i++) {
          var coin = cw_cmc[i];
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

      // console.log(watchlistNew)

      watchlistNew.map(function (row) {

        var wTempCoinCap = null;
        for (var i = 0; i < cw_cmc.length; i++) {
          var arraycoin = cw_cmc[i];
          if (arraycoin['id'] == row["coin_id"]) {
            wTempCoinCap = arraycoin['cap'];
          }
        }

        // // Automatically remove coin if it's gone from CMC
        // if (wTempCoinCap == null) {
        //   watchlistRemoveCoin(row["coin_id"]);
        //   return;
        // }

        var wTempCoinVol = 0;
        for (var i = 0; i < cw_cmc.length; i++) {
          var arraycoin = cw_cmc[i];
          if (arraycoin['id'] == row["coin_id"]) {
            wTempCoinVol = arraycoin['vol'];
          }
        }


        bodyRows += '<div>';

        var coinSymbol = getCoinName(row["coin_id"]);
        var coinSymbolL = '';
        if (typeof(coinSymbol) != 'undefined') {
          coinSymbolL = coinSymbol.toLowerCase();
        }

        // console.log(coinSymbol, coinSymbolL)

        bodyRows += '<div class="watchlist-grid"><div width="60" class="watchlistcoinid" id="' +
          row["coin_id"] +
          '"><a target="_blank" style="padding-top: 0px;display: grid;grid-template-columns: 28px 1fr;" class="watchlistcoin" href="/'+coinSymbolL+
          '"><div style="margin-top:-2px;text-align: right;"><img src="/img/coins/32x32/' +
          row["coin_id"] +
          '.png" width="18"></div><div style="padding-left:4px;text-align:left;">' +
          coinSymbol +
          '</div></a></div>';

        bodyRows += '<div style="margin-left:2px;margin-top:-1px;" ><svg onclick="display_note_w(&#x27;' + row["coin_id"] + '&#x27;)"  width="20" height="17" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 107.67 107.67"><title>Notes</title><g class="svg-portfolio-icon"><path d="M10.45,3.25H97.22a7.22,7.22,0,0,1,7.2,7.2V97.22a7.22,7.22,0,0,1-7.2,7.2H10.45a7.22,7.22,0,0,1-7.2-7.2V10.45a7.22,7.22,0,0,1,7.2-7.2Z" style="fill:none;stroke-miterlimit:2.613126039505005;stroke-width:6.5001301765441895px"/><path d="M23.84,33.14h60m-60,20.7h60m-60,20.7h31.4" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:6.5001301765441895px"/></g></svg></div>';

        bodyRows += '<div class="text-bold" width="68" id="' + row["coin_id"] + '-1h">' + getCoin24h(row["coin_id"]) + '</div>';

        bodyRows += '<div class="text-bold" width="55" id="' + row["coin_id"] + '-24h">' + getCoin7d(row["coin_id"]) + '</div>';


        if (conf_w == 'price') {
          bodyRows += '<div width="90" class="text-bold" height="35" id="' + row["coin_id"] + '-price">' + formatCurrencyDecimalMax(getCoinPrice(row["coin_id"]), cur_w) + '</div></div>';
        }
        else if (conf_w == 'cap') {
          bodyRows += '<div width="90" class="text-bold" height="35" id="' + row["coin_id"] + '-price">' + formatCurrencyM(wTempCoinCap) + 'M</div></div>';
        }
        else if (conf_w == 'vol') {
          bodyRows += '<div width="90" class="text-bold" height="35" id="' + row["coin_id"] + '-price">' + formatCurrencyM(wTempCoinVol) + 'M</div></div>';
        }


        // Note
        bodyRows += '<div class="note-tr ' + row["coin_id"] + '-note-tr-w" style="border-top: 1px dashed grey;">' +
          '</div><div class="note-tr ' + row["coin_id"] + '-note-tr-w note-tr">' +
          '<div class="note-div"><textarea id="' + row["coin_id"] + '-note-w" maxlength="800" placeholder="Place for your ' + coinSymbol + ' notes" class="note-textarea blursave_3" rows="4">' + row["note"] + '</textarea>' +
          '</div></div><div class="pw-separator"></div>';

          bodyRows += '</div>';
      });

      return headerRow + bodyRows + '</div>';

    }


    // Run the above function
    jQuery('#watchlist_content').html(build_table(watchlist));
    selectcurrencyw();
    
    jQuery('#selectcurrency1w').val(cur_w);

    // Activate html5 sortable
    sortable('.html5-sortable')[0].addEventListener('sortupdate', function(e) {
      console.log(e.detail.origin.index, e.detail.destination.index);

      // 1. re-order the current watchlist array using the above indexes
      function array_move(arr, old_index, new_index) {
        if (new_index >= arr.length) {
          var k = new_index - arr.length + 1;
          while (k--) {
            arr.push(undefined);
          }
        }
        arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
        return arr; // for testing
      };
      array_move(watchlist, e.detail.origin.index, e.detail.destination.index); 

      // 2. save the new array on the backend
      watchlist_save();
    });
    

    // Set up event listeners
    jQuery('.entersave').keydown(function (event) {
      var keyCode = (event.keyCode ? event.keyCode : event.which);
      if (keyCode == 13) {
        // jQuery('#watchlist_save').trigger('click');
        watchlist_save();
      }
    });

    jQuery('.blursave_2').focusout(function () {
      watchlist_save();
    });

    
    jQuery('.blursave_3').focusout(function () {
      watchlist_save_2(true);
    });

    jQuery('.entersave_2').keydown(function (event) {
      var keyCode = (event.keyCode ? event.keyCode : event.which);
      if (keyCode == 13) {
        // jQuery('#watchlist_save').trigger('click');
        watchlist_save_2();
      }
    });

    jQuery('.entersave_2').focusout(function () {
      watchlist_save_2();
    });

    jQuery('.blursave').focusout(function () {
      watchlist_save_2();
    });

  }
}


function watchlist_save() {

  watchlist.forEach(function (coin) {
    coin.note = jQuery("#" + coin.coin_id + "-note-w").val().replace(/\n/g, "4dW4t");
  });

  window.watchlist = watchlist;

  load_watchlist();
}

function watchlist_save_2(first) {

  if (first) {
    watchlist.forEach(function (coin) {
      coin.note = jQuery("#" + coin.coin_id + "-note-w").val().replace(/\n/g, "4dW4t");
    });
  }

  window.watchlist = watchlist;

}


function display_note_w(id) {
  if (jQuery("." + id + "-note-tr-w").is(":hidden")) {
    jQuery("." + id + "-note-tr-w").show();
  }
  else {
    jQuery("." + id + "-note-tr-w").hide();

  }
}


function selectcurrencyw(id) {
  if (typeof(id) != 'undefined') {
    if (id == 1) {
      newcur_w = jQuery("#selectcurrency1w option:selected").val();
      jQuery("#selectcurrency2w").val(newcur_w);
    }
    else if (id == 2) {
      newcur_w = jQuery("#selectcurrency2w option:selected").val();
      jQuery("#selectcurrency1w").val(newcur_w);
    }
    
    cur_w = jQuery("#selectcurrency" + id + "w option:selected").val();
    cur_wLower = cur_w.toLowerCase();
  }
  else {
    cur_wLower = cur_w.toLowerCase();  
  }

  function price() {
    // @todo: test eth flippening (here and in portfolio)
    if (cur_w == "BTC" || cur_w == "ETH") {
      var ethIndex = null;
      var btcIndex = null;
      for(var i = 0; i < cw_cmc.length; i++) {
        var coin = cw_cmc[i];
        if (coin['name'] === 'Bitcoin') {
          btcIndex = i;
        }
        else if (coin['name'] === 'Ethereum') {
          ethIndex = i;
        }
        if (btcIndex != null && ethIndex != null) {
          // console.log(btcIndex, ethIndex)
          break;
        }
      }
    }

    watchlist.forEach(function (coin) {

      for (var i = 0; i < cw_cmc.length; i++) {
        var arraycoin = cw_cmc[i];
        if (arraycoin['id'] == coin.coin_id) {

          if (cur_w != "BTC" && cur_w != "ETH") {

            if (cur_w == "USD") {
              if (conf_w == 'price') {
                coin_price = arraycoin['price_usd'];
                coin_price = formatPrice(coin_price);
              }
              else if (conf_w == 'cap') {
                coin_price = arraycoin['cap'];
                coin_price = formatCurrencyM(coin_price)+'M';
              }
              else if (conf_w == 'vol') {
                coin_price = arraycoin['vol'];
                coin_price = formatCurrencyM(coin_price)+'M';
              }
              jQuery("#" + coin.coin_id + "-price").html(coin_price);
            }
            else {
              if (conf_w == 'price') {
                coin_price = arraycoin['price_usd'] * cw_rates[cur_w.toLowerCase()];
                coin_price = formatPrice(coin_price);
              }
              else if (conf_w == 'cap') {
                coin_price = arraycoin['cap'] * cw_rates[cur_w.toLowerCase()];
                coin_price = formatCurrencyM(coin_price)+'M';
              }
              else if (conf_w == 'vol') {
                coin_price = arraycoin['vol'] * cw_rates[cur_w.toLowerCase()];
                coin_price = formatCurrencyM(coin_price)+'M';
              }
              jQuery("#" + coin.coin_id + "-price").html(coin_price);
            }

          }
          else {
            // get price and value
            coin_price = arraycoin['price_' + cur_wLower]
            coin_value = coin_price * coin.amount;

            // show ETH and BTC price in their own cur as 1.00
            if ((coin.slug == "ethereum" && cur_w == "ETH" || coin.slug == "bitcoin" && cur_w == "BTC") && conf_w == 'price') {
              jQuery("#" + coin.coin_id + "-price").html(Number(coin_price).toFixed(2));
            }
            else {
              if (cur_w == "ETH") {
                var coin_name = 'Ethereum';
              }
              else if (cur_w == "BTC") {
                var coin_name = 'Bitcoin';
              }

              if (conf_w == 'price') {
                coin_price_real = (coin_price.toFixed(8));
                if (!(coin_price > 0.00000001)) {
                  coin_price_real = '<div class="fixed-9">'+coin_price.toFixed(9)+'</div>';
                  if (!(coin_price > 0.000000001)) {
                    coin_price_real = '<div class="fixed-10">'+coin_price.toFixed(10)+'</div>';
                  }
                }
                jQuery("#" + coin.coin_id + "-price").html(coin_price_real);
              }
              else if (conf_w == 'cap') {
                if (coin_name == "Ethereum") {
                  // coin_price = arraycoin['cap'] / cw_cmc[1].price_usd;
                  coin_price = arraycoin['cap'] / cw_cmc[ethIndex].price_usd;
                }
                else if (coin_name == "Bitcoin") {
                  // coin_price = arraycoin['cap'] / cw_cmc[0].price_usd;
                  coin_price = arraycoin['cap'] / cw_cmc[btcIndex].price_usd;
                }
                coin_price = formatCrypto(coin_price, cur_w);
                jQuery("#" + coin.coin_id + "-price").html(coin_price);
              }
              else if (conf_w == 'vol') {
                if (coin_name == "Ethereum") {
                  coin_price = arraycoin['vol'] / cw_cmc[1].price_usd;
                }
                else if (coin_name == "Bitcoin") {
                  coin_price = arraycoin['vol'] / cw_cmc[0].price_usd;
                }
                coin_price = formatCrypto(coin_price, cur_w);
                jQuery("#" + coin.coin_id + "-price").html(coin_price);
              }

            }
          }
        }
      }
    });
  }
  price();

}
