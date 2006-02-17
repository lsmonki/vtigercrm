
<script type="text/javascript" language="JavaScript" src="smiletag/smiletag-script.js"></script>


{*<!--Home Page Entries  -->*}

<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
 	<tr><td style="height:2px"></td></tr>
 	<tr>
     <td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>My Home </td>
     <td class="sep1" style="width:1px"></td>
     <td class=small >
     	<table border=0 cellspacing=0 cellpadding=0>
  		<tr>
        <td>
    		<table border=0 cellspacing=0 cellpadding=5>
			<tr>
			<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Search.gif" alt="Search in Accounts..." title="Search in Accounts..." border=0></a></a></td>
			</tr>
			</table>
		</td>
		<td nowrap width=50>&nbsp;</td>
		<td>
			<table border=0 cellspacing=0 cellpadding=5>
			<tr>
			<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
            <td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0></a></a></td>
            <td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0></a></a></td>
			</tr>
			</table>
		</td>
		<td>
			<table border=0 cellspacing=0 cellpadding=5>
			<tr>
			</tr>
			</table>
		</td>
		</tr>
		</table>
	</td>
	<td nowrap style="width:50%;padding:10px">&nbsp;</td>
   </tr>
   <tr><td style="height:2px"></td></tr>

</TABLE>


<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
	<tr>
	<td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
	<td class="showPanelBg" valign=top width=100%>
		<div align=center class=small>
		<table border=0 cellspacing=0 cellpadding=10 width=100%>
		<tr>
		<td align=left valign=top width=70%>
		<br>	
		<!-- block for each table !-->
		{foreach key=table item=tabledetail from=$HOMEDETAILS}
		{if $tabledetail ne ''}
		<script language='Javascript'>
		        var leftpanelistarray=new Array('{$tabledetail.Title[2]}');
				  setExpandCollapse_gen()</script>
		<table class="tblPro1" border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody>
		<tr>
			<td>
			{if $tabledetail.Title[3] ne ''}	
			<script>
			function {$tabledetail.Title[4]}(selectactivity_view)
			{ldelim}
			//script to reload the page with the view type when the combo values are changed
			View_name = selectactivity_view.options[selectactivity_view.options.selectedIndex].value;
			document.{$tabledetail.Title[5]}.action = "index.php?module=Home&action=index&{$tabledetail.Title[6]}="+View_name;
			document.{$tabledetail.Title[5]}.submit();
			{rdelim}
			</script>
			<form name="{$tabledetail.Title[5]}" method="post">
			{/if}
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody>
				<tr>
				<!--title part-->
				<td class="tblPro1IconCell" align="center" width="50"><img src="{$IMAGE_PATH}{$tabledetail.Title[0]}"></td>
				<td class="tblPro1HeadingCell" width="80%">{$tabledetail.Title[1]}</td>
				<td>{$tabledetail.Title[3]}</td>
				<td valign="top" width="24"><img src="{$IMAGE_PATH}tblPro1BtnHide.gif" alt="Minimize / Maximize" border="0" onclick="javascript:expandCont('{$tabledetail.Title[2]}');"></td>
				<!--end of title part -->	
				</tr>
			</tbody>
			</table>
			</td>
		</tr>
		<tr>
			<td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">
			<div id="{$tabledetail.Title[2]}" style="display: block;" class="divEventToDo">
			<table class="EventToDo" border="0" cellpadding="3" cellspacing="0" width="100%">
			<tbody>
				<tr>
				<!--header part-->
				{foreach key=header item=headerdetail from=$tabledetail.Header}	
				<td class="tblPro1ColHeader">&nbsp;{$headerdetail}</td>
				{/foreach}
				<!--end of header Part-->	
				</tr>
				<!--row of entries	-->
				{foreach key=row item=detail from=$tabledetail.Entries}
				<tr>
				<!--entries-->
				{foreach key=label item=entries from=$detail}
				<td class="tblPro1DataCell">{$entries}</td>
				{/foreach}
				<!--end of entries -->
				</tr>
				{/foreach}
				<!--end of rows entries-->
			</tbody></table>
			</div>
			</td>
		</tr>
		{if $tabledetail.Title[3] ne ''}
		</form>
		{/if}
		</tbody></table>
		<br><br>
		{/if}
		{/foreach}
		<!--end of block for each table !-->		
		<br>	
		</td>
		<td align=left valign=top width=30%>
		Pipeline chart comes here..<br><br>
		{if $TAGCLOUD_JS ne ''}
	            <link href="{$TAGCLOUD_CSS}" rel="stylesheet" type="text/css">
        	    <script language="JavaScript"  type="text/javascript" src="{$TAGCLOUD_JS}"></script>
        	{/if}
        	<br>
		<table border="0" cellpadding="0" cellspacing="0">
     		<tr><b>YOUR SHOUT!!!</b>
          		<td valign="top" >
      	  			<iframe name="iframetag" marginwidth="0" marginheight="0" src="smiletag/view.php" width="190" height="300">
			Your Browser must support IFRAME to view this page correctly
		  </iframe>
		  </td>
     		</tr>
     	<tr>
          <td>
  			<form name="smiletagform" method="post" action="smiletag/post.php" target="iframetag"><br />
        		      Name<br /><input type="text" name="name"/><br />
              			URL or Email<br /><input type="text" name="mail_or_url" value="http://" /><br />
              Message  
<div id="smiley_box">
	<div class="smiley_top"><br /><a href="javascript:hideSmileyWindow();"><img src="smiletag/images/delete_icon.gif" alt="Close" /></a></div>
	<div class="smiley_middle">
		<div id="smiley_box_content" style="padding: 0px;">
			<table width="80%" border="0" cellspacing="5" cellpadding="0">
			  <tr>
				<td align="center"><a href="javascript:insertSmiley(':)');"><img src="smiletag/images/smile.gif" alt=":)" /></a></td>
				<td align="center"><a href="javascript:insertSmiley(':D');"><img src="smiletag/images/laugh.gif" alt=":D" /></a></td>
				<td align="center"><a href="javascript:insertSmiley(':(');"><img src="smiletag/images/sad.gif" alt=":(" /></a></td>
			  </tr>
			  <tr>
				<td align="center"><a href="javascript:insertSmiley(':o');"><img src="smiletag/images/shock.gif" alt=":o" /></a></td>
				<td align="center"><a href="javascript:insertSmiley(':p');"><img src="smiletag/images/tongue.gif" alt=":p" /></a></td>
				<td align="center"><a href="javascript:insertSmiley(';)');"><img src="smiletag/images/wink.gif" alt=";)" /></a></td>
			  </tr>
			  <tr>
				<td align="center"><a href="javascript:insertSmiley(':|');"><img src="smiletag/images/blah.gif" alt=":|" /></a></td>
				<td align="center"><a href="javascript:insertSmiley('x(');"><img src="smiletag/images/mad.gif" alt="x(" /></a></td>
				<td align="center"><a href="javascript:insertSmiley(':~');"><img src="smiletag/images/drool.gif" alt=":~" /></a></td>
			  </tr>
</table>
		</div>
	</div>
	<div class="smiley_bottom"></div>
</div>
  <a href="http://www.smiletag.com/" onclick="showSmileyWindow(event);return false">(Smilies)</a>
			<br /><textarea name="message_box" rows="3" cols="20"></textarea><br />
              <input type="hidden" name="message" value="" />
              <input type="submit" name="submit" value="Post!!" onclick="clearMessage()" /> 
			  <input type="reset"  name="reset" value="Reset" /><br />
            </form>
	       </td>
        </tr>
</table>
		</td>
		</tr>
		</table>
		</div>
	</td>
	<td valign=top><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
	</tr>
</table>
