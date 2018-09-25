<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

function autoload($class)
{
    require_once('./model/class.'.$class.'.php');
}
spl_autoload_register('autoload');

$session    = new session();
$auth       = new auth();

$loginHash  = $session->get('login_hash');
if(!$loginHash){
    header('Location: '.utility::APP_DIR.utility::LOGIN_PAGE);
    exit;
}

$user           = $session->get('user');

$APP_DIR        = utility::APP_DIR;
$tbs            = new clsTinyButStrong;
$subTemplate    = array('template' => 'view/pages/profile.html', 'title' => 'Profile');
$tbs->LoadTemplate('./view/'. utility::APP_LAYOUT .'/login.html');
$tbs->Show();
exit;