Attribute VB_Name = "modCommonUtils"
'*********************************************************************************
'* The contents of this file are subject to the vtiger CRM Public License Version 1.0
' * ("License"); You may not use this file except in compliance with the License
' * The Original Code is:  vtiger CRM Open Source
' * The Initial Developer of the Original Code is vtiger.
' * Portions created by vtiger are Copyright (C) vtiger.
'* © 2003-2005 vtiger.com. All rights reserved.
' ********************************************************************************/
Option Explicit

'Public Enum RegistryKeys
'  HKEY_CLASSES_ROOT = &H80000000
'  HKEY_CURRENT_USER = &H80000001
'  HKEY_LOCAL_MACHINE = &H80000002
'  HKEY_USERS = &H80000003
'  HKEY_CURRENT_CONFIG = &H80000005
'  HKEY_DYN_DATA = &H80000006
'End Enum

Public Const HKEY_PERFORMANCE_DATA = &H80000004
Private Const KEY_QUERY_VALUE = &H1
Public Const ERROR_SUCCESS = 0&
Public Const REG_SZ = 1
Public Const REG_DWORD = 4

Private Declare Sub CopyMemory Lib "Kernel32" Alias "RtlMoveMemory" (Destination As Any, Source As Any, ByVal Length As Long)

Private Declare Function RegOpenKeyEx Lib "advapi32.dll" Alias "RegOpenKeyExA" _
  (ByVal hKey As Long, ByVal lpSubKey As String, ByVal ulOptions As Long, _
  ByVal samDesired As Long, phkResult As Long) As Long
Declare Function RegCloseKey Lib "advapi32.dll" (ByVal hKey As Long) As Long
Declare Function RegCreateKey Lib "advapi32.dll" Alias "RegCreateKeyA" (ByVal hKey As Long, ByVal lpSubKey As String, phkResult As Long) As Long
Declare Function RegDeleteKey Lib "advapi32.dll" Alias "RegDeleteKeyA" (ByVal hKey As Long, ByVal lpSubKey As String) As Long
Declare Function RegDeleteValue Lib "advapi32.dll" Alias "RegDeleteValueA" (ByVal hKey As Long, ByVal lpValueName As String) As Long
Declare Function RegOpenKey Lib "advapi32.dll" Alias "RegOpenKeyA" (ByVal hKey As Long, ByVal lpSubKey As String, phkResult As Long) As Long
Declare Function RegQueryValueEx Lib "advapi32.dll" Alias "RegQueryValueExA" (ByVal hKey As Long, ByVal lpValueName As String, ByVal lpReserved As Long, lpType As Long, lpData As Any, lpcbData As Long) As Long
Declare Function RegSetValueEx Lib "advapi32.dll" Alias "RegSetValueExA" (ByVal hKey As Long, ByVal lpValueName As String, ByVal Reserved As Long, ByVal dwType As Long, lpData As Any, ByVal cbData As Long) As Long

'****************************************************************************
'                       HTML DECODE
'****************************************************************************
Private Static Sub ReplaceBin(ByRef Result As String, _
    ByRef Text As String, ByRef Search As String, _
    ByRef sOld As String, ByRef sNew As String, _
    ByVal Start As Long, ByVal Count As Long _
  )

  Dim TextLen As Long
  Dim OldLen As Long
  Dim NewLen As Long
  Dim ReadPos As Long
  Dim WritePos As Long
  Dim CopyLen As Long
  Dim Buffer As String
  Dim BufferLen As Long
  Dim BufferPosNew As Long
  Dim BufferPosNext As Long

  'Ersten Treffer bestimmen:
  If Start < 2 Then
    Start = InStrB(Search, sOld)
  Else
    Start = InStrB(Start + Start - 1, Search, sOld)
  End If
  If Start Then
  
    OldLen = LenB(sOld)
    NewLen = LenB(sNew)
    Select Case NewLen
    Case OldLen 'einfaches Überschreiben:
    
      Result = Text
      For Count = 1 To Count
        MidB$(Result, Start) = sNew
        Start = InStrB(Start + OldLen, Search, sOld)
        If Start = 0 Then Exit Sub
      Next Count
      Exit Sub
    
    Case Is < OldLen 'Ergebnis wird kürzer:
    
      'Buffer initialisieren:
      TextLen = LenB(Text)
      If TextLen > BufferLen Then
        Buffer = Text
        BufferLen = TextLen
      End If
      
      'Ersetzen:
      ReadPos = 1
      WritePos = 1
      If NewLen Then
      
        'Einzufügenden Text beachten:
        For Count = 1 To Count
          CopyLen = Start - ReadPos
          If CopyLen Then
            BufferPosNew = WritePos + CopyLen
            MidB$(Buffer, WritePos) = MidB$(Text, ReadPos, CopyLen)
            MidB$(Buffer, BufferPosNew) = sNew
            WritePos = BufferPosNew + NewLen
          Else
            MidB$(Buffer, WritePos) = sNew
            WritePos = WritePos + NewLen
          End If
          ReadPos = Start + OldLen
          Start = InStrB(ReadPos, Search, sOld)
          If Start = 0 Then Exit For
        Next Count
      
      Else
      
        'Einzufügenden Text ignorieren (weil leer):
        For Count = 1 To Count
          CopyLen = Start - ReadPos
          If CopyLen Then
            MidB$(Buffer, WritePos) = MidB$(Text, ReadPos, CopyLen)
            WritePos = WritePos + CopyLen
          End If
          ReadPos = Start + OldLen
          Start = InStrB(ReadPos, Search, sOld)
          If Start = 0 Then Exit For
        Next Count
      
      End If
      
      'Ergebnis zusammenbauen:
      If ReadPos > TextLen Then
        Result = LeftB$(Buffer, WritePos - 1)
      Else
        MidB$(Buffer, WritePos) = MidB$(Text, ReadPos)
        Result = LeftB$(Buffer, WritePos + LenB(Text) - ReadPos)
      End If
      Exit Sub
    
    Case Else 'Ergebnis wird länger:
    
      'Buffer initialisieren:
      TextLen = LenB(Text)
      BufferPosNew = TextLen + NewLen
      If BufferPosNew > BufferLen Then
        Buffer = Space$(BufferPosNew)
        BufferLen = LenB(Buffer)
      End If
      
      'Ersetzung:
      ReadPos = 1
      WritePos = 1
      For Count = 1 To Count
        CopyLen = Start - ReadPos
        If CopyLen Then
          'Positionen berechnen:
          BufferPosNew = WritePos + CopyLen
          BufferPosNext = BufferPosNew + NewLen
          
          'Ggf. Buffer vergrößern:
          If BufferPosNext > BufferLen Then
            Buffer = Buffer & Space$(BufferPosNext)
            BufferLen = LenB(Buffer)
          End If
          
          'String "patchen":
          MidB$(Buffer, WritePos) = MidB$(Text, ReadPos, CopyLen)
          MidB$(Buffer, BufferPosNew) = sNew
        Else
          'Position bestimmen:
          BufferPosNext = WritePos + NewLen
          
          'Ggf. Buffer vergrößern:
          If BufferPosNext > BufferLen Then
            Buffer = Buffer & Space$(BufferPosNext)
            BufferLen = LenB(Buffer)
          End If
          
          'String "patchen":
          MidB$(Buffer, WritePos) = sNew
        End If
        WritePos = BufferPosNext
        ReadPos = Start + OldLen
        Start = InStrB(ReadPos, Search, sOld)
        If Start = 0 Then Exit For
      Next Count
      
      'Ergebnis zusammenbauen:
      If ReadPos > TextLen Then
        Result = LeftB$(Buffer, WritePos - 1)
      Else
        BufferPosNext = WritePos + TextLen - ReadPos
        If BufferPosNext < BufferLen Then
          MidB$(Buffer, WritePos) = MidB$(Text, ReadPos)
          Result = LeftB$(Buffer, BufferPosNext)
        Else
          Result = LeftB$(Buffer, WritePos - 1) & MidB$(Text, ReadPos)
        End If
      End If
      Exit Sub
    
    End Select
  
  Else 'Kein Treffer:
    Result = Text
  End If

End Sub
Private Static Sub ReplaceBin0(ByRef Result As String, _
    ByRef Text As String, ByRef Search As String, _
    ByRef sOld As String, ByRef sNew As String, _
    ByVal Start As Long, ByVal Count As Long _
  )

  Dim TextLen As Long
  Dim OldLen As Long
  Dim NewLen As Long
  Dim ReadPos As Long
  Dim WritePos As Long
  Dim CopyLen As Long
  Dim Buffer As String
  Dim BufferLen As Long
  Dim BufferPosNew As Long
  Dim BufferPosNext As Long

  'Ersten Treffer bestimmen:
  If Start < 2 Then
    Start = InStr(Search, sOld)
  Else
    Start = InStr(Start, Search, sOld)
  End If
  
  If Start Then
  
    OldLen = Len(sOld)
    NewLen = Len(sNew)
    Select Case NewLen
    Case OldLen 'einfaches Überschreiben:
    
      Result = Text
      For Count = 1 To Count
        Mid$(Result, Start) = sNew
        Start = InStr(Start + OldLen, Search, sOld)
        If Start = 0 Then Exit Sub
      Next Count
      Exit Sub
    
    Case Is < OldLen 'Ergebnis wird kürzer:
    
      'Buffer initialisieren:
      TextLen = Len(Text)
      If TextLen > BufferLen Then
        Buffer = Text
        BufferLen = TextLen
      End If
      
      'Ersetzen:
      ReadPos = 1
      WritePos = 1
      If NewLen Then
      
        'Einzufügenden Text beachten:
        For Count = 1 To Count
          CopyLen = Start - ReadPos
          If CopyLen Then
            BufferPosNew = WritePos + CopyLen
            Mid$(Buffer, WritePos) = Mid$(Text, ReadPos, CopyLen)
            Mid$(Buffer, BufferPosNew) = sNew
            WritePos = BufferPosNew + NewLen
          Else
            Mid$(Buffer, WritePos) = sNew
            WritePos = WritePos + NewLen
          End If
          ReadPos = Start + OldLen
          Start = InStr(ReadPos, Search, sOld)
          If Start = 0 Then Exit For
        Next Count
      
      Else
      
        'Einzufügenden Text ignorieren (weil leer):
        For Count = 1 To Count
          CopyLen = Start - ReadPos
          If CopyLen Then
            Mid$(Buffer, WritePos) = Mid$(Text, ReadPos, CopyLen)
            WritePos = WritePos + CopyLen
          End If
          ReadPos = Start + OldLen
          Start = InStr(ReadPos, Search, sOld)
          If Start = 0 Then Exit For
        Next Count
      
      End If
      
      'Ergebnis zusammenbauen:
      If ReadPos > TextLen Then
        Result = Left$(Buffer, WritePos - 1)
      Else
        Mid$(Buffer, WritePos) = Mid$(Text, ReadPos)
        Result = Left$(Buffer, WritePos + Len(Text) - ReadPos)
      End If
      Exit Sub
    
    Case Else 'Ergebnis wird länger:
    
      'Buffer initialisieren:
      TextLen = Len(Text)
      BufferPosNew = TextLen + NewLen
      If BufferPosNew > BufferLen Then
        Buffer = Space$(BufferPosNew)
        BufferLen = Len(Buffer)
      End If
      
      'Ersetzung:
      ReadPos = 1
      WritePos = 1
      For Count = 1 To Count
        CopyLen = Start - ReadPos
        If CopyLen Then
          'Positionen berechnen:
          BufferPosNew = WritePos + CopyLen
          BufferPosNext = BufferPosNew + NewLen
          
          'Ggf. Buffer vergrößern:
          If BufferPosNext > BufferLen Then
            Buffer = Buffer & Space$(BufferPosNext)
            BufferLen = Len(Buffer)
          End If
          
          'String "patchen":
          Mid$(Buffer, WritePos) = Mid$(Text, ReadPos, CopyLen)
          Mid$(Buffer, BufferPosNew) = sNew
        Else
          'Position bestimmen:
          BufferPosNext = WritePos + NewLen
          
          'Ggf. Buffer vergrößern:
          If BufferPosNext > BufferLen Then
            Buffer = Buffer & Space$(BufferPosNext)
            BufferLen = Len(Buffer)
          End If
          
          'String "patchen":
          Mid$(Buffer, WritePos) = sNew
        End If
        WritePos = BufferPosNext
        ReadPos = Start + OldLen
        Start = InStr(ReadPos, Search, sOld)
        If Start = 0 Then Exit For
      Next Count
      
      'Ergebnis zusammenbauen:
      If ReadPos > TextLen Then
        Result = Left$(Buffer, WritePos - 1)
      Else
        BufferPosNext = WritePos + TextLen - ReadPos
        If BufferPosNext < BufferLen Then
          Mid$(Buffer, WritePos) = Mid$(Text, ReadPos)
          Result = Left$(Buffer, BufferPosNext)
        Else
          Result = Left$(Buffer, WritePos - 1) & Mid$(Text, ReadPos)
        End If
      End If
      Exit Sub
    
    End Select
  
  Else 'Kein Treffer:
    Result = Text
  End If

End Sub
Private Function ContainsOnly0(ByRef s As String) As Boolean

  Dim i As Long

  For i = 1 To Len(s)
    If Asc(Mid$(s, i, 1)) Then Exit Function
  Next i
  ContainsOnly0 = True

End Function
Public Function Replace(ByRef Text As String, _
    ByRef sOld As String, ByRef sNew As String, _
    Optional ByVal Start As Long = 1, _
    Optional ByVal Count As Long = 2147483647, _
    Optional ByVal Compare As VbCompareMethod = vbBinaryCompare _
  ) As String

  If LenB(sOld) = 0 Then

    'Suchstring ist leer:
    Replace = Text

  ElseIf ContainsOnly0(sOld) Then

    'Unicode-Problem, also kein LenB und co. verwenden:
    ReplaceBin0 Replace, Text, Text, sOld, sNew, Start, Count

  ElseIf Compare = vbBinaryCompare Then

    'Groß/Kleinschreibung unterscheiden:
    ReplaceBin Replace, Text, Text, sOld, sNew, Start, Count

  Else

    'Groß/Kleinschreibung ignorieren:
    ReplaceBin Replace, Text, LCase$(Text), LCase$(sOld), sNew, Start, Count

  End If

End Function
Public Sub ReplaceDo(ByRef Text As String, _
    ByRef sOld As String, ByRef sNew As String, _
    Optional ByVal Start As Long = 1, _
    Optional ByVal Count As Long = 2147483647, _
    Optional ByVal Compare As VbCompareMethod = vbBinaryCompare _
  )

  If LenB(sOld) = 0 Then

    'Suchstring ist leer: Nix machen!

  ElseIf ContainsOnly0(sOld) Then

    'Unicode-Problem, also kein LenB und co. verwenden:
    ReplaceBin0 Text, Text, Text, sOld, sNew, Start, Count

  ElseIf Compare = vbBinaryCompare Then

    'Groß/Kleinschreibung unterscheiden:
    If InStr(Start, Text, sOld, vbBinaryCompare) Then _
    ReplaceBin Text, Text, Text, sOld, sNew, Start, Count

  Else

    'Groß/Kleinschreibung ignorieren:
    If InStr(Start, Text, sOld, vbTextCompare) Then _
    ReplaceBin Text, Text, LCase$(Text), LCase$(sOld), sNew, Start, Count

  End If

End Sub
Public Function HTMLEncode(ByRef Text As String) As String

  Dim i As Long
  Dim Char As Integer

  'HTML-Spezies ersetzen:
  HTMLEncode = Text
  ReplaceDo HTMLEncode, "&", "&amp;"
  ReplaceDo HTMLEncode, """", "&quot;"
  ReplaceDo HTMLEncode, "<", "&lt;"
  ReplaceDo HTMLEncode, ">", "&gt;"

  'Sonderzeichen durch Asc-Code ersetzen:
  For i = Len(HTMLEncode) To 1 Step -1

    Char = Asc(Mid$(HTMLEncode, i, 1))
    Select Case Char: Case Is < 32, Is >= 160

      HTMLEncode _
        = Left$(HTMLEncode, i - 1) _
        & "&#" & Char & ";" _
        & Mid$(HTMLEncode, i + 1)

    End Select

  Next i

End Function

Public Function GetContentType(ByVal s As String) As String
  Dim hKey As Long
  Dim lpSubKey As String
  Dim lpValueName As String
  Dim lpType As Long
  Dim lpData As String
  Dim lpcbData As Long
  
  GetContentType = "application/octet-stream"
  lpSubKey = "Content Type"
  If RegOpenKeyEx(HKEY_CLASSES_ROOT, s, 0, KEY_QUERY_VALUE, hKey) = ERROR_SUCCESS Then
    RegQueryValueEx hKey, lpSubKey, 0, lpType, Chr(0), lpcbData
    If lpType = REG_SZ Then
      lpData = Space(lpcbData)
      If RegQueryValueEx(hKey, lpSubKey, 0, lpType, ByVal lpData, lpcbData) = ERROR_SUCCESS Then
        GetContentType = Left(lpData, lpcbData)
      End If
    End If
    RegCloseKey hKey
  End If
End Function

Public Function Encode64(ByVal iStr As String) As String
Dim iXml As New MSXML.DOMDocument
Dim iArray() As Byte

    With iXml.createElement("Encoder")
        .datatype = "bin.base64"
        ReDim iArray(LenB(iStr))
        CopyMemory iArray(0), ByVal StrPtr(iStr), LenB(iStr)

        .nodeTypedValue = iArray()
        Encode64 = .Text
    End With
Set iXml = Nothing
End Function
