

insert into ITEM_STATE VALUES (0,'OPEN');
insert into ITEM_STATE VALUES (1,'RESERVED');
insert into ITEM_STATE VALUES (2,'EXCHANGED');
insert into ITEM_STATE VALUES (3,'RETURNED');

insert into LOCATION (ID, BOROUGH, NEIGHBORHOOD) VALUES (1,'bk','dumbo');
insert into LOCATION (ID, BOROUGH, NEIGHBORHOOD) VALUES (2,'mh','greenwich village');
insert into LOCATION (ID, BOROUGH, NEIGHBORHOOD) VALUES (3,'mh','morningside heights');
insert into LOCATION (ID, BOROUGH, NEIGHBORHOOD) VALUES (4,'mh','lower east side');

insert into NETWORK VALUES (0,'NYU','nyu.edu', null);
insert into NETWORK VALUES (1,'Columbia University','columbia.edu', 'columbia.png');