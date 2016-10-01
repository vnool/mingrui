<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use backend\widgets\Comments;


/* @var $this yii\web\View */
/* @var $model backend\models\MingruiDoc */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '案例分享', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mingrui-doc-view">
 
    <p>
        <?= Html::a('Update', ['update', 'id'           => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id'           => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
/*   echo  DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:raw', 
            'doc',
            ['attribute'=>'createtime','format'=>'date']
        ],
    ]) */

echo  $this->render('view-item', [
        'model' => $model,
    ]) 
    ?>


        <div class="col-md-8" style="padding-left: 0px;">

              <?=Comments::widget([
                    'action'=>'mingrui-doc/send-comment',
                    'id' => 'doc'.$model->id,
                ])?>
        </div>
</div>
