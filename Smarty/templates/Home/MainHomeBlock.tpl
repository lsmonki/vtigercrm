	<div id="stuff_{$tablestuff.Stuffid}" class="MatrixLayer" style="float:left;overflow-x:hidden;">
	<table width="100%" cellpadding="0" cellspacing="0" class="small" style="padding-right:0px;padding-left:0px;padding-top:0px;">
		<tr id="headerrow_{$tablestuff.Stuffid}" class="headerrow">
			{if $MOD[$tablestuff.Stufftitle] neq ""}
				{assign var="stitle" value=$MOD[$tablestuff.Stufftitle]}
			{else}
				{assign var="stitle" value=$tablestuff.Stufftitle}
			{/if}
			<td align="left" class="homePageMatrixHdr" style="height:30px;" nowrap width=60%><b>&nbsp;{$stitle}</b></td>
			<td align="right" class="homePageMatrixHdr" style="height:30px;" width=5%>
				<span id="refresh_{$tablestuff.Stuffid}" style="position:relative;">&nbsp;&nbsp;</span>
		</td>
			<td align="right" class="homePageMatrixHdr" style="height:30px;" width=35% nowrap>
				{if ($tablestuff.Stufftype neq "Default" || $tablestuff.Stufftitle neq "Key Metrics") && ($tablestuff.Stufftype neq "Default" || $tablestuff.Stufftitle neq "Home Page Dashboard")}
				<a id="editlink" style='cursor:pointer;' onclick="showEditrow({$tablestuff.Stuffid},'{$tablestuff.Stufftype}')"><img src="{'windowSettings.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Edit" title="Edit" hspace="2" align="absmiddle"/></a>	
				{else}
					<img src="{'windowSettings-off.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Edit" title="Edit" hspace="2" align="absmiddle"/>
				{/if}

				{if $tablestuff.Stufftitle eq "Home Page Dashboard"}
					<a style='cursor:pointer;' onclick="fetch_homeDB({$tablestuff.Stuffid},'{$tablestuff.Stufftype}');"><img src="{'windowRefresh.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Refresh" title="Refresh" hspace="2" align="absmiddle"/></a>
				{else}
					<a style='cursor:pointer;' onclick="loadStuff({$tablestuff.Stuffid},'{$tablestuff.Stufftype}');"><img src="{'windowRefresh.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Refresh" title="Refresh" hspace="2" align="absmiddle"/></a>
				{/if}
				{if $tablestuff.Stufftype eq "Default"}
					<a style='cursor:pointer;' onclick="HideDefault({$tablestuff.Stuffid}),'{$tablestuff.Stufftype}'"><img src="{'windowMinMax.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Hide" title="Hide" hspace="5" align="absmiddle"/></a>
				{else}
					<img src="{'windowMinMax-off.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Hide" title="Hide" hspace="5" align="absmiddle"/>
				{/if}
				{if $tablestuff.Stufftype neq "Default"}
					<a id="deletelink" style='cursor:pointer;' onclick="DelStuff({$tablestuff.Stuffid})"><img src="{'windowClose.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Close" title="Close" hspace="5" align="absmiddle"/></a>
				{else}
					<img src="{'windowClose-off.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Close" title="Close" hspace="5" align="absmiddle"/>
				{/if}
			</td>
		</tr>
	</table>
		
	<table width="100%" cellpadding="0" cellspacing="0" class="small" style="padding-right:0px;padding-left:0px;padding-top:0px;">
	{if $tablestuff.Stufftype eq "Module"}	
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkModulesusr">
	{elseif $tablestuff.Stufftype eq "Default" && $tablestuff.Stufftitle neq "Home Page Dashboard"}	
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkModulesdef">
	{elseif $tablestuff.Stufftype eq "RSS"}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkRSS">
	{elseif $tablestuff.Stufftype eq "DashBoard"}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkDashboardusr">
	{elseif $tablestuff.Stufftype eq "Default" && $tablestuff.Stufftitle eq "Home Page Dashboard"}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkDashboarddef">
	{else}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab">
	{/if}	
			<td colspan="2">
				<div id="stuffcont_{$tablestuff.Stuffid}" style="height:260px; overflow-y: auto; overflow-x:hidden;width:100%;height:100%;"> 
				</div>
			</td>
		</tr>
		{if $tablestuff.Stufftype eq "Module" || ($tablestuff.Stufftype eq "Default" &&  $tablestuff.Stufftitle neq "Key Metrics" && $tablestuff.Stufftitle neq "Home Page Dashboard" && $tablestuff.Stufftitle neq "My Group Allocation" ) || $tablestuff.Stufftype eq "RSS" || $tablestuff.Stufftype eq "DashBoard"}
			<tr><td colspan=2 align=right><a href="#" id="a_{$tablestuff.Stuffid}">{$MOD.LBL_MORE}</a></td></tr>	
		{/if}
	</table>
</div>
<script language="javascript">
	window.onresize = function(){ldelim}positionDivInAccord('stuff_{$tablestuff.Stuffid}','{$tablestuff.Stufftitle}');{rdelim};
	positionDivInAccord('stuff_{$tablestuff.Stuffid}','{$tablestuff.Stufftitle}');
</script>	
