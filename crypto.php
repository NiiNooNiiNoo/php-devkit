<?php
/*
 * PHP Basic Development - Cryptography Class
 */

class Crypto
{
    public static function getHash($type,$value)
    {
        if(in_array($type,hash_algos()))
            return hash($type,$value);
        return false;
    }

    public static function crypt($key,$value)
    {
        return rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$key,utf8_decode($value),MCRYPT_MODE_ECB,
            mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB),MCRYPT_RAND))),"\0");
    }

    public static function decrypt($key,$value)
    {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,base64_decode($value),MCRYPT_MODE_ECB,
            mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB),MCRYPT_RAND)),"\0");
    }

    public static function __callStatic($name,$args)
    {
        return Crypto::getHash($name,$args[0]);
    }
}
