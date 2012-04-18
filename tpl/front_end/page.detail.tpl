{assign var='ary_valid_input' value=$ary_valid_input|@fnRemoveElement:'id'}
<div id="profile_detail">
    <div class="nav-bar top">
        <a href="{$CFG->www}page/{$ary_valid_input|@fnRemoveElement:'winner'|@fnBuildRewriteUrl}" class="close top">CLOSE</a>
        <div class="clear"><br class="accessibility" /></div>
    </div>
    
    <script type="text/javascript">
	var addthis_url = wwwroot + 'detail/id/{$ary_profile.id}';
	//{literal}
	var addthis_share = { url: addthis_url }
	//{/literal}
	
	</script>
    <div class="addthis_toolbox addthis_default_style ">
        <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:url="{$CFG->wwwroot}detail/id/{$ary_profile.id}"></a>
        <a class="addthis_button_tweet" twitter:url="{$CFG->wwwroot}detail/id/{$ary_profile.id}"></a>
        <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:url="{$CFG->wwwroot}detail/id/{$ary_profile.id}"></a>
        <a class="addthis_counter addthis_pill_style"></a>
    </div>
    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ygaward"> </script>
    <div id="profile">
    {assign var='entry_data' value=$ary_profile.entry_data|@unserialize}
    {foreach from=$entry_data.file item=ary_file}
        {assign var='tmp_pos' value=$ary_file.name|@strrpos:'.'}
        {assign var='file_type' value=$ary_file.name|lower|@substr:$tmp_pos|replace:'.':''}
        {include file='search.detail.tpl' ary_file=$ary_file file_type=$file_type}
    {/foreach}
    {foreach from=$entry_data.url item=ary_file}
        {include file='search.detail.tpl' ary_file=$ary_file file_type='url'}
    {/foreach}
    </div>
    <h2 class="member_list">{$ary_profile.user_name|replace:';':'<br/>'|replace:'+':' '|replace:'###':';'}</h2>
    <table class="desc" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <th>Name</th>
            <th>{if $entry_data.school}School{else}Agency{/if}</th>
        </tr>
        <tr>
            <td>{$ary_profile.entry_name|replace:'+':' '}</td>
            <td>{if $entry_data.school}{$entry_data.school.name}{else}{$entry_data.import.network}{/if}</td>
        </tr>
        <tr>
            <th>Category</th>
            <th>Country</th>
        </tr>
        <tr>
            <td>{$entry_data.import.Category|regex_replace:'/^\([^\)]+\)/':''|replace:'_':' '|replace:'+':' '|capitalize}</td>
            <td>{$entry_data.import.country|replace:'+':' '}</td>
        </tr>
        <tr>
            <th>Client</th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <td>{$entry_data.client_name}</td>
            <td><span class="award {$ary_profile.award|lower}">{$ary_profile.award|upper}</span></td>
        </tr>
    </table>
    <div class="clear"><br class="accessibility" /></div>
    {*$entry_data|@_d*}
    <div class="nav-bar bottom">
        <a href="{$CFG->www}page/{$ary_valid_input|@fnBuildRewriteUrl}" class="close bottom">CLOSE</a>
        <div class="clear"><br class="accessibility" /></div>
    </div>
</div><!--<div id="profile_detail">-->   
{*$data|@_debug*}