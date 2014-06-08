<?php
/*
 * PHP Basic Development Kit by xSplit
 */

class Image
{
    private $gd,$type,$width,$height;

    public function __construct($img)
    {
        if(self::isGd($img))
        {
            $this->gd = $img;
            $this->width = imagesx($img);
            $this->height = imagesy($img);
            $this->type = 'png';
        }
        else
        {
            list($this->width,$this->height) = getimagesize($img);
            $this->type = self::getType($img);
            $this->gd = call_user_func('imagecreatefrom'.$this->type,$img);
        }
    }

    public function resize($width,$height)
    {
        $origin = $this->gd;
        $this->gd = imagecreatetruecolor($width,$height);
        imagecopyresized($this->gd,$origin,0,0,0,0,$width,$height,$this->width,$this->height);
    }

    public function cut($x,$y,$width,$height)
    {
        $origin = $this->gd;
        $this->gd = imagecreatetruecolor($width, $height);
        imagecopy($this->gd,$origin,0,0,$x,$y,$width,$height);
    }

    public function paste(Image $img,$x,$y,$width=null,$height=null)
    {
        imagealphablending($this->gd,false);
        imagesavealpha($this->gd,true);
        imagecopymerge($this->gd,$img->getGD(),$x,$y,0,0,$width?$width:$img->getWidth(),$height?$height:$img->getHeight(),100);
    }

    public function write($text,$x,$y,$color,$font)
    {
        imagestring($this->gd,$font,$x,$y,$text,self::isGd($color)?$color:$this->allocHexColor($color));
    }

    public function allocHexColor($hex)
    {
        $hex = substr($hex,-6);
        return imagecolorallocate($this->gd,hexdec(substr($hex,0,2)),hexdec(substr($hex,2,2)),hexdec(substr($hex,4,2)));
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getGD()
    {
        return $this->gd;
    }

    public function out($file=null)
    {
        call_user_func('image'.$this->type,$this->gd,$file);
    }

    public function destroy()
    {
        imagedestroy($this->gd);
    }

    public static function isGd($img)
    {
        return is_resource($img) && get_resource_type($img)=='gd';
    }

    public static function getType($img)
    {
        return substr(image_type_to_extension(exif_imagetype($img)),1);
    }

    public static function getBase64Image($img)
    {
        return 'data:image/'.self::getType($img).';base64,'.base64_encode(file_get_contents($img));
    }
}
