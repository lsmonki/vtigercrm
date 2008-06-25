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
 * File Name: bg.js
 * 	Bulgarian language file.
 * 
 * File Authors:
 * 		Miroslav Ivanov (miro@primal-chaos.net)
 */

var FCKLang =
{
// Language direction : "ltr" (left to right) or "rtl" (right to left).
Dir					: "ltr",

ToolbarCollapse		: "Скрий панела �? ин�?трументите",
ToolbarExpand		: "Покажи панела �? ин�?трументите",

// Toolbar Items and Context Menu
Save				: "Запази",
NewPage				: "�?ова �?траница",
Preview				: "Предварителен изглед",
Cut					: "Изрежи",
Copy				: "Запамети",
Paste				: "Вмъкни",
PasteText			: "Вмъкни �?амо тек�?т",
PasteWord			: "Вмъкни от MS Word",
Print				: "Печат",
SelectAll			: "Селектирай в�?ичко",
RemoveFormat		: "Изтрий форматирането",
InsertLinkLbl		: "Връзка",
InsertLink			: "Добави/Редактирай връзка",
RemoveLink			: "Изтрий връзка",
Anchor				: "Добави/Редактирай котва",
InsertImageLbl		: "Изображение",
InsertImage			: "Добави/Редактирай изображение",
InsertFlashLbl		: "Flash",
InsertFlash			: "Добави/Редактиай Flash обект",
InsertTableLbl		: "Таблица",
InsertTable			: "Добави/Редактирай таблица",
InsertLineLbl		: "Лини�?",
InsertLine			: "Вмъкни хоризонтална лини�?",
InsertSpecialCharLbl: "Специален �?имвол",
InsertSpecialChar	: "Вмъкни �?пециален �?имвол",
InsertSmileyLbl		: "У�?мивка",
InsertSmiley		: "Добави у�?мивка",
About				: "За FCKeditor",
Bold				: "Удебелен",
Italic				: "Кур�?ив",
Underline			: "Подчертан",
StrikeThrough		: "Зачертан",
Subscript			: "Индек�? за база",
Superscript			: "Индек�? за �?тепен",
LeftJustify			: "Подравн�?ване в л�?во",
CenterJustify		: "Подравн�?вне в �?редата",
RightJustify		: "Подравн�?ване в д�?�?но",
BlockJustify		: "Дву�?транно подравн�?ване",
DecreaseIndent		: "�?амали от�?тъпа",
IncreaseIndent		: "Увеличи от�?тъпа",
Undo				: "Отмени",
Redo				: "Повтори",
NumberedListLbl		: "�?умериран �?пи�?ък",
NumberedList		: "Добави/Изтрий нумериран �?пи�?ък",
BulletedListLbl		: "�?енумериран �?пи�?ък",
BulletedList		: "Добави/Изтрий ненумериран �?пи�?ък",
ShowTableBorders	: "Покажи рамките на таблицата",
ShowDetails			: "Покажи подробно�?ти",
Style				: "Стил",
FontFormat			: "Формат",
Font				: "Шрифт",
FontSize			: "Размер",
TextColor			: "Цв�?т на тек�?та",
BGColor				: "Цв�?т на фона",
Source				: "Код",
Find				: "Тър�?и",
Replace				: "Заме�?ти",
SpellCheck			: "Провери правопи�?а",
UniversalKeyboard	: "Универ�?ална клавиатура",
PageBreakLbl		: "�?ов ред",
PageBreak			: "Вмъкни нов ред",

Form			: "Формул�?р",
Checkbox		: "Поле за отметка",
RadioButton		: "Поле за опци�?",
TextField		: "Тек�?тово поле",
Textarea		: "Тек�?това обла�?т",
HiddenField		: "Скрито поле",
Button			: "Бутон",
SelectionField	: "Падащо меню �? опции",
ImageButton		: "Бутон-изображение",

// Context Menu
EditLink			: "Редактирай връзка",
InsertRow			: "Добави ред",
DeleteRows			: "Изтрий редовете",
InsertColumn		: "Добави колона",
DeleteColumns		: "Изтрий колоните",
InsertCell			: "Добави клетка",
DeleteCells			: "Изтрий клетките",
MergeCells			: "Обедини клетките",
SplitCell			: "Раздели клетката",
TableDelete			: "Изтрий таблицата",
CellProperties		: "Параметри на клетката",
TableProperties		: "Параметри на таблицата",
ImageProperties		: "Параметри на изображението",
FlashProperties		: "Параметри на Flash обекта",

AnchorProp			: "Параметри на котвата",
ButtonProp			: "Параметри на бутона",
CheckboxProp		: "Параметри на полето за отметка",
HiddenFieldProp		: "Параметри на �?критото поле",
RadioButtonProp		: "Параметри на полето за опци�?",
ImageButtonProp		: "Параметри на бутона-изображение",
TextFieldProp		: "Параметри на тек�?товото-поле",
SelectionFieldProp	: "Параметри на падащото меню �? опции",
TextareaProp		: "Параметри на тек�?товата обла�?т",
FormProp			: "Параметри на формул�?ра",

FontFormats			: "�?ормален;Форматиран;�?дре�?;Заглавие 1;Заглавие 2;Заглавие 3;Заглавие 4;Заглавие 5;Заглавие 6;Параграф (DIV)",

// Alerts and Messages
ProcessingXHTML		: "Обработка на XHTML. Мол�? изчакайте...",
Done				: "Готово",
PasteWordConfirm	: "Тек�?тът, който и�?кате да вмъкнете е копиран от MS Word. Желаете ли да бъде изчи�?тен преди вмъкването?",
NotCompatiblePaste	: "Тази операци�? изи�?ква MS Internet Explorer вер�?и�? 5.5 или по-ви�?ока. Желаете ли да вмъкнете запаметеното без изчи�?тване?",
UnknownToolbarItem	: "�?епознат ин�?трумент \"%1\"",
UnknownCommand		: "�?епозната команда \"%1\"",
NotImplemented		: "Командата не е имплементирана",
UnknownToolbarSet	: "Панелът \"%1\" не �?ъще�?твува",
NoActiveX			: "You browser's security settings could limit some features of the editor. You must enable the option \"Run ActiveX controls and plug-ins\". You may experience errors and notice missing features.",	//MISSING
BrowseServerBlocked : "The resources browser could not be opened. Make sure that all popup blockers are disabled.",	//MISSING
DialogBlocked		: "It was not possible to open the dialog window. Make sure all popup blockers are disabled.",	//MISSING

// Dialogs
DlgBtnOK			: "ОК",
DlgBtnCancel		: "Отказ",
DlgBtnClose			: "Затвори",
DlgBtnBrowseServer	: "Разгледай �?ървъра",
DlgAdvancedTag		: "Подробно�?ти...",
DlgOpOther			: "&lt;Друго&gt;",
DlgInfoTab			: "Информаци�?",
DlgAlertUrl			: "Мол�?, въведете пълни�? път (URL)",

// General Dialogs Labels
DlgGenNotSet		: "&lt;не е на�?троен&gt;",
DlgGenId			: "Идентификатор",
DlgGenLangDir		: "по�?ока на речта",
DlgGenLangDirLtr	: "От л�?во на д�?�?но",
DlgGenLangDirRtl	: "От д�?�?но на л�?во",
DlgGenLangCode		: "Код на езика",
DlgGenAccessKey		: "Бърз клавиш",
DlgGenName			: "Име",
DlgGenTabIndex		: "Ред на до�?тъп",
DlgGenLongDescr		: "Опи�?ание на връзката",
DlgGenClass			: "Кла�? от �?тиловите таблици",
DlgGenTitle			: "Препоръчително заглавие",
DlgGenContType		: "Препоръчителен тип на �?ъдържанието",
DlgGenLinkCharset	: "Тип на �?вързани�? ре�?ур�?",
DlgGenStyle			: "Стил",

// Image Dialog
DlgImgTitle			: "Параметри на изображението",
DlgImgInfoTab		: "Информаци�? за изображението",
DlgImgBtnUpload		: "Прати към �?ървъра",
DlgImgURL			: "Пълен път (URL)",
DlgImgUpload		: "Качи",
DlgImgAlt			: "�?лтернативен тек�?т",
DlgImgWidth			: "Ширина",
DlgImgHeight		: "Ви�?очина",
DlgImgLockRatio		: "Запази пропорци�?та",
DlgBtnResetSize		: "Въз�?танови размера",
DlgImgBorder		: "Рамка",
DlgImgHSpace		: "Хоризонтален от�?тъп",
DlgImgVSpace		: "Вертикален от�?тъп",
DlgImgAlign			: "Подравн�?ване",
DlgImgAlignLeft		: "Л�?во",
DlgImgAlignAbsBottom: "�?ай-долу",
DlgImgAlignAbsMiddle: "Точно по �?редата",
DlgImgAlignBaseline	: "По базовата лини�?",
DlgImgAlignBottom	: "Долу",
DlgImgAlignMiddle	: "По �?редата",
DlgImgAlignRight	: "Д�?�?но",
DlgImgAlignTextTop	: "Върху тек�?та",
DlgImgAlignTop		: "Отгоре",
DlgImgPreview		: "Изглед",
DlgImgAlertUrl		: "Мол�?, въведете пълни�? път до изображението",
DlgImgLinkTab		: "Връзка",

// Flash Dialog
DlgFlashTitle		: "Параметри на Flash обекта",
DlgFlashChkPlay		: "�?втоматично �?тартиране",
DlgFlashChkLoop		: "�?ово �?тартиране �?лед завършването",
DlgFlashChkMenu		: "Разрешено Flash меню",
DlgFlashScale		: "Оразмер�?ване",
DlgFlashScaleAll	: "Покажи цели�? обект",
DlgFlashScaleNoBorder	: "Без рамка",
DlgFlashScaleFit	: "Според м�?�?тото",

// Link Dialog
DlgLnkWindowTitle	: "Връзка",
DlgLnkInfoTab		: "Информаци�? за връзката",
DlgLnkTargetTab		: "Цел",

DlgLnkType			: "Вид на връзката",
DlgLnkTypeURL		: "Пълен път (URL)",
DlgLnkTypeAnchor	: "Котва в текущата �?траница",
DlgLnkTypeEMail		: "Е-поща",
DlgLnkProto			: "Протокол",
DlgLnkProtoOther	: "&lt;друго&gt;",
DlgLnkURL			: "Пълен път (URL)",
DlgLnkAnchorSel		: "Изберете котва",
DlgLnkAnchorByName	: "По име на котвата",
DlgLnkAnchorById	: "По идентификатор на елемент",
DlgLnkNoAnchors		: "&lt;�?�?ма котви в текущи�? документ&gt;",
DlgLnkEMail			: "�?дре�? за е-поща",
DlgLnkEMailSubject	: "Тема на пи�?мото",
DlgLnkEMailBody		: "Тек�?т на пи�?мото",
DlgLnkUpload		: "Качи",
DlgLnkBtnUpload		: "Прати на �?ървъра",

DlgLnkTarget		: "Цел",
DlgLnkTargetFrame	: "&lt;рамка&gt;",
DlgLnkTargetPopup	: "&lt;дъщерен прозорец&gt;",
DlgLnkTargetBlank	: "�?ов прозорец (_blank)",
DlgLnkTargetParent	: "Родител�?ки прозорец (_parent)",
DlgLnkTargetSelf	: "�?ктивни�? прозорец (_self)",
DlgLnkTargetTop		: "Цели�? прозорец (_top)",
DlgLnkTargetFrameName	: "Име на целеви�? прозорец",
DlgLnkPopWinName	: "Име на дъщерни�? прозорец",
DlgLnkPopWinFeat	: "Параметри на дъщерни�? прозорец",
DlgLnkPopResize		: "С променливи размери",
DlgLnkPopLocation	: "Поле за адре�?",
DlgLnkPopMenu		: "Меню",
DlgLnkPopScroll		: "Плъзгач",
DlgLnkPopStatus		: "Поле за �?тату�?",
DlgLnkPopToolbar	: "Панел �? бутони",
DlgLnkPopFullScrn	: "Гол�?м екран (MS IE)",
DlgLnkPopDependent	: "Зави�?им (Netscape)",
DlgLnkPopWidth		: "Ширина",
DlgLnkPopHeight		: "Ви�?очина",
DlgLnkPopLeft		: "Координати - X",
DlgLnkPopTop		: "Координати - Y",

DlnLnkMsgNoUrl		: "Мол�?, напишете пълни�? път (URL)",
DlnLnkMsgNoEMail	: "Мол�?, напишете адре�?а за е-поща",
DlnLnkMsgNoAnchor	: "Мол�?, изберете котва",

// Color Dialog
DlgColorTitle		: "Изберете цв�?т",
DlgColorBtnClear	: "Изчи�?ти",
DlgColorHighlight	: "Текущ",
DlgColorSelected	: "Избран",

// Smiley Dialog
DlgSmileyTitle		: "Добави у�?мивка",

// Special Character Dialog
DlgSpecialCharTitle	: "Изберете �?пециален �?имвол",

// Table Dialog
DlgTableTitle		: "Параметри на таблицата",
DlgTableRows		: "Редове",
DlgTableColumns		: "Колони",
DlgTableBorder		: "Размер на рамката",
DlgTableAlign		: "Подравн�?ване",
DlgTableAlignNotSet	: "<�?е е избрано>",
DlgTableAlignLeft	: "Л�?во",
DlgTableAlignCenter	: "Център",
DlgTableAlignRight	: "Д�?�?но",
DlgTableWidth		: "Ширина",
DlgTableWidthPx		: "пик�?ели",
DlgTableWidthPc		: "проценти",
DlgTableHeight		: "Ви�?очина",
DlgTableCellSpace	: "Раз�?то�?ние между клетките",
DlgTableCellPad		: "От�?тъп на �?ъдържанието в клетките",
DlgTableCaption		: "Заглавие",
DlgTableSummary		: "Резюме",

// Table Cell Dialog
DlgCellTitle		: "Параметри на клетката",
DlgCellWidth		: "Ширина",
DlgCellWidthPx		: "пик�?ели",
DlgCellWidthPc		: "проценти",
DlgCellHeight		: "Ви�?очина",
DlgCellWordWrap		: "прена�?�?не на нов ред",
DlgCellWordWrapNotSet	: "&lt;�?е е на�?троено&gt;",
DlgCellWordWrapYes	: "Да",
DlgCellWordWrapNo	: "не",
DlgCellHorAlign		: "Хоризонтално подравн�?ване",
DlgCellHorAlignNotSet	: "&lt;�?е е на�?троено&gt;",
DlgCellHorAlignLeft	: "Л�?во",
DlgCellHorAlignCenter	: "Център",
DlgCellHorAlignRight: "Д�?�?но",
DlgCellVerAlign		: "Вертикално подравн�?ване",
DlgCellVerAlignNotSet	: "&lt;�?е е на�?троено&gt;",
DlgCellVerAlignTop	: "Горе",
DlgCellVerAlignMiddle	: "По �?редата",
DlgCellVerAlignBottom	: "Долу",
DlgCellVerAlignBaseline	: "По базовата лини�?",
DlgCellRowSpan		: "повече от един ред",
DlgCellCollSpan		: "повече от една колона",
DlgCellBackColor	: "фонов цв�?т",
DlgCellBorderColor	: "цв�?т на рамката",
DlgCellBtnSelect	: "Изберете...",

// Find Dialog
DlgFindTitle		: "Тър�?и",
DlgFindFindBtn		: "Тър�?и",
DlgFindNotFoundMsg	: "Указани�? тек�?т не беше намерен.",

// Replace Dialog
DlgReplaceTitle			: "Заме�?ти",
DlgReplaceFindLbl		: "Тър�?и:",
DlgReplaceReplaceLbl	: "Заме�?ти �?:",
DlgReplaceCaseChk		: "Съ�? �?ъщи�? реги�?тър",
DlgReplaceReplaceBtn	: "Заме�?ти",
DlgReplaceReplAllBtn	: "Заме�?ти в�?ички",
DlgReplaceWordChk		: "Тър�?и �?ъщата дума",

// Paste Operations / Dialog
PasteErrorPaste	: "�?а�?тройките за �?игурно�?т на ваши�? бразуър не разрешават на редактора да изпълни вмъкването. За целта използвайте клавиатурата (Ctrl+V).",
PasteErrorCut	: "�?а�?тройките за �?игурно�?т на ваши�? бразуър не разрешават на редактора да изпълни изр�?зването. За целта използвайте клавиатурата (Ctrl+X).",
PasteErrorCopy	: "�?а�?тройките за �?игурно�?т на ваши�? бразуър не разрешават на редактора да изпълни запамет�?ването. За целта използвайте клавиатурата (Ctrl+C).",

PasteAsText		: "Вмъкни като чи�?т тек�?т",
PasteFromWord	: "Вмъкни от MS Word",

DlgPasteMsg2	: "Вмъкнете тук �?ъдъжанието �? клавиатуарата (<STRONG>Ctrl+V</STRONG>) и нати�?нете <STRONG>OK</STRONG>.",
DlgPasteIgnoreFont		: "Игнорирай шрифтовите дефиниции",
DlgPasteRemoveStyles	: "Изтрий �?тиловите дефиниции",
DlgPasteCleanBox		: "Изчи�?ти",


// Color Picker
ColorAutomatic	: "По подразбиране",
ColorMoreColors	: "Други цветове...",

// Document Properties
DocProps		: "Параметри на документа",

// Anchor Dialog
DlgAnchorTitle		: "Параметри на котвата",
DlgAnchorName		: "Име на котвата",
DlgAnchorErrorName	: "Мол�?, въведете име на котвата",

// Speller Pages Dialog
DlgSpellNotInDic		: "Лип�?ва в речника",
DlgSpellChangeTo		: "Промени на",
DlgSpellBtnIgnore		: "Игнорирай",
DlgSpellBtnIgnoreAll	: "Игнорирай в�?ички",
DlgSpellBtnReplace		: "Заме�?ти",
DlgSpellBtnReplaceAll	: "Заме�?ти в�?ички",
DlgSpellBtnUndo			: "Отмени",
DlgSpellNoSuggestions	: "- �?�?ма предложени�? -",
DlgSpellProgress		: "Извършване на проверката за правопи�?...",
DlgSpellNoMispell		: "Проверката за правопи�? завършена: не �?а открити правопи�?ни грешки",
DlgSpellNoChanges		: "Проверката за правопи�? завършена: н�?ма променени думи",
DlgSpellOneChange		: "Проверката за правопи�? завършена: една дума е променена",
DlgSpellManyChanges		: "Проверката за правопи�? завършена: %1 думи �?а променени",

IeSpellDownload			: "Ин�?трументът за проверка на правопи�? не е ин�?талиран. Желаете ли да го ин�?талирате ?",

// Button Dialog
DlgButtonText	: "Тек�?т (Стойно�?т)",
DlgButtonType	: "Тип",

// Checkbox and Radio Button Dialogs
DlgCheckboxName		: "Име",
DlgCheckboxValue	: "Стойно�?т",
DlgCheckboxSelected	: "Отметнато",

// Form Dialog
DlgFormName		: "Име",
DlgFormAction	: "Дей�?твие",
DlgFormMethod	: "Метод",

// Select Field Dialog
DlgSelectName		: "Име",
DlgSelectValue		: "Стойно�?т",
DlgSelectSize		: "Размер",
DlgSelectLines		: "линии",
DlgSelectChkMulti	: "Разрешено множе�?твено �?електиране",
DlgSelectOpAvail	: "Възможни опции",
DlgSelectOpText		: "Тек�?т",
DlgSelectOpValue	: "Стойно�?т",
DlgSelectBtnAdd		: "Добави",
DlgSelectBtnModify	: "Промени",
DlgSelectBtnUp		: "�?агоре",
DlgSelectBtnDown	: "�?адолу",
DlgSelectBtnSetValue : "�?а�?трой като избрана �?тойно�?т",
DlgSelectBtnDelete	: "Изтрий",

// Textarea Dialog
DlgTextareaName	: "Име",
DlgTextareaCols	: "Колони",
DlgTextareaRows	: "Редове",

// Text Field Dialog
DlgTextName			: "Име",
DlgTextValue		: "Стойно�?т",
DlgTextCharWidth	: "Ширина на �?имволите",
DlgTextMaxChars		: "Мак�?имум �?имволи",
DlgTextType			: "Тип",
DlgTextTypeText		: "Тек�?т",
DlgTextTypePass		: "Парола",

// Hidden Field Dialog
DlgHiddenName	: "Име",
DlgHiddenValue	: "Стойно�?т",

// Bulleted List Dialog
BulletedListProp	: "Параметри на ненумерирани�? �?пи�?ък",
NumberedListProp	: "Параметри на нумерирани�? �?пи�?ък",
DlgLstType			: "Тип",
DlgLstTypeCircle	: "Окръжно�?т",
DlgLstTypeDisc		: "Кръг",
DlgLstTypeSquare	: "Квадрат",
DlgLstTypeNumbers	: "Чи�?ла (1, 2, 3)",
DlgLstTypeLCase		: "Малки букви (a, b, c)",
DlgLstTypeUCase		: "Големи букви (A, B, C)",
DlgLstTypeSRoman	: "Малки рим�?ки чи�?ла (i, ii, iii)",
DlgLstTypeLRoman	: "Големи рим�?ки чи�?ла (I, II, III)",

// Document Properties Dialog
DlgDocGeneralTab	: "Общи",
DlgDocBackTab		: "Фон",
DlgDocColorsTab		: "Цветове и от�?тъпи",
DlgDocMetaTab		: "Мета данни",

DlgDocPageTitle		: "Заглавие на �?траницата",
DlgDocLangDir		: "По�?ока на речта",
DlgDocLangDirLTR	: "От л�?во на д�?�?но",
DlgDocLangDirRTL	: "От д�?�?но на л�?во",
DlgDocLangCode		: "Код на езика",
DlgDocCharSet		: "Кодиране на �?имволите",
DlgDocCharSetOther	: "Друго кодиране на �?имволите",

DlgDocDocType		: "Тип на документа",
DlgDocDocTypeOther	: "Друг тип на документа",
DlgDocIncXHTML		: "Включи XHTML деклараци�?",
DlgDocBgColor		: "Цв�?т на фона",
DlgDocBgImage		: "Пълен път до фоновото изображение",
DlgDocBgNoScroll	: "�?е-повтар�?що �?е фоново изображение",
DlgDocCText			: "Тек�?т",
DlgDocCLink			: "Връзка",
DlgDocCVisited		: "По�?етена връзка",
DlgDocCActive		: "�?ктивна връзка",
DlgDocMargins		: "От�?тъпи на �?траницата",
DlgDocMaTop			: "Горе",
DlgDocMaLeft		: "Л�?во",
DlgDocMaRight		: "Д�?�?но",
DlgDocMaBottom		: "Долу",
DlgDocMeIndex		: "Ключови думи за документа (разделени �?ъ�? запетаи)",
DlgDocMeDescr		: "Опи�?ание на документа",
DlgDocMeAuthor		: "�?втор",
DlgDocMeCopy		: "�?втор�?ки права",
DlgDocPreview		: "Изглед",

// Templates Dialog
Templates			: "Шаблони",
DlgTemplatesTitle	: "Шаблони",
DlgTemplatesSelMsg	: "Изберете шаблон <br>(текущото �?ъдържание на редактора ще бъде загубено):",
DlgTemplatesLoading	: "Зареждане на �?пи�?ъка �? шаблоните. Мол�? изчакайте...",
DlgTemplatesNoTpl	: "(�?�?ма дефинирани шаблони)",

// About Dialog
DlgAboutAboutTab	: "За",
DlgAboutBrowserInfoTab	: "Информаци�? за браузъра",
DlgAboutVersion		: "вер�?и�?",
DlgAboutLicense		: "Лиценз по у�?лови�?та на GNU Lesser General Public License",
DlgAboutInfo		: "За повече информаци�? по�?етете"
}