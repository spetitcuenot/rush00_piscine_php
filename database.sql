-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jan 27, 2019 at 03:55 PM
-- Server version: 8.0.14
-- PHP Version: 7.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL,
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `price` float NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `name`, `description`, `price`, `stock`, `image`) VALUES
('36390072-bfc7-45b0-8bfc-43523fb3eff6', 'hello', 'world', 12, 2, '5c4d90ee5470f.jpg'),
('68df2147-21b2-11e9-9c1f-0242ac130003', 'weewew', 'wwewewweweweweweewewwebbbb', 12, 22, '5c4cd2cfd6fb6.jpg'),
('760db74b-9747-4ecb-bfa9-fdf8d73f707e', 'hello', 'world', 12, 2, '5c4d90d56535a.jpg'),
('9c22aa82-21a7-11e9-9c1f-0242ac130003', 'Aviscol sauvage', '&lt;img src=1 href=1 onerror=&quot;javascript:alert(1)&quot;&gt;&lt;/img&gt;\r\n&lt;audio src=1 href=1 onerror=&quot;javascript:alert(1)&quot;&gt;&lt;/audio&gt;\r\n&lt;video src=1 href=1 onerror=&quot;javascript:alert(1)&quot;&gt;&lt;/video&gt;\r\n&lt;body src=1 href=1 onerror=&quot;javascript:alert(1)&quot;&gt;&lt;/body&gt;\r\n&lt;image src=1 href=1 onerror=&quot;javascript:alert(1)&quot;&gt;&lt;/image&gt;\r\n&lt;object src=1 href=1 onerror=&quot;javascript:alert(1)&quot;&gt;&lt;/object&gt;\r\n&lt;script src=1 href=1 onerror=&quot;javascript:alert(1)&quot;&gt;&lt;/script&gt;', 900, 68, '5c4cc0b167905.jpeg'),
('d2dfb74e-8a44-44b7-9d48-5195930c7c04', 'hello', 'world', 12, 2, '5c4d910d59a83.jpg'),
('dbe8d679-31d8-4235-808d-2488367554b3', 'ewwe', 'ewwewe', 12, 22, '5c4d9001d23a4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `article_categories`
--

CREATE TABLE `article_categories` (
  `id` varchar(36) NOT NULL,
  `article_id` varchar(36) NOT NULL,
  `category_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `article_categories`
--

INSERT INTO `article_categories` (`id`, `article_id`, `category_id`) VALUES
('4eeddba0-224a-11e9-a753-0242ac130003', '760db74b-9747-4ecb-bfa9-fdf8d73f707e', '0dff3462-21b6-11e9-86cd-0242ac130004');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
('0dff3462-21b6-11e9-86cd-0242ac130004', 'Légumes', 'salut');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` varchar(36) NOT NULL,
  `total` float NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `total`, `user_id`) VALUES
('1e9e9b2e-6f43-4002-bfb9-243acdf38dc9', 900, 'e53e50bd-21af-11e9-9c1f-0242ac130003'),
('421d54e1-3e4c-48cf-be84-3991fac5dd5a', 24, 'e53e50bd-21af-11e9-9c1f-0242ac130003'),
('4d1a2c34-c3ce-4e3e-b6a6-1f8557ff5ce6', 24, 'e53e50bd-21af-11e9-9c1f-0242ac130003'),
('b071d24d-717c-4fae-8240-943cfc5162ee', 12, '687eea24-21a7-11e9-9c1f-0242ac130003');

-- --------------------------------------------------------

--
-- Table structure for table `order_articles`
--

CREATE TABLE `order_articles` (
  `id` varchar(36) NOT NULL,
  `order_id` varchar(36) NOT NULL,
  `article_id` varchar(36) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_articles`
--

INSERT INTO `order_articles` (`id`, `order_id`, `article_id`, `quantity`) VALUES
('3421019e-2241-11e9-a753-0242ac130003', '421d54e1-3e4c-48cf-be84-3991fac5dd5a', '36390072-bfc7-45b0-8bfc-43523fb3eff6', 1),
('3e0b4999-223d-11e9-a753-0242ac130003', '4d1a2c34-c3ce-4e3e-b6a6-1f8557ff5ce6', 'd2dfb74e-8a44-44b7-9d48-5195930c7c04', 1),
('42e1be98-2245-11e9-a753-0242ac130003', '1e9e9b2e-6f43-4002-bfb9-243acdf38dc9', '9c22aa82-21a7-11e9-9c1f-0242ac130003', 1),
('7f6d8791-2242-11e9-a753-0242ac130003', 'b071d24d-717c-4fae-8240-943cfc5162ee', 'd2dfb74e-8a44-44b7-9d48-5195930c7c04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `admin`) VALUES
('31a9d8a5-2236-11e9-a753-0242ac130003', 'Rémi Caumette', 'remi.caumette@icloud.com', 'e381ce0d812a96add78c7cebc00ce0a31d539e4318b12f0599d01e304021af0669d4f735e99ffd2543bb43c6b5d29df553b12076042afacb3826e35d5a1ef336', '8 rue de la monnaie, etage 2', 1),
('687eea24-21a7-11e9-9c1f-0242ac130003', 'Alexis Viscogliosi', 'Alexis.viscogliosi@outlook.fr', 'd9e6762dd1c8eaf6d61b3c6192fc408d4d6d5f1176d0c29169bc24e71c3f274ad27fcd5811b313d681f7e55ec02d73d499c95455b6b5bb503acf574fba8ffe85', '16 rue du bert', 1),
('a9b6f81f-2237-11e9-a753-0242ac130003', 'weweewew', 'weewopweopew@eewwe.fr', 'bf69bb1a4fc8f7b600714c860f7e06d915ffbd8e790afe424e0d444e9c3e16606271f58d43acdab5f80dfc86824928bc5daba26631d6d9b428dddfca04cf5075', 'wewewe', 0),
('b8758759-2237-11e9-a753-0242ac130003', 'eweewew', 'wwewewe@dwddw.fr', '05a659cd3c6cf1a0841e4c9cd39ac064bf66e0a914fb8a837b91426690e2aa86dee5cec80fa0de324febdd4f494c8fb1b698e8dbc79978f2754d76dc7f3ad062', 'ewwewewe', 1),
('c674e0c9-2237-11e9-a753-0242ac130003', 'wewewe', 'ewweweew@weweew.fr', 'c29d7eb7923c9cb52b35e0d6c12586639c56b648504264de8f151fdbf55d6c60140824ff995e06c03f2e4abd480c3f331dd0fcab2b8dd62969025b83c39eeae9', 'wewewe', 1),
('e53e50bd-21af-11e9-9c1f-0242ac130003', 'Rémi Caumette', 'remicaumette@icloud.com', 'e381ce0d812a96add78c7cebc00ce0a31d539e4318b12f0599d01e304021af0669d4f735e99ffd2543bb43c6b5d29df553b12076042afacb3826e35d5a1ef336', '8 rue de la monnaie, etage 2', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `article_categories`
--
ALTER TABLE `article_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `created_at` (`created_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `order_articles`
--
ALTER TABLE `order_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
