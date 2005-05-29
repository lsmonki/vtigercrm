/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2004 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: fcktoolbarbutton.js
 * 	FCKToolbarButton Class: represents a button in the toolbar.
 * 
 * Version:  2.0 RC3
 * Modified: 2005-01-18 11:07:28
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

var FCKToolbarButton = function( commandName, label, tooltip, style, sourceView, contextSensitive )
{
	this.Command			= FCKCommands.GetCommand( commandName ) ;
	this.Label				= label ? label : commandName ;
	this.Tooltip			= tooltip ? tooltip : ( label ? label : commandName) ;
	this.Style				= style ? style : FCK_TOOLBARITEM_ONLYICON ;
	this.SourceView			= sourceView ? true : false ;
	this.ContextSensitive	= contextSensitive ? true : false ;
	this.IconPath			= FCKConfig.SkinPath + 'toolbar/' + commandName.toLowerCase() + '.gif' ;
	this.State				= FCK_UNKNOWN ;
}

FCKToolbarButton.prototype.CreateInstance = function( parentToolbar )
{
/*
	<td title="Bold" class="TB_Button_Off" unselectable="on" onmouseover="Button_OnMouseOver(this);" onmouseout="Button_OnMouseOut(this);">
		<table class="TB_ButtonType_Icon" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td class="TB_Icon"><img src="icons/redo.gif" width="21" height="21"></td>
				<td class="TB_Text" unselectable="on">Redo</td>
			</tr>
		</table>
	</td>
*/	
	this.DOMDiv = document.createElement( 'div' ) ;
	this.DOMDiv.className		= 'TB_Button_Off' ;

	this.DOMDiv.FCKToolbarButton	= this ;
	
	this.DOMDiv.onmouseover = function()
	{
		if ( this.FCKToolbarButton.State != FCK_TRISTATE_DISABLED )
		{
			this.className = 'TB_Button_On' ;
		}
	}
	
	this.DOMDiv.onmouseout	= function()
	{
		if ( this.FCKToolbarButton.State != FCK_TRISTATE_DISABLED &&  this.FCKToolbarButton.State != FCK_TRISTATE_ON )
		{
			this.className = 'TB_Button_Off' ;
		}
	}
	
	this.DOMDiv.onclick = function()
	{
		if ( this.FCKToolbarButton.State != FCK_TRISTATE_DISABLED )
			this.FCKToolbarButton.Command.Execute() ;
		return false ;
	}

	// Gets the correct CSS class to use for the specified style (param).
	var sClass ;
	switch ( this.Style )
	{
		case FCK_TOOLBARITEM_ONLYICON :
			sClass = 'TB_ButtonType_Icon' ;
			break ;
		case FCK_TOOLBARITEM_ONLYTEXT :
			sClass = 'TB_ButtonType_Text' ;
			break ;
		case FCK_TOOLBARITEM_ICONTEXT :
			sClass = '' ;
			break ;
	}

	this.DOMDiv.innerHTML = 
		'<table title="' + this.Tooltip + '" class="' + sClass + '" cellspacing="0" cellpadding="0" border="0" unselectable="on">' +
			'<tr>' +
				'<td class="TB_Icon" unselectable="on"><img src="' + this.IconPath + '" width="21" height="21" unselectable="on"></td>' +
				'<td class="TB_Text" unselectable="on">' + this.Label + '</td>' +
			'</tr>' +
		'</table>' ;
	

	var oCell = parentToolbar.DOMRow.insertCell(-1) ;
	oCell.appendChild( this.DOMDiv ) ;
	
	this.RefreshState() ;
}

FCKToolbarButton.prototype.RefreshState = function()
{
/*
	TODO: Delete this commend block on stable version.
	// Gets the actual state.
//	var eState ;

//	if ( FCK.EditMode == FCK_EDITMODE_SOURCE && ! this.SourceView )
//		eState = FCK_TRISTATE_DISABLED ;
//	else
*/
	// Gets the actual state.
	var eState = this.Command.GetState() ;
	
	// If there are no state changes than do nothing and return.
	if ( eState == this.State ) return ;
	
	// Sets the actual state.
	this.State = eState ;
	
	switch ( this.State )
	{
		case FCK_TRISTATE_ON :
			this.DOMDiv.className = 'TB_Button_On' ;
			break ;
		case FCK_TRISTATE_OFF :
			this.DOMDiv.className = 'TB_Button_Off' ;
			break ;
		default :
			this.DOMDiv.className = 'TB_Button_Disabled' ;
			break ;
	}
}

FCKToolbarButton.prototype.Enable = function()
{
	this.RefreshState() ;
}

FCKToolbarButton.prototype.Disable = function()
{
	this.State = FCK_TRISTATE_DISABLED ;
	this.DOMDiv.className = 'TB_Button_Disabled' ;
}