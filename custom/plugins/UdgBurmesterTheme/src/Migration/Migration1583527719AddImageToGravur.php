<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;

class Migration1583527719AddImageToGravur extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1583527719;
    }

    public function update(Connection $connection): void
    {

        $sql = <<<SQL
            UPDATE `swag_customized_products_template` SET
                `media_id` = :media_id
                WHERE internal_name = 'gravur';
SQL;

        $connection->executeQuery(
            $sql,
            [
                'media_id' => Uuid::fromHexToBytes(CmsMedia::getDemoMediaIdByName($connection,'DEMO_600x600')),
            ]
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
