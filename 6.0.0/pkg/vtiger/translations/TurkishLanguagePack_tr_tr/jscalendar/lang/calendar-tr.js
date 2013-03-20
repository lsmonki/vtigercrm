//////////////////////////////////////////////////////////////////////////////////////////////
//	Turkish Translation by Nuri AKMAN
//	Location: Ankara/TURKEY
//	e-mail	: nuriakman@hotmail.com
//	Date	: April, 9 2003
//
//////////////////////////////////////////////////////////////////////////////////////////////

// ** I18N
Calendar._DN = new Array
("Pazar",
 "Pazartesi",
 "Salı",
 "Çarşamba",
 "Perşembe",
 "Cuma",
 "Cumartesi",
 "Pazar");

Calendar._SDN = new Array
("Paz",
 "Pzt",
 "Sal",
 "Çar",
 "Per",
 "Cum",
 "Cmt",
 "Paz");


Calendar._MN = new Array
("Ocak",
 "Şubat",
 "Mart",
 "Nisan",
 "Mayıs",
 "Haziran",
 "Temmuz",
 "Aðustos",
 "Eylül",
 "Ekim",
 "Kasım",
 "Aralık");

// short month names
Calendar._SMN = new Array
("Oca",
 "Şub",
 "Mar",
 "Nis",
 "May",
 "Haz",
 "Tem",
 "Aug",
 "Eyl",
 "Eki",
 "Kas",
 "Ara");

// tooltips
Calendar._TT = {};
Calendar._TT["TOGGLE"] = "Haftanın ilk gününü kaydır";
Calendar._TT["INFO"] = "Takvim Hakkında";

Calendar._TT["ABOUT"] =
"DHTML seçici Fecha/Hora\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Güncel sürüm şu adreste: http://dynarch.com/mishoo/calendar.epl\n" +
"GNU LGPL lisansı ile dağıtılmıştır. http://gnu.org/licenses/lgpl.html adresinde detayları bulabilirsiniz." +
"\n\n" +
"Tarih seçimi:\n" +
"- \xab ve \xbb butonları ile yıl seçebilirsiniz\n" +
"- " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " butonları ile ay seçimi yapabilirsiniz\n" +
"- Daha hızlı seçim yapmak için fareyi butonlar üzerinde tutun.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Zaman seçimi:\n" +
"- Zaman hanelerine tıklayarak arttırabilirsiniz\n" +
"- veya shift ile beraber tıklayarak azaltabilirsiniz\n" +
"- vye tıklayıp sürükleyerek daha hızlı seçim yapabilirsiniz.";

Calendar._TT["PREV_YEAR"] = "Önceki Yıl (Menü için basılı tutunuz)";
Calendar._TT["PREV_MONTH"] = "Önceki Ay (Menü için basılı tutunuz)";
Calendar._TT["GO_TODAY"] = "Bugün'e git";
Calendar._TT["NEXT_MONTH"] = "Sonraki Ay (Menü için basılı tutunuz)";
Calendar._TT["NEXT_YEAR"] = "Sonraki Yıl (Menü için basılı tutunuz)";
Calendar._TT["SEL_DATE"] = "Tarih seçiniz";
Calendar._TT["DRAG_TO_MOVE"] = "Taşımak için sürükleyiniz";
Calendar._TT["PART_TODAY"] = " (bugün)";
Calendar._TT["MON_FIRST"] = "Takvim Pazartesi gününden başlasın";
Calendar._TT["SUN_FIRST"] = "Takvim Pazar gününden başlasın";
Calendar._TT["DAY_FIRST"] = "İlk %s göster";
Calendar._TT["CLOSE"] = "Kapat";
Calendar._TT["TODAY"] = "Bugün";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "dd-mm-y";
Calendar._TT["TT_DATE_FORMAT"] = "d MM y, DD";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Kapat";
Calendar._TT["TODAY"] = "Bugün";
Calendar._TT["TIME_PART"] = "Değiştirmek için (Shift-)Tıkla veya sürükle";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "hafta";
Calendar._TT["TIME"] = "Zaman:";
