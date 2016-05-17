<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=no;">
<script type="text/javascript" language="javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" language="javascript" src="js/js.js"></script>
<title>充值页面</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <span class="mobile-title">游戏充值 </span>
</header>                  
<!--section begin-->
<section class="banner hide" id="banner">
  <div class="horn-btn"></div>
  <ul class="horn">
  </ul>
  <div class="cancel-btn"></div>
</section>
<section class="center-content main-content" id="main-container">
<div class="use_info">
 	<p>充值账号：<span><?php echo $_GET['uid'];?></span></p>
    <p>所需金额：<span><?php echo $_GET['orderMoney'];?></span><span>元</span></p>
    </div>
 <div class="pay">
 	  <p>请选择充值方式</p>
    <div class="pay-con" id="payType">
        <img src="images/zhifubao.png" rel="3"  />
    <img src="images/yidong.png" rel="0" />
     <img src="images/liantong.png" rel="1" />
    <img src="images/dianxin.png" rel="2"  />
    </div>
    </div>
  <form action="pay.php" method="get">
      <input name="payType" type="hidden" value="1" />
      <input name="partner" type="hidden" value="<?php echo $_GET['partner'];?>" />
      <input name="gameId" type="hidden" value="<?php echo $_GET['gameId'];?>" />
      <input name="uid" type="hidden" value="<?php echo $_GET['uid'];?>" />
      <input name="orderMoney" type="hidden" value="<?php echo $_GET['orderMoney'];?>" />
      <input name="cpInfo" type="hidden" value="<?php echo $_GET['cpInfo'];?>" />
      <input name="itemName" type="hidden" value="热血问战游戏充值" />
      <input name="cardTypeCombine" type="hidden" value="" />
      <input name="cardMoney" type="hidden" value="" />
      <!-- drop-down box -->
      <div id="historyNum" class="history-num"> </div>
      <div id="mobile_state" class="mobile_state place"></div>
    </div>
    <!-----------coins begin-------------->

    <div class="coins clearfix cur" id="cardMoney" style="display:none;">
     <p>请选择充值卡面额</p>
      <ul>
        <li value="10">10元</li>
        <li value="20">20元</li>
        <li value="30">30元</li>
        <li value="50">50元</li>
        <li value="100">100元</li>
        <li value="300">300元</li>
      </ul>
    </div>
    <div class="mobile-num" style="display:none;" id="coins">
      <div class="mum">
        <input  name="cardNum"  size="" class="input" placeholder="&nbsp;输入充值卡号" type="text">
      </div> 
       <div class="mum" >
        <input  name="cardPwd"  size="" class="input" placeholder="&nbsp;输入充值卡密码" type="password">
      </div> 
    </div>
    <div class="submit clearfix">
      <ul>
        <li>
          <div>
            <input data-payer="2002" name="alipay" class="btn pay-btn" id="alipay" value="" type="submit">
            <div class="ico-apay-div"><span data-payer="2002" class="ico-pay ico-apay">确定</span></div>
          </div>
          
        </li>
        </ul>
    </div>
  </form>
  </section>
<footer id="footer">
  <div>
    <p class="hot-line">充值服务商客服电话：<a href="tel:010-82615089"><span>010-82615089</span></a></p>
    <p> <a href="#">帮助和意见</a> </p>
    Copyright © 2013-2014<br>
   北京九天创世科技有限公司 版权所有 </div>
</footer>
</body>
</html>
