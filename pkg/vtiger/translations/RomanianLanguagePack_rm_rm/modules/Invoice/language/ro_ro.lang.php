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
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Defines the Romanian language pack 
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Magister Software SRL, Bucharest, Romania, www.magister.ro
 ********************************************************************************/
 
$mod_strings = Array(
'LBL_MODULE_NAME'=>'Factura',
'LBL_SO_MODULE_NAME'=>'Factura',
'LBL_RELATED_PRODUCTS'=>'Produse similare',
'LBL_MODULE_TITLE'=>'Factura: Index',
'LBL_SEARCH_FORM_TITLE'=>'Cauta factura',
'LBL_LIST_FORM_TITLE'=>'Lista facturi',
'LBL_LIST_SO_FORM_TITLE'=>'Lista comenzi vanzare',
'LBL_NEW_FORM_TITLE'=>'Factura noua',
'LBL_NEW_FORM_SO_TITLE'=>'Comanda noua vanzare',
'LBL_MEMBER_ORG_FORM_TITLE'=>'Organizatii membre',

'LBL_LIST_ACCOUNT_NAME'=>'Nume Cont',
'LBL_LIST_CITY'=>'Oras',
'LBL_LIST_WEBSITE'=>'Site Web',
'LBL_LIST_STATE'=>'Judet',
'LBL_LIST_PHONE'=>'Nr tel',
'LBL_LIST_EMAIL_ADDRESS'=>'Adresa email',
'LBL_LIST_CONTACT_NAME'=>'Nume Contact',

//DON'T CONVERT THESE THEY ARE MAPPINGS
'db_name' => 'LBL_LIST_ACCOUNT_NAME',
'db_website' => 'LBL_LIST_WEBSITE',
'db_billing_address_city' => 'LBL_LIST_CITY',

//END DON'T CONVERT

'LBL_ACCOUNT'=>'Cont:',
'LBL_ACCOUNT_NAME'=>'Nume Cont:',
'LBL_PHONE'=>'Nr tel:',
'LBL_WEBSITE'=>'Site Web:',
'LBL_FAX'=>'Nr fax:',
'LBL_TICKER_SYMBOL'=>'Simbol bursier:',
'LBL_OTHER_PHONE'=>'Alt nr tel:',
'LBL_ANY_PHONE'=>'Orice nr tel:',
'LBL_MEMBER_OF'=>'Membru al:',
'LBL_EMAIL'=>'Adresa email:',
'LBL_EMPLOYEES'=>'Angajati:',
'LBL_OTHER_EMAIL_ADDRESS'=>'Alta adresa email:',
'LBL_ANY_EMAIL'=>'Orice adresa email:',
'LBL_OWNERSHIP'=>'Proprietar:',
'LBL_RATING'=>'Rating:',
'LBL_INDUSTRY'=>'Industrie:',
'LBL_SIC_CODE'=>'Cod SIC:',
'LBL_TYPE'=>'Tip:',
'LBL_ANNUAL_REVENUE'=>'Venit anual:',
'LBL_ADDRESS_INFORMATION'=>'Info adresa',
'LBL_Quote_INFORMATION'=>'Info cont',
'LBL_CUSTOM_INFORMATION'=>'Info personalizata',
'LBL_BILLING_ADDRESS'=>'Adresa facturare:',
'LBL_SHIPPING_ADDRESS'=>'Adresa livrare:',
'LBL_ANY_ADDRESS'=>'Orice adresa:',
'LBL_CITY'=>'Oras:',
'LBL_STATE'=>'Judet:',
'LBL_POSTAL_CODE'=>'Cod postal:',
'LBL_COUNTRY'=>'Tara:',
'LBL_DESCRIPTION_INFORMATION'=>'Info descriere',
'LBL_DESCRIPTION'=>'Descriere:',
'LBL_TERMS_INFORMATION'=>'Termeni si conditii',
'NTC_COPY_BILLING_ADDRESS'=>'Copiaza adresa facturare la adresa livrare',
'NTC_COPY_SHIPPING_ADDRESS'=>'Copiaza adresa livrare la adresa facturare',
'NTC_REMOVE_MEMBER_ORG_CONFIRMATION'=>'Sunteti sigur ca doriti sa stergeti aceasta inregistrare de organizatie membra?',
'LBL_DUPLICATE'=>'Posibile conturi duplicat',
'MSG_DUPLICATE' => 'Crearea acestui cont poate duce la aparitia unui cont duplicat. Selectati un cont din lista de mai jos sau apasati Creaza cont nou pentru a continua crearea unui cont nou cu datele introduse anterior.',

'LBL_INVITEE'=>'Contacte',
'ERR_DELETE_RECORD'=>"Va rugam sa specificati un nr de inregistrare pentru a sterge acest cont.",

'LBL_SELECT_ACCOUNT'=>'Selectati cont',
'LBL_GENERAL_INFORMATION'=>'Info generala',

//for v4 release added
'LBL_NEW_POTENTIAL'=>'Potential nou',
'LBL_POTENTIAL_TITLE'=>'Potentiale',

'LBL_NEW_TASK'=>'Task nou',
'LBL_TASK_TITLE'=>'Task-uri',
'LBL_NEW_CALL'=>'Apel nou',
'LBL_CALL_TITLE'=>'Apeluri',
'LBL_NEW_MEETING'=>'Intalnire noua',
'LBL_MEETING_TITLE'=>'Intalniri',
'LBL_NEW_EMAIL'=>'Email nou',
'LBL_EMAIL_TITLE'=>'Emailuri noi',
'LBL_NEW_CONTACT'=>'Contact nou',
'LBL_CONTACT_TITLE'=>'Contacte',

//Added vtiger_fields after RC1 - Release
'LBL_ALL'=>'Tot',
'LBL_PROSPECT'=>'Prospectare',
'LBL_INVESTOR'=>'Investitor',
'LBL_RESELLER'=>'Reseller',
'LBL_PARTNER'=>'Partener',

// Added for 4GA
'LBL_TOOL_FORM_TITLE'=>'Instrumente cont',
//Added for 4GA
'Subject'=>'Subiect',
'Quote Name'=>'Nume Oferta',
'Vendor Name'=>'Nume Vanzator',
'Invoice Terms'=>'Termeni facturare',
'Contact Name'=>'Nume Contact',//to include contact name vtiger_field in Invoice
'Invoice Date'=>'Data facturare',
'Sub Total'=>'Sub Total',
'Due Date'=>'Data scadenta',
'Carrier'=>'Transportator',
'Type'=>'Tip',
'Sales Tax'=>'Taxa vanzari',
'Sales Commission'=>'Comision vanzari',
'Excise Duty'=>'Scadenta impozit',
'Total'=>'Total',
'Product Name'=>'Nume produs',
'Assigned To'=>'Asignat la',
'Billing Address'=>'Adresa facturare',
'Shipping Address'=>'Adresa livrare',
'Billing City'=>'Oras facturare',
'Billing State'=>'Judet facturare',
'Billing Code'=>'Cod facturare',
'Billing Country'=>'Tara facturare',
'Billing Po Box'=>'Casuta postala facturare',
'Shipping Po Box'=>'Casuta postala livrare',
'Shipping City'=>'Oras livrare',
'Shipping State'=>'Judet livrare',
'Shipping Code'=>'Cod livrare',
'Shipping Country'=>'Tara livrare',
'City'=>'Oras',
'State'=>'Judet',
'Code'=>'Cod',
'Country'=>'Tara',
'Created Time'=>'Creat la ora',
'Modified Time'=>'Modificat la ora',
'Description'=>'Descriere',
'Potential Name'=>'Nume potential',
'Customer No'=>'ID client',
'Sales Order'=>'Comanda Vanzare',
'Pending'=>'In asteptare',
'Account Name'=>'Nume Cont',
'Terms & Conditions'=>'Termeni si conditii',
//Quote Info
'LBL_INVOICE_INFORMATION'=>'Info facturare',
'LBL_INVOICE'=>'Factura:',
'LBL_SO_INFORMATION'=>'Info comanda vanzare',
'LBL_SO'=>'Comanda Vanzare:',

//Added in release 4.2
'LBL_SUBJECT'=>'Subiect:',
'LBL_SALES_ORDER'=>'Comanda vanzari:',
'Invoice Id'=>'ID Factura',
'LBL_MY_TOP_INVOICE'=>'Top facturi deschise',
'LBL_INVOICE_NAME'=>'Nume Factura:',
'Purchase Order'=>'Comanda Cumparare',
'Status'=>'Stare',
'Id'=>'ID Factura',
'Invoice'=>'Factura',

//Added for existing Picklist Entries

'Created'=>'Creat',
'Approved'=>'Confirmat',
'Sent'=>'Trimis',
'Credit Invoice'=>'Factura credit',
'Paid'=>'Platit',
//Added to Custom Invoice Number
'Invoice No'=>'Nr Factura',
'Adjustment'=>'Ajustare',

//Added for Reports (5.0.4)
'Tax Type'=>'Tip impozit',
'Discount Percent'=>'Procent discount',
'Discount Amount'=>'Cantitate discount',
'Terms & Conditions'=>'Termeni & Conditii',
'No'=>'Nu',
'Date'=>'Data',

// Added affter 5.0.4 GA
//Added for Documents module
'Documents'=>'Documente',
);

?>
