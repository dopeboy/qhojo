drop table if exists USER;
CREATE TABLE USER
(
	ID                              INTEGER AUTO_INCREMENT PRIMARY KEY,
	FIRST_NAME			VARCHAR(80),
	LAST_NAME			VARCHAR(80),
	EMAIL_ADDRESS			VARCHAR(80),
	PASSWORD			VARCHAR(80),
        PHONE_NUMBER                    VARCHAR(80),
        PROFILE_PICTURE_FILENAME        VARCHAR(80)
);

drop table if exists ITEM;
CREATE TABLE ITEM
(
	ID                              INTEGER AUTO_INCREMENT PRIMARY KEY,
	TITLE 				VARCHAR(80),
	DESCRIPTION			TEXT,
	RATE				FLOAT,
        DEPOSIT				FLOAT,                  
        LOCATION_ID                     INTEGER,                        
	STATE_ID			INTEGER,
	LENDER_ID			INTEGER,
	BORROWER_ID			INTEGER,
        BORROW_DURATION                 INTEGER,
	START_DATE			DATETIME,
	END_DATE			DATETIME,
        LENDER_TO_BORROWER_STARS	INTEGER,
	BORROWER_TO_LENDER_STARS	INTEGER,
        CONFIRMATION_CODE               INTEGER
);

drop table if exists ITEM_STATE;
CREATE TABLE ITEM_STATE
(
	ID                              INTEGER PRIMARY KEY,
	DESCRIPTION			VARCHAR(80)
);

drop table if exists ITEM_PICTURES;
CREATE TABLE ITEM_PICTURES
(
	ITEM_ID                         INTEGER,
	FILENAME                        VARCHAR(80),
        PRIMARY_FLAG                    BOOL
);

drop table if exists LOCATION;
CREATE TABLE LOCATION
(
        ID                              INTEGER AUTO_INCREMENT PRIMARY KEY,
        BOROUGH                         VARCHAR(80), 
	NEIGHBORHOOD                    VARCHAR(80)
);

/* VIEWS                                               */

CREATE OR REPLACE VIEW ITEM_VW AS
SELECT 
it.ID as "ITEM_ID", 
it.TITLE,
it.DESCRIPTION,
it.RATE,
it.DEPOSIT,
n1.BOROUGH,
n1.NEIGHBORHOOD,
ist.ID as "ITEM_STATE_ID",
ist.DESCRIPTION as "ITEM_STATE_DESC",
it.LENDER_ID,
u1.FIRST_NAME as "LENDER_FIRST_NAME",
u1.PROFILE_PICTURE_FILENAME as "LENDER_PICTURE_FILENAME",
u1.PHONE_NUMBER as "LENDER_PHONE_NUMBER",
u1.EMAIL_ADDRESS as "LENDER_EMAIL_ADDRESS",
it.BORROWER_ID,
u2.FIRST_NAME as "BORROWER_FIRST_NAME",
u2.PHONE_NUMBER as "BORROWER_PHONE_NUMBER",
it.BORROW_DURATION,
it.LENDER_TO_BORROWER_STARS,
it.BORROWER_TO_LENDER_STARS,
it.START_DATE,
it.END_DATE,
ip.FILENAME as "ITEM_PICTURE_FILENAME",
it.CONFIRMATION_CODE
FROM ITEM it
INNER JOIN ITEM_STATE ist on it.STATE_ID=ist.ID
INNER JOIN USER u1 on u1.ID=it.LENDER_ID
INNER JOIN ITEM_PICTURES ip on it.ID=ip.ITEM_ID and ip.PRIMARY_FLAG=true
LEFT JOIN USER u2 on u2.ID=it.BORROWER_ID
INNER JOIN LOCATION n1 on it.LOCATION_ID=n1.ID;
