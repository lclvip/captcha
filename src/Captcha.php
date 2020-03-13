<?php
namespace lclvip\Captcha;

class Captcha{
    private $width;
    private $height;   
    private $codeNum;
    private $type;
    private $fontStyle;
    private $dot;
    private $line;
    private $image;
    private $chars;
    
    function __construct($width=100,$height=40,$codeNum=4,$type=3,$session='securityCode',$fontStyle='fontStyle/MSYH.TTC',$dot=50,$line=4){
        session_start();
        $this->width=$width;
        $this->height=$height;
        $this->codeNum=$codeNum;
        $this->type=$type;
        $this->session=$session;
        $this->fontStyle=$fontStyle;
        $this->dot=$dot;
        $this->line=$line;
        $this->image=$this->createImage();    
        $this->chars=$this->createChar();
        $_SESSION [$this->session] = $this->chars;
    }
    
    public function showImage($red=232,$green=155,$blue=55){
        $color=imagecolorallocate($this->image, $red, $green, $blue);
        imagefilledrectangle($this->image, 0, 0, $this->width, $this->height, $color);
        for($i = 0; $i <$this->codeNum; $i++) {
            $size = mt_rand ( 16, 18 );
            $angle = mt_rand ( - 15, 15 );
            $x = 10 + $i * $size;
            $y = mt_rand ( 20, 26 );
            $color=imagecolorallocate($this->image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            $text = substr ( $this->chars, $i, 1 );
            imagettftext ( $this->image, $size, $angle, $x, $y, $color, $this->fontStyle, $text );
        }
        $this->interferon();
        header ( "content-type:image/gif" );
        imagegif ( $this->image );
        imagedestroy ( $this->image );
    }
    
    private function createImage(){        
        $image=imagecreatetruecolor($this->width, $this->height);                
        return $image;    
    }
    
    private function createChar(){
        if($this->type==1){
            $chars=implode('', range(0, 9));
        }else if($this->type==2){
            $chars=implode('', array_merge(range('A','Z'),range('a', 'z')));
        }elseif ($this->type==3) {
            $chars=implode('', array_merge(range(0, 9),range('A','Z'),range('a', 'z')));
        }
        $chars=str_shuffle($chars);
        if($this->codeNum>strlen($chars)){
            exit('数字过大');
        }
        $chars=substr($chars, 0,$this->codeNum);
        return $chars;
    }
    
    private function interferon(){
        for ($i=0; $i <$this->line ; $i++) { 
            $color = imagecolorallocate ($this->image, mt_rand ( 0, 255 ), mt_rand ( 0, 255 ), mt_rand ( 0, 255 ) );
            imageline($this->image,mt_rand(0, $this->width-1),mt_rand(0, $this->height-1), mt_rand(0, $this->width-1), mt_rand(0, $this->height-1), $color);
        }
        for ($i=0; $i <$this->dot ; $i++) { 
            $color = imagecolorallocate ($this->image, mt_rand ( 0, 255 ), mt_rand ( 0, 255 ), mt_rand ( 0, 255 ) );    
            imagesetpixel($this->image,mt_rand(0, $this->width-1) , mt_rand(0, $this->height-1), $color);        
        }
    }
    
}