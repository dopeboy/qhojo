insert into STATE VALUES (100,'INIT',1);
insert into STATE VALUES (200,'REQUESTED',1);
insert into STATE VALUES (300,'RESERVED',1);
insert into STATE VALUES (400,'REJECTED',1);
insert into STATE VALUES (401,'WITHDRAWN',1);
insert into STATE VALUES (500,'EXCHANGED',1);
insert into STATE VALUES (600,'CANCELLED BY LENDER',1);
insert into STATE VALUES (601,'CANCELLED BY BORROWER',1);
insert into STATE VALUES (650,'LATE',1);
insert into STATE VALUES (700,'RETURNED',1);
insert into STATE VALUES (800,'REPORTED DAMAGED BY LENDER',1);
insert into STATE VALUES (801,'REPORTED DAMAGED BY BORROWER',1);
insert into STATE VALUES (900,'REVIEWED BY LENDER',1);
insert into STATE VALUES (1000,'REVIEWED BY BORROWER',1);
insert into STATE VALUES (1100,'REVIEWED BY BORROWER',1);
insert into STATE VALUES (1200,'REVIEWED BY LENDER',1);

insert into EDGE VALUES (0,100,200,'USER REQUESTED ITEM', '%B requested %L\'s item', 1);
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

