<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;

use Shopware\Core\Framework\Uuid\Uuid;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1600852334SwitchSpliderNameField extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1600852334;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     */
    public function update(Connection $connection): void
    {
        $sql = <<<SQL
            SELECT id, name 
            FROM `cms_block` 
            WHERE type = 'biggercontentslider' OR type = 'bigcontentslider'
SQL;

        foreach ($connection->executeQuery(
            $sql
        )->fetchAll() as $cmsBlockData) {

            $data = [
                'cms_block_id' => $cmsBlockData['id'],
                'type' => 'text',
                'slot' => 'slidername',
                'en_config' => '{"content": {"value": "' . $cmsBlockData['name'] . '", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                'en_custom_fields' => '{}',
            ];
            self::createCmsElements($connection, $data);

        }
    }

    /**
     * @param Connection $connection
     * @param array $data
     * @throws \Doctrine\DBAL\DBALException
     */
    protected static function createCmsElements(Connection $connection, array $data): void
    {
        $cmsSlotId = Uuid::randomBytes();

        if (!array_key_exists('de_config', $data) && array_key_exists('en_config', $data)) {
            $data['de_config'] = $data['en_config'];
        }
        if (!array_key_exists('de_custom_fields', $data) && array_key_exists('en_custom_fields', $data)) {
            $data['de_custom_fields'] = $data['en_custom_fields'];
        }

        $sql = <<<SQL
            REPLACE INTO `cms_slot` (`id`, `version_id`, `cms_block_id`, `type`, `slot`, `created_at`) VALUES
                (:cms_slot_id, :version_id, :cms_block_id, :type, :slot, NOW());
            
            REPLACE INTO `cms_slot_translation` (`cms_slot_id`, `cms_slot_version_id`, `language_id`, `config`, `custom_fields`, `created_at`) VALUES
                (:cms_slot_id, :version_id, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), :en_config, :en_custom_fields, NOW());
            REPLACE INTO `cms_slot_translation` (`cms_slot_id`, `cms_slot_version_id`, `language_id`, `config`, `custom_fields`, `created_at`) VALUES
                (:cms_slot_id, :version_id, (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), :de_config, :de_custom_fields, NOW());
SQL;


        $data = array_merge(
            [
                'en_config' => '{}',
                'de_config' => '{}',
                'cms_slot_id' => $cmsSlotId,
                'version_id' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
            ],
            $data
        );

        $connection->executeQuery(
            $sql,
            $data
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
