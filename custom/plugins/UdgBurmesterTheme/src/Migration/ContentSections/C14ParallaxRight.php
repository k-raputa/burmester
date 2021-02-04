<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsMedia;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class C14ParallaxRight implements ContentSectionInterface
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
                'css_class'   => 'component-parallax',
                'sizing_mode' => 'boxed',
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'C14ParallaxRight',
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
                    'type' => 'parallax',
                    'name' => '',
                    'css_class'   => 'parallax image-right',
                    'margin_bottom' => '0',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'image',
                        'slot' => 'left',
                        'en_config' => '{"url": {"value": null, "source": "static"}, "media": {"value": "'.CmsMedia::getDemoMediaIdByName($connection, 'DEMO_750x500').'", "source": "static"}, "newTab": {"value": false, "source": "static"}, "minHeight": {"value": "340px", "source": "static"}, "displayMode": {"value": "standard", "source": "static"}, "verticalAlign": {"value": "flex-end", "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                    [
                        'cms_slot_id' => $cmsBlockId . '-2',
                        'type' => 'text',
                        'slot' => 'right',
                        'en_config' => '{"content": {"value": "<p class=\\"h2\\">Lorem ipsum dolor sit aem ipsum dolor sit amet.</p>\\n<p class=\\"text-lg\\">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolLorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolLorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolLorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolLorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.</p>\\n<p class=\\"h2\\">Lorem ipsum dolor sit amet.</p>\\n<p class=\\"text-lg\\">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.orem ipsum dolor sit amet, consectetur adipisicing elit. Commodi expedita fugit laborum maiores quo saepe.</p>\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );

    }
}
