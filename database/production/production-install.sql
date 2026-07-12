-- ═══════════════════════════════════════════════════════════════════════
-- 🏥 SIMRS Hospital — Production Database Installer
-- ═══════════════════════════════════════════════════════════════════════
-- Cara pakai:
--   1. Buat database kosong di server (mis: simrs_prod)
--   2. Import file ini via phpMyAdmin atau CLI:
--      mysql -u USERNAME -p NAMA_DATABASE < production-install.sql
--   3. Login: admin@hospital.test / password (GANTI segera!)
-- ═══════════════════════════════════════════════════════════════════════

SET NAMES utf8mb4;
SET TIME_ZONE='+07:00';
SET FOREIGN_KEY_CHECKS=0;
SET UNIQUE_CHECKS=0;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ambulance_calls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ambulance_id` bigint unsigned NOT NULL,
  `patient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pickup_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_date` datetime NOT NULL,
  `status` enum('pending','dispatched','en_route','arrived','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ambulance_calls_ambulance_id_foreign` (`ambulance_id`),
  KEY `ambulance_calls_status_index` (`status`),
  CONSTRAINT `ambulance_calls_ambulance_id_foreign` FOREIGN KEY (`ambulance_id`) REFERENCES `ambulances` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1621 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ambulances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','on_duty','maintenance','out_of_service') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `driver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ambulances_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anc_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `midwife_id` bigint unsigned NOT NULL,
  `visit_number` int NOT NULL,
  `visit_date` date NOT NULL,
  `gestational_age` int DEFAULT NULL,
  `weight` decimal(5,1) DEFAULT NULL,
  `blood_pressure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fundal_height` decimal(5,1) DEFAULT NULL,
  `fetal_heart_rate` int DEFAULT NULL,
  `fetal_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hemoglobin` decimal(4,1) DEFAULT NULL,
  `urine_protein` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tetanus_immunization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fe_tablets` int DEFAULT NULL,
  `complaints` text COLLATE utf8mb4_unicode_ci,
  `risk_score` int DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anc_records_midwife_id_foreign` (`midwife_id`),
  KEY `anc_records_patient_id_visit_date_index` (`patient_id`,`visit_date`),
  CONSTRAINT `anc_records_midwife_id_foreign` FOREIGN KEY (`midwife_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anc_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `polyclinic_id` bigint unsigned DEFAULT NULL,
  `treatment_id` bigint unsigned DEFAULT NULL,
  `appointment_date` datetime NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` enum('scheduled','confirmed','in_progress','completed','cancelled','no_show') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `complaint` text COLLATE utf8mb4_unicode_ci COMMENT 'Keluhan pasien',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `reminders` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_treatment_id_foreign` (`treatment_id`),
  KEY `appointments_doctor_id_appointment_date_index` (`doctor_id`,`appointment_date`),
  KEY `appointments_patient_id_appointment_date_index` (`patient_id`,`appointment_date`),
  KEY `appointments_polyclinic_id_foreign` (`polyclinic_id`),
  CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_polyclinic_id_foreign` FOREIGN KEY (`polyclinic_id`) REFERENCES `polyclinics` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=36854 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('medical','non_medical','IT','furniture','vehicle','building','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medical',
  `department_id` bigint unsigned DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `condition` enum('excellent','good','fair','poor','broken') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good',
  `status` enum('active','maintenance','disposed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assets_asset_code_unique` (`asset_code`),
  KEY `assets_department_id_foreign` (`department_id`),
  KEY `assets_category_index` (`category`),
  KEY `assets_status_index` (`status`),
  CONSTRAINT `assets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('present','late','absent','sick','leave','half_day') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present' COMMENT 'Status kehadiran',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_user_id_date_unique` (`user_id`,`date`),
  KEY `attendances_user_id_date_index` (`user_id`,`date`),
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `baby_immunizations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `maternity_id` bigint unsigned DEFAULT NULL,
  `vaccine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_date` date NOT NULL,
  `given_date` date DEFAULT NULL,
  `dose_number` int NOT NULL DEFAULT '1',
  `status` enum('scheduled','given','missed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `baby_immunizations_maternity_id_foreign` (`maternity_id`),
  KEY `baby_immunizations_patient_id_scheduled_date_index` (`patient_id`,`scheduled_date`),
  CONSTRAINT `baby_immunizations_maternity_id_foreign` FOREIGN KEY (`maternity_id`) REFERENCES `maternities` (`id`) ON DELETE SET NULL,
  CONSTRAINT `baby_immunizations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blood_donations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned DEFAULT NULL,
  `donor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') COLLATE utf8mb4_unicode_ci NOT NULL,
  `donation_date` datetime NOT NULL,
  `expiry_date` datetime NOT NULL,
  `quantity_ml` int DEFAULT NULL,
  `status` enum('available','used','expired','discarded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blood_donations_blood_type_index` (`blood_type`),
  KEY `blood_donations_status_index` (`status`),
  KEY `blood_donations_patient_id_foreign` (`patient_id`),
  CONSTRAINT `blood_donations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chart_of_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('asset','liability','equity','revenue','expense') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'asset',
  `normal_balance` enum('debit','credit') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'debit',
  `parent_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_of_accounts_account_code_unique` (`account_code`),
  KEY `chart_of_accounts_parent_id_foreign` (`parent_id`),
  CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinical_pathways` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosis_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected_los_days` smallint unsigned DEFAULT NULL,
  `phases` json DEFAULT NULL,
  `inclusion_criteria` text COLLATE utf8mb4_unicode_ci,
  `exclusion_criteria` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clinical_pathways_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `code_blue_activations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_time` datetime NOT NULL,
  `team_arrival_time` datetime DEFAULT NULL,
  `return_circulation_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `outcome` enum('rosc','died','transferred','ongoing') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ongoing',
  `team_leader` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `initial_rhythm` text COLLATE utf8mb4_unicode_ci,
  `interventions` text COLLATE utf8mb4_unicode_ci,
  `medications_given` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_blue_activations_code_no_unique` (`code_no`),
  KEY `code_blue_activations_patient_id_foreign` (`patient_id`),
  KEY `code_blue_activations_outcome_index` (`outcome`),
  CONSTRAINT `code_blue_activations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cost_estimate_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cost_estimate_id` bigint unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int unsigned NOT NULL DEFAULT '1',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(14,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(14,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cost_estimate_items_cost_estimate_id_foreign` (`cost_estimate_id`),
  CONSTRAINT `cost_estimate_items_cost_estimate_id_foreign` FOREIGN KEY (`cost_estimate_id`) REFERENCES `cost_estimates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cost_estimates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `estimate_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `estimate_date` date NOT NULL,
  `procedure_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','sent','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cost_estimates_estimate_no_unique` (`estimate_no`),
  KEY `cost_estimates_patient_id_foreign` (`patient_id`),
  KEY `cost_estimates_doctor_id_foreign` (`doctor_id`),
  KEY `cost_estimates_status_index` (`status`),
  CONSTRAINT `cost_estimates_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cost_estimates_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `floor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diet_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `order_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `diet_type` enum('regular','soft','liquid','puree','tube_feed','parenteral','diabetic','low_salt','low_protein','high_protein','low_fat','gluten_free','custom') COLLATE utf8mb4_unicode_ci NOT NULL,
  `texture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calories` int unsigned DEFAULT NULL,
  `restrictions` json DEFAULT NULL,
  `special_instructions` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','paused','discontinued','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `diet_orders_order_no_unique` (`order_no`),
  KEY `diet_orders_patient_id_foreign` (`patient_id`),
  KEY `diet_orders_doctor_id_foreign` (`doctor_id`),
  KEY `diet_orders_diet_type_index` (`diet_type`),
  KEY `diet_orders_status_index` (`status`),
  CONSTRAINT `diet_orders_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `diet_orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discharge_summaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `summary_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `medical_record_id` bigint unsigned DEFAULT NULL,
  `admission_date` datetime NOT NULL,
  `discharge_date` datetime NOT NULL,
  `admission_diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discharge_diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chief_complaint` text COLLATE utf8mb4_unicode_ci,
  `history` text COLLATE utf8mb4_unicode_ci,
  `physical_exam` text COLLATE utf8mb4_unicode_ci,
  `investigations` text COLLATE utf8mb4_unicode_ci,
  `treatment` text COLLATE utf8mb4_unicode_ci,
  `progress` text COLLATE utf8mb4_unicode_ci,
  `discharge_medication` text COLLATE utf8mb4_unicode_ci,
  `follow_up` text COLLATE utf8mb4_unicode_ci,
  `discharge_condition` enum('recovered','improved','unchanged','worsened','died') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'improved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `discharge_summaries_summary_no_unique` (`summary_no`),
  KEY `discharge_summaries_patient_id_foreign` (`patient_id`),
  KEY `discharge_summaries_doctor_id_foreign` (`doctor_id`),
  KEY `discharge_summaries_medical_record_id_foreign` (`medical_record_id`),
  CONSTRAINT `discharge_summaries_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `discharge_summaries_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE SET NULL,
  CONSTRAINT `discharge_summaries_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_polyclinic` (
  `doctor_id` bigint unsigned NOT NULL,
  `polyclinic_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`doctor_id`,`polyclinic_id`),
  KEY `doctor_polyclinic_polyclinic_id_foreign` (`polyclinic_id`),
  CONSTRAINT `doctor_polyclinic_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `doctor_polyclinic_polyclinic_id_foreign` FOREIGN KEY (`polyclinic_id`) REFERENCES `polyclinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Spesialisasi',
  `str_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor STR',
  `address` text COLLATE utf8mb4_unicode_ci,
  `consultation_fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','inactive','on_leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doctors_user_id_foreign` (`user_id`),
  CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drug_destruction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `drug_destruction_id` bigint unsigned NOT NULL,
  `drug_id` bigint unsigned DEFAULT NULL,
  `drug_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired_at` date DEFAULT NULL,
  `quantity` int unsigned NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drug_destruction_items_drug_destruction_id_foreign` (`drug_destruction_id`),
  KEY `drug_destruction_items_drug_id_foreign` (`drug_id`),
  CONSTRAINT `drug_destruction_items_drug_destruction_id_foreign` FOREIGN KEY (`drug_destruction_id`) REFERENCES `drug_destructions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drug_destruction_items_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drug_destructions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `destruction_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destruction_date` date NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsible_pharmacist` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `witness_name_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `witness_name_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `witness_role_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `witness_role_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `witnessed_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drug_destructions_destruction_no_unique` (`destruction_no`),
  KEY `drug_destructions_created_by_foreign` (`created_by`),
  KEY `drug_destructions_witnessed_by_foreign` (`witnessed_by`),
  CONSTRAINT `drug_destructions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `drug_destructions_witnessed_by_foreign` FOREIGN KEY (`witnessed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drug_supply_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `drug_supply_order_id` bigint unsigned NOT NULL,
  `drug_id` bigint unsigned DEFAULT NULL,
  `drug_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dose_form` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strength` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int unsigned NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drug_supply_order_items_drug_supply_order_id_foreign` (`drug_supply_order_id`),
  KEY `drug_supply_order_items_drug_id_foreign` (`drug_id`),
  CONSTRAINT `drug_supply_order_items_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `drug_supply_order_items_drug_supply_order_id_foreign` FOREIGN KEY (`drug_supply_order_id`) REFERENCES `drug_supply_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drug_supply_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` enum('regular','narcotic','psychotropic','precursor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_date` date NOT NULL,
  `supplier_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_license_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsible_pharmacist` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pharmacist_sipa_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','sent','received','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drug_supply_orders_order_no_unique` (`order_no`),
  KEY `drug_supply_orders_order_type_index` (`order_type`),
  KEY `drug_supply_orders_status_index` (`status`),
  KEY `drug_supply_orders_created_by_foreign` (`created_by`),
  CONSTRAINT `drug_supply_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drugs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Jenis obat: Tablet, Sirup, Salep, Injeksi',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Satuan: Strip, Botol, Ampul',
  `stock` int NOT NULL DEFAULT '0',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emergencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `triage` enum('red','yellow','green','black') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'green',
  `arrival_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complaint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosis` text COLLATE utf8mb4_unicode_ci,
  `action_taken` text COLLATE utf8mb4_unicode_ci,
  `status` enum('waiting','in_treatment','observation','discharged','referred','deceased') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `discharge_date` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `emergencies_patient_id_foreign` (`patient_id`),
  KEY `emergencies_doctor_id_foreign` (`doctor_id`),
  KEY `emergencies_status_index` (`status`),
  KEY `emergencies_triage_index` (`triage`),
  CONSTRAINT `emergencies_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `emergencies_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3613 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'NIP/Nomor Induk Pegawai',
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Jabatan',
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Departemen',
  `join_date` date DEFAULT NULL COMMENT 'Tanggal bergabung',
  `base_salary` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Gaji pokok',
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama bank',
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor rekening',
  `bpjs_tk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'BPJS Ketenagakerjaan',
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'NPWP',
  `employment_status` enum('permanent','contract','probation','intern','resigned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'permanent' COMMENT 'Status kepegawaian',
  `education_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Pendidikan terakhir',
  `emergency_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kontak darurat',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  KEY `employees_user_id_foreign` (`user_id`),
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment_maintenances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint unsigned NOT NULL,
  `scheduled_date` date NOT NULL,
  `performed_date` date DEFAULT NULL,
  `maintenance_type` enum('preventive','corrective','calibration','inspection') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'preventive',
  `performer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `findings` text COLLATE utf8mb4_unicode_ci,
  `action` text COLLATE utf8mb4_unicode_ci,
  `cost` decimal(14,2) NOT NULL DEFAULT '0.00',
  `result` enum('ok','needs_repair','replaced','failed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_due_date` date DEFAULT NULL,
  `status` enum('scheduled','in_progress','done','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipment_maintenances_asset_id_foreign` (`asset_id`),
  KEY `equipment_maintenances_maintenance_type_index` (`maintenance_type`),
  KEY `equipment_maintenances_status_index` (`status`),
  CONSTRAINT `equipment_maintenances_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hospital_beds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `bed_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','occupied','reserved','cleaning','maintenance','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `current_patient_id` bigint unsigned DEFAULT NULL,
  `occupied_since` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hospital_beds_bed_code_unique` (`bed_code`),
  KEY `hospital_beds_room_id_foreign` (`room_id`),
  KEY `hospital_beds_current_patient_id_foreign` (`current_patient_id`),
  KEY `hospital_beds_status_index` (`status`),
  CONSTRAINT `hospital_beds_current_patient_id_foreign` FOREIGN KEY (`current_patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hospital_beds_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `icu_monitorings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `hospital_bed_id` bigint unsigned DEFAULT NULL,
  `recorded_at` datetime NOT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `hr` smallint unsigned DEFAULT NULL,
  `rr` smallint unsigned DEFAULT NULL,
  `sbp` smallint unsigned DEFAULT NULL,
  `dbp` smallint unsigned DEFAULT NULL,
  `map` smallint unsigned DEFAULT NULL,
  `spo2` tinyint unsigned DEFAULT NULL,
  `gcs` tinyint unsigned DEFAULT NULL,
  `cvp` decimal(5,2) DEFAULT NULL,
  `ventilator` json DEFAULT NULL,
  `drips` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `icu_monitorings_hospital_bed_id_foreign` (`hospital_bed_id`),
  KEY `icu_monitorings_patient_id_recorded_at_index` (`patient_id`,`recorded_at`),
  CONSTRAINT `icu_monitorings_hospital_bed_id_foreign` FOREIGN KEY (`hospital_bed_id`) REFERENCES `hospital_beds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `icu_monitorings_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `infection_surveillances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `case_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `infection_type` enum('vap','clabsi','cauti','ssi','phlebitis','decubitus','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `detection_date` date NOT NULL,
  `onset_date` date DEFAULT NULL,
  `site` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organism` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symptoms` text COLLATE utf8mb4_unicode_ci,
  `antibiotic_therapy` text COLLATE utf8mb4_unicode_ci,
  `intervention` text COLLATE utf8mb4_unicode_ci,
  `outcome` enum('resolved','ongoing','died') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ongoing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `infection_surveillances_case_no_unique` (`case_no`),
  KEY `infection_surveillances_patient_id_foreign` (`patient_id`),
  KEY `infection_surveillances_infection_type_index` (`infection_type`),
  CONSTRAINT `infection_surveillances_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `informed_consents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consent_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kind` enum('consent','refusal','aps') COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `procedure_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `procedure_description` text COLLATE utf8mb4_unicode_ci,
  `risks` text COLLATE utf8mb4_unicode_ci,
  `alternatives` text COLLATE utf8mb4_unicode_ci,
  `signed_by_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_by_relation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `witness_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `informed_consents_consent_no_unique` (`consent_no`),
  KEY `informed_consents_patient_id_foreign` (`patient_id`),
  KEY `informed_consents_doctor_id_foreign` (`doctor_id`),
  KEY `informed_consents_kind_index` (`kind`),
  CONSTRAINT `informed_consents_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `informed_consents_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `insurance_claims` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `claim_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `payment_id` bigint unsigned DEFAULT NULL,
  `insurance_provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `policy_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `claim_type` enum('outpatient','inpatient','emergency','maternity') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'outpatient',
  `service_date` date NOT NULL,
  `claim_date` date NOT NULL,
  `diagnosis_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diagnosis_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `claimed_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `approved_amount` decimal(14,2) DEFAULT NULL,
  `status` enum('draft','submitted','approved','rejected','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `insurance_claims_claim_no_unique` (`claim_no`),
  KEY `insurance_claims_patient_id_foreign` (`patient_id`),
  KEY `insurance_claims_payment_id_foreign` (`payment_id`),
  KEY `insurance_claims_status_index` (`status`),
  CONSTRAINT `insurance_claims_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `insurance_claims_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `journal_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_debit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_credit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','posted','voided') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `posted_by` bigint unsigned DEFAULT NULL,
  `posted_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_entries_journal_number_unique` (`journal_number`),
  KEY `journal_entries_posted_by_foreign` (`posted_by`),
  KEY `journal_entries_entry_date_index` (`entry_date`),
  KEY `journal_entries_status_index` (`status`),
  CONSTRAINT `journal_entries_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_entry_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `journal_entry_id` bigint unsigned NOT NULL,
  `account_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `debit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_entry_lines_journal_entry_id_foreign` (`journal_entry_id`),
  KEY `journal_entry_lines_account_id_foreign` (`account_id`),
  CONSTRAINT `journal_entry_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `journal_entry_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lab_tests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `test_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sample_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('requested','sample_collected','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'requested',
  `results` text COLLATE utf8mb4_unicode_ci,
  `result_date` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `medical_record_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lab_tests_patient_id_index` (`patient_id`),
  KEY `lab_tests_status_index` (`status`),
  KEY `lab_tests_doctor_id_foreign` (`doctor_id`),
  KEY `lab_tests_appointment_id_foreign` (`appointment_id`),
  KEY `lab_tests_medical_record_id_foreign` (`medical_record_id`),
  CONSTRAINT `lab_tests_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lab_tests_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lab_tests_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lab_tests_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leaves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `leave_type` enum('annual','sick','maternity','paternity','unpaid','important_reason') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'annual',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leaves_approved_by_foreign` (`approved_by`),
  KEY `leaves_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maternities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `admission_date` datetime NOT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `delivery_type` enum('normal','caesar','vacuum','forceps') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `baby_gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `baby_weight` decimal(5,2) DEFAULT NULL,
  `baby_length` decimal(4,1) DEFAULT NULL,
  `baby_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complications` text COLLATE utf8mb4_unicode_ci,
  `status` enum('admitted','in_labor','delivered','postpartum','discharged') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admitted',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maternities_patient_id_index` (`patient_id`),
  KEY `maternities_status_index` (`status`),
  KEY `maternities_doctor_id_foreign` (`doctor_id`),
  CONSTRAINT `maternities_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `maternities_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medical_certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cert_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('sick_leave','healthy','drug_free','pregnancy','not_pregnancy','birth','death','visum','color_blind_free','medical_check_up') COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `medical_record_id` bigint unsigned DEFAULT NULL,
  `issue_date` date NOT NULL,
  `rest_from` date DEFAULT NULL,
  `rest_until` date DEFAULT NULL,
  `rest_days` smallint unsigned DEFAULT NULL,
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exam_data` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','issued','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'issued',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `medical_certificates_cert_no_unique` (`cert_no`),
  KEY `medical_certificates_patient_id_foreign` (`patient_id`),
  KEY `medical_certificates_doctor_id_foreign` (`doctor_id`),
  KEY `medical_certificates_medical_record_id_foreign` (`medical_record_id`),
  KEY `medical_certificates_type_index` (`type`),
  KEY `medical_certificates_status_index` (`status`),
  CONSTRAINT `medical_certificates_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_certificates_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_certificates_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medical_record_treatments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `medical_record_id` bigint unsigned NOT NULL,
  `treatment_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mr_treatment_unique` (`medical_record_id`,`treatment_id`),
  KEY `medical_record_treatments_treatment_id_index` (`treatment_id`),
  CONSTRAINT `medical_record_treatments_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE,
  CONSTRAINT `medical_record_treatments_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medical_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `clinical_pathway_id` bigint unsigned DEFAULT NULL,
  `diagnosis` text COLLATE utf8mb4_unicode_ci,
  `action` text COLLATE utf8mb4_unicode_ci,
  `medicine` text COLLATE utf8mb4_unicode_ci COMMENT 'Resep obat',
  `vital_signs` json DEFAULT NULL COMMENT 'Tanda vital: BP, HR, RR, temp',
  `lab_results` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan follow-up',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_records_patient_id_doctor_id_index` (`patient_id`,`doctor_id`),
  KEY `idx_medical_records_appointment_id` (`appointment_id`),
  KEY `medical_records_doctor_id_foreign` (`doctor_id`),
  KEY `medical_records_clinical_pathway_id_foreign` (`clinical_pathway_id`),
  CONSTRAINT `medical_records_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_records_clinical_pathway_id_foreign` FOREIGN KEY (`clinical_pathway_id`) REFERENCES `clinical_pathways` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_records_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4640 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medication_administrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `nurse_id` bigint unsigned DEFAULT NULL,
  `drug_id` bigint unsigned DEFAULT NULL,
  `drug_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route` enum('oral','IV','IM','SC','topical','inhalation','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'oral',
  `administered_at` datetime NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medication_administrations_nurse_id_foreign` (`nurse_id`),
  KEY `medication_administrations_drug_id_foreign` (`drug_id`),
  KEY `medication_administrations_patient_id_administered_at_index` (`patient_id`,`administered_at`),
  CONSTRAINT `medication_administrations_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medication_administrations_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medication_administrations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nurse_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `assignment_type` enum('nurse','midwife') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nurse',
  `task_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift` enum('pagi','siang','malam') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pagi',
  `status` enum('pending','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nurse_assignments_user_id_index` (`user_id`),
  KEY `nurse_assignments_patient_id_index` (`patient_id`),
  KEY `nurse_assignments_status_index` (`status`),
  CONSTRAINT `nurse_assignments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nurse_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nursing_cares` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `nurse_id` bigint unsigned NOT NULL,
  `assessment_date` datetime NOT NULL,
  `subjective_data` text COLLATE utf8mb4_unicode_ci,
  `objective_data` text COLLATE utf8mb4_unicode_ci,
  `nursing_diagnosis` text COLLATE utf8mb4_unicode_ci,
  `nursing_plan` text COLLATE utf8mb4_unicode_ci,
  `nursing_action` text COLLATE utf8mb4_unicode_ci,
  `evaluation` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nursing_cares_nurse_id_foreign` (`nurse_id`),
  KEY `nursing_cares_patient_id_status_index` (`patient_id`,`status`),
  KEY `nursing_cares_appointment_id_foreign` (`appointment_id`),
  CONSTRAINT `nursing_cares_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `nursing_cares_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nursing_cares_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `odontograms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `exam_date` date NOT NULL,
  `teeth_state` json DEFAULT NULL,
  `general_findings` text COLLATE utf8mb4_unicode_ci,
  `treatment_plan` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `odontograms_patient_id_foreign` (`patient_id`),
  KEY `odontograms_doctor_id_foreign` (`doctor_id`),
  CONSTRAINT `odontograms_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `odontograms_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `meta` json DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_contents_section_unique` (`section`),
  KEY `page_contents_section_index` (`section`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partographs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `maternity_id` bigint unsigned NOT NULL,
  `recorded_at` datetime NOT NULL,
  `cervical_dilation` decimal(3,1) DEFAULT NULL,
  `fetal_head_descent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contractions_per_10min` int DEFAULT NULL,
  `contraction_duration` int DEFAULT NULL,
  `maternal_pulse` int DEFAULT NULL,
  `blood_pressure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `urine_output` int DEFAULT NULL,
  `amniotic_fluid` enum('intact','ruptured_clear','ruptured_meconium','ruptured_blood') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oxytocin_drops` int DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partographs_maternity_id_recorded_at_index` (`maternity_id`,`recorded_at`),
  CONSTRAINT `partographs_maternity_id_foreign` FOREIGN KEY (`maternity_id`) REFERENCES `maternities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_feedbacks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating_overall` tinyint unsigned DEFAULT NULL,
  `rating_doctor` tinyint unsigned DEFAULT NULL,
  `rating_nurse` tinyint unsigned DEFAULT NULL,
  `rating_facility` tinyint unsigned DEFAULT NULL,
  `rating_cleanliness` tinyint unsigned DEFAULT NULL,
  `rating_speed` tinyint unsigned DEFAULT NULL,
  `would_recommend` tinyint(1) DEFAULT NULL,
  `positive` text COLLATE utf8mb4_unicode_ci,
  `negative` text COLLATE utf8mb4_unicode_ci,
  `suggestion` text COLLATE utf8mb4_unicode_ci,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `respondent_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `respondent_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('new','reviewed','responded','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_feedbacks_patient_id_foreign` (`patient_id`),
  KEY `patient_feedbacks_status_index` (`status`),
  CONSTRAINT `patient_feedbacks_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_safety_incidents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `incident_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `incident_type` enum('knc','ktc','ktd','kpc','sentinel') COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` enum('none','minor','moderate','major','catastrophic') COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `reporter_id` bigint unsigned DEFAULT NULL,
  `occurred_at` datetime NOT NULL,
  `reported_at` datetime NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `immediate_action` text COLLATE utf8mb4_unicode_ci,
  `root_cause` text COLLATE utf8mb4_unicode_ci,
  `corrective_action` text COLLATE utf8mb4_unicode_ci,
  `status` enum('reported','investigating','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reported',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_safety_incidents_incident_no_unique` (`incident_no`),
  KEY `patient_safety_incidents_patient_id_foreign` (`patient_id`),
  KEY `patient_safety_incidents_reporter_id_foreign` (`reporter_id`),
  KEY `patient_safety_incidents_incident_type_index` (`incident_type`),
  KEY `patient_safety_incidents_severity_index` (`severity`),
  KEY `patient_safety_incidents_status_index` (`status`),
  CONSTRAINT `patient_safety_incidents_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patient_safety_incidents_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_screenings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `screening_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fall_risk','pain','nutrition','pediatric_fall') COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `screened_at` datetime NOT NULL,
  `answers` json DEFAULT NULL,
  `score` smallint unsigned NOT NULL DEFAULT '0',
  `risk_level` enum('low','moderate','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'low',
  `intervention` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_screenings_screening_no_unique` (`screening_no`),
  KEY `patient_screenings_patient_id_foreign` (`patient_id`),
  KEY `patient_screenings_user_id_foreign` (`user_id`),
  KEY `patient_screenings_type_index` (`type`),
  CONSTRAINT `patient_screenings_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `patient_screenings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor Induk Kependudukan',
  `bpjs_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_verified` tinyint(1) NOT NULL DEFAULT '0',
  `birth_date` date DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `blood_type` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allergies` text COLLATE utf8mb4_unicode_ci COMMENT 'Riwayat alergi',
  `medical_history` text COLLATE utf8mb4_unicode_ci COMMENT 'Riwayat penyakit',
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_nik_unique` (`nik`),
  KEY `patients_user_id_foreign` (`user_id`),
  CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `medical_record_id` bigint unsigned DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `change_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','transfer','debit','credit','qris','card','insurance','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'cash',
  `status` enum('pending','completed','cancelled','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_invoice_number_unique` (`invoice_number`),
  KEY `idx_payments_patient_id` (`patient_id`),
  KEY `idx_payments_appointment_id` (`appointment_id`),
  KEY `payments_medical_record_id_foreign` (`medical_record_id`),
  CONSTRAINT `payments_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15508 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `polyclinics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode poli: UMUM, GIGI, JANTUNG, dll',
  `description` text COLLATE utf8mb4_unicode_ci,
  `floor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lantai',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `polyclinics_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `postnatal_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `midwife_id` bigint unsigned NOT NULL,
  `maternity_id` bigint unsigned DEFAULT NULL,
  `visit_number` int NOT NULL,
  `visit_date` date NOT NULL,
  `fundal_height` decimal(5,1) DEFAULT NULL,
  `lochia` enum('rubra','serosa','alba') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perineum_wound` text COLLATE utf8mb4_unicode_ci,
  `breastfeeding` enum('exclusive','mixed','formula') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complaints` text COLLATE utf8mb4_unicode_ci,
  `contraception_started` enum('none','IUD','implant','injection','pill','condom','sterilization') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `postnatal_records_midwife_id_foreign` (`midwife_id`),
  KEY `postnatal_records_maternity_id_foreign` (`maternity_id`),
  KEY `postnatal_records_patient_id_visit_date_index` (`patient_id`,`visit_date`),
  CONSTRAINT `postnatal_records_maternity_id_foreign` FOREIGN KEY (`maternity_id`) REFERENCES `maternities` (`id`) ON DELETE SET NULL,
  CONSTRAINT `postnatal_records_midwife_id_foreign` FOREIGN KEY (`midwife_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `postnatal_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prescription_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint unsigned NOT NULL,
  `drug_id` bigint unsigned DEFAULT NULL,
  `drug_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dose` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int unsigned NOT NULL DEFAULT '1',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instructions` text COLLATE utf8mb4_unicode_ci,
  `is_compounded` tinyint(1) NOT NULL DEFAULT '0',
  `is_high_alert` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prescription_items_prescription_id_foreign` (`prescription_id`),
  KEY `prescription_items_drug_id_foreign` (`drug_id`),
  CONSTRAINT `prescription_items_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prescriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rx_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `medical_record_id` bigint unsigned DEFAULT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `prescribed_at` date NOT NULL,
  `is_iter` tinyint(1) NOT NULL DEFAULT '0',
  `iter_count` tinyint unsigned NOT NULL DEFAULT '0',
  `status` enum('draft','issued','dispensed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'issued',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prescriptions_rx_no_unique` (`rx_no`),
  KEY `prescriptions_patient_id_foreign` (`patient_id`),
  KEY `prescriptions_doctor_id_foreign` (`doctor_id`),
  KEY `prescriptions_medical_record_id_foreign` (`medical_record_id`),
  KEY `prescriptions_status_index` (`status`),
  KEY `prescriptions_appointment_id_foreign` (`appointment_id`),
  CONSTRAINT `prescriptions_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prescriptions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prescriptions_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `supplier_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_date` date NOT NULL,
  `expected_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','ordered','partially_received','received','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  KEY `purchase_orders_department_id_foreign` (`department_id`),
  KEY `purchase_orders_status_index` (`status`),
  KEY `purchase_orders_order_date_index` (`order_date`),
  CONSTRAINT `purchase_orders_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `polyclinic_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `queue_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. A-001',
  `status` enum('waiting','called','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `called_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `queues_patient_id_foreign` (`patient_id`),
  KEY `queues_doctor_id_foreign` (`doctor_id`),
  KEY `queues_polyclinic_id_status_index` (`polyclinic_id`,`status`),
  KEY `queues_polyclinic_id_created_at_index` (`polyclinic_id`,`created_at`),
  CONSTRAINT `queues_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `queues_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `queues_polyclinic_id_foreign` FOREIGN KEY (`polyclinic_id`) REFERENCES `polyclinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5681 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `radiologies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `examination_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_part` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('requested','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'requested',
  `findings` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `medical_record_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `radiologies_patient_id_index` (`patient_id`),
  KEY `radiologies_status_index` (`status`),
  KEY `radiologies_doctor_id_foreign` (`doctor_id`),
  KEY `radiologies_appointment_id_foreign` (`appointment_id`),
  KEY `radiologies_medical_record_id_foreign` (`medical_record_id`),
  CONSTRAINT `radiologies_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `radiologies_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `radiologies_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE SET NULL,
  CONSTRAINT `radiologies_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referrals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `from_polyclinic_id` bigint unsigned DEFAULT NULL,
  `to_polyclinic_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosis` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `medical_record_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referrals_from_polyclinic_id_foreign` (`from_polyclinic_id`),
  KEY `referrals_to_polyclinic_id_foreign` (`to_polyclinic_id`),
  KEY `referrals_doctor_id_foreign` (`doctor_id`),
  KEY `referrals_patient_id_index` (`patient_id`),
  KEY `referrals_status_index` (`status`),
  KEY `referrals_medical_record_id_foreign` (`medical_record_id`),
  CONSTRAINT `referrals_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `referrals_from_polyclinic_id_foreign` FOREIGN KEY (`from_polyclinic_id`) REFERENCES `polyclinics` (`id`) ON DELETE SET NULL,
  CONSTRAINT `referrals_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE SET NULL,
  CONSTRAINT `referrals_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referrals_to_polyclinic_id_foreign` FOREIGN KEY (`to_polyclinic_id`) REFERENCES `polyclinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type` enum('VIP','Kelas 1','Kelas 2','Kelas 3','ICU','NICU','OK') COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor` int DEFAULT NULL,
  `bed_count` int NOT NULL DEFAULT '1',
  `price_per_day` decimal(12,2) NOT NULL DEFAULT '0.00',
  `facilities` json DEFAULT NULL COMMENT 'Fasilitas: AC, TV, Kamar Mandi',
  `status` enum('available','occupied','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_room_number_unique` (`room_number`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `period_month` int NOT NULL COMMENT 'Bulan 1-12',
  `period_year` int NOT NULL COMMENT 'Tahun',
  `base_salary` decimal(12,2) NOT NULL COMMENT 'Gaji pokok',
  `overtime_hours` decimal(5,1) NOT NULL DEFAULT '0.0' COMMENT 'Jam lembur',
  `overtime_pay` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Upah lembur',
  `bonus` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Bonus',
  `deduction` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Potongan',
  `deduction_note` text COLLATE utf8mb4_unicode_ci COMMENT 'Keterangan potongan',
  `total_salary` decimal(12,2) NOT NULL COMMENT 'Total gaji',
  `status` enum('draft','approved','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft' COMMENT 'Status penggajian',
  `paid_at` datetime DEFAULT NULL COMMENT 'Tanggal pembayaran',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `salaries_user_id_period_month_period_year_unique` (`user_id`,`period_month`,`period_year`),
  KEY `salaries_employee_id_foreign` (`employee_id`),
  KEY `salaries_period_month_period_year_index` (`period_month`,`period_year`),
  CONSTRAINT `salaries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'text, json, boolean, number',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'satu_sehat, general, notification',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shift_handovers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `from_nurse_id` bigint unsigned NOT NULL,
  `to_nurse_id` bigint unsigned NOT NULL,
  `shift_from` enum('pagi','siang','malam') COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift_to` enum('pagi','siang','malam') COLLATE utf8mb4_unicode_ci NOT NULL,
  `handover_date` date NOT NULL,
  `patient_summary` text COLLATE utf8mb4_unicode_ci,
  `important_notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shift_handovers_from_nurse_id_foreign` (`from_nurse_id`),
  KEY `shift_handovers_to_nurse_id_foreign` (`to_nurse_id`),
  KEY `shift_handovers_handover_date_index` (`handover_date`),
  CONSTRAINT `shift_handovers_from_nurse_id_foreign` FOREIGN KEY (`from_nurse_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shift_handovers_to_nurse_id_foreign` FOREIGN KEY (`to_nurse_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `shift_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift_type` enum('morning','afternoon','night','on_call','off') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'morning',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_schedules_shift_date_index` (`shift_date`),
  KEY `staff_schedules_department_index` (`department`),
  KEY `staff_schedules_user_id_index` (`user_id`),
  CONSTRAINT `staff_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surgeries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `scheduled_date` datetime DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surgeries_patient_id_foreign` (`patient_id`),
  KEY `surgeries_doctor_id_foreign` (`doctor_id`),
  KEY `surgeries_status_index` (`status`),
  KEY `surgeries_scheduled_date_index` (`scheduled_date`),
  CONSTRAINT `surgeries_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `surgeries_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telemedicine_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `started_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `platform` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('scheduled','ongoing','completed','no_show','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `chief_complaint` text COLLATE utf8mb4_unicode_ci,
  `assessment` text COLLATE utf8mb4_unicode_ci,
  `plan` text COLLATE utf8mb4_unicode_ci,
  `fee` decimal(14,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `telemedicine_sessions_session_no_unique` (`session_no`),
  KEY `telemedicine_sessions_patient_id_foreign` (`patient_id`),
  KEY `telemedicine_sessions_doctor_id_foreign` (`doctor_id`),
  KEY `telemedicine_sessions_status_index` (`status`),
  CONSTRAINT `telemedicine_sessions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `telemedicine_sessions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treatments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kategori tindakan',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `duration_minutes` int DEFAULT NULL COMMENT 'Durasi tindakan (menit)',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `requirements` json DEFAULT NULL COMMENT 'Persyaratan/persiapan',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `treatments_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','doctor','staff','nurse','midwife','pharmacist','cashier','lab_technician','IT','finance','HR','director','developer') COLLATE utf8mb4_unicode_ci DEFAULT 'staff',
  `department_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_department_id_foreign` (`department_id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vital_signs_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `nurse_id` bigint unsigned NOT NULL,
  `recorded_at` datetime NOT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `blood_pressure_systolic` int DEFAULT NULL,
  `blood_pressure_diastolic` int DEFAULT NULL,
  `heart_rate` int DEFAULT NULL,
  `respiratory_rate` int DEFAULT NULL,
  `oxygen_saturation` decimal(4,1) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vital_signs_records_nurse_id_foreign` (`nurse_id`),
  KEY `vital_signs_records_patient_id_recorded_at_index` (`patient_id`,`recorded_at`),
  KEY `vital_signs_records_appointment_id_foreign` (`appointment_id`),
  CONSTRAINT `vital_signs_records_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `vital_signs_records_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vital_signs_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


-- ═══════════════════════════════════════════════════════════════════════
-- Master Data Essential
-- ═══════════════════════════════════════════════════════════════════════
-- ═══════════════════════════════════════════════════════════════
-- Master Data Essential — Production Seed
-- Generated: 2026-05-22 15:42:32
-- ═══════════════════════════════════════════════════════════════

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

-- ── users ──
INSERT INTO `users` (`name`,`username`,`email`,`password`,`role`) VALUES ("Admin Rumah Sakit","admin","admin@hospital.test","$2y$12$Nc8nSuhwICSFtMtytMy1kuizOJERAFKtT1h6CaKRlGdg9/3UEWDt.","admin");
INSERT INTO `users` (`name`,`username`,`email`,`password`,`role`) VALUES ("Direktur","director","director@hospital.test","$2y$12$tsSI3J0ioph3o0LaLcM43erXK7awtmOCgMuy1qJ35NQfB8mWHnSri","director");
INSERT INTO `users` (`name`,`username`,`email`,`password`,`role`) VALUES ("IT Support","it.support","it.support@hospital.test","$2y$12$kHDExgNMUUclptZVIQGE1.SAvEvNW3pF/bQnLdF0IonDmUDOaqTYy","IT");

-- ── departments ──
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Administrasi","ADM",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Medis","MED",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Keperawatan","NUR",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Farmasi","FAR",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Keuangan","FIN",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("SDM","HRD",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("IT","IT",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Manajemen","MNG",1);

-- ── polyclinics ──
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("UMUM","Poli Umum",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("GIGI","Poli Gigi",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("ANAK","Poli Anak",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("MATA","Poli Mata",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("THT","Poli THT",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("JANTUNG","Poli Jantung",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("BEDAH","Poli Bedah",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("OBGYN","Poli Obgyn",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("KULIT","Poli Kulit Kelamin",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("SARAF","Poli Saraf",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("JIWA","Poli Psikiatri",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("FISIO","Poli Fisioterapi",1);

-- ── rooms ──
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("ICU-01","ICU",1,"1500000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("K1-01","Kelas 1",2,"500000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("K2-01","Kelas 2",4,"300000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("K3-01","Kelas 3",6,"150000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("NICU-01","NICU",1,"1800000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("OK-01","OK",1,"0.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("VIP-01","VIP",1,"1000000.00","available");

-- ── treatments ──
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Konsultasi Dokter Umum","konsultasi-dokter-umum","konsultasi","50000.00",15,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Konsultasi Dokter Spesialis","konsultasi-dokter-spesialis","konsultasi","200000.00",20,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Cek Tekanan Darah","cek-tekanan-darah","pemeriksaan","20000.00",5,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("EKG","ekg","pemeriksaan","150000.00",15,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Imunisasi BCG","imunisasi-bcg","imunisasi","100000.00",10,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Suntik Vitamin","suntik-vitamin","tindakan","75000.00",10,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Cabut Gigi","cabut-gigi","tindakan","250000.00",30,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Tambal Gigi","tambal-gigi","tindakan","200000.00",45,1);

-- ── drugs ──
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Paracetamol 500mg","analgesik","tablet",1000,"500.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Amoxicillin 500mg","antibiotik","kapsul",500,"1500.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Ibuprofen 400mg","analgesik","tablet",800,"1000.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Antasida","lambung","tablet",600,"800.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("CTM","antihistamin","tablet",500,"300.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Vitamin B Complex","vitamin","tablet",1000,"600.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("OBH Sirup 100ml","batuk","botol",200,"12000.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Salbutamol Inhaler","asma","inhaler",100,"85000.00",1);

-- ── chart_of_accounts ──
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1000","ASET","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1100","Kas","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1110","Kas di Tangan","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1120","Bank","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1200","Piutang Pasien","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1210","Piutang BPJS","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1220","Piutang Asuransi Swasta","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1300","Persediaan Obat","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1400","Aset Tetap","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("2000","KEWAJIBAN","liability","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("2100","Utang Usaha","liability","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("2200","Utang Gaji","liability","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("3000","EKUITAS","equity","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("3100","Modal Pemilik","equity","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("3200","Laba Ditahan","equity","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4000","PENDAPATAN","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4100","Pendapatan Konsultasi","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4200","Pendapatan Tindakan Medis","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4300","Pendapatan Penjualan Obat","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4400","Pendapatan Rawat Inap","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5000","BEBAN","expense","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5100","Beban Gaji Karyawan","expense","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5200","Beban Listrik & Air","expense","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5300","Beban Pembelian Obat","expense","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5400","Beban Peralatan Medis","expense","debit",1);

SET FOREIGN_KEY_CHECKS=1;

SET FOREIGN_KEY_CHECKS=1;
SET UNIQUE_CHECKS=1;

-- ✅ Production install complete. Login: admin@hospital.test / password
