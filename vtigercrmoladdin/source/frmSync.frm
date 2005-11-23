VERSION 5.00
Begin VB.Form frmSync 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "Synchronizing"
   ClientHeight    =   2595
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
   ScaleHeight     =   2595
   ScaleWidth      =   5610
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.CommandButton btnCancel 
      Caption         =   "&Cancel"
      Height          =   375
      Left            =   4200
      TabIndex        =   1
      Top             =   2120
      Width           =   1215
   End
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   735
      Left            =   0
      Picture         =   "frmSync.frx":000C
      ScaleHeight     =   735
      ScaleWidth      =   5655
      TabIndex        =   0
      Top             =   0
      Width           =   5655
   End
   Begin VB.Frame Frame1 
      Height          =   1335
      Left            =   -120
      TabIndex        =   2
      Top             =   660
      Width           =   6015
      Begin VB.PictureBox PrgBarSync 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00000000&
         Height          =   255
         Left            =   240
         ScaleHeight     =   195
         ScaleWidth      =   5235
         TabIndex        =   4
         Top             =   720
         Width           =   5295
      End
      Begin VB.Label lblSynStatus 
         Caption         =   "Reading Contacts....10% Completed"
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
Option Explicit
Public sSyncFlag As Boolean
Private Sub btnCancel_Click()
    Me.Hide
End Sub
Private Sub Form_Paint()
    If sSyncFlag = True Then Exit Sub
    sSyncFlag = True
    Call SyncMain("CONTACTSYNC")
End Sub
