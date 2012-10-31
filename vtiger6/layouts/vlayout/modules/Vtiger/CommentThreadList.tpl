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

<div class="commentDiv cursorPointer">
	<div class="singleComment">
		<div class="commentInfoHeader row-fluid"  data-commentid="{$COMMENT->getId()}">
			<div class="commentTitle" id="{$COMMENT->getId()}">
				{assign var=PARENT_COMMENT_MODEL value=$COMMENT->getParentCommentModel()}
				{assign var=CHILD_COMMENTS_MODEL value=$COMMENT->getChildComments()}
				<div>
					<span>
						{assign var=IMAGE_PATH value=$COMMENT->getImagePath()}
						<img class="alignMiddle pull-left" title="{vtranslate('LBL_USER_IMAGE')}" alt="{vtranslate('LBL_USER_IMAGE')}" src="{if !empty($IMAGE_PATH)}{$COMMENT->getImagePath()}{else}{vimage_path('Default_Image.png')}{/if}" style="width: 35px; height: 44px;">
					</span>
					<span class="commentorInfo">
						{assign var=COMMENTOR value=$COMMENT->getCommentedByModel()}
						<span class="commentorName pull-left"><strong>{$COMMENTOR->getName()}</strong></span>
						<span class="pull-right">
							{$COMMENT->getCommentedTime()}
						</span>
					</span>
				</div>
			</div>
		</div>
		<div class="commentInfoContent">
			{$COMMENT->get('commentcontent')}
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