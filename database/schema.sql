-- =====================================================================
--  CHO Marawi Portal — Database schema (MySQL 8 / MariaDB 10.6+)
--  Run via:  php database/install.php      (recommended)
--        or: import this file in HeidiSQL (Laragon > Database)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `cho_marawi`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `cho_marawi`;

-- ---------------------------------------------------------------------
-- Roles (RBAC foundation)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id`          TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug`        VARCHAR(50)  NOT NULL,
  `name`        VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `roles` (`slug`, `name`, `description`) VALUES
  ('admin',              'System Administrator', 'Full access to all modules and settings'),
  ('city_health_officer','City Health Officer',  'Head of office; oversight of all programs'),
  ('physician',          'Physician',            'Clinical consultations and medical certificates'),
  ('nurse',              'Nurse',                'Clinical support, immunization, TB-DOTS'),
  ('midwife',            'Midwife',              'Maternal, child health and family planning'),
  ('dentist',            'Dentist',              'Dental services'),
  ('med_tech',           'Medical Technologist', 'Laboratory and diagnostics'),
  ('pharmacist',         'Pharmacist',           'Medicine dispensing and inventory'),
  ('sanitary_inspector', 'Sanitary Inspector',   'Sanitary permits, inspections, water monitoring'),
  ('records_officer',    'Records Officer',      'Health records and civil registry coordination'),
  ('staff',              'Staff',                'General office staff');

-- ---------------------------------------------------------------------
-- Users
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`            VARCHAR(100) NOT NULL,
  `email`               VARCHAR(190) NULL,
  `password_hash`       VARCHAR(255) NOT NULL,
  `first_name`          VARCHAR(100) NOT NULL,
  `last_name`           VARCHAR(100) NOT NULL,
  `role_id`             TINYINT UNSIGNED NOT NULL,
  `status`              ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `failed_login_count`  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `locked_until`        DATETIME NULL,
  `last_login_at`       DATETIME NULL,
  `last_login_ip`       VARCHAR(45) NULL,
  `password_changed_at` DATETIME NULL,
  `created_at`          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_username` (`username`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Remember-me tokens (selector + hashed validator; never the password)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_remember_tokens` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         INT UNSIGNED NOT NULL,
  `selector`        CHAR(24) NOT NULL,
  `validator_hash`  CHAR(64) NOT NULL,
  `user_agent_hash` CHAR(64) NULL,
  `expires_at`      DATETIME NOT NULL,
  `created_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_remember_selector` (`selector`),
  KEY `idx_remember_user` (`user_id`),
  KEY `idx_remember_expires` (`expires_at`),
  CONSTRAINT `fk_remember_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Login attempts (security monitoring)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(100) NOT NULL,
  `ip_address` VARCHAR(45)  NOT NULL,
  `user_agent` VARCHAR(255) NULL,
  `successful` TINYINT(1)   NOT NULL DEFAULT 0,
  `reason`     VARCHAR(50)  NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_attempts_username` (`username`, `created_at`),
  KEY `idx_attempts_ip` (`ip_address`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Audit trail (RA 10173 — Data Privacy Act accountability)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED NULL,
  `action`     VARCHAR(100) NOT NULL,
  `entity`     VARCHAR(100) NULL,
  `entity_id`  VARCHAR(64)  NULL,
  `ip_address` VARCHAR(45)  NULL,
  `user_agent` VARCHAR(255) NULL,
  `meta`       JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_user` (`user_id`, `created_at`),
  KEY `idx_audit_action` (`action`, `created_at`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Server-side copy of UI preferences (sync across devices — later)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_preferences` (
  `user_id`     INT UNSIGNED NOT NULL,
  `preferences` JSON NOT NULL,
  `updated_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_prefs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
