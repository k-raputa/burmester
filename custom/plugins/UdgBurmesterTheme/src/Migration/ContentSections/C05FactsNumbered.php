<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C05FactsNumbered implements ContentSectionInterface
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
                'cms_section_name' => 'C05FactsNumbered',
                'position' => $position,
                'css_class' => 'component-facts-numbered',
            ]
        );

        $cmsBlockId = $cmsSectionId . '-1';
        CmsSection::createCmsBlockWithElements(
            $connection,
            [

                'cmsBlock' => [
                    'cms_section_id' => $cmsSectionId,
                    'cms_block_id' => $cmsBlockId,
                    'type' => 'text-three-column',
                    'name' => '(C05.00)',
                    'css_class' => 'offset-md-1 col-md-10',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'center',
                        'en_config' => '{"content":{"source":"static","value":"<div class=\\"fact-number\"><p class=\\"number h2 headline-primary\\">948<\\/p>\\n<p class=\\"fact chapter-secondary\\">Gefertigte Einzelteile<\\/p><\\/div>"},"verticalAlign":{"source":"static","value":null}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'text',
                        'slot' => 'right',
                        'en_config' => '{"content":{"source":"static","value":"<div class=\\"fact-number\"><p class=\\"number h2 headline-primary\\">48<br><\\/p>\\n<p class=\\"fact chapter-secondary\\">Qualitätsstandards<\\/p><\\/div>"},"verticalAlign":{"source":"static","value":null}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-3',
                        'type' => 'text',
                        'slot' => 'left',
                        'en_config' => '{"content":{"source":"static","value":"<div class=\\"fact-number\"><p class=\\"number h2 headline-primary\\">\\n\\t1.000\\n<\\/p>\\n<p class=\\"fact chapter-secondary\\">\\n\\tArbeitsstunden pro Kophörer Lorem ipsum dolor sit\\n<\\/p><\\/div>"},"verticalAlign":{"source":"static","value":null}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
