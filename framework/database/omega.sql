-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2020 at 04:20 AM
-- Server version: 10.1.37-MariaDB
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
-- Database: `omega`
--

-- --------------------------------------------------------

--
-- Table structure for table `block_list`
--

CREATE TABLE `block_list` (
  `id` int(10) UNSIGNED NOT NULL,
  `n_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `block_list`
--

INSERT INTO `block_list` (`id`, `n_code`) VALUES
(1, '4420440392');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(10) UNSIGNED NOT NULL,
  `image` text NOT NULL,
  `title` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `body` text NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `writer` varchar(255) NOT NULL,
  `updater` varchar(255) DEFAULT NULL,
  `keywords` text NOT NULL,
  `view` bigint(20) UNSIGNED NOT NULL,
  `publish` tinyint(1) UNSIGNED NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `image`, `title`, `slug`, `body`, `category_id`, `writer`, `updater`, `keywords`, `view`, `publish`, `created_at`, `updated_at`) VALUES
(1, '', 'سایت جدید امگا', 'سایت-جدید-امگا', '<p>امگا سایت جدید خود را به شما معرفی می&zwnj;کند</p>', 1, 'godheeva@gmail.com', NULL, 'omega,امگا', 0, 1, 1578347661, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(300) NOT NULL,
  `keywords` text NOT NULL,
  `publish` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `keywords`, `publish`) VALUES
(1, 'اطلاعیه', '', 1),
(2, 'اخبار', '', 1),
(3, 'آموزشی', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `blog_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `body` text NOT NULL,
  `responder_id` int(10) UNSIGNED NOT NULL,
  `respond` text NOT NULL,
  `publish` tinyint(1) UNSIGNED NOT NULL,
  `created_on` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `body` text NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `sent_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `factors`
--

CREATE TABLE `factors` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `plan_id` int(10) UNSIGNED NOT NULL,
  `factor_code` int(8) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `options` text NOT NULL,
  `total_amount` varchar(20) NOT NULL,
  `payed_amount` varchar(20) DEFAULT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(10) UNSIGNED NOT NULL,
  `answer` text NOT NULL,
  `question` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `answer`, `question`) VALUES
(1, 'خیر نمی‌توان! طبق نظر مهندس گرامی‌فر', 'آیا می‌توان پولدار شد؟');

-- --------------------------------------------------------

--
-- Table structure for table `helpful_links`
--

CREATE TABLE `helpful_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `image` text NOT NULL,
  `title` varchar(300) NOT NULL,
  `link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `helpful_links`
--

INSERT INTO `helpful_links` (`id`, `image`, `title`, `link`) VALUES
(1, 'public/uploads/abstract_yin_yang-wallpaper-1920x1080.jpg', 'سایت الکی خوب', 'https://www.google.com');

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `id` int(10) UNSIGNED NOT NULL,
  `mobile` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`) VALUES
(1, 'user'),
(2, 'setting');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `description`) VALUES
(1, 'create'),
(2, 'read'),
(3, 'update'),
(4, 'delete');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `contact` text NOT NULL,
  `capacity` int(6) UNSIGNED NOT NULL,
  `total_price` varchar(20) NOT NULL,
  `base_price` varchar(20) NOT NULL,
  `min_price` varchar(20) NOT NULL,
  `image` text NOT NULL,
  `description` text NOT NULL,
  `rules` text NOT NULL,
  `start_at` int(11) UNSIGNED NOT NULL,
  `end_at` int(11) UNSIGNED NOT NULL,
  `active_at` int(11) UNSIGNED NOT NULL,
  `deactive_at` int(11) UNSIGNED NOT NULL,
  `support_place` text,
  `support_phone` text NOT NULL,
  `place` text NOT NULL,
  `options` text NOT NULL,
  `lng` decimal(10,0) DEFAULT NULL,
  `lat` decimal(10,0) DEFAULT NULL,
  `status` int(1) UNSIGNED NOT NULL,
  `forced_status` tinyint(1) UNSIGNED NOT NULL,
  `publish` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `title`, `slug`, `contact`, `capacity`, `total_price`, `base_price`, `min_price`, `image`, `description`, `rules`, `start_at`, `end_at`, `active_at`, `deactive_at`, `support_place`, `support_phone`, `place`, `options`, `lng`, `lat`, `status`, `forced_status`, `publish`) VALUES
(1, 'طرح جابر بن حیان', 'طرح-جابر-بن-حیان', '6,7', 30, '1000000', '100000', '300000', 'public/uploads/apple_desktop-wallpaper-1920x1080.jpg', 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد وزمان مورد نیاز شامل حروفچینی دستاوردهای اصلی و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.', '<p>1- Be nice</p>\n<p>2- DBAA</p>', 1578934860, 1579540620, 1578330060, 1579194960, 'خونه عمه سعید', '09179516271', 'خونه عمم', '[{\"title\":\"\\u062c\\u062f\\u0648\\u0644 \\u06f1\",\"radio\":\"2\",\"name\":[\"\\u0637\\u0644\\u0627\\u06cc\\u06cc\",\"\\u0646\\u0642\\u0631\\u0647\\u200c\\u0627\\u06cc\"],\"price\":[\"200000\",\"150000\"],\"desc\":[\"\",\"\"]},{\"title\":\"\\u062c\\u062f\\u0648\\u0644 \\u06f2\",\"radio\":\"1\",\"name\":[\"\\u0631\\u06cc\\u0627\\u0636\\u06cc (\\u06f1\\u06f0 \\u062c\\u0644\\u0633\\u0647)\",\"\\u0628\\u0631\\u0646\\u0627\\u0645\\u0647 \\u0646\\u0648\\u06cc\\u0633\\u06cc\"],\"price\":[\"200000\",\"500000\"],\"desc\":[\"\\u06a9\\u0644\\u0627\\u0633\\u200c\\u0647\\u0627\\u06cc \\u06f1\\u06f0 \\u0646\\u0641\\u0631\\u06cc\",\"\\u062a\\u0648\\u0633\\u0637 \\u0627\\u0633\\u062a\\u0627\\u062f \\u0645\\u062d\\u0645\\u062f\"]}]', NULL, NULL, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `plan_images`
--

CREATE TABLE `plan_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `plan_id` int(10) UNSIGNED NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plan_images`
--

INSERT INTO `plan_images` (`id`, `plan_id`, `image`) VALUES
(6, 1, 'public/uploads/apple_desktop-wallpaper-1920x1080.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `plan_reserve`
--

CREATE TABLE `plan_reserve` (
  `id` int(10) UNSIGNED NOT NULL,
  `plan_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reserve_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'superUser', 'کاربر ویژه'),
(2, 'admin', 'ادمین'),
(3, 'writer', 'نویسنده'),
(4, 'student', 'دانش آموز'),
(5, 'collegeStudent', 'دانشجو'),
(6, 'graduate', 'فارغ التحصیل'),
(7, 'guest', 'مهمان');

-- --------------------------------------------------------

--
-- Table structure for table `roles_pages_perms`
--

CREATE TABLE `roles_pages_perms` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `page_id` int(10) UNSIGNED NOT NULL,
  `perm_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_pages_perms`
--

INSERT INTO `roles_pages_perms` (`id`, `role_id`, `page_id`, `perm_id`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 2),
(3, 1, 1, 3),
(4, 1, 1, 4),
(5, 1, 2, 1),
(6, 1, 2, 2),
(7, 1, 2, 3),
(8, 1, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `site_feedback`
--

CREATE TABLE `site_feedback` (
  `id` int(10) UNSIGNED NOT NULL,
  `image` text NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `feedback` text NOT NULL,
  `show_in_page` tinyint(1) UNSIGNED NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `static_pages`
--

CREATE TABLE `static_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(300) NOT NULL,
  `body` text NOT NULL,
  `url_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `static_pages`
--

INSERT INTO `static_pages` (`id`, `title`, `body`, `url_name`) VALUES
(1, 'طریقه ثبت نام در طرح', '<p style=\"direction: rtl;\">خیلی ساده هست میتونید خودتون متوجه بشید...</p>', 'how-to-register-in-plan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(128) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `father_name` varchar(50) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `connector_phone` varchar(11) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `address` text,
  `postal_code` varchar(10) DEFAULT NULL,
  `n_code` varchar(10) DEFAULT NULL,
  `id_code` varchar(10) DEFAULT NULL COMMENT 'شماره شناسنامه',
  `birth_certificate_place` varchar(50) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `gender` tinyint(1) UNSIGNED NOT NULL COMMENT '1: male, 2: female',
  `grade` tinyint(2) UNSIGNED NOT NULL,
  `school` varchar(150) NOT NULL,
  `field` tinyint(1) UNSIGNED NOT NULL,
  `gpa` varchar(20) NOT NULL COMMENT 'معدل',
  `military_status` tinyint(1) UNSIGNED NOT NULL,
  `military_place` varchar(300) NOT NULL,
  `military_end_year` varchar(4) NOT NULL,
  `marital_status` tinyint(1) UNSIGNED NOT NULL,
  `children_count` tinyint(2) UNSIGNED NOT NULL,
  `info_flag` tinyint(1) UNSIGNED NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created_on` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `father_name`, `phone`, `connector_phone`, `province`, `city`, `address`, `postal_code`, `n_code`, `id_code`, `birth_certificate_place`, `image`, `gender`, `grade`, `school`, `field`, `gpa`, `military_status`, `military_place`, `military_end_year`, `marital_status`, `children_count`, `info_flag`, `ip_address`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `active`, `created_on`) VALUES
(1, 'godheeva@gmail.com', '$2y$10$7T3XifCmmYtUphy6FK8rEuglwBqcVd6UJISYmsSldCpoXguTAgC3C', 'سعید گرامی فر', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4420440392', '', '', 'public/fe/img/user-default.jpg', 0, 0, '', 0, '', 0, '', '', 0, 0, 0, '::1', NULL, NULL, NULL, 1, 1577480531);

-- --------------------------------------------------------

--
-- Table structure for table `users_pages_perms`
--

CREATE TABLE `users_pages_perms` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `page_id` int(10) UNSIGNED NOT NULL,
  `perm_id` int(10) UNSIGNED NOT NULL,
  `allow` int(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `zarinpal_payment`
--

CREATE TABLE `zarinpal_payment` (
  `authority` varchar(100) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `plan_id` int(10) UNSIGNED NOT NULL,
  `payment_code` varchar(20) NOT NULL,
  `payment_status` tinyint(3) NOT NULL,
  `amount` varchar(20) NOT NULL,
  `payment_date` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `block_list`
--
ALTER TABLE `block_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factors`
--
ALTER TABLE `factors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `helpful_links`
--
ALTER TABLE `helpful_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_images`
--
ALTER TABLE `plan_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_reserve`
--
ALTER TABLE `plan_reserve`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_pages_perms`
--
ALTER TABLE `roles_pages_perms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rpp_r` (`role_id`),
  ADD KEY `fk_rpp_pa` (`page_id`),
  ADD KEY `fk_rpp_p` (`perm_id`);

--
-- Indexes for table `site_feedback`
--
ALTER TABLE `site_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `static_pages`
--
ALTER TABLE `static_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_pages_perms`
--
ALTER TABLE `users_pages_perms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_upp_u` (`user_id`),
  ADD KEY `fk_upp_pa` (`page_id`),
  ADD KEY `fk_upp_pe` (`perm_id`);

--
-- Indexes for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_urp_u` (`user_id`),
  ADD KEY `fk_urp_r` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `block_list`
--
ALTER TABLE `block_list`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factors`
--
ALTER TABLE `factors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `helpful_links`
--
ALTER TABLE `helpful_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plan_images`
--
ALTER TABLE `plan_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `plan_reserve`
--
ALTER TABLE `plan_reserve`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles_pages_perms`
--
ALTER TABLE `roles_pages_perms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `site_feedback`
--
ALTER TABLE `site_feedback`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `static_pages`
--
ALTER TABLE `static_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_pages_perms`
--
ALTER TABLE `users_pages_perms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_roles`
--
ALTER TABLE `users_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `roles_pages_perms`
--
ALTER TABLE `roles_pages_perms`
  ADD CONSTRAINT `fk_rpp_p` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `fk_rpp_pa` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`),
  ADD CONSTRAINT `fk_rpp_r` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `users_pages_perms`
--
ALTER TABLE `users_pages_perms`
  ADD CONSTRAINT `fk_upp_pa` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`),
  ADD CONSTRAINT `fk_upp_pe` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `fk_upp_u` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD CONSTRAINT `fk_urp_r` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `fk_urp_u` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
