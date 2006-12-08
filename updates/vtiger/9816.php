<?php
	$adb->createTable('vtigerversion', '
		id I AUTOINCREMENT KEY,
		project C 50,
		revision I'
	);
?>
