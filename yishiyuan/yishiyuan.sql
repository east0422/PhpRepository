-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 19, 2020 at 06:49 AM
-- Server version: 5.6.38
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yishiyuan`
--
CREATE DATABASE IF NOT EXISTS `yishiyuan` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `yishiyuan`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE IF NOT EXISTS `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`id`, `username`, `password`) VALUES
(1, 'admin', '888888');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL COMMENT '姓名',
  `sex` varchar(5) DEFAULT NULL COMMENT '性别(男/女)',
  `marry` varchar(10) DEFAULT NULL COMMENT '婚姻状况(未婚/已婚/离异)',
  `birthday` date DEFAULT NULL COMMENT '出生日期',
  `sfz` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `height` varchar(10) DEFAULT NULL COMMENT '身高',
  `education` varchar(15) DEFAULT NULL COMMENT '学历',
  `salary` varchar(30) DEFAULT NULL COMMENT '月收入',
  `workyears` varchar(15) DEFAULT NULL COMMENT '工作年限',
  `province` varchar(15) DEFAULT NULL COMMENT '省份',
  `city` varchar(15) DEFAULT NULL COMMENT '地级市',
  `district` varchar(15) DEFAULT NULL COMMENT '县/区',
  `address` varchar(150) DEFAULT NULL COMMENT '详细地址',
  `phone` varchar(15) DEFAULT NULL COMMENT '手机号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
