 <?php

 use yii\helpers\Html;
use backend\widgets\WeixinMenubar;
use backend\components\Functions;


use common\components\Statistics;
Statistics::doCount();

//var_dump($menu); 
?>


<style type="text/css">
 
 @media(min-width:640px) {
	 .main-header {position:fixed;width:100%}  
     
     .content-wrapper{margin-top:50px;}
}
    .main-sidebar {position: fixed} 

 
</style>


<!-- jQuery 2.2.3 -->
<!-- <script src="plugins/jQuery/jquery-2.2.3.min.js"></script> -->
<!-- jQuery UI 1.11.4 -->
<script src="js/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<!-- <script src="bootstrap/js/bootstrap.min.js"></script> -->
<!-- ChartJS -->
<script src="plugins/chartjs/Chart.js"></script>
<!-- Morris.js charts -->
<script src="plugins/morris/raphael-min.js"></script>
<script src="plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<!--script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script-->
<!--script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script-->
<!-- jQuery Knob Chart -->
<script src="plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="plugins/daterangepicker/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<!--script src="<?=$directoryAsset?>/js/app.min.js"></script-->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>


<?=WeixinMenubar::widget();?>

<script type="text/javascript">
    
    $(function(){
    	<?php
    	  $html =  Html::submitButton('搜 &nbsp;索', ['class' => 'btn btn-info']) 
            .Html::resetButton('清 &nbsp;空', ['class' => 'btn btn-default rest']) ;
    	?>
    	$("[data-toggle='gridviewoprator']").html('<?=$html?>');

    	$('.rest').click(function(){
	        $('.form-control').val('') ;
	        $('.table .form-control').eq(0).change();
	    });

	    if($('.pagination').length > 0){
	    	 
	    	var totalPage = $('.pagination li').length -2;
	    	var url;
	    	var $html;
	    	url = location.href+'&page=1';
	    	$html = '<li class="prev"><a href="'+url+'" data-page="0">|&lt;</a></li>';
	    	$('.pagination').prepend($html);


	    	url = location.href+'&page='+totalPage;
	    	$html = '<li class="prev"><a href="'+url+'" data-page="0">&gt;|</a></li>';
	    	$('.pagination').append($html);

	    	$html = '<li  ><input  class="pagego" style="padding: 5px;width:50px" placeholder="页码" title="输入页码" ></li>';
	    	$('.pagination').append($html);

	    	$('.pagego').keydown(function(e){ 
	    		var num = parseInt($(this).val() );
		    	if(e.keyCode==13 && num > 0){
				   url = location.href;
				   url += '&page='+num;
				   location.href  = url;
				}
		    });
	    }

		

   });
    
</script>


