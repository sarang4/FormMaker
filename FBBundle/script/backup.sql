-- MySQL dump 10.13  Distrib 5.1.63, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: symfony
-- ------------------------------------------------------
-- Server version	5.1.63-0ubuntu0.10.04.1

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
-- Table structure for table `account_to_form`
--

DROP TABLE IF EXISTS `account_to_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_to_form` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `account_id` int(11) NOT NULL DEFAULT '0',
  `deleted` int(5) DEFAULT '0',
  PRIMARY KEY (`form_id`,`account_id`),
  KEY `account_id` (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `checkbox_field`
--

DROP TABLE IF EXISTS `checkbox_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checkbox_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `label_id` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `disabled` varchar(10) DEFAULT NULL,
  `readonly` varchar(10) DEFAULT NULL,
  `checked` varchar(10) DEFAULT NULL,
  `group_id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`group_id`,`id`),
  KEY `label_id` (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `checkbox_field_data`
--

DROP TABLE IF EXISTS `checkbox_field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checkbox_field_data` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `group_id` varchar(50) NOT NULL DEFAULT '',
  `id` varchar(50) NOT NULL DEFAULT '',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `field_id` varchar(50) NOT NULL DEFAULT '',
  `value` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`group_id`,`id`,`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `checkboxgroup_field`
--

DROP TABLE IF EXISTS `checkboxgroup_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checkboxgroup_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `label_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `checkboxgroup_field_data`
--

DROP TABLE IF EXISTS `checkboxgroup_field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checkboxgroup_field_data` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `group_id` varchar(50) NOT NULL DEFAULT '',
  `id` varchar(50) NOT NULL DEFAULT '',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `element_id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`group_id`,`id`,`element_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `checkboxgroup_to_checkbox`
--

DROP TABLE IF EXISTS `checkboxgroup_to_checkbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checkboxgroup_to_checkbox` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `group_id` varchar(50) NOT NULL DEFAULT '',
  `element_id` varchar(50) NOT NULL DEFAULT '',
  `display_no` int(5) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`group_id`,`element_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fb_user`
--

DROP TABLE IF EXISTS `fb_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fb_user` (
  `name` varchar(50) DEFAULT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `type_of_user` int(11) DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subdomain_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subdomain_name` (`subdomain_name`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `file_field`
--

DROP TABLE IF EXISTS `file_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `maxlength` varchar(5) DEFAULT NULL,
  `size` varchar(5) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form`
--

DROP TABLE IF EXISTS `form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form` (
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `enctype` varchar(50) DEFAULT NULL,
  `method` varchar(15) DEFAULT NULL,
  `target` varchar(20) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `form_alignment` varchar(100) DEFAULT NULL,
  `deleted` int(5) DEFAULT '0',
  `thankyou_message` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1969 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form_to_element`
--

DROP TABLE IF EXISTS `form_to_element`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_to_element` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `element_id` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(50) DEFAULT NULL,
  `display_no` int(10) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`element_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `label_field`
--

DROP TABLE IF EXISTS `label_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `label_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `for_attr` varchar(50) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `hidden` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `number_field`
--

DROP TABLE IF EXISTS `number_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `number_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `label_id` varchar(50) DEFAULT NULL,
  `maxlength` varchar(5) DEFAULT NULL,
  `size` varchar(5) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`id`),
  KEY `label_id` (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `number_field_data`
--

DROP TABLE IF EXISTS `number_field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `number_field_data` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `field_id` varchar(50) NOT NULL DEFAULT '',
  `id` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`form_id`,`field_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `optgroup_field`
--

DROP TABLE IF EXISTS `optgroup_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `optgroup_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `disabled` varchar(10) DEFAULT NULL,
  `label` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `optgroup_to_option`
--

DROP TABLE IF EXISTS `optgroup_to_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `optgroup_to_option` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `optgroup_id` varchar(50) NOT NULL DEFAULT '',
  `option_id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`optgroup_id`,`option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `option_field`
--

DROP TABLE IF EXISTS `option_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `option_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `disabled` varchar(10) DEFAULT NULL,
  `selected` varchar(10) DEFAULT NULL,
  `select_id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`id`),
  KEY `select_id` (`select_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_field`
--

DROP TABLE IF EXISTS `password_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `label_id` varchar(50) DEFAULT NULL,
  `maxlength` varchar(5) DEFAULT NULL,
  `size` varchar(5) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `disabled` varchar(10) DEFAULT NULL,
  `readonly` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`id`),
  KEY `label_id` (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_field_data`
--

DROP TABLE IF EXISTS `password_field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_field_data` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `field_id` varchar(50) NOT NULL DEFAULT '',
  `id` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`form_id`,`field_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `radio_field`
--

DROP TABLE IF EXISTS `radio_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radio_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `label_id` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `disabled` varchar(10) DEFAULT NULL,
  `readonly` varchar(10) DEFAULT NULL,
  `checked` varchar(10) DEFAULT NULL,
  `group_id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`group_id`,`id`),
  KEY `label_id` (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `radioboxgroup_field`
--

DROP TABLE IF EXISTS `radioboxgroup_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radioboxgroup_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `label_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `radioboxgroup_field_data`
--

DROP TABLE IF EXISTS `radioboxgroup_field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radioboxgroup_field_data` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `field_id` varchar(50) NOT NULL DEFAULT '',
  `id` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`form_id`,`field_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `radioboxgroup_to_radiobox`
--

DROP TABLE IF EXISTS `radioboxgroup_to_radiobox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radioboxgroup_to_radiobox` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `group_id` varchar(50) NOT NULL DEFAULT '',
  `element_id` varchar(50) NOT NULL DEFAULT '',
  `display_no` int(5) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`group_id`,`element_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reset_field`
--

DROP TABLE IF EXISTS `reset_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reset_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `select_field`
--

DROP TABLE IF EXISTS `select_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `select_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `multiple` varchar(10) DEFAULT NULL,
  `label_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `select_field_data`
--

DROP TABLE IF EXISTS `select_field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `select_field_data` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `field_id` varchar(50) NOT NULL DEFAULT '',
  `id` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`form_id`,`field_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `select_to_element`
--

DROP TABLE IF EXISTS `select_to_element`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `select_to_element` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `select_id` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(50) DEFAULT NULL,
  `element_id` varchar(50) NOT NULL DEFAULT '',
  `display_no` int(5) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`select_id`,`element_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `submit_field`
--

DROP TABLE IF EXISTS `submit_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `submit_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `text_field`
--

DROP TABLE IF EXISTS `text_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `label_id` varchar(50) DEFAULT NULL,
  `maxlength` varchar(5) DEFAULT NULL,
  `size` varchar(5) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`form_id`,`id`),
  KEY `label_id` (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `text_field_data`
--

DROP TABLE IF EXISTS `text_field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_field_data` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `field_id` varchar(50) NOT NULL DEFAULT '',
  `id` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`form_id`,`field_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `textarea_field`
--

DROP TABLE IF EXISTS `textarea_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `textarea_field` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) DEFAULT NULL,
  `label_id` varchar(50) DEFAULT NULL,
  `hidden` varchar(10) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` varchar(50) NOT NULL DEFAULT '',
  `disabled` varchar(10) DEFAULT NULL,
  `readonly` varchar(10) DEFAULT NULL,
  `cols` varchar(5) DEFAULT NULL,
  `rows` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`form_id`,`id`),
  KEY `label_id` (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `textarea_field_data`
--

DROP TABLE IF EXISTS `textarea_field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `textarea_field_data` (
  `form_id` varchar(50) NOT NULL DEFAULT '',
  `field_id` varchar(50) NOT NULL DEFAULT '',
  `id` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(2000) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`form_id`,`field_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-10-06 21:09:12
