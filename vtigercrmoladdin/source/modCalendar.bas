Attribute VB_Name = "modCalendar"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit
Public Function sGetOlCalendars() As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlFolder As Outlook.MAPIFolder
Dim oOlItems As Outlook.Items
Dim oOlAppt As Outlook.AppointmentItem
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmnt_Root As MSXML.IXMLDOMElement
Dim oXMLElmnt_First As MSXML.IXMLDOMElement
Dim oXMLNode As MSXML.IXMLDOMNode
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction
Dim sStartDate As String
Dim sDueDate As String

Set oOlFolder = oOlApp.ActiveExplorer.CurrentFolder

Set oOlItems = oOlFolder.Items
Set oOlItems = oOlItems.Restrict("[MessageClass] = 'IPM.Appointment'")

Set oXMLInst = oXMLDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
oXMLDoc.insertBefore oXMLInst, oXMLDoc.firstChild

Set oXMLElmnt_Root = oXMLDoc.createElement("outlook")
Set oXMLDoc.documentElement = oXMLElmnt_Root

If oOlItems.Count > 0 Then

oOlItems.GetFirst

frmSync.PrgBarSync.Min = 0
frmSync.PrgBarSync.Max = oOlItems.Count
frmSync.PrgBarSync.Value = 0
frmSync.lblSynStatus.Caption = "Reading Appointments...."
DoEvents

For Each oOlAppt In oOlItems
    
    sStartDate = Format(oOlAppt.Start, "YYYY")
    sDueDate = Format(oOlAppt.End, "YYYY")
    
    If sStartDate <> "4501" Or sDueDate <> "4501" Then
    
        Set oXMLElmnt_First = oXMLDoc.createElement("calendaritems")
        Set oXMLNode = oXMLElmnt_Root.appendChild(oXMLElmnt_First)
        
        Call AddAttribute(oXMLElmnt_First, "entryid", oOlAppt.EntryID)
        
        sStartDate = Format(oOlAppt.Start, "YYYY")
        If sStartDate = "4501" Then
            sStartDate = ""
        Else
            sStartDate = Format(oOlAppt.Start, "YYYY-MM-DD HH:MM:SS")
        End If
        
        sDueDate = Format(oOlAppt.End, "YYYY")
        If sDueDate = "4501" Then
            sDueDate = ""
        Else
            sDueDate = Format(oOlAppt.End, "YYYY-MM-DD HH:MM:SS")
        End If
        
        Call AddChild(oXMLDoc, oXMLElmnt_First, "subject", EncodeUTF8(oOlAppt.Subject))
        Call AddChild(oXMLDoc, oXMLElmnt_First, "startdate", sStartDate)
        Call AddChild(oXMLDoc, oXMLElmnt_First, "duedate", sDueDate)
        Call AddChild(oXMLDoc, oXMLElmnt_First, "location", EncodeUTF8(oOlAppt.Location))
        Call AddChild(oXMLDoc, oXMLElmnt_First, "description", EncodeUTF8(oOlAppt.Body))
        Call AddChild(oXMLDoc, oXMLElmnt_First, "contactname", "")
        Call AddChild(oXMLDoc, oXMLElmnt_First, "category", EncodeUTF8(oOlAppt.Categories))
        
        frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
        
     End If
Next
End If
'MsgBox oXMLDoc.xml
sGetOlCalendars = oXMLDoc.xml
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("sGetOlCalendars" & Err.Description)
    LogTheMessage "sGetOlCalendars - " & Err.Description
    sGetOlCalendars = ""
EXIT_ROUTINE:
    Set oOlFolder = Nothing
    Set oOlItems = Nothing
    Set oOlAppt = Nothing
    Set oXMLDoc = Nothing
    Set oXMLElmnt_Root = Nothing
    Set oXMLElmnt_First = Nothing
    Set oXMLNode = Nothing
End Function

Public Function sGetvTigerCalendars() As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim oXMLVt_Doc As New MSXML.DOMDocument
Dim oXMLVt_RootElmnt As MSXML.IXMLDOMElement
Dim sVtCalendarsXML As String

frmSync.lblSynStatus.Caption = "Gettings Calendars...."
DoEvents

sVtCalendarsXML = svTigerSoGetClndr(gsVtUserId)
If sVtCalendarsXML <> "" Then
    If (oXMLVt_Doc.loadXML(sVtCalendarsXML) = True) Then
        Set oXMLVt_RootElmnt = oXMLVt_Doc.documentElement
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If
sGetvTigerCalendars = oXMLVt_Doc.xml
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("sGetvTigerCalendars" & Err.Description)
    LogTheMessage "sGetvTigerCalendars - " & Err.Description
    sGetvTigerCalendars = ""
EXIT_ROUTINE:
Set oXMLVt_Doc = Nothing
Set oXMLVt_RootElmnt = Nothing
End Function

Public Function sCreateOlClndr(ByVal oXMLVtElement As MSXML.IXMLDOMElement) As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlAppt As Outlook.AppointmentItem
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
     
    If sGetPathAsString(oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderCalendar)) <> gsClndrSyncFolder Then
        Set oOlFolder = oOlApp.GetNamespace("MAPI").GetFolderFromID(gsClndrSyncFolderId)
        Set oOlAppt = oOlFolder.Items.Add("IPM.Appointment")
    Else
        Set oOlAppt = oOlApp.ActiveExplorer.CurrentFolder.Items.Add("IPM.Appointment")
    End If
        
    oOlAppt.Subject = DecodeUTF8(oXMLVtElement.selectSingleNode("subject").nodeTypedValue)
    oOlAppt.Start = Format(DecodeUTF8(oXMLVtElement.selectSingleNode("startdate").nodeTypedValue), "YYYY-MM-DD HH:MM:SS")
    oOlAppt.End = Format(DecodeUTF8(oXMLVtElement.selectSingleNode("duedate").nodeTypedValue), "YYYY-MM-DD HH:MM:SS")
    oOlAppt.Location = DecodeUTF8(oXMLVtElement.selectSingleNode("location").nodeTypedValue)
    oOlAppt.Body = DecodeUTF8(oXMLVtElement.selectSingleNode("description").nodeTypedValue)
    
    oOlAppt.Save
      
'    If sGetPathAsString(oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderCalendar)) <> gsClndrSyncFolder Then
'        Set oOlFolder = oOlApp.GetNamespace("MAPI").GetFolderFromID(gsClndrSyncFolderId)
'        oOlAppt.Move oOlFolder
'    End If
    
    sCrmId = oXMLVtElement.getAttribute("crmid")
    
    If Not oXMLAppend_Doc.loadXML(oXMLVtElement.xml) Then GoTo ERROR_EXIT_ROUTINE
    Set oXMLAppend_Root = oXMLAppend_Doc.documentElement
    
    oXMLAppend_Root.removeAttribute ("crmid")
    oXMLAppend_Root.removeAttribute ("syncflag")
        
    Call AddAttribute(oXMLAppend_Root, "entryid", oOlAppt.EntryID)
    Call AddAttribute(oXMLAppend_Root, "syncflag", "NM")
    Set oXMLLocalOl_Node = oXMLLocalOl_Root.appendChild(oXMLAppend_Root)
    
    sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
    Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
    If oXMLLocalVt_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
    
    Call AddAttribute(oXMLLocalVt_First, "syncflag", "NM")
            
    sCreateOlClndr = oOlAppt.EntryID
        
    oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
    oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
    
    End If
End If

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sCreateOlClndr = ""
    'sMsgDlg ("sCreateOlClndr" & Err.Description)
    LogTheMessage "sCreateOlClndr - " & Err.Description
EXIT_ROUTINE:
Set oOlAppt = Nothing
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

Public Function bUpdateOlClndr(ByVal sEntryId As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlAppt As Outlook.AppointmentItem
Dim oOlNS As Outlook.Namespace
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

If sEntryId <> "" And sCrmId <> "" Then
    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
        Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
        Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
        
        Set oOlNS = oOlApp.GetNamespace("MAPI")
        Set oOlAppt = oOlNS.GetItemFromID(sEntryId)
                
        sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
        Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
        If oXMLLocalVt_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
                
        sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        If oOlAppt Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        oOlAppt.Subject = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("subject").nodeTypedValue)
        oOlAppt.Start = Format(DecodeUTF8(oXMLLocalVt_First.selectSingleNode("startdate").nodeTypedValue), "YYYY-MM-DD HH:MM:SS")
        oOlAppt.End = Format(DecodeUTF8(oXMLLocalVt_First.selectSingleNode("duedate").nodeTypedValue), "YYYY-MM-DD HH:MM:SS")
        oOlAppt.Location = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("location").nodeTypedValue)
        oOlAppt.Body = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("description").nodeTypedValue)
        
        oOlAppt.Categories = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("category").nodeTypedValue)

        oOlAppt.Save
              
        Call AddAttribute(oXMLLocalVt_First, "syncflag", "NM")
        
        If Not oXMLAppend_Doc.loadXML(oXMLLocalVt_First.xml) Then GoTo ERROR_EXIT_ROUTINE
        Set oXMLAppend_Root = oXMLAppend_Doc.documentElement
        
        oXMLAppend_Root.removeAttribute ("crmid")
        oXMLAppend_Root.removeAttribute ("syncflag")
        
        Call AddAttribute(oXMLAppend_Root, "entryid", sEntryId)
        Call AddAttribute(oXMLAppend_Root, "syncflag", "NM")
        Set oXMLAppend_Node = oXMLLocalOl_Root.replaceChild(oXMLAppend_Root, oXMLLocalOl_First)
                
        oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
        oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
    End If
End If
bUpdateOlClndr = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("bUpdateOlClndr" & Err.Description)
    LogTheMessage "bUpdateOlClndr - " & Err.Description
    bUpdateOlClndr = False
EXIT_ROUTINE:
Set oOlAppt = Nothing
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

Public Function bDelOlClndr(ByVal sEntryId As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlAppt As Outlook.AppointmentItem
Dim oOlNS As Outlook.Namespace
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


If sEntryId <> "" And sCrmId <> "" Then
    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
        Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
        Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
        
        Set oOlNS = oOlApp.GetNamespace("MAPI")
        Set oOlAppt = oOlNS.GetItemFromID(sEntryId)
        If Not oOlAppt Is Nothing Then
            oOlAppt.Delete
        End If
        
        sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
        Set oXMLLocalOl_Node = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        Set oXMLDel_Node = oXMLLocalOl_Root.removeChild(oXMLLocalOl_Node)
        
        sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
        Set oXMLLocalVt_Node = oXMLLocalVt_Root.selectSingleNode(sXQuery)
        If oXMLLocalVt_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        Set oXMLDel_Node = oXMLLocalVt_Root.removeChild(oXMLLocalVt_Node)
        
        oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
        oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        
    End If
End If
bDelOlClndr = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    bDelOlClndr = False
    'sMsgDlg ("bDelOlClndr" & Err.Description)
    LogTheMessage "bDelOlClndr - " & Err.Description
EXIT_ROUTINE:
Set oOlAppt = Nothing
Set oOlNS = Nothing
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_Node = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_Node = Nothing
Set oXMLDel_Node = Nothing
End Function

Public Function sCreateVtClndr(ByVal oXMLOlElement As MSXML.IXMLDOMElement) As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim sCrmId As String
Dim bOlFlag As Boolean
Dim bVtFlag As Boolean
Dim sEntryId As String
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
        
        sCrmId = sVtSoNewClndr(gsVtUserId, oXMLOlElement)
        sEntryId = oXMLOlElement.getAttribute("entryid")
                
        If Not oXMLAppend_Doc.loadXML(oXMLOlElement.xml) Then GoTo ERROR_EXIT_ROUTINE
               
        Set oXMLAppend_Root = oXMLAppend_Doc.documentElement
        
        oXMLAppend_Root.removeAttribute ("entryid")
        oXMLAppend_Root.removeAttribute ("syncflag")
        
        Call AddAttribute(oXMLAppend_Root, "crmid", sCrmId)
        Call AddAttribute(oXMLAppend_Root, "syncflag", "NM")
        Set oXMLLocalVt_Node = oXMLLocalVt_Root.appendChild(oXMLAppend_Root)
        
        sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        Call AddAttribute(oXMLLocalOl_First, "syncflag", "NM")
        
        sCreateVtClndr = sCrmId
        
        oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
        oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        
    End If
End If
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sCreateVtClndr = ""
    'sMsgDlg ("sCreateVtClndr" & Err.Description)
    LogTheMessage "sCreateVtClndr - " & Err.Description
EXIT_ROUTINE:
Set oXMLOlElement = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_Node = Nothing
Set oXMLAppend_Doc = Nothing
Set oXMLAppend_Root = Nothing
End Function

Public Function bUpdateVtClndr(ByVal sEntryId As String, ByVal sCrmId As String) As Boolean
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


If sEntryId <> "" And sCrmId <> "" Then
    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
        Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
        Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
                       
        sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
        Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
        If oXMLLocalVt_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
                
        sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        'Call Soap Method for Update
        If sVtSoUpdateClndr(sCrmId, oXMLLocalOl_First) <> "" Then
        
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
bUpdateVtClndr = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("bUpdateVtClndr" & Err.Description)
    LogTheMessage "bUpdateVtClndr - " & Err.Description
    bUpdateVtClndr = False
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

Public Function bDelVtClndr(ByVal sEntryId As String, ByVal sCrmId As String) As Boolean
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


If sEntryId <> "" And sCrmId <> "" Then
    bOlFlag = oXMLLocalOl_Doc.Load(gsVtUserFolder & LOCAL_OL_FILE)
    bVtFlag = oXMLLocalVt_Doc.Load(gsVtUserFolder & LOCAL_VTIGER_FILE)
    
    If bOlFlag = True And bVtFlag = True Then
        Set oXMLLocalOl_Root = oXMLLocalOl_Doc.documentElement
        Set oXMLLocalVt_Root = oXMLLocalVt_Doc.documentElement
        
        'Call Soap Method to Delete
        If bVtSoDeleteClndr(gsVtUserId, sCrmId) = True Then
            sXQuery = "calendaritems[@entryid='" & sEntryId & "']"
            Set oXMLLocalOl_Node = oXMLLocalOl_Root.selectSingleNode(sXQuery)
            If oXMLLocalOl_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
            Set oXMLDel_Node = oXMLLocalOl_Root.removeChild(oXMLLocalOl_Node)
            
            sXQuery = "calendaritems[@crmid='" & sCrmId & "']"
            Set oXMLLocalVt_Node = oXMLLocalVt_Root.selectSingleNode(sXQuery)
            If oXMLLocalVt_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
            Set oXMLDel_Node = oXMLLocalVt_Root.removeChild(oXMLLocalVt_Node)
            
            oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
            oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        End If
    End If
End If
bDelVtClndr = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    bDelVtClndr = False
    'sMsgDlg ("bDelVtClndr" & Err.Description)
    LogTheMessage "bDelVtClndr - " & Err.Description
EXIT_ROUTINE:
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_Node = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_Node = Nothing
Set oXMLDel_Node = Nothing
End Function

