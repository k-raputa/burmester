<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C02ImageTableRight implements ContentSectionInterface
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
                'css_class'   => 'component-image-text-video theme-pearl-bush',
                'sizing_mode' => 'boxed',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'C02ImageTableRight',
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
                    'type' => 'image-text',
                    'name' => '',
                    'css_class'   => 'col-12 image-video-container image-right',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'text',
                        'slot' => 'left',
                        'en_config' => '{"content": {"value": "<p class=\\"chapter-secondary\\">Technische Daten</p>\\n<table class=\\"sw-text-editor-table\\"><tbody class=\\"sw-text-editor-table__body\\">\\n\\t\\t<tr class=\\"sw-text-editor-table__row\\">\\n\\t\\t\\t<th class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>DIGITAL-Eingänge<br></th>\\n\\t\\t\\t<td class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>1 x LWL<br>1 x RCA<br></td>\\n\\t\\t</tr>\\n\\t\\t<tr class=\\"sw-text-editor-table__row\\">\\n\\t\\t\\t<th class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>DIGITAL-Eingänge<br></th>\\n\\t\\t\\t<td class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>1 x LWL<br>1 x RCA<br></td>\\n\\t\\t</tr>\\n\\t\\t<tr class=\\"sw-text-editor-table__row\\">\\n\\t\\t\\t<th class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>DIGITAL-Eingänge<br></th>\\n\\t\\t\\t<td class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>1 x LWL<br>1 x RCA<br></td>\\n\\t\\t</tr>\\n\\t\\t<tr class=\\"sw-text-editor-table__row\\">\\n\\t\\t\\t<th class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>DIGITAL-Eingänge<br></th>\\n\\t\\t\\t<td class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>1 x LWL<br>1 x RCA<br></td>\\n\\t\\t</tr>\\n\\t\\t<tr class=\\"sw-text-editor-table__row\\">\\n\\t\\t\\t<th class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>DIGITAL-Eingänge<br></th>\\n\\t\\t\\t<td class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>1 x LWL<br>1 x RCA<br></td>\\n\\t\\t</tr>\\n\\t\\t<tr class=\\"sw-text-editor-table__row\\">\\n\\t\\t\\t<th class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>DIGITAL-Eingänge<br></th>\\n\\t\\t\\t<td class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>1 x LWL<br>1 x RCA<br></td>\\n\\t\\t</tr>\\n\\t\\t<tr class=\\"sw-text-editor-table__row\\">\\n\\t\\t\\t<th class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>DIGITAL-Eingänge<br></th>\\n\\t\\t\\t<td class=\\"sw-text-editor-table__col\\"><div class=\\"sw-text-editor-table__col-selector\\" contenteditable=\\"false\\"></div>1 x LWL<br>1 x RCA<br></td>\\n\\t\\t</tr>\\n</tbody></table>\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'image',
                        'slot' => 'right',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_600x600').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
