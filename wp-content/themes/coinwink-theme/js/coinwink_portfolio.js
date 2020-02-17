function openPortfolio() {
    jQuery("#portfolio_content").hide();
    jQuery("#ajax_loader_portfolio").show();

    jQuery.ajax({
        type:"POST",
        url: ajax_url,
        data: 'action=get_portfolio'+security_url,
        success:function(data){
        var portfolio = data;
        
        if (portfolio) {
            portfolio = portfolio.replace(/\\/g, "");
            portfolio = JSON.parse( portfolio );
            window.portfolio = portfolio;
            load_portfolio(portfolio);
        }
        else {
            var portfolio = [];
            window.portfolio = portfolio;
            
            jQuery("#portfolio_empty").show();
            jQuery("#portfolio_content").hide();
            jQuery("#ajax_loader_portfolio").hide();
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
jQuery("#portfolio_add_coin").click(function() {
  cwAddCoin();
});


function cwAddCoin() {
  var coin_id = jQuery("#portfolio_dropdown").val();

  for (var i in jqueryarray) {
      if (jqueryarray.hasOwnProperty(i)) {
          if (jqueryarray[i]['id'] == coin_id) {
              var website_slug = jqueryarray[i]['slug'];
              break;
          }
      }
  }

  if (portfolio.length > 0) {
      if (subs == 1 || portfolio.length < 5) {
          var id = portfolio.length + 1;
          var found = portfolio.some(function (i) {
              return i.coin_id === coin_id;
          });
          if (!found) { portfolio.push({"coin_id" : coin_id, "amount": "0", "invested": "0", "invested_c": "USD", "note": "", "slug" : website_slug}); }    
      }
      else {
          jQuery('#portfolio-message').show();
      }
  }
  else {
      if (subs == 1 || portfolio.length < 5) {
          portfolio.push({"coin_id" : coin_id, "amount": "0", "invested": "0", "invested_c": "USD", "note": "", "slug" : website_slug});
      }
      else {
          jQuery('#portfolio-message').show();
      }
  }

  portfolio_save_2();
  load_portfolio(portfolio);
}


// Add coin
jQuery("#portfolio_remove_coin").click(function() {
  cwRemoveCoin();
});


// REMOVE COIN

function cwRemoveCoin() {
  var coin_id = jQuery("#portfolio_dropdown").val();

  for (var i = 0; i < portfolio.length; i++) {
          if (portfolio[i]["coin_id"] === coin_id) {
              portfolio.splice(i,1);
              break;
          }
  }

  if (portfolio.length < 1){
      portfolio = [];

      var data="&data="+JSON.stringify(portfolio);

      jQuery.ajax({
          type:"POST",
          url: ajax_url,
          data: 'action=update_portfolio'+data+security_url
      });
  };

  portfolio_save_2();
  load_portfolio(portfolio);	
}



// LOAD PORTFOLIO

function load_portfolio(json) {
    if (portfolio.length < 1) {
        jQuery("#portfolio_empty").show();
        jQuery("#portfolio_content").hide();
        jQuery("#ajax_loader_portfolio").hide();
    }
    else {
        jQuery("#portfolio_empty").hide();
        jQuery("#portfolio_content").empty();
        jQuery("#portfolio_content").show();
        jQuery("#ajax_loader_portfolio").hide();

        function build_table(json) {

            var cols = Object.keys(json[0]);
            var headerRow = '';
            var bodyRows = '';

            headerRow += '<select onchange="selectcurrency(1)" id="selectcurrency1" class="select-css"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="AUD">AUD</option><option value="CAD">CAD</option><option value="BRL">BRL</option><option value="MXN">MXN</option><option value="JPY">JPY</option><option value="SGD">SGD</option><option value="percent">%</option></select>'

            headerRow += '<th width="40">Coin</th><th width="70">Price</th><th width="50">24H</th><th width="70">Amount</th><th  width="70">Value</th>';

            var portfolio2 = [];

            json.map(function(coin, index) {
                // console.log(coin);

                function calculateValue(coin_id, amount) {
                    for(var i = 0; i < jqueryarray.length; i++) {
                    var coin = jqueryarray[i];
                    if (coin['id'] == coin_id) {
                        var coin_value = coin['price_usd'] * amount;
                        coin_value = Number(coin_value).toFixed(2);
                        return coin_value;
                    }
                }
                }

                var coin_value = calculateValue(coin["coin_id"], coin["amount"]);
                var coin_id = coin["coin_id"];
                var coin_amount = coin["amount"];
                var invested = coin["invested"];
                var invested_c = coin["invested_c"];
                var note = coin["note"].replace(/4dW4t/g, "&#13;&#10;");
                var slug = coin["slug"];

                if (coin_value) {
                    portfolio2.push({"coin_id" : coin_id, "amount": coin_amount, "value": coin_value, "invested": invested, "invested_c": invested_c, "note": note, "slug" : slug});
                }
                else {
                    portfolio.splice(index, 1);
                }

            });

            // console.log(portfolio2);

            var portfolioByValue = portfolio2.slice(0);
            portfolioByValue.sort(function(a,b) {
                return b.value - a.value;
            });
            // console.log(portfolioByValue);
            
            json = portfolioByValue;

            json.map(function(row) {
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

                function invested_c(currency) {
                    if (row["invested_c"] == "USD") {
                        return '<select class="invested_c" onchange="portfolio_save()" id="'+row["coin_id"]+'-invested_c"><option value="USD" selected>USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option></select>'
                    }
                    if (row["invested_c"] == "BTC") {
                        return '<select class="invested_c" onchange="portfolio_save()" id="'+row["coin_id"]+'-invested_c"><option value="USD">USD</option><option value="BTC" selected>BTC</option><option value="ETH">ETH</option></select>'
                    }
                    if (row["invested_c"] == "ETH") {
                        return '<select class="invested_c" onchange="portfolio_save()" id="'+row["coin_id"]+'-invested_c"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH" selected>ETH</option></select>'
                    }
                }

                function profit(coin_id) {

                    var invested_c = row["invested_c"];

                    if (row["invested"] == 0) {
                        return("0.00");
                    }
                    else {
                        for(var i = 0; i < jqueryarray.length; i++) {
                            var coin = jqueryarray[i];

                            var invested_c_l = invested_c.toLowerCase();

                            if (coin['id'] == coin_id) {
                                // get 1 coin price in invested currency
                                var coin_price = coin['price_'+invested_c_l];
                                // get total price for all coins in invested currency
                                var total_price = coin_price * row["amount"];
                                // get total investment in invested currency
                                var total_invested = row["invested"];
                                // profit = current total price minus total investment in invested currency
                                var profit = (total_price - total_invested);

                                for(var i = 0; i < jqueryarray.length; i++) {
                                    var coin = jqueryarray[i];
                                    if (coin['symbol'] == invested_c) {
                                        invested_c_price_usd = coin['price_usd'];
                                        break;
                                    }
                                }
                                
                                if (invested_c != "USD") {
                                    var profit = profit * invested_c_price_usd;
                                }

                                // convert to string
                                var profit = profit.toFixed(2).toString();

                                // // cut string
                                // if (profit.length > 7) { profit = profit.substring(0, 7) }
                                // if (profit.slice(-1) == ".") { profit = profit.slice(0, -1)}

                                // return(profit+"%");
                                return profit;
                            }
                        }
                    }
                }
                
                bodyRows += '<td rowspan="2" width="45" class="portfoliocoinid" id="'+
                row["coin_id"]+
                '"><a target="_blank" style="padding-top: 0px;" class="portfoliocoin" href="https://coinmarketcap.com/currencies/' +
                row["slug"] +
                '"><img src="'+homePath+'/img/coins/32x32/'+
                row["coin_id"]+
                '.png" width="25" style="padding-top:2px"><br>'+
                getCoinName(row["coin_id"]) +
                '</a></td>';

                bodyRows += '<td width="70" height="35" id="'+row["coin_id"]+'-price"><b>'+
                formatCurrencyDecimalMax(getCoinPrice(row["coin_id"]), selectedCurrency) + 
                '</b></td>';

                bodyRows += '<td class="coin24" width="50" id="'+row["coin_id"]+'-24h"><b>' + getCoin24h(row["coin_id"]) + '</b></td>';

                bodyRows += '<td width="70""><input value="'+
                row["amount"]+
                '" maxlength="99" class="portfoliocoinamount entersave blursave_2" id="'+row["coin_id"]+'-amount" name="" type="text" required></td>';

                bodyRows += '<td class="coinvalue" width="70" id="'+row["coin_id"]+'-value"><b>' + formatCurrencyDecimal(row["value"], selectedCurrency) + '</b></td></tr>';

                bodyRows += '<tr><td colspan="4" style="padding-bottom:0px;"><div class="portfolio-tools"><div class="note-img" style="margin-left:10px;"><div title="Notes" onclick="display_note(&#x27;'+row["coin_id"]+'&#x27;)" style="cursor:pointer;"><svg width="20" height="16"><use xlink:href="#portfolio-note"></use></svg></div></div><div class="note-img" title="ROI" style="cursor:pointer;" onclick="display_profit(&#x27;'+row["coin_id"]+'&#x27;)"><svg width="20" height="16"><use xlink:href="#portfolio-profitloss"></use></svg></div></div></div></td></tr>';

                // Profit loss
                bodyRows += '<tr class="note-tr '+row["coin_id"]+'-profit-tr" style="border-bottom: 1px dashed grey;"><td colspan="100%"></td></tr><tr class="note-tr '+
                row["coin_id"]+'-profit-tr note-tr"><td colspan="100%"><div class="profit"><div class="total-div">Total investment<br><input value="'+
                row["invested"]+
                '" maxlength="99" class="invested entersave" onchange="portfolio_save()" id="'+row["coin_id"]+'-invested"' +
                ' name="" type="number">' +
                invested_c(row["invested_c"]) +
                '</div><div class="profit-div" id="'+row["coin_id"]+'-profit">Profit/Loss<br><span id="'+row["coin_id"]+'-profit-span" style="margin-right:1px;">'+profit(row["coin_id"])+
                '</span>&nbsp;<select id="changeProfitCurrency-'+row["coin_id"]+'" class="invested_c" onchange="changeProfitCurrency(&#x27;'+row["coin_id"]+'&#x27;)"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option><option value="percent">%</option></select></div></td></tr>';

                // Note
                bodyRows += '<tr class="note-tr '+row["coin_id"]+'-note-tr" style="border-bottom: 1px dashed grey;">'+
                '<td colspan="100%"></td></tr><tr class="note-tr '+row["coin_id"]+'-note-tr note-tr"><td colspan="100%">'+
                // '<div class="note-save-div"><img src="https://coinwink.com/img/save.png" width="17" onclick="portfolio_save_2()" style="cursor:pointer;" title="Save"></div>'+
                '<div class="note-div"><textarea id="'+row["coin_id"]+'-note" maxlength="800" placeholder="Place for your '+getCoinName(row["coin_id"])+' notes" class="note-textarea blursave_2" rows="4">'+row["note"]+'</textarea></div>'+
                '</td></tr><tr style="border-bottom:1px solid #918f7b"><td colspan="100%"></td></tr>';
                
            });

            return '<table class="portfoliotable"><thead><tr>' +
            headerRow +
            '</tr></thead><tbody>' +
            bodyRows +
            '</tbody></table>Total value: <b><span id="totalValue"></span></b>&nbsp;&nbsp;<select style="padding:2px;padding-left:0px;font-size:12px;" onchange="selectcurrency(2)" id="selectcurrency2" class="selectcurrency-bottom"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="AUD">AUD</option><option value="CAD">CAD</option><option value="BRL">BRL</option><option value="MXN">MXN</option><option value="JPY">JPY</option><option value="SGD">SGD</option><option value="percent">%</option></select><br>__________________________________________<div style="height:20px;"></div>';
            
        }
        jQuery('#portfolio_content').html(build_table(portfolio));


        jQuery('.entersave').keydown(function(event){ 
            var keyCode = (event.keyCode ? event.keyCode : event.which);   
            if (keyCode == 13) {
                // jQuery('#portfolio_save').trigger('click');
                portfolio_save();
            }
        });

        jQuery('.blursave_2').focusout(function(){ 
          portfolio_save();
        });

        jQuery('.entersave_2').keydown(function(event){ 
            var keyCode = (event.keyCode ? event.keyCode : event.which);   
            if (keyCode == 13) {
                // jQuery('#portfolio_save').trigger('click');
                portfolio_save_2();
            }
        });

        jQuery('.entersave_2').focusout(function(){ 
            portfolio_save_2();
        });

        jQuery('.blursave').focusout(function(){ 
            portfolio_save_2();
        });

        
        var totalValue = 0;

        jQuery( ".coinvalue" ).each(function() {
            number = parseFloat(jQuery(this).text().replace(/,/g, ''));
            totalValue += number;
        });

        // totalValue = Number(totalValue).toFixed(2);

        totalValue = formatCurrencyDecimalMax(totalValue, selectedCurrency);
        jQuery("#totalValue").html(totalValue);

    }
}


function portfolio_save(){

    portfolio.forEach(function(coin) {
        coin.amount = jQuery("#"+coin.coin_id + "-amount").val().replace(/,/g, '.');
        coin.invested = jQuery("#"+coin.coin_id + "-invested").val();
        coin.invested_c = jQuery("#"+coin.coin_id + "-invested_c").val();
        coin.note = jQuery("#"+coin.coin_id + "-note").val().replace(/\n/g, "4dW4t");
    });

    //
    // Ajax to update db
    //
    var data="&data="+JSON.stringify(portfolio);

    jQuery.ajax({
        type:"POST",
        url: ajax_url,
        data: 'action=update_portfolio'+data+security_url
    }); 

    window.portfolio = portfolio;

    load_portfolio();
}


function portfolio_save_2(first) {

    if (first) {
      portfolio.forEach(function(coin) {
        coin.amount = jQuery("#"+coin.coin_id + "-amount").val().replace(/,/g, '.');
        coin.invested = jQuery("#"+coin.coin_id + "-invested").val();
        coin.invested_c = jQuery("#"+coin.coin_id + "-invested_c").val();
        coin.note = jQuery("#"+coin.coin_id + "-note").val().replace(/\n/g, "4dW4t");
      });
    }


    //
    // Ajax to update db
    //
    var data="&data="+JSON.stringify(portfolio);

    jQuery.ajax({
        type:"POST",
        url: ajax_url,
        data: 'action=update_portfolio'+data+security_url
    }); 

    window.portfolio = portfolio;

}


function display_note(id) {
    if(jQuery("."+id+"-note-tr").is(":hidden")) {
        jQuery("."+id+"-note-tr").show();
    }
    else {
        jQuery("."+id+"-note-tr").hide();

    }
}


function display_profit(id) {
    if(jQuery("."+id+"-profit-tr").is(":hidden")) {
        jQuery("."+id+"-profit-tr").show();
    }
    else {
        jQuery("."+id+"-profit-tr").hide();
        portfolio_save_2();
    }
}


function changeProfitCurrency(id) {

    selected_c = jQuery("#changeProfitCurrency-"+id+" option:selected").val();
    selected_c_l = selected_c.toLowerCase();

    var invested_c = jQuery("#"+id+"-invested_c").val();
    var invested_c_l = invested_c.toLowerCase();
    invested = jQuery("#"+id+"-invested").val();
    amount = jQuery("#"+id+"-amount").val();

    if (invested == "0") {
        return;
    }

    if (selected_c == "percent") {
        // Calculate PERCENT profit
        for(var i = 0; i < jqueryarray.length; i++) {
            var arraycoin = jqueryarray[i];
            if (arraycoin['id'] == id) {

                net_profit = amount * arraycoin["price_"+invested_c_l];

                // console.log(amount, invested, net_profit);
                profit = (net_profit / invested * 100);
                profit = profit - 100;
                profit = profit.toFixed(2) + '%';
                if (profit == "Infinity%") {
                    profit = "0";
                }
                jQuery("#" + id + "-profit-span").text(profit);
            }
        }
    }
    else {
        // Get profit in usd and convert in selected currency
        for(var i = 0; i < jqueryarray.length; i++) {
            var arraycoin = jqueryarray[i];

            if (arraycoin['id'] == id) {

                // get 1 coin price in invested currency
                var coin_price = arraycoin['price_'+invested_c_l];
                // get total price for all coins in invested currency
                var total_price = coin_price * amount;
                // get total investment in invested currency
                var total_invested = invested;
                // profit in invested currency
                var profit = (total_price - total_invested);  // ETH BTC USD

                // console.log(invested_c_l, coin_price, total_price, total_invested, profit)

                // convert profit from invested currency to usd, unless it is already in usd
                if (invested_c_l != "usd") {
                    for(var i = 0; i < jqueryarray.length; i++) {
                        var arraycoin = jqueryarray[i];
                        if (arraycoin['symbol'] === invested_c) {
                            invested_c_price_usd = arraycoin['price_usd'];
                        }
                    }
                    var profit = profit * invested_c_price_usd;
                    var profit = profit.toFixed(2).toString();
                }
                else {
                    var profit = profit.toFixed(2).toString();
                }

                // convert profit to selected currency
                if (selected_c == "BTC") {
                    var profit = profit / jqueryarray[0]["price_usd"];
                    var profit = profit.toFixed(6).toString();
                }
                if (selected_c == "ETH") {
                    var profit = profit / jqueryarray[1]["price_usd"];
                    var profit = profit.toFixed(5).toString();
                }

                jQuery("#" + id + "-profit-span").html(profit);

            }
        }
    }
     
}


function selectcurrency(id) {
    if(id == 1) {
        newSelectedCurrency = jQuery("#selectcurrency1 option:selected").val();
        jQuery("#selectcurrency2").val(newSelectedCurrency);
    }
    else if (id == 2) {
        newSelectedCurrency = jQuery("#selectcurrency2 option:selected").val();
        jQuery("#selectcurrency1").val(newSelectedCurrency);
    }

    selectedCurrency = jQuery("#selectcurrency"+id+" option:selected").val();
    selectedCurrencyLower = selectedCurrency.toLowerCase();

    if (selectedCurrency == "percent") {
        function change_to_percent() {
            var total_value = parseFloat((jQuery("#totalValue").html()).replace(/[^\d.-]/g, ''));
            // console.log(total_value);

            portfolio.forEach(function(coin) {
                // calculate value
                var coin_value = parseFloat((jQuery("#" + coin.coin_id + "-value").text()).replace(/,/g, ''));
                // console.log(coin_value);

                function calculatePercent() {
                  var coin_percent = ((coin_value / total_value) * 100).toFixed(2) + '%';
                  return coin_percent;
                }

                var coin_percent = calculatePercent();
                jQuery("#" + coin.coin_id + "-value").html('<b>'+coin_percent+'</b>');

            });
        }
        change_to_percent();	
        jQuery("#totalValue").text("100");
    }
    else {
        function price() {
            portfolio.forEach(function(coin) {

                for(var i = 0; i < jqueryarray.length; i++) {
                    var arraycoin = jqueryarray[i];
                    if (arraycoin['id'] == coin.coin_id) {
                      
                        // get price and value
                        coin_price = arraycoin['price_'+selectedCurrencyLower]
                        coin_value = coin_price * coin.amount;

                        
                        // show ETH and BTC price in their own cur as 1.00
                        if (coin.slug == "ethereum" && selectedCurrency == "ETH" || coin.slug == "bitcoin" && selectedCurrency == "BTC") {
                          jQuery("#" + coin.coin_id + "-price").html("<b>"+Number(coin_price).toFixed(2)+"</b>");

                          if (selectedCurrency == "ETH") {
                            jQuery("#" + coin.coin_id + "-value").html("<b>"+Number(coin_value).toFixed(5)+"</b>");
                          }
                          else if (selectedCurrency == "BTC") {
                            jQuery("#" + coin.coin_id + "-value").html("<b>"+Number(coin_value).toFixed(6)+"</b>");
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

                          jQuery("#" + coin.coin_id + "-price").html("<b>"+coin_price+"</b>");

                        }
                        else if (selectedCurrency == "BTC") {
                          jQuery("#" + coin.coin_id + "-price").html("<b>"+coin_price.toFixed(8)+"</b>");
                        }
                        else if (selectedCurrency == "ETH") {
                          jQuery("#" + coin.coin_id + "-price").html("<b>"+coin_price.toFixed(7)+"</b>");
                        }


                        if (selectedCurrency == "ETH") {
                          jQuery("#" + coin.coin_id + "-value").html("<b>"+Number(coin_value).toFixed(5)+"</b>");
                        }
                        else if (selectedCurrency == "BTC") {
                          jQuery("#" + coin.coin_id + "-value").html("<b>"+Number(coin_value).toFixed(6)+"</b>");
                        }
                        else {
                          jQuery("#" + coin.coin_id + "-value").html("<b>"+formatCurrencyDecimal(coin_value, selectedCurrency)+"</b>");
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
