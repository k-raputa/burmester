<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1583954660AllowedThumbnailSizes extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1583954660;
    }

    public function update(Connection $connection): void
    {
        $query = <<<SQL
            DELETE FROM `media_thumbnail_size`;
SQL;
        $connection->executeQuery($query);

        $sizes = [
            '3x4' => ['640x853', '960x1280', '1280x1707', '1600x2133', '2400x3200'],
            '4x3' => ['640x480', '960x720', '1280x960', '1600x1200', '2400x1800'],
            '3x2' => ['640x427', '960x640', '1280x853', '1600x1067', '2400x1600'],
            '2x1' => ['640x320', '960x480', '1280x640', '1600x800', '2400x1200'],
            '1x1' => ['640x640', '960x960', '1280x1280', '1600x1600', '2400x2400'],
        ];

        foreach ($sizes as $ratio) {
            foreach ($ratio as $xy) {
                $this->addSize($connection, $xy);
            }
        }
    }

    private function addSize(Connection $connection, string $id): void
    {
        $xy = explode('x', $id);

        $query = <<<SQL
            REPLACE INTO `media_thumbnail_size`
                SET `id` = :id,
                    `width` = :width,
                    `height` = :height,
                    `created_at` = NOW();
SQL;

        $connection->executeQuery($query, [
            'id' => $id,
            'width' => $xy[0],
            'height' => $xy[1],
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
