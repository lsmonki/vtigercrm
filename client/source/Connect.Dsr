VERSION 5.00
Begin {AC0714F6-3D04-11D1-AE7D-00A0C90F26F4} Connect 
   ClientHeight    =   9495
   ClientLeft      =   1740
   ClientTop       =   1545
   ClientWidth     =   12300
   _ExtentX        =   21696
   _ExtentY        =   16748
   _Version        =   393216
   Description     =   "vtigerCRM MSOffice Add-In"
   DisplayName     =   "vtigerCRM MSOffice Add-In"
   AppName         =   "Microsoft Word"
   AppVer          =   "Microsoft Word 9.0"
   LoadName        =   "Startup"
   LoadBehavior    =   3
   RegLocation     =   "HKEY_CURRENT_USER\Software\Microsoft\Office\Word"
End
Attribute VB_Name = "Connect"
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
' * All Rights Reserved.
'*
' ********************************************************************************/
Option Explicit

'IDTExtensibility2 is the implementation interface required for COM Add-ins.
Implements IDTExtensibility2

'Default Add-In Variables
Public FormDisplayed          As Boolean
Public VBInstance             As VBIDE.VBE
Dim mcbMenuCommandBar         As Office.CommandBarControl
Dim mfrmAddIn                 As New frmvtigerLogin
Public WithEvents MenuHandler As CommandBarEvents
Attribute MenuHandler.VB_VarHelpID = -1

'WordApplication variables
Dim oWordApp As New Word.Application

'Commandbar Variables
Dim WithEvents oLoginCmdBar As Office.CommandBarButton
Attribute oLoginCmdBar.VB_VarHelpID = -1
Dim WithEvents oConfCmdBar As Office.CommandBarButton
Attribute oConfCmdBar.VB_VarHelpID = -1
Dim WithEvents oHelpCmdBar As Office.CommandBarButton
Attribute oHelpCmdBar.VB_VarHelpID = -1
Dim WithEvents oLogoutCmdBar As Office.CommandBarButton
Attribute oLogoutCmdBar.VB_VarHelpID = -1
Dim WithEvents oInsertMergerCmdBar As Office.CommandBarButton
Attribute oInsertMergerCmdBar.VB_VarHelpID = -1
Dim WithEvents oAboutCmdBar As Office.CommandBarButton
Attribute oAboutCmdBar.VB_VarHelpID = -1

Private Sub IDTExtensibility2_OnAddInsUpdate(custom() As Variant)

End Sub

Private Sub IDTExtensibility2_OnStartupComplete(custom() As Variant)
    If GetSetting(App.Title, "Settings", "DisplayOnConnect", "0") = "1" Then
        'set this to display the form on connect
        Me.Show
    End If
End Sub
Private Sub IDTExtensibility2_OnBeginShutdown(custom() As Variant)
Set oWordAppObj = Nothing
Set oWordApp = Nothing
Set oLoginCmdBar = Nothing
Set oConfCmdBar = Nothing
Set oHelpCmdBar = Nothing
Set oLogoutCmdBar = Nothing
Set oInsertMergerCmdBar = Nothing
Set oAboutCmdBar = Nothing
End Sub

Private Sub IDTExtensibility2_OnConnection(ByVal Application As Object, ByVal ConnectMode As AddInDesignerObjects.ext_ConnectMode, ByVal AddInInst As Object, custom() As Variant)
On Error GoTo ERROR_EXIT_ROUTINE

Set oWordAppObj = Application

If Not bCreateRegPath() Then GoTo ERROR_EXIT_ROUTINE

If Not bGetRegInitValues() Then GoTo ERROR_EXIT_ROUTINE

If Not bGetRegKeyValues() Then GoTo ERROR_EXIT_ROUTINE

If Not bLoadLang() Then GoTo ERROR_EXIT_ROUTINE

Call IntiCmdBar(Application)
ERROR_EXIT_ROUTINE:
EXIT_ROUTINE:
End Sub

Private Sub IDTExtensibility2_OnDisconnection(ByVal RemoveMode As AddInDesignerObjects.ext_DisconnectMode, custom() As Variant)
Set oWordAppObj = Nothing
Set oWordApp = Nothing
Set oLoginCmdBar = Nothing
Set oConfCmdBar = Nothing
Set oHelpCmdBar = Nothing
Set oLogoutCmdBar = Nothing
Set oInsertMergerCmdBar = Nothing
Set oAboutCmdBar = Nothing
End Sub
'this event fires when the menu is clicked in the IDE
Private Sub MenuHandler_Click(ByVal CommandBarControl As Object, handled As Boolean, CancelDefault As Boolean)
    Me.Show
End Sub

Sub Hide()
    
    On Error Resume Next
    
    FormDisplayed = False
    mfrmAddIn.Hide
   
End Sub

Sub Show()
  
    On Error Resume Next
    
    If mfrmAddIn Is Nothing Then
        Set mfrmAddIn = New frmvtigerLogin
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


Private Sub oAboutCmdBar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
frmAbout.Show vbModal
End Sub

Private Sub oConfCmdBar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
frmConf.Show vbModal
End Sub

Public Function IntiCmdBar(ByRef oApplication As Object)

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

Dim cmbControl As Office.CommandBarControl
'Dim lblContrl As Office.CommandBarButton

sErrMsg = gMsg006

Set oWordApp = oApplication
'oWordApp.CommandBars("vtigerCRM Word Add-In").Reset
oWordApp.CommandBars.Add Name:="vtigerCRM Word Add-In", Temporary:=True

''Set lblContrl = oWordApp.CommandBars("vtigerCRM Word Add-In").Controls.Add(Type:=msoControlLabel, Temporary:=True)
''lblContrl.Caption = "vtiger.com"
''lblContrl.PasteFace

Set cmbControl = oWordApp.CommandBars("vtigerCRM Word Add-In").Controls.Add(Type:=msoControlPopup, Temporary:=True)
cmbControl.Caption = gMenuTitle

Set oLoginCmdBar = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=1, Temporary:=True)
With oLoginCmdBar
.Caption = gLogin
End With

Set oLogoutCmdBar = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=2, Temporary:=True)
With oLogoutCmdBar
.Caption = gLogout
.Enabled = False
End With

Set oInsertMergerCmdBar = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=3, Temporary:=True)
With oInsertMergerCmdBar
.Caption = gMailMerge
.BeginGroup = True
.Enabled = False
End With

Set oConfCmdBar = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=4, Temporary:=True)
With oConfCmdBar
.Caption = gConfig
.BeginGroup = True
End With

Set oHelpCmdBar = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=5, Temporary:=True)
With oHelpCmdBar
.Caption = gHelp
.Enabled = False
End With

Set oAboutCmdBar = cmbControl.Controls.Add(Type:=msoControlButton, Parameter:=6, Temporary:=True)
With oAboutCmdBar
.Caption = gAbout
.BeginGroup = True
End With

oWordApp.CommandBars("vtigerCRM Word Add-In").Position = msoBarTop
oWordApp.CommandBars("vtigerCRM Word Add-In").Visible = True
  
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
Call sErrorDlg(sErrMsg)

EXIT_ROUTINE:
End Function

Private Sub oHelpCmdBar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
Sh_Execute ("http://www.vtiger.com/products/crm/document.html")
End Sub

Private Sub oInsertMergerCmdBar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)
If Not bGetFieldValues Then GoTo ERROR_EXIT_ROUTINE
If Not bGetFieldValues_Acnt Then GoTo ERROR_EXIT_ROUTINE
If Not bGetFieldValues_Lead Then GoTo ERROR_EXIT_ROUTINE
If Not bGetFieldValues_Tickets Then GoTo ERROR_EXIT_ROUTINE
If Not bGetFieldValues_User Then GoTo ERROR_EXIT_ROUTINE
frmvtigerMerge.Show vbModal
ERROR_EXIT_ROUTINE:
End Sub

Private Sub oLoginCmdBar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)

If Not bLogInvtigerCRM() Then GoTo ERROR_EXIT_ROUTINE

oLoginCmdBar.Enabled = False
oInsertMergerCmdBar.Enabled = True
oLogoutCmdBar.Enabled = True
oHelpCmdBar.Enabled = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:

EXIT_ROUTINE:

End Sub

Private Sub oLogoutCmdBar_Click(ByVal Ctrl As Office.CommandBarButton, CancelDefault As Boolean)

If Not bLogOutvtigerCRM() Then GoTo ERROR_EXIT_ROUTINE

oLoginCmdBar.Enabled = True
oInsertMergerCmdBar.Enabled = False
oLogoutCmdBar.Enabled = False
oHelpCmdBar.Enabled = False
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:

EXIT_ROUTINE:

End Sub
