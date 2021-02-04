<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class S01Stage1Video implements ContentSectionInterface
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
                'css_class' => 'component-stage1 theme-silver-grey-light',
                'sizing_mode' => 'full_width',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'S01Stage1Video',
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
                    'type' => 'stage',
                    'name' => '',
                    'css_class' => 'custom-module',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'text',
                        'en_config' => '{"content": {"value": "<h1 class=\\"headline-product\\">\\n\\t705\\n</h1>\\n<h1>\\n\\tStage mit Video. Lorem ipsum dolor.\\n</h1>\\n<p>\\n\\tStet clita kasd gubergren, no sea takimata sanctus est Lorem takimata.\\n</p>\\n<a class=\\"btn btn-link\\" href=\\"#\\">\\n\\tMehr erfahren\\n</a>\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'image',
                        'slot' => 'image32',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "' . CmsMedia::getDemoMediaIdByName($connection, 'DEMO_750x500') . '", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
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
                        'slot' => 'video',
                        'en_config' => '{"end": {"value": null, "source": "static"}, "loop": {"value": false, "source": "static"}, "start": {"value": null, "source": "static"}, "videoID": {"value": "PbG0g2DYSYc", "source": "static"}, "autoPlay": {"value": false, "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "showControls": {"value": false, "source": "static"}, "advancedPrivacyMode": {"value": false, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
