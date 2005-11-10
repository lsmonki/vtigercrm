VERSION 5.00
Begin VB.Form frmvtigerLogin 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "vtigerCRM Word Add-In"
   ClientHeight    =   1665
   ClientLeft      =   2175
   ClientTop       =   1935
   ClientWidth     =   4380
   Icon            =   "frmvtigerLogin.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   1665
   ScaleWidth      =   4380
   ShowInTaskbar   =   0   'False
   StartUpPosition =   2  'CenterScreen
End
Attribute VB_Name = "frmvtigerLogin"
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

Public VBInstance As VBIDE.VBE
Public Connect As Connect

Option Explicit

Private Sub CancelButton_Click()
    Connect.Hide
End Sub

Private Sub OKButton_Click()
    MsgBox "AddIn operation on: " & VBInstance.FullName
End Sub

