<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C06Gallery implements ContentSectionInterface
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
                'css_class'   => 'component-gallery',
                'sizing_mode' => 'full_width',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'C06Gallery',
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
                    'css_class' => 'col-lg-5 col-md-6 offset-md-6 intro',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'content',
                        'en_config' => '{"content": {"value": "<p class=\\"chapter-default\\">Materialien</p>\\n<h2 class=\\"h1\\">Mit Liebe für feinste Details.</h2>\\n<p class=\\"text-lg\\">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.</p>\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
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
                    'position' => 1,
                    'type' => 'image-two-column',
                    'name' => '',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'image',
                        'slot' => 'left',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_750x1000').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": "flex-end", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'image',
                        'slot' => 'right',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_750x500').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": "flex-end", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );

        $cmsBlockId = $cmsSectionId . '-3';
        CmsSection::createCmsBlockWithElements(
            $connection,
            [
                'cmsBlock' => [
                    'cms_section_id' => $cmsSectionId,
                    'cms_block_id' => $cmsBlockId,
                    'position' => 1,
                    'type' => 'image-31',
                    'name' => '',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'image',
                        'slot' => 'left',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_1080x720').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": "flex-end", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'text',
                        'slot' => 'right',
                        'en_config' => '{"content": {"value": "", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );

        $cmsBlockId = $cmsSectionId . '-4';
        CmsSection::createCmsBlockWithElements(
            $connection,
            [
                'cmsBlock' => [
                    'cms_section_id' => $cmsSectionId,
                    'cms_block_id' => $cmsBlockId,
                    'position' => 1,
                    'type' => 'image-13',
                    'name' => '',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'image',
                        'slot' => 'left',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_450x600').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": "flex-end", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId. '-2',
                        'type' => 'image',
                        'slot' => 'right',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_1080x1440').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": "flex-end", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );

        $cmsBlockId = $cmsSectionId . '-5';
        CmsSection::createCmsBlockWithElements(
            $connection,
            [
                'cmsBlock' => [
                    'cms_section_id' => $cmsSectionId,
                    'cms_block_id' => $cmsBlockId,
                    'position' => 1,
                    'type' => 'image',
                    'name' => '',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'image',
                        'slot' => 'image',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_2880x1920').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": "flex-end", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
