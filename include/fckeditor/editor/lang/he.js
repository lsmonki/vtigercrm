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
 * File Name: he.js
 * 	Hebrew language file.
 * 
 * File Authors:
 * 		Tamir Mordo (tamir@tetitu.co.il)
 * 		Ophir Radnitz (ophir@liqweed.net)
 */

var FCKLang =
{
// Language direction : "ltr" (left to right) or "rtl" (right to left).
Dir					: "rtl",

ToolbarCollapse		: "כיווץ סרגל הכלי�?",
ToolbarExpand		: "פתיחת סרגל הכלי�?",

// Toolbar Items and Context Menu
Save				: "שמירה",
NewPage				: "דף חדש",
Preview				: "תצוגה מקדימה",
Cut					: "גזירה",
Copy				: "העתקה",
Paste				: "הדבקה",
PasteText			: "הדבקה כטקסט פשוט",
PasteWord			: "הדבקה מ-Word",
Print				: "הדפסה",
SelectAll			: "בחירת הכל",
RemoveFormat		: "הסרת העיצוב",
InsertLinkLbl		: "קישור",
InsertLink			: "הוספת/עריכת קישור",
RemoveLink			: "הסרת הקישור",
Anchor				: "הוספת/עריכת נקודת עיגון",
InsertImageLbl		: "תמונה",
InsertImage			: "הוספת/עריכת תמונה",
InsertFlashLbl		: "פל�?ש",
InsertFlash			: "הוסף/ערוך פל�?ש",
InsertTableLbl		: "טבלה",
InsertTable			: "הוספת/עריכת טבלה",
InsertLineLbl		: "קו",
InsertLine			: "הוספת קו �?ופקי",
InsertSpecialCharLbl: "תו מיוחד",
InsertSpecialChar	: "הוספת תו מיוחד",
InsertSmileyLbl		: "סמיילי",
InsertSmiley		: "הוספת סמיילי",
About				: "�?ודות FCKeditor",
Bold				: "מודגש",
Italic				: "נטוי",
Underline			: "קו תחתון",
StrikeThrough		: "כתיב מחוק",
Subscript			: "כתיב תחתון",
Superscript			: "כתיב עליון",
LeftJustify			: "יישור לשמ�?ל",
CenterJustify		: "מרכוז",
RightJustify		: "יישור לימין",
BlockJustify		: "יישור לשוליי�?",
DecreaseIndent		: "הקטנת �?ינדנטציה",
IncreaseIndent		: "הגדלת �?ינדנטציה",
Undo				: "ביטול צעד �?חרון",
Redo				: "חזרה על צעד �?חרון",
NumberedListLbl		: "רשימה ממוספרת",
NumberedList		: "הוספת/הסרת רשימה ממוספרת",
BulletedListLbl		: "רשימת נקודות",
BulletedList		: "הוספת/הסרת רשימת נקודות",
ShowTableBorders	: "הצגת מסגרת הטבלה",
ShowDetails			: "הצגת פרטי�?",
Style				: "סגנון",
FontFormat			: "עיצוב",
Font				: "גופן",
FontSize			: "גודל",
TextColor			: "צבע טקסט",
BGColor				: "צבע רקע",
Source				: "מקור",
Find				: "חיפוש",
Replace				: "החלפה",
SpellCheck			: "בדיקת �?יות",
UniversalKeyboard	: "מקלדת �?וניברסלית",
PageBreakLbl		: "Page Break",	//MISSING
PageBreak			: "Insert Page Break",	//MISSING

Form			: "טופס",
Checkbox		: "תיבת סימון",
RadioButton		: "לחצן �?פשרויות",
TextField		: "שדה טקסט",
Textarea		: "�?יזור טקסט",
HiddenField		: "שדה חבוי",
Button			: "כפתור",
SelectionField	: "שדה בחירה",
ImageButton		: "כפתור תמונה",

// Context Menu
EditLink			: "עריכת קישור",
InsertRow			: "הוספת שורה",
DeleteRows			: "מחיקת שורות",
InsertColumn		: "הוספת עמודה",
DeleteColumns		: "מחיקת עמודות",
InsertCell			: "הוספת ת�?",
DeleteCells			: "מחיקת ת�?י�?",
MergeCells			: "מיזוג ת�?י�?",
SplitCell			: "פיצול ת�?י�?",
TableDelete			: "Delete Table",	//MISSING
CellProperties		: "תכונות הת�?",
TableProperties		: "תכונות הטבלה",
ImageProperties		: "תכונות התמונה",
FlashProperties		: "מ�?פייני פל�?ש",

AnchorProp			: "מ�?פייני נקודת עיגון",
ButtonProp			: "מ�?פייני כפתור",
CheckboxProp		: "מ�?פייני תיבת סימון",
HiddenFieldProp		: "מ�?פיני שדה חבוי",
RadioButtonProp		: "מ�?פייני לחצן �?פשרויות",
ImageButtonProp		: "מ�?פיני כפתור תמונה",
TextFieldProp		: "מ�?פייני שדה טקסט",
SelectionFieldProp	: "מ�?פייני שדה בחירה",
TextareaProp		: "מ�?פיני �?יזור טקסט",
FormProp			: "מ�?פיני טופס",

FontFormats			: "נורמלי;קוד;כתובת;כותרת;כותרת 2;כותרת 3;כותרת 4;כותרת 5;כותרת 6",

// Alerts and Messages
ProcessingXHTML		: "מעבד XHTML, נ�? להמתין...",
Done				: "המשימה הושלמה",
PasteWordConfirm	: "נר�?ה הטקסט שבכוונתך להדביק מקורו בקובץ Word. ה�?�? ברצונך לנקות �?ותו טר�? ההדבקה?",
NotCompatiblePaste	: "פעולה זו זמינה לדפדפן Internet Explorer מגירס�? 5.5 ומעלה. ה�?�? להמשיך בהדבקה לל�? הניקוי?",
UnknownToolbarItem	: "פריט ל�? ידוע בסרגל הכלי�? \"%1\"",
UnknownCommand		: "ש�? פעולה ל�? ידוע \"%1\"",
NotImplemented		: "הפקודה ל�? מיושמת",
UnknownToolbarSet	: "ערכת סרגל הכלי�? \"%1\" ל�? קיימת",
NoActiveX			: "You browser's security settings could limit some features of the editor. You must enable the option \"Run ActiveX controls and plug-ins\". You may experience errors and notice missing features.",	//MISSING
BrowseServerBlocked : "The resources browser could not be opened. Make sure that all popup blockers are disabled.",	//MISSING
DialogBlocked		: "It was not possible to open the dialog window. Make sure all popup blockers are disabled.",	//MISSING

// Dialogs
DlgBtnOK			: "�?ישור",
DlgBtnCancel		: "ביטול",
DlgBtnClose			: "סגירה",
DlgBtnBrowseServer	: "סייר השרת",
DlgAdvancedTag		: "�?פשרויות מתקדמות",
DlgOpOther			: "&lt;�?חר&gt;",
DlgInfoTab			: "מידע",
DlgAlertUrl			: "�?נה הזן URL",

// General Dialogs Labels
DlgGenNotSet		: "&lt;ל�? נקבע&gt;",
DlgGenId			: "זיהוי (Id)",
DlgGenLangDir		: "כיוון שפה",
DlgGenLangDirLtr	: "שמ�?ל לימין (LTR)",
DlgGenLangDirRtl	: "ימין לשמ�?ל (RTL)",
DlgGenLangCode		: "קוד שפה",
DlgGenAccessKey		: "מקש גישה",
DlgGenName			: "ש�?",
DlgGenTabIndex		: "מספר ט�?ב",
DlgGenLongDescr		: "קישור לתי�?ור מפורט",
DlgGenClass			: "גיליונות עיצוב קבוצות",
DlgGenTitle			: "כותרת מוצעת",
DlgGenContType		: "Content Type מוצע",
DlgGenLinkCharset	: "קידוד המש�?ב המקושר",
DlgGenStyle			: "סגנון",

// Image Dialog
DlgImgTitle			: "תכונות התמונה",
DlgImgInfoTab		: "מידע על התמונה",
DlgImgBtnUpload		: "שליחה לשרת",
DlgImgURL			: "כתובת (URL)",
DlgImgUpload		: "העל�?ה",
DlgImgAlt			: "טקסט חלופי",
DlgImgWidth			: "רוחב",
DlgImgHeight		: "גובה",
DlgImgLockRatio		: "נעילת היחס",
DlgBtnResetSize		: "�?יפוס הגודל",
DlgImgBorder		: "מסגרת",
DlgImgHSpace		: "מרווח �?ופקי",
DlgImgVSpace		: "מרווח �?נכי",
DlgImgAlign			: "יישור",
DlgImgAlignLeft		: "לשמ�?ל",
DlgImgAlignAbsBottom: "לתחתית ה�?בסולוטית",
DlgImgAlignAbsMiddle: "מרכוז �?בסולוטי",
DlgImgAlignBaseline	: "לקו התחתית",
DlgImgAlignBottom	: "לתחתית",
DlgImgAlignMiddle	: "ל�?מצע",
DlgImgAlignRight	: "לימין",
DlgImgAlignTextTop	: "לר�?ש הטקסט",
DlgImgAlignTop		: "למעלה",
DlgImgPreview		: "תצוגה מקדימה",
DlgImgAlertUrl		: "נ�? להקליד �?ת כתובת התמונה",
DlgImgLinkTab		: "קישור",

// Flash Dialog
DlgFlashTitle		: "מ�?פיני פל�?ש",
DlgFlashChkPlay		: "נגן �?וטומטי",
DlgFlashChkLoop		: "לול�?ה",
DlgFlashChkMenu		: "�?פשר תפריט פל�?ש",
DlgFlashScale		: "גודל",
DlgFlashScaleAll	: "הצג הכל",
DlgFlashScaleNoBorder	: "לל�? גבולות",
DlgFlashScaleFit	: "הת�?מה מושלמת",

// Link Dialog
DlgLnkWindowTitle	: "קישור",
DlgLnkInfoTab		: "מידע על הקישור",
DlgLnkTargetTab		: "מטרה",

DlgLnkType			: "סוג קישור",
DlgLnkTypeURL		: "כתובת (URL)",
DlgLnkTypeAnchor	: "עוגן בעמוד זה",
DlgLnkTypeEMail		: "דו�?''ל",
DlgLnkProto			: "פרוטוקול",
DlgLnkProtoOther	: "&lt;�?חר&gt;",
DlgLnkURL			: "כתובת (URL)",
DlgLnkAnchorSel		: "בחירת עוגן",
DlgLnkAnchorByName	: "עפ''י ש�? העוגן",
DlgLnkAnchorById	: "עפ''י זיהוי (Id) הרכיב",
DlgLnkNoAnchors		: "&lt;�?ין עוגני�? זמיני�? בדף&gt;",
DlgLnkEMail			: "כתובת הדו�?''ל",
DlgLnkEMailSubject	: "נוש�? ההודעה",
DlgLnkEMailBody		: "גוף ההודעה",
DlgLnkUpload		: "העל�?ה",
DlgLnkBtnUpload		: "שליחה לשרת",

DlgLnkTarget		: "מטרה",
DlgLnkTargetFrame	: "&lt;frame&gt;",
DlgLnkTargetPopup	: "&lt;חלון קופץ&gt;",
DlgLnkTargetBlank	: "חלון חדש (_blank)",
DlgLnkTargetParent	: "חלון ה�?ב (_parent)",
DlgLnkTargetSelf	: "ב�?ותו החלון (_self)",
DlgLnkTargetTop		: "חלון ר�?שי (_top)",
DlgLnkTargetFrameName	: "ש�? frame היעד",
DlgLnkPopWinName	: "ש�? החלון הקופץ",
DlgLnkPopWinFeat	: "תכונות החלון הקופץ",
DlgLnkPopResize		: "בעל גודל ניתן לשינוי",
DlgLnkPopLocation	: "סרגל כתובת",
DlgLnkPopMenu		: "סרגל תפריט",
DlgLnkPopScroll		: "ניתן לגלילה",
DlgLnkPopStatus		: "סרגל חיווי",
DlgLnkPopToolbar	: "סרגל הכלי�?",
DlgLnkPopFullScrn	: "מסך מל�? (IE)",
DlgLnkPopDependent	: "תלוי (Netscape)",
DlgLnkPopWidth		: "רוחב",
DlgLnkPopHeight		: "גובה",
DlgLnkPopLeft		: "מיקו�? צד שמ�?ל",
DlgLnkPopTop		: "מיקו�? צד עליון",

DlnLnkMsgNoUrl		: "נ�? להקליד �?ת כתובת הקישור (URL)",
DlnLnkMsgNoEMail	: "נ�? להקליד �?ת כתובת הדו�?''ל",
DlnLnkMsgNoAnchor	: "נ�? לבחור עוגן במסמך",

// Color Dialog
DlgColorTitle		: "בחירת צבע",
DlgColorBtnClear	: "�?יפוס",
DlgColorHighlight	: "נוכחי",
DlgColorSelected	: "נבחר",

// Smiley Dialog
DlgSmileyTitle		: "הוספת סמיילי",

// Special Character Dialog
DlgSpecialCharTitle	: "בחירת תו מיוחד",

// Table Dialog
DlgTableTitle		: "תכונות טבלה",
DlgTableRows		: "שורות",
DlgTableColumns		: "עמודות",
DlgTableBorder		: "גודל מסגרת",
DlgTableAlign		: "יישור",
DlgTableAlignNotSet	: "<ל�? נקבע>",
DlgTableAlignLeft	: "שמ�?ל",
DlgTableAlignCenter	: "מרכז",
DlgTableAlignRight	: "ימין",
DlgTableWidth		: "רוחב",
DlgTableWidthPx		: "פיקסלי�?",
DlgTableWidthPc		: "�?חוז",
DlgTableHeight		: "גובה",
DlgTableCellSpace	: "מרווח ת�?",
DlgTableCellPad		: "ריפוד ת�?",
DlgTableCaption		: "כיתוב",
DlgTableSummary		: "Summary",	//MISSING

// Table Cell Dialog
DlgCellTitle		: "תכונות ת�?",
DlgCellWidth		: "רוחב",
DlgCellWidthPx		: "פיקסלי�?",
DlgCellWidthPc		: "�?חוז",
DlgCellHeight		: "גובה",
DlgCellWordWrap		: "גלילת שורות",
DlgCellWordWrapNotSet	: "<ל�? נקבע>",
DlgCellWordWrapYes	: "כן",
DlgCellWordWrapNo	: "ל�?",
DlgCellHorAlign		: "יישור �?ופקי",
DlgCellHorAlignNotSet	: "<ל�? נקבע>",
DlgCellHorAlignLeft	: "שמ�?ל",
DlgCellHorAlignCenter	: "מרכז",
DlgCellHorAlignRight: "ימין",
DlgCellVerAlign		: "יישור �?נכי",
DlgCellVerAlignNotSet	: "<ל�? נקבע>",
DlgCellVerAlignTop	: "למעלה",
DlgCellVerAlignMiddle	: "ל�?מצע",
DlgCellVerAlignBottom	: "לתחתית",
DlgCellVerAlignBaseline	: "קו תחתית",
DlgCellRowSpan		: "טווח שורות",
DlgCellCollSpan		: "טווח עמודות",
DlgCellBackColor	: "צבע רקע",
DlgCellBorderColor	: "צבע מסגרת",
DlgCellBtnSelect	: "בחירה...",

// Find Dialog
DlgFindTitle		: "חיפוש",
DlgFindFindBtn		: "חיפוש",
DlgFindNotFoundMsg	: "הטקסט המבוקש ל�? נמצ�?.",

// Replace Dialog
DlgReplaceTitle			: "החלפה",
DlgReplaceFindLbl		: "חיפוש מחרוזת:",
DlgReplaceReplaceLbl	: "החלפה במחרוזת:",
DlgReplaceCaseChk		: "הת�?מת סוג �?ותיות (Case)",
DlgReplaceReplaceBtn	: "החלפה",
DlgReplaceReplAllBtn	: "החלפה בכל העמוד",
DlgReplaceWordChk		: "הת�?מה למילה המל�?ה",

// Paste Operations / Dialog
PasteErrorPaste	: "הגדרות ה�?בטחה בדפדפן שלך ל�? מ�?פשרות לעורך לבצע פעולות הדבקה �?וטומטיות. יש להשתמש במקלדת לש�? כך (Ctrl+V).",
PasteErrorCut	: "הגדרות ה�?בטחה בדפדפן שלך ל�? מ�?פשרות לעורך לבצע פעולות גזירה  �?וטומטיות. יש להשתמש במקלדת לש�? כך (Ctrl+X).",
PasteErrorCopy	: "הגדרות ה�?בטחה בדפדפן שלך ל�? מ�?פשרות לעורך לבצע פעולות העתקה �?וטומטיות. יש להשתמש במקלדת לש�? כך (Ctrl+C).",

PasteAsText		: "הדבקה כטקסט פשוט",
PasteFromWord	: "הדבקה מ-Word",

DlgPasteMsg2	: "Please paste inside the following box using the keyboard (<STRONG>Ctrl+V</STRONG>) and hit <STRONG>OK</STRONG>.",
DlgPasteIgnoreFont		: "התעל�? מהגדרות סוג פונט",
DlgPasteRemoveStyles	: "הסר הגדרות סגנון",
DlgPasteCleanBox		: "קופסת ניקוי",


// Color Picker
ColorAutomatic	: "�?וטומטי",
ColorMoreColors	: "צבעי�? נוספי�?...",

// Document Properties
DocProps		: "מ�?פיני מסמך",

// Anchor Dialog
DlgAnchorTitle		: "מ�?פיני נקודת עיגון",
DlgAnchorName		: "ש�? לנקודת עיגון",
DlgAnchorErrorName	: "�?נ�? הזן ש�? לנקודת עיגון",

// Speller Pages Dialog
DlgSpellNotInDic		: "ל�? נמצ�? במילון",
DlgSpellChangeTo		: "שנה ל",
DlgSpellBtnIgnore		: "התעל�?",
DlgSpellBtnIgnoreAll	: "התעל�? מהכל",
DlgSpellBtnReplace		: "החלף",
DlgSpellBtnReplaceAll	: "החלף הכל",
DlgSpellBtnUndo			: "Undo",
DlgSpellNoSuggestions	: "- �?ין הצעות -",
DlgSpellProgress		: "בדיקות �?יות בתהליך ....",
DlgSpellNoMispell		: "בדיקות �?יות הסתיימה: ל�? נמצ�?ו שגיעות כתיב",
DlgSpellNoChanges		: "בדיקות �?יות הסתיימה: ל�? שונתה �?ף מילה",
DlgSpellOneChange		: "בדיקות �?יות הסתיימה: שונתה מילה �?חת",
DlgSpellManyChanges		: "בדיקות �?יות הסתיימה: %1 מילי�? שונו",

IeSpellDownload			: "בודק ה�?יות ל�? מותקן, ה�?�? �?תה מעוניין להוריד?",

// Button Dialog
DlgButtonText	: "טקסט (ערך)",
DlgButtonType	: "סוג",

// Checkbox and Radio Button Dialogs
DlgCheckboxName		: "ש�?",
DlgCheckboxValue	: "ערך",
DlgCheckboxSelected	: "בחור",

// Form Dialog
DlgFormName		: "ש�?",
DlgFormAction	: "שלח �?ל",
DlgFormMethod	: "סוג שליחה",

// Select Field Dialog
DlgSelectName		: "ש�?",
DlgSelectValue		: "ערך",
DlgSelectSize		: "גודל",
DlgSelectLines		: "שורות",
DlgSelectChkMulti	: "�?פשר בחירות מרובות",
DlgSelectOpAvail	: "�?פשרויות זמינות",
DlgSelectOpText		: "טקסט",
DlgSelectOpValue	: "ערך",
DlgSelectBtnAdd		: "הוסף",
DlgSelectBtnModify	: "שנה",
DlgSelectBtnUp		: "למעלה",
DlgSelectBtnDown	: "למטה",
DlgSelectBtnSetValue : "קבע כברירת מחדל",
DlgSelectBtnDelete	: "מחק",

// Textarea Dialog
DlgTextareaName	: "ש�?",
DlgTextareaCols	: "עמודות",
DlgTextareaRows	: "שורות",

// Text Field Dialog
DlgTextName			: "ש�?",
DlgTextValue		: "ערך",
DlgTextCharWidth	: "רוחב ב�?ותיות",
DlgTextMaxChars		: "מקסימות �?ותיות",
DlgTextType			: "סוג",
DlgTextTypeText		: "טקסט",
DlgTextTypePass		: "סיסמה",

// Hidden Field Dialog
DlgHiddenName	: "ש�?",
DlgHiddenValue	: "ערך",

// Bulleted List Dialog
BulletedListProp	: "מ�?פייני רשימה",
NumberedListProp	: "מ�?פייני רשימה ממוספרת",
DlgLstType			: "סוג",
DlgLstTypeCircle	: "עיגול",
DlgLstTypeDisc		: "Disc",	//MISSING
DlgLstTypeSquare	: "מרובע",
DlgLstTypeNumbers	: "מספרי�? (1, 2, 3)",
DlgLstTypeLCase		: "�?ותיות קטנות (a, b, c)",
DlgLstTypeUCase		: "�?ותיות גדולות (A, B, C)",
DlgLstTypeSRoman	: "ספרות רומ�?יות קטנות (i, ii, iii)",
DlgLstTypeLRoman	: "ספרות רומ�?יות גדולות (I, II, III)",

// Document Properties Dialog
DlgDocGeneralTab	: "כללי",
DlgDocBackTab		: "רקע",
DlgDocColorsTab		: "צבעי�? וגבולות",
DlgDocMetaTab		: "נתוני META",

DlgDocPageTitle		: "כותרת דף",
DlgDocLangDir		: "כיוון שפה",
DlgDocLangDirLTR	: "שמ�?ל לימין (LTR)",
DlgDocLangDirRTL	: "ימין לשמ�?ל (RTL)",
DlgDocLangCode		: "קוד שפה",
DlgDocCharSet		: "קידוד �?ותיות",
DlgDocCharSetOther	: "קידוד �?ותיות �?חר",

DlgDocDocType		: "הגדרות סוג מסמך",
DlgDocDocTypeOther	: "הגדרות סוג מסמך �?חרות",
DlgDocIncXHTML		: "כלול הגדרות XHTML",
DlgDocBgColor		: "צבע רקע",
DlgDocBgImage		: "URL לתמונת רקע",
DlgDocBgNoScroll	: "רגע לל�? גלילה",
DlgDocCText			: "טקסט",
DlgDocCLink			: "קישור",
DlgDocCVisited		: "קישור שבוקר",
DlgDocCActive		: " קישור פעיל",
DlgDocMargins		: "גבולות דף",
DlgDocMaTop			: "למעלה",
DlgDocMaLeft		: "שמ�?לה",
DlgDocMaRight		: "ימינה",
DlgDocMaBottom		: "למטה",
DlgDocMeIndex		: "מפתח ענייני�? של המסמך )מופרד בפסיק(",
DlgDocMeDescr		: "ת�?ור מסמך",
DlgDocMeAuthor		: "מחבר",
DlgDocMeCopy		: "זכויות יוצרי�?",
DlgDocPreview		: "תצוגה מקדימה",

// Templates Dialog
Templates			: "תבניות",
DlgTemplatesTitle	: "תביות תוכן",
DlgTemplatesSelMsg	: "�?נ�? בחר תבנית לפתיחה בעורך <BR>התוכן המקורי ימחק:",
DlgTemplatesLoading	: "מעלה רשימת תבניות �?נ�? המתן",
DlgTemplatesNoTpl	: "(ל�? הוגדרו תבניות)",

// About Dialog
DlgAboutAboutTab	: "�?ודות",
DlgAboutBrowserInfoTab	: "גירסת דפדפן",
DlgAboutVersion		: "גירס�?",
DlgAboutLicense		: "ברשיון תחת תנ�?י GNU Lesser General Public License",
DlgAboutInfo		: "מידע נוסף ניתן למצו�? כ�?ן:"
}