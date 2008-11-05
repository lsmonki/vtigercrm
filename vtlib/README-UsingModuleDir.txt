Creating basic skeleton module
==============================

1. Rename ModuleDir to ModuleName
2. Rename ModuleDir/ModuleFile.php to ModuleName.php
3. Rename ModuleDir/ModuleFileAjax.php to ModuleNameAjax.php
4. Rename ModuleDir/ModuleFile.js to ModuleName.js

4. Edit ModuleName.php

   a. Rename Class ModuleClass to ModuleName 

   b. Update $table_name and $table_index (Module table name and table index column)

   c. Update $groupTable

   d. Update $tab_name, $tab_name_index

   e. Update $list_fields, $list_fields_name, $sortby_fields

   f. Update $detailview_links

   g. Update $default_order_by, $default_sort_order

   h. Update $customFieldTable

   i. Rename function ModuleClass to function ModuleName [This is the Constructor Class]