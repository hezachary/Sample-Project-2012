
<script type="text/javascript" src="{$CFG->www}js/page_search.js"></script>
{assign var='ary_valid_input_for_view' value=$ary_valid_input|@fnRemoveElement:'award'}
{assign var='selected_view' value='All Entries'}
{foreach from=$ary_entry_view_list item=str_entry_view key=str_view_value}
    {if $ary_valid_input.award == $str_view_value}
        {assign var='selected_view' value=$str_entry_view}
    {/if}
{/foreach}
<div class="top_bar">
    <div class="left"><span class="counts"><span class="inner">{$int_total}</span></span><span class="desc">Results</span><span class="clear"><br class="accessibility"/></span></div>
    <div class="right">
        <div class="panel panel_filter">
            <a class="subject" href="javascript:void(0)" onclick="yg_search.expandPanel(this)"><strong>View By :</strong> <span>{$selected_view}</span></a>
            <ul class="sub_panel">
                {if $ary_valid_input.award}
                <li><a href="{$CFG->www}page/{$ary_valid_input_for_view|@fnBuildRewriteUrl}">All Entries</a></li>
                {else}
                <li><span>All Entries</span></li>
                {/if}
                {foreach from=$ary_entry_view_list item=str_entry_view key=str_view_value}
                {if $ary_valid_input.award == $str_view_value}
                <li><span>{$str_entry_view}</span></li>
                {else}
                <li><a href="{$CFG->www}page/{$ary_valid_input_for_view|@fnBuildRewriteUrl:'award':$str_view_value}">{$str_entry_view}</a></li>
                {/if}
                {/foreach}
            </ul>
        </div>
    </div>
    <div class="clear"><br class="accessibility" /></div>
</div>
<div id="container">{if !$int_total && $ary_valid_input.search_field}<p class="no_results">Nothing found - try again</p>{/if}</div>
<div class="overlay" id="welcome">
    <div class="details">
		<h2>Congratulations to the all the young talent whose work is in this showcase.</h2>
		<p>To be awarded a Bullet signifies that you are talent on the rise; a leader of tomorrow or even a potential creative demi-god. This recognition means that you will “Become the Hunted” for your talent, and you deserve it.</p>
        <p>Enjoy the great work and ideas in here; and take away some inspiration.<br />If your name isn’t here yet, don’t give up trying.</p>
		<div class="hr"><hr /></div>
        <div class="left">
            <h3>2011 Professional YoungGuns of the Year:</h3>
            <p> Alexander Nowak &amp; Felix Richter<br /><br />
                Y&amp;R NewYork <br />
                Airwalk <br />
                United States</p>
        </div>
        <div class="right">
            <h3>2011 Student YoungGun of the Year:</h3>
            <p> Edi Inderbitzin , Maximilian Gebhardt &amp; Maximilian Hoch<br />
                Miami Ad School Europe<br />
                Kinder<br />
                Germany
            </p>
        </div>
        <div class="hr"><hr /></div>
        <h4>YoungGuns also recognizes the organizations that incubate, support and develop young talent:</h4>
        <div class="left">
            <h4>2011 Advertising Agency of the Year:</h4>
            <p>Leo Burnett Chicago</p>
            <h4>2011 Digital Agency of the Year:</h4>
            <p>Y&amp;R, New York</p>
            <h4>2011 Design Agency of Year:</h4>
            <p>DDB Philippines</p>
        </div>
        <div class="right">
            <h4>2011 Network of the Year:</h4>
            <p>Leo Burnett Worldwide</p>
            <h4>2011 School of the Year:  :</h4>
            <p>Miami AdSchool Europe</p>
        </div>
        <div class="hr"><hr /></div>
        <p class="extra">Before you get into the work - to get all the 2011 YoungGuns Award information download the <a class="pdf" href="javascript:void(0)" onclick="jq('#welcome').data('overlay').close();yg_tools.loadOverlay()">2011 Winners &amp; Finalists PDFs <img src="{$CFG->wwwroot}images/pdf.gif" alt="2011 Winners &amp; Finalists PDFs" /></a><br/>
        Remember almost everything great is done by youth.</p>
	</div>
</div>