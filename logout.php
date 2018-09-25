<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

function autoload($class)
{
    require_once('./model/class.'.$class.'.php');
}
spl_autoload_register('autoload');

$auth   = new auth();
$auth->logout();
exit;