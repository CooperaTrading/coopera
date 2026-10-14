-- Coopera Trading Production Database Dump
-- Ready for 1-Click Import in cPanel phpMyAdmin / MySQL 5.7+ / 8.0+ / MariaDB
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_24_141210_create_categories_table', 1),
(5, '2026_07_24_141211_create_products_table', 1),
(6, '2026_07_24_141212_create_quote_requests_table', 1),
(7, '2026_07_24_141213_create_quote_request_items_table', 1),
(8, '2026_07_24_141214_create_proformas_table', 1),
(9, '2026_07_24_141215_create_proforma_items_table', 1),
(10, '2026_08_02_000001_create_proforma_templates_table', 2),
(11, '2026_08_23_000001_add_image_to_categories_table', 3),
(12, '2026_08_23_000002_add_is_active_and_sort_order_to_categories_and_products', 4),
(13, '2026_08_23_000003_create_partners_table', 5),
(14, '2026_09_06_000001_add_permissions_to_users_table', 6),
(15, '2026_09_06_000002_create_hero_slides_table', 7);

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` DATETIME NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `remember_token` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `is_super_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `permissions` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`, `is_super_admin`, `permissions`) VALUES
(1, 'Tsedey Yayeh', 'tsedey.yayeh@cooperatrading.com', '2026-08-29 15:17:08', '$2y$12$yKDe/xTbsDrD09QfnP45Quns9jR6ZFcVfKoFOjiAq.Rlb9bVxRrK6', 1, 'YGuTH1KbSzPy9ITYbi2XlavOurNEJowtXcAu0UbsmVp4rb44Vc1ecLs8Hnez', '2026-08-02 20:12:19', '2026-09-06 18:24:38', 1, '["categories","products","partners","quotes","templates","users"]');

-- --------------------------------------------------------
-- Table structure for table `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(255) NULL DEFAULT NULL,
  `user_agent` LONGTEXT NULL DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `cache`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` LONGTEXT NOT NULL,
  `expiration` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `cache_locks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` BIGINT UNSIGNED NOT NULL,
  `reserved_at` BIGINT UNSIGNED NULL DEFAULT NULL,
  `available_at` BIGINT UNSIGNED NOT NULL,
  `created_at` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `job_batches`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` BIGINT UNSIGNED NOT NULL,
  `pending_jobs` BIGINT UNSIGNED NOT NULL,
  `failed_jobs` BIGINT UNSIGNED NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` LONGTEXT NULL DEFAULT NULL,
  `cancelled_at` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` BIGINT UNSIGNED NOT NULL,
  `finished_at` BIGINT UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `failed_jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` LONGTEXT NOT NULL,
  `queue` LONGTEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `categories`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` BIGINT UNSIGNED NOT NULL DEFAULT ''0'',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `categories`
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`, `image`, `is_active`, `sort_order`) VALUES
(3, 'Water & Environmental Solutions', 'water-environmental-solutions', 'Integrated water treatment, environmental protection, and flow control solutions for municipal and industrial applications.', '2026-08-02 19:00:03', '2026-08-02 19:00:03', NULL, 1, 0),
(4, 'Packaging & Industrial Supplies', 'packaging-industrial-supplies', 'Industrial packaging, films, and consumables engineered for safe storage, transport, and protection of goods.', '2026-08-02 19:00:03', '2026-08-02 19:00:03', NULL, 1, 0),
(5, 'Industrial Technology Solutions', 'industrial-technology-solutions', 'Automation, electrical, and instrumentation solutions that power modern industrial operations.', '2026-08-02 19:00:03', '2026-08-02 19:00:03', NULL, 1, 0),
(7, 'Construction Product Markets', 'construction-product-markets', 'Comprehensive range of construction chemicals, systems, and solutions for commercial, industrial, and infrastructure projects.', '2026-08-23 19:48:25', '2026-08-23 20:01:54', NULL, 1, 0),
(8, 'Industrial Chemicals', 'industrial-chemicals', 'Premium industrial chemicals and raw materials for plastics, manufacturing, and processing industries.', '2026-08-23 19:48:25', '2026-08-23 19:48:25', NULL, 1, 0),
(9, 'Export Products', 'export-products', 'Premium Ethiopian-origin agricultural and natural products sourced, processed, and exported for international markets.', '2026-08-23 19:48:25', '2026-08-23 19:48:25', NULL, 1, 0);

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  `unit_of_measure` VARCHAR(255) NOT NULL DEFAULT ''piece'',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` BIGINT UNSIGNED NOT NULL DEFAULT ''0'',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `products`
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `image`, `description`, `unit_of_measure`, `created_at`, `updated_at`, `is_active`, `sort_order`) VALUES
(17, 3, 'Water Treatment Chemicals', 'water-treatment-chemicals', NULL, 'Coagulants, flocculants, disinfectants, and scale inhibitors for potable, process, and wastewater treatment.', 'kg', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(18, 3, 'Water Treatment Equipment', 'water-treatment-equipment', NULL, 'Reverse osmosis systems, softeners, filtration units, and packaged treatment plants.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(19, 3, 'Industrial Filtration', 'industrial-filtration', NULL, 'Cartridge, bag, and multimedia filters for process water, chemicals, and food & beverage applications.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(20, 3, 'Pumps & Flow Control', 'pumps-flow-control', NULL, 'Industrial pumps, valves, and metering systems for water, chemicals, and slurry service.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(21, 3, 'Environmental Protection Systems', 'environmental-protection-systems', NULL, 'Effluent treatment plants, odor control systems, and solid waste handling equipment.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(22, 4, 'Industrial Packaging', 'industrial-packaging', NULL, 'Heavy-duty sacks, FIBC jumbo bags, drums, and containers for bulk chemicals and finished goods.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(23, 4, 'Plastic Films', 'plastic-films', NULL, 'Stretch films, shrink films, laminating films, and protective films for packaging and palletizing.', 'kg', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(24, 4, 'Packaging Materials', 'packaging-materials', NULL, 'Strapping, tapes, edge protectors, and cushioning materials for industrial packaging operations.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(25, 4, 'Industrial Consumables', 'industrial-consumables', NULL, 'Cleaning supplies, abrasives, adhesives, and maintenance consumables for industrial facilities.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(26, 4, 'Safety Equipment (PPE)', 'safety-equipment-ppe', NULL, 'Personal protective equipment including helmets, gloves, respirators, eye protection, and safety footwear.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(27, 5, 'Industrial Automation', 'industrial-automation', NULL, 'PLCs, HMIs, variable frequency drives, and motion control systems for process and factory automation.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(28, 5, 'Electrical & Power Systems', 'electrical-power-systems', NULL, 'LV/MV switchgear, transformers, UPS systems, and power distribution equipment for industrial facilities.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(29, 5, 'Instrumentation & Control', 'instrumentation-control', NULL, 'Sensors, transmitters, analyzers, and control valves for process measurement and regulation.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(30, 5, 'Industrial Safety & Security', 'industrial-safety-security', NULL, 'Fire suppression, gas detection, access control, and perimeter security systems for industrial sites.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(31, 5, 'Smart Infrastructure', 'smart-infrastructure', NULL, 'IoT, SCADA, and remote monitoring solutions for smart factories and connected infrastructure.', 'piece', '2026-08-02 19:00:03', '2026-08-02 19:00:03', 1, 0),
(36, 7, 'Concrete Solutions', 'concrete-solutions', NULL, 'High-performance concrete admixtures, additives, and specialty solutions for ready-mix, precast, and on-site applications.', 'kg', '2026-08-23 19:48:25', '2026-08-23 20:04:24', 1, 0),
(37, 7, 'Waterproofing Systems', 'waterproofing-systems', NULL, 'Integrated waterproofing membranes, coatings, and sealants for roofs, basements, wet areas, and water-retaining structures.', 'piece', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(38, 7, 'Adhesives & Sealants', 'adhesives-sealants', NULL, 'Industrial-grade tile adhesives, structural epoxies, polyurethane sealants, and bonding agents for construction applications.', 'piece', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(39, 7, 'Concrete Repair & Protection', 'concrete-repair-protection', NULL, 'Repair mortars, anti-corrosion coatings, and protective systems that restore and extend the life of damaged concrete.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(40, 7, 'Flooring Systems', 'flooring-systems', NULL, 'Epoxy, polyurethane, and cementitious flooring systems for industrial, commercial, and decorative applications.', 'piece', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(41, 7, 'Roofing Systems', 'roofing-systems', NULL, 'Single-ply membranes, built-up roofing, and insulated roof panels engineered for durability and weather resistance.', 'piece', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(42, 7, 'Geosynthetics', 'geosynthetics', NULL, 'Geotextiles, geomembranes, geogrids, and geocomposites for soil stabilization, drainage, and reinforcement.', 'piece', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(43, 7, 'Expansion Joints & Joint Sealants', 'expansion-joints-joint-sealants', NULL, 'Expansion joint systems and sealants for buildings, bridges, parking decks, and heavily trafficked concrete slabs.', 'piece', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(44, 8, 'Plasticizers', 'plasticizers', NULL, 'DOP, DINP, DOTP, and bio-based plasticizers for flexible PVC, rubber, and polymer compounding.', 'kg', '2026-08-23 19:48:25', '2026-08-23 20:08:25', 1, 0),
(45, 8, 'PVC Resins', 'pvc-resins', NULL, 'Suspension, emulsion, and specialty grade PVC resins for pipe, profile, cable, and film manufacturing.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(46, 8, 'PVC Stabilizers', 'pvc-stabilizers', NULL, 'Lead-based, calcium-zinc, and tin stabilizers for heat and UV stabilization of PVC compounds.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(47, 8, 'Solvents', 'solvents', NULL, 'Industrial and specialty solvents including aromatics, aliphatics, alcohols, ketones, and esters.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(48, 8, 'Fillers', 'fillers', NULL, 'Calcium carbonate, talc, silica, and other mineral fillers for plastics, paints, and rubber compounding.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(49, 8, 'Processing Aids', 'processing-aids', NULL, 'Acrylic and wax-based processing aids that improve PVC fusion, melt strength, and surface finish.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(50, 8, 'Lubricants', 'lubricants', NULL, 'Internal and external lubricants for PVC, polyolefin, and engineering plastics processing.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(51, 8, 'Pigments & Color Masterbatches', 'pigments-color-masterbatches', NULL, 'Organic and inorganic pigments plus custom color masterbatches for plastics, coatings, and inks.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(52, 9, 'Coffee Export', 'coffee-export', NULL, 'Specialty and commercial grade Ethiopian Arabica coffee beans, washed and natural, with full traceability.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(53, 9, 'Sesame & Oilseeds', 'sesame-oilseeds', NULL, 'Hulled and natural sesame, Niger seed, and sunflower seeds processed for export markets.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(54, 9, 'Pulses & Spices', 'pulses-spices', NULL, 'Chickpeas, lentils, beans, and Ethiopian spices including korarima, long pepper, and berbere.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0),
(55, 9, 'Natural Products', 'natural-products', NULL, 'Honey, beeswax, herbs, and other natural Ethiopian products meeting international quality standards.', 'kg', '2026-08-23 19:48:25', '2026-08-23 19:48:25', 1, 0);

-- --------------------------------------------------------
-- Table structure for table `quote_requests`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `quote_requests`;
CREATE TABLE `quote_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(255) NOT NULL,
  `company_name` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NULL DEFAULT NULL,
  `message` LONGTEXT NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT ''pending'',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `quote_requests`
INSERT INTO `quote_requests` (`id`, `customer_name`, `company_name`, `email`, `phone`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Abrham Taye', 'ABG PLC', 'abrhamtaye903@gmail.com', '+251912345678', 'harer', 'processed', '2026-08-02 20:13:46', '2026-08-02 20:14:20');

-- --------------------------------------------------------
-- Table structure for table `quote_request_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `quote_request_items`;
CREATE TABLE `quote_request_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quote_request_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `unit_of_measure` VARCHAR(255) NOT NULL DEFAULT ''piece'',
  `quantity` BIGINT UNSIGNED NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `quote_request_items`
INSERT INTO `quote_request_items` (`id`, `quote_request_id`, `product_id`, `product_name`, `unit_of_measure`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Concrete Repair & Protection', 'kg', 10, '2026-08-02 20:13:46', '2026-08-02 20:13:46'),
(2, 1, NULL, 'Concrete Solutions', 'kg', 150, '2026-08-02 20:13:46', '2026-08-02 20:13:46'),
(3, 1, 28, 'Electrical & Power Systems', 'piece', 184, '2026-08-02 20:13:46', '2026-08-02 20:13:46'),
(4, 1, 21, 'Environmental Protection Systems', 'piece', 141, '2026-08-02 20:13:46', '2026-08-02 20:13:46');

-- --------------------------------------------------------
-- Table structure for table `proformas`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `proformas`;
CREATE TABLE `proformas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quote_request_id` BIGINT UNSIGNED NOT NULL,
  `proforma_number` VARCHAR(255) NOT NULL,
  `issue_date` DATE NULL DEFAULT NULL,
  `validity_date` DATE NULL DEFAULT NULL,
  `payment_terms` LONGTEXT NULL DEFAULT NULL,
  `delivery_time` LONGTEXT NULL DEFAULT NULL,
  `bank_details` LONGTEXT NULL DEFAULT NULL,
  `notes` LONGTEXT NULL DEFAULT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT ''0'',
  `vat` DECIMAL(12,2) NOT NULL DEFAULT ''0'',
  `total` DECIMAL(12,2) NOT NULL DEFAULT ''0'',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `proformas`
INSERT INTO `proformas` (`id`, `quote_request_id`, `proforma_number`, `issue_date`, `validity_date`, `payment_terms`, `delivery_time`, `bank_details`, `notes`, `subtotal`, `vat`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 'PROF-0001', '2026-08-02 00:00:00', '2026-09-01 00:00:00', '30% advance upon confirmation, 70% balance before shipment.', '21 days from receipt of advance payment.', 'Bank: Commercial Bank of Ethiopia\r\nAccount: 1000123456789\r\nSWIFT: CBETETAA\r\nBranch: Addis Ababa Main', NULL, 1052080, 157812, 1209892, '2026-08-02 20:14:20', '2026-08-02 20:14:20');

-- --------------------------------------------------------
-- Table structure for table `proforma_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `proforma_items`;
CREATE TABLE `proforma_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `proforma_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `unit_of_measure` VARCHAR(255) NOT NULL DEFAULT ''piece'',
  `quantity` BIGINT UNSIGNED NOT NULL,
  `unit_price` DECIMAL(12,2) NOT NULL DEFAULT ''0'',
  `total_price` DECIMAL(12,2) NOT NULL DEFAULT ''0'',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `proforma_items`
INSERT INTO `proforma_items` (`id`, `proforma_id`, `product_id`, `product_name`, `unit_of_measure`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Concrete Repair & Protection', 'kg', 10, 8500, 85000, '2026-08-02 20:14:20', '2026-08-02 20:14:20'),
(2, 1, NULL, 'Concrete Solutions', 'kg', 150, 650, 97500, '2026-08-02 20:14:20', '2026-08-02 20:14:20'),
(3, 1, 28, 'Electrical & Power Systems', 'piece', 184, 450, 82800, '2026-08-02 20:14:20', '2026-08-02 20:14:20'),
(4, 1, 21, 'Environmental Protection Systems', 'piece', 141, 5580, 786780, '2026-08-02 20:14:20', '2026-08-02 20:14:20');

-- --------------------------------------------------------
-- Table structure for table `proforma_templates`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `proforma_templates`;
CREATE TABLE `proforma_templates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL DEFAULT ''Default VAT Proforma Template'',
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `sections` LONGTEXT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `proforma_templates`
INSERT INTO `proforma_templates` (`id`, `name`, `is_active`, `sections`, `created_at`, `updated_at`) VALUES
(1, 'Default VAT Proforma Template', 1, '[{"key":"header_branding","title":"Header Logo & Proforma Title","description":"Coopera Trading brand logo, Proforma Invoice title, document number, and issue date","visible":true,"layout":"split","align":"between","qr_size":"medium"},{"key":"party_details","title":"Supplier & Customer Bill-To Details","description":"Coopera office address, phone, email, and Customer Bill-To address block","visible":true,"layout":"split","align":"left","qr_size":"medium"},{"key":"items_table","title":"Products Items Table","description":"Line items with Description, Unit of Measure, Quantity, Unit Price, and Line Total","visible":true,"layout":"split","align":"left","qr_size":"medium"},{"key":"financial_summary","title":"Financial Subtotal & 15% VAT Summary","description":"Subtotal ETB, 15% VAT, and Grand Total ETB calculation","visible":true,"layout":"split","align":"left","qr_size":"medium"},{"key":"amount_in_words","title":"Total Price in Words Line","description":"Official line displaying the total invoice amount spelled out in words","visible":true,"content":"Two Hundred Fifteen Thousand Six Hundred Twenty-Five ETB Only","label":"Total Amount in Words:","layout":"split","align":"left","qr_size":"medium"},{"key":"payment_bank_info","title":"Bank & Payment Details","description":"CBE bank name, account number, branch, and payment instructions","visible":true,"content":"Commercial Bank of Ethiopia (CBE)\\r\\nAccount Name: Coopera Trading\\r\\nAccount Number: 1000123456789\\r\\nBranch: Bole Branch, Addis Ababa","layout":"split","align":"left","qr_size":"medium"},{"key":"terms_notes","title":"Terms of Delivery & Tax Info","description":"Validity period, delivery schedule, TIN number, and notes","visible":true,"content":"Price validity: 30 Days from date of issue.\\r\\nDelivery: 3-5 Business days after payment confirmation.\\r\\nVAT Certificate #: 1234567890 | TIN #: 00987654321","layout":"split","align":"left","qr_size":"medium"},{"key":"qr_code_stamp","title":"QR Code Verification & Stamp","description":"Verification QR code (adjustable size) and Authorized Stamp\\/Signature block","visible":true,"layout":"split","align":"left","qr_size":"medium"}]', '2026-08-02 20:24:47', '2026-08-02 20:35:23');

-- --------------------------------------------------------
-- Table structure for table `partners`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `partners`;
CREATE TABLE `partners` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `logo` VARCHAR(255) NULL DEFAULT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` BIGINT UNSIGNED NOT NULL DEFAULT ''0'',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `partners`
INSERT INTO `partners` (`id`, `name`, `logo`, `url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Sika AG', NULL, 'https://www.sika.com', 1, 1, '2026-08-23 20:22:47', '2026-08-23 20:22:47'),
(2, 'BASF SE', NULL, 'https://www.basf.com', 1, 2, '2026-08-23 20:22:47', '2026-08-23 20:22:47'),
(3, 'Dow Chemical', NULL, 'https://www.dow.com', 1, 3, '2026-08-23 20:22:47', '2026-08-23 20:22:47'),
(4, 'LG Chem', NULL, 'https://www.lgchem.com', 1, 4, '2026-08-23 20:22:47', '2026-08-23 20:22:47'),
(5, 'SABIC', NULL, 'https://www.sabic.com', 1, 5, '2026-08-23 20:22:47', '2026-08-23 20:22:47'),
(6, 'Wanhua Chemical', NULL, 'https://www.whchem.com', 1, 6, '2026-08-23 20:22:47', '2026-08-23 20:22:47');

-- --------------------------------------------------------
-- Table structure for table `hero_slides`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hero_slides`;
CREATE TABLE `hero_slides` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `badge` VARCHAR(255) NULL DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  `image` VARCHAR(255) NOT NULL,
  `image_alt` VARCHAR(255) NULL DEFAULT NULL,
  `cta_label` VARCHAR(255) NULL DEFAULT NULL,
  `cta_url` VARCHAR(255) NULL DEFAULT NULL,
  `tags` LONGTEXT NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` BIGINT UNSIGNED NOT NULL DEFAULT ''0'',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `hero_slides`
INSERT INTO `hero_slides` (`id`, `badge`, `title`, `description`, `image`, `image_alt`, `cta_label`, `cta_url`, `tags`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Construction Product Markets', 'Construction chemicals & high-performance systems.', 'Comprehensive range of concrete admixtures, waterproofing membranes, structural epoxies, and grouting solutions built for infrastructure scale.', 'assets/images/hero/slide-construction.jpg', 'Construction chemicals and high-performance systems', 'Explore Construction', '/products/construction-product-markets', '["Concrete Admixtures","Waterproofing","Structural Epoxies","Grouting"]', 1, 0, '2026-09-06 21:19:42', '2026-09-06 21:19:42'),
(2, 'Specialty Ethiopian Exports', 'Direct Ethiopian exports to global markets.', 'Premium Arabica coffee, natural sesame seeds, pulses, and Ethiopian spices — sourced with 100% quality and origin traceability.', 'assets/images/hero/slide-export.jpg', 'Ethiopian agricultural exports to global markets', 'View Export Catalog', '/products/export-products', '["Arabica Coffee","Sesame Seeds","Pulses & Beans","Spices"]', 1, 1, '2026-09-06 21:19:42', '2026-09-06 21:19:42'),
(3, 'Industrial Chemicals & Materials', 'Raw materials & chemicals for manufacturing.', 'Industrial grade chemicals, polymer resins, plastic additives, and processing solvents tailored for high-volume production.', 'assets/images/hero/slide-chemical.jpg', 'Industrial chemicals and raw materials', 'Browse Chemicals', '/products/industrial-chemicals', '["Polymer Resins","Plastic Additives","Industrial Solvents","Raw Materials"]', 1, 2, '2026-09-06 21:19:42', '2026-09-06 21:19:42'),
(4, 'Bulk Global Logistics & Supply Chain', 'Integrated logistics & global sourcing precision.', 'End-to-end supply chain management, customs clearance support, bulk cargo handling, and freight logistics across East Africa.', 'assets/images/hero/slide-logistics.jpg', 'Bulk global logistics and supply chain', 'Discover Logistics', '/products/water-environmental-solutions', '["Freight Logistics","Customs Clearance","Bulk Cargo","Warehousing"]', 1, 3, '2026-09-06 21:19:42', '2026-09-06 21:19:42');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
