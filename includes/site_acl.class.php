<?php

class site_acl{
    
    public static $PAGE = array();
    
    public static $ACLRole = array(
        'user'    => null,
        'staff'    => 'user',
        'admin' => -1,
    );

    public static $ACL = array(
        'admin' => array(
            'allow' => array(
                array('staff'),
                array('admin'),
            ),
            'deny' => array(
                array('user'),
            ),
            //If you have default CMS control, put here (do not conflict with your mod config)
            'CMS' => array(
                'allow' => array(
                    array('staff', 'view'),
                    array('staff', 'edit'),
                    array('staff', 'add'),
                ),
                'deny' => array(
                ),
                'content' => array(
                    'allow' => array(
                        array('staff', 'view'),
                        array('staff', 'edit'),
                        array('staff', 'add'),
                    ),
                    'deny' => array(
                    ),
                ),
                'map_edit' => array(
                    'allow' => array(
                    ),
                    'deny' => array(
                        array('staff', 'view'),
                    ),
                ),
            ),
        ),
    );
    
    public static function acl(){
        foreach(self::$PAGE as $strPageName => $objPage){
            self::$ACL['admin']['CMS'][$strPageName]['allow'] = $objPage->allow;
            self::$ACL['admin']['CMS'][$strPageName]['deny'] = $objPage->deny;
        }
        self::populate_acl(current(self::$ACL), key(self::$ACL));
    }
    
    public static function populate_acl($aryAcl = array(), $strAclParentName = null){
        $objAcl = new Zend_Acl();
        
        //add role
        $aryTmpAcl = array();
        foreach(self::$ACLRole as $strRole => $strInheritRole){
            $$strRole = new Zend_Acl_Role($strRole);
            $aryTmpAcl[$strRole] = $$strRole;
            if(empty($strInheritRole)){
                $objAcl->addRole($$strRole);
            }else{
                $objAcl->addRole($$strRole, $aryTmpAcl[$strInheritRole]);
                if($strInheritRole == -1){
                    $objAcl->allow($strRole);
                }
            }
        }
        if($strAclParentName){
            $objAcl->add(new Zend_Acl_Resource($strAclParentName));
        }
        self::setAcl($objAcl, $strAclParentName, $aryAcl);
    }
    
    public static function setAcl($objAcl, $strAclParentName, $aryAcl){
        $objCurrentUser = user::get_handle();
        
        foreach($aryAcl as $strAclName => $aryAclSetting){
            switch($strAclName){
                case 'allow':
                    foreach($aryAclSetting as $aryAllow){
                        $objAcl->allow($aryAllow[0], $strAclParentName, $aryAllow[1]);
                    }
                    break;
                case 'deny':
                    foreach($aryAclSetting as $aryDeny){
                        $objAcl->deny($aryDeny[0], $strAclParentName, $aryDeny[1]);
                    }
                    break;
                default:
                    $objAcl->add(new Zend_Acl_Resource($strAclName));
                    self::setAcl($objAcl, $strAclName, $aryAclSetting);
                    break;
            }
        }
        if($strAclParentName){
            $objCurrentUser->ACL->$strAclParentName = array(
                                                'view' => $objAcl->isAllowed($objCurrentUser->role, $strAclParentName, 'view'),
                                                'edit' => $objAcl->isAllowed($objCurrentUser->role, $strAclParentName, 'edit'),
                                                'add' => $objAcl->isAllowed($objCurrentUser->role, $strAclParentName, 'add'),
                                                );
        }
    }
    
}


