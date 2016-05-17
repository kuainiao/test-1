<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>热血问战_修改密码</title>
<!--css链接地址-->
<link href="css/per_center/forgot_password/forgotpsw.css" rel="stylesheet" type="text/css" />
<link href="css/verification/forgotpw/verification.css" rel="stylesheet" type="text/css" />
<script  src="js/zhuce/forgotpw.js" type="text/javascript" language="javascript"></script>
<script  type="text/javascript" language="javascript"  src="js/verification/verification/index.js"></script>
<script  type="text/javascript" language="javascript"  src="js/common/jquery-1.8.3.min.js"></script>
<script  type="text/javascript" language="javascript"  src="js/verification/verification/webluker_1.0.8.js"></script>
<script  type="text/javascript" language="javascript"  src="js/verification/verification/MochiKit.js"></script>
<script  type="text/javascript" language="javascript" src="js/verification/yanzheng.js"></script>
<script  type="text/javascript" language="javascript" src="js/yazhengma/yanzhengma.js"></script>
<script  type="text/javascript" language="javascript" src="js/verification/yanzheng.js"></script>
<!--判断是否为IE6-->
<style type="text/css">
#ie6-warning{
background: #FFFF99 url("/upload/201006/20100628012515690.gif") no-repeat scroll 8px center;
position:absolute;
top:0;
left:0; 
font-size:12px;
color:#333;
width:100%;
padding: 2px 15px 2px 23px;
text-align:left;
z-index:9999;
}
#ie6-warning a {
text-decoration:none;
}
</style>
</head>
<body onLoad="createCode();">
<!--[if lte IE 6]>
<div id="ie6-warning">您正在使用浏览器版本过低，为了获得更好的浏览体验，建议您升级到 <a href="http://www.microsoft.com/china/windows/internet-explorer/" target="_blank">Internet Explorer 8</a> 或以下浏览器： <a href="Firefox</a'>http://www.mozillaonline.com/">Firefox</a> / <a href="Chrome</a'>http://www.google.com/chrome/?hl=zh-CN">Chrome</a> / <a href="Safari</a'>http://www.apple.com.cn/safari/">Safari</a> / <a href="Opera</a'>http://www.operachina.com/">Opera</a>
</div>
<script type="text/javascript">
function position_fixed(el, eltop, elleft){
// check if this is IE6 php程序员站
if(!window.XMLHttpRequest)
window.onscroll = function(){
el.style.top = (document.documentElement.scrollTop + eltop)+"px";
el.style.left = (document.documentElement.scrollLeft + elleft)+"px";
}
else el.style.position = "fixed"; phperz.com
} 
position_fixed(document.getElementById("ie6-warning"),0, 0);
</script> 
<![endif]-->
<!--整体框架-->
<div class="warp">
  <!--顶部框架-->
  <div class="warp_box">
    
    <div class="header">
      <div class="login_logo"><a href="#"><img width="300" height="72" src="images/index/logo.png"/></a></div>
      <div class="nav_header"> <div class="top_bar">
    <ul class="share">
          <li><a href="http://www.9sky.net.cn">返回</a></li>
      </ul>
    </div>
    </div>
    </div>
  </div>

  <div class="forgot_main">
    <!--填写找回账号-->
    <div class="forgot_box_tabs" >
      <div class="email_forgot">
          <form action="change.php" method="post">
        <div class="field-group">
            <label class="required title"><span>*</span>账号:</label>
            <span class="control-group" id="password1_input">
            <div class="input_add_long_background">
             
                <input class="register_input email_forgot_input" type="text" id="username" name="username" maxLength="20" value="" onblur="checkPwd1(this.value);"  placeholder="账号"/>
             
            </div>
            </span> </div>
        <div class="field-group">
            <label class="required title"><span>*</span>原密码:</label>
            <span class="control-group" id="password1_input">
            <div class="input_add_long_background">
             
                <input class="register_input email_forgot_input" type="password" id="password1" name="password1" maxLength="20" value="" onblur="checkPwd1(this.value);"  placeholder="原密码"/>
             
            </div>
            </span> </div>
             <div class="field-group">
            <label class="required title"><span>*</span>新密码:</label>
            <span class="control-group" id="password1_input">
            <div class="input_add_long_background">
             
                <input class="register_input email_forgot_input" type="password" id="password2" name="password2" maxLength="20" value="" onblur="checkPwd1(this.value);"  placeholder="新密码"/>
             
            </div>
            </span> </div>
                     <div class="field-group">
            <label class="required title"><span>*</span>确认新密码:</label>
            <span class="control-group" id="password1_input">
            <div class="input_add_long_background">
             
                <input class="register_input email_forgot_input" type="password" id="password22" name="password22" maxLength="20" value="" onblur="checkPwd1(this.value);"  placeholder="确认新密码"/>
             
            </div>
            </span> </div>
            <!--<div class="field-group">
            <label class="required title"><span>*</span>验证码:</label>
            <span class="control-group" id="code_input">
            <div class="input_add_background" style="margin-right:15px;">
              <input class="register_input_ot yanzheng" type="text" id="code" name="code" max_length="6" value="" onblur="checkAuthCode(this.value);"placeholder="验证码"/>
                 <span id="captchaImg" style="float:left;"> 
                   <input type="button" id="checkCode" class="code" style="width:60px" onClick="createCode()" />
                    </span> 
                    <span> <a href="#" onClick="createCode()">看不清楚</a></span> </div>
            </span>
             </div>-->
        
    	<div class="field-group">
                  <span class="control-group" id="mobile_input">
                  <div class="input_add_long_background">
                      <input type="submit" class="btn_login" value="确认" />

                  </div>
                  </span> </div>
          
        </form>
      </div>
    </div>
 
    
  </div>

  </div>

  
 <div class="trail" id="Gtrail">
  <div style="text-align:center; font-size:12px; margin:0 auto;">
    <div id="ZWTrail">
      <p><a class="sinatail" target="_blank" href="#">热血问战官网简介</a> | <a class="sinatail" target="_blank" href="#">论坛社区</a> | <a class="sinatail" target="_blank" href="#">网站合作</a> | <a class="sinatail" target="_blank" href="#">广告服务</a> | <a class="sinatail" target="_blank" href="#">联系我们</a> | <a class="sinatail" target="_blank" href="#">免责声明</a> | <a class="sinatail" target="_blank" href="#">招聘信息</a></p>
      <br>
      <p></p>
      <br>
      <p>九天创世科技有限公司 <a target="_blank" href="#">版权所有</a></p>
    </div>
  </div>
</div>
</div>
</body>
</html>
