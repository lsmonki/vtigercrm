#/bin/sh
#*********************************************************************************
# The contents of this file are subject to the vtiger CRM Public License Version 1.0
# ("License"); You may not use this file except in compliance with the License
# The Original Code is:  vtiger CRM Open Source
# The Initial Developer of the Original Code is vtiger.
# Portions created by vtiger are Copyright (C) vtiger.
# All Rights Reserved.
#
# ********************************************************************************

setVariables()
{
	wdir=`pwd`
	cp -f ../apache/htdocs/vtigerCRM/migrator_backup_connection.php ../apache/htdocs/vtigerCRM/migrator_connection.php
	chmod 777 ../apache/htdocs/vtigerCRM/migrator_connection.php 
}


getvtiger3_2_installdir()
{
	echo "Specify the host name of the vtiger CRM 3.2 mysql server"
	read mysql_host_name_3_2
	
	echo ''
	echo "Specify the user name of the vtiger CRM 3.2 mysql server"
	read mysql_username_3_2 

	echo ''
	echo "Specify the password of the vtiger CRM 3.2 mysql server"
	read mysql_password_3_2

	echo ''
	echo "Specify the port of the vtiger CRM 3.2 mysql server"
	read mysql_port_3_2

	echo ''
	echo "Specify the apache port of the vtiger CRM 4.0 mysql server"
	read apache_port_4_0		



	finAndReplace ../apache/htdocs/vtigerCRM/migrator_connection.php MYSQLHOSTNAME ${mysql_host_name_3_2}
	finAndReplace ../apache/htdocs/vtigerCRM/migrator_connection.php MYSQLUSERNAME ${mysql_username_3_2}
	finAndReplace ../apache/htdocs/vtigerCRM/migrator_connection.php MYSQLPASSWORD ${mysql_password_3_2}
	finAndReplace ../apache/htdocs/vtigerCRM/migrator_connection.php MYSQLPORT ${mysql_port_3_2}
	chmod 777 ../apache/htdocs/vtigerCRM/migrator_connection.php	

}

finAndReplace()
{
	fileName=$1
	var=$2
	val=$3
	
	tmpFile=${fileName}.$$
	sed -e "s@${var}@${val}@g" ${fileName} > ${tmpFile}
	mv -f ${tmpFile} ${fileName}
	
}

migrate()
{
	wget http://localhost:${apache_port_4_0}/Migrator.php
}

main()
{
	setVariables $*
	getvtiger3_2_installdir
	migrate
}


main $*
