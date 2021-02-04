<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use UdgBurmesterTheme\Migration\Traits\InitBase;


class Migration1583773448BasicInformationShoppages extends MigrationStep
{

    use InitBase;

    public function getCreationTimestamp(): int
    {
        return 1583773448;
    }

    public function update(Connection $connection): void
    {
        $configs = [
            '100.90.1' => 'tosPage',
            '100.90.2' => 'revocationPage',
            '100.90.3' => 'shippingPaymentInfoPage',
            '100.90.4' => 'privacyPage',
            '100.90.5' => 'imprintPage',
            '100.90.6' => '404Page',
            '100.2.3' => 'contactPage',
        ];

        foreach ($configs as $pageTree => $configKey) {

            $this->updateSystemConfigValues($connection, $pageTree, $configKey);
        }
    }

    private function updateSystemConfigValues(Connection $connection, string $pageTree, string $configKey): void
    {

        $query = <<<SQL
            -- set to shopname
            DELETE FROM `system_config` WHERE `configuration_key` = :configuration_key; 
            REPLACE INTO `system_config` SET
                `id` = :id,
                `configuration_key` = :configuration_key, 
                `configuration_value` = :configuration_value, 
                `created_at` = NOW(); 
            ; 
SQL;

        $connection->executeUpdate($query, [
            'id' => substr($configKey . '.basicInformation', 0, 16),
            'configuration_key' => 'core.basicInformation.' . $configKey,
            'configuration_value' => '{"_value": "' . $this->getCmsPageId($connection, $pageTree) . '"}',
        ]);
    }

    private function getCmsPageId($connection, string $pageTree)
    {

        $cmsPageId = $connection->fetchColumn(
            'SELECT `id` FROM `cms_page` WHERE `id` LIKE "'.$pageTree.'\_%"',
            [
            ]
        );

        return Uuid::fromBytesToHex($cmsPageId);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
