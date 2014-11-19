/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50530
Source Host           : 127.0.0.1:3306
Source Database       : faktury

Target Server Type    : MYSQL
Target Server Version : 50530
File Encoding         : 65001

Date: 2014-11-19 05:25:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `subjects`
-- ----------------------------
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ico` varchar(255) DEFAULT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `vat_pay` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `person` varchar(255) DEFAULT NULL,
  `created` varchar(255) DEFAULT NULL,
  `active` varchar(255) DEFAULT NULL,
  `file_number` varchar(255) DEFAULT NULL,
  `court` varchar(255) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of subjects
-- ----------------------------
INSERT INTO `subjects` VALUES ('1', '45274649', 'CZ45274649', '1', 'Praha', 'ČEZ, a. s.', 'Duhová 1444/2', '14000', '0', '1992-05-06T00:00:00+0200', '1', 'B 1581', 'Městský soud v Praze', '61', null, null, null, null);
INSERT INTO `subjects` VALUES ('8', '03523578', '', '', 'Česká Lípa', 'Tomáš Kolinger', 'Liberecká 3210', '47001', '1', '2014-10-29T00:00:00+0100', null, null, null, '61', null, null, null, null);
INSERT INTO `subjects` VALUES ('13', '123456', 'sadfsaf', null, 'sadf', 'asdfasf', 'asdfdsaf', 'asdfsadf', null, null, null, null, 'sadfsaf', '61', null, 'http://www.asdsad.de', 'visionsek9@yahoo.com', 'asdfsadf');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) DEFAULT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('61', 'heyduk2@seznam.cz', '$2y$10$FG0aAe/lA7XuW51eBFZc1.Hc0c/ZnN2QPfoTRN.3i8IMBeL9tjOt2', 'admin', 'asdasd', 'asdasd');
