VERSION 5.00
Object = "{EAB22AC0-30C1-11CF-A7EB-0000C05BAE0B}#1.1#0"; "shdocvw.dll"
Begin VB.Form frmOlForum 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "vtigerCRM Outlook Discussions"
   ClientHeight    =   4800
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   6975
   BeginProperty Font 
      Name            =   "Tahoma"
      Size            =   8.25
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   Icon            =   "frmOlForum.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   4800
   ScaleWidth      =   6975
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.CommandButton Command1 
      Caption         =   "&Close"
      Height          =   375
      Left            =   5640
      TabIndex        =   3
      Top             =   4320
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
      Picture         =   "frmOlForum.frx":000C
      ScaleHeight     =   615
      ScaleWidth      =   6975
      TabIndex        =   1
      Top             =   0
      Width           =   6975
   End
   Begin VB.ListBox List1 
      Height          =   2985
      Left            =   120
      TabIndex        =   0
      Top             =   1080
      Width           =   6820
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
      Height          =   3675
      Left            =   -120
      TabIndex        =   2
      Top             =   540
      Width           =   7215
      Begin VB.Label Label1 
         Caption         =   "Select a forum topic to read the description "
         Height          =   255
         Left            =   240
         TabIndex        =   4
         Top             =   240
         Width           =   4920
      End
   End
   Begin SHDocVwCtl.WebBrowser WebBrowser1 
      Height          =   1215
      Left            =   0
      TabIndex        =   5
      Top             =   0
      Width           =   2535
      ExtentX         =   4471
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
      Location        =   ""
   End
End
Attribute VB_Name = "frmOlForum"
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
Dim oRSS As MSXML.DOMDocument
Public sZohoDesc As String
Public sMLeft As String
Public sMTop As String
Dim oItemList() As MSXML.IXMLDOMNode
Private Sub Command1_Click()
    Unload frmOlForum
End Sub
Private Sub Form_Load()
    On Error GoTo ERROR_EXIT_ROUTINE
    
    Dim oRSS As New MSXML.DOMDocument
    Dim oItems As MSXML.IXMLDOMNodeList
    Dim oXMLElement As MSXML.IXMLDOMElement
    Dim oNode As MSXML.IXMLDOMNode
    
    Dim sString As String
    Dim ret As Long
    Dim i As Integer
    
    Dim sXMLString As String
    
    WebBrowser1.Navigate2 ("http://www.vtiger.com")
    Do While WebBrowser1.readyState <> READYSTATE_COMPLETE
       Sleep 100
       DoEvents
    Loop
        
    oRSS.async = False
    '"http://forums.vtiger.com/rss.php?name=forums&file=rss&f=17"
    If oRSS.Load("http://forums.vtiger.com/rss.php?name=forums&file=rss&f=17") = False Then GoTo ERROR_EXIT_ROUTINE
    Set oItems = oRSS.selectNodes("rss/channel/item")
    i = -1
    ReDim oItemList(oItems.Length)
    
    For Each oNode In oItems
        i = i + 1
        List1.AddItem oNode.selectSingleNode("title").Text
        Set oItemList(i) = oNode
    Next oNode
    
    GoTo EXIT_ROUTINE
   'oXMLhttp.Open "GET", "http://forums/rss.php?name=forums&file=rss&sid=7104f388f74831dc809653bb3e7f401f", False, "sumanraj", "sumanraj"
   'oXMLhttp.setRequestHeader "Content-Type", "text/xml"
   'oXMLhttp.Send
   'sString = oXMLhttp.responseText
ERROR_EXIT_ROUTINE:
    sMsgDlg (Err.Description)
EXIT_ROUTINE:
      Set oItems = Nothing
      Set oRSS = Nothing
      Set oXMLElement = Nothing
      'Set oZohoHook = Nothing
End Sub

Private Sub List1_Click()
    Dim oNode As MSXML.IXMLDOMNode
    Set oNode = oItemList(List1.ListIndex)
    On Error Resume Next
    sZohoDesc = oNode.selectSingleNode("description").Text
    frmForumDesc.Show vbModal
End Sub

'Private Sub List1_MouseDown(Button As Integer, Shift As Integer, X As Single, Y As Single)
'sMTop = X
'sMLeft = Y
'End Sub
