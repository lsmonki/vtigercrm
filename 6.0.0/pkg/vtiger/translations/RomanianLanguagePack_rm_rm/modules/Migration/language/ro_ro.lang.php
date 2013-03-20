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


$mod_strings = Array(
'LBL_MIGRATE_INFO'=>'Introduceti valorile pentru migrarea datelor de la <b><i> Sursa </i></b> la <b><i> Ultima versiune vtigerCRM </i></b>',
'LBL_CURRENT_VT_MYSQL_EXIST'=>'vtiger MySQL curent exista in',
'LBL_THIS_MACHINE'=>'Aceasta statie',
'LBL_DIFFERENT_MACHINE'=>'Alta statie',
'LBL_CURRENT_VT_MYSQL_PATH'=>'Calea curenta vtiger MySQL',
'LBL_SOURCE_VT_MYSQL_DUMPFILE'=>'vtiger <b>Sursa</b> Nume fisier dump',
'LBL_NOTE_TITLE'=>'Nota:',
'LBL_NOTES_LIST1'=>'Daca MySQL curent exista pe aceeasi statie atunci introduceti calea MySQL sau puteti introduce fisierul dump.',
'LBL_NOTES_LIST2'=>'Daca MySQL curent exista pe alta statie atunci introduceti sursa numelui fisierului dump precizand calea completa.',
'LBL_NOTES_DUMP_PROCESS'=>'Pentru a lua baza de date dump trebuie sa efectuati urmatoarea comanda din interiorul directorului <b>mysql/bin</b> 
			   <br><b>mysqldump --user="mysql_username"  --password="mysql-password" -h "hostname"  --port="mysql_port" "database_name" > dump_filename</b>
			   <br>add <b>SET FOREIGN_KEY_CHECKS = 0;</b> -- la inceputul fisierului dump
			   <br>add <b>SET FOREIGN_KEY_CHECKS = 1;</b> -- la finalul fisierului dump',
'LBL_NOTES_LIST3'=>'Precizati calea MySQL dupa modelul <b>/home/crm/vtigerCRM4_5/mysql</b>',
'LBL_NOTES_LIST4'=>'Precizati numele fisierului dump si calea completa dupa modelul <b>/home/fullpath/4_2_dump.txt</b>',

'LBL_CURRENT_MYSQL_PATH_FOUND'=>'Calea instalarii curente MySQL a fost gasita.',
'LBL_SOURCE_HOST_NAME'=>'Nume gazda sursa :',
'LBL_SOURCE_MYSQL_PORT_NO'=>'Numar port MySql sursa :',
'LBL_SOURCE_MYSQL_USER_NAME'=>'Nume utilizator MySql sursa :',
'LBL_SOURCE_MYSQL_PASSWORD'=>'Parola MySql sursa :',
'LBL_SOURCE_DB_NAME'=>'Nume baza de date sursa :',
'LBL_MIGRATE'=>'Migreaza la versiunea curenta',
//Added after 5 Beta 
'LBL_UPGRADE_VTIGER'=>'Actualizeaza baza de date vtiger CRM',
'LBL_UPGRADE_FROM_VTIGER_423'=>'Actualizeaza baza de date de la vtiger CRM 4.2.3 la 5.0.0',
'LBL_SETTINGS'=>'Setari',
'LBL_STEP'=>'Etapa',
'LBL_SELECT_SOURCE'=>'Selecteaza sursa',
'LBL_STEP1_DESC'=>'Pentru a incepe migrarea bzei de date, trebuie sa specificati formatul pentru datele vechi',
'LBL_RADIO_BUTTON1_TEXT'=>'Am acces la sistemul bazei de date in timp real vtiger CRM',
'LBL_RADIO_BUTTON1_DESC'=>'Aceasta optiune cere detalii despre adresa statiei gazda (unde DB este stocat) si accesul la DB. Prin aceasta metoda se suporta sistemele locale dar si la distanta. Consultati documentatie pentru Ajutor.',
'LBL_RADIO_BUTTON2_TEXT'=>'Am acces la baza de date dump arhivata vtiger CRM',
'LBL_RADIO_BUTTON2_DESC'=>'Aceasta optiune cere baza de date dump disponibila local pe aceeasi statie pe care are loc actualizarea. Nu puteti accesa datele dump de pe o alta statie (server de baze de date la distanta). Consultati documentatie pentru Ajutor.',
'LBL_RADIO_BUTTON3_TEXT'=>'Am o baza de date noua cu date 4.2.3',
'LBL_RADIO_BUTTON3_DESC'=>'Aceasta optiune cere detalii sistem baza de date pentru vtiger CRM 4.2.3, incluzand ID de server baza de date, utilizator, si parola. Nu puteti accesa datele dump de pe o alta statie (server de baze de date la distanta).',

'LBL_HOST_DB_ACCESS_DETAILS'=>'Detalii acces baza de date gazda',
'LBL_MYSQL_HOST_NAME_IP'=>'Nume gazda MySQL sau adresa IP : ',
'LBL_MYSQL_PORT'=>'Numar port MySQL : ',
'LBL_MYSQL_USER_NAME'=>'Utilizator MySql : ',
'LBL_MYSQL_PASSWORD'=>'Parola MySql : ',
'LBL_DB_NAME'=>'Nume baza de date : ',

'LBL_LOCATE_DB_DUMP_FILE'=>'Localizeaza fisier dump baza de date',
'LBL_DUMP_FILE_LOCATION'=>'Locatie fisier Dump : ',

'LBL_RADIO_BUTTON3_PROCESS'=>'<font color="red">Va rugam sa nu specificati detalii baza de date 4.2.3. Aceasta optiune va modifica direct baza de date in cauza.</font>
<br>Va recomandam urmatorii pasi.
<br>1. Luati un dump din baza de date 4.2.3
<br>2. Creati o baza de date noua (Este mai bine sa o creati pe serverul unde ruleaza baza de date vtiger 5.0.)
<br>3. Aplicati acest dump 4.2.3 la noua baza de date.
<br>Acordati noii baze de date detalii de acces. Aceasta migrare va modifica aceasta baza de date pentru a se incadra in schema 5.0.
Apoi puteti acorda un nume bazei de date in fisierul config.inc.php pentru a utiliza aceasta baza de date ie., $dbconfig[\'db_name\'] = \'new db name\';',

'LBL_ENTER_MYSQL_SERVER_PATH'=>'Introduceti calea MySQL Server',
'LBL_SERVER_PATH_DESC'=>'Calea MySQL pe server dupa modelul <b>/home/5beta/vtigerCRM5_beta/mysql/bin</b> or <b>c:\Program Files\mysql\bin</b>',
'LBL_MYSQL_SERVER_PATH'=>'Calea MySQL Server : ',
'LBL_MIGRATE_BUTTON'=>'Migreaza',
'LBL_CANCEL_BUTTON'=>'Anuleaza',
'LBL_UPGRADE_FROM_VTIGER_5X'=>'Actualizeaza baza de date de la vtiger CRM 5.x la urmatoarea versiune',
'LBL_PATCH_OR_MIGRATION'=>'trebuie specificata versiunea bazei de date sursa (actualizare Patch sau Migrare)',
//Added for java script alerts
'ENTER_SOURCE_HOST' => 'Introduceti nume gazda sursa',
'ENTER_SOURCE_MYSQL_PORT' => 'Introduceti nr port MySql sursa',
'ENTER_SOURCE_MYSQL_USER' => 'Introduceti nume utilizator MySql sursa',
'ENTER_SOURCE_DATABASE' => 'Introduceti nume baza de date sursa',
'ENTER_SOURCE_MYSQL_DUMP' => 'Introduceti fisierul dump MySQL valid',
'ENTER_HOST' => 'Introduceti nume gazda',
'ENTER_MYSQL_PORT' => 'Introduceti nr port MySql sursa',
'ENTER_MYSQL_USER' => 'Introduceti nume utilizator MySql sursa',
'ENTER_DATABASE' => 'Introduceti nume baza de date sursa',
'SELECT_ANYONE_OPTION' => 'Selectati orice optiune (una)',
'ENTER_CORRECT_MYSQL_PATH' => 'Introduceti calea corecta MySQL',

);






?>
