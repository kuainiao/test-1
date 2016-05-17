<?php $this->load->view('header.php');?>    
        <div class="job_menu">
            <ul>
                <li data-id="1" class="job_default"><a href="<?php echo site_url('fahao/index/'.$username.'/'.$gameid); ?>" style="display:block;color:#ff330b; background:#fff;">礼包列表</a></li>
              <li data-id="2" ><a href="<?php echo site_url('cunhao/index/'.$username.'/'.$gameid); ?>" style="display:block; background:#fff; ">存号箱</a></li>
          </ul>
        </div>
        <div class="hot_com" style="margin-bottom:10px; background:#fff; margin-top:10px;">
       <div class="list_title"><span style="border-left:3px solid #FF6600; padding-left:5px;">新服礼包列表</span> </div>
            <ul><?php foreach($code as $v):?>
            	<?php if($v->pf_type == 3) :?>
                        <li><a href="<?php echo site_url('linghao/index/'.$v->pf_id); ?>">
      <dl>
                                <dt>
                                    [<?php echo $v->pf_game_name;?>] <?php echo $v->pf_title;?>
                                </dt>
                                <dd>
                                    类别：<?php echo $v->pf_category;?>
                                </dd>
                                <dd class="dateTime">
                                    <?php echo date('Y-m-d',$v->pf_start_time);?>至<?php echo date('Y-m-d',$v->pf_end_time);?>
                                </dd>
                                <dd class="area">
                                 礼包： <?php echo $v->pf_number_last;?>/<?php echo $v->pf_number_total;?>
                                </dd>
                            </dl>
                        </a></li>
                 <?php endif;?>
                 <?php endforeach;?>
            </ul>
        </div>
        <div class="hot_com" style="margin-bottom:10px; background:#fff; margin-top:10px;">
       <div class="list_title"><span style="border-left:3px solid #FF6600; padding-left:5px;">公会礼包列表</span> </div>
            <ul>
                        <?php foreach($code as $v):?>
            	<?php if($v->pf_type == 2) :?>
                        <li><a href="<?php echo site_url('linghao/index/'.$v->pf_id); ?>">
      <dl>
                                <dt>
                                    [<?php echo $v->pf_game_name;?>] <?php echo $v->pf_title;?>
                                </dt>
                                <dd>
                                    类别：<?php echo $v->pf_category;?>
                                </dd>
                                <dd class="dateTime">
                                    <?php echo date('Y-m-d',$v->pf_start_time);?>至<?php echo date('Y-m-d',$v->pf_end_time);?>
                                </dd>
                                <dd class="area">
                                 礼包： <?php echo $v->pf_number_last;?>/<?php echo $v->pf_number_total;?>
                                </dd>
                            </dl>
                        </a></li>
                 <?php endif;?>
                 <?php endforeach;?>
            </ul>
        </div>
        <div class="hot_com" style="margin-bottom:10px; background:#fff; margin-top:10px;">
       <div class="list_title"><span style="border-left:3px solid #FF6600; padding-left:5px;">激活码列表</span> </div>
            <ul>
                        <?php foreach($code as $v):?>
            	<?php if($v->pf_type == 1) :?>
                        <li><a href="<?php echo site_url('linghao/index/'.$v->pf_id); ?>">
      <dl>
                                <dt>
                                    [<?php echo $v->pf_game_name;?>] <?php echo $v->pf_title;?>
                                </dt>
                                <dd>
                                    类别：<?php echo $v->pf_category;?>
                                </dd>
                                <dd class="dateTime">
                                    <?php echo date('Y-m-d',$v->pf_start_time);?>至<?php echo date('Y-m-d',$v->pf_end_time);?>
                                </dd>
                                <dd class="area">
                                 礼包： <?php echo $v->pf_number_last;?>/<?php echo $v->pf_number_total;?>
                                </dd>
                            </dl>
                        </a></li>
                 <?php endif;?>
                 <?php endforeach;?>
            </ul>
        </div>
        <div class="hot_com" style="margin-bottom:10px; background:#fff; margin-top:10px;">
       <div class="list_title"><span style="border-left:3px solid #FF6600; padding-left:5px;">特权礼包列表</span> </div>
            <ul>
                        <?php foreach($code as $v):?>
            	<?php if($v->pf_type == 4) :?>
                        <li><a href="<?php echo site_url('linghao/index/'.$v->pf_id); ?>">
      <dl>
                                <dt>
                                    [<?php echo $v->pf_game_name;?>] <?php echo $v->pf_title;?>
                                </dt>
                                <dd>
                                    类别：<?php echo $v->pf_category;?>
                                </dd>
                                <dd class="dateTime">
                                    <?php echo date('Y-m-d',$v->pf_start_time);?>至<?php echo date('Y-m-d',$v->pf_end_time);?>
                                </dd>
                                <dd class="area">
                                 礼包： <?php echo $v->pf_number_last;?>/<?php echo $v->pf_number_total;?>
                                </dd>
                            </dl>
                        </a></li>
                 <?php endif;?>
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