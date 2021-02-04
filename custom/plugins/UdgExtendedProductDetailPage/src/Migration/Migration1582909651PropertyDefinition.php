<?php declare(strict_types=1);

namespace UdgExtendedProductDetailPage\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1582909651PropertyDefinition extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1582909651;
    }

    public function update(Connection $connection): void
    {
        $this->updateColorProperty($connection);
        $this->updateProductlineProperty($connection);
    }

    private function updateColorProperty(Connection $connection): void
    {

        $groupId = 'color_UdgExtendedProductDetailPage';
        $groupIdSubstr = substr($groupId, 0, 13);

        // implement update
        $query = <<<SQL
        -- color --
REPLACE INTO `property_group` (`id`, `sorting_type`, `display_type`, `created_at`) VALUES
 (:property_group_id, 'alphanumeric', 'color', NOW());
        
REPLACE INTO `property_group_translation` (`property_group_id`, `language_id`, `name`, `description`, `custom_fields`, `created_at`) VALUES
 (:property_group_id, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 'Color', NULL, NULL, NOW()),
 (:property_group_id, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), 'Farbe', NULL, NULL, NOW());

REPLACE INTO `property_group_option` (`id`, `property_group_id`, `color_hex_code`, `media_id`, `created_at`) VALUES
 ('$groupIdSubstr-01', :property_group_id, '#c5c5c5', NULL, NOW());
REPLACE INTO `property_group_option_translation` (`property_group_option_id`, `language_id`, `name`, `position`, `custom_fields`, `created_at`) VALUES
 ('$groupIdSubstr-01', (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 'Cool Grey', 1, NULL, NOW());
REPLACE INTO `property_group_option` (`id`, `property_group_id`, `color_hex_code`, `media_id`, `created_at`) VALUES
 ('$groupIdSubstr-02', :property_group_id, '#856230', NULL, NOW());
REPLACE INTO `property_group_option_translation` (`property_group_option_id`, `language_id`, `name`, `position`, `custom_fields`, `created_at`) VALUES
 ('$groupIdSubstr-02', (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 'Brown', 1, NULL, NOW());
REPLACE INTO `property_group_option` (`id`, `property_group_id`, `color_hex_code`, `media_id`, `created_at`) VALUES
 ('$groupIdSubstr-03', :property_group_id, '#000000', NULL, NOW());
REPLACE INTO `property_group_option_translation` (`property_group_option_id`, `language_id`, `name`, `position`, `custom_fields`, `created_at`) VALUES
 ('$groupIdSubstr-03', (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 'Black', 1, NULL, NOW());
      
SQL;

        $connection->executeUpdate($query, [
            'property_group_id' => substr('color_UdgExtendedProductDetailPage', 0, 16),
        ]);
    }

    private function updateProductlineProperty(Connection $connection): void
    {
        $groupId = 'productline_UdgExtendedProductDetailPage';
        $groupIdSubstr = substr($groupId, 0, 13);

$query = <<<SQL
        -- productline --
REPLACE INTO `property_group` (`id`, `sorting_type`, `display_type`, `created_at`) VALUES
 (:property_group_id, 'alphanumeric', 'text', NOW());

REPLACE INTO `property_group_translation` (`property_group_id`, `language_id`, `name`, `description`, `custom_fields`, `created_at`) VALUES
 (:property_group_id, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), '_productline_', NULL, NULL, NOW());

REPLACE INTO `property_group_option` (`id`, `property_group_id`, `color_hex_code`, `media_id`, `created_at`) VALUES
 ('$groupIdSubstr-01', :property_group_id, NULL, NULL, NOW());
REPLACE INTO `property_group_option_translation` (`property_group_option_id`, `language_id`, `name`, `position`, `custom_fields`, `created_at`) VALUES
 ('$groupIdSubstr-01', (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 'Reference Line', 1, NULL, NOW());

SQL;

        $connection->executeUpdate($query, [
            'property_group_id' => substr($groupId, 0, 16),
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
