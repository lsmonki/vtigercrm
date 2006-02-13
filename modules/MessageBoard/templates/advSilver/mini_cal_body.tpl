<!-- BEGIN switch_mini_cal_add_events -->
<form name="mini_cal" id ="mini_cal" action="{S_MINI_CAL_ACTION}" method="post">
<!-- END switch_mini_cal_add_events -->
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  <tr><td height="28">
          <!-- BEGIN switch_mini_cal_events -->
                <a href="{U_MINI_CAL_CALENDAR}">
            <!-- END switch_mini_cal_events -->
            <span style=" font-size: 11pt; color : 999999; line-height:150%"><b>{L_MINI_CAL_CALENDAR}</b></span>
            <!-- BEGIN switch_mini_cal_events -->
                </a>
            <!-- END switch_mini_cal_events -->
        </span>
      </td>
  </tr>
  <tr><td class="row1" align="center">
  	<table width="100%">
        <tr><td align="left" colspan="2">{U_PREV_MONTH}</td><td colspan="3" align="center"><span class="genmed">{L_MINI_CAL_MONTH}</span></td><td align="right" colspan="2">{U_NEXT_MONTH}</td></tr>
		<tr>
			<th align="center" width="14%">{L_MINI_CAL_SUN}</th>
			<th align="center" width="14%">{L_MINI_CAL_MON}</th>
			<th align="center" width="14%">{L_MINI_CAL_TUE}</th>
			<th align="center" width="14%">{L_MINI_CAL_WED}</th>
			<th align="center" width="14%">{L_MINI_CAL_THU}</th>
			<th align="center" width="14%">{L_MINI_CAL_FRI}</th>
			<th align="center" width="14%">{L_MINI_CAL_SAT}</th>
		</tr>
		<!-- BEGIN mini_cal_row -->
		<tr>
			<!-- BEGIN mini_cal_days -->
			<td class="row1" align="center"><span class="gensmall">{mini_cal_row.mini_cal_days.MINI_CAL_DAY}</span></td>
			<!-- END mini_cal_days -->
		</tr>
		<!-- END mini_cal_row -->
	</table>
  	</td>
  </tr>
  <!-- BEGIN switch_mini_cal_birthdays -->
  <tr><td class="row1" align="left"><span class="gensmall">{L_WHOSBIRTHDAY_TODAY}</span></td></tr>
  <tr><td class="row1" align="left"><span class="gensmall">{L_WHOSBIRTHDAY_WEEK}</span></td></tr>
  <!-- END switch_mini_cal_birthdays -->
  <!-- BEGIN switch_mini_cal_events -->
  <tr><td class="catHead" height="28"><span class="cattitle">{L_MINI_CAL_EVENTS}</span></td></tr>
  <!-- END switch_mini_cal_events -->
  <!-- BEGIN mini_cal_events -->
  <tr><td class="row1"><span class="gensmall">{mini_cal_events.MINI_CAL_EVENT_DATE} - <a href="{mini_cal_events.U_MINI_CAL_EVENT}" class="gensmall">{mini_cal_events.S_MINI_CAL_EVENT}</a></span></td></tr>
  <!-- END mini_cal_events -->
  <!-- BEGIN mini_cal_no_events -->
  <tr><td class="row1"><span class="genMed">{L_MINI_CAL_NO_EVENTS}</span></td></tr>
  <!-- END mini_cal_no_events -->
  <!-- BEGIN switch_mini_cal_add_events -->
  <tr><td class="row1" height="28" align="center">
        <span class="genmed">
            {S_MINI_CAL_EVENTS_FORUMS_LIST} <input type="submit" value="{L_MINI_CAL_ADD_EVENT}" class="liteoption" />
            <input type="Hidden" name="mode" id="mode" value="newtopic" />
        </span>
      </td>
  </tr>
  <!-- END switch_mini_cal_add_events -->
</table>
<!-- BEGIN switch_mini_cal_add_events -->
</form>
<!-- END switch_mini_cal_add_events -->
<br />
