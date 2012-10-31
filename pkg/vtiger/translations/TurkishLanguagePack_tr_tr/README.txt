########################################################################
# Turkce dil paket
# Turkish Language Pak
#
# Adapted from french language pack 5.1.0 v1.4
#
#
# Thanks to vtiger5.exe
########################################################################

To install, use module manager
click on custom module
click import
select this zip file
validate
disconnect and choose turkish when you reconnect

to make turkish default, modify config.inc.php at the root of vtiger install
Change, the line default_language en_us to tr_tr


#########################
#     Attention : 		#
#########################

Dans le module Documents, la liste des actions à droite n'apparait plus après un passage en français
Pour corriger ce problème, vous devez modifier dans le fichier : /modules/Documents/DetailView.php
La ligne :
if($block_entries['File Name']['value'] != '' || isset($block_entries['File Name']['value']))
Par
if($block_entries[getTranslatedString('File Name','Documents')]['value'] !='' || 
	isset($block_entries[getTranslatedString('File Name','Documents')]['value'])) 

Dans le module Rapports
La liste de choix pour les filtres contenant entre autre égal à, supérieur à, ...
Pour remplacer le None par aucun, il faut modifier une ligne codée en dur
Dans /modules/Reports/reports.js
	Line 60: 		selObj.options[0] = new Option ('None', '');
	Line 84: 		selObj.options[0] = new Option ('None', '');
	Line 748: 		selObj.options[0] = new Option ('None', '');
	Line 774: 		selObj.options[0] = new Option ('None', '');
A remplacer par :
	Line 60: 		selObj.options[0] = new Option ('Aucun', '');
	Line 84: 		selObj.options[0] = new Option ('Aucun', '');
	Line 748: 		selObj.options[0] = new Option ('Aucun', '');
	Line 774: 		selObj.options[0] = new Option ('Aucun', '');
