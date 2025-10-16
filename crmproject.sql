-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2025 at 04:23 PM
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
-- Database: `crmproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Human Resources', 'Human Resources Department', 'HR', 1, '2025-10-01 02:29:17', '2025-10-01 02:29:17'),
(2, 'Information Technology', 'Information Technology Department', 'IT', 1, '2025-10-01 02:29:17', '2025-10-01 02:29:17'),
(3, 'Finance', 'Finance and Accounting Department', 'FIN', 1, '2025-10-01 02:29:17', '2025-10-01 02:29:17'),
(4, 'Marketing', 'Marketing and Sales Department', 'MKT', 1, '2025-10-01 02:29:17', '2025-10-01 02:29:17'),
(5, 'Operations', 'Operations Department', 'OPS', 1, '2025-10-01 02:29:17', '2025-10-01 02:29:17'),
(6, 'Support', 'Support Department', 'SUP', 1, '2025-10-01 02:29:17', '2025-10-03 07:47:17');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_09_23_115318_create_roles_table', 1),
(6, '2025_09_23_115322_create_modules_table', 1),
(7, '2025_09_23_115326_create_user_modules_table', 1),
(8, '2025_09_23_115329_update_users_table_for_crm', 1),
(9, '2025_09_24_044619_add_admin_id_to_users_table', 1),
(10, '2025_09_24_050807_add_profile_fields_to_users_table', 1),
(11, '2025_09_24_075921_add_phone_to_users_table', 1),
(12, '2025_09_24_080112_add_profile_fields_to_users_table', 1),
(13, '2025_09_24_080915_add_missing_columns_to_users', 1),
(14, '2025_09_24_081145_create_user_infos_table', 1),
(15, '2025_09_24_081619_remove_profile_fields_from_users_table', 1),
(21, '2025_09_24_081855_drop_user_infos_table', 2),
(22, '2025_10_01_071359_recreate_user_infos_table', 3),
(23, '2025_09_24_082128_add_missing_columns_to_user_info_table', 4),
(24, '2025_10_01_071637_add_missing_columns_to_user_infos', 5),
(25, '2025_09_24_082949_update_existing_users_hierarchy', 6),
(26, '2025_09_26_104331_create_supervisors_table', 7),
(27, '2025_09_27_113251_add_created_by_to_users_table', 8),
(28, '2025_09_27_114327_add_not_null_constraints_to_users_table', 9),
(29, '2025_09_26_104338_create_supervisor_permissions_table', 10),
(30, '2025_09_27_070744_create_sessions_table', 11),
(31, '2025_09_29_150514_add_avatar_column_to_user_info_table', 12),
(32, '2025_09_29_155654_create_departments_table', 13),
(33, '2025_09_29_155721_add_department_id_to_user_info_table', 14),
(34, '2025_09_27_110447_add_salary_to_users_table', 15),
(35, '2025_09_27_073829_add_remember_token_to_supervisors_table', 16),
(36, '2025_10_01_073221_create_user_types_table', 17),
(37, '2025_09_24_111358_ensure_all_users_have_user_info', 18),
(38, '2025_10_01_073254_add_superadmin_id_to_users_table', 19),
(39, '2025_09_24_111731_populate_hierarchy_in_users_table', 20),
(40, '2025_10_01_073329_create_user_identification_table', 21),
(41, '2025_09_24_083355_populate_user_identification_table', 22),
(42, '2025_09_24_083928_fix_user_identification_status_column', 23),
(43, '2025_10_01_083942_move_created_by_fields_to_user_infos', 24),
(44, '2025_10_01_084132_move_salary_to_user_infos', 25),
(45, '2025_10_01_084439_remove_name_from_users_table', 26),
(46, '2025_10_01_084936_reorder_users_table_columns', 27),
(47, '2025_10_01_084939_add_first_last_name_to_user_infos', 28),
(48, '2025_09_29_151148_add_additional_fields_to_user_info_table', 29),
(49, '2025_09_24_110531_add_user_type_id_to_user_info_table', 30),
(50, '2025_09_24_110925_migrate_user_types_to_user_info_table', 31),
(51, '2025_10_02_073658_add_permission_columns_to_user_modules_table', 32),
(52, '2025_10_06_063602_create_salary_payments_table', 33),
(53, '2025_10_06_103636_add_next_payment_date_to_salary_payments_table', 34),
(54, '2025_10_07_104806_add_finance_permissions_to_supervisor_permissions_table', 35),
(55, '2025_10_07_105943_add_finance_permissions_to_user_modules_table', 36),
(56, '2025_10_08_050947_add_missing_permission_columns_to_user_modules_table', 37);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'bx bx-grid-alt',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `description`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'HRM', 'Human Resource Management', 'bx bx-group', 1, '2025-10-01 01:49:29', '2025-10-01 01:49:29'),
(2, 'FINANCE', 'Financial Management System', 'bx bx-money', 1, '2025-10-01 01:49:29', '2025-10-01 01:49:29'),
(3, 'SUPPORT', 'Customer Support Management', 'bx bx-support', 1, '2025-10-01 01:49:29', '2025-10-02 03:50:59'),
(4, 'Reports & Analytics', 'View reports and analytics dashboard', 'bx bx-bar-chart-alt-2', 1, '2025-10-01 01:49:29', '2025-10-01 01:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'SuperAdmin', 'Full system access', '2025-10-01 01:49:29', '2025-10-01 01:49:29'),
(2, 'Admin', 'User management and module assignment', '2025-10-01 01:49:29', '2025-10-01 01:49:29'),
(3, 'User', 'Access to assigned modules only', '2025-10-01 01:49:29', '2025-10-01 01:49:29'),
(4, 'Supervisor', 'Module supervisor with limited permissions', '2025-10-01 02:35:59', '2025-10-01 02:35:59');

-- --------------------------------------------------------

--
-- Table structure for table `salary_payments`
--

CREATE TABLE `salary_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_by` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `next_payment_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_payments`
--

INSERT INTO `salary_payments` (`id`, `user_id`, `amount`, `status`, `paid_by`, `paid_at`, `next_payment_date`, `notes`, `created_at`, `updated_at`) VALUES
(24, 34, 50000.00, 'paid', 16, '2025-10-08 02:25:12', '2025-11-08', 'Salary payment marked as paid by Admin: golden miharu', '2025-10-08 02:25:12', '2025-10-08 02:25:12'),
(26, 40, 4220.00, 'paid', 36, '2025-10-08 02:54:47', '2025-11-08', 'Salary payment marked as paid by Admin: me me', '2025-10-08 02:54:47', '2025-10-08 02:54:47');

-- --------------------------------------------------------

--
-- Table structure for table `salary_payment_history`
--

CREATE TABLE `salary_payment_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_month` varchar(255) NOT NULL,
  `payment_year` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `paid_by` bigint(20) UNSIGNED NOT NULL,
  `paid_by_type` varchar(255) NOT NULL,
  `paid_by_name` varchar(255) NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_payment_history`
--

INSERT INTO `salary_payment_history` (`id`, `user_id`, `amount`, `payment_month`, `payment_year`, `status`, `paid_by`, `paid_by_type`, `paid_by_name`, `paid_at`, `due_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 34, 50000.00, '2025-10', '2025', 'paid', 16, 'admin', 'golden miharu', '2025-10-08 02:25:12', '2025-10-08 02:25:12', 'Salary payment marked as paid by Admin: golden miharu', '2025-10-08 02:25:12', '2025-10-08 02:25:12'),
(3, 40, 4220.00, '2025-10', '2025', 'paid', 36, 'admin', 'me me', '2025-10-08 02:54:47', '2025-10-08 02:54:47', 'Salary payment marked as paid by Admin: me me', '2025-10-08 02:54:47', '2025-10-08 02:54:47');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supervisors`
--

CREATE TABLE `supervisors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `superadmin_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supervisors`
--

INSERT INTO `supervisors` (`id`, `first_name`, `last_name`, `email`, `password`, `remember_token`, `status`, `admin_id`, `superadmin_id`, `created_at`, `updated_at`) VALUES
(4, 'omni', 'assign', 'omniassign6@gmail.com', '$2y$10$q66rvrld37H24L2IZ2FVBu9b9F19fk3XB.NEFCmriytpHlC6dpR2a', NULL, 'active', 16, 1, '2025-10-07 06:56:53', '2025-10-07 06:56:53'),
(5, 'mubeen', 'saeed', 'she@gmail.com', '$2y$10$izyDBxWdSp89Ghp/kMLQB.5IZ/eTCKRnTRglwiltwJKEG6LFfP9gi', NULL, 'active', 36, 1, '2025-10-08 03:37:51', '2025-10-08 03:37:51');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor_permissions`
--

CREATE TABLE `supervisor_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supervisor_id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `can_create_users` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit_users` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete_users` tinyint(1) NOT NULL DEFAULT 0,
  `can_reset_passwords` tinyint(1) NOT NULL DEFAULT 0,
  `can_assign_modules` tinyint(1) NOT NULL DEFAULT 0,
  `can_view_reports` tinyint(1) NOT NULL DEFAULT 0,
  `can_mark_salary_paid` tinyint(1) NOT NULL DEFAULT 0,
  `can_mark_salary_pending` tinyint(1) NOT NULL DEFAULT 0,
  `can_view_salary_data` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_salary_payments` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supervisor_permissions`
--

INSERT INTO `supervisor_permissions` (`id`, `supervisor_id`, `module_id`, `can_create_users`, `can_edit_users`, `can_delete_users`, `can_reset_passwords`, `can_assign_modules`, `can_view_reports`, `can_mark_salary_paid`, `can_mark_salary_pending`, `can_view_salary_data`, `can_manage_salary_payments`, `created_at`, `updated_at`) VALUES
(18, 4, 1, 1, 1, 0, 0, 1, 1, 1, 0, 1, 1, '2025-10-07 10:01:34', '2025-10-07 10:01:34'),
(19, 4, 2, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, '2025-10-07 10:01:34', '2025-10-07 10:01:34'),
(20, 4, 3, 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, '2025-10-07 10:01:34', '2025-10-07 10:01:34'),
(21, 5, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, '2025-10-08 03:37:51', '2025-10-08 03:37:51'),
(22, 5, 2, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, '2025-10-08 03:37:51', '2025-10-08 03:37:51'),
(23, 5, 3, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, '2025-10-08 03:37:51', '2025-10-08 03:37:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `role_id` bigint(20) NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `superadmin_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `admin_id`, `role_id`, `is_approved`, `superadmin_id`, `created_at`, `updated_at`) VALUES
(1, 'Saeed', 'Mubeen', 'saeedmubeen20@gmail.com', NULL, '$2y$10$l361YEIyd2CcAexEjmksQeGpowEPFMBc1SDfAUYCGbeKwlhvMocF2', NULL, NULL, 1, 1, NULL, '2025-10-01 01:49:29', '2025-10-01 01:49:29'),
(16, 'golden', 'miharu', 'goldenmiharu@gmail.com', NULL, '$2y$10$NoQVbaIgWMiIDxG0rhKHluGdv3pPWxwRzcvZ69f0yHq.KewTfJ7M.', NULL, NULL, 2, 1, 1, '2025-10-07 02:40:27', '2025-10-07 02:41:00'),
(34, 'mani', 'u', 'lilhexxi7@gmail.com', NULL, '$2y$10$VBLIPcs4PdvFR89L5jP4T.WtlMkczteeNgx2NDUs0BF..FtRm2yje', NULL, 16, 3, 1, 1, '2025-10-08 02:17:41', '2025-10-08 02:17:41'),
(36, 'me', 'me', 'lilfoxy7777@gmail.com', NULL, '$2y$10$kHizkKT0h8M2t3s.x29vceqHh3LtSNexYFnlA/styqF8mELeb.2Ba', NULL, NULL, 2, 1, 1, '2025-10-08 02:29:53', '2025-10-08 02:30:26'),
(40, 'mmm', 'mmm', 'mani@gmail.com', NULL, '$2y$10$QDoqmdvJJwMCDKycImkCTeC6yvKv0qLc.PDjhhFijBGTME0nJLbDG', NULL, 36, 3, 1, 1, '2025-10-08 02:54:26', '2025-10-08 02:54:26');

-- --------------------------------------------------------

--
-- Table structure for table `user_identification`
--

CREATE TABLE `user_identification` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `superadmin_id` bigint(20) DEFAULT NULL,
  `user_role` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_identification`
--

INSERT INTO `user_identification` (`id`, `user_id`, `admin_id`, `superadmin_id`, `user_role`, `status`, `approved_at`, `assigned_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 'superadmin', 'active', '2025-10-01 02:33:53', '2025-10-01 02:33:53', 'Auto-populated from existing user data', '2025-10-01 02:33:53', '2025-10-01 02:33:53');

-- --------------------------------------------------------

--
-- Table structure for table `user_infos`
--

CREATE TABLE `user_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `gmail` varchar(255) DEFAULT NULL,
  `cnic` varchar(255) DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `emergency_contact_relationship` varchar(255) DEFAULT NULL,
  `timezone` varchar(255) NOT NULL DEFAULT 'UTC',
  `language` varchar(255) NOT NULL DEFAULT 'en',
  `email_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `sms_notifications` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `superadmin_id` bigint(20) DEFAULT NULL,
  `user_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `created_by_type` varchar(255) DEFAULT NULL,
  `created_by_id` bigint(20) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_infos`
--

INSERT INTO `user_infos` (`id`, `user_id`, `first_name`, `last_name`, `phone`, `gmail`, `cnic`, `passport`, `date_of_birth`, `gender`, `avatar`, `address`, `city`, `state`, `country`, `postal_code`, `job_title`, `department`, `company`, `department_id`, `bio`, `linkedin_url`, `twitter_url`, `website_url`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relationship`, `timezone`, `language`, `email_notifications`, `sms_notifications`, `created_at`, `updated_at`, `superadmin_id`, `user_type_id`, `admin_id`, `created_by_type`, `created_by_id`, `salary`) VALUES
(2, 1, 'Saeed', 'Mubeen', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UTC', 'en', 1, 0, '2025-10-01 02:32:46', '2025-10-06 03:37:07', 1, NULL, NULL, 'system', 1, NULL),
(10, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UTC', 'en', 1, 0, '2025-10-07 02:40:27', '2025-10-07 02:40:27', 1, NULL, NULL, NULL, NULL, NULL),
(28, 34, 'mani', 'u', NULL, NULL, NULL, NULL, NULL, 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hhcdsh', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UTC', 'en', 1, 0, '2025-10-08 02:17:41', '2025-10-08 02:17:41', 1, NULL, 16, 'admin', 16, 50000.00),
(30, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UTC', 'en', 1, 0, '2025-10-08 02:29:53', '2025-10-08 02:29:53', 1, NULL, NULL, NULL, NULL, NULL),
(33, 40, 'mmm', 'mmm', NULL, NULL, NULL, NULL, NULL, 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UTC', 'en', 1, 0, '2025-10-08 02:54:26', '2025-10-08 02:54:26', 1, NULL, 36, 'admin', 36, 4220.00);

-- --------------------------------------------------------

--
-- Table structure for table `user_modules`
--

CREATE TABLE `user_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `can_view_reports` tinyint(1) NOT NULL DEFAULT 0,
  `can_create_users` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit_users` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete_users` tinyint(1) NOT NULL DEFAULT 0,
  `can_reset_passwords` tinyint(1) NOT NULL DEFAULT 0,
  `can_assign_modules` tinyint(1) NOT NULL DEFAULT 0,
  `can_mark_salary_paid` tinyint(1) NOT NULL DEFAULT 0,
  `can_mark_salary_pending` tinyint(1) NOT NULL DEFAULT 0,
  `can_view_salary_data` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_salary_payments` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_modules`
--

INSERT INTO `user_modules` (`id`, `user_id`, `module_id`, `can_view_reports`, `can_create_users`, `can_edit_users`, `can_delete_users`, `can_reset_passwords`, `can_assign_modules`, `can_mark_salary_paid`, `can_mark_salary_pending`, `can_view_salary_data`, `can_manage_salary_payments`, `created_at`, `updated_at`) VALUES
(42, 34, 1, 1, 0, 1, 0, 1, 1, 0, 0, 0, 0, '2025-10-08 02:17:42', '2025-10-08 02:17:42'),
(43, 34, 2, 1, 0, 0, 0, 0, 0, 1, 0, 1, 0, '2025-10-08 02:17:42', '2025-10-08 02:17:42'),
(44, 34, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2025-10-08 02:17:42', '2025-10-08 02:17:42'),
(46, 40, 2, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, '2025-10-08 02:54:26', '2025-10-08 02:54:26'),
(47, 40, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2025-10-08 02:54:26', '2025-10-08 02:54:26');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SuperAdmin', 'System Administrator', 1, '2025-10-01 02:32:38', '2025-10-01 02:32:38'),
(2, 'Admin', 'Administrator', 1, '2025-10-01 02:32:38', '2025-10-01 02:32:38'),
(3, 'Supervisor', 'Supervisor', 1, '2025-10-01 02:32:38', '2025-10-01 02:32:38'),
(4, 'User', 'Regular User', 1, '2025-10-01 02:32:38', '2025-10-01 02:32:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`),
  ADD UNIQUE KEY `departments_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_payments`
--
ALTER TABLE `salary_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_payments_user_id_foreign` (`user_id`),
  ADD KEY `salary_payments_paid_by_foreign` (`paid_by`);

--
-- Indexes for table `salary_payment_history`
--
ALTER TABLE `salary_payment_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_payment_history_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `supervisors`
--
ALTER TABLE `supervisors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supervisors_email_unique` (`email`),
  ADD KEY `supervisors_admin_id_foreign` (`admin_id`),
  ADD KEY `supervisors_superadmin_id_foreign` (`superadmin_id`);

--
-- Indexes for table `supervisor_permissions`
--
ALTER TABLE `supervisor_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supervisor_permissions_supervisor_id_module_id_unique` (`supervisor_id`,`module_id`),
  ADD KEY `supervisor_permissions_module_id_foreign` (`module_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_new_email_unique` (`email`);

--
-- Indexes for table `user_identification`
--
ALTER TABLE `user_identification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_identification_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_infos`
--
ALTER TABLE `user_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_infos_user_id_foreign` (`user_id`),
  ADD KEY `user_infos_department_id_foreign` (`department_id`);

--
-- Indexes for table `user_modules`
--
ALTER TABLE `user_modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_modules_user_id_module_id_unique` (`user_id`,`module_id`),
  ADD KEY `user_modules_module_id_foreign` (`module_id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salary_payments`
--
ALTER TABLE `salary_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `salary_payment_history`
--
ALTER TABLE `salary_payment_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `supervisors`
--
ALTER TABLE `supervisors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supervisor_permissions`
--
ALTER TABLE `supervisor_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `user_identification`
--
ALTER TABLE `user_identification`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_infos`
--
ALTER TABLE `user_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `user_modules`
--
ALTER TABLE `user_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `salary_payments`
--
ALTER TABLE `salary_payments`
  ADD CONSTRAINT `salary_payments_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salary_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_payment_history`
--
ALTER TABLE `salary_payment_history`
  ADD CONSTRAINT `salary_payment_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supervisors`
--
ALTER TABLE `supervisors`
  ADD CONSTRAINT `supervisors_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervisors_superadmin_id_foreign` FOREIGN KEY (`superadmin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supervisor_permissions`
--
ALTER TABLE `supervisor_permissions`
  ADD CONSTRAINT `supervisor_permissions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervisor_permissions_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_identification`
--
ALTER TABLE `user_identification`
  ADD CONSTRAINT `user_identification_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_infos`
--
ALTER TABLE `user_infos`
  ADD CONSTRAINT `user_infos_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_infos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_modules`
--
ALTER TABLE `user_modules`
  ADD CONSTRAINT `user_modules_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_modules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
