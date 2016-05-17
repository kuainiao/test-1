<?php $this->load->view('header.php');?> 
<script type="text/javascript">
    //点击刷新验证码
    function change_yzm(obj){
        $(obj).attr('src','<?php echo base_url('index.php/reemail/captcha');?>'+'/'+Math.random());
     }
</script>   
	<div class="log_box">
	
		<form id="form"  accept-charset="utf-8" method="post" action="<?php echo base_url('index.php/reemail/index/'.$username);?>" >
            <ul>
			
                <li class="username">
                    <input type="text" name="old_email" disabled="disabled" placeholder="原邮箱" value="<?php echo $get_user[2]; ?>">
                    <input type="password" name="password" placeholder="密码" value="">
                </li>
           
                <li class="pwd">
                    <input type="text" name="new_email" placeholder="新邮箱" value="">
                </li>
                <li class="username">
                    <div style="position:relative;">
                       <input type="text" value="" name="code" placeholder="验证码">
                      <img src="<?php echo base_url('index.php/reemail/captcha');?>" id="captcha" onclick="change_yzm(this)" width="80" height="32" style=" position:absolute; right: 0;
    top: 4px;" /><a href="javascript:;" onClick="change_yzm(captcha)">换一张</a>
                      
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