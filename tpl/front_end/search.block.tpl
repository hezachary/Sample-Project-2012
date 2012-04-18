                        
            {foreach from=$ary_profile item=ary_profile_item}
                    <div class="box">
                        {assign var='entry_data' value=$ary_profile_item.entry_data|@unserialize}
                        {assign var='image_width' value=172}
                        {if $CFG->dirroot|@mergeString:'images/thumbs/':$entry_data.entry_id:'_1.jpg'|@file_exists}
                            {assign var='file_path' value=$CFG->dirroot|@mergeString:'images/thumbs/':$entry_data.entry_id:'_1.jpg'}
                            {assign var='ary_file_size' value=$file_path|@getimagesize}
                            {math equation='oh * nw / ow' ow=$ary_file_size[0] oh=$ary_file_size[1] nw=$image_width assign='image_height' format='%u'}
                            {assign var='image_url' value=$CFG->www|@mergeString:'images/thumbs/':$entry_data.entry_id:'_1.jpg'}
                        {else}
                            {assign var='image_height' value='160'}
                            {assign var='image_url' value=$CFG->www|@mergeString:'images/sample_thumb.jpg'}
                        {/if}
                        <a href="{$CFG->www}detail/{$ary_valid_input|@fnBuildRewriteUrl:'id':$ary_profile_item.id}"><img src="{$image_url}" width="{$image_width}" height="{$image_height}" alt="{$ary_profile_item.name|strip_tags:false|escape:"htmlall"}" /><span class="file_type {$ary_profile_item.file_type}"><span class="accessibility">{$ary_profile_item.file_type}</span></span></a>
                        <p class="award {$ary_profile_item.award}" title="{$ary_profile_item.award}"><span>{$ary_profile_item.award}</span></p>
                        {assign var=ary_name_list value=';'|@explode:$ary_profile_item.user_name}
                        {if $ary_name_list|@sizeof > 4}
                            {assign var=ary_name_list value=$ary_name_list|@array_slice:0:3}
                            {assign var=tmp value=$ary_name_list|@array_push:'...'}
                        {else}
                            {assign var=ary_name_list value=$ary_name_list|@array_slice:0:4}
                        {/if}
                        
                        <h2 class="member_list"><a href="{$CFG->www}detail/{$ary_valid_input|@fnBuildRewriteUrl:'id':$ary_profile_item.id}">{'<br/>'|@implode:$ary_name_list|replace:'+':' '|replace:'###':';'}</a></h2>
                        <div class="clear"><br class="accessibility"/></div>
                        <p class="subject">{$entry_data.import.Category|regex_replace:'/^\([^\)]+\)/':''|replace:'_':' '|replace:'+':' '|capitalize}{*$ary_profile_item.category_name|capitalize*}</p>
                        <p class="desc"><small>{$ary_profile_item.entry_name|replace:'+':' '|truncate:30:'...'}</small></p>
                        {*$ary_profile_item|@_debug*}
                    </div>
            {/foreach}