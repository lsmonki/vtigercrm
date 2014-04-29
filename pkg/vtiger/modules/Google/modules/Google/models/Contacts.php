<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */
vimport('~~/modules/WSAPP/synclib/models/SyncRecordModel.php');

class Google_Contacts_Model extends WSAPP_SyncRecordModel {

    /**
     * return id of Google Record
     * @return <string> id
     */
    public function getId() {
        return $this->data['entity']->id->text;
    }

    /**
     * return modified time of Google Record
     * @return <date> modified time 
     */
    public function getModifiedTime() {
        return $this->vtigerFormat($this->data['entity']->updated->text);
    }

    /**
     * return first name of Google Record
     * @return <string> $first name
     */
    function getFirstName() {
        $fname = $this->data['entity']->name->givenName->text;
        return $fname;
    }

    /**
     * return Lastname of Google Record
     * @return <string> Last name
     */
    function getLastName() {
        $lname = $this->data['entity']->name->familyName->text;
        return $lname;
    }

    /**
     * return Emails of Google Record
     * @return <array> emails
     */
    function getEmails() {
        $arr = $this->data['entity']->getEmails();
        if (!empty($arr)) {
            $emails = array();
            foreach ($arr as $i => $email) {
                array_push($emails, $email->getValue());
            }
            return $emails;
        }
        return null;
    }

    /**
     * return Phone number of Google Record
     * @return <array> phone numbers
     */
    function getPhones() {

        $arr = $this->data['entity']->getPhones();
        if (!empty($arr)) {
            $phones = array();
            foreach ($arr as $i => $phone) {
                array_push($phones, $phone->getValue());
            }
            return $phones;
        }
        return null;
    }

    /**
     * return Addresss of Google Record
     * @return <array> Addresses
     */
    function getAddresses() {
        $arr = $this->data['entity']->getAddresses();
        if (!empty($arr)) {
            $addresses = array();
            foreach ($arr as $i => $address) {
                $addresses['street']=$address->getStreet()->text; 
                $addresses['city']=$address->getCity()->text; 
                $addresses['zip']=$address->getPostcode()->text; 
                $addresses['state']=$address->getRegion()->text; 
                $addresses['country']=$address->getCountry()->text; 
            }
            return $addresses;
        }
        return null;
    }
    
    function getTitle() {
        return $this->data['entity']->organization->orgTitle->text;
    }
    
    function getAccountName() {
        $orgName = $this->data['entity']->organization->orgName->text;
        return $orgName;
    }

    /**
     * Returns the Google_Contacts_Model of Google Record
     * @param <array> $recordValues
     * @return Google_Contacts_Model
     */
    public static function getInstanceFromValues($recordValues) {
        $model = new Google_Contacts_Model($recordValues);
        return $model;
    }

    /**
     * converts the Google Format date to 
     * @param <date> $date Google Date
     * @return <date> Vtiger date Format
     */
    public function vtigerFormat($date) {
        list($date, $timestring) = explode('T', $date);
        list($time, $tz) = explode('.', $timestring);

        return $date . " " . $time;
    }

}

?>
