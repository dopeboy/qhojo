insert into USER VALUES 
(
    100000,
    'Brian',
    'Fantana',
    'arithmetic@gmail.com',
    '8a17f7a49320d8b43862253d70da43c839cc164c517903007dc38f6ed163126b118e4e723fe7a14c5f50bfdcfafa51777a6d98122131b4ba01af0214487fbabf',
    '10001',
    'New York',
    'NY',
    'Hey I am en entrepreneur!',
    null,
    null,
    null,
    null,
    null,
    null,
    'Manish',
    'Sinha',
    null,
    null,
    '2013-07-31',
    0,
    1
);

insert into USER VALUES
(
    100001,
    'Ron',
    'Burgundy',
    'arithmetic@gmail.com',
    'blah',
    '11205',
    'Brooklyn',
    'NY',
    'Hey I am en entrepreneur!',
    '9495214954',
    1,
    null,
    null,
    'ron.jpg',
    'arithmetic@gmail.com',
    'Manish',
    'Sinha',
    null,
    null,
    '2013-07-31',
    0,
    1
);    

insert into ITEM VALUES
(
    800000,
    'Canon 7D',
    'Good condition.',
    25,
    2500,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-31 06:26:00'
);

insert into ITEM_PICTURE VALUES (800000,'7d-1.jpg',true);
insert into ITEM_PICTURE VALUES (800000,'7d-2.jpg',false);
insert into ITEM_PICTURE VALUES (800000,'7d-3.jpg',false);
insert into ITEM_PICTURE VALUES (800000,'7d-4.jpg',false);
insert into ITEM_PICTURE VALUES (800000,'7d-5.jpg',false);
insert into ITEM_PICTURE VALUES (800000,'7d-6.jpg',false);

insert into ITEM VALUES
(
    900000,
    'Canon 1D',
    'Good condition.',
    25,
    2500,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-30'
);

insert into ITEM_PICTURE VALUES (900000,'1d-1.jpg',true);
insert into ITEM_PICTURE VALUES (900000,'1d-2.jpg',false);
insert into ITEM_PICTURE VALUES (900000,'1d-3.jpg',false);

insert into ITEM VALUES
(
    500000,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (500000,'60d-1.jpg',true);
insert into ITEM_PICTURE VALUES (500000,'60d-2.jpg',false);
insert into ITEM_PICTURE VALUES (500000,'60d-3.jpg',false);

insert into TRANSACTION VALUES
(
    555555,
    800000,
    100001
);

insert into TRANSACTION VALUES
(
    655555,
    800000,
    100001
);

insert into TRANSACTION VALUES
(
    666666,
    900000,
    100001
);

insert into TRANSACTION VALUES
(
    766666,
    500000,
    100001
);

insert into DETAIL VALUES (555555, 0,'2013-07-14','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100000); -- put json here: start, end, msg
insert into DETAIL VALUES (555555, 1,'2013-07-15',null,100001); -- put json here for confirmation code
insert into DETAIL VALUES (555555, 3,'2013-07-18',null,100000); -- json here, bp hold uri
insert into DETAIL VALUES (555555, 7,'2013-07-21',null,100001);
insert into DETAIL VALUES (555555, 12,'2013-07-23','{"COMMENT":"Bad", "RATING" : 0}',100001);
insert into DETAIL VALUES (555555, 14,'2013-07-26','{"COMMENT":"Good", "RATING" : 1}',100000);

insert into DETAIL VALUES (655555, 0,'2013-07-11','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100000); -- put json here: start, end, msg
insert into DETAIL VALUES (655555, 1,'2013-07-12',null,100001); -- put json here for confirmation code
insert into DETAIL VALUES (655555, 3,'2013-07-13',null,100000); -- json here, bp hold uri
insert into DETAIL VALUES (655555, 7,'2013-07-24',null,100001);
insert into DETAIL VALUES (655555, 11,'2013-07-25','{"COMMENT":"Sweet", "RATING" : 1}',100000);
insert into DETAIL VALUES (655555, 13,'2013-07-27','{"COMMENT":"Sucked", "RATING" : 0}',100001);

insert into DETAIL VALUES (666666, 0,'2013-07-14','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100000); -- put json here: start, end, msg
insert into DETAIL VALUES (666666, 1,'2013-07-22',null,100001); -- put json here for confirmation code

insert into DETAIL VALUES (766666, 0,'2013-07-12','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code











insert into ITEM VALUES
(
    520000,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (520000,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    166666,
    520000,
    100001
);

insert into DETAIL VALUES (166666, 0,'2013-07-19','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code
insert into DETAIL VALUES (166666, 1,'2013-07-21',null,100001); -- put json here for confirmation code





insert into ITEM VALUES
(
    521000,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (521000,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    116666,
    521000,
    100001
);

insert into DETAIL VALUES (116666, 0,'2013-07-19','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code
insert into DETAIL VALUES (116666, 2,'2013-07-23',null,100001); -- put json here for confirmation code





insert into ITEM VALUES
(
    531000,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (531000,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    126666,
    531000,
    100001
);

insert into DETAIL VALUES (126666, 0,'2013-07-12','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code
insert into DETAIL VALUES (126666, 1,'2013-07-13',null,100001); -- put json here for confirmation code
insert into DETAIL VALUES (126666, 3,'2013-07-13',null,100000); -- json here, bp hold uri
insert into DETAIL VALUES (126666, 7,'2013-07-24',null,100001);
insert into DETAIL VALUES (126666, 12,'2013-07-24','{"COMMENT":"Sucked", "RATING" : 0}',100001);





insert into ITEM VALUES
(
    541000,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (541000,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    136666,
    541000,
    100001
);

insert into DETAIL VALUES (136666, 0,'2013-07-16','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code





insert into ITEM VALUES
(
    542000,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (542000,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    137666,
    542000,
    100001
);

insert into DETAIL VALUES (137666, 0,'2013-07-22','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code




insert into ITEM VALUES
(
    543000,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (543000,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    138666,
    543000,
    100001
);

insert into DETAIL VALUES (138666, 0,'2013-07-19','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code




insert into ITEM VALUES
(
    553000,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (553000,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    139666,
    553000,
    100001
);

insert into DETAIL VALUES (139666, 0,'2013-07-19','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code





insert into ITEM VALUES
(
    558100,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (558100,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    139166,
    558100,
    100001
);

insert into DETAIL VALUES (139166, 0,'2013-07-31','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code


insert into ITEM VALUES
(
    558200,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (558200,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    139266,
    558200,
    100001
);

insert into DETAIL VALUES (139266, 0,'2013-07-02','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code




insert into ITEM VALUES
(
    558300,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100000,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (558300,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    139366,
    558300,
    100001
);

insert into DETAIL VALUES (139366, 0,'2013-07-19','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100001); -- put json here for confirmation code
insert into DETAIL VALUES (139366, 1,'2013-07-25',null,100001); -- put json here for confirmation code
insert into DETAIL VALUES (139366, 3,'2013-07-31',null,100001); -- put json here for confirmation code


insert into ITEM VALUES
(
    598300,
    'Canon 60D',
    'Good condition.',
    35,
    2800,
    10001,
    'New York',
    'NY',
    100001,
    1,  
    '2013-07-26'
);

insert into ITEM_PICTURE VALUES (598300,'60d-1.jpg',true);

insert into TRANSACTION VALUES
(
    139377,
    598300,
    100000
);

insert into DETAIL VALUES (139377, 0,'2013-07-19 12:15:54','{"START_DATE":"08/01", "END_DATE" : "08/02", "MESSAGE" : "Hi I\d like to rent this for an upcoming gig."}',100000); -- put json here for confirmation code
insert into DETAIL VALUES (139377, 1,'2013-07-19 12:29:54',null,100000); -- put json here for confirmation code