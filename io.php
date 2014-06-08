<?php
/*
 * PHP Basic Development Kit by xSplit
 */

class Dir
{
    public static function create($dir,$mode=0777,$recursive=true)
    {
        mkdir($dir,$mode,$recursive);
    }

    public static function rename($dir,$dir2)
    {
        return is_dir($dir)?rename($dir,$dir2):false;
    }

    public static function move($dir,$dir2)
    {
        self::copy($dir,$dir2);
        self::delete($dir);
    }

    public static function delete($dir)
    {
        if(!is_dir($dir)) return false;
        foreach(glob($dir.'/*') as $r)
            is_file($r)?File::delete($r):self::delete($r);
        return rmdir($dir);
    }

    public static function copy($dir,$dir2)
    {
        if(!is_dir($dir)) return false;
        if(!is_dir($dir2)) Dir::create($dir2);
        foreach(glob($dir.'/*') as $r)
        {
            $ro = $r;
            $r = $dir2.'/'.str_replace($dir.'/','',$r);
            is_file($ro)?File::copy($ro,$r):self::copy($ro,$r);
        }
        return true;
    }

    public static function getZip($dir,$zipf)
    {
        $zip = new ZipArchive();
        if($zip->open($zipf,ZipArchive::CREATE)===true)
        {
            if(!is_dir($dir)) return false;
            $add_files = function(ZipArchive &$zip,$dir) use(&$add_files){
                foreach(glob($dir.'/*') as $r)
                {
                    if(is_file($r))
                        $zip->addFile($r);
                    else
                    {
                        $zip->addEmptyDir($dir);
                        $add_files($zip,$r);
                    }
                }
            };
            $add_files($zip,$dir);
            $zip->close();
            return true;
        }
        return false;
    }
}

class File
{
    public static function readLines($file,$start,$end)
    {
        return is_file($file)?array_slice(file($file),$start,$end):false;
    }

    public static function readAll($file)
    {
        return is_file($file)?file_get_contents($file):false;
    }

    public static function write($file,$content,$flags=0)
    {
        file_put_contents($file,$content,$flags);
    }

    public static function append($file,$content)
    {
        self::write($file,$content,FILE_APPEND);
    }

    public static function prepend($file,$content)
    {
        self::write($file,$content.self::readAll($file));
    }

    public static function create($file)
    {
        self::write($file,'');
    }

    public static function rename($file,$file2)
    {
        return is_file($file)?rename($file,$file2):false;
    }

    public static function move($file,$file2)
    {
        self::copy($file,$file2);
        self::delete($file);
    }

    public static function delete($file)
    {
        return is_file($file)?unlink($file):false;
    }

    public static function copy($file,$file2)
    {
        return is_file($file)?copy($file,$file2):false;
    }
}
