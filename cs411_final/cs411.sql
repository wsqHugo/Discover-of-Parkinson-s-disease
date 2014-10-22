DROP TABLE IF EXISTS `usr_UserAccount`;
CREATE TABLE IF NOT EXISTS `usr_UserAccount` (
  `UserName` varchar(20) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `FirstName` varchar(20) DEFAULT NULL,
  `LastName` varchar(20) DEFAULT NULL,
  `Gender` tinyint(4) DEFAULT NULL,
  `LastUpdateTime` int(11) DEFAULT NULL,
  `BirthdayMonth` tinyint(4) DEFAULT NULL,
  `BirthdayDay` tinyint(4) DEFAULT NULL,
  `BirthdayYear` int(4) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `NailPicture` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`UserName`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usr_UserAccount`
--

INSERT INTO `usr_UserAccount` (`UserName`, `Password`, `FirstName`, `LastName`, `Gender`, `LastUpdateTime`, `BirthdayMonth`, `BirthdayDay`, `BirthdayYear`, `Email`, `NailPicture`) VALUES
('shangquan', 'd28396a88d7d0349efe60d5dcc3d1faa', NULL, NULL, NULL, 1398637860, NULL, NULL, NULL, 'shangquan@gmail.com', NULL),
('shangquan1', 'd28396a88d7d0349efe60d5dcc3d1faa', NULL, NULL, NULL, 1398638056, NULL, NULL, NULL, 'shangquan1@gmail.com', NULL),
('shangquan2', 'd28396a88d7d0349efe60d5dcc3d1faa', 'aaa', 'bbb', 0, 1398638143, 1, 2, 1234, 'shangquan2@gmail.com', NULL),
('shangquan3', 'd28396a88d7d0349efe60d5dcc3d1faa', NULL, NULL, NULL, 1398638226, NULL, NULL, NULL, 'shangquan3@gmail.com', NULL);

DROP TABLE IF EXISTS `usr_UserDetails`;
CREATE TABLE IF NOT EXISTS `usr_UserDetails` (
  `UserName` varchar(20) NOT NULL,
  `UserProfile` varchar(500) DEFAULT NULL,
  `Interest` varchar(500) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `MailAddress` varchar(200) DEFAULT NULL,
  `JobTitle` varchar(50) DEFAULT NULL,
  `OrganizationName` varchar(50) DEFAULT NULL,
  `LastUpdateTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`UserName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usr_UserDetails`
--

INSERT INTO `usr_UserDetails` (`UserName`, `UserProfile`, `Interest`, `Country`, `MailAddress`, `JobTitle`, `OrganizationName`, `LastUpdateTime`) VALUES
('shangquan', NULL, NULL, NULL, NULL, NULL, NULL, 1398637860),
('shangquan1', NULL, NULL, NULL, NULL, NULL, NULL, 1398638056),
('shangquan2', 'qqq', 'www', 'usa', '', 'physician', 'uiuc', 1398638180),
('shangquan3', NULL, NULL, NULL, NULL, NULL, NULL, 1398638226);

DROP TABLE IF EXISTS `usr_UserStatistics`;
CREATE TABLE IF NOT EXISTS `usr_UserStatistics` (
  `UserName` varchar(20) NOT NULL,
  `RegisterTime` int(11) NOT NULL,
  `ActivationKey` varchar(100) DEFAULT NULL,
  `AccountStatus` binary(8) DEFAULT NULL,
  `LastLoginTime` int(11) DEFAULT NULL,
  `LastLogoutTime` int(11) DEFAULT NULL,
  `LoginDuration` int(11) DEFAULT NULL,
  `NoActivityDuration` int(11) DEFAULT NULL,
  `WildCards` varchar(100) DEFAULT NULL,
  `Authority` int(11) DEFAULT NULL,
  `Level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`UserName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usr_UserStatistics`
--

INSERT INTO `usr_UserStatistics` (`UserName`, `RegisterTime`, `ActivationKey`, `AccountStatus`, `LastLoginTime`, `LastLogoutTime`, `LoginDuration`, `NoActivityDuration`, `WildCards`, `Authority`, `Level`) VALUES
('shangquan', 1398637860, NULL, NULL, 1398638317, 1398638385, 68, 66, NULL, NULL, 1),
('shangquan1', 1398638056, NULL, NULL, 1398638391, 1398638405, 14, 115, NULL, NULL, 2),
('shangquan2', 1398638094, NULL, NULL, 1398638308, 1398638313, 5, 104, NULL, NULL, 2),
('shangquan3', 1398638226, NULL, NULL, 1398638290, 1398638300, 10, 61, NULL, NULL, 2);

DROP TABLE IF EXISTS `usr_Comment`;
CREATE TABLE IF NOT EXISTS `usr_Comment` (
  `CommentID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(20) NOT NULL,
  `Content` varchar(500) NOT NULL,
  `SendTime` int(11) NOT NULL,
  `EditTime` int(11) NOT NULL,
  `LastReplyTime` int(11) DEFAULT NULL,
  `Picture` varchar(100) DEFAULT NULL,
  `TargetID` varchar(150) NOT NULL,
  `TargetDescript` varchar(500) NOT NULL,
  `ReplyTo` int(11) DEFAULT NULL,
  `Quote` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`CommentID`),
  KEY `UserName` (`UserName`),
  KEY `TargetID` (`TargetID`),
  KEY `TargetDescript` (`TargetDescript`(333)),
  KEY `ReplyTo` (`ReplyTo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=106 ;

--
-- Dumping data for table `usr_Comment`
--

INSERT INTO `usr_Comment` (`CommentID`, `UserName`, `Content`, `SendTime`, `EditTime`, `LastReplyTime`, `Picture`, `TargetID`, `TargetDescript`, `ReplyTo`, `Quote`) VALUES
(101, 'shangquan1', 'good', 1398638271, 1398638271, NULL, NULL, '1', 'test_review_detail.php', 0, ''),
(102, 'shangquan3', 'bad', 1398638298, 1398638298, NULL, NULL, '1', 'test_review_detail.php', 0, ''),
(103, 'shangquan', 'happy', 1398638329, 1398638329, NULL, NULL, '1', 'test_review_detail.php', 101, '{"quoteAuthor":"shangquan1","quoteSendTime":"1398638271","quoteContent":"good"}'),
(104, 'shangquan', 'sad', 1398638361, 1398638361, NULL, NULL, '1', 'test_review_detail.php', 102, '{"quoteAuthor":"shangquan3","quoteSendTime":"1398638298","quoteContent":"bad"}'),
(105, 'shangquan', 'happy or bad?', 1398638381, 1398638381, NULL, NULL, '1', 'test_review_detail.php', 0, '');

DROP TABLE IF EXISTS `usr_Test`;
CREATE TABLE IF NOT EXISTS `usr_Test` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userName` varchar(20) NOT NULL,
  `createTime` int(11) NOT NULL,
  `Q1` int(1) NOT NULL,
  `Q2` int(1) NOT NULL,
  `Q3` int(1) NOT NULL,
  `Q4` int(1) NOT NULL,
  `Q5` int(1) NOT NULL,
  `Q6` int(1) NOT NULL,
  `videoFile` varchar(200) NOT NULL,
  `waveFile` varchar(200) NOT NULL,
  `groupName` varchar(2000) NOT NULL,
  `processData` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usr_Test`
--

INSERT INTO `usr_Test` (`id`, `userName`, `createTime`, `Q1`, `Q2`, `Q3`, `Q4`, `Q5`, `Q6`, `videoFile`, `waveFile`, `groupName`, `processData`) VALUES
(1, 'shangquan', 1398637931, 1, 1, 0, 1, 1, 0, 'parkinson2_2.mp4', 'test1398637931.txt', 'shangquan1|shangquan3', '5.19230769231');

DROP TABLE IF EXISTS `usr_authorization`;
CREATE TABLE IF NOT EXISTS `usr_authorization` (
  `userName` varchar(20) NOT NULL,
  `groupName` varchar(2000) NOT NULL,
  PRIMARY KEY (`userName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usr_authorization`
--

INSERT INTO `usr_authorization` (`userName`, `groupName`) VALUES
('shangquan', 'shangquan1|shangquan3');
