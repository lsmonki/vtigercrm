<?php
function ftpBackupFile($source_file, $ftpserver, $ftpuser, $ftppassword)
{
	
	// set up basic connection
	$conn_id = ftp_connect($ftpserver);

	// login with username and password
	$login_result = ftp_login($conn_id, $ftpuser, $ftppassword);

	// check connection
	/*if ((!$conn_id) || (!$login_result)) {
		echo "FTP connection has failed!";
		echo "Attempted to connect to $ftp_server for user $ftp_user_name";
		exit;
	} else {
		echo "Connected to $ftp_server, for user $ftp_user_name";
	}
	*/

	// upload the file
	$destination_file=$source_file;
	$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

	// check upload status
	/*
	if (!$upload) {
		echo "FTP upload has failed!";
	} else {
		echo "Uploaded $source_file to $ftp_server as $destination_file";
	}*/

	// close the FTP stream
	ftp_close($conn_id);
}
?>
