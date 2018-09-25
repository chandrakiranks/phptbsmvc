<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

function autoload($class)
{
    require_once('./model/class.'.$class.'.php');
}
spl_autoload_register('autoload');

$session    = new session();

$loginHash  = $session->get('login_hash');
$loginUser  = $session->get('user');
if($loginHash){
    header('Location: '.utility::APP_DIR.utility::LOGIN_LANDING_PAGE);
    exit;
}

$redirectTo     = utility::APP_DIR . utility::LOGIN_LANDING_PAGE;
$username       = utility::LOGIN_FORM_USERNAME_VAR;
$password       = utility::LOGIN_FORM_PASSWORD_VAR;

$APP_DIR        = utility::APP_DIR;
$tbs            = new clsTinyButStrong;
$subTemplate    = array('template' => 'view/pages/login.html', 'title' => 'Login');
$tbs->LoadTemplate('./view/'. utility::APP_LAYOUT .'/login.html');
$tbs->Show();
exit;