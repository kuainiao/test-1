<?php exit;?>
<!--{template common/header}-->
<div class="G6qNfDQqUt"><a href="forum.php">{echo m_lang('home')}</a> <em> &gt; </em> <a href="home.php?mod=space&do=blog&view=me">{lang blog}</a> <em> &gt; </em> {lang delete_blog}</div>

<!--{if $_GET[op] == 'delete'}-->
<div class="T9onYOQMi0">
<form method="post" autocomplete="off" action="home.php?mod=spacecp&ac=blog&op=delete&blogid=$blogid">
	<input type="hidden" name="referer" value="{echo dreferer()}" />
	<input type="hidden" name="deletesubmit" value="true" />
	<input type="hidden" name="formhash" value="{FORMHASH}" />
	<div class="Vja1dbTm53">{lang sure_delete_blog}?</div>
	<button type="submit" name="btnsubmit" value="true" class="kcQ3GJrc61"><strong>{lang determine}</strong></button>
</form>
</div>
<!--{/if}-->
<!--{template common/footer}-->