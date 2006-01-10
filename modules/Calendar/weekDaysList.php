	<!-- < div id="addEvent" class="caladdEvent" "> -->

<div align=left style="padding:5px;width:95%">
	Time filter : <select class=small>
	<option>Select ...</option>
	<option> - Work hours (8am - 8pm)</option>
	<option> - Early morning to Noon (12am - 12pm)</option>
	<option> - Noon to Midnight (12pm - 12am)</option>
	<option> - Full day (24 Hours)</option>
	<option> - Custom time...</option>
	</select>
</div>
<div class="calDiv" >

	<table border=0 cellspacing=1 cellpadding=5 width=100% class="calDayHour" style="background-color: #dadada">
	<!-- init week header -->
	<? 
	$week[0]="";
	$week[1]="19 - Sun ";
	$week[2]="20 - Mon";
	$week[3]="21 - Tue ";
	$week[4]="22 - Wed ";
	$week[5]="23 - Thu ";
	$week[6]="24 - Fri ";
	$week[7]="25 - Sat "; 
	?>
	
	
	<!-- start generator -->
	<!-- day name headers -->
	<? // Generates the first row, which is the name of the days like sunday monday etc ?>
	<? for ($row=1;$row<=1;$row++) { ?>
		<tr>
		<?
			for ($col=0;$col<=7;$col++)	
			{ 
		?>
		<td width=12% class="lvtCol" bgcolor="blue" valign=top> <? echo $week[$col]; ?></td>
		<? }  ?>
		</tr>
	<? }  ?>
	
	<!-- hour generator -->
	<? // Generates the rest of the table, starting from 1pm.. ?>
	<? for ($row=1;$row<=24;$row++) { ?>
		<tr>
		<?
			for ($col=0;$col<=7;$col++)	
			{ 
		?>
		<? if ($col==0) { ?> 
			<td  style="background-color:#eaeaea; border-top:1px solid #efefef;height:40px" width=12% valign=top>
			<? echo $row,"pm"; } else {?>  </b>
			<? if ($col==2 & $row>= 5 & $row<=8) { ?>
				<td class="cellScheduled" valign=top onMouseOver="this.className='cellSchHover'" onMouseOut="this.className='cellScheduled'">
					Schedules..<div valign=bottom align=right onclick="gshow('addEvent')"  width=10%>
					            +</div>
				</td>
			<? }  else { // if closes ?>
			
			<td onMouseOver="this.className='cellNormalHover'" onMouseOut="this.className='cellNormal'" bgcolor="white" style="height:40px" width=12% valign=top> <?  echo "-";  ?><div valign=bottom align=right onclick="gshow('addEvent')"  width=10% class="small" id="<? echo $row ;?>pm">
			+</div></td>
			
		<?}  } } ?>
		</tr>
	<? }  ?>
		
	

	<!-- stop generator -->
	</table>
		
		
	
		


