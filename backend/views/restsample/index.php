<?php

use backend\widgets\DateInput;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RestSampleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = '我的病人';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    /* .content tr td:nth-child(2){
       -webkit-filter: blur(6px);-filter: blur(6px);
    }*/
</style>
<div class="rest-sample-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);
?>

    <p>
        <?php //=Html::a('新建患者资料', ['create'], ['class' => 'btn btn-success'])
?>
    </p>
    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'rowOptions'   => function ($model) {
        $url = Yii::$app->urlManager->createUrl(['restsample/view', 'id' => $model->sample_id]);
        return ['onclick' => "location.href='$url';", 'style' => 'cursor:pointer'];
    },
    'emptyCell'    => '',
    'columns'      => [
        [
            'class'   => 'yii\grid\SerialColumn',
            'options' => ['width' => 50]],

        //'sample_id',

        [
            'attribute' => 'name',
            'options'   => ['width' => 100],
        ],
        //'type',
        //'ypkd_id',
        //'barcode',
        [
            'attribute' => 'sex',
            'filter'    => ['male' => '男', 'female' => '女'],
            'options'   => ['width' => 60],
            'value'     => function ($model) {
                return $model->sex == 'female' ? '女' : '男';
            },
        ],
        [
            'attribute' => 'birthday',
            'options'   => ['width' => 120],
            'filter'    => DateInput::widget(['attribute' => 'birthday', 'model' => $searchModel]),
        ],

        // 'age',
        [
            'attribute' => 'tel1',
            'label'     => '联系方式',
            'options'   => ['width' => 120],
            'value'     => function ($model) {
                $tels = $model->tel1;
                //$list = explode('、', $tels);
                //return str_replace(' ', '', $list[0]) . (count($list) > 1 ? '-等' : '');
                $tels = str_replace(' ', '', $tels);
                $tels = str_replace('-', '', $tels);
                /* if (strlen($tels) > 11) {
                $tels = substr($tels, 0, 11) . '...';
                }*/
                return $tels;
            },
            'filter'    => Html::activeTextInput($searchModel, 'tel1', [
                'class' => 'form-control',
            ]),
        ],
        // 'tel2',
        // 'email:email',
        ['attribute' => 'address', 'options' => ['width' => 120]],

        // 'symptom:ntext',
        // 'date',
        // 'has_project',
        // 'report_type',
        // 'guanlian',
        // 'pdf',
        // 'has_symptom',
        // 'relation',
        // 'related_sid',
        // 'xianzhengzhe',
        // 'yangbenruku',
        // 'heshuanruku',
        // 'heshuanruku2',
        // 'yangbenweizi',
        // 'heshuanweizi',
        // 'heshuanweizi2',
        // 'note:ntext',
        // 'doctor_id',
        // 'family_id',
        // 'sales_id',
        // 'shenhe_status',
        // 'clinic_no',
        // 'nationality',
        // 'patient_no',
        // 'clinic_symptom:ntext',
        // 'report_template',
        // 'created',
        // 'xiedai',
        // 'updated',
        // 'timestamp:ntext',
        // 'dengji_note:ntext',
        // 'express',
        // 'express_no',
        // 'shouyang_date',
        // 'shouyanged',

        // ['class'        => 'yii\grid\ActionColumn',
        //     'header'        => '操作',
        //     'template' => '{view}  ',
        //     'filterOptions' => ['data-toggle' => 'gridviewoprator'],
        //     'options'       => [
        //         'width' => 80,
        //     ],
        // ],
        [
            'options' => ['width' => '120'],
            'label'   => '操作',
            'filter'=> Html::submitButton('搜 &nbsp; 索', ['class' => 'btn btn-info']) 
            .Html::resetButton('恢 &nbsp;  复', ['class' => 'btn btn-default rest']) ,            
            'format'  => 'raw',
            'value'   => function ($sample) {
                 

                return Html::a('查看',['restsample/view','id'=>$sample->sample_id], ['class'=>'btn btn-info']);
            }
        ],//item
    ],
]);?>
</div>
<style type="text/css">
    .content-wrapper{overflow: auto}
    .disabled{background: #999;border:0px;}
</style>