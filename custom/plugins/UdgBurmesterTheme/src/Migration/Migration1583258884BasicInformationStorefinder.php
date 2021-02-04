<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use UdgBurmesterTheme\Migration\Traits\InitBase;


class Migration1583258884BasicInformationStorefinder extends MigrationStep
{

    use InitBase;

    public function getCreationTimestamp(): int
    {
        return 1583258884;
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
            'id' => substr('storefinder.basicInformation', 0, 16),
            'configuration_key' => 'core.basicInformation.storefinder',
            'configuration_value' => '{"_value": "' . $this->getCategoryId('100.2.1') . '"}',
        ]);
    }

    private function getCategoryId(string $no)
    {

        return Uuid::fromBytesToHex(base64_decode($this->getBaseName($no)));
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
