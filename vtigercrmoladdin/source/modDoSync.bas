Attribute VB_Name = "modDoSync"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit
Public Function AcceptChanges(ByVal sSyncModule As String)
Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Doc As New MSXML.DOMDocument

If sSyncModule <> "" Then
    Select Case (sSyncModule)
        Case "CONTACTSYNC":
            'frmSyncStatus.Hide
            If gsMappingSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
            If gsLocalOlSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
            If gsLocalVtSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
            
            If Not RemoveContacts(gsLocalOlSyncXML, gsMappingSyncXML) Then
                GoTo ERROR_EXIT_ROUTINE
            End If
            
            If oXMLLocalOl_Doc.loadXML(gsLocalOlSyncXML) = True Then
                oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
            End If
            
            If oXMLLocalVt_Doc.loadXML(gsLocalVtSyncXML) = True Then
                oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
            End If
            
            If oXMLMap_Doc.loadXML(gsMappingSyncXML) = True Then
                oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
            End If
            
            If Not bNewVtCntsInOl() Then GoTo ERROR_EXIT_ROUTINE
            
            If Not bNewOlCntsInVt() Then GoTo ERROR_EXIT_ROUTINE
        Case "TASKSYNC":
            If gsMappingSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
            If gsLocalOlSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
            If gsLocalVtSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
            
            If oXMLLocalOl_Doc.loadXML(gsLocalOlSyncXML) = True Then
                oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
            End If
            
            If oXMLLocalVt_Doc.loadXML(gsLocalVtSyncXML) = True Then
                oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
            End If
            
            If oXMLMap_Doc.loadXML(gsMappingSyncXML) = True Then
                oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
            End If
            
            If Not bNewVtTasksInOl() Then GoTo ERROR_EXIT_ROUTINE
            
            If Not bNewOlTasksInVt() Then GoTo ERROR_EXIT_ROUTINE
        Case "CALENDARSYNC":
            If gsMappingSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
            If gsLocalOlSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
            If gsLocalVtSyncXML = "" Then GoTo ERROR_EXIT_ROUTINE
            
            If Not RemoveCalendar(gsLocalOlSyncXML, gsMappingSyncXML) Then
                GoTo ERROR_EXIT_ROUTINE
            End If
                        
            If oXMLLocalOl_Doc.loadXML(gsLocalOlSyncXML) = True Then
                oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
            End If
            
            If oXMLLocalVt_Doc.loadXML(gsLocalVtSyncXML) = True Then
                oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
            End If
            
            If oXMLMap_Doc.loadXML(gsMappingSyncXML) = True Then
                oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
            End If
            
            If Not bNewVtClndrInOl() Then GoTo ERROR_EXIT_ROUTINE
            
            If Not bNewOlClndrInVt() Then GoTo ERROR_EXIT_ROUTINE
    End Select
    
End If
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalOl_Doc = Nothing
End Function
Public Function bNewVtCntsInOl() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim i As Integer

Dim sCrmId As String
Dim sEntryId As String
Dim sMCrmId As String
Dim sMEntryId As String
Dim sXQuery As String

Dim bMapFlag As Boolean
Dim bLocalVtFlag As Boolean
Dim bLocalOlFlag As Boolean

Dim sErrMsg As String

Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_NodeList As MSXML.IXMLDOMNodeList
Dim oXMLMap_First As MSXML.IXMLDOMElement

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement
Dim oXMLDel_Node As MSXML.IXMLDOMNode
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

bMapFlag = oXMLMap_Doc.Load(gsVtUserFolder & MAPPING_VTIGER_OL)
bLocalVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
bLocalOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)

If bMapFlag = True And bLocalVtFlag = True And bLocalOlFlag = True Then
         
     Set oXMLMap_Root = oXMLMap_Doc.documentElement
     Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
     Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
     
     'Create New Vt Contacts in Outlook
     sXQuery = "syncitem[@vtsyncflag='N' and @type='CNTS']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
     
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Adding Contacts...."
            DoEvents
        End If
        
        sErrMsg = "Error while creating contacts in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sCrmId = oXMLMap_First.getAttribute("crmid")
                If sCrmId <> "" Then
                    sXQuery = "contactitems[@crmid='" & sCrmId & "']"
                    Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                    If Not oXMLLocalVt_First Is Nothing Then
                           sEntryId = sCreateOlContacts(oXMLLocalVt_First)
                           If sEntryId <> "" Then
                                AddAttribute oXMLMap_First, "vtsyncflag", "S"
                                AddAttribute oXMLMap_First, "entryid", sEntryId
                                AddAttribute oXMLMap_First, "olsyncflag", "S"
                           End If
                    End If
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
        
     End If
     
     'Modify Vt Contacts in Outlook
     sXQuery = "syncitem[@vtsyncflag='M' and @olsyncflag!='M' and @type='CNTS']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Updating Contacts...."
            DoEvents
        End If
        
        sErrMsg = "Error while updating contacts in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMCrmId <> "" And sMEntryId <> "" Then
                      If bUpdateOlContacts(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      AddAttribute oXMLMap_First, "vtsyncflag", "S"
                      AddAttribute oXMLMap_First, "olsyncflag", "S"
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Modify Vt Contacts in Outlook and Outlook Contact in Vt in Case of Conflict
     sXQuery = "syncitem[@vtsyncflag='M' and @olsyncflag='M' and @type='CNTS']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Updating Contacts...."
            DoEvents
        End If
        
        sErrMsg = "Error while updating contacts in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMCrmId <> "" And sMEntryId <> "" Then
                      If bUpdateOlContacts(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      If bUpdateVtContacts(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      AddAttribute oXMLMap_First, "vtsyncflag", "S"
                      AddAttribute oXMLMap_First, "olsyncflag", "S"
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Delete Vt Contacts in Outlook
     sXQuery = "syncitem[@vtsyncflag='D' and @olsyncflag!='' and @type='CNTS']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Deleting Contacts...."
            DoEvents
        End If
        
        sErrMsg = "Error while deleting contacts in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMEntryId <> "" And sMCrmId <> "" Then
                    If bDelOlContacts(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                    Set oXMLDel_Node = oXMLMap_Root.removeChild(oXMLMap_First)
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
     
End If

bNewVtCntsInOl = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bNewVtCntsInOl = False
'sMsgDlg (Err.Description)
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage "bNewVtCntsInOl - " & Err.Description
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_First = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
End Function
Public Function bNewOlCntsInVt() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim i As Integer

Dim sCrmId As String
Dim sEntryId As String
Dim sMCrmId As String
Dim sMEntryId As String
Dim sXQuery As String

Dim bMapFlag As Boolean
Dim bLocalVtFlag As Boolean
Dim bLocalOlFlag As Boolean

Dim sErrMsg As String
Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_NodeList As MSXML.IXMLDOMNodeList
Dim oXMLMap_First As MSXML.IXMLDOMElement

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement
Dim oXMLDel_Node As MSXML.IXMLDOMNode
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

Dim oXMLNewDoc As New MSXML.DOMDocument
Dim oXMLNew_Root As MSXML.IXMLDOMElement
Dim oXMLNew_Node As MSXML.IXMLDOMNode
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction


bMapFlag = oXMLMap_Doc.Load(gsVtUserFolder & MAPPING_VTIGER_OL)
bLocalVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
bLocalOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)

If bMapFlag = True And bLocalVtFlag = True And bLocalOlFlag = True Then
         
     Set oXMLMap_Root = oXMLMap_Doc.documentElement
     Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
     Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
     
     'Create New Outlook Contacts in Vt
     sXQuery = "syncitem[@olsyncflag='N' and @type='CNTS']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Adding Contacts...."
            DoEvents
        End If
               
        sErrMsg = "Error while creating contacts in vtigerCRM"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sEntryId = oXMLMap_First.getAttribute("entryid")
                If sEntryId <> "" Then
                    sXQuery = "contactitems[@entryid='" & sEntryId & "']"
                    Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                    If Not oXMLLocalOl_First Is Nothing Then
                           sCrmId = sCreateVtContacts(oXMLLocalOl_First)
                           If sCrmId <> "" Then
                                AddAttribute oXMLMap_First, "olsyncflag", "S"
                                AddAttribute oXMLMap_First, "crmid", sCrmId
                                AddAttribute oXMLMap_First, "vtsyncflag", "S"
                           End If
                    End If
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
    End If
     
     
     'Modify Outlook Contacts in Vt
     sXQuery = "syncitem[@olsyncflag='M' and @vtsyncflag!='M' and @type='CNTS']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)

     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Updating Contacts...."
            DoEvents
        End If
        
        sErrMsg = "Error while updating contacts in vtigerCRM"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMCrmId <> "" And sMEntryId <> "" Then
                      If bUpdateVtContacts(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      AddAttribute oXMLMap_First, "vtsyncflag", "S"
                      AddAttribute oXMLMap_First, "olsyncflag", "S"
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Delete Outlook Contacts in Vt
     sXQuery = "syncitem[@olsyncflag='D' and @vtsyncflag!='' and @type='CNTS']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)

     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Deleting Contacts...."
            DoEvents
        End If
        
        sErrMsg = "Error while deleting contacts in vtigerCRM"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMEntryId <> "" And sMCrmId <> "" Then
                    If bDelVtContacts(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                    Set oXMLDel_Node = oXMLMap_Root.removeChild(oXMLMap_First)
                End If
            End If
        Next i
        frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        DoEvents
     End If
     
     oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
     
End If
bNewOlCntsInVt = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
bNewOlCntsInVt = False
'sMsgDlg ("bNewOlCntsInVt -- " & Err.Description)
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage "bNewOlCntsInVt - " & Err.Description
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_First = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
End Function
Public Function bNewVtTasksInOl() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim i As Integer

Dim sCrmId As String
Dim sEntryId As String
Dim sMCrmId As String
Dim sMEntryId As String
Dim sXQuery As String

Dim bMapFlag As Boolean
Dim bLocalVtFlag As Boolean
Dim bLocalOlFlag As Boolean

Dim sErrMsg As String
Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_NodeList As MSXML.IXMLDOMNodeList
Dim oXMLMap_First As MSXML.IXMLDOMElement

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement
Dim oXMLDel_Node As MSXML.IXMLDOMNode
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

bMapFlag = oXMLMap_Doc.Load(gsVtUserFolder & MAPPING_VTIGER_OL)
bLocalVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
bLocalOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)

If bMapFlag = True And bLocalVtFlag = True And bLocalOlFlag = True Then
         
     Set oXMLMap_Root = oXMLMap_Doc.documentElement
     Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
     Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
     
     'Create New Vt Tasks in Outlook
     sXQuery = "syncitem[@vtsyncflag='N' and @type='TASK']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
     
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Adding Tasks...."
            DoEvents
        End If
        
        sErrMsg = "Error while creating tasks in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sCrmId = oXMLMap_First.getAttribute("crmid")
                If sCrmId <> "" Then
                    sXQuery = "taskitems[@crmid='" & sCrmId & "']"
                    Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                    If Not oXMLLocalVt_First Is Nothing Then
                           sEntryId = sCreateOlTasks(oXMLLocalVt_First)
                           If sEntryId <> "" Then
                                AddAttribute oXMLMap_First, "vtsyncflag", "S"
                                AddAttribute oXMLMap_First, "entryid", sEntryId
                                AddAttribute oXMLMap_First, "olsyncflag", "S"
                           End If
                    End If
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Modify Vt Tasks in Outlook
     sXQuery = "syncitem[@vtsyncflag='M' and @olsyncflag!='M' and @type='TASK']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Updating Tasks...."
            DoEvents
        End If
        
        sErrMsg = "Error while updating tasks in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMCrmId <> "" And sMEntryId <> "" Then
                      If bUpdateOlTasks(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      AddAttribute oXMLMap_First, "vtsyncflag", "S"
                      AddAttribute oXMLMap_First, "olsyncflag", "S"
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Modify Vt Tasks in Outlook and Outlook Tasks in vt in Case of conflict
     sXQuery = "syncitem[@vtsyncflag='M' and @olsyncflag='M' and @type='TASK']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Updating Tasks...."
            DoEvents
        End If
        
        sErrMsg = "Error while updating tasks in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMCrmId <> "" And sMEntryId <> "" Then
                      If bUpdateOlTasks(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      If bUpdateVtTasks(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      AddAttribute oXMLMap_First, "vtsyncflag", "S"
                      AddAttribute oXMLMap_First, "olsyncflag", "S"
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Delete Vt Contacts in Outlook
     sXQuery = "syncitem[@vtsyncflag='D' and @olsyncflag!='' and @type='TASK']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Deleting Tasks...."
            DoEvents
        End If
        
        sErrMsg = "Error while deleting tasks in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMEntryId <> "" And sMCrmId <> "" Then
                    If bDelOlTasks(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                    Set oXMLDel_Node = oXMLMap_Root.removeChild(oXMLMap_First)
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
     
End If
bNewVtTasksInOl = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
bNewVtTasksInOl = False
'sMsgDlg (Err.Description)
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage "bNewVtTasksInOl - " & Err.Description
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_First = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
End Function
Public Function bNewOlTasksInVt() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim i As Integer

Dim sCrmId As String
Dim sEntryId As String
Dim sMCrmId As String
Dim sMEntryId As String
Dim sXQuery As String

Dim bMapFlag As Boolean
Dim bLocalVtFlag As Boolean
Dim bLocalOlFlag As Boolean

Dim sErrMsg As String
Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_NodeList As MSXML.IXMLDOMNodeList
Dim oXMLMap_First As MSXML.IXMLDOMElement

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement
Dim oXMLDel_Node As MSXML.IXMLDOMNode
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

Dim oXMLNewDoc As New MSXML.DOMDocument
Dim oXMLNew_Root As MSXML.IXMLDOMElement
Dim oXMLNew_Node As MSXML.IXMLDOMNode
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction


bMapFlag = oXMLMap_Doc.Load(gsVtUserFolder & MAPPING_VTIGER_OL)
bLocalVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
bLocalOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)

If bMapFlag = True And bLocalVtFlag = True And bLocalOlFlag = True Then
         
     Set oXMLMap_Root = oXMLMap_Doc.documentElement
     Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
     Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
     
     'Create New Outlook Contacts in Vt
     sXQuery = "syncitem[@olsyncflag='N' and @type='TASK']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Adding Tasks...."
            DoEvents
        End If
        
        sErrMsg = "Error while creating tasks in vtigerCRM"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sEntryId = oXMLMap_First.getAttribute("entryid")
                If sEntryId <> "" Then
                    sXQuery = "taskitems[@entryid='" & sEntryId & "']"
                    Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                    If Not oXMLLocalOl_First Is Nothing Then
                           sCrmId = sCreateVtTasks(oXMLLocalOl_First)
                           If sCrmId <> "" Then
                                AddAttribute oXMLMap_First, "olsyncflag", "S"
                                AddAttribute oXMLMap_First, "crmid", sCrmId
                                AddAttribute oXMLMap_First, "vtsyncflag", "S"
                           End If
                    End If
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
    End If
     
     
     'Modify Outlook Contacts in Vt
     sXQuery = "syncitem[@olsyncflag='M' and @vtsyncflag!='M' and @type='TASK']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)

     If Not oXMLMap_NodeList Is Nothing Then
     
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Updating Tasks...."
            DoEvents
        End If
        
        sErrMsg = "Error while updating tasks in vtigerCRM"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMCrmId <> "" And sMEntryId <> "" Then
                      If bUpdateVtTasks(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      AddAttribute oXMLMap_First, "vtsyncflag", "S"
                      AddAttribute oXMLMap_First, "olsyncflag", "S"
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Delete Outlook Contacts in Vt
     sXQuery = "syncitem[@olsyncflag='D' and @vtsyncflag!='' and @type='TASK']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)

     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Deleting Tasks...."
            DoEvents
        End If
        
        sErrMsg = "Error while deleting tasks in vtigerCRM"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMEntryId <> "" And sMCrmId <> "" Then
                    If bDelVtTasks(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                    Set oXMLDel_Node = oXMLMap_Root.removeChild(oXMLMap_First)
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
     
End If
bNewOlTasksInVt = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
bNewOlTasksInVt = False
'sMsgDlg ("bNewOlTasksInVt -- " & Err.Description)
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage "bNewOlTasksInVt - " & Err.Description
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_First = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
End Function

Public Function bNewVtClndrInOl() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim i As Integer

Dim sCrmId As String
Dim sEntryId As String
Dim sMCrmId As String
Dim sMEntryId As String
Dim sXQuery As String

Dim bMapFlag As Boolean
Dim bLocalVtFlag As Boolean
Dim bLocalOlFlag As Boolean

Dim sErrMsg As String
Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_NodeList As MSXML.IXMLDOMNodeList
Dim oXMLMap_First As MSXML.IXMLDOMElement

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement
Dim oXMLDel_Node As MSXML.IXMLDOMNode
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

bMapFlag = oXMLMap_Doc.Load(gsVtUserFolder & MAPPING_VTIGER_OL)
bLocalVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
bLocalOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)

If bMapFlag = True And bLocalVtFlag = True And bLocalOlFlag = True Then
         
     Set oXMLMap_Root = oXMLMap_Doc.documentElement
     Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
     Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
     
     'Create New Vt Clndr in Outlook
     sXQuery = "syncitem[@vtsyncflag='N' and @type='CLNDR']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
     
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Adding Clndr...."
            DoEvents
        End If
        
        sErrMsg = "Error while creating appointments in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sCrmId = oXMLMap_First.getAttribute("crmid")
                If sCrmId <> "" Then
                    sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
                    Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
                    If Not oXMLLocalVt_First Is Nothing Then
                           sEntryId = sCreateOlClndr(oXMLLocalVt_First)
                           If sEntryId <> "" Then
                                AddAttribute oXMLMap_First, "vtsyncflag", "S"
                                AddAttribute oXMLMap_First, "entryid", sEntryId
                                AddAttribute oXMLMap_First, "olsyncflag", "S"
                           End If
                    End If
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     
     'Modify Vt Clndr in Outlook and Outlook Clndr in vt when Conflict
     sXQuery = "syncitem[@vtsyncflag='M' and @olsyncflag='M' and @type='CLNDR']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Updating Clndr...."
            DoEvents
        End If
        
        sErrMsg = "Error while updating appointments in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMCrmId <> "" And sMEntryId <> "" Then
                      If bUpdateOlClndr(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      If bUpdateVtClndr(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      AddAttribute oXMLMap_First, "vtsyncflag", "S"
                      AddAttribute oXMLMap_First, "olsyncflag", "S"
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Modify Vt Clndr in Outlook
     sXQuery = "syncitem[@vtsyncflag='M' and @olsyncflag!='M' and @type='CLNDR']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Updating Clndr...."
            DoEvents
        End If
        
        sErrMsg = "Error while updating appointments in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMCrmId <> "" And sMEntryId <> "" Then
                      If bUpdateOlClndr(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      AddAttribute oXMLMap_First, "vtsyncflag", "S"
                      AddAttribute oXMLMap_First, "olsyncflag", "S"
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Delete Vt Contacts in Outlook
     sXQuery = "syncitem[@vtsyncflag='D' and @olsyncflag!='' and @type='CLNDR']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Deleting Clndr...."
            DoEvents
        End If
        
        sErrMsg = "Error while deleting appointments in outlook"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMEntryId <> "" And sMCrmId <> "" Then
                    If bDelOlClndr(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                    Set oXMLDel_Node = oXMLMap_Root.removeChild(oXMLMap_First)
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
     
End If
bNewVtClndrInOl = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
bNewVtClndrInOl = False
'sMsgDlg (Err.Description)
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage "bNewVtClndrInOl - " & Err.Description
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_First = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
End Function

Public Function bNewOlClndrInVt() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim i As Integer

Dim sCrmId As String
Dim sEntryId As String
Dim sMCrmId As String
Dim sMEntryId As String
Dim sXQuery As String

Dim bMapFlag As Boolean
Dim bLocalVtFlag As Boolean
Dim bLocalOlFlag As Boolean

Dim sErrMsg As String
Dim oXMLMap_Doc As New MSXML.DOMDocument
Dim oXMLMap_Root As MSXML.IXMLDOMElement
Dim oXMLMap_NodeList As MSXML.IXMLDOMNodeList
Dim oXMLMap_First As MSXML.IXMLDOMElement

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement
Dim oXMLDel_Node As MSXML.IXMLDOMNode
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

Dim oXMLNewDoc As New MSXML.DOMDocument
Dim oXMLNew_Root As MSXML.IXMLDOMElement
Dim oXMLNew_Node As MSXML.IXMLDOMNode
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction


bMapFlag = oXMLMap_Doc.Load(gsVtUserFolder & MAPPING_VTIGER_OL)
bLocalVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
bLocalOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)

If bMapFlag = True And bLocalVtFlag = True And bLocalOlFlag = True Then
         
     Set oXMLMap_Root = oXMLMap_Doc.documentElement
     Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
     Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
     
     'Create New Outlook Contacts in Vt
     sXQuery = "syncitem[@olsyncflag='N' and @type='CLNDR']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)
     
     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Adding Clndr...."
            DoEvents
        End If
        
        sErrMsg = "Error while creating appointments in vtigerCRM"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sEntryId = oXMLMap_First.getAttribute("entryid")
                If sEntryId <> "" Then
                    sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
                    Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
                    If Not oXMLLocalOl_First Is Nothing Then
                           sCrmId = sCreateVtClndr(oXMLLocalOl_First)
                           If sCrmId <> "" Then
                                AddAttribute oXMLMap_First, "olsyncflag", "S"
                                AddAttribute oXMLMap_First, "crmid", sCrmId
                                AddAttribute oXMLMap_First, "vtsyncflag", "S"
                           End If
                    End If
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
    End If
     
     
     'Modify Outlook Contacts in Vt
     sXQuery = "syncitem[@olsyncflag='M' and @vtsyncflag!='M' and @type='CLNDR']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)

     If Not oXMLMap_NodeList Is Nothing Then
     
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Updating Clndr...."
            DoEvents
        End If
        
        sErrMsg = "Error while creating appointments in vtigerCRM"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMCrmId <> "" And sMEntryId <> "" Then
                      If bUpdateVtClndr(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                      AddAttribute oXMLMap_First, "vtsyncflag", "S"
                      AddAttribute oXMLMap_First, "olsyncflag", "S"
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     'Delete Outlook Contacts in Vt
     sXQuery = "syncitem[@olsyncflag='D' and @vtsyncflag!='' and @type='CLNDR']"
     Set oXMLMap_NodeList = oXMLMap_Root.selectNodes(sXQuery)

     If Not oXMLMap_NodeList Is Nothing Then
        
        If oXMLMap_NodeList.Length > 0 Then
            frmSync.PrgBarSync.Min = 0
            frmSync.PrgBarSync.Max = oXMLMap_NodeList.Length
            frmSync.PrgBarSync.Value = 0
            frmSync.lblSynStatus.Caption = "Deleting Clndr...."
            DoEvents
        End If
        
        sErrMsg = "Error while creating appointments in vtigerCRM"
        For i = 0 To oXMLMap_NodeList.Length - 1
            Set oXMLMap_First = oXMLMap_NodeList.Item(i)
            If Not oXMLMap_First Is Nothing Then
                sMCrmId = oXMLMap_First.getAttribute("crmid")
                sMEntryId = oXMLMap_First.getAttribute("entryid")
                If sMEntryId <> "" And sMCrmId <> "" Then
                    If bDelVtClndr(sMEntryId, sMCrmId) = False Then GoTo ERROR_EXIT_ROUTINE
                    Set oXMLDel_Node = oXMLMap_Root.removeChild(oXMLMap_First)
                End If
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        Next i
     End If
     
     oXMLMap_Doc.Save (gsVtUserFolder & MAPPING_VTIGER_OL)
     
End If
bNewOlClndrInVt = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
bNewOlClndrInVt = False
'sMsgDlg ("bNewOlClndrInVt -- " & Err.Description)
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If
LogTheMessage "bNewOlClndrInVt - " & Err.Description
EXIT_ROUTINE:
Set oXMLMap_Doc = Nothing
Set oXMLMap_Root = Nothing
Set oXMLMap_First = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
End Function

Public Function RemoveContacts(ByRef LocalOlSyncXML As String, ByRef LocalMapSyncXML As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOlEdit_Doc As New MSXML.DOMDocument
Dim oXMLLocalMap_Doc As New MSXML.DOMDocument
Dim oXMLLocalMapEdit_Doc As New MSXML.DOMDocument

Dim oXMLParent_Doc As MSXML.IXMLDOMElement
Dim oXMLParentEdit_Doc As MSXML.IXMLDOMElement
Dim oXMLParentMap_Doc As MSXML.IXMLDOMElement
Dim oXMLParentMapEdit_Doc As MSXML.IXMLDOMElement

Dim oXMLTemp_Node As MSXML.IXMLDOMNode
Dim oXMLTempMap_Node As MSXML.IXMLDOMNode
Dim oXMLDel_Node As MSXML.IXMLDOMNode
Dim oXMLDelMap_Node As MSXML.IXMLDOMNode
Dim oXMLTemp_Elmt As MSXML.IXMLDOMElement


Dim i As Integer
Dim sEntryId As String
Dim sXQuery As String
Dim sOlQuery As String
Dim sErrMsg As String

Dim LocalOlXML As String
Dim LocalMapXml As String

LocalOlXML = LocalOlSyncXML
LocalMapXml = LocalMapSyncXML

sErrMsg = "Error while Loading Outlook and MappingXML"

'Local_Doc for iteration and LocalEdit_Doc for removing Child

If oXMLLocalOl_Doc.loadXML(LocalOlXML) = True And oXMLLocalOlEdit_Doc.loadXML(LocalOlXML) = True And oXMLLocalMap_Doc.loadXML(LocalMapXml) = True And oXMLLocalMapEdit_Doc.loadXML(LocalMapXml) = True Then
    
    sErrMsg = "Error while Loading Outlook and Mapping DocumentElement"
    
    'Parent_Doc for iteration and ParentEdit_Doc for removing Child
    
    Set oXMLParent_Doc = oXMLLocalOl_Doc.documentElement
    Set oXMLParentEdit_Doc = oXMLLocalOlEdit_Doc.documentElement
    Set oXMLParentMap_Doc = oXMLLocalMap_Doc.documentElement
    Set oXMLParentMapEdit_Doc = oXMLLocalMapEdit_Doc.documentElement
    
    For i = 0 To oXMLParent_Doc.childNodes.Length - 1
        If oXMLParent_Doc.childNodes.Item(i).baseName = "contactitems" Then
            If oXMLParent_Doc.childNodes.Item(i).selectSingleNode("lastname").Text = "" Then
                Set oXMLTemp_Node = oXMLParent_Doc.childNodes.Item(i)
                Set oXMLTemp_Elmt = oXMLParent_Doc.childNodes.Item(i)
                sEntryId = oXMLTemp_Elmt.getAttribute("entryid")
                sXQuery = "syncitem[@entryid='" & sEntryId & "']"
                sOlQuery = "contactitems[@entryid='" & sEntryId & "']"
                Set oXMLTempMap_Node = oXMLParentMapEdit_Doc.selectSingleNode(sXQuery)
                Set oXMLDelMap_Node = oXMLParentMapEdit_Doc.removeChild(oXMLTempMap_Node)
                Set oXMLTemp_Node = oXMLParentEdit_Doc.selectSingleNode(sOlQuery)
                Set oXMLDel_Node = oXMLParentEdit_Doc.removeChild(oXMLTemp_Node)
            End If
        End If
    Next i
End If

LocalOlSyncXML = oXMLParentEdit_Doc.xml
LocalMapSyncXML = oXMLParentMapEdit_Doc.xml
RemoveContacts = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
RemoveContacts = False
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If

EXIT_ROUTINE:

Set oXMLLocalOlEdit_Doc = Nothing
Set oXMLLocalMapEdit_Doc = Nothing
Set oXMLParentEdit_Doc = Nothing
Set oXMLParentMapEdit_Doc = Nothing

Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalMap_Doc = Nothing

Set oXMLParent_Doc = Nothing
Set oXMLParentMap_Doc = Nothing

Set oXMLTemp_Node = Nothing
Set oXMLTempMap_Node = Nothing
Set oXMLDel_Node = Nothing
Set oXMLDelMap_Node = Nothing
Set oXMLTemp_Elmt = Nothing

End Function

Public Function RemoveCalendar(ByRef LocalOlSyncXML As String, ByRef LocalMapSyncXML As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOlEdit_Doc As New MSXML.DOMDocument
Dim oXMLLocalMap_Doc As New MSXML.DOMDocument
Dim oXMLLocalMapEdit_Doc As New MSXML.DOMDocument

Dim oXMLParent_Doc As MSXML.IXMLDOMElement
Dim oXMLParentEdit_Doc As MSXML.IXMLDOMElement
Dim oXMLParentMap_Doc As MSXML.IXMLDOMElement
Dim oXMLParentMapEdit_Doc As MSXML.IXMLDOMElement

Dim oXMLTemp_Node As MSXML.IXMLDOMNode
Dim oXMLTempMap_Node As MSXML.IXMLDOMNode
Dim oXMLDel_Node As MSXML.IXMLDOMNode
Dim oXMLDelMap_Node As MSXML.IXMLDOMNode
Dim oXMLTemp_Elmt As MSXML.IXMLDOMElement


Dim i As Integer
Dim sEntryId As String
Dim sXQuery As String
Dim sOlQuery As String
Dim sErrMsg As String

Dim LocalOlXML As String
Dim LocalMapXml As String

LocalOlXML = LocalOlSyncXML
LocalMapXml = LocalMapSyncXML

sErrMsg = "Error while Loading Outlook and MappingXML"

'Local_Doc for iteration and LocalEdit_Doc for removing Child

If oXMLLocalOl_Doc.loadXML(LocalOlXML) = True And oXMLLocalOlEdit_Doc.loadXML(LocalOlXML) = True And oXMLLocalMap_Doc.loadXML(LocalMapXml) = True And oXMLLocalMapEdit_Doc.loadXML(LocalMapXml) = True Then
    
    sErrMsg = "Error while Loading Outlook and Mapping DocumentElement"
    
    'Parent_Doc for iteration and ParentEdit_Doc for removing Child
    
    Set oXMLParent_Doc = oXMLLocalOl_Doc.documentElement
    Set oXMLParentEdit_Doc = oXMLLocalOlEdit_Doc.documentElement
    Set oXMLParentMap_Doc = oXMLLocalMap_Doc.documentElement
    Set oXMLParentMapEdit_Doc = oXMLLocalMapEdit_Doc.documentElement
    
    For i = 0 To oXMLParent_Doc.childNodes.Length - 1
        If oXMLParent_Doc.childNodes.Item(i).baseName = "calendaritems" Then
            If oXMLParent_Doc.childNodes.Item(i).selectSingleNode("subject").Text = "" Then
                Set oXMLTemp_Node = oXMLParent_Doc.childNodes.Item(i)
                Set oXMLTemp_Elmt = oXMLParent_Doc.childNodes.Item(i)
                sEntryId = oXMLTemp_Elmt.getAttribute("entryid")
                sXQuery = "syncitem[@entryid='" & sEntryId & "']"
                sOlQuery = "calendaritems[@entryid='" & sEntryId & "']"
                Set oXMLTempMap_Node = oXMLParentMapEdit_Doc.selectSingleNode(sXQuery)
                Set oXMLDelMap_Node = oXMLParentMapEdit_Doc.removeChild(oXMLTempMap_Node)
                Set oXMLTemp_Node = oXMLParentEdit_Doc.selectSingleNode(sOlQuery)
                Set oXMLDel_Node = oXMLParentEdit_Doc.removeChild(oXMLTemp_Node)
            End If
        End If
    Next i
End If

LocalOlSyncXML = oXMLParentEdit_Doc.xml
LocalMapSyncXML = oXMLParentMapEdit_Doc.xml
RemoveCalendar = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
RemoveCalendar = False
If sErrMsg <> "" Then
    sMsgDlg (sErrMsg)
End If

EXIT_ROUTINE:

Set oXMLLocalOlEdit_Doc = Nothing
Set oXMLLocalMapEdit_Doc = Nothing
Set oXMLParentEdit_Doc = Nothing
Set oXMLParentMapEdit_Doc = Nothing

Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalMap_Doc = Nothing

Set oXMLParent_Doc = Nothing
Set oXMLParentMap_Doc = Nothing

Set oXMLTemp_Node = Nothing
Set oXMLTempMap_Node = Nothing
Set oXMLDel_Node = Nothing
Set oXMLDelMap_Node = Nothing
Set oXMLTemp_Elmt = Nothing

End Function


