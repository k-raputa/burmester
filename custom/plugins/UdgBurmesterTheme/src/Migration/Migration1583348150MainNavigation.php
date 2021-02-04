<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1583348150MainNavigation extends MigrationStep
{

    public function getCreationTimestamp(): int
    {
        return 1583348150;
    }

    public function update(Connection $connection): void
    {
        $this->setMainNavigationDepth($connection);
    }

    private function setMainNavigationDepth(Connection $connection): void
    {
        $query = <<<SQL
            UPDATE `sales_channel` SET 
                `navigation_category_depth` = :categoryDepth
             WHERE `id` IN    
                (SELECT `sales_channel_id` FROM `sales_channel_translation` WHERE name = 'Storefront');
                
SQL;

        $connection->executeUpdate($query, [
            'categoryDepth' => 3
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
