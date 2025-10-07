/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 80043
Source Host           : 127.0.0.1:3306
Source Database       : pdpa_system

Target Server Type    : MYSQL
Target Server Version : 80043
File Encoding         : 65001

Date: 2025-10-07 10:08:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for answers
-- ----------------------------
DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `assessment_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer_value` int NOT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `assessment_id` (`assessment_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=634 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of answers
-- ----------------------------
INSERT INTO `answers` VALUES ('67', '13', '129', '0', '');
INSERT INTO `answers` VALUES ('68', '13', '130', '0', '');
INSERT INTO `answers` VALUES ('69', '13', '131', '0', '');
INSERT INTO `answers` VALUES ('70', '13', '132', '0', '');
INSERT INTO `answers` VALUES ('71', '13', '133', '0', '');
INSERT INTO `answers` VALUES ('72', '13', '134', '0', '');
INSERT INTO `answers` VALUES ('73', '13', '135', '0', '');
INSERT INTO `answers` VALUES ('74', '13', '136', '0', '');
INSERT INTO `answers` VALUES ('75', '13', '137', '0', '');
INSERT INTO `answers` VALUES ('76', '13', '138', '0', '');
INSERT INTO `answers` VALUES ('77', '13', '139', '0', '');
INSERT INTO `answers` VALUES ('78', '13', '140', '0', '');
INSERT INTO `answers` VALUES ('79', '13', '142', '0', '');
INSERT INTO `answers` VALUES ('80', '13', '143', '0', '');
INSERT INTO `answers` VALUES ('81', '13', '144', '0', '');
INSERT INTO `answers` VALUES ('82', '13', '145', '0', '');
INSERT INTO `answers` VALUES ('83', '13', '146', '0', '');
INSERT INTO `answers` VALUES ('84', '13', '147', '0', '');
INSERT INTO `answers` VALUES ('85', '13', '148', '0', '');
INSERT INTO `answers` VALUES ('86', '13', '149', '0', '');
INSERT INTO `answers` VALUES ('87', '13', '150', '0', '');
INSERT INTO `answers` VALUES ('88', '13', '151', '0', '');
INSERT INTO `answers` VALUES ('89', '13', '152', '0', '');
INSERT INTO `answers` VALUES ('90', '13', '153', '0', '');
INSERT INTO `answers` VALUES ('91', '13', '154', '0', '');
INSERT INTO `answers` VALUES ('92', '13', '155', '0', '');
INSERT INTO `answers` VALUES ('93', '13', '156', '0', '');
INSERT INTO `answers` VALUES ('94', '13', '157', '0', '');
INSERT INTO `answers` VALUES ('95', '13', '158', '0', '');
INSERT INTO `answers` VALUES ('96', '13', '159', '0', '');
INSERT INTO `answers` VALUES ('97', '13', '160', '0', '');
INSERT INTO `answers` VALUES ('98', '13', '161', '0', '');
INSERT INTO `answers` VALUES ('99', '13', '162', '0', '');
INSERT INTO `answers` VALUES ('100', '13', '163', '0', '');
INSERT INTO `answers` VALUES ('101', '13', '164', '0', '');
INSERT INTO `answers` VALUES ('102', '13', '165', '0', '');
INSERT INTO `answers` VALUES ('103', '13', '166', '0', '');
INSERT INTO `answers` VALUES ('104', '13', '167', '0', '');
INSERT INTO `answers` VALUES ('105', '13', '168', '0', '');
INSERT INTO `answers` VALUES ('106', '13', '169', '0', '');
INSERT INTO `answers` VALUES ('107', '13', '170', '0', '');
INSERT INTO `answers` VALUES ('108', '13', '171', '0', '');
INSERT INTO `answers` VALUES ('109', '13', '172', '0', '');
INSERT INTO `answers` VALUES ('110', '13', '173', '0', '');
INSERT INTO `answers` VALUES ('111', '13', '174', '0', '');
INSERT INTO `answers` VALUES ('112', '13', '175', '0', '');
INSERT INTO `answers` VALUES ('113', '13', '176', '0', '');
INSERT INTO `answers` VALUES ('114', '13', '177', '0', '');
INSERT INTO `answers` VALUES ('115', '13', '178', '0', '');
INSERT INTO `answers` VALUES ('116', '13', '179', '0', '');
INSERT INTO `answers` VALUES ('117', '13', '180', '0', '');
INSERT INTO `answers` VALUES ('118', '13', '181', '0', '');
INSERT INTO `answers` VALUES ('119', '13', '182', '0', '');
INSERT INTO `answers` VALUES ('120', '13', '183', '0', '');
INSERT INTO `answers` VALUES ('121', '13', '184', '0', '');
INSERT INTO `answers` VALUES ('122', '13', '185', '0', '');
INSERT INTO `answers` VALUES ('123', '13', '186', '0', '');
INSERT INTO `answers` VALUES ('124', '13', '187', '0', '');
INSERT INTO `answers` VALUES ('125', '13', '188', '0', '');
INSERT INTO `answers` VALUES ('126', '13', '189', '0', '');
INSERT INTO `answers` VALUES ('127', '13', '190', '0', '');
INSERT INTO `answers` VALUES ('128', '13', '191', '0', '');
INSERT INTO `answers` VALUES ('129', '13', '192', '0', '');
INSERT INTO `answers` VALUES ('130', '13', '193', '0', '');
INSERT INTO `answers` VALUES ('131', '13', '194', '0', '');
INSERT INTO `answers` VALUES ('132', '13', '195', '0', '');
INSERT INTO `answers` VALUES ('133', '13', '196', '0', '');
INSERT INTO `answers` VALUES ('134', '13', '197', '0', '');
INSERT INTO `answers` VALUES ('135', '13', '198', '0', '');
INSERT INTO `answers` VALUES ('136', '13', '199', '0', '');
INSERT INTO `answers` VALUES ('137', '13', '200', '0', '');
INSERT INTO `answers` VALUES ('138', '13', '201', '0', '');
INSERT INTO `answers` VALUES ('139', '13', '202', '0', '');
INSERT INTO `answers` VALUES ('140', '13', '203', '0', '');
INSERT INTO `answers` VALUES ('141', '13', '204', '0', '');
INSERT INTO `answers` VALUES ('142', '13', '205', '0', '');
INSERT INTO `answers` VALUES ('143', '13', '206', '0', '');
INSERT INTO `answers` VALUES ('144', '13', '207', '0', '');
INSERT INTO `answers` VALUES ('145', '13', '208', '0', '');
INSERT INTO `answers` VALUES ('146', '13', '209', '0', '');
INSERT INTO `answers` VALUES ('147', '13', '210', '0', '');
INSERT INTO `answers` VALUES ('148', '13', '211', '0', '');
INSERT INTO `answers` VALUES ('149', '13', '212', '0', '');
INSERT INTO `answers` VALUES ('150', '13', '213', '0', '');
INSERT INTO `answers` VALUES ('151', '13', '214', '0', '');
INSERT INTO `answers` VALUES ('152', '13', '215', '0', '');
INSERT INTO `answers` VALUES ('153', '13', '216', '0', '');
INSERT INTO `answers` VALUES ('154', '13', '217', '0', '');
INSERT INTO `answers` VALUES ('155', '13', '218', '0', '');
INSERT INTO `answers` VALUES ('156', '13', '219', '0', '');
INSERT INTO `answers` VALUES ('157', '13', '220', '0', '');
INSERT INTO `answers` VALUES ('158', '13', '221', '0', '');
INSERT INTO `answers` VALUES ('159', '13', '222', '0', '');
INSERT INTO `answers` VALUES ('160', '13', '223', '0', '');
INSERT INTO `answers` VALUES ('161', '13', '224', '0', '');
INSERT INTO `answers` VALUES ('162', '13', '225', '0', '');
INSERT INTO `answers` VALUES ('163', '13', '226', '0', '');
INSERT INTO `answers` VALUES ('164', '13', '227', '0', '');
INSERT INTO `answers` VALUES ('165', '14', '129', '2', null);
INSERT INTO `answers` VALUES ('166', '14', '129', '0', '');
INSERT INTO `answers` VALUES ('167', '14', '130', '3', null);
INSERT INTO `answers` VALUES ('168', '14', '131', '2', null);
INSERT INTO `answers` VALUES ('169', '14', '132', '1', null);
INSERT INTO `answers` VALUES ('170', '14', '133', '1', null);
INSERT INTO `answers` VALUES ('171', '14', '134', '2', null);
INSERT INTO `answers` VALUES ('172', '14', '135', '2', null);
INSERT INTO `answers` VALUES ('173', '14', '136', '2', null);
INSERT INTO `answers` VALUES ('174', '14', '129', '2', '');
INSERT INTO `answers` VALUES ('175', '14', '130', '3', '');
INSERT INTO `answers` VALUES ('176', '14', '131', '2', '');
INSERT INTO `answers` VALUES ('177', '14', '132', '1', '');
INSERT INTO `answers` VALUES ('178', '14', '133', '1', '');
INSERT INTO `answers` VALUES ('179', '14', '134', '2', '');
INSERT INTO `answers` VALUES ('180', '14', '135', '2', '');
INSERT INTO `answers` VALUES ('181', '14', '136', '2', '');
INSERT INTO `answers` VALUES ('182', '14', '137', '0', '');
INSERT INTO `answers` VALUES ('183', '14', '138', '0', '');
INSERT INTO `answers` VALUES ('184', '14', '139', '0', '');
INSERT INTO `answers` VALUES ('185', '14', '140', '0', '');
INSERT INTO `answers` VALUES ('186', '14', '142', '0', '');
INSERT INTO `answers` VALUES ('187', '14', '143', '0', '');
INSERT INTO `answers` VALUES ('188', '14', '144', '0', '');
INSERT INTO `answers` VALUES ('189', '14', '145', '0', '');
INSERT INTO `answers` VALUES ('190', '14', '146', '0', '');
INSERT INTO `answers` VALUES ('191', '14', '147', '0', '');
INSERT INTO `answers` VALUES ('192', '14', '148', '0', '');
INSERT INTO `answers` VALUES ('193', '14', '149', '0', '');
INSERT INTO `answers` VALUES ('194', '14', '150', '0', '');
INSERT INTO `answers` VALUES ('195', '14', '151', '0', '');
INSERT INTO `answers` VALUES ('196', '14', '152', '0', '');
INSERT INTO `answers` VALUES ('197', '14', '153', '0', '');
INSERT INTO `answers` VALUES ('198', '14', '154', '0', '');
INSERT INTO `answers` VALUES ('199', '14', '155', '0', '');
INSERT INTO `answers` VALUES ('200', '14', '156', '0', '');
INSERT INTO `answers` VALUES ('201', '14', '157', '0', '');
INSERT INTO `answers` VALUES ('202', '14', '158', '0', '');
INSERT INTO `answers` VALUES ('203', '14', '159', '0', '');
INSERT INTO `answers` VALUES ('204', '14', '160', '0', '');
INSERT INTO `answers` VALUES ('205', '14', '161', '0', '');
INSERT INTO `answers` VALUES ('206', '14', '162', '0', '');
INSERT INTO `answers` VALUES ('207', '14', '163', '0', '');
INSERT INTO `answers` VALUES ('208', '14', '164', '0', '');
INSERT INTO `answers` VALUES ('209', '14', '165', '0', '');
INSERT INTO `answers` VALUES ('210', '14', '166', '0', '');
INSERT INTO `answers` VALUES ('211', '14', '167', '0', '');
INSERT INTO `answers` VALUES ('212', '14', '168', '0', '');
INSERT INTO `answers` VALUES ('213', '14', '169', '0', '');
INSERT INTO `answers` VALUES ('214', '14', '170', '0', '');
INSERT INTO `answers` VALUES ('215', '14', '171', '0', '');
INSERT INTO `answers` VALUES ('216', '14', '172', '0', '');
INSERT INTO `answers` VALUES ('217', '14', '173', '0', '');
INSERT INTO `answers` VALUES ('218', '14', '174', '0', '');
INSERT INTO `answers` VALUES ('219', '14', '175', '0', '');
INSERT INTO `answers` VALUES ('220', '14', '176', '0', '');
INSERT INTO `answers` VALUES ('221', '14', '177', '0', '');
INSERT INTO `answers` VALUES ('222', '14', '178', '0', '');
INSERT INTO `answers` VALUES ('223', '14', '179', '0', '');
INSERT INTO `answers` VALUES ('224', '14', '180', '0', '');
INSERT INTO `answers` VALUES ('225', '14', '181', '0', '');
INSERT INTO `answers` VALUES ('226', '14', '182', '0', '');
INSERT INTO `answers` VALUES ('227', '14', '183', '0', '');
INSERT INTO `answers` VALUES ('228', '14', '184', '0', '');
INSERT INTO `answers` VALUES ('229', '14', '185', '0', '');
INSERT INTO `answers` VALUES ('230', '14', '186', '0', '');
INSERT INTO `answers` VALUES ('231', '14', '187', '0', '');
INSERT INTO `answers` VALUES ('232', '14', '188', '0', '');
INSERT INTO `answers` VALUES ('233', '14', '189', '0', '');
INSERT INTO `answers` VALUES ('234', '14', '190', '0', '');
INSERT INTO `answers` VALUES ('235', '14', '191', '0', '');
INSERT INTO `answers` VALUES ('236', '14', '192', '0', '');
INSERT INTO `answers` VALUES ('237', '14', '193', '0', '');
INSERT INTO `answers` VALUES ('238', '14', '194', '0', '');
INSERT INTO `answers` VALUES ('239', '14', '195', '0', '');
INSERT INTO `answers` VALUES ('240', '14', '196', '0', '');
INSERT INTO `answers` VALUES ('241', '14', '197', '0', '');
INSERT INTO `answers` VALUES ('242', '14', '198', '0', '');
INSERT INTO `answers` VALUES ('243', '14', '199', '0', '');
INSERT INTO `answers` VALUES ('244', '14', '200', '0', '');
INSERT INTO `answers` VALUES ('245', '14', '201', '0', '');
INSERT INTO `answers` VALUES ('246', '14', '202', '0', '');
INSERT INTO `answers` VALUES ('247', '14', '203', '0', '');
INSERT INTO `answers` VALUES ('248', '14', '204', '0', '');
INSERT INTO `answers` VALUES ('249', '14', '205', '0', '');
INSERT INTO `answers` VALUES ('250', '14', '206', '0', '');
INSERT INTO `answers` VALUES ('251', '14', '207', '0', '');
INSERT INTO `answers` VALUES ('252', '14', '208', '0', '');
INSERT INTO `answers` VALUES ('253', '14', '209', '0', '');
INSERT INTO `answers` VALUES ('254', '14', '210', '0', '');
INSERT INTO `answers` VALUES ('255', '14', '211', '0', '');
INSERT INTO `answers` VALUES ('256', '14', '212', '0', '');
INSERT INTO `answers` VALUES ('257', '14', '213', '0', '');
INSERT INTO `answers` VALUES ('258', '14', '214', '0', '');
INSERT INTO `answers` VALUES ('259', '14', '215', '0', '');
INSERT INTO `answers` VALUES ('260', '14', '216', '0', '');
INSERT INTO `answers` VALUES ('261', '14', '217', '0', '');
INSERT INTO `answers` VALUES ('262', '14', '218', '0', '');
INSERT INTO `answers` VALUES ('263', '14', '219', '0', '');
INSERT INTO `answers` VALUES ('264', '14', '220', '0', '');
INSERT INTO `answers` VALUES ('265', '14', '221', '0', '');
INSERT INTO `answers` VALUES ('266', '14', '222', '0', '');
INSERT INTO `answers` VALUES ('267', '14', '223', '0', '');
INSERT INTO `answers` VALUES ('268', '14', '224', '0', '');
INSERT INTO `answers` VALUES ('269', '14', '225', '0', '');
INSERT INTO `answers` VALUES ('270', '14', '226', '0', '');
INSERT INTO `answers` VALUES ('271', '14', '227', '0', '');
INSERT INTO `answers` VALUES ('272', '18', '140', '1', null);
INSERT INTO `answers` VALUES ('273', '18', '139', '2', null);
INSERT INTO `answers` VALUES ('274', '18', '139', '3', null);
INSERT INTO `answers` VALUES ('275', '18', '139', '2', null);
INSERT INTO `answers` VALUES ('276', '18', '139', '1', null);
INSERT INTO `answers` VALUES ('277', '18', '129', '1', null);
INSERT INTO `answers` VALUES ('278', '18', '129', '0', '');
INSERT INTO `answers` VALUES ('279', '18', '130', '2', null);
INSERT INTO `answers` VALUES ('280', '18', '131', '2', null);
INSERT INTO `answers` VALUES ('281', '18', '132', '3', null);
INSERT INTO `answers` VALUES ('282', '18', '133', '2', null);
INSERT INTO `answers` VALUES ('283', '18', '133', '1', null);
INSERT INTO `answers` VALUES ('284', '18', '135', '2', null);
INSERT INTO `answers` VALUES ('285', '18', '134', '1', null);
INSERT INTO `answers` VALUES ('286', '18', '129', '1', null);
INSERT INTO `answers` VALUES ('287', '18', '137', '1', null);
INSERT INTO `answers` VALUES ('288', '18', '129', '1', '');
INSERT INTO `answers` VALUES ('289', '18', '130', '2', '');
INSERT INTO `answers` VALUES ('290', '18', '131', '2', '');
INSERT INTO `answers` VALUES ('291', '18', '132', '3', '');
INSERT INTO `answers` VALUES ('292', '18', '133', '1', '');
INSERT INTO `answers` VALUES ('293', '18', '134', '1', '');
INSERT INTO `answers` VALUES ('294', '18', '135', '2', '');
INSERT INTO `answers` VALUES ('295', '18', '136', '0', '');
INSERT INTO `answers` VALUES ('296', '18', '137', '1', '');
INSERT INTO `answers` VALUES ('297', '18', '138', '0', '');
INSERT INTO `answers` VALUES ('298', '18', '139', '1', '');
INSERT INTO `answers` VALUES ('299', '18', '140', '1', '');
INSERT INTO `answers` VALUES ('300', '18', '142', '0', '');
INSERT INTO `answers` VALUES ('301', '18', '143', '0', '');
INSERT INTO `answers` VALUES ('302', '18', '144', '0', '');
INSERT INTO `answers` VALUES ('303', '18', '145', '0', '');
INSERT INTO `answers` VALUES ('304', '18', '146', '0', '');
INSERT INTO `answers` VALUES ('305', '18', '147', '0', '');
INSERT INTO `answers` VALUES ('306', '18', '148', '0', '');
INSERT INTO `answers` VALUES ('307', '18', '149', '0', '');
INSERT INTO `answers` VALUES ('308', '18', '150', '0', '');
INSERT INTO `answers` VALUES ('309', '18', '151', '0', '');
INSERT INTO `answers` VALUES ('310', '18', '152', '0', '');
INSERT INTO `answers` VALUES ('311', '18', '153', '0', '');
INSERT INTO `answers` VALUES ('312', '18', '154', '0', '');
INSERT INTO `answers` VALUES ('313', '18', '155', '0', '');
INSERT INTO `answers` VALUES ('314', '18', '156', '0', '');
INSERT INTO `answers` VALUES ('315', '18', '157', '0', '');
INSERT INTO `answers` VALUES ('316', '18', '158', '0', '');
INSERT INTO `answers` VALUES ('317', '18', '159', '0', '');
INSERT INTO `answers` VALUES ('318', '18', '160', '0', '');
INSERT INTO `answers` VALUES ('319', '18', '161', '0', '');
INSERT INTO `answers` VALUES ('320', '18', '162', '0', '');
INSERT INTO `answers` VALUES ('321', '18', '163', '0', '');
INSERT INTO `answers` VALUES ('322', '18', '164', '0', '');
INSERT INTO `answers` VALUES ('323', '18', '165', '0', '');
INSERT INTO `answers` VALUES ('324', '18', '166', '0', '');
INSERT INTO `answers` VALUES ('325', '18', '167', '0', '');
INSERT INTO `answers` VALUES ('326', '18', '168', '0', '');
INSERT INTO `answers` VALUES ('327', '18', '169', '0', '');
INSERT INTO `answers` VALUES ('328', '18', '170', '0', '');
INSERT INTO `answers` VALUES ('329', '18', '171', '0', '');
INSERT INTO `answers` VALUES ('330', '18', '172', '0', '');
INSERT INTO `answers` VALUES ('331', '18', '173', '0', '');
INSERT INTO `answers` VALUES ('332', '18', '174', '0', '');
INSERT INTO `answers` VALUES ('333', '18', '175', '0', '');
INSERT INTO `answers` VALUES ('334', '18', '176', '0', '');
INSERT INTO `answers` VALUES ('335', '18', '177', '0', '');
INSERT INTO `answers` VALUES ('336', '18', '178', '0', '');
INSERT INTO `answers` VALUES ('337', '18', '179', '0', '');
INSERT INTO `answers` VALUES ('338', '18', '180', '0', '');
INSERT INTO `answers` VALUES ('339', '18', '181', '0', '');
INSERT INTO `answers` VALUES ('340', '18', '182', '0', '');
INSERT INTO `answers` VALUES ('341', '18', '183', '0', '');
INSERT INTO `answers` VALUES ('342', '18', '184', '0', '');
INSERT INTO `answers` VALUES ('343', '18', '185', '0', '');
INSERT INTO `answers` VALUES ('344', '18', '186', '0', '');
INSERT INTO `answers` VALUES ('345', '18', '187', '0', '');
INSERT INTO `answers` VALUES ('346', '18', '188', '0', '');
INSERT INTO `answers` VALUES ('347', '18', '189', '0', '');
INSERT INTO `answers` VALUES ('348', '18', '190', '0', '');
INSERT INTO `answers` VALUES ('349', '18', '191', '0', '');
INSERT INTO `answers` VALUES ('350', '18', '192', '0', '');
INSERT INTO `answers` VALUES ('351', '18', '193', '0', '');
INSERT INTO `answers` VALUES ('352', '18', '194', '0', '');
INSERT INTO `answers` VALUES ('353', '18', '195', '0', '');
INSERT INTO `answers` VALUES ('354', '18', '196', '0', '');
INSERT INTO `answers` VALUES ('355', '18', '197', '0', '');
INSERT INTO `answers` VALUES ('356', '18', '198', '0', '');
INSERT INTO `answers` VALUES ('357', '18', '199', '0', '');
INSERT INTO `answers` VALUES ('358', '18', '200', '0', '');
INSERT INTO `answers` VALUES ('359', '18', '201', '0', '');
INSERT INTO `answers` VALUES ('360', '18', '202', '0', '');
INSERT INTO `answers` VALUES ('361', '18', '203', '0', '');
INSERT INTO `answers` VALUES ('362', '18', '204', '0', '');
INSERT INTO `answers` VALUES ('363', '18', '205', '0', '');
INSERT INTO `answers` VALUES ('364', '18', '206', '0', '');
INSERT INTO `answers` VALUES ('365', '18', '207', '0', '');
INSERT INTO `answers` VALUES ('366', '18', '208', '0', '');
INSERT INTO `answers` VALUES ('367', '18', '209', '0', '');
INSERT INTO `answers` VALUES ('368', '18', '210', '0', '');
INSERT INTO `answers` VALUES ('369', '18', '211', '0', '');
INSERT INTO `answers` VALUES ('370', '18', '212', '0', '');
INSERT INTO `answers` VALUES ('371', '18', '213', '0', '');
INSERT INTO `answers` VALUES ('372', '18', '214', '0', '');
INSERT INTO `answers` VALUES ('373', '18', '215', '0', '');
INSERT INTO `answers` VALUES ('374', '18', '216', '0', '');
INSERT INTO `answers` VALUES ('375', '18', '217', '0', '');
INSERT INTO `answers` VALUES ('376', '18', '218', '0', '');
INSERT INTO `answers` VALUES ('377', '18', '219', '0', '');
INSERT INTO `answers` VALUES ('378', '18', '220', '0', '');
INSERT INTO `answers` VALUES ('379', '18', '221', '0', '');
INSERT INTO `answers` VALUES ('380', '18', '222', '0', '');
INSERT INTO `answers` VALUES ('381', '18', '223', '0', '');
INSERT INTO `answers` VALUES ('382', '18', '224', '0', '');
INSERT INTO `answers` VALUES ('383', '18', '225', '0', '');
INSERT INTO `answers` VALUES ('384', '18', '226', '0', '');
INSERT INTO `answers` VALUES ('385', '18', '227', '0', '');
INSERT INTO `answers` VALUES ('386', '19', '129', '1', null);
INSERT INTO `answers` VALUES ('387', '19', '129', '2', null);
INSERT INTO `answers` VALUES ('388', '19', '130', '2', null);
INSERT INTO `answers` VALUES ('389', '19', '131', '3', null);
INSERT INTO `answers` VALUES ('390', '19', '132', '2', null);
INSERT INTO `answers` VALUES ('391', '19', '133', '2', null);
INSERT INTO `answers` VALUES ('392', '19', '134', '3', null);
INSERT INTO `answers` VALUES ('393', '19', '135', '3', null);
INSERT INTO `answers` VALUES ('394', '19', '136', '2', null);
INSERT INTO `answers` VALUES ('395', '19', '137', '3', null);
INSERT INTO `answers` VALUES ('396', '19', '138', '3', null);
INSERT INTO `answers` VALUES ('397', '19', '139', '3', null);
INSERT INTO `answers` VALUES ('398', '19', '140', '3', null);
INSERT INTO `answers` VALUES ('399', '19', '142', '2', null);
INSERT INTO `answers` VALUES ('400', '19', '143', '2', null);
INSERT INTO `answers` VALUES ('401', '19', '144', '2', null);
INSERT INTO `answers` VALUES ('402', '19', '146', '2', null);
INSERT INTO `answers` VALUES ('403', '19', '147', '2', null);
INSERT INTO `answers` VALUES ('404', '19', '149', '3', null);
INSERT INTO `answers` VALUES ('405', '19', '148', '3', null);
INSERT INTO `answers` VALUES ('406', '19', '150', '3', null);
INSERT INTO `answers` VALUES ('407', '19', '151', '3', null);
INSERT INTO `answers` VALUES ('408', '19', '153', '3', null);
INSERT INTO `answers` VALUES ('409', '19', '154', '3', null);
INSERT INTO `answers` VALUES ('410', '19', '155', '2', null);
INSERT INTO `answers` VALUES ('411', '19', '156', '2', null);
INSERT INTO `answers` VALUES ('412', '19', '157', '2', null);
INSERT INTO `answers` VALUES ('413', '19', '159', '2', null);
INSERT INTO `answers` VALUES ('414', '19', '158', '3', null);
INSERT INTO `answers` VALUES ('415', '19', '160', '2', null);
INSERT INTO `answers` VALUES ('416', '19', '161', '2', null);
INSERT INTO `answers` VALUES ('417', '19', '162', '2', null);
INSERT INTO `answers` VALUES ('418', '19', '163', '3', null);
INSERT INTO `answers` VALUES ('419', '19', '165', '3', null);
INSERT INTO `answers` VALUES ('420', '19', '166', '2', null);
INSERT INTO `answers` VALUES ('421', '19', '167', '2', null);
INSERT INTO `answers` VALUES ('422', '19', '169', '2', null);
INSERT INTO `answers` VALUES ('423', '19', '171', '2', null);
INSERT INTO `answers` VALUES ('424', '19', '170', '2', null);
INSERT INTO `answers` VALUES ('425', '19', '129', '2', '');
INSERT INTO `answers` VALUES ('426', '19', '130', '2', '');
INSERT INTO `answers` VALUES ('427', '19', '131', '3', '');
INSERT INTO `answers` VALUES ('428', '19', '132', '2', '');
INSERT INTO `answers` VALUES ('429', '19', '133', '2', '');
INSERT INTO `answers` VALUES ('430', '19', '134', '3', '');
INSERT INTO `answers` VALUES ('431', '19', '135', '3', '');
INSERT INTO `answers` VALUES ('432', '19', '136', '2', '');
INSERT INTO `answers` VALUES ('433', '19', '137', '3', '');
INSERT INTO `answers` VALUES ('434', '19', '138', '3', '');
INSERT INTO `answers` VALUES ('435', '19', '139', '3', '');
INSERT INTO `answers` VALUES ('436', '19', '140', '3', '');
INSERT INTO `answers` VALUES ('437', '19', '142', '2', '');
INSERT INTO `answers` VALUES ('438', '19', '143', '2', '');
INSERT INTO `answers` VALUES ('439', '19', '144', '2', '');
INSERT INTO `answers` VALUES ('440', '19', '145', '0', '');
INSERT INTO `answers` VALUES ('441', '19', '146', '2', '');
INSERT INTO `answers` VALUES ('442', '19', '147', '2', '');
INSERT INTO `answers` VALUES ('443', '19', '148', '3', '');
INSERT INTO `answers` VALUES ('444', '19', '149', '3', '');
INSERT INTO `answers` VALUES ('445', '19', '150', '3', '');
INSERT INTO `answers` VALUES ('446', '19', '151', '3', '');
INSERT INTO `answers` VALUES ('447', '19', '152', '0', '');
INSERT INTO `answers` VALUES ('448', '19', '153', '3', '');
INSERT INTO `answers` VALUES ('449', '19', '154', '3', '');
INSERT INTO `answers` VALUES ('450', '19', '155', '2', '');
INSERT INTO `answers` VALUES ('451', '19', '156', '2', '');
INSERT INTO `answers` VALUES ('452', '19', '157', '2', '');
INSERT INTO `answers` VALUES ('453', '19', '158', '3', '');
INSERT INTO `answers` VALUES ('454', '19', '159', '2', '');
INSERT INTO `answers` VALUES ('455', '19', '160', '2', '');
INSERT INTO `answers` VALUES ('456', '19', '161', '2', '');
INSERT INTO `answers` VALUES ('457', '19', '162', '2', '');
INSERT INTO `answers` VALUES ('458', '19', '163', '3', '');
INSERT INTO `answers` VALUES ('459', '19', '164', '0', '');
INSERT INTO `answers` VALUES ('460', '19', '165', '3', '');
INSERT INTO `answers` VALUES ('461', '19', '166', '2', '');
INSERT INTO `answers` VALUES ('462', '19', '167', '2', '');
INSERT INTO `answers` VALUES ('463', '19', '168', '0', '');
INSERT INTO `answers` VALUES ('464', '19', '169', '2', '');
INSERT INTO `answers` VALUES ('465', '19', '170', '2', '');
INSERT INTO `answers` VALUES ('466', '19', '171', '2', '');
INSERT INTO `answers` VALUES ('467', '19', '172', '0', '');
INSERT INTO `answers` VALUES ('468', '19', '173', '0', '');
INSERT INTO `answers` VALUES ('469', '19', '174', '0', '');
INSERT INTO `answers` VALUES ('470', '19', '175', '0', '');
INSERT INTO `answers` VALUES ('471', '19', '176', '0', '');
INSERT INTO `answers` VALUES ('472', '19', '177', '0', '');
INSERT INTO `answers` VALUES ('473', '19', '178', '0', '');
INSERT INTO `answers` VALUES ('474', '19', '179', '0', '');
INSERT INTO `answers` VALUES ('475', '19', '180', '0', '');
INSERT INTO `answers` VALUES ('476', '19', '181', '0', '');
INSERT INTO `answers` VALUES ('477', '19', '182', '0', '');
INSERT INTO `answers` VALUES ('478', '19', '183', '0', '');
INSERT INTO `answers` VALUES ('479', '19', '184', '0', '');
INSERT INTO `answers` VALUES ('480', '19', '185', '0', '');
INSERT INTO `answers` VALUES ('481', '19', '186', '0', '');
INSERT INTO `answers` VALUES ('482', '19', '187', '0', '');
INSERT INTO `answers` VALUES ('483', '19', '188', '0', '');
INSERT INTO `answers` VALUES ('484', '19', '189', '0', '');
INSERT INTO `answers` VALUES ('485', '19', '190', '0', '');
INSERT INTO `answers` VALUES ('486', '19', '191', '0', '');
INSERT INTO `answers` VALUES ('487', '19', '192', '0', '');
INSERT INTO `answers` VALUES ('488', '19', '193', '0', '');
INSERT INTO `answers` VALUES ('489', '19', '194', '0', '');
INSERT INTO `answers` VALUES ('490', '19', '195', '0', '');
INSERT INTO `answers` VALUES ('491', '19', '196', '0', '');
INSERT INTO `answers` VALUES ('492', '19', '197', '0', '');
INSERT INTO `answers` VALUES ('493', '19', '198', '0', '');
INSERT INTO `answers` VALUES ('494', '19', '199', '0', '');
INSERT INTO `answers` VALUES ('495', '19', '200', '0', '');
INSERT INTO `answers` VALUES ('496', '19', '201', '0', '');
INSERT INTO `answers` VALUES ('497', '19', '202', '0', '');
INSERT INTO `answers` VALUES ('498', '19', '203', '0', '');
INSERT INTO `answers` VALUES ('499', '19', '204', '0', '');
INSERT INTO `answers` VALUES ('500', '19', '205', '0', '');
INSERT INTO `answers` VALUES ('501', '19', '206', '0', '');
INSERT INTO `answers` VALUES ('502', '19', '207', '0', '');
INSERT INTO `answers` VALUES ('503', '19', '208', '0', '');
INSERT INTO `answers` VALUES ('504', '19', '209', '0', '');
INSERT INTO `answers` VALUES ('505', '19', '210', '0', '');
INSERT INTO `answers` VALUES ('506', '19', '211', '0', '');
INSERT INTO `answers` VALUES ('507', '19', '212', '0', '');
INSERT INTO `answers` VALUES ('508', '19', '213', '0', '');
INSERT INTO `answers` VALUES ('509', '19', '214', '0', '');
INSERT INTO `answers` VALUES ('510', '19', '215', '0', '');
INSERT INTO `answers` VALUES ('511', '19', '216', '0', '');
INSERT INTO `answers` VALUES ('512', '19', '217', '0', '');
INSERT INTO `answers` VALUES ('513', '19', '218', '0', '');
INSERT INTO `answers` VALUES ('514', '19', '219', '0', '');
INSERT INTO `answers` VALUES ('515', '19', '220', '0', '');
INSERT INTO `answers` VALUES ('516', '19', '221', '0', '');
INSERT INTO `answers` VALUES ('517', '19', '222', '0', '');
INSERT INTO `answers` VALUES ('518', '19', '223', '0', '');
INSERT INTO `answers` VALUES ('519', '19', '224', '0', '');
INSERT INTO `answers` VALUES ('520', '19', '225', '0', '');
INSERT INTO `answers` VALUES ('521', '19', '226', '0', '');
INSERT INTO `answers` VALUES ('522', '19', '227', '0', '');
INSERT INTO `answers` VALUES ('523', '20', '129', '1', null);
INSERT INTO `answers` VALUES ('524', '20', '129', '2', null);
INSERT INTO `answers` VALUES ('525', '20', '130', '2', null);
INSERT INTO `answers` VALUES ('526', '20', '131', '2', null);
INSERT INTO `answers` VALUES ('527', '20', '132', '3', null);
INSERT INTO `answers` VALUES ('528', '20', '133', '3', null);
INSERT INTO `answers` VALUES ('529', '20', '134', '2', null);
INSERT INTO `answers` VALUES ('530', '20', '135', '2', null);
INSERT INTO `answers` VALUES ('531', '20', '136', '3', null);
INSERT INTO `answers` VALUES ('532', '20', '137', '2', null);
INSERT INTO `answers` VALUES ('533', '20', '138', '3', null);
INSERT INTO `answers` VALUES ('534', '20', '139', '2', null);
INSERT INTO `answers` VALUES ('535', '20', '140', '2', null);
INSERT INTO `answers` VALUES ('536', '20', '129', '2', '');
INSERT INTO `answers` VALUES ('537', '20', '130', '2', '');
INSERT INTO `answers` VALUES ('538', '20', '131', '2', '');
INSERT INTO `answers` VALUES ('539', '20', '132', '3', '');
INSERT INTO `answers` VALUES ('540', '20', '133', '3', '');
INSERT INTO `answers` VALUES ('541', '20', '134', '2', '');
INSERT INTO `answers` VALUES ('542', '20', '135', '2', '');
INSERT INTO `answers` VALUES ('543', '20', '136', '3', '');
INSERT INTO `answers` VALUES ('544', '20', '137', '2', '');
INSERT INTO `answers` VALUES ('545', '20', '138', '3', '');
INSERT INTO `answers` VALUES ('546', '20', '139', '2', '');
INSERT INTO `answers` VALUES ('547', '20', '140', '2', '');
INSERT INTO `answers` VALUES ('548', '20', '142', '0', '');
INSERT INTO `answers` VALUES ('549', '20', '143', '0', '');
INSERT INTO `answers` VALUES ('550', '20', '144', '0', '');
INSERT INTO `answers` VALUES ('551', '20', '145', '0', '');
INSERT INTO `answers` VALUES ('552', '20', '146', '0', '');
INSERT INTO `answers` VALUES ('553', '20', '147', '0', '');
INSERT INTO `answers` VALUES ('554', '20', '148', '0', '');
INSERT INTO `answers` VALUES ('555', '20', '149', '0', '');
INSERT INTO `answers` VALUES ('556', '20', '150', '0', '');
INSERT INTO `answers` VALUES ('557', '20', '151', '0', '');
INSERT INTO `answers` VALUES ('558', '20', '152', '0', '');
INSERT INTO `answers` VALUES ('559', '20', '153', '0', '');
INSERT INTO `answers` VALUES ('560', '20', '154', '0', '');
INSERT INTO `answers` VALUES ('561', '20', '155', '0', '');
INSERT INTO `answers` VALUES ('562', '20', '156', '0', '');
INSERT INTO `answers` VALUES ('563', '20', '157', '0', '');
INSERT INTO `answers` VALUES ('564', '20', '158', '0', '');
INSERT INTO `answers` VALUES ('565', '20', '159', '0', '');
INSERT INTO `answers` VALUES ('566', '20', '160', '0', '');
INSERT INTO `answers` VALUES ('567', '20', '161', '0', '');
INSERT INTO `answers` VALUES ('568', '20', '162', '0', '');
INSERT INTO `answers` VALUES ('569', '20', '163', '0', '');
INSERT INTO `answers` VALUES ('570', '20', '164', '0', '');
INSERT INTO `answers` VALUES ('571', '20', '165', '0', '');
INSERT INTO `answers` VALUES ('572', '20', '166', '0', '');
INSERT INTO `answers` VALUES ('573', '20', '167', '0', '');
INSERT INTO `answers` VALUES ('574', '20', '168', '0', '');
INSERT INTO `answers` VALUES ('575', '20', '169', '0', '');
INSERT INTO `answers` VALUES ('576', '20', '170', '0', '');
INSERT INTO `answers` VALUES ('577', '20', '171', '0', '');
INSERT INTO `answers` VALUES ('578', '20', '172', '0', '');
INSERT INTO `answers` VALUES ('579', '20', '173', '0', '');
INSERT INTO `answers` VALUES ('580', '20', '174', '0', '');
INSERT INTO `answers` VALUES ('581', '20', '175', '0', '');
INSERT INTO `answers` VALUES ('582', '20', '176', '0', '');
INSERT INTO `answers` VALUES ('583', '20', '177', '0', '');
INSERT INTO `answers` VALUES ('584', '20', '178', '0', '');
INSERT INTO `answers` VALUES ('585', '20', '179', '0', '');
INSERT INTO `answers` VALUES ('586', '20', '180', '0', '');
INSERT INTO `answers` VALUES ('587', '20', '181', '0', '');
INSERT INTO `answers` VALUES ('588', '20', '182', '0', '');
INSERT INTO `answers` VALUES ('589', '20', '183', '0', '');
INSERT INTO `answers` VALUES ('590', '20', '184', '0', '');
INSERT INTO `answers` VALUES ('591', '20', '185', '0', '');
INSERT INTO `answers` VALUES ('592', '20', '186', '0', '');
INSERT INTO `answers` VALUES ('593', '20', '187', '0', '');
INSERT INTO `answers` VALUES ('594', '20', '188', '0', '');
INSERT INTO `answers` VALUES ('595', '20', '189', '0', '');
INSERT INTO `answers` VALUES ('596', '20', '190', '0', '');
INSERT INTO `answers` VALUES ('597', '20', '191', '0', '');
INSERT INTO `answers` VALUES ('598', '20', '192', '0', '');
INSERT INTO `answers` VALUES ('599', '20', '193', '0', '');
INSERT INTO `answers` VALUES ('600', '20', '194', '0', '');
INSERT INTO `answers` VALUES ('601', '20', '195', '0', '');
INSERT INTO `answers` VALUES ('602', '20', '196', '0', '');
INSERT INTO `answers` VALUES ('603', '20', '197', '0', '');
INSERT INTO `answers` VALUES ('604', '20', '198', '0', '');
INSERT INTO `answers` VALUES ('605', '20', '199', '0', '');
INSERT INTO `answers` VALUES ('606', '20', '200', '0', '');
INSERT INTO `answers` VALUES ('607', '20', '201', '0', '');
INSERT INTO `answers` VALUES ('608', '20', '202', '0', '');
INSERT INTO `answers` VALUES ('609', '20', '203', '0', '');
INSERT INTO `answers` VALUES ('610', '20', '204', '0', '');
INSERT INTO `answers` VALUES ('611', '20', '205', '0', '');
INSERT INTO `answers` VALUES ('612', '20', '206', '0', '');
INSERT INTO `answers` VALUES ('613', '20', '207', '0', '');
INSERT INTO `answers` VALUES ('614', '20', '208', '0', '');
INSERT INTO `answers` VALUES ('615', '20', '209', '0', '');
INSERT INTO `answers` VALUES ('616', '20', '210', '0', '');
INSERT INTO `answers` VALUES ('617', '20', '211', '0', '');
INSERT INTO `answers` VALUES ('618', '20', '212', '0', '');
INSERT INTO `answers` VALUES ('619', '20', '213', '0', '');
INSERT INTO `answers` VALUES ('620', '20', '214', '0', '');
INSERT INTO `answers` VALUES ('621', '20', '215', '0', '');
INSERT INTO `answers` VALUES ('622', '20', '216', '0', '');
INSERT INTO `answers` VALUES ('623', '20', '217', '0', '');
INSERT INTO `answers` VALUES ('624', '20', '218', '0', '');
INSERT INTO `answers` VALUES ('625', '20', '219', '0', '');
INSERT INTO `answers` VALUES ('626', '20', '220', '0', '');
INSERT INTO `answers` VALUES ('627', '20', '221', '0', '');
INSERT INTO `answers` VALUES ('628', '20', '222', '0', '');
INSERT INTO `answers` VALUES ('629', '20', '223', '0', '');
INSERT INTO `answers` VALUES ('630', '20', '224', '0', '');
INSERT INTO `answers` VALUES ('631', '20', '225', '0', '');
INSERT INTO `answers` VALUES ('632', '20', '226', '0', '');
INSERT INTO `answers` VALUES ('633', '20', '227', '0', '');

-- ----------------------------
-- Table structure for assessments
-- ----------------------------
DROP TABLE IF EXISTS `assessments`;
CREATE TABLE `assessments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `organization_name` varchar(255) DEFAULT NULL,
  `assessor_name` varchar(255) DEFAULT NULL,
  `org_status` varchar(100) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `score` float DEFAULT NULL,
  `risk_level` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of assessments
-- ----------------------------
INSERT INTO `assessments` VALUES ('4', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-04 16:30:23', null, '2025-10-06 13:02:46', null, null);
INSERT INTO `assessments` VALUES ('5', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 02:28:48', null, '2025-10-06 13:02:56', null, null);
INSERT INTO `assessments` VALUES ('6', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 02:55:42', null, '2025-10-06 13:02:54', null, null);
INSERT INTO `assessments` VALUES ('7', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 02:56:57', '2025-10-05 02:57:06', '2025-10-06 13:02:52', '1', 'แดง');
INSERT INTO `assessments` VALUES ('8', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 03:01:15', null, '2025-10-06 13:02:51', null, null);
INSERT INTO `assessments` VALUES ('9', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 03:04:24', '2025-10-05 03:04:28', '2025-10-06 13:02:49', '1', 'แดง');
INSERT INTO `assessments` VALUES ('10', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 03:10:18', null, '2025-10-06 13:02:47', null, null);
INSERT INTO `assessments` VALUES ('11', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 04:22:27', null, '2025-10-06 13:02:43', null, null);
INSERT INTO `assessments` VALUES ('12', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 04:38:02', null, '2025-10-06 13:02:42', null, null);
INSERT INTO `assessments` VALUES ('13', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 07:38:58', '2025-10-05 07:39:22', '2025-10-06 13:02:40', '1', 'แดง');
INSERT INTO `assessments` VALUES ('14', 'ทดสอบ', 'sdsddsd', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 08:11:11', '2025-10-05 08:11:27', '2025-10-06 13:02:38', '1.13', 'แดง');
INSERT INTO `assessments` VALUES ('15', 'ทดสอบ', 'ทดสอบประเมิน', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 09:41:47', null, '2025-10-06 13:02:37', null, null);
INSERT INTO `assessments` VALUES ('16', 'ทดสอบ', 'ทดสอบประเมิน', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 09:43:07', null, '2025-10-06 13:02:34', null, null);
INSERT INTO `assessments` VALUES ('17', 'ทดสอบ', 'ทดสอบประเมิน', 'CII', 'habusaya@gmail.com', '2', '2025-10-05 09:44:44', null, '2025-10-06 13:02:33', null, null);
INSERT INTO `assessments` VALUES ('18', 'ทดสอบ1', 'ทดสอบประเมิน1', 'CII', 'habusaya@gmail.com', '2', '2025-10-06 09:29:48', '2025-10-06 09:36:20', '2025-10-06 13:02:31', '1.13', 'แดง');
INSERT INTO `assessments` VALUES ('19', 'ทดสอบ', 'ทดสอบประเมิน', 'CII', 'habusaya@gmail.com', '5', '2025-10-06 13:03:15', '2025-10-06 13:30:46', null, '1.79', 'แดง');
INSERT INTO `assessments` VALUES ('20', 'ทดสอบ', 'ทดสอบประเมิน', 'CII', 'habusaya@gmail.com', '2', '2025-10-07 02:13:54', '2025-10-07 02:14:51', null, '1.29', 'แดง');

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `weight` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7034 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('3731', 'D1', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', null, '1');
INSERT INTO `categories` VALUES ('3762', 'D2', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', null, '1');
INSERT INTO `categories` VALUES ('3783', 'D3', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', null, '1');

-- ----------------------------
-- Table structure for cii_assessments
-- ----------------------------
DROP TABLE IF EXISTS `cii_assessments`;
CREATE TABLE `cii_assessments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section` varchar(20) NOT NULL,
  `assessed_at` date NOT NULL,
  `note` text,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of cii_assessments
-- ----------------------------

-- ----------------------------
-- Table structure for cii_evidence
-- ----------------------------
DROP TABLE IF EXISTS `cii_evidence`;
CREATE TABLE `cii_evidence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `assessment_id` int NOT NULL,
  `item_id` int NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `note` text,
  `uploaded_by` int DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assessment_id` (`assessment_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `cii_evidence_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `cii_assessments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cii_evidence_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `cii_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of cii_evidence
-- ----------------------------

-- ----------------------------
-- Table structure for cii_items
-- ----------------------------
DROP TABLE IF EXISTS `cii_items`;
CREATE TABLE `cii_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section` varchar(20) NOT NULL,
  `num` int NOT NULL,
  `objective` text,
  `requirement` text,
  `evident` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_section_num` (`section`,`num`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of cii_items
-- ----------------------------
INSERT INTO `cii_items` VALUES ('1', 'D1', '1', 'มีการดำเนินการให้มีนโยบายแผนและแนวปฏิบัติฯ การรักษาความมั่นคงปลอดภัยไซเบอร์', 'ม. 43', 'นโยบายฯ, แผนฯ, แผนผังองค์กร, คำสั่งแต่งตั้งผู้รับผิดชอบ, CISO');
INSERT INTO `cii_items` VALUES ('2', 'D1', '2', 'มีการจัดทำประมวลแนวทางปฏิบัติและกรอบมาตรฐานฯ ที่สอดคล้องกับแผนฯ', 'ม. 44', 'แผนการสอบทาน, ประมวลแนวทางปฏิบัติ, Incident Response Plan');
INSERT INTO `cii_items` VALUES ('3', 'D1', '3', 'มีการป้องกัน รับมือ และตอบสนองเหตุการณ์ความมั่นคงไซเบอร์ฯ', 'ม. 45', 'แผนรับมือ, รายงานเหตุการณ์, Cybersecurity Incident Response Plan');
INSERT INTO `cii_items` VALUES ('4', 'D1', '4', 'มีการประเมินระดับความเสี่ยงฯ และดำเนินการตามมาตรการฯ', 'ม. 46', 'รายงานประเมินความเสี่ยง, มาตรการควบคุม, รายงานผล');
INSERT INTO `cii_items` VALUES ('5', 'D1', '5', 'มีการแจ้งเตือนและข้อมูลการติดต่อส่วนราชการที่เกี่ยวข้องฯ', 'ม. 52', 'หลักฐานการแจ้ง, หนังสือแจ้ง, Email, ช่องทางอื่น');
INSERT INTO `cii_items` VALUES ('6', 'D1', '6', 'มีการเปลี่ยนแปลงหรืออัพเดทข้อมูลการติดต่อของกรมที่เกี่ยวข้องฯ', 'ม. 52', 'หลักฐานการแจ้งเปลี่ยนแปลง, หนังสือแจ้ง, Email, ช่องทางอื่น');
INSERT INTO `cii_items` VALUES ('7', 'D1', '7', 'มีการประเมินและตรวจประเมินฯ', 'ม. 54', 'รายงานการตรวจประเมิน, รายงานผล, ประเมินประจำปี');
INSERT INTO `cii_items` VALUES ('8', 'D1', '8', 'มีการจัดส่งผลสรุปรายงานการดำเนินการฯ', 'ม. 54', 'รายงานผล, รายงานผลตรวจสอบ, รายงานประจำปี');
INSERT INTO `cii_items` VALUES ('9', 'D1', '9', 'มีนโยบายหรือข้อเสนอแนะในการรับมือเหตุการณ์ฯ', 'ม. 56', 'คู่มือ, ข้อเสนอแนะ, แนวทางปฏิบัติ, รายงาน');
INSERT INTO `cii_items` VALUES ('10', 'D1', '10', 'มีการทดสอบการตอบสนองเหตุการณ์ฯ (Cyber Exercise)', 'ม. 56', 'หลักฐานการทดสอบ, รายงานผล, National Cyber Exercise, รายงานผลทดสอบ');
INSERT INTO `cii_items` VALUES ('11', 'D1', '11', 'มีการบันทึกและรายงานผลการดำเนินงานฯ', 'ม. 57', 'รายงานผล, รายงานการดำเนินงาน, รายงานประจำปี');
INSERT INTO `cii_items` VALUES ('12', 'D1', '12', 'หากเกิดหรือคาดว่าจะเกิดเหตุการณ์ฯ ให้แจ้งหน่วยงานที่เกี่ยวข้องฯ', 'ม. 58', 'หลักฐานการแจ้ง, รายงานการดำเนินการ, หนังสือแจ้ง, Email, ช่องทางอื่น');
INSERT INTO `cii_items` VALUES ('13', 'D2', '13', 'มีการจัดโครงสร้างองค์กรให้มีการถ่วงดุล โดยจัดโครงสร้างองค์กรพร้อมกำหนดอำนาจ บทบาทหน้าที่ และความรับผิดชอบ (Authorities, Roles and Responsibilities) ที่ชัดเจน เกี่ยวกับการบริหารจัดการความมั่นคงปลอดภัยไซเบอร์ให้มีการถ่วงดุลตามหลักการควบคุม กำกับ และตรวจสอบ (Three Lines of Defense)', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 1.1', 'เอกสารผังโครงสร้างขององค์กร และการกำหนดอำนาจ บทบาทหน้าที่ และความรับผิดชอบ ที่แสดงการถ่วงดุลตามหลักการควบคุม กำกับ และตรวจสอบ (Three Lines of Defense)');
INSERT INTO `cii_items` VALUES ('14', 'D2', '14', 'หน่วยงานมีผู้บริหารระดับสูงที่ทำหน้าที่บริหารจัดการความมั่นคงปลอดภัยสารสนเทศ (Chief Information Security Officer : CISO) หรือเทียบเท่าที่ปฏิบัติหน้าที่เสมือน CISO ของหน่วยงาน', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 1.3', 'เอกสารคำสั่งแต่งตั้ง ผู้บริหารระดับสูง ที่ทำหน้าที่บริหารจัดการความมั่นคงปลอดภัยสารสนเทศ (CISO) หรือเทียบเท่า');
INSERT INTO `cii_items` VALUES ('15', 'D2', '15', 'ผู้บริหารระดับสูงที่ทำหน้าที่บริหารจัดการความมั่นคงปลอดภัยสารสนเทศมีความเป็นอิสระจากงานด้านการปฏิบัติงานเทคโนโลยีสารสนเทศ (IT operation) และงานด้านพัฒนาระบบเทคโนโลยีสารสนเทศ (IT Development) และมีอำนาจหน้าที่ (Authority) เพียงพอในการปฏิบัติงานในหน้าที่ CISO ได้อย่างมีประสิทธิภาพและประสิทธิผล', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 1.3', 'เอกสารแสดงอำนาจหน้าที่หรือความรับผิดชอบของ CISO หรือเทียบเท่า');
INSERT INTO `cii_items` VALUES ('16', 'D2', '16', 'มีการจัดทำกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์เป็นลายลักษณ์อักษร', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.1', 'เอกสารกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์');
INSERT INTO `cii_items` VALUES ('17', 'D2', '17', 'มีเกณฑ์ประเมินความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และระดับความเสี่ยงที่ยอมรับได้ (Risk Appetite)', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.1', 'เอกสารกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์');
INSERT INTO `cii_items` VALUES ('18', 'D2', '18', 'มีวิธีการประเมินความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.1', 'เอกสารกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์');
INSERT INTO `cii_items` VALUES ('19', 'D2', '19', 'มีการเฝ้าระวังและติดตามความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.1', 'เอกสารกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์');
INSERT INTO `cii_items` VALUES ('20', 'D2', '20', 'มีการเก็บรักษารายการความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ที่ระบุไว้ในทะเบียนความเสี่ยง (Risk Register) ที่เกี่ยวข้องกับบริการที่สำคัญของหน่วยงาน', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.2', 'เอกสารรายการความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ที่ระบุไว้ในทะเบียนความเสี่ยง (Risk Register)');
INSERT INTO `cii_items` VALUES ('21', 'D2', '21', 'มีการติดตามความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ที่ระบุไว้อย่างสม่ำเสมอเพื่อให้แน่ใจว่าอยู่ภายใต้เกณฑ์ระดับความเสี่ยงที่ยอมรับได้', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.3', 'เอกสารรายงานผลการติดตาม/ประเมินผลการดำเนินงานเกี่ยวกับการบริหารความเสี่ยง');
INSERT INTO `cii_items` VALUES ('22', 'D2', '22', 'มีการกำหนดและอนุมัตินโยบาย มาตรฐาน และแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญของหน่วยงาน จากภัยคุกคามทางไซเบอร์', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 3.1', 'เอกสารนโยบาย มาตรฐานและแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญ (เอกสารที่มีการอนุมัติ/ประกาศใช้)');
INSERT INTO `cii_items` VALUES ('23', 'D2', '23', 'มีนโยบาย มาตรฐาน และแนวปฏิบัติในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญของหน่วยงาน ที่สอดคล้องกับข้อกำหนดและทิศทางระดับภาคส่วน/ระดับประเทศ', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 3.1', 'เอกสารนโยบาย มาตรฐาน และแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญ');
INSERT INTO `cii_items` VALUES ('24', 'D2', '24', 'มีนโยบาย มาตรฐาน และแนวปฏิบัติที่มีการเผยแพร่และสื่อสารไปยังบุคลากรและบุคคลภายนอกทุกคนที่ทำหน้าที่หรือสามารถเข้าถึงบริการที่สำคัญของหน่วยงาน', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 3.1', 'เอกสารนโยบาย มาตรฐาน และแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญ');
INSERT INTO `cii_items` VALUES ('25', 'D2', '25', 'มีการทบทวนนโยบาย มาตรฐาน และแนวทางปฏิบัติกับสภาพแวดล้อมการปฏิบัติการไซเบอร์ของบริการที่สำคัญ และภูมิทัศน์ภัยคุกคามทางไซเบอร์ในปัจจุบัน อย่างน้อยปีละ 1 ครั้ง', 'นโยบายบริหารจัดการ ภาคผนวก ข้อ 3.2', 'เอกสารรายงานผลการทบทวนประจำปี เกี่ยวกับนโยบาย มาตรฐาน และแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญ');
INSERT INTO `cii_items` VALUES ('26', 'D3', '26', 'มีการตรวจสอบด้านความมั่นคงปลอดภัยไซเบอร์โดยมีข้อมูลสนับสนุนตามข้อกำหนดที่เกี่ยวข้อง', 'แผนการตรวจสอบ', 'รายงานหรือแผนการตรวจสอบ');
INSERT INTO `cii_items` VALUES ('27', 'D3', '27', 'มีการประเมินผลกระทบทางธุรกิจ (Business Impact Analysis: BIA)', 'แผนการตรวจสอบ', 'แผนการทำ BIA, Audit Program/Plan, Audit Report');
INSERT INTO `cii_items` VALUES ('28', 'D3', '28', 'มีการตรวจสอบในเรื่องบริการที่สำคัญที่หน่วยงานเป็นเจ้าของและใช้บริการ ตามผลการวิเคราะห์ผลกระทบทางธุรกิจ', 'แผนการตรวจสอบ', 'Audit Program, Audit Report, รายงานผลการวิเคราะห์ BIA');
INSERT INTO `cii_items` VALUES ('29', 'D3', '29', 'มีการตรวจสอบการปฏิบัติตามประมวลแนวทางปฏิบัติ มาตรฐานการปฏิบัติงาน และคำสั่ง กฎหมาย', 'แผนการตรวจสอบ', 'Audit Program, Audit Report, รายงานผลการตรวจสอบ');
INSERT INTO `cii_items` VALUES ('30', 'D3', '30', 'มีการสอบทานผลการปฏิบัติตามข้อกำหนดการตรวจสอบด้านความมั่นคงปลอดภัยไซเบอร์ และแจ้งผลการสอบทานให้กับหน่วยงานที่เกี่ยวข้องภายใน 30 วัน', 'แผนการตรวจสอบ', 'รายงานผลการสอบทาน, Audit Report, หนังสือแจ้งผล');
INSERT INTO `cii_items` VALUES ('31', 'D3', '31', 'มีการทบทวนและปรับปรุงข้อกำหนดการตรวจสอบด้านความมั่นคงปลอดภัยไซเบอร์อย่างน้อยปีละ 1 ครั้ง', 'แผนการตรวจสอบ', 'รายงานการทบทวน, Audit Report, รายงานผลการปรับปรุง');

-- ----------------------------
-- Table structure for cii_scores
-- ----------------------------
DROP TABLE IF EXISTS `cii_scores`;
CREATE TABLE `cii_scores` (
  `assessment_id` int NOT NULL,
  `item_id` int NOT NULL,
  `score` tinyint NOT NULL,
  `note` text,
  PRIMARY KEY (`assessment_id`,`item_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `cii_scores_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `cii_assessments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cii_scores_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `cii_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of cii_scores
-- ----------------------------

-- ----------------------------
-- Table structure for document_review_steps
-- ----------------------------
DROP TABLE IF EXISTS `document_review_steps`;
CREATE TABLE `document_review_steps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `document_id` int NOT NULL,
  `reviewer_id` int DEFAULT NULL,
  `action` enum('PENDING','PASS','FAIL','COMMENT') NOT NULL,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`),
  CONSTRAINT `document_review_steps_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of document_review_steps
-- ----------------------------
INSERT INTO `document_review_steps` VALUES ('2', '7', '1', 'PENDING', '', '2025-10-07 01:53:12');
INSERT INTO `document_review_steps` VALUES ('3', '7', '1', 'PENDING', '', '2025-10-07 01:53:38');
INSERT INTO `document_review_steps` VALUES ('4', '7', '5', 'PASS', '', '2025-10-07 02:11:36');
INSERT INTO `document_review_steps` VALUES ('5', '10', '5', 'PASS', '', '2025-10-07 02:37:42');

-- ----------------------------
-- Table structure for documents
-- ----------------------------
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `assessment_id` int NOT NULL,
  `category_id` int NOT NULL,
  `question_id` int DEFAULT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `stored_name` varchar(255) DEFAULT NULL,
  `mime` varchar(255) DEFAULT NULL,
  `size` int DEFAULT NULL,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `reviewed_by` int DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `status` enum('PENDING','PASS','FAIL') DEFAULT 'PENDING',
  `notes` text,
  `reviewers` text,
  `current_reviewer_idx` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `assessment_id` (`assessment_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of documents
-- ----------------------------
INSERT INTO `documents` VALUES ('2', '17', '3783', '155', 'คู่ฉบับขอใช้วิจัย .docx', 'doc_68e38b79d8a22_____________________________________________________.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '24865', '2025-10-06 09:27:21', null, null, 'PENDING', null, '[]', '0');
INSERT INTO `documents` VALUES ('3', '18', '3731', '129', 'คู่ฉบับขอใช้วิจัย .docx', 'doc_68e38cd5b2e6f_____________________________________________________.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '24865', '2025-10-06 09:33:09', null, null, 'PENDING', null, '[]', '0');
INSERT INTO `documents` VALUES ('4', '18', '3783', '157', '00_แนวทางสรุป_กำหนดตำแหน่งสายงานวิทยฯ69.docx', 'doc_68e38d61323ca_00_____________________________________________________________________________________________________69.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '30071', '2025-10-06 09:35:29', null, null, 'PENDING', null, '[]', '0');
INSERT INTO `documents` VALUES ('5', '18', '3783', '160', 'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx', 'doc_68e38d748c84a__________________________3______________________________________________________________________.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '35093', '2025-10-06 09:35:48', null, null, 'PENDING', null, '[]', '0');
INSERT INTO `documents` VALUES ('6', '19', '3731', '129', 'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx', 'doc_68e3c0daacfdc__________________________3______________________________________________________________________.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '35093', '2025-10-06 13:15:06', null, null, 'PENDING', null, '[]', '0');
INSERT INTO `documents` VALUES ('7', '19', '3762', '143', 'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx', 'doc_68e3c4599097a__________________________3______________________________________________________________________.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '35093', '2025-10-06 13:30:01', '5', '2025-10-07 02:11:36', 'PASS', null, '[5]', '0');
INSERT INTO `documents` VALUES ('8', '20', '3731', '129', 'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx', 'doc_68e47771193ab__________________________3______________________________________________________________________.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '35093', '2025-10-07 02:14:09', null, null, 'PENDING', null, null, '0');
INSERT INTO `documents` VALUES ('9', '20', '3731', '130', 'แบบฟอร์ม_2_แบบบรรยายลักษณะงาน(JD).docx', 'doc_68e4777678f8c__________________________2________________________________________________________JD_.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '23317', '2025-10-07 02:14:14', null, null, 'PENDING', null, null, '0');
INSERT INTO `documents` VALUES ('10', '20', '3731', '131', '00_แนวทางสรุป_กำหนดตำแหน่งสายงานวิทยฯ69.docx', 'doc_68e477855403b_00_____________________________________________________________________________________________________69.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '30071', '2025-10-07 02:14:29', '5', '2025-10-07 02:37:42', 'PASS', null, '[5]', '0');

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `details` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of log
-- ----------------------------
INSERT INTO `log` VALUES ('1', '2', 'document_review', 'Reviewed document ID 1, status: FAIL', '2025-10-04 14:39:42');
INSERT INTO `log` VALUES ('2', '1', 'assign_role', 'Set role of user #7 to reviewer', '2025-10-06 09:22:18');
INSERT INTO `log` VALUES ('3', '1', 'assign_role', 'Set role of user #7 to reviewer', '2025-10-06 09:22:21');
INSERT INTO `log` VALUES ('4', '1', 'assign_role', 'Set role of user #7 to reviewer', '2025-10-06 09:25:22');
INSERT INTO `log` VALUES ('5', '1', 'assessment_delete', 'Delete assessment #1', '2025-10-06 09:44:55');
INSERT INTO `log` VALUES ('6', '1', 'assessment_delete', 'Delete assessment #2', '2025-10-06 09:44:59');
INSERT INTO `log` VALUES ('7', '1', 'assessment_delete', 'Delete assessment #3', '2025-10-06 09:45:02');
INSERT INTO `log` VALUES ('8', '1', 'assign_reviewer', 'Assign reviewer #5 to document #5', '2025-10-06 09:46:01');
INSERT INTO `log` VALUES ('9', '1', 'assign_reviewer', 'Assign reviewer #5 to document #4', '2025-10-06 09:46:08');
INSERT INTO `log` VALUES ('10', '1', 'assign_reviewer', 'Assign reviewer #5 to document #3', '2025-10-06 09:50:46');
INSERT INTO `log` VALUES ('11', '1', 'assign_reviewer', 'Assign reviewer #5 to document #2', '2025-10-06 09:50:54');
INSERT INTO `log` VALUES ('12', '1', 'assessment_delete', 'Soft delete assessment #18', '2025-10-06 13:02:31');
INSERT INTO `log` VALUES ('13', '1', 'assessment_delete', 'Soft delete assessment #17', '2025-10-06 13:02:33');
INSERT INTO `log` VALUES ('14', '1', 'assessment_delete', 'Soft delete assessment #16', '2025-10-06 13:02:34');
INSERT INTO `log` VALUES ('15', '1', 'assessment_delete', 'Soft delete assessment #15', '2025-10-06 13:02:37');
INSERT INTO `log` VALUES ('16', '1', 'assessment_delete', 'Soft delete assessment #14', '2025-10-06 13:02:38');
INSERT INTO `log` VALUES ('17', '1', 'assessment_delete', 'Soft delete assessment #13', '2025-10-06 13:02:40');
INSERT INTO `log` VALUES ('18', '1', 'assessment_delete', 'Soft delete assessment #12', '2025-10-06 13:02:42');
INSERT INTO `log` VALUES ('19', '1', 'assessment_delete', 'Soft delete assessment #11', '2025-10-06 13:02:43');
INSERT INTO `log` VALUES ('20', '1', 'assessment_delete', 'Soft delete assessment #4', '2025-10-06 13:02:46');
INSERT INTO `log` VALUES ('21', '1', 'assessment_delete', 'Soft delete assessment #10', '2025-10-06 13:02:47');
INSERT INTO `log` VALUES ('22', '1', 'assessment_delete', 'Soft delete assessment #9', '2025-10-06 13:02:49');
INSERT INTO `log` VALUES ('23', '1', 'assessment_delete', 'Soft delete assessment #8', '2025-10-06 13:02:51');
INSERT INTO `log` VALUES ('24', '1', 'assessment_delete', 'Soft delete assessment #7', '2025-10-06 13:02:52');
INSERT INTO `log` VALUES ('25', '1', 'assessment_delete', 'Soft delete assessment #6', '2025-10-06 13:02:54');
INSERT INTO `log` VALUES ('26', '1', 'assessment_delete', 'Soft delete assessment #5', '2025-10-06 13:02:56');
INSERT INTO `log` VALUES ('27', '1', 'remove_reviewer', 'Remove reviewer #7 from document #2', '2025-10-06 13:47:23');
INSERT INTO `log` VALUES ('28', '1', 'assign_reviewer', 'Assign reviewer #7 to document #2', '2025-10-06 13:47:37');
INSERT INTO `log` VALUES ('29', '1', 'remove_reviewer', 'Remove reviewer #7 from document #2', '2025-10-06 13:47:41');
INSERT INTO `log` VALUES ('30', '1', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 13:47:49');
INSERT INTO `log` VALUES ('31', '1', 'assign_reviewer', 'Assign reviewer #5 to document #6', '2025-10-06 13:47:53');
INSERT INTO `log` VALUES ('32', '1', 'assign_reviewer', 'Assign reviewer #5 to document #5', '2025-10-06 13:48:03');
INSERT INTO `log` VALUES ('33', '1', 'assign_reviewer', 'Assign reviewer #6 to document #4', '2025-10-06 13:48:08');
INSERT INTO `log` VALUES ('34', '1', 'assign_reviewer', 'Assign reviewer #6 to document #3', '2025-10-06 13:48:13');
INSERT INTO `log` VALUES ('35', '1', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:13:18');
INSERT INTO `log` VALUES ('36', '1', 'remove_reviewer', 'Remove reviewer #5 from document #7', '2025-10-06 14:46:18');
INSERT INTO `log` VALUES ('37', '1', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:46:22');
INSERT INTO `log` VALUES ('38', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:46:39');
INSERT INTO `log` VALUES ('39', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:46:42');
INSERT INTO `log` VALUES ('40', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:46:43');
INSERT INTO `log` VALUES ('41', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:46:44');
INSERT INTO `log` VALUES ('42', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:47:04');
INSERT INTO `log` VALUES ('43', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:47:06');
INSERT INTO `log` VALUES ('44', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:49:40');
INSERT INTO `log` VALUES ('45', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:49:43');
INSERT INTO `log` VALUES ('46', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:49:45');
INSERT INTO `log` VALUES ('47', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:49:46');
INSERT INTO `log` VALUES ('48', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:49:47');
INSERT INTO `log` VALUES ('49', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:56:25');
INSERT INTO `log` VALUES ('50', '1', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 14:57:04');
INSERT INTO `log` VALUES ('51', '1', 'assign_reviewer', 'Assign reviewer #5 to document #6', '2025-10-06 14:57:08');
INSERT INTO `log` VALUES ('52', '1', 'assign_reviewer', 'Assign reviewer #5 to document #5', '2025-10-06 14:57:13');
INSERT INTO `log` VALUES ('53', '1', 'assign_reviewer', 'Assign reviewer #5 to document #4', '2025-10-06 14:57:17');
INSERT INTO `log` VALUES ('54', '1', 'assign_reviewer', 'Assign reviewer #5 to document #3', '2025-10-06 14:57:22');
INSERT INTO `log` VALUES ('55', '1', 'remove_reviewer', 'Remove reviewer #6 from document #3', '2025-10-06 14:57:30');
INSERT INTO `log` VALUES ('56', '1', 'remove_reviewer', 'Remove reviewer #6 from document #4', '2025-10-06 14:57:34');
INSERT INTO `log` VALUES ('57', '1', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-06 15:03:32');
INSERT INTO `log` VALUES ('58', '1', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-07 01:51:19');
INSERT INTO `log` VALUES ('59', '1', 'document_review', 'Reviewed document ID 7, status: PENDING', '2025-10-07 01:53:12');
INSERT INTO `log` VALUES ('60', '1', 'document_review', 'Reviewed document ID 7, status: PENDING', '2025-10-07 01:53:38');
INSERT INTO `log` VALUES ('61', '1', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-07 01:56:05');
INSERT INTO `log` VALUES ('62', '5', 'assign_reviewer', 'Assign reviewer #5 to document #7', '2025-10-07 02:09:10');
INSERT INTO `log` VALUES ('63', '5', 'document_review', 'Reviewed document ID 7, status: PASS', '2025-10-07 02:11:36');
INSERT INTO `log` VALUES ('64', '1', 'assign_reviewer', 'Assign reviewer #5 to document #10', '2025-10-07 02:37:11');
INSERT INTO `log` VALUES ('65', '5', 'document_review', 'Reviewed document ID 10, status: PASS', '2025-10-07 02:37:42');
INSERT INTO `log` VALUES ('66', '1', 'assign_role', 'Set role of user #7 to reviewer', '2025-10-07 02:53:17');
INSERT INTO `log` VALUES ('67', '1', 'assign_role', 'Set role of user #8 to evaluator', '2025-10-07 02:53:22');
INSERT INTO `log` VALUES ('68', '1', 'assign_role', 'Set role of user #7 to reviewer', '2025-10-07 02:53:26');
INSERT INTO `log` VALUES ('69', '1', 'assign_role', 'Set role of user #6 to reviewer', '2025-10-07 02:53:29');
INSERT INTO `log` VALUES ('70', '1', 'assign_role', 'Set role of user #5 to reviewer', '2025-10-07 02:53:31');
INSERT INTO `log` VALUES ('71', '1', 'assign_role', 'Set role of user #6 to reviewer', '2025-10-07 02:53:32');
INSERT INTO `log` VALUES ('72', '1', 'assign_role', 'Set role of user #6 to reviewer', '2025-10-07 02:53:33');

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `document_id` int DEFAULT NULL,
  `message` text NOT NULL,
  `event_type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of notifications
-- ----------------------------
INSERT INTO `notifications` VALUES ('2', '7', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร \'คู่ฉบับขอใช้วิจัย .docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '0', '2025-10-06 13:47:37');
INSERT INTO `notifications` VALUES ('3', '5', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร \'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '0', '2025-10-06 13:47:49');
INSERT INTO `notifications` VALUES ('4', '5', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร \'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '0', '2025-10-06 13:47:53');
INSERT INTO `notifications` VALUES ('5', '5', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร \'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '0', '2025-10-06 13:48:03');
INSERT INTO `notifications` VALUES ('6', '6', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร \'00_แนวทางสรุป_กำหนดตำแหน่งสายงานวิทยฯ69.docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '0', '2025-10-06 13:48:08');
INSERT INTO `notifications` VALUES ('7', '6', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร \'คู่ฉบับขอใช้วิจัย .docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '0', '2025-10-06 13:48:13');
INSERT INTO `notifications` VALUES ('8', '5', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร #7 \'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '1', '2025-10-06 14:46:22');
INSERT INTO `notifications` VALUES ('9', '5', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร #4 \'00_แนวทางสรุป_กำหนดตำแหน่งสายงานวิทยฯ69.docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '1', '2025-10-06 14:57:17');
INSERT INTO `notifications` VALUES ('10', '5', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร #3 \'คู่ฉบับขอใช้วิจัย .docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '1', '2025-10-06 14:57:22');
INSERT INTO `notifications` VALUES ('11', '5', null, 'เอกสาร \'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx\' ในหมวด \'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)\' ได้รับการตรวจสอบแล้ว สถานะ: รอตรวจสอบ', null, '0', '2025-10-07 01:53:12');
INSERT INTO `notifications` VALUES ('12', '5', null, 'เอกสาร \'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx\' ในหมวด \'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)\' ได้รับการตรวจสอบแล้ว สถานะ: รอตรวจสอบ', null, '0', '2025-10-07 01:53:38');
INSERT INTO `notifications` VALUES ('13', '5', null, 'คุณได้รับมอบหมายให้ตรวจเอกสาร #7 \'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx\' กรุณาเข้าระบบเพื่อรับงาน', null, '1', '2025-10-07 02:09:10');
INSERT INTO `notifications` VALUES ('14', '5', null, 'เอกสาร \'แบบฟอร์ม_3_หนังสือรับรองการนำไปใช้.docx\' ในหมวด \'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)\' ได้รับการตรวจสอบแล้ว สถานะ: อนุมัติ', null, '0', '2025-10-07 02:11:36');
INSERT INTO `notifications` VALUES ('15', '5', '10', 'คุณได้รับมอบหมายให้ตรวจเอกสาร #10 \'00_แนวทางสรุป_กำหนดตำแหน่งสายงานวิทยฯ69.docx\' กรุณาเข้าระบบเพื่อรับงาน', 'doc_assigned', '1', '2025-10-07 02:37:11');
INSERT INTO `notifications` VALUES ('16', '2', '10', 'เอกสาร \'00_แนวทางสรุป_กำหนดตำแหน่งสายงานวิทยฯ69.docx\' ในหมวด \'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562\' ได้รับการตรวจสอบแล้ว สถานะ: อนุมัติ', 'doc_reviewed', '0', '2025-10-07 02:37:42');

-- ----------------------------
-- Table structure for questions
-- ----------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `text` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `weight` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of questions
-- ----------------------------
INSERT INTO `questions` VALUES ('129', 'D1-1', 'มีการดำเนินการให้เป็นไปตามนโยบายและแผนว่าด้วย การรักษาความมั่นคงปลอดภัยไซเบอร์', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '5');
INSERT INTO `questions` VALUES ('130', 'D1-2', 'มีการจัดทำประมวลแนวทางปฏิบัติและกรอบมาตรฐาน\nด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ที่สอดคล้องกับ\nนโยบายและแผนว่าด้วยการรักษาความมั่นคงปลอดภัย\nไซเบอร์', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '5');
INSERT INTO `questions` VALUES ('131', 'D1-3', 'มีการป้องกัน รับมือ และลดความเสี่ยงจากภัยคุกคามทาง\nไซเบอร์ตามประมวลแนวทางปฏิบัติและกรอบมาตรฐาน\nด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ของแต่ละ\nหน่วยงาน และจะต้องดำเนินการให้เป็นไปตามประมวล\nแนวทางปฏิบัติและกรอบมาตรฐานด้านการรักษาความ\nมั่นคงปลอดภัยไซเบอร์', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '3');
INSERT INTO `questions` VALUES ('132', 'D1-4', 'มีการแจ้งรายชื่อเจ้าหน้าที่ระดับบริหารและระดับปฏิบัติการ\nเพื่อประสานงานด้านการรักษาความมั่นคงปลอดภัยไซเบอร์\nไปยัง สกมช . รวมถึงแจ้งปรับปรุงข้อมูลกรณีมีการเปลี่ยนแปลง', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '1');
INSERT INTO `questions` VALUES ('133', 'D1-5', 'มีการแจ้งรายชื่อและข้อมูลการติดต่อของเจ้าของกรรมสิทธิ์\nผู้ครอบครองคอมพิวเตอร์ และผู้ดูแลระบบคอมพิวเตอร์\nไปยัง สกมช หน่วยงานควบคุมหรือกำกับดูแลของตน และ\nหน่วยงานตามมาตรา 50 (Sectoral CERT) ภายใน 30 วัน\nนับแต่วันที่คณะกรรมการประกาศ', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '1');
INSERT INTO `questions` VALUES ('134', 'D1-6', 'มีการเปลี่ยนแปลงรายชื่อและข้อมูลการติดต่อของเจ้าของ\nกรรมสิทธิ์ ผู้ครอบครองคอมพิวเตอร์ และผู้ดูแลระบบ\nคอมพิวเตอร์ หน่วยงานได้แจ้งปรับปรุงข้อมูลไปยัง สกมช .', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '1');
INSERT INTO `questions` VALUES ('135', 'D1-7', 'มีการประเมินความเสี่ยงด้านการรักษาความมั่นคงปลอดภัย\nไซเบอร์โดยมีผู้ตรวจประเมิน รวมทั้งมีการตรวจสอบด้าน\nความมั่นคงปลอดภัยไซเบอร์โดยผู้ตรวจสอบด้านความมั่นคง\nปลอดภัยสารสนเทศ ทั้งโดยผู้ตรวจสอบภายในหรือโดย ผู้\nตรวจสอบอิสระภายนอก อย่างน้อยปีละ 1 ครั้ง', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '1');
INSERT INTO `questions` VALUES ('136', 'D1-8', 'มีการจัดส่งผลสรุปรายงานการดำเนินการ (การ ประเมิน\nความเสี่ยงฯ และการตรวจสอบ ) ต่อ สกมช . ภายใน 30 วัน\nนับแต่วันที่ดำเนินการแล้วเสร็จ', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '1');
INSERT INTO `questions` VALUES ('137', 'D1-9', 'มีกลไกหรือขั้นตอนเพื่อการเฝ้าระวังภัยคุกคามทางไซเบอร์\nหรือเหตุการณ์ที่เกี่ยวกับความมั่นคงปลอดภัยไซเบอร์ที่\nเกี่ยวข้องกับโครงสร้างพื้นฐานสำคัญทางสารสนเทศของตน\nตามมาตรฐานซึ่งกำหนดโดยหน่วยงานควบคุมหรือกำกับ\nดูแล และตามประมวลแนวทางปฏิบัติ รวมถึงระบบ\nมาตรการที่ใช้แก้ปัญหาเพื่อรักษาความมั่นคงปลอดภัย\nไซเบอร์ที่คณะกรรมการหรือ กกม . กำหนด', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '1');
INSERT INTO `questions` VALUES ('138', 'D1-10', 'มีการเข้าร่วมการทดสอบสถานะความพร้อมในการรับมือกับ\nภัยคุกคามทางไซเบอร์ที่ สกมช . จัดขึ้น เพื่อให้มั่นใจว่า\nหน่วยงานสามารถตอบสนองต่อภัยคุกคามทางไซเบอร์หรือ\nเหตุการณ์ที่เกี่ยวกับความมั่นคงปลอดภัยไซเบอร์ได้', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '1');
INSERT INTO `questions` VALUES ('139', 'D1-11', 'มีกระบวนงานหรือขั้นตอนปฏิบัติ ในการรายงานต่อ สกมช\nและหน่วยงานควบคุมหรือกำกับดูแล เมื่อมีเหตุภัยคุกคาม\nทางไซเบอร์เกิดขึ้นอย่างมีนัยสำคัญต่อระบบของหน่วยงาน\nและปฏิบัติการรับมือกับภัยคุกคามทางไซเบอร์', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '1');
INSERT INTO `questions` VALUES ('140', 'D1-12', 'หากเกิดหรือคาดว่าจะเกิดภัยคุกคามทางไซเบอร์หน่วยงาน\nของท่านได้ปฏิบัติการรับมือกับภัยคุกคามทางไซเบอร์\nตามที่กำหนดในมาตรา 58 ของ พระราชบัญญัติการรักษา\nความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562 ได้แก่\n(1) ในกรณีที่เกิดหรือคาดว่าจะเกิดภัยคุกคามทางไซเบอร์\nต่อระบบสารสนเทศซึ่งอยู่ในความดูแลรับผิดชอบ\nของหน่วยงาน หน่วยงานของท่านได้ดำเนินการ\nตรวจสอบข้อมูลที่เกี่ยวข้องข้อมูลคอมพิวเตอร์และระบบ\nคอมพิวเตอร์ รวมถึงพฤติการณ์แวดล้อมของตน\nเพื่อประเมินว่ามีภัยคุกคามทางไซเบอร์เกิดขึ้น\n(2) หน่วยงานของท่านได้ดำเนินการป้องกัน รับมือ\nและลดความเสี่ยงจากภัยคุกคามทางไซเบอร์\nตามประมวลแนวทางปฏิบัติและกรอบมาตรฐาน\nด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ของหน่วยงาน\n(3) หน่วยงานของท่านได้แจ้งไปยังสำนักงานและหน่วยงาน\nควบคุมหรือกำกับดูแลของตนโดยเร็ว', 'หมวดหมู่ D1 : พระราชบัญญัติการรักษาความมั่นคงปลอดภัยไซเบอร์ พ.ศ. 2562', '3731', '2');
INSERT INTO `questions` VALUES ('142', 'D2-13', 'มีการจัดโครงสร้างองค์กรให้มีการถ่วงดุล โดยจัดโครงสร้าง\nองค์กรพร้อมกำหนดอำนาจ บทบาทหน้าที่ และความ\nรับผิดชอบ (Authorities, Roles and Responsibilities) ที่ชัดเจน เกี่ยวกับการบริหารจัดการความมั่นคงปลอดภัยไซเบอร์ให้มีการถ่วงดุลตามหลักการควบคุม กำกับ และตรวจสอบ (Three Lines of Defense)', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('143', 'D2-14', 'หน่วยงานมีผู้บริหารระดับสูงที่ทำหน้าที่บริหารจัดการความ\nมั่นคงปลอดภัยสารสนเทศ ( Chief Information Security Officer : CISO) หรือเทียบเท่าที่ปฏิบัติหน้าที่เสมือน CISO ของหน่วยงาน', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('144', 'D2-15', 'ผู้บริหารระดับสูงที่ทำหน้าที่บริหารจัดการความมั่นคง\nปลอดภัยสารสนเทศมีความเป็นอิสระจากงานด้านการปฏิบัติงานเทคโนโลยีสารสนเทศ (IT operation) และ งานด้านพัฒนาระบบเทคโนโลยีสารสนเทศ (IT Development) และมีอำนาจหน้าที่ (Authority) เพียงพอในการปฏิบัติงานในหน้าที่ CISO ได้อย่างมีประสิทธิภาพและประสิทธิผล', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('145', 'D2-16', 'มีการจัดทำกรอบการบริหารความเสี่ยงด้านความมั่นคง\nปลอดภัยไซเบอร์เป็นลายลักษณ์อักษร', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('146', 'D2-17', 'มีเกณฑ์ประเมินความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์\nและระดับความเสี่ยงที่ยอมรับได้ Risk Appetite)', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('147', 'D2-18', 'มีวิธีการประเมินความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('148', 'D2-19', 'มีการเฝ้าระวังและติดตามความเสี่ยงด้านความมั่นคง\nปลอดภัยไซเบอร์', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('149', 'D2-20', 'มีการเก็บรักษารายการความเสี่ยงด้านการรักษา\nความมั่นคงปลอดภัยไซเบอร์ที่ระบุไว้ในทะเบียนความเสี่ยง\n(Risk Register) ที่เกี่ยวข้องกับบริการที่สำคัญของหน่วยงาน', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('150', 'D2-21', 'มีการติดตามความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์\nที่ระบุไว้อย่างสม่ำเสมอเพื่อให้แน่ใจว่าอยู่ภายใต้เกณฑ์ระดับ\nความเสี่ยงที่ยอมรับได้', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('151', 'D2-22', 'มีการกำหนดและอนุมัตินโยบาย มาตรฐาน และแนวทาง\nในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์\nและการป้องกันบริการที่สำคัญของหน่วยงาน จากภัย\nคุกคามทางไซเบอร์', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('152', 'D2-23', 'มีนโยบายมาตรฐาน และแนวปฏิบัติในการจัดการ\nความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกัน\nบริการที่สำคัญของหน่วยงาน ที่มีความสอดคล้องกับหลัก\nประมวลแนวทางปฏิบัติที่คณะกรรมการกำหนด ข้อกำหนด\nการรักษาความมั่นคงปลอดภัยไซเบอร์ของภาคส่วน\nและนโยบาย มาตรฐาน และทิศทางการรักษาความมั่นคง\nปลอดภัยไซเบอร์ระดับภูมิภาค หรือระดับประเทศ', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('153', 'D2-24', 'มีนโยบาย มาตรฐาน และแนวปฏิบัติที่มีการเผยแพร่และ\nสื่อสารไปยังบุคลากรและบุคคลภายนอกทุกคนที่ทำหน้าที่\nหรือสามารถเข้าถึงบริการที่สำคัญของหน่วยงาน', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('154', 'D2-25', 'มีการทบทวนนโยบาย มาตรฐาน และแนวทางปฏิบัติ\nกับสภาพแวดล้อมการปฏิบัติการไซเบอร์ของบริการที่สำคัญ\nของหน่วยงาน และภูมิทัศน์ภัยคุกคามทางไซเบอร์ในปัจจุบัน\nอย่างน้อยปีละ 1 ครั้ง', 'หมวดหมู่ D2 : นโยบายบริหารจัดการ ประกอบนโยบายและแผนปฏิบัติการว่าด้วยการรักษาความมั่นคงปลอดภัยไซเบอร์ (พ.ศ. 2560 - 2570)', '3762', '1');
INSERT INTO `questions` VALUES ('155', 'D3-26', 'มีการตรวจสอบด้านความมั่นคงปลอดภัยไซเบอร์\nโดยผู้ตรวจสอบด้านความมั่นคงปลอดภัยสารสนเทศ\nทั้งโดยผู้ตรวจสอบภายใน หรือผู้ตรวจสอบอิสระภายนอก\nอย่างน้อยปีละ 1 ครั้ง', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('156', 'D3-27', 'มีการตรวจสอบในเรื่องกระบวนการจัดทำและผลการ\nวิเคราะห์ผลกระทบทางธุรกิจ ( Business Impact Analysis: BIA)', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('157', 'D3-28', 'มีการตรวจสอบในเรื่องบริการที่สำคัญที่หน่วยงานเป็นเจ้าของและใช้บริการ ตามผลการวิเคราะห์ผลกระทบทางธุรกิจ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '5');
INSERT INTO `questions` VALUES ('158', 'D3-29', 'มีการตรวจสอบในเรื่องการปฏิบัติตามประมวลแนวทาง\nปฏิบัติ มาตรฐานการปฏิบัติงาน และที่ กมช. กำหนด', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '4');
INSERT INTO `questions` VALUES ('159', 'D3-30', 'หน่วยงานของท่านได้จัดส่งผลสรุปรายงานการตรวจสอบ\nด้านความมั่นคงปลอดภัยไซเบอร์ต่อ สกมช . ภายในกำหนด\n30 วัน นับแต่วันที่ดำเนินการแล้วเสร็จ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('160', 'D3-31', 'มีการจัดส่งสำเนาผลสรุปรายงานการตรวจสอบด้านความ\nมั่นคงปลอดภัยไซเบอร์ ตามข้อ 30 ต่อหน่วยงานควบคุม\nหรือกำกับดูแล', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('161', 'D3-32', 'ในกรณีที่การตรวจสอบดำเนินการภายใต้มาตรา 54\nระบุการไม่ปฏิบัติตามข้อ 17.1 เว้นแต่ กกม. จะระบุเป็น\nลายลักษณ์อักษรเป็นอย่างอื่น ให้หน่วยงานส่งแผนการ\nดำเนินการแก้ไขไปยัง สกมช. ภายในกำหนด 30 (สามสิบ ) วันนับแต่จากวันที่ได้รับรายงานการตรวจสอบ โดยแผนการ\nดำเนินการแก้ไขต้องมีรายละเอียดอย่างน้อย ประกอบด้วย\nรายละเอียดการดำเนินการแก้ไขที่หน่วยงานจะดำเนินการ\nเพื่อจัดการกับการไม่ปฏิบัติตาม และกำหนด ระยะเวลา\nสำหรับการดำเนินการแก้ไข', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('162', 'D3-33', 'ในกรณีที่ กกม เห็นสมควรให้ปรับปรุงแผนการ\nดำเนินการแก้ไข ให้หน่วยงานดำเนินการและส่งแผนการ\nดำเนินการแก้ไขที่ได้รับการปรับปรุงแล้วไปยัง สกมช\nภายในระยะเวลาที่ กกม . กำหนด พร้อมทั้ง ส่งสำเนาให้\nหน่วยงานควบคุมหรือกำกับดูแล ด้วย', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('163', 'D3-34', 'เมื่อแผนการดำเนินการแก้ไขได้รับความเห็นชอบจาก กกม หน่วยงาน CII จะ ดำเนินการตามแผนการดำเนินการแก้ไข\nดังกล่าว และดำเนินการแก้ไขทั้งหมดให้แล้วเสร็จภายใน\nกำหนดระยะเวลาตามที่ระบุไว้ เพื่อให้ผ่านเกณฑ์การ\nพิจารณาของ กกม .', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('164', 'D3-35', 'หน่วยงานของท่านได้กำหนดนโยบายการบริหารความ\nเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ ตามที่\nระบุไว้ในนโยบายบริหารจัดการที่เกี่ยวกับการรักษา\nความมั่นคงปลอดภัยไซเบอร์สำหรับหน่วยงานของรัฐ\nและหน่วยงานโครงสร้างพื้นฐานสำคัญทางสารสนเทศ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('165', 'D3-36', 'นโยบายการบริหารความเสี่ยงด้านการรักษาความมั่นคง\nปลอดภัยไซเบอร์มีเนื้อหาที่ครอบคลุมเรื่องโครงสร้าง\nองค์กรและบทบาทหน้าที่ของผู้ที่เกี่ยวข้องในการบริหาร\nความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('166', 'D3-37', 'มีการจัดทำระเบียบวิธีปฏิบัติและกระบวนการในการบริหาร\nความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('167', 'D3-38', 'มีการประเมินความเสี่ยงด้านการรักษาความมั่นคงปลอดภัย\nไซเบอร์อย่างน้อยปีละ 1 ครั้ง', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('168', 'D3-39', 'มีการระบุถึงความเสี่ยง (Risk Identification) ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ ซึ่งรวมถึงความเสี่ยงจากภัยคุกคามทางไซเบอร์ และช่องโหว่ต่างๆ และพิจารณาสาเหตุความเสี่ยงจากกกระบวนการปฏิบัติงานระบบงาน บุคลากร หรือปัจจัยภายนอก', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('169', 'D3-40', 'มีการวิเคราะห์ความเสี่ยง (Risk Analysis) ด้านการรักษา\nความมั่นคงปลอดภัยไซเบอร์ เพื่อหาแนวทางในการจัดการ\nความเสี่ยงที่เหมาะสม', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('170', 'D3-41', 'มีการประเมิน ถึงโอกาสที่ ความเสี่ยง (Risk Evaluation) ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์จะเกิดขึ้น และ\nผลกระทบต่อการปฏิบัติงานและการดำเนินธุรกิจ รวมถึง\nกำหนดระดับความเสี่ยงด้านการรักษาความมั่นคงปลอดภัย\nไซเบอร์ที่ยอมรับได้ (Risk Appetite)', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('171', 'D3-42', 'มีแนวทางจัดการ ควบคุม และป้องกันความเสี่ยงที่เหมาะสมสอดคล้องกับผลการประเมินความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ เพื่อให้ความเสี่ยงที่เหลืออยู่ (Residual Risk) อยู่ในระดับความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ที่ยอมรับได้', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('172', 'D3-43', 'มีการกำหนดดัชนีชี้วัดความเสี่ยงที่สำคัญ (Key Risk Indicator: KRI) ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ ที่เกี่ยวข้องกับการดำเนินธุรกิจ ให้สอดคล้องกับความสำคัญของความมั่นคงปลอดภัยไซเบอร์แต่ละงาน', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('173', 'D3-44', 'มีกระบวนการที่มีประสิทธิภาพในการติดตามและทบทวน\nความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '5');
INSERT INTO `questions` VALUES ('174', 'D3-45', 'มีการรายงานระดับความเสี่ยงและผลการบริหารความเสี่ยง\nด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ต่อคณะกรรมการของหน่วยงานที่ได้รับมอบหมายเป็นประจำ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('175', 'D3-46', 'มีการทบทวนระเบียบวิธีปฏิบัติและกระบวนการบริหาร\nความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์\nอย่างน้อยปีละ 1 ครั้ง', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('176', 'D3-47', 'มีการจัดทำแผนการรับมือภัยคุกคามทางไซเบอร์มีการ\nจัดทำแผนการรับมือภัยคุกคามทางไซเบอร์\n(Cybersecurity Incident Response Plan)', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('177', 'D3-48', 'มีการสื่อสารแผนการรับมือภัยคุกคามทางไซเบอร์ไปยัง\nบุคลากรที่เกี่ยวข้องทั้งหมด อย่างมีประสิทธิผล', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('178', 'D3-49', 'มีการทบทวนแผนการรับมือภัยคุกคามทางไซเบอร์\nอย่างน้อยปีละ 1 ครั้ง โดยนับแต่วันที่แผนได้รับการอนุมัติ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('179', 'D3-50', 'มีการทบทวนแผนการรับมือภัยคุกคามทางไซเบอร์ เมื่อมี\nการเปลี่ยนแปลงอย่างมีนัยสำคัญ ในสภาพแวดล้อมการ\nปฏิบัติการทางไซเบอร์ของบริการที่สำคัญของหน่วยงาน ของรัฐและหน่วยงานโครงสร้างพื้นฐานสำคัญทางสารสนเทศหรือข้อกำหนดในการตอบสนองต่อเหตุการณ์ที่เกี่ยวกับความมั่นคงปลอดภัยไซเบอร์', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('180', 'D3-51', 'หน่วยงานของท่านมีทะเบียนทรัพย์สิน ที่ระบุทรัพย์สินของ\nบริการที่สำคัญและดูแลรักษาทะเบียนทรัพย์สินให้เป็น\nปัจจุบัน ทั้งนี้ทะเบียนทรัพย์สินของบริการที่สำคัญต้องมี', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '5');
INSERT INTO `questions` VALUES ('181', 'D3-52', 'มีการระบุขอบเขตเครือข่ายของบริการที่สำคัญและระบบ\nคอมพิวเตอร์ที่เชื่อมต่อโดยตรงและมีนัยสำคัญ Direct and Significant Interface)', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('182', 'D3-53', 'มีการตรวจสอบทะเบียนทรัพย์สินอย่างน้อยปีละ 1 ครั้ง\nหากมีการเปลี่ยนแปลงใด ๆ กับทรัพย์สินของบริการที่สำคัญ\nให้ปรับปรุงทะเบียนทรัพย์สินดังกล่าวด้วย', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('183', 'D3-54', 'มีการประเมินความเสี่ยงด้านการรักษาความมั่นคงปลอดภัย\nไซเบอร์ของบริการที่สำคัญ ตามรายการที่ระบุไว้ในทะเบียน\nทรัพย์สิน ที่ระบุบริการสำคัญ อย่างน้อยปีละ 1 ครั้ง', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('184', 'D3-55', 'มีการประเมินความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์\nอย่างน้อยปีละ 1 ครั้ง หรือ เมื่อมีการเปลี่ยนแปลงที่สำคัญ\nตามเกณฑ์ประเมินความเสี่ยงด้านการรักษาความมั่นคง\nปลอดภัยไซเบอร์ที่กำหนดไว้สำหรับการบริหารความเสี่ยง\n(Risk Management) ตามนโยบายบริหารจัดการที่เกี่ยวกับ\nการรักษาความมั่นคงปลอดภัยไซเบอร์ที่คณะกรรมการ\nประกาศกำหนด', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('185', 'D3-56', 'มีการปรับปรุงทะเบียนความเสี่ยงทุกครั้งหลังการประเมิน\nความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('186', 'D3-57', 'มีการประเมินช่องโหว่โดยครอบคลุมบริการที่สำคัญซึ่งเป็น\nระบบเทคโนโลยีสารสนเทศ Information Technology System) หรือระบบที่ใช้ควบคุมเครื่องจักรในอุตสาหกรรม\nIndustrial Control System: ICS)', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('187', 'D3-58', 'หน่วยงานของท่านได้ระบุขอบเขตของการประเมินช่องโหว่\nของบริการที่สำคัญหรือไม่ (โดยขอบเขตดังกล่าวต้อง\nครอบคลุมการประเมินความมั่นคงปลอดภัยของโฮสต์\nเครือข่าย และสถาปัตยกรรม)', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('188', 'D3-59', 'หน่วยงานของท่านได้ประเมินช่องโหว่ของบริการที่สำคัญ\nก่อนที่จะทำการทดสอบระบบใหม่ที่จะเชื่อมต่อ หรือ\nดำเนินการเปลี่ยนแปลงระบบที่สำคัญกับบริการที่สำคัญ\nหรือไม่\nหมายเหตุ การเปลี่ยนแปลงระบบที่สำคัญ ได้แก่ การ\nเพิ่มโมดูลแอปพลิเคชัน Adding New Application Module) การปรับปรุงระบบ และการปรับเปลี่ยนเทคโนโลยี ทั้งนี้รวมถึงการเปลี่ยนไปใช้ระบบใหม่แทนที่\nระบบเดิมด้วย', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('189', 'D3-60', 'มีการพิจารณาดำเนินการทดสอบเจาะระบบ\n(Penetration Testing) โดยเฉพาะอย่างยิ่ง ระบบเทคโนโลยีสารสนเทศที่เชื่อมต่อกับอินเทอร์เน็ต Internet Facing) ให้สอดคล้องกับระดับของความเสี่ยง และพิจารณาผลกระทบหรือความเสี่ยงจากการทดสอบเจาะระบบด้วย', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('190', 'D3-61', 'มีการตรวจสอบให้แน่ใจว่าขอบเขตของการทดสอบเจาะ\nระบบ Scope of a Penetration Test) ได้รวมถึงการ\nทดสอบเจาะระบบของโฮสต์ เครือข่าย และแอปพลิเคชัน\nของบริการที่สำคัญ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('191', 'D3-62', 'มีการดำเนินการทดสอบเจาะระบบอย่างน้อยปีละ\n1 ครั้ง', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('192', 'D3-63', 'มีการตรวจสอบเพื่อให้แน่ใจว่าการทดสอบเจาะระบบ\nและผู้ทดสอบเจาะระบบ Penetration Testers) ที่ทำการ\nทดสอบเจาะระบบบนโครงสร้างพื้นฐานสำคัญสารสนเทศ\nมีการรับรองและได้รับประกาศนียบัตร (Accreditations and Certifications) ที่เป็นที่ยอมรับในอุตสาหกรรม และ\nเป็นอิสระ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('193', 'D3-64', 'มีการตรวจสอบให้แน่ใจว่าการทดสอบเจาะระบบทั้งหมด\nโดยผู้ให้บริการทดสอบเจาะระบบดำเนินการภายใต้การ\nดูแลของหน่วยงาน', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('194', 'D3-65', 'มีกระบวนการเพื่อติดตามและจัดการกับช่องโหว่\nที่ระบุในผลการประเมินช่องโหว่และในผลการทดสอบเจาะระบบและตรวจสอบว่าช่องโหว่ที่ระบุทั้งหมดได้รับการแก้ไขอย่างเพียงพอ (ทั้งนี้หมายรวมถึง ช่องโหว่ของซอฟต์แวร์ ฮาร์ดแวร์และระบบต่าง ๆ ที่ควบคุมการเข้าถึงทางกายภาพ)', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '15');
INSERT INTO `questions` VALUES ('195', 'D3-66', 'มีการส่งสำเนารายงานสรุปผลการทดสอบเจาะระบบ\nให้ กกม หรือ สกมช . ทราบภายใน 30 วัน นับจากที่ได้รับ\nหนังสือร้องขอ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('196', 'D3-67', 'หน่วยงานของท่านได้ระบุเกี่ยวกับความรับผิดชอบ\nและภาระรับผิดชอบ (Responsible, Accountable)\nของผู้ให้บริการภายนอกที่ให้บริการด้านเทคโนโลยี\nสารสนเทศ หรือด้านเทคโนโลยีด้านการปฏิบัติการ\n(Operational Technology) ในสัญญาการจัดซื้อจัดจ้าง', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('197', 'D3-68', 'หน่วยงานของท่านมีข้อกำหนดด้านความมั่นคงปลอดภัยไซเบอร์ของผู้ให้บริการภายนอกในข้อตกลงระดับการให้บริการ (Service Level Agreement) หรือเงื่อนไขของสัญญากับผู้ให้บริการภายนอก หรือไม่', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('198', 'D3-69', 'มีกระบวนการตรวจสอบความถูกต้องของผู้ให้บริการ\nภายนอกว่าสอดคล้องกับข้อกำหนดด้านความมั่นคง\nปลอดภัยไซเบอร์ในเงื่อนไขของสัญญา', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('199', 'D3-70', 'หน่วยงานของท่านได้ดำเนินการเจรจาต่อรองเงื่อนไข\nของสัญญาจ้างให้สอดคล้องกับกรณีที่มีข้อกำหนด\nทางกฎหมายหรือข้อบังคับใหม่', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('200', 'D3-71', 'มีการจำกัดการเข้าถึงบริการที่สำคัญเฉพาะบุคลากรกิจกรรม อุปกรณ์ และอินเทอร์เฟซ ที่ได้รับอนุญาตเท่านั้น', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '9');
INSERT INTO `questions` VALUES ('201', 'D3-72', 'มีการใช้เทคนิคการตรวจสอบสิทธิ์ที่สอดคล้องกับโปรไฟล์\nความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์\n(Cybersecurity Risk Profile) สำหรับแต่ละโหมดการ\nเข้าถึงบริการที่สำคัญ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('202', 'D3-73', 'มีการเก็บรักษาบันทึกของการเข้าถึงทั้งหมด Logs of All Accesses) และความพยายามทั้งหมดในการเข้าถึงบริการที่\nสำคัญและตรวจสอบบันทึกเหล่านี้เพื่อหากิจกรรมที่ผิดปกติ\nเป็นประจำ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('203', 'D3-74', 'มีการตรวจสอบให้แน่ใจว่าการเข้าถึงอินเทอร์เฟซ\n(Interface) ของบริการที่สำคัญ และการเข้าถึงทางลอจิคอล\nLogical) มีการกำกับดูแลโดยหน่วยงาน', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '5');
INSERT INTO `questions` VALUES ('204', 'D3-75', 'มีมาตรฐานการกำหนดค่าขั้นต่ำด้านความมั่นคงปลอดภัย\n(Security Baseline Configuration Standards) สำหรับ\nระบบปฏิบัติการ แอปพลิเคชัน และอุปกรณ์เครือข่าย\nทั้งหมดของบริการที่สำคัญ ที่สอดคล้องกับโปรไฟล์ความ\nเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์\nCybersecurity Risk Profile)', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '4');
INSERT INTO `questions` VALUES ('205', 'D3-76', 'มีมาตรฐานการกำหนดค่าขั้นต่ำด้านความมั่นคงปลอดภัย\nSecurity Baseline Configuration Standards) ต้องมี\nหลักการรักษาความมั่นคงปลอดภัยอย่างน้อย ต่อไปนี้\n(ก ) สิทธิพิเศษในการเข้าถึงน้อยที่สุด Least Access Privilege) (ข ) การแบ่งแยกหน้าที่ Separation of Duties) (ค ) การบังคับใช้นโยบายความซับซ้อนของรหัสผ่าน\n(ง ) การลบบัญชีที่ไม่ได้ใช้\n(จ ) การลบบริการและแอปพลิเคชันที่ไม่จำเป็น เช่น การ\nลบคอมไพเลอร์ (Removal of Compiler) และ\nแอปพลิเคชันสนับสนุนผู้ให้บริการภายนอก Vendor Support Application) (ฉ ) การปิดพอร์ตเครือข่ายที่ไม่ได้ใช้งาน\n(ช ) การป้องกันมัลแวร์ Malware) และ\n(ซ ) การปรับปรุงซอฟต์แวร์และแพตช์ Patch) ความ\nมั่นคงปลอดภัยของระบบอย่างทันการณ์และเหมาะสม', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('206', 'D3-77', 'มีการตรวจสอบให้แน่ใจว่ามีการใช้มาตรฐานการกำหนด\nค่าขั้นต่ำด้านความมั่นคงปลอดภัย (Security Baseline Configuration Standards) ตามที่ระบุไว้ ก่อนที่จะมี\nทรัพย์สินใด เชื่อมต่อหรือเมื่อมีการเปลี่ยนแปลงหรือ\nปรับปรุงบริการที่สำคัญ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('207', 'D3-78', 'มีการตรวจสอบมาตรฐานการกำหนดค่าขั้นต่ำด้านความ\nมั่นคงปลอดภัย (Security Baseline Configuration Standard) ของบริการที่สำคัญ อย่างน้อยปีละ 1 ครั้ง\nเพื่อให้แน่ใจว่ามาตรฐานเหล่านี้ยังคงมีประสิทธิภาพ\nต่อภัยคุกคามทางไซเบอร์', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('208', 'D3-79', 'มีกระบวนการจัดการเปลี่ยนแปลง\n(Change Management Process) เพื่ออนุญาตและตรวจสอบความถูกต้องของการเปลี่ยนแปลงระบบทั้งหมดที่มีต่อบริการที่สำคัญ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('209', 'D3-80', 'มีการตรวจสอบว่าระบบเชื่อมต่อระยะไกลทั้งหมดมายัง\nบริการที่สำคัญ มีมาตรการรักษาความมั่นคงปลอดภัย\nไซเบอร์ที่มีประสิทธิภาพ เพื่อป้องกันและตรวจจับการเข้าถึง\nโดยไม่ได้รับอนุญาต', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '4');
INSERT INTO `questions` VALUES ('210', 'D3-81', 'หน่วยงานของท่านปฏิบัติตามแนวปฏิบัติในการเชื่อมต่อ\nระยะไกลกับบริการที่สำคัญของหน่วยงานท่าน\nมีองค์ประกอบข้อใดต่อไปนี้\n(ก ) ในกรณีที่เป็นไปได้ให้เปิดใช้งานการเชื่อมต่อไปยัง\nหรือจากไซต์ระยะไกล เมื่อจำเป็นเท่านั้น\n(ข ) ในกรณีที่เป็นไปได้ ใช้เทคนิคการพิสูจน์ตัวตน\nAuthentication Techniques) ที่มีความมั่นคงปลอดภัย\nในการส่ง Transmission Security) และความสมบูรณ์ของ\nข้อความ Message Integrity) ที่แข็งแกร่ง\n(ค ใช้การเข้ารหัสสำหรับการเชื่อมต่อเครือข่ายทั้งหมด\nเช่น https, ssh, scp เป็นต้น\n(ง ไม่อนุญาตให้เชื่อมต่อระยะไกลจากการใช้คำสั่งระบบ\nIssuing System Commands) ที่จะส่งผลกระทบต่อการ\nดำเนินการบริการที่สำคัญของหน่วยงาน เว้นแต่จะได้รับ\nอนุญาตอย่างชัดเจนเนื่องจากความต้องการทางธุรกิจ\n(จ จำกัดการไหลของข้อมูลเฉพาะฟังก์ชันขั้นต่ำ\nที่จำเป็นสำหรับการเชื่อมต่อ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('211', 'D3-82', 'มีการควบคุมอย่างเข้มงวดในการเชื่อมต่อสื่อบันทึก\nข้อมูลแบบถอดได้และอุปกรณ์คอมพิวเตอร์แบบพกพา\nกับบริการที่สำคัญ\n(ก) ในกรณีที่มีฟังก์ชันให้ปิดใช้งานพอร์ตการ\nเชื่อมต่อภายนอกทั้งหมด (เช่น พอร์ต USB) ที่รองรับ\nสื่อบันทึกข้อมูลแบบถอดได้\n(ข) ใช้สื่อบันทึกข้อมูลที่ได้รับอนุญาตเท่านั้น\n(ค) ตรวจสอบว่าสื่อบันทึกข้อมูลแบบถอดได้และ\nอุปกรณ์คอมพิวเตอร์พกพาทั้งหมดไม่มีมัลแวร์ก่อนที่\nจะเชื่อมต่อกับบริการที่สำคัญของหน่วยงาน', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '4');
INSERT INTO `questions` VALUES ('212', 'D3-83', 'มีการเข้ารหัสข้อมูลที่ละเอียดอ่อนทั้งหมดของบริการที่สำคัญบนสื่อบันทึกข้อมูลแบบถอดได้', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('213', 'D3-84', 'มีแผนงานในการสร้างตระหนักรู้ด้านความมั่นคงปลอดภัย\nไซเบอร์ (Cybersecurity Awareness) สำหรับพนักงาน\nผู้รับเหมา และผู้ให้บริการภายนอกบุคคลที่สามที่สามารถ\nเข้าถึงโครงสร้างพื้นฐานสำคัญทางสารสนเทศ ของ\nหน่วยงานท่าน', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('214', 'D3-85', 'มีการทบทวนแผนงานในการสร้างความตระหนักรู้ด้านความ\nมั่นคงปลอดภัยไซเบอร์อย่างน้อยปีละ 1 ครั้ง เพื่อให้แน่ใจว่า เนื้อหาของแผนงานยังคงเป็นปัจจุบันและมีรายละเอียดที่เกี่ยวข้องเหมาะสม', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('215', 'D3-86', 'กำหนดขั้นตอนเพื่อแบ่งปันข้อมูลเกี่ยวกับเหตุการณ์ที่เกี่ยวกับความมั่นคงปลอดภัยไซเบอร์และภัยคุกคามทางไซเบอร์ในส่วนที่เกี่ยวกับบริการที่สำคัญ และมาตรการบรรเทาผลกระทบใด ๆ ที่ดำเนินการเพื่อตอบสนองต่อเหตุการณ์หรือภัยคุกคามดังกล่าว', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '4');
INSERT INTO `questions` VALUES ('216', 'D3-87', 'มีการสร้างกลไกและกระบวนการเพื่อ ตรวจจับ จัดประเภท\nวิเคราะห์และระบุว่ามีภัยคุกคามทางไซเบอร์หรือเหตุการณ์\nที่เกี่ยวกับความมั่นคงปลอดภัยไซเบอร์ที่เกี่ยวข้องกับบริการ\nที่สำคัญ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '5');
INSERT INTO `questions` VALUES ('217', 'D3-88', 'หน่วยงานมีการทบทวนกลไกและกระบวนการ ดังนี้\n(1) ตรวจจับเหตุการณ์ที่เกี่ยวกับความมั่นคงปลอดภัย\nไซเบอร์ทั้งหมดที่เกี่ยวข้องกับบริการที่สำคัญของหน่วยงาน\n(2) การจัดประเภทและวิเคราะห์เหตุการณ์ที่เกี่ยวกับ\nความมั่นคงปลอดภัยไซเบอร์ที่ตรวจพบ\n(3) การระบุว่ามีภัยคุกคามทางไซเบอร์หรือเหตุการณ์ที่\nเกี่ยวกับความมั่นคงปลอดภัยไซเบอร์ที่เกี่ยวข้องกับบริการที่\nสำคัญของหน่วยงานหรือไม่ เพื่อตรวจจับ จัดประเภท\nวิเคราะห์และระบุว่ามีภัยคุกคามทางไซเบอร์หรือเหตุการณ์\nที่เกี่ยวข้องกับความมั่นคงปลอดภัยไซเบอร์ที่เกี่ยวข้ องกับ\nบริการที่สำคัญ อย่างน้อยปีละ 1 ครั้ง', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '4');
INSERT INTO `questions` VALUES ('218', 'D3-89', 'มีการจัดทำสื่อสาร ฝึกซ้อม ทบทวน และปรับปรุง แผนการ\nรับมือภัยคุกคามทางไซเบอร์ ตามที่ระบุไว้ในประมวล\nแนวทางปฏิบัติการรักษาความมั่นคงปลอดภัยไซเบอร์\nอย่างน้อยปีละ 1 ครั้ง', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '5');
INSERT INTO `questions` VALUES ('219', 'D3-90', 'มีการจัดทำแผนการสื่อสารในภาวะวิกฤตเพื่อตอบสนอง\nต่อวิกฤตที่เกิดจากเหตุการณ์ที่เกี่ยวกับความมั่นคงปลอดภัย\nไซเบอร์', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('220', 'D3-91', 'มีแผนการสื่อสารในภาวะวิกฤตของหน่วยงานท่าน มี\nองค์ประกอบ ดังต่อไปนี้\n(ก) จัดตั้งทีมสื่อสารในภาวะวิกฤตเพื่อเปิดใช้งานในช่วง\nวิกฤต\n(ข) ระบุสถานการณ์จำลองเหตุการณ์ที่เกี่ยวกับความ\nมั่นคงปลอดภัยไซเบอร์ที่เป็นไปได้และแผนการดำเนินการที่\nเกี่ยวข้อง\n(ค) ระบุกลุ่มเป้าหมาย และผู้มีส่วนได้ส่วนเสียสำหรับ\nสถานการณ์จำลองเหตุการณ์ที่เกี่ยวกับความมั่นคงปลอดภัย\nไซเบอร์แต่ละประเภท\n(ง) ระบุโฆษกหลักและผู้เชี่ยวชาญด้านเทคนิคที่จะเป็น\nตัวแทนขององค์กรเมื่อกล่าวแถลงกับสื่อมวลชน\n(จ) ระบุแพลตฟอร์ม ช่องทางการเผยแพร่ที่เหมาะสม\nเช่น สื่อดั้งเดิมและโซเชียลมีเดีย ) สำหรับการเผยแพร่ข้อมูล', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '1');
INSERT INTO `questions` VALUES ('221', 'D3-92', 'มีการตรวจสอบให้แน่ใจว่าแผนการสื่อสารในภาวะวิกฤต\nรวมถึงการประสานงานระหว่างทุกฝ่ายที่ได้รับผลกระทบ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('222', 'D3-93', 'มีการฝึกซ้อมแผนการสื่อสารในภาวะวิกฤตอย่างน้อยปีละ\n1 ครั้ง เพื่อเป็นการทดสอบแผนและความเข้าใจของทีมงาน', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('223', 'D3-94', 'มีส่วนร่วมในการฝึกซ้อมรับมือกับภัยคุกคามทางไซเบอร์\nทั้งในระดับชาติหรือระดับภาคส่วน', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('224', 'D3-95', 'มีการตรวจสอบให้แน่ใจว่าบุคลากรที่เกี่ยวข้องที่ระบุไว้\nในแผนการรับมือภัยคุกคามทางไซเบอร์ มีส่วนร่วม\nในการฝึกซ้อมความมั่นคงปลอดภัยไซเบอร์', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('225', 'D3-96', 'มีการปฏิบัติตามคำขอใดของคณะกรรมการการรักษา\nความมั่นคงปลอดภัยไซเบอร์แห่งชาติ เพื่อให้ข้อมูลที่\nเกี่ยวข้องกับบริการที่สำคัญของหน่วยงาน เพื่อวัตถุประสงค์\nในการวางแผนและดำเนินการฝึกซ้อมรับมือกับภัยคุกคาม\nทางไซเบอร์', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '3');
INSERT INTO `questions` VALUES ('226', 'D3-97', 'มีการจัดทำแผนความต่อเนื่องทางธุรกิจ Business Continuity Plan : BCP) เพื่อให้หน่วยงานสามารถกลับมาดำเนินการได้อย่างต่อเนื่อง', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');
INSERT INTO `questions` VALUES ('227', 'D3-98', 'มีการฝึกซ้อม BCP อย่างน้อยปีละ 1 ครั้ง เพื่อทดสอบแผน\nเตรียมความพร้อมต่อสภาวะวิกฤตและพัฒนาปรับปรุงแผน\nให้มีประสิทธิภาพ', 'หมวดหมู่ D3 : ประมวลแนวทางปฏิบัติและกรอบมาตรฐาน ด้านการรักษาความมั่นคงปลอดภัยไซเบอร์', '3783', '2');

-- ----------------------------
-- Table structure for role_assignments
-- ----------------------------
DROP TABLE IF EXISTS `role_assignments`;
CREATE TABLE `role_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `role` enum('evaluator','reviewer','admin') NOT NULL,
  `assigned_by` int DEFAULT NULL,
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `role_assignments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of role_assignments
-- ----------------------------
INSERT INTO `role_assignments` VALUES ('1', '7', 'reviewer', '1', '2025-10-06 09:22:18');
INSERT INTO `role_assignments` VALUES ('2', '7', 'reviewer', '1', '2025-10-06 09:22:21');
INSERT INTO `role_assignments` VALUES ('3', '7', 'reviewer', '1', '2025-10-06 09:25:22');
INSERT INTO `role_assignments` VALUES ('4', '7', 'reviewer', '1', '2025-10-07 02:53:17');
INSERT INTO `role_assignments` VALUES ('5', '8', 'evaluator', '1', '2025-10-07 02:53:22');
INSERT INTO `role_assignments` VALUES ('6', '7', 'reviewer', '1', '2025-10-07 02:53:26');
INSERT INTO `role_assignments` VALUES ('7', '6', 'reviewer', '1', '2025-10-07 02:53:29');
INSERT INTO `role_assignments` VALUES ('8', '5', 'reviewer', '1', '2025-10-07 02:53:31');
INSERT INTO `role_assignments` VALUES ('9', '6', 'reviewer', '1', '2025-10-07 02:53:32');
INSERT INTO `role_assignments` VALUES ('10', '6', 'reviewer', '1', '2025-10-07 02:53:33');

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `skey` varchar(191) DEFAULT NULL,
  `svalue` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`skey`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES ('1', 'test_key', 'test_value');
INSERT INTO `settings` VALUES ('2', 'cii_imported', '1');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('evaluator','reviewer','admin') NOT NULL DEFAULT 'evaluator',
  `allowed_pages` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'admin@example.com', '$2y$10$4uQnr.DydZj/XFca7PgPGuoUdmsmN4DOVLeBT12ioGSxsankdpC.C', 'admin', null, '2025-10-04 10:27:38');
INSERT INTO `users` VALUES ('2', 'test', 'habusaya@gmail.com', '$2y$10$fZH6bHEavKAlO360IcFqzOt02i7tCnM1o/EoZlXisyCw9Ln021DwO', 'evaluator', null, '2025-10-04 14:20:49');
INSERT INTO `users` VALUES ('5', 'reviewer1', 'reviewer1@example.com', '$2y$10$dQTk.cOLHi/Twkb9WvgwV.XQ3YCiT.XdsbiVvb6gupAQ5QuQe5jTC', 'reviewer', null, '2025-10-06 04:31:42');
INSERT INTO `users` VALUES ('6', 'reviewer2', 'reviewer2@example.com', '$2y$10$uOHz9lztdUVRcJAahQ7C5uHMWlWQhIO19S8qs4mi/rRCaD0TxJiV2', 'reviewer', null, '2025-10-06 04:31:42');
INSERT INTO `users` VALUES ('7', 'chief_reviewer', 'chief@example.com', '$2y$10$XxEROmT4E2U8RbAMWRlYtOW7igAQJMOHwvyf6pGibBw7gBvNwK0KK', 'reviewer', null, '2025-10-06 04:31:42');
INSERT INTO `users` VALUES ('8', 'mdo', 'mdo@moph.go.th', '$2y$10$oZ1AogDiVCMfupyEWv7Ao.VqSTXfx1LX3b/U93IZnfeUb9Wa6avg2', 'evaluator', null, '2025-10-06 14:16:09');
