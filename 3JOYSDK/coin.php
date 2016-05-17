<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
"http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
<title>3卓网支付中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link href="./css/style.css" media="all" rel="stylesheet" type="text/css" />
<link href="css/css.css" media="all" rel="stylesheet" type="text/css" />
<link  href="css/jquery.mobile-1.3.2.css" media="all" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.8.3.min.js"language="javascript"></script>
<script src="js/jquery.mobile-1.3.2.js" language="javascript"></script>
<script src="js/js.js" language="javascript"></script>

<script type="text/javascript" language="javascript">
$.mobile.hidePageLoadingMsg();
</script>
<style>
.wenxintishi{background: none repeat scroll 0 0 #fffcee;
    border: 1px solid #f0dbb4;
    border-radius: 2px;
    color: #d78f22; line-height:33px; margin-bottom:10px; padding-left:5px;}
.mpay-box{width:100%;}
.mpay-left{width:40%; float:left;}
.mpay-right{float:left;  font-size:89%; line-height:350%; margin-left:10px; color:#333;}


</style>
</head>
<body>
<div class="wrap">
  <div class="span1_of_3">

    <!-- start span1_of_3 -->
    <div id="verticalTab" style="margin-top:2px;">
     
      <!-- start vertical Tabs-->
      <ul class="resp-tabs-list" id="sidenav" >
        <li class="active"><a href="#">支付宝</a></li>
        <li><a href="#">充值卡</a></li>
     
      </ul>
      <div class="resp-tabs-container" style="display:block;">
        <div class="new_posts" id="tab">
          <div class="vertical_post">
          <div class="yuan-number" style="background:#fff;">
          <form class="form1" action="coinpay.php" method="get" data-ajax="false" >
            <p class="wenxintishi">温馨提示：1元=100闪币；充值单位：元</p>
            <p>充值账户：<span><?php echo $_GET['uid'];?></span></p>
              <input name="uid" type="hidden" value="<?php echo $_GET['uid'];?>" />
              <input name="payType" type="hidden" value="alipay" />
             <div class="mpay-box">
              <div class="mpay-left"> <input name='orderMoney' type="text"  placeholder="充值金额" value=""id="alpay"  maxlength="9" /></div>
              <div class="mpay-right">
                    <span class="error-prompt"></span> 元=<span id="light_coin" style="color:red;">0</span>闪币
              </div>
              </div>
             
         
              <p class="yuan-tips" style="width:100%; clear:both;">确认无误后请到支付宝付款！</p>
              
               <input type="submit" value="去支付宝充值"   />
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="resp-tabs-container" style="display:none;">
        <div class="new_posts" id="tab">
          <div class="vertical_post">
            
         
           <div class="yuan-number" style="background:#fff;">
              <form class="form1" action="coinpay.php" method="get" data-ajax="false" >
             <p class="wenxintishi">温馨提示：1元=100闪币；充值单位：元</p>
            <p>充值账户：<span><?php echo $_GET['uid'];?></span></p>
                  <input name="uid" type="hidden" value="<?php echo $_GET['uid'];?>" />
                  <input name="payType" type="hidden" value="szpay" />
              <div class="mpay-box">
              <div class="mpay-left"> <input name='orderMoney' type="text"  placeholder="充值金额"  value=""  maxlength="9" id="alpay1"/></div>
              <div class="mpay-right">
                    <span class="error-prompt"></span> 元=<span id="light_coin1" style="color:red;">0</span>闪币
              </div>
              </div>
            
             <div data-role="controlgroup" data-type="horizontal">
               <div style="width:50%; float:left;">
                <select name="cardTypeCombine">
                  <option value="0">移动充值卡</option>
                  <option value="1">联通充值卡</option>
                  <option value="2">电信充值卡</option>
                </select>
                </div>
                <div style=" width:50%; float:left;">
                <select name="cardMoney">
                  <option value="10">面值：10元</option>
                  <option value="20">面值：20元</option>
                  <option value="30">面值：30元</option>
                  <option value="50" selected="selected">面值：50元</option>
                  <option value="100">面值：100元</option>
                  <option value="200">面值：200元</option>
                  <option value="500">面值：500元</option>
                </select>
                </div>
                </div>
                
                <input type="text"  name="cardNum" placeholder="卡号：请输入充值卡号" value="" maxlength="25" id="search_text" />
                <input type="password" name="cardPwd" placeholder="密码：请输入充值卡密码" value="" maxlength="25" id="search_text1" />
                <input type="submit" value="立即充值" />
              </form>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <div class="clear"></div>
  <div class="copy" style="font-size:14px;">
    <!-- start copy -->
   <p>充值服务商客服电话：<a href="tel:00000000"><span>010-00000000</span></a></p>
   <p> 北京闪卓互动科技有限公司 版权所有</p>
 
</div>
</div>

<!--<div id="dialog1" class="dialog">
<p class="dialog-title">安装提示</p>
<p class="dialog-content">为了保证您交易安全，需要您安装支付宝安全支付服务，才能进行付款。点击确定进行安装！</p>
<div class="dialog-btn-box" style="width:40%; " ><a href="javascript:void(0);" id="confirm">确定</a></div>
<div class="dialog-btn-box" style="width:40%; left:53%;" ><a href="javascript:void(0);" id="cancel">取消</a></div>
</div>-->
<div id="dialog2" class="dialog">
<p class="dialog-title">支付失败</p>
<p class="dialog-content"><span>移动充值卡</span>（尾号：<span>5555</span>）卡号密码验证失败，请稍后再试！</p>
<div class="dialog-btn-box" ><a href="javascript:void(0);" id="close1">确定</a></div>
</div>
<div id="dialog3" class="dialog">
<p class="dialog-title">支付失败</p>
<p class="dialog-content">您的闪币余额不足请到http://user.joy.cn充值闪币！</p>
<div class="dialog-btn-box" ><a href="javascript:void(0);" id="close2">确定</a></div>
</div>
<div id="fullbg"></div>
</body>
</html>
