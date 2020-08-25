-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2020 at 06:47 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

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
  `content` varchar(5000) NOT NULL,
  `postDate` datetime NOT NULL,
  `postID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblblogcomments`
--

INSERT INTO `tblblogcomments` (`commentID`, `content`, `postDate`, `postID`) VALUES
(63, '<strong>this is a nice post. </p></strong>', '2020-07-31 03:54:07', 92),
(64, '<p>this is a bad comment</p>', '2020-07-31 03:59:56', 92);

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
(10, 6937);

-- --------------------------------------------------------

--
-- Table structure for table `tblbloggercomments`
--

CREATE TABLE `tblbloggercomments` (
  `blogCommentID` int(11) NOT NULL,
  `userID` int(5) NOT NULL,
  `commentID` int(5) NOT NULL,
  `userPosition` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblbloggercomments`
--

INSERT INTO `tblbloggercomments` (`blogCommentID`, `userID`, `commentID`, `userPosition`) VALUES
(68, 9, 63, 2),
(69, 7, 64, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblblogpost`
--

CREATE TABLE `tblblogpost` (
  `postID` int(5) NOT NULL,
  `content` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `heading` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postDate` datetime NOT NULL,
  `userID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblblogpost`
--

INSERT INTO `tblblogpost` (`postID`, `content`, `heading`, `postDate`, `userID`) VALUES
(25, '<p>I wanted to create a safe space to express how you feel without revealing who you are. 		The ultimate bloggers dream to ventilate every thought, feeling, and behaviour, without the fear of anyone knowing your identity. 		A group journal, where others can be inspired by your narrative as you relieve all that’s pent up. 		This is a judgement and abuse free place. We only encourage supportive networking. 		If you’re interested in booking a therapeutic counselling session, check out our website <a href=\"https://theracoconsultants.com/\">here</a>. 		<br/><br/> -Antonia Mootoo</p>', 'Twilight Of The Thunder God', '2020-05-25 22:40:50', 9),
(26, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur, lacus nec dictum porttitor, ex libero feugiat mi, ac ullamcorper nibh sem ut turpis. Sed dignissim ligula ut imperdiet accumsan</p>', 'Twilight Of The Thunder God', '2020-05-25 23:18:20', 9),
(28, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur, lacus nec dictum porttitor, ex libero feugiat mi, ac ullamcorper nibh sem ut turpis. Sed dignissim ligula ut imperdiet accumsan</p>', 'No Image Test', '2020-05-25 23:58:29', 9),
(46, '<p>This is a test to ensure that the administrators can make blogposts without any issues. The posts should be available on the homepage without the admin posts needing to be confirmed. </p>', 'Admin Blog Test', '2020-06-03 14:43:14', 9),
(47, '<p>This is another test to ensure that admins can make blogposts with images attached, I\'m trying to write a bunch so I could make up the word limit on this thing. </p>', 'Admin Blog Image Test', '2020-06-03 14:57:21', 9),
(63, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla in tempus tellus, at porta risus. Sed varius urna ligula, sed bibendum lorem vehicula ac. Sed dignissim nulla quis maximus tempor. Praesent eleifend, enim vitae varius pulvinar, dui ligula finibus felis, a rutrum turpis enim id tortor31232112</p>', 'Used to the Darkness', '2020-06-10 20:29:49', 7),
(91, '<p>hello, how are you?&nbsp;&nbsp;Quisque quis gravida justo. Sed maximus eleifend felis sed pulvinar. Donec massa tellus, tempus non facilisis vitae, mattis sagittis erat. Mauris pretium urna metus, in interdum enim pretium nec.</p>\\r\\n', 'Test Title', '2020-07-30 19:33:30', 7),
(92, '<p>this is a test body, because reasons. I cant think of anything to write in this box. </p>', 'sup bro', '2020-07-31 03:50:28', 7),
(93, '<p>Duis blandit ipsum et libero porttitor viverra. Sed eget justo feugiat, semper quam quis, tristique nulla. Pellentesque ultricies orci dui, a rhoncus erat lacinia et. Fusce malesuada lacinia odio a pellentesque. Aenean quis massa lobortis, euismod massa non, sollicitudin felis. Nulla nec nibh id nunc egestas varius non sed erat. Integer metus lectus, egestas ullamcorper felis sed, vehicula ultricies libero. Fusce semper sapien et placerat dapibus.lmao</p>', 'This is an email test', '2020-07-31 22:27:57', 7),
(97, '<p>Nulla vulputate sodales felis id consequat. Sed velit ligula, aliquet ut vulputate luctus, accumsan ut erat. Duis hendrerit, quam sed consectetur dapibus, eros turpis sollicitudin elit, vitae ullamcorper ligula libero eget sem. Sed dignissim eros et euismod hendrerit. Phasellus sed massa nisl.</p>', 'Email Test', '2020-07-31 22:43:40', 7),
(98, '<p>I hate the fact that I have to sit down and write more than 100 words just to prevent people from fucking spamming the system, but honestly its not bad because in a regular use-case this is fine.&nbsp;</p>\r\n', 'New post with new editor', '2020-08-07 21:20:01', 9),
(99, '<p>Man, it is so weird that I have to sit down and write out this 100 words thing, I wonder how much words emotes take up, maybe a single character.&nbsp;👄</p>\r\n', 'Lets try this again with emotes', '2020-08-07 21:22:26', 9);

-- --------------------------------------------------------

--
-- Table structure for table `tblcommentreport`
--

CREATE TABLE `tblcommentreport` (
  `reportID` int(5) NOT NULL,
  `commentID` int(5) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `reportedUser` int(5) NOT NULL,
  `reportedBy` varchar(100) DEFAULT NULL,
  `reportedByID` int(5) NOT NULL,
  `reportDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcommentreport`
--

INSERT INTO `tblcommentreport` (`reportID`, `commentID`, `reason`, `reportedUser`, `reportedBy`, `reportedByID`, `reportDate`) VALUES
(47, 64, 'Spam', 7, 'jimmy2', 9, '2020-07-31 04:00:17'),
(48, 64, 'Spam', 7, 'jimmy2', 9, '2020-08-05 00:40:19');

-- --------------------------------------------------------

--
-- Table structure for table `tblconfirmedposts`
--

CREATE TABLE `tblconfirmedposts` (
  `cPostID` int(5) NOT NULL,
  `postID` int(5) NOT NULL,
  `userID` int(5) DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `reason` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblconfirmedposts`
--

INSERT INTO `tblconfirmedposts` (`cPostID`, `postID`, `userID`, `confirmed`, `reason`) VALUES
(7, 25, 9, 1, NULL),
(8, 26, 9, 1, NULL),
(28, 46, 9, 1, NULL),
(29, 47, 9, 1, NULL),
(74, 91, 9, 0, 'Spam'),
(75, 92, 9, 1, NULL),
(76, 93, NULL, 0, NULL),
(80, 97, NULL, 0, NULL),
(81, 98, 9, 1, NULL),
(82, 99, 9, 1, NULL);

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
(36, NULL, 25),
(37, '5181QbXpzl.jpg', 26),
(38, '872776_deus_ex.jpg', 26),
(62, NULL, 46),
(63, '583alena-aenami-million-little-pieces-1k.jpg', 47),
(64, '102alena-aenami-rooflinesgirl-1k-2.jpg', 47),
(146, NULL, 91),
(147, '803alena-aenami-witcher-1k.jpg', 92),
(148, '531alena-aenami-eternity-1080px.jpg', 92),
(149, NULL, 93),
(153, NULL, 97),
(154, NULL, 98),
(155, NULL, 99);

-- --------------------------------------------------------

--
-- Table structure for table `tblmessage`
--

CREATE TABLE `tblmessage` (
  `messageID` int(5) NOT NULL,
  `conversationID` int(100) NOT NULL,
  `originalSender` int(5) NOT NULL,
  `originalRecipient` int(5) NOT NULL,
  `sender` int(5) NOT NULL,
  `recipient` int(5) NOT NULL,
  `messageDate` datetime NOT NULL,
  `messageTitle` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `messageContent` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `userPosition` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblmessage`
--

INSERT INTO `tblmessage` (`messageID`, `conversationID`, `originalSender`, `originalRecipient`, `sender`, `recipient`, `messageDate`, `messageTitle`, `messageContent`, `userPosition`) VALUES
(32, 57, 9, 7, 9, 7, '2020-07-13 02:11:40', 'Message!', '<p>Hey how are ya :p</p>', 2),
(33, 57, 9, 7, 7, 9, '2020-07-13 02:12:11', 'Message!', '<p>I\\\'m good! How are you?</p>', 1),
(43, 91, 7, 9, 7, 9, '2020-07-24 14:39:11', 'Email Test 2', '<p>This is a test to see if the email functionality works as intended. </p>', 1),
(45, 91, 7, 9, 7, 9, '2020-07-30 19:45:07', 'Email Test 2', '<p>hey how are u</p>', 1),
(46, 91, 7, 9, 7, 9, '2020-07-31 03:56:29', 'Email Test 2', '<p>whats up</p>', 1),
(47, 682, 9, 7, 9, 7, '2020-08-07 21:57:27', 'this is a new message with the new editor', '<p>👸💋</p>\\r\\n', 2),
(48, 682, 9, 7, 9, 7, '2020-08-07 22:56:46', 'this is a new message with the new editor', '<p>👄</p>\r\n', 2),
(49, 682, 9, 7, 9, 7, '2020-08-07 22:57:54', 'this is a new message with the new editor', '<p>👃👅</p>\\r\\n', 2),
(50, 682, 9, 7, 7, 9, '2020-08-08 04:08:17', 'this is a new message with the new editor', '<p>👄</p>\\r\\n', 1),
(51, 91, 7, 9, 7, 9, '2020-08-17 19:37:36', 'Email Test 2', '<p>hey hey</p>\\r\\n', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblmessagereport`
--

CREATE TABLE `tblmessagereport` (
  `reportID` int(5) NOT NULL,
  `messageID` int(5) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `reportedUser` int(5) NOT NULL,
  `reportedBy` varchar(100) NOT NULL,
  `reportedByID` int(5) NOT NULL,
  `reportDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblmessagereport`
--

INSERT INTO `tblmessagereport` (`reportID`, `messageID`, `reason`, `reportedUser`, `reportedBy`, `reportedByID`, `reportDate`) VALUES
(19, 43, 'Spam', 7, 'jimmy2', 9, '2020-08-05 01:03:08'),
(20, 45, 'Spam', 7, 'jimmy2', 9, '2020-08-05 01:04:23');

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
(9, '<em>Test to ensure that the formatting for the post subsists.</p></em>', '2020-06-02 03:23:51', 9),
(10, '<p>this is a message</p>', '2020-06-23 21:39:22', 9),
(11, '<p>💃</p>\r\n', '2020-08-07 22:47:45', 9),
(14, '<p>❗️</p>\\r\\n', '2020-08-07 22:50:38', 9),
(15, '<p>👄👄👄</p>\\r\\n', '2020-08-07 22:53:05', 9),
(16, '<p>👨</p>\\r\\n', '2020-08-08 04:07:40', 9),
(17, '<p>👫</p>\\r\\n', '2020-08-13 20:28:39', 9);

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
(146, 46, 'admin'),
(148, 46, 'blog'),
(149, 47, 'admin'),
(151, 47, 'blog'),
(152, 47, 'image'),
(348, 91, 'blogpost'),
(349, 91, 'preview'),
(350, 92, 'blogpost'),
(351, 92, 'preview'),
(352, 93, 'blogpost'),
(353, 93, 'preview'),
(361, 97, 'first'),
(362, 97, 'post'),
(363, 98, 'first'),
(364, 98, 'post'),
(365, 99, 'first'),
(366, 99, 'post');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `userID` int(5) NOT NULL,
  `username` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `pictureID` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regDate` date NOT NULL,
  `position` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`userID`, `username`, `password`, `description`, `pictureID`, `email`, `regDate`, `position`) VALUES
(7, 'jimmy', '5f4dcc3b5aa765d61d8327deb882cf99', '<p>Test Description2</p>', '958deus-ex-human-revolution-wallpaper-adam-jensen-is-neo.jpg', 'test2@gmail.com', '2020-05-08', 1),
(8, 'JimbobTheSecond2', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, '2020-05-08', 1),
(9, 'jimmy2', '5f4dcc3b5aa765d61d8327deb882cf99', '<p>Here are some emotes&nbsp;👄👃👏</p>\r\n', '3781428436079-deus-ex-mankind-divided-concept-art-2.jpg', 'rys19@live.com', '2020-05-13', 2),
(10, 'TestUser', 'dc647eb65e6711e155375218212b3964', NULL, '823alena-aenami-005.jpg', NULL, '2020-05-20', 1);

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
  ADD PRIMARY KEY (`blogCommentID`),
  ADD UNIQUE KEY `commentID_2` (`commentID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `commentID` (`commentID`);

--
-- Indexes for table `tblblogpost`
--
ALTER TABLE `tblblogpost`
  ADD PRIMARY KEY (`postID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `tblcommentreport`
--
ALTER TABLE `tblcommentreport`
  ADD PRIMARY KEY (`reportID`),
  ADD KEY `tblreport_ibfk1` (`commentID`),
  ADD KEY `reportedUser` (`reportedUser`),
  ADD KEY `reportedBy` (`reportedBy`),
  ADD KEY `reportedByID` (`reportedByID`);

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
  ADD KEY `recipient` (`recipient`),
  ADD KEY `tblmessage_ibfk_3` (`sender`),
  ADD KEY `tblmessage_ibfk_4` (`originalSender`),
  ADD KEY `tblmessage_ibfk_5` (`originalRecipient`);

--
-- Indexes for table `tblmessagereport`
--
ALTER TABLE `tblmessagereport`
  ADD PRIMARY KEY (`reportID`),
  ADD KEY `messageID` (`messageID`),
  ADD KEY `reportedUser` (`reportedUser`),
  ADD KEY `reportedBy` (`reportedBy`),
  ADD KEY `reportedByID` (`reportedByID`);

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
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblblogcomments`
--
ALTER TABLE `tblblogcomments`
  MODIFY `commentID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tblbloggercomments`
--
ALTER TABLE `tblbloggercomments`
  MODIFY `blogCommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tblblogpost`
--
ALTER TABLE `tblblogpost`
  MODIFY `postID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `tblcommentreport`
--
ALTER TABLE `tblcommentreport`
  MODIFY `reportID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `tblconfirmedposts`
--
ALTER TABLE `tblconfirmedposts`
  MODIFY `cPostID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `tblimages`
--
ALTER TABLE `tblimages`
  MODIFY `imageID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `tblmessage`
--
ALTER TABLE `tblmessage`
  MODIFY `messageID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tblmessagereport`
--
ALTER TABLE `tblmessagereport`
  MODIFY `reportID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tblpmessage`
--
ALTER TABLE `tblpmessage`
  MODIFY `pMessageID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbltags`
--
ALTER TABLE `tbltags`
  MODIFY `tagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=372;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `userID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  ADD CONSTRAINT `tblbloggercomments_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblbloggercomments_ibfk_2` FOREIGN KEY (`commentID`) REFERENCES `tblblogcomments` (`commentID`);

--
-- Constraints for table `tblblogpost`
--
ALTER TABLE `tblblogpost`
  ADD CONSTRAINT `tblblogpost_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbluser` (`userID`);

--
-- Constraints for table `tblcommentreport`
--
ALTER TABLE `tblcommentreport`
  ADD CONSTRAINT `tblcommentreport_ibfk_3` FOREIGN KEY (`reportedUser`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblcommentreport_ibfk_4` FOREIGN KEY (`reportedBy`) REFERENCES `tbluser` (`username`),
  ADD CONSTRAINT `tblcommentreport_ibfk_5` FOREIGN KEY (`reportedByID`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblreport_ibfk1` FOREIGN KEY (`commentID`) REFERENCES `tblblogcomments` (`commentID`);

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
  ADD CONSTRAINT `tblmessage_ibfk_2` FOREIGN KEY (`recipient`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblmessage_ibfk_3` FOREIGN KEY (`sender`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblmessage_ibfk_4` FOREIGN KEY (`originalSender`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblmessage_ibfk_5` FOREIGN KEY (`originalRecipient`) REFERENCES `tbluser` (`userID`);

--
-- Constraints for table `tblmessagereport`
--
ALTER TABLE `tblmessagereport`
  ADD CONSTRAINT `tblmessagereport_ibfk_1` FOREIGN KEY (`messageID`) REFERENCES `tblmessage` (`messageID`),
  ADD CONSTRAINT `tblmessagereport_ibfk_3` FOREIGN KEY (`reportedUser`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblmessagereport_ibfk_4` FOREIGN KEY (`reportedBy`) REFERENCES `tbluser` (`username`),
  ADD CONSTRAINT `tblmessagereport_ibfk_5` FOREIGN KEY (`reportedByID`) REFERENCES `tbluser` (`userID`);

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
