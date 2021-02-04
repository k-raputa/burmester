<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1583341091RemoveCurrencies extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1583341091;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $query = <<<SQL
            -- remove all currency, except EUR
            DELETE FROM `currency` WHERE `iso_code` <> 'EUR'; 
SQL;

        $connection->executeUpdate($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
