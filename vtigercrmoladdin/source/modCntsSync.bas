Attribute VB_Name = "modContactSync"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit
Public Function sCheckOlNewContacts(ByVal sCntsXMLStr As String) As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oXMLLocalDoc As New MSXML.DOMDocument
Dim oXMLLocalElmnt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalNode As MSXML.IXMLDOMNode
Dim oXMLLocalInst As MSXML.IXMLDOMProcessingInstruction

Dim oXMLAppendDoc As New MSXML.DOMDocument
Dim oXMLAppend_Root As MSXML.IXMLDOMElement

Dim oXMLOlDoc As New MSXML.DOMDocument
Dim oXMLOl_Root As MSXML.IXMLDOMElement
Dim oXMLOl_First As MSXML.IXMLDOMElement

Dim i As Integer

Set oXMLLocalInst = oXMLLocalDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
oXMLLocalDoc.insertBefore oXMLLocalInst, oXMLLocalDoc.FirstChild

Set oXMLLocalElmnt_Root = oXMLLocalDoc.createElement("outlook")
Set oXMLLocalDoc.documentElement = oXMLLocalElmnt_Root

If (oXMLOlDoc.loadXML(sCntsXMLStr) = True) Then
    Set oXMLOl_Root = oXMLOlDoc.documentElement
    If (oXMLOl_Root.childNodes.Length > 0) Then
    
       For i = 0 To oXMLOl_Root.childNodes.Length - 1
            
            Set oXMLOl_First = oXMLOl_Root.childNodes.Item(i)

            If Not oXMLOl_First Is Nothing Then
                If Not oXMLAppendDoc.loadXML(oXMLOl_First.xml) Then GoTo ERROR_EXIT_ROUTINE
                Set oXMLAppend_Root = oXMLAppendDoc.documentElement
                oXMLAppend_Root.setAttribute "syncflag", "N"
                Set oXMLLocalNode = oXMLLocalElmnt_Root.appendChild(oXMLAppend_Root)
            End If
       Next i
       ''sCheckOlNewContacts = oXMLLocalDoc.xml
       'oXMLLocalDoc.Save (gsVtUserFolder & LOCAL_OL_FILE)
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

sCheckOlNewContacts = oXMLLocalDoc.xml
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckOlNewContacts" & Err.Description
    LogTheMessage ("sCheckOlNewContacts - " & Err.Description)
    sCheckOlNewContacts = ""
EXIT_ROUTINE:
    Set oXMLOlDoc = Nothing
    Set oXMLOl_Root = Nothing
    Set oXMLOl_First = Nothing
    Set oXMLLocalDoc = Nothing
    Set oXMLLocalElmnt_Root = Nothing
    Set oXMLLocalNode = Nothing
    Set oXMLLocalInst = Nothing
    Set oXMLAppendDoc = Nothing
    Set oXMLAppend_Root = Nothing
End Function

Public Function sCheckOlUpdateContacts(ByVal sCntsXMLStr As String) As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oXMLOlDoc As New MSXML.DOMDocument
Dim oXMLOl_Root As MSXML.IXMLDOMElement
Dim oXMLOl_First As MSXML.IXMLDOMElement
Dim sOlEntryId As String
Dim bOlFlag As Boolean

Dim sXQString As String
Dim oXMLLocalOlDoc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement
Dim oXMLLocalOl_Second As MSXML.IXMLDOMElement
Dim oXMLLocalOl_Node As MSXML.IXMLDOMNode

Dim oXMLAppendDoc As New MSXML.DOMDocument
Dim oXMLAppend_Root As MSXML.IXMLDOMElement

Dim bLocalFlag As Boolean

Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction

Dim i As Integer

bOlFlag = oXMLOlDoc.loadXML(sCntsXMLStr)
bLocalFlag = oXMLLocalOlDoc.Load(gsVtUserFolder & LOCAL_OL_FILE)

If (bOlFlag = True And bLocalFlag = True) Then
    Set oXMLOl_Root = oXMLOlDoc.documentElement
    Set oXMLLocalOl_Root = oXMLLocalOlDoc.documentElement
    
    If (oXMLOl_Root.childNodes.Length > 0) Then
       
        frmSync.PrgBarSync.Min = 0
        frmSync.PrgBarSync.Max = oXMLOl_Root.childNodes.Length
        frmSync.PrgBarSync.Value = 0
        frmSync.lblSynStatus.Caption = "Reading Updations...."
        DoEvents
       For i = 0 To oXMLOl_Root.selectNodes("contactitems").Length - 1
            
            Set oXMLOl_First = oXMLOl_Root.selectNodes("contactitems").Item(i)
            sOlEntryId = oXMLOl_First.getAttribute("entryid")
            
            If sOlEntryId <> "" Then
                
                sXQString = "contactitems[@entryid='" & sOlEntryId & "']"
                Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQString)
                
                If Not oXMLLocalOl_First Is Nothing Then
                    If Not oXMLAppendDoc.loadXML(oXMLOl_First.xml) Then GoTo ERROR_EXIT_ROUTINE
                    
                    If (bOlUpdateCheck(oXMLOl_First, oXMLLocalOl_First) = True) Then
                        Set oXMLAppend_Root = oXMLAppendDoc.documentElement
                        Call AddAttribute(oXMLAppend_Root, "syncflag", "M")
                        Set oXMLLocalOl_Node = oXMLLocalOl_Root.replaceChild(oXMLAppend_Root, oXMLLocalOl_First)
                    Else
                        Call AddAttribute(oXMLLocalOl_First, "syncflag", "NM")
                    End If
                    
                Else
                
                    If Not oXMLAppendDoc.loadXML(oXMLOl_First.xml) Then GoTo ERROR_EXIT_ROUTINE
                    Set oXMLAppend_Root = oXMLAppendDoc.documentElement
                    Call AddAttribute(oXMLAppend_Root, "syncflag", "N")
                    Set oXMLLocalOl_Node = oXMLLocalOl_Root.appendChild(oXMLAppend_Root)
                    
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
       Next i
       'oXMLLocalOlDoc.Save (gsVtUserFolder & LOCAL_OL_FILE)
       ''sCheckOlUpdateContacts = oXMLLocalOlDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

sCheckOlUpdateContacts = oXMLLocalOlDoc.xml
GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckOlUpdateContacts" & Err.Description & Err.Source
    LogTheMessage ("sCheckOlUpdateContacts - " & Err.Description)
    sCheckOlUpdateContacts = ""
EXIT_ROUTINE:
    Set oXMLOlDoc = Nothing
    Set oXMLOl_Root = Nothing
    Set oXMLOl_First = Nothing
    Set oXMLLocalOlDoc = Nothing
    Set oXMLLocalOl_Root = Nothing
    Set oXMLLocalOl_First = Nothing
    Set oXMLLocalOl_Second = Nothing
    Set oXMLLocalOl_Node = Nothing
End Function

Public Function bOlUpdateCheck(ByVal oXMLOl_Elmnt As MSXML.IXMLDOMElement, ByVal oXMLLocal_Elmnt As MSXML.IXMLDOMElement) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sModifiedFlag As Boolean
Dim sErrMsg As String
    If ((Not oXMLOl_Elmnt Is Nothing) And (Not oXMLLocal_Elmnt Is Nothing)) Then
        
        sErrMsg = "title"
        If oXMLOl_Elmnt.selectSingleNode("title").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("title").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "firstname"
        If oXMLOl_Elmnt.selectSingleNode("firstname").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("firstname").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "middlename"
        If oXMLOl_Elmnt.selectSingleNode("middlename").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("middlename").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "lastname"
        If oXMLOl_Elmnt.selectSingleNode("lastname").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("lastname").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        
        sErrMsg = "birthdate"
        If oXMLOl_Elmnt.selectSingleNode("birthdate").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("birthdate").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "emailaddress"
        If oXMLOl_Elmnt.selectSingleNode("emailaddress").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("emailaddress").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "jobtitle"
        If oXMLOl_Elmnt.selectSingleNode("jobtitle").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("jobtitle").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "department"
        If oXMLOl_Elmnt.selectSingleNode("department").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("department").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        
        sErrMsg = "accountname"
        If oXMLOl_Elmnt.selectSingleNode("accountname").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("accountname").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE

        sErrMsg = "officephone"
        If oXMLOl_Elmnt.selectSingleNode("officephone").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("officephone").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "homephone"
        If oXMLOl_Elmnt.selectSingleNode("homephone").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("homephone").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "otherphone"
        If oXMLOl_Elmnt.selectSingleNode("otherphone").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("otherphone").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "fax"
        If oXMLOl_Elmnt.selectSingleNode("fax").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("fax").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "mobile"
        If oXMLOl_Elmnt.selectSingleNode("mobile").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("mobile").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        
        sErrMsg = "asstname"
        If oXMLOl_Elmnt.selectSingleNode("asstname").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("asstname").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "asstphone"
        If oXMLOl_Elmnt.selectSingleNode("asstphone").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("asstphone").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "reportsto"
        If oXMLOl_Elmnt.selectSingleNode("reportsto").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("reportsto").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "mailingstreet"
        If oXMLOl_Elmnt.selectSingleNode("mailingstreet").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("mailingstreet").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        If oXMLOl_Elmnt.selectSingleNode("mailingcity").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("mailingcity").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        If oXMLOl_Elmnt.selectSingleNode("mailingstate").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("mailingstate").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        If oXMLOl_Elmnt.selectSingleNode("mailingzip").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("mailingzip").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        If oXMLOl_Elmnt.selectSingleNode("mailingcountry").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("mailingcountry").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "otherstreet"
        If oXMLOl_Elmnt.selectSingleNode("otherstreet").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("otherstreet").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        If oXMLOl_Elmnt.selectSingleNode("othercity").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("othercity").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        If oXMLOl_Elmnt.selectSingleNode("otherstate").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("otherstate").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        If oXMLOl_Elmnt.selectSingleNode("otherzip").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("otherzip").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        If oXMLOl_Elmnt.selectSingleNode("othercountry").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("othercountry").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
        sErrMsg = "description"
        If oXMLOl_Elmnt.selectSingleNode("description").nodeTypedValue <> oXMLLocal_Elmnt.selectSingleNode("description").nodeTypedValue Then GoTo ERROR_EXIT_ROUTINE
    End If
    
bOlUpdateCheck = False
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("bOlUpdateCheck" & Err.Description)
    LogTheMessage (sErrMsg)
    bOlUpdateCheck = True
EXIT_ROUTINE:
Set oXMLOl_Elmnt = Nothing
Set oXMLLocal_Elmnt = Nothing
End Function

Public Function sCheckOlDeleteContacts(ByVal sUpdatedLocalOlStr As String, ByVal sCntsXMLStr As String) As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oXMLOlDoc As New MSXML.DOMDocument
Dim oXMLOl_Root As MSXML.IXMLDOMElement
Dim oXMLOl_First As MSXML.IXMLDOMElement
Dim bOlFlag As Boolean

Dim sXQString As String
Dim sLocalEntryId As String
Dim oXMLLocalDoc As New MSXML.DOMDocument
Dim oXMLLocal_Root As MSXML.IXMLDOMElement
Dim oXMLLocal_First As MSXML.IXMLDOMElement
Dim bLocalFlag As Boolean
Dim i As Integer

bOlFlag = oXMLOlDoc.loadXML(sCntsXMLStr)
bLocalFlag = oXMLLocalDoc.loadXML(sUpdatedLocalOlStr)

If (bOlFlag = True And bLocalFlag = True) Then

    Set oXMLOl_Root = oXMLOlDoc.documentElement
    Set oXMLLocal_Root = oXMLLocalDoc.documentElement
    
    If (oXMLLocal_Root.childNodes.Length > 0) Then
       
       frmSync.PrgBarSync.Min = 0
       frmSync.PrgBarSync.Max = oXMLLocal_Root.childNodes.Length
       frmSync.PrgBarSync.Value = 0
       frmSync.lblSynStatus.Caption = "Reading Deletions...."
       DoEvents
       For i = 0 To oXMLLocal_Root.selectNodes("contactitems").Length - 1
            
            Set oXMLLocal_First = oXMLLocal_Root.selectNodes("contactitems").Item(i)
            sLocalEntryId = oXMLLocal_First.getAttribute("entryid") & vbNullString
            
            If sLocalEntryId <> "" Then
            
                sXQString = "contactitems[@entryid='" & sLocalEntryId & "']"
                Set oXMLOl_First = oXMLOl_Root.selectSingleNode(sXQString)
                
                If oXMLOl_First Is Nothing Then
                    Call AddAttribute(oXMLLocal_First, "syncflag", "D")
                End If
                
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
       Next i
       ''sCheckOlDeleteContacts = oXMLLocalDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

sCheckOlDeleteContacts = oXMLLocalDoc.xml
GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckOlDeleteContacts" & Err.Description
    LogTheMessage ("sCheckOlDeleteContacts - " & Err.Description)
    sCheckOlDeleteContacts = ""
EXIT_ROUTINE:
    Set oXMLOlDoc = Nothing
    Set oXMLOl_Root = Nothing
    Set oXMLOl_First = Nothing
    Set oXMLLocalDoc = Nothing
    Set oXMLLocal_Root = Nothing
    Set oXMLLocal_First = Nothing
End Function
'*******************************************************************************
'vtiger
'*******************************************************************************
Public Function sCheckVtNewContacts(ByVal sVtCntsXMLStr As String) As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oXMLLocalDoc As New MSXML.DOMDocument
Dim oXMLLocalElmnt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalNode As MSXML.IXMLDOMNode
Dim oXMLLocalInst As MSXML.IXMLDOMProcessingInstruction

Dim oXMLAppendDoc As New MSXML.DOMDocument
Dim oXMLAppend_Root As MSXML.IXMLDOMElement

Dim oXMLVtDoc As New MSXML.DOMDocument
Dim oXMLVt_Root As MSXML.IXMLDOMElement
Dim oXMLVt_First As MSXML.IXMLDOMElement

Dim i As Integer

Set oXMLLocalInst = oXMLLocalDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
oXMLLocalDoc.insertBefore oXMLLocalInst, oXMLLocalDoc.FirstChild

Set oXMLLocalElmnt_Root = oXMLLocalDoc.createElement("vtigercrm")
Set oXMLLocalDoc.documentElement = oXMLLocalElmnt_Root
       
If (oXMLVtDoc.loadXML(sVtCntsXMLStr) = True) Then
    Set oXMLVt_Root = oXMLVtDoc.documentElement
    If (oXMLVt_Root.childNodes.Length > 0) Then
    
       For i = 0 To oXMLVt_Root.childNodes.Length - 1
            
            Set oXMLVt_First = oXMLVt_Root.childNodes.Item(i)

            If Not oXMLVt_First Is Nothing Then
                If Not oXMLAppendDoc.loadXML(oXMLVt_First.xml) Then GoTo ERROR_EXIT_ROUTINE
                Set oXMLAppend_Root = oXMLAppendDoc.documentElement
                oXMLAppend_Root.setAttribute "syncflag", "N"
                Set oXMLLocalNode = oXMLLocalElmnt_Root.appendChild(oXMLAppend_Root)
            End If
            
       Next i
       'oXMLLocalDoc.Save (gsVtUserFolder & LOCAL_ZOHO_FILE)
       ''sCheckVtNewContacts = oXMLLocalDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

'bCheckZohoNewContacts = True
sCheckVtNewContacts = oXMLLocalDoc.xml
GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
   'MsgBox "sCheckVtNewContacts" & Err.Description
   LogTheMessage ("sCheckVtNewContacts - " & Err.Description)
    sCheckVtNewContacts = ""
EXIT_ROUTINE:
    Set oXMLVtDoc = Nothing
    Set oXMLVt_Root = Nothing
    Set oXMLVt_First = Nothing
    Set oXMLLocalDoc = Nothing
    Set oXMLLocalElmnt_Root = Nothing
    Set oXMLLocalNode = Nothing
    Set oXMLLocalInst = Nothing
    Set oXMLAppendDoc = Nothing
    Set oXMLAppend_Root = Nothing
End Function


Public Function sCheckVtUpdateContacts(ByVal sCntsXMLStr As String) As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oXMLVtDoc As New MSXML.DOMDocument
Dim oXMLVt_Root As MSXML.IXMLDOMElement
Dim oXMLVt_First As MSXML.IXMLDOMElement
Dim sVtCrmId As String
Dim bVtFlag As Boolean

Dim sXQString As String
Dim oXMLLocalVtDoc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement
Dim oXMLLocalVt_Second As MSXML.IXMLDOMElement
Dim oXMLLocalVt_Node As MSXML.IXMLDOMNode

Dim oXMLAppendDoc As New MSXML.DOMDocument
Dim oXMLAppend_Root As MSXML.IXMLDOMElement

Dim bLocalFlag As Boolean

Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction

Dim i As Integer

bVtFlag = oXMLVtDoc.loadXML(sCntsXMLStr)
bLocalFlag = oXMLLocalVtDoc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)

If (bVtFlag = True And bLocalFlag = True) Then
    Set oXMLVt_Root = oXMLVtDoc.documentElement
    Set oXMLLocalVt_Root = oXMLLocalVtDoc.documentElement
    
    If (oXMLVt_Root.childNodes.Length > 0) Then
       
        frmSync.PrgBarSync.Min = 0
        frmSync.PrgBarSync.Max = oXMLVt_Root.childNodes.Length
        frmSync.PrgBarSync.Value = 0
        frmSync.lblSynStatus.Caption = "Reading Updations...."
        DoEvents
        
       For i = 0 To oXMLVt_Root.selectNodes("contactitems").Length - 1
            
            Set oXMLVt_First = oXMLVt_Root.selectNodes("contactitems").Item(i)
            sVtCrmId = oXMLVt_First.getAttribute("crmid")
            
            If sVtCrmId <> "" Then
                
                sXQString = "contactitems[@crmid='" & sVtCrmId & "']"
                Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQString)
                
                If Not oXMLLocalVt_First Is Nothing Then
                    If Not oXMLAppendDoc.loadXML(oXMLVt_First.xml) Then GoTo ERROR_EXIT_ROUTINE
                    
                    If (bVtUpdateCheck(oXMLVt_First, oXMLLocalVt_First) = True) Then
                        Set oXMLAppend_Root = oXMLAppendDoc.documentElement
                        Call AddAttribute(oXMLAppend_Root, "syncflag", "M")
                        Set oXMLLocalVt_Node = oXMLLocalVt_Root.replaceChild(oXMLAppend_Root, oXMLLocalVt_First)
                    Else
                        Call AddAttribute(oXMLLocalVt_First, "syncflag", "NM")
                    End If
                    
                Else
                
                    If Not oXMLAppendDoc.loadXML(oXMLVt_First.xml) Then GoTo ERROR_EXIT_ROUTINE
                    Set oXMLAppend_Root = oXMLAppendDoc.documentElement
                    Call AddAttribute(oXMLAppend_Root, "syncflag", "N")
                    Set oXMLLocalVt_Node = oXMLLocalVt_Root.appendChild(oXMLAppend_Root)
                    
                End If
            End If
            
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
            
       Next i
       'oXMLLocalVtDoc.Save (gsVtUserFolder & LOCAL_ZOHO_FILE)
       ''sCheckVtUpdateContacts = oXMLLocalVtDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

sCheckVtUpdateContacts = oXMLLocalVtDoc.xml
'bCheckZohoUpdateContacts = True
GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckVtUpdateContacts" & Err.Description
    LogTheMessage ("sCheckVtUpdateContacts - " & Err.Description)
    sCheckVtUpdateContacts = ""
EXIT_ROUTINE:
    Set oXMLVtDoc = Nothing
    Set oXMLVt_Root = Nothing
    Set oXMLVt_First = Nothing
    Set oXMLLocalVtDoc = Nothing
    Set oXMLLocalVt_Root = Nothing
    Set oXMLLocalVt_First = Nothing
    Set oXMLLocalVt_Second = Nothing
    Set oXMLLocalVt_Node = Nothing
End Function

Public Function bVtUpdateCheck(ByVal oXMLVt_Elmnt As MSXML.IXMLDOMElement, ByVal oXMLLocal_Elmnt As MSXML.IXMLDOMElement) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sModifiedFlag As Boolean

    If ((Not oXMLVt_Elmnt Is Nothing) And (Not oXMLLocal_Elmnt Is Nothing)) Then
    
        If oXMLVt_Elmnt.selectSingleNode("title").Text <> oXMLLocal_Elmnt.selectSingleNode("title").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("firstname").Text <> oXMLLocal_Elmnt.selectSingleNode("firstname").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("lastname").Text <> oXMLLocal_Elmnt.selectSingleNode("lastname").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("birthdate").Text <> oXMLLocal_Elmnt.selectSingleNode("birthdate").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("emailaddress").Text <> oXMLLocal_Elmnt.selectSingleNode("emailaddress").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("jobtitle").Text <> oXMLLocal_Elmnt.selectSingleNode("jobtitle").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("department").Text <> oXMLLocal_Elmnt.selectSingleNode("department").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("accountname").Text <> oXMLLocal_Elmnt.selectSingleNode("accountname").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("officephone").Text <> oXMLLocal_Elmnt.selectSingleNode("officephone").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("homephone").Text <> oXMLLocal_Elmnt.selectSingleNode("homephone").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("otherphone").Text <> oXMLLocal_Elmnt.selectSingleNode("otherphone").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("fax").Text <> oXMLLocal_Elmnt.selectSingleNode("fax").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("mobile").Text <> oXMLLocal_Elmnt.selectSingleNode("mobile").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("asstname").Text <> oXMLLocal_Elmnt.selectSingleNode("asstname").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("asstphone").Text <> oXMLLocal_Elmnt.selectSingleNode("asstphone").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("reportsto").Text <> oXMLLocal_Elmnt.selectSingleNode("reportsto").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("mailingstreet").Text <> oXMLLocal_Elmnt.selectSingleNode("mailingstreet").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("mailingcity").Text <> oXMLLocal_Elmnt.selectSingleNode("mailingcity").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("mailingstate").Text <> oXMLLocal_Elmnt.selectSingleNode("mailingstate").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("mailingzip").Text <> oXMLLocal_Elmnt.selectSingleNode("mailingzip").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("mailingcountry").Text <> oXMLLocal_Elmnt.selectSingleNode("mailingcountry").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("otherstreet").Text <> oXMLLocal_Elmnt.selectSingleNode("otherstreet").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("othercity").Text <> oXMLLocal_Elmnt.selectSingleNode("othercity").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("otherstate").Text <> oXMLLocal_Elmnt.selectSingleNode("otherstate").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("otherzip").Text <> oXMLLocal_Elmnt.selectSingleNode("otherzip").Text Then GoTo ERROR_EXIT_ROUTINE
        If oXMLVt_Elmnt.selectSingleNode("othercountry").Text <> oXMLLocal_Elmnt.selectSingleNode("othercountry").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("description").Text <> oXMLLocal_Elmnt.selectSingleNode("description").Text Then GoTo ERROR_EXIT_ROUTINE
        
    End If
    
bVtUpdateCheck = False
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    bVtUpdateCheck = True
EXIT_ROUTINE:
Set oXMLVt_Elmnt = Nothing
Set oXMLLocal_Elmnt = Nothing
End Function

Public Function sCheckVtDeleteContacts(ByVal sUpdatedVtXML As String, ByVal sVtCntsXMLStr As String) As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oXMLVtDoc As New MSXML.DOMDocument
Dim oXMLVt_Root As MSXML.IXMLDOMElement
Dim oXMLVt_First As MSXML.IXMLDOMElement
Dim bVtFlag As Boolean

Dim sLocalCrmId As String
Dim sXQString As String
Dim bLocalFlag As Boolean
Dim oXMLLocalDoc As New MSXML.DOMDocument
Dim oXMLLocal_Root As MSXML.IXMLDOMElement
Dim oXMLLocal_First As MSXML.IXMLDOMElement
Dim oXMLLocal_Second As MSXML.IXMLDOMElement
Dim oXMLLocal_Node As MSXML.IXMLDOMNode
Dim i As Integer

bVtFlag = oXMLVtDoc.loadXML(sVtCntsXMLStr)
bLocalFlag = oXMLLocalDoc.loadXML(sUpdatedVtXML)

If (bVtFlag = True And bLocalFlag = True) Then

    Set oXMLVt_Root = oXMLVtDoc.documentElement
    Set oXMLLocal_Root = oXMLLocalDoc.documentElement
    
    If (oXMLLocal_Root.childNodes.Length > 0) Then
       
       frmSync.PrgBarSync.Min = 0
       frmSync.PrgBarSync.Max = oXMLLocal_Root.childNodes.Length
       frmSync.PrgBarSync.Value = 0
       frmSync.lblSynStatus.Caption = "Reading Deletions...."
       DoEvents
       
       For i = 0 To oXMLLocal_Root.selectNodes("contactitems").Length - 1
            
            Set oXMLLocal_First = oXMLLocal_Root.selectNodes("contactitems").Item(i)
            sLocalCrmId = oXMLLocal_First.getAttribute("crmid") & vbNullString
            
            If sLocalCrmId <> "" Then
                sXQString = "contactitems[@crmid='" & sLocalCrmId & "']"
                                
                Set oXMLVt_First = oXMLVt_Root.selectSingleNode(sXQString)

                If oXMLVt_First Is Nothing Then
                    Call AddAttribute(oXMLLocal_First, "syncflag", "D")
                End If
                
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
       Next i
       ''sCheckVtDeleteContacts = oXMLLocalDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

sCheckVtDeleteContacts = oXMLLocalDoc.xml
'sCheckVtDeleteContacts = True
GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckVtDeleteContacts" & Err.Description
    LogTheMessage ("sCheckVtDeleteContacts - " & Err.Description)
    sCheckVtDeleteContacts = ""
EXIT_ROUTINE:
    Set oXMLVtDoc = Nothing
    Set oXMLVt_Root = Nothing
    Set oXMLVt_First = Nothing
    Set oXMLLocalDoc = Nothing
    Set oXMLLocal_Root = Nothing
    Set oXMLLocal_First = Nothing
    Set oXMLLocal_Second = Nothing
    Set oXMLLocal_Node = Nothing
End Function

