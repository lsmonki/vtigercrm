<?php
/**
 * SquirrelMail internationalization functions
 *
 * Copyright (c) 1999-2005 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * This file contains variuos functions that are needed to do
 * internationalization of SquirrelMail.
 *
 * Internally the output character set is used. Other characters are
 * encoded using Unicode entities according to HTML 4.0.
 *
 * @version $Id$
 * @package squirrelmail
 * @subpackage i18n
 */

/** Everything uses global.php... */
require_once(SM_PATH . 'functions/global.php');

/* Decodes a string to the internal encoding from the given charset */
function charset_decode ($charset, $string) {
    global $languages, $squirrelmail_language;

    if (isset($languages[$squirrelmail_language]['XTRA_CODE']) &&
        function_exists($languages[$squirrelmail_language]['XTRA_CODE'])) {
        $string = $languages[$squirrelmail_language]['XTRA_CODE']('decode', $string);
    }

    /* All HTML special characters are 7 bit and can be replaced first */

    $string = htmlspecialchars ($string);
    $charset = strtolower($charset);

    set_my_charset() ;

    /* controls cpu and memory intensive decoding cycles */
    $aggressive_decoding = false;

    $decode=fixcharset($charset);
    $decodefile=SM_PATH . 'functions/decode/' . $decode . '.php';
    if (file_exists($decodefile)) {
      include_once($decodefile);
      $ret = call_user_func('charset_decode_'.$decode, $string);
    } else {
      $ret = $string;
    }

    return( $ret );
}

/**
 * Converts html string to given charset
 * @since 1.4.4 and 1.5.1
 * @param string $string
 * @param string $charset
 * @param boolean $htmlencode keep htmlspecialchars encoding
 * @param string
 */
function charset_encode($string,$charset,$htmlencode=true) {
    global $default_charset;

    $encode=fixcharset($charset);
    $encodefile=SM_PATH . 'functions/encode/' . $encode . '.php';
    if (file_exists($encodefile)) {
        include_once($encodefile);
        $ret = call_user_func('charset_encode_'.$encode, $string);
    } else {
        include_once(SM_PATH . 'functions/encode/us_ascii.php');
        $ret = charset_encode_us_ascii($string);
    }

    /**
     * Undo html special chars, some places (like compose form) have
     * own sanitizing functions and don't need html symbols.
     * Undo chars only after encoding in order to prevent conversion of 
     * html entities in plain text emails.
     */
    if (! $htmlencode ) {
        $ret = str_replace(array('&amp;','&gt;','&lt;','&quot;'),array('&','>','<','"'),$ret);
    }

    return( $ret );
}

/**
 * Combined decoding and encoding functions
 *
 * If conversion is done to charset different that utf-8, unsupported symbols
 * will be replaced with question marks.
 * @since 1.4.4 and 1.5.1
 * @param string $in_charset initial charset
 * @param string $string string that has to be converted
 * @param string $out_charset final charset
 * @param boolean $htmlencode keep htmlspecialchars encoding
 * @return string converted string
 */
function charset_convert($in_charset,$string,$out_charset,$htmlencode=true) {
    $string=charset_decode($in_charset,$string);
    $string=charset_encode($string,$out_charset,$htmlencode);
    return $string;
}

/**
 * Makes charset name suitable for decoding cycles
 *
 * @param string $charset Name of charset
 * @return string $charset Adjusted name of charset
 */
function fixcharset($charset) {
    /* remove minus and characters that might be used in paths from charset
     * name in order to be able to use it in function names and include calls.
     */
    $charset=preg_replace("/[-:.\/\\\]/",'_',$charset);

    // windows-125x and cp125x charsets
    $charset=str_replace('windows_','cp',$charset);

    // ibm > cp
    $charset=str_replace('ibm','cp',$charset);

    // iso-8859-8-i -> iso-8859-8
    // use same cycle until I'll find differences
    $charset=str_replace('iso_8859_8_i','iso_8859_8',$charset);

    return $charset;
}

/*
 * Set up the language to be output
 * if $do_search is true, then scan the browser information
 * for a possible language that we know
 */
function set_up_language($sm_language, $do_search = false, $default = false) {

    static $SetupAlready = 0;
    global $use_gettext, $languages,
           $squirrelmail_language, $squirrelmail_default_language,
           $sm_notAlias;

    if ($SetupAlready) {
        return;
    }

    $SetupAlready = TRUE;
    sqgetGlobalVar('HTTP_ACCEPT_LANGUAGE',  $accept_lang, SQ_SERVER);

    if ($do_search && ! $sm_language && isset($accept_lang)) {
        $sm_language = substr($accept_lang, 0, 2);
    }
    if ((!$sm_language||$default) && isset($squirrelmail_default_language)) {
        $squirrelmail_language = $squirrelmail_default_language;
        $sm_language = $squirrelmail_default_language;
    }
    $sm_notAlias = $sm_language;
    while (isset($languages[$sm_notAlias]['ALIAS'])) {
        $sm_notAlias = $languages[$sm_notAlias]['ALIAS'];
    }

    if ( isset($sm_language) &&
         $use_gettext &&
         $sm_language != '' &&
         isset($languages[$sm_notAlias]['CHARSET']) ) {
        bindtextdomain( 'squirrelmail', SM_PATH . 'locale/' );
        textdomain( 'squirrelmail' );
        if (function_exists('bind_textdomain_codeset')) {
            if ($sm_notAlias == 'ja_JP') {
                bind_textdomain_codeset ("squirrelmail", 'EUC-JP');
            } else {
                bind_textdomain_codeset ("squirrelmail", $languages[$sm_notAlias]['CHARSET'] );
            }
        }
        if (isset($languages[$sm_notAlias]['LOCALE'])){
            $longlocale=$languages[$sm_notAlias]['LOCALE'];
        } else {
            $longlocale=$sm_notAlias;
        }
        if ( !((bool)ini_get('safe_mode')) &&
             getenv( 'LC_ALL' ) != $longlocale ) {
            putenv( "LC_ALL=$longlocale" );
            putenv( "LANG=$longlocale" );
            putenv( "LANGUAGE=$longlocale" );
            putenv( "LC_NUMERIC=C" );
            if ($sm_notAlias=='tr_TR') putenv( "LC_CTYPE=C" );
        }
        setlocale(LC_ALL, $longlocale);
        // Workaround for plugins that use numbers with floating point
        // It might be removed if plugins use correct decimal delimiters
        // according to locale settings.
        setlocale(LC_NUMERIC, 'C');
        // Workaround for specific Turkish strtolower/strtoupper rules.
        // Many functions expect English conversion rules.
        if ($sm_notAlias=='tr_TR') setlocale(LC_CTYPE,'C');

        $squirrelmail_language = $sm_notAlias;
        if ($squirrelmail_language == 'ja_JP') {
            header ('Content-Type: text/html; charset=EUC-JP');
            if (!function_exists('mb_internal_encoding')) {
                echo _("You need to have php4 installed with the multibyte string function enabled (using configure option --enable-mbstring).");
                // Revert to English link has to be added.
                // stop further execution in order not to get php errors on mb_internal_encoding().
                return;
            }
            if (function_exists('mb_language')) {
                mb_language('Japanese');
            }
            mb_internal_encoding('EUC-JP');
            mb_http_output('pass');
        } else {
        //header( 'Content-Type: text/html; charset=' . $languages[$sm_notAlias]['CHARSET'] );
    }
}
}

/**
 * Sets default_charset variable according to the one that is used by user's translations.
 *
 * Function changes global $default_charset variable in order to be sure, that it
 * contains charset used by user's translation. Sanity of $squirrelmail_default_language
 * and $default_charset combination provided in SquirrelMail config is also tested.
 *
 * There can be a $default_charset setting in the
 * config.php file, but the user may have a different language
 * selected for a user interface. This function checks the
 * language selected by the user and tags the outgoing messages
 * with the appropriate charset corresponding to the language
 * selection. This is "more right" (tm), than just stamping the
 * message blindly with the system-wide $default_charset.
 */
function set_my_charset(){
    global $data_dir, $username, $default_charset, $languages, $squirrelmail_default_language;

    $my_language = getPref($data_dir, $username, 'language');
    if (!$my_language) {
        $my_language = $squirrelmail_default_language ;
    }
    // Catch removed translation
    if (!isset($languages[$my_language])) {
        $my_language="en_US";
    }
    while (isset($languages[$my_language]['ALIAS'])) {
        $my_language = $languages[$my_language]['ALIAS'];
    }
    $my_charset = $languages[$my_language]['CHARSET'];
    if ($my_language!='en_US') {
        $default_charset = $my_charset;
    }
}

/**
 * Function informs if it is safe to convert given charset to the one that is used by user.
 *
 * It is safe to use conversion only if user uses utf-8 encoding and when
 * converted charset is similar to the one that is used by user.
 *
 * @param string $input_charset Charset of text that needs to be converted
 * @return bool is it possible to convert to user's charset
 */
function is_conversion_safe($input_charset) {
    global $languages, $sm_notAlias, $default_charset, $lossy_encoding;

    if (isset($lossy_encoding) && $lossy_encoding )
        return true;

    // convert to lower case
    $input_charset = strtolower($input_charset);

    // Is user's locale Unicode based ?
    if ( $default_charset == "utf-8" ) {
        return true;
    }

    // Charsets that are similar
    switch ($default_charset) {
    case "windows-1251":
        if ( $input_charset == "iso-8859-5" ||
           $input_charset == "koi8-r" ||
           $input_charset == "koi8-u" ) {
        return true;
        } else {
            return false;
        }
    case "windows-1257":
        if ( $input_charset == "iso-8859-13" ||
             $input_charset == "iso-8859-4" ) {
            return true;
        } else {
            return false;
        }
    case "iso-8859-4":
        if ( $input_charset == "iso-8859-13" ||
             $input_charset == "windows-1257" ) {
            return true;
        } else {
            return false;
        }
    case "iso-8859-5":
        if ( $input_charset == "windows-1251" ||
             $input_charset == "koi8-r" ||
             $input_charset == "koi8-u" ) {
            return true;
        } else {
            return false;
        }
    case "iso-8859-13":
        if ( $input_charset == "iso-8859-4" ||
             $input_charset == "windows-1257" ) {
            return true;
        } else {
            return false;
        }
    case "koi8-r":
        if ( $input_charset == "windows-1251" ||
             $input_charset == "iso-8859-5" ||
             $input_charset == "koi8-u" ) {
            return true;
        } else {
            return false;
        }
    case "koi8-u":
        if ( $input_charset == "windows-1251" ||
             $input_charset == "iso-8859-5" ||
             $input_charset == "koi8-r" ) {
            return true;
        } else {
            return false;
        }
    default:
        return false;
    }
}

/* ---- extra code functions ----*/
/**
 * Japanese charset extra function
 */
function japanese_charset_xtra() {
    $ret = func_get_arg(1);  /* default return value */
    if (function_exists('mb_detect_encoding')) {
        switch (func_get_arg(0)) { /* action */
        case 'decode':
            $detect_encoding = @mb_detect_encoding($ret);
            if ($detect_encoding == 'JIS' ||
                $detect_encoding == 'EUC-JP' ||
                $detect_encoding == 'SJIS' ||
                $detect_encoding == 'UTF-8') {

                $ret = mb_convert_kana(mb_convert_encoding($ret, 'EUC-JP', 'AUTO'), "KV");
            }
            break;
        case 'encode':
            $detect_encoding = @mb_detect_encoding($ret);
            if ($detect_encoding == 'JIS' ||
                $detect_encoding == 'EUC-JP' ||
                $detect_encoding == 'SJIS' ||
                $detect_encoding == 'UTF-8') {

                $ret = mb_convert_encoding(mb_convert_kana($ret, "KV"), 'JIS', 'AUTO');
            }
            break;
        case 'strimwidth':
            $width = func_get_arg(2);
            $ret = mb_strimwidth($ret, 0, $width, '...');
            break;
        case 'encodeheader':
            $result = '';
            if (strlen($ret) > 0) {
                $tmpstr = mb_substr($ret, 0, 1);
                $prevcsize = strlen($tmpstr);
                for ($i = 1; $i < mb_strlen($ret); $i++) {
                    $tmp = mb_substr($ret, $i, 1);
                    if (strlen($tmp) == $prevcsize) {
                        $tmpstr .= $tmp;
                    } else {
                        if ($prevcsize == 1) {
                            $result .= $tmpstr;
                        } else {
                            $result .= str_replace(' ', '',
                                                   mb_encode_mimeheader($tmpstr,'iso-2022-jp','B',''));
                        }
                        $tmpstr = $tmp;
                        $prevcsize = strlen($tmp);
                    }
                }
                if (strlen($tmpstr)) {
                    if (strlen(mb_substr($tmpstr, 0, 1)) == 1)
                        $result .= $tmpstr;
                    else
                        $result .= str_replace(' ', '',
                                               mb_encode_mimeheader($tmpstr,'iso-2022-jp','B',''));
                }
            }
            $ret = $result;
            break;
        case 'decodeheader':
            $ret = str_replace("\t", "", $ret);
            if (eregi('=\\?([^?]+)\\?(q|b)\\?([^?]+)\\?=', $ret))
                $ret = @mb_decode_mimeheader($ret);
            $ret = @mb_convert_encoding($ret, 'EUC-JP', 'AUTO');
            break;
        case 'downloadfilename':
            $useragent = func_get_arg(2);
            if (strstr($useragent, 'Windows') !== false ||
                strstr($useragent, 'Mac_') !== false) {
                $ret = mb_convert_encoding($ret, 'SJIS', 'AUTO');
            } else {
                $ret = mb_convert_encoding($ret, 'EUC-JP', 'AUTO');
}
            break;
        case 'wordwrap':
            $no_begin = "\x21\x25\x29\x2c\x2e\x3a\x3b\x3f\x5d\x7d\xa1\xf1\xa1\xeb\xa1" .
                "\xc7\xa1\xc9\xa2\xf3\xa1\xec\xa1\xed\xa1\xee\xa1\xa2\xa1\xa3\xa1\xb9" .
                "\xa1\xd3\xa1\xd5\xa1\xd7\xa1\xd9\xa1\xdb\xa1\xcd\xa4\xa1\xa4\xa3\xa4" .
                "\xa5\xa4\xa7\xa4\xa9\xa4\xc3\xa4\xe3\xa4\xe5\xa4\xe7\xa4\xee\xa1\xab" .
                "\xa1\xac\xa1\xb5\xa1\xb6\xa5\xa1\xa5\xa3\xa5\xa5\xa5\xa7\xa5\xa9\xa5" .
                "\xc3\xa5\xe3\xa5\xe5\xa5\xe7\xa5\xee\xa5\xf5\xa5\xf6\xa1\xa6\xa1\xbc" .
                "\xa1\xb3\xa1\xb4\xa1\xaa\xa1\xf3\xa1\xcb\xa1\xa4\xa1\xa5\xa1\xa7\xa1" .
                "\xa8\xa1\xa9\xa1\xcf\xa1\xd1";
            $no_end = "\x5c\x24\x28\x5b\x7b\xa1\xf2\x5c\xa1\xc6\xa1\xc8\xa1\xd2\xa1" .
                "\xd4\xa1\xd6\xa1\xd8\xa1\xda\xa1\xcc\xa1\xf0\xa1\xca\xa1\xce\xa1\xd0\xa1\xef";
            $wrap = func_get_arg(2);

            if (strlen($ret) >= $wrap &&
                substr($ret, 0, 1) != '>' &&
                strpos($ret, 'http://') === FALSE &&
                strpos($ret, 'https://') === FALSE &&
                strpos($ret, 'ftp://') === FALSE) {

                $ret = mb_convert_kana($ret, "KV");

                $line_new = '';
                $ptr = 0;

                while ($ptr < strlen($ret) - 1) {
                    $l = mb_strcut($ret, $ptr, $wrap);
                    $ptr += strlen($l);
                    $tmp = $l;

                    $l = mb_strcut($ret, $ptr, 2);
                    while (strlen($l) != 0 && mb_strpos($no_begin, $l) !== FALSE ) {
                        $tmp .= $l;
                        $ptr += strlen($l);
                        $l = mb_strcut($ret, $ptr, 1);
                    }
                    $line_new .= $tmp;
                    if ($ptr < strlen($ret) - 1)
                        $line_new .= "\n";
                }
                $ret = $line_new;
            }
            break;
        case 'utf7-imap_encode':
            $ret = mb_convert_encoding($ret, 'UTF7-IMAP', 'EUC-JP');
            break;
        case 'utf7-imap_decode':
            $ret = mb_convert_encoding($ret, 'EUC-JP', 'UTF7-IMAP');
            break;
        }
    }
    return $ret;
}


/*
 * Korean charset extra function
 * Hangul(Korean Character) Attached File Name Fix.
 */
function korean_charset_xtra() {

    $ret = func_get_arg(1);  /* default return value */
    if (func_get_arg(0) == 'downloadfilename') { /* action */
        $ret = str_replace("\x0D\x0A", '', $ret);  /* Hanmail's CR/LF Clear */
        for ($i=0;$i<strlen($ret);$i++) {
            if ($ret[$i] >= "\xA1" && $ret[$i] <= "\xFE") {   /* 0xA1 - 0XFE are Valid */
                $i++;
                continue;
            } else if (($ret[$i] >= 'a' && $ret[$i] <= 'z') || /* From Original ereg_replace in download.php */
                       ($ret[$i] >= 'A' && $ret[$i] <= 'Z') ||
                       ($ret[$i] == '.') || ($ret[$i] == '-')) {
                continue;
            } else {
                $ret[$i] = '_';
            }
        }

    }

    return $ret;
}


/* ------------------------------ main --------------------------- */

global $squirrelmail_language, $languages, $use_gettext;

if (! isset($squirrelmail_language)) {
    $squirrelmail_language = '';
}

/* This array specifies the available languages. */

$languages['bg_BG']['NAME']    = 'Bulgarian';
$languages['bg_BG']['CHARSET'] = 'windows-1251';
$languages['bg_BG']['LOCALE']  = 'bg_BG.CP1251';
$languages['bg']['ALIAS']      = 'bg_BG';

$languages['bn_IN']['NAME']    = 'Bengali';
$languages['bn_IN']['CHARSET'] = 'utf-8';
$languages['bn_IN']['LOCALE']  = 'bn_IN.UTF-8';
$languages['bn_BD']['ALIAS'] = 'bn_IN';
$languages['bn']['ALIAS'] = 'bn_IN';

$languages['ca_ES']['NAME']    = 'Catalan';
$languages['ca_ES']['CHARSET'] = 'iso-8859-1';
$languages['ca_ES']['LOCALE']  = 'ca_ES.ISO8859-1';
$languages['ca']['ALIAS']      = 'ca_ES';

$languages['cs_CZ']['NAME']    = 'Czech';
$languages['cs_CZ']['CHARSET'] = 'iso-8859-2';
$languages['cs_CZ']['LOCALE']  = 'cs_CZ.ISO8859-2';
$languages['cs']['ALIAS']      = 'cs_CZ';

$languages['cy_GB']['NAME']    = 'Welsh';
$languages['cy_GB']['CHARSET'] = 'iso-8859-1';
$languages['cy_GB']['LOCALE']  = 'cy_GB.ISO8859-1';
$languages['cy']['ALIAS'] = 'cy_GB';

$languages['da_DK']['NAME']    = 'Danish';
$languages['da_DK']['CHARSET'] = 'iso-8859-1';
$languages['da_DK']['LOCALE']  = 'da_DK.ISO8859-1';
$languages['da']['ALIAS']      = 'da_DK';

$languages['de_DE']['NAME']    = 'Deutsch';
$languages['de_DE']['CHARSET'] = 'iso-8859-1';
$languages['de_DE']['LOCALE']  = 'de_DE.ISO8859-1';
$languages['de']['ALIAS']      = 'de_DE';

$languages['el_GR']['NAME']    = 'Greek';
$languages['el_GR']['CHARSET'] = 'iso-8859-7';
$languages['el_GR']['LOCALE']  = 'el_GR.ISO8859-7';
$languages['el']['ALIAS']      = 'el_GR';

$languages['en_GB']['NAME']    = 'British';
$languages['en_GB']['CHARSET'] = 'iso-8859-15';
$languages['en_GB']['LOCALE']  = 'en_GB.ISO-8859-15';

$languages['en_US']['NAME']    = 'English';
$languages['en_US']['CHARSET'] = 'iso-8859-1';
$languages['en_US']['LOCALE']  = 'en_US.ISO8859-1';
$languages['en']['ALIAS']      = 'en_US';

$languages['es_ES']['NAME']    = 'Spanish';
$languages['es_ES']['CHARSET'] = 'iso-8859-1';
$languages['es_ES']['LOCALE']  = 'es_ES.ISO8859-1';
$languages['es']['ALIAS']      = 'es_ES';

$languages['et_EE']['NAME']    = 'Estonian';
$languages['et_EE']['CHARSET'] = 'iso-8859-15';
$languages['et_EE']['LOCALE']  = 'et_EE.ISO8859-15';
$languages['et']['ALIAS']      = 'et_EE';

$languages['eu_ES']['NAME']    = 'Basque';
$languages['eu_ES']['CHARSET'] = 'iso-8859-1';
$languages['eu_ES']['LOCALE']  = 'eu_ES.ISO8859-1';
$languages['eu']['ALIAS']      = 'eu_ES';

$languages['fi_FI']['NAME']    = 'Finnish';
$languages['fi_FI']['CHARSET'] = 'iso-8859-1';
$languages['fi_FI']['LOCALE']  = 'fi_FI.ISO8859-1';
$languages['fi']['ALIAS']      = 'fi_FI';

$languages['fo_FO']['NAME']    = 'Faroese';
$languages['fo_FO']['CHARSET'] = 'iso-8859-1';
$languages['fo_FO']['LOCALE']  = 'fo_FO.ISO8859-1';
$languages['fo']['ALIAS']      = 'fo_FO';

$languages['fr_FR']['NAME']    = 'French';
$languages['fr_FR']['CHARSET'] = 'iso-8859-1';
$languages['fr_FR']['LOCALE']  = 'fr_FR.ISO8859-1';
$languages['fr']['ALIAS']      = 'fr_FR';

$languages['hr_HR']['NAME']    = 'Croatian';
$languages['hr_HR']['CHARSET'] = 'iso-8859-2';
$languages['hr_HR']['LOCALE']  = 'hr_HR.ISO8859-2';
$languages['hr']['ALIAS']      = 'hr_HR';

$languages['hu_HU']['NAME']    = 'Hungarian';
$languages['hu_HU']['CHARSET'] = 'iso-8859-2';
$languages['hu_HU']['LOCALE']  = 'hu_HU.ISO8859-2';
$languages['hu']['ALIAS']      = 'hu_HU';

$languages['id_ID']['NAME']    = 'Bahasa Indonesia';
$languages['id_ID']['CHARSET'] = 'iso-8859-1';
$languages['id_ID']['LOCALE']  = 'id_ID.ISO8859-1';
$languages['id']['ALIAS']      = 'id_ID';

$languages['is_IS']['NAME']    = 'Icelandic';
$languages['is_IS']['CHARSET'] = 'iso-8859-1';
$languages['is_IS']['LOCALE']  = 'is_IS.ISO8859-1';
$languages['is']['ALIAS']      = 'is_IS';

$languages['it_IT']['NAME']    = 'Italian';
$languages['it_IT']['CHARSET'] = 'iso-8859-1';
$languages['it_IT']['LOCALE']  = 'it_IT.ISO8859-1';
$languages['it']['ALIAS']      = 'it_IT';

$languages['ja_JP']['NAME']    = 'Japanese';
$languages['ja_JP']['CHARSET'] = 'iso-2022-jp';
$languages['ja_JP']['XTRA_CODE'] = 'japanese_charset_xtra';
$languages['ja']['ALIAS']      = 'ja_JP';

$languages['ko_KR']['NAME']    = 'Korean';
$languages['ko_KR']['CHARSET'] = 'euc-KR';
// Function does not provide all needed options
// $languages['ko_KR']['XTRA_CODE'] = 'korean_charset_xtra';
$languages['ko']['ALIAS']      = 'ko_KR';

$languages['lt_LT']['NAME']    = 'Lithuanian';
$languages['lt_LT']['CHARSET'] = 'utf-8';
$languages['lt_LT']['LOCALE']  = 'lt_LT.UTF-8';
$languages['lt']['ALIAS']      = 'lt_LT';

$languages['ms_MY']['NAME']    = 'Bahasa Melayu';
$languages['ms_MY']['CHARSET'] = 'iso-8859-1';
$languages['ms_MY']['LOCALE']  = 'ms_MY.ISO8859-1';
$languages['my']['ALIAS'] = 'ms_MY';

$languages['nl_NL']['NAME']    = 'Dutch';
$languages['nl_NL']['CHARSET'] = 'iso-8859-1';
$languages['nl_NL']['LOCALE']  = 'nl_NL.ISO8859-1';
$languages['nl']['ALIAS']      = 'nl_NL';

$languages['nb_NO']['NAME']    = 'Norwegian (Bokm&aring;l)';
$languages['nb_NO']['CHARSET'] = 'iso-8859-1';
$languages['nb_NO']['LOCALE']  = 'nb_NO.ISO8859-1';
$languages['nb']['ALIAS']      = 'nb_NO';

$languages['nn_NO']['NAME']    = 'Norwegian (Nynorsk)';
$languages['nn_NO']['CHARSET'] = 'iso-8859-1';
$languages['nn_NO']['LOCALE']  = 'nn_NO.ISO8859-1';

$languages['pl_PL']['NAME']    = 'Polish';
$languages['pl_PL']['CHARSET'] = 'iso-8859-2';
$languages['pl_PL']['LOCALE']  = 'pl_PL.ISO8859-2';
$languages['pl']['ALIAS']      = 'pl_PL';

$languages['pt_PT']['NAME'] = 'Portuguese (Portugal)';
$languages['pt_PT']['CHARSET'] = 'iso-8859-1';
$languages['pt_PT']['LOCALE']  = 'pt_PT.ISO8859-1';
$languages['pt']['ALIAS']      = 'pt_PT';

$languages['pt_BR']['NAME']    = 'Portuguese (Brazil)';
$languages['pt_BR']['CHARSET'] = 'iso-8859-1';
$languages['pt_BR']['LOCALE']  = 'pt_BR.ISO8859-1';

$languages['ro_RO']['NAME']    = 'Romanian';
$languages['ro_RO']['CHARSET'] = 'iso-8859-2';
$languages['ro_RO']['LOCALE']  = 'ro_RO.ISO8859-2';
$languages['ro']['ALIAS']      = 'ro_RO';

$languages['ru_RU']['NAME']    = 'Russian';
$languages['ru_RU']['CHARSET'] = 'utf-8';
$languages['ru_RU']['LOCALE']  = 'ru_RU.UTF-8';
$languages['ru']['ALIAS']      = 'ru_RU';

$languages['sk_SK']['NAME']     = 'Slovak';
$languages['sk_SK']['CHARSET']  = 'iso-8859-2';
$languages['sk_SK']['LOCALE']   = 'sk_SK.ISO8859-2';
$languages['sk']['ALIAS']       = 'sk_SK';

$languages['sl_SI']['NAME']    = 'Slovenian';
$languages['sl_SI']['CHARSET'] = 'iso-8859-2';
$languages['sl_SI']['LOCALE']  = 'sl_SI.ISO8859-2';
$languages['sl']['ALIAS']      = 'sl_SI';

$languages['sr_YU']['NAME']    = 'Serbian';
$languages['sr_YU']['CHARSET'] = 'iso-8859-2';
$languages['sr_YU']['LOCALE']  = 'sr_YU.ISO8859-2';
$languages['sr']['ALIAS']      = 'sr_YU';

$languages['sv_SE']['NAME']    = 'Swedish';
$languages['sv_SE']['CHARSET'] = 'iso-8859-1';
$languages['sv_SE']['LOCALE']  = 'sv_SE.ISO8859-1';
$languages['sv']['ALIAS']      = 'sv_SE';

/* translation is disabled because it contains less than 50%
 * translated strings
$languages['th_TH']['NAME']    = 'Thai';
$languages['th_TH']['CHARSET'] = 'tis-620';
$languages['th_TH']['LOCALE']  = 'th_TH.TIS-620';
$languages['th']['ALIAS'] = 'th_TH';
*/

$languages['tr_TR']['NAME']    = 'Turkish';
$languages['tr_TR']['CHARSET'] = 'iso-8859-9';
$languages['tr_TR']['LOCALE']  = 'tr_TR.ISO8859-9';
$languages['tr']['ALIAS']      = 'tr_TR';

$languages['zh_TW']['NAME']    = 'Chinese Trad';
$languages['zh_TW']['CHARSET'] = 'big5';
$languages['zh_TW']['LOCALE']  = 'zh_TW.BIG5';
$languages['tw']['ALIAS']      = 'zh_TW';

$languages['zh_CN']['NAME']    = 'Chinese Simp';
$languages['zh_CN']['CHARSET'] = 'gb2312';
$languages['zh_CN']['LOCALE']  = 'zh_CN.GB2312';
$languages['cn']['ALIAS']      = 'zh_CN';

/*
$languages['uk_UA']['NAME']    = 'Ukrainian';
$languages['uk_UA']['CHARSET'] = 'koi8-u';
$languages['uk']['ALIAS'] = 'uk_UA';
*/
/*
$languages['vi_VN']['NAME']    = 'Vietnamese';
$languages['vi_VN']['CHARSET'] = 'utf-8';
$languages['vi']['ALIAS'] = 'vi_VN';
*/

// Right to left languages

$languages['ar']['NAME']    = 'Arabic';
$languages['ar']['CHARSET'] = 'windows-1256';
$languages['ar']['DIR']     = 'rtl';

$languages['fa_IR']['NAME']    = 'Farsi';
$languages['fa_IR']['CHARSET'] = 'utf-8';
$languages['fa_IR']['DIR']     = 'rtl';
$languages['fa_IR']['LOCALE']  = 'fa_IR.UTF-8';
$languages['fa']['ALIAS']      = 'fa_IR';

$languages['he_IL']['NAME']    = 'Hebrew';
$languages['he_IL']['CHARSET'] = 'windows-1255';
$languages['he_IL']['DIR']     = 'rtl';
$languages['he']['ALIAS']      = 'he_IL';

$languages['ug']['NAME']    = 'Uighur';
$languages['ug']['CHARSET'] = 'utf-8';
$languages['ug']['DIR']     = 'rtl';

/* Detect whether gettext is installed. */
$gettext_flags = 0;
if (function_exists('_')) {
    $gettext_flags += 1;
}
if (function_exists('bindtextdomain')) {
    $gettext_flags += 2;
}
if (function_exists('textdomain')) {
    $gettext_flags += 4;
}

/* If gettext is fully loaded, cool */
if ($gettext_flags == 7) {
    $use_gettext = true;
}
/* If we can fake gettext, try that */
elseif ($gettext_flags == 0) {
    $use_gettext = true;
    include_once(SM_PATH . 'functions/gettext.php');
} else {
    /* Uh-ho.  A weird install */
    if (! $gettext_flags & 1) {
        function _($str) {
            return $str;
        }
    }
    if (! $gettext_flags & 2) {
        function bindtextdomain() {
            return;
        }
    }
    if (! $gettext_flags & 4) {
        function textdomain() {
            return;
        }
    }
}
?>
