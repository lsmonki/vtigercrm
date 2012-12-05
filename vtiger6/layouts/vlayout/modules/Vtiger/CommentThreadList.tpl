{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
<hr>
<div class="commentDiv cursorPointer">
	<div class="singleComment">
		<div class="commentInfoHeader row-fluid"  data-commentid="{$COMMENT->getId()}">
			<div class="commentTitle" id="{$COMMENT->getId()}">
				{assign var=PARENT_COMMENT_MODEL value=$COMMENT->getParentCommentModel()}
				{assign var=CHILD_COMMENTS_MODEL value=$COMMENT->getChildComments()}
				<div class="row-fluid">
					<div class="span1">
						{assign var=IMAGE_PATH value=$COMMENT->getImagePath()}
						<img class="alignMiddle pull-left" src="{if !empty($IMAGE_PATH)}{$COMMENT->getImagePath()}{else}{vimage_path('DefaultUserIcon.png')}{/if}">
					</div>
					<div class="span11 commentorInfo">
						{assign var=COMMENTOR value=$COMMENT->getCommentedByModel()}
						<div class="inner">
							<span class="commentorName pull-left"><strong>{$COMMENTOR->getName()}</strong></span>
							<span class="pull-right">
								<p class="muted"><small>{Vtiger_Util_Helper::formatDateDiffInStrings($COMMENT->getCommentedTime())}</small></p>
							</span>
							<div class="clearfix"></div>
						</div>
						<div class="commentInfoContent">
							{nl2br($COMMENT->get('commentcontent'))}
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid commentActionsDiv">
			<span class="pull-right commentActions">
				{assign var=CHILD_COMMENTS_COUNT value=$COMMENT->getChildCommentsCount()}
				<span>
					<a class="cursorPointer replyComment">
						<i class="icon-share-alt"></i>
						{vtranslate('LBL_REPLY',$MODULE_NAME)}
						{if	$CHILD_COMMENTS_COUNT gt 0}
							&nbsp;<span>|</span>&nbsp;
						{/if}
					</a>
				</span>
				{if $CHILD_COMMENTS_COUNT neq 0}
					<span class="hide viewThreadBlock">
							<a class="cursorPointer viewThread">
								{$COMMENT->getChildCommentsCount()}&nbsp;{if $CHILD_COMMENTS_COUNT eq 1}{vtranslate('LBL_REPLY',$MODULE_NAME)}{else}{vtranslate('LBL_REPLIES',$MODULE_NAME)}{/if}
								<img class="alignMiddle" src="{vimage_path('rightArrowSmall.png')}" />
							</a>
					</span>
					<span class="hideThreadBlock">
						<a class="cursorPointer hideThread">
							{$COMMENT->getChildCommentsCount()}&nbsp;{if $CHILD_COMMENTS_COUNT eq 1}{vtranslate('LBL_REPLY',$MODULE_NAME)}{else}{vtranslate('LBL_REPLIES',$MODULE_NAME)}{/if}
							<img class="alignMiddle" src="{vimage_path('downArrowSmall.png')}" />
						</a>
					</span>
				{/if}
			</span>

		</div>
	</div>
</div>