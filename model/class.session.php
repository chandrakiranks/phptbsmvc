<?php

class session
{
    function __construct()
    {
        @session_start();
    }

    function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    function get($name)
    {
        if (isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
        else
        {
            return false;
        }
    }

    function del($name)
    {
        unset($_SESSION[$name]);
    }

    function destroy()
    {
        $_SESSION = array(); // Destroy variables
        @session_unset();
        @session_destroy(); // Destroy session
        unset($_COOKIE[session_name()]);
        setcookie(session_name(), '', time()-300, '/', '', 0); // Destroy cookie.
    }

    function regenerate()
    {
        return true;
        $oldSessionID = session_id();
        session_regenerate_id();
        @unlink(session_save_path(). "/sess_" . $oldSessionID);
    }


}// end class

?>