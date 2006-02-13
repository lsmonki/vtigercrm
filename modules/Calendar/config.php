<?php
#
#
#this is a ERROR
#
require_once 'config.php';
$tutos[demo]=0;
$tutos[dbname][0]   = $dbconfig['db_name'];
$tutos[dbhost][0]   = $dbconfig['db_host_name'];
$tutos[dbport][0]   = 5000;
$tutos[dbuser][0]   = $dbconfig['db_user_name'];
$tutos[dbpasswd][0] = $dbconfig['db_password'];
$tutos[dbtype][0]   = 2;
$tutos[dbalias][0]  = "MYSQL TEST database";
$tutos[cryptpw][0]  = 1;
$tutos[repository][0]  = "repository";
$tutos[dbprefix][0]  = "tut_";
$tutos[baseurl][0]  = "http://next/tutosdev";

$tutos[dbname][1]   = "tutos";
$tutos[dbhost][1]   = "localhost";
$tutos[dbport][1]   = 5432;
$tutos[dbuser][1]   = "wwwrun";
$tutos[dbpasswd][1] = "";
$tutos[dbtype][1]   = 2;
$tutos[dbalias][1]  = "Postgres database";
$tutos[cryptpw][1]  = 1;
$tutos[repository][1]  = "repository1";
$tutos[dbprefix][1]  = "";
$tutos[baseurl][1]  = "http://next/tutosdev";

$tutos[dbname][2]   = "/tmp/tutos.gdb";
#$tutos[dbhost][2]   = $_SERVER["SERVER_NAME"];
$tutos[dbhost][2]   = 'localhost';
$tutos[dbhost][2]   = "192.168.7.75";
$tutos[dbport][2]   = 0;
$tutos[dbuser][2]   = "wwwrun";
$tutos[dbpasswd][2] = "tutos";
$tutos[dbtype][2]   = 7;
$tutos[dbalias][2]  = "Interbase";
$tutos[cryptpw][2]  = 0;
$tutos[repository][2]  = "repository2";

$tutos[dbname][3]   = "HADES";
$tutos[dbhost][3]   = "hades";
$tutos[dbport][3]   = 0;
$tutos[dbuser][3]   = "tutos";
$tutos[dbpasswd][3] = "tutos2002";
$tutos[dbtype][3]   = 3;
$tutos[dbalias][3]  = "Oracle on hades";
$tutos[cryptpw][3]  = 1;
$tutos[repository][3]  = "repository3";
$tutos[dbhome][3]   = "/opt/oracle/";

$tutos[dbname][4]   = "tutosdev";
$tutos[dbhost][4]   = "localhost";
$tutos[dbport][4]   = 5432;
$tutos[dbuser][4]   = "wwwrun";
$tutos[dbpasswd][4] = "";
$tutos[dbtype][4]   = 1;
$tutos[dbalias][4]  = "POSTGRES";
$tutos[cryptpw][4]  = 1;
$tutos[repository][4]  = "repository4";

$tutos[proxyhost] = "localhost";
$tutos[proxyport] = 3128;

$tutos[defaultTZ] = "Europe/Berlin";

$tutos[ldapserver][0] = "scd2ldap.siemens.net";
$tutos[ldapport][0]   = 389;
$tutos['ldap:scd2ldap.siemens.net:389']['servername']= 'SCD';
$tutos['ldap:scd2ldap.siemens.net:389']['coding']    = 'utf';
$tutos['ldap:scd2ldap.siemens.net:389']['basedn']    = ' ';
$tutos['ldap:scd2ldap.siemens.net:389']['uniquekey'] = 'scdId';
$tutos['ldap:scd2ldap.siemens.net:389']['fname']     = 'givenname';
$tutos['ldap:scd2ldap.siemens.net:389']['lname']     = 'sn';
$tutos['ldap:scd2ldap.siemens.net:389']['title']     = 'personaltitle';
$tutos['ldap:scd2ldap.siemens.net:389']['country']   = 'c';
$tutos['ldap:scd2ldap.siemens.net:389']['phone_1']   = 'telephonenumber';
$tutos['ldap:scd2ldap.siemens.net:389']['phone_2']   = 'mobile';
$tutos['ldap:scd2ldap.siemens.net:389']['fax_1']     = 'faxnumber';
$tutos['ldap:scd2ldap.siemens.net:389']['email_1']   = 'mail';
$tutos['ldap:scd2ldap.siemens.net:389']['city']      = 'localitynational';
$tutos['ldap:scd2ldap.siemens.net:389']['+']['A+']   = array('l','scdLocality');
$tutos['ldap:scd2ldap.siemens.net:389']['street1']   = 'A+street';
$tutos['ldap:scd2ldap.siemens.net:389']['zip']       = 'A+postalcode';
$tutos['ldap:scd2ldap.siemens.net:389']['+']['B+']   = array('o','organization');
$tutos['ldap:scd2ldap.siemens.net:389']['+']['C+']   = array('ou','organizationalunit');
$tutos['ldap:scd2ldap.siemens.net:389']['company']   = 'C+ounameinternational';

$tutos[ldapserver][1] = "www.trustcenter.de";
$tutos[ldapport][1]   = 389;
$tutos['ldap:www.trustcenter.de:389']['servername']= 'Truscenter';
$tutos['ldap:www.trustcenter.de:389']['basedn']    = 'dc=de';
$tutos['ldap:www.trustcenter.de:389']['fullname']  = 'cn';
$tutos['ldap:www.trustcenter.de:389']['uniquekey'] = 'dn';
$tutos['ldap:www.trustcenter.de:389']['email_1']   = 'mail';
$tutos['ldap:www.trustcenter.de:389']['city']      = 'l';
$tutos['ldap:www.trustcenter.de:389']['country']   = 'c';
$tutos['ldap:www.trustcenter.de:389']['company']   = 'o';
$tutos['ldap:www.trustcenter.de:389']['department']= 'ou';

$tutos[ldapserver][2] = "x500.bund.de";
$tutos[ldapport][2]   = 389;
$tutos['ldap:x500.bund.de:389']['servername']= 'BUND';
$tutos['ldap:x500.bund.de:389']['coding']    = 'plain';
$tutos['ldap:x500.bund.de:389']['basedn']    = 'o=Bund,c=DE';
$tutos['ldap:x500.bund.de:389']['uniquekey'] = 'dn';
$tutos['ldap:x500.bund.de:389']['fname']     = 'sn';
$tutos['ldap:x500.bund.de:389']['lname']     = 'givenname';
$tutos['ldap:x500.bund.de:389']['title']     = 'vocation';
$tutos['ldap:x500.bund.de:389']['phone_1']   = 'telephonenumber';
$tutos['ldap:x500.bund.de:389']['fax_1']     = 'facsimiletelephonenumber';
$tutos['ldap:x500.bund.de:389']['zip']       = 'postalcode';
$tutos['ldap:x500.bund.de:389']['email_1']   = 'mail';
$tutos['ldap:x500.bund.de:389']['desc1']     = 'functionaltitle';
$tutos['ldap:x500.bund.de:389']['street1']   = 'postofficebox';
$tutos['ldap:x500.bund.de:389']['city']      = 'gouvernmentorganizationalpersonlocality';

$tutos[ldapserver][3] = "ldap.openldap.org";
$tutos[ldapport][3]   = 389;
$tutos['ldap:ldap.openldap.org:389']['basedn']   ="dc=openLDAP,dc=Org";

$tutos[ldapserver][4] = "ldap.rediris.es";
$tutos[ldapport][4]   = 1389;
$tutos['ldap:ldap.rediris.es:1389']['basedn']   ="dc=rediris,dc=es";
$tutos['ldap:ldap.rediris.es:1389']['uniquekey']   ="dn";
$tutos['ldap:ldap.rediris.es:1389']['lname']     = 'sn';
$tutos['ldap:ldap.rediris.es:1389']['email_1']   = 'mail';
$tutos['ldap:ldap.rediris.es:1389']['picture']   = 'jpegphoto';
$tutos['ldap:ldap.rediris.es:1389']['fax_1']     = 'facsimiletelephonenumber';
$tutos['ldap:ldap.rediris.es:1389']['phone_1']   = 'telephonenumber';
$tutos['ldap:ldap.rediris.es:1389']['desc1']     = 'description';
$tutos['ldap:ldap.rediris.es:1389']['fullname']  = 'cn';
$tutos['ldap:ldap.rediris.es:1389']['url']       = 'labeleduri';

$tutos[ldapserver][5] = "ldap.uninett.no";
$tutos[ldapport][5]   = 389;
$tutos['ldap:ldap.uninett.no:389']['basedn']    ="dc=uninett,dc=no";
$tutos['ldap:ldap.uninett.no:389']['uniquekey'] ="dn";
$tutos['ldap:ldap.uninett.no:389']['lname']     ="sn";
$tutos['ldap:ldap.uninett.no:389']['fname']     ="givenname";
$tutos['ldap:ldap.uninett.no:389']['email_1']   ="mail";
$tutos['ldap:ldap.uninett.no:389']['picture']   ="jpegphoto";
$tutos['ldap:ldap.uninett.no:389']['phone_1']   ="telephonenumber";
$tutos['ldap:ldap.uninett.no:389']['fax_1']     ="facsimiletelephonenumber";
$tutos['ldap:ldap.uninett.no:389']['company']   ="o";

$tutos[ldapserver][13] = "master.surfnet.nl";
$tutos[ldapport][13]   = 389;
$tutos['ldap:master.surfnet.nl:389']['basedn']    = 'c=NL';
$tutos['ldap:master.surfnet.nl:389']['uniquekey']    = 'dn';
$tutos['ldap:master.surfnet.nl:389']['url']       = 'labeleduri';

$tutos[ldapserver][14] = "www.nldap.com";
$tutos[ldapport][14]   = 389;

$tutos[ldapserver][15] = "directory.d-trust.de";
$tutos[ldapport][15]   = 389;

$tutos[ldapserver][16] = "memberdir.netscape.com";
$tutos[ldapport][16]   = 389;
$tutos['ldap:memberdir.netscape.com:389']['basedn']    = 'ou=member_directory,o=netcenter.com';

$tutos[ldapserver][17] = "alpha.dante.org.uk";
$tutos[ldapport][17]   = 389;
$tutos['ldap:alpha.dante.org.uk:389']['servername']= 'DANTE';
$tutos['ldap:alpha.dante.org.uk:389']['coding']    = 'utf';
$tutos['ldap:alpha.dante.org.uk:389']['basedn']    = 'dc=dante,dc=org,dc=uk';
$tutos['ldap:alpha.dante.org.uk:389']['uniquekey'] = 'dn';
$tutos['ldap:alpha.dante.org.uk:389']['fullname']  = 'cn';
$tutos['ldap:alpha.dante.org.uk:389']['lname']     = 'sn';
$tutos['ldap:alpha.dante.org.uk:389']['phone_1']   = 'telephonenumber';
$tutos['ldap:alpha.dante.org.uk:389']['email_1']   = 'mail';
$tutos['ldap:alpha.dante.org.uk:389']['street1']   = 'street';
$tutos['ldap:alpha.dante.org.uk:389']['zip']       = 'postalcode';
$tutos['ldap:alpha.dante.org.uk:389']['fax_1']     = 'facsimiletelephonenumber';
$tutos['ldap:alpha.dante.org.uk:389']['desc1']     = 'organizationalstatus';
$tutos['ldap:alpha.dante.org.uk:389']['picture']   = 'jpegphoto';
$tutos['ldap:alpha.dante.org.uk:389']['url']       = 'labeleduri';

$tutos[ldapserver][18] = "next";
$tutos[ldapport][18]   = 389;
$tutos['ldap:next:389']['servername']= 'TEST';
$tutos['ldap:next:389']['coding']    = 'utf';
$tutos['ldap:next:389']['basedn']    = 'o=tutos';
$tutos['ldap:next:389']['uniquekey'] = 'dn';

$tutos[mailmode] = 2;
$tutos[smtphost] = "192.168.7.75";
$tutos[popbeforesmtp_user] = "testuser";
$tutos[popbeforesmtp_pass] = "tutos";
$tutos[faxmode] = 1;
$tutos[faxspool]  = "/usr/bin/faxspool";
$tutos[faxmail]  = "fax@tutos.org";
$tutos[faxmail_user]  = "tutos";
$tutos[faxmail_pass]  = "xxx";
$tutos[themes] = array();
$tutos[themes][] = "nuke";
$tutos[themes][] = "blue";
$tutos[themes][] = "tutos";
$tutos[themes][] = "red";
$tutos[themes][] = "white";
$tutos[themes][] = "sqli";
$tutos[themes][] = "visual";

$tutos[debug]=0;
$tutos[usemail]   = 1;
$tutos[useacl]   = 1;
$tutos[use_check_dbacl] = 1;
$tutos[debugConsole]=0;
$tutos[defaultacl] = 2;

$tutos[handler]['country']['cia'] = 'cia_factbook/cia.pinc';
$tutos[handler]['city']['map24'] = 'map24/map24.pinc';
$tutos[handler]['city']['mapquest'] = 'mapquest/mapquest.pinc';
$tutos[handler]['city']['pw'] = 'phpweather/pw.pinc';
$tutos[handler]['fax']['fax'] = 'fax/fax.pinc';
$tutos[handler]['phone']['sms'] = 'sms/sms.pinc';
$tutos[handler]['phone']['fax'] = 'fax/fax.pinc';
$tutos[handler]['money']['oanda'] = 'oanda/oanda.pinc';
$tutos[handler]['money']['yahoo'] = 'yahoo/yahoo_currency_converter.pinc';

# ldap
#$tutos[-17] = 0;


$tutos[logo] = "http://next/tutosdev/html/tutos_small.png";
$tutos[smsmail] = "";

$tutos[wvSummary] = "/usr/bin/wvSummary";
$tutos[wvHtml]    = "/usr/bin/wvWare";

$tutos[authtype] = "db";
$tutos['typo3dbname']   = 'usr_web499_1';
$tutos['typo3dbhost']   = 'localhost';
$tutos['typo3dbuser']   = 'web499';
$tutos['typo3dbpasswd'] = 'zwei_passwor';

$tutos['typo3dbname']   = $tutos[dbname][1];
$tutos['typo3dbhost']   = $tutos[dbhost][1];
$tutos['typo3dbuser']   = $tutos[dbuser][1];
$tutos['typo3dbpasswd'] = $tutos[dbpasswd][1];


#
# The requirements engineering module
# allows you to keep track of your product requirements
# Modul by Gero Kohnert  tutos@tutos.de
#
#@include("requirements/mconfig.pinc");
#
# The riskmanagement module
# Modul by Gero Kohnert tutos@tutos.de
#
#@include("riskmanagement/mconfig.pinc");

#@include("search/mconfig.pinc");
#@include("palm/mconfig.pinc");
#@include("ticker/mconfig.pinc");
#@include("xml/mconfig.pinc");
#@include("stuff/mconfig.pinc");
#@include("ldap/mconfig.pinc");

$tutos[tasksincalendar]  = 1;

#$tutos[-17] = 0; # ldap
#$tutos[sessionpath] = $root_directory ."modules/Calendar/tmp";
#$tutos[errlog] = $tutos[sessionpath]. "/debug.out";
$tutos[jpgraph]  = "jpgraph";
#$tutos[fpdfpath]  = "";
$tutos[-15] = 0;

?>
