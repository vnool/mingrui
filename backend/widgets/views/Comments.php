<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\MingruiComments;
use backend\widgets\WechatRecord;
 use backend\widgets\VoiceShow;

 
?><div class="box box-primary direct-chat direct-chat-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            意见与点评
        </h3>
        <div class="box-tools pull-right">
            <span class="badge bg-light-blue" data-toggle="tooltip" title="3 New Messages">
                
            </span>
            <button class="btn btn-box-tool" data-widget="collapse" type="button">
                <i class="fa fa-minus">
                </i>
            </button>
            <button class="btn btn-box-tool" data-toggle="tooltip" data-widget="chat-pane-toggle" title="Contacts" type="button">
                <i class="fa fa-comments">
                </i>
            </button>
            <button class="btn btn-box-tool" data-widget="remove" type="button">
                <i class="fa fa-times">
                </i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- Conversations are loaded here -->
        <div class="direct-chat-messages" style='height:330px'>
            <?php
foreach ($model->comments as $comment) {
    if ($comment) {
        echo $this->render('CommentsLine', ['model' => $comment]);
    }

}

?>

        </div>
        <!--/.direct-chat-messages-->
        <!-- Contacts are loaded here -->
        <div class="direct-chat-contacts">
            <ul class="contacts-list">
                <li>
                    <a href="#">
                        <img alt="User Image" class="contacts-list-img" src="../dist/img/user1-128x128.jpg">
                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                    Count Dracula
                                    <small class="contacts-list-date pull-right">
                                        2/28/2015
                                    </small>
                                </span>
                                <span class="contacts-list-msg">
                                    How have you been? I was...
                                </span>
                            </div>
                            <!-- /.contacts-list-info -->
                        </img>
                    </a>
                </li>
                <!-- End Contact Item -->
            </ul>
            <!-- /.contatcts-list -->
        </div>
        <!-- /.direct-chat-pane -->
    </div>
    <!-- /.box-body -->

    <div class="box-footer">    
       <?php 
       $form = ActiveForm::begin(['action' => $model->formaction ,'method'=>'post', 'id'=>'noteform']); 

       ?>
           
           <input type="hidden" name="MingruiComments[report_id]" value="<?=$model->id?>">

            <div class="input-group">
                    <input class="form-control" id='MingruiComments-content'  name="MingruiComments[content]" 
                        placeholder="输入留言内容" type="text">
                    <span class="input-group-btn voiceActionBtn">
                        <button type=button class="btn btn-info btn-flat"  >
                         <i class='fa fa-microphone'> </i> 语音
                        </button>
                    </span>
                    <span class="input-group-btn">
                        <button type=button  class="btn btn-primary btn-flat" id="submitbtn">
                            留言
                        </button>
                    </span>
                 
            </div>
            <?=
            WechatRecord::widget([]);
            ?>

        <?php ActiveForm::end(); ?>
    </div>
    <!-- /.box-footer-->
</div>

<script type="text/javascript">
    
    $(function(){
        if(!isWeixin()){
             $('.voiceActionBtn').hide();
        }        
    });

     $('.voiceActionBtn').click(function(){
        $('body').trigger("voice_init",{"multi":false});  //弹出语音 
     });
    
    var nowdataType = 'text';
    $('body').bind("voiceUpdate",function(e,voices){
        nowdataType= 'voice';
       $('#MingruiComments-content').val(JSON.stringify(voices) );
       $('#MingruiComments-content').hide();
       $('.voiceActionBtn').hide();
    });
   
    $("#submitbtn").click(function(e){ 
      if(nowdataType=='voice'){
         voiceUpload(function(voices){        
            $('#MingruiComments-content').val(JSON.stringify(voices) );
            $('#noteform').submit();
         });
      }else{
         $('#noteform').submit();
      }
      
    });
     
</script>
<?=VoiceShow::begin();?>