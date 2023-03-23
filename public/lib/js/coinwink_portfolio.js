
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

            headerRow += '<select onchange="selectcurrency(1)" id="selectcurrency1" class="select-css"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option><option value="percent">%</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="AUD">AUD</option><option value="CAD">CAD</option><option value="BRL">BRL</option><option value="MXN">MXN</option><option value="JPY">JPY</option><option value="SGD">SGD</option></select>'

            headerRow += '<div class="grid-portfolio-titles"><div>Coin</div><div>Price</div><div>24H</div><div>Amount</div><div>Value</div>';

            var portfolio2 = [];

            json.map(function(coin, index) {
                // console.log(coin);

                function calculateValue(coin_id, amount) {
                    for(var i = 0; i < cw_cmc.length; i++) {
						var coin = cw_cmc[i];
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
                for(var i = 0; i < cw_cmc.length; i++) {
                    var coin = cw_cmc[i];
                    if (coin['id'] == coin_id) {
                        var coin_name = coin['symbol'];
                        return coin_name;
                    }
                }
                }

                function getCoinPrice(coin_id) {
                  for(var i = 0; i < cw_cmc.length; i++) {
                    var coin = cw_cmc[i];
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
                    for(var i = 0; i < cw_cmc.length; i++) {
                        var coin = cw_cmc[i];
                        if (coin['id'] == coin_id) {
                            if (coin['per_24h'] == null) {
                              return "---";
                            }
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

                function invested_c(currency) {
                    var selected_BTC, selected_ETH, selected_USD, selected_EUR, selected_CAD, selected_AUD, selected_MXN, selected_BRL, selected_JPY, selected_SGD = '';

                    if (row["invested_c"] == "USD") {
                        var selected_USD = 'selected';
                    }
                    if (row["invested_c"] == "BTC") {
                        var selected_BTC = 'selected';
                    }
                    if (row["invested_c"] == "ETH") {
                        var selected_ETH = 'selected';
                    }
                    if (row["invested_c"] == "EUR") {
                        var selected_EUR = 'selected';
                    }
                    if (row["invested_c"] == "GBP") {
                        var selected_GBP = 'selected';
                    }
                    if (row["invested_c"] == "AUD") {
                        var selected_AUD = 'selected';
                    }
                    if (row["invested_c"] == "CAD") {
                        var selected_CAD = 'selected';
                    }
                    if (row["invested_c"] == "BRL") {
                        var selected_BRL = 'selected';
                    }
                    if (row["invested_c"] == "MXN") {
                        var selected_MXN = 'selected';
                    }
                    if (row["invested_c"] == "SGD") {
                        var selected_SGD = 'selected';
                    }
                    if (row["invested_c"] == "JPY") {
                        var selected_JPY = 'selected';
                    }

                    return '<select class="select-css-currency roi" onchange="portfolio_roi(&#x27;'+row["coin_id"]+'&#x27;)" id="'+row["coin_id"]+'-invested_c"><option value="USD" '+selected_USD+'>USD</option><option value="BTC" '+selected_BTC+'>BTC</option><option value="ETH" '+selected_ETH+'>ETH</option><option value="EUR" '+selected_EUR+'>EUR</option><option value="GBP" '+selected_GBP+'>GBP</option><option value="AUD" '+selected_AUD+'>AUD</option><option value="CAD" '+selected_CAD+'>CAD</option><option value="BRL" '+selected_BRL+'>BRL</option><option value="MXN" '+selected_MXN+'>MXN</option><option value="JPY" '+selected_JPY+'>JPY</option><option value="SGD" '+selected_SGD+'>SGD</option></select>'

                }
                
                bodyRows += '<div class="grid-portfolio-structure-outer-0"><div class="grid-portfolio-structure-outer-1"><div class="portfoliocoinid" id="'+
                row["coin_id"]+
                '"><a target="_blank" style="padding-top: 0px;" class="portfoliocoin" href="https://coinmarketcap.com/currencies/' +
                row["slug"] +
                '/"><img src="/img/coins/32x32/'+
                row["coin_id"]+
                '.png" width="25"><br>'+
                getCoinName(row["coin_id"]) +
                '</a></div>';

                bodyRows += '<div class="grid-portfolio-structure-outer-2"><div class="grid-portfolio-structure-inner"><div id="p-'+row["coin_id"]+'-price"><b>'+
                formatCurrencyDecimalMax(getCoinPrice(row["coin_id"]), cur_p) + 
                '</b></div>';

                bodyRows += '<div class="coin24" id="'+row["coin_id"]+'-24h"><b>' + getCoin24h(row["coin_id"]) + '</b></div>';

                bodyRows += '<div class="portfolioinputdiv"><input value="'+
                row["amount"]+
                '" maxlength="99" class="portfoliocoinamount entersave blursave_2" id="'+row["coin_id"]+'-amount" name="" type="text"></div>';

                bodyRows += '<div class="coinvalue" id="'+row["coin_id"]+'-value"><b>' + formatCurrencyDecimal(row["value"], cur_p) + '</b></div></div>';

                bodyRows += '<div><div style="padding-bottom:0px;"><div class="portfolio-tools"><div class="note-img" style="margin-left:6.5px;"><div onclick="display_note(&#x27;'+row["coin_id"]+'&#x27;)"><svg width="20" height="17" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 107.67 107.67"><title>Notes</title><g class="svg-portfolio-icon"><path d="M10.45,3.25H97.22a7.22,7.22,0,0,1,7.2,7.2V97.22a7.22,7.22,0,0,1-7.2,7.2H10.45a7.22,7.22,0,0,1-7.2-7.2V10.45a7.22,7.22,0,0,1,7.2-7.2Z" style="fill:none;stroke-miterlimit:2.613126039505005;stroke-width:6.5001301765441895px"/><path d="M23.84,33.14h60m-60,20.7h60m-60,20.7h31.4" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:6.5001301765441895px"/></g></svg></div></div><div class="note-img"  onclick="display_profit(&#x27;'+row["coin_id"]+'&#x27;)"><svg width="20" height="17" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 125.37 107.67"><title>ROI</title><g class="svg-portfolio-icon"><path d="M94.79,21.57H120.1a2,2,0,0,1,2,2v78.79a2,2,0,0,1-2,2H94.79a2,2,0,0,1-2-2V23.6a2,2,0,0,1,2-2ZM50,3.25H75.34a2,2,0,0,1,2,2v97.11a2,2,0,0,1-2,2H50a2,2,0,0,1-2-2V5.28a2,2,0,0,1,2-2ZM5.28,62h25.3a2,2,0,0,1,2,2v38.38a2,2,0,0,1-2,2H5.28a2,2,0,0,1-2-2V64a2,2,0,0,1,2-2Z" style="fill:none;stroke-linejoin:round;stroke-width:6.5001301765441895px"/></g></svg></div></div></div></div></div></div>';

                // Profit loss
                bodyRows += '<div class="note-tr '+row["coin_id"]+'-profit-tr" style="border-top: 1px dashed grey;"><div class="note-tr '+
                row["coin_id"]+'-profit-tr note-tr" style="width:290px;margin:0 auto;"><div class="profit"><div class="total-div">Total investment<div style="height:4px;"></div><input value="'+
                row["invested"]+
                '" maxlength="99" class="invested" onchange="portfolio_roi(&#x27;'+row["coin_id"]+'&#x27;)" id="'+row["coin_id"]+'-invested"' +
                ' name="" type="number">' +
                invested_c(row["invested_c"]) +
                '</div><div class="profit-div" id="'+row["coin_id"]+'-profit">Profit/Loss<div style="height:4px;"></div><span id="'+row["coin_id"]+'-profit-span" style="margin-right:1px;">'+getProfit(row["coin_id"], row["invested"], row["invested_c"], row["amount"])+
                '</span>&nbsp;<select id="changeProfitCurrency-'+row["coin_id"]+'" class="select-css-currency roi" onchange="changeProfitCurrency(&#x27;'+row["coin_id"]+'&#x27;)"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option><option value="percent">%</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="AUD">AUD</option><option value="CAD">CAD</option><option value="BRL">BRL</option><option value="MXN">MXN</option><option value="JPY">JPY</option><option value="SGD">SGD</option></select></div></div></div></div>';

                // Note
                bodyRows += '<div class="note-tr '+row["coin_id"]+'-note-tr" style="border-top: 1px dashed grey;">'+
                '<div class="note-tr '+row["coin_id"]+'-note-tr note-tr">'+
                '<div class="note-div"><textarea id="'+row["coin_id"]+'-note" maxlength="1000" placeholder="Place for your '+getCoinName(row["coin_id"])+' notes" class="note-textarea blursave_3" rows="4">'+row["note"]+'</textarea></div>'+
                '</div></div><div class="pw-separator"></div>';

                bodyRows += '</div></div>'; // grids end
                
            });

            return '<table class="portfoliotable">' +
            headerRow +
            bodyRows +
            '<div style="height:24px;"></div>Total value: <b><span id="totalValue"></span></b><select class="select-css-currency total-val" onchange="selectcurrency(2)" id="selectcurrency2"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option><option value="percent">%</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="AUD">AUD</option><option value="CAD">CAD</option><option value="BRL">BRL</option><option value="MXN">MXN</option><option value="JPY">JPY</option><option value="SGD">SGD</option></select><div style="height:10px;"></div><div class="pw-line"></div>';
            
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

        jQuery('.blursave_3').focusout(function(){ 
            portfolio_save_2(true);
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
            var number = parseFloat(jQuery(this).text().replace(/,/g, ''));
            totalValue += number;
        });

        // totalValue = Number(totalValue).toFixed(2);

        totalValue = formatCurrencyDecimalMax(totalValue, cur_p);
        jQuery("#totalValue").html(totalValue);

    }
    selectcurrency();
    jQuery('#selectcurrency1').val(cur_p);
    jQuery('#selectcurrency2').val(cur_p);
}


function portfolio_save(){

    portfolio.forEach(function(coin) {
        coin.amount = jQuery("#"+coin.coin_id + "-amount").val().replace(/,/g, '').replace(/\s/g, '');
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
        url: '/api/update_portfolio',
        data: data
    }); 

    window.portfolio = portfolio;

    load_portfolio();
}


function portfolio_save_2(first){

    if (first) {
      portfolio.forEach(function(coin) {
        coin.amount = jQuery("#"+coin.coin_id + "-amount").val().replace(/,/g, '').replace(/\s/g, '');
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
        url: '/api/update_portfolio',
        data: data
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



function getProfit(coin_id, invested, invested_c, amount) {

	if (invested == 0) {
		return("0.00");
	}

	var profit = changeProfitCurrency(coin_id, invested, invested_c, amount);

	return(profit);
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



function portfolio_roi(id){
    // console.log(id);

    portfolio.forEach(function(coin) {
        coin.amount = jQuery("#"+coin.coin_id + "-amount").val().replace(/,/g, '').replace(/\s/g, '');
        coin.invested = jQuery("#"+coin.coin_id + "-invested").val();
        coin.invested_c = jQuery("#"+coin.coin_id + "-invested_c").val();
        coin.note = jQuery("#"+coin.coin_id + "-note").val().replace(/\n/g, "4dW4t");
    });

    // console.log(portfolio);
    // return;

    var data="&data="+JSON.stringify(portfolio);

    jQuery.ajax({
        type:"POST",
        url: '/api/update_portfolio',
        data: data
    }); 

    window.portfolio = portfolio;

    changeProfitCurrency(id);
}


function changeProfitCurrency(id, invested, invested_c, amount) {

	// console.log(id);

	if (typeof(invested) == 'undefined') {
		var initialRoiLoad = false;
		var selected_c = jQuery("#changeProfitCurrency-"+id+" option:selected").val();
	
		var invested_c = jQuery("#"+id+"-invested_c").val();
		var invested = jQuery("#"+id+"-invested").val();
		var amount = jQuery("#"+id+"-amount").val();
	
		if (invested == "0") {
			return;
		}
	}
	else {
		var initialRoiLoad = true;
		var selected_c = 'USD';
	}

	var invested_c_l = invested_c.toLowerCase();
	

    if (selected_c == "percent") {
        // Calculate profit in PERCENTAGE
        for(var i = 0; i < cw_cmc.length; i++) {
            var arraycoin = cw_cmc[i];
            if (arraycoin['id'] == id) {

                if (invested_c == 'USD' || invested_c == 'BTC' || invested_c == 'ETH' ) {
                    var net_profit = amount * arraycoin["price_"+invested_c_l];
                }
                else if (invested_c == 'EUR') {
                    var net_profit = amount * arraycoin["price_usd"] * rates['eur'];
                }
                else if (invested_c == 'GBP') {
                    var net_profit = amount * arraycoin["price_usd"] * rates['gbp'];
                }
                else if (invested_c == 'CAD') {
                    var net_profit = amount * arraycoin["price_usd"] * rates['cad'];
                }
                else if (invested_c == 'AUD') {
                    var net_profit = amount * arraycoin["price_usd"] * rates['aud'];
                }
                else if (invested_c == 'MXN') {
                    var net_profit = amount * arraycoin["price_usd"] * rates['mxn'];
                }
                else if (invested_c == 'BRL') {
                    var net_profit = amount * arraycoin["price_usd"] * rates['brl'];
                }
                else if (invested_c == 'SGD') {
                    var net_profit = amount * arraycoin["price_usd"] * rates['sgd'];
                }
                else if (invested_c == 'JPY') {
                    var net_profit = amount * arraycoin["price_usd"] * rates['jpy'];
                }

                // console.log(amount, invested, net_profit);
                var profit = (net_profit / invested * 100);
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
        // for possible eth flippening, not to hardcode index (here and in watchlist)
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

        // Get profit in usd and convert to selected currency
        for(var i = 0; i < cw_cmc.length; i++) {
            var arraycoin = cw_cmc[i];

            if (arraycoin['id'] == id) {
                var currentValue = amount * arraycoin["price_usd"];
                var investedValue = invested;

                // convert invested value to usd
                if (invested_c == 'BTC') {
                    investedValue = investedValue * cw_cmc[btcIndex]['price_usd'];
                }
                else if (invested_c == 'ETH') {
                    investedValue = investedValue * cw_cmc[ethIndex]['price_usd'];
                }
                else if (invested_c == 'EUR') {
                    investedValue = investedValue / rates['eur'];
                }
                else if (invested_c == 'GBP') {
                    investedValue = investedValue / rates['gbp'];
                }
                else if (invested_c == 'CAD') {
                    investedValue = investedValue / rates['cad'];
                }
                else if (invested_c == 'AUD') {
                    investedValue = investedValue / rates['aud'];
                }
                else if (invested_c == 'MXN') {
                    investedValue = investedValue / rates['mxn'];
                }
                else if (invested_c == 'BRL') {
                    investedValue = investedValue / rates['brl'];
                }
                else if (invested_c == 'SGD') {
                    investedValue = investedValue / rates['sgd'];
                }
                else if (invested_c == 'JPY') {
                    investedValue = investedValue / rates['jpy'];
                }

                var profit = currentValue - investedValue;

                // console.log(profit);
                // return;

                if (selected_c == 'BTC') {
                    profit = profit / cw_cmc[btcIndex]['price_usd'];
                }
                else if ( selected_c == 'ETH' ) {
                    profit = profit / cw_cmc[ethIndex]['price_usd'];
                }
                else if (selected_c == 'EUR') {
                    profit = profit * rates['eur'];
                }
                else if (selected_c == 'GBP') {
                    profit = profit * rates['gbp'];
                }
                else if (selected_c == 'CAD') {
                    profit = profit * rates['cad'];
                }
                else if (selected_c == 'AUD') {
                    profit = profit * rates['aud'];
                }
                else if (selected_c == 'MXN') {
                    profit = profit * rates['mxn'];
                }
                else if (selected_c == 'BRL') {
                    profit = profit * rates['brl'];
                }
                else if (selected_c == 'SGD') {
                    profit = profit * rates['sgd'];
                }
                else if (selected_c == 'JPY') {
                    profit = profit * rates['jpy'];
                }

                // console.log(profit);

                // convert profit to selected currency
                if (selected_c == "BTC") {
                    profit = profit.toFixed(6).toString();
                }
                else if (selected_c == "ETH") {
                    profit = profit.toFixed(6).toString();
                }
                else {
                    profit = formatCurrencyDecimal(profit, 'usd');
                }

				if (!initialRoiLoad) {
					jQuery("#" + id + "-profit-span").html(profit);
				}
				else {
					return(profit);
				}
            
            }
        }
    }    
    
}


function selectcurrency(id) {
    if(typeof(id) != 'undefined') {
        if(id == 1) {
            newcur_p = jQuery("#selectcurrency1 option:selected").val();
            jQuery("#selectcurrency2").val(newcur_p);
        }
        else if (id == 2) {
            newcur_p = jQuery("#selectcurrency2 option:selected").val();
            jQuery("#selectcurrency1").val(newcur_p);
        }
        cur_p = jQuery("#selectcurrency"+id+" option:selected").val();
        cur_pLower = cur_p.toLowerCase();

        jQuery.ajax({
            type: 'POST',
            url: '/api/config_cur_p',
            data: '&cur_p=' + cur_p
        })
    }
    else {
        cur_pLower = cur_p.toLowerCase();
    }

    if (cur_p == "percent") {
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

                for(var i = 0; i < cw_cmc.length; i++) {
                    var arraycoin = cw_cmc[i];
                    if (arraycoin['id'] == coin.coin_id) {
                      
                        // get price and value
                        var coin_price = null;
                        var coin_value = null;

                        if (cur_p != "BTC" && cur_p != "ETH") {

                            if (cur_p == "USD") {
                                coin_price = arraycoin['price_usd'];
                                coin_value = coin_price * coin.amount;
                                coin_price = formatPrice(coin_price);
                            }
                            else {
                                coin_price = arraycoin['price_usd'] * rates[cur_p.toLowerCase()];
                                coin_value = coin_price * coin.amount;
                                coin_price = formatPrice(coin_price);
                            }
                            jQuery("#p-" + coin.coin_id + "-price").html("<b>"+coin_price+"</b>");
                            jQuery("#" + coin.coin_id + "-value").html("<b>"+formatCurrencyDecimal(coin_value, cur_p)+"</b>");
                        }
                        else if (cur_p == "BTC" || cur_p == "ETH") {
                            if (cur_p == "BTC") {
                                coin_price = arraycoin['price_btc'];
                            }
                            else if (cur_p == "ETH") {
                                coin_price = arraycoin['price_eth'];
                            }
                            coin_value = coin_price * coin.amount;

                            var coin_price_real = (coin_price.toFixed(7));

                            if (!(coin_price > 0.0000001)) {
                                coin_price_real = coin_price.toFixed(8)
                                if (!(coin_price > 0.00000001)) {
                                    coin_price_real = '<div class="fixed-9">'+coin_price.toFixed(9)+'</div>';
                                    if (!(coin_price > 0.000000001)) {
                                        coin_price_real = '<div class="fixed-10">'+coin_price.toFixed(10)+'</div>';
                                    }
                                }
                            }
                            // show ETH and BTC price in their own cur as 1.00
                            if (coin.slug == "ethereum" && cur_p == "ETH" || coin.slug == "bitcoin" && cur_p == "BTC") {
                                coin_price = arraycoin['price_'+cur_p.toLowerCase()]
                                jQuery("#p-" + coin.coin_id + "-price").html("<b>"+Number(coin_price).toFixed(2)+"</b>");
                            }
                            else {
                                jQuery("#p-" + coin.coin_id + "-price").html("<b>"+coin_price_real+"</b>");
                            }
                            
                            var coin_value_real = (coin_value.toFixed(6));
                            
                            if (!(coin_value > 0.000001)) {
                                coin_value_real = (coin_value.toFixed(7));
                            }

                            if (coin_value > 10000) {
                                coin_value_real = (coin_value.toFixed(3));
                            }
                            if (coin_value > 100000) {
                                coin_value_real = (coin_value.toFixed(2));
                            }
                            if (coin_value > 1000000) {
                                coin_value_real = (coin_value.toFixed(1));
                            }
                            if (coin_value > 10000000) {
                                coin_value_real = (coin_value.toFixed(0));
                            }

                            jQuery("#" + coin.coin_id + "-value").html("<b>"+coin_value_real+"</b>");
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
        if (cur_p == "USD" || cur_p == "EUR"  || cur_p == "GBP" || cur_p == "AUD" || cur_p == "CAD" || cur_p == "BRL" || cur_p == "MXN" || cur_p == "SGD" || cur_p == "JPY") {
            // totalValue = Number(totalValue).toFixed(2);

            totalValue = formatCurrencyDecimalMax(totalValue, cur_p);

        }

        
        else {
            totalValue = Number(totalValue).toFixed(6);
        }
        jQuery("#totalValue").html(totalValue);
    }

}



function updatePortfolioFeedback(type) {
    var divId = Date.now();
    if(type == 'added') {
        document.getElementById('portfolio-feedback').innerHTML = '<div id="'+divId+'">Added!</div>';
    }
    else if (type == 'removed') {
        document.getElementById('portfolio-feedback').innerHTML = '<div id="'+divId+'">Removed!</div>';
    }
    else if (type == 'notInList') { 
        document.getElementById('portfolio-feedback').innerHTML = '<div id="'+divId+'">Not in portfolio!</div>';
    }
    else if (type == 'alreadyInList') {
        document.getElementById('portfolio-feedback').innerHTML = '<div id="'+divId+'">Already in portfolio!</div>';
    }
    setTimeout(function () {
        jQuery("#"+divId).fadeOut("normal", function() {
            jQuery("#"+divId).remove();
        });
    }, 1500); 
}


// Add coin
function cwAddCoin() {
	var coin_id = jQuery("#portfolio_dropdown").val();

	// get coin's slug
	for (var i in cw_cmc) {
		if (cw_cmc.hasOwnProperty(i)) {
			if (cw_cmc[i]['id'] == coin_id) {
				var website_slug = cw_cmc[i]['slug'];
				break;
			}
		}
	}

	function addPortCoin() {
		// var id = portfolio.length + 1;
		var found = portfolio.some(function (i) {
			return i.coin_id === coin_id;
		});
		if (!found) { 
            updatePortfolioFeedback('added');
			portfolio.push({"coin_id" : coin_id, "amount": "0", "invested": "0", "invested_c": "USD", "note": "", "slug" : website_slug}); 
		}
        else {
            updatePortfolioFeedback('alreadyInList');
        }
	}

	if (subs == 1 || portfolio.length < 5) {
		addPortCoin();
	}
	else if (limitEarly && portfolio.length < 10) {
		addPortCoin();
	}
	else {
		jQuery('#portfolio-message').show();
	}

	portfolio_save_2();
    load_portfolio(portfolio); 
}



function cwRemoveCoin() {
	var coin_id = jQuery("#portfolio_dropdown").val();

	for (var i = 0; i < portfolio.length; i++) {
        if (portfolio[i]["coin_id"] === coin_id) {
            portfolio.splice(i,1);
            updatePortfolioFeedback('removed');
            break;
        }
        else {
            updatePortfolioFeedback('notInList');
        }
	}

	if (portfolio.length < 1){
		portfolio = [];

		var data="&data="+JSON.stringify(portfolio);

		jQuery.ajax({
			type:"POST",
            url: '/api/update_portfolio',
            data: data
		});
	};

	portfolio_save_2();
	load_portfolio(portfolio);	
}