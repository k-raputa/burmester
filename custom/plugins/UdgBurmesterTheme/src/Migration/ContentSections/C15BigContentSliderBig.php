<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C15BigContentSliderBig implements ContentSectionInterface
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
                'css_class' => ' theme-white',
                'sizing_mode' => 'full_width',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'C15BigContentSliderBig',
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
                    'type' => 'biggercontentslider',
                    'name' => 'Slide ' . $position,
                    'css_class' => 'custom-module',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'text',
                        'en_config' => '{"content": {"value": "<p class=\\"chapter-default\\">\\n\\tProduktlinien Slide-' . $position . '\\n</p>\\n\\n<h3 class=\\"h1\\">\\n\\tLorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt.\\n</h3>\\n\\n<a class=\\"btn btn-link\\" href=\\"#\\">\\n\\tLoremipsumdolor\\n</a>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
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
                ]
            ]
        );

    }
}
