-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 16, 2019 at 06:35 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digidocu_xdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `validation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `custom_fields`
--

INSERT INTO `custom_fields` (`id`, `model_type`, `name`, `validation`, `created_at`, `updated_at`) VALUES
(2, 'documents', 'model_year', 'required', '2019-11-11 11:42:56', '2019-11-11 12:05:44'),
(3, 'tags', 'address', NULL, '2019-11-12 10:39:04', '2019-11-12 10:40:10'),
(4, 'tags', 'city', 'required', '2019-11-12 10:39:19', '2019-11-12 10:39:19'),
(6, 'tags', 'branch_manager', 'required', '2019-11-13 09:16:04', '2019-11-13 09:16:04'),
(7, 'tags', 'contact_number', 'required', '2019-11-13 09:16:55', '2019-11-13 09:16:55'),
(8, 'tags', 'city', 'required', '2019-11-13 09:26:18', '2019-11-13 09:26:18'),
(9, 'tags', 'state', 'required', '2019-11-13 09:26:28', '2019-11-13 09:26:28'),
(10, 'tags', 'pincode', 'required|numeric', '2019-11-13 09:29:02', '2019-11-13 09:29:02'),
(11, 'documents', 'address', 'required', '2019-11-13 09:29:32', '2019-11-13 09:29:32'),
(12, 'documents', 'city', 'required', '2019-11-13 09:29:44', '2019-11-13 09:29:44'),
(13, 'documents', 'state', 'required', '2019-11-13 09:30:29', '2019-11-13 09:30:29'),
(14, 'tags', 'mobile_number', 'required', '2019-11-13 09:35:22', '2019-11-13 09:35:22'),
(15, 'tags', 'vehicle_name', 'required', '2019-11-13 09:38:41', '2019-11-13 09:38:41'),
(16, 'tags', 'vehicle_model', 'required', '2019-11-13 09:38:59', '2019-11-13 09:38:59'),
(17, 'tags', 'finance_status', 'required', '2019-11-13 09:39:29', '2019-11-13 09:39:29'),
(18, 'tags', 'office_contact', 'required', '2009-12-31 18:41:31', '2009-12-31 18:41:31'),
(19, 'documents', 'vehicle_number', 'required|numeric', '2019-11-15 11:27:13', '2019-11-15 11:27:13');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `custom_fields` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `name`, `description`, `status`, `created_by`, `custom_fields`, `verified_by`, `verified_at`, `created_at`, `updated_at`) VALUES
(3, 'Mandarmalika B. Singh', NULL, 'ACTIVE', 1, '{\"model_year\":\"2018\",\"address\":\"P.O. Box 627, 445 Ut St.\",\"city\":\"Palanpur\",\"state\":\"Gujarat\",\"vehicle_number\":\"2528\"}', NULL, NULL, '2019-11-14 09:45:28', '2019-11-15 11:27:46');

-- --------------------------------------------------------

--
-- Table structure for table `documents_tags`
--

CREATE TABLE `documents_tags` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `documents_tags`
--

INSERT INTO `documents_tags` (`document_id`, `tag_id`) VALUES
(3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `file_type_id` int(10) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `custom_fields` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_types`
--

CREATE TABLE `file_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `no_of_files` int(11) NOT NULL,
  `labels` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_validations` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_maxsize` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `file_types`
--

INSERT INTO `file_types` (`id`, `name`, `no_of_files`, `labels`, `file_validations`, `file_maxsize`, `created_at`, `updated_at`) VALUES
(1, 'Address Proof', 2, 'front,back', 'mimes:pdf', 8, '2019-11-12 07:01:06', '2019-11-12 07:01:06'),
(2, 'ID Proof', 2, 'Front,Back', 'mimes:jpeg,bmp,png,jpg', 8, '2019-11-13 09:49:02', '2019-11-13 09:50:38'),
(3, 'Bank Proof', 1, 'Bank Passbook Front', 'mimes:jpeg,bmp,png,jpg', 8, '2019-11-13 09:50:02', '2019-11-13 09:50:02'),
(4, 'Finance Proof', 3, 'Cheque-1,Cheque-2,Cheque-3', 'mimes:jpeg,bmp,png,jpg', 8, '2019-11-13 09:53:10', '2019-11-13 09:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_11_09_055735_create_settings_table', 1),
(5, '2019_11_11_162247_create_custom_fields_table', 2),
(6, '2019_11_11_170438_create_custom_fields_table', 3),
(7, '2019_11_12_122144_create_file_types_table', 4),
(8, '2019_11_12_155907_create_tags_table', 5),
(11, '2019_11_13_150331_create_documents_table', 6),
(12, '2019_11_14_144921_create_documents_tags_table', 7),
(13, '2019_11_15_122537_create_files_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'system_title', 'Tanu Motors', '2019-11-09 01:18:18', '2019-11-09 01:18:18'),
(2, 'system_logo', 'logo.png', '2019-11-09 01:18:18', '2019-11-09 01:18:18'),
(5, 'tags_label_singular', 'branch', '2019-11-09 01:25:08', '2009-12-31 18:40:36'),
(6, 'tags_label_plural', 'branches', '2019-11-09 01:25:08', '2019-11-09 01:25:08'),
(7, 'document_label_singular', 'customer', '2019-11-09 01:25:08', '2019-11-09 01:25:08'),
(8, 'document_label_plural', 'customers', '2019-11-09 01:25:08', '2019-11-09 01:25:08'),
(9, 'file_label_singular', 'document', '2019-11-09 01:25:08', '2019-11-09 01:25:08'),
(10, 'file_label_plural', 'documents', '2019-11-09 01:25:08', '2019-11-09 01:25:08'),
(11, 'default_file_validations', 'mimes:jpeg,bmp,png,jpg', '2019-11-09 01:25:08', '2019-11-09 01:25:08'),
(12, 'default_file_maxsize', '8', '2019-11-09 01:25:08', '2019-11-09 01:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `custom_fields` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `color`, `created_by`, `custom_fields`, `created_at`, `updated_at`) VALUES
(1, 'A1 Palanpur', '#8000ff', 2, '{\"address\":\"123, xyz\",\"city\":\"pln\"}', '2019-11-12 12:16:27', '2019-11-13 05:44:43'),
(2, 'A2 Vadgam', '#008000', 1, '{\"address\":\"44, Opp. Pqr\",\"city\":\"Vadgam\"}', '2019-11-13 05:57:13', '2019-11-13 05:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `address`, `description`, `email_verified_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super@gmail.com', 'super', 'P.O. Box 818, 9420 Convallis Ave', NULL, NULL, '$2y$10$YLLzKbdmcEey5sNBEd6tze.SrAlGcms1OdF3AmygBjtSdcbxaojfq', 'ACTIVE', NULL, '2019-11-09 01:36:35', '2019-11-11 09:16:10'),
(2, 'snehal', NULL, 'snehal', NULL, NULL, NULL, '$2y$10$P0p15xMeapDyjmsHBzfjgePnv664.qxdUbWakZs5UgeGFG2gElAdi', 'ACTIVE', NULL, '2019-11-11 07:51:19', '2019-11-12 07:17:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_created_by_foreign` (`created_by`),
  ADD KEY `documents_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `documents_tags`
--
ALTER TABLE `documents_tags`
  ADD PRIMARY KEY (`document_id`,`tag_id`),
  ADD KEY `documents_tags_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_document_id_foreign` (`document_id`),
  ADD KEY `files_file_type_id_foreign` (`file_type_id`),
  ADD KEY `files_created_by_foreign` (`created_by`);

--
-- Indexes for table `file_types`
--
ALTER TABLE `file_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tags_created_by_foreign` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_types`
--
ALTER TABLE `file_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `documents_tags`
--
ALTER TABLE `documents_tags`
  ADD CONSTRAINT `documents_tags_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `documents_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `files_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`),
  ADD CONSTRAINT `files_file_type_id_foreign` FOREIGN KEY (`file_type_id`) REFERENCES `file_types` (`id`);

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
