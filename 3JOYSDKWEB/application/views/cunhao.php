<?php $this->load->view('header.php');?>
<script>
     function codeCopy(code_num){
                prompt("请按CTRL+C快捷键进行复制！",code_num);
            }
</script>    
        <div class="job_menu">
            <ul>
                <li data-id="1"><a href="<?php echo site_url('fahao/index/'.$username.'/'.$gameid); ?>" style="display:block;">礼包列表</a></li>
              <li data-id="2"  class="job_default"><a href="<?php echo site_url('cunhao/index/'.$username.'/'.$gameid); ?>" style="display:block;color:#ff330b;">存号箱</a></li>
          </ul>
        </div>
        <div class="hot_com">
            <ul>
            	<?php foreach($list as $v) :?>
                        <li>
                            <dl>
                                <dt>
                                   <a href="fahaoxiangxi.html"> [<?php echo $v['pf_game_name']?>] <?php echo $v['title'];?><span>(<?php if($v['type'] ==1){echo '激活码';}elseif($v['type'] ==2){echo '公会礼包';}elseif($v['type'] ==3){echo '新服礼包';}else{echo '特权礼包';}?>)</span> </a>                                </dt>
                                <dd class="dateTime">
                                   使用有效期： <?php echo date('Y-m-d',$v['pf_start_time']);?>至<?php echo date('Y-m-d',$v['pf_end_time']);?>
                                </dd>
                           <dt><span style="font-size:0.8em; ">激活码：</span><input type="text" value="<?php echo $v['pf_number'];?>" style="border:1px solid #ccc;"/><a rel="<?php echo $v['pf_number'];?>" onClick="codeCopy('<?php echo $v['pf_number'];?>');return false" href="javascript:void(0);">复制</a></dt>
                            
                            </dl>
                       </li>
                <?php endforeach;?>         
            </ul>
        </div>
        <div class="job_pages">
            
<!-- AspNetPager V7.1 for VS2005 & VS2008  Copyright:2003-2007 Webdiyer (www.webdiyer.com) -->
<div id="AspNetPager1" class="previous_next">
<?php echo $this->pagination->create_links(); ?>
</div>
<!-- AspNetPager V7.1 for VS2005 & VS2008 End -->

<?php $this->load->view('footer.php');?>