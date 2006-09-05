VERSION 5.00
Object = "{55473EAC-7715-4257-B5EF-6E14EBD6A5DD}#1.0#0"; "vbalProgBar6.ocx"
Begin VB.Form frmSync 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "Synchronizing"
   ClientHeight    =   2460
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   5610
   BeginProperty Font 
      Name            =   "Tahoma"
      Size            =   8.25
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   Icon            =   "frmSync.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   2460
   ScaleWidth      =   5610
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.Timer Timer1 
      Interval        =   1000
      Left            =   120
      Top             =   1920
   End
   Begin VB.CommandButton btnCancel 
      Caption         =   "&Cancel"
      Height          =   375
      Left            =   4200
      TabIndex        =   1
      Top             =   2000
      Width           =   1215
   End
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   615
      Left            =   0
      Picture         =   "frmSync.frx":000C
      ScaleHeight     =   615
      ScaleWidth      =   5655
      TabIndex        =   0
      Top             =   0
      Width           =   5655
   End
   Begin VB.Frame Frame1 
      Height          =   1335
      Left            =   -120
      TabIndex        =   2
      Top             =   540
      Width           =   6015
      Begin vbalProgBarLib6.vbalProgressBar PrgBarSync 
         Height          =   255
         Left            =   240
         TabIndex        =   4
         Top             =   720
         Width           =   5370
         _ExtentX        =   9472
         _ExtentY        =   450
         Picture         =   "frmSync.frx":1239
         ForeColor       =   0
         BarPicture      =   "frmSync.frx":1255
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
      End
      Begin VB.Label lblSynStatus 
         Caption         =   "Reading...."
         Height          =   255
         Left            =   240
         TabIndex        =   3
         Top             =   480
         Width           =   5280
      End
   End
End
Attribute VB_Name = "frmSync"
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
Public sSyncFlag As Boolean
Private Sub btnCancel_Click()
    Me.Hide
End Sub
Private Sub Form_Load()
    'Timer1.Enabled = True
End Sub
Private Sub Form_Paint()
    'PrgBarSync.Min = 0
    Timer1.Interval = 500
    Timer1.Enabled = True
End Sub
Private Sub Timer1_Timer()
    If sSyncFlag = True Then Exit Sub
    sSyncFlag = True
    If gsSyncModule = "CONTACTSYNC" Then
        Call SyncMain("CONTACTSYNC")
    ElseIf gsSyncModule = "TASKSYNC" Then
        Call SyncMain("TASKSYNC")
    ElseIf gsSyncModule = "CALENDARSYNC" Then
        Call SyncMain("CALENDARSYNC")
    End If
    Timer1.Interval = 0
End Sub
