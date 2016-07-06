<?php
/*
 * PHP Basic Development Kit - Html Composer
 */

class Html
{
    const DOCTYPE =  "<!DOCTYPE html>";

    public static function setIcon($icon)
    {
        self::out('link','',array('href'=>$icon,'rel'=>'shortcut icon'),false);
    }

    public static function includeCss($css)
    {
        self::out('link','',array('href'=>$css,'rel'=>'stylesheet','type'=>'text/css'),false);
    }

    public static function includeScript($script)
    {
        self::out('script','',array('type'=>'text/javascript','src'=>$script));
    }

    public static function out($tag,$content='',array $attributes=array(),$close=true)
    {
        echo (new HtmlElement($tag,$content,$attributes,$close))->render();
    }
}

class HtmlElement
{
    public $tag,$content,$close;
    private $attributes,$nodes = array();

    public function __construct($tag,$content='',array $attributes=array(),$close=true)
    {
        $this->tag = $tag;
        $this->content= $content;
        $this->attributes = $attributes;
        $this->close = $close;
    }

    public function render()
    {
        return '<'.$this->tag.implode('',array_map(function($k,$v){return ' '.$k.'="'.$v.'"';},array_keys($this->attributes),$this->attributes)).'>'
        .implode('',array_map(function(HtmlElement $n){return $n->render();},$this->nodes)).$this->content.($this->close?'</'.$this->tag.'>':'');
    }

    public function addNode(HtmlElement $node)
    {
        $this->nodes[] = $node;
    }

    public function &getNode($index)
    {
        $ref = isset($this->nodes[$index]) ? $this->nodes[$index] : false;
        return $ref;
    }

    public function removeNode($index)
    {
        unset($this->nodes[$index]);
    }

    public function setAttributes(array $attributes)
    {
        foreach($attributes as $attr => $value) $this->setAttribute($attr,$value);
    }

    public function setAttribute($attr,$value)
    {
        $this->attributes[$attr] = $value;
    }

    public function removeAttribute($attr)
    {
        unset($this->attributes[$attr]);
    }
}
