-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2020-04-24 10:04:24
-- 服务器版本： 5.6.47-log
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payrecord`
--
CREATE DATABASE IF NOT EXISTS `payrecord` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `payrecord`;

-- --------------------------------------------------------

--
-- 表的结构 `wxpay`
--

CREATE TABLE IF NOT EXISTS `wxpay` (
  `id` int(11) NOT NULL,
  `out_trade_no` varchar(32) NOT NULL COMMENT '商户系统内部订单号',
  `total_fee` int(11) NOT NULL COMMENT '标价金额(单位为分)',
  `trade_type` varchar(16) NOT NULL COMMENT '交易类型',
  `openid` varchar(128) DEFAULT NULL COMMENT '用户标识',
  `transaction_id` varchar(32) DEFAULT NULL COMMENT '微信支付订单号',
  `trade_time` datetime DEFAULT NULL COMMENT '订单支付时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wxpay`
--
ALTER TABLE `wxpay`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wxpay`
--
ALTER TABLE `wxpay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
