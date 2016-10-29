<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model backend\models\MingruiVcf */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mingrui-vcf-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','class'=>'upload']]);?>

 


     <?php
		echo $form->field($model, 'vcf[]')->widget(FileInput::classname(), [
		    'options'       => ['multiple' => true, 'accept' => '*/*'],
		    'pluginOptions' => [
		        'showUpload' => true,
                'showPreview' => false,
                'previewFileType'=>'none'
		    ],
		])->label('选择vcf文件');
	?>
	
	
    <?php // $form->field($model, 'status')->textInput(['maxlength' => true]) 
    ?>

    <?= $form->field($model, 'sick')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'age')->textInput() ?>
 
     <?= $form->field($model, 'sex')->dropDownList(    
         ['male'=>'男','female'=>'女'], ['style' => 'width:240px;']
         );
     ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'diagnose')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'gene')->textInput(['maxlength' => true]) ?>
 

    <?php  //$form->field($model, 'task_id')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord ? '上传' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveForm::end();?>

</div>
