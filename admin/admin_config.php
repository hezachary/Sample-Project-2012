<?php
require_once('../config.php');
Zend_Session::start();
####Get $USER####
user::getUser();
site_acl::acl();