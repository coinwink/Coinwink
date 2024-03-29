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

// var initialValue = '1';
