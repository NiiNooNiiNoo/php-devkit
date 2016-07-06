<?php
/*
 * PHP Basic Development Kit - Auth Classes
 */
 
class Cookie
{
    public static function set($name,$value,$time=86400,$path=null,$domain=null,$secure=false,$httponly=true)
    {
        setcookie($name,$value,time()+$time,$path,$domain,$secure,$httponly);
    }

    public static function has($name)
    {
        return isset($_COOKIE[$name]);
    }

    public static function get($name)
    {
        return self::has($name) ? $_COOKIE[$name] : false;
    }

    public static function remove($name)
    {
        self::set($name,'',-86400);
    }
}

class Session
{
    public static function set($name,$value)
    {
        $_SESSION[$name] = $value;
    }

    public static function has($name)
    {
        return isset($_SESSION[$name]);
    }

    public static function get($name)
    {
        return self::has($name) ? $_SESSION[$name] : false;
    }

    public static function remove($name)
    {
        unset($_SESSION[$name]);
    }

    public static function setMaxTime($time)
    {
        ini_set('session.gc_maxlifetime',$time);
        session_set_cookie_params($time);
    }
}

class User
{
    public $data = array();

    public function __construct(array $data=array())
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : false;
    }

    public function __set($name,$value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    public function toJson()
    {
        return json_encode($this->data);
    }

    public function serialize()
    {
        return serialize($this->data);
    }

    public static function getIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}
