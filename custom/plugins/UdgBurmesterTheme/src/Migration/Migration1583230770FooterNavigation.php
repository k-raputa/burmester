<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

use UdgBurmesterTheme\Migration\Traits\InitCategories;

class Migration1583230770FooterNavigation extends MigrationStep
{


    use InitCategories;

    public function getCreationTimestamp(): int
    {
        return 1583230770;
    }

    public function update(Connection $connection): void
    {
        $this->createCategories($connection);
        $this->setFooterNavigationToSalesChannel($connection,'100');

    }

    private function setFooterNavigationToSalesChannel(Connection $connection, string $no): void
    {
        $query = <<<SQL
            UPDATE `sales_channel` SET 
                `footer_category_id` = :categoryId,
                `footer_category_version_id` = :versionId
             WHERE `id` IN    
                (SELECT `sales_channel_id` FROM `sales_channel_translation` WHERE name = 'Storefront');
                
SQL;

        $connection->executeUpdate($query, [
            'categoryId' => base64_decode($this->getBaseName($no)),
            'versionId' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
