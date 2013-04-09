
insert into USER (FIRST_NAME, LAST_NAME, EMAIL_ADDRESS,PASSWORD,PHONE_NUMBER,PROFILE_PICTURE_FILENAME) VALUES ('Ron', 'Burgundy', 'ron@qhojo.com','ron','+13475594954','ron.jpg');
insert into USER (FIRST_NAME, LAST_NAME, EMAIL_ADDRESS,PASSWORD,PHONE_NUMBER,PROFILE_PICTURE_FILENAME) VALUES ('Brian', 'Fantana', 'brian@qhojo.com','brian','+19492160925','brian.jpg');
insert into USER (FIRST_NAME, LAST_NAME, EMAIL_ADDRESS,PASSWORD,PHONE_NUMBER,PROFILE_PICTURE_FILENAME) VALUES ('Brick', 'Tamland', 'brick@qhojo.com','brick','+19492160925','brick.jpg');
insert into USER (FIRST_NAME, LAST_NAME, EMAIL_ADDRESS,PASSWORD,PHONE_NUMBER,PROFILE_PICTURE_FILENAME) VALUES ('Veronica', 'Corningstone', 'veronica@qhojo.com','veronica','+13475594954','veronica.jpg');

insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID, START_DATE, END_DATE) 
VALUES ('Microphone', 'In good condition. Used a couple times for newcasts.', 10,100,2,1,1,'2012-10-31','2012-11-02');
insert into ITEM_REQUESTS (REQUEST_ID,ITEM_ID,REQUESTER_ID,DURATION,MESSAGE,ACCEPTED_FLAG)
VALUES (3, 1, 4, 2, 'sdfsdf', 1);


insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID, START_DATE, END_DATE) 
VALUES ('Gray Suit', 'In good condition. Used a couple times for newcasts.', 10,100,3,3,1,'2012-10-31','2012-11-02');
insert into ITEM_REQUESTS (REQUEST_ID,ITEM_ID,REQUESTER_ID,DURATION,MESSAGE,ACCEPTED_FLAG)
VALUES (4, 2, 2, 2, 'sdfsdf', 1);



insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID) 
VALUES ('Vacuum Cleaner', 'Dyson brand.', 5,150,4,0,2);
insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID) 
VALUES ('Wilson Tennis Racquet', 'V-Matrix Technology with V-Lock Bridge has a new concave frame geometry broadens the racket sweet spot for increased power on off-centered hits. It consists of stop shock sleeves, power String for increased power and enlarged head for greater power.',
15,100,1,0,3);
insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID) 
VALUES ('Coleman Montana Tent', 'Ideal for outdoorsy families and extended camping trips, the Coleman Montana 8 Tent offers a full feature set for a fun family camping experience. It sleeps up to eight comfortably, thanks to a generous 16-by-seven-foot (W x D) layout and spacious center height of six feet, two inches.',
20,120,3,0,3);
insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID) 
VALUES ('Sigma 10-22 Wide angle Lens', 'Sigma Corporation is pleased to announce the new Sigma 10-20mm F3.5 EX DC HSM. This super-wide angle lens has a maximum aperture of F3.5 throughout the entire zoom range. With its wide angle view from 102.4 degrees it can produce striking images with exaggerated perspective. The maximum aperture of F3.5 is ideal for indoor shooting and it enables photographers to emphasize the subject. Two ELD (Extraordinary Low Dispersion) glass elements and a SLD (Special Low Dispersion) glass element provide excellent correction of color aberration. Four aspherical lenses provide correction for distortion and allow compact and lightweight construction. The Super Multi-Layer coating reduces flare and ghosting. High image quality is assured throughout the entire zoom range. The incorporation of HSM (Hyper-Sonic Motor) ensures a quiet and high-speed auto focus as well as full-time manual focusing capability. This lens has a minimum focusing distance of 9.4 inches (24cm) throughout the entire zoom range and a maximum magnification ratio of 1:6.6. The lens design incorporates an inner focusing system which eliminates front lens rotation, making the lens particularly suitable for using the Petal-type hood and polarizing filter. The Petal-type hood blocks extraneous light and reduces internal reflection.',
20,300,2,0,1);
insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID) 
VALUES ('Diamondback Bike', 'The bike industry often sees fads come and go. Innovation doesn\'t always assure success or even relevance for that matter. But the 29er revolution seems to be one that\'s here to stay and for good reason.

There\'s no denying the rock crawling, root crushing power of a larger wheel. Making the largest of obstacles the smallest of matters is a defining trait of these muddy beasts. By offering decreased rolling resistance, increased traction when cornering and improved ground clearance, one ride upon one of these massive behemoths is sure to sway even the staunchest skeptic.',
30,380,1,0,1);
insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID) 
VALUES ('KL Industries Canoe', 'Get more from your canoeing expeditions with the WaterQuest 156 Canoe from KL Industries, which steers and maneuvers like a regular canoe but also includes a square back for adding an optional electric trolling motor. It has three seats and up to an 800-pound carrying capacity. It includes a built-in cooler located under the center seat, and a dry storage holds wallets, sunglasses, cell phones, cameras and more. Other features include a rugged UV-stabilized Fortiflex polyethylene hull, drink holders molded into every seat, and a protective rub rail. It\'s backed by a 2-year warranty on the hull and parts. Made in USA.',
10,500,3,0,4);
insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,LOCATION_ID,STATE_ID,LENDER_ID) 
VALUES ('Tomtom GPS Navigator', 'The TomTom START 50M 5" Vehicle GPS with Free Lifetime Map Updates is here to ensure your journey is smooth from start to finish. It includes the TomTom Maps with IQ Routes and Map Share technology, which keeps you informed with dynamic road changes on a daily basis. Enjoy easy driving with Free Lifetime Map Updates. For the life of your product you can download four or more full map updates on your device every year. You receive updates to the road network, addresses and Points of Interest, to ensure that you always have the industry\'s most up-to-date map. Never miss your exit or turn with Advanced Lane Guidance. It shows you exactly which lane to take before you approach an exit, turn or difficult intersection so you can stay on the right path. Choose from over 7 million Points of Interest in over 60 categories and find the nearest restaurants, hotels, shops and more wherever you go. TomTom START 50M 5" Vehicle GPS with Free Lifetime Map Updates: 5" screen 7 million POIs and IQ Routes Maps cover the U.S. Map Share technology Download 4 or more free full map updates every year Advanced Lane Guidance shows you which lane to take Helps find the lowest fuel prices in the area Roadside Assistance connects you to a specialist wherever and whenever you need.',
5,80,3,0,3);

insert into ITEM_PICTURES VALUES (1,'microphone.jpg',true);
insert into ITEM_PICTURES VALUES (2,'gray_suit.jpg',true);
insert into ITEM_PICTURES VALUES (3,'dyson.jpg',true);
insert into ITEM_PICTURES VALUES (4,'racquet.jpg',true);
insert into ITEM_PICTURES VALUES (5,'tent.jpg',true);
insert into ITEM_PICTURES VALUES (6,'lens.jpg',true);
insert into ITEM_PICTURES VALUES (7,'bike.jpg',true);
insert into ITEM_PICTURES VALUES (8,'canoe.jpg',true);
insert into ITEM_PICTURES VALUES (9,'tomtom.jpg',true);