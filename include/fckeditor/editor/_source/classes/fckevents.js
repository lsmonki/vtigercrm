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
 * File Name: fckevents.js
 * 	FCKEvents Class: used to handle events is a advanced way.
 * 
 * Version:  2.0 RC3
 * Modified: 2005-03-02 09:19:48
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

var FCKEvents = function( eventsOwner )
{
	this.Owner = eventsOwner ;
	this.RegisteredEvents = new Object() ;
}

FCKEvents.prototype.AttachEvent = function( eventName, functionPointer )
{
	if ( ! this.RegisteredEvents[ eventName ] ) this.RegisteredEvents[ eventName ] = new Array() ;
	
	this.RegisteredEvents[ eventName ][ this.RegisteredEvents[ eventName ].length ] = functionPointer ;
}

FCKEvents.prototype.FireEvent = function( eventName, params )
{
	var bReturnValue = true ;
	
	var oCalls = this.RegisteredEvents[ eventName ] ;
	if ( oCalls ) 
	{
		for ( var i = 0 ; i < oCalls.length ; i++ )
			bReturnValue = ( oCalls[ i ]( params ) && bReturnValue ) ;
	}
	
	return bReturnValue ;
}