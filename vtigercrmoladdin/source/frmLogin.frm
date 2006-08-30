VERSION 5.00
Object = "{EAB22AC0-30C1-11CF-A7EB-0000C05BAE0B}#1.1#0"; "shdocvw.dll"
Begin VB.Form frmLogin 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "vtigerCRM Login"
   ClientHeight    =   3540
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   5175
   BeginProperty Font 
      Name            =   "Tahoma"
      Size            =   8.25
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   Icon            =   "frmLogin.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   3540
   ScaleWidth      =   5175
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.CommandButton cmdCancel 
      Caption         =   "Cancel"
      Height          =   375
      Left            =   3840
      TabIndex        =   5
      Top             =   3080
      Width           =   1215
   End
   Begin VB.CommandButton cmdOk 
      Caption         =   "&Login"
      Height          =   375
      Left            =   2400
      TabIndex        =   4
      Top             =   3080
      Width           =   1215
   End
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H80000008&
      Height          =   615
      Left            =   0
      Picture         =   "frmLogin.frx":000C
      ScaleHeight     =   615
      ScaleWidth      =   5175
      TabIndex        =   6
      Top             =   0
      Width           =   5175
   End
   Begin VB.Frame Frame1 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   2415
      Left            =   -120
      TabIndex        =   7
      Top             =   540
      Width           =   5415
      Begin VB.TextBox txtVtigerUrl 
         Height          =   300
         Left            =   1200
         TabIndex        =   2
         Top             =   1440
         Width           =   3975
      End
      Begin VB.CheckBox Check1 
         Caption         =   " Remember Me"
         Height          =   255
         Left            =   1200
         TabIndex        =   3
         Top             =   2080
         Width           =   3855
      End
      Begin VB.TextBox txtVtigerPwd 
         Height          =   300
         IMEMode         =   3  'DISABLE
         Left            =   1200
         PasswordChar    =   "*"
         TabIndex        =   1
         Top             =   960
         Width           =   3975
      End
      Begin VB.TextBox txtVtigerId 
         Height          =   300
         Left            =   1200
         TabIndex        =   0
         Top             =   480
         Width           =   3975
      End
      Begin VB.Label Label4 
         Caption         =   "E.g., http://en.vtiger.com"
         Height          =   255
         Left            =   1200
         TabIndex        =   12
         Top             =   1800
         Width           =   3855
      End
      Begin VB.Label Label3 
         Caption         =   "vtiger URL:"
         Height          =   255
         Left            =   240
         TabIndex        =   10
         Top             =   1440
         Width           =   1095
      End
      Begin VB.Label Label2 
         Caption         =   "Password:"
         Height          =   255
         Left            =   240
         TabIndex        =   9
         Top             =   960
         Width           =   1095
      End
      Begin VB.Label Label1 
         Caption         =   "User Name:"
         Height          =   255
         Left            =   240
         TabIndex        =   8
         Top             =   480
         Width           =   1095
      End
   End
   Begin SHDocVwCtl.WebBrowser WBrowser 
      Height          =   1215
      Left            =   480
      TabIndex        =   11
      Top             =   1320
      Width           =   1575
      ExtentX         =   2778
      ExtentY         =   2143
      ViewMode        =   0
      Offline         =   0
      Silent          =   0
      RegisterAsBrowser=   0
      RegisterAsDropTarget=   1
      AutoArrange     =   0   'False
      NoClientEdge    =   0   'False
      AlignLeft       =   0   'False
      NoWebView       =   0   'False
      HideFileNames   =   0   'False
      SingleClick     =   0   'False
      SingleSelection =   0   'False
      NoFolders       =   0   'False
      Transparent     =   0   'False
      ViewID          =   "{0057D0E0-3573-11CF-AE69-08002B2E1262}"
      Location        =   "http:///"
   End
End
Attribute VB_Name = "frmLogin"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
'********************************************************************************/
Option Explicit
Private Sub cmdCancel_Click()
    Unload frmLogin
End Sub
Private Sub cmdOk_Click()
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
Dim sMandatoryMsg As String
Dim Index As Integer
Dim sLoginReturn As String
Dim sState As String
sErrMsg = gMsg001
Dim oNewLogin As New MSXML.DOMDocument

    If (Trim(txtVtigerId.Text) = "") Then
        Index = Index + 1
        sMandatoryMsg = Index & ". User Name" & vbCrLf
    End If
    
    If (Trim(txtVtigerPwd.Text) = "") Then
        Index = Index + 1
        sMandatoryMsg = sMandatoryMsg & Index & ". Password"
    End If
    
    If sMandatoryMsg <> "" Then
        sMsgDlg ("The following fields are Mandatory" & vbCrLf & sMandatoryMsg)
    Else
        'By Using WebBrowser Control Open the URL specified in vtigerURL
        WBrowser.Navigate2 (Trim(txtVtigerUrl.Text))
        Do While WBrowser.readyState <> READYSTATE_COMPLETE
            Sleep 100
            DoEvents
        Loop
        
        oNewLogin.async = False
        
        'Check for WSDL to verify the vtigerURL
        If oNewLogin.Load(Trim(txtVtigerUrl.Text) & "/vtigerservice.php?service=outlook&wsdl") = True Then
        'If oNewLogin.Load(Trim(txtVtigerUrl.Text) & "/vtigerolservice.php?wsdl") = True Then
        
            gsVtUrl = Trim(txtVtigerUrl.Text) & "/vtigerservice.php?service=outlook"
            'gsVtUrl = Trim(txtVtigerUrl.Text) & "/vtigerolservice.php"
            
            sLoginReturn = sVtSoLogin(Trim(txtVtigerId.Text), Trim(txtVtigerPwd.Text))
            If Trim(sLoginReturn) <> "FALSE" Then
                'If the Login is Valid do the Configuration for the User
                Call DoUserConfiguration(Trim(txtVtigerId.Text))
                LogTheMessage ("Successfully logged in to vtigercrm")
                Unload Me
            Else
                sMsgDlg ("You must specify a valid username and password")
            End If
            
        Else
            sMsgDlg ("Unable to access the vtiger URL (" & Trim(txtVtigerUrl.Text) & ")")
        End If
    End If
    GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    If sErrMsg <> "" Then
        sMsgDlg (sErrMsg)
    End If
EXIT_ROUTINE:
Set oNewLogin = Nothing
End Sub
Public Function DoUserConfiguration(ByVal sVtigerUserId As String)
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
If sVtigerUserId <> "" Then
    
    sErrMsg = "Error while initializing the logfile"
    If bIntializeLogFile(sVtigerUserId) = False Then GoTo ERROR_EXIT_ROUTINE
    
    gsVtUrl = Trim(txtVtigerUrl.Text) & "/vtigerservice.php?service=outlook"
    'gsVtUrl = Trim(txtVtigerUrl.Text) & "/vtigerolservice.php"
    gsVtUserId = sVtigerUserId
    gsLoginSuccess = True
    
    LogTheMessage ("Initializing user details")
    'If Use checks on "Remember Me" Then save the userfile path in registry
    If Check1.Value = 1 Then
        
        sErrMsg = "Error while initializing the user configuration"
        If bInitUserDtls(sVtigerUserId, Trim(txtVtigerPwd.Text), Trim(txtVtigerUrl.Text), "1") = False Then GoTo ERROR_EXIT_ROUTINE
        
        sErrMsg = "Error while writing configuration to registry"
        If bSaveRegUserPath(gsVtUserFolder) = False Then GoTo ERROR_EXIT_ROUTINE
        
    Else
        sErrMsg = "Error while initializing the user configuration"
        If bInitUserDtls(sVtigerUserId, Trim(txtVtigerPwd.Text), Trim(txtVtigerUrl.Text), "0") = False Then GoTo ERROR_EXIT_ROUTINE
        
        sErrMsg = "Error while writing configuration to registry"
        If bSaveRegUserPath("") = False Then GoTo ERROR_EXIT_ROUTINE
        
    End If
    LogTheMessage ("Successfully logged in to vtiger")
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    If sErrMsg <> "" Then
        sMsgDlg (sErrMsg)
    End If
EXIT_ROUTINE:
End Function

Public Function bInitUserDtls(ByVal sVtUserId As String, _
                              ByVal sVtPwd As String, ByVal sVtUrl As String, _
                              ByVal sSaveType As String) As Boolean
                              
On Error GoTo ERROR_EXIT_ROUTINE

Dim oFS As New Scripting.FileSystemObject
Dim oXMLConfDoc As New MSXML.DOMDocument
Dim oXMLConfElement As MSXML.IXMLDOMElement
Dim oXMLElmnt_First As MSXML.IXMLDOMElement
Dim oXMLNode As MSXML.IXMLDOMNode
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction

If oFS.FileExists(gsVtUserFolder & VTIGER_USER_CONF) = True Then
    
    If oXMLConfDoc.Load(gsVtUserFolder & VTIGER_USER_CONF) = True Then
        Set oXMLConfElement = oXMLConfDoc.documentElement

        If Not oXMLConfElement.selectSingleNode("logindtls") Is Nothing Then
            
            Set oXMLElmnt_First = oXMLConfElement.selectSingleNode("logindtls")
            Call AddAttribute(oXMLElmnt_First, "type", sSaveType)
            oXMLConfElement.selectSingleNode("logindtls/uid").nodeTypedValue = sVtUserId
            oXMLConfElement.selectSingleNode("logindtls/pwd").nodeTypedValue = sVtPwd
            oXMLConfElement.selectSingleNode("logindtls/vturl").nodeTypedValue = sVtUrl
            
            oXMLConfDoc.Save (gsVtUserFolder & VTIGER_USER_CONF)
            
        End If
        
    End If
    
Else
    Set oXMLConfElement = oXMLConfDoc.createElement("vtuserdtls")
    Set oXMLConfDoc.documentElement = oXMLConfElement
    
    Set oXMLInst = oXMLConfDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
    oXMLConfDoc.insertBefore oXMLInst, oXMLConfDoc.FirstChild
    
    Set oXMLElmnt_First = oXMLConfDoc.createElement("logindtls")
    Set oXMLNode = oXMLConfElement.appendChild(oXMLElmnt_First)
    
    Call AddAttribute(oXMLElmnt_First, "type", sSaveType)
    Call AddChild(oXMLConfDoc, oXMLElmnt_First, "uid", sVtUserId)
    Call AddChild(oXMLConfDoc, oXMLElmnt_First, "pwd", sVtPwd)
    Call AddChild(oXMLConfDoc, oXMLElmnt_First, "vturl", sVtUrl)
    
    oXMLConfDoc.Save (gsVtUserFolder & VTIGER_USER_CONF)
    
End If
bInitUserDtls = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    bInitUserDtls = False
    LogTheMessage ("bInitUserDtls - " & Err.Description)
EXIT_ROUTINE:
    Set oFS = Nothing
    Set oXMLConfDoc = Nothing
    Set oXMLConfElement = Nothing
    Set oXMLInst = Nothing
    Set oXMLElmnt_First = Nothing
    Set oXMLNode = Nothing
End Function

Private Sub Form_Load()
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
Dim oFS As New Scripting.FileSystemObject
Dim oXMLConfDoc As New MSXML.DOMDocument
Dim oXMLConfElement As MSXML.IXMLDOMElement
Dim oXMLElmnt_First As MSXML.IXMLDOMElement
Dim oMapiFldr As MAPIFolder

If oFS.FileExists(bGetRegUserPath & VTIGER_USER_CONF) = True Then
    If oXMLConfDoc.Load(bGetRegUserPath & VTIGER_USER_CONF) = True Then
        Set oXMLConfElement = oXMLConfDoc.documentElement
        If Not oXMLConfElement.selectSingleNode("logindtls") Is Nothing Then
            Set oXMLElmnt_First = oXMLConfElement.selectSingleNode("logindtls")
            If oXMLElmnt_First.getAttribute("type") = 1 Then
                txtVtigerId.Text = oXMLConfElement.selectSingleNode("logindtls/uid").nodeTypedValue
                txtVtigerPwd.Text = oXMLConfElement.selectSingleNode("logindtls/pwd").nodeTypedValue
                txtVtigerUrl.Text = oXMLConfElement.selectSingleNode("logindtls/vturl").nodeTypedValue
                Check1.Value = 1
            End If
        End If
        If Not oXMLConfElement.selectSingleNode("syncpref") Is Nothing Then
            gsCntsSyncFolder = oXMLConfElement.selectSingleNode("syncpref/cntsfldr").nodeTypedValue
            gsTaskSyncFolder = oXMLConfElement.selectSingleNode("syncpref/taskfldr").nodeTypedValue
            gsClndrSyncFolder = oXMLConfElement.selectSingleNode("syncpref/calnfldr").nodeTypedValue
        Else
            Set oMapiFldr = oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderContacts)
            gsCntsSyncFolder = sGetPathAsString(oMapiFldr)
            
            Set oMapiFldr = oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderTasks)
            gsTaskSyncFolder = sGetPathAsString(oMapiFldr)
            
            Set oMapiFldr = oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderCalendar)
            gsClndrSyncFolder = sGetPathAsString(oMapiFldr)
        End If
        
        If Not oXMLConfElement.selectSingleNode("conflictpref") Is Nothing Then
            gsShowDlg = oXMLConfElement.selectSingleNode("conflictpref/showdlg").nodeTypedValue
            gsNtfyConflict = oXMLConfElement.selectSingleNode("conflictpref/ntfyconflict").nodeTypedValue
        Else
            gsShowDlg = 1
            gsNtfyConflict = 0
        End If
    End If
Else
    Set oMapiFldr = oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderContacts)
    gsCntsSyncFolder = sGetPathAsString(oMapiFldr)
    
    Set oMapiFldr = oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderTasks)
    gsTaskSyncFolder = sGetPathAsString(oMapiFldr)
    
    Set oMapiFldr = oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderCalendar)
    gsClndrSyncFolder = sGetPathAsString(oMapiFldr)
    
    gsShowDlg = 1
    gsNtfyConflict = 0
End If
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
EXIT_ROUTINE:
    Set oFS = Nothing
    Set oXMLConfDoc = Nothing
    Set oXMLConfElement = Nothing
    Set oXMLElmnt_First = Nothing
    Set oMapiFldr = Nothing
End Sub
Public Function bSaveRegUserPath(ByVal sUserPath As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

    SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_USER_PATH, sUserPath

bSaveRegUserPath = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
bSaveRegUserPath = False
LogTheMessage ("bSaveRegUserPath" & Err.Description)
EXIT_ROUTINE:
End Function
Public Function bGetRegUserPath() As String
On Error GoTo ERROR_EXIT_ROUTINE

bGetRegUserPath = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_USER_PATH)

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
bGetRegUserPath = ""
EXIT_ROUTINE:
End Function
