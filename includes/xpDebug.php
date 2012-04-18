<?
    /**
    * @author  peter wang <xpw365@gmail.com>
    * @version  2.2 
    * @package _debug
     * some debug script for site
     *  
     */

    /**
     * name alias for debug::a()
     * @param array  $x
     * @param nameof array [ $name]
     * @param indent int [ $tab]
     * @param max array level int [ $deep]
     * @param  do not use  $level
     * @return string 
     */
    
    function _debug($x,$name='',$tab=6,$deep=50,$level=0){
        return xpDebug::a($x,$name,1,$tab,$deep);
    }
    function _die($x,$name='',$tab=6,$deep=50,$level=0){
         xpDebug::a($x,$name,1,$tab,$deep);
         die;
    }
    
    /**
     * name alias for debug::b();
     * @param array  $x
     * @param nameof array [ $name]
     * @param indent int [ $tab]
     * @param max array level int [ $deep]
     * @param  do not use  $level
     * @return html string
     */
    function _debugd($x,$name='',$tab=6,$deep=50,$level=0,$close=0){
        return xpDebug::b($x,$name,1,$tab,$deep,$level,$close);
    }
    
    function _debugu($x,$name='',$close=50,$deep=50,$level=0){
        if( __DEBUG__) echo xpDebug::tree($x,$name,$close,$deep,$level);
    }

/**
 * classed debug function
 * @author     : peter <xpw365@gmail.com>
 * @example 
 * @version     : 2.2
 */
class xpDebug{
    /**
     * output array to screen 
     *         with closable div
     *
     * @param   array or object $x    : obj to display
     * @param  string $name    : objname
     * @param   boolean $display    : if output to screen
     * @param   int $tab        : tab width
     * @param   int $deep        : debug deepth
     * @param   private int $level    : private to function , should not be used
     * @return  string
     */
    
    function b($x,$name='',$display=true,$tab=6,$deep=50,$level=0)
    {
        if(! __DEBUG__ ) return;
        if($level==0) $con = "<pre>{".($name?'-&gt;'.$name : '') ."<div style=\"border:1px #ddd dotted\">";        //1st start
        $level++; 
        if($level == $deep) return serialize($x);
        if(is_array($x) || is_object($x)) {
            foreach ($x as $k=>$v){
                $id= md5($level.'_'.$k);
                $idplus = $id."p";
                $con .=  "<div style=\"border:1px #ddd dotted\">".str_pad($level,1,' ').str_pad('',$level*$tab,' ',STR_PAD_LEFT)."(<a id=\"$idplus\" style=\"cursor:pointer;color:blue;\" onclick=\"javascript:var p =document.getElementById('$idplus'); var t =document.getElementById('$id'); if(t.style.display){ t.style.display=''; p.innerHTML='-';}else{ t.style.display='none' ; p.innerHTML='+';}  return false;\">-</a>)$k=>[\r<div id=\"$id\" style=\"border:1px #ddd dotted\">";
                $con.=debug::b($v,'',$display,$tab,$deep,$level);
                $con .= "</div>".str_pad($level,5,' ').str_pad('', $level*$tab,' ',STR_PAD_LEFT)."]</div>";
            }
        }else{
            $con.= str_pad($level,5,' ').str_pad('',$level*$tab,' ',STR_PAD_LEFT)."$x";
        }
        if($level==1){ 
            $con .= "</div>}</pre>"; //end
            if($display) echo $con;    //display
        }
        return $con;
    }
    
    /**
     * output array to screen and string
     *
     * @param   array or object $x    : obj to display
     * @param  string $name    : objname
     * @param   boolean $display    : if output to screen
     * @param   int $tab        : tab width
     * @param   int $deep        : debug deepth
     * @param   private int $level    : private to function , should not be used
     * @return  string
     */    
    function a($x,$name='',$display=true,$tab=6,$deep=50,$level=0){
        if(! __DEBUG__ ) return;
        if($level==0) $con = "<pre>\r{".($name?'-&gt;'.$name : '') ."\r";        //1st start
        $level++; 
        if($level == $deep) return serialize($x);
        if(is_array($x) || is_object($x)) {
            foreach ($x as $k=>$v){
                $con .= str_pad($level,5,'.').str_pad('',$level*$tab,'.',STR_PAD_LEFT)."$k=>[\r";
                $con .= self::a($v,'',$display,$tab,$deep,$level);
                $con.= str_pad($level,5,'.').str_pad('', $level*$tab,'.',STR_PAD_LEFT)."]\r";
            }
        }else{
            $con.= str_pad($level,5,'.').str_pad('',$level*$tab,'.',STR_PAD_LEFT)."$x\r";
        }
        if($level==1){
            $con.= "}\r</pre>\r"; //end
            if($display) echo $con;    //display            
        }
        return $con;
        
    }
    /**
     * output to file
     *
     * @param array $x    : object
     * @param string $fn    : filename/path
     */
    function f($x,$fn){
        file_put_contents($fn , self::a($x));
    }
    
    /**
     * output array to screen and die
     *
     * @param   array or object $x    : obj to display
     * @param  string $name    : objname
     * @param   boolean $display    : if output to screen
     * @param   int $tab        : tab width
     * @param   int $deep        : debug deepth
     * @param   private int $level    : private to function , should not be used
     * @return  string
     */
    function ca($x,$name='',$display=true,$tab=6,$deep=50,$level=0){
        self::a($x,$name,$display,$tab,$deep,$level);
        die();
    }
    /**
     * print out msg with time stamp
     *
     * @param string $str    : msg to output
     * @param int $indent    : indent value
     */
    function msg($str,$indent=0){
        if(__DEBUG__)  return;
        echo date('Y-m-d H:i:s').'.'.self::msecond()." :".str_pad($str, $indent*4, "&nbsp;", STR_PAD_LEFT)."<br>";
    }

    /**
     * get mi
     *
     * @return unknown
     */
    private    function msecond(){
        $a=explode(' ',microtime());
        return $a[0]*1000;
    }    

    /**
     * ul type tree
     *
     * @param array $x        : object;
     * @param string $name    : obj name
     * @param int $deep        : max depth;
     * @param int $level        : do not set!
     * @return html string
     */
    function tree($x,$name='',$close=50, $deep=50, $level=0){
            if($level>$deep) return "<li id=\"$id\">".serialize($x)."</li>";
            $id=uniqid().$level;
            $num = count($x);
            $myid = $id.'_tree_block';
            $con.=($level?'':'<ul style="padding:0;margin:0;">');
            if(is_array($x) || is_object($x)) {
                $con.="<li style=\"border-bottom:dotted 1px #eee;\"><a href=\"javascript:void(0)\" onclick=\"this.blur(); var e=document.getElementById('$id'); var d=e.style.display; if(d){e.style.display='';this.childNodes[0].childNodes[0].innerHTML='-';}else{e.style.display='none';this.childNodes[0].childNodes[0].innerHTML='+';}\"><span class=\"symbol\"><b>".($level>=$close?"+":"-")."</b><sup>$level</sup><sub>$num</sub>".$name."</span></a> <span> &rarr;</li><li id=\"$id\" style=\"display:".($level>=$close?"none":"")."\"><ul >";
                foreach ($x as $k=>$v)
                    $con.=self::tree($v,$k,$close, $deep,$level+1);
                $con.="</ul></li>";    
            }else{
                $con .= "<li id=\"$id\"><div><div style=\"float:left;margin-right:12px;\"> </b> $name &rarr; </div><div style=\"float:left\">".implode('<br>',xpAS::clean(explode('*#',str_replace('*#','*#&bull; ',$x))))."</div><div style=\"clear:both;\" ></div></div></li>";
            }
            $con.=$level?'':'</ul>';
 
            if(!$level) $con = "<style type=\"text/css\">b{margin:0 3px 0 0;} .bbb{color:#ddd;font-size:0.6em;} sup,sub{color:#bbb; font-size:0.6em;} sub{ margin-left:-5px;} .symbol{height:0.8em;font-size:0.8em;padding:0 3px 0 10px;margin: 0 3px; background:#fff0b0;border:#eee solid 1px;border-right:#ddd solid 2px;border-bottom:#ddd solid 2px;} ul {font-family: courier new;margin-left:40px;padding-top:0;display:block;list-style-type:none;} li{margin:7px 0;} a{text-decoration:none;}</style>" 
                       .$con;
            return $con;
            
        }
                
    
    
}



?>