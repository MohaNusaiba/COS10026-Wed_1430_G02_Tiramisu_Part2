-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 26, 2026 at 07:10 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecocityg02`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `member_id` int(11) NOT NULL,
  `member_name` varchar(100) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `project1_contribution` text NOT NULL,
  `project2_contribution` text NOT NULL,
  `quote` text DEFAULT NULL,
  `quote_translation` text DEFAULT NULL,
  `interest_area` varchar(100) DEFAULT NULL,
  `coding_snack` varchar(100) DEFAULT NULL,
  `dream_travel` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`member_id`, `member_name`, `student_id`, `project1_contribution`, `project2_contribution`, `quote`, `quote_translation`, `interest_area`, `coding_snack`, `dream_travel`) VALUES
(1, 'Nusaiba Mohammed', '104649533', 'Completed jobs.html, about.html, styles.css, Jira workspace creation, logo creation, GitHub technical support, navbar and footer setup.', 'Worked on dynamic PHP page conversion, database integration support, shared layout components, styling updates, and repository organisation.', 'অল্প বিদ্যা ভয়ঙ্করী', 'A little learning is a dangerous thing.', 'Cloud & SDN Networking Security, SOC roles', 'Loukamades', 'Norway or Switzerland'),
(2, 'Ruby Telford', '105916092', 'Completed apply.html, index.html, styles.css, GitHub repository support, team meeting chat and form layout.', 'Updated the application form to post to process_eoi.php, disabled client-side validation, implemented server-side EOI processing, validation, sanitising, database insertion, and successful EOI confirmation.', 'All our dreams can come true, if we have the courage to pursue them. - Walt Disney', 'Our dreams can come true if we are brave enough to try.', 'Creating personalised things for friends and family', 'Pretzels', 'Europe'),
(3, 'Harpreet Kour', '106232058', 'Attended meeting, joined Jira, and linked to GitHub.', 'Assisted with project review, testing support, and checking website functionality.', 'ਹਮ ਨਹੀਂ ਚੰਗੇ ਬੁਰਾ ਨਹੀਂ ਕੋਇ', 'I am not good; no one is bad.', 'Travelling', 'Egg wrap', 'India');

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `job_ref_num` varchar(5) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `dob` varchar(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street_address` varchar(50) NOT NULL,
  `suburb_town` varchar(40) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `skill_iot` tinyint(1) DEFAULT 0,
  `skill_data` tinyint(1) DEFAULT 0,
  `skill_urban` tinyint(1) DEFAULT 0,
  `skill_renewable` tinyint(1) DEFAULT 0,
  `skill_problem` tinyint(1) DEFAULT 0,
  `skill_teamwork` tinyint(1) DEFAULT 0,
  `other_skills` text DEFAULT NULL,
  `Status` enum('New','Current','Final') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
