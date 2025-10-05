-- PDPA Assessment System Database Schema
-- Create database and tables

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: pdpa_db

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `organization` varchar(200) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
-- Default admin user (password: admin123)
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `organization`, `role`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'System Administrator', 'PDPA System', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_categories`
--

CREATE TABLE IF NOT EXISTS `assessment_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `name_en` varchar(200) DEFAULT NULL,
  `description` text,
  `category_type` enum('pdpa','cyber') NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_categories`
--

INSERT INTO `assessment_categories` (`id`, `name`, `name_en`, `description`, `category_type`, `display_order`) VALUES
(1, 'การจัดการข้อมูลส่วนบุคคล', 'Personal Data Management', 'การจัดเก็บ ใช้ และเปิดเผยข้อมูลส่วนบุคคล', 'pdpa', 1),
(2, 'สิทธิของเจ้าของข้อมูล', 'Data Subject Rights', 'การจัดการสิทธิของเจ้าของข้อมูลส่วนบุคคล', 'pdpa', 2),
(3, 'มาตรการรักษาความปลอดภัย', 'Security Measures', 'มาตรการป้องกันและรักษาความปลอดภัยข้อมูล', 'pdpa', 3),
(4, 'การแจ้งเหตุละเมิดข้อมูล', 'Data Breach Notification', 'การแจ้งเหตุละเมิดข้อมูลส่วนบุคคล', 'pdpa', 4),
(5, 'การประเมินผลกระทบ', 'Privacy Impact Assessment', 'การประเมินผลกระทบด้านความเป็นส่วนตัว', 'pdpa', 5),
(6, 'ความมั่นคงปลอดภัยทางไซเบอร์', 'Cybersecurity', 'มาตรการรักษาความมั่นคงปลอดภัยไซเบอร์', 'cyber', 1),
(7, 'การตอบสนองต่อภัยคุกคาม', 'Threat Response', 'การจัดการและตอบสนองต่อภัยคุกคามทางไซเบอร์', 'cyber', 2),
(8, 'การสำรองข้อมูล', 'Data Backup', 'การสำรองและกู้คืนข้อมูล', 'cyber', 3);

-- --------------------------------------------------------

--
-- Table structure for table `assessment_questions`
--

CREATE TABLE IF NOT EXISTS `assessment_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `question_en` text,
  `question_type` enum('yes_no','rating','multiple_choice') DEFAULT 'yes_no',
  `weight` decimal(5,2) DEFAULT 1.00,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `assessment_questions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `assessment_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_questions`
--

INSERT INTO `assessment_questions` (`id`, `category_id`, `question`, `question_en`, `question_type`, `weight`, `display_order`) VALUES
-- PDPA Questions
(1, 1, 'องค์กรมีนโยบายการคุ้มครองข้อมูลส่วนบุคคลที่ชัดเจนหรือไม่', 'Does your organization have a clear personal data protection policy?', 'yes_no', 2.00, 1),
(2, 1, 'มีการแต่งตั้งเจ้าหน้าที่คุ้มครองข้อมูลส่วนบุคคล (DPO) หรือไม่', 'Has a Data Protection Officer (DPO) been appointed?', 'yes_no', 2.00, 2),
(3, 1, 'มีการจัดทำบันทึกกิจกรรมการประมวลผลข้อมูลส่วนบุคคลหรือไม่', 'Are records of personal data processing activities maintained?', 'yes_no', 1.50, 3),
(4, 1, 'มีการแจ้งวัตถุประสงค์ในการเก็บข้อมูลต่อเจ้าของข้อมูลหรือไม่', 'Are data subjects informed about the purpose of data collection?', 'yes_no', 1.50, 4),
(5, 2, 'มีกระบวนการรับคำขอใช้สิทธิของเจ้าของข้อมูลหรือไม่', 'Is there a process for handling data subject rights requests?', 'yes_no', 1.50, 1),
(6, 2, 'สามารถให้เจ้าของข้อมูลเข้าถึงข้อมูลของตนเองได้หรือไม่', 'Can data subjects access their own data?', 'yes_no', 1.50, 2),
(7, 2, 'มีกระบวนการลบหรือทำลายข้อมูลตามคำขอหรือไม่', 'Is there a process for data deletion upon request?', 'yes_no', 1.00, 3),
(8, 3, 'มีมาตรการรักษาความปลอดภัยข้อมูลทางเทคนิคหรือไม่', 'Are technical security measures in place?', 'yes_no', 2.00, 1),
(9, 3, 'มีการเข้ารหัสข้อมูลส่วนบุคคลที่ละเอียดอ่อนหรือไม่', 'Is sensitive personal data encrypted?', 'yes_no', 2.00, 2),
(10, 3, 'มีการควบคุมการเข้าถึงข้อมูลส่วนบุคคลหรือไม่', 'Are access controls implemented for personal data?', 'yes_no', 1.50, 3),
(11, 4, 'มีแผนรับมือเหตุละเมิดข้อมูลส่วนบุคคลหรือไม่', 'Is there a data breach response plan?', 'yes_no', 1.50, 1),
(12, 4, 'มีกระบวนการแจ้งเหตุละเมิดต่อสำนักงาน กคช. หรือไม่', 'Is there a process for notifying PDPC about breaches?', 'yes_no', 2.00, 2),
(13, 5, 'มีการทำ Privacy Impact Assessment (PIA) หรือไม่', 'Are Privacy Impact Assessments (PIA) conducted?', 'yes_no', 1.50, 1),
(14, 5, 'มีการทบทวนความเสี่ยงด้านความเป็นส่วนตัวเป็นประจำหรือไม่', 'Are privacy risks reviewed regularly?', 'yes_no', 1.00, 2),
-- Cyber Law Questions
(15, 6, 'มีนโยบายความมั่นคงปลอดภัยไซเบอร์หรือไม่', 'Is there a cybersecurity policy?', 'yes_no', 2.00, 1),
(16, 6, 'มีการติดตั้งและอัปเดต Firewall หรือไม่', 'Are firewalls installed and updated?', 'yes_no', 1.50, 2),
(17, 6, 'มีการติดตั้งและอัปเดต Antivirus/Antimalware หรือไม่', 'Are antivirus/antimalware solutions installed and updated?', 'yes_no', 1.50, 3),
(18, 6, 'มีการจำกัดสิทธิการเข้าถึงระบบตามหน้าที่หรือไม่', 'Are system access rights limited based on roles?', 'yes_no', 1.50, 4),
(19, 7, 'มีทีมตอบสนองต่อเหตุการณ์ทางไซเบอร์หรือไม่', 'Is there a cyber incident response team?', 'yes_no', 2.00, 1),
(20, 7, 'มีการฝึกซ้อมรับมือภัยคุกคามทางไซเบอร์หรือไม่', 'Are cybersecurity drills conducted?', 'yes_no', 1.00, 2),
(21, 8, 'มีการสำรองข้อมูลอย่างสม่ำเสมอหรือไม่', 'Are regular data backups performed?', 'yes_no', 2.00, 1),
(22, 8, 'มีการทดสอบการกู้คืนข้อมูลเป็นระยะหรือไม่', 'Are data recovery procedures tested regularly?', 'yes_no', 1.50, 2);

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE IF NOT EXISTS `assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `assessment_type` enum('pdpa','cyber','both') DEFAULT 'both',
  `status` enum('in_progress','completed') DEFAULT 'in_progress',
  `started_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` timestamp NULL DEFAULT NULL,
  `total_score` decimal(5,2) DEFAULT 0.00,
  `max_score` decimal(5,2) DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_answers`
--

CREATE TABLE IF NOT EXISTS `assessment_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT 0.00,
  `answered_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assessment_id` (`assessment_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `assessment_answers_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessment_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `assessment_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

COMMIT;
