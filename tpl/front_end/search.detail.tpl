
            <div class="photo-info main" id="photo_info${$ary_file.entry_id}${if $file_type == 'url'}{$ary_file.entry_url_id}{else}{$ary_file.entry_file_id}{/if}">

                    {if  $file_type == 'flv' || $file_type == 'avi' || $file_type == 'mov' || $file_type == 'mp4' ||  $file_type == 'mpg' ||  $file_type == 'mpeg' ||  $file_type == 'wmv'}
                       {assign var='view_file_name' value=$ary_file.entry_id|fnRetrieveFileName:$ary_file.name:'video'}
                    {elseif $file_type == 'gif' || $file_type == 'jpg' || $file_type == 'jpeg' ||  $file_type == 'png'}
                        {assign var='view_file_name' value=$ary_file.entry_id|fnRetrieveFileName:$ary_file.name:'web'}
                    {else}
					    {assign var='view_file_name' value=$ary_file.name}
                    {/if}
                    <div align="center">
					{if $file_type == 'gif' || $file_type == 'jpg' || $file_type == 'jpeg' ||  $file_type == 'png'}
                        <p id="entry_file_{$ary_file.entry_id}_{$ary_file.entry_file_id}" align="center"><img src="{$CFG->wwwroot}images/entries/{$ary_file.entry_id}_{$ary_file.entry_file_id}_1.jpg" alt="{$file_type}" width="685" /></p>
					{elseif $file_type == 'flv' || $file_type == 'avi' || $file_type == 'mov' || $file_type == 'mp4' ||  $file_type == 'mpg' ||  $file_type == 'mpeg' ||  $file_type == 'wmv'}
                        <p id="entry_file_{$ary_file.entry_id}_{$ary_file.entry_file_id}" align="center"></p>
                        <script>yg_tools.loadVideo('{$ary_file.entry_id}_{$ary_file.entry_file_id}', '{$ary_file.location|replace:'https:':'http:'}/{$view_file_name}', 685, 514, '{$CFG->wwwroot}images/entries/{$ary_file.entry_id}_{$ary_file.entry_file_id}_1.jpg');</script>
					{elseif $file_type == 'wav'}
                        <p id="entry_file_{$ary_file.entry_id}_{$ary_file.entry_file_id}" align="center"></p>
                        <script>yg_tools.loadAudio('{$ary_file.entry_id}_{$ary_file.entry_file_id}', '{$ary_file.location|replace:'https:':'http:'}/{$view_file_name}', 685, 24);</script>
					{elseif $file_type == 'mp3' ||  $file_type == 'wma'}
                        <p id="entry_file_{$ary_file.entry_id}_{$ary_file.entry_file_id}" align="center"></p>
                        <script>yg_tools.loadAudio('{$ary_file.entry_id}_{$ary_file.entry_file_id}', '{$ary_file.location|replace:'https:':'http:'}/{$view_file_name}', 685, 70);</script>
					{elseif $file_type == 'swf'}
                        <p id="entry_file_{$ary_file.entry_id}_{$ary_file.entry_file_id}" align="center"></p>
                        <script>yg_tools.loadFlash('{$ary_file.entry_id}_{$ary_file.entry_file_id}', '{$ary_file.location|replace:'https:':'http:'}/{$view_file_name}', 685, 514);</script>
					{elseif $file_type == 'doc' || $file_type == 'docx' || $file_type == 'txt' || $file_type == 'pdf'}
                        <p id="entry_file_{$ary_file.entry_id}_{$ary_file.entry_file_id}" align="center">
                            <a href="{$ary_file.location|replace:'https:':'http:'}/{$view_file_name}" target="_blank"><img src="{$CFG->wwwroot}images/pdf_icon.jpg" alt="Please use your mouse right Click here and Choose [Save Link As ...]" border="0" /></a>
                        </p>
					{elseif $file_type == 'url'}
                        <p id="entry_file_{$ary_file.entry_id}_{$ary_file.entry_url_id}" align="center">
                            <a href="{if $view_file_name|@substr:0:4 == 'http'}{$view_file_name}{else}http://{$view_file_name}{/if}" target="_blank"><img width="685" src="{$CFG->www}images/entries/{$ary_file.entry_id}_{$ary_file.entry_url_id}_1.jpg" border="0" /></a>
                        </p>
					{/if}
                    </div>
            </div>
            <div class="gap"><hr class="accessibility" /></div>
            