
			<div style="width: 800px; margin: 10px 5px;">
				<form id="translation_form_{$register_id}" name="register_form_{$register_id}" action="" method="POST">
					<input type="hidden" value="{$register_id}" name="id"/>
					<input type="hidden" name="field_list"  value="{','|@implode:$field_list}" />
					{if $msg|@is_array && $msg|@sizeof > 0}
					<ul class="warning"> 
						{foreach name=msg from=$msg item=msg_item}
							<li>{$msg_item}</li>
						{/foreach}
				    </ul>
				    {/if}
					<ul class="content_edit mini">
						{foreach name=smtFieldList from=$field_list item=strFiledName}
						<li>
							<label for="{$strFiledName}">{$strFiledName|capitalize}:</label>
							{if $strFiledName=='state' }
							<select name="{$strFiledName}">
								{html_options options=$state_list|@fnRetrieveForOption selected=$obj_register->$strFiledName}
							</select>
							{elseif $strFiledName=='reminder' || $strFiledName=='promotions' }
							<select name="{$strFiledName}">
								{html_options options=$accept_list|@fnRetrieveForOption selected=$obj_register->$strFiledName}
							</select>
							{elseif $strFiledName=='daily_prizes' || $strFiledName=='cashback'}
							<select name="{$strFiledName}">
								{html_options options=$agreement_list|@fnRetrieveForOption selected=$obj_register->$strFiledName}
							</select>
							{elseif $strFiledName=='status' }
							<select name="{$strFiledName}">
								{html_options options=$status_list|@fnRetrieveForOption selected=$obj_register->$strFiledName}
							</select>
							{else}
							<input type="text" name="{$strFiledName}" value="{$obj_register->$strFiledName}" />
							{/if}
							<br/>
						</li>
						{/foreach}
						<li>
							<label for="save" class="accessibility">Save:</label>
							<input name="save" type="button" value="save" onclick="fnSubmitForm(this.form)" />
							<br/>
						</li>
					</ul>
				</form>
			</div>
			