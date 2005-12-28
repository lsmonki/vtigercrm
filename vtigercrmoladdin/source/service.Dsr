VERSION 5.00
Begin {AC0714F6-3D04-11D1-AE7D-00A0C90F26F4} service 
   ClientHeight    =   9630
   ClientLeft      =   1740
   ClientTop       =   1545
   ClientWidth     =   10545
   _ExtentX        =   18600
   _ExtentY        =   16986
   _Version        =   393216
   Description     =   "vtigerCRM Outlook Addin"
   DisplayName     =   "vtigerCRM"
   AppName         =   "Microsoft Outlook"
   AppVer          =   "Microsoft Outlook 9.0"
   LoadName        =   "Startup"
   LoadBehavior    =   3
   RegLocation     =   "HKEY_CURRENT_USER\Software\Microsoft\Office\Outlook"
End
Attribute VB_Name = "service"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit

Implements IDTExtensibility2
Private gBaseClass As New clsOlkAddIn

Private Sub IDTExtensibility2_OnAddInsUpdate(custom() As Variant)
'To Be Declared for IDTExtensibility2
End Sub
Private Sub IDTExtensibility2_OnBeginShutdown(custom() As Variant)
'To Be Declared for IDTExtensibility2
End Sub
Private Sub IDTExtensibility2_OnConnection(ByVal Application As Object, _
   ByVal ConnectMode As AddInDesignerObjects.ext_ConnectMode, _
   ByVal AddInInst As Object, custom() As Variant)
    
    gBaseClass.InitHandler Application, AddInInst.ProgId
   
End Sub

Private Sub IDTExtensibility2_OnDisconnection(ByVal RemoveMode _
   As AddInDesignerObjects.ext_DisconnectMode, custom() As Variant)
    Dim objCB As Office.CommandBar
    On Error Resume Next
    'If RemoveMode = ext_dm_UserClosed Then
        Set objCB = golApp.ActiveExplorer.CommandBars("vtigerCRM")
        objCB.FindControl(Type:=msoControlPopup, Tag:="vtigerCRMMenu").Delete
        objCB.FindControl(Type:=msoControlButton, Tag:="AddEmailsButton").Delete
        objCB.FindControl(Type:=msoControlButton, Tag:="SyncContactsButton").Delete
        objCB.FindControl(Type:=msoControlButton, Tag:="SyncTasksButton").Delete
        objCB.FindControl(Type:=msoControlButton, Tag:="SyncCalendarButton").Delete
        
        objCB.Delete
    'End If
    gBaseClass.UnInitHandler
    Set gBaseClass = Nothing
End Sub
Private Sub IDTExtensibility2_OnStartupComplete(custom() As Variant)
'To Be Declared for IDTExtensibility2
End Sub
