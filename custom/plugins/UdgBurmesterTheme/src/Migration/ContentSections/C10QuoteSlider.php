<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C10QuoteSlider implements ContentSectionInterface
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
                'cms_section_name' => 'C10QuoteSlider',
                'position' => $position,
                'css_class' => 'custom-module component-quoteslider theme-taube-dark',
            ]
        );

        $cmsBlockId = $cmsSectionId . '-1';
        CmsSection::createCmsBlockWithElements(
            $connection,
            [

                'cmsBlock' => [
                    'cms_section_id' => $cmsSectionId,
                    'cms_block_id' => $cmsBlockId,
                    'type' => 'text-slider',
                    'name' => '(C10.00)',
                    'css_class' => 'custom-module',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'slide1',
                        'en_config' => '{"content": {"value": "<h3 class=\\"text-quote\\">1. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid assumenda blanditiis est quidem voluptatum.\\n                        <\\/h3>\\n                        <p class=\\"chapter-secondary\\">\\n                            Lorem ipsum\\n                        <\\/p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'text',
                        'slot' => 'slide2',
                        'en_config' => '{"content": {"value": "<h3 class=\\"text-quote\\">2. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid assumenda blanditiis est quidem voluptatum.\\n                        <\\/h3>\\n                        <p class=\\"chapter-secondary\\">\\n                            Lorem ipsum\\n                        <\\/p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-3',
                        'type' => 'text',
                        'slot' => 'slide3',
                        'en_config' => '{"content": {"value": "<h3 class=\\"text-quote\\">3. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid assumenda blanditiis est quidem voluptatum.\\n                        <\\/h3>\\n                        <p class=\\"chapter-secondary\\">\\n                            Lorem ipsum\\n                        <\\/p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
