VERSION 5.00
Begin {AC0714F6-3D04-11D1-AE7D-00A0C90F26F4} service 
   ClientHeight    =   9630
   ClientLeft      =   1740
   ClientTop       =   1545
   ClientWidth     =   10560
   _ExtentX        =   18627
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

'IDTExtensibility2 is the implementation interface required for COM Add-ins.
Implements IDTExtensibility2

'Commandbar Variables
Dim WithEvents oLogin As Office.CommandBarButton
Attribute oLogin.VB_VarHelpID = -1
Dim WithEvents oAddEmails As Office.CommandBarButton
Attribute oAddEmails.VB_VarHelpID = -1
Dim WithEvents oAboutCmdBar As Office.CommandBarButton
Attribute oAboutCmdBar.VB_VarHelpID = -1
Dim WithEvents ovtigerHelp As Office.CommandBarButton
Attribute ovtigerHelp.VB_VarHelpID = -1
Dim WithEvents ovtigerForums As Office.CommandBarButton
Attribute ovtigerForums.VB_VarHelpID = -1
Dim WithEvents oPref As Office.CommandBarButton
Attribute oPref.VB_VarHelpID = -1
Dim WithEvents oAddEmailsButton As Office.CommandBarButton
Attribute oAddEmailsButton.VB_VarHelpID = -1

Dim WithEvents oSyncMenuTasks As Office.CommandBarButton
Attribute oSyncMenuTasks.VB_VarHelpID = -1
Dim WithEvents oSyncMenuCalendar As Office.CommandBarButton
Attribute oSyncMenuCalendar.VB_VarHelpID = -1
Dim WithEvents oSyncMenuContacts As Office.CommandBarButton
Attribute oSyncMenuContacts.VB_VarHelpID = -1

Dim WithEvents oSyncTasks As Office.CommandBarButton
Attribute oSyncTasks.VB_VarHelpID = -1
Dim WithEvents oSyncCalendar As Office.CommandBarButton
Attribute oSyncCalendar.VB_VarHelpID = -1
Dim WithEvents oSyncContacts As Office.CommandBarButton
Attribute oSyncContacts.VB_VarHelpID = -1

Public WithEvents OlMailExplorers As Outlook.Explorer
Attribute OlMailExplorers.VB_VarHelpID = -1
Private WithEvents mOlApp As Outlook.Application
Attribute mOlApp.VB_VarHelpID = -1

Public FormDisplayed          As Boolean
Public VBInstance             As VBIDE.VBE
Dim mcbMenuCommandBar         As Office.CommandBarControl
Dim mfrmAddIn                 As New frmAddIn
Public WithEvents MenuHandler As CommandBarEvents          'command bar event handler
Attribute MenuHandler.VB_VarHelpID = -1
Sub Hide()
    
    On Error Resume Next
    
    FormDisplayed = False
    mfrmAddIn.Hide
   
End Sub
Sub Show()
  
    On Error Resume Next
    
    If mfrmAddIn Is Nothing Then
        Set mfrmAddIn = New frmAddIn
    End If
    
    Set mfrmAddIn.VBInstance = VBInstance
    Set mfrmAddIn.Connect = Me
    FormDisplayed = True
    mfrmAddIn.Show
   
End Sub
'------------------------------------------------------
'this method adds the Add-In to VB
'------------------------------------------------------
Private Sub AddinInstance_OnConnection(ByVal Application As Object, ByVal ConnectMode As AddInDesignerObjects.ext_ConnectMode, ByVal AddInInst As Object, custom() As Variant)
    On Error GoTo error_handler
    
    'save the vb instance
    Set VBInstance = Application
    
    'this is a good place to set a breakpoint and
    'test various addin objects, properties and methods
    Debug.Print VBInstance.FullName

    If ConnectMode = ext_cm_External Then
        'Used by the wizard toolbar to start this wizard
        Me.Show
    Else
        Set mcbMenuCommandBar = AddToAddInCommandBar("My AddIn")
        'sink the event
        Set Me.MenuHandler = VBInstance.Events.CommandBarEvents(mcbMenuCommandBar)
    End If
  
    If ConnectMode = ext_cm_AfterStartup Then
        If GetSetting(App.Title, "Settings", "DisplayOnConnect", "0") = "1" Then
            'set this to display the form on connect
            Me.Show
        End If
    End If
  
    Exit Sub
    
error_handler:
    
    MsgBox Err.Description
    
End Sub

'------------------------------------------------------
'this method removes the Add-In from VB
'------------------------------------------------------
Private Sub AddinInstance_OnDisconnection(ByVal RemoveMode As AddInDesignerObjects.ext_DisconnectMode, custom() As Variant)
    On Error Resume Next
    
    'delete the command bar entry
    mcbMenuCommandBar.Delete
    
    'shut down the Add-In
    If FormDisplayed Then
        SaveSetting App.Title, "Settings", "DisplayOnConnect", "1"
        FormDisplayed = False
    Else
        SaveSetting App.Title, "Settings", "DisplayOnConnect", "0"
    End If
    
    Unload mfrmAddIn
    Set mfrmAddIn = Nothing

End Sub

Private Sub IDTExtensibility_OnStartupComplete(custom() As Variant)
    If GetSetting(App.Title, "Settings", "DisplayOnConnect", "0") = "1" Then
        'set this to display the form on connect
        Me.Show
    End If
End Sub
'this event fires when the menu is clicked in the IDE
Private Sub MenuHandler_Click(ByVal CommandBarControl As Object, handled As Boolean, CancelDefault As Boolean)
    Me.Show
End Sub

Function AddToAddInCommandBar(sCaption As String) As Office.CommandBarControl
    Dim cbMenuCommandBar As Office.CommandBarControl  'command bar object
    Dim cbMenu As Object
  
    On Error GoTo AddToAddInCommandBarErr
    
    'see if we can find the Add-Ins menu
    Set cbMenu = VBInstance.CommandBars("Add-Ins")
    If cbMenu Is Nothing Then
        'not available so we fail
        Exit Function
    End If
    
    'add it to the command bar
    Set cbMenuCommandBar = cbMenu.Controls.Add(1)
    'set the caption
    cbMenuCommandBar.Caption = sCaption
    
    Set AddToAddInCommandBar = cbMenuCommandBar
    
    Exit Function
    
AddToAddInCommandBarErr:

End Function

Private Sub IDTExtensibility2_OnConnection(ByVal Application As Object, ByVal ConnectMode As AddInDesignerObjects.ext_ConnectMode, ByVal AddInInst As Object, custom() As Variant)
Set mOlApp = Application
Call IntiCmdBar(Application)
Call bLoadMessages
Set OlMailExplorers = mOlApp.ActiveExplorer

oAddEmails.Enabled = False
oAddEmailsButton.Enabled = False
oPref.Enabled = False
oSyncContacts.Enabled = False
oSyncMenuContacts.Enabled = False
oSyncMenuTasks.Enabled = False
oSyncMenuCalendar.Enabled = False
oSyncTasks.Enabled = False
oSyncCalendar.Enabled = False

gsLoginSuccess = False

''Call EnableDisableButtons
''Call bIntializeLogFile(gsVtUserId)
End Sub
Private Sub IDTExtensibility2_OnAddInsUpdate(custom() As Variant)
'MsgBox "IDTExtensibility2_OnAddInsUpdate"
End Sub
Private Sub IDTExtensibility2_OnBeginShutdown(custom() As Variant)
'MsgBox "IDTExtensibility2_OnBeginShutdown"
End Sub
Private Sub IDTExtensibility2_OnDisconnection(ByVal RemoveMode As AddInDesignerObjects.ext_DisconnectMode, custom() As Variant)
Set oOlApp = Nothing
'Commandbar Variables
Set oLogin = Nothing
Set oAddEmails = Nothing
Set oAboutCmdBar = Nothing
Set ovtigerHelp = Nothing
Set ovtigerForums = Nothing
Set OlMailExplorers = Nothing
Set mOlApp = Nothing
End Sub
Private Sub IDTExtensibility2_OnStartupComplete(custom() As Variant)
'MsgBox "IDTExtensibility2_OnStartupComplete"
End Sub
Public Sub EnableDisableButtons()
    If gsLoginSuccess = True Then
        If OlMailExplorers.CurrentFolder.DefaultItemType = olMailItem And OlMailExplorers.CurrentFolder.Name <> "Personal Folders" Then
            oAddEmails.Enabled = True
            oAddEmailsButton.Enabled = True
            oSyncContacts.Enabled = False
            oSyncMenuContacts.Enabled = False
            oSyncTasks.Enabled = False
            oSyncMenuTasks.Enabled = False
            oSyncCalendar.Enabled = False
            oSyncMenuCalendar.Enabled = False
        ElseIf sGetPathAsString(OlMailExplorers.CurrentFolder) = gsCntsSyncFolder Then
            gsCntsSyncFolderId = OlMailExplorers.CurrentFolder.EntryID
            oSyncContacts.Enabled = True
            oSyncMenuContacts.Enabled = True
            oSyncCalendar.Enabled = False
            oSyncMenuCalendar.Enabled = False
            oSyncTasks.Enabled = False
            oSyncMenuTasks.Enabled = False
            oAddEmails.Enabled = False
            oAddEmailsButton.Enabled = False
        ElseIf sGetPathAsString(OlMailExplorers.CurrentFolder) = gsTaskSyncFolder Then
            gsTaskSyncFolderId = OlMailExplorers.CurrentFolder.EntryID
            oSyncTasks.Enabled = True
            oSyncMenuTasks.Enabled = True
            oSyncContacts.Enabled = False
            oSyncMenuContacts.Enabled = False
            oSyncCalendar.Enabled = False
            oSyncMenuCalendar.Enabled = False
            oAddEmails.Enabled = False
            oAddEmailsButton.Enabled = False
        ElseIf sGetPathAsString(OlMailExplorers.CurrentFolder) = gsClndrSyncFolder Then
            gsClndrSyncFolderId = OlMailExplorers.CurrentFolder.EntryID
            oSyncCalendar.Enabled = True
            oSyncMenuCalendar.Enabled = True
            oSyncTasks.Enabled = False
            oSyncMenuTasks.Enabled = False
            oSyncContacts.Enabled = False
            oSyncMenuContacts.Enabled = False
            oAddEmails.Enabled = False
            oAddEmailsButton.Enabled = False
        Else
            oSyncCalendar.Enabled = False
            oSyncMenuCalendar.Enabled = False
            oSyncTasks.Enabled = False
            oSyncMenuTasks.Enabled = False
            oSyncContacts.Enabled = False
            oSyncMenuContacts.Enabled = False
            oAddEmails.Enabled = False
            oAddEmailsButton.Enabled = False
        End If
        oPref.Enabled = True
    End If
End Sub
Public Function IntiCmdBar(ByRef oApplication As Object)

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

Dim cmdBar As Office.CommandBar
Dim cmbControl As Office.CommandBarControl
Dim oHelpCmdBar As Office.CommandBarControl
Dim oDivider As Office.CommandBarControl

sErrMsg = "Cannot Intialize Outlook Application"
Set oOlApp = oApplication
Set cmdBar = oOlApp.ActiveExplorer.CommandBars.Add(Name:="vtigerCRM", Temporary:=True)

Set cmbControl = cmdBar.Controls.Add(msoControlPopup)
cmbControl.Caption = "vtige&rCRM"
 
Set oLogin = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=1, Temporary:=True)
With oLogin
.Caption = "&Login"
End With

Set oAddEmails = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=2, Temporary:=True)
With oAddEmails
.Caption = "&Add to vtigerCRM"
.BeginGroup = True
End With

Set oSyncMenuContacts = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=3, Temporary:=True)
With oSyncMenuContacts
.Caption = "Sync &Contacts"
.BeginGroup = True
End With

Set oSyncMenuTasks = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=3, Temporary:=True)
With oSyncMenuTasks
.Caption = "Sync &Tasks"
End With

Set oSyncMenuCalendar = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=3, Temporary:=True)
With oSyncMenuCalendar
.Caption = "Sync &Calendar"
End With

Set oPref = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=3, Temporary:=True)
With oPref
.Caption = "&Preferences"
.BeginGroup = True
End With

Set oHelpCmdBar = cmbControl.Controls.Add(Type:=msoControlPopup, Parameter:=4, Temporary:=True)
With oHelpCmdBar
.Caption = "&Help"
.BeginGroup = True
End With

Set ovtigerHelp = oHelpCmdBar.Controls.Add(Type:=msoControlButton, Parameter:=5, Temporary:=True)
With ovtigerHelp
.Caption = "&vtigerCRM Addin &Help"
End With

Set ovtigerForums = oHelpCmdBar.Controls.Add(Type:=msoControlButton, Parameter:=6, Temporary:=True)
With ovtigerForums
.Caption = "vtigerCRM Addin &Forums"
End With


Set oAboutCmdBar = oHelpCmdBar.Controls.Add(Type:=msoControlButton, Parameter:=7, Temporary:=True)
With oAboutCmdBar
.Caption = "&About vtigerCRM Addin"
.BeginGroup = True
End With

'cmdbar.
'Set oDivider = cmdBar.Controls.Add(msoControlExpandingGrid)

Set oAddEmailsButton = cmdBar.Controls.Add(msoControlButton)
oAddEmailsButton.Caption = "Add to vtigerCRM"
oAddEmailsButton.BeginGroup = True
oAddEmailsButton.Style = msoButtonIconAndCaption

Set oSyncContacts = cmdBar.Controls.Add(msoControlButton)
oSyncContacts.Caption = "Sync Contacts"
oSyncContacts.BeginGroup = True
oSyncContacts.Style = msoButtonIconAndCaption

Set oSyncTasks = cmdBar.Controls.Add(msoControlButton)
oSyncTasks.Caption = "Sync Tasks"
oSyncTasks.Style = msoButtonIconAndCaption

Set oSyncCalendar = cmdBar.Controls.Add(msoControlButton)
oSyncCalendar.Caption = "Sync Calendar"
oSyncCalendar.Style = msoButtonIconAndCaption

oOlApp.ActiveExplorer.CommandBars("vtigerCRM").Position = msoBarTop
oOlApp.ActiveExplorer.CommandBars("vtigerCRM").Visible = True
  
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    Call sMsgDlg(sErrMsg)
EXIT_ROUTINE:
    Set cmdBar = Nothing
    Set cmbControl = Nothing
    Set oHelpCmdBar = Nothing
End Function
Private Sub oAboutCmdBar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmAbout.Show vbModal
End Sub
Private Sub oAddEmails_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmAddMsg.Show vbModal
End Sub
Private Sub oAddEmailsButton_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmAddMsg.Show vbModal
End Sub
Private Sub OlMailExplorers_FolderSwitch()
    If gsLoginSuccess = True Then
        If OlMailExplorers.CurrentFolder.DefaultItemType = olMailItem And OlMailExplorers.CurrentFolder.Name <> "Personal Folders" Then
            oAddEmails.Enabled = True
            oAddEmailsButton.Enabled = True
            oSyncContacts.Enabled = False
            oSyncMenuContacts.Enabled = False
            oSyncTasks.Enabled = False
            oSyncMenuTasks.Enabled = False
            oSyncCalendar.Enabled = False
            oSyncMenuCalendar.Enabled = False
        ElseIf sGetPathAsString(OlMailExplorers.CurrentFolder) = gsCntsSyncFolder Then
            gsCntsSyncFolderId = OlMailExplorers.CurrentFolder.EntryID
            oSyncContacts.Enabled = True
            oSyncMenuContacts.Enabled = True
            oSyncCalendar.Enabled = False
            oSyncMenuCalendar.Enabled = False
            oSyncTasks.Enabled = False
            oSyncMenuTasks.Enabled = False
            oAddEmails.Enabled = False
            oAddEmailsButton.Enabled = False
        ElseIf sGetPathAsString(OlMailExplorers.CurrentFolder) = gsTaskSyncFolder Then
            gsTaskSyncFolderId = OlMailExplorers.CurrentFolder.EntryID
            oSyncTasks.Enabled = True
            oSyncMenuTasks.Enabled = True
            oSyncContacts.Enabled = False
            oSyncMenuContacts.Enabled = False
            oSyncCalendar.Enabled = False
            oSyncMenuCalendar.Enabled = False
            oAddEmails.Enabled = False
            oAddEmailsButton.Enabled = False
        ElseIf sGetPathAsString(OlMailExplorers.CurrentFolder) = gsClndrSyncFolder Then
            gsClndrSyncFolderId = OlMailExplorers.CurrentFolder.EntryID
            oSyncCalendar.Enabled = True
            oSyncMenuCalendar.Enabled = True
            oSyncTasks.Enabled = False
            oSyncMenuTasks.Enabled = False
            oSyncContacts.Enabled = False
            oSyncMenuContacts.Enabled = False
            oAddEmails.Enabled = False
            oAddEmailsButton.Enabled = False
        Else
            oSyncCalendar.Enabled = False
            oSyncMenuCalendar.Enabled = False
            oSyncTasks.Enabled = False
            oSyncMenuTasks.Enabled = False
            oSyncContacts.Enabled = False
            oSyncMenuContacts.Enabled = False
            oAddEmails.Enabled = False
            oAddEmailsButton.Enabled = False
        End If
    End If
End Sub
Private Sub oLogin_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    Load frmLogin
    frmLogin.Show vbModal
    If gsLoginSuccess = True Then
       Call EnableDisableButtons
    End If
End Sub
Private Sub oPref_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmPref.Show vbModal
    Call EnableDisableButtons
End Sub

Private Sub oSyncCalendar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    gsSyncModule = "CALENDARSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub

Private Sub oSyncContacts_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    gsSyncModule = "CONTACTSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub

Private Sub oSyncMenuCalendar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    gsSyncModule = "CALENDARSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub

Private Sub oSyncMenuContacts_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    gsSyncModule = "CONTACTSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub
Private Sub oSyncMenuTasks_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    gsSyncModule = "TASKSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub
Private Sub oSyncTasks_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    gsSyncModule = "TASKSYNC"
    frmSync.sSyncFlag = False
    frmSync.Show (vbModal)
End Sub
Private Sub ovtigerForums_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    frmOlForum.Show vbModal
End Sub
Private Sub ovtigerHelp_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
    Sh_Execute ("http://www.vtiger.com/products/crm/document.html")
End Sub
