<?php
 /*+********************************************************************************
 * Terms & Conditions are placed on the: http://vtiger.com.pl
 ********************************************************************************
 *  Language		: Język Polski
 *  Vtiger Version	: 5.4.x
 *	Pack Version	: 1.13
 *  Author          : OpenSaaS Sp. z o.o. 
 *  Licence			: GPL
 *  Help/Email      : bok@opensaas.pl                                                                                                                 
 *  Website         : www.vtiger.com.pl, www.opensaas.pl
 ********************************************************************************+*/
$mod_strings = Array(
'LBL_MIGRATE_INFO' => 'Wprowadź dane potrzebne do przeprowadzenia aktualizacji  <b><i> Source </i></b> to <b><i> Current (Latest) vtigerCRM </i></b>',
'LBL_CURRENT_VT_MYSQL_EXIST' => 'Baza danych MySQL systemu vTiger  znajduje się na',
'LBL_THIS_MACHINE' => 'tym samym komputerze / localhost /',
'LBL_DIFFERENT_MACHINE' => 'innym komputerze / serwerze /',
'LBL_CURRENT_VT_MYSQL_PATH' => 'Ścieżka do bazy danych MySQL',
'LBL_SOURCE_VT_MYSQL_DUMPFILE' => 'vtiger <b>Source</b> nazwa pliku Kopii Zapasowej',
      'LBL_NOTE_TITLE' => 'Uwaga:',
      'LBL_NOTES_LIST1' => 'Jeśli baza mySql istnieje na tym samym komputerze to podaj do niej ścieżkę. Możesz też określić plik Kopii Zapasowej jeśli go posiadasz.',
      'LBL_NOTES_LIST2' => 'Jeśli baza mySql istnieje na innym komputerze (Serwerze) to podaj pełną ścieżkę do pliku Kopii Zapasowej.',
      'LBL_NOTES_DUMP_PROCESS' => 'Aby uzyskać kopię bazy danych mySql proszę wykonać polecenie z lokalizacji <b>mysql/bin</b> 
 <br><b>mysqldump --user="mysql_username"  --password="mysql-password" -h "hostname"  --port="mysql_port" "database_name" > dump_filename</b>
			   <br>dodaj <b>SET FOREIGN_KEY_CHECKS = 0;</b> -- na początku pliku Kopii Zapasowej
			   <br>dodaj <b>SET FOREIGN_KEY_CHECKS = 1;</b> -- na końcu pliku Kopii Zapasowej',
      'LBL_NOTES_LIST3' => 'Podaj ścieżkę do Twojej bazy danych MySQL , przykładowo <b>/home/crm/vtigerCRM4_5/mysql</b>',
      'LBL_NOTES_LIST4' => 'Wpisz pełną ścieżkę z nazwą pliku Kopii Zapasowej w stylu <b>/home/fullpath/5_1_kopia.txt</b>',
      'LBL_CURRENT_MYSQL_PATH_FOUND' => 'Ścieżka do aktualnej instalacji  mySql została znaleziona.',
      'LBL_SOURCE_HOST_NAME' => 'Nazwa Hosta (Serwera z MySql) :',
      'LBL_SOURCE_MYSQL_PORT_NO' => 'MySql - Numer Portu :',
      'LBL_SOURCE_MYSQL_USER_NAME' => 'MySql - Nazwa Użytkownika :',
      'LBL_SOURCE_MYSQL_PASSWORD' => 'MySql - Hasło :',
      'LBL_SOURCE_DB_NAME' => 'MySql - Nazwa Bazy Danych  :',
      'LBL_MIGRATE' => 'Atualizacja do Najnowszej Wersji vTiger\'a',
      'LBL_UPGRADE_VTIGER' => 'Aktualizacja bazy danych systemu vtiger CRM',
      'LBL_UPGRADE_FROM_VTIGER_423' => 'Upgrade bazy danych z vtiger CRM 4.2.3  do 5.0.0',
      'LBL_SETTINGS' => 'Narzędzia konfiguracyjne',
      'LBL_STEP' => 'Krok',
      'LBL_SELECT_SOURCE' => 'Wybierz tryb aktualizacji stosownie do Twojej wersji systemu',
      'LBL_STEP1_DESC' => 'Aby rozpocząć Aktualizację bazy danych musisz określić format w jakim  są zapisane dotychczasowe dane.',
      'LBL_RADIO_BUTTON1_TEXT' => 'Mam dostęp administracyjny do bazy danych systemu vTiger',
      'LBL_RADIO_BUTTON1_DESC' => 'Ta opcja wymaga podania nazwy serwera (hosta) bazy danych, nazwy bazy, użytkownika i hasła. Oba - lokalny i zdalny serwer - są obsługiwane w tej opcji.',
      'LBL_RADIO_BUTTON2_TEXT' => 'My dostęp do Kopii Zapasowej vtiger CRM.',
      'LBL_RADIO_BUTTON2_DESC' => 'Ta opcja wymaga posiadania na tym samym komputerze pliku Kopii Zapasowej bazy danych. Nie można wskazać zdalnej lokalizacji ( np. na serwerze).',
      'LBL_RADIO_BUTTON3_TEXT' => 'My nową instalację wersji  4.2.3 ',
      'LBL_RADIO_BUTTON3_DESC' => 'Lepiej zainstaluj od razu 5.x  !!!',
      'LBL_HOST_DB_ACCESS_DETAILS' => 'Dane dostępowe do serwera bazy danych',
      'LBL_MYSQL_HOST_NAME_IP' => 'MySQL - nazwa hosta (serwera) lub IP Address : ',
      'LBL_MYSQL_PORT' => 'MySQL - Numer Portu : ',
      'LBL_MYSQL_USER_NAME' => 'MySql - Nazwa Użytkownika : ',
      'LBL_MYSQL_PASSWORD' => 'MySql - Hasło : ',
      'LBL_DB_NAME' => 'Nazwa bazy danych : ',
      'LBL_LOCATE_DB_DUMP_FILE' => 'Lokalizacja pliku Kopii Zapasowej bazy danych',
      'LBL_DUMP_FILE_LOCATION' => 'Lokalizacja Kopii Zapasowej : ',
      'LBL_RADIO_BUTTON3_PROCESS' => '<font color="red">Proszę nie wpisywać danych bazy  z wersji 4.2.3. This option will alter the given database directly.</font>
<br>It is strongly recommended that to do the following.
<br>1. Take a dump of your 4.2.3 database
<br>2. Create new database (Better is to create a database in the server where your vtiger 5.0 Database is running.)
<br>3. Apply this 4.2.3 dump to this new database.
<br>Now give this new database access details. This migration will modify this Database to fit with the 5.0 Schema.
Then you can give this Database name in config.inc.php file to use this Database ie., $dbconfig[\'db_name\'] = \'new db name\';',
      'LBL_ENTER_MYSQL_SERVER_PATH' => 'Podaj ścieżkę do serwera bazy danych  MySQL',
      'LBL_SERVER_PATH_DESC' => 'MySQL - ścieżka na serwerze jak np. (Linux) <b>/home/5beta/vtigerCRM5_beta/mysql/bin</b> lub (Windows) <b>c:\Program Files\mysql\bin</b>',
      'LBL_MYSQL_SERVER_PATH' => 'Ścieżka do serwera bazy danych MySQL : ',
      'LBL_MIGRATE_BUTTON' => 'Aktualizuj',
      'LBL_CANCEL_BUTTON' => 'Anuluj',
      'LBL_UPGRADE_FROM_VTIGER_5X' => 'Aktualizuje dane z wersji 5.x do wersji najnowszej',
      'LBL_PATCH_OR_MIGRATION' => 'musisz określić wersję bazy danych vTiger\'a, która ma zostać zaktualizowana poprzez instalację poprawki (Patch update) lub wykonanie aktualizacji (Migration) lub instalację rozszerzenia (Upgrade)',
      'ENTER_SOURCE_HOST' => 'Proszę wpisać nazwę źródłowego hosta (serwera)',
      'ENTER_SOURCE_MYSQL_PORT' => 'Proszę wpisać numer portu źródłowej bazy MySql',
      'ENTER_SOURCE_MYSQL_USER' => 'Proszę wpisać nazwę użytkownika źródłowej bazy MySql',
      'ENTER_SOURCE_DATABASE' => 'Proszę wpisać nazwę źródłowej bazy danych',
      'ENTER_SOURCE_MYSQL_DUMP' => 'Proszę określić poprawny  plik Kopii Zapasowej MySQL',
      'ENTER_HOST' => 'Proszę wpisać nazwę docelowego hosta (serwera) MySql',
      'ENTER_MYSQL_PORT' => 'Proszę wpisać numer portu docelowej bazy MySql',
      'ENTER_MYSQL_USER' => 'Proszę wpisać nazwę użytkownika docelowej bazy MySql',
      'ENTER_DATABASE' => 'Proszę wpisać nazwę docelowej bazy MySql',
      'SELECT_ANYONE_OPTION' => 'Proszę wybrać opcje',
      'ENTER_CORRECT_MYSQL_PATH' => 'Proszę wpisać poprawną ścieżkę do bazy MySQL ',
);
?>