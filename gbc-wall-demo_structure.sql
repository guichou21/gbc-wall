-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Mar 12 Novembre 2013 à 16:40
-- Version du serveur: 5.5.20
-- Version de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `gbc-wall-demo`
--

-- --------------------------------------------------------

--
-- Structure de la table `gbc_user_user`
--

CREATE TABLE IF NOT EXISTS `gbc_user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_28AFCBC192FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_28AFCBC1A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;


--
-- Contenu de la table `gbc_user_user`
--

INSERT INTO `gbc_user_user` (`id`, `username`, `password`, `salt`, `roles`, `username_canonical`, `email`, `email_canonical`, `enabled`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `credentials_expired`, `credentials_expire_at`) VALUES
(2, 'admin', 'A4OOSnwXtORW9t/OqLb0hTtqFM3sB4PUCeD46xA+FxIo3Noxyo6c3ZxeomeUs5ZxfnWGyoKb0gcFnvTsBaZibw==', '87uz2yxv7dcsocco8k0kw0c48ss00g8', 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'admin', 'admin@gmail.com', 'admin@gmail.com', 1, '2013-10-14 09:09:52', 0, 0, NULL, NULL, NULL, 0, NULL);



-- --------------------------------------------------------

--
-- Structure de la table `gbc_wall_comment`
--

CREATE TABLE IF NOT EXISTS `gbc_wall_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` longtext COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_29E759D6126F525E` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- Structure de la table `gbc_wall_item`
--

CREATE TABLE IF NOT EXISTS `gbc_wall_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `ratio` decimal(10,6) NOT NULL,
  `reverseRatio` decimal(10,6) NOT NULL,
  `nbLike` int(11) NOT NULL,
  `type` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `tags` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=206 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `gbc_wall_comment`
--
ALTER TABLE `gbc_wall_comment`
  ADD CONSTRAINT `FK_29E759D6126F525E` FOREIGN KEY (`item_id`) REFERENCES `gbc_wall_item` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
