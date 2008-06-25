/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: zh.js
 * 	Chinese Traditional language file.
 * 
 * File Authors:
 * 		Zak Fong (zakfong@yahoo.com.tw)
 */

var FCKLang =
{
// Language direction : "ltr" (left to right) or "rtl" (right to left).
Dir					: "ltr",

ToolbarCollapse		: "隱�?�?��?�",
ToolbarExpand		: "顯示�?��?�",

// Toolbar Items and Context Menu
Save				: "儲存",
NewPage				: "開新檔案",
Preview				: "�?覽",
Cut					: "剪下",
Copy				: "複製",
Paste				: "貼上",
PasteText			: "貼為純文字格�?",
PasteWord			: "自 Word 貼上",
Print				: "列�?�",
SelectAll			: "全�?�",
RemoveFormat		: "清除格�?",
InsertLinkLbl		: "超連�?",
InsertLink			: "�?�入/編輯超連�?",
RemoveLink			: "移除超連�?",
Anchor				: "�?�入/編輯錨點",
InsertImageLbl		: "影�?",
InsertImage			: "�?�入/編輯影�?",
InsertFlashLbl		: "Flash",
InsertFlash			: "�?�入/編輯 Flash",
InsertTableLbl		: "表格",
InsertTable			: "�?�入/編輯表格",
InsertLineLbl		: "水平線",
InsertLine			: "�?�入水平線",
InsertSpecialCharLbl: "特殊符號",
InsertSpecialChar	: "�?�入特殊符號",
InsertSmileyLbl		: "表情符號",
InsertSmiley		: "�?�入表情符號",
About				: "關於 FCKeditor",
Bold				: "粗體",
Italic				: "斜體",
Underline			: "底線",
StrikeThrough		: "刪除線",
Subscript			: "下標",
Superscript			: "上標",
LeftJustify			: "�?�左�?齊",
CenterJustify		: "置中",
RightJustify		: "�?��?��?齊",
BlockJustify		: "左�?��?齊",
DecreaseIndent		: "減少縮排",
IncreaseIndent		: "增加縮排",
Undo				: "復原",
Redo				: "�?複",
NumberedListLbl		: "編號清單",
NumberedList		: "�?�入/移除編號清單",
BulletedListLbl		: "項目清單",
BulletedList		: "�?�入/移除項目清單",
ShowTableBorders	: "顯示表格邊框",
ShowDetails			: "顯示詳細資料",
Style				: "樣�?",
FontFormat			: "格�?",
Font				: "字體",
FontSize			: "大�?",
TextColor			: "文字�?色",
BGColor				: "背景�?色",
Source				: "原始碼",
Find				: "尋找",
Replace				: "�?�代",
SpellCheck			: "拼字檢查",
UniversalKeyboard	: "�?�國�?�盤",
PageBreakLbl		: "分�?符號",
PageBreak			: "�?�入分�?符號",

Form			: "表單",
Checkbox		: "核�?�方塊",
RadioButton		: "�?�項按鈕",
TextField		: "文字方塊",
Textarea		: "文字�?�域",
HiddenField		: "隱�?欄�?",
Button			: "按鈕",
SelectionField	: "清單/�?�單",
ImageButton		: "影�?按鈕",

// Context Menu
EditLink			: "編輯超連�?",
InsertRow			: "�?�入列",
DeleteRows			: "刪除列",
InsertColumn		: "�?�入欄",
DeleteColumns		: "刪除欄",
InsertCell			: "�?�入儲存格",
DeleteCells			: "刪除儲存格",
MergeCells			: "�?�併儲存格",
SplitCell			: "分割儲存格",
TableDelete			: "刪除表格",
CellProperties		: "儲存格屬性",
TableProperties		: "表格屬性",
ImageProperties		: "影�?屬性",
FlashProperties		: "Flash 屬性",

AnchorProp			: "錨點屬性",
ButtonProp			: "按鈕屬性",
CheckboxProp		: "核�?�方塊屬性",
HiddenFieldProp		: "隱�?欄�?屬性",
RadioButtonProp		: "�?�項按鈕屬性",
ImageButtonProp		: "影�?按鈕屬性",
TextFieldProp		: "文字方塊屬性",
SelectionFieldProp	: "清單/�?�單屬性",
TextareaProp		: "文字�?�域屬性",
FormProp			: "表單屬性",

FontFormats			: "一般;格�?化;地�?�;標題 1;標題 2;標題 3;標題 4;標題 5;標題 6;段�?� (DIV)",

// Alerts and Messages
ProcessingXHTML		: "處�?� XHTML 中，請�?候…",
Done				: "完�?",
PasteWordConfirm	: "您想貼上的文字似乎是自 Word 複製而來，請�?您是�?��?先清除 Word 的格�?後�?行貼上？",
NotCompatiblePaste	: "此指令僅在 Internet Explorer 5.5 或以上的版本有效。請�?您是�?��?��?�?清除格�?�?�貼上？",
UnknownToolbarItem	: "未知工具列項目 \"%1\"",
UnknownCommand		: "未知指令�??稱 \"%1\"",
NotImplemented		: "尚未安�?此指令",
UnknownToolbarSet	: "工具列設定 \"%1\" �?存在",
NoActiveX			: "�?覽器的安全性設定�?制了本編輯器的�?些功能。您必須啟用安全性設定中的「執行ActiveX控制項與外掛程�?�?項目，�?�則本編輯器將會出�?�錯誤並缺少�?些功能",
BrowseServerBlocked : "無法開啟資�?�?覽器，請確定所有快顯視窗�?鎖程�?是�?�關閉",
DialogBlocked		: "無法開啟�?話視窗，請確定所有快顯視窗�?鎖程�?是�?�關閉",

// Dialogs
DlgBtnOK			: "確定",
DlgBtnCancel		: "�?�消",
DlgBtnClose			: "關閉",
DlgBtnBrowseServer	: "�?覽伺�?器端",
DlgAdvancedTag		: "進階",
DlgOpOther			: "&lt;其他&gt;",
DlgInfoTab			: "資訊",
DlgAlertUrl			: "請�?�入 URL",

// General Dialogs Labels
DlgGenNotSet		: "&lt;尚未設定&gt;",
DlgGenId			: "ID",
DlgGenLangDir		: "語言方�?�",
DlgGenLangDirLtr	: "由左而�?� (LTR)",
DlgGenLangDirRtl	: "由�?�而左 (RTL)",
DlgGenLangCode		: "語言代碼",
DlgGenAccessKey		: "存�?��?�",
DlgGenName			: "�??稱",
DlgGenTabIndex		: "定�?順�?",
DlgGenLongDescr		: "詳細 URL",
DlgGenClass			: "樣�?表類別",
DlgGenTitle			: "標題",
DlgGenContType		: "內容類型",
DlgGenLinkCharset	: "連�?資�?之編碼",
DlgGenStyle			: "樣�?",

// Image Dialog
DlgImgTitle			: "影�?屬性",
DlgImgInfoTab		: "影�?資訊",
DlgImgBtnUpload		: "上傳至伺�?器",
DlgImgURL			: "URL",
DlgImgUpload		: "上傳",
DlgImgAlt			: "替代文字",
DlgImgWidth			: "寬度",
DlgImgHeight		: "高度",
DlgImgLockRatio		: "等比例",
DlgBtnResetSize		: "�?設為原大�?",
DlgImgBorder		: "邊框",
DlgImgHSpace		: "水平�?離",
DlgImgVSpace		: "垂直�?離",
DlgImgAlign			: "�?齊",
DlgImgAlignLeft		: "�?�左�?齊",
DlgImgAlignAbsBottom: "絕�?下方",
DlgImgAlignAbsMiddle: "絕�?中間",
DlgImgAlignBaseline	: "基準線",
DlgImgAlignBottom	: "�?�下�?齊",
DlgImgAlignMiddle	: "置中�?齊",
DlgImgAlignRight	: "�?��?��?齊",
DlgImgAlignTextTop	: "文字上方",
DlgImgAlignTop		: "�?�上�?齊",
DlgImgPreview		: "�?覽",
DlgImgAlertUrl		: "請輸入影�? URL",
DlgImgLinkTab		: "超連�?",

// Flash Dialog
DlgFlashTitle		: "Flash 屬性",
DlgFlashChkPlay		: "自動播放",
DlgFlashChkLoop		: "�?複",
DlgFlashChkMenu		: "開啟�?�單",
DlgFlashScale		: "縮放",
DlgFlashScaleAll	: "全部顯示",
DlgFlashScaleNoBorder	: "無邊框",
DlgFlashScaleFit	: "精確符�?�",

// Link Dialog
DlgLnkWindowTitle	: "超連�?",
DlgLnkInfoTab		: "超連�?資訊",
DlgLnkTargetTab		: "目標",

DlgLnkType			: "超連接類型",
DlgLnkTypeURL		: "URL",
DlgLnkTypeAnchor	: "本�?錨點",
DlgLnkTypeEMail		: "電�?郵件",
DlgLnkProto			: "通訊�?�定",
DlgLnkProtoOther	: "&lt;其他&gt;",
DlgLnkURL			: "URL",
DlgLnkAnchorSel		: "請�?�擇錨點",
DlgLnkAnchorByName	: "�?錨點�??稱",
DlgLnkAnchorById	: "�?元件 ID",
DlgLnkNoAnchors		: "&lt;本文件尚無�?�用之錨點&gt;",
DlgLnkEMail			: "電�?郵件",
DlgLnkEMailSubject	: "郵件主旨",
DlgLnkEMailBody		: "郵件內容",
DlgLnkUpload		: "上傳",
DlgLnkBtnUpload		: "傳�?至伺�?器",

DlgLnkTarget		: "目標",
DlgLnkTargetFrame	: "&lt;框架&gt;",
DlgLnkTargetPopup	: "&lt;快顯視窗&gt;",
DlgLnkTargetBlank	: "新視窗 (_blank)",
DlgLnkTargetParent	: "父視窗 (_parent)",
DlgLnkTargetSelf	: "本視窗 (_self)",
DlgLnkTargetTop		: "最上層視窗 (_top)",
DlgLnkTargetFrameName	: "目標框架�??稱",
DlgLnkPopWinName	: "快顯視窗�??稱",
DlgLnkPopWinFeat	: "快顯視窗屬性",
DlgLnkPopResize		: "�?�調整大�?",
DlgLnkPopLocation	: "網�?�列",
DlgLnkPopMenu		: "�?�單列",
DlgLnkPopScroll		: "�?�軸",
DlgLnkPopStatus		: "狀態列",
DlgLnkPopToolbar	: "工具列",
DlgLnkPopFullScrn	: "全螢幕 (IE)",
DlgLnkPopDependent	: "從屬 (NS)",
DlgLnkPopWidth		: "寬",
DlgLnkPopHeight		: "高",
DlgLnkPopLeft		: "左",
DlgLnkPopTop		: "�?�",

DlnLnkMsgNoUrl		: "請輸入欲連�?的 URL",
DlnLnkMsgNoEMail	: "請輸入電�?郵件�?�?�",
DlnLnkMsgNoAnchor	: "請�?�擇錨點",

// Color Dialog
DlgColorTitle		: "請�?�擇�?色",
DlgColorBtnClear	: "清除",
DlgColorHighlight	: "�?覽",
DlgColorSelected	: "�?�擇",

// Smiley Dialog
DlgSmileyTitle		: "�?�入表情符號",

// Special Character Dialog
DlgSpecialCharTitle	: "請�?�擇特殊符號",

// Table Dialog
DlgTableTitle		: "表格屬性",
DlgTableRows		: "列數",
DlgTableColumns		: "欄數",
DlgTableBorder		: "邊框",
DlgTableAlign		: "�?齊",
DlgTableAlignNotSet	: "&lt;未設定&gt;",
DlgTableAlignLeft	: "�?�左�?齊",
DlgTableAlignCenter	: "置中",
DlgTableAlignRight	: "�?��?��?齊",
DlgTableWidth		: "寬度",
DlgTableWidthPx		: "�?素",
DlgTableWidthPc		: "百分比",
DlgTableHeight		: "高度",
DlgTableCellSpace	: "間�?",
DlgTableCellPad		: "內�?",
DlgTableCaption		: "標題",
DlgTableSummary		: "摘�?",

// Table Cell Dialog
DlgCellTitle		: "儲存格屬性",
DlgCellWidth		: "寬度",
DlgCellWidthPx		: "�?素",
DlgCellWidthPc		: "百分比",
DlgCellHeight		: "高度",
DlgCellWordWrap		: "自動�?�行",
DlgCellWordWrapNotSet	: "&lt;尚未設定&gt;",
DlgCellWordWrapYes	: "是",
DlgCellWordWrapNo	: "�?�",
DlgCellHorAlign		: "水平�?齊",
DlgCellHorAlignNotSet	: "&lt;尚未設定&gt;",
DlgCellHorAlignLeft	: "�?�左�?齊",
DlgCellHorAlignCenter	: "置中",
DlgCellHorAlignRight: "�?��?��?齊",
DlgCellVerAlign		: "垂直�?齊",
DlgCellVerAlignNotSet	: "&lt;尚未設定&gt;",
DlgCellVerAlignTop	: "�?�上�?齊",
DlgCellVerAlignMiddle	: "置中",
DlgCellVerAlignBottom	: "�?�下�?齊",
DlgCellVerAlignBaseline	: "基準線",
DlgCellRowSpan		: "�?�併列數",
DlgCellCollSpan		: "�?�併欄数",
DlgCellBackColor	: "背景�?色",
DlgCellBorderColor	: "邊框�?色",
DlgCellBtnSelect	: "請�?�擇…",

// Find Dialog
DlgFindTitle		: "尋找",
DlgFindFindBtn		: "尋找",
DlgFindNotFoundMsg	: "未找到指定的文字。",

// Replace Dialog
DlgReplaceTitle			: "�?�代",
DlgReplaceFindLbl		: "尋找:",
DlgReplaceReplaceLbl	: "�?�代:",
DlgReplaceCaseChk		: "大�?寫須相符",
DlgReplaceReplaceBtn	: "�?�代",
DlgReplaceReplAllBtn	: "全部�?�代",
DlgReplaceWordChk		: "全字相符",

// Paste Operations / Dialog
PasteErrorPaste	: "�?覽器的安全性設定�?�?許編輯器自動執行貼上動作。請使用快�?��?� (Ctrl+V) 貼上。",
PasteErrorCut	: "�?覽器的安全性設定�?�?許編輯器自動執行剪下動作。請使用快�?��?� (Ctrl+X) 剪下。",
PasteErrorCopy	: "�?覽器的安全性設定�?�?許編輯器自動執行複製動作。請使用快�?��?� (Ctrl+C) 複製。",

PasteAsText		: "貼為純文字格�?",
PasteFromWord	: "自 Word 貼上",

DlgPasteMsg2	: "請使用快�?��?� (<strong>Ctrl+V</strong>) 貼到下方�?�域中並按下 <strong>確定</strong>",
DlgPasteIgnoreFont		: "移除字型設定",
DlgPasteRemoveStyles	: "移除樣�?設定",
DlgPasteCleanBox		: "清除文字�?�域",


// Color Picker
ColorAutomatic	: "自動",
ColorMoreColors	: "更多�?色…",

// Document Properties
DocProps		: "文件屬性",

// Anchor Dialog
DlgAnchorTitle		: "命�??錨點",
DlgAnchorName		: "錨點�??稱",
DlgAnchorErrorName	: "請輸入錨點�??稱",

// Speller Pages Dialog
DlgSpellNotInDic		: "�?在字典中",
DlgSpellChangeTo		: "更改為",
DlgSpellBtnIgnore		: "忽略",
DlgSpellBtnIgnoreAll	: "全部忽略",
DlgSpellBtnReplace		: "�?�代",
DlgSpellBtnReplaceAll	: "全部�?�代",
DlgSpellBtnUndo			: "復原",
DlgSpellNoSuggestions	: "- 無建議值 -",
DlgSpellProgress		: "進行拼字檢查中…",
DlgSpellNoMispell		: "拼字檢查完�?：未發�?�拼字錯誤",
DlgSpellNoChanges		: "拼字檢查完�?：未更改任何單字",
DlgSpellOneChange		: "拼字檢查完�?：更改了 1 個單字",
DlgSpellManyChanges		: "拼字檢查完�?：更改了 %1 個單字",

IeSpellDownload			: "尚未安�?拼字檢查元件。您是�?�想�?�?�在下載？",

// Button Dialog
DlgButtonText	: "顯示文字 (值)",
DlgButtonType	: "類型",

// Checkbox and Radio Button Dialogs
DlgCheckboxName		: "�??稱",
DlgCheckboxValue	: "�?��?�值",
DlgCheckboxSelected	: "已�?��?�",

// Form Dialog
DlgFormName		: "�??稱",
DlgFormAction	: "動作",
DlgFormMethod	: "方法",

// Select Field Dialog
DlgSelectName		: "�??稱",
DlgSelectValue		: "�?��?�值",
DlgSelectSize		: "大�?",
DlgSelectLines		: "行",
DlgSelectChkMulti	: "�?�多�?�",
DlgSelectOpAvail	: "�?�用�?�項",
DlgSelectOpText		: "顯示文字",
DlgSelectOpValue	: "值",
DlgSelectBtnAdd		: "新增",
DlgSelectBtnModify	: "修改",
DlgSelectBtnUp		: "上移",
DlgSelectBtnDown	: "下移",
DlgSelectBtnSetValue : "設為�?設值",
DlgSelectBtnDelete	: "刪除",

// Textarea Dialog
DlgTextareaName	: "�??稱",
DlgTextareaCols	: "字元寬度",
DlgTextareaRows	: "列數",

// Text Field Dialog
DlgTextName			: "�??稱",
DlgTextValue		: "值",
DlgTextCharWidth	: "字元寬度",
DlgTextMaxChars		: "最多字元數",
DlgTextType			: "類型",
DlgTextTypeText		: "文字",
DlgTextTypePass		: "密碼",

// Hidden Field Dialog
DlgHiddenName	: "�??稱",
DlgHiddenValue	: "值",

// Bulleted List Dialog
BulletedListProp	: "項目清單屬性",
NumberedListProp	: "編號清單屬性",
DlgLstType			: "清單類型",
DlgLstTypeCircle	: "圓圈",
DlgLstTypeDisc		: "圓點",
DlgLstTypeSquare	: "方塊",
DlgLstTypeNumbers	: "數字 (1, 2, 3)",
DlgLstTypeLCase		: "�?寫字�? (a, b, c)",
DlgLstTypeUCase		: "大寫字�? (A, B, C)",
DlgLstTypeSRoman	: "�?寫羅馬數字 (i, ii, iii)",
DlgLstTypeLRoman	: "大寫羅馬數字 (I, II, III)",

// Document Properties Dialog
DlgDocGeneralTab	: "一般",
DlgDocBackTab		: "背景",
DlgDocColorsTab		: "顯色與邊界",
DlgDocMetaTab		: "Meta 資料",

DlgDocPageTitle		: "�?�?�標題",
DlgDocLangDir		: "語言方�?�",
DlgDocLangDirLTR	: "由左而�?� (LTR)",
DlgDocLangDirRTL	: "由�?�而左 (RTL)",
DlgDocLangCode		: "語言代碼",
DlgDocCharSet		: "字元編碼",
DlgDocCharSetOther	: "其他字元編碼",

DlgDocDocType		: "文件類型",
DlgDocDocTypeOther	: "其他文件類型",
DlgDocIncXHTML		: "包�?� XHTML 定義",
DlgDocBgColor		: "背景�?色",
DlgDocBgImage		: "背景影�?",
DlgDocBgNoScroll	: "浮水�?�",
DlgDocCText			: "文字",
DlgDocCLink			: "超連�?",
DlgDocCVisited		: "已�?覽�?�的超連�?",
DlgDocCActive		: "作用中的超連�?",
DlgDocMargins		: "�?�?�邊界",
DlgDocMaTop			: "上",
DlgDocMaLeft		: "左",
DlgDocMaRight		: "�?�",
DlgDocMaBottom		: "下",
DlgDocMeIndex		: "文件索引關�?�字 (用�?�形逗號[,]分隔)",
DlgDocMeDescr		: "文件說明",
DlgDocMeAuthor		: "作者",
DlgDocMeCopy		: "版權所有",
DlgDocPreview		: "�?覽",

// Templates Dialog
Templates			: "樣版",
DlgTemplatesTitle	: "內容樣版",
DlgTemplatesSelMsg	: "請�?�擇欲開啟的樣版<br> (原有的內容將會被清除):",
DlgTemplatesLoading	: "讀�?�樣版清單中，請�?候…",
DlgTemplatesNoTpl	: "(無樣版)",

// About Dialog
DlgAboutAboutTab	: "關於",
DlgAboutBrowserInfoTab	: "�?覽器資訊",
DlgAboutVersion		: "版本",
DlgAboutLicense		: "�?據 GNU 較寬鬆公共許�?�證(LGPL)發佈",
DlgAboutInfo		: "想�?�得更多資訊請至 "
}