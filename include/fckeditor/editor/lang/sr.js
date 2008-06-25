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
 * File Name: sr.js
 * 	Serbian (Cyrillic) language file.
 * 
 * File Authors:
 * 		Zoran Subić (zoran@tf.zr.ac.yu)
 */

var FCKLang =
{
// Language direction : "ltr" (left to right) or "rtl" (right to left).
Dir					: "ltr",

ToolbarCollapse		: "Смањи линију �?а алаткама",
ToolbarExpand		: "Прошири линију �?а алаткама",

// Toolbar Items and Context Menu
Save				: "Сачувај",
NewPage				: "�?ова �?траница",
Preview				: "Изглед �?транице",
Cut					: "И�?еци",
Copy				: "Копирај",
Paste				: "Залепи",
PasteText			: "Залепи као неформатиран тек�?т",
PasteWord			: "Залепи из Worda",
Print				: "Штампа",
SelectAll			: "Означи �?ве",
RemoveFormat		: "Уклони форматирање",
InsertLinkLbl		: "Линк",
InsertLink			: "Уне�?и/измени линк",
RemoveLink			: "Уклони линк",
Anchor				: "Уне�?и/измени �?идро",
InsertImageLbl		: "Слика",
InsertImage			: "Уне�?и/измени �?лику",
InsertFlashLbl		: "Флеш елемент",
InsertFlash			: "Уне�?и/измени флеш",
InsertTableLbl		: "Табела",
InsertTable			: "Уне�?и/измени табелу",
InsertLineLbl		: "Линија",
InsertLine			: "Уне�?и хоризонталну линију",
InsertSpecialCharLbl: "Специјални карактери",
InsertSpecialChar	: "Уне�?и �?пецијални карактер",
InsertSmileyLbl		: "Смајли",
InsertSmiley		: "Уне�?и �?мајлија",
About				: "О ФЦКедитору",
Bold				: "Подебљано",
Italic				: "Курзив",
Underline			: "Подвучено",
StrikeThrough		: "Прецртано",
Subscript			: "Индек�?",
Superscript			: "Степен",
LeftJustify			: "Лево равнање",
CenterJustify		: "Центриран тек�?т",
RightJustify		: "Де�?но равнање",
BlockJustify		: "Обо�?трано равнање",
DecreaseIndent		: "Смањи леву маргину",
IncreaseIndent		: "Увећај леву маргину",
Undo				: "Поништи акцију",
Redo				: "Понови акцију",
NumberedListLbl		: "�?абројиву ли�?ту",
NumberedList		: "Уне�?и/уклони набројиву ли�?ту",
BulletedListLbl		: "�?енабројива ли�?та",
BulletedList		: "Уне�?и/уклони ненабројиву ли�?ту",
ShowTableBorders	: "Прикажи оквир табеле",
ShowDetails			: "Прикажи детаље",
Style				: "Стил",
FontFormat			: "Формат",
Font				: "Фонт",
FontSize			: "Величина фонта",
TextColor			: "Боја тек�?та",
BGColor				: "Боја позадине",
Source				: "K&ocirc;д",
Find				: "Претрага",
Replace				: "Замена",
SpellCheck			: "Провери �?пеловање",
UniversalKeyboard	: "Универзална та�?татура",
PageBreakLbl		: "Page Break",	//MISSING
PageBreak			: "Insert Page Break",	//MISSING

Form			: "Форма",
Checkbox		: "Поље за потврду",
RadioButton		: "Радио-дугме",
TextField		: "Тек�?туално поље",
Textarea		: "Зона тек�?та",
HiddenField		: "Скривено поље",
Button			: "Дугме",
SelectionField	: "Изборно поље",
ImageButton		: "Дугме �?а �?ликом",

// Context Menu
EditLink			: "Промени линк",
InsertRow			: "Уне�?и ред",
DeleteRows			: "Обриши редове",
InsertColumn		: "Уне�?и колону",
DeleteColumns		: "Обриши колоне",
InsertCell			: "Уне�?и ћелије",
DeleteCells			: "Обриши ћелије",
MergeCells			: "Спој ћелије",
SplitCell			: "Раздвоји ћелије",
TableDelete			: "Delete Table",	//MISSING
CellProperties		: "О�?обине ћелије",
TableProperties		: "О�?обине табеле",
ImageProperties		: "О�?обине �?лике",
FlashProperties		: "О�?обине Флеша",

AnchorProp			: "О�?обине �?идра",
ButtonProp			: "О�?обине дугмета",
CheckboxProp		: "О�?обине поља за потврду",
HiddenFieldProp		: "О�?обине �?кривеног поља",
RadioButtonProp		: "О�?обине радио-дугмета",
ImageButtonProp		: "О�?обине дугмета �?а �?ликом",
TextFieldProp		: "О�?обине тек�?туалног поља",
SelectionFieldProp	: "О�?обине изборног поља",
TextareaProp		: "О�?обине зоне тек�?та",
FormProp			: "О�?обине форме",

FontFormats			: "Normal;Formatirano;Adresa;Heading 1;Heading 2;Heading 3;Heading 4;Heading 5;Heading 6",

// Alerts and Messages
ProcessingXHTML		: "Обрађујем XHTML. Maлo �?трпљења...",
Done				: "Завршио",
PasteWordConfirm	: "Тек�?т који желите да налепите копиран је из Worda. Да ли желите да буде очишћен од формата пре лепљења?",
NotCompatiblePaste	: "Ова команда је до�?тупна �?амо за Интернет Екплорер од верзије 5.5. Да ли желите да налепим тек�?т без чишћења?",
UnknownToolbarItem	: "�?епозната �?тавка toolbara \"%1\"",
UnknownCommand		: "�?епозната наредба \"%1\"",
NotImplemented		: "�?аредба није имплементирана",
UnknownToolbarSet	: "Toolbar \"%1\" не по�?тоји",
NoActiveX			: "You browser's security settings could limit some features of the editor. You must enable the option \"Run ActiveX controls and plug-ins\". You may experience errors and notice missing features.",	//MISSING
BrowseServerBlocked : "The resources browser could not be opened. Make sure that all popup blockers are disabled.",	//MISSING
DialogBlocked		: "It was not possible to open the dialog window. Make sure all popup blockers are disabled.",	//MISSING

// Dialogs
DlgBtnOK			: "OK",
DlgBtnCancel		: "Oткажи",
DlgBtnClose			: "Затвори",
DlgBtnBrowseServer	: "Претражи �?ервер",
DlgAdvancedTag		: "�?апредни тагови",
DlgOpOther			: "&lt;О�?тали&gt;",
DlgInfoTab			: "Инфо",
DlgAlertUrl			: "Молимо Ва�?, уне�?ите УРЛ",

// General Dialogs Labels
DlgGenNotSet		: "&lt;није по�?тављено&gt;",
DlgGenId			: "Ид",
DlgGenLangDir		: "Смер језика",
DlgGenLangDirLtr	: "С лева на де�?но (LTR)",
DlgGenLangDirRtl	: "С де�?на на лево (RTL)",
DlgGenLangCode		: "K&ocirc;д језика",
DlgGenAccessKey		: "При�?тупни та�?тер",
DlgGenName			: "�?азив",
DlgGenTabIndex		: "Таб индек�?",
DlgGenLongDescr		: "Пун опи�? УРЛ",
DlgGenClass			: "Stylesheet кла�?е",
DlgGenTitle			: "Advisory на�?лов",
DlgGenContType		: "Advisory вр�?та �?адржаја",
DlgGenLinkCharset	: "Linked Resource Charset",
DlgGenStyle			: "Стил",

// Image Dialog
DlgImgTitle			: "О�?обине �?лика",
DlgImgInfoTab		: "Инфо �?лике",
DlgImgBtnUpload		: "Пошаљи на �?ервер",
DlgImgURL			: "УРЛ",
DlgImgUpload		: "Пошаљи",
DlgImgAlt			: "�?лтернативни тек�?т",
DlgImgWidth			: "Ширина",
DlgImgHeight		: "Ви�?ина",
DlgImgLockRatio		: "Закључај одно�?",
DlgBtnResetSize		: "Ре�?етуј величину",
DlgImgBorder		: "Оквир",
DlgImgHSpace		: "HSpace",
DlgImgVSpace		: "VSpace",
DlgImgAlign			: "Равнање",
DlgImgAlignLeft		: "Лево",
DlgImgAlignAbsBottom: "Abs доле",
DlgImgAlignAbsMiddle: "Abs �?редина",
DlgImgAlignBaseline	: "Базно",
DlgImgAlignBottom	: "Доле",
DlgImgAlignMiddle	: "Средина",
DlgImgAlignRight	: "Де�?но",
DlgImgAlignTextTop	: "Врх тек�?та",
DlgImgAlignTop		: "Врх",
DlgImgPreview		: "Изглед",
DlgImgAlertUrl		: "Уне�?ите УРЛ �?лике",
DlgImgLinkTab		: "Линк",

// Flash Dialog
DlgFlashTitle		: "О�?обине флеша",
DlgFlashChkPlay		: "�?утомат�?ки �?тарт",
DlgFlashChkLoop		: "Понављај",
DlgFlashChkMenu		: "Укључи флеш мени",
DlgFlashScale		: "Скалирај",
DlgFlashScaleAll	: "Прикажи �?ве",
DlgFlashScaleNoBorder	: "Без ивице",
DlgFlashScaleFit	: "Попуни површину",

// Link Dialog
DlgLnkWindowTitle	: "Линк",
DlgLnkInfoTab		: "Линк инфо",
DlgLnkTargetTab		: "Мета",

DlgLnkType			: "Вр�?та линка",
DlgLnkTypeURL		: "URL",
DlgLnkTypeAnchor	: "Сидро на овој �?траници",
DlgLnkTypeEMail		: "Eлектрон�?ка пошта",
DlgLnkProto			: "Протокол",
DlgLnkProtoOther	: "&lt;друго&gt;",
DlgLnkURL			: "УРЛ",
DlgLnkAnchorSel		: "Одабери �?идро",
DlgLnkAnchorByName	: "По називу �?идра",
DlgLnkAnchorById	: "Пo Ид-jу елемента",
DlgLnkNoAnchors		: "&lt;�?ема до�?тупних �?идра&gt;",
DlgLnkEMail			: "�?дре�?а електрон�?ке поште",
DlgLnkEMailSubject	: "�?а�?лов",
DlgLnkEMailBody		: "Садржај поруке",
DlgLnkUpload		: "Пошаљи",
DlgLnkBtnUpload		: "Пошаљи на �?ервер",

DlgLnkTarget		: "Meтa",
DlgLnkTargetFrame	: "&lt;оквир&gt;",
DlgLnkTargetPopup	: "&lt;и�?качући прозор&gt;",
DlgLnkTargetBlank	: "�?ови прозор (_blank)",
DlgLnkTargetParent	: "Родитељ�?ки прозор (_parent)",
DlgLnkTargetSelf	: "И�?ти прозор (_self)",
DlgLnkTargetTop		: "Прозор на врху (_top)",
DlgLnkTargetFrameName	: "�?азив одредишног фрејма",
DlgLnkPopWinName	: "�?азив и�?качућег прозора",
DlgLnkPopWinFeat	: "Могућно�?ти и�?качућег прозора",
DlgLnkPopResize		: "Променљива величина",
DlgLnkPopLocation	: "Локација",
DlgLnkPopMenu		: "Контек�?тни мени",
DlgLnkPopScroll		: "Скрол бар",
DlgLnkPopStatus		: "Стату�?на линија",
DlgLnkPopToolbar	: "Toolbar",
DlgLnkPopFullScrn	: "Приказ преко целог екрана (ИE)",
DlgLnkPopDependent	: "Зави�?но (Netscape)",
DlgLnkPopWidth		: "Ширина",
DlgLnkPopHeight		: "Ви�?ина",
DlgLnkPopLeft		: "Од леве ивице екрана (пик�?ела)",
DlgLnkPopTop		: "Од врха екрана (пик�?ела)",

DlnLnkMsgNoUrl		: "Уне�?ите УРЛ линка",
DlnLnkMsgNoEMail	: "Откуцајте адре�?у електрон�?ке поште",
DlnLnkMsgNoAnchor	: "Одаберите �?идро",

// Color Dialog
DlgColorTitle		: "Одаберите боју",
DlgColorBtnClear	: "Обриши",
DlgColorHighlight	: "По�?ветли",
DlgColorSelected	: "Одабери",

// Smiley Dialog
DlgSmileyTitle		: "Уне�?и �?мајлија",

// Special Character Dialog
DlgSpecialCharTitle	: "Одаберите �?пецијални карактер",

// Table Dialog
DlgTableTitle		: "О�?обине табеле",
DlgTableRows		: "Редова",
DlgTableColumns		: "Kолона",
DlgTableBorder		: "Величина оквира",
DlgTableAlign		: "Равнање",
DlgTableAlignNotSet	: "<није по�?тављено>",
DlgTableAlignLeft	: "Лево",
DlgTableAlignCenter	: "Средина",
DlgTableAlignRight	: "Де�?но",
DlgTableWidth		: "Ширина",
DlgTableWidthPx		: "пик�?ела",
DlgTableWidthPc		: "процената",
DlgTableHeight		: "Ви�?ина",
DlgTableCellSpace	: "Ћелиј�?ки про�?тор",
DlgTableCellPad		: "Размак ћелија",
DlgTableCaption		: "�?а�?лов табеле",
DlgTableSummary		: "Summary",	//MISSING

// Table Cell Dialog
DlgCellTitle		: "О�?обине ћелије",
DlgCellWidth		: "Ширина",
DlgCellWidthPx		: "пик�?ела",
DlgCellWidthPc		: "процената",
DlgCellHeight		: "Ви�?ина",
DlgCellWordWrap		: "Дељење речи",
DlgCellWordWrapNotSet	: "<није по�?тављено>",
DlgCellWordWrapYes	: "Да",
DlgCellWordWrapNo	: "�?е",
DlgCellHorAlign		: "Водоравно равнање",
DlgCellHorAlignNotSet	: "<није по�?тављено>",
DlgCellHorAlignLeft	: "Лево",
DlgCellHorAlignCenter	: "Средина",
DlgCellHorAlignRight: "Де�?но",
DlgCellVerAlign		: "Вертикално равнање",
DlgCellVerAlignNotSet	: "<није по�?тављено>",
DlgCellVerAlignTop	: "Горње",
DlgCellVerAlignMiddle	: "Средина",
DlgCellVerAlignBottom	: "Доње",
DlgCellVerAlignBaseline	: "Базно",
DlgCellRowSpan		: "Спајање редова",
DlgCellCollSpan		: "Спајање колона",
DlgCellBackColor	: "Боја позадине",
DlgCellBorderColor	: "Боја оквира",
DlgCellBtnSelect	: "Oдабери...",

// Find Dialog
DlgFindTitle		: "Пронађи",
DlgFindFindBtn		: "Пронађи",
DlgFindNotFoundMsg	: "Тражени тек�?т није пронађен.",

// Replace Dialog
DlgReplaceTitle			: "Замени",
DlgReplaceFindLbl		: "Пронађи:",
DlgReplaceReplaceLbl	: "Замени �?а:",
DlgReplaceCaseChk		: "Разликуј велика и мала �?лова",
DlgReplaceReplaceBtn	: "Замени",
DlgReplaceReplAllBtn	: "Замени �?ве",
DlgReplaceWordChk		: "Упореди целе речи",

// Paste Operations / Dialog
PasteErrorPaste	: "Сигурно�?на подешавања Вашег претраживача не дозвољавају операције аутомат�?ког лепљења тек�?та. Молимо Ва�? да кори�?тите пречицу �?а та�?татуре (Ctrl+V).",
PasteErrorCut	: "Сигурно�?на подешавања Вашег претраживача не дозвољавају операције аутомат�?ког и�?ецања тек�?та. Молимо Ва�? да кори�?тите пречицу �?а та�?татуре (Ctrl+X).",
PasteErrorCopy	: "Сигурно�?на подешавања Вашег претраживача не дозвољавају операције аутомат�?ког копирања тек�?та. Молимо Ва�? да кори�?тите пречицу �?а та�?татуре (Ctrl+C).",

PasteAsText		: "Залепи као чи�?т тек�?т",
PasteFromWord	: "Залепи из Worda",

DlgPasteMsg2	: "Молимо Ва�? да залепите унутар доње површине кори�?тећи та�?татурну пречицу (<STRONG>Ctrl+V</STRONG>) и да прити�?нете <STRONG>OK</STRONG>.",
DlgPasteIgnoreFont		: "Игнориши Font Face дефиниције",
DlgPasteRemoveStyles	: "Уклони дефиниције �?тилова",
DlgPasteCleanBox		: "Обриши �?ве",


// Color Picker
ColorAutomatic	: "�?утомат�?ки",
ColorMoreColors	: "Више боја...",

// Document Properties
DocProps		: "О�?обине документа",

// Anchor Dialog
DlgAnchorTitle		: "О�?обине �?идра",
DlgAnchorName		: "Име �?идра",
DlgAnchorErrorName	: "Молимо Ва�? да уне�?ете име �?идра",

// Speller Pages Dialog
DlgSpellNotInDic		: "�?ије у речнику",
DlgSpellChangeTo		: "Измени",
DlgSpellBtnIgnore		: "Игнориши",
DlgSpellBtnIgnoreAll	: "Игнориши �?ве",
DlgSpellBtnReplace		: "Замени",
DlgSpellBtnReplaceAll	: "Замени �?ве",
DlgSpellBtnUndo			: "Врати акцију",
DlgSpellNoSuggestions	: "- Без �?уге�?тија -",
DlgSpellProgress		: "Провера �?пеловања у току...",
DlgSpellNoMispell		: "Провера �?пеловања завршена: грешке ни�?у пронађене",
DlgSpellNoChanges		: "Провера �?пеловања завршена: �?ије измењена ниједна реч",
DlgSpellOneChange		: "Провера �?пеловања завршена: Измењена је једна реч",
DlgSpellManyChanges		: "Провера �?пеловања завршена:  %1 реч(и) је измењено",

IeSpellDownload			: "Провера �?пеловања није ин�?талирана. Да ли желите да је �?кинете �?а Интернета?",

// Button Dialog
DlgButtonText	: "Тек�?т (вредно�?т)",
DlgButtonType	: "Tип",

// Checkbox and Radio Button Dialogs
DlgCheckboxName		: "�?азив",
DlgCheckboxValue	: "Вредно�?т",
DlgCheckboxSelected	: "Означено",

// Form Dialog
DlgFormName		: "�?азив",
DlgFormAction	: "Aкција",
DlgFormMethod	: "Mетода",

// Select Field Dialog
DlgSelectName		: "�?азив",
DlgSelectValue		: "Вредно�?т",
DlgSelectSize		: "Величина",
DlgSelectLines		: "линија",
DlgSelectChkMulti	: "Дозволи више�?труку �?елекцију",
DlgSelectOpAvail	: "До�?тупне опције",
DlgSelectOpText		: "Тек�?т",
DlgSelectOpValue	: "Вредно�?т",
DlgSelectBtnAdd		: "Додај",
DlgSelectBtnModify	: "Измени",
DlgSelectBtnUp		: "Горе",
DlgSelectBtnDown	: "Доле",
DlgSelectBtnSetValue : "Поде�?и као означену вредно�?т",
DlgSelectBtnDelete	: "Обриши",

// Textarea Dialog
DlgTextareaName	: "�?азив",
DlgTextareaCols	: "Број колона",
DlgTextareaRows	: "Број редова",

// Text Field Dialog
DlgTextName			: "�?азив",
DlgTextValue		: "Вредно�?т",
DlgTextCharWidth	: "Ширина (карактера)",
DlgTextMaxChars		: "Мак�?имално карактера",
DlgTextType			: "Тип",
DlgTextTypeText		: "Тек�?т",
DlgTextTypePass		: "Лозинка",

// Hidden Field Dialog
DlgHiddenName	: "�?азив",
DlgHiddenValue	: "Вредно�?т",

// Bulleted List Dialog
BulletedListProp	: "О�?обине Bulleted ли�?те",
NumberedListProp	: "О�?обине набројиве ли�?те",
DlgLstType			: "Тип",
DlgLstTypeCircle	: "Круг",
DlgLstTypeDisc		: "Disc",	//MISSING
DlgLstTypeSquare	: "Квадрат",
DlgLstTypeNumbers	: "Бројеви (1, 2, 3)",
DlgLstTypeLCase		: "мала �?лова (a, b, c)",
DlgLstTypeUCase		: "ВЕЛИК�? СЛОВ�? (A, B, C)",
DlgLstTypeSRoman	: "Мале рим�?ке цифре (i, ii, iii)",
DlgLstTypeLRoman	: "Велике рим�?ке цифре (I, II, III)",

// Document Properties Dialog
DlgDocGeneralTab	: "Опште о�?обине",
DlgDocBackTab		: "Позадина",
DlgDocColorsTab		: "Боје и маргине",
DlgDocMetaTab		: "Метаподаци",

DlgDocPageTitle		: "�?а�?лов �?транице",
DlgDocLangDir		: "Смер језика",
DlgDocLangDirLTR	: "Слева наде�?но (LTR)",
DlgDocLangDirRTL	: "Зде�?на налево (RTL)",
DlgDocLangCode		: "Шифра језика",
DlgDocCharSet		: "Кодирање �?купа карактера",
DlgDocCharSetOther	: "О�?тала кодирања �?купа карактера",

DlgDocDocType		: "Заглавље типа документа",
DlgDocDocTypeOther	: "О�?тала заглавља типа документа",
DlgDocIncXHTML		: "Улључи XHTML декларације",
DlgDocBgColor		: "Боја позадине",
DlgDocBgImage		: "УРЛ позадин�?ке �?лике",
DlgDocBgNoScroll	: "Фик�?ирана позадина",
DlgDocCText			: "Тек�?т",
DlgDocCLink			: "Линк",
DlgDocCVisited		: "По�?ећени линк",
DlgDocCActive		: "�?ктивни линк",
DlgDocMargins		: "Маргине �?транице",
DlgDocMaTop			: "Горња",
DlgDocMaLeft		: "Лева",
DlgDocMaRight		: "Де�?на",
DlgDocMaBottom		: "Доња",
DlgDocMeIndex		: "Кључне речи за индек�?ирање документа (раздвојене зарезом)",
DlgDocMeDescr		: "Опи�? документа",
DlgDocMeAuthor		: "�?утор",
DlgDocMeCopy		: "�?утор�?ка права",
DlgDocPreview		: "Изглед �?транице",

// Templates Dialog
Templates			: "Обра�?ци",
DlgTemplatesTitle	: "Обра�?ци за �?адржај",
DlgTemplatesSelMsg	: "Молимо Ва�? да одаберете образац који ће бити примењен на �?траницу (тренутни �?адржај ће бити обри�?ан):",
DlgTemplatesLoading	: "Учитавам ли�?ту образаца. Мало �?трпљења...",
DlgTemplatesNoTpl	: "(�?ема дефини�?аних образаца)",

// About Dialog
DlgAboutAboutTab	: "О едитору",
DlgAboutBrowserInfoTab	: "Информације о претраживачу",
DlgAboutVersion		: "верзија",
DlgAboutLicense		: "Лиценцирано под у�?ловима GNU Lesser General Public License",
DlgAboutInfo		: "За више информација по�?етите"
}