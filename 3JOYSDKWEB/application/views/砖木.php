<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta content="telephone=no" name="format-detection" />

<script type="text/javascript" language="javascript" src="<?php echo $viewspath;?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $viewspath;?>js/top_nav.js"></script>
<title>3卓网中心</title>
<style type="text/css">

html, body, div, span, applet, object, iframe, h1, button, input, textarea, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video {
    border: 0 none;
    font-family: "Microsoft Yahei",Verdana,Arial,Helvetica,sans-serif;
    margin: 0;
    padding: 0;
}

body {
    background: #f0f0f1 none repeat scroll 0 0;
    font-family: "微软雅黑";
}
body, html {
    height: 100%;
}

.sanzhuo-head {
    background: #333333 none repeat scroll 0 0;
    font-size: 14px;
    height: 60px;
}

.sanzhuo-head-box {
    margin: 0 auto;
    position: relative;
    width: 77%;
}



.sanzhuo-pclogo {
    float: left;
    margin-top: 15px;
	margin-left: 10%;
}


a {
    text-decoration: none;
	outline: medium none;
    overflow: hidden;
}
.sanzhuo-rt {
    float: right;
    height: 60px;
}

.sanzhuo-rt a {
    float: left;
    height: 28px;
    margin-top: 15px;
    width: 28px;
}

.log_box {
    font-size: 14px;
    padding: 10% 2%;
	
	
}

#content{
	width:100%;
	
	height:90%;
	float: left;
}
.sign-title {
    
    border: 1px solid #ccc;
    height: 40px;
    line-height: 40px;
    margin: 30px auto 0;
    width: 90%;
}
form.registerform {
    width: 100%;
}
.tang-pass-reg form.sign-form {
    float: left;
    margin: 8% 0 0 9%;
	position:relative;
}


.registerform tr {
    height: 25%;
	margin-bottom:10px;
}
.registerform td.user {
    color: #333333;
    font-size: 14px;

    width: 30%;
}




input[type="text"], input[type="password"],input[type="text1"] {
    color: #999999;
    display: inline-block;
    font-family: "microsoft yahei";
    font-size: 14px;
    padding-left: 6px;
	border: 2px solid #dcdbdb;
}
.inputxt {
    border: 1px solid #dddddd;
    height: 32px;
    line-height: 32px;
    transition: all 0.3s ease 0s;
    width: 90%;
}

.tang-pass-reg .form-code {
   width:35%;
   height:30px;
    
}


.registerform td a {
    color: #9a9a9a;
    display: inline-block;
    font-size: 12px;
    height: 34px;
    line-height: 34px;
    
    text-decoration: underline;
    width: 25%;
	margin-left: 15px;
}

form .reg-btn {
    height: 85px;
    
	margin:5% 0 40% 5%;
    width: 50%;
}
.reg-btn .user-btn {
    background: #fff none repeat scroll 0 0;
    border: 1px solid #ccc;
    color: #000;
    font-size: 20px;
    height: 42px;
    width: 100%;
}





.footer {
    background: #333 none repeat scroll 0 0;
    clear: both;
    height: 50%;
    text-align: center;
	
}


</style>
</head>

<body>

<div class="sanzhuo-head">
  
    
      <div class="sanzhuo-pclogo"><a href="http://user.3joy.cn/index.php"><img width="80%" height="25" src="<?php echo $viewspath;?>images/pc-logo.png"></a></div>
	  
	     
	
    
</div>



  

	


    <div style="border:none;" class="tang-pass-reg">
      
        <h2 style="font-size:18px; color:#444;margin-left:9.5%; margin-top: 10%;">忘记密码</h2>
   
      <form action="http://sdkweb.3joy.cn/index.php/forpassword" method="post" class="registerform sign-form" accept-charset="utf-8">
        <table>
          <tbody><tr>
            <td class="user" style="position:absolute;top:-2px;">邮箱</td>
            <td width="70%" style="position:absolute;left:50px;top:-5px"><input type="text" value="" class="inputxt" name="email">
            </td>
            <td>&nbsp;</td>
          </tr>
        <tr>
            <td class="user" style="position:absolute;top:70px;">用户名</td>
            <td width="70%"style="position:absolute;left:50px;top:65px"><input type="text" value="" class="inputxt" name="username">
            </td>
            <td></td>
          </tr>
            <tr><td class="user" style="position:absolute;top:140px;">验证码</td>
            <td width="70%" style="position:absolute;left:50px;top:130px"><input type="text" value="" placeholder="输入验证码" name="code" class="pass-text-input form-code">
              <em id="yw0_wrap"><img alt="点击刷新验证码" title="点击刷新验证码" id="captcha"  onclick="$('#captcha').attr('src','<?php echo base_url('index.php/forpassword/captcha');?>'+'/'+Math.random())"  src="<?php echo base_url('index.php/forpassword/captcha');?>" style="position:absolute;margin-left:30px;"></em>
            <td></td>
          </tr>
		  <tr>
		  <td></td>
		  <td width="50%" style="position:absolute;left:30px;top:200px"><p><input type="submit" style="width:100%;height:35px;font-size:14px; color:#FFFFFF; background:#ce2029;" value="找回" class="user-btn"></p></td>
		  <td></td>
		  </tr>
        </tbody></table>
         <p class="reg-btn">
       
        </p>
         </form>
    </div>
	
	
	

            



			
      
        
        

   
 
	
	   <div class="footer">   
  <div style="color:#5b5b5b; line-height:36px; font-size:1em;">Copyright © 2013-2014 Beijing 3joy Interactive Technology Co., Ltd.Corporation, All Rights Reserved &nbsp;<a href="http://www.miibeian.gov.cn" target="_blank" style="color:#999;">京ICP备14052723号</a>
     <div style="display:block;">北京闪卓互动科技有限公司 <a target="_blank" href="#" style="color:#999;">版权所有</a></div>
      </div>
 </div>
</body>
</html>
