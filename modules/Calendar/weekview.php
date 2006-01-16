<!-- Contents -->

<?
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

?>

<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
<tr>
	<td valign=top><img src="<?echo $image_path ?>showPanelTopLeft.gif"></td>
	<td class="showPanelBg" valign=top width=100%>
		<!-- PUBLIC CONTENTS STARTS-->
		<div class="small" style="padding:20px">
		
		
		 <span class="lvtHeaderText">Calendar & Activities for 19 Dec 2005 to 26 Dec 2005, (Week 47)</span>  <br>
		 Today : 23 activities 
		 <hr noshade size=1>
		 <br> 
		
		<!-- Account details tabs -->
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=day">Day</a></td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td class="dvtSelectedCell" align=center nowrap>Week</td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=month">Month</a></td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td class="dvtTabCache" style="width:100%">&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign=top align=left >
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				<tr>
					<td align=left style="padding:5px">
					<!-- content cache -->


					<!-- day starts-->
					
					<!-- day calendar -->
					<table border=0 cellspacing=0 cellpadding=0 width=100%>
					<tr>
						<td>
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="calTopBg">
							<tr>
								<td><img src="<?echo $image_path ?>calTopLeft.gif"></td>
								<td><img src="<?echo $image_path ?>calNavPrev.gif" alt="Previous" title="Previous"></td>
								<td><img src="<?echo $image_path ?>calSep.gif"></td>
								<td align=center width=100% class="lvtHeaderText">
									
									Dec 19 - Dec 26 (Week 47)
										
								</td>
								<td><img src="<?echo $image_path ?>calSep.gif"></td>
								<td><img src="<?echo $image_path ?>calNavNext.gif" alt="Next" title="Next"></td>
								<td align=right><img src="<?echo $image_path ?>calTopRight.gif"></td>
							</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<!-- calendar list -->
							<table border=0 cellspacing=0 cellpadding=10 width=100% class="calDisplay">
							<tr>
								<td align=center >
								<!-- Add Event DIV starts-->
																<!-- Add Activity DIV stops-->
									<? include "addEventUI.php" ?>
									<? include 'weekDaysList.php'; ?>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="calBottomBg">
							<tr>
								<td><img src="<?echo $image_path ?>calBottomLeft.gif"></td>
								<td width=100%><img src="<?echo $image_path ?>calBottomBg.gif"></td>
								<td align=right><img src="<?echo $image_path ?>calBottomRght.gif"></td>
							</tr>
							</table>
						</td>
					</tr>
					</table>
					
					
					



					<!-- content cache -->
					</td>
				</tr>
				</table>
				
			</td>
		</tr>
		</table>
		
			
			
		
		</div>
		<!-- PUBLIC CONTENTS STOPS-->
	</td>
	<td align=right valign=top><img src="<?echo $image_path ?>showPanelTopRight.gif"></td>
</tr>
</table>
