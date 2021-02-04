<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C13Downloads implements ContentSectionInterface
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
                'css_class' => 'component-downloads theme-pearl-bush',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'C13Downloads',
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
                    'type' => 'text',
                    'name' => '',
                    'css_class' => 'offset-lg-2 col-lg-8 offset-md-1 col-md-10',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'content',
                        'en_config' => '{"content": {"value": "<h2 class=\\"h1\\">\\n\\tDownloads\\n</h2>\\n\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );

        $cmsBlockId = $cmsSectionId . '-2';
        CmsSection::createCmsBlockWithElements(
            $connection,
            [
                'cmsBlock' => [
                    'cms_section_id' => $cmsSectionId,
                    'cms_block_id' => $cmsBlockId,
                    'position' => 2,
                    'type' => 'image-two-column',
                    'name' => '',
                    'css_class' => 'offset-md-1 col-md-10',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'udg-download',
                        'slot' => 'left',
                        'en_config' => '{"media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection,'DEMO_PDF').'", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'udg-download',
                        'slot' => 'right',
                        'en_config' => '{"media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection,'DEMO_PDF').'", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );

    }
}
