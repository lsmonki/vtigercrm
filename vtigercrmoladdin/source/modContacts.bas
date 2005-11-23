Attribute VB_Name = "modContacts"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit
Public Function sGetOlContacts() As String

On Error GoTo ERROR_EXIT_ROUTINE

'Dim oOlApp As New Outlook.Application
Dim oOlFolder As Outlook.MAPIFolder
Dim oOlItems As Outlook.Items
Dim oOlContacts As Outlook.ContactItem
Dim oXMLDoc As New MSXML.DOMDocument
Dim oXMLElmnt_Root As MSXML.IXMLDOMElement
Dim oXMLElmnt_First As MSXML.IXMLDOMElement
Dim oXMLNode As MSXML.IXMLDOMNode
Dim oXMLInst As MSXML.IXMLDOMProcessingInstruction
Dim sBirthDate As String

Set oOlFolder = oOlApp.ActiveExplorer.CurrentFolder
Set oOlItems = oOlFolder.Items
Set oOlItems = oOlItems.Restrict("[MessageClass] = 'IPM.Contact'")

Set oXMLInst = oXMLDoc.createProcessingInstruction("xml", "version='1.0' encoding='UTF-8'")
oXMLDoc.insertBefore oXMLInst, oXMLDoc.firstChild

Set oXMLElmnt_Root = oXMLDoc.createElement("outlook")
Set oXMLDoc.documentElement = oXMLElmnt_Root

If oOlItems.Count > 0 Then

oOlItems.GetFirst

frmSync.PrgBarSync.Min = 0
frmSync.PrgBarSync.Max = oOlItems.Count
frmSync.PrgBarSync.Value = 0
frmSync.lblSynStatus.Caption = "Reading Contacts...."
DoEvents
For Each oOlContacts In oOlItems
    
    Set oXMLElmnt_First = oXMLDoc.createElement("contactitems")
    Set oXMLNode = oXMLElmnt_Root.appendChild(oXMLElmnt_First)
    
    Call AddAttribute(oXMLElmnt_First, "entryid", oOlContacts.EntryID)
    'MsgBox EncodeUTF8(oOlContacts.FirstName)
    Call AddChild(oXMLDoc, oXMLElmnt_First, "title", EncodeUTF8(oOlContacts.Title))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "firstname", EncodeUTF8(oOlContacts.FirstName))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "middlename", EncodeUTF8(oOlContacts.MiddleName))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "lastname", EncodeUTF8(oOlContacts.LastName))
    sBirthDate = Format(oOlContacts.Birthday, "YYYY")
    If sBirthDate = "4501" Then
        sBirthDate = ""
    Else
        sBirthDate = Format(oOlContacts.Birthday, "YYYY-MM-DD")
    End If
    Call AddChild(oXMLDoc, oXMLElmnt_First, "birthdate", sBirthDate)
    Call AddChild(oXMLDoc, oXMLElmnt_First, "emailaddress", EncodeUTF8(oOlContacts.Email1Address))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "jobtitle", EncodeUTF8(oOlContacts.JobTitle))
    
    Call AddChild(oXMLDoc, oXMLElmnt_First, "department", EncodeUTF8(oOlContacts.Department))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "accountname", EncodeUTF8(oOlContacts.CompanyName))
    
    Call AddChild(oXMLDoc, oXMLElmnt_First, "officephone", EncodeUTF8(oOlContacts.BusinessTelephoneNumber))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "homephone", EncodeUTF8(oOlContacts.HomeTelephoneNumber))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "otherphone", EncodeUTF8(oOlContacts.OtherTelephoneNumber))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "fax", EncodeUTF8(oOlContacts.BusinessFaxNumber))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "mobile", EncodeUTF8(oOlContacts.MobileTelephoneNumber))
    
    Call AddChild(oXMLDoc, oXMLElmnt_First, "asstname", EncodeUTF8(oOlContacts.AssistantName))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "asstphone", EncodeUTF8(oOlContacts.AssistantTelephoneNumber))
    
    Call AddChild(oXMLDoc, oXMLElmnt_First, "reportsto", EncodeUTF8(oOlContacts.ManagerName))
    
    Call AddChild(oXMLDoc, oXMLElmnt_First, "mailingstreet", EncodeUTF8(oOlContacts.BusinessAddressStreet))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "mailingcity", EncodeUTF8(oOlContacts.BusinessAddressCity))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "mailingstate", EncodeUTF8(oOlContacts.BusinessAddressState))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "mailingzip", EncodeUTF8(oOlContacts.BusinessAddressPostalCode))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "mailingcountry", EncodeUTF8(oOlContacts.BusinessAddressCountry))
    
    Call AddChild(oXMLDoc, oXMLElmnt_First, "otherstreet", EncodeUTF8(oOlContacts.OtherAddressStreet))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "othercity", EncodeUTF8(oOlContacts.OtherAddressCity))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "otherstate", EncodeUTF8(oOlContacts.OtherAddressState))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "otherzip", EncodeUTF8(oOlContacts.OtherAddressPostalCode))
    Call AddChild(oXMLDoc, oXMLElmnt_First, "othercountry", EncodeUTF8(oOlContacts.OtherAddressCountry))
    
    Call AddChild(oXMLDoc, oXMLElmnt_First, "description", EncodeUTF8(oOlContacts.Body))
    
    'Not for Synchronization
    Call AddChild(oXMLDoc, oXMLElmnt_First, "category", EncodeUTF8(oOlContacts.Categories))
    
    frmSync.PrgBarSync.Value = frmSync.PrgBarSync.Value + 1
Next
End If
'oXMLDoc.Save (gsVtUserFolder & "\cnts.xml")
sGetOlContacts = oXMLDoc.xml
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("sGetOlContacts" & Err.Description)
    LogTheMessage ("sGetOlContacts - " & Err.Description)
    sGetOlContacts = ""
EXIT_ROUTINE:
'    Set oOlApp = Nothing
    Set oOlFolder = Nothing
    Set oOlItems = Nothing
    Set oOlContacts = Nothing
    Set oXMLDoc = Nothing
    Set oXMLElmnt_Root = Nothing
    Set oXMLElmnt_First = Nothing
    Set oXMLNode = Nothing
End Function
Public Function sGetvTigerContacts() As String
On Error GoTo ERROR_EXIT_ROUTINE
Dim oXMLVt_Doc As New MSXML.DOMDocument
Dim oXMLVt_RootElmnt As MSXML.IXMLDOMElement
Dim sVtContactsXML As String


frmSync.lblSynStatus.Caption = "Gettings Contacts...."

DoEvents
sVtContactsXML = svTigerSoGetContacts(gsVtUserId)

If sVtContactsXML <> "" Then
    If (oXMLVt_Doc.loadXML(sVtContactsXML) = True) Then
        Set oXMLVt_RootElmnt = oXMLVt_Doc.documentElement
    Else
        GoTo ERROR_EXIT_ROUTINE
    End If
Else
    GoTo ERROR_EXIT_ROUTINE
End If
sGetvTigerContacts = oXMLVt_Doc.xml
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("sGetvTigerContacts" & Err.Description)
    LogTheMessage ("sGetvTigerContacts - " & Err.Description)
    sGetvTigerContacts = ""
EXIT_ROUTINE:
Set oXMLVt_Doc = Nothing
Set oXMLVt_RootElmnt = Nothing
End Function
Public Function sCreateOlContacts(ByVal oXMLVtElement As MSXML.IXMLDOMElement) As String

On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlContact As Outlook.ContactItem
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
    
    If sGetPathAsString(oOlApp.GetNamespace("MAPI").GetDefaultFolder(olFolderContacts)) <> gsCntsSyncFolder Then
        Set oOlFolder = oOlApp.GetNamespace("MAPI").GetFolderFromID(gsCntsSyncFolderId)
        Set oOlContact = oOlFolder.Items.Add("IPM.Contact")
    Else
        Set oOlContact = oOlApp.ActiveExplorer.CurrentFolder.Items.Add("IPM.Contact")
    End If
    
    oOlContact.Title = DecodeUTF8(oXMLVtElement.selectSingleNode("title").nodeTypedValue)
    oOlContact.FirstName = DecodeUTF8(oXMLVtElement.selectSingleNode("firstname").nodeTypedValue)
    oOlContact.MiddleName = DecodeUTF8(oXMLVtElement.selectSingleNode("middlename").nodeTypedValue)
    oOlContact.LastName = DecodeUTF8(oXMLVtElement.selectSingleNode("lastname").nodeTypedValue)

    sBirthDate = DecodeUTF8(oXMLVtElement.selectSingleNode("birthdate").nodeTypedValue)
    If sBirthDate = "" Then
        sBirthDate = Format("4501-01-01", "YYYY-MM-DD")
    Else
        sBirthDate = Format(DecodeUTF8(oXMLVtElement.selectSingleNode("birthdate").nodeTypedValue), "YYYY-MM-DD")
    End If
    oOlContact.Birthday = sBirthDate

    oOlContact.Email1Address = DecodeUTF8(oXMLVtElement.selectSingleNode("emailaddress").nodeTypedValue)
    oOlContact.CompanyName = DecodeUTF8(oXMLVtElement.selectSingleNode("accountname").nodeTypedValue)
    oOlContact.JobTitle = DecodeUTF8(oXMLVtElement.selectSingleNode("jobtitle").nodeTypedValue)
    oOlContact.Department = DecodeUTF8(oXMLVtElement.selectSingleNode("department").nodeTypedValue)

    oOlContact.BusinessTelephoneNumber = DecodeUTF8(oXMLVtElement.selectSingleNode("officephone").nodeTypedValue)
    oOlContact.HomeTelephoneNumber = DecodeUTF8(oXMLVtElement.selectSingleNode("homephone").nodeTypedValue)
    oOlContact.OtherTelephoneNumber = DecodeUTF8(oXMLVtElement.selectSingleNode("otherphone").nodeTypedValue)
    oOlContact.BusinessFaxNumber = DecodeUTF8(oXMLVtElement.selectSingleNode("fax").nodeTypedValue)
    oOlContact.MobileTelephoneNumber = DecodeUTF8(oXMLVtElement.selectSingleNode("mobile").nodeTypedValue)

    oOlContact.AssistantName = DecodeUTF8(oXMLVtElement.selectSingleNode("asstname").nodeTypedValue)
    oOlContact.AssistantTelephoneNumber = DecodeUTF8(oXMLVtElement.selectSingleNode("asstphone").nodeTypedValue)

    oOlContact.ManagerName = DecodeUTF8(oXMLVtElement.selectSingleNode("reportsto").nodeTypedValue)

    oOlContact.BusinessAddressStreet = DecodeUTF8(oXMLVtElement.selectSingleNode("mailingstreet").nodeTypedValue)
    oOlContact.BusinessAddressCity = DecodeUTF8(oXMLVtElement.selectSingleNode("mailingcity").nodeTypedValue)
    oOlContact.BusinessAddressState = DecodeUTF8(oXMLVtElement.selectSingleNode("mailingstate").nodeTypedValue)
    oOlContact.BusinessAddressPostalCode = DecodeUTF8(oXMLVtElement.selectSingleNode("mailingzip").nodeTypedValue)
    oOlContact.BusinessAddressCountry = DecodeUTF8(oXMLVtElement.selectSingleNode("mailingcountry").nodeTypedValue)

    oOlContact.OtherAddressStreet = DecodeUTF8(oXMLVtElement.selectSingleNode("otherstreet").nodeTypedValue)
    oOlContact.OtherAddressCity = DecodeUTF8(oXMLVtElement.selectSingleNode("othercity").nodeTypedValue)
    oOlContact.OtherAddressState = DecodeUTF8(oXMLVtElement.selectSingleNode("otherstate").nodeTypedValue)
    oOlContact.OtherAddressPostalCode = DecodeUTF8(oXMLVtElement.selectSingleNode("otherzip").nodeTypedValue)
    oOlContact.OtherAddressCountry = DecodeUTF8(oXMLVtElement.selectSingleNode("othercountry").nodeTypedValue)

    oOlContact.Body = DecodeUTF8(oXMLVtElement.selectSingleNode("description").nodeTypedValue)

    oOlContact.Categories = DecodeUTF8(oXMLVtElement.selectSingleNode("category").nodeTypedValue)

    oOlContact.Save
      
    sCrmId = oXMLVtElement.getAttribute("crmid")
    
    If Not oXMLAppend_Doc.loadXML(oXMLVtElement.xml) Then GoTo ERROR_EXIT_ROUTINE
    Set oXMLAppend_Root = oXMLAppend_Doc.documentElement
    
    oXMLAppend_Root.removeAttribute ("crmid")
    oXMLAppend_Root.removeAttribute ("syncflag")
        
    Call AddAttribute(oXMLAppend_Root, "entryid", oOlContact.EntryID)
    Call AddAttribute(oXMLAppend_Root, "syncflag", "NM")
    Set oXMLLocalOl_Node = oXMLLocalOl_Root.appendChild(oXMLAppend_Root)
    
    sXQuery = "contactitems[@crmid='" & sCrmId & "']"
    Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
    If oXMLLocalVt_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
    
    Call AddAttribute(oXMLLocalVt_First, "syncflag", "NM")
            
    sCreateOlContacts = oOlContact.EntryID
        
    oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
    oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
    
    End If
End If

GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sCreateOlContacts = ""
    'sMsgDlg ("sCreateOlContacts" & Err.Description)
    LogTheMessage ("sCreateOlContacts - " & Err.Description)
EXIT_ROUTINE:
Set oOlContact = Nothing
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

Public Function bUpdateOlContacts(ByVal sEntryId As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlContact As Outlook.ContactItem
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
        Set oOlContact = oOlNS.GetItemFromID(sEntryId)
                
        sXQuery = "contactitems[@crmid='" & sCrmId & "']"
        Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
        If oXMLLocalVt_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
                
        sXQuery = "contactitems[@entryid='" & sEntryId & "']"
        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        If oOlContact Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        oOlContact.Title = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("title").nodeTypedValue)
        oOlContact.FirstName = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("firstname").nodeTypedValue)
        oOlContact.MiddleName = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("middlename").nodeTypedValue)
        oOlContact.LastName = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("lastname").nodeTypedValue)

        sBirthDate = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("birthdate").nodeTypedValue)
        If sBirthDate = "" Then
            sBirthDate = Format("4501-01-01", "YYYY-MM-DD")
        Else
            sBirthDate = Format(DecodeUTF8(oXMLLocalVt_First.selectSingleNode("birthdate").nodeTypedValue), "YYYY-MM-DD")
        End If
        oOlContact.Birthday = sBirthDate

        oOlContact.Email1Address = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("emailaddress").nodeTypedValue)
        oOlContact.CompanyName = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("accountname").nodeTypedValue)
        oOlContact.JobTitle = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("jobtitle").nodeTypedValue)
        oOlContact.Department = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("department").nodeTypedValue)

        oOlContact.BusinessTelephoneNumber = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("officephone").nodeTypedValue)
        oOlContact.HomeTelephoneNumber = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("homephone").nodeTypedValue)
        oOlContact.OtherTelephoneNumber = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("otherphone").nodeTypedValue)
        oOlContact.BusinessFaxNumber = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("fax").nodeTypedValue)
        oOlContact.MobileTelephoneNumber = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("mobile").nodeTypedValue)

        oOlContact.AssistantName = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("asstname").nodeTypedValue)
        oOlContact.AssistantTelephoneNumber = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("asstphone").nodeTypedValue)

        oOlContact.ManagerName = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("reportsto").nodeTypedValue)

        oOlContact.BusinessAddressStreet = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("mailingstreet").nodeTypedValue)
        oOlContact.BusinessAddressCity = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("mailingcity").nodeTypedValue)
        oOlContact.BusinessAddressState = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("mailingstate").nodeTypedValue)
        oOlContact.BusinessAddressPostalCode = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("mailingzip").nodeTypedValue)
        oOlContact.BusinessAddressCountry = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("mailingcountry").nodeTypedValue)

        oOlContact.OtherAddressStreet = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("otherstreet").nodeTypedValue)
        oOlContact.OtherAddressCity = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("othercity").nodeTypedValue)
        oOlContact.OtherAddressState = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("otherstate").nodeTypedValue)
        oOlContact.OtherAddressPostalCode = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("otherzip").nodeTypedValue)
        oOlContact.OtherAddressCountry = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("othercountry").nodeTypedValue)

        oOlContact.Body = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("description").nodeTypedValue)

        oOlContact.Categories = DecodeUTF8(oXMLLocalVt_First.selectSingleNode("category").nodeTypedValue)

        oOlContact.Save
              
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
bUpdateOlContacts = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("bUpdateOlContacts" & Err.Description)
    LogTheMessage ("bUpdateOlContacts - " & Err.Description)
    bUpdateOlContacts = False
EXIT_ROUTINE:
Set oOlContact = Nothing
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

Public Function bDelOlContacts(ByVal sEntryId As String, ByVal sCrmId As String) As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

Dim oOlContact As Outlook.ContactItem
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
        Set oOlContact = oOlNS.GetItemFromID(sEntryId)
        If Not oOlContact Is Nothing Then
            oOlContact.Delete
        End If
        
        sXQuery = "contactitems[@entryid='" & sEntryId & "']"
        Set oXMLLocalOl_Node = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        Set oXMLDel_Node = oXMLLocalOl_Root.removeChild(oXMLLocalOl_Node)
        
        sXQuery = "contactitems[@crmid='" & sCrmId & "']"
        Set oXMLLocalVt_Node = oXMLLocalVt_Root.selectSingleNode(sXQuery)
        If oXMLLocalVt_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        Set oXMLDel_Node = oXMLLocalVt_Root.removeChild(oXMLLocalVt_Node)
        
        oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
        oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        
    End If
End If
bDelOlContacts = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    bDelOlContacts = False
    'sMsgDlg ("bDelOlContacts" & Err.Description)
    LogTheMessage ("bDelOlContacts - " & Err.Description)
EXIT_ROUTINE:
Set oOlContact = Nothing
Set oOlNS = Nothing
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_Node = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_Node = Nothing
Set oXMLDel_Node = Nothing
End Function
Public Function sCreateVtContacts(ByVal oXMLOlElement As MSXML.IXMLDOMElement) As String
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
        
        sCrmId = sVtSoNewContacts(gsVtUserId, oXMLOlElement)
        sEntryId = oXMLOlElement.getAttribute("entryid")
                
        If Not oXMLAppend_Doc.loadXML(oXMLOlElement.xml) Then GoTo ERROR_EXIT_ROUTINE
               
        Set oXMLAppend_Root = oXMLAppend_Doc.documentElement
        
        oXMLAppend_Root.removeAttribute ("entryid")
        oXMLAppend_Root.removeAttribute ("syncflag")
        
        Call AddAttribute(oXMLAppend_Root, "crmid", sCrmId)
        Call AddAttribute(oXMLAppend_Root, "syncflag", "NM")
        Set oXMLLocalVt_Node = oXMLLocalVt_Root.appendChild(oXMLAppend_Root)
        
        sXQuery = "contactitems[@entryid='" & sEntryId & "']"
        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        Call AddAttribute(oXMLLocalOl_First, "syncflag", "NM")
        
        sCreateVtContacts = sCrmId
        
        oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
        oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        
    End If
End If
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    sCreateVtContacts = ""
    'sMsgDlg ("sCreateVtContacts" & Err.Description)
    LogTheMessage ("sCreateVtContacts - " & Err.Description)
EXIT_ROUTINE:
Set oXMLOlElement = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_Node = Nothing
Set oXMLAppend_Doc = Nothing
Set oXMLAppend_Root = Nothing
End Function

Public Function bUpdateVtContacts(ByVal sEntryId As String, ByVal sCrmId As String) As Boolean
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
                       
        sXQuery = "contactitems[@crmid='" & sCrmId & "']"
        Set oXMLLocalVt_First = oXMLLocalVt_Root.selectSingleNode(sXQuery)
        If oXMLLocalVt_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
                
        sXQuery = "contactitems[@entryid='" & sEntryId & "']"
        Set oXMLLocalOl_First = oXMLLocalOl_Root.selectSingleNode(sXQuery)
        If oXMLLocalOl_First Is Nothing Then GoTo ERROR_EXIT_ROUTINE
        
        'Call Soap Method for Update
        If sVtSoUpdateContacts(sCrmId, oXMLLocalOl_First) <> "" Then
        
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
bUpdateVtContacts = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    'sMsgDlg ("bUpdateVtContacts" & Err.Description)
    LogTheMessage ("bUpdateVtContacts - " & Err.Description)
    bUpdateVtContacts = False
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

Public Function bDelVtContacts(ByVal sEntryId As String, ByVal sCrmId As String) As Boolean
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
        If bVtSoDeleteContacts(gsVtUserId, sCrmId) = True Then
            sXQuery = "contactitems[@entryid='" & sEntryId & "']"
            Set oXMLLocalOl_Node = oXMLLocalOl_Root.selectSingleNode(sXQuery)
            If oXMLLocalOl_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
            Set oXMLDel_Node = oXMLLocalOl_Root.removeChild(oXMLLocalOl_Node)
            
            sXQuery = "contactitems[@crmid='" & sCrmId & "']"
            Set oXMLLocalVt_Node = oXMLLocalVt_Root.selectSingleNode(sXQuery)
            If oXMLLocalVt_Node Is Nothing Then GoTo ERROR_EXIT_ROUTINE
            Set oXMLDel_Node = oXMLLocalVt_Root.removeChild(oXMLLocalVt_Node)
            
            oXMLLocalOl_Doc.Save (gsVtUserFolder & LOCAL_OL_FILE)
            oXMLLocalVt_Doc.Save (gsVtUserFolder & LOCAL_VTIGER_FILE)
        End If
    End If
End If
bDelVtContacts = True
GoTo EXIT_ROUTINE
ERROR_EXIT_ROUTINE:
    bDelVtContacts = False
    'sMsgDlg ("bDelVtContacts" & Err.Description)
    LogTheMessage ("bDelVtContacts - " & Err.Description)
EXIT_ROUTINE:
Set oXMLLocalOl_Doc = Nothing
Set oXMLLocalOl_Root = Nothing
Set oXMLLocalOl_Node = Nothing
Set oXMLLocalVt_Doc = Nothing
Set oXMLLocalVt_Root = Nothing
Set oXMLLocalVt_Node = Nothing
Set oXMLDel_Node = Nothing
End Function
