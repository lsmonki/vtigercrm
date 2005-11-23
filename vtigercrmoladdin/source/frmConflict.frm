VERSION 5.00
Object = "{0ECD9B60-23AA-11D0-B351-00A0C9055D8E}#6.0#0"; "MSHFLXGD.OCX"
Begin VB.Form frmConflict 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "Conflict Resoultion"
   ClientHeight    =   4890
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   7215
   BeginProperty Font 
      Name            =   "Tahoma"
      Size            =   8.25
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   Icon            =   "frmConflict.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   4890
   ScaleWidth      =   7215
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
      Height          =   375
      Left            =   5880
      TabIndex        =   5
      Top             =   4410
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
      Picture         =   "frmConflict.frx":000C
      ScaleHeight     =   615
      ScaleWidth      =   7215
      TabIndex        =   0
      Top             =   0
      Width           =   7215
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
      Height          =   3735
      Left            =   -120
      TabIndex        =   1
      Top             =   540
      Width           =   7455
      Begin VB.OptionButton optCnflct 
         Caption         =   "Update vtigerCRM Details in Outlook"
         Height          =   255
         Index           =   1
         Left            =   240
         TabIndex        =   4
         Top             =   3420
         Width           =   6975
      End
      Begin VB.OptionButton optCnflct 
         Caption         =   "Update Outlook Details in vtigerCRM"
         Height          =   255
         Index           =   0
         Left            =   240
         TabIndex        =   3
         Top             =   3120
         Width           =   6975
      End
      Begin MSHierarchicalFlexGridLib.MSHFlexGrid flxGrdConflict 
         Height          =   2535
         Left            =   240
         TabIndex        =   2
         Top             =   480
         Width           =   6975
         _ExtentX        =   12303
         _ExtentY        =   4471
         _Version        =   393216
         Cols            =   3
         FixedCols       =   0
         BackColorBkg    =   -2147483643
         GridColor       =   -2147483633
         FocusRect       =   0
         HighLight       =   2
         ScrollBars      =   2
         SelectionMode   =   1
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Tahoma"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         _NumberOfBands  =   1
         _Band(0).Cols   =   3
      End
      Begin VB.Label Label1 
         Caption         =   "Conflict Resoultion for Updation in Outlook and vtigerCRM"
         Height          =   255
         Left            =   240
         TabIndex        =   6
         Top             =   240
         Width           =   6975
      End
   End
End
Attribute VB_Name = "frmConflict"
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
Private Sub cmdSave_Click()
    If optCnflct(0).Value = True Then
        nConflictOption = 0
    ElseIf optCnflct(1).Value = True Then
        nConflictOption = 1
    End If
    Unload Me
End Sub
Private Sub Form_Load()
    flxGrdConflict.Row = 0
    flxGrdConflict.Col = 0
    flxGrdConflict.ColWidth(0) = 1900
    flxGrdConflict.Text = "Fields"
    flxGrdConflict.Col = 1
    flxGrdConflict.ColWidth(1) = 2500
    flxGrdConflict.Text = "Outlook"
    flxGrdConflict.Col = 2
    flxGrdConflict.ColWidth(2) = 2500
    flxGrdConflict.Text = "vtigerCRM"
    flxGrdConflict.GridLines = flexGridNone
    
    If gsNtfyConflict = 0 Then
        optCnflct(0).Value = True
    ElseIf gsNtfyConflict = 1 Then
        cmdSave.Caption = "Ok"
        optCnflct(0).Value = True
        optCnflct(0).Enabled = False
        optCnflct(1).Enabled = False
    ElseIf gsNtfyConflict = 2 Then
        cmdSave.Caption = "Ok"
        optCnflct(1).Value = True
        optCnflct(0).Enabled = False
        optCnflct(1).Enabled = False
    End If
End Sub

Public Function bPopulateConflict(ByVal oXMLLocalOl As MSXML.IXMLDOMElement, _
                                  ByVal oXMLLocalVt As MSXML.IXMLDOMElement) As Boolean

On Error GoTo ERROR_EXIT_ROUTINE
Dim i As Integer
If Not oXMLLocalOl Is Nothing And Not oXMLLocalVt Is Nothing Then
    flxGrdConflict.Rows = oXMLLocalOl.childNodes.Length + 1
    flxGrdConflict.GridLines = flexGridFlat
    For i = 0 To oXMLLocalOl.childNodes.Length - 1
        flxGrdConflict.Row = i + 1
        flxGrdConflict.ColAlignment(1) = 1
        flxGrdConflict.ColAlignment(2) = 1
        Call AddFlxGrdRow(flxGrdConflict, 0, oXMLLocalOl.childNodes(i).nodeName)
        Call AddFlxGrdRow(flxGrdConflict, 1, DecodeUTF8(oXMLLocalOl.childNodes(i).nodeTypedValue))
        Call AddFlxGrdRow(flxGrdConflict, 2, DecodeUTF8(oXMLLocalVt.childNodes(i).nodeTypedValue))
    Next i
End If

bPopulateConflict = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
bPopulateConflict = False
sMsgDlg (Err.Description)
EXIT_ROUTINE:
End Function

