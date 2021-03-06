<?php
namespace backend\controllers;

use common\components\SMS;
use yii\web\Controller;

/**
 * Site controller
 */
class UtilsController extends Controller
{
    public $layout               = false;
    public $enableCsrfValidation = false;

    public function init()
    {

    }
    
    public function actionMsg($msg){ 
    	return $msg;
    }
    public function actionSendsms($code,$mobile)
    {
        if ($_SESSION['verify_code'] != $code) {

            return json_encode(['code' => 1001]);
        }
        if(!empty($_SESSION['check_sms_time']) && $_SESSION['check_sms_time'] > time()-60){
            return json_encode(['code' => 1002]);
        }
 
        $_SESSION['check_sms']      = rand(1000, 9999);
        $_SESSION['check_sms_time'] = time();

        SMS::sendSMS($mobile, [$_SESSION['check_sms'], '20分钟']);
        return json_encode(['code' => 1]);

    }

    public function actionVerifycheck($code)
    {
        if ($_SESSION['verify_code'] == $code) {

            return json_encode(['code' => 1]);
        }
    }
    public function actionVerifyimg()
    {
        /*
         *  @Author fy
         */
        $imgwidth  = 100; //图片宽度
        $imgheight = 40; //图片高度
        $codelen   = 4; //验证码长度
        $fontsize  = 20; //字体大小
        $charset   = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';

        $font = 'Fonts/segoesc.ttf';
        $font = './FreeSans.ttf';
        //putenv('GDFONTPATH=' . realpath('.'));
        // Name the font to be used (note the lack of the .ttf extension)
        //$font = 'fontello';

        $im    = imagecreatetruecolor($imgwidth, $imgheight);
        $while = imageColorAllocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $while); //填充图像
        //取得字符串
        $authstr = '';
        $_len    = strlen($charset) - 1;
        for ($i = 0; $i < $codelen; $i++) {
            $authstr .= $charset[mt_rand(0, $_len)];
        }

        $_SESSION['verify_code'] = strtolower($authstr); //全部转为小写，主要是为了不区分大小写

//随机画点,已经改为划星星了
        for ($i = 0; $i < $imgwidth; $i++) {
            $randcolor = imageColorallocate($im, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($im, mt_rand(1, 5), mt_rand(0, $imgwidth), mt_rand(0, $imgheight), '*', $randcolor);
            //imagesetpixel($im,mt_rand(0,$imgwidth),mt_rand(0,$imgheight),$randcolor);
        }

        imagesetthickness($im, 4);
//随机画线,线条数量=字符数量（随便）
        for ($i = 0; $i < $codelen; $i++) {
            $randcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imageline($im, 0, mt_rand(0, $imgheight), $imgwidth, mt_rand(0, $imgheight), $randcolor);
        }
        $_x = intval($imgwidth / $codelen); //计算字符距离
        $_y = intval($imgheight * 0.7); //字符显示在图片70%的位置
        for ($i = 0; $i < strlen($authstr); $i++) {
            $randcolor = imagecolorallocate($im, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));
            //imagestring($im,5,$j,5,$imgstr[$i],$color3);
            // imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
            imagettftext($im, $fontsize, mt_rand(-30, 30), $i * $_x + 3, $_y, $randcolor, $font, $authstr[$i]);
        }
//生成图像
        header("content-type: image/PNG");
        imagePNG($im);
        imageDestroy($im);
        exit;
    }

    public function actionSavefile(){ 
    	//$_FILE['upload']
    	$callback =$_GET["CKEditorFuncNum"]; 
    	$fileName ='upload/editor/' . md5(time().rand()) .$_FILES['upload']['name'];


    	if (move_uploaded_file($_FILES['upload']['tmp_name'], $fileName  )) {
		     return  "<script type=\"text/javascript\">"
                ." window.parent.CKEDITOR.tools.callFunction( $callback ,'{$fileName}','');"
                ."  </script> ";
		} else { 

			return "upload failed!";
		}




    	

    }
}
