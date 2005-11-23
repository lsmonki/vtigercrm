VERSION 5.00
Begin VB.Form frmBusy 
   BorderStyle     =   0  'None
   Caption         =   "Form1"
   ClientHeight    =   795
   ClientLeft      =   0
   ClientTop       =   0
   ClientWidth     =   3360
   Icon            =   "frmBusy.frx":0000
   LinkTopic       =   "Form1"
   Picture         =   "frmBusy.frx":000C
   ScaleHeight     =   795
   ScaleWidth      =   3360
   ShowInTaskbar   =   0   'False
   StartUpPosition =   1  'CenterOwner
   Begin VB.Label Label1 
      BackColor       =   &H80000005&
      Caption         =   "Getting Contacts from vtigerCRM"
      BeginProperty Font 
         Name            =   "Tahoma"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   240
      TabIndex        =   0
      Top             =   240
      Width           =   2895
   End
End
Attribute VB_Name = "frmBusy"
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
   Dim transColor As Long
   transColor = &H8000FF
   Me.BackColor = transColor
   
   Dim lStyle As Long
   lStyle = GetWindowLong(hWnd, GWL_EXSTYLE)
   lStyle = lStyle Or WS_EX_LAYERED
   SetWindowLong hWnd, GWL_EXSTYLE, lStyle
      
   SetLayeredWindowAttributes Me.hWnd, transColor, 255, LWA_COLORKEY Or LWA_ALPHA
End Sub
