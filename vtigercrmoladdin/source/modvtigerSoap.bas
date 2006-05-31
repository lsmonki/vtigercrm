Attribute VB_Name = "modvtigerSoap"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit
'-----------------------------------------------------------
'Logging into vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function sVtSoLogin(ByVal sUserId As String, ByVal sPassword) As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement

sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope

oPSoap.MethodName = "LoginToVtiger"

oPSoap.Parameters.Create "userid", sUserId
oPSoap.Parameters.Create "password", sPassword

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml"
oXMLHttp.send oPSoap.Serialize

'MsgBox oXMLHttp.responseText
sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:LoginToVtigerResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:LoginToVtigerResponse/return").nodeTypedValue <> "TRUE" Then
        sVtSoLogin = oXMLDocElmnt.selectSingleNode("//ns1:LoginToVtigerResponse/return").nodeTypedValue
    Else
        sVtSoLogin = ""
        GoTo EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        sVtSoLogin = ""
        GoTo EXIT_ROUTINE
   End If
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
sVtSoLogin = ""
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("sVtSoLogin - " & Err.Description)
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-------------------------------------------------------------------------
'Searching Contacts By EmailAddress in vtigerCRM Using SOAP Method
'-------------------------------------------------------------------------
Public Function sVtigerSoContactSearch(ByVal sVtigerLoginId As String, ByVal sEmailAddress As String) As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement

sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope

Dim sResult As String

oPSoap.MethodName = "SearchContactsByEmail"
oPSoap.Parameters.Create "username", sVtigerLoginId
oPSoap.Parameters.Create "emailaddress", sEmailAddress

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If (oXMLDocElmnt.selectSingleNode("//ns1:SearchContactsByEmailResponse").childNodes(0).childNodes.Length > 0) Then
     sErrMsg = gMsg004
    If Not oXMLDocElmnt.selectNodes("//ns1:SearchContactsByEmailResponse/return") Is Nothing Then
        sResult = sDeSerializeXML(oXMLDocElmnt.selectNodes("//ns1:SearchContactsByEmailResponse/return"))
    Else
        sResult = ""
    End If
Else
    sResult = ""
End If
sVtigerSoContactSearch = sResult
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("sVtigerSoContactSearch - " & Err.Description)
sVtigerSoContactSearch = ""
EXIT_ROUTINE:
Set oXMLHttp = Nothing
Set oPSoap = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
Public Function sDeSerializeXML(ByVal objXML_NodeList As MSXML.IXMLDOMNodeList) As String
On Error GoTo ERROR_EXIT_ROUTINE

Dim objXML_Elmnt As MSXML.IXMLDOMElement
Dim objXML_ChildElmnt As MSXML.IXMLDOMElement

Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmnt_Root As MSXML.IXMLDOMElement
Dim oXMLElmnt_First As MSXML.IXMLDOMElement
Dim oXMLNode As MSXML.IXMLDOMNode
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction

Dim i As Integer
Dim j As Integer

Set oXMLInst = oXMLDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
oXMLDoc.insertBefore oXMLInst, oXMLDoc.FirstChild

Set oXMLElmnt_Root = oXMLDoc.createElement("vtiger")
Set oXMLDoc.documentElement = oXMLElmnt_Root

If Not objXML_NodeList.Item(0) Is Nothing Then
    If objXML_NodeList.Item(0).childNodes.Length > 0 Then
        For i = 0 To objXML_NodeList.Item(0).childNodes.Length - 1
            Set objXML_Elmnt = objXML_NodeList.Item(0).childNodes.Item(i)
            If Not objXML_Elmnt Is Nothing Then
                Set oXMLElmnt_First = oXMLDoc.createElement("items")
                Set oXMLNode = oXMLElmnt_Root.appendChild(oXMLElmnt_First)
                For j = 0 To objXML_Elmnt.childNodes.Length - 1
                    Set objXML_ChildElmnt = objXML_Elmnt.childNodes.Item(j)
                    Call AddChild(oXMLDoc, oXMLElmnt_First, objXML_ChildElmnt.nodeName, objXML_ChildElmnt.nodeTypedValue)
                Next j
            End If
        Next i
    End If
End If

sDeSerializeXML = EncodeUTF8(oXMLDoc.xml)
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
LogTheMessage ("sDeSerializeXML - " & Err.Description)
EXIT_ROUTINE:
Set objXML_Elmnt = Nothing
Set objXML_ChildElmnt = Nothing
Set oXMLDoc = Nothing
Set oXMLElmnt_Root = Nothing
Set oXMLElmnt_First = Nothing
Set oXMLNode = Nothing
Set oXMLInst = Nothing
End Function
'--------------------------------------------------------------
'Adding the EmailMessage to vtigerCRM Contact Using SOAP Method
'--------------------------------------------------------------
Public Function sAddMessageToContact(ByVal sVtigerLoginId As String, _
                                     ByVal sContactId As String, _
                                     ByVal oXML_Elmnt As MSXML.IXMLDOMElement) As String
                                     
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope
sErrMsg = gMsg002
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String

Dim aEmailMsgDtls() As Object

Call mkAddEmailMsgStruct(oXML_Elmnt, aEmailMsgDtls)

oPSoap.MethodName = "AddMessageToContact"

oPSoap.Parameters.Create "username", sVtigerLoginId
oPSoap.Parameters.Create "contactid", sContactId
oPSoap.Parameters.Create "msgdtls", aEmailMsgDtls

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:AddMessageToContactResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:AddMessageToContactResponse/return").nodeTypedValue <> "" Then
        sAddMessageToContact = oXMLDocElmnt.selectSingleNode("//ns1:AddMessageToContactResponse/return").nodeTypedValue
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If


GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("sAddMessageToContact - " & Err.Description)
sAddMessageToContact = ""
EXIT_ROUTINE:
Set oXMLHttp = Nothing
Set oPSoap = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function

Private Function mkAddEmailMsgStruct(ByVal oXML_Elmnt As MSXML.IXMLDOMElement, _
                                     ByRef aEmailMsgArray() As Object)
                                     
On Error GoTo ERROR_EXIT_ROUTINE
    
    ReDim aEmailMsgArray(0) As Object
    Dim ct As New CoSoapNode
    Dim i As Integer
    
    For i = 0 To oXML_Elmnt.childNodes.Length - 1
        With oXML_Elmnt.childNodes.Item(i)
             ct.Nodes.Create .nodeName, .nodeTypedValue
        End With
    Next i
        
    Set aEmailMsgArray(0) = ct
    Set ct = Nothing
    GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
LogTheMessage ("mkAddEmailMsgStruct - " & Err.Description)
EXIT_ROUTINE:
End Function
'-----------------------------------------------------------------
'Adding EmailAttahment to vtigerCRM Added Emails Using SOAP Method
'-----------------------------------------------------------------
Public Function bAddEmailAttachment(ByVal sCrmId As String, _
                                     ByVal sFileName As String, _
                                     ByVal sFileData As String, _
                                     ByVal sFileSize As String, _
                                     ByVal sFileType As String) As Boolean
                                     
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope
sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String

oPSoap.MethodName = "AddEmailAttachment"
oPSoap.Parameters.Create "emailid", sCrmId
oPSoap.Parameters.Create "filedata", sFileData
oPSoap.Parameters.Create "filename", sFileName
oPSoap.Parameters.Create "filesize", sFileSize
oPSoap.Parameters.Create "filetype", ""
oPSoap.Parameters.Create "username", gsVtUserId

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:AddEmailAttachmentResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:AddEmailAttachmentResponse/return").nodeTypedValue <> "" Then
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

bAddEmailAttachment = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("bAddEmailAttachment - " & Err.Description)
bAddEmailAttachment = False
EXIT_ROUTINE:
Set oXMLHttp = Nothing
Set oPSoap = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'Retreving Contacts from vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function svTigerSoGetContacts(ByVal sVtigerLoginId As String) As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope
sErrMsg = gMsg001
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim sResult As String

oPSoap.MethodName = "GetContacts"
oPSoap.Parameters.Create "username", sVtigerLoginId

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If (oXMLDocElmnt.selectSingleNode("//ns1:GetContactsResponse").childNodes.Length > 0) Then
    sErrMsg = gMsg004
    If Not oXMLDocElmnt.selectNodes("//ns1:GetContactsResponse/return") Is Nothing Then
        sResult = sDeSerializeVtXML("CONTACTS", oXMLDocElmnt.selectNodes("//ns1:GetContactsResponse/return"))
    Else
        sResult = ""
    End If
Else
    sResult = ""
End If

svTigerSoGetContacts = sResult
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("svTigerSoGetContacts - " & Err.Description)
svTigerSoGetContacts = ""
EXIT_ROUTINE:
Set oXMLHttp = Nothing
Set oPSoap = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function

Public Function sDeSerializeVtXML(ByVal sModule As String, ByVal objXML_NodeList As MSXML.IXMLDOMNodeList) As String
On Error GoTo ERROR_EXIT_ROUTINE

Dim objXML_Elmnt As MSXML.IXMLDOMElement
Dim objXML_ChildElmnt As MSXML.IXMLDOMElement

Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmnt_Root As MSXML.IXMLDOMElement
Dim oXMLElmnt_First As MSXML.IXMLDOMElement
Dim oXMLNode As MSXML.IXMLDOMNode
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction
Dim sCrmId As String
Dim i As Integer
Dim j As Integer

Set oXMLInst = oXMLDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
oXMLDoc.insertBefore oXMLInst, oXMLDoc.FirstChild

Set oXMLElmnt_Root = oXMLDoc.createElement("vtigercrm")
Set oXMLDoc.documentElement = oXMLElmnt_Root

If Not objXML_NodeList.Item(0) Is Nothing Then
    
    If objXML_NodeList.Item(0).childNodes.Length > 0 Then
        
        For i = 0 To objXML_NodeList.Item(0).childNodes.Length - 1
            
            Set objXML_Elmnt = objXML_NodeList.Item(0).childNodes.Item(i)
            If Not objXML_Elmnt Is Nothing Then
                
                If sModule = "CONTACTS" Then
                    Set oXMLElmnt_First = oXMLDoc.createElement("contactitems")
                ElseIf sModule = "TASKS" Then
                    Set oXMLElmnt_First = oXMLDoc.createElement("taskitems")
                ElseIf sModule = "CALENDAR" Then
                    Set oXMLElmnt_First = oXMLDoc.createElement("calendaritems")
                End If
                
                Set oXMLNode = oXMLElmnt_Root.appendChild(oXMLElmnt_First)
                
                For j = 0 To objXML_Elmnt.childNodes.Length - 1
                                      
                    Set objXML_ChildElmnt = objXML_Elmnt.childNodes.Item(j)
                    If (objXML_ChildElmnt.nodeName <> "id") Then
                        Call AddChild(oXMLDoc, oXMLElmnt_First, objXML_ChildElmnt.nodeName, objXML_ChildElmnt.nodeTypedValue)
                    Else
                        sCrmId = objXML_ChildElmnt.nodeTypedValue
                    End If
                Next j
                
                If sCrmId <> "" Then
                    Call AddAttribute(oXMLElmnt_First, "crmid", sCrmId)
                End If
                
            End If
        Next i
    End If
End If
sDeSerializeVtXML = EncodeUTF8(oXMLDoc.xml)
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
LogTheMessage ("sDeSerializeVtXML - " & Err.Description)
sDeSerializeVtXML = ""
EXIT_ROUTINE:
Set objXML_Elmnt = Nothing
Set objXML_ChildElmnt = Nothing
Set oXMLDoc = Nothing
Set oXMLElmnt_Root = Nothing
Set oXMLElmnt_First = Nothing
Set oXMLNode = Nothing
Set oXMLInst = Nothing
End Function
Private Function mkCreateSoStruct(ByVal oXML_Elmnt As MSXML.IXMLDOMElement, ByRef aCntArray() As Object)
On Error GoTo ERROR_EXIT_ROUTINE
    Dim i As Integer
    Dim j As Integer
    Dim oXML_Child As MSXML.IXMLDOMElement
    
    ReDim aCntArray(1) As Object
    Dim ct As New CoSoapNode
    
    ct.Nodes.Create "entryid", oXML_Elmnt.getAttribute("entryid")
    For i = 0 To oXML_Elmnt.childNodes.Length - 1
        With oXML_Elmnt.childNodes.Item(i)
             ct.Nodes.Create .nodeName, DecodeUTF8(.nodeTypedValue)
        End With
    Next i
    Set aCntArray(0) = ct
    Set ct = Nothing
    GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
'sMsgDlg ("mkCreateSoStruct " & Err.Description)
LogTheMessage ("mkCreateSoStruct - " & Err.Description)
EXIT_ROUTINE:
Set oXML_Child = Nothing
End Function
'-----------------------------------------------------------
'Adding New Contacts in vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function sVtSoNewContacts(ByVal sVtLoginId As String, ByVal sVtContactXml As MSXML.IXMLDOMElement) As String

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope

sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String
Dim sSOAPEnvelope As String
Dim aCntsDtls() As Object


Call mkCreateSoStruct(sVtContactXml, aCntsDtls)

oPSoap.MethodName = "AddContacts"
oPSoap.Parameters.Create "username", sVtLoginId
oPSoap.Parameters.Create "cntdtls", aCntsDtls

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:AddContactsResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:AddContactsResponse/return").nodeTypedValue <> "" Then
        sVtSoNewContacts = oXMLDocElmnt.selectSingleNode("//ns1:AddContactsResponse/return").nodeTypedValue
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("sVtSoNewContacts - " & Err.Description)
sVtSoNewContacts = ""
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
Private Function mkUpdateSoStruct(ByVal sUpdateCrmId As String, ByVal oXML_Elmnt As MSXML.IXMLDOMElement, ByRef aCntDtls() As Object)
On Error GoTo ERROR_EXIT_ROUTINE
    Dim i As Integer
    Dim j As Integer
    Dim oXML_Child As MSXML.IXMLDOMElement
    ReDim aCntDtls(1) As Object
    Dim ct As New CoSoapNode
    
    ct.Nodes.Create "id", sUpdateCrmId
    For i = 0 To oXML_Elmnt.childNodes.Length - 1
        
        With oXML_Elmnt.childNodes.Item(i)
             ct.Nodes.Create .nodeName, DecodeUTF8(.nodeTypedValue)
        End With
        
    Next i
    Set aCntDtls(0) = ct
    Set ct = Nothing
    GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
'sMsgDlg ("mkUpdateSoStruct " & Err.Description)
LogTheMessage ("mkUpdateSoStruct - " & Err.Description)
EXIT_ROUTINE:
Set oXML_Child = Nothing
End Function
'-----------------------------------------------------------
'Updating the Contacts in vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function sVtSoUpdateContacts(ByVal sCrmId As String, ByVal objXMLElmnt As MSXML.IXMLDOMElement) As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope

sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String
Dim sSOAPEnvelope As String
Dim aCntsDtls() As Object

Call mkUpdateSoStruct(sCrmId, objXMLElmnt, aCntsDtls)

oPSoap.MethodName = "UpdateContacts"
oPSoap.Parameters.Create "username", gsVtUserId
oPSoap.Parameters.Create "cntdtls", aCntsDtls

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:UpdateContactsResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:UpdateContactsResponse/return").nodeTypedValue <> "" Then
        sVtSoUpdateContacts = oXMLDocElmnt.selectSingleNode("//ns1:UpdateContactsResponse/return").nodeTypedValue
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("sVtSoUpdateContacts - " & Err.Description)
sVtSoUpdateContacts = ""
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'Deleting the Contacts in vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function bVtSoDeleteContacts(ByVal sVtLoginId As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope

sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String

oPSoap.MethodName = "DeleteContacts"
oPSoap.Parameters.Create "username", sVtLoginId
oPSoap.Parameters.Create "crmid", sCrmId

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:DeleteContactsResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:DeleteContactsResponse/return").nodeTypedValue = "" Then
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

bVtSoDeleteContacts = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("bVtSoDeleteContacts - " & Err.Description)
bVtSoDeleteContacts = False
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'Retreving Tasks from vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function svTigerSoGetTasks(ByVal sVtigerLoginId As String) As String
On Error GoTo ERROR_EXIT_ROUTINE

Dim sErrMsg As String

sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope

sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String

oPSoap.MethodName = "GetTasks"
oPSoap.Parameters.Create "username", sVtigerLoginId

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If (oXMLDocElmnt.selectSingleNode("//ns1:GetTasksResponse").childNodes.Length > 0) Then
    sErrMsg = gMsg004
    If Not oXMLDocElmnt.selectNodes("//ns1:GetTasksResponse/return") Is Nothing Then
        sResult = sDeSerializeVtXML("TASKS", oXMLDocElmnt.selectNodes("//ns1:GetTasksResponse/return"))
    Else
        sResult = ""
    End If
Else
    sResult = ""
End If

svTigerSoGetTasks = sResult
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("svTigerSoGetTasks - " & Err.Description)
svTigerSoGetTasks = ""
EXIT_ROUTINE:
Set oXMLHttp = Nothing
Set oPSoap = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'Adding New Tasks in vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function sVtSoNewTasks(ByVal sVtLoginId As String, ByVal sVtTaskXml As MSXML.IXMLDOMElement) As String

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String

sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope

sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String
Dim sSOAPEnvelope As String
Dim aTaskArray() As Object


Call mkCreateSoStruct(sVtTaskXml, aTaskArray)

oPSoap.MethodName = "AddTasks"
oPSoap.Parameters.Create "username", sVtLoginId
oPSoap.Parameters.Create "taskdtls", aTaskArray

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:AddTasksResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:AddTasksResponse/return").nodeTypedValue <> "" Then
        sVtSoNewTasks = oXMLDocElmnt.selectSingleNode("//ns1:AddTasksResponse/return").nodeTypedValue
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("sVtSoNewTasks" & Err.Description)
'sMsgDlg ("sVtSoNewTasks " & Err.Description)
sVtSoNewTasks = ""
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'Updating Tasks in vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function sVtSoUpdateTasks(ByVal sCrmId As String, ByVal objXMLElmnt As MSXML.IXMLDOMElement) As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope
sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String
Dim sSOAPEnvelope As String
Dim aTaskDtls() As Object

Call mkUpdateSoStruct(sCrmId, objXMLElmnt, aTaskDtls)

oPSoap.MethodName = "UpdateTasks"
oPSoap.Parameters.Create "username", gsVtUserId
oPSoap.Parameters.Create "taskdtls", aTaskDtls

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:UpdateTasksResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:UpdateTasksResponse/return").nodeTypedValue <> "" Then
        sVtSoUpdateTasks = oXMLDocElmnt.selectSingleNode("//ns1:UpdateTasksResponse/return").nodeTypedValue
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("sVtSoUpdateTasks - " & Err.Description)
sVtSoUpdateTasks = ""
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'Deleting Tasks in vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function bVtSoDeleteTasks(ByVal sVtLoginId As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope
sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String

oPSoap.MethodName = "DeleteTasks"
oPSoap.Parameters.Create "username", sVtLoginId
oPSoap.Parameters.Create "crmid", sCrmId

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:DeleteTasksResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:DeleteTasksResponse/return").nodeTypedValue = "" Then
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

bVtSoDeleteTasks = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("bVtSoDeleteTasks - " & Err.Description)
bVtSoDeleteTasks = False
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'For Retreving the Calendar from vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function svTigerSoGetClndr(ByVal sVtigerLoginId As String) As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope
sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String

oPSoap.MethodName = "GetClndr"
oPSoap.Parameters.Create "username", sVtigerLoginId

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003

If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If (oXMLDocElmnt.selectSingleNode("//ns1:GetClndrResponse").childNodes.Length > 0) Then
    sErrMsg = gMsg004
    If Not oXMLDocElmnt.selectNodes("//ns1:GetClndrResponse/return") Is Nothing Then
        sResult = sDeSerializeVtXML("CALENDAR", oXMLDocElmnt.selectNodes("//ns1:GetClndrResponse/return"))
    Else
        sResult = ""
    End If
Else
    sResult = ""
End If
svTigerSoGetClndr = sResult
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
'sMsgDlg (Err.Description & "svTigerSoGetClndr" & Err.Source)
LogTheMessage ("svTigerSoGetClndr - " & Err.Description)
svTigerSoGetClndr = ""
EXIT_ROUTINE:
Set oXMLHttp = Nothing
Set oPSoap = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'For Adding the Calendar in vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function sVtSoNewClndr(ByVal sVtLoginId As String, ByVal sVtClndrXml As MSXML.IXMLDOMElement) As String

On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope
sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String
Dim sSOAPEnvelope As String
Dim aClndrDtls() As Object


Call mkCreateSoStruct(sVtClndrXml, aClndrDtls)

oPSoap.MethodName = "AddClndr"
oPSoap.Parameters.Create "username", sVtLoginId
oPSoap.Parameters.Create "clndrdtls", aClndrDtls

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:AddClndrResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:AddClndrResponse/return").nodeTypedValue <> "" Then
        sVtSoNewClndr = oXMLDocElmnt.selectSingleNode("//ns1:AddClndrResponse/return").nodeTypedValue
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("sVtSoNewClndr - " & Err.Description)
'sMsgDlg ("sVtSoNewClndr " & Err.Description)
sVtSoNewClndr = ""
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'For Updating the Calendar in vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function sVtSoUpdateClndr(ByVal sCrmId As String, ByVal objXMLElmnt As MSXML.IXMLDOMElement) As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope
sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String
Dim sSOAPEnvelope As String
Dim aClndrDtls() As Object

Call mkUpdateSoStruct(sCrmId, objXMLElmnt, aClndrDtls)

oPSoap.MethodName = "UpdateClndr"
oPSoap.Parameters.Create "username", gsVtUserId
oPSoap.Parameters.Create "clndrdtls", aClndrDtls

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:UpdateClndrResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:UpdateClndrResponse/return").nodeTypedValue <> "" Then
        sVtSoUpdateClndr = oXMLDocElmnt.selectSingleNode("//ns1:UpdateClndrResponse/return").nodeTypedValue
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("sVtSoUpdateClndr - " & Err.Description)
sVtSoUpdateClndr = ""
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
'-----------------------------------------------------------
'For Deleting the Calendar in vtigerCRM Using SOAP Method
'-----------------------------------------------------------
Public Function bVtSoDeleteClndr(ByVal sVtLoginId As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sErrMsg As String
sErrMsg = gMsg002
Dim oPSoap As New PocketSOAP.CoEnvelope
sErrMsg = gMsg001
Dim oXMLHttp As New MSXML.XMLHTTPRequest
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLDocElmnt As MSXML.IXMLDOMElement
Dim sResult As String

oPSoap.MethodName = "DeleteClndr"
oPSoap.Parameters.Create "username", sVtLoginId
oPSoap.Parameters.Create "crmid", sCrmId

oXMLHttp.Open "POST", gsVtUrl, False
oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
oXMLHttp.setRequestHeader "Content-Type", "text/xml; charset=UTF-8"
oXMLHttp.send oPSoap.Serialize

sErrMsg = gMsg003
If oXMLDoc.loadXML(oXMLHttp.responseText) = False Then GoTo ERROR_EXIT_ROUTINE
Set oXMLDocElmnt = oXMLDoc.documentElement

If Not oXMLDocElmnt.selectSingleNode("//ns1:DeleteClndrResponse/return") Is Nothing Then
    sErrMsg = gMsg004
    If oXMLDocElmnt.selectSingleNode("//ns1:DeleteClndrResponse/return").nodeTypedValue = "" Then
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
   If Not oXMLDocElmnt.selectSingleNode("//faultstring") Is Nothing Then
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultstring").nodeTypedValue)
        LogTheMessage (oXMLDocElmnt.selectSingleNode("//faultcode").nodeTypedValue)
        GoTo ERROR_EXIT_ROUTINE
   End If
End If

bVtSoDeleteClndr = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage ("bVtSoDeleteClndr - " & Err.Description)
'sMsgDlg ("bVtSoDeleteClndr" & Err.Description)
bVtSoDeleteClndr = False
EXIT_ROUTINE:
Set oPSoap = Nothing
Set oXMLHttp = Nothing
Set oXMLDoc = Nothing
Set oXMLDocElmnt = Nothing
End Function
