-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2020 at 03:54 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sincerely`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `userID` int(5) NOT NULL,
  `adminID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`userID`, `adminID`) VALUES
(9, 12345);

-- --------------------------------------------------------

--
-- Table structure for table `tblblogcomments`
--

CREATE TABLE `tblblogcomments` (
  `commentID` int(5) NOT NULL,
  `content` varchar(400) NOT NULL,
  `postDate` datetime NOT NULL,
  `postID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblblogcomments`
--

INSERT INTO `tblblogcomments` (`commentID`, `content`, `postDate`, `postID`) VALUES
(1, 'Test comment 1', '2020-05-26 23:45:53', 29),
(2, 'Test Comment 2', '2020-05-26 23:47:35', 29);

-- --------------------------------------------------------

--
-- Table structure for table `tblblogger`
--

CREATE TABLE `tblblogger` (
  `userID` int(5) NOT NULL,
  `bloggerID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblblogger`
--

INSERT INTO `tblblogger` (`userID`, `bloggerID`) VALUES
(7, 209),
(8, 6947),
(9, 8705),
(10, 6937);

-- --------------------------------------------------------

--
-- Table structure for table `tblbloggercomments`
--

CREATE TABLE `tblbloggercomments` (
  `userID` int(5) NOT NULL,
  `commentID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblbloggercomments`
--

INSERT INTO `tblbloggercomments` (`userID`, `commentID`) VALUES
(9, 1),
(9, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tblblogpost`
--

CREATE TABLE `tblblogpost` (
  `postID` int(5) NOT NULL,
  `content` varchar(2500) NOT NULL,
  `heading` varchar(100) NOT NULL,
  `postDate` datetime NOT NULL,
  `userID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblblogpost`
--

INSERT INTO `tblblogpost` (`postID`, `content`, `heading`, `postDate`, `userID`) VALUES
(17, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sed pellentesque sem, id molestie neque. Curabitur vehicula neque quis cursus tincidunt. Mauris non neque vehicula, egestas lacus vel, porttitor neque. Vestibulum faucibus nunc in dignissim condimentum. Mauris faucibus vehicula odio. Nunc molestie consequat odio, nec viverra leo euismod et. Etiam vel odio vulputate, suscipit ex ut, bibendum diam.</p>', 'Test Title 3', '2020-05-19 00:00:00', 7),
(20, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut gravida sem. Donec elementum facilisis euismod. Duis ac dictum leo. Duis ornare enim vel arcu convallis semper. Etiam in laoreet neque. Nullam dignissim augue eget nunc porttitor faucibus. Cras sit amet neque ante. Integer eu risus ligula. </p>', 'Admin Blog Test', '2020-05-21 00:00:00', 9),
(21, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut gravida sem. Donec elementum facilisis euismod. Duis ac dictum leo. Duis ornare enim vel arcu convallis semper. Etiam in laoreet neque. Nullam dignissim augue eget nunc porttitor faucibus. Cras sit amet neque ante. Integer eu risus ligula. </p>', 'Test Title 180', '2020-05-21 00:00:00', 7),
(22, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut gravida sem. Donec elementum facilisis euismod. Duis ac dictum leo. Duis ornare enim vel arcu convallis semper. Etiam in laoreet neque. Nullam dignissim augue eget nunc porttitor faucibus. Cras sit amet neque ante. Integer eu risus ligula. </p>', 'Test Title 630', '2020-05-22 00:00:00', 7),
(25, '<p>I wanted to create a safe space to express how you feel without revealing who you are. 		The ultimate bloggers dream to ventilate every thought, feeling, and behaviour, without the fear of anyone knowing your identity. 		A group journal, where others can be inspired by your narrative as you relieve all that’s pent up. 		This is a judgement and abuse free place. We only encourage supportive networking. 		If you’re interested in booking a therapeutic counselling session, check out our website <a href=\"https://theracoconsultants.com/\">here</a>. 		<br/><br/> -Antonia Mootoo</p>', 'Twilight Of The Thunder God', '2020-05-25 22:40:50', 9),
(26, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur, lacus nec dictum porttitor, ex libero feugiat mi, ac ullamcorper nibh sem ut turpis. Sed dignissim ligula ut imperdiet accumsan</p>', 'Twilight Of The Thunder God', '2020-05-25 23:18:20', 9),
(28, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur, lacus nec dictum porttitor, ex libero feugiat mi, ac ullamcorper nibh sem ut turpis. Sed dignissim ligula ut imperdiet accumsan</p>', 'No Image Test', '2020-05-25 23:58:29', 9),
(29, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tincidunt tincidunt enim eget posuere. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras ut neque quam. Quisque cursus condimentum libero, quis dignissim leo consectetur eu.</p>', 'Null Image', '2020-05-26 00:17:53', 9),
(30, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce tristique lectus ligula, vel vulputate dui blandit sit amet. Mauris ullamcorper mi leo, vel facilisis libero tempor ut. Donec malesuada lorem sed purus facilisis, id fringilla quam iaculis. Mauris convallis elit nec varius blandit. Nulla pharetra pellentesque magna. Duis non nunc feugiat, egestas dolor non, dapibus nunc. Nullam neque velit, luctus et lobortis sed, aliquam sit amet massa. Suspendisse blandit nisi vel nulla sodales vehicula. Sed facilisis iaculis enim sed semper.</p>', 'Test Blogpost for the Admin Check', '2020-06-02 01:24:37', 7),
(31, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce tristique lectus ligula, vel vulputate dui blandit sit amet. Mauris ullamcorper mi leo, vel facilisis libero tempor ut. Donec malesuada lorem sed purus facilisis, id fringilla quam iaculis. Mauris convallis elit nec varius blandit. Nulla pharetra pellentesque magna. Duis non nunc feugiat, egestas dolor non, dapibus nunc. Nullam neque velit, luctus et lobortis sed, aliquam sit amet massa. Suspendisse blandit nisi vel nulla sodales vehicula. Sed facilisis iaculis enim sed semper.</p>', 'Tag Test Heading', '2020-06-02 01:36:05', 7),
(34, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce tristique lectus ligula, vel vulputate dui blandit sit amet. Mauris ullamcorper mi leo, vel facilisis libero tempor ut. Donec malesuada lorem sed purus facilisis, id fringilla quam iaculis. Mauris convallis elit nec varius blandit. Nulla pharetra pellentesque magna. Duis non nunc feugiat, egestas1231</p>', 'Let Me Live / Let Me Die', '2020-06-02 02:03:03', 7),
(35, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce tristique lectus ligula, vel vulputate dui blandit sit amet. Mauris ullamcorper mi leo, vel facilisis libero tempor ut.09qw1</p>', 'Test Blog for Reasons', '2020-06-02 02:53:30', 7),
(37, '<strong>This is a test to ensure that the formatting does not break anything on the home page.</strong><em>It really shouldn\'t break anything, but I\'m a little concerned.</p></em>', 'Edit Test', '2020-06-02 03:17:05', 9);

-- --------------------------------------------------------

--
-- Table structure for table `tblconfirmedposts`
--

CREATE TABLE `tblconfirmedposts` (
  `cPostID` int(5) NOT NULL,
  `postID` int(5) NOT NULL,
  `userID` int(5) DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblconfirmedposts`
--

INSERT INTO `tblconfirmedposts` (`cPostID`, `postID`, `userID`, `confirmed`) VALUES
(1, 17, 9, 1),
(2, 20, 9, 1),
(3, 21, 9, 1),
(4, 22, NULL, 0),
(7, 25, 9, 1),
(8, 26, 9, 1),
(11, 29, 9, 1),
(12, 30, 9, 1),
(13, 31, NULL, 0),
(14, 31, NULL, 0),
(16, 34, NULL, 0),
(17, 35, NULL, 0),
(19, 37, 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblimages`
--

CREATE TABLE `tblimages` (
  `imageID` int(5) NOT NULL,
  `imageName` varchar(200) DEFAULT NULL,
  `postID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblimages`
--

INSERT INTO `tblimages` (`imageID`, `imageName`, `postID`) VALUES
(25, '559alena-aenami-million-little-pieces-1k.jpg', 17),
(26, '284alena-aenami-rooflinesgirl-1k-2.jpg', 17),
(30, '638zennyfresh.jpg', 20),
(31, '792zennyfresh.jpg', 21),
(32, '78alena-aenami-001-1.jpg', 22),
(33, '80alena-aenami-005.jpg', 22),
(36, NULL, 25),
(37, '5181QbXpzl.jpg', 26),
(38, '872776_deus_ex.jpg', 26),
(41, NULL, 29),
(42, '890alena-aenami-million-little-pieces-1k.jpg', 30),
(43, '540alena-aenami-rooflinesgirl-1k-2.jpg', 30),
(44, NULL, 31),
(47, NULL, 34),
(48, '944alena-aenami-001-1.jpg', 35),
(49, '84alena-aenami-005.jpg', 35),
(51, NULL, 37);

-- --------------------------------------------------------

--
-- Table structure for table `tblmessage`
--

CREATE TABLE `tblmessage` (
  `messageID` int(5) NOT NULL,
  `sender` int(5) NOT NULL,
  `recipient` int(5) NOT NULL,
  `messageDate` date NOT NULL,
  `messageTitle` varchar(200) NOT NULL,
  `messageContent` varchar(2500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblpmessage`
--

CREATE TABLE `tblpmessage` (
  `pMessageID` int(5) NOT NULL,
  `pMessage` varchar(2500) NOT NULL,
  `pMessageDate` datetime NOT NULL,
  `userID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblpmessage`
--

INSERT INTO `tblpmessage` (`pMessageID`, `pMessage`, `pMessageDate`, `userID`) VALUES
(2, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur, lacus nec dictum porttitor, ex libero feugiat mi, ac ullamcorper nibh sem ut turpis. Sed dignissim ligula ut imperdiet accumsan. Nulla ultricies tristique elementum. Donec semper sapien elit, eget euismod tellus facilisis at. Sed at dignissim sem, id suscipit libero. Nullam id ultricies nulla. Nunc blandit a metus eu consequat. Aenean luctus velit eu nunc placerat, sit amet consec</p>', '2020-05-25 00:00:00', 9),
(3, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur, lacus nec dictum porttitor, ex libero feugiat mi, ac ullamcorper nibh sem ut turpis. Sed dignissim ligula ut imperdiet accumsan', '2020-05-25 22:14:29', 9),
(4, ' This is a test for the emoji stuffs :0', '2020-05-29 21:38:53', 9),
(8, 'Hello! I hope you all have a wonderful day today, regardless of what might be going on!', '2020-06-02 03:10:41', 9),
(9, '<em>Test to ensure that the formatting for the post subsists.</p></em>', '2020-06-02 03:23:51', 9);

-- --------------------------------------------------------

--
-- Table structure for table `tbltags`
--

CREATE TABLE `tbltags` (
  `tagID` int(11) NOT NULL,
  `postID` int(5) NOT NULL,
  `tagName` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbltags`
--

INSERT INTO `tbltags` (`tagID`, `postID`, `tagName`) VALUES
(56, 20, 'hello'),
(57, 20, 'how'),
(58, 20, 'are'),
(59, 20, 'you'),
(60, 20, 'friend'),
(61, 21, 'hello'),
(62, 21, 'how'),
(63, 21, 'are'),
(64, 21, 'you'),
(65, 21, 'friend'),
(66, 22, 'hello'),
(67, 22, 'how'),
(68, 22, 'are'),
(69, 22, 'you'),
(70, 22, 'friend'),
(81, 25, 'hello'),
(82, 25, 'how'),
(83, 25, 'are'),
(84, 25, 'you'),
(85, 25, 'friend'),
(86, 26, 'hello'),
(87, 26, 'how'),
(88, 26, 'are'),
(89, 26, 'you'),
(90, 26, 'friend'),
(101, 29, 'hello'),
(102, 29, 'how'),
(103, 29, 'are'),
(104, 29, 'you'),
(105, 29, 'friend'),
(106, 30, 'test'),
(107, 30, 'tags'),
(108, 31, 'Test'),
(109, 31, 'tag'),
(110, 31, 'for'),
(111, 31, 'system'),
(112, 31, 'testing'),
(115, 34, 'Test'),
(116, 34, 'tag'),
(117, 34, 'for'),
(118, 34, 'system'),
(119, 34, 'testing'),
(120, 35, 'custom'),
(121, 35, 'tags'),
(123, 37, 'Edit');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `userID` int(5) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(50) NOT NULL,
  `description` varchar(2500) DEFAULT NULL,
  `pictureID` varchar(1000) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `regDate` date NOT NULL,
  `position` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`userID`, `username`, `password`, `description`, `pictureID`, `email`, `regDate`, `position`) VALUES
(7, 'jimmy', '5f4dcc3b5aa765d61d8327deb882cf99', '<p>Test Description</p>', '121skellyboi.jpg', 'test@gmail.com', '2020-05-08', 1),
(8, 'JimbobTheSecond2', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, '2020-05-08', 1),
(9, 'jimmy2', '5f4dcc3b5aa765d61d8327deb882cf99', '<u><em><strong>Henlo, how are you?</p></strong></em></u>', '896alena-aenami-rooflinesgirl-1k-2.jpg', NULL, '2020-05-13', 2),
(10, 'TestUser', 'dc647eb65e6711e155375218212b3964', NULL, NULL, NULL, '2020-05-20', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `tblblogcomments`
--
ALTER TABLE `tblblogcomments`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `postID` (`postID`);

--
-- Indexes for table `tblblogger`
--
ALTER TABLE `tblblogger`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `tblbloggercomments`
--
ALTER TABLE `tblbloggercomments`
  ADD KEY `userID` (`userID`),
  ADD KEY `commentID` (`commentID`);

--
-- Indexes for table `tblblogpost`
--
ALTER TABLE `tblblogpost`
  ADD PRIMARY KEY (`postID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `tblconfirmedposts`
--
ALTER TABLE `tblconfirmedposts`
  ADD PRIMARY KEY (`cPostID`),
  ADD KEY `postID` (`postID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `tblimages`
--
ALTER TABLE `tblimages`
  ADD PRIMARY KEY (`imageID`),
  ADD KEY `postID` (`postID`);

--
-- Indexes for table `tblmessage`
--
ALTER TABLE `tblmessage`
  ADD PRIMARY KEY (`messageID`),
  ADD KEY `sender` (`sender`),
  ADD KEY `recipient` (`recipient`);

--
-- Indexes for table `tblpmessage`
--
ALTER TABLE `tblpmessage`
  ADD PRIMARY KEY (`pMessageID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `tbltags`
--
ALTER TABLE `tbltags`
  ADD PRIMARY KEY (`tagID`),
  ADD KEY `postID` (`postID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblblogcomments`
--
ALTER TABLE `tblblogcomments`
  MODIFY `commentID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblblogpost`
--
ALTER TABLE `tblblogpost`
  MODIFY `postID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tblconfirmedposts`
--
ALTER TABLE `tblconfirmedposts`
  MODIFY `cPostID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tblimages`
--
ALTER TABLE `tblimages`
  MODIFY `imageID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tblmessage`
--
ALTER TABLE `tblmessage`
  MODIFY `messageID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblpmessage`
--
ALTER TABLE `tblpmessage`
  MODIFY `pMessageID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbltags`
--
ALTER TABLE `tbltags`
  MODIFY `tagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `userID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD CONSTRAINT `tbladmin_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbluser` (`userID`);

--
-- Constraints for table `tblblogcomments`
--
ALTER TABLE `tblblogcomments`
  ADD CONSTRAINT `tblblogcomments_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `tblblogpost` (`postID`);

--
-- Constraints for table `tblblogger`
--
ALTER TABLE `tblblogger`
  ADD CONSTRAINT `tblblogger_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbluser` (`userID`);

--
-- Constraints for table `tblbloggercomments`
--
ALTER TABLE `tblbloggercomments`
  ADD CONSTRAINT `tblbloggercomments_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tblblogger` (`userID`),
  ADD CONSTRAINT `tblbloggercomments_ibfk_2` FOREIGN KEY (`commentID`) REFERENCES `tblblogcomments` (`commentID`);

--
-- Constraints for table `tblblogpost`
--
ALTER TABLE `tblblogpost`
  ADD CONSTRAINT `tblblogpost_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tblblogger` (`userID`);

--
-- Constraints for table `tblconfirmedposts`
--
ALTER TABLE `tblconfirmedposts`
  ADD CONSTRAINT `tblconfirmedposts_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `tblblogpost` (`postID`),
  ADD CONSTRAINT `tblconfirmedposts_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `tbladmin` (`userID`);

--
-- Constraints for table `tblimages`
--
ALTER TABLE `tblimages`
  ADD CONSTRAINT `tblimages_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `tblblogpost` (`postID`);

--
-- Constraints for table `tblmessage`
--
ALTER TABLE `tblmessage`
  ADD CONSTRAINT `tblmessage_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblmessage_ibfk_2` FOREIGN KEY (`recipient`) REFERENCES `tbluser` (`userID`);

--
-- Constraints for table `tblpmessage`
--
ALTER TABLE `tblpmessage`
  ADD CONSTRAINT `tblpmessage_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbladmin` (`userID`);

--
-- Constraints for table `tbltags`
--
ALTER TABLE `tbltags`
  ADD CONSTRAINT `tbltags_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `tblblogpost` (`postID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
