{if  $USER->ACL->admin.view}
<div class="content custom_field">
    <div class="page_content">
        <h2>Main Cache List<a id="page_detail" name="page_detail"></a></h2>

        <form class="normal_form" name="image_form" method="post" action="" >
            <input type="hidden" id="section" name="section" value="{$CFG->section}" />
            <table class="image_list" cellpadding="2" cellspacing="2" border="0">
            <thead>
            <tr>
                <th align="center" width="20"><input type="checkbox" id="select_common_cache" name="select_common_cache[0]" value="1" /></th>
                <th align="center">Name</th>
                <th align="center" width="84">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <tr class="{cycle values=",alt"}">
                <td align="center"><input type="checkbox" id="select_common_cache$retrieveNeighbourhoodLevelSettings" name="select_common_cache$retrieveNeighbourhoodLevelSettings" value="1" /></td>
                <td>Neighbourhood Level Settings</td>
                <td align="center"><input type="button" id="clear_common_cache$retrieveNeighbourhoodLevelSettings" name="clear_common_cache$retrieveNeighbourhoodLevelSettings" value="Clear Cache" /></td>
            </tr>
            <tr class="{cycle values=",alt"}">
                <td align="center"><input type="checkbox" id="select_common_cache$retrieveResidentLevelSettings" name="select_common_cache$retrieveResidentLevelSettings" value="1" /></td>
                <td>Resident Level Settings</td>
                <td align="center"><input type="button" id="clear_common_cache$retrieveResidentLevelSettings" name="clear_common_cache$retrieveResidentLevelSettings" value="Clear Cache" /></td>
            </tr>
            <tr class="{cycle values=",alt"}">
                <td align="center"><input type="checkbox" id="select_common_cache$retrieveMapImageList" name="select_common_cache$retrieveMapImageList" value="1" /></td>
                <td>Map Image Files List</td>
                <td align="center"><input type="button" id="clear_common_cache$retrieveMapImageList" name="clear_common_cache$retrieveMapImageList" value="Clear Cache" /></td>
            </tr>
            <tr class="{cycle values=",alt"}">
                <td align="center"><input type="checkbox" id="select_common_cache$retrieveResidentElementTypeList" name="select_common_cache$retrieveResidentElementTypeList" value="1" /></td>
                <td>Plot Elements List (Grass, Roads, Houses, Fences, Animals, Community Properties, etc. Anything display on a plot)</td>
                <td align="center"><input type="button" id="clear_common_cache$retrieveResidentElementTypeList" name="clear_common_cache$retrieveResidentElementTypeList" value="Clear Cache" /></td>
            </tr>
            <tr class="{cycle values=",alt"}">
                <td align="center"><input type="checkbox" id="select_common_cache$retrieveTaskSettings" name="select_common_cache$retrieveTaskSettings" value="1" /></td>
                <td>Task Settings</td>
                <td align="center"><input type="button" id="clear_common_cache$retrieveTaskSettings" name="clear_common_cache$retrieveTaskSettings" value="Clear Cache" /></td>
            </tr>
            <tr class="{cycle values=",alt"}">
                <td align="center"><input type="checkbox" id="select_common_cache$retrieveLanguageList" name="select_common_cache$retrieveLanguageList" value="1" /></td>
                <td>Language Settings</td>
                <td align="center"><input type="button" id="clear_common_cache$retrieveLanguageList" name="clear_common_cache$retrieveLanguageList" value="Clear Cache" /></td>
            </tr>
            <tr class="{cycle values=",alt"}">
                <td align="center"><input type="checkbox" id="select_common_cache$retrieveSponsorList" name="select_common_cache$retrieveSponsorList" value="1" /></td>
                <td>Sponsor Settings</td>
                <td align="center"><input type="button" id="clear_common_cache$retrieveSponsorList" name="clear_common_cache$retrieveSponsorList" value="Clear Cache" /></td>
            </tr>
            </tbody>
        </table>      
        <h2>Neighbourhood List</h2>
        <table class="image_list" cellpadding="2" cellspacing="2" border="0">
        {if $page_content.neighbourhood_list|@is_array && $page_content.neighbourhood_list|@sizeof}
            <thead>
            <tr>
                <th align="center" width="20"><input type="checkbox" id="select_neighbourhood" name="select_neighbourhood[0]" value="1" /></th>
                <th align="center">Name</th>
                <th align="center">Template</th>
                <th align="center" width="50">Rating</th>
                <th align="center" width="50">Level</th>
                <th align="center" width="80">Residents</th>
                <th align="center" width="80">Free Plots</th>
                <th align="center">Mayer</th>
                <th align="center" width="84">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$page_content.neighbourhood_list item=ary_neighbourhood}
            <tr id="neighbourhood_row_{$ary_neighbourhood.id}" class="{cycle values=",alt"}">
                <td align="center"><input type="checkbox" id="select_neighbourhood${$ary_neighbourhood.id}" name="select_neighbourhood${$ary_neighbourhood.id}" value="1" /></td>
                <td>{$ary_neighbourhood.name}</td>
                <td>{$ary_neighbourhood.template_name}</td>
                <td align="center">{$ary_neighbourhood.rating}</td>
                <td align="center">{$ary_neighbourhood.level}</td>
                <td align="center">{$ary_neighbourhood.resident_qty}</td>
                <td align="center">{$ary_neighbourhood.free_land_qty}</td>
                <td>
                {foreach from=$ary_neighbourhood.neighbourhood_resident_list item=ary_resident}
                    {if $ary_resident.position_id == 1}
                        <a href="?section=player_list&amp;mode=info&amp;id={$ary_resident.id}" title="{$ary_resident.name}: {$ary_resident.rating}, {$ary_resident.facebook_id}">{$ary_resident.name}</a>
                    {/if}
                {/foreach}
                </td>
                <td align="center"><input type="button" id="clear_cache_neighbourhood${$ary_neighbourhood.id}" name="clear_cache_neighbourhood${$ary_neighbourhood.id}" value="Clear Cache" /></td>
            </tr>
            {/foreach}
            </tbody>
        {/if}
        </table>
        <p><input type="button" id="clear_selected_cache" name="clear_selected_cache" value="Clear Selected Cache" /></p>
        </form>
    </div>
</div>

<script language="javascript" type="text/javascript">
    /* <![CDATA[ */
    var www = '{$CFG->www}';
    var commandList = [];
    /*{literal}*/
    $(document).ready(function(){
        var chkSelectCommonCache = $('#select_common_cache');
        var chkSelectNeighbourhood = $('#select_neighbourhood');
        var chkSelectCommonCacheList = $('input[type=checkbox][id^="select_common_cache$"]');
        var chkSelectNeighbourhoodList = $('input[type=checkbox][id^="select_neighbourhood$"]');
        
        var btnSelectCommonCacheList = $('input[type=button][id^="clear_common_cache$"]');
        var btnSelectNeighbourhoodList = $('input[type=button][id^="clear_cache_neighbourhood$"]');
        
        var btnClearSelectedCache = $('#clear_selected_cache');
        
        
        btnClearSelectedCache.bind('click', function(){
            fnClearBySelect();
        });
        chkSelectCommonCache.bind('click', function(){
            var blnChecked = this.checked;
            chkSelectCommonCacheList.each(function(){
                this.checked = blnChecked
            });
        });
        chkSelectNeighbourhood.bind('click', function(){
            var blnChecked = this.checked;
            chkSelectNeighbourhoodList.each(function(){
                this.checked = blnChecked
            });
        });
        chkSelectCommonCacheList.each(function(){
            $(this).bind('click', function(){
                selectAll(chkSelectCommonCache.get(0), chkSelectCommonCacheList);
            });
        });
        chkSelectNeighbourhoodList.each(function(){
            $(this).bind('click', function(){
                selectAll(chkSelectNeighbourhood.get(0), chkSelectNeighbourhoodList);
            });
        });
        btnSelectCommonCacheList.each(function(){
            $(this).bind('click', function(){
                fnClearCache(this, this.id.toString().replace('clear_common_cache$',''));
            });
        });
        btnSelectNeighbourhoodList.each(function(){
            $(this).bind('click', function(){
                fnClearCache(this, this.id.toString().replace('clear_cache_neighbourhood$',''));
            });
        });
    });
    
    function selectAll(dom, sampleList){
        var blnChecked = sampleList[0].checked;
        for(var i=1; i < sampleList.length; i++ ){
            blnChecked = blnChecked * sampleList[i].checked;
        }
        dom.checked = blnChecked;
    }
    
    function fnClearBySelect(){
        var chkSelectCacheList = $('input[type=checkbox][id^="select_common_cache$"],input[type=checkbox][id^="select_neighbourhood$"]');
        commandList = [];
        chkSelectCacheList.each(function(){
            if(this.checked){
                var command = {};
                command.dom = $(this).closest('tr').find('input[type=button]').get(0);
                command.command = this.id.substring(this.id.indexOf('$') + 1);
                commandList.push(command);
            };
        });
        if(commandList.length > 0){
            var new_command = commandList.pop();
            fnClearCache(new_command.dom, new_command.command);
        }
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