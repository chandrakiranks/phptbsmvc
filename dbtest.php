<?php

    function autoload($class)
    {
        require_once('./model/class.'.$class.'.php');
    }
    spl_autoload_register('autoload');

    $user   = new user();
    $users  = $user->getUser();

    print '<pre>'; print_r($users); print '<pre>';
?>
