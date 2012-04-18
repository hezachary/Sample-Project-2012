<?php
//DB table tbl_extra_panel
$config['project_sync']->section_name = 'Maintain Project';
$config['project_sync']->default_php = 'index.php';
$config['project_sync']->tpl = 'project_sync.tpl';
$config['project_sync']->ajax_php = 'ajax.php';
$config['project_sync']->svn['url'] = 'http://192.168.1.3/svn/Test/trunk';
$config['project_sync']->svn['id'] = 'zac';
$config['project_sync']->svn['pw'] = '4863are';
$config['project_sync']->svn['folder_list'] = array(
    'data' => array(
        'chmod' => '0600',
    ),
    'public_data' => array(
        'chmod' => '0655',
    ),
);
?>