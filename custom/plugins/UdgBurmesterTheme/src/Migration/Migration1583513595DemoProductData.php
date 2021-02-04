<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;

class Migration1583513595DemoProductData extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1583513595;
    }

    public function update(Connection $connection): void
    {
        //skip import
        return;

        // implement update

        $datas = array_merge(
            $this->getDataA(),
            $this->getDataB(),
            $this->getDataC(),
            $this->getDataD()
        );

        foreach ($datas as $data) {
            $productId = $this->createProduct($connection, $data);
            $this->addMedia($connection, $productId, $data['media']);
            if (array_key_exists('variant', $data)) {
                $this->addVariant($connection, $productId, $data['variant']['color']);
            }
        }
    }

    private function getDataA(): array {

        $data = [];
        for($i = 1; $i < 35; $i++) {

            // Produkt ohne Varianten und ohne Gravur.
            $data[] = [
                'product_number' => $i.'A',
                'stock' => 10,
                'restock_time' => 3,
                'min_purchase' => 1,
                'price' => '{"%1$s": {"net": 840.34, "gross": 1100, "linked": true, "currencyId": "%2$s"}}',
                'property_group_option' => 'Reference Line',
                'en_name' => 'Demo '.$i.'A Cumque',
                'en_custom_fields' => '{}',
                'en_description' => 'Produkt ohne Varianten und ohne Gravur.  Cumque ad et sunt aut dicta ut. Omnis minus aut vitae dignissimos et delectus. Perferendis velit qui in officia est et ut.',
                'media' => [
                    [
                        'media' => 'DEMO_600x600',
                    ],
                    [
                        'media' => 'DEMO_300x600',
                    ],
                    [
                        'media' => 'DEMO_600x300',
                    ]
                ]
            ];
        }

        return $data;
    }

    private function getDataB(): array {

        $data = [];
        for($i = 1; $i < 10; $i++) {
            // Produkt ohne Varianten und mit Gravur.
            $data[] = [
                'product_number' => '10'.$i.'B',
                'stock' => 10,
                'restock_time' => 3,
                'min_purchase' => 1,
                'price' => '{"%1$s": {"net": 840.34, "gross": 1100, "linked": true, "currencyId": "%2$s"}}',
                'property_group_option' => 'Reference Line',
                'en_name' => 'Demo 10'.$i.'B Omnis',
                'en_custom_fields' => '{}',
                'en_description' => 'Produkt ohne Varianten und mit Gravur.  Cumque ad et sunt aut dicta ut. Omnis minus aut vitae dignissimos et delectus. Perferendis velit qui in officia est et ut.',
                'media' => [
                    [
                        'media' => 'DEMO_600x600',
                    ],
                    [
                        'media' => 'DEMO_300x600',
                    ],
                    [
                        'media' => 'DEMO_600x300',
                    ]
                ],
                'swag_customized_products_template_id' => 'gravur_UdgExtend',
            ];
        }

        return $data;
    }

    private function getDataC(): array {

        $data = [];
        for($i = 1; $i < 2; $i++) {

            // Produkt mit Varianten und mit Gravur.
            $data[] = [
                'product_number' => '20'.$i.'C',
                'stock' => 10,
                'restock_time' => 3,
                'min_purchase' => 1,
                'price' => '{"%1$s": {"net": 840.34, "gross": 1100, "linked": true, "currencyId": "%2$s"}}',
                'property_group_option' => 'Reference Line',
                'en_name' => 'Demo 20'.$i.'C Perferendis',
                'en_custom_fields' => '{}',
                'en_description' => 'Produkt mit Varianten und mit Gravur.  Cumque ad et sunt aut dicta ut. Omnis minus aut vitae dignissimos et delectus. Perferendis velit qui in officia est et ut.',
                'media' => [
                    [
                        'media' => 'DEMO_600x600',
                    ],
                    [
                        'media' => 'DEMO_300x600',
                    ],
                    [
                        'media' => 'DEMO_600x300',
                    ]
                ],
                'swag_customized_products_template_id' => 'gravur_UdgExtend',
                'variant' => ['color' => ['#c5c5c5', '#856230', '#000000']],
            ];
        }

        return $data;
    }

    private function getDataD(): array {

        $data = [];
        for($i = 1; $i < 2; $i++) {
            // Produkt mit Varianten und ohne Gravur.
            $data[] = [
                'product_number' => '30'.$i.'D',
                'stock' => 10,
                'restock_time' => 3,
                'min_purchase' => 1,
                'price' => '{"%1$s": {"net": 840.34, "gross": 1100, "linked": true, "currencyId": "%2$s"}}',
                'property_group_option' => 'Reference Line',
                'en_name' => 'Demo 30'.$i.'D dignissimos',
                'en_custom_fields' => '{}',
                'en_description' => 'Produkt mit Varianten und ohne Gravur.  Cumque ad et sunt aut dicta ut. Omnis minus aut vitae dignissimos et delectus. Perferendis velit qui in officia est et ut.',
                'media' => [
                    [
                        'media' => 'DEMO_600x600',
                    ],
                    [
                        'media' => 'DEMO_300x600',
                    ],
                    [
                        'media' => 'DEMO_600x300',
                    ]
                ],
                'variant' => ['color' => ['#c5c5c5', '#856230', '#000000']],
            ];
        }

        return $data;
    }


    private function createProduct(Connection $connection, array $data): string
    {
        $productId = 'DEMO_' . $data['product_number'];

        $data['price'] = sprintf(
            $data['price'],
            bin2hex($productId),
            $this->getCurrency($connection)
        );

        if (!array_key_exists('de_name', $data) && array_key_exists('en_name', $data)) {
            $data['de_name'] = $data['en_name'];
        }
        if (!array_key_exists('de_custom_fields', $data) && array_key_exists('en_custom_fields', $data)) {
            $data['de_custom_fields'] = $data['en_custom_fields'];
        }
        if (!array_key_exists('de_description', $data) && array_key_exists('en_description', $data)) {
            $data['de_description'] = $data['en_description'];
        }

        $data = array_merge(
            [
                'product_id' => $productId,
                'cover_id' => $productId . '-0',
                'version' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
                'delivery_time_id' => $this->getDeliveryTime($connection),
                'swag_customized_products_template_id' => '',
            ],
            $data
        );

        $sqls = [];
        $sqls[] = <<<SQL
            REPLACE INTO `product` SET
                `id` = :product_id, 
                `version_id` = :version, 
                `product_number` = :product_number, 
                `active` = 1,  
                `stock` = :stock,
                `available_stock` = :stock,
                `available` = 1,
                `min_purchase` = :min_purchase,
                `price` = :price,
                `cover` = :cover_id,
                `tax_id` = (SELECT `id` FROM tax WHERE `name` = 'High tax'),
                `tax` = (SELECT `id` FROM tax WHERE `name` = 'High tax'),
                `media` = :product_id,
                `prices` = :product_id,
                `visibilities` = :product_id,
                `properties` = :product_id,
                `categories` = :product_id,
                `translations` = :product_id,
                `tags` = :product_id,
                `crossSellings` = :product_id,
                `delivery_time_id` =:delivery_time_id,
                `deliveryTime` =:delivery_time_id,
                `swag_customized_products_template_id` = :swag_customized_products_template_id,
                `swagCustomizedProductsTemplate` = :swag_customized_products_template_id,
                `created_at` = NOW();
SQL;
        $sqls[] = <<<SQL
            REPLACE INTO `product_translation` SET 
                `product_id` = :product_id, 
                `product_version_id` = :version, 
                `name` = :en_name, 
                `custom_fields` = :en_custom_fields,
                `description` = :en_description, 
                `language_id`= (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 
                `created_at` = NOW();
SQL;
        $sqls[] = <<<SQL
            REPLACE INTO `product_translation` SET 
                `product_id` = :product_id, 
                `product_version_id` = :version, 
                `name` = :de_name, 
                `custom_fields` = :de_custom_fields,
                `description` = :de_description, 
                `language_id`= (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), 
                `created_at` = NOW();
SQL;
        $sqls[] = <<<SQL
            REPLACE INTO `product_property` SET 
                `product_id` = :product_id, 
                `product_version_id` = :version, 
                `property_group_option_id` = (SELECT `property_group_option_id` FROM `property_group_option_translation` WHERE name = :property_group_option);
SQL;
        $sqls[] = <<<SQL
            REPLACE INTO `product_visibility` SET 
                `id` = :product_id, 
                `product_id` = :product_id, 
                `product_version_id` = :version, 
                `sales_channel_id` = (SELECT `sales_channel_id` FROM `sales_channel_translation` WHERE name = 'Storefront'),
                `visibility` = 30,
                `created_at` = NOW();
SQL;
        foreach ($sqls as $sql) {

            $connection->executeQuery(
                $sql,
                $data
            );
        }
        return $productId;
    }

    private function addMedia(Connection $connection, string $productId, array $mediaDatas): void
    {

        $sqls = [];
        $sqls[] = <<<SQL
            REPLACE INTO `product_media` SET
                `id` = :id,
                `version_id` = :version,
                `position` = :position,
                `product_id` = :product_id, 
                `product_version_id` = :version, 
                `media_id` = :media_id, 
                `created_at` = NOW();
SQL;

        foreach ($mediaDatas as $position => $data) {

            $data['media_id'] = Uuid::fromHexToBytes(CmsMedia::getDemoMediaIdByName($connection, $data['media']));
            $data = array_merge(
                [
                    'id' => $productId . '-' . $position,
                    'position' => $position,
                    'product_id' => $productId,
                    'version' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
                ],
                $data
            );

            foreach ($sqls as $sql) {

                $connection->executeQuery(
                    $sql,
                    $data
                );
            }
        }
    }

    private function addVariant(Connection $connection, string $productId, array $propertyData): void
    {


        $sqls = [];
        $sqls[] = <<<SQL
            REPLACE INTO `product_property` SET 
                `product_id` = :product_id, 
                `product_version_id` = :version, 
                `property_group_option_id` = (SELECT `id` FROM `property_group_option` WHERE color_hex_code = :color_hex_code);
SQL;

        // @todo generate variants...

        foreach ($propertyData as $color_hex_code) {


            $data = [
                'color_hex_code' => $color_hex_code,
                'product_id' => $productId,
                'version' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
            ];

            foreach ($sqls as $sql) {

                $connection->executeQuery(
                    $sql,
                    $data
                );
            }
        }
    }


    private function getCurrency(Connection $connection): string
    {

        $sql = <<<SQL
SELECT `id`
FROM `currency` 
WHERE `iso_code` = 'EUR' 
SQL;

        $currencyId = $connection->executeQuery($sql)->fetchColumn();

        return Uuid::fromBytesToHex((string)$currencyId);
    }

    private function getDeliveryTime(Connection $connection): string
    {

        $sql = <<<SQL
SELECT `id`
FROM `delivery_time` 
LIMIT 0,1 
SQL;

        $deliveryId = $connection->executeQuery($sql)->fetchColumn();

        return (string)$deliveryId;
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
