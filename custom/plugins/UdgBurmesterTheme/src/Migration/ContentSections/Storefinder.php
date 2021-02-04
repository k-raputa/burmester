<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class Storefinder implements ContentSectionInterface
{

    /**
     * @param Connection $connection
     * @param string $cmsPageId
     * @param string $cmsBaseId
     * @param int $position
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    public static function createSection(Connection $connection, string $cmsPageId, string $cmsBaseId, int $position = 0)
    {

        $cmsSectionId = CmsSection::createCmsSection(
            $connection,
            [
                'cms_page_id' => $cmsPageId,
                'css_class' => '',
                'sizing_mode' => 'full_width',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'Storefinder',
                'position' => $position,
            ]
        );

        $cmsBlockId = $cmsSectionId . '-1';
        CmsSection::createCmsBlockWithElements(
            $connection,
            [
                'cmsBlock' => [
                    'cms_section_id' => $cmsSectionId,
                    'cms_block_id' => $cmsBlockId,
                    'position' => 1,
                    'type' => 'form',
                    'name' => '',
                    'css_class' => 'custom-modules',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'storefinder',
                        'slot' => 'content',
                        'en_config' => '{}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
