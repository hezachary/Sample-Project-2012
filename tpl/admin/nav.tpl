
    <ul>
      <li class="{if $CFG->section==''}selected{/if}"><a href="?">Home</a></li>
      <li class="{if $CFG->section=='register'}selected{/if}"><a href="?section=register">Maintain Register(s)</a></li>
	{foreach name=nav_list from=$PAGE key=section_item item=obj_setting}
      <li class="{if $CFG->section==$section_item}selected{/if}"><a href="?section={$section_item}">{$obj_setting->section_name}</a></li>
	{/foreach}
    </ul>