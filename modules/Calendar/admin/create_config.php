<?php
/**
 * Copyright 2001-2004 by Gero Kohnert
 *
 * Create a beginners config.php
 *
 * !! Remove/Disable this script after running !!
 *
 * @modulegroup ADMIN
 * @module scheme
 * @package BASE
 */

 if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
   session_save_path("C:/Temp");
 } else {
   session_save_path("/tmp");
 }
 session_name('TUTOS_CONFIG_CREATOR');
 session_start();
 $_SESSION['name'] = 'TUTOS_CONFIG_CREATOR';

 $cc['cfn1'] = "../config.php";
 $cc['cfn2'] = "../config.pinc";

$conf = array();
$info = array();


/**
 * check if a directory exists and is writeable
 */
 function test_dir($path) {
   $msg = "";
   if (! is_dir($path) ) {
     $msg .= "directory ". realpath($path) ." does not exist<br />\n";
   }
   return $msg;
 }

/**
 * check if a directory exists and is writeable
 */
 function test_dir_writeable($path) {
   $msg = "";
   if (! file_exists($path) || !is_dir($path)) {
     $msg .= realpath($path) ." does not exist<br />\n";
     return $msg;
   }
   $msg .= test_file_make($path ."/testfile");
   return $msg;
 }

/**
 * check if a file exists
 */
 function test_file_exist($path) {
   $msg = "";
   if (! file_exists($path) ) {
     $msg .= "File ". $path ." does not exist<br />\n";
   }
   return $msg;
 }

/**
 * check if a file  is createable
 */
 function test_file_make($path) {
   $msg = "";

   $x = fopen($path ,"wb");
   fwrite($x, "");
   fclose($x);
   if (! file_exists($path) ) {
     $msg .= "could not create ". realpath($path) ."<br />\n";
   } else {
     unlink($path);
   }
   return $msg;
 }

/**
 * check if a host is available
 */
 function test_host_exist($path) {
   $msg = "";
   return $msg;
 }

/**
 *
 */
 function check_permission_to_go() {
   global $cc;

   $fc = 0;
   $msg = "";
   if (file_exists($cc['cfn2'])) {
     $msg .=  "config file ". $cc['cfn2'] ." already exits<br />";
     $fc++;
   }
   if (file_exists($cc['cfn1'])) {
     @include $cc['cfn1'];
     if (!isset($tutos['CCSID'])) {
       $msg .= "config file ". $cc['cfn1'] ." already exits and is not usable for automatic changing<br />";
       $fc++;
     } elseif ($tutos['CCSID'] != session_id() ) {
       $msg .= "config file ". $cc['cfn1'] ." already exits and is not from this session<br />";
       $fc++;
     }
   }

   if ($fc == 2) {
     $msg .= "there are two config files.<br />That is not a good idea<br />";
   }
   return($msg);
 }
/**
 * read config from POST
 */
 function get_config(&$conf,&$info) {
   if (isset($_POST['dbname'])){
     $conf['dbname'][0] = $_POST['dbname']; 
   } else {
     $conf['dbname'][0] = 'tutos'; 
   }
   if (isset($_POST['dbhost'])){
     $conf['dbhost'][0] = $_POST['dbhost']; 
   } else {
     $conf['dbhost'][0] = 'localhost'; 
   }
   if (isset($_POST['dbport'])){   $conf['dbport'][0] = $_POST['dbport']; }
   if (isset($_POST['dbuser'])){   $conf['dbuser'][0] = $_POST['dbuser']; }
   if (isset($_POST['dbpasswd'])){ $conf['dbpasswd'][0] = $_POST['dbpasswd']; }
   if (isset($_POST['dbtype'])){ $conf['dbtype'][0] = $_POST['dbtype']; }
   if (isset($_POST['dbprefix'])){ $conf['dbprefix'][0] = $_POST['dbprefix']; }
   if (isset($_POST['cryptpw'])){ $conf['cryptpw'][0] = $_POST['cryptpw']; }
   if (isset($_POST['repository'])){ $conf['repository'][0] = $_POST['repository']; }
   if (isset($_POST['dbalias'])){
     $conf['dbalias'][0] = $_POST['dbalias']; 
   } else {
     $conf['dbalias'][0] = $conf['dbname'][0];
   }

   if (isset($_POST['mailmode'])){ $conf['mailmode'] = $_POST['mailmode']; }
   if ( isset($_POST['sendmail']) && !empty($_POST['sendmail']) ){
     $conf['sendmail'] = $_POST['sendmail']; 
   } else {
     $conf['sendmail'] = '/usr/lib/sendmail'; 
     $info['sendmail'] = 'using default'; 
   }
   if (isset($_POST['smtphost']) && !empty($_POST['smtphost'])){
     $conf['smtphost'] = $_POST['smtphost'];
   } else {
     $conf['smtphost'] = $conf['dbhost'][0];
     $info['smtphost'] = 'set to dbhost'; 
   }

   if (isset($_POST['sessionpath'])){ $conf['sessionpath'] = $_POST['sessionpath']; }
   if (isset($_POST['errlog'])){ $conf['errlog'] = $_POST['errlog']; }
   if (isset($_POST['jpgraph'])){ $conf['jpgraph'] = $_POST['jpgraph']; }

   if (isset($_POST['demo'])){
     $conf['demo'] = 1; 
   } else {
     $conf['demo'] = 0; 
   }
   if (isset($_POST['debug'])){
     $conf['debug'] = 1; 
   } else {
     $conf['debug'] = 0; 
   }
 }
/**
 *  Create one
 */
 function write_config($conf) {
   global $cc;

   $fn = dirname($_SERVER["SCRIPT_FILENAME"]);
   $x = fopen($fn ."/". $cc['cfn1'] ,"wb");
   @fwrite($x, "<?php
# remove this line when finsihed with config
\$tutos['CCSID'] = \"". session_id() ."\";
#
# sessionpath
#
\$tutos[sessionpath] = \"". $conf['sessionpath']."\";
#
# the next lines are a database definition
#
\$tutos[dbname][0]     = \"". $conf['dbname'][0] ."\";
\$tutos[dbhost][0]     = \"". $conf['dbhost'][0] ."\";
\$tutos[dbport][0]     = \"". $conf['dbport'][0] ."\";
\$tutos[dbuser][0]     = \"". $conf['dbuser'][0] ."\";
\$tutos[dbpasswd][0]   = \"". $conf['dbpasswd'][0] ."\";
\$tutos[dbtype][0]     = \"". $conf['dbtype'][0] ."\";
\$tutos[dbalias][0]    = \"". $conf['dbalias'][0] ."\";
\$tutos[cryptpw][0]    = \"". $conf['cryptpw'][0] ."\";
\$tutos[repository][0] = \"". $conf['repository'][0] ."\";
\$tutos[dbprefix][0]   = \"". $conf['dbprefix'][0] ."\";
#
# MAIL
#
\$tutos[mailmode] = \"". $conf['mailmode']."\";
\$tutos[sendmail] = \"". $conf['sendmail']."\";
\$tutos[smtphost] = \"". $conf['smtphost']."\";
#
# demo mode
#
\$tutos[demo] = ". $conf['demo'].";
#
# debug mode
#
\$tutos[debug] = ". $conf['debug'].";
\$tutos[errlog] = \"". $conf['errlog']."\";
#
\$tutos[jpgraph] = \"". $conf['jpgraph']."\";
#
# EOF
?>
");
   fclose($x);
 }


 $msg = check_permission_to_go();
 if ($msg != "") {
   die ($msg);
 }

 $msg .= get_config($conf,$info);
 $msg = write_config($conf);


 $tutos['base'] = "../..";
 #ini_set("include_path","..");
 include_once 'webelements.p3';
 include_once 'permission.p3';
 require 'admin/mconfig.pinc';

 $dbc = null;
 $x = new tutos_user($dbc);
 $current_user = &$x;
 ReadLang($lang);

 loadmodules("admin","new");
 loadmodule("admin");

 loadlayout();

 /**
  * display a update output
  */
 class create_config extends layout {
   /**
    * test the config
    */
   function info_help_test($fld) {
     global $lang,$info;

     $msg =  "<font size=\"-1\">".$lang['AdminCCHelp'][$fld]."</font>\n";
     if (isset($this->test[$fld]) && !empty($this->test[$fld]) ) {
       $msg .= "<br />\n". $this->error($this->test[$fld]) ."\n";
     }
     if (isset($info[$fld]) && !empty($info[$fld]) ) {
       $msg .= "<br /><i>\n". $info[$fld] ."</i>\n";
     }
     return $msg;
   }
   /**
    * test the config
    */
   function test_config(&$test) {
    global $tutos;

    if ( $tutos[usedocmanagement] != 0) {
        $test['repository'] = test_dir_writeable( getcwd()."/". $tutos['base'] ."/". $tutos[repository][0] );
    }
    $test['sessionpath'] = test_dir_writeable( $tutos[sessionpath] );
    $test['errlog'] = test_dir_writeable( dirname($tutos[errlog]) ); 
    $test['sendmail'] = test_file_exist( $tutos[sendmail] );
    $test['smtphost'] = test_host_exist( $tutos[smtphost] );
    $test['jpgraph'] = test_file_exist(  getcwd()."/". $tutos['base'] ."/". $tutos[jpgraph] ."/jpgraph.php" );

   }
   /**
    * display the info
    */
   Function info() {
     global $lang,$tutos,$table,$test;

     echo "<form name=\"confignew\" action=\"create_config.php\" method=\"POST\">\n";
     echo $this->DataTableStart();



     echo "<tr><th colspan=\"6\">Admin</th></tr>\n";

     echo "<tr>\n";
     echo $this->showfield('Session Path',1,"sessionpath");
     echo " <td colspan=\"5\" valign=\"top\">\n";
     echo " <input name=\"sessionpath\" id=\"sessionpath\" size=\"40\" value=\"". $tutos[sessionpath] ."\"><br />\n";
     echo $this->info_help_test('sessionpath');
     echo "</td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo $this->showfield('Demo Mode',1,"demo");
     echo " <td colspan=\"5\" valign=\"top\">\n";
     echo "  <input name=\"demo\" id=\"demo\" type=\"checkbox\"><br />\n";
     echo $this->info_help_test('demo');
     echo " </td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo $this->showfield('Debug Mode',1,"debug");
     echo " <td colspan=\"2\" valign=\"top\">\n";
     echo " <input name=\"debug\" id=\"debug\" type=\"checkbox\"><br />\n";
     echo $this->info_help_test('debug');
     echo " </td>\n";
     echo $this->showfield('Error Logfile',1,"errlog");
     echo " <td colspan=\"2\" valign=\"top\">\n";
     echo " <input name=\"errlog\" id=\"errlog\" size=\"40\" value=\"". $tutos[errlog] ."\"><br />\n";
     echo $this->info_help_test('errlog');
     echo " </td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo $this->showfield('JPGraph',0,"jpgraph");
     echo " <td colspan=\"5\" valign=\"top\">\n";
     echo " <input name=\"jpgraph\" id=\"jpgraph\" size=\"40\" value=\"". $tutos[jpgraph] ."\"><br />\n";
     echo $this->info_help_test('jpgraph');
     echo " </td>\n";
     echo "</tr>\n";



     echo "<tr>\n";
     echo " <th colspan=\"6\">". $lang['DB'] ."</th>\n";
     echo "</tr>\n";

     $db = new database();
     echo "<tr>\n";
     echo $this->showfield($lang['AdminDBName']."&nbsp;[0]",1,"dbname");
     echo " <td colspan=\"2\" valign=\"top\"><input size=\"". min($table['database']['name'][size],30) ."\" maxlength=\"".$table['database']['name'][size]."\" name=\"dbname\" id=\"dbname\" value=\"". $tutos[dbname][0] ."\"><br />\n";
     echo $this->info_help_test('dbname');
     echo " </td>\n";
     echo $this->showfield($lang['AdminDBType']."&nbsp;[0]",1,"dbtype");
     echo " <td colspan=\"2\" valign=\"top\">\n";
     echo " <select name=\"dbtype\">\n";
     foreach($db->tlist as $i => $f) {
       echo "  <option value=\"".$i."\"".($i == $tutos[dbtype][0] ? " selected":"").">".$f."</option>\n";
     }
     echo " </select><br />\n";
     echo $this->info_help_test('dbtype');
     echo " </td>\n";
     echo "</td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo $this->showfield($lang['AdminDBAlias']."&nbsp;[0]",0,"dbalias");
     echo " <td colspan=\"5\" valign=\"top\"><input size=\"". min($table['database']['dbalias'][size],30) ."\" maxlength=\"".$table['database']['dbalias'][size]."\" name=\"dbalias\" id=\"dbalias\" value=\"". $tutos[dbalias][0] ."\"><br />\n";
     echo $this->info_help_test('dbalias');
     echo " </td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo $this->showfield($lang['AdminDBHost']."&nbsp;[0]",1,"dbhost");
     echo " <td colspan=\"2\" valign=\"top\"><input size=\"". min($table['database']['dbhost'][size],30) ."\" maxlength=\"". $table['database']['dbhost'][size] ."\" name=\"dbhost\" id=\"dbhost\" value=\"". $tutos[dbhost][0] ."\"><br />\n";
     echo $this->info_help_test('dbhost');
     echo " </td>\n";

     echo $this->showfield($lang['AdminDBPort']."&nbsp;[0]",1,"dbport");
     echo " <td colspan=\"2\" valign=\"top\"><input size=\"5\" maxlength=\"5\" name=\"dbport\" id=\"dbport\" value=\"". $tutos[dbport][0] ."\"><br />\n";
     echo $this->info_help_test('dbport');
     echo " </td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo $this->showfield($lang['AdminDBUser']."&nbsp;[0]",1,"dbuser");
     echo " <td colspan=\"2\" valign=\"top\"><input size=\"". min($table['database']['dbuser'][size],30) ."\" maxlength=\"". $table['database']['dbuser'][size] ."\" name=\"dbuser\" id=\"dbuser\" value=\"". $tutos[dbuser][0] ."\"><br />\n";
     echo $this->info_help_test('dbuser');
     echo " </td>\n";

     echo $this->showfield($lang['AdminDBPass']."&nbsp;[0]",0,"dbpasswd");
     echo " <td colspan=\"2\" valign=\"top\"><input size=\"". min($table['database']['dbpass'][size],30) ."\" maxlength=\"". $table['database']['dbpass'][size] ."\" name=\"dbpasswd\" id=\"dbpasswd\" value=\"". $tutos[dbpasswd][0] ."\"><br />\n";
     echo $this->info_help_test('dbpasswd');
     echo " </td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo $this->showfield($lang['AdminDBPrefix']."&nbsp;[0]",0,"dbprefix");
     echo " <td colspan=\"2\" valign=\"top\"><input size=\"". min($table['database']['prefix'][size],10) ."\" maxlength=\"". $table['database']['prefix'][size] ."\" name=\"dbprefix\" id=\"dbprefix\" value=\"". $tutos[dbprefix][0] ."\"><br />\n";
     echo $this->info_help_test('dbprefix');
     echo " </td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo $this->showfield($lang['AdminDBRep']."&nbsp;[0]",0,"repository");
     echo " <td colspan=\"2\" valign=\"top\"><input size=\"". min($table['database']['repository'][size],50) ."\" maxlength=\"". $table['database']['repository'][size] ."\" name=\"repository\" id=\"repository\" value=\"". $tutos[repository][0] ."\"><br />\n";
     echo $this->info_help_test('repository');
     echo " </td>\n";
  
     echo "</tr>\n";

     echo "<tr><th colspan=\"6\">sending Mail</th></tr>\n";
     echo $this->showfieldc('Mail Mode',1,"demo");
     echo " <td colspan=\"5\" valign=\"top\">\n";
     echo " <select name=\"mailmode\">\n";
     echo "  <option value=\"0\" ".(0 == $tutos[mailmode] ? " selected":"").">0 = no mail</option>\n";
     echo "  <option value=\"1\" ".(1 == $tutos[mailmode] ? " selected":"").">1 = via sendmail (see sendmail path below)</option>\n";
     echo "  <option value=\"2\" ".(2 == $tutos[mailmode] ? " selected":"").">2 = via smtp (see smtphost below)</option>\n";
     echo " </select><br />\n";
     echo $this->info_help_test('mailmode');
     echo "</td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo " <td colspan=\"1\" valign=\"top\"></td>\n";
     echo $this->showfield('1: sendmail path',0,"sendmail");
     echo " <td colspan=\"4\" valign=\"top\"><input size=\"30\" maxlength=\"30\" name=\"sendmail\" id=\"sendmail\" value=\"". $tutos[sendmail] ."\"><br />\n";
     echo $this->info_help_test('sendmail');
     echo " </td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo " <td colspan=\"1\" valign=\"top\"></td>\n";
     echo $this->showfield('2: smtp host',0,"smtphost");
     echo " <td colspan=\"4\" valign=\"top\"><input size=\"30\" maxlength=\"30\" name=\"smtphost\" id=\"smtphost\" value=\"". $tutos[smtphost] ."\"><br />\n";
     echo $this->info_help_test('smtphost');
     echo " </td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     submit_reset(0,-1,1,1,1,0);
     echo "</tr>\n";

     echo $this->DataTableEnd();
     hiddenFormElements();
     echo $this->getHidden();
     echo "</form>\n";
     echo $this->setfocus("confignew.name");
     echo $lang['FldsRequired'] ."\n";
   }
   /**
    * navigate
    */
   Function navigate() {
   }
   /**
    * prepare
    */
   Function prepare() {
     global $table,$sequence,$tableidx,$tutos,$msg,$lang;

     $this->name = "Config Maker";
     $this->nomenu = true;
     $this->test = array();

     if ( $tutos[demo] != 0 ) {
#       $msg .= "will not work in demo";
#       $this->stop = true;
     }

     $this->test_config($this->test);
   }
 }


 $l = new create_config($x);
 $l->display();

?>
<!--
    CVS Info:  $Id: create_config.php,v 1.16 2005/05/03 13:18:47 saraj Exp $
    $Author: saraj $
-->
