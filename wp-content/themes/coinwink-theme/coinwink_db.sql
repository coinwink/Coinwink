-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 09, 2020 at 02:27 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coinwink_git`
--

-- --------------------------------------------------------

--
-- Table structure for table `cw_alerts_email_cur`
--

DROP TABLE IF EXISTS `cw_alerts_email_cur`;
CREATE TABLE IF NOT EXISTS `cw_alerts_email_cur` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `coin` varchar(300) NOT NULL,
  `coin_id` varchar(64) NOT NULL,
  `symbol` varchar(64) NOT NULL,
  `below` varchar(64) NOT NULL,
  `below_currency` varchar(64) NOT NULL,
  `above` varchar(64) NOT NULL,
  `above_currency` varchar(64) NOT NULL,
  `below_sent` varchar(32) NOT NULL,
  `above_sent` varchar(32) NOT NULL,
  `email` varchar(300) NOT NULL,
  `unique_id` varchar(64) NOT NULL,
  `timestamp` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_alerts_email_per`
--

DROP TABLE IF EXISTS `cw_alerts_email_per`;
CREATE TABLE IF NOT EXISTS `cw_alerts_email_per` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `coin` varchar(300) NOT NULL,
  `coin_id` varchar(32) NOT NULL,
  `symbol` varchar(64) NOT NULL,
  `price_set_btc` varchar(64) NOT NULL,
  `price_set_usd` varchar(64) NOT NULL,
  `price_set_eth` varchar(64) NOT NULL,
  `plus_percent` varchar(64) NOT NULL,
  `plus_change` varchar(64) NOT NULL,
  `plus_compared` varchar(64) NOT NULL,
  `minus_percent` varchar(64) NOT NULL,
  `minus_change` varchar(64) NOT NULL,
  `minus_compared` varchar(64) NOT NULL,
  `plus_sent` varchar(1) NOT NULL,
  `minus_sent` varchar(1) NOT NULL,
  `email` varchar(300) NOT NULL,
  `unique_id` varchar(64) NOT NULL,
  `timestamp` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_alerts_portfolio`
--

DROP TABLE IF EXISTS `cw_alerts_portfolio`;
CREATE TABLE IF NOT EXISTS `cw_alerts_portfolio` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) NOT NULL,
  `change_1h_plus` int(11) NOT NULL,
  `change_1h_minus` int(11) NOT NULL,
  `change_24h_plus` int(11) NOT NULL,
  `change_24h_minus` int(11) NOT NULL,
  `on_1h_plus` varchar(10) NOT NULL,
  `on_1h_minus` varchar(10) NOT NULL,
  `on_24h_plus` varchar(10) NOT NULL,
  `on_24h_minus` varchar(10) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT 'email',
  `destination` varchar(132) NOT NULL,
  `timestamp` datetime NOT NULL,
  `expanded` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_alerts_sms_cur`
--

DROP TABLE IF EXISTS `cw_alerts_sms_cur`;
CREATE TABLE IF NOT EXISTS `cw_alerts_sms_cur` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `coin` text NOT NULL,
  `coin_id` text NOT NULL,
  `symbol` text NOT NULL,
  `below` text NOT NULL,
  `below_currency` text NOT NULL,
  `above` text NOT NULL,
  `above_currency` text NOT NULL,
  `below_sent` text NOT NULL,
  `above_sent` text NOT NULL,
  `phone` text NOT NULL,
  `user_ID` text NOT NULL,
  `unique_id` varchar(100) NOT NULL,
  `timestamp` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_alerts_sms_per`
--

DROP TABLE IF EXISTS `cw_alerts_sms_per`;
CREATE TABLE IF NOT EXISTS `cw_alerts_sms_per` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `coin` varchar(64) NOT NULL,
  `coin_id` int(11) NOT NULL,
  `symbol` varchar(32) NOT NULL,
  `price_set_btc` varchar(32) NOT NULL,
  `price_set_usd` varchar(32) NOT NULL,
  `price_set_eth` varchar(32) NOT NULL,
  `plus_percent` varchar(32) NOT NULL,
  `plus_change` varchar(32) NOT NULL,
  `plus_compared` varchar(32) NOT NULL,
  `minus_percent` varchar(32) NOT NULL,
  `minus_change` varchar(32) NOT NULL,
  `minus_compared` varchar(32) NOT NULL,
  `plus_sent` varchar(1) NOT NULL,
  `minus_sent` varchar(1) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `user_ID` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_data_cmc`
--

DROP TABLE IF EXISTS `cw_data_cmc`;
CREATE TABLE IF NOT EXISTS `cw_data_cmc` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `json` mediumtext NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `cw_data_cmc` (`ID`, `json`) VALUES
(1, '');
COMMIT;

-- --------------------------------------------------------

--
-- Table structure for table `cw_data_cur_rates`
--

DROP TABLE IF EXISTS `cw_data_cur_rates`;
CREATE TABLE IF NOT EXISTS `cw_data_cur_rates` (
  `ID` int(11) NOT NULL,
  `EUR` varchar(32) NOT NULL,
  `GBP` varchar(32) NOT NULL,
  `CAD` varchar(32) NOT NULL,
  `AUD` varchar(32) NOT NULL,
  `BRL` varchar(32) NOT NULL,
  `MXN` varchar(32) NOT NULL,
  `JPY` varchar(32) NOT NULL,
  `SGD` varchar(32) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_feedback`
--

DROP TABLE IF EXISTS `cw_feedback`;
CREATE TABLE IF NOT EXISTS `cw_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` varchar(10000) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_logs_alerts_email`
--

DROP TABLE IF EXISTS `cw_logs_alerts_email`;
CREATE TABLE IF NOT EXISTS `cw_logs_alerts_email` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` tinytext DEFAULT NULL,
  `alert_ID` varchar(64) NOT NULL,
  `coin_ID` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `symbol` tinytext NOT NULL,
  `type` tinytext DEFAULT NULL,
  `destination` tinytext DEFAULT NULL,
  `status` tinytext DEFAULT NULL,
  `error` varchar(1280) DEFAULT 'NULL',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `time` int(11) NOT NULL,
  `content` varchar(9999) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_logs_alerts_portfolio`
--

DROP TABLE IF EXISTS `cw_logs_alerts_portfolio`;
CREATE TABLE IF NOT EXISTS `cw_logs_alerts_portfolio` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) DEFAULT NULL,
  `alert_ID` varchar(64) NOT NULL,
  `coin_ID` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `symbol` tinytext NOT NULL,
  `coin` tinytext NOT NULL,
  `type` tinytext DEFAULT NULL,
  `destination` tinytext DEFAULT NULL,
  `status` tinytext DEFAULT NULL,
  `error` varchar(1280) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `time` int(11) NOT NULL,
  `content` varchar(9999) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_logs_alerts_sms`
--

DROP TABLE IF EXISTS `cw_logs_alerts_sms`;
CREATE TABLE IF NOT EXISTS `cw_logs_alerts_sms` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) DEFAULT NULL,
  `alert_ID` varchar(64) NOT NULL,
  `coin_ID` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `symbol` tinytext NOT NULL,
  `type` tinytext DEFAULT NULL,
  `destination` tinytext DEFAULT NULL,
  `status` tinytext DEFAULT NULL,
  `error` varchar(1280) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `time` int(11) NOT NULL,
  `content` varchar(9999) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_settings`
--

DROP TABLE IF EXISTS `cw_settings`;
CREATE TABLE IF NOT EXISTS `cw_settings` (
  `user_ID` smallint(10) NOT NULL AUTO_INCREMENT,
  `subs` tinyint(4) NOT NULL DEFAULT 0,
  `sms` tinyint(4) NOT NULL DEFAULT 0,
  `legac` tinyint(4) NOT NULL DEFAULT 0,
  `theme` varchar(32) NOT NULL,
  `t_s` tinyint(1) NOT NULL,
  `t_i` varchar(4) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `unique_id` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_nr` varchar(100) NOT NULL,
  `cur_main` varchar(10) NOT NULL,
  `cur_p` varchar(10) NOT NULL,
  `cur_w` varchar(10) NOT NULL,
  `conf_w` varchar(10) NOT NULL,
  `portfolio` text NOT NULL,
  `watchlist` text NOT NULL,
  PRIMARY KEY (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cw_subs`
--

DROP TABLE IF EXISTS `cw_subs`;
CREATE TABLE IF NOT EXISTS `cw_subs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `date_renewed` date NOT NULL,
  `status` varchar(64) NOT NULL,
  `months` int(11) NOT NULL DEFAULT 1,
  `payment_ID` varchar(64) NOT NULL,
  `country` tinytext NOT NULL,
  `subscription` varchar(64) NOT NULL,
  `customer` varchar(64) NOT NULL,
  `date_cancelled` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
