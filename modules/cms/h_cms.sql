/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : h_cms

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2016-02-17 16:43:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for base_news
-- ----------------------------
DROP TABLE IF EXISTS `base_news`;
CREATE TABLE `base_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_date` date DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of base_news
-- ----------------------------

-- ----------------------------
-- Table structure for base_news_base_tag
-- ----------------------------
DROP TABLE IF EXISTS `base_news_base_tag`;
CREATE TABLE `base_news_base_tag` (
  `base_news_id` int(11) NOT NULL,
  `base_tag_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`base_news_id`,`base_tag_id`),
  KEY `fk-base_news_base_tag-base_tags` (`base_tag_id`),
  CONSTRAINT `base_news_base_tag_ibfk_1` FOREIGN KEY (`base_news_id`) REFERENCES `base_news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `base_news_base_tag_ibfk_2` FOREIGN KEY (`base_tag_id`) REFERENCES `base_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of base_news_base_tag
-- ----------------------------

-- ----------------------------
-- Table structure for base_pages
-- ----------------------------
DROP TABLE IF EXISTS `base_pages`;
CREATE TABLE `base_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `parent_page_id` int(11) DEFAULT NULL,
  `route` varchar(128) DEFAULT NULL,
  `redirect_to` varchar(128) DEFAULT NULL,
  `menu_index` smallint(5) unsigned DEFAULT NULL COMMENT 'null => pas dans le menu, index base 0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_parent_page_id` (`parent_page_id`),
  CONSTRAINT `base_pages_ibfk_1` FOREIGN KEY (`parent_page_id`) REFERENCES `base_pages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of base_pages
-- ----------------------------
INSERT INTO `base_pages` VALUES ('1', 'présentation', null, '/site/about', '', '0', '1', '2015-09-25 03:20:41', '2015-10-20 06:49:28');
INSERT INTO `base_pages` VALUES ('2', 'contact', null, '/site/contact', '', '1', '1', '2015-09-27 04:49:20', '2015-10-20 06:49:40');
INSERT INTO `base_pages` VALUES ('3', 'accueil', null, '/site/index', '', null, '1', '2015-09-29 11:06:26', '2015-10-05 10:24:28');

-- ----------------------------
-- Table structure for base_page_base_text
-- ----------------------------
DROP TABLE IF EXISTS `base_page_base_text`;
CREATE TABLE `base_page_base_text` (
  `base_page_id` int(11) NOT NULL,
  `base_text_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`base_page_id`,`base_text_id`),
  KEY `bpbt_base_text_fk` (`base_text_id`),
  CONSTRAINT `base_page_base_text_ibfk_1` FOREIGN KEY (`base_page_id`) REFERENCES `base_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `base_page_base_text_ibfk_2` FOREIGN KEY (`base_text_id`) REFERENCES `base_texts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of base_page_base_text
-- ----------------------------
INSERT INTO `base_page_base_text` VALUES ('1', '1', '2015-09-25 03:20:41', '2015-09-25 03:20:41');
INSERT INTO `base_page_base_text` VALUES ('2', '2', '2015-09-27 05:53:22', '2015-09-27 05:53:22');
INSERT INTO `base_page_base_text` VALUES ('3', '3', '2015-09-29 13:17:41', '2015-09-29 13:17:41');

-- ----------------------------
-- Table structure for base_tags
-- ----------------------------
DROP TABLE IF EXISTS `base_tags`;
CREATE TABLE `base_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of base_tags
-- ----------------------------

-- ----------------------------
-- Table structure for base_texts
-- ----------------------------
DROP TABLE IF EXISTS `base_texts`;
CREATE TABLE `base_texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `base_text_idx_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of base_texts
-- ----------------------------
INSERT INTO `base_texts` VALUES ('1', 'présentation', '2015-09-22 04:00:55', '2015-09-22 04:00:55');
INSERT INTO `base_texts` VALUES ('2', 'contact', '2015-09-27 05:34:27', '2015-09-27 05:34:27');
INSERT INTO `base_texts` VALUES ('3', 'accueil', '2015-09-29 11:12:04', '2015-09-29 11:12:04');

-- ----------------------------
-- Table structure for languages
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `iso_639_code` varchar(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_idx_code` (`iso_639_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of languages
-- ----------------------------
INSERT INTO `languages` VALUES ('1', 'français', 'fr', '2015-09-17 00:00:00', '2015-09-17 00:00:00');
INSERT INTO `languages` VALUES ('2', 'anglais', 'en', '2015-09-17 00:00:00', '2015-09-17 00:00:00');
INSERT INTO `languages` VALUES ('3', 'espagnol', 'es', '2015-09-17 00:00:00', '2015-09-17 00:00:00');

-- ----------------------------
-- Table structure for web_news
-- ----------------------------
DROP TABLE IF EXISTS `web_news`;
CREATE TABLE `web_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `base_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-web_news-base_news` (`base_id`),
  KEY `fk-web_news-languages` (`language_id`),
  CONSTRAINT `web_news_ibfk_1` FOREIGN KEY (`base_id`) REFERENCES `base_news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `web_news_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_news
-- ----------------------------

-- ----------------------------
-- Table structure for web_pages
-- ----------------------------
DROP TABLE IF EXISTS `web_pages`;
CREATE TABLE `web_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `base_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `menu_title` varchar(40) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-web_pages-base_pages` (`base_id`),
  KEY `fk-web_pages-languages` (`language_id`),
  CONSTRAINT `web_pages_ibfk_1` FOREIGN KEY (`base_id`) REFERENCES `base_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `web_pages_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_pages
-- ----------------------------
INSERT INTO `web_pages` VALUES ('1', '1', '1', 'A propos de... [MONSITE.NET]', 'Présentation', 'Présentation du site www.[MONSITE.NET]', '', '2015-09-25 03:20:41', '2015-10-20 06:48:34');
INSERT INTO `web_pages` VALUES ('2', '2', '1', 'Contact', 'Contact', 'Contacter l\'administrateur du site [MONSITE.NET]', '', '2015-09-27 04:53:42', '2015-10-20 06:48:45');
INSERT INTO `web_pages` VALUES ('3', '3', '1', 'Titre du site [MONSITE.NET]', 'Accueil', '[MONSITE.NET]', '', '2015-09-29 11:08:04', '2015-10-20 06:49:07');

-- ----------------------------
-- Table structure for web_tags
-- ----------------------------
DROP TABLE IF EXISTS `web_tags`;
CREATE TABLE `web_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `base_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `label` varchar(128) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-web_tags-base_tags` (`base_id`),
  KEY `fk-web_tags-languages` (`language_id`),
  CONSTRAINT `web_tags_ibfk_1` FOREIGN KEY (`base_id`) REFERENCES `base_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `web_tags_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_tags
-- ----------------------------

-- ----------------------------
-- Table structure for web_texts
-- ----------------------------
DROP TABLE IF EXISTS `web_texts`;
CREATE TABLE `web_texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `base_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_base_text` (`base_id`),
  KEY `fk_web_text_language` (`language_id`),
  CONSTRAINT `web_texts_ibfk_1` FOREIGN KEY (`base_id`) REFERENCES `base_texts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `web_texts_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_texts
-- ----------------------------
INSERT INTO `web_texts` VALUES ('1', '1', '1', 'A propos de : [MONSITE.NET]', '', 'Présentation du site [MONSITE.NET]', '<p>Les fiches de ce site sont rédigées selon l\'inspiration du jour plutôt qu\'en suivant un un plan rigoureux. Vous trouverez donc la recension incomplète, surtout au début puisqu\'il faut un peu de temps disponible pour rédiger un article. </p><p>Vous ne trouverez pas non plus toutes les grandes dates de notre histoire. Celles qui ont été omises ne sont pas écartées par un choix volontaire ou définitif : dans la mesure du temps disponible, les dates majeures de notre histoire ont vocation à être progressivement renseignées. </p><p>A l\'inverse, vous trouverez certainement des fiches commémorant des événements qui vous semblent mineurs ou sélectionnés de façon résolument subjective. C\'est tout à fait normal, chaque rédacteur élaborant ses articles en fonction de ses centres d\'intérêt, de compétence mais aussi de l\'humeur du moment. Car le travail fourni ici doit évidemment rester plaisant aux contributeurs faute de quoi le site cessera vite de vivre. </p><p>D\'avance donc, merci pour votre compréhension si jamais vous ne trouvez pas ici ce que vous cherchez. Mais comme nous restons ouverts à toutes remarques et suggestions, vous pouvez facilement nous communiquer vos suggestions et vos observations en remplissant le <a href=\"/contact\">formulaire de contact</a>. </p>Cordialement,<br><em>le webmestre</em>', '2015-09-22 04:04:12', '2016-02-17 15:27:22');
INSERT INTO `web_texts` VALUES ('2', '2', '1', 'Nous contacter', '', 'Texte principal de la page de contact', '<p>Pour toute question concernant ce site, vous pouvez nous contacter en remplissant le formulaire ci-dessous.</p>', '2015-09-27 05:44:20', '2015-09-27 05:44:20');
INSERT INTO `web_texts` VALUES ('3', '3', '1', 'Texte du site [MONSITE.NET]', '', '', '<p>Bienvenue sur le site [MONSITE.NET]</p>', '2015-09-29 12:12:51', '2016-02-17 15:27:58');
