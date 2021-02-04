<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583773542AddCmsPagesToHome extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583773542;
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    public function update(Connection $connection): void
    {

        $this->createCmsPage(
            $connection,
            [
                'no' => 0,
                'en_name' => '0 Home',
            ]
        );

        $query = <<<SQL
            UPDATE `category` SET `cms_page_id` = (SELECT `id` FROM `cms_page` WHERE `id` LIKE "0\_%")  
               WHERE `auto_increment` = 1 AND LEVEL = 1;
SQL;

        $connection->executeQuery($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
