-- From schema/DatabaseSchema.xml

ALTER TABLE vtiger_organizationdetails
    ADD COLUMN deleted integer NOT NULL DEFAULT 0;
ALTER TABLE vtiger_organizationdetails
    ADD COLUMN quote_template character varying(30) DEFAULT NULL;
ALTER TABLE vtiger_organizationdetails
    ADD COLUMN so_template character varying(30) DEFAULT NULL;
ALTER TABLE vtiger_organizationdetails
    ADD COLUMN po_template character varying(30) DEFAULT NULL;
ALTER TABLE vtiger_organizationdetails
    ADD COLUMN invoice_template character varying(30) DEFAULT NULL;

CREATE TABLE vtiger_orgunit (
    orgunitid serial NOT NULL,
    organizationname character varying(60) NOT NULL,
    type character varying(30),
    name character varying(60) NOT NULL,
    address character varying(150),
    city character varying(100),
    state character varying(100),
    country character varying(100),
    code character varying(30),
    phone character varying(30),
    fax character varying(30),
    website character varying(100),
    deleted integer NOT NULL DEFAULT 0,
    quote_template character varying(30) DEFAULT NULL,
    so_template character varying(30) DEFAULT NULL,
    po_template character varying(30) DEFAULT NULL,
    invoice_template character varying(30) DEFAULT NULL
);

ALTER TABLE ONLY vtiger_orgunit
    ADD CONSTRAINT vtiger_orgunit_pkey PRIMARY KEY (orgunitid);

ALTER TABLE ONLY vtiger_orgunit
    ADD CONSTRAINT fk_1_vtiger_orgunit FOREIGN KEY (organizationname) REFERENCES vtiger_organizationdetails(organizationname)
	ON UPDATE CASCADE
	ON DELETE CASCADE;

CREATE UNIQUE INDEX vtiger_orgunit_name_idx ON vtiger_orgunit USING btree (organizationname, name);

CREATE INDEX vtiger_orgunit_type_idx ON vtiger_orgunit USING btree (organizationname, type);

CREATE TABLE vtiger_orgunittype (
    orgunittypeid serial NOT NULL,
    orgunittype character varying(200) NOT NULL,
    sortorderid integer DEFAULT 0 NOT NULL,
    presence integer DEFAULT 1 NOT NULL
);

ALTER TABLE ONLY vtiger_orgunittype
    ADD CONSTRAINT vtiger_orgunittype_pkey PRIMARY KEY (orgunittypeid);

CREATE UNIQUE INDEX vtiger_orgunittype_orgunittype_idx ON vtiger_orgunittype USING btree (orgunittype);

COPY vtiger_orgunittype (orgunittypeid, orgunittype, sortorderid, presence) FROM stdin;
1	--None--	0	1
2	Headquarter	1	1
3	Sales Office	2	1
4	Sales Department	3	1
5	Marketing Department	4	1
6	Technical Assitency Center	5	1
7	Customer Service Center	6	1
8	Operation Center	7	1
9	Maintenance Center	8	1
10	Human Resources Department	9	1
11	Purchasing Department	10	1
12	Development Department	11	1
13	Provisioning Department	12	1
14	Controlling Department	13	1
15	Asset Management Department	14	1
16	Bookkeeping Department	15	1
17	Bookkeeping Office	16	1
18	Tax Consultants	17	1
19	Law Enforcement Consultants	18	1
\.

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('vtiger_orgunittype', 'orgunittypeid'), 3, true);

CREATE TABLE vtiger_user2org (
    organizationname character varying(60) NOT NULL,
    userid integer NOT NULL DEFAULT 0,
    primarytag integer DEFAULT 0
);

ALTER TABLE ONLY vtiger_user2org
    ADD CONSTRAINT vtiger_user2org_pkey PRIMARY KEY (organizationname,userid);

CREATE INDEX vtiger_user2org_organizationname_idx ON vtiger_user2org USING btree (organizationname);

CREATE INDEX vtiger_user2org_userid_idx ON vtiger_user2org USING btree (userid);

ALTER TABLE ONLY vtiger_user2org
    ADD CONSTRAINT fk_1_vtiger_user2org FOREIGN KEY (organizationname) REFERENCES vtiger_organizationdetails(organizationname)
	ON UPDATE CASCADE
	ON DELETE CASCADE;

ALTER TABLE ONLY vtiger_user2org
    ADD CONSTRAINT fk_2_vtiger_user2org FOREIGN KEY (userid) REFERENCES vtiger_users(id) ON DELETE CASCADE;

INSERT INTO vtiger_user2org
    SELECT 'vtiger', id, '1' FROM vtiger_users;

CREATE TABLE vtiger_user2orgunit (
    orgunitid integer NOT NULL DEFAULT 0,
    userid integer NOT NULL DEFAULT 0,
    primarytag integer DEFAULT 0
);

ALTER TABLE ONLY vtiger_user2orgunit
    ADD CONSTRAINT vtiger_user2orgunit_pkey PRIMARY KEY (orgunitid,userid);

CREATE INDEX vtiger_user2orgunit_orgunitid_idx ON vtiger_user2orgunit USING btree (orgunitid);

CREATE INDEX vtiger_user2orgunit_userid_idx ON vtiger_user2orgunit USING btree (userid);

ALTER TABLE ONLY vtiger_user2orgunit
    ADD CONSTRAINT fk_1_vtiger_user2orgunit FOREIGN KEY (orgunitid) REFERENCES vtiger_orgunit(orgunitid) ON DELETE CASCADE;

ALTER TABLE ONLY vtiger_user2orgunit
    ADD CONSTRAINT fk_2_vtiger_user2orgunit FOREIGN KEY (userid) REFERENCES vtiger_users(id) ON DELETE CASCADE;

CREATE TABLE vtiger_entity2org (
    organizationname character varying(60) NOT NULL,
    crmid integer NOT NULL DEFAULT 0
    primarytag integer DEFAULT 0
);

ALTER TABLE ONLY vtiger_entity2org
    ADD CONSTRAINT vtiger_entity2org_pkey PRIMARY KEY (organizationname,crmid);

CREATE INDEX vtiger_entity2org_crmid_idx ON vtiger_entity2org USING btree (crmid);

CREATE INDEX vtiger_entity2org_organizationname_idx ON vtiger_entity2org USING btree (organizationname);

ALTER TABLE ONLY vtiger_entity2org
    ADD CONSTRAINT fk_1_vtiger_entity2org FOREIGN KEY (organizationname) REFERENCES vtiger_organizationdetails(organizationname)
	ON UPDATE CASCADE
	ON DELETE CASCADE;

ALTER TABLE ONLY vtiger_entity2org
    ADD CONSTRAINT fk_2_vtiger_entity2org FOREIGN KEY (crmid) REFERENCES vtiger_crmentity(crmid) ON DELETE CASCADE;

INSERT INTO vtiger_entity2org
    SELECT 'vtiger', crmid, '1' FROM vtiger_crmentity;

-- New organization module
INSERT INTO vtiger_tab VALUES (30,'Organization',0,30,'Company Details',null,null,1);
INSERT INTO vtiger_parenttabrel VALUES (8,30,2);

INSERT INTO vtiger_blocks VALUES (84,30,'LBL_COMPANY_DETAILS',1,0,0,0,0,0);
INSERT INTO vtiger_blocks VALUES (85,30,'LBL_COMPANY_TEMPLATES',1,0,0,0,0,0);
INSERT INTO vtiger_blocks VALUES (86,30,'LBL_COMPANY_ORGUNITS',1,0,0,0,0,0);
INSERT INTO vtiger_blocks VALUES (87,30,'LBL_COMPANY_BANKACCOUNTS',1,0,0,0,0,0);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,456,'organizationname','vtiger_organizationdetails',1,'2','organizationname','Company Name',1,0,0,60,3,84,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 456, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 456, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 456, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 456, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 456, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,457,'logo','vtiger_organizationdetails',1,'107','logo','Company Logo',1,0,0,100,1,84,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 457, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 457, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 457, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 457, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 457, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,458,'address','vtiger_organizationdetails',1,'2','address','Address',1,0,0,150,2,84,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 458, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 458, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 458, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 458, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 458, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,459,'state','vtiger_organizationdetails',1,'1','state','State',1,0,0,100,10,84,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 459, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 459, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 459, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 459, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 459, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,460,'code','vtiger_organizationdetails',1,'2','code','Postal Code',1,0,0,30,4,84,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 460, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 460, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 460, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 460, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 460, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,461,'country','vtiger_organizationdetails',1,'2','country','Country',1,0,0,100,8,84,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 461, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 461, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 461, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 461, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 461, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,462,'phone','vtiger_organizationdetails',1,'2','phone','Phone',1,0,0,30,7,84,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 462, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 462, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 462, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 462, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 462, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,463,'fax','vtiger_organizationdetails',1,'2','fax','Fax',1,0,0,30,9,84,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 463, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 463, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 463, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 463, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 463, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,464,'website','vtiger_organizationdetails',1,'17','website','Website',1,0,0,100,5,84,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 464, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 464, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 464, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 464, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 464, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,465,'city','vtiger_organizationdetails',1,'2','city','City',1,0,0,100,6,84,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 465, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 465, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 465, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 465, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 465, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,477,'quote_template','vtiger_organizationdetails',1,'1','quote_template','Quote Template',1,0,0,30,1,85,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 477, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 477, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 477, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 477, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 477, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,478,'po_template','vtiger_organizationdetails',1,'1','po_template','Purchase Order Template',1,0,0,30,2,85,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 478, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 478, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 478, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 478, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 478, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,479,'so_template','vtiger_organizationdetails',1,'1','so_template','Sales Order Template',1,0,0,30,3,85,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 479, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 479, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 479, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 479, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 479, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (30,480,'invoice_template','vtiger_organizationdetails',1,'1','invoice_template','Invoice Template',1,0,0,30,4,85,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 480, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 480, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 480, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 480, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 480, 0, 1);

INSERT INTO vtiger_tab VALUES (31,'OrgUnit',0,31,'Organization Unit',null,null,1);
INSERT INTO vtiger_parenttabrel VALUES (30,31,2);

INSERT INTO vtiger_blocks VALUES (88,31,'LBL_ORGUNIT_DETAILS',2,0,0,0,0,0);
INSERT INTO vtiger_blocks VALUES (89,31,'LBL_ORGUNIT_TEMPLATES',3,0,0,0,0,0);
INSERT INTO vtiger_blocks VALUES (90,31,'LBL_COMPANY_DETAILS',1,0,0,0,0,0);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,466,'name','vtiger_orgunit',1,'32','name','OrgUnit Name',1,0,0,60,3,88,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 466, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 466, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 466, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 466, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 466, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,467,'type','vtiger_orgunit',1,'16','orgunittype','OrgUnit Type',1,0,0,100,1,88,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 467, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 467, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 467, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 467, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 467, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,468,'address','vtiger_orgunit',1,'4','address','Address',1,0,0,150,2,88,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 468, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 468, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 468, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 468, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 468, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,469,'state','vtiger_orgunit',1,'3','state','State',1,0,0,100,10,88,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 469, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 469, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 469, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 469, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 469, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,470,'code','vtiger_orgunit',1,'4','code','Postal Code',1,0,0,30,4,88,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 470, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 470, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 470, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 470, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 470, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,471,'country','vtiger_orgunit',1,'4','country','Country',1,0,0,100,8,88,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 471, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 471, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 471, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 471, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 471, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,472,'phone','vtiger_orgunit',1,'4','phone','Phone',1,0,0,30,7,88,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 472, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 472, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 472, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 472, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 472, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,473,'fax','vtiger_orgunit',1,'4','fax','Fax',1,0,0,30,9,88,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 473, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 473, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 473, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 473, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 473, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,474,'website','vtiger_orgunit',1,'18','website','Website',1,0,0,100,5,88,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 474, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 474, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 474, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 474, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 474, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,475,'city','vtiger_orgunit',1,'4','city','City',1,0,0,100,6,88,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 475, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 475, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 475, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 475, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 475, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,481,'quote_template','vtiger_orgunit',1,'3','quote_template','Quote Template',1,0,0,30,1,89,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 481, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 481, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 481, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 481, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 481, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,482,'po_template','vtiger_orgunit',1,'3','po_template','Purchase Order Template',1,0,0,30,2,89,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 482, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 482, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 482, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 482, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 482, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,483,'so_template','vtiger_orgunit',1,'3','so_template','Sales Order Template',1,0,0,30,3,89,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 483, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 483, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 483, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 483, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 483, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,484,'invoice_template','vtiger_orgunit',1,'3','invoice_template','Invoice Template',1,0,0,30,4,89,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 484, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 484, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 484, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 484, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 484, 0, 1);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (31,485,'organizationname','vtiger_orgunit',1,'8','organizationname','Organization',1,0,0,30,1,90,1,'V~M',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 485, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 485, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 485, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 485, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 485, 0, 1);

-- Organization unit ids in potentials
ALTER TABLE vtiger_potential ADD COLUMN orgunitid integer;

-- From modules/Users/DefaultDataPopulator.php
UPDATE vtiger_field SET sequence=14 WHERE fieldid=115;
UPDATE vtiger_field SET sequence=15 WHERE fieldid=116;
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (2,455,'orgunitid','vtiger_potential',1,'12','orgunit','Organization unit',1,0,0,100,13,1,1,'N~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 455, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 455, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 455, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 455, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 455, 0, 1);

-- Organization unit ids in quotes
ALTER TABLE vtiger_quotes ADD COLUMN orgunitid integer;

-- From modules/Users/DefaultDataPopulator.php
UPDATE vtiger_field SET sequence=21 WHERE fieldid=288;
UPDATE vtiger_field SET sequence=20 WHERE fieldid=297;
UPDATE vtiger_field SET sequence=19 WHERE fieldid=296;
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (20,486,'orgunitid','vtiger_quotes',1,'12','orgunit','Organization unit',1,0,0,100,18,51,1,'N~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 486, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 486, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 486, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 486, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 486, 0, 1);

-- Organization unit ids in sales orders
ALTER TABLE vtiger_salesorder ADD COLUMN orgunitid integer;

-- From modules/Users/DefaultDataPopulator.php
UPDATE vtiger_field SET sequence=20 WHERE fieldid=370;
UPDATE vtiger_field SET sequence=19 WHERE fieldid=369;
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (22,487,'orgunitid','vtiger_salesorder',1,'12','orgunit','Organization unit',1,0,0,100,18,63,1,'N~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 487, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 487, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 487, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 487, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 487, 0, 1);

-- Organization unit ids in purchase orders and invoices
ALTER TABLE vtiger_purchaseorder ADD COLUMN orgunitid integer;

-- From modules/Users/DefaultDataPopulator.php
UPDATE vtiger_field SET sequence=19 WHERE fieldid=332;
UPDATE vtiger_field SET sequence=18 WHERE fieldid=331;
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (21,488,'orgunitid','vtiger_purchaseorder',1,'12','orgunit','Organization unit',1,0,0,100,17,57,1,'N~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 488, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 488, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 488, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 488, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 488, 0, 1);

-- Organization unit ids in invoices
ALTER TABLE vtiger_invoice ADD COLUMN orgunitid integer;

-- From modules/Users/DefaultDataPopulator.php
UPDATE vtiger_field SET sequence=19 WHERE fieldid=406;
UPDATE vtiger_field SET sequence=18 WHERE fieldid=405;
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (23,489,'orgunitid','vtiger_invoice',1,'12','orgunit','Organization unit',1,0,0,100,17,69,1,'N~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 489, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 489, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 489, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 489, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 489, 0, 1);

-- From modules/Users/DefaultDataPopulator.php
INSERT INTO vtiger_blocks VALUES (91,14,'LBL_COMPANY_ASSIGNMENT',7,0,0,0,0,0);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (14,490,'otherorgs','vtiger_entity2org',1,'14','otherorgs','Organization Assignment',1,0,0,100,1,91,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 490, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 490, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 490, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 490, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 490, 0, 1);

-- From modules/Users/DefaultDataPopulator.php
INSERT INTO vtiger_blocks VALUES (92,6,'LBL_COMPANY_ASSIGNMENT',5,0,0,0,0,0);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (6,491,'otherorgs','vtiger_entity2org',1,'14','otherorgs','Organization Assignment',1,0,0,100,1,92,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 491, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 491, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 491, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 491, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 491, 0, 1);

-- From modules/Users/DefaultDataPopulator.php
INSERT INTO vtiger_blocks VALUES (93,4,'LBL_COMPANY_ASSIGNMENT',7,0,0,0,0,0);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (4,492,'otherorgs','vtiger_entity2org',1,'14','otherorgs','Organization Assignment',1,0,0,100,1,93,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 492, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 492, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 492, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 492, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 492, 0, 1);

-- From modules/Users/DefaultDataPopulator.php
INSERT INTO vtiger_blocks VALUES (94,18,'LBL_COMPANY_ASSIGNMENT',5,0,0,0,0,0);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (18,493,'otherorgs','vtiger_entity2org',1,'14','otherorgs','Organization Assignment',1,0,0,100,1,94,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 493, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 493, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 493, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 493, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 493, 0, 1);

-- From modules/Users/DefaultDataPopulator.php
INSERT INTO vtiger_blocks VALUES (95,19,'LBL_COMPANY_ASSIGNMENT',4,0,0,0,0,0);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (19,494,'otherorgs','vtiger_entity2org',1,'14','otherorgs','Organization Assignment',1,0,0,100,1,95,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 494, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 494, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 494, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 494, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 494, 0, 1);

-- From modules/Users/DefaultDataPopulator.php
INSERT INTO vtiger_blocks VALUES (96,7,'LBL_COMPANY_ASSIGNMENT',5,0,0,0,0,0);
SELECT nextval('vtiger_field_fieldid_seq');  
INSERT INTO vtiger_field VALUES (7,495,'otherorgs','vtiger_entity2org',1,'14','otherorgs','Organization Assignment',1,0,0,100,1,96,1,'V~O',1,null,'BAS');
INSERT INTO vtiger_profile2field VALUES (1, 2, 495, 0, 1);
INSERT INTO vtiger_profile2field VALUES (2, 2, 495, 0, 1);
INSERT INTO vtiger_profile2field VALUES (3, 2, 495, 0, 1);
INSERT INTO vtiger_profile2field VALUES (4, 2, 495, 0, 1);
INSERT INTO vtiger_def_org_field VALUES (2, 495, 0, 1);

