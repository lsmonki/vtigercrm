Attribute VB_Name = "modLang"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit

Public gMsg001 As String
Public gMsg002 As String
Public gMsg003 As String
Public gMsg004 As String
Public gMsg005 As String
Public gMsg006 As String
Public gMsg007 As String
Public gMsg008 As String
Public gMsg009 As String
Public gMsg010 As String
Public gMsg011 As String
Public gMsg012 As String
Public gMsg013 As String
Public gMsg014 As String
Public gMsg015 As String
Public gMsg016 As String
Public gMsg017 As String

Public Function bLoadMessages() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

gMsg001 = "MSXML objects cannot be initialize"
gMsg002 = "PocketSOAP objects cannot be initialize"
gMsg003 = "Invalid return value from vtigerCRM"
gMsg004 = "Error while parsing response from vtigerCRM"
gMsg005 = "Outlook objects cannot be initialize"
gMsg006 = "Selected mail cannot be initialize"
gMsg007 = ""
gMsg008 = ""
gMsg009 = ""
gMsg010 = ""
gMsg011 = ""
gMsg012 = ""
gMsg013 = ""
gMsg014 = ""
gMsg015 = ""
gMsg016 = ""
gMsg017 = ""

bLoadMessages = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bLoadMessages = False
EXIT_ROUTINE:
End Function
