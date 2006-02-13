<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is Xavier DUTOIT;
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): SugarCRM Inc.
 ********************************************************************************/
/*********************************************************************************
 * $Header $
 * Description:  Tool to manage the uploaded files.
 ********************************************************************************/

require_once('config.php');

/** BEGIN CONTRIBUTION
* Date: 09/07/04
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): SugarCRM Inc. */
define('MAX_FILE_SIZE', $upload_maxsize);
define('UPLOAD_FOLDER', $upload_dir);
/** END CONTRIBUTION */


class File {
	function File ($id='',$name='',$module='Notes')
	{
		$this->id = $id;
		$this->name = $name;
		$this->module = $module;
		// just in case we need to attach file to more than the notes
		/** BEGIN CONTRIBUTION
		* Date: 09/07/04
		* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
		* All Rights Reserved.
		* Contributor(s): SugarCRM Inc. */
		$this->folder = '/'.UPLOAD_FOLDER;
		/** END CONTRIBUTION */
	}

	/**
	* Upload the file, you are supposed to call SetID after that (once you know it...
	*/
	function Upload ($fieldname)
	{
		global $root_directory;
		/** BEGIN CONTRIBUTION
		* Date: 09/08/04
		* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
		* All Rights Reserved.
		* Contributor(s): SugarCRM Inc. */
		global $upload_badext;
		/** END CONTRIBUTION */
		if (!isset ($_FILES[$fieldname]) || empty ($_FILES[$fieldname]['tmp_name'])) {
			return true; // uploading an empty file can't fail...
		}
		$this->name = $_FILES[$fieldname]['name'];
                

		/** BEGIN CONTRIBUTION
		* Date: 09/07/04
		* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
		* All Rights Reserved.
		* Contributor(s): SugarCRM Inc. */
		if (isset($id)) $this->id = $id;
		$ext = (strrpos($this->name, ".")) ? substr($this->name, strrpos($this->name, ".") + 1) : NULL;
		if (in_array($ext, $upload_badext)) {
			$this->name .= '.txt';
		}
		/** END CONTRIBUTION */
		$dest = $root_directory.$this->folder.$this->name;
		if (!move_uploaded_file($_FILES[$fieldname]['tmp_name'], $dest))
		{
			if (!is_dir($this->folder))
			{
				if (mkdir($root_directory.$this->folder, 0755))
					return move_uploaded_file($_FILES[$fieldname]['tmp_name'], $dest);
				else
				{
					// how to handle such error ?
					die ("ERROR: can't upload $this->name to $this->folder");
				}
			}
		}
		return true;
	}


	function SetID ($id)
	{
		global $root_directory;
		if (empty ($this->name))
			return;
		if (empty ($id))
			die ("id empty");
		$this->id = $id;
		$path = $root_directory.$this->folder;
		rename ($path.$this->name,$path.$id.$this->name);
	}

	function URL ()
	{
		/** VTIGER CRM CONTRIBUTION BEGINS
		*/
		$web_root = $_SERVER['SERVER_NAME']. ":" .$_SERVER['SERVER_PORT'];
		$web_root = "http://$web_root";
		/*
		   VTIGER CRM CONTRIBUTION ENDS	
		*/		

		//global $site_URL;
		return $web_root.$this->folder.$this->id.rawurlencode($this->name);
	}

	/** BEGIN CONTRIBUTION
	* Date: <today's date>
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): SugarCRM Inc. */
	function Delete ($old_file)
	{
		global $root_directory, $upload_dir;
		return unlink($root_directory . "/" . $upload_dir . $old_file);
	}
	/* END CONTRIBUTION */
}

?>
