<?php
/**
 * Created by IntelliJ IDEA.
 * User: chandrakiran.ks
 * Date: 24/09/18
 * Time: 6:00 PM
 */

class user
{
    var $db     = NULL;
    var $user   = array();

    function __construct() {
        $this->db   = db::singleton();
        $this->getUser();
    }

    function getUser(){
        $sql    = "SELECT * FROM users";
        $user   = $this->db->get_results($sql, ARRAY_A);
        return $user;
    }

}