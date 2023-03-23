// Select2 optimization as described here:
// https://stackoverflow.com/questions/32756698/how-to-enable-infinite-scrolling-in-select2-4-0-without-ajax

jQuery.fn.select2.amd.define('select2/data/customAdapter', ['select2/data/array', 'select2/utils'],
function (ArrayData, Utils) {
    function CustomDataAdapter($element, options) {
        CustomDataAdapter.__super__.constructor.call(this, $element, options);
    }
 
    Utils.Extend(CustomDataAdapter, ArrayData);
 
    CustomDataAdapter.prototype.current = function (callback) {
         var found = [],
            findValue = null,
            initialValue = this.options.options.initialValue,
            selectedValue = this.$element.val(),
            jsonData = this.options.options.jsonData,
            jsonMap = this.options.options.jsonMap;
        
        if (initialValue !== null){
            findValue = initialValue;
            this.options.options.initialValue = null;  // <-- set null after initialized              
        }
         else if (selectedValue !== null){
            findValue = selectedValue;
        }
        
         if(!this.$element.prop('multiple')){
            findValue = [findValue];
            this.$element.html();     // <-- if I do this for multiple then it breaks
        }

        // Query value(s)
        for (var v = 0; v < findValue.length; v++) {              
            for (var i = 0, len = jsonData.length; i < len; i++) {
                if (findValue[v] == jsonData[i][jsonMap.id]){
                   found.push({id: jsonData[i][jsonMap.id], text: jsonData[i][jsonMap.text]}); 
                   if(this.$element.find("option[value='" + findValue[v] + "']").length == 0) {
                       this.$element.append(new Option(jsonData[i][jsonMap.text], jsonData[i][jsonMap.id]));
                   }
                   break;   
                }
            }
        }
        
        // Set found matches as selected
        this.$element.find("option").prop("selected", false).removeAttr("selected");            
        for (var v = 0; v < found.length; v++) {            
            this.$element.find("option[value='" + found[v].id + "']").prop("selected", true).attr("selected","selected");            
        }

        // If nothing was found, then set to top option (for single select)
        if (!found.length && !this.$element.prop('multiple')) {  // default to top option 
            found.push({id: jsonData[0][jsonMap.id], text: jsonData[0][jsonMap.text]}); 
             this.$element.html(new Option(jsonData[0][jsonMap.text], jsonData[0][jsonMap.id], true, true));
        }
        
        callback(found);
    };        
 
    CustomDataAdapter.prototype.query = function (params, callback) {
        if (!("page" in params)) {
            params.page = 1;
        }

        var jsonData = this.options.options.jsonData,
            pageSize = this.options.options.pageSize,
            jsonMap = this.options.options.jsonMap;

        var results = jQuery.map(jsonData, function(obj) {
            // Search
            if(new RegExp(params.term, "i").test(obj[jsonMap.text])) {
                return {
                    id:obj[jsonMap.id],
                    text:obj[jsonMap.text]
                };
            }
        });

        callback({
            results:results.slice((params.page - 1) * pageSize, params.page * pageSize),
            pagination:{
                more:results.length >= params.page * pageSize
            }
        });
    };

    return CustomDataAdapter;

});

var jsonAdapter=jQuery.fn.select2.amd.require('select2/data/customAdapter');

var isIndividualCoinPage = false;

initialValue = "1";


// jQuery(document).ready(function() {
//     if (typeof(initialValue) == 'undefined') {
//       initialValue = "1";
//     }
//     var myOptions = {
//         ajax: {},
//         jsonData: cw_cmc,
//         jsonMap: {id: "id", text: "text"},
//         initialValue: initialValue,
//         pageSize: 50,
//         dataAdapter: jsonAdapter
// 	};
// 	jQuery(".selectcoin").select2(myOptions);
// });

function currencyConverterPrep(isPortfolio) {
    var temp = document.getElementsByTagName("template")[0];
    var clon = temp.content.cloneNode(true);
    if (isPortfolio) {
        document.getElementById('portfolio-currency-converter').innerHTML = "";
        document.getElementById('individual-currency-converter').innerHTML = "";

        document.getElementById('portfolio-currency-converter').appendChild(clon);
        initialValue = '1';
    }
    else {
        if (isLoggedIn) {
            document.getElementById('portfolio-currency-converter').innerHTML = "";
        }
        document.getElementById('individual-currency-converter').innerHTML = "";
        document.getElementById('individual-currency-converter').appendChild(clon);
    }
    
    var curListArray = Object.keys(cw_rates).map((key) => [String(key), cw_rates[key]]);
    var curListArrayNew = [];

    curListArrayNew[0] = [];
    curListArrayNew[0]['id'] = 'usd';
    curListArrayNew[0]['text'] = 'USD';

    for(var i=0; i < curListArray.length; i++) {
        var coin = curListArray[i];
        var i2 = i + 1;
        curListArrayNew[i2] = [];
        curListArrayNew[i2]['id'] = coin[0];
        curListArrayNew[i2]['text'] = coin[0].toUpperCase();
    }

    // console.log(curListArrayNew)

    var cw_cmcConverter = curListArrayNew.concat(cw_cmc);
    // console.log(cw_cmcConverter)

    // Activate Select2 top dropdown
    myOptions = {
        ajax: {},
        jsonData: cw_cmcConverter,
        jsonMap: {id: "id", text: "text"},
        initialValue: initialValue,
        pageSize: 50,
        dataAdapter: jsonAdapter
    };
    jQuery(".selectcoin-conv-1").select2(myOptions);
    
    // Activate Select2 bottom dropdown
    initialValue = "usd";
    myOptions = {
        ajax: {},
        jsonData: cw_cmcConverter,
        jsonMap: {id: "id", text: "text"},
        initialValue: initialValue,
        pageSize: 50,
        dataAdapter: jsonAdapter
    };
    jQuery(".selectcoin-conv-2").select2(myOptions);

    // Initial values
    jQuery('#conv-input-1').val(1);

    jQuery('#conv-input-1').change(function() {
        currencyConverter('top');
    })

    jQuery('#conv-input-2').change(function() {
        currencyConverter('bottom');
    })

    jQuery('.selectcoin-conv-1').change(function() {
        currencyConverter('top');
    })

    jQuery('.selectcoin-conv-2').change(function() {
        currencyConverter('top');
    })

    jQuery(document).ready(function() {
        currencyConverter('top');
    });
}

function currencyConverter(type) {
    if (type == 'top') {
        var convertFromCoinId = jQuery('.selectcoin-conv-1').val();
        var convertToCoinId = jQuery('.selectcoin-conv-2').val();
        
        var convertFromCoinAmount = jQuery('#conv-input-1').val();
    }
    else if (type == 'bottom') {
        var convertFromCoinId = jQuery('.selectcoin-conv-2').val();
        var convertToCoinId = jQuery('.selectcoin-conv-1').val();
        
        var convertFromCoinAmount = jQuery('#conv-input-2').val();
    }

    if (convertFromCoinAmount == '') {
        jQuery('#conv-input-2').val('');
        jQuery('#conv-input-1').val('');
        return;
    }

    if (isNaN(convertFromCoinAmount)) {
        alert('Input field should be a numeric value.');
        return;
    }

    var convertFromCoinPrice = null;
    var convertToCoinPrice = null;

    for(var i=0; i < cw_cmc.length; i++) {
		var coin = cw_cmc[i];
        if (convertFromCoinId == 'usd') {
            convertFromCoinPrice = 1;
            break;
        }
        else if (isNaN(convertFromCoinId)) {
            convertFromCoinPrice = cw_rates[convertFromCoinId];
            break;
        }
		else if (coin['id'] == convertFromCoinId) {
			convertFromCoinPrice = coin['price_usd'];
			break;
		}
	}

    for(var i=0; i < cw_cmc.length; i++) {
		var coin = cw_cmc[i];
        if (convertToCoinId == 'usd') {
            convertToCoinPrice = 1;
            break;
        }
        else if (isNaN(convertToCoinId)) {
            convertToCoinPrice = cw_rates[convertToCoinId];
            break;
        }
		else if (coin['id'] == convertToCoinId) {
			convertToCoinPrice = coin['price_usd'];
			break;
		}
	}

    // if currency-currency conversion
    if (isNaN(convertToCoinId) && isNaN(convertFromCoinId)) {
        // console.log(convertFromCoinPrice, convertToCoinPrice);

        // get usd values
        var fromUsdRate = 1 / convertFromCoinPrice;
        var toUsdRate = 1 / convertToCoinPrice;
        
        // console.log("from", fromUsdRate, "to", toUsdRate);

        var amountUsd = convertFromCoinAmount * fromUsdRate;
        var result = parseFloat(amountUsd / toUsdRate);

        if (result >= 1) {
            result = result.toFixed(2);
        }
    }
    else {
        // if currency-cryptocurrency
        if (isNaN(convertFromCoinId)) {
            // console.log("THIS")
            // get usd values
            var fromUsdRate = 1 / convertFromCoinPrice;
            var toUsdRate = convertToCoinPrice;
            
            // console.log("from", fromUsdRate, "to", toUsdRate);

            var valueUsd = convertFromCoinAmount * fromUsdRate;
            // console.log(valueUsd)
            // console.log(convertToCoinPrice, valueUsd)
            // console.log(parseFloat(convertToCoinPrice / valueUsd))
            // console.log((valueUsd / toUsdRate) + '')
            var result = parseFloat(valueUsd / toUsdRate);
            if (result >= 1) {
                result = result.toFixed(2);
            }
        }
        // if vice-versa
        else if (isNaN(convertToCoinId)) {
            // console.log("THIS2");
            // get usd values
            var fromUsdRate = 1 / convertFromCoinPrice;
            var toUsdRate = 1 / convertToCoinPrice;
            
            // console.log("from", fromUsdRate, "to", toUsdRate);

            var valueUsd = fromUsdRate / convertFromCoinAmount;
            // console.log(valueUsd)
            var result = parseFloat(convertToCoinPrice / valueUsd);
            if (result >= 1) {
                result = result.toFixed(2);
            }
        }
        // if cryptocurrency conversion
        else {
            var result = parseFloat(convertFromCoinAmount * convertFromCoinPrice / convertToCoinPrice);
        }
    }
    if (typeof(result) == 'number') {
        if (result == 0) {
            result = "0.00";
        }
        else if (result > 10000000000) {
            result = result.toFixed(0);
        }
        else if (result > 1000000000) {
            result = result.toFixed(2);
        }
        else if (result > 100000000) {
            result = result.toFixed(3);
        }
        else if (result > 10000000) {
            result = result.toFixed(4);
        }
        else {
            if (result < 0.000000001) {
                result = (result).toFixed(12);
            }
            else if (result < 0.0000001) {
                result = (result).toFixed(11);
            }
            else if (result < 0.0000001) {
                result = (result).toFixed(10);
            }
            else if (result < 0.000001) {
                result = (result).toFixed(9);
            }
            else if (result < 0.00001) {
                result = (result).toFixed(8);
            }
            else if (result < 0.0001) {
                result = (result).toFixed(7);
            }
            else {
                result = (result).toFixed(6);
            }
        }
    }
    
    jQuery('#ajax_loader_converter').hide();
    // jQuery('#converter-content').show();

    if (type == 'top') {
        jQuery('#conv-input-2').val(result);
    }
    else {
        jQuery('#conv-input-1').val(result);
    }
}

