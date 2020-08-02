-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2020 at 04:31 PM
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
(1, 'Test comment 1', '2020-05-26 23:45:53', 29),
(2, 'Test Comment 2', '2020-05-26 23:47:35', 29),
(3, 'testComment', '2020-06-02 23:14:29', 39),
(5, '<strong>Hello! How</strong><em>are</em><u>you?</p></u>', '2020-06-02 23:49:05', 30),
(6, '<strong>HELLO!</strong><p> Its <em>very good</em> to <u>hear from you!</p></u>', '2020-06-02 23:52:31', 30),
(7, '<p>This is a test to ensure that everything is working fine.</p>', '2020-06-02 23:58:00', 30),
(8, '<strong>this </strong><em>is </em><u>a </u><s>test</p></s>', '2020-06-02 23:58:27', 30),
(10, '<p>Firefox Comment</p>', '2020-06-03 15:42:04', 47),
(11, '<p>Test comment</p>', '2020-06-23 21:31:52', 63),
(12, '<p>test comment 2</p>', '2020-06-23 21:36:59', 63),
(13, '<p>thank you for commenting!</p>', '2020-06-23 21:53:06', 63),
(14, '<p>hello!</p>', '2020-06-23 21:54:02', 63),
(15, '<p>test comment</p>', '2020-07-07 23:26:43', 63),
(16, '<p>hello, how are you</p>', '2020-07-07 23:27:08', 63),
(17, '<p>another test</p>', '2020-07-07 23:32:18', 63),
(18, '<p>test to maintain user position</p>', '2020-07-11 15:28:22', 63),
(20, '<p>here is another test</p>', '2020-07-11 15:30:08', 63),
(21, '<p>heyo</p>', '2020-07-11 16:03:18', 63);

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
(10, 6937),
(11, 3689);

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
(1, 9, 1, 2),
(2, 9, 2, 2),
(3, 9, 3, 2),
(5, 9, 5, 2),
(6, 9, 6, 2),
(7, 9, 7, 2),
(8, 9, 8, 2),
(10, 7, 10, 1),
(11, 9, 11, 2),
(12, 9, 12, 2),
(13, 7, 13, 1),
(14, 7, 14, 1),
(16, 7, 16, 1),
(17, 7, 17, 1),
(18, 7, 18, 1),
(19, 7, 20, 1),
(20, 11, 21, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblblogpost`
--

CREATE TABLE `tblblogpost` (
  `postID` int(5) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `heading` varchar(100) NOT NULL,
  `postDate` datetime NOT NULL,
  `userID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblblogpost`
--

INSERT INTO `tblblogpost` (`postID`, `content`, `heading`, `postDate`, `userID`) VALUES
(17, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sed pellentesque sem, id molestie neque. Curabitur vehicula neque quis cursus tincidunt. Mauris non neque vehicula, egestas lacus vel, porttitor neque. Vestibulum faucibus nunc in dignissim condimentum. Mauris faucibus vehicula odio. Nunc molestie consequat odio, nec viverra leo euismod et. Etiam vel odio vulputate, suscipit ex ut, bibendum diam 03423094-</p>', 'Title 360', '2020-05-19 00:00:00', 7),
(20, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut gravida sem. Donec elementum facilisis euismod. Duis ac dictum leo. Duis ornare enim vel arcu convallis semper. Etiam in laoreet neque. Nullam dignissim augue eget nunc porttitor faucibus. Cras sit amet neque ante. Integer eu risus ligula. </p>', 'Admin Blog Test', '2020-05-21 00:00:00', 9),
(21, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut gravida sem. Donec elementum facilisis euismod. Duis ac dictum leo. Duis ornare enim vel arcu convallis semper. Etiam in laoreet neque. Nullam dignissim augue eget nunc porttitor faucibus. Cras sit amet neque ante. Integer eu risus ligula. 123</p>', 'Test Title 128', '2020-05-21 00:00:00', 7),
(22, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut gravida sem. Donec elementum facilisis euismod. Duis ac dictum leo. Duis ornare enim vel arcu convallis semper. Etiam in laoreet neque. Nullam dignissim augue eget nunc porttitor faucibus. Cras sit amet neque ante. Integer eu risus ligula. </p>', 'Test Title 630', '2020-05-22 00:00:00', 7),
(25, '<p>I wanted to create a safe space to express how you feel without revealing who you are. 		The ultimate bloggers dream to ventilate every thought, feeling, and behaviour, without the fear of anyone knowing your identity. 		A group journal, where others can be inspired by your narrative as you relieve all that’s pent up. 		This is a judgement and abuse free place. We only encourage supportive networking. 		If you’re interested in booking a therapeutic counselling session, check out our website <a href=\"https://theracoconsultants.com/\">here</a>. 		<br/><br/> -Antonia Mootoo</p>', 'Twilight Of The Thunder God', '2020-05-25 22:40:50', 9),
(26, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur, lacus nec dictum porttitor, ex libero feugiat mi, ac ullamcorper nibh sem ut turpis. Sed dignissim ligula ut imperdiet accumsan</p>', 'Twilight Of The Thunder God', '2020-05-25 23:18:20', 9),
(28, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur, lacus nec dictum porttitor, ex libero feugiat mi, ac ullamcorper nibh sem ut turpis. Sed dignissim ligula ut imperdiet accumsan</p>', 'No Image Test', '2020-05-25 23:58:29', 9),
(29, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tincidunt tincidunt enim eget posuere. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras ut neque quam. Quisque cursus condimentum libero, quis dignissim leo consectetur eu.</p>', 'Null Image', '2020-05-26 00:17:53', 9),
(30, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer cursus purus nulla, et egestas est luctus vel. Vestibulum tempor ipsum vel ipsum malesuada, eu viverra velit dictum. Vivamus luctus est nec tellus condimentum, vel tristique dui egestas. Nam ut suscipit dolor, eu malesuada sem. Integer sit amet ultricies quam. In rhoncus quam tortor, et volutpat arcu elementum ac. Maecenas eget justo rhoncus, dapibus dui id, mattis arcu.</p>', 'Title 130', '2020-06-02 01:24:37', 7),
(31, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce tristique lectus ligula, vel vulputate dui blandit sit amet. Mauris ullamcorper mi leo, vel facilisis libero tempor ut. Donec malesuada lorem sed purus facilisis, id fringilla quam iaculis. Mauris convallis elit nec varius blandit. Nulla pharetra pellentesque magna. Duis non nunc feugiat, egestas dolor non, dapibus nunc. Nullam neque velit, luctus et lobortis sed, aliquam sit amet massa. Suspendisse blandit nisi vel nulla sodales vehicula. Sed facilisis iaculis enim sed semper.</p>', 'Tag Test Heading', '2020-06-02 01:36:05', 7),
(34, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce tristique lectus ligula, vel vulputate dui blandit sit amet. Mauris ullamcorper mi leo, vel facilisis libero tempor ut. Donec malesuada lorem sed purus facilisis, id fringilla quam iaculis. Mauris convallis elit nec varius blandit. Nulla pharetra pellentesque magna. Duis non nunc feugiat, egestas1231</p>', 'Let Me Live / Let Me Die', '2020-06-02 02:03:03', 7),
(35, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce tristique lectus ligula, vel vulputate dui blandit sit amet. Mauris ullamcorper mi leo, vel facilisis libero tempor ut.09qw1</p>', 'Test Blog for Reasons', '2020-06-02 02:53:30', 7),
(37, '<strong>This is a test to ensure that the formatting does not break anything on the home page.</strong><em>It really shouldn\'t break anything, but I\'m a little concerned.</p></em>', 'Edit Test', '2020-06-02 03:17:05', 9),
(38, '<p>This is a blogpost made to test that the SQL query on the homepage only displays posts that have been accepted by the admins of the site, therefore this post should not be seen. </p>', 'Unaccepted Post', '2020-06-02 20:46:53', 7),
(39, '<p>This is a blogpost made to test that the SQL query on the homepage only displays posts that have been accepted by the admins of the site, this post should be seen. </p>', 'Accepted Post', '2020-06-02 20:54:09', 7),
(40, '<p>This is a blog post used to test the image upload of the blog creation function. This should work.</p>', 'Image Test', '2020-06-02 21:13:17', 7),
(41, '<p>This is a blogpost used to test the image upload of the blog creation function. Only a single image will be uploaded. </p>', 'Single Image Test', '2020-06-02 21:18:02', 7),
(43, '<p>This is a blogpost made with no images, this is done to test to ensure that blogposts with no images can be made without issues. </p>', 'No Image Test', '2020-06-02 21:31:05', 7),
(45, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas elementum neque eget sapien maximus ultrices. Etiam pharetra massa ut tristique faucibus. Donec nunc arcu, euismod dictum lectus at, cursus laoreet ipsum. Nulla facilisi. Curabitur imperdiet, lorem eget mollis elementum, quam nisl congue ligula, eget porta enim dui vel massa13131231</p>', 'Final Test', '2020-06-02 22:26:28', 7),
(46, '<p>This is a test to ensure that the administrators can make blogposts without any issues. The posts should be available on the homepage without the admin posts needing to be confirmed. </p>', 'Admin Blog Test', '2020-06-03 14:43:14', 9),
(47, '<p>This is another test to ensure that admins can make blogposts with images attached, I\'m trying to write a bunch so I could make up the word limit on this thing. </p>', 'Admin Blog Image Test', '2020-06-03 14:57:21', 9),
(48, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pellentesque scelerisque justo, a aliquet risus fermentum at. Suspendisse eu lectus eros. Duis fermentum neque et nibh ornare vehicula. Fusce et scelerisque orci. Cras eros tortor, tincidunt a enim et, accumsan volutpat nisi. Sed dictum, tortor sit amet imperdiet volutpat, odio nulla blandit magna, at dictum massa justo at leo. Sed nec turpis odio.</p>', 'Firefox Blogpost', '2020-06-03 15:42:43', 7),
(49, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultrices velit vel leo tempor tincidunt. Nulla facilisi. Suspendisse semper libero vitae lobortis rutrum. Mauris eleifend fermentum dictum. Praesent consequat augue vel feugiat elementum. Suspendisse malesuada risus odio, id lacinia ligula volutpat eu. </p>', 'Test Title', '2020-06-09 21:20:43', 7),
(59, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultrices velit vel leo tempor tincidunt. Nulla facilisi. Suspendisse semper libero vitae lobortis rutrum. Mauris eleifend fermentum dictum. Praesent consequat augue vel feugiat elementum. Suspendisse malesuada risus odio, id lacinia ligula volutpat eu. 12312</p>', 'Preview Test 1', '2020-06-09 21:40:15', 7),
(60, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultrices velit vel leo tempor tincidunt. Nulla facilisi. Suspendisse semper libero vitae lobortis rutrum. Mauris eleifend fermentum dictum. Praesent consequat augue vel feugiat elementum. Suspendisse malesuada risus odio, id lacinia ligula volutpat eu. 546</p>', 'preview post 1', '2020-06-09 21:44:56', 7),
(61, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultrices velit vel leo tempor tincidunt. Nulla facilisi. Suspendisse semper libero vitae lobortis rutrum. Mauris eleifend fermentum dictum. Praesent consequat augue vel feugiat elementum. Suspendisse malesuada risus odio, id lacinia ligula volutpat eu. 5655</p>', 'prewview', '2020-06-09 21:48:36', 7),
(62, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla in tempus tellus, at porta risus. Sed varius urna ligula, sed bibendum lorem vehicula ac. Sed dignissim nulla quis maximus tempor. Praesent eleifend, enim vitae varius pulvinar, dui ligula finibus felis, a rutrum turpis enim id tortor12321321</p>', 'Preview Test 6', '2020-06-10 20:18:39', 7),
(63, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla in tempus tellus, at porta risus. Sed varius urna ligula, sed bibendum lorem vehicula ac. Sed dignissim nulla quis maximus tempor. Praesent eleifend, enim vitae varius pulvinar, dui ligula finibus felis, a rutrum turpis enim id tortor31232112</p>', 'Used to the Darkness', '2020-06-10 20:29:49', 7),
(64, '<strong>Lorem ipsum dolor sit ame</strong><p>t, consectetur adipiscing elit. Nulla viverra auctor nunc non tempus. Integer eleifend non risus id euismod. Sed arcu libero, iaculi<u>s sit amet eleifend in, congue a mauris. Vestibulum imperdiet facilisis nulla sit amet sagittis. Aliquam egestas vel leo nec consequat.qweqweqwewq</p></u>', 'Test Title', '2020-06-11 01:14:50', 7),
(65, '<p>Pellentesque nec turpis libero. Pellentesque efficitur, ipsum non egestas porttitor, urna enim blandit dui, non interdum augue ligula cursus massa. Vestibulum id leo metus.12345</p>', 'No image blogpost  preview', '2020-06-23 22:47:14', 7),
(66, '<p>Pellentesque nec turpis libero. Pellentesque efficitur, ipsum non egestas porttitor, urna enim blandit dui, non interdum augue ligula cursus massa. Vestibulum id leo metus.7890</p>', 'Blogpost Preview with Image', '2020-06-23 22:53:07', 7);

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
  `reportDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcommentreport`
--

INSERT INTO `tblcommentreport` (`reportID`, `commentID`, `reason`, `reportedUser`, `reportedBy`, `reportDate`) VALUES
(3, 11, 'Harassment', 9, 'jimmy', '2020-07-09 21:59:24'),
(13, 12, 'Spam', 9, 'jimmy', '2020-07-11 04:45:35'),
(14, 13, 'Spam', 7, 'bill', '2020-07-11 15:30:49');

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
(1, 17, 9, 1, NULL),
(2, 20, 9, 1, NULL),
(3, 21, 9, 2, NULL),
(4, 22, 9, 2, 'Spam'),
(7, 25, 9, 1, NULL),
(8, 26, 9, 1, NULL),
(11, 29, 9, 1, NULL),
(12, 30, 9, 0, NULL),
(13, 31, 9, 2, 'Violence'),
(14, 31, 9, 2, 'Violence'),
(16, 34, NULL, 0, NULL),
(17, 35, NULL, 0, NULL),
(19, 37, 9, 1, NULL),
(20, 38, 9, 2, 'Violence'),
(21, 39, 9, 1, NULL),
(22, 40, NULL, 0, NULL),
(23, 41, NULL, 0, NULL),
(25, 43, NULL, 0, NULL),
(27, 45, NULL, 0, NULL),
(28, 46, 9, 1, NULL),
(29, 47, 9, 1, NULL),
(30, 48, NULL, 0, NULL),
(31, 49, NULL, 0, NULL),
(41, 59, NULL, 0, NULL),
(42, 60, NULL, 0, NULL),
(43, 61, NULL, 0, NULL),
(44, 62, 9, 2, 'Spam'),
(45, 63, 9, 1, NULL),
(46, 64, 9, 2, 'Violence'),
(47, 65, NULL, 0, NULL),
(48, 66, NULL, 0, NULL);

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
(30, '638zennyfresh.jpg', 20),
(31, '22415937040_560813030788324_6633396955100558967_o.jpg', 21),
(32, '78alena-aenami-001-1.jpg', 22),
(33, '80alena-aenami-005.jpg', 22),
(36, NULL, 25),
(37, '5181QbXpzl.jpg', 26),
(38, '872776_deus_ex.jpg', 26),
(41, NULL, 29),
(44, NULL, 31),
(47, NULL, 34),
(48, '944alena-aenami-001-1.jpg', 35),
(49, '84alena-aenami-005.jpg', 35),
(51, NULL, 37),
(52, NULL, 38),
(53, NULL, 39),
(54, '78936791532_1613964488731288_3557870866995871744_n.jpg', 40),
(55, '293alena-aenami-million-little-pieces-1k.jpg', 40),
(56, '363babyboy.jpg', 41),
(60, '880fuscia.png', 45),
(61, '569lightblue.jpg', 45),
(62, NULL, 46),
(63, '583alena-aenami-million-little-pieces-1k.jpg', 47),
(64, '102alena-aenami-rooflinesgirl-1k-2.jpg', 47),
(65, '5602776_deus_ex.jpg', 48),
(66, '314486_deus_ex.jpg', 48),
(67, '76815937040_560813030788324_6633396955100558967_o.jpg', 49),
(68, '2311428436079-deus-ex-mankind-divided-concept-art-2.jpg', 49),
(81, NULL, 59),
(82, NULL, 60),
(83, NULL, 61),
(84, NULL, 62),
(85, NULL, 63),
(86, NULL, 64),
(107, NULL, 43),
(108, NULL, 65),
(109, '696qaca0mps8v351.jpg', 66),
(111, '583alena-aenami-lunar-cover.jpg', 30);

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
  `messageTitle` varchar(200) NOT NULL,
  `messageContent` varchar(2500) NOT NULL,
  `userPosition` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblmessage`
--

INSERT INTO `tblmessage` (`messageID`, `conversationID`, `originalSender`, `originalRecipient`, `sender`, `recipient`, `messageDate`, `messageTitle`, `messageContent`, `userPosition`) VALUES
(1, 0, 9, 7, 9, 7, '2020-07-01 00:00:00', 'Test Title', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc commodo auctor risus vulputate convallis. Phasellus cursus porttitor sapien a finibus. Vivamus interdum vulputate ipsum, sed ullamcorper justo scelerisque non. Sed lobortis justo a pharetra ornare. Proin faucibus, augue sit amet facilisis scelerisque</p>', 2),
(2, 477, 7, 9, 7, 9, '2020-07-01 00:00:00', 'Test Title', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam non quam interdum, aliquet nibh ut, consectetur lorem. In venenatis tempus tortor, nec volutpat nisl maximus a. </p>', 1),
(4, 0, 9, 7, 7, 9, '2020-07-01 22:42:25', 'Test Title', '<p>This is a proper reply from the app itself.</p>', 1),
(5, 0, 9, 7, 7, 9, '2020-07-01 22:42:52', 'Test Title', '<p>This is to check if the message screen scrolls properly.</p>', 1),
(6, 0, 9, 7, 7, 9, '2020-07-01 22:49:27', 'Test Title', '<p>greetings!!!</p>', 1),
(7, 477, 7, 9, 7, 9, '2020-07-01 23:14:06', 'Test Title', '<p>hello</p>', 1),
(8, 296, 7, 11, 7, 11, '2020-07-01 23:58:20', 'Test For Bill', '<p>Quisque dignissim non neque fermentum laoreet. Aliquam egestas nulla quam, ac vulputate libero bibendum in. Mauris pulvinar tincidunt dolor, non posuere ligula. Maecenas non lacus efficitur, efficitur ex non, porttitor est. Donec condimentum nec turpis sed ultrices. Integer tincidunt metus nisi, </p>', 1),
(9, 477, 7, 9, 7, 9, '2020-07-01 23:59:46', 'Test Title', '<p>Does this work?</p>', 1),
(10, 0, 9, 7, 7, 9, '2020-07-02 00:05:55', 'Test Title', '<p>This is a test</p>', 1),
(11, 0, 9, 7, 9, 7, '2020-07-02 00:06:35', 'Test Title', '<p>tthis is a test on jimmy2\\\'s account</p>', 2),
(12, 477, 7, 9, 9, 7, '2020-07-02 00:19:02', 'Test Title', '<p>I have received your message!</p>', 2),
(13, 0, 9, 7, 9, 7, '2020-07-02 00:19:30', 'Test Title', '<p>this is another test sent off jimmy2\\\'s account</p>', 2),
(14, 122, 9, 11, 9, 11, '2020-07-02 00:21:07', 'This is another test for Bill', '<p>Hello Bill! How are you? How is your wife and children doing? I hope you\\\'re well and that you\\\'re doing well when it comes to handling those issues we talked about prior. Don\\\'t worry, you\\\'ll be okay!</p>', 2),
(20, 172, 7, 9, 7, 9, '2020-07-06 00:08:21', 'Email Test', '<p>Pellentesque vitae tellus elementum, ullamcorper dui at, placerat ex. Quisque sagittis nibh tristique libero bibendum, vel elementum felis consectetur. Aenean tincidunt sollicitudin ex, id convallis orci posuere quis. Integer porttitor erat ut risus suscipit, in rutrum mi dignissim. Donec dignissim,</p>', 1),
(21, 572, 7, 9, 7, 9, '2020-07-06 00:51:40', 'Email Test', '<strong>Pellentesque vitae tellus elementum, ullamcorper dui at, placerat ex. Quisque sagittis nibh tristique libero bibendum, vel elementum felis consectetur. Aenean tincidunt sollicitudin ex, id convallis orci posuere quis. Integer porttitor erat ut risus suscipit, in rutrum mi dignissim. Donec dignissim,</p></strong>', 1),
(22, 572, 7, 9, 7, 9, '2020-07-06 21:04:37', 'Email Test', '<p>Lets hope you get this email too, buddy. </p>', 1),
(23, 572, 7, 9, 9, 7, '2020-07-06 21:05:44', 'Email Test', '<p>I got the email, don\\\'t worry!</p>', 2),
(24, 572, 7, 9, 7, 9, '2020-07-06 21:14:50', 'Email Test', '<p>just checking to ensure that the title works as intended.</p>', 1),
(25, 296, 7, 11, 11, 7, '2020-07-09 13:52:53', 'Test For Bill', '<p>this is a message</p>', 1),
(26, 296, 7, 11, 7, 11, '2020-07-09 14:21:50', 'Test For Bill', '<p>hello</p>', 2),
(27, 296, 7, 11, 11, 7, '2020-07-09 14:22:28', 'Test For Bill', '<p>what is happening</p>', 1),
(29, 296, 7, 11, 11, 7, '2020-07-09 14:25:36', 'Test For Bill', '<p>hi</p>', 1),
(30, 183, 11, 7, 11, 7, '2020-07-11 16:06:39', 'user position test', '<p>hey bro, how are you?</p>', 1),
(31, 122, 9, 11, 11, 9, '2020-07-11 16:19:45', 'This is another test for Bill', '<p>test to make sure this still works</p>', 1);

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
  `reportDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblmessagereport`
--

INSERT INTO `tblmessagereport` (`reportID`, `messageID`, `reason`, `reportedUser`, `reportedBy`, `reportDate`) VALUES
(4, 26, 'Spam', 7, 'bill', '2020-07-09 22:03:44'),
(10, 4, 'Harassment', 11, 'jimmy2', '2020-07-11 05:01:26');

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
(10, '<p>this is a message</p>', '2020-06-23 21:39:22', 9);

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
(116, 34, 'tag'),
(117, 34, 'for'),
(118, 34, 'system'),
(119, 34, 'testing'),
(120, 35, 'custom'),
(123, 37, 'Edit'),
(126, 39, 'Seen'),
(128, 40, 'image'),
(130, 40, 'for'),
(131, 40, 'blogpost'),
(132, 41, 'single'),
(133, 41, 'image'),
(135, 41, 'for'),
(136, 41, 'blogpost'),
(140, 43, 'no'),
(141, 43, 'image'),
(142, 43, 'blog'),
(144, 45, 'final'),
(146, 46, 'admin'),
(148, 46, 'blog'),
(149, 47, 'admin'),
(151, 47, 'blog'),
(152, 47, 'image'),
(153, 48, 'firefox'),
(184, 64, 'formatting'),
(188, 61, 'new'),
(189, 61, 'tags'),
(194, 31, 'hello'),
(195, 31, 'no'),
(196, 31, 'dude'),
(197, 21, 'new'),
(198, 21, 'tags'),
(199, 65, 'blogpost'),
(200, 65, 'preview'),
(201, 66, 'blogpost'),
(202, 66, 'preview'),
(203, 66, 'image'),
(230, 30, 'one'),
(231, 30, 'two');

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
(7, 'jimmy', '5f4dcc3b5aa765d61d8327deb882cf99', '<p>Test Description2</p>', '958deus-ex-human-revolution-wallpaper-adam-jensen-is-neo.jpg', 'test2@gmail.com', '2020-05-08', 1),
(8, 'JimbobTheSecond2', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, '2020-05-08', 1),
(9, 'jimmy2', '5f4dcc3b5aa765d61d8327deb882cf99', '<p>Henlo, how are you?</p>', '3781428436079-deus-ex-mankind-divided-concept-art-2.jpg', 'rys19@live.com', '2020-05-13', 2),
(10, 'TestUser', 'dc647eb65e6711e155375218212b3964', NULL, '823alena-aenami-005.jpg', NULL, '2020-05-20', 1),
(11, 'bill', '5f4dcc3b5aa765d61d8327deb882cf99', '<strong>Hello!</strong><em>My name</em><u>is bill!</u><s>What is yours?</p></s>', '689sky_lantern.jpg', 'bill2@gmail.com', '2020-06-03', 1);

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
  ADD KEY `reportedBy` (`reportedBy`);

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
  ADD KEY `reportedBy` (`reportedBy`);

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
  MODIFY `commentID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tblbloggercomments`
--
ALTER TABLE `tblbloggercomments`
  MODIFY `blogCommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tblblogpost`
--
ALTER TABLE `tblblogpost`
  MODIFY `postID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `tblcommentreport`
--
ALTER TABLE `tblcommentreport`
  MODIFY `reportID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tblconfirmedposts`
--
ALTER TABLE `tblconfirmedposts`
  MODIFY `cPostID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tblimages`
--
ALTER TABLE `tblimages`
  MODIFY `imageID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `tblmessage`
--
ALTER TABLE `tblmessage`
  MODIFY `messageID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tblmessagereport`
--
ALTER TABLE `tblmessagereport`
  MODIFY `reportID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblpmessage`
--
ALTER TABLE `tblpmessage`
  MODIFY `pMessageID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbltags`
--
ALTER TABLE `tbltags`
  MODIFY `tagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `userID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- Constraints for table `tblcommentreport`
--
ALTER TABLE `tblcommentreport`
  ADD CONSTRAINT `tblcommentreport_ibfk_3` FOREIGN KEY (`reportedUser`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblcommentreport_ibfk_4` FOREIGN KEY (`reportedBy`) REFERENCES `tbluser` (`username`),
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
  ADD CONSTRAINT `tblmessagereport_ibfk_4` FOREIGN KEY (`reportedBy`) REFERENCES `tbluser` (`username`);

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
