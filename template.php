<?php
    function autoload($class)
    {
        require_once('./model/class.'.$class.'.php');
    }
    spl_autoload_register('autoload');

    $user   = new user();
    $users  = $user->getUser();

    $tbs            = new clsTinyButStrong();
	$subTemplate    = array('template' => 'view/pages/page.html', 'title' => "User List");
	$tbs->LoadTemplate('./view/'. utility::APP_LAYOUT .'/layout.html');
    $tbs->MergeBlock('usersBlk', $users);
	$tbs->Show();
	exit;
?>
