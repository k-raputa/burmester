<?php

namespace UdgBurmesterTheme\Migration\ContentSections\Creator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;

class CmsMedia
{

    /**
     * @param Connection $connection
     * @param string $name
     * @return string
     * @throws DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    public static function getDemoMediaIdByName(Connection $connection, $name): string
    {

        $sql = <<<SQL
SELECT `id`
FROM `media` 
WHERE `file_name` = :file_name 
SQL;

        $mediaId = $connection->executeQuery($sql, [
            'file_name' => $name
        ])->fetchColumn();
        if ($mediaId !== false) {

            return Uuid::fromBytesToHex($mediaId);
        }

        die(
            'Missing DEMO media for ' . $name . '
            Import via: 
            bin/console udg:media:upload custom/plugins/UdgBurmesterTheme/src/Migration/' . self::getBaseClassname() . '_demo_files.csv
            '
        );
    }

    /**
     * @return string
     */
    private static function getBaseClassname(): string
    {
        return substr(__CLASS__, strrpos(__CLASS__, "\\") + 1);
    }
}
