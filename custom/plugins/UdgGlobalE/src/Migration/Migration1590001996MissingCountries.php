<?php declare(strict_types=1);

namespace UdgGlobalE\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\Traits\Sql\InitSql;

/**
 * Class Migration1590001996MissingCountries
 * @package UdgGlobalE\Migration
 */
class Migration1590001996MissingCountries extends MigrationStep
{
    use InitSql;

    /**
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1590001996;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     */
    public function update(Connection $connection): void
    {
        $connection->executeQuery($this->getSql());
    }

    /**
     * @param Connection $connection
     */
    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
