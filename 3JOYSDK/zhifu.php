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
<script src="js/jquery-1.8.3.min.js" language="javascript"></script>
<script src="js/js.js" language="javascript"></script>
<script src="js/jquery.mobile-1.3.2.js" language="javascript"></script>
<style>

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
        <li><a href="#">闪币</a></li>
      </ul>
      <div class="resp-tabs-container" style="display:block;">
         <form action="pay.php" method="get" id="pay_form" data-ajax="false">
    <input name="partner" type="hidden" value="<?php echo $_GET['partner'];?>" />
      <input name="gameId" type="hidden" value="<?php echo $_GET['gameId'];?>" />
      <input name="cpInfo" type="hidden" value="<?php echo $_GET['cpInfo'];?>" />
    <input name="orderMoney" type="hidden" value="<?php echo $_GET['orderMoney'];?>" />
      <input name="uid" type="hidden" value="<?php echo $_GET['uid'];?>" />
      <input name="itemName" type="hidden" value="<?php echo $_GET['itemName'];?>" />
    <input name="payType" type="hidden" value="alipay" />
        <div class="new_posts" id="tab">
          <div class="vertical_post">
          <div class="yuan-number">
            <div data-role="collapsible" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u"  data-iconpos="right" >
      <h1>  <span class="margin-left10"><?php echo $_GET['orderMoney'];?></span><span>元</span></h1>
     <p class="marginf-left10"><span>用户：</span><span><?php echo $_GET['uid'];?></span></p>
     <p><span>商品：</span><span><?php echo $_GET['itemName'];?></span></p></p>
    </div>
           
            </div>
            <p class="yuan-tips">确认无误后请到支付宝付款！</p>
            <div class="zhifubox"><a href="javascript:;"  class="zhifubtn" data-role="button" id="sub_pay">去支付宝充值</a></div>
          </div>
        </div>
        </form>
      </div>
      <div class="resp-tabs-container" style="display:none;">
        <div class="new_posts" id="tab">
          <div class="vertical_post">
            <div class="yuan-number">
            <div data-role="collapsible" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u"  data-iconpos="right" >
      <h1>  <span class="margin-left10"><?php echo $_GET['orderMoney'];?></span><span>元</span></h1>
      <p>  <p class="marginf-left10"><span>用户：</span><span><?php echo $_GET['uid'];?></span></p>
                <p class="topdivdeline"><span>商品：</span><span><?php echo $_GET['itemName'];?></span></p></p>
    </div>
           
            </div>
            <div> </div>
            <div>
              <form action="pay.php" method="get" id="card_form" class="form1" data-ajax="false">
    <input name="partner" type="hidden" value="<?php echo $_GET['partner'];?>" />
      <input name="gameId" type="hidden" value="<?php echo $_GET['gameId'];?>" />
      <input name="cpInfo" type="hidden" value="<?php echo $_GET['cpInfo'];?>" />
    <input name="orderMoney" type="hidden" value="<?php echo $_GET['orderMoney'];?>" />
        <input name="uid" type="hidden" value="<?php echo $_GET['uid'];?>" />
        <input name="itemName" type="hidden" value="<?php echo $_GET['itemName'];?>" />
    <input name="payType" type="hidden" value="szpay"  />
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
                <input type="text"  name="cardNum" placeholder="卡号：请输入充值卡号" value="" maxlength="25"  />
                <input type="password" placeholder="密码：请输入充值卡密码" value="" maxlength="25"  name="cardPwd" />
                <input type="submit" value="立即充值" />
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="resp-tabs-container" style="display:none;">
        <form action="pay.php" method="get" id="coin_form">
    <input name="partner" type="hidden" value="<?php echo $_GET['partner'];?>" />
      <input name="gameId" type="hidden" value="<?php echo $_GET['gameId'];?>" />
      <input name="cpInfo" type="hidden" value="<?php echo $_GET['cpInfo'];?>" />
    <input name="orderMoney" type="hidden" value="<?php echo $_GET['orderMoney'];?>" />
          <input name="uid" type="hidden" value="<?php echo $_GET['uid'];?>" />
          <input name="itemName" type="hidden" value="<?php echo $_GET['itemName'];?>" />
    <input name="payType" type="hidden" value="coin" />
        <div class="new_posts" id="tab">
          <div class="vertical_post">
            <div class="shanbibox">
              <div class="shanbinumber">闪币余额：<span>
                      <?php
                            require_once dirname(__FILE__).'/'.'models/PFUserModel.php';
                            $new = new PFUserModel();
                            echo $new->selectLightCoin($_GET['uid']);
                      ?>
                      闪币</span></div>
              </div>
            <div class="clear"></div>
            <div class="yuan-number">
            <div data-role="collapsible" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u"  data-iconpos="right" >
      <h1>  <span class="margin-left10"><?php echo $_GET['orderMoney']*100;?></span><span>闪币</span></h1>
      <p>  <p class="marginf-left10"><span>用户：</span><span><?php echo $_GET['uid'];?></span></p>
                <p class="topdivdeline"><span>商品：</span><span><?php echo $_GET['itemName'];?></span></p></p>
    </div>
           
            </div>
            <p class="yuan-tips">若余额不足，请到<a href="http://user.3joy.cn" style="color:#FF0000;">http:user.3joy.cn</a>进行充值！</p>
              <input type="submit" value="立即充值" />
          </div>
        </div>
      </div>
      </form>
    </div>
  </div>
  <div class="clear"></div>
  <div class="copy" style="font-size:14px;">
    <!-- start copy -->
   <p>充值服务商客服电话：<a href="tel:00000000"><span>010-00000000</span></a></p>
   <p> 北京闪卓互动科技有限公司 版权所有</p>
 
</div>
</div>
<script>
$('#sub_coin').click(function(){

  $('#coin_form').submit();
});
$('#sub_card').click(function(){

  $('#card_form').submit();
});
$('#sub_pay').click(function(){

  $('#pay_form').submit();
});
</script>
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
