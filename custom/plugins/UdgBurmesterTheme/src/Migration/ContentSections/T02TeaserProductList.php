<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsProduct;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class T02TeaserProductList implements ContentSectionInterface
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
                'cms_section_name' => 'T02TeaserProductList',
                'position' => $position,
                'css_class' => 'theme-silver-grey-light',
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
                    'type' => 'text-three-column',
                    'name' => '',
                    'css_class' => ' cms-block-product-three-column',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'product-box',
                        'slot' => 'left',
                        'en_config' => '{"product": {"value": "' . CmsProduct::getDemoProductIdByProductNumber($connection, '1A') . '", "source": "static"}, "boxLayout": {"value": "standard", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'product-box',
                        'slot' => 'center',
                        'en_config' => '{"product": {"value": "' . CmsProduct::getDemoProductIdByProductNumber($connection, '101B') . '", "source": "static"}, "boxLayout": {"value": "standard", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-3',
                        'type' => 'product-box',
                        'slot' => 'right',
                        'en_config' => '{"product": {"value": "' . CmsProduct::getDemoProductIdByProductNumber($connection, '201C') . '", "source": "static"}, "boxLayout": {"value": "standard", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
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
                    'css_class' => ' cms-block-product-three-column',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'product-box',
                        'slot' => 'left',
                        'en_config' => '{"product": {"value": "' . CmsProduct::getDemoProductIdByProductNumber($connection, '301D') . '", "source": "static"}, "boxLayout": {"value": "standard", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'text',
                        'slot' => 'center',
                        'en_config' => '{"content": {"value": "", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-3',
                        'type' => 'text',
                        'slot' => 'right',
                        'en_config' => '{"content": {"value": "", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
