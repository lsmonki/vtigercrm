<?php
/***************************************************************************  
 *                               extension.inc
 *                            -------------------                         
 *   begin                : Saturday, Feb 13, 2001 
 *   copyright            : (C) 2001 The phpBB Group        
 *   email                : support@phpbb.com                           
 *                                                          
 *   $Id: extension.inc,v 1.9 2005/01/04 15:04:11 saraj Exp $
 *                                                            
 * 
 ***************************************************************************/ 

define ('IN_PHPBB', true);
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Change this if your extension is not .php!
//
$phpEx = "php";

$starttime = 0;

?>
