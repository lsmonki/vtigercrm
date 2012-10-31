<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific apmt_locationuage governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Defines the Romanian apmt_locationuage pack
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Magister Software SRL, Bucharest, Romania, www.magister.ro
 ********************************************************************************/

$mod_strings = Array(
'LBL_MODULE_NAME'=>'Calendar',
'LBL_MODULE_TITLE'=>'Calendar: Index',
'LBL_MODULE_APPOINTMENT'=>'Calendar: Intalnire',
'LBL_MODULE_APPOINTMENT_DETAIL'=>'Calendar: Detalii intalnire',
'LBL_SAVE'=>'Salveaza',
'LBL_RESET'=>'Anuleaza',
'LBL_LIST_USER_NAME'=>'Nume Utilizator',
'LBL_LIST_NAME'=>'Nume',
'LBL_CALENDAR_SHARED'=>'Calendar folosit in comun cu',
'LBL_CALENDAR_SHARING'=>'Folositi calendar impreuna cu',
'LBL_SHARING_OPTION'=>'Pot sa-mi vizualizez calendarul?',
'LBL_LIST_TOOLS'=>'Instrumente',
'LBL_SETTINGS'=>'Setari',
'LBL_CALSETTINGS'=>'Setari calendar',
'LBL_USE24'=>'Utilizeaza format 24 ore',
'LBL_CALSTART'=>'Lanseaza calendarul meu la ora',
'LBL_TIMESETTINGS'=>'Setari timp',
'LBL_HOLDFOLLOWUP'=>'Intalnirea va avea loc la',
'LBL_CALL' => 'Apel',
'LBL_MEET' => 'Intalnire',
'LBL_APPNT' => 'Intalnire',
'LBL_NEW_APPNT' => 'Intalnire noua',
'LBL_NEW_APPNT_INFO' => 'Creaza intalnire noua',
'LBL_VIEW_DAY_APPNT_INFO' => 'Vizualizeaza intalnirile pentru aceasta zi',
'LBL_CHANGE_APPNT' => 'vizualizeaza sau modifica aceasta intalnire (%s - %s)',

'LBL_DAY' => 'Ziua',
'LBL_DAY_BUTTON_KEY' => 'Z',
'LBL_DAY_BUTTON_TITLE' => 'Ziua [Alt+D]',
'LBL_DAY1' => 'Luni',
'LBL_DAY2' => 'Marti',
'LBL_DAY3' => 'Miercuri',
'LBL_DAY4' => 'Joi',
'LBL_DAY5' => 'Vineri',
'LBL_DAY6' => 'Sambata',
'LBL_DAY0' => 'Duminica',

'LBL_SM_MON' => 'Lun',
'LBL_SM_TUE' => 'Mar',
'LBL_SM_WED' => 'Mer',
'LBL_SM_THU' => 'Joi',
'LBL_SM_FRI' => 'Vin',
'LBL_SM_SAT' => 'Sam',
'LBL_SM_SUN' => 'Dum',

'LBL_DATE_TITLE' => 'Ziua %d, %A',

'LBL_WEEK' => 'Saptamana',
'LBL_WEEK_BUTTON_KEY' => 'W',
'LBL_WEEK_BUTTON_TITLE' => 'Saptamana [Alt+W]',
'LBL_WEEKS' => 'Saptamani',
'LBL_NEXT_WEEK' => 'saptamana urmatoare',
'LBL_LAST_WEEK' => 'saptamana trecuta',
'LBL_4WEEKS_BACK' => 'acum 4 saptamani',
'LBL_4WEEKS_PLUS' => 'peste 4 saptamani',
'LBL_RELOAD' => 'Reincarca',


'LBL_APPCREATED_BY' => 'Creat de catre',
'LBL_AT_DATE_TIME' => 'la',

'LBL_MON' => 'Luna',
'LBL_MON_BUTTON_KEY' => 'M',
'LBL_MON_BUTTON_TITLE' => 'Luna [Alt+M]',
'LBL_PREV_MON' => 'Luna precedenta',
'LBL_NEXT_MON' => 'Luna urmatoare',

'LBL_YEAR_BUTTON_KEY'=>'Y',
'LBL_MON_BUTTON_TITLE'=>'Anul [Alt+Y]',
'LBL_PREV_YEAR'=>'Anul precedent',
'LBL_NEXT_YEAR' => 'Anul urmator',

'LBL_APP_LOCATION' => 'Locatie',
'LBL_APP_IGNORE_TIME' => 'ignorati orarul de mai sus',
'LBL_SUBJECT'=>'Subiect:',
'LBL_APP_DESCRIPTION' => 'Descriere',
'LBL_CONTACT'=>'Contact:',

'LBL_APP_IGNORE_TIME2' => '(ex. intalnirea va avea loc<br /> dar nici o ora specificata zilele acestea)',

'LBL_APP_ERR001' =>'Data invalida in acest camp %s!',
'LBL_APP_ERR002' =>'Inceput dupa final!',
'LBL_APP_ERR003' =>'Contact lipsa!',
'LBL_APP_ERR004' =>'Subiect lipsa!',
'ERR_DELETE_RECORD'=>"Trebuie sa specificati un nr de inregistrare pentru a sterge aceasta intalnire.",
'DELETE_CONFIRMATION'=>"Sunteti sigur ca doriti sa stergeti aceasta intalnire?",

'AppLoc'=> Array('0' => 'La birou'
			, '1' => 'Iesit'
			, '2' => 'In concediu'
			, '3' => 'In concediu medical'
			, '4' => 'Reportat'
			, '5' => 'Optiune'
			, '6' => 'Privat'),

'cal_month_long'=>array(
"",
"Ianuarie",
"Februarie",
"Martie",
"Aprilie",
"Mai",
"Iunie",
"Iulie",
"August",
"Septembrie",
"Octombrie",
"Noiembrie",
"Decembrie",
),

'cal_weekdays_short'=>array(
"Lun",
"Mar",
"Mer",
"Joi",
"Vin",
"Sam",
"Dum",
),
'cal_weekdays_long'=>array(
"Luni",
"Marti",
"Miercuri",
"Joi",
"Vineri",
"Sambata",
"Duminica",
),
'cal_month_short'=>array(
"",
"Ian",
"Feb",
"Mar",
"Apr",
"Mai",
"Iun",
"Iul",
"Aug",
"Sep",
"Oct",
"Nov",
"Dec",
),

'LBL_TIME'=>'Ora',
'LBL_START_TIME' => 'Ora inceput',
'LBL_END_TIME' => 'Ora final',
'LBL_START_DATE'=>'Data inceput',
'LBL_TIME_START'=>'Ora inceput',
'LBL_DUE_DATE'=>'Data scadenta',
'LBL_START_DATE_TIME'=>'Data & ora inceput',
'LBL_END_DATE_TIME'=>'Data & ora final',
'LBL_TODO'=>'Task',
'LBL_TODOS'=>'Task-uri',
'LBL_EVENTS'=>'Evenimente',
'LBL_TOTALEVENTS'=>'Toate activitatile mele:',
'LBL_TOTALTODOS'=>'Toate task-urile mele:',
'LBL_VIEW'=>'Vizualizare',
'LBL_LISTVIEW'=>'Lista',
'LBL_HRVIEW'=>'Orara',
'LBL_WEEKVIEW'=>'Calendar saptamanal',
'LBL_MONTHVIEW'=>'Calendar lunar',
'LBL_YEARVIEW'=>'Calendar anual',
'LBL_STATUS'=>'Stare',
'LBL_ACTION'=>'Actiuni',
'LBL_ADD'=>'Adauga',
'LBL_OPENCAL'=>'Deschide calendar',


'LBL_ADD_EVENT'=>'Adauga eveniment',
'LBL_ADDCALL'=>'Apel',
'LBL_ADDMEETING'=>'Intalnire',
'LBL_ADDTODO'=>'Task',
'LBL_BEFOREEVENT'=>'inainte de inceputul evenimentului',
'LBL_BEFORETASK'=>'inainte de inceputul task-ului',
'LBL_EVENTDETAILS'=>'Detalii eveniment',
'LBL_CURSTATUS'=>'Stare actuala',
'LBL_ASSINGEDTO'=>'Asignat la',
'LBL_RELATEDTO'=>'Se refera la',
'LBL_PENDING'=>'In asteptare',
'LBL_PUBLIC'=>'Marcheaza public',
'LBL_MORE'=>'Mai mult',
'LBL_EDIT'=>'Editeaza',
'LBL_EVERYDAY'=>'Zilnic',
'LBL_EVERYWEEK'=>'Saptamanal',
'LBL_EVERYMON'=>'Lunar',
'LBL_WEEKS'=>'Saptamana/i',
'LBL_MONTHS'=>'Luna/i',
'LBL_YEAR'=>'An',
'LBL_NONE_SCHEDULED'=>'Nimic programat',

'LBL_INVITE_INST1'=>'Pentru a invita, selectati utilizatorii din lista "Utilizatori disponibili" si apasati butonul "Adauga".',
'LBL_INVITE_SHARE'=>'Pentru a sharui, selectati utilizatorii din lista "Utilizatori disponibili" si apasati butonul "Adauga".',
'LBL_INVITE_INST2'=>'Pentru a sterge, selectati utilizatorii din lista "Utilizatori selectati" si apasati butonul "Sterge".',
'LBL_SELUSR_INFO'=>' Utilizatorii selectati vor primi un email despre eveniment.',
'LBL_CALSHAREMESSAGE'=>'Voi partaja calendarul meu cu urmatorii utilizatori selectati',
'LBL_CALSHARE'=>'Partajare calendar',
'LBL_SEL_USERS'=>'Utilizatori selectati',
'LBL_AVL_USERS'=>'Utilizatori disponibili',
'LBL_ADD_BUTTON'=>'Adauga',
'LBL_USERS'=>'Utilizatori',
'LBL_RMV_BUTTON'=>'Sterge',
'LBL_SDRMD'=>'Trimite atentionare catre',
'LBL_ENABLE_REPEAT'=>'Activeaza repetare',
'LBL_REPEAT_ONCE'=>'Repeta o singura data in fiecare',
'LBL_ADD_TODO'=>'Creaza task',
'LBL_TODONAME'=>'Task',
'LBL_TODODATETIME'=>'Ora & data',





//DON'T CONVERT THESE THEY ARE MAPPINGS - STARTS
'db_last_name' => 'LBL_LIST_LAST_NAME',
'db_first_name' => 'LBL_LIST_FIRST_NAME',
'db_title' => 'LBL_LIST_TITLE',
'db_email1' => 'LBL_LIST_EMAIL_ADDRESS',
'db_email2' => 'LBL_LIST_EMAIL_ADDRESS',
//DON'T CONVERT THESE THEY ARE MAPPINGS -ENDS
'LBL_COMPLETED'=>'Marcheaza Completat',
'LBL_DEFERRED'=>'Marcheaza Amanat',
'LBL_HELD'=>'Marcheaza Realizat',
'LBL_NOTHELD'=>'Marcheaza Nerealizat',
'LBL_POSTPONE'=>'Amana',
'LBL_CHANGEOWNER'=>'Modifica proprietar',
'LBL_DEL'=>'Sterge',

//Added for actvity merge with calendar
'LBL_SEARCH_FORM_TITLE'=>'Cauta activitate',
'LBL_LIST_FORM_TITLE'=>'Lista activitati',
'LBL_NEW_FORM_TITLE'=>'Activitate noua',
'LBL_TASK_INFORMATION'=>'Info task',
'LBL_EVENT_INFORMATION'=>'Info eveniment',
'LBL_CALENDAR_INFORMATION'=>'Info calendar',

'LBL_NAME'=>'Subiect :',
'LBL_ACTIVITY_NOTIFICATION'=>'Va aducem la cunostinta ca o activitate asignata dvs. a fost',
'LBL_ACTIVITY_INVITATION'=>'Va aducem la cunostinta ca o activitate la care sunteti invitat a fost',
'LBL_DETAILS_STRING'=>'Detaliile sunt',
'LBL_REGARDS_STRING'=>'Multumesc',
'LBL_CONTACT_NAME'=>'Nume contact',
'LBL_OPEN_ACTIVITIES'=>'Activitati deschise',
'LBL_ACTIVITY'=>'Activitate:',
'LBL_HISTORY'=>'Istoric',
'LBL_UPCOMING'=>"Activitatile urmatoare si in suspans",
'LBL_TODAY'=>'complet ',

'LBL_NEW_TASK_BUTTON_TITLE'=>'Task nou [Alt+N]',
'LBL_NEW_TASK_BUTTON_KEY'=>'N',
'LBL_NEW_TASK_BUTTON_LABEL'=>'Task nou',
'LBL_SCHEDULE_MEETING_BUTTON_TITLE'=>'Programeaza intalnire [Alt+M]',
'LBL_SCHEDULE_MEETING_BUTTON_KEY'=>'M',
'LBL_SCHEDULE_MEETING_BUTTON_LABEL'=>'Programeaza intalnire',
'LBL_SCHEDULE_CALL_BUTTON_TITLE'=>'Programeaza apel [Alt+C]',
'LBL_SCHEDULE_CALL_BUTTON_KEY'=>'C',
'LBL_SCHEDULE_CALL_BUTTON_LABEL'=>'Programeaza apel',
'LBL_NEW_NOTE_BUTTON_TITLE'=>'Nota noua [Alt+T]',
'LBL_NEW_ATTACH_BUTTON_TITLE'=>'Ataseaza fisier [Alt+F]',
'LBL_NEW_NOTE_BUTTON_KEY'=>'T',
'LBL_NEW_ATTACH_BUTTON_KEY'=>'F',
'LBL_NEW_NOTE_BUTTON_LABEL'=>'Nota noua',
'LBL_NEW_ATTACH_BUTTON_LABEL'=>'Ataseaza fisier',
'LBL_TRACK_EMAIL_BUTTON_TITLE'=>'Urmareste email [Alt+K]',
'LBL_TRACK_EMAIL_BUTTON_KEY'=>'K',
'LBL_TRACK_EMAIL_BUTTON_LABEL'=>'Urmareste email',

'LBL_LIST_CLOSE'=>'Inchide',
'LBL_LIST_STATUS'=>'Stare',
'LBL_LIST_CONTACT'=>'Contact',
//Added for 4.2 release for Account column support as shown by Fredy
'LBL_LIST_ACCOUNT'=>'Cont',
'LBL_LIST_RELATED_TO'=>'Se refera la',
'LBL_LIST_DUE_DATE'=>'Data scadenta',
'LBL_LIST_DATE'=>'Data',
'LBL_LIST_SUBJECT'=>'Subiect',
'LBL_LIST_LAST_MODIFIED'=>'Modificat la data',
'LBL_LIST_RECURRING_TYPE'=>'Tip recurent',

'ERR_DELETE_RECORD'=>"Trebuie specificat un nr de referinta pentru a sterge contul vtiger_account.",
'NTC_NONE_SCHEDULED'=>'Nimic programat.',

// Added vtiger_fields for Attachments in Activities/SubPanelView.php
'LBL_ATTACHMENTS'=>'Atasamente',
'LBL_NEW_ATTACHMENT'=>'Atasament nou',

//Added vtiger_fields after RC1 - Release
'LBL_ALL'=>'Toate',
'LBL_CALL'=>'Apel',
'LBL_MEETING'=>'Intalnire',
'LBL_TASK'=>'Task',

//Added for 4GA Release
'Subject'=>'Subiect',
'Assigned To'=>'Asignat la',
'Start Date & Time'=>'Data & ora inceput',
'Time Start'=>'Ora inceput',
'Due Date'=>'Data scadenta',
'Related To'=>'Se refera la',
'Contact Name'=>'Nume contact',
'Status'=>'Stare',
'Priority'=>'Prioritate',
'Visibility'=>'Vizibilitate',
'Send Notification'=>'Trimite notificare',
'Created Time'=>'Creat la ora',
'Modified Time'=>'Modificat la ora',
'Activity Type'=>'Tip activitate',
'Description'=>'Descriere',
'Duration'=>'Durata',
'Duration Minutes'=>'Durata in minute',
'Location'=>'Locatie',
'No Time'=>'Lipseste ora',
//Added for Send Reminder 4.2 release
'Send Reminder'=>'Trimite atentionare',
'LBL_YES'=>'Da',
'LBL_NO'=>'Nu',
'LBL_DAYS'=>'Zi(le)',
'LBL_MINUTES'=>'minute',
'LBL_HOURS'=>'ore',
'LBL_BEFORE_EVENT'=>'inainte de eveniment',
//Added for CustomView 4.2 Release
'Close'=>'Inchide',
'Start Date'=>'Data inceput',
'Type'=>'Tip',
'End Date'=>'Data final',
'Recurrence'=> 'Evenimente recurente',
'Recurring Type'=> 'Tip recurent',
//Activities - Notification Error
'LBL_NOTIFICATION_ERROR'=>'Eroare email : Va rugam sa verificati configuratia serverului de plecare emailuri in Setari->Configurare Server plecare emailuri sau MailId pentru acest utilizator nu este configurat',
// Mike Crowe Mod --------------------------------------------------------added for generic search
'LBL_GENERAL_INFORMATION'=>'Info generala',

'LBL_EVENTTYPE'=>'Tip Eveniment',
'LBL_EVENTNAME'=>'Nume Eveniment',
'LBL_EVENTSTAT'=>'Evenimentul incepe la',
'LBL_EVENTEDAT'=>'Evenimentul se termina la',
'LBL_INVITE'=>'Invita',
'LBL_REPEAT'=>'Repeta',
'LBL_REMINDER'=>'Atentionare',
'LBL_SENDREMINDER'=>'Trimite atentionare',
'LBL_NOTIFICATION'=>'Notificare',
'LBL_SENDNOTIFICATION'=>'Trimite notificare',
'LBL_RMD_ON'=>'Atentioneaza la',
'LBL_REPEATEVENT'=>'Repeta o data la fiecare',
'LBL_TIMEDATE'=>'Ora & data',
'LBL_HR'=>'ore',
'LBL_MIN'=>'min',
'LBL_EVENT'=>'Eveniment',
'Daily'=>'Zi(le)',
'Weekly'=>'Saptamana(i)',
'Monthly'=>'Luna(i)',
'Yearly'=>'An',
'createdtime'=>'Creat la ora',
'modifiedtime'=>'Modificat la ora',
'first'=>'Primul',
'last'=>'Ultimul',
'High'=>'Fierbinte',
'Medium'=>'Mediu',
'Low'=>'Rece',
'LBL_SELECT'=>'Selecteaza',
'LBL_ALL_EVENTS_TODOS'=>'Toate Evenimentele si Task-urile',
'First'=>'Primul', 
'Last'=>'Ultimul', 
'on'=>'la', 
'day of the month'=>'ziua lunii',
'Private'=>'Privat',
'Public'=>'Public',

//Added for existing Picklist entries

'Planned'=>'Planificat',
'Held'=>'A avut loc',
'Not Held'=>'Nu a avut loc',
'Completed'=>'Finalizat',
'Deferred'=>'Amanat',
'Not Started'=>'Neinceput',
'In Progress'=>'In desfasurare',
'Pending Input'=>'In suspans',
'LBL_REMAINDER_DAY'=>'zile',
'LBL_REMAINDER_HRS'=>'ore',
'Call'=>'Apel',
'Meeting'=>'Intalnire',

//added to send dates and time in calendar notification/invitation mail.

'Start date and time'=>'Data & ora inceput',
'End date and time'=>'Data & ora final',
//this is for task
'End date'=>'Data final',
'LBL_SET_DATE'=>'Seteaza data..',
'Recurrence'=>'Repetare',

//added to send invitation mail Subject.
'INVITATION'=>' Invitatie ',

// Added/Updated for vtiger CRM 5.0.4
'LBL_YEAR_BUTTON_TITLE'=>'An [Alt+Y]',
'LBL_SELECT_CONTACT'=>'Selecteaza Contacte',
'SHARED_EVENT_DEL_MSG'=>'Utilizatorul nu are permisiunea sa Editeze/Stearga Evenimente partajate.',
//added to fix ticket#4525
'LBL_CREATED'=>'nou creata',
'LBL_UPDATED'=>'actualizata',

//Added after 5.0.4 GA
'LBL_BUSY' => 'Ocupat',

//Custom Fields support for Calendar
'LBL_CUSTOM_INFORMATION'=>'Informatie Personalizata',

// Repeat Event support for Calendar
'LBL_UNTIL' => 'Pana la',
'LBL_SET_DATE'=>'Seteaza Data',

'LBL_MINE' =>'Ale mele',

);

?>
