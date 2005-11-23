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
    oXMLMapDoc.insertBefore oXMLInst, oXMLMapDoc.firstChild
    
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
                             Call AddAttribute(oXMLMap_First, "vtsyncflag", "NM")
                        ElseIf nConflictOption = 1 Then
                             Call AddAttribute(oXMLMap_First, "olsyncflag", "NM")
                             Call AddAttribute(oXMLMap_First, "vtsyncflag", "M")
                        End If
                    Else
                        If gsNtfyConflict = 1 Then
                             Call AddAttribute(oXMLMap_First, "olsyncflag", "M")
                             Call AddAttribute(oXMLMap_First, "vtsyncflag", "NM")
                        ElseIf gsNtfyConflict = 2 Then
                            Call AddAttribute(oXMLMap_First, "olsyncflag", "NM")
                            Call AddAttribute(oXMLMap_First, "vtsyncflag", "M")
                        End If
                    End If
                End If
            End If
        Next i
     End If
End If
sConflictCheck = oXMLMap_Doc.xml
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
