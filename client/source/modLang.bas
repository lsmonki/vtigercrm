Attribute VB_Name = "modLang"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
' * All Rights Reserved.
'*
' ********************************************************************************/
Option Explicit

'Api to read and write string from ini file
Private Declare Function GetPrivateProfileString Lib "kernel32.dll" Alias "GetPrivateProfileStringA" (ByVal lpApplicationName As String, ByVal lpKeyName As String, ByVal lpDefault As String, ByVal lpReturnedString As String, ByVal nSize As Long, ByVal lpFileName As String) As Long
Private Declare Function WritePrivateProfileString Lib "kernel32.dll" Alias "WritePrivateProfileStringA" (ByVal lpApplicationName As String, ByVal lpKeyName As String, ByVal LPString As String, ByVal lpFileName As String) As Long
Private Const MAX_PATH = 260

'Gloabal Constants for Language Settings
Public Const sEnglish_IniFileName As String = "english.ini"
Public Const sGerman_IniFileName As String = "german.ini"
Public Const sFrench_IniFileName As String = "french.ini"
Public Const sPortuguese_IniFileName As String = "portuguese.ini"
Public Const sDeutsch_IniFileName As String = "deutsch.ini"
Public Const sFinnish_IniFileName As String = "finnish.ini"

Public Const LangEnglish As String = "en_us"
Public Const LangGerman As String = "de_ch"
Public Const LangFrench As String = "fr_fr"
Public Const LangPortuguese As String = "pt_pt"
Public Const LangDeutsch As String = "de_de"
Public Const LangFinnish As String = "fi_fi"

Public Const sSec_Common As String = "vtigerCRM_Common"
Public Const sSec_WordMenu As String = "vtigerCRM_WordMenu"
Public Const sSec_frmMerge As String = "vtigerCRM_frmMerge"
Public Const sSec_frmConfig As String = "vtigerCRM_frmConfig"
Public Const sSec_frmAbout As String = "vtigerCRM_frmAbout"
Public Const sSec_Messages As String = "vtigerCRM_Messages"


'Global variables for Language Settings
Public sLangIniPath As String

'Common Section Values
Public gCmpyName As String
Public gPrdName As String
Public gPrdVersion As String
Public gPrdSite As String
Public gPrdLang As String

'WordMenu Section Values
Public gMenuTitle As String
Public gLogin As String
Public gLogout As String
Public gMailMerge As String
Public gConfig As String
Public gHelp As String
Public gAbout As String

'frmAbout Section Values
Public gfrmAbout_FormName As String
Public gfrmAbout_Label2 As String
Public gfrmAbout_Label3 As String
Public gfrmAbout_OkButton As String

'frmMerge Section Values
Public gfrmMerge_FormName As String
Public gfrmMerge_Label1 As String
Public gfrmMerge_Label2 As String
Public gfrmMerge_InsertButton As String
Public gfrmMerge_CloseButton As String

'frmConf Section Values
Public gfrmConf_FormName As String
Public gfrmConf_Label1 As String
Public gfrmConf_Button1 As String
Public gfrmConf_Button2 As String

Public gfrmConf_Frame1 As String
Public gfrmConf_Frm1_Lbl1 As String
Public gfrmConf_Frm1_Lbl2 As String
Public gfrmConf_Frm1_Lbl3 As String
Public gfrmConf_Frm1_Lbl4 As String

Public gfrmConf_Frame2 As String
Public gfrmConf_Frm2_Lbl1 As String
Public gfrmConf_Frm2_Lbl2 As String
Public gfrmConf_Frm2_Lbl3 As String
Public gfrmConf_Frm2_Lbl4 As String
Public gfrmConf_Frm2_Opt1 As String
Public gfrmConf_Frm2_Opt2 As String

Public gMsg001 As String 'ActiveX object cannot be intialize
Public gMsg002 As String 'vtigerCRM Word Add-In cannot connect to the server
Public gMsg003 As String 'Connected to vtigerCRM
Public gMsg004 As String 'Disconnected from vtigerCRM
Public gMsg005 As String 'Invalid return value from vtigerCRM
Public gMsg006 As String 'vtigerCRM can't create template menu
Public gMsg007 As String 'Select merge field to insert
Public gMsg008 As String 'vtigerCRM can't write configuration to the registry
Public gMsg009 As String 'Required fields to access vtigerCRM
Public gMsg010 As String 'User Name
Public gMsg011 As String 'Password
Public gMsg012 As String 'URL
Public gMsg013 As String 'Proxy Address
Public gMsg014 As String 'Proxy Port
Public gMsg015 As String 'Proxy User Name
Public gMsg016 As String 'Proxy Password
Public gMsg017 As String 'Create document to insert merge field
Public gMsg018 As String 'Unable to connect vtigerCRM Server with the given username and password



Public Function bLoadLang() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

If gLangInstalled <> "" Then

    sLangIniPath = sGetLangFilePath(gLangInstalled)
      
    If Not bvtigerCRM_Common Then GoTo ERROR_EXIT_ROUTINE
    
    If Not bvtigerCRM_Messages Then GoTo ERROR_EXIT_ROUTINE
    
    If Not bvtigerCRM_WordMenu Then GoTo ERROR_EXIT_ROUTINE
        
    If Not bvtigerCRM_frmConf Then GoTo ERROR_EXIT_ROUTINE
    
    If Not bvtigerCRM_frmMerge Then GoTo ERROR_EXIT_ROUTINE
    
    If Not bvtigerCRM_frmAbout Then GoTo ERROR_EXIT_ROUTINE
    
End If

bLoadLang = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bLoadLang = False
EXIT_ROUTINE:
End Function
Public Function sGetLangFilePath(ByVal sRegLangValue As String) As String
Dim sLangAppPath As String

If sRegLangValue = LangEnglish Then
    sLangAppPath = gapppath & "\Language\" & sEnglish_IniFileName
ElseIf sRegLangValue = LangGerman Then
    sLangAppPath = gapppath & "\Language\" & sGerman_IniFileName
ElseIf sRegLangValue = LangFrench Then
    sLangAppPath = gapppath & "\Language\" & sFrench_IniFileName
ElseIf sRegLangValue = LangDeutsch Then
    sLangAppPath = gapppath & "\Language\" & sDeutsch_IniFileName
ElseIf sRegLangValue = LangPortuguese Then
    sLangAppPath = gapppath & "\Language\" & sPortuguese_IniFileName
ElseIf sRegLangValue = LangFinnish Then
    sLangAppPath = gapppath & "\Language\" & sFinnish_IniFileName
End If

sGetLangFilePath = sLangAppPath

End Function
Public Function bvtigerCRM_Common() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

gCmpyName = ini_read(sSec_Common, "CompanyName", sLangIniPath)
gPrdName = ini_read(sSec_Common, "ProductName", sLangIniPath)
gPrdVersion = ini_read(sSec_Common, "ProductVersion", sLangIniPath)
gPrdSite = ini_read(sSec_Common, "ProductSite", sLangIniPath)
gPrdLang = ini_read(sSec_Common, "ProductLanguage", sLangIniPath)

bvtigerCRM_Common = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bvtigerCRM_Common = False
EXIT_ROUTINE:

End Function

Public Function bvtigerCRM_frmMerge() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

gfrmMerge_FormName = ini_read(sSec_frmMerge, "FormName", sLangIniPath)
gfrmMerge_Label1 = ini_read(sSec_frmMerge, "Label1", sLangIniPath)
gfrmMerge_Label2 = ini_read(sSec_frmMerge, "Label2", sLangIniPath)
gfrmMerge_InsertButton = ini_read(sSec_frmMerge, "Button1", sLangIniPath)
gfrmMerge_CloseButton = ini_read(sSec_frmMerge, "Button2", sLangIniPath)

bvtigerCRM_frmMerge = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bvtigerCRM_frmMerge = False
EXIT_ROUTINE:

End Function

Public Function bvtigerCRM_frmConf() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

gfrmConf_FormName = ini_read(sSec_frmConfig, "FormName", sLangIniPath)
gfrmConf_Label1 = ini_read(sSec_frmConfig, "Label1", sLangIniPath)
gfrmConf_Button1 = ini_read(sSec_frmConfig, "Button1", sLangIniPath)
gfrmConf_Button2 = ini_read(sSec_frmConfig, "Button2", sLangIniPath)

gfrmConf_Frame1 = ini_read(sSec_frmConfig, "Frame1", sLangIniPath)
gfrmConf_Frm1_Lbl1 = ini_read(sSec_frmConfig, "Frame1_Label1", sLangIniPath)
gfrmConf_Frm1_Lbl2 = ini_read(sSec_frmConfig, "Frame1_Label2", sLangIniPath)
gfrmConf_Frm1_Lbl3 = ini_read(sSec_frmConfig, "Frame1_Label3", sLangIniPath)
gfrmConf_Frm1_Lbl4 = ini_read(sSec_frmConfig, "Frame1_Label4", sLangIniPath)

gfrmConf_Frame2 = ini_read(sSec_frmConfig, "Frame2", sLangIniPath)
gfrmConf_Frm2_Lbl1 = ini_read(sSec_frmConfig, "Frame2_Label1", sLangIniPath)
gfrmConf_Frm2_Lbl2 = ini_read(sSec_frmConfig, "Frame2_Label2", sLangIniPath)
gfrmConf_Frm2_Lbl3 = ini_read(sSec_frmConfig, "Frame2_Label3", sLangIniPath)
gfrmConf_Frm2_Lbl4 = ini_read(sSec_frmConfig, "Frame2_Label4", sLangIniPath)
gfrmConf_Frm2_Opt1 = ini_read(sSec_frmConfig, "Frame2_Opt1", sLangIniPath)
gfrmConf_Frm2_Opt2 = ini_read(sSec_frmConfig, "Frame2_Opt2", sLangIniPath)

bvtigerCRM_frmConf = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bvtigerCRM_frmConf = False
EXIT_ROUTINE:

End Function

Public Function bvtigerCRM_frmAbout() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

gfrmAbout_FormName = ini_read(sSec_frmAbout, "FormName", sLangIniPath)
gfrmAbout_Label2 = ini_read(sSec_frmAbout, "Label2", sLangIniPath)
gfrmAbout_Label3 = ini_read(sSec_frmAbout, "Label3", sLangIniPath)
gfrmAbout_OkButton = ini_read(sSec_frmAbout, "Button1", sLangIniPath)

bvtigerCRM_frmAbout = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bvtigerCRM_frmAbout = False
EXIT_ROUTINE:

End Function

Public Function bvtigerCRM_Messages() As Boolean
On Error GoTo ERROR_EXIT_ROUTINE

gMsg001 = ini_read(sSec_Messages, "Msg001", sLangIniPath)
gMsg002 = ini_read(sSec_Messages, "Msg002", sLangIniPath)
gMsg003 = ini_read(sSec_Messages, "Msg003", sLangIniPath)
gMsg004 = ini_read(sSec_Messages, "Msg004", sLangIniPath)
gMsg005 = ini_read(sSec_Messages, "Msg005", sLangIniPath)
gMsg006 = ini_read(sSec_Messages, "Msg006", sLangIniPath)
gMsg007 = ini_read(sSec_Messages, "Msg007", sLangIniPath)
gMsg008 = ini_read(sSec_Messages, "Msg008", sLangIniPath)
gMsg009 = ini_read(sSec_Messages, "Msg009", sLangIniPath)
gMsg010 = ini_read(sSec_Messages, "Msg010", sLangIniPath)
gMsg011 = ini_read(sSec_Messages, "Msg011", sLangIniPath)
gMsg012 = ini_read(sSec_Messages, "Msg012", sLangIniPath)
gMsg013 = ini_read(sSec_Messages, "Msg013", sLangIniPath)
gMsg014 = ini_read(sSec_Messages, "Msg014", sLangIniPath)
gMsg015 = ini_read(sSec_Messages, "Msg015", sLangIniPath)
gMsg016 = ini_read(sSec_Messages, "Msg016", sLangIniPath)
gMsg017 = ini_read(sSec_Messages, "Msg017", sLangIniPath)
gMsg018 = ini_read(sSec_Messages, "Msg018", sLangIniPath)

bvtigerCRM_Messages = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bvtigerCRM_Messages = False
EXIT_ROUTINE:

End Function
Public Function bvtigerCRM_WordMenu() As Boolean

On Error GoTo ERROR_EXIT_ROUTINE

gMenuTitle = ini_read(sSec_WordMenu, "MenuTitle", sLangIniPath, "vtiger&CRM")
gLogin = ini_read(sSec_WordMenu, "SubMenu1", sLangIniPath, "Sign &In")
gLogout = ini_read(sSec_WordMenu, "SubMenu2", sLangIniPath, "Sign &Out")
gMailMerge = ini_read(sSec_WordMenu, "SubMenu3", sLangIniPath, "Insert &Merge Field")
gConfig = ini_read(sSec_WordMenu, "SubMenu4", sLangIniPath, "&Configuration")
gHelp = ini_read(sSec_WordMenu, "SubMenu5", sLangIniPath, "&Help")
gAbout = ini_read(sSec_WordMenu, "SubMenu6", sLangIniPath, "&About")

bvtigerCRM_WordMenu = True
GoTo EXIT_ROUTINE

ERROR_EXIT_ROUTINE:
bvtigerCRM_WordMenu = False
EXIT_ROUTINE:

End Function
'=============================================================================================================
' ini_read
' Param                 Description
' ---------------------------------
' SectionName           Specifies the section to find the specified key in
' KeyName               Specifies the key to get the value from
' INIPath               Specifies the full path to the .INI file to get the value from
' DefaultValue          Optional. Specifies a default value to return.  If the
'                       specified file does not exist, if the section is does not
'                       exist, if the specified key does not exist, or if the value
'                       of the specified key is vbNullString, then this value is returned
'
' Return:
' -------
' Returns the specified key value, or the specified DefaultValue
'=============================================================================================================
Public Function ini_read(ByVal SectionName As String, ByVal KeyName As String, ByVal INIPath As String, Optional ByVal DefaultValue As String = "") As String

On Error Resume Next
Dim lngLength As Long
  
    ini_read = String(MAX_PATH, Chr(0))
    lngLength = GetPrivateProfileString(SectionName & Chr(0), KeyName & Chr(0), DefaultValue & Chr(0), ini_read, Len(ini_read), INIPath & Chr(0))
    ini_read = Left(ini_read, lngLength)
End Function

