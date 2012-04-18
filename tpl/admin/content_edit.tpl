	{php}
		/*
		//pie_page multi inner join pie_page
		$ary_position_list[] = array('id'=>$id, 'name'=>$name);
		
		//pie_page
		$ary_page_content = array(
									'id'=>$value['id'], 
									'title'=>$value['title'], 
									'short_desc'=>$value['short_desc'], 
									'content'=>$value['content'], 
									'quote'=>$value['quote'], 
									'display_mode'=>$value['display_mode'], 
									'publish'=>$value['publish'], 
									'title_image_left'=>basename($value['title_image_left']), 
									'title_image_right'=>basename($value['title_image_right']), 
									'extension'=>$value['extension'], 
									'extension_path'=>$value['extension_path'], 
									);
		//pie_page, pie_page_tag, pie_tag : inner join
		$ary_tag_list[] = array('name'=>$value['name'],);
		*/
		
	{/php}
	<!-- TinyMCE -->
	<script type="text/javascript" src="../js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
	{literal}
		//tinyMCE.init
		({
			// General options
			mode : "textareas",
			theme : "advanced",
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",
	
			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : false,
			file_browser_callback : "fileBrowserCallBack",
	
			// Example content CSS (should be your site CSS)
			content_css : "../css/editor.css",
	
			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",
	
			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
		
		function fileBrowserCallBack(field_name, url, type, win) {
			var connector = "{/literal}{$CFG->www}{literal}lib/filemanager/browser.html?Connector=connectors/php/connector.php";
			var enableAutoTypeSelection = true;
			
			var cType;
			tinymcpuk_field = field_name;
			tinymcpuk = win;
			
			switch (type) {
				case "image":
					cType = "Image";
					break;
				case "flash":
					cType = "Flash";
					break;
				case "file":
					cType = "File";
					break;
			}
			
			if (enableAutoTypeSelection && cType) {
				connector += "&Type=" + cType;
			}
			
			window.open(connector, "tinymcpuk", "modal,width=600,height=400");
		}
	{/literal}
	</script>
	<!-- /TinyMCE -->
	{if  $USER->username}
		<div class="tool_bar">
			{if $page_content.bread_crumbs|@is_array && $page_content.bread_crumbs|@sizeof}
			<ol class="bread_crumbs">
			{section name=bread_crumbs loop=$page_content.bread_crumbs}
				<li class="{if $smarty.section.bread_crumbs.last}end{/if}">{if $smarty.section.bread_crumbs.first}Your position: {/if}<a href="admin_index.php?section=content&pid={$page_content.bread_crumbs[bread_crumbs].id}">{$page_content.bread_crumbs[bread_crumbs].title}</a></li>
			{/section}
			</ol>
			{/if}
			<ul class="function_list">
				<li><a class="both" href="javascript:fnFunctionSwitch('both')">Icon + Text</a></li>
				<li><a class="text" href="javascript:fnFunctionSwitch('text')">Text Only</a></li>
				<li><a class="icon" href="javascript:fnFunctionSwitch('icon')">Icon Only</a></li>
				<li><a class="logout" href="javascript:fnFunctionSwitch('logout')">Logout &raquo;</a></li>
			</ul>
			<div class="clear"><br class="accessibility"/></div>
		</div>
	{/if}
  <div class="content custom_field">
    <div class="page_content_edit custom_field">
	  <form name="content_maintain" action="" method="post" enctype="multipart/form-data">
	  	<input name="pid" id="pid" value="{if $page_content.mode==add}{$page_content.obj_page_content->parent_id}{else}{$page_content.obj_page_content->id}{/if}" type="hidden" />
	  	<input name="mode" id="mode" value="{$page_content.mode}" type="hidden" />
	  	<input id="save" name="save" type="hidden" value="Save" />
	  <h2>{if $page_content.mode==add}Add Page{else}Page Details{/if}</h2>
	    <div class="tools">
		       <ul>
		        <li><a class="tool_icon" href="javascript:document.content_maintain.submit();"><span>Save</span></a></li>
		      </ul>
			  <div class="clear"><br class="accessibility"/></div>
		</div>
	  {if $page_content.obj_page_content->extension=='true'}<p>Extension Mode, all the settings below may not working.</p>{/if}
	  {if $msg|@is_array && $msg|@sizeof > 0}
	  <ul class="warning"> 
		{foreach name=msg from=$msg item=msg_item}
			<li>{$msg_item}</li>
		{/foreach}
      </ul>
      {/if}
      <ul class="content_edit">
        <li>
        	<label for="title">Title:</label>
        	<input type="text" value="{$page_content.obj_page_content->title}" name="title" id="title" maxlength="255" />
        	<br/>
        </li>
        <li>
        	<p>Short description:</p>
        	<textarea name="short_desc" id="short_desc" rows="5" cols="80">{$page_content.obj_page_content->short_desc}</textarea>
        </li>
        <li>
        	<p class="title">1st Content:</p>
        	<p class="check"><label for="main_content_0">main area: </label><input type="radio" name="main_content" id="main_content_0" value="0" {if $page_content.obj_page_content->aryContentList[0].main=='true'}checked="checked"{/if}></p>
        	<textarea name="content[]" id="content_1" rows="15" cols="80">{$page_content.obj_page_content->aryContentList[0].content}</textarea>
        </li>
        <li>
        	<p class="title">2nd Content: </p>
        	<p class="check><label for="main_content_1">main area: </label><input type="radio" name="main_content" id="main_content_1" value="1" {if $page_content.obj_page_content->aryContentList[1].main=='true'}checked="checked"{/if}></p>
        	<textarea name="content[]" id="content_2" rows="15" cols="80">{$page_content.obj_page_content->aryContentList[1].content}</textarea>
        </li>
        <li>
        	<p class="title">3rd Content: </p>
        	<p class="check"><label for="main_content_2">main area: </label><input type="radio" name="main_content" id="main_content_2" value="2" {if $page_content.obj_page_content->aryContentList[2].main=='true'}checked="checked"{/if}></p>
        	<textarea name="content[]" id="content_3" rows="15" cols="80">{$page_content.obj_page_content->aryContentList[2].content}</textarea>
        </li>
        <li>
        	<p>Quote: (<span class="warning">less than 200 characters</span>)</p>
        	<textarea name="quote" id="quote" rows="2" cols="80" maxlength="255">{$page_content.obj_page_content->quote}</textarea>
        </li>
        <li>
        	<label for="tag">Tags:</label>
        	<input type="text" value="{foreach name=tag_list from=$page_content.ary_tag_list item=ary_tag_item}{$ary_tag_item.tag}{if !$smarty.foreach.tag_list.last},{/if}{/foreach}" name="tag" id="tag" />
        	<br/>
        </li>
        <li>
        	<label for="publish">Published:</label>
			<select name="publish" id="publish">
				<option value="true" {if $page_content.obj_page_content->publish=='true'}selected="selected"{/if}>Publish</option>
				<option value="false" {if $page_content.obj_page_content->publish=='false'}selected="selected"{/if}>Unpublish</option>
			</select>
			<br/>
        <li>
        	<label for="display_mode">Display Mode:</label>
			<select name="display_mode" id="display_mode">
				<option value="self" {if $page_content.obj_page_content->display_mode==self}selected="selected"{/if}>Self Content</option>
				<option value="sub" {if $page_content.obj_page_content->display_mode==sub}selected="selected"{/if}>First Sub Page Content</option>
			</select>
			<br/>
		</li>
		<li>
			<label for="file_title_image_left">Title Image Left:</label>
			<p>
				<span id="new_file_title_image_left" style="display:{if $page_content.obj_page_content->title_image_left|@trim|@strlen > 0}none{/if}">
		        	 <input name="file_title_image_left" type="file" size="67" />
		        </span>
				<span id="old_file_title_image_left" style="display:{if $page_content.obj_page_content->title_image_left|@trim|@strlen < 1}none{/if}">
					<img align="top" src="{$CFG->www}image.php?mode=big&position=left&id={$page_content.obj_page_content->id}" alt="logo image left" />
					&nbsp;
					<input type="button" class="button" value="Delete" onclick="fnRemoveImage('{$page_content.obj_page_content->id}', 'left')" >
				</span>
			</p>
			<br/>
		</li>
		<li>
			<label for="file_title_image_right">Title Image Right:</label>
	        <p>
				<span id="new_file_title_image_right" style="display:{if $page_content.obj_page_content->title_image_right|@trim|@strlen > 0}none{/if}">
		        	 <input name="file_title_image_right" type="file" size="67" />
		        </span>
				<span id="old_file_title_image_right" style="display:{if $page_content.obj_page_content->title_image_right|@trim|@strlen < 1}none{/if}">
					<input type="button" class="button" value="Delete" onclick="fnRemoveImage('{$page_content.obj_page_content->id}', 'right')" >
					&nbsp;
					<img align="top" src="{$CFG->www}image.php?mode=big&position=right&id={$page_content.obj_page_content->id}" alt="logo image right" />
				</span>
	        </p>
			<br/>
		</li>
      </ul>
	  </form>
    </div>
  </div>