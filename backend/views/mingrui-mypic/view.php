<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MingruiMypic */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Mingrui Mypics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mingrui-mypic-view">

     

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'images:ntext',
            'createtime',
        ],
    ]) ?>

</div>
