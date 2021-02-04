/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- add missing countries
DELETE FROM `country` WHERE `position` = 222;

/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` (`id`, `iso`, `position`, `tax_free`, `active`, `iso3`, `display_state_in_registration`, `force_state_in_registration`, `created_at`, `updated_at`, `shipping_available`) VALUES
	(_binary 0x092036DD0EB14E06B9EAA145620103D3, 'UZ', 222, 0, 1, 'UZB', 0, 0, '2020-06-02 10:01:35.890', '2020-06-02 10:02:58.786', 1);
/*!40000 ALTER TABLE `country` ENABLE KEYS */;

/*!40000 ALTER TABLE `country_translation` DISABLE KEYS */;
INSERT INTO `country_translation` (`country_id`, `language_id`, `name`, `custom_fields`, `created_at`, `updated_at`) VALUES
	(_binary 0x092036DD0EB14E06B9EAA145620103D3, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), 'Usbekistan', NULL, '2020-06-02 10:02:58.786', NULL),
	(_binary 0x092036DD0EB14E06B9EAA145620103D3, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 'Uzbekistan', NULL, '2020-06-02 10:01:35.890', NULL);
/*!40000 ALTER TABLE `country_translation` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
