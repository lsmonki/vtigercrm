<?php
/*
* MailFeed v1.0.1
* Copyright © 2004 Ryan Grove <ryan@wonko.com>. All rights reserved.
*
* Checks a POP3, IMAP, or NNTP mailbox on demand and returns an RSS feed
* containing the messages in the mailbox. See the MailFeed website at
* http://wonko.com/software/mailfeed/ for details.
*
* Todo:
* - Caching
*
******************************************************************************
* This program is free software; you can redistribute it and/or modify it
* under the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the License, or (at your
* option) any later version.
*
* This program is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General
* Public License for more details.
*
* You should have received a copy of the GNU General Public License along
* with this program; if not, write to the Free Software Foundation, Inc.,
* 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
 
###############################################################################
# Configuration
###############
 
/* POP3/IMAP/NNTP server to connect to, with optional port. */
$server = "test.com:143";
 
/* Protocol specification (optional) */
$protocol = "/notls";
 
/* Name of the mailbox to open. */
$mailbox = "INBOX";
 
/* Your username. */
$username = "p1";
 
/* Your password. */
$password = "p1";
 
/* Whether or not to download the message body and display it in the
   <description> element. */
$downloadBody = true;
 
/* Whether or not to mark retrieved messages as seen. */
$markSeen = true;
 
/* Whether or not to convert newlines to HTML line breaks in message bodies for
   displaying in HTML-based RSS readers. */
$htmlLineBreaks = true;
 
/* If the message body is longer than this number of bytes, it will be trimmed.
   Set to 0 for no limit. */
$bodyMaxLength = 4096;
 
###############################################################################
# End of User-Editable Settings
###############################
 
require_once("Mail/mimeDecode.php");
 
// Parse URL vars.
foreach(array_keys($_GET) as $var)
{
	$value = urldecode($_GET[$var]);
 
	switch(strtolower($var))
	{
		case "server":
			$server = $value;
			break;
 
		case "protocol":
			$protocol = $value;
			break;
 
		case "mailbox":
			$mailbox = $value;
			break;
 
		case "username":
		case "user":
		case "login":
			$username = $value;
			break;
 
		case "password":
		case "pass":
			$password = $value;
			break;
 
		case "markseen":
		case "seen":
			$markSeen = $value;
			break;
 
		case "downloadbody":
		case "showbody":
		case "body":
			$downloadBody = $value;
			break;
 
		case "htmlLineBreaks":
			$htmlLineBreaks = $value;
			break;
 
		case "bodymaxlength":
		case "maxbodylength":
		case "bodylength":
			$bodyMaxLength = $value;
			break;
	}
}
 
RssHeader();
 
error_reporting(0);
set_error_handler("errorHandler");
 
if ($conn = imap_open('{'.$server.$protocol.'}'.$mailbox, $username, $password, OP_READONLY))
{
	// Set up the parameters for the MimeDecode object.
	$mimeParams = array();
	$mimeParams['decode_headers'] = true;
	$mimeParams['crlf']           = "\r\n";
 
	if ($downloadBody)
	{
		$mimeParams['include_bodies'] = true;
		$mimeParams['decode_bodies']  = true;
	}
	else
	{
		$mimeParams['include_bodies'] = false;
		$mimeParams['decode_bodies']  = false;
	}
 
	// See if the mailbox contains any messages.
	if ($msgCount = imap_num_msg($conn))
	{
		// Loop through the messages.
		for($i = 1; $i <= $msgCount; $i++)
		{
			// Get the message header.
			if ($downloadBody)
				$header = imap_fetchheader($conn, $i, FT_PREFETCHTEXT);
			else
				$header = imap_fetchheader($conn, $i);
 
			// Get the message body if desired.
			if ($downloadBody)
			{
				if ($markSeen)
					$body = imap_body($conn, $i);
				else
					$body = imap_body($conn, $i, FT_PEEK);
			}
 
			// Send the header and body through mimeDecode.
			$mimeParams['input'] = $header.$body;
			$message = Mail_mimeDecode::decode($mimeParams);
 
			// Some mail servers and clients use special messages for holding
			// mailbox data; ignore that message if it exists.
			if ($message->headers['subject'] != "DON'T DELETE THIS MESSAGE -- FOLDER INTERNAL DATA")
			{
				// Format the message for inclusion in the RSS feed.
				if ($downloadBody)
				{
					// Does the message have an attachment?
					if (strtolower($message->ctype_primary) == "multipart")
					{
						$body = trim($message->parts[0]->body);
 
						// Get information about the attachments.
						$attachCount = count($message->parts) - 1;
						$attachSize  = 0;
 
						for($p = 1; $p < count($message->parts); $p++)
							$attachSize += strlen($message->parts[$p]->body);
 
						if ($attachCount == 1)
							$body .= "\n\n[Message contains 1 attachment. (".translateSize($attachSize).")]";
						else
							$body .= "\n\n[Message contains $attachCount attachments. (".translateSize($attachSize).")]";
					}
					else
					{
						$body = trim($message->body);
					}
 
					// Trim the body to $bodyMaxLength characters if desired.
					if ($bodyMaxLength && strlen($body) > $bodyMaxLength)
						$body = substr($body, 0, $bodyMaxLength).'...';
 
					// Convert newlines to HTML line breaks if desired.
					if ($htmlLineBreaks)
						$body = nl2br($body);
				}
				else
				{
					$body = "";
				}
 
				// Create the RSS item.
				RssItem($message->headers['subject'], $body,
					$message->headers['from'], $mailbox,
					dateToRfc822($message->headers['date']));
			}
		}
	}
 
	imap_close($conn);
}
 
RssFooter();
 
restore_error_handler();
 
###############################################################################
# RSS functions
###############
 
function RssFooter()
{
	?>
		</channel>
	</rss>
	<?php
}
 
function RssHeader()
{
	header("Content-Type: text/xml");
 
	echo "<?xml version=\"1.0\"?>\n";
	?>
	<rss version="2.0">
		<channel>
			<title>Inbox</title>
			<description>Checks a POP3, IMAP, or NNTP mailbox on demand and returns an RSS feed containing the messages in the mailbox.</description>
			<docs>http://blogs.law.harvard.edu/tech/rss</docs>
			<generator>INBOX</generator>
			<link>index.php?module=squirrelmail-1.4.4&amp;action=right_main</link>
	<?php
}
 
function RssItem($title, $description = "", $author = "", $category = "",
	$pubDate = "", $guid = "")
{
	?>
			<item>
				<title><?=htmlspecialchars($title)?></title>
				<link>index.php?module=squirrelmail-1.4.4&amp;action=right_main&amp;smodule=WEBMAILS</link>
				<?php
				if (strlen($pubDate))
				{
					?>
					<pubDate><?=htmlspecialchars($pubDate)?></pubDate>
					<?php
				}
 
				if (strlen($description))
				{
					?>
					<description><?=htmlspecialchars($description)?></description>
					<?php
				}
 
				if (strlen($author))
				{
					?>
					<author><?=htmlspecialchars($author)?></author>
					<?php
				}
 
				if (strlen($category))
				{
					?>
					<category><?=htmlspecialchars($category)?></category>
					<?php
				}
 
				if (strlen($guid))
				{
					?>
					<guid><?=htmlspecialchars($guid)?></guid>
					<?php
				}
				?>
			</item>
	<?
}
 
###############################################################################
# Error handling functions
##########################
 
function errorHandler($errno, $errmsg, $filename, $linenum, $vars)
{
	if ($errno != E_NOTICE && $errno != E_USER_NOTICE)
	{
		RssItem("MailFeed Error", "Error at line $linenum: ".htmlspecialchars($errmsg),
			"MailFeed", "Errors", date("D, d M Y H:i:s T"));
	}
}
 
###############################################################################
# Miscellaneous functions
#########################
 
function dateToRfc822($date)
{
	$time = strtotime($date);
 
	if ($time > 0)
		$date = date("D, d M Y H:i:s T", $time);
 
	return $date;
}
 
function translateSize($size)
{
	$units    = array("bytes", "KB", "MB", "GB", "TB");
 
	for($i = 0; $size >= 1024 && $i < count($units); $i++)
		$size /= 1024;
 
	return round($size, 2)." {$units[$i]}";
}
?>

