<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class T06TeaserContent implements ContentSectionInterface
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
                'css_class' => 'component-teaser-content',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'T06TeaserContent',
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
                    'css_class' => '',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'content',
                        'en_config' => '{"content": {"value": "Empfohlene Artikel", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
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
                    'type' => 'image-text-row-2',
                    'name' => '',
                    'css_class' => '',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'image',
                        'slot' => 'left-image',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_750x500').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": "flex-end", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId. '-2',
                        'type' => 'text',
                        'slot' => 'left-text',
                        'en_config' => '{"content": {"value": "<h2>Lorem Ipsum dolor sit amet</h2>\\n                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, \\n                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.</p><a class=\\"btn btn-link\\" href=\\"#\\">Mehr erfahren</a>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-3',
                        'type' => 'image',
                        'slot' => 'right-image',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_750x500').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": "flex-end", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-4',
                        'type' => 'text',
                        'slot' => 'right-text',
                        'en_config' => '{"content": {"value": "<h2>Lorem Ipsum dolor sit amet</h2>\\n                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, \\n                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.</p><a class=\\"btn btn-link\\" href=\\"#\\">Mehr erfahren</a>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
