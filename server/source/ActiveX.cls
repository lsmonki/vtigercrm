VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "Activex"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
' * All Rights Reserved.
'*
' ********************************************************************************/

Option Explicit
Dim bBulkData As Boolean
Dim oWord As Object
Dim oDoc As Object
Dim sDocPath As String
Dim sHeadSrc() As String
Dim sDataSrc() As String

'**************************************************************************
'                       API FOR GETTING TEMPORAY PATH
'**************************************************************************
Private Const MAX_PATH = 255

Private Declare Function GetTempPath Lib "Kernel32" Alias "GetTempPathA" _
(ByVal nBufferLength As Long, ByVal lpBuffer As String) As Long

'**************************************************************************
'                       API TO DOWNLOAD THE FILE FROM URL
'**************************************************************************
Private Declare Function URLDownloadToFile Lib "urlmon" Alias _
    "URLDownloadToFileA" (ByVal pCaller As Long, ByVal szURL As String, _
    ByVal szFileName As String, ByVal dwReserved As Long, _
    ByVal lpfnCB As Long) As Long
    
'**************************************************************************
'                       API TO DELETE THE FILE
'**************************************************************************
Private Declare Function DeleteFile Lib "Kernel32" Alias "DeleteFileA" (ByVal lpFileName As String) As Long
Public Function GetTempDir() As String
    Dim sRet As String, lngLen As Long
    'create buffer
    sRet = String(MAX_PATH, 0)
    lngLen = GetTempPath(MAX_PATH, sRet)
    If lngLen = 0 Then Err.Raise Err.LastDllError
    GetTempDir = Left$(sRet, lngLen)
End Function
Public Function Init() As Boolean
    On Error GoTo ERROR_EXIT_ROUTINE
    Dim sErrMsg As String
    
    sErrMsg = "Please ensure that Microsoft Word is installed on your machine." & vbCrLf & "ActiveX component can't create object"
    Set oWord = CreateObject("Word.Application")
    
    bBulkData = True
    Init = True
    
    GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    MsgBox sErrMsg, vbExclamation, "vtiger CRM"
    Init = False
EXIT_ROUTINE:
End Function
Public Function vGetHDSrc(ByVal sHSrc As String, ByVal sDSrc As String)
On Error GoTo ERROR_EXIT_ROUTINE
If sDSrc <> "" And sHSrc <> "" Then
sHeadSrc = Split(sHSrc, ",")
sDataSrc = Split(sDSrc, ",")
bBulkData = False
End If
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
EXIT_ROUTINE:
End Function
Public Function vOpenDoc()
    
    On Error GoTo ERROR_EXIT_ROUTINE
    
    Dim oWordField As Word.MailMergeField
    Dim oWordFieldRange As Word.Range
    Dim sFieldSplit() As String
    Dim n As Integer
                   
    For Each oWordField In oDoc.MailMerge.Fields
         
         oWordField.Select
         Set oWordFieldRange = oWord.Selection.Range
         'sFieldSplit = Split(oWordField.Code, " ")
         
         For n = LBound(sHeadSrc) To UBound(sHeadSrc)
            If oWordFieldRange = "«" & sHeadSrc(n) & "»" Then
                If Trim(sDataSrc(n)) = "" Then
                    oWordFieldRange.Text = "__________"
                Else
                    oWordFieldRange.Text = sDataSrc(n)
                End If
            End If
         Next n
    
    Next oWordField
    
    'oDoc.MailMerge.Destination = wdSendToNewDocument
    'oDoc.Close (False)
    oDoc.Save
    oWord.Visible = True
    
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
Err.Clear
EXIT_ROUTINE:
    'oDoc.Close (False)
    'Set oDoc = Nothing
    'Set oWord = Nothing
    Set oWordField = Nothing
    Set oWordFieldRange = Nothing
End Function
Public Function bDLTempDoc(ByVal sWebFilePath As String, ByVal sFileName As String) As Boolean
    
    On Error GoTo ERROR_EXIT_ROUTINE
    Dim sDownLoad As String
    Dim sErrMsg As String
    Dim errcode As Long

    If sWebFilePath <> "" And sFileName <> "" Then
        errcode = URLDownloadToFile(0, sWebFilePath, GetTempDir & sFileName, 0, 0)
        If errcode <> 0 Then
            sErrMsg = "vtiger CRM cannot download template document"
            GoTo ERROR_EXIT_ROUTINE
        End If
    Else
        sErrMsg = "Invalid template file path"
        GoTo ERROR_EXIT_ROUTINE
    End If
    
    sDocPath = sFileName
    bDLTempDoc = True

    GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    MsgBox sErrMsg, vbExclamation, "vtiger CRM"
    bDLTempDoc = False
EXIT_ROUTINE:
End Function
Public Function vLTemplateDoc()
    On Error GoTo ERROR_EXIT_ROUTINE
    Dim sErrMsg As String
    
    sErrMsg = "vtiger CRM cannot access template document"
    Set oDoc = oWord.Documents.Open(GetTempDir & sDocPath)
    'Init = True
    
    GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    If IsObject(oWord) Then
        oWord.Quit
        Set oDoc = Nothing
        Set oWord = Nothing
    End If
    MsgBox sErrMsg, vbExclamation, "vtiger CRM"
    'Init = False
EXIT_ROUTINE:
End Function

Public Function bBulkHDSrc(ByVal sWebFilePath As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
    Dim sDownLoad As String
    Dim sErrMsg As String
    Dim errcode As Long
    
    If sWebFilePath <> "" Then
        errcode = URLDownloadToFile(0, sWebFilePath, GetTempDir & "DataSrc.csv", 0, 0)
        If errcode <> 0 Then
            sErrMsg = "vtigerCRM cannot download data source"
            GoTo ERROR_EXIT_ROUTINE
        End If
    Else
        sErrMsg = "Invalid data source file path"
        GoTo ERROR_EXIT_ROUTINE
    End If
    bBulkHDSrc = True
    GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    MsgBox sErrMsg, vbExclamation, "vtiger CRM"
    bBulkHDSrc = False
EXIT_ROUTINE:
End Function

Public Function vBulkOpenDoc()
    
    On Error GoTo ERROR_EXIT_ROUTINE
    oWord.DisplayAlerts = wdAlertsNone
    
    oDoc.MailMerge.OpenDataSource Name:=GetTempDir & "DataSrc.csv", LinkToSource:=False
    oDoc.MailMerge.Destination = wdSendToNewDocument
    oDoc.MailMerge.Execute Pause:=False

    oDoc.Save
    oDoc.Close (False)
    oWord.Visible = True
    
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
   
    oDoc.Close (False)
    oWord.Quit
    Set oDoc = Nothing
    Set oWord = Nothing
    
EXIT_ROUTINE:
    'oDoc.Close (False)
    'Set oDoc = Nothing
    Err.Clear
    'Set oWord = Nothing
    'Set oWordField = Nothing
    'Set oWordFieldRange = Nothing
End Function

Public Function UnInit() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE

Dim sFiles As String
Dim sErrMsg As String
Dim lErrCode As Long
Dim lErrCode1 As Long

If bBulkData Then
    sErrMsg = "vtiger CRM cannot delete source document"
    lErrCode1 = DeleteFile(GetTempDir & "DataSrc.csv")
    If lErrCode1 <> 1 Then GoTo ERROR_EXIT_ROUTINE

    sErrMsg = "vtiger CRM cannot delete template document"
    lErrCode = DeleteFile(GetTempDir & sDocPath)
    If lErrCode <> 1 Then GoTo ERROR_EXIT_ROUTINE
End If

UnInit = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    MsgBox sErrMsg, vbExclamation, "vtiger CRM"
    UnInit = False
EXIT_ROUTINE:
End Function


