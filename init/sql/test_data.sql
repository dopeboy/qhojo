use qhojo;

insert into USER (FIRST_NAME, LAST_NAME, EMAIL_ADDRESS,PASSWORD,PHONE_NUMBER,PROFILE_PICTURE_FILENAME) VALUES ('Ron', 'Burgundy', 'ron','ron','9495214954','ron.jpg');
insert into USER (FIRST_NAME, LAST_NAME, EMAIL_ADDRESS,PASSWORD,PHONE_NUMBER,PROFILE_PICTURE_FILENAME) VALUES ('Brian', 'Fantana', 'brian','brian','9495214954','brian.jpg');

insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID) 
VALUES ('Sex Panther', '60% of the time, it works all the time.', 1.99,15,1,0,1);
insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID, BORROWER_ID, BORROW_DURATION, START_DATE, END_DATE) 
VALUES ('Microphone', 'In good condition. Used a couple times for newcasts.', 10,100,2,1,1,2,2,'2012-10-31','2012-11-02');
insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID, BORROWER_ID, BORROW_DURATION, START_DATE, END_DATE) 
VALUES ('Gray Suit', 'In good condition. Used a couple times for newcasts.', 10,100,3,3,1,2,2,'2012-10-31','2012-11-02');
insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID) 
VALUES ('Vacuum Cleaner', 'Dyson brand.', 5,150,4,0,2);

insert into ITEM_PICTURES VALUES (1,'panther.jpg',true);
insert into ITEM_PICTURES VALUES (2,'microphone.jpg',true);
insert into ITEM_PICTURES VALUES (3,'gray_suit.jpg',true);
insert into ITEM_PICTURES VALUES (4,'dyson.jpg',true);
insert into ITEM_PICTURES VALUES (4,'microphone.jpg',false);