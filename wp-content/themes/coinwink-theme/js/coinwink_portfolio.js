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
    }
    }
});


jQuery("#portfolio_add_coin").click(function() {
var coin_id = jQuery("#portfolio_dropdown").val();

if (portfolio.length > 0) {

    var id = portfolio.length + 1;
    var found = portfolio.some(function (i) {
        return i.coin_id === coin_id;
    });
    if (!found) { portfolio.push({"coin_id" : coin_id, "amount": "0"}); }

}
else {
    portfolio.push({"coin_id" : coin_id, "amount": "0"});
}

load_portfolio(portfolio);
});

jQuery("#portfolio_remove_coin").click(function() {
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

load_portfolio(portfolio);	
});


function load_portfolio(json) {
if (portfolio.length < 1) {
    jQuery("#portfolio_empty").show();
    jQuery("#portfolio_content").hide();
}
else {
    jQuery("#portfolio_empty").hide();
    jQuery("#portfolio_content").show();
    jQuery("#portfolio_content").empty();

    function build_table(json) {

        var cols = Object.keys(json[0]);
        var headerRow = '';
        var bodyRows = '';

        headerRow += '<th>Coin</th><th>Price</th><th>Amount</th><th>Value</th>';

        var portfolio2 = [];

        json.map(function(coin) {
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

            portfolio2.push({"coin_id" : coin_id, "amount": coin_amount, "value": coin_value});

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
                    return coin_price;
                }
            }
            }
            
            bodyRows += '<td class="portfoliocoinid" id="'+
            row["coin_id"]+
            '"><a target="_blank" class="portfoliocoin" href="https://coinmarketcap.com/currencies/' +
            row["coin_id"] +
            '"><img src="/coins/32x32/'+
            row["coin_id"]+
            '.png" width="16">&nbsp;&nbsp;'+
            getCoinName(row["coin_id"]) +
            '</a></td>';

            bodyRows += '<td>'+
            getCoinPrice(row["coin_id"]) +
            '</td>';

            bodyRows += '<td><input value="'+
            row["amount"]+
            '" maxlength="99" class="portfoliocoinamount"  name="" type="text" required></td>';

            bodyRows += '<td class="coinvalue">' + row["value"] + '</td>';

            bodyRows += '</tr>';
            
        });

        return '<table class="portfoliotable"><thead><tr>' +
        headerRow +
        '</tr></thead><tbody>' +
        bodyRows +
        '</tbody></table><b>Total value: </b><span id="totalValue"></span>&nbsp;&nbsp;<select id="value_select" class="selectcurrency" style="margin-bottom:0px;"><option value="USD">USD</option><option value="BTC">BTC</option><option value="ETH">ETH</option><option value="percent">%</option></select><br><br><input type="submit" id="portfolio_save" style="width:48px;" value="Save" onclick="portfolio_save()"/>';
        
    }
    document.getElementById('portfolio_content').innerHTML = build_table(portfolio);

    var totalValue = 0;				
        jQuery( ".coinvalue" ).each(function() {
            number = parseFloat(jQuery(this).html());
            totalValue += number;
        });
    totalValue = Number(totalValue).toFixed(2);
    jQuery("#totalValue").html(totalValue);

    jQuery('#value_select').change(function(){
        currency = jQuery("#value_select option:selected").val();
        
        if (currency == "BTC") {
            function change_to_btc() {
                jQuery('.portfoliotable > tbody > tr').each(function(i) {
    
                var coin_id = jQuery(this).find(">:first-child").attr('id');
                var coin_amount = jQuery(this).find('input').val();		
    
                function getCoinPrice(coin_id) {
                for(var i = 0; i < jqueryarray.length; i++) {
                    var coin = jqueryarray[i];
                    if (coin['id'] == coin_id) {
                        var coin_price = coin['price_btc'];
                        return coin_price;
                    }
                }
                }
    
                var coin_price = getCoinPrice(coin_id);
    
                var coin_value = coin_amount * coin_price;
                coin_value = Number(coin_value).toFixed(5);
    
                jQuery(this).find(">:nth-child(2)").text(coin_price);
                jQuery(this).find(">:nth-child(4)").text(coin_value);
    
                });
            }
            change_to_btc();	

            var totalValue = 0;				
            jQuery( ".coinvalue" ).each(function() {
                number = parseFloat(jQuery(this).html());
                totalValue += number;
            });
            totalValue = Number(totalValue).toFixed(5);
            jQuery("#totalValue").html(totalValue);
        }

        if (currency == "ETH") {
            function change_to_eth() {
                jQuery('.portfoliotable > tbody > tr').each(function(i) {
    
                var coin_id = jQuery(this).find(">:first-child").attr('id');
                var coin_amount = jQuery(this).find('input').val();		
    
                function getCoinPrice(coin_id) {
                for(var i = 0; i < jqueryarray.length; i++) {
                    var coin = jqueryarray[i];
                    if (coin['id'] == coin_id) {
                        var coin_price = coin['price_eth'];
                        return coin_price;
                    }
                }
                }
    
                var coin_price = Number(getCoinPrice(coin_id)).toFixed(6);
    
                var coin_value = coin_amount * coin_price;
                coin_value = Number(coin_value).toFixed(6);
    
                jQuery(this).find(">:nth-child(2)").text(coin_price);
                jQuery(this).find(">:nth-child(4)").text(coin_value);
    
                });
            }
            change_to_eth();	

            var totalValue = 0;				
            jQuery( ".coinvalue" ).each(function() {
                number = parseFloat(jQuery(this).html());
                totalValue += number;
            });
            totalValue = Number(totalValue).toFixed(6);
            jQuery("#totalValue").html(totalValue);
        }

        if (currency == "USD") {
            function change_to_usd() {
                jQuery('.portfoliotable > tbody > tr').each(function(i) {

                var coin_id = jQuery(this).find(">:first-child").attr('id');
                var coin_amount = jQuery(this).find('input').val();		

                function getCoinPrice(coin_id) {
                for(var i = 0; i < jqueryarray.length; i++) {
                    var coin = jqueryarray[i];
                    if (coin['id'] == coin_id) {
                        var coin_price = coin['price_usd'];
                        return coin_price;
                    }
                }
                }

                var coin_price = getCoinPrice(coin_id);

                var coin_value = coin_amount * coin_price;
                coin_value = Number(coin_value).toFixed(2);

                jQuery(this).find(">:nth-child(2)").text(coin_price);
                jQuery(this).find(">:nth-child(4)").text(coin_value);

                });
            }

            change_to_usd();	

            var totalValue = 0;				
            jQuery( ".coinvalue" ).each(function() {
                number = parseFloat(jQuery(this).html());
                totalValue += number;
            });
            totalValue = Number(totalValue).toFixed(2);
            jQuery("#totalValue").html(totalValue);
        }

        if (currency == "percent") {
            function change_to_percent() {
                jQuery('.portfoliotable > tbody > tr').each(function(i) {

                var coin_value = parseFloat(jQuery(this).find(">:nth-child(4)").html());
                var total_value = parseFloat(jQuery("#totalValue").html());

                function calculatePercent() {
                        var coin_percent = ((coin_value / total_value) * 100).toFixed(2) + '%';
                        return coin_percent;
                }

                var coin_percent = calculatePercent();

                jQuery(this).find(">:nth-child(4)").text(coin_percent);

                });
            }

            change_to_percent();	

            jQuery("#totalValue").text("100");
        }
    });	

}
}

function portfolio_save(){
var portfolio = [];

jQuery('.portfoliotable > tbody > tr').each(function(i) {
    
        var rowcoinid = jQuery(this).find(">:first-child").attr('id');
        var rowcoinamount = jQuery(this).find('input').val();
        
        rowcoinamount = rowcoinamount.replace(/,/g, '.');

        portfolio.push({"coin_id" : rowcoinid, "amount": rowcoinamount});
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