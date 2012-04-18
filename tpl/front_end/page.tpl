<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{assign var=section value=$page_file|replace:'.tpl':''|replace:'.':'_'|lower}
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title></title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="robots" content="INDEX,FOLLOW" />
        <link rel="icon" type="image/x-icon" href="{$CFG->www}ico/site.ico" />
        <link rel="shortcut icon" type="image/x-icon" href="{$CFG->www}ico/site.ico" />
        <link rel="stylesheet" type="text/css" media="screen" href="{$CFG->www}css/global.css" />               
        <script type="text/javascript" src="{$CFG->www}lib/js/jquery.min.js"></script>
        <script type="text/javascript">
            // <![CDATA[
            var jq = jQuery.noConflict();
            // ]]>
        </script>
        <script type="text/javascript" src="{$CFG->www}lib/js/jquery.uniform.min.js"></script>
        <script type="text/javascript" src="{$CFG->www}lib/js/jquery.placehold.min.js"></script>
        <script type="text/javascript" src="{$CFG->www}lib/js/jquery.json.min.js"></script>
        <script type="text/javascript" src="{$CFG->www}lib/js/jquery.base64.min.js"></script>
        
        <script type="text/javascript">
            // <![CDATA[
            var wwwroot = "{$CFG->wwwroot}";
            var www = "{$CFG->www}";
            var gcode = "{$CFG->google_code}";
            var time = new Date('{'r'|@date}');
            // ]]>
        </script>
        <script type="text/javascript" src="{$CFG->www}js/project.js"></script>
    </head>
    <body class="{$section}">
        
        <div class="content">
            {include file=$page_file page_content=$page_content}
        </div>
        
        {include file='widget.google_analytics.tpl'}
    
    </body>
</html>