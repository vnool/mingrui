<?php
namespace common\models;

use backend\models\RestClient;
use backend\models\RestSample;
use backend\widgets\Nodata;
use common\models\User;
use Yii;
use yii\base\Model;

class WechatUser extends Model
{
    public $mobile;
    public $smscode;
    public $openid;

    public function rules()
    {
        return [
            [['mobile', 'smscode', 'openid'], 'safe'],
        ];
    }
    public function bindMobile()
    {

        //2. 保存手机号
        if (!$this->openid) {
            $this->openid = $_SESSION['openid'];
        }
        if (!$this->openid) {
            return;
        }
        $user = User::find()->where(['wx_openid' => $this->openid])->one();
        if ($user) {
            //$mobile = $this->mobile ;
            $mobile = $this->switchTestMobile($this->mobile);
            if(!$mobile){  
            	echo Nodata::widget(['title' => '错误!', 
                'message' => ' 电话号码不正确'
                ]);
            	return false;
            }
            $user   = $this->bindMingruiUser($user, $mobile);
            if ($user) {
                $user->username = $mobile;
                $user->status   = 10;

                $user->updated_at = time();
                if (!$user->save()) {
                    var_export($user->errors);
                    exit;
                }
                return $user;

            };

        } else {
            exit('没有该用户openid=' . $this->openid);
        }

    }
    /**
     * 绑定明睿的用户id
     * @param  [type] $model  yii系统的user
     * @param  [type] $mobile [description]
     * @return [type]         [description]
     */
    public function bindMingruiUser($model, $mobile)
    {
        //设置医生或用户的id

        //$erp_user = RestClient::find()->where(['tel' => $mobile])->one();
        $erp_user = RestClient::find()->where(['like', "REPLACE(tel,' ','')", $mobile])->one();
        if ($erp_user) {
            $role_text = 'doctor';
            $userid    = $erp_user->id;
        } else {
            //SELECT * FROM `rest_sample` where REPLACE(tel1,' ','') like '%15942175885%' ;
            // $erp_user = RestSample::find()->where(['like', 'tel1', $mobile])->one();
            $erp_user = RestSample::find()->where(['like', "REPLACE(tel1,' ','')", $mobile])->one();
            if ($erp_user) {
                $role_text = 'guest';
                $userid    = $erp_user->sample_id;
            }

        }

        if ($erp_user) {
        	//var_dump($_SESSION);

            if ($_SESSION['wechat_entery'] != 'all' && $_SESSION['wechat_entery'] != $role_text) {
                $realRole  = $role_text;
                $rolePlace = $_SESSION['wechat_entery'];
                echo Nodata::widget([
                    'title'   => '错误!',
                    'message' => '您没有权限使用该公众号。您可能是进错了公众号<br>'
                    . '您是:' . $realRole . ', 而这里是' . $rolePlace . '的入口',
                ]);
                return false;
            } else {

                $model->role_text   = $role_text;
                $model->role_tab_id = $userid;
                $model->save();
                $_SESSION['mobile'] = $mobile;

                $this->initAfter1stLogin($model, $role_text);
                return $model;
            }

        } else {

            echo Nodata::widget([
                'title'   => '该号码' . $mobile . '未被记录!',
                'message' => '请联系客服/销售，将你的手机号录入系统中。客服电话：010-53396195']);
            return;

            return false;
            exit(10);
        }
    }
    /**
     * 首次登陆后，设置一些基本数据
     * @param  [type] $model     [description]
     * @param  [type] $role_text [description]
     * @return [type]            [description]
     */
    public function initAfter1stLogin($model, $role_text)
    {
        //为用户指定角色
        $auth       = Yii::$app->authManager;
        $role       = (object) null;
        $role->name = $role_text;
        $auth->assign($role, $model->id);

    }
    public function switchTestMobile($mobile)
    {
        if (!empty(Yii::$app->params['mobile_aliases'])) {
            if (array_key_exists($mobile, Yii::$app->params['mobile_aliases'])) {
                $mobile = Yii::$app->params['mobile_aliases'][$mobile];
            }
        }
        return $mobile;
    }
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }
    public function getMobile()
    {
        return $this->mobile;
    }

    public static function wechatUserInfo()
    {
        $oauth  = Yii::$app->wechat->getOauth2AccessToken($_GET['code']);
        $openid = $oauth['openid'];

        $user = User::find()->where(['wx_openid' => $openid])->one();
        if (!$user) {

            //获得微信个人资料
            $wechatinfo = Yii::$app->wechat->getSnsMemberInfo($openid, $oauth['access_token']);
            //保存资料
            $user = self::newUser4wechat($wechatinfo);
            if ($user) {
                $_SESSION['openid'] = $openid;
                return $user;
            } else {
                return 'get wechat info fail';
            }
        } else {
            $_SESSION['openid'] = $oauth['openid'];

            return $user;
        }

    }

    public static function localUser($openid)
    {
        return User::find()->where(['wx_openid' => $openid])->one();

    }

    /**
     * 获取身份，并跳转到对应的url
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    public static function show($url, $entery = 'xxx')
    {
        $_SESSION['wechat_entery'] = $entery;
        $_SESSION['entery_url']    = self::createUrl($url);
        //var_dump($_SESSION); exit( "====");
        $user                      = self::oauth();

        if ($user) {

            self::login($user);

        }
    }

    /**
     * 给用户登录权限
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public static function login($user)
    {
        if ($user->role_text != $_SESSION['wechat_entery']) {
            //串号了
            echo Nodata::widget(['title' => '错误!', 
                'message' => '您没有权限使用该公众号[' . $user->role_text .'/'.  $_SESSION['wechat_entery'].']'
                ]);
            return;
        } 

        Yii::$app->user->login($user, 0);
        if (Yii::$app->user->isGuest) {
            exit('Login finally failed!!');
        }

        header('Location: ' . $_SESSION['entery_url']);
        exit();
    }

    public static function checkWechatEntery($entery)
    {

    }
    /**
     * 检查用户身份
     * @return [type] [description]
     */
    public static function oauth()
    {

        if (!empty($_SESSION['openid'])) {
            $user = self::localUser($_SESSION['openid']);
            if ($user && $user->status != 0) {
                return $user;
            }
        }

        //去微信认证
        $redirectUrl = self::createUrl(['wechat-oauth/login']);
        //$$redirectUrl = str_replace('%2F', '/', $redirectUrl);
        $toUrl = Yii::$app->wechat->getOauth2AuthorizeUrl($redirectUrl, 'LOGIN', 'snsapi_userinfo');
        //exit($toUrl);
        header("Location: $toUrl");
        exit;

    }

    public static function checkUserExist()
    {

    }

    public static function newUser4wechat($info)
    {
        $user           = new User();
        $user->username = 'unset-' . $info['openid'];
        $user->setPassword(rand());
        $user->generateAuthKey();
        $user->access_token = hash('sha256', $info['openid']);
        $user->status       = 0;

        $user->email     = 'x';
        $user->wx_openid = $info['openid'];
        $user->avatar    = $info['headimgurl'];
        $user->nickname  = $info['nickname'];

        $user->created_at = $user->updated_at = time();
        if (!$user->save()) {
            var_export($user->errors);
        }
        return $user;
    }

    public static function createUrl($url)
    {
        if (!empty($_GET['role'])) {
            $url['role'] = $_GET['role'];
        }

        return Yii::$app->urlManager->createAbsoluteUrl($url);
    }

    public static function switchWechat($switch = false)
    {
        //
        if (!empty($_GET['role']) && $_GET['role'] == 'doctor') {
            $config = Yii::$app->params['wechat_doctor']['config'];
            //var_dump(Yii::$app->wechat);

            Yii::$app->set('wechat', Yii::createObject([
                'class'     => 'callmez\wechat\sdk\Wechat',
                'appId'     => $config['appId'],
                'appSecret' => $config['appSecret'],
                'token'     => $config['token'],
            ]));
            //var_dump(Yii::$app->wechat);
        }

    }

    public static function getWechat($isdoctor = false)
    {
        if ($isdoctor) {
            //echo "use doctor";
            $config = Yii::$app->params['wechat_doctor']['config'];
            return Yii::createObject([
                'class'     => 'callmez\wechat\sdk\Wechat',
                'appId'     => $config['appId'],
                'appSecret' => $config['appSecret'],
                'token'     => $config['token'],
            ]);
        } else {
            return Yii::$app->wechat;
        }
    }

    public function wechatQrcodeUrl()
    {
        $param = [
            'action_name' => 'QR_SCENE',
            'action_info' => [
                'scene' => [
                    // 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
                    'scene_id'  => rand(),
                    // 场景值ID（字符串形式的ID），字符串类型，长度限制为1到64，仅永久二维码支持此字段
                    'scene_str' => '',
                ],
            ],
        ];
        try {
            define('CURL_SSLVERSION_TLSv1', 1);
            $ticket = Yii::$app->wechat->createQrCode($param);
            if ($ticket) {
                return Yii::$app->wechat->getQrCodeUrl($ticket['ticket']);
            }
        } catch (Exception $e) {}

    }
}
