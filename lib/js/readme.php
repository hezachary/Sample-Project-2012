<?php
/**
 * JS Base64 + Json Sample:
 * decode: jq.parseJSON(jq.base64.decode(decodeURIComponent('string')))
 * encode: jq.base64.encode(jq.toJSON({}))
 * 
 * Object to url query
 * jq.param({})
 * 
 * Jquery.tools:
 * UI - Tabs
 * UI - Tooltip
 * UI - Scrollable
 * UI - Overlay
 * Form - Dateinput
 * Form - Rangeinput
 * Form - Validator
 * Toolbox - Flashembed
 * Toolbox - History
 * Toolbox - Expose
 * Toolbox - Mousewheel
 * For detail, demo, manual - http://flowplayer.org/tools/index.html
 * 
 * Jquery-ui:
 * Interactions:
 *  Draggable
 *  Droppable
 *  Resizable
 *  Selectable
 *  Sortable
 * Widgets:
 *  Accordion
 *  Autocomplete
 *  Button
 *  Datepicker
 *  Dialog
 *  Progressbar
 *  Slider
 *  Tabs
 * Utilities:
 *  Position
 *  Effects
 * For detail, demo, manual - http://jqueryui.com/home, http://docs.jquery.com/UI
 * 
 * Jquery.uniform:
 * Uniform styles:
 *  Selects (Drop downs)
 *  Checkboxes
 *  Radio buttons
 *  File Upload inputs
 * For detail, demo, manual - http://uniformjs.com/
 * 
 * Jquery.placehold
 * Example:
 * <script type="text/javascript">
 *  $().ready( function() {
 *      $( "input, textarea" ).placehold({
 *          placeholderClassName: "something-temporary"
 *      });
 *  });
 * </script>
 * <form>
 *  <ul>
 *      <li><label>First name</label><input type="text" name="first_name" placeholder="Enter your first name..." /></li>
 *      <li><label>Last name</label><input type="text" name="last_name" placeholder="Enter your first name..." /></li>
 *      <li><label>Comment</label><textarea name="comment" placeholder="Enter your comment..."></textarea></li>
 *  </ul>
 * </form>
 * For detail, demo, manual - http://www.viget.com/inspire/a-jquery-placeholder-enabling-plugin/, https://github.com/jgarber623/jquery-placehold, http://www.viget.com/uploads/file/jquery-placehold/
 * 
 * 
 * *******************************************************
 * Touch Scroll for i-Device and Android
 * API List: http://uxebu.com/blog/2010/09/15/touchscroll-0-2-first-alpha-available/
 * GIThub source: https://github.com/davidaurelio/TouchScroll/downloads
 * 
 * In your HTML
 * 1. HTML 5 Doc type : <!DOCTYPE HTML>
 * 2. Include the [touchscroll.min.js]
 * 3. Include the [stylesheet.css]
 * 
 * In your CSS -
 * 1. Set: 
 * html, body{
 *                 margin: 0;
 *                 padding: 0;
 *                 height: 100%;
 * }
 * Remeber has to put [html], not just [body]
 * 2. Outside the scroll-able content, put a container [div], set:
 * .scroll_container{
 *     height: 100%;
 *     overflow: hidden;
 *     display: -webkit-box;
 *     -webkit-box-orient: vertical;
 *     -webkit-box-pack: justify;
 * }
 * 3. For the scroll-able content [div], set:
 * .scroll_container .scrollbar{
 *     -webkit-box-flex: 1;
 * }
 * 
 * In your javascript:
 * 1. Locate the element(s) first, like : var scroll_node = document.getElementByID('scroll_id').
 *     If you use jquery, please use [each] or get(?), to locate the element itself.
 * 2. Use the scroll node to create new [TouchScroll] instance: var obj_scroller = new TouchScroll(scroll_node, {elastic: true});
 * 3. To apply the scroll, please call [setupScroller] method: obj_scroller.setupScroller(true); 
 * BTW, if you have different css design for landscape and portrait, in your css file, you can use:
 * @media screen and (width: 768px) and (max-height: 1004px){
 *                 .sample_ css_1 { ... }
 *                 .sample_ css_2 { ... }
 * }
 * 
 * And meta tag for pixal to pixal:
 * <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
 * <meta name="apple-mobile-web-app-capable" content="yes"/>
 * *******************************************************
 * 
 **/ 
?>

