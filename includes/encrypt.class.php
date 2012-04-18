<?php
/*
 * Please install and enable Mcrypt lib first.
 * 
 * $base64Key = encrypt::populate_base64Key();
 * $parametersText = 'very important';
 * list($encryptedParametersText, $signatureText) = encrypt::retrieve( $base64Key, $parametersText);
 * $parametersText_decrypt = encrypt::retrieve( $base64Key, $encryptedParametersText, $signatureText);
 * 2010-05 Zac@messagestudio
 */
class encrypt{
    /*
     * Set algorithm
     * array('arcfour', '', 'cbc', '');
     * array('blowfish', '', 'cbc', '');
     * array('blowfish-compat', '', 'cbc', '');
     * array('cast-128', '', 'cbc', '');
     * array('cast-256', '', 'cbc', '');
     * array('des', '', 'cbc', '');
     * array('enigma', '', 'cbc', '');
     * array('gost', '', 'cbc', '');
     * array('loki97', '', 'cbc', '');
     * array('rc2', '', 'cbc', '');
     * array('rijndael-128', '', 'cbc', '');
     * array('rijndael-192', '', 'cbc', '');
     * array('rijndael-256', '', 'cbc', '');
     * array('saferplus', '', 'cbc', '');
     * array('tripledes', '', 'cbc', '');
     * array('twofish', '', 'cbc', '');
     * array('wake', '', 'cbc', '');
     * array('xtea', '', 'cbc', '');
     */ 
    public $aryAlgorithm = array('rijndael-256', '', 'cbc', '');
    
    /*
     * Populate a key, please save the key, and use it as static value
     */
    public function populate_base64Key()
    {
        $strExport = '';
        $i = 0;
        $size = mcrypt_get_block_size(self::$aryAlgorithm[0], self::$aryAlgorithm[2]);
        while($i < $size){
            $strExport .= chr(rand(1, 200));
            $i ++;    
        }
        $base64Key = base64_encode($strExport);
        return $base64Key;
    }
    
    /*
     * With $signatureText means encrypt
     * Without $signatureText means decrypt
     */
    public function retrieve( $base64Key, $encryptedParametersText, $signatureText = null, $iv = null, $static_mode = true )
    {
        $key = base64_decode( $base64Key );
        
        $td = mcrypt_module_open(self::$aryAlgorithm[0], self::$aryAlgorithm[1], $aryAlgorithm[2], self::$aryAlgorithm[3]);
        $size = mcrypt_get_block_size(self::$aryAlgorithm[0], self::$aryAlgorithm[2]);
        $iv = $static_mode?str_repeat("\0", $size):$iv;
        
        if($signatureText){
            //Decrypt// Decrypt the parameter text
            mcrypt_generic_init($td, $key, $iv);
            $parametersText = mdecrypt_generic($td, base64_decode( $encryptedParametersText ) );
            $parametersText = self::pkcs5_unpad( $parametersText );
            mcrypt_generic_deinit($td);
    
            // Decrypt the signature value
            mcrypt_generic_init($td, $key, $iv);
            $hash = mdecrypt_generic($td, base64_decode( $signatureText ) );
            $hash = bin2hex( self::pkcs5_unpad( $hash ) );
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);

            // Compute the SHA1 hash of the parameters
            $computedHash = sha1( $parametersText );
            
            // Check the provided SHA1 hash against the computed one
            if ( $computedHash != $hash ){
                //Invalid parameters signature;
                return false;
            }
        }else{
            //Encrypt
            $iv = $static_mode?$iv:mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
            
            mcrypt_generic_init($td, $key, $iv);
            $parametersText = self::pkcs5_pad( $encryptedParametersText, $size );
            $parametersText = mcrypt_generic($td, $parametersText);
            mcrypt_generic_deinit($td);
            $parametersText = base64_encode($parametersText);
            
            // Compute the SHA1 hash of the parameters
            $computedHash = sha1( $encryptedParametersText );
            
            mcrypt_generic_init($td, $key, $iv);
            $computedHash = self::hex2bin($computedHash);
            $computedHash = self::pkcs5_pad( $computedHash, $size );
            $signatureText = mcrypt_generic($td, $computedHash);
            mcrypt_generic_deinit($td);
            
            $signatureText = base64_encode($signatureText);
            
            mcrypt_module_close($td);
        }
        
        return array($parametersText, $signatureText, $iv);
    }

    private function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

    
    private function pkcs5_pad ($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    
    private function hex2bin($str) {
        $bin = "";
        $i = 0;
        do {
            $bin .= chr(hexdec($str{$i}.$str{($i + 1)}));
            $i += 2;
        } while ($i < strlen($str));
        return $bin;
    }
}