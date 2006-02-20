-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2-Debian-1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Lundi 20 Février 2006 à 19:11
-- Version du serveur: 5.0.18
-- Version de PHP: 4.4.2-1
-- 
-- Base de données: `vtigercrm`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `CustomerDetails`
-- 

CREATE TABLE `CustomerDetails` (
  `customerid` int(19) NOT NULL,
  `portal` varchar(3) default NULL,
  `support_start_date` date default NULL,
  `support_end_date` date default NULL,
  PRIMARY KEY  (`customerid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `CustomerDetails`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `PortalInfo`
-- 

CREATE TABLE `PortalInfo` (
  `id` int(11) NOT NULL,
  `user_name` varchar(50) default NULL,
  `user_password` varchar(30) default NULL,
  `type` varchar(5) default NULL,
  `last_login_time` datetime NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime NOT NULL,
  `isactive` int(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `PortalInfo`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `account`
-- 

CREATE TABLE `account` (
  `accountid` int(19) NOT NULL default '0',
  `accountname` varchar(100) NOT NULL,
  `parentid` int(19) default '0',
  `account_type` varchar(50) default NULL,
  `industry` varchar(50) default NULL,
  `annualrevenue` int(19) default '0',
  `rating` varchar(50) default NULL,
  `ownership` varchar(50) default NULL,
  `siccode` int(10) default '0',
  `tickersymbol` varchar(30) default NULL,
  `phone` varchar(30) default NULL,
  `otherphone` varchar(30) default NULL,
  `email1` varchar(100) default NULL,
  `email2` varchar(100) default NULL,
  `website` varchar(30) default NULL,
  `fax` varchar(30) default NULL,
  `employees` int(10) default '0',
  PRIMARY KEY  (`accountid`),
  KEY `account_type` (`account_type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `account`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `accountbillads`
-- 

CREATE TABLE `accountbillads` (
  `accountaddressid` int(19) NOT NULL default '0',
  `city` varchar(30) default NULL,
  `code` varchar(30) default NULL,
  `country` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `street` varchar(250) default NULL,
  PRIMARY KEY  (`accountaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `accountbillads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `accountdepstatus`
-- 

CREATE TABLE `accountdepstatus` (
  `deploymentstatusid` int(19) NOT NULL auto_increment,
  `deploymentstatus` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`deploymentstatusid`),
  UNIQUE KEY `AccountDepStatus_UK0` (`deploymentstatus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `accountdepstatus`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `accountownership`
-- 

CREATE TABLE `accountownership` (
  `acctownershipid` int(19) NOT NULL auto_increment,
  `ownership` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`acctownershipid`),
  UNIQUE KEY `AccountOwnership_UK0` (`ownership`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `accountownership`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `accountrating`
-- 

CREATE TABLE `accountrating` (
  `accountratingid` int(19) NOT NULL auto_increment,
  `rating` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`accountratingid`),
  UNIQUE KEY `AccountRating_UK0` (`rating`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `accountrating`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `accountregion`
-- 

CREATE TABLE `accountregion` (
  `accountregionid` int(19) NOT NULL auto_increment,
  `region` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`accountregionid`),
  UNIQUE KEY `AccountRegion_UK0` (`region`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `accountregion`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `accountscf`
-- 

CREATE TABLE `accountscf` (
  `accountid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`accountid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `accountscf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `accountshipads`
-- 

CREATE TABLE `accountshipads` (
  `accountaddressid` int(19) NOT NULL default '0',
  `city` varchar(30) default NULL,
  `code` varchar(30) default NULL,
  `country` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `street` varchar(250) default NULL,
  PRIMARY KEY  (`accountaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `accountshipads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `accounttype`
-- 

CREATE TABLE `accounttype` (
  `accounttypeid` int(19) NOT NULL auto_increment,
  `accounttype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`accounttypeid`),
  UNIQUE KEY `AccountType_UK0` (`accounttype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- 
-- Contenu de la table `accounttype`
-- 

INSERT INTO `accounttype` VALUES (1, '--None--', 0, 1);
INSERT INTO `accounttype` VALUES (2, 'Analyst', 1, 1);
INSERT INTO `accounttype` VALUES (3, 'Competitor', 2, 1);
INSERT INTO `accounttype` VALUES (4, 'Customer', 3, 1);
INSERT INTO `accounttype` VALUES (5, 'Integrator', 4, 1);
INSERT INTO `accounttype` VALUES (6, 'Investor', 5, 1);
INSERT INTO `accounttype` VALUES (7, 'Partner', 6, 1);
INSERT INTO `accounttype` VALUES (8, 'Press', 7, 1);
INSERT INTO `accounttype` VALUES (9, 'Prospect', 8, 1);
INSERT INTO `accounttype` VALUES (10, 'Reseller', 9, 1);
INSERT INTO `accounttype` VALUES (11, 'Other', 10, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `activity`
-- 

CREATE TABLE `activity` (
  `activityid` int(19) NOT NULL default '0',
  `subject` varchar(100) NOT NULL,
  `semodule` varchar(20) default NULL,
  `activitytype` varchar(50) NOT NULL,
  `description` text,
  `date_start` date NOT NULL,
  `due_date` date default NULL,
  `time_start` varchar(50) NOT NULL,
  `sendnotification` varchar(50) NOT NULL default 'false',
  `duration_hours` varchar(2) default NULL,
  `duration_minutes` varchar(2) default NULL,
  `status` varchar(100) default NULL,
  `eventstatus` varchar(100) default NULL,
  `priority` varchar(150) default NULL,
  `location` varchar(150) default NULL,
  PRIMARY KEY  (`activityid`),
  KEY `Activity_IDX0` (`activityid`,`subject`),
  KEY `activitytype` (`activitytype`,`date_start`),
  KEY `date_start` (`date_start`,`due_date`),
  KEY `date_start2` (`date_start`,`time_start`),
  KEY `eventstatus` (`eventstatus`),
  KEY `status` (`status`,`eventstatus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `activity`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `activity_reminder`
-- 

CREATE TABLE `activity_reminder` (
  `activity_id` int(11) NOT NULL,
  `reminder_time` int(11) NOT NULL,
  `reminder_sent` int(2) NOT NULL,
  `recurringid` int(19) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `activity_reminder`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `activitygrouprelation`
-- 

CREATE TABLE `activitygrouprelation` (
  `activityid` int(19) default NULL,
  `groupname` varchar(100) default NULL,
  KEY `activitygrouprelation_IDX0` (`activityid`),
  KEY `activitygrouprelation_IDX1` (`groupname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `activitygrouprelation`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `activityproductrel`
-- 

CREATE TABLE `activityproductrel` (
  `activityid` int(19) NOT NULL default '0',
  `productid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`activityid`,`productid`),
  KEY `activityproductrel_IDX0` (`activityid`),
  KEY `activityproductRel_IDX1` (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `activityproductrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `activitytype`
-- 

CREATE TABLE `activitytype` (
  `activitytypeid` int(19) NOT NULL auto_increment,
  `activitytype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`activitytypeid`),
  UNIQUE KEY `ActivityType_UK0` (`activitytype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `activitytype`
-- 

INSERT INTO `activitytype` VALUES (1, 'Call', 0, 1);
INSERT INTO `activitytype` VALUES (2, 'Meeting', 1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `activsubtype`
-- 

CREATE TABLE `activsubtype` (
  `activesubtypeid` int(19) NOT NULL auto_increment,
  `activsubtype` varchar(100) default NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`activesubtypeid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `activsubtype`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `attachments`
-- 

CREATE TABLE `attachments` (
  `attachmentsid` int(19) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) default NULL,
  `type` varchar(100) default NULL,
  `attachmentsize` varchar(50) NOT NULL,
  `attachmentcontents` longblob,
  PRIMARY KEY  (`attachmentsid`),
  KEY `attachmentsid` (`attachmentsid`),
  KEY `description` (`description`,`name`,`type`,`attachmentsid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `attachments`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `businesstype`
-- 

CREATE TABLE `businesstype` (
  `businesstypeid` int(19) NOT NULL auto_increment,
  `businesstype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`businesstypeid`),
  UNIQUE KEY `BusinessType_UK0` (`businesstype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `businesstype`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `carrier`
-- 

CREATE TABLE `carrier` (
  `carrierid` int(19) NOT NULL auto_increment,
  `carrier` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`carrierid`),
  UNIQUE KEY `carrier_UK0` (`carrier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `carrier`
-- 

INSERT INTO `carrier` VALUES (1, 'FedEx', 0, 1);
INSERT INTO `carrier` VALUES (2, 'UPS', 1, 1);
INSERT INTO `carrier` VALUES (3, 'USPS', 2, 1);
INSERT INTO `carrier` VALUES (4, 'DHL', 3, 1);
INSERT INTO `carrier` VALUES (5, 'BlueDart', 4, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `cntactivityrel`
-- 

CREATE TABLE `cntactivityrel` (
  `contactid` int(19) NOT NULL default '0',
  `activityid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`activityid`),
  KEY `CntActivityRel_IDX0` (`contactid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `cntactivityrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `competitor`
-- 

CREATE TABLE `competitor` (
  `competitorid` int(19) NOT NULL,
  `competitorname` varchar(100) NOT NULL,
  `website` varchar(100) default NULL,
  `strength` varchar(250) default NULL,
  `weakness` varchar(250) default NULL,
  PRIMARY KEY  (`competitorid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `competitor`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `contactaddress`
-- 

CREATE TABLE `contactaddress` (
  `contactaddressid` int(19) NOT NULL default '0',
  `mailingcity` varchar(40) default NULL,
  `mailingstreet` varchar(250) default NULL,
  `mailingcountry` varchar(40) default NULL,
  `othercountry` varchar(30) default NULL,
  `mailingstate` varchar(30) default NULL,
  `othercity` varchar(40) default NULL,
  `otherstate` varchar(50) default NULL,
  `mailingzip` varchar(30) default NULL,
  `otherzip` varchar(30) default NULL,
  `otherstreet` varchar(250) default NULL,
  PRIMARY KEY  (`contactaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `contactaddress`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `contactdetails`
-- 

CREATE TABLE `contactdetails` (
  `contactid` int(19) NOT NULL default '0',
  `accountid` int(19) default NULL,
  `salutation` varchar(50) default 'Mr',
  `firstname` varchar(40) default NULL,
  `lastname` varchar(80) NOT NULL,
  `email` varchar(100) default NULL,
  `phone` varchar(50) default NULL,
  `mobile` varchar(50) default NULL,
  `title` varchar(50) default NULL,
  `department` varchar(30) default NULL,
  `fax` varchar(50) default NULL,
  `reportsto` varchar(30) default NULL,
  `training` varchar(50) default NULL,
  `usertype` varchar(50) default NULL,
  `contacttype` varchar(50) default NULL,
  `otheremail` varchar(100) default NULL,
  `yahooid` varchar(100) default NULL,
  `donotcall` varchar(3) default NULL,
  `emailoptout` varchar(3) default '0',
  `currency` varchar(20) default 'Dollars',
  PRIMARY KEY  (`contactid`),
  KEY `ContactDetails_IDX1` (`accountid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `contactdetails`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `contactscf`
-- 

CREATE TABLE `contactscf` (
  `contactid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`contactid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `contactscf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `contactsubdetails`
-- 

CREATE TABLE `contactsubdetails` (
  `contactsubscriptionid` int(19) NOT NULL default '0',
  `homephone` varchar(50) default NULL,
  `otherphone` varchar(50) default NULL,
  `assistant` varchar(30) default NULL,
  `assistantphone` varchar(50) default NULL,
  `birthday` date default NULL,
  `laststayintouchrequest` int(30) default '0',
  `laststayintouchsavedate` int(19) default '0',
  `leadsource` varchar(50) default NULL,
  PRIMARY KEY  (`contactsubscriptionid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `contactsubdetails`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `contacttype`
-- 

CREATE TABLE `contacttype` (
  `contacttypeid` int(19) NOT NULL auto_increment,
  `contacttype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`contacttypeid`),
  UNIQUE KEY `ContactType_UK0` (`contacttype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `contacttype`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `contpotentialrel`
-- 

CREATE TABLE `contpotentialrel` (
  `contactid` int(19) NOT NULL default '0',
  `potentialid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`contactid`,`potentialid`),
  KEY `ContPotentialRel_IDX0` (`potentialid`),
  KEY `ContPotentialRel_IDX1` (`contactid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `contpotentialrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `convertleadmapping`
-- 

CREATE TABLE `convertleadmapping` (
  `cfmid` int(19) NOT NULL auto_increment,
  `leadfid` int(19) NOT NULL,
  `accountfid` int(19) default NULL,
  `contactfid` int(19) default NULL,
  `potentialfid` int(19) default NULL,
  PRIMARY KEY  (`cfmid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `convertleadmapping`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `crmentity`
-- 

CREATE TABLE `crmentity` (
  `crmid` int(19) NOT NULL,
  `smcreatorid` int(19) NOT NULL default '0',
  `smownerid` int(19) NOT NULL default '0',
  `modifiedby` int(19) NOT NULL default '0',
  `setype` varchar(30) NOT NULL,
  `description` text,
  `createdtime` datetime NOT NULL,
  `modifiedtime` datetime NOT NULL,
  `viewedtime` datetime default NULL,
  `status` varchar(50) default NULL,
  `version` int(19) NOT NULL default '0',
  `presence` int(1) default '1',
  `deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`crmid`),
  KEY `crmentity_IDX0` (`smcreatorid`),
  KEY `crmentity_IDX1` (`smownerid`),
  KEY `crmentity_IDX2` (`modifiedby`),
  KEY `deleted` (`deleted`,`smownerid`),
  KEY `smownerid` (`smownerid`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `crmentity`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `crmentitynotesrel`
-- 

CREATE TABLE `crmentitynotesrel` (
  `crmid` int(19) NOT NULL default '0',
  `notesid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`crmid`,`notesid`),
  KEY `crmentityNotesRel_IDX0` (`notesid`),
  KEY `crmentityNotesRel_IDX1` (`crmid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `crmentitynotesrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `currency`
-- 

CREATE TABLE `currency` (
  `currencyid` int(19) NOT NULL auto_increment,
  `currency` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`currencyid`),
  UNIQUE KEY `Currency_CRY` (`currency`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `currency`
-- 

INSERT INTO `currency` VALUES (1, 'Rupees', 0, 1);
INSERT INTO `currency` VALUES (2, 'Dollar', 1, 1);
INSERT INTO `currency` VALUES (3, 'Euro', 2, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `currency_info`
-- 

CREATE TABLE `currency_info` (
  `currency_name` varchar(100) NOT NULL,
  `currency_code` varchar(100) default NULL,
  `currency_symbol` varchar(30) default NULL,
  PRIMARY KEY  (`currency_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `currency_info`
-- 

INSERT INTO `currency_info` VALUES ('U.S Dollar', 'USD', '$');

-- --------------------------------------------------------

-- 
-- Structure de la table `customaction`
-- 

CREATE TABLE `customaction` (
  `cvid` int(19) default NULL,
  `subject` varchar(250) NOT NULL,
  `module` varchar(50) NOT NULL,
  `content` text,
  KEY `customaction_IDX0` (`cvid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `customaction`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `customview`
-- 

CREATE TABLE `customview` (
  `cvid` int(19) NOT NULL,
  `viewname` varchar(100) NOT NULL,
  `setdefault` int(1) default '0',
  `setmetrics` int(1) default '0',
  `entitytype` varchar(100) NOT NULL,
  PRIMARY KEY  (`cvid`),
  KEY `customview` (`cvid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `customview`
-- 

INSERT INTO `customview` VALUES (1, 'Hot Leads', 0, 1, 'Leads');
INSERT INTO `customview` VALUES (2, 'This Month Leads', 0, 0, 'Leads');
INSERT INTO `customview` VALUES (3, 'Prospect Accounts', 0, 1, 'Accounts');
INSERT INTO `customview` VALUES (4, 'New This Week', 0, 0, 'Accounts');
INSERT INTO `customview` VALUES (5, 'Contacts Address', 0, 0, 'Contacts');
INSERT INTO `customview` VALUES (6, 'Todays Birthday', 0, 0, 'Contacts');
INSERT INTO `customview` VALUES (7, 'Potentails Won', 0, 1, 'Potentials');
INSERT INTO `customview` VALUES (8, 'Prospecting', 0, 0, 'Potentials');
INSERT INTO `customview` VALUES (9, 'Open Tickets', 0, 1, 'HelpDesk');
INSERT INTO `customview` VALUES (10, 'High Prioriy Tickets', 0, 0, 'HelpDesk');
INSERT INTO `customview` VALUES (11, 'Open Quotes', 0, 1, 'Quotes');
INSERT INTO `customview` VALUES (12, 'Rejected Quotes', 0, 0, 'Quotes');

-- --------------------------------------------------------

-- 
-- Structure de la table `customview_seq`
-- 

CREATE TABLE `customview_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `customview_seq`
-- 

INSERT INTO `customview_seq` VALUES (12);

-- --------------------------------------------------------

-- 
-- Structure de la table `cvadvfilter`
-- 

CREATE TABLE `cvadvfilter` (
  `cvid` int(19) default NULL,
  `columnindex` int(11) NOT NULL,
  `columnname` varchar(250) default '',
  `comparator` varchar(10) default '',
  `value` varchar(200) default '',
  KEY `cvadvfilter_IDX0` (`cvid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `cvadvfilter`
-- 

INSERT INTO `cvadvfilter` VALUES (1, 0, 'leaddetails:leadstatus:leadstatus:Leads_Lead_Status:V', 'e', 'Hot');
INSERT INTO `cvadvfilter` VALUES (3, 0, 'account:account_type:accounttype:Accounts_Type:V', 'e', 'Prospect');
INSERT INTO `cvadvfilter` VALUES (7, 0, 'potential:sales_stage:sales_stage:Potentials_Sales_Stage:V', 'e', 'Closed Won');
INSERT INTO `cvadvfilter` VALUES (8, 0, 'potential:sales_stage:sales_stage:Potentials_Sales_Stage:V', 'e', 'Prospecting');
INSERT INTO `cvadvfilter` VALUES (9, 0, 'troubletickets:status:ticketstatus:HelpDesk_Status:V', 'n', 'Closed');
INSERT INTO `cvadvfilter` VALUES (10, 0, 'troubletickets:priority:ticketpriorities:HelpDesk_Priority:V', 'e', 'High');
INSERT INTO `cvadvfilter` VALUES (11, 0, 'quotes:quotestage:quotestage:Quotes_Quote_Stage:V', 'n', 'Accepted');
INSERT INTO `cvadvfilter` VALUES (11, 1, 'quotes:quotestage:quotestage:Quotes_Quote_Stage:V', 'n', 'Rejected');
INSERT INTO `cvadvfilter` VALUES (12, 0, 'quotes:quotestage:quotestage:Quotes_Quote_Stage:V', 'e', 'Rejected');

-- --------------------------------------------------------

-- 
-- Structure de la table `cvcolumnlist`
-- 

CREATE TABLE `cvcolumnlist` (
  `cvid` int(19) default NULL,
  `columnindex` int(11) NOT NULL,
  `columnname` varchar(250) default '',
  KEY `cvcolumnlist_IDX0` (`cvid`),
  KEY `columnindex` (`columnindex`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `cvcolumnlist`
-- 

INSERT INTO `cvcolumnlist` VALUES (1, 0, 'leaddetails:firstname:firstname:Leads_First_Name:V');
INSERT INTO `cvcolumnlist` VALUES (1, 1, 'leaddetails:lastname:lastname:Leads_Last_Name:V');
INSERT INTO `cvcolumnlist` VALUES (1, 2, 'leaddetails:company:company:Leads_Company:V');
INSERT INTO `cvcolumnlist` VALUES (1, 3, 'leaddetails:leadsource:leadsource:Leads_Lead_Source:V');
INSERT INTO `cvcolumnlist` VALUES (1, 4, 'leadsubdetails:website:website:Leads_Website:V');
INSERT INTO `cvcolumnlist` VALUES (1, 5, 'leaddetails:email:email:Leads_Email:V');
INSERT INTO `cvcolumnlist` VALUES (2, 0, 'leaddetails:firstname:firstname:Leads_First_Name:V');
INSERT INTO `cvcolumnlist` VALUES (2, 1, 'leaddetails:lastname:lastname:Leads_Last_Name:V');
INSERT INTO `cvcolumnlist` VALUES (2, 2, 'leaddetails:company:company:Leads_Company:V');
INSERT INTO `cvcolumnlist` VALUES (2, 3, 'leaddetails:leadsource:leadsource:Leads_Lead_Source:V');
INSERT INTO `cvcolumnlist` VALUES (2, 4, 'leadsubdetails:website:website:Leads_Website:V');
INSERT INTO `cvcolumnlist` VALUES (2, 5, 'leaddetails:email:email:Leads_Email:V');
INSERT INTO `cvcolumnlist` VALUES (3, 0, 'account:accountname:accountname:Accounts_Account_Name:V');
INSERT INTO `cvcolumnlist` VALUES (3, 1, 'account:phone:phone:Accounts_Phone:V');
INSERT INTO `cvcolumnlist` VALUES (3, 2, 'account:website:website:Accounts_Website:V');
INSERT INTO `cvcolumnlist` VALUES (3, 3, 'account:rating:rating:Accounts_Rating:V');
INSERT INTO `cvcolumnlist` VALUES (3, 4, 'crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V');
INSERT INTO `cvcolumnlist` VALUES (4, 0, 'account:accountname:accountname:Accounts_Account_Name:V');
INSERT INTO `cvcolumnlist` VALUES (4, 1, 'account:phone:phone:Accounts_Phone:V');
INSERT INTO `cvcolumnlist` VALUES (4, 2, 'account:website:website:Accounts_Website:V');
INSERT INTO `cvcolumnlist` VALUES (4, 3, 'accountbillads:city:bill_city:Accounts_City:V');
INSERT INTO `cvcolumnlist` VALUES (4, 4, 'crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V');
INSERT INTO `cvcolumnlist` VALUES (5, 0, 'contactdetails:firstname:firstname:Contacts_First_Name:V');
INSERT INTO `cvcolumnlist` VALUES (5, 1, 'contactdetails:lastname:lastname:Contacts_Last_Name:V');
INSERT INTO `cvcolumnlist` VALUES (5, 2, 'contactaddress:mailingstreet:mailingstreet:Contacts_Mailing_Street:V');
INSERT INTO `cvcolumnlist` VALUES (5, 3, 'contactaddress:mailingcity:mailingcity:Contacts_City:V');
INSERT INTO `cvcolumnlist` VALUES (5, 4, 'contactaddress:mailingstate:mailingstate:Contacts_State:V');
INSERT INTO `cvcolumnlist` VALUES (5, 5, 'contactaddress:mailingzip:mailingzip:Contacts_Zip:V');
INSERT INTO `cvcolumnlist` VALUES (5, 6, 'contactaddress:mailingcountry:mailingcountry:Contacts_Country:V');
INSERT INTO `cvcolumnlist` VALUES (6, 0, 'contactdetails:firstname:firstname:Contacts_First_Name:V');
INSERT INTO `cvcolumnlist` VALUES (6, 1, 'contactdetails:lastname:lastname:Contacts_Last_Name:V');
INSERT INTO `cvcolumnlist` VALUES (6, 2, 'contactdetails:title:title:Contacts_Title:V');
INSERT INTO `cvcolumnlist` VALUES (6, 3, 'contactdetails:accountid:account_id:Contacts_Account_Name:I');
INSERT INTO `cvcolumnlist` VALUES (6, 4, 'contactdetails:email:email:Contacts_Email:V');
INSERT INTO `cvcolumnlist` VALUES (6, 5, 'contactsubdetails:otherphone:otherphone:Contacts_Phone:V');
INSERT INTO `cvcolumnlist` VALUES (6, 6, 'crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V');
INSERT INTO `cvcolumnlist` VALUES (7, 0, 'potential:potentialname:potentialname:Potentials_Potential_Name:V');
INSERT INTO `cvcolumnlist` VALUES (7, 1, 'potential:accountid:account_id:Potentials_Account_Name:V');
INSERT INTO `cvcolumnlist` VALUES (7, 2, 'potential:amount:amount:Potentials_Amount:N');
INSERT INTO `cvcolumnlist` VALUES (7, 3, 'potential:leadsource:leadsource:Potentials_Lead_Source:V');
INSERT INTO `cvcolumnlist` VALUES (7, 4, 'potential:closingdate:closingdate:Potentials_Expected_Close_Date:D');
INSERT INTO `cvcolumnlist` VALUES (7, 5, 'crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V');
INSERT INTO `cvcolumnlist` VALUES (8, 0, 'potential:potentialname:potentialname:Potentials_Potential_Name:V');
INSERT INTO `cvcolumnlist` VALUES (8, 1, 'potential:accountid:account_id:Potentials_Account_Name:V');
INSERT INTO `cvcolumnlist` VALUES (8, 2, 'potential:amount:amount:Potentials_Amount:N');
INSERT INTO `cvcolumnlist` VALUES (8, 3, 'potential:leadsource:leadsource:Potentials_Lead_Source:V');
INSERT INTO `cvcolumnlist` VALUES (8, 4, 'potential:closingdate:closingdate:Potentials_Expected_Close_Date:D');
INSERT INTO `cvcolumnlist` VALUES (8, 5, 'crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V');
INSERT INTO `cvcolumnlist` VALUES (9, 0, 'troubletickets:title:ticket_title:HelpDesk_Title:V');
INSERT INTO `cvcolumnlist` VALUES (9, 1, 'troubletickets:parent_id:parent_id:HelpDesk_Related_to:I');
INSERT INTO `cvcolumnlist` VALUES (9, 2, 'troubletickets:priority:ticketpriorities:HelpDesk_Priority:V');
INSERT INTO `cvcolumnlist` VALUES (9, 3, 'troubletickets:product_id:product_id:HelpDesk_Product_Name:I');
INSERT INTO `cvcolumnlist` VALUES (9, 4, 'crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V');
INSERT INTO `cvcolumnlist` VALUES (10, 0, 'troubletickets:title:ticket_title:HelpDesk_Title:V');
INSERT INTO `cvcolumnlist` VALUES (10, 1, 'troubletickets:parent_id:parent_id:HelpDesk_Related_to:I');
INSERT INTO `cvcolumnlist` VALUES (10, 2, 'troubletickets:status:ticketstatus:HelpDesk_Status:V');
INSERT INTO `cvcolumnlist` VALUES (10, 3, 'troubletickets:product_id:product_id:HelpDesk_Product_Name:I');
INSERT INTO `cvcolumnlist` VALUES (10, 4, 'crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V');
INSERT INTO `cvcolumnlist` VALUES (11, 0, 'quotes:subject:subject:Quotes_Subject:V');
INSERT INTO `cvcolumnlist` VALUES (11, 1, 'quotes:quotestage:quotestage:Quotes_Quote_Stage:V');
INSERT INTO `cvcolumnlist` VALUES (11, 2, 'quotes:potentialid:potential_id:Quotes_Potential_Name:I');
INSERT INTO `cvcolumnlist` VALUES (11, 3, 'quotes:accountid:account_id:Quotes_Account_Name:I');
INSERT INTO `cvcolumnlist` VALUES (11, 4, 'quotes:validtill:validtill:Quotes_Valid_Till:D');
INSERT INTO `cvcolumnlist` VALUES (11, 5, 'crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V');
INSERT INTO `cvcolumnlist` VALUES (12, 0, 'quotes:subject:subject:Quotes_Subject:V');
INSERT INTO `cvcolumnlist` VALUES (12, 1, 'quotes:potentialid:potential_id:Quotes_Potential_Name:I');
INSERT INTO `cvcolumnlist` VALUES (12, 2, 'quotes:accountid:account_id:Quotes_Account_Name:I');
INSERT INTO `cvcolumnlist` VALUES (12, 3, 'quotes:validtill:validtill:Quotes_Valid_Till:D');
INSERT INTO `cvcolumnlist` VALUES (12, 4, 'crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V');

-- --------------------------------------------------------

-- 
-- Structure de la table `def_org_field`
-- 

CREATE TABLE `def_org_field` (
  `tabid` int(10) default NULL,
  `fieldid` int(19) default NULL,
  `visible` int(19) default NULL,
  `readonly` int(19) default NULL,
  KEY `idx_def_org_field` (`tabid`,`fieldid`),
  KEY `tabid` (`tabid`),
  KEY `visible` (`visible`,`fieldid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `def_org_field`
-- 

INSERT INTO `def_org_field` VALUES (6, 1, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 2, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 3, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 4, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 5, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 6, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 7, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 8, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 9, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 10, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 11, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 12, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 13, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 14, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 15, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 16, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 17, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 18, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 19, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 20, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 21, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 22, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 23, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 24, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 25, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 26, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 27, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 28, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 29, 0, 1);
INSERT INTO `def_org_field` VALUES (6, 30, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 32, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 33, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 34, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 35, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 36, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 37, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 38, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 39, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 40, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 41, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 42, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 43, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 44, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 45, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 46, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 47, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 48, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 49, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 50, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 51, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 52, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 53, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 54, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 55, 0, 1);
INSERT INTO `def_org_field` VALUES (7, 56, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 58, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 59, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 60, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 61, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 62, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 63, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 64, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 65, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 66, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 67, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 68, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 69, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 70, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 71, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 72, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 73, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 74, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 75, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 76, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 77, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 78, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 79, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 80, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 81, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 82, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 83, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 84, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 85, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 86, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 87, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 88, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 89, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 90, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 91, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 92, 0, 1);
INSERT INTO `def_org_field` VALUES (4, 93, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 94, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 95, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 96, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 97, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 98, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 99, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 100, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 101, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 102, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 103, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 104, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 105, 0, 1);
INSERT INTO `def_org_field` VALUES (2, 106, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 107, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 108, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 109, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 110, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 111, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 112, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 113, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 115, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 116, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 117, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 118, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 119, 0, 1);
INSERT INTO `def_org_field` VALUES (13, 120, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 121, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 122, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 123, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 124, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 125, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 126, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 127, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 128, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 129, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 130, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 131, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 132, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 133, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 134, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 135, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 136, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 137, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 138, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 139, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 140, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 141, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 142, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 143, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 144, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 145, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 146, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 147, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 148, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 149, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 150, 0, 1);
INSERT INTO `def_org_field` VALUES (14, 151, 0, 1);
INSERT INTO `def_org_field` VALUES (8, 152, 0, 1);
INSERT INTO `def_org_field` VALUES (8, 153, 0, 1);
INSERT INTO `def_org_field` VALUES (8, 154, 0, 1);
INSERT INTO `def_org_field` VALUES (8, 155, 0, 1);
INSERT INTO `def_org_field` VALUES (8, 156, 0, 1);
INSERT INTO `def_org_field` VALUES (8, 157, 0, 1);
INSERT INTO `def_org_field` VALUES (8, 158, 0, 1);
INSERT INTO `def_org_field` VALUES (10, 159, 0, 1);
INSERT INTO `def_org_field` VALUES (10, 162, 0, 1);
INSERT INTO `def_org_field` VALUES (10, 163, 0, 1);
INSERT INTO `def_org_field` VALUES (10, 164, 0, 1);
INSERT INTO `def_org_field` VALUES (10, 165, 0, 1);
INSERT INTO `def_org_field` VALUES (10, 166, 0, 1);
INSERT INTO `def_org_field` VALUES (10, 168, 0, 1);
INSERT INTO `def_org_field` VALUES (10, 169, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 170, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 171, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 172, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 174, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 175, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 176, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 177, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 179, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 180, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 181, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 182, 0, 1);
INSERT INTO `def_org_field` VALUES (9, 184, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 190, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 191, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 192, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 194, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 195, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 196, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 198, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 199, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 200, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 201, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 202, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 203, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 204, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 205, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 206, 0, 1);
INSERT INTO `def_org_field` VALUES (16, 207, 0, 1);
INSERT INTO `def_org_field` VALUES (15, 208, 0, 1);
INSERT INTO `def_org_field` VALUES (15, 209, 0, 1);
INSERT INTO `def_org_field` VALUES (15, 210, 0, 1);
INSERT INTO `def_org_field` VALUES (15, 211, 0, 1);
INSERT INTO `def_org_field` VALUES (15, 212, 0, 1);
INSERT INTO `def_org_field` VALUES (15, 213, 0, 1);
INSERT INTO `def_org_field` VALUES (15, 214, 0, 1);
INSERT INTO `def_org_field` VALUES (15, 215, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 216, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 217, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 218, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 219, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 220, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 221, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 222, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 223, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 224, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 225, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 226, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 227, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 228, 0, 1);
INSERT INTO `def_org_field` VALUES (18, 229, 0, 1);
INSERT INTO `def_org_field` VALUES (19, 230, 0, 1);
INSERT INTO `def_org_field` VALUES (19, 231, 0, 1);
INSERT INTO `def_org_field` VALUES (19, 232, 0, 1);
INSERT INTO `def_org_field` VALUES (19, 233, 0, 1);
INSERT INTO `def_org_field` VALUES (19, 234, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 235, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 236, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 237, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 238, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 239, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 240, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 241, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 243, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 244, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 248, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 249, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 250, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 251, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 252, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 253, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 254, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 255, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 256, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 257, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 258, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 259, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 260, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 261, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 262, 0, 1);
INSERT INTO `def_org_field` VALUES (20, 263, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 264, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 265, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 266, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 267, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 268, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 269, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 270, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 273, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 274, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 277, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 278, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 279, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 280, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 281, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 282, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 283, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 284, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 285, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 286, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 287, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 288, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 289, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 290, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 291, 0, 1);
INSERT INTO `def_org_field` VALUES (21, 292, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 293, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 294, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 295, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 296, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 297, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 298, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 299, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 300, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 301, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 302, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 305, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 306, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 309, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 310, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 311, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 312, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 313, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 314, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 315, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 316, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 317, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 318, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 319, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 320, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 321, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 322, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 323, 0, 1);
INSERT INTO `def_org_field` VALUES (22, 324, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 325, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 326, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 327, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 328, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 329, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 330, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 333, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 334, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 337, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 338, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 339, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 340, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 341, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 342, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 343, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 344, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 345, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 346, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 347, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 348, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 349, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 350, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 351, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 352, 0, 1);
INSERT INTO `def_org_field` VALUES (23, 353, 0, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `def_org_share`
-- 

CREATE TABLE `def_org_share` (
  `ruleid` int(11) NOT NULL auto_increment,
  `tabid` int(11) NOT NULL,
  `permission` int(10) default NULL,
  PRIMARY KEY  (`ruleid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- 
-- Contenu de la table `def_org_share`
-- 

INSERT INTO `def_org_share` VALUES (1, 2, 2);
INSERT INTO `def_org_share` VALUES (2, 4, 2);
INSERT INTO `def_org_share` VALUES (3, 6, 2);
INSERT INTO `def_org_share` VALUES (4, 7, 2);
INSERT INTO `def_org_share` VALUES (5, 8, 2);
INSERT INTO `def_org_share` VALUES (6, 9, 2);
INSERT INTO `def_org_share` VALUES (7, 10, 2);
INSERT INTO `def_org_share` VALUES (8, 13, 2);
INSERT INTO `def_org_share` VALUES (9, 14, 2);
INSERT INTO `def_org_share` VALUES (10, 15, 2);
INSERT INTO `def_org_share` VALUES (11, 16, 2);
INSERT INTO `def_org_share` VALUES (12, 18, 2);
INSERT INTO `def_org_share` VALUES (13, 19, 2);
INSERT INTO `def_org_share` VALUES (14, 20, 2);
INSERT INTO `def_org_share` VALUES (15, 21, 2);
INSERT INTO `def_org_share` VALUES (16, 22, 2);
INSERT INTO `def_org_share` VALUES (17, 23, 2);

-- --------------------------------------------------------

-- 
-- Structure de la table `def_org_share_seq`
-- 

CREATE TABLE `def_org_share_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `def_org_share_seq`
-- 

INSERT INTO `def_org_share_seq` VALUES (17);

-- --------------------------------------------------------

-- 
-- Structure de la table `defaultcv`
-- 

CREATE TABLE `defaultcv` (
  `tabid` int(19) NOT NULL,
  `defaultviewname` varchar(50) NOT NULL,
  `query` text,
  PRIMARY KEY  (`tabid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `defaultcv`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `downloadpurpose`
-- 

CREATE TABLE `downloadpurpose` (
  `downloadpurposeid` int(19) NOT NULL auto_increment,
  `purpose` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`downloadpurposeid`),
  UNIQUE KEY `DownloadPurpose_UK0` (`purpose`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `downloadpurpose`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `duration_minutes`
-- 

CREATE TABLE `duration_minutes` (
  `minutesid` int(19) NOT NULL auto_increment,
  `duration_minutes` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`minutesid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `duration_minutes`
-- 

INSERT INTO `duration_minutes` VALUES (1, '00', 0, 1);
INSERT INTO `duration_minutes` VALUES (2, '15', 1, 1);
INSERT INTO `duration_minutes` VALUES (3, '30', 2, 1);
INSERT INTO `duration_minutes` VALUES (4, '45', 3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `durationhrs`
-- 

CREATE TABLE `durationhrs` (
  `hrsid` int(19) NOT NULL auto_increment,
  `hrs` varchar(50) default NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`hrsid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `durationhrs`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `durationmins`
-- 

CREATE TABLE `durationmins` (
  `minsid` int(19) NOT NULL auto_increment,
  `mins` varchar(50) default NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`minsid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `durationmins`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `emails`
-- 

CREATE TABLE `emails` (
  `emailid` int(19) NOT NULL,
  `filename` varchar(50) default NULL,
  `description` text,
  PRIMARY KEY  (`emailid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `emails`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `emailtemplates`
-- 

CREATE TABLE `emailtemplates` (
  `foldername` varchar(100) default NULL,
  `templatename` varchar(100) default NULL,
  `subject` varchar(100) default NULL,
  `description` text,
  `body` text,
  `deleted` int(1) NOT NULL default '0',
  `templateid` int(19) NOT NULL auto_increment,
  PRIMARY KEY  (`templateid`),
  KEY `idx_emailtemplates` (`foldername`,`templatename`,`subject`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Contenu de la table `emailtemplates`
-- 

INSERT INTO `emailtemplates` VALUES ('Public', 'Announcement for Release', 'Announcement for Release', 'Announcement of a release', '\n	Hello!\n\n	On behalf of the vtiger team,  I am pleased to announce the release of vtiger crm4.2 . This is a feature packed release including the mass email template handling, custom view feature, reports feature and a host of other utilities. vtiger runs on all platforms.\n\n	Notable Features of vtiger are :\n	-Email Client Integration\n	-Trouble Ticket Integration\n	-Invoice Management Integration\n	-Reports Integration\n	-Portal Integration\n	-Enhanced Word Plugin Support\n	-Custom View Integration\n\n	Known Issues:\n	-ABCD\n	-EFGH\n	-IJKL\n	-MNOP\n	-QRST', 0, 1);
INSERT INTO `emailtemplates` VALUES ('Public', 'Pending Invoices', 'Invoices Pending', 'Payment Due', 'name\nstreet,\ncity,\nstate,\n zip)\n \n Dear\n \n Please check the following invoices that are yet to be paid by you:\n \n No. Date      Amount\n 1   1/1/01    $4000\n 2   2/2//01   $5000\n 3   3/3/01    $10000\n 4   7/4/01    $23560\n \n Kindly let us know if there are any issues that you feel are pending to be discussed.\n We will be more than happy to give you a call.\n We would like to continue our business with you.\n \n Sincerely,\n name\n title', 0, 2);
INSERT INTO `emailtemplates` VALUES ('Public', 'Acceptance Proposal', 'Acceptance Proposal', 'Acceptance of Proposal', ' Dear\n\nYour proposal on the project XYZW has been reviewed by us\nand is acceptable in its entirety.\n\nWe are eagerly looking forward to this project\nand are pleased about having the opportunity to work\ntogether. We look forward to a long standing relationship\nwith your esteemed firm.\n\nI would like to take this opportunity to invite you\nto a game of golf on Wednesday morning 9am at the\nCuff Links Ground. We will be waiting for you in the\nExecutive Lounge.\n\nLooking forward to seeing you there.\n\nSincerely,\nname\ntitle', 0, 3);
INSERT INTO `emailtemplates` VALUES ('Public', 'Good received acknowledgement', 'Goods received acknowledgement', 'Acknowledged Receipt of Goods', ' The undersigned hereby acknowledges receipt and delivery\nof the goods.\nThe undersigned will release the payment subject to the goods being discovered not satisfactory.\n\nSigned under seal this <date>\n\nSincerely,\nname\ntitle', 0, 4);
INSERT INTO `emailtemplates` VALUES ('Public', 'Accept Order', 'Accept Order', 'Acknowledgement/Acceptance of Order', ' Dear\n	 We are in receipt of your order as contained in the\n   purchase order form.We consider this to be final and binding on both sides.\nIf there be any exceptions noted, we shall consider them\nonly if the objection is received within ten days of receipt of\nthis notice.\n\nThank you for your patronage.\nSincerely,\nname\ntitle', 0, 5);
INSERT INTO `emailtemplates` VALUES ('Public', 'Address Change', 'Change of Address', 'Address Change', 'Dear\n\nWe are relocating our office to\n11111,XYZDEF Cross,\nUVWWX Circle\nThe telephone number for this new location is (101) 1212-1328.\n\nOur Manufacturing Division will continue operations\nat 3250 Lovedale Square Avenue, in Frankfurt.\n\nWe hope to keep in touch with you all.\nPlease update your addressbooks.\n\n\nThank You,\nname\ntitle', 0, 6);
INSERT INTO `emailtemplates` VALUES ('Public', 'Follow Up', 'Follow Up', 'Follow Up of meeting', 'Dear\n\nThank you for extending us the opportunity to meet with\nyou and members of your staff.\n\nI know that John Doe serviced your account\nfor many years and made many friends at your firm. He has personally\ndiscussed with me the deep relationship that he had with your firm.\nWhile his presence will be missed, I can promise that we will\ncontinue to provide the fine service that was accorded by\nJohn to your firm.\n\nI was genuinely touched to receive such fine hospitality.\n\nThank you once again.\n\nSincerely,\nname\ntitle', 0, 7);
INSERT INTO `emailtemplates` VALUES ('Public', 'Target Crossed!', 'Target Crossed!', 'Fantastic Sales Spree!', 'Congratulations!\n\nThe numbers are in and I am proud to inform you that our\ntotal sales for the previous quarter\namounts to $100,000,00.00!. This is the first time\nwe have exceeded the target by almost 30%.\nWe have also beat the previous quarter record by a\nwhopping 75%!\n\nLet us meet at Smoking Joe for a drink in the evening!\n\nC you all there guys!\n\nSincerely,\nname\ntitle', 0, 8);
INSERT INTO `emailtemplates` VALUES ('Public', 'Thanks Note', 'Thanks Note', 'Note of thanks', '\nDear\n\nThank you for your confidence in our ability to serve you.\nWe are glad to be given the chance to serve you.I look\nforward to establishing a long term partnership with you.\nConsider me as a friend.\nShould any need arise,please do give us a call.\n\nSincerely,\nname\ntitle', 0, 9);

-- --------------------------------------------------------

-- 
-- Structure de la table `emailtemplates_seq`
-- 

CREATE TABLE `emailtemplates_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `emailtemplates_seq`
-- 

INSERT INTO `emailtemplates_seq` VALUES (9);

-- --------------------------------------------------------

-- 
-- Structure de la table `evaluationstatus`
-- 

CREATE TABLE `evaluationstatus` (
  `evalstatusid` int(19) NOT NULL auto_increment,
  `status` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`evalstatusid`),
  UNIQUE KEY `EvaluationStatus_UK0` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `evaluationstatus`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `eventstatus`
-- 

CREATE TABLE `eventstatus` (
  `eventstatusid` int(19) NOT NULL auto_increment,
  `eventstatus` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`eventstatusid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `eventstatus`
-- 

INSERT INTO `eventstatus` VALUES (1, 'Planned', 0, 1);
INSERT INTO `eventstatus` VALUES (2, 'Held', 1, 1);
INSERT INTO `eventstatus` VALUES (3, 'Not Held', 2, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `faq`
-- 

CREATE TABLE `faq` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` varchar(100) default NULL,
  `question` text,
  `answer` text,
  `category` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `faq_IDX0` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `faq`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `faqcategories`
-- 

CREATE TABLE `faqcategories` (
  `faqcategories_id` int(19) NOT NULL auto_increment,
  `faqcategories` varchar(60) default NULL,
  `SORTORDERID` int(19) NOT NULL default '0',
  `PRESENCE` int(1) NOT NULL default '1',
  PRIMARY KEY  (`faqcategories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `faqcategories`
-- 

INSERT INTO `faqcategories` VALUES (1, 'General', 0, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `faqcomments`
-- 

CREATE TABLE `faqcomments` (
  `commentid` int(19) NOT NULL auto_increment,
  `faqid` int(19) default NULL,
  `comments` text,
  `createdtime` datetime NOT NULL,
  PRIMARY KEY  (`commentid`),
  KEY `faqcomments_IDX0` (`faqid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `faqcomments`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `faqstatus`
-- 

CREATE TABLE `faqstatus` (
  `faqstatus_id` int(19) NOT NULL auto_increment,
  `faqstatus` varchar(60) default NULL,
  `SORTORDERID` int(19) NOT NULL default '0',
  `PRESENCE` int(1) NOT NULL default '1',
  PRIMARY KEY  (`faqstatus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `faqstatus`
-- 

INSERT INTO `faqstatus` VALUES (1, 'Draft', 0, 1);
INSERT INTO `faqstatus` VALUES (2, 'Reviewed', 1, 1);
INSERT INTO `faqstatus` VALUES (3, 'Published', 2, 1);
INSERT INTO `faqstatus` VALUES (4, 'Obsolete', 3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `field`
-- 

CREATE TABLE `field` (
  `tabid` int(19) NOT NULL,
  `fieldid` int(19) NOT NULL auto_increment,
  `columnname` varchar(30) NOT NULL,
  `tablename` varchar(50) NOT NULL,
  `generatedtype` int(19) NOT NULL default '0',
  `uitype` varchar(30) NOT NULL,
  `fieldname` varchar(50) NOT NULL,
  `fieldlabel` varchar(50) NOT NULL,
  `readonly` int(1) NOT NULL,
  `presence` int(19) NOT NULL default '1',
  `selected` int(1) NOT NULL,
  `maximumlength` int(19) default NULL,
  `sequence` int(19) default NULL,
  `block` int(19) default NULL,
  `displaytype` int(19) default NULL,
  `typeofdata` varchar(100) default NULL,
  PRIMARY KEY  (`fieldid`),
  KEY `Field_IDX0` (`tabid`),
  KEY `fieldname` (`fieldname`),
  KEY `tabid` (`tabid`,`block`,`displaytype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=354 ;

-- 
-- Contenu de la table `field`
-- 

INSERT INTO `field` VALUES (6, 1, 'accountname', 'account', 1, '2', 'accountname', 'Account Name', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (6, 2, 'phone', 'account', 1, '11', 'phone', 'Phone', 1, 0, 0, 100, 2, 1, 1, 'V~O');
INSERT INTO `field` VALUES (6, 3, 'website', 'account', 1, '17', 'website', 'Website', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (6, 4, 'fax', 'account', 1, '1', 'fax', 'Fax', 1, 0, 0, 100, 4, 1, 1, 'V~O');
INSERT INTO `field` VALUES (6, 5, 'tickersymbol', 'account', 1, '1', 'tickersymbol', 'Ticker Symbol', 1, 0, 0, 100, 5, 1, 1, 'V~O');
INSERT INTO `field` VALUES (6, 6, 'otherphone', 'account', 1, '11', 'otherphone', 'Other Phone', 1, 0, 0, 100, 6, 1, 1, 'V~O');
INSERT INTO `field` VALUES (6, 7, 'parentid', 'account', 1, '51', 'account_id', 'Member Of', 1, 0, 0, 100, 7, 1, 1, 'I~O');
INSERT INTO `field` VALUES (6, 8, 'email1', 'account', 1, '13', 'email1', 'Email', 1, 0, 0, 100, 8, 1, 1, 'E~O');
INSERT INTO `field` VALUES (6, 9, 'employees', 'account', 1, '7', 'employees', 'Employees', 1, 0, 0, 100, 9, 1, 1, 'I~O');
INSERT INTO `field` VALUES (6, 10, 'email2', 'account', 1, '13', 'email2', 'Other Email', 1, 0, 0, 100, 10, 1, 1, 'E~O');
INSERT INTO `field` VALUES (6, 11, 'ownership', 'account', 1, '1', 'ownership', 'Ownership', 1, 0, 0, 100, 11, 1, 1, 'V~O');
INSERT INTO `field` VALUES (6, 12, 'rating', 'account', 1, '1', 'rating', 'Rating', 1, 0, 0, 100, 12, 1, 1, 'V~O');
INSERT INTO `field` VALUES (6, 13, 'industry', 'account', 1, '15', 'industry', 'industry', 1, 0, 0, 100, 13, 1, 1, 'V~O');
INSERT INTO `field` VALUES (6, 14, 'siccode', 'account', 1, '1', 'siccode', 'SIC Code', 1, 0, 0, 100, 14, 1, 1, 'I~O');
INSERT INTO `field` VALUES (6, 15, 'account_type', 'account', 1, '15', 'accounttype', 'Type', 1, 0, 0, 100, 15, 1, 1, 'V~O');
INSERT INTO `field` VALUES (6, 16, 'annualrevenue', 'account', 1, '71', 'annual_revenue', 'Annual Revenue', 1, 0, 0, 100, 16, 1, 1, 'I~O');
INSERT INTO `field` VALUES (6, 17, 'smownerid', 'crmentity', 1, '52', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 17, 1, 1, 'V~M');
INSERT INTO `field` VALUES (6, 18, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 18, 1, 2, 'T~O');
INSERT INTO `field` VALUES (6, 19, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 18, 1, 2, 'T~O');
INSERT INTO `field` VALUES (6, 20, 'street', 'accountbillads', 1, '21', 'bill_street', 'Billing Address', 1, 0, 0, 100, 1, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 21, 'street', 'accountshipads', 1, '21', 'ship_street', 'Shipping Address', 1, 0, 0, 100, 2, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 22, 'city', 'accountbillads', 1, '1', 'bill_city', 'Billing City', 1, 0, 0, 100, 3, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 23, 'city', 'accountshipads', 1, '1', 'ship_city', 'Shipping City', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 24, 'state', 'accountbillads', 1, '1', 'bill_state', 'Billing State', 1, 0, 0, 100, 5, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 25, 'state', 'accountshipads', 1, '1', 'ship_state', 'Shipping State', 1, 0, 0, 100, 6, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 26, 'code', 'accountbillads', 1, '1', 'bill_code', 'Billing Code', 1, 0, 0, 100, 7, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 27, 'code', 'accountshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 0, 0, 100, 8, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 28, 'country', 'accountbillads', 1, '1', 'bill_country', 'Billing Country', 1, 0, 0, 100, 9, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 29, 'country', 'accountshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 0, 0, 100, 10, 2, 1, 'V~O');
INSERT INTO `field` VALUES (6, 30, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (7, 31, 'salutation', 'leaddetails', 1, '55', 'salutationtype', 'Salutation', 1, 0, 0, 100, 1, 1, 3, 'V~O');
INSERT INTO `field` VALUES (7, 32, 'firstname', 'leaddetails', 1, '55', 'firstname', 'First Name', 1, 0, 0, 100, 2, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 33, 'phone', 'leadaddress', 1, '11', 'phone', 'Phone', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 34, 'lastname', 'leaddetails', 1, '2', 'lastname', 'Last Name', 1, 0, 0, 100, 4, 1, 1, 'V~M');
INSERT INTO `field` VALUES (7, 35, 'mobile', 'leadaddress', 1, '1', 'mobile', 'Mobile', 1, 0, 0, 100, 5, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 36, 'company', 'leaddetails', 1, '2', 'company', 'Company', 1, 0, 0, 100, 6, 1, 1, 'V~M');
INSERT INTO `field` VALUES (7, 37, 'fax', 'leadaddress', 1, '1', 'fax', 'Fax', 1, 0, 0, 100, 7, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 38, 'designation', 'leaddetails', 1, '1', 'designation', 'Designation', 1, 0, 0, 100, 8, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 39, 'email', 'leaddetails', 1, '13', 'email', 'Email', 1, 0, 0, 100, 9, 1, 1, 'E~O');
INSERT INTO `field` VALUES (7, 40, 'leadsource', 'leaddetails', 1, '15', 'leadsource', 'Lead Source', 1, 0, 0, 100, 10, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 41, 'website', 'leadsubdetails', 1, '17', 'website', 'Website', 1, 0, 0, 100, 11, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 42, 'industry', 'leaddetails', 1, '15', 'industry', 'Industry', 1, 0, 0, 100, 12, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 43, 'leadstatus', 'leaddetails', 1, '15', 'leadstatus', 'Lead Status', 1, 0, 0, 100, 13, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 44, 'annualrevenue', 'leaddetails', 1, '71', 'annualrevenue', 'Annual Revenue', 1, 0, 0, 100, 14, 1, 1, 'I~O');
INSERT INTO `field` VALUES (7, 45, 'rating', 'leaddetails', 1, '15', 'rating', 'Rating', 1, 0, 0, 100, 15, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 46, 'noofemployees', 'leaddetails', 1, '1', 'noofemployees', 'No Of Employees', 1, 0, 0, 100, 16, 1, 1, 'I~O');
INSERT INTO `field` VALUES (7, 47, 'smownerid', 'crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 17, 1, 1, 'V~M');
INSERT INTO `field` VALUES (7, 48, 'yahooid', 'leaddetails', 1, '13', 'yahooid', 'Yahoo Id', 1, 0, 0, 100, 18, 1, 1, 'V~O');
INSERT INTO `field` VALUES (7, 49, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 19, 1, 2, 'T~O');
INSERT INTO `field` VALUES (7, 50, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 20, 1, 2, 'T~O');
INSERT INTO `field` VALUES (7, 51, 'lane', 'leadaddress', 1, '21', 'lane', 'Street', 1, 0, 0, 100, 1, 2, 1, 'V~O');
INSERT INTO `field` VALUES (7, 52, 'code', 'leadaddress', 1, '1', 'code', 'Postal Code', 1, 0, 0, 100, 2, 2, 1, 'V~O');
INSERT INTO `field` VALUES (7, 53, 'city', 'leadaddress', 1, '1', 'city', 'City', 1, 0, 0, 100, 3, 2, 1, 'V~O');
INSERT INTO `field` VALUES (7, 54, 'country', 'leadaddress', 1, '1', 'country', 'Country', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (7, 55, 'state', 'leadaddress', 1, '1', 'state', 'State', 1, 0, 0, 100, 5, 2, 1, 'V~O');
INSERT INTO `field` VALUES (7, 56, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (4, 57, 'salutation', 'contactdetails', 1, '55', 'salutationtype', 'Salutation', 1, 0, 0, 100, 1, 1, 3, 'V~O');
INSERT INTO `field` VALUES (4, 58, 'firstname', 'contactdetails', 1, '55', 'firstname', 'First Name', 1, 0, 0, 100, 2, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 59, 'phone', 'contactdetails', 1, '11', 'phone', 'Office Phone', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 60, 'lastname', 'contactdetails', 1, '2', 'lastname', 'Last Name', 1, 0, 0, 100, 4, 1, 1, 'V~M');
INSERT INTO `field` VALUES (4, 61, 'mobile', 'contactdetails', 1, '1', 'mobile', 'Mobile', 1, 0, 0, 100, 5, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 62, 'accountid', 'contactdetails', 1, '51', 'account_id', 'Account Name', 1, 0, 0, 100, 6, 1, 1, 'I~O');
INSERT INTO `field` VALUES (4, 63, 'homephone', 'contactsubdetails', 1, '11', 'homephone', 'Home Phone', 1, 0, 0, 100, 7, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 64, 'leadsource', 'contactsubdetails', 1, '15', 'leadsource', 'Lead Source', 1, 0, 0, 100, 8, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 65, 'otherphone', 'contactsubdetails', 1, '11', 'otherphone', 'Phone', 1, 0, 0, 100, 9, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 66, 'title', 'contactdetails', 1, '1', 'title', 'Title', 1, 0, 0, 100, 10, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 67, 'fax', 'contactdetails', 1, '1', 'fax', 'Fax', 1, 0, 0, 100, 11, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 68, 'department', 'contactdetails', 1, '1', 'department', 'Department', 1, 0, 0, 100, 12, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 69, 'birthday', 'contactsubdetails', 1, '5', 'birthday', 'Birthdate', 1, 0, 0, 100, 14, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 70, 'email', 'contactdetails', 1, '13', 'email', 'Email', 1, 0, 0, 100, 15, 1, 1, 'E~O');
INSERT INTO `field` VALUES (4, 71, 'reportsto', 'contactdetails', 1, '57', 'contact_id', 'Reports To', 1, 0, 0, 100, 16, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 72, 'assistant', 'contactsubdetails', 1, '1', 'assistant', 'Assistant', 1, 0, 0, 100, 17, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 73, 'yahooid', 'contactdetails', 1, '13', 'yahooid', 'Yahoo Id', 1, 0, 0, 100, 18, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 74, 'assistantphone', 'contactsubdetails', 1, '11', 'assistantphone', 'Assistant Phone', 1, 0, 0, 100, 19, 1, 1, 'V~O');
INSERT INTO `field` VALUES (4, 75, 'donotcall', 'contactdetails', 1, '56', 'donotcall', 'Do Not Call', 1, 0, 0, 100, 20, 1, 1, 'C~O');
INSERT INTO `field` VALUES (4, 76, 'emailoptout', 'contactdetails', 1, '56', 'emailoptout', 'Email Opt Out', 1, 0, 0, 100, 21, 1, 1, 'C~O');
INSERT INTO `field` VALUES (4, 77, 'smownerid', 'crmentity', 1, '52', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 22, 1, 1, 'V~M');
INSERT INTO `field` VALUES (4, 78, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 23, 1, 2, 'T~O');
INSERT INTO `field` VALUES (4, 79, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 24, 1, 2, 'T~O');
INSERT INTO `field` VALUES (4, 80, 'portal', 'CustomerDetails', 1, '56', 'portal', 'Portal User', 1, 0, 0, 100, 1, 4, 1, 'C~O');
INSERT INTO `field` VALUES (4, 81, 'support_start_date', 'CustomerDetails', 1, '5', 'support_start_date', 'Support Start Date', 1, 0, 0, 100, 2, 4, 1, 'D~O');
INSERT INTO `field` VALUES (4, 82, 'support_end_date', 'CustomerDetails', 1, '5', 'support_end_date', 'Support End Date', 1, 0, 0, 100, 3, 4, 1, 'D~O~OTH~GE~support_start_date~Support Start Date');
INSERT INTO `field` VALUES (4, 83, 'mailingstreet', 'contactaddress', 1, '21', 'mailingstreet', 'Mailing Street', 1, 0, 0, 100, 1, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 84, 'otherstreet', 'contactaddress', 1, '21', 'otherstreet', 'Other Street', 1, 0, 0, 100, 2, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 85, 'mailingcity', 'contactaddress', 1, '1', 'mailingcity', 'Mailing City', 1, 0, 0, 100, 3, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 86, 'othercity', 'contactaddress', 1, '1', 'othercity', 'Other City', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 87, 'mailingstate', 'contactaddress', 1, '1', 'mailingstate', 'Mailing State', 1, 0, 0, 100, 5, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 88, 'otherstate', 'contactaddress', 1, '1', 'otherstate', 'Other State', 1, 0, 0, 100, 6, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 89, 'mailingzip', 'contactaddress', 1, '1', 'mailingzip', 'Mailing Zip', 1, 0, 0, 100, 7, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 90, 'otherzip', 'contactaddress', 1, '1', 'otherzip', 'Other Zip', 1, 0, 0, 100, 8, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 91, 'mailingcountry', 'contactaddress', 1, '1', 'mailingcountry', 'Mailing Country', 1, 0, 0, 100, 9, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 92, 'othercountry', 'contactaddress', 1, '1', 'othercountry', 'Other Country', 1, 0, 0, 100, 10, 2, 1, 'V~O');
INSERT INTO `field` VALUES (4, 93, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (2, 94, 'potentialname', 'potential', 1, '2', 'potentialname', 'Potential Name', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (2, 95, 'amount', 'potential', 1, '71', 'amount', 'Amount', 1, 0, 0, 100, 2, 1, 1, 'N~O');
INSERT INTO `field` VALUES (2, 96, 'accountid', 'potential', 1, '50', 'account_id', 'Account Name', 1, 0, 0, 100, 3, 1, 1, 'V~M');
INSERT INTO `field` VALUES (2, 97, 'closingdate', 'potential', 1, '23', 'closingdate', 'Expected Close Date', 1, 0, 0, 100, 5, 1, 1, 'D~M');
INSERT INTO `field` VALUES (2, 98, 'potentialtype', 'potential', 1, '15', 'opportunity_type', 'Type', 1, 0, 0, 100, 6, 1, 1, 'V~O');
INSERT INTO `field` VALUES (2, 99, 'nextstep', 'potential', 1, '1', 'nextstep', 'Next Step', 1, 0, 0, 100, 7, 1, 1, 'V~O');
INSERT INTO `field` VALUES (2, 100, 'leadsource', 'potential', 1, '15', 'leadsource', 'Lead Source', 1, 0, 0, 100, 8, 1, 1, 'V~O');
INSERT INTO `field` VALUES (2, 101, 'sales_stage', 'potential', 1, '16', 'sales_stage', 'Sales Stage', 1, 0, 0, 100, 9, 1, 1, 'V~O');
INSERT INTO `field` VALUES (2, 102, 'smownerid', 'crmentity', 1, '52', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 10, 1, 1, 'V~M');
INSERT INTO `field` VALUES (2, 103, 'probability', 'potential', 1, '9', 'probability', 'Probability', 1, 0, 0, 100, 11, 1, 1, 'N~O~3,3~LE~100');
INSERT INTO `field` VALUES (2, 104, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 13, 1, 2, 'T~O');
INSERT INTO `field` VALUES (2, 105, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 14, 1, 2, 'T~O');
INSERT INTO `field` VALUES (2, 106, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 2, 1, 'V~O');
INSERT INTO `field` VALUES (13, 107, 'smownerid', 'crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 2, 1, 1, 'V~M');
INSERT INTO `field` VALUES (13, 108, 'parent_id', 'troubletickets', 1, '68', 'parent_id', 'Related To', 1, 0, 0, 100, 4, 1, 1, 'I~O');
INSERT INTO `field` VALUES (13, 109, 'priority', 'troubletickets', 1, '15', 'ticketpriorities', 'Priority', 1, 0, 0, 100, 5, 1, 1, 'V~O');
INSERT INTO `field` VALUES (13, 110, 'product_id', 'troubletickets', 1, '59', 'product_id', 'Product Name', 1, 0, 0, 100, 6, 1, 1, 'I~O');
INSERT INTO `field` VALUES (13, 111, 'severity', 'troubletickets', 1, '15', 'ticketseverities', 'Severity', 1, 0, 0, 100, 7, 1, 1, 'V~O');
INSERT INTO `field` VALUES (13, 112, 'status', 'troubletickets', 1, '15', 'ticketstatus', 'Status', 1, 0, 0, 100, 8, 1, 1, 'V~O');
INSERT INTO `field` VALUES (13, 113, 'category', 'troubletickets', 1, '15', 'ticketcategories', 'Category', 1, 0, 0, 100, 9, 1, 1, 'V~O');
INSERT INTO `field` VALUES (13, 114, 'update_log', 'troubletickets', 1, '15', 'update_log', 'Update History', 1, 0, 0, 100, 9, 1, 3, 'V~O');
INSERT INTO `field` VALUES (13, 115, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 10, 1, 2, 'T~O');
INSERT INTO `field` VALUES (13, 116, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 11, 1, 2, 'T~O');
INSERT INTO `field` VALUES (13, 117, 'title', 'troubletickets', 1, '22', 'ticket_title', 'Title', 1, 0, 0, 100, 1, 2, 1, 'V~M');
INSERT INTO `field` VALUES (13, 118, 'description', 'troubletickets', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (13, 119, 'solution', 'troubletickets', 1, '19', 'solution', 'Solution', 1, 0, 0, 100, 1, 4, 1, 'V~O');
INSERT INTO `field` VALUES (13, 120, 'comments', 'ticketcomments', 1, '19', 'comments', 'Add Comment', 1, 0, 0, 100, 1, 6, 1, 'V~O');
INSERT INTO `field` VALUES (14, 121, 'productname', 'products', 1, '2', 'productname', 'Product Name', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (14, 122, 'productcode', 'products', 1, '1', 'productcode', 'Product Code', 1, 0, 0, 100, 2, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 123, 'discontinued', 'products', 1, '56', 'discontinued', 'Product Active', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 124, 'manufacturer', 'products', 1, '15', 'manufacturer', 'Manufacturer', 1, 0, 0, 100, 9, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 125, 'productcategory', 'products', 1, '15', 'productcategory', 'Product Category', 1, 0, 0, 100, 4, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 126, 'sales_start_date', 'products', 1, '5', 'sales_start_date', 'Sales Start Date', 1, 0, 0, 100, 5, 1, 1, 'D~O');
INSERT INTO `field` VALUES (14, 127, 'sales_end_date', 'products', 1, '5', 'sales_end_date', 'Sales End Date', 1, 0, 0, 100, 6, 1, 1, 'D~O~OTH~GE~sales_start_date~Sales Start Date');
INSERT INTO `field` VALUES (14, 128, 'start_date', 'products', 1, '5', 'start_date', 'Support Start Date', 1, 0, 0, 100, 7, 1, 1, 'D~O');
INSERT INTO `field` VALUES (14, 129, 'expiry_date', 'products', 1, '5', 'expiry_date', 'Support Expiry Date', 1, 0, 0, 100, 8, 1, 1, 'D~O~OTH~GE~start_date~Start Date');
INSERT INTO `field` VALUES (14, 130, 'crmid', 'seproductsrel', 1, '66', 'parent_id', 'Related To', 1, 0, 0, 100, 10, 1, 1, 'I~O');
INSERT INTO `field` VALUES (14, 131, 'contactid', 'products', 1, '57', 'contact_id', 'Contact Name', 1, 0, 0, 100, 11, 1, 1, 'I~O');
INSERT INTO `field` VALUES (14, 132, 'website', 'products', 1, '17', 'website', 'Website', 1, 0, 0, 100, 12, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 133, 'vendor_id', 'products', 1, '75', 'vendor_id', 'Vendor Name', 1, 0, 0, 100, 13, 1, 1, 'I~O');
INSERT INTO `field` VALUES (14, 134, 'mfr_part_no', 'products', 1, '1', 'mfr_part_no', 'Mfr PartNo', 1, 0, 0, 100, 14, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 135, 'vendor_part_no', 'products', 1, '1', 'vendor_part_no', 'Vendor PartNo', 1, 0, 0, 100, 15, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 136, 'serialno', 'products', 1, '1', 'serial_no', 'Serial No', 1, 0, 0, 100, 16, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 137, 'productsheet', 'products', 1, '1', 'productsheet', 'Product Sheet', 1, 0, 0, 100, 17, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 138, 'glacct', 'products', 1, '15', 'glacct', 'GL Account', 1, 0, 0, 100, 18, 1, 1, 'V~O');
INSERT INTO `field` VALUES (14, 139, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 19, 1, 2, 'T~O');
INSERT INTO `field` VALUES (14, 140, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 20, 1, 2, 'T~O');
INSERT INTO `field` VALUES (14, 141, 'unit_price', 'products', 1, '71', 'unit_price', 'Unit Price', 1, 0, 0, 100, 1, 2, 1, 'N~O');
INSERT INTO `field` VALUES (14, 142, 'commissionrate', 'products', 1, '9', 'commissionrate', 'Commission Rate', 1, 0, 0, 100, 2, 2, 1, 'N~O');
INSERT INTO `field` VALUES (14, 143, 'taxclass', 'products', 1, '15', 'taxclass', 'Tax Class', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (14, 144, 'usageunit', 'products', 1, '15', 'usageunit', 'Usage Unit', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (14, 145, 'qty_per_unit', 'products', 1, '1', 'qty_per_unit', 'Qty/Unit', 1, 0, 0, 100, 2, 3, 1, 'N~O');
INSERT INTO `field` VALUES (14, 146, 'qtyinstock', 'products', 1, '1', 'qtyinstock', 'Qty In Stock', 1, 0, 0, 100, 3, 3, 1, 'I~O');
INSERT INTO `field` VALUES (14, 147, 'reorderlevel', 'products', 1, '1', 'reorderlevel', 'Reorder Level', 1, 0, 0, 100, 4, 3, 1, 'I~O');
INSERT INTO `field` VALUES (14, 148, 'handler', 'products', 1, '52', 'assigned_user_id', 'Handler', 1, 0, 0, 100, 5, 3, 1, 'I~O');
INSERT INTO `field` VALUES (14, 149, 'qtyindemand', 'products', 1, '1', 'qtyindemand', 'Qty In Demand', 1, 0, 0, 100, 6, 3, 1, 'I~O');
INSERT INTO `field` VALUES (14, 150, 'imagename', 'products', 1, '69', 'imagename', 'Product Image', 1, 0, 0, 100, 1, 6, 1, 'V~O');
INSERT INTO `field` VALUES (14, 151, 'product_description', 'products', 1, '19', 'product_description', 'Description', 1, 0, 0, 100, 1, 4, 1, 'V~O');
INSERT INTO `field` VALUES (8, 152, 'contact_id', 'notes', 1, '57', 'contact_id', 'Contact Name', 1, 0, 0, 100, 1, 1, 1, 'V~O');
INSERT INTO `field` VALUES (8, 153, 'crmid', 'senotesrel', 1, '62', 'parent_id', 'Related To', 1, 0, 0, 100, 2, 1, 1, 'I~O');
INSERT INTO `field` VALUES (8, 154, 'title', 'notes', 1, '2', 'title', 'Subject', 1, 0, 0, 100, 3, 1, 1, 'V~M');
INSERT INTO `field` VALUES (8, 155, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 4, 1, 2, 'T~O');
INSERT INTO `field` VALUES (8, 156, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 5, 1, 2, 'T~O');
INSERT INTO `field` VALUES (8, 157, 'filename', 'notes', 1, '61', 'filename', 'Attachment', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (8, 158, 'notecontent', 'notes', 1, '19', 'notecontent', 'Note', 1, 0, 0, 100, 5, 3, 1, 'V~O');
INSERT INTO `field` VALUES (10, 159, 'date_start', 'activity', 1, '6', 'date_start', 'Date & Time Sent', 1, 0, 0, 100, 1, 1, 1, 'DT~M~time_start~Time Start');
INSERT INTO `field` VALUES (10, 160, 'semodule', 'activity', 1, '2', 'parent_type', 'Sales Enity Module', 1, 0, 0, 100, 2, 1, 3, '');
INSERT INTO `field` VALUES (10, 161, 'activitytype', 'activity', 1, '2', 'activitytype', 'Activtiy Type', 1, 0, 0, 100, 3, 1, 3, 'V~O');
INSERT INTO `field` VALUES (10, 162, 'crmid', 'seactivityrel', 1, '67', 'parent_id', 'Related To', 1, 0, 0, 100, 4, 1, 1, 'I~O');
INSERT INTO `field` VALUES (10, 163, 'smownerid', 'crmentity', 1, '52', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 5, 1, 1, 'V~M');
INSERT INTO `field` VALUES (10, 164, 'subject', 'activity', 1, '2', 'subject', 'Subject', 1, 0, 0, 100, 6, 2, 1, 'V~M');
INSERT INTO `field` VALUES (10, 165, 'filename', 'emails', 1, '61', 'filename', 'Attachment', 1, 0, 0, 100, 7, 3, 1, 'V~O');
INSERT INTO `field` VALUES (10, 166, 'description', 'emails', 1, '19', 'description', 'Description', 1, 0, 0, 100, 8, 4, 1, 'V~O');
INSERT INTO `field` VALUES (10, 167, 'time_start', 'activity', 1, '2', 'time_start', 'Time Start', 1, 0, 0, 100, 9, 1, 3, 'T~O');
INSERT INTO `field` VALUES (10, 168, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 10, 1, 2, 'T~O');
INSERT INTO `field` VALUES (10, 169, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 11, 1, 2, 'T~O');
INSERT INTO `field` VALUES (9, 170, 'subject', 'activity', 1, '2', 'subject', 'Subject', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (9, 171, 'smownerid', 'crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 2, 1, 1, 'V~M');
INSERT INTO `field` VALUES (9, 172, 'date_start', 'activity', 1, '6', 'date_start', 'Start Date & Time', 1, 0, 0, 100, 3, 1, 1, 'DT~M~time_start');
INSERT INTO `field` VALUES (9, 173, 'time_start', 'activity', 1, '2', 'time_start', 'Time Start', 1, 0, 0, 100, 4, 1, 3, 'T~O');
INSERT INTO `field` VALUES (9, 174, 'due_date', 'activity', 1, '23', 'due_date', 'Due Date', 1, 0, 0, 100, 5, 1, 1, 'D~M~OTH~GE~date_start~Start Date & Time');
INSERT INTO `field` VALUES (9, 175, 'crmid', 'seactivityrel', 1, '66', 'parent_id', 'Related To', 1, 0, 0, 100, 7, 1, 1, 'I~O');
INSERT INTO `field` VALUES (9, 176, 'contactid', 'cntactivityrel', 1, '57', 'contact_id', 'Contact Name', 1, 0, 0, 100, 8, 1, 1, 'I~O');
INSERT INTO `field` VALUES (9, 177, 'status', 'activity', 1, '15', 'taskstatus', 'Status', 1, 0, 0, 100, 9, 1, 1, 'V~O');
INSERT INTO `field` VALUES (9, 178, 'eventstatus', 'activity', 1, '15', 'eventstatus', 'Status', 1, 0, 0, 100, 9, 1, 3, 'V~O');
INSERT INTO `field` VALUES (9, 179, 'priority', 'activity', 1, '15', 'taskpriority', 'Priority', 1, 0, 0, 100, 10, 1, 1, 'V~O');
INSERT INTO `field` VALUES (9, 180, 'sendnotification', 'activity', 1, '56', 'sendnotification', 'Send Notification', 1, 0, 0, 100, 11, 1, 1, 'C~O');
INSERT INTO `field` VALUES (9, 181, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 14, 1, 2, 'T~O');
INSERT INTO `field` VALUES (9, 182, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 15, 1, 2, 'T~O');
INSERT INTO `field` VALUES (9, 183, 'activitytype', 'activity', 1, '15', 'activitytype', 'Activity Type', 1, 0, 0, 100, 16, 1, 3, 'V~O');
INSERT INTO `field` VALUES (9, 184, 'description', 'activity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 2, 1, 'V~O');
INSERT INTO `field` VALUES (9, 185, 'duration_hours', 'activity', 1, '63', 'duration_hours', 'Duration', 1, 0, 0, 100, 17, 1, 3, 'T~O');
INSERT INTO `field` VALUES (9, 186, 'duration_minutes', 'activity', 1, '15', 'duration_minutes', 'Duration Minutes', 1, 0, 0, 100, 18, 1, 3, 'T~O');
INSERT INTO `field` VALUES (9, 187, 'location', 'activity', 1, '1', 'location', 'Location', 1, 0, 0, 100, 19, 1, 3, 'V~O');
INSERT INTO `field` VALUES (9, 188, 'reminder_time', 'activity_reminder', 1, '30', 'reminder_time', 'Send Reminder', 1, 0, 0, 100, 1, 7, 3, 'I~O');
INSERT INTO `field` VALUES (9, 189, 'recurringtype', 'recurringevents', 1, '15', 'recurringtype', 'Recurrence', 1, 0, 0, 100, 6, 1, 3, 'O~O');
INSERT INTO `field` VALUES (16, 190, 'subject', 'activity', 1, '2', 'subject', 'Subject', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (16, 191, 'smownerid', 'crmentity', 1, '53', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 2, 1, 1, 'I~O');
INSERT INTO `field` VALUES (16, 192, 'date_start', 'activity', 1, '6', 'date_start', 'Start Date & Time', 1, 0, 0, 100, 3, 1, 1, 'DT~M~time_start');
INSERT INTO `field` VALUES (16, 193, 'time_start', 'activity', 1, '2', 'time_start', 'Time Start', 1, 0, 0, 100, 4, 1, 3, 'T~M');
INSERT INTO `field` VALUES (16, 194, 'due_date', 'activity', 1, '23', 'due_date', 'End Date', 1, 0, 0, 100, 5, 1, 1, 'D~M~OTH~GE~date_start~Start Date & Time');
INSERT INTO `field` VALUES (16, 195, 'recurringtype', 'recurringevents', 1, '15', 'recurringtype', 'Recurrence', 1, 0, 0, 100, 6, 1, 1, 'O~O');
INSERT INTO `field` VALUES (16, 196, 'duration_hours', 'activity', 1, '63', 'duration_hours', 'Duration', 1, 0, 0, 100, 7, 1, 1, 'I~M');
INSERT INTO `field` VALUES (16, 197, 'duration_minutes', 'activity', 1, '15', 'duration_minutes', 'Duration Minutes', 1, 0, 0, 100, 8, 1, 3, 'O~O');
INSERT INTO `field` VALUES (16, 198, 'crmid', 'seactivityrel', 1, '66', 'parent_id', 'Related To', 1, 0, 0, 100, 9, 1, 1, 'I~O');
INSERT INTO `field` VALUES (16, 199, 'contactid', 'cntactivityrel', 1, '57', 'contact_id', 'Contact Name', 1, 0, 0, 100, 10, 1, 1, 'V~O');
INSERT INTO `field` VALUES (16, 200, 'eventstatus', 'activity', 1, '15', 'eventstatus', 'Status', 1, 0, 0, 100, 11, 1, 1, 'V~O');
INSERT INTO `field` VALUES (16, 201, 'sendnotification', 'activity', 1, '56', 'sendnotification', 'Send Notification', 1, 0, 0, 100, 12, 1, 1, 'C~O');
INSERT INTO `field` VALUES (16, 202, 'activitytype', 'activity', 1, '15', 'activitytype', 'Activity Type', 1, 0, 0, 100, 13, 1, 1, 'V~O');
INSERT INTO `field` VALUES (16, 203, 'location', 'activity', 1, '1', 'location', 'Location', 1, 0, 0, 100, 14, 1, 1, 'V~O');
INSERT INTO `field` VALUES (16, 204, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 15, 1, 2, 'T~O');
INSERT INTO `field` VALUES (16, 205, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 16, 1, 2, 'T~O');
INSERT INTO `field` VALUES (16, 206, 'description', 'activity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 2, 1, 'V~O');
INSERT INTO `field` VALUES (16, 207, 'reminder_time', 'activity_reminder', 1, '30', 'reminder_time', 'Send Reminder', 1, 0, 0, 100, 1, 7, 1, 'I~O');
INSERT INTO `field` VALUES (15, 208, 'product_id', 'faq', 1, '59', 'product_id', 'Product Name', 1, 0, 0, 100, 1, 1, 1, 'I~O');
INSERT INTO `field` VALUES (15, 209, 'category', 'faq', 1, '15', 'faqcategories', 'Category', 1, 0, 0, 100, 2, 1, 1, 'V~O');
INSERT INTO `field` VALUES (15, 210, 'status', 'faq', 1, '15', 'faqstatus', 'Status', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (15, 211, 'question', 'faq', 1, '20', 'question', 'Question', 1, 0, 0, 100, 1, 2, 1, 'V~M');
INSERT INTO `field` VALUES (15, 212, 'answer', 'faq', 1, '20', 'faq_answer', 'Answer', 1, 0, 0, 100, 1, 3, 1, 'V~M');
INSERT INTO `field` VALUES (15, 213, 'comments', 'faqcomments', 1, '19', 'comments', 'Add Comment', 1, 0, 0, 100, 1, 4, 1, 'V~O');
INSERT INTO `field` VALUES (15, 214, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 3, 1, 2, 'T~O');
INSERT INTO `field` VALUES (15, 215, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 4, 1, 2, 'T~O');
INSERT INTO `field` VALUES (18, 216, 'vendorname', 'vendor', 1, '2', 'vendorname', 'Vendor Name', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (18, 217, 'phone', 'vendor', 1, '1', 'phone', 'Phone', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (18, 218, 'email', 'vendor', 1, '13', 'email', 'Email', 1, 0, 0, 100, 4, 1, 1, 'E~O');
INSERT INTO `field` VALUES (18, 219, 'website', 'vendor', 1, '17', 'website', 'Website', 1, 0, 0, 100, 5, 1, 1, 'V~O');
INSERT INTO `field` VALUES (18, 220, 'glacct', 'vendor', 1, '15', 'glacct', 'GL Account', 1, 0, 0, 100, 6, 1, 1, 'V~O');
INSERT INTO `field` VALUES (18, 221, 'category', 'vendor', 1, '1', 'category', 'Category', 1, 0, 0, 100, 7, 1, 1, 'V~O');
INSERT INTO `field` VALUES (18, 222, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 8, 1, 2, 'T~O');
INSERT INTO `field` VALUES (18, 223, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 9, 1, 2, 'T~O');
INSERT INTO `field` VALUES (18, 224, 'street', 'vendor', 1, '21', 'treet', 'Street', 1, 0, 0, 100, 1, 2, 1, 'V~O');
INSERT INTO `field` VALUES (18, 225, 'city', 'vendor', 1, '1', 'city', 'City', 1, 0, 0, 100, 2, 2, 1, 'V~O');
INSERT INTO `field` VALUES (18, 226, 'state', 'vendor', 1, '1', 'state', 'State', 1, 0, 0, 100, 3, 2, 1, 'V~O');
INSERT INTO `field` VALUES (18, 227, 'postalcode', 'vendor', 1, '1', 'postalcode', 'Postal Code', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (18, 228, 'country', 'vendor', 1, '1', 'country', 'Country', 1, 0, 0, 100, 5, 2, 1, 'V~O');
INSERT INTO `field` VALUES (18, 229, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (19, 230, 'bookname', 'pricebook', 1, '2', 'bookname', 'Price Book Name', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (19, 231, 'active', 'pricebook', 1, '56', 'active', 'Active', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (19, 232, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 4, 1, 2, 'T~O');
INSERT INTO `field` VALUES (19, 233, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 5, 1, 2, 'T~O');
INSERT INTO `field` VALUES (19, 234, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 2, 1, 'V~O');
INSERT INTO `field` VALUES (20, 235, 'subject', 'quotes', 1, '2', 'subject', 'Subject', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (20, 236, 'potentialid', 'quotes', 1, '76', 'potential_id', 'Potential Name', 1, 0, 0, 100, 2, 1, 1, 'I~O');
INSERT INTO `field` VALUES (20, 237, 'quotestage', 'quotes', 1, '15', 'quotestage', 'Quote Stage', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (20, 238, 'validtill', 'quotes', 1, '5', 'validtill', 'Valid Till', 1, 0, 0, 100, 4, 1, 1, 'D~O');
INSERT INTO `field` VALUES (20, 239, 'team', 'quotes', 1, '1', 'team', 'Team', 1, 0, 0, 100, 5, 1, 1, 'V~O');
INSERT INTO `field` VALUES (20, 240, 'contactid', 'quotes', 1, '57', 'contact_id', 'Contact Name', 1, 0, 0, 100, 6, 1, 1, 'V~O');
INSERT INTO `field` VALUES (20, 241, 'carrier', 'quotes', 1, '15', 'carrier', 'Carrier', 1, 0, 0, 100, 8, 1, 1, 'V~O');
INSERT INTO `field` VALUES (20, 242, 'subtotal', 'quotes', 1, '1', 'hdnSubTotal', 'Sub Total', 1, 0, 0, 100, 9, 1, 3, 'N~O');
INSERT INTO `field` VALUES (20, 243, 'shipping', 'quotes', 1, '1', 'shipping', 'Shipping', 1, 0, 0, 100, 10, 1, 1, 'V~O');
INSERT INTO `field` VALUES (20, 244, 'inventorymanager', 'quotes', 1, '77', 'assigned_user_id1', 'Inventory Manager', 1, 0, 0, 100, 11, 1, 1, 'I~O');
INSERT INTO `field` VALUES (20, 245, 'tax', 'quotes', 1, '1', 'txtTax', 'Tax', 1, 0, 0, 100, 13, 1, 3, 'N~O');
INSERT INTO `field` VALUES (20, 246, 'adjustment', 'quotes', 1, '1', 'txtAdjustment', 'Adjustment', 1, 0, 0, 100, 20, 1, 3, 'NN~O');
INSERT INTO `field` VALUES (20, 247, 'total', 'quotes', 1, '1', 'hdnGrandTotal', 'Total', 1, 0, 0, 100, 14, 1, 3, 'N~O');
INSERT INTO `field` VALUES (20, 248, 'accountid', 'quotes', 1, '73', 'account_id', 'Account Name', 1, 0, 0, 100, 16, 1, 1, 'I~M');
INSERT INTO `field` VALUES (20, 249, 'smownerid', 'crmentity', 1, '52', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 17, 1, 1, 'V~M');
INSERT INTO `field` VALUES (20, 250, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 18, 1, 2, 'T~O');
INSERT INTO `field` VALUES (20, 251, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 19, 1, 2, 'T~O');
INSERT INTO `field` VALUES (20, 252, 'bill_street', 'quotesbillads', 1, '24', 'bill_street', 'Billing Address', 1, 0, 0, 100, 1, 2, 1, 'V~M');
INSERT INTO `field` VALUES (20, 253, 'ship_street', 'quotesshipads', 1, '24', 'ship_street', 'Shipping Address', 1, 0, 0, 100, 2, 2, 1, 'V~M');
INSERT INTO `field` VALUES (20, 254, 'bill_city', 'quotesbillads', 1, '1', 'bill_city', 'Billing City', 1, 0, 0, 100, 3, 2, 1, 'V~O');
INSERT INTO `field` VALUES (20, 255, 'ship_city', 'quotesshipads', 1, '1', 'ship_city', 'Shipping City', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (20, 256, 'bill_state', 'quotesbillads', 1, '1', 'bill_state', 'Billing State', 1, 0, 0, 100, 5, 2, 1, 'V~O');
INSERT INTO `field` VALUES (20, 257, 'ship_state', 'quotesshipads', 1, '1', 'ship_state', 'Shipping State', 1, 0, 0, 100, 6, 2, 1, 'V~O');
INSERT INTO `field` VALUES (20, 258, 'bill_code', 'quotesbillads', 1, '1', 'bill_code', 'Billing Code', 1, 0, 0, 100, 7, 2, 1, 'V~O');
INSERT INTO `field` VALUES (20, 259, 'ship_code', 'quotesshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 0, 0, 100, 8, 2, 1, 'V~O');
INSERT INTO `field` VALUES (20, 260, 'bill_country', 'quotesbillads', 1, '1', 'bill_country', 'Billing Country', 1, 0, 0, 100, 9, 2, 1, 'V~O');
INSERT INTO `field` VALUES (20, 261, 'ship_country', 'quotesshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 0, 0, 100, 10, 2, 1, 'V~O');
INSERT INTO `field` VALUES (20, 262, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (20, 263, 'terms_conditions', 'quotes', 1, '19', 'terms_conditions', 'Terms & Conditions', 1, 0, 0, 100, 1, 6, 1, 'V~O');
INSERT INTO `field` VALUES (21, 264, 'subject', 'purchaseorder', 1, '2', 'subject', 'Subject', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (21, 265, 'vendorid', 'purchaseorder', 1, '81', 'vendor_id', 'Vendor Name', 1, 0, 0, 100, 3, 1, 1, 'I~M');
INSERT INTO `field` VALUES (21, 266, 'requisition_no', 'purchaseorder', 1, '1', 'requisition_no', 'Requisition No', 1, 0, 0, 100, 4, 1, 1, 'V~O');
INSERT INTO `field` VALUES (21, 267, 'tracking_no', 'purchaseorder', 1, '1', 'tracking_no', 'Tracking Number', 1, 0, 0, 100, 5, 1, 1, 'V~O');
INSERT INTO `field` VALUES (21, 268, 'contactid', 'purchaseorder', 1, '57', 'contact_id', 'Contact Name', 1, 0, 0, 100, 6, 1, 1, 'I~O');
INSERT INTO `field` VALUES (21, 269, 'duedate', 'purchaseorder', 1, '5', 'duedate', 'Due Date', 1, 0, 0, 100, 7, 1, 1, 'V~O');
INSERT INTO `field` VALUES (21, 270, 'carrier', 'purchaseorder', 1, '15', 'carrier', 'Carrier', 1, 0, 0, 100, 8, 1, 1, 'V~O');
INSERT INTO `field` VALUES (21, 271, 'salestax', 'purchaseorder', 1, '1', 'txtTax', 'Sales Tax', 1, 0, 0, 100, 10, 1, 3, 'N~O');
INSERT INTO `field` VALUES (21, 272, 'adjustment', 'purchaseorder', 1, '1', 'txtAdjustment', 'Adjustment', 1, 0, 0, 100, 10, 1, 3, 'NN~O');
INSERT INTO `field` VALUES (21, 273, 'salescommission', 'purchaseorder', 1, '1', 'salescommission', 'Sales Commission', 1, 0, 0, 100, 11, 1, 1, 'N~O');
INSERT INTO `field` VALUES (21, 274, 'exciseduty', 'purchaseorder', 1, '1', 'exciseduty', 'Excise Duty', 1, 0, 0, 100, 12, 1, 1, 'N~O');
INSERT INTO `field` VALUES (21, 275, 'total', 'purchaseorder', 1, '1', 'hdnGrandTotal', 'Total', 1, 0, 0, 100, 13, 1, 3, 'N~O');
INSERT INTO `field` VALUES (21, 276, 'subtotal', 'purchaseorder', 1, '1', 'hdnSubTotal', 'Sub Total', 1, 0, 0, 100, 14, 1, 3, 'N~O');
INSERT INTO `field` VALUES (21, 277, 'postatus', 'purchaseorder', 1, '15', 'postatus', 'Status', 1, 0, 0, 100, 15, 1, 1, 'V~O');
INSERT INTO `field` VALUES (21, 278, 'smownerid', 'crmentity', 1, '52', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 16, 1, 1, 'V~M');
INSERT INTO `field` VALUES (21, 279, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 17, 1, 2, 'T~O');
INSERT INTO `field` VALUES (21, 280, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 18, 1, 2, 'T~O');
INSERT INTO `field` VALUES (21, 281, 'bill_street', 'pobillads', 1, '24', 'bill_street', 'Billing Address', 1, 0, 0, 100, 1, 2, 1, 'V~M');
INSERT INTO `field` VALUES (21, 282, 'ship_street', 'poshipads', 1, '24', 'ship_street', 'Shipping Address', 1, 0, 0, 100, 2, 2, 1, 'V~M');
INSERT INTO `field` VALUES (21, 283, 'bill_city', 'pobillads', 1, '1', 'bill_city', 'Billing City', 1, 0, 0, 100, 3, 2, 1, 'V~O');
INSERT INTO `field` VALUES (21, 284, 'ship_city', 'poshipads', 1, '1', 'ship_city', 'Shipping City', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (21, 285, 'bill_state', 'pobillads', 1, '1', 'bill_state', 'Billing State', 1, 0, 0, 100, 5, 2, 1, 'V~O');
INSERT INTO `field` VALUES (21, 286, 'ship_state', 'poshipads', 1, '1', 'ship_state', 'Shipping State', 1, 0, 0, 100, 6, 2, 1, 'V~O');
INSERT INTO `field` VALUES (21, 287, 'bill_code', 'pobillads', 1, '1', 'bill_code', 'Billing Code', 1, 0, 0, 100, 7, 2, 1, 'V~O');
INSERT INTO `field` VALUES (21, 288, 'ship_code', 'poshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 0, 0, 100, 8, 2, 1, 'V~O');
INSERT INTO `field` VALUES (21, 289, 'bill_country', 'pobillads', 1, '1', 'bill_country', 'Billing Country', 1, 0, 0, 100, 9, 2, 1, 'V~O');
INSERT INTO `field` VALUES (21, 290, 'ship_country', 'poshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 0, 0, 100, 10, 2, 1, 'V~O');
INSERT INTO `field` VALUES (21, 291, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (21, 292, 'terms_conditions', 'purchaseorder', 1, '19', 'terms_conditions', 'Terms & Conditions', 1, 0, 0, 100, 1, 6, 1, 'V~O');
INSERT INTO `field` VALUES (22, 293, 'subject', 'salesorder', 1, '2', 'subject', 'Subject', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (22, 294, 'potentialid', 'salesorder', 1, '76', 'potential_id', 'Potential Name', 1, 0, 0, 100, 2, 1, 1, 'I~O');
INSERT INTO `field` VALUES (22, 295, 'customerno', 'salesorder', 1, '1', 'customerno', 'Customer No', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (22, 296, 'quoteid', 'salesorder', 1, '78', 'quote_id', 'Quote Name', 1, 0, 0, 100, 4, 1, 1, 'I~O');
INSERT INTO `field` VALUES (22, 297, 'purchaseorder', 'salesorder', 1, '1', 'purchaseorder', 'Purchase Order', 1, 0, 0, 100, 4, 1, 1, 'V~O');
INSERT INTO `field` VALUES (22, 298, 'contactid', 'salesorder', 1, '57', 'contact_id', 'Contact Name', 1, 0, 0, 100, 6, 1, 1, 'I~O');
INSERT INTO `field` VALUES (22, 299, 'duedate', 'salesorder', 1, '5', 'duedate', 'Due Date', 1, 0, 0, 100, 8, 1, 1, 'D~O');
INSERT INTO `field` VALUES (22, 300, 'carrier', 'salesorder', 1, '15', 'carrier', 'Carrier', 1, 0, 0, 100, 9, 1, 1, 'V~O');
INSERT INTO `field` VALUES (22, 301, 'pending', 'salesorder', 1, '1', 'pending', 'Pending', 1, 0, 0, 100, 10, 1, 1, 'V~O');
INSERT INTO `field` VALUES (22, 302, 'sostatus', 'salesorder', 1, '15', 'sostatus', 'Status', 1, 0, 0, 100, 11, 1, 1, 'V~O');
INSERT INTO `field` VALUES (22, 303, 'salestax', 'salesorder', 1, '1', 'txtTax', 'Sales Tax', 1, 0, 0, 100, 12, 1, 3, 'N~O');
INSERT INTO `field` VALUES (22, 304, 'adjustment', 'salesorder', 1, '1', 'txtAdjustment', 'Sales Tax', 1, 0, 0, 100, 12, 1, 3, 'NN~O');
INSERT INTO `field` VALUES (22, 305, 'salescommission', 'salesorder', 1, '1', 'salescommission', 'Sales Commission', 1, 0, 0, 100, 13, 1, 1, 'N~O');
INSERT INTO `field` VALUES (22, 306, 'exciseduty', 'salesorder', 1, '1', 'exciseduty', 'Excise Duty', 1, 0, 0, 100, 13, 1, 1, 'N~O');
INSERT INTO `field` VALUES (22, 307, 'total', 'salesorder', 1, '1', 'hdnGrandTotal', 'Total', 1, 0, 0, 100, 14, 1, 3, 'N~O');
INSERT INTO `field` VALUES (22, 308, 'subtotal', 'salesorder', 1, '1', 'hdnSubTotal', 'Total', 1, 0, 0, 100, 15, 1, 3, 'N~O');
INSERT INTO `field` VALUES (22, 309, 'accountid', 'salesorder', 1, '73', 'account_id', 'Account Name', 1, 0, 0, 100, 16, 1, 1, 'I~M');
INSERT INTO `field` VALUES (22, 310, 'smownerid', 'crmentity', 1, '52', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 17, 1, 1, 'V~M');
INSERT INTO `field` VALUES (22, 311, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 18, 1, 2, 'T~O');
INSERT INTO `field` VALUES (22, 312, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 19, 1, 2, 'T~O');
INSERT INTO `field` VALUES (22, 313, 'bill_street', 'sobillads', 1, '24', 'bill_street', 'Billing Address', 1, 0, 0, 100, 1, 2, 1, 'V~M');
INSERT INTO `field` VALUES (22, 314, 'ship_street', 'soshipads', 1, '24', 'ship_street', 'Shipping Address', 1, 0, 0, 100, 2, 2, 1, 'V~M');
INSERT INTO `field` VALUES (22, 315, 'bill_city', 'sobillads', 1, '1', 'bill_city', 'Billing City', 1, 0, 0, 100, 3, 2, 1, 'V~O');
INSERT INTO `field` VALUES (22, 316, 'ship_city', 'soshipads', 1, '1', 'ship_city', 'Shipping City', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (22, 317, 'bill_state', 'sobillads', 1, '1', 'bill_state', 'Billing State', 1, 0, 0, 100, 5, 2, 1, 'V~O');
INSERT INTO `field` VALUES (22, 318, 'ship_state', 'soshipads', 1, '1', 'ship_state', 'Shipping State', 1, 0, 0, 100, 6, 2, 1, 'V~O');
INSERT INTO `field` VALUES (22, 319, 'bill_code', 'sobillads', 1, '1', 'bill_code', 'Billing Code', 1, 0, 0, 100, 7, 2, 1, 'V~O');
INSERT INTO `field` VALUES (22, 320, 'ship_code', 'soshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 0, 0, 100, 8, 2, 1, 'V~O');
INSERT INTO `field` VALUES (22, 321, 'bill_country', 'sobillads', 1, '1', 'bill_country', 'Billing Country', 1, 0, 0, 100, 9, 2, 1, 'V~O');
INSERT INTO `field` VALUES (22, 322, 'ship_country', 'soshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 0, 0, 100, 10, 2, 1, 'V~O');
INSERT INTO `field` VALUES (22, 323, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (22, 324, 'terms_conditions', 'salesorder', 1, '19', 'terms_conditions', 'Terms & Conditions', 1, 0, 0, 100, 1, 6, 1, 'V~O');
INSERT INTO `field` VALUES (23, 325, 'subject', 'invoice', 1, '2', 'subject', 'Subject', 1, 0, 0, 100, 1, 1, 1, 'V~M');
INSERT INTO `field` VALUES (23, 326, 'salesorderid', 'invoice', 1, '80', 'salesorder_id', 'Sales Order', 1, 0, 0, 100, 2, 1, 1, 'I~O');
INSERT INTO `field` VALUES (23, 327, 'customerno', 'invoice', 1, '1', 'customerno', 'Customer No', 1, 0, 0, 100, 3, 1, 1, 'V~O');
INSERT INTO `field` VALUES (23, 328, 'invoicedate', 'invoice', 1, '5', 'invoicedate', 'Invoice Date', 1, 0, 0, 100, 5, 1, 1, 'D~O');
INSERT INTO `field` VALUES (23, 329, 'duedate', 'invoice', 1, '5', 'duedate', 'Due Date', 1, 0, 0, 100, 6, 1, 1, 'D~O');
INSERT INTO `field` VALUES (23, 330, 'purchaseorder', 'invoice', 1, '1', 'purchaseorder', 'Purchase Order', 1, 0, 0, 100, 8, 1, 1, 'V~O');
INSERT INTO `field` VALUES (23, 331, 'salestax', 'invoice', 1, '1', 'txtTax', 'Sales Tax', 1, 0, 0, 100, 9, 1, 3, 'N~O');
INSERT INTO `field` VALUES (23, 332, 'adjustment', 'invoice', 1, '1', 'txtAdjustment', 'Sales Tax', 1, 0, 0, 100, 9, 1, 3, 'NN~O');
INSERT INTO `field` VALUES (23, 333, 'salescommission', 'invoice', 1, '1', 'salescommission', 'Sales Commission', 1, 0, 0, 10, 13, 1, 1, 'N~O');
INSERT INTO `field` VALUES (23, 334, 'exciseduty', 'invoice', 1, '1', 'exciseduty', 'Excise Duty', 1, 0, 0, 100, 11, 1, 1, 'N~O');
INSERT INTO `field` VALUES (23, 335, 'subtotal', 'invoice', 1, '1', 'hdnSubTotal', 'Sub Total', 1, 0, 0, 100, 12, 1, 3, 'N~O');
INSERT INTO `field` VALUES (23, 336, 'total', 'invoice', 1, '1', 'hdnGrandTotal', 'Total', 1, 0, 0, 100, 13, 1, 3, 'N~O');
INSERT INTO `field` VALUES (23, 337, 'accountid', 'invoice', 1, '73', 'account_id', 'Account Name', 1, 0, 0, 100, 14, 1, 1, 'I~M');
INSERT INTO `field` VALUES (23, 338, 'invoicestatus', 'invoice', 1, '15', 'invoicestatus', 'Status', 1, 0, 0, 100, 15, 1, 1, 'V~O');
INSERT INTO `field` VALUES (23, 339, 'smownerid', 'crmentity', 1, '52', 'assigned_user_id', 'Assigned To', 1, 0, 0, 100, 16, 1, 1, 'V~M');
INSERT INTO `field` VALUES (23, 340, 'createdtime', 'crmentity', 1, '70', 'createdtime', 'Created Time', 1, 0, 0, 100, 17, 1, 2, 'T~O');
INSERT INTO `field` VALUES (23, 341, 'modifiedtime', 'crmentity', 1, '70', 'modifiedtime', 'Modified Time', 1, 0, 0, 100, 18, 1, 2, 'T~O');
INSERT INTO `field` VALUES (23, 342, 'bill_street', 'invoicebillads', 1, '24', 'bill_street', 'Billing Address', 1, 0, 0, 100, 1, 2, 1, 'V~M');
INSERT INTO `field` VALUES (23, 343, 'ship_street', 'invoiceshipads', 1, '24', 'ship_street', 'Shipping Address', 1, 0, 0, 100, 2, 2, 1, 'V~M');
INSERT INTO `field` VALUES (23, 344, 'bill_city', 'invoicebillads', 1, '1', 'bill_city', 'Billing City', 1, 0, 0, 100, 3, 2, 1, 'V~O');
INSERT INTO `field` VALUES (23, 345, 'ship_city', 'invoiceshipads', 1, '1', 'ship_city', 'Shipping City', 1, 0, 0, 100, 4, 2, 1, 'V~O');
INSERT INTO `field` VALUES (23, 346, 'bill_state', 'invoicebillads', 1, '1', 'bill_state', 'Billing State', 1, 0, 0, 100, 5, 2, 1, 'V~O');
INSERT INTO `field` VALUES (23, 347, 'ship_state', 'invoiceshipads', 1, '1', 'ship_state', 'Shipping State', 1, 0, 0, 100, 6, 2, 1, 'V~O');
INSERT INTO `field` VALUES (23, 348, 'bill_code', 'invoicebillads', 1, '1', 'bill_code', 'Billing Code', 1, 0, 0, 100, 7, 2, 1, 'V~O');
INSERT INTO `field` VALUES (23, 349, 'ship_code', 'invoiceshipads', 1, '1', 'ship_code', 'Shipping Code', 1, 0, 0, 100, 8, 2, 1, 'V~O');
INSERT INTO `field` VALUES (23, 350, 'bill_country', 'invoicebillads', 1, '1', 'bill_country', 'Billing Country', 1, 0, 0, 100, 9, 2, 1, 'V~O');
INSERT INTO `field` VALUES (23, 351, 'ship_country', 'invoiceshipads', 1, '1', 'ship_country', 'Shipping Country', 1, 0, 0, 100, 10, 2, 1, 'V~O');
INSERT INTO `field` VALUES (23, 352, 'description', 'crmentity', 1, '19', 'description', 'Description', 1, 0, 0, 100, 1, 3, 1, 'V~O');
INSERT INTO `field` VALUES (23, 353, 'terms_conditions', 'invoice', 1, '19', 'terms_conditions', 'Terms & Conditions', 1, 0, 0, 100, 1, 6, 1, 'V~O');

-- --------------------------------------------------------

-- 
-- Structure de la table `field_seq`
-- 

CREATE TABLE `field_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `field_seq`
-- 

INSERT INTO `field_seq` VALUES (353);

-- --------------------------------------------------------

-- 
-- Structure de la table `files`
-- 

CREATE TABLE `files` (
  `id` varchar(36) NOT NULL,
  `name` varchar(36) default NULL,
  `content` longblob,
  `deleted` int(1) NOT NULL default '0',
  `date_entered` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `assigned_user_id` varchar(36) default NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_cont_owner_id_and_name` (`assigned_user_id`,`name`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `files`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `glacct`
-- 

CREATE TABLE `glacct` (
  `glacctid` int(19) NOT NULL auto_increment,
  `glacct` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`glacctid`),
  UNIQUE KEY `GlAcct_UK0` (`glacct`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Contenu de la table `glacct`
-- 

INSERT INTO `glacct` VALUES (1, '300-Sales-Software', 0, 1);
INSERT INTO `glacct` VALUES (2, '301-Sales-Hardware', 1, 1);
INSERT INTO `glacct` VALUES (3, '302-Rental-Income', 2, 1);
INSERT INTO `glacct` VALUES (4, '303-Interest-Income', 3, 1);
INSERT INTO `glacct` VALUES (5, '304-Sales-Software-Support', 4, 1);
INSERT INTO `glacct` VALUES (6, '305-Sales Other', 5, 1);
INSERT INTO `glacct` VALUES (7, '306-Internet Sales', 6, 1);
INSERT INTO `glacct` VALUES (8, '307-Service-Hardware Labor', 7, 1);
INSERT INTO `glacct` VALUES (9, '308-Sales-Books', 8, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `groups`
-- 

CREATE TABLE `groups` (
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `groups`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `headers`
-- 

CREATE TABLE `headers` (
  `fileid` int(3) NOT NULL auto_increment,
  `headernames` varchar(30) NOT NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `headers`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `import_maps`
-- 

CREATE TABLE `import_maps` (
  `id` int(19) NOT NULL auto_increment,
  `name` varchar(36) NOT NULL,
  `module` varchar(36) NOT NULL,
  `content` longblob,
  `has_header` int(1) NOT NULL default '1',
  `deleted` int(1) NOT NULL default '0',
  `date_entered` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL default '0000-00-00 00:00:00',
  `assigned_user_id` varchar(36) default NULL,
  `is_published` varchar(3) NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `idx_import_maps` (`assigned_user_id`,`module`,`name`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `import_maps`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `industry`
-- 

CREATE TABLE `industry` (
  `industryid` int(19) NOT NULL auto_increment,
  `industry` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`industryid`),
  UNIQUE KEY `Industry_UK0` (`industry`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- 
-- Contenu de la table `industry`
-- 

INSERT INTO `industry` VALUES (1, '--None--', 0, 1);
INSERT INTO `industry` VALUES (2, 'Apparel', 1, 1);
INSERT INTO `industry` VALUES (3, 'Banking', 2, 1);
INSERT INTO `industry` VALUES (4, 'Biotechnology', 3, 1);
INSERT INTO `industry` VALUES (5, 'Chemicals', 4, 1);
INSERT INTO `industry` VALUES (6, 'Communications', 5, 1);
INSERT INTO `industry` VALUES (7, 'Construction', 6, 1);
INSERT INTO `industry` VALUES (8, 'Consulting', 7, 1);
INSERT INTO `industry` VALUES (9, 'Education', 8, 1);
INSERT INTO `industry` VALUES (10, 'Electronics', 9, 1);
INSERT INTO `industry` VALUES (11, 'Energy', 10, 1);
INSERT INTO `industry` VALUES (12, 'Engineering', 11, 1);
INSERT INTO `industry` VALUES (13, 'Entertainment', 12, 1);
INSERT INTO `industry` VALUES (14, 'Environmental', 13, 1);
INSERT INTO `industry` VALUES (15, 'Finance', 14, 1);
INSERT INTO `industry` VALUES (16, 'Food & Beverage', 15, 1);
INSERT INTO `industry` VALUES (17, 'Government', 16, 1);
INSERT INTO `industry` VALUES (18, 'Healthcare', 17, 1);
INSERT INTO `industry` VALUES (19, 'Hospitality', 18, 1);
INSERT INTO `industry` VALUES (20, 'Insurance', 19, 1);
INSERT INTO `industry` VALUES (21, 'Machinery', 20, 1);
INSERT INTO `industry` VALUES (22, 'Manufacturing', 21, 1);
INSERT INTO `industry` VALUES (23, 'Media', 22, 1);
INSERT INTO `industry` VALUES (24, 'Not For Profit', 23, 1);
INSERT INTO `industry` VALUES (25, 'Recreation', 24, 1);
INSERT INTO `industry` VALUES (26, 'Retail', 25, 1);
INSERT INTO `industry` VALUES (27, 'Shipping', 26, 1);
INSERT INTO `industry` VALUES (28, 'Technology', 27, 1);
INSERT INTO `industry` VALUES (29, 'Telecommunications', 28, 1);
INSERT INTO `industry` VALUES (30, 'Transportation', 29, 1);
INSERT INTO `industry` VALUES (31, 'Utilities', 30, 1);
INSERT INTO `industry` VALUES (32, 'Other', 31, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `inventorynotification`
-- 

CREATE TABLE `inventorynotification` (
  `notificationid` int(19) NOT NULL auto_increment,
  `notificationname` varchar(200) default NULL,
  `notificationsubject` varchar(200) default NULL,
  `notificationbody` text,
  `label` varchar(50) default NULL,
  PRIMARY KEY  (`notificationid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `inventorynotification`
-- 

INSERT INTO `inventorynotification` VALUES (1, 'InvoiceNotification', '{PRODUCTNAME} Stock Level is Low', 'Dear {HANDLER},\n\nThe current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}. Kindly procure required number of units as the stock level is below reorder level {REORDERLEVELVALUE}.\n\nPlease treat this information as Urgent as the invoice is already sent  to the customer.\n\nSeverity: Critical\n\nThanks,\n{CURRENTUSER} ', 'InvoiceNotificationDescription');
INSERT INTO `inventorynotification` VALUES (2, 'QuoteNotification', 'Quote given for {PRODUCTNAME}', 'Dear {HANDLER},\n\nQuote is generated for {QUOTEQUANTITY} units of {PRODUCTNAME}. The current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}. \n\nSeverity: Minor\n\nThanks,\n{CURRENTUSER} ', 'QuoteNotificationDescription');
INSERT INTO `inventorynotification` VALUES (3, 'SalesOrderNotification', 'Sales Order generated for {PRODUCTNAME}', 'Dear {HANDLER},\n\nSalesOrder is generated for {SOQUANTITY} units of {PRODUCTNAME}. The current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}. \n\nPlease treat this information  with priority as the sales order is already generated.\n\nSeverity: Major\n\nThanks,\n{CURRENTUSER} ', 'SalesOrderNotificationDescription');

-- --------------------------------------------------------

-- 
-- Structure de la table `inventorynotification_seq`
-- 

CREATE TABLE `inventorynotification_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `inventorynotification_seq`
-- 

INSERT INTO `inventorynotification_seq` VALUES (3);

-- --------------------------------------------------------

-- 
-- Structure de la table `invoice`
-- 

CREATE TABLE `invoice` (
  `invoiceid` int(19) NOT NULL default '0',
  `subject` varchar(100) default NULL,
  `salesorderid` int(19) default NULL,
  `customerno` varchar(100) default NULL,
  `notes` varchar(100) default NULL,
  `invoicedate` date default NULL,
  `duedate` date default NULL,
  `invoiceterms` varchar(100) default NULL,
  `type` varchar(100) default NULL,
  `salestax` decimal(11,3) default NULL,
  `adjustment` decimal(11,3) default NULL,
  `salescommission` decimal(11,3) default NULL,
  `exciseduty` decimal(11,3) default NULL,
  `subtotal` decimal(11,3) default NULL,
  `total` decimal(11,3) default NULL,
  `shipping` varchar(100) default NULL,
  `accountid` int(19) default NULL,
  `terms_conditions` text,
  `purchaseorder` varchar(200) default NULL,
  `invoicestatus` varchar(200) default NULL,
  PRIMARY KEY  (`invoiceid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `invoice`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `invoicebillads`
-- 

CREATE TABLE `invoicebillads` (
  `invoicebilladdressid` int(19) NOT NULL default '0',
  `bill_city` varchar(30) default NULL,
  `bill_code` varchar(30) default NULL,
  `bill_country` varchar(30) default NULL,
  `bill_state` varchar(30) default NULL,
  `bill_street` varchar(250) default NULL,
  PRIMARY KEY  (`invoicebilladdressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `invoicebillads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `invoicecf`
-- 

CREATE TABLE `invoicecf` (
  `invoiceid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`invoiceid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `invoicecf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `invoiceproductrel`
-- 

CREATE TABLE `invoiceproductrel` (
  `invoiceid` int(19) NOT NULL,
  `productid` int(19) NOT NULL,
  `quantity` int(19) default NULL,
  `listprice` decimal(11,3) default NULL,
  PRIMARY KEY  (`invoiceid`,`productid`),
  KEY `InvoiceProductRel_IDX1` (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `invoiceproductrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `invoiceshipads`
-- 

CREATE TABLE `invoiceshipads` (
  `invoiceshipaddressid` int(19) NOT NULL default '0',
  `ship_city` varchar(30) default NULL,
  `ship_code` varchar(30) default NULL,
  `ship_country` varchar(30) default NULL,
  `ship_state` varchar(30) default NULL,
  `ship_street` varchar(250) default NULL,
  PRIMARY KEY  (`invoiceshipaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `invoiceshipads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `invoicestatus`
-- 

CREATE TABLE `invoicestatus` (
  `inovicestatusid` int(19) NOT NULL auto_increment,
  `invoicestatus` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`inovicestatusid`),
  UNIQUE KEY `invoicestatus_UK0` (`invoicestatus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `invoicestatus`
-- 

INSERT INTO `invoicestatus` VALUES (1, 'Created', 0, 1);
INSERT INTO `invoicestatus` VALUES (2, 'Approved', 1, 1);
INSERT INTO `invoicestatus` VALUES (3, 'Sent', 2, 1);
INSERT INTO `invoicestatus` VALUES (4, 'Credit Invoice', 3, 1);
INSERT INTO `invoicestatus` VALUES (5, 'Paid', 4, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `lar`
-- 

CREATE TABLE `lar` (
  `larid` int(19) NOT NULL default '0',
  `name` varchar(50) NOT NULL,
  `createdby` int(19) NOT NULL default '0',
  `createdon` date NOT NULL,
  PRIMARY KEY  (`larid`),
  UNIQUE KEY `LAR_UK0` (`name`),
  KEY `LAR_IDX0` (`createdby`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `lar`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `leadacctrel`
-- 

CREATE TABLE `leadacctrel` (
  `leadid` int(19) NOT NULL default '0',
  `accountid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`leadid`),
  KEY `LeadAcctRel_IDX1` (`accountid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `leadacctrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `leadaddress`
-- 

CREATE TABLE `leadaddress` (
  `leadaddressid` int(19) NOT NULL default '0',
  `city` varchar(30) default NULL,
  `code` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `country` varchar(30) default NULL,
  `phone` varchar(50) default NULL,
  `mobile` varchar(50) default NULL,
  `fax` varchar(50) default NULL,
  `lane` varchar(30) default NULL,
  `leadaddresstype` varchar(30) default 'Billing',
  PRIMARY KEY  (`leadaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `leadaddress`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `leadcontrel`
-- 

CREATE TABLE `leadcontrel` (
  `leadid` int(19) NOT NULL default '0',
  `contactid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`leadid`),
  KEY `LeadContRel_IDX1` (`contactid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `leadcontrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `leaddetails`
-- 

CREATE TABLE `leaddetails` (
  `leadid` int(19) NOT NULL,
  `email` varchar(100) default NULL,
  `interest` varchar(50) default NULL,
  `firstname` varchar(40) default NULL,
  `salutation` varchar(10) default NULL,
  `lastname` varchar(80) NOT NULL,
  `company` varchar(100) NOT NULL,
  `annualrevenue` int(19) default '0',
  `industry` varchar(50) default NULL,
  `campaign` varchar(30) default NULL,
  `rating` varchar(50) default NULL,
  `leadstatus` varchar(50) default NULL,
  `leadsource` varchar(50) default NULL,
  `converted` int(1) default '0',
  `designation` varchar(50) default 'SalesMan',
  `licencekeystatus` varchar(50) default NULL,
  `space` varchar(250) default NULL,
  `comments` text,
  `priority` varchar(50) default NULL,
  `demorequest` varchar(50) default NULL,
  `partnercontact` varchar(50) default NULL,
  `productversion` varchar(20) default NULL,
  `product` varchar(50) default NULL,
  `maildate` date default NULL,
  `nextstepdate` date default NULL,
  `fundingsituation` varchar(50) default NULL,
  `purpose` varchar(50) default NULL,
  `evaluationstatus` varchar(50) default NULL,
  `transferdate` date default NULL,
  `revenuetype` varchar(50) default NULL,
  `noofemployees` varchar(50) default NULL,
  `yahooid` varchar(100) default NULL,
  `assignleadchk` int(1) default '0',
  PRIMARY KEY  (`leadid`),
  KEY `converted` (`converted`,`leadstatus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `leaddetails`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `leadgrouprelation`
-- 

CREATE TABLE `leadgrouprelation` (
  `leadid` int(19) default NULL,
  `groupname` varchar(100) default NULL,
  KEY `leadgrouprelation_IDX0` (`leadid`),
  KEY `leadgrouprelation_IDX1` (`groupname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `leadgrouprelation`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `leadpotrel`
-- 

CREATE TABLE `leadpotrel` (
  `leadid` int(19) NOT NULL default '0',
  `potentialid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`potentialid`),
  UNIQUE KEY `LeadPotRel_UK0` (`leadid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `leadpotrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `leadscf`
-- 

CREATE TABLE `leadscf` (
  `leadid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`leadid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `leadscf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `leadsource`
-- 

CREATE TABLE `leadsource` (
  `leadsourceid` int(19) NOT NULL auto_increment,
  `leadsource` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`leadsourceid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- 
-- Contenu de la table `leadsource`
-- 

INSERT INTO `leadsource` VALUES (1, '--None--', 0, 1);
INSERT INTO `leadsource` VALUES (2, 'Cold Call', 1, 1);
INSERT INTO `leadsource` VALUES (3, 'Existing Customer', 2, 1);
INSERT INTO `leadsource` VALUES (4, 'Self Generated', 3, 1);
INSERT INTO `leadsource` VALUES (5, 'Employee', 4, 1);
INSERT INTO `leadsource` VALUES (6, 'Partner', 5, 1);
INSERT INTO `leadsource` VALUES (7, 'Public Relations', 6, 1);
INSERT INTO `leadsource` VALUES (8, 'Direct Mail', 7, 1);
INSERT INTO `leadsource` VALUES (9, 'Conference', 8, 1);
INSERT INTO `leadsource` VALUES (10, 'Trade Show', 9, 1);
INSERT INTO `leadsource` VALUES (11, 'Web Site', 10, 1);
INSERT INTO `leadsource` VALUES (12, 'Word of mouth', 11, 1);
INSERT INTO `leadsource` VALUES (13, 'Other', 12, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `leadstage`
-- 

CREATE TABLE `leadstage` (
  `leadstageid` int(19) NOT NULL auto_increment,
  `stage` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`leadstageid`),
  UNIQUE KEY `LeadStage_UK0` (`stage`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `leadstage`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `leadstatus`
-- 

CREATE TABLE `leadstatus` (
  `leadstatusid` int(19) NOT NULL auto_increment,
  `leadstatus` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`leadstatusid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- 
-- Contenu de la table `leadstatus`
-- 

INSERT INTO `leadstatus` VALUES (1, '--None--', 0, 1);
INSERT INTO `leadstatus` VALUES (2, 'Attempted to Contact', 1, 1);
INSERT INTO `leadstatus` VALUES (3, 'Cold', 2, 1);
INSERT INTO `leadstatus` VALUES (4, 'Contact in Future', 3, 1);
INSERT INTO `leadstatus` VALUES (5, 'Contacted', 4, 1);
INSERT INTO `leadstatus` VALUES (6, 'Hot', 5, 1);
INSERT INTO `leadstatus` VALUES (7, 'Junk Lead', 6, 1);
INSERT INTO `leadstatus` VALUES (8, 'Lost Lead', 7, 1);
INSERT INTO `leadstatus` VALUES (9, 'Not Contacted', 8, 1);
INSERT INTO `leadstatus` VALUES (10, 'Pre Qualified', 9, 1);
INSERT INTO `leadstatus` VALUES (11, 'Qualified', 10, 1);
INSERT INTO `leadstatus` VALUES (12, 'Warm', 11, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `leadsubdetails`
-- 

CREATE TABLE `leadsubdetails` (
  `leadsubscriptionid` int(19) NOT NULL default '0',
  `currency` varchar(20) default 'Dollars',
  `website` varchar(255) default NULL,
  `callornot` int(1) default '0',
  `readornot` int(1) default '0',
  `empct` int(10) default '0',
  PRIMARY KEY  (`leadsubscriptionid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `leadsubdetails`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `licencekeystatus`
-- 

CREATE TABLE `licencekeystatus` (
  `licencekeystatusid` int(19) NOT NULL auto_increment,
  `licencekeystatus` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`licencekeystatusid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `licencekeystatus`
-- 

INSERT INTO `licencekeystatus` VALUES (1, '--None--', 0, 1);
INSERT INTO `licencekeystatus` VALUES (2, 'Sent', 1, 1);
INSERT INTO `licencekeystatus` VALUES (3, 'Not Sent', 2, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `loginhistory`
-- 

CREATE TABLE `loginhistory` (
  `login_id` int(11) NOT NULL auto_increment,
  `user_name` varchar(25) NOT NULL,
  `user_ip` varchar(25) NOT NULL,
  `logout_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `login_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `status` varchar(25) default NULL,
  PRIMARY KEY  (`login_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `loginhistory`
-- 

INSERT INTO `loginhistory` VALUES (1, 'admin', '127.0.0.1', '0000-00-00 00:00:00', '2006-02-20 19:07:58', 'Signedin');

-- --------------------------------------------------------

-- 
-- Structure de la table `mail_accounts`
-- 

CREATE TABLE `mail_accounts` (
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `display_name` varchar(50) default NULL,
  `mail_id` varchar(50) default NULL,
  `account_name` varchar(50) default NULL,
  `mail_protocol` varchar(20) default NULL,
  `mail_username` varchar(50) NOT NULL,
  `mail_password` varchar(20) NOT NULL,
  `mail_servername` varchar(50) default NULL,
  `status` varchar(10) default NULL,
  `set_default` int(2) default NULL,
  PRIMARY KEY  (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `mail_accounts`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `manufacturer`
-- 

CREATE TABLE `manufacturer` (
  `manufacturerid` int(19) NOT NULL auto_increment,
  `manufacturer` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`manufacturerid`),
  UNIQUE KEY `Manufacturer_MFR` (`manufacturer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `manufacturer`
-- 

INSERT INTO `manufacturer` VALUES (1, '--None--', 0, 1);
INSERT INTO `manufacturer` VALUES (2, 'AltvetPet Inc.', 1, 1);
INSERT INTO `manufacturer` VALUES (3, 'LexPon Inc.', 2, 1);
INSERT INTO `manufacturer` VALUES (4, 'MetBeat Corp', 3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `notes`
-- 

CREATE TABLE `notes` (
  `notesid` int(19) NOT NULL default '0',
  `contact_id` int(19) NOT NULL default '0',
  `title` varchar(50) NOT NULL,
  `filename` varchar(50) default NULL,
  `notecontent` text,
  PRIMARY KEY  (`notesid`,`contact_id`),
  KEY `Notes_UK0` (`title`),
  KEY `Notes_IDX0` (`notesid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `notes`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `notificationscheduler`
-- 

CREATE TABLE `notificationscheduler` (
  `schedulednotificationid` int(19) NOT NULL auto_increment,
  `schedulednotificationname` varchar(200) default NULL,
  `active` int(1) default NULL,
  `notificationsubject` varchar(200) default NULL,
  `notificationbody` text,
  `label` varchar(50) default NULL,
  PRIMARY KEY  (`schedulednotificationid`),
  UNIQUE KEY `notificationscheduler_UK0` (`schedulednotificationname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- 
-- Contenu de la table `notificationscheduler`
-- 

INSERT INTO `notificationscheduler` VALUES (1, 'LBL_TASK_NOTIFICATION_DESCRITPION', 1, 'Task Delay Notification', 'Tasks delayed beyond 24 hrs ', 'LBL_TASK_NOTIFICATION');
INSERT INTO `notificationscheduler` VALUES (2, 'LBL_BIG_DEAL_DESCRIPTION', 1, 'Big Deal notification', 'Success! A big deal has been won! ', 'LBL_BIG_DEAL');
INSERT INTO `notificationscheduler` VALUES (3, 'LBL_TICKETS_DESCRIPTION', 1, 'Pending Tickets notification', 'Ticket pending please ', 'LBL_PENDING_TICKETS');
INSERT INTO `notificationscheduler` VALUES (4, 'LBL_MANY_TICKETS_DESCRIPTION', 1, 'Too many tickets Notification', 'Too many tickets pending against this entity ', 'LBL_MANY_TICKETS');
INSERT INTO `notificationscheduler` VALUES (5, 'LBL_START_DESCRIPTION', 1, 'Support Start Notification', 'Support starts please ', 'LBL_START_NOTIFICATION');
INSERT INTO `notificationscheduler` VALUES (6, 'LBL_SUPPORT_DESCRIPTION', 1, 'Support ending please', 'Support Ending Notification', 'LBL_SUPPORT_NOTICIATION');
INSERT INTO `notificationscheduler` VALUES (7, 'LBL_ACTIVITY_REMINDER_DESCRIPTION', 1, 'Activity Reminder Notication', 'This is a reminder notification for the Activity', 'LBL_ACTIVITY_NOTIFICATION');

-- --------------------------------------------------------

-- 
-- Structure de la table `notificationscheduler_seq`
-- 

CREATE TABLE `notificationscheduler_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `notificationscheduler_seq`
-- 

INSERT INTO `notificationscheduler_seq` VALUES (7);

-- --------------------------------------------------------

-- 
-- Structure de la table `opportunity_type`
-- 

CREATE TABLE `opportunity_type` (
  `opptypeid` int(19) NOT NULL auto_increment,
  `opportunity_type` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`opptypeid`),
  UNIQUE KEY `Opportunity_UK0` (`opportunity_type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `opportunity_type`
-- 

INSERT INTO `opportunity_type` VALUES (1, '--None--', 0, 1);
INSERT INTO `opportunity_type` VALUES (2, 'Existing Business', 1, 1);
INSERT INTO `opportunity_type` VALUES (3, 'New Business', 2, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `organizationdetails`
-- 

CREATE TABLE `organizationdetails` (
  `organizationame` varchar(60) default NULL,
  `address` varchar(150) default NULL,
  `city` varchar(100) default NULL,
  `state` varchar(100) default NULL,
  `country` varchar(100) default NULL,
  `code` varchar(30) default NULL,
  `phone` varchar(30) default NULL,
  `fax` varchar(30) default NULL,
  `website` varchar(50) default NULL,
  `logoname` varchar(50) default NULL,
  `logo` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `organizationdetails`
-- 

INSERT INTO `organizationdetails` VALUES ('vtiger', ' 40-41-42, Sivasundar Apartments, Flat D-II, Shastri Street, Velachery', 'Chennai', 'Tamil Nadu', 'India', '600 042', '+91-44-5202-1990', '+91-44-5202-1990', 'www.vtiger.com', 'vtiger-crm-logo.jpg', NULL);

-- --------------------------------------------------------

-- 
-- Structure de la table `pobillads`
-- 

CREATE TABLE `pobillads` (
  `pobilladdressid` int(19) NOT NULL default '0',
  `bill_city` varchar(30) default NULL,
  `bill_code` varchar(30) default NULL,
  `bill_country` varchar(30) default NULL,
  `bill_state` varchar(30) default NULL,
  `bill_street` varchar(250) default NULL,
  PRIMARY KEY  (`pobilladdressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `pobillads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `poproductrel`
-- 

CREATE TABLE `poproductrel` (
  `purchaseorderid` int(19) NOT NULL,
  `productid` int(19) NOT NULL,
  `quantity` int(19) default NULL,
  `listprice` decimal(11,3) default NULL,
  PRIMARY KEY  (`purchaseorderid`,`productid`),
  KEY `PoProductRel_IDX1` (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `poproductrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `poshipads`
-- 

CREATE TABLE `poshipads` (
  `poshipaddressid` int(19) NOT NULL default '0',
  `ship_city` varchar(30) default NULL,
  `ship_code` varchar(30) default NULL,
  `ship_country` varchar(30) default NULL,
  `ship_state` varchar(30) default NULL,
  `ship_street` varchar(250) default NULL,
  PRIMARY KEY  (`poshipaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `poshipads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `postatus`
-- 

CREATE TABLE `postatus` (
  `postatusid` int(19) NOT NULL auto_increment,
  `postatus` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`postatusid`),
  UNIQUE KEY `postatus_UK0` (`postatus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `postatus`
-- 

INSERT INTO `postatus` VALUES (1, 'Created', 0, 1);
INSERT INTO `postatus` VALUES (2, 'Approved', 1, 1);
INSERT INTO `postatus` VALUES (3, 'Delivered', 2, 1);
INSERT INTO `postatus` VALUES (4, 'Canceled', 3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `potcompetitorrel`
-- 

CREATE TABLE `potcompetitorrel` (
  `potentialid` int(19) NOT NULL,
  `competitorid` int(19) NOT NULL,
  PRIMARY KEY  (`potentialid`,`competitorid`),
  KEY `PotCompetitorRel_IDX0` (`potentialid`),
  KEY `PotCompetitorRel_IDX1` (`competitorid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `potcompetitorrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `potential`
-- 

CREATE TABLE `potential` (
  `potentialid` int(19) NOT NULL default '0',
  `accountid` int(19) default NULL,
  `potentialname` varchar(120) NOT NULL,
  `amount` decimal(10,0) default '0',
  `currency` varchar(20) default NULL,
  `closingdate` date default NULL,
  `typeofrevenue` varchar(50) default NULL,
  `campaignsource` varchar(30) default NULL,
  `nextstep` varchar(100) default NULL,
  `private` int(1) default '0',
  `probability` decimal(7,3) default '0.000',
  `sales_stage` varchar(50) default NULL,
  `potentialtype` varchar(50) default NULL,
  `leadsource` varchar(50) default NULL,
  `productid` int(50) default NULL,
  `productversion` varchar(50) default NULL,
  `quotationref` varchar(50) default NULL,
  `partnercontact` varchar(50) default NULL,
  `remarks` varchar(50) default NULL,
  `runtimefee` int(19) default '0',
  `followupdate` date default NULL,
  `evaluationstatus` varchar(50) default NULL,
  `description` text,
  `forecastcategory` int(19) default '0',
  `outcomeanalysis` int(19) default '0',
  PRIMARY KEY  (`potentialid`),
  KEY `Potential_IDX0` (`accountid`),
  KEY `potentialid` (`potentialid`),
  KEY `sales_stage` (`sales_stage`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `potential`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `potentialscf`
-- 

CREATE TABLE `potentialscf` (
  `potentialid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`potentialid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `potentialscf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `pricebook`
-- 

CREATE TABLE `pricebook` (
  `pricebookid` int(19) NOT NULL default '0',
  `bookname` varchar(100) default NULL,
  `active` int(1) default NULL,
  `description` text,
  PRIMARY KEY  (`pricebookid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `pricebook`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `pricebookcf`
-- 

CREATE TABLE `pricebookcf` (
  `pricebookid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`pricebookid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `pricebookcf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `pricebookproductrel`
-- 

CREATE TABLE `pricebookproductrel` (
  `pricebookid` int(19) NOT NULL,
  `productid` int(19) NOT NULL,
  `listprice` decimal(11,3) default NULL,
  PRIMARY KEY  (`pricebookid`,`productid`),
  KEY `PriceBookProductRel_IDX0` (`pricebookid`),
  KEY `PriceBookProductRel_IDX1` (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `pricebookproductrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `priority`
-- 

CREATE TABLE `priority` (
  `priorityid` int(19) NOT NULL auto_increment,
  `priority` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`priorityid`),
  UNIQUE KEY `Priority_UK0` (`priority`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `priority`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `productcategory`
-- 

CREATE TABLE `productcategory` (
  `productcategoryid` int(19) NOT NULL auto_increment,
  `productcategory` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`productcategoryid`),
  UNIQUE KEY `ProductCategory_UK0` (`productcategory`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `productcategory`
-- 

INSERT INTO `productcategory` VALUES (1, '--None--', 0, 1);
INSERT INTO `productcategory` VALUES (2, 'Hardware', 1, 1);
INSERT INTO `productcategory` VALUES (3, 'Software', 2, 1);
INSERT INTO `productcategory` VALUES (4, 'CRM Applications', 3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `productcf`
-- 

CREATE TABLE `productcf` (
  `productid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `productcf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `productcollaterals`
-- 

CREATE TABLE `productcollaterals` (
  `productid` int(11) NOT NULL,
  `date_entered` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `data` longblob,
  `description` text,
  `filename` varchar(50) default NULL,
  `filesize` varchar(50) NOT NULL,
  `filetype` varchar(20) NOT NULL,
  PRIMARY KEY  (`productid`),
  KEY `idx_collaterals_name` (`productid`,`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `productcollaterals`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `products`
-- 

CREATE TABLE `products` (
  `productid` int(11) NOT NULL,
  `productname` varchar(50) NOT NULL,
  `productcode` varchar(40) default NULL,
  `productcategory` varchar(40) default NULL,
  `manufacturer` varchar(40) default NULL,
  `product_description` text,
  `qty_per_unit` decimal(11,2) default '0.00',
  `unit_price` decimal(11,2) default NULL,
  `weight` decimal(11,3) default NULL,
  `pack_size` int(11) default NULL,
  `sales_start_date` date default NULL,
  `sales_end_date` date default NULL,
  `start_date` date default NULL,
  `expiry_date` date default NULL,
  `cost_factor` int(11) default NULL,
  `commissionrate` decimal(3,3) default NULL,
  `commissionmethod` varchar(50) default NULL,
  `discontinued` int(1) default NULL,
  `usageunit` varchar(200) default NULL,
  `handler` int(11) default NULL,
  `contactid` int(11) default NULL,
  `currency` varchar(200) default NULL,
  `reorderlevel` int(11) default NULL,
  `website` varchar(100) default NULL,
  `taxclass` varchar(200) default NULL,
  `mfr_part_no` varchar(200) default NULL,
  `vendor_part_no` varchar(200) default NULL,
  `serialno` varchar(200) default NULL,
  `qtyinstock` int(11) default NULL,
  `productsheet` varchar(200) default NULL,
  `qtyindemand` int(11) default NULL,
  `glacct` varchar(200) default NULL,
  `vendor_id` int(11) default NULL,
  `imagename` varchar(150) default NULL,
  PRIMARY KEY  (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `products`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `profile`
-- 

CREATE TABLE `profile` (
  `profileid` int(10) NOT NULL auto_increment,
  `profilename` varchar(50) NOT NULL,
  PRIMARY KEY  (`profileid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `profile`
-- 

INSERT INTO `profile` VALUES (1, 'Administrator');
INSERT INTO `profile` VALUES (2, 'Sales Profile');
INSERT INTO `profile` VALUES (3, 'Support Profile');
INSERT INTO `profile` VALUES (4, 'Guest Profile');

-- --------------------------------------------------------

-- 
-- Structure de la table `profile2field`
-- 

CREATE TABLE `profile2field` (
  `profileid` int(11) default NULL,
  `tabid` int(10) default NULL,
  `fieldid` int(19) default NULL,
  `visible` int(19) default NULL,
  `readonly` int(19) default NULL,
  KEY `idx_prof2fileid` (`profileid`,`tabid`),
  KEY `tabid` (`tabid`,`profileid`),
  KEY `visible` (`visible`,`profileid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `profile2field`
-- 

INSERT INTO `profile2field` VALUES (1, 6, 1, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 2, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 3, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 4, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 5, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 6, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 7, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 8, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 9, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 10, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 11, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 12, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 13, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 14, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 15, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 16, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 17, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 18, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 19, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 20, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 21, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 22, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 23, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 24, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 25, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 26, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 27, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 28, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 29, 0, 1);
INSERT INTO `profile2field` VALUES (1, 6, 30, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 32, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 33, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 34, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 35, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 36, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 37, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 38, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 39, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 40, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 41, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 42, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 43, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 44, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 45, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 46, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 47, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 48, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 49, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 50, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 51, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 52, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 53, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 54, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 55, 0, 1);
INSERT INTO `profile2field` VALUES (1, 7, 56, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 58, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 59, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 60, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 61, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 62, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 63, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 64, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 65, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 66, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 67, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 68, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 69, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 70, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 71, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 72, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 73, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 74, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 75, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 76, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 77, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 78, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 79, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 80, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 81, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 82, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 83, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 84, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 85, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 86, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 87, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 88, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 89, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 90, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 91, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 92, 0, 1);
INSERT INTO `profile2field` VALUES (1, 4, 93, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 94, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 95, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 96, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 97, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 98, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 99, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 100, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 101, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 102, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 103, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 104, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 105, 0, 1);
INSERT INTO `profile2field` VALUES (1, 2, 106, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 107, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 108, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 109, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 110, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 111, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 112, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 113, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 115, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 116, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 117, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 118, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 119, 0, 1);
INSERT INTO `profile2field` VALUES (1, 13, 120, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 121, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 122, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 123, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 124, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 125, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 126, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 127, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 128, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 129, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 130, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 131, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 132, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 133, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 134, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 135, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 136, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 137, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 138, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 139, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 140, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 141, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 142, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 143, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 144, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 145, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 146, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 147, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 148, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 149, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 150, 0, 1);
INSERT INTO `profile2field` VALUES (1, 14, 151, 0, 1);
INSERT INTO `profile2field` VALUES (1, 8, 152, 0, 1);
INSERT INTO `profile2field` VALUES (1, 8, 153, 0, 1);
INSERT INTO `profile2field` VALUES (1, 8, 154, 0, 1);
INSERT INTO `profile2field` VALUES (1, 8, 155, 0, 1);
INSERT INTO `profile2field` VALUES (1, 8, 156, 0, 1);
INSERT INTO `profile2field` VALUES (1, 8, 157, 0, 1);
INSERT INTO `profile2field` VALUES (1, 8, 158, 0, 1);
INSERT INTO `profile2field` VALUES (1, 10, 159, 0, 1);
INSERT INTO `profile2field` VALUES (1, 10, 162, 0, 1);
INSERT INTO `profile2field` VALUES (1, 10, 163, 0, 1);
INSERT INTO `profile2field` VALUES (1, 10, 164, 0, 1);
INSERT INTO `profile2field` VALUES (1, 10, 165, 0, 1);
INSERT INTO `profile2field` VALUES (1, 10, 166, 0, 1);
INSERT INTO `profile2field` VALUES (1, 10, 168, 0, 1);
INSERT INTO `profile2field` VALUES (1, 10, 169, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 170, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 171, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 172, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 174, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 175, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 176, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 177, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 179, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 180, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 181, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 182, 0, 1);
INSERT INTO `profile2field` VALUES (1, 9, 184, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 190, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 191, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 192, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 194, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 195, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 196, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 198, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 199, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 200, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 201, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 202, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 203, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 204, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 205, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 206, 0, 1);
INSERT INTO `profile2field` VALUES (1, 16, 207, 0, 1);
INSERT INTO `profile2field` VALUES (1, 15, 208, 0, 1);
INSERT INTO `profile2field` VALUES (1, 15, 209, 0, 1);
INSERT INTO `profile2field` VALUES (1, 15, 210, 0, 1);
INSERT INTO `profile2field` VALUES (1, 15, 211, 0, 1);
INSERT INTO `profile2field` VALUES (1, 15, 212, 0, 1);
INSERT INTO `profile2field` VALUES (1, 15, 213, 0, 1);
INSERT INTO `profile2field` VALUES (1, 15, 214, 0, 1);
INSERT INTO `profile2field` VALUES (1, 15, 215, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 216, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 217, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 218, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 219, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 220, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 221, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 222, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 223, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 224, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 225, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 226, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 227, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 228, 0, 1);
INSERT INTO `profile2field` VALUES (1, 18, 229, 0, 1);
INSERT INTO `profile2field` VALUES (1, 19, 230, 0, 1);
INSERT INTO `profile2field` VALUES (1, 19, 231, 0, 1);
INSERT INTO `profile2field` VALUES (1, 19, 232, 0, 1);
INSERT INTO `profile2field` VALUES (1, 19, 233, 0, 1);
INSERT INTO `profile2field` VALUES (1, 19, 234, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 235, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 236, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 237, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 238, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 239, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 240, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 241, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 243, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 244, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 248, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 249, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 250, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 251, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 252, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 253, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 254, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 255, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 256, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 257, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 258, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 259, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 260, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 261, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 262, 0, 1);
INSERT INTO `profile2field` VALUES (1, 20, 263, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 264, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 265, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 266, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 267, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 268, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 269, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 270, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 273, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 274, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 277, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 278, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 279, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 280, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 281, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 282, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 283, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 284, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 285, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 286, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 287, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 288, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 289, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 290, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 291, 0, 1);
INSERT INTO `profile2field` VALUES (1, 21, 292, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 293, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 294, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 295, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 296, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 297, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 298, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 299, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 300, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 301, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 302, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 305, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 306, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 309, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 310, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 311, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 312, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 313, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 314, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 315, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 316, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 317, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 318, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 319, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 320, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 321, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 322, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 323, 0, 1);
INSERT INTO `profile2field` VALUES (1, 22, 324, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 325, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 326, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 327, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 328, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 329, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 330, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 333, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 334, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 337, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 338, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 339, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 340, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 341, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 342, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 343, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 344, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 345, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 346, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 347, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 348, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 349, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 350, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 351, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 352, 0, 1);
INSERT INTO `profile2field` VALUES (1, 23, 353, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 1, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 2, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 3, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 4, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 5, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 6, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 7, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 8, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 9, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 10, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 11, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 12, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 13, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 14, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 15, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 16, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 17, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 18, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 19, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 20, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 21, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 22, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 23, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 24, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 25, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 26, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 27, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 28, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 29, 0, 1);
INSERT INTO `profile2field` VALUES (2, 6, 30, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 32, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 33, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 34, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 35, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 36, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 37, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 38, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 39, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 40, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 41, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 42, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 43, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 44, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 45, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 46, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 47, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 48, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 49, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 50, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 51, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 52, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 53, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 54, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 55, 0, 1);
INSERT INTO `profile2field` VALUES (2, 7, 56, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 58, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 59, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 60, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 61, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 62, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 63, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 64, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 65, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 66, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 67, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 68, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 69, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 70, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 71, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 72, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 73, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 74, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 75, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 76, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 77, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 78, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 79, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 80, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 81, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 82, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 83, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 84, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 85, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 86, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 87, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 88, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 89, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 90, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 91, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 92, 0, 1);
INSERT INTO `profile2field` VALUES (2, 4, 93, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 94, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 95, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 96, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 97, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 98, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 99, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 100, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 101, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 102, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 103, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 104, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 105, 0, 1);
INSERT INTO `profile2field` VALUES (2, 2, 106, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 107, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 108, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 109, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 110, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 111, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 112, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 113, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 115, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 116, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 117, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 118, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 119, 0, 1);
INSERT INTO `profile2field` VALUES (2, 13, 120, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 121, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 122, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 123, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 124, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 125, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 126, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 127, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 128, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 129, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 130, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 131, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 132, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 133, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 134, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 135, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 136, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 137, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 138, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 139, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 140, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 141, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 142, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 143, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 144, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 145, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 146, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 147, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 148, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 149, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 150, 0, 1);
INSERT INTO `profile2field` VALUES (2, 14, 151, 0, 1);
INSERT INTO `profile2field` VALUES (2, 8, 152, 0, 1);
INSERT INTO `profile2field` VALUES (2, 8, 153, 0, 1);
INSERT INTO `profile2field` VALUES (2, 8, 154, 0, 1);
INSERT INTO `profile2field` VALUES (2, 8, 155, 0, 1);
INSERT INTO `profile2field` VALUES (2, 8, 156, 0, 1);
INSERT INTO `profile2field` VALUES (2, 8, 157, 0, 1);
INSERT INTO `profile2field` VALUES (2, 8, 158, 0, 1);
INSERT INTO `profile2field` VALUES (2, 10, 159, 0, 1);
INSERT INTO `profile2field` VALUES (2, 10, 162, 0, 1);
INSERT INTO `profile2field` VALUES (2, 10, 163, 0, 1);
INSERT INTO `profile2field` VALUES (2, 10, 164, 0, 1);
INSERT INTO `profile2field` VALUES (2, 10, 165, 0, 1);
INSERT INTO `profile2field` VALUES (2, 10, 166, 0, 1);
INSERT INTO `profile2field` VALUES (2, 10, 168, 0, 1);
INSERT INTO `profile2field` VALUES (2, 10, 169, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 170, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 171, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 172, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 174, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 175, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 176, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 177, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 179, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 180, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 181, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 182, 0, 1);
INSERT INTO `profile2field` VALUES (2, 9, 184, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 190, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 191, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 192, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 194, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 195, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 196, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 198, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 199, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 200, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 201, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 202, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 203, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 204, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 205, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 206, 0, 1);
INSERT INTO `profile2field` VALUES (2, 16, 207, 0, 1);
INSERT INTO `profile2field` VALUES (2, 15, 208, 0, 1);
INSERT INTO `profile2field` VALUES (2, 15, 209, 0, 1);
INSERT INTO `profile2field` VALUES (2, 15, 210, 0, 1);
INSERT INTO `profile2field` VALUES (2, 15, 211, 0, 1);
INSERT INTO `profile2field` VALUES (2, 15, 212, 0, 1);
INSERT INTO `profile2field` VALUES (2, 15, 213, 0, 1);
INSERT INTO `profile2field` VALUES (2, 15, 214, 0, 1);
INSERT INTO `profile2field` VALUES (2, 15, 215, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 216, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 217, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 218, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 219, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 220, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 221, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 222, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 223, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 224, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 225, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 226, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 227, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 228, 0, 1);
INSERT INTO `profile2field` VALUES (2, 18, 229, 0, 1);
INSERT INTO `profile2field` VALUES (2, 19, 230, 0, 1);
INSERT INTO `profile2field` VALUES (2, 19, 231, 0, 1);
INSERT INTO `profile2field` VALUES (2, 19, 232, 0, 1);
INSERT INTO `profile2field` VALUES (2, 19, 233, 0, 1);
INSERT INTO `profile2field` VALUES (2, 19, 234, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 235, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 236, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 237, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 238, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 239, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 240, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 241, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 243, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 244, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 248, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 249, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 250, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 251, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 252, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 253, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 254, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 255, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 256, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 257, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 258, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 259, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 260, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 261, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 262, 0, 1);
INSERT INTO `profile2field` VALUES (2, 20, 263, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 264, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 265, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 266, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 267, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 268, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 269, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 270, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 273, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 274, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 277, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 278, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 279, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 280, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 281, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 282, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 283, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 284, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 285, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 286, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 287, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 288, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 289, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 290, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 291, 0, 1);
INSERT INTO `profile2field` VALUES (2, 21, 292, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 293, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 294, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 295, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 296, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 297, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 298, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 299, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 300, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 301, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 302, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 305, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 306, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 309, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 310, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 311, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 312, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 313, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 314, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 315, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 316, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 317, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 318, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 319, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 320, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 321, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 322, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 323, 0, 1);
INSERT INTO `profile2field` VALUES (2, 22, 324, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 325, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 326, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 327, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 328, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 329, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 330, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 333, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 334, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 337, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 338, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 339, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 340, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 341, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 342, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 343, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 344, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 345, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 346, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 347, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 348, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 349, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 350, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 351, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 352, 0, 1);
INSERT INTO `profile2field` VALUES (2, 23, 353, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 1, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 2, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 3, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 4, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 5, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 6, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 7, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 8, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 9, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 10, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 11, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 12, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 13, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 14, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 15, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 16, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 17, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 18, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 19, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 20, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 21, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 22, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 23, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 24, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 25, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 26, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 27, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 28, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 29, 0, 1);
INSERT INTO `profile2field` VALUES (3, 6, 30, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 32, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 33, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 34, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 35, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 36, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 37, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 38, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 39, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 40, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 41, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 42, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 43, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 44, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 45, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 46, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 47, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 48, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 49, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 50, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 51, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 52, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 53, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 54, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 55, 0, 1);
INSERT INTO `profile2field` VALUES (3, 7, 56, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 58, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 59, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 60, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 61, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 62, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 63, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 64, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 65, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 66, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 67, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 68, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 69, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 70, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 71, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 72, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 73, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 74, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 75, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 76, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 77, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 78, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 79, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 80, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 81, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 82, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 83, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 84, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 85, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 86, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 87, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 88, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 89, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 90, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 91, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 92, 0, 1);
INSERT INTO `profile2field` VALUES (3, 4, 93, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 94, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 95, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 96, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 97, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 98, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 99, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 100, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 101, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 102, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 103, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 104, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 105, 0, 1);
INSERT INTO `profile2field` VALUES (3, 2, 106, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 107, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 108, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 109, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 110, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 111, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 112, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 113, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 115, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 116, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 117, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 118, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 119, 0, 1);
INSERT INTO `profile2field` VALUES (3, 13, 120, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 121, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 122, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 123, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 124, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 125, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 126, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 127, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 128, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 129, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 130, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 131, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 132, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 133, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 134, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 135, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 136, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 137, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 138, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 139, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 140, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 141, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 142, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 143, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 144, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 145, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 146, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 147, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 148, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 149, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 150, 0, 1);
INSERT INTO `profile2field` VALUES (3, 14, 151, 0, 1);
INSERT INTO `profile2field` VALUES (3, 8, 152, 0, 1);
INSERT INTO `profile2field` VALUES (3, 8, 153, 0, 1);
INSERT INTO `profile2field` VALUES (3, 8, 154, 0, 1);
INSERT INTO `profile2field` VALUES (3, 8, 155, 0, 1);
INSERT INTO `profile2field` VALUES (3, 8, 156, 0, 1);
INSERT INTO `profile2field` VALUES (3, 8, 157, 0, 1);
INSERT INTO `profile2field` VALUES (3, 8, 158, 0, 1);
INSERT INTO `profile2field` VALUES (3, 10, 159, 0, 1);
INSERT INTO `profile2field` VALUES (3, 10, 162, 0, 1);
INSERT INTO `profile2field` VALUES (3, 10, 163, 0, 1);
INSERT INTO `profile2field` VALUES (3, 10, 164, 0, 1);
INSERT INTO `profile2field` VALUES (3, 10, 165, 0, 1);
INSERT INTO `profile2field` VALUES (3, 10, 166, 0, 1);
INSERT INTO `profile2field` VALUES (3, 10, 168, 0, 1);
INSERT INTO `profile2field` VALUES (3, 10, 169, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 170, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 171, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 172, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 174, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 175, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 176, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 177, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 179, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 180, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 181, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 182, 0, 1);
INSERT INTO `profile2field` VALUES (3, 9, 184, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 190, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 191, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 192, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 194, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 195, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 196, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 198, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 199, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 200, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 201, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 202, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 203, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 204, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 205, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 206, 0, 1);
INSERT INTO `profile2field` VALUES (3, 16, 207, 0, 1);
INSERT INTO `profile2field` VALUES (3, 15, 208, 0, 1);
INSERT INTO `profile2field` VALUES (3, 15, 209, 0, 1);
INSERT INTO `profile2field` VALUES (3, 15, 210, 0, 1);
INSERT INTO `profile2field` VALUES (3, 15, 211, 0, 1);
INSERT INTO `profile2field` VALUES (3, 15, 212, 0, 1);
INSERT INTO `profile2field` VALUES (3, 15, 213, 0, 1);
INSERT INTO `profile2field` VALUES (3, 15, 214, 0, 1);
INSERT INTO `profile2field` VALUES (3, 15, 215, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 216, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 217, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 218, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 219, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 220, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 221, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 222, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 223, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 224, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 225, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 226, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 227, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 228, 0, 1);
INSERT INTO `profile2field` VALUES (3, 18, 229, 0, 1);
INSERT INTO `profile2field` VALUES (3, 19, 230, 0, 1);
INSERT INTO `profile2field` VALUES (3, 19, 231, 0, 1);
INSERT INTO `profile2field` VALUES (3, 19, 232, 0, 1);
INSERT INTO `profile2field` VALUES (3, 19, 233, 0, 1);
INSERT INTO `profile2field` VALUES (3, 19, 234, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 235, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 236, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 237, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 238, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 239, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 240, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 241, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 243, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 244, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 248, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 249, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 250, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 251, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 252, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 253, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 254, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 255, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 256, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 257, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 258, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 259, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 260, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 261, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 262, 0, 1);
INSERT INTO `profile2field` VALUES (3, 20, 263, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 264, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 265, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 266, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 267, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 268, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 269, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 270, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 273, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 274, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 277, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 278, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 279, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 280, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 281, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 282, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 283, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 284, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 285, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 286, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 287, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 288, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 289, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 290, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 291, 0, 1);
INSERT INTO `profile2field` VALUES (3, 21, 292, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 293, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 294, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 295, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 296, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 297, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 298, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 299, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 300, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 301, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 302, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 305, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 306, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 309, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 310, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 311, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 312, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 313, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 314, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 315, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 316, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 317, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 318, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 319, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 320, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 321, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 322, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 323, 0, 1);
INSERT INTO `profile2field` VALUES (3, 22, 324, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 325, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 326, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 327, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 328, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 329, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 330, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 333, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 334, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 337, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 338, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 339, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 340, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 341, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 342, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 343, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 344, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 345, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 346, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 347, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 348, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 349, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 350, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 351, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 352, 0, 1);
INSERT INTO `profile2field` VALUES (3, 23, 353, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 1, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 2, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 3, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 4, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 5, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 6, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 7, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 8, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 9, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 10, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 11, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 12, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 13, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 14, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 15, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 16, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 17, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 18, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 19, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 20, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 21, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 22, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 23, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 24, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 25, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 26, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 27, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 28, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 29, 0, 1);
INSERT INTO `profile2field` VALUES (4, 6, 30, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 32, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 33, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 34, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 35, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 36, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 37, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 38, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 39, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 40, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 41, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 42, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 43, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 44, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 45, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 46, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 47, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 48, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 49, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 50, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 51, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 52, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 53, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 54, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 55, 0, 1);
INSERT INTO `profile2field` VALUES (4, 7, 56, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 58, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 59, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 60, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 61, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 62, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 63, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 64, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 65, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 66, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 67, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 68, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 69, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 70, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 71, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 72, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 73, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 74, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 75, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 76, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 77, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 78, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 79, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 80, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 81, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 82, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 83, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 84, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 85, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 86, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 87, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 88, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 89, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 90, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 91, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 92, 0, 1);
INSERT INTO `profile2field` VALUES (4, 4, 93, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 94, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 95, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 96, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 97, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 98, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 99, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 100, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 101, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 102, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 103, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 104, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 105, 0, 1);
INSERT INTO `profile2field` VALUES (4, 2, 106, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 107, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 108, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 109, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 110, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 111, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 112, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 113, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 115, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 116, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 117, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 118, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 119, 0, 1);
INSERT INTO `profile2field` VALUES (4, 13, 120, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 121, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 122, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 123, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 124, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 125, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 126, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 127, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 128, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 129, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 130, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 131, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 132, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 133, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 134, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 135, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 136, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 137, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 138, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 139, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 140, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 141, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 142, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 143, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 144, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 145, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 146, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 147, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 148, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 149, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 150, 0, 1);
INSERT INTO `profile2field` VALUES (4, 14, 151, 0, 1);
INSERT INTO `profile2field` VALUES (4, 8, 152, 0, 1);
INSERT INTO `profile2field` VALUES (4, 8, 153, 0, 1);
INSERT INTO `profile2field` VALUES (4, 8, 154, 0, 1);
INSERT INTO `profile2field` VALUES (4, 8, 155, 0, 1);
INSERT INTO `profile2field` VALUES (4, 8, 156, 0, 1);
INSERT INTO `profile2field` VALUES (4, 8, 157, 0, 1);
INSERT INTO `profile2field` VALUES (4, 8, 158, 0, 1);
INSERT INTO `profile2field` VALUES (4, 10, 159, 0, 1);
INSERT INTO `profile2field` VALUES (4, 10, 162, 0, 1);
INSERT INTO `profile2field` VALUES (4, 10, 163, 0, 1);
INSERT INTO `profile2field` VALUES (4, 10, 164, 0, 1);
INSERT INTO `profile2field` VALUES (4, 10, 165, 0, 1);
INSERT INTO `profile2field` VALUES (4, 10, 166, 0, 1);
INSERT INTO `profile2field` VALUES (4, 10, 168, 0, 1);
INSERT INTO `profile2field` VALUES (4, 10, 169, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 170, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 171, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 172, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 174, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 175, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 176, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 177, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 179, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 180, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 181, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 182, 0, 1);
INSERT INTO `profile2field` VALUES (4, 9, 184, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 190, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 191, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 192, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 194, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 195, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 196, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 198, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 199, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 200, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 201, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 202, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 203, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 204, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 205, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 206, 0, 1);
INSERT INTO `profile2field` VALUES (4, 16, 207, 0, 1);
INSERT INTO `profile2field` VALUES (4, 15, 208, 0, 1);
INSERT INTO `profile2field` VALUES (4, 15, 209, 0, 1);
INSERT INTO `profile2field` VALUES (4, 15, 210, 0, 1);
INSERT INTO `profile2field` VALUES (4, 15, 211, 0, 1);
INSERT INTO `profile2field` VALUES (4, 15, 212, 0, 1);
INSERT INTO `profile2field` VALUES (4, 15, 213, 0, 1);
INSERT INTO `profile2field` VALUES (4, 15, 214, 0, 1);
INSERT INTO `profile2field` VALUES (4, 15, 215, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 216, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 217, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 218, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 219, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 220, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 221, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 222, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 223, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 224, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 225, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 226, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 227, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 228, 0, 1);
INSERT INTO `profile2field` VALUES (4, 18, 229, 0, 1);
INSERT INTO `profile2field` VALUES (4, 19, 230, 0, 1);
INSERT INTO `profile2field` VALUES (4, 19, 231, 0, 1);
INSERT INTO `profile2field` VALUES (4, 19, 232, 0, 1);
INSERT INTO `profile2field` VALUES (4, 19, 233, 0, 1);
INSERT INTO `profile2field` VALUES (4, 19, 234, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 235, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 236, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 237, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 238, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 239, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 240, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 241, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 243, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 244, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 248, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 249, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 250, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 251, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 252, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 253, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 254, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 255, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 256, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 257, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 258, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 259, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 260, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 261, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 262, 0, 1);
INSERT INTO `profile2field` VALUES (4, 20, 263, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 264, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 265, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 266, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 267, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 268, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 269, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 270, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 273, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 274, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 277, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 278, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 279, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 280, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 281, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 282, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 283, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 284, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 285, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 286, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 287, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 288, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 289, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 290, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 291, 0, 1);
INSERT INTO `profile2field` VALUES (4, 21, 292, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 293, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 294, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 295, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 296, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 297, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 298, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 299, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 300, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 301, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 302, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 305, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 306, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 309, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 310, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 311, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 312, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 313, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 314, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 315, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 316, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 317, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 318, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 319, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 320, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 321, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 322, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 323, 0, 1);
INSERT INTO `profile2field` VALUES (4, 22, 324, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 325, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 326, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 327, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 328, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 329, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 330, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 333, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 334, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 337, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 338, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 339, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 340, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 341, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 342, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 343, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 344, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 345, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 346, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 347, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 348, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 349, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 350, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 351, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 352, 0, 1);
INSERT INTO `profile2field` VALUES (4, 23, 353, 0, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `profile2standardpermissions`
-- 

CREATE TABLE `profile2standardpermissions` (
  `profileid` int(11) default NULL,
  `tabid` int(10) default NULL,
  `Operation` int(10) default NULL,
  `permissions` int(1) default NULL,
  KEY `idx_prof2stad` (`profileid`,`tabid`,`Operation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `profile2standardpermissions`
-- 

INSERT INTO `profile2standardpermissions` VALUES (1, 1, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 1, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 1, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 1, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 1, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 2, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 2, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 2, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 2, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 2, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 3, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 3, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 3, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 3, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 3, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 4, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 4, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 4, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 4, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 4, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 6, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 6, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 6, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 6, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 6, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 7, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 7, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 7, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 7, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 7, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 8, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 8, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 8, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 8, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 8, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 9, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 9, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 9, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 9, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 9, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 10, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 10, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 10, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 10, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 10, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 13, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 13, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 13, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 13, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 13, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 14, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 14, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 14, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 14, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 14, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 15, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 15, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 15, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 15, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 15, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 16, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 16, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 16, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 16, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 16, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 18, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 18, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 18, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 18, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 18, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 19, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 19, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 19, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 19, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 19, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 20, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 20, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 20, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 20, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 20, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 21, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 21, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 21, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 21, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 21, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 22, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 22, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 22, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 22, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 22, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 23, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 23, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 23, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 23, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (1, 23, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 1, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 1, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 1, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 1, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 1, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 2, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 2, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 2, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 2, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 2, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 3, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 3, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 3, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 3, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 3, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 4, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 4, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 4, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 4, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 4, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 6, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 6, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 6, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 6, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 6, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 7, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 7, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 7, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 7, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 7, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 8, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 8, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 8, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 8, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 8, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 9, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 9, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 9, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 9, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 9, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 10, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 10, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 10, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 10, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 10, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 13, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (2, 13, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (2, 13, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (2, 13, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 13, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 14, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 14, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 14, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 14, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 14, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 15, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 15, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 15, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 15, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 15, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 16, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 16, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 16, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 16, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 16, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 18, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 18, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 18, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 18, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 18, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 19, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 19, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 19, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 19, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 19, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 20, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 20, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 20, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 20, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 20, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 21, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 21, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 21, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 21, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 21, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 22, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 22, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 22, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 22, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 22, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 23, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 23, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 23, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 23, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (2, 23, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 1, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 1, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 1, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 1, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 1, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 2, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (3, 2, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (3, 2, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (3, 2, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 2, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 3, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 3, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 3, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 3, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 3, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 4, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 4, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 4, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 4, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 4, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 6, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 6, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 6, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 6, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 6, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 7, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 7, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 7, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 7, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 7, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 8, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 8, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 8, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 8, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 8, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 9, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 9, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 9, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 9, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 9, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 10, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 10, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 10, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 10, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 10, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 13, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 13, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 13, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 13, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 13, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 14, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 14, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 14, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 14, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 14, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 15, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 15, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 15, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 15, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 15, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 16, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 16, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 16, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 16, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 16, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 18, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 18, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 18, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 18, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 18, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 19, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 19, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 19, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 19, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 19, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 20, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 20, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 20, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 20, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 20, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 21, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 21, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 21, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 21, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 21, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 22, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 22, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 22, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 22, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 22, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 23, 0, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 23, 1, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 23, 2, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 23, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (3, 23, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 1, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 1, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 1, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 1, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 1, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 2, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 2, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 2, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 2, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 2, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 3, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 3, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 3, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 3, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 3, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 4, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 4, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 4, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 4, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 4, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 6, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 6, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 6, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 6, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 6, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 7, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 7, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 7, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 7, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 7, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 8, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 8, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 8, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 8, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 8, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 9, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 9, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 9, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 9, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 9, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 10, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 10, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 10, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 10, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 10, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 13, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 13, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 13, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 13, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 13, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 14, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 14, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 14, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 14, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 14, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 15, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 15, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 15, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 15, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 15, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 16, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 16, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 16, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 16, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 16, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 18, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 18, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 18, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 18, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 18, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 19, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 19, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 19, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 19, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 19, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 20, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 20, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 20, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 20, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 20, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 21, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 21, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 21, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 21, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 21, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 22, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 22, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 22, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 22, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 22, 4, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 23, 0, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 23, 1, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 23, 2, 1);
INSERT INTO `profile2standardpermissions` VALUES (4, 23, 3, 0);
INSERT INTO `profile2standardpermissions` VALUES (4, 23, 4, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `profile2tab`
-- 

CREATE TABLE `profile2tab` (
  `profileid` int(11) default NULL,
  `tabid` int(10) default NULL,
  `permissions` int(10) NOT NULL default '0',
  KEY `idx_profile2tab` (`profileid`,`tabid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `profile2tab`
-- 

INSERT INTO `profile2tab` VALUES (1, 1, 0);
INSERT INTO `profile2tab` VALUES (1, 2, 0);
INSERT INTO `profile2tab` VALUES (1, 3, 0);
INSERT INTO `profile2tab` VALUES (1, 4, 0);
INSERT INTO `profile2tab` VALUES (1, 6, 0);
INSERT INTO `profile2tab` VALUES (1, 7, 0);
INSERT INTO `profile2tab` VALUES (1, 8, 0);
INSERT INTO `profile2tab` VALUES (1, 9, 0);
INSERT INTO `profile2tab` VALUES (1, 10, 0);
INSERT INTO `profile2tab` VALUES (1, 13, 0);
INSERT INTO `profile2tab` VALUES (1, 14, 0);
INSERT INTO `profile2tab` VALUES (1, 15, 0);
INSERT INTO `profile2tab` VALUES (1, 16, 0);
INSERT INTO `profile2tab` VALUES (1, 17, 0);
INSERT INTO `profile2tab` VALUES (1, 18, 0);
INSERT INTO `profile2tab` VALUES (1, 19, 0);
INSERT INTO `profile2tab` VALUES (1, 20, 0);
INSERT INTO `profile2tab` VALUES (1, 21, 0);
INSERT INTO `profile2tab` VALUES (1, 22, 0);
INSERT INTO `profile2tab` VALUES (1, 23, 0);
INSERT INTO `profile2tab` VALUES (1, 24, 0);
INSERT INTO `profile2tab` VALUES (1, 25, 0);
INSERT INTO `profile2tab` VALUES (2, 1, 0);
INSERT INTO `profile2tab` VALUES (2, 2, 0);
INSERT INTO `profile2tab` VALUES (2, 3, 0);
INSERT INTO `profile2tab` VALUES (2, 4, 0);
INSERT INTO `profile2tab` VALUES (2, 6, 0);
INSERT INTO `profile2tab` VALUES (2, 7, 0);
INSERT INTO `profile2tab` VALUES (2, 8, 0);
INSERT INTO `profile2tab` VALUES (2, 9, 0);
INSERT INTO `profile2tab` VALUES (2, 10, 0);
INSERT INTO `profile2tab` VALUES (2, 13, 0);
INSERT INTO `profile2tab` VALUES (2, 14, 0);
INSERT INTO `profile2tab` VALUES (2, 15, 0);
INSERT INTO `profile2tab` VALUES (2, 16, 0);
INSERT INTO `profile2tab` VALUES (2, 17, 0);
INSERT INTO `profile2tab` VALUES (2, 18, 0);
INSERT INTO `profile2tab` VALUES (2, 19, 0);
INSERT INTO `profile2tab` VALUES (2, 20, 0);
INSERT INTO `profile2tab` VALUES (2, 21, 0);
INSERT INTO `profile2tab` VALUES (2, 22, 0);
INSERT INTO `profile2tab` VALUES (2, 23, 0);
INSERT INTO `profile2tab` VALUES (2, 24, 0);
INSERT INTO `profile2tab` VALUES (2, 25, 0);
INSERT INTO `profile2tab` VALUES (3, 1, 0);
INSERT INTO `profile2tab` VALUES (3, 2, 0);
INSERT INTO `profile2tab` VALUES (3, 3, 0);
INSERT INTO `profile2tab` VALUES (3, 4, 0);
INSERT INTO `profile2tab` VALUES (3, 6, 0);
INSERT INTO `profile2tab` VALUES (3, 7, 0);
INSERT INTO `profile2tab` VALUES (3, 8, 0);
INSERT INTO `profile2tab` VALUES (3, 9, 0);
INSERT INTO `profile2tab` VALUES (3, 10, 0);
INSERT INTO `profile2tab` VALUES (3, 13, 0);
INSERT INTO `profile2tab` VALUES (3, 14, 0);
INSERT INTO `profile2tab` VALUES (3, 15, 0);
INSERT INTO `profile2tab` VALUES (3, 16, 0);
INSERT INTO `profile2tab` VALUES (3, 17, 0);
INSERT INTO `profile2tab` VALUES (3, 18, 0);
INSERT INTO `profile2tab` VALUES (3, 19, 0);
INSERT INTO `profile2tab` VALUES (3, 20, 0);
INSERT INTO `profile2tab` VALUES (3, 21, 0);
INSERT INTO `profile2tab` VALUES (3, 22, 0);
INSERT INTO `profile2tab` VALUES (3, 23, 0);
INSERT INTO `profile2tab` VALUES (3, 24, 0);
INSERT INTO `profile2tab` VALUES (3, 25, 0);
INSERT INTO `profile2tab` VALUES (4, 1, 0);
INSERT INTO `profile2tab` VALUES (4, 2, 0);
INSERT INTO `profile2tab` VALUES (4, 3, 0);
INSERT INTO `profile2tab` VALUES (4, 4, 0);
INSERT INTO `profile2tab` VALUES (4, 6, 0);
INSERT INTO `profile2tab` VALUES (4, 7, 0);
INSERT INTO `profile2tab` VALUES (4, 8, 0);
INSERT INTO `profile2tab` VALUES (4, 9, 0);
INSERT INTO `profile2tab` VALUES (4, 10, 0);
INSERT INTO `profile2tab` VALUES (4, 13, 0);
INSERT INTO `profile2tab` VALUES (4, 14, 0);
INSERT INTO `profile2tab` VALUES (4, 15, 0);
INSERT INTO `profile2tab` VALUES (4, 16, 0);
INSERT INTO `profile2tab` VALUES (4, 17, 0);
INSERT INTO `profile2tab` VALUES (4, 18, 0);
INSERT INTO `profile2tab` VALUES (4, 19, 0);
INSERT INTO `profile2tab` VALUES (4, 20, 0);
INSERT INTO `profile2tab` VALUES (4, 21, 0);
INSERT INTO `profile2tab` VALUES (4, 22, 0);
INSERT INTO `profile2tab` VALUES (4, 23, 0);
INSERT INTO `profile2tab` VALUES (4, 24, 0);
INSERT INTO `profile2tab` VALUES (4, 25, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `profile2utility`
-- 

CREATE TABLE `profile2utility` (
  `profileid` int(11) default NULL,
  `tabid` int(11) default NULL,
  `activityid` int(11) default NULL,
  `permission` int(1) default NULL,
  KEY `idx_prof2utility` (`profileid`,`tabid`,`activityid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `profile2utility`
-- 

INSERT INTO `profile2utility` VALUES (1, 2, 5, 0);
INSERT INTO `profile2utility` VALUES (1, 2, 6, 0);
INSERT INTO `profile2utility` VALUES (1, 4, 5, 0);
INSERT INTO `profile2utility` VALUES (1, 4, 6, 0);
INSERT INTO `profile2utility` VALUES (1, 6, 5, 0);
INSERT INTO `profile2utility` VALUES (1, 6, 6, 0);
INSERT INTO `profile2utility` VALUES (1, 7, 5, 0);
INSERT INTO `profile2utility` VALUES (1, 7, 6, 0);
INSERT INTO `profile2utility` VALUES (1, 8, 6, 0);
INSERT INTO `profile2utility` VALUES (1, 10, 6, 0);
INSERT INTO `profile2utility` VALUES (1, 7, 8, 0);
INSERT INTO `profile2utility` VALUES (1, 6, 8, 0);
INSERT INTO `profile2utility` VALUES (1, 4, 8, 0);
INSERT INTO `profile2utility` VALUES (1, 14, 5, 0);
INSERT INTO `profile2utility` VALUES (1, 14, 6, 0);
INSERT INTO `profile2utility` VALUES (2, 2, 5, 1);
INSERT INTO `profile2utility` VALUES (2, 2, 6, 1);
INSERT INTO `profile2utility` VALUES (2, 4, 5, 1);
INSERT INTO `profile2utility` VALUES (2, 4, 6, 1);
INSERT INTO `profile2utility` VALUES (2, 6, 5, 1);
INSERT INTO `profile2utility` VALUES (2, 6, 6, 1);
INSERT INTO `profile2utility` VALUES (2, 7, 5, 1);
INSERT INTO `profile2utility` VALUES (2, 7, 6, 1);
INSERT INTO `profile2utility` VALUES (2, 8, 6, 1);
INSERT INTO `profile2utility` VALUES (2, 10, 6, 1);
INSERT INTO `profile2utility` VALUES (2, 7, 8, 0);
INSERT INTO `profile2utility` VALUES (2, 6, 8, 0);
INSERT INTO `profile2utility` VALUES (2, 4, 8, 0);
INSERT INTO `profile2utility` VALUES (2, 14, 5, 1);
INSERT INTO `profile2utility` VALUES (2, 14, 6, 1);
INSERT INTO `profile2utility` VALUES (3, 2, 5, 1);
INSERT INTO `profile2utility` VALUES (3, 2, 6, 1);
INSERT INTO `profile2utility` VALUES (3, 4, 5, 1);
INSERT INTO `profile2utility` VALUES (3, 4, 6, 1);
INSERT INTO `profile2utility` VALUES (3, 6, 5, 1);
INSERT INTO `profile2utility` VALUES (3, 6, 6, 1);
INSERT INTO `profile2utility` VALUES (3, 7, 5, 1);
INSERT INTO `profile2utility` VALUES (3, 7, 6, 1);
INSERT INTO `profile2utility` VALUES (3, 8, 6, 1);
INSERT INTO `profile2utility` VALUES (3, 10, 6, 1);
INSERT INTO `profile2utility` VALUES (3, 7, 8, 0);
INSERT INTO `profile2utility` VALUES (3, 6, 8, 0);
INSERT INTO `profile2utility` VALUES (3, 4, 8, 0);
INSERT INTO `profile2utility` VALUES (3, 14, 5, 1);
INSERT INTO `profile2utility` VALUES (3, 14, 6, 1);
INSERT INTO `profile2utility` VALUES (4, 2, 5, 1);
INSERT INTO `profile2utility` VALUES (4, 2, 6, 1);
INSERT INTO `profile2utility` VALUES (4, 4, 5, 1);
INSERT INTO `profile2utility` VALUES (4, 4, 6, 1);
INSERT INTO `profile2utility` VALUES (4, 6, 5, 1);
INSERT INTO `profile2utility` VALUES (4, 6, 6, 1);
INSERT INTO `profile2utility` VALUES (4, 7, 5, 1);
INSERT INTO `profile2utility` VALUES (4, 7, 6, 1);
INSERT INTO `profile2utility` VALUES (4, 8, 6, 1);
INSERT INTO `profile2utility` VALUES (4, 10, 6, 1);
INSERT INTO `profile2utility` VALUES (4, 7, 8, 1);
INSERT INTO `profile2utility` VALUES (4, 6, 8, 1);
INSERT INTO `profile2utility` VALUES (4, 4, 8, 1);
INSERT INTO `profile2utility` VALUES (4, 14, 5, 1);
INSERT INTO `profile2utility` VALUES (4, 14, 6, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `profile_seq`
-- 

CREATE TABLE `profile_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `profile_seq`
-- 

INSERT INTO `profile_seq` VALUES (4);

-- --------------------------------------------------------

-- 
-- Structure de la table `purchaseorder`
-- 

CREATE TABLE `purchaseorder` (
  `purchaseorderid` int(19) NOT NULL default '0',
  `subject` varchar(100) default NULL,
  `quoteid` int(19) default NULL,
  `vendorid` int(19) default NULL,
  `requisition_no` varchar(100) default NULL,
  `tracking_no` varchar(100) default NULL,
  `contactid` int(19) default NULL,
  `duedate` date default NULL,
  `carrier` varchar(100) default NULL,
  `type` varchar(100) default NULL,
  `salestax` decimal(11,3) default NULL,
  `adjustment` decimal(11,3) default NULL,
  `salescommission` decimal(11,3) default NULL,
  `exciseduty` decimal(11,3) default NULL,
  `total` decimal(11,3) default NULL,
  `subtotal` decimal(11,3) default NULL,
  `terms_conditions` text,
  `postatus` varchar(200) default NULL,
  PRIMARY KEY  (`purchaseorderid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `purchaseorder`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `purchaseordercf`
-- 

CREATE TABLE `purchaseordercf` (
  `purchaseorderid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`purchaseorderid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `purchaseordercf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `quotes`
-- 

CREATE TABLE `quotes` (
  `quoteid` int(19) NOT NULL default '0',
  `subject` varchar(100) default NULL,
  `potentialid` int(19) default NULL,
  `quotestage` varchar(200) default NULL,
  `validtill` date default NULL,
  `team` varchar(200) default NULL,
  `contactid` int(19) default NULL,
  `currency` varchar(100) default NULL,
  `subtotal` decimal(11,3) default NULL,
  `carrier` varchar(100) default NULL,
  `shipping` varchar(100) default NULL,
  `inventorymanager` int(19) default NULL,
  `type` varchar(100) default NULL,
  `tax` decimal(11,3) default NULL,
  `adjustment` decimal(11,3) default NULL,
  `total` decimal(11,3) default NULL,
  `accountid` int(19) default NULL,
  `terms_conditions` text,
  PRIMARY KEY  (`quoteid`),
  KEY `quotestage` (`quotestage`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `quotes`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `quotesbillads`
-- 

CREATE TABLE `quotesbillads` (
  `quotebilladdressid` int(19) NOT NULL default '0',
  `bill_city` varchar(30) default NULL,
  `bill_code` varchar(30) default NULL,
  `bill_country` varchar(30) default NULL,
  `bill_state` varchar(30) default NULL,
  `bill_street` varchar(250) default NULL,
  PRIMARY KEY  (`quotebilladdressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `quotesbillads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `quotescf`
-- 

CREATE TABLE `quotescf` (
  `quoteid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`quoteid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `quotescf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `quotesproductrel`
-- 

CREATE TABLE `quotesproductrel` (
  `quoteid` int(19) NOT NULL,
  `productid` int(19) NOT NULL,
  `quantity` int(19) default NULL,
  `listprice` decimal(11,3) default NULL,
  PRIMARY KEY  (`quoteid`,`productid`),
  KEY `QuotesProductRel_IDX0` (`quoteid`),
  KEY `QuotesProductRel_IDX1` (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `quotesproductrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `quotesshipads`
-- 

CREATE TABLE `quotesshipads` (
  `quoteshipaddressid` int(19) NOT NULL default '0',
  `ship_city` varchar(30) default NULL,
  `ship_code` varchar(30) default NULL,
  `ship_country` varchar(30) default NULL,
  `ship_state` varchar(30) default NULL,
  `ship_street` varchar(250) default NULL,
  PRIMARY KEY  (`quoteshipaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `quotesshipads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `quotestage`
-- 

CREATE TABLE `quotestage` (
  `quotestageid` int(19) NOT NULL auto_increment,
  `quotestage` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`quotestageid`),
  UNIQUE KEY `quotestage_UK0` (`quotestage`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `quotestage`
-- 

INSERT INTO `quotestage` VALUES (1, 'Created', 0, 1);
INSERT INTO `quotestage` VALUES (2, 'Delivered', 1, 1);
INSERT INTO `quotestage` VALUES (3, 'Reviewed', 2, 1);
INSERT INTO `quotestage` VALUES (4, 'Accepted', 3, 1);
INSERT INTO `quotestage` VALUES (5, 'Rejected', 4, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `rating`
-- 

CREATE TABLE `rating` (
  `rating_id` int(19) NOT NULL auto_increment,
  `rating` varchar(200) default NULL,
  `SORTORDERID` int(19) NOT NULL default '0',
  `PRESENCE` int(1) NOT NULL default '1',
  PRIMARY KEY  (`rating_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Contenu de la table `rating`
-- 

INSERT INTO `rating` VALUES (1, '--None--', 0, 1);
INSERT INTO `rating` VALUES (2, 'Acquired', 1, 1);
INSERT INTO `rating` VALUES (3, 'Active', 2, 1);
INSERT INTO `rating` VALUES (4, 'Market Failed', 3, 1);
INSERT INTO `rating` VALUES (5, 'Project Cancelled', 4, 1);
INSERT INTO `rating` VALUES (6, 'Shutdown', 5, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `recurringevents`
-- 

CREATE TABLE `recurringevents` (
  `recurringid` int(19) NOT NULL auto_increment,
  `activityid` int(19) NOT NULL,
  `recurringdate` date default NULL,
  `recurringtype` varchar(30) default NULL,
  PRIMARY KEY  (`recurringid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `recurringevents`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `recurringtype`
-- 

CREATE TABLE `recurringtype` (
  `recurringeventid` int(19) NOT NULL auto_increment,
  `recurringtype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`recurringeventid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `recurringtype`
-- 

INSERT INTO `recurringtype` VALUES (1, '--None--', 0, 1);
INSERT INTO `recurringtype` VALUES (2, 'Daily', 1, 1);
INSERT INTO `recurringtype` VALUES (3, 'Weekly', 2, 1);
INSERT INTO `recurringtype` VALUES (4, 'Monthly', 3, 1);
INSERT INTO `recurringtype` VALUES (5, 'Yearly', 4, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `relatedlists`
-- 

CREATE TABLE `relatedlists` (
  `relation_id` int(19) NOT NULL,
  `tabid` int(10) default NULL,
  `related_tabid` int(10) default NULL,
  `name` varchar(100) default NULL,
  `sequence` int(10) default NULL,
  `label` varchar(100) default NULL,
  `presence` int(10) NOT NULL default '0',
  PRIMARY KEY  (`relation_id`),
  KEY `idx_profile2tab` (`relation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `relatedlists`
-- 

INSERT INTO `relatedlists` VALUES (1, 6, 2, 'get_opportunities', 1, 'Potentials', 0);
INSERT INTO `relatedlists` VALUES (2, 6, 4, 'get_contacts', 2, 'Contacts', 0);
INSERT INTO `relatedlists` VALUES (3, 6, 9, 'get_activities', 3, 'Acivities', 0);
INSERT INTO `relatedlists` VALUES (4, 6, 13, 'get_tickets', 4, 'HelpDesk', 0);
INSERT INTO `relatedlists` VALUES (5, 6, 9, 'get_history', 5, 'History', 0);
INSERT INTO `relatedlists` VALUES (6, 6, 0, 'get_attachments', 6, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (7, 6, 20, 'get_quotes', 7, 'Quotes', 0);
INSERT INTO `relatedlists` VALUES (8, 6, 23, 'get_invoices', 8, 'Invoice', 0);
INSERT INTO `relatedlists` VALUES (9, 6, 22, 'get_salesorder', 9, 'Sales Order', 0);
INSERT INTO `relatedlists` VALUES (10, 6, 14, 'get_products', 10, 'Products', 0);
INSERT INTO `relatedlists` VALUES (11, 7, 9, 'get_activities', 1, 'Activities', 0);
INSERT INTO `relatedlists` VALUES (12, 7, 10, 'get_emails', 2, 'Emails', 0);
INSERT INTO `relatedlists` VALUES (13, 7, 9, 'get_history', 3, 'History', 0);
INSERT INTO `relatedlists` VALUES (14, 7, 0, 'get_attachments', 4, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (15, 7, 14, 'get_products', 5, 'Products', 0);
INSERT INTO `relatedlists` VALUES (16, 4, 2, 'get_opportunities', 1, 'Potentials', 0);
INSERT INTO `relatedlists` VALUES (17, 4, 9, 'get_activities', 2, 'Activities', 0);
INSERT INTO `relatedlists` VALUES (18, 4, 10, 'get_emails', 3, 'Emails', 0);
INSERT INTO `relatedlists` VALUES (19, 4, 13, 'get_tickets', 4, 'HelpDesk', 0);
INSERT INTO `relatedlists` VALUES (20, 4, 20, 'get_quotes', 5, 'Quotes', 0);
INSERT INTO `relatedlists` VALUES (21, 4, 21, 'get_purchase_orders', 6, 'Purchase Order', 0);
INSERT INTO `relatedlists` VALUES (22, 4, 22, 'get_salesorder', 7, 'Sales Order', 0);
INSERT INTO `relatedlists` VALUES (23, 4, 14, 'get_products', 8, 'Products', 0);
INSERT INTO `relatedlists` VALUES (24, 4, 9, 'get_history', 9, 'History', 0);
INSERT INTO `relatedlists` VALUES (25, 4, 0, 'get_attachments', 10, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (26, 2, 9, 'get_activities', 1, 'Activities', 0);
INSERT INTO `relatedlists` VALUES (27, 2, 4, 'get_contacts', 2, 'Contacts', 0);
INSERT INTO `relatedlists` VALUES (28, 2, 14, 'get_products', 3, 'History', 0);
INSERT INTO `relatedlists` VALUES (29, 2, 0, 'get_stage_history', 4, 'Sales Stage History', 0);
INSERT INTO `relatedlists` VALUES (30, 2, 0, 'get_attachments', 5, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (31, 2, 20, 'get_Quotes', 6, 'Quotes', 0);
INSERT INTO `relatedlists` VALUES (32, 2, 22, 'get_salesorder', 7, 'Sales Order', 0);
INSERT INTO `relatedlists` VALUES (33, 2, 9, 'get_history', 8, 'History', 0);
INSERT INTO `relatedlists` VALUES (34, 14, 13, 'get_tickets', 1, 'HelpDesk', 0);
INSERT INTO `relatedlists` VALUES (35, 14, 9, 'get_activities', 2, 'Activities', 0);
INSERT INTO `relatedlists` VALUES (36, 14, 0, 'get_attachments', 3, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (37, 14, 20, 'get_quotes', 4, 'Quotes', 0);
INSERT INTO `relatedlists` VALUES (38, 14, 21, 'get_purchase_orders', 5, 'Purchase Order', 0);
INSERT INTO `relatedlists` VALUES (39, 14, 22, 'get_salesorder', 6, 'Sales Order', 0);
INSERT INTO `relatedlists` VALUES (40, 14, 23, 'get_invoices', 7, 'Invoice', 0);
INSERT INTO `relatedlists` VALUES (41, 14, 19, 'get_product_pricebooks', 8, 'PriceBook', 0);
INSERT INTO `relatedlists` VALUES (42, 10, 4, 'get_contacts', 1, 'Contacts', 0);
INSERT INTO `relatedlists` VALUES (43, 10, 0, 'get_users', 2, 'Users', 0);
INSERT INTO `relatedlists` VALUES (44, 10, 0, 'get_attachments', 3, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (45, 13, 9, 'get_activities', 1, 'Activities', 0);
INSERT INTO `relatedlists` VALUES (46, 13, 0, 'get_attachments', 2, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (47, 19, 14, 'get_pricebook_products', 2, 'Products', 0);
INSERT INTO `relatedlists` VALUES (48, 18, 14, 'get_products', 1, 'Products', 0);
INSERT INTO `relatedlists` VALUES (49, 18, 21, 'get_purchase_orders', 2, 'Products', 0);
INSERT INTO `relatedlists` VALUES (50, 18, 4, 'get_contacts', 3, 'Contacts', 0);
INSERT INTO `relatedlists` VALUES (51, 20, 23, 'get_salesorder', 1, 'Sales Order', 0);
INSERT INTO `relatedlists` VALUES (52, 20, 9, 'get_activities', 2, 'Activities', 0);
INSERT INTO `relatedlists` VALUES (53, 20, 9, 'get_history', 3, 'History', 0);
INSERT INTO `relatedlists` VALUES (54, 21, 9, 'get_activities', 1, 'Activities', 0);
INSERT INTO `relatedlists` VALUES (55, 21, 0, 'get_attachments', 2, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (56, 21, 9, 'get_history', 3, 'History', 0);
INSERT INTO `relatedlists` VALUES (57, 22, 9, 'get_activities', 1, 'Activities', 0);
INSERT INTO `relatedlists` VALUES (58, 22, 0, 'get_attachments', 2, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (59, 22, 23, 'get_invoices', 3, 'Invoice', 0);
INSERT INTO `relatedlists` VALUES (60, 22, 9, 'get_history', 4, 'History', 0);
INSERT INTO `relatedlists` VALUES (61, 23, 9, 'get_activities', 1, 'Activities', 0);
INSERT INTO `relatedlists` VALUES (62, 23, 0, 'get_attachments', 2, 'Attachments', 0);
INSERT INTO `relatedlists` VALUES (63, 23, 9, 'get_history', 3, 'History', 0);
INSERT INTO `relatedlists` VALUES (64, 9, 0, 'get_users', 1, 'Users', 0);
INSERT INTO `relatedlists` VALUES (65, 9, 4, 'get_contacts', 2, 'Contacts', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `relatedlists_seq`
-- 

CREATE TABLE `relatedlists_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `relatedlists_seq`
-- 

INSERT INTO `relatedlists_seq` VALUES (65);

-- --------------------------------------------------------

-- 
-- Structure de la table `relcriteria`
-- 

CREATE TABLE `relcriteria` (
  `queryid` int(19) default NULL,
  `columnindex` int(11) NOT NULL,
  `columnname` varchar(250) default '',
  `comparator` varchar(10) default '',
  `value` varchar(200) default '',
  KEY `relcriteria_IDX0` (`queryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `relcriteria`
-- 

INSERT INTO `relcriteria` VALUES (1, 0, 'accountContacts:accountname:Contacts_Account_Name:account_id:I', 'n', '');
INSERT INTO `relcriteria` VALUES (2, 0, 'accountContacts:accountname:Contacts_Account_Name:account_id:I', 'e', '');
INSERT INTO `relcriteria` VALUES (3, 0, 'potential:potentialname:Potentials_Potential_Name:potentialname:V', 'n', '');
INSERT INTO `relcriteria` VALUES (7, 0, 'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V', 'e', 'Closed Won');
INSERT INTO `relcriteria` VALUES (12, 0, 'troubletickets:status:HelpDesk_Status:ticketstatus:V', 'n', 'Closed');
INSERT INTO `relcriteria` VALUES (15, 0, 'quotes:quotestage:Quotes_Quote_Stage:quotestage:V', 'n', 'Accepted');
INSERT INTO `relcriteria` VALUES (15, 1, 'quotes:quotestage:Quotes_Quote_Stage:quotestage:V', 'n', 'Rejected');

-- --------------------------------------------------------

-- 
-- Structure de la table `report`
-- 

CREATE TABLE `report` (
  `reportid` int(19) NOT NULL,
  `folderid` int(19) NOT NULL,
  `reportname` varchar(100) default '',
  `description` varchar(250) default '',
  `reporttype` varchar(50) default '',
  `queryid` int(19) NOT NULL default '0',
  `state` varchar(50) default 'SAVED',
  `customizable` int(1) default '1',
  `category` int(11) default '1',
  PRIMARY KEY  (`reportid`),
  KEY `report_IDX0` (`queryid`),
  KEY `report_IDX1` (`folderid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `report`
-- 

INSERT INTO `report` VALUES (1, 1, 'Contacts by Accounts', 'Contacts related to Accounts', 'tabular', 1, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (2, 1, 'Contacts without Accounts', 'Contacts not related to Accounts', 'tabular', 2, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (3, 1, 'Contacts by Potentials', 'Contacts related to Potentials', 'tabular', 3, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (4, 2, 'Lead by Source', 'Lead by Source', 'summary', 4, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (5, 2, 'Lead Status Report', 'Lead Status Report', 'summary', 5, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (6, 3, 'Potential Pipeline', 'Potential Pipline', 'summary', 6, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (7, 3, 'Closed Potentials', 'Potential that have Won', 'tabular', 7, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (8, 4, 'Last Month Activities', 'Last Month Activites', 'tabular', 8, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (9, 4, 'This Month Activities', 'This Month Activites', 'tabular', 9, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (10, 5, 'Tickets by Products', 'Tickets related to Products', 'tabular', 10, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (11, 5, 'Tickets by Priority', 'Tickets by Priority', 'summary', 11, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (12, 5, 'Open Tickets', 'Tickets that are Open', 'tabular', 12, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (13, 6, 'Product Details', 'Product Detailed Report', 'tabular', 13, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (14, 6, 'Products by Contacts', 'Products related to Contacts', 'tabular', 14, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (15, 7, 'Open Quotes', 'Quotes that are Open', 'tabular', 15, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (16, 7, 'Quotes Detailed Report', 'Quotes detailed report', 'tabular', 16, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (17, 8, 'Orders by Contacts', 'Orders related to Contacts', 'tabular', 17, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (18, 8, 'Orders Detailed Report', 'Orders detailed report', 'tabular', 18, 'SAVED', 1, 1);
INSERT INTO `report` VALUES (19, 9, 'Invoice Detailed Report', 'Invoice detailed report', 'tabular', 19, 'SAVED', 1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `reportfolder`
-- 

CREATE TABLE `reportfolder` (
  `folderid` int(19) NOT NULL auto_increment,
  `foldername` varchar(100) NOT NULL default '',
  `description` varchar(250) default '',
  `state` varchar(50) default 'SAVED',
  PRIMARY KEY  (`folderid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Contenu de la table `reportfolder`
-- 

INSERT INTO `reportfolder` VALUES (1, 'Account and Contact Reports', 'Account and Contact Reports', 'SAVED');
INSERT INTO `reportfolder` VALUES (2, 'Lead Reports', 'Lead Reports', 'SAVED');
INSERT INTO `reportfolder` VALUES (3, 'Potential Reports', 'Potential Reports', 'SAVED');
INSERT INTO `reportfolder` VALUES (4, 'Activity Reports', 'Activity Reports', 'SAVED');
INSERT INTO `reportfolder` VALUES (5, 'HelpDesk Reports', 'HelpDesk Reports', 'SAVED');
INSERT INTO `reportfolder` VALUES (6, 'Product Reports', 'Product Reports', 'SAVED');
INSERT INTO `reportfolder` VALUES (7, 'Quote Reports', 'Quote Reports', 'SAVED');
INSERT INTO `reportfolder` VALUES (8, 'Order Reports', 'Order Reports', 'SAVED');
INSERT INTO `reportfolder` VALUES (9, 'Invoice Reports', 'Invoice Reports', 'SAVED');

-- --------------------------------------------------------

-- 
-- Structure de la table `reportmodules`
-- 

CREATE TABLE `reportmodules` (
  `reportmodulesid` int(19) NOT NULL,
  `primarymodule` varchar(50) NOT NULL default '',
  `secondarymodules` varchar(250) default '',
  PRIMARY KEY  (`reportmodulesid`),
  KEY `reportmodules_IDX0` (`reportmodulesid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `reportmodules`
-- 

INSERT INTO `reportmodules` VALUES (1, 'Contacts', 'Accounts');
INSERT INTO `reportmodules` VALUES (2, 'Contacts', 'Accounts');
INSERT INTO `reportmodules` VALUES (3, 'Contacts', 'Potentials');
INSERT INTO `reportmodules` VALUES (4, 'Leads', '');
INSERT INTO `reportmodules` VALUES (5, 'Leads', '');
INSERT INTO `reportmodules` VALUES (6, 'Potentials', '');
INSERT INTO `reportmodules` VALUES (7, 'Potentials', '');
INSERT INTO `reportmodules` VALUES (8, 'Activities', '');
INSERT INTO `reportmodules` VALUES (9, 'Activities', '');
INSERT INTO `reportmodules` VALUES (10, 'HelpDesk', 'Products');
INSERT INTO `reportmodules` VALUES (11, 'HelpDesk', '');
INSERT INTO `reportmodules` VALUES (12, 'HelpDesk', '');
INSERT INTO `reportmodules` VALUES (13, 'Products', '');
INSERT INTO `reportmodules` VALUES (14, 'Products', 'Contacts');
INSERT INTO `reportmodules` VALUES (15, 'Quotes', '');
INSERT INTO `reportmodules` VALUES (16, 'Quotes', '');
INSERT INTO `reportmodules` VALUES (17, 'Orders', 'Contacts');
INSERT INTO `reportmodules` VALUES (18, 'Orders', '');
INSERT INTO `reportmodules` VALUES (19, 'Invoice', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `reportsortcol`
-- 

CREATE TABLE `reportsortcol` (
  `sortcolid` int(19) NOT NULL default '0',
  `reportid` int(19) NOT NULL default '0',
  `columnname` varchar(250) default '',
  `sortorder` varchar(250) default 'Asc',
  KEY `reportsortcol_IDX0` (`reportid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `reportsortcol`
-- 

INSERT INTO `reportsortcol` VALUES (1, 4, 'leaddetails:leadsource:Leads_Lead_Source:leadsource:V', 'Ascending');
INSERT INTO `reportsortcol` VALUES (1, 5, 'leaddetails:leadstatus:Leads_Lead_Status:leadstatus:V', 'Ascending');
INSERT INTO `reportsortcol` VALUES (1, 6, 'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V', 'Ascending');
INSERT INTO `reportsortcol` VALUES (1, 11, 'troubletickets:priority:HelpDesk_Priority:ticketpriorities:V', 'Ascending');

-- --------------------------------------------------------

-- 
-- Structure de la table `reportsummary`
-- 

CREATE TABLE `reportsummary` (
  `reportsummaryid` int(19) NOT NULL default '0',
  `summarytype` int(19) NOT NULL default '0',
  `columnname` varchar(250) default '',
  KEY `reportsummary_IDX0` (`reportsummaryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `reportsummary`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `revenuetype`
-- 

CREATE TABLE `revenuetype` (
  `revenuetypeid` int(19) NOT NULL auto_increment,
  `revenuetype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`revenuetypeid`),
  UNIQUE KEY `RevenueType_UK0` (`revenuetype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `revenuetype`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `role`
-- 

CREATE TABLE `role` (
  `roleid` int(11) NOT NULL auto_increment,
  `name` varchar(60) default NULL,
  `description` varchar(100) default NULL,
  PRIMARY KEY  (`roleid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `role`
-- 

INSERT INTO `role` VALUES (1, 'administrator', '');
INSERT INTO `role` VALUES (2, 'standard_user', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `role2action`
-- 

CREATE TABLE `role2action` (
  `rolename` varchar(50) default NULL,
  `tabid` int(11) default '0',
  `actionname` varchar(100) default NULL,
  `action_permission` int(4) NOT NULL default '0',
  `description` varchar(100) default NULL,
  KEY `idx_role2action_name` (`rolename`,`tabid`,`actionname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `role2action`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `role2profile`
-- 

CREATE TABLE `role2profile` (
  `roleid` int(11) NOT NULL,
  `profileid` int(11) default NULL,
  PRIMARY KEY  (`roleid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `role2profile`
-- 

INSERT INTO `role2profile` VALUES (1, 1);
INSERT INTO `role2profile` VALUES (2, 2);

-- --------------------------------------------------------

-- 
-- Structure de la table `role2tab`
-- 

CREATE TABLE `role2tab` (
  `rolename` varchar(100) default NULL,
  `tabid` int(11) default '0',
  `module_permission` int(4) NOT NULL default '0',
  `description` varchar(100) default NULL,
  KEY `idx_role2tab_name` (`rolename`,`tabid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `role2tab`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `role_seq`
-- 

CREATE TABLE `role_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `role_seq`
-- 

INSERT INTO `role_seq` VALUES (2);

-- --------------------------------------------------------

-- 
-- Structure de la table `rss`
-- 

CREATE TABLE `rss` (
  `rssid` int(19) NOT NULL,
  `rssurl` varchar(200) NOT NULL default '',
  `rsstitle` varchar(200) default NULL,
  `rsstype` int(10) default '0',
  `starred` int(1) default '0',
  `rsscategory` varchar(100) default '',
  PRIMARY KEY  (`rssid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `rss`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rsscategory`
-- 

CREATE TABLE `rsscategory` (
  `rsscategoryid` int(19) NOT NULL auto_increment,
  `rsscategory` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`rsscategoryid`),
  UNIQUE KEY `RssCategory_UK0` (`rsscategory`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `rsscategory`
-- 

INSERT INTO `rsscategory` VALUES (1, 'vtiger Discussions', 0, 1);
INSERT INTO `rsscategory` VALUES (2, 'vtiger Wiki', 1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `sales_stage`
-- 

CREATE TABLE `sales_stage` (
  `sales_stage_id` int(19) NOT NULL auto_increment,
  `sales_stage` varchar(200) default NULL,
  `SORTORDERID` int(19) NOT NULL default '0',
  `PRESENCE` int(1) NOT NULL default '1',
  PRIMARY KEY  (`sales_stage_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- 
-- Contenu de la table `sales_stage`
-- 

INSERT INTO `sales_stage` VALUES (1, 'Prospecting', 0, 1);
INSERT INTO `sales_stage` VALUES (2, 'Qualification', 1, 1);
INSERT INTO `sales_stage` VALUES (3, 'Needs Analysis', 2, 1);
INSERT INTO `sales_stage` VALUES (4, 'Value Proposition', 3, 1);
INSERT INTO `sales_stage` VALUES (5, 'Id. Decision Makers', 4, 1);
INSERT INTO `sales_stage` VALUES (6, 'Perception Analysis', 5, 1);
INSERT INTO `sales_stage` VALUES (7, 'Proposal/Price Quote', 6, 1);
INSERT INTO `sales_stage` VALUES (8, 'Negotiation/Review', 7, 1);
INSERT INTO `sales_stage` VALUES (9, 'Closed Won', 8, 1);
INSERT INTO `sales_stage` VALUES (10, 'Closed Lost', 9, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `salesmanactivityrel`
-- 

CREATE TABLE `salesmanactivityrel` (
  `smid` int(19) NOT NULL default '0',
  `activityid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`smid`,`activityid`),
  KEY `SalesmanActivityRel_IDX0` (`activityid`),
  KEY `SalesmanActivityRel_IDX1` (`smid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `salesmanactivityrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `salesmanticketrel`
-- 

CREATE TABLE `salesmanticketrel` (
  `smid` int(19) NOT NULL default '0',
  `id` int(19) NOT NULL default '0',
  PRIMARY KEY  (`smid`,`id`),
  KEY `SalesmanTicketRel_IDX1` (`smid`),
  KEY `SalesmanTicketRel_IDX0` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `salesmanticketrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `salesorder`
-- 

CREATE TABLE `salesorder` (
  `salesorderid` int(19) NOT NULL default '0',
  `subject` varchar(100) default NULL,
  `potentialid` int(19) default NULL,
  `customerno` varchar(100) default NULL,
  `quoteid` int(19) default NULL,
  `vendorterms` varchar(100) default NULL,
  `contactid` int(19) default NULL,
  `vendorid` int(19) default NULL,
  `duedate` date default NULL,
  `carrier` varchar(100) default NULL,
  `pending` varchar(200) default NULL,
  `type` varchar(100) default NULL,
  `salestax` decimal(11,3) default NULL,
  `adjustment` decimal(11,3) default NULL,
  `salescommission` decimal(11,3) default NULL,
  `exciseduty` decimal(11,3) default NULL,
  `total` decimal(11,3) default NULL,
  `subtotal` decimal(11,3) default NULL,
  `accountid` int(19) default NULL,
  `terms_conditions` text,
  `purchaseorder` varchar(200) default NULL,
  `sostatus` varchar(200) default NULL,
  PRIMARY KEY  (`salesorderid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `salesorder`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `salesordercf`
-- 

CREATE TABLE `salesordercf` (
  `salesorderid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`salesorderid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `salesordercf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `salutationtype`
-- 

CREATE TABLE `salutationtype` (
  `salutationid` int(19) NOT NULL auto_increment,
  `salutationtype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`salutationid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Contenu de la table `salutationtype`
-- 

INSERT INTO `salutationtype` VALUES (1, '--None--', 0, 1);
INSERT INTO `salutationtype` VALUES (2, 'Mr.', 1, 1);
INSERT INTO `salutationtype` VALUES (3, 'Ms.', 2, 1);
INSERT INTO `salutationtype` VALUES (4, 'Mrs.', 3, 1);
INSERT INTO `salutationtype` VALUES (5, 'Dr.', 4, 1);
INSERT INTO `salutationtype` VALUES (6, 'Prof.', 5, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `seactivityrel`
-- 

CREATE TABLE `seactivityrel` (
  `crmid` int(19) NOT NULL,
  `activityid` int(19) NOT NULL,
  PRIMARY KEY  (`crmid`,`activityid`),
  KEY `SeActivityRel_IDX0` (`activityid`),
  KEY `SeActivityRel_IDX1` (`crmid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `seactivityrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `seattachmentsrel`
-- 

CREATE TABLE `seattachmentsrel` (
  `crmid` int(19) NOT NULL default '0',
  `attachmentsid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`crmid`,`attachmentsid`),
  KEY `SeAttachmentsRel_IDX0` (`attachmentsid`),
  KEY `SeAttachmentsRel_IDX1` (`crmid`),
  KEY `attachmentsid` (`attachmentsid`,`crmid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `seattachmentsrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `selectcolumn`
-- 

CREATE TABLE `selectcolumn` (
  `queryid` int(19) default NULL,
  `columnindex` int(11) NOT NULL default '0',
  `columnname` varchar(250) default '',
  KEY `selectcolumn_IDX0` (`queryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `selectcolumn`
-- 

INSERT INTO `selectcolumn` VALUES (1, 0, 'contactdetails:firstname:Contacts_First_Name:firstname:V');
INSERT INTO `selectcolumn` VALUES (1, 1, 'contactdetails:lastname:Contacts_Last_Name:lastname:V');
INSERT INTO `selectcolumn` VALUES (1, 2, 'contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V');
INSERT INTO `selectcolumn` VALUES (1, 3, 'accountContacts:accountname:Contacts_Account_Name:account_id:I');
INSERT INTO `selectcolumn` VALUES (1, 4, 'account:industry:Accounts_industry:industry:V');
INSERT INTO `selectcolumn` VALUES (1, 5, 'contactdetails:email:Contacts_Email:email:V');
INSERT INTO `selectcolumn` VALUES (2, 0, 'contactdetails:firstname:Contacts_First_Name:firstname:V');
INSERT INTO `selectcolumn` VALUES (2, 1, 'contactdetails:lastname:Contacts_Last_Name:lastname:V');
INSERT INTO `selectcolumn` VALUES (2, 2, 'contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V');
INSERT INTO `selectcolumn` VALUES (2, 3, 'accountContacts:accountname:Contacts_Account_Name:account_id:I');
INSERT INTO `selectcolumn` VALUES (2, 4, 'account:industry:Accounts_industry:industry:V');
INSERT INTO `selectcolumn` VALUES (2, 5, 'contactdetails:email:Contacts_Email:email:V');
INSERT INTO `selectcolumn` VALUES (3, 0, 'contactdetails:firstname:Contacts_First_Name:firstname:V');
INSERT INTO `selectcolumn` VALUES (3, 1, 'contactdetails:lastname:Contacts_Last_Name:lastname:V');
INSERT INTO `selectcolumn` VALUES (3, 2, 'accountContacts:accountname:Contacts_Account_Name:account_id:I');
INSERT INTO `selectcolumn` VALUES (3, 3, 'contactdetails:email:Contacts_Email:email:V');
INSERT INTO `selectcolumn` VALUES (3, 4, 'potential:potentialname:Potentials_Potential_Name:potentialname:V');
INSERT INTO `selectcolumn` VALUES (3, 5, 'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V');
INSERT INTO `selectcolumn` VALUES (4, 0, 'leaddetails:firstname:Leads_First_Name:firstname:V');
INSERT INTO `selectcolumn` VALUES (4, 1, 'leaddetails:lastname:Leads_Last_Name:lastname:V');
INSERT INTO `selectcolumn` VALUES (4, 2, 'leaddetails:company:Leads_Company:company:V');
INSERT INTO `selectcolumn` VALUES (4, 3, 'leaddetails:email:Leads_Email:email:V');
INSERT INTO `selectcolumn` VALUES (5, 0, 'leaddetails:firstname:Leads_First_Name:firstname:V');
INSERT INTO `selectcolumn` VALUES (5, 1, 'leaddetails:lastname:Leads_Last_Name:lastname:V');
INSERT INTO `selectcolumn` VALUES (5, 2, 'leaddetails:company:Leads_Company:company:V');
INSERT INTO `selectcolumn` VALUES (5, 3, 'leaddetails:email:Leads_Email:email:V');
INSERT INTO `selectcolumn` VALUES (5, 4, 'leaddetails:leadsource:Leads_Lead_Source:leadsource:V');
INSERT INTO `selectcolumn` VALUES (6, 0, 'potential:potentialname:Potentials_Potential_Name:potentialname:V');
INSERT INTO `selectcolumn` VALUES (6, 1, 'potential:amount:Potentials_Amount:amount:N');
INSERT INTO `selectcolumn` VALUES (6, 2, 'potential:potentialtype:Potentials_Type:opportunity_type:V');
INSERT INTO `selectcolumn` VALUES (6, 3, 'potential:leadsource:Potentials_Lead_Source:leadsource:V');
INSERT INTO `selectcolumn` VALUES (6, 4, 'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V');
INSERT INTO `selectcolumn` VALUES (7, 0, 'potential:potentialname:Potentials_Potential_Name:potentialname:V');
INSERT INTO `selectcolumn` VALUES (7, 1, 'potential:amount:Potentials_Amount:amount:N');
INSERT INTO `selectcolumn` VALUES (7, 2, 'potential:potentialtype:Potentials_Type:opportunity_type:V');
INSERT INTO `selectcolumn` VALUES (7, 3, 'potential:leadsource:Potentials_Lead_Source:leadsource:V');
INSERT INTO `selectcolumn` VALUES (7, 4, 'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V');
INSERT INTO `selectcolumn` VALUES (8, 0, 'activity:subject:Activities_Subject:subject:V');
INSERT INTO `selectcolumn` VALUES (8, 1, 'contactdetailsActivities:lastname:Activities_Contact_Name:contact_id:I');
INSERT INTO `selectcolumn` VALUES (8, 2, 'activity:status:Activities_Status:taskstatus:V');
INSERT INTO `selectcolumn` VALUES (8, 3, 'activity:priority:Activities_Priority:taskpriority:V');
INSERT INTO `selectcolumn` VALUES (8, 4, 'usersActivities:user_name:Activities_Assigned_To:assigned_user_id:V');
INSERT INTO `selectcolumn` VALUES (9, 0, 'activity:subject:Activities_Subject:subject:V');
INSERT INTO `selectcolumn` VALUES (9, 1, 'contactdetailsActivities:lastname:Activities_Contact_Name:contact_id:I');
INSERT INTO `selectcolumn` VALUES (9, 2, 'activity:status:Activities_Status:taskstatus:V');
INSERT INTO `selectcolumn` VALUES (9, 3, 'activity:priority:Activities_Priority:taskpriority:V');
INSERT INTO `selectcolumn` VALUES (9, 4, 'usersActivities:user_name:Activities_Assigned_To:assigned_user_id:V');
INSERT INTO `selectcolumn` VALUES (10, 0, 'troubletickets:title:HelpDesk_Title:ticket_title:V');
INSERT INTO `selectcolumn` VALUES (10, 1, 'troubletickets:status:HelpDesk_Status:ticketstatus:V');
INSERT INTO `selectcolumn` VALUES (10, 2, 'products:productname:Products_Product_Name:productname:V');
INSERT INTO `selectcolumn` VALUES (10, 3, 'products:discontinued:Products_Product_Active:discontinued:V');
INSERT INTO `selectcolumn` VALUES (10, 4, 'products:productcategory:Products_Product_Category:productcategory:V');
INSERT INTO `selectcolumn` VALUES (10, 5, 'products:manufacturer:Products_Manufacturer:manufacturer:V');
INSERT INTO `selectcolumn` VALUES (10, 6, 'contactdetailsProducts:lastname:Products_Contact_Name:contact_id:I');
INSERT INTO `selectcolumn` VALUES (11, 0, 'troubletickets:title:HelpDesk_Title:ticket_title:V');
INSERT INTO `selectcolumn` VALUES (11, 1, 'troubletickets:priority:HelpDesk_Priority:ticketpriorities:V');
INSERT INTO `selectcolumn` VALUES (11, 2, 'troubletickets:severity:HelpDesk_Severity:ticketseverities:V');
INSERT INTO `selectcolumn` VALUES (11, 3, 'troubletickets:status:HelpDesk_Status:ticketstatus:V');
INSERT INTO `selectcolumn` VALUES (11, 4, 'troubletickets:category:HelpDesk_Category:ticketcategories:V');
INSERT INTO `selectcolumn` VALUES (11, 5, 'usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V');
INSERT INTO `selectcolumn` VALUES (12, 0, 'troubletickets:title:HelpDesk_Title:ticket_title:V');
INSERT INTO `selectcolumn` VALUES (12, 1, 'troubletickets:priority:HelpDesk_Priority:ticketpriorities:V');
INSERT INTO `selectcolumn` VALUES (12, 2, 'troubletickets:severity:HelpDesk_Severity:ticketseverities:V');
INSERT INTO `selectcolumn` VALUES (12, 3, 'troubletickets:status:HelpDesk_Status:ticketstatus:V');
INSERT INTO `selectcolumn` VALUES (12, 4, 'troubletickets:category:HelpDesk_Category:ticketcategories:V');
INSERT INTO `selectcolumn` VALUES (12, 5, 'usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V');
INSERT INTO `selectcolumn` VALUES (13, 0, 'products:productname:Products_Product_Name:productname:V');
INSERT INTO `selectcolumn` VALUES (13, 1, 'products:productcode:Products_Product_Code:productcode:V');
INSERT INTO `selectcolumn` VALUES (13, 2, 'products:discontinued:Products_Product_Active:discontinued:V');
INSERT INTO `selectcolumn` VALUES (13, 3, 'products:productcategory:Products_Product_Category:productcategory:V');
INSERT INTO `selectcolumn` VALUES (13, 4, 'contactdetailsProducts:lastname:Products_Contact_Name:contact_id:I');
INSERT INTO `selectcolumn` VALUES (13, 5, 'products:website:Products_Website:website:V');
INSERT INTO `selectcolumn` VALUES (13, 6, 'vendorRel:vendorname:Products_Vendor_Name:vendor_id:I');
INSERT INTO `selectcolumn` VALUES (13, 7, 'products:mfr_part_no:Products_Mfr_PartNo:mfr_part_no:V');
INSERT INTO `selectcolumn` VALUES (14, 0, 'products:productname:Products_Product_Name:productname:V');
INSERT INTO `selectcolumn` VALUES (14, 1, 'products:manufacturer:Products_Manufacturer:manufacturer:V');
INSERT INTO `selectcolumn` VALUES (14, 2, 'products:productcategory:Products_Product_Category:productcategory:V');
INSERT INTO `selectcolumn` VALUES (14, 3, 'contactdetails:firstname:Contacts_First_Name:firstname:V');
INSERT INTO `selectcolumn` VALUES (14, 4, 'contactdetails:lastname:Contacts_Last_Name:lastname:V');
INSERT INTO `selectcolumn` VALUES (14, 5, 'contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V');
INSERT INTO `selectcolumn` VALUES (15, 0, 'quotes:subject:Quotes_Subject:subject:V');
INSERT INTO `selectcolumn` VALUES (15, 1, 'potentialRel:potentialname:Quotes_Potential_Name:potential_id:I');
INSERT INTO `selectcolumn` VALUES (15, 2, 'quotes:quotestage:Quotes_Quote_Stage:quotestage:V');
INSERT INTO `selectcolumn` VALUES (15, 3, 'contactdetailsQuotes:lastname:Quotes_Contact_Name:contact_id:V');
INSERT INTO `selectcolumn` VALUES (15, 4, 'usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I');
INSERT INTO `selectcolumn` VALUES (15, 5, 'accountQuotes:accountname:Quotes_Account_Name:account_id:I');
INSERT INTO `selectcolumn` VALUES (16, 0, 'quotes:subject:Quotes_Subject:subject:V');
INSERT INTO `selectcolumn` VALUES (16, 1, 'potentialRel:potentialname:Quotes_Potential_Name:potential_id:I');
INSERT INTO `selectcolumn` VALUES (16, 2, 'quotes:quotestage:Quotes_Quote_Stage:quotestage:V');
INSERT INTO `selectcolumn` VALUES (16, 3, 'contactdetailsQuotes:lastname:Quotes_Contact_Name:contact_id:V');
INSERT INTO `selectcolumn` VALUES (16, 4, 'usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I');
INSERT INTO `selectcolumn` VALUES (16, 5, 'accountQuotes:accountname:Quotes_Account_Name:account_id:I');
INSERT INTO `selectcolumn` VALUES (16, 6, 'quotes:carrier:Quotes_Carrier:carrier:V');
INSERT INTO `selectcolumn` VALUES (16, 7, 'quotes:shipping:Quotes_Shipping:shipping:V');
INSERT INTO `selectcolumn` VALUES (17, 0, 'purchaseorder:subject:Orders_Subject:subject:V');
INSERT INTO `selectcolumn` VALUES (17, 1, 'vendorRel:vendorname:Orders_Vendor_Name:vendor_id:I');
INSERT INTO `selectcolumn` VALUES (17, 2, 'purchaseorder:tracking_no:Orders_Tracking_Number:tracking_no:V');
INSERT INTO `selectcolumn` VALUES (17, 3, 'contactdetails:firstname:Contacts_First_Name:firstname:V');
INSERT INTO `selectcolumn` VALUES (17, 4, 'contactdetails:lastname:Contacts_Last_Name:lastname:V');
INSERT INTO `selectcolumn` VALUES (17, 5, 'contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V');
INSERT INTO `selectcolumn` VALUES (17, 6, 'contactdetails:email:Contacts_Email:email:V');
INSERT INTO `selectcolumn` VALUES (18, 0, 'purchaseorder:subject:Orders_Subject:subject:V');
INSERT INTO `selectcolumn` VALUES (18, 1, 'vendorRel:vendorname:Orders_Vendor_Name:vendor_id:I');
INSERT INTO `selectcolumn` VALUES (18, 2, 'purchaseorder:requisition_no:Orders_Requisition_No:requisition_no:V');
INSERT INTO `selectcolumn` VALUES (18, 3, 'purchaseorder:tracking_no:Orders_Tracking_Number:tracking_no:V');
INSERT INTO `selectcolumn` VALUES (18, 4, 'contactdetailsOrders:lastname:Orders_Contact_Name:contact_id:I');
INSERT INTO `selectcolumn` VALUES (18, 5, 'purchaseorder:carrier:Orders_Carrier:carrier:V');
INSERT INTO `selectcolumn` VALUES (18, 6, 'purchaseorder:salescommission:Orders_Sales_Commission:salescommission:N');
INSERT INTO `selectcolumn` VALUES (18, 7, 'purchaseorder:exciseduty:Orders_Excise_Duty:exciseduty:N');
INSERT INTO `selectcolumn` VALUES (18, 8, 'usersOrders:user_name:Orders_Assigned_To:assigned_user_id:V');
INSERT INTO `selectcolumn` VALUES (19, 0, 'invoice:subject:Invoice_Subject:subject:V');
INSERT INTO `selectcolumn` VALUES (19, 1, 'invoice:salesorderid:Invoice_Sales_Order:salesorder_id:I');
INSERT INTO `selectcolumn` VALUES (19, 2, 'invoice:customerno:Invoice_Customer_No:customerno:V');
INSERT INTO `selectcolumn` VALUES (19, 3, 'invoice:notes:Invoice_Notes:notes:V');
INSERT INTO `selectcolumn` VALUES (19, 4, 'invoice:invoiceterms:Invoice_Invoice_Terms:invoiceterms:V');
INSERT INTO `selectcolumn` VALUES (19, 5, 'invoice:exciseduty:Invoice_Excise_Duty:exciseduty:N');
INSERT INTO `selectcolumn` VALUES (19, 6, 'invoice:salescommission:Invoice_Sales_Commission:salescommission:N');
INSERT INTO `selectcolumn` VALUES (19, 7, 'accountInvoice:accountname:Invoice_Account_Name:account_id:I');

-- --------------------------------------------------------

-- 
-- Structure de la table `selectquery`
-- 

CREATE TABLE `selectquery` (
  `queryid` int(19) NOT NULL,
  `startindex` int(19) default '0',
  `numofobjects` int(19) default '0',
  PRIMARY KEY  (`queryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `selectquery`
-- 

INSERT INTO `selectquery` VALUES (1, 0, 0);
INSERT INTO `selectquery` VALUES (2, 0, 0);
INSERT INTO `selectquery` VALUES (3, 0, 0);
INSERT INTO `selectquery` VALUES (4, 0, 0);
INSERT INTO `selectquery` VALUES (5, 0, 0);
INSERT INTO `selectquery` VALUES (6, 0, 0);
INSERT INTO `selectquery` VALUES (7, 0, 0);
INSERT INTO `selectquery` VALUES (8, 0, 0);
INSERT INTO `selectquery` VALUES (9, 0, 0);
INSERT INTO `selectquery` VALUES (10, 0, 0);
INSERT INTO `selectquery` VALUES (11, 0, 0);
INSERT INTO `selectquery` VALUES (12, 0, 0);
INSERT INTO `selectquery` VALUES (13, 0, 0);
INSERT INTO `selectquery` VALUES (14, 0, 0);
INSERT INTO `selectquery` VALUES (15, 0, 0);
INSERT INTO `selectquery` VALUES (16, 0, 0);
INSERT INTO `selectquery` VALUES (17, 0, 0);
INSERT INTO `selectquery` VALUES (18, 0, 0);
INSERT INTO `selectquery` VALUES (19, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `selectquery_seq`
-- 

CREATE TABLE `selectquery_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `selectquery_seq`
-- 

INSERT INTO `selectquery_seq` VALUES (19);

-- --------------------------------------------------------

-- 
-- Structure de la table `senotesrel`
-- 

CREATE TABLE `senotesrel` (
  `crmid` int(19) NOT NULL default '0',
  `notesid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`crmid`,`notesid`),
  KEY `SeNotesRel_IDX0` (`notesid`),
  KEY `SeNotesRel_IDX1` (`crmid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `senotesrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `seproductsrel`
-- 

CREATE TABLE `seproductsrel` (
  `crmid` int(19) NOT NULL default '0',
  `productid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`crmid`,`productid`),
  KEY `SeProductsRel_IDX0` (`productid`),
  KEY `SeProductRel_IDX1` (`crmid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `seproductsrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `seticketsrel`
-- 

CREATE TABLE `seticketsrel` (
  `crmid` int(19) NOT NULL default '0',
  `ticketid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`crmid`,`ticketid`),
  KEY `SeTicketsRel_IDX1` (`crmid`),
  KEY `SeTicketsRel_IDX0` (`ticketid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `seticketsrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `sobillads`
-- 

CREATE TABLE `sobillads` (
  `sobilladdressid` int(19) NOT NULL default '0',
  `bill_city` varchar(30) default NULL,
  `bill_code` varchar(30) default NULL,
  `bill_country` varchar(30) default NULL,
  `bill_state` varchar(30) default NULL,
  `bill_street` varchar(250) default NULL,
  PRIMARY KEY  (`sobilladdressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `sobillads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `soproductrel`
-- 

CREATE TABLE `soproductrel` (
  `salesorderid` int(19) NOT NULL,
  `productid` int(19) NOT NULL,
  `quantity` int(19) default NULL,
  `listprice` decimal(11,3) default NULL,
  PRIMARY KEY  (`salesorderid`,`productid`),
  KEY `SoProductRel_IDX0` (`salesorderid`),
  KEY `SoProductRel_IDX1` (`productid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `soproductrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `soshipads`
-- 

CREATE TABLE `soshipads` (
  `soshipaddressid` int(19) NOT NULL default '0',
  `ship_city` varchar(30) default NULL,
  `ship_code` varchar(30) default NULL,
  `ship_country` varchar(30) default NULL,
  `ship_state` varchar(30) default NULL,
  `ship_street` varchar(250) default NULL,
  PRIMARY KEY  (`soshipaddressid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `soshipads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `sostatus`
-- 

CREATE TABLE `sostatus` (
  `sostatusid` int(19) NOT NULL auto_increment,
  `sostatus` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`sostatusid`),
  UNIQUE KEY `sostatus_UK0` (`sostatus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `sostatus`
-- 

INSERT INTO `sostatus` VALUES (1, 'Created', 0, 1);
INSERT INTO `sostatus` VALUES (2, 'Approved', 1, 1);
INSERT INTO `sostatus` VALUES (3, 'Delivered', 2, 1);
INSERT INTO `sostatus` VALUES (4, 'Canceled', 3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `systems`
-- 

CREATE TABLE `systems` (
  `id` int(19) NOT NULL,
  `server` varchar(30) default NULL,
  `server_username` varchar(30) default NULL,
  `server_password` varchar(30) default NULL,
  `server_type` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `systems`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `tab`
-- 

CREATE TABLE `tab` (
  `tabid` int(19) NOT NULL default '0',
  `name` varchar(25) NOT NULL,
  `presence` int(19) NOT NULL default '1',
  `tabsequence` int(10) default NULL,
  `tablabel` varchar(25) NOT NULL,
  `modifiedby` int(19) default NULL,
  `modifiedtime` int(19) default NULL,
  `customized` int(1) default NULL,
  PRIMARY KEY  (`tabid`),
  UNIQUE KEY `Tab_UK0` (`name`),
  KEY `Tab_IDX0` (`modifiedby`),
  KEY `tabid` (`tabid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `tab`
-- 

INSERT INTO `tab` VALUES (3, 'Home', 0, 1, 'Home', 0, 0, 1);
INSERT INTO `tab` VALUES (7, 'Leads', 0, 4, 'Leads', 0, 0, 1);
INSERT INTO `tab` VALUES (6, 'Accounts', 0, 5, 'Accounts', 0, 0, 1);
INSERT INTO `tab` VALUES (4, 'Contacts', 0, 6, 'Contacts', 0, 0, 1);
INSERT INTO `tab` VALUES (2, 'Potentials', 0, 7, 'Potentials', 0, 0, 1);
INSERT INTO `tab` VALUES (8, 'Notes', 0, 9, 'Notes', 0, 0, 1);
INSERT INTO `tab` VALUES (9, 'Activities', 0, 3, 'Activities', 0, 0, 1);
INSERT INTO `tab` VALUES (10, 'Emails', 0, 10, 'Emails', 0, 0, 1);
INSERT INTO `tab` VALUES (13, 'HelpDesk', 0, 11, 'HelpDesk', 0, 0, 1);
INSERT INTO `tab` VALUES (14, 'Products', 0, 8, 'Products', 0, 0, 1);
INSERT INTO `tab` VALUES (1, 'Dashboard', 0, 12, 'Dashboards', 0, 0, 1);
INSERT INTO `tab` VALUES (15, 'Faq', 2, 14, 'Faq', 0, 0, 1);
INSERT INTO `tab` VALUES (16, 'Events', 2, 13, 'Events', 0, 0, 1);
INSERT INTO `tab` VALUES (17, 'Calendar', 0, 2, 'Calendar', 0, 0, 1);
INSERT INTO `tab` VALUES (18, 'Vendor', 2, 15, 'Vendor', 0, 0, 1);
INSERT INTO `tab` VALUES (19, 'PriceBook', 2, 16, 'PriceBook', 0, 0, 1);
INSERT INTO `tab` VALUES (20, 'Quotes', 0, 17, 'Quotes', 0, 0, 1);
INSERT INTO `tab` VALUES (21, 'Orders', 0, 18, 'Orders', 0, 0, 1);
INSERT INTO `tab` VALUES (22, 'SalesOrder', 2, 19, 'SalesOrder', 0, 0, 1);
INSERT INTO `tab` VALUES (23, 'Invoice', 0, 20, 'Invoice', 0, 0, 1);
INSERT INTO `tab` VALUES (24, 'Rss', 0, 21, 'Rss', 0, 0, 1);
INSERT INTO `tab` VALUES (25, 'Reports', 0, 22, 'Reports', 0, 0, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `taskpriority`
-- 

CREATE TABLE `taskpriority` (
  `taskpriorityid` int(19) NOT NULL auto_increment,
  `taskpriority` varchar(100) default NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`taskpriorityid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `taskpriority`
-- 

INSERT INTO `taskpriority` VALUES (1, 'High', 0, 1);
INSERT INTO `taskpriority` VALUES (2, 'Medium', 1, 1);
INSERT INTO `taskpriority` VALUES (3, 'Low', 2, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `taskstatus`
-- 

CREATE TABLE `taskstatus` (
  `taskstatusid` int(19) NOT NULL auto_increment,
  `taskstatus` varchar(100) default NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`taskstatusid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Contenu de la table `taskstatus`
-- 

INSERT INTO `taskstatus` VALUES (1, 'Not Started', 0, 1);
INSERT INTO `taskstatus` VALUES (2, 'In Progress', 1, 1);
INSERT INTO `taskstatus` VALUES (3, 'Completed', 2, 1);
INSERT INTO `taskstatus` VALUES (4, 'Pending Input', 3, 1);
INSERT INTO `taskstatus` VALUES (5, 'Deferred', 4, 1);
INSERT INTO `taskstatus` VALUES (6, 'Planned', 5, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `taxclass`
-- 

CREATE TABLE `taxclass` (
  `taxclassid` int(19) NOT NULL auto_increment,
  `taxclass` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`taxclassid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `taxclass`
-- 

INSERT INTO `taxclass` VALUES (1, 'SalesTax', 0, 1);
INSERT INTO `taxclass` VALUES (2, 'Vat', 1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `ticketcategories`
-- 

CREATE TABLE `ticketcategories` (
  `ticketcategories_id` int(19) NOT NULL auto_increment,
  `ticketcategories` varchar(100) default NULL,
  `SORTORDERID` int(19) NOT NULL default '0',
  `PRESENCE` int(1) NOT NULL default '0',
  PRIMARY KEY  (`ticketcategories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `ticketcategories`
-- 

INSERT INTO `ticketcategories` VALUES (1, 'Big Problem', 0, 1);
INSERT INTO `ticketcategories` VALUES (2, 'Small Problem', 1, 1);
INSERT INTO `ticketcategories` VALUES (3, 'Other Problem', 2, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `ticketcf`
-- 

CREATE TABLE `ticketcf` (
  `ticketid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`ticketid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `ticketcf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `ticketcomments`
-- 

CREATE TABLE `ticketcomments` (
  `commentid` int(19) NOT NULL auto_increment,
  `ticketid` int(19) default NULL,
  `comments` text,
  `ownerid` int(19) NOT NULL default '0',
  `ownertype` varchar(10) default NULL,
  `createdtime` datetime NOT NULL,
  PRIMARY KEY  (`commentid`),
  KEY `ticketcomments_IDX0` (`ticketid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `ticketcomments`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `ticketgrouprelation`
-- 

CREATE TABLE `ticketgrouprelation` (
  `ticketid` int(19) default NULL,
  `groupname` varchar(100) default NULL,
  KEY `ticketgrouprelation_IDX0` (`ticketid`),
  KEY `ticketgrouprelation_IDX1` (`groupname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `ticketgrouprelation`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `ticketpriorities`
-- 

CREATE TABLE `ticketpriorities` (
  `ticketpriorities_id` int(19) NOT NULL auto_increment,
  `ticketpriorities` varchar(100) default NULL,
  `SORTORDERID` int(19) NOT NULL default '0',
  `PRESENCE` int(1) NOT NULL default '0',
  PRIMARY KEY  (`ticketpriorities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `ticketpriorities`
-- 

INSERT INTO `ticketpriorities` VALUES (1, 'Low', 0, 1);
INSERT INTO `ticketpriorities` VALUES (2, 'Normal', 1, 1);
INSERT INTO `ticketpriorities` VALUES (3, 'High', 2, 1);
INSERT INTO `ticketpriorities` VALUES (4, 'Urgent', 3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `ticketseverities`
-- 

CREATE TABLE `ticketseverities` (
  `ticketseverities_id` int(19) NOT NULL auto_increment,
  `ticketseverities` varchar(100) default NULL,
  `SORTORDERID` int(19) NOT NULL default '0',
  `PRESENCE` int(1) NOT NULL default '0',
  PRIMARY KEY  (`ticketseverities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `ticketseverities`
-- 

INSERT INTO `ticketseverities` VALUES (1, 'Minor', 0, 1);
INSERT INTO `ticketseverities` VALUES (2, 'Major', 1, 1);
INSERT INTO `ticketseverities` VALUES (3, 'Feature', 2, 1);
INSERT INTO `ticketseverities` VALUES (4, 'Critical', 3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `ticketstatus`
-- 

CREATE TABLE `ticketstatus` (
  `ticketstatus_id` int(19) NOT NULL auto_increment,
  `ticketstatus` varchar(60) default NULL,
  `SORTORDERID` int(19) NOT NULL default '0',
  `PRESENCE` int(1) NOT NULL default '0',
  PRIMARY KEY  (`ticketstatus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `ticketstatus`
-- 

INSERT INTO `ticketstatus` VALUES (1, 'Open', 0, 1);
INSERT INTO `ticketstatus` VALUES (2, 'In Progress', 1, 1);
INSERT INTO `ticketstatus` VALUES (3, 'Wait For Response', 2, 1);
INSERT INTO `ticketstatus` VALUES (4, 'Closed', 3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `ticketstracktime`
-- 

CREATE TABLE `ticketstracktime` (
  `ticket_id` int(11) NOT NULL default '0',
  `supporter_id` int(11) NOT NULL default '0',
  `minutes` int(11) default '0',
  `date_logged` int(11) NOT NULL default '0',
  KEY `idx_ticketstracktime` (`ticket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `ticketstracktime`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `tracker`
-- 

CREATE TABLE `tracker` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` varchar(36) default NULL,
  `module_name` varchar(25) default NULL,
  `item_id` varchar(36) default NULL,
  `item_summary` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `tracker`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `troubletickets`
-- 

CREATE TABLE `troubletickets` (
  `ticketid` int(19) NOT NULL,
  `groupname` varchar(100) default NULL,
  `parent_id` varchar(100) default NULL,
  `product_id` varchar(100) default NULL,
  `priority` varchar(150) default NULL,
  `severity` varchar(150) default NULL,
  `status` varchar(150) default NULL,
  `category` varchar(150) default NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `solution` text,
  `update_log` text,
  `version_id` int(11) default NULL,
  PRIMARY KEY  (`ticketid`),
  KEY `troubletickets_IDX0` (`ticketid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `troubletickets`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `usageunit`
-- 

CREATE TABLE `usageunit` (
  `usageunitid` int(19) NOT NULL auto_increment,
  `usageunit` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`usageunitid`),
  UNIQUE KEY `UsageUnit_UK0` (`usageunit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- 
-- Contenu de la table `usageunit`
-- 

INSERT INTO `usageunit` VALUES (1, 'Box', 0, 1);
INSERT INTO `usageunit` VALUES (2, 'Carton', 1, 1);
INSERT INTO `usageunit` VALUES (3, 'Caton', 2, 1);
INSERT INTO `usageunit` VALUES (4, 'Dozen', 3, 1);
INSERT INTO `usageunit` VALUES (5, 'Each', 4, 1);
INSERT INTO `usageunit` VALUES (6, 'Hours', 5, 1);
INSERT INTO `usageunit` VALUES (7, 'Impressions', 6, 1);
INSERT INTO `usageunit` VALUES (8, 'Lb', 7, 1);
INSERT INTO `usageunit` VALUES (9, 'M', 8, 1);
INSERT INTO `usageunit` VALUES (10, 'Pack', 9, 1);
INSERT INTO `usageunit` VALUES (11, 'Pages', 10, 1);
INSERT INTO `usageunit` VALUES (12, 'Pieces', 11, 1);
INSERT INTO `usageunit` VALUES (13, 'Quantity', 12, 1);
INSERT INTO `usageunit` VALUES (14, 'Reams', 13, 1);
INSERT INTO `usageunit` VALUES (15, 'Sheet', 14, 1);
INSERT INTO `usageunit` VALUES (16, 'Spiral Binder', 15, 1);
INSERT INTO `usageunit` VALUES (17, 'Sq Ft', 16, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `user2role`
-- 

CREATE TABLE `user2role` (
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`),
  KEY `user2role_IDX1` (`roleid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `user2role`
-- 

INSERT INTO `user2role` VALUES (1, 1);
INSERT INTO `user2role` VALUES (2, 2);

-- --------------------------------------------------------

-- 
-- Structure de la table `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `user_name` varchar(20) default NULL,
  `user_password` varchar(30) default NULL,
  `user_hash` varchar(32) default NULL,
  `first_name` varchar(30) default NULL,
  `last_name` varchar(30) default NULL,
  `reports_to_id` varchar(36) default NULL,
  `is_admin` varchar(3) default '0',
  `description` text,
  `date_entered` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified_user_id` varchar(36) default NULL,
  `title` varchar(50) default NULL,
  `department` varchar(50) default NULL,
  `phone_home` varchar(50) default NULL,
  `phone_mobile` varchar(50) default NULL,
  `phone_work` varchar(50) default NULL,
  `phone_other` varchar(50) default NULL,
  `phone_fax` varchar(50) default NULL,
  `email1` varchar(100) default NULL,
  `email2` varchar(100) default NULL,
  `yahoo_id` varchar(100) default NULL,
  `status` varchar(25) default NULL,
  `signature` varchar(250) default NULL,
  `address_street` varchar(150) default NULL,
  `address_city` varchar(100) default NULL,
  `address_state` varchar(100) default NULL,
  `address_country` varchar(25) default NULL,
  `address_postalcode` varchar(9) default NULL,
  `user_preferences` text,
  `tz` varchar(30) default NULL,
  `holidays` varchar(60) default NULL,
  `namedays` varchar(60) default NULL,
  `workdays` varchar(30) default NULL,
  `weekstart` int(11) default NULL,
  `date_format` varchar(30) default NULL,
  `deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_user_name` (`user_name`),
  KEY `user_password` (`user_password`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `users`
-- 

INSERT INTO `users` VALUES (1, 'admin', 'adpexzg3FUZAk', '21232f297a57a5a743894a0e4a801fc3', NULL, 'Administrator', NULL, 'on', NULL, '2006-02-20 19:07:58', '2006-02-20 19:06:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Europe/Berlin', 'de,en_uk,fr,it,us,', '', '0,1,2,3,4,5,6,', 1, 'yyyy-mm-dd', 0);
INSERT INTO `users` VALUES (2, 'standarduser', 'stX/AHHNK/Gkw', NULL, NULL, 'standarduser', NULL, '0', NULL, '2006-02-20 19:06:54', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'standarduser@standard.user.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'yyyy-mm-dd', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `users2group`
-- 

CREATE TABLE `users2group` (
  `groupname` varchar(100) default NULL,
  `userid` varchar(50) default NULL,
  KEY `idx_users2group` (`groupname`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `users2group`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `users_last_import`
-- 

CREATE TABLE `users_last_import` (
  `id` int(36) NOT NULL auto_increment,
  `assigned_user_id` varchar(36) default NULL,
  `bean_type` varchar(36) default NULL,
  `bean_id` varchar(36) default NULL,
  `deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_user_id` (`assigned_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `users_last_import`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `users_seq`
-- 

CREATE TABLE `users_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `users_seq`
-- 

INSERT INTO `users_seq` VALUES (2);

-- --------------------------------------------------------

-- 
-- Structure de la table `usertype`
-- 

CREATE TABLE `usertype` (
  `usertypeid` int(19) NOT NULL auto_increment,
  `usertype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`usertypeid`),
  UNIQUE KEY `UserType_UK0` (`usertype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `usertype`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `vendor`
-- 

CREATE TABLE `vendor` (
  `vendorid` int(19) NOT NULL default '0',
  `company_name` varchar(100) default NULL,
  `vendorname` varchar(100) default NULL,
  `phone` varchar(100) default NULL,
  `email` varchar(100) default NULL,
  `website` varchar(100) default NULL,
  `glacct` varchar(50) default NULL,
  `category` varchar(50) default NULL,
  `street` text,
  `city` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `postalcode` varchar(100) default NULL,
  `country` varchar(100) default NULL,
  `description` text,
  PRIMARY KEY  (`vendorid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `vendor`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `vendorcf`
-- 

CREATE TABLE `vendorcf` (
  `vendorid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`vendorid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `vendorcf`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `vendorcontactrel`
-- 

CREATE TABLE `vendorcontactrel` (
  `vendorid` int(19) NOT NULL default '0',
  `contactid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`vendorid`,`contactid`),
  KEY `VendorContactRel_IDX0` (`vendorid`),
  KEY `VendorContactRel_IDX1` (`contactid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `vendorcontactrel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `wordtemplates`
-- 

CREATE TABLE `wordtemplates` (
  `templateid` int(19) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `module` varchar(30) NOT NULL,
  `date_entered` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `parent_type` varchar(50) NOT NULL,
  `data` longblob,
  `description` text,
  `filesize` varchar(50) NOT NULL,
  `filetype` varchar(20) NOT NULL,
  `deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`templateid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `wordtemplates`
-- 

