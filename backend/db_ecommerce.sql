-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2019 at 08:16 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `messagein`
--

CREATE TABLE `messagein` (
  `Id` int(11) NOT NULL,
  `SendTime` datetime DEFAULT NULL,
  `ReceiveTime` datetime DEFAULT NULL,
  `MessageFrom` varchar(80) DEFAULT NULL,
  `MessageTo` varchar(80) DEFAULT NULL,
  `SMSC` varchar(80) DEFAULT NULL,
  `MessageText` text,
  `MessageType` varchar(80) DEFAULT NULL,
  `MessageParts` int(11) DEFAULT NULL,
  `MessagePDU` text,
  `Gateway` varchar(80) DEFAULT NULL,
  `UserId` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messagein`
--

INSERT INTO `messagein` (`Id`, `SendTime`, `ReceiveTime`, `MessageFrom`, `MessageTo`, `SMSC`, `MessageText`, `MessageType`, `MessageParts`, `MessagePDU`, `Gateway`, `UserId`) VALUES
(1, '2017-11-02 05:19:29', NULL, '211', '+639305235027', NULL, '0B05040B8423F00003FB0302,870906890101C651018715060350524F585932000187070603534D415254204D4D530001C65201872F060350524F5859325F3100018720060331302E3130322E36312E343600018721068501872206034E4150475052535F320001C6530187230603383038300001010101C600015501873606037734000187070603534D4152', NULL, NULL, NULL, NULL, NULL),
(2, '2017-11-02 05:19:34', NULL, '211', '+639305235027', NULL, '0B05040B8423F00003FB0303,54204D4D5300018739060350524F585932000187340603687474703A2F2F31302E3130322E36312E3233383A383030322F00010101', NULL, NULL, NULL, NULL, NULL),
(3, '2017-11-02 05:19:14', NULL, '211', '+639305235027', NULL, '0B05040B8423F00003FA0201,6C062F1F2DB69180923646443032463643313042394231363544354242413143304143413232424334343239453236423600030B6A00C54503312E310001C6560187070603534D41525420494E5445524E4554000101C65501871106034E4150475052535F330001871006AB0187070603534D41525420494E5445524E455400', NULL, NULL, NULL, NULL, NULL),
(4, '2017-11-02 05:19:19', NULL, '211', '+639305235027', NULL, '0B05040B8423F00003FA0202,0187140187080603696E7465726E65740001870906890101C600015501873606037732000187070603534D41525420494E5445524E45540001872206034E4150475052535F330001C65901873A0603687474703A2F2F6D2E736D6172742E636F6D2E7068000187070603484F4D450001871C01010101', NULL, NULL, NULL, NULL, NULL),
(5, '2017-11-02 05:19:24', NULL, '211', '+639305235027', NULL, '0B05040B8423F00003FB0301,6D062F1F2DB69180923432373832413042464145313131463335303137323744303141433530304134373930423843334500030B6A00C54503312E310001C6560187070603534D415254204D4D53000101C65501871106034E4150475052535F320001871006AB0187070603534D415254204D4D530001870806036D6D730001', NULL, NULL, NULL, NULL, NULL),
(6, '2017-11-02 05:19:29', NULL, '211', '+639305235027', NULL, '0B05040B8423F00003FB0302,870906890101C651018715060350524F585932000187070603534D415254204D4D530001C65201872F060350524F5859325F3100018720060331302E3130322E36312E343600018721068501872206034E4150475052535F320001C6530187230603383038300001010101C600015501873606037734000187070603534D4152', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messagelog`
--

CREATE TABLE `messagelog` (
  `Id` int(11) NOT NULL,
  `SendTime` datetime DEFAULT NULL,
  `ReceiveTime` datetime DEFAULT NULL,
  `StatusCode` int(11) DEFAULT NULL,
  `StatusText` varchar(80) DEFAULT NULL,
  `MessageTo` varchar(80) DEFAULT NULL,
  `MessageFrom` varchar(80) DEFAULT NULL,
  `MessageText` text,
  `MessageType` varchar(80) DEFAULT NULL,
  `MessageId` varchar(80) DEFAULT NULL,
  `ErrorCode` varchar(80) DEFAULT NULL,
  `ErrorText` varchar(80) DEFAULT NULL,
  `Gateway` varchar(80) DEFAULT NULL,
  `MessageParts` int(11) DEFAULT NULL,
  `MessagePDU` text,
  `Connector` varchar(80) DEFAULT NULL,
  `UserId` varchar(80) DEFAULT NULL,
  `UserInfo` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messagelog`
--

INSERT INTO `messagelog` (`Id`, `SendTime`, `ReceiveTime`, `StatusCode`, `StatusText`, `MessageTo`, `MessageFrom`, `MessageText`, `MessageType`, `MessageId`, `ErrorCode`, `ErrorText`, `Gateway`, `MessageParts`, `MessagePDU`, `Connector`, `UserId`, `UserInfo`) VALUES
(1, '2018-01-27 20:38:08', NULL, 300, NULL, '09305235027', 'Hello Poh', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '2018-01-27 20:39:06', NULL, 300, NULL, '09305235027', 'Hello Poh', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '2018-01-27 20:49:14', NULL, 300, NULL, '09305235027', 'hi poh', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '2018-01-27 20:50:56', NULL, 300, NULL, '09508767867', 'hi poh', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '2018-02-09 17:52:26', NULL, 300, NULL, '09486457414', 'Test to send', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, '2018-02-09 17:54:27', NULL, 300, NULL, '09486457414', 'Test to send', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, '2018-02-09 17:55:11', NULL, 300, NULL, '09486457414', 'Test to send', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, '2018-02-09 17:59:11', NULL, 300, NULL, '09486457414', 'Test to send', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, '2018-02-09 18:00:12', NULL, 200, NULL, '+639486457414', 'yes', NULL, NULL, '1:+639486457414:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, '2018-02-09 18:01:12', NULL, 200, NULL, '+639486457414', 'Test to send', NULL, NULL, '1:+639486457414:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, '2018-02-09 18:02:58', NULL, 200, NULL, '+639486457414', 'FROM JANNO : Confirmed', NULL, NULL, '1:+639486457414:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '2018-02-09 18:05:22', NULL, 200, NULL, '+639486457414', 'FROM Bachelor of Science and Entrepreneurs : Your order has been .Confirmed', NULL, NULL, '1:+639486457414:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, '2018-02-09 18:08:14', NULL, 200, NULL, '+639486457414', 'FROM Bachelor of Science and Entrepreneurs : Your order has been .Confirmed', NULL, NULL, '1:+639486457414:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, '2018-02-09 18:21:41', NULL, 200, NULL, '+639486457414', 'FROM Bachelor of Science and Entrepreneurs : Your order has been .Confirmed', NULL, NULL, '1:+639486457414:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, '2018-04-01 22:17:34', NULL, 300, NULL, '09123586545', 'Your code is .6048', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, '2018-04-01 22:18:20', NULL, 300, NULL, '09123586545', 'Your code is .9305', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, '2018-04-01 22:20:15', NULL, 300, NULL, '09123586545', 'Your code is .2924', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, '2018-04-01 22:42:36', NULL, 300, NULL, '09123586545', 'Your code is .6938', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, '2018-04-02 00:40:53', NULL, 300, NULL, '9956112920', 'Your code is .7290', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, '2018-04-02 00:42:14', NULL, 300, NULL, '9956112920', 'Your code is .4506', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, '2018-04-02 00:43:46', NULL, 300, NULL, '9956112920', 'Your code is .4506', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, '2018-04-02 00:45:56', NULL, 300, NULL, '09956112920', 'Your code is .6988', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, '2018-04-02 00:47:17', NULL, 300, NULL, '09956112920', 'Your code is .4380', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, '2018-04-02 00:48:53', NULL, 200, NULL, '639956112920', 'Your code is .5936', NULL, NULL, '1:639956112920:129', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, '2018-04-02 00:50:29', NULL, 200, NULL, '639956112920', 'Your code is .5349', NULL, NULL, '1:639956112920:130', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, '2018-04-02 00:53:32', NULL, 200, NULL, '639956112920', 'Your code is', NULL, NULL, '1:639956112920:131', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, '2018-04-02 00:54:43', NULL, 200, NULL, '639956112920', 'Your code is 3407', NULL, NULL, '1:639956112920:132', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messageout`
--

CREATE TABLE `messageout` (
  `Id` int(11) NOT NULL,
  `MessageTo` varchar(80) DEFAULT NULL,
  `MessageFrom` varchar(80) DEFAULT NULL,
  `MessageText` text,
  `MessageType` varchar(80) DEFAULT NULL,
  `Gateway` varchar(80) DEFAULT NULL,
  `UserId` varchar(80) DEFAULT NULL,
  `UserInfo` text,
  `Priority` int(11) DEFAULT NULL,
  `Scheduled` datetime DEFAULT NULL,
  `ValidityPeriod` int(11) DEFAULT NULL,
  `IsSent` tinyint(1) NOT NULL DEFAULT '0',
  `IsRead` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblautonumber`
--

CREATE TABLE `tblautonumber` (
  `ID` int(11) NOT NULL,
  `AUTOSTART` varchar(11) NOT NULL,
  `AUTOINC` int(11) NOT NULL,
  `AUTOEND` int(11) NOT NULL,
  `AUTOKEY` varchar(12) NOT NULL,
  `AUTONUM` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblautonumber`
--

INSERT INTO `tblautonumber` (`ID`, `AUTOSTART`, `AUTOINC`, `AUTOEND`, `AUTOKEY`, `AUTONUM`) VALUES
(1, '2017', 1, 43, 'PROID', 10),
(2, '0', 1, 95, 'ordernumber', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `CATEGID` int(11) NOT NULL,
  `CATEGORIES` varchar(255) NOT NULL,
  `USERID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`CATEGID`, `CATEGORIES`, `USERID`) VALUES
(5, 'SHOES', 0),
(11, 'BAGS', 0),
(12, 'CLOTHING', 0),
(13, 'INTERIORS', 0),
(14, 'HOUSEHOLDS', 0),
(15, 'FASHION', 0),
(16, 'KIDS', 0),
(17, 'WOMENS', 0),
(18, 'MENS', 0),
(19, 'SPORTSWEAR', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblcustomer`
--

CREATE TABLE `tblcustomer` (
  `CUSTOMERID` int(11) NOT NULL,
  `FNAME` varchar(30) NOT NULL,
  `LNAME` varchar(30) NOT NULL,
  `MNAME` varchar(30) NOT NULL,
  `CUSHOMENUM` varchar(90) NOT NULL,
  `STREETADD` text NOT NULL,
  `BRGYADD` text NOT NULL,
  `CITYADD` text NOT NULL,
  `PROVINCE` varchar(80) NOT NULL,
  `COUNTRY` varchar(30) NOT NULL,
  `DBIRTH` date NOT NULL,
  `GENDER` varchar(10) NOT NULL,
  `PHONE` varchar(20) NOT NULL,
  `EMAILADD` varchar(120) NOT NULL,
  `ZIPCODE` int(6) NOT NULL,
  `CUSUNAME` varchar(120) NOT NULL,
  `CUSPASS` varchar(90) NOT NULL,
  `CUSPHOTO` varchar(255) NOT NULL,
  `TERMS` tinyint(4) NOT NULL,
  `DATEJOIN` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcustomer`
--

INSERT INTO `tblcustomer` (`CUSTOMERID`, `FNAME`, `LNAME`, `MNAME`, `CUSHOMENUM`, `STREETADD`, `BRGYADD`, `CITYADD`, `PROVINCE`, `COUNTRY`, `DBIRTH`, `GENDER`, `PHONE`, `EMAILADD`, `ZIPCODE`, `CUSUNAME`, `CUSPASS`, `CUSPHOTO`, `TERMS`, `DATEJOIN`) VALUES
(1, 'janobe', 'Palacios', '', '321', 'Coloso Street', 'brgy. 1', 'Kabankalan City', 'Negros Occidental', 'Philippines', '0000-00-00', 'Male', '+639956112920', '', 6111, 'kenjie@yahoo.com', '1dd4efc811372cd1efe855981a8863d10ddde1ca', 'customer_image/a1157016c5d8272126380b27a59e2e7e.jpg', 1, '2015-11-26'),
(2, 'Mark Anthony', 'Geasin', '', '1234', 'paglaom', 'dancalan', 'ilog', 'negros occ', 'philippines', '0000-00-00', '', '091023333234', '', 6111, 'bboy', '0377588176145a8f0d837ff6e9bf0c1616268387', 'customer_image/10801930_959054964122877_391305007291646162_n.jpg', 1, '2015-11-26'),
(3, 'Jano', 'Palacios', '', '12312', 's', 'brgy 1', 'kabankalan city', 'negross occidental', 'philippines', '0000-00-00', 'Male', '21312312312', '', 6111, 'jan', '53199fa57fdf5676d03d89fbdd26e69a927766fc', 'customer_image/Tropical-Beach-Wallpaper.jpg', 1, '2017-12-08'),
(4, 'Jamei', 'Laveste', '', '', '', '', 'kabankalan city', '', '', '0000-00-00', 'Female', '362656556', '', 0, 'jame', 'f144dcce05af4d40fa0aeba34b05f4472472a4de', 'customer_image/1351064148bpguarhW.jpg', 1, '2018-01-23'),
(5, 'Jeanniebe', 'Palacios', '', '', '', '', 'Kab City', '', '', '0000-00-00', 'Female', '+639486457414', '', 0, 'bebe', 'd079a1c06803587ea09bff3f44a567e19169e7b5', '', 1, '2018-02-09'),
(6, 'Janry', 'Tan', '', '', '', '', 'Kab City', '', '', '0000-00-00', 'Male', '0234234', '', 0, 'jan', '0271c5467994a9e88e01be5b7e1f5f43d0ab93d2', '', 1, '2018-04-01'),
(7, 'Jake', 'Cuenca', '', '', '', '', 'Kabankalan City', '', '', '0000-00-00', 'Male', '639305235027', '', 0, 'jake', '403ba16f713c8371eef121530a922824be29b68a', '', 1, '2018-04-16'),
(8, 'Jake', 'Tam', '', '', '', '', 'Kab City', '', '', '0000-00-00', 'Male', '021312312', '', 0, 'j', '30e1fe53111f7e583c382596a32885fd27283970', '', 1, '2018-09-23'),
(9, 'Annie', 'Paredes', '', '', '', '', 's', '', '', '0000-00-00', 'Female', '12312312', '', 0, 'an', 'aa46142b604e671794a84129896d4dec508dec81', 'customer_image/shirt2.jpg', 1, '2019-08-20');

-- --------------------------------------------------------

--
-- Table structure for table `tblorder`
--

CREATE TABLE `tblorder` (
  `ORDERID` int(11) NOT NULL,
  `PROID` int(11) NOT NULL,
  `ORDEREDQTY` int(11) NOT NULL,
  `ORDEREDPRICE` double NOT NULL,
  `ORDEREDNUM` int(11) NOT NULL,
  `USERID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblorder`
--

INSERT INTO `tblorder` (`ORDERID`, `PROID`, `ORDEREDQTY`, `ORDEREDPRICE`, `ORDEREDNUM`, `USERID`) VALUES
(1, 201737, 4, 476, 93, 0),
(2, 201740, 3, 447, 93, 0),
(3, 201738, 1, 199, 94, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `PROID` int(11) NOT NULL,
  `PRODESC` varchar(255) DEFAULT NULL,
  `INGREDIENTS` varchar(255) NOT NULL,
  `PROQTY` int(11) DEFAULT NULL,
  `ORIGINALPRICE` double NOT NULL,
  `PROPRICE` double DEFAULT NULL,
  `CATEGID` int(11) DEFAULT NULL,
  `IMAGES` varchar(255) DEFAULT NULL,
  `PROSTATS` varchar(30) DEFAULT NULL,
  `OWNERNAME` varchar(90) NOT NULL,
  `OWNERPHONE` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblproduct`
--

INSERT INTO `tblproduct` (`PROID`, `PRODESC`, `INGREDIENTS`, `PROQTY`, `ORIGINALPRICE`, `PROPRICE`, `CATEGID`, `IMAGES`, `PROSTATS`, `OWNERNAME`, `OWNERPHONE`) VALUES
(201737, 'KILY Korean Casual Sleeveless Dress Printed Dress 5a0019                      ', '', 5, 100, 119, 12, 'uploaded_photos/korean.jpeg', 'Available', 'janobe', ''),
(201738, 'terno top and pants korean fashion boho terno summer terno for women  ', '', 3, 150, 199, 12, 'uploaded_photos/terno.jpg', 'Available', 'janobe', ''),
(201739, '4Color Menâ€²S Denim Pants STRETCHABLE Skinny Black/Blue', '', 5, 250, 289, 18, 'uploaded_photos/jeans.jpg', 'Available', 'janobe', ''),
(201740, 'SIMPLE Fashion Men`S Casual T Shirt Short Sleeve Round neck Top', '', 1, 100, 149, 18, 'uploaded_photos/shirt.jpg', 'Available', 'janobe', ''),
(201741, 'ICM #T146 BESTSELLER TOPS TSHIRT FOR MEN', '', 4, 50, 89, 18, 'uploaded_photos/shirt2.jpg', 'Available', 'janobe', ''),
(201742, 'CJY-001 Coat Rack Creative Simple CoatRack Bedroom Wardrobe (Gray)', '', 4, 250, 287, 14, 'uploaded_photos/bed.jpeg', 'Available', 'janobe', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblpromopro`
--

CREATE TABLE `tblpromopro` (
  `PROMOID` int(11) NOT NULL,
  `PROID` int(11) NOT NULL,
  `PRODISCOUNT` double NOT NULL,
  `PRODISPRICE` double NOT NULL,
  `PROBANNER` tinyint(4) NOT NULL,
  `PRONEW` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblpromopro`
--

INSERT INTO `tblpromopro` (`PROMOID`, `PROID`, `PRODISCOUNT`, `PRODISPRICE`, `PROBANNER`, `PRONEW`) VALUES
(1, 201737, 0, 119, 0, 0),
(2, 201738, 0, 199, 0, 0),
(3, 201739, 0, 289, 0, 0),
(4, 201740, 0, 149, 0, 0),
(5, 201741, 0, 89, 0, 0),
(6, 201742, 0, 287, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblsetting`
--

CREATE TABLE `tblsetting` (
  `SETTINGID` int(11) NOT NULL,
  `PLACE` text NOT NULL,
  `BRGY` varchar(90) NOT NULL,
  `DELPRICE` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblsetting`
--

INSERT INTO `tblsetting` (`SETTINGID`, `PLACE`, `BRGY`, `DELPRICE`) VALUES
(1, 'Kabankalan City', 'Brgy. 1', 50),
(2, 'Himamaylan City', 'Brgy. 1', 70);

-- --------------------------------------------------------

--
-- Table structure for table `tblstockin`
--

CREATE TABLE `tblstockin` (
  `STOCKINID` int(11) NOT NULL,
  `STOCKDATE` datetime DEFAULT NULL,
  `PROID` int(11) DEFAULT NULL,
  `STOCKQTY` int(11) DEFAULT NULL,
  `STOCKPRICE` double DEFAULT NULL,
  `USERID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblsummary`
--

CREATE TABLE `tblsummary` (
  `SUMMARYID` int(11) NOT NULL,
  `ORDEREDDATE` datetime NOT NULL,
  `CUSTOMERID` int(11) NOT NULL,
  `ORDEREDNUM` int(11) NOT NULL,
  `DELFEE` double NOT NULL,
  `PAYMENT` double NOT NULL,
  `PAYMENTMETHOD` varchar(30) NOT NULL,
  `ORDEREDSTATS` varchar(30) NOT NULL,
  `ORDEREDREMARKS` varchar(125) NOT NULL,
  `CLAIMEDADTE` datetime NOT NULL,
  `HVIEW` tinyint(4) NOT NULL,
  `USERID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblsummary`
--

INSERT INTO `tblsummary` (`SUMMARYID`, `ORDEREDDATE`, `CUSTOMERID`, `ORDEREDNUM`, `DELFEE`, `PAYMENT`, `PAYMENTMETHOD`, `ORDEREDSTATS`, `ORDEREDREMARKS`, `CLAIMEDADTE`, `HVIEW`, `USERID`) VALUES
(1, '2019-08-21 06:24:24', 9, 93, 0, 0, 'Cash on Delivery', 'Pending', 'Your order is on process.', '0000-00-00 00:00:00', 1, 0),
(3, '2019-08-21 06:27:09', 9, 94, 70, 269, 'Cash on Delivery', 'Confirmed', 'Your order has been confirmed.', '2019-08-21 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbluseraccount`
--

CREATE TABLE `tbluseraccount` (
  `USERID` int(11) NOT NULL,
  `U_NAME` varchar(122) NOT NULL,
  `U_USERNAME` varchar(122) NOT NULL,
  `U_PASS` varchar(122) NOT NULL,
  `U_ROLE` varchar(30) NOT NULL,
  `USERIMAGE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbluseraccount`
--

INSERT INTO `tbluseraccount` (`USERID`, `U_NAME`, `U_USERNAME`, `U_PASS`, `U_ROLE`, `USERIMAGE`) VALUES
(128, 'Administrator', 'admin@hmart.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Administrator', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblwishlist`
--

CREATE TABLE `tblwishlist` (
  `id` int(11) NOT NULL,
  `CUSID` int(11) NOT NULL,
  `PROID` int(11) NOT NULL,
  `WISHDATE` date NOT NULL,
  `WISHSTATS` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblwishlist`
--

INSERT INTO `tblwishlist` (`id`, `CUSID`, `PROID`, `WISHDATE`, `WISHSTATS`) VALUES
(2, 9, 201742, '2019-08-21', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messagein`
--
ALTER TABLE `messagein`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `messagelog`
--
ALTER TABLE `messagelog`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IDX_MessageId` (`MessageId`,`SendTime`);

--
-- Indexes for table `messageout`
--
ALTER TABLE `messageout`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IDX_IsRead` (`IsRead`);

--
-- Indexes for table `tblautonumber`
--
ALTER TABLE `tblautonumber`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`CATEGID`);

--
-- Indexes for table `tblcustomer`
--
ALTER TABLE `tblcustomer`
  ADD PRIMARY KEY (`CUSTOMERID`);

--
-- Indexes for table `tblorder`
--
ALTER TABLE `tblorder`
  ADD PRIMARY KEY (`ORDERID`),
  ADD KEY `USERID` (`USERID`),
  ADD KEY `PROID` (`PROID`),
  ADD KEY `ORDEREDNUM` (`ORDEREDNUM`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`PROID`),
  ADD KEY `CATEGID` (`CATEGID`);

--
-- Indexes for table `tblpromopro`
--
ALTER TABLE `tblpromopro`
  ADD PRIMARY KEY (`PROMOID`),
  ADD UNIQUE KEY `PROID` (`PROID`);

--
-- Indexes for table `tblsetting`
--
ALTER TABLE `tblsetting`
  ADD PRIMARY KEY (`SETTINGID`);

--
-- Indexes for table `tblstockin`
--
ALTER TABLE `tblstockin`
  ADD PRIMARY KEY (`STOCKINID`),
  ADD KEY `PROID` (`PROID`,`USERID`),
  ADD KEY `USERID` (`USERID`);

--
-- Indexes for table `tblsummary`
--
ALTER TABLE `tblsummary`
  ADD PRIMARY KEY (`SUMMARYID`),
  ADD UNIQUE KEY `ORDEREDNUM` (`ORDEREDNUM`),
  ADD KEY `CUSTOMERID` (`CUSTOMERID`),
  ADD KEY `USERID` (`USERID`);

--
-- Indexes for table `tbluseraccount`
--
ALTER TABLE `tbluseraccount`
  ADD PRIMARY KEY (`USERID`);

--
-- Indexes for table `tblwishlist`
--
ALTER TABLE `tblwishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messagein`
--
ALTER TABLE `messagein`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messagelog`
--
ALTER TABLE `messagelog`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `messageout`
--
ALTER TABLE `messageout`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblautonumber`
--
ALTER TABLE `tblautonumber`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `CATEGID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tblcustomer`
--
ALTER TABLE `tblcustomer`
  MODIFY `CUSTOMERID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblorder`
--
ALTER TABLE `tblorder`
  MODIFY `ORDERID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblpromopro`
--
ALTER TABLE `tblpromopro`
  MODIFY `PROMOID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblsetting`
--
ALTER TABLE `tblsetting`
  MODIFY `SETTINGID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblstockin`
--
ALTER TABLE `tblstockin`
  MODIFY `STOCKINID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblsummary`
--
ALTER TABLE `tblsummary`
  MODIFY `SUMMARYID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbluseraccount`
--
ALTER TABLE `tbluseraccount`
  MODIFY `USERID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `tblwishlist`
--
ALTER TABLE `tblwishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- ========================================================
-- MIGRATIONS EXPANSION TABLES
-- ========================================================

-- MySQL database schema expansion script for H-Mart

-- 1. Activity Log / Audit Trail
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `log_id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT NOT NULL,
  `action` VARCHAR(50) NOT NULL, -- create, update, delete, login, export
  `target_table` VARCHAR(100) NOT NULL,
  `old_values` TEXT DEFAULT NULL, -- JSON format of changed properties
  `new_values` TEXT DEFAULT NULL, -- JSON format of updated properties
  `ip_address` VARCHAR(45) NOT NULL,
  `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Email Marketing Tables
CREATE TABLE IF NOT EXISTS `email_lists` (
  `list_id` INT AUTO_INCREMENT PRIMARY KEY,
  `list_name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_campaigns` (
  `campaign_id` INT AUTO_INCREMENT PRIMARY KEY,
  `campaign_title` VARCHAR(150) NOT NULL,
  `subject_line` VARCHAR(255) NOT NULL,
  `content_html` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Draft', -- Draft, Scheduled, Sent
  `scheduled_at` DATETIME DEFAULT NULL,
  `sent_at` DATETIME DEFAULT NULL,
  `list_id` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_opens_clicks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `campaign_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `action_type` VARCHAR(20) NOT NULL, -- open, click
  `link_url` VARCHAR(255) DEFAULT NULL,
  `recorded_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_queue` (
  `queue_id` INT AUTO_INCREMENT PRIMARY KEY,
  `campaign_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `email_address` VARCHAR(100) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Pending', -- Pending, Sent, Failed
  `attempts` INT DEFAULT 0,
  `error_message` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `sent_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Push Notifications Configuration & Log
CREATE TABLE IF NOT EXISTS `push_subscriptions` (
  `subscription_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_type` VARCHAR(20) NOT NULL, -- Admin, Customer
  `user_id` INT NOT NULL,
  `endpoint` TEXT NOT NULL,
  `p256dh` VARCHAR(255) NOT NULL,
  `auth` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `push_notifications_log` (
  `log_id` INT AUTO_INCREMENT PRIMARY KEY,
  `target_segment` VARCHAR(50) NOT NULL, -- Admins, All Customers, VIPs
  `title` VARCHAR(150) NOT NULL,
  `body` TEXT NOT NULL,
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. SMS Config & Alerts Log
CREATE TABLE IF NOT EXISTS `sms_alerts_config` (
  `config_id` INT AUTO_INCREMENT PRIMARY KEY,
  `alert_type` VARCHAR(50) NOT NULL, -- high_value_order, fraud, critical_stock, back_in_stock
  `enabled` TINYINT DEFAULT 1,
  `recipient_phone` VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sms_logs` (
  `sms_id` INT AUTO_INCREMENT PRIMARY KEY,
  `phone_number` VARCHAR(30) NOT NULL,
  `message_body` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Sent', -- Sent, Failed
  `error_message` TEXT DEFAULT NULL,
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Multi-language Translation Cache
CREATE TABLE IF NOT EXISTS `translations_cache` (
  `translation_id` INT AUTO_INCREMENT PRIMARY KEY,
  `lang_code` VARCHAR(10) NOT NULL, -- en, es, fr, de, ar
  `text_key` VARCHAR(255) NOT NULL,
  `translated_text` TEXT NOT NULL,
  INDEX (`lang_code`, `text_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Multi-currency Manager
CREATE TABLE IF NOT EXISTS `currencies` (
  `currency_id` INT AUTO_INCREMENT PRIMARY KEY,
  `currency_code` VARCHAR(10) UNIQUE NOT NULL, -- USD, EUR, INR
  `currency_symbol` VARCHAR(10) NOT NULL, -- $, €, ₹
  `exchange_rate` DOUBLE NOT NULL DEFAULT 1.0, -- relative to base currency (e.g. INR)
  `is_base` TINYINT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `exchange_rates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `currency_code` VARCHAR(10) NOT NULL,
  `rate` DOUBLE NOT NULL,
  `last_updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Backup Log
CREATE TABLE IF NOT EXISTS `backup_logs` (
  `backup_id` INT AUTO_INCREMENT PRIMARY KEY,
  `file_name` VARCHAR(255) NOT NULL,
  `file_size_bytes` BIGINT NOT NULL,
  `storage_location` VARCHAR(100) NOT NULL, -- Local, S3, FTP
  `status` VARCHAR(20) DEFAULT 'Success', -- Success, Failed
  `error_details` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. System Health Metrics & Alerts
CREATE TABLE IF NOT EXISTS `health_metrics` (
  `metric_id` INT AUTO_INCREMENT PRIMARY KEY,
  `cpu_usage_pct` DOUBLE NOT NULL,
  `memory_usage_pct` DOUBLE NOT NULL,
  `disk_usage_pct` DOUBLE NOT NULL,
  `mysql_ping_ms` INT NOT NULL,
  `microservice_ping_ms` INT NOT NULL,
  `recorded_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `health_alerts` (
  `alert_id` INT AUTO_INCREMENT PRIMARY KEY,
  `component` VARCHAR(50) NOT NULL, -- CPU, MySQL, Microservice
  `alert_message` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active', -- Active, Resolved
  `notified_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `resolved_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Support Ticket System (Admin & Customer Support)
CREATE TABLE IF NOT EXISTS `support_tickets` (
  `ticket_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `order_number` INT DEFAULT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `category` VARCHAR(50) NOT NULL, -- Return, Refund, Product Inquiry, Payment Issue
  `status` VARCHAR(30) DEFAULT 'Open', -- Open, Assigned, Resolved, Closed
  `priority` VARCHAR(20) DEFAULT 'Medium', -- Low, Medium, High
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ticket_replies` (
  `reply_id` INT AUTO_INCREMENT PRIMARY KEY,
  `ticket_id` INT NOT NULL,
  `sender_type` VARCHAR(20) NOT NULL, -- Customer, Admin
  `sender_id` INT NOT NULL,
  `message_body` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ticket_assignments` (
  `assignment_id` INT AUTO_INCREMENT PRIMARY KEY,
  `ticket_id` INT NOT NULL,
  `agent_id` INT NOT NULL,
  `assigned_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Multi-site / Country Manager
CREATE TABLE IF NOT EXISTS `sites` (
  `site_id` INT AUTO_INCREMENT PRIMARY KEY,
  `site_name` VARCHAR(100) NOT NULL,
  `country_code` VARCHAR(5) NOT NULL, -- US, ES, IN
  `currency_code` VARCHAR(5) NOT NULL,
  `language_code` VARCHAR(5) NOT NULL,
  `tax_rate` DOUBLE NOT NULL DEFAULT 0.0,
  `timezone` VARCHAR(100) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Customer Activity logs
CREATE TABLE IF NOT EXISTS `customer_activity_log` (
  `activity_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `action` VARCHAR(100) NOT NULL, -- login, view_product, search, add_to_cart, purchase
  `details` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Customer Notification Preferences
CREATE TABLE IF NOT EXISTS `customer_notification_preferences` (
  `preference_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT UNIQUE NOT NULL,
  `order_updates_sms` TINYINT DEFAULT 1,
  `order_updates_email` TINYINT DEFAULT 1,
  `promotions_sms` TINYINT DEFAULT 0,
  `promotions_email` TINYINT DEFAULT 1,
  `back_in_stock_email` TINYINT DEFAULT 1,
  `price_drop_email` TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. Customer Wishlists & Wishlist Items Expansion
CREATE TABLE IF NOT EXISTS `customer_wishlists` (
  `wishlist_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `wishlist_name` VARCHAR(100) NOT NULL,
  `is_default` TINYINT DEFAULT 0,
  `share_token` VARCHAR(100) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `wishlist_items` (
  `item_id` INT AUTO_INCREMENT PRIMARY KEY,
  `wishlist_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `added_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. Product Spec Comparison
CREATE TABLE IF NOT EXISTS `product_comparisons` (
  `comparison_id` INT AUTO_INCREMENT PRIMARY KEY,
  `session_id` VARCHAR(100) NOT NULL,
  `product_id` INT NOT NULL,
  `added_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 15. Customer Product Reviews & Ratings (Extending beyond BERT sentiment analysis)
CREATE TABLE IF NOT EXISTS `customer_reviews` (
  `review_id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `rating` INT NOT NULL, -- 1 to 5
  `review_title` VARCHAR(150) NOT NULL,
  `review_text` TEXT NOT NULL,
  `review_photo` VARCHAR(255) DEFAULT NULL,
  `is_verified_purchase` TINYINT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'Approved',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `review_votes` (
  `vote_id` INT AUTO_INCREMENT PRIMARY KEY,
  `review_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `vote_type` VARCHAR(10) NOT NULL, -- helpful, unhelpful
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `review_qna` (
  `qna_id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `question` TEXT NOT NULL,
  `answer` TEXT DEFAULT NULL,
  `answered_by_admin_id` INT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `answered_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 16. Back In Stock Alerts & Price Drop Alerts
CREATE TABLE IF NOT EXISTS `back_in_stock_alerts` (
  `alert_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active', -- Active, Notified
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `price_drop_alerts` (
  `alert_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `target_price` DOUBLE NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active', -- Active, Notified
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 17. Abandoned Cart Tracking & Recovery Campaign Metrics
CREATE TABLE IF NOT EXISTS `abandoned_carts` (
  `abandoned_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `cart_details_json` TEXT NOT NULL,
  `last_active_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` VARCHAR(30) DEFAULT 'Abandoned' -- Abandoned, Recovered, Emailed
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recovery_attempts` (
  `attempt_id` INT AUTO_INCREMENT PRIMARY KEY,
  `abandoned_id` INT NOT NULL,
  `method` VARCHAR(20) NOT NULL, -- Email, Push
  `discount_offered_code` VARCHAR(50) DEFAULT NULL,
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recovery_conversions` (
  `conversion_id` INT AUTO_INCREMENT PRIMARY KEY,
  `abandoned_id` INT NOT NULL,
  `order_number` INT NOT NULL,
  `recovered_amount` DOUBLE NOT NULL,
  `recovered_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ========================================================
-- SMART FEATURES TABLES
-- ========================================================

-- Smart E-Commerce ML & Security Features
-- Run in phpMyAdmin on database: db_ecommerce

CREATE TABLE IF NOT EXISTS `tbl_otp_codes` (
  `OTP_ID` int(11) NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(120) NOT NULL,
  `OTP_CODE` varchar(45) NOT NULL,
  `PURPOSE` enum('login','signup','reset') NOT NULL DEFAULT 'login',
  `EXPIRES_AT` datetime NOT NULL,
  `IS_USED` tinyint(1) NOT NULL DEFAULT 0,
  `CREATED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OTP_ID`),
  KEY `idx_otp_email` (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_browse_history` (
  `HISTORY_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUSTOMERID` int(11) DEFAULT NULL,
  `PROID` int(11) NOT NULL,
  `CATEGID` int(11) DEFAULT NULL,
  `SESSION_ID` varchar(64) DEFAULT NULL,
  `VIEWED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`HISTORY_ID`),
  KEY `idx_browse_customer` (`CUSTOMERID`),
  KEY `idx_browse_proid` (`PROID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_login_attempts` (
  `ATTEMPT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(120) NOT NULL,
  `IP_ADDRESS` varchar(45) NOT NULL,
  `SUCCESS` tinyint(1) NOT NULL DEFAULT 0,
  `ATTEMPTED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ATTEMPT_ID`),
  KEY `idx_login_ip` (`IP_ADDRESS`),
  KEY `idx_login_user` (`USERNAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_payment_attempts` (
  `PAY_ATTEMPT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUSTOMERID` int(11) DEFAULT NULL,
  `ORDEREDNUM` int(11) DEFAULT NULL,
  `PAYMENT_METHOD` varchar(40) NOT NULL,
  `AMOUNT` double NOT NULL DEFAULT 0,
  `STATUS` enum('success','failed','blocked') NOT NULL DEFAULT 'failed',
  `FAILURE_REASON` varchar(255) DEFAULT NULL,
  `IP_ADDRESS` varchar(45) DEFAULT NULL,
  `ATTEMPTED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PAY_ATTEMPT_ID`),
  KEY `idx_pay_customer` (`CUSTOMERID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_fraud_alerts` (
  `ALERT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUSTOMERID` int(11) DEFAULT NULL,
  `ALERT_TYPE` varchar(60) NOT NULL,
  `SEVERITY` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `DESCRIPTION` text NOT NULL,
  `META_JSON` text,
  `IS_RESOLVED` tinyint(1) NOT NULL DEFAULT 0,
  `CREATED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ALERT_ID`),
  KEY `idx_fraud_resolved` (`IS_RESOLVED`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_inventory_alerts` (
  `INV_ALERT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROID` int(11) NOT NULL,
  `ALERT_TYPE` enum('low_stock','fast_moving','slow_moving') NOT NULL,
  `MESSAGE` varchar(255) NOT NULL,
  `CREATED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`INV_ALERT_ID`),
  KEY `idx_inv_proid` (`PROID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ========================================================
-- ADMIN DASHBOARD AI TABLES
-- ========================================================

-- H-Mart Admin Dashboard Expansion Schema Migrations
-- Created: 2026-05-31

CREATE TABLE IF NOT EXISTS `demand_forecasts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `forecast_date` DATE NOT NULL,
  `predicted_demand` DOUBLE NOT NULL,
  `recommended_reorder_qty` INT NOT NULL,
  `accuracy_metric` DOUBLE NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `churn_scores` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `churn_probability` DOUBLE NOT NULL,
  `risk_level` VARCHAR(20) NOT NULL,
  `top_risk_factors` TEXT NOT NULL,
  `evaluated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `product_reviews_sentiment` (
  `review_id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `review_text` TEXT NOT NULL,
  `rating` INT NOT NULL,
  `sentiment_label` VARCHAR(20) NOT NULL,
  `sentiment_score` DOUBLE NOT NULL,
  `topics_extracted` TEXT DEFAULT NULL,
  `is_fake` TINYINT DEFAULT 0,
  `is_fake_confidence` DOUBLE DEFAULT 0.0,
  `reviewed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`product_id`),
  INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recommendations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `recommendation_type` VARCHAR(50) NOT NULL,
  `score` DOUBLE NOT NULL,
  `generated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`customer_id`),
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recommendations_tracking` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `recommendation_type` VARCHAR(50) NOT NULL,
  `action` VARCHAR(20) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `dynamic_pricing_suggestions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `base_price` DOUBLE NOT NULL,
  `suggested_price` DOUBLE NOT NULL,
  `expected_revenue_lift` DOUBLE NOT NULL,
  `confidence_score` DOUBLE NOT NULL,
  `reasons` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `price_ab_tests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `price_a` DOUBLE NOT NULL,
  `price_b` DOUBLE NOT NULL,
  `group_a_sales` INT DEFAULT 0,
  `group_b_sales` INT DEFAULT 0,
  `group_a_revenue` DOUBLE DEFAULT 0.0,
  `group_b_revenue` DOUBLE DEFAULT 0.0,
  `start_date` DATE NOT NULL,
  `end_date` DATE DEFAULT NULL,
  `status` VARCHAR(20) DEFAULT 'running',
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `returns` (
  `return_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `order_number` INT NOT NULL,
  `return_status` VARCHAR(20) DEFAULT 'Pending',
  `request_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `reason_summary` VARCHAR(255) NOT NULL,
  `refund_amount` DOUBLE NOT NULL,
  INDEX (`customer_id`),
  INDEX (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `return_items` (
  `return_item_id` INT AUTO_INCREMENT PRIMARY KEY,
  `return_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `return_reason_code` VARCHAR(50) NOT NULL,
  INDEX (`return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `refunds` (
  `refund_id` INT AUTO_INCREMENT PRIMARY KEY,
  `return_id` INT NOT NULL,
  `transaction_reference` VARCHAR(100) NOT NULL,
  `refund_method` VARCHAR(50) NOT NULL,
  `refund_status` VARCHAR(20) DEFAULT 'Pending',
  `processed_at` DATETIME DEFAULT NULL,
  INDEX (`return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `coupons` (
  `coupon_id` INT AUTO_INCREMENT PRIMARY KEY,
  `coupon_code` VARCHAR(50) UNIQUE NOT NULL,
  `type` VARCHAR(20) NOT NULL,
  `value` DOUBLE NOT NULL,
  `start_date` DATE NOT NULL,
  `expiry_date` DATE NOT NULL,
  `usage_limit` INT NOT NULL,
  `times_used` INT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'active',
  `min_spend` DOUBLE DEFAULT 0.0,
  `max_spend` DOUBLE DEFAULT 999999.0,
  `target_segment` VARCHAR(50) DEFAULT 'All'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `coupon_usage` (
  `usage_id` INT AUTO_INCREMENT PRIMARY KEY,
  `coupon_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `order_number` INT NOT NULL,
  `discount_applied` DOUBLE NOT NULL,
  `used_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`coupon_id`),
  INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `shipping_tracking` (
  `tracking_id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` INT UNIQUE NOT NULL,
  `carrier` VARCHAR(50) NOT NULL,
  `tracking_number` VARCHAR(100) NOT NULL,
  `status` VARCHAR(30) DEFAULT 'Order Placed',
  `origin_lat` DOUBLE NOT NULL,
  `origin_lng` DOUBLE NOT NULL,
  `current_lat` DOUBLE NOT NULL,
  `current_lng` DOUBLE NOT NULL,
  `dest_lat` DOUBLE NOT NULL,
  `dest_lng` DOUBLE NOT NULL,
  `eta_delivery` DATETIME NOT NULL,
  `actual_delivery` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `shipping_updates` (
  `update_id` INT AUTO_INCREMENT PRIMARY KEY,
  `tracking_id` INT NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `status_details` TEXT NOT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`tracking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `vendors` (
  `vendor_id` INT AUTO_INCREMENT PRIMARY KEY,
  `vendor_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `address` TEXT NOT NULL,
  `rating` DOUBLE DEFAULT 5.0,
  `status` VARCHAR(20) DEFAULT 'Active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `vendor_products` (
  `vendor_product_id` INT AUTO_INCREMENT PRIMARY KEY,
  `vendor_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `cost_price` DOUBLE NOT NULL,
  `lead_time_days` INT NOT NULL,
  INDEX (`vendor_id`),
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `purchase_orders` (
  `po_id` INT AUTO_INCREMENT PRIMARY KEY,
  `po_number` VARCHAR(50) UNIQUE NOT NULL,
  `vendor_id` INT NOT NULL,
  `status` VARCHAR(30) DEFAULT 'Draft',
  `total_amount` DOUBLE NOT NULL,
  `expected_delivery` DATE NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `vendor_payouts` (
  `payout_id` INT AUTO_INCREMENT PRIMARY KEY,
  `vendor_id` INT NOT NULL,
  `po_id` INT NOT NULL,
  `amount` DOUBLE NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Unpaid',
  `processed_at` DATETIME DEFAULT NULL,
  INDEX (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `low_stock_alerts` (
  `alert_id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `threshold` INT NOT NULL,
  `current_stock` INT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active',
  `notified_at` DATETIME DEFAULT NULL,
  `resolved_at` DATETIME DEFAULT NULL,
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `python_ai_logs` (
  `log_id` INT AUTO_INCREMENT PRIMARY KEY,
  `endpoint` VARCHAR(100) NOT NULL,
  `request_payload` TEXT DEFAULT NULL,
  `response_payload` TEXT DEFAULT NULL,
  `execution_time_ms` INT NOT NULL,
  `success` TINYINT DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ========================================================
-- SEED DATA FOR NEW TABLES
-- ========================================================

-- H-Mart Admin Dashboard Expansion Seed Data
-- Created: 2026-05-31

-- 1. Vendors Seed
INSERT IGNORE INTO `vendors` (`vendor_id`, `vendor_name`, `email`, `phone`, `address`, `rating`, `status`) VALUES
(1, 'Fresh Farms Ltd', 'info@freshfarms.com', '+91-9988776655', 'Farm Estate, Zone A, Bacolod City', 4.8, 'Active'),
(2, 'StyleHub Importers', 'wholesale@stylehub.com', '+91-8877665544', 'Sector 12, Industrial Hub, Manila', 4.2, 'Active'),
(3, 'SmartLogistics & Goods', 'orders@smartlogistics.com', '+91-7766554433', 'Warehouse Road, Block B, Iloilo City', 4.5, 'Active');

-- 2. Vendor Products Seed (mapping existing products)
INSERT IGNORE INTO `vendor_products` (`vendor_id`, `product_id`, `cost_price`, `lead_time_days`) VALUES
(2, 201737, 80.00, 5),
(2, 201738, 120.00, 6),
(2, 201739, 180.00, 4),
(2, 201740, 70.00, 5),
(2, 201741, 45.00, 3),
(3, 201742, 190.00, 8);

-- 3. Purchase Orders Seed
INSERT IGNORE INTO `purchase_orders` (`po_id`, `po_number`, `vendor_id`, `status`, `total_amount`, `expected_delivery`, `created_at`) VALUES
(1, 'PO-2026-0001', 2, 'Sent', 4850.00, DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), CURRENT_TIMESTAMP),
(2, 'PO-2026-0002', 3, 'Draft', 1900.00, DATE_ADD(CURRENT_DATE, INTERVAL 8 DAY), CURRENT_TIMESTAMP);

-- 4. Vendor Payouts Seed
INSERT IGNORE INTO `vendor_payouts` (`vendor_id`, `po_id`, `amount`, `status`, `processed_at`) VALUES
(2, 1, 4850.00, 'Unpaid', NULL);

-- 5. Returns & Refunds Seed
INSERT IGNORE INTO `returns` (`return_id`, `customer_id`, `order_number`, `return_status`, `request_date`, `reason_summary`, `refund_amount`) VALUES
(1, 9, 93, 'Pending', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 2 DAY), 'Received wrong size for the Korean Casual Dress.', 119.00),
(2, 9, 94, 'Refunded', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 10 DAY), 'Damaged top with a slight tear.', 199.00);

INSERT IGNORE INTO `return_items` (`return_id`, `product_id`, `quantity`, `return_reason_code`) VALUES
(1, 201737, 1, 'wrong_item'),
(2, 201738, 1, 'damaged');

INSERT IGNORE INTO `refunds` (`return_id`, `transaction_reference`, `refund_method`, `refund_status`, `processed_at`) VALUES
(2, 'REF-2019-0822-948', 'Cash on Delivery', 'Success', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 9 DAY));

-- 6. Coupons Seed
INSERT IGNORE INTO `coupons` (`coupon_id`, `coupon_code`, `type`, `value`, `start_date`, `expiry_date`, `usage_limit`, `times_used`, `status`, `min_spend`, `max_spend`, `target_segment`) VALUES
(1, 'HMART10', 'percent', 10.0, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), 500, 1, 'active', 200.0, 5000.0, 'All'),
(2, 'FRESH50', 'fixed', 50.0, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), 100, 0, 'active', 500.0, 9999.0, 'VIP'),
(3, 'MISSYOU25', 'percent', 25.0, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), 50, 0, 'active', 100.0, 2000.0, 'Churn_Risk');

INSERT IGNORE INTO `coupon_usage` (`coupon_id`, `customer_id`, `order_number`, `discount_applied`) VALUES
(1, 9, 94, 19.90);

-- 7. Shipping Tracking Seed
INSERT IGNORE INTO `shipping_tracking` (`tracking_id`, `order_number`, `carrier`, `tracking_number`, `status`, `origin_lat`, `origin_lng`, `current_lat`, `current_lng`, `dest_lat`, `dest_lng`, `eta_delivery`, `actual_delivery`) VALUES
(1, 93, 'H-Mart Delivery', 'HM-SH-0093', 'In Transit', 10.6698, 122.9563, 10.1524, 122.8912, 10.0264, 122.8123, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY), NULL),
(2, 94, 'DHL Express', 'DH-SH-0094', 'Delivered', 10.6698, 122.9563, 10.0984, 122.8715, 10.0984, 122.8715, DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY), DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY));

INSERT IGNORE INTO `shipping_updates` (`tracking_id`, `location`, `status_details`, `updated_at`) VALUES
(1, 'Bacolod Main Warehouse', 'Package sorted and scanned.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 12 HOUR)),
(1, 'Bago City Transit Station', 'In transit towards southern destination.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 4 HOUR)),
(2, 'Bacolod Main Warehouse', 'Shipment picked up.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 2 DAY)),
(2, 'Himamaylan Hub', 'Arrived at delivery terminal.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)),
(2, 'Himamaylan Customer Home', 'Delivered and signed by Annie.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY));

-- 8. Low Stock Alerts Seed
INSERT IGNORE INTO `low_stock_alerts` (`product_id`, `threshold`, `current_stock`, `status`, `notified_at`) VALUES
(201737, 10, 5, 'Active', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)),
(201740, 5, 1, 'Active', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 12 HOUR));

-- 9. Demand Forecasting Seed (30-day forecast curves)
INSERT IGNORE INTO `demand_forecasts` (`product_id`, `forecast_date`, `predicted_demand`, `recommended_reorder_qty`, `accuracy_metric`) VALUES
(201737, DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), 12.5, 0, 92.4),
(201737, DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), 14.2, 15, 92.4),
(201737, DATE_ADD(CURRENT_DATE, INTERVAL 15 DAY), 18.0, 20, 92.4),
(201738, DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), 8.1, 0, 89.5),
(201738, DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), 9.5, 10, 89.5),
(201739, DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), 19.3, 25, 94.1);

-- 10. Churn Predictions Seed
INSERT IGNORE INTO `churn_scores` (`customer_id`, `churn_probability`, `risk_level`, `top_risk_factors`) VALUES
(1, 14.5, 'Low', '[]'),
(2, 34.0, 'Medium', '["Purchase gap exceeds 45 days"]'),
(3, 78.2, 'High', '["No orders placed in last 90 days", "Negative review sentiment logged"]'),
(9, 12.0, 'Low', '[]');

-- 11. Review Sentiment Seed
INSERT IGNORE INTO `product_reviews_sentiment` (`review_id`, `product_id`, `customer_id`, `review_text`, `rating`, `sentiment_label`, `sentiment_score`, `topics_extracted`, `is_fake`, `is_fake_confidence`) VALUES
(1, 201737, 9, 'Really beautiful dress, fits perfectly and the fabric is very soft.', 5, 'Positive', 0.98, '["size", "fit", "fabric", "dress"]', 0, 2.5),
(2, 201738, 9, 'Okay product, but stitching was a bit loose. Delivery was fast though.', 3, 'Neutral', 0.51, '["stitching", "delivery"]', 0, 12.0),
(3, 201740, 3, 'Worst experience. The color faded completely on the first wash and it shrank! Do not buy!', 1, 'Negative', 0.99, '["color", "shrank", "wash", "quality"]', 0, 5.0);

-- 12. Recommendations Seed
INSERT IGNORE INTO `recommendations` (`customer_id`, `product_id`, `recommendation_type`, `score`) VALUES
(9, 201739, 'ALS', 0.89),
(9, 201741, 'ItemCF', 0.74),
(9, 201742, 'Trending', 0.95);

-- 13. Admin User Seed
INSERT IGNORE INTO `tbluseraccount` (`USERID`, `U_NAME`, `U_USERNAME`, `U_PASS`, `U_ROLE`, `USERIMAGE`) VALUES
(128, 'Admin', 'admin@hmart.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Administrator', '');
