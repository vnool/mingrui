<?php
use yii\helpers\Html;

$controllerID = Yii::$app->controller->id;
$actionID     = Yii::$app->controller->action->id;

$active = ['view' => '', 'show-report' => '', 'stats' => '', 'index' => '', 'analyze' => ''];
switch ($actionID) {
    case 'view':
        $activeid = 'view';
        break;
    case 'show-report':
        $activeid = 'show-report';
        break;
    case 'stats':
        $activeid = 'stats';
        break;
    case 'index': //mingrui-attachment
        $activeid = 'index';
        break;
    case 'analyze':
        $activeid = 'analyze';
        break;
    default:
        # code...
        break;
}
$active[$activeid] = 'active';

$pingjiaUrl = Yii::$app->urlManager->createUrl(['pingjia/save-xingji'])


?>
 <style type="text/css">
   .btn.active{
      box-shadow: inset 0 3px 5px rgba(0, 0, 0, .7);
    }
</style>

<p>


<?=Html::a('意见反馈', ['rest-report/view', 'id' => $model_id], [
    'class' => 'btn btn-info ' . $active['view'],
])?>


<?=Html::a('报告详情', ['show-report', 'id' => $model_id], [
    'class' => 'btn btn-success ' . $active['show-report'],
])?>

<?=Html::a('报告归类', ['rest-report/stats', 'id' => $model_id], [
    'class' => 'btn btn-primary ' . $active['stats'],
])?>

<?=Html::a('星级评价', '#', [
    'class' => 'btn btn-info ' . $active['index'],
    'onclick'=>'abc()'
])?>

<?=Html::a('完善资料', ['mingrui-attachment/', 'reportid' => $model_id], [
    'class' => 'btn btn-warning ' . $active['index'],
])?>

&nbsp;&nbsp;&nbsp;
<?=Html::a('数据分析', ['rest-report/analyze', 'id' => $model_id], [
    'class' => 'btn btn-danger ' . $active['analyze'],
])?>

</p>





<style type="text/css">
    .pingjia  p{margin-left:40px;}
    .pingjia i{font-style:normal; }
    .pingjia .tag{display: inline-block; width:60px; font-size:1.5em}
    .pingjia .tagtxt{display: inline-block; width:50px;}
    .pingjia input{margin-right:20px; width: 18px; height: 18px;}
</style>
<div class="example-modal" >
<div class="modal modal-primary" id='xingjipingjiaBox'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">  </h4>
      </div>
      <div class="modal-body">
        <div class='pingjia' >
           <p><input type=radio name='pingjia' value="1"> <i class='tag'>★</i>疑似阳性</p>
           <p><input type=radio name='pingjia' value="2"> <i class='tag'>★★</i>阳性</p>
           <p><input type=radio name='pingjia' value="3"> <i class='tag'>★★★</i>阳性+好案例</p>
           <p><input type=radio name='pingjia' value="4" > <i class='tag'>■</i>阴性</p>
           <p><input type=radio name='pingjia' value="5"> <i class='tag'>■ ■</i>阳性+特殊案例</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal"> 取 消 </button>
        <button type="button" class="btn btn-outline" id='pingjisendBtn'> 确 定 </button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</div>
<!-- /.example-modal -->

<script type="text/javascript">
    function abc(){
       $('#xingjipingjiaBox').show();
    }
    $('#xingjipingjiaBox').click(function(){
        $(this).fadeOut();
    });
    $('#pingjisendBtn').click(function(){
        var url = "<?=$pingjiaUrl ?>";
        var val = $('#xingjipingjiaBox input[name="pingjia"]:checked').val();
        $.ajax({
             type: "POST",
             url:  url,
             data: {report_id: '<?=$model_id?>', pingjia: val},
             dataType: "json",
             success: function(d){
                  if(d.code==1){
                     $('#xingjipingjiaBox').slideUp();
                  }
             }
         });
    });

  
    
</script>
