{if  $USER->ACL->map_edit.view}
<script language="javascript" type="text/javascript" src="{$CFG->www}js/jQueryRotateCompressed.2.1.js"></script>
<div class="content custom_field">
    <div class="page_content">
{if $page_content}
    {assign var='ary_map_matrix_list' value=$page_content.ary_map_matrix_list}
    {assign var='neighbourhood_id' value=$page_content.neighbourhood_id}
    {assign var='ary_neighbourhood_list' value=$page_content.ary_neighbourhood_list}
    {assign var='ary_element_list' value=$page_content.ary_element_list}
{/if}
<script type="text/javascript">
/* <![CDATA[*/
var wwwroot = "{$CFG->wwwroot}";
var www = "{$CFG->www}";
var element_list = {$ary_element_list};
var template_settings = {$ary_map_matrix_list};
var current_neighbourhood_publish = '{foreach from=$ary_neighbourhood_list item=ary_neighbourhood}{if $ary_neighbourhood.id == $neighbourhood_id}{if $ary_neighbourhood.published}1{else}-1{/if}{/if}{/foreach}';
var current_duplicate_publish = '-1';
/*{literal}*/
    $(document).ready(function(){
        var org_neighbourhood_list_val = $('#neighbourhood_list');
        $('#neighbourhood_list').bind('change', function(){
            var r=confirm("Change Neighbourhood Selection will lose current work,\n Please confirm.");
            if (r==true){
                window.location = 'admin_index.php?section=map_editor&neighbourhood_id=' + $(this).val();
            }else{
                $('#neighbourhood_list').val(org_neighbourhood_list_val);
            }
        });
        $('#published').bind('change', function(){
            switch($('#status_mode').val()){
                case '1':
                    current_duplicate_publish = this.checked ? 1 : -1;
                    break;
                case '2':
                    current_neighbourhood_publish = this.checked ? 1 : -1;
                    break;
            }
        });
        $('#status_mode').bind('change', function(){
            switch($(this).val()){
                case '1':
                    $('#neighbourhood_id').val('');
                    $('#neighbourhood_name').val('');
                    if(current_duplicate_publish == 1){
                        $('#published').attr('checked', 'checked');
                    }else{
                        $('#published').removeAttr('checked');
                    }
                    break;
                case '2':
                    $('#neighbourhood_id').val($('#neighbourhood_list').val());
                    var select_box = $('#neighbourhood_list').get(0);
                    console.log(select_box.selectedIndex);
                    $('#neighbourhood_name').val(select_box.options[select_box.selectedIndex].text);
                    if(current_neighbourhood_publish == 1){
                        $('#published').attr('checked', 'checked');
                    }else{
                        $('#published').removeAttr('checked');
                    }
                    break;
            }
        });
        
        $('#submit_button').bind('click',function(){
            if($('#neighbourhood_id').val()=='' && $.trim($('#neighbourhood_name').val()) == ''){
                alert('Please fill the Name field');
                return;
            }
            var map_data = [];
            $('img.img').each(function(){
                var img_node = $(this);
                var status_container = img_node.closest('div.map_container').find('table.menu tr.change_status');
                map_data.push(status_container.attr('title') + ',' + img_node.attr('alt'));
            })
            if(map_data.length > 0){
                $('#map_data').val(map_data.join(';'));
                document.populate.submit();
            }
        });
        $('#button_populate').bind('click', function(){
            var x = parseInt($('#x').val());
            var y = parseInt($('#y').val());
            var table_node = $('#map_table');
            table_node.empty();
            for(var yi = 0; yi < y; yi++){
                var strRow = '';
                var status = 2; //free land
                for(var xi = 0; xi < x; xi++){
                    strRow += fnPopulateMap(xi, yi, status);
                }
                strRow = '<tr>' + strRow + '</tr>';
                table_node.append(strRow);
            }
            $('.img').each(function(){
                imgRotate($(this));
            })
            fnDisplayInfo();
        });
        
        if(template_settings.length > 0){
            fnPopulateMapFromTemplate();
            fnDisplayInfo();
        }
        //console.log(element_list);
    });
    
    function fnPopulateMapFromTemplate(){
        //console.log(template_settings[99]);
        var x = 0;
        var y = 0;
        var formated_settings = {};
        var table_node = $('#map_table');
        table_node.empty();
        
        for(var i=0; i < template_settings.length; i++){
            if(parseInt(template_settings[i]['x']) > x){
                x = template_settings[i]['x'];
            }
            if(parseInt(template_settings[i]['y']) > y){
                y = template_settings[i]['y'];
            }
            formated_settings[template_settings[i]['x'] + ',' + template_settings[i]['y']] = template_settings[i];
        }
        for(var yi = 0; yi < y; yi++){
            var strRow = '';
            for(var xi = 0; xi < x; xi++){
                var status = formated_settings[(xi+1) + ',' + (yi+1)]['map_element_type_id'];
                var menu_row = [];
                var row = [];
                for(n in formated_settings[(xi+1) + ',' + (yi+1)]['resident_field_building_list']){
                    var element_id = formated_settings[(xi+1) + ',' + (yi+1)]['resident_field_building_list'][n]['resident_element_id'];
                    var z_index = formated_settings[(xi+1) + ',' + (yi+1)]['resident_field_building_list'][n]['z_index'];
                    var mirror = formated_settings[(xi+1) + ',' + (yi+1)]['resident_field_building_list'][n]['mirror'];
                    var current_element = element_list[status][z_index]['resident_element_list'][element_id];
                    var file_name = formated_settings[(xi+1) + ',' + (yi+1)]['resident_field_building_list'][n]['file_name'] != "" ? formated_settings[(xi+1) + ',' + (yi+1)]['resident_field_building_list'][n]['file_name'] : current_element['file_name'];
                    menu_row.push([(xi+1), (yi+1), element_id, z_index, current_element]);
                    row.push(fnImg((xi+1), (yi+1), element_id, z_index, file_name, mirror));
                }
                strRow += '<td class="map_matrix status_' + status + '" id="td_' + (xi+1) + '_' + (yi+1) + '"><div class="map_container">' + row.join('') + fnAddMenu(menu_row) + '</div></td>';
            }
            strRow = '<tr>' + strRow + '</tr>';
            table_node.append(strRow);
        }
        $('.img').each(function(){
            imgRotate($(this));
        })
    }
    
    function fnPopulateMap(xi, yi, status){
        var layout_list = element_list[status];
        var row = [];
        var menu_row = [];
        var img = '';
        for(layout_i in layout_list){
            var layout = layout_list[layout_i];
            var file_name = '';
            var element_id = 0;
            var z_index = layout.id;
            
            var element_index = 0;
            for(element_list_i in layout.resident_element_list){
                element_index++;
            }
            element_index = Math.floor(Math.random()*element_index);
            var loop_i = 0;
            for(element_list_i in layout.resident_element_list){
                var element = layout.resident_element_list[element_list_i];
                if(loop_i == element_index){
                    file_name = element.file_name;
                    element_id = element.id;
                    break;
                }
                loop_i ++;
            }
            menu_row.push([(xi+1), (yi+1), element_id, z_index, layout.resident_element_list[element_id]]);
            row.push(fnImg((xi+1), (yi+1), element_id, z_index, file_name, 0));
        }
        strRow = '<td class="map_matrix status_' + status + '" id="td_' + (xi+1) + '_' + (yi+1) + '"><div class="map_container">' + row.join('') + fnAddMenu(menu_row) + '</div></td>';//fnImg() + fnAddMenu() +
        return strRow;
    }
    
    function imgRotate(el){
        var ary = el.attr('alt').split(',');
        el.rotate(ary[4]*90);
        ary.pop();
        el.siblings('.alt[alt^=' + ary.join(',') + ']').each(function(){
            var ary_alt = $(this).attr('alt').split(',');
            $(this).rotate(ary_alt[4]*90);
        });
    }
    
    function fnImg(x,y, element_id, z_index,file_name, mirror){
        var str = '<img class="img" style="z-index: ' + z_index + '; ' + ( file_name.indexOf('i_')==0 ? 'visibility:hidden' : '' ) + '" alt="' + x + ',' + y + ',' + z_index + ',' + element_id + ',' + mirror + '" src="' + www + 'images/admin/' + file_name + '" width="auto" height="auto"/>';
        var attach_str = '';
        if(file_name.indexOf('?') > -1){
            var ary_data = file_name.split('?')[1].split('&');
            for(var i=0; i< ary_data.length; i++){
                var img_data = ary_data[i].split('=');
                img_data = img_data[1].split(',');
                attach_str += '<img class="alt" style="z-index: ' + img_data[4] + ';left_:' + img_data[1] + 'px;top_:' + img_data[2] + 'px;' + ( img_data[0].substr(-4).toLowerCase() == '.swf' ? 'visibility:hidden' : '' ) + '" alt="' + x + ',' + y + ',' + z_index + ',' + element_id + ',' + img_data[3] + '" src="' + www + 'images/admin/' + img_data[0] + '" width="auto" height="auto"/>';
            }
        }
        return str + attach_str;
    }
    
    function fnAddMenu(menu_row){
        var status = null;
        var str = '';
        for(var i = 0; i < menu_row.length; i++){
            status = status ? status : menu_row[i][4]['resident_element_type_id']%10;
            str += '<tr title="' + menu_row[i][4]['resident_element_type_id'] + ',' + menu_row[i][4]['id'] + '"><td class="del" onclick="fnRemoveNode(this)"><span>X</span></td><td class="info" onclick="fnExtendNodeMenu(this, \'update\')"><span>' + menu_row[i][4]['name'] + '</span></td><td class="del" onclick="fnMirrorImg(this)"><span>R</span></td></tr>';
        }
        str = '<tr class="change_status" title="' + status + '"><td class="add" colspan="3" onclick="fnChangeStatus(this)" title="' + status + '"><span>' + (status==2?'Free to Public':'Public to Free') + '</span></td></tr>' + str;
        str += '<tr><td class="add" colspan="3" onclick="fnExtendNodeMenu(this, \'add\')"><span>+</span></td></tr>';
        str = '<table class="menu" cellpadding="0" cellspacing="0" border="0">' + str + '</table>';
        return str;
    }
    
    function fnChangeStatus(el){
        fnCloseAdd();
        var type = $(el).attr('title') == 2 ? 1 : 2;
        var main_container = $(el).closest('div.map_container');
        var mapimg_list = main_container.children('img.img');
        var menu_row = [];
        mapimg_list.each(function(){
            var ary_info = this.alt.split(',');
            var x = ary_info[0];
            var y = ary_info[1];
            var z_index = parseInt(Math.floor(ary_info[2]/10)*10 + type);
            var element_id = ary_info[3];
            var layout_list = element_list[type];
            var current_img = $(this);
            
            var element_index = 0;
            for(element_list_i in element_list[$(el).attr('title')][ary_info[2]]['resident_element_list']){
                if(element_id == element_list[$(el).attr('title')][ary_info[2]]['resident_element_list'][element_list_i]['id']){
                    break;
                }
                element_index++;
            }
            var blnReplaced = false;
            if(typeof layout_list[z_index] != 'undefined'){
                var real_index = 0;
                var alt = '';
                var src = '';
                var css = '';
                var node_settings = {};
                for(i in layout_list[z_index]['resident_element_list']){
                    var node_settings = layout_list[z_index]['resident_element_list'][i];
                    alt = x + ',' + y + ',' + z_index + ',' + node_settings['id'] + ',' + ary_info[4];
                    src = www + 'images/admin/' + node_settings['file_name'];
                    css = node_settings['resident_element_type_id'];
                    blnReplaced = true;
                    if(element_index==real_index){
                        break;
                    }
                    real_index++;
                }
                current_img.attr('alt', alt);
                current_img.attr('src', src);
                current_img.css('z-index', css);
                imgRotate(current_img);
                menu_row.push([x, y, element_id, css, node_settings]);
            }
            
            if(!blnReplaced){
                current_img.remove();
            }
           //console.log(ary_info);
        });
        var td_node = $(el).closest('td.map_matrix');
        td_node.removeClass();
        td_node.addClass('map_matrix');
        td_node.addClass('status_' + type);
        $(el).closest('table.menu').remove();
        main_container.append(fnAddMenu(menu_row));
        fnDisplayInfo();
    }
    function fnRemoveNode(el){
        fnCloseAdd();
        var main_container = $(el).closest('div.map_container');
        var menu_tr = $(el).closest('tr');
        var mapimg_node = main_container.children('img.img[alt$=",' + menu_tr.attr('title') + ',0"]');
        if(mapimg_node.size() < 1){
            mapimg_node = main_container.children('img.img[alt$=",' + menu_tr.attr('title') + ',1"]');
        }
        var ary = mapimg_node.attr('alt').split(',');
        ary.pop();
        mapimg_node.siblings('.alt[alt^=' + ary.join(',') + ']').each(function(){
            $(this).remove();
        });
        mapimg_node.remove();
        menu_tr.remove();
        fnDisplayInfo();
    }
    function fnExtendNodeMenu(el, mode){
        fnCloseAdd();
        var main_container = $(el).closest('div.map_container');
        var menu_table = $(el).closest('table.menu');
        var menu_tr_change_status = $(el).closest('tr').prevAll('tr.change_status');
        var status = menu_tr_change_status.attr('title');
        
        var menu_tr = [];
        switch(mode){
            case 'add':
            menu_tr = $(el).closest('tr').prevAll('tr[title*=","]');
            break;
            case 'update':
            menu_tr = $(el).closest('tr');
            break;
        }
        
        var z_index_list = [];
        var id_list = [];
        menu_tr.each(function(){
            var ary = $(this).attr('title').split(',');
            z_index_list.push(ary[0]);
            id_list.push(ary[1]);
        });
        var str = '';
        for(i in element_list[status]){
            if(
                (jQuery.inArray(i, z_index_list) == -1 && mode=='add')
            ||  (jQuery.inArray(i, z_index_list) > -1 && mode=='update')
            ){
                for(n in element_list[status][i]['resident_element_list']){
                    var nodesettings = element_list[status][i]['resident_element_list'][n];
                    str += '<tr title="' + nodesettings['resident_element_type_id'] + ',' + nodesettings['id'] + '"><td class="add_in" onclick="fnAddNode(this, \'' + mode + '\')">' + (nodesettings['file_name'].indexOf('blank.png') == 0 ? nodesettings['name'] : '<img src="' + www + 'images/admin/' + nodesettings['file_name'] + '" title="' + nodesettings['name'] + '" width="auto" height="auto"/>') + '</td></td></tr>';
                }
            }
        }
        if(str==''){
            str = '<tr class="no_image"><td class="close" onclick="fnCloseAdd()"><span>No Image Available Close Menu</span></td></tr>' + str;
        }else{
            str = '<tr><td class="close" onclick="fnCloseAdd()"><span>Close Menu</span></td></tr>' + str;
        }
        
        str = '<table class="add_menu" cellpadding="0" cellspacing="0" border="0">' + str + '</table>';
        main_container.children('table.add_menu');
        main_container.append(str)
        return;
    }
    function fnCloseAdd(){
        $('table.add_menu').each(function(){
            $(this).remove();
        });
    }
    function fnAddNode(el, mode){
        var menu_tr = $(el).closest('tr');
        var ary = menu_tr.attr('title').split(',');
        var main_container = $(el).closest('div.map_container');
        var last_img = main_container.children('img.img:last');
        var ary_org = last_img.attr('alt').split(',');
        var status = ary_org[2]%10;
        
        switch(mode){
            case 'add':
            last_img.after(fnImg(ary_org[0],ary_org[1], ary[1], ary[0], element_list[status][ary[0]]['resident_element_list'][ary[1]]['file_name'], 0));
            break;
            case 'update':
            main_container.children('img.alt[alt^=' + ary_org[0] + ',' + ary_org[1] + ',' + ary[0] + ',]').remove();
            main_container.children('img.img[alt^=' + ary_org[0] + ',' + ary_org[1] + ',' + ary[0] + ',]').replaceWith(fnImg(ary_org[0],ary_org[1], ary[1], ary[0], element_list[status][ary[0]]['resident_element_list'][ary[1]]['file_name'], ary_org[4]));
            break;
        }
        
        var all_img = main_container.children('img.img');
        var menu_row = [];
        all_img.each(function(){
            var current_img = $(this);
            var setting = current_img.attr('alt').split(',');
            imgRotate(current_img);
            menu_row.push([setting[0], setting[1], setting[3], setting[2], element_list[status][setting[2]]['resident_element_list'][setting[3]]]);
        });
        
        fnCloseAdd();
        
        main_container.children('table.menu').remove();
        main_container.append(fnAddMenu(menu_row));
        fnDisplayInfo();
    }
    function fnMirrorImg(el){
        fnCloseAdd();
        var main_container = $(el).closest('div.map_container');
        var menu_tr = $(el).closest('tr');
        var mapimg_node = main_container.children('img.img[alt$=",' + menu_tr.attr('title') + ',0"]');
        if(mapimg_node.size() < 1){
            mapimg_node = main_container.children('img.img[alt$=",' + menu_tr.attr('title') + ',1"]');
        }
        var ary = mapimg_node.attr('alt').split(',');
        if(typeof ary[4] == 'undefined'){
            ary[4] = 0;
        }else{
            ary[4] = parseInt(ary[4]) ^ 1;
        }
        mapimg_node.attr('alt', ary.join(','));
        
        ary.pop();
        mapimg_node.siblings('.alt[alt^=' + ary.join(',') + ']').each(function(){
            var ary_alt = $(this).attr('alt').split(',');
            ary_alt[4] = parseInt(ary_alt[4]) ^ 1;
            $(this).attr('alt', ary_alt.join(','));
        });
        imgRotate(mapimg_node);
    }
    function fnDisplayInfo(){
        var public_node_list = $('td.status_1');
        var free_node_list = $('td.status_2');
        var str = '';
        
        var objCount = {};
        for(var i=0; i < public_node_list.length; i++){
            
            var img_list = $(public_node_list[i]).find('img.img');
            for(var n=0; n < img_list.length; n++){
                var img = $(img_list[n]);
                objCount[element_list[1][img.css('z-index')]['name']] = typeof objCount[element_list[1][img.css('z-index')]['name']] == 'undefined' ? 1 : objCount[element_list[1][img.css('z-index')]['name']] + 1;
            }
        }
        
        var row = [];
        for(var i in objCount){
            row.push('<li><strong>' + i + ':</strong> ' + objCount[i] + '</li>')
        }
        var str_row = '';
        if(row.length > 0){
            str_row = '<ul class="info_detail">' + row.join('') + '</ul>';
        }
        str += '<li><strong>Public:</strong> ' + public_node_list.size() + str_row + '</li>';
        str += '<li><strong>Free:</strong> ' + free_node_list.size() + '</li>';
        $('ul.display_info').empty();
        $('ul.display_info').append(str);
    }
/*{/literal}*/
/* ]]>*/
</script>
<h2>Habitat Homes for Hope - Map Editor <sup>version 1.0</sup></h2>
<form id="populate" name="populate" action="" method="post">
    <input type="hidden" name="map_data" id="map_data" />
    <input type="hidden" name="neighbourhood_id" id="neighbourhood_id" />
    <input type="hidden" name="section" id="section" value="map_editor" />
    <dir  style="float: left; width: 49%; padding: 1px;">
        <table cellpadding="0" cellspacing="2" border="0">
            <tr>
                <th align="left" width="70">Template</th>
                <td width="120">
                    <select id="neighbourhood_list" name="neighbourhood_list">
                    <option value="">New</option>
                    {foreach from=$ary_neighbourhood_list item=ary_neighbourhood}
                    <option value="{$ary_neighbourhood.id}" {if $ary_neighbourhood.id == $neighbourhood_id}selected="selected"{/if}>{$ary_neighbourhood.name}</option>
                    {/foreach}
                    </select>
                </td>
                <th align="left" width="70">Name</th>
                <td width="120"><input type="text" name="neighbourhood_name" value="" id="neighbourhood_name" /></td>
            </tr>
            <tr {if !$neighbourhood_id}style="display:none"{/if}>
                <th align="left">Action Mode</th>
                <td class="for_select" colspan="3">
                    <select id="status_mode" name="status_mode">
                        <option value="1">Duplicate</option>
                        <option value="2">Update</option>
                    </select>
                </td>
            </tr>
            <tr {if $neighbourhood_id}style="display:none"{/if}>
                <th align="left" valign="top"><input type="button" name="populate" value="populate" id="button_populate" /></th>
                <td class="for_new">
                    <label><input type="text" name="x" value="10" id="x" maxlength="2" size="2" /></label><label><input type="text" name="y" value="10" id="y" maxlength="2" size="2" /></label> X|Y
                </td>
                <td class="for_new" colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <label id="neighbourhood_status">
                        <input type="checkbox" id="published" name="published" value="1" /> Publish the Template
                    </label>
                </td>
                <td colspan="2" align="right">
                    <input type="button" name="Submit" value="Submit" id="submit_button" />
                </td>
            </tr>
        </table>
    </dir>
    <dir  style="float: left; width: 49%; padding: 1px;">
        <iframe src="{$CFG->www}admin/clear_setting_cache.php" width="400" height="40" style="float: left; border: 0 none; overflow: hidden"></iframe>
    </dir>
    <div class="clear"><br class="accessibility" /></div>
</form>
<form id="map" name="map" action="" method="post">
    <ul class="display_info">
    </ul>
    <table id="map_table" align="center" class="map_table" cellpadding="0" cellspacing="2" border="1">
    </table>
</form>

    </div>
</div>
{/if}