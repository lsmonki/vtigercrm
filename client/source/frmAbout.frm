VERSION 5.00
Begin VB.Form frmAbout 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "About vtigerCRM Word Add-In"
   ClientHeight    =   2400
   ClientLeft      =   6765
   ClientTop       =   4695
   ClientWidth     =   4560
   Icon            =   "frmAbout.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   2400
   ScaleWidth      =   4560
   ShowInTaskbar   =   0   'False
   Begin VB.PictureBox Picture1 
      BorderStyle     =   0  'None
      Height          =   735
      Left            =   960
      Picture         =   "frmAbout.frx":058A
      ScaleHeight     =   735
      ScaleWidth      =   2535
      TabIndex        =   4
      Top             =   120
      Width           =   2535
   End
   Begin VB.CommandButton OKButton 
      Caption         =   "OK"
      Height          =   375
      Left            =   3240
      TabIndex        =   3
      Top             =   1920
      Width           =   1215
   End
   Begin VB.Label Label3 
      Caption         =   "Word Version 9.0"
      Height          =   255
      Left            =   120
      TabIndex        =   2
      Top             =   1920
      Width           =   3015
   End
   Begin VB.Label Label2 
      Caption         =   "Copyright © 2004-2005 vtiger.com. All rights reserverd."
      Height          =   615
      Left            =   120
      TabIndex        =   1
      Top             =   1200
      Width           =   4335
   End
   Begin VB.Label Label1 
      Caption         =   "Word Add-In Version 1.0"
      Height          =   255
      Left            =   120
      TabIndex        =   0
      Top             =   960
      Width           =   4335
   End
End
Attribute VB_Name = "frmAbout"
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

Private Sub Form_Load()
    'Language Implmentation
    frmAbout.Caption = gfrmAbout_FormName & " " & gPrdName
    Label1.Caption = gPrdName & " " & gPrdVersion
    Label2.Caption = gfrmAbout_Label2
    Label3.Caption = gfrmAbout_Label3
    OKButton.Caption = gfrmAbout_OkButton
    
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
End Sub

Private Sub OKButton_Click()
Unload frmAbout
End Sub

