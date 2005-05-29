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
 * File Name: fckxml_ie.js
 * 	FCKXml Class: class to load and manipulate XML files.
 * 	(IE specific implementation)
 * 
 * Version:  2.0 RC3
 * Modified: 2005-02-27 22:15:31
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

var FCKXml = function()
{}

FCKXml.prototype.LoadUrl = function( urlToCall, asyncFunctionPointer )
{
	var oFCKXml = this ;

	var bAsync = ( typeof(asyncFunctionPointer) == 'function' ) ;

	var oXmlHttp = FCKTools.CreateXmlObject( 'XmlHttp' ) ;

	oXmlHttp.open( "GET", urlToCall, bAsync ) ;
	
	if ( bAsync )
	{	
		oXmlHttp.onreadystatechange = function() 
		{
			if ( oXmlHttp.readyState == 4 )
			{
				oFCKXml.DOMDocument = oXmlHttp.responseXML ;
				asyncFunctionPointer( oFCKXml ) ;
			}
		}
	}
	
	oXmlHttp.send( null ) ;
	
	if ( ! bAsync )
	{ 
		if ( oXmlHttp.status == 200 )
			this.DOMDocument = oXmlHttp.responseXML ;
		else if ( oXmlHttp.status == 0 && oXmlHttp.readyState == 4 )
		{
			oFCKXml.DOMDocument = FCKTools.CreateXmlObject( 'DOMDocument' ) ;
			oFCKXml.DOMDocument.async = false ;
			oFCKXml.DOMDocument.resolveExternals = false ;
			oFCKXml.DOMDocument.loadXML( oXmlHttp.responseText ) ;
		}
		else
			alert( 'Error loading "' + urlToCall + '"' ) ;
	}
}

FCKXml.prototype.SelectNodes = function( xpath, contextNode )
{
	if ( contextNode )
		return contextNode.selectNodes( xpath ) ;
	else
		return this.DOMDocument.selectNodes( xpath ) ;
}

FCKXml.prototype.SelectSingleNode = function( xpath, contextNode ) 
{
	if ( contextNode )
		return contextNode.selectSingleNode( xpath ) ;
	else
		return this.DOMDocument.selectSingleNode( xpath ) ;
}
