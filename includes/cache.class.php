<?php
class cache{
    public static function aryFontendOptions($lifetime = 60, $caching = true, $automatic_serialization = true, $automatic_cleaning_factor = true){
        return array(
                    'lifeTime'                    => $lifetime,
                    'caching'                    => $caching,
                    'automatic_serialization'    => $automatic_serialization,
                    'automatic_cleaning_factor'    => $automatic_cleaning_factor,
        );
    }
    
    public static function aryBackendOptions(){
        global $CFG;
        return array('cacheDir' => $CFG->cache);
    }
}