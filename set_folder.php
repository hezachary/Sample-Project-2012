<?php
echo posix_getuid();die();
/**
 * Folder List Define
 * 
 * chmod permission [0][owner][group][public]
 * # 	Permission
 * 7 	full
 * 6 	read and write
 * 5 	read and execute
 * 4 	read only
 * 3 	write and execute
 * 2 	write only
 * 1 	execute only
 * 0 	none
 **/
$aryFolderListDefine = array(
    'data' => array(
        'chmod' => '0700',
    ),
    'session' => array(
        'chmod' => '0700',
    ),
    'tpl' => array(
        'chmod' => '0700',
    ),
);

define('DIRROOT', dirname(__FILE__).'/');

foreach($aryFolderListDefine as $strFolderPath => $arySvnSetting){
    $strHtaccessFilePath = DIRROOT . ($arySvnSetting['htaccess'] ? $arySvnSetting['htaccess'] : $strFolderPath.'/.htaccess');
    if(file_exists($strHtaccessFilePath)){
        $aryFolderListDefine[$strFolderPath]['htaccess'] = file_get_contents($strHtaccessFilePath);
    }
}
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename=install.php');
echo '<?php';
?>

define('ABSPATH', dirname(__FILE__).'/');

echo '<pre>';

if(!is_writable(ABSPATH)):
	echo 'No Write Permission';
else:
	echo 'Writable';
    
    <?php
    foreach($aryFolderListDefine as $folder => $access):
    ?>
    
    $target_folder = ABSPATH.'<?= $folder ?>';
    @mkdir($target_folder);
    chmod($target_folder, '<?= $access['chmod'] ?>');
    file_put_contents($target_folder.'/.htaccess', '<?= addslashes($access['htaccess']) ?>');
    
    <?php
    endforeach;
    ?>
    
endif;

<?php
echo '?>';
?>
