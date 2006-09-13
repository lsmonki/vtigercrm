VERSION 5.00
Begin VB.Form frmvtigerMerge 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "vtigerCRM - Insert Merge Fields"
   ClientHeight    =   5295
   ClientLeft      =   5610
   ClientTop       =   3345
   ClientWidth     =   6990
   Icon            =   "frmvtigerTempate.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   5295
   ScaleWidth      =   6990
   ShowInTaskbar   =   0   'False
   Begin VB.CommandButton cmdClose 
      Caption         =   "C&lose"
      Height          =   375
      Left            =   5640
      TabIndex        =   3
      Top             =   4680
      Width           =   1215
   End
   Begin VB.CommandButton cmdInsert 
      Caption         =   "&Insert"
      Height          =   375
      Left            =   4200
      TabIndex        =   2
      Top             =   4680
      Width           =   1215
   End
   Begin VB.ListBox lstFields 
      Height          =   3180
      Left            =   3120
      MultiSelect     =   1  'Simple
      TabIndex        =   1
      Top             =   1320
      Width           =   3735
   End
   Begin VB.ListBox lstColumns 
      Height          =   3180
      Left            =   120
      TabIndex        =   0
      Top             =   1320
      Width           =   2895
   End
   Begin VB.PictureBox Picture1 
      Height          =   735
      Left            =   -120
      Picture         =   "frmvtigerTempate.frx":058A
      ScaleHeight     =   675
      ScaleWidth      =   7035
      TabIndex        =   6
      Top             =   0
      Width           =   7095
   End
   Begin VB.Label Label1 
      Caption         =   "2. Then select the merge field to insert"
      Height          =   420
      Left            =   3120
      TabIndex        =   5
      Top             =   840
      Width           =   3675
   End
   Begin VB.Label Label5 
      Caption         =   "1. Choose field type"
      Height          =   420
      Left            =   135
      TabIndex        =   4
      Top             =   840
      Width           =   2790
   End
End
Attribute VB_Name = "frmvtigerMerge"
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
' * All Rights Reserved.
'*
' ********************************************************************************/

Private Sub cmdClose_Click()
Unload frmvtigerMerge
End Sub

Private Sub cmdInsert_Click()
On Error GoTo ERROR_EXIT_ROUTINE

Dim oWordActiveApp As New Word.Application
Dim oWordActiveDoc As New Word.Document
Dim sString As String
Dim i As Integer
Set oWordActiveApp = oWordAppObj

If oWordActiveApp Is Nothing Then
    
Else
    If lstFields.SelCount > 0 Then
        For i = 0 To lstFields.ListCount - 1
            If lstFields.Selected(i) = True Then
               sString = Replace(lstFields.List(i), ": ", "_")
               sString = Replace(sString, Chr$(32), "")
               oWordActiveApp.ActiveDocument.Fields.Add Selection.Range, wdFieldMergeField, UCase(sString)
               'oWordActiveApp.ActiveDocument.Fields
            End If
        Next i
    Else
       Call sErrorDlg(gMsg007)
       GoTo EXIT_ROUTINE
    End If
End If

Unload frmvtigerMerge
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
Call sErrorDlg(gMsg017)
EXIT_ROUTINE:
Set oWordActiveApp = Nothing
End Sub

Private Sub Form_Load()

'Language Implmentation
frmvtigerMerge.Caption = gfrmMerge_FormName
Label5.Caption = gfrmMerge_Label1
    Label1.Caption = gfrmMerge_Label2
cmdInsert.Caption = gfrmMerge_InsertButton
cmdClose.Caption = gfrmMerge_CloseButton

Me.Top = (Screen.Height - Me.Height) / 2

Me.Left = (Screen.Width - Me.Width) / 2

'Call sLoadField(lstColumns)

End Sub

Private Sub lstColumns_Click()
Dim i As Integer
If lstColumns.Selected(lstColumns.ListIndex) = True Then
    lstFields.Clear
    Select Case (lstColumns.ListIndex)
    Case contact_index:
        If UBound(a) <> 0 Then
            For i = 0 To UBound(a)
                lstFields.AddItem Replace(lstColumns.Text, " Fields", "") & ": " & a(i), i
            Next i
        End If
    Case account_index:
        If UBound(a_acnt) <> 0 Then
            For i = 0 To UBound(a_acnt)
                lstFields.AddItem Replace(lstColumns.Text, " Fields", "") & ": " & a_acnt(i), i
            Next i
        End If
    Case lead_index:
        If UBound(a_lead) <> 0 Then
            For i = 0 To UBound(a_lead)
                lstFields.AddItem Replace(lstColumns.Text, " Fields", "") & ": " & a_lead(i), i
            Next i
        End If
    Case ticket_index:
        If UBound(a_tickets) <> 0 Then
            For i = 0 To UBound(a_tickets)
                lstFields.AddItem Replace(lstColumns.Text, " Fields", "") & ": " & a_tickets(i), i
            Next i
        End If
    Case user_index:
        If UBound(a_user) <> 0 Then
            For i = 0 To UBound(a_user)
                lstFields.AddItem Replace(lstColumns.Text, " Fields", "") & ": " & a_user(i), i
            Next i
        End If
        
    End Select
End If
End Sub


