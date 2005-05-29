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
 * File Name: fck_1_gecko.js
 * 	This is the first part of the "FCK" object creation. This is the main
 * 	object that represents an editor instance.
 * 	(Gecko specific implementations)
 * 
 * Version:  2.0 RC3
 * Modified: 2005-02-23 18:27:28
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

FCK.Description = "FCKeditor for Gecko Browsers" ;

FCK.InitializeBehaviors = function()
{
	// Disable Right-Click
	var oOnContextMenu = function( e )
	{
		e.preventDefault() ;
		FCK.ShowContextMenu( e.clientX, e.clientY ) ;
	}
	this.EditorDocument.addEventListener( 'contextmenu', oOnContextMenu, true ) ;

	var oOnKeyDown = function( e )
	{
		if ( e.ctrlKey && !e.shiftKey && !e.altKey )
		{
			// Char 86/118 = V/v
			if ( e.which == 86 || e.which == 118 )
			{
				if ( FCK.Status == FCK_STATUS_COMPLETE )
				{
					if ( !FCK.Events.FireEvent( "OnPaste" ) )
						e.preventDefault() ;
				}
				else
					e.preventDefault() ;
			}
		}
	}
	this.EditorDocument.addEventListener( 'keydown', oOnKeyDown, true ) ;
	
	this.ExecOnSelectionChange = function()
	{
		FCK.Events.FireEvent( "OnSelectionChange" ) ;
	}
	
	this.ExecOnSelectionChangeTimer = function()
	{
		if ( FCK.LastOnChangeTimer )
			window.clearTimeout( FCK.LastOnChangeTimer ) ;
		
		FCK.LastOnChangeTimer = window.setTimeout( FCK.ExecOnSelectionChange, 100 ) ;
	}
	
	this.EditorDocument.addEventListener( 'mouseup', this.ExecOnSelectionChange, false ) ;
	
	// On Gecko, firing the "OnSelectionChange" event on every key press started to be too much
	// slow. So, a timer has been implemented to solve performance issues when tipying to quickly.
	this.EditorDocument.addEventListener( 'keyup', this.ExecOnSelectionChangeTimer, false ) ;
	
	this._DblClickListener = function( e )
	{
		FCK.OnDoubleClick( e.target ) ;
		e.stopPropagation() ;
	}

	this.EditorDocument.addEventListener( 'dblclick', this._DblClickListener, true ) ;
	
	this._OnLoad = function()
	{
		if ( this._FCK_HTML )
		{
			this.document.body.innerHTML = this._FCK_HTML ;
			this._FCK_HTML = null ;
		}
	}
	
	this.EditorWindow.addEventListener( 'load', this._OnLoad, true ) ;
}

FCK.MakeEditable = function()
{
	this.EditorWindow.document.designMode = 'on' ;

	// Tell Gecko to use or not the <SPAN> tag for the bold, italic and underline.
	this.EditorWindow.document.execCommand( 'useCSS', false, !FCKConfig.GeckoUseSPAN ) ;
}

FCK.Focus = function()
{
	try
	{
		FCK.EditorWindow.focus() ;
	}
	catch(e) {}
}

FCK.SetHTML = function( html, forceWYSIWYG )
{
	if ( forceWYSIWYG || FCK.EditMode == FCK_EDITMODE_WYSIWYG )
	{
		// Gecko has a lot of bugs mainly when handling editing features.
		// To avoid an Aplication Exception (that closes the browser!) we
		// must first write the <HTML> contents with an empty body, and
		// then insert the body contents.
		// (Oh yes... it took me a lot of time to find out this workaround)

		this.EditorDocument.open() ;

		if ( FCKConfig.FullPage && FCKRegexLib.BodyContents.test( html ) )
		{
			// Add the <BASE> tag to the input HTML.
			if ( FCK.TempBaseTag.length > 0 && !FCKRegexLib.HasBaseTag.test( html ) )
				html = html.replace( FCKRegexLib.HeadCloser, FCK.TempBaseTag + '</head>' ) ;

			html = html.replace( FCKRegexLib.HeadCloser, '<link href="' + FCKConfig.BasePath + 'css/fck_internal.css' + '" rel="stylesheet" type="text/css" _fcktemp="true" /></head>' ) ;

			// Extract the BODY contents from the html.
			var oMatch		= html.match( FCKRegexLib.BodyContents ) ;
			var sOpener		= oMatch[1] ;	// This is the HTML until the <body...> tag, inclusive.
			var sContents	= oMatch[2] ;	// This is the BODY tag contents.
			var sCloser		= oMatch[3] ;	// This is the HTML from the </body> tag, inclusive.
		
			this.EditorDocument.write( sOpener + '&nbsp;' + sCloser ) ;
		}
		else
		{
			var sHtml = 
				'<html dir="' + FCKConfig.ContentLangDirection + '">' + 
				'<head><title></title>' +
				'<link href="' + FCKConfig.EditorAreaCSS + '" rel="stylesheet" type="text/css" />' +
				'<link href="' + FCKConfig.BasePath + 'css/fck_internal.css' + '" rel="stylesheet" type="text/css" _fcktemp="true" />' ;

			sHtml += FCK.TempBaseTag ;

			sHtml += '</head><body>&nbsp;</body></html>' ;

			this.EditorDocument.write( sHtml ) ;
		}

		this.EditorDocument.close() ;

		if ( this.EditorDocument.body )
			this.EditorDocument.body.innerHTML = sContents ?  sContents : html ;
		else
			this.EditorWindow._FCK_HTML = sContents ?  sContents : html ;
		
		// TODO: Wait stable version and remove the following commented lines.
		// We must load the CSS style after setting the innerHTML to avoid an error.
		// The body is not available is the style link tag is written inside the <html> tag.
//		if ( !FCKConfig.FullPage )
//		{
//			FCKTools.AppendStyleSheet( this.EditorDocument, FCKConfig.EditorAreaCSS ) ;
//			FCKTools.AppendStyleSheet( this.EditorDocument, FCKConfig.BasePath + 'css/fck_internal.css' ) ;
//		}
		
		this.InitializeBehaviors() ;
		
		this.Events.FireEvent( 'OnAfterSetHTML' ) ;
	}
	else
		document.getElementById('eSourceField').value = html ;
}

