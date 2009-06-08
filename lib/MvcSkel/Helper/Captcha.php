<?php
/**
 * MvcSkel Auth helper.
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    MvcSkel
 * @copyright  2008, Whirix Ltd.
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://code.google.com/p/mvcskel/
 *
 * NOTE: the code for getImage function is got from Yii framework:
 * CCaptchaAction class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

require_once 'Text/Password.php';

/**
 * Verification image service.
 */
class MvcSkel_Helper_Captcha {
    /**
    * Init random value for image. Keep the value in session.
    * @return string generated value
    */
    public static function init($clear = false) {
        if ($clear) {
            MvcSkel_Helper_Captcha::clear();
        }
        // generate only if not set
        if (isset($_SESSION['MVCSKEL_VERIFICATION_IMAGE'])) {
            return $_SESSION['MVCSKEL_VERIFICATION_IMAGE'];
        }

        $_SESSION['MVCSKEL_VERIFICATION_IMAGE'] = Text_Password::create(6);

        return $_SESSION['MVCSKEL_VERIFICATION_IMAGE'];
    }

    /**
    * Check the the given argument with given value.
    * @param string $value value to check
    * @return boolean true if values are matched
    */
    public static function check($value) {
        return $_SESSION['MVCSKEL_VERIFICATION_IMAGE']==$value;
    }

    /**
    * Removes value.
    */
    public static function clear() {
        unset($_SESSION['MVCSKEL_VERIFICATION_IMAGE']);
    }

    /**
    * Get image data to display (do send headers).
    * @return image data
    */
    public static function getImage() {
        $height = 60;
        $width = $height*2;//60;
        $backColor = 0xFFFFFF;//0xFFF5DF;
        $foreColor = 0x2040A0;

        $image = imagecreatetruecolor($width, $height);
        $backColor = imagecolorallocate($image,
            (int)($backColor%0x1000000/0x10000),
            (int)($backColor%0x10000/0x100),
            $backColor%0x100);
        imagefilledrectangle($image, 0, 0, $width, $height, $backColor);
        imagecolordeallocate($image, $backColor);

        $foreColor = imagecolorallocate($image,
            (int)($foreColor%0x1000000/0x10000),
            (int)($foreColor%0x10000/0x100),
            $foreColor%0x100);

        if($fontFile===null) {
            $fontFile = dirname(__FILE__).'/Duality.ttf';
        }

        $code = $_SESSION['MVCSKEL_VERIFICATION_IMAGE'];
        $offset = 2;
        $length = strlen($code);
        $box = imagettfbbox(30, 0, $fontFile, $code);
        $w = $box[4] - $box[0] - $offset * ($length - 1);
        $h = $box[1] - $box[5];
        $scale = min(($width - $padding*2)/$w,($height - $padding*2)/$h);
        $x=10;
        $y=round($height*27/40);
        for($i=0; $i<$length; ++$i)
        {
            $fontSize=(int)(rand(26,32)*$scale*0.8);
            $angle=rand(-10,10);
            $letter=$code[$i];
            $box=imagettftext($image,$fontSize,$angle,$x,$y,$foreColor,$fontFile,$letter);
            $x=$box[2]-$offset;
        }

        imagecolordeallocate($image,$foreColor);

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header("Content-type: image/png");
        imagepng($image);
        imagedestroy($image);
        return;
    }
}
?>
