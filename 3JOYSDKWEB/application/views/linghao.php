<?php $this->load->view('header.php');?>
<link rel="stylesheet" href="<?php echo $viewspath;?>js/js/jDialog/jDialog.css" type="text/css">
<script type="text/javascript"  src="<?php echo $viewspath;?>js/js/jDialog/jquery.dialog.js"></script>
<script type="text/javascript"  src="<?php echo $viewspath;?>js/js/jDialog/jquery.drag.js" ></script>
<script type="text/javascript"  src="<?php echo $viewspath;?>js/js/jDialog/jquery.mask.js" ></script>
<script>
function codeCopy(code_num){
	prompt("请按CTRL+C快捷键进行复制！",code_num);
}
$(function(){
	$("#getLink").click(function(){
               $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('index.php/linghao/code_get');?>",
                        data: "id="+<?php echo $code_info->pf_id;?>,
                        dataType:'json',
                        success: function(data){
                          if(!!data && data.code==1){
                               var dialog = jDialog.confirm(data.message,{
                                        handler : function(button,dialog) {
                                                //window.location.href = "";
                                                dialog.close();
                                                window.location.reload();
                                        }
                                },{
                                        handler : function(button,dialog) {
                                               dialog.close();
                                                window.location.reload();
                                                
                                        }
                                });
                                
                          }else{
                               var dialog = jDialog.confirm(data.message,{
                                        handler : function(button,dialog) {

                                                dialog.close();
                                        }
                                },{
                                        handler : function(button,dialog) {

                                                dialog.close();
                                        }
                                });
                          }
                        }
                });
		
	});
	
});
</script>     
  <div class="job_menu">
    <ul>
      <li data-id="1" class="job_default"><a href="<?php echo site_url('fahao'); ?>" style="display:block;color:#ff330b;">礼包列表</a></li>
      <li data-id="2" > <a href="<?php echo site_url('cunhao'); ?>" style="display:block;">存号箱</a> </li>
    </ul>
  </div>
  <div class="hot_com">
    <ul>
      <li> 
      
        <dl>
          <dt> <img src="<?php echo empty($upload_logo)?($urlpath . '/uploads' . $iconurl):($urlpath . '/uploads/' . $upload_logo); ?>" width="60" height="60" style="float:left;"/><span style="margin-left:5px;" > [<?php echo $pf_game_name;?>] <?php echo $code_info->pf_title;?></span> </dt>
          <dt style="font-size:0.9em;"> <span style="margin-left:5px;"> 剩余情况:</span> <span style=" color:#FF6600; margin-left:5px;"><?php echo $rates;?>%</span> </dt>
          <?php if(!empty($code_num)):?>
          	<dt style="clear:both;"><span style="font-size:0.8em; ">激活码：</span><input type="text" value="<?php echo $code_num;?>" style="border:1px solid #ccc;"/><a onClick="codeCopy('<?php echo $code_num;?>');return false" href="javascript:void(0)">复制</a></dt>
          <?php else:?>
	          <?php if(time()>=$code_info->pf_start_time && time()<=$code_info->pf_end_time):?>
	          	<?php if($rates != 0): ?>
	          		<dt style=" clear:both; margin-top:15px;"> 
	          		<a href="javascript:void(0)" id="getLink" style=" padding:4px; background:#FF6600; text-align:center; color:#FFFFFF; border-radius:5px;"> 领号 </a></dt>
	          	<?php else:?>
	          		<a href="javascript:void(0)" class="fahao_list_btn end fahao_xiang">已领完</a>
	          	<?php endif;?>
	          <?php else:?>
	          	<a href="javascript:void(0)" class="fahao_list_btn end fahao_xiang">结束</a>
	          <?php endif;?>
          <?php endif;?>
        </dl>
        </li>
    </ul>
  </div>
  <div class="hot_com">
    <ul>
      <li>
        <dl>
          <dt style="margin-bottom:10px;"> <span style="border-left:5px solid  #FF9900; padding-left:5px; margin-bottom:8px;"> 礼包内容：</span> </dt>
          <?php echo $code_info->pf_explain;?>
        </dl>
        </a></li>
    </ul>
    <ul>
      <li>
        <dl>
          <dt style="margin-bottom:10px;"> <span style="border-left:5px solid  #FF9900; padding-left:5px; margin-bottom:8px;"> 使用方法：</span> </dt>
          <?php echo $code_info->pf_method;?>
        </dl>
        </a></li>
    </ul>
  </div>
<?php $this->load->view('footer.php');?>