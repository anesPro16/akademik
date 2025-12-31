-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2025 at 10:34 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akademik`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `user_id`, `teacher_id`, `name`, `code`, `created_at`) VALUES
('01KB6S6G7FHD6ZYZP8BKNNYBFF', '01K8WAF2VCSHCNQYZQNDQ0K806', '01KB4C6WZDNXRWB3SMFXSZ9NT1', '5a', '30D93B', '2025-11-29 10:06:11'),
('01KB7B61CNTD2D91YRZ6P1GVCF', '01K8WAF2VCSHCNQYZQNDQ0K806', '01KB7B41WP5AAA8MZ0TVADNRF8', 'Kelas 6', 'E3810E', '2025-11-29 15:20:30');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `exam_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('UTS','UAS') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`exam_id`, `class_id`, `exam_name`, `type`, `start_time`, `end_time`, `is_active`, `created_at`) VALUES
('01KDSX5C7WTTCFNJSK9S6GTZ0E', '01KB7B61CNTD2D91YRZ6P1GVCF', 'mtk', 'UTS', '2025-12-31 16:52:00', '2025-12-31 17:52:00', 1, '2025-12-31 16:52:26');

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

CREATE TABLE `exam_questions` (
  `id` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_id` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_a` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_b` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_c` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_d` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_answer` enum('A','B','C','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pbl_essay_questions`
--

CREATE TABLE `pbl_essay_questions` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `essay_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'FK ke pbl_solution_essays.id',
  `question_number` int NOT NULL COMMENT 'Nomor urut pertanyaan',
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Teks pertanyaan',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_essay_questions`
--

INSERT INTO `pbl_essay_questions` (`id`, `essay_id`, `question_number`, `question_text`, `created_at`) VALUES
('01KDSA8Z2R1KF88SX1GNTGRHHT', '01KCPBSAB0WJQ7V5NPPH888ERZ', 1, 'apakah yang dimaksud', '2025-12-31 11:22:21'),
('01KDSA8Z2RZ1RZ3J09XAW07TQ2', '01KCPBSAB0WJQ7V5NPPH888ERZ', 2, 'jelaskan apa itu', '2025-12-31 11:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_essay_submissions`
--

CREATE TABLE `pbl_essay_submissions` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `essay_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'FK ke pbl_solution_essays.id',
  `user_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'FK ke users.id (siswa)',
  `submission_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade` int DEFAULT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_essay_submissions`
--

INSERT INTO `pbl_essay_submissions` (`id`, `essay_id`, `user_id`, `submission_content`, `grade`, `feedback`, `created_at`, `updated_at`) VALUES
('01KDSARSR78GHD1Z8WYKKS6659', '01KCPBSAB0WJQ7V5NPPH888ERZ', '01K976AHZGDA70DMQ7M9MF6SHS', '1. sesuatu\r\n2. jadi proses', NULL, NULL, '2025-12-31 11:30:59', '2025-12-31 11:30:59');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_final_results`
--

CREATE TABLE `pbl_final_results` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'FK ke users.id (siswa)',
  `final_score` int DEFAULT '0' COMMENT 'Nilai Akhir (0-100)',
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Refleksi/Penguatan dari Guru',
  `status` enum('draft','published') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pbl_observation_results`
--

CREATE TABLE `pbl_observation_results` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `observation_slot_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int NOT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_observation_results`
--

INSERT INTO `pbl_observation_results` (`id`, `observation_slot_id`, `user_id`, `score`, `feedback`, `created_at`) VALUES
('01KDN7HJAEW4CY7XKRQ33R9RZJ', '01KCP97PECMFVK0GAGQP9WXDH4', '01K976AHZGDA70DMQ7M9MF6SHS', 88, 'ok', '2025-12-29 21:17:39');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_observation_slots`
--

CREATE TABLE `pbl_observation_slots` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_observation_slots`
--

INSERT INTO `pbl_observation_slots` (`id`, `class_id`, `title`, `description`, `created_at`) VALUES
('01KBM94F1QRMBHN3Z7J6TZ5PCN', '01KB6S6G7FHD6ZYZP8BKNNYBFF', 'test', 'test', '2025-12-04 15:54:49'),
('01KCP97PECMFVK0GAGQP9WXDH4', '01KB7B61CNTD2D91YRZ6P1GVCF', 'Observasi pertama', 'Observasi lingkungan di SDN Pantai Hurip 02', '2025-12-17 20:50:45');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_observation_uploads`
--

CREATE TABLE `pbl_observation_uploads` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `observation_slot_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID Siswa',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_observation_uploads`
--

INSERT INTO `pbl_observation_uploads` (`id`, `observation_slot_id`, `user_id`, `file_name`, `original_name`, `description`, `created_at`) VALUES
('01KDN7DKKJR08ZRDVBHACPE34P', '01KCP97PECMFVK0GAGQP9WXDH4', '01K976AHZGDA70DMQ7M9MF6SHS', '37398e79cf24d61797834c6b6d151f44.pdf', '01KCRDVHDK4EHXYEDEZ7E4MFPE.pdf', '', '2025-12-29 21:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_orientasi`
--

CREATE TABLE `pbl_orientasi` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reflection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_orientasi`
--

INSERT INTO `pbl_orientasi` (`id`, `class_id`, `title`, `reflection`, `file_path`, `created_at`) VALUES
('01KBH01DMKF6VGRXJJ6F1DE5TB', '01KB6S6G7FHD6ZYZP8BKNNYBFF', 'test', 'test', 'uploads/pbl/01KCKM5GXJ0KNXQ9AYVABAD50C.pdf', '2025-12-03 09:18:08'),
('01KCN7E22Y0P7WYBFQPM4XJVKZ', '01KB6S6G7FHD6ZYZP8BKNNYBFF', 'materi bahasa indonesia', 'materi pertemuan 1', 'uploads/pbl/01KCN9QQ6EZ1EJMB25ZMRN7573.png', '2025-12-17 11:00:02'),
('01KCNY7QZRZ8V4PFPNPHFWB5PX', '01KB7B61CNTD2D91YRZ6P1GVCF', 'Materi PKM ', 'Belajar kebaikan dari hal kecil untuk sekitar dan Indonesia', 'uploads/pbl/01KCQAHGPE0Z9CK1G08MDCBQJG.pdf', '2025-12-17 17:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_quizzes`
--

CREATE TABLE `pbl_quizzes` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_quizzes`
--

INSERT INTO `pbl_quizzes` (`id`, `class_id`, `title`, `description`, `created_at`) VALUES
('01KBH0DPH9HD8DEV3N7T3XQQN1', '01KB6S6G7FHD6ZYZP8BKNNYBFF', 'test', 'test', '2025-12-03 09:24:51'),
('01KCNW8BGR9HC709KH6M7SDZJH', '01KB7B61CNTD2D91YRZ6P1GVCF', 'Kuis Matemetika', 'kuis operasi bilangan', '2025-12-17 17:03:55');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_quiz_answers`
--

CREATE TABLE `pbl_quiz_answers` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `result_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `selected_option` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_quiz_answers`
--

INSERT INTO `pbl_quiz_answers` (`id`, `result_id`, `question_id`, `selected_option`, `is_correct`, `created_at`) VALUES
('01KCKSE1CFHSZWD1YB6S6NRESD', '01KCKSE1CFYS36003XHZXJ181J', '01KBKK9A955KEQB14ZXVB7M0XP', 'C', 1, '2025-12-16 21:36:07'),
('01KCKSE1CFCC0QC2CE01B3N9ER', '01KCKSE1CFYS36003XHZXJ181J', '01KBKK9A956DA39N3BTY2JTAGW', 'C', 1, '2025-12-16 21:36:07'),
('01KCP9569ZYTMFKT2WW3NR0BFX', '01KCP9569ZCHVC34QE313N25R2', '01KCP8W65B3MXHP26TS671B8TH', 'C', 1, '2025-12-17 20:49:23'),
('01KCP9569ZB4H33KH6Y1E29GHC', '01KCP9569ZCHVC34QE313N25R2', '01KCP8W65BTHN88Y9QW8PXEAME', 'C', 1, '2025-12-17 20:49:23'),
('01KCPCR97DQ17VC3SE52W30V5N', '01KCPCR97DTQ4FCRYPG7W7FZSJ', '01KCP8W65B3MXHP26TS671B8TH', 'C', 1, '2025-12-17 21:52:15'),
('01KCPCR97DHW80KR9EXHGGZ1Y7', '01KCPCR97DTQ4FCRYPG7W7FZSJ', '01KCP8W65BTHN88Y9QW8PXEAME', 'C', 1, '2025-12-17 21:52:15'),
('01KCQX46503WZ93RJCNKRC2YMY', '01KCQX4650QV4KQMJ64454MN87', '01KCQW7MTPTBQEQ5QDGXFDBZWZ', 'A', 1, '2025-12-18 11:57:36'),
('01KCQX59NJ0C0GFJ158TNFYKFX', '01KCQX59NJ70YE0NHA20JHV02Z', '01KCP8W65B3MXHP26TS671B8TH', 'A', 0, '2025-12-18 11:58:13'),
('01KCQX59NJA3D30A9TGGCWNW5R', '01KCQX59NJ70YE0NHA20JHV02Z', '01KCP8W65BTHN88Y9QW8PXEAME', 'B', 1, '2025-12-18 11:58:13'),
('01KCQX7MS1NRBFHJBT1YV23FSK', '01KCQX7MS1KYPDQGV2DH4DXVMD', '01KCQW7MTPTBQEQ5QDGXFDBZWZ', 'A', 1, '2025-12-18 11:59:30');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_quiz_questions`
--

CREATE TABLE `pbl_quiz_questions` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_a` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_b` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_c` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_d` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_answer` enum('A','B','C','D') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_quiz_questions`
--

INSERT INTO `pbl_quiz_questions` (`id`, `quiz_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `created_at`) VALUES
('01KBKK9A955KEQB14ZXVB7M0XP', '01KBH0DPH9HD8DEV3N7T3XQQN1', '1+1=', '1', '0', '2', '3', 'C', '2025-12-04 09:32:59'),
('01KBKK9A956DA39N3BTY2JTAGW', '01KBH0DPH9HD8DEV3N7T3XQQN1', 'siapa', 'Saya', 'Aku', 'Dia', 'Kamu', 'C', '2025-12-04 09:32:59'),
('01KCP8W65B3MXHP26TS671B8TH', '01KCNW8BGR9HC709KH6M7SDZJH', '(5 - 4) + 2', '1', '2', '3', '4', 'C', '2025-12-17 20:44:28'),
('01KCP8W65BTHN88Y9QW8PXEAME', '01KCNW8BGR9HC709KH6M7SDZJH', '(3 + 7) - 6 ', '3', '4', '5', '6', 'B', '2025-12-17 20:44:28');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_quiz_results`
--

CREATE TABLE `pbl_quiz_results` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int NOT NULL,
  `total_correct` int NOT NULL,
  `total_questions` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_quiz_results`
--

INSERT INTO `pbl_quiz_results` (`id`, `quiz_id`, `user_id`, `score`, `total_correct`, `total_questions`, `created_at`) VALUES
('01KCKSE1CFYS36003XHZXJ181J', '01KBH0DPH9HD8DEV3N7T3XQQN1', '01K912FR1QZHEWJ6MCVK8WEK5V', 100, 2, 2, '2025-12-16 21:36:07'),
('01KCQX4650QV4KQMJ64454MN87', '01KCQVZ4JNK7097M1RHWYVMWK2', '01K976AHZGDA70DMQ7M9MF6SHS', 100, 1, 1, '2025-12-18 11:57:36'),
('01KCQX59NJ70YE0NHA20JHV02Z', '01KCNW8BGR9HC709KH6M7SDZJH', '01K976AHZGDA70DMQ7M9MF6SHS', 50, 1, 2, '2025-12-18 11:58:13'),
('01KCQX7MS1KYPDQGV2DH4DXVMD', '01KCQVZ4JNK7097M1RHWYVMWK2', '01K912FR1QZHEWJ6MCVK8WEK5V', 100, 1, 1, '2025-12-18 11:59:29');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_reflections`
--

CREATE TABLE `pbl_reflections` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Siswa ID',
  `teacher_reflection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `student_feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_reflections`
--

INSERT INTO `pbl_reflections` (`id`, `class_id`, `user_id`, `teacher_reflection`, `student_feedback`, `created_at`, `updated_at`) VALUES
('01KCN2MSA5ZM85XE1Q82TJSWVX', '01KB6S6G7FHD6ZYZP8BKNNYBFF', '01K912FR1QZHEWJ6MCVK8WEK5V', 'cukup baik', 'bagus', '2025-12-17 09:36:20', '2025-12-17 09:36:20'),
('01KCPDGM5084MNRTZVRMEE9CWK', '01KB7B61CNTD2D91YRZ6P1GVCF', '01K976AHZGDA70DMQ7M9MF6SHS', 'cukup baik,', 'terus semangat', '2025-12-17 22:05:32', '2025-12-17 22:06:59'),
('01KCQXHA9ZG7XGWWWNJD2CXE6P', '01KB7B61CNTD2D91YRZ6P1GVCF', '01K912FR1QZHEWJ6MCVK8WEK5V', 'ok', 'semangat', '2025-12-18 12:04:46', '2025-12-18 12:04:46');

-- --------------------------------------------------------

--
-- Table structure for table `pbl_solution_essays`
--

CREATE TABLE `pbl_solution_essays` (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Instruksi/prompt untuk esai',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pbl_solution_essays`
--

INSERT INTO `pbl_solution_essays` (`id`, `class_id`, `title`, `description`, `created_at`) VALUES
('01KBMBYS6A9ZPHATNRX3JH175T', '01KB6S6G7FHD6ZYZP8BKNNYBFF', 'test', 'test', '2025-12-04 16:44:08'),
('01KCPBSAB0WJQ7V5NPPH888ERZ', '01KB7B61CNTD2D91YRZ6P1GVCF', 'Esai pertama', 'Tugas Bahasa Indonesia', '2025-12-17 21:35:20');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
('01K8WA6A9HTVM98RYM1P5ZWNYH', 'Admin'),
('01K8WA6WVXEKX7JK822G9PVZG9', 'Guru'),
('01K8WA74MMB7VBRM1Y05NS7GNQ', 'Siswa'),
('01K8WA7CX41SY2BEDRT2QBXQ7Q', 'Tamu');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `class_id`, `created_at`) VALUES
('01KCP92PV5YWRY19QC05GM1XXP', '01K976AHZGDA70DMQ7M9MF6SHS', '01KB7B61CNTD2D91YRZ6P1GVCF', '2025-12-17 20:48:02'),
('01KCQV300A2GVRF22949HGKZC3', '01K94KA9TRKC5ZEAPM3PRKVP9S', '01KB6S6G7FHD6ZYZP8BKNNYBFF', '2025-12-18 11:22:00'),
('01KCQX6V56QTZG2TCEKFMM5S23', '01K912FR1QZHEWJ6MCVK8WEK5V', '01KB7B61CNTD2D91YRZ6P1GVCF', '2025-12-18 11:59:03');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `created_at`) VALUES
('01KB4C6WZDNXRWB3SMFXSZ9NT1', '01KB4C6WR9SGY6RMQK5HAVA1AB', '2025-11-28 11:40:43'),
('01KB7B41WP5AAA8MZ0TVADNRF8', '01KB7B41P3TQC2JSDWFMEZTSE8', '2025-11-29 15:19:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '01K8WA74MMB7VBRM1Y05NS7GNQ',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'foto.jpg',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role_id`, `name`, `email`, `image`, `is_active`, `created_at`) VALUES
('01K8WAF2VCSHCNQYZQNDQ0K806', 'Admin', '$2y$10$ZBicPw.RXfH2mZVnD.IHruqGGg9S8pVR/cQWGOnujiryKogfnqakq', '01K8WA6A9HTVM98RYM1P5ZWNYH', 'adm', 'admin@example.com', 'foto.jpg', 1, '2025-10-31 12:04:55'),
('01K8WTRAA9YN933F4BJ2NXXNKQ', 'guru', '$2y$10$H3S1k38s5/ItrsR.6fOKI.4z74dJtcmHe/AUts9cee6T6rXYkpWJy', '01K8WA6WVXEKX7JK822G9PVZG9', 'guru_ipas', 'guru_ipas@example.com', 'foto.jpg', 1, '2025-10-31 16:49:35'),
('01K912FR1QZHEWJ6MCVK8WEK5V', 'sulastri', '$2y$10$Sl7f2LZh5aRqpR1HwsGlwumimlhdRlWVrXBBCu6QlRbz8OA7APJbK', '01K8WA74MMB7VBRM1Y05NS7GNQ', 'Sulastri', 'sulastri6@email.id', 'foto.jpg', 1, '2025-11-02 08:21:41'),
('01K94KA9TRKC5ZEAPM3PRKVP9S', 'herman', '$2y$10$U/opFpM538ZKLQf2OSF.3evyqEERt/bA4bsxaDsWL4nFqQi1SlRIe', '01K8WA74MMB7VBRM1Y05NS7GNQ', 'Herman', 'herman6@email.id', 'foto.jpg', 1, '2025-11-03 17:13:31'),
('01K976AHZGDA70DMQ7M9MF6SHS', 'mujaki', '$2y$10$QpYl4IuqPUX1JXXgnhoN4OBg.nPy5Ra/9rPmEvtURp7UtkRcXqh/G', '01K8WA74MMB7VBRM1Y05NS7GNQ', 'Mujaki', 'Mujaki6@email.id', 'foto.jpg', 1, '2025-11-04 17:24:11'),
('01KB4C6WR9SGY6RMQK5HAVA1AB', 'siti_jainabun', '$2y$10$fHr8e70PrRoJ98su4XNBFebJ6xhM02gUCroXw5wc8gv9Fstng.nrG', '01K8WA6WVXEKX7JK822G9PVZG9', 'Ibu SITI JAINABUN', 'siti_jainabun@email.id', 'foto.jpg', 1, '2025-11-28 11:40:43'),
('01KB7B41P3TQC2JSDWFMEZTSE8', 'juhaeiriah', '$2y$10$HgUDzfFbppAUNhtwBEe7Zu0JQJ/KNSUueEzQpHafnPAJltjpj/Zy.', '01K8WA6WVXEKX7JK822G9PVZG9', 'Ibu Juhaeiriah', 'juhaeiriah@email.id', 'foto.jpg', 1, '2025-11-29 15:19:25');

-- --------------------------------------------------------

--
-- Table structure for table `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `id` int NOT NULL,
  `role_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_access_menu`
--

INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(1, '01K8WA6A9HTVM98RYM1P5ZWNYH', 1),
(2, '01K8WA6A9HTVM98RYM1P5ZWNYH', 2),
(3, '01K8WA6A9HTVM98RYM1P5ZWNYH', 3),
(4, '01K8WA6A9HTVM98RYM1P5ZWNYH', 4),
(5, '01K8WA6WVXEKX7JK822G9PVZG9', 2),
(6, '01K8WA74MMB7VBRM1Y05NS7GNQ', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `id` int NOT NULL,
  `menu` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`id`, `menu`) VALUES
(1, 'Admin'),
(2, 'Guru'),
(3, 'Siswa'),
(4, 'Menu');

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `id` int NOT NULL,
  `menu_id` int NOT NULL,
  `title` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon`, `is_active`) VALUES
(1, 1, 'Dashboard Admin', 'admin/dashboard', 'bi-grid', 0),
(3, 1, 'Kelola Guru', 'admin/dashboard/teachers', 'bi-person', 1),
(4, 1, 'Kelola Murid', 'admin/dashboard/students', 'bi-people', 1),
(5, 4, 'Kelola Menu', 'menu', 'bi-folder', 1),
(6, 4, 'Kelola Submenu', 'menu/submenu', 'bi-folder2-open', 1),
(10, 2, 'Dashboard Guru', 'guru/dashboard', 'bi-grid', 1),
(11, 3, 'Dashboard Siswa', 'siswa/dashboard', 'bi-grid', 1),
(12, 1, 'Kelola Kelas', 'admin/dashboard/classes', 'bi-easel', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`exam_id`);

--
-- Indexes for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pbl_essay_questions`
--
ALTER TABLE `pbl_essay_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_question_essay` (`essay_id`);

--
-- Indexes for table `pbl_essay_submissions`
--
ALTER TABLE `pbl_essay_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `essay_id` (`essay_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pbl_observation_slots`
--
ALTER TABLE `pbl_observation_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `pbl_observation_uploads`
--
ALTER TABLE `pbl_observation_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `observation_slot_id` (`observation_slot_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pbl_quizzes`
--
ALTER TABLE `pbl_quizzes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pbl_quiz_questions`
--
ALTER TABLE `pbl_quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `pbl_quiz_results`
--
ALTER TABLE `pbl_quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pbl_solution_essays`
--
ALTER TABLE `pbl_solution_essays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pbl_essay_questions`
--
ALTER TABLE `pbl_essay_questions`
  ADD CONSTRAINT `fk_question_essay` FOREIGN KEY (`essay_id`) REFERENCES `pbl_solution_essays` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pbl_essay_submissions`
--
ALTER TABLE `pbl_essay_submissions`
  ADD CONSTRAINT `pbl_essay_submissions_ibfk_1` FOREIGN KEY (`essay_id`) REFERENCES `pbl_solution_essays` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pbl_observation_uploads`
--
ALTER TABLE `pbl_observation_uploads`
  ADD CONSTRAINT `fk_obs_slot` FOREIGN KEY (`observation_slot_id`) REFERENCES `pbl_observation_slots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pbl_quiz_questions`
--
ALTER TABLE `pbl_quiz_questions`
  ADD CONSTRAINT `pbl_quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `pbl_quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD CONSTRAINT `user_access_menu_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_access_menu_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `user_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
