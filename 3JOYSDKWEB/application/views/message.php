<?php $this->load->view('header.php');?> 
<script type="text/javascript">
setTimeout("page_skip('<?php echo $msg_url;?>')",3000);
function page_skip(url) {
  window.location.href = url;
}
</script>
<div class="sanzhuo-main">
  
  <div class="sz-content">
    <div class="main-cbox">
        
        <div class="thiscontent">
          <div class="thisaddBox pd20" style="width:100%;border:4px solid #dde4e8;">
            <div class="form-content" style="padding:0px;">
    <div class="tang-pass-reg" style="border:none;">
      <div style=" float:left; text-align:center; width:100%; margin-top:50px;">
      <img src="<?php echo $msg_img;?>"  />
      <p><h3><?php echo $msg_text;?></h3></p>
      <p><a href="<?php echo $msg_url;?>" style="color:#0d7dbd; font-size:14px; float: left; text-align:center; width:100%;"><?php echo $msg_back;?></a></p>
	  <span id="showtext" style="display:none">2</span>
     
      </div>
    </div>
  </div>
        </div>
        </div>
        </div>
   </div>
</div>

<?php $this->load->view('footer'); ?>