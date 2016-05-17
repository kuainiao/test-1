<?php $this->load->view('header.php');?>
<div class="log_box">	
<ul>
         
         
			 <li class="btn_submit" style="margin-bottom:10px; text-align:center; ">
               <img src="http://bbs.3joy.cn/uc_server/avatar.php?uid=<?php echo $user_info->uid;?>&type=virtual&size=middle" width="124" height="124" />
               <p>3卓网玩家：<span><?php echo $user_info->username;?></span></p>
                <p>闪币：<span><?php if(empty($coin)){echo 0;}else {echo $coin[0]->light_coin;}?></span>&nbsp;金钱：<span><?php if(empty($money)){echo 0;}else {echo $money[0]->extcredits2;}?></span></p>
               
                    <p>邮箱：<span><?php echo $user_info->email;?></span></p>
              </li>
        
           
           
      </ul>
            <ul>
         
         
			 <li class="btn_submit" style="margin-bottom:10px; ">
                    <a href="<?php echo site_url('repassword/index/'.$user_info->username); ?>">
                    <button type="button" class="btn1" style="background:#fff; color:#000000; border:1px solid #ccc;">
                    
                    修改密码
                       </button></a>
              </li>
        
              <li class="btn_submit"  style="margin-bottom:10px; ">
                   <a href="<?php echo site_url('reemail/index/'.$user_info->username); ?>"> 
                 <button type="submit" style="background:#fff; color:#000000; border:1px solid #ccc;">
                     更换邮箱</button></a>
              </li>
           
      </ul>
        </div>
<?php $this->load->view('footer.php');?>