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
 * Contributor(s):Gökhan MERCANOĞLU www.vtigerturkey.com gmercanATmsn.com.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/language/en_us.lang.php,v 1.17 2005/03/28 06:31:38 rank Exp $
 * Description:  Defines the English language pack for the Account module.
 ********************************************************************************/
 
$mod_strings = Array(
// Mike Crowe Mod --------------------------------------------------------added for general search
'LBL_GENERAL_INFORMATION'=>'Genel Bilgi',

'LBL_MODULE_NAME'=>'E-Postalar',
'LBL_MODULE_TITLE'=>'E-Postalar : Ana Sayfa',
'LBL_SEARCH_FORM_TITLE'=>'E-Posta Arama',
'LBL_LIST_FORM_TITLE'=>'E-Posta Listeleme',
'LBL_NEW_FORM_TITLE'=>'E-Posta İzleme',

'LBL_LIST_SUBJECT'=>'Konu',
'LBL_LIST_CONTACT'=>'İlgili Kişi',
'LBL_LIST_RELATED_TO'=>'İlgili',
'LBL_LIST_DATE'=>'Gönderildiği Tarih',
'LBL_LIST_TIME'=>'Gönderildiği Zaman',

'ERR_DELETE_RECORD'=>"Lütfen Silmek İstediğiniz Kaydı Seçiniz.",
'LBL_DATE_SENT'=>'Gönderildiği Tarih:',
'LBL_SUBJECT'=>'Konu:',
'LBL_BODY'=>'Ana Bölüm:',
'LBL_DATE_AND_TIME'=>'Gönderildiği Tarih & Zaman :',
'LBL_DATE'=>'Gönderildiği Tarih:',
'LBL_TIME'=>'Gönderildiği Zaman:',
'LBL_SUBJECT'=>'Konu:',
'LBL_BODY'=>'Ana Bölüm:',
'LBL_CONTACT_NAME'=>' İlgili Kişi Adı: ',
'LBL_EMAIL'=>'E-Posta:',  
'LBL_COLON'=>':',

'LBL_CHK_MAIL'=>'E-Posta',
'LBL_COMPOSE'=>'Oluştur',
'LBL_SETTINGS'=>'Ayarlar',
'LBL_EMAIL_FOLDERS'=>'E-Posta Klasörleri',
'LBL_INBOX'=>'Gelen Kutusu',
'LBL_SENT_MAILS'=>'Gönderilenler',
'LBL_TRASH'=>'Silinenler',
'LBL_JUNK_MAILS'=>'Önemsizler',
'LBL_TO_LEADS'=>'Kaynağa',
'LBL_TO_CONTACTS'=>'İlgili Kişi',
'LBL_TO_ACCOUNTS'=>'Müşteri',
'LBL_MY_MAILS'=>'E-Postalarım',
'LBL_QUAL_CONTACT'=>'Önemli Mailler (ilgili kişilerden)',
'LBL_MAILS'=>'E-Postalar',
'LBL_QUALIFY_BUTTON'=>'Önemli',
'LBL_REPLY_BUTTON'=>'Cevapla',
'LBL_FORWARD_BUTTON'=>'İlet',
'LBL_DOWNLOAD_ATTCH_BUTTON'=>'Ekleri indir',
'LBL_FROM'=>'Kimden :',
'LBL_CC'=>'Cc :',
'LBL_BCC'=>'Bcc :',

'NTC_REMOVE_INVITEE'=>'Bu alıcıyı E-Postadan kaldırmak istediğinizden eminmisiniz?',
'LBL_INVITEE'=>'Alıcılar',

// Added Fields
// Contacts-SubPanelViewContactsAndUsers.php
'LBL_BULK_MAILS'=>'Toplu E-Postalar',
'LBL_ATTACHMENT'=>'Ekler',
'LBL_UPLOAD'=>'Yükle',
'LBL_FILE_NAME'=>'Dosya Adı',
'LBL_SEND'=>'Gönder',

'LBL_EMAIL_TEMPLATES'=>'E-Posta Şablonları',
'LBL_TEMPLATE_NAME'=>'Şablon İsimleri',
'LBL_DESCRIPTION'=>'Açıklamalar',
'LBL_EMAIL_TEMPLATES_LIST'=>'E-Posta Şablonları Listesi',
'LBL_EMAIL_INFORMATION'=>'E-Posta Bilgileri',




//for v4 release added
'LBL_NEW_LEAD'=>'Yeni Kaynak',
'LBL_LEAD_TITLE'=>'Kaynaklar',

'LBL_NEW_PRODUCT'=>'Yeni Ürün',
'LBL_PRODUCT_TITLE'=>'Ürünler',
'LBL_NEW_CONTACT'=>'Yeni ilgili Kişi',
'LBL_CONTACT_TITLE'=>'İlgili Kişiler',
'LBL_NEW_ACCOUNT'=>'Yeni Müşteri',
'LBL_ACCOUNT_TITLE'=>'Müşteriler',

// Added vtiger_fields after vtiger4 - Beta
'LBL_USER_TITLE'=>'Kullanıcılar',
'LBL_NEW_USER'=>'Yeni Kullanıcı',

// Added for 4 GA
'LBL_TOOL_FORM_TITLE'=>'E-Posta Araçları',
//Added for 4GA
'Date & Time Sent'=>'Gönderildiği Tarih & Zaman',
'Sales Enity Module'=>'Satış Modülü',
'Related To'=>'ilgili ',
'Assigned To'=>'Atandığı Kişi',
'Subject'=>'Konu',
'Attachment'=>'Ek',
'Description'=>'Tanım',
'Time Start'=>'Başlangıç Zamanı',
'Created Time'=>'Oluşturulduğu Zaman',
'Modified Time'=>'Değiştirldiği Zaman',

'MESSAGE_CHECK_MAIL_SERVER_NAME'=>'Please Check the Mail Server Name...',
'MESSAGE_CHECK_MAIL_ID'=>'Please Check the Email Id of "Assigned To" User...',
'MESSAGE_MAIL_HAS_SENT_TO_USERS'=>'Mail has been sent to the following User(s) :',
'MESSAGE_MAIL_HAS_SENT_TO_CONTACTS'=>'Mail has been sent to the following Contact(s) :',
'MESSAGE_MAIL_ID_IS_INCORRECT'=>'Mail Id is incorrect. Please Check this Mail Id...',
'MESSAGE_ADD_USER_OR_CONTACT'=>'lütfen ilgili kişi veya kullanıcı ekleyiniz...',
'MESSAGE_MAIL_SENT_SUCCESSFULLY'=>' E-Posta başarıyla gönderildi!',

// Added for web mail post 4.0.1 release
'LBL_FETCH_WEBMAIL'=>'Netpostaları getir',
//Added for 4.2 Release -- CustomView
'LBL_ALL'=>'All',
'MESSAGE_CONTACT_NOT_WANT_MAIL'=>'Bu ilgili kişi E-Posta istemiyor.',
'LBL_WEBMAILS_TITLE'=>'WebMail',
'LBL_EMAILS_TITLE'=>'E-Posta',
'LBL_MAIL_CONNECT_ERROR_INFO'=>'E-Posta Sunucusuna bağşanılamadı !<br> Hesabım->E-Posta Sunucu Listesi ->E-Posta Hesapları Listesi',






'LBL_ALLMAILS'=>'Bütün E-Postalar',
'LBL_TO_USERS'=>'Kullanıcılar',
'LBL_TO'=>'Kime:',
'LBL_IN_SUBJECT'=>'konulu',
'LBL_IN_SENDER'=>'gönderen',
'LBL_IN_SUBJECT_OR_SENDER'=>'konu veya gönderen',
'SELECT_EMAIL'=>'E-Posta ID seçiniz',
'Sender'=>'Gönderen',
'LBL_CONFIGURE_MAIL_SETTINGS'=>'Gelen E-Posta Sunucunuz Ayarlanmamış',
'LBL_MAILSELECT_INFO'=>'has the follwoing Email IDs associated.Please Select the Email IDs to which,the mail should be sent',
'LBL_MAILSELECT_INFO1'=>'The following Email ID types are associated to the selected',
'LBL_MAILSELECT_INFO2'=>'Gönderilecek E-Posta Tipini Seçiniz',
'LBL_MULTIPLE'=>'Multiple',
'LBL_COMPOSE_EMAIL'=>'E-Posta Yaz',
'LBL_VTIGER_EMAIL_CLIENT'=>'vtiger Webmail Client',

//Added for 5.0.3
'TITLE_VTIGERCRM_MAIL'=>'vtigerCRM Mail',
'TITLE_COMPOSE_MAIL'=>'E-Posta Yaz',

'MESSAGE_MAIL_COULD_NOT_BE_SEND'=>'Posta kullanıcıya atanamadı.',
'MESSAGE_PLEASE_CHECK_ASSIGNED_USER_EMAILID'=>'lütfen kullanıcı Eposta IDnin atamasını kontrol ediniz...',
'MESSAGE_PLEASE_CHECK_THE_FROM_MAILID'=>'Lütfen gönderen eposta adresini kontrol ediniz',
'MESSAGE_MAIL_COULD_NOT_BE_SEND_TO_THIS_EMAILID'=>'Posta bu eposta adresine gönderilemedi',
'PLEASE_CHECK_THIS_EMAILID'=>'Lütfen bu posta adresini kontrol ediniz...',
'LBL_CC_EMAIL_ERROR'=>'CC eposta adresi doğru değildir',
'LBL_BCC_EMAIL_ERROR'=>'BCC eposta adresi doğru değildir',
'LBL_NO_RCPTS_EMAIL_ERROR'=>'Alıcı Seçmediniz',
'LBL_CONF_MAILSERVER_ERROR'=>'Please configure your outgoing mailserver under Settings ---> Outgoing Server link',
'LBL_VTIGER_EMAIL_CLIENT'=>'vtiger Webmail Client',
'LBL_MAILSELECT_INFO3'=>'You don\'t have permission to view email id(s) of the selected Record(s).',
'LBL_NO_RECORDS' => 'Bu -klasörde eposta bulunmamaktadır',
//Added  for script alerts
'FEATURE_AVAILABLE_INFO' => 'This feature is currently only available for Microsoft Internet Explorer 5.5+ users\n\nWait for an update!',
'DOWNLOAD_CONFIRAMATION' => 'Dosyayı kaydetmek istermisiniz?',
'LBL_PLEASE_ATTACH' => 'Lütfen uygun dosyaları ekleyiniz!',
'LBL_KINDLY_UPLOAD' => 'Please configure <font color="red">upload_tmp_dir</font> variable in php.ini file.',
'LBL_EXCEED_MAX' => 'Üzgünüm maksimum dosya ekleme limitini aştınız. Lütfen daha küçük dosya deneyiniz ',
'LBL_BYTES' => ' bytes',
'LBL_CHECK_USER_MAILID' => 'Lütfen mevcut posta adresinizi kontrol ediniz',

// Added/Updated for vtiger CRM 5.0.4
'Activity Type'=>'Etkinlik Tipi',
'LBL_MAILSELECT_INFO'=>' ile ilişkili aşağıdaki eposta adresleri var. Eposta gönderilmesini istediğiniz eposta adreslerini seçin',
'LBL_NO_RECORDS' => 'Hiç Kayıt Yok',
'LBL_PRINT_EMAIL'=> 'Yazdır',

);

?>
