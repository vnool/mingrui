<?php
use yii\helpers\Html;
use backend\models\userMessage;
use backend\widgets\GuestbookDrop;
use backend\components\Functions;
use backend\models\MingruiOrder;


/* @var $this \yii\web\View */
 
$hideMenuToggle = Functions::ismobile() ? 'hide' : '';
$showMenuToggleBtn = $hideMenuToggle=='hide' ? '' : 'hide';

 $message = userMessage::myMessages();
$reportMessage = userMessage::reportMessage();

$orderCount = MingruiOrder::find()->where(['status'=>'init'])->count();
$orderUrl = Yii::$app->urlManager->createUrl(['/orders/']);
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><img src=logo.png style="width:36px"></span>
                <span class="logo-lg"  ><img src=logo.png style="width:64px">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo', 'style'=>'padding-left:0px']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle <?=$hideMenuToggle ?>" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <a class="btn <?=$showMenuToggleBtn ?>" 
        style='position: absolute;line-height: 50px;line-height: 35px;height: 100%;color:#fff' data-toggle="offcanvas" role="button">
        <i class='fa fa-list-ul'></i> 菜单
        </a>
        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <?php 
                if(Yii::$app->user->can('admin')){ 
                ?>
                 <li class="dropdown messages-menu">
                    <a href="<?=$orderUrl  ?>"    title="订单">
                        <i class="fa fa-dollar"></i>&nbsp; 
                        <span class="label label-danger"><?=$orderCount ?></span>
                    </a> 
                </li>
                  <? }
                  ?>
               <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  title="报告留言">
                        <i class="fa fa-files-o"></i>
                        <span class="label label-warning"><?=count($reportMessage ) ?></span>
                    </a>
                      <ul class="dropdown-menu">
                        <?=GuestbookDrop::widget(['message'=>$reportMessage ]);?>
                      </ul>
                </li>


                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"   title="互动留言">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"><?=count($message) ?></span>
                    </a>
                      <ul class="dropdown-menu">
                        <?=GuestbookDrop::widget(['message'=>$message]);?>
                      </ul>
                </li>
                  

                <li class="dropdown user user-menu"> 
                  <?= Html::a(  '退出',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat' ,
                                    'style'=>'display:inline-block;border:0px;background:none']
                                ) ?>

                    <a href="#" class="dropdown-toggle hidden" data-toggle="dropdown">
                        <img src="<?= Yii::$app->user->identity->avatar ?>" class="user-image"  onerror="this.src='images/user2.png';"  alt="User Imagexx"/>
                       
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= Yii::$app->user->identity->avatar ?>" 
                               onerror="this.src='images/user2.png';"  class="img-circle"
                                 alt="User Image"/>

                            <p>
                                 <?= Yii::$app->user->identity->nickname; ?>
                                <small style="display: none">（女，6岁）</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#"></a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">积分10</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#"></a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">个人资料</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    '注销',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li style="display: none">
                    <a href="#" data-toggle="control-sidebar" >
                    <i class="fa fa-gears"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
