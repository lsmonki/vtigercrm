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


<!-- Tag cloud display -->
<table border=0 cellspacing=0 cellpadding=0 width=100% class="rightMailMerge">
   <tr>
	<td class="rightMailMergeHeader"><b>{$APP.LBL_TAG_CLOUD}</b></td>
   </tr>
   <tr style="height:25px">
	<td class="rightMailMergeContent">
		<table border=0 cellspacing=0 cellpadding=0 width=100% >
		   <tr>
			<td>
          			<table width="250" border="0" cellspacing="0" cellpadding="0">
				   <tr>
					<td colspan="3">
						<img src="{$IMAGE_PATH}cloud_top.gif" width=250 height=38 alt="">
					</td>
				   </tr>
				   <tr>
					<td width="16" height="10">
						<img src="{$IMAGE_PATH}cloud_top_left.gif" width="16" height="10">
					</td>
					<td width="221" height="10">
						<img src="{$IMAGE_PATH}tagcloud_03.gif" width="221" height="10">
					</td>
					<td width="13" height="10">
						<img src="{$IMAGE_PATH}cloud_top_right.gif" width="13" height="10">
					</td>
				   </tr>
				   <tr>
					<td class="cloudLft"></td>
					<td><span id="tagfields"></span></td>
					<td class="cloudRht"></td>
				   </tr>
				   <tr>
					<td width="16" height="13">
						<img src="{$IMAGE_PATH}cloud_btm_left.gif" width="16" height="13">
					</td>
					<td width="221" height="13">
						<img src="{$IMAGE_PATH}cloud_btm_bdr.gif" width="221" height="13">
					</td>
					<td width="13" height="13">
						<img src="{$IMAGE_PATH}cloud_btm_right.gif" width="13" height="13">
					</td>
				   </tr>
				</table>
			</td>
		   </tr>
		</table>
	</td>
   </tr>
</table>


