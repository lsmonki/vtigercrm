<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_ModuleManager_ExtensionStore_View extends Settings_Vtiger_Index_View {
    
    public function __construct() {
        $this->exposeMethod('searchExtension');
        $this->exposeMethod('extensionDetail');
    }
    
    public function process(Vtiger_Request $request) {
        $mode = $request->getMode();
        if(!empty($mode)) {
                $this->invokeExposedMethod($mode, $request);
                return;
        }
                
        $viewer = $this->getViewer($request);
        $qualifiedModuleName = $request->getModule(false);

        $viewer->assign('EXTENSION_LOADER',function_exists('_vtextnld'));
        $viewer->assign('SEARCH_MODE', false);
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->assign('EXTENSIONS_LIST', Settings_ModuleManager_Extension_Model::getAll());
        $viewer->assign('EXTENSION_LOADER',function_exists('_vtextnld'));
        $viewer->view('ExtensionStore.tpl', $qualifiedModuleName);
    }
    
    /**
    * Function to get the list of Script models to be included
    * @param Vtiger_Request $request
    * @return <Array> - List of Vtiger_JsScript_Model instances
    */
   function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
                "libraries.jquery.jqueryRating",
                "libraries.jquery.boxslider.jqueryBxslider",
                "modules.Settings.$moduleName.resources.ExtensionStore",
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
   }
   
   public function searchExtension(Vtiger_Request $request){
        $searchTerm = $request->get('searchTerm');
        $viewer = $this->getViewer($request);
        $qualifiedModuleName = $request->getModule(false);

        $viewer->assign('EXTENSION_LOADER',function_exists('_vtextnld'));
        $viewer->assign('SEARCH_MODE', true);
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->assign('EXTENSIONS_LIST', Settings_ModuleManager_Extension_Model::getSearchedExtensions($searchTerm));
        $viewer->view('ExtensionStore.tpl', $qualifiedModuleName);
    }
    
    public function extensionDetail(Vtiger_Request $request){
        $viewer = $this->getViewer($request);
        $qualifiedModuleName = $request->getModule(false);
        $extensionId = $request->get('extensionId');
        $moduleAction = $request->get('moduleAction');
        $extensionDetail = Settings_ModuleManager_Extension_Model::getAll($extensionId);
        
        $viewer->assign('MODULE_ACTION', $moduleAction);
        $viewer->assign('EXTENSION_DETAIL', $extensionDetail[$extensionId]);
        $viewer->assign('EXTENSION_ID', $extensionId);
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->view('ExtensionDetail.tpl', $qualifiedModuleName);
    }
}
