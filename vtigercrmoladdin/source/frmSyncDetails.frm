VERSION 5.00
Object = "{0ECD9B60-23AA-11D0-B351-00A0C9055D8E}#6.0#0"; "MSHFLXGD.OCX"
Begin VB.Form frmSyncDetails 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "Sync Changes"
   ClientHeight    =   6960
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   6960
   BeginProperty Font 
      Name            =   "Tahoma"
      Size            =   8.25
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   Icon            =   "frmSyncDetails.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   6960
   ScaleWidth      =   6960
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFFF&
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
      Picture         =   "frmSyncDetails.frx":000C
      ScaleHeight     =   615
      ScaleWidth      =   7095
      TabIndex        =   1
      Top             =   0
      Width           =   7095
   End
   Begin VB.CommandButton Command1 
      Caption         =   "&Close"
      Height          =   375
      Left            =   5640
      TabIndex        =   0
      Top             =   6480
      Width           =   1215
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
      Height          =   5655
      Left            =   -120
      TabIndex        =   2
      Top             =   600
      Width           =   7215
      Begin MSHierarchicalFlexGridLib.MSHFlexGrid FlxGrdOl 
         Height          =   2055
         Left            =   240
         TabIndex        =   6
         Top             =   480
         Width           =   6735
         _ExtentX        =   11880
         _ExtentY        =   3625
         _Version        =   393216
         Cols            =   4
         FixedCols       =   0
         BackColorBkg    =   -2147483643
         GridColor       =   -2147483644
         FocusRect       =   0
         HighLight       =   2
         ScrollBars      =   2
         SelectionMode   =   1
         _NumberOfBands  =   1
         _Band(0).Cols   =   4
      End
      Begin MSHierarchicalFlexGridLib.MSHFlexGrid FlxGrdVt 
         Height          =   2055
         Left            =   240
         TabIndex        =   5
         Top             =   3000
         Width           =   6735
         _ExtentX        =   11880
         _ExtentY        =   3625
         _Version        =   393216
         Cols            =   4
         FixedCols       =   0
         BackColorBkg    =   -2147483643
         GridColor       =   -2147483644
         FocusRect       =   0
         HighLight       =   2
         ScrollBars      =   2
         SelectionMode   =   1
         _NumberOfBands  =   1
         _Band(0).Cols   =   4
      End
      Begin VB.Label Label1 
         Caption         =   "Note:Records in red color will not be synchronised as some of the mandatory fields are missing"
         ForeColor       =   &H000000FF&
         Height          =   495
         Left            =   240
         TabIndex        =   7
         Top             =   5040
         Width           =   6735
      End
      Begin VB.Label lblVtiger 
         Caption         =   "The following changes will be applied to your vtigerCRM Contacts"
         Height          =   255
         Left            =   240
         TabIndex        =   4
         Top             =   2760
         Width           =   4920
      End
      Begin VB.Label lblOutlook 
         Caption         =   "The following changes will be applied to your Contacts in Outlook"
         Height          =   255
         Left            =   240
         TabIndex        =   3
         Top             =   240
         Width           =   4920
      End
   End
End
Attribute VB_Name = "frmSyncDetails"
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

Private Type OlContactDtls
    sStatus As String
    sFullName As String
    sLastName As String
    sCompanyName As String
    sEmailId As String
End Type

Private Type OlTaskDtls
    sStatus As String
    sSubject As String
    sStartDate As String
    sEndDate As String
End Type

Private Type OlClndrDtls
    sStatus As String
    sSubject As String
    sStartDate As String
    sEndDate As String
End Type

Private Type VtContactDtls
    sStatus As String
    sFullName As String
    sCompanyName As String
    sEmailId As String
End Type

Private Type VtTaskDtls
    sStatus As String
    sSubject As String
    sStartDate As String
    sEndDate As String
End Type

Private Type VtClndrDtls
    sStatus As String
    sSubject As String
    sStartDate As String
    sEndDate As String
End Type

Dim aOlContacts() As OlContactDtls
Dim aVtContacts() As VtContactDtls
Dim aOlTasks() As OlTaskDtls
Dim aVtTasks() As VtTaskDtls
Dim aOlClndr() As OlClndrDtls
Dim aVtClndr() As VtClndrDtls
Private Sub Command1_Click()
    Unload Me
End Sub

Private Sub Form_Load()
Dim i As Integer
Dim sFlag As Boolean
Call bInitListViews
sFlag = False

If bPopOlListView = True Then
    If gsSyncModule = "CONTACTSYNC" Then
        FlxGrdVt.Rows = UBound(aOlContacts) + 1
        FlxGrdVt.GridLines = flexGridFlat
        For i = 0 To UBound(aOlContacts) - 1
            FlxGrdVt.Row = i + 1
            If aOlContacts(i).sLastName = "" Then
                FlxGrdVt.Col = 0
                FlxGrdVt.CellBackColor = &HFF
                FlxGrdVt.CellForeColor = &HFFFFFF
                FlxGrdVt.Text = "Ignore"
                FlxGrdVt.Col = 1
                FlxGrdVt.CellBackColor = &HFF
                FlxGrdVt.CellForeColor = &HFFFFFF
                FlxGrdVt.Text = aOlContacts(i).sFullName
                FlxGrdVt.Col = 2
                FlxGrdVt.CellBackColor = &HFF
                FlxGrdVt.CellForeColor = &HFFFFFF
                FlxGrdVt.Text = aOlContacts(i).sCompanyName
                FlxGrdVt.Col = 3
                FlxGrdVt.CellBackColor = &HFF
                FlxGrdVt.CellForeColor = &HFFFFFF
                FlxGrdVt.Text = aOlContacts(i).sEmailId
            Else
                FlxGrdVt.Col = 0
                FlxGrdVt.CellBackColor = &HFFFFFF
                FlxGrdVt.Text = aOlContacts(i).sStatus
                FlxGrdVt.Col = 1
                FlxGrdVt.CellBackColor = &HFFFFFF
                FlxGrdVt.Text = aOlContacts(i).sFullName
                FlxGrdVt.Col = 2
                FlxGrdVt.CellBackColor = &HFFFFFF
                FlxGrdVt.Text = aOlContacts(i).sCompanyName
                FlxGrdVt.Col = 3
                FlxGrdVt.CellBackColor = &HFFFFFF
                FlxGrdVt.Text = aOlContacts(i).sEmailId
                FlxGrdVt.BackColor = &HFFFFFF
            End If
            
        Next i
    ElseIf gsSyncModule = "TASKSYNC" Then
        FlxGrdVt.Rows = UBound(aOlTasks) + 1
        FlxGrdVt.GridLines = flexGridFlat
        For i = 0 To UBound(aOlTasks) - 1
            FlxGrdVt.Row = i + 1
            FlxGrdVt.Col = 0
            FlxGrdVt.Text = aOlTasks(i).sStatus
            FlxGrdVt.Col = 1
            FlxGrdVt.Text = aOlTasks(i).sSubject
            FlxGrdVt.Col = 2
            FlxGrdVt.Text = aOlTasks(i).sStartDate
            FlxGrdVt.Col = 3
            FlxGrdVt.Text = aOlTasks(i).sEndDate
        Next i
    ElseIf gsSyncModule = "CALENDARSYNC" Then
        FlxGrdVt.Rows = UBound(aOlClndr) + 1
        FlxGrdVt.GridLines = flexGridFlat
        For i = 0 To UBound(aOlClndr) - 1
            FlxGrdVt.Row = i + 1
            If aOlClndr(i).sSubject <> "" Then
                FlxGrdVt.Col = 0
                FlxGrdVt.Text = aOlClndr(i).sStatus
                FlxGrdVt.Col = 1
                FlxGrdVt.Text = aOlClndr(i).sSubject
                FlxGrdVt.Col = 2
                FlxGrdVt.Text = aOlClndr(i).sStartDate
                FlxGrdVt.Col = 3
                FlxGrdVt.Text = aOlClndr(i).sEndDate
            Else
                FlxGrdVt.Col = 0
                FlxGrdVt.CellBackColor = &HFF
                FlxGrdVt.CellForeColor = &HFFFFFF
                FlxGrdVt.Text = "Ignore"
                FlxGrdVt.Col = 1
                FlxGrdVt.CellBackColor = &HFF
                FlxGrdVt.CellForeColor = &HFFFFFF
                FlxGrdVt.Text = aOlClndr(i).sSubject
                FlxGrdVt.Col = 2
                FlxGrdVt.CellBackColor = &HFF
                FlxGrdVt.CellForeColor = &HFFFFFF
                FlxGrdVt.Text = aOlClndr(i).sStartDate
                FlxGrdVt.Col = 3
                FlxGrdVt.CellBackColor = &HFF
                FlxGrdVt.CellForeColor = &HFFFFFF
                FlxGrdVt.Text = aOlClndr(i).sEndDate
            End If
        Next i
    End If
End If

If bPopVtListView = True Then
    If gsSyncModule = "CONTACTSYNC" Then
        FlxGrdOl.Rows = UBound(aVtContacts) + 1
        FlxGrdOl.GridLines = flexGridFlat
        For i = 0 To UBound(aVtContacts) - 1
            FlxGrdOl.Row = i + 1
            FlxGrdOl.Col = 0
            FlxGrdOl.Text = aVtContacts(i).sStatus
            FlxGrdOl.Col = 1
            FlxGrdOl.Text = aVtContacts(i).sFullName
            FlxGrdOl.Col = 2
            FlxGrdOl.Text = aVtContacts(i).sCompanyName
            FlxGrdOl.Col = 3
            FlxGrdOl.Text = aVtContacts(i).sEmailId
        Next i
    ElseIf gsSyncModule = "TASKSYNC" Then
        FlxGrdOl.Rows = UBound(aVtTasks) + 1
        FlxGrdOl.GridLines = flexGridFlat
        For i = 0 To UBound(aVtTasks) - 1
            FlxGrdOl.Row = i + 1
            FlxGrdOl.Col = 0
            FlxGrdOl.Text = aVtTasks(i).sStatus
            FlxGrdOl.Col = 1
            FlxGrdOl.Text = aVtTasks(i).sSubject
            FlxGrdOl.Col = 2
            FlxGrdOl.Text = aVtTasks(i).sStartDate
            FlxGrdOl.Col = 3
            FlxGrdOl.Text = aVtTasks(i).sEndDate
        Next i
    ElseIf gsSyncModule = "CALENDARSYNC" Then
        FlxGrdOl.Rows = UBound(aVtClndr) + 1
        FlxGrdOl.GridLines = flexGridFlat
        For i = 0 To UBound(aVtClndr) - 1
            FlxGrdOl.Row = i + 1
            FlxGrdOl.Col = 0
            FlxGrdOl.Text = aVtClndr(i).sStatus
            FlxGrdOl.Col = 1
            FlxGrdOl.Text = aVtClndr(i).sSubject
            FlxGrdOl.Col = 2
            FlxGrdOl.Text = aVtClndr(i).sStartDate
            FlxGrdOl.Col = 3
            FlxGrdOl.Text = aVtClndr(i).sEndDate
        Next i
    End If
End If
End Sub

Private Function bInitListViews() As Boolean
    On Error GoTo ERROR_EXIT_ROUTINE
    
    If gsSyncModule = "CONTACTSYNC" Then
        
        lblOutlook.Caption = "The following changes will be applied to your Contacts in Outlook"
        lblVtiger.Caption = "The following changes will be applied to your vtigerCRM Contacts"
        
        FlxGrdVt.Row = 0
        FlxGrdVt.Col = 0
        FlxGrdVt.ColWidth(0) = 800
        FlxGrdVt.Text = "Status"
        FlxGrdVt.Col = 1
        FlxGrdVt.ColWidth(1) = 2000
        FlxGrdVt.Text = "Full Name"
        FlxGrdVt.Col = 2
        FlxGrdVt.ColWidth(2) = 1800
        FlxGrdVt.Text = "Company"
        FlxGrdVt.Col = 3
        FlxGrdVt.ColWidth(3) = 2050
        FlxGrdVt.Text = "Email"
        FlxGrdVt.GridLines = flexGridNone
        
        FlxGrdOl.Row = 0
        FlxGrdOl.Col = 0
        FlxGrdOl.ColWidth(0) = 800
        FlxGrdOl.Text = "Status"
        FlxGrdOl.Col = 1
        FlxGrdOl.ColWidth(1) = 2000
        FlxGrdOl.Text = "Full Name"
        FlxGrdOl.Col = 2
        FlxGrdOl.ColWidth(2) = 1800
        FlxGrdOl.Text = "Company"
        FlxGrdOl.Col = 3
        FlxGrdOl.ColWidth(3) = 2050
        FlxGrdOl.Text = "Email"
        FlxGrdOl.GridLines = flexGridNone
    ElseIf gsSyncModule = "TASKSYNC" Then
        
        lblOutlook.Caption = "The following changes will be applied to your Tasks in Outlook"
        lblVtiger.Caption = "The following changes will be applied to your vtigerCRM Tasks"
        
        FlxGrdVt.Row = 0
        FlxGrdVt.Col = 0
        FlxGrdVt.ColWidth(0) = 800
        FlxGrdVt.Text = "Status"
        FlxGrdVt.Col = 1
        FlxGrdVt.ColWidth(1) = 3450
        FlxGrdVt.Text = "Subject"
        FlxGrdVt.Col = 2
        FlxGrdVt.ColWidth(2) = 1200
        FlxGrdVt.Text = "Start Date"
        FlxGrdVt.Col = 3
        FlxGrdVt.ColWidth(3) = 1200
        FlxGrdVt.Text = "Due Date"
        FlxGrdVt.GridLines = flexGridNone
        
        FlxGrdOl.Row = 0
        FlxGrdOl.Col = 0
        FlxGrdOl.ColWidth(0) = 800
        FlxGrdOl.Text = "Status"
        FlxGrdOl.Col = 1
        FlxGrdOl.ColWidth(1) = 3450
        FlxGrdOl.Text = "Subject"
        FlxGrdOl.Col = 2
        FlxGrdOl.ColWidth(2) = 1200
        FlxGrdOl.Text = "Start Date"
        FlxGrdOl.Col = 3
        FlxGrdOl.ColWidth(3) = 1200
        FlxGrdOl.Text = "Due Date"
        FlxGrdOl.GridLines = flexGridNone
    ElseIf gsSyncModule = "CALENDARSYNC" Then
        
        lblOutlook.Caption = "The following changes will be applied to your Meetings in Outlook"
        lblVtiger.Caption = "The following changes will be applied to your vtigerCRM Meetings"
        
        FlxGrdVt.Row = 0
        FlxGrdVt.Col = 0
        FlxGrdVt.ColWidth(0) = 800
        FlxGrdVt.Text = "Status"
        FlxGrdVt.Col = 1
        FlxGrdVt.ColWidth(1) = 3450
        FlxGrdVt.Text = "Subject"
        FlxGrdVt.Col = 2
        FlxGrdVt.ColWidth(2) = 1200
        FlxGrdVt.Text = "Start Date"
        FlxGrdVt.Col = 3
        FlxGrdVt.ColWidth(3) = 1200
        FlxGrdVt.Text = "Due Date"
        FlxGrdVt.GridLines = flexGridNone
        
        FlxGrdOl.Row = 0
        FlxGrdOl.Col = 0
        FlxGrdOl.ColWidth(0) = 800
        FlxGrdOl.Text = "Status"
        FlxGrdOl.Col = 1
        FlxGrdOl.ColWidth(1) = 3450
        FlxGrdOl.Text = "Subject"
        FlxGrdOl.Col = 2
        FlxGrdOl.ColWidth(2) = 1200
        FlxGrdOl.Text = "Start Date"
        FlxGrdOl.Col = 3
        FlxGrdOl.ColWidth(3) = 1200
        FlxGrdOl.Text = "Due Date"
        FlxGrdOl.GridLines = flexGridNone
    End If
    
    GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    LogTheMessage ("ERROR Initializing Sync Status ListViews" & Err.Description)
    bInitListViews = True
EXIT_ROUTINE:
End Function

Private Function bPopOlListView() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_FirstElmnt As MSXML.IXMLDOMElement
Dim bMapFlag As Boolean

Dim i As Integer
Dim sOlSyncFlag As String
Dim sXQuery As String
Dim sEntryId As String
Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_FirstElmnt As MSXML.IXMLDOMElement
Dim bLocalOlFlag As Boolean
Dim Index As Integer

Dim sLastName As String
Dim sFirstName As String
Dim sMiddleName As String
Dim sCompanyName As String
Dim sEmailId As String

Dim sTkSubject As String
Dim sTkStartDate As String
Dim sTkDueDate As String

Dim sClSubject As String
Dim sClStartDate As String
Dim sClDueDate As String
                            
bMapFlag = oXMLMap_Doc.loadXML(gsMappingSyncXML)
bLocalOlFlag = oXMLLocalOl_Doc.loadXML(gsLocalOlSyncXML)

Index = 0

If (bMapFlag = True And bLocalOlFlag = True) Then
    Set oXMLMap_Root = oXMLMap_Doc.documentElement
    Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
    If oXMLMap_Root.childNodes.Length > 0 Then
           For i = 0 To oXMLMap_Root.childNodes.Length - 1
           
                Set oXMLMap_FirstElmnt = oXMLMap_Root.childNodes.Item(i)
                
                sEntryId = oXMLMap_FirstElmnt.getAttribute("entryid") & vbNullString
                sOlSyncFlag = oXMLMap_FirstElmnt.getAttribute("olsyncflag") & vbNullString
                
                If Trim(sOlSyncFlag) = "N" And Trim(sEntryId) <> "" Then
                    
                    If gsSyncModule = "CONTACTSYNC" Then
                    
                        sXQuery = "contactitems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_FirstElmnt = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalOl_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aOlContacts(Index + 1)
                            
                            sFirstName = oXMLLocalOl_FirstElmnt.selectSingleNode("firstname").nodeTypedValue
                            sMiddleName = oXMLLocalOl_FirstElmnt.selectSingleNode("middlename").nodeTypedValue
                            sLastName = oXMLLocalOl_FirstElmnt.selectSingleNode("lastname").nodeTypedValue
                            sCompanyName = oXMLLocalOl_FirstElmnt.selectSingleNode("accountname").nodeTypedValue
                            sEmailId = oXMLLocalOl_FirstElmnt.selectSingleNode("emailaddress").nodeTypedValue
                            
                            aOlContacts(Index).sStatus = "Addition"
                            aOlContacts(Index).sLastName = DecodeUTF8(sLastName)
                            aOlContacts(Index).sFullName = DecodeUTF8(sFirstName) & " " & DecodeUTF8(sMiddleName) & " " & DecodeUTF8(sLastName)
                            aOlContacts(Index).sEmailId = DecodeUTF8(sEmailId)
                            aOlContacts(Index).sCompanyName = DecodeUTF8(sCompanyName)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "TASKSYNC" Then
                    
                        sXQuery = "taskitems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_FirstElmnt = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalOl_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aOlTasks(Index + 1)
                            
                            sTkSubject = oXMLLocalOl_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sTkStartDate = oXMLLocalOl_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sTkDueDate = oXMLLocalOl_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                            
                            aOlTasks(Index).sStatus = "Addition"
                            aOlTasks(Index).sSubject = DecodeUTF8(sTkSubject)
                            aOlTasks(Index).sStartDate = DecodeUTF8(sTkStartDate)
                            aOlTasks(Index).sEndDate = DecodeUTF8(sTkDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "CALENDARSYNC" Then
                    
                        sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_FirstElmnt = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalOl_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aOlClndr(Index + 1)
                            
                            sClSubject = oXMLLocalOl_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sClStartDate = oXMLLocalOl_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sClDueDate = oXMLLocalOl_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                            
                            aOlClndr(Index).sStatus = "Addition"
                            aOlClndr(Index).sSubject = DecodeUTF8(sClSubject)
                            aOlClndr(Index).sStartDate = DecodeUTF8(sClStartDate)
                            aOlClndr(Index).sEndDate = DecodeUTF8(sClDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    
                    End If
                    
                End If
                
                If Trim(sOlSyncFlag) = "M" And Trim(sEntryId) <> "" Then
                    
                    If gsSyncModule = "CONTACTSYNC" Then
                    
                        sXQuery = "contactitems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_FirstElmnt = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalOl_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aOlContacts(Index + 1)
                            
                            sFirstName = oXMLLocalOl_FirstElmnt.selectSingleNode("firstname").nodeTypedValue
                            sMiddleName = oXMLLocalOl_FirstElmnt.selectSingleNode("middlename").nodeTypedValue
                            sLastName = oXMLLocalOl_FirstElmnt.selectSingleNode("lastname").nodeTypedValue
                            sCompanyName = oXMLLocalOl_FirstElmnt.selectSingleNode("accountname").nodeTypedValue
                            sEmailId = oXMLLocalOl_FirstElmnt.selectSingleNode("emailaddress").nodeTypedValue
                            
                            aOlContacts(Index).sStatus = "Update"
                            aOlContacts(Index).sLastName = DecodeUTF8(sLastName)
                            aOlContacts(Index).sFullName = DecodeUTF8(sFirstName) & " " & DecodeUTF8(sMiddleName) & " " & DecodeUTF8(sLastName)
                            aOlContacts(Index).sEmailId = DecodeUTF8(sEmailId)
                            aOlContacts(Index).sCompanyName = DecodeUTF8(sCompanyName)
                            
                            Index = Index + 1
                            
                        End If
                        
                    ElseIf gsSyncModule = "TASKSYNC" Then
                        
                        sXQuery = "taskitems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_FirstElmnt = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalOl_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aOlTasks(Index + 1)
                            
                            sTkSubject = oXMLLocalOl_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sTkStartDate = oXMLLocalOl_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sTkDueDate = oXMLLocalOl_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                            
                            aOlTasks(Index).sStatus = "Update"
                            aOlTasks(Index).sSubject = DecodeUTF8(sTkSubject)
                            aOlTasks(Index).sStartDate = DecodeUTF8(sTkStartDate)
                            aOlTasks(Index).sEndDate = DecodeUTF8(sTkDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "CALENDARSYNC" Then
                    
                        sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_FirstElmnt = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalOl_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aOlClndr(Index + 1)
                            
                            sClSubject = oXMLLocalOl_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sClStartDate = oXMLLocalOl_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sClDueDate = oXMLLocalOl_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                            
                            aOlClndr(Index).sStatus = "Update"
                            aOlClndr(Index).sSubject = DecodeUTF8(sClSubject)
                            aOlClndr(Index).sStartDate = DecodeUTF8(sClStartDate)
                            aOlClndr(Index).sEndDate = DecodeUTF8(sClDueDate)
                            
                            Index = Index + 1
                            
                        End If
                        
                    End If
                End If
                
                If Trim(sOlSyncFlag) = "D" And Trim(sEntryId) <> "" Then
                    
                    If gsSyncModule = "CONTACTSYNC" Then
                    
                        sXQuery = "contactitems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_FirstElmnt = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalOl_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aOlContacts(Index + 1)
                            
                            sFirstName = oXMLLocalOl_FirstElmnt.selectSingleNode("firstname").nodeTypedValue
                            sMiddleName = oXMLLocalOl_FirstElmnt.selectSingleNode("middlename").nodeTypedValue
                            sLastName = oXMLLocalOl_FirstElmnt.selectSingleNode("lastname").nodeTypedValue
                            sCompanyName = oXMLLocalOl_FirstElmnt.selectSingleNode("accountname").nodeTypedValue
                            sEmailId = oXMLLocalOl_FirstElmnt.selectSingleNode("emailaddress").nodeTypedValue
                            
                            aOlContacts(Index).sStatus = "Delete"
                            aOlContacts(Index).sFullName = DecodeUTF8(sFirstName) & " " & DecodeUTF8(sMiddleName) & " " & DecodeUTF8(sLastName)
                            aOlContacts(Index).sEmailId = DecodeUTF8(sEmailId)
                            aOlContacts(Index).sCompanyName = DecodeUTF8(sCompanyName)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "TASKSYNC" Then
                    
                        sXQuery = "taskitems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_FirstElmnt = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalOl_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aOlTasks(Index + 1)
                            
                            sTkSubject = oXMLLocalOl_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sTkStartDate = oXMLLocalOl_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sTkDueDate = oXMLLocalOl_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                            
                            aOlTasks(Index).sStatus = "Delete"
                            aOlTasks(Index).sSubject = DecodeUTF8(sTkSubject)
                            aOlTasks(Index).sStartDate = DecodeUTF8(sTkStartDate)
                            aOlTasks(Index).sEndDate = DecodeUTF8(sTkDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "CALENDARSYNC" Then
                    
                        sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_FirstElmnt = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalOl_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aOlClndr(Index + 1)
                            
                            sClSubject = oXMLLocalOl_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sClStartDate = oXMLLocalOl_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sClDueDate = oXMLLocalOl_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                            
                            aOlClndr(Index).sStatus = "Delete"
                            aOlClndr(Index).sSubject = DecodeUTF8(sClSubject)
                            aOlClndr(Index).sStartDate = DecodeUTF8(sClStartDate)
                            aOlClndr(Index).sEndDate = DecodeUTF8(sClDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    End If
                End If
                
           Next i
    End If
End If

If Index = 0 Then
    bPopOlListView = False
Else
    bPopOlListView = True
End If

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
'sMsgDlg (Err.Description)
bPopOlListView = False
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_FirstElmnt = Nothing
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_FirstElmnt = Nothing
End Function
Private Function bPopVtListView() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_FirstElmnt As MSXML.IXMLDOMElement
Dim bMapFlag As Boolean

Dim i As Integer
Dim sVtSyncFlag As String
Dim sXQuery As String
Dim sCrmId As String
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_FirstElmnt As MSXML.IXMLDOMElement
Dim bLocalVtFlag As Boolean
Dim Index As Integer
Dim sLastName As String
Dim sMiddleName As String
Dim sFirstName As String
Dim sCompanyName As String
Dim sEmailId As String

Dim sTkSubject As String
Dim sTkStartDate As String
Dim sTkDueDate As String

Dim sClSubject As String
Dim sClStartDate As String
Dim sClDueDate As String

bMapFlag = oXMLMap_Doc.loadXML(gsMappingSyncXML)
bLocalVtFlag = oXMLLocalVt_Doc.loadXML(gsLocalVtSyncXML)

Index = 0

If (bMapFlag = True And bLocalVtFlag = True) Then
    Set oXMLMap_Root = oXMLMap_Doc.documentElement
    Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
    If oXMLMap_Root.childNodes.Length > 0 Then
           For i = 0 To oXMLMap_Root.childNodes.Length - 1
           
                Set oXMLMap_FirstElmnt = oXMLMap_Root.childNodes.Item(i)
                
                sCrmId = oXMLMap_FirstElmnt.getAttribute("crmid") & vbNullString
                sVtSyncFlag = oXMLMap_FirstElmnt.getAttribute("vtsyncflag") & vbNullString
                
                If Trim(sVtSyncFlag) = "N" And Trim(sCrmId) <> "" Then
                    
                    If gsSyncModule = "CONTACTSYNC" Then
                        sXQuery = "contactitems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_FirstElmnt = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalVt_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aVtContacts(Index + 1)
                            
                            sFirstName = oXMLLocalVt_FirstElmnt.selectSingleNode("firstname").nodeTypedValue
                            sMiddleName = oXMLLocalVt_FirstElmnt.selectSingleNode("middlename").nodeTypedValue
                            sLastName = oXMLLocalVt_FirstElmnt.selectSingleNode("lastname").nodeTypedValue
                            sCompanyName = oXMLLocalVt_FirstElmnt.selectSingleNode("accountname").nodeTypedValue
                            sEmailId = oXMLLocalVt_FirstElmnt.selectSingleNode("emailaddress").nodeTypedValue
                                                    
                            aVtContacts(Index).sStatus = "Addition"
                            aVtContacts(Index).sFullName = DecodeUTF8(sFirstName) & " " & DecodeUTF8(sMiddleName) & " " & DecodeUTF8(sLastName)
                            aVtContacts(Index).sEmailId = DecodeUTF8(sEmailId)
                            aVtContacts(Index).sCompanyName = DecodeUTF8(sCompanyName)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "TASKSYNC" Then
                    
                        sXQuery = "taskitems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_FirstElmnt = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalVt_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aVtTasks(Index + 1)
                            
                            sTkSubject = oXMLLocalVt_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sTkStartDate = oXMLLocalVt_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sTkDueDate = oXMLLocalVt_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                                                   
                            aVtTasks(Index).sStatus = "Addition"
                            aVtTasks(Index).sSubject = DecodeUTF8(sTkSubject)
                            aVtTasks(Index).sStartDate = DecodeUTF8(sTkStartDate)
                            aVtTasks(Index).sEndDate = DecodeUTF8(sTkDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "CALENDARSYNC" Then
                    
                        sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_FirstElmnt = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalVt_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aVtClndr(Index + 1)
                            
                            sClSubject = oXMLLocalVt_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sClStartDate = oXMLLocalVt_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sClDueDate = oXMLLocalVt_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                                                   
                            aVtClndr(Index).sStatus = "Addition"
                            aVtClndr(Index).sSubject = DecodeUTF8(sClSubject)
                            aVtClndr(Index).sStartDate = DecodeUTF8(sClStartDate)
                            aVtClndr(Index).sEndDate = DecodeUTF8(sClDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    End If
                End If
                
                If Trim(sVtSyncFlag) = "M" And Trim(sCrmId) <> "" Then
                    
                    If gsSyncModule = "CONTACTSYNC" Then
                    
                        sXQuery = "contactitems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_FirstElmnt = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalVt_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aVtContacts(Index + 1)
                            
                            sFirstName = oXMLLocalVt_FirstElmnt.selectSingleNode("firstname").nodeTypedValue
                            sMiddleName = oXMLLocalVt_FirstElmnt.selectSingleNode("middlename").nodeTypedValue
                            sLastName = oXMLLocalVt_FirstElmnt.selectSingleNode("lastname").nodeTypedValue
                            sCompanyName = oXMLLocalVt_FirstElmnt.selectSingleNode("accountname").nodeTypedValue
                            sEmailId = oXMLLocalVt_FirstElmnt.selectSingleNode("emailaddress").nodeTypedValue
                                                    
                            aVtContacts(Index).sStatus = "Update"
                            aVtContacts(Index).sFullName = DecodeUTF8(sFirstName) & " " & DecodeUTF8(sMiddleName) & " " & DecodeUTF8(sLastName)
                            aVtContacts(Index).sEmailId = DecodeUTF8(sEmailId)
                            aVtContacts(Index).sCompanyName = DecodeUTF8(sCompanyName)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "TASKSYNC" Then
                        
                        sXQuery = "taskitems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_FirstElmnt = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalVt_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aVtTasks(Index + 1)
                            
                            sTkSubject = oXMLLocalVt_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sTkStartDate = oXMLLocalVt_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sTkDueDate = oXMLLocalVt_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                                                   
                            aVtTasks(Index).sStatus = "Update"
                            aVtTasks(Index).sSubject = DecodeUTF8(sTkSubject)
                            aVtTasks(Index).sStartDate = DecodeUTF8(sTkStartDate)
                            aVtTasks(Index).sEndDate = DecodeUTF8(sTkDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "CALENDARSYNC" Then
                    
                        sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_FirstElmnt = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalVt_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aVtClndr(Index + 1)
                            
                            sClSubject = oXMLLocalVt_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sClStartDate = oXMLLocalVt_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sClDueDate = oXMLLocalVt_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                                                   
                            aVtClndr(Index).sStatus = "Update"
                            aVtClndr(Index).sSubject = DecodeUTF8(sClSubject)
                            aVtClndr(Index).sStartDate = DecodeUTF8(sClStartDate)
                            aVtClndr(Index).sEndDate = DecodeUTF8(sClDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    End If
                End If
                
                If Trim(sVtSyncFlag) = "D" And Trim(sCrmId) <> "" Then
                    
                    If gsSyncModule = "CONTACTSYNC" Then
                    
                        sXQuery = "contactitems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_FirstElmnt = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalVt_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aVtContacts(Index + 1)
                            
                            sFirstName = oXMLLocalVt_FirstElmnt.selectSingleNode("firstname").nodeTypedValue
                            sMiddleName = oXMLLocalVt_FirstElmnt.selectSingleNode("middlename").nodeTypedValue
                            sLastName = oXMLLocalVt_FirstElmnt.selectSingleNode("lastname").nodeTypedValue
                            sCompanyName = oXMLLocalVt_FirstElmnt.selectSingleNode("accountname").nodeTypedValue
                            sEmailId = oXMLLocalVt_FirstElmnt.selectSingleNode("emailaddress").nodeTypedValue
                                                    
                            aVtContacts(Index).sStatus = "Delete"
                            aVtContacts(Index).sFullName = DecodeUTF8(sFirstName) & " " & DecodeUTF8(sMiddleName) & " " & DecodeUTF8(sLastName)
                            aVtContacts(Index).sEmailId = DecodeUTF8(sEmailId)
                            aVtContacts(Index).sCompanyName = DecodeUTF8(sCompanyName)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "TASKSYNC" Then
                        sXQuery = "taskitems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_FirstElmnt = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalVt_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aVtTasks(Index + 1)
                            
                            sTkSubject = oXMLLocalVt_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sTkStartDate = oXMLLocalVt_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sTkDueDate = oXMLLocalVt_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                                                   
                            aVtTasks(Index).sStatus = "Delete"
                            aVtTasks(Index).sSubject = DecodeUTF8(sTkSubject)
                            aVtTasks(Index).sStartDate = DecodeUTF8(sTkStartDate)
                            aVtTasks(Index).sEndDate = DecodeUTF8(sTkDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    ElseIf gsSyncModule = "CALENDARSYNC" Then
                    
                        sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_FirstElmnt = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                        
                        If Not oXMLLocalVt_FirstElmnt Is Nothing Then
                        
                            ReDim Preserve aVtClndr(Index + 1)
                            
                            sClSubject = oXMLLocalVt_FirstElmnt.selectSingleNode("subject").nodeTypedValue
                            sClStartDate = oXMLLocalVt_FirstElmnt.selectSingleNode("startdate").nodeTypedValue
                            sClDueDate = oXMLLocalVt_FirstElmnt.selectSingleNode("duedate").nodeTypedValue
                                                   
                            aVtClndr(Index).sStatus = "Delete"
                            aVtClndr(Index).sSubject = DecodeUTF8(sClSubject)
                            aVtClndr(Index).sStartDate = DecodeUTF8(sClStartDate)
                            aVtClndr(Index).sEndDate = DecodeUTF8(sClDueDate)
                            
                            Index = Index + 1
                            
                        End If
                    End If
                End If
           Next i
    End If
End If

If Index = 0 Then
    bPopVtListView = False
Else
    bPopVtListView = True
End If

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
sMsgDlg ("bPopVtListView" & Err.Description)
bPopVtListView = False
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_FirstElmnt = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_FirstElmnt = Nothing
End Function

