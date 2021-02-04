<?php

namespace UdgBurmesterTheme\Migration\ContentSections\Creator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;

class CmsSection
{

    /**
     * @param Connection $connection
     * @param array $data
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function createCmsSection(Connection $connection, array $data): string
    {
        $sql = <<<SQL
            REPLACE INTO `cms_section` SET
                `id` = :cms_section_id,
                `name` = :cms_section_name,
                `cms_page_id` = :cms_page_id,
                `position` = :position,
                `css_class` = :css_class,
                `sizing_mode` = :sizing_mode,
                `created_at` = NOW();
SQL;


        $data = array_merge(
            [
                'position' => 0,
                'cms_section_name' => '',
                'css_class' => '',
                'sizing_mode' => 'boxed',
            ],
            $data
        );

        $connection->executeQuery(
            $sql,
            $data
        );

        return $data['cms_section_id'];
    }

    /**
     * @param Connection $connection
     * @param array $data
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function createCmsBlockWithElements(Connection $connection, array $data): void
    {
        $cmsBlockId = self::createCmsBlock($connection, $data['cmsBlock']);

        $i = 1;
        foreach ($data['cmsSlots'] as $dataCmsElements) {
            $cmsSlotId = substr($cmsBlockId, 0, 15 - strlen((string)$i)) . '-' . $i;
            self::createCmsElements(
                $connection,
                array_merge(['cms_block_id' => $cmsBlockId, 'cms_slot_id' => $cmsSlotId], $dataCmsElements)
            );

            $i++;
        }

    }

    /**
     * @param Connection $connection
     * @param array $data
     * @return string
     * @throws DBALException
     */
    protected static function createCmsBlock(Connection $connection, array $data): string
    {

        if (array_key_exists('cms_block_id', $data)) {
            $cmsBlockId = $data['cms_block_id'];
        } else {
            $cmsBlockId = substr(preg_replace(
                    '/Migration\d*/',
                    '',
                    self::getBaseClassname()
                ), 0, 14) . '-1';
        }

        $sql = <<<SQL
            REPLACE INTO `cms_block` SET
                `id` = :cms_block_id,
                `cms_section_id` = :cms_section_id,
                `position` = :position, 
                `section_position` = :section_position, 
                `type` =:type, 
                `name` =:name, 
                `margin_top` =:margin_top, 
                `margin_bottom` =:margin_bottom, 
                `margin_left` =:margin_left, 
                `margin_right` =:margin_right, 
                `background_color` =:background_color, 
                `background_media_id` =:background_media_id, 
                `background_media_mode` =:background_media_mode, 
                `css_class` =:css_class, 
                `custom_fields` =:custom_fields,
                `created_at` = NOW();
SQL;


        $data = array_merge(
            [
                'cms_block_id' => $cmsBlockId,
                'position' => 0,
                'section_position' => 'main',
                'name' => '',
                'margin_top' => '0',
                'margin_bottom' => '0',
                'margin_left' => '0',
                'margin_right' => '0',
                'background_color' => '',
                'background_media_id' => null,
                'background_media_mode' => '',
                'css_class' => '',
                'custom_fields' => '{}',
            ],
            $data
        );

        $connection->executeQuery(
            $sql,
            $data
        );

        return $cmsBlockId;
    }

    /**
     * @param Connection $connection
     * @param array $data
     * @throws \Doctrine\DBAL\DBALException
     */
    protected static function createCmsElements(Connection $connection, array $data): void
    {
        if (array_key_exists('cms_slot_id', $data)) {
            $cmsSlotId = $data['cms_slot_id'];
        } else {
            $cmsSlotId = substr($data['cms_block_id'], 0, 14) . '-1';
        }

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


}
