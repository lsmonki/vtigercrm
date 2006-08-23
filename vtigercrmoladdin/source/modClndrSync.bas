Attribute VB_Name = "modCalendarSync"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit
Public Function sCheckOlNewCalendars(ByVal sClndrXMLStr As String) As String

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

If (oXMLOlDoc.loadXML(sClndrXMLStr) = True) Then
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
       ''sCheckOlNewCalendars = oXMLLocalDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

sCheckOlNewCalendars = oXMLLocalDoc.xml
'bCheckOlNewCalendars = True
GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckOlNewCalendars" & Err.Description
    LogTheMessage "sCheckOlNewCalendars - " & Err.Description
    sCheckOlNewCalendars = ""
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

Public Function sCheckOlUpdateCalendars(ByVal sClndrXMLStr As String) As String

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

bOlFlag = oXMLOlDoc.loadXML(sClndrXMLStr)
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
        
       For i = 0 To oXMLOl_Root.selectNodes("calendaritems").Length - 1
            
            Set oXMLOl_First = oXMLOl_Root.selectNodes("calendaritems").Item(i)
            sOlEntryId = oXMLOl_First.getAttribute("entryid")
            
            If sOlEntryId <> "" Then
                
                sXQString = "calendaritems[@entryid='" & sOlEntryId & "']"
                Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQString)
                
                If Not oXMLLocalOl_First Is Nothing Then
                    If Not oXMLAppendDoc.loadXML(oXMLOl_First.xml) Then GoTo ERROR_EXIT_ROUTINE
                    
                    If (bOlUpdateCalendarCheck(oXMLOl_First, oXMLLocalOl_First) = True) Then
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
       ''sCheckOlUpdateCalendars = oXMLLocalOlDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

'sCheckOlUpdateCalendars = True
sCheckOlUpdateCalendars = oXMLLocalOlDoc.xml
GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckOlUpdateCalendars" & Err.Description & Err.Source
    LogTheMessage "sCheckOlUpdateCalendars - " & Err.Description
    sCheckOlUpdateCalendars = ""
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
Public Function bOlUpdateCalendarCheck(ByVal oXMLOl_Elmnt As MSXML.IXMLDOMElement, ByVal oXMLLocal_Elmnt As MSXML.IXMLDOMElement) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sModifiedFlag As Boolean

    If ((Not oXMLOl_Elmnt Is Nothing) And (Not oXMLLocal_Elmnt Is Nothing)) Then
        
        If oXMLOl_Elmnt.selectSingleNode("subject").Text <> oXMLLocal_Elmnt.selectSingleNode("subject").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLOl_Elmnt.selectSingleNode("startdate").Text <> oXMLLocal_Elmnt.selectSingleNode("startdate").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLOl_Elmnt.selectSingleNode("duedate").Text <> oXMLLocal_Elmnt.selectSingleNode("duedate").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLOl_Elmnt.selectSingleNode("location").Text <> oXMLLocal_Elmnt.selectSingleNode("location").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLOl_Elmnt.selectSingleNode("description").Text <> oXMLLocal_Elmnt.selectSingleNode("description").Text Then GoTo ERROR_EXIT_ROUTINE
        
    End If
    
bOlUpdateCalendarCheck = False
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("bOlUpdateCalendarCheck" & Err.Description)
    bOlUpdateCalendarCheck = True
EXIT_ROUTINE:
Set oXMLOl_Elmnt = Nothing
Set oXMLLocal_Elmnt = Nothing
End Function

Public Function sCheckOlDeleteCalendars(ByVal sUpdatedLocalOlStr As String, ByVal sClndrXMLStr As String) As String

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

bOlFlag = oXMLOlDoc.loadXML(sClndrXMLStr)
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
       
       For i = 0 To oXMLLocal_Root.selectNodes("calendaritems").Length - 1
            
            Set oXMLLocal_First = oXMLLocal_Root.selectNodes("calendaritems").Item(i)
            sLocalEntryId = oXMLLocal_First.getAttribute("entryid") & vbNullString
            
            If sLocalEntryId <> "" Then
            
                sXQString = "calendaritems[@entryid='" & sLocalEntryId & "']"
                Set oXMLOl_First = oXMLOl_Root.selectSingleNode(sXQString)
                
                If oXMLOl_First Is Nothing Then
                    Call AddAttribute(oXMLLocal_First, "syncflag", "D")
                End If
                
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
       Next i
       ''sCheckOlDeleteCalendars = oXMLLocalDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If
sCheckOlDeleteCalendars = oXMLLocalDoc.xml
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckOlDeleteCalendars" & Err.Description
    LogTheMessage "sCheckOlDeleteCalendars - " & Err.Description
    sCheckOlDeleteCalendars = ""
EXIT_ROUTINE:
    Set oXMLOlDoc = Nothing
    Set oXMLOl_Root = Nothing
    Set oXMLOl_First = Nothing
    Set oXMLLocalDoc = Nothing
    Set oXMLLocal_Root = Nothing
    Set oXMLLocal_First = Nothing
End Function
Public Function sCheckVtNewCalendars(ByVal sVtClndrXMLStr As String) As String

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
       
If (oXMLVtDoc.loadXML(sVtClndrXMLStr) = True) Then
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
       ''sCheckVtNewCalendars = oXMLLocalDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

sCheckVtNewCalendars = oXMLLocalDoc.xml
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    LogTheMessage "sCheckVtNewCalendars - " & Err.Description
    'MsgBox "sCheckVtNewCalendars" & Err.Description
    sCheckVtNewCalendars = ""
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
Public Function sCheckVtUpdateCalendars(ByVal sClndrXMLStr As String) As String

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

bVtFlag = oXMLVtDoc.loadXML(sClndrXMLStr)
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
        
       For i = 0 To oXMLVt_Root.selectNodes("calendaritems").Length - 1
            
            Set oXMLVt_First = oXMLVt_Root.selectNodes("calendaritems").Item(i)
            sVtCrmId = oXMLVt_First.getAttribute("crmid")
            
            If sVtCrmId <> "" Then
                
                sXQString = "calendaritems[@crmid='" & sVtCrmId & "']"
                Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQString)
                
                If Not oXMLLocalVt_First Is Nothing Then
                    If Not oXMLAppendDoc.loadXML(oXMLVt_First.xml) Then GoTo ERROR_EXIT_ROUTINE
                    
                    If (bVtUpdateCalendarCheck(oXMLVt_First, oXMLLocalVt_First) = True) Then
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
       ''sCheckVtUpdateCalendars = oXMLLocalVtDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

sCheckVtUpdateCalendars = oXMLLocalVtDoc.xml
GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckVtUpdateCalendars" & Err.Description
    LogTheMessage "sCheckVtUpdateCalendars - " & Err.Description
    sCheckVtUpdateCalendars = ""
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

Public Function sCheckVtDeleteCalendars(ByVal sUpdatedVtXML As String, ByVal sVtClndrXMLStr As String) As String

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

bVtFlag = oXMLVtDoc.loadXML(sVtClndrXMLStr)
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
        
       For i = 0 To oXMLLocal_Root.selectNodes("calendaritems").Length - 1
            
            Set oXMLLocal_First = oXMLLocal_Root.selectNodes("calendaritems").Item(i)
            sLocalCrmId = oXMLLocal_First.getAttribute("crmid") & vbNullString
            
            If sLocalCrmId <> "" Then
                sXQString = "calendaritems[@crmid='" & sLocalCrmId & "']"
                                
                Set oXMLVt_First = oXMLVt_Root.selectSingleNode(sXQString)
                
                If oXMLVt_First Is Nothing Then
                    Call AddAttribute(oXMLLocal_First, "syncflag", "D")
                End If
                
            End If
            frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
       Next i
       ''sCheckVtDeleteCalendars = oXMLLocalDoc.xml
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If

sCheckVtDeleteCalendars = oXMLLocalDoc.xml
GoTo EXIT_ROUTINE
    
ERROR_EXIT_ROUTINE:
    'MsgBox "sCheckVtDeleteCalendars" & Err.Description
    LogTheMessage "sCheckVtDeleteCalendars - " & Err.Description
    sCheckVtDeleteCalendars = ""
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
Public Function bVtUpdateCalendarCheck(ByVal oXMLVt_Elmnt As MSXML.IXMLDOMElement, ByVal oXMLLocal_Elmnt As MSXML.IXMLDOMElement) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sModifiedFlag As Boolean

    If ((Not oXMLVt_Elmnt Is Nothing) And (Not oXMLLocal_Elmnt Is Nothing)) Then
    
        If oXMLVt_Elmnt.selectSingleNode("subject").Text <> oXMLLocal_Elmnt.selectSingleNode("subject").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("startdate").Text <> oXMLLocal_Elmnt.selectSingleNode("startdate").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("duedate").Text <> oXMLLocal_Elmnt.selectSingleNode("duedate").Text Then GoTo ERROR_EXIT_ROUTINE
        
         If oXMLVt_Elmnt.selectSingleNode("location").Text <> oXMLLocal_Elmnt.selectSingleNode("location").Text Then GoTo ERROR_EXIT_ROUTINE
        
        If oXMLVt_Elmnt.selectSingleNode("description").Text <> oXMLLocal_Elmnt.selectSingleNode("description").Text Then GoTo ERROR_EXIT_ROUTINE
        
    End If
    
bVtUpdateCalendarCheck = False
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    bVtUpdateCalendarCheck = True
EXIT_ROUTINE:
Set oXMLVt_Elmnt = Nothing
Set oXMLLocal_Elmnt = Nothing
End Function
