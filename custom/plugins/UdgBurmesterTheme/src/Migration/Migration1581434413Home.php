<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

use UdgBurmesterTheme\Migration\Traits\InitCategories;

class Migration1581434413Home extends MigrationStep
{


    use InitCategories;

    public function getCreationTimestamp(): int
    {
        return 1581434413;
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     */
    public function update(Connection $connection): void
    {
        $this->cleanCategories($connection);
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    private function cleanCategories(Connection $connection)
    {
        $query = <<<SQL
            UPDATE `category_translation` SET `name` = 'Home' WHERE 
               `category_id` IN (SELECT `id` FROM `category` WHERE LEVEL = 1);
SQL;

        $connection->executeQuery($query);
    }


    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
