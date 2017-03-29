<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use yii\widgets\ActiveForm;


include_once('header.php');

$this->title = $keywords .' 的相关基因';


?>

 <style type="text/css">
 .keybg{background:#ff9}
 </style>

 <div class="row">
		<div class="col-md-4">
          <div class="box box-blue box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">疾病搜索</h3></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <ul class="nav nav-stacked"> <?php
              if(count($models) < 1) {
              	echo "没有相关数据";
              }else{
                foreach ($models as $key => $info) {
					$chpo = str_replace($keywords, '<i class=keybg>' . $keywords.'</i>',$info->chpo);
					$url = Yii::$app->urlManager->createUrl(['gene/subinfo-bygene', 'gene' => $info->gene ]) ;
                	?>  
		            <li style="display:block;border:none">
		               <a href="<?=$url?>"> <?=$info->gene ?> <span class="pull-right"> <?=$info->diseaseID ?> </span></a>
		            </li>
		            <li style="padding:0px 5px 15px 15px;color:#666;"> 
		               <a href="<?=$url?>"> <?=$info->gene ?><b>HPO表型:</b> &nbsp;<?=$chpo ?></a>
		            </li>


             <?php 

                }//for
             }//if
              ?>
              </ul>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
</div>



 <script>
 function showsub(id){
	location.href= "?r=gene/subinfo&subid="+id;

}
 </script>