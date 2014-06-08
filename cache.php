<?php
/*
 * PHP Basic Development Kit by xSplit
 */

class Cache
{
    private static $cache_dir='/caches';

    public static function setCacheDir($dir)
    {
        if(!is_dir($dir)) mkdir($dir);
        self::$cache_dir = $dir;
    }

    public static function store($name,$content,$fix,$time=3600)
    {
        file_put_contents(self::$cache_dir.'/'.md5($name).'_'.(time()+$time).'_'.$fix.'.tmp',$content);
    }

    public static function get($name)
    {
        foreach(glob(self::$cache_dir.'/*.tmp') as $file)
        {
            $e = explode('_',$file);
            if(basename($e[0])==md5($name) && count($e)>2)
            {
                if($e[1]>time())
                    return file_get_contents($file);
                else
                {
                    unlink($file);
                    break;
                }
            }
        }
        return false;
    }

    public static function clean($name)
    {
        foreach(glob(self::$cache_dir.'/*.tmp') as $file)
        {
            $e = explode('_',$file);
            if(basename($e[0])==md5($name) && count($e)>2)
                unlink($file);
        }
    }
}
