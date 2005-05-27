#DROP TABLE webcal_user;
CREATE TABLE webcal_user (
  cal_login VARCHAR(25) NOT NULL,
  cal_passwd VARCHAR(25),
  cal_lastname VARCHAR(25),
  cal_firstname VARCHAR(25),
  cal_is_admin CHAR(1) DEFAULT 'N',
  cal_email VARCHAR(75) NULL,
  PRIMARY KEY ( cal_login )
);
#DROP TABLE webcal_entry;
CREATE TABLE webcal_entry (
  cal_id INT NOT NULL,
  cal_group_id INT NULL,
  cal_create_by VARCHAR(25) NOT NULL,
  cal_date INT NOT NULL,
  cal_time INT NULL,
  cal_mod_date INT,
  cal_mod_time INT,
  cal_duration INT NOT NULL,
  cal_priority INT DEFAULT 2,
  cal_type CHAR(1) DEFAULT 'E',
  cal_access CHAR(1) DEFAULT 'P',
  cal_name VARCHAR(80) NOT NULL,
  cal_description TEXT,
  PRIMARY KEY ( cal_id )
);
#DROP TABLE webcal_entry_user;
CREATE TABLE webcal_entry_user (
  cal_id int(11) DEFAULT '0' NOT NULL,
  cal_login varchar(25) DEFAULT '' NOT NULL,
  cal_status char(1) DEFAULT 'A',
  PRIMARY KEY (cal_id,cal_login)
);
#DROP TABLE webcal_user_pref;
CREATE TABLE webcal_user_pref (
  cal_login varchar(25) NOT NULL,
  cal_setting varchar(25) NOT NULL,
  cal_value varchar(50) NULL,
  PRIMARY KEY ( cal_login, cal_setting )
);












