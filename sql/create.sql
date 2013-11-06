drop table if exists USER;
CREATE TABLE USER
(
    ID                               INTEGER PRIMARY KEY,
    FIRST_NAME                      VARCHAR(80),
    LAST_NAME                       VARCHAR(80),
    EMAIL_ADDRESS                   VARCHAR(80),
    PASSWORD                        CHAR(128),
    ZIPCODE                         INTEGER,
    CITY                            VARCHAR(160),
    STATE                           VARCHAR(160),
    BLURB                           TEXT,
    PHONE_NUMBER                    VARCHAR(80),
    PHONE_VERIFIED                  INTEGER,
    PHONE_VERIFICATION_CODE         INTEGER,
    PHONE_VERIFICATION_DATESTAMP    DATETIME,
    PROFILE_PICTURE_FILENAME        VARCHAR(80),
    PAYPAL_EMAIL_ADDRESS            VARCHAR(80),
    PAYPAL_FIRST_NAME               VARCHAR(80),
    PAYPAL_LAST_NAME                VARCHAR(80),
    BP_BUYER_URI                    VARCHAR(160),
    BP_PRIMARY_CARD_URI             VARCHAR(160),
    JOIN_DATE                       DATETIME,
    ADMIN                           INTEGER DEFAULT 0,
    ACTIVE                          INTEGER
);

drop table if exists ITEM;
CREATE TABLE ITEM
(
    ID                                  INTEGER PRIMARY KEY,
    TITLE 				VARCHAR(80),
    DESCRIPTION                         TEXT,
    RATE				INTEGER,
    DEPOSIT				INTEGER,                  
    ZIPCODE                             INTEGER,
    CITY                                VARCHAR(160),
    STATE                               VARCHAR(160),
    LENDER_ID                           INTEGER,
    VIEWS                               INTEGER DEFAULT 0,
    ACTIVE                              INTEGER,
    CREATE_DATE                         DATETIME
);

drop table if exists STATE;
CREATE TABLE STATE
(
    ID                                  INTEGER PRIMARY KEY,
    NAME                                VARCHAR(80),
    ACTIVE                              INTEGER
);

drop table if exists EDGE;
CREATE TABLE EDGE
(
    ID                                  INTEGER PRIMARY KEY,
    STATE_A_ID                          INTEGER,
    STATE_B_ID                          INTEGER,
    SHORT_DESCRIPTION                   VARCHAR(80),
    LONG_DESCRIPTION                    VARCHAR(160),
    ACTIVE                              INTEGER    
);

drop table if exists TRANSACTION;
CREATE TABLE TRANSACTION
(
    ID                                  INTEGER PRIMARY KEY,
    ITEM_ID                             INTEGER,
    BORROWER_ID                         INTEGER
);

drop table if exists DETAIL;
CREATE TABLE DETAIL
(
    TRANSACTION_ID                      INTEGER,
    EDGE_ID                             INTEGER,
    ENTRY_DATE                          DATETIME,
    DATA                                blob,
    USER_ID                             INTEGER
);

drop table if exists ITEM_PICTURE;
CREATE TABLE ITEM_PICTURE
(
	ITEM_ID                         INTEGER,
	FILENAME                        VARCHAR(80),
        PRIMARY_FLAG                    INTEGER
);

drop table if exists REJECT_OPTIONS;
CREATE TABLE REJECT_OPTIONS
(
	ID                                 INTEGER PRIMARY KEY,
	DESCRIPTION                        VARCHAR(80),
        DISPLAY_ORDER                      INTEGER
);

drop table if exists WITHDRAW_OPTIONS;
CREATE TABLE WITHDRAW_OPTIONS
(
	ID                                 INTEGER PRIMARY KEY,
	DESCRIPTION                        VARCHAR(80),
        DISPLAY_ORDER                      INTEGER
);

drop table if exists CANCEL_OPTIONS;
CREATE TABLE CANCEL_OPTIONS
(
	ID                                 INTEGER PRIMARY KEY,
	DESCRIPTION                        VARCHAR(80),
        DISPLAY_ORDER                      INTEGER
);

drop table if exists DAMAGE_OPTIONS;
CREATE TABLE DAMAGE_OPTIONS
(
	ID                                 INTEGER PRIMARY KEY,
	DESCRIPTION                        VARCHAR(80),
        DISPLAY_ORDER                      INTEGER
);

drop table if exists CONTACT_MESSAGES;
CREATE TABLE CONTACT_MESSAGES
(
	ID                                 INTEGER PRIMARY KEY,
        SENDER_ID                          INTEGER,
        RECIPIENT_ID                       INTEGER,
        ENTITY_TYPE                        VARCHAR(80),
        ENTITY_ID                          INTEGER,
        MESSAGE                            TEXT,
        DATE_SENT                          DATETIME
);

drop table if exists INVITE;
CREATE TABLE INVITE
(
	ID                                 VARCHAR(80) PRIMARY KEY, /* alphanumeric */
        CODE                               VARCHAR(80),
        COUNT                              INTEGER,
        CREATED_BY_USER_ID                 INTEGER,
        DATE_CREATED                       DATETIME,
        ACTIVE                             INTEGER
);

drop table if exists INVITE_REQUEST;
CREATE TABLE INVITE_REQUEST
(
	ID                                  INTEGER PRIMARY KEY,
        FIRST_NAME                          VARCHAR(80),
        LAST_NAME                           VARCHAR(80),
        EMAIL_ADDRESS                       VARCHAR(80),
        DATE_CREATED                        DATETIME
);

drop table if exists NOTIFICATION;
CREATE TABLE NOTIFICATION
(
	ID                                  INTEGER PRIMARY KEY,
        SENDER_USER_ID                      INTEGER,
        RECEIPIENT_USER_ID                  INTEGER,
        TRANSACTION_ID                      INTEGER,
        TYPE_ID                             INTEGER,
        UNREAD                              INTEGER,
        DATE                                DATETIME
);

drop table if exists NOTIFICATION_TYPE;
CREATE TABLE NOTIFICATION_TYPE
(
	ID                                  INTEGER PRIMARY KEY,
        DESCRIPTION                         VARCHAR(80),
        TITLE                               VARCHAR(80),
        TEXT_PATTERN                        VARCHAR(160),
        LINK                                VARCHAR(80),
        ACTIVE                              INTEGER
);

drop table if exists SEARCH_HISTORY;
CREATE TABLE SEARCH_HISTORY
(
	ID                                  VARCHAR(80) PRIMARY KEY, /* alphanumeric */
        QUERY                               TEXT,
        LOCATION                            TEXT,
        SEARCHED_BY_USER_ID                 INTEGER,
        RESULTS_COUNT                       INTEGER,
        DATE                                DATETIME
);

insert into INVITE VALUES (md5(rand()),'333',99,1,null,1);

