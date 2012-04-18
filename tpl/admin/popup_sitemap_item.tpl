
				{if $id_tree|@is_array && $id_tree|@sizeof}
				<ol class="site_map" type="1">
				{foreach name=sub_menu from=$id_tree key=id item=sub_tree}
					<li class="{if $menu_content[$id].self}self{/if}">
						[
						{if !$menu_content[$id].content_selected}
						<a href="javascript:fnSelectContent('{$node_id}', '{$id}')">SELECT</a>
						{else}
						<span>SELECTED</span>
						{/if}
						]
						<span>{$menu_content[$id].title}[<a href="javascript:void(0);" onmouseover="fnTips(this, '{$menu_content[$id].short_desc|@htmlentities}')" onmouseout="fnTips(this)">?</a>]</span>
						{if $sub_tree|@is_array && $sub_tree|sizeof}
							{include file='popup_sitemap_item.tpl' id_tree=$sub_tree}
						{/if}
					</li>
				{/foreach}
				</ol>
				{/if}