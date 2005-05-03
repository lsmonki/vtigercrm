# The contents of this file are subject to the SugarCRM Public License Version 1.1.2
# ("License"); You may not use this file except in compliance with the# License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
# Software distributed under the License is distributed on an  "AS IS"  basis,
# WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
# the specific language governing rights and limitations under the License.
# The Original Code is:  SugarCRM Open Source# The Initial Developer of the Original Code is SugarCRM, Inc.
# Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
# All Rights Reserved.
# Contributor(s): ____________________________________

# Description:  Schema migration from 2.1 -> 3.0


# This SQL script must be run on a vtigerCRM 2.1 database in order for that db
# to function with the 3.0 application.



# Users Table
ALTER TABLE users CHANGE user_name user_name VARCHAR(100);
ALTER TABLE users CHANGE user_password user_password VARCHAR(100);
ALTER TABLE users ADD date_format VARCHAR(30) AFTER weekstart;

update users set date_format='yyyy-mm-dd';

# Accounts Table

ALTER TABLE account CHANGE email1 email1 VARCHAR(100);
ALTER TABLE account CHANGE email2 email2 VARCHAR(100);

# Activity Table


ALTER TABLE activity ADD eventstatus VARCHAR(100) AFTER status;


#Products Table

ALTER TABLE products CHANGE qty_per_unit qty_per_unit decimal(11,2);
ALTER TABLE products CHANGE unit_price unit_price decimal(11,2);
ALTER TABLE products CHANGE weight weight decimal(11,3);
ALTER TABLE products CHANGE commissionrate commissionrate decimal(4,3);

#Currency Info
CREATE TABLE currency_info (
  currency_name varchar(100) NOT NULL default '',
  currency_code varchar(100) default NULL,
  currency_symbol varchar(30) default NULL,
  PRIMARY KEY  (currency_name)
) TYPE=InnoDB;

INSERT INTO currency_info VALUES ('U.S Dollar','USD','$');

# Login History

ALTER TABLE loginhistory CHANGE login_time login_time_temp timestamp(14);
ALTER TABLE loginhistory CHANGE logout_time logout_time_temp timestamp(14);

ALTER TABLE loginhistory CHANGE login_time_temp logout_time timestamp(14);
ALTER TABLE loginhistory CHANGE logout_time_temp login_time timestamp(14);

# Default Org Table
CREATE TABLE def_org_field (
  tabid int(10) default NULL,
  fieldid int(19) default NULL,
  visible int(19) default NULL,
  readonly int(19) default NULL,
  KEY idx_def_org_field (tabid,fieldid)
) TYPE=InnoDB; 

insert into def_org_field(tabid, fieldid,visible, readonly) (select tabid,fieldid,0,1 from profile2field group by fieldid); 
