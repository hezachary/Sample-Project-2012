<?php
set_time_limit(0); 
define('ABSPATH', dirname(__FILE__).'/');
if(file_exists(ABSPATH.'index.php')):
    echo 'Project already deploied, install cannot run twice unless you remove the index.php and backup all your data.';
    exit();
endif;


if(!is_writable(ABSPATH)):
	echo 'No Write Permission';
else:
	echo 'Writable';
    $out = array();
    exec('svn checkout {$CFG->project_sync->svn.url} '.ABSPATH.' --username {$CFG->project_sync->svn.id} --password {$CFG->project_sync->svn.pw} --no-auth-cache', $out);
    echo '<pre>';
    print_r($out);
    flush();
    
    /*
    {foreach from=$CFG->project_sync->svn.folder_list key='folder' item='access'}
    */
    
    $target_folder = ABSPATH.'{$folder}';
    @mkdir($target_folder);
    chmod($target_folder, '{$access.chmod}');
    file_put_contents($target_folder.'.htaccess', '{$access.htaccess|@addslashes}');
    
    /*
    {/foreach}
    */
endif;
?>