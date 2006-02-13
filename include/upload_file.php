<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.;
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/*********************************************************************************
 * $Header $
 * Description:
 ********************************************************************************/

require_once('config.php');


class UploadFile 
{

	var $field_name;
	var $stored_file_name;

        function UploadFile ($field_name)
        {
		$this->field_name = $field_name;
        }

	function get_url($stored_file_name,$bean_id)
	{
		global $site_URL;
		global $upload_dir;
                //echo $site_URL.'/'.$upload_dir.$bean_id.$stored_file_name;
                //echo $_ENV['HOSTNAME'] .':' .$_SERVER["SERVER_PORT"].'/'.$upload_dir.$bean_id.$stored_file_name;
                return 'http://'.$_ENV['HOSTNAME'] .':' .$_SERVER["SERVER_PORT"].'/'.$upload_dir.$bean_id.$stored_file_name;
                //return $site_URL.'/'.$upload_dir.$bean_id.$stored_file_name;
	}

	function duplicate_file($old_id, $new_id, $file_name)
	{
		global $root_directory;
		global $upload_dir;
                $source = $root_directory.'/'.$upload_dir.$old_id.$file_name;
                $destination = $root_directory.'/'.$upload_dir.$new_id.$file_name;
		copy( $source,$destination);
	}
	
	function confirm_upload()
	{
		global $root_directory;
		global $upload_dir;
		global $upload_maxsize;
                global $upload_badext;


		if (!is_uploaded_file($_FILES[$this->field_name]['tmp_name']) )
		{
			return false;
		}
		else if ($_FILES[$this->field_name]['size'] > $upload_maxsize)
		{
			die("ERROR: uploaded file was too big: max filesize:$upload_maxsize");
		}


		if( !is_writable( $root_directory.'/'.$upload_dir))
		{
			die ("ERROR: cannot write to directory: $root_directory/$upload_dir for uploads");
		}

		$this->stored_file_name = $this->create_stored_filename();

		return true;
	}

	function get_stored_file_name()
	{
		return $this->stored_file_name;
	}

	function create_stored_filename()
	{
		global $upload_badext;
                $stored_file_name = $_FILES[$this->field_name]['name'];

                $ext_pos = strrpos($stored_file_name, ".");

		$ext = substr($stored_file_name, $ext_pos + 1);

                if (in_array($ext, $upload_badext)) 
		{
                        $stored_file_name .= ".txt";
                }

		return $stored_file_name;
	}

	function final_move($bean_id)
	{
		global $root_directory;
		global $upload_dir;

                $file_name = $bean_id.$this->stored_file_name;

                $destination = $root_directory.'/'.$upload_dir.$file_name;

		if (!move_uploaded_file($_FILES[$this->field_name]['tmp_name'], $destination))
                {
			die ("ERROR: can't move_uploaded_file to $destination");
                }

                return true;


	}

	function unlink_file($bean_id,$file_name)
        {
                global $root_directory;
		global $upload_dir;
                return unlink($root_directory."/".$upload_dir.$bean_id.$file_name);
        }


}
?>
