Attribute VB_Name = "modSync"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit

Public gsSyncModule As String

Public gsMappingSyncXML As String
Public gsLocalOlSyncXML As String
Public gsLocalVtSyncXML As String

Public Const LOCAL_OL_FILE = "\oldata.xml"
Public Const LOCAL_VTIGER_FILE = "\vtigerdata.xml"
Public Const MAPPING_VTIGER_OL = "\mapolvtiger.xml"
Public Function SyncMain(ByVal sSyncModule As String)
On Error GoTo ERROR_EXIT_ROUTINE

If sSyncModule <> "" Then
    Select Case (sSyncModule)
        Case "CONTACTSYNC":
             If Not ContactSyncMain() Then GoTo ERROR_EXIT_ROUTINE
             If Not bSyncMapping(gsLocalOlSyncXML, gsLocalVtSyncXML) Then GoTo ERROR_EXIT_ROUTINE
        Case "TASKSYNC":
             If Not TaskSyncMain() Then GoTo ERROR_EXIT_ROUTINE
             If Not bSyncMapping(gsLocalOlSyncXML, gsLocalVtSyncXML) Then GoTo ERROR_EXIT_ROUTINE
        Case "CALENDARSYNC":
             If Not CalendarSyncMain() Then GoTo ERROR_EXIT_ROUTINE
             If Not bSyncMapping(gsLocalOlSyncXML, gsLocalVtSyncXML) Then GoTo ERROR_EXIT_ROUTINE
    End Select
    
End If
Load frmSyncStatus
frmSyncStatus.sStatusFlag = False
frmSyncStatus.Show vbModal
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    LogTheMessage (Err.Description)
EXIT_ROUTINE:
End Function

Public Function ContactSyncMain() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sOlCntsXML As String
Dim sUpdatedOLXML As String
Dim sUpdatedVtXML As String
Dim sVtCntsXML As String
Dim sErrMsg As String
Dim oFS As New Scripting.FileSystemObject
    
    If (oFS.FileExists(gsVtUserFolder & LOCAL_OL_FILE) = True) Then
        
        sOlCntsXML = sGetOlContacts()
        sErrMsg = "Error while getting contact details from outlook"
        If sOlCntsXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
        sUpdatedOLXML = sCheckOlUpdateContacts(sOlCntsXML)
        sErrMsg = "Error while preparing outlook contact details for sync"
        If sUpdatedOLXML = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedOLXML = sCheckOlDeleteContacts(sUpdatedOLXML, sOlCntsXML)
        sErrMsg = "Error while preparing outlook contact details for sync"
        If sUpdatedOLXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
    Else
        sOlCntsXML = sGetOlContacts()
        sErrMsg = "Error while getting contact details from outlook"
        If sOlCntsXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
        sUpdatedOLXML = sCheckOlNewContacts(sOlCntsXML)
        sErrMsg = "Error while preparing outlook contact details for sync"
        If sUpdatedOLXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
    End If
    
    If (oFS.FileExists(gsVtUserFolder & LOCAL_VTIGER_FILE) = True) Then
        
        sErrMsg = ""
        sVtCntsXML = sGetvTigerContacts()
        If sVtCntsXML = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedVtXML = sCheckVtUpdateContacts(sVtCntsXML)
        sErrMsg = "Error while preparing vtigerCRM contact details for sync"
        If sUpdatedVtXML = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedVtXML = sCheckVtDeleteContacts(sUpdatedVtXML, sVtCntsXML)
        sErrMsg = "Error while preparing vtigerCRM contact details for sync"
        If sUpdatedVtXML = "" Then GoTo ERROR_EXIT_ROUTINE
    Else
        sVtCntsXML = sGetvTigerContacts()
        sErrMsg = ""
        If sVtCntsXML = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedVtXML = sCheckVtNewContacts(sVtCntsXML)
        sErrMsg = "Error while preparing vtigerCRM contact details for sync"
        If sUpdatedVtXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
    End If

gsLocalOlSyncXML = sUpdatedOLXML
gsLocalVtSyncXML = sUpdatedVtXML

ContactSyncMain = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    gsLocalOlSyncXML = ""
    gsLocalVtSyncXML = ""
    If sErrMsg <> "" Then
        sMsgDlg (sErrMsg)
    End If
    ContactSyncMain = False
EXIT_ROUTINE:
    Set oFS = Nothing
End Function

Public Function TaskSyncMain() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sOlTaskXML As String
Dim sUpdatedOLXML As String
Dim sUpdatedVtXML As String
Dim sVtTaskXml As String
Dim sErrMsg As String

Dim oFS As New Scripting.FileSystemObject
    
    If (oFS.FileExists(gsVtUserFolder & LOCAL_OL_FILE) = True) Then
    
        sOlTaskXML = sGetOlTasks()
        sErrMsg = "Error while getting task details from outlook"
        If sOlTaskXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
        sUpdatedOLXML = sCheckOlUpdateTasks(sOlTaskXML)
        sErrMsg = "Error while preparing outlook task details for sync"
        If sUpdatedOLXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
        sUpdatedOLXML = sCheckOlDeleteTasks(sUpdatedOLXML, sOlTaskXML)
        sErrMsg = "Error while preparing outlook task details for sync"
        If sUpdatedOLXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
    Else
        sOlTaskXML = sGetOlTasks()
        sErrMsg = "Error while getting task details from outlook"
        If sOlTaskXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
        sUpdatedOLXML = sCheckOlNewTasks(sOlTaskXML)
        sErrMsg = "Error while preparing outlook task details for sync"
        If sUpdatedOLXML = "" Then GoTo ERROR_EXIT_ROUTINE
    End If
    
    If (oFS.FileExists(gsVtUserFolder & LOCAL_VTIGER_FILE) = True) Then
        
        sVtTaskXml = sGetvTigerTasks()
        sErrMsg = ""
        If sVtTaskXml = "" Then GoTo ERROR_EXIT_ROUTINE
        
        sUpdatedVtXML = sCheckVtUpdateTasks(sVtTaskXml)
        sErrMsg = "Error while preparing vtigerCRM task details for sync"
        If sUpdatedVtXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
        sUpdatedVtXML = sCheckVtDeleteTasks(sUpdatedVtXML, sVtTaskXml)
        sErrMsg = "Error while preparing vtigerCRM task details for sync"
        If sUpdatedVtXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
    Else
        
        sVtTaskXml = sGetvTigerTasks()
        sErrMsg = ""
        If sVtTaskXml = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedVtXML = sCheckVtNewTasks(sVtTaskXml)
        sErrMsg = "Error while preparing vtigerCRM task details for sync"
        If sUpdatedVtXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
    End If

gsLocalOlSyncXML = sUpdatedOLXML
gsLocalVtSyncXML = sUpdatedVtXML
TaskSyncMain = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    gsLocalOlSyncXML = ""
    gsLocalVtSyncXML = ""
    If sErrMsg <> "" Then
        sMsgDlg (sErrMsg)
    End If
    TaskSyncMain = False
EXIT_ROUTINE:
    Set oFS = Nothing
End Function

Public Function CalendarSyncMain() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE
Dim sOlCalendarXML As String
Dim sUpdatedOLXML As String
Dim sUpdatedVtXML As String
Dim sVtCalendarXml As String
Dim sErrMsg As String

Dim oFS As New Scripting.FileSystemObject
    
    If (oFS.FileExists(gsVtUserFolder & LOCAL_OL_FILE) = True) Then
        sOlCalendarXML = sGetOlCalendars()
        sErrMsg = "Error while getting appointment details from outlook"
        If sOlCalendarXML = "" Then GoTo ERROR_EXIT_ROUTINE
        
        sUpdatedOLXML = sCheckOlUpdateCalendars(sOlCalendarXML)
        sErrMsg = "Error while preparing outlook appointment details for sync"
        If sUpdatedOLXML = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedOLXML = sCheckOlDeleteCalendars(sUpdatedOLXML, sOlCalendarXML)
        sErrMsg = "Error while preparing outlook appointment details for sync"
        If sUpdatedOLXML = "" Then GoTo ERROR_EXIT_ROUTINE

    Else
        sOlCalendarXML = sGetOlCalendars()
        sErrMsg = "Error while getting appointment details from outlook"
        If sOlCalendarXML = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedOLXML = sCheckOlNewCalendars(sOlCalendarXML)
        sErrMsg = "Error while preparing outlook appointment details for sync"
        If sUpdatedOLXML = "" Then GoTo ERROR_EXIT_ROUTINE
    End If
    
    If (oFS.FileExists(gsVtUserFolder & LOCAL_VTIGER_FILE) = True) Then
        sVtCalendarXml = sGetvTigerCalendars()
        sErrMsg = ""
        If sVtCalendarXml = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedVtXML = sCheckVtUpdateCalendars(sVtCalendarXml)
        sErrMsg = "Error while preparing vtigerCRM appointment details for sync"
        If sUpdatedVtXML = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedVtXML = sCheckVtDeleteCalendars(sUpdatedVtXML, sVtCalendarXml)
        sErrMsg = "Error while preparing vtigerCRM appointment details for sync"
        If sUpdatedVtXML = "" Then GoTo ERROR_EXIT_ROUTINE

    Else
        sVtCalendarXml = sGetvTigerCalendars()
        sErrMsg = ""
        If sVtCalendarXml = "" Then GoTo ERROR_EXIT_ROUTINE

        sUpdatedVtXML = sCheckVtNewCalendars(sVtCalendarXml)
        sErrMsg = "Error while preparing vtigerCRM appointment details for sync"
        If sUpdatedVtXML = "" Then GoTo ERROR_EXIT_ROUTINE
    End If

gsLocalOlSyncXML = sUpdatedOLXML
gsLocalVtSyncXML = sUpdatedVtXML

CalendarSyncMain = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
    gsLocalOlSyncXML = ""
    gsLocalVtSyncXML = ""
    CalendarSyncMain = False
    If sErrMsg <> "" Then
        sMsgDlg (sErrMsg)
    End If
EXIT_ROUTINE:
    Set oFS = Nothing
End Function
