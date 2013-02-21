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

 ********************************************************************************/

$mod_strings = array (
      'LBL_MIGRATE_INFO' => 'Введите Значения для Миграции Данных с <b><i> Источника </i></b> в <b><i> Текущую (Последнюю) Версию vtigerCRM </i></b>',
      'LBL_CURRENT_VT_MYSQL_EXIST' => 'Текущая база vtiger MySQL расположена в',
      'LBL_THIS_MACHINE' => 'Этот Компьютер',
      'LBL_DIFFERENT_MACHINE' => 'Другой Компьютер',
      'LBL_CURRENT_VT_MYSQL_PATH' => 'Текущее расположение vtiger MySQL',
      'LBL_SOURCE_VT_MYSQL_DUMPFILE' => 'Файл Базы vtiger <b>Source</b>',
      'LBL_NOTE_TITLE' => 'Заметка:',
      'LBL_NOTES_LIST1' => 'Если Текущая MySQL расположена на том самом компьютере, тогда введите путь к MySQL или Вы можете задать имя файла Базы если у Вас есть.',
      'LBL_NOTES_LIST2' => 'Если текущая MySQL расположена на другом компьютере, тогда укажите (Источник) файл Базы и полный путь к нему.',
      'LBL_NOTES_DUMP_PROCESS' => 'Чтобы сделать Дамп Базы пожалуйста запустите следующие комманды находясь в директории <b>mysql/bin</b>
			   <br><b>mysqldump --user="mysql_username"  --password="mysql-password" -h "hostname"  --port="mysql_port" "database_name" > dump_filename</b>
			   <br>add <b>SET FOREIGN_KEY_CHECKS = 0;</b> -- в начале файла дампа
			   <br>add <b>SET FOREIGN_KEY_CHECKS = 1;</b> -- в конце файла дампа',
      'LBL_NOTES_LIST3' => 'Задайте MySQL путь, например <b>/home/crm/vtigerCRM4_5/mysql</b>',
      'LBL_NOTES_LIST4' => 'Задайте имя файлу дампа базы, например <b>/home/fullpath/4_2_dump.txt</b>',
      'LBL_CURRENT_MYSQL_PATH_FOUND' => 'Найден путь к текущей MySQL.',
      'LBL_SOURCE_HOST_NAME' => 'Хост Источника :',
      'LBL_SOURCE_MYSQL_PORT_NO' => '№ Порта MySql Источника :',
      'LBL_SOURCE_MYSQL_USER_NAME' => 'Пользователь MySql Источника :',
      'LBL_SOURCE_MYSQL_PASSWORD' => 'Пароль Источника MySql :',
      'LBL_SOURCE_DB_NAME' => 'Название Базы Источника :',
      'LBL_MIGRATE' => 'Миграция до Текущей Версии',
      'LBL_UPGRADE_VTIGER' => 'Обновить Базу vtiger CRM',
      'LBL_UPGRADE_FROM_VTIGER_423' => 'Обновить базу с vtiger CRM 4.2.3 до 5.0.0',
      'LBL_SETTINGS' => 'Параметры',
      'LBL_STEP' => 'Шаг',
      'LBL_SELECT_SOURCE' => 'выберите Источник',
      'LBL_STEP1_DESC' => 'Чтобы начать миграцию базы Вам необходимо указать формат в котором доступны старые данные',
      'LBL_RADIO_BUTTON1_TEXT' => 'У Вас есть доступ к базе vtiger CRM',
      'LBL_RADIO_BUTTON1_DESC' => 'Эта опция требует наличия у Вас адреса хоста машины ( где База Данных находится ). Обе системы, локальная и удаленная поддерживаются в этом методе. Обратитесь к документации за помощью.',
      'LBL_RADIO_BUTTON2_TEXT' => 'У меня есть доступ к сохраненному архиву дампа базы vtiger CRM',
      'LBL_RADIO_BUTTON2_DESC' => 'Эта опция требует наличия дампа базы на локальной машине, которая обновляется. Вы не можете получить доступ к базе с другого компьютера (удаленного сервера баз данных). Обратитесь к документации за помощь.',
      'LBL_RADIO_BUTTON3_TEXT' => 'У меня есть новая база с Данными версии 4.2.3',
      'LBL_RADIO_BUTTON3_DESC' => 'Эта опция требует информации о базе vtiger CRM 4.2.3, включая сервер, пользователя, и пароль. Вы можете получить доступ к дампу базы с другого компьютера (удаленный сервер БД).',
      'LBL_HOST_DB_ACCESS_DETAILS' => 'Данные для доступа к БД',
      'LBL_MYSQL_HOST_NAME_IP' => 'Сервер MySQL (имя или IP) : ',
      'LBL_MYSQL_PORT' => 'Номер порта MySQL : ',
      'LBL_MYSQL_USER_NAME' => 'Имя Пользователя MySql : ',
      'LBL_MYSQL_PASSWORD' => 'Пароль MySql : ',
      'LBL_DB_NAME' => 'Название Базы : ',
      'LBL_LOCATE_DB_DUMP_FILE' => 'Разместить файл дампа БД',
      'LBL_DUMP_FILE_LOCATION' => 'Размещение файла дампа БД : ',
      'LBL_RADIO_BUTTON3_PROCESS' => '<font color="red">Пожалуйста не указывайте детали БД 4.2.3. Эта опция изменит БД напрямую.</font>
<br>Настоятельно рекомендуется сделать следующее.
<br>1. Возьмите дамп БД 4.2.3
<br>2. Создайте новую БД (Лучше создать БД на сервере где работает Ваша БД vtiger 5.0.)
<br>3. Загрузите этот дамп 4.2.3 в Вашу новую БД.
<br>Теперь задайте параметры доступа к этой БД. Миграция изменит схему БД на ту что в версии 5.0.
Теперь Вы можете задать имя этой БД в файле config.inc.php, чтобы использовать эту БД, то есть, $dbconfig[\'db_name\'] = \'new db name\';',
      'LBL_ENTER_MYSQL_SERVER_PATH' => 'Введите путь к Серверу MySQL',
      'LBL_SERVER_PATH_DESC' => 'Путь к Серверу MySQL, например <b>/home/5beta/vtigerCRM5_beta/mysql/bin</b> или <b>c:\Program Files\mysql\bin</b>',
      'LBL_MYSQL_SERVER_PATH' => 'Путь к Серверу MySQL : ',
      'LBL_MIGRATE_BUTTON' => 'Мигрировать',
      'LBL_CANCEL_BUTTON' => 'Отмена',
      'LBL_UPGRADE_FROM_VTIGER_5X' => 'Обновить БД с vtiger CRM 5.x до следующих версий',
      'LBL_PATCH_OR_MIGRATION' => 'Вам необходимо указать версию БД источника (Обновление Патчем или Миграция)',
      'ENTER_SOURCE_HOST' => 'Пожалуйста, укажите Хост Источника',
      'ENTER_SOURCE_MYSQL_PORT' => 'Пожалуйста, укажите Порт MySql Источника',
      'ENTER_SOURCE_MYSQL_USER' => 'Пожалуйста, укажите Пользователя MySql Источника',
      'ENTER_SOURCE_DATABASE' => 'Пожалуйста, укажите Название БД Источника',
      'ENTER_SOURCE_MYSQL_DUMP' => 'Пожалуйста, укажите правильный файл дампа БД MySQL',
      'ENTER_HOST' => 'Пожалуйста, укажите Хост',
      'ENTER_MYSQL_PORT' => 'Пожалуйста, укажите Порт MySql',
      'ENTER_MYSQL_USER' => 'Пожалуйста, укажите Пользователя MySql',
      'ENTER_DATABASE' => 'Пожалуйста, укажите Название БД',
      'SELECT_ANYONE_OPTION' => 'Пожалуйста, выберите любую опцию',
      'ENTER_CORRECT_MYSQL_PATH' => 'Пожалуйста, укажите правильный путь к MySQL',
);
?>