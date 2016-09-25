
<script type="text/javascript" src='http://res.wx.qq.com/open/js/jweixin-1.1.0.js'></script>
<style type="text/css">
    .record_box{
        z-index: 999; position: fixed; top:50%;left: 50%;
        transform: translate(-50%,-50%); -webkit-transform: translate(-50%,-50%);
        background: #fff;
        height: 200px;width: 200px;box-shadow: 1px 1px 11px;
        display: none;
    }
    .record_box .innerbox{    height: 90px;    width: 90px;
    margin: 20px auto;    position: relative;
   }
    .record_box input{visibility: hidden;}
    .record_box .recStatus{font-size: 3em;position: absolute;
      top: 50%;    left: 50%; cursor:pointer;
       transform: translate(-50%,-50%);
      -webkit-transform: translate(-50%,-50%);
    }
    .record_box .info{
        display: block;
    text-align: center;
    font-size: 1.5em;
    }
    .record_box .startbtn i{
      /*  border-right:1px solid rgba(255,255,255,0.5); */
    }

    .voiceline>:last-child{
    position: absolute;
    right: 0;
    bottom: 0;
    width: 32px;
    line-height: 34px;
    font-size: 1.6em;
    text-align: center; 
    border-left: 1px solid rgba(200,200,200,0.5);
    }

</style>
<div class='record_box '>
  
    <div class="innerbox">
       <input type="text" id='recordBar' class="knob" 
       data-readonly="true"        readonly='readonly' value="30"
        data-thickness="0.1" data-width="90" data-height="90" data-fgColor="#00a65a" data-bgColor='#bbb' >



       <i class='fa fa-microphone recStatus' status='stoped'></i>
       <span class="info">00'00"</span>  

       <a class='btn btn-info startbtn' id='rec'><i class='fa fa-circle'></i><span> 点击开始</span></a>

     </div>
 </div>

 <div id='record_list'>
         
           
 
       
   

 </div>
 <textarea id='tpl_voice' style="display: none">
     <div  >
        <div class="direct-chat-text bg-aqua "  style="margin-left:0px" >
            <div class='btn-social voiceline' style="height: 30px;line-height: 30px;" >
               <i class='fa  fa-play-circle-o' style="cursor:pointer"></i> 
               <span class='voicetext' onclick="playme(this);">今天肚子有点痛</span>
               <i class='fa fa-remove' style="border-left:1px solid rgba(200,200,200,0.5);"></i>
             </div>
        </div>   
     </div>

 </textarea>

 <script type="text/javascript">

     
     wx.config(<?=$config ?>);

     /*
   wx.config({
      debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
      appId: ' ', // 必填，公众号的唯一标识
      timestamp:  , // 必填，生成签名的时间戳
      nonceStr: ' ', // 必填，生成签名的随机串
      signature: ' ',// 必填，签名，见附录1
      jsApiList: ['startRecord','stopRecord','onVoiceRecordEnd','playVoice','pauseVoice','stopVoice','onVoicePlayEnd',
      'uploadVoice','downloadVoice'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });*/

 

$('.startbtn').click(function(){
    ActionSwitch();
});
var status = 'stoped';
function ActionSwitch(){ 
  if(status == 'stoped'){ //开始录制
    $('.startbtn i').removeClass('fa-circle').addClass('fa-stop');
    $('.startbtn span').html('点击停止');
    status = 'recording';
    wx.startRecord();
    startClock();
  }else{ //停止
    $('.startbtn i').addClass('fa-circle').removeClass('fa-stop');
    $('.startbtn span').html('点击开始');
    status = 'stoped';
    stopClock();
    wx.stopRecord({
      success: function (res) {
          var localId = res.localId;
          recDone(localId);
      }
    });

  }
  
}
 
function recDone(localId){
   $('.record_box').slideUp();
   wx.playVoice({
        localId:  localId // 需要播放的音频的本地ID，由stopRecord接口获得
   });
   var html = $('#tpl_voice').val();
   var x = $(html);
   var resID = Math.random();
   x.find('.voiceline').attr('localId',localId);
   x.find('.voicetext').attr('redId',resID);
   x.find('.voicetext').html('语音识别中..');

   $('#record_list').append(x);
    wx.translateVoice({
     localId: localId, // 需要识别的音频的本地Id，由录音相关接口获得
      isShowProgressTips: 1, // 默认为1，显示进度提示
      success: function (res) {
         //alert(res.translateResult);
         $('#record_list').find("[redId='"+resID+"']").html(res.translateResult); // 语音识别的结果
         $('body').trigger("voice", 
          {"localId":localId,"text":res.translateResult,"time":timeTxt()}
          );
      },
      fail:function(e){
        alert(JSON.stringify(e));
      }
  });

}
  
function playme(obj){
   var localId = $(obj).parent().attr('localId');
   wx.playVoice({
        localId:  localId // 需要播放的音频的本地ID，由stopRecord接口获得
   });
}

 
 

 
wx.onVoiceRecordEnd({
    // 录音时间超过一分钟没有停止的时候会执行 complete 回调
    complete: function (res) {
        var localId = res.localId; 
        recDone(localId);
    }
});
 

/*wx.playVoice({
    localId: '' // 需要播放的音频的本地ID，由stopRecord接口获得
});

wx.stopVoice({
    localId: '' // 需要停止的音频的本地ID，由stopRecord接口获得
});

wx.onVoicePlayEnd({
    success: function (res) {
        var localId = res.localId; // 返回音频的本地ID
    }
});

wx.uploadVoice({
    localId: '', // 需要上传的音频的本地ID，由stopRecord接口获得
    isShowProgressTips: 1, // 默认为1，显示进度提示
        success: function (res) {
        var serverId = res.serverId; // 返回音频的服务器端ID
    }
});
*/
 </script>
 <script type="text/javascript">
   var runningClock ;
   var time = 0;
   function startClock(){
      statusInit();

      runningClock =   setInterval(function(){
        time +=0.1 ;
        if(time>100){
            stopClock();
            return;
        }
        setInfo();

     },100);
   }
   
   function setInfo(){
        $('#recordBar').val(time);
        $('#recordBar').trigger('change');
        
        if(parseInt(time)%2 ==0){
            $('.record_box .recStatus').css('color','#333');
        }else{
            $('.record_box .recStatus').css('color','#00a600');
        }    
   
      $('.record_box .info').html(timeTxt() );
   }
   function timeTxt( )
    {
      var s = parseInt(time);      
      var ss =parseInt(60*(time -s))   ;
      if(s < 10 ) s='0'+s;
      if(ss < 10 ) ss='0'+ss;
      return s +"'" +ss +'"'

   }  
   function stopClock(){
        clearInterval(runningClock);
   }
   $('.record_box .recStatus').click(function(){
          ActionSwitch();
   });

   function statusInit(){
      time=0;
      setInfo();
   }

   $('body').bind("voice_init", function(){
      statusInit();
      $('.record_box').slideDown();
   });

    function recordDismiss(){
      statusInit();
      stopClock();
      $('.record_box').slideUp();
   }

 </script>