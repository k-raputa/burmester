<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use UdgBurmesterTheme\Migration\Traits\InitBase;


class Migration1583773450ShoppagesHideInNavi extends MigrationStep
{

    use InitBase;

    public function getCreationTimestamp(): int
    {
        return 1583773450;
    }

    public function update(Connection $connection): void
    {
        $configs = [
            '100.90',
        ];

        foreach ($configs as $pageTree) {

            $this->hideInNavigation($connection, $pageTree);
        }
    }

    private function hideInNavigation(Connection $connection, string $pageTree): void
    {

        $query = <<<SQL
            UPDATE `category` SET
                `visible` = 0,
                `active` = 0
                WHERE `id` = :id; 
            ; 
SQL;

        $connection->executeUpdate($query, [
            'id' => $this->getCategoryId($pageTree),
        ]);
    }

    private function getCategoryId(string $no)
    {

        return base64_decode($this->getBaseName($no));
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
