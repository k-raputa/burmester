<?php declare(strict_types=1);

namespace UdgExtendedProductDetailPage\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1582912391CustomDownloadFields extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1582912391;
    }

    public function update(Connection $connection): void
    {
        // implement update

        $setId = substr('download_UdgExPDP', 0, 16);
        $relationId = substr('product_' . $setId, 0, 16);

        // implement update
        $query = <<<SQL
REPLACE INTO `custom_field_set` (`id`, `name`, `config`, `active`, `created_at`)
 VALUES ('$setId', 'UdgExtendedProductDetailPage_Download', '{"label": {"en-GB": "Downloads"}}', 1, NOW());

REPLACE INTO `custom_field` (`id`, `name`, `type`, `config`, `active`, `set_id`, `created_at`) VALUES
('UEPDP_download_1', 'udgextendedproductdetailpage_download_1', 'text', '{"type": "text", "label": {"en-GB": "Productdownload 1"}, "helpText": {"en-GB": null}, "validation": "required", "placeholder": {"en-GB": null}, "componentName": "sw-media-field", "customFieldType": "media", "customFieldPosition": 1}', 1, '$setId', NOW()),
('UEPDP_download_2', 'udgextendedproductdetailpage_download_2', 'text', '{"type": "text", "label": {"en-GB": "Productdownload 2"}, "helpText": {"en-GB": null}, "validation": "required", "placeholder": {"en-GB": null}, "componentName": "sw-media-field", "customFieldType": "media", "customFieldPosition": 2}', 1, '$setId', NOW()),
('UEPDP_download_3', 'udgextendedproductdetailpage_download_3', 'text', '{"type": "text", "label": {"en-GB": "Productdownload 3"}, "helpText": {"en-GB": null}, "validation": "required", "placeholder": {"en-GB": null}, "componentName": "sw-media-field", "customFieldType": "media", "customFieldPosition": 3}', 1, '$setId', NOW()),
('UEPDP_download_4', 'udgextendedproductdetailpage_download_4', 'text', '{"type": "text", "label": {"en-GB": "Productdownload 4"}, "helpText": {"en-GB": null}, "validation": "required", "placeholder": {"en-GB": null}, "componentName": "sw-media-field", "customFieldType": "media", "customFieldPosition": 4}', 1, '$setId', NOW()),
('UEPDP_download_5', 'udgextendedproductdetailpage_download_5', 'text', '{"type": "text", "label": {"en-GB": "Productdownload 5"}, "helpText": {"en-GB": null}, "validation": "required", "placeholder": {"en-GB": null}, "componentName": "sw-media-field", "customFieldType": "media", "customFieldPosition": 5}', 1, '$setId', NOW()),
('UEPDP_download_6', 'udgextendedproductdetailpage_download_6', 'text', '{"type": "text", "label": {"en-GB": "Productdownload 6"}, "helpText": {"en-GB": null}, "validation": "required", "placeholder": {"en-GB": null}, "componentName": "sw-media-field", "customFieldType": "media", "customFieldPosition": 6}', 1, '$setId', NOW());

REPLACE INTO `custom_field_set_relation` (`id`, `set_id`, `entity_name`, `created_at`) VALUES
('$relationId', '$setId', 'product', NOW());
SQL;
        $connection->executeUpdate($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
