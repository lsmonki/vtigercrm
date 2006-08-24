VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "clsExplWrap"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit

Private gBaseClass As New clsOlkAddIn
Private WithEvents m_objExpl As Outlook.Explorer
Attribute m_objExpl.VB_VarHelpID = -1
Private m_nID As Integer

Private Sub Class_Initialize()
    Set m_objExpl = Nothing
End Sub
Private Sub Class_Terminate()
    Set m_objExpl = Nothing
End Sub
Public Property Let Explorer(objExpl As Outlook.Explorer)
    Set m_objExpl = objExpl
End Property
Public Property Get Explorer() As Outlook.Explorer
    Set Explorer = m_objExpl
End Property
Public Property Let Key(anID As Integer)
    m_nID = anID
End Property
Private Sub m_objExpl_Activate()
    On Error Resume Next
    Call RefreshToolbar(m_objExpl.CurrentFolder)
End Sub
Private Sub m_objExpl_BeforeFolderSwitch(ByVal NewFolder As Object, Cancel As Boolean)
    On Error Resume Next
    Call RefreshToolbar(NewFolder)
End Sub
Private Sub m_objExpl_Close()
    On Error Resume Next
    modvtigerUtils.KillExpl m_nID, Me
    Set m_objExpl = Nothing
    If golApp.Explorers.Count <= 1 Then
        gBaseClass.UnInitHandler
    End If
End Sub
Private Sub m_objExpl_SelectionChange()
    On Error Resume Next
    If gblnNewExpl Then
        Call RefreshToolbar(m_objExpl.CurrentFolder)
        gblnNewExpl = False
    End If
End Sub
Public Sub RefreshToolbar(objFolder As Outlook.MAPIFolder)
    On Error Resume Next
    
    Dim objCmdBar As Office.CommandBar
    Dim objEmailButton As Office.CommandBarButton
    Dim objEmailMenu As Office.CommandBarButton
    
    Dim objSCButton As Office.CommandBarButton
    Dim objSCMenu As Office.CommandBarButton
    
    Dim objSyncTaskButton As Office.CommandBarButton
    Dim objSyncTaskMenu As Office.CommandBarButton
    
    Dim objSyncClndrButton As Office.CommandBarButton
    Dim objSyncClndrMenu As Office.CommandBarButton
    
    Dim objPrefMenu As Office.CommandBarButton
    Dim objLoginMenu As Office.CommandBarButton
    Dim cmbControl As Office.CommandBarPopup
    
    Dim i As Integer
    
    Set objCmdBar = m_objExpl.CommandBars("vtigerCRM")
    
    Set objEmailButton = objCmdBar.FindControl(Tag:="AddEmailsButton")
    Set objSCButton = objCmdBar.FindControl(Tag:="SyncContactsButton")
    Set objSyncTaskButton = objCmdBar.FindControl(Tag:="SyncTasksButton")
    Set objSyncClndrButton = objCmdBar.FindControl(Tag:="SyncCalendarButton")
    
    Set cmbControl = objCmdBar.FindControl(Type:=msoControlPopup, Tag:="vtigerCRMMenu")
    If Not cmbControl Is Nothing Then
        For i = 1 To cmbControl.Controls.Count
            If cmbControl.Controls.Item(i).Tag = "AddToVtigerCRM" Then
                Set objEmailMenu = cmbControl.Controls.Item(i)
            End If
            If cmbControl.Controls.Item(i).Tag = "SyncCntsMenu" Then
                Set objSCMenu = cmbControl.Controls.Item(i)
            End If
            If cmbControl.Controls.Item(i).Tag = "SyncTaskMenu" Then
                Set objSyncTaskMenu = cmbControl.Controls.Item(i)
            End If
            If cmbControl.Controls.Item(i).Tag = "SyncClndrMenu" Then
                Set objSyncClndrMenu = cmbControl.Controls.Item(i)
            End If
            If cmbControl.Controls.Item(i).Tag = "Pref" Then
                Set objPrefMenu = cmbControl.Controls.Item(i)
            End If
            If cmbControl.Controls.Item(i).Tag = "Login" Then
                Set objLoginMenu = cmbControl.Controls.Item(i)
            End If
        Next i
    End If
       
    If objFolder Is Nothing Then
        objEmailButton.Enabled = False
        objEmailMenu.Enabled = False
        objSCButton.Enabled = False
        objSCMenu.Enabled = False
        objSyncTaskButton.Enabled = False
        objSyncTaskMenu.Enabled = False
        objSyncClndrButton.Enabled = False
        objSyncClndrMenu.Enabled = False
        objLoginMenu.Enabled = True
        objPrefMenu.Enabled = False
        Exit Sub
    Else
        If gsLoginSuccess = True Then
            Dim oPSoap As New PocketSOAP.CoEnvelope
            Dim oXMLHttp As New MSXML.XMLHTTPRequest
            Dim oXMLDoc As New MSXML.DOMDocument
            Dim oXMLDocElmnt As MSXML.IXMLDOMElement
                
            If objFolder.DefaultItemType = olMailItem And Trim(sGetPathAsString(objFolder)) <> "Personal Folders" Then
                'Added to integrate Security for Emails
                oPSoap.MethodName = "CheckEmailPermission"

                oPSoap.Parameters.Create "username", gsVtUserId

                oXMLHttp.Open "POST", gsVtUrl, False
                oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
                oXMLHttp.setRequestHeader "Content-Type", "text/xml"
                oXMLHttp.send oPSoap.Serialize
                oXMLDoc.loadXML (oXMLHttp.responseText)
                Set oXMLDocElmnt = oXMLDoc.documentElement
                If oXMLDocElmnt.selectSingleNode("//ns1:CheckEmailPermissionResponse/return").nodeTypedValue <> "denied" Then
                    objEmailButton.Enabled = True
                    objEmailMenu.Enabled = True
                Else
                    objEmailButton.Enabled = False
                    objEmailMenu.Enabled = False
                End If

                objSCButton.Enabled = False
                objSCMenu.Enabled = False
                objSyncTaskButton.Enabled = False
                objSyncTaskMenu.Enabled = False
                objSyncClndrButton.Enabled = False
                objSyncClndrMenu.Enabled = False
            ElseIf Trim(sGetPathAsString(objFolder)) = Trim(gsCntsSyncFolder) Then
                gsCntsSyncFolderId = objFolder.EntryID
                
                'Added to integrate Security for Contacts
                oPSoap.MethodName = "CheckContactPermission"

                oPSoap.Parameters.Create "username", gsVtUserId

                oXMLHttp.Open "POST", gsVtUrl, False
                oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
                oXMLHttp.setRequestHeader "Content-Type", "text/xml"
                oXMLHttp.send oPSoap.Serialize
                oXMLDoc.loadXML (oXMLHttp.responseText)
                Set oXMLDocElmnt = oXMLDoc.documentElement
                If oXMLDocElmnt.selectSingleNode("//ns1:CheckContactPermissionResponse/return").nodeTypedValue <> "denied" Then
                    objSCButton.Enabled = True
                    objSCMenu.Enabled = True
                Else
                    objSCButton.Enabled = False
                    objSCMenu.Enabled = False
                End If
                
                
                objEmailButton.Enabled = False
                objEmailMenu.Enabled = False
                objSyncTaskButton.Enabled = False
                objSyncTaskMenu.Enabled = False
                objSyncClndrButton.Enabled = False
                objSyncClndrMenu.Enabled = False
            ElseIf Trim(sGetPathAsString(objFolder)) = Trim(gsTaskSyncFolder) Then
                gsTaskSyncFolderId = objFolder.EntryID
                
                'Added to integrate Security for Activity
                oPSoap.MethodName = "CheckActivityPermission"

                oPSoap.Parameters.Create "username", gsVtUserId

                oXMLHttp.Open "POST", gsVtUrl, False
                oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
                oXMLHttp.setRequestHeader "Content-Type", "text/xml"
                oXMLHttp.send oPSoap.Serialize
                oXMLDoc.loadXML (oXMLHttp.responseText)
                Set oXMLDocElmnt = oXMLDoc.documentElement
                If oXMLDocElmnt.selectSingleNode("//ns1:CheckActivityPermissionResponse/return").nodeTypedValue <> "denied" Then
                    objSyncTaskButton.Enabled = True
                    objSyncTaskMenu.Enabled = True
                Else
                    objSyncTaskButton.Enabled = False
                    objSyncTaskMenu.Enabled = False
                End If
                objEmailButton.Enabled = False
                objEmailMenu.Enabled = False
                objSCButton.Enabled = False
                objSCMenu.Enabled = False
                objSyncClndrButton.Enabled = False
                objSyncClndrMenu.Enabled = False
            ElseIf Trim(sGetPathAsString(objFolder)) = Trim(gsClndrSyncFolder) Then
                gsClndrSyncFolderId = objFolder.EntryID
                
                'Added to integrate Security for Calendar
                oPSoap.MethodName = "CheckActivityPermission"

                oPSoap.Parameters.Create "username", gsVtUserId

                oXMLHttp.Open "POST", gsVtUrl, False
                oXMLHttp.setRequestHeader "SOAPAction", "vtigerolservice"
                oXMLHttp.setRequestHeader "Content-Type", "text/xml"
                oXMLHttp.send oPSoap.Serialize
                oXMLDoc.loadXML (oXMLHttp.responseText)
                Set oXMLDocElmnt = oXMLDoc.documentElement
                If oXMLDocElmnt.selectSingleNode("//ns1:CheckActivityPermissionResponse/return").nodeTypedValue <> "denied" Then
                    objSyncClndrButton.Enabled = True
                    objSyncClndrMenu.Enabled = True
                Else
                    objSyncClndrButton.Enabled = False
                    objSyncClndrMenu.Enabled = False
                End If
                
                objEmailButton.Enabled = False
                objEmailMenu.Enabled = False
                objSCButton.Enabled = False
                objSCMenu.Enabled = False
                objSyncTaskButton.Enabled = False
                objSyncTaskMenu.Enabled = False
            Else
                objEmailButton.Enabled = False
                objEmailMenu.Enabled = False
                objSCButton.Enabled = False
                objSCMenu.Enabled = False
                objSyncTaskButton.Enabled = False
                objSyncTaskMenu.Enabled = False
                objSyncClndrButton.Enabled = False
                objSyncClndrMenu.Enabled = False
            End If
            'objLoginMenu.Enabled = False
            objPrefMenu.Enabled = True
        End If
    End If
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'MsgBox Err.Description
EXIT_ROUTINE:
    Set objCmdBar = Nothing
    Set cmbControl = Nothing
    Set objEmailMenu = Nothing
    Set objEmailButton = Nothing
    Set objSCButton = Nothing
    Set objSCMenu = Nothing
    Set objPrefMenu = Nothing
    Set objLoginMenu = Nothing
    Set objSyncTaskButton = Nothing
    Set objSyncTaskMenu = Nothing
    Set objSyncClndrButton = Nothing
    Set objSyncClndrMenu = Nothing
End Sub
