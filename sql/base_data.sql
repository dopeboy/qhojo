

insert into ITEM_STATE VALUES (0,'OPEN');
insert into ITEM_STATE VALUES (1,'RESERVED');
insert into ITEM_STATE VALUES (2,'EXCHANGED');
insert into ITEM_STATE VALUES (3,'RETURNED');

insert into BOROUGH (ID, SHORT_NAME, FULL_NAME) VALUES (0,'mh','manhattan');
insert into BOROUGH (ID, SHORT_NAME, FULL_NAME) VALUES (1,'bk','brooklyn');
insert into BOROUGH (ID, SHORT_NAME, FULL_NAME) VALUES (2,'qns','queens');

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
-- Added 06/23
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (12,0,'midtown west');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (13,0,'midtown east');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (14,0,'upper west side');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (15,0,'upper east side');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (16,0,'west village');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (17,0,'chelsea');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (18,0,'nolita');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (19,0,'soho');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (20,0,'union square');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (21,0,'tribeca');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (22,0,'east village');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (23,0,'battery park');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (24,0,'chinatown');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (25,0,'lower manhattan');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (26,0,'east harlem');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (27,0,'financial district');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (28,0,'flatiron');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (29,0,'gramercy');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (30,0,'inwood');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (31,0,'murray hill');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (32,1,'brooklyn heights');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (33,1,'boerum hill');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (34,1,'cobble hill');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (35,1,'carrol gardens');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (36,1,'red hook');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (37,1,'prospect park south');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (38,2,'astoria');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (39,2,'lic');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (40,2,'steinway');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (41,2,'woodside');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (42,2,'maspeth');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (43,2,'ridgewood');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (44,2,'middle village');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (45,2,'glendale');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (46,2,'elmhurst');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (47,2,'forest hills');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (48,2,'jackson heights');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (49,2,'flushing');
insert into NEIGHBORHOOD (ID, BOROUGH_ID, FULL_NAME) VALUES (50,2,'kew gardens');



insert into NETWORK VALUES (0,'NYU','nyu.edu', 'nyu.png');
insert into NETWORK VALUES (1,'Columbia University','columbia.edu', 'columbia.png');

insert into TAG VALUES (0,'photo');
insert into TAG VALUES (1,'video');
insert into TAG VALUES (2,'outdoor');