<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1593075866BaseAuthConfig extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1593075866;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $query = <<<SQL
            -- activate basic auth
            DELETE FROM `system_config` WHERE `configuration_key` = :configuration_key; 
            REPLACE INTO `system_config` SET
                `id` = :id,
                `configuration_key` = :configuration_key, 
                `configuration_value` = :configuration_value, 
                `created_at` = NOW(); 
            ;
SQL;

        $connection->executeUpdate($query, [
            'id' => Uuid::randomBytes(),
            'configuration_key' => 'derskoBasicAuthSW6.config.active',
            'configuration_value' => '{"_value": true}',
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
