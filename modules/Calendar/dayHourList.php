	<!-- < div id="addEvent" class="caladdEvent" "> -->
<?
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new

$hour_format=$current_user->calendar_hour_format;
if($hour_format=="24")
{
}

?>
	
<div class="calDiv" >

<a name="12am">
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="calDayHour">
	<tr>
		<td width=10% class="calDayHourCell" valign=top align=right>12 am</td>
		<td width=90% valign=top>
			<table border=0 cellspacing=0 cellpadding=2 width=100%>
			<tr>
				<td onMouseOver="gshow('12am')"  onMouseOut="ghide('12am')"  valign=top style="border-left:1px dashed #dadada;height:50px" width=90%></td>
				<td onMouseOver="gshow('12am')"  onMouseOut="ghide('12am')"  width=10%><input type="button" value="Add Event" onClick="gshow('addEvent')" class="small" style="display:none" id='12am' ></td>
			</tr>
			</table>
		</td>
	</tr>
	</table><? 
	for ($i=1;$i <=11; $i++) {
?>
<a name="<?echo $i;?>am">
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="calDayHour">
	<tr>
		<td width=10% class="calDayHourCell" valign=top align=right><? echo $i; ?> am</td>
		<td width=90% valign=top>
			<table border=0 cellspacing=0 cellpadding=2 width=100%>
			<tr>
				<td onMouseOver="gshow('<?echo $i?>am')"  onMouseOut="ghide('<?echo $i?>am')"  valign=top style="border-left:1px dashed #dadada;height:50px" width=90%></td>
				<td onMouseOver="gshow('<?echo $i?>am')"  onMouseOut="ghide('<?echo $i?>am')"  width=10%><input type="button" value="Add Event" class="small" onClick="gshow('addEvent');" style="display:none" id=<? echo $i?>am ></td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
<? } ?>
<a name="12pm">
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="calDayHour">
	<tr>
		<td width=10% class="calDayHourCell" valign=top align=right>12 pm</td>
		<td width=90% valign=top>
			<table border=0 cellspacing=0 cellpadding=2 width=100%>
			<tr>
				<td onMouseOver="gshow('12pm')"  onMouseOut="ghide('12pm')"  valign=top style="border-left:1px dashed #dadada;height:50px" width=90%></td>
				<td onMouseOver="gshow('12pm')"  onMouseOut="ghide('12pm')"  width=10%><input type="button" value="Add Event" onClick="gshow('addEvent')" class="small" style="display:none" id='12pm' ></td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
<? 

	for ($i=1;$i <=11; $i++) {
?>
<a name="<?echo $i;?>pm">
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="calDayHour">
	<tr>
		<td width=10% class="calDayHourCell" valign=top align=right><? echo $i; ?> pm</td>
		<td width=90% valign=top>
			<table border=0 cellspacing=0 cellpadding=2 width=100%>
			<tr>
				<td onMouseOver="gshow('<?echo $i?>pm')"  onMouseOut="ghide('<?echo $i?>pm')"  valign=top style="border-left:1px dashed #dadada;height:50px" width=90%></td>
				<td onMouseOver="gshow('<?echo $i?>pm')"  onMouseOut="ghide('<?echo $i?>pm')"  width=10%><input type="button" value="Add Event" onClick="gshow('addEvent')" class="small" style="display:none" id='<? echo $i?>pm' ></td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
<? } ?>

</div>

<table border=0 cellspacing=0 cellpadding=2 width=95% bgcolor="#dadada">
<tr>
	<td align=center><b>Hour</b></td>
	<td>
		<table border=0 cellspacing=1 cellpadding=5 width=100% bgcolor="#dadada">
			<tr bgcolor=white>
			<td width=4% align=center><b>12am</b></td>
			<? for ($i=1;$i <10; $i++) { ?>
			<td width=4% align=center><?echo $i; ?> </td>
			<? } ?>	
			<? for ($i=10;$i <=11; $i++) { ?>
			<td class="calHourOccupied"  width=4% align=center><?echo $i; ?></td>
			<? } ?>	
			<td class="calHourOccupied"  width=4% align=center><b>12pm</b></td>
			<? for ($i=1;$i <3; $i++) { ?>
			<td class="calHourOccupied"  width=4% align=center><?echo $i; ?></td>
			<? } ?>	
			<? for ($i=3;$i <=11; $i++) { ?>
			<td width=4% align=center><?echo $i; ?> </td>
			<? } ?>	
			</tr>
			<!-- <tr>
				<td bgcolor=white colspan=11 align=center>AM</td>
				<td bgcolor=white colspan=12 align=center>PM</td>
				<td bgcolor=white align=center>AM</td>
			</tr> -->
		</table>
	</td>
</tr>
</table>

<table border=0 cellspacing=0 cellpadding=5 width=95%>
<tr>
	<td align=right>
		<table border=0 cellpadding=0 cellspacing=5 >
		<tr><td class="calHourOccupied" align=center style="width:20px;border:1px solid black"> &nbsp; &nbsp; </td><td>Activities</td></tr>
		</table>
	</td>
</tr>
</table>



<br>
