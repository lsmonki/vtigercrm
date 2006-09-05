VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{0ECD9B60-23AA-11D0-B351-00A0C9055D8E}#6.0#0"; "MSHFLXGD.OCX"
Object = "{BDC217C8-ED16-11CD-956C-0000C04E4C0A}#1.1#0"; "TABCTL32.OCX"
Begin VB.Form frmAddMsg 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "Add Message to vtigerCRM"
   ClientHeight    =   6585
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   8175
   BeginProperty Font 
      Name            =   "Tahoma"
      Size            =   8.25
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   Icon            =   "frmAddMsg.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   6585
   ScaleWidth      =   8175
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   615
      Left            =   0
      Picture         =   "frmAddMsg.frx":000C
      ScaleHeight     =   615
      ScaleWidth      =   8175
      TabIndex        =   12
      Top             =   0
      Width           =   8175
   End
   Begin VB.CommandButton cmdClose 
      Caption         =   "Close"
      Height          =   375
      Left            =   6480
      TabIndex        =   11
      Top             =   6120
      Width           =   1575
   End
   Begin VB.CommandButton cmdAddvtiger 
      Caption         =   "Add to vtigerCRM"
      Height          =   375
      Left            =   4320
      TabIndex        =   10
      Top             =   6120
      Width           =   1935
   End
   Begin TabDlg.SSTab SSTab1 
      Height          =   5295
      Left            =   120
      TabIndex        =   0
      Top             =   720
      Width           =   7935
      _ExtentX        =   13996
      _ExtentY        =   9340
      _Version        =   393216
      Style           =   1
      TabHeight       =   600
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Tahoma"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      TabCaption(0)   =   " Contacts"
      TabPicture(0)   =   "frmAddMsg.frx":1239
      Tab(0).ControlEnabled=   -1  'True
      Tab(0).Control(0)=   "txtAddress"
      Tab(0).Control(0).Enabled=   0   'False
      Tab(0).Control(1)=   "Label2"
      Tab(0).Control(1).Enabled=   0   'False
      Tab(0).Control(2)=   "Label1"
      Tab(0).Control(2).Enabled=   0   'False
      Tab(0).Control(3)=   "cmdSrchVtiger"
      Tab(0).Control(3).Enabled=   0   'False
      Tab(0).Control(4)=   "FlxGrdDtls1"
      Tab(0).Control(4).Enabled=   0   'False
      Tab(0).ControlCount=   5
      TabCaption(1)   =   " Edit Message"
      TabPicture(1)   =   "frmAddMsg.frx":12FF
      Tab(1).ControlEnabled=   0   'False
      Tab(1).Control(0)=   "txtMsg"
      Tab(1).Control(1)=   "Label3"
      Tab(1).ControlCount=   2
      TabCaption(2)   =   "Attachments"
      TabPicture(2)   =   "frmAddMsg.frx":13CB
      Tab(2).ControlEnabled=   0   'False
      Tab(2).Control(0)=   "FlxGrdDtls2"
      Tab(2).Control(1)=   "lblNote"
      Tab(2).Control(2)=   "Label4"
      Tab(2).ControlCount=   3
      Begin MSHierarchicalFlexGridLib.MSHFlexGrid FlxGrdDtls2 
         Height          =   3855
         Left            =   -74880
         TabIndex        =   8
         Top             =   960
         Width           =   7695
         _ExtentX        =   13573
         _ExtentY        =   6800
         _Version        =   393216
         FixedCols       =   0
         BackColorBkg    =   -2147483643
         GridColor       =   12632256
         WordWrap        =   -1  'True
         FocusRect       =   0
         HighLight       =   2
         ScrollBars      =   2
         SelectionMode   =   1
         _NumberOfBands  =   1
         _Band(0).Cols   =   2
      End
      Begin MSHierarchicalFlexGridLib.MSHFlexGrid FlxGrdDtls1 
         Height          =   3495
         Left            =   120
         TabIndex        =   5
         Top             =   1680
         Width           =   7695
         _ExtentX        =   13573
         _ExtentY        =   6165
         _Version        =   393216
         Cols            =   4
         FixedCols       =   0
         BackColorBkg    =   -2147483643
         GridColor       =   12632256
         FocusRect       =   0
         HighLight       =   2
         ScrollBars      =   2
         SelectionMode   =   1
         _NumberOfBands  =   1
         _Band(0).Cols   =   4
      End
      Begin VB.CommandButton cmdSrchVtiger 
         Caption         =   "Search in vtigerCRM"
         Height          =   375
         Left            =   5520
         TabIndex        =   2
         Top             =   840
         Width           =   2295
      End
      Begin VB.Label lblNote 
         Caption         =   "Note : Attachments with size less than 700KB can be uploaded."
         BeginProperty Font 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H000000FF&
         Height          =   255
         Left            =   -74880
         TabIndex        =   13
         Top             =   4920
         Width           =   7335
      End
      Begin VB.Label Label4 
         Caption         =   "Below is the attachments of current message."
         BeginProperty Font 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   -74880
         TabIndex        =   9
         Top             =   480
         Width           =   7695
      End
      Begin VB.Label Label3 
         Caption         =   "Below is the content of current message. You can edit the content before adding to vtigerCRM"
         BeginProperty Font 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   -74880
         TabIndex        =   7
         Top             =   480
         Width           =   7695
      End
      Begin MSForms.TextBox txtMsg 
         Height          =   4215
         Left            =   -74880
         TabIndex        =   6
         Top             =   960
         Width           =   7695
         VariousPropertyBits=   -1399830501
         ScrollBars      =   2
         Size            =   "13573;7435"
         FontName        =   "Tahoma"
         FontHeight      =   165
         FontCharSet     =   0
         FontPitchAndFamily=   2
      End
      Begin VB.Label Label1 
         Caption         =   "Search for Contacts in vtigerCRM"
         BeginProperty Font 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   120
         TabIndex        =   4
         Top             =   600
         Width           =   7695
      End
      Begin VB.Label Label2 
         Caption         =   "Select a Contact to Associate with Current Message"
         BeginProperty Font 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   120
         TabIndex        =   3
         Top             =   1440
         Width           =   7335
      End
      Begin MSForms.TextBox txtAddress 
         Height          =   375
         Left            =   120
         TabIndex        =   1
         Top             =   840
         Width           =   5175
         VariousPropertyBits=   746604571
         Size            =   "9128;661"
         FontName        =   "Tahoma"
         FontHeight      =   165
         FontCharSet     =   0
         FontPitchAndFamily=   2
      End
   End
End
Attribute VB_Name = "frmAddMsg"
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
Dim gsSubject As String
Dim gsDate As String
Dim gsMailId As String
Dim gsContactId As String
Dim gsMailBody As String
Dim gsSelectedTab As Integer
Private Sub cmdAddvtiger_Click()
On Error GoTo ERROR_EXIT_ROUTINE
    Dim sBinFile As String
    Dim sFile64Encode As String
    Dim sErrMsg As String
    Dim sEmailId As String
    Dim sFileType As String
    Dim sFileSize As String
    Dim oFS As New Scripting.FileSystemObject
    Dim oFolder As Folder
    Dim oFiles As File
    Dim objBASE64 As New Base64Class
    Dim oXMLDoc As New MSXML.DOMDocument
    Dim oXMLElmnt_Root As MSXML.IXMLDOMElement
    Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction


    If gsContactId <> "" Then
      
        Set oXMLInst = oXMLDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
        oXMLDoc.insertBefore oXMLInst, oXMLDoc.FirstChild
        
        Set oXMLElmnt_Root = oXMLDoc.createElement("msgdetails")
        Set oXMLDoc.documentElement = oXMLElmnt_Root

        Call AddChild(oXMLDoc, oXMLElmnt_Root, "subject", gsSubject)
        Call AddChild(oXMLDoc, oXMLElmnt_Root, "body", txtMsg.Text)
        Call AddChild(oXMLDoc, oXMLElmnt_Root, "datesent", gsDate)
        
        sEmailId = sAddMessageToContact(gsVtUserId, gsContactId, oXMLElmnt_Root)
      
        If sEmailId <> "" Then
            If oFS.FolderExists(gsVtUserFolder & "\Attachments") = True Then
                
                sErrMsg = "Error while getting attachemts"
                Set oFolder = oFS.GetFolder(gsVtUserFolder & "\Attachments")
                
                sErrMsg = "Error while preparing attachments to upload"
                For Each oFiles In oFolder.Files
                    sBinFile = String(FileLen(oFiles.Path), Chr(0))
                    Open oFiles.Path For Binary Access Read As #1
                        Get #1, , sBinFile
                    Close #1
                    sFile64Encode = objBASE64.EncodeString(sBinFile)
                    sFileSize = oFiles.Size
                    Call bAddEmailAttachment(sEmailId, oFiles.Name, sFile64Encode, sFileSize, sFileType)
                    oFiles.Delete (True)
                Next
                
            End If
            gsContactId = ""
            sMsgDlg ("Sucessfully added to vtigerCRM")
            LogTheMessage ("Successfully added message to vtiger EntityId:" & sEmailId)
        End If
    Else
      sMsgDlg ("Select a Contact and Add to vtigerCRM")
    End If
    
    GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    If sErrMsg <> "" Then
        sMsgDlg (sErrMsg)
    End If
    LogTheMessage ("cmdAddvtiger_Click" & Err.Description)
EXIT_ROUTINE:
    Set oFS = Nothing
    Set objBASE64 = Nothing
    Set oXMLDoc = Nothing
    Set oXMLElmnt_Root = Nothing
    Set oXMLInst = Nothing
End Sub

Private Sub cmdClose_Click()
    Unload Me
End Sub

Private Sub FlxGrdDtls1_Click()
On Error GoTo ERROR_EXIT_ROUTINE
    gsContactId = ""
    If FlxGrdDtls1.RowSel > 0 Then
                gsContactId = gsContactEntityId(FlxGrdDtls1.RowSel)
    End If
    GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    gsContactId = ""
EXIT_ROUTINE:
End Sub

Private Sub Form_Load()
    Call bInitializeLV
    Call MailInitialize
End Sub
Private Sub cmdSrchVtiger_Click()
On Error GoTo ERROR_EXIT_ROUTINE
Dim sReturnString As String
Dim sErrMsg As String
Dim sAppDataPath As String

If Trim(txtAddress.Text) = vbNull Then
    sMsgDlg ("Please provide contact name or email to search")
Else
    sReturnString = sVtigerSoContactSearch(gsVtUserId, Trim(txtAddress.Text))
    'sErrMsg = "Error while populating contact details"
    If Not bPopulateCntsList(sReturnString, FlxGrdDtls1) Then GoTo ERROR_EXIT_ROUTINE
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    If sErrMsg <> "" Then
        sMsgDlg (sErrMsg)
    End If
EXIT_ROUTINE:
End Sub
'Intialise the ListView
Private Function bInitializeLV() As Boolean

    On Error GoTo ERROR_EXIT_ROUTINE

    LogTheMessage ("Initializing ListView")
    
    FlxGrdDtls1.Row = 0
    FlxGrdDtls1.Col = 0
    FlxGrdDtls1.ColWidth(0) = 800
    FlxGrdDtls1.Text = "Type"
    FlxGrdDtls1.Col = 1
    FlxGrdDtls1.ColWidth(1) = 2500
    FlxGrdDtls1.Text = "Full Name"
    FlxGrdDtls1.Col = 2
    FlxGrdDtls1.ColWidth(2) = 1750
    FlxGrdDtls1.Text = "Company"
    FlxGrdDtls1.Col = 3
    FlxGrdDtls1.ColWidth(3) = 2600
    FlxGrdDtls1.Text = "Email Address"
    FlxGrdDtls1.GridLines = flexGridNone
    
    FlxGrdDtls2.Row = 0
    FlxGrdDtls2.Col = 0
    FlxGrdDtls2.ColWidth(0) = 5200
    FlxGrdDtls2.Text = "File Name"
    FlxGrdDtls2.Col = 1
    FlxGrdDtls2.ColWidth(1) = 2350
    FlxGrdDtls2.Text = "File Size"
    FlxGrdDtls2.GridLines = flexGridNone
    
    bInitializeLV = True
    LogTheMessage ("Initializing ListView Successfull")
    
    GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    LogTheMessage ("ERROR Initializing ListView - " & Err.Description)
    bInitializeLV = True
EXIT_ROUTINE:
End Function
'Intialize the Selected Mail and assign to global variables
Public Function MailInitialize()

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
Dim oActExplorer As Explorer
Dim oActSelection As Selection
Dim oMailItem As MailItem
Dim oItem As Object
Dim sMailId As String

LogTheMessage ("Initializing Mails")

If IsObject(oOlApp.Explorers) Then
    
    sErrMsg = gMsg005
    Set oActExplorer = oOlApp.ActiveExplorer
    If IsObject(oActExplorer) Then
    
        sErrMsg = gMsg006
        Set oActSelection = oActExplorer.Selection
        If oActSelection.Count = 1 Then
            For Each oItem In oActSelection
                If oItem.Class = olMail Then
                    Set oMailItem = oItem
                        
                        sErrMsg = "Error while getting address from selected mail"
                        If Not sGetMailId(oMailItem) Then GoTo ERROR_EXIT_ROUTINE
                        
                        txtAddress.Text = gsMailId
                        If Trim(oMailItem.HTMLBody) <> "" Then
                            txtMsg.Text = oMailItem.HTMLBody
                        Else
                            txtMsg.Text = oMailItem.Body
                        End If
                        
                        sErrMsg = "Error while getting attachments from selected mail"
                        If Not bGetAttachments(oMailItem) Then GoTo ERROR_EXIT_ROUTINE
                End If
            Next
        Else
                sMsgDlg ("Select a message to associate with vtigerCRM")
        End If
        
    End If
Else
    sMsgDlg ("Select a message to associate with vtigerCRM")
End If

LogTheMessage ("Initializing Mails Successful EntryId:" & oMailItem.EntryID)
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    If sErrMsg <> "" Then
        sMsgDlg (sErrMsg)
    End If
    LogTheMessage ("ERROR Initializing Mails - " & Err.Description)
EXIT_ROUTINE:
Set oActExplorer = Nothing
Set oActSelection = Nothing
Set oMailItem = Nothing
End Function

'Get mailid and datesent on the Selected Mail and assign to global variables
Private Function sGetMailId(ByVal oMailItem As MailItem) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim oParentFldr As MAPIFolder
 
If IsObject(oMailItem) Then
    Set oParentFldr = oMailItem.Parent
    If oParentFldr.Name = "OutBox" Or oParentFldr.Name = "Sent Items" Or oParentFldr.Name = "Drafts" Then
        gsMailId = oMailItem.Recipients.Item(1).Address
        gsSubject = oMailItem.Subject
        gsDate = Format(oMailItem.SentOn, DB_DATE_TIME_SEC_FORMAT)
    Else
        gsMailId = oMailItem.SentOnBehalfOfName
        gsSubject = oMailItem.Subject
        gsDate = Format(oMailItem.ReceivedTime, DB_DATE_TIME_SEC_FORMAT)
    End If
End If
sGetMailId = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    LogTheMessage ("ERROR Initializing Mail Address - " & Err.Description)
    sGetMailId = False
EXIT_ROUTINE:
    Set oParentFldr = Nothing
End Function
'get the attachments for the selecte mail
Private Function bGetAttachments(ByVal oMailItem As MailItem) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim oAttachments As Outlook.Attachments
Dim oAttachment As Outlook.Attachment
Dim oFS As New Scripting.FileSystemObject
Dim sFileSize As String
Dim i As Integer
Dim sVtUserFldr As String

LogTheMessage ("Initializing Attachments")

Set oAttachments = oMailItem.Attachments
sVtUserFldr = gsVtUserFolder & "\Attachments\"

If oAttachments.Count > 0 Then
    FlxGrdDtls2.Rows = oAttachments.Count + 1
    FlxGrdDtls2.GridLines = flexGridFlat
End If

For i = 1 To oAttachments.Count
    
    Set oAttachment = oAttachments.Item(i)
    
    If oFS.FolderExists(sVtUserFldr) = False Then
        oFS.CreateFolder (sVtUserFldr)
    End If
    
    'Save the file to temporary directory
    oAttachment.SaveAsFile (sVtUserFldr & oAttachment.FileName)
       
    'Caculate the file size
    sFileSize = FileSystem.FileLen(sVtUserFldr & oAttachment.FileName)
    If CDbl(sFileSize) < 1024 Then
        sFileSize = CStr(sFileSize) & " Bytes"
    ElseIf CDbl(sFileSize) > 1024 And CDbl(sFileSize) < 1024000 Then
        sFileSize = CStr(Round(CDbl(sFileSize) / 1024, 2)) & " KB"
    ElseIf CDbl(sFileSize) >= 1024000 Then
        sFileSize = CStr(Round(CDbl(sFileSize) / 1024000, 2)) & " MB"
        oFS.DeleteFile (sVtUserFldr & oAttachment.FileName)
    End If
    
    'list the file size & file name
    FlxGrdDtls2.Row = i
    Call AddFlxGrdRow(FlxGrdDtls2, 0, Trim(oAttachment.FileName))
    Call AddFlxGrdRow(FlxGrdDtls2, 1, Trim(sFileSize))

Next i

bGetAttachments = True
LogTheMessage ("Initializing Attachments Successfull")
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    'sMsgDlg (Err.Description)
    bGetAttachments = False
    LogTheMessage ("ERROR Initializing Attachments - " & Err.Description)
EXIT_ROUTINE:
Set oAttachments = Nothing
Set oAttachment = Nothing
End Function

Private Sub Form_Unload(Cancel As Integer)
Dim oFS As New Scripting.FileSystemObject
If oFS.FolderExists(gsVtUserFolder & "\Attachments") = True Then
    oFS.DeleteFolder gsVtUserFolder & "\Attachments", True
End If
Set oFS = Nothing
End Sub
