<?php
/**
* @author  peter wang <xpw365@gmail.com>
* @version  1.23 
* @package data array/grid 
*     
* stantard xp table: key field : id auto incremantal      
*/

/*start class */
class xpTable {

    //loaded tables
    static $tables = array();
    
    var $table;
    var $fields;
    var $field_names;
    var $key;
    /**
     * object handler
     *
     * @param string $name    :  table name
     * @return object handler
     */
    static function load($name){
        if(self::$tables[$name]) return self::$tables[$name];
        return  self::$tables[$name] = new self($name);
    }
        
    
    /**
     * create table name
     *
     * @param string $name    : table name
     */
    function __construct($name){
        
        $this->table = $name;
        
        $db = xpMysql::conn();
        $r = $db->table_info($name);
        $keys=array();
        foreach ($r as $v){
            $rows[] = "`{$v['Field']}` {$v['Type']} ".($v['Null']=='NO' ? ' NOT NULL ' : ' ' ). ($v['Default'] ? ' default '.$v['Default'] :' ' .$v['Extra']);
            $this->field_names[] = $v['Field'];
            
            switch($v['Key']){
                case 'PRI':
                    $keys[] = "PRIMARY KEY  (`{$v['Field']}`)";        
                    $this->key = $v['Field'];
                    break;
                case 'MUL':
                    $keys[] = "KEY `{$v['Field']}` (`{$v['Field']}`)";        
                    break;
                        
            }
        }
        $this->fields = xpAS::extend($rows,$keys);
        
    }
    

    
    /**
     * relay all other call to xpMysql
     *
     * @param string $name     : method name
     * @param mix $args        : parameters
     * @return mix
     */
    function __call($name,$args){
        $db = xpMysql::conn();
        array_unshift($args,$this->table);
        return call_user_func_array(array($db,$name) ,$args);
    }
    
    function q($q){
        $db = xpMysql::conn();
        return $db->q($q);        
    }
    
    
    function check($value,$field=null){
            if(!$field) $field = $this->key ? $this->key : $this->field_names[0];
            return $this->get(array($field=>$value));
    }
    
    function append($arr){
            if(!is_array($arr) ) return false;
            return     $this->insert($this->_filter($arr));
    }
        
    function update($arr,$con=null){
            if(!is_array($arr) ) return false;
            if(!$con) $con =array( $this->key=>$arr[$this->key] );
            return $this->write($this->_filter($arr,false),$con);
        }

    /**
         * list
         *  status, limit , orders, search, status
         *
         * @param array $arr
         * @return mix
         */
    function lists($arr=null){
        /**
         * $arr =array(
         *     limit=>1,25,
         *  order=>name,-age,mms // -: DESC
         *  fields=name,age,email or * //default * if empty
         *  search=>array(array(name="peter",id<12,syatus is not_null, ql <> 121),
         *                 array(email like "peter%")
         *                 ) 
         *                 * inside array is AND condition
         *                 *between array is OR condition    
         *  or search=>name="peter",id<12,syatus is not_null, ql <> 121
         *     status=>0,1,2,3, *=all//default =1,    
         *     count=1 ; return total counts
         * )
         * 
         */

        //range
        if($arr['limit']){
            $t = xpAS::split($arr['limit']);
            if(count($t)==1)
                $limit = 'LIMIT '.(int)$t[0].' ';
            else 
                $limit = 'LIMIT '.(int)$t[0].' , '.(int)$t[1].' ';
        }
        
        //order
        if($arr['range']) $arr['limit'] = $arr['range'];
        if($arr['order']){
            foreach (xpAS::split($arr['order']) as $k=>$v){
                    $v = mysql_escape_string($v);
                    $order[]=$this->_table_name($this->table).'.'.( $v{0}=='-' ? substr($v,1)." DESC " : $v);
            }
            $order =" ORDER BY ".implode(',',$order);
        }
        
        //fields
        $fields = '*';
        if($arr['fields']){
            $t = explode(',',$arr['fields']);
            foreach ($t as $k=>$v){
                if(strpos($v,'(')){
                    $t[$k] = mysql_escape_string(preg_replace('/(.*?)\(\s*?(.*?)\s*\)(.*)/','$1'.'('.$this->conn->_table_name($this->table).'.'.'$2)$3',$v));
                }else{
                    $t[$k] = $this->_table_name($this->table).'.'.mysql_escape_string($v);
                }
            }
            $fields = implode("\n ,",$t);        
        }
        
        //condition
        $cond = ' 1 ';            
        if($arr['search']){
            $cond = $this->_condition($arr['search']);
        }    
                    
        $q = "
            SELECT $fields
            FROM ".$this->_table_name($this->table)."
            WHERE 
                    $cond
                    $order                    
                    $limit
        ";

        $data = $this->q($q);    
        if($arr['count']){
            $f = $fields ?xpAS::get(xpAS::split($fields),0) : $f = $this->field_names[0];
            $q = "
                SELECT COUNT($f) as total
                FROM ".$this->_table_name($this->table)."
                WHERE 
                        $cond
            ";            
            $total = xpAS::get($this->q($q),'0,total');    
            return array('data'=>$data, 'count'=>(int)$total);

        }else{
            return array('data'=>$data);
        }

    }

    
    function _filter($arr,$no_key=true){
            foreach ($arr as $k=>$v)
                if(in_array($k,$this->field_names)) 
                    if(!$no_key || $k != $this->key )
                        $brr[$k] = $v;
            return $brr;
    }    

    function _in($arr){
        $db = xpMysql::conn();
        return $db->_in($arr);            
    }
    function _condition($arr){
        $db = xpMysql::conn();

        return $db->_condition($this->table, $arr);            
    }    
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    

    
        
}