<?php
/*
 * PHP Basic Development Kit - Http Handling
 */

class Request
{
    public static function getHeaderString($name)
    {
        return isset($_SERVER['HTTP_'.$name]) ? $_SERVER['HTTP_'.$name] : false;
    }

    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getDomain()
    {
        $scheme  = isset($_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
        return $scheme.'://'.$_SERVER['SERVER_NAME'];
    }

    public static function getUrl()
    {
        return self::getDomain().$_SERVER['PHP_SELF'];
    }

    public static function getFullUrl()
    {
        $query = !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '';
        return self::getUrl().$query;
    }

    public static function isAjax()
    {
        return self::getHeaderString('X_REQUESTED_WITH') === 'XMLHttpRequest';
    }
}

class Response
{
    public static function setContentType($type)
    {
        header('Content-Type: '.$type);
    }

    public static function redirect($page,$delay=null)
    {
        if(is_int($delay))
            header("Refresh: $delay; url=$page");
        else
            header("Location: $page");
    }

    public static function asJSON(array $json)
    {
        self::setContentType('application/json');
        echo json_encode($json);
    }

    public static function asXML(SimpleXMLElement $xml)
    {
        self::setContentType('application/xml');
        echo $xml->asXML();
    }

    public static function downloadFile($file,$download_name,$mime,array $hoptions=array())
    {
        if (is_file($file))
        {
            self::setContentType($mime);
            header('Content-Disposition: attachment; filename='.$download_name);
            header('Content-Length: ' . filesize($file));
            foreach($hoptions as $option => $value) header($option.': '.$value);
            readfile($file);
            return true;
        }
        return false;
    }
}
