<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1581106973DomainLanguageSettings extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1581106973;
    }

    public function update(Connection $connection): void
    {
        switch ($_SERVER['APP_URL']) {
            case 'https://dev.burmester.de':
            case 'https://staging.burmester.de':
            case 'https://live.burmester.de':
                $url = $_SERVER['APP_URL'];
                break;
            default:
                $url = 'http://shopware.local';
                break;
        }



        $query = <<<SQL
            DELETE FROM `sales_channel_domain`;
            -- set domains
            REPLACE INTO `sales_channel_domain`
                SET `id` = FROM_BASE64('EVsXKDoAS+uKhgHRQGwzeA=='),
                    `sales_channel_id` = (SELECT `sales_channel_id` FROM `sales_channel_translation` WHERE name = 'Storefront'), 
                    `language_id` = (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), 
                    `url` = '$url', 
                    `currency_id` = (SELECT `id` FROM `currency` WHERE iso_code = 'EUR'), 
                    `snippet_set_id` = (SELECT `id` FROM `snippet_set` WHERE name = 'BASE de-DE'), 
                    `created_at` = NOW();
            REPLACE INTO `sales_channel_domain`
                SET `id` = FROM_BASE64('IJnaj1ElRyWFikguz+0EsA=='),
                    `sales_channel_id` = (SELECT `sales_channel_id` FROM `sales_channel_translation` WHERE name = 'Storefront'), 
                    `language_id` = (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 
                    `url` = '$url/en', 
                    `currency_id` = (SELECT `id` FROM `currency` WHERE iso_code = 'EUR'), 
                    `snippet_set_id` = (SELECT `id` FROM `snippet_set` WHERE name = 'BASE en-GB'), 
                    `created_at` = NOW();
SQL;

        $connection->executeUpdate($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
