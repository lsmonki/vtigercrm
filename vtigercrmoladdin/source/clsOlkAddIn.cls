VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "clsOlkAddIn"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit

'Object variables for Event procedures
Private WithEvents objOutlook As Outlook.Application
Attribute objOutlook.VB_VarHelpID = -1
Private WithEvents objExpl As Outlook.Explorer
Attribute objExpl.VB_VarHelpID = -1
Private WithEvents colExpl As Outlook.Explorers
Attribute colExpl.VB_VarHelpID = -1

'CommandBar object variables for vtigerCRM Toolbar
Dim cbrNewToolbar As Office.CommandBar
Dim oHelpCmdBar As Office.CommandBarControl

Private WithEvents oLogin As Office.CommandBarButton
Attribute oLogin.VB_VarHelpID = -1
Private WithEvents oAddEmailsMenu As Office.CommandBarButton
Attribute oAddEmailsMenu.VB_VarHelpID = -1
Private WithEvents oAboutVt As Office.CommandBarButton
Attribute oAboutVt.VB_VarHelpID = -1
Private WithEvents oVtHelp As Office.CommandBarButton
Attribute oVtHelp.VB_VarHelpID = -1
Private WithEvents oVtForums As Office.CommandBarButton
Attribute oVtForums.VB_VarHelpID = -1
Private WithEvents oPref As Office.CommandBarButton
Attribute oPref.VB_VarHelpID = -1
Private WithEvents oLogout As Office.CommandBarButton
Attribute oLogout.VB_VarHelpID = -1
Private WithEvents oAddEmailsButton As Office.CommandBarButton
Attribute oAddEmailsButton.VB_VarHelpID = -1

Private WithEvents oSContactsMenu As Office.CommandBarButton
Attribute oSContactsMenu.VB_VarHelpID = -1
Private WithEvents oSyncContacts As Office.CommandBarButton
Attribute oSyncContacts.VB_VarHelpID = -1

Private WithEvents oSTaskMenu As Office.CommandBarButton
Attribute oSTaskMenu.VB_VarHelpID = -1
Private WithEvents oSyncTask As Office.CommandBarButton
Attribute oSyncTask.VB_VarHelpID = -1

Private WithEvents oSCalendarMenu As Office.CommandBarButton
Attribute oSCalendarMenu.VB_VarHelpID = -1
Private WithEvents oSyncCalendar As Office.CommandBarButton
Attribute oSyncCalendar.VB_VarHelpID = -1

Dim cmbControl As Office.CommandBarPopup

Friend Sub InitHandler(olApp As Outlook.Application, strProgID As String)
    On Error Resume Next
    'Dim objExplWrap As New clsExplWrap
    
    Set objOutlook = olApp
    Set golApp = olApp
    gstrProgID = strProgID

    If colExpl.Count >= 1 Then
        Set colExpl = objOutlook.Explorers
        AddExpl objOutlook.ActiveExplorer
        CBOutlookItems objOutlook.ActiveExplorer.CurrentFolder
        'objExplWrap.RefreshToolbar (objOutlook.ActiveExplorer.CurrentFolder)
    End If
    Call bLoadMessages
'Set objExplWrap = Nothing
End Sub
Friend Sub UnInitHandler()
    On Error Resume Next
    Set oHelpCmdBar = Nothing
    Set oLogin = Nothing
    Set oAddEmailsMenu = Nothing
    Set oAboutVt = Nothing
    Set oVtHelp = Nothing
    Set oVtForums = Nothing
    Set oPref = Nothing
    Set oLogout = Nothing
    Set oAddEmailsButton = Nothing
    Set oSContactsMenu = Nothing
    Set oSyncContacts = Nothing
    Set oSTaskMenu = Nothing
    Set oSyncTask = Nothing
    Set oSCalendarMenu = Nothing
    Set oSyncCalendar = Nothing
    Set cmbControl = Nothing
    Set cbrNewToolbar = Nothing
    Set colExpl = Nothing
    Set golApp = Nothing
    Set oOlApp = Nothing
    Set objOutlook = Nothing
End Sub
Private Sub colExpl_NewExplorer(ByVal Explorer As Outlook.Explorer)
    On Error Resume Next
    gblnNewExpl = True
    AddExpl Explorer
End Sub
Private Sub objOutlook_Startup()
    On Error Resume Next
    If colExpl.Count Then
        AddExpl objOutlook.ActiveExplorer
    End If
End Sub
Friend Sub CBOutlookItems(ByVal objFolder As Outlook.MAPIFolder)
    On Error GoTo ERROR_EXIT_ROUTINE
    
    Dim i As Integer
    Dim blnNew As Boolean
    Dim CBBTest
    
    For i = 1 To golApp.ActiveExplorer.CommandBars.Count
        If golApp.ActiveExplorer.CommandBars.Item(i).Name = "vtigerCRM" Then
            Set cbrNewToolbar = golApp.ActiveExplorer.CommandBars.Item(i)
        End If
    Next i
   
    If cbrNewToolbar Is Nothing Then
        blnNew = True
        Set cbrNewToolbar = golApp.ActiveExplorer.CommandBars.Add(Name:="vtigerCRM", Position:=msoBarTop, Temporary:=False)
    End If
    
    Set cmbControl = cbrNewToolbar.FindControl(Type:=msoControlPopup, Tag:="vtigerCRMMenu")
    If Not cmbControl Is Nothing Then
        cmbControl.Delete
        Set cmbControl = Nothing
    End If
    If cmbControl Is Nothing Then
        Set cmbControl = cbrNewToolbar.Controls.Add(Type:=msoControlPopup)
        cmbControl.Tag = "vtigerCRMMenu"
        cmbControl.OnAction = "<!" & gstrProgID & ">"
        cmbControl.Caption = "&vtigerCRM"
        
        For i = 1 To cmbControl.Controls.Count
            If cmbControl.Controls.Item(i).Tag = "Help" Then
                Set oHelpCmdBar = cmbControl.Controls.Item(i)
            End If
        Next i
        
        Set oLogin = CreateAddInPopupButton(gstrProgID, cmbControl, _
        "&Login", "Login", "Login to vtigerCRM", 0, False, msoButtonCaption)
        oLogin.Enabled = True
        
        Set oAddEmailsMenu = CreateAddInPopupButton(gstrProgID, cmbControl, _
        "&Associate With vtigerCRM", "AddToVtigerCRM", "Associate your Emails to vtigerCRM", 0, True, msoButtonIconAndCaption)
        oAddEmailsMenu.Enabled = False
        
        Set oSContactsMenu = CreateAddInPopupButton(gstrProgID, cmbControl, _
        "Sync &Contacts", "SyncCntsMenu", "Synchronize contacts with vtigerCRM", 0, True, msoButtonIconAndCaption)
        oSContactsMenu.Enabled = False
        
        Set oSTaskMenu = CreateAddInPopupButton(gstrProgID, cmbControl, _
        "Sync &Tasks", "SyncTaskMenu", "Synchronize Task with vtigerCRM", 0, False, msoButtonIconAndCaption)
        oSTaskMenu.Enabled = False

        Set oSCalendarMenu = CreateAddInPopupButton(gstrProgID, cmbControl, _
        "Sync &Calendar", "SyncClndrMenu", "Synchronize Calendar with vtigerCRM", 0, False, msoButtonIconAndCaption)
        oSCalendarMenu.Enabled = False
        
        Set oPref = CreateAddInPopupButton(gstrProgID, cmbControl, _
        "&Preferences", "Pref", "Preferences for vtigerCRM Add-In", 0, True, msoButtonCaption)
        oPref.Enabled = False
                    
        Set oLogout = CreateAddInPopupButton(gstrProgID, cmbControl, _
        "&Logout", "Logout", "Logout", 0, True, msoButtonCaption)
        oLogout.Enabled = True
        
        
        If oHelpCmdBar Is Nothing Then
            Set oHelpCmdBar = cmbControl.Controls.Add(Type:=msoControlPopup, Parameter:=7)
            With oHelpCmdBar
                .Caption = "&Help"
                .Tag = "Help"
                .BeginGroup = True
                .OnAction = "<!" & gstrProgID & ">"
            End With
        End If
            
        Set oVtHelp = CreateAddInPopupButton(gstrProgID, oHelpCmdBar, _
        "&vtigerCRM Addin Help", "vtigerHelp", "", 0, False, msoButtonCaption)
        
        Set oVtForums = CreateAddInPopupButton(gstrProgID, oHelpCmdBar, _
        "vtigerCRM Addin &Forums", "vtigerForums", "", 0, False, msoButtonCaption)
        
        Set oAboutVt = CreateAddInPopupButton(gstrProgID, oHelpCmdBar, _
        "&About vtigerCRM", "vtigerAbout", "", 0, True, msoButtonCaption)
        
    End If
        
    Set oAddEmailsButton = cbrNewToolbar.FindControl(Type:=msoControlButton, Tag:="AddEmailsButton")
    If Not oAddEmailsButton Is Nothing Then
        oAddEmailsButton.Delete
        Set oAddEmailsButton = Nothing
    End If
    Set oAddEmailsButton = CreateAddInCommandBarButton(gstrProgID, cbrNewToolbar, _
    "&Associate With vtigerCRM", "AddEmailsButton", "Associate your Emails to vtigerCRM", 0, True, msoButtonIconAndCaption)
    oAddEmailsButton.Enabled = False
    
    Set oSyncContacts = cbrNewToolbar.FindControl(Type:=msoControlButton, Tag:="SyncContactsButton")
    If Not oSyncContacts Is Nothing Then
        oSyncContacts.Delete
        Set oSyncContacts = Nothing
    End If
    Set oSyncContacts = CreateAddInCommandBarButton(gstrProgID, cbrNewToolbar, _
    "Sync &Contacts", "SyncContactsButton", "Synchronize Contacts with vtigerCRM", 0, True, msoButtonIconAndCaption)
    oSyncContacts.Enabled = False
    
    Set oSyncTask = cbrNewToolbar.FindControl(Type:=msoControlButton, Tag:="SyncTasksButton")
    If Not oSyncTask Is Nothing Then
        oSyncTask.Delete
        Set oSyncTask = Nothing
    End If
    Set oSyncTask = CreateAddInCommandBarButton(gstrProgID, cbrNewToolbar, _
    "Sync &Tasks", "SyncTasksButton", "Synchronize Tasks with vtigerCRM", 0, False, msoButtonIconAndCaption)
    oSyncTask.Enabled = False

    Set oSyncCalendar = cbrNewToolbar.FindControl(Type:=msoControlButton, Tag:="SyncCalendarButton")
    If Not oSyncCalendar Is Nothing Then
        oSyncCalendar.Delete
        Set oSyncCalendar = Nothing
    End If
    Set oSyncCalendar = CreateAddInCommandBarButton(gstrProgID, cbrNewToolbar, _
    "Sync &Calendar", "SyncCalendarButton", "Synchronize Calendar with vtigerCRM", 0, False, msoButtonIconAndCaption)
    oSyncCalendar.Enabled = False
    
    'Display toolbar if new
    If blnNew Then
        cbrNewToolbar.Visible = True
    End If
    GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    MsgBox Err.Description & Err.Source
EXIT_ROUTINE:
End Sub
'*******************************************************************
'Description: Methods to perform on click event from Button & Menu
'             from vtigerCRM ToolBar
'*******************************************************************
Private Sub oLogin_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmLogin.Show vbModal
End Sub

Private Sub oLogout_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    oLogin.Enabled = True
    oPref.Enabled = False
    oSCalendarMenu.Enabled = False
    oSTaskMenu.Enabled = False
    oSContactsMenu.Enabled = False
    oSyncCalendar.Enabled = False
    oSyncContacts.Enabled = False
    oSyncTask.Enabled = False
    oAddEmailsButton.Enabled = False
    oAddEmailsMenu.Enabled = False
End Sub

Private Sub oPref_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmPref.Show vbModal
End Sub

Private Sub oSContactsMenu_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    modSync.gsSyncModule = "CONTACTSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub

Private Sub oSyncContacts_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    modSync.gsSyncModule = "CONTACTSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub

Private Sub oSTaskMenu_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    modSync.gsSyncModule = "TASKSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub

Private Sub oSyncTask_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    modSync.gsSyncModule = "TASKSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub

Private Sub oSCalendarMenu_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    modSync.gsSyncModule = "CALENDARSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub

Private Sub oSyncCalendar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    modSync.gsSyncModule = "CALENDARSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub

Private Sub oAddEmailsButton_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmAddMsg.Show vbModal
End Sub

Private Sub oAddEmailsMenu_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmAddMsg.Show vbModal
End Sub

Private Sub oVtHelp_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    Sh_Execute ("http://www.vtiger.com/products/crm/document.html")
End Sub

Private Sub oVtForums_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmOlForum.Show vbModeless
End Sub

Private Sub oAboutVt_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmAbout.Show vbModeless
End Sub

