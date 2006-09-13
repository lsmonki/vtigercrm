VERSION 5.00
Begin VB.Form frmConf 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "vtigerCRM - Configuration"
   ClientHeight    =   6135
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   5130
   Icon            =   "frmConf.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   6135
   ScaleWidth      =   5130
   ShowInTaskbar   =   0   'False
   StartUpPosition =   3  'Windows Default
   Begin VB.PictureBox Picture1 
      BorderStyle     =   0  'None
      Height          =   495
      Left            =   240
      Picture         =   "frmConf.frx":058A
      ScaleHeight     =   495
      ScaleWidth      =   630
      TabIndex        =   24
      Top             =   120
      Width           =   630
   End
   Begin VB.CommandButton OKButton 
      Caption         =   "OK"
      Height          =   375
      Left            =   2400
      TabIndex        =   8
      Top             =   5640
      Width           =   1215
   End
   Begin VB.CommandButton CancelButton 
      Caption         =   "Cancel"
      Height          =   375
      Left            =   3780
      TabIndex        =   9
      Top             =   5640
      Width           =   1215
   End
   Begin VB.Frame Frame1 
      Caption         =   "vtigerCRM Configuration"
      Height          =   1905
      Left            =   120
      TabIndex        =   17
      Top             =   720
      Width           =   4890
      Begin VB.TextBox txtVtUser 
         Height          =   285
         Left            =   1800
         TabIndex        =   1
         Top             =   315
         Width           =   2850
      End
      Begin VB.TextBox txtVtPwd 
         Height          =   285
         IMEMode         =   3  'DISABLE
         Left            =   1800
         PasswordChar    =   "*"
         TabIndex        =   2
         Top             =   720
         Width           =   2850
      End
      Begin VB.TextBox txtVtUrl 
         Height          =   285
         IMEMode         =   3  'DISABLE
         Left            =   1800
         TabIndex        =   3
         Top             =   1125
         Width           =   2850
      End
      Begin VB.Label Label11 
         Caption         =   "(E.g., http://en.vtiger.com/)"
         Height          =   255
         Left            =   1830
         TabIndex        =   21
         Top             =   1500
         Width           =   2745
      End
      Begin VB.Label Label3 
         Caption         =   "vtiger URL:"
         Height          =   345
         Left            =   150
         TabIndex        =   20
         Top             =   1155
         Width           =   1515
      End
      Begin VB.Label Label2 
         Caption         =   "vtiger Password:"
         Height          =   360
         Left            =   150
         TabIndex        =   19
         Top             =   735
         Width           =   1530
      End
      Begin VB.Label Label1 
         Caption         =   "vtiger User Name:"
         Height          =   330
         Left            =   150
         TabIndex        =   18
         Top             =   345
         Width           =   1635
      End
   End
   Begin VB.Frame Frame2 
      Caption         =   "Configure Proxy to access vtigerCRM via Internet"
      Height          =   2565
      Left            =   120
      TabIndex        =   0
      Top             =   2880
      Width           =   4890
      Begin VB.OptionButton OptPrxy 
         Caption         =   "Option1"
         Height          =   195
         Index           =   1
         Left            =   285
         TabIndex        =   23
         Top             =   680
         Width           =   165
      End
      Begin VB.OptionButton OptPrxy 
         Caption         =   "Option1"
         Height          =   195
         Index           =   0
         Left            =   285
         TabIndex        =   10
         Top             =   390
         Width           =   165
      End
      Begin VB.TextBox txtPrxyPort 
         Height          =   285
         Left            =   3795
         TabIndex        =   5
         Top             =   1125
         Width           =   840
      End
      Begin VB.TextBox txtPrxyAddrs 
         Height          =   285
         Left            =   1680
         TabIndex        =   4
         Top             =   1125
         Width           =   1410
      End
      Begin VB.TextBox txtPrxyPwd 
         Height          =   285
         IMEMode         =   3  'DISABLE
         Left            =   1680
         PasswordChar    =   "*"
         TabIndex        =   7
         Top             =   1980
         Width           =   2970
      End
      Begin VB.TextBox txtPrxyUser 
         Height          =   285
         Left            =   1680
         TabIndex        =   6
         Top             =   1560
         Width           =   2970
      End
      Begin VB.Label lblManPrxy 
         Caption         =   "Manual Proxy Configuration"
         Height          =   225
         Left            =   585
         TabIndex        =   16
         Top             =   660
         Width           =   4155
      End
      Begin VB.Label Label4 
         Caption         =   "Direct connection to the Internet"
         Height          =   180
         Left            =   585
         TabIndex        =   15
         Top             =   375
         Width           =   4155
      End
      Begin VB.Label lblPrxyPwd 
         AutoSize        =   -1  'True
         Caption         =   "Password:"
         Height          =   435
         Left            =   120
         TabIndex        =   14
         Top             =   2025
         Width           =   1455
      End
      Begin VB.Label lblPrxyPort 
         AutoSize        =   -1  'True
         Caption         =   "Port:"
         Height          =   315
         Left            =   3270
         TabIndex        =   13
         Top             =   1140
         Width           =   450
      End
      Begin VB.Label lblPrxyAddrs 
         AutoSize        =   -1  'True
         Caption         =   "Address:"
         Height          =   420
         Left            =   120
         TabIndex        =   12
         Top             =   1155
         Width           =   1455
      End
      Begin VB.Label lblPrxyUser 
         AutoSize        =   -1  'True
         Caption         =   "Proxy User Name:"
         Height          =   195
         Left            =   120
         TabIndex        =   11
         Top             =   1560
         Width           =   1275
      End
   End
   Begin VB.Label Label5 
      Caption         =   "Please enter the following details to access your secure vtigerCRM"
      BeginProperty Font 
         Name            =   "Microsoft Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   540
      Left            =   960
      TabIndex        =   22
      Top             =   120
      Width           =   4035
   End
End
Attribute VB_Name = "frmConf"
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

Private Sub CancelButton_Click()
Unload frmConf
End Sub

Private Sub Form_Load()
    'Language Implementation
    
    frmConf.Caption = gfrmConf_FormName
    Label5.Caption = gfrmConf_Label1
    OKButton.Caption = gfrmConf_Button1
    CancelButton.Caption = gfrmConf_Button2
    
    Frame1.Caption = gfrmConf_Frame1
    Label1.Caption = gfrmConf_Frm1_Lbl1
    Label2.Caption = gfrmConf_Frm1_Lbl2
    Label3.Caption = gfrmConf_Frm1_Lbl3
    Label11.Caption = gfrmConf_Frm1_Lbl4
      
    Frame2.Caption = gfrmConf_Frame2
    lblPrxyAddrs.Caption = gfrmConf_Frm2_Lbl1
    lblPrxyPort.Caption = gfrmConf_Frm2_Lbl2
    lblPrxyUser.Caption = gfrmConf_Frm2_Lbl3
    lblPrxyPwd.Caption = gfrmConf_Frm2_Lbl4
    
    Label4.Caption = gfrmConf_Frm2_Opt1
    lblManPrxy.Caption = gfrmConf_Frm2_Opt2
    
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    If gproxyenabled = "0" Then
        OptPrxy(0).Value = True
        Call bDisableProxy
    ElseIf gproxyenabled = "1" Then
        OptPrxy(1).Value = True
        Call bEnableProxy
    End If
    
    Call bPopConf
    
End Sub

Public Function bDisableProxy()

On Error GoTo ERROR_EXIT_ROUTINE

    lblPrxyAddrs.Enabled = False
    lblPrxyPort.Enabled = False
    lblPrxyUser.Enabled = False
    lblPrxyPwd.Enabled = False
    
    txtPrxyAddrs.Enabled = False
    txtPrxyPort.Enabled = False
    txtPrxyUser.Enabled = False
    txtPrxyPwd.Enabled = False

ERROR_EXIT_ROUTINE:

EXIT_ROUTINE:
End Function
Public Function bEnableProxy()

On Error GoTo ERROR_EXIT_ROUTINE

    lblPrxyAddrs.Enabled = True
    lblPrxyPort.Enabled = True
    lblPrxyUser.Enabled = True
    lblPrxyPwd.Enabled = True
    
    txtPrxyAddrs.Enabled = True
    txtPrxyPort.Enabled = True
    txtPrxyUser.Enabled = True
    txtPrxyPwd.Enabled = True

ERROR_EXIT_ROUTINE:

EXIT_ROUTINE:

End Function


Private Sub OKButton_Click()
Dim sInfoMsg As String

Dim svtMsg As String
Dim sPrxyMsg As String
Dim nCount As Integer
    
    nCount = 0
    
    If Trim(txtVtUser.Text) = "" Then
        nCount = nCount + 1
        sInfoMsg = nCount & ". " & gMsg010 & vbCrLf
    End If
    If Trim(txtVtPwd.Text) = "" Then
        nCount = nCount + 1
        sInfoMsg = sInfoMsg & nCount & ". " & gMsg011 & vbCrLf
    End If
    If Trim(txtVtUrl.Text) = "" Then
        nCount = nCount + 1
        sInfoMsg = sInfoMsg & nCount & ". " & gMsg012 & vbCrLf
    End If
    
    If OptPrxy(1).Value = True Then
        If Trim(txtPrxyAddrs.Text) = "" Then
            nCount = nCount + 1
            sInfoMsg = sInfoMsg & nCount & ". " & gMsg013 & vbCrLf
        End If
        If Trim(txtPrxyPort.Text) = "" Then
            nCount = nCount + 1
            sInfoMsg = sInfoMsg & nCount & ". " & gMsg014 & vbCrLf
        End If
        If Trim(txtPrxyUser.Text) = "" Then
            nCount = nCount + 1
            sInfoMsg = sInfoMsg & nCount & ". " & gMsg015 & vbCrLf
        End If
        If Trim(txtPrxyPwd.Text) = "" Then
            nCount = nCount + 1
            sInfoMsg = sInfoMsg & nCount & ". " & gMsg016 & vbCrLf
        End If
    End If
    
    If sInfoMsg <> "" Then
        GoTo ERROR_EXIT_ROUTINE
    End If
    
    Call bSaveConf
    Unload frmConf

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:

If sInfoMsg <> "" Then
    svtMsg = gMsg009 & vbCrLf & sInfoMsg
    Call sErrorDlg(svtMsg)
End If
EXIT_ROUTINE:

End Sub

Private Sub OptPrxy_Click(Index As Integer)

    If OptPrxy(1).Value = True Then
    Call bEnableProxy
    End If
    
    If OptPrxy(0).Value = True Then
    Call bDisableProxy
    End If

End Sub

Public Function bPopConf()

On Error GoTo ERROR_EXIT_ROUTINE

    txtVtUser.Text = Trim(gvtigerusername)
    txtVtPwd.Text = Trim(gvtigerpassword)
    txtVtUrl.Text = Trim(gvtigerurl)
    
    txtPrxyAddrs.Text = Trim(gproxyaddress)
    txtPrxyPort.Text = Trim(gproxyport)
    txtPrxyUser.Text = Trim(gproxyusername)
    txtPrxyPwd.Text = Trim(gproxypassword)

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:

EXIT_ROUTINE:

End Function


Public Function bSaveConf()

On Error GoTo ERROR_EXIT_ROUTINE

Dim sErrMsg As String

gvtigerusername = Trim(txtVtUser.Text)
gvtigerpassword = Trim(txtVtPwd.Text)
gvtigerurl = Trim(txtVtUrl.Text)

sErrMsg = gMsg008

SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_VTIGER_USERNAME, gvtigerusername
SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_VTIGER_PASSWORD, Encrypt(gvtigerpassword, "vtigerCRM")
SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_VTIGER_URL, gvtigerurl

If OptPrxy(1).Value = True Then
    gproxyenabled = "1"
    gproxyaddress = Trim(txtPrxyAddrs.Text)
    gproxyport = Trim(txtPrxyPort.Text)
    gproxyusername = Trim(txtPrxyUser.Text)
    gproxypassword = Trim(txtPrxyPwd.Text)
    
    sErrMsg = gMsg008
    SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_ENABLED, gproxyenabled
    SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_ADDRS, gproxyaddress
    SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_PORT, gproxyport
    SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_USERNAME, gproxyusername
    SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_PASSWORD, Encrypt(gproxypassword, "vtigerCRM")
ElseIf OptPrxy(0).Value = True Then
    gproxyenabled = "0"
    SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_ENABLED, gproxyenabled
End If

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
Call sErrorDlg(sErrMsg)
EXIT_ROUTINE:

End Function

Public Function bConfCheck()

End Function
