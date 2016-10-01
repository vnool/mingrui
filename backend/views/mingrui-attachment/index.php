<?php

use backend\models\RestReport;
use yii\grid\GridView;
use yii\helpers\Html;
use backend\widgets\Imglist;
use backend\widgets\RestrepotTop;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MingruiAttachmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$id = $_GET['reportid'];
global $sick;
$report = RestReport::findOne($id);
$sick   = $report->sample->name;

$this->title                   = '完善资料';
$this->params['breadcrumbs'][] = ['label' => '完善资料', 'url' => ['rest-report/index']];
$this->params['breadcrumbs'][] = ['label' => "[{$sick}]报告", 'url' => ['rest-report/view', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mingrui-attachment-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?=RestrepotTop::widget(['report_id'=>$id]); ?>

    <p>
        <?=Html::a('新增 资料', ['create', 'reportid' => $_GET['reportid']], ['class' => 'btn btn-success'])?>
    </p>
   <?= Imglist::widget([
        'dataProvider' => $dataProvider, 
    ]); ?>
</div>
