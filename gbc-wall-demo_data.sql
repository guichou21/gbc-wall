-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Ven 22 Novembre 2013 à 14:24
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Contenu de la table `gbc_user_user`
--

INSERT INTO `gbc_user_user` (`id`, `username`, `password`, `salt`, `roles`, `username_canonical`, `email`, `email_canonical`, `enabled`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `credentials_expired`, `credentials_expire_at`) VALUES
(12, 'admin', 'A4OOSnwXtORW9t/OqLb0hTtqFM3sB4PUCeD46xA+FxIo3Noxyo6c3ZxeomeUs5ZxfnWGyoKb0gcFnvTsBaZibw==', '87uz2yxv7dcsocco8k0kw0c48ss00g8', 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'admin', 'admin@gmail.com', 'admin@gmail.com', 1, '2013-11-22 13:11:44', 0, 0, NULL, NULL, NULL, 0, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `gbc_wall_comment`
--

INSERT INTO `gbc_wall_comment` (`id`, `item_id`, `author`, `comment`, `is_approved`, `created`, `updated`) VALUES
(1, 207, 'gb', 'test page', 1, '2013-11-22 13:08:49', '2013-11-22 13:08:49');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=220 ;

--
-- Contenu de la table `gbc_wall_item`
--

INSERT INTO `gbc_wall_item` (`id`, `file`, `description`, `date`, `ratio`, `reverseRatio`, `nbLike`, `type`, `tags`) VALUES
(206, 'bender-futurama-poker-4d8463.jpg', 'Poker time', '2013-11-12 16:57:00', '1.777778', '0.562500', 0, 'Picture', ''),
(207, 'futurama-572f7e.png', 'le trio', '2013-11-12 17:01:00', '1.777132', '0.562704', 1, 'Picture', ''),
(208, 'air-bender-617cca.jpg', 'Air bender', '2013-11-22 14:08:00', '1.333333', '0.750000', 0, 'Picture', ''),
(209, 'Nibbler-futurama-3305545-1024-768-8c783d.jpg', 'Nibbler', '2013-11-22 14:08:00', '1.333333', '0.750000', 0, 'Picture', ''),
(210, 'img3-22baa9.jpg', 'TV Show screenshot', '2013-11-13 14:08:00', '1.333333', '0.750000', 0, 'Picture', ''),
(211, 'Futurama-iPod-1024x768-05f116.jpg', 'Ipod style', '2013-11-22 14:09:00', '1.333333', '0.750000', 0, 'Picture', ''),
(212, 'Universe-680be1.png', 'universe', '2013-11-22 14:09:00', '2.420575', '0.413125', 0, 'Picture', ''),
(213, 'img1-598e2e.jpg', 'Affiche', '2013-11-01 14:10:00', '1.333333', '0.750000', 0, 'Picture', ''),
(214, 'futurama-bfbd77.jpg', 'Encore une affiche', '2013-11-10 14:10:00', '1.333333', '0.750000', 0, 'Picture', ''),
(215, 'futurama-bender2-b0b52e.jpg', 'Bender switch off', '2013-11-10 14:11:00', '1.250000', '0.800000', 0, 'Picture', ''),
(216, 'im-futurama-b80876.jpg', 'cheers', '2013-11-22 14:11:00', '1.489489', '0.671371', 0, 'Picture', ''),
(217, '2013-10-Futurama-Wallpaper-HD-d9a892.jpg', 'HD intro', '2013-11-22 14:11:00', '1.777778', '0.562500', 0, 'Picture', ''),
(218, 'http://www.youtube.com/embed/GLpO-OvJU74', 'on youtube', '2013-10-22 14:12:00', '1.000000', '1.000000', 0, 'Video', ''),
(219, 'http://player.vimeo.com/video/12915013', 'on vimeo', '2013-10-22 14:14:00', '1.000000', '1.000000', 0, 'Video', '');

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
