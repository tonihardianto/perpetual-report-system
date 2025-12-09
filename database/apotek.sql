-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 08, 2025 at 03:46 PM
-- Server version: 9.5.0
-- PHP Version: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apotek`
--
CREATE DATABASE IF NOT EXISTS `apotek` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `apotek`;

-- --------------------------------------------------------

--
-- Table structure for table `batch_obat`
--

CREATE TABLE `batch_obat` (
  `id` bigint UNSIGNED NOT NULL,
  `obat_id` bigint UNSIGNED NOT NULL,
  `nomor_batch` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_ed` date NOT NULL,
  `harga_beli_per_satuan` decimal(15,2) NOT NULL,
  `stok_awal` int NOT NULL DEFAULT '0',
  `sisa_stok` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batch_obat`
--

INSERT INTO `batch_obat` (`id`, `obat_id`, `nomor_batch`, `tanggal_masuk`, `tanggal_ed`, `harga_beli_per_satuan`, `stok_awal`, `sisa_stok`, `created_at`, `updated_at`) VALUES
(1, 2, 'A1', '2025-01-02', '2025-11-29', 1000.00, 100, 0, '2025-11-14 06:01:54', '2025-11-14 06:06:44'),
(2, 6, 'B1', '2025-01-02', '2025-11-29', 1000.00, 100, 0, '2025-11-14 06:01:54', '2025-11-14 06:07:53'),
(3, 3, 'C1', '2025-01-02', '2025-11-29', 1000.00, 100, 0, '2025-11-14 06:01:54', '2025-11-14 06:07:53'),
(4, 2, 'A2', '2025-01-09', '2025-11-30', 1000.00, 100, 0, '2025-11-14 06:03:11', '2025-11-14 06:06:44'),
(5, 3, 'C2', '2025-01-09', '2025-11-30', 1000.00, 100, 25, '2025-11-14 06:03:11', '2025-11-14 06:07:53'),
(6, 6, 'B2', '2025-01-09', '2025-11-30', 1000.00, 100, 0, '2025-11-14 06:03:11', '2025-11-14 06:07:53'),
(7, 2, 'A3', '2025-01-15', '2025-11-30', 1000.00, 100, 50, '2025-11-14 06:04:54', '2025-11-14 06:06:44'),
(8, 6, 'B3', '2025-01-15', '2025-11-30', 1000.00, 100, 75, '2025-11-14 06:04:54', '2025-11-14 06:07:53'),
(9, 3, 'C3', '2025-01-15', '2025-11-30', 1000.00, 100, 100, '2025-11-14 06:04:54', '2025-11-14 06:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_10_17_024141_create_obat_table', 1),
(6, '2025_10_17_024156_create_supplier_table', 1),
(7, '2025_10_17_024204_create_batch_obat_table', 1),
(8, '2025_10_17_024209_create_transaksi_mutasi_table', 1),
(9, '2025_10_17_024221_create_stock_opname_table', 1),
(10, '2025_11_11_135345_create_stock_opname_headers_table', 1),
(11, '2025_12_01_145431_create_permission_tables', 2),
(12, '2025_12_03_042656_add_username_to_users_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 2),
(5, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_obat` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_obat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan_terkecil` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `kode_obat`, `nama_obat`, `satuan_terkecil`, `is_aktif`, `created_at`, `updated_at`) VALUES
(1, 'AFT001', 'Soft U Derm 10% cr 20gr A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(2, 'AGP', 'ABU Antivenom Green Pit Viper', 'Kapsul', 1, '2025-11-14 05:56:04', '2025-12-01 08:27:36'),
(3, 'AIM001', 'AIM salmonella typhi H', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(5, 'AK001', 'Ofloxacin TT (Akilen)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(6, 'AKA001', 'Akarbose 50mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(7, 'AKS001', 'AKSAMED sarung tangan Steril 6,5', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(8, 'AKS002', 'AKSAMED sarung tangan Steril 7', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(9, 'AKS003', 'AKSAMED sarung tangan Steril 7,5', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(10, 'ALA001', 'Spuit 1 ml ( terumo ) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(11, 'ALA002', 'Spuit 10 ml (OM) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(12, 'ALA003', 'Spuit 1ml B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(13, 'ALA004', 'Spuit 5 ml (Aximed) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(14, 'ALA005', 'Spuit 50cc LT Ulir (OM) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(15, 'ALB0001', 'Albothyl 10ml', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(16, 'ALB001', 'Albendazol 400mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(17, 'ALB002', 'ALBUMIN (A-234)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(18, 'ALK00004', 'Alkohol 96% 1L', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(19, 'ALK0005', 'Alkacid 1L', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(20, 'ALK001', 'Alkohol 70% 1L (Medika)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(21, 'ALK002', 'Alkohol antiseptik 70%  galon', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(22, 'ALK003', 'Alkohol Swab (Onemed) A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(23, 'ALK005', 'Alkohol 1 ml', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(24, 'Alk006', 'Alkohol 70% 1L A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(25, 'ALK007', 'Alkohol Swab', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(26, 'ALK011', 'Alkazyme', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(27, 'ALL001', 'Allopurinol 100mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(28, 'ALL005', 'Allopurinol 300 mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(29, 'ALL051', 'Neomycin SO4 + Dexamethason TM (Alletrol)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(30, 'ALP001', 'Alprazolam 0,5mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(31, 'ALP002', 'Alprazolam 1 mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(32, 'ALP003', 'Alprazolam 0,25 mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(33, 'ALY001', 'Neurodex kap (Fundifar)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(34, 'ALY002', 'Alylestrenol (Premaston) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(35, 'ALY003', 'Alylestrenol (Progeston) A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(36, 'AMB001', 'Ambroxol 15mg/5ml syr. A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(37, 'AMB002', 'Ambroxol 30mg A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(38, 'AMB003', 'Ambroxol syr 15mg/5ml B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(39, 'AMB004', 'Ambroxol tab. 30mg (Roverton)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(40, 'AMB005', 'Ambroxol 15mg/5ml (Roverton) A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(41, 'AMI001', 'Amikasin inj. 250mg (Gybotic)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(42, 'AMI002', 'Aminofilin 200mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(43, 'AMI003', 'Aminofilin inj. 24mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(44, 'AMI004', 'Amiodaron 200 mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(45, 'AMI005', 'Amiodaron hcl inj 150 mg/3 ml A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(46, 'AMI006', 'Amiodaron inj. (Tyarit) A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(47, 'AMI007', 'Amitriptilin 25mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(48, 'AMI011', 'Amiodaron Inj', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(49, 'AMI022', 'Aminofilin150mg (Decafil)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(50, 'AML001', 'Amlodipin 10mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(51, 'AML002', 'Amlodipin 5mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(52, 'AMN0001', 'Amino Acid 8% (Aminoleban)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(53, 'AMO0003', 'Amoxicilin 100mg drop', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(54, 'AMO001', 'Amoxicillin 125mg/5ml syr.', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(55, 'AMO002', 'Amoxicillin 500mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(56, 'AMO003', 'Amoxicillin forte 250mg/5ml syr.', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(57, 'AMP001', 'Ampicillin inj. 1gr.', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(58, 'AMS0001', 'Ampicilin Sulbactam Inj. 750mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(60, 'ANA002', 'Analog insulin long act. 100 UI/ml (Levemir) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(62, 'ANA004', 'Analog insulin rapid act.100 UI (Apidra) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(64, 'ANE00003', 'Mask Anesthesia no.2 (Work)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(65, 'ANLT0001', 'Asam Folat 1mg (Anelat) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(66, 'ANS00004', 'Mask Anesthesia no.3 (Work)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(67, 'ANS00005', 'Mask Anesthesia no.5 (Work)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(68, 'ANS00006', 'Mask Anesthesia no.1 (Work)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(69, 'ANS00007', 'Mask Anesthesia no.4 (Work)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(70, 'ANS00008', 'Mask Anesthesia no.6 (Work)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(71, 'ANT0001', 'Antasida Syr. A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(72, 'ANT001', 'Antasida DOEN I tablet (komb.aluminium  hidroksida 200 mg + magnesium hidroksida 200 mg', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(73, 'ANT002', 'Antasida DOEN II syr. B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(74, 'ANT003', 'Anti Bakteri DOEN (basitrasin + polimiksin)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(75, 'ANT004', 'Anti Malaria (Dihidroartemisinin Piperakuin)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(76, 'ANT005', 'Antihemorrhoid supp.', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(77, 'ANT006', 'Antihemorrhoid supp. (Superrhoid)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(78, 'ANT007', 'Antimigren DOEN (Ergotamin Caffein)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(79, 'ANT008', 'Antimigren DOEN (Ericaf)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(80, 'ANT009', 'Antiparkinson DOEN (Levopar)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(81, 'APR00003', 'Apron (Cosmomed) A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(82, 'APR0004', 'Apron Re Use A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(83, 'apr0005', 'Apron (Solida) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(84, 'APR001', 'Apron (OM) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(85, 'AQU0001', 'Aquadest lab', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(86, 'AQU001', 'Aqua pro injeksi A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(87, 'AQU002', 'Aqua pro injeksi B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(88, 'AQU003', 'Aqua pro irrigation 1L', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(89, 'ARA0004', 'Sodium Hyaluronate inj. (Aragan) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(90, 'ARH001', 'Arhrocain 4%+Epinefrin inj. (Septocain )', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(91, 'ARM001', 'Arm sling OM (L) A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(92, 'ARM002', 'Arm sling Supermed (L) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(93, 'ARM003', 'Arm Sling OM (M) A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(94, 'ARM004', 'Arm Sling Supermed (M) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(95, 'ARM005', 'Arm Sling Supermed (S) B', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(96, 'ARM006', 'Arm Sling OM (S) A', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(97, 'ART0001', 'Articulating Paper Nordin', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(98, 'AS001', 'Asam Valproat syr (Ikalep)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04'),
(99, 'ASA00012', 'Asam Valproat syr. (120ml)', 'PCS', 1, '2025-11-14 05:56:04', '2025-11-14 05:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage users', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(2, 'manage roles', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(3, 'manage obat', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(4, 'import data', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(5, 'perform stock-opname', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(6, 'view reports', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(7, 'export reports', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(8, 'process mutasi', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(9, 'manage settings', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(2, 'admin', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(3, 'manager', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(4, 'staff', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31'),
(5, 'viewer', 'web', '2025-12-01 07:54:31', '2025-12-01 07:54:31');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(3, 4),
(5, 4),
(8, 4),
(6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `stock_opname`
--

CREATE TABLE `stock_opname` (
  `id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `tanggal_opname` date NOT NULL,
  `stok_tercatat_sistem` int NOT NULL,
  `stok_fisik` int NOT NULL,
  `selisih` int NOT NULL,
  `nilai_selisih` decimal(15,2) NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_opname`
--

INSERT INTO `stock_opname` (`id`, `batch_id`, `tanggal_opname`, `stok_tercatat_sistem`, `stok_fisik`, `selisih`, `nilai_selisih`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 7, '2025-01-31', 100, 150, 50, 50000.00, '- (Alokasi Sisa Fisik)', '2025-11-14 06:06:44', '2025-11-14 06:06:44'),
(2, 8, '2025-01-31', 100, 175, 75, 75000.00, '- (Alokasi Sisa Fisik)', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(3, 9, '2025-01-31', 100, 200, 100, 100000.00, '- (Alokasi Sisa Fisik)', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(4, 5, '2025-01-31', 100, 125, 25, 25000.00, '- (Alokasi Sisa Fisik)', '2025-11-14 06:07:53', '2025-11-14 06:07:53');

-- --------------------------------------------------------

--
-- Table structure for table `stock_opname_headers`
--

CREATE TABLE `stock_opname_headers` (
  `id` bigint UNSIGNED NOT NULL,
  `bulan` tinyint UNSIGNED NOT NULL COMMENT 'Bulan Stock Opname (1-12)',
  `tahun` year NOT NULL COMMENT 'Tahun Stock Opname',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Selesai' COMMENT 'Status SO: Selesai, Draft, dll.',
  `tanggal_so_dilakukan` timestamp NULL DEFAULT NULL COMMENT 'Tanggal SO ini dicatat (bukan tanggal periode SO)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_opname_headers`
--

INSERT INTO `stock_opname_headers` (`id`, `bulan`, `tahun`, `status`, `tanggal_so_dilakukan`, `created_at`, `updated_at`) VALUES
(1, 1, '2025', 'Selesai', '2025-11-14 06:06:44', '2025-11-14 06:06:44', '2025-11-14 06:06:44');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_supplier` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_supplier` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_mutasi`
--

CREATE TABLE `transaksi_mutasi` (
  `id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `tipe_transaksi` enum('MASUK','KELUAR','PENYESUAIAN') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_unit` int NOT NULL,
  `harga_pokok_unit` decimal(15,2) NOT NULL,
  `total_hpp` decimal(15,2) NOT NULL,
  `harga_jual_unit` decimal(15,2) DEFAULT NULL,
  `referensi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi_mutasi`
--

INSERT INTO `transaksi_mutasi` (`id`, `batch_id`, `tanggal_transaksi`, `tipe_transaksi`, `jumlah_unit`, `harga_pokok_unit`, `total_hpp`, `harga_jual_unit`, `referensi`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-01-02 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, '-', 'Pembelian/Penerimaan', '2025-11-14 06:01:54', '2025-11-14 06:01:54'),
(2, 2, '2025-01-02 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, '-', 'Pembelian/Penerimaan', '2025-11-14 06:01:54', '2025-11-14 06:01:54'),
(3, 3, '2025-01-02 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, '-', 'Pembelian/Penerimaan', '2025-11-14 06:01:54', '2025-11-14 06:01:54'),
(4, 4, '2025-01-09 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, '-', 'Pembelian/Penerimaan', '2025-11-14 06:03:11', '2025-11-14 06:03:11'),
(5, 5, '2025-01-09 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, '-', 'Pembelian/Penerimaan', '2025-11-14 06:03:11', '2025-11-14 06:03:11'),
(6, 6, '2025-01-09 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, '-', 'Pembelian/Penerimaan', '2025-11-14 06:03:11', '2025-11-14 06:03:11'),
(7, 7, '2025-01-15 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, '-', 'Pembelian/Penerimaan', '2025-11-14 06:04:54', '2025-11-14 06:04:54'),
(8, 8, '2025-01-15 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, '-', 'Pembelian/Penerimaan', '2025-11-14 06:04:54', '2025-11-14 06:04:54'),
(9, 9, '2025-01-15 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, '-', 'Pembelian/Penerimaan', '2025-11-14 06:04:54', '2025-11-14 06:04:54'),
(10, 1, '2025-01-31 23:59:59', 'KELUAR', -100, 1000.00, -100000.00, NULL, 'SO-202501312359', 'Konsumsi (Kerugian/Loss) dari SO: -', '2025-11-14 06:06:44', '2025-11-14 06:06:44'),
(11, 4, '2025-01-31 23:59:59', 'KELUAR', -100, 1000.00, -100000.00, NULL, 'SO-202501312359', 'Konsumsi (Kerugian/Loss) dari SO: -', '2025-11-14 06:06:44', '2025-11-14 06:06:44'),
(12, 7, '2025-01-31 23:59:59', 'KELUAR', -50, 1000.00, -50000.00, NULL, 'SO-202501312359', 'Konsumsi (Kerugian/Loss) dari SO: -', '2025-11-14 06:06:44', '2025-11-14 06:06:44'),
(13, 7, '2025-01-31 23:59:59', 'PENYESUAIAN', 50, 1000.00, 50000.00, NULL, 'SO-202501312359', 'Penyesuaian Stok (Sisa Fisik untuk Reporting): -', '2025-11-14 06:06:44', '2025-11-14 06:06:44'),
(14, 7, '2025-01-31 23:59:59', 'PENYESUAIAN', -50, 1000.00, -50000.00, NULL, 'SO-202501312359', 'Penyesuaian Stok (Jurnal Penutup (Reversal)): -', '2025-11-14 06:06:44', '2025-11-14 06:06:44'),
(15, 7, '2025-02-01 00:00:00', 'MASUK', 50, 1000.00, 50000.00, NULL, 'OP-SO-202502010000', 'Pembelian Awal (Stok Awal Bulan) dari SO sebelumnya: -', '2025-11-14 06:06:44', '2025-11-14 06:06:44'),
(16, 2, '2025-01-31 23:59:59', 'KELUAR', -100, 1000.00, -100000.00, NULL, 'SO-202501312359', 'Konsumsi (Kerugian/Loss) dari SO: -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(17, 6, '2025-01-31 23:59:59', 'KELUAR', -100, 1000.00, -100000.00, NULL, 'SO-202501312359', 'Konsumsi (Kerugian/Loss) dari SO: -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(18, 8, '2025-01-31 23:59:59', 'KELUAR', -25, 1000.00, -25000.00, NULL, 'SO-202501312359', 'Konsumsi (Kerugian/Loss) dari SO: -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(19, 8, '2025-01-31 23:59:59', 'PENYESUAIAN', 75, 1000.00, 75000.00, NULL, 'SO-202501312359', 'Penyesuaian Stok (Sisa Fisik untuk Reporting): -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(20, 8, '2025-01-31 23:59:59', 'PENYESUAIAN', -75, 1000.00, -75000.00, NULL, 'SO-202501312359', 'Penyesuaian Stok (Jurnal Penutup (Reversal)): -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(21, 8, '2025-02-01 00:00:00', 'MASUK', 75, 1000.00, 75000.00, NULL, 'OP-SO-202502010000', 'Pembelian Awal (Stok Awal Bulan) dari SO sebelumnya: -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(22, 3, '2025-01-31 23:59:59', 'KELUAR', -100, 1000.00, -100000.00, NULL, 'SO-202501312359', 'Konsumsi (Kerugian/Loss) dari SO: -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(23, 5, '2025-01-31 23:59:59', 'KELUAR', -75, 1000.00, -75000.00, NULL, 'SO-202501312359', 'Konsumsi (Kerugian/Loss) dari SO: -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(24, 9, '2025-01-31 23:59:59', 'PENYESUAIAN', 100, 1000.00, 100000.00, NULL, 'SO-202501312359', 'Penyesuaian Stok (Sisa Fisik untuk Reporting): -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(25, 9, '2025-01-31 23:59:59', 'PENYESUAIAN', -100, 1000.00, -100000.00, NULL, 'SO-202501312359', 'Penyesuaian Stok (Jurnal Penutup (Reversal)): -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(26, 9, '2025-02-01 00:00:00', 'MASUK', 100, 1000.00, 100000.00, NULL, 'OP-SO-202502010000', 'Pembelian Awal (Stok Awal Bulan) dari SO sebelumnya: -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(27, 5, '2025-01-31 23:59:59', 'PENYESUAIAN', 25, 1000.00, 25000.00, NULL, 'SO-202501312359', 'Penyesuaian Stok (Sisa Fisik untuk Reporting): -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(28, 5, '2025-01-31 23:59:59', 'PENYESUAIAN', -25, 1000.00, -25000.00, NULL, 'SO-202501312359', 'Penyesuaian Stok (Jurnal Penutup (Reversal)): -', '2025-11-14 06:07:53', '2025-11-14 06:07:53'),
(29, 5, '2025-02-01 00:00:00', 'MASUK', 25, 1000.00, 25000.00, NULL, 'OP-SO-202502010000', 'Pembelian Awal (Stok Awal Bulan) dari SO sebelumnya: -', '2025-11-14 06:07:53', '2025-11-14 06:07:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin@themesbrand.com', NULL, '$2y$10$qwTLeMwpcHVX8ExZ/Q5NFOSdE3Xvqrh2AqEwc5NepdMH.vx./d9A.', 'avatar-1.jpg', NULL, '2025-11-14 05:38:26', '2025-11-14 05:38:26'),
(2, 'Megalodon', 'toni', 'toni@sahabatmedia.co.id', NULL, '$2y$10$OC364gpf9Ee7Pe9z2MzeQ.fm1bElxCI5y/pC/j5K1CElPArG/D1d.', NULL, NULL, '2025-12-01 08:15:24', '2025-12-01 08:15:24'),
(3, 'BPK', 'bpk', NULL, NULL, '$2y$10$IvidWi1a9563oZdflRA6cOMSo27RpZ7kE4iB1krdPvDh4UeqPSL8O', NULL, NULL, '2025-12-01 08:25:55', '2025-12-02 23:30:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batch_obat`
--
ALTER TABLE `batch_obat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batch_obat_obat_id_nomor_batch_unique` (`obat_id`,`nomor_batch`);

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
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `obat_kode_obat_unique` (`kode_obat`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `stock_opname`
--
ALTER TABLE `stock_opname`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_opname_batch_id_foreign` (`batch_id`);

--
-- Indexes for table `stock_opname_headers`
--
ALTER TABLE `stock_opname_headers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supplier_kode_supplier_unique` (`kode_supplier`);

--
-- Indexes for table `transaksi_mutasi`
--
ALTER TABLE `transaksi_mutasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_mutasi_batch_id_foreign` (`batch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batch_obat`
--
ALTER TABLE `batch_obat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stock_opname`
--
ALTER TABLE `stock_opname`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_opname_headers`
--
ALTER TABLE `stock_opname_headers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_mutasi`
--
ALTER TABLE `transaksi_mutasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batch_obat`
--
ALTER TABLE `batch_obat`
  ADD CONSTRAINT `batch_obat_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_opname`
--
ALTER TABLE `stock_opname`
  ADD CONSTRAINT `stock_opname_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batch_obat` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `transaksi_mutasi`
--
ALTER TABLE `transaksi_mutasi`
  ADD CONSTRAINT `transaksi_mutasi_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batch_obat` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
