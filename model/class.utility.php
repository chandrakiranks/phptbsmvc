<?php

class utility
{
    const APP_AUTH_KEY                  = 'secretpasswordtohash';
    const APP_DIR                       = './';
    const APP_LAYOUT                    = 'layout';

    const LOGIN_FORM_USERNAME_VAR       = 'username';
    const LOGIN_FORM_PASSWORD_VAR       = 'password';

    const LOGIN_USER_TABLE_VAR          = 'users';
    const LOGIN_USER_TABLE_USERID_VAR   = 'id';
    const LOGIN_USER_TABLE_ACCTNAME_VAR = 'name';
    const LOGIN_USER_TABLE_USERNAME_VAR = 'username';
    const LOGIN_USER_TABLE_PASSWORD_VAR = 'password';
    const LOGIN_USER_TABLE_ROLE_VAR     = 'type';
    const LOGIN_LANDING_PAGE            = 'profile.php';
    const LOGIN_PAGE                    = 'login.php';
    const LOGOUT_PAGE                   = 'logout.php';

    static function isLeapYear($year){
        if($year%4){return false;} else {return true;}
    }

    static function sayHello() {
        return 'Hello';
    }

    static function testDbConnect() {
        $db = db::singleton();
        $sql    = "SELECT * FROM users";
        $result = $db->get_results($sql, ARRAY_A);
        return $result;
    }

    static function getFieldsOfTable($tblName = NULL){
        $db = db::singleton();
        if($tblName){
            $sql    = "select column_name from information_schema.columns where TABLE_SCHEMA = '{$db->dbname}' AND table_name='{$tblName}'";
            return $db->get_results($sql, ARRAY_A);
        }

        return array();
    }

    static function convertOneToTwoDimesionArray($array = array(), $key = 'key', $value = 'value'){
        $result = array();
        if(is_array($array)){
            $i = 0;
            foreach($array as $k => $v){
                $result[$i][$key] = $k;
                $result[$i][$value] = $v;
                $i++;
            }
        }
        return $result;
    }

    static function isNullOrEmpty($var){
        if(!is_null($var) && strlen($var) > 0){
            return false;
        }

        return true;
    }

    static function convertDateTimeToTimestamp($dateTime, $h = 12, $m = 12, $s = 12){
        if($dateTime){
            if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4})$/', $dateTime, $match)){
                if(is_array($match) && count($match) == 4){
                    $timeStamp   = mktime($h, $m, $s, $match['1'], $match['2'], $match['3']);
                    return $timeStamp;
                }
            }
        }
        return NULL;
    }

    static function groupArrayByValue($resource, $attribute){
        $result = array();
        if(is_array($resource) && count($resource) && $attribute){
            foreach($resource as $key => $val){
                if(!isset($result[$val[$attribute]])){
                    $result[$val[$attribute]] = array('name' => ucwords(str_replace('_', ' ', $val[$attribute])), $attribute => array());
                }

                $result[$val[$attribute]][$attribute][]   = $val;
            }
        }

        return $result;
    }

    static function lockTables($otherTables = array())
    {
        $db         = db::singleton();
        $tableList  = array('users');

        if(is_array($otherTables) && count($otherTables)){
            $tableList  = array_merge($tableList, $otherTables);
        }

        $query_lock = "LOCK TABLE ";
        foreach ($tableList as $key=>$val){
            $tableList[$key] .= " WRITE";
        }

        $query_lock .= implode(', ', $tableList);
        $db->query($query_lock);

        return true;
    }

    static function unlockTables()
    {
        $db             = db::singleton();
        $query_unlock   = "UNLOCK TABLES";
        $db->query($query_unlock);

        return true;
    }

    static function sanitizeData($data = NULL){
        if(!empty($data) || $data == 0){
            return strip_tags($data);
        }
        return NULL;
    }

    static function escape($str)
    {
        if ( is_array($str) )
        {
            foreach($str as $key => $val)
            {
                if ( is_string($val) )
                    $str[$key] = mysqli_real_escape_string ( stripslashes(trim($val)) );
            }
        }

        if ( is_string($str) ) {
            $str = mysqli_real_escape_string(stripslashes(trim($str)));
        }

        return $str;
    }

    static function getHashOf($str){
        return md5($str.self::APP_AUTH_KEY);
    }
}