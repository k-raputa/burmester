<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1581106897RootLanguageSettings extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1581106897;
    }

    public function update(Connection $connection): void
    {
        $query = <<<SQL
            -- set active channels
            UPDATE `sales_channel` SET `active` = 0;
            UPDATE `sales_channel` SET `active` = 1 WHERE `id` IN    
                (SELECT `sales_channel_id` FROM `sales_channel_translation` WHERE name = 'Storefront');
                
            -- set default language
            UPDATE `sales_channel` SET `language_id` = (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE'))
                WHERE `id` IN    
                    (SELECT `sales_channel_id` FROM `sales_channel_translation` WHERE name = 'Storefront');
SQL;

        $connection->executeUpdate($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
