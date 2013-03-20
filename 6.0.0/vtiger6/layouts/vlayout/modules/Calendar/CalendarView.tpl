{strip}
{literal}<style type="text/css">

</style>{/literal}
<input type="hidden" id="currentView" value="{$smarty.request.view}" />
<input type="hidden" id="activity_view" value="{$CURRENT_USER->get('activity_view')}" />
<input type="hidden" id="time_format" value="{$CURRENT_USER->get('hour_format')}" />
<input type="hidden" id="start_hour" value="{$CURRENT_USER->get('start_hour')}" />
<input type="hidden" id="start_day" value="{$CURRENT_USER->get('dayoftheweek')}" />
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<p><!-- Divider --></p>
				<div id="calendarview"></div>
			</div>
		</div>
	</div>
{/strip}