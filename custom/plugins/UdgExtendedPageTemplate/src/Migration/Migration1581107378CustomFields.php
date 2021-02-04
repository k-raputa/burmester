<?php declare(strict_types=1);

namespace UdgExtendedPageTemplate\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1581107378CustomFields extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1581107378;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $query = <<<SQL
-- create custom field 'udgextendedpagetemplate_css'
REPLACE INTO `custom_field_set` (`id`, `name`, `config`, `active`, `created_at`)
 VALUES (FROM_BASE64('4mnbqRToTIGzlK2qItCpKA=='), 'UdgExtendedPageTemplate', '{"label": {"en-GB": "CSS class"}}', 1,  NOW());

REPLACE INTO `custom_field` (`id`, `name`, `type`, `config`, `active`, `set_id`, `created_at`) VALUES
(FROM_BASE64('dw3e+XhoSvaw8K5Vz7Bxrg=='), 'udgextendedpagetemplate_css', 'text', '{"type": "text", "label": {"en-GB": "CSS"}, "helpText": {"en-GB": null}, "validation": "required", "placeholder": {"en-GB": null}, "componentName": "sw-field", "customFieldType": "text", "customFieldPosition": 1}', 1, FROM_BASE64('4mnbqRToTIGzlK2qItCpKA=='),  NOW());

REPLACE INTO `custom_field_set_relation` (`id`, `set_id`, `entity_name`, `created_at`, `updated_at`) VALUES
(FROM_BASE64('d2s5sOORQgWSxIhOOxTMuw=='), FROM_BASE64('4mnbqRToTIGzlK2qItCpKA=='), 'cms_page', NOW(), NULL);
SQL;

        $connection->executeUpdate($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
