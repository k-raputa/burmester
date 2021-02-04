<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1583348143SetCartShowDeliveryTime extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1583348143;
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
            'id'=> substr('showDeliveryTime.cart', 0, 16),
            'configuration_key' => 'core.cart.showDeliveryTime',
            'configuration_value' => '{"_value": "true"}',
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
