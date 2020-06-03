-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2018 at 07:19 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Parent` int(11) NOT NULL,
  `Ordering` smallint(6) NOT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `Description`, `Parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(7, 'Tools', 'Tools', 0, 1, 0, 1, 0),
(8, 'Cell Phones', 'Mobile Phones', 0, 2, 0, 0, 0),
(9, 'Hand Made', 'Hande Made', 0, 3, 0, 0, 0),
(10, 'Computer', 'Computer', 0, 4, 0, 0, 0),
(11, 'Electronic', 'Electronics', 0, 5, 0, 0, 0),
(13, 'Hand made Boxes', 'Hand Made Boxes Tolls', 9, 2, 0, 0, 0),
(14, 'Dell Computers', 'Computer type', 10, 3, 0, 0, 0),
(15, 'New Hand Made Category', 'Hand Made Description', 9, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CommentID` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT '0',
  `Date` date NOT NULL,
  `UserID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`CommentID`, `Comment`, `Status`, `Date`, `UserID`, `ItemID`) VALUES
(1, 'Thansk very much', 1, '2018-06-08', 12, 7),
(2, 'This is Very Good Item', 1, '2018-06-09', 14, 12),
(3, 'This is Very Good Item', 1, '2018-06-09', 14, 12),
(4, 'This is Very Good Item', 1, '2018-06-09', 14, 12),
(5, 'This Is Very Good Item', 1, '2018-06-09', 15, 11),
(7, 'Very Good item', 1, '2018-06-09', 1, 11),
(8, 'Good Item Hany', 1, '2018-06-09', 11, 11),
(9, 'It is very good item thanks ', 1, '2018-06-12', 35, 7),
(10, 'Very Good Thanks very much', 1, '2018-06-12', 36, 7),
(11, 'hello very good\r\n', 1, '2018-06-13', 36, 32),
(12, 'its very good', 1, '2018-06-13', 11, 11),
(13, 'very good', 1, '2018-06-13', 11, 32),
(14, 'my item', 1, '2018-06-13', 11, 34),
(15, 'good computer games', 0, '2018-06-13', 11, 35);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemID` int(11) NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Adding_Date` date NOT NULL,
  `Country` varchar(255) NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT '0',
  `Rating` tinyint(4) NOT NULL,
  `CatID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `ItemAvatar` varchar(255) NOT NULL,
  `Tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemID`, `ItemName`, `Description`, `Price`, `Adding_Date`, `Country`, `Status`, `Rating`, `CatID`, `UserID`, `Approve`, `ItemAvatar`, `Tags`) VALUES
(7, 'Iphon 6 plus', 'very good mobile', '500$', '2018-06-02', 'india', 2, 0, 8, 17, 0, '', ''),
(9, 'Newtowk Cabel', 'Buliding Newtwork cable', '120$', '2018-06-02', 'USA', 1, 0, 10, 12, 1, '', ''),
(11, 'Needle', 'Needle Tools', '20$', '2018-06-04', 'China', 1, 0, 7, 15, 1, '', ''),
(12, 'Cabel', 'Cabel', '20 $', '2018-06-04', 'USA', 2, 0, 11, 14, 1, '', ''),
(15, 'Xbox GAmes', 'Computer Games', '120', '2018-06-09', 'USA', 1, 0, 10, 11, 1, '', ''),
(19, 'Item TEst two', 'test descritpion', '30', '2018-06-10', 'USA', 2, 0, 7, 11, 1, '', ''),
(22, 'Woden Game', 'Good Woden Game', '100', '2018-06-10', 'USa', 1, 0, 7, 22, 1, '', 'Hand, Made, tools,elkomy'),
(24, 'PS Game 4', 'Playstation Games and beatufifull game such as', '20', '2018-06-11', 'usa', 1, 0, 10, 11, 1, '', 'RPG,Online,Games'),
(28, 'item test', 'fore testing only', '20', '2018-06-12', 'test', 1, 0, 11, 36, 1, '1036635611_maxresdefault.jpg', ''),
(32, 'Item Preview', 'Item Preview Description', '120', '2018-06-13', 'USA', 1, 0, 9, 36, 1, '30423469_739ae93d09a2696c095c4dbd6867f029--graphic-design-typography-design-logos.jpg', ''),
(34, 'PlayStation 5 ', 'pLaystation 5 games', '500', '2018-06-13', 'usa', 1, 0, 9, 11, 1, '1180361760_32475803_358858197970276_8881021623914725376_n.jpg', ''),
(35, 'Computer Games', 'Computer Games description', '500', '2018-06-13', 'USA', 2, 0, 10, 11, 1, '258018560_11041124_956644137726419_4832727897713288134_n.png', 'Computer,Games,Online'),
(36, 'Wireless Mouse', 'WireLess Mous Laser,WireLess Mous Laser,WireLess Mous Laser', '200', '2018-06-13', 'USA', 3, 0, 10, 11, 1, '189554996_15400916_355909614786365_7903741183192493486_n.jpg', 'mouse,computer,wireless');

-- --------------------------------------------------------

--
-- Table structure for table `shopingcar`
--

CREATE TABLE `shopingcar` (
  `ٍShopingCarID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Amount` int(11) NOT NULL,
  `Price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `shopingcar`
--

INSERT INTO `shopingcar` (`ٍShopingCarID`, `ItemID`, `UserID`, `Amount`, `Price`) VALUES
(1, 36, 11, 0, 0),
(2, 36, 11, 2, 400),
(3, 34, 11, 2, 1000),
(4, 36, 11, 1, 200),
(5, 28, 11, 1, 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `GroupID` tinyint(4) NOT NULL DEFAULT '0',
  `TrustStatus` tinyint(4) NOT NULL DEFAULT '0',
  `RegStatus` tinyint(4) NOT NULL DEFAULT '0',
  `Date` date NOT NULL,
  `Avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Password`, `FullName`, `Email`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `Avatar`) VALUES
(1, 'mohamed', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'mohamed elkomy', 'M@M', 1, 0, 1, '2018-06-01', ''),
(11, 'Hanyma', '302e3033d7432b6b07dc781127468d07c28d9a83', 'Hany Matter', 'H@H', 1, 0, 1, '2018-06-01', '512121229_107b7992a1776f0c965a4872294f2b5c--personal-logo-personal-branding.jpg'),
(12, 'Yousef', 'e61861b496f7b15ac45ab19d6b6742e9ff1895b7', 'Yousef', 'Y@Y', 0, 0, 1, '2018-06-01', ''),
(13, 'Kamal', 'b0b4d9513005320005624fa20d35acacad77c6fd', 'Kamal', 'K@K', 0, 0, 1, '2018-06-01', ''),
(14, 'Samir', '7b4e6eeb003ca9305a1196bdd408e2e53e3ab760', 'Samir', 'S@S', 0, 0, 1, '2018-06-01', ''),
(15, 'Maher', '876a8678832b5784d9830801ca2b24f8fd44da7d', 'Maher', 'M@M', 0, 0, 0, '2018-06-01', ''),
(16, 'Naser', 'dbe092111c55447030b95a47c824f8964274c77d', 'Naser', 'N@N', 0, 0, 0, '2018-06-01', ''),
(17, 'Hesham', 'f7381d72287d52b7908b7872f3baf9d8ff7a2c52', 'Hesham', 'H@H', 0, 0, 1, '2018-06-01', ''),
(18, 'Reem', 'cc21eb26634fdddd3d53efceca2de9be51d1b00f', 'Reem', 'R@R', 0, 0, 1, '2018-06-02', ''),
(19, 'Malek', '328922fcfedbba70a0312fae41465c04cc9f5980', 'Malek', 'M@M', 0, 0, 1, '2018-06-02', ''),
(20, 'Torky', '601f1889667efaebb33b8c12572835da3f027f78', '', 'T@T.com', 0, 0, 0, '2018-06-04', ''),
(21, 'Yaman', '601f1889667efaebb33b8c12572835da3f027f78', '', 'Y@Y.com', 0, 0, 0, '2018-06-04', ''),
(22, 'Kenzy', '601f1889667efaebb33b8c12572835da3f027f78', '', 'K@K.com', 0, 0, 0, '2018-06-04', ''),
(23, 'ayman', '601f1889667efaebb33b8c12572835da3f027f78', '', 'a@a.com', 0, 0, 0, '2018-06-04', ''),
(27, 'Ahmed Elkomy', '601f1889667efaebb33b8c12572835da3f027f78', '', 'A@A.com', 0, 0, 0, '2018-06-11', ''),
(28, 'Ayman Elkomy', '601f1889667efaebb33b8c12572835da3f027f78', 'Ayman', 'A@A.com', 0, 0, 1, '2018-06-11', '435567690_avatar.png'),
(29, 'Glal Elkomy', '601f1889667efaebb33b8c12572835da3f027f78', '', 'G@G.com', 0, 0, 0, '2018-06-11', '579724157_'),
(30, 'Gamal Elkomy', '601f1889667efaebb33b8c12572835da3f027f78', '', 'A@A.com', 0, 0, 0, '2018-06-11', '973249797_avatar.png'),
(31, 'Asmaa Gamal', '601f1889667efaebb33b8c12572835da3f027f78', '', 'S@S.com', 0, 0, 0, '2018-06-11', '364436155_avatar.png'),
(32, 'Remas Elkomy', '601f1889667efaebb33b8c12572835da3f027f78', '', 'A@A.com', 0, 0, 0, '2018-06-11', '107751580_avatar.png'),
(33, 'MoSalah', '601f1889667efaebb33b8c12572835da3f027f78', 'Mohamed Salah', 'M@M.com', 0, 0, 1, '2018-06-12', '252295333_32313832_358858101303619_6139362274516664320_n.jpg'),
(34, 'Eman Saad', '601f1889667efaebb33b8c12572835da3f027f78', 'Eman Saad', 'E@E.com', 0, 0, 1, '2018-06-12', '455878690_CNpVqyqUEAA2IB3.jpg'),
(35, 'Hegazy', '601f1889667efaebb33b8c12572835da3f027f78', 'ahmed Hegazy', 'H@H.com', 0, 0, 1, '2018-06-12', '635364250_32512050_358858067970289_6933956800806912000_n.jpg'),
(36, 'Trezeguet', 'ccbe91b1f19bd31a1365363870c0eec2296a61c1', 'Mahmoud Trezeguet', 'T@T.com', 0, 0, 1, '2018-06-12', '1068134874_mo___salah_by_bassam21312-dbww51y.jpg'),
(37, 'Kemo Kemo', '601f1889667efaebb33b8c12572835da3f027f78', 'Kemo Elahdal', 'J@J.com', 0, 0, 0, '2018-06-13', '1342075194_maxresdefault.jpg'),
(38, 'dsfsadfafasfads', '601f1889667efaebb33b8c12572835da3f027f78', 'afafasdfasd', 'a@a.com', 0, 0, 0, '2018-06-13', '629081610_maxresdefault.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD UNIQUE KEY `CategoryName` (`CategoryName`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `item_2` (`ItemID`),
  ADD KEY `user` (`UserID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `user_1` (`UserID`),
  ADD KEY `cat_1` (`CatID`);

--
-- Indexes for table `shopingcar`
--
ALTER TABLE `shopingcar`
  ADD PRIMARY KEY (`ٍShopingCarID`),
  ADD KEY `User_3` (`UserID`),
  ADD KEY `item_3` (`ItemID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `shopingcar`
--
ALTER TABLE `shopingcar`
  MODIFY `ٍShopingCarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `item_2` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`CatID`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shopingcar`
--
ALTER TABLE `shopingcar`
  ADD CONSTRAINT `User_3` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_3` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
