<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C03BigVideo implements ContentSectionInterface
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
                'css_class' => 'component-big-video theme-cool-copper-dark',
                'sizing_mode' => 'full_width',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'C03BigVideo',
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
                    'type' => 'bigvideo',
                    'name' => '',
                    'css_class' => 'custom-module',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'text',
                        'en_config' => '{"content": {"value": "<h3 class=\\"text-quote\\">From an idea to reality.</h3>\\n<p class=\\"chapter-secondary\\">Making of</p>\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'image',
                        'slot' => 'image21',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "' . CmsMedia::getDemoMediaIdByName($connection, 'DEMO_2880x1920') . '", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-3',
                        'type' => 'image',
                        'slot' => 'image11',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "' . CmsMedia::getDemoMediaIdByName($connection, 'DEMO_600x600') . '", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-4',
                        'type' => 'youtube-video',
                        'slot' => 'videolightbox',
                        'en_config' => '{"end": {"value": null, "source": "static"}, "loop": {"value": false, "source": "static"}, "start": {"value": null, "source": "static"}, "videoID": {"value": "PbG0g2DYSYc", "source": "static"}, "autoPlay": {"value": false, "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "showControls": {"value": false, "source": "static"}, "advancedPrivacyMode": {"value": false, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-5',
                        'type' => 'text',
                        'slot' => 'videodesktop',
                        'en_config' => '{"content": {"value": "", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-6',
                        'type' => 'text',
                        'slot' => 'videomobile',
                        'en_config' => '{"content": {"value": "", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
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
                    'type' => 'text',
                    'name' => '',
                    'css_class' => ' offset-lg-8 col-lg-3 offset-md-6 col-md-5 big-video-caption theme-cool-copper-dark',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'content',
                        'en_config' => '{"content": {"value": "<span class=\\"text-md\\">Optional caption diam nonumy eirmod mpor invidunt ut labore et dolore magna aliquyam e</span>\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
