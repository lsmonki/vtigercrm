Attribute VB_Name = "modSyncMapping"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit
Public Function bSyncMapping(ByVal sLocalOlXML As String, ByVal sLocalVtXML) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim oFS As New Scripting.FileSystemObject

Dim oXMLMapDoc As New MSXML.DOMDocument
Dim oXMLMapElement As MSXML.IXMLDOMElement
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction
Dim sErrMsg As String

If oFS.FileExists(gsVtUserFolder & MAPPING_VTIGER_OL) = True Then

    gsMappingSyncXML = sCreateSyncMapFile(sLocalOlXML, sLocalVtXML)
    sErrMsg = "Error while creating sync configuration"
    If gsMappingSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
    
    gsMappingSyncXML = sConflictCheck(gsMappingSyncXML, sLocalOlXML, sLocalVtXML)
    sErrMsg = "Error while creating conflict changes"
    If gsMappingSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
    
Else
    Set oXMLMapElement = oXMLMapDoc.createElement("mapolvtiger")
    Set oXMLMapDoc.documentElement = oXMLMapElement
    
    Set oXMLInst = oXMLMapDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
    oXMLMapDoc.insertBefore oXMLInst, oXMLMapDoc.FirstChild
    
    oXMLMapDoc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
    
    gsMappingSyncXML = sCreateSyncMapFile(sLocalOlXML, sLocalVtXML)
    sErrMsg = "Error while creating sync configuration"
    If gsMappingSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
End If

bSyncMapping = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    bSyncMapping = False
    'sMsgDlg ("bSyncMapping" & Err.Description)
    If sErrMsg <> "" Then
        sMsgDlg (sErrMsg)
    End If
EXIT_ROUTINE:
    Set oFS = Nothing
    Set oXMLMapDoc = Nothing
    Set oXMLMapElement = Nothing
    Set oXMLInst = Nothing
End Function
Public Function sCreateSyncMapFile(ByVal sLocalOlXMLStr As String, ByVal sLocalVtXMLStr As String) As String
On Error GoTo ERROR_EXIT_ROUTINE

Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_First As MSXML.IXMLDOMElement

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement

Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

Dim oXMLAppend_Element As MSXML.IXMLDOMElement
Dim oXMLAppend_Node As MSXML.IXMLDOMNode

Dim bMapFlag As Boolean
Dim bLocalVtFlag As Boolean
Dim bLocalOlFlag As Boolean

Dim sXOlQuery As String
Dim sXVtQuery As String

Dim sVtCrmId As String
Dim sOlEntryId As String

Dim sVtSyncFlag As String
Dim sOlSyncFlag As String

Dim sMapItem As String
Dim sMapType As String

Dim i As Integer


bMapFlag = oXMLMap_Doc.Load(gsVtUserFolder & MAPPING_VTIGER_OL)
bLocalOlFlag = oXMLLocalOl_Doc.loadXML(sLocalOlXMLStr)
bLocalVtFlag = oXMLLocalVt_Doc.loadXML(sLocalVtXMLStr)

If gsSyncModule = "CONTACTSYNC" Then
    sMapItem = "contactitems"
    sMapType = "CNTS"
ElseIf gsSyncModule = "TASKSYNC" Then
    sMapItem = "taskitems"
    sMapType = "TASK"
ElseIf gsSyncModule = "CALENDARSYNC" Then
    sMapItem = "calendaritems"
    sMapType = "CLNDR"
End If

If (bMapFlag = True And bLocalOlFlag = True And bLocalVtFlag = True) Then

    Set oXMLMap_Root = oXMLMap_Doc.documentElement
    Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
    Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
    
    If oXMLLocalOl_Root.childNodes.Length > 0 Then
        
        For i = 0 To oXMLLocalOl_Root.selectNodes(sMapItem).Length - 1
                    
            Set oXMLLocalOl_First = oXMLLocalOl_Root.selectNodes(sMapItem).Item(i)
            sOlEntryId = oXMLLocalOl_First.getAttribute("entryid")
            sOlSyncFlag = oXMLLocalOl_First.getAttribute("syncflag")
            
            If sOlEntryId <> "" Then
                sXOlQuery = "syncitem[@entryid='" & sOlEntryId & "']"
                Set oXMLMap_First = oXMLMap_Root.selectSingleNode(sXOlQuery)
                If oXMLMap_First Is Nothing Then
                    If (sOlSyncFlag <> "D") Then
                        Set oXMLAppend_Element = oXMLMap_Doc.createElement("syncitem")
                        oXMLAppend_Element.setAttribute "type", sMapType
                        oXMLAppend_Element.setAttribute "entryid", sOlEntryId
                        oXMLAppend_Element.setAttribute "olsyncflag", sOlSyncFlag
                        Set oXMLAppend_Node = oXMLMap_Root.appendChild(oXMLAppend_Element)
                    End If
                Else
                    If (sOlSyncFlag = "D") Then
                        If (Trim(oXMLMap_First.getAttribute("vtsyncflag") & vbNullString) = "") Then
                            Set oXMLAppend_Node = oXMLMap_Root.removeChild(oXMLMap_First)
                        End If
                    End If
                    
                    If (Trim(oXMLMap_First.getAttribute("vtsyncflag") & vbNullString) <> "") Then
                        Call AddAttribute(oXMLMap_First, "olsyncflag", sOlSyncFlag)
                    End If
                End If
            End If
        Next i
        'oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_Vt_OL)
    End If
    
    If oXMLLocalVt_Root.childNodes.Length > 0 Then
        
        For i = 0 To oXMLLocalVt_Root.selectNodes(sMapItem).Length - 1
                    
            Set oXMLLocalVt_First = oXMLLocalVt_Root.selectNodes(sMapItem).Item(i)
            sVtCrmId = oXMLLocalVt_First.getAttribute("crmid")
            sVtSyncFlag = oXMLLocalVt_First.getAttribute("syncflag")
            
            If sVtCrmId <> "" Then
                sXVtQuery = "syncitem[@crmid='" & sVtCrmId & "']"
                Set oXMLMap_First = oXMLMap_Root.selectSingleNode(sXVtQuery)
                If oXMLMap_First Is Nothing Then
                    If (sVtSyncFlag <> "D") Then
                        Set oXMLAppend_Element = oXMLMap_Doc.createElement("syncitem")
                        oXMLAppend_Element.setAttribute "type", sMapType
                        oXMLAppend_Element.setAttribute "crmid", sVtCrmId
                        oXMLAppend_Element.setAttribute "vtsyncflag", sVtSyncFlag
                        Set oXMLAppend_Node = oXMLMap_Root.appendChild(oXMLAppend_Element)
                    End If
                Else
                    If (sVtSyncFlag = "D") Then
                        If (Trim(oXMLMap_First.getAttribute("olsyncflag") & vbNullString) = "") Then
                            Set oXMLAppend_Node = oXMLMap_Root.removeChild(oXMLMap_First)
                        End If
                    End If
                    
                    If (Trim(oXMLMap_First.getAttribute("olsyncflag") & vbNullString) <> "") Then
                        Call AddAttribute(oXMLMap_First, "vtsyncflag", sVtSyncFlag)
                    End If
                End If
            End If
        Next i
        'oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_Vt_OL)
    End If
End If
'oXMLMap_Doc.Save "D:/final.xml"
sCreateSyncMapFile = oXMLMap_Doc.xml
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
'sMsgDlg ("sCreateSyncMapFile" & Err.Description)
LogTheMessage "sCreateSyncMapFile - " & Err.Description
sCreateSyncMapFile = ""
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_First = Nothing
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_First = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
Set oXMLAppend_Element = Nothing
Set oXMLAppend_Node = Nothing
End Function
Public Function sConflictCheck(ByVal sMapXML As String, ByVal sLocalOlXMLStr As String, ByVal sLocalVtXMLStr As String) As String

On Error GoTo ERROR_EXIT_ROUTINE
Dim i As Integer
Dim sCrmId As String
Dim sEntryId As String
Dim sXQuery As String

Dim bMapFlag As Boolean
Dim bLocalVtFlag As Boolean
Dim bLocalOlFlag As Boolean

Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_NodeList As MSXML.IXMLDOMNodeList
Dim oXMLMap_First As MSXML.IXMLDOMElement

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement

Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

bMapFlag = oXMLMap_Doc.loadXML(sMapXML)
bLocalOlFlag = oXMLLocalOl_Doc.loadXML(sLocalOlXMLStr)
bLocalVtFlag = oXMLLocalVt_Doc.loadXML(sLocalVtXMLStr)
If bMapFlag = True And bLocalOlFlag = True And bLocalVtFlag = True Then

     Set oXMLMap_Root = oXMLMap_Doc.documentElement
     Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
     Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
         
     sXQuery = "syncitem[@olsyncflag='M' and @vtsyncflag='M']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sCrmId = oXMLMap_First.getAttribute("crmid")
                sEntryId = oXMLMap_First.getAttribute("entryid")
                If sEntryId <> "" And sCrmId <> "" Then
                    
                    If gsSyncModule = "CONTACTSYNC" Then
                        sXQuery = "contactitems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        sXQuery = "contactitems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                    ElseIf gsSyncModule = "TASKSYNC" Then
                        sXQuery = "taskitems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        sXQuery = "taskitems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                    ElseIf gsSyncModule = "CALENDARSYNC" Then
                        sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
                        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                        sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
                        Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                    End If
                    
                    If gsShowDlg = 1 Then
                        Load frmConflict
                        If frmConflict.bPopulateConflict(oXMLLocalOl_First, oXMLLocalVt_First) = False Then GoTo EXIT_ROUTINE
                        frmConflict.Show vbModal
                        
                        If nConflictOption = 0 Then
                             Call AddAttribute(oXMLMap_First, "olsyncflag", "M")
                             Call AddAttribute(oXMLMap_First, "vtsyncflag", "M")
                             Call GetMergedDocument(oXMLLocalOl_First, oXMLLocalVt_First, "outlook", gsSyncModule)
                        ElseIf nConflictOption = 1 Then
                             Call AddAttribute(oXMLMap_First, "olsyncflag", "M")
                             Call AddAttribute(oXMLMap_First, "vtsyncflag", "M")
                             Call GetMergedDocument(oXMLLocalOl_First, oXMLLocalVt_First, "vtiger", gsSyncModule)
                        End If
                    Else
                        If gsNtfyConflict = 1 Then
                             Call AddAttribute(oXMLMap_First, "olsyncflag", "M")
                             Call AddAttribute(oXMLMap_First, "vtsyncflag", "M")
                             Call GetMergedDocument(oXMLLocalOl_First, oXMLLocalVt_First, "outlook", gsSyncModule)
                        ElseIf gsNtfyConflict = 2 Then
                            Call AddAttribute(oXMLMap_First, "olsyncflag", "M")
                            Call AddAttribute(oXMLMap_First, "vtsyncflag", "M")
                            Call GetMergedDocument(oXMLLocalOl_First, oXMLLocalVt_First, "vtiger", gsSyncModule)
                        End If
                    End If
                End If
            End If
        Next i
     End If
End If

sConflictCheck = oXMLMap_Doc.xml
gsLocalOlSyncXML = oXMLLocalOl_Doc.xml
gsLocalVtSyncXML = oXMLLocalVt_Doc.xml
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
'sMsgDlg ("sConflictCheck" & Err.Description)
LogTheMessage "sConflictCheck - " & Err.Description
sConflictCheck = ""
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_First = Nothing
Set oXMLMap_NodeList = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_First = Nothing
End Function

Public Function GetMergedDocument(ByRef oXMLLocalOl As MSXML.IXMLDOMElement, ByRef oXMLLocalVt As MSXML.IXMLDOMElement, ByVal Winner As String, ByVal SyncModule) As Boolean
If Winner <> "" Then
Select Case (SyncModule)
        Case "CONTACTSYNC"
            Select Case (Winner)
                Case "outlook":
                    If oXMLLocalOl.selectSingleNode("title").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("title").nodeTypedValue = oXMLLocalOl.selectSingleNode("title").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("title").nodeTypedValue = oXMLLocalVt.selectSingleNode("title").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("firstname").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("firstname").nodeTypedValue = oXMLLocalOl.selectSingleNode("firstname").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("firstname").nodeTypedValue = oXMLLocalVt.selectSingleNode("firstname").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("middlename").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("middlename").nodeTypedValue = oXMLLocalOl.selectSingleNode("middlename").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("middlename").nodeTypedValue = oXMLLocalVt.selectSingleNode("middlename").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("lastname").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("lastname").nodeTypedValue = oXMLLocalOl.selectSingleNode("lastname").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("lastname").nodeTypedValue = oXMLLocalVt.selectSingleNode("lastname").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("birthdate").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("birthdate").nodeTypedValue = oXMLLocalOl.selectSingleNode("birthdate").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("birthdate").nodeTypedValue = oXMLLocalVt.selectSingleNode("birthdate").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("emailaddress").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("emailaddress").nodeTypedValue = oXMLLocalOl.selectSingleNode("emailaddress").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("emailaddress").nodeTypedValue = oXMLLocalVt.selectSingleNode("emailaddress").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("jobtitle").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("jobtitle").nodeTypedValue = oXMLLocalOl.selectSingleNode("jobtitle").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("jobtitle").nodeTypedValue = oXMLLocalVt.selectSingleNode("jobtitle").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("department").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("department").nodeTypedValue = oXMLLocalOl.selectSingleNode("department").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("department").nodeTypedValue = oXMLLocalVt.selectSingleNode("department").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("accountname").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("accountname").nodeTypedValue = oXMLLocalOl.selectSingleNode("accountname").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("accountname").nodeTypedValue = oXMLLocalVt.selectSingleNode("accountname").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("officephone").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("officephone").nodeTypedValue = oXMLLocalOl.selectSingleNode("officephone").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("officephone").nodeTypedValue = oXMLLocalVt.selectSingleNode("officephone").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("homephone").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("homephone").nodeTypedValue = oXMLLocalOl.selectSingleNode("homephone").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("homephone").nodeTypedValue = oXMLLocalVt.selectSingleNode("homephone").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("otherphone").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("otherphone").nodeTypedValue = oXMLLocalOl.selectSingleNode("otherphone").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("otherphone").nodeTypedValue = oXMLLocalVt.selectSingleNode("otherphone").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("fax").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("fax").nodeTypedValue = oXMLLocalOl.selectSingleNode("fax").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("fax").nodeTypedValue = oXMLLocalVt.selectSingleNode("fax").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("mobile").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("mobile").nodeTypedValue = oXMLLocalOl.selectSingleNode("mobile").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("mobile").nodeTypedValue = oXMLLocalVt.selectSingleNode("mobile").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("asstname").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("asstname").nodeTypedValue = oXMLLocalOl.selectSingleNode("asstname").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("asstname").nodeTypedValue = oXMLLocalVt.selectSingleNode("asstname").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("reportsto").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("reportsto").nodeTypedValue = oXMLLocalOl.selectSingleNode("reportsto").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("reportsto").nodeTypedValue = oXMLLocalVt.selectSingleNode("reportsto").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("mailingstreet").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("mailingstreet").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingstreet").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("mailingstreet").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingstreet").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("mailingcity").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("mailingcity").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingcity").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("mailingcity").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingcity").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("mailingstate").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("mailingstate").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingstate").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("mailingstate").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingstate").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("mailingzip").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("mailingzip").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingzip").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("mailingzip").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingzip").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("mailingcountry").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("mailingcountry").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingcountry").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("mailingcountry").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingcountry").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("otherstreet").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("otherstreet").nodeTypedValue = oXMLLocalOl.selectSingleNode("otherstreet").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("otherstreet").nodeTypedValue = oXMLLocalVt.selectSingleNode("otherstreet").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("othercity").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("othercity").nodeTypedValue = oXMLLocalOl.selectSingleNode("othercity").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("othercity").nodeTypedValue = oXMLLocalVt.selectSingleNode("othercity").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("otherstate").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("otherstate").nodeTypedValue = oXMLLocalOl.selectSingleNode("otherstate").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("otherstate").nodeTypedValue = oXMLLocalVt.selectSingleNode("otherstate").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("otherzip").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("otherzip").nodeTypedValue = oXMLLocalOl.selectSingleNode("otherzip").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("otherzip").nodeTypedValue = oXMLLocalVt.selectSingleNode("otherzip").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("othercountry").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("othercountry").nodeTypedValue = oXMLLocalOl.selectSingleNode("othercountry").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("othercountry").nodeTypedValue = oXMLLocalVt.selectSingleNode("othercountry").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("description").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("description").nodeTypedValue = oXMLLocalOl.selectSingleNode("description").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("description").nodeTypedValue = oXMLLocalVt.selectSingleNode("description").nodeTypedValue
                    End If
                    
                Case "vtiger":
                        If oXMLLocalVt.selectSingleNode("title").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("title").nodeTypedValue = oXMLLocalVt.selectSingleNode("title").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("title").nodeTypedValue = oXMLLocalOl.selectSingleNode("title").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("firstname").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("firstname").nodeTypedValue = oXMLLocalVt.selectSingleNode("firstname").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("firstname").nodeTypedValue = oXMLLocalOl.selectSingleNode("firstname").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("middlename").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("middlename").nodeTypedValue = oXMLLocalVt.selectSingleNode("middlename").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("middlename").nodeTypedValue = oXMLLocalOl.selectSingleNode("middlename").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("lastname").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("lastname").nodeTypedValue = oXMLLocalVt.selectSingleNode("lastname").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("lastname").nodeTypedValue = oXMLLocalOl.selectSingleNode("lastname").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("birthdate").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("birthdate").nodeTypedValue = oXMLLocalVt.selectSingleNode("birthdate").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("birthdate").nodeTypedValue = oXMLLocalOl.selectSingleNode("birthdate").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("emailaddress").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("emailaddress").nodeTypedValue = oXMLLocalVt.selectSingleNode("emailaddress").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("emailaddress").nodeTypedValue = oXMLLocalOl.selectSingleNode("emailaddress").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("jobtitle").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("jobtitle").nodeTypedValue = oXMLLocalVt.selectSingleNode("jobtitle").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("jobtitle").nodeTypedValue = oXMLLocalOl.selectSingleNode("jobtitle").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("department").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("department").nodeTypedValue = oXMLLocalVt.selectSingleNode("department").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("department").nodeTypedValue = oXMLLocalOl.selectSingleNode("department").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("accountname").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("accountname").nodeTypedValue = oXMLLocalVt.selectSingleNode("accountname").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("accountname").nodeTypedValue = oXMLLocalOl.selectSingleNode("accountname").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("officephone").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("officephone").nodeTypedValue = oXMLLocalVt.selectSingleNode("officephone").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("officephone").nodeTypedValue = oXMLLocalOl.selectSingleNode("officephone").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("homephone").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("homephone").nodeTypedValue = oXMLLocalVt.selectSingleNode("homephone").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("homephone").nodeTypedValue = oXMLLocalOl.selectSingleNode("homephone").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("otherphone").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("otherphone").nodeTypedValue = oXMLLocalVt.selectSingleNode("otherphone").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("otherphone").nodeTypedValue = oXMLLocalOl.selectSingleNode("otherphone").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("fax").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("fax").nodeTypedValue = oXMLLocalVt.selectSingleNode("fax").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("fax").nodeTypedValue = oXMLLocalOl.selectSingleNode("fax").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("mobile").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("mobile").nodeTypedValue = oXMLLocalVt.selectSingleNode("mobile").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("mobile").nodeTypedValue = oXMLLocalOl.selectSingleNode("mobile").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("asstname").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("asstname").nodeTypedValue = oXMLLocalVt.selectSingleNode("asstname").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("asstname").nodeTypedValue = oXMLLocalOl.selectSingleNode("asstname").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("reportsto").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("reportsto").nodeTypedValue = oXMLLocalVt.selectSingleNode("reportsto").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("reportsto").nodeTypedValue = oXMLLocalOl.selectSingleNode("reportsto").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("mailingstreet").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("mailingstreet").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingstreet").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("mailingstreet").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingstreet").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("mailingcity").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("mailingcity").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingcity").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("mailingcity").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingcity").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("mailingstate").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("mailingstate").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingstate").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("mailingstate").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingstate").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("mailingzip").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("mailingzip").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingzip").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("mailingzip").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingzip").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("mailingcountry").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("mailingcountry").nodeTypedValue = oXMLLocalVt.selectSingleNode("mailingcountry").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("mailingcountry").nodeTypedValue = oXMLLocalOl.selectSingleNode("mailingcountry").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("otherstreet").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("otherstreet").nodeTypedValue = oXMLLocalVt.selectSingleNode("otherstreet").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("otherstreet").nodeTypedValue = oXMLLocalOl.selectSingleNode("otherstreet").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("othercity").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("othercity").nodeTypedValue = oXMLLocalVt.selectSingleNode("othercity").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("othercity").nodeTypedValue = oXMLLocalOl.selectSingleNode("othercity").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("otherstate").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("otherstate").nodeTypedValue = oXMLLocalVt.selectSingleNode("otherstate").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("otherstate").nodeTypedValue = oXMLLocalOl.selectSingleNode("otherstate").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("otherzip").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("otherzip").nodeTypedValue = oXMLLocalVt.selectSingleNode("otherzip").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("otherzip").nodeTypedValue = oXMLLocalOl.selectSingleNode("otherzip").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("othercountry").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("othercountry").nodeTypedValue = oXMLLocalVt.selectSingleNode("othercountry").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("othercountry").nodeTypedValue = oXMLLocalOl.selectSingleNode("othercountry").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("description").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("description").nodeTypedValue = oXMLLocalVt.selectSingleNode("description").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("description").nodeTypedValue = oXMLLocalOl.selectSingleNode("description").nodeTypedValue
                    End If
        
            End Select
        Case "TASKSYNC":
            Select Case (Winner)
                Case "outlook":
                    If oXMLLocalOl.selectSingleNode("subject").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("subject").nodeTypedValue = oXMLLocalOl.selectSingleNode("subject").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("subject").nodeTypedValue = oXMLLocalVt.selectSingleNode("subject").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue = oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue = oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue = oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue = oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("status").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("status").nodeTypedValue = oXMLLocalOl.selectSingleNode("status").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("status").nodeTypedValue = oXMLLocalVt.selectSingleNode("status").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("priority").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("priority").nodeTypedValue = oXMLLocalOl.selectSingleNode("priority").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("priority").nodeTypedValue = oXMLLocalVt.selectSingleNode("priority").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("description").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("description").nodeTypedValue = oXMLLocalOl.selectSingleNode("description").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("description").nodeTypedValue = oXMLLocalVt.selectSingleNode("description").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("contactname").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("contactname").nodeTypedValue = oXMLLocalOl.selectSingleNode("contactname").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("contactname").nodeTypedValue = oXMLLocalVt.selectSingleNode("contactname").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("category").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("category").nodeTypedValue = oXMLLocalOl.selectSingleNode("category").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("category").nodeTypedValue = oXMLLocalVt.selectSingleNode("category").nodeTypedValue
                    End If
                
                Case "vtiger":
                    If oXMLLocalVt.selectSingleNode("subject").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("subject").nodeTypedValue = oXMLLocalVt.selectSingleNode("subject").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("subject").nodeTypedValue = oXMLLocalOl.selectSingleNode("subject").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue = oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue = oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue = oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue = oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("status").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("status").nodeTypedValue = oXMLLocalVt.selectSingleNode("status").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("status").nodeTypedValue = oXMLLocalOl.selectSingleNode("status").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("priority").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("priority").nodeTypedValue = oXMLLocalVt.selectSingleNode("priority").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("priority").nodeTypedValue = oXMLLocalOl.selectSingleNode("priority").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("description").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("description").nodeTypedValue = oXMLLocalVt.selectSingleNode("description").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("description").nodeTypedValue = oXMLLocalOl.selectSingleNode("description").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("contactname").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("contactname").nodeTypedValue = oXMLLocalVt.selectSingleNode("contactname").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("contactname").nodeTypedValue = oXMLLocalOl.selectSingleNode("contactname").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("category").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("category").nodeTypedValue = oXMLLocalVt.selectSingleNode("category").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("category").nodeTypedValue = oXMLLocalOl.selectSingleNode("category").nodeTypedValue
                    End If
            End Select
        Case "CALENDARSYNC":
            Select Case (Winner)
                Case "outlook":
                    If oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue = oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue = oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("subject").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("subject").nodeTypedValue = oXMLLocalOl.selectSingleNode("subject").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("subject").nodeTypedValue = oXMLLocalVt.selectSingleNode("subject").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue = oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue = oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("description").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("description").nodeTypedValue = oXMLLocalOl.selectSingleNode("description").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("description").nodeTypedValue = oXMLLocalVt.selectSingleNode("description").nodeTypedValue
                    End If
                    If oXMLLocalOl.selectSingleNode("location").nodeTypedValue <> "" Then
                        oXMLLocalVt.selectSingleNode("location").nodeTypedValue = oXMLLocalOl.selectSingleNode("location").nodeTypedValue
                    Else
                        oXMLLocalOl.selectSingleNode("location").nodeTypedValue = oXMLLocalVt.selectSingleNode("location").nodeTypedValue
                    End If

                Case "vtiger":
                    If oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue = oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("duedate").nodeTypedValue = oXMLLocalOl.selectSingleNode("duedate").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("subject").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("subject").nodeTypedValue = oXMLLocalVt.selectSingleNode("subject").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("subject").nodeTypedValue = oXMLLocalOl.selectSingleNode("subject").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue = oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("startdate").nodeTypedValue = oXMLLocalOl.selectSingleNode("startdate").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("description").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("description").nodeTypedValue = oXMLLocalVt.selectSingleNode("description").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("description").nodeTypedValue = oXMLLocalOl.selectSingleNode("description").nodeTypedValue
                    End If
                    If oXMLLocalVt.selectSingleNode("location").nodeTypedValue <> "" Then
                        oXMLLocalOl.selectSingleNode("location").nodeTypedValue = oXMLLocalVt.selectSingleNode("location").nodeTypedValue
                    Else
                        oXMLLocalVt.selectSingleNode("location").nodeTypedValue = oXMLLocalOl.selectSingleNode("location").nodeTypedValue
                    End If
            End Select
    End Select
End If
    
End Function


