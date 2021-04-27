-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2021 at 05:46 PM
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
-- Database: `happytech`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_log`
--

CREATE TABLE `action_log` (
  `id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `action` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `tablename_id` int(11) NOT NULL,
  `rownumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant`
--

CREATE TABLE `applicant` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decommissioned` tinyint(1) NOT NULL,
  `cv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applicant`
--

INSERT INTO `applicant` (`id`, `name`, `email`, `decommissioned`, `cv`) VALUES
(1, 'David Smith', 'David@email.com', 0, ''),
(2, 'John Adam', 'John@test.com', 0, ''),
(3, 'Ben Jones', 'Ben@test.com', 0, ''),
(4, 'Sam Alan', 'Sam@test.com', 0, NULL),
(5, 'Amy Smith', 'Amy@test.com', 0, NULL),
(6, 'Sarah Jones', 'Sarah@test.com', 0, NULL),
(7, 'Claire Little', 'Claire@email.com', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210401112957', '2021-04-01 13:30:10', 81),
('DoctrineMigrations\\Version20210401113655', '2021-04-01 13:36:59', 161),
('DoctrineMigrations\\Version20210401113926', '2021-04-01 13:39:30', 51),
('DoctrineMigrations\\Version20210401114112', '2021-04-01 13:41:15', 30),
('DoctrineMigrations\\Version20210401123042', '2021-04-01 14:30:46', 108),
('DoctrineMigrations\\Version20210405151607', '2021-04-05 17:16:17', 31),
('DoctrineMigrations\\Version20210407114846', '2021-04-07 13:48:59', 25);

-- --------------------------------------------------------

--
-- Table structure for table `feedback_response`
--

CREATE TABLE `feedback_response` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_id` int(11) NOT NULL,
  `feedback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_response_statement`
--

CREATE TABLE `feedback_response_statement` (
  `id` int(11) NOT NULL,
  `feedbackresponse_id` int(11) NOT NULL,
  `statement_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_type`
--

CREATE TABLE `feedback_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decommissioned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback_type`
--

INSERT INTO `feedback_type` (`id`, `name`, `decommissioned`) VALUES
(1, 'Passed', 0),
(2, 'Failed', 0),
(3, 'Secondment', 0),
(4, 'Needs Improvement', 0),
(5, 'New Hire', 0),
(6, 'Appraisal', 0),
(7, 'Mandatory Training', 0);

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `jobcategory_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duedate` date NOT NULL,
  `decommissioned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`id`, `jobcategory_id`, `name`, `description`, `duedate`, `decommissioned`) VALUES
(1, 1, 'PHP Developer', 'Full Stack Web Developer', '2021-01-04', 0),
(2, 2, 'Javascript Developer', 'Front End Developer', '2021-01-01', 0),
(3, 3, 'Senior Symfony Developer', 'Senior Symfony Developer to work with the Design Team to develop new business solutions', '2021-07-08', 0),
(4, 1, 'Development Team Leader', 'Team Leader to oversee the Web Based Development Team\r\n\r\nTeam Leader to oversee the Web Based Development Team\r\n\r\nTeam Leader to oversee the Web Based Development Team', '2021-05-07', 0);

-- --------------------------------------------------------

--
-- Table structure for table `job_applicant`
--

CREATE TABLE `job_applicant` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `applicantresponded` tinyint(1) NOT NULL,
  `emailed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_category`
--

CREATE TABLE `job_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decommissioned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_category`
--

INSERT INTO `job_category` (`id`, `name`, `decommissioned`) VALUES
(1, 'Promotion', 0),
(2, 'New Hire', 0),
(3, 'Secondment', 0);

-- --------------------------------------------------------

--
-- Table structure for table `statement`
--

CREATE TABLE `statement` (
  `id` int(11) NOT NULL,
  `statement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decommissioned` tinyint(1) NOT NULL,
  `statementtext` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statement`
--

INSERT INTO `statement` (`id`, `statement`, `decommissioned`, `statementtext`) VALUES
(1, 'Passed', 0, 'Passed the Interview'),
(2, 'Failed', 0, 'Failed the Interview'),
(3, 'Clean Code', 0, 'Code is Commented well and is easy to read and follow'),
(4, 'Poorly Commented', 0, 'Code is poorly commented and difficult to follow'),
(5, 'Testing Failed', 0, 'The test was failed and will need to be retaken'),
(6, 'Testing', 0, 'Testing dfvadfvbasgfba');

-- --------------------------------------------------------

--
-- Table structure for table `table_list`
--

CREATE TABLE `table_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `table_list`
--

INSERT INTO `table_list` (`id`, `name`) VALUES
(1, 'Applicant'),
(2, 'Feedback Response'),
(3, 'Feedback Response Statement'),
(4, 'Feedback Type'),
(5, 'Job'),
(6, 'Job Applicant'),
(7, 'Job Category'),
(8, 'Statement'),
(9, 'Template'),
(10, 'Template Header'),
(11, 'Template Statement'),
(12, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE `template` (
  `id` int(11) NOT NULL,
  `header_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decommissioned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template`
--

INSERT INTO `template` (`id`, `header_id`, `name`, `decommissioned`) VALUES
(1, 1, 'Passed', 0),
(2, 2, 'Failed', 0);

-- --------------------------------------------------------

--
-- Table structure for table `template_header`
--

CREATE TABLE `template_header` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedbacktype_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template_header`
--

INSERT INTO `template_header` (`id`, `user_id`, `feedbacktype_id`, `name`) VALUES
(1, 1, 1, 'Job Passed'),
(2, 1, 2, 'Job Failed');

-- --------------------------------------------------------

--
-- Table structure for table `template_statement`
--

CREATE TABLE `template_statement` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `statement_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template_statement`
--

INSERT INTO `template_statement` (`id`, `template_id`, `statement_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decommissioned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `decommissioned`) VALUES
(1, 'Martyn@Happytech.co.uk', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$WFpBYUc3ckcwYUpPU0xQTA$JPwR2vtaqjqIm5RJvQN8oMfRz/s4lX9rJvOVRSD6xv0', 'Martyn', 0),
(2, 'Dave@Happytech.co.uk', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$LjZFUWVYay9oUFJmRDR5eA$daGw76U/3rGtrey9KdwXO7cikKt+Y4iB0NOIxV36ziY', 'Dave', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action_log`
--
ALTER TABLE `action_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B2C5F685A76ED395` (`user_id`),
  ADD KEY `IDX_B2C5F685D6F67E96` (`tablename_id`);

--
-- Indexes for table `applicant`
--
ALTER TABLE `applicant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_CAAD10195E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_CAAD1019E7927C74` (`email`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `feedback_response`
--
ALTER TABLE `feedback_response`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_row` (`template_id`,`applicant_id`,`job_id`),
  ADD KEY `IDX_7135A0895DA0FB8` (`template_id`),
  ADD KEY `IDX_7135A08997139001` (`applicant_id`),
  ADD KEY `IDX_7135A089BE04EA9` (`job_id`);

--
-- Indexes for table `feedback_response_statement`
--
ALTER TABLE `feedback_response_statement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_row` (`feedbackresponse_id`,`statement_id`),
  ADD KEY `IDX_F4C1D6CAC4B5F662` (`feedbackresponse_id`),
  ADD KEY `IDX_F4C1D6CA849CB65B` (`statement_id`);

--
-- Indexes for table `feedback_type`
--
ALTER TABLE `feedback_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_497CB6A05E237E06` (`name`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FBD8E0F81B7AFD87` (`jobcategory_id`);

--
-- Indexes for table `job_applicant`
--
ALTER TABLE `job_applicant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D1DFF08BE04EA9` (`job_id`),
  ADD KEY `IDX_D1DFF0897139001` (`applicant_id`);

--
-- Indexes for table `job_category`
--
ALTER TABLE `job_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_610BBCBA5E237E06` (`name`);

--
-- Indexes for table `statement`
--
ALTER TABLE `statement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C0DB5176C0DB5176` (`statement`);

--
-- Indexes for table `table_list`
--
ALTER TABLE `table_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_97601F835E237E06` (`name`),
  ADD KEY `IDX_97601F832EF91FD8` (`header_id`);

--
-- Indexes for table `template_header`
--
ALTER TABLE `template_header`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_659AA1335E237E06` (`name`),
  ADD KEY `IDX_659AA133A76ED395` (`user_id`),
  ADD KEY `IDX_659AA1331335F5E` (`feedbacktype_id`);

--
-- Indexes for table `template_statement`
--
ALTER TABLE `template_statement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_row` (`template_id`,`statement_id`),
  ADD KEY `IDX_98827C05DA0FB8` (`template_id`),
  ADD KEY `IDX_98827C0849CB65B` (`statement_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action_log`
--
ALTER TABLE `action_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicant`
--
ALTER TABLE `applicant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `feedback_response`
--
ALTER TABLE `feedback_response`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback_response_statement`
--
ALTER TABLE `feedback_response_statement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback_type`
--
ALTER TABLE `feedback_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_applicant`
--
ALTER TABLE `job_applicant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_category`
--
ALTER TABLE `job_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `statement`
--
ALTER TABLE `statement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `table_list`
--
ALTER TABLE `table_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `template_header`
--
ALTER TABLE `template_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `template_statement`
--
ALTER TABLE `template_statement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `action_log`
--
ALTER TABLE `action_log`
  ADD CONSTRAINT `FK_B2C5F685A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_B2C5F685D6F67E96` FOREIGN KEY (`tablename_id`) REFERENCES `table_list` (`id`);

--
-- Constraints for table `feedback_response`
--
ALTER TABLE `feedback_response`
  ADD CONSTRAINT `FK_7135A0895DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`),
  ADD CONSTRAINT `FK_7135A08997139001` FOREIGN KEY (`applicant_id`) REFERENCES `applicant` (`id`),
  ADD CONSTRAINT `FK_7135A089BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`);

--
-- Constraints for table `feedback_response_statement`
--
ALTER TABLE `feedback_response_statement`
  ADD CONSTRAINT `FK_F4C1D6CA849CB65B` FOREIGN KEY (`statement_id`) REFERENCES `statement` (`id`),
  ADD CONSTRAINT `FK_F4C1D6CAC4B5F662` FOREIGN KEY (`feedbackresponse_id`) REFERENCES `feedback_response` (`id`);

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `FK_FBD8E0F81B7AFD87` FOREIGN KEY (`jobcategory_id`) REFERENCES `job_category` (`id`);

--
-- Constraints for table `job_applicant`
--
ALTER TABLE `job_applicant`
  ADD CONSTRAINT `FK_D1DFF0897139001` FOREIGN KEY (`applicant_id`) REFERENCES `applicant` (`id`),
  ADD CONSTRAINT `FK_D1DFF08BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`);

--
-- Constraints for table `template`
--
ALTER TABLE `template`
  ADD CONSTRAINT `FK_97601F832EF91FD8` FOREIGN KEY (`header_id`) REFERENCES `template_header` (`id`);

--
-- Constraints for table `template_header`
--
ALTER TABLE `template_header`
  ADD CONSTRAINT `FK_659AA1331335F5E` FOREIGN KEY (`feedbacktype_id`) REFERENCES `feedback_type` (`id`),
  ADD CONSTRAINT `FK_659AA133A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `template_statement`
--
ALTER TABLE `template_statement`
  ADD CONSTRAINT `FK_98827C05DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`),
  ADD CONSTRAINT `FK_98827C0849CB65B` FOREIGN KEY (`statement_id`) REFERENCES `statement` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
