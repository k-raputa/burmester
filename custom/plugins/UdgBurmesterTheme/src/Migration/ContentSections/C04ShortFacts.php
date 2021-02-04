<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C04ShortFacts implements ContentSectionInterface
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
                'cms_section_name' => 'C04ShortFacts',
                'position' => $position,
                'css_class' => 'component-short-facts',
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
                    'css_class' => 'offset-md-1 col-md-8 facts-headline',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'content',
                        'en_config' => '{"content": {"value": "<p class=\\"chapter-default\\">\\n\\tKeyfacts\\n</p>\\n<h1>\\n\\tUnser Anspruch ist es, das Maß an Qualität immer wieder aufs Neue zu übertreffen. Jede einzelne Baugruppe erhält vor dem Einbau eine 100-%-Prüfung Lorem nach Burmester-Qualitätsstandards.\\n</h1>\\n\\t\\t\\t\\t\\t\\t\\t", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
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
                    'type' => 'text-three-column',
                    'name' => '',
                    'css_class' => 'offset-md-1 col-md-10 facts-short',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'left',
                        'en_config' => '{"content": {"value": "<h2>\\n\\tTrue Sound ®\\n</h2>\\n<p>\\n\\tUnsere eigens entwickelte True Sound Technologie bringt selbst feine Nuancen Lorem Ipsum uismod bibendum laoreet.\\n</p>\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'text',
                        'slot' => 'center',
                        'en_config' => '{"content": {"value": "<h2>\\n    Planar-Magnetisch\\n</h2>\\n<p>\\nExtreme Resistenz gegen elektronische und akustische Verzerrungen aller Art durch unsere Planar-Magnettreiber.\\n</p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-3',
                        'type' => 'text',
                        'slot' => 'right',
                        'en_config' => '{"content": {"value": "<h2>\\n\\tResonance+ ®\\n</h2>\\n<p>\\n\\tUnsere eigens entwickelte Resonance+ Technologie sorgt für die perfekte Resonanz und damit den perfekten Klang.\\n</p>\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
