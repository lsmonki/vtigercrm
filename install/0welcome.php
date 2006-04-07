<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/0welcome.php,v 1.10 2004/08/26 11:44:30 saraj Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

//get php configuration settings.  requires elaborate parsing of phpinfo() output
ob_start();
phpinfo(INFO_GENERAL);
$string = ob_get_contents();
ob_end_clean();

$pieces = explode("<h2", $string);
$settings = array();
foreach($pieces as $val)
{
   preg_match("/<a name=\"module_([^<>]*)\">/", $val, $sub_key);
   preg_match_all("/<tr[^>]*>
									   <td[^>]*>(.*)<\/td>
									   <td[^>]*>(.*)<\/td>/Ux", $val, $sub);
   preg_match_all("/<tr[^>]*>
									   <td[^>]*>(.*)<\/td>
									   <td[^>]*>(.*)<\/td>
									   <td[^>]*>(.*)<\/td>/Ux", $val, $sub_ext);
   foreach($sub[0] as $key => $val) {
		if (preg_match("/Configuration File \(php.ini\) Path /", $val)) {
	   		$val = preg_replace("/Configuration File \(php.ini\) Path /", '', $val);
			$phpini = strip_tags($val);
	   	}
   }

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM 5.0 Alpha5 Configuration Wizard</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">


<!-- Master table -->
<table border=0 cellspacing=0 cellpadding=0 width=100%>
<tr>
	<td align=center>
	<br><br>
	<!--  Top Header -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwTopBg.gif) repeat-x;">
	<tr>
		<td><img src="install/images/cwTopLeft.gif" alt="vtiger CRM" title="vtiger CRM"></td>
		<td align=right><img src="install/images/cwTopRight.gif" alt="v5alpha5" title="v5alpha5"></td>
	</tr>
	</table>
	
	<br><br>
	
	<!-- Welcome note -->
	<table border="0" cellspacing="0" cellpadding="5" width="70%" class=small> 
	<tr>	
		<td colspan="2"><img src="install/images/cwTitle.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
	</tr>
	<tr>
		<td style="color:#333399" width="70%"><span
	style="text-alignment:justify">

Welcome!<br><br>
Thank you for trying out vtigerCRM 5 Beta  and for making it a part of your experience.<br>
This is a product of pure teamwork and passion.
Every developer and contributor has added value to vtiger either in the
form of bug reports, bug fixes, feature contributions or for that
matter, providing the hosting machine or maintenance of the forge. We
have learnt a lot working with all of you.
 We intend to create something worth
using, worth using for long.<br><br>

<i>We sincerely hope, we are able to live upto your expectations.</i>

<br>
<br>
<bold>vtiger is 100% pure Open Source. <u>You do not have to pay
anything to use the product. If you do subscribe to the support package though, you will be able
to get time bound answers to all your queries</u>.We do provide telephone support for paid customers.
There are no other hidden costs. The support subscription link is as follows :- 
<ul> http://vtiger.com/index.php?option=com_content&task=view&id=19&Itemid=37 </ul>

vtiger does not believe in any big dollar marketing campaign hence you
will not find much advertisement about vtiger from us.
Whatever you see and hear is purely due to word of mouth. We do believe that <b>YOU</b> can and will make a
difference.If you feel we have lived upto your expectations, please do consider spreading the word about vtiger by - telling a friend,  setting it up for someone, or blogging about us or in any
other manner you are comfortable with. Please find mentioned below some information about us that you could utilize
<ul>http://blogs.vtiger.com</ul> 
<ul>http://forums.vtiger.com </ul>
<ul>http://vtiger.fosslabs.com (vtigerforge)</ul> 
<ul>vtigercrm-developers@lists.vtigercrm.com (Mailing List)</ul>
<ul>Join Freenode - /join #vtiger (IRC Channel)</ul>


<br>
<br>
<br>
Again, thanks for taking out the time to try vtiger! <br><br>
<b><i>We want you to know that we really appreciate it.</i></b>

<br>
<br>
<br>
<br>











This Configuration Wizard will create vtiger CRM 5.0 Alpha5 databases and tables and configuration files you need to start. The entire process should
				take about four minutes. Click the Start button when you are ready.</span> <br><br>
				<span style="color:#555555">- vtiger CRM 5.0 Alpha5 is tested on mySQL 4.1.X, mySQL 5.0.19, PHP 5.0.19 and Apache 2.0.40. vtiger CRM 5 will not work on mysql 4.0.x versions.vtiger crm can run on a system which has xampp/lampp/wampp already installed in it. Please follow the wizard for the installation procedure regardless of the setup that you have<br>
				<br></span>
<br>
		</td>
	</tr>
	<tr>
		<td >
		<!-- License -->
			<div align=left style="color:#737373;overflow: scroll; height: 100px; width: 95%;border:1px dashed #cccccc;padding:10px">
			
			<b>License Agreement</b><br>
			This software is a collective work consisting of the following major Open Source components: <br>Apache software, MySQL server, PHP, SugarCRM, Smarty, TUTOS, phpSysinfo, SquirrelMail, and PHPMailer each licensed under a separate Open Source License.<br>
			vtiger.com is not affiliated with nor endorsed by any of the above
			providers.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<br>
			<br>
			1. Apache web server software used under the Apache License available
			at &lt;vtiger CRM Home&gt;/vtigerCRM/apache/LICENSE.txt<br>
			2. MySQL database software used under the GPL License available at
			&lt;vtiger CRM Home&gt;/vtigerCRM/mysql/README.txt <br>
			3. PHP software used under the PHP License version 3.0 available at
			&lt;vtiger CRM Home&gt;/vtigerCRM/php/license.txt&nbsp; <br>
			4. SugarCRM software used under the SugarCRM Public License SPL 1.1.2
			available at &lt;vtiger CRM Home&gt;/vtigerCRM/LICENSE_windows.txt<br>
			5. gdwin32 software used under the GNU GPL available at gdwin32
			&lt;vtiger CRM Home&gt;/vtigerCRM/gdwin32/gd-license.txt<br>
			6. nusoap software used under GNU LGPL from
			http://sourceforge.net/projects/nusoap<br>
			7. phpBB software used under GNU GPL downloaded from
			http://www.phpbb.com/support/license.php<br>
			8. TUTOS software used under GNU GPL downloaded from
			http://www.tutos.org/html/copyright.html available at &lt;vtiger CRM
			Home&gt;/vtigerCRM/modules/Calendar/TUTOS_Copyright.pdf<br>
			9. PHPMailer software used under the GNU LGPL available at &lt;vtiger
			CRM Home&gt;/vtigerCRM/modules/Emails/PHPMailer_LICENSE.txt<br>
			10. ADOdb software used under BSD license available at &lt;vtiger CRM
			Home&gt;/vtigerCRM/adodb/license.txt<br>
			11. phpSysinfo software used under GNU GPL available at &lt;vtiger CRM
			Home&gt;/vtigerCRM/modules/System/COPYING<br>
			12. feedParser software used under GNU GPL downloaded from
			http://revjim.net/code/feedParser/<br>
			13. FCKeditor software used under LGPL downloaded from
			http://www.fckeditor.net/download/default.html available at &lt;vtiger
			CRM Home&gt;/vtigerCRM/includes/FCKeditor/license.txt<br>
			14. SquirrelMail used under GNU GPL downloaded from
			http://www.squirrelmail.org/download.php available at
			http://www.squirrelmail.org/wiki/en_US/SquirrelMailGPL<br>
			15. Mailfeed used under GNU GPL downloaed from
			http://wiki.wonko.com/software/mailfeed/ available at
			http://wiki.wonko.com/software/mailfeed/#copyright <br>
			16. In addition to the above mentioned Open Source components, vtiger
			provides additional functionality, which is dual-licensed under Mozilla
			Public License (MPL 1.1) as well as the GNU Public License (GPL).<br>
			<br>
			The licenses of the Open Source components are reproduced in full below.<br>
			<br>
			&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp; Apache License<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Version 2.0, January 2004<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			http://www.apache.org/licenses/<br>
			<br>
			&nbsp;&nbsp; TERMS AND CONDITIONS FOR USE, REPRODUCTION, AND
			DISTRIBUTION<br>
			<br>
			&nbsp;&nbsp; 1. Definitions.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "License" shall mean the terms and
			conditions for use, reproduction,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and distribution as defined by Sections
			1 through 9 of this document.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "Licensor" shall mean the copyright
			owner or entity authorized by<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the copyright owner that is granting the
			License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "Legal Entity" shall mean the union of
			the acting entity and all<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; other entities that control, are
			controlled by, or are under common<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; control with that entity. For the
			purposes of this definition,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "control" means (i) the power, direct or
			indirect, to cause the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; direction or management of such entity,
			whether by contract or<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; otherwise, or (ii) ownership of fifty
			percent (50%) or more of the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; outstanding shares, or (iii) beneficial
			ownership of such entity.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "You" (or "Your") shall mean an
			individual or Legal Entity<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; exercising permissions granted by this
			License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "Source" form shall mean the preferred
			form for making modifications,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; including but not limited to software
			source code, documentation<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; source, and configuration files.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "Object" form shall mean any form
			resulting from mechanical<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; transformation or translation of a
			Source form, including but<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; not limited to compiled object code,
			generated documentation,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and conversions to other media types.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "Work" shall mean the work of
			authorship, whether in Source or<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Object form, made available under the
			License, as indicated by a<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; copyright notice that is included in or
			attached to the work<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (an example is provided in the Appendix
			below).<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "Derivative Works" shall mean any work,
			whether in Source or Object<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; form, that is based on (or derived from)
			the Work and for which the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; editorial revisions, annotations,
			elaborations, or other modifications<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; represent, as a whole, an original work
			of authorship. For the purposes<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of this License, Derivative Works shall
			not include works that remain<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; separable from, or merely link (or bind
			by name) to the interfaces of,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the Work and Derivative Works thereof.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "Contribution" shall mean any work of
			authorship, including<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the original version of the Work and any
			modifications or additions<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to that Work or Derivative Works
			thereof, that is intentionally<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; submitted to Licensor for inclusion in
			the Work by the copyright owner<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; or by an individual or Legal Entity
			authorized to submit on behalf of<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the copyright owner. For the purposes of
			this definition, "submitted"<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; means any form of electronic, verbal, or
			written communication sent<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to the Licensor or its representatives,
			including but not limited to<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; communication on electronic mailing
			lists, source code control systems,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and issue tracking systems that are
			managed by, or on behalf of, the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Licensor for the purpose of discussing
			and improving the Work, but<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; excluding communication that is
			conspicuously marked or otherwise<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; designated in writing by the copyright
			owner as "Not a Contribution."<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "Contributor" shall mean Licensor and
			any individual or Legal Entity<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; on behalf of whom a Contribution has
			been received by Licensor and<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; subsequently incorporated within the
			Work.<br>
			<br>
			&nbsp;&nbsp; 2. Grant of Copyright License. Subject to the terms and
			conditions of<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; this License, each Contributor hereby
			grants to You a perpetual,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; worldwide, non-exclusive, no-charge,
			royalty-free, irrevocable<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; copyright license to reproduce, prepare
			Derivative Works of,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; publicly display, publicly perform,
			sublicense, and distribute the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Work and such Derivative Works in Source
			or Object form.<br>
			<br>
			&nbsp;&nbsp; 3. Grant of Patent License. Subject to the terms and
			conditions of<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; this License, each Contributor hereby
			grants to You a perpetual,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; worldwide, non-exclusive, no-charge,
			royalty-free, irrevocable<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (except as stated in this section)
			patent license to make, have made,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; use, offer to sell, sell, import, and
			otherwise transfer the Work,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; where such license applies only to those
			patent claims licensable<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; by such Contributor that are necessarily
			infringed by their<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Contribution(s) alone or by combination
			of their Contribution(s)<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; with the Work to which such
			Contribution(s) was submitted. If You<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; institute patent litigation against any
			entity (including a<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; cross-claim or counterclaim in a
			lawsuit) alleging that the Work<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; or a Contribution incorporated within
			the Work constitutes direct<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; or contributory patent infringement,
			then any patent licenses<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; granted to You under this License for
			that Work shall terminate<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; as of the date such litigation is filed.<br>
			<br>
			&nbsp;&nbsp; 4. Redistribution. You may reproduce and distribute copies
			of the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Work or Derivative Works thereof in any
			medium, with or without<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; modifications, and in Source or Object
			form, provided that You<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; meet the following conditions:<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (a) You must give any other recipients
			of the Work or<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Derivative Works
			a copy of this License; and<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (b) You must cause any modified files to
			carry prominent notices<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; stating that You
			changed the files; and<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (c) You must retain, in the Source form
			of any Derivative Works<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; that You
			distribute, all copyright, patent, trademark, and<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; attribution
			notices from the Source form of the Work,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; excluding those
			notices that do not pertain to any part of<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the Derivative
			Works; and<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (d) If the Work includes a "NOTICE" text
			file as part of its<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; distribution,
			then any Derivative Works that You distribute must<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; include a
			readable copy of the attribution notices contained<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; within such
			NOTICE file, excluding those notices that do not<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; pertain to any
			part of the Derivative Works, in at least one<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of the following
			places: within a NOTICE text file distributed<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; as part of the
			Derivative Works; within the Source form or<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; documentation,
			if provided along with the Derivative Works; or,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; within a display
			generated by the Derivative Works, if and<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; wherever such
			third-party notices normally appear. The contents<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of the NOTICE
			file are for informational purposes only and<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; do not modify
			the License. You may add Your own attribution<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; notices within
			Derivative Works that You distribute, alongside<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; or as an
			addendum to the NOTICE text from the Work, provided<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; that such
			additional attribution notices cannot be construed<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; as modifying the
			License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You may add Your own copyright statement
			to Your modifications and<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; may provide additional or different
			license terms and conditions<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; for use, reproduction, or distribution
			of Your modifications, or<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; for any such Derivative Works as a
			whole, provided Your use,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; reproduction, and distribution of the
			Work otherwise complies with<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the conditions stated in this License.<br>
			<br>
			&nbsp;&nbsp; 5. Submission of Contributions. Unless You explicitly
			state otherwise,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; any Contribution intentionally submitted
			for inclusion in the Work<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; by You to the Licensor shall be under
			the terms and conditions of<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; this License, without any additional
			terms or conditions.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notwithstanding the above, nothing
			herein shall supersede or modify<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the terms of any separate license
			agreement you may have executed<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; with Licensor regarding such
			Contributions.<br>
			<br>
			&nbsp;&nbsp; 6. Trademarks. This License does not grant permission to
			use the trade<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; names, trademarks, service marks, or
			product names of the Licensor,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; except as required for reasonable and
			customary use in describing the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; origin of the Work and reproducing the
			content of the NOTICE file.<br>
			<br>
			&nbsp;&nbsp; 7. Disclaimer of Warranty. Unless required by applicable
			law or<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; agreed to in writing, Licensor provides
			the Work (and each<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Contributor provides its Contributions)
			on an "AS IS" BASIS,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WITHOUT WARRANTIES OR CONDITIONS OF ANY
			KIND, either express or<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; implied, including, without limitation,
			any warranties or conditions<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of TITLE, NON-INFRINGEMENT,
			MERCHANTABILITY, or FITNESS FOR A<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PARTICULAR PURPOSE. You are solely
			responsible for determining the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; appropriateness of using or
			redistributing the Work and assume any<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; risks associated with Your exercise of
			permissions under this License.<br>
			<br>
			&nbsp;&nbsp; 8. Limitation of Liability. In no event and under no legal
			theory,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; whether in tort (including negligence),
			contract, or otherwise,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; unless required by applicable law (such
			as deliberate and grossly<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; negligent acts) or agreed to in writing,
			shall any Contributor be<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; liable to You for damages, including any
			direct, indirect, special,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; incidental, or consequential damages of
			any character arising as a<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; result of this License or out of the use
			or inability to use the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Work (including but not limited to
			damages for loss of goodwill,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; work stoppage, computer failure or
			malfunction, or any and all<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; other commercial damages or losses),
			even if such Contributor<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; has been advised of the possibility of
			such damages.<br>
			<br>
			&nbsp;&nbsp; 9. Accepting Warranty or Additional Liability. While
			redistributing<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the Work or Derivative Works thereof,
			You may choose to offer,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and charge a fee for, acceptance of
			support, warranty, indemnity,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; or other liability obligations and/or
			rights consistent with this<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; License. However, in accepting such
			obligations, You may act only<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; on Your own behalf and on Your sole
			responsibility, not on behalf<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of any other Contributor, and only if
			You agree to indemnify,<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; defend, and hold each Contributor
			harmless for any liability<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; incurred by, or claims asserted against,
			such Contributor by reason<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of your accepting any such warranty or
			additional liability.<br>
			<br>
			&nbsp;&nbsp; END OF TERMS AND CONDITIONS<br>
			<br>
			&nbsp;&nbsp; APPENDIX: How to apply the Apache License to your work.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To apply the Apache License to your
			work, attach the following<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; boilerplate notice, with the fields
			enclosed by brackets "[]"<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; replaced with your own identifying
			information. (Don't include<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the brackets!)&nbsp; The text should be
			enclosed in the appropriate<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; comment syntax for the file format. We
			also recommend that a<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; file or class name and description of
			purpose be included on the<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; same "printed page" as the copyright
			notice for easier<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; identification within third-party
			archives.<br>
			<br>
			&nbsp;&nbsp; Copyright [yyyy] [name of copyright owner]<br>
			<br>
			&nbsp;&nbsp; Licensed under the Apache License, Version 2.0 (the
			"License");<br>
			&nbsp;&nbsp; you may not use this file except in compliance with the
			License.<br>
			&nbsp;&nbsp; You may obtain a copy of the License at<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			http://www.apache.org/licenses/LICENSE-2.0<br>
			<br>
			&nbsp;&nbsp; Unless required by applicable law or agreed to in writing,
			software<br>
			&nbsp;&nbsp; distributed under the License is distributed on an "AS IS"
			BASIS,<br>
			&nbsp;&nbsp; WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
			express or implied.<br>
			&nbsp;&nbsp; See the License for the specific language governing
			permissions and<br>
			&nbsp;&nbsp; limitations under the License.<br>
			<br>
			<br>
			<br>
			APACHE HTTP SERVER SUBCOMPONENTS: <br>
			<br>
			The Apache HTTP Server includes a number of subcomponents with<br>
			separate copyright notices and license terms. Your use of the source<br>
			code for the these subcomponents is subject to the terms and<br>
			conditions of the following licenses. <br>
			<br>
			For the mod_mime_magic component:<br>
			<br>
			/*<br>
			&nbsp;* mod_mime_magic: MIME type lookup via file magic numbers<br>
			&nbsp;* Copyright (c) 1996-1997 Cisco Systems, Inc.<br>
			&nbsp;*<br>
			&nbsp;* This software was submitted by Cisco Systems to the Apache
			Group in July<br>
			&nbsp;* 1997.&nbsp; Future revisions and derivatives of this source
			code must<br>
			&nbsp;* acknowledge Cisco Systems as the original contributor of this
			module.<br>
			&nbsp;* All other licensing and usage conditions are those of the
			Apache Group.<br>
			&nbsp;*<br>
			&nbsp;* Some of this code is derived from the free version of the file
			command<br>
			&nbsp;* originally posted to comp.sources.unix.&nbsp; Copyright info
			for that program<br>
			&nbsp;* is included below as required.<br>
			&nbsp;*
			---------------------------------------------------------------------------<br>
			&nbsp;* - Copyright (c) Ian F. Darwin, 1987. Written by Ian F. Darwin.<br>
			&nbsp;*<br>
			&nbsp;* This software is not subject to any license of the American
			Telephone and<br>
			&nbsp;* Telegraph Company or of the Regents of the University of
			California.<br>
			&nbsp;*<br>
			&nbsp;* Permission is granted to anyone to use this software for any
			purpose on any<br>
			&nbsp;* computer system, and to alter it and redistribute it freely,
			subject to<br>
			&nbsp;* the following restrictions:<br>
			&nbsp;*<br>
			&nbsp;* 1. The author is not responsible for the consequences of use of
			this<br>
			&nbsp;* software, no matter how awful, even if they arise from flaws in
			it.<br>
			&nbsp;*<br>
			&nbsp;* 2. The origin of this software must not be misrepresented,
			either by<br>
			&nbsp;* explicit claim or by omission.&nbsp; Since few users ever read
			sources, credits<br>
			&nbsp;* must appear in the documentation.<br>
			&nbsp;*<br>
			&nbsp;* 3. Altered versions must be plainly marked as such, and must
			not be<br>
			&nbsp;* misrepresented as being the original software.&nbsp; Since few
			users ever read<br>
			&nbsp;* sources, credits must appear in the documentation.<br>
			&nbsp;*<br>
			&nbsp;* 4. This notice may not be removed or altered.<br>
			&nbsp;*
			-------------------------------------------------------------------------<br>
			&nbsp;*<br>
			&nbsp;*/<br>
			<br>
			<br>
			For the&nbsp; modules\mappers\mod_imap.c component:<br>
			<br>
			&nbsp; "macmartinized" polygon code copyright 1992 by Eric Haines,
			erich@eye.com<br>
			<br>
			For the&nbsp; server\util_md5.c component:<br>
			<br>
			/************************************************************************<br>
			&nbsp;* NCSA HTTPd Server<br>
			&nbsp;* Software Development Group<br>
			&nbsp;* National Center for Supercomputing Applications<br>
			&nbsp;* University of Illinois at Urbana-Champaign<br>
			&nbsp;* 605 E. Springfield, Champaign, IL 61820<br>
			&nbsp;* httpd@ncsa.uiuc.edu<br>
			&nbsp;*<br>
			&nbsp;* Copyright&nbsp; (C)&nbsp; 1995, Board of Trustees of the
			University of Illinois<br>
			&nbsp;*<br>
			&nbsp;************************************************************************<br>
			&nbsp;*<br>
			&nbsp;* md5.c: NCSA HTTPd code which uses the md5c.c RSA Code<br>
			&nbsp;*<br>
			&nbsp;*&nbsp; Original Code Copyright (C) 1994, Jeff Hostetler,
			Spyglass, Inc.<br>
			&nbsp;*&nbsp; Portions of Content-MD5 code Copyright (C) 1993, 1994 by
			Carnegie Mellon<br>
			&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp; University (see Copyright below).<br>
			&nbsp;*&nbsp; Portions of Content-MD5 code Copyright (C) 1991 Bell
			Communications <br>
			&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp; Research, Inc. (Bellcore) (see
			Copyright below).<br>
			&nbsp;*&nbsp; Portions extracted from mpack, John G. Myers -
			jgm+@cmu.edu<br>
			&nbsp;*&nbsp; Content-MD5 Code contributed by Martin Hamilton
			(martin@net.lut.ac.uk)<br>
			&nbsp;*<br>
			&nbsp;*/<br>
			<br>
			<br>
			/* these portions extracted from mpack, John G. Myers - jgm+@cmu.edu */<br>
			/* (C) Copyright 1993,1994 by Carnegie Mellon University<br>
			&nbsp;* All Rights Reserved.<br>
			&nbsp;*<br>
			&nbsp;* Permission to use, copy, modify, distribute, and sell this
			software<br>
			&nbsp;* and its documentation for any purpose is hereby granted without<br>
			&nbsp;* fee, provided that the above copyright notice appear in all
			copies<br>
			&nbsp;* and that both that copyright notice and this permission notice<br>
			&nbsp;* appear in supporting documentation, and that the name of
			Carnegie<br>
			&nbsp;* Mellon University not be used in advertising or publicity<br>
			&nbsp;* pertaining to distribution of the software without specific,<br>
			&nbsp;* written prior permission.&nbsp; Carnegie Mellon University
			makes no<br>
			&nbsp;* representations about the suitability of this software for any<br>
			&nbsp;* purpose.&nbsp; It is provided "as is" without express or implied<br>
			&nbsp;* warranty.<br>
			&nbsp;*<br>
			&nbsp;* CARNEGIE MELLON UNIVERSITY DISCLAIMS ALL WARRANTIES WITH REGARD
			TO<br>
			&nbsp;* THIS SOFTWARE, INCLUDING ALL IMPLIED WARRANTIES OF
			MERCHANTABILITY<br>
			&nbsp;* AND FITNESS, IN NO EVENT SHALL CARNEGIE MELLON UNIVERSITY BE
			LIABLE<br>
			&nbsp;* FOR ANY SPECIAL, INDIRECT OR CONSEQUENTIAL DAMAGES OR ANY
			DAMAGES<br>
			&nbsp;* WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER
			IN<br>
			&nbsp;* AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION,
			ARISING<br>
			&nbsp;* OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS<br>
			&nbsp;* SOFTWARE.<br>
			&nbsp;*/<br>
			<br>
			/*<br>
			&nbsp;* Copyright (c) 1991 Bell Communications Research, Inc. (Bellcore)<br>
			&nbsp;*<br>
			&nbsp;* Permission to use, copy, modify, and distribute this material<br>
			&nbsp;* for any purpose and without fee is hereby granted, provided<br>
			&nbsp;* that the above copyright notice and this permission notice<br>
			&nbsp;* appear in all copies, and that the name of Bellcore not be<br>
			&nbsp;* used in advertising or publicity pertaining to this<br>
			&nbsp;* material without the specific, prior written permission<br>
			&nbsp;* of an authorized representative of Bellcore.&nbsp; BELLCORE<br>
			&nbsp;* MAKES NO REPRESENTATIONS ABOUT THE ACCURACY OR SUITABILITY<br>
			&nbsp;* OF THIS MATERIAL FOR ANY PURPOSE.&nbsp; IT IS PROVIDED "AS IS",<br>
			&nbsp;* WITHOUT ANY EXPRESS OR IMPLIED WARRANTIES.&nbsp; <br>
			&nbsp;*/<br>
			<br>
			For the&nbsp; srclib\apr\include\apr_md5.h component: <br>
			/*<br>
			&nbsp;* This is work is derived from material Copyright RSA Data
			Security, Inc.<br>
			&nbsp;*<br>
			&nbsp;* The RSA copyright statement and Licence for that original
			material is<br>
			&nbsp;* included below. This is followed by the Apache copyright
			statement and<br>
			&nbsp;* licence for the modifications made to that material.<br>
			&nbsp;*/<br>
			<br>
			/* Copyright (C) 1991-2, RSA Data Security, Inc. Created 1991. All<br>
			&nbsp;&nbsp; rights reserved.<br>
			<br>
			&nbsp;&nbsp; License to copy and use this software is granted provided
			that it<br>
			&nbsp;&nbsp; is identified as the "RSA Data Security, Inc. MD5
			Message-Digest<br>
			&nbsp;&nbsp; Algorithm" in all material mentioning or referencing this
			software<br>
			&nbsp;&nbsp; or this function.<br>
			<br>
			&nbsp;&nbsp; License is also granted to make and use derivative works
			provided<br>
			&nbsp;&nbsp; that such works are identified as "derived from the RSA
			Data<br>
			&nbsp;&nbsp; Security, Inc. MD5 Message-Digest Algorithm" in all
			material<br>
			&nbsp;&nbsp; mentioning or referencing the derived work.<br>
			<br>
			&nbsp;&nbsp; RSA Data Security, Inc. makes no representations
			concerning either<br>
			&nbsp;&nbsp; the merchantability of this software or the suitability of
			this<br>
			&nbsp;&nbsp; software for any particular purpose. It is provided "as is"<br>
			&nbsp;&nbsp; without express or implied warranty of any kind.<br>
			<br>
			&nbsp;&nbsp; These notices must be retained in any copies of any part
			of this<br>
			&nbsp;&nbsp; documentation and/or software.<br>
			&nbsp;*/<br>
			<br>
			For the&nbsp; srclib\apr\passwd\apr_md5.c component:<br>
			<br>
			/*<br>
			&nbsp;* This is work is derived from material Copyright RSA Data
			Security, Inc.<br>
			&nbsp;*<br>
			&nbsp;* The RSA copyright statement and Licence for that original
			material is<br>
			&nbsp;* included below. This is followed by the Apache copyright
			statement and<br>
			&nbsp;* licence for the modifications made to that material.<br>
			&nbsp;*/<br>
			<br>
			/* MD5C.C - RSA Data Security, Inc., MD5 message-digest algorithm<br>
			&nbsp;*/<br>
			<br>
			/* Copyright (C) 1991-2, RSA Data Security, Inc. Created 1991. All<br>
			&nbsp;&nbsp; rights reserved.<br>
			<br>
			&nbsp;&nbsp; License to copy and use this software is granted provided
			that it<br>
			&nbsp;&nbsp; is identified as the "RSA Data Security, Inc. MD5
			Message-Digest<br>
			&nbsp;&nbsp; Algorithm" in all material mentioning or referencing this
			software<br>
			&nbsp;&nbsp; or this function.<br>
			<br>
			&nbsp;&nbsp; License is also granted to make and use derivative works
			provided<br>
			&nbsp;&nbsp; that such works are identified as "derived from the RSA
			Data<br>
			&nbsp;&nbsp; Security, Inc. MD5 Message-Digest Algorithm" in all
			material<br>
			&nbsp;&nbsp; mentioning or referencing the derived work.<br>
			<br>
			&nbsp;&nbsp; RSA Data Security, Inc. makes no representations
			concerning either<br>
			&nbsp;&nbsp; the merchantability of this software or the suitability of
			this<br>
			&nbsp;&nbsp; software for any particular purpose. It is provided "as is"<br>
			&nbsp;&nbsp; without express or implied warranty of any kind.<br>
			<br>
			&nbsp;&nbsp; These notices must be retained in any copies of any part
			of this<br>
			&nbsp;&nbsp; documentation and/or software.<br>
			&nbsp;*/<br>
			/*<br>
			&nbsp;* The apr_md5_encode() routine uses much code obtained from the
			FreeBSD 3.0<br>
			&nbsp;* MD5 crypt() function, which is licenced as follows:<br>
			&nbsp;*
			----------------------------------------------------------------------------<br>
			&nbsp;* "THE BEER-WARE LICENSE" (Revision 42):<br>
			&nbsp;* &lt;phk@login.dknet.dk&gt; wrote this file.&nbsp; As long as
			you retain this notice you<br>
			&nbsp;* can do whatever you want with this stuff. If we meet some day,
			and you think<br>
			&nbsp;* this stuff is worth it, you can buy me a beer in return.&nbsp;
			Poul-Henning Kamp<br>
			&nbsp;*
			----------------------------------------------------------------------------<br>
			&nbsp;*/<br>
			<br>
			For the srclib\apr-util\crypto\apr_md4.c component:<br>
			<br>
			&nbsp;* This is derived from material copyright RSA Data Security, Inc.<br>
			&nbsp;* Their notice is reproduced below in its entirety.<br>
			&nbsp;*<br>
			&nbsp;* Copyright (C) 1991-2, RSA Data Security, Inc. Created 1991. All<br>
			&nbsp;* rights reserved.<br>
			&nbsp;*<br>
			&nbsp;* License to copy and use this software is granted provided that
			it<br>
			&nbsp;* is identified as the "RSA Data Security, Inc. MD4 Message-Digest<br>
			&nbsp;* Algorithm" in all material mentioning or referencing this
			software<br>
			&nbsp;* or this function.<br>
			&nbsp;*<br>
			&nbsp;* License is also granted to make and use derivative works
			provided<br>
			&nbsp;* that such works are identified as "derived from the RSA Data<br>
			&nbsp;* Security, Inc. MD4 Message-Digest Algorithm" in all material<br>
			&nbsp;* mentioning or referencing the derived work.<br>
			&nbsp;*<br>
			&nbsp;* RSA Data Security, Inc. makes no representations concerning
			either<br>
			&nbsp;* the merchantability of this software or the suitability of this<br>
			&nbsp;* software for any particular purpose. It is provided "as is"<br>
			&nbsp;* without express or implied warranty of any kind.<br>
			&nbsp;*<br>
			&nbsp;* These notices must be retained in any copies of any part of this<br>
			&nbsp;* documentation and/or software.<br>
			&nbsp;*/<br>
			<br>
			For the srclib\apr-util\include\apr_md4.h component:<br>
			<br>
			&nbsp;*<br>
			&nbsp;* This is derived from material copyright RSA Data Security, Inc.<br>
			&nbsp;* Their notice is reproduced below in its entirety.<br>
			&nbsp;*<br>
			&nbsp;* Copyright (C) 1991-2, RSA Data Security, Inc. Created 1991. All<br>
			&nbsp;* rights reserved.<br>
			&nbsp;*<br>
			&nbsp;* License to copy and use this software is granted provided that
			it<br>
			&nbsp;* is identified as the "RSA Data Security, Inc. MD4 Message-Digest<br>
			&nbsp;* Algorithm" in all material mentioning or referencing this
			software<br>
			&nbsp;* or this function.<br>
			&nbsp;*<br>
			&nbsp;* License is also granted to make and use derivative works
			provided<br>
			&nbsp;* that such works are identified as "derived from the RSA Data<br>
			&nbsp;* Security, Inc. MD4 Message-Digest Algorithm" in all material<br>
			&nbsp;* mentioning or referencing the derived work.<br>
			&nbsp;*<br>
			&nbsp;* RSA Data Security, Inc. makes no representations concerning
			either<br>
			&nbsp;* the merchantability of this software or the suitability of this<br>
			&nbsp;* software for any particular purpose. It is provided "as is"<br>
			&nbsp;* without express or implied warranty of any kind.<br>
			&nbsp;*<br>
			&nbsp;* These notices must be retained in any copies of any part of this<br>
			&nbsp;* documentation and/or software.<br>
			&nbsp;*/<br>
			<br>
			<br>
			For the srclib\apr-util\test\testdbm.c component:<br>
			<br>
			/* ====================================================================<br>
			&nbsp;* The Apache Software License, Version 1.1<br>
			&nbsp;*<br>
			&nbsp;* Copyright (c) 2000-2002 The Apache Software Foundation.&nbsp;
			All rights<br>
			&nbsp;* reserved.<br>
			&nbsp;*<br>
			&nbsp;* Redistribution and use in source and binary forms, with or
			without<br>
			&nbsp;* modification, are permitted provided that the following
			conditions<br>
			&nbsp;* are met:<br>
			&nbsp;*<br>
			&nbsp;* 1. Redistributions of source code must retain the above
			copyright<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; notice, this list of conditions and the
			following disclaimer.<br>
			&nbsp;*<br>
			&nbsp;* 2. Redistributions in binary form must reproduce the above
			copyright<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; notice, this list of conditions and the
			following disclaimer in<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; the documentation and/or other materials
			provided with the<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; distribution.<br>
			&nbsp;*<br>
			&nbsp;* 3. The end-user documentation included with the redistribution,<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; if any, must include the following
			acknowledgment:<br>
			&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "This product includes
			software developed by the<br>
			&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Apache Software
			Foundation (http://www.apache.org/)."<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; Alternately, this acknowledgment may appear
			in the software itself,<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; if and wherever such third-party
			acknowledgments normally appear.<br>
			&nbsp;*<br>
			&nbsp;* 4. The names "Apache" and "Apache Software Foundation" must<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; not be used to endorse or promote products
			derived from this<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; software without prior written permission.
			For written<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; permission, please contact apache@apache.org.<br>
			&nbsp;*<br>
			&nbsp;* 5. Products derived from this software may not be called
			"Apache",<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; nor may "Apache" appear in their name,
			without prior written<br>
			&nbsp;*&nbsp;&nbsp;&nbsp; permission of the Apache Software Foundation.<br>
			&nbsp;*<br>
			&nbsp;* THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED<br>
			&nbsp;* WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
			WARRANTIES<br>
			&nbsp;* OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE<br>
			&nbsp;* DISCLAIMED.&nbsp; IN NO EVENT SHALL THE APACHE SOFTWARE
			FOUNDATION OR<br>
			&nbsp;* ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,<br>
			&nbsp;* SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT<br>
			&nbsp;* LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF<br>
			&nbsp;* USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
			AND<br>
			&nbsp;* ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
			LIABILITY,<br>
			&nbsp;* OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
			OUT<br>
			&nbsp;* OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
			OF<br>
			&nbsp;* SUCH DAMAGE.<br>
			&nbsp;*
			====================================================================<br>
			&nbsp;*<br>
			&nbsp;* This software consists of voluntary contributions made by many<br>
			&nbsp;* individuals on behalf of the Apache Software Foundation.&nbsp;
			For more<br>
			&nbsp;* information on the Apache Software Foundation, please see<br>
			&nbsp;* &lt;http://www.apache.org/&gt;.<br>
			&nbsp;*<br>
			&nbsp;* This file came from the SDBM package (written by
			oz@nexus.yorku.ca).<br>
			&nbsp;* That package was under public domain. This file has been ported
			to<br>
			&nbsp;* APR, updated to ANSI C and other, newer idioms, and added to
			the Apache<br>
			&nbsp;* codebase under the above copyright and license.<br>
			&nbsp;*/<br>
			<br>
			<br>
			For the srclib\apr-util\test\testmd4.c component:<br>
			<br>
			&nbsp;*<br>
			&nbsp;* This is derived from material copyright RSA Data Security, Inc.<br>
			&nbsp;* Their notice is reproduced below in its entirety.<br>
			&nbsp;*<br>
			&nbsp;* Copyright (C) 1990-2, RSA Data Security, Inc. Created 1990. All<br>
			&nbsp;* rights reserved.<br>
			&nbsp;*<br>
			&nbsp;* RSA Data Security, Inc. makes no representations concerning
			either<br>
			&nbsp;* the merchantability of this software or the suitability of this<br>
			&nbsp;* software for any particular purpose. It is provided "as is"<br>
			&nbsp;* without express or implied warranty of any kind.<br>
			&nbsp;*<br>
			&nbsp;* These notices must be retained in any copies of any part of this<br>
			&nbsp;* documentation and/or software.<br>
			&nbsp;*/<br>
			<br>
			For the srclib\apr-util\xml\expat\conftools\install-sh component:<br>
			<br>
			#<br>
			# install - install a program, script, or datafile<br>
			# This comes from X11R5 (mit/util/scripts/install.sh).<br>
			#<br>
			# Copyright 1991 by the Massachusetts Institute of Technology<br>
			#<br>
			# Permission to use, copy, modify, distribute, and sell this software
			and its<br>
			# documentation for any purpose is hereby granted without fee, provided
			that<br>
			# the above copyright notice appear in all copies and that both that<br>
			# copyright notice and this permission notice appear in supporting<br>
			# documentation, and that the name of M.I.T. not be used in advertising
			or<br>
			# publicity pertaining to distribution of the software without specific,<br>
			# written prior permission.&nbsp; M.I.T. makes no representations about
			the<br>
			# suitability of this software for any purpose.&nbsp; It is provided
			"as is"<br>
			# without express or implied warranty.<br>
			#<br>
			<br>
			For the srclib\pcre\install-sh component:<br>
			<br>
			#<br>
			# Copyright 1991 by the Massachusetts Institute of Technology<br>
			#<br>
			# Permission to use, copy, modify, distribute, and sell this software
			and its<br>
			# documentation for any purpose is hereby granted without fee, provided
			that<br>
			# the above copyright notice appear in all copies and that both that<br>
			# copyright notice and this permission notice appear in supporting<br>
			# documentation, and that the name of M.I.T. not be used in advertising
			or<br>
			# publicity pertaining to distribution of the software without specific,<br>
			# written prior permission.&nbsp; M.I.T. makes no representations about
			the<br>
			# suitability of this software for any purpose.&nbsp; It is provided
			"as is"<br>
			# without express or implied warranty.<br>
			<br>
			For the pcre component:<br>
			<br>
			PCRE LICENCE<br>
			------------<br>
			<br>
			PCRE is a library of functions to support regular expressions whose
			syntax<br>
			and semantics are as close as possible to those of the Perl 5 language.<br>
			<br>
			Written by: Philip Hazel &lt;ph10@cam.ac.uk&gt;<br>
			<br>
			University of Cambridge Computing Service,<br>
			Cambridge, England. Phone: +44 1223 334714.<br>
			<br>
			Copyright (c) 1997-2001 University of Cambridge<br>
			<br>
			Permission is granted to anyone to use this software for any purpose on
			any<br>
			computer system, and to redistribute it freely, subject to the following<br>
			restrictions:<br>
			<br>
			1. This software is distributed in the hope that it will be useful,<br>
			&nbsp;&nbsp; but WITHOUT ANY WARRANTY; without even the implied
			warranty of<br>
			&nbsp;&nbsp; MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.<br>
			<br>
			2. The origin of this software must not be misrepresented, either by<br>
			&nbsp;&nbsp; explicit claim or by omission. In practice, this means
			that if you use<br>
			&nbsp;&nbsp; PCRE in software which you distribute to others,
			commercially or<br>
			&nbsp;&nbsp; otherwise, you must put a sentence like this<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp; Regular expression support is provided by the
			PCRE library package,<br>
			&nbsp;&nbsp;&nbsp;&nbsp; which is open source software, written by
			Philip Hazel, and copyright<br>
			&nbsp;&nbsp;&nbsp;&nbsp; by the University of Cambridge, England.<br>
			<br>
			&nbsp;&nbsp; somewhere reasonably visible in your documentation and in
			any relevant<br>
			&nbsp;&nbsp; files or online help data or similar. A reference to the
			ftp site for<br>
			&nbsp;&nbsp; the source, that is, to<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;
			ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/<br>
			<br>
			&nbsp;&nbsp; should also be given in the documentation.<br>
			<br>
			3. Altered versions must be plainly marked as such, and must not be<br>
			&nbsp;&nbsp; misrepresented as being the original software.<br>
			<br>
			4. If PCRE is embedded in any software that is released under the GNU<br>
			&nbsp;&nbsp; General Purpose Licence (GPL), or Lesser General Purpose
			Licence (LGPL),<br>
			&nbsp;&nbsp; then the terms of that licence shall supersede any
			condition above with<br>
			&nbsp;&nbsp; which it is incompatible.<br>
			<br>
			The documentation for PCRE, supplied in the "doc" directory, is
			distributed<br>
			under the same terms as the software itself.<br>
			<br>
			End PCRE LICENCE<br>
			<br>
			<br>
			For the test\zb.c component:<br>
			<br>
			/*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			ZeusBench V1.01<br>
			&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp; ===============<br>
			<br>
			This program is Copyright (C) Zeus Technology Limited 1996.<br>
			<br>
			This program may be used and copied freely providing this copyright
			notice<br>
			is not removed.<br>
			<br>
			This software is provided "as is" and any express or implied waranties,
			<br>
			including but not limited to, the implied warranties of merchantability
			and<br>
			fitness for a particular purpose are disclaimed.&nbsp; In no event
			shall <br>
			Zeus Technology Ltd. be liable for any direct, indirect, incidental,
			special, <br>
			exemplary, or consequential damaged (including, but not limited to, <br>
			procurement of substitute good or services; loss of use, data, or
			profits;<br>
			or business interruption) however caused and on theory of
			liability.&nbsp; Whether<br>
			in contract, strict liability or tort (including negligence or
			otherwise) <br>
			arising in any way out of the use of this software, even if advised of
			the<br>
			possibility of such damage.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp; Written by Adam Twiss (adam@zeus.co.uk).&nbsp;
			March 1996<br>
			<br>
			Thanks to the following people for their input:<br>
			&nbsp; Mike Belshe (mbelshe@netscape.com) <br>
			&nbsp; Michael Campanella (campanella@stevms.enet.dec.com)<br>
			<br>
			*/<br>
			<br>
			For the expat xml parser component:<br>
			<br>
			Copyright (c) 1998, 1999, 2000 Thai Open Source Software Center Ltd<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			and Clark Cooper<br>
			<br>
			Permission is hereby granted, free of charge, to any person obtaining<br>
			a copy of this software and associated documentation files (the<br>
			"Software"), to deal in the Software without restriction, including<br>
			without limitation the rights to use, copy, modify, merge, publish,<br>
			distribute, sublicense, and/or sell copies of the Software, and to<br>
			permit persons to whom the Software is furnished to do so, subject to<br>
			the following conditions:<br>
			&nbsp;&nbsp;&nbsp; <br>
			The above copyright notice and this permission notice shall be included<br>
			in all copies or substantial portions of the Software.<br>
			&nbsp;&nbsp;&nbsp; <br>
			THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,<br>
			EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF<br>
			MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.<br>
			IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY<br>
			CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,<br>
			TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE<br>
			SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.<br>
			<br>
			====================================================================<br>
			<br>
			For the mod_deflate zlib compression component:<br>
			<br>
			&nbsp;(C) 1995-2002 Jean-loup Gailly and Mark Adler<br>
			<br>
			&nbsp; This software is provided 'as-is', without any express or implied<br>
			&nbsp; warranty.&nbsp; In no event will the authors be held liable for
			any damages<br>
			&nbsp; arising from the use of this software.<br>
			<br>
			&nbsp; Permission is granted to anyone to use this software for any
			purpose,<br>
			&nbsp; including commercial applications, and to alter it and
			redistribute it<br>
			&nbsp; freely, subject to the following restrictions:<br>
			<br>
			&nbsp; 1. The origin of this software must not be misrepresented; you
			must not<br>
			&nbsp;&nbsp;&nbsp;&nbsp; claim that you wrote the original software. If
			you use this software<br>
			&nbsp;&nbsp;&nbsp;&nbsp; in a product, an acknowledgment in the product
			documentation would be<br>
			&nbsp;&nbsp;&nbsp;&nbsp; appreciated but is not required.<br>
			&nbsp; 2. Altered source versions must be plainly marked as such, and
			must not be<br>
			&nbsp;&nbsp;&nbsp;&nbsp; misrepresented as being the original software.<br>
			&nbsp; 3. This notice may not be removed or altered from any source
			distribution.<br>
			<br>
			&nbsp; Jean-loup Gailly&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mark
			Adler<br>
			&nbsp;
			jloup@gzip.org&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			madler@alumni.caltech.edu<br>
			<br>
			-------------------------------------------------------------------------------------<br>
			<br>
			This is a release of MySQL, a GPL (free) SQL database server (more<br>
			licence information in the PUBLIC file and in the reference manual).<br>
			<br>
			Please read the "Upgrading from..." section in the manual first, if you
			are<br>
			migrating from older versions of MySQL!<br>
			<br>
			The latest information about MySQL can be found at:<br>
			http://www.mysql.com<br>
			<br>
			To see what it can do take a look at the features section in the<br>
			manual.<br>
			<br>
			For installation instructions see the Installation chapter in the<br>
			manual.<br>
			<br>
			For future plans see the TODO appendix in the manual.<br>
			<br>
			New features/bug fixes history is in the news appendix in the manual.<br>
			<br>
			For the currently known bugs/misfeatures (known errors) see the bugs<br>
			appendix in the manual.<br>
			<br>
			For examples of SQL and benchmarking information see the bench<br>
			directory.<br>
			<br>
			The manual mentioned above can be found in the Docs directory. The<br>
			manual is available in the following formats: as plain ASCII text in<br>
			Docs/manual.txt, in HTML format in Docs/manual_toc.html, as GNU Info in<br>
			Docs/mysql.info and as PostScript in Docs/manual.ps.<br>
			<br>
			MySQL is brought to you by the MySQL team at MySQL AB<br>
			<br>
			For a list of developers and other contributors, see the Credits
			appendix<br>
			in the manual.<br>
			<br>
			************************************************************<br>
			<br>
			IMPORTANT:<br>
			<br>
			Send bug (error) reports, questions and comments to the mailing list<br>
			at mysql@lists.mysql.com<br>
			<br>
			Please use the 'mysqlbug' script when posting bug reports or questions<br>
			about MySQL. mysqlbug will gather some information about your system<br>
			and start your editor with a form in which you can describe your<br>
			problem. Bug reports might be silently ignored by the MySQL<br>
			maintainers if there is not a good reason included in the report as to<br>
			why mysqlbug has not been used. A report that says 'MySQL does not<br>
			work for me. Why?' is not considered a valid bug report.<br>
			<br>
			The mysqlbug script can be found in the 'scripts' directory of the<br>
			distribution, that is '&lt;where-you-installed-mysql&gt;/scripts'.<br>
			<br>
			<br>
			--------------------------------------------------------------------------------<br>
			<br>
			-------------------------------------------------------------------- <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			The PHP License, version 3.0<br>
			Copyright (c) 1999 - 2002 The PHP Group. All rights reserved.<br>
			-------------------------------------------------------------------- <br>
			<br>
			Redistribution and use in source and binary forms, with or without<br>
			modification, is permitted provided that the following conditions<br>
			are met:<br>
			<br>
			&nbsp; 1. Redistributions of source code must retain the above copyright<br>
			&nbsp;&nbsp;&nbsp;&nbsp; notice, this list of conditions and the
			following disclaimer.<br>
			&nbsp;<br>
			&nbsp; 2. Redistributions in binary form must reproduce the above
			copyright<br>
			&nbsp;&nbsp;&nbsp;&nbsp; notice, this list of conditions and the
			following disclaimer in<br>
			&nbsp;&nbsp;&nbsp;&nbsp; the documentation and/or other materials
			provided with the<br>
			&nbsp;&nbsp;&nbsp;&nbsp; distribution.<br>
			&nbsp;<br>
			&nbsp; 3. The name "PHP" must not be used to endorse or promote products<br>
			&nbsp;&nbsp;&nbsp;&nbsp; derived from this software without prior
			written permission. For<br>
			&nbsp;&nbsp;&nbsp;&nbsp; written permission, please contact
			group@php.net.<br>
			&nbsp; <br>
			&nbsp; 4. Products derived from this software may not be called "PHP",
			nor<br>
			&nbsp;&nbsp;&nbsp;&nbsp; may "PHP" appear in their name, without prior
			written permission<br>
			&nbsp;&nbsp;&nbsp;&nbsp; from group@php.net.&nbsp; You may indicate
			that your software works in<br>
			&nbsp;&nbsp;&nbsp;&nbsp; conjunction with PHP by saying "Foo for PHP"
			instead of calling<br>
			&nbsp;&nbsp;&nbsp;&nbsp; it "PHP Foo" or "phpfoo"<br>
			&nbsp;<br>
			&nbsp; 5. The PHP Group may publish revised and/or new versions of the<br>
			&nbsp;&nbsp;&nbsp;&nbsp; license from time to time. Each version will
			be given a<br>
			&nbsp;&nbsp;&nbsp;&nbsp; distinguishing version number.<br>
			&nbsp;&nbsp;&nbsp;&nbsp; Once covered code has been published under a
			particular version<br>
			&nbsp;&nbsp;&nbsp;&nbsp; of the license, you may always continue to use
			it under the terms<br>
			&nbsp;&nbsp;&nbsp;&nbsp; of that version. You may also choose to use
			such covered code<br>
			&nbsp;&nbsp;&nbsp;&nbsp; under the terms of any subsequent version of
			the license<br>
			&nbsp;&nbsp;&nbsp;&nbsp; published by the PHP Group. No one other than
			the PHP Group has<br>
			&nbsp;&nbsp;&nbsp;&nbsp; the right to modify the terms applicable to
			covered code created<br>
			&nbsp;&nbsp;&nbsp;&nbsp; under this License.<br>
			<br>
			&nbsp; 6. Redistributions of any form whatsoever must retain the
			following<br>
			&nbsp;&nbsp;&nbsp;&nbsp; acknowledgment:<br>
			&nbsp;&nbsp;&nbsp;&nbsp; "This product includes PHP, freely available
			from<br>
			&nbsp;&nbsp;&nbsp;&nbsp; &lt;http://www.php.net/&gt;".<br>
			<br>
			THIS SOFTWARE IS PROVIDED BY THE PHP DEVELOPMENT TEAM ``AS IS'' AND <br>
			ANY EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,<br>
			THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A <br>
			PARTICULAR PURPOSE ARE DISCLAIMED.&nbsp; IN NO EVENT SHALL THE PHP<br>
			DEVELOPMENT TEAM OR ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, <br>
			INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES <br>
			(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR <br>
			SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)<br>
			HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,<br>
			STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)<br>
			ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED<br>
			OF THE POSSIBILITY OF SUCH DAMAGE.<br>
			<br>
			-------------------------------------------------------------------- <br>
			<br>
			This software consists of voluntary contributions made by many<br>
			individuals on behalf of the PHP Group.<br>
			<br>
			The PHP Group can be contacted via Email at group@php.net.<br>
			<br>
			For more information on the PHP Group and the PHP project, <br>
			please see &lt;http://www.php.net&gt;.<br>
			<br>
			This product includes the Zend Engine, freely available at<br>
			&lt;http://www.zend.com&gt;.<br>
			<br>
			-----------------------------------------------------------------------<br>
			<br>
			<br>
			SUGARCRM PUBLIC LICENSE<br>
			Version 1.1.2<br>
			<br>
			1. Definitions.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.0.1. "Commercial Use" means
			distribution or otherwise making the Covered Code available to a third
			party.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.1. ''Contributor'' means each entity
			that creates or contributes to the creation of Modifications.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.2. ''Contributor Version'' means the
			combination of the Original Code, prior Modifications used by a
			Contributor, and the Modifications made by that particular Contributor.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.3. ''Covered Code'' means the Original
			Code or Modifications or the combination of the Original Code and
			Modifications, in each case including portions thereof.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.4. ''Electronic Distribution
			Mechanism'' means a mechanism generally accepted in the software
			development community for the electronic transfer of data.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.5. ''Executable'' means Covered Code
			in any form other than Source Code.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.6. ''Initial Developer'' means the
			individual or entity identified as the Initial Developer in the Source
			Code notice required by Exhibit A.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.7. ''Larger Work'' means a work which
			combines Covered Code or portions thereof with code not governed by the
			terms of this License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.8. ''License'' means this document.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.8.1. "Licensable" means having the
			right to grant, to the maximum extent possible, whether at the time of
			the initial grant or subsequently acquired, any and all of the rights
			conveyed herein.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.9. ''Modifications'' means any
			addition to or deletion from the substance or structure of either the
			Original Code or any previous Modifications. When Covered Code is
			released as a series of files, a Modification is:<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A.
			Any addition to or deletion from the contents of a file containing
			Original Code or previous Modifications.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; B.
			Any new file that contains any part of the Original Code or previous
			Modifications.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.10. ''Original Code'' means Source
			Code of computer software code which is described in the Source Code
			notice required by Exhibit A as Original Code, and which, at the time
			of its release under this License is not already Covered Code governed
			by this License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.10.1. "Patent Claims" means any patent
			claim(s), now owned or hereafter acquired, including without
			limitation,&nbsp; method, process, and apparatus claims, in any patent
			Licensable by grantor.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.11. ''Source Code'' means the
			preferred form of the Covered Code for making modifications to it,
			including all modules it contains, plus any associated interface
			definition files, scripts used to control compilation and installation
			of an Executable, or source code differential comparisons against
			either the Original Code or another well known, available Covered Code
			of the Contributor's choice. The Source Code can be in a compressed or
			archival form, provided the appropriate decompression or de-archiving
			software is widely available for no charge.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.12. "You'' (or "Your")&nbsp; means an
			individual or a legal entity exercising rights under, and complying
			with all of the terms of, this License or a future version of this
			License issued under Section 6.1. For legal entities, "You'' includes
			any entity which controls, is controlled by, or is under common control
			with You. For purposes of this definition, "control'' means (a) the
			power, direct or indirect, to cause the direction or management of such
			entity, whether by contract or otherwise, or (b) ownership of more than
			fifty percent (50%) of the outstanding shares or beneficial ownership
			of such entity.<br>
			<br>
			2. Source Code License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2.1. The Initial Developer Grant.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The Initial Developer hereby grants You
			a world-wide, royalty-free, non-exclusive license, subject to third
			party intellectual property claims:<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			(a)&nbsp; under intellectual property rights (other than patent or
			trademark) Licensable by Initial Developer to use, reproduce, modify,
			display, perform, sublicense and distribute the Original Code (or
			portions thereof) with or without Modifications, and/or as part of a
			Larger Work; and<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (b)
			under Patents Claims infringed by the making, using or selling of
			Original Code, to make, have made, use, practice, sell, and offer for
			sale, and/or otherwise dispose of the Original Code (or portions
			thereof).<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			(c) the licenses granted in this Section 2.1(a) and (b) are effective
			on the date Initial Developer first distributes Original Code under the
			terms of this License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (d)
			Notwithstanding Section 2.1(b) above, no patent license is granted: 1)
			for code that You delete from the Original Code; 2) separate from the
			Original Code;&nbsp; or 3) for infringements caused by: i) the
			modification of the Original Code or ii) the combination of the
			Original Code with other software or devices.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2.2. Contributor Grant.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Subject to third party intellectual
			property claims, each Contributor hereby grants You a world-wide,
			royalty-free, non-exclusive license<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			(a)&nbsp; under intellectual property rights (other than patent or
			trademark) Licensable by Contributor, to use, reproduce, modify,
			display, perform, sublicense and distribute the Modifications created
			by such Contributor (or portions thereof) either on an unmodified
			basis, with other Modifications, as Covered Code and/or as part of a
			Larger Work; and<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (b)
			under Patent Claims infringed by the making, using, or selling of&nbsp;
			Modifications made by that Contributor either alone and/or in
			combination with its Contributor Version (or portions of such
			combination), to make, use, sell, offer for sale, have made, and/or
			otherwise dispose of: 1) Modifications made by that Contributor (or
			portions thereof); and 2) the combination of&nbsp; Modifications made
			by that Contributor with its Contributor Version (or portions of such
			combination).<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (c)
			the licenses granted in Sections 2.2(a) and 2.2(b) are effective on the
			date Contributor first makes Commercial Use of the Covered Code.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			(d)&nbsp;&nbsp;&nbsp; Notwithstanding Section 2.2(b) above, no patent
			license is granted: 1) for any code that Contributor has deleted from
			the Contributor Version; 2)&nbsp; separate from the Contributor
			Version;&nbsp; 3)&nbsp; for infringements caused by: i) third party
			modifications of Contributor Version or ii)&nbsp; the combination of
			Modifications made by that Contributor with other software&nbsp;
			(except as part of the Contributor Version) or other devices; or 4)
			under Patent Claims infringed by Covered Code in the absence of
			Modifications made by that Contributor.<br>
			<br>
			<br>
			3. Distribution Obligations.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3.1. Application of License.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The Modifications which You create or to
			which You contribute are governed by the terms of this License,
			including without limitation Section 2.2. The Source Code version of
			Covered Code may be distributed only under the terms of this License or
			a future version of this License released under Section 6.1, and You
			must include a copy of this License with every copy of the Source Code
			You distribute. You may not offer or impose any terms on any Source
			Code version that alters or restricts the applicable version of this
			License or the recipients' rights hereunder. However, You may include
			an additional document offering the additional rights described in
			Section 3.5.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3.2. Availability of Source Code.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Any Modification which You create or to
			which You contribute must be made available in Source Code form under
			the terms of this License either on the same media as an Executable
			version or via an accepted Electronic Distribution Mechanism to anyone
			to whom you made an Executable version available; and if made available
			via Electronic Distribution Mechanism, must remain available for at
			least twelve (12) months after the date it initially became available,
			or at least six (6) months after a subsequent version of that
			particular Modification has been made available to such recipients. You
			are responsible for ensuring that the Source Code version remains
			available even if the Electronic Distribution Mechanism is maintained
			by a third party.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3.3. Description of Modifications.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You must cause all Covered Code to which
			You contribute to contain a file documenting the changes You made to
			create that Covered Code and the date of any change. You must include a
			prominent statement that the Modification is derived, directly or
			indirectly, from Original Code provided by the Initial Developer and
			including the name of the Initial Developer in (a) the Source Code, and
			(b) in any notice in an Executable version or related documentation in
			which You describe the origin or ownership of the Covered Code.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3.4. Intellectual Property Matters<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (a)
			Third Party Claims.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If
			Contributor has knowledge that a license under a third party's
			intellectual property rights is required to exercise the rights granted
			by such Contributor under Sections 2.1 or 2.2, Contributor must include
			a text file with the Source Code distribution titled "LEGAL'' which
			describes the claim and the party making the claim in sufficient detail
			that a recipient will know whom to contact. If Contributor obtains such
			knowledge after the Modification is made available as described in
			Section 3.2, Contributor shall promptly modify the LEGAL file in all
			copies Contributor makes available thereafter and shall take other
			steps (such as notifying appropriate mailing lists or newsgroups)
			reasonably calculated to inform those who received the Covered Code
			that new knowledge has been obtained.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (b)
			Contributor APIs.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If
			Contributor's Modifications include an application programming
			interface and Contributor has knowledge of patent licenses which are
			reasonably necessary to implement that API, Contributor must also
			include this information in the LEGAL file.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			(c)&nbsp;&nbsp;&nbsp; Representations.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Contributor represents that, except as disclosed pursuant to Section
			3.4(a) above, Contributor believes that Contributor's Modifications are
			Contributor's original creation(s) and/or Contributor has sufficient
			rights to grant the rights conveyed by this License.<br>
			<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3.5. Required Notices.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You must duplicate the notice in Exhibit
			A in each file of the Source Code.&nbsp; If it is not possible to put
			such notice in a particular Source Code file due to its structure, then
			You must include such notice in a location (such as a relevant
			directory) where a user would be likely to look for such a
			notice.&nbsp; If You created one or more Modification(s) You may add
			your name as a Contributor to the notice described in Exhibit A.&nbsp;
			You must also duplicate this License in any documentation for the
			Source Code where You describe recipients' rights or ownership rights
			relating to Covered Code.&nbsp; You may choose to offer, and to charge
			a fee for, warranty, support, indemnity or liability obligations to one
			or more recipients of Covered Code. However, You may do so only on Your
			own behalf, and not on behalf of the Initial Developer or any
			Contributor. You must make it absolutely clear than any such warranty,
			support, indemnity or liability obligation is offered by You alone, and
			You hereby agree to indemnify the Initial Developer and every
			Contributor for any liability incurred by the Initial Developer or such
			Contributor as a result of warranty, support, indemnity or liability
			terms You offer.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3.6. Distribution of Executable Versions.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You may distribute Covered Code in
			Executable form only if the requirements of Section 3.1-3.5 have been
			met for that Covered Code, and if You include a notice stating that the
			Source Code version of the Covered Code is available under the terms of
			this License, including a description of how and where You have
			fulfilled the obligations of Section 3.2. The notice must be
			conspicuously included in any notice in an Executable version, related
			documentation or collateral in which You describe recipients' rights
			relating to the Covered Code. You may distribute the Executable version
			of Covered Code or ownership rights under a license of Your choice,
			which may contain terms different from this License, provided that You
			are in compliance with the terms of this License and that the license
			for the Executable version does not attempt to limit or alter the
			recipient's rights in the Source Code version from the rights set forth
			in this License. If You distribute the Executable version under a
			different license You must make it absolutely clear that any terms
			which differ from this License are offered by You alone, not by the
			Initial Developer or any Contributor. You hereby agree to indemnify the
			Initial Developer and every Contributor for any liability incurred by
			the Initial Developer or such Contributor as a result of any such terms
			You offer.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3.7. Larger Works.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You may create a Larger Work by
			combining Covered Code with other code not governed by the terms of
			this License and distribute the Larger Work as a single product. In
			such a case, You must make sure the requirements of this License are
			fulfilled for the Covered Code.<br>
			<br>
			4. Inability to Comply Due to Statute or Regulation.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If it is impossible for You to comply
			with any of the terms of this License with respect to some or all of
			the Covered Code due to statute, judicial order, or regulation then You
			must: (a) comply with the terms of this License to the maximum extent
			possible; and (b) describe the limitations and the code they affect.
			Such description must be included in the LEGAL file described in
			Section 3.4 and must be included with all distributions of the Source
			Code. Except to the extent prohibited by statute or regulation, such
			description must be sufficiently detailed for a recipient of ordinary
			skill to be able to understand it.<br>
			<br>
			5. Application of this License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; This License applies to code to which
			the Initial Developer has attached the notice in Exhibit A and to
			related Covered Code.<br>
			<br>
			6. Versions of the License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 6.1. New Versions.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SugarCRM Inc. (''SugarCRM'') may publish
			revised and/or new versions of the License from time to time. Each
			version will be given a distinguishing version number.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 6.2. Effect of New Versions.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Once Covered Code has been published
			under a particular version of the License, You may always continue to
			use it under the terms of that version. You may also choose to use such
			Covered Code under the terms of any subsequent version of the License
			published by SugarCRM. No one other than SugarCRM has the right to
			modify the terms applicable to Covered Code created under this License.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 6.3. Derivative Works.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If You create or use a modified version
			of this License (which you may only do in order to apply it to code
			which is not already Covered Code governed by this License), You must
			(a) rename Your license so that the phrases ''SugarCRM'', ''SPL'' or
			any confusingly similar phrase do not appear in your license (except to
			note that your license differs from this License) and (b) otherwise
			make it clear that Your version of the license contains terms which
			differ from the SugarCRM Public License. (Filling in the name of the
			Initial Developer, Original Code or Contributor in the notice described
			in Exhibit A shall not of themselves be deemed to be modifications of
			this License.)<br>
			<br>
			7. DISCLAIMER OF WARRANTY.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; COVERED CODE IS PROVIDED UNDER THIS
			LICENSE ON AN "AS IS'' BASIS, WITHOUT WARRANTY OF ANY KIND, EITHER
			EXPRESSED OR IMPLIED, INCLUDING, WITHOUT LIMITATION, WARRANTIES THAT
			THE COVERED CODE IS FREE OF DEFECTS, MERCHANTABLE, FIT FOR A PARTICULAR
			PURPOSE OR NON-INFRINGING. THE ENTIRE RISK AS TO THE QUALITY AND
			PERFORMANCE OF THE COVERED CODE IS WITH YOU. SHOULD ANY COVERED CODE
			PROVE DEFECTIVE IN ANY RESPECT, YOU (NOT THE INITIAL DEVELOPER OR ANY
			OTHER CONTRIBUTOR) ASSUME THE COST OF ANY NECESSARY SERVICING, REPAIR
			OR CORRECTION. THIS DISCLAIMER OF WARRANTY CONSTITUTES AN ESSENTIAL
			PART OF THIS LICENSE. NO USE OF ANY COVERED CODE IS AUTHORIZED
			HEREUNDER EXCEPT UNDER THIS DISCLAIMER.<br>
			<br>
			8. TERMINATION.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8.1.&nbsp; This License and the rights
			granted hereunder will terminate automatically if You fail to comply
			with terms herein and fail to cure such breach within 30 days of
			becoming aware of the breach. All sublicenses to the Covered Code which
			are properly granted shall survive any termination of this License.
			Provisions which, by their nature, must remain in effect beyond the
			termination of this License shall survive.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8.2.&nbsp; If You initiate litigation by
			asserting a patent infringement claim (excluding declatory judgment
			actions) against Initial Developer or a Contributor (the Initial
			Developer or Contributor against whom You file such action is referred
			to as "Participant")&nbsp; alleging that:<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (a)&nbsp; such Participant's Contributor
			Version directly or indirectly infringes any patent, then any and all
			rights granted by such Participant to You under Sections 2.1 and/or 2.2
			of this License shall, upon 60 days notice from Participant terminate
			prospectively, unless if within 60 days after receipt of notice You
			either: (i)&nbsp; agree in writing to pay Participant a mutually
			agreeable reasonable royalty for Your past and future use of
			Modifications made by such Participant, or (ii) withdraw Your
			litigation claim with respect to the Contributor Version against such
			Participant.&nbsp; If within 60 days of notice, a reasonable royalty
			and payment arrangement are not mutually agreed upon in writing by the
			parties or the litigation claim is not withdrawn, the rights granted by
			Participant to You under Sections 2.1 and/or 2.2 automatically
			terminate at the expiration of the 60 day notice period specified above.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (b)&nbsp; any software, hardware, or
			device, other than such Participant's Contributor Version, directly or
			indirectly infringes any patent, then any rights granted to You by such
			Participant under Sections 2.1(b) and 2.2(b) are revoked effective as
			of the date You first made, used, sold, distributed, or had made,
			Modifications made by that Participant.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8.3.&nbsp; If You assert a patent
			infringement claim against Participant alleging that such Participant's
			Contributor Version directly or indirectly infringes any patent where
			such claim is resolved (such as by license or settlement) prior to the
			initiation of patent infringement litigation, then the reasonable value
			of the licenses granted by such Participant under Sections 2.1 or 2.2
			shall be taken into account in determining the amount or value of any
			payment or license.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8.4.&nbsp; In the event of termination
			under Sections 8.1 or 8.2 above,&nbsp; all end user license agreements
			(excluding distributors and resellers) which have been validly granted
			by You or any distributor hereunder prior to termination shall survive
			termination.<br>
			<br>
			9. LIMITATION OF LIABILITY.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UNDER NO CIRCUMSTANCES AND UNDER NO
			LEGAL THEORY, WHETHER TORT (INCLUDING NEGLIGENCE), CONTRACT, OR
			OTHERWISE, SHALL YOU, THE INITIAL DEVELOPER, ANY OTHER CONTRIBUTOR, OR
			ANY DISTRIBUTOR OF COVERED CODE, OR ANY SUPPLIER OF ANY OF SUCH
			PARTIES, BE LIABLE TO ANY PERSON FOR ANY INDIRECT, SPECIAL, INCIDENTAL,
			OR CONSEQUENTIAL DAMAGES OF ANY CHARACTER INCLUDING, WITHOUT
			LIMITATION, DAMAGES FOR LOSS OF GOODWILL, WORK STOPPAGE, COMPUTER
			FAILURE OR MALFUNCTION, OR ANY AND ALL OTHER COMMERCIAL DAMAGES OR
			LOSSES, EVEN IF SUCH PARTY SHALL HAVE BEEN INFORMED OF THE POSSIBILITY
			OF SUCH DAMAGES. THIS LIMITATION OF LIABILITY SHALL NOT APPLY TO
			LIABILITY FOR DEATH OR PERSONAL INJURY RESULTING FROM SUCH PARTY'S
			NEGLIGENCE TO THE EXTENT APPLICABLE LAW PROHIBITS SUCH LIMITATION. SOME
			JURISDICTIONS DO NOT ALLOW THE EXCLUSION OR LIMITATION OF INCIDENTAL OR
			CONSEQUENTIAL DAMAGES, SO THIS EXCLUSION AND LIMITATION MAY NOT APPLY
			TO YOU.<br>
			<br>
			10. U.S. GOVERNMENT END USERS.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The Covered Code is a ''commercial
			item,'' as that term is defined in 48 C.F.R. 2.101 (Oct. 1995),
			consisting of ''commercial computer software'' and ''commercial
			computer software documentation,'' as such terms are used in 48 C.F.R.
			12.212 (Sept. 1995). Consistent with 48 C.F.R. 12.212 and 48 C.F.R.
			227.7202-1 through 227.7202-4 (June 1995), all U.S. Government End
			Users acquire Covered Code with only those rights set forth herein.<br>
			<br>
			11. MISCELLANEOUS.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; This License represents the complete
			agreement concerning subject matter hereof. If any provision of this
			License is held to be unenforceable, such provision shall be reformed
			only to the extent necessary to make it enforceable. This License shall
			be governed by California law provisions (except to the extent
			applicable law, if any, provides otherwise), excluding its
			conflict-of-law provisions. With respect to disputes in which at least
			one party is a citizen of, or an entity chartered or registered to do
			business in the United States of America, any litigation relating to
			this License shall be subject to the jurisdiction of the Federal Courts
			of the Northern District of California, with venue lying in Santa Clara
			County, California, with the losing party responsible for costs,
			including without limitation, court costs and reasonable attorneys'
			fees and expenses. The application of the United Nations Convention on
			Contracts for the International Sale of Goods is expressly excluded.
			Any law or regulation which provides that the language of a contract
			shall be construed against the drafter shall not apply to this License.<br>
			<br>
			12. RESPONSIBILITY FOR CLAIMS.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; As between Initial Developer and the
			Contributors, each party is responsible for claims and damages arising,
			directly or indirectly, out of its utilization of rights under this
			License and You agree to work with Initial Developer and Contributors
			to distribute such responsibility on an equitable basis. Nothing herein
			is intended or shall be deemed to constitute any admission of liability.<br>
			<br>
			13. MULTIPLE-LICENSED CODE.<br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Initial Developer may designate portions
			of the Covered Code as “Multiple-Licensed”.&nbsp; “Multiple-Licensed”
			means that the Initial Developer permits you to utilize portions of the
			Covered Code under Your choice of the SPL or the alternative licenses,
			if any, specified by the Initial Developer in the file described in
			Exhibit A.<br>
			<br>
			SugarCRM Public License 1.1.2 - Exhibit A<br>
			<br>
			The contents of this file are subject to the SugarCRM Public License
			Version 1.1.2<br>
			("License"); You may not use this file except in compliance with the<br>
			License. You may obtain a copy of the License at
			http://www.sugarcrm.com/SPL<br>
			Software distributed under the License is distributed on an "AS IS"
			basis,<br>
			WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
			License for<br>
			the specific language governing rights and limitations under the
			License.<br>
			<br>
			The Original Code is: SugarCRM Open Source<br>
			<br>
			The Initial Developer of the Original Code is SugarCRM, Inc.<br>
			Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;<br>
			All Rights Reserved.<br>
			Contributor(s): ______________________________________.<br>
			<br>
			[NOTE: The text of this Exhibit A may differ slightly from the text of
			the notices in the Source Code files of the Original Code. You should
			use the text of this Exhibit A rather than the text found in the
			Original Code Source Code for Your Modifications.]<br>
			<br>
			SugarCRM Public License 1.1.2 - Exihibit B<br>
			<br>
			Additional Terms applicable to the SugarCRM Public License.<br>
			<br>
			I. Effect.<br>
			These additional terms described in this SugarCRM Public License –
			Additional Terms shall apply to the Covered Code under this License.<br>
			<br>
			II. SugarCRM and logo.<br>
			This License does not grant any rights to use the trademarks "SugarCRM"
			and the "SugarCRM" logos even if such marks are included in the
			Original Code or Modifications. <br>
			<br>
			<br>
			<br>
			The modifications made to the code of SugarCRM are available under SPL
			and the new additions made by vtiger are available under Mozilla Public
			License (MPL) and GNU Public License (GPL).<br>
			<br>
			<br>
			--------------------------------------------------------------------------------------------------------------<br>
			<br>
			GNU Lesser General Public License<br>
			<br>
			Version 2.1, February 1999<br>
			<br>
			&nbsp;&nbsp;&nbsp; Copyright (C) 1991, 1999 Free Software Foundation,
			Inc. 59 Temple Place,<br>
			Suite 330, Boston, MA 02111-1307 USA Everyone is permitted to copy and<br>
			distribute verbatim copies of this license document, but changing it is
			not<br>
			allowed.<br>
			<br>
			&nbsp;&nbsp;&nbsp; [This is the first released version of the Lesser
			GPL. It also counts as<br>
			the successor of the GNU Library Public License, version 2, hence the
			version<br>
			number 2.1.]<br>
			<br>
			Preamble<br>
			<br>
			The licenses for most software are designed to take away your freedom
			to share<br>
			and change it. By contrast, the GNU General Public Licenses are
			intended to<br>
			guarantee your freedom to share and change free software--to make sure
			the<br>
			software is free for all its users.<br>
			<br>
			This license, the Lesser General Public License, applies to some
			specially<br>
			designated software packages--typically libraries--of the Free Software<br>
			Foundation and other authors who decide to use it. You can use it too,
			but we<br>
			suggest you first think carefully about whether this license or the
			ordinary<br>
			General Public License is the better strategy to use in any particular
			case,<br>
			based on the explanations below.<br>
			<br>
			When we speak of free software, we are referring to freedom of use, not
			price.<br>
			Our General Public Licenses are designed to make sure that you have the<br>
			freedom to distribute copies of free software (and charge for this
			service if<br>
			you wish); that you receive source code or can get it if you want it;
			that you<br>
			can change the software and use pieces of it in new free programs; and
			that<br>
			you are informed that you can do these things.<br>
			<br>
			To protect your rights, we need to make restrictions that forbid
			distributors<br>
			to deny you these rights or to ask you to surrender these rights. These<br>
			restrictions translate to certain responsibilities for you if you
			distribute<br>
			copies of the library or if you modify it.<br>
			<br>
			For example, if you distribute copies of the library, whether gratis or
			for a<br>
			fee, you must give the recipients all the rights that we gave you. You
			must<br>
			make sure that they, too, receive or can get the source code. If you
			link<br>
			other code with the library, you must provide complete object files to
			the<br>
			recipients, so that they can relink them with the library after making
			changes<br>
			to the library and recompiling it. And you must show them these terms
			so they<br>
			know their rights.<br>
			<br>
			We protect your rights with a two-step method: (1) we copyright the
			library,<br>
			and (2) we offer you this license, which gives you legal permission to
			copy,<br>
			distribute and/or modify the library.<br>
			<br>
			To protect each distributor, we want to make it very clear that there
			is no<br>
			warranty for the free library. Also, if the library is modified by
			someone<br>
			else and passed on, the recipients should know that what they have is
			not the<br>
			original version, so that the original author's reputation will not be<br>
			affected by problems that might be introduced by others.<br>
			<br>
			Finally, software patents pose a constant threat to the existence of
			any free<br>
			program. We wish to make sure that a company cannot effectively
			restrict the<br>
			users of a free program by obtaining a restrictive license from a patent<br>
			holder. Therefore, we insist that any patent license obtained for a
			version of<br>
			the library must be consistent with the full freedom of use specified
			in this<br>
			license.<br>
			<br>
			Most GNU software, including some libraries, is covered by the ordinary
			GNU<br>
			General Public License. This license, the GNU Lesser General Public
			License,<br>
			applies to certain designated libraries, and is quite different from the<br>
			ordinary General Public License. We use this license for certain
			libraries in<br>
			order to permit linking those libraries into non-free programs.<br>
			<br>
			When a program is linked with a library, whether statically or using a
			shared<br>
			library, the combination of the two is legally speaking a combined
			work, a<br>
			derivative of the original library. The ordinary General Public License<br>
			therefore permits such linking only if the entire combination fits its<br>
			criteria of freedom. The Lesser General Public License permits more lax<br>
			criteria for linking other code with the library.<br>
			<br>
			We call this license the "Lesser" General Public License because it
			does Less<br>
			to protect the user's freedom than the ordinary General Public License.
			It<br>
			also provides other free software developers Less of an advantage over<br>
			competing non-free programs. These disadvantages are the reason we use
			the<br>
			ordinary General Public License for many libraries. However, the Lesser<br>
			license provides advantages in certain special circumstances.<br>
			<br>
			For example, on rare occasions, there may be a special need to
			encourage the<br>
			widest possible use of a certain library, so that it becomes a de-facto<br>
			standard. To achieve this, non-free programs must be allowed to use the<br>
			library. A more frequent case is that a free library does the same job
			as<br>
			widely used non-free libraries. In this case, there is little to gain by<br>
			limiting the free library to free software only, so we use the Lesser
			General<br>
			Public License.<br>
			<br>
			In other cases, permission to use a particular library in non-free
			programs<br>
			enables a greater number of people to use a large body of free
			software. For<br>
			example, permission to use the GNU C Library in non-free programs
			enables many<br>
			more people to use the whole GNU operating system, as well as its
			variant, the<br>
			GNU/Linux operating system.<br>
			<br>
			Although the Lesser General Public License is Less protective of the
			users'<br>
			freedom, it does ensure that the user of a program that is linked with
			the<br>
			Library has the freedom and the wherewithal to run that program using a<br>
			modified version of the Library.<br>
			<br>
			The precise terms and conditions for copying, distribution and
			modification<br>
			follow. Pay close attention to the difference between a "work based on
			the<br>
			library" and a "work that uses the library". The former contains code
			derived<br>
			from the library, whereas the latter must be combined with the library
			in<br>
			order to run.<br>
			TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION<br>
			<br>
			0. This License Agreement applies to any software library or other
			program<br>
			which contains a notice placed by the copyright holder or other
			authorized<br>
			party saying it may be distributed under the terms of this Lesser
			General<br>
			Public License (also called "this License"). Each licensee is addressed
			as<br>
			"you".<br>
			<br>
			A "library" means a collection of software functions and/or data
			prepared so<br>
			as to be conveniently linked with application programs (which use some
			of<br>
			those functions and data) to form executables.<br>
			<br>
			The "Library", below, refers to any such software library or work which
			has<br>
			been distributed under these terms. A "work based on the Library" means
			either<br>
			the Library or any derivative work under copyright law: that is to say,
			a work<br>
			containing the Library or a portion of it, either verbatim or with<br>
			modifications and/or translated straightforwardly into another language.<br>
			(Hereinafter, translation is included without limitation in the term<br>
			"modification".)<br>
			<br>
			"Source code" for a work means the preferred form of the work for making<br>
			modifications to it. For a library, complete source code means all the
			source<br>
			code for all modules it contains, plus any associated interface
			definition<br>
			files, plus the scripts used to control compilation and installation of
			the<br>
			library.<br>
			<br>
			Activities other than copying, distribution and modification are not
			covered<br>
			by this License; they are outside its scope. The act of running a
			program<br>
			using the Library is not restricted, and output from such a program is
			covered<br>
			only if its contents constitute a work based on the Library
			(independent of<br>
			the use of the Library in a tool for writing it). Whether that is true
			depends<br>
			on what the Library does and what the program that uses the Library
			does.<br>
			<br>
			1. You may copy and distribute verbatim copies of the Library's complete<br>
			source code as you receive it, in any medium, provided that you
			conspicuously<br>
			and appropriately publish on each copy an appropriate copyright notice
			and<br>
			disclaimer of warranty; keep intact all the notices that refer to this
			License<br>
			and to the absence of any warranty; and distribute a copy of this
			License<br>
			along with the Library.<br>
			<br>
			You may charge a fee for the physical act of transferring a copy, and
			you may<br>
			at your option offer warranty protection in exchange for a fee.<br>
			<br>
			2. You may modify your copy or copies of the Library or any portion of
			it,<br>
			thus forming a work based on the Library, and copy and distribute such<br>
			modifications or work under the terms of Section 1 above, provided that
			you<br>
			also meet all of these conditions:<br>
			<br>
			&nbsp;&nbsp;&nbsp; a) The modified work must itself be a software
			library.<br>
			<br>
			&nbsp;&nbsp;&nbsp; b) You must cause the files modified to carry
			prominent notices stating<br>
			that you changed the files and the date of any change.<br>
			<br>
			&nbsp;&nbsp;&nbsp; c) You must cause the whole of the work to be
			licensed at no charge to all<br>
			third parties under the terms of this License.<br>
			<br>
			&nbsp;&nbsp;&nbsp; d) If a facility in the modified Library refers to a
			function or a table<br>
			of data to be supplied by an application program that uses the
			facility, other<br>
			than as an argument passed when the facility is invoked, then you must
			make a<br>
			good faith effort to ensure that, in the event an application does not
			supply<br>
			such function or table, the facility still operates, and performs
			whatever<br>
			part of its purpose remains meaningful.<br>
			<br>
			&nbsp;&nbsp;&nbsp; (For example, a function in a library to compute
			square roots has a<br>
			purpose that is entirely well-defined independent of the application.<br>
			Therefore, Subsection 2d requires that any application-supplied
			function or<br>
			table used by this function must be optional: if the application does
			not<br>
			supply it, the square root function must still compute square roots.)<br>
			<br>
			&nbsp;&nbsp;&nbsp; These requirements apply to the modified work as a
			whole. If identifiable<br>
			sections of that work are not derived from the Library, and can be
			reasonably<br>
			considered independent and separate works in themselves, then this
			License,<br>
			and its terms, do not apply to those sections when you distribute them
			as<br>
			separate works. But when you distribute the same sections as part of a
			whole<br>
			which is a work based on the Library, the distribution of the whole
			must be on<br>
			the terms of this License, whose permissions for other licensees extend
			to the<br>
			entire whole, and thus to each and every part regardless of who wrote
			it.<br>
			<br>
			&nbsp;&nbsp;&nbsp; Thus, it is not the intent of this section to claim
			rights or contest your<br>
			rights to work written entirely by you; rather, the intent is to
			exercise the<br>
			right to control the distribution of derivative or collective works
			based on<br>
			the Library.<br>
			<br>
			&nbsp;&nbsp;&nbsp; In addition, mere aggregation of another work not
			based on the Library<br>
			with the Library (or with a work based on the Library) on a volume of a<br>
			storage or distribution medium does not bring the other work under the
			scope<br>
			of this License. <br>
			<br>
			3. You may opt to apply the terms of the ordinary GNU General Public
			License<br>
			instead of this License to a given copy of the Library. To do this, you
			must<br>
			alter all the notices that refer to this License, so that they refer to
			the<br>
			ordinary GNU General Public License, version 2, instead of to this
			License.<br>
			(If a newer version than version 2 of the ordinary GNU General Public
			License<br>
			has appeared, then you can specify that version instead if you wish.)
			Do not<br>
			make any other change in these notices.<br>
			<br>
			Once this change is made in a given copy, it is irreversible for that
			copy, so<br>
			the ordinary GNU General Public License applies to all subsequent
			copies and<br>
			derivative works made from that copy.<br>
			<br>
			This option is useful when you wish to copy part of the code of the
			Library<br>
			into a program that is not a library.<br>
			<br>
			4. You may copy and distribute the Library (or a portion or derivative
			of it,<br>
			under Section 2) in object code or executable form under the terms of
			Sections<br>
			1 and 2 above provided that you accompany it with the complete
			corresponding<br>
			machine-readable source code, which must be distributed under the terms
			of<br>
			Sections 1 and 2 above on a medium customarily used for software
			interchange.<br>
			<br>
			If distribution of object code is made by offering access to copy from a<br>
			designated place, then offering equivalent access to copy the source
			code from<br>
			the same place satisfies the requirement to distribute the source code,
			even<br>
			though third parties are not compelled to copy the source along with the<br>
			object code.<br>
			<br>
			5. A program that contains no derivative of any portion of the Library,
			but is<br>
			designed to work with the Library by being compiled or linked with it,
			is<br>
			called a "work that uses the Library". Such a work, in isolation, is
			not a<br>
			derivative work of the Library, and therefore falls outside the scope
			of this<br>
			License.<br>
			<br>
			However, linking a "work that uses the Library" with the Library
			creates an<br>
			executable that is a derivative of the Library (because it contains
			portions<br>
			of the Library), rather than a "work that uses the library". The
			executable is<br>
			therefore covered by this License. Section 6 states terms for
			distribution of<br>
			such executables.<br>
			<br>
			When a "work that uses the Library" uses material from a header file
			that is<br>
			part of the Library, the object code for the work may be a derivative
			work of<br>
			the Library even though the source code is not. Whether this is true is<br>
			especially significant if the work can be linked without the Library,
			or if<br>
			the work is itself a library. The threshold for this to be true is not<br>
			precisely defined by law.<br>
			<br>
			If such an object file uses only numerical parameters, data structure
			layouts<br>
			and accessors, and small macros and small inline functions (ten lines
			or less<br>
			in length), then the use of the object file is unrestricted, regardless
			of<br>
			whether it is legally a derivative work. (Executables containing this
			object<br>
			code plus portions of the Library will still fall under Section 6.)<br>
			<br>
			Otherwise, if the work is a derivative of the Library, you may
			distribute the<br>
			object code for the work under the terms of Section 6. Any executables<br>
			containing that work also fall under Section 6, whether or not they are
			linked<br>
			directly with the Library itself.<br>
			<br>
			6. As an exception to the Sections above, you may also combine or link
			a "work<br>
			that uses the Library" with the Library to produce a work containing
			portions<br>
			of the Library, and distribute that work under terms of your choice,
			provided<br>
			that the terms permit modification of the work for the customer's own
			use and<br>
			reverse engineering for debugging such modifications.<br>
			<br>
			You must give prominent notice with each copy of the work that the
			Library is<br>
			used in it and that the Library and its use are covered by this
			License. You<br>
			must supply a copy of this License. If the work during execution
			displays<br>
			copyright notices, you must include the copyright notice for the
			Library among<br>
			them, as well as a reference directing the user to the copy of this
			License.<br>
			Also, you must do one of these things:<br>
			<br>
			&nbsp;&nbsp;&nbsp; a) Accompany the work with the complete
			corresponding machine-readable<br>
			source code for the Library including whatever changes were used in the
			work<br>
			(which must be distributed under Sections 1 and 2 above); and, if the
			work is<br>
			an executable linked with the Library, with the complete
			machine-readable<br>
			"work that uses the Library", as object code and/or source code, so
			that the<br>
			user can modify the Library and then relink to produce a modified
			executable<br>
			containing the modified Library. (It is understood that the user who
			changes<br>
			the contents of definitions files in the Library will not necessarily
			be able<br>
			to recompile the application to use the modified definitions.)<br>
			<br>
			&nbsp;&nbsp;&nbsp; b) Use a suitable shared library mechanism for
			linking with the Library. A<br>
			suitable mechanism is one that (1) uses at run time a copy of the
			library<br>
			already present on the user's computer system, rather than copying
			library<br>
			functions into the executable, and (2) will operate properly with a
			modified<br>
			version of the library, if the user installs one, as long as the
			modified<br>
			version is interface-compatible with the version that the work was made
			with.<br>
			<br>
			&nbsp;&nbsp;&nbsp; c) Accompany the work with a written offer, valid
			for at least three<br>
			years, to give the same user the materials specified in Subsection 6a,
			above,<br>
			for a charge no more than the cost of performing this distribution.<br>
			<br>
			&nbsp;&nbsp;&nbsp; d) If distribution of the work is made by offering
			access to copy from a<br>
			designated place, offer equivalent access to copy the above specified<br>
			materials from the same place.<br>
			<br>
			&nbsp;&nbsp;&nbsp; e) Verify that the user has already received a copy
			of these materials or<br>
			that you have already sent this user a copy.<br>
			<br>
			For an executable, the required form of the "work that uses the
			Library" must<br>
			include any data and utility programs needed for reproducing the
			executable<br>
			from it. However, as a special exception, the materials to be
			distributed need<br>
			not include anything that is normally distributed (in either source or
			binary<br>
			form) with the major components (compiler, kernel, and so on) of the
			operating<br>
			system on which the executable runs, unless that component itself
			accompanies<br>
			the executable.<br>
			<br>
			It may happen that this requirement contradicts the license
			restrictions of<br>
			other proprietary libraries that do not normally accompany the operating<br>
			system. Such a contradiction means you cannot use both them and the
			Library<br>
			together in an executable that you distribute.<br>
			<br>
			7. You may place library facilities that are a work based on the Library<br>
			side-by-side in a single library together with other library facilities
			not<br>
			covered by this License, and distribute such a combined library,
			provided that<br>
			the separate distribution of the work based on the Library and of the
			other<br>
			library facilities is otherwise permitted, and provided that you do
			these two<br>
			things:<br>
			<br>
			&nbsp;&nbsp;&nbsp; a) Accompany the combined library with a copy of the
			same work based on<br>
			the Library, uncombined with any other library facilities. This must be<br>
			distributed under the terms of the Sections above.<br>
			<br>
			&nbsp;&nbsp;&nbsp; b) Give prominent notice with the combined library
			of the fact that part<br>
			of it is a work based on the Library, and explaining where to find the<br>
			accompanying uncombined form of the same work.<br>
			<br>
			8. You may not copy, modify, sublicense, link with, or distribute the
			Library<br>
			except as expressly provided under this License. Any attempt otherwise
			to<br>
			copy, modify, sublicense, link with, or distribute the Library is void,
			and<br>
			will automatically terminate your rights under this License. However,
			parties<br>
			who have received copies, or rights, from you under this License will
			not have<br>
			their licenses terminated so long as such parties remain in full
			compliance.<br>
			<br>
			9. You are not required to accept this License, since you have not
			signed it.<br>
			However, nothing else grants you permission to modify or distribute the<br>
			Library or its derivative works. These actions are prohibited by law if
			you do<br>
			not accept this License. Therefore, by modifying or distributing the
			Library<br>
			(or any work based on the Library), you indicate your acceptance of this<br>
			License to do so, and all its terms and conditions for copying,
			distributing<br>
			or modifying the Library or works based on it.<br>
			<br>
			10. Each time you redistribute the Library (or any work based on the
			Library),<br>
			the recipient automatically receives a license from the original
			licensor to<br>
			copy, distribute, link with or modify the Library subject to these
			terms and<br>
			conditions. You may not impose any further restrictions on the
			recipients'<br>
			exercise of the rights granted herein. You are not responsible for
			enforcing<br>
			compliance by third parties with this License.<br>
			<br>
			11. If, as a consequence of a court judgment or allegation of patent<br>
			infringement or for any other reason (not limited to patent issues),<br>
			conditions are imposed on you (whether by court order, agreement or
			otherwise)<br>
			that contradict the conditions of this License, they do not excuse you
			from<br>
			the conditions of this License. If you cannot distribute so as to
			satisfy<br>
			simultaneously your obligations under this License and any other
			pertinent<br>
			obligations, then as a consequence you may not distribute the Library
			at all.<br>
			For example, if a patent license would not permit royalty-free
			redistribution<br>
			of the Library by all those who receive copies directly or indirectly
			through<br>
			you, then the only way you could satisfy both it and this License would
			be to<br>
			refrain entirely from distribution of the Library.<br>
			<br>
			If any portion of this section is held invalid or unenforceable under
			any<br>
			particular circumstance, the balance of the section is intended to
			apply, and<br>
			the section as a whole is intended to apply in other circumstances.<br>
			<br>
			It is not the purpose of this section to induce you to infringe any
			patents or<br>
			other property right claims or to contest validity of any such claims;
			this<br>
			section has the sole purpose of protecting the integrity of the free
			software<br>
			distribution system which is implemented by public license practices.
			Many<br>
			people have made generous contributions to the wide range of software<br>
			distributed through that system in reliance on consistent application
			of that<br>
			system; it is up to the author/donor to decide if he or she is willing
			to<br>
			distribute software through any other system and a licensee cannot
			impose that<br>
			choice.<br>
			<br>
			This section is intended to make thoroughly clear what is believed to
			be a<br>
			consequence of the rest of this License.<br>
			<br>
			12. If the distribution and/or use of the Library is restricted in
			certain<br>
			countries either by patents or by copyrighted interfaces, the original<br>
			copyright holder who places the Library under this License may add an
			explicit<br>
			geographical distribution limitation excluding those countries, so that<br>
			distribution is permitted only in or among countries not thus excluded.
			In<br>
			such case, this License incorporates the limitation as if written in
			the body<br>
			of this License.<br>
			<br>
			13. The Free Software Foundation may publish revised and/or new
			versions of<br>
			the Lesser General Public License from time to time. Such new versions
			will be<br>
			similar in spirit to the present version, but may differ in detail to
			address<br>
			new problems or concerns.<br>
			<br>
			Each version is given a distinguishing version number. If the Library<br>
			specifies a version number of this License which applies to it and "any
			later<br>
			version", you have the option of following the terms and conditions
			either of<br>
			that version or of any later version published by the Free Software<br>
			Foundation. If the Library does not specify a license version number,
			you may<br>
			choose any version ever published by the Free Software Foundation.<br>
			<br>
			14. If you wish to incorporate parts of the Library into other free
			programs<br>
			whose distribution conditions are incompatible with these, write to the
			author<br>
			to ask for permission. For software which is copyrighted by the Free
			Software<br>
			Foundation, write to the Free Software Foundation; we sometimes make<br>
			exceptions for this. Our decision will be guided by the two goals of<br>
			preserving the free status of all derivatives of our free software and
			of<br>
			promoting the sharing and reuse of software generally.<br>
			<br>
			NO WARRANTY<br>
			<br>
			15. BECAUSE THE LIBRARY IS LICENSED FREE OF CHARGE, THERE IS NO
			WARRANTY FOR<br>
			THE LIBRARY, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN
			OTHERWISE<br>
			STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE<br>
			LIBRARY "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR
			IMPLIED,<br>
			INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
			MERCHANTABILITY AND<br>
			FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND<br>
			PERFORMANCE OF THE LIBRARY IS WITH YOU. SHOULD THE LIBRARY PROVE
			DEFECTIVE,<br>
			YOU ASSUME THE COST OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.<br>
			<br>
			16. IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN
			WRITING WILL<br>
			ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY AND/OR
			REDISTRIBUTE<br>
			THE LIBRARY AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING
			ANY<br>
			GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF
			THE USE<br>
			OR INABILITY TO USE THE LIBRARY (INCLUDING BUT NOT LIMITED TO LOSS OF
			DATA OR<br>
			DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD
			PARTIES OR<br>
			A FAILURE OF THE LIBRARY TO OPERATE WITH ANY OTHER SOFTWARE), EVEN IF
			SUCH<br>
			HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH
			DAMAGES.<br>
			END OF TERMS AND CONDITIONS<br>
			How to Apply These Terms to Your New Libraries<br>
			If you develop a new library, and you want it to be of the greatest
			possible<br>
			use to the public, we recommend making it free software that everyone
			can<br>
			redistribute and change. You can do so by permitting redistribution
			under<br>
			these terms (or, alternatively, under the terms of the ordinary General
			Public<br>
			License).<br>
			<br>
			To apply these terms, attach the following notices to the library. It is<br>
			safest to attach them to the start of each source file to most
			effectively<br>
			convey the exclusion of warranty; and each file should have at least the<br>
			"copyright" line and a pointer to where the full notice is found.<br>
			<br>
			&nbsp;&nbsp;&nbsp; &lt;one line to give the library's name and an idea
			of what it does.&gt;<br>
			Copyright (C) &lt;year&gt; &lt;name of author&gt;<br>
			<br>
			&nbsp;&nbsp;&nbsp; This library is free software; you can redistribute
			it and/or modify it<br>
			under the terms of the GNU Lesser General Public License as published
			by the<br>
			Free Software Foundation; either version 2.1 of the License, or (at your<br>
			option) any later version.<br>
			<br>
			&nbsp;&nbsp;&nbsp; This library is distributed in the hope that it will
			be useful, but<br>
			WITHOUT ANY WARRANTY; without even the implied warranty of
			MERCHANTABILITY or<br>
			FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public
			License<br>
			for more details.<br>
			<br>
			&nbsp;&nbsp;&nbsp; You should have received a copy of the GNU Lesser
			General Public License<br>
			along with this library; if not, write to the Free Software Foundation,
			Inc.,<br>
			59 Temple Place, Suite 330, Boston, MA 02111-1307 USA <br>
			<br>
			Also add information on how to contact you by electronic and paper mail.<br>
			<br>
			You should also get your employer (if you work as a programmer) or your<br>
			school, if any, to sign a "copyright disclaimer" for the library, if<br>
			necessary. Here is a sample; alter the names:<br>
			<br>
			&nbsp;&nbsp;&nbsp; Yoyodyne, Inc., hereby disclaims all copyright
			interest in the library<br>
			`Frob' (a library for tweaking knobs) written by James Random Hacker.<br>
			<br>
			&nbsp;&nbsp;&nbsp; signature of Ty Coon, 1 April 1990<br>
			&nbsp;&nbsp;&nbsp; Ty Coon, President of Vice<br>
			<br>
			That's all there is to it!<br>
			<br>
			Acknowledgements:<br>
			<br>
			We have used the following software:<br>
			<br>
			1. ExcelReader package to read the xls files. We have taken the utility
			from the following Web site : <br>
			&nbsp;http://freshmeat.net/projects/phpexcelreader/<br>
			&nbsp;The product is available as GNU General Public License (GPL).<br>
			<br>
			2. E-mail Client to handle attachments with PHP. We have taken the
			ideas from the article by Kevin Steffer available at the following Web
			site:<br>
			http://www.linuxscope.net/articles/mailAttachmentsPHP.html<br>
			<br>
			3. FPDF package to create PDF reports and documents. We have taken the
			utility from the following Web site: <br>
			http://www.fpdf.org/<br>
			<br>
			We are grateful to the creators of the respective products for
			providing such a beautiful utilities.<br>
			&nbsp;<br>
			<br>
			</div>

		</td>
	</tr>
	<tr>
		<td align="center" width="70%">
		<form action="install.php" method="post" name="form" id="form">
                <input type="hidden" name="file" value="1checkSystem.php" />
				<input type="image" src="install/images/cwBtnStart.gif" onClick="window.location='install.php'"><br>
				By clicking the Start button, you are agreeing to the licensing terms.
		</form>
		</td>
	</tr>
	</table>
	
	<br><br><br><br>
	
	<!-- Horizontal Shade -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwShadeBg.gif) repeat-x;">
	<tr>
		<td><img src="install/images/cwShadeLeft.gif"></td>
		<td align=right><img src="install/images/cwShadeRight.gif"></td>
	</tr>
	</table>
		
	
<!--	<table border="0" cellspacing="0" cellpadding="5" width="80%" class=small> 
	<tr>	
		<td ><img align="left" src="install/images/cwRegVCRM.gif" alt="Register vtiger CRM " title="Register vtiger CRM "> (Optional)</td>
	</tr>
	<tr>
		<td><span style="color:#999999">Please take a moment to register your copy of vtiger CRM. Though this is optional, we encourage you to register. Only your name and email 
		address are required for registration. We do not sell, rent, share or otherwise, distribute your information to third parties.<br></span>
		
		</td>
	</tr>
	<tr>
		<td align=center>
<IFRAME src="http://www.vtiger.com/products/crm/registration.html" width="500" height=325 scrolling='no' frameborder="0">
  [Your user agent does not support frames or is currently configured
  not to display frames. However, you may visit
  <A href="http://www.vtiger.com/products/crm/registration.html">the related document.</A>] 
  </IFRAME> 		</td>
	</tr>
	</table>
	
		
	
	
	</td>
</tr>
</table>
<!-- Master table closes -->


<!-- <table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;"><tbody>
  <tr>
      <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
      <td align="right"><h2>Step 1 of 5</h2></td>
      <td align="right"><IMG alt="vtiger CRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
    </tr>
</tbody></table>
<table width="75%" align="center" border="0" cellpadding="10" cellspacing="0" border="0"><tbody>
    <tr>
      <td width="100%" colspan="3">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr>
				<td align="left"><h3>Registration</h3></td>
					</tr></tbody></table>
			  </td>
			  <td align="right">&nbsp;</td>
			  <td width="85%" align="right"><hr width="100%"></td>
			  </tr>
		</tbody></table>
	  </td>
    </tr>
	<tr><td><h4>Welcome to the vtiger CRM installation</h4><P>
  			This installer creates the vtiger CRM 5.0 Alpha5 database tables and sets the configuration variables that you need to start.
			The entire process should take about four minutes.

			<p>

 <font color=red> <b>Kindly note vtiger CRM 5.0 Alpha5 is tested on mysql 5.0.19 and PHP 5.1.19 and Apache 2.0.40 .  vtiger CRM 5 will not work on mysql 4.0.x versions. vtiger crm can run on a system which has xampp/lampp/wampp already installed in it. Please follow the wizard for the installation procedure regardless of the setup</b> </font>

			
			<P>For installation help, please visit the vtiger CRM <A href="http://www.vtiger.com/forums/index.php?c=3" target="_blank">support forums</A>.</td>
	</tr>

	<tr>

		<td><hr></td>
	</tr>
	<tr>
		<td valign='top'><h4>vtiger CRM Registration</h4><br>
Please take a moment and register with vtiger CRM. Your name and email address are the only required fields for registration. All other fields are optional, but very helpful. We do not sell, rent, share, or otherwise distribute the information collected here to third parties.
<P>
Please see <a href="http://www.vtigercrm.com" target="_blank">http://www.vtigercrm.com</a> for information on additional functionality, support requests…
</td></tr>	<tr>
       <td align="right">
	    <form action="install.php" method="post" name="form" id="form">
		<input type="hidden" name="file" value="1checkSystem.php" />
		<input class="button" type="submit" name="next" value="Next >" /> &nbsp; &nbsp; </td>
    </tr>
<tr><td align='center' colspan='3'>  <IFRAME src="http://www.vtiger.com/products/crm/registration.html" width="100%" height=325 scrolling='no' frameborder="0">
  [Your user agent does not support frames or is currently configured
  not to display frames. However, you may visit
  <A href="http://www.vtiger.com/products/crm/registration.html">the related document.</A>] 
  </IFRAME>
</td></tr>
	</tbody>

</form> -->
</body>
</html>
