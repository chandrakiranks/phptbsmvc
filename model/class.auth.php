<?php

class auth
{
    var $db;
    var $redirect;
    var $hashKey;
    var $md5;
    var $session;
    var $user;
    var $loggedin;

    function __construct()
    {
        $this->db       = db::singleton();
        $this->session  = new session();
        $this->redirect = utility::APP_DIR.utility::LOGIN_PAGE;
        $this->hashKey  = utility::APP_AUTH_KEY;
        $this->md5      = true;
        $this->user     = null;
        $this->login();
    }

    function login()
    {
        if ($this->session->get('login_hash'))
        {
            $this->confirmAuth();
            $this->session->regenerate();
            return;
        }

        /* Fresh login */
        if ( !isset($_POST[utility::LOGIN_FORM_USERNAME_VAR]) || !isset($_POST[utility::LOGIN_FORM_PASSWORD_VAR]) )
        {
            $this->redirect(true, 'session has expired or you have not logged in');
        }

        // do validation here
        $errors = NULL;
        if(!validator::username($_POST[utility::LOGIN_FORM_USERNAME_VAR])){
            $errors['username'] = "Invalid username";
        }

        if(!validator::password($_POST[utility::LOGIN_FORM_PASSWORD_VAR])){
            $errors['password'] = "Invalid password";
        }

        if($errors != null){
            $this->session->set('errors', $errors);
            $this->redirect(true, 'Invalid login credentials');
            exit;
        }

        $password	    = $this->db->escape($_POST[utility::LOGIN_FORM_PASSWORD_VAR]);
        $login		    = $this->db->escape($_POST[utility::LOGIN_FORM_USERNAME_VAR]);
        $password	    = $this->db->escape($password);
        $md5Password    = md5($password.utility::APP_AUTH_KEY);

        $sql     = "SELECT * FROM ". utility::LOGIN_USER_TABLE_VAR ." WHERE " . utility::LOGIN_USER_TABLE_USERNAME_VAR . "='{$login}'";
        $sql    .=	" AND " . utility::LOGIN_USER_TABLE_PASSWORD_VAR . "='{$md5Password}'";
        $user    = $this->db->get_row($sql, ARRAY_A);

        if ($user[utility::LOGIN_USER_TABLE_USERNAME_VAR] === $_POST[utility::LOGIN_FORM_USERNAME_VAR])
        {
            $this->storeAuth($user);
        }
        else
        {
            $this->redirect(true, 'Invalid login credentials');
        }
    }

    function storeAuth($user)
    {
        $this->user = array('name' => $user[utility::LOGIN_USER_TABLE_ACCTNAME_VAR], utility::LOGIN_USER_TABLE_USERID_VAR => $user[utility::LOGIN_USER_TABLE_USERID_VAR], utility::LOGIN_USER_TABLE_USERNAME_VAR => $user[utility::LOGIN_USER_TABLE_USERNAME_VAR], utility::LOGIN_USER_TABLE_ROLE_VAR => $user[utility::LOGIN_USER_TABLE_ROLE_VAR]);
        $this->session->set('user', $this->user);

        $hashKey = md5($this->hashKey . $user[utility::LOGIN_USER_TABLE_USERID_VAR] . $user[utility::LOGIN_USER_TABLE_USERNAME_VAR] . $user[utility::LOGIN_USER_TABLE_ROLE_VAR]);
        $this->session->set('login_hash', $hashKey);
        $this->session->set('login_ua', md5($_SERVER['HTTP_USER_AGENT']));

        $this->session->set('alert-success', 'You logged in successfully');

        //hack to direct the dealer to business actions page on login
        //header('Location: '.utility::APP_DIR.'profile.php');
        return true;
    }

    function confirmAuth()
    {
        $user       = $this->session->get('user');
        $hashKey    = $this->session->get('login_hash');
        $ua         = $this->session->get('login_ua');

        if (md5($this->hashKey.$user[utility::LOGIN_USER_TABLE_USERID_VAR].$user[utility::LOGIN_USER_TABLE_USERNAME_VAR].$user[utility::LOGIN_USER_TABLE_ROLE_VAR]) != $hashKey){
            $this->logout(false);
        }
        if (md5($_SERVER['HTTP_USER_AGENT']) != $ua){
            $this->logout(false);
        }

        return true;
    }

    function logout($from = false)
    {
        $this->destroy();
        $this->session  = new session();
        $this->redirect($from);
    }

    function destroy()
    {
        $this->session->del('user');
        $this->session->del('login_hash');
        $this->session->del('login_ua');
        $this->session->destroy();

        return true;
    }

    function redirect($from = true, $msg = 'Page has expired, please log in again')
    {
        $fromstring = '';
        if ( $from && !strstr($_SERVER['REQUEST_URI'], utility::LOGOUT_PAGE) ){
            $fromstring .= $_SERVER['REQUEST_URI'];
        }

        // $this->session->set('alert-success', $msg);
        $this->session->set('fromurl', $fromstring);
        header("Location: " . $this->redirect);
        exit();
    }

    function checkAttempts($login)
    {
        return true;
    }

    function isAuthorizedUser($loginTypes = array(), $user = null){
        if(is_array($loginTypes) && count($loginTypes) && $user){
            if(in_array($user, $loginTypes)){
                return true;
            } else {
                header('Location: '.utility::APP_DIR.utility::LOGIN_LANDING_PAGE);
                exit;
            }
        }
    }

} // end class auth