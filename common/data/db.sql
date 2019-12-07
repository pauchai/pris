-- MySQL dump 10.13  Distrib 5.7.11, for Linux (i686)
--
-- Host: localhost    Database: yii2_start_vova07
-- ------------------------------------------------------
-- Server version	5.7.11-0ubuntu6

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
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'MODEL_ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'COUNTRY_TITLE',
  `iso` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'COUNTRY_ISO',
  PRIMARY KEY (`id`),
  KEY `title_idx` (`title`),
  KEY `iso_idx` (`iso`)
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (1,'Andorra','AD'),(2,'United Arab Emirates','AE'),(3,'Afghanistan','AF'),(4,'Antigua and Barbuda','AG'),(5,'Anguilla','AI'),(6,'Albania','AL'),(7,'Armenia','AM'),(8,'Angola','AO'),(9,'Antarctica','AQ'),(10,'Argentina','AR'),(11,'American Samoa','AS'),(12,'Austria','AT'),(13,'Australia','AU'),(14,'Aruba','AW'),(15,'Aland Islands','AX'),(16,'Azerbaijan','AZ'),(17,'Bosnia and Herzegovina','BA'),(18,'Barbados','BB'),(19,'Bangladesh','BD'),(20,'Belgium','BE'),(21,'Burkina Faso','BF'),(22,'Bulgaria','BG'),(23,'Bahrain','BH'),(24,'Burundi','BI'),(25,'Benin','BJ'),(26,'Saint Barthelemy','BL'),(27,'Bermuda','BM'),(28,'Brunei','BN'),(29,'Bolivia','BO'),(30,'Bonaire, Saint Eustatius and Saba ','BQ'),(31,'Brazil','BR'),(32,'Bahamas','BS'),(33,'Bhutan','BT'),(34,'Bouvet Island','BV'),(35,'Botswana','BW'),(36,'Belarus','BY'),(37,'Belize','BZ'),(38,'Canada','CA'),(39,'Cocos Islands','CC'),(40,'Democratic Republic of the Congo','CD'),(41,'Central African Republic','CF'),(42,'Republic of the Congo','CG'),(43,'Switzerland','CH'),(44,'Ivory Coast','CI'),(45,'Cook Islands','CK'),(46,'Chile','CL'),(47,'Cameroon','CM'),(48,'China','CN'),(49,'Colombia','CO'),(50,'Costa Rica','CR'),(51,'Cuba','CU'),(52,'Cape Verde','CV'),(53,'Curacao','CW'),(54,'Christmas Island','CX'),(55,'Cyprus','CY'),(56,'Czech Republic','CZ'),(57,'Germany','DE'),(58,'Djibouti','DJ'),(59,'Denmark','DK'),(60,'Dominica','DM'),(61,'Dominican Republic','DO'),(62,'Algeria','DZ'),(63,'Ecuador','EC'),(64,'Estonia','EE'),(65,'Egypt','EG'),(66,'Western Sahara','EH'),(67,'Eritrea','ER'),(68,'Spain','ES'),(69,'Ethiopia','ET'),(70,'Finland','FI'),(71,'Fiji','FJ'),(72,'Falkland Islands','FK'),(73,'Micronesia','FM'),(74,'Faroe Islands','FO'),(75,'France','FR'),(76,'Gabon','GA'),(77,'United Kingdom','GB'),(78,'Grenada','GD'),(79,'Georgia','GE'),(80,'French Guiana','GF'),(81,'Guernsey','GG'),(82,'Ghana','GH'),(83,'Gibraltar','GI'),(84,'Greenland','GL'),(85,'Gambia','GM'),(86,'Guinea','GN'),(87,'Guadeloupe','GP'),(88,'Equatorial Guinea','GQ'),(89,'Greece','GR'),(90,'South Georgia and the South Sandwich Islands','GS'),(91,'Guatemala','GT'),(92,'Guam','GU'),(93,'Guinea-Bissau','GW'),(94,'Guyana','GY'),(95,'Hong Kong','HK'),(96,'Heard Island and McDonald Islands','HM'),(97,'Honduras','HN'),(98,'Croatia','HR'),(99,'Haiti','HT'),(100,'Hungary','HU'),(101,'Indonesia','ID'),(102,'Ireland','IE'),(103,'Israel','IL'),(104,'Isle of Man','IM'),(105,'India','IN'),(106,'British Indian Ocean Territory','IO'),(107,'Iraq','IQ'),(108,'Iran','IR'),(109,'Iceland','IS'),(110,'Italy','IT'),(111,'Jersey','JE'),(112,'Jamaica','JM'),(113,'Jordan','JO'),(114,'Japan','JP'),(115,'Kenya','KE'),(116,'Kyrgyzstan','KG'),(117,'Cambodia','KH'),(118,'Kiribati','KI'),(119,'Comoros','KM'),(120,'Saint Kitts and Nevis','KN'),(121,'North Korea','KP'),(122,'South Korea','KR'),(123,'Kosovo','XK'),(124,'Kuwait','KW'),(125,'Cayman Islands','KY'),(126,'Kazakhstan','KZ'),(127,'Laos','LA'),(128,'Lebanon','LB'),(129,'Saint Lucia','LC'),(130,'Liechtenstein','LI'),(131,'Sri Lanka','LK'),(132,'Liberia','LR'),(133,'Lesotho','LS'),(134,'Lithuania','LT'),(135,'Luxembourg','LU'),(136,'Latvia','LV'),(137,'Libya','LY'),(138,'Morocco','MA'),(139,'Monaco','MC'),(140,'Moldova','MD'),(141,'Montenegro','ME'),(142,'Saint Martin','MF'),(143,'Madagascar','MG'),(144,'Marshall Islands','MH'),(145,'Macedonia','MK'),(146,'Mali','ML'),(147,'Myanmar','MM'),(148,'Mongolia','MN'),(149,'Macao','MO'),(150,'Northern Mariana Islands','MP'),(151,'Martinique','MQ'),(152,'Mauritania','MR'),(153,'Montserrat','MS'),(154,'Malta','MT'),(155,'Mauritius','MU'),(156,'Maldives','MV'),(157,'Malawi','MW'),(158,'Mexico','MX'),(159,'Malaysia','MY'),(160,'Mozambique','MZ'),(161,'Namibia','NA'),(162,'New Caledonia','NC'),(163,'Niger','NE'),(164,'Norfolk Island','NF'),(165,'Nigeria','NG'),(166,'Nicaragua','NI'),(167,'Netherlands','NL'),(168,'Norway','NO'),(169,'Nepal','NP'),(170,'Nauru','NR'),(171,'Niue','NU'),(172,'New Zealand','NZ'),(173,'Oman','OM'),(174,'Panama','PA'),(175,'Peru','PE'),(176,'French Polynesia','PF'),(177,'Papua New Guinea','PG'),(178,'Philippines','PH'),(179,'Pakistan','PK'),(180,'Poland','PL'),(181,'Saint Pierre and Miquelon','PM'),(182,'Pitcairn','PN'),(183,'Puerto Rico','PR'),(184,'Palestinian Territory','PS'),(185,'Portugal','PT'),(186,'Palau','PW'),(187,'Paraguay','PY'),(188,'Qatar','QA'),(189,'Reunion','RE'),(190,'Romania','RO'),(191,'Serbia','RS'),(192,'Russia','RU'),(193,'Rwanda','RW'),(194,'Saudi Arabia','SA'),(195,'Solomon Islands','SB'),(196,'Seychelles','SC'),(197,'Sudan','SD'),(198,'South Sudan','SS'),(199,'Sweden','SE'),(200,'Singapore','SG'),(201,'Saint Helena','SH'),(202,'Slovenia','SI'),(203,'Svalbard and Jan Mayen','SJ'),(204,'Slovakia','SK'),(205,'Sierra Leone','SL'),(206,'San Marino','SM'),(207,'Senegal','SN'),(208,'Somalia','SO'),(209,'Suriname','SR'),(210,'Sao Tome and Principe','ST'),(211,'El Salvador','SV'),(212,'Sint Maarten','SX'),(213,'Syria','SY'),(214,'Swaziland','SZ'),(215,'Turks and Caicos Islands','TC'),(216,'Chad','TD'),(217,'French Southern Territories','TF'),(218,'Togo','TG'),(219,'Thailand','TH'),(220,'Tajikistan','TJ'),(221,'Tokelau','TK'),(222,'East Timor','TL'),(223,'Turkmenistan','TM'),(224,'Tunisia','TN'),(225,'Tonga','TO'),(226,'Turkey','TR'),(227,'Trinidad and Tobago','TT'),(228,'Tuvalu','TV'),(229,'Taiwan','TW'),(230,'Tanzania','TZ'),(231,'Ukraine','UA'),(232,'Uganda','UG'),(233,'United States Minor Outlying Islands','UM'),(234,'United States','US'),(235,'Uruguay','UY'),(236,'Uzbekistan','UZ'),(237,'Vatican','VA'),(238,'Saint Vincent and the Grenadines','VC'),(239,'Venezuela','VE'),(240,'British Virgin Islands','VG'),(241,'U.S. Virgin Islands','VI'),(242,'Vietnam','VN'),(243,'Vanuatu','VU'),(244,'Wallis and Futuna','WF'),(245,'Samoa','WS'),(246,'Yemen','YE'),(247,'Mayotte','YT'),(248,'South Africa','ZA'),(249,'Zambia','ZM'),(250,'Zimbabwe','ZW'),(251,'Serbia and Montenegro','CS'),(252,'Netherlands Antilles','AN');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-07-10 15:41:56

