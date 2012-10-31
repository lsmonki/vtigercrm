<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): Magister Software SRL, Bucharest, Romania, www.magister.ro
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/language/en_us.lang.php,v 1.17 2005/03/28 06:31:38 rank Exp $
 * Description:  Defines the Romanian language pack for the Account module.
 ********************************************************************************/
 
$mod_strings = Array(
// Mike Crowe Mod --------------------------------------------------------added for general search
'LBL_GENERAL_INFORMATION'=>'Info generala',

'LBL_MODULE_NAME'=>'Email-uri',
'LBL_MODULE_TITLE'=>'Email-uri: Index',
'LBL_SEARCH_FORM_TITLE'=>'Cauta email',
'LBL_LIST_FORM_TITLE'=>'Lista email-uri',
'LBL_NEW_FORM_TITLE'=>'Cauta email',

'LBL_LIST_SUBJECT'=>'Subiect',
'LBL_LIST_CONTACT'=>'Contact',
'LBL_LIST_RELATED_TO'=>'Se refera la',
'LBL_LIST_DATE'=>'Trimis la data',
'LBL_LIST_TIME'=>'Trimis la ora',

'ERR_DELETE_RECORD'=>"Trebuie sa specificati un nr de inregistrare pentru a sterge acest cont.",
'LBL_DATE_SENT'=>'Trimis la data:',
'LBL_DATE_AND_TIME'=>'Trimis la data & ora:',
'LBL_DATE'=>'Trimis la data:',
'LBL_TIME'=>'Trimis la ora:',
'LBL_SUBJECT'=>'Subiect:',
'LBL_BODY'=>'Mesaj:',
'LBL_CONTACT_NAME'=>'Nume Contact: ',
'LBL_EMAIL'=>'Email:', 
'LBL_DETAILVIEW_EMAIL'=>'Email', 
'LBL_COLON'=>':',
'LBL_CHK_MAIL'=>'Verifica posta',
'LBL_COMPOSE'=>'Compune',
//Single change for 5.0.3
'LBL_SETTINGS'=>'Setari pentru Serverul primire emailuri',
'LBL_EMAIL_FOLDERS'=>'Directoare email',
'LBL_INBOX'=>'Inbox',
'LBL_SENT_MAILS'=>'Email-uri trimise',
'LBL_TRASH'=>'Gunoi',
'LBL_JUNK_MAILS'=>'Emailuri Junk',
'LBL_TO_LEADS'=>'Catre Prospectari',
'LBL_TO_CONTACTS'=>'Catre Contacte',
'LBL_TO_ACCOUNTS'=>'Catre Conturi',
'LBL_MY_MAILS'=>'Email-urile mele',
'LBL_QUAL_CONTACT'=>'Email-uri contacte',
'LBL_MAILS'=>'Email-uri',
'LBL_QUALIFY_BUTTON'=>'Califica',
'LBL_REPLY_BUTTON'=>'Raspunde',
'LBL_FORWARD_BUTTON'=>'Trimite mai departe',
'LBL_DOWNLOAD_ATTCH_BUTTON'=>'Descarca atasamente',
'LBL_FROM'=>'De la :',
'LBL_CC'=>'Cc :',
'LBL_BCC'=>'Bcc :',

'NTC_REMOVE_INVITEE'=>'Sunteti sigur ca doriti sa stergeti destinatarul acestui email?',
'LBL_INVITEE'=>'Destinatari',

// Added Fields
// Contacts-SubPanelViewContactsAndUsers.php
'LBL_BULK_MAILS'=>'Email-uri Bulk',
'LBL_ATTACHMENT'=>'Atasament',
'LBL_UPLOAD'=>'Incarca',
'LBL_FILE_NAME'=>'Nume fisier',
'LBL_SEND'=>'Trimite',

'LBL_EMAIL_TEMPLATES'=>'Modele email',
'LBL_TEMPLATE_NAME'=>'Nume model',
'LBL_DESCRIPTION'=>'Descriere',
'LBL_EMAIL_TEMPLATES_LIST'=>'Lista modele email',
'LBL_EMAIL_INFORMATION'=>'Info email',




//for v4 release added
'LBL_NEW_LEAD'=>'Prospectare noua',
'LBL_LEAD_TITLE'=>'Prospectari',

'LBL_NEW_PRODUCT'=>'Produs nou',
'LBL_PRODUCT_TITLE'=>'Produse',
'LBL_NEW_CONTACT'=>'Contact nou',
'LBL_CONTACT_TITLE'=>'Contacte',
'LBL_NEW_ACCOUNT'=>'Cont nou',
'LBL_ACCOUNT_TITLE'=>'Conturi',

// Added vtiger_fields after vtiger4 - Beta
'LBL_USER_TITLE'=>'Utilizatori',
'LBL_NEW_USER'=>'Utilizator nou',

// Added for 4 GA
'LBL_TOOL_FORM_TITLE'=>'Instrumente email',
//Added for 4GA
'Date & Time Sent'=>'Trimis la data & ora',
'Sales Enity Module'=>'Modul entitate vanzari',
'Related To'=>'Se refera la',
'Assigned To'=>'Asignat la',
'Subject'=>'Subiect',
'Attachment'=>'Atasament',
'Description'=>'Descriere',
'Time Start'=>'Ora inceput',
'Created Time'=>'Creat la ora',
'Modified Time'=>'Modificat la ora',

'MESSAGE_CHECK_MAIL_SERVER_NAME'=>'Verifica numele server Mail...',
'MESSAGE_CHECK_MAIL_ID'=>'Verifica ID asignare utilizator...',
'MESSAGE_MAIL_HAS_SENT_TO_USERS'=>'Email-ul a fost trimis urmatorului/ilor utilizator/i :',
'MESSAGE_MAIL_HAS_SENT_TO_CONTACTS'=>'Email-ul a fost trimis urmatoarei/lor persoane contact :',
'MESSAGE_MAIL_ID_IS_INCORRECT'=>'ID email incorect. Verifica ID email...',
'MESSAGE_ADD_USER_OR_CONTACT'=>'Adauga utilizator sau contact...',
'MESSAGE_MAIL_SENT_SUCCESSFULLY'=>' Email/uri trimis/e cu succes!',

// Added for web mail post 4.0.1 release
'LBL_FETCH_WEBMAIL'=>'Descarca Web Mail',
//Added for 4.2 Release -- CustomView
'LBL_ALL'=>'Toate',
'MESSAGE_CONTACT_NOT_WANT_MAIL'=>'Acest contact nu doreste sa primeasca emailuri.',
'LBL_WEBMAILS_TITLE'=>'Email-uri web',
'LBL_EMAILS_TITLE'=>'Email',
'LBL_MAIL_CONNECT_ERROR_INFO'=>'Eroare conectare la server mail!<br> Verifica in Conturile mele->Listeaza Mail Server -> Listeaza cont Mail',
'LBL_ALLMAILS'=>'Toate Email-urile',
'LBL_TO_USERS'=>'Catre Utilizatori',
'LBL_TO'=>'Catre:',
'LBL_IN_SUBJECT'=>'in subiect',
'LBL_IN_SENDER'=>'in expeditor',
'LBL_IN_SUBJECT_OR_SENDER'=>'in subiect sau expeditor',
'SELECT_EMAIL'=>'Selecteaza ID email',
'Sender'=>'Expeditor',
'LBL_CONFIGURE_MAIL_SETTINGS'=>'Server primire emailuri nu este configurat',
'LBL_MAILSELECT_INFO1'=>'Urmatoarele tipuri de ID-uri email sunt asociate cu optiunile selectate',
'LBL_MAILSELECT_INFO2'=>'Selecteaza tipuri de ID email pentru care trebuie trimis emailul',
'LBL_MULTIPLE'=>'Multiple',
'LBL_COMPOSE_EMAIL'=>'Compune email',
'LBL_VTIGER_EMAIL_CLIENT'=>'Client Webmail CRM',

//Added for 5.0.3
'TITLE_VTIGERCRM_MAIL'=>'vtigerCRM Mail',
'TITLE_COMPOSE_MAIL'=>'Compune email',

'MESSAGE_MAIL_COULD_NOT_BE_SEND'=>'Emailul nu a putut fi trimis catre utilizatorul asignat.',
'MESSAGE_PLEASE_CHECK_ASSIGNED_USER_EMAILID'=>'Verifica ID email asignat acestui utilizator...',
'MESSAGE_PLEASE_CHECK_THE_FROM_MAILID'=>'Verifica ID email',
'MESSAGE_MAIL_COULD_NOT_BE_SEND_TO_THIS_EMAILID'=>'Emailul nu a putut fi trimis la acest ID email',
'PLEASE_CHECK_THIS_EMAILID'=>'Verifica acest ID email...',
'LBL_CC_EMAIL_ERROR'=>'cc mailid incorect',
'LBL_BCC_EMAIL_ERROR'=>'bcc mailid incorect',
'LBL_NO_RCPTS_EMAIL_ERROR'=>'Destinatari nespecificati',
'LBL_CONF_MAILSERVER_ERROR'=>'serverul plecare emailuri trebuie configurat din setari ---> Outgoing Server link',
'LBL_VTIGER_EMAIL_CLIENT'=>'Client Webmail CRM',
'LBL_MAILSELECT_INFO3'=>'Acces interzis pentru vizualizarea ID-urilor email pentru inregistrarile selectate.',
//Added  for script alerts
'FEATURE_AVAILABLE_INFO' => 'Aceasta functionalitate este disponibila in acest moment numai pentru Microsoft Internet Explorer 5.5+ users\n\nWait f
or an update!',
'DOWNLOAD_CONFIRAMATION' => 'Doriti sa descarcati fisierul?',
'LBL_PLEASE_ATTACH' => 'Ataseaza fisier valid si incearca din nou!',
'LBL_KINDLY_UPLOAD' => 'Configureaza <font color="red">upload_tmp_dir</font> variable in php.ini file.',
'LBL_EXCEED_MAX' => 'Ne pare rau, fisierul urcat depaseste limita maxima de dimensiune. Incercati din nou cu un fisier mai mic ',
'LBL_BYTES' => ' bytes',
'LBL_CHECK_USER_MAILID' => 'Verifica ID email al utilizatorului curent. Pentru a trimite emailuri este necesar un ID email valid',

// Added/Updated for vtiger CRM 5.0.4
'Activity Type'=>'Tip Activitate',
'LBL_MAILSELECT_INFO'=>'are urmatorul ID email asociat. Va rugam sa selectati ID-ul email catre care trebuie trimis emailul',
'LBL_NO_RECORDS' => 'Zero inregistrari gasite',
'LBL_PRINT_EMAIL'=> 'Printeaza',

);

?>
