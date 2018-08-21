
--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `iso_639_code` varchar(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_idx_code` (`iso_639_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'français','fr','2015-09-17 00:00:00','2015-09-17 00:00:00'),(2,'anglais','en','2015-09-17 00:00:00','2015-09-17 00:00:00')
  ,(3,'espagnol','es','2015-09-17 00:00:00','2015-09-17 00:00:00');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `web_text`
--

DROP TABLE IF EXISTS `web_texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `language_id` int(10) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_web_text_parent` (`parent_id`),
  KEY `fk_web_text_language` (`language_id`),
  CONSTRAINT `fk_web_text_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_web_text_parent` FOREIGN KEY (`parent_id`) REFERENCES `base_texts` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_texts`
--

LOCK TABLES `web_texts` WRITE;
/*!40000 ALTER TABLE `web_texts` DISABLE KEYS */;
INSERT INTO web_texts (id, parent_id, language_id, title, subtitle, description, body, created_at, updated_at) VALUES
  (2, 1, 1, 'aaaaaaa', '', '', '<p><span class=\"label-red\">Label</span></p><p><img alt=\"\" style=\"float: left; margin: 0px 10px 10px 0px;\" src=\"/uploads/1/79d51f4d7e-404.jpg\">\r\n</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam non auctor orci. In\r\n</p><p>molestie consectetur sodales. Nunc quis tortor viverra, pellentesque mi et, sodales libero! Duis rhoncus hendrerit augue, vel faucibus magna eleifend sed. Etiam ultrices iaculis dui, sit amet elementum dui molestie non. Integer dignissim purus metus, sit amet dictum dolor convallis eget. Donec ac metus ut massa euismod mattis.<br><br>Sed ligula nisi, cursus a sem at, condimentum elementum diam. Vivamus eu mollis est. In sed libero aliquet, ornare dui vitae, egestas metus. Phasellus eu dictum felis. Ut tempor pellentesque erat sed rutrum. Aliquam aliquam leo sit amet ipsum aliquam porta ac eu nisi. Aliquam sit amet justo id diam mattis dictum nec in tortor. Pellentesque hendrerit; sem non molestie porta, justo nunc condimentum arcu, in rutrum neque metus sed purus. Aenean nec metus ultricies, euismod augue eu, cursus leo. Vivamus efficitur ex augue, sed efficitur urna molestie porttitor. Mauris sit amet tortor eu nibh faucibus maximus.\r\n</p><p><br>Ut ac risus sapien! Nam nec enim aliquam, auctor arcu a, ornare diam. Suspendisse a augue bibendum, maximus mauris vel, laoreet arcu. Donec facilisis velit id orci rhoncus, iaculis tristique libero facilisis. Pellentesque eget faucibus nisi. Morbi et neque dictum, maximus felis in, ullamcorper sapien. Sed laoreet mauris eget arcu ultrices mattis. Aenean dignissim mauris vel velit mollis rutrum. Aenean id gravida est. Nunc in sem dui.<br>\r\n</p>', '2015-09-18 16:04:49', '2015-09-21 09:31:19'),
  (3, 2, 1, 'A propos de : Ephémérides.eu', '', 'Présentation du site ephemeridesd.eu', '<p><span class=\"floating-image-container\"><img src=\"/uploads/1/6b061fc28f-calendrier-1988-62-pett.jpg\" alt=\"6b061fc28f-calendrier-1988-62-pett\"></span>Les fiches de ce site sont rédigées selon l''inspiration du jour plutôt qu''en suivant un un plan rigoureux. Vous trouverez donc la recension incomplète, surtout au début puisqu''il faut un eu de temps disponible pour rédiger un article.\r\n</p><p>Vous ne trouverez pas non plus toutes les grandes dates de notre histoire. Celles qui ont été omises ne sont pas écartées par un choix volontaire et surtout pas définitif : dans la mesure du temps disponible, les dates majeures ont bel et bien vocation à être progressivement renseignées.\r\n</p><p>A l''inverse, vous trouverez certainement des fiches commémorant des événements qui vous semblent mineurs, ou bien sélectionnés de façon résolument subjective. C''est tout à fait normal, chaque rédacteur élaborant ses articles en fonction de ses centres d''intérêt, de compétence mais aussi de l''humeur du moment. Car le travail fourni ici doit évidemment rester plaisant aux contributeurs, faute de quoi le site cessera vite de vivre.\r\n</p><p>D''avance donc, merci pour votre compréhension si jamais vous ne trouvez pas ici ce que vous cherchez. Mais comme nous restons ouverts à toutes remarques et suggestions, vous pouvez facilement nous communiquer vos observations en remplissant le formulaire de contact.\r\n</p><p>Cordialement,<br><em>le webmestre</em><br><br>\r\n</p>', '2015-09-21 14:27:49', '2015-09-21 16:36:39');
/*!40000 ALTER TABLE `web_texts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `web_text_parent`
--

DROP TABLE IF EXISTS `base_texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `base_texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `base_text_idx_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `base_texts`
--

LOCK TABLES `base_texts` WRITE;
/*!40000 ALTER TABLE `base_texts` DISABLE KEYS */;
INSERT INTO base_texts (id, code, enabled, created_at, updated_at) VALUES
  (1, 'test', 0, '2015-09-18 10:27:20', '2015-09-18 10:27:20'),
  (2, 'Présentation', 1, '2015-09-21 14:26:58', '2015-09-21 15:32:19');
/*!40000 ALTER TABLE `base_texts` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
-- Dump completed on 2015-09-17 11:59:24
