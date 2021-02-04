<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1582201233MailConfig extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1582201233;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $query = <<<SQL
            -- set to env usage
            DELETE FROM `system_config` WHERE `configuration_key` = 'core.mailerSettings.emailAgent'; 
            
SQL;

        $connection->executeUpdate($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
