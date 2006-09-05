Attribute VB_Name = "modTasks"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit
Public Function sGetOlTasks() As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlFolder As Outlook.MAPIFolder
Dim oOlItems As Outlook.Items
Dim oOlTasks As Outlook.TaskItem
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmnt_Root As MSXML.IXMLDOMElement
Dim oXMLElmnt_First As MSXML.IXMLDOMElement
Dim oXMLNode As MSXML.IXMLDOMNode
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction
Dim sStartDate As String
Dim sDueDate As String

If sGetPathAsString(oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderTasks)) <> gsTaskSyncFolder Then
    Set oOlFolder = oOlApp.GetNamespace("MAPI").GetFolderFromID(gsTaskSyncFolderId)
Else
    Set oOlFolder = oOlApp.ActiveExplorer.CurrentFolder
End If
    
Set oOlItems = oOlFolder.Items
Set oOlItems = oOlItems.Restrict("[MessageClass] = 'IPM.Task'")

Set oXMLInst = oXMLDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
oXMLDoc.insertBefore oXMLInst, oXMLDoc.FirstChild

Set oXMLElmnt_Root = oXMLDoc.createElement("outlook")
Set oXMLDoc.documentElement = oXMLElmnt_Root

If oOlItems.Count > 0 Then

oOlItems.GetFirst

frmSync.PrgBarSync.Min = 0
frmSync.PrgBarSync.Max = oOlItems.Count
frmSync.PrgBarSync.Value = 0
frmSync.lblSynStatus.Caption = "Reading Tasks...."
DoEvents

For Each oOlTasks In oOlItems
    
    sStartDate = Format(oOlTasks.StartDate, "YYYY")
    sDueDate = Format(oOlTasks.DueDate, "YYYY")
    
    If sStartDate <> "4501" Then
    
        Set oXMLElmnt_First = oXMLDoc.createElement("taskitems")
        Set oXMLNode = oXMLElmnt_Root.appendChild(oXMLElmnt_First)
        
        Call AddAttribute(oXMLElmnt_First, "entryid", oOlTasks.EntryID)
        
        sStartDate = Format(oOlTasks.StartDate, "YYYY")
        If sStartDate = "4501" Then
            sStartDate = ""
        Else
            sStartDate = Format(oOlTasks.StartDate, "YYYY-MM-DD")
        End If
        
        sDueDate = Format(oOlTasks.DueDate, "YYYY")
        If sDueDate = "4501" Then
            sDueDate = ""
        Else
            sDueDate = Format(oOlTasks.DueDate, "YYYY-MM-DD")
        End If
        
        Call AddChild(oXMLDoc, oXMLElmnt_First, "subject", EncodeUTF8(oOlTasks.Subject))
        Call AddChild(oXMLDoc, oXMLElmnt_First, "startdate", sStartDate)
        Call AddChild(oXMLDoc, oXMLElmnt_First, "duedate", sDueDate)
        Call AddChild(oXMLDoc, oXMLElmnt_First, "status", EncodeUTF8(oOlTasks.Status))
        Call AddChild(oXMLDoc, oXMLElmnt_First, "priority", EncodeUTF8(oOlTasks.Importance))
        Call AddChild(oXMLDoc, oXMLElmnt_First, "description", EncodeUTF8(oOlTasks.Body))
        Call AddChild(oXMLDoc, oXMLElmnt_First, "contactname", "")
        Call AddChild(oXMLDoc, oXMLElmnt_First, "category", EncodeUTF8(oOlTasks.Categories))
        
        frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        
     End If
Next
End If
sGetOlTasks = oXMLDoc.xml
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sMsgDlg ("sGetOlTasks" & Err.Description)
    sGetOlTasks = ""
EXIT_ROUTINE:
    'Set oOlApp = Nothing
    Set oOlFolder = Nothing
    Set oOlItems = Nothing
    Set oOlTasks = Nothing
    Set oXMLDoc = Nothing
    Set oXMLElmnt_Root = Nothing
    Set oXMLElmnt_First = Nothing
    Set oXMLNode = Nothing
End Function
Public Function sGetvTigerTasks() As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim oXMLVt_Doc As New MSXML.DOMDocument
Dim oXMLVt_RootElmnt As MSXML.IXMLDOMElement
Dim sVtTasksXML As String

frmSync.lblSynStatus.Caption = "Gettings Tasks...."
DoEvents

sVtTasksXML = svTigerSoGetTasks(gsVtUserId)
If sVtTasksXML <> "" Then
    If (oXMLVt_Doc.loadXML(sVtTasksXML) = True) Then
        Set oXMLVt_RootElmnt = oXMLVt_Doc.documentElement
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If
sGetvTigerTasks = oXMLVt_Doc.xml
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sMsgDlg ("sGetvTigerTasks" & Err.Description)
    sGetvTigerTasks = ""
EXIT_ROUTINE:
Set oXMLVt_Doc = Nothing
Set oXMLVt_RootElmnt = Nothing
End Function

Public Function sCreateOlTasks(ByVal oXMLVtElement As MSXML.IXMLDOMElement) As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlTask As Outlook.TaskItem
Dim oOlFolder As Outlook.MAPIFolder

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_Node As MSXML.IXMLDOMNode

Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

Dim oXMLAppend_Doc As New MSXML.DOMDocument
Dim oXMLAppend_Root As MSXML.IXMLDOMElement

Dim sBirthDate As String
Dim bOlFlag As Boolean
Dim bVtFlag As Boolean
Dim sCrmId As String
Dim sXQuery As String

If Not oXMLVtElement Is Nothing Then

    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
    
    Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
    Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
    
    'Set oOlTask = oOlApp.CreateItem(olTaskItem)
    If sGetPathAsString(oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderTasks)) <> gsTaskSyncFolder Then
        Set oOlFolder = oOlApp.GetNamespace("MAPI").GetFolderFromID(gsTaskSyncFolderId)
        Set oOlTask = oOlFolder.Items.Add("IPM.Task")
    Else
        Set oOlTask = oOlApp.ActiveExplorer.CurrentFolder.Items.Add("IPM.Task")
    End If

    oOlTask.Subject = DecodeUTF8(oXMLVtElement.selectSingleNode("subject").nodeTypedValue)
    oOlTask.StartDate = DecodeUTF8(oXMLVtElement.selectSingleNode("startdate").nodeTypedValue)
    oOlTask.DueDate = DecodeUTF8(oXMLVtElement.selectSingleNode("duedate").nodeTypedValue)
    oOlTask.Status = DecodeUTF8(oXMLVtElement.selectSingleNode("status").nodeTypedValue)
    oOlTask.Importance = DecodeUTF8(oXMLVtElement.selectSingleNode("priority").nodeTypedValue)
    oOlTask.Body = DecodeUTF8(oXMLVtElement.selectSingleNode("description").nodeTypedValue)
          
    oOlTask.Save
    
    sCrmId = oXMLVtElement.getAttribute("crmid")
    
    If Not oXMLAppend_Doc.loadXML(oXMLVtElement.xml) Then GoTo ERROR_EXIT_ROUTINE
    Set oXMLAppend_Root = oXMLAppend_Doc.documentElement
    
    oXMLAppend_Root.removeAttribute ("crmid")
    oXMLAppend_Root.removeAttribute ("syncflag")
        
    Call AddAttribute(oXMLAppend_Root, "entryid", oOlTask.EntryID)
    Call AddAttribute(oXMLAppend_Root, "syncflag", "NM")
    Set oXMLLocalOl_Node = oXMLLocalOl_Root.appendChild(oXMLAppend_Root)
    
    sXQuery = "taskitems[@crmid='" & sCrmId & "']"
    Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
    If oXMLLocalVt_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
    
    Call AddAttribute(oXMLLocalVt_First, "syncflag", "NM")
            
    sCreateOlTasks = oOlTask.EntryID
        
    oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
    oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
    
    End If
End If

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sCreateOlTasks = ""
    'sMsgDlg ("sCreateOlTasks" & Err.Description)
    LogTheMessage "sCreateOlTasks - " & Err.Description
EXIT_ROUTINE:
Set oOlTask = Nothing
Set oOlFolder = Nothing
Set oXMLVtElement = Nothing
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_Node = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
Set oXMLAppend_Doc = Nothing
Set oXMLAppend_Root = Nothing
End Function

Public Function bUpdateOlTasks(ByVal sEntryid As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlTask As Outlook.TaskItem
Dim oOlNS As Outlook.NameSpace
Dim sXQuery As String
Dim bOlFlag As Boolean
Dim bVtFlag As Boolean
Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement
Dim oXMLAppend_Doc As New MSXML.DOMDocument
Dim oXMLAppend_Root As MSXML.IXMLDOMElement
Dim oXMLAppend_Node As MSXML.IXMLDOMNode
Dim sBirthDate As String

If sEntryid <> "" And sCrmId <> "" Then
    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
        Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
        Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
        
        Set oOlNS = oOlApp.GetNamespace("MAPI")
        Set oOlTask = oOlNS.GetItemFromID(sEntryid)
                
        sXQuery = "taskitems[@crmid='" & sCrmId & "']"
        Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
        If oXMLLocalVt_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
                
        sXQuery = "taskitems[@entryid='" & sEntryid & "']"
        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        If oOlTask Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        oOlTask.Subject = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("subject").nodeTypedValue)
        oOlTask.StartDate = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("startdate").nodeTypedValue)
        oOlTask.DueDate = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("duedate").nodeTypedValue)
        oOlTask.Status = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("status").nodeTypedValue)
        oOlTask.Importance = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("priority").nodeTypedValue)
        oOlTask.Body = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("description").nodeTypedValue)
        
        oOlTask.Categories = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("category").nodeTypedValue)

        oOlTask.Save
              
        Call AddAttribute(oXMLLocalVt_First, "syncflag", "NM")
        
        If Not oXMLAppend_Doc.loadXML(oXMLLocalVt_First.xml) Then GoTo ERROR_EXIT_ROUTINE
        Set oXMLAppend_Root = oXMLAppend_Doc.documentElement
        
        oXMLAppend_Root.removeAttribute ("crmid")
        oXMLAppend_Root.removeAttribute ("syncflag")
        
        Call AddAttribute(oXMLAppend_Root, "entryid", sEntryid)
        Call AddAttribute(oXMLAppend_Root, "syncflag", "NM")
        Set oXMLAppend_Node = oXMLLocalOl_Root.replaceChild(oXMLAppend_Root, oXMLLocalOl_First)
                
        oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
        oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
    End If
End If
bUpdateOlTasks = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("bUpdateOlTasks" & Err.Description)
    LogTheMessage "bUpdateOlTasks - " & Err.Description
    bUpdateOlTasks = False
EXIT_ROUTINE:
Set oOlTask = Nothing
Set oOlNS = Nothing
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_First = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
Set oXMLAppend_Doc = Nothing
Set oXMLAppend_Root = Nothing
Set oXMLAppend_Node = Nothing
End Function

Public Function bDelOlTasks(ByVal sEntryid As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlTask As Outlook.TaskItem
Dim oOlNS As Outlook.NameSpace
Dim sXQuery As String
Dim bOlFlag As Boolean
Dim bVtFlag As Boolean
Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_Node As MSXML.IXMLDOMNode
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_Node As MSXML.IXMLDOMNode
Dim oXMLDel_Node As MSXML.IXMLDOMNode


If sEntryid <> "" And sCrmId <> "" Then
    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
        Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
        Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
        
        Set oOlNS = oOlApp.GetNamespace("MAPI")
        Set oOlTask = oOlNS.GetItemFromID(sEntryid)
        If Not oOlTask Is Nothing Then
            oOlTask.Delete
        End If
        
        sXQuery = "taskitems[@entryid='" & sEntryid & "']"
        Set oXMLLocalOl_Node = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        Set oXMLDel_Node = oXMLLocalOl_Root.removeChild(oXMLLocalOl_Node)
        
        sXQuery = "taskitems[@crmid='" & sCrmId & "']"
        Set oXMLLocalVt_Node = oXMLLocalVt_Root.selectSingleNode(sXQuery)
        If oXMLLocalVt_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        Set oXMLDel_Node = oXMLLocalVt_Root.removeChild(oXMLLocalVt_Node)
        
        oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
        oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        
    End If
End If
bDelOlTasks = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    bDelOlTasks = False
    LogTheMessage "bDelOlTasks - " & Err.Description
    'sMsgDlg ("bDelOlTasks" & Err.Description)
EXIT_ROUTINE:
Set oOlTask = Nothing
Set oOlNS = Nothing
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_Node = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_Node = Nothing
Set oXMLDel_Node = Nothing
End Function

Public Function sCreateVtTasks(ByVal oXMLOlElement As MSXML.IXMLDOMElement) As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim sCrmId As String
Dim bOlFlag As Boolean
Dim bVtFlag As Boolean
Dim sEntryid As String
Dim sXQuery As String

Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_Node As MSXML.IXMLDOMNode

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement

Dim oXMLAppend_Doc As New MSXML.DOMDocument
Dim oXMLAppend_Root As MSXML.IXMLDOMElement

 
If Not oXMLOlElement Is Nothing Then
    
    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
        Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
        Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
        
        sCrmId = sVtSoNewTasks(gsVtUserId, oXMLOlElement)
        sEntryid = oXMLOlElement.getAttribute("entryid")
                
        If Not oXMLAppend_Doc.loadXML(oXMLOlElement.xml) Then GoTo ERROR_EXIT_ROUTINE
               
        Set oXMLAppend_Root = oXMLAppend_Doc.documentElement
        
        oXMLAppend_Root.removeAttribute ("entryid")
        oXMLAppend_Root.removeAttribute ("syncflag")
        
        Call AddAttribute(oXMLAppend_Root, "crmid", sCrmId)
        Call AddAttribute(oXMLAppend_Root, "syncflag", "NM")
        Set oXMLLocalVt_Node = oXMLLocalVt_Root.appendChild(oXMLAppend_Root)
        
        sXQuery = "taskitems[@entryid='" & sEntryid & "']"
        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        Call AddAttribute(oXMLLocalOl_First, "syncflag", "NM")
        
        sCreateVtTasks = sCrmId
        
        oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
        oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        
    End If
End If
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sCreateVtTasks = ""
    LogTheMessage "sCreateVtTasks - " & Err.Description
    'sMsgDlg ("sCreateVtTasks" & Err.Description)
EXIT_ROUTINE:
Set oXMLOlElement = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_Node = Nothing
Set oXMLAppend_Doc = Nothing
Set oXMLAppend_Root = Nothing
End Function

Public Function bUpdateVtTasks(ByVal sEntryid As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim sXQuery As String
Dim bOlFlag As Boolean
Dim bVtFlag As Boolean

Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_First As MSXML.IXMLDOMElement

Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_First As MSXML.IXMLDOMElement

Dim oXMLAppend_Doc As New MSXML.DOMDocument
Dim oXMLAppend_Root As MSXML.IXMLDOMElement
Dim oXMLAppend_Node As MSXML.IXMLDOMNode


If sEntryid <> "" And sCrmId <> "" Then
    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
        Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
        Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
                       
        sXQuery = "taskitems[@crmid='" & sCrmId & "']"
        Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
        If oXMLLocalVt_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
                
        sXQuery = "taskitems[@entryid='" & sEntryid & "']"
        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        'Call Soap Method for Update
        If sVtSoUpdateTasks(sCrmId, oXMLLocalOl_First) <> "" Then
        
            Call AddAttribute(oXMLLocalOl_First, "syncflag", "NM")
            
            If Not oXMLAppend_Doc.loadXML(oXMLLocalOl_First.xml) Then GoTo ERROR_EXIT_ROUTINE
            Set oXMLAppend_Root = oXMLAppend_Doc.documentElement
            oXMLAppend_Root.removeAttribute ("entryid")
            oXMLAppend_Root.removeAttribute ("syncflag")
            Call AddAttribute(oXMLAppend_Root, "crmid", sCrmId)
            Call AddAttribute(oXMLAppend_Root, "syncflag", "NM")
            Set oXMLAppend_Node = oXMLLocalVt_Root.replaceChild(oXMLAppend_Root, oXMLLocalVt_First)
                    
            oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
            oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        End If
    End If
End If
bUpdateVtTasks = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("bUpdateVtTasks" & Err.Description)
    LogTheMessage "bUpdateVtTasks - " & Err.Description
    bUpdateVtTasks = False
EXIT_ROUTINE:
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_First = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_First = Nothing
Set oXMLAppend_Doc = Nothing
Set oXMLAppend_Root = Nothing
Set oXMLAppend_Node = Nothing
End Function

Public Function bDelVtTasks(ByVal sEntryid As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim sXQuery As String
Dim bOlFlag As Boolean
Dim bVtFlag As Boolean
Dim oXMLLocalOl_Doc As New MSXML.DOMDocument
Dim oXMLLocalOl_Root As MSXML.IXMLDOMElement
Dim oXMLLocalOl_Node As MSXML.IXMLDOMNode
Dim oXMLLocalVt_Doc As New MSXML.DOMDocument
Dim oXMLLocalVt_Root As MSXML.IXMLDOMElement
Dim oXMLLocalVt_Node As MSXML.IXMLDOMNode
Dim oXMLDel_Node As MSXML.IXMLDOMNode


If sEntryid <> "" And sCrmId <> "" Then
    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
        Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
        Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
        
        'Call Soap Method to Delete
        If bVtSoDeleteTasks(gsVtUserId, sCrmId) = True Then
            sXQuery = "taskitems[@entryid='" & sEntryid & "']"
            Set oXMLLocalOl_Node = oXMLLocalOl_Root.selectSingleNode(sXQuery)
            If oXMLLocalOl_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
            Set oXMLDel_Node = oXMLLocalOl_Root.removeChild(oXMLLocalOl_Node)
            
            sXQuery = "taskitems[@crmid='" & sCrmId & "']"
            Set oXMLLocalVt_Node = oXMLLocalVt_Root.selectSingleNode(sXQuery)
            If oXMLLocalVt_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
            Set oXMLDel_Node = oXMLLocalVt_Root.removeChild(oXMLLocalVt_Node)
            
            oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
            oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        End If
    End If
End If
bDelVtTasks = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    bDelVtTasks = False
    'sMsgDlg ("bDelVtTasks" & Err.Description)
    LogTheMessage "bDelVtTasks - " & Err.Description
EXIT_ROUTINE:
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_Node = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_Node = Nothing
Set oXMLDel_Node = Nothing
End Function
