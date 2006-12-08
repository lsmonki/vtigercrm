<?php

if($_POST['Continue']) {
	// process the upgrade and return the user to the login screen
	require_once 'include/utils.php';
	$updates = version_difference();
	$files = upgrade_files($updates);
	foreach($files as $project=>$includes) {
		foreach($includes as $include) {
			include 'updates/'.$project.'/'.$include;
		}
	}

	set_version($updates);
	header('Location: index.php');
	exit;
}

// check if an upgrade is required
$upgrades = version_difference();
if($upgrades) {
	$files = upgrade_files($upgrades);
	if(!$files) {
		// source code revision numbers bumped, but the database
		// was unchanged
		// update the database revision silently
		set_version($upgrades);
	} else {
		// upgrade required
		// prompt for confirmation
print <<< END
	<div style="text-align: center">
		VtigerCRM upgrade detected
		<br>
		<br>
END;
		foreach($upgrades as $project=>$path) {
			print $project.' upgraded from version '.(int) $path[0].' to '.$path[1].'<br>';
		}
print <<< END
	<br>
	The required database changes will be completed automatically.  Please backup your database before proceeding.<br>
	<form action="update.php" method="post">
	<input type="submit" name="Continue" value="Continue" />
	</form>
END;
		exit;
	}
}

function set_version($versions)
{
	global $adb;

	foreach($versions as $project=>$path) {
		if($path[0]) {
			$sql = "UPDATE vtigerversion SET revision = ".$path[1]." WHERE project = ".$adb->quote($project);
		} else {
			$sql = "INSERT INTO vtigerversion (project, revision)
				VALUES (".$adb->quote($project).", ".$path[1].")";
		}
		$adb->query($sql);
	}
}

function version_difference()
{
	// set source code version
	$svn_revision = array();
	include 'vtigerversion.php';
	
	global $adb;

	// get database version
	$db_version = array();
	$sql = "SELECT project, revision
		FROM vtigerversion";
	$res = $adb->query($sql);
	if(!$res) {
		// assume database is being upgraded from a version older then
		// when the upgrade system was put in place
		
		// TODO guess a fake revision number based on some properties
		// of the database to support upgrades from older vtiger versions

		//die('Cannot upgrade the database from unsupported vtiger version');
	} else {
		while($row = $adb->fetch_row($res)) {
			$db_version[$row['project']] = $row['revision'];
		}
	}

	$upgrade_path = array();
	foreach($svn_revision as $project=>$version) {
		if($db_version[$project] < $version) {
			$upgrade_path[$project] = array($db_version[$project], $version);
		}
	}
	return $upgrade_path;
}

function upgrade_files($upgrade_path)
{
	// returns a list of all files needed to be included to perform the
	// given upgrade path

	$files = array();
	foreach($upgrade_path as $project=>$path) {
		$dir = 'updates/'.$project;
		if(is_dir($dir)) {
			if($dh = opendir($dir)) {
				while(($file = readdir($dh)) !== false) {
					if(preg_match('/^\d+\.php$/', $file, $version)) {
						$version = (int) $version[0];
						if($version > $path[0] && $version <= $path[1]) {
							$files[$project][] = $file;
						}
					}
				}
				closedir($dh);
			}
		}
	}

	return $files;
}

?>
