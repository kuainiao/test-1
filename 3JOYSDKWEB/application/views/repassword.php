<?php $this->load->view('header.php');?> 
<script type="text/javascript">
    //点击刷新验证码
    function change_yzm(obj){
        $(obj).attr('src','<?php echo base_url('index.php/repassword/captcha');?>'+'/'+Math.random());
     }
</script>   
	<div class="log_box">
	
<!-- 手机网站适应性广告代码 -->
<ins data-ad-format="auto" data-ad-slot="1246518959" data-ad-client="ca-pub-1449491313257558" style="display:block" class="adsbygoogle"></ins>
            <form  accept-charset="utf-8" method="post" action="<?php echo base_url('index.php/repassword/index/'.$username);?>" style="float: none; margin: 30px auto;" >
            <ul>
			
                <li class="username">
                    <input type="password" name="oldpassword" placeholder="旧密码" value="">
                    <input type="password" name="newpassword" placeholder="新密码" value="">
                </li>
           
                <li class="pwd">
                    <input type="password" name="newpassword1" placeholder="重复密码" value="">
                </li>
                <li class="username">
                    <div style="position:relative;">
                       <input type="text" name="code" placeholder="验证码" value="" >
                    <img src="<?php echo base_url('index.php/repassword/captcha');?>" id="captcha" onclick="change_yzm(this)" width="100" height="32" style=" position:absolute; right: 0;
    top: 4px;"><a href="javascript:;" onClick="change_yzm(captcha)">换一张</a>
                      
                    </div>
                </li>
                <li class="btn_submit">
                    <button type="submit">
                       确 定</button>
                </li>
           
            </ul>
            </form>
        </div>
<?php $this->load->view('footer.php');?>