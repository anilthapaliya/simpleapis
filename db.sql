-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 08, 2025 at 05:27 PM
-- Server version: 10.6.22-MariaDB-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `policies`
--

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `song_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `artist` varchar(200) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `cover_art` varchar(200) NOT NULL,
  `file_path` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`song_id`, `title`, `artist`, `genre`, `cover_art`, `file_path`) VALUES
(1, 'Faded', 'Alan Walker', 'Dance', 'https://policies.foliagesoft.com.np/resources/images/album_art_faded.jpg', 'https://policies.foliagesoft.com.np/resources/audio/faded.mp3'),
(2, 'Lean On', 'Major Lazer & DJ Snake', 'Beats', 'https://policies.foliagesoft.com.np/resources/images/album_art_lean_on.jpg', 'https://policies.foliagesoft.com.np/resources/audio/lean_on.mp3'),
(3, 'Friends', 'Marshmello & Anne-Marie', 'Anthem', 'https://policies.foliagesoft.com.np/resources/images/album_art_friends.jpg', 'https://policies.foliagesoft.com.np/resources/audio/friends.mp3'),
(4, 'Titanium', 'David Guetta', 'Anthem', 'https://policies.foliagesoft.com.np/resources/images/album_art_titanium.jpg', 'https://policies.foliagesoft.com.np/resources/audio/titanium.mp3'),
(5, 'Ghas Katne Khurkera', 'Buddhi Krishna Lamichhane', 'Folk Song', '', 'https://policies.foliagesoft.com.np/resources/audio/ghas_katne.mp3');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `fcm_id` varchar(200) NOT NULL DEFAULT '',
  `token` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email_address`, `password`, `full_name`, `address`, `fcm_id`, `token`) VALUES
(1, 'anil@gmail.com', '$2y$10$H3PuQq4mD6NgQkeYTnRfL.eZLTVg41EUIYHQZtk05zb/ZOpIcG.z.', 'Anil Thapaliya', 'Chitwan', '', 'e208fe4197c90c2e98c31068d8e94127'),
(2, 'nbn@gmail.com', '$2y$10$2nvy0mq9weREFNdVyhtq..CYkEGXZNWiuyw.HlhxQJ.r5VxhQIgKu', 'Nabin Neupane', 'Baglung', '', 'b55c3c3f40434ec6acfa0c238707f571');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`song_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `song_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
