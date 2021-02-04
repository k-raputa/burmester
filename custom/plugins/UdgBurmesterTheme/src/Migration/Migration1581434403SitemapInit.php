<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

use UdgBurmesterTheme\Migration\Traits\InitCategories;

class Migration1581434403SitemapInit extends MigrationStep
{


    use InitCategories;

    public function getCreationTimestamp(): int
    {
        return 1581434403;
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     */
    public function update(Connection $connection): void
    {
        $this->cleanCategories($connection);
        $this->createCategories($connection);
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    private function cleanCategories(Connection $connection)
    {
        $query = <<<SQL
            DELETE FROM `category` WHERE level = 2; 
            UPDATE `category` SET `child_count` = 0;
SQL;

        $connection->executeQuery($query);
    }


    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
