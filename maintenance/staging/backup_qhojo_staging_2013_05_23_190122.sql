-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: qhojo_staging
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.12.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `BOROUGH`
--

DROP TABLE IF EXISTS `BOROUGH`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BOROUGH` (
  `ID` int(11) NOT NULL,
  `SHORT_NAME` varchar(80) DEFAULT NULL,
  `FULL_NAME` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BOROUGH`
--

LOCK TABLES `BOROUGH` WRITE;
/*!40000 ALTER TABLE `BOROUGH` DISABLE KEYS */;
INSERT INTO `BOROUGH` VALUES (0,'mh','manhattan'),(1,'bk','brooklyn');
/*!40000 ALTER TABLE `BOROUGH` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ITEM`
--

DROP TABLE IF EXISTS `ITEM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ITEM` (
  `ID` int(11) NOT NULL,
  `TITLE` varchar(80) DEFAULT NULL,
  `DESCRIPTION` text,
  `RATE` float DEFAULT NULL,
  `DEPOSIT` float DEFAULT NULL,
  `LOCATION_ID` int(11) DEFAULT NULL,
  `STATE_ID` int(11) DEFAULT NULL,
  `LENDER_ID` int(11) DEFAULT NULL,
  `START_DATE` datetime DEFAULT NULL,
  `END_DATE` datetime DEFAULT NULL,
  `LENDER_TO_BORROWER_STARS` int(11) DEFAULT NULL,
  `BORROWER_TO_LENDER_STARS` int(11) DEFAULT NULL,
  `LENDER_TO_BORROWER_COMMENTS` text,
  `BORROWER_TO_LENDER_COMMENTS` text,
  `CONFIRMATION_CODE` int(11) DEFAULT NULL,
  `ACTIVE_FLAG` int(11) DEFAULT NULL,
  `CREATE_DATE` datetime DEFAULT NULL,
  `BORROWER_BP_HOLD_URI` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`),
  KEY `LENDER_ID` (`LENDER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ITEM`
--

LOCK TABLES `ITEM` WRITE;
/*!40000 ALTER TABLE `ITEM` DISABLE KEYS */;
INSERT INTO `ITEM` VALUES (200001,'Microphone','In good condition. Used a couple times for newcasts.',10,100,2,1,100001,'2012-10-31 00:00:00','2012-11-02 00:00:00',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL),(200002,'Gray Suit','In good condition. Used a couple times for newcasts.',10,100,3,3,100001,'2012-10-31 00:00:00','2012-11-02 00:00:00',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL),(200003,'Vacuum Cleaner','Dyson brand.',5,150,0,0,100002,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL),(200004,'Wilson Tennis Racquet','V-Matrix Technology with V-Lock Bridge has a new concave frame geometry broadens the racket sweet spot for increased power on off-centered hits. It consists of stop shock sleeves, power String for increased power and enlarged head for greater power.',15,100,1,0,100003,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL),(200005,'Coleman Montana Tent','Ideal for outdoorsy families and extended camping trips, the Coleman Montana 8 Tent offers a full feature set for a fun family camping experience. It sleeps up to eight comfortably, thanks to a generous 16-by-seven-foot (W x D) layout and spacious center height of six feet, two inches.',20,120,3,0,100003,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL),(200006,'Sigma 10-22 Wide angle Lens','Sigma Corporation is pleased to announce the new Sigma 10-20mm F3.5 EX DC HSM. This super-wide angle lens has a maximum aperture of F3.5 throughout the entire zoom range. With its wide angle view from 102.4 degrees it can produce striking images with exaggerated perspective. The maximum aperture of F3.5 is ideal for indoor shooting and it enables photographers to emphasize the subject. Two ELD (Extraordinary Low Dispersion) glass elements and a SLD (Special Low Dispersion) glass element provide excellent correction of color aberration. Four aspherical lenses provide correction for distortion and allow compact and lightweight construction. The Super Multi-Layer coating reduces flare and ghosting. High image quality is assured throughout the entire zoom range. The incorporation of HSM (Hyper-Sonic Motor) ensures a quiet and high-speed auto focus as well as full-time manual focusing capability. This lens has a minimum focusing distance of 9.4 inches (24cm) throughout the entire zoom range and a maximum magnification ratio of 1:6.6. The lens design incorporates an inner focusing system which eliminates front lens rotation, making the lens particularly suitable for using the Petal-type hood and polarizing filter. The Petal-type hood blocks extraneous light and reduces internal reflection.',20,300,2,0,100001,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL),(200007,'Diamondback Bike','The bike industry often sees fads come and go. Innovation doesn\'t always assure success or even relevance for that matter. But the 29er revolution seems to be one that\'s here to stay and for good reason.\n\nThere\'s no denying the rock crawling, root crushing power of a larger wheel. Making the largest of obstacles the smallest of matters is a defining trait of these muddy beasts. By offering decreased rolling resistance, increased traction when cornering and improved ground clearance, one ride upon one of these massive behemoths is sure to sway even the staunchest skeptic.',30,380,1,0,100001,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL),(200008,'KL Industries Canoe','Get more from your canoeing expeditions with the WaterQuest 156 Canoe from KL Industries, which steers and maneuvers like a regular canoe but also includes a square back for adding an optional electric trolling motor. It has three seats and up to an 800-pound carrying capacity. It includes a built-in cooler located under the center seat, and a dry storage holds wallets, sunglasses, cell phones, cameras and more. Other features include a rugged UV-stabilized Fortiflex polyethylene hull, drink holders molded into every seat, and a protective rub rail. It\'s backed by a 2-year warranty on the hull and parts. Made in USA.',10,500,3,0,100004,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL),(200009,'Tomtom GPS Navigator','The TomTom START 50M 5\" Vehicle GPS with Free Lifetime Map Updates is here to ensure your journey is smooth from start to finish. It includes the TomTom Maps with IQ Routes and Map Share technology, which keeps you informed with dynamic road changes on a daily basis. Enjoy easy driving with Free Lifetime Map Updates. For the life of your product you can download four or more full map updates on your device every year. You receive updates to the road network, addresses and Points of Interest, to ensure that you always have the industry\'s most up-to-date map. Never miss your exit or turn with Advanced Lane Guidance. It shows you exactly which lane to take before you approach an exit, turn or difficult intersection so you can stay on the right path. Choose from over 7 million Points of Interest in over 60 categories and find the nearest restaurants, hotels, shops and more wherever you go. TomTom START 50M 5\" Vehicle GPS with Free Lifetime Map Updates: 5\" screen 7 million POIs and IQ Routes Maps cover the U.S. Map Share technology Download 4 or more free full map updates every year Advanced Lane Guidance shows you which lane to take Helps find the lowest fuel prices in the area Roadside Assistance connects you to a specialist wherever and whenever you need.',5,80,3,0,100003,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `ITEM` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ITEM_PICTURES`
--

DROP TABLE IF EXISTS `ITEM_PICTURES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ITEM_PICTURES` (
  `ITEM_ID` int(11) DEFAULT NULL,
  `FILENAME` varchar(80) DEFAULT NULL,
  `PRIMARY_FLAG` tinyint(1) DEFAULT NULL,
  KEY `ITEM_ID` (`ITEM_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ITEM_PICTURES`
--

LOCK TABLES `ITEM_PICTURES` WRITE;
/*!40000 ALTER TABLE `ITEM_PICTURES` DISABLE KEYS */;
INSERT INTO `ITEM_PICTURES` VALUES (200001,'microphone.jpg',1),(200002,'gray_suit.jpg',1),(200003,'dyson.jpg',1),(200004,'racquet.jpg',1),(200005,'tent.jpg',1),(200006,'lens.jpg',1),(200007,'bike.jpg',1),(200008,'canoe.jpg',1),(200009,'tomtom.jpg',1);
/*!40000 ALTER TABLE `ITEM_PICTURES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ITEM_REQUESTS`
--

DROP TABLE IF EXISTS `ITEM_REQUESTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ITEM_REQUESTS` (
  `REQUEST_ID` int(11) NOT NULL,
  `ITEM_ID` int(11) DEFAULT NULL,
  `REQUESTER_ID` int(11) DEFAULT NULL,
  `DURATION` int(11) DEFAULT NULL,
  `MESSAGE` text,
  `ACCEPTED_FLAG` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`REQUEST_ID`),
  KEY `ITEM_ID` (`ITEM_ID`),
  KEY `REQUESTER_ID` (`REQUESTER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ITEM_REQUESTS`
--

LOCK TABLES `ITEM_REQUESTS` WRITE;
/*!40000 ALTER TABLE `ITEM_REQUESTS` DISABLE KEYS */;
INSERT INTO `ITEM_REQUESTS` VALUES (3,200001,100004,2,'sdfsdf',1),(4,200002,100002,2,'sdfsdf',1);
/*!40000 ALTER TABLE `ITEM_REQUESTS` ENABLE KEYS */;
UNLOCK TABLES;
