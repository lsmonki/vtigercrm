Attribute VB_Name = "modGeneral"
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

'Api for executing in shell
Private Declare Function ShellExecute Lib "shell32.dll" Alias "ShellExecuteA" (ByVal hwnd As Long, ByVal lpOperation As String, ByVal lpFile As String, ByVal lpParameters As String, ByVal lpDirectory As String, ByVal nShowCmd As Long) As Long

'Registry Path for vtigerCRM OfficeEdtion
Public Const REG_PATH As String = "Software\vtiger\vtigerCRM Office Plug-in\4.5"
Public Const REG_LANG_PATH As String = "Software\vtigerCRM Office Plug-in 4.5"

'Registry Key Names
Public Const R_KEY_APP_PATH As String = "applicationpath"

Public Const R_KEY_PROXY_USERNAME As String = "proxyusername"
Public Const R_KEY_PROXY_PASSWORD As String = "proxypassword"
Public Const R_KEY_PROXY_PORT As String = "proxyport"
Public Const R_KEY_PROXY_ENABLED As String = "proxyenable"
Public Const R_KEY_PROXY_ADDRS As String = "proxyaddress"

Public Const R_KEY_VTIGER_USERNAME As String = "vtigerusername"
Public Const R_KEY_VTIGER_PASSWORD As String = "vtigerpassword"
Public Const R_KEY_VTIGER_URL As String = "vtigerurl"

Public Const R_KEY_INST_LANG As String = "Installer Language"

'Global variable to store Registry Values
Public gapppath As String

Public gproxyusername As String
Public gproxypassword As String
Public gproxyport As String
Public gproxyenabled As String
Public gproxyaddress As String

Public gvtigerusername As String
Public gvtigerpassword As String
Public gvtigerurl As String

Public gLangInstalled As String

Public oWordAppObj As Object
Public a() As String
Public a_acnt() As String
Public a_lead() As String
Public a_tickets() As String
Public a_user() As String
Public module_index As Integer
Public contact_index As Integer
Public lead_index As Integer
Public ticket_index As Integer
Public user_index As Integer
Public account_index As Integer

''Public Sub ExecuteInShell(ByRef url As String)
''On Error Resume Next
''ShellExecute App.hInstance, vbNullString, url, vbNullString, vbNullString, SW_SHOWNORMAL
''End Sub

Sub Main()

'On Error GoTo ERROR_EXIT_ROUTINE
'
'If Not bCreateRegPath() Then GoTo ERROR_EXIT_ROUTINE
'
'If Not bGetRegInitValues() Then GoTo ERROR_EXIT_ROUTINE
'
'If Not bGetRegKeyValues() Then GoTo ERROR_EXIT_ROUTINE
'
'If Not bLoadLang() Then GoTo ERROR_EXIT_ROUTINE
'
'ERROR_EXIT_ROUTINE:
'
'EXIT_ROUTINE:

End Sub


Public Function bCreateRegPath() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE

SaveKey HKEY_CURRENT_USER, REG_PATH
bCreateRegPath = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bCreateRegPath = False

EXIT_ROUTINE:

End Function

Public Function bGetRegInitValues() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE

'get application path
gapppath = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_APP_PATH)

If Trim(gapppath) = "" Then
    SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_APP_PATH, App.Path
End If

gapppath = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_APP_PATH)

'get proxy enabled
gproxyenabled = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_ENABLED)

If Trim(gproxyenabled) = "" Then
    SaveString HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_ENABLED, "0"
End If

bGetRegInitValues = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bGetRegInitValues = False

EXIT_ROUTINE:
End Function
Public Function bGetRegKeyValues() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE

'get key values from registry
gvtigerusername = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_VTIGER_USERNAME)

If Trim(gvtigerusername) <> "" Then
    gvtigerpassword = Decrypt(GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_VTIGER_PASSWORD), "vtigerCRM")
End If

gvtigerurl = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_VTIGER_URL)

gproxyenabled = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_ENABLED)

If gproxyenabled = "1" Then
    gproxyusername = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_USERNAME)
    gproxypassword = Decrypt(GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_PASSWORD), "vtigerCRM")
    gproxyport = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_PORT)
    gproxyaddress = GetString(HKEY_CURRENT_USER, REG_PATH, R_KEY_PROXY_ADDRS)
End If

gLangInstalled = GetString(HKEY_LOCAL_MACHINE, REG_LANG_PATH, R_KEY_INST_LANG)

bGetRegKeyValues = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bGetRegKeyValues = False
MsgBox Err.Description
EXIT_ROUTINE:
End Function


Public Function bLogInvtigerCRM() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE

Dim sErrStr As String

sErrStr = gMsg001
 
Dim oSoap As New PocketSOAP.CoEnvelope12
Dim oSoapHttp As New PocketSOAP.HTTPTransport
Dim svtigerURL As String
Dim sValue As String
Dim sArrayvalue(20) As String

oSoap.MethodName = "create_session"

If gvtigerusername <> "" And gvtigerpassword <> "" And gvtigerurl <> "" Then

    oSoap.Parameters.Create "user_name", gvtigerusername
    oSoap.Parameters.Create "password", gvtigerpassword
    svtigerURL = gvtigerurl & "/vtigerservice.php?service=wordplugin"
    
    If gproxyenabled = "1" Then
        oSoapHttp.SetProxy gproxyaddress, CInt(gproxyport)
        oSoapHttp.ProxyAuthentication gproxyusername, gproxypassword
    End If
    
    sErrStr = gMsg002
    oSoapHttp.Send svtigerURL, oSoap.Serialize
    oSoap.Parse oSoapHttp
    
    sErrStr = gMsg005
    If oSoap.Parameters.ItemByName("return").Value = "TempSessionID" Then
        Call sErrorDlg(gMsg003)
    Else
        sErrStr = gMsg018
        GoTo ERROR_EXIT_ROUTINE
    End If
    
Else
    sErrStr = ""
    frmConf.Show vbModal
    GoTo ERROR_EXIT_ROUTINE
End If

bLogInvtigerCRM = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    bLogInvtigerCRM = False
    If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
    'MsgBox Err.Description
EXIT_ROUTINE:
    Set oSoap = Nothing
    Set oSoapHttp = Nothing
End Function


Public Function bLogOutvtigerCRM() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oSoap As New PocketSOAP.CoEnvelope12
Dim oSoapHttp As New PocketSOAP.HTTPTransport
Dim svtigerURL As String

oSoap.MethodName = "end_session"

If gvtigerusername <> "" And gvtigerpassword <> "" And gvtigerurl <> "" Then

    oSoap.Parameters.Create "user_name", gvtigerusername
    svtigerURL = gvtigerurl & "/vtigerservice.php?service=wordplugin"
    
    If gproxyenabled = "1" Then
        oSoapHttp.SetProxy gproxyaddress, CInt(gproxyport)
        oSoapHttp.ProxyAuthentication gproxyusername, gproxypassword
    End If
    
     sErrStr = gMsg002
    oSoapHttp.Send svtigerURL, oSoap.Serialize
    oSoap.Parse oSoapHttp
    
    sErrStr = gMsg005
    If oSoap.Parameters.ItemByName("return").Value = "Success" Then
         Call sErrorDlg(gMsg004)
    Else
         Call sErrorDlg(gMsg004)
    End If
    
Else
    sErrStr = ""
    frmConf.Show vbModal
    GoTo ERROR_EXIT_ROUTINE
End If

bLogOutvtigerCRM = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    bLogOutvtigerCRM = False
   If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
    'MsgBox Err.Description
EXIT_ROUTINE:
    Set oSoap = Nothing
    Set oSoapHttp = Nothing
End Function

Public Function sLoadField(ByVal lstFields As ListBox)
Dim i As Integer
lstFields.AddItem "Contact Fields", 0
lstFields.AddItem "Account Fields", 1
lstFields.AddItem "Lead Fields", 2
lstFields.AddItem "Ticket Fields", 3
lstFields.AddItem "User Fields", 4
Set lstFields = Nothing
End Function

Public Function IntializePages()
''frmvtigerMerge.Label1 = "Magic Words"
''frmvtigerMerge.cmdClose.Caption = "Closee"
End Function


Public Function bGetFieldValues() As Boolean

module_index = 0
contact_index = 50
user_index = 51
lead_index = 52
account_index = 53
ticket_index = 54

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oSoap As New PocketSOAP.CoEnvelope12
Dim oSoapHttp As New PocketSOAP.HTTPTransport
Dim svtigerURL As String
Dim sValue As String
Dim sArrayvalue(20) As String
Dim oSoapNode
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode

oSoap.MethodName = "get_contacts_columns"

If gvtigerusername <> "" And gvtigerpassword <> "" And gvtigerurl <> "" Then

    oSoap.Parameters.Create "user_name", gvtigerusername
    oSoap.Parameters.Create "password", gvtigerpassword
    svtigerURL = gvtigerurl & "/vtigerservice.php?service=wordplugin"

    If gproxyenabled = "1" Then
        oSoapHttp.SetProxy gproxyaddress, CInt(gproxyport)
        oSoapHttp.ProxyAuthentication gproxyusername, gproxypassword
    End If

    sErrStr = gMsg005
    oSoapHttp.Send svtigerURL, oSoap.Serialize
    oSoap.Parse oSoapHttp

    If oSoap.Serialize <> "" Then
        If oXMLDoc.loadXML(oSoap.Serialize) Then
            sErrStr = gMsg005
    
            Set oXMLElmt_Root = oXMLDoc.documentElement
            Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
            Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_contacts_columnsResponse/return")
            If oXMLReturnElmt.hasChildNodes <> False Then
                Call sParseXML(oSoap.Serialize)
                frmvtigerMerge.lstColumns.AddItem "Contact Fields", module_index
                contact_index = module_index
                module_index = module_index + 1
            End If
         Else
            GoTo ERROR_EXIT_ROUTINE
         End If
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
    
Else
    sErrStr = ""
    frmConf.Show vbModal
    GoTo ERROR_EXIT_ROUTINE
End If

bGetFieldValues = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    bGetFieldValues = False
    If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
EXIT_ROUTINE:
    Set oSoap = Nothing
    Set oSoapHttp = Nothing
End Function

Public Function sParseXML(sXMLString)

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode
Dim oXMLFieldNode As MSXML.IXMLDOMNode
Dim i As Integer


If oXMLDoc.loadXML(sXMLString) Then
    sErrStr = gMsg005
    
    Set oXMLElmt_Root = oXMLDoc.documentElement
    Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
    Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_contacts_columnsResponse/return")

    ReDim a(oXMLReturnElmt.childNodes.Length - 1) As String
    
    For i = 0 To oXMLReturnElmt.childNodes.Length - 1
        Set oXMLFieldNode = oXMLReturnElmt.childNodes(i)
        a(i) = oXMLFieldNode.childNodes(0).Text
    Next i
Else
    sErrStr = gMsg005
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
   If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
EXIT_ROUTINE:
Set oXMLDoc = Nothing
Set oXMLElmt_Root = Nothing
Set oXMLBody = Nothing
Set oXMLReturnElmt = Nothing
Set oXMLFieldNode = Nothing
End Function


Public Function bGetFieldValues_Acnt() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oSoap As New PocketSOAP.CoEnvelope12
Dim oSoapHttp As New PocketSOAP.HTTPTransport
Dim svtigerURL As String
Dim sValue As String
Dim sArrayvalue(20) As String
Dim oSoapNode
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode


oSoap.MethodName = "get_accounts_columns"

If gvtigerusername <> "" And gvtigerpassword <> "" And gvtigerurl <> "" Then

    oSoap.Parameters.Create "user_name", gvtigerusername
    oSoap.Parameters.Create "password", gvtigerpassword
    svtigerURL = gvtigerurl & "/vtigerservice.php?service=wordplugin"
    
    If gproxyenabled = "1" Then
        oSoapHttp.SetProxy gproxyaddress, CInt(gproxyport)
        oSoapHttp.ProxyAuthentication gproxyusername, gproxypassword
    End If
    
    sErrStr = gMsg005
    oSoapHttp.Send svtigerURL, oSoap.Serialize
    oSoap.Parse oSoapHttp
    If oSoap.Serialize <> "" Then
        If oXMLDoc.loadXML(oSoap.Serialize) Then
            sErrStr = gMsg005
    
            Set oXMLElmt_Root = oXMLDoc.documentElement
            Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
            Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_accounts_columnsResponse/return")
            If oXMLReturnElmt.hasChildNodes <> False Then

                Call sParseXML_Acnt(oSoap.Serialize)
                frmvtigerMerge.lstColumns.AddItem "Account Fields", module_index
                account_index = module_index
                module_index = module_index + 1
            End If
         Else
            GoTo ERROR_EXIT_ROUTINE
         End If
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
    
Else
    sErrStr = ""
    GoTo ERROR_EXIT_ROUTINE
End If

bGetFieldValues_Acnt = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    bGetFieldValues_Acnt = False
    If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
EXIT_ROUTINE:
    Set oSoap = Nothing
    Set oSoapHttp = Nothing
End Function

Public Function sParseXML_Acnt(sXMLString)

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode
Dim oXMLFieldNode As MSXML.IXMLDOMNode
Dim i As Integer


If oXMLDoc.loadXML(sXMLString) Then
    sErrStr = gMsg005
    
    Set oXMLElmt_Root = oXMLDoc.documentElement
    Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
    Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_accounts_columnsResponse/return")
    
    ReDim a_acnt(oXMLReturnElmt.childNodes.Length - 1) As String
    
    For i = 0 To oXMLReturnElmt.childNodes.Length - 1
        Set oXMLFieldNode = oXMLReturnElmt.childNodes(i)
        a_acnt(i) = oXMLFieldNode.childNodes(0).Text
    Next i
Else
    sErrStr = gMsg005
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
   If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
EXIT_ROUTINE:
Set oXMLDoc = Nothing
Set oXMLElmt_Root = Nothing
Set oXMLBody = Nothing
Set oXMLReturnElmt = Nothing
Set oXMLFieldNode = Nothing
End Function

Public Function bGetFieldValues_User() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oSoap As New PocketSOAP.CoEnvelope12
Dim oSoapHttp As New PocketSOAP.HTTPTransport
Dim svtigerURL As String
Dim sValue As String
Dim sArrayvalue(20) As String
Dim oSoapNode
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode

oSoap.MethodName = "get_user_columns"

If gvtigerusername <> "" And gvtigerpassword <> "" And gvtigerurl <> "" Then

    oSoap.Parameters.Create "user_name", gvtigerusername
    oSoap.Parameters.Create "password", gvtigerpassword
    svtigerURL = gvtigerurl & "/vtigerservice.php?service=wordplugin"
    
    If gproxyenabled = "1" Then
        oSoapHttp.SetProxy gproxyaddress, CInt(gproxyport)
        oSoapHttp.ProxyAuthentication gproxyusername, gproxypassword
    End If
    
    sErrStr = gMsg005
    oSoapHttp.Send svtigerURL, oSoap.Serialize
    oSoap.Parse oSoapHttp

    If oSoap.Serialize <> "" Then
        If oXMLDoc.loadXML(oSoap.Serialize) Then
            sErrStr = gMsg005
    
            Set oXMLElmt_Root = oXMLDoc.documentElement
            Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
            Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_user_columnsResponse/return")
            If oXMLReturnElmt.hasChildNodes <> False Then

                Call sParseXML_User(oSoap.Serialize)
                frmvtigerMerge.lstColumns.AddItem "User Fields", module_index
                user_index = module_index
                module_index = module_index + 1
            End If
         Else
            GoTo ERROR_EXIT_ROUTINE
         End If
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
    
Else
    sErrStr = ""
    GoTo ERROR_EXIT_ROUTINE
End If

bGetFieldValues_User = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    bGetFieldValues_User = False
    If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
EXIT_ROUTINE:
    Set oSoap = Nothing
    Set oSoapHttp = Nothing
End Function

Public Function bGetFieldValues_Lead() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oSoap As New PocketSOAP.CoEnvelope12
Dim oSoapHttp As New PocketSOAP.HTTPTransport
Dim svtigerURL As String
Dim sValue As String
Dim sArrayvalue(20) As String
Dim oSoapNode
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode

oSoap.MethodName = "get_leads_columns"

If gvtigerusername <> "" And gvtigerpassword <> "" And gvtigerurl <> "" Then

    oSoap.Parameters.Create "user_name", gvtigerusername
    oSoap.Parameters.Create "password", gvtigerpassword
    svtigerURL = gvtigerurl & "/vtigerservice.php?service=wordplugin"
    
    If gproxyenabled = "1" Then
        oSoapHttp.SetProxy gproxyaddress, CInt(gproxyport)
        oSoapHttp.ProxyAuthentication gproxyusername, gproxypassword
    End If
    
    sErrStr = gMsg005
    oSoapHttp.Send svtigerURL, oSoap.Serialize
    oSoap.Parse oSoapHttp
    
    If oSoap.Serialize <> "" Then
        If oXMLDoc.loadXML(oSoap.Serialize) Then
            sErrStr = gMsg005
    
            Set oXMLElmt_Root = oXMLDoc.documentElement
            Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
            Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_leads_columnsResponse/return")
            If oXMLReturnElmt.hasChildNodes <> False Then

                Call sParseXML_Lead(oSoap.Serialize)
                frmvtigerMerge.lstColumns.AddItem "Lead Fields", module_index
                lead_index = module_index
                module_index = module_index + 1
            End If
         Else
            GoTo ERROR_EXIT_ROUTINE
         End If
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
    
Else
    sErrStr = ""
    GoTo ERROR_EXIT_ROUTINE
End If

bGetFieldValues_Lead = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    bGetFieldValues_Lead = False
    If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
EXIT_ROUTINE:
    Set oSoap = Nothing
    Set oSoapHttp = Nothing
End Function

Public Function bGetFieldValues_Tickets() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oSoap As New PocketSOAP.CoEnvelope12
Dim oSoapHttp As New PocketSOAP.HTTPTransport
Dim svtigerURL As String
Dim sValue As String
Dim sArrayvalue(20) As String
Dim oSoapNode
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode

oSoap.MethodName = "get_tickets_columns"

If gvtigerusername <> "" And gvtigerpassword <> "" And gvtigerurl <> "" Then

    oSoap.Parameters.Create "user_name", gvtigerusername
    oSoap.Parameters.Create "password", gvtigerpassword
    svtigerURL = gvtigerurl & "/vtigerservice.php?service=wordplugin"
    
    If gproxyenabled = "1" Then
        oSoapHttp.SetProxy gproxyaddress, CInt(gproxyport)
        oSoapHttp.ProxyAuthentication gproxyusername, gproxypassword
    End If
    
    sErrStr = gMsg005
    oSoapHttp.Send svtigerURL, oSoap.Serialize
    oSoap.Parse oSoapHttp
    
    If oSoap.Serialize <> "" Then
        If oXMLDoc.loadXML(oSoap.Serialize) Then
            sErrStr = gMsg005
    
            Set oXMLElmt_Root = oXMLDoc.documentElement
            Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
            Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_tickets_columnsResponse/return")
            If oXMLReturnElmt.hasChildNodes <> False Then

                Call sParseXML_Tickets(oSoap.Serialize)
                frmvtigerMerge.lstColumns.AddItem "Ticket Fields", module_index
                ticket_index = module_index
                module_index = module_index + 1
            End If
         Else
            GoTo ERROR_EXIT_ROUTINE
         End If
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
    
Else
    sErrStr = ""
    GoTo ERROR_EXIT_ROUTINE
End If

bGetFieldValues_Tickets = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    bGetFieldValues_Tickets = False
    If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
EXIT_ROUTINE:
    Set oSoap = Nothing
    Set oSoapHttp = Nothing
End Function
Public Function sParseXML_User(sXMLString)

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode
Dim oXMLFieldNode As MSXML.IXMLDOMNode
Dim i As Integer


If oXMLDoc.loadXML(sXMLString) Then
    sErrStr = gMsg005
    
    Set oXMLElmt_Root = oXMLDoc.documentElement
    Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
    Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_user_columnsResponse/return")
    
    ReDim a_user(oXMLReturnElmt.childNodes.Length - 1) As String
    
    For i = 0 To oXMLReturnElmt.childNodes.Length - 1
        Set oXMLFieldNode = oXMLReturnElmt.childNodes(i)
        a_user(i) = oXMLFieldNode.childNodes(0).Text
    Next i
Else
    sErrStr = gMsg005
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
   If sErrStr <> "" Then
        Call sErrorDlg(sErrStr & Err.Description)
    End If
EXIT_ROUTINE:
Set oXMLDoc = Nothing
Set oXMLElmt_Root = Nothing
Set oXMLBody = Nothing
Set oXMLReturnElmt = Nothing
Set oXMLFieldNode = Nothing
End Function

Public Function sParseXML_Tickets(sXMLString)

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode
Dim oXMLFieldNode As MSXML.IXMLDOMNode
Dim i As Integer


If oXMLDoc.loadXML(sXMLString) Then
    sErrStr = gMsg005
    
    Set oXMLElmt_Root = oXMLDoc.documentElement
    Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
    Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_tickets_columnsResponse/return")
    
    ReDim a_tickets(oXMLReturnElmt.childNodes.Length - 1) As String
    
    For i = 0 To oXMLReturnElmt.childNodes.Length - 1
        Set oXMLFieldNode = oXMLReturnElmt.childNodes(i)
        a_tickets(i) = oXMLFieldNode.childNodes(0).Text
    Next i
Else
    sErrStr = gMsg005
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
   If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
EXIT_ROUTINE:
Set oXMLDoc = Nothing
Set oXMLElmt_Root = Nothing
Set oXMLBody = Nothing
Set oXMLReturnElmt = Nothing
Set oXMLFieldNode = Nothing
End Function
Public Function sParseXML_Lead(sXMLString)

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrStr As String

sErrStr = gMsg001

Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmt_Root As MSXML.IXMLDOMElement
Dim oXMLBody As MSXML.IXMLDOMElement
Dim oXMLReturnElmt As MSXML.IXMLDOMNode
Dim oXMLFieldNode As MSXML.IXMLDOMNode
Dim i As Integer


If oXMLDoc.loadXML(sXMLString) Then
    sErrStr = gMsg005
    
    Set oXMLElmt_Root = oXMLDoc.documentElement
    Set oXMLBody = oXMLElmt_Root.childNodes(0)
    
    Set oXMLReturnElmt = oXMLBody.selectSingleNode("//E:get_leads_columnsResponse/return")
    
    ReDim a_lead(oXMLReturnElmt.childNodes.Length - 1) As String
    
    For i = 0 To oXMLReturnElmt.childNodes.Length - 1
        Set oXMLFieldNode = oXMLReturnElmt.childNodes(i)
        a_lead(i) = oXMLFieldNode.childNodes(0).Text
    Next i
Else
    sErrStr = gMsg005
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
   If sErrStr <> "" Then
        Call sErrorDlg(sErrStr)
    End If
EXIT_ROUTINE:
Set oXMLDoc = Nothing
Set oXMLElmt_Root = Nothing
Set oXMLBody = Nothing
Set oXMLReturnElmt = Nothing
Set oXMLFieldNode = Nothing
End Function

Public Function sErrorDlg(ByVal sErrMsg As String, Optional ByVal nType As Integer = 0)
    If nType = 0 Then
        MsgBox sErrMsg, vbInformation, gPrdName
    ElseIf nType = 1 Then
        MsgBox sErrMsg, vbCritical, gPrdName
    End If
End Function

Public Function Encrypt(ByVal Plain As String, _
  sEncKey As String) As String
    '*********************************************************
    'Coded WhiteKnight 6-1-00
    'This Encrypts A string by converting it to its ASCII number
    'but the difference is it uses a Key String it converts the
    'keystring to ASCII and adds it to the first ASCII Value the
    'key is needed to decrypt the text.  I do plan on changing
    'this some what but For Now its ok.  I've only seen it
    'cause an error when the wrong Key was entered while
     'decrypting.
    
    'Note That If you use the same letter more then 3 times in a
    'row then each letter after it if still the same is ignored
    '(ie aaa = aaaaaaaaa but aaa <> aaaza)
    'If anyone Can figure out a way to fix this please e-mail me
  '*********************************************************
    Dim encrypted2 As String
    Dim LenLetter As Integer
    Dim Letter As String
    Dim KeyNum As String
    Dim encstr As String
    Dim temp As String
    Dim temp2 As String
    Dim itempstr As String
    Dim itempnum As Integer
    Dim Math As Long
    Dim i As Integer
    
    On Error GoTo oops
    
    If sEncKey = "" Then sEncKey = "WhiteKnight"
    'Sets the Encryption Key if one is not set
    ReDim encKEY(1 To Len(sEncKey))
    
    'starts the values for the Encryption Key
        
    For i = 1 To Len(sEncKey$)
     KeyNum = Mid$(sEncKey$, i, 1) 'gets the letter at index i
     encKEY(i) = Asc(KeyNum) 'sets the the Array value
                             'to ASC number for the letter

           'This is the first letter so just hold the value
        If i = 1 Then Math = encKEY(i): GoTo nextone

        'compares the value to the previous value and then
        'either adds/subtracts the value to the Math total
       If i >= 2 And Math - encKEY(i) >= 0 And encKEY(i) <= _
           encKEY(i - 1) Then Math = Math - encKEY(i)

        If i >= 2 And Math - encKEY(i) >= 0 And encKEY(i) <= _
           encKEY(i - 1) Then Math = Math - encKEY(i)
        If i >= 2 And encKEY(i) >= Math And encKEY(i) >= _
           encKEY(i - 1) Then Math = Math + encKEY(i)
        If i >= 2 And encKEY(i) < Math And encKEY(i) _
          >= encKEY(i - 1) Then Math = Math + encKEY(i)
nextone:
    Next i
    
    
    For i = 1 To Len(Plain) 'Now for the String to be encrypted
        Letter = Mid$(Plain, i, 1) 'sets Letter to
                                   'the letter at index i
        LenLetter = Asc(Letter) + Math 'Now it adds the Asc
                                       'value of Letter to Math

'checks and corrects the format then adds a space to separate them frm each other
        If LenLetter >= 100 Then encstr = _
             encstr & Asc(Letter) + Math & " "

         'checks and corrects the format then adds a space
        'to separate them frm each other
        If LenLetter <= 99 Then encstr$ = encstr & "0" & _
          Asc(Letter) + Math & " "
    Next i


    'This is part of what i'm doing to convert the encrypted
    'numbers to Letters so it sort of encrypts the
    'encrypted message.
    temp$ = encstr 'hold the encrypted data
    temp$ = TrimSpaces(temp) 'get rid of the spaces
    itempnum% = Mid(temp, 1, 2) 'grab the first 2 numbers
    temp2$ = Chr(itempnum% + 100) 'Now add 100 so it
                                   'will be a valid char

    'If its a 2 digit number hold it and continue
    If Len(itempnum%) >= 2 Then itempstr$ = Str(itempnum%)
 
   'If the number is a single digit then add a '0' to the front
   'then hold it
    If Len(itempnum%) = 1 Then itempstr$ = "0" & _
        TrimSpaces(Str(itempnum%))
    
    encrypted2$ = temp2 'set the encrypted message
    
    For i = 3 To Len(temp) Step 2
        itempnum% = Mid(temp, i, 2) 'grab the next 2 numbers
  
      ' add 100 so it will be a valid char
        temp2$ = Chr(itempnum% + 100)

      'if its the last number we only want to hold it we
       'don't want to add a '0' even if its a single digit
        If i = Len(temp) Then itempstr$ = _
         Str(itempnum%): GoTo itsdone

'If its a 2 digit number hold it and continue
        If Len(itempnum%) = 2 Then itempstr$ = _
            Str(itempnum%)

        'If the number is a single digit then add a '0'
        'to the front then hold it
        If Len(TrimSpaces(Str(itempnum))) = 1 Then _
      itempstr$ = "0" & TrimSpaces(Str(itempnum%))

        'Now check to see if a - number was created
        'if so cause an error message
        If Left(TrimSpaces(Str(itempnum)), 1) = "-" Then _
          Err.Raise 20000, , "Unexpected Error"
           

itsdone:
           'Set The Encrypted message
        encrypted2$ = encrypted2 & temp2$
    Next i


    'Encrypt = encstr 'Returns the First Encrypted String
    Encrypt = encrypted2 'Returns the Second Encrypted String
    Exit Function 'We are outta Here
oops:
    Debug.Print "Error description", Err.Description
    Debug.Print "Error source:", Err.Source
    Debug.Print "Error Number:", Err.Number
End Function

Public Function Decrypt(ByVal Encrypted As String, _
    sEncKey As String) As String

    Dim NewEncrypted As String
    Dim Letter As String
    Dim KeyNum As String
    Dim EncNum As String
    Dim encbuffer As Long
    Dim strDecrypted As String
    Dim Kdecrypt As String
    Dim lastTemp As String
    Dim LenTemp As Integer
    Dim temp As String
    Dim temp2 As String
    Dim itempstr As String
    Dim itempnum As Integer
    Dim Math As Long
    Dim i As Integer
    
    On Error GoTo oops

    If sEncKey = "" Then sEncKey = "WhiteKnight"

    ReDim encKEY(1 To Len(sEncKey))
    
    'Convert The Key For Decryption
    For i = 1 To Len(sEncKey$)
        KeyNum = Mid$(sEncKey$, i, 1) 'Get Letter i% in the Key
        encKEY(i) = Asc(KeyNum) 'Convert Letter i to Asc value
 
'if it the first letter just hold it
       If i = 1 Then
          Math = encKEY(i): GoTo nextone
       End If
       
       If i >= 2 And Math - encKEY(i) >= 0 And encKEY(i) _
               <= encKEY(i - 1) Then Math = Math - encKEY(i)
               
               
               'compares the value to the previous value and
               'then either adds/subtracts the value to the
               'Math total
        If i >= 2 And Math - encKEY(i) >= 0 And encKEY(i) _
              <= encKEY(i - 1) Then Math = Math - encKEY(i)
        If i >= 2 And encKEY(i) >= Math And encKEY(i) _
              >= encKEY(i - 1) Then Math = Math + encKEY(i)
        If i >= 2 And encKEY(i) < Math And encKEY(i) _
              >= encKEY(i - 1) Then Math = Math + encKEY(i)
nextone:
    Next i
    
    
    'This is part of what i'm doing to convert the encrypted
    'numbers to  Letters so it sort of encrypts the encrypted
    'message.
    temp$ = Encrypted 'hold the encrypted data


    For i = 1 To Len(temp)
        itempstr = TrimSpaces(Str(Asc(Mid(temp, i, 1)) - _
           100)) 'grab the next 2 numbers
           'If its a 2 digit number hold it and continue
        If Len(itempstr$) = 2 Then itempstr$ = itempstr$
          If i = Len(temp) - 2 Then LenTemp% = _
               Len(Mid(temp2, Len(temp2) - 3))
          If i = Len(temp) Then itempstr$ = _
              TrimSpaces(itempstr$): GoTo itsdone
          'If the number is a single digit then add a '0' to the
          'front then hold it
        If Len(TrimSpaces(itempstr$)) = 1 Then _
             itempstr$ = "0" & TrimSpaces(itempstr$)
        'Now check to see if a - number was created if so
        'cause an error message
        If Left(TrimSpaces(itempstr$), 1) = "-" Then _
             Err.Raise 20000, , "Unexpected Error"
           

itsdone:
        temp2$ = temp2$ & itempstr 'hold the first decryption
    Next i
    
    
    Encrypted = TrimSpaces(temp2$) 'set the encrypted data


    For i = 1 To Len(Encrypted) Step 3
        'Format the encrypted string for the second decryption
        NewEncrypted = NewEncrypted & _
            Mid(Encrypted, CLng(i), 3) & " "
    Next i

' Hold the last set of numbers to check it its the correct format
    lastTemp$ = TrimSpaces(Mid(NewEncrypted, _
         Len(NewEncrypted$) - 3))
         
         If Len(lastTemp$) = 2 Then
' If it = 2 then its not the Correct format and we need to fix it
        lastTemp$ = Mid(NewEncrypted, _
           Len(NewEncrypted$) - 1) 'Holds Last Number so a '0'
                                    'Can be added between them
'set it to the new format
        Encrypted = Mid(NewEncrypted, 1, _
           Len(NewEncrypted) - 2) & "0" & lastTemp$
Else
        Encrypted$ = NewEncrypted$ 'set the new format

    End If
    'The Actual Decryption
    For i = 1 To Len(Encrypted)
        Letter = Mid$(Encrypted, i, 1) 'Hold Letter at index i
        EncNum = EncNum & Letter 'Hold the letters
        If Letter = " " Then 'we have a letter to decrypt
            encbuffer = CLng(Mid(EncNum, 1, _
              Len(EncNum) - 1)) 'Convert it to long and
                                 'get the number minus the " "
            strDecrypted$ = strDecrypted & Chr(encbuffer - _
               Math) 'Store the decrypted string
            EncNum = "" 'clear if it is a space so we can get
                        'the next set of numbers
        End If
    Next i

    Decrypt = strDecrypted

    Exit Function
oops:
    Debug.Print "Error description", Err.Description
    Debug.Print "Error source:", Err.Source
    Debug.Print "Error Number:", Err.Number
Err.Raise 20001, , "You have entered the wrong encryption string"

End Function

Private Function TrimSpaces(strstring As String) As String
    Dim lngpos As Long
    Do While InStr(1&, strstring$, " ")
        DoEvents
         lngpos& = InStr(1&, strstring$, " ")
         strstring$ = Left$(strstring$, (lngpos& - 1&)) & _
            Right$(strstring$, Len(strstring$) - _
               (lngpos& + Len(" ") - 1&))
    Loop
     TrimSpaces$ = strstring$
End Function

Public Function Sh_Execute(ByVal url As String)
Dim hHandle As Long
ShellExecute hHandle, vbNullString, url, vbNullString, vbNullString, 0
End Function

