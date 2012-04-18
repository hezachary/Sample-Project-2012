function fnReloadField(field_name){
	//ajax publish page with id
	var data = 'mode=home&field_name='+field_name;
	fnAjaxWithInfo(data, 'fnSetField');
}
function fnSetField(data){
	//ajax publish page with id
	if(data && (typeof data != 'undefined')){
        if(data.status=='true'){
            //console.log(data);
            var node = $('#home_'+data.id);
            node.replaceWith(data.html);
        }
	}
}
function fnPublishContent(menu_id){
	//ajax publish page with id
	var data = 'mode=publish&mid='+menu_id;
	fnAjaxWithInfo(data, 'fnSetPublish');
}

function fnSetPublish(data){
	//ajax publish page with id
	if(data && (typeof data != 'undefined')){
		var text = data.status=='true'?'Unpublish Page':'Publish Page';
		var filename = data.status=='true'?'unpublish':'publish';
		var node = $('#publish_status_button_'+data.id+' img')[0];
		node.src = www + "images/" + filename.toLowerCase() + '.gif';
		node.alt = text;
		node.title = text;
		fnIconSwitch(null, '#publish_status_button_'+data.id);
	}
}

function fnRemoveImage(menu_id){
	//ajax publish page with id
	var data = 'mode=image&mid='+menu_id;
	fnAjaxWithInfo(data, 'fnSetImage');
}

function fnSetImage(data){
	//ajax publish page with id
	if(data && (typeof data != 'undefined')){
		$('#new_file_picture').css({display:""});
		$('#old_file_picture').remove();
	}
}

function fnEditLanguageContent(menu_id, language_id){
	//Popup a div let user to select a page from tree list
	var data = 'mode=maintain_translation&mid='+menu_id+'&language_id='+language_id;
	fnAjaxWithInfo(data, 'fnDisplayPanel');
}

function fnRemoveShortDesc(page_id, order_item_id){
	//ajax remove short desc with page id and related page id from pie_content_order table, also update the order number
	var data = 'mode=remove_short_desc&pid='+page_id+'&data='+order_item_id;
	fnAjaxWithInfo(data, 'fnRemoveShortDescRow');
}

function fnRemoveShortDescRow(data){
	if(data && (typeof data != 'undefined')){
		$('#content_order_'+data.id).remove();
	}
}

function fnAddShortDesc(node_id, page_id){
	//Popup a div let user to select a page from tree list
	var data = 'mode=retrieve_short_desc_list&pid='+page_id+'&data='+node_id;
	fnAjaxWithInfo(data, 'fnDisplayPanel');

}

function fnSelectContent(node_id, page_id){
	fnClosePanel();
	var data = 'mode=insert_short_desc&pid='+page_id+'&data='+node_id;
	fnAjaxWithInfo(data, 'fnAddContentRow');
}

function fnAddContentRow(data){
	if(data && (typeof data != 'undefined')){
		$('#content_list_ul').append(data.html);
		fnIconSwitch(null, '#content_list_ul ');
	}
}

function fnDisplayPanel(data){
	if($('#ajax_display').length < 1){
		$('body').append('<div id="ajax_display" class="top_display"></div>');
	}

	if($('#ajax_display_outter').length < 1){
		$('#ajax_display').before('<div id="ajax_display_outter" class="screen_block"></div>');
	}
	
	var height = $(window).height();
	var scrollTop = $(window).scrollTop();
	var width = $('body').width();
	var content_width = parseInt($(data.html)[0].style.width) + parseInt($(data.html)[0].style.marginLeft);
	$('#ajax_display').css({'left':((width - content_width)/2)+'px', 'top':(scrollTop + 100)+'px'});
	$('#ajax_display_outter').css({'width':width+'px', 'height':$('body').height()+'px', 'z-index': '1000'});
	$('#ajax_display').empty();
	$('#ajax_display').append('<p class="close_button">[<a href="javascript:fnClosePanel()">X<a>]</p>');
	$('#ajax_display').append(data.html);
	$('#ajax_display_outter').fadeTo("fast", 0.75, function(){
		$('#ajax_display').show();
	});
	$('#ajax_display_outter').show();
}

function fnClosePanel(){
	$('#ajax_display').empty();
	$('#ajax_display').hide();
	$('#ajax_display_outter').hide();
}

function fnHidePanel(){
	$('#ajax_display').hide();
	$('#ajax_display_outter').hide();
}

function fnShowPanel(){
	$('#ajax_display').show();
	$('#ajax_display_outter').show();
}

function fnSaveContentOrder(page_id){
	var send_back = fnPopulateListId('content_list_ul');
	//ajax send back the "send_back"
	var data = 'mode=content_order&pid='+page_id+'&data='+send_back;
	fnAjaxWithInfo(data, '');
}

function fnSaveMenuOrder(page_id){
	var send_back = fnPopulateListId('sub_list_ul');
	//ajax send back the "send_back"
	var data = 'mode=menu_order&pid='+page_id+'&data='+send_back;
	fnAjaxWithInfo(data, '');
}

function fnAjaxWithInfo(data, function_name){
	if($('#ajax_msg').length < 1){
		$('body').append('<div id="ajax_msg" class="top_msg"></div>');
	}

	if($('#ajax_msg_outter').length < 1){
		$('#ajax_msg').before('<div id="ajax_msg_outter" class="screen_block"></div>');
	}
	
	var height = $(window).height();
	var scrollTop = $(window).scrollTop();
	var width = $('body').width();
	$('#ajax_msg').css({'left':((width - 300)/2)+'px', 'top':(scrollTop + 100)+'px'});
	$('#ajax_msg_outter').css({'width':width+'px', 'height':$('body').height()+'px', 'z-index': '2000'});
	$('#ajax_msg').empty();
	$('#ajax_msg').append('Sending Ajax...');
	$('#ajax_msg_outter').fadeTo("fast", 0.75, function(){
		$('#ajax_msg').show();
		$.ajax({
			type: "POST",
			url: "admin_ajax.php",
			data: data,
			async: false,
			dataType: 'json',
			success: function(msg){
				$('#ajax_msg').append(msg.text);
				$('#ajax_msg_outter').fadeTo(500, 0, function(){
					$('#ajax_msg').hide();
					$('#ajax_msg_outter').hide();
					$('#ajax_msg').empty();
					if(function_name){
					   if(typeof function_name == 'string'){
					       eval(function_name+'(msg.data);');
					   }else{
					       function_name(msg.data);
					   }
					}
				});
			}
		});
	});
	$('#ajax_msg_outter').show();

}

function fnPopulateListId(dom_id){
	var node_list = $('#'+dom_id+' > li');
	var node_length = node_list.length;
	var ary_order_list = new Array();
	var count = 0;
	for(var  i = 0; i < node_length; i++){
		var id = node_list[i].id.toString().substring(node_list[i].id.toString().lastIndexOf('_') + 1);

		if(!isNaN(id)){
			ary_order_list.push(count + '.' + id);
			count ++;
		}
	}
	return ary_order_list.join(',');
}

function fnTips(node){
	if(arguments.length > 1){
		$(node).after('<span class="tips_outter"><span class="tips_inner"><span class="tips_self">'+arguments[1]+'</span></span></span>');
	}else{
		$(node).next('span.tips_outter').remove();
	}
}

function fnOperateSwitch(val){
	$('div.tool_bar ul.operate_list > li > a.page_'+val).toggleClass('selected');
	
	var node;
	var hash;
	switch(val){
		case 'detail':
			node = $('body.admin div.content > div.page_content');
			node.toggle();
			if(node.css('display') != 'none')hash = "page_detail";
			break;
		case 'language':
			node = $('body.admin div.content > div.language_content');
			node.toggle();
			if(node.css('display') != 'none')hash = "language_content";
			break;
		case 'sub':
			node = $('body.admin div.content > div.sub_list');
			node.toggle();
			if(node.css('display') != 'none')hash = "page_sub_list";
			break;
	}
	fnFunctionSwitch($('div.tool_bar ul.function_list').data('val'));
	window.location.hash = hash;
}

function fnFunctionSwitch(val){
	
	switch(val){
		case 'logout':
			window.location = "admin_login.php?logout=logout";
			return;
			break;
		case 'text':
		case 'icon':
		case 'both':
			break;
		default:
			val = $.cookie('menu');
			val = val?val:'both';
			break;
	}
	
	$('div.tool_bar ul.function_list > li > a.selected').removeClass('selected');
	$('div.tool_bar ul.function_list > li > a.'+val).addClass('selected');
	val = fnIconSwitch(val);
	$('div.tool_bar ul.function_list').data('val', val);
	$.cookie('menu', val);
}

function fnIconSwitch(){
	var val = arguments[0]?arguments[0]:$('div.tool_bar ul.function_list').data('val');
	var preDefine = arguments[1]?arguments[1]:'';
	$(preDefine + '.tool_icon img').each(function(i){
		var span = $(this).next('span');
		if(span.length < 1){
			$(this).after('<span>'+$(this).attr('title')+'</span>');
		}else{
			span.text($(this).attr('title'));
		}
	});
	
	switch(val){
		case 'logout':
			window.location = "admin_login.php?logout=logout";
			return;
			break;
		case 'text':
			$(preDefine + '.tool_icon img').addClass('invisible', false);
			$(preDefine + '.tool_icon span').removeClass('invisible', true);
			break;
		case 'icon':
			$(preDefine + '.tool_icon img').removeClass('invisible');
			$(preDefine + '.tool_icon img').each(function(i){
				$(this).next('span').addClass('invisible', false);
			});
			break;
		case 'both':
		default:
			$(preDefine + '.tool_icon img').removeClass('invisible', false);
			$(preDefine + '.tool_icon span').removeClass('invisible', false);
			val = 'both';
			break;
	}
	
	
	$(preDefine + '.tool_icon').parent().each(function(i){
		var tool_icon = $(this).children('.tool_icon');
		var gaps = (val=='text'||tool_icon.children('img').length==0)&&tool_icon.children('span.invisible').length==0?0:0;
		if(tool_icon.length > 0){
			var height = tool_icon.outerHeight();
			$(this).css({'height':height+gaps+'px'});
		}
		if(tool_icon.length > 1){
			$(this).css({'margin':'2px 0'});
		}
	});
	return val;
}


function fnPageJump(value){
	alert(value);
	alert(fnQueryPopulate('page', value));
	//window.location.search = fnQueryPopulate('page', value);
}