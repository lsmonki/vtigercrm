VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{BDC217C8-ED16-11CD-956C-0000C04E4C0A}#1.1#0"; "TABCTL32.OCX"
Begin VB.Form frmPref 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "vtigerCRM Preferences"
   ClientHeight    =   6360
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   5775
   Icon            =   "frmPref.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   6360
   ScaleWidth      =   5775
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin TabDlg.SSTab SSTab1 
      Height          =   5055
      Left            =   120
      TabIndex        =   3
      Top             =   720
      Width           =   5535
      _ExtentX        =   9763
      _ExtentY        =   8916
      _Version        =   393216
      Style           =   1
      Tabs            =   1
      TabHeight       =   520
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Tahoma"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      TabCaption(0)   =   "Synchronization"
      TabPicture(0)   =   "frmPref.frx":000C
      Tab(0).ControlEnabled=   -1  'True
      Tab(0).Control(0)=   "txtCalFldr"
      Tab(0).Control(0).Enabled=   0   'False
      Tab(0).Control(1)=   "Label3"
      Tab(0).Control(1).Enabled=   0   'False
      Tab(0).Control(2)=   "txtTasksFldr"
      Tab(0).Control(2).Enabled=   0   'False
      Tab(0).Control(3)=   "Label2"
      Tab(0).Control(3).Enabled=   0   'False
      Tab(0).Control(4)=   "Label1"
      Tab(0).Control(4).Enabled=   0   'False
      Tab(0).Control(5)=   "txtCntsFolder"
      Tab(0).Control(5).Enabled=   0   'False
      Tab(0).Control(6)=   "cmdCalPick"
      Tab(0).Control(6).Enabled=   0   'False
      Tab(0).Control(7)=   "cmdTasksPick"
      Tab(0).Control(7).Enabled=   0   'False
      Tab(0).Control(8)=   "cmdCntsPick"
      Tab(0).Control(8).Enabled=   0   'False
      Tab(0).Control(9)=   "Frame1"
      Tab(0).Control(9).Enabled=   0   'False
      Tab(0).ControlCount=   10
      Begin VB.Frame Frame1 
         Caption         =   "Conflict Resolution Settings"
         BeginProperty Font 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   1935
         Left            =   120
         TabIndex        =   7
         Top             =   3000
         Width           =   5295
         Begin VB.CheckBox chkConflict 
            Caption         =   "Show conflict resolution dialog"
            BeginProperty Font 
               Name            =   "Tahoma"
               Size            =   8.25
               Charset         =   0
               Weight          =   400
               Underline       =   0   'False
               Italic          =   0   'False
               Strikethrough   =   0   'False
            EndProperty
            Height          =   255
            Left            =   120
            TabIndex        =   17
            Top             =   1560
            Width           =   5055
         End
         Begin VB.OptionButton optConflict 
            Caption         =   "vtigerCRM Wins"
            BeginProperty Font 
               Name            =   "Tahoma"
               Size            =   8.25
               Charset         =   0
               Weight          =   400
               Underline       =   0   'False
               Italic          =   0   'False
               Strikethrough   =   0   'False
            EndProperty
            Height          =   255
            Index           =   2
            Left            =   120
            TabIndex        =   10
            Top             =   1080
            Width           =   4935
         End
         Begin VB.OptionButton optConflict 
            Caption         =   "Microsoft Outlook Wins"
            BeginProperty Font 
               Name            =   "Tahoma"
               Size            =   8.25
               Charset         =   0
               Weight          =   400
               Underline       =   0   'False
               Italic          =   0   'False
               Strikethrough   =   0   'False
            EndProperty
            Height          =   255
            Index           =   1
            Left            =   120
            TabIndex        =   9
            Top             =   720
            Width           =   4935
         End
         Begin VB.OptionButton optConflict 
            Caption         =   "Notify when conflict occurs"
            BeginProperty Font 
               Name            =   "Tahoma"
               Size            =   8.25
               Charset         =   0
               Weight          =   400
               Underline       =   0   'False
               Italic          =   0   'False
               Strikethrough   =   0   'False
            EndProperty
            Height          =   255
            Index           =   0
            Left            =   120
            TabIndex        =   8
            Top             =   360
            Width           =   4935
         End
      End
      Begin VB.CommandButton cmdCntsPick 
         Height          =   325
         Left            =   4920
         TabIndex        =   6
         Top             =   780
         Width           =   495
      End
      Begin VB.CommandButton cmdTasksPick 
         Height          =   325
         Left            =   4920
         TabIndex        =   5
         Top             =   1635
         Width           =   495
      End
      Begin VB.CommandButton cmdCalPick 
         Height          =   325
         Left            =   4920
         TabIndex        =   4
         Top             =   2520
         Width           =   495
      End
      Begin MSForms.TextBox txtCntsFolder 
         Height          =   330
         Left            =   120
         TabIndex        =   16
         Top             =   780
         Width           =   4695
         VariousPropertyBits=   746604575
         BackColor       =   -2147483624
         Size            =   "8281;573"
         FontHeight      =   165
         FontCharSet     =   0
         FontPitchAndFamily=   2
      End
      Begin VB.Label Label1 
         Caption         =   "Choose Contact Folder for Synchronization"
         BeginProperty Font 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   120
         TabIndex        =   15
         Top             =   480
         Width           =   4695
      End
      Begin VB.Label Label2 
         Caption         =   "Choose Task Folder for Synchronization"
         BeginProperty Font 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   120
         TabIndex        =   14
         Top             =   1335
         Width           =   4695
      End
      Begin MSForms.TextBox txtTasksFldr 
         Height          =   330
         Left            =   120
         TabIndex        =   13
         Top             =   1635
         Width           =   4695
         VariousPropertyBits=   746604575
         BackColor       =   -2147483624
         Size            =   "8281;573"
         FontHeight      =   165
         FontCharSet     =   0
         FontPitchAndFamily=   2
      End
      Begin VB.Label Label3 
         Caption         =   "Choose Calendar Folder for Synchronization"
         BeginProperty Font 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   120
         TabIndex        =   12
         Top             =   2220
         Width           =   4695
      End
      Begin MSForms.TextBox txtCalFldr 
         Height          =   330
         Left            =   120
         TabIndex        =   11
         Top             =   2520
         Width           =   4695
         VariousPropertyBits=   746604575
         BackColor       =   -2147483624
         Size            =   "8281;573"
         FontHeight      =   165
         FontCharSet     =   0
         FontPitchAndFamily=   2
      End
   End
   Begin VB.CommandButton Command1 
      Caption         =   "&Close"
      BeginProperty Font 
         Name            =   "Tahoma"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   4440
      TabIndex        =   2
      Top             =   5880
      Width           =   1215
   End
   Begin VB.CommandButton OKButton 
      Caption         =   "&OK"
      BeginProperty Font 
         Name            =   "Tahoma"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3120
      TabIndex        =   1
      Top             =   5880
      Width           =   1215
   End
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "Tahoma"
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
      Picture         =   "frmPref.frx":0028
      ScaleHeight     =   615
      ScaleWidth      =   5895
      TabIndex        =   0
      Top             =   0
      Width           =   5895
   End
End
Attribute VB_Name = "frmPref"
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
' ********************************************************************************/
Option Explicit

Private Sub cmdCalPick_Click()
On Error GoTo ERROR_EXIT_ROUTINE
Dim ObjMapiFldr As MAPIFolder
Dim sFldrPath As String
Dim ObjNS As Outlook.NameSpace

Set ObjNS = Application.GetNamespace("MAPI")
Set ObjMapiFldr = ObjNS.PickFolder

If (ObjMapiFldr.DefaultItemType <> olAppointmentItem) Then
    sMsgDlg ("Select your Calendar folder for synchronization")
    GoTo EXIT_ROUTINE
End If

sFldrPath = sGetPathAsString(ObjMapiFldr)
txtCalFldr.Text = sFldrPath
gsClndrSyncFolder = txtCalFldr.Text

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    LogTheMessage (Err.Description)
EXIT_ROUTINE:
Set ObjMapiFldr = Nothing
Set ObjNS = Nothing
End Sub
Private Sub cmdCntsPick_Click()
On Error GoTo ERROR_EXIT_ROUTINE
Dim ObjMapiFldr As MAPIFolder
Dim sFldrPath As String
Dim ObjNS As Outlook.NameSpace

Set ObjNS = Application.GetNamespace("MAPI")
Set ObjMapiFldr = ObjNS.PickFolder

If (ObjMapiFldr.DefaultItemType <> olContactItem) Then
    sMsgDlg ("Select your Contact folder for synchronization")
    GoTo EXIT_ROUTINE
End If

sFldrPath = sGetPathAsString(ObjMapiFldr)
txtCntsFolder.Text = sFldrPath
gsCntsSyncFolder = txtCntsFolder.Text
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    LogTheMessage (Err.Description)
EXIT_ROUTINE:
Set ObjMapiFldr = Nothing
Set ObjNS = Nothing
End Sub
Private Sub cmdTasksPick_Click()
On Error GoTo ERROR_EXIT_ROUTINE
Dim ObjMapiFldr As MAPIFolder
Dim sFldrPath As String
Dim ObjNS As Outlook.NameSpace

Set ObjNS = Application.GetNamespace("MAPI")
Set ObjMapiFldr = ObjNS.PickFolder

If (ObjMapiFldr.DefaultItemType <> olTaskItem) Then
    sMsgDlg ("Select your Task folder for synchronization")
    GoTo EXIT_ROUTINE
End If

sFldrPath = sGetPathAsString(ObjMapiFldr)
txtTasksFldr.Text = sFldrPath
gsTaskSyncFolder = txtTasksFldr.Text
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    LogTheMessage (Err.Description)
EXIT_ROUTINE:
Set ObjMapiFldr = Nothing
Set ObjNS = Nothing
End Sub
Private Sub Command1_Click()
    Unload Me
End Sub
Private Sub Form_Load()
Dim oMapiFldr As MAPIFolder
If gsCntsSyncFolder = "" Then
    Set oMapiFldr = oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderContacts)
    txtCntsFolder.Text = sGetPathAsString(oMapiFldr)
    gsCntsSyncFolder = txtCntsFolder.Text
Else
    txtCntsFolder.Text = gsCntsSyncFolder
End If
If gsTaskSyncFolder = "" Then
    Set oMapiFldr = oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderTasks)
    txtTasksFldr.Text = sGetPathAsString(oMapiFldr)
    gsTaskSyncFolder = txtTasksFldr.Text
Else
    txtTasksFldr.Text = gsTaskSyncFolder
End If
If gsClndrSyncFolder = "" Then
    Set oMapiFldr = oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderCalendar)
    txtCalFldr.Text = sGetPathAsString(oMapiFldr)
    gsClndrSyncFolder = txtCalFldr.Text
Else
    txtCalFldr.Text = gsClndrSyncFolder
End If

If gsShowDlg = 1 Then
    chkConflict.Value = 1
End If

If gsNtfyConflict = 0 Then
    optConflict(0).Value = True
ElseIf gsNtfyConflict = 1 Then
    optConflict(1).Value = True
ElseIf gsNtfyConflict = 2 Then
    optConflict(2).Value = True
End If

Set oMapiFldr = Nothing
End Sub

Private Sub OKButton_Click()
On Error GoTo ERROR_EXIT_ROUTINE
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmnt_First As MSXML.IXMLDOMElement
Dim oXMLNode As MSXML.IXMLDOMNode
Dim oXMLConfElement As MSXML.IXMLDOMElement
Dim oXMLConfDoc As New MSXML.DOMDocument

    If chkConflict.Value = 1 Then
        gsShowDlg = 1
    Else
        gsShowDlg = 0
    End If
    
    If optConflict(0).Value = True Then
        gsNtfyConflict = 0
        gsShowDlg = 1
    ElseIf optConflict(1).Value = True Then
        gsNtfyConflict = 1
    ElseIf optConflict(2).Value = True Then
        gsNtfyConflict = 2
    End If
    
    If oXMLConfDoc.Load(gsVtUserFolder & VTIGER_USER_CONF) = True Then
        Set oXMLConfElement = oXMLConfDoc.documentElement
        
        If Not oXMLConfElement.selectSingleNode("syncpref") Is Nothing Then
            oXMLConfElement.selectSingleNode("syncpref/cntsfldr").nodeTypedValue = gsCntsSyncFolder
            oXMLConfElement.selectSingleNode("syncpref/taskfldr").nodeTypedValue = gsTaskSyncFolder
            oXMLConfElement.selectSingleNode("syncpref/calnfldr").nodeTypedValue = gsClndrSyncFolder
        Else
            Set oXMLElmnt_First = oXMLDoc.createElement("syncpref")
            Set oXMLNode = oXMLConfElement.appendChild(oXMLElmnt_First)
            Call AddChild(oXMLDoc, oXMLElmnt_First, "cntsfldr", gsCntsSyncFolder)
            Call AddChild(oXMLDoc, oXMLElmnt_First, "taskfldr", gsTaskSyncFolder)
            Call AddChild(oXMLDoc, oXMLElmnt_First, "calnfldr", gsClndrSyncFolder)
        End If
        
        If Not oXMLConfElement.selectSingleNode("conflictpref") Is Nothing Then
            oXMLConfElement.selectSingleNode("conflictpref/showdlg").nodeTypedValue = gsShowDlg
            oXMLConfElement.selectSingleNode("conflictpref/ntfyconflict").nodeTypedValue = gsNtfyConflict
        Else
            Set oXMLElmnt_First = oXMLDoc.createElement("conflictpref")
            Set oXMLNode = oXMLConfElement.appendChild(oXMLElmnt_First)
            Call AddChild(oXMLDoc, oXMLElmnt_First, "showdlg", gsShowDlg)
            Call AddChild(oXMLDoc, oXMLElmnt_First, "ntfyconflict", gsNtfyConflict)
        End If
        oXMLConfDoc.Save (gsVtUserFolder & VTIGER_USER_CONF)
    End If
    
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
sMsgDlg (Err.Description)
EXIT_ROUTINE:
Set oXMLDoc = Nothing
Set oXMLElmnt_First = Nothing
Set oXMLNode = Nothing
Set oXMLConfElement = Nothing
Set oXMLConfDoc = Nothing
Unload Me
End Sub

Private Sub optConflict_Click(Index As Integer)
If Index = 0 Then
    chkConflict.Value = 1
End If
End Sub
