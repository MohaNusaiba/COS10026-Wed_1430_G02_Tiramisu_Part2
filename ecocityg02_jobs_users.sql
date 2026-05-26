-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2026 at 08:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `reference` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `additional_info` varchar(255) DEFAULT NULL,
  `salary_range` varchar(50) DEFAULT NULL,
  `reports_to` varchar(100) DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `preferable_skills` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `reference`, `title`, `description`, `additional_info`, `salary_range`, `reports_to`, `responsibilities`, `requirements`, `preferable_skills`) VALUES
(1, 'SC123', 'Smart Transport Systems Analyst', 'Design and optimise intelligent transport systems using real-time data and digital platforms to improve urban mobility outcomes.\nWe welcome and encourage applications from Aboriginal and Torres Strait Islander peoples, as well as individuals from all cultural and diverse backgrounds.', 'Quaterly: May require site visits and collaboration with local councils.', '$95,000 – $115,000 AUD', 'Senior Infrastructure Manager', 'Analyse transport data from IoT sensors and traffic systems\nDevelop models to improve traffic flow and reduce congestion\nCollaborate with councils on smart mobility strategies\nSupport deployment of integrated transport platforms', 'Bachelor\'s degree in Engineering, IT, or related field\nExperience with data analytics and transport systems\nProficiency in Python or similar tools', 'Experience in smart city or government projects\nKnowledge of GIS platforms\nStrong stakeholder communication skills'),
(2, 'EN456', 'Energy Monitoring Solutions Engineer', 'Develop and implement digital energy monitoring platforms to enhance sustainability and efficiency across urban infrastructure.\nWe welcome and encourage applications from Aboriginal and Torres Strait Islander peoples, as well as individuals from all cultural and diverse backgrounds.', 'Relocation: Company accommodation available at site.', '$105,000 – $130,000 AUD', 'Head of Smart Energy Solutions', 'Design energy monitoring dashboards and reporting tools\nIntegrate IoT devices for real-time energy tracking\nWork with stakeholders to identify optimisation opportunities\nEnsure compliance with energy regulations', 'Degree in Electrical Engineering or similar\nExperience with IoT platforms and cloud systems\nStrong analytical skills', 'Renewable energy project experience\nData visualisation tools knowledge\nUnderstanding of smart grids'),
(3, 'PM789', 'Smart City Project Manager', 'Lead and coordinate smart city initiatives, ensuring successful delivery across multiple stakeholders.\nWe welcome and encourage applications from Aboriginal and Torres Strait Islander peoples, as well as individuals from all cultural and diverse backgrounds.', 'Paid certification and promotion opportunities open.', '$110,000 – $125,000 AUD', 'Director of Operations', 'Manage project timelines, budgets, and risks\nCoordinate cross-functional teams\nLiaise with councils and partners\nEnsure project governance standards are met', 'Degree in Project Management or related field\nExperience managing complex projects\nStrong leadership skills', 'PRINCE2 or PMP certification\nExperience in infrastructure or government projects\nAgile methodology knowledge'),
(4, 'HR654', 'Human Resources & Talent Specialist', 'Support recruitment, employee engagement, and HR operations in a dynamic consultancy environment.\nWe welcome and encourage applications from Aboriginal and Torres Strait Islander peoples, as well as individuals from all cultural and diverse backgrounds.', 'This position is also open to entry level Legal Assistants.', '$85,000 – $100,000 AUD', 'Head of People & Culture', 'Manage recruitment and onboarding processes\nDevelop engagement and training programs\nEnsure HR compliance and policies\nSupport performance management', 'Degree in Human Resources or related field\nExperience in recruitment and employee relations\nStrong interpersonal skills', 'Experience in consultancy or tech sector\nKnowledge of HR systems\nInterest in workplace culture development');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `login_attempts`, `locked_until`) VALUES
('admin', '$2y$10$jSnQlU6CuuiVkqTF5Nj3y.fq/7U7.1Zdl/4lRhyHrrV7ZD2qM0HMW', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
