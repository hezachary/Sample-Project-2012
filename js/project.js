project_tools = new function(){
    
    this.passUrlQuery = function(){
        if(window.location.search.length < 2) return {};
        var url = arguments.lengt > 0 ? arguments[0] : window.location.search.substr(1);
        var ary_url = url.split('&');
        var obj_url = {};
        for(var i = 0; i < ary_url.length ; i++){
            var ary_query_item = ary_url[i].split('=');
            if(typeof ary_query_item[1] != 'undefined'){
                obj_url[ary_query_item[0]] = ary_query_item[1];
            }
        }
        return obj_url;
    }
    
    /**
     * var data = {};
     * data = pickUpForm('form[id="form_name"]', data);
     * jq.ajax ....
     *
     **/
    this.pickUpForm = function(str_form_name, data){
        var blnPickDisabled = arguments.length > 2 && arguments[2] == true ? true : false;
        //console.log(str_form_name + ' input,' + str_form_name + ' select,' + str_form_name + ' textarea');
        jq(str_form_name + ' input,' + str_form_name + ' select,' + str_form_name + ' textarea').each(function(){
            if(!blnPickDisabled && this.disabled){
                return;
            }
            var field_type = jq(this).attr('type');
            var blnRetrieve = true;
            if(field_type == 'checkbox' || field_type == 'radio' ){
                if(!this.checked){
                    blnRetrieve = false;
                }
            }
            if(blnRetrieve){
                var input_node = jq(this);
                data[input_node.attr('name')] = input_node.val();   
            }
        });
        return data;
    }
}