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
 * File Name: ru.js
 * 	Russian language file.
 * 
 * File Authors:
 * 		Andrey Grebnev (andrey.grebnev@blandware.com)
 */

var FCKLang =
{
// Language direction : "ltr" (left to right) or "rtl" (right to left).
Dir					: "ltr",

ToolbarCollapse		: "Свернуть панель ин�?трументов",
ToolbarExpand		: "Развернуть панель ин�?трументов",

// Toolbar Items and Context Menu
Save				: "Сохранить",
NewPage				: "�?ова�? �?траница",
Preview				: "Предварительный про�?мотр",
Cut					: "Вырезать",
Copy				: "Копировать",
Paste				: "В�?тавить",
PasteText			: "В�?тавить только тек�?т",
PasteWord			: "В�?тавить из Word",
Print				: "Печать",
SelectAll			: "Выделить в�?е",
RemoveFormat		: "Убрать форматирование",
InsertLinkLbl		: "С�?ылка",
InsertLink			: "В�?тавить/Редактировать �?�?ылку",
RemoveLink			: "Убрать �?�?ылку",
Anchor				: "В�?тавить/Редактировать �?корь",
InsertImageLbl		: "Изображение",
InsertImage			: "В�?тавить/Редактировать изображение",
InsertFlashLbl		: "Flash",
InsertFlash			: "В�?тавить/Редактировать Flash",
InsertTableLbl		: "Таблица",
InsertTable			: "В�?тавить/Редактировать таблицу",
InsertLineLbl		: "Лини�?",
InsertLine			: "В�?тавить горизонтальную линию",
InsertSpecialCharLbl: "Специальный �?имвол",
InsertSpecialChar	: "В�?тавить �?пециальный �?имвол",
InsertSmileyLbl		: "Смайлик",
InsertSmiley		: "В�?тавить �?майлик",
About				: "О FCKeditor",
Bold				: "Жирный",
Italic				: "Кур�?ив",
Underline			: "Подчеркнутый",
StrikeThrough		: "Зачеркнутый",
Subscript			: "Под�?трочный индек�?",
Superscript			: "�?ад�?трочный индек�?",
LeftJustify			: "По левому краю",
CenterJustify		: "По центру",
RightJustify		: "По правому краю",
BlockJustify		: "По ширине",
DecreaseIndent		: "Уменьшить от�?туп",
IncreaseIndent		: "Увеличить от�?туп",
Undo				: "Отменить",
Redo				: "Повторить",
NumberedListLbl		: "�?умерованный �?пи�?ок",
NumberedList		: "В�?тавить/Удалить нумерованный �?пи�?ок",
BulletedListLbl		: "Маркированный �?пи�?ок",
BulletedList		: "В�?тавить/Удалить маркированный �?пи�?ок",
ShowTableBorders	: "Показать бордюры таблицы",
ShowDetails			: "Показать детали",
Style				: "Стиль",
FontFormat			: "Форматирование",
Font				: "Шрифт",
FontSize			: "Размер",
TextColor			: "Цвет тек�?та",
BGColor				: "Цвет фона",
Source				: "И�?точник",
Find				: "�?айти",
Replace				: "Заменить",
SpellCheck			: "Проверить орфографию",
UniversalKeyboard	: "Универ�?альна�? клавиатура",
PageBreakLbl		: "Page Break",	//MISSING
PageBreak			: "Insert Page Break",	//MISSING

Form			: "Форма",
Checkbox		: "Флагова�? кнопка",
RadioButton		: "Кнопка выбора",
TextField		: "Тек�?товое поле",
Textarea		: "Тек�?това�? обла�?ть",
HiddenField		: "Скрытое поле",
Button			: "Кнопка",
SelectionField	: "Спи�?ок",
ImageButton		: "Кнопка �? изображением",

// Context Menu
EditLink			: "В�?тавить �?�?ылку",
InsertRow			: "В�?тавить �?троку",
DeleteRows			: "Удалить �?троки",
InsertColumn		: "В�?тавить колонку",
DeleteColumns		: "Удалить колонки",
InsertCell			: "В�?тавить �?чейку",
DeleteCells			: "Удалить �?чейки",
MergeCells			: "Соединить �?чейки",
SplitCell			: "Разбить �?чейку",
TableDelete			: "Delete Table",	//MISSING
CellProperties		: "Свой�?тва �?чейки",
TableProperties		: "Свой�?тва таблицы",
ImageProperties		: "Свой�?тва изображени�?",
FlashProperties		: "Свой�?тва Flash",

AnchorProp			: "Свой�?тва �?кор�?",
ButtonProp			: "Свой�?тва кнопки",
CheckboxProp		: "Свой�?тва флаговой кнопки",
HiddenFieldProp		: "Свой�?тва �?крытого пол�?",
RadioButtonProp		: "Свой�?тва кнопки выбора",
ImageButtonProp		: "Свой�?тва кнопки �? изображением",
TextFieldProp		: "Свой�?тва тек�?тового пол�?",
SelectionFieldProp	: "Свой�?тва �?пи�?ка",
TextareaProp		: "Свой�?тва тек�?товой обла�?ти",
FormProp			: "Свой�?тва формы",

FontFormats			: "�?ормальный;Форматированный;�?дре�?;Заголовок 1;Заголовок 2;Заголовок 3;Заголовок 4;Заголовок 5;Заголовок 6",

// Alerts and Messages
ProcessingXHTML		: "Обработка XHTML. Пожалуй�?та подождите...",
Done				: "Сделано",
PasteWordConfirm	: "Тек�?т, который вы хотите в�?тавить, похож на копируемый из Word. Вы хотите очи�?тить его перед в�?тавкой?",
NotCompatiblePaste	: "Эта команда до�?тупна дл�? Internet Explorer вер�?ии 5.5 или выше. Вы хотите в�?тавить без очи�?тки?",
UnknownToolbarItem	: "�?е изве�?тный �?лемент панели ин�?трументов \"%1\"",
UnknownCommand		: "�?е изве�?тное им�? команды \"%1\"",
NotImplemented		: "Команда не реализована",
UnknownToolbarSet	: "Панель ин�?трументов \"%1\" не �?уще�?твует",
NoActiveX			: "You browser's security settings could limit some features of the editor. You must enable the option \"Run ActiveX controls and plug-ins\". You may experience errors and notice missing features.",	//MISSING
BrowseServerBlocked : "The resources browser could not be opened. Make sure that all popup blockers are disabled.",	//MISSING
DialogBlocked		: "It was not possible to open the dialog window. Make sure all popup blockers are disabled.",	//MISSING

// Dialogs
DlgBtnOK			: "ОК",
DlgBtnCancel		: "Отмена",
DlgBtnClose			: "Закрыть",
DlgBtnBrowseServer	: "Про�?мотреть на �?ервере",
DlgAdvancedTag		: "Ра�?ширенный",
DlgOpOther			: "&lt;Другое&gt;",
DlgInfoTab			: "Информаци�?",
DlgAlertUrl			: "Пожалуй�?та в�?тавьте URL",

// General Dialogs Labels
DlgGenNotSet		: "&lt;не определено&gt;",
DlgGenId			: "Идентификатор",
DlgGenLangDir		: "�?аправление �?зыка",
DlgGenLangDirLtr	: "Слева на право (LTR)",
DlgGenLangDirRtl	: "Справа на лево (RTL)",
DlgGenLangCode		: "Язык",
DlgGenAccessKey		: "Гор�?ча�? клавиша",
DlgGenName			: "Им�?",
DlgGenTabIndex		: "По�?ледовательно�?ть перехода",
DlgGenLongDescr		: "Длинное опи�?ание URL",
DlgGenClass			: "Кла�?�? CSS",
DlgGenTitle			: "Заголовок",
DlgGenContType		: "Тип �?одержимого",
DlgGenLinkCharset	: "Кодировка",
DlgGenStyle			: "Стиль CSS",

// Image Dialog
DlgImgTitle			: "Свой�?тва изображени�?",
DlgImgInfoTab		: "Информаци�? о изображении",
DlgImgBtnUpload		: "По�?лать на �?ервер",
DlgImgURL			: "URL",
DlgImgUpload		: "Закачать",
DlgImgAlt			: "�?льтернативный тек�?т",
DlgImgWidth			: "Ширина",
DlgImgHeight		: "Вы�?ота",
DlgImgLockRatio		: "Сохран�?ть пропорции",
DlgBtnResetSize		: "Сбро�?ить размер",
DlgImgBorder		: "Бордюр",
DlgImgHSpace		: "Горизонтальный от�?туп",
DlgImgVSpace		: "Вертикальный от�?туп",
DlgImgAlign			: "Выравнивание",
DlgImgAlignLeft		: "По левому краю",
DlgImgAlignAbsBottom: "�?б�? понизу",
DlgImgAlignAbsMiddle: "�?б�? по�?ередине",
DlgImgAlignBaseline	: "По базовой линии",
DlgImgAlignBottom	: "Понизу",
DlgImgAlignMiddle	: "По�?ередине",
DlgImgAlignRight	: "По правому краю",
DlgImgAlignTextTop	: "Тек�?т наверху",
DlgImgAlignTop		: "По верху",
DlgImgPreview		: "Предварительный про�?мотр",
DlgImgAlertUrl		: "Пожалуй�?та введите URL изображени�?",
DlgImgLinkTab		: "С�?ылка",

// Flash Dialog
DlgFlashTitle		: "Свой�?тва Flash",
DlgFlashChkPlay		: "�?вто проигрывание",
DlgFlashChkLoop		: "Повтор",
DlgFlashChkMenu		: "Включить меню Flash",
DlgFlashScale		: "Ма�?штабировать",
DlgFlashScaleAll	: "Показывать в�?е",
DlgFlashScaleNoBorder	: "Без бордюра",
DlgFlashScaleFit	: "Точное �?овпадение",

// Link Dialog
DlgLnkWindowTitle	: "С�?ылка",
DlgLnkInfoTab		: "Информаци�? �?�?ылки",
DlgLnkTargetTab		: "Цель",

DlgLnkType			: "Тип �?�?ылки",
DlgLnkTypeURL		: "URL",
DlgLnkTypeAnchor	: "Якорь на �?ту �?траницу",
DlgLnkTypeEMail		: "Эл. почта",
DlgLnkProto			: "Протокол",
DlgLnkProtoOther	: "&lt;другое&gt;",
DlgLnkURL			: "URL",
DlgLnkAnchorSel		: "Выберите �?корь",
DlgLnkAnchorByName	: "По имени �?кор�?",
DlgLnkAnchorById	: "По идентификатору �?лемента",
DlgLnkNoAnchors		: "&lt;�?ет �?корей до�?тупных в �?том документе&gt;",
DlgLnkEMail			: "�?дре�? �?л. почты",
DlgLnkEMailSubject	: "Заголовок �?ообщени�?",
DlgLnkEMailBody		: "Тело �?ообщени�?",
DlgLnkUpload		: "Закачать",
DlgLnkBtnUpload		: "По�?лать на �?ервер",

DlgLnkTarget		: "Цель",
DlgLnkTargetFrame	: "&lt;фрейм&gt;",
DlgLnkTargetPopup	: "&lt;в�?плывающее окно&gt;",
DlgLnkTargetBlank	: "�?овое окно (_blank)",
DlgLnkTargetParent	: "Родитель�?кое окно (_parent)",
DlgLnkTargetSelf	: "Тоже окно (_self)",
DlgLnkTargetTop		: "Самое верхнее окно (_top)",
DlgLnkTargetFrameName	: "Им�? целевого фрейма",
DlgLnkPopWinName	: "Им�? в�?плывающего окна",
DlgLnkPopWinFeat	: "Свой�?тва в�?плывающего окна",
DlgLnkPopResize		: "Измен�?ющее�?�? в размерах",
DlgLnkPopLocation	: "Панель локации",
DlgLnkPopMenu		: "Панель меню",
DlgLnkPopScroll		: "Поло�?ы прокрутки",
DlgLnkPopStatus		: "Строка �?о�?то�?ни�?",
DlgLnkPopToolbar	: "Панель ин�?трументов",
DlgLnkPopFullScrn	: "Полный �?кран (IE)",
DlgLnkPopDependent	: "Зави�?имый (Netscape)",
DlgLnkPopWidth		: "Ширина",
DlgLnkPopHeight		: "Вы�?ота",
DlgLnkPopLeft		: "Позици�? �?лева",
DlgLnkPopTop		: "Позици�? �?верху",

DlnLnkMsgNoUrl		: "Пожалуй�?та введите URL �?�?ылки",
DlnLnkMsgNoEMail	: "Пожалуй�?та введите адре�? �?л. почты",
DlnLnkMsgNoAnchor	: "Пожалуй�?та выберете �?корь",

// Color Dialog
DlgColorTitle		: "Выберите цвет",
DlgColorBtnClear	: "Очи�?тить",
DlgColorHighlight	: "Под�?веченный",
DlgColorSelected	: "Выбранный",

// Smiley Dialog
DlgSmileyTitle		: "В�?тавить �?майлик",

// Special Character Dialog
DlgSpecialCharTitle	: "Выберите �?пециальный �?имвол",

// Table Dialog
DlgTableTitle		: "Свой�?тва таблицы",
DlgTableRows		: "Строки",
DlgTableColumns		: "Колонки",
DlgTableBorder		: "Размер бордюра",
DlgTableAlign		: "Выравнивание",
DlgTableAlignNotSet	: "<�?е у�?т.>",
DlgTableAlignLeft	: "Слева",
DlgTableAlignCenter	: "По центру",
DlgTableAlignRight	: "Справа",
DlgTableWidth		: "Ширина",
DlgTableWidthPx		: "пик�?елей",
DlgTableWidthPc		: "процентов",
DlgTableHeight		: "Вы�?ота",
DlgTableCellSpace	: "Промежуток (spacing)",
DlgTableCellPad		: "От�?туп (padding)",
DlgTableCaption		: "Заголовок",
DlgTableSummary		: "Summary",	//MISSING

// Table Cell Dialog
DlgCellTitle		: "Свой�?тва �?чейки",
DlgCellWidth		: "Ширина",
DlgCellWidthPx		: "пик�?елей",
DlgCellWidthPc		: "процентов",
DlgCellHeight		: "Вы�?ота",
DlgCellWordWrap		: "Заворачивание тек�?та",
DlgCellWordWrapNotSet	: "<�?е у�?т.>",
DlgCellWordWrapYes	: "Да",
DlgCellWordWrapNo	: "�?ет",
DlgCellHorAlign		: "Гор. выравнивание",
DlgCellHorAlignNotSet	: "<�?е у�?т.>",
DlgCellHorAlignLeft	: "Слева",
DlgCellHorAlignCenter	: "По центру",
DlgCellHorAlignRight: "Справа",
DlgCellVerAlign		: "Верт. выравнивание",
DlgCellVerAlignNotSet	: "<�?е у�?т.>",
DlgCellVerAlignTop	: "Сверху",
DlgCellVerAlignMiddle	: "По�?ередине",
DlgCellVerAlignBottom	: "Снизу",
DlgCellVerAlignBaseline	: "По базовой линии",
DlgCellRowSpan		: "Диапазон �?трок (span)",
DlgCellCollSpan		: "Диапазон колонок (span)",
DlgCellBackColor	: "Цвет фона",
DlgCellBorderColor	: "Цвет бордюра",
DlgCellBtnSelect	: "Выберите...",

// Find Dialog
DlgFindTitle		: "�?айти",
DlgFindFindBtn		: "�?айти",
DlgFindNotFoundMsg	: "Указанный тек�?т не найден.",

// Replace Dialog
DlgReplaceTitle			: "Заменить",
DlgReplaceFindLbl		: "�?айти:",
DlgReplaceReplaceLbl	: "Заменить на:",
DlgReplaceCaseChk		: "Учитывать реги�?тр",
DlgReplaceReplaceBtn	: "Заменить",
DlgReplaceReplAllBtn	: "Заменить в�?е",
DlgReplaceWordChk		: "Совпадение целых �?лов",

// Paste Operations / Dialog
PasteErrorPaste	: "�?а�?тройки безопа�?но�?ти вашего браузера не позвол�?ют редактору автоматиче�?ки выполн�?ть операции в�?тавки. Пожалуй�?та и�?пользуйте клавиатуру дл�? �?того (Ctrl+V).",
PasteErrorCut	: "�?а�?тройки безопа�?но�?ти вашего браузера не позвол�?ют редактору автоматиче�?ки выполн�?ть операции вырезани�?. Пожалуй�?та и�?пользуйте клавиатуру дл�? �?того (Ctrl+X).",
PasteErrorCopy	: "�?а�?тройки безопа�?но�?ти вашего браузера не позвол�?ют редактору автоматиче�?ки выполн�?ть операции копировани�?. Пожалуй�?та и�?пользуйте клавиатуру дл�? �?того (Ctrl+C).",

PasteAsText		: "В�?тавить только тек�?т",
PasteFromWord	: "В�?тавить из Word",

DlgPasteMsg2	: "Пожалуй�?та в�?тавьте тек�?т в пр�?моугольник и�?пользу�? �?очетание клавиш (<STRONG>Ctrl+V</STRONG>) и нажмите <STRONG>OK</STRONG>.",
DlgPasteIgnoreFont		: "Игнорировать определени�? гарнитуры",
DlgPasteRemoveStyles	: "Убрать определени�? �?тилей",
DlgPasteCleanBox		: "Очи�?тить",


// Color Picker
ColorAutomatic	: "�?втоматиче�?кий",
ColorMoreColors	: "Цвета...",

// Document Properties
DocProps		: "Свой�?тва документа",

// Anchor Dialog
DlgAnchorTitle		: "Свой�?тва �?кор�?",
DlgAnchorName		: "Им�? �?кор�?",
DlgAnchorErrorName	: "Пожалуй�?та введите им�? �?кор�?",

// Speller Pages Dialog
DlgSpellNotInDic		: "�?ет в �?ловаре",
DlgSpellChangeTo		: "Заменить на",
DlgSpellBtnIgnore		: "Игнорировать",
DlgSpellBtnIgnoreAll	: "Игнорировать в�?е",
DlgSpellBtnReplace		: "Заменить",
DlgSpellBtnReplaceAll	: "Заменить в�?е",
DlgSpellBtnUndo			: "Отменить",
DlgSpellNoSuggestions	: "- �?ет предположений -",
DlgSpellProgress		: "Идет проверка орфографии...",
DlgSpellNoMispell		: "Проверка орфографии закончена: ошибок не найдено",
DlgSpellNoChanges		: "Проверка орфографии закончена: ни одного �?лова не изменено",
DlgSpellOneChange		: "Проверка орфографии закончена: одно �?лово изменено",
DlgSpellManyChanges		: "Проверка орфографии закончена: 1% �?лов изменен",

IeSpellDownload			: "Модуль проверки орфографии не у�?тановлен. Хотите �?качать его �?ейча�??",

// Button Dialog
DlgButtonText	: "Тек�?т (Значение)",
DlgButtonType	: "Тип",

// Checkbox and Radio Button Dialogs
DlgCheckboxName		: "Им�?",
DlgCheckboxValue	: "Значение",
DlgCheckboxSelected	: "Выбранна�?",

// Form Dialog
DlgFormName		: "Им�?",
DlgFormAction	: "Дей�?твие",
DlgFormMethod	: "Метод",

// Select Field Dialog
DlgSelectName		: "Им�?",
DlgSelectValue		: "Значение",
DlgSelectSize		: "Размер",
DlgSelectLines		: "линии",
DlgSelectChkMulti	: "Разрешить множе�?твенный выбор",
DlgSelectOpAvail	: "До�?тупные варианты",
DlgSelectOpText		: "Тек�?т",
DlgSelectOpValue	: "Значение",
DlgSelectBtnAdd		: "Добавить",
DlgSelectBtnModify	: "Модифицировать",
DlgSelectBtnUp		: "Вверх",
DlgSelectBtnDown	: "Вниз",
DlgSelectBtnSetValue : "У�?тановить как выбранное значение",
DlgSelectBtnDelete	: "Удалить",

// Textarea Dialog
DlgTextareaName	: "Им�?",
DlgTextareaCols	: "Колонки",
DlgTextareaRows	: "Строки",

// Text Field Dialog
DlgTextName			: "Им�?",
DlgTextValue		: "Значение",
DlgTextCharWidth	: "Ширина",
DlgTextMaxChars		: "Мак�?. кол-во �?имволов",
DlgTextType			: "Тип",
DlgTextTypeText		: "Тек�?т",
DlgTextTypePass		: "Пароль",

// Hidden Field Dialog
DlgHiddenName	: "Им�?",
DlgHiddenValue	: "Значение",

// Bulleted List Dialog
BulletedListProp	: "Свой�?тва маркированного �?пи�?ка",
NumberedListProp	: "Свой�?тва нумерованного �?пи�?ка",
DlgLstType			: "Тип",
DlgLstTypeCircle	: "Круг",
DlgLstTypeDisc		: "Disc",	//MISSING
DlgLstTypeSquare	: "Квадрат",
DlgLstTypeNumbers	: "�?омера (1, 2, 3)",
DlgLstTypeLCase		: "Буквы нижнего реги�?тра (a, b, c)",
DlgLstTypeUCase		: "Буквы верхнего реги�?тра (A, B, C)",
DlgLstTypeSRoman	: "Малые рим�?кие буквы (i, ii, iii)",
DlgLstTypeLRoman	: "Большие рим�?кие буквы (I, II, III)",

// Document Properties Dialog
DlgDocGeneralTab	: "Общие",
DlgDocBackTab		: "Задний фон",
DlgDocColorsTab		: "Цвета и от�?тупы",
DlgDocMetaTab		: "Мета данные",

DlgDocPageTitle		: "Заголовок �?траницы",
DlgDocLangDir		: "�?аправление тек�?та",
DlgDocLangDirLTR	: "Слева на право (LTR)",
DlgDocLangDirRTL	: "Справа на лево (RTL)",
DlgDocLangCode		: "Код �?зыка",
DlgDocCharSet		: "Кодировка набора �?имволов",
DlgDocCharSetOther	: "Друга�? кодировка набора �?имволов",

DlgDocDocType		: "Заголовок типа документа",
DlgDocDocTypeOther	: "Другой заголовок типа документа",
DlgDocIncXHTML		: "Включить XHTML объ�?влени�?",
DlgDocBgColor		: "Цвет фона",
DlgDocBgImage		: "URL изображени�? фона",
DlgDocBgNoScroll	: "�?е�?кроллируемый фон",
DlgDocCText			: "Тек�?т",
DlgDocCLink			: "С�?ылка",
DlgDocCVisited		: "По�?ещенна�? �?�?ылка",
DlgDocCActive		: "�?ктивна�? �?�?ылка",
DlgDocMargins		: "От�?тупы �?траницы",
DlgDocMaTop			: "Верхний",
DlgDocMaLeft		: "Левый",
DlgDocMaRight		: "Правый",
DlgDocMaBottom		: "�?ижний",
DlgDocMeIndex		: "Ключевые �?лова документа (разделенные зап�?той)",
DlgDocMeDescr		: "Опи�?ание документа",
DlgDocMeAuthor		: "�?втор",
DlgDocMeCopy		: "�?втор�?кие права",
DlgDocPreview		: "Предварительный про�?мотр",

// Templates Dialog
Templates			: "Шаблоны",
DlgTemplatesTitle	: "Шаблоны �?одержимого",
DlgTemplatesSelMsg	: "Пожалуй�?та выберете шаблон дл�? открыти�? в редакторе<br>(текущее �?одержимое будет потер�?но):",
DlgTemplatesLoading	: "Загрузка �?пи�?ка шаблонов. Пожалуй�?та подождите...",
DlgTemplatesNoTpl	: "(�?и одного шаблона не определено)",

// About Dialog
DlgAboutAboutTab	: "О программе",
DlgAboutBrowserInfoTab	: "Информаци�? браузера",
DlgAboutVersion		: "Вер�?и�?",
DlgAboutLicense		: "Лицензировано в �?оответ�?твии �? у�?лови�?ми GNU Lesser General Public License",
DlgAboutInfo		: "Дл�? большей информации, по�?етите"
}