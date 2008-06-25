<?php
//
// phpSysInfo - A PHP System Information Script
// http://phpsysinfo.sourceforge.net/
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// $Id: lt.php,v 1.16 2005/12/31 17:25:02 bigmichi1 Exp $

$charset                = 'utf-8';

$text['title']          = 'Informacija apie sistemą';

$text['vitals']         = 'Sistema';
$text['hostname']       = 'Kompiuterio vardas';
$text['ip']             = 'IP adresas';
$text['kversion']       = 'Branduolio versija';
$text['dversion']       = 'Distribucija';
$text['uptime']         = 'Veikimo laikas';
$text['users']          = 'Vartotojai';
$text['loadavg']        = 'Apkrovos vidurkiai';

$text['hardware']       = 'Aparatūra';
$text['numcpu']         = 'Procesorių kiekis';
$text['cpumodel']       = 'Modelis';
$text['cpuspeed']       = 'Procesoriaus dažnis';
$text['busspeed']       = 'Magistralės dažnis';
$text['cache']          = 'Spartinan�?ioji atmintinė';
$text['bogomips']       = 'Sistemos „bogomips“';

$text['pci']            = 'PCI įrenginiai';
$text['ide']            = 'IDE įrenginiai';
$text['scsi']           = 'SCSI įrenginiai';
$text['usb']            = 'USB įrenginiai';

$text['netusage']       = 'Tinklas';
$text['device']         = 'Įrenginys';
$text['received']       = 'Gauta';
$text['sent']           = 'Išsiųsta';
$text['errors']         = 'Klaidos/pamesti paketai';

$text['memusage']       = 'Atmintis';
$text['phymem']         = 'Operatyvioji atmintis';
$text['swap']           = 'Disko swap skirsnis';

$text['fs']             = 'Bylų sistema';
$text['mount']          = 'Prijungimo vieta';
$text['partition']      = 'Skirsnis';

$text['percent']        = 'Apkrova procentais';
$text['type']           = 'Tipas';
$text['free']           = 'Laisva';
$text['used']           = 'Apkrauta';
$text['size']           = 'Dydis';
$text['totals']         = 'Iš viso';

$text['kb']             = 'KB';
$text['mb']             = 'MB';
$text['gb']             = 'GB';

$text['none']           = 'nėra';

$text['capacity']       = 'Talpa';
  
$text['template']       = 'Šablonas';
$text['language']       = 'Kalba';
$text['submit']         = 'Atnaujinti';
$text['created']        = 'Naudojamas';

$text['days']           = 'd.';
$text['hours']          = 'val.';
$text['minutes']        = 'min.';

$text['temperature']    = 'Temperatūra';
$text['voltage']        = 'Įtampa';
$text['fans']           = 'Aušintuvai';
$text['s_value']        = 'Reikšmė';
$text['s_min']          = 'Min';
$text['s_max']          = 'Maks';
$text['s_div']          = 'Div';
// Hysteresis is the value that defines at which temp
// the alarm should deactivate. If you have set an
// alarm to go off when CPU temp reaches 60 degrees,
// a hysteresis set at, say, 58 degress will deactivate
// the alarm when temp goes below 58 degrees.
$text['hysteresis']     = 'Signalizuojama ties';
$text['s_limit']        = 'Riba';
$text['s_label']        = 'Pavadinimas';
$text['degree_mark']    = '&ordm;C';
$text['voltage_mark']   = 'V';
$text['rpm_mark']       = 'aps./min';

$text['app']		= 'Kernel + applications';
$text['buffers']	= 'Buffers';
$text['cached']		= 'Cached';

?>
