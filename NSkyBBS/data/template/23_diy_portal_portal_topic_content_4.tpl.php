<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('portal_topic_content_4');?><?php include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em> <?php echo $topic['title'];?>
</div>
</div>
<link id="style_css" rel="stylesheet" type="text/css" href="static/topic/t1/style.css">
<style id="diy_style" type="text/css"></style>
<div id="widgets">
</div>
<div class="wp">
<!--[diy=diypage]--><div id="diypage" class="area"><div id="frame1" class="frame move-span frame-1 cl"><div class="frame-title title"><span class="titletext">活动中心</span></div><div id="frame1_left" class="column frame-1-c"><div id="frame1_left_temp" class="move-span temp"></div></div></div></div><!--[/diy]-->
<?php if($topic['allowcomment']==1) { $data = &$topic;
$common_url = "portal.php?mod=comment&amp;id=$topicid&amp;idtype=topicid";
$form_url = "portal.php?mod=portalcp&amp;ac=comment";
$commentlist = portaltopicgetcomment($topicid);?><?php include template('portal/portal_comment'); } ?>
</div><?php include template('common/footer'); ?>