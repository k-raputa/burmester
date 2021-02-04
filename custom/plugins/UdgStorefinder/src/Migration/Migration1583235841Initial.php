<?php declare(strict_types=1);

namespace UdgStorefinder\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\Traits\Sql\InitSql;

/**
 * Todo: Update api key and url settings
 * Class Migration1583235841Initial
 * @package UdgStorefinder\Migration
 */
class Migration1583235841Initial extends MigrationStep
{
    use InitSql;

    /**
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1583235841;
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
