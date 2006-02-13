/***************************************************************************
 *						lang_extend.php [{COUNTRY_NAME}]
 *						---------------
 *	begin				: 28/09/2003
 *	copyright			: Ptirhiik
 *	email				: ptirhiik@clanmckeen.com
 *
 *	version				: 1.0.1 - 16/10/2003
 *
 * redefinition of existing keys
 *
 * last edit : {EDITTIME} by {USERNAME}
 ***************************************************************************/
 
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// admin part
if ( $lang_extend_admin )
{
	<!-- BEGIN admin -->
	<!-- BEGIN pack -->
	<!-- BEGIN linefeed -->

	<!-- END linefeed -->
	// pack : {admin.pack.TITLE}
	<!-- BEGIN entry -->
	<!-- BEGIN switch_simple -->
	$lang['{admin.pack.entry.KEY_MAIN}'] = '{admin.pack.entry.VALUE}';
	<!-- END switch_simple -->
	<!-- BEGIN switch_double -->
	$lang['{admin.pack.entry.KEY_MAIN}']['{admin.pack.entry.KEY_SUB}'] = '{admin.pack.entry.VALUE}';
	<!-- END switch_double -->
	<!-- END entry -->
	<!-- END pack -->
	<!-- END admin -->
}
<!-- BEGIN normal -->
<!-- BEGIN pack -->

// pack : {normal.pack.TITLE}
<!-- BEGIN entry -->
<!-- BEGIN switch_simple -->
$lang['{normal.pack.entry.KEY_MAIN}'] = '{normal.pack.entry.VALUE}';
<!-- END switch_simple -->
<!-- BEGIN switch_double -->
$lang['{normal.pack.entry.KEY_MAIN}']['{normal.pack.entry.KEY_SUB}'] = '{normal.pack.entry.VALUE}';
<!-- END switch_double -->
<!-- END entry -->
<!-- END pack -->
<!-- END normal -->