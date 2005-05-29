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
 * File Name: fck.js
 * 	Creation and initialization of the "FCK" object. This is the main object
 * 	that represents an editor instance.
 * 
 * Version:  2.0 RC3
 * Modified: 2005-02-23 20:51:12
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

// FCK represents the active editor instance
var FCK = new Object() ;
FCK.Name			= FCKURLParams[ 'InstanceName' ] ;

FCK.Status			= FCK_STATUS_NOTLOADED ;
FCK.EditMode		= FCK_EDITMODE_WYSIWYG ;

FCK.PasteEnabled	= false ;

// First try to get the Linked field using its ID.
FCK.LinkedField = window.parent.document.getElementById( FCK.Name ) ;
// If no linked field is available with that ID, try with the "Name".
if ( !FCK.LinkedField )
	FCK.LinkedField = window.parent.document.getElementsByName( FCK.Name )[0] ;
