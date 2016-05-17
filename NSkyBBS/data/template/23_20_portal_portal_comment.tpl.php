<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); 
0
|| checktplrefresh('./template/default/portal/portal_comment.htm', './template/default/common/seccheck.htm', 1429524712, '20', './data/template/23_20_portal_portal_comment.tpl.php', './template/wxd_jdbbs', 'portal/portal_comment')
;?>
<div id="comment" class="bm">
<div class="bm_h cl">
<?php if(!$data['htmlmade']) { if($data['commentnum']) { ?>
<a href="javascript:;" class="y xi2" onclick="location.href=location.href.replace(/(\#.*)/, '')+'#cform';$('message').focus();return false;">发表评论</a>
<?php } } else { ?>
<a href="<?php echo $common_url;?>#cform" class="y xi2">发表评论</a>
<?php } ?>
<h3>最新评论</h3>
</div>
<div id="comment_ul" class="bm_c"><?php if(is_array($commentlist)) foreach($commentlist as $comment) { include template('portal/comment_li'); if(!empty($aimgs[$comment['cid']])) { ?>
<script type="text/javascript" reload="1">aimgcount[<?php echo $comment['cid'];?>] = [<?php echo implode(',', $aimgs[$comment['cid']]);; ?>];attachimgshow(<?php echo $comment['cid'];?>);</script>
<?php } } if(!empty($pricount)) { ?>
<p class="mtn mbn y">提示：本页有 <?php echo $pricount;?> 个评论因未通过审核而被隐藏</p>
<?php } if($data['commentnum']) { ?><p class="ptm pbm"><a href="<?php echo $common_url;?>" class="xi2">查看全部评论(<em id="_commentnum"><?php echo $data['commentnum'];?></em>)</a></p><?php } if(!$data['htmlmade']) { ?>
<form id="cform" name="cform" action="<?php echo $form_url;?>" method="post" autocomplete="off">
<div class="tedt">
<div class="area">
<textarea name="message" rows="3" class="pt" id="message" onkeydown="ctrlEnter(event, 'commentsubmit_btn');"></textarea>
</div>
</div>

<?php if($secqaacheck || $seccodecheck) { ?><?php
$sectpl = <<<EOF
<sec> <span id="sec<hash>" onclick="showMenu(this.id);"><sec></span><div id="sec<hash>_menu" class="p_pop p_opt" style="display:none"><sec></div>
EOF;
?>
<div class="mtm"><?php $sechash = !isset($sechash) ? 'S'.($_G['inajax'] ? 'A' : '').$_G['sid'] : $sechash.random(3);
$sectpl = str_replace("'", "\'", $sectpl);?><?php if($secqaacheck) { ?>
<span id="secqaa_q<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updatesecqaa('q<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } if($seccodecheck) { ?>
<span id="seccode_c<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updateseccode('c<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } ?></div>
<?php } if(!empty($topicid) ) { ?>
<input type="hidden" name="referer" value="<?php echo $topicurl;?>#comment" />
<input type="hidden" name="topicid" value="<?php echo $topicid;?>">
<?php } else { ?>
<input type="hidden" name="portal_referer" value="<?php echo $viewurl;?>#comment">
<input type="hidden" name="referer" value="<?php echo $viewurl;?>#comment" />
<input type="hidden" name="id" value="<?php echo $data['id'];?>" />
<input type="hidden" name="idtype" value="<?php echo $data['idtype'];?>" />
<input type="hidden" name="aid" value="<?php echo $aid;?>">
<?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<input type="hidden" name="replysubmit" value="true">
<input type="hidden" name="commentsubmit" value="true" />
<p class="ptn"><button type="submit" name="commentsubmit_btn" id="commentsubmit_btn" value="true" class="pn"><strong>评论</strong></button></p>
</form>
<?php } ?>
</div>
</div>