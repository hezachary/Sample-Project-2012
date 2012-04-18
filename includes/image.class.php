<?php
class image {
    
    public static function imageResizeCalculation ($image, $aryStandard, $x, $y, $fix = null) {
        $aryInput = array($x, $y);
        
        $aryFitStand =array();
        
        $s = $aryStandard[0]/$aryStandard[1];
        $i = $aryInput[0]/$aryInput[1];
    
        if (($s > $i && empty($fix)) || $fix == 'height'){
            $aryFitStand[0] = round($aryStandard[1]*$i);
            $aryFitStand[1] = $aryStandard[1];
        }elseif(($s < $i && empty($fix)) || $fix == 'width'){
            $aryFitStand[0] = $aryStandard[0];
            $aryFitStand[1] = round($aryStandard[0]/$i);
        }else{
            $aryFitStand = $aryStandard;
        }
        return $aryFitStand;
    }
    
    /* resize a image 
     * to x * y
     * type = 0 : x' =>x, y'=>y ; no keep ratio  
     *      = 1 : keep ratio : crop;
     *        = 2 : keep ratio : fit in side (fill grey area)
     *         = + 512 : will not keep long & wide 
     *                   otherwise  will try to keep L&W eg. pic 600x800 =>640x480 will be 480x640
     *                    keek long long & wide wide  
     */
    public static function imageResize($image,$nx=0,$ny=0,$ox,$oy,$type=1)
    {
    
        if(!($ox && $oy) ) return false;        //not doing zero size  
        
        if(!$nx && !$ny){
            $nx=80;
            $ny=60;
        }
        if(!$nx) $nx = round($ny * $ox / $oy);
        if(!$ny) $ny = round($nx * $oy / $ox);
        
         if( (($ox>$oy) xor ($nx>$ny))  && !$type&512 ) list($nx,$ny) = array($ny,$nx);
         
        $img = imagecreatetruecolor($nx, $ny);
        $bg = imagecolorallocate($img, 180, 180, 180);    // create grey background
        imagefill($img, 0, 0, $bg);
        $nr = $nx/$ny;
        $or = $ox/$oy;
        
        $dx= $dy = 0;
        $sx= $sy = 0;
        $nw = $nx;
        $nh = $ny;
        $ow = $ox;
        $oh = $oy;
        switch($type & 15) { //type only
            case 1:
                if($nr<$or)    {
                    $ow = $oy*$nr;
                    $sx = ($ox - $ow)/2;
                }else{
                    $oh = $ox/$nr;
                    $sy= ($oy - $oh)/2;
                }
                break;
            case 2:
                if($nr>$or)    {
                    $nw = $ny*$or;
                    $dx = ($nx - $nw)/2;
                }else{
                    $nh = $nx/$or;
                    $dy = ($ny - $nh)/2;
                }
                break;                
        }
        
        imagecopyresampled($img, $image, (int)$dx, (int)$dy,(int)$sx,(int)$sy, (int)$nw, (int)$nh, (int)$ow, (int)$oh);        
        return $img;
        
    }

    public static function convertPng2Jpg($strSourceFileName, $strExportFileName, $strLocation, $intRed , $intGreen , $intBlue, $intQuality = 100){
        $tmpPng = imagecreatefromstring(file_get_contents($strLocation.$strSourceFileName));
        imagealphablending($tmpPng, false);
        imagesavealpha($tmpPng, true);
        $intWidth = imagesx($tmpPng);
        $intHeight = imagesy($tmpPng);
        $imgJpg = imagecreatetruecolor($intWidth, $intHeight);
        $bgImg = imagecolorallocate($imgJpg, $intRed, $intGreen, $intBlue);    // create grey background
        imagefill($imgJpg, 0, 0, $bgImg);
        imagecopy($imgJpg, $tmpPng, 0,0,0,0, $intWidth, $intHeight);
        imagejpeg($imgJpg, $strLocation.$strExportFileName, $intQuality);
        imagedestroy($imgJpg);
        imagedestroy($tmpPng);
    }

    public static function saveImage($strFileName, $strLocation, $strType, $arySizeList, $intQuality = 90){
        
        $tmpImage_Org = imagecreatefromstring(file_get_contents($strLocation.$strFileName));
        
        foreach ($arySizeList as $strSizeName => $arySizeGroup){
            $newPath = $strLocation.$strFileName.'_'.$strSizeName;
            @unlink($newPath);
            
            $intImageX = imagesx($tmpImage_Org);
            $intImageY = imagesy($tmpImage_Org);
            
            $arySize = $intImageX>$intImageY?$arySizeGroup[0]:$arySizeGroup[1];
            
            $arySize = isset($arySize[3])?self::imageResizeCalculation($tmpImage_Org, $arySize, $intImageX, $intImageY, $arySize[3]):$arySize;
            
            $tmpImage = self::imageResize($tmpImage_Org, $arySize[0], $arySize[1], $intImageX, $intImageY, $arySize[2]);
            switch ($strType){
                default:
                case "image/jpeg":
                    imagejpeg($tmpImage, $newPath, $intQuality);
                    break;
//                case "image/gif":
//                    imagegif($tmpImage, $newPath);
//                    break;
//                case "image/png":
//                    imagepng($tmpImage, $newPath, 1);
//                    break;
//                default:
//                    break;
            }
            imagedestroy($tmpImage);
        }
        
        return true;
    }

}