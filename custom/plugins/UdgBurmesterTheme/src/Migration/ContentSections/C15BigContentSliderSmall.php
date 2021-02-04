<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C15BigContentSliderSmall implements ContentSectionInterface
{



    /**
     * @param Connection $connection
     * @param string $cmsPageId
     * @param string $cmsBaseId
     * @param int $position
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function createSection(Connection $connection, string $cmsPageId, string $cmsBaseId, int $position = 0)
    {

        $cmsSectionId = CmsSection::createCmsSection(
            $connection,
            [
                'cms_page_id' => $cmsPageId,
                'css_class' => 'component-big-content-slider-small slider theme-mine-shaft',
                'sizing_mode' => 'boxed',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'C15BigContentSliderSmall',
                'position' => $position,
            ]
        );

        self::addCmsSection($connection, $cmsSectionId, 1);
        self::addCmsSection($connection, $cmsSectionId, 2);
        self::addCmsSection($connection, $cmsSectionId, 3);
        self::addCmsSection($connection, $cmsSectionId, 4);
        self::addCmsSection($connection, $cmsSectionId, 5);
        self::addCmsSection($connection, $cmsSectionId, 6);
        self::addCmsSection($connection, $cmsSectionId, 7);
    }


    private static function addCmsSection(Connection $connection, string $cmsSectionId, int $position)
    {

        $cmsBlockId = $cmsSectionId . '-' . $position;

        CmsSection::createCmsBlockWithElements(
            $connection,
            [
                'cmsBlock' => [
                    'cms_section_id' => $cmsSectionId,
                    'cms_block_id' => $cmsBlockId,
                    'position' => $position,
                    'type' => 'bigcontentslider',
                    'name' => 'Slide ' . $position,
                    'css_class' => 'slide',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'top',
                        'en_config' => '{"content": {"value": "<p class=\\"chapter-default\\">\\n\\tSysteme Slide-' . $position . '\\n</p>\\n<p class=\\"h1\\">\\n\\tSystem Slide-' . $position . ' lorem ipsum\\n</p>\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'image',
                        'slot' => 'middle',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "' . CmsMedia::getDemoMediaIdByName($connection, 'DEMO_750x500') . '", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value":  null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-3',
                        'type' => 'text',
                        'slot' => 'bottom',
                        'en_config' => '{"content": {"value": "<p class=\\"text-lg\\">\\n\\tLorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.\\n</p>\\n<div>\\n\\t<div class=\\"button-group\\">\\n\\t\\t<a class=\\"btn btn-link\\" href=\\"https://www.burmester.de/\\">\\n\\t\\t\\tZu unserem Support-Center\\n\\t\\t</a>\\n\\t</div>\\n</div>\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );

    }
}
