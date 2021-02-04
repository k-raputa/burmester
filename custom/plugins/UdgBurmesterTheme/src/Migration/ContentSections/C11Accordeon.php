<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C11Accordeon implements ContentSectionInterface
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
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'C11Accordeon',
                'position' => $position,
                'css_class' => 'component-accordion theme-copper-green-dark',
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
                    'css_class' => 'col-sm-12',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'content',
                        'en_config' => '{"content": {"value": "<h2 style=\\"text-align: center;\\" class=\\"chapter-headline chapter-secondary\\">\\n\\t\\tHäufig gestellte Fragen\\n\\t</h2>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
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
                    'type' => 'accordeon',
                    'name' => '(C11.00)',
                    'css_class' => 'offset-md-1 col-md-10 custom-module',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'accordeon',
                        'slot' => 'slide1',
                        'en_config' => '{"header": {"value": "Ipsum Lorem  dolor sit amet", "source": "static"}, "content": {"value": "<p class=\\"text-lg\\">\\n                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.\\n                        </p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'accordeon',
                        'slot' => 'slide2',
                        'en_config' => '{"header": {"value": "Ipsum Lorem  dolor sit amet", "source": "static"}, "content": {"value": "<p class=\\"text-lg\\">\\n                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.\\n                        </p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-',
                        'type' => 'accordeon',
                        'slot' => 'slide3',
                        'en_config' => '{"header": {"value": "Ipsum Lorem  dolor sit amet", "source": "static"}, "content": {"value": "<p class=\\"text-lg\\">\\n                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.\\n                        </p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-4',
                        'type' => 'accordeon',
                        'slot' => 'slide4',
                        'en_config' => '{"header": {"value": "Ipsum Lorem  dolor sit amet", "source": "static"}, "content": {"value": "<p class=\\"text-lg\\">\\n                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.\\n                        </p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-5',
                        'type' => 'accordeon',
                        'slot' => 'slide5',
                        'en_config' => '{"header": {"value": "Ipsum Lorem  dolor sit amet", "source": "static"}, "content": {"value": "<p class=\\"text-lg\\">\\n                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.\\n                        </p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-6',
                        'type' => 'accordeon',
                        'slot' => 'slide6',
                        'en_config' => '{"header": {"value": "Ipsum Lorem  dolor sit amet", "source": "static"}, "content": {"value": "<p class=\"text-lg\">\n                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.\n                        </p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
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
                    'position' => 3,
                    'type' => 'text',
                    'name' => '',
                    'css_class' => 'col-sm-12',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'content',
                        'en_config' => '{"content": {"value": "<div style=\\"text-align: center;\\" >\\n<div class=\\"button-group\\" >\\n\\t\\t<a class=\\"btn\\n\\t\\t              btn-link\\" href=\\"https://www.burmester.de\\">\\n\\t\\t\\tZu unserem Support-Center\\n\\t\\t</a>\\n</div>\\n</div>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );


    }
}
