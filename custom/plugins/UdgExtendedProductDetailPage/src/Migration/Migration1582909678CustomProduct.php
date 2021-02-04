<?php declare(strict_types=1);

namespace UdgExtendedProductDetailPage\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1582909678CustomProduct extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1582909678;
    }

    public function update(Connection $connection): void
    {

        // implement update
        $query = <<<SQL
REPLACE INTO `swag_customized_products_template` (`id`, `version_id`, `parent_version_id`, `internal_name`, `active`, `step_by_step`, `confirm_input`, `created_at`) VALUES
 (:templateId, :versionId, :versionId, 'gravur', 1, 0, 0, NOW());
 
REPLACE INTO `swag_customized_products_template_option` (`id`, `version_id`, `template_id`, `template_version_id`, `type`, `type_properties`, `item_number`, `required`, `one_time_surcharge`, `relative_surcharge`, `advanced_surcharge`, `position`,  `percentage_surcharge`, `created_at`) VALUES 
 (:templateOptionId, :versionId, :templateId, :versionId, 'textfield', '{"maxLength": 100, "minLength": 0}', NULL, 0, 0, 0, 0, 1, 0, NOW());
 
REPLACE INTO `swag_customized_products_template_option_translation` (`swag_customized_products_template_option_id`, `swag_customized_products_template_option_version_id`, `language_id`, `display_name`, `description`, `created_at`) VALUES
 (:templateOptionId, :versionId, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 'Gravur', 'Bitte geben Sie Ihre Initialen im Eingabefeld an. Diese werden im xxxx eingraviert.', NOW());
REPLACE INTO `swag_customized_products_template_option_translation` (`swag_customized_products_template_option_id`, `swag_customized_products_template_option_version_id`, `language_id`, `display_name`, `description`, `created_at`) VALUES
 (:templateOptionId, :versionId, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), 'Gravur', 'Bitte geben Sie Ihre Initialen im Eingabefeld an. Diese werden im xxxx eingraviert.', NOW());
 
REPLACE INTO `swag_customized_products_template_translation` (`swag_customized_products_template_id`, `swag_customized_products_template_version_id`, `language_id`, `display_name`, `description`, `created_at`) VALUES
 (:templateId, :versionId, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 'Personalisieren', 'Sie können Ihre Initialen auf dem Musterstück eingravieren lassen und die Materialien nach ihren Bedürfnissen auswählen.<br>', NOW());
REPLACE INTO `swag_customized_products_template_translation` (`swag_customized_products_template_id`, `swag_customized_products_template_version_id`, `language_id`, `display_name`, `description`, `created_at`) VALUES
 (:templateId, :versionId, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), 'Personalisieren', 'Sie können Ihre Initialen auf dem Musterstück eingravieren lassen und die Materialien nach ihren Bedürfnissen auswählen.<br>', NOW());

SQL;

        $connection->executeUpdate($query, [
            'versionId' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
            'templateId' => substr('gravur_UdgExtendedProductDetailPage', 0, 16),
            'templateOptionId' => substr('gravur_UdgExtendedProductDetailPage', 0, 16),
        ]);

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
