-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 11, 2016 at 02:01 AM
-- Server version: 5.5.45
-- PHP Version: 5.4.44

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kin`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorys`
--

CREATE TABLE IF NOT EXISTS `categorys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `categorys`
--

INSERT INTO `categorys` (`id`, `cname`) VALUES
(1, 'Обучение'),
(2, 'Триллер'),
(3, 'Коммедия'),
(4, 'Ужасы'),
(5, 'Ужасы');

-- --------------------------------------------------------

--
-- Table structure for table `dislikes`
--

CREATE TABLE IF NOT EXISTS `dislikes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_film` (`id_film`),
  KEY `id_user_2` (`id_user`,`id_film`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `dislikes`
--

INSERT INTO `dislikes` (`id`, `id_user`, `id_film`) VALUES
(1, 4, 1),
(2, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `films`
--

CREATE TABLE IF NOT EXISTS `films` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(60) NOT NULL,
  `fcategory` int(11) DEFAULT NULL,
  `fposter` varchar(60) NOT NULL,
  `flikes` int(11) NOT NULL DEFAULT '0',
  `fdislikes` int(11) NOT NULL DEFAULT '0',
  `fabout` text,
  `ffile` varchar(60) NOT NULL,
  `fraiting` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fcategory` (`fcategory`),
  KEY `fcategory_2` (`fcategory`),
  KEY `fcategory_3` (`fcategory`),
  KEY `fcategory_4` (`fcategory`),
  KEY `fcategory_5` (`fcategory`),
  KEY `flikes` (`flikes`,`fdislikes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `films`
--

INSERT INTO `films` (`id`, `fname`, `fcategory`, `fposter`, `flikes`, `fdislikes`, `fabout`, `ffile`, `fraiting`) VALUES
(1, 'Говнофильм', 2, 'Govnofilm.jpeg', 0, 0, 'Что-то там про что-то', 'D:/gada', 3),
(6, 'Тест', NULL, 'Test.jpg', 0, 0, 'Какое-то описание', 'galaxy.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `films_categories`
--

CREATE TABLE IF NOT EXISTS `films_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_film` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_film` (`id_film`,`id_cat`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `films_categories`
--

INSERT INTO `films_categories` (`id`, `id_film`, `id_cat`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 6, 1),
(4, 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `later`
--

CREATE TABLE IF NOT EXISTS `later` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`,`id_film`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `later`
--

INSERT INTO `later` (`id`, `id_user`, `id_film`) VALUES
(1, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`,`id_film`),
  KEY `id_film` (`id_film`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `id_user`, `id_film`) VALUES
(1, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(45) NOT NULL,
  `uemail` varchar(45) NOT NULL,
  `upass` varchar(45) NOT NULL,
  `urole` varchar(45) NOT NULL DEFAULT 'user',
  `uava` varchar(45) NOT NULL DEFAULT 'default.jpg',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uname`, `uemail`, `upass`, `urole`, `uava`) VALUES
(3, 'Semen', 'sem@mail.ru', 'd9b1d7db4cd6e70935368a1efb10e377', 'admin', 'Semen.jpg'),
(4, 'Ada', 'hell@mail.ru', '1f32aa4c9a1d2ea010adcf2348166a04', 'user', 'default.jpg'),
(5, 'Login', 'login@mail.ru', 'd9b1d7db4cd6e70935368a1efb10e377', 'user', 'default.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  `uraiting` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `vote`
--

INSERT INTO `vote` (`id`, `id_user`, `id_film`, `uraiting`) VALUES
(15, 3, 6, 4),
(16, 3, 1, 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dislikes`
--
ALTER TABLE `dislikes`
  ADD CONSTRAINT `dislikes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dislikes_ibfk_2` FOREIGN KEY (`id_film`) REFERENCES `films` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `films_ibfk_1` FOREIGN KEY (`fcategory`) REFERENCES `categorys` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `films_categories`
--
ALTER TABLE `films_categories`
  ADD CONSTRAINT `cat_film_bk1` FOREIGN KEY (`id_film`) REFERENCES `films` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cat_film_bk2` FOREIGN KEY (`id_cat`) REFERENCES `categorys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_film`) REFERENCES `films` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
