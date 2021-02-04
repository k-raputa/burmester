<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1583229824BasicInformation extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1583229824;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $query = <<<SQL
            -- set to shopname
            DELETE FROM `system_config` WHERE `configuration_key` = :configuration_key; 
            REPLACE INTO `system_config` SET
                `id` = :id,
                `configuration_key` = :configuration_key, 
                `configuration_value` = :configuration_value, 
                `created_at` = NOW(); 
            ; 
SQL;

        $connection->executeUpdate($query, [
            'id'=> substr('shopName.basicInformation', 0, 16),
            'configuration_key' => 'core.basicInformation.shopName',
            'configuration_value' => '{"_value": "Burmester"}',
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
