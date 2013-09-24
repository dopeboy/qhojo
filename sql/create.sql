drop database qhojo_staging;
create database qhojo_staging;
use qhojo_staging;

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
    ADMIN                            INTEGER DEFAULT 0,
    ACTIVE                          INTEGER
);

ALTER TABLE USER ADD INDEX ID (ID);
ALTER TABLE USER ADD INDEX EMAIL_ADDRESS (EMAIL_ADDRESS);

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
    ACTIVE                              INTEGER,
    CREATE_DATE                         DATETIME
);

ALTER TABLE ITEM ADD INDEX ID (ID);
ALTER TABLE ITEM ADD INDEX LENDER_ID (LENDER_ID);

drop table if exists STATE;
CREATE TABLE STATE
(
    ID                                  INTEGER PRIMARY KEY,
    NAME                                VARCHAR(80),
    ACTIVE                              INTEGER
);

ALTER TABLE STATE ADD INDEX ID (ID);

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

ALTER TABLE EDGE ADD INDEX ID (ID);
ALTER TABLE EDGE ADD INDEX STATE_B_ID (STATE_B_ID);

drop table if exists TRANSACTION;
CREATE TABLE TRANSACTION
(
    ID                                  INTEGER PRIMARY KEY,
    ITEM_ID                             INTEGER,
    BORROWER_ID                         INTEGER
);

ALTER TABLE TRANSACTION ADD INDEX ID (ID);
ALTER TABLE TRANSACTION ADD INDEX ITEM_ID (ITEM_ID);
ALTER TABLE TRANSACTION ADD INDEX BORROWER_ID (BORROWER_ID);

drop table if exists DETAIL;
CREATE TABLE DETAIL
(
    TRANSACTION_ID                      INTEGER,
    EDGE_ID                             INTEGER,
    ENTRY_DATE                          DATETIME,
    DATA                                blob,
    USER_ID                             INTEGER
);

ALTER TABLE DETAIL ADD INDEX TRANSACTION_ID (TRANSACTION_ID);
ALTER TABLE DETAIL ADD INDEX EDGE_ID (EDGE_ID);

drop table if exists ITEM_PICTURE;
CREATE TABLE ITEM_PICTURE
(
	ITEM_ID                         INTEGER,
	FILENAME                        VARCHAR(80),
        PRIMARY_FLAG                    INTEGER
);

ALTER TABLE ITEM_PICTURE ADD INDEX ITEM_ID (ITEM_ID);

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

ALTER TABLE INVITE ADD INDEX ID (ID);
ALTER TABLE INVITE ADD INDEX CODE (CODE);

drop table if exists INVITE_REQUEST;
CREATE TABLE INVITE_REQUEST
(
	ID                                  INTEGER PRIMARY KEY,
        FIRST_NAME                          VARCHAR(80),
        LAST_NAME                           VARCHAR(80),
        EMAIL_ADDRESS                       VARCHAR(80),
        DATE_CREATED                        DATETIME
);


/*  insert into INVITE VALUES (md5(rand()),'222',9,1,null,1); */

/* VIEWS                                               */

CREATE OR REPLACE VIEW INVITE_VW AS
SELECT
ID as "INVITE_ID",
CODE as "INVITE_CODE",
COUNT,
CREATED_BY_USER_ID,
DATE_CREATED
FROM INVITE
WHERE ACTIVE = 1;


CREATE OR REPLACE VIEW REJECT_OPTIONS_VW AS
SELECT
ID as "REJECT_OPTION_ID",
DESCRIPTION as "REJECT_DESCRIPTION",
DISPLAY_ORDER
FROM REJECT_OPTIONS
ORDER BY DISPLAY_ORDER;

CREATE OR REPLACE VIEW WITHDRAW_OPTIONS_VW AS
SELECT
ID as "WITHDRAW_OPTION_ID",
DESCRIPTION as "WITHDRAW_DESCRIPTION",
DISPLAY_ORDER
FROM WITHDRAW_OPTIONS
ORDER BY DISPLAY_ORDER;

CREATE OR REPLACE VIEW CANCEL_OPTIONS_VW AS
SELECT
ID as "CANCEL_OPTION_ID",
DESCRIPTION as "CANCEL_DESCRIPTION",
DISPLAY_ORDER
FROM CANCEL_OPTIONS
ORDER BY DISPLAY_ORDER;

CREATE OR REPLACE VIEW DAMAGE_OPTIONS_VW AS
SELECT
ID as "DAMAGE_OPTION_ID",
DESCRIPTION as "DAMAGE_DESCRIPTION",
DISPLAY_ORDER
FROM DAMAGE_OPTIONS
ORDER BY DISPLAY_ORDER;

CREATE OR REPLACE VIEW BASE_VW AS
SELECT 
i.ID as "ITEM_ID",
i.TITLE,
i.DESCRIPTION as "ITEM_DESCRIPTION",
i.RATE,
i.DEPOSIT,                  
i.ZIPCODE,
i.CITY,
i.STATE,
i.LENDER_ID,
u2.FIRST_NAME as "LENDER_FIRST_NAME",
CONCAT(u2.FIRST_NAME,' ',UPPER(LEFT(u2.LAST_NAME,1)),'.') as "LENDER_NAME",
u2.PHONE_NUMBER as "LENDER_PHONE_NUMBER",
u2.PAYPAL_EMAIL_ADDRESS as "LENDER_PAYPAL_EMAIL_ADDRESS",
u2.EMAIL_ADDRESS as "LENDER_EMAIL_ADDRESS",
i.ACTIVE as "ITEM_ACTIVE",
i.CREATE_DATE,
ip.FILENAME as "ITEM_PICTURE_FILENAME",
t.ID as "TRANSACTION_ID",
t.BORROWER_ID,
CONCAT(u1.FIRST_NAME,' ',UPPER(LEFT(u1.LAST_NAME,1)),'.') as "BORROWER_NAME",
u1.FIRST_NAME as "BORROWER_FIRST_NAME",
u1.PHONE_NUMBER as "BORROWER_PHONE_NUMBER",
u1.BP_BUYER_URI as "BORROWER_BP_BUYER_URI",
u1.EMAIL_ADDRESS as "BORROWER_EMAIL_ADDRESS",
d.EDGE_ID,
d.ENTRY_DATE,
d.DATA,
d.USER_ID,
e.STATE_A_ID,
e.STATE_B_ID,
REPLACE(REPLACE(e.LONG_DESCRIPTION,'%B',u1.FIRST_NAME),'%L',u2.FIRST_NAME) as "SUMMARY",
s.ID as "STATE_ID",
s.NAME as "STATE_NAME"
FROM ITEM i
INNER JOIN ITEM_PICTURE ip on i.ID=ip.ITEM_ID and ip.PRIMARY_FLAG=true
INNER JOIN TRANSACTION t on i.ID=t.ITEM_ID   
INNER JOIN DETAIL d on d.TRANSACTION_ID=t.ID  
INNER JOIN EDGE e on e.ID=d.EDGE_ID and e.ACTIVE=1
INNER JOIN STATE s on s.ID=e.STATE_B_ID and s.ACTIVE=1
INNER JOIN USER u1 on t.BORROWER_ID=u1.ID
INNER JOIN USER u2 on i.LENDER_ID=u2.ID;

-- Show newest requests last; hence the sort by max(entry_date)
CREATE OR REPLACE VIEW REQUESTED_BASE_VW AS
SELECT 
TRANSACTION_ID, 
MAX(STATE_B_ID),
max(ENTRY_DATE) as "END_STATE_DATE"
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID)=200
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW REQUESTED_VW AS
SELECT 
a.*
FROM BASE_VW a 
inner join REQUESTED_BASE_VW s 
on a.TRANSACTION_ID=s.TRANSACTION_ID
ORDER BY s.END_STATE_DATE ASC, a.TRANSACTION_ID, a.STATE_A_ID;

CREATE OR REPLACE VIEW PENDING_BASE_VW AS
SELECT 
TRANSACTION_ID, 
MAX(STATE_B_ID),
max(ENTRY_DATE) as "END_STATE_DATE"
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID)=250
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW PENDING_VW AS
SELECT 
a.*
FROM BASE_VW a 
inner join PENDING_BASE_VW s 
on a.TRANSACTION_ID=s.TRANSACTION_ID
ORDER BY s.END_STATE_DATE ASC, a.TRANSACTION_ID, a.STATE_A_ID;

CREATE OR REPLACE VIEW RESERVED_BASE_VW AS
SELECT 
TRANSACTION_ID, 
MAX(STATE_B_ID),
max(ENTRY_DATE) as "END_STATE_DATE"
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID)=300
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW RESERVED_VW AS
SELECT 
a.*
FROM BASE_VW a 
inner join RESERVED_BASE_VW s 
on a.TRANSACTION_ID=s.TRANSACTION_ID
ORDER BY s.END_STATE_DATE ASC, a.TRANSACTION_ID, a.STATE_A_ID;

CREATE OR REPLACE VIEW EXCHANGED_BASE_VW AS
SELECT 
TRANSACTION_ID, 
MAX(STATE_B_ID),
max(ENTRY_DATE) as "END_STATE_DATE"
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID)=500
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW EXCHANGED_VW AS
SELECT 
a.*
FROM BASE_VW a 
inner join EXCHANGED_BASE_VW s 
on a.TRANSACTION_ID=s.TRANSACTION_ID
ORDER BY s.END_STATE_DATE ASC, a.TRANSACTION_ID, a.STATE_A_ID;

CREATE OR REPLACE VIEW LATE_BASE_VW AS
SELECT 
TRANSACTION_ID, 
MAX(STATE_B_ID),
max(ENTRY_DATE) as "END_STATE_DATE"
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID)=650
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW LATE_VW AS
SELECT 
a.*
FROM BASE_VW a 
inner join LATE_BASE_VW s 
on a.TRANSACTION_ID=s.TRANSACTION_ID
ORDER BY s.END_STATE_DATE ASC, a.TRANSACTION_ID, a.STATE_A_ID;

CREATE OR REPLACE VIEW DAMAGED_BASE_VW AS
SELECT 
TRANSACTION_ID, 
MAX(STATE_B_ID),
max(ENTRY_DATE) as "END_STATE_DATE"
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID) in (800,801)
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW DAMAGED_VW AS
SELECT 
a.*
FROM BASE_VW a 
inner join DAMAGED_BASE_VW s 
on a.TRANSACTION_ID=s.TRANSACTION_ID
ORDER BY s.END_STATE_DATE ASC, a.TRANSACTION_ID, a.STATE_A_ID;

CREATE OR REPLACE VIEW RESERVED_AND_EXCHANGED_AND_LATE_BASE_VW AS
SELECT 
TRANSACTION_ID,
max(ENTRY_DATE) as "END_STATE_DATE",
MAX(STATE_B_ID)
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID) in (300,500,650)
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW RESERVED_AND_EXCHANGED_AND_LATE_VW AS
SELECT 
s.*
FROM RESERVED_AND_EXCHANGED_AND_LATE_BASE_VW a 
inner join BASE_VW s on s.TRANSACTION_ID=a.TRANSACTION_ID
ORDER BY a.END_STATE_DATE ASC, s.TRANSACTION_ID, s.STATE_A_ID;

CREATE OR REPLACE VIEW RETURNED_AND_NEED_LENDER_REVIEW_BASE_VW AS
SELECT 
TRANSACTION_ID,
max(ENTRY_DATE) as "END_STATE_DATE",
MAX(STATE_B_ID)
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID) in (700,1100)
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW RETURNED_AND_NEED_LENDER_REVIEW_VW AS
SELECT 
s.*
FROM RETURNED_AND_NEED_LENDER_REVIEW_BASE_VW a 
inner join BASE_VW s on s.TRANSACTION_ID=a.TRANSACTION_ID
ORDER BY a.END_STATE_DATE ASC, s.TRANSACTION_ID, s.STATE_A_ID;

CREATE OR REPLACE VIEW RETURNED_AND_NEED_BORROWER_REVIEW_BASE_VW AS
SELECT 
TRANSACTION_ID,
max(ENTRY_DATE) as "END_STATE_DATE",
MAX(STATE_B_ID)
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID) in (700,900)
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW RETURNED_AND_NEED_BORROWER_REVIEW_VW AS
SELECT 
s.*
FROM RETURNED_AND_NEED_BORROWER_REVIEW_BASE_VW a 
inner join BASE_VW s on s.TRANSACTION_ID=a.TRANSACTION_ID
ORDER BY a.END_STATE_DATE ASC, s.TRANSACTION_ID, s.STATE_A_ID;

CREATE OR REPLACE VIEW COMPLETED_BY_LENDER_BASE_VW AS
SELECT 
TRANSACTION_ID,
max(ENTRY_DATE) as "END_STATE_DATE",
MAX(STATE_B_ID)
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID) in (900,1000,1200)
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW COMPLETED_BY_LENDER_VW AS
SELECT 
s.*
FROM COMPLETED_BY_LENDER_BASE_VW a 
inner join BASE_VW s on s.TRANSACTION_ID=a.TRANSACTION_ID
ORDER BY a.END_STATE_DATE ASC, s.TRANSACTION_ID, s.STATE_A_ID;

CREATE OR REPLACE VIEW COMPLETED_BY_BORROWER_BASE_VW AS
SELECT 
TRANSACTION_ID,
max(ENTRY_DATE) as "END_STATE_DATE",
MAX(STATE_B_ID)
FROM BASE_VW 
GROUP BY TRANSACTION_ID 
HAVING MAX(STATE_B_ID) in (1000,1100, 1200)
ORDER BY max(ENTRY_DATE) ASC;

CREATE OR REPLACE VIEW COMPLETED_BY_BORROWER_VW AS
SELECT 
s.*
FROM COMPLETED_BY_BORROWER_BASE_VW a 
inner join BASE_VW s on s.TRANSACTION_ID=a.TRANSACTION_ID
ORDER BY a.END_STATE_DATE ASC, s.TRANSACTION_ID, s.STATE_A_ID;

-- --------------------------------------------------------------

CREATE OR REPLACE VIEW RESPONSE_TIME_BASE1_VW AS
SELECT LENDER_ID, TRANSACTION_ID, ENTRY_DATE 
FROM BASE_VW b 
WHERE b.STATE_B_ID in (300,400);

CREATE OR REPLACE VIEW RESPONSE_TIME_BASE2_VW AS
SELECT LENDER_ID, TRANSACTION_ID, ENTRY_DATE 
FROM BASE_VW b 
WHERE b.STATE_B_ID in (200);

CREATE OR REPLACE VIEW RESPONSE_TIME_VW AS
SELECT a.LENDER_ID, 
AVG(TIMESTAMPDIFF(MINUTE,b.ENTRY_DATE,a.ENTRY_DATE)) as "RESPONSE_TIME_IN_MINUTES"
FROM RESPONSE_TIME_BASE1_VW a
INNER JOIN RESPONSE_TIME_BASE2_VW b on a.TRANSACTION_ID=b.TRANSACTION_ID
GROUP BY LENDER_ID;


-- Completed is any transaction that has hit the "returned" state
CREATE OR REPLACE VIEW COMPLETED_LOANS_CNT_VW AS
SELECT
b.LENDER_ID as "USER_ID",
count(*) as "COUNT"
FROM BASE_VW b
where b.STATE_B_ID=700
group by b.LENDER_ID;

CREATE OR REPLACE VIEW COMPLETED_BORROWS_CNT_VW AS
SELECT
b.BORROWER_ID as "USER_ID",
count(*) as "COUNT"
FROM BASE_VW b
where b.STATE_B_ID=700
group by b.BORROWER_ID;

CREATE OR REPLACE VIEW USER_VW AS
SELECT 
u.ID as "USER_ID",
u.FIRST_NAME,
u.LAST_NAME,
CONCAT(u.FIRST_NAME,' ',UPPER(LEFT(u.LAST_NAME,1)),'.') as NAME,
u.EMAIL_ADDRESS,
u.ZIPCODE,
u.PASSWORD,
u.PHONE_NUMBER,
u.PHONE_VERIFIED,
u.PHONE_VERIFICATION_CODE,
u.PHONE_VERIFICATION_DATESTAMP,
u.PROFILE_PICTURE_FILENAME,
u.PAYPAL_EMAIL_ADDRESS,
u.PAYPAL_FIRST_NAME,
u.PAYPAL_LAST_NAME,
u.BP_BUYER_URI,
u.BP_PRIMARY_CARD_URI,
u.JOIN_DATE,
u.ADMIN,
u.ACTIVE,
u.BLURB,
COALESCE(clc.COUNT, 0) + COALESCE(cbc.COUNT, 0) as "COMPLETED_TRANSACTION_CNT",
CONCAT(u.CITY,', ',u.STATE) as "LOCATION",
COALESCE(r.RESPONSE_TIME_IN_MINUTES) as "RESPONSE_TIME_IN_MINUTES"
FROM USER u
LEFT JOIN COMPLETED_LOANS_CNT_VW clc on u.ID=clc.USER_ID
LEFT JOIN COMPLETED_BORROWS_CNT_VW cbc on u.ID=cbc.USER_ID
LEFT JOIN RESPONSE_TIME_VW r on u.ID=r.LENDER_ID;


CREATE OR REPLACE VIEW REVIEW_VW AS
SELECT 
b.ITEM_ID, 
b.TITLE as "ITEM_TITLE",
b.ITEM_ACTIVE,
b.DATA,
case
    when STATE_B_ID in (900,1200) then b.LENDER_ID
    when STATE_B_ID in (1000,1100) then b.BORROWER_ID
end as "REVIEWER_ID",
case
    when STATE_B_ID in (900,1200) then b.BORROWER_ID
    when STATE_B_ID in (1000,1100) then b.LENDER_ID
end as "REVIEWEE_ID",
case
    when STATE_B_ID in (900,1200) then u1.NAME
    when STATE_B_ID in (1000,1100) then u2.NAME
end as "REVIEWER_NAME",
case
    when STATE_B_ID in (900,1200) then u1.ACTIVE
    when STATE_B_ID in (1000,1100) then u2.ACTIVE
end as "REVIEWER_ACTIVE",
case
    when STATE_B_ID in (900,1200) then u1.PROFILE_PICTURE_FILENAME
    when STATE_B_ID in (1000,1100) then u2.PROFILE_PICTURE_FILENAME
end as "REVIEWER_PROFILE_PICTURE",
b.ENTRY_DATE as "REVIEW_DATE"
FROM BASE_VW b
INNER JOIN USER_VW u1 on b.LENDER_ID=u1.USER_ID
INNER JOIN USER_VW u2 on b.BORROWER_ID=u2.USER_ID
WHERE STATE_B_ID in (900,1000,1100,1200)
ORDER BY b.ENTRY_DATE DESC;



CREATE OR REPLACE VIEW ITEM_VW AS
SELECT 
i.ID as "ITEM_ID", 
i.TITLE,
i.DESCRIPTION,
i.RATE,
i.DEPOSIT,
i.ZIPCODE,
i.CITY,
i.STATE,
CONCAT(i.CITY,', ',i.STATE) as "LOCATION",
i.LENDER_ID,
ip.FILENAME as "ITEM_PICTURE_FILENAME",
i.CREATE_DATE,
u.PROFILE_PICTURE_FILENAME,
u.FIRST_NAME as "LENDER_FIRST_NAME",
u.NAME as "LENDER_NAME",
case
    when u.COMPLETED_TRANSACTION_CNT is null then 0
    else u.COMPLETED_TRANSACTION_CNT 
end as "COMPLETED_TRANSACTION_CNT",
COALESCE(r.RESPONSE_TIME_IN_MINUTES) as "RESPONSE_TIME_IN_MINUTES"
FROM ITEM i
INNER JOIN ITEM_PICTURE ip on i.ID=ip.ITEM_ID and ip.PRIMARY_FLAG=true
INNER JOIN USER_VW u on i.LENDER_ID=u.USER_ID and u.ACTIVE=1
LEFT JOIN RESPONSE_TIME_VW r on i.LENDER_ID=r.LENDER_ID
WHERE i.ACTIVE=1;

