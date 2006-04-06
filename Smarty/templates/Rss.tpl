
<script language="JavaScript" type="text/javascript" src="modules/Rss/Rss.js"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
	<td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
	<span class="lvtHeaderText">Tools &gt; Rss </span>
	<hr noshade="noshade" size="1" />
	</td>

	<td width="5%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
	<td width="95%" style="padding-left:20px;" valign="top">
	<!-- module Select Table -->
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="7" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGEPATH}top_left.jpg" align="top"  /></td>
		<td bgcolor="#EBEBEB" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;height:6px;"></td>

		<td width="8" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGEPATH}top_right.jpg" width="8" height="6" align="top" /></td>
		</tr>
		<tr>
		<td bgcolor="#EBEBEB" width="7"></td>
		<td bgcolor="#ECECEC" style="padding-left:10px;height:20px;vertical-align:middle;">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td style="padding:10px;vertical-align:middle;" width="28%">{$RSSFEEDS_TITLE}</td>
			<td width="2%">&nbsp;</td>
		
			<td width="60%"><img src="{$IMAGEPATH}rssimage.gif" width="176" height="44"  align="right"/></td>
			</tr>
			<tr>
				<td bgcolor="#949494">&nbsp;</td>
				<td>&nbsp;</td>
				<td class="subHdr"><b>Feeds list from : {$TITLE}</b>
				</td>
			</tr>
	
			<tr>
			<td rowspan="2" valign="top" bgcolor="#FFFFFF">{$RSSFEEDS}</td>
			<td>&nbsp;</td>
			<td	class="delBg"><input type="button" name="delete" value=" Delete " class="classBtn" /></td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td style="padding:1px;" align="left">
			<div id="rssScroll">

				<table class="rssTable" cellspacing="0" cellpadding="0">
				<tr><td colspan="4">{$RSSDETAILS}</td></tr>	
				</table>
			</div>
			</td>
			</tr>
			
			<tr>		
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td height="5"></td>
			</tr>
			
			<tr>
			<td colspan="3" class="frameHdr" id="rsstitle">&nbsp;</td>
			</tr>
			<tr>
			<td colspan="3" class="forwardBg">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td width="10%">Forward to </td>
				<td width="50%"><input type="text" name="textfield"  class="detailedViewTextBox"/></td>
				<td width="5%"><input type="button" name="Button" value=" Send Now "  class="classBtn"/></td>
				<td width="35%">&nbsp;</td>
				</tr>
				</table>

			</td>
			</tr>
			<tr>
			<td colspan="3">
			<iframe width="100%" height="300" frameborder="0" id="mysite" scrolling="auto" marginheight="0" marginwidth="0" style="background-color:#FFFFFF;"></iframe>
			</td>
			</tr>
			</table>
	
		</td>
		<td bgcolor="#EBEBEB" width="8"></td>
		</tr>
		<tr>
		<td width="7" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGEPATH}bottom_left.jpg" align="bottom"  /></td>
		<td bgcolor="#ECECEC" height="8" style="font-size:1px;" ></td>
		<td width="8" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGEPATH}bottom_right.jpg" align="bottom" /></td>
		</tr>
		</table><br />
	
	</td>
	<td>&nbsp;</td>
	</tr>
</table>
