VERSION 5.00
Begin VB.Form frmSyncStatus 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "Synchronizing Status"
   ClientHeight    =   5520
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   6255
   BeginProperty Font 
      Name            =   "Tahoma"
      Size            =   8.25
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   Icon            =   "frmSyncStatus.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   5520
   ScaleWidth      =   6255
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.CommandButton cmdCancel 
      Caption         =   "&Close"
      Height          =   375
      Left            =   4920
      TabIndex        =   7
      Top             =   5040
      Width           =   1215
   End
   Begin VB.CommandButton cmdAccept 
      Caption         =   "&Accept Changes"
      Height          =   375
      Left            =   3120
      TabIndex        =   1
      Top             =   5040
      Width           =   1575
   End
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   615
      Left            =   0
      Picture         =   "frmSyncStatus.frx":000C
      ScaleHeight     =   615
      ScaleWidth      =   6255
      TabIndex        =   0
      Top             =   0
      Width           =   6255
   End
   Begin VB.Frame Frame1 
      Height          =   4335
      Left            =   -120
      TabIndex        =   2
      Top             =   540
      Width           =   6495
      Begin VB.CommandButton cmdViewDetails 
         Caption         =   "&View Details"
         Height          =   375
         Left            =   4680
         TabIndex        =   8
         Top             =   3720
         Width           =   1575
      End
      Begin VB.Label lblZohoStatus 
         Caption         =   "- No Changes"
         Height          =   1335
         Left            =   360
         TabIndex        =   6
         Top             =   2400
         Width           =   4920
      End
      Begin VB.Label lblOlStatus 
         Caption         =   "- No Changes"
         Height          =   1335
         Left            =   360
         TabIndex        =   5
         Top             =   600
         Width           =   4920
      End
      Begin VB.Label lblVtiger 
         Caption         =   "The following changes will be applied to your vtigerCRM Contacts"
         Height          =   255
         Left            =   240
         TabIndex        =   4
         Top             =   2040
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
Attribute VB_Name = "frmSyncStatus"
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
Public sStatusFlag As Boolean
Private Sub cmdAccept_Click()
    Me.Hide
    sStatusFlag = False
    If gsSyncModule = "CONTACTSYNC" Then
        Call AcceptChanges("CONTACTSYNC")
    ElseIf gsSyncModule = "TASKSYNC" Then
        Call AcceptChanges("TASKSYNC")
    ElseIf gsSyncModule = "CALENDARSYNC" Then
        Call AcceptChanges("CALENDARSYNC")
    End If
    frmSync.Hide
    Unload Me
    Unload frmSync
End Sub
Private Sub cmdCancel_Click()
    Me.Hide
    frmSync.Hide
    sStatusFlag = False
    Unload Me
End Sub
Private Sub cmdViewDetails_Click()
    frmSyncDetails.Show vbModal
End Sub
Private Function sGetStatus()

Dim sOlStatus As String
Dim sVtStatus As String
                      

sOlStatus = sGetSyncStatusOl()

sVtStatus = sGetSyncStatusVt()


If gsSyncModule = "CONTACTSYNC" Then
    lblVtiger.Caption = "The following changes will be applied to your vtigerCRM Contacts"
    lblOutlook.Caption = "The following changes will be applied to your Contacts in Outlook"
ElseIf gsSyncModule = "TASKSYNC" Then
    lblVtiger.Caption = "The following changes will be applied to your vtigerCRM Tasks"
    lblOutlook.Caption = "The following changes will be applied to your Tasks in Outlook"
ElseIf gsSyncModule = "CALENDARSYNC" Then
    lblVtiger.Caption = "The following changes will be applied to your vtigerCRM Calendar"
    lblOutlook.Caption = "The following changes will be applied to your Calendar in Outlook"
End If

If sOlStatus <> "" Then
    lblZohoStatus.Caption = sOlStatus
Else
    lblZohoStatus.Caption = "- No Changes"
End If

If sVtStatus <> "" Then
    lblOlStatus.Caption = sVtStatus
Else
    lblOlStatus.Caption = "- No Changes"
End If

If sOlStatus = "" And sVtStatus = "" Then
    cmdViewDetails.Enabled = False
    cmdAccept.Enabled = False
Else
    cmdViewDetails.Enabled = True
    cmdAccept.Enabled = True
End If
End Function
Private Sub Form_Activate()
    If sStatusFlag = True Then Exit Sub
    sStatusFlag = True
    Call sGetStatus
End Sub

Private Function sGetSyncStatusOl() As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLRoot_Element As MSXML.IXMLDOMElement
Dim nCount As Integer
Dim sCountReturnStr As String
Dim sMapType As String

If gsSyncModule = "CONTACTSYNC" Then
    sMapType = "CNTS"
ElseIf gsSyncModule = "TASKSYNC" Then
    sMapType = "TASK"
ElseIf gsSyncModule = "CALENDARSYNC" Then
    sMapType = "CLNDR"
End If

If oXMLDoc.loadXML(gsMappingSyncXML) = True Then
    
    Set oXMLRoot_Element = oXMLDoc.documentElement
    
    nCount = oXMLRoot_Element.selectNodes("syncitem[@olsyncflag='N' and @type='" & sMapType & "']").Length
    If nCount > 0 Then
        If gsSyncModule = "CONTACTSYNC" Then
            sCountReturnStr = "- " & nCount & " Contact(s) Addition" & vbCrLf
        ElseIf gsSyncModule = "TASKSYNC" Then
            sCountReturnStr = "- " & nCount & " Task(s) Addition" & vbCrLf
        ElseIf gsSyncModule = "CALENDARSYNC" Then
            sCountReturnStr = "- " & nCount & " Meeting(s) Addition" & vbCrLf
        End If
    End If
    
    nCount = oXMLRoot_Element.selectNodes("syncitem[@olsyncflag='M' and @type='" & sMapType & "']").Length
    If nCount > 0 Then
        If gsSyncModule = "CONTACTSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Contact(s) Updation" & vbCrLf
        ElseIf gsSyncModule = "TASKSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Task(s) Updation" & vbCrLf
        ElseIf gsSyncModule = "CALENDARSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Meeting(s) Updation" & vbCrLf
        End If
    End If
    
    nCount = oXMLRoot_Element.selectNodes("syncitem[@olsyncflag='D' and @type='" & sMapType & "']").Length
    If nCount > 0 Then
        If gsSyncModule = "CONTACTSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Contact(s) Deletion"
        ElseIf gsSyncModule = "TASKSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Task(s) Deletion"
        ElseIf gsSyncModule = "CALENDARSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Meeting(s) Deletion"
        End If
    End If
Else
    sCountReturnStr = ""
End If

sGetSyncStatusOl = sCountReturnStr
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sMsgDlg ("sGetSyncStatusOl" & Err.Description)
    sGetSyncStatusOl = ""
EXIT_ROUTINE:
Set oXMLDoc = Nothing
Set oXMLRoot_Element = Nothing
End Function

Private Function sGetSyncStatusVt() As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLRoot_Element As MSXML.IXMLDOMElement
Dim nCount As Integer
Dim sCountReturnStr As String
Dim sMapType As String

If gsSyncModule = "CONTACTSYNC" Then
    sMapType = "CNTS"
ElseIf gsSyncModule = "TASKSYNC" Then
    sMapType = "TASK"
ElseIf gsSyncModule = "CALENDARSYNC" Then
    sMapType = "CLNDR"
End If


If oXMLDoc.loadXML(gsMappingSyncXML) = True Then
    
    Set oXMLRoot_Element = oXMLDoc.documentElement
    
    nCount = oXMLRoot_Element.selectNodes("syncitem[@vtsyncflag='N' and @type='" & sMapType & "']").Length
    If nCount > 0 Then
        If gsSyncModule = "CONTACTSYNC" Then
            sCountReturnStr = "- " & nCount & " Contact(s) Addition" & vbCrLf
        ElseIf gsSyncModule = "TASKSYNC" Then
            sCountReturnStr = "- " & nCount & " Task(s) Addition" & vbCrLf
        ElseIf gsSyncModule = "CALENDARSYNC" Then
            sCountReturnStr = "- " & nCount & " Meeting(s) Addition" & vbCrLf
        End If
    End If
    
    nCount = oXMLRoot_Element.selectNodes("syncitem[@vtsyncflag='M' and @type='" & sMapType & "']").Length
    If nCount > 0 Then
        If gsSyncModule = "CONTACTSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Contact(s) Updation" & vbCrLf
        ElseIf gsSyncModule = "TASKSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Task(s) Updation" & vbCrLf
        ElseIf gsSyncModule = "CALENDARSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Meeting(s) Updation" & vbCrLf
        End If
    End If
    
    nCount = oXMLRoot_Element.selectNodes("syncitem[@vtsyncflag='D' and @type='" & sMapType & "']").Length
    If nCount > 0 Then
        If gsSyncModule = "CONTACTSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Contact(s) Deletion"
        ElseIf gsSyncModule = "TASKSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Task(s) Deletion"
        ElseIf gsSyncModule = "CALENDARSYNC" Then
            sCountReturnStr = sCountReturnStr & "- " & nCount & " Meeting(s) Deletion"
        End If
    End If
Else
    sCountReturnStr = ""
End If
sGetSyncStatusVt = sCountReturnStr
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sMsgDlg ("sGetSyncStatusVt" & Err.Description)
    sGetSyncStatusVt = ""
EXIT_ROUTINE:
Set oXMLDoc = Nothing
Set oXMLRoot_Element = Nothing
End Function
Private Sub Form_Unload(Cancel As Integer)
        Me.Hide
        frmSync.Hide
        sStatusFlag = False
End Sub
