{if  $USER->ACL->map_edit.view}
<script language="javascript" type="text/javascript" src="{$CFG->www}js/jQueryRotateCompressed.2.1.js"></script>
<div class="content custom_field">
    <div class="page_content">
        <h2>Maintian Celebrity House</h2>
        <form class="normal_form" name="image_form" method="post" action="" >
            <input type="hidden" id="section" name="section" value="{$CFG->section}" />
        <table class="image_list" cellpadding="2" cellspacing="2" border="0">
        {if $page_content.neighbourhood_list|@is_array && $page_content.neighbourhood_list|@sizeof}
            <thead>
            <tr>
                <th align="center" width="84">&nbsp;</th>
                <th align="center">Name</th>
                <th align="center" width="200">Celebrity House</th>
                <th align="center" width="100">Position</th>
                <th align="center" width="100">Mirror</th>
                <th align="center" width="84">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$page_content.neighbourhood_list item=ary_neighbourhood}
            <tr id="neighbourhood_row_{$ary_neighbourhood.id}" class="{cycle values=",alt"}">
                <td align="center"><input type="button" id="send_message_to_residents${$ary_neighbourhood.neighbourhood.id}" name="send_message_to_residents${$ary_neighbourhood.neighbourhood.id}" value="Send Notification to Everyone" /></td>
                <td>
                    {$ary_neighbourhood.neighbourhood.name}
                </td>
                <td align="center">
                    <select name="resident_element_id" class="sel_resident_element_id" id="resident_element_id${$ary_neighbourhood.neighbourhood.id}">
                        <option value="">N/A</option>
                        {html_options options=$page_content.celebrity_house_element_list selected=$ary_neighbourhood.resident_field_building.resident_element_id}
                    </select>
                </td>
                <td align="center">
                    <select name="map_matrix_id" class="sel_map_matrix_id" id="map_matrix_id${$ary_neighbourhood.neighbourhood.id}">
                        {if $ary_neighbourhood.resident_field_building}
                        <option value="{$ary_neighbourhood.mapmatrix.id}">x={$ary_neighbourhood.mapmatrix.x},y={$ary_neighbourhood.mapmatrix.y}(current)</option>
                        {else}
                        <option value="">N/A</option>
                        {/if}
                        <option value="data">Retrieve All Avaliable</option>
                    </select>
                </td>
                <td align="center">
                    <select name="mirror" class="mirror" id="resident_element_mirror${$ary_neighbourhood.neighbourhood.id}">
                        <option value="">N/A</option>
                        <option value="0" {if $ary_neighbourhood.resident_field_building.mirror==='0'}selected="selected"{/if}>No</option>
                        <option value="1" {if $ary_neighbourhood.resident_field_building.mirror==1}selected="selected"{/if}>Yes</option>
                    </select>
                </td>
                <td align="center"><input type="button" id="update_celebrity_house${$ary_neighbourhood.neighbourhood.id}" name="update_celebrity_house${$ary_neighbourhood.neighbourhood.id}" value="Update" /></td>
            </tr>
            {/foreach}
            </tbody>
        {/if}
        </table>
        </form>
    </div>
</div>

<script language="javascript" type="text/javascript">
    /* <![CDATA[ */
    var www = '{$CFG->www}';
    var commandList = [];
    /*{literal}*/
    $(document).ready(function(){
        var selPosition = $('select[id^="map_matrix_id$"]');
        var btnUpdate = $('input[type=button][id^="update_celebrity_house$"]');
        var btnSend = $('input[type=button][id^="send_message_to_residents$"]');
        
        selPosition.each(function(){
            $(this).bind('change', function(){
                switch($(this).val()){
                    case 'data':
                        fnRetrieveAvailablePlot(this, this.id.toString().replace('map_matrix_id$',''));
                        break;
                }
            });
        });
        
        btnUpdate.each(function(){
            $(this).bind('click', function(){
                fnUpdateCelebrityHouse(this, this.id.toString().replace('update_celebrity_house$',''));
            });
        });
        
        btnSend.each(function(){
            $(this).bind('click', function(){
                fnSendMessage2Residents(this, this.id.toString().replace('send_message_to_residents$',''));
            });
        });
    });
    
    function fnRetrieveAvailablePlot(dom, neighbourhood_id){
        var td = $(dom).closest('td');
        
        var data = {};
        data['command'] = 'retrieve_available_plot';
        data['neighbourhood_id'] = neighbourhood_id;
        data['mode'] = 'celebrity_house';
        
        fnAjax(dom, data, function(msg){
            td.append(msg.html);
        });
    }
    
    function fnUpdateCelebrityHouse(dom, neighbourhood_id){
        var td = $(dom).closest('td');
        var tr = $(dom).closest('tr');
        
        var data = {};
        data['command'] = 'update_celebrity_house';
        data['neighbourhood_id'] = neighbourhood_id;
        data['map_matrix_id'] = tr.find('select[id^="map_matrix_id$"]').val();
        data['resident_element_id'] = tr.find('select[id^="resident_element_id$"]').val();
        data['mirror'] = tr.find('select[id^="resident_element_mirror$"]').val();
        
        data['mode'] = 'celebrity_house';
        
        fnAjax(dom, data, function(msg){
            td.append(msg.html);
        });
    }
    
    function fnSendMessage2Residents(dom, neighbourhood_id){
        var td = $(dom).closest('td');
        var tr = $(dom).closest('tr');
        
        var data = {};
        data['command'] = 'send_message_to_residents';
        data['neighbourhood_id'] = neighbourhood_id;
        data['map_matrix_id'] = tr.find('select[id^="map_matrix_id$"]').val();
        data['resident_element_id'] = tr.find('select[id^="resident_element_id$"]').val();
        data['mirror'] = tr.find('select[id^="resident_element_mirror$"]').val();
        
        data['mode'] = 'celebrity_house';
        
        fnAjax(dom, data, function(msg){
            td.append(msg.html);
        });
    }
    
    function fnAjax(dom, data, callback){
        if($('#ajax_display').length < 1){
            $('body').append('<div id="ajax_display" class="top_msg"></div>');
        }
        if($('#ajax_display_outter').length < 1){
            $('#ajax_display').before('<div id="ajax_display_outter"></div>');
        }
        var height = $(window).height();
        var scrollTop = $(window).scrollTop();
        var width = $('body').width();
        var td = $(dom).closest('td');
        var tr = $(dom).closest('tr');
        $('#ajax_display_outter').css({'width':width+'px', 'height':$('body').height()+'px', 'z-index': '2000'});
        $('#ajax_display_outter').fadeTo("fast", 0.25, function(){
            $('#ajax_display_outter').show();
            td.empty();
            td.append('<img src="' + www + '/images/ajax_loader.gif" alt="" width="16" height="16" />');
            $.ajax({
                type: "POST",
                url: "admin_ajax.php",
                data: data,
                async: false,
                dataType: 'json',
                success: function(msg){
                    td.empty();
                    callback(msg);
                    $('#ajax_display_outter').hide();
                    $('#ajax_display').empty();
                }
            });
        });
        
    }
    
    function fnClearCache(dom, command){
        if($('#ajax_display').length < 1){
            $('body').append('<div id="ajax_display" class="top_msg"></div>');
        }
        if($('#ajax_display_outter').length < 1){
            $('#ajax_display').before('<div id="ajax_display_outter"></div>');
        }
        var height = $(window).height();
        var scrollTop = $(window).scrollTop();
        var width = $('body').width();
        var td = $(dom).closest('td');
        var tr = $(dom).closest('tr');
        $('#ajax_display_outter').css({'width':width+'px', 'height':$('body').height()+'px', 'z-index': '2000'});
        $('#ajax_display_outter').fadeTo("fast", 0.25, function(){
            $('#ajax_display_outter').show();
            td.empty();
            td.append('<img src="' + www + '/images/ajax_loader.gif" alt="" width="16" height="16" />');
            var data = {};
            data['command'] = command;
            data['mode'] = 'cache_manager';
            $.ajax({
                type: "POST",
                url: "admin_ajax.php",
                data: data,
                async: false,
                dataType: 'json',
                success: function(msg){
                    td.empty();
                    td.append('<strong>' + msg.text + '</strong>');
                    $('#ajax_display_outter').hide();
                    $('#ajax_display').empty();
                    tr.find('input[type=checkbox]').remove();
                    if(commandList.length > 0){
                        var new_command = commandList.pop();
                        fnClearCache(new_command.dom, new_command.command);
                    }
                }
            });
        });
    }
    /*{/literal} ]]>*/
</script>
{/if}