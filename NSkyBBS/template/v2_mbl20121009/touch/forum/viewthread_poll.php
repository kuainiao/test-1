<?php exit;?>
<div id="postmessage_$post[pid]" class="IeEaWCrC27">$post[message]</div>

<div class="UiRqU4Xar9">
<form id="poll" name="poll" method="post" autocomplete="off" action="forum.php?mod=misc&action=votepoll&fid=$_G[fid]&tid=$_G[tid]&pollsubmit=yes{if $_GET[from]}&from=$_GET[from]{/if}&quickforward=yes&mobile=2" >
	<input type="hidden" name="formhash" value="{FORMHASH}" />
	<div>
		<!--{if $multiple}--><strong>{lang poll_multiple}{lang thread_poll}</strong><!--{if $maxchoices}-->: ( {lang poll_more_than} )<!--{/if}--><!--{else}--><strong>{lang poll_single}{lang thread_poll}</strong><!--{/if}--><!--{if $visiblepoll && $_G['group']['allowvote']}--> , {lang poll_after_result}<!--{/if}-->, {lang poll_voterscount}
	</div>

	<!--{if $_G[forum_thread][remaintime]}-->
	<p>
		{lang poll_count_down}:
		<span class="0GJVCDXl4J">
		<!--{if $_G[forum_thread][remaintime][0]}-->$_G[forum_thread][remaintime][0] {lang days}<!--{/if}-->
		<!--{if $_G[forum_thread][remaintime][1]}-->$_G[forum_thread][remaintime][1] {lang poll_hour}<!--{/if}-->
		$_G[forum_thread][remaintime][2] {lang poll_minute}
		</span>
	</p>
	<!--{elseif $expiration && $expirations < TIMESTAMP}-->
	<p><strong>{lang poll_end}</strong></p>
	<!--{/if}-->

	<div>
        <!--{loop $polloptions $key $option}-->
            <p>
            <!--{if $_G['group']['allowvote']}-->
                <input type="$optiontype" id="option_$key" name="pollanswers[]" value="$option[polloptionid]" {if $_G['forum_thread']['is_archived']}disabled="disabled"{/if}  />
            <!--{/if}-->
            <label for="option_$key">$key.$option[polloption]</label>
            <!--{if !$visiblepoll}-->
                $option[percent]% <em style="color:#$option[color]">($option[votes])</em>
            <!--{/if}-->
            </p>
        <!--{/loop}-->
        <!--{if $_G['group']['allowvote'] && !$_G['forum_thread']['is_archived']}-->
            <input type="submit" name="pollsubmit" id="pollsubmit" value="{lang submit}" />
            <!--{if $overt}-->
                <span class="gE08ICXz4M">({lang poll_msg_overt})</span>
            <!--{/if}-->
        <!--{elseif !$allwvoteusergroup}-->
            <!--{if !$_G['uid']}-->
            <span class="D2lReDdK6D">{lang poll_msg_allwvote_user}</span>
            <!--{else}-->
            <span class="D2lReDdK6D">{lang poll_msg_allwvoteusergroup}</span>
            <!--{/if}-->
        <!--{elseif !$allowvotepolled}-->
            <span class="D2lReDdK6D">{lang poll_msg_allowvotepolled}</span>
        <!--{elseif !$allowvotethread}-->
            <span class="D2lReDdK6D">{lang poll_msg_allowvotethread}</span>
        <!--{/if}-->
	</div>
</form>
</div>