<?php
/*
 * PHP Basic Development Kit - Http Class
 */
 
class CURL
{
    public static function get($url,array $vars=array(),$cookie=null,array $options=array())
    {
        return self::req($url,$vars,'get',$cookie,array(),$options);
    }

    public static function post($url,array $vars=array(),$cookie=null,$files=array(),array $options=array())
    {
        return self::req($url,$vars,'post',$cookie,$files,$options);
    }

    private static function req($url,array $vars,$method,$cookie,array $files,array $options)
    {
        $c = new CURLRequest($url,$method,$vars);
        $c->setOptions($options);
        if(!is_null($cookie))
            $c->setCookieFile($cookie);
        if(substr($url,0,5)=='https')
            $c->ignoreSSL();
        $c->addFiles($files);
        $r = $c->send();
        $c->close();
        return $r;
    }
}

class CURLRequest
{
    private $ch;
    private $url;
    private $data;
    private $is_post;

    public function __construct($url,$method='get',array $data=array())
    {
        $this->url = $url;
        $this->ch = curl_init($url);
        $this->data = $data;
        $this->setMethod($method);
        $this->setOptions(array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7'
        ));
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function setMethod($method)
    {
        $this->is_post = strtolower($method)=='post';
        curl_setopt($this->ch,CURLOPT_POST,$this->is_post);
    }

    public function setOptions(array $options)
    {
        curl_setopt_array($this->ch, $options);
    }

    public function setDataFromUrl($data)
    {
        $rdata = array();
        foreach(explode('&',$data) as $val)
        {
            $f = explode('=',$val);
            $rdata[$f[0]] = $f[1];
        }
        $this->setData($rdata);
    }

    public function setCookieFile($file)
    {
       $this->setOptions(array(
            CURLOPT_COOKIEFILE => $file,
            CURLOPT_COOKIEJAR => $file
        ));
    }

    public function addFiles(array $files)
    {
        if(!$this->is_post) return;
        foreach($files as $name => $file)
            $this->data[$name] = '@'.realpath($file);
    }

    public function ignoreSSL()
    {
        $this->setOptions(array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
    }

    public function send()
    {
        if($this->is_post)
            curl_setopt($this->ch,CURLOPT_POSTFIELDS,$this->data);
        else if(count($this->data)>0)
            curl_setopt($this->ch,CURLOPT_URL,$this->url.'?'.http_build_query($this->data));
        $r = curl_exec($this->ch);
        return $r;
    }

    public function getInfo()
    {
        return curl_getinfo($this->ch);
    }

    public function close()
    {
        curl_close($this->ch);
    }
}
