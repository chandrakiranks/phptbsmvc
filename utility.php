<?php

    function autoload($class)
    {
        require_once('./model/class.'.$class.'.php');
    }
    spl_autoload_register('autoload');

    print utility::getHashOf('000000');

?>
