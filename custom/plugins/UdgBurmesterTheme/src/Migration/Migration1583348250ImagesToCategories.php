<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;

class Migration1583348250ImagesToCategories extends MigrationStep
{

    public function getCreationTimestamp(): int
    {
        return 1583348150;
    }

    public function update(Connection $connection): void
    {

        $query = <<<SQL
            UPDATE `category` SET 
                `media_id` = :mediaId
SQL;

        $connection->executeUpdate($query, [
            'mediaId' => Uuid::fromHexToBytes(CmsMedia::getDemoMediaIdByName($connection, 'DEMO_750x500')),
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
