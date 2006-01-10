	<!-- < div id="addEvent" class="caladdEvent" "> -->

<div class="calDiv" >

<table border=0 cellspacing=1 cellpadding=5 width=100% class="calDayHour" style="background-color: #dadada">
<!-- init week header -->
<?
$week[1]="Sun ";
$week[2]="Mon";
$week[3]="Tue ";
$week[4]="Wed ";
$week[5]="Thu ";
$week[6]="Fri ";
$week[7]="Sat ";
?>


<!-- start generator -->
<!-- day name headers -->
<? // Generates the first row, which is the name of the days like sunday monday etc ?>
<? for ($row=1;$row<=1;$row++) { ?>
	<tr>
		<?
		for ($col=1;$col<=7;$col++)
		{
			?>
				<td width=12% class="lvtCol" bgcolor="blue" valign=top> <? echo $week[$col]; ?></td>
				<? }  ?>
			</tr>
			<? }  ?>
</div>
	<table border=0 cellspacing=1 cellpadding=5 width=100% class="calDayHour" style="background-color: #dadada">
	<!-- init week header -->
		
<!-- hour generator -->
<? // Generates the rest of the table, starting from 1pm.. ?>
<?$date=28;
for ($row=1;$row<=6;$row++) {
if($date>31)
$currentmodule=1;
?>
<tr>
<?
for ($col=1;$col<=7;$col++)
{
	?>
	<? if ($col==2 & $row== 5 ) { ?>
	<td class="cellScheduled" valign=top onMouseOver="this.className='cellSchHover'" onMouseOut="this.className='cellScheduled'">
<?if($date>31)$date=1;  echo $date;?><div valign=bottom align=right >Schedules..</div>
	</td>
	<? }  else { // if closes ?>
<td onMouseOver="this.className='cellNormalHover'" onMouseOut="this.className='cellNormal'" bgcolor="white" style="height:40px" width=12% valign=top> <? if($date>31)$date=1; echo $date; $date++; ?><div valign=bottom align=right onclick="gshow('addEvent')"  onMouseOut="ghide('12pm')"  width=10%>
            +
			</div></td>

			<?}  } ?>
			</tr>
<? } ?>
	<!-- stop generator -->
	</table>
		
		
	
		


