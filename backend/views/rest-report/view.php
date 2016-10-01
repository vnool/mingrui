<?php

use backend\widgets\Comments;
use backend\widgets\Summary;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model backend\models\RestReport */

$this->title = '报告:' . $model->sample->name;

$this->params['breadcrumbs'][] = ['label' => '报告列表', 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rest-report-view">



    <p>


<?=Html::a('意见反馈', ['rest-report/view', 'id' => $model->id], [
    'class' => 'btn btn-info actived',
])?>


<?=Html::a('报告详情', ['show-report', 'id' => $model->id], [
    'class' => 'btn btn-success',
])?>

<?=Html::a('报告归类', ['rest-report/stats', 'id' => $model->id], [
    'class' => 'btn btn-primary',
])?>



<?=Html::a('完善资料', ['mingrui-attachment/', 'reportid' => $model->id], [
    'class' => 'btn btn-warning',
])?>

&nbsp;&nbsp;&nbsp;
<?=Html::a('数据分析', ['rest-report/analyze', 'id' => $model->id], [
    'class' => 'btn btn-danger',
])?>
    </p>




<div class="row">
        <div class="col-md-4">
          <?php
echo Summary::widget(['model' => $model, 'diseases' => $diseases]);
?>
        </div>
        <!-- /.col -->



        <div class="col-md-8">

              <?=Comments::widget([
                     'action'=>'rest-report/send-comment',
                    'id' => $model->id,
                ])?>
        </div>





</div>
  <!-- /.row -->







    <?php

//     DetailView::widget([
//     'model'      => $model,
//     'attributes' => [
//         //'id',
//         'report_id',
//         'created',
//         //'updated',
//         'status',
//         'note:ntext',
//         // 'assigner_id',
//         //'product_id',
//         [
//             'attribute' => 'product.name',
//             'label'     => '检查项目',
//             'value'     => $model->product->name,
//         ],

//         //'complete',
//         //'cnvsqlite',
//         // 'snpsqlite',
//         //'cnvsave:ntext',
//         /*        [
//         'attribute' => 'cnvsave',
//         'label'     => 'cnvsave',
//         'format'    => 'raw',
//         'value'     => $model->cnsaveimg,
//         ],*/
//         /*      array(
//         'label' => 'xx',
//         'format' => 'raw',
//         'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
//         'value'     => function ($model) {
//         return '/';
//         },
//         ),*/
//         // 'snpsave:ntext',
//         // 'finish',
//         //  'xiafa',
//         //  'analysis_id',
//         // 'yidai_complete',
//         //'url:url',
//         //'yidai_note:ntext',
//         // 'express',
//         // 'express_no',
//         //'sample_id',
//         //   'pdf',

//         ['label'    => '结论',
//             'attribute' => 'conclusion',
//             'format'    => 'raw',
//             'value'     => $model->conclusiontag,

//         ],
//         //'explain:ntext',
//         [
//             'label' => '注释',
//             'value' => $model->explainsummary,
//         ],
//         // 'jxyanzhen',
//         //  'mut_type',
//         //'star',
//         // 'template',
//         // 'type',
//         // 'gene_template',
//         // 'ptype',
//         // 'csupload',
//         // 'family_id',
//         // 'date',
//         //'abiresult:ntext',
//         /*        ['label'    => '诊断',
//         'attribute' => 'abiresult',
//         'format'    => 'raw',
//         'value'     => function($model){
//         $json = json_decode($model->abiresult);
//         if($json){
//         return "TODO";// $json->dignosis;
//         }
//         } ,

//         ],*/

//         //'snpexplain:ntext',
//         //'abiexported',
//         //  'final_note:ntext',
//         //  'assigner_note:ntext',
//         //  'shenhe_date',
//         //   'locked',
//         //  'express_sent',
//         //  'sale_marked',
//         // 'time_stamp:ntext',
//         // 'yidaifinished_date',
//         //  'kyupload',
//         //   'yidai_marked',
//     ],
// ]);

?>

</div>

