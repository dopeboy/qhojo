insert into USER (ID, FIRST_NAME, LAST_NAME, EMAIL_ADDRESS, PASSWORD, ZIPCODE, CITY, STATE, JOIN_DATE, ADMIN, ACTIVE) 
VALUES (142543, 'Qho','Admin','support@qhojo.com','40ef41af0788a653148a6bc2849a4588ba2056058240b08a2eaada92f58bd94b79ba99aaaf394ffc913d7989988340dfa215f48b448ea119e6adf1763b649450',10002,'New York','NY','2013-09-21 13:22:01',1,1);

insert into STATE VALUES (100,'INIT',1);
insert into STATE VALUES (200,'REQUESTED',1);
insert into STATE VALUES (250,'PENDING',1);
insert into STATE VALUES (300,'RESERVED',1);
insert into STATE VALUES (400,'REJECTED',1);
insert into STATE VALUES (401,'WITHDRAWN',1);
insert into STATE VALUES (500,'EXCHANGED',1);
insert into STATE VALUES (600,'CANCELLED BY LENDER',1);
insert into STATE VALUES (601,'CANCELLED BY BORROWER',1);
insert into STATE VALUES (625,'HOLD FAILED',1);
insert into STATE VALUES (650,'LATE',1);
insert into STATE VALUES (700,'RETURNED',1);
insert into STATE VALUES (800,'REPORTED DAMAGED BY LENDER',1);
insert into STATE VALUES (801,'REPORTED DAMAGED BY BORROWER',1);
insert into STATE VALUES (900,'REVIEWED BY LENDER',1);
insert into STATE VALUES (1000,'REVIEWED BY BORROWER',1);
insert into STATE VALUES (1100,'REVIEWED BY BORROWER',1);
insert into STATE VALUES (1200,'REVIEWED BY LENDER',1);
insert into STATE VALUES (850,'EXPIRED',1);

insert into EDGE VALUES (0,100,200,'USER REQUESTED ITEM', '%B requested %L\'s item', 1);
insert into EDGE VALUES (19,200,250,'LENDER ACCEPTED REQUEST', '%L accepted %B\'s request', 1);
insert into EDGE VALUES (20,250,300,'BORROWER COMPLETED USER PROFILE', '%B completed their user profile', 1);

insert into EDGE VALUES (21,250,850,'BORROWER DID NOT COMPLETE USER PROFILE IN 24 HOURS', '%B did not complete their user profile', 1);
insert into EDGE VALUES (22,300,850,'ITEM WAS NOT EXCHANGED ON START DATE', '%B did not text confirmation on start date', 1);

insert into EDGE VALUES (1,200,300,'LENDER ACCEPTED REQUEST', '%L accepted %B\'s request', 1);
insert into EDGE VALUES (2,200,400,'LENDER REJECTED REQUEST', '%L rejected %B\'s request', 1);
insert into EDGE VALUES (18,200,401,'BORROWER WITHDREW REQUEST', '%B withdrew the request', 1);
insert into EDGE VALUES (3,300,500,'BORROWER CONFIRMED ITEM VIA TEXT MESSAGE', '%B confirmed and received %L\'s item',1);

insert into EDGE VALUES (4,300,600,'LENDER CANCELLED', '%L cancelled the transaction',1);
insert into EDGE VALUES (5,300,601,'BORROWER CANCELLED', '%B cancelled the transaction',1);
insert into EDGE VALUES (6,300,625,'HOLD FAILED','The credit card hold failed.',1);

insert into EDGE VALUES (7,500,700,'LENDER CONFIRMED RETURNED ITEM VIA TEXT MESSAGE','%B returned item to %L', 1);
insert into EDGE VALUES (8,500,650,'LENDER DID NOT SEND TEXT MESSAGE BY DUE DATE + SLACK TIME','%L never sent the confirmation text message',1);
insert into EDGE VALUES (9,500,800,'LENDER REPORTED ITEM AS DAMAGED','%L reported item as damaged',1);
insert into EDGE VALUES (10,500,801,'BORROWER REPORTED ITEM AS DAMAGED','%B reported item as damaged',1);

insert into EDGE VALUES (15,650,800,'LENDER REPORTED ITEM AS DAMAGED','%L reported item as damaged',1);
insert into EDGE VALUES (16,650,801,'BORROWER REPORTED ITEM AS DAMAGED','%B reported item as damaged',1);
insert into EDGE VALUES (17,650,700,'LENDER CONFIRMED RETURNED ITEM VIA TEXT MESSAGE','%B returned item to %L', 1);

insert into EDGE VALUES (11,700,900,'LENDER SUBMITTED FEEDBACK','%L reviewed %B',1);
insert into EDGE VALUES (12,700,1100,'BORROWER SUBMITTED FEEDBACK','%B reviewed %L',1);
insert into EDGE VALUES (13,900,1000,'BORROWER SUBMITTED FEEDBACK','%B reviewed %L',1);
insert into EDGE VALUES (14,1100,1200,'LENDER SUBMITTED FEEDBACK','%L reviewed %B',1);

insert into REJECT_OPTIONS VALUES (0,'Scheduling issue',0);
insert into REJECT_OPTIONS VALUES (1,'Other',1);

insert into WITHDRAW_OPTIONS VALUES (0,'Lender didn\'t reply in time',0);
insert into WITHDRAW_OPTIONS VALUES (1,'Changed mind',1);
insert into WITHDRAW_OPTIONS VALUES (2,'Other',2);

insert into CANCEL_OPTIONS VALUES (0,'Scheduling issue with the lender/borrower',0);
insert into CANCEL_OPTIONS VALUES (1,'Changed mind',1);
insert into CANCEL_OPTIONS VALUES (2,'Other',2);

insert into DAMAGE_OPTIONS VALUES (0,'Significant cosmetic damage',0);
insert into DAMAGE_OPTIONS VALUES (1,'Functionality is significantly impaired',1);
insert into DAMAGE_OPTIONS VALUES (2,'Item is no longer functional',2);

insert into NOTIFICATION_TYPE VALUES (0, 'info',  'New Request', 'You have received a request from %U to borrow your item %I. Click here to view it.', '/user/dashboard/#lending#%T', 1);
insert into NOTIFICATION_TYPE VALUES (1, 'error',  'Request Rejected', 'Your request to borrow %I was rejected by %U.', '', 1);
insert into NOTIFICATION_TYPE VALUES (2, 'success',  'Request Accepted', 'Your request to borrow %I was accepted by %U. Click here to view it.', '/user/dashboard/#borrowing#%T', 1);
insert into NOTIFICATION_TYPE VALUES (3, 'info',  'Pending', 'Your request to borrow %I was tentatively accepted by %U. To finalize it, you must complete your profile by clicking on this box.', '/user/completeprofile', 1);

/* 
insert into CATEGORY (ID, NAME, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (0,'Lens',0,0,NOW(),1);
insert into CATEGORY (ID, NAME, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (1,'Body',1,0,NOW(),1);
insert into CATEGORY (ID, NAME, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (2,'Accessory',2,0,NOW(),1);

insert into BRAND (ID, NAME, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (0,'Canon',0,0,NOW(),1);
insert into BRAND (ID, NAME, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (1,'Nikon',0,0,NOW(),1);
insert into BRAND (ID, NAME, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (2,'Kessler',0,0,NOW(),1);
insert into BRAND (ID, NAME, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (3,'Hasselback',0,0,NOW(),1);

insert into PRODUCT (ID, CATEGORY_ID, BRAND_ID, NAME, DESCRIPTION, DISPLAY_ORDER, VALUE, RATE, URL, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (0,0,0,'24-70mm F/2.8','Cool lens', 0, 100, 10, 'http://aaa.com', 0, NOW(),1);
insert into PRODUCT_IMAGE (ID, PRODUCT_ID, FILENAME, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (0,0, 'aaa.jpg', 0,NOW(),1);

insert into PRODUCT (ID, CATEGORY_ID, BRAND_ID, NAME, DESCRIPTION, DISPLAY_ORDER, VALUE, RATE, URL, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (1,1,0,'7D','Cool body', 0, 100, 10, 'http://aaa.com', 0, NOW(),1);

insert into PRODUCT (ID, CATEGORY_ID, BRAND_ID, NAME, DESCRIPTION, DISPLAY_ORDER, VALUE, RATE, URL, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (2,1,0,'6D','real Cool body', 0, 100, 10, 'http://aaa.com', 0, NOW(),1);

insert into PRODUCT (ID, CATEGORY_ID, BRAND_ID, NAME, DESCRIPTION, DISPLAY_ORDER, VALUE, RATE, URL, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (3,1,1,'D600','latest', 0, 100, 10, 'http://aaa.com', 0, NOW(),1);

insert into PRODUCT (ID, CATEGORY_ID, BRAND_ID, NAME, DESCRIPTION, DISPLAY_ORDER, VALUE, RATE, URL, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE)  
VALUES (4,2,3,'Stealth','good quality', 0, 100, 10, 'http://aaa.com', 0, NOW(),1);

*/