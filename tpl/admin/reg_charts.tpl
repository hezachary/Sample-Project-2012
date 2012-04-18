
<script src="{$CFG->www}js/raphael.js" type="text/javascript" charset="utf-8"></script>
<script src="{$CFG->www}js/g.raphael-min.js" type="text/javascript" charset="utf-8"></script>
<script src="{$CFG->www}js/g.line.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
	{literal}
    $(document).ready(function(){
        var objSettings = [
                           {
                               title:'Daily Register Log', 
                               color: '#2a3c62', 
                               hover_color: '#4a5c82',
                               tag_title: ' register(s) on '
                           }, 
                           {
                               title:'Daily Email Log', 
                               color: '#be3741', 
                               hover_color: '#de5761',
                               tag_title: ' referral and schedule email(s) on ',
                           }
                          ];
        {/literal}
        var ary_chart_data = [[[],[],[]] , [[],[],[]]];
        {foreach name=daily_register_log from=$page_content.daily_register_log item=aryData}

			ary_chart_data[0][0].push({$smarty.foreach.daily_register_log.index});
			ary_chart_data[0][1].push({$aryData.total});
			ary_chart_data[0][2].push('{$aryData.log_date}');
    	{/foreach}
        {foreach name=daily_ref_email_log from=$page_content.daily_ref_email_log item=aryData}

			ary_chart_data[1][0].push({$smarty.foreach.daily_ref_email_log.index});
			ary_chart_data[1][1].push({$aryData.total});
			ary_chart_data[1][2].push('{$aryData.log_date}');
        {/foreach}
			
        
    	fnDrawChart('holder', objSettings, ary_chart_data);
	{literal}
    });
    {/literal}



    {literal}
    function fnDrawChart(str_node_id, objSettings, ary_chart_data){
        var padding = 30;
        var width = $("#" + str_node_id).width();
        var height = 200; 
        var data_length = 0;

        for(var i = 0; i < ary_chart_data.length; i ++ ){
        	data_length = ary_chart_data[i][2].length > data_length ? ary_chart_data[i][2].length : data_length;
        }

    	var r = Raphael(str_node_id, width - padding, height + 10);
        var lines = r.g.linechart(padding, padding, width - padding*2, height - padding, 
                                  [ary_chart_data[0][0], ary_chart_data[1][0]], 
                                  [ary_chart_data[0][1], ary_chart_data[1][1]],
                                  {nostroke: false, axis: "0 0 1 1", symbol: "o", smooth: false, shade: false, axisxstep: data_length - 1});
        lines.hoverColumn(
            function () {
	            this.tags = r.set();
	            for (var i = 0, ii = this.y.length; i < ii; i++) {
		            var index = this.values[i].split(",");
	            	this.symbols[i].attr({r: 4, fill: objSettings[i].hover_color, stroke : objSettings[i].hover_color});
	            	//
	            	//var i_height = height - padding - i*30;
	                //this.tags.push(r.g.tag(this.x, this.y[i], (ary_chart_data[i][2][index[1]] + "  , " + ary_chart_data[i][1][index[1]]), 160, 10).insertBefore(this).attr([{fill: "#fff"}, {fill: this.symbols[i].attr("fill")}]));
	            	//r.g.tag(this.x, this.y[i], this.values[i], 160, 10).insertBefore(this).attr([{fill: "#fff"}, {fill: this.symbols[i].attr("fill")}])

	                var title = ary_chart_data[i][1][index[1]] + objSettings[i].tag_title + ary_chart_data[i][2][index[1]];
	                var chart_padding = 15;
	                var out_margin = 2;
	                var box_padding = 4;

	                var txt_w = title.length*7;
	                var txt_h = 16;
	                
	                var edge_left = padding + chart_padding + out_margin + box_padding + txt_w/2;
	                var edge_right = width - chart_padding - padding - out_margin - box_padding - txt_w/2;

	                var box_w = txt_w + box_padding*2;
	                var box_h = txt_h + box_padding*2;

	                var txt_x = 0;
	                var txt_y = padding + out_margin + box_padding + i*out_margin*2 + i*box_padding*2 + i*txt_h; 
	                if(this.x < edge_left){
	                	txt_x = edge_left;
		            }else if (this.x > edge_right){
	                	txt_x = edge_right;
					}else if (0 <= (this.x - width/2) && (this.x - width/2) < box_w/2){
						//alert("A" + (this.x - width/2));
						txt_x = width/2;
					}else if (0 < (width/2 - this.x) && (width/2 - this.x) < box_w/2){
						//alert("B" + (width/2 - this.x));
						txt_x = width/2;
					}else{
						txt_x = (this.x - width/2) > 0 ? this.x - box_w/2 : this.x + box_w/2 ;
					}

	                var box_x = txt_x - txt_w/2 - box_padding;
	                var box_y = txt_y - box_padding - txt_h/2;

	                var custom_tag = r.set();
	                custom_tag.push(r.path("M" + this.x + " " + this.y[i] + "L" + txt_x + " " + (box_y + box_h)).attr({stroke: objSettings[i].hover_color, "stroke-width": 1}));
	                custom_tag.push(r.rect(box_x, box_y, box_w, box_h).attr({stroke: objSettings[i].hover_color, fill: "#ffffff", "stroke-width": 1}));
	    	        custom_tag.push(r.g.text(txt_x, txt_y, title).attr({"font-size": 12}));
	            	this.tags.push(custom_tag);
	            }
	        },
	        function () {
	            for (var i = 0, ii = this.y.length; i < ii; i++) {
		            var index = this.values[i].split(",");
	        		this.symbols[i].attr({r: 2, fill: objSettings[index[0]].color, stroke: objSettings[index[0]].color});
	            }
	            this.tags && this.tags.remove();
	        }
        );

        r.g.txtattr.font = "12px 'Courier New', sans-serif";

        for(i in objSettings){
        	r.circle(width - padding - objSettings[i].title.length*7 - 8, padding + i*16 + 1, 4).attr({stroke: objSettings[i].color, fill: objSettings[i].color});
        	r.g.text(width - padding - objSettings[i].title.length*7/2, padding + i*16, objSettings[i].title).attr({"font-size": 12});
            lines.symbols[i].attr({r: 2, fill: objSettings[i].color, stroke: objSettings[i].color});
            lines.lines[i].attr({"stroke-width": 1, stroke: objSettings[i].color});
            r.g.text(20, height - 12, 'Qty').attr({"font-size": 12});
            r.g.text(40, height, 'Day').attr({"font-size": 12});
        }
    } 
    {/literal}
</script>
<div id="holder"></div>