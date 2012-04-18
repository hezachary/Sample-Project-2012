<?php
    /**
    * some date lib used in php4 , and on php5
    *        
    *         ISO :2009-10-25
    *         unix time : 1800239283900
    * 
    * @author     :coordinated  peter<xpw365@gmail.com>
    * @since     :2006-05-05
    * @example     : 
    */
    class xpDate{
    
    private static $MONTH_DAYS = array(31,28,31,30,31,30,31,31,30,31,30,31);
    
    /**
     * return time stamp (include milisecond)
     *
     * @return iso time stamp string with mili seconds
     */
    function timestamp(){
        return date('Y-m-d H:i:s').'.'.self::msecond();
    }
    /**
     * return milliseconds
     *
     * @return milisecond : current miliseconds
     */
    function msecond(){
        $a=explode(' ',microtime());
        return $a[0]*1000;
    }
    
    /**
     * change iso date time by $alt 
     *     eg. $alt = '+1 day' ; ..
     *
     * @param iso date  $isoDateTime    : TIME TO ALTER
     * @param  string $alt            : alter factory    : +1 day; +8 hour..
     * @return string time            : iso time
     */
    function alter($isoDateTime,$alt)    {
        $date = new DateTime($isoDateTime);
        $date->modify($alt);
        return $date->format("Y-m-d H:i:s");
    }
    /**
     * create datye object using mysql time stamp
     *
     * @param iso date $isoTime        : mysql type timestamp
     * @return xpDate date object array    :array('year','month','date','hour','minute','second','week','day','ydays','mdays','summertime','timezone','ut');
     */
    function dateOBJ($isoTime){
        //delete micro second
        $isoTime = preg_replace('/\..*/','',$isoTime);
        $ut = self::returnUNIXTimestampFromMYSQL($isoTime);
        $values = explode('-',date('Y-m-d-H-i-s-W-w-z-t-I-T', $ut));
        $values[]=$ut;
        $keys=array('year','month','date','hour','minute','second','week','day','ydays','mdays','summertime','timezone','ut');
        $obj=array_combine($keys,$values);
        $obj['day']=$obj['day']?$obj['day']:7;            //change 0-6:sun-sat to 1-7:mon-sun 
        return $obj;
    }
    /**
     * return Unixtimestamp from date obj
     *
     * @param object $obj        : xpDate date object
     * @return Unixtimestamp
     */
    function dateOBJ2UT($obj){
        return self::returnUNIXTimestampFromMYSQL("{$obj['year']}-{$obj['month']}-{$obj['date']} {$obj['hour']}:{$obj['minute']}:{$obj['second']}");
    }
    /**
     * make date array as array('2008-11-12', '2008-11-13', '2008-11-14', ..)
     *
     * @param iso date $from    : date start
     * @param iso date $to        : date to
     * @return date array        : array contain the iso dates array ('2009-01-01', 2009-01-02', ...'2009-01-23')..
     */
    function dateArray($from,$to){
        $from = self::returnDate($from);
        $to = self::returnDate($to);
        $darr=array();
        $now = $from;
        while($now <= $to)
        {
            $darr[] = $now;
            $now = self::returnDate(self::next_day($now));
        }
        return $darr;
    }
    /**
     * return which day od the weekday
     *
     * @param  date $date    : iso date    2006-09-11
     * @return int         : day    (1-7 => mon-sun)
     */
    function weekDay($date){
        $obj = self::dateOBJ($date);
        return $obj['day'];
    }
    /**
     * get 1st date of month
     *
     * @param isodate  $date    : date
     * @return isodate         :1st date of the month 2009-01-01
     */
    function first_day_of_month($date){
        return self::returnYYYY($date).'-'.self::returnMM($date).'-01';
    }
    /**
     * return last date of the month
     *
     * @param  iso date $date     :date
     * @return isodate         : last day of the month 2009-01-31
     */
    function last_day_of_month($date)    {
        return self::returnYYYY($date).'-'.self::returnMM($date).'-'.(sprintf('%2s',self::get_month_days($date)));
    }
    /**
     * get the date's week's monday's date
     *
     * @param isodate $date    : date
     * @return date monday's date 
     */
    function getMonday($date){
        $now = $date;
        while(1)
        {
            $obj=self::dateOBJ($now);
            if($obj['day']==1) return $now;
            $now = self::last_day($now);
            unset($obj);
        }
    }
    /**
     * get the date's week's sunday's date
     *
     * @param  iso date $date    : date
     * @return isodate sunday's date
     */
    function getSunday($date){
        $now = $date;
        while(1)
        {
            $obj=self::dateOBJ($now);
            if($obj['day']==7) return $now;
            $now = self::next_day($now);
            unset($obj);            
        }
    }    
    
    /**
     * get how many days of the month
     *
     * @param  isodate $date    : date
     * @return int number of days
     */
    function get_month_days($date){
        $obj = self::dateOBJ($date);
        $md = $obj['mdays'];
        unset($obj);
        return $md;
    }
    /**
     * get previous date
     *    name alias of last_day
     * @param  isodate  $date    :date
     * @return  isodate        : 2009-02-01 -> 2009-01-31
     */
    function previous_day($date)     {
        return self::last_day($date);
    }
    /**
     * get previous date
     *
     * @param  isodate $date    :date
     * @return isodate
     */
    function last_day($date) {
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." -1 day"));
    }    
    /**
     * get previous month
     *    synonymous TO LAST_MONTH
         * @param  isodate $date    :date
     * @return isodate of previous month
     */
    function previous_month($date){
        return self::last_month($date);
    }
    /**
     * get previous n* month
         * @param  isodate $date    :date
     * @return isodate of previous     n * month
     */
    function previousNmonth($date,$n){
        return self::lastNmonth($date,$n);
    }
    

    /**
     * shift second
     */
    function last_second($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." -1 second"));
    }
    
    function lastNsecond($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::last_second($date);
        return $date;    
    }
        
    function next_second($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." +1 second"));
    }
    
    function nextNsecond($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::next_second($date);
        return $date;    
    }
    /**
     * shift minute
     */
    function last_minute($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." -1 minute"));
    }
    
    function lastNminute($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::last_minute($date);
        return $date;    
    }
        
    function next_minute($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." +1 minute"));
    }
    
    function nextNminute($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::next_minute($date);
        return $date;    
    }
    
    /**
     * shift hour
     */
    function last_hour($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." -1 hour"));
    }
    
    function lastNhour($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::last_hour($date);
        return $date;    
    }
        
    function next_hour($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." +1 hour"));
    }
    
    function nextNhour($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::next_hour($date);
        return $date;    
    }    
    
    /**
     * shift date
     */
    function last_date($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." -1 day"));
    }
    
    function lastNdate($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::last_date($date);
        return $date;    
    }
        
    function next_date($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." +1 day"));
    }
    
    function nextNdate($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::next_date($date);
        return $date;    
    }    
    
    /**
     * shift month
     */
    function last_month($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." -1 month"));
    }
    
    function lastNmonth($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::last_month($date);
        return $date;    
    }
        
    function next_month($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." +1 month"));
    }
    
    function nextNmonth($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::next_month($date);
        return $date;    
    }    
    
    /**
     * shift year
     */
    function last_year($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." -1 year"));
    }
    
    function lastNyear($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::last_year($date);
        return $date;    
    }
        
    function next_year($date){
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." +1 year"));
    }
    
    function nextNyear($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::next_year($date);
        return $date;    
    }    
    
    
    
    
    /**
     * returen previous n* day
     *
     * @param   isodate $date    : date
     * @param   int $n        : 
     * @return  isodate
     */
    function previousNday($date,$n) {
        return self::lastNday($date,$n);
    }
    /**
     * returen previous n* day
     *
     * @param   isodate $date    : date
     * @param   int $n        : 
     * @return  isodate
     */
    function lastNday($date,$n) {
        for($i=0;$i<$n;$i++) $date = self::previous_day($date);
        return $date;    
    }
    
    /**
     * return next date
     *
     * @param   isodate $date
     * @return  isodate
     */
    function next_day($date) {
        return self::returnMYSQLTimestampFromUNIX(strtotime($date." +1 day"));
    }
    
    
    
    
    
    /**
     * return date of last month
     *
     * @param   isodate $date
     * @return  isodate last month
     */
//    function last_month($date) {
//        return self::_month($date,-1);
//    }    
//    /**
//     * return next month
//     *
//     * @param   isodate $date
//     * @return  iso date next month
//     */
//    function next_month($date) {
//        return self::_month($date,1);
//    }
//    /**
//     * shift month
//     *
//     * @param   iso date $date    :current date
//     * @param   int $d        : shifter +1, -5..
//     * @return  iso date
//     */
//    private function _month($date,$d)
//    {
//        //beware of end of month should to 30 or 31..
//        $obj=self::dateOBJ($date);
//        $month = $obj['month'] + $d ;
//        $obj['year'] +=$month<1? ceil($month/12)-1 : floor($month / 12) ;
//        $obj['month'] =$month<1? $month % 12 + 12  : $month % 12;
//    
//        $mdays = self::get_month_days($obj['year']."-".$obj['month']."-01");
//
//        $obj['date'] = $obj['date'] > $mdays ? $mdays : $obj['date'];
//        $md = self::returnMYSQLTimestampFromUNIX(self::dateOBJ2UT($obj));
//        unset($obj);
//        return $md;
//    }
//    /**
//     * last N* month
//     *
//     * @param   iso date $date    : currently date
//     * @param   int $n        : n month
//     * @return  isodate
//     */
//    function lastNmonth($date,$n) {
//        return self::_month($date,-$n);
//    }    
//    /**
//     * next n* month
//     *
//     * @param isodate $date
//     * @param int $n
//     * @return isodate
//     */
//    function nextNmonth($date,$n) {
//        return self::_month($date,$n);
//    }    


    /**
     * get time string
     *
     * @param  $str
     * @return  time string : 21:02:21
     */
    function get_time_string($str){
        return  xpAS::preg_get('/[([0|1][0-9])|(2[0-3])] : [0-9][0-9] /',$str);
    }
    /**
     * next n* day
     *
     * @param   isodate $date
     * @param   int $n
     * @return  isodate
     */
    function nextNday($date,$n)     {
        for($i=0;$i<$n;$i++) $date = self::next_day($date);
        return $date;    
    }
    /**
     * get prevoius date of ut
     *
     * @param   $tm
     * @return  unix timestamp
     */
    function previous_day_ut($tm) {
        return self::last_day_ut($tm);
    }    
    /**
     * previous date of ut
     *
     * @param   unix timestamp $tm
     * @return  unix timestamp
     */
    function last_day_ut($tm) {
        $date =self::previous_day($date);
        return self::returnUNIXTimestampFromMYSQL($date);
    }
    
    
    /**
     *  get next date for unix timestamp
     *
     * @param   unix timestamp $tm
     * @return   unix timestamp
     */
    function next_day_ut($tm) {
        $date = self::returnMYSQLTimestampFromUNIX($tm); 
        $date =self::next_day($date);
        return self::returnUNIXTimestampFromMYSQL($date);
        
    }
    
    /**
     *  check 2 iso date time is in same date
     *
     * @param isodate  $d1    : date 1
     * @param  isodate  $d2: date2
     * @return  boolean
     */
    function same_day($d1,$d2){
        $d1 = explode(' ', $d1);
        $d2 = explode(' ', $d2);
        if($d1[0] == $d2[0])
            return true;
        else     
            return false;
    }
    /**
     * return number of day between two dates by Paul K
     * this one need mysql server    
     * iso date format
     *
     * @param  isodate  $d1    : date1
     * @param   isodate $d2    : date2
     * @return  int 
     */
    private function _numberDays2($d1, $d2){
         $db = new db_sql(); 
         $sql = "SELECT datediff('$d2','$d1') as difference";
         $db->query($sql);
         $r =$db->next_record();
        return $r['difference']+1;
    }
    /**
     * return number of day between two dates 
     *
     * @param  date  $d1
     * @param   date $d2
     * @return  int 
     */
    private function _numberDays1($d1,$d2)    {
        return round((self::returnUNIXTimestampFromMYSQL($d2)-self::returnUNIXTimestampFromMYSQL($d1))/86400+1);
    }
    /**
     * return number of day between two dates 
     *
     * @param  date  $d1
     * @param   date $d2
     * @return  int 
     */
    private function _numberDays0($from,$to){
        $sign=1;
        if($from >$to)    {
            $sign=-1;
            $tmp=$from;
            $from=$to;
            $to=$tmp;
        }
            
        $c=0;
        for($i=$from;$i<=$to;)    {
            $i=self::next_day($i);
            echo "d=$i<br>";
            $c++;
        }
        return $c*$sign;
    }
    /**
     * return number of day between two dates 
     *
     * @param  isodate  $d1
     * @param   isodate $d2
     * @param  int    $type    : which evalute method to be used 0=day by day; 1=deirct calculate(no summery time);  2=use mysql;     
     * @return  int 
     */
    function num_days($d1,$d2,$type=1)    {
            switch($type)
            {
                case 0: return self::_numberDays0($d1,$d2);break;
                case 1: return self::_numberDays1($d1,$d2);break;
                case 2: return self::_numberDays2($d1,$d2);break;
            }
            return 0;
    }
    /**
     * return number of day between two dates 
     *
     * @param  ut  $d1
     * @param  ut  $d2
     * @return  int 
     */
    function num_days_ut($from,$to,$type=1)    {
        return self::num_days(self::returnMYSQLTimestampFromUNIX($from),self::returnMYSQLTimestampFromUNIX($to),$type);
    }
    /**
     * change date format
     *
     * @param dateString  $date-time    :
     * @param int $t1            : source type    0=iso/asian; 1=australia/brit; 2=usa/nuts;    
     * @param int  $t2            : destn trye
     * @return dateString
     */
    function date_format($time,$t1,$t2){        
        $fmt = array(array(2,1,0),array(0,1,2),array(1,2,0));
        $time=explode(" ",$time);
        $d = explode("-",$time[0]);
        $f=$fmt[$t1];
        $m=array($d[array_search(0,$f)],$d[array_search(1,$f)],$d[array_search(2,$f)]);
        $f=$fmt[$t2];
        $time[0]=$m[$f[0]].'-'.$m[$f[1]].'-'.$m[$f[2]];
        $time = implode(" ",$time);
        return $time;
    }
    /**
     * iso to au format
     * @param  string $time    :iso date-time
     * @return  string        :au formated date 
     */
    function change_time_au($time){
        $time=explode(" ",$time);
        $time[0] = explode("-",$time[0]);
        $t = implode("-",array($time[0][2],$time[0][1],$time[0][0]));
        $time[0] =$t;
        $time = implode(" ",$time);
        return $time;
    }
    /**
     * return iso time-stamp from unix time
     * @param int $unix_timestamp    : unix_timestamp
     * @return  string    iso time
     */    
    function returnMYSQLTimestampFromUNIX($unix_timestamp) {
        return date('Y-m-d H:i:s', $unix_timestamp);
    }
    /**
     * return unix time from iso date-time
     * @param string $mysql_timestamp    : iso timestamp
     * @return int    unix timestamp
     */
    function returnUNIXTimestampFromMYSQL($mysql_timestamp) {
        return strtotime($mysql_timestamp);
    }
    /**
     * formating and filtering iso date
     * @param string $isoTime    : iso timestamp 
     * @return string iso timestamp
     */
    function dateIt($isoTime)
    {
        return self::returnMYSQLTimestampFromUNIX(self::returnUNIXTimestampFromMYSQL($isoTime));
    }
    /**
     * return date value(DD) from iso timestamp
     * @param string $date        : mysql timestamp
     * @return string date value of  mysql-timestamp
     */
    function returnDD($date){
        return substr(self::dateIt($date),8,2);
    }
    /**
     * return month value(mm) from iso timestamp
     * @param string $date        : mysql timestamp
     * @return string month value of  mysql-timestamp
     */
    function returnMM($date)    {
        return substr(self::dateIt($date),5,2);
    }
    /**
     * return year value(YYYY) from iso timestamp
     * @param string $date        : mysql timestamp
     * @return string year  value of  mysql-timestamp
     */
    function returnYYYY($date)    {
        return substr(self::dateIt($date),0,4);
    }
    
    /**
     * @deprecated 
     * return year value(YY) from iso timestamp only 2digital
         * @param string $date        : mysql timestamp
     * @return string year  value of  mysql-timestamp
     */
    function returnYY($date){
        return substr(self::dateIt($date),2,2);
    }
    /**
     * return date value(YYY-MM-DD) from iso timestamp only 2digital
         * @param string $date        : mysql timestamp
     * @return string date  value of  mysql-timestamp
     */
    function returnDate($date) //iso time stamp
    {
        return substr(self::dateIt($date),0,10);
    }
    /**
     * return time value(hh:mm:ss:ms) from iso timestamp only 2digital
         * @param string $date        : mysql timestamp
     * @return string time  value of  mysql-timestamp
     */
    function returnTime($date) //iso time stamp
    {
        return substr(self::dateIt($date),11);
    }
    /**
     * return hour value from iso timestamp only 2digital
         * @param string $date        : mysql timestamp
     * @return string hour  value of  mysql-timestamp
     */
    function returnHR($date)
    {
        return substr(self::dateIt($date),11,2);
    }
    /**
     * return minute value from iso timestamp only 2digital
         * @param string $date        : mysql timestamp
     * @return string minute  value of  mysql-timestamp
     */
    function returnMN($date)
    {
        return substr(self::dateIt($date),14,2);
    }
    /**
     * return second value from iso timestamp only 2digital
         * @param string $date        : mysql timestamp
     * @return string second  value of  mysql-timestamp
     */
    function returnSD($date)
    {
        return substr(self::dateIt($date),17,2);
    }
    
    /**
     * return timestamp
     *
     * @return string
     */
    function now(){
        return self::returnMYSQLTimestampFromUNIX(time());
    }
    /**
     * return today's date from iso timestamp only 2digital
     * @return string today's date
     */
    function today()
    {
        return self::returnDate(self::returnMYSQLTimestampFromUNIX(time()));
    }
    /**
     * return now time
     *
     * @return time string 12:23:51
     */
    function time(){
        return self::returnTime(self::returnMYSQLTimestampFromUNIX(time()));
    }
    /**
     *  show month and date. eg. 12 DEC    
     * @param string $time        : time stamp
     * @param int    $t1        : input time format
     * @param int    $t2        : output time format
     * @return  string        : "month date"
     */
    function show_month_date($time,$t1=1,$t2=1)
    {
        global $_months;
        $date=(self::returnDD($time));
        $month=($_months[(int)self::returnMM($time)]);
        //echo $_months[self::returnMM($time)];
        $fmt = array(array($date,$month),array($month,$date),array($month,$date));
        return $fmt[$t1][0]." ".$fmt[$t1][1];        
    }
    /**
     *  show year and month. eg. 1998 DEC    
     * @param string $time        : time stamp
     * @param int    $t1        : input time format
     * @param int    $t2        : output time format
     * @return  string        : "1998 DEC"
     */    
    function show_year_month($time,$t1=1,$t2=1)
    {
        global $_months;
        $year=lang(self::returnYYYY($time));
        $month=lang($_months[(int)self::returnMM($time)]);
        //echo $_months[self::returnMM($time)];
        $fmt = array(array($month,$year),array($year,$month),array($month,$year));
        return $fmt[$t1][0]." ".$fmt[$t1][1];        
        
    }
    /**
     *  show year , month and date. eg. 1998 DEC 20    
     * @param string $time        : time stamp
     * @param int    $t1        : input time format
     * @param int    $t2        : output time format
     * @return  string        : "1998 DEC"
     */    
    function show_year_month_date($time,$t1=1,$t2=1)
    {
        global $_months;
        $date=MDDate::returndd($time);
        $year=lang(MDDate::returnYYYY($time));
        $month=lang($_months[(int)MDDate::returnMM($time)]);
        //echo $_months[MDDate::returnMM($time)];
        $fmt = array(array($date,$month,$year),array($year,$month,$date),array($month,$date,$year));
        return $fmt[$t1][0]." ".$fmt[$t1][1].' '.$fmt[$t1][2]  ;        
        
    }
            
    /**
     * convert period of time(unix timestamp) to a array('week','day',hour','mintue','second')
     * @param int    $ut    : unixtime stamp
     * @return array    : time length array('week','day',hour','mintue','second')
     */
    function u2time($ut){
        
        $trr['week'] = (int)($ut/604800);
        $ut -=  $trr['week'] *604800;
        $trr['day'] =(int)($ut/86400);
        $ut -=     $trr['day'] *86400;
        $trr['hour'] = (int)($ut/3600);
        $ut -=    $trr['hour'] *3600;
        $trr['minute'] = (int)($ut/60);
        $ut -= $trr['minute'] *60;
        $trr['second'] = $ut;
        return $trr;
    
    }
    /**
     * return period time in Hours:Minutes:Seconds or unix time
     *
     * @param isodate $d1
     * @param isodate $d2
     * @param int $type
     * @return string
     */
    function period($d1,$d2,$type=null){
        $ud1 = self::returnUNIXTimestampFromMYSQL($d1);
        $ud2 = self::returnUNIXTimestampFromMYSQL($d2);
        $a = self::u2time($ud2-$ud1);
        switch($type){
            case 1:
                return ($a['hour']<10?'0':'').$a['hour'].':'.($a['minute']<10?'0':'').$a['minute'];
                break;
            default:        
                return $a;
                break;
        }
    }
    
    /**
     * change to 24hours. eg 2:00pm to 14:00
     * @param string $time     : 12hour time(with am/pm)
     * @param boolean        : with second
     * @return string    24hour time
     */
    function to24hours($time,$s=0){
        if(strlen($time) == 6) $time ='0'.$time;
        if(!( $time =preg_replace('/\s*/', '', xpAS::preg_get('/[1|0][0-9](\:[0-5][0-9]){1,2}\s*([a|p]m)?/i',$time)) ) ) return $s?'00:00:00':'00:00';
    
        $ap = strtolower(xpAS::preg_get('/[a|p]m/i',$time)) ;
        $hr = xpAS::preg_get('/\d\d/',$time);
        //_debug($hr,$ap);
        if($ap=='pm' && $hr != 12)
            $hr +=12;
        $hr = sprintf("%02d", ($hr % 24) );
        $time = $hr.substr(preg_replace('/[a|p]m/i',' ',$time),2);
        return $s?$time:substr($time,0,5);
    }
    /**
     * change 12hours to 24hours. eg  14:00 to 2:00pm 
     * @param string $time     : 24hour time     
     * * @param boolean        : with second
     * @return string    12hour time(with am/pm)
     */
    function to12hours($time,$s=0){
        if(!( $time =preg_replace('/\s*/', '', xpAS::preg_get('/\d\d(\:[0-5][0-9]){1,2}/i',$time)) ) ) return $s?'00:00:00AM':'00:00AM';
        $hr = xpAS::preg_get('/\d\d/',$time);
        $hr = $hr % 24;
        $m = ($hr >=12) ?'PM':'AM';
        if($hr >12 ) $hr = $hr-12;
        $hr = sprintf("%02d",$hr);
        return  $hr.substr($time,2,$s?6:3) .$m;
        
    }
    /**
     * name alias od to12hours
     * change 12hours to 24hours. eg  14:00 to 2:00pm 
     * @param string $time     : 24hour time     
     * * @param boolean        : with second
     * @return string    12hour time(with am/pm)
     */
    function show12hours($time,$s=0){
            return self::to12hours($time,$s);
    }
    /**
     * return time block
     *
     * @param step length $b
     * @return time array
     */
    function time_segments($b = 5){
        $b = (int)$b;
        if($b >1440 || $b<1) $b=5;
    
        $step = 1440/$b;
        for($i=0;$i<$step;$i++){    
            $ms = $i%(60/$b);
            $hs = floor($i/(60/$b));
            $v = sprintf("%02d",$hs).':'.sprintf("%02d",$ms*$b);
            $t[$v]= $v ;
        }
        return $t;
    }

    function time_block($start='00:00',$end='24:00',$b=5){
        $trr = self::time_segments($b);
        $start = substr($start,0,5);
        $end = substr($end,0,5);
        foreach ($trr as $k=>$v){
            if($start <= $v && $v <= $end ) $brr[$k]=$v;
        }
        return $brr;
    }
    
    function check_iso($date){
        $d = explode('-',$date);
        if($d[0] <1700 || $d[0] > 3000 ) return MDDate::today();
        return $date;
    }

    
}
