

insert into ITEM_STATE VALUES (0,'OPEN');
insert into ITEM_STATE VALUES (1,'RESERVED');
insert into ITEM_STATE VALUES (2,'EXCHANGED');
insert into ITEM_STATE VALUES (3,'RETURNED');

insert into BOROUGH (ID, SHORT_NAME, FULL_NAME) VALUES (0,'mh','manhattan');
insert into BOROUGH (ID, SHORT_NAME, FULL_NAME) VALUES (1,'bk','brooklyn');
-- insert into BOROUGH (ID, SHORT_NAME, FULL_NAME) VALUES (2,'bx','the bronx');
-- insert into BOROUGH (ID, SHORT_NAME, FULL_NAME) VALUES (3,'q','queens');
-- insert into BOROUGH (ID, SHORT_NAME, FULL_NAME) VALUES (4,'si','staten island');

insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (0,0,'greenwich village');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (1,0,'morningside heights');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (2,1,'dumbo');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (3,0,'lower east side');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (4,1,'fort greene');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (5,1,'park slope');
-- Added 06/12
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (6,1,'williamsburg');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (7,1,'bushwick');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (8,1,'prospect heights');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (9,1,'clinton hill');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (10,1,'crown heights');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (11,1,'bed-stuy');


insert into NETWORK VALUES (0,'NYU','nyu.edu', 'nyu.png');
insert into NETWORK VALUES (1,'Columbia University','columbia.edu', 'columbia.png');

insert into TAG VALUES (0,'photo');
insert into TAG VALUES (1,'video');
insert into TAG VALUES (2,'outdoor');