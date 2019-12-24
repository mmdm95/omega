-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2019 at 06:46 PM
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
-- Database: `my_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `brand_code` varchar(20) NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `image` text NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `show_in_pages` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_code`, `brand_name`, `image`, `description`, `keywords`, `show_in_pages`) VALUES
(6, 'BRD_FGJI3TKa', 'Samsung', 'public/uploads/brands/samsung-logo.jpg', '', 'samsung,سامسونگ,SAMSUNG', 1),
(10, 'BRD_XdIEhUCt', 'ADATA', 'public/uploads/brands/adata.png', '', 'ADATA', 1),
(11, 'BRD_ym8heDyC', 'Huawei', 'public/uploads/brands/Huawei.png', '', 'هوآوی,Huawei,HUAWEI,Smart Phone,Honor', 1),
(12, 'BRD_4LKS1Wzz', 'پارس خزر', 'public/uploads/brands/pars-khazar.png', 'در اسفند ماه ۱۳۴۷ با مشارکت و همکاری فنی کارخانه توشیبای ژاپن، شرکت پارس توشیبا فعالیت خود را در استان گیلان آغاز کرد. در آبان سال ۱۳۶۱ نام شرکت پارس توشیبا به پارس خزر تغییر یافت. درحال حاضر پارس خزر به عنوان بزرگ‌ترین تولید کننده لوازم خانگی برقی کوچک در ایران، به صورت سهامی عام به فعالیت خود ادامه می‌دهد. خشنودی مشتریان، طراحی و توسعه محصولات جدید و تلاش در شکوفایی ایران، سرلوحه فعالیت این شرکت است.', 'Pars,Pars Khazar,پارس خزر,کالای ایرانی,لوازم خانگی', 1),
(13, 'BRD_TqjV4e5c', 'ایکس‌ویژن', 'public/uploads/brands/x-vision.png', '', 'X-VISION,کالای ایرانی,برند ایرانی,لوازم خانگی', 1),
(14, 'BRD_ULN5MKZG', 'متفرقه', 'public/uploads/brands/100514.png', '', '', 0),
(15, 'BRD_YN7DeVgA', 'تراستکتور', 'public/uploads/brands/100009102.jpg', 'معرفی برند تراستکتور\r\n\r\nتراستکتور برند مطمئن و قابل اعتماد در تولید محافظ صفحه نمایش است محافظ صفحه نمایش‌های ساخته شده توسط این برند دارای برش های دقیق و منظم با بهترین تکنولوژی ساخت است که نتیجه‌ی آن حفظ کیفیت تاچ و تصویر صفحه نمایش شما بوده این برند علاوه بر تاکید بر کیفیت دارای قیمت مناسب و مقرون به صرفه نیز است.', '', 1),
(16, 'BRD_kEqOAnbB', 'نیلکون', 'public/uploads/brands/nillkon.png', '', '', 1),
(17, 'BRD_GviPfzho', 'شیائومی', 'public/uploads/brands/Xiaomi.png', '', 'Xiaomi', 1),
(18, 'BRD_lQstNR5U', 'Apple', 'public/uploads/brands/Apple.png', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_code` varchar(20) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT '0',
  `all_parents` text NOT NULL,
  `level` tinyint(2) UNSIGNED NOT NULL,
  `keywords` text NOT NULL,
  `deletable` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `priority` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `show_in_menu` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_code`, `category_name`, `parent_id`, `all_parents`, `level`, `keywords`, `deletable`, `priority`, `status`, `show_in_menu`) VALUES
(1, 'CAT_k2d489', 'دسته‌بندی نشده', NULL, '', 0, '', 0, 0, 0, 0),
(9, 'CAT_W2MOZ0PC', 'گوشی موبایل', 10, '10', 2, 'موبایل,گوشی,گوشی موبایل', 1, 2, 1, 1),
(10, 'CAT_FMXSQGkE', 'کالای دیجیتال', 0, '', 1, '', 1, 0, 1, 1),
(11, 'CAT_hJhgnmE6', 'لوازم جانبی گوشی', 10, '10', 2, '', 1, 3, 1, 1),
(12, 'CAT_OOsXx3Fd', 'کیف و کاور گوشی', 11, '11,10', 3, '', 1, 6, 1, 1),
(13, 'CAT_aVzqFSYz', 'هارد و فلش SSD', 10, '10', 2, '', 1, 4, 1, 1),
(14, 'CAT_4nUmKIni', 'هوآوی', 9, '9,10', 3, '', 1, 8, 1, 1),
(15, 'CAT_CyiioFSX', 'هندزفری', 11, '11,10', 3, '', 1, 5, 1, 1),
(16, 'CAT_JfdVwOpT', 'محافظ صفحه نمایش گوشی', 11, '11,10', 3, '', 1, 3, 1, 1),
(17, 'CAT_HwxqeaZa', 'پاوربانک (شارژر همراه)', 11, '11,10', 3, '', 1, 4, 1, 1),
(18, 'CAT_CF2WHcOJ', 'آیفون اپل', 9, '9,10', 3, '', 1, 7, 1, 1),
(19, 'CAT_4aXv6LmA', 'لوازم جانبی دوربین', 10, '10', 2, '', 1, 1, 1, 1),
(20, 'CAT_MD7JeKwZ', 'لنز', 19, '19,10', 3, '', 1, 1, 1, 0),
(21, 'CAT_vLxYENkE', 'کیف', 19, '19,10', 3, '', 1, 2, 1, 0),
(22, 'CAT_Z9LS2q4R', 'سامسونگ', 9, '9,10', 3, '', 1, 9, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(10) UNSIGNED NOT NULL,
  `color_code` varchar(20) NOT NULL,
  `color_name` varchar(100) NOT NULL,
  `color_hex` varchar(9) NOT NULL,
  `deletable` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `color_code`, `color_name`, `color_hex`, `deletable`) VALUES
(1, 'COL_Jfh21ocv', 'بی‌رنگ', '#FFFFFF', 0),
(3, 'COL_JDhx5CLB', 'نقره‌ای', '#e3e5e6', 1),
(4, 'COL_u9oEpW8n', 'سفید', '#ffffff', 1),
(5, 'COL_X8a89yfU', 'آبی', '#3085d1', 1),
(6, 'COL_Kxv4RmTl', 'سبز', '#29de9e', 1),
(8, 'COL_wBddfCXU', 'گل رز', '#ff6bf5', 1),
(9, 'COL_SJVzfKZJ', 'زرشکی', '#77083a', 1),
(10, 'COL_C8uJnVzP', 'مشکی', '#000000', 1),
(11, 'COL_gDIbwuXj', 'قهوه‌ای', '#6e2100', 1),
(12, 'COL_M5SSescK', 'سورمه‌ای', '#0725b7', 1),
(13, 'COL_USfgmkxC', 'طلایی', '#f7bc0b', 1),
(14, 'COL_jStmNeaL', 'خاکستری', '#b9b9b9', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `cons` text NOT NULL,
  `pros` text NOT NULL,
  `recommended` tinyint(1) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `helpful` int(10) UNSIGNED NOT NULL,
  `useless` int(10) UNSIGNED NOT NULL,
  `comment_date` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `product_id`, `user_id`, `body`, `cons`, `pros`, `recommended`, `status`, `helpful`, `useless`, `comment_date`) VALUES
(3, 6, 10, 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد وزمان مورد نیاز شامل حروفچینی دستاوردهای اصلی و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.', 'اثر انگشت ندارد,صفحه نمایش کوچولو', 'نسبت به قیمت مناسب است,بدنه مقاوم,دوربین مناسب,گیر نداره', 1, 2, 0, 0, 1573421625);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `call_number` varchar(11) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `order_code` varchar(20) DEFAULT NULL,
  `body` text NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `send_time` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `full_name`, `call_number`, `subject`, `order_code`, `body`, `status`, `send_time`) VALUES
(1, 'mm', 'dm', 'ok', '', 'hi babe!', 1, 1573501390);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `coupon_code` varchar(20) NOT NULL,
  `coupon_title` varchar(300) NOT NULL,
  `min_price` varchar(20) NOT NULL,
  `max_price` varchar(20) DEFAULT NULL,
  `amount` varchar(20) NOT NULL,
  `unit` tinyint(1) UNSIGNED NOT NULL,
  `coupon_expire_time` int(11) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_code`, `coupon_title`, `min_price`, `max_price`, `amount`, `unit`, `coupon_expire_time`, `status`) VALUES
(2, 'COP_MMDMTEST', 'بچه تخفیف', '15000', '', '3000', 1, 1574281800, 1),
(3, 'Summer-98', 'تخفیف ویژه تابستان ۱۳۹۸', '150000', '2000000', '150000', 1, 1567279800, 0);

-- --------------------------------------------------------

--
-- Table structure for table `factors`
--

CREATE TABLE `factors` (
  `id` int(10) UNSIGNED NOT NULL,
  `factor_code` varchar(20) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `method_code` varchar(20) NOT NULL,
  `payment_title` varchar(100) NOT NULL,
  `payment_status` tinyint(1) NOT NULL,
  `send_status` int(10) UNSIGNED DEFAULT NULL,
  `amount` varchar(100) NOT NULL,
  `shipping_title` varchar(100) NOT NULL,
  `shipping_price` varchar(100) DEFAULT NULL,
  `shipping_min_days` int(3) UNSIGNED NOT NULL,
  `shipping_max_days` int(3) UNSIGNED NOT NULL,
  `final_amount` varchar(100) NOT NULL,
  `coupon_code` varchar(20) NOT NULL,
  `coupon_title` varchar(300) NOT NULL,
  `coupon_amount` varchar(20) NOT NULL,
  `coupon_unit` tinyint(1) UNSIGNED DEFAULT NULL,
  `discount_price` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_receiver` varchar(100) NOT NULL,
  `shipping_province` varchar(100) NOT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `shipping_postal_code` varchar(10) NOT NULL,
  `shipping_phone` varchar(11) NOT NULL,
  `want_factor` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `payment_date` int(11) DEFAULT NULL,
  `shipping_date` int(11) DEFAULT NULL,
  `order_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `factors`
--

INSERT INTO `factors` (`id`, `factor_code`, `user_id`, `first_name`, `last_name`, `mobile`, `method_code`, `payment_title`, `payment_status`, `send_status`, `amount`, `shipping_title`, `shipping_price`, `shipping_min_days`, `shipping_max_days`, `final_amount`, `coupon_code`, `coupon_title`, `coupon_amount`, `coupon_unit`, `discount_price`, `shipping_address`, `shipping_receiver`, `shipping_province`, `shipping_city`, `shipping_postal_code`, `shipping_phone`, `want_factor`, `payment_date`, `shipping_date`, `order_date`) VALUES
(4, 'BTK_823134', 10, 'محمد مهدی', 'دهقان', '09179516271', 'PAY_86106351', 'پرداخت درب منزل', -9, 1, '41000', 'پست سفارشی', '18000', 1, 3, '39700', 'COP_MMDMTEST', 'بچه تخفیف', '3000', 1, '1300', 'بلوار جام جم کوچه دهم پلاک 14', 'محمد مهدی دهقان', 'فارس', 'آباده', '9999999999', '09179516271', 0, 1573202277, NULL, 1573202275),
(5, 'BTK_791084', 10, 'محمد مهدی', 'دهقان', '09179516271', 'PAY_86106351', 'پرداخت درب منزل', -8, NULL, '50700', 'پست سفارشی', '18000', 1, 3, '32700', 'COP_MMDMTEST', 'بچه تخفیف', '3000', 1, '15000', 'بلوار جام جم کوچه دهم پلاک 14', 'محمد مهدی دهقان', 'فارس', 'آباده', '9999999999', '09179516271', 1, 1573204681, NULL, 1573204425),
(6, 'BTK_163584', 10, 'محمد مهدی', 'دهقان', '09179516271', 'PAY_32879731', 'درگاه پرداخت بانک تجارت', 0, 1, '41000', 'پست سفارشی', '18000', 1, 3, '39700', 'COP_MMDMTEST', 'بچه تخفیف', '3000', 1, '1300', 'بلوار جام جم کوچه دهم پلاک 14', 'محمد مهدی دهقان', 'فارس', 'آباده', '9999999999', '09179516271', 0, 1573826815, NULL, 1573843083);

-- --------------------------------------------------------

--
-- Table structure for table `factors_item`
--

CREATE TABLE `factors_item` (
  `id` int(10) UNSIGNED NOT NULL,
  `factor_code` varchar(20) DEFAULT NULL,
  `product_code` varchar(20) NOT NULL,
  `product_count` int(10) UNSIGNED NOT NULL,
  `product_color` varchar(100) NOT NULL,
  `product_color_hex` varchar(9) NOT NULL,
  `product_unit_price` bigint(20) UNSIGNED NOT NULL,
  `product_price` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `factors_item`
--

INSERT INTO `factors_item` (`id`, `factor_code`, `product_code`, `product_count`, `product_color`, `product_color_hex`, `product_unit_price`, `product_price`) VALUES
(4, 'BTK_823134', 'PRD_IMiWqaVX', 1, 'بی‌رنگ', '#FFFFFF', 26000, 26000),
(5, 'BTK_791084', 'PRD_FL7o0vQM', 3, 'بی‌رنگ', '#FFFFFF', 10900, 32700),
(6, 'BTK_163584', 'PRD_FL7o0vQM', 3, 'بی‌رنگ', '#FFFFFF', 10900, 32700);

-- --------------------------------------------------------

--
-- Table structure for table `factors_reserved`
--

CREATE TABLE `factors_reserved` (
  `id` int(10) UNSIGNED NOT NULL,
  `factor_code` varchar(20) NOT NULL,
  `factor_time` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `festivals`
--

CREATE TABLE `festivals` (
  `id` int(10) UNSIGNED NOT NULL,
  `festival_code` varchar(20) NOT NULL,
  `festival_title` varchar(300) NOT NULL,
  `festival_set_date` int(11) NOT NULL,
  `festival_expire_date` int(11) NOT NULL,
  `is_main` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `festivals`
--

INSERT INTO `festivals` (`id`, `festival_code`, `festival_title`, `festival_set_date`, `festival_expire_date`, `is_main`, `status`) VALUES
(2, 'FES_HBOK58YQ', 'جشنواره تابستان شگفت انگیز', 1568518020, 1574310420, 1, 1),
(3, 'FES_G5yVGFpk', 'جشنواره گوشی‌های شگفت‌انگیز', 1568695860, 1574332740, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gateway_idpay`
--

CREATE TABLE `gateway_idpay` (
  `id` int(11) NOT NULL,
  `factor_code` varchar(20) NOT NULL,
  `payment_code` varchar(20) NOT NULL,
  `payment_id` varchar(300) NOT NULL,
  `payment_link` text NOT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `track_id` varchar(100) NOT NULL,
  `msg` text NOT NULL,
  `mask_card_number` varchar(16) NOT NULL,
  `payment_date` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gateway_idpay`
--

INSERT INTO `gateway_idpay` (`id`, `factor_code`, `payment_code`, `payment_id`, `payment_link`, `status`, `track_id`, `msg`, `mask_card_number`, `payment_date`) VALUES
(1, 'BTK_163584', '13255377932', 'dae53f08a8d6d515755c91f7722794c0', 'https://idpay.ir/p/ws/dae53f08a8d6d515755c91f7722794c0', 1, '7966397', 'پرداخت ناموفق بوده است', '603769******9389', 1573826815);

-- --------------------------------------------------------

--
-- Table structure for table `gateway_mabna`
--

CREATE TABLE `gateway_mabna` (
  `id` int(11) NOT NULL,
  `factor_code` varchar(20) NOT NULL,
  `payment_code` varchar(20) NOT NULL,
  `payment_id` varchar(300) NOT NULL,
  `payment_link` text NOT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `track_id` varchar(100) NOT NULL,
  `msg` text NOT NULL,
  `mask_card_number` varchar(16) NOT NULL,
  `payment_date` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gateway_zarinpal`
--

CREATE TABLE `gateway_zarinpal` (
  `authority` varchar(100) NOT NULL,
  `factor_code` varchar(20) NOT NULL,
  `payment_code` varchar(20) NOT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `payment_date` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `guarantees`
--

CREATE TABLE `guarantees` (
  `id` int(10) UNSIGNED NOT NULL,
  `guarantee_code` varchar(20) NOT NULL,
  `guarantee_title` varchar(300) NOT NULL,
  `image` text NOT NULL,
  `deletable` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `guarantees`
--

INSERT INTO `guarantees` (`id`, `guarantee_code`, `guarantee_title`, `image`, `deletable`) VALUES
(1, 'GRT_g9Ed9i', 'ندارد', '', 0),
(3, 'GRT_1ok65e', 'سام سرویس', 'public/uploads/guarantees/sam-service.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
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
(1, 'setting'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `method_code` varchar(20) NOT NULL,
  `method_title` varchar(100) NOT NULL,
  `image` text NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `deletable` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `method_code`, `method_title`, `image`, `status`, `deletable`) VALUES
(2, 'PAY_74214735', 'درگاه پرداخت بانک ملت', 'public/uploads/payment-gateway/yxpqervzmp8etor3jxei.jpg', 1, 0),
(7, 'PAY_32879731', 'درگاه پرداخت بانک تجارت', 'public/uploads/payment-gateway/24.png', 1, 0),
(8, 'PAY_86106351', 'پرداخت درب منزل', '', 1, 1);

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_code` varchar(20) NOT NULL,
  `product_title` varchar(400) NOT NULL,
  `latin_title` varchar(400) NOT NULL,
  `brand` int(10) UNSIGNED DEFAULT NULL,
  `category` int(10) UNSIGNED DEFAULT NULL,
  `image` text NOT NULL,
  `body` text NOT NULL,
  `stock_count` int(10) UNSIGNED NOT NULL,
  `sold_count` int(10) UNSIGNED NOT NULL,
  `discount` varchar(10) NOT NULL,
  `discount_unit` tinyint(1) UNSIGNED NOT NULL,
  `property` text NOT NULL,
  `property_abstract` text NOT NULL,
  `related` text NOT NULL,
  `user_created_id` int(10) UNSIGNED NOT NULL,
  `user_updated_id` int(10) UNSIGNED NOT NULL,
  `keywords` text NOT NULL,
  `publish` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `available` tinyint(1) UNSIGNED NOT NULL,
  `view` int(11) UNSIGNED NOT NULL,
  `created_on` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `product_title`, `latin_title`, `brand`, `category`, `image`, `body`, `stock_count`, `sold_count`, `discount`, `discount_unit`, `property`, `property_abstract`, `related`, `user_created_id`, `user_updated_id`, `keywords`, `publish`, `available`, `view`, `created_on`) VALUES
(4, 'PRD_IMiWqaVX', 'هندزفری بلوتوث مدل i7 الف 001', 'Bluetooth i7 001', 14, 15, 'public/uploads/products/HandsFree-Bluetooth-I7/1.jpg', 'اگر شما در طول روز تماس‌های زیادی دارید و استفاده از گوشی برایتان مقدور نیست، نیاز به هندزفری با کیفیت دارید. هندزفری بلوتوث مدل i7 با طراحی زیبا انتخاب مناسبی برای شما است. این کالا در رنگ طلایی و با الهام گرفتن از ایرپاد اپل طراحی شده است. این هندزفری علاوه بر قابلیت مکالمه تلفنی، به شما امکان کنترل صدا و پخش موسیقی را هم می‌دهد. با استفاده از بلوتوث این محصول به گوشی هوشمند‌تان متصل می‌شود. وزن این کالا 5 گرم است و در استفاده‌های طولانی مدت شما را خسته نخواهد کرد. مدت زمان نگهداری شارژ در حالت استندبای هندزفری بلوتوث مدل i7 ، صد و بیست ساعت تخمین زده شده است، همین امر سبب شده شما نیاز به شارژ زود هنگام هندزفریتان را نداشته باشید. در جعبه این کالا علاوه بر هندزفری کابل DC به USB 2 هم ارائه شده است و در بسته hpq i7 ارائه میشود i7s .', 15, 4, '5', 2, '[{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u06a9\\u0644\\u06cc\",\"property\":[\"\\u0642\\u0627\\u0628\\u0644\\u06cc\\u062a \\u067e\\u062e\\u0634 \\u0645\\u0648\\u0633\\u06cc\\u0642\\u06cc\",\"\\u0642\\u0627\\u0628\\u0644\\u06cc\\u062a \\u06a9\\u0646\\u062a\\u0631\\u0644 \\u0635\\u062f\\u0627 \\u0648 \\u0645\\u0648\\u0632\\u06cc\\u06a9\",\"\\u0631\\u0627\\u0647\\u0646\\u0645\\u0627\\u06cc \\u0635\\u0648\\u062a\\u06cc\"],\"value\":[\"\\u062f\\u0627\\u0631\\u062f\",\"\\u062f\\u0627\\u0631\\u062f\",\"\\u0646\\u062f\\u0627\\u0631\\u062f\"]},{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u0647\\u062f\\u0641\\u0648\\u0646\",\"property\":[\"\\u0627\\u0645\\u067e\\u062f\\u0627\\u0646\\u0633\"],\"value\":[\"\\u06f3\\u06f2 \\u0627\\u0647\\u0645\"]},{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u062f\\u06cc\\u06af\\u0631\",\"property\":[\"\\u0648\\u0632\\u0646\",\"\\u0645\\u062f\\u062a \\u0632\\u0645\\u0627\\u0646 \\u0646\\u06af\\u0647\\u062f\\u0627\\u0631\\u06cc \\u0634\\u0627\\u0631\\u0698 \\u062f\\u0631 \\u062d\\u0627\\u0644\\u062a \\u0627\\u0633\\u062a\\u0646\\u062f\\u0628\\u0627\\u06cc\",\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u062f\\u06cc\\u06af\\u0631\"],\"value\":[\"\\u06f5 \\u06af\\u0631\\u0645\",\"\\u06f1\\u06f2\\u06f0 \\u0633\\u0627\\u0639\\u062a\",\" \\u062f\\u0627\\u0631\\u0627\\u06cc \\u06a9\\u0627\\u0628\\u0644 DC \\u0628\\u0647 USB 2 \"]}]', 'قابلیت پخش موسیقی: دارد ,قابلیت کنترل صدا و موزیک: دارد ,راهنمای صوتی: ندارد', '', 7, 7, 'هندزفری بلوتوث,هندزفری', 1, 1, 25, 1566550415),
(5, 'PRD_FL7o0vQM', 'محافظ صفحه نمایش تراستکتور مدل GLS مناسب برای گوشی موبایل سامسونگ Galaxy A40', 'Trustector GLS Screen Protector For Samsung Galaxy A40', 15, 16, 'public/uploads/products/Trustector-GLS-Screen-Protector-For-Samsung-Galaxy-A40/111654496.jpg', 'گلس محافظ صفحه نمایش گوشی سامسونگ Galaxy A40 ، یک محافظ ضد خراش قوی است که گوشی شما را برای همیشه نو و تمیز نگه می دارد. میزان سختی این گلس 9H است و در عین محافظت مطمئن ضخامت بسیار کمی دارد. پک های محافظتی تراستکتور کوچکترین تاثیری بر عملکرد تاچ و لمس گوشی شما نخواهد داشت و کیفیت صفحه نمایش شما را کاهش نمی دهد، همچنین گلس تراستکتور به خوبی از LCD گوشی شما محافظت می کند، جلوی خط و خراش افتادن روی آن را می گیرد و اجازه نمی دهد در صورت زمین خوردن، آسیبی به صفحه نمایش گوشی وارد شود. در صورتی که ضربه بسیار شدید به آن وارد شود، به راحتی نمی شکند و در صورت شکستن هم، پودر نمی شود. این گلس به خوبی برش خورده است و به راحتی روی گوشی قرار می گیرد.', 2, 3, '5000', 1, '[{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u06a9\\u0644\\u06cc\",\"property\":[\"\\u0646\\u0648\\u0639\",\"\\u0642\\u0627\\u0628\\u0644\\u06cc\\u062a \\u0646\\u0635\\u0628 \\u0622\\u0633\\u0627\\u0646\",\"\\u0645\\u0642\\u0627\\u0648\\u0645 \\u062f\\u0631 \\u0628\\u0631\\u0627\\u0628\\u0631 \\u0636\\u0631\\u0628\\u0647\",\"\\u062c\\u0644\\u0648\\u06af\\u06cc\\u0631\\u06cc \\u0627\\u0632 \\u0627\\u06cc\\u062c\\u0627\\u062f \\u062e\\u0637 \\u0648 \\u062e\\u0634\",\"\\u062c\\u0644\\u0648\\u06af\\u06cc\\u0631\\u06cc \\u0627\\u0632 \\u0627\\u0646\\u0639\\u06a9\\u0627\\u0633 \\u0646\\u0648\\u0631\",\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u062f\\u06cc\\u06af\\u0631\"],\"value\":[\"\\u0628\\u0631\\u0627\\u0642\",\"\\u0628\\u0644\\u0647\",\"\\u062f\\u0627\\u0631\\u062f\",\"\\u062f\\u0627\\u0631\\u062f\",\"\\u062f\\u0627\\u0631\\u062f\",\" \\u0627\\u0633\\u062a\\u0641\\u0627\\u062f\\u0647 \\u0627\\u0632 \\u0634\\u06cc\\u0634\\u0647 \\u062d\\u0631\\u0627\\u0631\\u062a \\u062f\\u06cc\\u062f\\u0647 \\u062a\\u062d\\u062a \\u0634\\u0631\\u0627\\u06cc\\u0637 \\u062e\\u0627\\u0635 , \\u0628\\u0631\\u0634 \\u062f\\u0642\\u06cc\\u0642 \\u0633\\u0646\\u0633\\u0648\\u0631 \\u0647\\u0627 \\u0628\\u0631\\u0627\\u06cc \\u0646\\u0628\\u0648\\u062f \\u06a9\\u0648\\u0686\\u06a9\\u062a\\u0631\\u06cc\\u0646 \\u0646\\u0642\\u0635\\u06cc , \\u0633\\u0627\\u0632\\u06af\\u0627\\u0631\\u06cc \\u0628\\u0627 \\u062a\\u0645\\u0627\\u0645\\u06cc \\u06a9\\u06cc\\u0633 \\u0648 \\u06a9\\u06cc\\u0641 \\u0647\\u0627\\u06cc \\u0645\\u0648\\u062c\\u0648\\u062f , \\u0633\\u062e\\u062a\\u06cc 9H\\u060c \\u0648 \\u0645\\u0642\\u0627\\u0648\\u0645\\u062a \\u0628\\u0633\\u06cc\\u0627\\u0631 \\u0628\\u0627\\u0644\\u0627 \\u062f\\u0631 \\u0628\\u0631\\u0627\\u0628\\u0631 \\u062e\\u0637 \\u0648 \\u062e\\u0634 , \\u0645\\u0642\\u0627\\u0648\\u0645 \\u062f\\u0631 \\u0628\\u0631\\u0627\\u0628\\u0631 \\u0627\\u062b\\u0631 \\u0627\\u0646\\u06af\\u0634\\u062a\\u060c \\u0642\\u0637\\u0631\\u0627\\u062a \\u0622\\u0628\\u060c\\u0686\\u0631\\u0628\\u06cc , \\u0639\\u062f\\u0645 \\u06a9\\u0627\\u0647\\u0634 \\u062d\\u0633\\u0627\\u0633\\u06cc\\u062a \\u062a\\u0627\\u0686 \\u0635\\u0641\\u062d\\u0647 \\u0646\\u0645\\u0627\\u06cc\\u0634 , \\u062f\\u0627\\u0631\\u0627\\u06cc \\u0648\\u0636\\u0648\\u062d \\u0648 \\u0634\\u0641\\u0627\\u0641\\u06cc\\u062a \\u0628\\u0633\\u06cc\\u0627\\u0631 \\u0628\\u0627\\u0644\\u0627 \\u0628\\u0627 \\u0642\\u0627\\u0628\\u0644\\u06cc\\u062a \\u0631\\u062f \\u06a9\\u0631\\u062f\\u0646 99 \\u062f\\u0631\\u0635\\u062f \\u0646\\u0648\\u0631 \\u0627\\u0632 \\u0635\\u0641\\u062d\\u0647 \\u0646\\u0645\\u0627\\u06cc\\u0634 \\u0628\\u0647 \\u0686\\u0634\\u0645 \\u0628\\u06cc\\u0646\\u0646\\u062f\\u0647 , \\u062f\\u0627\\u0631\\u0627\\u06cc \\u0644\\u0628\\u0647 \\u0647\\u0627\\u06cc\\u06cc \\u062e\\u0645\\u06cc\\u062f\\u0647 \"]}]', 'مناسب برای گوشی های: Samsung Galaxy A40,نوع: براق ,مقاوم در برابر ضربه: دارد', '', 7, 7, '', 1, 1, 43, 1566552479),
(6, 'PRD_Y3F1ZaIZ', 'گوشی موبایل سامسونگ مدل Galaxy A40 SM-A405FN/DS دو سیم‌کارت ظرفیت 64 گیگابایت', 'Samsung Galaxy A40 SM-A405FN/DS Dual Sim 64GB Mobile Phone', 6, 22, 'public/uploads/products/Samsung-Galaxy-A40-SM-A405FNDS-Dual-Sim-64GB-Mobile-Phone/112344253.jpg', 'گوشی سامسونگ Galaxy A40 از زبان طراحی سری گوشی های گلکسی A 2019 این شرکت پیروی می‌کند اما یک تفاوت عمده نسبت به دیگر محصولات سری گلکسی A سال 2019 دارد که مربوط به اندازه و ابعاد آن می‌شود. بدنه این گوشی ابعاد 7.9 در 69.2 در 144.4 میلیمتری دارد و بدین ترتیب در مقایسه با دیگر گوشی های سری گلکسی A کوچک تر است و راحت تر می‌توان با یک دست از آن استفاده کرد. وزن این گوشی هم به چیزی در حدود 140 گرم می‌رسد که وزن مناسبی خواهد بود و باز هم منجر به سهولت حمل و نقل دستگاه می‌شود. وقتی Galaxy A40 سامسونگ را در دست می‌گیرید حس خوبی به شما منتقل خواهد شد. سامسونگ پلاستیکی که در قاب پشت این اسمارتفون بکار رفته را با لقب \"Glastic\" (گلاستیک) معرفی کرده که این لقب از ترکیب دو عبارت \"Glass\" (شیشه) و \"Plastic\" (پلاستیک) به وجود می‌آید. در بخش فوقانی و سمت چپ قاب پشت این گوشی دو لنز دوربین اصلی آن و چراغ فلش LED دستگاه نصب هستند و تقریب در وسط قاب پشت آن و کمی بالاتر شاهد بکارگیری اسکنر انگشت آن هستیم.', 10, 0, '0', 1, '[{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u06a9\\u0644\\u06cc\",\"property\":[\"\\u0627\\u0628\\u0639\\u0627\\u062f\",\"\\u062a\\u0648\\u0636\\u06cc\\u062d\\u0627\\u062a \\u0633\\u06cc\\u0645 \\u06a9\\u0627\\u0631\\u062a\",\"\\u0648\\u0632\\u0646\",\"\\u062a\\u0639\\u062f\\u0627\\u062f \\u0633\\u06cc\\u0645 \\u06a9\\u0627\\u0631\\u062a\"],\"value\":[\" 144.3 \\u00d7 69.1 \\u00d7 7.9 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0645\\u062a\\u0631 \",\" \\u0633\\u0627\\u06cc\\u0632 \\u0646\\u0627\\u0646\\u0648 (8.8 \\u00d7 12.3 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0645\\u062a\\u0631) \",\" 140 \\u06af\\u0631\\u0645 \",\" \\u062f\\u0648 \\u0633\\u06cc\\u0645 \\u06a9\\u0627\\u0631\\u062a \"]},{\"title\":\"\\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\",\"property\":[\"\\u062a\\u0631\\u0627\\u0634\\u0647\",\"\\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\\u200c\\u06cc \\u0645\\u0631\\u06a9\\u0632\\u06cc\",\"\\u0641\\u0631\\u06a9\\u0627\\u0646\\u0633 \\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\\u200c\\u06cc \\u0645\\u0631\\u06a9\\u0632\\u06cc\",\"\\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\\u200c\\u06cc \\u06af\\u0631\\u0627\\u0641\\u06cc\\u06a9\\u06cc\"],\"value\":[\" Exynos 7885 \",\" Dual-core Cortex-A73 & Hexa-core Cortex-A53 \",\" 1.77 + 1.59 \\u06af\\u06cc\\u06af\\u0627\\u0647\\u0631\\u062a\\u0632 \",\" Mali-G71 MP2 \"]},{\"title\":\"\\u062d\\u0627\\u0641\\u0638\\u0647\",\"property\":[\"\\u062d\\u0627\\u0641\\u0638\\u0647 \\u062f\\u0627\\u062e\\u0644\\u06cc\",\"\\u0645\\u0642\\u062f\\u0627\\u0631 RAM\",\"\\u067e\\u0634\\u062a\\u06cc\\u0628\\u0627\\u0646\\u06cc \\u0627\\u0632 \\u06a9\\u0627\\u0631\\u062a \\u062d\\u0627\\u0641\\u0638\\u0647 \\u062c\\u0627\\u0646\\u0628\\u06cc\"],\"value\":[\" 64 \\u06af\\u06cc\\u06af\\u0627\\u0628\\u0627\\u06cc\\u062a \",\" 4 \\u06af\\u06cc\\u06af\\u0627\\u0628\\u0627\\u06cc\\u062a \",\" microSD \"]},{\"title\":\"\\u0635\\u0641\\u062d\\u0647 \\u0646\\u0645\\u0627\\u06cc\\u0634\",\"property\":[\"\\u0635\\u0641\\u062d\\u0647 \\u0646\\u0645\\u0627\\u06cc\\u0634 \\u0631\\u0646\\u06af\\u06cc\",\"\\u0635\\u0641\\u062d\\u0647 \\u0646\\u0645\\u0627\\u06cc\\u0634 \\u0644\\u0645\\u0633\\u06cc\",\"\\u0627\\u0646\\u062f\\u0627\\u0632\\u0647\",\"\\u062a\\u0639\\u062f\\u0627\\u062f \\u0631\\u0646\\u06af\"],\"value\":[\"\\u0628\\u0644\\u0647\",\"\\u0628\\u0644\\u0647\",\" 5.9 \\u0627\\u06cc\\u0646\\u0686 \",\" 16 \\u0645\\u06cc\\u0644\\u06cc\\u0648\\u0646 \\u0631\\u0646\\u06af \"]},{\"title\":\"\\u0627\\u0631\\u062a\\u0628\\u0627\\u0637\\u0627\\u062a\",\"property\":[\"\\u0634\\u0628\\u06a9\\u0647 \\u0647\\u0627\\u06cc \\u0627\\u0631\\u062a\\u0628\\u0627\\u0637\\u06cc\",\"\\u062f\\u0631\\u06af\\u0627\\u0647 \\u0627\\u0631\\u062a\\u0628\\u0627\\u0637\\u06cc\",\"\\u0646\\u0633\\u062e\\u0647 \\u0628\\u0644\\u0648\\u062a\\u0648\\u062b\"],\"value\":[\" 2G , 3G , 4G \",\"\\u062f\\u0627\\u0631\\u062f\",\" \\u06f4.\\u06f2 \"]},{\"title\":\"\\u062f\\u0648\\u0631\\u0628\\u06cc\\u0646\",\"property\":[\"\\u062f\\u0648\\u0631\\u0628\\u06cc\\u0646\",\"\\u0631\\u0632\\u0648\\u0644\\u0648\\u0634\\u0646 \\u0639\\u06a9\\u0633\",\"\\u0641\\u0644\\u0634\",\"\\u0642\\u0627\\u0628\\u0644\\u06cc\\u062a\\u200c\\u0647\\u0627\\u06cc \\u062f\\u0648\\u0631\\u0628\\u06cc\\u0646\",\"\\u062f\\u0648\\u0631\\u0628\\u06cc\\u0646 \\u0633\\u0644\\u0641\\u06cc\"],\"value\":[\"\\u0628\\u0644\\u0647\",\" 16.0 \\u0645\\u06af\\u0627\\u067e\\u06cc\\u06a9\\u0633\\u0644 \",\"LED\",\" \\u06f1\\u06f6+\\u06f5 \\u0645\\u06af\\u0627\\u067e\\u06cc\\u06a9\\u0633\\u0644 (\\u062f\\u0648 \\u06af\\u0627\\u0646\\u0647) \",\" \\u06f2\\u06f5 \\u0645\\u06af\\u0627\\u067e\\u06cc\\u06a9\\u0633\\u0644 \"]},{\"title\":\"\\u0635\\u062f\\u0627\",\"property\":[\"\\u0628\\u0644\\u0646\\u062f\\u06af\\u0648\",\"\\u062e\\u0631\\u0648\\u062c\\u06cc \\u0635\\u062f\\u0627\"],\"value\":[\"\\u062f\\u0627\\u0631\\u062f\",\" \\u062c\\u06a9 3.5 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0645\\u062a\\u0631\\u06cc \"]},{\"title\":\"\\u0627\\u0645\\u06a9\\u0627\\u0646\\u0627\\u062a \\u0646\\u0631\\u0645 \\u0627\\u0641\\u0632\\u0627\\u0631\\u06cc\",\"property\":[\"\\u0633\\u06cc\\u0633\\u062a\\u0645 \\u0639\\u0627\\u0645\\u0644\",\"\\u067e\\u0634\\u062a\\u06cc\\u0628\\u0627\\u0646\\u06cc \\u0627\\u0632 \\u0632\\u0628\\u0627\\u0646 \\u0641\\u0627\\u0631\\u0633\\u06cc\",\"\\u0645\\u0646\\u0648\\u06cc \\u0641\\u0627\\u0631\\u0633\\u06cc\",\"\\u0636\\u0628\\u0637 \\u0635\\u062f\\u0627\"],\"value\":[\"Android\",\"\\u0628\\u0644\\u0647\",\"\\u0628\\u0644\\u0647\",\"\\u0628\\u0644\\u0647\"]},{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u062f\\u06cc\\u06af\\u0631\",\"property\":[\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u0628\\u0627\\u062a\\u0631\\u06cc\"],\"value\":[\" \\u0644\\u06cc\\u062a\\u06cc\\u0648\\u0645 \\u067e\\u0644\\u06cc\\u0645\\u0631\\u06cc 3100 \\u0645\\u06cc\\u0644\\u06cc \\u0622\\u0645\\u067e\\u0631 \"]}]', 'حافظه داخلی: 64 گیگابایت,شبکه های ارتباطی: 2G 3G 4G ,مقدار RAM: 4 گیگابایت,رزولوشن عکس: 16.0 مگاپیکسل ', '5', 7, 7, '', 1, 1, 116, 1566554053),
(7, 'PRD_kkRpDali', 'کیف کلاسوری نیلکین مدل Qin مناسب برای گوشی موبایل سامسونگ Galaxy A40', 'Nillkin Qin Flip Cover For Samsung Galaxy A40', 16, 12, 'public/uploads/products/Nillkin-Qin-Flip-Cover-For-Samsung-Galaxy-A40/1.jpg', '', 17, 0, '0', 1, '[{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u0641\\u06cc\\u0632\\u06cc\\u06a9\\u06cc\",\"property\":[\"\\u0627\\u0628\\u0639\\u0627\\u062f\",\"\\u0648\\u0632\\u0646\",\"\\u0646\\u0648\\u0639\",\"\\u062c\\u0646\\u0633\",\"\\u0633\\u0627\\u062e\\u062a\\u0627\\u0631\",\"\\u0633\\u0637\\u062d \\u067e\\u0648\\u0634\\u0634\",\"\\u0642\\u0627\\u0628\\u0644\\u06cc\\u062a\\u200c\\u0647\\u0627\\u06cc \\u0648\\u06cc\\u0698\\u0647\"],\"value\":[\" 180*110*20 \\u0645\\u06cc\\u0644\\u06cc \\u0645\\u062a\\u0631 \",\" 89 \\u06af\\u0631\\u0645 \",\" \\u06a9\\u06cc\\u0641 \\u06a9\\u0644\\u0627\\u0633\\u0648\\u0631\\u06cc \",\" \\u0686\\u0631\\u0645 \\u0645\\u0635\\u0646\\u0648\\u0639\\u06cc \",\"\\u0645\\u0627\\u062a\",\" \\u0642\\u0627\\u0628 \\u067e\\u0634\\u062a\\u06cc , \\u0642\\u0627\\u0628 \\u062c\\u0644\\u0648\\u06cc\\u06cc , \\u0644\\u0628\\u0647 \\u0686\\u067e , \\u0644\\u0628\\u0647 \\u0631\\u0627\\u0633\\u062a \",\"\\u0645\\u0642\\u0627\\u0648\\u0645 \\u062f\\u0631 \\u0628\\u0631\\u0627\\u0628\\u0631 \\u0636\\u0631\\u0628\\u0647 , \\u062f\\u0627\\u0631\\u0627\\u06cc \\u0627\\u0633\\u062a\\u0627\\u0646\\u062f\\u0627\\u0631\\u062f\\u0647\\u0627\\u06cc \\u0646\\u0638\\u0627\\u0645\\u06cc \\u0645\\u0642\\u0627\\u0648\\u0645\\u062a \\u062f\\u0631 \\u0628\\u0631\\u0627\\u0628\\u0631 \\u0633\\u0642\\u0648\\u0637 , \\u062f\\u0633\\u062a\\u0631\\u0633\\u06cc \\u0622\\u0633\\u0627\\u0646 \\u0628\\u0647 \\u062f\\u0631\\u06af\\u0627\\u0647 \\u0647\\u0627 \"]}]', '', '5,6', 7, 7, '', 1, 1, 88, 1566555809),
(8, 'PRD_fhP7YO4T', 'شارژر همراه شیاومی مدل PLM09ZM ظرفیت 10000 میلی آمپر ساعت', 'Xiaomi PLM09ZM 10000mAh Power Bank', 17, 17, 'public/uploads/products/Xiaomi-PLM09ZM-10000mAh-Power-Bank/2237092.jpg', 'در مدلPLM09ZM از سری شارژرهای همراه شیائومی یک باتری لیتیوم پلیمری با ظرفیت 10000 میلی‌آمپر ساعت به‌کار رفته است. این محصول 243 گرم وزن دارد و به شکل مستطیل طراحی و تولید شده است. طول آن 147 میلی‌متر، عرض این پاور بانک 71.2 میلی‌متر و ارتفاعش 14.2 میلی‌متر است. این محصول وزن کم و ابعاد مناسبی دارد و شما می‌توانید آن را به‌طور دائم درون کیف یا جیب خود قرار دهید. براین اساس دیگر هیچ استرسی بابت تمام شدن شارژ باتری گوشی موبایل، تبلت و دیگر دستگاه‌های از این قبیل در شرایط حساس نخواهید داشت و همیشه باتری دستگاه‌تان شارژ دارد. در زمان استفاده از شارژرهای فندکی، دیواری، پاور بانک و دیگر شارژرهای از این قبیل باید به شدت جریان خروجی و دستگاهی که قصد دارید باتری آن را شارژ کنید، توجه داشته باشید. به‌طور مثال برای شارژ باتری انواع گوشی‌های موبایل باید از یک شارژر با حداقل شدت جریان خروجی 1 آمپر استفاده کرد. این در حالی است که برای شارژ باتری تبلت‌ها باید یک شارژر با حداقل شدت جریان خروجی 2 آمپر داشته باشید. براین اساس شما با استفاده از این محصول می‌توانید باتری انواع گوشی‌های موبایل، تبلت‌ها، دوربین‌های دیجیتال و دیگر لوازم الکترونیکی سازگار با این تکنولوژی را شارژ کنید. روی بدنه‌ی این مدل از شارژرهای همراه شیائومی نشانگر LED وجود دارد که وضعیت باتری را نمایش می‌دهد. همچنین در بسته‌بندی آن یک کابل USB قرار دارد که شما با استفاده از آن می‌توانید باتری به‌کار رفته در پاور بانک را شارژ کنید.', 10, 0, '5', 1, '[{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u06a9\\u0644\\u06cc\",\"property\":[\"\\u0627\\u0628\\u0639\\u0627\\u062f\",\"\\u06a9\\u0644\\u0627\\u0633 \\u0648\\u0632\\u0646\\u06cc\",\"\\u0648\\u0632\\u0646\",\"\\u0646\\u0648\\u0639 \\u0628\\u0627\\u062a\\u0631\\u06cc\",\"\\u062a\\u0639\\u062f\\u0627\\u062f \\u062f\\u0631\\u06af\\u0627\\u0647 \\u062e\\u0631\\u0648\\u062c\\u06cc\",\"\\u0645\\u062d\\u062f\\u0648\\u062f\\u0647 \\u0638\\u0631\\u0641\\u06cc\\u062a\",\"\\u0638\\u0631\\u0641\\u06cc\\u062a \\u0627\\u0633\\u0645\\u06cc\",\"\\u0646\\u062d\\u0648\\u0647 \\u0646\\u0645\\u0627\\u06cc\\u0634 \\u0645\\u06cc\\u0632\\u0627\\u0646 \\u0634\\u0627\\u0631\\u0698 \\u0628\\u0627\\u062a\\u0631\\u06cc\",\"\\u0633\\u0627\\u0632\\u06af\\u0627\\u0631 \\u0628\\u0627\",\"\\u0642\\u0627\\u0628\\u0644\\u06cc\\u062a\\u200c\\u0647\\u0627\\u06cc \\u0648\\u06cc\\u0698\\u0647\",\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u0641\\u0646\\u06cc\"],\"value\":[\" 14.2 \\u00d7 71.2 \\u00d7 147 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0645\\u062a\\u0631 \",\" \\u0645\\u0639\\u0645\\u0648\\u0644\\u06cc|200 \\u062a\\u0627 400 \\u06af\\u0631\\u0645 \",\" 243 \\u06af\\u0631\\u0645 \",\" \\u0644\\u06cc\\u062a\\u06cc\\u0648\\u0645-\\u067e\\u0644\\u06cc\\u0645\\u0631\\u06cc \",\" 2 \\u0639\\u062f\\u062f \",\" 5 \\u062a\\u0627 10 \\u0647\\u0632\\u0627\\u0631 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0622\\u0645\\u067e\\u0631\\u200c\\u0633\\u0627\\u0639\\u062a \",\" 10000 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0622\\u0645\\u067e\\u0631 \\u0633\\u0627\\u0639\\u062a \",\" \\u0646\\u0634\\u0627\\u0646\\u06af\\u0631 LED \",\" \\u0627\\u0646\\u0648\\u0627\\u0639 \\u06af\\u0648\\u0634\\u06cc \\u0647\\u0627\\u06cc \\u0645\\u0648\\u0628\\u0627\\u06cc\\u0644\\u060c \\u062a\\u0628\\u0644\\u062a\\u060c \\u062f\\u0648\\u0631\\u0628\\u06cc\\u0646 \\u062f\\u06cc\\u062c\\u06cc\\u062a\\u0627\\u0644\\u060c \\u0645\\u0648\\u0632\\u06cc\\u06a9 \\u067e\\u0644\\u06cc\\u0631 \\u0648 \\u062f\\u06cc\\u06af\\u0631 \\u062f\\u0633\\u062a\\u06af\\u0627\\u0647 \\u0647\\u0627\\u06cc \\u0627\\u0632 \\u0627\\u06cc\\u0646 \\u0642\\u0628\\u06cc\\u0644 \",\"\\u0634\\u0627\\u0631\\u0698 \\u0634\\u062f\\u0646 \\u0633\\u0631\\u06cc\\u0639 \\u067e\\u0627\\u0648\\u0631\\u0628\\u0627\\u0646\\u06a9 , \\u0627\\u0645\\u06a9\\u0627\\u0646 \\u0634\\u0627\\u0631\\u0698 \\u062a\\u0628\\u0644\\u062a , \\u062a\\u06a9\\u0646\\u0648\\u0644\\u0648\\u0698\\u06cc Quick Charge 2.0 , \\u0634\\u0627\\u0631\\u0698 \\u0627\\u06cc\\u0645\\u0646 (MultiProtect) , \\u0627\\u0645\\u06a9\\u0627\\u0646 \\u0634\\u0627\\u0631\\u0698 \\u06a9\\u0631\\u062f\\u0646 \\u0633\\u0631\\u06cc\\u0639\\u200c\\u062a\\u0631 \\u0645\\u0648\\u0628\\u0627\\u06cc\\u0644\",\" \\u0633\\u0627\\u062e\\u062a\\u0647 \\u0634\\u062f\\u0647 \\u0627\\u0632 \\u0622\\u0644\\u06cc\\u0627\\u0698 \\u0622\\u0644\\u0648\\u0645\\u06cc\\u0646\\u06cc\\u0648\\u0645 , \\u062f\\u0627\\u0631\\u0627\\u06cc \\u067e\\u0648\\u0631\\u062a microUSB \\u0628\\u0631\\u0627\\u06cc \\u0634\\u0627\\u0631\\u0698 \\u067e\\u0627\\u0648\\u0631\\u0628\\u0627\\u0646\\u06a9 , \\u0648\\u0631\\u0648\\u062f\\u06cc: 5\\u0648\\u0644\\u062a\\/2 \\u0622\\u0645\\u067e\\u0631\\u060c 9 \\u0648\\u0644\\u062a\\/2 \\u0622\\u0645\\u067e\\u0631\\u060c 12 \\u0648\\u0644\\u062a\\/ 1.5 \\u0622\\u0645\\u067e\\u0631 , \\u062e\\u0631\\u0648\\u062c\\u06cc: 5.1 \\u0648\\u0644\\u062a\\/2.4 \\u0622\\u0645\\u067e\\u0631\\u060c 9 \\u0648\\u0644\\u062a\\/1.6 \\u0622\\u0645\\u067e\\u0631\\u060c 12 \\u0648\\u0644\\u062a\\u060c 1.2 \\u0622\\u0645\\u067e\\u0631 , \\u062f\\u0627\\u0631\\u0627\\u06cc \\u06a9\\u0627\\u0628\\u0644 microUSB \"]}]', '', '5', 7, 7, '', 1, 1, 5, 1566558069),
(9, 'PRD_OA5Eg1Fs', 'گوشی موبایل اپل مدل iPhone XS Max دو سیم‌ کارت ظرفیت 256 گیگابایت', 'Apple iPhone XS Max Dual SIM 256GB Mobile Phone', 18, 18, 'public/uploads/products/Apple-iPhone-XS-Max-Dual-SIM-256GB-Mobile-Phone/4560689.jpg', 'گوشی همراه «iPhone XS Max» یکی از سه محصول جدید شرکت «اپل» است که همراه با دو مدل دیگر این شرکت یعنی «iPhone XR»، «iPhone XS» در ماه سپتامبر سال 2018 به بازار عرضه شد. این گوشی در ویژگی‌ها شباهت زیادی به گوشی iPhone XS دارد اما در اندازه قدری بزرگ است. این گوشی با نمایشگری با پنل ­ Super AMOLED ساخته‌شده است. این نمایشگر رزولوشن بسیار بالایی دارد؛ به‌طوری‌که در اندازه­‌ی 6.5اینچی‌اش، حدود 458 پیکسل را در هر اینچ جا داده است که میزان بسیار مطلوبی است. همچنین این نمایشگر تقریباً تمام قاب جلویی گوشی را پر کرده است که نشان از کیفیت ساخت بالای این گوشی دارد. قاب پشتی هم از شیشه ساخته‌ شده تا هم گوشی مشکل آنتن­‌دهی نداشته باشد و هم امکان شارژ بی‌­سیم باتری هم در این گوشی وجود داشته باشد. البته قابی فلزی این بدنه شیشه‌ای را در خود جای داده است. این بدنه­‌ی زیبا در مقابل خط‌‌وخش مقاومت زیادی دارد؛ پس خیالتان از این بابت که آب و گردوغبار به‌‌راحتی روی آیفون XS Max تأثیر نمی­‌گذارد، راحت باشد. علاوه‌براین لکه و چربی هم روی این صفحه‌نمایش باکیفیت تأثیر چندانی ندارند. ویژگی دیگر مجهز بودن آیفون XS Max به حسگر تشخیص چهره است. این فناوری چهره­‌ی شما را با استفاده از فناوری جدید شناسایی می­‌کند؛ حتی اگر تغییری در چهره­‌ی شما ایجاد شود، آن را شناخته و تنها با صورت شما قفل گوشی را باز می­‌کند. به لطف این فناوری جدید دیگر خبری از حسگر اثرانگشت در آیفون XS Max نیست؛ پیش‌ازاین شاهد استفاده از این فناوری در گوشی آیفون 10 بودیم. دو دوربین با سنسورهای­ 12مگاپیکسلی ویژگی دیگر این گوشی است که عکس‌هایی با کیفیت کاملاً رضایت‌بخش را به کاربر هدیه می‌دهد. قابلیت اتصال به شبکه­‌های 4G، بلوتوث نسخه­‌ی 5، نسخه­‌ی 12 از iOS  دیگر ویژگی­‌های این گوشی هستند. از نظر سخت­‌افزاری هم این گوشی از تراشه­‌ی جدید A12 بهره می‌­برد که در آن پردازنده‌­ای شش‌هسته‌ا‌ی و قدرتمند قرارگرفته تا بتواند علاوه‌بر کارهای معمول، از قابلیت­‌های جدید واقعیت مجازی که اپل این روزها روی آن تمرکز خاصی دارد، پشتیبانی کند. این گوشی آیفون با داشتن استاندارد IP68 در برابر آب مقاوم است و می‌تواند برای 30 دقیقه در عمق 2 متری دوام بیاورد. اپل این محصول را با ویژگی‌های خاص به‌عنوان یکی از پرچم‌داران این شرکت به بازار عرضه کرده است.', 10, 0, '0', 1, '[{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u06a9\\u0644\\u06cc\",\"property\":[\"\\u0627\\u0628\\u0639\\u0627\\u062f\",\"\\u062a\\u0648\\u0636\\u06cc\\u062d\\u0627\\u062a \\u0633\\u06cc\\u0645 \\u06a9\\u0627\\u0631\\u062a\",\"\\u0648\\u0632\\u0646\",\"\\u0633\\u0627\\u062e\\u062a\\u0627\\u0631 \\u0628\\u062f\\u0646\\u0647\",\"\\u0642\\u0627\\u0628\\u0644\\u06cc\\u062a\\u200c\\u0647\\u0627\\u06cc \\u0648\\u06cc\\u0698\\u0647\"],\"value\":[\" 7.7 \\u00d7 77.4 \\u00d7 157.5 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0645\\u062a\\u0631 \",\" \\u0633\\u0627\\u06cc\\u0632 \\u0646\\u0627\\u0646\\u0648 (8.8 \\u00d7 12.3 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0645\\u062a\\u0631) \",\" 208 \\u06af\\u0631\\u0645 \",\" \\u0641\\u0644\\u0632 \\u0648 \\u0634\\u06cc\\u0634\\u0647 , \\u0634\\u06cc\\u0634\\u0647 \\u0645\\u0642\\u0627\\u0648\\u0645 \\u062f\\u0631 \\u0628\\u0631\\u0627\\u0628\\u0631 \\u062e\\u0637 \\u0648 \\u062e\\u0634 , \\u067e\\u0648\\u0634\\u0634 Oleophobic , \\u062f\\u0627\\u0631\\u0627\\u06cc \\u0627\\u0633\\u062a\\u0627\\u0646\\u062f\\u0627\\u0631\\u062f IP68 \\u0628\\u0631\\u0627\\u06cc \\u0645\\u0642\\u0627\\u0648\\u0645\\u062a \\u062f\\u0631 \\u0628\\u0631\\u0627\\u0628\\u0631 \\u0622\\u0628 (\\u0628\\u0647 \\u0645\\u062f\\u062a 30 \\u062f\\u0642\\u06cc\\u0642\\u0647 \\u062f\\u0631 \\u0639\\u0645\\u0642 2 \\u0645\\u062a\\u0631) \",\" \\u0645\\u0642\\u0627\\u0648\\u0645 \\u062f\\u0631 \\u0628\\u0631\\u0627\\u0628\\u0631 \\u0622\\u0628 , \\u062f\\u0627\\u0631\\u0627\\u06cc \\u0628\\u062f\\u0646\\u0647 \\u0645\\u0642\\u0627\\u0648\\u0645 , \\u0645\\u0646\\u0627\\u0633\\u0628 \\u0639\\u06a9\\u0627\\u0633\\u06cc , \\u0641\\u0628\\u0644\\u062a , \\u0645\\u0646\\u0627\\u0633\\u0628 \\u0628\\u0627\\u0632\\u06cc , \\u0645\\u062c\\u0647\\u0632 \\u0628\\u0647 \\u062d\\u0633\\u200c\\u06af\\u0631 \\u062a\\u0634\\u062e\\u06cc\\u0635 \\u0686\\u0647\\u0631\\u0647 \"]},{\"title\":\"\\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\",\"property\":[\"\\u062a\\u0631\\u0627\\u0634\\u0647\",\"\\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\\u200c\\u06cc \\u0645\\u0631\\u06a9\\u0632\\u06cc\",\"\\u0646\\u0648\\u0639 \\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\",\"\\u0641\\u0631\\u06a9\\u0627\\u0646\\u0633 \\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\\u200c\\u06cc \\u0645\\u0631\\u06a9\\u0632\\u06cc\",\"\\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\\u200c\\u06cc \\u06af\\u0631\\u0627\\u0641\\u06cc\\u06a9\\u06cc\"],\"value\":[\" Apple A12 Bionic Chipset \",\" Hexa-core (Vortex + Tempest) CPU \",\"\\u0646\\u0648\\u0639 \\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\",\"\\u0641\\u0631\\u06a9\\u0627\\u0646\\u0633 \\u067e\\u0631\\u062f\\u0627\\u0632\\u0646\\u062f\\u0647\\u200c\\u06cc \\u0645\\u0631\\u06a9\\u0632\\u06cc\",\" Apple (4-core graphics) GPU \"]}]', '', '4', 7, 7, '', 1, 1, 26, 1566575323),
(10, 'PRD_BNKjUi0m', 'گوشی موبایل اپل مدل Apple iPhone XS تک سیم کارت ظرفیت 64 گیگابایت', 'Apple iPhone XS Single SIM 64GB Mobile Phone', 18, 18, 'public/uploads/products/Apple-iPhone-XS-Single-SIM-64GB-Mobile-Phone/4561039.jpg', 'گوشی همراه «iPhone XS» از سری تولیدات جدید شرکت «اپل» است که در ماه سپتامبر سال 2018 همراه با دو مدل دیگر این شرکت یعنی «iPhone XR»، «iPhoane XS Max» رونمایی شد. این گوشی در ویژگی‌ها شباهت زیادی به گوشی iPhone XS Max دارد اما در ابعاد کمی کوچکتر است. این گوشی با نمایشگری با پنل ­ Super AMOLED ساخته‌شده است. این نمایشگر رزولوشن بسیار بالایی دارد؛ به‌طوری‌که در اندازه­‌ی 5.8اینچی‌اش، حدود 458 پیکسل را در هر اینچ جا داده است که میزان بسیار مطلوبی است. همچنین این نمایشگر تقریباً تمام قاب جلویی گوشی را پر کرده است که نشان از کیفیت ساخت بالای این گوشی دارد. قاب پشتی هم از شیشه ساخته‌ شده تا هم گوشی مشکل آنتن­‌دهی نداشته باشد و هم امکان شارژ بی‌­سیم باتری هم در این گوشی وجود داشته باشد. البته قابی فلزی این بدنه شیشه‌ای را در خود جای داده است. این بدنه­‌ی زیبا در مقابل خط‌‌وخش مقاومت زیادی دارد؛ پس خیالتان از این بابت که آب و گردوغبار به‌‌راحتی روی آیفون XS  تأثیر نمی­‌گذارد، راحت باشد. علاوه‌براین لکه و چربی هم روی این صفحه‌نمایش باکیفیت تأثیر چندانی ندارند. ویژگی دیگر مجهز بودن آیفون XS  به حسگر تشخیص چهره است. این فناوری چهره­‌ی شما را با استفاده از فناوری جدید شناسایی می­‌کند؛ حتی اگر تغییری در چهره­‌ی شما ایجاد شود، آن را شناخته و تنها با صورت شما قفل گوشی را باز می­‌کند. به لطف این فناوری جدید دیگر خبری از حسگر اثرانگشت در آیفون XS  نیست؛ پیش‌ازاین شاهد استفاده از این فناوری در گوشی آیفون 10 بودیم. دو دوربین با سنسورهای­ 12مگاپیکسلی ویژگی دیگر این گوشی است که عکس‌هایی با کیفیت کاملاً رضایت‌بخش را به کاربر هدیه می‌دهد. قابلیت اتصال به شبکه­‌های 4G، بلوتوث نسخه­‌ی 5، نسخه­‌ی 12 از iOS  دیگر ویژگی­‌های این گوشی هستند. از نظر سخت­‌افزاری هم این گوشی از تراشه­‌ی جدید A12 بهره می‌­برد که در آن پردازنده‌­ای شش‌هسته‌ا‌ی و قدرتمند قرارگرفته تا بتواند علاوه‌بر کارهای معمول، از قابلیت­‌های جدید واقعیت مجازی که اپل این روزها روی آن تمرکز خاصی دارد، پشتیبانی کند. این گوشی آیفون با داشتن استاندارد IP68 در برابر آب مقاوم است و می‌تواند برای 30 دقیقه در عمق 2 متری دوام بیاورد. اپل این محصول را با ویژگی‌های خاص به‌عنوان یکی از پرچم‌داران این شرکت به بازار عرضه کرده است.', 10, 0, '17', 2, '[{\"title\":\"\\u0645\\u0634\\u062e\\u0635\\u0627\\u062a \\u06a9\\u0644\\u06cc\",\"property\":[\"\\u0627\\u0628\\u0639\\u0627\\u062f\",\"\\u062a\\u0648\\u0636\\u06cc\\u062d\\u0627\\u062a \\u0633\\u06cc\\u0645 \\u06a9\\u0627\\u0631\\u062a\",\"\\u0648\\u0632\\u0646\"],\"value\":[\" 7.7 \\u00d7 70.9 \\u00d7 143.6 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0645\\u062a\\u0631 \",\" \\u0633\\u0627\\u06cc\\u0632 \\u0646\\u0627\\u0646\\u0648 (8.8 \\u00d7 12.3 \\u0645\\u06cc\\u0644\\u06cc\\u200c\\u0645\\u062a\\u0631) , \\u067e\\u0634\\u062a\\u06cc\\u0628\\u0627\\u0646\\u06cc \\u0627\\u0632 eSIM \",\" 177 \\u06af\\u0631\\u0645 \"]}]', 'حافظه داخلی: 64 گیگابایت ,شبکه های ارتباطی: 2G 3G 4G ,حس‌گرها: قطب‌نما (Compass) شتاب‌سنج (Accelerometer) مجاورت (Proximity) فشارسنج (Barometer) ژیروسکوپ (Gyro) تشخیص چهره بیومتریک (Face ID)', '', 7, 7, '', 1, 1, 59, 1566575686);

-- --------------------------------------------------------

--
-- Stand-in structure for view `products_advanced`
-- (See below for the actual view)
--
CREATE TABLE `products_advanced` (
`id` int(10) unsigned
,`product_code` varchar(20)
,`product_title` varchar(400)
,`latin_title` varchar(400)
,`brand_id` int(10) unsigned
,`category_id` int(10) unsigned
,`image` text
,`body` text
,`stock_count` int(10) unsigned
,`sold_count` int(10) unsigned
,`discount` varchar(10)
,`discount_unit` tinyint(1) unsigned
,`property` text
,`property_abstract` text
,`related` text
,`user_created_id` int(10) unsigned
,`user_updated_id` int(10) unsigned
,`keywords` text
,`publish` tinyint(1) unsigned
,`available` tinyint(1) unsigned
,`view` int(11) unsigned
,`created_on` int(11) unsigned
,`brand_code` varchar(20)
,`brand_name` varchar(100)
,`brand_keywords` text
,`category_code` varchar(20)
,`category_name` varchar(100)
,`parent_id` int(10) unsigned
,`all_parents` text
,`category_keywords` text
,`price` bigint(20) unsigned
,`color_id` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `products_advanced_2`
-- (See below for the actual view)
--
CREATE TABLE `products_advanced_2` (
`id` int(10) unsigned
,`product_code` varchar(20)
,`product_title` varchar(400)
,`latin_title` varchar(400)
,`brand_id` int(10) unsigned
,`category_id` int(10) unsigned
,`image` text
,`body` text
,`stock_count` int(10) unsigned
,`sold_count` int(10) unsigned
,`discount` varchar(10)
,`discount_unit` tinyint(1) unsigned
,`property` text
,`property_abstract` text
,`related` text
,`user_created_id` int(10) unsigned
,`user_updated_id` int(10) unsigned
,`keywords` text
,`publish` tinyint(1) unsigned
,`available` tinyint(1) unsigned
,`view` int(11) unsigned
,`created_on` int(11) unsigned
,`brand_code` varchar(20)
,`brand_name` varchar(100)
,`brand_keywords` text
,`category_code` varchar(20)
,`category_name` varchar(100)
,`parent_id` int(10) unsigned
,`all_parents` text
,`category_keywords` text
,`price` bigint(20) unsigned
,`color_id` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Table structure for table `products_colors`
--

CREATE TABLE `products_colors` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `color_id` int(10) UNSIGNED NOT NULL,
  `price` bigint(20) UNSIGNED NOT NULL,
  `count` int(10) UNSIGNED NOT NULL,
  `sold_count` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_colors`
--

INSERT INTO `products_colors` (`id`, `product_id`, `color_id`, `price`, `count`, `sold_count`) VALUES
(39, 5, 1, 10900, 5, 0),
(45, 8, 4, 174000, 5, 0),
(46, 8, 12, 183000, 5, 0),
(47, 6, 3, 3399000, 5, 0),
(48, 6, 5, 3399000, 5, 0),
(49, 9, 13, 19000000, 5, 0),
(50, 9, 14, 19000000, 5, 0),
(57, 10, 4, 17990000, 5, 0),
(58, 10, 13, 17990000, 5, 0),
(59, 7, 9, 99000, 5, 0),
(60, 7, 10, 101100, 5, 0),
(61, 7, 11, 101600, 5, 0),
(62, 7, 3, 103000, 2, 0),
(77, 4, 1, 26000, 15, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products_festivals`
--

CREATE TABLE `products_festivals` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `festival_id` int(10) UNSIGNED NOT NULL,
  `discount` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_festivals`
--

INSERT INTO `products_festivals` (`id`, `product_id`, `festival_id`, `discount`) VALUES
(3, 4, 2, '5'),
(5, 8, 2, '12'),
(10, 9, 3, '2'),
(11, 7, 2, '3'),
(20, 6, 3, '8'),
(21, 6, 2, '12');

-- --------------------------------------------------------

--
-- Table structure for table `products_guarantee`
--

CREATE TABLE `products_guarantee` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `guarantee_title` varchar(100) NOT NULL,
  `guarantee_price` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_guarantee`
--

INSERT INTO `products_guarantee` (`id`, `product_id`, `guarantee_title`, `guarantee_price`) VALUES
(3, 4, 'سرویس ویژه دیجی کالا: ۷ روز تضمین بازگشت کالا', '0'),
(4, 5, 'گارانتی اصالت و سلامت فیزیکی کالا', '0'),
(5, 6, 'گارانتی ۱۸ ماهه آرمان همراه ارتباطات', '110000'),
(6, 7, 'گارانتی اصالت و سلامت فیزیکی کالا', '0'),
(7, 8, 'گارانتی اصالت و سلامت فیزیکی کالا', '0'),
(8, 9, 'گارانتی ۱۸ ماهه توسعه اقتصاد توان یاسین', '300000'),
(9, 10, 'گارانتی ۱۸ ماهه لوکا + بیمه ۱۲ ماهه بدنه لوکا(شکستگی و آبخوردگی)', '0');

-- --------------------------------------------------------

--
-- Table structure for table `products_image`
--

CREATE TABLE `products_image` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_image`
--

INSERT INTO `products_image` (`id`, `product_id`, `image`) VALUES
(42, 5, 'public/uploads/products/Trustector-GLS-Screen-Protector-For-Samsung-Galaxy-A40/111654504.jpg'),
(43, 5, 'public/uploads/products/Trustector-GLS-Screen-Protector-For-Samsung-Galaxy-A40/111654519.jpg'),
(44, 5, 'public/uploads/products/Trustector-GLS-Screen-Protector-For-Samsung-Galaxy-A40/111654528.jpg'),
(45, 5, 'public/uploads/products/Trustector-GLS-Screen-Protector-For-Samsung-Galaxy-A40/111654539.jpg'),
(58, 8, 'public/uploads/products/Xiaomi-PLM09ZM-10000mAh-Power-Bank/2.jpg'),
(59, 8, 'public/uploads/products/Xiaomi-PLM09ZM-10000mAh-Power-Bank/3.jpg'),
(60, 8, 'public/uploads/products/Xiaomi-PLM09ZM-10000mAh-Power-Bank/4.jpg'),
(61, 6, 'public/uploads/products/Samsung-Galaxy-A40-SM-A405FNDS-Dual-Sim-64GB-Mobile-Phone/2.jpg'),
(62, 6, 'public/uploads/products/Samsung-Galaxy-A40-SM-A405FNDS-Dual-Sim-64GB-Mobile-Phone/112344505.jpg'),
(63, 6, 'public/uploads/products/Samsung-Galaxy-A40-SM-A405FNDS-Dual-Sim-64GB-Mobile-Phone/3.jpg'),
(64, 9, 'public/uploads/products/Apple-iPhone-XS-Max-Dual-SIM-256GB-Mobile-Phone/2.jpg'),
(65, 9, 'public/uploads/products/Apple-iPhone-XS-Max-Dual-SIM-256GB-Mobile-Phone/3.jpg'),
(66, 9, 'public/uploads/products/Apple-iPhone-XS-Max-Dual-SIM-256GB-Mobile-Phone/4.jpg'),
(67, 9, 'public/uploads/products/Apple-iPhone-XS-Max-Dual-SIM-256GB-Mobile-Phone/5.jpg'),
(68, 9, 'public/uploads/products/Apple-iPhone-XS-Max-Dual-SIM-256GB-Mobile-Phone/6.jpg'),
(69, 9, 'public/uploads/products/Apple-iPhone-XS-Max-Dual-SIM-256GB-Mobile-Phone/7.jpg'),
(70, 9, 'public/uploads/products/Apple-iPhone-XS-Max-Dual-SIM-256GB-Mobile-Phone/9.jpg'),
(71, 9, 'public/uploads/products/Apple-iPhone-XS-Max-Dual-SIM-256GB-Mobile-Phone/1.jpg'),
(75, 10, 'public/uploads/products/Apple-iPhone-XS-Single-SIM-64GB-Mobile-Phone/2.jpg'),
(76, 7, 'public/uploads/products/Nillkin-Qin-Flip-Cover-For-Samsung-Galaxy-A40/2.jpg'),
(77, 7, 'public/uploads/products/Nillkin-Qin-Flip-Cover-For-Samsung-Galaxy-A40/3.jpg'),
(78, 7, 'public/uploads/products/Nillkin-Qin-Flip-Cover-For-Samsung-Galaxy-A40/4.jpg'),
(79, 7, 'public/uploads/products/Nillkin-Qin-Flip-Cover-For-Samsung-Galaxy-A40/5.jpg'),
(80, 7, 'public/uploads/products/Nillkin-Qin-Flip-Cover-For-Samsung-Galaxy-A40/6.jpg'),
(81, 7, 'public/uploads/products/Nillkin-Qin-Flip-Cover-For-Samsung-Galaxy-A40/7.jpg'),
(82, 7, 'public/uploads/products/Nillkin-Qin-Flip-Cover-For-Samsung-Galaxy-A40/8.jpg'),
(83, 7, 'public/uploads/products/Nillkin-Qin-Flip-Cover-For-Samsung-Galaxy-A40/9.jpg'),
(126, 4, 'public/uploads/products/HandsFree-Bluetooth-I7/2.jpg'),
(127, 4, 'public/uploads/products/HandsFree-Bluetooth-I7/3.jpg'),
(128, 4, 'public/uploads/products/HandsFree-Bluetooth-I7/4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(10) UNSIGNED NOT NULL,
  `property_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `property_name`) VALUES
(1, 'ابعاد'),
(2, 'وزن'),
(3, 'تعداد سیم کارت'),
(4, 'تراشه'),
(5, 'نوع پردازنده'),
(6, 'حافظه داخلی'),
(7, 'مقدار RAM'),
(8, 'اندازه'),
(9, 'رزولوشن'),
(10, 'تعداد رنگ'),
(11, 'بلوتوث'),
(12, 'شبکه های ارتباطی'),
(13, 'رزولوشن عکس'),
(14, 'فیلمبرداری'),
(15, 'دوربین سلفی'),
(16, 'قابلیت پخش موسیقی'),
(17, 'قابلیت کنترل صدا و موزیک'),
(18, 'راهنمای صوتی'),
(19, 'امپدانس'),
(20, 'مدت زمان نگهداری شارژ در حالت استندبای'),
(21, 'مشخصات دیگر'),
(22, 'نوع'),
(23, 'جنس'),
(24, 'ساختار'),
(25, 'سطح پوشش'),
(26, 'قابلیت‌های ویژه'),
(27, 'کلاس وزنی'),
(28, 'نوع باتری'),
(29, 'تعداد درگاه خروجی'),
(30, 'محدوده ظرفیت'),
(31, 'ظرفیت اسمی'),
(32, 'نحوه نمایش میزان شارژ باتری'),
(33, 'سازگار با'),
(34, 'قابلیت های ویژه'),
(35, 'مشخصات فنی'),
(36, 'قابلیت نصب آسان'),
(37, 'مقاوم در برابر ضربه'),
(38, 'جلوگیری از انعکاس نور'),
(39, 'جلوگیری از ایجاد خط و خش'),
(40, 'ضخامت'),
(41, 'توضیحات سیم کارت'),
(42, 'پردازنده‌ی مرکزی'),
(43, 'فرکانس پردازنده‌ی مرکزی'),
(44, 'پردازنده‌ی گرافیکی'),
(45, 'پشتیبانی از کارت حافظه جانبی'),
(46, 'صفحه نمایش رنگی'),
(47, 'صفحه نمایش لمسی'),
(48, 'درگاه ارتباطی'),
(49, 'نسخه بلوتوث'),
(50, 'دوربین'),
(51, 'فلش'),
(52, 'قابلیت‌های دوربین'),
(53, 'بلندگو'),
(54, 'خروجی صدا'),
(55, 'سیستم عامل'),
(56, 'پشتیبانی از زبان فارسی'),
(57, 'منوی فارسی'),
(58, 'ضبط صدا'),
(59, 'مشخصات باتری'),
(60, 'ساختار بدنه'),
(61, 'بازه‌ی اندازه صفحه نمایش'),
(62, 'تراکم پیکسلی'),
(63, 'شبکه 4G'),
(64, 'شبکه 2G'),
(65, 'شبکه 3G'),
(66, 'فن‌آوری‌های ارتباطی'),
(67, 'Wi-Fi'),
(68, 'فن‌آوری مکان‌یابی'),
(69, 'توضیحات تکمیلی صدا');

-- --------------------------------------------------------

--
-- Table structure for table `return_order`
--

CREATE TABLE `return_order` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_code` varchar(20) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `request_time` int(11) NOT NULL
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
(4, 'user', 'کاربر عادی'),
(5, 'guest', 'کاربر مهمان');

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
(1, 1, 2, 2),
(2, 1, 1, 2),
(3, 1, 2, 4),
(4, 1, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `send_status`
--

CREATE TABLE `send_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `badge` varchar(100) NOT NULL,
  `priority` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `send_status`
--

INSERT INTO `send_status` (`id`, `name`, `badge`, `priority`) VALUES
(1, 'در صف بررسی', 'bg-slate', 1),
(3, 'خروج از انبار', 'label-info', 4),
(4, 'تحویل به پست', 'bg-purple', 5),
(5, 'تایید نشده', 'label-danger', 2),
(6, 'تحویل به مشتری', 'label-primary', 6),
(7, 'آماده سازی سفارش', 'label-success', 3),
(8, 'لغو شده', 'label-danger', 7);

-- --------------------------------------------------------

--
-- Table structure for table `sent_sms`
--

CREATE TABLE `sent_sms` (
  `id` int(10) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `msg` text NOT NULL,
  `sent_date` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shippings`
--

CREATE TABLE `shippings` (
  `id` int(10) UNSIGNED NOT NULL,
  `shipping_code` varchar(20) NOT NULL,
  `shipping_title` varchar(100) NOT NULL,
  `shipping_price` varchar(20) NOT NULL,
  `max_price` varchar(20) DEFAULT NULL,
  `min_days` int(3) UNSIGNED NOT NULL DEFAULT '0',
  `max_days` int(3) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shippings`
--

INSERT INTO `shippings` (`id`, `shipping_code`, `shipping_title`, `shipping_price`, `max_price`, `min_days`, `max_days`, `status`) VALUES
(3, 'SHP_IrWcBt', 'پست سفارشی', '18000', '200000', 1, 3, 1),
(5, 'SHP_UFab5zv0', 'پست پیشتاز', '۲۲۷۰۰', '۲۵۰۰۰۰', 0, 0, 1),
(6, 'SHP_1rJX0Ro4', 'تیپاکس', '۱۱۲۴۰۰', '۴۳۰۰۰۰', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(10) UNSIGNED NOT NULL,
  `image` text NOT NULL,
  `link` text NOT NULL,
  `priority` tinyint(2) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `image`, `link`, `priority`, `status`) VALUES
(1, 'public/uploads/slider/22f48d8e-6a8f-431c-985d-76ab0e1e59405_21_1_1.jpg', 'http://www.digidigi.com', 1, 1),
(2, 'public/uploads/slider/a264d696-9c12-4dd9-bdc1-12c13a3632b329_21_1_1.jpg', 'www.digidigi.com', 2, 1),
(3, 'public/uploads/slider/c0a50594-df40-412b-84f8-c7d6872fb83620_21_1_1.jpg', 'www.digidigi.com', 3, 1),
(4, 'public/uploads/slider/d1844e92-e5a9-4aef-8ea7-49be936422ca6_21_1_1.jpg', 'www.digidigi.com', 4, 1);

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
(1, 'شرایط استفاده', '<h4>توجه داشته باشید کلیه اصول و رویه&rlm;&zwnj;های دیجی&zwnj;کالا منطبق با قوانین جمهوری اسلامی ایران، قانون تجارت الکترونیک و قانون حمایت از حقوق مصرف کننده است و متعاقبا کاربر نیز موظف به رعایت قوانین مرتبط با کاربر است. در صورتی که در قوانین مندرج، رویه&rlm;&zwnj;ها و سرویس&rlm;&zwnj;های دیجی&zwnj;کالا تغییراتی در آینده ایجاد شود، در همین صفحه منتشر و به روز رسانی می شود و شما توافق می&rlm;&zwnj;کنید که استفاده مستمر شما از سایت به معنی پذیرش هرگونه تغییر است.</h4>\n<div id=\"user\">تعریف مشتری یا کاربر\n<h4>محصولات ارایه شده در وب&zwnj;سایت، یا توسط دیجی&zwnj;کالا از شرکت&zwnj;های معتبر و واردکنندگان رسمی و قانونی خریداری و عرضه می&zwnj;شود و یا برخی فروشندگان با دیجی&zwnj;کالا همکاری می&zwnj;کنند و محصولات خود را به&zwnj;واسطه این فروشگاه اینترنتی، به صورت آنلاین به فروش می&zwnj;رسانند. منظور از کالای فروشندگان، گروه دوم است و فروشنده، شخصی حقیقی یا حقوقی است که محصول خود را در وب&zwnj;سایت دیجی&zwnj;کالا عرضه کرده، به فروش می رساند. همچنین اعتبار فروشنده&zwnj;ها توسط دیجی&zwnj;کالا بررسی و مدارک مورد نیاز از ایشان اخذ می&zwnj;شود.<br /> - مسئولیت&zwnj;های مربوط به کیفیت، قیمت، محتوا، شرایط و همچنین خدمات پس از فروش محصول بر عهده فروشندگان است.<br /> - فاکتور کالاهایی که توسط فروشندگان در سایت عرضه می&zwnj;شود، در صورت درخواست خریدار توسط فروشنده ارسال می&zwnj;شود.<br /> - خریداران حداکثر تا 2 روز بعد از ثبت سفارش و قطعی شدن آن، فرصت دارند درخواست ارسال فاکتور را ثبت کنند.<br /> - سفارش&zwnj;هایی که دارای حداقل یک کالا از فروشندگان باشند و تا ساعت 16 هر روز کاری نهایی شوند ، حداقل 1 روز کاری بعد (طبق زمان تحویل اعلام&zwnj;شده در سایت) در تهران و شهرستان&zwnj;ها، ارسال خواهند شد.<br /> - 1 روز کاری فاصله زمانی تحویل برای کالاهای فروشندگان، به معنای این است که در صورت خرید کالای فروشندگان ، شروع پردازش سفارش حداکثر 1 روز پس از زمان سفارش&zwnj;گذاری در دیجی&zwnj;کالا است.<br /> - منظور از ۱ روز کاری، زمان آماده&zwnj;سازی و ارسال کالا به انبار دیجی&zwnj;کالا توسط فروشنده است، که امکان دارد این زمان بسته به نوع کالا تغییر یابد.<br /> - در صورت بروز تاخیر در پردازش سفارش شما، این موضوع از طریق پیامک به شما اطلاع رسانی شده و دیجی&shy;کالا تلاش خواهد کرد محصول/محصولات مورد نظر شما را ظرف ۲۴ ساعت تامین کند، درصورت عدم تامین کالا بعد از ۲۴ ساعت، اقلام در انتظار تامین سفارش شما بصورت سیستمی لغو خواهد شد.<br /> - در شرایط خاص مثل فروش شگفت&zwnj;انگیز، احتمال لغو شدن سفارش مربوط به محصولات فروشندگان به دلایلی مانند اتمام موجودی کالا وجود دارد و دیجی&zwnj;کالا مجاز است بدون اطلاع قبلی نسبت به توقف سفارش&zwnj;&rlm;گیری جدید، اقدام و فروش را متوقف کند.<br /> - در صورتی&zwnj;که دیجی&zwnj;کالا تخفیف به شرط خرید تا سقف خاصی را اعلام کرد (Discount Voucher)، میزان خرید از کالاهای فروشندگان محاسبه نمی&zwnj;شود و این کالاها مشمول استفاده از این تخفیف&zwnj;ها نمی&zwnj;شوند.<br /> - نحوه برگشت از فروش کالاهای فروشندگان طبق رویه دیجی&zwnj;کالا است، با این تفاوت که فروشنده مسئولیت هرگونه عدم مطابقت را به عهده می&zwnj;گیرد.<br /> - به کالاهای فروشندگان تخفیف سازمانی تعلق نمی&zwnj;گیرد.<br /> - خدمات پس &zwnj;از&zwnj; فروش دیجی&zwnj;کالا تنها درصورتی درخواست مشتری مبنی بر بازگرداندن کالای فروشندگان را می&zwnj;پذیرد که لیبل بارکد نارنجی رنگ که روی کالا نصب شده است، جدا نشده باشد.</h4>\n</div>\n<div id=\"connection\">ارتباطات الکترونیکی\n<h4>دیجی&zwnj;کالا به اطلاعات خصوصی اشخاصى که از خدمات سایت استفاده می&rlm;&zwnj;کنند، احترام گذاشته و از آن محافظت می&rlm;&zwnj;کند.<br /> دیجی&zwnj;کالا متعهد می&rlm;&zwnj;شود در حد توان از حریم شخصی شما دفاع کند و در این راستا، تکنولوژی مورد نیاز برای هرچه مطمئن&rlm;&zwnj;تر و امن&rlm;&zwnj;تر شدن استفاده شما از سایت را، توسعه دهد. در واقع با استفاده از سایت دیجی&zwnj;کالا، شما رضایت خود را از این سیاست نشان می&rlm;&zwnj;دهید.<br /> همه مطالب در دسترس از طریق هر یک از خدمات دیجی&zwnj;کالا، مانند متن، گرافیک، آرم، آیکون دکمه، تصاویر، ویدئوهای تصویری، موارد قابل دانلود و کپی، داده&zwnj;ها و کلیه محتوای تولید شده توسط دیجی&zwnj;کالا جزئی از اموال دیجی&zwnj;کالا محسوب می&rlm;&zwnj;شود و حق استفاده و نشر تمامی مطالب موجود و در دسترس در انحصار دیجی&zwnj;کالا است و هرگونه استفاده بدون کسب مجوز کتبی، حق پیگرد قانونی را برای دیجی&zwnj;کالا محفوظ می&rlm;&zwnj;دارد. علاوه بر این، اسکریپت&zwnj;ها، و اسامی خدمات قابل ارائه از طریق هر یک از خدمات ایجاد شده توسط دیجی&zwnj;کالا و علائم تجاری ثبت شده نیز در انحصار دیجی&zwnj;کالا است و هر گونه استفاده با مقاصد تجاری پیگرد قانونی دارد. کاربران مجاز به بهره&zwnj;&rlm;برداری و استفاده از لیست محصولات، مشخصات فنی، قیمت و هر گونه استفاده از مشتقات وب&rlm;&zwnj;سایت دیجی&zwnj;کالا و یا هر یک از خدمات و یا مطالب، دانلود یا کپی کردن اطلاعات با مقاصد تجاری، هر گونه استفاده از داده کاوی، روبات، یا روش&zwnj;&rlm;های مشابه مانند جمع آوری داده&zwnj;&rlm;ها و ابزارهای استخراج نیستند و کلیه این حقوق به صراحت برای دیجی&zwnj;کالا محفوظ است. در صورت استفاده از هر یک از خدمات دیجی&zwnj;کالا، کاربران مسئول حفظ محرمانه بودن حساب و رمز عبور خود هستند و تمامی مسئولیت فعالیت&zwnj;&rlm;هایی که تحت حساب کاربری و یا رمز ورود انجام می&rlm;&zwnj;پذیرد به عهده کاربران است. دیجی&zwnj;کالا محصولاتی مناسب استفاده افراد زیر 18 سال به فروش می&rlm;&zwnj;رساند و در صورتی که کاربران از سن ذکر شده جوان&zwnj;&rlm;تر هستند می&zwnj;&rlm;باید با اطلاع والدین و یا قیم قانونی، به خرید و پرداخت اقدام کنند.<br /> تنها مرجع رسمی مورد تایید ما برای ارتباط با شما، پایگاه رسمی این سایت یعنی www.digikala.com است. ما با هیچ روش دیگری جز ارسال نامه از طرف آدرس&rlm;&zwnj;های رسمی و تایید شده در سایت، با شما تماس نمی&zwnj;&rlm;گیریم. وب سایت دیجی&zwnj;کالا هیچگونه سایت اینترنتی با آدرسی غیر از www.digikala.com و همچنین، هیچ گونه وبلاگ و شناسه در برنامه&rlm;&zwnj;های گفتگوی اینترنتی همچون Yahoo Messenger یا MSN Messenger و غیره ندارد و هیچ &rlm;گاه با این روش&rlm;&zwnj;ها با شما تماس نمی&rlm;&zwnj;گیرد. کاربران جهت برقراری ارتباط، تنها می&rlm;&zwnj;توانند از آدرس&zwnj;&rlm;های ذکر شده در بخش ارتباط با ما استفاده کنند.</h4>\n</div>\n<div id=\"order\">ثبت، پردازش و ارسال سفارش\n<p>حساب مشتری مسترد می&zwnj;شود.</p>\n</div>\n<div id=\"gift\">شرایط خریداری کارت هدیه\n<h4>دیجی&zwnj;کالا همواره نهایت تلاش خود را می&rlm;&zwnj;کند تا کلیه سفارش&rlm;&zwnj;ها در نهایت صحت و بدون آسیب به دست مشتریان خود در سراسر کشور برسد. با توجه به بسته&zwnj;بندی ایمن و استاندارد همه مرسولات، تحویل به هر یک از شرکت&zwnj;&rlm;های حمل و نقل معتبر به انتخاب کاربر و اعلام بارنامه مرسوله (درج در سبد خرید و اطلاع رسانی از طریق سرویس پیام کوتاه) به این معنی است که بروز هر گونه حادثه در هنگام حمل و تحویل به عهده شرکت حمل و نقل است و دیجی&zwnj;کالا تنها در صورت تایید شرکت حمل کننده سفارش و در راستای تسهیل امور پیگیری، خسارت را جبران می&zwnj;&rlm;کند.<br /> آسیب&rlm;&zwnj;های ناشی از حمل و نقل باید در عرض 24 ساعت کاری پس از تحویل سفارش به خدمات پس از فروش دیجی&zwnj;کالا اطلاع داده شود و کالای آسیب دیده به همراه صورت جلسه رسمی شرکت حمل کننده و کلیه متعلقات و فاکتور به خدمات پس از فروش دیجی&zwnj;کالا ارسال شود.<br /> برای اطلاعات بیشتر به صفحه رویه&zwnj;های بازگرداندن کالا مراجعه کنید.</h4>\n</div>\n<div id=\"test\">سرویس مهلت تست ۷ روزه دیجی&zwnj;کالا\n<p>- اطلاعات هر محصول آرایشی بهداشتی در وب&zwnj;سایت دیجی&zwnj;کالا، صرفاً برای اطلاع&zwnj;رسانی است و جنبه مشاوره ندارد. خریدار باید قبل از استفاده از مواد آرایشی بهداشتی نسبت به کسب اطلاعات حرفه&zwnj;ای و اخذ مشاوره از متخصص مربوط اقدام کند. همچنین،نظراتی که کاربران در خصوص کالا در وب&shy;&zwnj;سایت درج کرده&zwnj;اند، تجربه یا اطلاعات شخصی افراد است و برای آنها و دیجی&shy;&zwnj;کالا مسئولیتی ایجاد نمی&zwnj;کند. همچنین دیجی&zwnj;کالا مسئولیتی در قبال درستی اطلاعات فراهم شده روی بسته&zwnj;بندی کالا ندارد و مسئولیت آن با شرکت تولیدکننده کالاست.<br /> - درصورت عدم آگاهی، اطلاع و ناتوانی مشتری در استفاده از محصولات آرایشی و بهداشتی و یا ایجاد خسارت نسبت به خود یا محصول، تمامی مسئولیت&shy;&zwnj;های آن بر عهده مشتری است و فروشگاه دیجی&zwnj;کالا در این خصوص هیچگونه تعهدی نخواهد داشت.<br /> - در صورت تایید معیوب بودن کالای مرجوعی توسط شرکت تامین یا تولیدکننده، دیجی&zwnj;کالا صرفا هزینه کالا یا سرویس را مطابق فاکتور (حداکثر تا یک ماه از تاریخ فاکتور) به مشتری برمی&zwnj;گرداند.<br /> - دیجی&zwnj;کالا نسبت به عوارض جسمی و بیماری&zwnj;های ناشی از استفاده محصولات آرایشی و بهداشتی (از قبیل حساسیت&zwnj;های پوستی، جراحات و ...) مسئولیت و پاسخگویی ندارد. انجام پیگیری&zwnj;های لازم باید توسط مشتری و از طریق شرکت&zwnj;های مربوط ( نمایندگی&zwnj;ها و تامین&zwnj;کنندگان رسمی کالا) انجام شود.<br /> برای اطلاعات بیشتر به صفحه رویه&zwnj;های بازگرداندن کالا مراجعه کنید.</p>\n</div>\n<div id=\"comments\">نظرات کاربران\n<p>۱. فارسی بنویسید و از کیبورد فارسی استفاده کنید. بهتر است از فضای خالی (Space) بیش از حد معمول، کشیدن حروف یا کلمات، استفاده&zwnj;ی مکرر از یک حرف یا کلمه، شکلک و اموجی در متن خودداری کنید.<br /> ۲. برای نظر یا نقد و بررسی خود عنوانی متناسب با متن انتخاب کنید. یک عنوان خوب کاربران را برای خواندن نظر شما ترغیب خواهد کرد.<br /> ۳. نقد کاربران باید شامل قوت&zwnj;ها و ضعف&zwnj;های محصول در استفاده&zwnj;ی عملی و تجربه&zwnj;ی شخصی باشد و مزایا و معایب به&zwnj;صورت خلاصه و تیتروار در محل تعیین&zwnj;شده درج شود. لازم است تا حد ممکن از مبالغه و بزرگ&zwnj;نمایی مزایا یا معایب محصول خودداری کنید.<br /> ۴. نقد مناسب، نقدی است که فقط معایب یا فقط مزایا را در نظر نگیرد؛ بلکه به&zwnj;طور واقع&zwnj;بینانه معایب و مزایای هر محصول را در کنار هم بررسی کند.<br /> ۵. با توجه به تفاوت در سطح محصولات مختلف و تفاوت عمده در قیمت&zwnj;های آن&zwnj;ها، لازم است نقد و بررسی هر محصول با توجه به قیمت آن صورت گیرد؛ نه به&zwnj;صورت مطلق.<br /> ۶. جهت احترام&zwnj;گذاشتن به وقت بازدیدکنندگان سایت، لازم است هنگام نوشتن نقد، مطالب غیرضروری را حذف کرده و فقط مطالب ضروری و مفید را در نقدتان لحاظ کنید.<br /> ۷. با توجه به ساختار بخش نظرات، از سوال&zwnj;کردن یا درخواست راهنمایی در این بخش خودداری کرده و سوال یا درخواست راهنمایی خود را در بخش پرسش و پاسخ مطرح کنید.<br /> ۸. کاربران ارسال&zwnj;کننده&zwnj;ی نظر موظف&zwnj;اند از ادبیات محترمانه استفاده کرده و از توهین به دیگر کاربران یا سایر افراد پرهیز کنند. بدیهی است هرگونه توهین به فرد یا افراد و استفاده از کلمات نامناسب، باعث تاییدنشدن نظر کاربر می&zwnj;شود.<br /> ۹. قسمت نظرات سایت، با تالارهای گفت&zwnj;وگو (فروم) متفاوت است؛ لذا برای حفظ ساختار، مباحث خارج از چهارچوبی که حالت بحث و گفت&zwnj;وگو دارد، تایید نخواهد شد.<br /> ۱۰. تمام کاربران حق دارند نظرات خود را به شرط رعایت قوانین، در سایت منتشر کنند؛ لذا حتی اگر نظری را به دور از واقعیت، جانب&zwnj;دارانه یا اشتباه یافتید، نباید نظردهنده را مخاطب قرار دهید یا از وی انتقاد کنید. هر کاربر تنها می&zwnj;تواند نظر خود را عنوان کرده و قضاوت را به خوانندگان نظرات واگذار کند.<br /> ۱۱. از طریق نمودار تغییر قیمت در سایت می&zwnj;توانید از تغییرات قیمت آگاه شوید؛ لذا به&zwnj;هیچ&zwnj;وجه در بخش نظرات مبلغ &laquo;قیمت&raquo; را ذکر نکرده، درباره&zwnj;ی آن سؤال نکنید و نظری ندهید.<br /> ۱۲. در نظرات خود، از بزرگ&zwnj;نمایی یا اغراق درباره&zwnj;ی قوت&zwnj;ها یا ضعف&zwnj;های محصول خودداری کنید. بدیهی است تا حد ممکن هرگونه نظر مبالغه&zwnj;آمیز یا به دور از واقعیت تایید نخواهد شد.<br /> ۱۳. با توجه به مسئولیت سایت در قبال لینک&zwnj;های موجود در آن، نباید لینک سایت&zwnj;های دیگر را در نظرات خود ثبت کنید. دقت داشته باشید تا جای ممکن از هرگونه لینک&zwnj;دادن (فرستادن) دیگر کاربران به سایت&zwnj;های دیگر و درج ایمیل یا نام کاربری شبکه&zwnj;های اجتماعی خودداری کنید.<br /> ۱۴. تنها نظراتی تایید خواهند شد که مرتبط با محصول موردنظر باشند؛ لذا بحث&zwnj;های متفرقه و غیرمرتبط با محصول را مطرح نکنید.<br /> ۱۵. کاربران می&zwnj;توانند نقد خود به هر بخش از دیجی&zwnj;کالا را در قسمت مربوط اعلام کنند؛ لذا هیچ&zwnj;گونه نقدی را درباره&zwnj;ی سایت یا خدمات آن در قسمت نظرات ننویسید.<br /> ۱۶. توجه داشته باشند، مسائلی را که از آن اطمینان ندارید، به&zwnj;هیچ&zwnj;وجه در نظرات ثبت نکنید؛ همچنین از بازنشر شایعات یا اطلاعات غیرمطمئن درباره&zwnj;ی محصولات جدا خودداری کنید.<br /> ۱۷. بهتر است مطالبی در این بخش ثبت شود که برای بازدیدکنندگان سایت مفید باشد؛ لذا از بیان هرگونه مطالب شخصی، غیرمرتبط یا غیرضروری در این بخش پرهیز کنید.<br /> ۱۸. لازم است نظرات خود را به صورت نگارشی ثبت کرده و از کوتاه&zwnj;کردن کلمات یا استفاده از ادبیات محاوره تا جای ممکن خودداری کنید. استفاده از ادبیات نوشتاری که قابلیت نمایش در سایت را داشته باشد، لازمه&zwnj;ی تایید نظرات کاربران است. <br /> ۱۹. کامنت هایی که با عرف جامعه مغایر هستند مثل کامنت های کالاهای جنسی تایید نخواهند شد.</p>\n<h6>شرایط ارسال پرسش یا پاسخ:</h6>\n<h4>دیجی&zwnj;کالا نهایت تلاش و دقت را در راستای ارائه تمامی سرویس&zwnj;&rlm;های خود می&rlm;&zwnj;کند و به منظور تولید محتوا از منابع و مراجع اصیل و نیز شرکت&rlm;&zwnj;های سازنده محصولات استفاده می&rlm;&zwnj;کند. لازم به ذکر است دیجی&zwnj;کالا تضمین نمی&rlm;&zwnj;کند که توصیفات محصول و یا دیگر مطالب مندرج در سایت عاری از خطا باشد. اگر محصول ارائه شده توسط دیجی&zwnj;کالا دارای هر گونه مغایرت با اطلاعات درج شده در سایت است تنها راه حل، استرداد کالا قبل از استفاده و در شرایط اولیه است.<br /> وب &rlm;&zwnj;سایت دیجی&zwnj;کالا هیچ گونه مسوولیتی را در رابطه با حذف شدن صفحه&rlm;&zwnj;های سایت خود و یا لینک&rlm;&zwnj;های مرده نمی&zwnj;&rlm;پذیرد. سروﻳس&zwnj;&rlm;های سایت آن&rlm;گونه که هست ارائه می&rlm;&zwnj;شود و سایت دیجی&zwnj;کالا تحت هیچ شرایطی مسوولیت تاخیر یا عدم کارکرد سایت را که می&zwnj;تواند ناشى از عوامل طبیعى، نیروى انسانی، مشکلات اینترنتى، خرابی تجهیزات کامپیوترى، مخابراتى و غیره باشد بر عهده ندارد.<br /> سایت دیجی&zwnj;کالا ممکن است دارای سرویس&zwnj;&rlm;هایی با قابلیت مشارکت عمومی موسوم به سرویس&zwnj;&rlm;های جمعی، همچون اتاق گفتگو، تابلوی اعلانات، تالار گفتگو، نقد، گروه&zwnj;&rlm;های خبری، شبکه دوستان و سرویس&rlm;&zwnj;های دیگری باشد که برای تسهیل ارتباط شما با دیگران (چه در مقیاس عمومی مثل تالار گفتگو و چه در مقیاس خصوصی مثل شبکه خصوصی دوستان) ارائه می&rlm;&zwnj;شوند. شما توافق می&rlm;&zwnj;کنید که از این سرویس&rlm;&zwnj;های جمعی، فقط برای ثبت، ارسال و دریافت پیام&rlm;&zwnj;هایی استفاده کنید که مناسب و مرتبط با آن سرویس خاص باشد.<br /> <br /> &copy; کلیه محتویات سایت دیجی&zwnj;کالا شامل قانون حق تکثیر شده و به سایت دیجی&zwnj;کالا تعلق دارد.</h4>\n</div>\n<div id=\"pricing\">سیاست قیمت گذاری\n<h4>دیجی&zwnj;کالا در هیچ نقطه&rlm;&zwnj;ای از کشور نمایندگی فروش و یا خدمات پس از فروش ندارد و کلیه سرویس&zwnj;&rlm;ها و تراکنش&rlm;&zwnj;های مالی از طریق دفتر مرکزی پردازش می&rlm;&zwnj;شود. لطفاً در صورت مشاهده هر گونه تخلفی از این دست، مراتب را جهت پیگرد قانونی به روابط عمومی دیجی&zwnj;کالا اطلاع دهید.<br /> در حال حاضر دیجی&zwnj;کالا در شهرهای تحت پوشش تحویل اکسپرس، تنها خدمات ارسال و تحویل سفارش را از طریق مراکز توزیع و پخش خود انجام می&zwnj;دهد و برای خرید تنها باید به وب سایت دیجی&zwnj;کالا مراجعه و سفارش خود را ثبت کرد.</h4>\n</div>\n<div id=\"law\">قوه قهریه\n<p>تمامی شرایط و قوانین مندرج، در شرایط عادی قابل اجرا است و در صورت بروز هرگونه از موارد قوه قهریه، دیجی&zwnj;کالا هیچ گونه مسئولیتی ندارد.<br /> دیجی&zwnj;کالا خود را ملزم به رعایت حریم شخصی کاربران می&zwnj;داند، لطفا در صورت مشاهده هرگونه تخلف، مراتب را از طریق کانال&rlm;&zwnj;های ارتباطی ذکر شده با ما در میان بگذارید.</p>\n</div>', 'terms'),
(2, 'درباره ما', '<div>\n<div>\n<div><img alt=\"\" /></div>\n<p>همواره یکی از اولویت&zwnj;های دیجی&zwnj;کالا، تولید و ارائه محتوای فنی مورد نیاز کاربران با بالاترین استانداردها، برای تسهیل در فرایند پیش از خرید آنهاست. برای دیجی&zwnj;کالا بسیار مهم است که میلیون&rlm;&zwnj;ها کاربر آن، کالای تخصصی مورد نیاز خود را به درستی و با دقت بالا و بیشترین سهولت انتخاب کنند. دیجی&zwnj;کالا از هرگونه تلاشی برای بالاتر بردن کیفیت خرید مشتریان خود دریغ نمی&rlm;&zwnj;کند و همواره تلاش می&rlm;&zwnj;کند تا در طول فرآیند خرید یعنی پیش، حین و پس از خرید، بیشترین ارزش را برای کاربران و مشتریان خود خلق کند.<br /> دیجی&zwnj;کالا در سال 1390 با راه اندازی \"رسانه تصویری دیجی&zwnj;کالا\" یا DigiKala TV برای اولین بار در کشور تولید و ارائه محتوای ویدئویی آموزشی و تخصصی در زمینه معرفی و نقد و بررسی جدیدترین کالاهای دیجیتال و فن&rlm;&zwnj;آوری&rlm; های این حوزه را آغاز کرد. هدف از تولید این ویدئوها ایجاد امکانی برای کاربران جهت بررسی دقیق&zwnj;تر تمامی ابعاد و مشخصات محصول و شکل&zwnj;گیری تصویری دقیق&rlm;&rlm;&zwnj;تر از کالاها و تکنولوژی&rlm; های این حوزه بود. در حال حاضر این بخش یکی از محبوب&rlm;&zwnj;ترین خدمات دیجی&zwnj;کالا است، از این رو تولید محتوای تخصصی با کیفیت و استانداردهایی فراتر از گذشته یکی از راهبردهای اصلی دیجی&zwnj;کالا محسوب می&rlm;&zwnj;شود. رسانه تصویری دیجی&zwnj;کالا در جهت تولید محتوای ویدئویی علمی و کاربردی با بکارگیری افراد متخصص این حوزه نهایت تلاش خود را می&rlm;&rlm;&zwnj;کند. این ویدئوها به صورت کاملا مستقل و در استودیوی اختصاصی دیجی&zwnj;کالا تولید می&rlm;&zwnj;شوند و این سرویس به رایگان ارائه می&rlm;&zwnj;شود.<br /> <br /> دیجی&zwnj;کالا با ارائه طیف گسترده&rlm;&zwnj;ای از معتبرترین برندها در گروه&zwnj;&rlm;های مختلف و با همکاری نزدیک با وارد&rlm;کنندگان و توزیع&rlm; کنندگان اصلی این کالاها در ایران، تلاش می&rlm;&zwnj;کند تا نیازهای متفاوت مشتریان با کاربری&rlm;&zwnj;های متفاوت آنان را برآورده سازد. ارائه قیمت&rlm;&zwnj;های کاملا رقابتی و مناسب، همراه با کیفیت مطلوب خدمات پس از فروش از اولویت&rlm;&zwnj;های دیجی&zwnj;کالا محسوب می&zwnj;شود. دیجی&zwnj;کالا با درک اهمیت این موضوع برای مشتریان خود و با بکارگیری نهایت توان و ابزارهای در دسترس در تعاملات خود با تامین&rlm; کنندگان، می&rlm;&rlm;&zwnj;کوشد امکان ارائه پایین&rlm;&rlm;&zwnj;ترین قیمت&rlm;&rlm;&zwnj;ها و بهترین خدمات را فراهم آورد. طرح&rlm;&rlm;&zwnj;های مشوق خرید همواره مورد توجه مشتریان بوده و دیجی&zwnj;کالا نیز به منظور قدردانی از مشتریان وفادار خود همواره تخفیف&rlm;&zwnj;های قابل توجهی را در قالب طرح&zwnj;های مختلف مشوق خرید برای آنان در نظر می&rlm;&rlm;&zwnj;گیرد. کیفیت و سهولت استفاده از وب&rlm; سایت دیجی&zwnj;کالا و خدمات ارائه شده در آن، همواره یکی از مهم&rlm;&zwnj;ترین و با اولویت&zwnj;ترین موضوعات در دیجی&zwnj;کالا بوده است و همه کارکنان فنی و تخصصی آن نهایت تلاش خود را به عمل می&zwnj;آورند تا با ارائه با&rlm;کیفیت&rlm;&zwnj;ترین و به&rlm;&zwnj;روز&rlm;ترین سرویس&rlm;&zwnj;های مبتنی بر وب و تسهیل تمامی فرآیندهای بررسی، انتخاب و خرید کالا در وب سایت دیجی&zwnj;کالا، خدماتی شایسته و تجربه&zwnj;ای خوشایند را برای مخاطبان خود به ارمغان بیاورند.</p>\n</div>\n</div>', 'aboutus');

-- --------------------------------------------------------

--
-- Table structure for table `titles_property`
--

CREATE TABLE `titles_property` (
  `id` int(10) UNSIGNED NOT NULL,
  `title_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `titles_property`
--

INSERT INTO `titles_property` (`id`, `title_name`) VALUES
(10, 'مشخصات'),
(11, 'مشخصات فیزیکی'),
(12, 'مشخصات کلی'),
(13, 'مشخصات هدفون'),
(14, 'مشخصات دیگر'),
(15, 'صدا'),
(16, 'پردازنده'),
(17, 'حافظه'),
(18, 'نمایشگر'),
(19, 'صفحه نمایش'),
(20, 'ارتباطات'),
(21, 'دوربین'),
(22, 'امکانات نرم افزاری');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `n_code` varchar(10) DEFAULT NULL,
  `card_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `ip_address`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `created_on`, `active`, `first_name`, `last_name`, `image`, `n_code`, `card_number`) VALUES
(7, 'godroham@gmail.com', '$2y$10$QciND8313KmTHJ.oujaYveiuYukRabODIlzRtaiqV.oSRrjsmRE7i', '::1', 'saeedgerami72@gmail.com', '', NULL, NULL, 1565047321, 1, 'سعید', 'گرامی فر', 'user.svg', '4420440392', NULL),
(9, '09139518055', '$2y$10$.EQMz/1RhSnpSTSct4ty6OwShKZ2/5htlf1UsGCNktQy3xqsOQ3Xe', '::1', 'saeedgerami72@yahoo.com', '', NULL, NULL, 1567360102, 1, 'سعید', 'گرامی فر', 'avatar-1.svg', '4420440392', ''),
(10, '09179516271', '$2y$10$VWZJcueQ9V/XpshALj.Fp.j1USaNWCfXJw7DZNBU7EEIzChvTnA1K', '::1', '', '', NULL, NULL, 1567440291, 1, 'محمد مهدی', 'دهقان', 'avatar-5.svg', '4420549033', '');

-- --------------------------------------------------------

--
-- Table structure for table `users_address`
--

CREATE TABLE `users_address` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `receiver` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `phone` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_address`
--

INSERT INTO `users_address` (`id`, `user_id`, `receiver`, `province`, `city`, `address`, `postal_code`, `phone`) VALUES
(1, 9, 'سعید گرامی فر', 'یزد', 'میبد', 'خیابان کاشانی، کوچه لااله، کوی ۱۲ مدرس، پلاک ۳۵', '8916754959', '09139518055'),
(2, 9, 'سعید گرامی فر', 'یزد', 'مهریز', 'مهریز، کوچه بازار نو، مهرپادین', '8916754959', '09139518055'),
(3, 9, 'محمدمهدی دهقان', 'فارس', 'آباده', 'بلوار جام جم کوچه دهم پلاک 14', '8916754959', '09179516271'),
(4, 10, 'محمد مهدی دهقان', 'فارس', 'آباده', 'بلوار جام جم کوچه دهم پلاک 14', '9999999999', '09179516271'),
(5, 10, 'محمد مهدی دهقان', 'یزد', 'یزد', 'بلوار جام جم کوچه دهم پلاک 14', '9999999999', '09179516271');

-- --------------------------------------------------------

--
-- Table structure for table `users_favorite`
--

CREATE TABLE `users_favorite` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_favorite`
--

INSERT INTO `users_favorite` (`id`, `user_id`, `product_id`) VALUES
(16, 10, 10);

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

--
-- Dumping data for table `users_pages_perms`
--

INSERT INTO `users_pages_perms` (`id`, `user_id`, `page_id`, `perm_id`, `allow`) VALUES
(1, 7, 1, 2, 1);

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
(1, 7, 1),
(2, 10, 4),
(3, 9, 4);

-- --------------------------------------------------------

--
-- Structure for view `products_advanced`
--
DROP TABLE IF EXISTS `products_advanced`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `products_advanced`  AS  select `p`.`id` AS `id`,`p`.`product_code` AS `product_code`,`p`.`product_title` AS `product_title`,`p`.`latin_title` AS `latin_title`,`p`.`brand` AS `brand_id`,`p`.`category` AS `category_id`,`p`.`image` AS `image`,`p`.`body` AS `body`,`p`.`stock_count` AS `stock_count`,`p`.`sold_count` AS `sold_count`,`p`.`discount` AS `discount`,`p`.`discount_unit` AS `discount_unit`,`p`.`property` AS `property`,`p`.`property_abstract` AS `property_abstract`,`p`.`related` AS `related`,`p`.`user_created_id` AS `user_created_id`,`p`.`user_updated_id` AS `user_updated_id`,`p`.`keywords` AS `keywords`,`p`.`publish` AS `publish`,`p`.`available` AS `available`,`p`.`view` AS `view`,`p`.`created_on` AS `created_on`,`b`.`brand_code` AS `brand_code`,`b`.`brand_name` AS `brand_name`,`b`.`keywords` AS `brand_keywords`,`c`.`category_code` AS `category_code`,`c`.`category_name` AS `category_name`,`c`.`parent_id` AS `parent_id`,`c`.`all_parents` AS `all_parents`,`c`.`keywords` AS `category_keywords`,`pc`.`price` AS `price`,`pc`.`color_id` AS `color_id` from (((`products` `p` left join `brands` `b` on((`p`.`brand` = `b`.`id`))) left join `categories` `c` on((`p`.`category` = `c`.`id`))) left join `products_colors` `pc` on((`pc`.`product_id` = `p`.`id`))) where (case when (`p`.`stock_count` <> 0) then (`pc`.`count` <> 0) else 1 end) group by `pc`.`product_id` ;

-- --------------------------------------------------------

--
-- Structure for view `products_advanced_2`
--
DROP TABLE IF EXISTS `products_advanced_2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `products_advanced_2`  AS  select `p`.`id` AS `id`,`p`.`product_code` AS `product_code`,`p`.`product_title` AS `product_title`,`p`.`latin_title` AS `latin_title`,`p`.`brand` AS `brand_id`,`p`.`category` AS `category_id`,`p`.`image` AS `image`,`p`.`body` AS `body`,`p`.`stock_count` AS `stock_count`,`p`.`sold_count` AS `sold_count`,`p`.`discount` AS `discount`,`p`.`discount_unit` AS `discount_unit`,`p`.`property` AS `property`,`p`.`property_abstract` AS `property_abstract`,`p`.`related` AS `related`,`p`.`user_created_id` AS `user_created_id`,`p`.`user_updated_id` AS `user_updated_id`,`p`.`keywords` AS `keywords`,`p`.`publish` AS `publish`,`p`.`available` AS `available`,`p`.`view` AS `view`,`p`.`created_on` AS `created_on`,`b`.`brand_code` AS `brand_code`,`b`.`brand_name` AS `brand_name`,`b`.`keywords` AS `brand_keywords`,`c`.`category_code` AS `category_code`,`c`.`category_name` AS `category_name`,`c`.`parent_id` AS `parent_id`,`c`.`all_parents` AS `all_parents`,`c`.`keywords` AS `category_keywords`,`pc`.`price` AS `price`,`pc`.`color_id` AS `color_id` from (((`products` `p` left join `brands` `b` on((`p`.`brand` = `b`.`id`))) left join `categories` `c` on((`p`.`category` = `c`.`id`))) left join `products_colors` `pc` on((`pc`.`product_id` = `p`.`id`))) where (case when (`p`.`stock_count` <> 0) then (`pc`.`count` <> 0) else 1 end) group by `p`.`id`,`pc`.`color_id`,`pc`.`product_id`,`pc`.`price` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brand_code` (`brand_code`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id_2` (`product_id`,`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupon_code` (`coupon_code`);

--
-- Indexes for table `factors`
--
ALTER TABLE `factors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `factor_code_2` (`factor_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `send_stat` (`send_status`);

--
-- Indexes for table `factors_item`
--
ALTER TABLE `factors_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_factors_item_products` (`product_code`),
  ADD KEY `fk_factors_item_factors` (`factor_code`);

--
-- Indexes for table `factors_reserved`
--
ALTER TABLE `factors_reserved`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `festivals`
--
ALTER TABLE `festivals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `festival_code` (`festival_code`);

--
-- Indexes for table `gateway_idpay`
--
ALTER TABLE `gateway_idpay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idpay_factors` (`factor_code`);

--
-- Indexes for table `gateway_mabna`
--
ALTER TABLE `gateway_mabna`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mabna_factors` (`factor_code`);

--
-- Indexes for table `gateway_zarinpal`
--
ALTER TABLE `gateway_zarinpal`
  ADD PRIMARY KEY (`authority`),
  ADD KEY `fk_zarinpal_factors` (`factor_code`);

--
-- Indexes for table `guarantees`
--
ALTER TABLE `guarantees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guarantee_code` (`guarantee_code`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`product_code`),
  ADD KEY `brand` (`brand`),
  ADD KEY `category` (`category`),
  ADD KEY `user_created_id` (`user_created_id`),
  ADD KEY `brand_2` (`brand`),
  ADD KEY `user_updated_id` (`user_updated_id`);

--
-- Indexes for table `products_colors`
--
ALTER TABLE `products_colors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_color` (`product_id`,`color_id`) USING BTREE,
  ADD KEY `color_id` (`color_id`),
  ADD KEY `product_id` (`product_id`) USING BTREE;

--
-- Indexes for table `products_festivals`
--
ALTER TABLE `products_festivals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`,`festival_id`),
  ADD KEY `product_id_2` (`product_id`),
  ADD KEY `festival_id` (`festival_id`);

--
-- Indexes for table `products_guarantee`
--
ALTER TABLE `products_guarantee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products_image`
--
ALTER TABLE `products_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_order`
--
ALTER TABLE `return_order`
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
-- Indexes for table `send_status`
--
ALTER TABLE `send_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_sms`
--
ALTER TABLE `sent_sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shippings`
--
ALTER TABLE `shippings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `static_pages`
--
ALTER TABLE `static_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `titles_property`
--
ALTER TABLE `titles_property`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users_address`
--
ALTER TABLE `users_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_2` (`user_id`);

--
-- Indexes for table `users_favorite`
--
ALTER TABLE `users_favorite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`) USING BTREE,
  ADD KEY `fk_users_favorite_products1` (`product_id`);

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
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `factors`
--
ALTER TABLE `factors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `factors_item`
--
ALTER TABLE `factors_item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `factors_reserved`
--
ALTER TABLE `factors_reserved`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `festivals`
--
ALTER TABLE `festivals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gateway_idpay`
--
ALTER TABLE `gateway_idpay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gateway_mabna`
--
ALTER TABLE `gateway_mabna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guarantees`
--
ALTER TABLE `guarantees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products_colors`
--
ALTER TABLE `products_colors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `products_festivals`
--
ALTER TABLE `products_festivals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products_guarantee`
--
ALTER TABLE `products_guarantee`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products_image`
--
ALTER TABLE `products_image`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `return_order`
--
ALTER TABLE `return_order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles_pages_perms`
--
ALTER TABLE `roles_pages_perms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `send_status`
--
ALTER TABLE `send_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sent_sms`
--
ALTER TABLE `sent_sms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `static_pages`
--
ALTER TABLE `static_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `titles_property`
--
ALTER TABLE `titles_property`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users_address`
--
ALTER TABLE `users_address`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_favorite`
--
ALTER TABLE `users_favorite`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users_pages_perms`
--
ALTER TABLE `users_pages_perms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_roles`
--
ALTER TABLE `users_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `factors`
--
ALTER TABLE `factors`
  ADD CONSTRAINT `fk_factors_send_status1` FOREIGN KEY (`send_status`) REFERENCES `send_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_factors_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `factors_item`
--
ALTER TABLE `factors_item`
  ADD CONSTRAINT `fk_factors_item_factors` FOREIGN KEY (`factor_code`) REFERENCES `factors` (`factor_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_factors_item_products` FOREIGN KEY (`product_code`) REFERENCES `products` (`product_code`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `gateway_idpay`
--
ALTER TABLE `gateway_idpay`
  ADD CONSTRAINT `fk_idpay_factors` FOREIGN KEY (`factor_code`) REFERENCES `factors` (`factor_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gateway_mabna`
--
ALTER TABLE `gateway_mabna`
  ADD CONSTRAINT `fk_mabna_factors` FOREIGN KEY (`factor_code`) REFERENCES `factors` (`factor_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gateway_zarinpal`
--
ALTER TABLE `gateway_zarinpal`
  ADD CONSTRAINT `fk_zarinpal_factors` FOREIGN KEY (`factor_code`) REFERENCES `factors` (`factor_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brands1` FOREIGN KEY (`brand`) REFERENCES `brands` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_categories1` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_users1` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `products_colors`
--
ALTER TABLE `products_colors`
  ADD CONSTRAINT `fk_products_colors_colors1` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_colors_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products_festivals`
--
ALTER TABLE `products_festivals`
  ADD CONSTRAINT `fk_products_festivals_festivals` FOREIGN KEY (`festival_id`) REFERENCES `festivals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_festivals_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products_guarantee`
--
ALTER TABLE `products_guarantee`
  ADD CONSTRAINT `fk_products_guarantee_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products_image`
--
ALTER TABLE `products_image`
  ADD CONSTRAINT `fk_products_images_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `roles_pages_perms`
--
ALTER TABLE `roles_pages_perms`
  ADD CONSTRAINT `fk_rpp_p` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `fk_rpp_pa` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`),
  ADD CONSTRAINT `fk_rpp_r` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `users_address`
--
ALTER TABLE `users_address`
  ADD CONSTRAINT `fk_users_address_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_favorite`
--
ALTER TABLE `users_favorite`
  ADD CONSTRAINT `fk_users_favorite_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_favorite_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
