VERSION 5.00
Object = "{683364A1-B37D-11D1-ADC5-006008A5848C}#1.0#0"; "dhtmled.ocx"
Begin VB.Form frmForumDesc 
   BorderStyle     =   0  'None
   Caption         =   "vtigerCRM Outlook Discussions"
   ClientHeight    =   4800
   ClientLeft      =   0
   ClientTop       =   0
   ClientWidth     =   6660
   BeginProperty Font 
      Name            =   "Tahoma"
      Size            =   8.25
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   Icon            =   "frmForumDesc.frx":0000
   MaxButton       =   0   'False
   MinButton       =   0   'False
   Picture         =   "frmForumDesc.frx":000C
   ScaleHeight     =   4800
   ScaleWidth      =   6660
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.CommandButton OKButton 
      Caption         =   "&Close"
      Height          =   375
      Left            =   5160
      TabIndex        =   1
      Top             =   4200
      Width           =   1215
   End
   Begin DHTMLEDLibCtl.DHTMLEdit DHTMLEdit1 
      Height          =   3855
      Left            =   180
      TabIndex        =   0
      Tag             =   "101"
      Top             =   180
      Width           =   6255
      ActivateApplets =   0   'False
      ActivateActiveXControls=   0   'False
      ActivateDTCs    =   -1  'True
      ShowDetails     =   0   'False
      ShowBorders     =   0   'False
      Appearance      =   0
      Scrollbars      =   -1  'True
      ScrollbarAppearance=   0
      SourceCodePreservation=   -1  'True
      AbsoluteDropMode=   0   'False
      SnapToGrid      =   0   'False
      SnapToGridX     =   50
      SnapToGridY     =   50
      BrowseMode      =   -1  'True
      UseDivOnCarriageReturn=   0   'False
   End
End
Attribute VB_Name = "frmForumDesc"
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
Private Declare Function SendMessageLong Lib "USER32" Alias "SendMessageA" (ByVal hWnd As Long, ByVal wMsg As Long, ByVal wParam As Long, ByVal lParam As Long) As Long
Private Declare Function ReleaseCapture Lib "USER32" () As Long
Private Const WM_SYSCOMMAND = &H112&
Private Const SC_MOVE = &HF010&
Private Const WM_NCLBUTTONDOWN = &HA1
Private Const HTCAPTION = 2

Private Declare Function GetWindowLong Lib "USER32" Alias "GetWindowLongA" _
   (ByVal hWnd As Long, ByVal nIndex As Long) As Long
Private Declare Function SetWindowLong Lib "USER32" Alias "SetWindowLongA" _
   (ByVal hWnd As Long, ByVal nIndex As Long, _
   ByVal dwNewLong As Long) As Long
Private Const GWL_STYLE = (-16)
Private Const GWL_EXSTYLE = (-20)

'Requires Windows 2000 or later:
Private Const WS_EX_LAYERED = &H80000
Private Type BLENDFUNCTION
   BlendOp As Byte
   BlendFlags As Byte
   SourceConstantAlpha As Byte
   AlphaFormat As Byte
End Type
'//
'// currently defined blend function
'//

Private Const AC_SRC_OVER = &H0

'//
'// alpha format flags
'//
Private Const AC_SRC_ALPHA = &H1
Private Const AC_SRC_NO_PREMULT_ALPHA = &H1
Private Const AC_SRC_NO_ALPHA = &H2
Private Const AC_DST_NO_PREMULT_ALPHA = &H10
Private Const AC_DST_NO_ALPHA = &H20

Private Declare Function SetLayeredWindowAttributes Lib "USER32" _
   (ByVal hWnd As Long, ByVal crKey As Long, _
   ByVal bAlpha As Byte, ByVal dwFlags As Long) As Long
Private Const LWA_COLORKEY = &H1
Private Const LWA_ALPHA = &H2

Private Declare Function UpdateLayeredWindow Lib "USER32" _
   (ByVal hWnd As Long, ByVal hdcDst As Long, pptDst As Any, _
   psize As Any, ByVal hdcSrc As Long, _
   pptSrc As Any, crKey As Long, _
   ByVal pblend As Long, ByVal dwFlags As Long) As Long
Private Const ULW_COLORKEY = &H1
Private Const ULW_ALPHA = &H2
Private Const ULW_OPAQUE = &H4

Private Declare Function RedrawWindow Lib "USER32" (ByVal hWnd As Long, lprcUpdate As Any, ByVal hrgnUpdate As Long, ByVal fuRedraw As Long) As Long
Private Const RDW_ALLCHILDREN = &H80
Private Const RDW_ERASE = &H4
Private Const RDW_FRAME = &H400
Private Const RDW_INVALIDATE = &H1
Private Sub Form_Load()
   
''   Me.Top = frmOlForum.sMTop
''   Me.Left = frmOlForum.sMLeft
   
   Dim transColor As Long
   transColor = &H8000FF
   Me.BackColor = transColor
   
   Dim lStyle As Long
   lStyle = GetWindowLong(hWnd, GWL_EXSTYLE)
   lStyle = lStyle Or WS_EX_LAYERED
   SetWindowLong hWnd, GWL_EXSTYLE, lStyle
      
   SetLayeredWindowAttributes Me.hWnd, transColor, 255, LWA_COLORKEY Or LWA_ALPHA

    DHTMLEdit1.DocumentHTML = "<p style='font-family: Arial;font-size: 10pt'>" & frmOlForum.sZohoDesc & "</p>"
End Sub
Private Sub OKButton_Click()
    Unload frmForumDesc
End Sub

