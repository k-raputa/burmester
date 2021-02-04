<?php

namespace UdgBurmesterTheme\Migration\ContentSections\Creator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;

class CmsProduct
{

    /**
     * @param Connection $connection
     * @return string
     * @throws DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    public static function getDemoProductIdByProductNumber(Connection $connection, string $productNumber): string
    {
        $sql = <<<SQL
SELECT `id`
FROM `product` 
WHERE `product_number` = :productNumber 
SQL;

        $productId = $connection->executeQuery($sql, [
            'productNumber' => $productNumber,
        ])->fetchColumn();
        if ($productId !== false) {

            return Uuid::fromBytesToHex($productId);
        }

        die(
            'Missing DEMO product for ' . $productNumber
        );
    }
}
