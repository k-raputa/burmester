<?php declare(strict_types=1);

namespace UdgGlobalE\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1588173586BasicInformationGlobalE extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1588173586;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $query = <<<SQL
            DELETE FROM `system_config` WHERE `configuration_key` = :configuration_key; 
            REPLACE INTO `system_config` SET
                `id` = :id,
                `configuration_key` = :configuration_key, 
                `configuration_value` = :configuration_value, 
                `created_at` = NOW(); 
            ; 
SQL;

        $connection->executeUpdate($query, [
            'id' => substr('GEM.ID.basicInformation', 0, 16),
            'configuration_key' => 'UdgGlobalE.config.MerchantID',
            'configuration_value' => '{"_value": "688"}',
        ]);
        $connection->executeUpdate($query, [
            'id' => substr('GEM.GUID.basicInformation', 0, 16),
            'configuration_key' => 'UdgGlobalE.config.MerchantGUID',
            'configuration_value' => '{"_value": "bf9a2cc4-304c-47bc-9722-8967dfd97734"}',
        ]);
    }


    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
