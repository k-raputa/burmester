<?php declare(strict_types=1);

namespace UdgExtendedProductDetailPage\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1581107378CustomCmsFields extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1581107378;
    }

    public function update(Connection $connection): void
    {

        $setId = substr('cms_UdgExPDP', 0, 16);
        $relationId = substr('product_' . $setId, 0, 16);

        // implement update
        $query = <<<SQL
-- create custom field 'udgextendedpagetemplate_css'
REPLACE INTO `custom_field_set` (`id`, `name`, `config`, `active`, `created_at`)
 VALUES ('$setId', 'UdgExtendedProductDetailPage_CMS', '{"label": {"en-GB": "CMS page"}}', 1, NOW());

REPLACE INTO `custom_field` (`id`, `name`, `type`, `config`, `active`, `set_id`, `created_at`) VALUES
('UEPDP_categoryid', 'udgextendedproductdetailpage_categoryid4cmspage', 'text', '{"type": "text", "label": {"en-GB": "CategoryId for CmsPage"}, "helpText": {"en-GB": null}, "validation": "required", "placeholder": {"en-GB": null}, "componentName": "sw-field", "customFieldType": "text", "customFieldPosition": 1}', 1, '$setId', NOW());

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
