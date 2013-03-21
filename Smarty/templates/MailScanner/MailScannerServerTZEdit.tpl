{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
  * ("License"); You may not use this file except in compliance with the License
  * The Original Code is:  vtiger CRM Open Source
  * The Initial Developer of the Original Code is vtiger.
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.
  *
 ********************************************************************************/
-->*}

<select name="mailboxinfo_timezone">
	<option value="" {if !$SELECTEDTZ}selected="true"{/if}>{'LBL_I_DONT_KNOW'|@getTranslatedString:$MODULE}</option>
	<option value="-12:00" {if $SELECTEDTZ == "-12:00"}selected="true"{/if}>(GMT -12:00 hours) Eniwetok, Kwajalein</option>
	<option value="-11:00" {if $SELECTEDTZ == "-11:00"}selected="true"{/if}>(GMT -11:00 hours) Midway Island, Samoa</option>
	<option value="-10:00" {if $SELECTEDTZ == "-10:00"}selected="true"{/if}>(GMT -10:00 hours) Hawaii</option>
	<option value="-9:00" {if $SELECTEDTZ == "-9:00"}selected="true"{/if}>(GMT -9:00 hours) Alaska</option>
	<option value="-8:00" {if $SELECTEDTZ == "-8:00"}selected="true"{/if}>(GMT -8:00 hours) Pacific Time (US & Canada)</option>
	<option value="-7:00" {if $SELECTEDTZ == "-7:00"}selected="true"{/if}>(GMT -7:00 hours) Mountain Time (US & Canada)</option>
	<option value="-6:00" {if $SELECTEDTZ == "-6:00"}selected="true"{/if}>(GMT -6:00 hours) Central Time (US & Canada), Mexico City</option>
	<option value="-5:00" {if $SELECTEDTZ == "-5:00"}selected="true"{/if}>(GMT -5:00 hours) Eastern Time (US & Canada), Bogota, Lima, Quito</option>
	<option value="-4:00" {if $SELECTEDTZ == "-4:00"}selected="true"{/if}>(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz</option>
	<option value="-3:30" {if $SELECTEDTZ == "-3:30"}selected="true"{/if}>(GMT -3:30 hours) Newfoundland</option>
	<option value="-3:00" {if $SELECTEDTZ == "-3:00"}selected="true"{/if}>(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown</option>
	<option value="-2:00" {if $SELECTEDTZ == "-2:00"}selected="true"{/if}>(GMT -2:00 hours) Mid-Atlantic</option>
	<option value="-1:00" {if $SELECTEDTZ == "-1:00"}selected="true"{/if}>(GMT -1:00 hours) Azores, Cape Verde Islands</option>
	<option value="0:00" {if $SELECTEDTZ == "0:00"}selected="true"{/if}>(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia</option>
	<option value="+1:00" {if $SELECTEDTZ == "+1:00"}selected="true"{/if}>(GMT +1:00 hours) CET(Central Europe Time), Brussels, Copenhagen, Madrid, Paris</option>
	<option value="+2:00" {if $SELECTEDTZ == "+2:00"}selected="true"{/if}>(GMT +2:00 hours) EET(Eastern Europe Time), Kaliningrad, South Africa</option>
	<option value="+3:00" {if $SELECTEDTZ == "+3:00"}selected="true"{/if}>(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg, Volgograd, Nairobi</option>
	<option value="+3:30" {if $SELECTEDTZ == "+3:30"}selected="true"{/if}>(GMT +3:30 hours) Tehran</option>
	<option value="+4:00" {if $SELECTEDTZ == "+4:00"}selected="true"{/if}>(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi</option>
	<option value="+4:30" {if $SELECTEDTZ == "+4:30"}selected="true"{/if}>(GMT +4:30 hours) Kabul</option>
	<option value="+5:00" {if $SELECTEDTZ == "+5:00"}selected="true"{/if}>(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
	<option value="+5:30" {if $SELECTEDTZ == "+5:30"}selected="true"{/if}>(GMT +5:30 hours) Bombay, Calcutta, Madras, New Delhi</option>
	<option value="+6:00" {if $SELECTEDTZ == "+6:00"}selected="true"{/if}>(GMT +6:00 hours) Almaty, Dhaka, Colombo</option>
	<option value="+7:00" {if $SELECTEDTZ == "+7:00"}selected="true"{/if}>(GMT +7:00 hours) Bangkok, Hanoi, Jakarta</option>
	<option value="+8:00" {if $SELECTEDTZ == "+8:00"}selected="true"{/if}>(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong, Chongqing, Urumqi, Taipei</option>
	<option value="+9:00" {if $SELECTEDTZ == "+9:00"}selected="true"{/if}>(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
	<option value="+9:30" {if $SELECTEDTZ == "+9:30"}selected="true"{/if}>(GMT +9:30 hours) Adelaide, Darwin</option>
	<option value="+10:00" {if $SELECTEDTZ == "+10:00"}selected="true"{/if}>(GMT +10:00 hours) EAST(East Australian Standard), Guam, Papua New Guinea, Vladivostok</option>
	<option value="+11:00" {if $SELECTEDTZ == "+11:00"}selected="true"{/if}>(GMT +11:00 hours) Magadan, Solomon Islands, New Caledonia</option>
	<option value="+12:00" {if $SELECTEDTZ == "+12:00"}selected="true"{/if}>(GMT +12:00 hours) Auckland, Wellington, Fiji, Kamchatka, Marshall Island</option>
</select>