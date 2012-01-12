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
 * Contributor(s): ______________________________________.
 ********************************************************************************
 * $Header:  \modules\Import\language\hu_hu.lang.php - 11:34 2011.11.11. $
 * Description:  Defines the Hungarian language pack for the Import module vtiger 5.3.x
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Istvan Holbok,  e-mail: holbok@gmail.com , mobil: +3670-3420900 , Skype: holboki
 ********************************************************************************/

$mod_strings = Array(
'LBL_IMPORT_MODULE_NO_DIRECTORY'=>'A könyvtár ',
'LBL_IMPORT_MODULE_NO_DIRECTORY_END'=>' nem létezik vagy írásvédett',
'LBL_IMPORT_MODULE_ERROR_NO_UPLOAD'=>'Nem sikerült feltölteni a fájlt, próbáld meg újra',
'LBL_IMPORT_MODULE_ERROR_LARGE_FILE'=>'A fájl túl nagy. Max:',
'LBL_IMPORT_MODULE_ERROR_LARGE_FILE_END'=>'Bájt. Növeld meg a $upload_maxsize értékét a config.inc.php fájlban.',
'LBL_MODULE_NAME'=>'Importálás',
'LBL_TRY_AGAIN'=>'Próbáld újra',
'LBL_ERROR'=>'Hiba:',
'ERR_MULTIPLE'=>'Több oszlopot is ugyanazzal a mezőnévvel kötöttél össze.',
'ERR_MISSING_REQUIRED_FIELDS'=>'Kötelező mező hiányzik:',
'ERR_SELECT_FULL_NAME'=>'Nem választhatod ki a Teljes név mezőt, ha a Keresztnév és a Vezetéknév mezőt már kiválasztottad.',
'ERR_SELECT_FILE'=>'Válasszon ki egy fájlt a feltöltéshez.',
'LBL_SELECT_FILE'=>'Fájl kiválasztása:',
'LBL_CUSTOM'=>'Egyedi',
'LBL_DONT_MAP'=>'-- Ezt a mezőt ne feleltesse meg --',
'LBL_STEP_1_TITLE'=>'1. lépés a 4-ből: Válaszd ki az adatok forrását',
'LBL_WHAT_IS'=>'Válaszd ki az adatok forrását a következőkből:',
'LBL_MICROSOFT_OUTLOOK'=>'Microsoft Outlook',
'LBL_ACT'=>'Act!',
'LBL_SALESFORCE'=>'Salesforce.com',
'LBL_MY_SAVED'=>'Elmentett adatforrásaim:',
'LBL_PUBLISH'=>'közzétesz',
'LBL_DELETE'=>'Töröl',
'LBL_PUBLISHED_SOURCES'=>'Közzétett források:',
'LBL_UNPUBLISH'=>'visszavon',
'LBL_NEXT'=>'Következő',
'LBL_BACK'=>'Vissza',
'LBL_STEP_2_TITLE'=>'2. lépés a 4-ből: Töltsd fel a beolvasandó fájlt',
'LBL_HAS_HEADER'=>'Van fejléc',

'LBL_NUM_1'=>'1.',
'LBL_NUM_2'=>'2.',
'LBL_NUM_3'=>'3.',
'LBL_NUM_4'=>'4.',
'LBL_NUM_5'=>'5.',
'LBL_NUM_6'=>'6.',
'LBL_NUM_7'=>'7.',
'LBL_NUM_8'=>'8.',
'LBL_NUM_9'=>'9.',
'LBL_NUM_10'=>'10.',
'LBL_NUM_11'=>'11.',
'LBL_NUM_12'=>'12.',
'LBL_NOW_CHOOSE'=>'Most válaszd ki azt a fájlt beolvasásra:',
'LBL_IMPORT_OUTLOOK_TITLE'=>'Microsoft Outlook 98 és 2000 (illetve az újabb verziói a programnak) ki tudja menteni az adatokat egy <b>Comma Separated Values (CSV = Vesszővel Elválasztott Értékek)</b> formátumú fájlba, amit be tudunk olvasni ebbe a rendszerbe. Hogy kimentsd az adataidat az Outlook-ból, kövesd az alábbi lépéseket:',
'LBL_OUTLOOK_NUM_1'=>'<b>Outlook</b> indítása',
'LBL_OUTLOOK_NUM_2'=>'Válaszd a <b>Fájl</b> menüt, és azon belül az <b>Import és Export ...</b> menüpontot',
'LBL_OUTLOOK_NUM_3'=>'Válaszd az <b>Export fájlba</b> és kattints a Következő gombra',
'LBL_OUTLOOK_NUM_4'=>'Válaszd a <b>CSV (Windows)</b> formátumot és kattints a <b>Következő</b> gombra.<br>  Megjegyzés: Az Outlook program kérheti, hogy telepítsd az export komponenst',
'LBL_OUTLOOK_NUM_5'=>'Válaszd a <b>Kapcsolatok</b> mappát és kattints a <b>Következő</b> gombra. Több Kapcsolatok mappából is választhatsz, ha a kapcsolataid több mappában voltak tárolva.',
'LBL_OUTLOOK_NUM_6'=>'Válaszd ki a fájl nevet és kattints a <b>Következő</b> gombra',
'LBL_OUTLOOK_NUM_7'=>'Kattints a <b>Befejezés</b>-re',

'LBL_IMPORT_ACT_TITLE'=>'Act! is tudja exportálni az adatokat <b>Vesszővel Elválasztott Értékek (Comma Separated Values)</b> formátumba, amelyből az adatok betölthetők ebbe a rendszerbe. Kövesd az alábbi lépéseket az adatok kimentéséhez az Act! programból:',
'LBL_ACT_NUM_1'=>'Lépj be az <b>ACT!</b>-ba',
'LBL_ACT_NUM_2'=>'Válaszd a <b>Fájl</b> menüt, és azon belül a <b>Data Exchange</b> menüpontot, majd az <b>Export...</b> menüpontot',
'LBL_ACT_NUM_3'=>'Válaszd fájl típusnak a <b>Text-Delimited</b> formátumot',
'LBL_ACT_NUM_4'=>'Válassz egy fájlnevet és mentési könyvtárt a kimentendő adatoknak, és kattints a <b>Next</b> gombra',
'LBL_ACT_NUM_5'=>'Válaszd a <b>Contacts records only</b> lehetőséget',
'LBL_ACT_NUM_6'=>'Kattints az <b>Options...</b> gombra',
'LBL_ACT_NUM_7'=>'Válaszd a <b>Comma</b>-t mint mező elválasztó karaktert',
'LBL_ACT_NUM_8'=>'Jelöld be a <b>Yes, export field names</b> jelölő dobozt, és kattints az <b>OK</b> gombra',
'LBL_ACT_NUM_9'=>'Kattints a <b>Next</b> gombra',
'LBL_ACT_NUM_10'=>'Válaszd az <b>All Records</b> lehetőséget, majd kattints a <b>Finish</b> gombra',

'LBL_IMPORT_SF_TITLE'=>'A Salesforce.com is tudja exportálni az adatokat <b>Vesszővel Elválasztott Értékek (Comma Separated Values)</b> formátumba, amelyből az adatok betölthetők ebbe a rendszerbe. Kövesd az alábbi lépéseket az adatok kimentéséhez a Salesforce.com programból:',
'LBL_SF_NUM_1'=>'Nyisd meg a böngésződet és menj a http://www.salesforce.com oldalra, majd lépj be az e-mail címeddel és a jelszavaddal.',
'LBL_SF_NUM_2'=>'Kattints a <b>Reports</b> fülre a felső menüben',
'LBL_SF_NUM_3'=>'Cégek exportálásához kattins az <b>Active Accounts</b> linkre, Kapcsolatok exportálásához pedig kattints a <b>Mailing List</b> linkre',
'LBL_SF_NUM_4'=>'<b>1. lépés: Jelentés típusának kiválasztása</b>, válaszd a <b>Tabular Report</b>-t majd kattints a <b>Next</b> gombra',
'LBL_SF_NUM_5'=>'<b>2. lépés: Jelentés oszlopainak kiválasztása</b>, válaszd ki az oszlopokat, amiket exportálni akarsz, majd kattints a <b>Next</b> gombra',
'LBL_SF_NUM_6'=>'<b>3. lépés: Összegzendő információk kiválasztása</b>, csak kattints a <b>Next</b> gombra',
'LBL_SF_NUM_7'=>'<b>4. lépés: Jelentés oszlopok sorba rendezése</b>, csak kattints a <b>Next</b> gombra',
'LBL_SF_NUM_8'=>'<b>5. lépés: Jelentés szempontjainak kiválasztása</b>, a <b>Start Date</b> kapcsán válassz egy elegendően régi dátumot, hogy minden cég bele kerülhessen. A Cégek egy részét is exportálhatod, ha több feltételt adsz meg. Amikor kész vagy, kattints a <b>Run Report</b> gombra.',
'LBL_SF_NUM_9'=>'A report will be generated, and the page should display <b>Report Generation Status: Complete.</b> Now click <b>Export to Excel</b>',
'LBL_SF_NUM_10'=>'On <b>Export Report:</b>, for <b>Export File Format:</b>, choose <b>Comma Delimited .csv</b>. Click <b>Export</b>.',
'LBL_SF_NUM_11'=>'Egy párbeszéd ablak ugrik fel, amin keresztül el tudod menteni az export fájlt a számítógépedre.',

'LBL_IMPORT_CUSTOM_TITLE'=>'Sok alkalmazás támogatja az adatok exportálását <b>vesszővel elválasztott értékeket tartalmazó szöveg fájlba (.csv)</b>. A legtöbb alkalmazás esetében követheted az alábbi lépéseket:',
'LBL_CUSTOM_NUM_1'=>'Lépj be az alkalmazásba és nyisd meg az adatfájlt',
'LBL_CUSTOM_NUM_2'=>'Válaszd a <b>Mentés másként...</b> vagy <b>Exportálás...</b> menüpontot',
'LBL_CUSTOM_NUM_3'=>'Fájl mentése <b>CSV</b> vagyis <b>Vesszővel Elválasztott Értékek (Comma Separated Values)</b> formátumba.',

'LBL_STEP_3_TITLE'=>'3. lépés a 4-ből: Mezők jóváhagyása és Import',
'LBL_STEP_1'=>'1. lépés a 3-ból: ',
'LBL_STEP_1_TITLE'=>'Válaszd ki a .CSV fájlt',
'LBL_STEP_1_TEXT'=> ' vtiger CRM támogatja a CSV (<b>Vesszővel Elválasztott Értékek</b>) fájlokból történő importálást. Az importálás megkezdéséhez böngésszél a .csv fájl után, majd nyomd meg a Következő gombot a folytatáshoz.',

'LBL_SELECT_FIELDS_TO_MAP'=>'Az alábbi listából válaszd ki, hogy az importálandó fájl milyen mezőit milyen vtiger mezőkkel rendeled össze. Amikor kész vagy a leképezéssel kattints az <b>Importálás most</b> gombra.',

'LBL_DATABASE_FIELD'=>'Adatbázis mező',
'LBL_HEADER_ROW'=>'Fejléc sor',
'LBL_ROW'=>'Sor',
'LBL_SAVE_AS_CUSTOM'=>'Mentés egyedi Leképezésként :',
'LBL_CONTACTS_NOTE_1'=>'A Vezetéknév vagy Keresztnév mezők valamelyikét hozzá kell rendelni.',
'LBL_CONTACTS_NOTE_2'=>'Ha a Teljes név szerepel a mezőhozzárendelésben, akkor a Vezetéknév és Keresztnév mezőket figyelmen kívül hagyjuk.',
'LBL_CONTACTS_NOTE_3'=>'Ha a Teljes név szerepel a mezőhozzárendelésben, akkor a Teljes név mező tartalma a Vezetéknév és Keresztnév mezőkbe automatikusan szét lesz osztva a betöltés során.',
'LBL_CONTACTS_NOTE_4'=>'A cím mező 2. és 3. sorában szereplő adatokat hozzáadtuk az Utca, házszám mező végéhez, amikor beolvastuk őket az adatbázisba.',
'LBL_ACCOUNTS_NOTE_1'=>'A Cégnév mezőhöz kell kapcsolni beolvasandó adatot.',
'LBL_ACCOUNTS_NOTE_2'=>'A cím mező 2. és 3. sorában szereplő adatokat hozzáadtuk az Utca, házszám mező végéhez, amikor beolvastuk őket az adatbázisba.',
'LBL_POTENTIALS_NOTE_1'=>'Lehetőség neve, Cégnév, Lezárás dátuma, és az Értékesítési fázis kötelező mezők.',
'LBL_OPPORTUNITIES_NOTE_1'=>'Lehetőség neve, Cégnév, Lezárás dátuma, és az Értékesítési fázis kötelező mezők.',
'LBL_LEADS_NOTE_1'=>'A Vezetéknév mezőhöz kell kapcsolni beolvasandó adatot.',
'LBL_LEADS_NOTE_2'=>'A Cégnév mezőhöz kell kapcsolni beolvasandó adatot.',
'LBL_IMPORT_NOW'=>'Importálás most',
'LBL_'=>'',
'LBL_CANNOT_OPEN'=>'Az importálandó fájl nem nyitható meg olvasásra',
'LBL_NOT_SAME_NUMBER'=>'A mezők száma különböző volt az egyes sorokban az iportálandó fájlban',
'LBL_NO_LINES'=>'Egyetlen sor sem volt az importálandó fájlban',
'LBL_FILE_ALREADY_BEEN_OR'=>'Az import fájlt már feldolgoztuk vagy nem létezik',
'LBL_SUCCESS'=>'Sikeres! ',
'LBL_SUCCESSFULLY'=>'Sikeresen importálva',
'LBL_LAST_IMPORT_UNDONE'=>'Az utolsó importálást visszavontuk.',
'LBL_NO_IMPORT_TO_UNDO'=>'Nincs visszavonható importálás.',
'LBL_FAIL'=>'Hiba:',
'LBL_RECORDS_SKIPPED'=>' rekordot kihagytunk, mivel egy vagy több kötelező mező hiányzott',
'LBL_IDS_EXISTED_OR_LONGER'=>' rekordot kihagytunk, mivel az azonosítója már létezett, vagy 36 karakternél hosszabb volt',
'LBL_RESULTS'=>'Eredmények',
'LBL_IMPORT_MORE'=>'További importálás',
'LBL_FINISHED'=>'Befejeződött',
'LBL_UNDO_LAST_IMPORT'=>'Utolsó importálás visszavonása',

'LBL_SUCCESS_1' => 'Sikeresen Importált/Frissített rekordok száma : ',
'LBL_SKIPPED_1' => 'Egy vagy több kötelező mező hiánya miatt kihagyott rekordok száma : ',

//Added for patch2 - Products Import Notes
'LBL_PRODUCTS_NOTE_1'=>'Termék nevét hozzá kell rendelned',
'LBL_PRODUCTS_NOTE_2'=>'Importálás előtt győződj meg arról, hogy nincs-e duplán leképzve egy mező a hozzárendelésben',

//Added for version 5
'LBL_FILE_LOCATION'=>'Fájl helye :',
'LBL_STEP_2_3'=>'2. lépés a 3-ból :',
'LBL_LIST_MAPPING'=>'Lista és Hozzárendelések',
'LBL_STEP_2_MSG'=>'A következő táblázatok tartalmazzák az importáltakat',
'LBL_STEP_2_MSG1'=>'és más adatokat.',
'LBL_STEP_2_TXT'=>'A mezők hozzárendeléséhez válaszd ki a megfelelő kapcsolódásokat',
'LBL_USE_SAVED_MAPPING'=>'Elmentett hozzárendelés használata :',
'LBL_MAPPING'=>'Hozzárendelés',
'LBL_HEADERS'=>'Fejléc :',
'LBL_ERROR_MULTIPLE'=>'Ugyanazt a mezőt duplán rendelted hozzá. Ellenőrizd a hozzárendelt mezőket.',
'LBL_STEP_3_3'=>'3. lépés a 3-ból : ',
'LBL_MAPPING_RESULTS'=>'Leképezés eredménye',
'LBL_LAST_IMPORTED'=>'Utoljára importálva',
//Added for sript alerts
'PLEASE_CHECK_MAPPING' => "' több mint egyszer is mapped more than once. Please check the mapping.",
'MAP_MANDATORY_FIELD' => 'A kötelező mezőkhöz szükséges beolvasandó adatot kapcsolnod.',
'ENTER_SAVEMAP_NAME' => 'Add meg a Leképezés mentési nevét',

//Added for 5.0.3
'to'=>'to',
'of'=>'of',
'are_imported_succesfully'=>'sikeresen importálva.',

// Added after 5.0.4 GA

//added for duplicate handling 
'LBL_LAST_IMPORT'=>'Utolsóként importált',
'Select_Criteria_For_Duplicate' => 'Válaszd ki a szempontot a rekord duplikáció kezelésére',
'Manual_Merging' => 'Kézi összefűzés',
'Auto_Merging' => 'Automatikus összefűzés',
'Ignore_Duplicate' => 'Hagyd figyelmen kívül a duplikált import rekordokat',
'Overwrite_Duplicate' => 'Írd felül a duplikált import rekordokat',
'Duplicate_Records_Skipped_Info' => 'Kihagyott rekordok száma - duplikáció : ',
'Duplicate_Records_Overwrite_Info' => 'Felülírt rekordok száma - duplikáció : ',
'LBL_STEP_4_4'=>'4. a 4 lépésből: ',
'LBL_STEP_3_4'=>'3. a 4 lépésből: ',
'LBL_STEP_2_4'=>'2. a 4 lépésből: ',
'LBL_STEP_1_4'=>'1. a 4 lépésből: ',

'LBL_DELIMITER' => 'Mezőelválasztó:',
'LBL_FORMAT' => 'Formátum:',
'LBL_MAX_FILE_SIZE' => ' a maximális megengedett fájlméret',

'LBL_MERGE_FIELDS_DUPLICATE' => 'Mezők összefűzése a beolvasott rekordok duplikálása végett',
'Customer Portal Login Details' => 'Ügyfélszolgálati portál belépési adatok',
);

$mod_list_strings = Array(
'contacts_import_fields' => Array(
	"firstname"=>"Keresztnév"
	,"lastname"=>"Vezetéknév"
	,"salutationtype"=>"Megszólítás"
	,"leadsource"=>"Kapcsolat forrása"
	,"birthday"=>"Születésnap"
	,"donotcall"=>"Ne hívd"
	,"emailoptout"=>"E-mail leiratkozott"
	,"account_id"=>"Cégnév"
	,"title"=>"Beosztás"
	,"department"=>"Részleg"
	,"homephone"=>"Telefon (Otthon)"
	,"mobile"=>"Telefon (Mobil)"
	,"phone"=>"Telefon (Munkahely)"
	,"otherphone"=>"Telefon (Egyéb)"
	,"fax"=>"Fax"
	,"email"=>"E-mail"
	,"otheremail"=>"E-mail (Egyéb)"
	,"secondaryemail"=>"Másodlagos E-mail"
	,"assistant"=>"Asszisztens"
	,"assistantphone"=>"Asszisztens Telefon"
	,"mailingstreet"=>"Levelezési cím - Utca"
	,"mailingpobox"=>"Levelezési cím - Postafiók"
	,"mailingcity"=>"Levelezési cím - Város"
	,"mailingstate"=>"Levelezési cím - Állam/Megye"
	,"mailingzip"=>"Levelezési cím - Irányítószám"
	,"mailingcountry"=>"Levelezési cím - Ország"
	,"otherstreet"=>"Másik cím - Utca"
	,"otherpobox"=>"Másik cím - Postafiók"
	,"othercity"=>"Másik cím - City"
	,"otherstate"=>"Másik cím - Állam/Megye"
	,"otherzip"=>"Másik cím - Irányítószám"
	,"othercountry"=>"Másik cím - Ország"
	,"description"=>"Leírás"
	,"assigned_user_id"=>"Felelős"
	),

'accounts_import_fields' => Array(
	//"id"=>"Account ID",
	"accountname"=>"Cégnév",
	"website"=>"Weboldal",
	"industry"=>"Iparág",
	"accounttype"=>"Típus",
	"tickersymbol"=>"Tőzsdei jel",
	"parent_name"=>"Tagsága",
	"employees"=>"Alkalmazottak",
	"ownership"=>"Tulajdonos",
	"phone"=>"Telefon",
	"fax"=>"Fax",
	"otherphone"=>"Másik Telefon",
	"email1"=>"E-mail",
	"email2"=>"Másik E-mail",
	"rating"=>"Értékelés",
	"siccode"=>"TEÁOR",
	"annual_revenue"=>"Éves bevétel",
	"bill_street"=>"Számlázási cím - Utca",
	"bill_pobox"=>"Számlázási cím - Postafiók",
	"bill_city"=>"Számlázási cím - Város",
	"bill_state"=>"Számlázási cím - Állam/Megye",
	"bill_code"=>"Számlázási cím - Irányítószám",
	"bill_country"=>"Számlázási cím - Ország",
	"ship_street"=>"Szállítási cím - Utca",
	"ship_pobox"=>"Szállítási cím - Postafiók",
	"ship_city"=>"Szállítási cím - Város",
	"ship_state"=>"Szállítási cím - Állam/Megye",
	"ship_code"=>"Szállítási cím - Irányítószám",
	"ship_country"=>"Szállítási cím - Ország",
	"description"=>"Leírás",
	"assigned_user_id"=>"Felelős"
	),

'potentials_import_fields' => Array(
		//"id"=>"Account ID"
                 "potentialname"=>"Lehetőség neve"
                , "account_id"=>"Cégnév"
                , "opportunity_type"=>"Lehetőség típusa"
                , "leadsource"=>"Jelölt forrás"
                , "amount"=>"Mennyiség"
                , "closingdate"=>"Lezárás dátuma"
                , "nextstep"=>"Következő lépés"
                , "sales_stage"=>"Értékesítési fázis"
                , "probability"=>"Valószínűség"
                , "description"=>"Leírás"
		,"assigned_user_id"=>"Felelős"
	),


'leads_import_fields' => Array(
		"salutationtype"=>"Megszólítás",
		"firstname"=>"Keresztnév",
		"phone"=>"Telefon",
		"lastname"=>"Vezetéknév",
		"mobile"=>"Mobil",
		"company"=>"Cégnév",
		"fax"=>"Fax",
		"designation"=>"Rendeltetés",
		"email"=>"E-mail",
		"leadsource"=>"Jelölt forrás",
		"website"=>"Weboldal",
		"industry"=>"Iparág",
		"leadstatus"=>"Jelölt állapot",
		"annualrevenue"=>"Éves jövedelem",
		"rating"=>"Értékelés",
		"noofemployees"=>"Alkalmazottak száma",
		"assigned_user_id"=>"Felelős",
		"secondaryemail"=>"Másodlagos E-mail",
		"lane"=>"Utca, házszám",
		"pobox"=>"Postafiók",
		"code"=>"Irányítószám",
		"city"=>"Város",
		"country"=>"Ország",
		"state"=>"Állam/Megye",
		"description"=>"Leírás",
	),
 
 'products_import_fields' => Array(
 	'productname'=>'Termék neve',
 	'productcode'=>'Termék kód',
 	'productcategory'=>'Termék kategória',
 	'manufacturer'=>'Gyártó',
 	'product_description'=>'Termék leírása',
 	'qty_per_unit'=>'Mennyiségi egység',
 	'unit_price'=>'Egységár',
 	'weight'=>'Súly',
 	'pack_size'=>'Csomagolási méret',
 	'start_date'=>'Kezdő dátum',
 	'expiry_date'=>'Lejárati dátum',
 	'cost_factor'=>'Költség tényező',
 	'commissionmethod'=>'Jutalék számítás',
 	'discontinued'=>'Megszűnt',
 	'commissionrate'=>'Jutalék számítás',
	'sales_start_date'=>'Értékesítés kezdő dátuma',
	'sales_end_date'=>'Értékesítés záró dátuma',
	'usageunit'=>'Használati egység',
	'serialno'=>'Sorozat szám',
	'currency'=>'pénznem',
	'reorderlevel'=>'Újra rendelési szint',
	'website'=>'Weboldal',
	'taxclass'=>'Adó osztály',
	'mfr_part_no'=>'Gyártói cikkszám',
	'vendor_part_no'=>'Szállítói cikkszám',
	'qtyinstock'=>'Készlet',
	'productsheet'=>'Termék adatlap',
	'qtyindemand'=>'Mennyiségi igény',
	'glacct'=>'Könyvelési szám',
	'assigned_user_id'=>'Felelős'
	 ),
//Pavani...adding list of import fields for helpdesk and vendors
'helpdesk_import_fields' => Array(
        "ticketid"=>"Kérés AZ",
        "priority"=>"Prioritás",
        "severity"=>"Fontosság",
        "status"=>"Állapot",
        "category"=>"Kategória",
        "title"=>"Megnevezés",
        "description"=>"Leírás",
        "solution"=>"Megoldás"
        ),

'vendors_import_fields' => Array(
        "vendorid"=>"Szállító AZ",
        "vendorname"=>"Szállító neve",
        "phone"=>"Telefon",
        "email"=>"E-mail",
        "website"=>"Weboldal",
        "category"=>"Kategória",
        "street"=>"Utca, házszám",
        "city"=>"Város",
        "state"=>"Állam/Megye",
        "pobox"=>"Postafiók",
        "postalcode"=>"Irányítószám",
        "country"=>"Ország",
        "description"=>"Leírás"
        )
//Pavani...end list
);

?>
