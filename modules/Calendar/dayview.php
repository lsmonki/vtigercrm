<!-- Contents -->
<?
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

if(isset($_REQUEST['display_date']) && $_REQUEST['display_date'] !="")
{
	$display_date=$_REQUEST['display_date'];
}
else
{
        $display_date= date("Y-m-d",mktime(0,0,0,date("n"),(date("j")),date("Y")));
}

$date_val=explode("-",$display_date);	

$year=$date_val[0];
$month=$date_val[1];
$day=$date_val[2];


$month_in_text=date("F", mktime(0, 0, 0, $month, $day, $year));
$day_in_words=date("l", mktime(0, 0, 0, $month, $day, $year));
?>
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
<tr>
	<!--td valign=top><img src=$image_path/showPanelTopLeft.gif></td -->
	<td valign=top><img src="<?echo $image_path ?>showPanelTopLeft.gif"</td>
	<td class="showPanelBg" valign=top width=100%>
		<!-- PUBLIC CONTENTS STARTS-->
		<div class="small" style="padding:20px">
		
		
		 <!--span class="lvtHeaderText">Calendar & Activities for 20 December 2005</span>  <br-->
		 <span class="lvtHeaderText">Calendar & Activities for <?echo "$day $month_in_text $year"; ?></span>  <br>
		<!-- Today : 3 activities -->
		 <hr noshade size=1>
		 <br> 
		
		<!-- calendar tabs -->
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					<td class="dvtSelectedCell" align=center nowrap>Day</td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=week">Week</a></td>
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
					<!-- <table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr>
						<td width=100% align=right>[ <a href="#">Select another day</a> ]</td>
					</tr>
					</table> -->
					
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
									<!-- <table border=0 cellspacing=0 cellpadding2 width=1>
									<tr>
										<td><select class=lvtHeaderText><option>20</option><option>21</option><option>22</option></select></td>
										<td><select class=lvtHeaderText><option>November</option><option>December</option></select></td>
										<td><select class=lvtHeaderText><option>2004</option><option>2005</option><option>2006</option></select></td>
									</tr>
									</table> -->
									
									<!--Day 20, Friday-->
									Day <? echo "$day, $day_in_words ";?>
										
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
									<? include "addEventUI.php" ?>

									<? include 'dayHourList.php'; ?>
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
								<td align=right><img src="<?echo $image_path ?>calBottomRight.gif"></td>
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
	<td align=right valign=top><img src="../images/showPanelTopRight.gif"></td>
</tr>
</table>
