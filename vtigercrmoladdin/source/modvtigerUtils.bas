Attribute VB_Name = "modvtigerUtils"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit

'For Outlook Explorer
Public golApp As Outlook.Application
Public gstrProgID As String
Public gcolExplWrap As New Collection
Public gblnNewExpl As Boolean
Dim nID As Integer
Dim blnActivate As Boolean

'Date formats
Public Const DB_DATE_TIME_SEC_FORMAT As String = "YYYY-MM-DD"
Public Const DB_DATE_TIME_LOG_SEC_FORMAT As String = "DDD MMM YYYY HH:MM:SS"
Public Const DB_DATE_TIME_LOG_FORMAT As String = "YYYY-MM-DD HH:MM:SS"

'log files & attachments costants
Public Const VTIGER_LOG_FILE As String = "\vtigercrm.log"
Public Const VTIGER_TEMP_ATTACH_FOLDER As String = "\Attachments"
Public Const VTIGER_USER_CONF = "\vtconf.xml"

Public oOlApp As New Outlook.Application

'Registry location
Public Const REG_PATH As String = "Software\vtiger\vtigerCRM Outlook Plug-in"
'Public Const REG_LANG_PATH As String = "Software\"

'Registry Key Names
Public Const R_KEY_APP_PATH As String = "applicationpath"
Public Const R_KEY_USER_PATH As String = "userdatapath"

Public nConflictOption As Integer

'Users Path
Private gapppath As String
Private guserdatapath As String

'For AddMsgToVtiger
Public gsContactEntityId() As Integer

'VtigerAdd-in Version
Public Const gsVtAddinVersion As String = "4.2.3"

'Users Path Accessble in all pages
Public gsVtUserLogFile As String
Public gsVtUserFolder As String

'For Vtiger Login Accessables
Public gsVtUserId As String
Public gsLoginSuccess As Boolean
Public gbOpenFlag As Boolean
Public gsVtUrl As String


'For Vtiger Sync Folder
Public gsCntsSyncFolderId As String
Public gsTaskSyncFolderId As String
Public gsClndrSyncFolderId As String
Public gsCntsSyncFolder As String
Public gsTaskSyncFolder As String
Public gsClndrSyncFolder As String
Public gsShowDlg As String
Public gsNtfyConflict As String
'**************************************************************************
'                       API FOR OPENING INTERNET CONNECTION
'**************************************************************************
'API-Deklarationen:
Private Declare Sub InternetCloseHandle Lib "wininet.dll" ( _
    ByVal hInet As Long)
Private Declare Function InternetOpenA Lib "wininet.dll" ( _
    ByVal sAgent As String, ByVal lAccessType As Long, _
    ByVal sProxyName As String, ByVal sProxyBypass As String, _
    ByVal lFlags As Long) As Long
Private Declare Function InternetOpenUrlA Lib "wininet.dll" ( _
    ByVal hOpen As Long, ByVal sUrl As String, _
    ByVal sHeaders As String, ByVal lLength As Long, _
    ByVal lFlags As Long, ByVal lContext As Long) As Long
Private Declare Sub InternetReadFile Lib "wininet.dll" ( _
    ByVal hFile As Long, ByVal sBuffer As String, _
    ByVal lNumBytesToRead As Long, lNumberOfBytesRead As Long)

'Enumeration für Internet:
Public Enum InternetOpenType
  IOTPreconfig = 0
  IOTDirect = 1
  IOTProxy = 3
End Enum

Public Declare Sub Sleep Lib "Kernel32" (ByVal dwMilliseconds As Long)

Private Declare Function ShellExecute Lib "shell32.dll" Alias "ShellExecuteA" (ByVal hWnd As Long, ByVal lpOperation As String, ByVal lpFile As String, ByVal lpParameters As String, ByVal lpDirectory As String, ByVal nShowCmd As Long) As Long

Public Function sMsgDlg(ByVal sMsg As String, Optional ByVal nDlgType As Integer = 0)
    If nDlgType = 0 Then
        MsgBox sMsg, vbInformation, "vtigerCRM Outlook Addin"
    ElseIf nDlgType = 1 Then
        MsgBox sMsg, vbCritical, "vtigerCRM Outlook Addin"
    ElseIf nDlgType = 2 Then
        MsgBox sMsg, vbExclamation, "vtigerCRM Outlook Addin"
    End If
End Function

Public Function LogTheMessage(ByVal sLogMsg As String)
On Error GoTo ERROR_EXIT_ROUTINE
Dim sDebugFile As String
Dim sLogMessage As String
Dim nFreeFile As Integer

If sLogMsg <> "" Then
    nFreeFile = FreeFile
    sDebugFile = gsVtUserLogFile
    sLogMessage = "[" & Format(Now, DB_DATE_TIME_LOG_FORMAT) & "] - " & sLogMsg
    Open sDebugFile For Append As #nFreeFile
            Print #nFreeFile, sLogMessage
    Close #nFreeFile
End If

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sMsgDlg ("Error while writing log message")
EXIT_ROUTINE:
End Function
Public Function sGetZohoUserFldr(ByVal svtigerId As String) As String

Dim oFS As New Scripting.FileSystemObject

If oFS.FolderExists(App.Path & "\data\" & svtigerId) = True Then
    sGetZohoUserFldr = App.Path & "\data\" & svtigerId
Else
    If oFS.FolderExists(App.Path & "\data") = False Then oFS.CreateFolder (App.Path & "\data")
    oFS.CreateFolder (App.Path & "\data\" & svtigerId)
    sGetZohoUserFldr = App.Path & "\data\" & svtigerId
End If

Set oFS = Nothing
End Function
Public Function sGetLogFile() As String

Dim oFS As New Scripting.FileSystemObject

If oFS.FileExists(gsVtUserFolder & VTIGER_LOG_FILE) = True Then
    oFS.CreateTextFile gsVtUserFolder & VTIGER_LOG_FILE, True
    sGetLogFile = gsVtUserFolder & VTIGER_LOG_FILE
Else
    oFS.CreateTextFile (gsVtUserFolder & VTIGER_LOG_FILE)
    sGetLogFile = gsVtUserFolder & VTIGER_LOG_FILE
End If

Set oFS = Nothing

End Function

Public Function bIntializeLogFile(ByVal sVtId As String) As Boolean
   
    On Error GoTo ERROR_EXIT_ROUTINE
    
    Dim nFreeFile As Integer
    Dim sDebugFile As String
        
    If sVtId <> "" Then
    
        gsVtUserFolder = sGetZohoUserFldr(sVtId)
        gsVtUserLogFile = sGetLogFile()
        
        If gsVtUserLogFile <> "" Then
            nFreeFile = FreeFile
            sDebugFile = gsVtUserLogFile
            Open sDebugFile For Append As #nFreeFile
                    Print #nFreeFile, "-------------------------------------------------------------------------------------"
                    Print #nFreeFile, " vtigerCRM Outlook Addin Log - " & sVtId & " - " & Format(Now, DB_DATE_TIME_LOG_SEC_FORMAT)
                    Print #nFreeFile, " Version - " & gsVtAddinVersion
                    Print #nFreeFile, "-------------------------------------------------------------------------------------"
                    Print #nFreeFile, ""
            Close #nFreeFile
        End If
        
        LogTheMessage ("Initializing log file successfull")
        bIntializeLogFile = True
        GoTo EXIT_ROUTINE
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
    
ERROR_EXIT_ROUTINE:
    'LogTheMessage ("Intializing log file Failed " & Err.Description)
    bIntializeLogFile = False
EXIT_ROUTINE:
End Function

'Populating the ListView1 By Parsing the XML File
Public Function bPopulateCntsList(ByVal sSearchDetailsXML As String, ByRef FlxGrd As MSHFlexGrid) As Boolean
                    
On Error GoTo ERROR_EXIT_ROUTINE
Dim oXMLDocument As New MSXML.DOMDocument
Dim oXMLNodeList As IXMLDOMNodeList
Dim oXMLFirstNode As IXMLDOMNode
Dim sFrstName As String
Dim sLastName As String
Dim i As Integer
Dim j As Integer
Dim sEntityType As String

If sSearchDetailsXML <> "" Then
    If oXMLDocument.loadXML(sSearchDetailsXML) = True Then
        Set oXMLNodeList = oXMLDocument.documentElement.childNodes
        If oXMLNodeList.Length > 0 Then
            FlxGrd.Rows = oXMLNodeList.Length + 1
            FlxGrd.GridLines = flexGridFlat
            ReDim gsContactEntityId(oXMLNodeList.Length)
            For i = 0 To oXMLNodeList.Length - 1
                Set oXMLFirstNode = oXMLNodeList.Item(i)
                FlxGrd.Row = i + 1
                For j = 0 To oXMLFirstNode.childNodes.Length - 1
                    Call AddFlxGrdRow(FlxGrd, 0, "Contacts")
                    With oXMLFirstNode.childNodes(j)
                        If (.nodeName = "id") Then gsContactEntityId(i + 1) = .nodeTypedValue
                        If (.nodeName = "firstname") Then sFrstName = .nodeTypedValue
                        If (.nodeName = "lastname") Then sLastName = .nodeTypedValue
                        If (.nodeName = "accountname") Then Call AddFlxGrdRow(FlxGrd, 2, DecodeUTF8(.nodeTypedValue))
                        If (.nodeName = "emailaddress") Then Call AddFlxGrdRow(FlxGrd, 3, DecodeUTF8(.nodeTypedValue))
                    End With
                    Call AddFlxGrdRow(FlxGrd, 1, DecodeUTF8(sFrstName) & " " & DecodeUTF8(sLastName))
                Next j
            Next i
        Else
            sMsgDlg ("No Matching Records like " & Trim(frmAddMsg.txtAddress))
        End If
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
    sMsgDlg ("No Matching Records like " & Trim(frmAddMsg.txtAddress))
End If

bPopulateCntsList = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    bPopulateCntsList = False
    LogTheMessage ("bPopulateCntsList - " & Err.Description)
EXIT_ROUTINE:
Set oXMLFirstNode = Nothing
Set oXMLNodeList = Nothing
Set oXMLDocument = Nothing
End Function
Public Function AddFlxGrdRow(ByRef FlxGrd As MSHFlexGrid, ByVal ColIndex As Integer, ByVal sValue As String)
    FlxGrd.Col = ColIndex
    FlxGrd.Text = sValue
End Function
'for xml easy accessing
Public Sub AddChild(ByRef xmlDoc As MSXML.DOMDocument, _
        ByRef xmlParentNode As MSXML.IXMLDOMElement, _
        ByVal sNodeName As String, ByVal sValue As String)
        
Dim xml_Node As MSXML.IXMLDOMNode
Dim xmlElmt_Child As MSXML.IXMLDOMElement

    Set xmlElmt_Child = xmlDoc.createElement(sNodeName)
    Set xml_Node = xmlParentNode.appendChild(xmlElmt_Child)
    xmlElmt_Child.Text = sValue
    
End Sub
'for xml easy accessing
Public Function AddAttribute(ByRef oXMLElement As MSXML.IXMLDOMElement, _
                             ByVal sAttribName As String, ByVal sAttribValue As String)
    oXMLElement.setAttribute sAttribName, sAttribValue
End Function
'**********************************************************************************
'                            OPENING INTERNET CONNECTION
'**********************************************************************************
Public Function OpenURL( _
    ByVal url As String, _
    Optional ByVal OpenType As InternetOpenType = IOTPreconfig _
  ) As String
  
  Const INET_RELOAD = &H80000000
  Dim hInet As Long
  Dim hURL As Long
  Dim Buffer As String * 2048
  Dim Bytes As Long
  
  'Inet-Connection öffnen:
  hInet = InternetOpenA( _
      "Test:INET", OpenType, _
      vbNullString, vbNullString, 0)
  hURL = InternetOpenUrlA( _
      hInet, url, vbNullString, 0, INET_RELOAD, 0)
  
  'Daten sammeln:
  Do
    InternetReadFile hURL, Buffer, Len(Buffer), Bytes
    If Bytes = 0 Then Exit Do
    OpenURL = OpenURL & Left$(Buffer, Bytes)
  Loop
  
  'Inet-Connection schließen:
  InternetCloseHandle hURL
  InternetCloseHandle hInet
End Function
Public Function sGetPathAsString(ByVal oMapiFldr As Outlook.MAPIFolder) As String
Dim sPath As String
Dim sFlag As Boolean
sFlag = False
sPath = ""
Do While (1)
On Error GoTo EXIT_LOOP
    If (sFlag = True) Then
        sPath = oMapiFldr.Name & "\" & sPath
    Else
        sFlag = True
        sPath = oMapiFldr.Name
    End If
Set oMapiFldr = oMapiFldr.Parent
Loop
EXIT_LOOP:
Set oMapiFldr = Nothing
sGetPathAsString = sPath
End Function

Public Function Sh_Execute(ByVal url As String)
Dim hHandle As Long
ShellExecute hHandle, vbNullString, url, vbNullString, vbNullString, 0
End Function


Public Sub AddExpl(Explorer As Outlook.Explorer)
    Dim objExplWrap As New clsExplWrap
    
    objExplWrap.Explorer = Explorer
    
    objExplWrap.Key = nID
    gcolExplWrap.Add objExplWrap, CStr(nID)
    nID = nID + 1
End Sub

Public Sub KillExpl(anID As Integer, objExplWrap As clsExplWrap)
    Dim objExplWrap2 As clsExplWrap
    
    Set objExplWrap2 = gcolExplWrap.Item(CStr(anID))
    ' checks to make sure we're removing the
    ' right Explorer from the collection
    If Not objExplWrap2 Is objExplWrap Then
        Err.Raise 1, Description:="Unexpected Error in KillExpl"
        Exit Sub
    End If
    
    gcolExplWrap.Remove CStr(anID)
End Sub

Public Function CreateAddInCommandBarButton _
    (strProgID As String, objCommandBar As CommandBar, _
    strCaption As String, strTag As String, strTip As String, _
    intFaceID As Integer, blnBeginGroup As Boolean, intStyle As Integer) _
    As Office.CommandBarButton
    
    Dim ctlBtnAddin As CommandBarButton
    On Error Resume Next
    ' Test to determine if button exists on command bar.
    Set ctlBtnAddin = objCommandBar.FindControl(Tag:=strTag)
    If ctlBtnAddin Is Nothing Then
        ' Add new button.
        Set ctlBtnAddin = objCommandBar.Controls.Add(Type:=msoControlButton, _
            Parameter:=strTag)
        ' Set button's Caption, Tag, Style, and OnAction properties.
        With ctlBtnAddin
            .Caption = strCaption
            .Tag = strTag
            If intStyle <> msoButtonCaption Then
                .FaceId = intFaceID
            End If
            .Style = intStyle
            .ToolTipText = strTip
            .BeginGroup = blnBeginGroup
            ' Set the OnAction property with ProgID of Add-In
            .OnAction = "<!" & strProgID & ">"
        End With
    End If
    
    ' Return reference to new commandbar button.
    Set CreateAddInCommandBarButton = ctlBtnAddin

End Function

Public Function CreateAddInPopupButton _
    (strProgID As String, objCommandBar As CommandBarPopup, _
    strCaption As String, strTag As String, strTip As String, _
    intFaceID As Integer, blnBeginGroup As Boolean, intStyle As Integer) _
    As Office.CommandBarButton
    
    On Error Resume Next
    
    Dim ctlBtnAddin As CommandBarButton
    Dim i As Integer
    
    For i = 1 To objCommandBar.Controls.Count
        If objCommandBar.Controls.Item(i).Tag = strTag Then
            Set ctlBtnAddin = objCommandBar.Controls.Item(i)
        End If
    Next i
            
    If ctlBtnAddin Is Nothing Then
        Set ctlBtnAddin = objCommandBar.Controls.Add(Type:=msoControlButton, _
            Parameter:=strTag)
        With ctlBtnAddin
            .Caption = strCaption
            .Tag = strTag
            If intStyle <> msoButtonCaption Then
                .FaceId = intFaceID
            End If
            .Style = intStyle
            .ToolTipText = strTip
            .BeginGroup = blnBeginGroup
            .OnAction = "<!" & strProgID & ">"
        End With
    End If
    
    ' Return reference to new commandbar button.
    Set CreateAddInPopupButton = ctlBtnAddin

End Function
