<?php
	require_once('checkSession.php');
	require_once('../lib/domit/xml_domit_lite_include.php');
	require_once('../lib/St_XmlParser.class.php');	
	require_once('../lib/St_ConfigManager.class.php');

	//load smilies list
	$configManager 	    =& new St_ConfigManager();
	$maxInputLength 	= $configManager->getMaxInputLength();
	$customText			= $configManager->getCustomTextPair();
	
	$fileName = $configManager->getDataDir().'/smiletag-config.xml';
	if(!is_writable($fileName)){
			$LOCAL_MESSAGE = "File $fileName is not writable! Please change the permission.";
	}		
	/*********************************************************************/
	require_once('localMessage.php');
?>	
	<form action="adminProcess.php" method="post">
		   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
			  <tr class="odd">
				<th colspan="4" align="center" scope="col">Board Control </th>
				</tr>
			  <tr>
				<td width="18%" align="center"><strong>Enable Board</strong></td>
				<td width="64%"> If no, the shoutbox will be locked. Nobody can post in locked mode. </td>
				<td width="9%" align="center"><input name="enable_board" type="radio" value="true" <?php if($configManager->isBoardEnabled()) echo 'checked'; ?>>
				  Yes</td>
			    <td width="9%" align="center"><input name="enable_board" type="radio" value="false" <?php if(!$configManager->isBoardEnabled()) echo 'checked'; ?>>
			      No</td>
			    </tr>
			  <tr class="odd">
			    <td align="center"><strong>Enable  Moderation</strong></td>
				<td>If yes,  all messages will need your moderation before they appeared in the shoutbox. </td>
				<td align="center"><input name="enable_moderation" type="radio" value="true" <?php if($configManager->isModerationEnabled()) echo 'checked'; ?>>
				  Yes</td>
				<td align="center"><input name="enable_moderation" type="radio" value="false" <?php if(!$configManager->isModerationEnabled()) echo 'checked'; ?>>
				  No</td>
				</tr>
			  <tr>
			    <td align="center"><strong>Enable Smiley</strong></td>
				<td>If no,  smiley code will not translated into smiley image. </td>
				<td align="center"><input name="enable_smiley" type="radio" value="true" <?php if($configManager->isSmileyEnabled()) echo 'checked'; ?>>
				  Yes</td>
				<td align="center"><input name="enable_smiley" type="radio" value="false" <?php if(!$configManager->isSmileyEnabled()) echo 'checked'; ?>>
				  No</td>
			  </tr>
			  <tr class="odd">
			    <td align="center"><strong>Enable Bad Words Filter</strong></td>
			    <td>If no, bad words filtering will be disabled. </td>
			    <td align="center"><input name="enable_badword_filter" type="radio" value="true" <?php if($configManager->isBadwordFilterEnabled()) echo 'checked'; ?>>
			      Yes</td>
			    <td align="center"><input name="enable_badword_filter" type="radio" value="false" <?php if(!$configManager->isBadwordFilterEnabled()) echo 'checked'; ?>>
			      No</td>
			  </tr>
			  <tr>
			    <td align="center"><strong>Enable BBCode</strong></td>
			    <td>If no, BBCode formatting tag for bold [b]...[/b],italic [i]...[/i] and underline [u]...[/u] will be disabled. </td>
			    <td align="center"><input name="enable_formatting" type="radio" value="true" <?php if($configManager->isFormattingEnabled()) echo 'checked'; ?>>
			      Yes</td>
			    <td align="center"><input name="enable_formatting" type="radio" value="false" <?php if(!$configManager->isFormattingEnabled()) echo 'checked'; ?>>
			      No</td>
			  </tr>
			  <tr class="odd">
			    <td align="center"><strong>Enable URL Parsing</strong> </td>
			    <td>Automatically convert any URL found in the message into   link. If no, this feature will be disabled. </td>
			    <td align="center"><input name="parse_url_inside_message" type="radio" value="true" <?php if($configManager->isParseMessageUrlEnabled()) echo 'checked'; ?>>
			      Yes</td>
			    <td align="center"><input name="parse_url_inside_message" type="radio" value="false" <?php if(!$configManager->isParseMessageUrlEnabled()) echo 'checked'; ?>>
			      No</td>
			  </tr>
			  <tr>
			    <td align="center"><strong>Enable Ban</strong></td>
			    <td>If no, all banning feature (ip address / nick banning) will be disabled. </td>
			    <td align="center"><input name="enable_ban" type="radio" value="true" <?php if($configManager->isBanEnabled()) echo 'checked'; ?>>
			      Yes</td>
			    <td align="center"><input name="enable_ban" type="radio" value="false" <?php if(!$configManager->isBanEnabled()) echo 'checked'; ?>>
			      No</td>
			  </tr>
			  <tr class="odd">
			    <td align="center"><strong>Auto Ban</strong></td>
			    <td>If yes, smiletag will automatically   ban user IP address, if he keeps flooding your board after a number of flood   attempt.</td>
			    <td align="center"><input name="auto_ban" type="radio" value="true" <?php if($configManager->isAutoBanEnabled()) echo 'checked'; ?>>
			      Yes</td>
			    <td align="center"><input name="auto_ban" type="radio" value="false" <?php if(!$configManager->isAutoBanEnabled()) echo 'checked'; ?>>
			      No</td>
			  </tr>
			  
			  <tr>
			    <td colspan="4" align="right"><input name="submit" class="btn" type="submit" id="submit" value="Submit Board Control Setting"></td>
			    </tr>
			</table>
			<input type="hidden" name="action_input" value="configuration">
			<input type="hidden" name="action_target" value="configuration">
		   </form><br />
		   <form action="adminProcess.php" method="post">
		   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
			  <tr class="odd">
				<th colspan="3" align="center" scope="col">Others  </th>
				</tr>
			  <tr>
				<td width="18%" align="center"><strong>Displayed Message</strong></td>
				<td width="61%">Maximum number of message displayed in the shoutbox iframe.</td>
				<td width="21%"><input name="max_message_number" type="text" class="form" id="max_message_number" value="<?php echo $configManager->getMaxMessageNumber(); ?>" size="5"></td>
				</tr>
			  <tr class="odd">
			    <td align="center"><strong>Max Character</strong></td>
			    <td><p align="justify">Maximum character length allowed for each posted message.</p></td>
			    <td><input name="max_message_length" type="text" class="form" id="max_message_length" value="<?php echo $maxInputLength['message']; ?>" size="5"></td>
			    </tr>
			  <tr>
			    <td align="center"><strong>Message Rotation</strong></td>
				<td>Maximum number of messages can be stored in the data file.</td>
				<td><input name="max_message_rotation" type="text" class="form" id="max_message_rotation" value="<?php echo $configManager->getMaxMessageRotation(); ?>" size="5"></td>
				</tr>
			  <tr class="odd">
			    <td align="center"><strong>Max Nickname</strong></td>
				<td><p align="justify">Maximum character allowed for nickname.</p></td>
				<td><input name="max_nickname" type="text" class="form" id="max_nickname" value="<?php echo $maxInputLength['name']; ?>" size="5"></td>
				</tr>
			  <tr>
			    <td align="center"><strong>Max URL</strong></td>
			    <td><p align="justify">Maximum character allowed for URL (Web address / email). </p></td>
			    <td><input name="max_url" type="text" class="form" id="max_url" value="<?php echo $maxInputLength['message']; ?>" size="5"></td>
			    </tr>
			  <tr class="odd">
			    <td align="center"><strong>Time Zone</strong></td>
			    <td>Sets the time zone for smiletag timestamp. Uses GMT setting.</td>
			    <td><input name="time_zone" type="text" class="form" id="time_zone" value="<?php echo $configManager->getTimeZone(); ?>" size="10"></td>
			    </tr>
			  <tr>
			    <td align="center"><strong>Date Format</strong></td>
			    <td>Sets the date format for smiletag timestamp. The format is based on PHP <A href="http://www.php.net/date">date format character</A>.</td>
			    <td><input name="date_format" type="text" class="form" id="date_format" value="<?php echo $configManager->getDateFormat(); ?>" size="10"></td>
			    </tr>
			  <tr class="odd">
			    <td align="center"><strong>Time Format</strong></td>
			    <td>Sets the hour, minute and seconds format.</td>
			    <td><input name="time_format" type="text" class="form" id="time_format" value="<?php echo $configManager->getTimeFormat(); ?>" size="10"></td>
			    </tr>
			  <tr>
			    <td align="center"><strong>URL Text</strong></td>
			    <td>The text which will be used to replace every link found in the message.</td>
			    <td><input name="url_text" type="text" class="form" id="url_text" value="<?php echo $configManager->getUrlText(); ?>" size="10"></td>
			    </tr>
			  <tr class="odd">
			    <td align="center"><strong>Flood Interval</strong></td>
			    <td>Number of seconds to wait before a user can post another message. Sets to 0 to   disable flood protection.</td>
			    <td><input name="flood_interval" type="text" class="form" id="flood_interval" value="<?php echo $configManager->getFloodInterval(); ?>" size="5"></td>
			    </tr>
			  <tr>
			    <td align="center"><strong>Auto Ban Trigger  </strong></td>
			    <td>The number of flood attempt that will cause a user automatically banned. This   option only applied if <STRONG>Auto Ban </STRONG>is enabled. </td>
			    <td><input name="auto_ban_trigger" type="text" class="form" id="auto_ban_trigger" value="<?php echo $configManager->getMaxFlood(); ?>" size="5"></td>
			    </tr>
			  <tr class="odd">
			    <td align="center"><strong>Enable Custom Text </strong></td>
			    <td>This is a general purpose option, that let you display a different text for each   message. If yes, then <STRONG>Custom Text 1 </STRONG> and <STRONG>Custom Text 2 </STRONG> will be displayed sequently for each message. You   can use it to create different background color, image, custom tag, etc. It's up   to your creativity</td>
			    <td><input name="enable_custom_text" type="radio" value="true" <?php if($configManager->isCustomTextEnabled()) echo 'checked'; ?>>
Yes 
  <input name="enable_custom_text" type="radio" value="false" <?php if(!$configManager->isCustomTextEnabled()) echo 'checked'; ?>>
  No</td>
			    </tr>
			  <tr>
			    <td align="center"><strong>Custom Text 1  </strong></td>
			    <td>The first custom text. </td>
			    <td><input name="text_first" type="text" class="form" id="text_first" value="<?php echo $customText[0]; ?>" size="20"></td>
			    </tr>
			  <tr class="odd">
			    <td align="center"><strong>Custom Text 2</strong></td>
			    <td>The second custom text. </td>
			    <td><input name="text_second" type="text" class="form" id="text_second" value="<?php echo $customText[1]; ?>" size="20"></td>
			    </tr>
			  

			  <tr>
			    <td colspan="3" align="right"><input name="submit" class="btn" type="submit" id="submit" value="Submit Others Setting"></td>
			    </tr>
			</table>
			<input type="hidden" name="action_input" value="configuration">
			<input type="hidden" name="action_target" value="configuration">
		   </form><br />
		   <form action="adminProcess.php" method="post">
		   <table width="50%" border="0" cellspacing="0" cellpadding="0" class="grid">
			  <tr class="odd">
				<th colspan="2" align="center" scope="col">Data Files  </th>
				</tr>
			  <tr>
				<td width="16%" align="center"><strong>Message File  </strong></td>
				<td width="35%"><input name="message_file" type="text" class="form" id="message_file" value="<?php echo $configManager->getMessageFileName(); ?>" size="30"></td>
				</tr>
			  <tr class="odd">
			    <td align="center"><strong>Moderation File </strong></td>
			    <td><input name="moderation_message_file" type="text" class="form" id="moderation_message_file" value="<?php echo $configManager->getModerationMessageFileName(); ?>" size="30"></td>
			    </tr>
			  <tr>
			    <td align="center"><strong>Smiley Config File  </strong></td>
				<td><input name="smiley_config_file" type="text" class="form" id="smiley_config_file" value="<?php echo $configManager->getSmileyConfigFile(); ?>" size="30"></td>
				</tr>
			  <tr class="odd">
			    <td align="center"><strong>Ban List File  </strong></td>
				<td><input name="ban_file" type="text" class="form" id="ban_file" value="<?php echo $configManager->getBanFileName(); ?>" size="30"></td>
				</tr>
			  <tr>
			    <td align="center"><strong>Bad Words File </strong></td>
			    <td><input name="badword_file" type="text" class="form" id="badword_file" value="<?php echo $configManager->getBadwordFile(); ?>" size="30"></td>
			    </tr>
			  

			  <tr>
			    <td colspan="2" align="right"><input name="submit" class="btn" type="submit" id="submit" value="Submit Data Files Setting"></td>
			    </tr>
			</table>
			<input type="hidden" name="action_input" value="configuration">
			<input type="hidden" name="action_target" value="configuration">
		   </form><br />