-- Schema SQL complet pour la base de données du projet


CREATE TABLE IF NOT EXISTS `entries` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`email` VARCHAR(255) NOT NULL,
`username` VARCHAR(100) NOT NULL,
`platform` ENUM('roblox','fortnite','other') NOT NULL DEFAULT 'other',
`contact_method` VARCHAR(100) DEFAULT NULL,
`category` ENUM('robux','vbucks') NOT NULL,
`ip` VARCHAR(45) DEFAULT NULL,
`user_agent` TEXT,
`status` ENUM('pending','validated','banned') NOT NULL DEFAULT 'pending',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
`validated_at` DATETIME DEFAULT NULL,
`unique_token` VARCHAR(64) NOT NULL,
PRIMARY KEY (`id`),
INDEX (`email`),
INDEX (`username`),
INDEX (`category`),
INDEX (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `ad_events` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`entry_id` INT UNSIGNED NOT NULL,
`started_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
`completed_at` DATETIME DEFAULT NULL,
`ad_provider` VARCHAR(100) DEFAULT NULL,
`rewarded` TINYINT(1) DEFAULT 0,
PRIMARY KEY (`id`),
FOREIGN KEY (`entry_id`) REFERENCES `entries`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `winners` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`entry_id` INT UNSIGNED NOT NULL,
`category` ENUM('robux','vbucks') NOT NULL,
`prize` VARCHAR(255) NOT NULL,
`draw_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
`delivered` TINYINT(1) DEFAULT 0,
`delivered_at` DATETIME DEFAULT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`entry_id`) REFERENCES `entries`(`id`) ON DELETE CASCADE,
INDEX (`category`),
INDEX (`draw_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `bans` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`entry_id` INT UNSIGNED DEFAULT NULL,
`email` VARCHAR(255) DEFAULT NULL,
`ip` VARCHAR(45) DEFAULT NULL,
`reason` VARCHAR(255) DEFAULT NULL,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
INDEX (`email`),
INDEX (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `draw_logs` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`category` ENUM('robux','vbucks') NOT NULL,
`seed` VARCHAR(128) DEFAULT NULL,
`selected_entry_id` INT UNSIGNED NOT NULL,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Fin du schema