<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

use UdgBurmesterTheme\Migration\Traits\InitCategories;

class Migration1583230781ServiceNavigation extends MigrationStep
{


    use InitCategories;

    public function getCreationTimestamp(): int
    {
        return 1583230781;
    }

    public function update(Connection $connection): void
    {
        $this->createCategories($connection);
        $this->setFooterNavigationToSalesChannel($connection,'200');

        $this->setLink($connection, 'https://www.facebook.com/Burmesteraudiosysteme/', '200.1');
        $this->setLink($connection, 'https://www.instagram.com/burmesteraudio/', '200.2');
        $this->setLink($connection, 'https://www.youtube.com', '200.3');
        $this->setLink($connection, 'https://de.pinterest.com/burmesterberlin/', '200.4');

    }

    private function setFooterNavigationToSalesChannel(Connection $connection, string $no): void
    {
        $query = <<<SQL
            UPDATE `sales_channel` SET 
                `service_category_id` = :categoryId,
                `service_category_version_id` = :versionId
             WHERE `id` IN    
                (SELECT `sales_channel_id` FROM `sales_channel_translation` WHERE name = 'Storefront');
                
SQL;

        $connection->executeUpdate($query, [
            'categoryId' => base64_decode($this->getBaseName($no)),
            'versionId' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
        ]);
    }

    private function setLink(Connection $connection, $url, $no): void
    {
        $query = <<<SQL
            UPDATE `category_translation` SET 
                `external_link` = :externalLink
             WHERE `category_id` = :categoryId;
                
SQL;

        $connection->executeUpdate($query, [
            'categoryId' => base64_decode($this->getBaseName($no)),
            'externalLink' => $url,
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
