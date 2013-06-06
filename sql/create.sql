drop table if exists USER;
CREATE TABLE USER
(
	ID                              INTEGER PRIMARY KEY,
	FIRST_NAME			VARCHAR(80),
	LAST_NAME			VARCHAR(80),
	EMAIL_ADDRESS			VARCHAR(80),
	PASSWORD			CHAR(128),
        LOCATION_ID                     INTEGER,
        PHONE_NUMBER                    VARCHAR(80),
        PROFILE_PICTURE_FILENAME        VARCHAR(80),
        PAYPAL_EMAIL_ADDRESS            VARCHAR(80),
        PAYPAL_FIRST_NAME               VARCHAR(80),
        PAYPAL_LAST_NAME                VARCHAR(80),
        BP_BUYER_URI                    VARCHAR(160),
        BP_PRIMARY_CARD_URI             VARCHAR(160),
        JOIN_DATE                       DATETIME,
        ADMIN_FLAG                      BOOL DEFAULT 0,
        ACTIVE_FLAG                     INTEGER
);

ALTER TABLE USER ADD INDEX ID (ID);
ALTER TABLE USER ADD INDEX EMAIL_ADDRESS (EMAIL_ADDRESS);

drop table if exists ITEM;
CREATE TABLE ITEM
(
	ID                              INTEGER PRIMARY KEY,
	TITLE 				VARCHAR(80),
	DESCRIPTION			TEXT,
	RATE				FLOAT,
        DEPOSIT				FLOAT,                  
        LOCATION_ID                     INTEGER,                        
	STATE_ID			INTEGER,
	LENDER_ID			INTEGER,
	START_DATE			DATETIME,
	END_DATE			DATETIME,
        LENDER_TO_BORROWER_STARS	INTEGER,
	BORROWER_TO_LENDER_STARS	INTEGER,
        LENDER_TO_BORROWER_COMMENTS     TEXT,
        BORROWER_TO_LENDER_COMMENTS     TEXT,
        CONFIRMATION_CODE               INTEGER,
        ACTIVE_FLAG                     INTEGER,
        CREATE_DATE                     DATETIME,
        BORROWER_BP_HOLD_URI            VARCHAR(80)
);

ALTER TABLE ITEM ADD INDEX ID (ID);
ALTER TABLE ITEM ADD INDEX LENDER_ID (LENDER_ID);

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

ALTER TABLE ITEM_PICTURES ADD INDEX ITEM_ID (ITEM_ID);

drop table if exists ITEM_REQUESTS;
CREATE TABLE ITEM_REQUESTS
(
        REQUEST_ID                      INTEGER PRIMARY KEY,
	ITEM_ID                         INTEGER,
	REQUESTER_ID                    INTEGER,
        DURATION                        INTEGER,
        MESSAGE                         TEXT,
        ACCEPTED_FLAG                   BOOL, 
        CREATE_DATE                     DATETIME
);

ALTER TABLE ITEM_REQUESTS ADD INDEX ITEM_ID (ITEM_ID);
ALTER TABLE ITEM_REQUESTS ADD INDEX REQUESTER_ID (REQUESTER_ID);

drop table if exists BOROUGH;
CREATE TABLE BOROUGH
(
        ID                              INTEGER PRIMARY KEY,
        SHORT_NAME                      VARCHAR(80),
        FULL_NAME                       VARCHAR(80)
);

drop table if exists NEIGHBORHOOD;
CREATE TABLE NEIGHBORHOOD
(
        ID                              INTEGER PRIMARY KEY,
        BOROUGH_ID                      INTEGER,
        FULL_NAME                       VARCHAR(80)
);

drop table if exists NETWORK;
CREATE TABLE NETWORK
(
        ID                              INTEGER PRIMARY KEY,
        NAME                            VARCHAR(80),
        EMAIL_EXTENSION                 VARCHAR(80),
        ICON_IMAGE                      VARCHAR(80)
);

drop table if exists USER_NETWORK;
CREATE TABLE USER_NETWORK
(
        CONFIRMATION_ID                 CHAR(13),
        USER_ID                         INTEGER,
        NETWORK_ID                      INTEGER,
        USER_NETWORK_EMAIL              VARCHAR(80),
        ACTIVE                          BOOL,
        CREATION_DATE                   DATETIME,
        CONFIRMED_DATE                  DATETIME
);

drop table if exists TAG;
CREATE TABLE TAG
(
        ID                              INTEGER PRIMARY KEY,
        NAME                            VARCHAR(160)
);

ALTER TABLE TAG ADD INDEX ID (ID);

drop table if exists ITEM_TAG;
CREATE TABLE ITEM_TAG
(
        ITEM_ID                         INTEGER,
        TAG_ID                          INTEGER
);

ALTER TABLE ITEM_TAG ADD INDEX ITEM_ID (ITEM_ID);

/* VIEWS                                               */

CREATE OR REPLACE VIEW LOCATION_VW AS
SELECT 
n.ID,
b.SHORT_NAME as "BOROUGH_SHORT",
b.FULL_NAME as "BOROUGH_FULL",
n.FULL_NAME as "NEIGHBORHOOD",
b.ID as "BOROUGH_ID"
FROM BOROUGH b
INNER JOIN NEIGHBORHOOD n on b.ID=n.BOROUGH_ID;

CREATE OR REPLACE VIEW ITEM_VW AS
SELECT 
it.ID as "ITEM_ID", 
it.TITLE,
it.DESCRIPTION,
it.RATE,
it.DEPOSIT,
it.LOCATION_ID,
n1.BOROUGH_ID,
n1.BOROUGH_SHORT,
n1.BOROUGH_FULL,
n1.NEIGHBORHOOD,
ist.ID as "ITEM_STATE_ID",
ist.DESCRIPTION as "ITEM_STATE_DESC",
it.LENDER_ID,
u1.FIRST_NAME as "LENDER_FIRST_NAME",
u1.PROFILE_PICTURE_FILENAME as "LENDER_PICTURE_FILENAME",
u1.PHONE_NUMBER as "LENDER_PHONE_NUMBER",
u1.EMAIL_ADDRESS as "LENDER_EMAIL_ADDRESS",
u1.PAYPAL_EMAIL_ADDRESS as "LENDER_PAYPAL_EMAIL_ADDRESS",
ir.REQUESTER_ID as BORROWER_ID,
u2.FIRST_NAME as "BORROWER_FIRST_NAME",
u2.PROFILE_PICTURE_FILENAME as "BORROWER_PICTURE_FILENAME",
u2.PHONE_NUMBER as "BORROWER_PHONE_NUMBER",
u2.EMAIL_ADDRESS as "BORROWER_EMAIL_ADDRESS",
u2.BP_BUYER_URI as "BORROWER_BP_BUYER_URI",
ir.DURATION,
it.LENDER_TO_BORROWER_STARS,
it.BORROWER_TO_LENDER_STARS,
it.START_DATE,
it.END_DATE,
ip.FILENAME as "ITEM_PICTURE_FILENAME",
it.CONFIRMATION_CODE,
it.LENDER_TO_BORROWER_COMMENTS,
it.BORROWER_TO_LENDER_COMMENTS,
ir.REQUEST_ID,
it.BORROWER_BP_HOLD_URI
FROM ITEM it
INNER JOIN ITEM_STATE ist on it.STATE_ID=ist.ID
INNER JOIN USER u1 on u1.ID=it.LENDER_ID
INNER JOIN ITEM_PICTURES ip on it.ID=ip.ITEM_ID and ip.PRIMARY_FLAG=true
LEFT JOIN ITEM_REQUESTS ir on it.ID=ir.ITEM_ID and ir.ACCEPTED_FLAG=true
LEFT JOIN USER u2 on u2.ID=ir.REQUESTER_ID
INNER JOIN LOCATION_VW n1 on it.LOCATION_ID=n1.ID
WHERE it.ACTIVE_FLAG=1;

CREATE OR REPLACE VIEW ITEM_REQUESTS_VW AS
SELECT 
ir.REQUEST_ID,
ir.ITEM_ID,
it.TITLE,
it.RATE,
ir.REQUESTER_ID,
u1.FIRST_NAME as "REQUESTER_FIRST_NAME",
u1.EMAIL_ADDRESS as "REQUESTER_EMAIL_ADDRESS",
ir.DURATION,
ir.MESSAGE,
it.LENDER_ID
FROM ITEM_REQUESTS ir
INNER JOIN ITEM_VW it on ir.ITEM_ID=it.ITEM_ID
INNER JOIN USER u1 on ir.REQUESTER_ID=u1.ID
where it.ITEM_STATE_ID=0 and ir.ACCEPTED_FLAG is null;

CREATE OR REPLACE VIEW TAG_VW AS
SELECT 
it.ITEM_ID,
t.NAME as "TAG_NAME"
FROM ITEM_TAG it
INNER JOIN TAG t on it.TAG_ID=t.ID;

CREATE OR REPLACE VIEW USER_VW AS
SELECT 
u.ID,
u.FIRST_NAME,
u.LAST_NAME,
u.EMAIL_ADDRESS,
u.PASSWORD,
u.PHONE_NUMBER,
u.PROFILE_PICTURE_FILENAME,
u.PAYPAL_EMAIL_ADDRESS,
u.PAYPAL_FIRST_NAME,
u.PAYPAL_LAST_NAME,
u.BP_BUYER_URI,
u.BP_PRIMARY_CARD_URI,
u.JOIN_DATE,
u.ADMIN_FLAG,
l.ID as "LOCATION_ID",
l.BOROUGH_SHORT,
l.BOROUGH_FULL,
l.NEIGHBORHOOD
FROM USER u
LEFT JOIN LOCATION_VW l on u.LOCATION_ID = l.ID
where u.ACTIVE_FLAG=1;
