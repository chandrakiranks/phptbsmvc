<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Validator
{
    static function validateUserRegister($name, $email, $email_confirm, $password, $password_confirm, $phone){
        /*
         * Need to check for email && email_confirm
         * Need to check for password && password_confirm
         *
         * check is email already registered?
         *
         * and set error messages properly to all fields
         */

        $allOkay    = true;

        if(self::nullOrEmpty($name) || !self::name($name)){
            $allOkay = false;
            $_SESSION['error']['name'] = 'Please enter the valid name';
        }

        if(self::nullOrEmpty($email) || !self::email($email)){
            $allOkay = false;
            $_SESSION['error']['email'] = 'Please enter the valid email address';
        }

        if($email !== $email_confirm){
            $allOkay = false;
            $_SESSION['error']['email_confirm'] = 'Emails are not same';
        }

        if(self::nullOrEmpty($password) || !self::password($password)){
            $allOkay = false;
            $_SESSION['error']['password'] = 'Please enter the valid password (alphanumeric with $ @ # *)';
        }

        if($password !== $password_confirm){
            $allOkay = false;
            $_SESSION['error']['password_confirm'] = 'Passwords are not same';
        }

        if(!self::nullOrEmpty($phone)){
            if(!self::phone($phone)){
                $allOkay = false;
                $_SESSION['error']['phone'] = 'Please enter the valid phone number (only numeric)';
            }
        }

        return $allOkay;
    }

    static function nullOrEmpty($text){
        if(is_null($text) || empty($text)){
            return true;
        } else {
            return false;
        }
    }

    static function number($number = null){
        if($number){
            return preg_match('/^[0-9\.]*$/', $number);
        }
        return false;
    }
    static function phone($text = null){
        if($text){
            return preg_match('/^[0-9]{8,10}$/', $text);
        }
        return false;
    }

    static function email($text = null){
        if($text){
            return preg_match('/^[a-z0-9\-_]+(\.[_a-z0-9\-]+)*@[a-z0-9\-]+(\.[a-z0-9\-]+)*(\.[a-z]{2,3})$/', $text);
        }
        return false;
    }

    static function name($text = null){
        if($text){
            return preg_match('/^[a-zA-Z0-9\ \.\-\']+$/', $text);
        }
        return false;
    }

    static function address($text = null){
        if($text){
            return preg_match('/^[a-zA-Z0-9\ \.\-\',#\/]+$/', $text);
        }
        return false;
    }

    static function password($text = null){
        if($text){
            return preg_match('/^[a-zA-Z0-9\$@#\*]+$/', $text);
        }
        return false;
    }

    static function username($text = null){
        if($text){
            return preg_match('/^[a-zA-Z0-9]{1,15}$/', $text);
        }
        return false;
    }

    static function accountStatus($status){
        return in_array($status, array('0', '1', '-1'));
    }

    static function dateFormat($text = null){
        if($text){
            return preg_match('/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/', $text);
        }
        return false;
    }

} // end of utility class
?>